@extends('layouts.app')

@section('content')
  <h1 class="h3 mb-3">Trajets</h1>

  @includeIf('partials.flash')

  <div class="table-box">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Départ</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Places (libres/total)</th>
            <th>Auteur</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($trips as $t)
          <tr>
            <td>{{ $t->from->name ?? '—' }}</td>
            <td>{{ $t->departure_at?->format('d/m/Y') }}</td>
            <td>{{ $t->departure_at?->format('H:i') }}</td>

            <td>{{ $t->to->name ?? '—' }}</td>
            <td>{{ $t->arrival_at?->format('d/m/Y') }}</td>
            <td>{{ $t->arrival_at?->format('H:i') }}</td>

            <td>{{ $t->seats_free }} / {{ $t->seats_total }}</td>
            <td>{{ $t->author->name ?? '—' }}</td>
            <td class="text-end">
              <form action="{{ route('admin.trips.destroy', $t) }}" method="POST"
                    onsubmit="return confirm('Supprimer ce trajet ?')" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-chip dark">Supprimer</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center text-muted py-4">Aucun trajet</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
