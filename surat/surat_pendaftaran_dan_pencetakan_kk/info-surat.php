<?php
  session_start();
  include ('../../config/koneksi.php');
  include ('../part/header.php');

  $nik = $_POST['fnik'];

  $qCekNik = mysqli_query($connect,"SELECT * FROM penduduk WHERE nik = '$nik'");
  $row = mysqli_num_rows($qCekNik);

  if($row > 0){
    $data = mysqli_fetch_assoc($qCekNik);
    if($data['nik'] == $nik){

      // simpan sesi pemohon
      $_SESSION['nik'] = $nik;

      // ✅ Cek: jangan izinkan buat baru kalau masih ada paket PENDING untuk NIK yg sama
      // (kita cek di tabel KK paket, karena itu yang menandakan paket berjalan)
      $cek = mysqli_query($connect, "
        SELECT 1 FROM surat_pendaftaran_pencetakan_kk_kelahiran
        WHERE nik='$nik' AND status_surat='PENDING'
        LIMIT 1
      ");
      if ($cek && mysqli_num_rows($cek) > 0) {
        header("location:index.php?pesan=pending");
        exit;
      }

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
              <a class="col-sm-6"><h5><b>PAKET AKTA KELAHIRAN + CETAK KK (KARENA KELAHIRAN)</b></h5></a>
              <a class="col-sm-6"><h5><b>NOMOR SURAT : -</b></h5></a>
            </div>
          </div>
          <hr>

          <form method="post" action="simpan-surat.php" enctype="multipart/form-data">

            <!-- Informasi Pemohon -->
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

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Alamat Pemohon</label>
                  <div class="col-sm-12">
                    <textarea name="falamat_pemohon" class="form-control" readonly><?php echo $alamat_pemohon; ?></textarea>
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- DATA AKTA -->
            <h6 class="container-fluid" align="right"><i class="fas fa-baby"></i> Data Pengajuan Akta Kelahiran (Wajib)</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nama Bayi</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnama_bayi" class="form-control" required placeholder="Masukkan nama bayi">
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
                    <input type="text" name="ftempat_lahir_bayi" class="form-control" required placeholder="Contoh: RS / Kota">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Tanggal Lahir Bayi</label>
                  <div class="col-sm-12">
                    <input type="date" name="ftgl_lahir_bayi" class="form-control" required>
                    <small class="text-muted">Syarat: umur anak < 17 tahun</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Jam Lahir (opsional)</label>
                  <div class="col-sm-12">
                    <input type="text" name="fjam_lahir_bayi" class="form-control" placeholder="Contoh: 12:30">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Anak ke- (opsional)</label>
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
                    <input type="text" name="fpanjang_bayi" class="form-control" placeholder="Contoh: 49 cm">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">NIK Ayah</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnik_ayah" class="form-control" maxlength="20" required onkeypress="return hanyaAngka(event)" placeholder="Masukkan NIK ayah">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nama Ayah</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnama_ayah" class="form-control" required placeholder="Masukkan nama ayah">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">NIK Ibu</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnik_ibu" class="form-control" maxlength="20" required onkeypress="return hanyaAngka(event)" placeholder="Masukkan NIK ibu">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nama Ibu</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnama_ibu" class="form-control" required placeholder="Masukkan nama ibu">
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- Persyaratan Berkas Akta -->
            <h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Berkas Akta Kelahiran</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">1. Surat Kelahiran dari RS/Bidan</label>
                  <div class="col-sm-12">
                    <input type="file" name="fsurat_kelahiran_rs" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF (max 2MB)</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">2. FC Buku Nikah (opsional jika belum ada)</label>
                  <div class="col-sm-12">
                    <input type="file" name="ffc_buku_nikah" class="form-control">
                    <small class="text-muted">JPG/PNG/PDF (max 2MB)</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">3. KK Pemohon</label>
                  <div class="col-sm-12">
                    <input type="file" name="fkk_pemohon" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF (max 2MB)</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">4. KTP Ayah</label>
                  <div class="col-sm-12">
                    <input type="file" name="fktp_ayah" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF (max 2MB)</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">5. KTP Ibu</label>
                  <div class="col-sm-12">
                    <input type="file" name="fktp_ibu" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF (max 2MB)</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">6. Dokumen Pendukung Lain (opsional)</label>
                  <div class="col-sm-12">
                    <input type="file" name="fdokumen_pendukung_lain" class="form-control">
                    <small class="text-muted">JPG/PNG/PDF (max 2MB)</small>
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- DATA CETAK KK -->
            <h6 class="container-fluid" align="right"><i class="fas fa-users"></i> Data Pendaftaran & Pencetakan KK (Wajib)</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nomor KK Lama</label>
                  <div class="col-sm-12">
                    <input type="text" name="fno_kk_lama" class="form-control" required placeholder="Masukkan nomor KK lama" onkeypress="return hanyaAngka(event)">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nama Kepala Keluarga</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnama_kepala_keluarga" class="form-control" required placeholder="Masukkan nama kepala keluarga">
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Keperluan (untuk apa)</label>
                  <div class="col-sm-12">
                    <input type="text" name="fkeperluan_kk" class="form-control" required placeholder="Contoh: Penambahan anggota keluarga karena kelahiran">
                  </div>
                </div>
              </div>
            </div>

            <br>

            <!-- Berkas KK -->
            <h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Berkas Cetak KK</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">1. Scan KK Lama</label>
                  <div class="col-sm-12">
                    <input type="file" name="fkk_lama" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF (max 2MB)</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">2. KTP Pemohon (opsional)</label>
                  <div class="col-sm-12">
                    <input type="file" name="fktp_pemohon" class="form-control">
                    <small class="text-muted">JPG/PNG/PDF (max 2MB)</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="container-fluid mt-3">
              <div class="alert alert-warning">
                <b>Syarat:</b>
                <ol style="margin-bottom:0;">
                  <li>Pengajuan ini <b>wajib paket</b> dengan pembuatan <b>Akta Kelahiran</b>.</li>
                  <li>Anak <b>umur kurang dari 17 tahun</b> dan <b>belum kawin</b>.</li>
                </ol>
              </div>

              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="fbelum_kawin" value="1" id="belum_kawin" required>
                <label class="form-check-label" for="belum_kawin">
                  Saya menyatakan anak <b>belum kawin</b> dan <b>berumur kurang dari 17 tahun</b>.
                </label>
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

  <script>
    function hanyaAngka(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
      return true;
    }
  </script>
</body>

<?php
    }
  } else {
    header("location:index.php?pesan=gagal");
    exit;
  }

  include ('../part/footer.php');
?>
