<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();

if (empty($_FILES['file'])) cl_fail('No file uploaded.');
$file = $_FILES['file'];

if ($file['error'] !== UPLOAD_ERR_OK) cl_fail('Upload failed.');
if ($file['size'] > CL_MAX_UPLOAD) cl_fail('File is too large (max 2 MB).');

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!isset($allowed[$mime])) cl_fail('Only JPG, PNG, WEBP or GIF images are allowed.');

// double-check it's really an image (defends against renamed non-image files)
if (@getimagesize($file['tmp_name']) === false) cl_fail('Invalid image file.');

$ext = $allowed[$mime];
$name = bin2hex(random_bytes(12)) . '.' . $ext;
$destDir = __DIR__ . '/../uploads/';
if (!is_dir($destDir)) mkdir($destDir, 0755, true);
$dest = $destDir . $name;

if (!move_uploaded_file($file['tmp_name'], $dest)) cl_fail('Could not save the uploaded file.', 500);

cl_ok(['path' => 'uploads/' . $name]);
