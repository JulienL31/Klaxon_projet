@extends('layouts.app')

@section('title','Accueil')
@section('pagetitle','Trajets proposés')

{{-- Message page spécifique (on ne réaffiche pas session("status") ici) --}}
@section('flash')
  @guest
    <div class="alert-klx">
      Pour obtenir plus d'informations sur un trajet, veuillez vous connecter
    </div>
  @endguest
@endsection

@section('content')
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
            <th>Places</th>
            @auth <th class="text-end">Actions</th> @endauth
          </tr>
        </thead>

        <tbody>
          @forelse($trips as $t)
            @php
              $canManage = auth()->check() && (
                (auth()->user()->role ?? 'user') === 'admin' ||
                auth()->id() === $t->author_id
              );
            @endphp

            <tr>
              <td>{{ $t->from?->name ?? '—' }}</td>
              <td>{{ $t->departure_at?->format('d/m/y') ?? '—' }}</td>
              <td>{{ $t->departure_at?->format('H:i') ?? '—' }}</td>
              <td>{{ $t->to?->name ?? '—' }}</td>
              <td>{{ $t->arrival_at?->format('d/m/y') ?? '—' }}</td>
              <td>{{ $t->arrival_at?->format('H:i') ?? '—' }}</td>
              <td>{{ $t->seats_free }}</td>

              @auth
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

                  @if($canManage)
                    <a class="btn btn-sm btn-light" href="{{ route('trips.edit', $t) }}" aria-label="Modifier">
                      <i class="bi bi-pencil"></i>
                    </a>

                    <form method="POST"
                          action="{{ route('trips.destroy', $t) }}"
                          class="d-inline"
                          onsubmit="return confirm('Supprimer ce trajet ?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-light" aria-label="Supprimer">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  @endif
                </td>
              @endauth
            </tr>
          @empty
            <tr>
              <td colspan="@auth 8 @else 7 @endauth"
                  class="text-center py-5 text-muted">
                Aucun trajet disponible
              </td>
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
