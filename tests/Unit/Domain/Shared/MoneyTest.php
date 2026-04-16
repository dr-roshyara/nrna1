<?php

namespace Tests\Unit\Domain\Shared;

use App\Domain\Shared\ValueObjects\Money;
use App\Domain\Shared\Exceptions\InvalidMoneyException;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * RED: Money cannot be negative
     */
    public function test_money_cannot_be_negative(): void
    {
        $this->expectException(InvalidMoneyException::class);
        $this->expectExceptionMessage('Amount cannot be negative');

        new Money(-10.50, 'EUR');
    }

    /**
     * RED: Money stores amount and currency correctly
     */
    public function test_money_stores_amount_and_currency(): void
    {
        $money = new Money(100.50, 'EUR');

        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals('EUR', $money->getCurrency());
    }

    /**
     * RED: Two Money objects with same values are equal
     */
    public function test_two_moneys_with_same_values_are_equal(): void
    {
        $money1 = new Money(100.00, 'EUR');
        $money2 = new Money(100.00, 'EUR');

        $this->assertTrue($money1->equals($money2));
    }

    /**
     * RED: Two Money objects with different amounts are not equal
     */
    public function test_two_moneys_with_different_amounts_are_not_equal(): void
    {
        $money1 = new Money(100.00, 'EUR');
        $money2 = new Money(50.00, 'EUR');

        $this->assertFalse($money1->equals($money2));
    }

    /**
     * RED: Money rejects invalid currency (not ISO 4217 3-letter code)
     */
    public function test_money_rejects_invalid_currency(): void
    {
        $this->expectException(InvalidMoneyException::class);
        $this->expectExceptionMessage('Currency must be ISO 4217');

        new Money(100.00, 'EURO');  // 4 letters, invalid
    }

    /**
     * RED: Money accepts zero amount
     */
    public function test_money_accepts_zero_amount(): void
    {
        $money = new Money(0.00, 'EUR');

        $this->assertEquals(0.00, $money->getAmount());
        $this->assertEquals('EUR', $money->getCurrency());
    }

    /**
     * RED: Money accepts common currencies
     */
    public function test_money_accepts_common_currencies(): void
    {
        $this->assertInstanceOf(Money::class, new Money(100.00, 'EUR'));
        $this->assertInstanceOf(Money::class, new Money(100.00, 'USD'));
        $this->assertInstanceOf(Money::class, new Money(100.00, 'GBP'));
        $this->assertInstanceOf(Money::class, new Money(100.00, 'INR'));
    }
}
