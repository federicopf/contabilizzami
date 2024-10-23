<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
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

        // Recupera gli account coinvolti
        $accountFrom = Account::findOrFail($data['account_from_id']);
        $accountTo = Account::findOrFail($data['account_to_id']);

        // Crea la transazione di uscita per l'account di origine
        $transactionFrom = Transaction::create([
            'account_id' => $accountFrom->id,
            'description' => 'Trasferimento a ' . $accountTo->name,
            'amount' => -$data['amount'],
        ]);

        // Crea la transazione di entrata per l'account di destinazione
        $transactionTo = Transaction::create([
            'account_id' => $accountTo->id,
            'description' => 'Ricezione da ' . $accountFrom->name,
            'amount' => $data['amount'],
        ]);

        // Collega le due transazioni come trasferimento
        $transactionFrom->linkedTransactions()->attach($transactionTo->id);

        // Reindirizza alla pagina precedente con un messaggio di successo
        return redirect()->back()->with('success', 'Trasferimento creato con successo!');
    }
}