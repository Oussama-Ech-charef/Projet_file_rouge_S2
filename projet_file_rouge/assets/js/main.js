// assets/js/main.js — Search + Filter for Home Specialty Cards (no framework)
document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchInput');
  const filterSelect = document.getElementById('filterSelect');
  const cards = document.querySelectorAll('.card[data-name]');

  function applyFilters() {
    const q = (searchInput?.value || '').toLowerCase().trim();
    const filter = filterSelect?.value || 'all';
    cards.forEach(card => {
      const name = (card.dataset.name || '').toLowerCase();
      const desc = (card.dataset.desc || '').toLowerCase();
      const matchSearch = !q || name.includes(q) || desc.includes(q);
      let matchFilter = true;
      if (filter !== 'all') {
        // Filtre simple: par première lettre ou par nom exact — reste cohérent avec données approuvées
        // filter value = nom normalisé d'une spécialité
        matchFilter = name === filter.toLowerCase();
      }
      card.style.display = (matchSearch && matchFilter) ? '' : 'none';
    });
    // message vide
    const visible = Array.from(cards).some(c => c.style.display !== 'none');
    const empty = document.getElementById('noResults');
    if (empty) empty.style.display = visible ? 'none' : 'block';
  }

  searchInput?.addEventListener('input', applyFilters);
  filterSelect?.addEventListener('change', applyFilters);
});
