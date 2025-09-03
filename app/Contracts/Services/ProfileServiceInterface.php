<?php

namespace App\Contracts\Services;

use App\Models\User;

interface ProfileServiceInterface
{
    /**
     * Aggiorna la password dell'utente
     */
    public function updatePassword(User $user, string $newPassword): void;
}
