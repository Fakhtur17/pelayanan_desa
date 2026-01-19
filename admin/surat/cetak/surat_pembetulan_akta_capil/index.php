<?php 
  include ('../../permintaan_surat/konfirmasi/part/akses.php');
  include ('../../../../config/koneksi.php');

  if(!isset($_GET['id'])){
    die("ID surat tidak ada.");
  }

  $id = $_GET['id'];

  // ambil data penduduk + surat pembetulan akta capil
  $qCek = mysqli_query($connect,"
    SELECT penduduk.*, surat_pembetulan_akta_capil.*
    FROM penduduk 
    LEFT JOIN surat_pembetulan_akta_capil
      ON surat_pembetulan_akta_capil.nik = penduduk.nik
    WHERE surat_pembetulan_akta_capil.id_spac='$id'
  ");

  if(!$qCek || mysqli_num_rows($qCek) == 0){
    die("Data surat tidak ditemukan.");
  }

  while($row = mysqli_fetch_array($qCek)){

    $qTampilDesa = mysqli_query($connect, "SELECT * FROM profil_desa WHERE id_profil_desa='1'");
    foreach($qTampilDesa as $rows){

      $id_pejabat_desa = $row['id_pejabat_desa'];

      $qCekPejabatDesa = mysqli_query($connect,"
        SELECT pejabat_desa.jabatan, pejabat_desa.nama_pejabat_desa
        FROM pejabat_desa 
        WHERE pejabat_desa.id_pejabat_desa='$id_pejabat_desa'
      ");

      while($rowss = mysqli_fetch_array($qCekPejabatDesa)){
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="shortcut icon" href="../../../../assets/img/mini-logo.png">
  <title>CETAK SURAT</title>
  <link href="../../../../assets/formsuratCSS/formsurat.css" rel="stylesheet" type="text/css"/>
  <style type="text/css" media="print">
    @page { margin: 0; }
    body { 
      margin: 1cm;
      margin-left: 2cm;
      margin-right: 2cm;
      font-family: "Times New Roman", Times, serif;
    }
  </style>
</head>

<body>
<div>
  <table width="100%">
    <tr><img src="../../../../assets/img/logo.png" alt="" class="logo"></tr>
    <div class="header">
      <h4 class="kop" style="text-transform: uppercase">PEMERINTAH <?php echo $rows['kota']; ?></h4>
      <h4 class="kop" style="text-transform: uppercase">KECAMATAN <?php echo $rows['kecamatan']; ?></h4>
      <h4 class="kop" style="text-transform: uppercase">KEPALA DESA <?php echo $rows['nama_desa']; ?></h4>
      <h5 class="kop2" style="text-transform: capitalize;">
        <?php echo $rows['alamat'] . " Telp. " . $rows['no_telpon'] . " Kode Pos " . $rows['kode_pos']; ?>
      </h5>
      <div style="text-align: center;"><hr></div>
    </div>

    <br>
    <div align="center"><u><h4 class="kop">SURAT PEMBETULAN AKTA CAPIL</h4></u></div>
    <div align="center"><h4 class="kop3">Nomor :&nbsp;&nbsp;&nbsp;<?php echo $row['no_surat']; ?></h4></div>
  </table>

  <br>
  <div class="clear"></div>

  <div id="isi3">
    <table width="100%">
      <tr>
        <td class="indentasi">
          Yang bertanda tangan di bawah ini,
          <a style="text-transform: capitalize;">
            <?php echo $rowss['jabatan'] . " " . $rows['nama_desa']; ?>, Kecamatan <?php echo $rows['kecamatan']; ?>, <?php echo $rows['kota']; ?>
          </a>, menerangkan bahwa:
        </td>
      </tr>
    </table>

    <br><br>

    <!-- =========================
         DATA PEMOHON / TERMOHON
    ========================= -->
    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td width="30%" class="indentasi">Nama Termohon</td>
        <td width="2%">:</td>
        <td width="68%" style="text-transform: uppercase; font-weight: bold;"><?php echo $row['nama']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">NIK</td>
        <td>:</td>
        <td><?php echo $row['nik']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Jenis Kelamin</td>
        <td>:</td>
        <td><?php echo $row['jenis_kelamin']; ?></td>
      </tr>

      <?php
        $tgl_lhr = $row['tgl_lahir'];
        $tgl = date('d ', strtotime($tgl_lhr));
        $bln = date('F', strtotime($tgl_lhr));
        $thn = date(' Y', strtotime($tgl_lhr));
        $blnIndo = array(
          'January' => 'Januari','February' => 'Februari','March' => 'Maret','April' => 'April',
          'May' => 'Mei','June' => 'Juni','July' => 'Juli','August' => 'Agustus',
          'September' => 'September','October' => 'Oktober','November' => 'November','December' => 'Desember'
        );
      ?>
      <tr>
        <td class="indentasi">Tempat/Tgl. Lahir</td>
        <td>:</td>
        <td><?php echo $row['tempat_lahir'] . ", " . $tgl . $blnIndo[$bln] . $thn; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Agama</td>
        <td>:</td>
        <td><?php echo $row['agama']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Pekerjaan</td>
        <td>:</td>
        <td><?php echo $row['pekerjaan']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Alamat</td>
        <td>:</td>
        <td>
          <?php
            if(isset($row['alamat_pemohon']) && !empty($row['alamat_pemohon'])){
              echo $row['alamat_pemohon'];
            } else {
              echo $row['jalan'].", RT".$row['rt']."/RW".$row['rw'].", Dusun ".$row['dusun'].", Desa ".$row['desa'].", Kecamatan ".$row['kecamatan'].", ".$row['kota'];
            }
          ?>
        </td>
      </tr>
      <tr>
        <td class="indentasi">Kewarganegaraan</td>
        <td>:</td>
        <td style="text-transform: uppercase;"><?php echo $row['kewarganegaraan']; ?></td>
      </tr>
    </table>

    <br><br>

    <!-- =========================
         ISI PEMBETULAN
    ========================= -->
    <table width="100%">
      <tr>
        <td class="indentasi">
          Dengan ini yang bersangkutan mengajukan permohonan <b>pembetulan data Akta Catatan Sipil (Capil)</b> dengan rincian sebagai berikut:
        </td>
      </tr>
    </table>

    <br>

    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td width="30%" class="indentasi">Jenis Akta</td>
        <td width="2%">:</td>
        <td width="68%"><b><?php echo $row['jenis_akta']; ?></b></td>
      </tr>

      <tr>
        <td class="indentasi">Nomor Akta</td>
        <td>:</td>
        <td><?php echo !empty($row['no_akta']) ? $row['no_akta'] : "-"; ?></td>
      </tr>

      <tr>
        <td class="indentasi">Bagian yang Dibetulkan</td>
        <td>:</td>
        <td><?php echo $row['bagian_dibetulkan']; ?></td>
      </tr>

      <tr>
        <td class="indentasi">Data Sebelum</td>
        <td>:</td>
        <td><b><u><?php echo $row['data_sebelum']; ?></u></b></td>
      </tr>

      <tr>
        <td class="indentasi">Data Sesudah</td>
        <td>:</td>
        <td><b><u><?php echo $row['data_sesudah']; ?></u></b></td>
      </tr>

      <?php if(isset($row['keterangan']) && !empty($row['keterangan'])){ ?>
      <tr>
        <td class="indentasi">Keterangan</td>
        <td>:</td>
        <td><?php echo $row['keterangan']; ?></td>
      </tr>
      <?php } ?>
    </table>

    <br><br>

    <!-- =========================
         PERSYARATAN
    ========================= -->
    <table width="100%">
      <tr>
        <td class="indentasi">
          Adapun persyaratan yang dilampirkan:
          <br>1. Foto asli kutipan akta capil
          <br>2. Foto KK termohon
          <br><br>
          <b>Catatan Autentik:</b>
          <?php
            // kalau tidak diisi dari DB, tampilkan default
            if(isset($row['catatan_autentik']) && !empty($row['catatan_autentik'])){
              echo $row['catatan_autentik'];
            } else {
              echo "Dokumen autentik membutuhkan tanda tangan langsung (tanda tangan basah), sehingga pengambilan surat dilakukan di Balai Desa.";
            }
          ?>
        </td>
      </tr>
    </table>

    <br>

    <table width="100%">
      <tr>
        <td class="indentasi">
          Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </td>
      </tr>
    </table>
  </div>

  <br>

  <table width="100%" style="text-transform: capitalize;">
    <tr>
      <td width="10%"></td>
      <td width="30%"></td>
      <td width="10%"></td>
      <td align="center">
        <?php echo $rows['nama_desa']; ?>,
        <?php
          $tanggal = date('d F Y');
          $bulan = date('F', strtotime($tanggal));
          $bulanIndo = array(
            'January' => 'Januari','February' => 'Februari','March' => 'Maret','April' => 'April',
            'May' => 'Mei','June' => 'Juni','July' => 'Juli','August' => 'Agustus',
            'September' => 'September','October' => 'Oktober','November' => 'November','December' => 'Desember'
          );
          echo date('d ') . $bulanIndo[$bulan] . date(' Y');
        ?>
      </td>
    </tr>
    <tr>
      <td></td><td></td><td></td>
      <td align="center"><?php echo $rowss['jabatan'] . " " . $rows['nama_desa']; ?></td>
    </tr>

    <tr><td colspan="4" style="height:80px;"></td></tr>

    <tr>
      <td></td><td></td><td></td>
      <td align="center" style="text-transform: uppercase;"><u><b><?php echo $rowss['nama_pejabat_desa']; ?></b></u></td>
    </tr>
  </table>
</div>

<script>
  window.print();
</script>
</body>
</html>

<?php
      }
    }
  }
?>
