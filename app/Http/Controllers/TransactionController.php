<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionTransfer;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Remove the specified transaction from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        // Assicura che l'utente possa eliminare solo transazioni relative ai propri conti
        if ($transaction->account->user_id !== auth()->id()) {
            abort(403, 'Accesso negato');
        }

        // Controlla se esiste una transazione collegata tramite la tabella pivot e cancellala
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

        // Elimina la transazione
        $transaction->delete();

        // Reindirizza alla pagina precedente con un messaggio di successo
        return redirect()->back()->with('success', 'Transazione eliminata con successo!');
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valida i dati della richiesta
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        // Assicura che l'utente possa aggiungere transazioni solo ai propri conti
        $account = Account::findOrFail($data['account_id']);
        if ($account->user_id !== auth()->id()) {
            abort(403, 'Accesso negato');
        }

        // Crea la transazione
        Transaction::create($data);

        // Reindirizza alla pagina precedente con un messaggio di successo
        return redirect()->back()->with('success', 'Transazione creata con successo!');
    }

    /**
     * Store a newly created transfer between accounts in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
        // Valida i dati della richiesta
        $data = $request->validate([
            'account_from_id' => 'required|exists:accounts,id',
            'account_to_id' => 'required|exists:accounts,id|different:account_from_id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Recupera gli account coinvolti e assicura che appartengano all'utente
        $accountFrom = Account::findOrFail($data['account_from_id']);
        $accountTo = Account::findOrFail($data['account_to_id']);

        if ($accountFrom->user_id !== auth()->id() || $accountTo->user_id !== auth()->id()) {
            abort(403, 'Accesso negato');
        }

        // Crea la transazione di uscita per l'account di origine
        $transactionFrom = Transaction::create([
            'account_id' => $accountFrom->id,
            'description' => '',
            'amount' => -$data['amount'],
        ]);

        // Crea la transazione di entrata per l'account di destinazione
        $transactionTo = Transaction::create([
            'account_id' => $accountTo->id,
            'description' => 'Ricezione da ' . $accountFrom->name,
            'amount' => $data['amount'],
        ]);

        // Collega le due transazioni come trasferimento
        TransactionTransfer::insert([
            'transaction_id' => $transactionFrom->id,
            'linked_transaction_id' => $transactionTo->id,
        ]);

        // Reindirizza alla pagina precedente con un messaggio di successo
        return redirect()->back()->with('success', 'Trasferimento creato con successo!');
    }

    public function suggestions(Request $request)
    {
        // Valida l'input della query
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        // Cerca descrizioni che iniziano con il testo inserito dall'utente
        $suggestions = Transaction::where('description', 'like', $request->query('query') . '%')
                                ->pluck('description') // Ottieni solo le descrizioni
                                ->unique() // Rimuovi duplicati
                                ->take(3); // Limita i risultati a 5 suggerimenti

        // Restituisci i suggerimenti come array JSON
        return response()->json([
            'suggestions' => $suggestions
        ]);
    }

}
