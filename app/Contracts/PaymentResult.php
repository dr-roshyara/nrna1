<?php

namespace App\Contracts;

readonly class PaymentResult
{
    public function __construct(
        public bool   $success,
        public string $gatewayReference,
        public string $status,
        public ?string $failureReason = null,
    ) {}
}
