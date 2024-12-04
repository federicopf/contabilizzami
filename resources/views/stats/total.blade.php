@extends('layouts.app')

@section('content')

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

@vite('resources/js/charts/total.js')

<script type="module">
    
</script>

@endsection
