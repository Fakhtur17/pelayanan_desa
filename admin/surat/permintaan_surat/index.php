<?php
  include ('../../../config/koneksi.php');
  include ('../part/akses.php');
  include ('../part/header.php');
?>

<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <?php
          if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Administrator')){
            echo '<img src="../../../assets/img/ava-admin-female.png" class="img-circle" alt="User Image">';
          }else if(isset($_SESSION['lvl']) && ($_SESSION['lvl'] == 'Kepala Desa')){
            echo '<img src="../../../assets/img/ava-kades.png" class="img-circle" alt="User Image">';
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
        <a href="../../dashboard/">
          <i class="fas fa-tachometer-alt"></i> <span>&nbsp;&nbsp;Dashboard</span>
        </a>
      </li>
      <li>
        <a href="../../penduduk/">
          <i class="fa fa-users"></i> <span>Data Penduduk</span>
        </a>
      </li>

      <li class="active treeview">
        <a href="#">
          <i class="fas fa-envelope-open-text"></i> <span>&nbsp;&nbsp;Surat</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li class="active">
            <a href="#">
              <i class="fa fa-circle-notch"></i> Permintaan Surat
            </a>
          </li>
          <li>
            <a href="../../surat/surat_selesai/">
              <i class="fa fa-circle-notch"></i> Surat Selesai
            </a>
          </li>
        </ul>
      </li>

      <li>
        <a href="../../laporan/">
          <i class="fas fa-chart-line"></i> <span>&nbsp;&nbsp;Laporan</span>
        </a>
      </li>
    </ul>
  </section>
</aside>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Permintaan Surat</h1>
    <ol class="breadcrumb">
      <li><a href="../../dashboard/"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Permintaan Surat</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <br><br>

        <table class="table table-striped table-bordered table-responsive" id="data-table" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th><strong>Tanggal</strong></th>
              <th><strong>NIK</strong></th>
              <th><strong>Nama</strong></th>
              <th><strong>Jenis Surat</strong></th>
              <th><strong>Status</strong></th>
              <th><strong>Aksi</strong></th>
            </tr>
          </thead>

          <tbody>
            <?php
              // Ambil semua surat status PENDING, samakan kolom & alias ID jadi id_surat
              $sql = "
                SELECT
                  penduduk.nama,
                  surat_keterangan.id_sk AS id_surat,
                  surat_keterangan.no_surat,
                  surat_keterangan.nik,
                  surat_keterangan.jenis_surat,
                  surat_keterangan.status_surat,
                  surat_keterangan.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_keterangan ON surat_keterangan.nik = penduduk.nik
                WHERE surat_keterangan.status_surat='PENDING'

                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_keterangan_berkelakuan_baik.id_skbb AS id_surat,
                  surat_keterangan_berkelakuan_baik.no_surat,
                  surat_keterangan_berkelakuan_baik.nik,
                  surat_keterangan_berkelakuan_baik.jenis_surat,
                  surat_keterangan_berkelakuan_baik.status_surat,
                  surat_keterangan_berkelakuan_baik.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_keterangan_berkelakuan_baik ON surat_keterangan_berkelakuan_baik.nik = penduduk.nik
                WHERE surat_keterangan_berkelakuan_baik.status_surat='PENDING'

                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_keterangan_domisili.id_skd AS id_surat,
                  surat_keterangan_domisili.no_surat,
                  surat_keterangan_domisili.nik,
                  surat_keterangan_domisili.jenis_surat,
                  surat_keterangan_domisili.status_surat,
                  surat_keterangan_domisili.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_keterangan_domisili ON surat_keterangan_domisili.nik = penduduk.nik
                WHERE surat_keterangan_domisili.status_surat='PENDING'

                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_keterangan_kepemilikan_kendaraan_bermotor.id_skkkb AS id_surat,
                  surat_keterangan_kepemilikan_kendaraan_bermotor.no_surat,
                  surat_keterangan_kepemilikan_kendaraan_bermotor.nik,
                  surat_keterangan_kepemilikan_kendaraan_bermotor.jenis_surat,
                  surat_keterangan_kepemilikan_kendaraan_bermotor.status_surat,
                  surat_keterangan_kepemilikan_kendaraan_bermotor.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_keterangan_kepemilikan_kendaraan_bermotor ON surat_keterangan_kepemilikan_kendaraan_bermotor.nik = penduduk.nik
                WHERE surat_keterangan_kepemilikan_kendaraan_bermotor.status_surat='PENDING'

                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_keterangan_perhiasan.id_skp AS id_surat,
                  surat_keterangan_perhiasan.no_surat,
                  surat_keterangan_perhiasan.nik,
                  surat_keterangan_perhiasan.jenis_surat,
                  surat_keterangan_perhiasan.status_surat,
                  surat_keterangan_perhiasan.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_keterangan_perhiasan ON surat_keterangan_perhiasan.nik = penduduk.nik
                WHERE surat_keterangan_perhiasan.status_surat='PENDING'

                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_keterangan_usaha.id_sku AS id_surat,
                  surat_keterangan_usaha.no_surat,
                  surat_keterangan_usaha.nik,
                  surat_keterangan_usaha.jenis_surat,
                  surat_keterangan_usaha.status_surat,
                  surat_keterangan_usaha.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_keterangan_usaha ON surat_keterangan_usaha.nik = penduduk.nik
                WHERE surat_keterangan_usaha.status_surat='PENDING'

                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_lapor_hajatan.id_slh AS id_surat,
                  surat_lapor_hajatan.no_surat,
                  surat_lapor_hajatan.nik,
                  surat_lapor_hajatan.jenis_surat,
                  surat_lapor_hajatan.status_surat,
                  surat_lapor_hajatan.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_lapor_hajatan ON surat_lapor_hajatan.nik = penduduk.nik
                WHERE surat_lapor_hajatan.status_surat='PENDING'

                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_pengantar_skck.id_sps AS id_surat,
                  surat_pengantar_skck.no_surat,
                  surat_pengantar_skck.nik,
                  surat_pengantar_skck.jenis_surat,
                  surat_pengantar_skck.status_surat,
                  surat_pengantar_skck.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_pengantar_skck ON surat_pengantar_skck.nik = penduduk.nik
                WHERE surat_pengantar_skck.status_surat='PENDING'

                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_pengajuan_akta_kelahiran.id_spak AS id_surat,
                  surat_pengajuan_akta_kelahiran.no_surat,
                  surat_pengajuan_akta_kelahiran.nik,
                  surat_pengajuan_akta_kelahiran.jenis_surat,
                  surat_pengajuan_akta_kelahiran.status_surat,
                  surat_pengajuan_akta_kelahiran.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_pengajuan_akta_kelahiran ON surat_pengajuan_akta_kelahiran.nik = penduduk.nik
                WHERE surat_pengajuan_akta_kelahiran.status_surat='PENDING'

                -- ✅ TAMBAHAN: SURAT KETERANGAN AKTA KEMATIAN
                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_keterangan_akta_kematian.id_skkm AS id_surat,
                  surat_keterangan_akta_kematian.no_surat,
                  surat_keterangan_akta_kematian.nik,
                  surat_keterangan_akta_kematian.jenis_surat,
                  surat_keterangan_akta_kematian.status_surat,
                  surat_keterangan_akta_kematian.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_keterangan_akta_kematian ON surat_keterangan_akta_kematian.nik = penduduk.nik
                WHERE surat_keterangan_akta_kematian.status_surat='PENDING'

                -- ✅ TAMBAHAN: SURAT CETAK KEMBALI AKTA CAPIL
                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_cetak_kembali_akta_capil.id_sckac AS id_surat,
                  surat_cetak_kembali_akta_capil.no_surat,
                  surat_cetak_kembali_akta_capil.nik,
                  surat_cetak_kembali_akta_capil.jenis_surat,
                  surat_cetak_kembali_akta_capil.status_surat,
                  surat_cetak_kembali_akta_capil.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_cetak_kembali_akta_capil
                  ON surat_cetak_kembali_akta_capil.nik = penduduk.nik
                WHERE surat_cetak_kembali_akta_capil.status_surat='PENDING'

                -- ✅ TAMBAHAN: SURAT PEMBETULAN AKTA CAPIL
                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_pembetulan_akta_capil.id_spac AS id_surat,
                  surat_pembetulan_akta_capil.no_surat,
                  surat_pembetulan_akta_capil.nik,
                  surat_pembetulan_akta_capil.jenis_surat,
                  surat_pembetulan_akta_capil.status_surat,
                  surat_pembetulan_akta_capil.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_pembetulan_akta_capil
                  ON surat_pembetulan_akta_capil.nik = penduduk.nik
                WHERE surat_pembetulan_akta_capil.status_surat='PENDING'


                -- ✅ TAMBAHAN: SURAT PERUBAHAN NAMA CAPIL
                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_perubahan_nama_capil.id AS id_surat,
                  surat_perubahan_nama_capil.no_surat,
                  surat_perubahan_nama_capil.nik,
                  surat_perubahan_nama_capil.jenis_surat,
                  surat_perubahan_nama_capil.status_surat,
                  surat_perubahan_nama_capil.created_at AS tanggal_surat
                FROM penduduk
                LEFT JOIN surat_perubahan_nama_capil
                  ON surat_perubahan_nama_capil.nik = penduduk.nik
                WHERE surat_perubahan_nama_capil.status_surat='PENDING'

                -- ✅ TAMBAHAN: SURAT PENDAFTARAN & PENCETAKAN KK KARENA KELAHIRAN
                UNION ALL
                SELECT
                  penduduk.nama,
                  surat_pendaftaran_pencetakan_kk_kelahiran.id_spkkk AS id_surat,
                  surat_pendaftaran_pencetakan_kk_kelahiran.no_surat,
                  surat_pendaftaran_pencetakan_kk_kelahiran.nik,
                  surat_pendaftaran_pencetakan_kk_kelahiran.jenis_surat,
                  surat_pendaftaran_pencetakan_kk_kelahiran.status_surat,
                  surat_pendaftaran_pencetakan_kk_kelahiran.tanggal_surat
                FROM penduduk
                LEFT JOIN surat_pendaftaran_pencetakan_kk_kelahiran
                  ON surat_pendaftaran_pencetakan_kk_kelahiran.nik = penduduk.nik
                WHERE surat_pendaftaran_pencetakan_kk_kelahiran.status_surat='PENDING'

                ORDER BY tanggal_surat DESC
              ";

              $qTampil = mysqli_query($connect, $sql);

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

              if ($qTampil && mysqli_num_rows($qTampil) > 0){
                while ($row = mysqli_fetch_assoc($qTampil)){
                  $tglSurat = $row['tanggal_surat'];
                  $tgl = date('d ', strtotime($tglSurat));
                  $bln = date('F', strtotime($tglSurat));
                  $thn = date(' Y', strtotime($tglSurat));
            ?>
                <tr>
                  <td><?php echo $tgl . $blnIndo[$bln] . $thn; ?></td>
                  <td><?php echo $row['nik']; ?></td>
                  <td style="text-transform: capitalize;"><?php echo $row['nama']; ?></td>
                  <td><?php echo $row['jenis_surat']; ?></td>
                  <td>
                    <a class="btn btn-danger btn-sm" href="#"><i class="fa fa-spinner"></i><b> <?php echo $row['status_surat']; ?></b></a>
                  </td>
                  <td>
                    <?php
                      if($row['jenis_surat']=="Surat Keterangan"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_keterangan/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Keterangan Berkelakuan Baik"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_keterangan_berkelakuan_baik/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Keterangan Domisili"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_keterangan_domisili/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Keterangan Kepemilikan Kendaraan Bermotor"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_keterangan_kepemilikan_kendaraan_bermotor/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Keterangan Perhiasan"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_keterangan_perhiasan/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Keterangan Usaha"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_keterangan_usaha/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Lapor Hajatan"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_lapor_hajatan/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Pengantar SKCK"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_pengantar_skck/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Pengajuan Akta Kelahiran"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_pengajuan_akta_kelahiran/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>

                    <?php
                      } else if($row['jenis_surat']=="Surat Keterangan Akta Kematian"){
                    ?>
                      <!-- ✅ TAMBAHAN: tombol konfirmasi akta kematian -->
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_keterangan_akta_kematian/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>
                   <?php
                      } else if($row['jenis_surat']=="Surat Permohonan Cetak Kembali Akta Capil"){
                    ?>
                      <!-- ✅ TAMBAHAN: tombol konfirmasi cetak kembali akta capil -->
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_cetak_kembali_akta_capil/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>
                    <?php
                      } else if($row['jenis_surat']=="Surat Pembetulan Akta Capil"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_pembetulan_akta_capil/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>
                    <?php
                      } else if($row['jenis_surat']=="Surat Perubahan Nama Capil"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_perubahan_nama_dengan_capil/index.php?id=<?php echo $row['id_surat']; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>
                                        <?php
                      } else if($row['jenis_surat']=="Surat Pendaftaran dan Pencetakan KK Karena Kelahiran"){
                    ?>
                      <a class="btn btn-success btn-sm" href='konfirmasi/surat_pendaftaran_pencetakan_kk_kelahiran/index.php?id=<?php echo $row["id_surat"]; ?>'>
                        <i class="fa fa-check"></i><b> KONFIRMASI</b>
                      </a>


                    <?php
                      } else {
                        echo "-";
                      }
                    ?>
                  </td>
                </tr>
            <?php
                }
              } else {
            ?>
              <tr>
                <td colspan="6" align="center">Tidak ada permintaan surat.</td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

      </div>
    </div>
  </section>
</div>

<?php
  include ('../part/footer.php');
?>
