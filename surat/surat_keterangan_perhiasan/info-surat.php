<?php
  include ('../../config/koneksi.php');
  include ('../part/header.php');

  if(!isset($_POST['fnik'])){
    header("location:index.php");
    exit;
  }

  $nik = mysqli_real_escape_string($connect, $_POST['fnik']);

  $qCekNik = mysqli_query($connect, "SELECT * FROM penduduk WHERE nik = '$nik' LIMIT 1");

  if(!$qCekNik || mysqli_num_rows($qCekNik) == 0){
    header("location:index.php?pesan=gagal");
    exit;
  }

  $data = mysqli_fetch_assoc($qCekNik);
  $_SESSION['nik'] = $nik;

  // format tgl lahir
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
              <div class="col-sm-6"><h5><b>SURAT KETERANGAN PERHIASAN</b></h5></div>
              <div class="col-sm-6"><h5 class="text-right"><b>NOMOR SURAT : -</b></h5></div>
            </div>
          </div>

          <hr>

          <!-- WAJIB enctype -->
          <form method="post" action="simpan-surat.php" enctype="multipart/form-data">

            <!-- INFORMASI PEMOHON -->
            <h6 class="container-fluid" align="right"><i class="fas fa-user"></i> Informasi Pemohon</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Nama Lengkap</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform:capitalize;" value="<?php echo $data['nama']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Jenis Kelamin</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform:capitalize;" value="<?php echo $data['jenis_kelamin']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Tempat, Tgl Lahir</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform:capitalize;" value="<?php echo $data['tempat_lahir'].", ".$tgl.$blnIndo[$bln].$thn; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Agama</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform:capitalize;" value="<?php echo $data['agama']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Pekerjaan</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform:capitalize;" value="<?php echo $data['pekerjaan']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Status Perkawinan</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform:capitalize;" value="<?php echo $data['status_perkawinan']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">NIK</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnik" class="form-control" value="<?php echo $data['nik']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Kewarganegaraan</label>
                  <div class="col-sm-12">
                    <input type="text" class="form-control" style="text-transform:uppercase;" value="<?php echo $data['kewarganegaraan']; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Alamat</label>
                  <div class="col-sm-12">
                    <textarea class="form-control" style="text-transform:capitalize;" readonly><?php echo $alamat_pemohon; ?></textarea>
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- FORM PERHIASAN -->
            <h6 class="container-fluid" align="right"><i class="fas fa-gem"></i> Formulir Informasi Perhiasan</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Jenis Perhiasan</label>
                  <div class="col-sm-12">
                    <select name="fjenis_perhiasan" class="form-control" required>
                      <option value="">-- Jenis Perhiasan --</option>
                      <option value="Emas">Emas</option>
                      <option value="Berlian">Berlian</option>
                      <option value="Mutiara">Mutiara</option>
                      <option value="Etnik">Etnik</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Nama Perhiasan</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnama_perhiasan" class="form-control" style="text-transform:capitalize;" placeholder="Masukkan Nama Perhiasan" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Berat (Gram)</label>
                  <div class="col-sm-12">
                    <input type="text" name="fberat" maxlength="5" onkeypress="return hanyaAngka(event)" class="form-control" placeholder="Masukkan Berat (Gram)" required>
                    <script>
                      function hanyaAngka(evt){
                        var charCode = (evt.which) ? evt.which : event.keyCode;
                        if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
                        return true;
                      }
                    </script>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Toko Perhiasan</label>
                  <div class="col-sm-12">
                    <input type="text" name="ftoko_perhiasan" class="form-control" style="text-transform:capitalize;" placeholder="Masukkan Toko Perhiasan" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Lokasi Toko Perhiasan</label>
                  <div class="col-sm-12">
                    <input type="text" name="flokasi_toko_perhiasan" class="form-control" style="text-transform:capitalize;" placeholder="Masukkan Lokasi Toko Perhiasan" required>
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- FORM SURAT -->
            <h6 class="container-fluid" align="right"><i class="fas fa-edit"></i> Formulir Surat</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">Keperluan Surat</label>
                  <div class="col-sm-12">
                    <input type="text" name="fkeperluan" class="form-control" style="text-transform:capitalize;" placeholder="Masukkan Keperluan Surat" required>
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- PERSYARATAN BERKAS (KTP & KK) -->
            <h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Persyaratan Berkas</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">1. Upload KTP</label>
                  <div class="col-sm-12">
                    <input type="file" name="ktp" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight:500;">2. Upload KK</label>
                  <div class="col-sm-12">
                    <input type="file" name="kk" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
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

<?php include ('../part/footer.php'); ?>
