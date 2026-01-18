<?php 
	include ('../../permintaan_surat/konfirmasi/part/akses.php');
  	include ('../../../../config/koneksi.php');

  	$id = $_GET['id'];

  	// ambil data penduduk + surat akta kelahiran
  	$qCek = mysqli_query($connect,"
  		SELECT penduduk.*, surat_pengajuan_akta_kelahiran.*
  		FROM penduduk 
  		LEFT JOIN surat_pengajuan_akta_kelahiran 
  			ON surat_pengajuan_akta_kelahiran.nik = penduduk.nik 
  		WHERE surat_pengajuan_akta_kelahiran.id_spak='$id'
  	");

  	while($row = mysqli_fetch_array($qCek)){

  		$qTampilDesa = mysqli_query($connect, "SELECT * FROM profil_desa WHERE id_profil_desa = '1'");
        foreach($qTampilDesa as $rows){

			$id_pejabat_desa = $row['id_pejabat_desa'];

			$qCekPejabatDesa = mysqli_query($connect,"
				SELECT pejabat_desa.jabatan, pejabat_desa.nama_pejabat_desa
				FROM pejabat_desa 
				WHERE pejabat_desa.id_pejabat_desa = '$id_pejabat_desa'
			");

		  	while($rowss = mysqli_fetch_array($qCekPejabatDesa)){
?>

<html>
<head>
	<link rel="shortcut icon" href="../../../../assets/img/mini-logo.png">
	<title>CETAK SURAT</title>
	<link href="../../../../assets/formsuratCSS/formsurat.css" rel="stylesheet" type="text/css"/>
	<style type="text/css" media="print">
	    @page { margin: 0; }
  		body { 
  			margin: 1cm;
  			margin-left: 2cm;
  			margin-right: 2cm;
  			font-family: "Times New Roman", Times, serif;
  		}
	</style>
</head>
<body>
<div>
	<table width="100%">
		<tr><img src="../../../../assets/img/logo-jombang-90x90.png" alt="" class="logo"></tr>
		<div class="header">
			<h4 class="kop" style="text-transform: uppercase">PEMERINTAH <?php echo $rows['kota']; ?></h4>
			<h4 class="kop" style="text-transform: uppercase">KECAMATAN <?php echo $rows['kecamatan']; ?></h4>
			<h4 class="kop" style="text-transform: uppercase">KEPALA DESA <?php echo $rows['nama_desa']; ?></h4>
			<h5 class="kop2" style="text-transform: capitalize;"><?php echo $rows['alamat'] . " Telp. " . $rows['no_telpon'] . " Kode Pos " . $rows['kode_pos']; ?></h5>
			<div style="text-align: center;">
				<hr>
			</div>
		</div>
		<br>
		<div align="center"><u><h4 class="kop">SURAT PENGAJUAN AKTA KELAHIRAN</h4></u></div>
		<div align="center"><h4 class="kop3">Nomor :&nbsp;&nbsp;&nbsp;<?php echo $row['no_surat']; ?></h4></div>
	</table>

	<br>
	<div class="clear"></div>

	<div id="isi3">
		<table width="100%">
			<tr>
				<td class="indentasi">
					Yang bertanda tangan di bawah ini, 
					<a style="text-transform: capitalize;">
						<?php echo $rowss['jabatan'] . " " . $rows['nama_desa']; ?>, Kecamatan <?php echo $rows['kecamatan']; ?>, <?php echo $rows['kota']; ?>
					</a>, menerangkan bahwa:
				</td>
			</tr>
		</table>

		<br><br>

		<!-- =========================
		     DATA PEMOHON
		========================= -->
		<table width="100%" style="text-transform: capitalize;">
			<tr>
				<td width="30%" class="indentasi">Nama Pemohon</td>
				<td width="2%">:</td>
				<td width="68%" style="text-transform: uppercase; font-weight: bold;"><?php echo $row['nama']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">NIK Pemohon</td>
				<td>:</td>
				<td><?php echo $row['nik']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Jenis Kelamin</td>
				<td>:</td>
				<td><?php echo $row['jenis_kelamin']; ?></td>
			</tr>

			<?php
				$tgl_lhr = $row['tgl_lahir'];
				$tgl = date('d ', strtotime($tgl_lhr));
				$bln = date('F', strtotime($tgl_lhr));
				$thn = date(' Y', strtotime($tgl_lhr));
				$blnIndo = array(
				    'January' => 'Januari',
				    'February' => 'Februari',
				    'March' => 'Maret',
				    'April' => 'April',
				    'May' => 'Mei',
				    'June' => 'Juni',
				    'July' => 'Juli',
				    'August' => 'Agustus',
				    'September' => 'September',
				    'October' => 'Oktober',
				    'November' => 'November',
				    'December' => 'Desember'
				);
			?>
			<tr>
				<td class="indentasi">Tempat/Tgl. Lahir</td>
				<td>:</td>
				<td><?php echo $row['tempat_lahir'] . ", " . $tgl . $blnIndo[$bln] . $thn; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Agama</td>
				<td>:</td>
				<td><?php echo $row['agama']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Pekerjaan</td>
				<td>:</td>
				<td><?php echo $row['pekerjaan']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Alamat</td>
				<td>:</td>
				<td>
					<?php 
						// pakai alamat_pemohon dari tabel surat (lebih sesuai struktur kamu)
						echo $row['alamat_pemohon']; 
					?>
				</td>
			</tr>
			<tr>
				<td class="indentasi">Kewarganegaraan</td>
				<td>:</td>
				<td style="text-transform: uppercase;"><?php echo $row['kewarganegaraan']; ?></td>
			</tr>
		</table>

		<br><br>

		<!-- =========================
		     DATA BAYI
		========================= -->
		<table width="100%" style="text-transform: capitalize;">
			<tr>
				<td class="indentasi" colspan="3"><b>Data Bayi</b></td>
			</tr>
			<tr>
				<td width="30%" class="indentasi">Nama Bayi</td>
				<td width="2%">:</td>
				<td width="68%"><b><?php echo $row['nama_bayi']; ?></b></td>
			</tr>
			<tr>
				<td class="indentasi">Jenis Kelamin</td>
				<td>:</td>
				<td><?php echo $row['jenis_kelamin_bayi']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Tempat Lahir</td>
				<td>:</td>
				<td><?php echo $row['tempat_lahir_bayi']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Tanggal Lahir</td>
				<td>:</td>
				<td><?php echo date('d-m-Y', strtotime($row['tgl_lahir_bayi'])); ?></td>
			</tr>
			<tr>
				<td class="indentasi">Jam Lahir</td>
				<td>:</td>
				<td><?php echo !empty($row['jam_lahir_bayi']) ? $row['jam_lahir_bayi'] : "-"; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Anak Ke-</td>
				<td>:</td>
				<td><?php echo !empty($row['anak_ke']) ? $row['anak_ke'] : "-"; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Berat</td>
				<td>:</td>
				<td><?php echo !empty($row['berat_bayi']) ? $row['berat_bayi'] : "-"; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Panjang</td>
				<td>:</td>
				<td><?php echo !empty($row['panjang_bayi']) ? $row['panjang_bayi'] : "-"; ?></td>
			</tr>
		</table>

		<br><br>

		<!-- =========================
		     DATA AYAH & IBU
		========================= -->
		<table width="100%" style="text-transform: capitalize;">
			<tr>
				<td class="indentasi" colspan="3"><b>Data Orang Tua</b></td>
			</tr>
			<tr>
				<td width="30%" class="indentasi">NIK Ayah</td>
				<td width="2%">:</td>
				<td width="68%"><?php echo $row['nik_ayah']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Nama Ayah</td>
				<td>:</td>
				<td><?php echo $row['nama_ayah']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">NIK Ibu</td>
				<td>:</td>
				<td><?php echo $row['nik_ibu']; ?></td>
			</tr>
			<tr>
				<td class="indentasi">Nama Ibu</td>
				<td>:</td>
				<td><?php echo $row['nama_ibu']; ?></td>
			</tr>
		</table>

		<br><br>

		<!-- =========================
		     KETERANGAN / KEPERLUAN
		========================= -->
		<table width="100%">
			<tr>
				<td class="indentasi">
					Adapun pengajuan akta kelahiran ini dibuat untuk:
				</td>
			</tr>
		</table>
		<br>

		<table width="100%" style="text-transform: capitalize;">
			<tr>
				<td class="indentasi" style="text-align:center;">
					<b><u><?php echo !empty($row['keterangan']) ? $row['keterangan'] : "-"; ?></u></b>
				</td>
			</tr>
		</table>

		<br>

		<table width="100%">
			<tr>
				<td class="indentasi">
					Demikian surat pengajuan ini dibuat untuk dipergunakan sebagaimana mestinya.
				</td>
			</tr>
		</table>
	</div>

	<br>

	<table width="100%" style="text-transform: capitalize;">
		<tr>
			<td width="10%"></td>
			<td width="30%"></td>
			<td width="10%"></td>
			<td align="center">
				<?php echo $rows['nama_desa']; ?>, 
				<?php
					$tanggal = date('d F Y');
					$bulan = date('F', strtotime($tanggal));
					$bulanIndo = array(
					    'January' => 'Januari',
					    'February' => 'Februari',
					    'March' => 'Maret',
					    'April' => 'April',
					    'May' => 'Mei',
					    'June' => 'Juni',
					    'July' => 'Juli',
					    'August' => 'Agustus',
					    'September' => 'September',
					    'October' => 'Oktober',
					    'November' => 'November',
					    'December' => 'Desember'
					);
					echo date('d ') . $bulanIndo[$bulan] . date(' Y');
				?>
			</td>
		</tr>
		<tr>
			<td></td><td></td><td></td>
			<td align="center"><?php echo $rowss['jabatan'] . " " . $rows['nama_desa']; ?></td>
		</tr>

		<tr><td colspan="4" style="height:80px;"></td></tr>

		<tr>
			<td></td><td></td><td></td>
			<td align="center" style="text-transform: uppercase;"><u><b><?php echo $rowss['nama_pejabat_desa']; ?></b></u></td>
		</tr>
	</table>
</div>

<script>
	window.print();
</script>
</body>
</html>

<?php
			}
		}
  	}
?>
