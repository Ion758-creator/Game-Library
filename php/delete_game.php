<?php
include 'auth.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$input = file_get_contents('php://input');
$data  = json_decode($input, true);
$id    = intval($data['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    exit();
}

$dataFile = __DIR__ . '/../data/data.json';
$games = json_decode(file_get_contents($dataFile), true) ?? [];
$games = array_values(array_filter($games, fn($g) => $g['id'] != $id));

file_put_contents($dataFile, json_encode($games, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
