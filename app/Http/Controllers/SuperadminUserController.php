<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class SuperadminUserController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('superadmin', '0'); // Imposta il valore predefinito a '0' se non è passato nel parametro GET
    
        // Query per ottenere gli utenti in base al tipo
        $users = User::where('superadmin', $type)->get();
    
        return view('superadmin.users.index', ['users' => $users]);
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
