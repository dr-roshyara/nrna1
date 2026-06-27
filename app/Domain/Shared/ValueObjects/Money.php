<?php

namespace App\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\InvalidMoneyException;

class Money
{
    /**
     * Create a new Money value object.
     *
     * @param float $amount The monetary amount
     * @param string $currency ISO 4217 3-letter currency code (e.g. 'EUR', 'USD')
     * @throws InvalidMoneyException If amount is negative or currency is invalid
     */
    public function __construct(
        private readonly float $amount,
        private readonly string $currency = 'EUR'
    ) {
        $this->validate();
    }

    /**
     * Validate amount and currency.
     *
     * @throws InvalidMoneyException
     */
    private function validate(): void
    {
        if ($this->amount < 0) {
            throw new InvalidMoneyException('Amount cannot be negative');
        }

        if (strlen($this->currency) !== 3) {
            throw new InvalidMoneyException('Currency must be ISO 4217');
        }
    }

    /**
     * Get the amount.
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Get the currency code.
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Check if two Money objects are equal.
     */
    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    /**
     * Add another Money object to this one.
     */
    public function add(Money $other): Money
    {
        return new Money($this->amount + $other->amount, $this->currency);
    }
}
