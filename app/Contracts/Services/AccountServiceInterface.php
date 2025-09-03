<?php

namespace App\Contracts\Services;

use App\Models\Account;
use Illuminate\Support\Collection;

interface AccountServiceInterface
{
    /**
     * Ottiene gli account dell'utente filtrati per tipo
     */
    public function getUserAccountsByType(int $userId, $type): Collection;

    /**
     * Crea un nuovo account
     */
    public function createAccount(array $data, int $userId): Account;

    /**
     * Ottiene un account con le transazioni caricate e processate
     */
    public function getAccountWithProcessedTransactions(Account $account, int $userId): Account;

    /**
     * Aggiorna un account
     */
    public function updateAccount(Account $account, array $data, int $userId): Account;

    /**
     * Elimina un account (soft delete)
     */
    public function deleteAccount(Account $account, int $userId): void;

    /**
     * Ottiene gli account eliminati dell'utente
     */
    public function getDeletedAccounts(int $userId): Collection;

    /**
     * Ripristina un account eliminato
     */
    public function restoreAccount(int $accountId, int $userId): Account;

    /**
     * Ottiene tutti gli account dell'utente
     */
    public function getAllUserAccounts(int $userId): Collection;
}
