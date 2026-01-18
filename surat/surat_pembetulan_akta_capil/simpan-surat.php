<?php
include ('../../config/koneksi.php');

if (isset($_POST['submit'])) {

    $jenis_surat    = "Surat Pembetulan Akta Capil";
    $nik            = $_POST['fnik'];
    $keperluan      = addslashes($_POST['fkeperluan']);
    $status_surat   = "PENDING";
    $id_profil_desa = "1";

    // ✅ CEK: jangan izinkan buat baru kalau masih ada yg PENDING untuk NIK yang sama
    $cek = mysqli_query($connect, "
        SELECT 1
        FROM surat_pembetulan_akta_capil
        WHERE nik='$nik' AND status_surat='PENDING'
        LIMIT 1
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
            alert('Masih ada pengajuan Pembetulan Akta Capil yang PENDING. Silakan tunggu sampai dikonfirmasi / selesai dulu.');
            window.location.href='../index.php';
        </script>";
        exit;
    }

    // (opsional) alamat pemohon kalau mau disimpan
    $alamat_pemohon = !empty($_POST['falamat_pemohon']) ? addslashes($_POST['falamat_pemohon']) : NULL;

    // DATA PEMBETULAN
    $jenis_akta         = addslashes($_POST['fjenis_akta']);
    $no_akta            = !empty($_POST['fno_akta']) ? addslashes($_POST['fno_akta']) : NULL;

    $bagian_dibetulkan  = addslashes($_POST['fbagian_dibetulkan']);
    $data_sebelum       = addslashes($_POST['fdata_sebelum']);
    $data_sesudah       = addslashes($_POST['fdata_sesudah']);

    $keterangan         = !empty($_POST['fketerangan']) ? addslashes($_POST['fketerangan']) : NULL;

    // CATATAN AUTENTIK
    $catatan_autentik   = "Dokumen autentik membutuhkan tanda tangan langsung (tanda tangan basah), sehingga pengambilan surat dilakukan di Balai Desa.";

    // ===== FUNGSI UPLOAD =====
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

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newName = $fileField . '_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
        $dest = rtrim($uploadDir, '/').'/'.$newName;

        if (move_uploaded_file($tmpFile, $dest)) {
            return $newName;
        }
        return NULL;
    }

    // ✅ sesuaikan folder upload (kalau file ini ada di dalam /surat/..., path relatif harus benar)
    $uploadDir = "../../uploads/pembetulan_akta_capil/";

    // UPLOAD PERSYARATAN (WAJIB)
    $foto_kutipan_akta = uploadFile('ffoto_kutipan_akta', $uploadDir);
    $foto_kk_termohon  = uploadFile('ffoto_kk_termohon', $uploadDir);

    if (!$foto_kutipan_akta || !$foto_kk_termohon) {
        echo "Gagal: File wajib belum terupload atau format tidak didukung.";
        exit;
    }

    // INSERT
    $qTambahSurat = "
        INSERT INTO surat_pembetulan_akta_capil
        (jenis_surat, nik,
         jenis_akta, no_akta, bagian_dibetulkan, data_sebelum, data_sesudah,
         keterangan,
         foto_kutipan_akta, foto_kk_termohon,
         alamat_pemohon,
         catatan_autentik,
         keperluan,
         status_surat, id_profil_desa)
        VALUES
        ('$jenis_surat', '$nik',
         '$jenis_akta',
         ".($no_akta ? "'$no_akta'" : "NULL").",
         '$bagian_dibetulkan',
         '$data_sebelum',
         '$data_sesudah',
         ".($keterangan ? "'$keterangan'" : "NULL").",
         '$foto_kutipan_akta',
         '$foto_kk_termohon',
         ".($alamat_pemohon ? "'$alamat_pemohon'" : "NULL").",
         '$catatan_autentik',
         '$keperluan',
         '$status_surat', '$id_profil_desa')
    ";

    $TambahSurat = mysqli_query($connect, $qTambahSurat);

    if ($TambahSurat) {
        header("location:../index.php?pesan=berhasil");
        exit;
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($connect);
    }
}
?>
