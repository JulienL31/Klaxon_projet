@extends('layouts.app')

@section('title','Modifier un trajet')
@section('pagetitle','Modifier un trajet')

@section('content')
  @if ($errors->any())
    <div class="alert-klx mb-3">
      <strong>Veuillez corriger les erreurs ci-dessous.</strong>
    </div>
  @endif

  @php
    // On part de la BDD (departure_at / arrival_at), avec filet de sécurité
    $dep   = $trip->departure_at;
    $arr   = $trip->arrival_at;

    $depDate = old('departure_date', $dep?->format('Y-m-d'));
    $depTime = old('departure_time', $dep?->format('H:i'));
    $arrDate = old('arrival_date',   $arr?->format('Y-m-d'));
    $arrTime = old('arrival_time',   $arr?->format('H:i'));
  @endphp

  <div class="row g-4">
    <div class="col-md-7">
      <form method="POST" action="{{ route('trips.update', $trip) }}" class="bg-white p-3 p-md-4 border rounded-3">
        @csrf
        @method('PUT')

        <div class="row g-3">
          {{-- Agences --}}
          <div class="col-md-6">
            <label for="agency_from_id" class="form-label">Agence de départ</label>
            <select id="agency_from_id" name="agency_from_id" class="form-select" required>
              <option value="">— Sélectionner —</option>
              @foreach($agencies as $a)
                <option value="{{ $a->id }}"
                  @selected(old('agency_from_id', $trip->agency_from_id) == $a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
            @error('agency_from_id') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="agency_to_id" class="form-label">Agence d’arrivée</label>
            <select id="agency_to_id" name="agency_to_id" class="form-select" required>
              <option value="">— Sélectionner —</option>
              @foreach($agencies as $a)
                <option value="{{ $a->id }}"
                  @selected(old('agency_to_id', $trip->agency_to_id) == $a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
            @error('agency_to_id') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          {{-- Dates / heures --}}
          <div class="col-md-6">
            <label for="departure_date" class="form-label">Date de départ</label>
            <input id="departure_date" type="date" name="departure_date" class="form-control"
                   value="{{ $depDate }}" required>
            @error('departure_date') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="departure_time" class="form-label">Heure de départ</label>
            <input id="departure_time" type="time" name="departure_time" class="form-control"
                   value="{{ $depTime }}" required>
            @error('departure_time') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="arrival_date" class="form-label">Date d’arrivée</label>
            <input id="arrival_date" type="date" name="arrival_date" class="form-control"
                   value="{{ $arrDate }}" required>
            @error('arrival_date') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="arrival_time" class="form-label">Heure d’arrivée</label>
            <input id="arrival_time" type="time" name="arrival_time" class="form-control"
                   value="{{ $arrTime }}" required>
            @error('arrival_time') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          {{-- Places --}}
          <div class="col-md-6">
            <label for="seats_total" class="form-label">Nombre total de places</label>
            <input id="seats_total" type="number" min="1" step="1" name="seats_total" class="form-control"
                   value="{{ old('seats_total', $trip->seats_total) }}" required>
            @error('seats_total') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="seats_free" class="form-label">Places disponibles</label>
            <input id="seats_free" type="number" min="0" step="1" name="seats_free" class="form-control"
                   value="{{ old('seats_free', $trip->seats_free) }}" required>
            @error('seats_free') <div class="text-danger small">{{ $message }}</div> @enderror
            <div class="form-text">Ne peut pas dépasser le nombre total de places.</div>
          </div>
        </div>

        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary">Enregistrer</button>
          <a href="{{ route('home') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </form>
    </div>

    {{-- Panneau infos (lecture seule) --}}
    <div class="col-md-5">
      <div class="bg-white p-3 p-md-4 border rounded-3">
        <h6 class="mb-3">Informations du contact</h6>
        <p class="mb-1"><strong>Nom :</strong> {{ $trip->author?->name ?? auth()->user()->name }}</p>
        <p class="mb-1"><strong>Email :</strong> {{ $trip->author?->email ?? auth()->user()->email }}</p>
        <p class="mb-0"><strong>Téléphone :</strong> {{ $trip->author?->phone ?? auth()->user()->phone ?? '—' }}</p>
      </div>
    </div>
  </div>

  {{-- JS : seats_free <= seats_total + arrivée >= départ (date) --}}
  <script>
    (function () {
      const total = document.getElementById('seats_total');
      const free  = document.getElementById('seats_free');
      const depD  = document.getElementById('departure_date');
      const arrD  = document.getElementById('arrival_date');

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
        if (depD.value) {
          arrD.min = depD.value;
          if (arrD.value && arrD.value < depD.value) arrD.value = depD.value;
        } else {
          arrD.removeAttribute('min');
        }
      }

      total?.addEventListener('input', syncSeats);
      depD?.addEventListener('change', syncArrivalMin);

      syncSeats();
      syncArrivalMin();
    })();
  </script>
@endsection
