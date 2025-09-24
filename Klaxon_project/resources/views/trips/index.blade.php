@extends('layouts.app')

@section('title','Trajets')
@section('pagetitle','Trajets')

@section('content')
  {{-- Bouton créer un trajet (utilise la route utilisateur) --}}
  <div class="d-flex justify-content-end mb-3">
    <a href="{{ route('trips.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i> Créer un trajet
    </a>
  </div>

  @if(session('status'))
    <div class="alert-klx mb-3">{{ session('status') }}</div>
  @endif

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
              <td>{{ $t->departure_at?->format('d/m/y') ?? '—' }}</td>
              <td>{{ $t->departure_at?->format('H:i') ?? '—' }}</td>

              <td>{{ $t->to?->name ?? '—' }}</td>
              <td>{{ $t->arrival_at?->format('d/m/y') ?? '—' }}</td>
              <td>{{ $t->arrival_at?->format('H:i') ?? '—' }}</td>

              <td>{{ $t->seats_free }} / {{ $t->seats_total }}</td>
              <td>{{ $t->contact_name ?? $t->author?->name ?? '—' }}</td>

              <td class="text-end">
                {{-- Voir (popup) --}}
                <button type="button"
                        class="btn btn-sm btn-light btn-trip-details"
                        data-bs-toggle="modal"
                        data-bs-target="#tripDetailsModal"
                        data-author="{{ $t->contact_name ?? $t->author?->name }}"
                        data-phone="{{ $t->contact_phone ?? $t->author?->phone }}"
                        data-email="{{ $t->contact_email ?? $t->author?->email }}"
                        data-seats="{{ $t->seats_total }}"
                        aria-label="Voir les détails">
                  <i class="bi bi-eye"></i>
                </button>

                {{-- Éditer (route utilisateur) --}}
                <a class="btn btn-sm btn-light" href="{{ route('trips.edit', $t) }}" aria-label="Modifier">
                  <i class="bi bi-pencil"></i>
                </a>

                {{-- Supprimer (route admin) --}}
                <form method="POST"
                      action="{{ route('admin.trips.destroy', $t) }}"
                      class="d-inline"
                      onsubmit="return confirm('Supprimer ce trajet ?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-light" aria-label="Supprimer">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-5 text-muted">Aucun trajet</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('modals')
  @include('partials.trip-details-modal')
@endpush
