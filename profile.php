<?php
include 'php/auth.php';
requireLogin();
include 'php/library.php';

$username = getUsername();

// Stats
$total     = count($games);
$playing   = count(array_filter($games, fn($g) => strtolower($g['status']) === 'playing'));
$completed = count(array_filter($games, fn($g) => strtolower($g['status']) === 'completed'));
$backlog   = count(array_filter($games, fn($g) => strtolower($g['status']) === 'backlog'));
$avgRating = $total > 0
    ? round(array_sum(array_column($games, 'rating')) / $total, 1)
    : 0;

// User's games (all for now — later can be filtered by addedBy)
$userGames = $games;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library | Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
    
</head>
<body>

<div class="container">

    <aside class="sidebar">
        <div class="logo">
            🎮
            <div>
                <h2>MY LIBRARY</h2>
                <p>Game Collection</p>
            </div>
        </div>

        <ul class="menu">
            <li onclick="window.location='index.php'">🏠 Library</li>
            <li onclick="window.location='index.php?filter=playing'">🎮 Playing</li>
            <li onclick="window.location='index.php?filter=completed'">✅ Completed</li>
            <li onclick="window.location='index.php?filter=backlog'">🕐 Backlog</li>
            <li onclick="window.location='index.php'">☰ All Games</li>
        </ul>

        <ul class="menu bottom">
            <li class="active">👤 Profile</li>
            <li onclick="window.location='php/logout.php'">🚪 Logout</li>
        </ul>

        <div class="sidebar-card">
            
            <h3>Enjoy your games!</h3>
            <p>Keep track of your adventures 💜</p>
        </div>
    </aside>

    <main class="main">

        <div class="header">
            <div class="title">
                <h1>My Profile</h1>
                <p>Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
            </div>
            <div class="header-right">
                <a href="index.php" class="add-btn">
                    ← Back to Library
                </a>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-avatar">
                👤
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($username); ?></h2>
                <p class="profile-role">⭐ Game Collector</p>
                <p class="profile-joined">📅 Member since 2025</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon all">📚</div>
                <div class="stat-info">
                    <span class="stat-number"><?php echo $total; ?></span>
                    <span class="stat-label">Total Games</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon playing">🎮</div>
                <div class="stat-info">
                    <span class="stat-number"><?php echo $playing; ?></span>
                    <span class="stat-label">Playing</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed">✅</div>
                <div class="stat-info">
                    <span class="stat-number"><?php echo $completed; ?></span>
                    <span class="stat-label">Completed</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon backlog">🕐</div>
                <div class="stat-info">
                    <span class="stat-number"><?php echo $backlog; ?></span>
                    <span class="stat-label">Backlog</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rating">⭐</div>
                <div class="stat-info">
                    <span class="stat-number"><?php echo $avgRating; ?></span>
                    <span class="stat-label">Avg Rating</span>
                </div>
            </div>
        </div>

        <!-- My Games Table -->
        <div class="section-title">
            <h2>📋 My Games</h2>
        </div>

        <div class="games-table-wrap">
            <table class="games-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Game</th>
                        <th>Genre</th>
                        <th>Status</th>
                        <th>Rating</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userGames as $i => $game): ?>
                    <tr>
                        <td class="td-num"><?php echo $i + 1; ?></td>
                        <td class="td-title">
                            <img src="<?php echo htmlspecialchars($game['image']); ?>"
                                 alt="<?php echo htmlspecialchars($game['title']); ?>">
                            <?php echo htmlspecialchars($game['title']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($game['genre'] ?? '—'); ?></td>
                        <td>
                            <span class="status <?php echo strtolower($game['status']); ?>">
                                <?php echo htmlspecialchars($game['status']); ?>
                            </span>
                        </td>
                        <td class="td-rating">⭐ <?php echo htmlspecialchars($game['rating']); ?></td>
                        <td class="td-notes"><?php echo htmlspecialchars($game['notes'] ?? '—'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <footer>Made with ❤️ by Ion Șaptefrați</footer>

    </main>

</div>

</body>
</html>
