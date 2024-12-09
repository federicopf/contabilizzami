@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crea Nuovo Utente</h1>

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
    <form action="{{ route('superadmin.users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Inserisci il nome" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci l'email" required>
        </div>

        <div class="mb-3">
            <label for="superadmin" class="form-label">Tipo di Utente</label>
            <select class="form-select" id="superadmin" name="superadmin" required>
                <option value="0" selected>Utente</option>
                <option value="1">Superadmin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crea Utente</button>
    </form>
</div>
@endsection
