<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppConfig;

class AppConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Colori delle tipologie di conto
        $accountColors = [
            'account_color_Spendibili' => '#FF5733', // Colore per i conti spendibili
            'account_color_Risparmio' => '#33FF57', // Colore per i conti risparmio
            'account_color_Investimento' => '#5733FF', // Colore per i conti investimento
            'account_color_Debito/Credito' => '#FFD700', // Colore per i conti credito
        ];

        // Popola la tabella con i colori
        foreach ($accountColors as $key => $value) {
            AppConfig::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
