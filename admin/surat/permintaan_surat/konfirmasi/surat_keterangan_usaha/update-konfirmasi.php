<?php
include ('../../../../../config/koneksi.php');

$id              = mysqli_real_escape_string($connect, $_POST['id']); // id_sku
$no_surat        = mysqli_real_escape_string($connect, $_POST['fno_surat']);
$id_pejabat_desa = mysqli_real_escape_string($connect, $_POST['fid_pejabat_desa']);
$status_surat    = "SELESAI";

$qUpdate = "
  UPDATE surat_keterangan_usaha
  SET
    no_surat = '$no_surat',
    id_pejabat_desa = '$id_pejabat_desa',
    status_surat = '$status_surat'
  WHERE id_sku = '$id'
";

$update = mysqli_query($connect, $qUpdate);

if($update){
  header('location:../../');
} else {
  echo ("<script LANGUAGE='JavaScript'>
    window.alert('Gagal mengonfirmasi surat: ".mysqli_error($connect)."');
    window.location.href='#';
  </script>");
}
?>
