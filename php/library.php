<?php
$dataFile = __DIR__ . '/../data/data.json';

if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $games = json_decode($content, true);
    if (!is_array($games)) {
        $games = [];
    }
} else {
    $games = [];
}
?>
