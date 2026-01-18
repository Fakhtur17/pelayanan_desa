<?php
include ('../../config/koneksi.php');

if (isset($_POST['submit'])) {

    $jenis_surat    = "Surat Permohonan Cetak Kembali Akta Capil";
    $nik            = $_POST['fnik'];
    $keperluan      = addslashes($_POST['fkeperluan']);
    $status_surat   = "PENDING";
    $id_profil_desa = "1";

    // DATA AKTA
    $jenis_akta         = addslashes($_POST['fjenis_akta']);
    $nomor_akta         = !empty($_POST['fnomor_akta']) ? addslashes($_POST['fnomor_akta']) : NULL;
    $tahun_akta         = !empty($_POST['ftahun_akta']) ? addslashes($_POST['ftahun_akta']) : NULL;

    $alasan_permohonan  = addslashes($_POST['falasan_permohonan']);
    $keterangan_alasan  = !empty($_POST['fketerangan_alasan']) ? addslashes($_POST['fketerangan_alasan']) : NULL;

    $asal_akta          = addslashes($_POST['fasal_akta']);
    $daerah_penerbit    = !empty($_POST['fdaerah_penerbit']) ? addslashes($_POST['fdaerah_penerbit']) : NULL;

    // ===== FUNGSI UPLOAD (sama gaya dengan punyamu) =====
    function uploadFile($fileField, $uploadDir) {
        if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] != 0) {
            return NULL;
        }

        $namaFile = $_FILES[$fileField]['name'];
        $tmpFile  = $_FILES[$fileField]['tmp_name'];

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        $allowed = array('jpg','jpeg','png','pdf','doc','docx');
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

    $uploadDir = "../../uploads/cetak_kembali_akta_capil/";

    // UPLOAD BERKAS (sesuai syarat)
    $foto_kk   = uploadFile('ffoto_kk', $uploadDir); // wajib
    $bukti_akta = uploadFile('fbukti_akta', $uploadDir); // opsional
    $bukti_kehilangan_atau_pernyataan = uploadFile('fbukti_kehilangan_atau_pernyataan', $uploadDir); // wajib
    $dok_konfirmasi_penerbit = uploadFile('fdok_konfirmasi_penerbit', $uploadDir); // opsional

    // Validasi minimal file wajib
    if (!$foto_kk || !$bukti_kehilangan_atau_pernyataan) {
        echo "Gagal: File wajib belum terupload atau format tidak didukung.";
        exit;
    }

    // Validasi tambahan: jika asal akta luar daerah, surat konfirmasi wajib
    if ($asal_akta == "Luar Daerah" && !$dok_konfirmasi_penerbit) {
        echo "Gagal: Karena asal akta Luar Daerah, Surat Konfirmasi Penerbit wajib diupload.";
        exit;
    }

    // INSERT ke tabel baru
    $qTambahSurat = "
        INSERT INTO surat_cetak_kembali_akta_capil
        (jenis_surat, nik,
         jenis_akta, nomor_akta, tahun_akta,
         alasan_permohonan, keterangan_alasan,
         asal_akta, daerah_penerbit,
         foto_kk, bukti_akta, bukti_kehilangan_atau_pernyataan, dokumen_konfirmasi_penerbit,
         keperluan,
         status_surat, id_profil_desa)
        VALUES
        ('$jenis_surat', '$nik',
         '$jenis_akta',
         ".($nomor_akta ? "'$nomor_akta'" : "NULL").",
         ".($tahun_akta ? "'$tahun_akta'" : "NULL").",
         '$alasan_permohonan',
         ".($keterangan_alasan ? "'$keterangan_alasan'" : "NULL").",
         '$asal_akta',
         ".($daerah_penerbit ? "'$daerah_penerbit'" : "NULL").",
         '$foto_kk',
         ".($bukti_akta ? "'$bukti_akta'" : "NULL").",
         '$bukti_kehilangan_atau_pernyataan',
         ".($dok_konfirmasi_penerbit ? "'$dok_konfirmasi_penerbit'" : "NULL").",
         '$keperluan',
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
