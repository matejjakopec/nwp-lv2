<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
<?php
$xml = simplexml_load_file("data.xml");

function valueOrEmpty($value) {
    return isset($value) ? $value : "";
}

$content = "
    <div class='container'>
        <div class='main-body'>
            <div class='row gutters-sm'>";

foreach ($xml->record as $person) {
    $content .=
        "<div class='col-md-4 mb-3'>
                     <div class='card'>
                        <div class='card-body'>
                            <div class='d-flex flex-column align-items-center text-center'>
                                <img src='" . valueOrEmpty($person->slika) . "' alt='Profile image' class='rounded-circle' width='150'>
                                <div class='mt-3'>
                                    <h4>" . valueOrEmpty($person->ime) . " " . valueOrEmpty($person->prezime) . "</h4>
                                    <p class='text-secondary mb-1'>" . valueOrEmpty($person->spol) . "</p>
                                    <p class='text-muted font-size-sm'>" . valueOrEmpty($person->email) . "</p>
                                    <p class='text-muted font-size-sm'>" . valueOrEmpty($person->zivotopis) . "</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
}

$content .= "</div></div></div>";

echo $content;
?>
</body>
</html>
