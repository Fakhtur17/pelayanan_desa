<?php
include "../../config/koneksi.php";

/* ===== Bulan Indo ===== */
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

/* ===== Builder filter (tanggal/bulan/tahun) ===== */
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

/* ===== Judul Cetak ===== */
$judulTambahan = "";
if(isset($_GET['filter']) && !empty($_GET['filter'])){
  $filter = $_GET['filter'];

  if($filter == '2' && !empty($_GET['tanggal'])){
    $tgl_lhr = $_GET['tanggal'];
    $tgl = date('d ', strtotime($tgl_lhr));
    $bln = date('F', strtotime($tgl_lhr));
    $thn = date(' Y', strtotime($tgl_lhr));
    $judulTambahan = "(Tanggal ".$tgl.$bulanIndo[$bln].$thn.")";
  } else if($filter == '3' && !empty($_GET['bulan']) && !empty($_GET['tahun'])){
    $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
    $judulTambahan = "(Bulan ".$nama_bulan[(int)$_GET['bulan']]." ".$_GET['tahun'].")";
  } else if($filter == '4' && !empty($_GET['tahun'])){
    $judulTambahan = "(Tahun ".$_GET['tahun'].")";
  }
}

/* ===== QUERY LENGKAP (sama seperti laporan baru) ===== */
$query = "
  SELECT 
    p.nik, p.no_kk, p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
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
    s.no_surat, s.tanggal_surat AS tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pendaftaran_pencetakan_kk_kelahiran s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  /* khusus ini: tanggal pakai created_at */
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
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="../../assets/img/mini-logo.png">
  <title>CETAK LAPORAN</title>
  <style>
    @page { margin: 2cm; }
    body { font-family: "Times New Roman", Times, serif; font-size: 12pt; }
    hr { border-bottom: 1px solid #000; height:0px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border:1px solid #000; padding:6px; vertical-align: top; }
    th { text-align:center; }
    .judul { text-align:center; font-size: 14pt; font-weight: bold; }
    .subjudul { text-align:center; font-size: 12pt; font-weight: bold; margin-top:4px; }
    .small { font-size: 10pt; }
  </style>
</head>
<body>

  <div class="judul">Laporan Surat Administrasi Desa - Surat Keluar</div>
  <?php if(!empty($judulTambahan)){ ?>
    <div class="subjudul"><?php echo $judulTambahan; ?></div>
  <?php } ?>
  <hr><br>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>No. Surat</th>
        <th>Tanggal Surat</th>
        <th>Jenis Surat</th>
        <th>NIK</th>
        <th>No KK</th>
        <th>Nama</th>
        <th>Tempat/Tgl Lahir</th>
        <th>JK</th>
        <th>Agama</th>
        <th>Jalan</th>
        <th>Dusun</th>
        <th>RT</th>
        <th>RW</th>
        <th>Desa</th>
        <th>Kecamatan</th>
        <th>Kota</th>
        <th>Pend. KK</th>
        <th>Pend. Terakhir</th>
        <th>Pend. Ditempuh</th>
        <th>Pekerjaan</th>
        <th>Status Kawin</th>
        <th>Status Keluarga</th>
        <th>WN</th>
        <th>Nama Ayah</th>
        <th>Nama Ibu</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = mysqli_query($connect, $query);

      if($sql === false){
        echo "<tr><td colspan='26'>Query error: ".mysqli_error($connect)."</td></tr>";
      } else {
        $no = 1;
        if(mysqli_num_rows($sql) > 0){
          while($data = mysqli_fetch_array($sql)){

            // TTL indo
            $tglTTL = '-';
            if(!empty($data['tgl_lahir'])){
              $tanggal = date('d', strtotime($data['tgl_lahir']));
              $bulan   = date('F', strtotime($data['tgl_lahir']));
              $tahun   = date('Y', strtotime($data['tgl_lahir']));
              $tglTTL  = $tanggal." ".$bulanIndo[$bulan]." ".$tahun;
            }
            $ttl = (!empty($data['tempat_lahir']) ? $data['tempat_lahir'] : '-') . ", " . $tglTTL;

            // tanggal surat indo
            $tglSuratIndo = '-';
            if(!empty($data['tanggal_surat'])){
              $tgl_s = date('d ', strtotime($data['tanggal_surat']));
              $bln_s = date('F', strtotime($data['tanggal_surat']));
              $thn_s = date(' Y', strtotime($data['tanggal_surat']));
              $tglSuratIndo = $tgl_s.$bulanIndo[$bln_s].$thn_s;
            }

            echo "<tr>";
            echo "<td style='text-align:center;'>".$no++."</td>";
            echo "<td>".$data['no_surat']."</td>";
            echo "<td>".$tglSuratIndo."</td>";
            echo "<td>".$data['jenis_surat']."</td>";
            echo "<td>".$data['nik']."</td>";
            echo "<td>".$data['no_kk']."</td>";
            echo "<td>".$data['nama']."</td>";
            echo "<td>".$ttl."</td>";
            echo "<td>".$data['jenis_kelamin']."</td>";
            echo "<td>".$data['agama']."</td>";
            echo "<td>".$data['jalan']."</td>";
            echo "<td>".$data['dusun']."</td>";
            echo "<td>".$data['rt']."</td>";
            echo "<td>".$data['rw']."</td>";
            echo "<td>".$data['desa']."</td>";
            echo "<td>".$data['kecamatan']."</td>";
            echo "<td>".$data['kota']."</td>";
            echo "<td>".$data['pend_kk']."</td>";
            echo "<td>".$data['pend_terakhir']."</td>";
            echo "<td>".$data['pend_ditempuh']."</td>";
            echo "<td>".$data['pekerjaan']."</td>";
            echo "<td>".$data['status_perkawinan']."</td>";
            echo "<td>".$data['status_dlm_keluarga']."</td>";
            echo "<td>".$data['kewarganegaraan']."</td>";
            echo "<td>".$data['nama_ayah']."</td>";
            echo "<td>".$data['nama_ibu']."</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='26' style='text-align:center;'>Data tidak ditemukan.</td></tr>";
        }
      }
      ?>
    </tbody>
  </table>

  <script>
    window.print();
  </script>

</body>
</html>
