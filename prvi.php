<?php

$columnName = function ($value) {
    return $value->name;
};

$dbName = "radovi";
$dir = "backup/$dbName";

if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
    echo "<p>Cannot create directory uploads.</p>";
    exit;
}

$time = time();
$dbc = mysqli_connect("localhost", "root", "", $dbName);

if (!$dbc) {
    echo "<p>Cannot connect to database $dbName.</p>";
    exit;
}

$files = [];
$r = mysqli_query($dbc, "SHOW TABLES");

if (mysqli_num_rows($r) > 0) {
    echo "<p>Backup for database '$dbName'.</p>";

    while (list($table) = mysqli_fetch_array($r, MYSQLI_NUM)) {
        $q = "SELECT * FROM $table";
        $columns = array_map($columnName, $dbc->query($q)->fetch_fields());
        $r2 = mysqli_query($dbc, $q);

        if (mysqli_num_rows($r2) > 0) {
            $fileName = "$table" . "_" . "$time";
            $filePath = "$dir/$fileName.txt";

            if ($fp = fopen($filePath, "w+")) {
                $files[] = $fileName;

                while ($row = mysqli_fetch_array($r2, MYSQLI_NUM)) {
                    $rowText = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES ('" . implode("', '", $row) . "');\n";
                    fwrite($fp, $rowText);
                }
                fclose($fp);

                echo "<p>Table '$table' has been stored.</p>";

                if ($fp = gzopen("$dir/" . $fileName . "sql.gz", 'w9')) {
                    $content = file_get_contents($filePath);
                    gzwrite($fp, $content);
                    unlink($filePath);
                    gzclose($fp);

                    echo "<p>Table '$table' has been compressed.</p>";
                } else {
                    echo "<p>Error compressing table '$table'.</p>";
                }
            } else {
                echo "<p>Cannot open file $filePath.</p>";
                break;
            }
        }
    }
} else {
    echo "<p>Database $dbName contains no tables.</p>";
}

?>
