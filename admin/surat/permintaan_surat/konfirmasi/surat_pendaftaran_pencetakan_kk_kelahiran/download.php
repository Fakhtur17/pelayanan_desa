<?php
// download.php
// Download aman untuk berkas paket Akta Kelahiran + KK Kelahiran

// type = akta | kk
$type = isset($_GET['type']) ? strtolower(trim($_GET['type'])) : '';
if (!in_array($type, ['akta', 'kk'])) {
  http_response_code(400);
  die("Parameter type tidak valid.");
}

if (!isset($_GET['file']) || trim($_GET['file']) === '') {
  http_response_code(400);
  die("Parameter file kosong.");
}

// cegah path traversal
$file = basename($_GET['file']);

// batasi ekstensi
$allowedExt = ['jpg','jpeg','png','gif','webp','pdf','doc','docx','xls','xlsx'];
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt)) {
  http_response_code(403);
  die("Tipe file tidak diizinkan.");
}

// tentukan folder upload
if ($type === 'akta') {
  $baseDir = realpath(__DIR__ . "/../../../../../uploads/akta_kelahiran");
} else {
  $baseDir = realpath(__DIR__ . "/../../../../../uploads/kk_kelahiran");
}

if (!$baseDir) {
  http_response_code(500);
  die("Folder upload tidak ditemukan.");
}

$fullPath = $baseDir . DIRECTORY_SEPARATOR . $file;

if (!file_exists($fullPath)) {
  http_response_code(404);
  die("File tidak ditemukan.");
}

$mime = function_exists('mime_content_type') ? mime_content_type($fullPath) : 'application/octet-stream';
$size = filesize($fullPath);

header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . $size);
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

readfile($fullPath);
exit;
