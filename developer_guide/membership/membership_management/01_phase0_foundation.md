# Phase 0 — Foundation: Policy, Config, Contracts

## Goal

Establish the foundational layer before writing any business logic. This phase defines:
- **Who can do what** — `MembershipPolicy` using `UserOrganisationRole`
- **How the system is configured** — `config/membership.php`
- **How payments are abstracted** — `PaymentGateway` interface + `ManualPaymentGateway` (Phase 1 no-op)

---

## TDD Sequence

```
1. Write MembershipPolicyTest.php  (RED — 25 tests fail, class doesn't exist)
2. Create MembershipPolicy.php     (GREEN — 25 tests pass)
3. Create config/membership.php    (supporting config)
4. Create PaymentGateway contract  (interface for future Stripe/PayPal)
5. Create ManualPaymentGateway     (no-op implementation for Phase 1)
6. Bind in AppServiceProvider      (inject ManualPaymentGateway by default)
```

---

## 1. Role Hierarchy

The `UserOrganisationRole` model defines five roles with a numeric hierarchy:

```
owner      → 100   (full control)
admin      →  80   (manage members, fees, renewals; cannot manage types)
commission →  60   (read-only on applications)
voter      →  40   (no membership admin access)
member     →  20   (read-only own data; self-renewal only)
```

---

## 2. MembershipPolicy

**File:** `app/Policies/MembershipPolicy.php`

The policy centralises all membership authorization decisions. It is used directly in controllers (not via Laravel's Gate, to avoid registration boilerplate for this phase).

### Policy Methods

| Method | Allowed Roles | Notes |
|--------|--------------|-------|
| `viewApplications` | owner, admin, commission | Read access to application list/show |
| `approveApplication` | owner, admin | Write access to approve |
| `rejectApplication` | owner, admin | Write access to reject |
| `manageMembershipTypes` | owner only | Create/update/delete types |
| `recordFeePayment` | owner, admin | Record or waive fee payments |
| `initiateRenewal` | owner, admin + member (self) | Member can renew own membership |

### Implementation

```php
namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;

class MembershipPolicy
{
    public function viewApplications(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin', 'commission']);
    }

    public function approveApplication(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    public function rejectApplication(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    public function manageMembershipTypes(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner']);
    }

    public function recordFeePayment(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    public function initiateRenewal(User $user, Organisation $organisation, bool $isSelf = false): bool
    {
        if ($this->hasRole($user, $organisation, ['owner', 'admin'])) {
            return true;
        }

        // Member can only renew their own membership
        return $isSelf && $this->hasRole($user, $organisation, ['member']);
    }

    private function hasRole(User $user, Organisation $organisation, array $roles): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', $roles)
            ->exists();
    }
}
```

### How to Use in Controllers

```php
// Direct instantiation (no Gate registration needed)
private function authorizeRecordPayment($user, Organisation $organisation): void
{
    abort_if(!(new MembershipPolicy())->recordFeePayment($user, $organisation), 403);
}
```

### Tenant Isolation Test

A critical test verifies cross-organisation isolation:

```php
/** @test */
public function admin_of_other_org_cannot_approve(): void
{
    $otherOrg  = Organisation::factory()->create(['type' => 'tenant']);
    $otherAdmin = User::factory()->create();
    UserOrganisationRole::create([
        'user_id'         => $otherAdmin->id,
        'organisation_id' => $otherOrg->id,
        'role'            => 'admin',
    ]);

    // otherAdmin has no role in $this->org
    $result = (new MembershipPolicy())->approveApplication($otherAdmin, $this->org);

    $this->assertFalse($result);
}
```

---

## 3. Configuration

**File:** `config/membership.php`

```php
return [
    'notifications' => [
        'application_submitted' => ['mail', 'database'],
        'application_approved'  => ['mail'],
        'application_rejected'  => ['mail'],
        'renewal_reminder'      => ['mail'],
        'payment_confirmation'  => ['mail'],
    ],

    // Days after expiry that a member may still self-renew
    'self_renewal_window_days'   => env('MEMBERSHIP_SELF_RENEWAL_WINDOW_DAYS', 90),

    // Days after expiry before a member's status changes
    'grace_period_days'          => env('MEMBERSHIP_GRACE_PERIOD_DAYS', 30),

    // Days before an unprocessed application auto-expires
    'application_expiry_days'    => env('MEMBERSHIP_APPLICATION_EXPIRY_DAYS', 30),
];
```

### Environment Variables

Add these to `.env` / `.env.example` to override defaults:

```env
MEMBERSHIP_SELF_RENEWAL_WINDOW_DAYS=90
MEMBERSHIP_GRACE_PERIOD_DAYS=30
MEMBERSHIP_APPLICATION_EXPIRY_DAYS=30
```

---

## 4. Payment Gateway Abstraction

### Why Abstract Payments?

Phase 1 uses manual (admin-recorded) payments only. Future phases may integrate Stripe, PayPal, or bank transfer gateways. Abstracting now means zero controller changes when switching.

### Interface

**File:** `app/Contracts/PaymentGateway.php`

```php
namespace App\Contracts;

use App\Models\MembershipFee;

interface PaymentGateway
{
    public function createPayment(MembershipFee $fee): PaymentIntent;
    public function confirmPayment(string $paymentIntentId): PaymentResult;
    public function refundPayment(MembershipFee $fee, ?float $amount = null): RefundResult;
}
```

### Value Objects

**`PaymentIntent`** — returned by `createPayment()`:

```php
readonly class PaymentIntent
{
    public function __construct(
        public string $id,
        public string $status,
        public float  $amount,
        public string $currency,
        public ?string $redirectUrl = null,
    ) {}
}
```

**`PaymentResult`** — returned by `confirmPayment()`:

```php
readonly class PaymentResult
{
    public function __construct(
        public bool   $success,
        public string $paymentIntentId,
        public string $status,
        public ?string $failureReason = null,
    ) {}
}
```

### Phase 1 No-Op Implementation

**File:** `app/Services/ManualPaymentGateway.php`

The `ManualPaymentGateway` satisfies the interface without touching any external service. Admins record payments by hand, so `createPayment` returns a synthetic ID and `confirmPayment` always succeeds.

```php
class ManualPaymentGateway implements PaymentGateway
{
    public function createPayment(MembershipFee $fee): PaymentIntent
    {
        return new PaymentIntent(
            id:       'manual_' . Str::uuid(),
            status:   'pending',
            amount:   $fee->amount,
            currency: $fee->currency,
        );
    }

    public function confirmPayment(string $paymentIntentId): PaymentResult
    {
        return new PaymentResult(
            success:         true,
            paymentIntentId: $paymentIntentId,
            status:          'succeeded',
        );
    }

    public function refundPayment(MembershipFee $fee, ?float $amount = null): RefundResult
    {
        return new RefundResult(
            success:  true,
            refundId: 'manual_refund_' . Str::uuid(),
            amount:   $amount ?? $fee->amount,
            currency: $fee->currency,
        );
    }
}
```

### Binding in AppServiceProvider

```php
// app/Providers/AppServiceProvider.php
use App\Contracts\PaymentGateway;
use App\Services\ManualPaymentGateway;

public function register(): void
{
    $this->app->bind(PaymentGateway::class, ManualPaymentGateway::class);
}
```

To switch to Stripe in Phase 5, only this line changes:

```php
$this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
```

---

## 5. Test Coverage — Phase 0

**File:** `tests/Unit/Policies/MembershipPolicyTest.php`

**25 tests covering:**

| Test Group | Tests |
|-----------|-------|
| `viewApplications` | owner ✓, admin ✓, commission ✓, voter ✗, member ✗ |
| `approveApplication` | owner ✓, admin ✓, commission ✗, voter ✗, member ✗ |
| `rejectApplication` | owner ✓, admin ✓, commission ✗, voter ✗, member ✗ |
| `manageMembershipTypes` | owner ✓, admin ✗, commission ✗, voter ✗, member ✗ |
| `recordFeePayment` | owner ✓, admin ✓, commission ✗, voter ✗, member ✗ |
| `initiateRenewal` | owner ✓, admin ✓, member (isSelf=true) ✓, member (isSelf=false) ✗ |
| Cross-org isolation | admin of other org → ✗ |

---

## Common Mistakes

### Mistake 1: Using Laravel Gate without registration

```php
// WRONG — Gate::allows requires policy registration
$this->authorize('viewApplications', $organisation);

// CORRECT — instantiate directly
abort_if(!(new MembershipPolicy())->viewApplications($request->user(), $organisation), 403);
```

### Mistake 2: Checking role on wrong model

```php
// WRONG — OrganisationUser.role is the org-membership role, not the permission role
OrganisationUser::where('user_id', $user->id)->where('role', 'admin')->exists();

// CORRECT — UserOrganisationRole is the RBAC table
UserOrganisationRole::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->whereIn('role', ['admin'])
    ->exists();
```
