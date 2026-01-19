<?php 
  include ('../../permintaan_surat/konfirmasi/part/akses.php');
  include ('../../../../config/koneksi.php');

  $id = $_GET['id'];

  // ambil data penduduk + surat akta kematian
  $qCek = mysqli_query($connect,"
    SELECT penduduk.*, surat_keterangan_akta_kematian.*
    FROM penduduk 
    LEFT JOIN surat_keterangan_akta_kematian
      ON surat_keterangan_akta_kematian.nik = penduduk.nik
    WHERE surat_keterangan_akta_kematian.id_skkm='$id'
  ");

  while($row = mysqli_fetch_array($qCek)){

    $qTampilDesa = mysqli_query($connect, "SELECT * FROM profil_desa WHERE id_profil_desa = '1'");
    foreach($qTampilDesa as $rows){

      $id_pejabat_desa = $row['id_pejabat_desa'];

      $qCekPejabatDesa = mysqli_query($connect,"
        SELECT pejabat_desa.jabatan, pejabat_desa.nama_pejabat_desa
        FROM pejabat_desa 
        WHERE pejabat_desa.id_pejabat_desa = '$id_pejabat_desa'
      ");

      while($rowss = mysqli_fetch_array($qCekPejabatDesa)){
?>

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
      <div style="text-align: center;">
        <hr>
      </div>
    </div>
    <br>
    <div align="center"><u><h4 class="kop">SURAT KETERANGAN AKTA KEMATIAN</h4></u></div>
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
         DATA PEMOHON / PENDUDUK
    ========================= -->
    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td width="30%" class="indentasi">Nama Pemohon</td>
        <td width="2%">:</td>
        <td width="68%" style="text-transform: uppercase; font-weight: bold;"><?php echo $row['nama']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">NIK Pemohon</td>
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
            echo $row['jalan'].", RT".$row['rt']."/RW".$row['rw'].", Dusun ".$row['dusun'].", Desa ".$row['desa'].", Kecamatan ".$row['kecamatan'].", ".$row['kota'];
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
         DATA ALMARHUM
    ========================= -->
    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td class="indentasi" colspan="3"><b>Data Almarhum</b></td>
      </tr>
      <tr>
        <td width="30%" class="indentasi">Nama Almarhum</td>
        <td width="2%">:</td>
        <td width="68%" style="font-weight: bold; text-transform: uppercase;"><?php echo $row['nama_almarhum']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Tanggal Meninggal</td>
        <td>:</td>
        <td><?php echo date('d-m-Y', strtotime($row['tgl_meninggal'])); ?></td>
      </tr>
      <tr>
        <td class="indentasi">Tempat Meninggal</td>
        <td>:</td>
        <td><?php echo $row['tempat_meninggal']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Sebab Meninggal</td>
        <td>:</td>
        <td><?php echo !empty($row['sebab_meninggal']) ? $row['sebab_meninggal'] : "-"; ?></td>
      </tr>
    </table>

    <br><br>

    <!-- =========================
         KEPERLUAN / KETERANGAN
    ========================= -->
    <table width="100%">
      <tr>
        <td class="indentasi">
          Surat keterangan ini dibuat untuk:
        </td>
      </tr>
    </table>
    <br>

    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td class="indentasi" style="text-align:center;">
          <b><u><?php echo !empty($row['keperluan']) ? $row['keperluan'] : "-"; ?></u></b>
        </td>
      </tr>
      <tr>
        <td class="indentasi" style="text-align:center;">
          <?php echo !empty($row['keterangan']) ? $row['keterangan'] : ""; ?>
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
