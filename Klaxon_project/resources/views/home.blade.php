@extends('layouts.app')

@section('content')
  @includeIf('partials.flash')

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">Trajets proposés</h1>

    @auth
      <a href="{{ route('trips.create') }}" class="btn btn-chip dark">Créer un trajet</a>
    @endauth
  </div>

  @if($trips->isEmpty())
    <div class="alert-chip">Aucun trajet disponible pour le moment.</div>
  @else
    <div class="table-box">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Départ</th>
              <th>Arrivée</th>
              <th>Date départ</th>
              <th>Date arrivée</th>
              <th>Places libres</th>
              @auth
                <th class="text-end">Actions</th>
              @endauth
            </tr>
          </thead>
          <tbody>
            @foreach($trips as $trip)
              <tr>
                <td>{{ $trip->from->name ?? '—' }}</td>
                <td>{{ $trip->to->name ?? '—' }}</td>
                <td>{{ $trip->departure_at?->format('d/m/Y H:i') }}</td>
                <td>{{ $trip->arrival_at?->format('d/m/Y H:i') }}</td>
                <td>{{ $trip->seats_free }} / {{ $trip->seats_total }}</td>
                @auth
                  <td class="text-end">
                    @if(auth()->id() === $trip->author_id || (auth()->user()->role ?? 'user') === 'admin')
                      <a href="{{ route('trips.edit', $trip) }}" class="btn btn-sm btn-chip gray">Modifier</a>
                      <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Supprimer ce trajet ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-chip dark">Supprimer</button>
                      </form>
                    @else
                      <span class="text-muted small">—</span>
                    @endif
                  </td>
                @endauth
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
@endsection
