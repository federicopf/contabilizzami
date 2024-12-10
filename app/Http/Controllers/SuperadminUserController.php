<?php

namespace App\Http\Controllers;


use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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
        // Validazione dei dati
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'superadmin' => 'required|boolean',
        ], [
            'name.required' => 'Il campo nome è obbligatorio.',
            'name.string' => 'Il campo nome deve essere una stringa.',
            'name.max' => 'Il campo nome non può superare i 255 caratteri.',
            'email.required' => 'Il campo Email è obbligatorio.',
            'email.string' => 'Il campo email deve essere una stringa.',
            'email.email' => 'Il campo email deve essere un indirizzo email valido.',
            'email.max' => 'Il campo email non può superare i 255 caratteri.',
            'email.unique' => 'L\'indirizzo email inserito è già in uso.',
            'superadmin.required' => 'Il campo tipo di utente è obbligatorio.',
            'superadmin.boolean' => 'Il campo tipo di utente deve essere un valore valido.',
        ]);
    
        // Genera una password temporanea
        $temporaryPassword = Str::random(12);
    
        // Crea l'utente
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'superadmin' => $request->superadmin, // Salva il tipo di utente
        ]);
    
        // Mostra una schermata di successo con la password temporanea
        return view('superadmin.users.create_success', compact('user', 'temporaryPassword'));
    }
    
    public function show(User $user)
    {
        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'superadmin' => 'required|boolean',
        ], [
            'name.required' => 'Il campo nome è obbligatorio.',
            'name.string' => 'Il campo nome deve essere una stringa.',
            'name.max' => 'Il campo nome non può superare i 255 caratteri.',
            'email.required' => 'Il campo Email è obbligatorio.',
            'email.string' => 'Il campo email deve essere una stringa.',
            'email.email' => 'Il campo email deve essere un indirizzo email valido.',
            'email.max' => 'Il campo email non può superare i 255 caratteri.',
            'email.unique' => 'L\'indirizzo email inserito è già in uso.',
            'superadmin.required' => 'Il campo tipo di utente è obbligatorio.',
            'superadmin.boolean' => 'Il campo tipo di utente deve essere un valore valido.',
        ]);
    
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'superadmin' => $request->superadmin,
        ]);
    
        return redirect()->route('superadmin.users.show', $user->id)
            ->with('success', 'Utente aggiornato con successo.');
    }
    
    public function destroy(User $user)
    {
        $user->delete();
    
        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utente eliminato con successo.');
    }

    public function resetPassword(User $user)
    {
        // Genera una nuova password temporanea
        $temporaryPassword = Str::random(12);

        // Aggiorna la password dell'utente
        $user->password = Hash::make($temporaryPassword);
        $user->save();

        // Reindirizza alla schermata di dettaglio con la nuova password
        return redirect()->route('superadmin.users.show', $user->id)
            ->with('success', 'La password è stata resettata con successo. La nuova password è: ' . $temporaryPassword);
    }
}
