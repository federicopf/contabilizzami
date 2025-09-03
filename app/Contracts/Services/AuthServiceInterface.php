<?php

namespace App\Contracts\Services;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Valida i dati di registrazione
     */
    public function validateRegistrationData(array $data): \Illuminate\Contracts\Validation\Validator;

    /**
     * Crea un nuovo utente
     */
    public function createUser(array $data): User;
}
