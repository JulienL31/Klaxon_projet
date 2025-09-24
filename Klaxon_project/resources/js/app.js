import './bootstrap';
import '../sass/app.scss';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// ---- Trip details modal fill ----
(function () {
  const modalEl = document.getElementById('tripDetailsModal');
  if (!modalEl) return;

  const canSee = modalEl.dataset.canSee === '1';

  function maskName(v) {
    return v ? String(v).replace(/\S/g, 'x') : '—';
  }
  function maskPhone(v) {
    return v ? String(v).replace(/\d/g, 'X') : '—';
  }
  function maskEmail(v) {
    if (!v) return '—';
    const parts = String(v).split('@');
    const u = (parts[0] || '').replace(/\S/g, 'x') || 'xxxx';
    const d = (parts[1] || '').replace(/\S/g, 'x') || 'xxxx.xx';
    return `${u}@${d}`;
  }

  document.addEventListener(
    'click',
    (e) => {
      const btn = e.target.closest('.btn-trip-details');
      if (!btn) return;

      const author = btn.getAttribute('data-author') || '';
      const phone  = btn.getAttribute('data-phone')  || '';
      const email  = btn.getAttribute('data-email')  || '';
      const seats  = btn.getAttribute('data-seats')  || '';

      document.getElementById('tdAuthor').textContent = canSee ? (author || '—') : maskName(author);
      document.getElementById('tdPhone').textContent  = canSee ? (phone  || '—') : maskPhone(phone);
      document.getElementById('tdEmail').textContent  = canSee ? (email  || '—') : maskEmail(email);
      document.getElementById('tdSeats').textContent  = seats || '—';
    },
    true
  );
})();
