@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Dettagli del Conto</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Dettagli del Conto -->
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Nome del Conto:</strong> {{ $account->name }}</p>
                    <p><strong>Tipo di Conto:</strong> 
                        @switch($account->type)
                            @case(1)
                                Spendibili
                                @break
                            @case(2)
                                Risparmio
                                @break
                            @case(3)
                                Investimento
                                @break
                            @case(4)
                                Debito
                                @break
                            @case(5)
                                Credito
                                @break
                            @default
                                Sconosciuto
                        @endswitch
                    </p>
                    <p><strong>Saldo Attuale:</strong> € {{ number_format($account->transactions->sum('amount'), 2, ',', '.') }}</p>
                    
                    <!-- Dropdown per le azioni sul conto -->
                    <div class="dropdown mb-4">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Azioni Conto
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="">Crea Nuova Transazione</a></li>
                            <li><a class="dropdown-item" href="">Crea Trasferimento</a></li>

                            <li><a class="dropdown-item" href="{{ route('conti.edit', $account->id) }}">Modifica Conto</a></li>
                            <li>
                                <form action="{{ route('conti.destroy', $account->id ) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item" onclick="return confirm('Sei sicuro di voler eliminare questo conto?')">Elimina Conto</button>
                                </form>
                            </li>
                        </ul>
                    </div>
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
                                    <th scope="col" class="text-end">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($account->transactions as $transaction)
                                    <tr>
                                        <td class="d-none d-md-table-cell">{{ $transaction->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>{{ $transaction->amount < 0 ? '- € ' . abs($transaction->amount) : '+ € ' . $transaction->amount }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('transactions.destroy', ['transaction' => $transaction->id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo movimento?')">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pulsante per tornare indietro -->
            <a href="{{ route('conti.index',$type) }}" class="btn btn-secondary mt-4">Indietro</a>
        </div>
    </div>
</div>
@endsection
