<?php
// download.php - Surat Lapor Hajatan (KTP/KK) aman

$baseDir = realpath(__DIR__ . "/../../../../../uploads/persyaratan_surat_lapor_hajatan");
// __DIR__ = folder konfirmasi/surat_lapor_hajatan
// naik 5 level sesuai struktur, lalu ke uploads/persyaratan_surat_lapor_hajatan

if (!$baseDir) {
  http_response_code(500);
  die("Folder upload tidak ditemukan.");
}

$sub = isset($_GET['sub']) ? $_GET['sub'] : '';
if (!in_array($sub, ['ktp','kk'])) {
  http_response_code(400);
  die("Subfolder tidak valid.");
}

if (!isset($_GET['file']) || trim($_GET['file']) === '') {
  http_response_code(400);
  die("Parameter file kosong.");
}

$file = basename($_GET['file']); // cegah path traversal

$allowedExt = ['jpg','jpeg','png','gif','webp','pdf','doc','docx','xls','xlsx'];
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt)) {
  http_response_code(403);
  die("Tipe file tidak diizinkan.");
}

$fullPath = $baseDir . DIRECTORY_SEPARATOR . $sub . DIRECTORY_SEPARATOR . $file;

if (!file_exists($fullPath)) {
  http_response_code(404);
  die("File tidak ditemukan.");
}

$mime = mime_content_type($fullPath);
$size = filesize($fullPath);

header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . $size);
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

readfile($fullPath);
exit;
