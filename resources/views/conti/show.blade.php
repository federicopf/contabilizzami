@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 mt-4">Dettagli del Conto</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <!-- Dropdown per le azioni sul conto -->
            <div class="dropdown mb-4">
                <a href="{{ route('conti.index',$type) }}" class="btn btn-secondary mt-2">Indietro</a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createTransactionModal">Crea Nuova Transazione</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createTransferModal">Crea Trasferimento</a></li>
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

            <!-- Dettagli del Conto -->
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Nome del Conto:</strong> {{ $account->name }}</p>
                    <p><strong>Tipo di Conto:</strong>  {{ $account->getTypeNameAttribute() }}</p>
                    <p><strong>Saldo Attuale:</strong> {{ number_format($account->transactions->sum('amount'), 2, ',', '.') }}</p>
                </div>
                
                <button class="btn btn-primary dropdown-toggle m-3 mt-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Azioni Conto
                </button>
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
                                        <td>{{ number_format($transaction->amount, 2, ',', '.') }}</td>
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
        </div>
    </div>
</div>

<!-- Modal per Crea Nuova Transazione -->
<!-- Modal per Crea Nuova Transazione -->
<div class="modal fade" id="createTransactionModal" tabindex="-1" aria-labelledby="createTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTransactionModalLabel">Crea Nuova Transazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="account_id" value="{{ $account->id }}">
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <input type="text" class="form-control" id="description" name="description" required autocomplete="off">
                        <!-- Div per visualizzare i suggerimenti -->
                        <div id="descriptionSuggestions" class="list-group"></div>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Importo</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salva Transazione</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal per Crea Trasferimento -->
<div class="modal fade" id="createTransferModal" tabindex="-1" aria-labelledby="createTransferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTransferModalLabel">Crea Trasferimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transactions.transfer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="account_from_id" value="{{ $account->id }}">
                    <div class="mb-3">
                        <label for="account_to_id" class="form-label">Conto Destinazione</label>
                        <select class="form-select" id="account_to_id" name="account_to_id" required>
                            @foreach($accounts as $acc)
                                @if($acc->id != $account->id)
                                    <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Importo</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salva Trasferimento</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="module">
    $(document).ready(function() {
        let debounceTimer;

        // Funzione per il debouncing
        function debounce(callback, delay) {
            return function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(callback, delay);
            };
        }

        // Funzione per cercare i suggerimenti
        function searchSuggestions() {
            var description = $('#description').val();

            if (description.length > 2) {
                $.ajax({
                    url: '{{ route("transactions.suggestions") }}',
                    method: 'GET',
                    data: {
                        query: description
                    },
                    success: function(response) {
                        $('#descriptionSuggestions').empty();

                        if (response.suggestions.length > 0) {
                            $.each(response.suggestions, function(index, suggestion) {
                                $('#descriptionSuggestions').append(
                                    '<button type="button" class="list-group-item list-group-item-action suggestion-item">' + suggestion + '</button>'
                                );
                            });
                        } else {
                            $('#descriptionSuggestions').append('<div class="list-group-item">Nessun suggerimento disponibile.</div>');
                        }
                    }
                });
            } else {
                $('#descriptionSuggestions').empty();
            }
        }

        // Associa la funzione di ricerca dei suggerimenti con debouncing all'input
        $('#description').on('input', debounce(function() {
            searchSuggestions();
        }, 300)); // Ritarda di 300ms

        // Event listener per quando si clicca su un suggerimento
        $(document).on('click', '.suggestion-item', function() {
            var selectedText = $(this).text();
            $('#description').val(selectedText); // Imposta il testo selezionato nell'input
            $('#descriptionSuggestions').empty(); // Nasconde i suggerimenti dopo la selezione
        });
    });
</script>
@endsection
