<?php
// download.php
// Paksa download file upload cetak_kembali_akta_capil dengan aman (anti path traversal)

$baseDir = realpath(__DIR__ . "/../../../../../uploads/cetak_kembali_akta_capil");

if (!$baseDir) {
  http_response_code(500);
  die("Folder upload tidak ditemukan.");
}

if (!isset($_GET['file']) || trim($_GET['file']) === '') {
  http_response_code(400);
  die("Parameter file kosong.");
}

$file = basename($_GET['file']); // cegah ../

$allowedExt = ['jpg','jpeg','png','gif','webp','pdf','doc','docx','xls','xlsx'];
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt)) {
  http_response_code(403);
  die("Tipe file tidak diizinkan.");
}

$fullPath = $baseDir . DIRECTORY_SEPARATOR . $file;

// proteksi tambahan: pastikan masih di dalam baseDir
$realFile = realpath($fullPath);
if ($realFile === false || strpos($realFile, $baseDir) !== 0) {
  http_response_code(403);
  die("Akses file ditolak.");
}

if (!file_exists($realFile)) {
  http_response_code(404);
  die("File tidak ditemukan.");
}

$mime = function_exists('mime_content_type') ? mime_content_type($realFile) : 'application/octet-stream';
$size = filesize($realFile);

header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . $size);
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

readfile($realFile);
exit;
