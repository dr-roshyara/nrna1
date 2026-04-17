<?php

namespace Tests\Unit\Domain\Shared;

use App\Domain\Shared\ValueObjects\Money;
use App\Domain\Shared\Exceptions\InvalidMoneyException;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * RED Test: Money cannot be negative
     */
    public function test_money_cannot_be_negative(): void
    {
        $this->expectException(InvalidMoneyException::class);
        new Money(-10.50, 'EUR');
    }

    /**
     * RED Test: Money stores amount and currency
     */
    public function test_money_stores_amount_and_currency(): void
    {
        $money = new Money(100.50, 'EUR');

        $this->assertEquals(100.50, $money->getAmount());
        $this->assertEquals('EUR', $money->getCurrency());
    }

    /**
     * RED Test: Two moneys with same values are equal
     */
    public function test_two_moneys_with_same_values_are_equal(): void
    {
        $money1 = new Money(100.00, 'EUR');
        $money2 = new Money(100.00, 'EUR');

        $this->assertTrue($money1->equals($money2));
    }

    /**
     * RED Test: Add two moneys returns correct sum
     */
    public function test_add_two_moneys_returns_correct_sum(): void
    {
        $money1 = new Money(50.00, 'EUR');
        $money2 = new Money(25.50, 'EUR');

        $result = $money1->add($money2);

        $this->assertEquals(75.50, $result->getAmount());
        $this->assertEquals('EUR', $result->getCurrency());
    }

    /**
     * RED Test: Money rejects invalid currency
     */
    public function test_money_rejects_invalid_currency(): void
    {
        $this->expectException(InvalidMoneyException::class);
        new Money(100.00, 'INVALID');
    }
}
