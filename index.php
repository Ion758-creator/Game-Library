<?php
include 'php/auth.php';
requireLogin();
include 'php/library.php';

if (!isset($games)) {
    $games = [];
}

$username = getUsername();
$totalGames    = count($games);
$playing       = count(array_filter($games, fn($g) => strtolower($g['status']) === 'playing'));
$completed     = count(array_filter($games, fn($g) => strtolower($g['status']) === 'completed'));
$backlog       = count(array_filter($games, fn($g) => strtolower($g['status']) === 'backlog'));
$totalHours    = array_sum(array_column($games, 'hours'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="container">

    <aside class="sidebar">

        <div class="logo">
            <i class="fa-solid fa-gamepad"></i>
            <div>
                <h2>MY LIBRARY</h2>
                <p>Game Collection</p>
            </div>
        </div>

        <ul class="menu">
            <li class="active" onclick="setFilter('all', this)"><i class="fa-solid fa-house"></i> Library</li>
            <li onclick="setFilter('playing', this)"><i class="fa-solid fa-gamepad"></i> Playing</li>
            <li onclick="setFilter('completed', this)"><i class="fa-solid fa-circle-check"></i> Completed</li>
            <li onclick="setFilter('backlog', this)"><i class="fa-solid fa-clock"></i> Backlog</li>
            <li onclick="setFilter('all', this)"><i class="fa-solid fa-table-cells"></i> All Games</li>
        </ul>

        <ul class="menu bottom">
            <li onclick="openModal('addGameModal')"><i class="fa-solid fa-circle-plus"></i> Add Game</li>
            <li onclick="openModal('settingsModal')"><i class="fa-solid fa-gear"></i> Settings</li>
            <li>
                <a href="php/logout.php" style="color:inherit;text-decoration:none;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </li>
        </ul>

        <div class="sidebar-card">
            <i class="fa-solid fa-gamepad big-icon"></i>
            <h3>Enjoy your games!</h3>
            <p>Keep track of your adventures 💜</p>
        </div>

    </aside>

    <main class="main">

        <div class="header">
            <div class="title">
                <h1>Game Library</h1>
                <p>All your games in one place</p>
            </div>

            <div class="header-right">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Search games..." oninput="filterGames()">
                </div>

                <!-- User profile button -->
                <a href="php/profile.php" class="user-btn" title="My Profile">
                    <i class="fa-solid fa-user"></i>
                    <?php echo htmlspecialchars($username); ?>
                </a>

                <button class="add-btn" onclick="openModal('addGameModal')">
                    <i class="fa-solid fa-plus"></i>
                    Add Game
                </button>
            </div>
        </div>

        <!-- Stats bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <i class="fa-solid fa-layer-group"></i>
                <span><?php echo $totalGames; ?> Total</span>
            </div>
            <div class="stat-item playing-stat">
                <i class="fa-solid fa-gamepad"></i>
                <span><?php echo $playing; ?> Playing</span>
            </div>
            <div class="stat-item completed-stat">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo $completed; ?> Completed</span>
            </div>
            <div class="stat-item backlog-stat">
                <i class="fa-solid fa-clock"></i>
                <span><?php echo $backlog; ?> Backlog</span>
            </div>
            <div class="stat-item hours-stat">
                <i class="fa-solid fa-hourglass-half"></i>
                <span><?php echo $totalHours; ?>h Played</span>
            </div>
        </div>

        <div class="filters">
            <button class="filter active" onclick="setFilter('all', this)">All</button>
            <button class="filter playing" onclick="setFilter('playing', this)">Playing</button>
            <button class="filter completed" onclick="setFilter('completed', this)">Completed</button>
            <button class="filter backlog" onclick="setFilter('backlog', this)">Backlog</button>

            <span class="total" id="totalCount">
                Total: <?php echo $totalGames; ?> games
            </span>
        </div>

        <div class="games-grid" id="gamesGrid">

            <?php foreach($games as $game) { ?>

                <div class="card"
                     data-status="<?php echo strtolower($game['status']); ?>"
                     data-title="<?php echo strtolower($game['title']); ?>">

                    <img src="<?php echo htmlspecialchars($game['image']); ?>"
                         alt="<?php echo htmlspecialchars($game['title']); ?>">

                    <div class="card-content">

                        <span class="status <?php echo strtolower($game['status']); ?>">
                            <?php echo htmlspecialchars($game['status']); ?>
                        </span>

                        <h2><?php echo htmlspecialchars($game['title']); ?></h2>

                        <div class="card-meta">
                            <?php if (!empty($game['genre'])): ?>
                                <span class="genre"><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($game['genre']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($game['hours']) && $game['hours'] > 0): ?>
                                <span class="hours"><i class="fa-solid fa-clock"></i> <?php echo $game['hours']; ?>h</span>
                            <?php endif; ?>
                        </div>

                        <div class="rating">
                            ⭐ <span><?php echo htmlspecialchars($game['rating']); ?>/10</span>
                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

        <footer>
            Made with ❤️ by Ion Șaptefrați
        </footer>

    </main>

</div>

<script src="js/script.js"></script>
</body>
</html>
