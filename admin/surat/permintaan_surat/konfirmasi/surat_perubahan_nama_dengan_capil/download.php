<?php
// download.php
// Paksa download file upload perubahan nama capil dengan aman

$baseDir = realpath(__DIR__ . "/../../../../../uploads/perubahan_nama_capil");
// __DIR__ = folder konfirmasi/surat_perubahan_nama_capil
// naik 5 level sesuai struktur kamu, lalu ke uploads/perubahan_nama_capil

if (!$baseDir) {
  http_response_code(500);
  die("Folder upload tidak ditemukan.");
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
