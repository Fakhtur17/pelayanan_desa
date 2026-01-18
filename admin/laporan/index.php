<?php
  include ('../../config/koneksi.php');
  include ('../part/akses.php');
  include ('../part/header.php');
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type='text/javascript'>
  $(window).load(function(){
    $("#ktp").change(function() {
      console.log($("#ktp option:selected").val());
      if ($("#ktp option:selected").val() == 'Tidak Ada') {
        $('#no_ktp').prop('hidden', 'true');
      } else {
        $('#no_ktp').prop('hidden', false);
      }
    });
  });
</script>

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
      <li>
        <a href="../dashboard/">
          <i class="fas fa-tachometer-alt"></i> <span>&nbsp;&nbsp;Dashboard</span>
        </a>
      </li>
      <li>
        <a href="../penduduk/">
          <i class="fa fa-users"></i> <span>Data Penduduk</span>
        </a>
      </li>
      <?php
        if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){
      ?>
      <li class="treeview">
        <a href="#">
          <i class="fas fa-envelope-open-text"></i> <span>&nbsp;&nbsp;Surat</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="../surat/permintaan_surat/"><i class="fa fa-circle-notch"></i> Permintaan Surat</a>
          </li>
          <li>
            <a href="../surat/surat_selesai/"><i class="fa fa-circle-notch"></i> Surat Selesai</a>
          </li>
        </ul>
      </li>
      <?php 
        }else{
          
        }
      ?>
      <li class="active">
        <a href="#"><i class="fas fa-chart-line"></i> <span>&nbsp;&nbsp;Laporan</span></a>
      </li>
    </ul>
  </section>
</aside>
<div class="content-wrapper">
  <section class="content-header">
    <?php
      if(isset($_GET['filter']) && ! empty($_GET['filter'])){
        $filter = $_GET['filter'];
        if($filter == '1'){
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar</h1>';
        }else if($filter == '2'){
          $tgl_lhr = date($_GET['tanggal']);
          $tgl = date('d ', strtotime($tgl_lhr));
          $bln = date('F', strtotime($tgl_lhr));
          $thn = date(' Y', strtotime($tgl_lhr));
          $blnIndo = array(
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
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar (Tanggal '.$tgl . $blnIndo[$bln] . $thn.')</b>';
        }else if($filter == '3'){
          $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar (Bulan '.$nama_bulan[$_GET['bulan']].' '.$_GET['tahun'].')</b>';
        }else if($filter == '4'){
          echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar (Tahun '.$_GET['tahun'].')</b>';
        }
      }else{
        echo '<h1>Laporan Surat Administrasi Desa - Surat Keluar</h1>';
      } 
    ?>
    <h1></h1>
    <ol class="breadcrumb">
      <li><a href="../dashboard/"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Laporan</li>
    </ol>
  </section>
  <section class="content">      
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-9">
          <?php
            $whereTanggal = "";
            $whereBulan   = "";
            $whereTahun   = "";

            if (isset($_GET['filter']) && $_GET['filter'] == '2' && !empty($_GET['tanggal'])) {
              $tgl = mysqli_real_escape_string($connect, $_GET['tanggal']);
              $whereTanggal = " AND DATE(s.tanggal_surat) = '$tgl' ";
            }

            if (isset($_GET['filter']) && $_GET['filter'] == '3' && !empty($_GET['bulan']) && !empty($_GET['tahun'])) {
              $bulan = (int) $_GET['bulan'];
              $tahun = (int) $_GET['tahun'];
              $whereBulan = " AND MONTH(s.tanggal_surat) = $bulan AND YEAR(s.tanggal_surat) = $tahun ";
            }

            if (isset($_GET['filter']) && $_GET['filter'] == '4' && !empty($_GET['tahun'])) {
              $tahun = (int) $_GET['tahun'];
              $whereTahun = " AND YEAR(s.tanggal_surat) = $tahun ";
            }
          ?>

        </div>
        <div class="col-md-3" align="right">
          <a name="filter" target="output" class="btn btn-primary btn-md" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-filter"></i> Filter</a>
          <a href="../laporan/" name="filter" class="btn btn-danger btn-md"><i class="fas fa-eraser"></i> Reset Filter</a>
        </div><br>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                      <option value="1">Semua Waktu</option>
                      <option value="2">Per Tanggal</option>
                      <option value="3">Per Bulan</option>
                      <option value="4">Per Tahun</option>
                    </select>
                  </div>
                  <div class="form-group" id="form-tanggal">
                    <label>Tanggal</label><br>
                    <input class="form-control" type="date" name="tanggal">
                  </div>
                  <div class="form-group" id="form-bulan">
                    <label>Bulan</label><br>
                    <select class="form-control" name="bulan">
                      <option value="">Pilih</option>
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                  <div class="form-group" id="form-tahun">
                    <label>Tahun</label><br>
                    <select class="form-control" name="tahun">
                      <option value="">Pilih</option>
                      <?php
                        $query = "SELECT YEAR(tanggal_surat) AS tahun FROM surat_keterangan GROUP BY YEAR(tanggal_surat)";
                        $sql = mysqli_query($connect, $query);
                        while($data = mysqli_fetch_array($sql)){
                          echo '<option value="'.$data['tahun'].'">'.$data['tahun'].'</option>';
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
        </div><br><br>
        <?php
          if(isset($_GET['filter']) && ! empty($_GET['filter'])){
            $filter = $_GET['filter'];
            if($filter == '1'){
              $query = "SELECT 
                p.nik,
                p.no_kk,
                p.nama,
                p.tempat_lahir,
                p.tgl_lahir,
                p.jenis_kelamin,
                p.agama,
                p.jalan,
                p.dusun,
                p.rt,
                p.rw,
                p.desa,
                p.kecamatan,
                p.kota,
                p.pend_kk,
                p.pend_terakhir,
                p.pend_ditempuh,
                p.pekerjaan,
                p.status_perkawinan,
                p.status_dlm_keluarga,
                p.kewarganegaraan,
                p.nama_ayah,
                p.nama_ibu,
                s.no_surat,
                s.tanggal_surat,
                s.jenis_surat
              FROM penduduk p
              JOIN surat_keterangan s ON s.nik = p.nik
              WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
              WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
              WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
              WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
              WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
              WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

              ORDER BY tanggal_surat DESC
              ";

            }else if($filter == '2'){
              $query = "SELECT 
  p.nik,
  p.no_kk,
  p.nama,
  p.tempat_lahir,
  p.tgl_lahir,
  p.jenis_kelamin,
  p.agama,
  p.jalan,
  p.dusun,
  p.rt,
  p.rw,
  p.desa,
  p.kecamatan,
  p.kota,
  p.pend_kk,
  p.pend_terakhir,
  p.pend_ditempuh,
  p.pekerjaan,
  p.status_perkawinan,
  p.status_dlm_keluarga,
  p.kewarganegaraan,
  p.nama_ayah,
  p.nama_ibu,
  s.no_surat,
  s.tanggal_surat,
  s.jenis_surat
FROM penduduk p
JOIN surat_keterangan s ON s.nik = p.nik
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

ORDER BY tanggal_surat DESC
";

            }else if($filter == '3'){
              $query = "SELECT 
  p.nik,
  p.no_kk,
  p.nama,
  p.tempat_lahir,
  p.tgl_lahir,
  p.jenis_kelamin,
  p.agama,
  p.jalan,
  p.dusun,
  p.rt,
  p.rw,
  p.desa,
  p.kecamatan,
  p.kota,
  p.pend_kk,
  p.pend_terakhir,
  p.pend_ditempuh,
  p.pekerjaan,
  p.status_perkawinan,
  p.status_dlm_keluarga,
  p.kewarganegaraan,
  p.nama_ayah,
  p.nama_ibu,
  s.no_surat,
  s.tanggal_surat,
  s.jenis_surat
FROM penduduk p
JOIN surat_keterangan s ON s.nik = p.nik
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

ORDER BY tanggal_surat DESC
";

            }else if($filter == '4'){
              $query = "SELECT 
  p.nik,
  p.no_kk,
  p.nama,
  p.tempat_lahir,
  p.tgl_lahir,
  p.jenis_kelamin,
  p.agama,
  p.jalan,
  p.dusun,
  p.rt,
  p.rw,
  p.desa,
  p.kecamatan,
  p.kota,
  p.pend_kk,
  p.pend_terakhir,
  p.pend_ditempuh,
  p.pekerjaan,
  p.status_perkawinan,
  p.status_dlm_keluarga,
  p.kewarganegaraan,
  p.nama_ayah,
  p.nama_ibu,
  s.no_surat,
  s.tanggal_surat,
  s.jenis_surat
FROM penduduk p
JOIN surat_keterangan s ON s.nik = p.nik
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

ORDER BY tanggal_surat DESC
";

            }
          }else{
            $query = "SELECT 
  p.nik,
  p.no_kk,
  p.nama,
  p.tempat_lahir,
  p.tgl_lahir,
  p.jenis_kelamin,
  p.agama,
  p.jalan,
  p.dusun,
  p.rt,
  p.rw,
  p.desa,
  p.kecamatan,
  p.kota,
  p.pend_kk,
  p.pend_terakhir,
  p.pend_ditempuh,
  p.pekerjaan,
  p.status_perkawinan,
  p.status_dlm_keluarga,
  p.kewarganegaraan,
  p.nama_ayah,
  p.nama_ibu,
  s.no_surat,
  s.tanggal_surat,
  s.jenis_surat
FROM penduduk p
JOIN surat_keterangan s ON s.nik = p.nik
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

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
WHERE s.status_surat = 'selesai' $whereTanggal $whereBulan $whereTahun

ORDER BY tanggal_surat DESC
";

          } 
        ?>
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

        $sql = mysqli_query($connect, $query);
        $row = mysqli_num_rows($sql);

        $no = 1;
        if($row > 0){
          while($data = mysqli_fetch_array($sql)){

            // TTL (tempat, tgl lahir)
            $tgl = '';
            if(!empty($data['tgl_lahir'])){
              $tanggal = date('d', strtotime($data['tgl_lahir']));
              $bulan   = date('F', strtotime($data['tgl_lahir']));
              $tahun   = date('Y', strtotime($data['tgl_lahir']));
              $tgl     = $tanggal . " " . $bulanIndo[$bulan] . " " . $tahun;
            }
            $ttl = (!empty($data['tempat_lahir']) ? $data['tempat_lahir'] : '-') . ", " . (!empty($tgl) ? $tgl : '-');

            // Tanggal surat (format Indo)
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

          <!-- kolom surat -->
          <td><?php echo $data['no_surat']; ?></td>
          <td><?php echo $tglSuratIndo; ?></td>
          <td><?php echo $data['jenis_surat']; ?></td>
          <td><?php echo $data['nik']; ?></td>
          <td><?php echo $data['no_kk']; ?></td>
          <td style="text-transform: capitalize;"><?php echo $data['nama']; ?></td>
          <td style="text-transform: capitalize;"><?php echo $ttl; ?></td>
          <td style="text-transform: capitalize;"><?php echo $data['jenis_kelamin']; ?></td>
          <td style="text-transform: capitalize;"><?php echo $data['agama']; ?></td>

          <td style="text-transform: capitalize;"><?php echo $data['jalan']; ?></td>
          <td style="text-transform: capitalize;"><?php echo $data['dusun']; ?></td>
          <td><?php echo $data['rt']; ?></td>
          <td><?php echo $data['rw']; ?></td>
          <td style="text-transform: capitalize;"><?php echo $data['desa']; ?></td>
          <td style="text-transform: capitalize;"><?php echo $data['kecamatan']; ?></td>
          <td style="text-transform: capitalize;"><?php echo $data['kota']; ?></td>

          <td><?php echo $data['pend_kk']; ?></td>
          <td><?php echo $data['pend_terakhir']; ?></td>
          <td><?php echo $data['pend_ditempuh']; ?></td>

          <td style="text-transform: capitalize;"><?php echo $data['pekerjaan']; ?></td>
          <td><?php echo $data['status_perkawinan']; ?></td>
          <td><?php echo $data['status_dlm_keluarga']; ?></td>
          <td><?php echo $data['kewarganegaraan']; ?></td>

          <td style="text-transform: capitalize;"><?php echo $data['nama_ayah']; ?></td>
          <td style="text-transform: capitalize;"><?php echo $data['nama_ibu']; ?></td>

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
            $('#form-tanggal, #form-bulan, #form-tahun').hide();
            $('#filter').change(function(){
              if($(this).val() == '1'){
                $('#form-tanggal, #form-bulan, #form-tahun').hide();
              }else if($(this).val() == '2'){
                $('#form-bulan, #form-tahun').hide();
                $('#form-tanggal').show();
              }else if($(this).val() == '3'){
                $('#form-tanggal').hide();
                $('#form-bulan, #form-tahun').show();
              }else{
                $('#form-tanggal, #form-bulan').hide();
                $('#form-tahun').show();
              }
              $('#form-tanggal input, #form-bulan select, #form-tahun select').val('');
            })
          })
        </script>
      </div>
    </div>
  </section>
</div>

<?php 
  include ('../part/footer.php');
?>