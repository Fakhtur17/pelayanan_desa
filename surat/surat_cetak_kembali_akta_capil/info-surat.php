<?php
	include ('../../config/koneksi.php');
	include ('../part/header.php');

	$nik = $_POST['fnik'];

	$qCekNik = mysqli_query($connect,"SELECT * FROM penduduk WHERE nik = '$nik'");
	$row = mysqli_num_rows($qCekNik);

	if($row > 0){
		$data = mysqli_fetch_assoc($qCekNik);
		if($data['nik'] == $nik){
			$_SESSION['nik'] = $nik;

			$tgl_lhr = $data['tgl_lahir'];
			$tgl = date('d ', strtotime($tgl_lhr));
			$bln = date('F', strtotime($tgl_lhr));
			$thn = date(' Y', strtotime($tgl_lhr));
			$blnIndo = array(
				'January' => 'Januari','February' => 'Februari','March' => 'Maret','April' => 'April',
				'May' => 'Mei','June' => 'Juni','July' => 'Juli','August' => 'Agustus',
				'September' => 'September','October' => 'Oktober','November' => 'November','December' => 'Desember'
			);

			$alamat_pemohon = $data['jalan'] . ", RT" . $data['rt'] . "/RW" . $data['rw'] . ", Dusun " . $data['dusun'] . ",\nDesa " . $data['desa'] . ", Kecamatan " . $data['kecamatan'] . ", " . $data['kota'];
?>
<body class="bg-light">
	<div class="container" style="max-height:cover; padding-top:30px; padding-bottom:60px; position:relative; min-height: 100%;">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<h5 class="card-header"><i class="fas fa-envelope"></i> INFORMASI SURAT</h5>
					<br>
					<div class="container-fluid">
						<div class="row">
							<a class="col-sm-6"><h5><b>SURAT CETAK KEMBALI AKTA CAPIL</b></h5></a>
							<a class="col-sm-6"><h5><b>NOMOR SURAT : -</b></h5></a>
						</div>
					</div>
					<hr>

					<form method="post" action="simpan-surat.php" enctype="multipart/form-data">

						<h6 class="container-fluid" align="right"><i class="fas fa-user"></i> Informasi Pemohon</h6>
						<hr width="97%">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Nama Lengkap</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" value="<?php echo $data['nama']; ?>" readonly>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">NIK Pemohon</label>
									<div class="col-sm-12">
										<input type="text" name="fnik" class="form-control" value="<?php echo $data['nik']; ?>" readonly>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Tempat, Tgl Lahir</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" value="<?php echo $data['tempat_lahir'] . ", " . $tgl . $blnIndo[$bln] . $thn; ?>" readonly>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Jenis Kelamin</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" value="<?php echo $data['jenis_kelamin']; ?>" readonly>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Alamat Pemohon</label>
									<div class="col-sm-12">
										<textarea name="falamat_pemohon" class="form-control" readonly><?php echo $alamat_pemohon; ?></textarea>
									</div>
								</div>
							</div>
						</div>

						<br>

						<h6 class="container-fluid" align="right"><i class="fas fa-edit"></i> Data Akta</h6>
						<hr width="97%">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Jenis Akta</label>
									<div class="col-sm-12">
										<select name="fjenis_akta" class="form-control" required>
											<option value="">-- Pilih --</option>
											<option value="Akta Kelahiran">Akta Kelahiran</option>
											<option value="Akta Perkawinan">Akta Perkawinan</option>
											<option value="Akta Kematian">Akta Kematian</option>
											<option value="Akta Perceraian">Akta Perceraian</option>
											<option value="Akta Lainnya">Akta Lainnya</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Nomor Akta (jika ada)</label>
									<div class="col-sm-12">
										<input type="text" name="fnomor_akta" class="form-control" placeholder="Masukkan nomor akta (opsional)">
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Tahun Akta (jika ada)</label>
									<div class="col-sm-12">
										<input type="text" name="ftahun_akta" class="form-control" placeholder="Contoh: 2020 (opsional)">
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Alasan Permohonan</label>
									<div class="col-sm-12">
										<select name="falasan_permohonan" class="form-control" required>
											<option value="">-- Pilih --</option>
											<option value="Rusak">Akta Rusak</option>
											<option value="Hilang">Akta Hilang</option>
											<option value="Dikuasai Pihak Lain">Dikuasai Pihak Lain</option>
											<option value="Lainnya">Lainnya</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Keterangan Alasan (opsional)</label>
									<div class="col-sm-12">
										<input type="text" name="fketerangan_alasan" class="form-control" placeholder="Contoh: akta hilang saat pindahan, dsb (opsional)">
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Asal Akta</label>
									<div class="col-sm-12">
										<select name="fasal_akta" class="form-control" required>
											<option value="">-- Pilih --</option>
											<option value="Dalam Daerah">Dalam Daerah</option>
											<option value="Luar Daerah">Luar Daerah</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Daerah Penerbit (jika luar daerah)</label>
									<div class="col-sm-12">
										<input type="text" name="fdaerah_penerbit" class="form-control" placeholder="Contoh: Kabupaten/Kota penerbit (opsional)">
									</div>
								</div>
							</div>
						</div>

						<br>

						<h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Persyaratan Berkas</h6>
						<hr width="97%">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">1. Foto Asli KK Termohon</label>
									<div class="col-sm-12">
										<input type="file" name="ffoto_kk" class="form-control" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">2. Asli Kutipan Akta (rusak/hilang/dikuasai pihak lain)</label>
									<div class="col-sm-12">
										<input type="file" name="fbukti_akta" class="form-control">
										<small class="text-muted">Jika hilang dan tidak ada, boleh dikosongi.</small>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">3. Surat Kehilangan Polisi / Surat Pernyataan Sengketa</label>
									<div class="col-sm-12">
										<input type="file" name="fbukti_kehilangan_atau_pernyataan" class="form-control" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">4. Surat Konfirmasi Penerbit (khusus luar daerah)</label>
									<div class="col-sm-12">
										<input type="file" name="fdok_konfirmasi_penerbit" class="form-control">
										<small class="text-muted">Wajib jika asal akta luar daerah.</small>
									</div>
								</div>
							</div>
						</div>

						<br>

						<h6 class="container-fluid" align="right"><i class="fas fa-edit"></i> Keperluan</h6>
						<hr width="97%">

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Keperluan Surat</label>
									<div class="col-sm-12">
										<input type="text" name="fkeperluan" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Keperluan Surat" required>
									</div>
								</div>
							</div>
						</div>

						<hr width="97%">

						<div class="container-fluid">
							<input type="reset" class="btn btn-warning" value="Batal">
							<input type="submit" name="submit" class="btn btn-info" value="Submit">
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</body>

<?php
		}
	}else{
		header("location:index.php?pesan=gagal");
	}

	include ('../part/footer.php');
?>
