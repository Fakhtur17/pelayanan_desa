<?php
  include ('../part/akses.php');
  include ('../part/header.php');
  include ('../../config/koneksi.php');

  // ===== helper hari/bulan indo =====
  $hariIndo = array(
    'Mon' => 'Senin',
    'Tue' => 'Selasa',
    'Wed' => 'Rabu',
    'Thu' => 'Kamis',
    'Fri' => 'Jumat',
    'Sat' => 'Sabtu',
    'Sun' => 'Minggu',
  );

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

  /**
   * Helper: hitung total surat dari banyak tabel dengan status tertentu.
   * $tables: array berisi list tabel surat.
   * $status: 'PENDING' / 'SELESAI' (bebas, nanti dibandingkan UPPER).
   */
  function countSuratByStatus($connect, $tables, $status){
    $status = strtoupper($status);
    $parts = [];

    foreach($tables as $t){
      // $t bisa string "nama_tabel" atau array ['table'=>'xxx', 'dateField'=>'created_at']
      if (is_array($t)) {
        $table = $t['table'];
      } else {
        $table = $t;
      }
      $parts[] = "SELECT 1 AS x FROM {$table} WHERE UPPER(status_surat)='{$status}'";
    }

    $sql = implode(" UNION ALL ", $parts);
    $q = mysqli_query($connect, $sql);
    return $q ? mysqli_num_rows($q) : 0;
  }

  /**
   * Helper: hitung total surat SELESAI bulan ini.
   * Default pakai tanggal_surat, tapi ada tabel tertentu pakai created_at.
   */
  function countSuratSelesaiBulanIni($connect, $tables){
    $bulanIni = date('m');
    $tahunIni = date('Y');

    $parts = [];
    foreach($tables as $t){
      if (is_array($t)) {
        $table = $t['table'];
        $dateField = $t['dateField']; // misal created_at
      } else {
        $table = $t;
        $dateField = 'tanggal_surat';
      }

      $parts[] = "SELECT 1 AS x FROM {$table}
                 WHERE UPPER(status_surat)='SELESAI'
                 AND MONTH({$dateField})='{$bulanIni}'
                 AND YEAR({$dateField})='{$tahunIni}'";
    }

    $sql = implode(" UNION ALL ", $parts);
    $q = mysqli_query($connect, $sql);
    return $q ? mysqli_num_rows($q) : 0;
  }

  // ===== DAFTAR TABEL SURAT (sesuaikan kalau ada yang beda nama) =====
  // Tabel standar (umumnya pakai tanggal_surat)
  $tablesTanggalSurat = [
    'surat_keterangan',
    'surat_keterangan_berkelakuan_baik',
    'surat_keterangan_domisili',
    'surat_keterangan_usaha',
    'surat_lapor_hajatan',
    'surat_pengantar_skck',
    'surat_keterangan_kepemilikan_kendaraan_bermotor',
    'surat_keterangan_perhiasan',

    // ===== SURAT TAMBAHAN (yang kamu minta dilengkapi) =====
    'surat_pengajuan_akta_kelahiran',
    'surat_pendaftaran_pencetakan_kk_kelahiran',
    'surat_keterangan_akta_kematian',
    'surat_cetak_kembali_akta_capil',
    'surat_pembetulan_akta_capil',
  ];

  // Kalau ada tabel yang tanggalnya BUKAN tanggal_surat (contoh: created_at)
  $tablesCreatedAt = [
    ['table' => 'surat_perubahan_nama_capil', 'dateField' => 'created_at'],
  ];

  // Gabungan semua tabel surat
  $allSuratTables = array_merge($tablesTanggalSurat, $tablesCreatedAt);

  // ===== HITUNG ANGKA DASHBOARD =====
  $jumlahPenduduk = 0;
  $qPenduduk = mysqli_query($connect, "SELECT * FROM penduduk");
  if ($qPenduduk) $jumlahPenduduk = mysqli_num_rows($qPenduduk);

  $jumlahPending = countSuratByStatus($connect, $allSuratTables, 'PENDING');
  $jumlahSelesai = countSuratByStatus($connect, $allSuratTables, 'SELESAI');

  // Opsional: surat keluar selesai bulan ini
  $jumlahSelesaiBulanIni = countSuratSelesaiBulanIni($connect, $allSuratTables);

  // tanggal sekarang
  $tanggal = date('D d F Y');
  $hari = date('D', strtotime($tanggal));
  $bulan = date('F', strtotime($tanggal));
?>

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
      <li class="active">
        <a href="#">
          <i class="fas fa-tachometer-alt"></i> <span>&nbsp;&nbsp;Dashboard</span>
        </a>
      </li>
      <li>
        <a href="../penduduk/">
          <i class="fa fa-users"></i> <span>Data Penduduk</span>
        </a>
      </li>

      <?php if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){ ?>
      <li class="treeview">
        <a href="#">
          <i class="fas fa-envelope-open-text"></i> <span>&nbsp;&nbsp;Surat</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="../surat/permintaan_surat/"><i class="fa fa-circle-notch"></i> Permintaan Surat</a></li>
          <li><a href="../surat/surat_selesai/"><i class="fa fa-circle-notch"></i> Surat Selesai</a></li>
        </ul>
      </li>
      <?php } ?>

      <li>
        <a href="../laporan/"><i class="fas fa-chart-line"></i> <span>&nbsp;&nbsp;Laporan</span></a>
      </li>
    </ul>
  </section>
</aside>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Dashboard</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <?php if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){ ?>

      <!-- Data Penduduk -->
      <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?php echo $jumlahPenduduk; ?></h3>
            <p>Data Penduduk</p>
          </div>
          <div class="icon">
            <i class="fas fa-users" style="font-size:70px"></i>
          </div>
          <a href="../penduduk/" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Permintaan Surat (Pending) -->
      <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo $jumlahPending; ?></h3>
            <p>Permintaan Surat (Pending)</p>
          </div>
          <div class="icon">
            <i class="fas fa-envelope-open-text" style="font-size:70px"></i>
          </div>
          <a href="../surat/permintaan_surat/" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Surat Keluar (Selesai) -->
      <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo $jumlahSelesai; ?></h3>
            <p>Surat Keluar (Selesai)</p>
          </div>
          <div class="icon">
            <i class="fas fa-envelope" style="font-size:70px"></i>
          </div>
          <a href="../laporan/" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Opsional: Surat Keluar Bulan Ini -->
      <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-purple">
          <div class="inner">
            <h3><?php echo $jumlahSelesaiBulanIni; ?></h3>
            <p>Surat Keluar Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-calendar-check" style="font-size:70px"></i>
          </div>
          <a href="../laporan/?filter=3&bulan=<?php echo date('n'); ?>&tahun=<?php echo date('Y'); ?>"
             class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <?php } else if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Kepala Desa')){ ?>

      <div class="col-lg-1"></div>

      <!-- Data Penduduk -->
      <div class="col-lg-5 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?php echo $jumlahPenduduk; ?></h3>
            <p>Data Penduduk</p>
          </div>
          <div class="icon">
            <i class="fas fa-users" style="font-size:70px"></i>
          </div>
          <a href="../penduduk/" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Surat Keluar (Selesai) -->
      <div class="col-lg-5 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo $jumlahSelesai; ?></h3>
            <p>Laporan Surat Administrasi Desa - Surat Keluar</p>
          </div>
          <div class="icon">
            <i class="fas fa-envelope" style="font-size:70px"></i>
          </div>
          <a href="../laporan/" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-1"></div>

      <?php } ?>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Welcome Home!</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-4" style="text-align: center;">
                  <img style="max-width:200px; width: 130px; height:auto;" src="../../assets/img/logo_banjarnegara.png"><br>
                  <?php
                    $qTampilDesa = mysqli_query($connect, "SELECT * FROM profil_desa WHERE id_profil_desa = '1'");
                    if($qTampilDesa){
                      foreach($qTampilDesa as $row){
                        echo '<p style="font-size: 20pt; font-weight: 500; text-transform: uppercase;">
                                <strong>DESA '.$row['nama_desa'].'</strong>
                              </p><hr>';
                      }
                    }
                  ?>
                </div>

                <div class="col-md-8">
                  <div class="pull-right">
                    <?php
                      echo $hariIndo[$hari] . ', ' . date('d ') . $bulanIndo[$bulan] . date(' Y');
                    ?>
                  </div>
                  <br>

                  <div style="font-size: 35pt; font-weight: 500;">
                    <p>Halo, <strong><?php echo $_SESSION['lvl']; ?></strong></p>
                  </div>
                  <div style="font-size: 15pt; font-weight: 500;">
                    <p>Selamat datang di <a href="#" style="text-decoration:none"><strong>Web Aplikasi Pelayanan Surat Administrasi Desa Online.</strong></a></p>
                  </div>
                  <br><br><br>
                  <div style="font-size: 10pt; font-weight: 500;">© e-<b>SuratDesa</b> 2026. Hak Cipta Dilindungi.</div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>

<?php include ('../part/footer.php'); ?>
