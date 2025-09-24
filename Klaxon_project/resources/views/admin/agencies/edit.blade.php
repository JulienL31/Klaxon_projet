@extends('layouts.app')

@section('title','Modifier une agence')
@section('pagetitle','Modifier une agence')

@section('content')
  <form method="POST" action="{{ route('admin.agencies.update',$agency) }}" class="bg-white p-3 border rounded-3">
    @csrf @method('PUT')
    <div class="mb-3">
      <label class="form-label">Nom</label>
      <input type="text" name="name" class="form-control" value="{{ old('name',$agency->name) }}" required maxlength="100">
      @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary">Enregistrer</button>
      <a href="{{ route('admin.agencies.index') }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
@endsection
