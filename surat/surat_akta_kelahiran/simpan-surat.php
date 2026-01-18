<?php
include ('../../config/koneksi.php');

if (isset($_POST['submit'])) {

    $jenis_surat    = "Surat Pengajuan Akta Kelahiran";
    $nik            = $_POST['fnik'];
    $keterangan     = addslashes($_POST['fkeperluan']); // dari input "Keperluan Surat" kamu
    $status_surat   = "PENDING";
    $id_profil_desa = "1";

    // DATA BAYI
    $nama_bayi          = addslashes($_POST['fnama_bayi']);
    $jenis_kelamin_bayi = addslashes($_POST['fjenis_kelamin_bayi']);
    $tempat_lahir_bayi  = addslashes($_POST['ftempat_lahir_bayi']);
    $tgl_lahir_bayi     = $_POST['ftgl_lahir_bayi'];
    $jam_lahir_bayi     = !empty($_POST['fjam_lahir_bayi']) ? $_POST['fjam_lahir_bayi'] : NULL;
    $anak_ke            = !empty($_POST['fanak_ke']) ? addslashes($_POST['fanak_ke']) : NULL;
    $berat_bayi         = !empty($_POST['fberat_bayi']) ? addslashes($_POST['fberat_bayi']) : NULL;
    $panjang_bayi       = !empty($_POST['fpanjang_bayi']) ? addslashes($_POST['fpanjang_bayi']) : NULL;

    // DATA AYAH & IBU
    $nik_ayah  = addslashes($_POST['fnik_ayah']);
    $nama_ayah = addslashes($_POST['fnama_ayah']);
    $nik_ibu   = addslashes($_POST['fnik_ibu']);
    $nama_ibu  = addslashes($_POST['fnama_ibu']);

    // ALAMAT PEMOHON
    $alamat_pemohon = addslashes($_POST['falamat_pemohon']);

    // FUNGSI UPLOAD
    function uploadFile($fileField, $uploadDir) {
        if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] != 0) {
            return NULL;
        }

        $namaFile = $_FILES[$fileField]['name'];
        $tmpFile  = $_FILES[$fileField]['tmp_name'];

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        $allowed = array('jpg','jpeg','png','pdf','doc','docx','zip','rar');
        if (!in_array($ext, $allowed)) {
            return NULL;
        }

        $newName = $fileField . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $dest = $uploadDir . $newName;

        if (move_uploaded_file($tmpFile, $dest)) {
            return $newName;
        }

        return NULL;
    }

    $uploadDir = "../../uploads/akta_kelahiran/";

    // UPLOAD BERKAS
    $surat_kelahiran_rs     = uploadFile('fsurat_kelahiran_rs', $uploadDir);
    $fc_buku_nikah          = uploadFile('ffc_buku_nikah', $uploadDir);
    $kk_pemohon             = uploadFile('fkk_pemohon', $uploadDir);
    $ktp_ayah               = uploadFile('fktp_ayah', $uploadDir);
    $ktp_ibu                = uploadFile('fktp_ibu', $uploadDir);
    $dokumen_pendukung_lain = uploadFile('fdokumen_pendukung_lain', $uploadDir);

    // INSERT SESUAI KOLOM TABEL KAMU
    $qTambahSurat = "
        INSERT INTO surat_pengajuan_akta_kelahiran
        (jenis_surat, nik,
         nama_bayi, jenis_kelamin_bayi, tempat_lahir_bayi, tgl_lahir_bayi, jam_lahir_bayi, anak_ke, berat_bayi, panjang_bayi,
         nik_ayah, nama_ayah, nik_ibu, nama_ibu,
         alamat_pemohon,
         surat_kelahiran_rs, fc_buku_nikah, kk_pemohon, ktp_ayah, ktp_ibu, dokumen_pendukung_lain,
         keterangan,
         status_surat, id_profil_desa)
        VALUES
        ('$jenis_surat', '$nik',
         '$nama_bayi', '$jenis_kelamin_bayi', '$tempat_lahir_bayi', '$tgl_lahir_bayi',
         ".($jam_lahir_bayi ? "'$jam_lahir_bayi'" : "NULL").",
         ".($anak_ke ? "'$anak_ke'" : "NULL").",
         ".($berat_bayi ? "'$berat_bayi'" : "NULL").",
         ".($panjang_bayi ? "'$panjang_bayi'" : "NULL").",
         '$nik_ayah', '$nama_ayah', '$nik_ibu', '$nama_ibu',
         '$alamat_pemohon',
         ".($surat_kelahiran_rs ? "'$surat_kelahiran_rs'" : "NULL").",
         ".($fc_buku_nikah ? "'$fc_buku_nikah'" : "NULL").",
         ".($kk_pemohon ? "'$kk_pemohon'" : "NULL").",
         ".($ktp_ayah ? "'$ktp_ayah'" : "NULL").",
         ".($ktp_ibu ? "'$ktp_ibu'" : "NULL").",
         ".($dokumen_pendukung_lain ? "'$dokumen_pendukung_lain'" : "NULL").",
         '$keterangan',
         '$status_surat', '$id_profil_desa')
    ";

    $TambahSurat = mysqli_query($connect, $qTambahSurat);

    if ($TambahSurat) {
        header("location:../index.php?pesan=berhasil");
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($connect);
    }
}
?>
