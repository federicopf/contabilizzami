@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Pulsante 'Torna alla Lista' in alto a sinistra -->
    <div class="mb-3">
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Torna alla Lista</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">{{ $user->name }}</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Email:</strong></p>
                    <p>{{ $user->email }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tipo Utente:</strong></p>
                    <p>{{ $user->superadmin ? 'Superadmin' : 'Utente' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pulsanti azione -->
    <div class="d-flex justify-content-between">
        <div>
            <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-warning">Modifica Utente</a>
            <a href="#" class="btn btn-info">Resetta Password</a>
        </div>
        <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo utente?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Elimina Utente</button>
        </form>
    </div>
</div>
@endsection
