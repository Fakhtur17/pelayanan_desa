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
              <a class="col-sm-6"><h5><b>SURAT PERUBAHAN NAMA CAPIL</b></h5></a>
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

            <h6 class="container-fluid" align="right"><i class="fas fa-edit"></i> Data Perubahan Nama</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nama Lama (sesuai dokumen)</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnama_lama" class="form-control" required placeholder="Masukkan nama lama">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Nama Baru (yang diajukan)</label>
                  <div class="col-sm-12">
                    <input type="text" name="fnama_baru" class="form-control" required placeholder="Masukkan nama baru">
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">Alasan Perubahan (opsional)</label>
                  <div class="col-sm-12">
                    <textarea name="falasan" class="form-control" placeholder="Contoh: penyesuaian dengan ijazah / kesalahan penulisan / dll"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <br>

            <h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Persyaratan Berkas (Wajib)</h6>
            <hr width="97%">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">1. Foto Asli Kutipan Akta Capil</label>
                  <div class="col-sm-12">
                    <input type="file" name="ffile_kutipan_akta" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">2. Foto KK Termohon</label>
                  <div class="col-sm-12">
                    <input type="file" name="ffile_kk_termohon" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">3. Dokumen Negara/Ijazah (Rujukan Perubahan Nama)</label>
                  <div class="col-sm-12">
                    <input type="file" name="ffile_ijazah_rujukan" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF</small>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="col-sm-12" style="font-weight: 500;">4. SPTJM Kebenaran Data</label>
                  <div class="col-sm-12">
                    <input type="file" name="ffile_sptjm_kebenaran" class="form-control" required>
                    <small class="text-muted">JPG/PNG/PDF</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="container-fluid mt-3">
              <div class="alert alert-warning">
                <b>Catatan:</b> Setelah pengajuan berhasil, pemohon akan mendapatkan <b>No. Tanda Terima</b>
                sebagai bukti untuk <b>pengambilan surat</b> di Balai Desa.
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
    } else {
      header("location:index.php?pesan=gagal");
    }

  include ('../part/footer.php');
?>
