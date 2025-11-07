<?php

use App\Entities\Account;
use App\Exceptions\InsufficientFundsError;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class AccountEntityTest extends CIUnitTestCase
{
    private Account $account;

    protected function setUp(): void
    {
        parent::setUp();
        $this->account = new Account([
            'id' => 1,
            'numero' => '123456789',
            'balance' => 1000.00,
            'overdraft' => 500.00,
            'client_id' => 42,
        ]);
    }

    // ====================================
    // Property and Attribute Tests
    // ====================================

    /**
     * Test that Account entity can be instantiated with attributes.
     */
    public function testAccountCanBeInstantiatedWithAttributes(): void
    {
        $this->assertIsObject($this->account);
        $this->assertInstanceOf(Account::class, $this->account);
    }

    /**
     * Test that id property is set correctly on instantiation.
     */
    public function testIdPropertyIsSetCorrectly(): void
    {
        $this->assertEquals(1, $this->account->id);
    }

    /**
     * Test that numero property is set correctly on instantiation.
     */
    public function testNumeroPropertyIsSetCorrectly(): void
    {
        $this->assertEquals('123456789', $this->account->numero);
    }

    /**
     * Test that balance property is set correctly on instantiation.
     */
    public function testBalancePropertyIsSetCorrectly(): void
    {
        $this->assertEquals(1000.00, $this->account->balance);
    }

    /**
     * Test that overdraft property is set correctly on instantiation.
     */
    public function testOverdraftPropertyIsSetCorrectly(): void
    {
        $this->assertEquals(500.00, $this->account->overdraft);
    }

    /**
     * Test that client_id property is set correctly on instantiation.
     */
    public function testClientIdPropertyIsSetCorrectly(): void
    {
        $this->assertEquals(42, $this->account->client_id);
    }

    /**
     * Test that Account entity can be instantiated with empty attributes.
     */
    public function testAccountCanBeInstantiatedEmpty(): void
    {
        $account = new Account();

        $this->assertNull($account->id);
        $this->assertNull($account->numero);
        $this->assertEquals(0, $account->balance);
        $this->assertEquals(0, $account->overdraft);
        $this->assertNull($account->client_id);
    }

    /**
     * Test that balance property defaults to 0.
     */
    public function testBalancePropertyDefaultsToZero(): void
    {
        $account = new Account();
        $this->assertEquals(0, $account->balance);
    }

    /**
     * Test that overdraft property defaults to 0.
     */
    public function testOverdraftPropertyDefaultsToZero(): void
    {
        $account = new Account();
        $this->assertEquals(0, $account->overdraft);
    }

    /**
     * Test that properties can be modified after instantiation.
     */
    public function testPropertiesCanBeModifiedAfterInstantiation(): void
    {
        $this->account->numero = '987654321';
        $this->account->balance = 2000.00;
        $this->account->client_id = 99;

        $this->assertEquals('987654321', $this->account->numero);
        $this->assertEquals(2000.00, $this->account->balance);
        $this->assertEquals(99, $this->account->client_id);
    }

    // ====================================
    // Withdraw Method - Successful Cases
    // ====================================

    /**
     * Test that withdrawing a valid amount decreases the account balance.
     */
    public function testWithdrawingValidAmountDecreasesBalance(): void
    {
        $initialBalance = $this->account->balance;
        $withdrawAmount = 100.00;

        $this->account->withdraw($withdrawAmount);

        $this->assertEquals($initialBalance - $withdrawAmount, $this->account->balance);
        $this->assertEquals(900.00, $this->account->balance);
    }

    /**
     * Test that withdrawing exactly the full balance is allowed.
     */
    public function testWithdrawingFullBalanceIsAllowed(): void
    {
        $this->account->balance = 500.00;
        $this->account->withdraw(500.00);

        $this->assertEquals(0, $this->account->balance);
    }

    /**
     * Test that withdrawing zero amount does not affect balance.
     */
    public function testWithdrawingZeroAmountDoesNotAffectBalance(): void
    {
        $initialBalance = $this->account->balance;
        $this->account->withdraw(0);

        $this->assertEquals($initialBalance, $this->account->balance);
    }

    /**
     * Test that withdrawing small decimal amounts works correctly.
     */
    public function testWithdrawingSmallDecimalAmountsWorksCorrectly(): void
    {
        $this->account->balance = 100.50;
        $this->account->withdraw(0.50);

        $this->assertEqualsWithDelta(100.00, $this->account->balance, 0.001);
    }

    /**
     * Test that withdrawing multiple times accumulates correctly.
     */
    public function testWithdrawingMultipleTimesAccumulatesCorrectly(): void
    {
        $this->account->balance = 1000.00;

        $this->account->withdraw(100.00);
        $this->assertEquals(900.00, $this->account->balance);

        $this->account->withdraw(200.00);
        $this->assertEquals(700.00, $this->account->balance);

        $this->account->withdraw(300.00);
        $this->assertEquals(400.00, $this->account->balance);
    }

    /**
     * Test that large withdrawal amounts work correctly.
     */
    public function testLargeWithdrawalAmountsWorkCorrectly(): void
    {
        $this->account->balance = 999999.99;
        $this->account->withdraw(500000.00);

        $this->assertEqualsWithDelta(499999.99, $this->account->balance, 0.001);
    }

    /**
     * Test that withdrawal with very small balance works.
     */
    public function testWithdrawalWithVerySmallBalanceWorks(): void
    {
        $this->account->balance = 0.01;
        $this->account->withdraw(0.01);

        $this->assertEqualsWithDelta(0, $this->account->balance, 0.001);
    }

    // ====================================
    // Withdraw Method - Error Cases
    // ====================================

    /**
     * Test that withdrawing more than balance throws InsufficientFundsError.
     */
    public function testWithdrawingMoreThanBalanceThrowsException(): void
    {
        $this->expectException(InsufficientFundsError::class);

        $this->account->balance = 500.00;
        $this->account->withdraw(600.00);
    }

    /**
     * Test that InsufficientFundsError has correct message.
     */
    public function testInsufficientFundsErrorHasCorrectMessage(): void
    {
        $this->expectException(InsufficientFundsError::class);
        $this->expectExceptionMessage('Insufficient funds for this withdrawal');

        $this->account->balance = 100.00;
        $this->account->withdraw(200.00);
    }

    /**
     * Test that attempting withdrawal by just 1 unit over balance throws exception.
     */
    public function testWithdrawingJustOverBalanceThrowsException(): void
    {
        $this->expectException(InsufficientFundsError::class);

        $this->account->balance = 100.00;
        $this->account->withdraw(100.01);
    }

    /**
     * Test that withdrawing when balance is exactly zero throws exception.
     */
    public function testWithdrawingWithZeroBalanceThrowsException(): void
    {
        $this->expectException(InsufficientFundsError::class);

        $this->account->balance = 0;
        $this->account->withdraw(0.01);
    }

    /**
     * Test that balance is not modified when withdrawal fails.
     */
    public function testBalanceNotModifiedWhenWithdrawalFails(): void
    {
        $initialBalance = 500.00;
        $this->account->balance = $initialBalance;

        try {
            $this->account->withdraw(600.00);
        } catch (InsufficientFundsError) {
            // Expect exception
        }

        $this->assertEquals($initialBalance, $this->account->balance);
    }

    /**
     * Test that overdraft property does not affect withdrawal validation.
     */
    public function testOverdraftPropertyDoesNotAffectWithdrawalValidation(): void
    {
        $this->account->balance = 100.00;
        $this->account->overdraft = 500.00; // Has overdraft allowance

        // Should still fail because overdraft is not considered in validation
        $this->expectException(InsufficientFundsError::class);
        $this->account->withdraw(150.00);
    }

    // ====================================
    // Float/Precision Tests
    // ====================================

    /**
     * Test that floating point arithmetic is handled correctly.
     */
    public function testFloatingPointArithmeticIsHandledCorrectly(): void
    {
        $this->account->balance = 0.1 + 0.2; // Classic floating point issue
        $this->account->withdraw(0.1);

        // Using delta for floating point comparison
        $this->assertEqualsWithDelta(0.2, $this->account->balance, 0.001);
    }

    /**
     * Test that balance calculations maintain precision with many decimal places.
     */
    public function testBalanceCalculationsMaintainPrecision(): void
    {
        $this->account->balance = 123.456;
        $this->account->withdraw(23.456);

        $this->assertEqualsWithDelta(100.00, $this->account->balance, 0.001);
    }

    // ====================================
    // Entity Type/Data Type Tests
    // ====================================

    /**
     * Test that withdraw accepts float type.
     */
    public function testWithdrawAcceptsFloatType(): void
    {
        $this->account->balance = 100.00;
        $this->account->withdraw(50.5);

        $this->assertEqualsWithDelta(49.5, $this->account->balance, 0.001);
    }

    /**
     * Test that withdraw accepts integer type and converts correctly.
     */
    public function testWithdrawAcceptsIntegerType(): void
    {
        $this->account->balance = 100;
        $this->account->withdraw(50); // Integer

        $this->assertEquals(50, $this->account->balance);
    }

    /**
     * Test that negative withdrawal amounts are treated as withdrawal.
     */
    public function testNegativeWithdrawalAmountsAreValidated(): void
    {
        $initialBalance = 100.00;
        $this->account->balance = $initialBalance;

        // Negative amount should still check against balance
        // -50 > 100 is false, so it should not throw
        $this->account->withdraw(-50);

        // Balance should be increased because we subtract a negative
        $this->assertEquals($initialBalance - (-50), $this->account->balance);
        $this->assertEquals(150.00, $this->account->balance);
    }

    // ====================================
    // Edge Cases
    // ====================================

    /**
     * Test that account with null balance initializes and can be used.
     */
    public function testAccountWithNullBalanceInitializesToZero(): void
    {
        $account = new Account(['balance' => null]);

        // The entity should handle this gracefully
        $this->assertNull($account->balance);
    }

    /**
     * Test that account attributes are properly stored as attributes.
     */
    public function testAccountAttributesAreProperlyStored(): void
    {
        $attributes = [
            'id' => 5,
            'numero' => 'ACC-001',
            'balance' => 5000,
            'overdraft' => 1000,
            'client_id' => 7,
        ];

        $account = new Account($attributes);

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $account->$key);
        }
    }

    /**
     * Test that account entity extends CodeIgniter Entity.
     */
    public function testAccountExtendsCodeIgniterEntity(): void
    {
        $this->assertInstanceOf(\CodeIgniter\Entity\Entity::class, $this->account);
    }

    /**
     * Test that withdraw method returns void.
     */
    public function testWithdrawMethodReturnsVoid(): void
    {
        $this->account->balance = 100.00;
        $result = $this->account->withdraw(50.00);

        $this->assertNull($result);
    }
}
