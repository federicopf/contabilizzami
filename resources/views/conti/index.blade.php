@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-between mb-4">
            <div class="col-md-6">
                <h2>Gestione dei Conti</h2>
                <p class="text-muted">Visualizza e gestisci i tuoi conti</p>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-primary">+ Aggiungi Conto</button>
            </div>
        </div>

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
                                <tr onclick="window.location='{{ route('conti.show', $account->id) }}'" style="cursor: pointer;">
                                    <td>{{ $account->name }}</td>
                                    <td class="d-none d-md-table-cell">{{ $account->type }}</td>
                                    <td>€ {{ number_format($account->balance, 2, ',', '.') }}</td>
                                    <td class="d-none d-md-table-cell">{{ $account->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
