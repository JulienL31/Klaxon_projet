@extends('layouts.app')
@section('title','Utilisateurs')
@section('pagetitle','Utilisateurs')

@section('content')
  <div class="table-box">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th></tr></thead>
        <tbody>
          @foreach($users as $u)
            <tr>
              <td>{{ $u->id }}</td>
              <td>{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td>{{ $u->role }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
