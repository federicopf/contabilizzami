<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Collega l'account all'utente, con cancellazione a cascata
            $table->string('name'); // Nome del conto
            $table->integer('type'); // Tipo di conto come intero (es. 1=Risparmio, 2=Debiti, ecc.)
            $table->string('color')->default('0d6efd'); // Colore del conto
            $table->timestamps(); // Data di creazione e aggiornamento
            $table->softDeletes(); // Cancellazione logica
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
