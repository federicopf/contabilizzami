<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
}
