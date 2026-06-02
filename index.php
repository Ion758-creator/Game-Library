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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    .modal-overlay {
        display: none !important;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.75);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.open {
        display: flex !important;
    }
    </style>
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

<!-- ══════════════ MODAL ADD GAME ══════════════ -->
<div class="modal-overlay" id="addGameModal">
    <div class="modal">
        <div class="modal-header">
            <h2><i class="fa-solid fa-circle-plus"></i> Add New Game</h2>
            <button class="modal-close" onclick="closeModal('addGameModal')">
                &times;
            </button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>Game Title</label>
                <input type="text" id="gameTitle" placeholder="Enter game title..." class="form-input">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select id="gameStatus" class="form-input">
                    <option value="playing">🎮 Playing</option>
                    <option value="completed">✅ Completed</option>
                    <option value="backlog">🕐 Backlog</option>
                </select>
            </div>

            <div class="form-group">
                <label>Rating (0–10)</label>
                <input type="number" id="gameRating" placeholder="e.g. 9.5" min="0" max="10" step="0.1" class="form-input">
            </div>

            <div class="form-group">
                <label>Cover Image URL <span class="optional">(optional)</span></label>
                <input type="text" id="gameImage" placeholder="https://..." class="form-input">
                <p class="hint">Leave empty to use a default cover</p>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('addGameModal')">Cancel</button>
            <button class="btn-confirm" onclick="addGame()">
                <i class="fa-solid fa-plus"></i> Add Game
            </button>
        </div>
    </div>
</div>

<!-- ══════════════ MODAL SETTINGS ══════════════ -->
<div class="modal-overlay" id="settingsModal">
    <div class="modal">
        <div class="modal-header">
            <h2><i class="fa-solid fa-gear"></i> Settings</h2>
            <button class="modal-close" onclick="closeModal('settingsModal')">
                &times;
            </button>
        </div>

        <div class="modal-body">

            <div class="settings-section">
                <h3>Appearance</h3>

                <div class="setting-row">
                    <div class="setting-info">
                        <span class="setting-label">Accent Color</span>
                        <span class="setting-desc">Change the main theme color</span>
                    </div>
                    <div class="color-options">
                        <button class="color-dot active" style="background:#8b5cf6" data-color="#8b5cf6" onclick="setAccentColor('#8b5cf6', this)" title="Purple"></button>
                        <button class="color-dot" style="background:#3b82f6" data-color="#3b82f6" onclick="setAccentColor('#3b82f6', this)" title="Blue"></button>
                        <button class="color-dot" style="background:#10b981" data-color="#10b981" onclick="setAccentColor('#10b981', this)" title="Green"></button>
                        <button class="color-dot" style="background:#f59e0b" data-color="#f59e0b" onclick="setAccentColor('#f59e0b', this)" title="Amber"></button>
                        <button class="color-dot" style="background:#ef4444" data-color="#ef4444" onclick="setAccentColor('#ef4444', this)" title="Red"></button>
                        <button class="color-dot" style="background:#ec4899" data-color="#ec4899" onclick="setAccentColor('#ec4899', this)" title="Pink"></button>
                    </div>
                </div>

                <div class="setting-row">
                    <div class="setting-info">
                        <span class="setting-label">Card Layout</span>
                        <span class="setting-desc">Choose how games are displayed</span>
                    </div>
                    <div class="layout-options">
                        <button class="layout-btn active" id="gridLayoutBtn" onclick="setLayout('grid', this)" title="Grid">
                            <i class="fa-solid fa-table-cells"></i>
                        </button>
                        <button class="layout-btn" id="listLayoutBtn" onclick="setLayout('list', this)" title="List">
                            <i class="fa-solid fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="settings-section">
                <h3>Library</h3>

                <div class="setting-row">
                    <div class="setting-info">
                        <span class="setting-label">Sort Games By</span>
                        <span class="setting-desc">Default ordering of your collection</span>
                    </div>
                    <select id="sortOrder" class="form-input small" onchange="applySortOrder()">
                        <option value="default">Default</option>
                        <option value="title">Title (A–Z)</option>
                        <option value="rating">Rating (High–Low)</option>
                        <option value="status">Status</option>
                    </select>
                </div>
            </div>

            <div class="settings-section">
                <h3>About</h3>
                <div class="about-box">
                    <i class="fa-solid fa-gamepad" style="font-size:30px; color:var(--accent);"></i>
                    <div>
                        <p style="font-weight:bold;">Game Library v1.0</p>
                        <p style="color:#aaa; font-size:14px;">Made with ❤️ by Ion Șaptefrați</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('settingsModal')">Close</button>
            <button class="btn-confirm" onclick="saveSettings()">
                <i class="fa-solid fa-floppy-disk"></i> Save Settings
            </button>
        </div>
    </div>
</div>

<script src="js/script.js"></script>

</body>
</html>
