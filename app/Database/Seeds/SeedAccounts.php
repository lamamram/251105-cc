<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AccountModel;
use Faker\Factory;

class SeedAccounts extends Seeder
{
    public function run()
    {
        // Utilisation de la bibliothèque Faker
        $faker = Factory::create();
        
        // Création des comptes pour les clients avec ID 1 et 2
        for ($clientId = 1; $clientId <= 2; $clientId++) {
            $account = new AccountModel();
            // Génération d'un numéro de compte en hexadécimal
            $accountNumber = bin2hex(random_bytes(8));
            
            // Génération d'un solde entre 0 et 1000
            $balance = $faker->randomFloat(2, 0, 1000);
            
            // Insertion dans la base de données avec un découvert de 200
            $account->insert([
                'client_id' => $clientId,
                'numero' => $accountNumber,
                'balance' => $balance,
                'overdraft' => 200
            ]);
            
            // Affichage d'un message pour indiquer que le compte a été créé
            echo "Compte créé pour client ID {$clientId}: numéro {$accountNumber}, solde {$balance}\n";
        }
    }
}
