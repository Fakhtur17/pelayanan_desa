<?php
include ('../../config/koneksi.php');

if (isset($_POST['submit'])) {

  $jenis_surat  = "Surat Keterangan Usaha";
  $nik          = mysqli_real_escape_string($connect, $_POST['fnik']);
  $usaha        = mysqli_real_escape_string($connect, $_POST['fusaha']);
  $alamat_usaha = mysqli_real_escape_string($connect, $_POST['falamat_usaha']);
  $keperluan    = mysqli_real_escape_string($connect, $_POST['fkeperluan']);

  $status_surat   = "PENDING";
  $id_profil_desa = 1;

  // =========================
  // FUNGSI UPLOAD (mirip surat kematian tapi bisa prefix)
  // =========================
  function uploadFile($fileField, $uploadDir, $prefix = 'FILE') {
    if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] != UPLOAD_ERR_OK) {
      return NULL;
    }

    $namaFile = $_FILES[$fileField]['name'];
    $tmpFile  = $_FILES[$fileField]['tmp_name'];
    $ext      = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    $allowed = array('jpg','jpeg','png','pdf');
    if (!in_array($ext, $allowed)) {
      return NULL;
    }

    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    // format nama file biar rapi + unik
    $newName = $prefix . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
    $dest    = rtrim($uploadDir, '/').'/'.$newName;

    if (move_uploaded_file($tmpFile, $dest)) {
      return $newName;
    }
    return NULL;
  }

  // =========================
  // FOLDER UPLOAD (SKU)
  // simpan terpisah: /ktp dan /kk
  // =========================
  $baseUpload = "../../uploads/persyaratan_surat_keterangan_usaha/";
  $dirKTP = $baseUpload . "ktp/";
  $dirKK  = $baseUpload . "kk/";

  // =========================
  // UPLOAD FILE
  // =========================
  $ktp_pemohon = uploadFile('ktp_pemohon', $dirKTP, "KTP_".$nik);
  $kk_pemohon  = uploadFile('kk_pemohon',  $dirKK,  "KK_".$nik);

  // Kalau wajib, pastikan benar-benar ada (double-safety)
  if (!$ktp_pemohon || !$kk_pemohon) {
    header("location:index.php?pesan=gagal");
    exit;
  }

  // =========================
  // INSERT KE TABEL SKU
  // =========================
  $qTambahSurat = "
    INSERT INTO surat_keterangan_usaha
    (jenis_surat, no_surat, nik, usaha, alamat_usaha, keperluan, ktp_pemohon, kk_pemohon, status_surat, id_profil_desa)
    VALUES
    ('$jenis_surat', NULL, '$nik', '$usaha', '$alamat_usaha', '$keperluan', '$ktp_pemohon', '$kk_pemohon', '$status_surat', '$id_profil_desa')
  ";

  $TambahSurat = mysqli_query($connect, $qTambahSurat);

  if ($TambahSurat) {
    header("location:index.php?pesan=berhasil");
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($connect);
  }
}
?>
