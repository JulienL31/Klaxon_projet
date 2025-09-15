<nav class="navbar navbar-klx mb-3">
  <div class="container-fluid">
    <a class="navbar-brand fw-semibold" href="{{ route('home') }}">Touche pas au klaxon</a>
    <div class="ms-auto d-flex align-items-center gap-2">
      @guest
        <a class="btn btn-chip dark" href="{{ route('login') }}">Connexion</a>
      @endguest
      @auth
        <a class="btn btn-chip dark" href="#">Créer un trajet</a>
        <span>Bonjour {{ auth()->user()->first_name ?? 'Xxxxxx' }} {{ auth()->user()->last_name ?? 'Xxxxxx' }}</span>
        <form method="POST" action="#" class="d-inline">@csrf
          <button class="btn btn-chip dark">Déconnexion</button>
        </form>
      @endauth
    </div>
  </div>
</nav>
