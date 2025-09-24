@extends('layouts.app')

@section('content')
  {{-- Wrapper centré sur l’écran, en tenant compte de la navbar --}}
  <div class="auth-wrapper d-flex align-items-center justify-content-center">
    <div class="card shadow-sm auth-card border-2">
      <div class="card-body p-4 p-sm-5">
        <h1 class="h2 mb-4 text-center">Connexion</h1>

        {{-- Flash / erreurs --}}
        @includeIf('partials.flash')

        @if ($errors->any())
          <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input
              id="email"
              type="email"
              name="email"
              class="form-control @error('email') is-invalid @enderror"
              value="{{ old('email') }}"
              required
              autofocus
              placeholder="nom@entreprise.com">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input
              id="password"
              type="password"
              name="password"
              class="form-control @error('password') is-invalid @enderror"
              required
              placeholder="••••••••">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember">
              <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
            {{-- Lien "Mot de passe oublié ?" si tu en as un --}}
            @if (Route::has('password.request'))
              <a class="small" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            @endif
          </div>

          <button type="submit" class="btn btn-dark btn-chip w-100">
            Se connecter
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
