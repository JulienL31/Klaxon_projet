@extends('layouts.app')
@section('title','Modifier une agence')
@section('pagetitle','Modifier une agence')

@section('content')
  <form method="POST" action="{{ route('admin.agencies.update',$agency) }}" class="bg-white p-3 p-md-4 border rounded-3" style="max-width:520px">
    @csrf @method('PUT')
    <div class="mb-3">
      <label class="form-label">Nom</label>
      <input type="text" name="name" class="form-control" value="{{ old('name',$agency->name) }}" required>
      @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <button class="btn btn-primary">Enregistrer</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.agencies.index') }}">Annuler</a>
  </form>
@endsection
