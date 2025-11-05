<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\TestClientModel;
use Faker\Factory;

class SeedTestClients extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $client = new TestClientModel();
        $names = ["sender", "receiver"];
        foreach($names as $name) {
            $client->insert([
                'name'        => $faker->firstName() . " " .$name,
                'phone_number'=> $faker->phoneNumber(),
                'created_at'  => $faker->dateTimeThisDecade()->format('U')
            ]);
        }
    }
}
