@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Modifica Conto</h2>

            <form action="{{ route('conti.update', ['account' => $account->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nome del Conto -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nome del Conto</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $account->name }}" required>
                </div>

                <!-- Tipo di Conto -->
                @if($account->type == 4 || $account->type == 5)
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo di Conto</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="4" {{ $account->type == 4 ? 'selected' : '' }}>Debito</option>
                            <option value="5" {{ $account->type == 5 ? 'selected' : '' }}>Credito</option>
                        </select>
                    </div>
                @else
                    <input type="hidden" name="type" value="{{ $account->type }}">
                @endif

                <!-- Pulsante di Salvataggio -->
                <button type="submit" class="btn btn-primary">Salva Modifiche</button>

                <!-- Pulsante per tornare indietro -->
                <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Indietro</a>
            </form>
        </div>
    </div>
</div>
@endsection
