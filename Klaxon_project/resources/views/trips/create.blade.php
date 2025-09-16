@extends('layouts.app')

@section('title','Modifier un trajet')
@section('pagetitle','Modifier un trajet')

@section('content')
  <form method="POST" action="{{ route('trips.update', $trip) }}" class="bg-white p-3 border rounded-3">
    @csrf @method('PUT')

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Agence de départ</label>
        <select name="agency_from_id" class="form-select" required>
          @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(old('agency_from_id',$trip->agency_from_id)==$a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
        @error('agency_from_id') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Agence d’arrivée</label>
        <select name="agency_to_id" class="form-select" required>
          @foreach($agencies as $a)
            <option value="{{ $a->id }}" @selected(old('agency_to_id',$trip->agency_to_id)==$a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
        @error('agency_to_id') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      @php
        $depDate = old('departure_date', $trip->departure_dt->format('Y-m-d'));
        $depTime = old('departure_time', $trip->departure_dt->format('H:i'));
        $arrDate = old('arrival_date', $trip->arrival_dt->format('Y-m-d'));
        $arrTime = old('arrival_time', $trip->arrival_dt->format('H:i'));
      @endphp

      <div class="col-md-6">
        <label class="form-label">Date de départ</label>
        <input type="date" name="departure_date" class="form-control" value="{{ $depDate }}" required>
        @error('departure_date') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Heure de départ</label>
        <input type="time" name="departure_time" class="form-control" value="{{ $depTime }}" required>
        @error('departure_time') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Date d’arrivée</label>
        <input type="date" name="arrival_date" class="form-control" value="{{ $arrDate }}" required>
        @error('arrival_date') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Heure d’arrivée</label>
        <input type="time" name="arrival_time" class="form-control" value="{{ $arrTime }}" required>
        @error('arrival_time') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Nombre total de places</label>
        <input type="number" min="1" name="seats_total" class="form-control" value="{{ old('seats_total',$trip->seats_total) }}" required>
        @error('seats_total') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Places disponibles</label>
        <input type="number" min="0" name="seats_free" class="form-control" value="{{ old('seats_free',$trip->seats_free) }}" required>
        @error('seats_free') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mt-3">
      <button class="btn btn-primary">Enregistrer</button>
      <a href="{{ route('home') }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
@endsection
