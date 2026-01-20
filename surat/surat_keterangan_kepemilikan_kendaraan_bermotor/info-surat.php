<?php
  include ('../../config/koneksi.php');
  include ('../part/header.php');

  if(!isset($_POST['fnik'])){
    header("location:index.php");
    exit;
  }

  $nik = mysqli_real_escape_string($connect, $_POST['fnik']);

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
        'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April',
        'May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus',
        'September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
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
              <a class="col-sm-6"><h5><b>SURAT KETERANGAN KEPEMILIKAN KENDARAAN BERMOTOR</b></h5></a>
              <a class="col-sm-6"><h5><b>NOMOR SURAT : -</b></h5></a>
            </div>
          </div>
          <hr>

          <!-- ✅ wajib enctype untuk upload -->
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
                    <input type="text" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['nama']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Jenis Kelamin</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['jenis_kelamin']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Tempat, Tgl Lahir</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['tempat_lahir'].", ".$tgl.$blnIndo[$bln].$thn; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Agama</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['agama']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Pekerjaan</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['pekerjaan']; ?>" readonly>
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
                    <textarea class="form-control" style="text-transform: capitalize;" readonly><?php echo $alamat_pemohon; ?></textarea>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Kewarganegaraan</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform: uppercase;" value="<?php echo $data['kewarganegaraan']; ?>" readonly>
                  </div>
                </div>
              </div>

            </div>

            <br>

            <!-- =========================
                 INFORMASI KENDARAAN (SKKKB)
            ========================= -->
            <h6 class="container-fluid" align="right"><i class="fas fa-car"></i> Formulir Informasi Kendaraan</h6>
            <hr width="97%">

            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Merk / Type</label>
                  <div class="col-sm-12">
                    <input type="text" name="fmerk_type" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Merk / Type" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Jenis Model</label>
                  <div class="col-sm-12">
                    <input type="text" name="fjenis_model" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Jenis Model" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Tahun Pembuatan</label>
                  <div class="col-sm-12">
                    <input type="text" name="ftahun_pembuatan" class="form-control" placeholder="Contoh: 2015" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">CC</label>
                  <div class="col-sm-12">
                    <input type="text" name="fcc" class="form-control" placeholder="Contoh: 125" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Warna Cat</label>
                  <div class="col-sm-12">
                    <input type="text" name="fwarna_cat" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Warna Cat" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">No. Rangka</label>
                  <div class="col-sm-12">
                    <input type="text" name="fno_rangka" class="form-control" placeholder="Masukkan No. Rangka" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">No. Mesin</label>
                  <div class="col-sm-12">
                    <input type="text" name="fno_mesin" class="form-control" placeholder="Masukkan No. Mesin" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">No. Polisi</label>
                  <div class="col-sm-12">
                    <input type="text" name="fno_polisi" class="form-control" placeholder="Contoh: S 5503ZW" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">No. BPKB</label>
                  <div class="col-sm-12">
                    <input type="text" name="fno_bpkb" class="form-control" placeholder="Masukkan No. BPKB" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Atas Nama Pemilik</label>
                  <div class="col-sm-12">
                    <input type="text" name="fatas_nama_pemilik" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Atas Nama Pemilik" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Alamat Pemilik</label>
                  <div class="col-sm-12">
                    <textarea name="falamat_pemilik" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Alamat Pemilik" required></textarea>
                  </div>
                </div>
              </div>

            </div>

            <br>

            <!-- =========================
                 KEPERLUAN SURAT
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

            <br>

            <!-- =========================
                 PERSYARATAN (KTP + KK)
            ========================= -->
            <h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Persyaratan Berkas</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">1. KTP Pemilik</label>
                  <div class="col-sm-12">
                    <input type="file" name="ktp_pemilik" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">2. KK Pemilik</label>
                  <div class="col-sm-12">
                    <input type="file" name="kk_pemilik" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
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
  } else {
    header("location:index.php?pesan=gagal");
    exit;
  }

  include ('../part/footer.php');
?>
