@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Seconda sezione: statistiche mensili e annuali -->
    <h4>Statistiche totale saldo</h4>
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
                    <canvas id="monthlyTotalChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistiche annuali -->
        <div class="col-md-6">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h5 class="card-title text-center">Statistiche Annuali</h5>
                    <canvas id="yearlyTotalChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/charts/total.js')

<script type="module">

    
let currentYear = new Date().getFullYear();
    $(document).ready(function () {
        //ON LAUNCH
        initMonthlyStats();
        initYearlyStats();

        //BINDIGS 
        $('#currentYear').text(currentYear);

        $('#prevYear').click(function () {
            changeYearMonthlyStatsChart(-1);
        });

        $('#nextYear').click(function () {
            changeYearMonthlyStatsChart(1);
        });

        //FUNCTIONS
        function initYearlyStats(){
            updateYearlyStatsChart();
        }

        function initMonthlyStats(){
            changeYearMonthlyStatsChart(0);
        }

        function changeYearMonthlyStatsChart(direction) {
            currentYear += direction;
            $('#currentYear').text(currentYear);

            updateMonthlyStatsChart(currentYear);
        }

        function updateMonthlyStatsChart(year) {
            $.ajax({
                url: `/api/statstotal/monthly/${year}`,
                method: 'GET',
                success: function (response) {
                    // Aggiorna i dati del grafico
                    const chart = window.monthlyTotalChart;

                    if (chart) {
                        chart.data.datasets[0].data = response.totale;
                        chart.update();
                    }
                },
                error: function (xhr) {
                    console.error('Errore nel recupero dei dati:', xhr.responseJSON?.error || xhr.statusText);
                }
            });
        }

        function updateYearlyStatsChart() {
            $.ajax({
                url: `/api/statstotal/yearly`,
                method: 'GET',
                success: function (response) {
                    // Aggiorna i dati del grafico
                    const chart = window.yearlyTotalChart;

                    if (chart) {
                        chart.data.datasets[0].data = response.totale;
                        chart.update();
                    }
                },
                error: function (xhr) {
                    console.error('Errore nel recupero dei dati:', xhr.responseJSON?.error || xhr.statusText);
                }
            });
        }
    });
</script>

@endsection
