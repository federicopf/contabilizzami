<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Transaction;
use App\Models\Account;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Recupera i conti dell'utente autenticato
        $accounts = auth()->user()->accounts()->with('transactions')->get();

        // Calcola il saldo totale
        $totalBalance = $accounts->sum(function ($account) {
            return $account->transactions->sum('amount');
        });

        // Suddivisione per tipologia
        $accountTypes = [
            'Risparmio' => $accounts->where('type', 'Risparmio')->sum(fn($account) => $account->transactions->sum('amount')),
            'Debito/Credito' => $accounts->where('type', 'Debito/Credito')->sum(fn($account) => $account->transactions->sum('amount')),
            'Contanti' => $accounts->where('type', 'Contanti')->sum(fn($account) => $account->transactions->sum('amount')),
        ];

        // Recupera le ultime 5 transazioni
        $recentTransactions = auth()->user()->transactions()->orderBy('created_at', 'desc')->take(5)->get();


        // Modifica ogni transazione per aggiungere una descrizione specifica in caso di trasferimento
        $recentTransactions->each(function ($transaction) {
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

        return view('home', compact('totalBalance', 'accountTypes', 'recentTransactions'));
    }

}
