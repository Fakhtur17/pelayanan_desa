<?php
  include ('../part/akses.php');
  include ('../../../../../config/koneksi.php');
  include ('../part/header.php');

  if(!isset($_GET['id']) || $_GET['id'] == ''){
    header("location:../../");
    exit;
  }

  $id = mysqli_real_escape_string($connect, $_GET['id']);

  // Ambil 1 data surat pembetulan
  $qCek = mysqli_query($connect,"
    SELECT p.*, s.*
    FROM penduduk p
    JOIN surat_pembetulan_akta_capil s ON s.nik = p.nik
    WHERE s.id_spac = '$id'
    LIMIT 1
  ");

  if(!$qCek || mysqli_num_rows($qCek) == 0){
    echo "<div class='alert alert-danger'>Data surat tidak ditemukan.</div>";
    include ('../part/footer.php');
    exit;
  }

  $row = mysqli_fetch_assoc($qCek);

  function tglIndo($tanggal){
    if(empty($tanggal)) return "-";
    $blnIndo = array(
      'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni',
      'July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
    );
    return date('d ', strtotime($tanggal)).$blnIndo[date('F', strtotime($tanggal))].date(' Y', strtotime($tanggal));
  }

  // Folder upload pembetulan (pastikan bener sesuai punyamu)
  $baseUploadUrl = "../../../../../uploads/pembetulan_akta_capil/";

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

      echo '        <a class="btn btn-success btn-sm" href="download.php?file='.urlencode($fileName).'">';
      echo '          <i class="fa fa-download"></i> Unduh';
      echo '        </a>';
      echo '      </div>';

      if($isImg){
        echo '      <div class="file-preview"><img src="'.$url.'" alt="'.$safeLabel.'"></div>';
      } else {
        echo '      <div class="file-note text-muted"><i class="fa fa-info-circle"></i> Preview hanya untuk gambar. Klik "Lihat".</div>';
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
  .file-card{border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.04);overflow:hidden;height:100%;}
  .file-card-head{padding:10px 12px;background:#f9fafb;border-bottom:1px solid #e5e7eb;}
  .file-title{font-weight:700;color:#111827;}
  .file-card-body{padding:12px;}
  .file-meta{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
  .badge-ext{display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;border:1px solid #e5e7eb;background:#fff;color:#374151;font-weight:700;}
  .file-name{font-size:12px;color:#6b7280;word-break:break-all;}
  .file-actions{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px;}
  .file-preview{border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;background:#f3f4f6;}
  .file-preview img{max-width:100%;max-height:260px;object-fit:contain;display:block;margin:auto;}
  .file-note{font-size:12px;}
</style>

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
            <h2 class="box-title"><i class="fas fa-envelope"></i> Konfirmasi Surat Pembetulan Akta Capil</h2>
          </div>

          <div class="box-body">
            <form class="form-horizontal" method="post" action="update-konfirmasi.php">
              <div class="row">
                <div class="col-md-6">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Tanda Tangan</label>
                      <div class="col-sm-9">
                        <select name="fid_pejabat_desa" class="form-control" required>
                          <option value="">-- Pilih Tanda Tangan --</option>
                          <?php
                            $selectedPejabat = $row['id_pejabat_desa'];
                            $tampilPejabat = mysqli_query($connect, "SELECT * FROM pejabat_desa");
                            while($p = mysqli_fetch_assoc($tampilPejabat)){
                              $sel = ($p['id_pejabat_desa'] == $selectedPejabat) ? 'selected' : '';
                              echo '<option value="'.$p['id_pejabat_desa'].'" '.$sel.'>'.$p['jabatan'].' ('.$p['nama_pejabat_desa'].')</option>';
                            }
                          ?>
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
                        <input type="text" name="fno_surat" value="<?php echo htmlspecialchars($row['no_surat']); ?>" class="form-control" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <hr>

              <h4><b>Informasi Surat Pembetulan</b></h4>

              <div class="form-group">
                <label class="col-sm-2 control-label">Jenis Akta</label>
                <div class="col-sm-10">
                  <input type="text" value="<?php echo htmlspecialchars($row['jenis_akta']); ?>" class="form-control" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">No Akta</label>
                <div class="col-sm-10">
                  <input type="text" value="<?php echo htmlspecialchars($row['no_akta']); ?>" class="form-control" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Bagian Dibetulkan</label>
                <div class="col-sm-10">
                  <input type="text" value="<?php echo htmlspecialchars($row['bagian_dibetulkan']); ?>" class="form-control" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Data Sebelum</label>
                <div class="col-sm-10">
                  <input type="text" value="<?php echo htmlspecialchars($row['data_sebelum']); ?>" class="form-control" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Data Sesudah</label>
                <div class="col-sm-10">
                  <input type="text" value="<?php echo htmlspecialchars($row['data_sesudah']); ?>" class="form-control" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Keperluan</label>
                <div class="col-sm-10">
                  <input type="text" value="<?php echo htmlspecialchars($row['keperluan']); ?>" class="form-control" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">Keterangan</label>
                <div class="col-sm-10">
                  <textarea rows="2" class="form-control" readonly><?php echo htmlspecialchars($row['keterangan']); ?></textarea>
                </div>
              </div>

              <hr>

              <h4><b>Persyaratan Berkas</b></h4>
              <div class="row">
                <?php
                  renderFileCard("1. Foto Kutipan Akta", $row['foto_kutipan_akta'], $baseUploadUrl);
                  renderFileCard("2. Foto KK Termohon", $row['foto_kk_termohon'], $baseUploadUrl);
                ?>
              </div>

              <!-- ID pembetulan -->
              <input type="hidden" name="id" value="<?php echo $row['id_spac']; ?>">

              <div class="box-body pull-right">
                <input type="submit" name="submit" class="btn btn-success" value="Konfirmasi">
                <a href="../../" class="btn btn-default">Kembali</a>
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
