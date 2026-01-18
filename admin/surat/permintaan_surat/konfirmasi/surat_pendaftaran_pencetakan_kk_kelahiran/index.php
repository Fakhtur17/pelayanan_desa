<?php
include ('../part/akses.php');
include ('../../../../../config/koneksi.php');
include ('../part/header.php');

if(!isset($_GET['id'])){
  header("location:../../");
  exit;
}

$id = $_GET['id']; // ini id surat KK paket (misal id_spkkk)

// ===== helper tanggal indo =====
function tglIndo($tanggal){
  if(empty($tanggal)) return "-";
  $blnIndo = array(
    'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni',
    'July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
  );
  $tgl = date('d ', strtotime($tanggal));
  $bln = date('F', strtotime($tanggal));
  $thn = date(' Y', strtotime($tanggal));
  return $tgl.$blnIndo[$bln].$thn;
}

// URL folder upload (public)
$baseUrlAkta = "../../../../../uploads/akta_kelahiran/";
$baseUrlKK   = "../../../../../uploads/kk_kelahiran/";

// render file card (mirip punyamu)
function renderFileCard($label, $fileName, $baseUrl, $downloadType){
  $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');

  echo '<div class="col-md-6" style="margin-bottom:14px;">';
  echo '  <div class="file-card">';
  echo '    <div class="file-card-head">';
  echo '      <div class="file-title"><i class="fa fa-paperclip"></i> '.$safeLabel.'</div>';
  echo '    </div>';

  if(!empty($fileName)){
    $safeFile = htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8');
    $url      = $baseUrl.$safeFile;

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp']);

    echo '    <div class="file-card-body">';
    echo '      <div class="file-meta">';
    echo '        <span class="badge-ext">'.strtoupper($ext).'</span>';
    echo '        <span class="file-name">'.$safeFile.'</span>';
    echo '      </div>';

    echo '      <div class="file-actions">';
    echo '        <a class="btn btn-info btn-sm" href="'.$url.'" target="_blank" rel="noopener">';
    echo '          <i class="fa fa-eye"></i> Lihat';
    echo '        </a>';
    echo '        <a class="btn btn-success btn-sm" href="download.php?type='.$downloadType.'&file='.urlencode($fileName).'">';
    echo '          <i class="fa fa-download"></i> Unduh';
    echo '        </a>';
    echo '      </div>';

    if($isImg){
      echo '      <div class="file-preview">';
      echo '        <img src="'.$url.'" alt="'.$safeLabel.'">';
      echo '      </div>';
    } else {
      echo '      <div class="file-note text-muted">';
      echo '        <i class="fa fa-info-circle"></i> Preview hanya untuk gambar. Klik "Lihat" untuk membuka berkas.';
      echo '      </div>';
    }

    echo '    </div>';
  } else {
    echo '    <div class="file-card-body">';
    echo '      <div class="text-danger" style="font-weight:600;"><i class="fa fa-times"></i> Tidak ada berkas</div>';
    echo '    </div>';
  }

  echo '  </div>';
  echo '</div>';
}

// ===== ambil data: penduduk + akta + kk paket =====
// NOTE: GANTI nama PK KK jika beda (id_spkkk)
$qCek = mysqli_query($connect,"
  SELECT 
    p.*,

    -- AKTA
    a.id_spak AS akta_id_spak,
    a.no_surat AS akta_no_surat,
    a.nama_bayi, a.jenis_kelamin_bayi, a.tempat_lahir_bayi, a.tgl_lahir_bayi, a.jam_lahir_bayi,
    a.anak_ke, a.berat_bayi, a.panjang_bayi,
    a.nik_ayah, a.nama_ayah, a.nik_ibu, a.nama_ibu,
    a.alamat_pemohon AS akta_alamat_pemohon,
    a.surat_kelahiran_rs, a.fc_buku_nikah, a.kk_pemohon, a.ktp_ayah, a.ktp_ibu,
    a.dokumen_pendukung_lain, a.keterangan AS akta_keterangan,
    a.status_surat AS akta_status_surat,
    a.tanggal_surat AS akta_tanggal_surat,
    a.id_pejabat_desa AS akta_id_pejabat_desa,

    -- KK PAKET
    k.id_spkkk AS kk_id_spkkk,
    k.no_surat AS kk_no_surat,
    k.no_kk_lama, k.nama_kepala_keluarga, k.alasan, k.keperluan AS kk_keperluan,
    k.kk_lama, k.ktp_pemohon,
    k.status_surat AS kk_status_surat,
    k.tanggal_surat AS kk_tanggal_surat,
    k.id_pejabat_desa AS kk_id_pejabat_desa

  FROM surat_pendaftaran_pencetakan_kk_kelahiran k
  LEFT JOIN surat_pengajuan_akta_kelahiran a ON a.id_spak = k.id_spak
  LEFT JOIN penduduk p ON p.nik = k.nik
  WHERE k.id_spkkk = '$id'
  LIMIT 1
");

if(!$qCek || mysqli_num_rows($qCek) == 0){
  echo "<div class='alert alert-danger'>Data paket surat tidak ditemukan.</div>";
  include ('../part/footer.php');
  exit;
}

$row = mysqli_fetch_assoc($qCek);
?>

<style>
  .file-card{
  border:1px solid #e5e7eb;
  border-radius:12px;
  background:#fff;
  box-shadow:0 2px 10px rgba(0,0,0,.04);
  overflow:hidden;

  height: 260px;              /* 🔒 TINGGI FIXED */
  display: flex;
  flex-direction: column;
}

  .file-card-head{padding:10px 12px;background:#f9fafb;border-bottom:1px solid #e5e7eb;}
  .file-title{font-weight:700;color:#111827;}
  .file-card-body{
  padding:12px;
  flex: 1;                    /* 🔒 isi mengikuti card */
  display: flex;
  flex-direction: column;
}

  .file-meta{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
  .badge-ext{display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;border:1px solid #e5e7eb;background:#fff;color:#374151;font-weight:700;}
  .file-name{
  font-size:12px;
  color:#6b7280;

  white-space: nowrap;        /* 🔒 satu baris */
  overflow: hidden;
  text-overflow: ellipsis;    /* 🔒 titik-titik */
}

  .file-actions{
  margin-top: auto;           /* 🔒 dorong ke bawah */
  display:flex;
  gap:8px;
  flex-wrap:wrap;
}

  .file-preview{
  height: 120px;              /* 🔒 preview fixed */
  border:1px solid #e5e7eb;
  border-radius:10px;
  overflow:hidden;
  background:#f3f4f6;
  display:flex;
  align-items:center;
  justify-content:center;
  margin-bottom:8px;
}

.file-preview img{
  max-width:100%;
  max-height:100%;
  object-fit:contain;
}

  .file-note{font-size:12px;}
  .file-empty{
  margin:auto;
  color:#ef4444;
  font-weight:600;
  text-align:center;
}

</style>

<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <?php  
          if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){
            echo '<img src="../../../../../assets/img/ava-admin-female.png" class="img-circle" alt="User Image">';
          }else if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Kepala Desa')){
            echo '<img src="../../../../../assets/img/ava-kades.png" class="img-circle" alt="User Image">';
          }
        ?>
      </div>
      <div class="pull-left info">
        <p><?php echo $_SESSION['lvl']; ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li><a href="../../../../dashboard/"><i class="fas fa-tachometer-alt"></i> <span>&nbsp;&nbsp;Dashboard</span></a></li>
      <li><a href="../../../../penduduk/"><i class="fa fa-users"></i><span>&nbsp;Data Penduduk</span></a></li>

      <li class="active treeview">
        <a href="#"><i class="fas fa-envelope-open-text"></i> <span>&nbsp;&nbsp;Surat</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="active"><a href="../../../permintaan_surat/"><i class="fa fa-circle-notch"></i> Permintaan Surat</a></li>
          <li><a href="../../../surat_selesai/"><i class="fa fa-circle-notch"></i> Surat Selesai</a></li>
        </ul>
      </li>

      <li><a href="../../../../laporan/"><i class="fas fa-chart-line"></i> <span>&nbsp;&nbsp;Laporan</span></a></li>
    </ul>
  </section>
</aside>

<div class="content-wrapper">
  <section class="content-header">
    <h1>&nbsp;</h1>
    <ol class="breadcrumb">
      <li><a href="../../../../dashboard/"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Permintaan Surat</li>
    </ol>
  </section>

  <section class="content">      
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
          <div class="box-header with-border">
            <h2 class="box-title"><i class="fas fa-envelope"></i> Konfirmasi Paket Akta Kelahiran + Pencetakan KK</h2>
          </div>

          <div class="box-body">
            <form class="form-horizontal" method="post" action="update-konfirmasi.php">

              <!-- TTD & NO SURAT (2 nomor surat: AKTA dan KK) -->
              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Tanda Tangan</label>
                      <div class="col-sm-9">
                        <select name="fid_pejabat_desa" class="form-control" required>
                          <option value="">-- Pilih Tanda Tangan --</option>
                          <?php
                            $selectedPejabat = !empty($row['kk_id_pejabat_desa']) ? $row['kk_id_pejabat_desa'] : $row['akta_id_pejabat_desa'];
                            $tampilPejabat = mysqli_query($connect, "SELECT * FROM pejabat_desa");
                            while($p = mysqli_fetch_assoc($tampilPejabat)){
                              $sel = ($p['id_pejabat_desa'] == $selectedPejabat) ? 'selected="selected"' : '';
                          ?>
                            <option value="<?php echo $p['id_pejabat_desa']; ?>" <?php echo $sel; ?>>
                              <?php echo $p['jabatan']." (".$p['nama_pejabat_desa'].")"; ?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">No. Surat Akta</label>
                      <div class="col-sm-8">
                        <input type="text" name="fno_surat_akta" value="<?php echo $row['akta_no_surat']; ?>" class="form-control" placeholder="No surat akta" required>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">No. Surat KK</label>
                      <div class="col-sm-8">
                        <input type="text" name="fno_surat_kk" value="<?php echo $row['kk_no_surat']; ?>" class="form-control" placeholder="No surat KK" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- hidden ids -->
              <input type="hidden" name="id_spkkk" value="<?php echo $row['kk_id_spkkk']; ?>">
              <input type="hidden" name="id_spak" value="<?php echo $row['akta_id_spak']; ?>">

              <!-- INFORMASI PENDUDUK -->
              <h5 class="box-title pull-right" style="color:#696969;">
                <i class="fas fa-info-circle"></i> <b>Informasi Penduduk</b>
              </h5>
              <br><hr style="border-bottom:1px solid #DCDCDC;">

              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Nama Lengkap</label>
                      <div class="col-sm-9">
                        <input type="text" style="text-transform:uppercase;" value="<?php echo $row['nama']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Tempat, Tgl Lahir</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['tempat_lahir'].", ".tglIndo($row['tgl_lahir']); ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Pekerjaan</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['pekerjaan']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Alamat</label>
                      <div class="col-sm-9">
                        <textarea rows="3" class="form-control" readonly><?php
                          echo $row['jalan'].", RT".$row['rt']."/RW".$row['rw'].", Dusun ".$row['dusun'].", Desa ".$row['desa'].", Kecamatan ".$row['kecamatan'].", ".$row['kota'];
                        ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Jenis Kelamin</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['jenis_kelamin']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Agama</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['agama']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">NIK</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['nik']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Kewarganegaraan</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['kewarganegaraan']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- INFO AKTA -->
              <h5 class="box-title pull-right" style="color:#696969;">
                <i class="fas fa-baby"></i> <b>Informasi Akta Kelahiran</b>
              </h5>
              <br><hr style="border-bottom:1px solid #DCDCDC;">

              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Nama Bayi</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['nama_bayi']; ?>" class="form-control" readonly></div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">TTL Bayi</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['tempat_lahir_bayi'].", ".tglIndo($row['tgl_lahir_bayi']); ?>" class="form-control" readonly></div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">JK</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['jenis_kelamin_bayi']; ?>" class="form-control" readonly></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Ayah</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['nama_ayah']." (".$row['nik_ayah'].")"; ?>" class="form-control" readonly></div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Ibu</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['nama_ibu']." (".$row['nik_ibu'].")"; ?>" class="form-control" readonly></div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Keterangan</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['akta_keterangan']; ?>" class="form-control" readonly></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- INFO KK -->
              <h5 class="box-title pull-right" style="color:#696969;">
                <i class="fas fa-id-card"></i> <b>Informasi Pencetakan KK</b>
              </h5>
              <br><hr style="border-bottom:1px solid #DCDCDC;">

              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">No KK Lama</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['no_kk_lama']; ?>" class="form-control" readonly></div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Kepala Keluarga</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['nama_kepala_keluarga']; ?>" class="form-control" readonly></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Keperluan</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['kk_keperluan']; ?>" class="form-control" readonly></div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">Alasan</label>
                      <div class="col-sm-8"><input type="text" value="<?php echo $row['alasan']; ?>" class="form-control" readonly></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- PERSYARATAN BERKAS -->
              <h5 class="box-title pull-right" style="color:#696969;">
                <i class="fas fa-paperclip"></i> <b>Persyaratan Berkas</b>
              </h5>
              <br><hr style="border-bottom:1px solid #DCDCDC;">

              <div class="row">
                <?php
                  // BERKAS AKTA
                  renderFileCard("Surat Kelahiran (RS/Bidan)", $row['surat_kelahiran_rs'], $baseUrlAkta, "akta");
                  renderFileCard("FC Buku Nikah (opsional)", $row['fc_buku_nikah'], $baseUrlAkta, "akta");
                  renderFileCard("KK Pemohon (Akta)", $row['kk_pemohon'], $baseUrlAkta, "akta");
                  renderFileCard("KTP Ayah", $row['ktp_ayah'], $baseUrlAkta, "akta");
                  renderFileCard("KTP Ibu", $row['ktp_ibu'], $baseUrlAkta, "akta");
                  renderFileCard("Dokumen Pendukung Lain (opsional)", $row['dokumen_pendukung_lain'], $baseUrlAkta, "akta");

                  // BERKAS KK
                  renderFileCard("Scan KK Lama", $row['kk_lama'], $baseUrlKK, "kk");
                  renderFileCard("KTP Pemohon (opsional)", $row['ktp_pemohon'], $baseUrlKK, "kk");
                ?>
              </div>

              <!-- SUBMIT -->
              <div class="row">
                <div class="col-md-12">
                  <div class="box-body pull-right">
                    <input type="submit" name="submit" class="btn btn-success" value="Konfirmasi">
                    <a href="../../" class="btn btn-default">Kembali</a>
                  </div>
                </div>
              </div>

            </form>
          </div>

          <div class="box-footer"></div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include ('../part/footer.php'); ?>
