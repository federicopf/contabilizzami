<?php

namespace App\Contracts\Services;

interface DashboardServiceInterface
{
    /**
     * Ottiene i dati per la dashboard dell'utente
     */
    public function getDashboardData(int $userId): array;
}
