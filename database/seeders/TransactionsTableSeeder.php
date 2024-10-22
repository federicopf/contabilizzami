<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Account;
use Carbon\Carbon;

class TransactionsTableSeeder extends Seeder
{
    public function run()
    {
        // Recupera tutti gli account dal database
        $accounts = Account::all();

        // Assicurati che ci siano almeno due account per creare trasferimenti
        if ($accounts->count() < 2) {
            $this->command->info('Non ci sono abbastanza account per creare trasferimenti. Creane almeno due.');
            return;
        }

        foreach ($accounts as $account) {
            // Crea 5 transazioni normali per ogni account
            for ($i = 0; $i < 5; $i++) {
                Transaction::create([
                    'account_id' => $account->id,
                    'description' => 'Transazione normale ' . ($i + 1),
                    'amount' => rand(-1000, 1000),
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // Seleziona due account a caso per creare trasferimenti
        $accountFrom = $accounts->random();
        $accountTo = $accounts->where('id', '!=', $accountFrom->id)->random();

        // Crea una transazione di uscita dal primo conto
        $transactionFrom = Transaction::create([
            'account_id' => $accountFrom->id,
            'description' => 'Trasferimento a ' . $accountTo->name,
            'amount' => -500.00,
            'created_at' => Carbon::now()->subDays(rand(1, 30)),
            'updated_at' => Carbon::now()->subDays(rand(1, 30)),
        ]);

        // Crea una transazione di entrata nel secondo conto
        $transactionTo = Transaction::create([
            'account_id' => $accountTo->id,
            'description' => 'Ricezione da ' . $accountFrom->name,
            'amount' => 500.00,
            'created_at' => Carbon::now()->subDays(rand(1, 30)),
            'updated_at' => Carbon::now()->subDays(rand(1, 30)),
        ]);

        // Collega le due transazioni come trasferimento
        $transactionFrom->linkedTransactions()->attach($transactionTo->id);

        // Output di informazioni per confermare la creazione dei trasferimenti
        $this->command->info('Trasferimento creato tra account ' . $accountFrom->name . ' e ' . $accountTo->name);
    }
}
