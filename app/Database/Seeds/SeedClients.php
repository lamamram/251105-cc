<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ClientModel;
use CodeIgniter\Shield\Entities\User;
use Faker\Factory;

class SeedClients extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $users = auth()->getProvider();
        foreach($users->findAll() as $user) {
            $client = new ClientModel();
            $name = mb_substr($user->username, 1);
            $client->insert([
                'name'        => $faker->firstName() . " " .$name,
                'phone_number'=> $faker->phoneNumber(),
                'created_at'  => $faker->dateTimeThisDecade()->format('U'),
                'user_id'     => $user->id,
            ]);
        }
    }
}
