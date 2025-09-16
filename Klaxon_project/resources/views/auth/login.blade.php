@extends('layouts.app')

@section('title','Connexion')
@section('pagetitle','Connexion')

@section('content')
  @if (session('status'))
    <div class="alert-klx mb-3">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert-klx mb-3">Identifiants invalides.</div>
  @endif

  <form method="POST" action="{{ route('login') }}" class="bg-white p-3 p-md-4 border rounded-3" style="max-width:480px">
    @csrf

    <div class="mb-3">
      <label class="form-label">Adresse email</label>
      <input
        type="email"
        name="email"
        class="form-control"
        value="{{ old('email') }}"
        required
        autofocus
        autocomplete="username">
    </div>

    <div class="mb-3">
      <label class="form-label">Mot de passe</label>
      <input
        type="password"
        name="password"
        class="form-control"
        required
        autocomplete="current-password">
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="remember" id="remember">
      <label class="form-check-label" for="remember">Se souvenir de moi</label>
    </div>

    <button class="btn btn-primary">Se connecter</button>
  </form>
@endsection
