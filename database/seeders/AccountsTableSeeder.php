<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use Carbon\Carbon;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            [
                'name' => 'Conto Corrente',
                'user_id' => 1,
                'type' => 1, // Spendibili
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10)
            ],
            [
                'name' => 'Risparmio Famiglia',
                'user_id' => 1,
                'type' => 2, // Risparmio
                'created_at' => Carbon::now()->subMonths(4),
                'updated_at' => Carbon::now()->subMonths(4)
            ],
            [
                'name' => 'Investimenti Azioni',
                'user_id' => 1,
                'type' => 3, // Investimento
                'created_at' => Carbon::now()->subYear(2),
                'updated_at' => Carbon::now()->subYear(2)
            ],
            [
                'name' => 'Mutuo Casa',
                'user_id' => 1,
                'type' => 4, // Debito
                'created_at' => Carbon::now()->subYears(1),
                'updated_at' => Carbon::now()->subYears(1)
            ],
            [
                'name' => 'Carta di Credito',
                'user_id' => 1,
                'type' => 5, // Credito
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subMonths(3)
            ],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}
