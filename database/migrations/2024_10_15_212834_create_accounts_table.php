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
            $table->string('name'); // Nome del conto
            $table->integer('type'); // Tipo di conto come intero (es. 1=Risparmio, 2=Debiti, ecc.)
            $table->timestamps(); // Data di creazione e aggiornamento
            $table->softDeletes(); // Cancellazione logica
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
