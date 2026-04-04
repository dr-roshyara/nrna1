<?php

namespace App\Contracts;

readonly class RefundResult
{
    public function __construct(
        public bool   $success,
        public float  $refundedAmount,
        public string $gatewayReference,
        public ?string $failureReason = null,
    ) {}
}
