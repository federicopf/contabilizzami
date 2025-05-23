<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionTransfer;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type)
    {
        switch($type){
            case "999" : 
                $accounts = Account::where('user_id', auth()->id())
                    ->where(function($query) {
                        $query->where('type', 4)->orWhere('type', 5);
                    })->get();
                break;
            default : 
                $accounts = Account::where('user_id', auth()->id())
                    ->where('type', $type)
                    ->get();
                break;
        }
        
        return view(
            'conti.index', 
            compact(
                'accounts',
                'type'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($type)
    {
        return view(
            'conti.create',
            compact(
                'type'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2,3,4,5', // Deve essere uno dei tipi definiti
        ]);

        $validated['user_id'] = auth()->id();
        Account::create($validated);

        if($validated['type'] == 4 || $validated['type'] == 5){
            $validated['type'] = 999;
        }

        return redirect()->route('conti.index', ['type' => $validated['type']])
                         ->with('success', 'Conto creato con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        // Assicura che l'utente possa visualizzare solo i propri conti
        if ($account->user_id !== auth()->id()) {
            abort(403, 'Accesso negato');
        }

        $type = $account->type;
        
        if($account->type == 4 || $account->type == 5){
            $type = 999;
        }

        // Carica le transazioni con i conti collegati per identificare i trasferimenti
        $account->load('transactions.account');

        // Modifica ogni transazione per aggiungere una descrizione specifica in caso di trasferimento
        $account->transactions->each(function ($transaction) {
            // Cerca la transazione collegata direttamente nella tabella pivot
            $linkedTransaction = TransactionTransfer::where('transaction_id', $transaction->id)
                ->orWhere('linked_transaction_id', $transaction->id)
                ->first();

            if ($linkedTransaction) {
                $linkedTransactionModel = Transaction::find($linkedTransaction->transaction_id == $transaction->id ? $linkedTransaction->linked_transaction_id : $linkedTransaction->transaction_id);
                
                if ($transaction->amount < 0) {
                    $transaction->description = 'Trasferimento da ' . $transaction->account->name . ' a ' . $linkedTransactionModel->account->name;
                } else {
                    $transaction->description = 'Ricezione da ' . $linkedTransactionModel->account->name . ' a ' . $transaction->account->name;
                }
            }
        });

        $accounts = Account::where('user_id', auth()->id())->get();
        
        return view('conti.show', compact('account', 'type', 'accounts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        // Assicura che l'utente possa modificare solo i propri conti
        if ($account->user_id !== auth()->id()) {
            abort(403, 'Accesso negato');
        }

        return view('conti.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        // Assicura che l'utente possa aggiornare solo i propri conti
        if ($account->user_id !== auth()->id()) {
            abort(403, 'Accesso negato');
        }

        // Valida i dati del form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2,3,4,5',
        ]);

        // Aggiorna i dati del conto
        $account->update($validated);

        // Reindirizza alla pagina dei dettagli del conto con un messaggio di successo
        return redirect()->route('conti.show', ['account' => $account->id])
                        ->with('success', 'Conto aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        // Assicura che l'utente possa eliminare solo i propri conti
        if ($account->user_id !== auth()->id()) {
            abort(403, 'Accesso negato');
        }

        // Controlla se il saldo del conto è pari a zero
        $balance = $account->transactions->sum('amount');
        if ($balance != 0) {
            return redirect()->back()->withErrors('Impossibile eliminare il conto. Prima devi azzerare il saldo.');
        }

        // Elimina il conto (soft delete)
        $account->delete();

        // Reindirizza alla pagina principale dei conti con un messaggio di successo
        return redirect()->route('conti.index', ['type' => $account->type])
                        ->with('success', 'Conto eliminato con successo!');
    }

    public function deleted()
    {
        // Recupera solo i conti eliminati (soft deleted) dell'utente corrente
        $deletedAccounts = Account::onlyTrashed()->where('user_id', auth()->id())->get();

        // Ritorna la vista con i conti eliminati
        return view('conti.deleted', compact('deletedAccounts'));
    }
    
    public function restore($id)
    {
        // Trova il conto eliminato tramite il soft delete
        $account = Account::withTrashed()->findOrFail($id);

        // Assicura che l'utente possa ripristinare solo i propri conti
        if ($account->user_id !== auth()->id()) {
            abort(403, 'Accesso negato');
        }

        // Ripristina il conto
        $account->restore();

        return redirect()->route('conti.deleted')
                        ->with('success', 'Conto ripristinato con successo!');
    }
}
