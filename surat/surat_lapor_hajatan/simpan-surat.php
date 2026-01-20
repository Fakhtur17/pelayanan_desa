<?php
include('../../config/koneksi.php');

if (isset($_POST['submit'])) {

  $jenis_surat     = "Surat Lapor Hajatan";
  $nik             = mysqli_real_escape_string($connect, $_POST['fnik']);

  // nomor bukti (opsional)
  $bukti_ktp = !empty($_POST['fbukti_ktp']) ? mysqli_real_escape_string($connect, $_POST['fbukti_ktp']) : NULL;
  $bukti_kk  = !empty($_POST['fbukti_kk'])  ? mysqli_real_escape_string($connect, $_POST['fbukti_kk'])  : NULL;

  $jenis_hajat     = mysqli_real_escape_string($connect, $_POST['fjenis_hajat']);
  $hari            = mysqli_real_escape_string($connect, $_POST['fhari']);
  $tanggal         = mysqli_real_escape_string($connect, $_POST['ftanggal']); // format: YYYY-MM-DD
  $jenis_hiburan   = mysqli_real_escape_string($connect, $_POST['fjenis_hiburan']);
  $pemilik         = mysqli_real_escape_string($connect, $_POST['fpemilik']);
  $alamat_pemilik  = mysqli_real_escape_string($connect, $_POST['falamat_pemilik']);

  $status_surat    = "PENDING";
  $id_profil_desa  = 1;

  // ============ FUNGSI UPLOAD (mirip surat kematian) ============
  function uploadFile($fileField, $uploadDir, $prefix){
    if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
      return NULL;
    }

    $namaFile = $_FILES[$fileField]['name'];
    $tmpFile  = $_FILES[$fileField]['tmp_name'];

    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','pdf'];

    if (!in_array($ext, $allowed)) {
      return NULL;
    }

    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $newName = $prefix . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
    $dest = rtrim($uploadDir,'/').'/'.$newName;

    if (move_uploaded_file($tmpFile, $dest)) {
      return $newName;
    }
    return NULL;
  }

  // Folder upload (sesuaikan kalau struktur foldermu beda)
  $dirKtp = "../../uploads/persyaratan_surat_lapor_hajatan/ktp/";
  $dirKk  = "../../uploads/persyaratan_surat_lapor_hajatan/kk/";

  // Upload
  $file_ktp = uploadFile('ktp', $dirKtp, 'KTP');
  $file_kk  = uploadFile('kk',  $dirKk,  'KK');

  // validasi: KTP wajib
  if ($file_ktp === NULL) {
    header("location:index.php?pesan=gagal");
    exit;
  }

  // tanggal ke DATETIME (biar cocok kolom `tanggal` datetime)
  $tanggal_db = $tanggal . " 00:00:00";

  // ============ INSERT ============
  $sql = "
    INSERT INTO surat_lapor_hajatan
    (jenis_surat, no_surat, nik,
     bukti_ktp, file_ktp, bukti_kk, file_kk,
     jenis_hajat, hari, tanggal, jenis_hiburan, pemilik, alamat_pemilik,
     status_surat, id_profil_desa)
    VALUES
    ('$jenis_surat', NULL, '$nik',
     ".($bukti_ktp ? "'$bukti_ktp'" : "NULL").", ".($file_ktp ? "'$file_ktp'" : "NULL").",
     ".($bukti_kk  ? "'$bukti_kk'"  : "NULL").", ".($file_kk  ? "'$file_kk'"  : "NULL").",
     '$jenis_hajat', '$hari', '$tanggal_db', '$jenis_hiburan', '$pemilik', '$alamat_pemilik',
     '$status_surat', '$id_profil_desa')
  ";

  $ok = mysqli_query($connect, $sql);

  if ($ok) {
    header("location:index.php?pesan=berhasil");
    exit;
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($connect);
  }
}
?>
