<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\TransactionTransfer;
use App\Contracts\Services\TransactionServiceInterface;
use Illuminate\Support\Collection;

class TransactionService implements TransactionServiceInterface
{
    /**
     * Elimina una transazione e la sua transazione collegata se presente
     */
    public function deleteTransaction(Transaction $transaction, int $userId): void
    {
        // Verifica autorizzazione
        $this->ensureUserOwnsTransaction($transaction, $userId);

        // Elimina la transazione collegata se presente
        $this->deleteLinkedTransaction($transaction);

        // Elimina la transazione
        $transaction->delete();
    }

    /**
     * Crea una nuova transazione
     */
    public function createTransaction(array $data, int $userId): Transaction
    {
        // Verifica autorizzazione
        $account = Account::findOrFail($data['account_id']);
        $this->ensureUserOwnsAccount($account, $userId);

        return Transaction::create($data);
    }

    /**
     * Crea un trasferimento tra due conti
     */
    public function createTransfer(array $data, int $userId): array
    {
        // Recupera gli account coinvolti e verifica autorizzazione
        $accountFrom = Account::findOrFail($data['account_from_id']);
        $accountTo = Account::findOrFail($data['account_to_id']);

        $this->ensureUserOwnsAccount($accountFrom, $userId);
        $this->ensureUserOwnsAccount($accountTo, $userId);

        // Crea la transazione di uscita
        $transactionFrom = Transaction::create([
            'account_id' => $accountFrom->id,
            'description' => '',
            'amount' => -$data['amount'],
        ]);

        // Crea la transazione di entrata
        $transactionTo = Transaction::create([
            'account_id' => $accountTo->id,
            'description' => 'Ricezione da ' . $accountFrom->name,
            'amount' => $data['amount'],
        ]);

        // Collega le due transazioni
        TransactionTransfer::insert([
            'transaction_id' => $transactionFrom->id,
            'linked_transaction_id' => $transactionTo->id,
        ]);

        return [
            'transaction_from' => $transactionFrom,
            'transaction_to' => $transactionTo
        ];
    }

    /**
     * Ottiene suggerimenti per le descrizioni delle transazioni
     */
    public function getDescriptionSuggestions(string $query): Collection
    {
        return Transaction::where('description', 'like', $query . '%')
            ->pluck('description')
            ->unique()
            ->take(3);
    }

    /**
     * Elimina la transazione collegata se presente
     */
    private function deleteLinkedTransaction(Transaction $transaction): void
    {
        $linkedTransactionId = TransactionTransfer::where('transaction_id', $transaction->id)
            ->orWhere('linked_transaction_id', $transaction->id)
            ->value('transaction_id') == $transaction->id
            ? TransactionTransfer::where('transaction_id', $transaction->id)->value('linked_transaction_id')
            : TransactionTransfer::where('linked_transaction_id', $transaction->id)->value('transaction_id');

        if ($linkedTransactionId) {
            $linkedTransaction = Transaction::find($linkedTransactionId);
            if ($linkedTransaction) {
                $linkedTransaction->delete();
            }
        }
    }

    /**
     * Verifica che l'utente possieda la transazione
     */
    private function ensureUserOwnsTransaction(Transaction $transaction, int $userId): void
    {
        if ($transaction->account->user_id !== $userId) {
            abort(403, 'Accesso negato');
        }
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
