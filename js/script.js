// ═══════════════════════════════════════════════════════
//  Game Library — main script
// ═══════════════════════════════════════════════════════

const STORAGE_KEY = "gamelib_settings";

// ─── Translations ───────────────────────────────────────
const TRANSLATIONS = {
    ro: {
        library: "Biblioteca mea",
        subtitle: "Toate jocurile tale într-un loc",
        search: "Caută jocuri...",
        addGame: "Adaugă joc",
        all: "Toate",
        playing: "În joc",
        completed: "Finalizate",
        backlog: "Lista de așteptare",
        total: "Total",
        games: "jocuri",
        logout: "Deconectare",
        settings: "Setări",
        addGameTitle: "Adaugă Joc",
        gameTitle: "Titlul jocului",
        imageUrl: "URL imagine",
        status: "Status",
        rating: "Notă (0–10)",
        genre: "Gen",
        notes: "Note",
        cancel: "Anulează",
        confirm: "Adaugă joc",
        appearance: "Aspect",
        accentColor: "Culoare accent",
        layout: "Aspect grid",
        darkMode: "Mod întunecat",
        language: "Limbă",
        resetSettings: "Resetează setările",
        about: "Despre",
        toastAdded: "adăugat în biblioteca ta! 🎮",
        toastNoTitle: "Te rog introdu titlul jocului.",
        toastBadRating: "Nota trebuie să fie între 0 și 10.",
        toastColorUpdated: "Culoare accent actualizată!",
        toastLayoutUpdated: "Aspect actualizat!",
        toastReset: "Setările au fost resetate! ✅",
        loginRequired: "Trebuie să fii autentificat pentru a adăuga jocuri.",
        loginRegister: "Autentificare →",
        editGame: "Editează jocul",
        deleteConfirm: "Ești sigur că vrei să ștergi acest joc?",
        toastDeleted: "Joc șters!",
        toastUpdated: "Joc actualizat!",
        contact: "Contact",
        profile: "Profil",
        home: "Acasă",
    },
    en: {
        library: "My Library",
        subtitle: "All your games in one place",
        search: "Search games...",
        addGame: "Add Game",
        all: "All",
        playing: "Playing",
        completed: "Completed",
        backlog: "Backlog",
        total: "Total",
        games: "games",
        logout: "Logout",
        settings: "Settings",
        addGameTitle: "Add Game",
        gameTitle: "Game Title",
        imageUrl: "Image URL",
        status: "Status",
        rating: "Rating (0–10)",
        genre: "Genre",
        notes: "Notes",
        cancel: "Cancel",
        confirm: "Add Game",
        appearance: "Appearance",
        accentColor: "Accent Color",
        layout: "Grid Layout",
        darkMode: "Dark Mode",
        language: "Language",
        resetSettings: "Reset Settings",
        about: "About",
        toastAdded: "added to your library! 🎮",
        toastNoTitle: "Please enter a game title.",
        toastBadRating: "Rating must be between 0 and 10.",
        toastColorUpdated: "Accent color updated!",
        toastLayoutUpdated: "Layout updated!",
        toastReset: "Settings reset to default! ✅",
        loginRequired: "You need to be logged in to add games.",
        loginRegister: "Login / Register →",
        editGame: "Edit Game",
        deleteConfirm: "Are you sure you want to delete this game?",
        toastDeleted: "Game deleted!",
        toastUpdated: "Game updated!",
        contact: "Contact",
        profile: "Profile",
        home: "Home",
    },
    ru: {
        library: "Моя библиотека",
        subtitle: "Все ваши игры в одном месте",
        search: "Поиск игр...",
        addGame: "Добавить игру",
        all: "Все",
        playing: "Играю",
        completed: "Завершены",
        backlog: "Список желаний",
        total: "Всего",
        games: "игр",
        logout: "Выйти",
        settings: "Настройки",
        addGameTitle: "Добавить игру",
        gameTitle: "Название игры",
        imageUrl: "URL изображения",
        status: "Статус",
        rating: "Оценка (0–10)",
        genre: "Жанр",
        notes: "Заметки",
        cancel: "Отмена",
        confirm: "Добавить",
        appearance: "Внешний вид",
        accentColor: "Акцентный цвет",
        layout: "Вид сетки",
        darkMode: "Тёмный режим",
        language: "Язык",
        resetSettings: "Сбросить настройки",
        about: "О приложении",
        toastAdded: "добавлена в библиотеку! 🎮",
        toastNoTitle: "Пожалуйста, введите название игры.",
        toastBadRating: "Оценка должна быть от 0 до 10.",
        toastColorUpdated: "Цвет акцента обновлён!",
        toastLayoutUpdated: "Вид обновлён!",
        toastReset: "Настройки сброшены! ✅",
        loginRequired: "Войдите в систему, чтобы добавить игры.",
        loginRegister: "Войти / Зарегистрироваться →",
        editGame: "Редактировать игру",
        deleteConfirm: "Вы уверены, что хотите удалить эту игру?",
        toastDeleted: "Игра удалена!",
        toastUpdated: "Игра обновлена!",
        contact: "Контакт",
        profile: "Профиль",
        home: "Главная",
    }
};

// ─── State ──────────────────────────────────────────────
let currentFilter = "all";
let currentLang   = "ro";

// ─── DOM refs ───────────────────────────────────────────
const gamesGrid   = document.getElementById("gamesGrid");
const searchInput = document.getElementById("searchInput");
const totalCount  = document.getElementById("totalCount");
const filterBtns  = Array.from(document.querySelectorAll(".filter"));
const sidebarItems= Array.from(document.querySelectorAll(".sidebar .menu li[data-filter]"));

// ─── Filter / Search ────────────────────────────────────
function setFilter(status, el) {
    currentFilter = status;
    filterBtns.forEach(b => {
        const s = b.getAttribute("onclick")?.match(/setFilter\('(.+?)'/)?.[1];
        b.classList.toggle("active", s === status);
    });
    sidebarItems.forEach(li => li.classList.toggle("active", li.dataset.filter === status));
    updateGameList();
}

function filterGames() { updateGameList(); }

function updateGameList() {
    const query = searchInput?.value.trim().toLowerCase() || "";
    let visible = 0;
    document.querySelectorAll(".card").forEach(card => {
        const s = card.dataset.status;
        const t = card.dataset.title;
        const show = (currentFilter === "all" || s === currentFilter) && (!query || t.includes(query));
        card.style.display = show ? "" : "none";
        if (show) visible++;
    });
    if (totalCount) {
        const t = getT();
        totalCount.textContent = `${t.total}: ${visible} ${t.games}`;
    }
}

// ─── Modals ─────────────────────────────────────────────
function openModal(id)  { document.getElementById(id)?.classList.add("open"); }
function closeModal(id) { document.getElementById(id)?.classList.remove("open"); }

document.addEventListener("click", e => {
    if (e.target.classList.contains("modal-overlay")) closeModal(e.target.id);
});

// ─── Add Game ───────────────────────────────────────────
function submitAddGame() {
    const t = getT();
    const title  = document.getElementById("gameTitle")?.value.trim();
    const image  = document.getElementById("gameImage")?.value.trim();
    const status = document.getElementById("gameStatus")?.value;
    const rating = document.getElementById("gameRating")?.value.trim();
    const genre  = document.getElementById("gameGenre")?.value.trim();
    const notes  = document.getElementById("gameNotes")?.value.trim();

    if (!title) { showToast(t.toastNoTitle, "error"); document.getElementById("gameTitle").focus(); return; }
    const ratingNum = rating ? parseFloat(rating) : null;
    if (ratingNum !== null && (isNaN(ratingNum) || ratingNum < 0 || ratingNum > 10)) {
        showToast(t.toastBadRating, "error"); return;
    }

    const newGame = {
        id: Date.now(), title,
        image: image || "images/default.jpg",
        status, rating: ratingNum !== null ? ratingNum.toFixed(1) : "—",
        genre: genre || "—", notes: notes || "", addedBy: "user"
    };

    addCardToGrid(newGame);

    fetch("php/add_game.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(newGame)
    }).then(r => r.json()).then(data => {
        if (data.success) showToast(`"${title}" ${t.toastAdded}`);
        else showToast(data.error || "Error", "error");
    }).catch(() => showToast(`"${title}" ${t.toastAdded}`));

    ["gameTitle","gameImage","gameRating","gameGenre","gameNotes"].forEach(id => {
        const el = document.getElementById(id); if (el) el.value = "";
    });
    const sel = document.getElementById("gameStatus"); if (sel) sel.value = "Playing";
    closeModal("addGameModal");
}

function addCardToGrid(game) {
    const sl = game.status.toLowerCase();
    const card = document.createElement("div");
    card.className = "card";
    card.dataset.status = sl;
    card.dataset.title  = game.title.toLowerCase();
    card.dataset.id     = game.id;
    card.innerHTML = `
        <div class="card-actions">
            <button class="card-btn edit-btn"   onclick="openEditModal(${game.id})" title="Edit"><i class="fa-solid fa-pen"></i></button>
            <button class="card-btn delete-btn" onclick="deleteGame(${game.id}, this)" title="Delete"><i class="fa-solid fa-trash"></i></button>
        </div>
        <img src="${escH(game.image)}" alt="${escH(game.title)}"
             onerror="this.src='https://placehold.co/400x250/111827/8b5cf6?text=No+Image'">
        <div class="card-content">
            <span class="status ${sl}">${escH(game.status)}</span>
            <h2>${escH(game.title)}</h2>
            <div class="rating">⭐⭐⭐⭐⭐ <span>(${escH(String(game.rating))})</span></div>
        </div>`;
    gamesGrid?.appendChild(card);
    updateGameList();
}

// ─── Delete Game ────────────────────────────────────────
function deleteGame(id, btn) {
    const t = getT();
    if (!confirm(t.deleteConfirm)) return;
    const card = btn.closest(".card");

    fetch("php/delete_game.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            card?.remove();
            updateGameList();
            showToast(t.toastDeleted);
        }
    }).catch(() => { card?.remove(); updateGameList(); showToast(t.toastDeleted); });
}

// ─── Edit Game ──────────────────────────────────────────
function openEditModal(id) {
    const card = document.querySelector(`.card[data-id="${id}"]`);
    if (!card) return;
    const titleEl = card.querySelector("h2");
    const statusEl = card.querySelector(".status");
    const ratingEl = card.querySelector(".rating span");
    const imgEl   = card.querySelector("img");

    document.getElementById("editGameId").value     = id;
    document.getElementById("editGameTitle").value  = titleEl?.textContent || "";
    document.getElementById("editGameStatus").value = statusEl?.className.replace("status ","").trim() || "Playing";
    document.getElementById("editGameImage").value  = imgEl?.src || "";
    const rat = ratingEl?.textContent.replace(/[()]/g,"").trim();
    document.getElementById("editGameRating").value = rat === "—" ? "" : rat;

    openModal("editGameModal");
}

function submitEditGame() {
    const t = getT();
    const id     = parseInt(document.getElementById("editGameId").value);
    const title  = document.getElementById("editGameTitle").value.trim();
    const status = document.getElementById("editGameStatus").value;
    const image  = document.getElementById("editGameImage").value.trim();
    const rating = document.getElementById("editGameRating").value.trim();

    if (!title) { showToast(t.toastNoTitle, "error"); return; }
    const ratingNum = rating ? parseFloat(rating) : null;
    if (ratingNum !== null && (isNaN(ratingNum) || ratingNum < 0 || ratingNum > 10)) {
        showToast(t.toastBadRating, "error"); return;
    }

    const updates = { id, title, status,
        image: image || "images/default.jpg",
        rating: ratingNum !== null ? ratingNum.toFixed(1) : "—"
    };

    // Update DOM
    const card = document.querySelector(`.card[data-id="${id}"]`);
    if (card) {
        const sl = status.toLowerCase();
        card.dataset.status = sl;
        card.dataset.title  = title.toLowerCase();
        card.querySelector("h2").textContent = title;
        const statusEl = card.querySelector(".status");
        statusEl.className = `status ${sl}`;
        statusEl.textContent = status;
        card.querySelector(".rating span").textContent = `(${updates.rating})`;
        if (image) card.querySelector("img").src = image;
    }

    fetch("php/edit_game.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(updates)
    }).then(r => r.json()).then(data => {
        showToast(data.success ? t.toastUpdated : (data.error || "Error"), data.success ? "success" : "error");
    }).catch(() => showToast(t.toastUpdated));

    closeModal("editGameModal");
    updateGameList();
}

// ─── Settings ───────────────────────────────────────────
function loadSettings() {
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}"); } catch { return {}; }
}
function saveSettings(obj) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify({ ...loadSettings(), ...obj }));
}

function applySettings() {
    const s = loadSettings();
    if (s.accentColor) {
        document.documentElement.style.setProperty("--accent", s.accentColor);
        document.documentElement.style.setProperty("--accent-dark", s.accentDark || s.accentColor);
        document.querySelectorAll(".color-dot").forEach(d => d.classList.toggle("active", d.dataset.color === s.accentColor));
    }
    if (s.layout) applyLayout(s.layout, false);
    if (s.theme)  applyTheme(s.theme, false);
    if (s.lang)   applyLang(s.lang, false);
}

function setAccentColor(dotEl) {
    const color = dotEl.dataset.color, dark = dotEl.dataset.dark || color;
    document.documentElement.style.setProperty("--accent", color);
    document.documentElement.style.setProperty("--accent-dark", dark);
    document.querySelectorAll(".color-dot").forEach(d => d.classList.remove("active"));
    dotEl.classList.add("active");
    saveSettings({ accentColor: color, accentDark: dark });
    showToast(getT().toastColorUpdated);
}

function setLayout(layout) { applyLayout(layout, true); }
function applyLayout(layout, persist) {
    if (layout === "list") {
        gamesGrid?.classList.add("list-layout");
        document.getElementById("btnGrid")?.classList.remove("active");
        document.getElementById("btnList")?.classList.add("active");
    } else {
        gamesGrid?.classList.remove("list-layout");
        document.getElementById("btnGrid")?.classList.add("active");
        document.getElementById("btnList")?.classList.remove("active");
    }
    if (persist) { saveSettings({ layout }); showToast(getT().toastLayoutUpdated); }
}

function toggleTheme() {
    const cur = document.documentElement.getAttribute("data-theme") || "dark";
    applyTheme(cur === "dark" ? "light" : "dark", true);
}
function applyTheme(theme, persist) {
    document.documentElement.setAttribute("data-theme", theme);
    if (persist) saveSettings({ theme });
}

function setLang(val) { applyLang(val, true); }
function applyLang(lang, persist) {
    if (!TRANSLATIONS[lang]) return;
    currentLang = lang;
    if (persist) saveSettings({ lang });
    const t = TRANSLATIONS[lang];
    // Update UI text
    document.querySelectorAll("[data-lang-key]").forEach(el => {
        const key = el.dataset.langKey;
        if (t[key]) el.textContent = t[key];
    });
    if (searchInput) searchInput.placeholder = t.search;
    const langSel = document.getElementById("langSelect");
    if (langSel) langSel.value = lang;
}

function getT() { return TRANSLATIONS[currentLang] || TRANSLATIONS.ro; }

function resetSettings() {
    if (!confirm("Reset all settings?")) return;
    localStorage.removeItem(STORAGE_KEY);
    document.documentElement.removeAttribute("data-theme");
    document.documentElement.style.removeProperty("--accent");
    document.documentElement.style.removeProperty("--accent-dark");
    gamesGrid?.classList.remove("list-layout");
    document.getElementById("btnGrid")?.classList.add("active");
    document.getElementById("btnList")?.classList.remove("active");
    document.querySelectorAll(".color-dot").forEach((d, i) => d.classList.toggle("active", i === 0));
    currentLang = "ro";
    applyLang("ro", false);
    showToast(getT().toastReset);
}

// ─── Toast ──────────────────────────────────────────────
function showToast(msg, type = "success") {
    const toast = document.getElementById("toast");
    if (!toast) return;
    toast.textContent = msg;
    toast.className = `toast${type === "error" ? " error" : ""} show`;
    setTimeout(() => toast.classList.remove("show"), 3000);
}

// ─── Helpers ────────────────────────────────────────────
function escH(str) {
    const d = document.createElement("div");
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

// ─── Add card actions to existing cards ─────────────────
function addActionsToCards() {
    document.querySelectorAll(".card").forEach(card => {
        const id = card.dataset.id;
        if (!id || card.querySelector(".card-actions")) return;
        const actions = document.createElement("div");
        actions.className = "card-actions";
        actions.innerHTML = `
            <button class="card-btn edit-btn"   onclick="openEditModal(${id})" title="Edit"><i class="fa-solid fa-pen"></i></button>
            <button class="card-btn delete-btn" onclick="deleteGame(${id}, this)" title="Delete"><i class="fa-solid fa-trash"></i></button>`;
        card.prepend(actions);
    });
}

// ─── Init ───────────────────────────────────────────────
applySettings();
addActionsToCards();
if (document.querySelectorAll(".card").length > 0) updateGameList();
