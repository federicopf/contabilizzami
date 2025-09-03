<?php

namespace App\Services;

use App\Contracts\Services\DashboardServiceInterface;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionTransfer;
use Illuminate\Support\Collection;

class DashboardService implements DashboardServiceInterface
{
    /**
     * Ottiene i dati per la dashboard dell'utente
     */
    public function getDashboardData(int $userId): array
    {
        // Recupera i conti dell'utente con le transazioni
        $accounts = Account::where('user_id', $userId)
            ->with('transactions')
            ->get();

        // Calcola il saldo totale
        $totalBalance = $accounts->sum(function ($account) {
            return $account->transactions->sum('amount');
        });

        // Calcola i saldi per tipo di account
        $accountTypes = $this->calculateAccountTypeBalances($accounts);

        // Recupera le ultime transazioni
        $recentTransactions = $this->getRecentTransactions($userId);

        return [
            'totalBalance' => $totalBalance,
            'accountTypes' => $accountTypes,
            'recentTransactions' => $recentTransactions
        ];
    }

    /**
     * Calcola i saldi per tipo di account
     */
    private function calculateAccountTypeBalances(Collection $accounts): array
    {
        $accountTypes = [];
        $debitoCreditoBalance = 0;

        foreach (Account::TYPES as $typeId => $typeName) {
            $balance = $accounts->where('type', $typeId)
                ->sum(fn($account) => $account->transactions->sum('amount'));

            if ($typeName === 'Debito' || $typeName === 'Credito') {
                $debitoCreditoBalance += $balance;
            } else {
                $accountTypes[$typeName] = $balance;
            }
        }

        // Aggiungi il saldo combinato di Debito/Credito
        $accountTypes['Debito/Credito'] = $debitoCreditoBalance;

        return $accountTypes;
    }

    /**
     * Ottiene le ultime transazioni dell'utente
     */
    private function getRecentTransactions(int $userId): Collection
    {
        $recentTransactions = Transaction::whereHas('account', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('account')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Processa le transazioni per identificare i trasferimenti
        $this->processTransferTransactions($recentTransactions);

        return $recentTransactions;
    }

    /**
     * Processa le transazioni per identificare i trasferimenti
     */
    private function processTransferTransactions(Collection $transactions): void
    {
        $transactions->each(function ($transaction) {
            $transaction->linked = 0;

            $linkedTransaction = TransactionTransfer::where('transaction_id', $transaction->id)
                ->orWhere('linked_transaction_id', $transaction->id)
                ->first();

            if ($linkedTransaction) {
                $linkedTransactionModel = Transaction::find(
                    $linkedTransaction->transaction_id == $transaction->id 
                        ? $linkedTransaction->linked_transaction_id 
                        : $linkedTransaction->transaction_id
                );
                
                $transaction->linked = 1;

                if ($transaction->amount < 0) {
                    $transaction->description = 'Trasferimento da ' . $transaction->account->name . ' a ' . $linkedTransactionModel->account->name;
                } else {
                    $transaction->description = 'Ricezione da ' . $linkedTransactionModel->account->name . ' a ' . $transaction->account->name;
                }
            }
        });
    }
}
