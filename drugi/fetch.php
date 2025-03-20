<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
session_start();

$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    exit("<p>No files for decrypting.</p>");
}

$files = array_filter(array_diff(scandir($uploadDir), ['..', '.']), function($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'txt';
});

if (empty($files)) {
    echo "<p>No files for decrypting.</p>";
} else {
    echo "<ul>";
    foreach ($files as $file) {
        $nameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
        echo "<li><a href='download.php?file=$nameWithoutExt'>$nameWithoutExt</a></li>";
    }
    echo "</ul>";
}
?>
</body>
</html>
