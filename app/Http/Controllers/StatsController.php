<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function getStats($year)
    {
        // Simula i dati (sostituisci con la logica del database, se necessario)
        $data = [
            2023 => [
                'entrate' => [1000, 1100, 1500, 1200, 1600, 2000, 2100, 2200, 2000, 1900, 2500, 2400],
                'uscite' => [800, 850, 700, 900, 1000, 1300, 1400, 1100, 1200, 1250, 1100, 1200],
            ],
            2024 => [
                'entrate' => [1200, 1500, 1700, 1300, 1900, 2200, 2100, 1800, 2000, 1900, 2400, 2300],
                'uscite' => [900, 1000, 800, 1200, 1300, 1400, 1100, 950, 1200, 1300, 1000, 1050],
            ],
        ];

        // Restituisci i dati come JSON
        if (array_key_exists($year, $data)) {
            return response()->json($data[$year]);
        }

        return response()->json(['error' => 'Dati non trovati per l\'anno specificato.'], 404);
    }
}

