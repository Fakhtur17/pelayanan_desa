<?php
session_start();
include ('../../config/koneksi.php');

if (!isset($_POST['submit'])) {
  http_response_code(400);
  die("Akses tidak valid.");
}

$nik = $_POST['fnik'];

// ✅ CEK pemohon masih ada?
$qCek = mysqli_query($connect, "SELECT nik FROM penduduk WHERE nik='$nik' LIMIT 1");
if (!$qCek || mysqli_num_rows($qCek) == 0) {
  header("location:index.php?pesan=gagal");
  exit;
}

// ✅ CEK: jangan izinkan buat baru kalau masih ada paket PENDING untuk NIK yang sama
$cek = mysqli_query($connect, "
  SELECT 1 FROM surat_pendaftaran_pencetakan_kk_kelahiran
  WHERE nik='$nik' AND status_surat='PENDING'
  LIMIT 1
");
if ($cek && mysqli_num_rows($cek) > 0) {
  echo "<script>
    alert('Masih ada pengajuan paket Akta + KK yang PENDING. Silakan tunggu sampai selesai.');
    window.location.href='index.php?pesan=pending';
  </script>";
  exit;
}

// ===== SYARAT BELUM KAWIN (checkbox) =====
if (!isset($_POST['fbelum_kawin']) || $_POST['fbelum_kawin'] !== '1') {
  echo "Gagal: Anda wajib menyetujui pernyataan 'anak belum kawin dan umur < 17 tahun'.";
  exit;
}

// ===== VALIDASI UMUR ANAK < 17 TAHUN =====
$tgl_lahir_bayi = $_POST['ftgl_lahir_bayi'];
$today = new DateTime(date('Y-m-d'));
$born  = new DateTime($tgl_lahir_bayi);
$age   = $born->diff($today)->y;

if ($age >= 17) {
  echo "Gagal: Umur anak harus kurang dari 17 tahun.";
  exit;
}

// ===== UPLOAD (format sama seperti contoh kamu) =====
function uploadFileAman($field, $uploadDir) {
  if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return NULL;

  $namaFile = $_FILES[$field]['name'];
  $tmpFile  = $_FILES[$field]['tmp_name'];
  $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

  $allowed = ['jpg','jpeg','png','pdf'];
  if (!in_array($ext, $allowed)) return NULL;

  $maxSize = 2 * 1024 * 1024;
  if ($_FILES[$field]['size'] > $maxSize) return NULL;

  if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

  $newName = $field . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
  $dest    = rtrim($uploadDir, "/") . "/" . $newName;

  return move_uploaded_file($tmpFile, $dest) ? $newName : NULL;
}

$uploadDirAkta = "../../uploads/akta_kelahiran/";
$uploadDirKK   = "../../uploads/kk_kelahiran/";

// ===== DATA AKTA =====
$jenis_surat_akta = "Surat Pengajuan Akta Kelahiran";
$nama_bayi = addslashes($_POST['fnama_bayi']);
$jk_bayi   = addslashes($_POST['fjenis_kelamin_bayi']);
$tempat_lahir_bayi = addslashes($_POST['ftempat_lahir_bayi']);
$jam_lahir_bayi = !empty($_POST['fjam_lahir_bayi']) ? addslashes($_POST['fjam_lahir_bayi']) : NULL;
$anak_ke   = !empty($_POST['fanak_ke']) ? addslashes($_POST['fanak_ke']) : NULL;
$berat_bayi = !empty($_POST['fberat_bayi']) ? addslashes($_POST['fberat_bayi']) : NULL;
$panjang_bayi = !empty($_POST['fpanjang_bayi']) ? addslashes($_POST['fpanjang_bayi']) : NULL;

$nik_ayah  = addslashes($_POST['fnik_ayah']);
$nama_ayah = addslashes($_POST['fnama_ayah']);
$nik_ibu   = addslashes($_POST['fnik_ibu']);
$nama_ibu  = addslashes($_POST['fnama_ibu']);

$alamat_pemohon = !empty($_POST['falamat_pemohon']) ? addslashes($_POST['falamat_pemohon']) : NULL;
$keterangan = "Paket Akta Kelahiran + Pencetakan KK karena kelahiran";

$surat_kelahiran_rs = uploadFileAman('fsurat_kelahiran_rs', $uploadDirAkta);
$fc_buku_nikah      = uploadFileAman('ffc_buku_nikah', $uploadDirAkta);
$kk_pemohon         = uploadFileAman('fkk_pemohon', $uploadDirAkta);
$ktp_ayah_file      = uploadFileAman('fktp_ayah', $uploadDirAkta);
$ktp_ibu_file       = uploadFileAman('fktp_ibu', $uploadDirAkta);
$dok_lain           = uploadFileAman('fdokumen_pendukung_lain', $uploadDirAkta);

if (!$surat_kelahiran_rs || !$kk_pemohon || !$ktp_ayah_file || !$ktp_ibu_file) {
  echo "Gagal: Berkas akta wajib terupload (Surat Kelahiran, KK Pemohon, KTP Ayah, KTP Ibu). Format JPG/PNG/PDF max 2MB.";
  exit;
}

// ===== DATA KK =====
$jenis_surat_kk = "Surat Pendaftaran dan Pencetakan KK Karena Kelahiran";
$no_kk_lama     = addslashes($_POST['fno_kk_lama']);
$nama_kk        = addslashes($_POST['fnama_kepala_keluarga']);
$keperluan_kk   = addslashes($_POST['fkeperluan_kk']);

$kk_lama_file   = uploadFileAman('fkk_lama', $uploadDirKK);
$ktp_pemohon_file = uploadFileAman('fktp_pemohon', $uploadDirKK);

if (!$kk_lama_file) {
  echo "Gagal: Scan KK lama wajib diupload (JPG/PNG/PDF max 2MB).";
  exit;
}

$catatan_autentik = "Dokumen membutuhkan verifikasi dan/atau tanda tangan basah. Pengambilan surat dilakukan di Balai Desa.";
$status_surat   = "PENDING";
$id_profil_desa = "1";

// ===== TRANSAKSI: insert akta -> ambil id_spak -> insert kk =====
mysqli_begin_transaction($connect);

try {
  // INSERT AKTA
  $qAkta = "
    INSERT INTO surat_pengajuan_akta_kelahiran
    (jenis_surat, no_surat, nik, nama_bayi, jenis_kelamin_bayi, tempat_lahir_bayi, tgl_lahir_bayi,
     jam_lahir_bayi, anak_ke, berat_bayi, panjang_bayi,
     nik_ayah, nama_ayah, nik_ibu, nama_ibu,
     alamat_pemohon, surat_kelahiran_rs, fc_buku_nikah, kk_pemohon, ktp_ayah, ktp_ibu,
     dokumen_pendukung_lain, keterangan, id_pejabat_desa, status_surat, id_profil_desa)
    VALUES
    ('$jenis_surat_akta', NULL, '$nik',
     '$nama_bayi', '$jk_bayi', '$tempat_lahir_bayi', '$tgl_lahir_bayi',
     ".($jam_lahir_bayi ? "'$jam_lahir_bayi'" : "NULL").",
     ".($anak_ke ? "'$anak_ke'" : "NULL").",
     ".($berat_bayi ? "'$berat_bayi'" : "NULL").",
     ".($panjang_bayi ? "'$panjang_bayi'" : "NULL").",
     '$nik_ayah', '$nama_ayah', '$nik_ibu', '$nama_ibu',
     ".($alamat_pemohon ? "'$alamat_pemohon'" : "NULL").",
     '$surat_kelahiran_rs',
     ".($fc_buku_nikah ? "'$fc_buku_nikah'" : "NULL").",
     '$kk_pemohon',
     '$ktp_ayah_file',
     '$ktp_ibu_file',
     ".($dok_lain ? "'$dok_lain'" : "NULL").",
     '$keterangan',
     NULL, '$status_surat', '$id_profil_desa'
    )
  ";

  $okAkta = mysqli_query($connect, $qAkta);
  if (!$okAkta) {
    throw new Exception("Gagal simpan akta: " . mysqli_error($connect));
  }

  $id_spak = mysqli_insert_id($connect);

  // INSERT KK (PAKET)
  $qKK = "
    INSERT INTO surat_pendaftaran_pencetakan_kk_kelahiran
    (jenis_surat, no_surat, nik, id_spak,
     no_kk_lama, nama_kepala_keluarga, alasan, keperluan,
     kk_lama, ktp_pemohon,
     catatan_autentik, id_pejabat_desa, status_surat, id_profil_desa)
    VALUES
    ('$jenis_surat_kk', NULL, '$nik', '$id_spak',
     '$no_kk_lama', '$nama_kk',
     'Penambahan anggota keluarga karena kelahiran',
     '$keperluan_kk',
     '$kk_lama_file',
     ".($ktp_pemohon_file ? "'$ktp_pemohon_file'" : "NULL").",
     '$catatan_autentik',
     NULL, '$status_surat', '$id_profil_desa'
    )
  ";

  $okKK = mysqli_query($connect, $qKK);
  if (!$okKK) {
    throw new Exception("Gagal simpan KK: " . mysqli_error($connect));
  }

  mysqli_commit($connect);

  header("location:../index.php?pesan=berhasil");
  exit;

} catch (Exception $e) {
  mysqli_rollback($connect);
  echo "Gagal: " . $e->getMessage();
}
