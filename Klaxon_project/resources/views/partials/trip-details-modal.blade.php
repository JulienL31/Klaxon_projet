@php($canSee = auth()->check())

<div class="modal fade"
     id="tripDetailsModal"
     tabindex="-1"
     aria-hidden="true"
     data-can-see="{{ $canSee ? 1 : 0 }}">
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
