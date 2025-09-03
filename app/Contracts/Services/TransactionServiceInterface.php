<?php

namespace App\Contracts\Services;

use App\Models\Transaction;
use Illuminate\Support\Collection;

interface TransactionServiceInterface
{
    /**
     * Elimina una transazione e la sua transazione collegata se presente
     */
    public function deleteTransaction(Transaction $transaction, int $userId): void;

    /**
     * Crea una nuova transazione
     */
    public function createTransaction(array $data, int $userId): Transaction;

    /**
     * Crea un trasferimento tra due conti
     */
    public function createTransfer(array $data, int $userId): array;

    /**
     * Ottiene suggerimenti per le descrizioni delle transazioni
     */
    public function getDescriptionSuggestions(string $query): Collection;
}
