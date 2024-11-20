<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionTransfer extends Model
{
    use HasFactory;

    // Nome della tabella
    protected $table = 'transaction_transfers';

    // Campi assegnabili in massa
    protected $fillable = [
        'transaction_id',
        'linked_transaction_id',
        'created_at',
        'updated_at',
    ];

    // Relazione con il modello Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function linkedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'linked_transaction_id');
    }
}
