@extends('layouts.app')

@section('title','Créer un trajet')
@section('pagetitle','Créer un trajet')

@section('content')
  <form method="POST" action="{{ route('trips.store') }}" class="bg-white p-3 border rounded-3">
    @csrf

    <div class="row g-3">
      {{-- Agences --}}
      <div class="col-md-6">
        <label class="form-label">Agence de départ</label>
        <select name="agency_from_id" class="form-select" required>
          <option value="" hidden>Choisir…</option>
          @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(old('agency_from_id') == $a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
        @error('agency_from_id') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Agence d’arrivée</label>
        <select name="agency_to_id" class="form-select" required>
          <option value="" hidden>Choisir…</option>
          @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(old('agency_to_id') == $a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
        @error('agency_to_id') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      {{-- Départ --}}
      <div class="col-md-6">
        <label class="form-label">Date de départ</label>
        <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date') }}" required>
        @error('departure_date') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Heure de départ</label>
        <input type="time" name="departure_time" class="form-control" value="{{ old('departure_time') }}" required>
        @error('departure_time') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      {{-- Arrivée --}}
      <div class="col-md-6">
        <label class="form-label">Date d’arrivée</label>
        <input type="date" name="arrival_date" class="form-control" value="{{ old('arrival_date') }}" required>
        @error('arrival_date') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Heure d’arrivée</label>
        <input type="time" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}" required>
        @error('arrival_time') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      {{-- Places --}}
      <div class="col-md-6">
        <label class="form-label">Nombre total de places</label>
        <input type="number" min="1" name="seats_total" class="form-control" value="{{ old('seats_total') }}" required>
        @error('seats_total') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Places disponibles</label>
        <input type="number" min="0" name="seats_free" class="form-control" value="{{ old('seats_free') }}" required>
        @error('seats_free') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      {{-- Infos auteur (affichage non modifiable comme demandé dans le sujet) --}}
      <div class="col-12">
        <div class="p-3 bg-light rounded-3">
          <div class="row g-2">
            <div class="col-md-4">
              <label class="form-label mb-0 small text-muted">Auteur</label>
              <input class="form-control" value="{{ auth()->user()->name }}" disabled>
            </div>
            <div class="col-md-4">
              <label class="form-label mb-0 small text-muted">Téléphone</label>
              <input class="form-control" value="{{ auth()->user()->phone ?? '—' }}" disabled>
            </div>
            <div class="col-md-4">
              <label class="form-label mb-0 small text-muted">Email</label>
              <input class="form-control" value="{{ auth()->user()->email }}" disabled>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-3">
      <button class="btn btn-primary">Enregistrer</button>
      <a href="{{ route('home') }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
@endsection
