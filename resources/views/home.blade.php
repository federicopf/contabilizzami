@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h4 class="mb-4">
                Ciao {{ Auth::user()->name }}, ecco una panoramica delle tue finanze!
            </h4>
        </div>
    </div>

    <!-- Prima sezione: saldo totale e suddivisione conti -->
    <div class="row justify-content-center mb-4">
        <!-- Saldo totale -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Saldo Totale</h5>
                    <h2>€ {{ number_format($totalBalance, 2, ',', '.') }}</h2>
                    <p class="card-text">La somma totale dei tuoi conti attivi.</p>
                </div>
            </div>
        </div>

        <!-- Suddivisione conti -->
        <div class="col-md-4">
            <div class="card bg-light mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Suddivisione per Tipologia</h5>
                    <ul class="list-group">
                        @foreach($accountTypes as $type => $balance)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Conto {{ $type }}
                                <span class="badge bg-primary rounded-pill">€ {{ number_format($balance, 2, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Transazioni recenti -->
        <div class="col-md-4">
            <div class="card bg-light mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Transazioni Recenti</h5>
                    <ul class="list-group">
                        @forelse($recentTransactions as $transaction)
                            <li class="list-group-item">
                                {{ $transaction->amount > 0 ? '+' : '-' }} € {{ number_format(abs($transaction->amount), 2, ',', '.') }} 
                                | {{ $transaction->description }}
                                
                                @if(!$transaction->linked)
                                | {{ $transaction->account->name }}
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item">Nessuna transazione recente</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Seconda sezione: statistiche mensili e annuali -->
    <div class="row justify-content-center mb-4">
        <!-- Statistiche mensili -->
        <div class="col-md-6">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h5 class="card-title text-center">Statistiche Mensili</h5>
                    <canvas id="monthlyStatsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistiche annuali -->
        <div class="col-md-6">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h5 class="card-title text-center">Statistiche Annuali</h5>
                    <canvas id="annualStatsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Sezione Obiettivi Finanziari (Opzionale) -->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h5 class="card-title text-center">Obiettivi Finanziari</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Raggiungi un saldo di €10,000 nel conto risparmio
                            <span class="badge bg-warning rounded-pill">75% Completo</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Ripaga il debito di €3,000
                            <span class="badge bg-success rounded-pill">50% Completo</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

@vite('resources/js/charts.js')
@endsection
