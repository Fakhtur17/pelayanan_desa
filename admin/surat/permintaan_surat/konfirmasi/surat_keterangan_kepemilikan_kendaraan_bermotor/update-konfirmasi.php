<?php
include ('../../../../../config/koneksi.php');

$id              = mysqli_real_escape_string($connect, $_POST['id']);
$no_surat        = mysqli_real_escape_string($connect, $_POST['fno_surat']);
$id_pejabat_desa = mysqli_real_escape_string($connect, $_POST['ft_tangan']);
$status_surat    = "SELESAI";

$qUpdate = "
  UPDATE surat_keterangan_kepemilikan_kendaraan_bermotor
  SET
    no_surat = '$no_surat',
    id_pejabat_desa = '$id_pejabat_desa',
    status_surat = '$status_surat'
  WHERE id_skkkb = '$id'
";

$update = mysqli_query($connect, $qUpdate);

if($update){
  header('location:../../'); // balik ke permintaan surat
}else{
  echo ("<script LANGUAGE='JavaScript'>
    window.alert('Gagal mengonfirmasi surat: ".mysqli_error($connect)."');
    window.location.href='index.php?id=".$id."';
  </script>");
}
