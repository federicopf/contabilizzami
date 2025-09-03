<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserServiceInterface
{
    /**
     * Ottiene gli utenti filtrati per tipo superadmin
     */
    public function getUsersBySuperadminType(bool $isSuperadmin): Collection;

    /**
     * Crea un nuovo utente con password temporanea
     */
    public function createUser(array $data): array;

    /**
     * Aggiorna un utente
     */
    public function updateUser(User $user, array $data): User;

    /**
     * Elimina un utente
     */
    public function deleteUser(User $user): void;

    /**
     * Resetta la password di un utente
     */
    public function resetUserPassword(User $user): string;
}
