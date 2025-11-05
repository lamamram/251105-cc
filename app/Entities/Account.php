<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Account extends Entity
{
    protected $attributes = [
        'id' => null,
        'numero' => null,
        'balance' => 0,
        'overdraft' => 0,
        'client_id' => null,
    ];
}