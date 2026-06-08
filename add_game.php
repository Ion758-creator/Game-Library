<?php
include 'auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$input = file_get_contents('php://input');
$game  = json_decode($input, true);

if (!$game || empty($game['title'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit();
}

$dataFile = __DIR__ . '/../data/data.json';

$games = [];
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $games   = json_decode($content, true);
    if (!is_array($games)) $games = [];
}

// Give a proper id
$maxId = 0;
foreach ($games as $g) {
    if (isset($g['id']) && $g['id'] > $maxId) $maxId = $g['id'];
}
$game['id']       = $maxId + 1;
$game['addedBy']  = getUsername();

$games[] = $game;

$result = file_put_contents($dataFile, json_encode($games, JSON_PRETTY_PRINT));

if ($result === false) {
    echo json_encode(['success' => false, 'error' => 'Could not write to file']);
} else {
    echo json_encode(['success' => true, 'game' => $game]);
}
