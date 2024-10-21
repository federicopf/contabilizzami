<?php

namespace App\Http\Controllers;

use App\Models\Account;
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
        return view('conti.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
