<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Touche pas au Klaxon')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  @vite(['resources/js/app.js'])
</head>
<body>
  <div class="container py-3">
    @include('partials.header')
    @yield('flash')
    @includeIf('partials.flash')
    <h1 class="page-title">@yield('pagetitle','')</h1>
    @yield('content')
    @include('partials.footer')
  </div>
  @stack('modals')
</body>
</html>
