<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('account_id')->constrained()->onDelete('cascade'); // Chiave esterna verso accounts
            $table->string('description'); // Descrizione del movimento
            $table->decimal('amount', 15, 2); // Importo del movimento (può essere positivo o negativo)
            $table->timestamps(); // Data di creazione e aggiornamento
        });

        Schema::create('transaction_transfers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade'); // Prima transazione
            $table->foreignId('linked_transaction_id')->constrained('transactions')->onDelete('cascade'); // Transazione collegata
            $table->timestamps(); // Date di creazione e aggiornamento
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_transfers');
        Schema::dropIfExists('transactions');
    }
}
