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
                                <th scope="col" class="text-end d-none d-md-table-cell">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Conto Corrente</td>
                                <td class="d-none d-md-table-cell">Spendibili</td>
                                <td>€ 2,500</td>
                                <td class="d-none d-md-table-cell">2023-10-16</td>
                                <td class="text-end d-none d-md-table-cell">
                                    <button class="btn btn-info btn-sm me-1">Visualizza</button>
                                    <button class="btn btn-warning btn-sm me-1">Modifica</button>
                                    <button class="btn btn-danger btn-sm">Elimina</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Risparmio Famiglia</td>
                                <td class="d-none d-md-table-cell">Risparmio</td>
                                <td>€ 5,000</td>
                                <td class="d-none d-md-table-cell">2022-06-10</td>
                                <td class="text-end d-none d-md-table-cell">
                                    <button class="btn btn-info btn-sm me-1">Visualizza</button>
                                    <button class="btn btn-warning btn-sm me-1">Modifica</button>
                                    <button class="btn btn-danger btn-sm">Elimina</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Investimenti Azioni</td>
                                <td class="d-none d-md-table-cell">Investimento</td>
                                <td>€ 10,000</td>
                                <td class="d-none d-md-table-cell">2021-03-05</td>
                                <td class="text-end d-none d-md-table-cell">
                                    <button class="btn btn-info btn-sm me-1">Visualizza</button>
                                    <button class="btn btn-warning btn-sm me-1">Modifica</button>
                                    <button class="btn btn-danger btn-sm">Elimina</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
