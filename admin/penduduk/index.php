<?php 
  include ('../part/akses.php');
  include ('../part/header.php');
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
      <li>
        <a href="../dashboard/">
          <i class="fas fa-tachometer-alt"></i> <span>&nbsp;&nbsp;Dashboard</span>
        </a>
      </li>
      <li class="active">
        <a href="#"><i class="fa fa-users"></i> <span>Data Penduduk</span></a>
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
      <li>
        <a href="../laporan/">
          <i class="fas fa-chart-line"></i> <span>&nbsp;&nbsp;Laporan</span>
        </a>
      </li>
    </ul>
  </section>
</aside>
<div class="content-wrapper">
  <section class="content-header">
    <h1>Data Penduduk</h1>
    <ol class="breadcrumb">
      <li><a href="../dashboard/"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Data Penduduk</li>
    </ol>
  </section>
  <section class="content">      
    <div class="row">
      <div class="col-md-12">
        <div>
          <?php 
            if(isset($_GET['pesan'])){
              if($_GET['pesan']=="gagal-menambah"){
                echo "<div class='alert alert-danger'><center>Anda tidak bisa menambah data. NIK tersebut sudah digunakan.</center></div>";
              }
              if($_GET['pesan']=="gagal-menghapus"){
                echo "<div class='alert alert-danger'><center>Anda tidak bisa menghapus data tersebut.</center></div>";
              }
            }
          ?>
        </div>
        <?php 
          if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){
        ?>
        <a class="btn btn-success btn-md" href='tambah-penduduk.php'><i class="fa fa-user-plus"></i> Tambah Data Penduduk</a>
        <a target="_blank" class="btn btn-info btn-md" href='export-penduduk.php'><i class="fas fa-file-export"></i> Export .XLS</a>
        <?php 
          } else {

          }
        ?>
        <br><br>
        <div class="table-responsive">
<table class="table table-striped table-bordered" id="data-table" width="100%" cellspacing="0" style="white-space:nowrap;">
  <thead>
    <tr>
      <th class="text-center"><strong>No</strong></th>
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

      <?php if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){ ?>
        <th class="text-center"><strong>Aksi</strong></th>
      <?php } ?>
    </tr>
  </thead>

  <tbody>
    <?php
      include ('../../config/koneksi.php');

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

      $no = 1;
      $qTampil = mysqli_query($connect, "SELECT * FROM penduduk ORDER BY id_penduduk DESC");
      foreach($qTampil as $row){

        $tanggal = date('d', strtotime($row['tgl_lahir']));
        $bulan   = date('F', strtotime($row['tgl_lahir']));
        $tahun   = date('Y', strtotime($row['tgl_lahir']));
        $ttl     = $row['tempat_lahir'] . ", " . $tanggal . " " . $bulanIndo[$bulan] . " " . $tahun;
    ?>
    <tr>
      <td class="text-center"><?php echo $no++; ?></td>
      <td><?php echo $row['nik']; ?></td>
      <td><?php echo $row['no_kk']; ?></td>
      <td style="text-transform: capitalize;"><?php echo $row['nama']; ?></td>
      <td style="text-transform: capitalize;"><?php echo $ttl; ?></td>
      <td style="text-transform: capitalize;"><?php echo $row['jenis_kelamin']; ?></td>
      <td style="text-transform: capitalize;"><?php echo $row['agama']; ?></td>

      <td style="text-transform: capitalize;"><?php echo $row['jalan']; ?></td>
      <td style="text-transform: capitalize;"><?php echo $row['dusun']; ?></td>
      <td><?php echo $row['rt']; ?></td>
      <td><?php echo $row['rw']; ?></td>
      <td style="text-transform: capitalize;"><?php echo $row['desa']; ?></td>
      <td style="text-transform: capitalize;"><?php echo $row['kecamatan']; ?></td>
      <td style="text-transform: capitalize;"><?php echo $row['kota']; ?></td>

      <td><?php echo $row['pend_kk']; ?></td>
      <td><?php echo $row['pend_terakhir']; ?></td>
      <td><?php echo $row['pend_ditempuh']; ?></td>

      <td style="text-transform: capitalize;"><?php echo $row['pekerjaan']; ?></td>
      <td><?php echo $row['status_perkawinan']; ?></td>
      <td><?php echo $row['status_dlm_keluarga']; ?></td>
      <td><?php echo $row['kewarganegaraan']; ?></td>

      <td style="text-transform: capitalize;"><?php echo $row['nama_ayah']; ?></td>
      <td style="text-transform: capitalize;"><?php echo $row['nama_ibu']; ?></td>

      <?php if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){ ?>
        <td class="text-center">
          <a class="btn btn-success btn-sm" href='edit-penduduk.php?id=<?php echo $row['id_penduduk']; ?>'>
            <i class="fa fa-edit"></i>
          </a>
          <a class="btn btn-danger btn-sm" href='hapus-penduduk.php?id=<?php echo $row['id_penduduk']; ?>'
             onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
            <i class="fa fa-trash"></i>
          </a>
        </td>
      <?php } ?>
    </tr>
    <?php } ?>
  </tbody>
</table>
</div>

      </div>
    </div>
  </section>
</div>

<?php 
  include ('../part/footer.php');
?>