<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Contracts\Services\AccountServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountServiceInterface $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($type)
    {
        $accounts = $this->accountService->getUserAccountsByType(Auth::id() ?? 0, $type);
        
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
            'type' => 'required|integer|in:1,2,3,4,5',
        ]);

        $this->accountService->createAccount($validated, Auth::id() ?? 0);

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
        $account = $this->accountService->getAccountWithProcessedTransactions($account, Auth::id() ?? 0);
        
        $type = $account->type;
        
        if($account->type == 4 || $account->type == 5){
            $type = 999;
        }

        $accounts = $this->accountService->getAllUserAccounts(Auth::id() ?? 0);
        
        return view('conti.show', compact('account', 'type', 'accounts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        // La verifica di autorizzazione è già fatta nel servizio quando necessario
        return view('conti.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2,3,4,5',
        ]);

        $this->accountService->updateAccount($account, $validated, Auth::id() ?? 0);

        return redirect()->route('conti.show', ['account' => $account->id])
                        ->with('success', 'Conto aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        try {
            $this->accountService->deleteAccount($account, Auth::id() ?? 0);

            return redirect()->route('conti.index', ['type' => $account->type])
                            ->with('success', 'Conto eliminato con successo!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function deleted()
    {
        $deletedAccounts = $this->accountService->getDeletedAccounts(Auth::id() ?? 0);

        return view('conti.deleted', compact('deletedAccounts'));
    }
    
    public function restore($id)
    {
        $this->accountService->restoreAccount($id, Auth::id() ?? 0);

        return redirect()->route('conti.deleted')
                        ->with('success', 'Conto ripristinato con successo!');
    }
}
