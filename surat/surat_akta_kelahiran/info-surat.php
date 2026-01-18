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

			// Format tanggal lahir pemohon
			$tgl_lhr = $data['tgl_lahir'];
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
							<a class="col-sm-6"><h5><b>SURAT PENGAJUAN AKTA KELAHIRAN</b></h5></a>
							<a class="col-sm-6"><h5><b>NOMOR SURAT : -</b></h5></a>
						</div>
					</div>
					<hr>

					<!-- penting: enctype multipart untuk upload -->
					<form method="post" action="simpan-surat.php" enctype="multipart/form-data">

						<!-- =========================
							 INFORMASI PEMOHON
						========================= -->
						<h6 class="container-fluid" align="right"><i class="fas fa-user"></i> Informasi Pemohon</h6>
						<hr width="97%">

						<div class="row">
							<div class="col-sm-6">
							    <div class="form-group">
						           	<label class="col-sm-12" style="font-weight: 500;">Nama Lengkap</label>
						           	<div class="col-sm-12">
						               	<input type="text" name="fnama_pemohon" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['nama']; ?>" readonly>
						           	</div>
						        </div>
							</div>

							<div class="col-sm-6">
							    <div class="form-group">
						           	<label class="col-sm-12" style="font-weight: 500;">Jenis Kelamin</label>
						           	<div class="col-sm-12">
						               	<input type="text" name="fjenis_kelamin_pemohon" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['jenis_kelamin']; ?>" readonly>
						           	</div>
						        </div>
							</div>

							<div class="col-sm-6">
							    <div class="form-group">
						           	<label class="col-sm-12" style="font-weight: 500;">Tempat, Tgl Lahir</label>
						           	<div class="col-sm-12">
						               	<input type="text" name="ftempat_tgl_lahir_pemohon" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['tempat_lahir'] . ", " . $tgl . $blnIndo[$bln] . $thn; ?>" readonly>
						           	</div>
						        </div>
							</div>

							<div class="col-sm-6">
							    <div class="form-group">
						           	<label class="col-sm-12" style="font-weight: 500;">Agama</label>
						           	<div class="col-sm-12">
						               	<input type="text" name="fagama_pemohon" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['agama']; ?>" readonly>
						           	</div>
						        </div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
							    <div class="form-group">
						           	<label class="col-sm-12" style="font-weight: 500;">Pekerjaan</label>
						           	<div class="col-sm-12">
						               	<input type="text" name="fpekerjaan_pemohon" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['pekerjaan']; ?>" readonly>
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
						           	<label class="col-sm-12" style="font-weight: 500;">Alamat Pemohon</label>
						           	<div class="col-sm-12">
						               	<textarea name="falamat_pemohon" class="form-control" style="text-transform: capitalize;" readonly><?php echo $alamat_pemohon; ?></textarea>
						           	</div>
						        </div>
						  	</div>

							<div class="col-sm-6">
							    <div class="form-group">
						           	<label class="col-sm-12" style="font-weight: 500;">Kewarganegaraan</label>
						           	<div class="col-sm-12">
						               	<input type="text" name="fkewarganegaraan_pemohon" class="form-control" style="text-transform: uppercase;" value="<?php echo $data['kewarganegaraan']; ?>" readonly>
						           	</div>
						        </div>
							</div>
						</div>

						<br>

						<!-- =========================
							 FORMULIR AKTA KELAHIRAN
						========================= -->
						<h6 class="container-fluid" align="right"><i class="fas fa-edit"></i> Data Bayi</h6>
						<hr width="97%">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Nama Bayi</label>
									<div class="col-sm-12">
										<input type="text" name="fnama_bayi" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Nama Bayi" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Jenis Kelamin Bayi</label>
									<div class="col-sm-12">
										<select name="fjenis_kelamin_bayi" class="form-control" required>
											<option value="">-- Pilih --</option>
											<option value="Laki-laki">Laki-laki</option>
											<option value="Perempuan">Perempuan</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Tempat Lahir Bayi</label>
									<div class="col-sm-12">
										<input type="text" name="ftempat_lahir_bayi" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Tempat Lahir Bayi" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Tanggal Lahir Bayi</label>
									<div class="col-sm-12">
										<input type="date" name="ftgl_lahir_bayi" class="form-control" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Jam Lahir (opsional)</label>
									<div class="col-sm-12">
										<input type="time" name="fjam_lahir_bayi" class="form-control">
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Anak Ke- (opsional)</label>
									<div class="col-sm-12">
										<input type="text" name="fanak_ke" class="form-control" placeholder="Contoh: 1">
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Berat Bayi (opsional)</label>
									<div class="col-sm-12">
										<input type="text" name="fberat_bayi" class="form-control" placeholder="Contoh: 3.2 kg">
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Panjang Bayi (opsional)</label>
									<div class="col-sm-12">
										<input type="text" name="fpanjang_bayi" class="form-control" placeholder="Contoh: 50 cm">
									</div>
								</div>
							</div>
						</div>

						<br>

						<!-- =========================
							 DATA AYAH & IBU
						========================= -->
						<h6 class="container-fluid" align="right"><i class="fas fa-users"></i> Data Ayah & Ibu</h6>
						<hr width="97%">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">NIK Ayah</label>
									<div class="col-sm-12">
										<input type="text" name="fnik_ayah" class="form-control" placeholder="Masukkan NIK Ayah" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Nama Ayah</label>
									<div class="col-sm-12">
										<input type="text" name="fnama_ayah" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['nama_ayah']; ?>" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">NIK Ibu</label>
									<div class="col-sm-12">
										<input type="text" name="fnik_ibu" class="form-control" placeholder="Masukkan NIK Ibu" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Nama Ibu</label>
									<div class="col-sm-12">
										<input type="text" name="fnama_ibu" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['nama_ibu']; ?>" required>
									</div>
								</div>
							</div>
						</div>

						<br>

						<!-- =========================
							 UPLOAD PERSYARATAN
						========================= -->
						<h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Persyaratan Berkas</h6>
						<hr width="97%">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Surat Kelahiran RS/Puskesmas</label>
									<div class="col-sm-12">
										<input type="file" name="fsurat_kelahiran_rs" class="form-control" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">FC Buku Nikah</label>
									<div class="col-sm-12">
										<input type="file" name="ffc_buku_nikah" class="form-control" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Asli/Scan KK Termohon</label>
									<div class="col-sm-12">
										<input type="file" name="fkk_pemohon" class="form-control" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Asli/Scan KTP-el Ayah</label>
									<div class="col-sm-12">
										<input type="file" name="fktp_ayah" class="form-control" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Asli/Scan KTP-el Ibu</label>
									<div class="col-sm-12">
										<input type="file" name="fktp_ibu" class="form-control" required>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="col-sm-12" style="font-weight: 500;">Dokumen Pendukung Lain (opsional)</label>
									<div class="col-sm-12">
										<input type="file" name="fdokumen_pendukung_lain" class="form-control">
									</div>
								</div>
							</div>
						</div>

						<br>

						<!-- =========================
							 KEPERLUAN
						========================= -->
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
