<?php
include ('../../config/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

  $jenis_surat    = "Surat Keterangan Kepemilikan Kendaraan Bermotor";
  $nik            = mysqli_real_escape_string($connect, $_POST['fnik']);

  // DATA KENDARAAN
  $merk_type        = mysqli_real_escape_string($connect, $_POST['fmerk_type']);
  $jenis_model      = mysqli_real_escape_string($connect, $_POST['fjenis_model']);
  $tahun_pembuatan  = mysqli_real_escape_string($connect, $_POST['ftahun_pembuatan']);
  $cc               = mysqli_real_escape_string($connect, $_POST['fcc']);
  $warna_cat        = mysqli_real_escape_string($connect, $_POST['fwarna_cat']);
  $no_rangka        = mysqli_real_escape_string($connect, $_POST['fno_rangka']);
  $no_mesin         = mysqli_real_escape_string($connect, $_POST['fno_mesin']);
  $no_polisi        = mysqli_real_escape_string($connect, $_POST['fno_polisi']);
  $no_bpkb          = mysqli_real_escape_string($connect, $_POST['fno_bpkb']);
  $atas_nama_pemilik= mysqli_real_escape_string($connect, $_POST['fatas_nama_pemilik']);
  $alamat_pemilik   = mysqli_real_escape_string($connect, $_POST['falamat_pemilik']);

  // KEPERLUAN
  $keperluan      = mysqli_real_escape_string($connect, $_POST['fkeperluan']);

  $status_surat   = "PENDING";
  $id_profil_desa = 1;

  // cek nik ada
  $qCekNik = mysqli_query($connect, "SELECT nik FROM penduduk WHERE nik='$nik' LIMIT 1");
  if(!$qCekNik || mysqli_num_rows($qCekNik) == 0){
    header("location:index.php?pesan=gagal");
    exit;
  }

  // ===== fungsi upload =====
  function uploadFile($fileField, $uploadDir, $prefix){
    if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] !== UPLOAD_ERR_OK) {
      return NULL;
    }

    $namaFile = $_FILES[$fileField]['name'];
    $tmpFile  = $_FILES[$fileField]['tmp_name'];
    $ext      = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png','pdf'];
    if (!in_array($ext, $allowed)) {
      return NULL;
    }

    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    // nama file rapi + ada nik + timestamp
    $newName = $prefix . '_' . $GLOBALS['nik'] . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
    $dest    = rtrim($uploadDir, '/').'/'.$newName;

    if (move_uploaded_file($tmpFile, $dest)) {
      return $newName;
    }
    return NULL;
  }

  // ===== folder upload =====
  // lokasi file ini: surat/.../simpan-surat.php
  // maka naik 2 level -> /surat/  lalu ke /uploads/...
  $baseDir = "../../uploads/persyaratan_surat_kepemilikan_kendaraan_bermotor/";

  // upload ke subfolder ktp dan kk
  $ktp_pemilik = uploadFile('ktp_pemilik', $baseDir . "ktp", "KTP");
  $kk_pemilik  = uploadFile('kk_pemilik',  $baseDir . "kk",  "KK");

  // kalau upload wajib dan gagal
  if(empty($ktp_pemilik) || empty($kk_pemilik)){
    echo "<script>alert('Upload KTP/KK gagal. Pastikan file JPG/PNG/PDF dan tidak error.');window.location='index.php';</script>";
    exit;
  }

  // ===== INSERT sesuai database kamu =====
  $qTambahSurat = "
    INSERT INTO surat_keterangan_kepemilikan_kendaraan_bermotor
    (jenis_surat, no_surat, nik,
     merk_type, jenis_model, tahun_pembuatan, cc, warna_cat,
     no_rangka, no_mesin, no_polisi, no_bpkb,
     atas_nama_pemilik, alamat_pemilik,
     ktp_pemilik, kk_pemilik,
     keperluan, status_surat, id_profil_desa)
    VALUES
    ('$jenis_surat', NULL, '$nik',
     '$merk_type', '$jenis_model', '$tahun_pembuatan', '$cc', '$warna_cat',
     '$no_rangka', '$no_mesin', '$no_polisi', '$no_bpkb',
     '$atas_nama_pemilik', '$alamat_pemilik',
     '$ktp_pemilik', '$kk_pemilik',
     '$keperluan', '$status_surat', '$id_profil_desa')
  ";

  $TambahSurat = mysqli_query($connect, $qTambahSurat);

  if($TambahSurat){
    header("location:index.php?pesan=berhasil");
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($connect);
  }
}
?>
