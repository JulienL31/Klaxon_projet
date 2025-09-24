@extends('layouts.app')

@section('title','Créer une agence')
@section('pagetitle','Créer une agence')

@section('content')
  <form method="POST" action="{{ route('admin.agencies.store') }}" class="bg-white p-3 border rounded-3" novalidate>
    @csrf

    <div class="mb-3">
      <label class="form-label">Nom de l’agence</label>
      <input type="text" name="name" value="{{ old('name') }}"
             class="form-control @error('name') is-invalid @enderror" required maxlength="100">
      @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Enregistrer</button>
      <a href="{{ route('admin.agencies.index') }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
@endsection
