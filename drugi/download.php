<?php
session_start();

if (!isset($_GET['file']) || empty($_GET['file'])) {
    exit("Invalid file request.");
}

$file = basename($_GET['file']);
$filePath = "uploads/$file.txt";

if (!file_exists($filePath)) {
    exit("File not found.");
}

$decryptionKey = md5('kljuc za enkripciju');
$cipher = "AES-128-CTR";
$options = 0;
$decryptionIv = isset($_SESSION['iv']) ? $_SESSION['iv'] : '';

if (empty($decryptionIv)) {
    exit("Decryption IV is missing.");
}

$contentEncrypted = file_get_contents($filePath);
$contentDecrypted = openssl_decrypt(base64_decode($contentEncrypted), $cipher, $decryptionKey, $options, $decryptionIv);

$tempFile = "uploads/$file";
file_put_contents($tempFile, $contentDecrypted);
clearstatcache();

if (file_exists($tempFile)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($tempFile) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($tempFile));
    ob_clean();
    flush();
    readfile($tempFile);
    unlink($tempFile);
    exit;
}

exit("Error processing file.");