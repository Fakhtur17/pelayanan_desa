<?php
include('../../config/koneksi.php');
include('../part/header.php');

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

// alamat pemohon
$alamat_pemohon = $data['jalan'] . ", RT" . $data['rt'] . "/RW" . $data['rw'] . ", Dusun " . $data['dusun'] . ",\nDesa " . $data['desa'] . ", Kecamatan " . $data['kecamatan'] . ", " . $data['kota'];
?>

<body class="bg-light">
<div class="container" style="padding-top:30px; padding-bottom:60px; min-height:100%;">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <h5 class="card-header"><i class="fas fa-envelope"></i> INFORMASI SURAT</h5>
        <br>

        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6"><h5><b>SURAT LAPOR HAJATAN</b></h5></div>
            <div class="col-sm-6 text-right"><h5><b>NOMOR SURAT : -</b></h5></div>
          </div>
        </div>

        <hr>

        <!-- WAJIB enctype karena upload file -->
        <form method="post" action="simpan-surat.php" enctype="multipart/form-data">

          <!-- ================= INFORMASI PEMOHON ================= -->
          <h6 class="container-fluid" align="right"><i class="fas fa-user"></i> Informasi Pemohon</h6>
          <hr width="97%">

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Nama Lengkap</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" value="<?php echo $data['nama']; ?>" readonly>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Jenis Kelamin</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" value="<?php echo $data['jenis_kelamin']; ?>" readonly>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Agama</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" value="<?php echo $data['agama']; ?>" readonly>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Pekerjaan</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" value="<?php echo $data['pekerjaan']; ?>" readonly>
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
                <label class="col-sm-12" style="font-weight:500;">Alamat</label>
                <div class="col-sm-12">
                  <textarea class="form-control" readonly><?php echo $alamat_pemohon; ?></textarea>
                </div>
              </div>
            </div>
          </div>

          <br>

          <!-- ================= PERSYARATAN (UPLOAD) ================= -->
          <h6 class="container-fluid" align="right"><i class="fas fa-paperclip"></i> Persyaratan Berkas</h6>
          <hr width="97%">

          <div class="row">
            <!-- Nomor KTP (teks) -->
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Nomor KTP (Opsional)</label>
                <div class="col-sm-12">
                  <input type="text" name="fbukti_ktp" class="form-control" maxlength="16"
                         onkeypress="return hanyaAngka(event)" placeholder="Masukkan Nomor KTP (Opsional)">
                </div>
              </div>
            </div>

            <!-- Nomor KK (teks) -->
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Nomor KK (Opsional)</label>
                <div class="col-sm-12">
                  <input type="text" name="fbukti_kk" class="form-control" maxlength="16"
                         onkeypress="return hanyaAngka(event)" placeholder="Masukkan Nomor KK (Opsional)">
                </div>
              </div>
            </div>

            <!-- Upload KTP -->
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Upload KTP (Wajib)</label>
                <div class="col-sm-12">
                  <input type="file" name="ktp" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
              </div>
            </div>

            <!-- Upload KK -->
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Upload KK (Opsional)</label>
                <div class="col-sm-12">
                  <input type="file" name="kk" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                </div>
              </div>
            </div>
          </div>

          <br>

          <!-- ================= FORM HAJATAN ================= -->
          <h6 class="container-fluid" align="right"><i class="fas fa-edit"></i> Formulir Hajatan</h6>
          <hr width="97%">

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Jenis Hajat</label>
                <div class="col-sm-12">
                  <select name="fjenis_hajat" class="form-control" required>
                    <option value="">-- Jenis Hajat --</option>
                    <option value="Pernikahan">Pernikahan</option>
                    <option value="Khitanan">Khitanan</option>
                    <option value="Tasyakuran">Tasyakuran</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Hari</label>
                <div class="col-sm-12">
                  <select name="fhari" class="form-control" required>
                    <option value="">-- Hari --</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jum'at">Jum'at</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Tanggal</label>
                <div class="col-sm-12">
                  <input type="date" name="ftanggal" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Jenis Hiburan</label>
                <div class="col-sm-12">
                  <input type="text" name="fjenis_hiburan" class="form-control" style="text-transform:capitalize;"
                         placeholder="Masukkan Jenis Hiburan" required>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Pemilik Hiburan</label>
                <div class="col-sm-12">
                  <input type="text" name="fpemilik" class="form-control" style="text-transform:capitalize;"
                         placeholder="Masukkan Pemilik Hiburan" required>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="col-sm-12" style="font-weight:500;">Alamat Pemilik Hiburan</label>
                <div class="col-sm-12">
                  <input type="text" name="falamat_pemilik" class="form-control" style="text-transform:capitalize;"
                         placeholder="Masukkan Alamat Pemilik Hiburan" required>
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

<?php include('../part/footer.php'); ?>
