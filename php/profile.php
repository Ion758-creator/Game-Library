<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=login_required");
    exit();
}

$username = $_SESSION['username'];

// Load users.json to get user info
$usersFile = __DIR__ . "/../data/users.json";
$users = json_decode(file_get_contents($usersFile), true) ?? [];

$currentUser = null;
foreach ($users as $u) {
    if ($u['username'] === $username) {
        $currentUser = $u;
        break;
    }
}

// Load games from JSON
$gamesFile = __DIR__ . "/../data/data.json";
$games = json_decode(file_get_contents($gamesFile), true) ?? [];

$totalGames  = count($games);
$playing     = count(array_filter($games, fn($g) => strtolower($g['status']) === 'playing'));
$completed   = count(array_filter($games, fn($g) => strtolower($g['status']) === 'completed'));
$backlog     = count(array_filter($games, fn($g) => strtolower($g['status']) === 'backlog'));
$totalHours  = array_sum(array_column($games, 'hours'));

// Favourite genre (most common in collection)
$genres = array_column($games, 'genre');
$genreCount = array_count_values(array_filter($genres));
arsort($genreCount);
$favGenre = !empty($genreCount) ? array_key_first($genreCount) : 'N/A';

// Highest rated game
usort($games, fn($a, $b) => $b['rating'] <=> $a['rating']);
$topGame = !empty($games) ? $games[0] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile – <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .profile-wrapper {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #8b5cf6;
            text-decoration: none;
            margin-bottom: 30px;
            font-size: 15px;
            transition: 0.2s;
        }
        .back-link:hover { color: #a78bfa; }

        .profile-card {
            background: #0b1020;
            border: 1px solid #1e2a4a;
            border-radius: 20px;
            padding: 40px;
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 0 20px rgba(139,92,246,0.4);
        }

        .profile-info h2 {
            font-size: 26px;
            margin-bottom: 6px;
        }

        .profile-info p {
            color: #888;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            background: rgba(139,92,246,0.15);
            border: 1px solid rgba(139,92,246,0.3);
            color: #a78bfa;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #0b1020;
            border: 1px solid #1e2a4a;
            border-radius: 14px;
            padding: 22px 20px;
            text-align: center;
            transition: 0.2s;
        }
        .stat-card:hover {
            border-color: #8b5cf6;
            transform: translateY(-2px);
        }

        .stat-card i {
            font-size: 24px;
            margin-bottom: 10px;
            display: block;
        }

        .stat-card .num {
            font-size: 28px;
            font-weight: 700;
            display: block;
        }

        .stat-card .label {
            font-size: 13px;
            color: #888;
            margin-top: 4px;
            display: block;
        }

        .stat-card.playing-card i { color: #3b82f6; }
        .stat-card.completed-card i { color: #22c55e; }
        .stat-card.backlog-card i { color: #f59e0b; }
        .stat-card.hours-card i { color: #ec4899; }
        .stat-card.total-card i { color: #8b5cf6; }
        .stat-card.genre-card i { color: #06b6d4; }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i { color: #8b5cf6; }

        .top-game-card {
            background: #0b1020;
            border: 1px solid #1e2a4a;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .top-game-card img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .top-game-card .game-info h3 { font-size: 17px; margin-bottom: 6px; }
        .top-game-card .game-info p  { color: #888; font-size: 14px; }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            color: #ef4444;
            padding: 10px 22px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 15px;
            transition: 0.2s;
        }
        .logout-btn:hover {
            background: rgba(239,68,68,0.2);
        }
    </style>
</head>
<body>

<div class="profile-wrapper">

    <a href="../index.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to Library
    </a>

    <!-- Profile card -->
    <div class="profile-card">
        <div class="avatar">
            <?php echo strtoupper(substr($username, 0, 1)); ?>
        </div>
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p>Member of Game Library</p>
            <span class="badge"><i class="fa-solid fa-gamepad"></i> Gamer</span>
        </div>
    </div>

    <!-- Stats -->
    <p class="section-title"><i class="fa-solid fa-chart-bar"></i> My Statistics</p>

    <div class="stats-grid">
        <div class="stat-card total-card">
            <i class="fa-solid fa-layer-group"></i>
            <span class="num"><?php echo $totalGames; ?></span>
            <span class="label">Total Games</span>
        </div>
        <div class="stat-card playing-card">
            <i class="fa-solid fa-gamepad"></i>
            <span class="num"><?php echo $playing; ?></span>
            <span class="label">Playing</span>
        </div>
        <div class="stat-card completed-card">
            <i class="fa-solid fa-circle-check"></i>
            <span class="num"><?php echo $completed; ?></span>
            <span class="label">Completed</span>
        </div>
        <div class="stat-card backlog-card">
            <i class="fa-solid fa-clock"></i>
            <span class="num"><?php echo $backlog; ?></span>
            <span class="label">Backlog</span>
        </div>
        <div class="stat-card hours-card">
            <i class="fa-solid fa-hourglass-half"></i>
            <span class="num"><?php echo $totalHours; ?>h</span>
            <span class="label">Hours Played</span>
        </div>
        <div class="stat-card genre-card">
            <i class="fa-solid fa-tag"></i>
            <span class="num" style="font-size:18px;"><?php echo htmlspecialchars($favGenre); ?></span>
            <span class="label">Fav Genre</span>
        </div>
    </div>

    <!-- Top rated game -->
    <?php if ($topGame): ?>
    <p class="section-title"><i class="fa-solid fa-trophy"></i> Top Rated Game</p>
    <div class="top-game-card">
        <img src="../<?php echo htmlspecialchars($topGame['image']); ?>"
             alt="<?php echo htmlspecialchars($topGame['title']); ?>">
        <div class="game-info">
            <h3><?php echo htmlspecialchars($topGame['title']); ?></h3>
            <p>⭐ <?php echo $topGame['rating']; ?>/10 &nbsp;•&nbsp;
               <?php echo htmlspecialchars($topGame['genre'] ?? ''); ?> &nbsp;•&nbsp;
               <?php echo $topGame['hours']; ?>h played</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Logout -->
    <a href="logout.php" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>

</div>

</body>
</html>
