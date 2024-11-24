<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_configs', function (Blueprint $table) {
            $table->id(); // Chiave primaria
            $table->unsignedBigInteger('user_id')->default(0); // ID utente o 0 per configurazioni generali
            $table->string('key'); // Chiave della configurazione, unica
            $table->text('value')->nullable(); // Valore della configurazione
            $table->timestamps(); // Created at e Updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_configs'); // Nome corretto della tabella
    }
}
