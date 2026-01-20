<?php
  include ('../../config/koneksi.php');
  include ('../part/header.php');

  if(!isset($_POST['fnik'])){
    header("location:index.php");
    exit;
  }

  $nik = mysqli_real_escape_string($connect, $_POST['fnik']);

  $qCekNik = mysqli_query($connect,"SELECT * FROM penduduk WHERE nik = '$nik'");
  if(!$qCekNik || mysqli_num_rows($qCekNik) == 0){
    header("location:index.php?pesan=gagal");
    exit;
  }

  $data = mysqli_fetch_assoc($qCekNik);
  $_SESSION['nik'] = $nik;

  // tanggal lahir pemohon (format indo)
  $tgl_lhr = $data['tgl_lahir'];
  $tgl = date('d ', strtotime($tgl_lhr));
  $bln = date('F', strtotime($tgl_lhr));
  $thn = date(' Y', strtotime($tgl_lhr));

  $blnIndo = array(
    'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni',
    'July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
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
              <a class="col-sm-6"><h5><b>SURAT KETERANGAN USAHA</b></h5></a>
              <a class="col-sm-6"><h5><b>NOMOR SURAT : -</b></h5></a>
            </div>
          </div>
          <hr>

          <!-- WAJIB enctype utk upload -->
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
                    <input type="text" name="ftempat_tgl_lahir_pemohon" class="form-control" style="text-transform: capitalize;" value="<?php echo $data['tempat_lahir'] . ', ' . $tgl . $blnIndo[$bln] . $thn; ?>" readonly>
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
                 FORMULIR SURAT (SKU)
            ========================= -->
            <h6 class="container-fluid" align="right"><i class="fas fa-edit"></i> Formulir Surat</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nama Usaha</label>
                  <div class="col-sm-12">
                    <input type="text" name="fusaha" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Nama Usaha" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Keperluan Surat</label>
                  <div class="col-sm-12">
                    <input type="text" name="fkeperluan" class="form-control" style="text-transform: capitalize;" placeholder="Contoh: Pengajuan modal usaha" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Alamat Lengkap Usaha</label>
                  <div class="col-sm-12">
                    <input type="text" name="falamat_usaha" class="form-control" style="text-transform: capitalize;" placeholder="Jalan, Nomor, RT/RW, Dusun, Desa, Kecamatan, Kota" required>
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- =========================
                 PERSYARATAN BERKAS (SKU)
                 Tambahan: KTP + KK
            ========================= -->
            <h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Persyaratan Berkas</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">1. KTP Pemohon</label>
                  <div class="col-sm-12">
                    <input type="file" name="ktp_pemohon" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">2. KK Pemohon</label>
                  <div class="col-sm-12">
                    <input type="file" name="kk_pemohon" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
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
