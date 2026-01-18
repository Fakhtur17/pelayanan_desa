<?php
include ('../../config/koneksi.php');

if (isset($_POST['submit'])) {

  $jenis_surat    = "Surat Perubahan Nama Capil";
  $nik            = $_POST['fnik'];
  $keperluan      = addslashes($_POST['fkeperluan']);
  $status_surat   = "PENDING";
  $id_profil_desa = "1";

  // ✅ CEK: jangan izinkan buat baru kalau masih ada yg PENDING untuk NIK yang sama
  $cek = mysqli_query($connect, "
      SELECT 1
      FROM surat_perubahan_nama_capil
      WHERE nik='$nik' AND status_surat='PENDING'
      LIMIT 1
  ");

  if ($cek && mysqli_num_rows($cek) > 0) {
      echo "<script>
          alert('Masih ada pengajuan Perubahan Nama Capil yang PENDING. Silakan tunggu sampai dikonfirmasi / selesai dulu.');
          window.location.href='../index.php';
      </script>";
      exit;
  }

  $alamat_pemohon = !empty($_POST['falamat_pemohon']) ? addslashes($_POST['falamat_pemohon']) : NULL;

  $nama_lama = addslashes($_POST['fnama_lama']);
  $nama_baru = addslashes($_POST['fnama_baru']);
  $alasan    = !empty($_POST['falasan']) ? addslashes($_POST['falasan']) : NULL;

  $catatan_autentik = "Dokumen membutuhkan verifikasi dan/atau tanda tangan basah. Pengambilan surat dilakukan di Balai Desa.";

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

  $uploadDir = "../../uploads/perubahan_nama_capil/";

  $file_kutipan_akta   = uploadFileAman('ffile_kutipan_akta', $uploadDir);
  $file_kk_termohon    = uploadFileAman('ffile_kk_termohon', $uploadDir);
  $file_ijazah_rujukan  = uploadFileAman('ffile_ijazah_rujukan', $uploadDir);
  $file_sptjm_kebenaran = uploadFileAman('ffile_sptjm_kebenaran', $uploadDir);

  if (!$file_kutipan_akta || !$file_kk_termohon || !$file_ijazah_rujukan || !$file_sptjm_kebenaran) {
    echo "Gagal: Pastikan semua file wajib terupload (format JPG/PNG/PDF, maksimal 2MB).";
    exit;
  }

  $no_tanda_terima = "TT-CA-" . date('Ymd') . "-" . rand(100000, 999999);

  $q = "
    INSERT INTO surat_perubahan_nama_capil
    (jenis_surat, nik,
     nama_lama, nama_baru, alasan_perubahan,
     file_kutipan_akta, file_kk_termohon, file_ijazah_rujukan, file_sptjm_kebenaran,
     no_tanda_terima,
     keperluan, alamat_pemohon,
     catatan_autentik,
     status_surat, id_profil_desa)
    VALUES
    ('$jenis_surat', '$nik',
     '$nama_lama', '$nama_baru',
     ".($alasan ? "'$alasan'" : "NULL").",
     '$file_kutipan_akta', '$file_kk_termohon', '$file_ijazah_rujukan', '$file_sptjm_kebenaran',
     '$no_tanda_terima',
     '$keperluan',
     ".($alamat_pemohon ? "'$alamat_pemohon'" : "NULL").",
     '$catatan_autentik',
     '$status_surat', '$id_profil_desa')
  ";

  $ok = mysqli_query($connect, $q);

  if ($ok) {
    header("location:../index.php?pesan=berhasil");
    exit;
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($connect);
  }
}
?>
