@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-between mb-4">
            <div class="col-md-6">
                <h2>Gestione dei Conti</h2>
                <p class="text-muted">Visualizza e gestisci i tuoi conti</p>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('conti.create', $type) }}" class="btn btn-primary">+ Aggiungi Conto</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <!-- Lista dei Conti -->
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col" class="d-none d-md-table-cell">Tipo</th>
                                <th scope="col">Saldo</th>
                                <th scope="col" class="d-none d-md-table-cell">Data di Creazione</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $account)
                                <tr role="button" onclick="window.location='{{ route('conti.show', $account->id) }}'" style="cursor: pointer;">
                                    <td>{{ $account->name }}</td>
                                    <td class="d-none d-md-table-cell">{{ $account->getTypeNameAttribute() }}</td>
                                    <td>{{ number_format($account->transactions->sum('amount'), 2, ',', '.') }}</td>
                                    <td class="d-none d-md-table-cell">{{ $account->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script type="module">
        $(document).ready(function () {
            // Ricarica la pagina quando si torna indietro dal browser
            window.addEventListener('popstate', function () {
                if (window.location.pathname === '{{ route("conti.index", $type) }}') {
                    location.reload();
                }
            });
        });
    </script>
@endsection
