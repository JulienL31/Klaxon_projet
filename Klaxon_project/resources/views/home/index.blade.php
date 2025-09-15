@extends('layouts.app')

@section('title','Accueil')
@section('pagetitle','Trajets proposés')

{{-- Messages au-dessus du titre (maquette) --}}
@section('flash')
  @if(session('status'))
    <div class="alert-klx">{{ session('status') }}</div>
  @endif
  @guest
    <div class="alert-klx">Pour obtenir plus d'informations sur un trajet, veuillez vous connecter</div>
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
            <tr>
              <td>{{ $t->from->name }}</td>
              <td>{{ $t->departure_dt->format('d/m/y') }}</td>
              <td>{{ $t->departure_dt->format('H:i') }}</td>
              <td>{{ $t->to->name }}</td>
              <td>{{ $t->arrival_dt->format('d/m/y') }}</td>
              <td>{{ $t->arrival_dt->format('H:i') }}</td>
              <td>{{ $t->seats_free }}</td>
              @auth
                <td class="text-end">
                  {{-- Voir (ouvre la modale de détails) --}}
                  <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#tripDetails-{{ $t->id }}">
                    <i class="bi bi-eye"></i>
                  </button>

                  {{-- Éditer / Supprimer (à activer quand l’auteur est relié) --}}
                  {{--
                  @can('update', $t)
                    <a class="btn btn-sm btn-light" href="{{ route('trips.edit',$t) }}"><i class="bi bi-pencil"></i></a>
                  @endcan
                  @can('delete', $t)
                    <form method="POST" action="{{ route('trips.destroy',$t) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer ce trajet ?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-light"><i class="bi bi-trash"></i></button>
                    </form>
                  @endcan
                  --}}
                </td>
              @endauth
            </tr>
          @empty
            <tr>
              <td colspan="@auth 8 @else 7 @endauth" class="text-center py-5 text-muted">
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
  {{-- Une modale de détails par trajet (affichée quand connecté) --}}
  @auth
    @foreach($trips as $t)
      <div class="modal fade" id="tripDetails-{{ $t->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Détails du trajet</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              <p><strong>Auteur :</strong> {{ $t->contact_name }}</p>
              <p><strong>Téléphone :</strong> {{ $t->contact_phone ?? '—' }}</p>
              <p><strong>Email :</strong> {{ $t->contact_email }}</p>
              <p><strong>Nombre total de places :</strong> {{ $t->seats_total }}</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  @endauth
@endpush
