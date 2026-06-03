<?php
include 'php/library.php';

if (!isset($games)) {
    $games = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Game Library</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
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

                <button class="add-btn" onclick="openModal('addGameModal')">
                    <i class="fa-solid fa-plus"></i>
                    Add Game
                </button>

            </div>

        </div>

        <div class="filters">

            <button class="filter active" onclick="setFilter('all', this)">All</button>
            <button class="filter playing" onclick="setFilter('playing', this)">Playing</button>
            <button class="filter completed" onclick="setFilter('completed', this)">Completed</button>
            <button class="filter backlog" onclick="setFilter('backlog', this)">Backlog</button>

            <span class="total" id="totalCount">
                Total: <?php echo count($games ?? []); ?> games
            </span>

            <a href="./php/login.php" class="login-btn">
                Login / Register
            </a>

        </div>

        <div class="games-grid" id="gamesGrid">

            <?php foreach($games as $game) { ?>

                <div class="card"
                     data-status="<?php echo strtolower($game['status']); ?>"
                     data-title="<?php echo strtolower($game['title']); ?>">

                    <img src="<?php echo $game['image']; ?>" alt="<?php echo $game['title']; ?>">

                    <div class="card-content">

                        <span class="status <?php echo strtolower($game['status']); ?>">
                            <?php echo $game['status']; ?>
                        </span>

                        <h2><?php echo $game['title']; ?></h2>

                        <div class="rating">
                            ⭐⭐⭐⭐⭐
                            <span>(<?php echo $game['rating']; ?>)</span>
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

<div class="modal-overlay" id="addGameModal">
    <div class="modal">
        <div class="modal-header">
            <h2><i class="fa-solid fa-circle-plus"></i> Add Game</h2>
            <button class="modal-close" onclick="closeModal('addGameModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Feature not implemented yet. You can add this later.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('addGameModal')">Close</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="settingsModal">
    <div class="modal">
        <div class="modal-header">
            <h2><i class="fa-solid fa-gear"></i> Settings</h2>
            <button class="modal-close" onclick="closeModal('settingsModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Settings are not available yet. This is a placeholder modal.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('settingsModal')">Close</button>
        </div>
    </div>
</div>

<script src="js/script.js"></script>

</body>
</html>