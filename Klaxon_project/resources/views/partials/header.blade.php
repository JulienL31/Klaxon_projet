<nav class="navbar navbar-klx mb-3">
  <div class="container-fluid">
    <a class="navbar-brand fw-semibold" href="{{ route('home') }}">Touche pas au klaxon</a>

    <div class="ms-auto d-flex align-items-center gap-2">
      @guest
        <a class="btn btn-chip dark" href="{{ route('login') }}">Connexion</a>
      @else
        <a class="btn btn-chip dark"
           href="{{ \Illuminate\Support\Facades\Route::has('trips.create') ? route('trips.create') : '#' }}">
          Créer un trajet
        </a>

        <span>Bonjour {{ auth()->user()->name }}</span>

        @if(\Illuminate\Support\Facades\Route::has('logout'))
          <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                <button class="btn btn-chip dark">Déconnexion</button>
          </form>
        @else
          <button class="btn btn-chip dark ms-2" disabled aria-disabled="true">Déconnexion</button>
        @endif
      @endguest
    </div>
  </div>
</nav>
