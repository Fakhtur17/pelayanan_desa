<?php
include ('../../../../../config/koneksi.php');

if(!isset($_POST['submit'])){
  header("location:../../");
  exit;
}

$id              = (int) $_POST['id']; // id_spac
$no_surat        = mysqli_real_escape_string($connect, $_POST['fno_surat']);
$id_pejabat_desa = (int) $_POST['fid_pejabat_desa'];
$status_surat    = "SELESAI";

$qUpdate = "
  UPDATE surat_pembetulan_akta_capil
  SET
    no_surat = '$no_surat',
    id_pejabat_desa = $id_pejabat_desa,
    status_surat = '$status_surat'
  WHERE id_spac = $id
";

$update = mysqli_query($connect, $qUpdate);

if($update){
  header('location:../../');
}else{
  die("Gagal mengonfirmasi surat: ".mysqli_error($connect));
}
?>
