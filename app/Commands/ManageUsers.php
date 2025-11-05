<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Shield\Entities\User;
use Faker\Factory;

class ManageUsers extends BaseCommand
{
    protected $group       = 'Manage';
    protected $name        = 'manage:users';
    protected $description = 'add users to the database';

    public function run(array $params)
    {
      $faker = Factory::create();
      $users = auth()->getProvider();
      for($i=1; $i<=$params[0]; $i++){
        $firstName = $faker->firstName;
        $lastName = $faker->unique()->lastName;
        $username = strtolower(mb_substr($firstName, 0, 1)).strtolower($lastName);
        $email    = $username."@".$faker->safeEmailDomain;
        $user = new User([
            'username' => $username,
            'email'    => $email,
            'password' => 'roottoor',
        ]);
        $users->save($user);
        $user = $users->findById($users->getInsertID());
        $user->activate();
        $users->addToDefaultGroup($user);
      }
      CLI::write(CLI::color($params[0]." users created !", 'yellow'));
    }
}