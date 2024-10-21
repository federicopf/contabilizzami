@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Aggiungi un Nuovo Conto</h2>

            <form action="{{ route('conti.store') }}" method="POST">
                @csrf

                <!-- Nome del Conto -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nome del Conto</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Inserisci il nome del conto" required>
                </div>

                <!-- Tipo di Conto -->
                @if($type == 999)
                    <div class="mb-3">
                    <label for="type" class="form-label">Tipo di Conto</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Seleziona tipo di conto</option>
                        <option value="4">Debito</option>
                        <option value="5">Credito</option>
                    </select>
                    </div>
                @else
                    <input type="hidden" name="type" id="type" value="{{ $type }}">
                @endif

                <!-- Pulsante di Invio -->
                <button type="submit" class="btn btn-primary">Aggiungi Conto</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Indietro</a>
            </form>
        </div>
    </div>
</div>
@endsection
