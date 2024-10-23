<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Account;
use App\Models\Transaction;
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
                $accounts = Account::where('type','=',4)->orWhere('type','=',5)->get();
                break;
            default : 
                $accounts = Account::where('type','=',$type)->get();
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
        $type = $account->type;
        
        if($account->type == 4 || $account->type == 5){
            $type = 999;
        }

        // Carica le transazioni con i conti collegati per identificare i trasferimenti
        $account->load('transactions.account');

        // Modifica ogni transazione per aggiungere una descrizione specifica in caso di trasferimento
        $account->transactions->each(function ($transaction) {
            // Cerca la transazione collegata direttamente nella tabella pivot
            $linkedTransaction = DB::table('transaction_transfers')
                ->where('transaction_id', $transaction->id)
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

        $accounts = Account::get();
        
        return view('conti.show', compact('account', 'type', 'accounts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return view('conti.edit', compact('account'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
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
        // Elimina il conto (soft delete)
        $account->delete();

        // Reindirizza alla pagina principale dei conti con un messaggio di successo
        return redirect()->route('conti.index', ['type' => $account->type])
                         ->with('success', 'Conto eliminato con successo!');
    }
}
