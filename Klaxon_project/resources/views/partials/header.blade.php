<header role="banner">
  <nav class="navbar navbar-klx mb-3" role="navigation" aria-label="Navigation principale">
    <div class="container-fluid">
      <a class="navbar-brand fw-semibold"
         href="@can('admin') {{ route('admin.index') }} @else {{ route('home') }} @endcan">
        Touche pas au klaxon
      </a>

      <div class="ms-auto d-flex align-items-center gap-2">
        @guest
          <a class="btn btn-chip dark" href="{{ route('login') }}">Connexion</a>
        @else
          @can('admin')
            <a class="btn btn-chip gray" href="{{ route('admin.users.index') }}">Utilisateurs</a>
            <a class="btn btn-chip gray" href="{{ route('admin.agencies.index') }}">Agences</a>
            <a class="btn btn-chip gray" href="{{ route('admin.trips.index') }}">Trajets</a>
            {{-- Accès direct à la création --}}
            <a class="btn btn-chip dark" href="{{ route('trips.create') }}">Créer un trajet</a>
          @else
            <a class="btn btn-chip dark" href="{{ route('trips.create') }}">Créer un trajet</a>
          @endcan

          <span class="ms-1">Bonjour {{ auth()->user()->name }}</span>

          <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
            @csrf
            <button type="submit" class="btn btn-chip dark" aria-label="Se déconnecter">Déconnexion</button>
          </form>
        @endguest
      </div>
    </div>
  </nav>
</header>
