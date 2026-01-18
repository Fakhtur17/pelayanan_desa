<?php
include ('../../config/koneksi.php');

if (isset($_POST['submit'])) {

  $jenis_surat    = "Surat Keterangan Akta Kematian";
  $nik            = mysqli_real_escape_string($connect, $_POST['fnik']);

  // DATA ALMARHUM
  $nama_almarhum    = mysqli_real_escape_string($connect, $_POST['fnama_almarhum']);
  $tgl_meninggal    = mysqli_real_escape_string($connect, $_POST['ftgl_meninggal']);
  $tempat_meninggal = mysqli_real_escape_string($connect, $_POST['ftempat_meninggal']);
  $sebab_meninggal  = !empty($_POST['fsebab_meninggal']) ? mysqli_real_escape_string($connect, $_POST['fsebab_meninggal']) : NULL;

  // KEPERLUAN
  $keperluan   = mysqli_real_escape_string($connect, $_POST['fkeperluan']);
  $keterangan  = !empty($_POST['fketerangan']) ? mysqli_real_escape_string($connect, $_POST['fketerangan']) : NULL;

  $status_surat   = "PENDING";
  $id_profil_desa = 1;

  // FUNGSI UPLOAD
  function uploadFile($fileField, $uploadDir) {
    if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] != 0) {
      return NULL;
    }

    $namaFile = $_FILES[$fileField]['name'];
    $tmpFile  = $_FILES[$fileField]['tmp_name'];
    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    $allowed = array('jpg','jpeg','png','pdf','doc','docx','zip','rar');
    if (!in_array($ext, $allowed)) {
      return NULL;
    }

    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $newName = $fileField . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
    $dest = rtrim($uploadDir, '/').'/'.$newName;

    if (move_uploaded_file($tmpFile, $dest)) {
      return $newName;
    }
    return NULL;
  }

  // Folder upload (sesuaikan kalau strukturmu beda)
  $uploadDir = "../../uploads/akta_kematian/";

  // UPLOAD BERKAS
  $surat_kematian   = uploadFile('surat_kematian', $uploadDir);
  $kk_termohon      = uploadFile('kk_termohon', $uploadDir);
  $ktp_termohon     = uploadFile('ktp_termohon', $uploadDir);
  $kk_ahli_waris    = uploadFile('kk_ahli_waris', $uploadDir);
  $dokumen_lainnya  = uploadFile('dokumen_lainnya', $uploadDir);

  // INSERT KE TABEL surat_keterangan_akta_kematian
  $qTambahSurat = "
    INSERT INTO surat_keterangan_akta_kematian
    (jenis_surat, no_surat,
     nik, nama_almarhum, tgl_meninggal, tempat_meninggal, sebab_meninggal,
     keperluan, keterangan,
     surat_kematian, kk_termohon, ktp_termohon, kk_ahli_waris, dokumen_lainnya,
     status_surat, id_profil_desa)
    VALUES
    ('$jenis_surat', NULL,
     '$nik', '$nama_almarhum', '$tgl_meninggal', '$tempat_meninggal',
     ".($sebab_meninggal ? "'$sebab_meninggal'" : "NULL").",
     '$keperluan',
     ".($keterangan ? "'$keterangan'" : "NULL").",
     ".($surat_kematian ? "'$surat_kematian'" : "NULL").",
     ".($kk_termohon ? "'$kk_termohon'" : "NULL").",
     ".($ktp_termohon ? "'$ktp_termohon'" : "NULL").",
     ".($kk_ahli_waris ? "'$kk_ahli_waris'" : "NULL").",
     ".($dokumen_lainnya ? "'$dokumen_lainnya'" : "NULL").",
     '$status_surat', '$id_profil_desa')
  ";

  $TambahSurat = mysqli_query($connect, $qTambahSurat);

  if ($TambahSurat) {
    header("location:index.php?pesan=berhasil");
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($connect);
  }
}
?>
