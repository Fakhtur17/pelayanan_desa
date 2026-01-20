<?php
// download.php
// Paksa download file upload persyaratan_surat_keterangan dengan aman
// Struktur folder:
// uploads/persyaratan_surat_keterangan/ktp/xxxx
// uploads/persyaratan_surat_keterangan/kk/xxxx

$baseDir = realpath(__DIR__ . "/../../../../../uploads/persyaratan_surat_keterangan");
// __DIR__ = folder konfirmasi/surat_keterangan
// naik 5 level sesuai struktur, lalu ke uploads/persyaratan_surat_keterangan

if (!$baseDir) {
  http_response_code(500);
  die("Folder upload tidak ditemukan.");
}

// validasi subfolder
$sub = isset($_GET['sub']) ? strtolower(trim($_GET['sub'])) : '';
if (!in_array($sub, ['ktp', 'kk'])) {
  http_response_code(400);
  die("Subfolder tidak valid.");
}

// validasi parameter file
if (!isset($_GET['file']) || trim($_GET['file']) === '') {
  http_response_code(400);
  die("Parameter file kosong.");
}

// cegah path traversal
$file = basename($_GET['file']);

// batasi ekstensi
$allowedExt = ['jpg','jpeg','png','gif','webp','pdf'];
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt)) {
  http_response_code(403);
  die("Tipe file tidak diizinkan.");
}

// path file yang akan didownload
$fullPath = $baseDir . DIRECTORY_SEPARATOR . $sub . DIRECTORY_SEPARATOR . $file;

// validasi file ada
if (!file_exists($fullPath)) {
  http_response_code(404);
  die("File tidak ditemukan.");
}

$mime = function_exists('mime_content_type') ? mime_content_type($fullPath) : 'application/octet-stream';
$size = filesize($fullPath);

// header download
header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . $size);
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

readfile($fullPath);
exit;
