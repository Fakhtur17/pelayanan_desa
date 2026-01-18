<?php
include ('../../../../../config/koneksi.php');

if(!isset($_POST['id'], $_POST['fno_surat'], $_POST['fid_pejabat_desa'])){
  header('location:../../');
  exit;
}

$id              = $_POST['id'];
$no_surat        = addslashes($_POST['fno_surat']);
$id_pejabat_desa = addslashes($_POST['fid_pejabat_desa']);
$status_surat    = "SELESAI";

$qUpdate = "
  UPDATE surat_perubahan_nama_capil
  SET 
    no_surat = '$no_surat',
    id_pejabat_desa = '$id_pejabat_desa',
    status_surat = '$status_surat'
  WHERE id = '$id'
";

$update = mysqli_query($connect, $qUpdate);

if($update){
  header('location:../../');
  exit;
}else{
  echo "<script>
    alert('Gagal mengonfirmasi surat: ".mysqli_error($connect)."');
    window.location.href='index.php?id=".$id."';
  </script>";
  exit;
}
