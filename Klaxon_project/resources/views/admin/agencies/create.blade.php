@extends('layouts.app')
@section('title','Créer une agence')
@section('pagetitle','Créer une agence')

@section('content')
  <form method="POST" action="{{ route('admin.agencies.store') }}" class="bg-white p-3 p-md-4 border rounded-3" style="max-width:520px">
    @csrf
    <div class="mb-3">
      <label class="form-label">Nom</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
      @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <button class="btn btn-primary">Enregistrer</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.agencies.index') }}">Annuler</a>
  </form>
@endsection
