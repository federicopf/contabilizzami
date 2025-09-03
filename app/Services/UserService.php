<?php

namespace App\Services;

use App\Models\User;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

class UserService implements UserServiceInterface
{
    /**
     * Ottiene gli utenti filtrati per tipo superadmin
     */
    public function getUsersBySuperadminType(bool $isSuperadmin): Collection
    {
        return User::where('superadmin', $isSuperadmin)->get();
    }

    /**
     * Crea un nuovo utente con password temporanea
     */
    public function createUser(array $data): array
    {
        // Genera una password temporanea
        $temporaryPassword = Str::random(12);

        // Crea l'utente
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($temporaryPassword),
            'superadmin' => $data['superadmin'],
        ]);

        return [
            'user' => $user,
            'temporary_password' => $temporaryPassword
        ];
    }

    /**
     * Aggiorna un utente
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'superadmin' => $data['superadmin'],
        ]);

        return $user;
    }

    /**
     * Elimina un utente
     */
    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    /**
     * Resetta la password di un utente
     */
    public function resetUserPassword(User $user): string
    {
        // Genera una nuova password temporanea
        $temporaryPassword = Str::random(12);

        // Aggiorna la password dell'utente
        $user->password = Hash::make($temporaryPassword);
        $user->save();

        return $temporaryPassword;
    }
}
