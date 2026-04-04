<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentIntent;
use App\Contracts\PaymentResult;
use App\Contracts\RefundResult;
use App\Models\MembershipFee;
use Illuminate\Support\Str;

/**
 * ManualPaymentGateway
 *
 * No-op implementation used in Phase 1.
 * Admins record payments manually; no external gateway is contacted.
 *
 * Replace with StripePaymentGateway in Phase 5.
 */
class ManualPaymentGateway implements PaymentGateway
{
    public function createPayment(MembershipFee $fee): PaymentIntent
    {
        return new PaymentIntent(
            id:          'manual_' . Str::uuid(),
            status:      'pending',
            amount:      (float) $fee->amount,
            currency:    $fee->currency,
            redirectUrl: null,
        );
    }

    public function confirmPayment(string $paymentIntentId): PaymentResult
    {
        return new PaymentResult(
            success:          true,
            gatewayReference: $paymentIntentId,
            status:           'succeeded',
        );
    }

    public function refundPayment(MembershipFee $fee, ?float $amount = null): RefundResult
    {
        return new RefundResult(
            success:          true,
            refundedAmount:   $amount ?? (float) $fee->amount,
            gatewayReference: 'manual_refund_' . Str::uuid(),
        );
    }
}
