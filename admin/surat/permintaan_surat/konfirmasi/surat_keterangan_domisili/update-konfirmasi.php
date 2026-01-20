<?php
include ('../../../../../config/koneksi.php');

if(!isset($_POST['submit'])){
  header("location:../../");
  exit;
}

$id              = mysqli_real_escape_string($connect, $_POST['id']);               // id_skd
$no_surat        = mysqli_real_escape_string($connect, $_POST['fno_surat']);
$id_pejabat_desa = mysqli_real_escape_string($connect, $_POST['fid_pejabat_desa']); // dari select tanda tangan
$status_surat    = "SELESAI";

$qUpdate = "
  UPDATE surat_keterangan_domisili
  SET
    no_surat = '$no_surat',
    id_pejabat_desa = '$id_pejabat_desa',
    status_surat = '$status_surat'
  WHERE id_skd = '$id'
";

$update = mysqli_query($connect, $qUpdate);

if($update){
  header('location:../../'); // balik ke halaman permintaan surat
  exit;
}else{
  echo ("<script LANGUAGE='JavaScript'>
    window.alert('Gagal mengonfirmasi surat: ".mysqli_error($connect)."');
    window.location.href='#';
  </script>");
}
?>
