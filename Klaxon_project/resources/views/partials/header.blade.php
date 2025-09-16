<nav class="navbar navbar-klx mb-3">
  <div class="container-fluid">
    <a class="navbar-brand fw-semibold"
       href="{{ (auth()->check() && (auth()->user()->role ?? 'user') === 'admin') ? route('admin.index') : route('home') }}">
      Touche pas au klaxon
    </a>

    <div class="ms-auto d-flex align-items-center gap-2">
      @guest
        <a class="btn btn-chip dark" href="{{ route('login') }}">Connexion</a>
      @else
        @if((auth()->user()->role ?? 'user') === 'admin')
          <a class="btn btn-chip gray" href="{{ route('admin.users.index') }}">Utilisateurs</a>
          <a class="btn btn-chip gray" href="{{ route('admin.agencies.index') }}">Agences</a>
          <a class="btn btn-chip gray" href="{{ route('admin.trips.index') }}">Trajets</a>
        @else
          <a class="btn btn-chip dark" href="{{ route('trips.create') }}">Créer un trajet</a>
        @endif

        <span>Bonjour {{ auth()->user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
          @csrf
          <button class="btn btn-chip dark">Déconnexion</button>
        </form>
      @endguest
    </div>
  </div>
</nav>
