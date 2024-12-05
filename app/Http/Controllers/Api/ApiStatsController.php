<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Http\Controllers\Controller;

use App\Models\Transaction;
use App\Models\TransactionTransfer;

use Illuminate\Http\Request;

class ApiStatsController extends Controller
{
    
    public function getStatsMonthlyInOut($year)
    {
        // Query SQL per calcolare entrate e uscite mensili escludendo le transazioni interne
        $query = "
            SELECT 
                MONTH(t.created_at) AS month,
                SUM(CASE WHEN t.amount > 0 THEN t.amount ELSE 0 END) AS entrate,
                SUM(CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE 0 END) AS uscite
            FROM transactions t
            LEFT JOIN transaction_transfers tt1 ON t.id = tt1.transaction_id
            LEFT JOIN transaction_transfers tt2 ON t.id = tt2.linked_transaction_id
            WHERE 
                tt1.transaction_id IS NULL
                AND tt2.linked_transaction_id IS NULL
                AND YEAR(t.created_at) = ?
            GROUP BY MONTH(t.created_at)
            ORDER BY MONTH(t.created_at)
        ";

        // Esegui la query con il parametro dell'anno
        $transactions = DB::select($query, [$year]);

        // Inizializza i dati con 12 mesi vuoti
        $stats = [
            'entrate' => array_fill(0, 12, 0),
            'uscite' => array_fill(0, 12, 0),
        ];

        // Popola i dati mese per mese
        foreach ($transactions as $transaction) {
            $monthIndex = $transaction->month - 1; // Indici da 0 (gennaio) a 11 (dicembre)
            $stats['entrate'][$monthIndex] = $transaction->entrate;
            $stats['uscite'][$monthIndex] = $transaction->uscite;
        }

        // Restituisci i dati come JSON
        return response()->json($stats);
    }

    public function getStatsYearlyInOut()
    {
        // Query SQL per calcolare entrate e uscite annuali escludendo le transazioni interne
        $query = "
            SELECT 
                YEAR(t.created_at) AS year,
                SUM(CASE WHEN t.amount > 0 THEN t.amount ELSE 0 END) AS entrate,
                SUM(CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE 0 END) AS uscite
            FROM transactions t
            LEFT JOIN transaction_transfers tt1 ON t.id = tt1.transaction_id
            LEFT JOIN transaction_transfers tt2 ON t.id = tt2.linked_transaction_id
            WHERE 
                tt1.transaction_id IS NULL
                AND tt2.linked_transaction_id IS NULL
            GROUP BY YEAR(t.created_at)
            ORDER BY YEAR(t.created_at)
        ";
    
        // Esegui la query
        $transactions = DB::select($query);

        // Inizializza gli array per entrate e uscite
        $stats = [
            'entrate' => [],
            'uscite' => [],
        ];

        if(empty($transactions)){
            return response()->json($stats);
        }

        // Popola i dati per ogni anno
        foreach ($transactions as $transaction) {
            $yearIndex = $transaction->year; // Usa l'anno come indice
            $stats['entrate'][$yearIndex] = $transaction->entrate;
            $stats['uscite'][$yearIndex] = $transaction->uscite;
        }

        // Assicura che tutti gli anni intermedi siano inclusi con valori di default (0)
        $minYear = min(array_keys($stats['entrate']));
        $maxYear = max(array_keys($stats['entrate']));

        for ($year = $minYear; $year <= $maxYear; $year++) {
            if (!isset($stats['entrate'][$year])) {
                $stats['entrate'][$year] = 0;
            }
            if (!isset($stats['uscite'][$year])) {
                $stats['uscite'][$year] = 0;
            }
        }

        // Restituisci i dati come JSON
        return response()->json($stats);
    }

    public function getStatsMonthlyTotal($year)
    {
        // Query SQL per ottenere tutte le transazioni fino alla fine dell'anno richiesto
        $query = "
            SELECT 
                YEAR(t.created_at) AS year,
                MONTH(t.created_at) AS month,
                SUM(t.amount) AS totale
            FROM transactions t
            LEFT JOIN transaction_transfers tt1 ON t.id = tt1.transaction_id
            LEFT JOIN transaction_transfers tt2 ON t.id = tt2.linked_transaction_id
            WHERE 
                tt1.transaction_id IS NULL
                AND tt2.linked_transaction_id IS NULL
                AND YEAR(t.created_at) <= ?
            GROUP BY YEAR(t.created_at), MONTH(t.created_at)
            ORDER BY YEAR(t.created_at), MONTH(t.created_at)
        ";

        // Esegui la query con il parametro dell'anno
        $transactions = DB::select($query, [$year]);

        // Inizializza i dati con 12 mesi vuoti (0 per tutti i mesi da gennaio a dicembre)
        $stats = array_fill(0, 12, "0.00"); // Valori iniziali come stringhe per mantenere il formato JSON

        // Variabile per il totale cumulativo
        $cumulativeTotal = 0;

        // Popola i dati mese per mese
        foreach ($transactions as $transaction) {
            // Calcola il totale cumulativo fino al mese corrente
            $cumulativeTotal += $transaction->totale;

            // Aggiorna il saldo cumulativo per il mese corrente (solo per l'anno richiesto)
            if ($transaction->year == $year) {
                $monthIndex = $transaction->month - 1; // Indici da 0 (gennaio) a 11 (dicembre)
                $stats[$monthIndex] = number_format($cumulativeTotal, 2, '.', ''); // Formattato con 2 decimali
            }
        }

        // Restituisci i dati nel formato desiderato
        return response()->json(['totale' => $stats]);
    }

    public function getStatsYearlyTotal()
    {
        // Query SQL per ottenere tutte le transazioni fino all'anno corrente
        $query = "
            SELECT 
                YEAR(t.created_at) AS year,
                SUM(t.amount) AS totale
            FROM transactions t
            LEFT JOIN transaction_transfers tt1 ON t.id = tt1.transaction_id
            LEFT JOIN transaction_transfers tt2 ON t.id = tt2.linked_transaction_id
            WHERE 
                tt1.transaction_id IS NULL
                AND tt2.linked_transaction_id IS NULL
            GROUP BY YEAR(t.created_at)
            ORDER BY YEAR(t.created_at)
        ";

        // Esegui la query
        $transactions = DB::select($query);

        // Inizializza il totale cumulativo e l'array dei risultati
        $cumulativeTotal = 0;
        $stats = [];

        // Popola i dati anno per anno
        foreach ($transactions as $transaction) {
            // Calcola il totale cumulativo
            $cumulativeTotal += $transaction->totale;

            // Aggiungi il saldo cumulativo per l'anno corrente
            $stats[$transaction->year] = $cumulativeTotal;
        }

        // Restituisci i dati come JSON
        return response()->json(['totale' => $stats]);
    }


}

