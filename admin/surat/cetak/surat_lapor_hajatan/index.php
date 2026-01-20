<?php
include ('../../permintaan_surat/konfirmasi/part/akses.php');
include ('../../../../config/koneksi.php');

if(!isset($_GET['id'])){
  header("location:../../");
  exit;
}

$id = mysqli_real_escape_string($connect, $_GET['id']);

// =========================
// ambil data penduduk + surat lapor hajatan
// =========================
$qCek = mysqli_query($connect, "
  SELECT penduduk.*, surat_lapor_hajatan.*
  FROM penduduk
  LEFT JOIN surat_lapor_hajatan
    ON surat_lapor_hajatan.nik = penduduk.nik
  WHERE surat_lapor_hajatan.id_slh = '$id'
");

if(!$qCek || mysqli_num_rows($qCek) == 0){
  die("Data surat tidak ditemukan.");
}

$row = mysqli_fetch_assoc($qCek);

// =========================
// ambil profil desa (id 1)
// =========================
$qTampilDesa = mysqli_query($connect, "SELECT * FROM profil_desa WHERE id_profil_desa = '1' LIMIT 1");
$rows = mysqli_fetch_assoc($qTampilDesa);

// =========================
// ambil pejabat desa berdasar id_pejabat_desa di surat
// =========================
$id_pejabat_desa = $row['id_pejabat_desa'];

$qPejabat = mysqli_query($connect, "
  SELECT jabatan, nama_pejabat_desa
  FROM pejabat_desa
  WHERE id_pejabat_desa = '$id_pejabat_desa'
  LIMIT 1
");
$rowss = mysqli_fetch_assoc($qPejabat);

// kalau belum dipilih pejabat (NULL)
$jabatan = !empty($rowss['jabatan']) ? $rowss['jabatan'] : "Pejabat Desa";
$namaPejabat = !empty($rowss['nama_pejabat_desa']) ? $rowss['nama_pejabat_desa'] : "............................";

// =========================
// helper tanggal Indo (sama gaya akta kematian)
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

// =========================
// umur (seperti punya kamu)
// =========================
$umurTahun = "-";
if(!empty($row['tgl_lahir'])){
  $tgl_lahir = new DateTime($row['tgl_lahir']);
  $tgl_hari_ini = new DateTime();
  $umur = $tgl_hari_ini->diff($tgl_lahir);
  $umurTahun = $umur->y . " Tahun";
}

// tanggal hajatan (kolomnya datetime)
// kalau kamu simpan date saja, tetap aman
$tglHajatan = !empty($row['tanggal']) ? tglIndo($row['tanggal']) : "-";

// tanggal cetak (hari ini)
$tglCetak = tglIndo(date('Y-m-d'));
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
    <tr>
      <img src="../../../../assets/img/logo.png" alt="" class="logo">
    </tr>
    <div class="header">
      <h4 class="kop" style="text-transform: uppercase">PEMERINTAH <?php echo $rows['kota']; ?></h4>
      <h4 class="kop" style="text-transform: uppercase">KECAMATAN <?php echo $rows['kecamatan']; ?></h4>
      <h4 class="kop" style="text-transform: uppercase">KEPALA DESA <?php echo $rows['nama_desa']; ?></h4>
      <h5 class="kop2" style="text-transform: capitalize;">
        <?php echo $rows['alamat'] . " Telp. " . $rows['no_telpon'] . " Kode Pos " . $rows['kode_pos']; ?>
      </h5>
      <div style="text-align:center;">
        <hr>
      </div>
    </div>

    <br>

    <div align="center">
      <u><h4 class="kop">SURAT LAPOR HAJATAN</h4></u>
    </div>
    <div align="center">
      <h4 class="kop3">Nomor :&nbsp;&nbsp;&nbsp;<?php echo !empty($row['no_surat']) ? $row['no_surat'] : "-"; ?></h4>
    </div>
  </table>

  <br>
  <div class="clear"></div>

  <div id="isi3">
    <table width="100%">
      <tr>
        <td class="indentasi">
          Yang bertanda tangan di bawah ini,
          <a style="text-transform: capitalize;">
            <?php echo $jabatan . " " . $rows['nama_desa']; ?>,
            Kecamatan <?php echo $rows['kecamatan']; ?>,
            <?php echo $rows['kota']; ?>
          </a>, menerangkan dengan sebenarnya bahwa :
        </td>
      </tr>
    </table>

    <br>

    <!-- =========================
         DATA PENDUDUK / PEMOHON
    ========================= -->
    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td width="30%" class="indentasi">N&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;A</td>
        <td width="2%">:</td>
        <td width="68%" style="text-transform: uppercase; font-weight: bold;"><?php echo $row['nama']; ?></td>
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
        <td class="indentasi">Umur</td>
        <td>:</td>
        <td><?php echo $umurTahun; ?></td>
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

      <tr>
        <td class="indentasi">Surat Bukti Diri</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td class="indentasi">KTP</td>
        <td>:</td>
        <td><?php echo !empty($row['bukti_ktp']) ? $row['bukti_ktp'] : "-"; ?></td>
      </tr>
      <tr>
        <td class="indentasi">KK</td>
        <td>:</td>
        <td><?php echo !empty($row['bukti_kk']) ? $row['bukti_kk'] : "-"; ?></td>
      </tr>
    </table>

    <br>

    <!-- =========================
         INFO HAJATAN
    ========================= -->
    <table width="100%">
      <tr>
        <td class="indentasi">
          Orang tersebut akan punya hajat :
          <a style="text-transform: uppercase;"><?php echo $row['jenis_hajat']; ?></a>
          yang dilaksanakan pada :
        </td>
      </tr>
    </table>

    <br>

    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td width="25%" class="indentasi">Hari</td>
        <td width="2%">:</td>
        <td width="73%"><?php echo $row['hari']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Tanggal</td>
        <td>:</td>
        <td><?php echo $tglHajatan; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Jenis Hiburan</td>
        <td>:</td>
        <td><?php echo $row['jenis_hiburan']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Pemilik</td>
        <td>:</td>
        <td style="text-transform: uppercase;"><b><?php echo $row['pemilik']; ?></b></td>
      </tr>
      <tr>
        <td class="indentasi">Alamat</td>
        <td>:</td>
        <td><?php echo $row['alamat_pemilik']; ?></td>
      </tr>
    </table>

    <br>

    <table width="100%">
      <tr>
        <td class="indentasi">
          Demikian Surat Lapor Hajatan ini dibuat dengan sebenar-benarnya dan digunakan sebagaimana mestinya.
        </td>
      </tr>
    </table>
  </div>

  <br>

  <!-- =========================
       TTD + PERHATIAN
  ========================= -->
  <table width="100%">
    <tr>
      <td width="40%"><b>PERHATIAN :</b></td>
      <td width="0%"></td>
      <td width="10%"></td>
      <td align="center" style="text-transform: capitalize;">
        <?php echo $rows['nama_desa']; ?>, <?php echo $tglCetak; ?>
      </td>
    </tr>

    <tr>
      <td>
        1. Mohon tidak ditempati main judi dalam bentuk apapun.<br>
        2. Mohon tidak digunakan minum minuman keras jenis apapun.<br>
        3. Bila ketentuan ini diabaikan maka Surat Laporan ini di cabut dan diajukan kepada yang berwenang.
      </td>
      <td></td><td></td>
      <td align="center" style="text-transform: capitalize;">
        <?php echo $jabatan . " " . $rows['nama_desa']; ?>
      </td>
    </tr>

    <tr><td colspan="4" style="height:80px;"></td></tr>

    <tr>
      <td></td><td></td><td></td>
      <td align="center" style="text-transform: uppercase;">
        <b><u><?php echo $namaPejabat; ?></u></b>
      </td>
    </tr>
  </table>

</div>

<script>
  window.print();
</script>
</body>
</html>
