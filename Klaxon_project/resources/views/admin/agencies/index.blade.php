@extends('layouts.app')

@section('title','Agences')
@section('pagetitle','Agences')

@section('content')
  @if(session('status'))
    <div class="alert-klx mb-3">{{ session('status') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a class="btn btn-primary" href="{{ route('admin.agencies.create') }}">Créer une agence</a>
  </div>

  <div class="table-box">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nom</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($agencies as $a)
          <tr>
            <td>{{ $a->id }}</td>
            <td>{{ $a->name }}</td>
            <td class="text-end">
              <a class="btn btn-sm btn-light" href="{{ route('admin.agencies.edit',$a) }}"><i class="bi bi-pencil"></i></a>
              <form class="d-inline" method="POST" action="{{ route('admin.agencies.destroy',$a) }}"
                    onsubmit="return confirm('Supprimer cette agence ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-light"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center text-muted py-4">Aucune agence.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $agencies->links() }}
  </div>
@endsection
