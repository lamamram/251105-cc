<?php

namespace App\Entities;

use App\Exceptions\InsufficientFundsError;
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

    /**
     * Withdraw an amount from the account.
     *
     * @param float $amount The amount to withdraw
     *
     * @throws InsufficientFundsError If the withdrawal amount exceeds the balance
     */
    public function withdraw(float $amount): void
    {
        if ($amount > $this->balance) {
            throw new InsufficientFundsError('Insufficient funds for this withdrawal');
        }

        $this->balance -= $amount;
    }
}