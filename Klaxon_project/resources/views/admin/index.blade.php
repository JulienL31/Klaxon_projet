@extends('layouts.app')

@section('title','Tableau de bord')
@section('pagetitle','Tableau de bord administrateur')

@section('content')
  @if(session('status')) <div class="alert-klx mb-3">{{ session('status') }}</div> @endif

  <div class="row g-3">
    <div class="col-md-4">
      <div class="bg-white p-3 border rounded-3 text-center">
        <div class="fs-1">{{ $stats['users'] }}</div>
        <div class="text-muted">Utilisateurs</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="bg-white p-3 border rounded-3 text-center">
        <div class="fs-1">{{ $stats['agencies'] }}</div>
        <div class="text-muted">Agences</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="bg-white p-3 border rounded-3 text-center">
        <div class="fs-1">{{ $stats['trips'] }}</div>
        <div class="text-muted">Trajets</div>
      </div>
    </div>
  </div>
@endsection
