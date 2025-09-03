<?php

namespace App\Contracts\Services;

interface StatsServiceInterface
{
    /**
     * Ottiene le statistiche mensili di entrate e uscite per un anno specifico
     */
    public function getMonthlyInOutStats(int $year, int $userId): array;

    /**
     * Ottiene le statistiche annuali di entrate e uscite
     */
    public function getYearlyInOutStats(int $userId): array;

    /**
     * Ottiene le statistiche mensili del totale cumulativo per un anno specifico
     */
    public function getMonthlyTotalStats(int $year, int $userId): array;

    /**
     * Ottiene le statistiche annuali del totale cumulativo
     */
    public function getYearlyTotalStats(int $userId): array;
}
