@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Dettagli del Conto</h2>

            <!-- Dettagli del Conto -->
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Nome del Conto:</strong> Conto Corrente</p>
                    <p><strong>Tipo di Conto:</strong> Spendibili</p>
                    <p><strong>Saldo Attuale:</strong> € 2,500</p>
                    <p><strong>Data di Creazione:</strong> 2023-10-16</p>
                    <p><strong>Ultimo Aggiornamento:</strong> 2023-10-20</p>

                    <!-- Pulsanti di Modifica ed Eliminazione -->
                    <a href="{{ route('conti.edit', $account->id) }}" class="btn btn-warning">Modifica</a>
                    <form action="{{ route('conti.destroy', $account->id ) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo conto?')">Elimina</button>
                    </form>
                </div>
            </div>

            <!-- Lista dei Movimenti -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Lista dei Movimenti</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col" class="d-none d-md-table-cell">Data</th>
                                    <th scope="col">Descrizione</th>
                                    <th scope="col">Importo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="d-none d-md-table-cell">2023-10-18</td>
                                    <td>Pagamento Bollette</td>
                                    <td>- € 100,00</td>
                                </tr>
                                <tr>
                                    <td class="d-none d-md-table-cell">2023-10-19</td>
                                    <td>Stipendio Mensile</td>
                                    <td>+ € 1,500,00</td>
                                </tr>
                                <tr>
                                    <td class="d-none d-md-table-cell">2023-10-20</td>
                                    <td>Spesa Supermercato</td>
                                    <td>- € 50,00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pulsante per tornare indietro -->
            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-4">Indietro</a>
        </div>
    </div>
</div>
@endsection
