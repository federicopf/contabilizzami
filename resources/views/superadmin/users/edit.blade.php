@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifica Utente</h1>

    <!-- Messaggi di errore -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="superadmin" class="form-label">Tipo di Utente</label>
            <select class="form-select" id="superadmin" name="superadmin" required>
                <option value="0" {{ !$user->superadmin ? 'selected' : '' }}>Utente</option>
                <option value="1" {{ $user->superadmin ? 'selected' : '' }}>Superadmin</option>
            </select>
        </div>

        <a href="{{ route('superadmin.users.show', $user->id) }}" class="btn btn-secondary">Annulla</a>
        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
    </form>
</div>
@endsection
