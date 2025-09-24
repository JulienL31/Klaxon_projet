@extends('layouts.app')

@section('title','Agences')
@section('pagetitle','Agences')

@section('content')

  @if(session('status'))
    <div class="alert-klx mb-3">{{ session('status') }}</div>
  @endif

  <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
    <a class="btn btn-primary" href="{{ route('admin.agencies.create') }}">
      Créer une agence
    </a>

    <form method="GET" class="ms-auto d-flex gap-2">
      <input
        type="search"
        name="q"
        value="{{ request('q') }}"
        class="form-control"
        placeholder="Rechercher une agence…"
        aria-label="Rechercher une agence"
        style="max-width: 260px"
      >
      @if(request()->filled('q'))
        <a class="btn btn-outline-secondary" href="{{ route('admin.agencies.index') }}">Réinitialiser</a>
      @endif
      <button class="btn btn-outline-primary">Rechercher</button>
    </form>
  </div>

  <div class="table-box">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th style="width: 90px;">ID</th>
            <th>Nom</th>
            <th style="width: 180px;">Trajets liés</th>
            <th class="text-end" style="width: 160px;">Actions</th>
          </tr>
        </thead>

        <tbody>
          @forelse($agencies as $a)
            @php
              $departCount = $a->departing_trips_count ?? 0;
              $arriveCount = $a->arriving_trips_count ?? 0;
              $totalTrips  = $departCount + $arriveCount;
              $hasTrips    = $totalTrips > 0;
            @endphp

            <tr>
              <td>{{ $a->id }}</td>
              <td>{{ $a->name }}</td>
              <td>
                <span class="text-muted">
                  {{ $totalTrips }}
                  <span class="d-none d-sm-inline">trajet{{ $totalTrips > 1 ? 's' : '' }}</span>
                  @if($departCount || $arriveCount)
                    <span class="d-none d-lg-inline">
                      ({{ $departCount }} départ{{ $departCount>1 ? 's':'' }},
                      {{ $arriveCount }} arrivée{{ $arriveCount>1 ? 's':'' }})
                    </span>
                  @endif
                </span>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-light"
                   href="{{ route('admin.agencies.edit', $a) }}"
                   aria-label="Modifier l’agence {{ $a->name }}">
                  <i class="bi bi-pencil"></i>
                </a>

                @if(!$hasTrips)
                  <form class="d-inline"
                        method="POST"
                        action="{{ route('admin.agencies.destroy', $a) }}"
                        onsubmit="return confirm('Supprimer l’agence « {{ $a->name }} » ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-light" aria-label="Supprimer l’agence {{ $a->name }}">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                @else
                  <button class="btn btn-sm btn-light" disabled
                          title="Impossible de supprimer : des trajets utilisent cette agence">
                    <i class="bi bi-trash"></i>
                  </button>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center py-5 text-muted">
                Aucune agence
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if(method_exists($agencies, 'links'))
    <div class="mt-3">
      {{ $agencies->withQueryString()->links() }}
    </div>
  @endif
@endsection
