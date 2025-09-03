<?php

namespace App\Services;

use App\Contracts\Services\ProfileServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileService implements ProfileServiceInterface
{
    /**
     * Aggiorna la password dell'utente
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
