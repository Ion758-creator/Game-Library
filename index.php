<?php
include 'php/auth.php';
include 'php/library.php';

if (!isset($games)) $games = [];
$loggedIn = isLoggedIn();
$username = getUsername();
?>
<!DOCTYPE html>
<html lang="ro" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <span class="logo-icon">🎮</span>
            <div>
                <h2>MY LIBRARY</h2>
                <p>Game Collection</p>
            </div>
        </div>

        <ul class="menu">
            <li data-filter="all" class="active" onclick="setFilter('all',this)">🏠 <span data-lang-key="home">Acasă</span></li>
            <li data-filter="playing" onclick="setFilter('playing',this)">🎮 <span data-lang-key="playing">În joc</span></li>
            <li data-filter="completed" onclick="setFilter('completed',this)">✅ <span data-lang-key="completed">Finalizate</span></li>
            <li data-filter="backlog" onclick="setFilter('backlog',this)">🕐 <span data-lang-key="backlog">Lista de așteptare</span></li>
        </ul>

        <ul class="menu bottom">
            <?php if ($loggedIn): ?>
                <li onclick="window.location='profile.php'">👤 <?= htmlspecialchars($username) ?></li>
                <li onclick="openModal('addGameModal')">➕ <span data-lang-key="addGame">Adaugă joc</span></li>
                <li onclick="window.location='php/logout.php'">🚪 <span data-lang-key="logout">Deconectare</span></li>
            <?php else: ?>
                <li onclick="openModal('settingsModal')">⚙️ <span data-lang-key="settings">Setări</span></li>
            <?php endif; ?>
            <li onclick="window.location='php/contact.php'">✉️ <span data-lang-key="contact">Contact</span></li>
        </ul>

        <div class="sidebar-card">
            <div class="big-icon">🎮</div>
            <h3>Enjoy your games!</h3>
            <p>Keep track of your adventures 💜</p>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <!-- HERO — afișat doar când nu ești logat -->
        <?php if (!$loggedIn): ?>
        <div class="hero">
            <div class="hero-text">
                <h1>Bun venit la <span>Game Library</span> 🎮</h1>
                <p>Organizează-ți colecția de jocuri, urmărește progresul și descoperă noi titluri. Aplicația perfectă pentru orice pasionat de gaming.</p>
                <div class="hero-actions">
                    <a href="php/login.php" class="add-btn">🔑 Autentificare</a>
                    <a href="php/login.php" class="btn-outline">👤 Creează cont</a>
                </div>
            </div>
            <div class="hero-icon">🎮</div>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Urmărire jocuri</h3>
                <p>Marchează ce joci, ce ai terminat și ce vrei să joci</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⭐</div>
                <h3>Evaluare</h3>
                <p>Acordă note și lasă impresii pentru fiecare joc</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌙</div>
                <h3>Dark / Light mode</h3>
                <p>Alege tema preferată din setări</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3>Multilingv</h3>
                <p>Română, Engleză sau Rusă</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- HEADER -->
        <div class="header">
            <div class="title">
                <h1 data-lang-key="library">Biblioteca mea</h1>
                <p data-lang-key="subtitle">Toate jocurile tale într-un loc</p>
            </div>
            <div class="header-right">
                <div class="search-box">
                    <span>🔍</span>
                    <input type="text" id="searchInput" placeholder="Caută jocuri..." oninput="filterGames()">
                </div>
                <button class="add-btn" onclick="openModal('addGameModal')">
                    ➕ <span data-lang-key="addGame">Adaugă joc</span>
                </button>
                <button class="icon-btn" onclick="openModal('settingsModal')" title="Setări">⚙️</button>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="filters">
            <button class="filter active" onclick="setFilter('all',this)" data-lang-key="all">Toate</button>
            <button class="filter playing"   onclick="setFilter('playing',this)"   data-lang-key="playing">În joc</button>
            <button class="filter completed" onclick="setFilter('completed',this)" data-lang-key="completed">Finalizate</button>
            <button class="filter backlog"   onclick="setFilter('backlog',this)"   data-lang-key="backlog">Lista de așteptare</button>
            <span class="total" id="totalCount">Total: <?= count($games) ?> jocuri</span>
            <?php if ($loggedIn): ?>
                <a href="profile.php" class="login-btn">👤 <?= htmlspecialchars($username) ?></a>
            <?php else: ?>
                <a href="./php/login.php" class="login-btn" data-lang-key="loginRegister">Autentificare / Înregistrare</a>
            <?php endif; ?>
        </div>

        <!-- GAMES GRID -->
        <div class="games-grid" id="gamesGrid">
            <?php foreach($games as $game): ?>
            <div class="card"
                 data-status="<?= strtolower($game['status']) ?>"
                 data-title="<?= strtolower($game['title']) ?>"
                 data-id="<?= $game['id'] ?>">

                <?php if ($loggedIn): ?>
                <div class="card-actions">
                    <button class="card-btn edit-btn"   onclick="openEditModal(<?= $game['id'] ?>)" title="Edit">✏️</button>
                    <button class="card-btn delete-btn" onclick="deleteGame(<?= $game['id'] ?>, this)" title="Delete">🗑️</button>
                </div>
                <?php endif; ?>

                <img src="<?= htmlspecialchars($game['image']) ?>"
                     alt="<?= htmlspecialchars($game['title']) ?>"
                     onerror="this.src='https://placehold.co/400x250/111827/8b5cf6?text=No+Image'">
                <div class="card-content">
                    <span class="status <?= strtolower($game['status']) ?>"><?= htmlspecialchars($game['status']) ?></span>
                    <h2><?= htmlspecialchars($game['title']) ?></h2>
                    <div class="rating">⭐⭐⭐⭐⭐ <span>(<?= htmlspecialchars($game['rating']) ?>)</span></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <footer>Made with ❤️ by Ion Șaptefrați</footer>
    </main>
</div>

<!-- Mobile nav -->
<nav class="mobile-nav">
    <button class="mobile-nav-btn active" onclick="setFilter('all',this)">🏠<span>Acasă</span></button>
    <button class="mobile-nav-btn" onclick="setFilter('playing',this)">🎮<span>Joc</span></button>
    <button class="mobile-nav-btn" onclick="setFilter('completed',this)">✅<span>Gata</span></button>
    <button class="mobile-nav-btn" onclick="openModal('addGameModal')">➕<span>Adaugă</span></button>
    <button class="mobile-nav-btn" onclick="openModal('settingsModal')">⚙️<span>Setări</span></button>
</nav>

<!-- ADD GAME MODAL -->
<div class="modal-overlay" id="addGameModal">
    <div class="modal">
        <div class="modal-header">
            <h2>➕ <span data-lang-key="addGameTitle">Adaugă Joc</span></h2>
            <button class="modal-close" onclick="closeModal('addGameModal')">&times;</button>
        </div>
        <div class="modal-body">
            <?php if ($loggedIn): ?>
                <div class="form-group">
                    <label><span data-lang-key="gameTitle">Titlul jocului</span> <span style="color:#ef4444">*</span></label>
                    <input type="text" id="gameTitle" class="form-input" placeholder="ex: The Witcher 3">
                </div>
                <div class="form-group">
                    <label><span data-lang-key="imageUrl">URL imagine</span> <span class="optional">(opțional)</span></label>
                    <input type="text" id="gameImage" class="form-input" placeholder="https://...">
                    <p class="hint">Lasă gol pentru imagine implicită.</p>
                </div>
                <div class="form-group">
                    <label data-lang-key="status">Status</label>
                    <select id="gameStatus" class="form-input">
                        <option value="Playing">Playing</option>
                        <option value="Completed">Completed</option>
                        <option value="Backlog">Backlog</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><span data-lang-key="rating">Notă (0–10)</span> <span class="optional">(opțional)</span></label>
                    <input type="number" id="gameRating" class="form-input small" min="0" max="10" step="0.1" placeholder="8.5">
                </div>
                <div class="form-group">
                    <label><span data-lang-key="genre">Gen</span> <span class="optional">(opțional)</span></label>
                    <input type="text" id="gameGenre" class="form-input" placeholder="ex: RPG, Action, Horror">
                </div>
                <div class="form-group">
                    <label><span data-lang-key="notes">Note</span> <span class="optional">(opțional)</span></label>
                    <textarea id="gameNotes" class="form-input" rows="3" placeholder="Părerile tale despre joc..."></textarea>
                </div>
            <?php else: ?>
                <div style="text-align:center;padding:30px 0;">
                    <div style="font-size:48px;margin-bottom:16px;">🔒</div>
                    <p style="color:var(--text-muted);margin-bottom:12px;" data-lang-key="loginRequired">Trebuie să fii autentificat pentru a adăuga jocuri.</p>
                    <a href="php/login.php" style="color:var(--accent);font-weight:600;" data-lang-key="loginRegister">Autentificare →</a>
                </div>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('addGameModal')" data-lang-key="cancel">Anulează</button>
            <?php if ($loggedIn): ?>
            <button class="btn-confirm" onclick="submitAddGame()">
                ➕ <span data-lang-key="confirm">Adaugă joc</span>
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- EDIT GAME MODAL -->
<div class="modal-overlay" id="editGameModal">
    <div class="modal">
        <div class="modal-header">
            <h2>✏️ <span data-lang-key="editGame">Editează jocul</span></h2>
            <button class="modal-close" onclick="closeModal('editGameModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editGameId">
            <div class="form-group">
                <label data-lang-key="gameTitle">Titlul jocului</label>
                <input type="text" id="editGameTitle" class="form-input">
            </div>
            <div class="form-group">
                <label data-lang-key="status">Status</label>
                <select id="editGameStatus" class="form-input">
                    <option value="Playing">Playing</option>
                    <option value="Completed">Completed</option>
                    <option value="Backlog">Backlog</option>
                </select>
            </div>
            <div class="form-group">
                <label data-lang-key="imageUrl">URL imagine</label>
                <input type="text" id="editGameImage" class="form-input" placeholder="https://...">
            </div>
            <div class="form-group">
                <label data-lang-key="rating">Notă (0–10)</label>
                <input type="number" id="editGameRating" class="form-input small" min="0" max="10" step="0.1">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('editGameModal')" data-lang-key="cancel">Anulează</button>
            <button class="btn-confirm" onclick="submitEditGame()">💾 Salvează</button>
        </div>
    </div>
</div>

<!-- SETTINGS MODAL -->
<div class="modal-overlay" id="settingsModal">
    <div class="modal">
        <div class="modal-header">
            <h2>⚙️ <span data-lang-key="settings">Setări</span></h2>
            <button class="modal-close" onclick="closeModal('settingsModal')">&times;</button>
        </div>
        <div class="modal-body">

            <div class="settings-section">
                <h3 data-lang-key="appearance">Aspect</h3>

                <div class="setting-row">
                    <div class="setting-info">
                        <span class="setting-label" data-lang-key="accentColor">Culoare accent</span>
                        <span class="setting-desc">Alege culoarea principală</span>
                    </div>
                    <div class="color-options">
                        <div class="color-dot active" style="background:#8b5cf6" data-color="#8b5cf6" data-dark="#7c3aed" onclick="setAccentColor(this)" title="Purple"></div>
                        <div class="color-dot" style="background:#3b82f6" data-color="#3b82f6" data-dark="#2563eb" onclick="setAccentColor(this)" title="Blue"></div>
                        <div class="color-dot" style="background:#10b981" data-color="#10b981" data-dark="#059669" onclick="setAccentColor(this)" title="Green"></div>
                        <div class="color-dot" style="background:#f59e0b" data-color="#f59e0b" data-dark="#d97706" onclick="setAccentColor(this)" title="Amber"></div>
                        <div class="color-dot" style="background:#ef4444" data-color="#ef4444" data-dark="#dc2626" onclick="setAccentColor(this)" title="Red"></div>
                    </div>
                </div>

                <div class="setting-row">
                    <div class="setting-info">
                        <span class="setting-label" data-lang-key="layout">Aspect grid</span>
                        <span class="setting-desc">Grid sau listă</span>
                    </div>
                    <div class="layout-options">
                        <button class="layout-btn active" id="btnGrid" onclick="setLayout('grid')" title="Grid">▦</button>
                        <button class="layout-btn" id="btnList" onclick="setLayout('list')" title="List">☰</button>
                    </div>
                </div>

                <div class="setting-row">
                    <div class="setting-info">
                        <span class="setting-label" data-lang-key="darkMode">Mod întunecat</span>
                        <span class="setting-desc">Dark / Light mode</span>
                    </div>
                    <div class="theme-toggle" onclick="toggleTheme()" title="Toggle theme"></div>
                </div>

                <div class="setting-row">
                    <div class="setting-info">
                        <span class="setting-label" data-lang-key="language">Limbă</span>
                        <span class="setting-desc">Română / English / Русский</span>
                    </div>
                    <select class="lang-select" id="langSelect" onchange="setLang(this.value)">
                        <option value="ro">🇷🇴 Română</option>
                        <option value="en">🇬🇧 English</option>
                        <option value="ru">🇷🇺 Русский</option>
                    </select>
                </div>
            </div>

            <div class="settings-section">
                <h3>Date</h3>
                <div class="setting-row">
                    <div class="setting-info">
                        <span class="setting-label" data-lang-key="resetSettings">Resetează setările</span>
                        <span class="setting-desc">Restabilește valorile implicite</span>
                    </div>
                    <button class="btn-reset" onclick="resetSettings()">↺ Reset</button>
                </div>
            </div>

            <div class="settings-section">
                <h3 data-lang-key="about">Despre</h3>
                <div class="about-box">
                    <span style="font-size:32px;">🎮</span>
                    <div>
                        <p style="font-weight:600;">Game Library v2.0</p>
                        <p style="color:var(--text-muted);font-size:13px;margin-top:4px;">Made with ❤️ by Ion Șaptefrați</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('settingsModal')">Închide</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>
<script src="js/script.js"></script>
</body>
</html>
