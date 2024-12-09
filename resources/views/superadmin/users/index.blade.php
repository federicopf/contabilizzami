@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h2>Gestione utenti</h2>
            <p class="text-muted">Visualizza e gestisci i tuoi utenti</p>
        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">+ Aggiungi Utente</a>
        </div>
    </div>
    
    <!-- Filtro -->
    <div class="row">
        <div class="mb-4 col-12 col-md-4">
            <label for="userFilter" class="form-label">Seleziona tipo di utente:</label>
            <select id="userFilter" class="form-select border-secondary">
                <option value="0" {{ request('superadmin') == '0' ? 'selected' : '' }}>Utente</option>
                <option value="1" {{ request('superadmin') == '1' ? 'selected' : '' }}>Superadmin</option>
            </select>
        </div>
    </div>    
    
    <!-- Tabella -->
    <table class="table table-striped">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody id="userTable">
            @forelse ($users as $user)
                <tr data-type="{{ $user->type }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->type == 0 ? 'Utente' : 'Superadmin' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nessun utente trovato.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script>
    $(document).ready(function () {
        $('#userFilter').on('change', function () {
            let selectedType = $(this).val();
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('superadmin', selectedType);
            window.location.href = currentUrl.toString();
        });
    });
</script>

@endsection
