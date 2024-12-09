@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Utente Creato con Successo</h1>

    <div class="alert alert-success">
        <p><strong>Nome:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Tipo Utente:</strong> {{ $user->superadmin ? 'Superadmin' : 'Utente' }}</p>
        <p><strong>Password Temporanea:</strong> <code>{{ $temporaryPassword }}</code></p>
    </div>

    <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">Crea un Altro Utente</a>
    <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Torna alla Lista Utenti</a>
</div>
@endsection
