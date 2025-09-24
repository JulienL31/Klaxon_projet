@extends('layouts.app')
@section('title','Trajets')
@section('pagetitle','Trajets')

@section('content')
  @if(session('status')) <div class="alert-klx mb-3">{{ session('status') }}</div> @endif

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
              <td>{{ $t->from?->name ?? '—' }}</td>
              <td>{{ $t->departure_dt?->format('d/m/y') ?? '—' }}</td>
              <td>{{ $t->departure_dt?->format('H:i') ?? '—' }}</td>
              <td>{{ $t->to?->name ?? '—' }}</td>
              <td>{{ $t->arrival_dt?->format('d/m/y') ?? '—' }}</td>
              <td>{{ $t->arrival_dt?->format('H:i') ?? '—' }}</td>
              <td>{{ ($t->seats_free ?? 0) }} / {{ ($t->seats_total ?? 0) }}</td>
              <td>{{ $t->author?->name ?? $t->contact_name ?? '—' }}</td>
              <td class="text-end">
                <form method="POST" action="{{ route('admin.trips.destroy',$t) }}"
                      onsubmit="return confirm('Supprimer ce trajet ?')" class="d-inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-light"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="text-center py-5 text-muted">Aucun trajet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
