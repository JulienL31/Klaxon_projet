@extends('layouts.app')

@section('title','Créer un trajet')
@section('pagetitle','Créer un trajet')

@section('content')
  {{-- Messages éventuels --}}
  @if (session('status'))
    <div class="alert-klx mb-3">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert-klx mb-3">
      <strong>Veuillez corriger les erreurs ci-dessous.</strong>
    </div>
  @endif

  <div class="row g-4">
    <div class="col-md-7">
      <form method="POST" action="{{ route('trips.store') }}" class="bg-white p-3 p-md-4 border rounded-3">
        @csrf

        <div class="row g-3">
          {{-- Agences --}}
          <div class="col-md-6">
            <label for="agency_from_id" class="form-label">Agence de départ</label>
            <select id="agency_from_id" name="agency_from_id" class="form-select" required>
              <option value="">— Sélectionner —</option>
              @foreach($agencies as $a)
                <option value="{{ $a->id }}" @selected(old('agency_from_id') == $a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
            @error('agency_from_id') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="agency_to_id" class="form-label">Agence d’arrivée</label>
            <select id="agency_to_id" name="agency_to_id" class="form-select" required>
              <option value="">— Sélectionner —</option>
              @foreach($agencies as $a)
                <option value="{{ $a->id }}" @selected(old('agency_to_id') == $a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
            @error('agency_to_id') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          {{-- Dates / heures --}}
          <div class="col-md-6">
            <label for="departure_date" class="form-label">Date de départ</label>
            <input id="departure_date" type="date" name="departure_date" class="form-control"
                   value="{{ old('departure_date') }}" required>
            @error('departure_date') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="departure_time" class="form-label">Heure de départ</label>
            <input id="departure_time" type="time" name="departure_time" class="form-control"
                   value="{{ old('departure_time') }}" required>
            @error('departure_time') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="arrival_date" class="form-label">Date d’arrivée</label>
            <input id="arrival_date" type="date" name="arrival_date" class="form-control"
                   value="{{ old('arrival_date') }}" required>
            @error('arrival_date') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="arrival_time" class="form-label">Heure d’arrivée</label>
            <input id="arrival_time" type="time" name="arrival_time" class="form-control"
                   value="{{ old('arrival_time') }}" required>
            @error('arrival_time') <div class="text-danger small">{{ $message }}</div> @enderror
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
        <p class="mb-0"><strong>Téléphone :</strong> {{ auth()->user()->phone ?? '—' }}</p>
        <small class="text-muted d-block mt-2">Ces informations seront utilisées comme contact du trajet.</small>
      </div>
    </div>
  </div>
@endsection
