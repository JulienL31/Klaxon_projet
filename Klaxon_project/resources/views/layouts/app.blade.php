<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Touche pas au Klaxon')</title>

  {{-- Icônes Bootstrap --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  {{-- CSS + JS (Bootstrap bundle importé dans resources/js/app.js) --}}
  @vite(['resources/js/app.js'])
</head>
<body>
  <div class="container py-3">
    {{-- Header / Nav --}}
    @include('partials.header')

    {{-- Flash global : à afficher UNE SEULE FOIS ici --}}
    @if (session('status'))
      <div class="alert-klx mb-3">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="alert-klx mb-3">{{ session('error') }}</div>
    @endif
    @if (session('success'))
      {{-- au cas où certains contrôleurs envoient encore "success" --}}
      <div class="alert-klx mb-3">{{ session('success') }}</div>
    @endif

    {{-- Messages spécifiques de page (ex: notice visiteur) --}}
    @yield('flash')

    {{-- Titre de page (affiché seulement s'il est défini) --}}
    @hasSection('pagetitle')
      <h1 class="page-title">@yield('pagetitle')</h1>
    @endif

    {{-- Contenu principal --}}
    @yield('content')

    {{-- Footer --}}
    @include('partials.footer')
  </div>

  {{-- Modales poussées par les vues --}}
  @stack('modals')
</body>
</html>
