<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ProfileServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

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

        // Recupera l'utente autenticato e aggiorna la password
        $user = Auth::user();
        $this->profileService->updatePassword($user, $request->password);

        // Messaggio di successo e redirect
        return redirect()->back()->with('success', 'La password è stata aggiornata con successo.');
    }
}
