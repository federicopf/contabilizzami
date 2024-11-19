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
                    <h5 class="card-title text-center">
                        <button id="prevYear" class="btn btn-link">
                            <i class="bi bi-arrow-left-circle"></i> <!-- Freccia sinistra -->
                        </button>
        
                        <span id="currentYear">2024</span> <!-- Mostra l'anno corrente -->
        
                        <button id="nextYear" class="btn btn-link">
                            <i class="bi bi-arrow-right-circle"></i> <!-- Freccia destra -->
                        </button>
                    </h5>
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

</div>

@vite('resources/js/charts/home.js')

<script type="module">

    let currentYear = new Date().getFullYear();
    $(document).ready(function () {
        //ON LAUNCH
        changeYear(0);

        //BINDIGS 
        $('#currentYear').text(currentYear);

        $('#prevYear').click(function () {
            changeYear(-1);
        });

        $('#nextYear').click(function () {
            changeYear(1);
        });

        //FUNCTIONS
        function changeYear(direction) {
            currentYear += direction;
            $('#currentYear').text(currentYear);

            updateMonthlyStatsChart(currentYear);
        }

        function updateMonthlyStatsChart(year) {
            const newData = {
                2023: {
                    entrate: [1000, 1100, 1500, 1200, 1600, 2000, 2100, 2200, 2000, 1900, 2500, 2400],
                    uscite: [800, 850, 700, 900, 1000, 1300, 1400, 1100, 1200, 1250, 1100, 1200]
                },
                2024: {
                    entrate: [1200, 1500, 1700, 1300, 1900, 2200, 2100, 1800, 2000, 1900, 2400, 2300],
                    uscite: [900, 1000, 800, 1200, 1300, 1400, 1100, 950, 1200, 1300, 1000, 1050]
                }
            };

            if (newData[year]) {
                const chart = window.monthlyStatsChart;

                if (chart) {
                    chart.data.datasets[0].data = newData[year].entrate;
                    chart.data.datasets[1].data = newData[year].uscite;
                    chart.update();
                } else {
                    console.error('Il grafico non è stato inizializzato.');
                }
            } else {
                console.error(`Nessun dato trovato per l'anno ${year}`);
            }
        }
    });
    
</script>
@endsection
