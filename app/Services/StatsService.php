<?php

namespace App\Services;

use App\Contracts\Services\StatsServiceInterface;
use Illuminate\Support\Facades\DB;

class StatsService implements StatsServiceInterface
{
    /**
     * Ottiene le statistiche mensili di entrate e uscite per un anno specifico
     */
    public function getMonthlyInOutStats(int $year, int $userId): array
    {
        $query = "
            SELECT 
                MONTH(t.created_at) AS month,
                SUM(CASE WHEN t.amount > 0 THEN t.amount ELSE 0 END) AS entrate,
                SUM(CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE 0 END) AS uscite
            FROM transactions t
            INNER JOIN accounts a ON t.account_id = a.id
            LEFT JOIN transaction_transfers tt1 ON t.id = tt1.transaction_id
            LEFT JOIN transaction_transfers tt2 ON t.id = tt2.linked_transaction_id
            WHERE 
                tt1.transaction_id IS NULL
                AND tt2.linked_transaction_id IS NULL
                AND YEAR(t.created_at) = ?
                AND a.user_id = ?
            GROUP BY MONTH(t.created_at)
            ORDER BY MONTH(t.created_at)
        ";

        $transactions = DB::select($query, [$year, $userId]);

        $stats = [
            'entrate' => array_fill(0, 12, 0),
            'uscite' => array_fill(0, 12, 0),
        ];

        foreach ($transactions as $transaction) {
            $monthIndex = $transaction->month - 1;
            $stats['entrate'][$monthIndex] = $transaction->entrate;
            $stats['uscite'][$monthIndex] = $transaction->uscite;
        }

        return $stats;
    }

    /**
     * Ottiene le statistiche annuali di entrate e uscite
     */
    public function getYearlyInOutStats(int $userId): array
    {
        $query = "
            SELECT 
                YEAR(t.created_at) AS year,
                SUM(CASE WHEN t.amount > 0 THEN t.amount ELSE 0 END) AS entrate,
                SUM(CASE WHEN t.amount < 0 THEN ABS(t.amount) ELSE 0 END) AS uscite
            FROM transactions t
            INNER JOIN accounts a ON t.account_id = a.id
            LEFT JOIN transaction_transfers tt1 ON t.id = tt1.transaction_id
            LEFT JOIN transaction_transfers tt2 ON t.id = tt2.linked_transaction_id
            WHERE 
                tt1.transaction_id IS NULL
                AND tt2.linked_transaction_id IS NULL
                AND a.user_id = ?
            GROUP BY YEAR(t.created_at)
            ORDER BY YEAR(t.created_at)
        ";

        $transactions = DB::select($query, [$userId]);

        $stats = [
            'entrate' => [],
            'uscite' => [],
        ];

        if (empty($transactions)) {
            return $stats;
        }

        foreach ($transactions as $transaction) {
            $yearIndex = $transaction->year;
            $stats['entrate'][$yearIndex] = $transaction->entrate;
            $stats['uscite'][$yearIndex] = $transaction->uscite;
        }

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

        return $stats;
    }

    /**
     * Ottiene le statistiche mensili del totale cumulativo per un anno specifico
     */
    public function getMonthlyTotalStats(int $year, int $userId): array
    {
        $query = "
            SELECT 
                YEAR(t.created_at) AS year,
                MONTH(t.created_at) AS month,
                SUM(t.amount) AS totale
            FROM transactions t
            INNER JOIN accounts a ON t.account_id = a.id
            LEFT JOIN transaction_transfers tt1 ON t.id = tt1.transaction_id
            LEFT JOIN transaction_transfers tt2 ON t.id = tt2.linked_transaction_id
            WHERE 
                tt1.transaction_id IS NULL
                AND tt2.linked_transaction_id IS NULL
                AND YEAR(t.created_at) <= ?
                AND a.user_id = ?
            GROUP BY YEAR(t.created_at), MONTH(t.created_at)
            ORDER BY YEAR(t.created_at), MONTH(t.created_at)
        ";

        $transactions = DB::select($query, [$year, $userId]);

        $stats = array_fill(0, 12, "0.00");
        $cumulativeTotal = 0;

        foreach ($transactions as $transaction) {
            $cumulativeTotal += $transaction->totale;

            if ($transaction->year == $year) {
                $monthIndex = $transaction->month - 1;
                $stats[$monthIndex] = number_format($cumulativeTotal, 2, '.', '');
            }
        }

        return ['totale' => $stats];
    }

    /**
     * Ottiene le statistiche annuali del totale cumulativo
     */
    public function getYearlyTotalStats(int $userId): array
    {
        $query = "
            SELECT 
                YEAR(t.created_at) AS year,
                SUM(t.amount) AS totale
            FROM transactions t
            INNER JOIN accounts a ON t.account_id = a.id
            LEFT JOIN transaction_transfers tt1 ON t.id = tt1.transaction_id
            LEFT JOIN transaction_transfers tt2 ON t.id = tt2.linked_transaction_id
            WHERE 
                tt1.transaction_id IS NULL
                AND tt2.linked_transaction_id IS NULL
                AND a.user_id = ?
            GROUP BY YEAR(t.created_at)
            ORDER BY YEAR(t.created_at)
        ";

        $transactions = DB::select($query, [$userId]);

        $cumulativeTotal = 0;
        $stats = [];

        foreach ($transactions as $transaction) {
            $cumulativeTotal += $transaction->totale;
            $stats[$transaction->year] = $cumulativeTotal;
        }

        return ['totale' => $stats];
    }
}
