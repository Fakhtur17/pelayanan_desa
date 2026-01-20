<?php
  include ('../../permintaan_surat/konfirmasi/part/akses.php');
  include ('../../../../config/koneksi.php');

  if(!isset($_GET['id']) || trim($_GET['id']) === ''){
    die("ID surat tidak valid.");
  }

  $id = mysqli_real_escape_string($connect, $_GET['id']);

  // 1) ambil data penduduk + surat domisili (1 baris)
  $qCek = mysqli_query($connect, "
    SELECT p.*, skd.*
    FROM surat_keterangan_domisili skd
    LEFT JOIN penduduk p ON p.nik = skd.nik
    WHERE skd.id_skd = '$id'
    LIMIT 1
  ");

  if(!$qCek || mysqli_num_rows($qCek) == 0){
    die("Data surat tidak ditemukan.");
  }

  $row = mysqli_fetch_assoc($qCek);

  // 2) profil desa
  $qTampilDesa = mysqli_query($connect, "SELECT * FROM profil_desa WHERE id_profil_desa='1' LIMIT 1");
  if(!$qTampilDesa || mysqli_num_rows($qTampilDesa)==0){
    die("Profil desa tidak ditemukan.");
  }
  $desa = mysqli_fetch_assoc($qTampilDesa);

  // 3) pejabat desa (ttd)
  $pejabat = ['jabatan'=>'-', 'nama_pejabat_desa'=>'-'];
  if(!empty($row['id_pejabat_desa'])){
    $id_pejabat_desa = mysqli_real_escape_string($connect, $row['id_pejabat_desa']);
    $qPejabat = mysqli_query($connect, "
      SELECT jabatan, nama_pejabat_desa
      FROM pejabat_desa
      WHERE id_pejabat_desa = '$id_pejabat_desa'
      LIMIT 1
    ");
    if($qPejabat && mysqli_num_rows($qPejabat)>0){
      $pejabat = mysqli_fetch_assoc($qPejabat);
    }
  }

  // helper tanggal indo
  function tglIndo($tanggal){
    if(empty($tanggal)) return "-";
    $blnIndo = [
      'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni',
      'July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
    ];
    $tgl = date('d ', strtotime($tanggal));
    $bln = date('F', strtotime($tanggal));
    $thn = date(' Y', strtotime($tanggal));
    return $tgl . ($blnIndo[$bln] ?? $bln) . $thn;
  }

  // Alamat penduduk (alamat KTP)
  $alamatKTP = $row['jalan'] . ", RT" . $row['rt'] . "/RW" . $row['rw'] .
               ", Dusun " . $row['dusun'] . ", Desa " . $row['desa'] .
               ", Kecamatan " . $row['kecamatan'] . ", " . $row['kota'];

  // Alamat domisili (dari kolom baru)
  $alamatDomisili = !empty($row['alamat_domisili']) ? $row['alamat_domisili'] : $alamatKTP;

  $rtDom = !empty($row['rt_domisili']) ? $row['rt_domisili'] : $row['rt'];
  $rwDom = !empty($row['rw_domisili']) ? $row['rw_domisili'] : $row['rw'];

  $desaDom = !empty($row['desa_domisili']) ? $row['desa_domisili'] : $row['desa'];
  $kecDom  = !empty($row['kecamatan_domisili']) ? $row['kecamatan_domisili'] : $row['kecamatan'];
  $kabDom  = !empty($row['kabupaten_domisili']) ? $row['kabupaten_domisili'] : $row['kota'];
  $provDom = !empty($row['provinsi_domisili']) ? $row['provinsi_domisili'] : '';

  $lamaTinggal = !empty($row['lama_tinggal']) ? $row['lama_tinggal'] : '-';
  $keperluan   = !empty($row['keperluan']) ? $row['keperluan'] : '-';
  $keterangan  = !empty($row['keterangan']) ? $row['keterangan'] : '-';
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
      <h4 class="kop" style="text-transform: uppercase;">PEMERINTAH <?php echo $desa['kota']; ?></h4>
      <h4 class="kop" style="text-transform: uppercase;">KECAMATAN <?php echo $desa['kecamatan']; ?></h4>
      <h4 class="kop" style="text-transform: uppercase;">DESA <?php echo $desa['nama_desa']; ?></h4>
      <h5 class="kop2" style="text-transform: capitalize;">
        <?php echo $desa['alamat'] . " Telp. " . $desa['no_telpon'] . " Kode Pos " . $desa['kode_pos']; ?>
      </h5>
      <div style="text-align:center;">
        <hr>
      </div>
    </div>

    <br>
    <div align="center"><u><h4 class="kop">SURAT KETERANGAN DOMISILI</h4></u></div>
    <div align="center"><h4 class="kop3">Nomor :&nbsp;&nbsp;&nbsp;<?php echo !empty($row['no_surat']) ? $row['no_surat'] : '-'; ?></h4></div>
  </table>

  <br>
  <div class="clear"></div>

  <div id="isi3">
    <table width="100%">
      <tr>
        <td class="indentasi">
          Yang bertanda tangan di bawah ini,
          <a style="text-transform: capitalize;">
            <?php echo $pejabat['jabatan'] . " " . $desa['nama_desa']; ?>,
            Kecamatan <?php echo $desa['kecamatan']; ?>, <?php echo $desa['kota']; ?>
          </a>,
          menerangkan dengan sebenarnya bahwa :
        </td>
      </tr>
    </table>

    <br><br>

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
        <td class="indentasi">NIK</td>
        <td>:</td>
        <td><?php echo $row['nik']; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Alamat KTP</td>
        <td>:</td>
        <td><?php echo $alamatKTP; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Kewarganegaraan</td>
        <td>:</td>
        <td style="text-transform: uppercase;"><?php echo $row['kewarganegaraan']; ?></td>
      </tr>
    </table>

    <br><br>

    <!-- ✅ Bagian domisili sesuai kolom baru -->
    <table width="100%">
      <tr>
        <td class="indentasi">
          Bahwa benar yang bersangkutan saat ini berdomisili di:
          <a style="text-transform: capitalize;">
            <b><u>
              <?php
                echo $alamatDomisili .
                     ", RT" . $rtDom . "/RW" . $rwDom .
                     ", Desa/Kel. " . $desaDom .
                     ", Kecamatan " . $kecDom .
                     ", " . $kabDom .
                     (!empty($provDom) ? ", Provinsi " . $provDom : "");
              ?>
            </u></b>
          </a>.
        </td>
      </tr>
    </table>

    <br>

    <table width="100%" style="text-transform: capitalize;">
      <tr>
        <td class="indentasi" width="30%">Lama Tinggal</td>
        <td width="2%">:</td>
        <td width="68%"><?php echo $lamaTinggal; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Keperluan</td>
        <td>:</td>
        <td><?php echo $keperluan; ?></td>
      </tr>
      <tr>
        <td class="indentasi">Keterangan</td>
        <td>:</td>
        <td><?php echo $keterangan; ?></td>
      </tr>
    </table>

    <br>

    <table width="100%">
      <tr>
        <td class="indentasi">
          Demikian surat keterangan ini dibuat dengan sebenar-benarnya dan digunakan sebagaimana mestinya.
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
        <?php echo $desa['nama_desa']; ?>, <?php echo tglIndo(date('Y-m-d')); ?>
      </td>
    </tr>
    <tr>
      <td></td><td></td><td></td>
      <td align="center"><?php echo $pejabat['jabatan'] . " " . $desa['nama_desa']; ?></td>
    </tr>

    <!-- spasi ttd -->
    <tr><td colspan="4" style="height:80px;"></td></tr>

    <tr>
      <td></td><td></td><td></td>
      <td align="center" style="text-transform: uppercase;">
        <u><b><?php echo $pejabat['nama_pejabat_desa']; ?></b></u>
      </td>
    </tr>
  </table>

</div>

<script>
  window.print();
</script>
</body>
</html>
