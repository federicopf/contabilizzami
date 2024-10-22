<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'description',
        'amount',
    ];

    // Relazione con il modello Account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function linkedTransactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_transfers', 'transaction_id', 'linked_transaction_id')
                    ->withTimestamps();
    }

}
