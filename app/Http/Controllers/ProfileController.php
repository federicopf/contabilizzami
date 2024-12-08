<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Aggiorna la password dell'utente autenticato.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validazione del form
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'La password è obbligatoria.',
            'password.min' => 'La password deve contenere almeno 8 caratteri.',
            'password.confirmed' => 'Le password non corrispondono.',
        ]);

        // Recupera l'utente autenticato
        $user = Auth::user();

        // Aggiorna la password
        $user->password = Hash::make($request->password);
        $user->save();

        // Messaggio di successo e redirect
        return redirect()->back()->with('success', 'La password è stata aggiornata con successo.');
    }
}
