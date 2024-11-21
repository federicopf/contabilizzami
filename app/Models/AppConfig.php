<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    use HasFactory;

    // Campi assegnabili in massa
    protected $fillable = ['key', 'value'];

    // Disabilita i timestamp se non necessari
    public $timestamps = true;

    /**
     * Recupera il valore di una configurazione data la chiave.
     * Se non esiste, restituisce il valore predefinito.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Imposta o aggiorna il valore di una configurazione data la chiave.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function setValue(string $key, $value): bool
    {
        $config = self::firstOrNew(['key' => $key]);
        $config->value = $value;
        return $config->save();
    }
}
