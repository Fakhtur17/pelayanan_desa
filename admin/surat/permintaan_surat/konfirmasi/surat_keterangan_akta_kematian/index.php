<?php
  include ('../part/akses.php');
  include ('../../../../../config/koneksi.php');
  include ('../part/header.php');

  if(!isset($_GET['id'])){
    header("location:../../");
    exit;
  }

  $id = $_GET['id'];

  // ambil data penduduk + data surat akta kematian
  $qCek = mysqli_query($connect,"
    SELECT penduduk.*, surat_keterangan_akta_kematian.*
    FROM penduduk
    LEFT JOIN surat_keterangan_akta_kematian
      ON surat_keterangan_akta_kematian.nik = penduduk.nik
    WHERE surat_keterangan_akta_kematian.id_skkm = '$id'
  ");

  if(!$qCek || mysqli_num_rows($qCek) == 0){
    echo "<div class='alert alert-danger'>Data surat tidak ditemukan.</div>";
    include ('../part/footer.php');
    exit;
  }

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
  $baseUploadUrl = "../../../../../uploads/akta_kematian/";

  // ✅ fungsi tampilan file (lebih bagus + ada tombol unduh lewat download.php)
  function renderFileCard($label, $fileName, $baseUrl){
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

      // ✅ pakai download.php biar dipaksa download
      echo '        <a class="btn btn-success btn-sm" href="download.php?file='.urlencode($fileName).'">';
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
?>

<style>
  .file-card{
    border:1px solid #e5e7eb;
    border-radius:12px;
    background:#fff;
    box-shadow:0 2px 10px rgba(0,0,0,.04);
    overflow:hidden;
    height:100%;
  }
  .file-card-head{
    padding:10px 12px;
    background:#f9fafb;
    border-bottom:1px solid #e5e7eb;
  }
  .file-title{
    font-weight:700;
    color:#111827;
  }
  .file-card-body{
    padding:12px;
  }
  .file-meta{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:10px;
  }
  .badge-ext{
    display:inline-block;
    padding:3px 10px;
    border-radius:999px;
    font-size:11px;
    border:1px solid #e5e7eb;
    background:#fff;
    color:#374151;
    font-weight:700;
  }
  .file-name{
    font-size:12px;
    color:#6b7280;
    word-break:break-all;
  }
  .file-actions{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    margin-bottom:10px;
  }
  .file-preview{
    border:1px solid #e5e7eb;
    border-radius:10px;
    overflow:hidden;
    background:#f3f4f6;
  }
  .file-preview img{
    max-width: 100%;
    max-height: 260px;
    object-fit: contain;
    display: block;
    margin: auto;
  }
  .file-note{
    font-size:12px;
  }
</style>

<?php while($row = mysqli_fetch_array($qCek)){ ?>

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
            <h2 class="box-title"><i class="fas fa-envelope"></i> Konfirmasi Surat Keterangan Akta Kematian</h2>
          </div>

          <div class="box-body">
            <form class="form-horizontal" method="post" action="update-konfirmasi.php">

              <!-- TTD & NO SURAT -->
              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Tanda Tangan</label>
                      <div class="col-sm-9">
                        <select name="fid_pejabat_desa" class="form-control" style="text-transform: uppercase;" required>
                          <option value="">-- Pilih Tanda Tangan --</option>
                          <?php
                            $selectedPejabat = $row['id_pejabat_desa'];
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

                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">No. Surat</label>
                      <div class="col-sm-9">
                        <input type="text" name="fno_surat" value="<?php echo $row['no_surat']; ?>" class="form-control" placeholder="Masukkan No. Surat" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- INFORMASI PENDUDUK -->
              <h5 class="box-title pull-right" style="color: #696969;">
                <i class="fas fa-info-circle"></i> <b>Informasi Penduduk</b>
              </h5>
              <br><hr style="border-bottom: 1px solid #DCDCDC;">

              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Nama Lengkap</label>
                      <div class="col-sm-9">
                        <input type="text" style="text-transform: uppercase;" value="<?php echo $row['nama']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Tempat, Tgl Lahir</label>
                      <div class="col-sm-9">
                        <input type="text" style="text-transform: capitalize;" value="<?php echo $row['tempat_lahir'].", ".tglIndo($row['tgl_lahir']); ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Pekerjaan</label>
                      <div class="col-sm-9">
                        <input type="text" style="text-transform: capitalize;" value="<?php echo $row['pekerjaan']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Alamat</label>
                      <div class="col-sm-9">
                        <textarea rows="3" class="form-control" style="text-transform: capitalize;" readonly><?php echo $row['jalan'].", RT".$row['rt']."/RW".$row['rw'].", Dusun ".$row['dusun'].", Desa ".$row['desa'].", Kecamatan ".$row['kecamatan'].", ".$row['kota']; ?></textarea>
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
                        <input type="text" style="text-transform: capitalize;" value="<?php echo $row['agama']; ?>" class="form-control" readonly>
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
                        <input type="text" style="text-transform: uppercase;" value="<?php echo $row['kewarganegaraan']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- INFORMASI AKTA KEMATIAN -->
              <h5 class="box-title pull-right" style="color: #696969;">
                <i class="fas fa-info-circle"></i> <b>Informasi Surat Akta Kematian</b>
              </h5>
              <br><hr style="border-bottom: 1px solid #DCDCDC;">

              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Nama Almarhum</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['nama_almarhum']; ?>" class="form-control" readonly>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-3 control-label">Tanggal Meninggal</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo tglIndo($row['tgl_meninggal']); ?>" class="form-control" readonly>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-3 control-label">Tempat Meninggal</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['tempat_meninggal']; ?>" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Sebab Meninggal</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo !empty($row['sebab_meninggal']) ? $row['sebab_meninggal'] : '-'; ?>" class="form-control" readonly>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-3 control-label">Keperluan</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo $row['keperluan']; ?>" class="form-control" readonly>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-3 control-label">Keterangan</label>
                      <div class="col-sm-9">
                        <input type="text" value="<?php echo !empty($row['keterangan']) ? $row['keterangan'] : '-'; ?>" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ✅ PERSYARATAN BERKAS -->
              <h5 class="box-title pull-right" style="color: #696969;">
                <i class="fas fa-paperclip"></i> <b>Persyaratan Berkas</b>
              </h5>
              <br><hr style="border-bottom: 1px solid #DCDCDC;">

              <div class="row">
                <?php
                  renderFileCard("Surat Kematian", $row['surat_kematian'], $baseUploadUrl);
                  renderFileCard("KK Termohon", $row['kk_termohon'], $baseUploadUrl);
                  renderFileCard("KTP-el Termohon", $row['ktp_termohon'], $baseUploadUrl);
                  renderFileCard("KK Ahli Waris", $row['kk_ahli_waris'], $baseUploadUrl);
                  renderFileCard("Dokumen Lainnya", $row['dokumen_lainnya'], $baseUploadUrl);
                ?>
              </div>

              <!-- HIDDEN ID + SUBMIT -->
              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <input type="hidden" name="id" value="<?php echo $row['id_skkm']; ?>">
                  </div>
                </div>

                <div class="col-md-6">
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

<?php } include ('../part/footer.php'); ?>
