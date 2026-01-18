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

      // Format tanggal lahir pemohon (opsional untuk ditampilkan)
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
              <a class="col-sm-6"><h5><b>SURAT KETERANGAN AKTA KEMATIAN</b></h5></a>
              <a class="col-sm-6"><h5><b>NOMOR SURAT : -</b></h5></a>
            </div>
          </div>
          <hr>

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
                 DATA ALMARHUM
            ========================= -->
            <h6 class="container-fluid" align="right"><i class="fas fa-user-times"></i> Data Almarhum</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nama Almarhum</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnama_almarhum" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Nama Almarhum" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Tanggal Meninggal</label>
                  <div class="col-sm-12">
                    <input type="date" name="ftgl_meninggal" class="form-control" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Tempat Meninggal</label>
                  <div class="col-sm-12">
                    <input type="text" name="ftempat_meninggal" class="form-control" style="text-transform: capitalize;" placeholder="Masukkan Tempat Meninggal" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Sebab Meninggal (Opsional)</label>
                  <div class="col-sm-12">
                    <input type="text" name="fsebab_meninggal" class="form-control" style="text-transform: capitalize;" placeholder="(Opsional)">
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
                  <label class="col-sm-12" style="font-weight: 500;">Keperluan</label>
                  <div class="col-sm-12">
                    <input type="text" name="fkeperluan" class="form-control" style="text-transform: capitalize;" placeholder="Contoh: Pengurusan akta kematian" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Keterangan (Opsional)</label>
                  <div class="col-sm-12">
                    <input type="text" name="fketerangan" class="form-control" style="text-transform: capitalize;" placeholder="(Opsional)">
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- =========================
                 PERSYARATAN BERKAS
            ========================= -->
            <h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Persyaratan Berkas</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">1. Surat Kematian</label>
                  <div class="col-sm-12">
                    <input type="file" name="surat_kematian" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">2. KK Termohon</label>
                  <div class="col-sm-12">
                    <input type="file" name="kk_termohon" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">3. KTP-el Termohon</label>
                  <div class="col-sm-12">
                    <input type="file" name="ktp_termohon" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">4. KK Ahli Waris</label>
                  <div class="col-sm-12">
                    <input type="file" name="kk_ahli_waris" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">5. Dokumen Lainnya (Opsional)</label>
                  <div class="col-sm-12">
                    <input type="file" name="dokumen_lainnya" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
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
    } // end if nik sama
  } else {
    header("location:index.php?pesan=gagal");
    exit;
  }

  include ('../part/footer.php');
?>
