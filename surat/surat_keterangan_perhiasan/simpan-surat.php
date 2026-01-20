<?php
include ('../../config/koneksi.php');

if (!isset($_POST['submit'])) {
  header("location:index.php");
  exit;
}

$jenis_surat = "Surat Keterangan Perhiasan";
$nik = mysqli_real_escape_string($connect, $_POST['fnik']);

$jenis_perhiasan = mysqli_real_escape_string($connect, $_POST['fjenis_perhiasan']);
$nama_perhiasan  = mysqli_real_escape_string($connect, $_POST['fnama_perhiasan']);
$berat           = mysqli_real_escape_string($connect, $_POST['fberat']);
$toko_perhiasan  = mysqli_real_escape_string($connect, $_POST['ftoko_perhiasan']);
$lokasi_toko     = mysqli_real_escape_string($connect, $_POST['flokasi_toko_perhiasan']);
$keperluan       = mysqli_real_escape_string($connect, $_POST['fkeperluan']);

$status_surat   = "PENDING";
$id_profil_desa = 1;

// ====== CEK NIK ADA ======
$qCekNik = mysqli_query($connect, "SELECT nik FROM penduduk WHERE nik='$nik' LIMIT 1");
if(!$qCekNik || mysqli_num_rows($qCekNik) == 0){
  header("location:index.php?pesan=gagal");
  exit;
}

// ====== FUNGSI UPLOAD (AMAN) ======
function uploadFile($fileField, $uploadDir, $prefix){
  if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
    return NULL;
  }

  $origName = $_FILES[$fileField]['name'];
  $tmpName  = $_FILES[$fileField]['tmp_name'];

  $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
  $allowed = ['jpg','jpeg','png','pdf','webp'];

  if (!in_array($ext, $allowed)) {
    return NULL;
  }

  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  // nama file baru (unik + aman)
  $safeNik = preg_replace('/[^0-9]/', '', $prefix); // prefix boleh nik
  $newName = $fileField . '_' . $safeNik . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;

  $dest = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $newName;

  if (move_uploaded_file($tmpName, $dest)) {
    return $newName;
  }

  return NULL;
}

// ====== FOLDER UPLOAD ======
$dirKtp = "../../uploads/persyaratan_surat_keterangan_perhiasan/ktp/";
$dirKk  = "../../uploads/persyaratan_surat_keterangan_perhiasan/kk/";

// ====== UPLOAD FILE ======
$fileKtp = uploadFile('ktp', $dirKtp, $nik);
$fileKk  = uploadFile('kk',  $dirKk,  $nik);

// kalau wajib, pastikan tidak null
if(empty($fileKtp) || empty($fileKk)){
  echo "<script>alert('Upload KTP/KK gagal atau tipe file tidak diizinkan. Pastikan file .jpg/.png/.pdf');window.history.back();</script>";
  exit;
}

// ====== INSERT ======
$qTambahSurat = "
  INSERT INTO surat_keterangan_perhiasan
  (jenis_surat, no_surat, nik,
   jenis_perhiasan, nama_perhiasan, berat,
   toko_perhiasan, lokasi_toko_perhiasan,
   keperluan, ktp, kk,
   status_surat, id_profil_desa)
  VALUES
  ('$jenis_surat', NULL, '$nik',
   '$jenis_perhiasan', '$nama_perhiasan', '$berat',
   '$toko_perhiasan', '$lokasi_toko',
   '$keperluan', '$fileKtp', '$fileKk',
   '$status_surat', '$id_profil_desa')
";

$TambahSurat = mysqli_query($connect, $qTambahSurat);

if ($TambahSurat) {
  header("location:index.php?pesan=berhasil");
  exit;
} else {
  echo "Gagal menyimpan data: " . mysqli_error($connect);
  exit;
}
