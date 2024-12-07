@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h2>Gestione utenti</h2>
            <p class="text-muted">Visualizza e gestisci i tuoi utenti</p>
        </div>
        <div class="col-md-3 text-end">
            <a class="btn btn-primary">+ Aggiungi Utente</a>
        </div>
    </div>
    
    <!-- Filtro -->
    <div class="row">
        <div class="mb-4 col-12 col-md-4">
            <label for="userFilter" class="form-label">Seleziona tipo di utente:</label>
            <select id="userFilter" class="form-select border-secondary">
                <option value="0">Utente</option>
                <option value="1">Superadmin</option>
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
            <tr data-type="0">
                <td>1</td>
                <td>Mario Rossi</td>
                <td>Utente</td>
            </tr>
            <tr data-type="0">
                <td>3</td>
                <td>Anna Verdi</td>
                <td>Utente</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('userFilter').addEventListener('change', function () {
        const filterValue = this.value;
        const rows = document.querySelectorAll('#userTable tr');

        rows.forEach(row => {
            const type = row.getAttribute('data-type');
            if (filterValue === "" || filterValue === type) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endsection
