<?php
  include ('../../permintaan_surat/konfirmasi/part/akses.php');
  include ('../../../../config/koneksi.php');

  if(!isset($_GET['id'])){
    echo "ID tidak ditemukan.";
    exit;
  }

  $id = mysqli_real_escape_string($connect, $_GET['id']);

  // =========================
  // AMBIL DATA PEMOHON + SURAT USAHA
  // =========================
  $qCek = mysqli_query($connect,"
    SELECT penduduk.*, surat_keterangan_usaha.*
    FROM penduduk
    LEFT JOIN surat_keterangan_usaha
      ON surat_keterangan_usaha.nik = penduduk.nik
    WHERE surat_keterangan_usaha.id_sku = '$id'
    LIMIT 1
  ");

  if(!$qCek || mysqli_num_rows($qCek) == 0){
    echo "Data surat tidak ditemukan.";
    exit;
  }

  $row = mysqli_fetch_assoc($qCek);

  // =========================
  // AMBIL PROFIL DESA
  // =========================
  $qTampilDesa = mysqli_query($connect, "SELECT * FROM profil_desa WHERE id_profil_desa = '1' LIMIT 1");
  if(!$qTampilDesa || mysqli_num_rows($qTampilDesa) == 0){
    echo "Profil desa tidak ditemukan.";
    exit;
  }
  $rows = mysqli_fetch_assoc($qTampilDesa);

  // =========================
  // AMBIL PEJABAT DESA (TTD)
  // =========================
  $id_pejabat_desa = $row['id_pejabat_desa'];

  $qCekPejabatDesa = mysqli_query($connect,"
    SELECT jabatan, nama_pejabat_desa
    FROM pejabat_desa
    WHERE id_pejabat_desa = '$id_pejabat_desa'
    LIMIT 1
  ");

  $rowss = mysqli_fetch_assoc($qCekPejabatDesa);

  // fallback kalau belum dipilih pejabat
  $jabatan = isset($rowss['jabatan']) ? $rowss['jabatan'] : 'Perangkat Desa';
  $nama_pejabat = isset($rowss['nama_pejabat_desa']) ? $rowss['nama_pejabat_desa'] : '-';

  // =========================
  // FORMAT TANGGAL INDO (SAMA AKTA KEMATIAN)
  // =========================
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

  // tanggal surat (pakai dari DB kalau ada, kalau tidak pakai hari ini)
  $tanggalSurat = !empty($row['tanggal_surat']) ? $row['tanggal_surat'] : date('Y-m-d');
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

  <!-- =========================
       KOP SURAT
  ========================= -->
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
    <div align="center"><u><h4 class="kop">SURAT KETERANGAN USAHA</h4></u></div>
    <div align="center"><h4 class="kop3">Nomor :&nbsp;&nbsp;&nbsp;<?php echo !empty($row['no_surat']) ? $row['no_surat'] : "-"; ?></h4></div>
  </table>

  <br>
  <div class="clear"></div>

  <!-- =========================
       PEMBUKA
  ========================= -->
  <div id="isi3">
    <table width="100%">
      <tr>
        <td class="indentasi">
          Yang bertanda tangan di bawah ini,
          <a style="text-transform: capitalize;">
            <?php echo $jabatan . " " . $rows['nama_desa']; ?>,
            Kecamatan <?php echo $rows['kecamatan']; ?>, <?php echo $rows['kota']; ?>
          </a>,
          menerangkan dengan sebenarnya bahwa:
        </td>
      </tr>
    </table>

    <br><br>

    <!-- =========================
         DATA PEMOHON (SAMA AKTA KEMATIAN)
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
      <tr>
        <td class="indentasi">Tempat/Tgl. Lahir</td>
        <td>:</td>
        <td><?php echo $row['tempat_lahir'] . ", " . tglIndo($row['tgl_lahir']); ?></td>
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
            echo $row['jalan'] . ", RT" . $row['rt'] . "/RW" . $row['rw'] .
                 ", Dusun " . $row['dusun'] . ", Desa " . $row['desa'] .
                 ", Kecamatan " . $row['kecamatan'] . ", " . $row['kota'];
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
         DATA USAHA (MENYESUAIKAN DB surat_keterangan_usaha)
    ========================= -->
    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td class="indentasi" colspan="3"><b>Data Usaha</b></td>
      </tr>
      <tr>
        <td width="30%" class="indentasi">Nama Usaha</td>
        <td width="2%">:</td>
        <td width="68%" style="font-weight:bold; text-transform: uppercase;"><?php echo !empty($row['usaha']) ? $row['usaha'] : "-"; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Alamat Usaha</td>
        <td>:</td>
        <td><?php echo !empty($row['alamat_usaha']) ? $row['alamat_usaha'] : "-"; ?></td>
      </tr>
    </table>

    <br><br>

    <!-- =========================
         KEPERLUAN (MENYESUAIKAN DB)
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
    </table>

    <br>

    <table width="100%">
      <tr>
        <td class="indentasi">
          Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya.
        </td>
      </tr>
    </table>
  </div>

  <br>

  <!-- =========================
       TTD (SAMA AKTA KEMATIAN)
  ========================= -->
  <table width="100%" style="text-transform: capitalize;">
    <tr>
      <td width="10%"></td>
      <td width="30%"></td>
      <td width="10%"></td>
      <td align="center">
        <?php echo $rows['nama_desa']; ?>, <?php echo tglIndo($tanggalSurat); ?>
      </td>
    </tr>
    <tr>
      <td></td><td align="center">TTD. bersangkutan</td><td></td>
      <td align="center"><?php echo $jabatan . " " . $rows['nama_desa']; ?></td>
    </tr>

    <tr><td colspan="4" style="height:80px;"></td></tr>

    <tr>
      <td></td>
      <td align="center" style="text-transform: uppercase"><b><u><?php echo $row['nama']; ?></u></b></td>
      <td></td>
      <td align="center" style="text-transform: uppercase;"><b><u><?php echo $nama_pejabat; ?></u></b></td>
    </tr>
  </table>

</div>

<script>
  window.print();
</script>
</body>
</html>
