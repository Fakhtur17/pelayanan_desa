<?php
include ('../../config/koneksi.php');
include ('../part/akses.php');

/* =========================
   KONFIG BULAN INDO
========================= */
$bulanIndo = array(
  'January' => 'Januari',
  'February' => 'Februari',
  'March' => 'Maret',
  'April' => 'April',
  'May' => 'Mei',
  'June' => 'Juni',
  'July' => 'Juli',
  'August' => 'Agustus',
  'September' => 'September',
  'October' => 'Oktober',
  'November' => 'November',
  'December' => 'Desember'
);

/* =========================
   BUILDER WHERE FILTER
========================= */
function buildWhereFilter($connect, $field){
  $where = "";

  if (isset($_GET['filter']) && $_GET['filter'] == '2' && !empty($_GET['tanggal'])) {
    $tgl = mysqli_real_escape_string($connect, $_GET['tanggal']);
    $where .= " AND DATE($field) = '$tgl' ";
  }

  if (isset($_GET['filter']) && $_GET['filter'] == '3' && !empty($_GET['bulan']) && !empty($_GET['tahun'])) {
    $bulan = (int) $_GET['bulan'];
    $tahun = (int) $_GET['tahun'];
    $where .= " AND MONTH($field) = $bulan AND YEAR($field) = $tahun ";
  }

  if (isset($_GET['filter']) && $_GET['filter'] == '4' && !empty($_GET['tahun'])) {
    $tahun = (int) $_GET['tahun'];
    $where .= " AND YEAR($field) = $tahun ";
  }

  return $where;
}

$whereTanggalSurat = buildWhereFilter($connect, "s.tanggal_surat");
$whereCreatedAt    = buildWhereFilter($connect, "s.created_at");

/* =========================
   QUERY UTAMA (UNION)
========================= */
$query = "
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_berkelakuan_baik s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_domisili s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_usaha s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_lapor_hajatan s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pengantar_skck s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  /* ===== TAMBAHAN SURAT ===== */
  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_cetak_kembali_akta_capil s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_akta_kematian s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_kepemilikan_kendaraan_bermotor s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_perhiasan s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pembetulan_akta_capil s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pengajuan_akta_kelahiran s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pendaftaran_pencetakan_kk_kelahiran s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.created_at AS tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_perubahan_nama_capil s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereCreatedAt

  ORDER BY tanggal_surat DESC
";

/* =========================
   MODE EXPORT (HARUS DI ATAS HEADER ADMIN)
========================= */
$isExport = (isset($_GET['export']) && $_GET['export'] == '1');

if($isExport){
  // supaya warning tidak ikut ke excel
  error_reporting(0);
  ini_set('display_errors', 0);

  // bersihkan buffer output (kalau ada)
  if (ob_get_length()) { ob_end_clean(); }

  // nama file dinamis
  $suffix = "semua_waktu";
  if(isset($_GET['filter'])){
    if($_GET['filter']=='2' && !empty($_GET['tanggal'])) $suffix = "tanggal_".$_GET['tanggal'];
    if($_GET['filter']=='3' && !empty($_GET['bulan']) && !empty($_GET['tahun'])) $suffix = "bulan_".$_GET['bulan']."_".$_GET['tahun'];
    if($_GET['filter']=='4' && !empty($_GET['tahun'])) $suffix = "tahun_".$_GET['tahun'];
  }
  $filename = "laporan_surat_keluar_".$suffix.".xls";

  header("Content-Type: application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".$filename);
  header("Pragma: no-cache");
  header("Expires: 0");

  $sql = mysqli_query($connect, $query);
  if(!$sql){
    echo "QUERY ERROR: ".mysqli_error($connect);
    exit;
  }

  echo '<table border="1">';
  echo '<thead>
    <tr>
      <th>No</th>
      <th>No. Surat</th>
      <th>Tanggal Surat</th>
      <th>Jenis Surat</th>
      <th>NIK</th>
      <th>No KK</th>
      <th>Nama</th>
      <th>Tempat/Tgl Lahir</th>
      <th>Jenis Kelamin</th>
      <th>Agama</th>
      <th>Jalan</th>
      <th>Dusun</th>
      <th>RT</th>
      <th>RW</th>
      <th>Desa</th>
      <th>Kecamatan</th>
      <th>Kota</th>
      <th>Pendidikan di KK</th>
      <th>Pendidikan Terakhir</th>
      <th>Pendidikan Ditempuh</th>
      <th>Pekerjaan</th>
      <th>Status Perkawinan</th>
      <th>Status Dlm Keluarga</th>
      <th>Kewarganegaraan</th>
      <th>Nama Ayah</th>
      <th>Nama Ibu</th>
    </tr>
  </thead>';
  echo '<tbody>';

  $no = 1;
  if(mysqli_num_rows($sql) > 0){
    while($data = mysqli_fetch_assoc($sql)){

      // TTL
      $tglTTL = '-';
      if(!empty($data['tgl_lahir'])){
        $tanggal = date('d', strtotime($data['tgl_lahir']));
        $bulan   = date('F', strtotime($data['tgl_lahir']));
        $tahun   = date('Y', strtotime($data['tgl_lahir']));
        $tglTTL  = $tanggal . " " . $bulanIndo[$bulan] . " " . $tahun;
      }
      $ttl = (!empty($data['tempat_lahir']) ? $data['tempat_lahir'] : '-') . ", " . $tglTTL;

      // Tanggal surat Indo
      $tglSuratIndo = '-';
      if(!empty($data['tanggal_surat'])){
        $tgl_s = date('d ', strtotime($data['tanggal_surat']));
        $bln_s = date('F', strtotime($data['tanggal_surat']));
        $thn_s = date(' Y', strtotime($data['tanggal_surat']));
        $tglSuratIndo = $tgl_s . $bulanIndo[$bln_s] . $thn_s;
      }

      echo '<tr>';
      echo '<td>'.$no++.'</td>';
      echo '<td>'.htmlspecialchars($data['no_surat']).'</td>';
      echo '<td>'.htmlspecialchars($tglSuratIndo).'</td>';
      echo '<td>'.htmlspecialchars($data['jenis_surat']).'</td>';
      echo '<td>'.htmlspecialchars($data['nik']).'</td>';
      echo '<td>'.htmlspecialchars($data['no_kk']).'</td>';
      echo '<td>'.htmlspecialchars($data['nama']).'</td>';
      echo '<td>'.htmlspecialchars($ttl).'</td>';
      echo '<td>'.htmlspecialchars($data['jenis_kelamin']).'</td>';
      echo '<td>'.htmlspecialchars($data['agama']).'</td>';
      echo '<td>'.htmlspecialchars($data['jalan']).'</td>';
      echo '<td>'.htmlspecialchars($data['dusun']).'</td>';
      echo '<td>'.htmlspecialchars($data['rt']).'</td>';
      echo '<td>'.htmlspecialchars($data['rw']).'</td>';
      echo '<td>'.htmlspecialchars($data['desa']).'</td>';
      echo '<td>'.htmlspecialchars($data['kecamatan']).'</td>';
      echo '<td>'.htmlspecialchars($data['kota']).'</td>';
      echo '<td>'.htmlspecialchars($data['pend_kk']).'</td>';
      echo '<td>'.htmlspecialchars($data['pend_terakhir']).'</td>';
      echo '<td>'.htmlspecialchars($data['pend_ditempuh']).'</td>';
      echo '<td>'.htmlspecialchars($data['pekerjaan']).'</td>';
      echo '<td>'.htmlspecialchars($data['status_perkawinan']).'</td>';
      echo '<td>'.htmlspecialchars($data['status_dlm_keluarga']).'</td>';
      echo '<td>'.htmlspecialchars($data['kewarganegaraan']).'</td>';
      echo '<td>'.htmlspecialchars($data['nama_ayah']).'</td>';
      echo '<td>'.htmlspecialchars($data['nama_ibu']).'</td>';
      echo '</tr>';
    }
  } else {
    echo '<tr><td colspan="26" align="center">Data tidak ditemukan.</td></tr>';
  }

  echo '</tbody></table>';
  exit; // PENTING: stop supaya tidak render layout admin
}

/* =========================
   NORMAL WEB MODE
========================= */
include ('../part/header.php');

$sql = mysqli_query($connect, $query);
if(!$sql){
  die("QUERY ERROR: " . mysqli_error($connect));
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <?php  
          if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){
            echo '<img src="../../assets/img/ava-admin-female.png" class="img-circle" alt="User Image">';
          }else if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Kepala Desa')){
            echo '<img src="../../assets/img/ava-kades.png" class="img-circle" alt="User Image">';
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
      <li><a href="../dashboard/"><i class="fas fa-tachometer-alt"></i> <span>&nbsp;&nbsp;Dashboard</span></a></li>
      <li><a href="../penduduk/"><i class="fa fa-users"></i> <span>Data Penduduk</span></a></li>

      <?php if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){ ?>
      <li class="treeview">
        <a href="#"><i class="fas fa-envelope-open-text"></i> <span>&nbsp;&nbsp;Surat</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li><a href="../surat/permintaan_surat/"><i class="fa fa-circle-notch"></i> Permintaan Surat</a></li>
          <li><a href="../surat/surat_selesai/"><i class="fa fa-circle-notch"></i> Surat Selesai</a></li>
        </ul>
      </li>
      <?php } ?>

      <li class="active"><a href="#"><i class="fas fa-chart-line"></i> <span>&nbsp;&nbsp;Laporan</span></a></li>
    </ul>
  </section>
</aside>

<div class="content-wrapper">
  <section class="content-header">
    <?php
      if(isset($_GET['filter']) && !empty($_GET['filter'])){
        $filter = $_GET['filter'];

        if($filter == '1'){
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar</h1>';
        } else if($filter == '2' && !empty($_GET['tanggal'])){
          $tgl_lhr = $_GET['tanggal'];
          $tgl = date('d ', strtotime($tgl_lhr));
          $bln = date('F', strtotime($tgl_lhr));
          $thn = date(' Y', strtotime($tgl_lhr));
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar (Tanggal '.$tgl . $bulanIndo[$bln] . $thn.')</h1>';
        } else if($filter == '3'){
          $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar (Bulan '.$nama_bulan[$_GET['bulan']].' '.$_GET['tahun'].')</h1>';
        } else if($filter == '4'){
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar (Tahun '.$_GET['tahun'].')</h1>';
        } else {
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar</h1>';
        }
      } else {
        echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar</h1>';
      }
    ?>
    <ol class="breadcrumb">
      <li><a href="../dashboard/"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Laporan</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">

        <div class="col-md-7"></div>

        <div class="col-md-5" align="right">
          <a class="btn btn-success btn-md" target="_blank"
             href="cetak-laporan.php?<?php echo http_build_query($_GET); ?>">
            <i class="fa fa-print"></i> Cetak
          </a>

          <a class="btn btn-info btn-md"
             href="?<?php echo http_build_query(array_merge($_GET, ['export'=>'1'])); ?>">
            <i class="fa fa-file-excel"></i> Download Excel
          </a>

          <a class="btn btn-primary btn-md" data-toggle="modal" data-target="#exampleModal">
            <i class="fas fa-filter"></i> Filter
          </a>

          <a href="../laporan/" class="btn btn-danger btn-md">
            <i class="fas fa-eraser"></i> Reset Filter
          </a>
        </div>

        <br>

        <!-- Modal Filter -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="get" action="">
                <div class="modal-body">
                  <div class="form-group">
                    <label>Filter Berdasarkan</label>
                    <select class="form-control" name="filter" id="filter">
                      <option value="1" <?php echo (isset($_GET['filter']) && $_GET['filter']=='1')?'selected':''; ?>>Semua Waktu</option>
                      <option value="2" <?php echo (isset($_GET['filter']) && $_GET['filter']=='2')?'selected':''; ?>>Per Tanggal</option>
                      <option value="3" <?php echo (isset($_GET['filter']) && $_GET['filter']=='3')?'selected':''; ?>>Per Bulan</option>
                      <option value="4" <?php echo (isset($_GET['filter']) && $_GET['filter']=='4')?'selected':''; ?>>Per Tahun</option>
                    </select>
                  </div>

                  <div class="form-group" id="form-tanggal">
                    <label>Tanggal</label>
                    <input class="form-control" type="date" name="tanggal"
                      value="<?php echo isset($_GET['tanggal']) ? htmlspecialchars($_GET['tanggal']) : ''; ?>">
                  </div>

                  <div class="form-group" id="form-bulan">
                    <label>Bulan</label>
                    <select class="form-control" name="bulan">
                      <option value="">Pilih</option>
                      <?php
                        $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                        for($i=1;$i<=12;$i++){
                          $selected = (isset($_GET['bulan']) && (int)$_GET['bulan']==$i) ? 'selected' : '';
                          echo '<option value="'.$i.'" '.$selected.'>'.$nama_bulan[$i].'</option>';
                        }
                      ?>
                    </select>
                  </div>

                  <div class="form-group" id="form-tahun">
                    <label>Tahun</label>
                    <select class="form-control" name="tahun">
                      <option value="">Pilih</option>
                      <?php
                        $qy = "SELECT YEAR(tanggal_surat) AS tahun FROM surat_keterangan GROUP BY YEAR(tanggal_surat) ORDER BY tahun DESC";
                        $sy = mysqli_query($connect, $qy);
                        while($dy = mysqli_fetch_array($sy)){
                          $selected = (isset($_GET['tahun']) && $_GET['tahun']==$dy['tahun']) ? 'selected' : '';
                          echo '<option value="'.$dy['tahun'].'" '.$selected.'>'.$dy['tahun'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <br><br>

        <div class="table-responsive">
          <table class="table table-striped table-bordered" width="100%" cellspacing="0" style="white-space:nowrap;">
            <thead>
              <tr>
                <th class="text-center"><strong>No</strong></th>
                <th><strong>No. Surat</strong></th>
                <th><strong>Tanggal Surat</strong></th>
                <th><strong>Jenis Surat</strong></th>
                <th><strong>NIK</strong></th>
                <th><strong>No KK</strong></th>
                <th><strong>Nama</strong></th>
                <th><strong>Tempat/Tgl Lahir</strong></th>
                <th><strong>Jenis Kelamin</strong></th>
                <th><strong>Agama</strong></th>
                <th><strong>Jalan</strong></th>
                <th><strong>Dusun</strong></th>
                <th><strong>RT</strong></th>
                <th><strong>RW</strong></th>
                <th><strong>Desa</strong></th>
                <th><strong>Kecamatan</strong></th>
                <th><strong>Kota</strong></th>
                <th><strong>Pendidikan di KK</strong></th>
                <th><strong>Pendidikan Terakhir</strong></th>
                <th><strong>Pendidikan Ditempuh</strong></th>
                <th><strong>Pekerjaan</strong></th>
                <th><strong>Status Perkawinan</strong></th>
                <th><strong>Status Dlm Keluarga</strong></th>
                <th><strong>Kewarganegaraan</strong></th>
                <th><strong>Nama Ayah</strong></th>
                <th><strong>Nama Ibu</strong></th>
              </tr>
            </thead>

            <tbody>
              <?php
                $no = 1;
                if(mysqli_num_rows($sql) > 0){
                  while($data = mysqli_fetch_assoc($sql)){

                    // TTL
                    $tglTTL = '-';
                    if(!empty($data['tgl_lahir'])){
                      $tanggal = date('d', strtotime($data['tgl_lahir']));
                      $bulan   = date('F', strtotime($data['tgl_lahir']));
                      $tahun   = date('Y', strtotime($data['tgl_lahir']));
                      $tglTTL  = $tanggal . " " . $bulanIndo[$bulan] . " " . $tahun;
                    }
                    $ttl = (!empty($data['tempat_lahir']) ? $data['tempat_lahir'] : '-') . ", " . $tglTTL;

                    // Tanggal surat Indo
                    $tglSuratIndo = '-';
                    if(!empty($data['tanggal_surat'])){
                      $tgl_s = date('d ', strtotime($data['tanggal_surat']));
                      $bln_s = date('F', strtotime($data['tanggal_surat']));
                      $thn_s = date(' Y', strtotime($data['tanggal_surat']));
                      $tglSuratIndo = $tgl_s . $bulanIndo[$bln_s] . $thn_s;
                    }
              ?>
              <tr>
                <td class="text-center"><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($data['no_surat']); ?></td>
                <td><?php echo htmlspecialchars($tglSuratIndo); ?></td>
                <td><?php echo htmlspecialchars($data['jenis_surat']); ?></td>
                <td><?php echo htmlspecialchars($data['nik']); ?></td>
                <td><?php echo htmlspecialchars($data['no_kk']); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['nama']); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($ttl); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['jenis_kelamin']); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['agama']); ?></td>

                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['jalan']); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['dusun']); ?></td>
                <td><?php echo htmlspecialchars($data['rt']); ?></td>
                <td><?php echo htmlspecialchars($data['rw']); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['desa']); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['kecamatan']); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['kota']); ?></td>

                <td><?php echo htmlspecialchars($data['pend_kk']); ?></td>
                <td><?php echo htmlspecialchars($data['pend_terakhir']); ?></td>
                <td><?php echo htmlspecialchars($data['pend_ditempuh']); ?></td>

                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['pekerjaan']); ?></td>
                <td><?php echo htmlspecialchars($data['status_perkawinan']); ?></td>
                <td><?php echo htmlspecialchars($data['status_dlm_keluarga']); ?></td>
                <td><?php echo htmlspecialchars($data['kewarganegaraan']); ?></td>

                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['nama_ayah']); ?></td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($data['nama_ibu']); ?></td>
              </tr>
              <?php
                  }
                } else {
                  echo "<tr><td colspan='26' align='center'>Data tidak ditemukan.</td></tr>";
                }
              ?>
            </tbody>
          </table>
        </div>

        <br>

        <script>
          $(document).ready(function(){
            function toggleFilterForm(){
              var val = $('#filter').val();
              $('#form-tanggal, #form-bulan, #form-tahun').hide();

              if(val == '1'){
                $('#form-tanggal, #form-bulan, #form-tahun').hide();
              }else if(val == '2'){
                $('#form-tanggal').show();
              }else if(val == '3'){
                $('#form-bulan, #form-tahun').show();
              }else{
                $('#form-tahun').show();
              }
            }

            toggleFilterForm();

            $('#filter').change(function(){
              toggleFilterForm();
              $('#form-tanggal input, #form-bulan select, #form-tahun select').val('');
            });
          });
        </script>

      </div>
    </div>
  </section>
</div>

<?php include ('../part/footer.php'); ?>
