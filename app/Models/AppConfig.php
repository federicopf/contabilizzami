<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    use HasFactory;

    // Campi assegnabili in massa
    protected $fillable = ['key', 'value', 'user_id'];

    // Disabilita i timestamp se non necessari
    public $timestamps = true;

    /**
     * Recupera il valore di una configurazione data la chiave e l'user_id.
     * Se non esiste, restituisce il valore predefinito.
     *
     * @param string $key
     * @param int|null $userId
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, ?int $userId = 0, $default = null)
    {
        // Cerca la configurazione specifica per l'utente
        $value = self::where('key', $key)
                    ->where('user_id', $userId)
                    ->value('value');

        // Se non trova niente, cerca la configurazione generale (user_id = 0)
        if ($value === null) {
            $value = self::where('key', $key)
                        ->where('user_id', 0)
                        ->value('value');
        }

        // Se non trova ancora niente, restituisce il valore predefinito
        return $value ?? $default;
    }

    /**
     * Imposta o aggiorna il valore di una configurazione data la chiave e l'user_id.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $userId
     * @return bool
     */
    public static function setValue(string $key, $value, ?int $userId = 0): bool
    {
        $config = self::firstOrNew(['key' => $key, 'user_id' => $userId]);
        $config->value = $value;
        return $config->save();
    }
}
