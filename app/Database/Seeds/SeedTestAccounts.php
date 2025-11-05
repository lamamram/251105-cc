<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\TestAccountModel;
use Faker\Factory;

class SeedTestAccounts extends Seeder
{
    public function run()
    {
        // Utilisation de la bibliothèque Faker
        $faker = Factory::create();
        $accounts = [
            [
                'client_id' => 1,
                'numero' => bin2hex(random_bytes(8)),
                'balance' => 500,
                'overdraft' => 200
            ],
            [
                'client_id' => 2,
                'numero' => bin2hex(random_bytes(8)),
                'balance' => 200,
                'overdraft' => 200
            ]
        ];
        $model = new TestAccountModel();
        foreach ($accounts as $account) {
            $model->insert($account);
        }
    }
}
