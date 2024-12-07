<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperadminUserController extends Controller
{
    public function index()
    {
        // Mostra la lista degli utenti
        return view('superadmin.users.index', [
            'users' => [
                ['id' => 1, 'name' => 'Mario Rossi', 'type' => 'Utente'],
                ['id' => 2, 'name' => 'Luigi Bianchi', 'type' => 'Superadmin'],
                ['id' => 3, 'name' => 'Anna Verdi', 'type' => 'Utente'],
            ]
        ]);
    }

    public function create()
    {
        // Mostra il form per creare un nuovo utente
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        // Logica per salvare un nuovo utente (mock)
        return redirect()->route('superadmin.users.index')->with('success', 'Utente creato con successo.');
    }

    public function edit($id)
    {
        // Mostra il form per modificare un utente (mock)
        return view('superadmin.users.edit', [
            'user' => ['id' => $id, 'name' => 'Mario Rossi', 'type' => 'Utente']
        ]);
    }

    public function update(Request $request, $id)
    {
        // Logica per aggiornare un utente (mock)
        return redirect()->route('superadmin.users.index')->with('success', 'Utente aggiornato con successo.');
    }

    public function show($id)
    {
        // Mostra i dettagli di un singolo utente (mock)
        return view('superadmin.users.show', [
            'user' => ['id' => $id, 'name' => 'Mario Rossi', 'type' => 'Utente']
        ]);
    }

    public function destroy($id)
    {
        // Logica per eliminare un utente (mock)
        return redirect()->route('superadmin.users.index')->with('success', 'Utente eliminato con successo.');
    }
}
