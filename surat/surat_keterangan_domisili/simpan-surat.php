<?php
include ('../../config/koneksi.php');

if (isset($_POST['submit'])) {

  $jenis_surat = "Surat Keterangan Domisili";
  $nik         = mysqli_real_escape_string($connect, $_POST['fnik']);

  // Data Domisili
  $alamat_domisili    = mysqli_real_escape_string($connect, $_POST['falamat_domisili']);
  $rt_domisili        = mysqli_real_escape_string($connect, $_POST['frt_domisili']);
  $rw_domisili        = mysqli_real_escape_string($connect, $_POST['frw_domisili']);
  $desa_domisili      = mysqli_real_escape_string($connect, $_POST['fdesa_domisili']);
  $kecamatan_domisili = mysqli_real_escape_string($connect, $_POST['fkecamatan_domisili']);
  $kabupaten_domisili = mysqli_real_escape_string($connect, $_POST['fkabupaten_domisili']);
  $provinsi_domisili  = mysqli_real_escape_string($connect, $_POST['fprovinsi_domisili']);
  $lama_tinggal       = !empty($_POST['flama_tinggal']) ? mysqli_real_escape_string($connect, $_POST['flama_tinggal']) : NULL;

  // Keperluan
  $keperluan  = mysqli_real_escape_string($connect, $_POST['fkeperluan']);
  $keterangan = !empty($_POST['fketerangan']) ? mysqli_real_escape_string($connect, $_POST['fketerangan']) : NULL;

  $status_surat   = "PENDING";
  $id_profil_desa = 1;
  $id_pejabat_desa = NULL; // default kosong dulu

  // ========= FUNGSI UPLOAD (sama model akta kematian)
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

  // Folder upload domisili (silakan sesuaikan)
  $uploadDir = "../../uploads/persyaratan_surat_keterangan_domisili/";

  // Upload wajib
  $ktp_pemohon = uploadFile('ktp_pemohon', $uploadDir);
  $kk_pemohon  = uploadFile('kk_pemohon', $uploadDir);

  if (!$ktp_pemohon || !$kk_pemohon) {
    echo "Gagal upload berkas KTP/KK. Pastikan file dipilih dan formatnya benar.";
    exit;
  }

  // INSERT sesuai tabel domisili (kolom lama + kolom tambahan hasil ALTER)
  $qTambahSurat = "
    INSERT INTO surat_keterangan_domisili
    (jenis_surat, no_surat, nik,
     alamat_domisili, rt_domisili, rw_domisili, desa_domisili, kecamatan_domisili, kabupaten_domisili, provinsi_domisili, lama_tinggal,
     keperluan, keterangan,
     ktp_pemohon, kk_pemohon,
     id_pejabat_desa, status_surat, id_profil_desa)
    VALUES
    ('$jenis_surat', NULL, '$nik',
     '$alamat_domisili', '$rt_domisili', '$rw_domisili', '$desa_domisili', '$kecamatan_domisili', '$kabupaten_domisili', '$provinsi_domisili',
     ".($lama_tinggal ? "'$lama_tinggal'" : "NULL").",
     '$keperluan',
     ".($keterangan ? "'$keterangan'" : "NULL").",
     '$ktp_pemohon', '$kk_pemohon',
     ".($id_pejabat_desa === NULL ? "NULL" : "'$id_pejabat_desa'").",
     '$status_surat', '$id_profil_desa')
  ";

  $TambahSurat = mysqli_query($connect, $qTambahSurat);

  if ($TambahSurat) {
    header("location:index.php?pesan=berhasil");
    exit;
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($connect);
  }
}
?>
