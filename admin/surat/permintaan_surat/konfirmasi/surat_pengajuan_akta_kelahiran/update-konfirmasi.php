<?php
	include ('../../../../../config/koneksi.php');

	$id              = $_POST['id'];            // id_spak
	$no_surat        = $_POST['fno_surat'];
	$id_pejabat_desa = $_POST['fid_pejabat_desa']; // dari select tanda tangan
	$status_surat    = "SELESAI";

	$qUpdate = "
		UPDATE surat_pengajuan_akta_kelahiran
		SET 
			no_surat = '$no_surat',
			id_pejabat_desa = '$id_pejabat_desa',
			status_surat = '$status_surat'
		WHERE id_spak = '$id'
	";

	$update = mysqli_query($connect, $qUpdate);

	if($update){
		header('location:../../'); // balik ke halaman permintaan surat
	}else{
		echo ("<script LANGUAGE='JavaScript'>
			window.alert('Gagal mengonfirmasi surat: ".mysqli_error($connect)."');
			window.location.href='#';
		</script>");
	}
?>
