<?php
include ('../../config/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $jenis_surat    = "Surat Keterangan";
  $nik            = mysqli_real_escape_string($connect, $_POST['fnik']);
  $keperluan      = mysqli_real_escape_string($connect, $_POST['fkeperluan']);
  $status_surat   = "PENDING";
  $id_profil_desa = 1;

  // cek nik ada
  $qCekNik = mysqli_query($connect, "SELECT nik FROM penduduk WHERE nik='$nik' LIMIT 1");
  if (!$qCekNik || mysqli_num_rows($qCekNik) == 0) {
    header("location:index.php?pesan=gagal");
    exit;
  }

  function uploadFile($fileField, $uploadDir, $prefix = 'FILE') {

    if (!isset($_FILES[$fileField])) {
      return "ERROR_NO_FILE";
    }

    if ($_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
      return "ERROR_UPLOAD_" . $_FILES[$fileField]['error'];
    }

    $namaFile = $_FILES[$fileField]['name'];
    $tmpFile  = $_FILES[$fileField]['tmp_name'];
    $size     = $_FILES[$fileField]['size'];
    $ext      = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    if ($size > 2 * 1024 * 1024) return "ERROR_SIZE";

    $allowed = ['jpg','jpeg','png','pdf'];
    if (!in_array($ext, $allowed)) return "ERROR_EXT";

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $newName = $prefix . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
    $dest = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $newName;

    if (move_uploaded_file($tmpFile, $dest)) {
      file_put_contents(__DIR__ . "/debug_upload_path.txt", "SUKSES: $dest\n", FILE_APPEND);
      return $newName;
    }

    file_put_contents(__DIR__ . "/debug_upload_path.txt", "GAGAL MOVE: $dest\n", FILE_APPEND);
    return "ERROR_MOVE";
  }

  $uploadDirKTP = __DIR__ . "/../../uploads/persyaratan_surat_keterangan/ktp/";
  $uploadDirKK  = __DIR__ . "/../../uploads/persyaratan_surat_keterangan/kk/";

  $ktp_pemohon = uploadFile('file_ktp', $uploadDirKTP, "KTP_" . $nik);
  if (strpos($ktp_pemohon, "ERROR_") === 0) {
    die("<script>alert('Upload KTP gagal: $ktp_pemohon');history.back();</script>");
  }

  $kk_pemohon = uploadFile('file_kk', $uploadDirKK, "KK_" . $nik);
  if (strpos($kk_pemohon, "ERROR_") === 0) {
    @unlink($uploadDirKTP . $ktp_pemohon);
    die("<script>alert('Upload KK gagal: $kk_pemohon');history.back();</script>");
  }

  $qTambahSurat = "
    INSERT INTO surat_keterangan
      (jenis_surat, nik, keperluan, ktp_pemohon, kk_pemohon, status_surat, id_profil_desa)
    VALUES
      ('$jenis_surat', '$nik', '$keperluan', '$ktp_pemohon', '$kk_pemohon', '$status_surat', '$id_profil_desa')
  ";

  $TambahSurat = mysqli_query($connect, $qTambahSurat);

  if ($TambahSurat) {
    header("location:../index.php?pesan=berhasil");
    exit;
  } else {
    @unlink($uploadDirKTP . $ktp_pemohon);
    @unlink($uploadDirKK . $kk_pemohon);
    die("<script>alert('Gagal simpan surat: ".mysqli_error($connect)."');history.back();</script>");
  }
}
?>
