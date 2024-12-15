<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionTransfer;
use Illuminate\Support\Facades\Auth;

class ApiStatsController extends Controller
{
    public function getStatsMonthlyInOut($year)
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

        $transactions = DB::select($query, [$year, Auth::id()]);

        $stats = [
            'entrate' => array_fill(0, 12, 0),
            'uscite' => array_fill(0, 12, 0),
        ];

        foreach ($transactions as $transaction) {
            $monthIndex = $transaction->month - 1;
            $stats['entrate'][$monthIndex] = $transaction->entrate;
            $stats['uscite'][$monthIndex] = $transaction->uscite;
        }

        return response()->json($stats);
    }

    public function getStatsYearlyInOut()
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

        $transactions = DB::select($query, [Auth::id()]);

        $stats = [
            'entrate' => [],
            'uscite' => [],
        ];

        if (empty($transactions)) {
            return response()->json($stats);
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

        return response()->json($stats);
    }

    public function getStatsMonthlyTotal($year)
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

        $transactions = DB::select($query, [$year, Auth::id()]);

        $stats = array_fill(0, 12, "0.00");
        $cumulativeTotal = 0;

        foreach ($transactions as $transaction) {
            $cumulativeTotal += $transaction->totale;

            if ($transaction->year == $year) {
                $monthIndex = $transaction->month - 1;
                $stats[$monthIndex] = number_format($cumulativeTotal, 2, '.', '');
            }
        }

        return response()->json(['totale' => $stats]);
    }

    public function getStatsYearlyTotal()
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

        $transactions = DB::select($query, [Auth::id()]);

        $cumulativeTotal = 0;
        $stats = [];

        foreach ($transactions as $transaction) {
            $cumulativeTotal += $transaction->totale;
            $stats[$transaction->year] = $cumulativeTotal;
        }

        return response()->json(['totale' => $stats]);
    }
}
