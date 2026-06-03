const gamesGrid = document.getElementById("gamesGrid");
const searchInput = document.getElementById("searchInput");
const totalCount = document.getElementById("totalCount");
const filterButtons = Array.from(document.querySelectorAll(".filter"));
const sidebarItems = Array.from(document.querySelectorAll(".sidebar .menu li"));
const cards = Array.from(document.querySelectorAll(".card"));

let currentFilter = "all";

function setFilter(status, element) {
    currentFilter = status;

    filterButtons.forEach((btn) => {
        const btnStatus = btn.getAttribute("onclick")?.match(/setFilter\('(.+?)'/)?.[1];
        btn.classList.toggle("active", btnStatus === status);
    });

    sidebarItems.forEach((item) => {
        const itemStatus = item.getAttribute("onclick")?.match(/setFilter\('(.+?)'/)?.[1];
        item.classList.toggle("active", itemStatus === status);
    });

    updateGameList();
}

function filterGames() {
    updateGameList();
}

function updateGameList() {
    const query = searchInput?.value.trim().toLowerCase() || "";
    let visibleCount = 0;

    cards.forEach((card) => {
        const status = card.dataset.status;
        const title = card.dataset.title;
        const matchesStatus = currentFilter === "all" || status === currentFilter;
        const matchesText = query === "" || title.includes(query);
        const visible = matchesStatus && matchesText;

        card.style.display = visible ? "" : "none";
        if (visible) visibleCount += 1;
    });

    if (totalCount) {
        totalCount.textContent = `Total: ${visibleCount} games`;
    }
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.warn(`Modal not found: ${modalId}`);
        return;
    }
    modal.classList.add("open");
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.remove("open");
}

// Initialize page with default filter state
if (cards.length > 0) {
    updateGameList();
}
