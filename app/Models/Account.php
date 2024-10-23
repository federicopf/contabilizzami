<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
    ];

    // Mappatura dei tipi di conto
    const TYPES = [
        1 => 'Spendibili',
        2 => 'Risparmio',
        3 => 'Investimento',
        4 => 'Debito',
        5 => 'Credito'
    ];

    /**
     * Ottieni il nome del tipo di conto.
     *
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? 'Sconosciuto';
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('created_at','DESC');
    }

}
