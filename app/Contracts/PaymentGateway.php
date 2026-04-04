<?php

namespace App\Contracts;

use App\Models\MembershipFee;

/**
 * PaymentGateway
 *
 * Abstraction for processing membership fee payments.
 *
 * Phase 1 implementation: ManualPaymentGateway (no-op, admin records manually).
 * Phase 5 implementation: StripePaymentGateway / PayPalPaymentGateway.
 *
 * Bind the desired implementation in AppServiceProvider:
 *   $this->app->bind(PaymentGateway::class, ManualPaymentGateway::class);
 */
interface PaymentGateway
{
    /**
     * Initiate a payment intent for the given membership fee.
     * Returns a gateway-specific intent object (e.g. Stripe PaymentIntent).
     * For manual payments this is a no-op returning a local intent ID.
     */
    public function createPayment(MembershipFee $fee): PaymentIntent;

    /**
     * Confirm and capture a previously created payment intent.
     */
    public function confirmPayment(string $paymentIntentId): PaymentResult;

    /**
     * Refund a previously confirmed payment, optionally partial.
     */
    public function refundPayment(MembershipFee $fee, ?float $amount = null): RefundResult;
}
