<?php
session_start();

$file = __DIR__ . "/../data/users.json";

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$content = file_get_contents($file);
$users   = json_decode($content, true);

if (!is_array($users)) {
    $users = [];
}

$username = trim($_POST["username"] ?? "");
$password = trim($_POST["password"] ?? "");
$fullname = trim($_POST["fullname"] ?? "");

if (empty($username) || empty($password)) {
    header("Location: login.php?error=empty");
    exit();
}

// ── REGISTER ──────────────────────────────────────────────
if (isset($_POST["register"])) {

    foreach ($users as $user) {
        if ($user["username"] === $username) {
            header("Location: login.php?error=exists");
            exit();
        }
    }

    $users[] = [
        "username" => $username,
        "fullname" => $fullname ?: $username,
        "password" => password_hash($password, PASSWORD_DEFAULT),
        "joined"   => date("Y-m-d")
    ];

    $result = file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

    if ($result === false) {
        header("Location: login.php?error=write");
        exit();
    }

    header("Location: login.php?success=1");
    exit();
}

// ── LOGIN ─────────────────────────────────────────────────
if (isset($_POST["login"])) {

    foreach ($users as $user) {
        if (
            $user["username"] === $username &&
            password_verify($password, $user["password"])
        ) {
            $_SESSION["username"] = $username;
            $_SESSION["fullname"] = $user["fullname"] ?? $username;
            header("Location: ../index.php");
            exit();
        }
    }

    header("Location: login.php?error=1");
    exit();
}
?>
