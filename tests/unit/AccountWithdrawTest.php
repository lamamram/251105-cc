<?php

use App\Entities\Account;
use App\Exceptions\InsufficientFundsError;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AccountWithdrawTest extends CIUnitTestCase
{
    private Account $account;

    protected function setUp(): void
    {
        parent::setUp();
        $this->account = new Account([
            'id' => 1,
            'numero' => '123456',
            'balance' => 1000,
            'overdraft' => 0,
            'client_id' => 1,
        ]);
    }

    /**
     * Test that withdrawing an amount decreases the account balance by that amount.
     */
    public function testWithdrawingAnAmountDecreasesTheAccountBalance(): void
    {
        $initialBalance = $this->account->balance;
        $withdrawAmount = 100;

        $this->account->withdraw($withdrawAmount);

        $this->assertEquals($initialBalance - $withdrawAmount, $this->account->balance);
    }

    /**
     * Test that withdrawing an amount greater than the balance raises an InsufficientFundsError.
     */
    public function testWithdrawingAnAmountGreaterThanTheBalanceRaisesAnError(): void
    {
        $this->expectException(\App\Exceptions\InsufficientFundsError::class);

        $withdrawAmount = 1500; // Greater than balance of 1000

        $this->account->withdraw($withdrawAmount);
    }
}
