<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Contracts\Services\TransactionServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $this->transactionService->deleteTransaction($transaction, Auth::id() ?? 0);

        return redirect()
            ->route('conti.show', ['account' => $transaction->account->id])
            ->with('success', 'Transazione eliminata con successo!');
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        $transaction = $this->transactionService->createTransaction($data, Auth::id() ?? 0);
        $account = Account::findOrFail($data['account_id']);

        return redirect()
                ->route('conti.show', ['account' => $account->id])
                ->with('success', 'Transazione creata con successo!');
    }

    /**
     * Store a newly created transfer between accounts in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
        $data = $request->validate([
            'account_from_id' => 'required|exists:accounts,id',
            'account_to_id' => 'required|exists:accounts,id|different:account_from_id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $result = $this->transactionService->createTransfer($data, Auth::id() ?? 0);

        return redirect()
                ->route('conti.show', ['account' => $data['account_from_id']])
                ->with('success', 'Trasferimento creato con successo!');
    }

    public function suggestions(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $suggestions = $this->transactionService->getDescriptionSuggestions($request->query('query'));

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Remove the specified transaction via API.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy(Transaction $transaction)
    {
        try {
            $this->transactionService->deleteTransaction($transaction, Auth::id() ?? 0);

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Transazione eliminata con successo',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 403);
        }
    }

    /**
     * Store a new transaction via API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        try {
            $transaction = $this->transactionService->createTransaction($data, Auth::id() ?? 0);

            return response()->json([
                'status' => true,
                'data' => $transaction->description,
                'message' => 'Transazione creata con successo',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 403);
        }
    }
    
    /**
     * Store a new transfer between accounts via API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiTransfer(Request $request)
    {
        $data = $request->validate([
            'account_from_id' => 'required|exists:accounts,id',
            'account_to_id' => 'required|exists:accounts,id|different:account_from_id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $result = $this->transactionService->createTransfer($data, Auth::id() ?? 0);

            return response()->json([
                'status' => true,
                'data' => $result['transaction_to']->description,
                'message' => 'Trasferimento creato con successo',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 403);
        }
    }
}
