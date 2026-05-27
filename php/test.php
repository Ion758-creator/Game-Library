<!DOCTYPE html>
<html>
<body>
<?php
$message = "Pagina principala functioneaza!";

echo "<h1>$message</h1>";

echo "<script>console.log('$message');</script>";
?>

<?php

$numere = [12, 9, 13, 1, 0, 10, 55, 6, 8, 20];

$pare   = 0;
$impare = 0;

echo "<h2>Verificare numere din array:</h2>";

for ($i = 0; $i < count($numere); $i++) {
    if ($numere[$i] % 2 == 0) {
        echo "Numărul " . $numere[$i] . " este <strong>PAR</strong><br>";
        $pare++;
    } else {
        echo "Numărul " . $numere[$i] . " este <em>IMPAR</em><br>";
        $impare++;
    }
}
echo "<p>Total numere <strong>PARE</strong>: $pare</p>";
echo "<p>Total numere <em>IMPARE</em>: $impare</p>";
?>

</body>
</html>