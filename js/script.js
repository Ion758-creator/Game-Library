// ── ACCENT COLOR ───────────────────────────────────
function applyAccentColor(color) {
    document.documentElement.style.setProperty('--accent', color);
    document.documentElement.style.setProperty('--accent-dark', shadeColor(color, -15));
}

function shadeColor(color, percent) {
    const num = parseInt(color.replace('#',''), 16);
    const r = Math.min(255, Math.max(0, (num >> 16) + percent * 2.55 | 0));
    const g = Math.min(255, Math.max(0, ((num >> 8) & 0x00FF) + percent * 2.55 | 0));
    const b = Math.min(255, Math.max(0, (num & 0x0000FF) + percent * 2.55 | 0));
    return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}

// ── MODAL ──────────────────────────────────────────
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}

// Închide la click pe overlay
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

// Închide cu ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach(m => closeModal(m.id));
    }
});

// ── ADD GAME ───────────────────────────────────────
function addGame() {
    const title = document.getElementById('gameTitle').value.trim();
    const status = document.getElementById('gameStatus').value;
    const rating = document.getElementById('gameRating').value.trim();
    const image = document.getElementById('gameImage').value.trim();

    if (!title) {
        showToast('Please enter a game title!', 'error');
        document.getElementById('gameTitle').focus();
        return;
    }

    const ratingVal = rating ? parseFloat(rating) : '—';

    const defaultCover = 'https://via.placeholder.com/400x250/111827/8b5cf6?text=' + encodeURIComponent(title);
    const imageSrc = image || defaultCover;

    const statusLabels = { playing: 'Playing', completed: 'Completed', backlog: 'Backlog' };
    const stars = ratingVal !== '—' ? getStars(ratingVal) : '⭐';

    const card = document.createElement('div');
    card.className = 'card';
    card.dataset.status = status;
    card.dataset.title = title.toLowerCase();
    card.innerHTML = `
        <img src="${imageSrc}" alt="${title}" onerror="this.src='https://via.placeholder.com/400x250/111827/8b5cf6?text=${encodeURIComponent(title)}'">
        <div class="card-content">
            <span class="status ${status}">${statusLabels[status]}</span>
            <h2>${title}</h2>
            <div class="rating">
                ${stars}
                <span>(${ratingVal})</span>
            </div>
        </div>
    `;

    document.getElementById('gamesGrid').prepend(card);

    // Reset form
    document.getElementById('gameTitle').value = '';
    document.getElementById('gameRating').value = '';
    document.getElementById('gameImage').value = '';
    document.getElementById('gameStatus').value = 'playing';

    closeModal('addGameModal');
    filterGames();
    showToast('Game added successfully! 🎮');
}

function getStars(rating) {
    const full = Math.round(rating / 2);
    return '⭐'.repeat(Math.min(full, 5));
}

// ── SETTINGS ──────────────────────────────────────
function setAccentColor(color, btn) {
    document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
    btn.classList.add('active');
    applyAccentColor(color);
    localStorage.setItem('accentColor', color);
}

function setLayout(type, btn) {
    const grid = document.getElementById('gamesGrid');
    document.querySelectorAll('.layout-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (type === 'list') {
        grid.classList.add('list-layout');
    } else {
        grid.classList.remove('list-layout');
    }
    localStorage.setItem('layout', type);
}

function applySortOrder() {
    const order = document.getElementById('sortOrder').value;
    const grid = document.getElementById('gamesGrid');
    const cards = [...grid.querySelectorAll('.card')];

    if (order === 'title') {
        cards.sort((a, b) => a.dataset.title.localeCompare(b.dataset.title));
    } else if (order === 'rating') {
        cards.sort((a, b) => {
            const ra = parseFloat(a.querySelector('.rating span')?.textContent.replace(/[()]/g,'')) || 0;
            const rb = parseFloat(b.querySelector('.rating span')?.textContent.replace(/[()]/g,'')) || 0;
            return rb - ra;
        });
    } else if (order === 'status') {
        const order2 = { playing: 0, completed: 1, backlog: 2 };
        cards.sort((a, b) => (order2[a.dataset.status] || 99) - (order2[b.dataset.status] || 99));
    }

    cards.forEach(c => grid.appendChild(c));
    localStorage.setItem('sortOrder', order);
}

function saveSettings() {
    localStorage.setItem('sortOrder', document.getElementById('sortOrder').value);
    applySortOrder();
    closeModal('settingsModal');
    showToast('Settings saved! ⚙️');
}

// ── TOAST NOTIFICATION ─────────────────────────────
function showToast(message, type = 'success') {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ── FILTRARE STATUS ────────────────────────────────
let currentFilter = 'all';

function setFilter(status, btn) {
    currentFilter = status;

    document.querySelectorAll('.filter').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.menu li').forEach(li => li.classList.remove('active'));

    if (btn) btn.classList.add('active');

    filterGames();
}

// ── CĂUTARE + FILTRARE COMBINATĂ ────────────────────
function filterGames() {
    const searchInput = document.getElementById('searchInput');
    const query = searchInput ? searchInput.value.toLowerCase() : '';
    const cards = document.querySelectorAll('.games-grid .card');
    let visible = 0;

    cards.forEach(card => {
        const matchStatus = currentFilter === 'all' || card.dataset.status === currentFilter;
        const matchSearch = card.dataset.title.includes(query);
        const show = matchStatus && matchSearch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    const totalEl = document.getElementById('totalCount');
    if (totalEl) {
        totalEl.textContent = 'Total: ' + visible + ' games';
    }
}

// ── LOAD SAVED SETTINGS ───────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    // Restore accent color
    const savedColor = localStorage.getItem('accentColor') || '#8b5cf6';
    applyAccentColor(savedColor);
    const colorBtn = document.querySelector(`.color-dot[data-color="${savedColor}"]`);
    if (colorBtn) {
        document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
        colorBtn.classList.add('active');
    }

    // Restore layout
    const savedLayout = localStorage.getItem('layout') || 'grid';
    if (savedLayout === 'list') {
        document.getElementById('gamesGrid').classList.add('list-layout');
        document.getElementById('listLayoutBtn')?.classList.add('active');
        document.getElementById('gridLayoutBtn')?.classList.remove('active');
    }

    // Restore sort order
    const savedSort = localStorage.getItem('sortOrder') || 'default';
    const sortEl = document.getElementById('sortOrder');
    if (sortEl) sortEl.value = savedSort;

    filterGames();
});
