<?php

namespace App\Contracts;

/**
 * Value object returned by PaymentGateway::createPayment().
 */
readonly class PaymentIntent
{
    public function __construct(
        public string $id,
        public string $status,   // pending | requires_action | succeeded
        public float  $amount,
        public string $currency,
        public ?string $redirectUrl = null,
    ) {}
}
