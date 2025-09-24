@php
  // Peut-on afficher les coordonnées en clair ?
  $canSee = auth()->check();
@endphp

<div class="modal fade"
     id="tripDetailsModal"
     data-can-see="{{ $canSee ? '1' : '0' }}"
     tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails du trajet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <div class="modal-body">
        <p class="mb-2"><strong>Auteur :</strong> <span id="tdAuthor">—</span></p>
        <p class="mb-2"><strong>Téléphone :</strong> <span id="tdPhone">—</span></p>
        <p class="mb-2"><strong>Email :</strong> <span id="tdEmail">—</span></p>
        <p class="mb-0"><strong>Nombre total de places :</strong> <span id="tdSeats">—</span></p>

        @unless($canSee)
          <div class="alert alert-info mt-3 mb-0">
            Connectez-vous pour voir les coordonnées complètes.
          </div>
        @endunless
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

{{-- Script de remplissage de la modale (vanilla JS + Bootstrap) --}}
<script>
(function () {
  const modalEl = document.getElementById('tripDetailsModal');
  const canSee = modalEl?.dataset?.canSee === '1';

  // Petites fonctions de masquage pour les invités
  function maskName(v){ if(!v) return '—'; return v.replace(/\S/g,'x'); }
  function maskPhone(v){ if(!v) return '—'; return v.replace(/\d/g,'X'); }
  function maskEmail(v){
    if(!v) return '—';
    const parts = String(v).split('@');
    const u = parts[0] ?? '', d = parts[1] ?? '';
    const mask = s => s ? s.replace(/\S/g,'x') : '';
    return (mask(u) || 'xxxx') + '@' + (mask(d) || 'xxxx.xx');
  }

  document.addEventListener('click', function(e){
    const btn = e.target.closest('.btn-trip-details');
    if(!btn) return;

    const author = btn.getAttribute('data-author') || '';
    const phone  = btn.getAttribute('data-phone')  || '';
    const email  = btn.getAttribute('data-email')  || '';
    const seats  = btn.getAttribute('data-seats')  || '';

    document.getElementById('tdAuthor').textContent = canSee ? (author || '—') : maskName(author);
    document.getElementById('tdPhone').textContent  = canSee ? (phone  || '—') : maskPhone(phone);
    document.getElementById('tdEmail').textContent  = canSee ? (email  || '—') : maskEmail(email);
    document.getElementById('tdSeats').textContent  = seats || '—';
  }, {capture: true});
})();
</script>
