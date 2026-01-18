<?php
include ('../../../../../config/koneksi.php');

if(!isset($_POST['id_spkkk'], $_POST['id_spak'], $_POST['fno_surat_akta'], $_POST['fno_surat_kk'], $_POST['fid_pejabat_desa'])){
  header('location:../../');
  exit;
}

$id_spkkk        = addslashes($_POST['id_spkkk']);   // PK tabel KK paket (ubah kalau beda)
$id_spak         = addslashes($_POST['id_spak']);    // PK tabel akta
$no_surat_akta   = addslashes($_POST['fno_surat_akta']);
$no_surat_kk     = addslashes($_POST['fno_surat_kk']);
$id_pejabat_desa = addslashes($_POST['fid_pejabat_desa']);

$status_surat = "SELESAI";

mysqli_begin_transaction($connect);

try {
  // 1) Update AKTA
  $qA = "
    UPDATE surat_pengajuan_akta_kelahiran
    SET
      no_surat = '$no_surat_akta',
      id_pejabat_desa = '$id_pejabat_desa',
      status_surat = '$status_surat'
    WHERE id_spak = '$id_spak'
  ";
  $okA = mysqli_query($connect, $qA);
  if(!$okA){
    throw new Exception("Gagal update akta: ".mysqli_error($connect));
  }

  // 2) Update KK paket
  $qK = "
    UPDATE surat_pendaftaran_pencetakan_kk_kelahiran
    SET
      no_surat = '$no_surat_kk',
      id_pejabat_desa = '$id_pejabat_desa',
      status_surat = '$status_surat'
    WHERE id_spkkk = '$id_spkkk'
  ";
  $okK = mysqli_query($connect, $qK);
  if(!$okK){
    throw new Exception("Gagal update KK: ".mysqli_error($connect));
  }

  mysqli_commit($connect);
  header('location:../../');
  exit;

} catch(Exception $e){
  mysqli_rollback($connect);
  echo "<script>
    alert('".$e->getMessage()."');
    window.location.href='index.php?id=".$id_spkkk."';
  </script>";
  exit;
}
