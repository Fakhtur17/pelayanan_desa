<?php
include ('../../config/koneksi.php');
include ('../part/akses.php');

// JANGAN tampilkan error ke output
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// ====== HEADER CSV (paling stabil untuk NIK/KK) ======
$filename = "Laporan_Surat_Keluar_" . date("Y-m-d_H-i-s") . ".csv";
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// UTF-8 BOM biar Excel Windows gak aneh encoding
echo "\xEF\xBB\xBF";

// Bulan Indo
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

// Builder filter (sama seperti laporan)
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

// =========================
// QUERY UTAMA (sama seperti punyamu)
// =========================
$query = "
SELECT * FROM (
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_berkelakuan_baik s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_domisili s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_usaha s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_lapor_hajatan s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pengantar_skck s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_cetak_kembali_akta_capil s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_akta_kematian s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_kepemilikan_kendaraan_bermotor s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_keterangan_perhiasan s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pembetulan_akta_capil s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pengajuan_akta_kelahiran s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_pendaftaran_pencetakan_kk_kelahiran s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereTanggalSurat

  UNION ALL
  SELECT 
    CAST(p.nik AS CHAR(25))   AS nik,
    CAST(p.no_kk AS CHAR(25)) AS no_kk,
    p.nama, p.tempat_lahir, p.tgl_lahir, p.jenis_kelamin, p.agama,
    p.jalan, p.dusun, p.rt, p.rw, p.desa, p.kecamatan, p.kota,
    p.pend_kk, p.pend_terakhir, p.pend_ditempuh, p.pekerjaan,
    p.status_perkawinan, p.status_dlm_keluarga, p.kewarganegaraan,
    p.nama_ayah, p.nama_ibu,
    s.no_surat, s.created_at AS tanggal_surat, s.jenis_surat
  FROM penduduk p
  JOIN surat_perubahan_nama_capil s ON s.nik = p.nik
  WHERE UPPER(s.status_surat) = 'SELESAI' $whereCreatedAt

) AS gabungan
ORDER BY tanggal_surat DESC
";

$sql = mysqli_query($connect, $query);
if(!$sql){
  // keluarkan 1 baris error dalam CSV
  echo "ERROR," . str_replace(["\r","\n"], " ", mysqli_error($connect));
  exit;
}

// ====== OUTPUT CSV ======
$out = fopen("php://output", "w");

// Header kolom
fputcsv($out, array(
  "No","No Surat","Tanggal Surat","Jenis Surat","NIK","No KK","Nama","Tempat/Tgl Lahir",
  "Jenis Kelamin","Agama","Jalan","Dusun","RT","RW","Desa","Kecamatan","Kota",
  "Pendidikan di KK","Pendidikan Terakhir","Pendidikan Ditempuh","Pekerjaan",
  "Status Perkawinan","Status Dlm Keluarga","Kewarganegaraan","Nama Ayah","Nama Ibu"
));

$no = 1;
while($data = mysqli_fetch_assoc($sql)){

  // TTL
  $tglTTL = "-";
  if(!empty($data['tgl_lahir'])){
    $tanggal = date('d', strtotime($data['tgl_lahir']));
    $bulan   = date('F', strtotime($data['tgl_lahir']));
    $tahun   = date('Y', strtotime($data['tgl_lahir']));
    $tglTTL  = $tanggal . " " . $bulanIndo[$bulan] . " " . $tahun;
  }
  $ttl = (!empty($data['tempat_lahir']) ? $data['tempat_lahir'] : "-") . ", " . $tglTTL;

  // Tanggal surat Indo
  $tglSuratIndo = "-";
  if(!empty($data['tanggal_surat'])){
    $tgl_s = date('d', strtotime($data['tanggal_surat'])) . " ";
    $bln_s = date('F', strtotime($data['tanggal_surat']));
    $thn_s = " " . date('Y', strtotime($data['tanggal_surat']));
    $tglSuratIndo = $tgl_s . $bulanIndo[$bln_s] . $thn_s;
  }

  // PAKSA EXCEL ANGAP TEKS: tambah TAB di depan
  $nik  = "\t" . trim((string)$data['nik']);
  $nokk = "\t" . trim((string)$data['no_kk']);

  fputcsv($out, array(
    $no++,
    $data['no_surat'],
    $tglSuratIndo,
    $data['jenis_surat'],
    $nik,
    $nokk,
    $data['nama'],
    $ttl,
    $data['jenis_kelamin'],
    $data['agama'],
    $data['jalan'],
    $data['dusun'],
    $data['rt'],
    $data['rw'],
    $data['desa'],
    $data['kecamatan'],
    $data['kota'],
    $data['pend_kk'],
    $data['pend_terakhir'],
    $data['pend_ditempuh'],
    $data['pekerjaan'],
    $data['status_perkawinan'],
    $data['status_dlm_keluarga'],
    $data['kewarganegaraan'],
    $data['nama_ayah'],
    $data['nama_ibu']
  ));
}

fclose($out);
exit;
