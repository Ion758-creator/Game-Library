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

// ── FILTRARE STATUS ────────────────────────────────
let currentFilter = 'all';

function setFilter(status, btn) {
    currentFilter = status;
    document.querySelectorAll('.filter').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterGames();
}

// ── CĂUTARE ────────────────────────────────────────
function filterGames() {
    const query  = document.getElementById('searchInput').value.toLowerCase();
    const cards  = document.querySelectorAll('.games-grid .card');

    cards.forEach(card => {
        const matchStatus = currentFilter === 'all' || card.dataset.status === currentFilter;
        const matchSearch = card.dataset.title.includes(query);
        card.style.display = (matchStatus && matchSearch) ? '' : 'none';
    });
}
