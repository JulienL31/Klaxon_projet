@extends('layouts.app')

@section('title','Créer un trajet')
@section('pagetitle','Créer un trajet')

@section('content')
{{-- Erreurs globales (liste courte) --}}
@if ($errors->any())
<div class="alert-klx mb-3">
  <strong>Veuillez corriger les erreurs ci-dessous.</strong>
</div>
@endif

<div class="row g-4">
  <div class="col-md-7">
    <form method="POST" action="{{ route('trips.store') }}" class="bg-white p-3 p-md-4 border rounded-3" novalidate>
      @csrf

      {{-- Si tu veux forcer l’auteur côté vue (sinon le controller le fait déjà) --}}
      @auth
      <input type="hidden" name="author_id" value="{{ auth()->id() }}">
      @endauth

      <div class="row g-3">
        {{-- Agences --}}
        <div class="col-md-6">
          <label for="agency_from_id" class="form-label">Agence de départ</label>
          <select id="agency_from_id" name="agency_from_id" class="form-select" required>
            <option value="">— Sélectionner —</option>
            @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(old('agency_from_id')==$a->id)>{{ $a->name }}</option>
            @endforeach
          </select>
          @error('agency_from_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="agency_to_id" class="form-label">Agence d’arrivée</label>
          <select id="agency_to_id" name="agency_to_id" class="form-select" required>
            <option value="">— Sélectionner —</option>
            @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(old('agency_to_id')==$a->id)>{{ $a->name }}</option>
            @endforeach
          </select>
          @error('agency_to_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        {{-- Datetimes (alignés avec le controller : departure_at / arrival_at) --}}
        <div class="col-md-6">
          <label for="departure_at" class="form-label">Départ (date & heure)</label>
          <input
            id="departure_at"
            type="datetime-local"
            name="departure_at"
            class="form-control"
            value="{{ old('departure_at') }}"
            required>
          @error('departure_at') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="arrival_at" class="form-label">Arrivée (date & heure)</label>
          <input
            id="arrival_at"
            type="datetime-local"
            name="arrival_at"
            class="form-control"
            value="{{ old('arrival_at') }}"
            required {{-- 👈 ajoute ce required --}}>
          @error('arrival_at') <div class="text-danger small">{{ $message }}</div> @enderror
          <div class="form-text">Doit être postérieure au départ.</div>
        </div>

        {{-- Places --}}
        <div class="col-md-6">
          <label for="seats_total" class="form-label">Nombre total de places</label>
          <input id="seats_total" type="number" min="1" step="1" name="seats_total" class="form-control"
            value="{{ old('seats_total', 1) }}" required>
          @error('seats_total') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="seats_free" class="form-label">Places disponibles</label>
          <input id="seats_free" type="number" min="0" step="1" name="seats_free" class="form-control"
            value="{{ old('seats_free', 1) }}" required>
          @error('seats_free') <div class="text-danger small">{{ $message }}</div> @enderror
          <div class="form-text">Ne peut pas dépasser le nombre total de places.</div>
        </div>

        {{-- Coordonnées de contact (optionnelles) --}}
        <div class="col-md-6">
          <label for="contact_name" class="form-label">Nom du contact</label>
          <input
            id="contact_name"
            type="text"
            name="contact_name"
            class="form-control"
            value="{{ old('contact_name', auth()->user()->name ?? '') }}"
            maxlength="255">
          @error('contact_name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="contact_email" class="form-label">Email de contact</label>
          <input
            id="contact_email"
            type="email"
            name="contact_email"
            class="form-control"
            value="{{ old('contact_email', auth()->user()->email ?? '') }}"
            maxlength="255">
          @error('contact_email') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
          <label for="contact_phone" class="form-label">Téléphone:</label>
          <input
            id="contact_phone"
            type="tel"
            name="contact_phone"
            class="form-control"
            placeholder="+33 6 12 34 56 78"
            value="{{ old('contact_phone', auth()->user()->phone ?? '') }}"
            maxlength="50">
          @error('contact_phone') <div class="text-danger small">{{ $message }}</div> @enderror
          <div class="form-text">
            Ce numéro sera associé au trajet pour permettre aux collègues de vous joindre.
          </div>
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>

  {{-- Panneau infos utilisateur (non modifiable) --}}
  <div class="col-md-5">
    <div class="bg-white p-3 p-md-4 border rounded-3">
      <h6 class="mb-3">Vos informations</h6>
      <p class="mb-1"><strong>Nom :</strong> {{ auth()->user()->name }}</p>
      <p class="mb-1"><strong>Email :</strong> {{ auth()->user()->email }}</p>
      <p class="mb-0"><strong>Téléphone :</strong> {{ auth()->user()->phone_pretty ?? (auth()->user()->phone ?? '—') }}</p>
      <small class="text-muted d-block mt-2">
        Ces informations seront associées au trajet pour faciliter le contact.
      </small>
    </div>
  </div>
</div>

{{-- JS léger : cohérence seats_free <= seats_total + arrivée pas avant départ --}}
<script>
  (function() {
    const total = document.getElementById('seats_total');
    const free = document.getElementById('seats_free');
    const dep = document.getElementById('departure_at');
    const arr = document.getElementById('arrival_at');

    function syncSeats() {
      const max = parseInt(total.value || '0', 10);
      if (Number.isFinite(max) && max > 0) {
        free.max = String(max);
        if (parseInt(free.value || '0', 10) > max) free.value = max;
      } else {
        free.removeAttribute('max');
      }
    }

    function syncArrivalMin() {
      if (dep.value) {
        arr.min = dep.value;
        if (arr.value && arr.value < dep.value) arr.value = dep.value;
      } else {
        arr.removeAttribute('min');
      }
    }

    total?.addEventListener('input', syncSeats);
    dep?.addEventListener('change', syncArrivalMin);

    // init
    syncSeats();
    syncArrivalMin();
  })();
</script>
@endsection