<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionTransfer;

use Illuminate\Http\Request;

class HomeController extends Controller
{
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

        $debitoCreditoBalance = 0;
        foreach (Account::TYPES as $typeId => $typeName) {
            $balance = $accounts->where('type', $typeId)->sum(fn($account) => $account->transactions->sum('amount'));

            if ($typeName === 'Debito' || $typeName === 'Credito') {
                $debitoCreditoBalance += $balance; // Somma Debito e Credito
            } else {
                $accountTypes[$typeName] = $balance; // Altri tipi vengono aggiunti normalmente
            }
        }

        // Aggiungi il saldo combinato di Debito/Credito con un'etichetta
        $accountTypes['Debito/Credito'] = $debitoCreditoBalance;

        // Recupera le ultime 5 transazioni
        $recentTransactions = auth()->user()->transactions()->orderBy('created_at', 'desc')->take(3)->get();


        // Modifica ogni transazione per aggiungere una descrizione specifica in caso di trasferimento
        $recentTransactions->each(function ($transaction) {
            $transaction->linked = 0;

            $linkedTransaction = TransactionTransfer::where('transaction_id', $transaction->id)
                ->orWhere('linked_transaction_id', $transaction->id)
                ->first();

            if ($linkedTransaction) {
                $linkedTransactionModel = Transaction::find($linkedTransaction->transaction_id == $transaction->id ? $linkedTransaction->linked_transaction_id : $linkedTransaction->transaction_id);
                $transaction->linked = 1;

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
