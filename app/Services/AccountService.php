<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionTransfer;
use App\Contracts\Services\AccountServiceInterface;
use Illuminate\Support\Collection;

class AccountService implements AccountServiceInterface
{
    /**
     * Ottiene gli account dell'utente filtrati per tipo
     */
    public function getUserAccountsByType(int $userId, $type): Collection
    {
        if ($type == "999") {
            return Account::where('user_id', $userId)
                ->where(function($query) {
                    $query->where('type', 4)->orWhere('type', 5);
                })->get();
        }

        return Account::where('user_id', $userId)
            ->where('type', $type)
            ->get();
    }

    /**
     * Crea un nuovo account
     */
    public function createAccount(array $data, int $userId): Account
    {
        $data['user_id'] = $userId;
        return Account::create($data);
    }

    /**
     * Ottiene un account con le transazioni caricate e processate
     */
    public function getAccountWithProcessedTransactions(Account $account, int $userId): Account
    {
        // Verifica autorizzazione
        $this->ensureUserOwnsAccount($account, $userId);

        // Carica le transazioni con i conti collegati
        $account->load('transactions.account');

        // Processa le transazioni per identificare i trasferimenti
        $this->processTransferTransactions($account);

        return $account;
    }

    /**
     * Aggiorna un account
     */
    public function updateAccount(Account $account, array $data, int $userId): Account
    {
        // Verifica autorizzazione
        $this->ensureUserOwnsAccount($account, $userId);

        $account->update($data);
        return $account;
    }

    /**
     * Elimina un account (soft delete)
     */
    public function deleteAccount(Account $account, int $userId): void
    {
        // Verifica autorizzazione
        $this->ensureUserOwnsAccount($account, $userId);

        // Controlla se il saldo del conto è pari a zero
        $balance = $account->transactions->sum('amount');
        if ($balance != 0) {
            throw new \Exception('Impossibile eliminare il conto. Prima devi azzerare il saldo.');
        }

        $account->delete();
    }

    /**
     * Ottiene gli account eliminati dell'utente
     */
    public function getDeletedAccounts(int $userId): Collection
    {
        return Account::onlyTrashed()->where('user_id', $userId)->get();
    }

    /**
     * Ripristina un account eliminato
     */
    public function restoreAccount(int $accountId, int $userId): Account
    {
        $account = Account::withTrashed()->findOrFail($accountId);
        
        // Verifica autorizzazione
        $this->ensureUserOwnsAccount($account, $userId);

        $account->restore();
        return $account;
    }

    /**
     * Ottiene tutti gli account dell'utente
     */
    public function getAllUserAccounts(int $userId): Collection
    {
        return Account::where('user_id', $userId)->get();
    }

    /**
     * Processa le transazioni per identificare i trasferimenti
     */
    private function processTransferTransactions(Account $account): void
    {
        $account->transactions->each(function ($transaction) {
            // Cerca la transazione collegata direttamente nella tabella pivot
            $linkedTransaction = TransactionTransfer::where('transaction_id', $transaction->id)
                ->orWhere('linked_transaction_id', $transaction->id)
                ->first();

            if ($linkedTransaction) {
                $linkedTransactionModel = Transaction::find(
                    $linkedTransaction->transaction_id == $transaction->id 
                        ? $linkedTransaction->linked_transaction_id 
                        : $linkedTransaction->transaction_id
                );
                
                if ($transaction->amount < 0) {
                    $transaction->description = 'Trasferimento da ' . $transaction->account->name . ' a ' . $linkedTransactionModel->account->name;
                } else {
                    $transaction->description = 'Ricezione da ' . $linkedTransactionModel->account->name . ' a ' . $transaction->account->name;
                }
            }
        });
    }

    /**
     * Verifica che l'utente possieda l'account
     */
    private function ensureUserOwnsAccount(Account $account, int $userId): void
    {
        if ($account->user_id !== $userId) {
            abort(403, 'Accesso negato');
        }
    }
}
