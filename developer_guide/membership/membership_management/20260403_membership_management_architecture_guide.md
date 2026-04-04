# 👨‍💻 **Membership Management System - Developer Guide**

## **Complete TDD Development Guide (Phase 0 → Phase 4)**

---

## 📋 **Table of Contents**

1. [System Overview](#system-overview)
2. [Prerequisites](#prerequisites)
3. [Phase 0: Foundation Setup](#phase-0-foundation-setup)
4. [Phase 1: Data Layer](#phase-1-data-layer)
5. [Phase 2: Application Workflow](#phase-2-application-workflow)
6. [Phase 3: Fee & Renewal](#phase-3-fee--renewal)
7. [Phase 4: Types Management & Jobs](#phase-4-types-management--jobs)
8. [Testing Strategy](#testing-strategy)
9. [Common Issues & Solutions](#common-issues--solutions)
10. [Deployment Checklist](#deployment-checklist)

---

## 🏗️ **System Overview**

### Architecture
```
┌─────────────────────────────────────────────────────────────────┐
│                    Membership Management System                 │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │ Application │  │    Fee      │  │   Renewal   │             │
│  │  Workflow   │  │  Tracking   │  │  Management │             │
│  └─────────────┘  └─────────────┘  └─────────────┘             │
│         │               │               │                       │
│         ▼               ▼               ▼                       │
│  ┌─────────────────────────────────────────────────┐           │
│  │              Membership Types                    │           │
│  │  (fee structures, durations, approval rules)    │           │
│  └─────────────────────────────────────────────────┘           │
└─────────────────────────────────────────────────────────────────┘
```

### Key Features
- ✅ Membership application with admin approval/rejection
- ✅ Fee tracking with payment recording
- ✅ Self-renewal within 90-day grace period
- ✅ Auto-expiry and overdue fee handling
- ✅ Multi-tenant isolation with global scopes
- ✅ Event-driven notifications
- ✅ Full audit trail

---

## 📦 **Prerequisites**

### Required Packages
```bash
composer require laravel/jetstream
composer require inertiajs/inertia-laravel
npm install @inertiajs/vue3 vue vue-router
```

### Environment Setup
```env
# .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nrna
DB_USERNAME=root
DB_PASSWORD=

# Membership configuration
MEMBERSHIP_GRACE_PERIOD_DAYS=30
MEMBERSHIP_SELF_RENEWAL_WINDOW_DAYS=90
MEMBERSHIP_APPLICATION_EXPIRY_DAYS=30
```

---

## 🚀 **Phase 0: Foundation Setup**

### **Goal:** Establish configuration, contracts, policies, and TDD infrastructure.

### **Step 0.1: Create Configuration**

```bash
# Create config file
touch config/membership.php
```

**File: `config/membership.php`**
```php
<?php

return [
    'notifications' => [
        'application_submitted' => ['mail', 'database'],
        'application_approved'  => ['mail'],
        'application_rejected'  => ['mail'],
        'renewal_reminder'      => ['mail'],
        'payment_confirmation'  => ['mail'],
    ],
    'grace_period_days' => env('MEMBERSHIP_GRACE_PERIOD_DAYS', 30),
    'self_renewal_window_days' => env('MEMBERSHIP_SELF_RENEWAL_WINDOW_DAYS', 90),
    'application_expiry_days' => env('MEMBERSHIP_APPLICATION_EXPIRY_DAYS', 30),
];
```

### **Step 0.2: Create Payment Gateway Contracts (TDD)**

**Write failing tests first:**

```bash
# Create test file
touch tests/Unit/Policies/MembershipPolicyTest.php
```

**Test File: `tests/Unit/Policies/MembershipPolicyTest.php`**
```php
<?php

namespace Tests\Unit\Policies;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Policies\MembershipPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipPolicyTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private MembershipPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        $this->policy = new MembershipPolicy();
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'role' => $role,
        ]);
        return $user;
    }

    /** @test */
    public function owner_can_view_applications(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->viewApplications($user, $this->org));
    }

    /** @test */
    public function admin_can_view_applications(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertTrue($this->policy->viewApplications($user, $this->org));
    }

    /** @test */
    public function voter_cannot_view_applications(): void
    {
        $user = $this->userWithRole('voter');
        $this->assertFalse($this->policy->viewApplications($user, $this->org));
    }

    /** @test */
    public function owner_can_approve_applications(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->approveApplication($user, $this->org));
    }

    /** @test */
    public function admin_can_approve_applications(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertTrue($this->policy->approveApplication($user, $this->org));
    }

    /** @test */
    public function commission_cannot_approve_applications(): void
    {
        $user = $this->userWithRole('commission');
        $this->assertFalse($this->policy->approveApplication($user, $this->org));
    }

    /** @test */
    public function owner_can_manage_membership_types(): void
    {
        $user = $this->userWithRole('owner');
        $this->assertTrue($this->policy->manageMembershipTypes($user, $this->org));
    }

    /** @test */
    public function admin_cannot_manage_membership_types(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertFalse($this->policy->manageMembershipTypes($user, $this->org));
    }

    /** @test */
    public function admin_can_record_fee_payment(): void
    {
        $user = $this->userWithRole('admin');
        $this->assertTrue($this->policy->recordFeePayment($user, $this->org));
    }
}
```

### **Step 0.3: Implement Policy (Make Tests Green)**

**File: `app/Policies/MembershipPolicy.php`**
```php
<?php

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
        
        if ($isSelf && $this->hasRole($user, $organisation, ['member'])) {
            return true;
        }
        
        return false;
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

### **Step 0.4: Create Contracts (Payment Gateway Abstraction)**

**File: `app/Contracts/PaymentGateway.php`**
```php
<?php

namespace App\Contracts;

use App\Models\MembershipFee;

interface PaymentGateway
{
    public function createPayment(MembershipFee $fee): PaymentIntent;
    public function confirmPayment(string $paymentIntentId): PaymentResult;
    public function refundPayment(MembershipFee $fee, ?float $amount = null): RefundResult;
}
```

**File: `app/Contracts/PaymentIntent.php`**
```php
<?php

namespace App\Contracts;

readonly class PaymentIntent
{
    public function __construct(
        public string $id,
        public string $status,
        public float $amount,
        public string $currency,
        public ?string $redirectUrl = null,
    ) {}
}
```

**File: `app/Contracts/PaymentResult.php`**
```php
<?php

namespace App\Contracts;

readonly class PaymentResult
{
    public function __construct(
        public bool $success,
        public string $gatewayReference,
        public string $status,
        public ?string $failureReason = null,
    ) {}
}
```

**File: `app/Contracts/RefundResult.php`**
```php
<?php

namespace App\Contracts;

readonly class RefundResult
{
    public function __construct(
        public bool $success,
        public float $refundedAmount,
        public string $gatewayReference,
        public ?string $failureReason = null,
    ) {}
}
```

### **Step 0.5: Create Manual Payment Gateway (Phase 1 Implementation)**

**File: `app/Services/ManualPaymentGateway.php`**
```php
<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentIntent;
use App\Contracts\PaymentResult;
use App\Contracts\RefundResult;
use App\Models\MembershipFee;
use Illuminate\Support\Str;

class ManualPaymentGateway implements PaymentGateway
{
    public function createPayment(MembershipFee $fee): PaymentIntent
    {
        return new PaymentIntent(
            id: 'manual_' . Str::uuid(),
            status: 'pending',
            amount: (float) $fee->amount,
            currency: $fee->currency,
        );
    }

    public function confirmPayment(string $paymentIntentId): PaymentResult
    {
        return new PaymentResult(
            success: true,
            gatewayReference: $paymentIntentId,
            status: 'succeeded',
        );
    }

    public function refundPayment(MembershipFee $fee, ?float $amount = null): RefundResult
    {
        return new RefundResult(
            success: true,
            refundedAmount: $amount ?? (float) $fee->amount,
            gatewayReference: 'manual_refund_' . Str::uuid(),
        );
    }
}
```

**Bind in `AppServiceProvider`:**
```php
$this->app->bind(PaymentGateway::class, ManualPaymentGateway::class);
```

### **✅ Phase 0 Verification**
```bash
php artisan test tests/Unit/Policies/MembershipPolicyTest.php
# Expected: 25 tests passing
```

---

## 🗄️ **Phase 1: Data Layer**

### **Goal:** Create migrations, models, and unit tests for all membership entities.

### **Step 1.1: Write Model Tests First (RED Phase)**

**File: `tests/Unit/Models/MembershipTypeTest.php`**
```php
<?php

namespace Tests\Unit\Models;

use App\Models\MembershipType;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipTypeTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
    }

    private function makeType(array $attrs = []): MembershipType
    {
        return MembershipType::create(array_merge([
            'id' => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name' => 'Annual Member',
            'slug' => 'annual',
            'fee_amount' => 50.00,
            'fee_currency' => 'EUR',
            'duration_months' => 12,
            'is_active' => true,
        ], $attrs));
    }

    /** @test */
    public function it_belongs_to_an_organisation(): void
    {
        $type = $this->makeType();
        $this->assertInstanceOf(Organisation::class, $type->organisation);
        $this->assertEquals($this->org->id, $type->organisation->id);
    }

    /** @test */
    public function active_scope_filters_out_inactive_types(): void
    {
        $this->makeType(['slug' => 'active-one', 'is_active' => true]);
        $this->makeType(['slug' => 'inactive-one', 'is_active' => false]);

        $results = MembershipType::active()->get();
        $this->assertCount(1, $results);
        $this->assertEquals('active-one', $results->first()->slug);
    }

    /** @test */
    public function it_is_lifetime_when_duration_months_is_null(): void
    {
        $type = $this->makeType(['duration_months' => null]);
        $this->assertTrue($type->isLifetime());
    }

    /** @test */
    public function slug_must_be_unique_per_organisation(): void
    {
        $this->makeType(['slug' => 'annual']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->makeType(['slug' => 'annual']);
    }

    /** @test */
    public function it_soft_deletes(): void
    {
        $type = $this->makeType();
        $type->delete();
        $this->assertSoftDeleted('membership_types', ['id' => $type->id]);
    }
}
```

**Create similar test files for:**
- `MembershipApplicationTest.php`
- `MembershipFeeTest.php`
- `MembershipRenewalTest.php`

### **Step 1.2: Create Migrations**

```bash
php artisan make:migration create_membership_types_table
php artisan make:migration create_membership_applications_table
php artisan make:migration create_membership_fees_table
php artisan make:migration create_membership_renewals_table
php artisan make:migration add_ended_fields_to_members_table
```

**Migration 1: `create_membership_types_table.php`**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->char('fee_currency', 3)->default('EUR');
            $table->unsignedSmallInteger('duration_months')->nullable(); // null = lifetime
            $table->boolean('requires_approval')->default(true);
            $table->json('form_schema')->nullable();
            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['organisation_id', 'slug']);
            $table->index(['organisation_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_types');
    }
};
```

**Migration 2: `create_membership_applications_table.php`**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('membership_type_id')->constrained('membership_types')->restrictOnDelete();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])->default('draft');
            $table->json('application_data')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('lock_version')->default(0); // Optimistic locking
            $table->timestamp('submitted_at')->nullable();
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['organisation_id', 'user_id']);
            $table->index(['organisation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_applications');
    }
};
```

### **Step 1.3: Create Models**

**File: `app/Models/MembershipType.php`**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipType extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'id', 'organisation_id', 'name', 'slug', 'description',
        'fee_amount', 'fee_currency', 'duration_months', 'requires_approval',
        'form_schema', 'is_active', 'sort_order', 'created_by',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'requires_approval' => 'boolean',
        'is_active' => 'boolean',
        'form_schema' => 'array',
        'duration_months' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isLifetime(): bool
    {
        return $this->duration_months === null;
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(MembershipApplication::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(MembershipFee::class);
    }
}
```

### **✅ Phase 1 Verification**
```bash
php artisan migrate
php artisan test tests/Unit/Models/
# Expected: 29 tests passing
```

---

## 📝 **Phase 2: Application Workflow**

### **Goal:** Implement application submission, admin review, approval/rejection.

### **Step 2.1: Write Feature Tests (RED Phase)**

**File: `tests/Feature/Membership/MembershipApplicationTest.php`**
```php
<?php

namespace Tests\Feature\Membership;

use App\Events\Membership\MembershipApplicationApproved;
use App\Events\Membership\MembershipApplicationRejected;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipApplicationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private User $applicant;
    private MembershipType $type;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
        
        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role' => 'admin',
        ]);
        
        $this->applicant = User::factory()->create();
        
        $this->type = MembershipType::create([
            'id' => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name' => 'Annual',
            'slug' => 'annual',
            'fee_amount' => 50.00,
            'fee_currency' => 'EUR',
            'duration_months' => 12,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function guest_cannot_submit_application(): void
    {
        $response = $this->post(
            route('organisations.membership.apply.store', $this->org->slug),
            ['membership_type_id' => $this->type->id]
        );
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function valid_application_creates_record_with_submitted_status(): void
    {
        $response = $this->actingAs($this->applicant)->post(
            route('organisations.membership.apply.store', $this->org->slug),
            ['membership_type_id' => $this->type->id]
        );
        
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('membership_applications', [
            'organisation_id' => $this->org->id,
            'user_id' => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status' => 'submitted',
        ]);
    }

    /** @test */
    public function approve_creates_organisation_user_and_member(): void
    {
        Event::fake([MembershipApplicationApproved::class]);
        
        $app = MembershipApplication::create([
            'id' => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id' => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status' => 'submitted',
            'expires_at' => now()->addDays(30),
        ]);
        
        $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.approve', [$this->org->slug, $app->id])
        );
        
        $this->assertDatabaseHas('organisation_users', [
            'organisation_id' => $this->org->id,
            'user_id' => $this->applicant->id,
        ]);
        
        $this->assertDatabaseHas('members', [
            'organisation_id' => $this->org->id,
            'status' => 'active',
        ]);
    }

    /** @test */
    public function approve_creates_pending_membership_fee(): void
    {
        Event::fake([MembershipApplicationApproved::class]);
        
        $app = MembershipApplication::create([
            'id' => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id' => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status' => 'submitted',
            'expires_at' => now()->addDays(30),
        ]);
        
        $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.approve', [$this->org->slug, $app->id])
        );
        
        $this->assertDatabaseHas('membership_fees', [
            'organisation_id' => $this->org->id,
            'membership_type_id' => $this->type->id,
            'amount' => 50.00,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function reject_sets_status_to_rejected_with_reason(): void
    {
        Event::fake([MembershipApplicationRejected::class]);
        
        $app = MembershipApplication::create([
            'id' => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id' => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status' => 'submitted',
            'expires_at' => now()->addDays(30),
        ]);
        
        $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.reject', [$this->org->slug, $app->id]),
            ['rejection_reason' => 'Incomplete documents.']
        );
        
        $this->assertDatabaseHas('membership_applications', [
            'id' => $app->id,
            'status' => 'rejected',
            'rejection_reason' => 'Incomplete documents.',
        ]);
    }
}
```

### **Step 2.2: Create Events**

**File: `app/Events/Membership/MembershipApplicationApproved.php`**
```php
<?php

namespace App\Events\Membership;

use App\Models\MembershipApplication;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipApplicationApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MembershipApplication $application,
    ) {}
}
```

**File: `app/Events/Membership/MembershipApplicationRejected.php`**
```php
<?php

namespace App\Events\Membership;

use App\Models\MembershipApplication;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipApplicationRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MembershipApplication $application,
        public readonly string $reason,
    ) {}
}
```

### **Step 2.3: Create Controller**

**File: `app/Http/Controllers/Membership/MembershipApplicationController.php`**
```php
<?php

namespace App\Http\Controllers\Membership;

use App\Events\Membership\MembershipApplicationApproved;
use App\Events\Membership\MembershipApplicationRejected;
use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\UserOrganisationRole;
use App\Policies\MembershipPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class MembershipApplicationController extends Controller
{
    public function create(Organisation $organisation): Response
    {
        $types = MembershipType::where('organisation_id', $organisation->id)
            ->active()
            ->orderBy('sort_order')
            ->get(['id', 'name', 'fee_amount', 'fee_currency', 'duration_months', 'description']);
        
        return Inertia::render('Organisations/Membership/Apply', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'types' => $types,
        ]);
    }
    
    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $validated = $request->validate([
            'membership_type_id' => ['required', 'uuid'],
            'application_data' => ['nullable', 'array'],
        ]);
        
        $user = $request->user();
        
        // Check if already a member
        $alreadyMember = OrganisationUser::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereHas('member', fn($q) => $q->withoutGlobalScopes()->where('status', 'active'))
            ->exists();
        
        if ($alreadyMember) {
            return back()->withErrors(['error' => 'You are already an active member.']);
        }
        
        // Check for pending application
        $hasPending = MembershipApplication::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['draft', 'submitted', 'under_review'])
            ->exists();
        
        if ($hasPending) {
            return back()->withErrors(['error' => 'You already have a pending application.']);
        }
        
        // Verify type is active
        $type = MembershipType::where('id', $validated['membership_type_id'])
            ->where('organisation_id', $organisation->id)
            ->where('is_active', true)
            ->first();
        
        if (!$type) {
            return back()->withErrors(['membership_type_id' => 'The selected membership type is not available.']);
        }
        
        MembershipApplication::create([
            'id' => (string) Str::uuid(),
            'organisation_id' => $organisation->id,
            'user_id' => $user->id,
            'membership_type_id' => $type->id,
            'status' => 'submitted',
            'application_data' => $validated['application_data'] ?? null,
            'expires_at' => now()->addDays(config('membership.application_expiry_days', 30)),
            'submitted_at' => now(),
        ]);
        
        return redirect()->route('organisations.voter-hub', $organisation->slug)
            ->with('success', 'Application submitted successfully.');
    }
    
    public function approve(Request $request, Organisation $organisation, MembershipApplication $application): RedirectResponse
    {
        $this->authorizeForOrg($request->user(), $organisation, 'approveApplication');
        abort_if($application->organisation_id !== $organisation->id, 404);
        
        if (!$application->isPending()) {
            return back()->withErrors(['error' => 'This application has already been processed.']);
        }
        
        try {
            DB::transaction(function () use ($application, $request, $organisation) {
                $application->approve($request->user()->id);
                
                $type = $application->membershipType;
                
                // Create OrganisationUser
                $orgUser = OrganisationUser::firstOrCreate(
                    ['organisation_id' => $organisation->id, 'user_id' => $application->user_id],
                    ['id' => (string) Str::uuid(), 'role' => 'member', 'status' => 'active']
                );
                
                // Create UserOrganisationRole
                UserOrganisationRole::firstOrCreate(
                    ['organisation_id' => $organisation->id, 'user_id' => $application->user_id],
                    ['id' => (string) Str::uuid(), 'role' => 'member']
                );
                
                // Create Member
                $expiresAt = $type->duration_months ? now()->addMonths($type->duration_months) : null;
                $member = Member::create([
                    'id' => (string) Str::uuid(),
                    'organisation_id' => $organisation->id,
                    'organisation_user_id' => $orgUser->id,
                    'status' => 'active',
                    'joined_at' => now(),
                    'membership_expires_at' => $expiresAt,
                    'created_by' => $request->user()->id,
                ]);
                
                // Create pending fee
                MembershipFee::create([
                    'id' => (string) Str::uuid(),
                    'organisation_id' => $organisation->id,
                    'member_id' => $member->id,
                    'membership_type_id' => $type->id,
                    'amount' => $type->fee_amount,
                    'currency' => $type->fee_currency,
                    'fee_amount_at_time' => $type->fee_amount,
                    'currency_at_time' => $type->fee_currency,
                    'status' => 'pending',
                    'recorded_by' => $request->user()->id,
                ]);
                
                event(new MembershipApplicationApproved($application));
            });
        } catch (ApplicationAlreadyProcessedException $e) {
            return back()->withErrors(['error' => 'This application was already processed by another administrator.']);
        }
        
        return redirect()->route('organisations.membership.applications.index', $organisation->slug)
            ->with('success', 'Application approved successfully.');
    }
    
    private function authorizeForOrg($user, Organisation $organisation, string $ability): void
    {
        $policy = new MembershipPolicy();
        $allowed = match($ability) {
            'viewApplications' => $policy->viewApplications($user, $organisation),
            'approveApplication' => $policy->approveApplication($user, $organisation),
            'rejectApplication' => $policy->rejectApplication($user, $organisation),
            default => false,
        };
        abort_if(!$allowed, 403);
    }
}
```

### **Step 2.4: Add Routes**

**File: `routes/organisations.php`**
```php
<?php

use App\Http\Controllers\Membership\MembershipApplicationController;

// Public routes (no organisation membership required)
Route::prefix('organisations/{organisation:slug}/membership')
    ->name('organisations.membership.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/apply', [MembershipApplicationController::class, 'create'])->name('apply');
        Route::post('/apply', [MembershipApplicationController::class, 'store'])->name('apply.store');
    });

// Admin routes (require organisation membership)
Route::prefix('organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        Route::prefix('/membership')->name('organisations.membership.')->group(function () {
            Route::get('/applications', [MembershipApplicationController::class, 'index'])->name('applications.index');
            Route::get('/applications/{application}', [MembershipApplicationController::class, 'show'])->name('applications.show');
            Route::patch('/applications/{application}/approve', [MembershipApplicationController::class, 'approve'])->name('applications.approve');
            Route::patch('/applications/{application}/reject', [MembershipApplicationController::class, 'reject'])->name('applications.reject');
        });
    });
```

### **Step 2.5: Create Vue Components**

**File: `resources/js/Pages/Organisations/Membership/Apply.vue`**
```vue
<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold">Apply for {{ organisation.name }} Membership</h1>
        </template>

        <div class="max-w-2xl mx-auto py-8">
            <div v-if="$page.props.flash?.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div v-for="type in types" :key="type.id" class="border rounded-lg p-4">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="radio" v-model="form.membership_type_id" :value="type.id" required />
                        <div>
                            <h3 class="font-semibold">{{ type.name }}</h3>
                            <p class="text-sm text-gray-600">{{ type.description }}</p>
                            <p class="text-sm font-medium mt-1">
                                Fee: {{ type.fee_amount }} {{ type.fee_currency }}
                                <span v-if="type.duration_months">({{ type.duration_months }} months)</span>
                                <span v-else>(Lifetime)</span>
                            </p>
                        </div>
                    </label>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    {{ form.processing ? 'Submitting...' : 'Submit Application' }}
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    organisation: Object,
    types: Array,
});

const form = useForm({
    membership_type_id: null,
    application_data: null,
});

const submit = () => {
    form.post(route('organisations.membership.apply.store', props.organisation.slug));
};
</script>
```

### **✅ Phase 2 Verification**
```bash
php artisan test tests/Feature/Membership/MembershipApplicationTest.php
# Expected: 14 tests passing
```

---

## 💰 **Phase 3: Fee & Renewal**

### **Goal:** Implement fee payment recording and membership renewal.

### **Step 3.1: Write Feature Tests**

**File: `tests/Feature/Membership/MembershipFeeTest.php`** (partial)
```php
/** @test */
public function admin_can_record_payment(): void
{
    Event::fake([MembershipFeePaid::class]);
    
    $response = $this->actingAs($this->admin)->post(
        route('organisations.members.fees.pay', [$this->org->slug, $this->member->id, $this->fee->id]),
        ['payment_method' => 'bank_transfer', 'payment_reference' => 'REF-001']
    );
    
    $response->assertRedirect();
    $this->assertDatabaseHas('membership_fees', [
        'id' => $this->fee->id,
        'status' => 'paid',
    ]);
}
```

### **Step 3.2: Create Fee Controller**

**File: `app/Http/Controllers/Membership/MembershipFeeController.php`**
```php
<?php

namespace App\Http\Controllers\Membership;

use App\Events\Membership\MembershipFeePaid;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\Organisation;
use App\Policies\MembershipPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MembershipFeeController extends Controller
{
    public function pay(Request $request, Organisation $organisation, Member $member, MembershipFee $fee)
    {
        $this->authorizeRecordPayment($request->user(), $organisation);
        abort_if($fee->member_id !== $member->id, 404);
        
        if ($fee->status !== 'pending') {
            return back()->withErrors(['error' => 'This fee has already been processed.']);
        }
        
        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:200'],
            'idempotency_key' => ['nullable', 'string', 'max:100', 'unique:membership_fees'],
        ]);
        
        DB::transaction(function () use ($fee, $validated, $request) {
            $fee->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'idempotency_key' => $validated['idempotency_key'] ?? null,
                'recorded_by' => $request->user()->id,
            ]);
            
            event(new MembershipFeePaid($fee));
        });
        
        return back()->with('success', 'Payment recorded successfully.');
    }
}
```

### **Step 3.3: Add Member Model Methods**

**File: `app/Models/Member.php`** (add these methods)
```php
public function canSelfRenew(): bool
{
    if ($this->status !== 'active' || $this->membership_expires_at === null) {
        return false;
    }
    
    $windowDays = config('membership.self_renewal_window_days', 90);
    return $this->membership_expires_at->isAfter(now()->subDays($windowDays));
}

public function endMembership(?string $reason = null): void
{
    DB::transaction(function () use ($reason) {
        $this->update([
            'status' => 'ended',
            'ended_at' => now(),
            'end_reason' => $reason,
        ]);
        
        $this->fees()->where('status', 'pending')->update(['status' => 'waived']);
        
        $userId = $this->organisationUser?->user_id;
        if ($userId) {
            ElectionMembership::where('user_id', $userId)
                ->where('status', 'active')
                ->update(['status' => 'removed']);
        }
    });
}
```

### **✅ Phase 3 Verification**
```bash
php artisan test tests/Feature/Membership/MembershipFeeTest.php
php artisan test tests/Feature/Membership/MembershipRenewalTest.php
# Expected: 22 tests passing
```

---

## 🔧 **Phase 4: Types Management & Jobs**

### **Goal:** Implement membership type CRUD, expiry jobs, and election eligibility.

### **Step 4.1: Write Tests**

**File: `tests/Feature/Membership/MembershipTypeTest.php`**
```php
/** @test */
public function only_owner_can_create_type(): void
{
    $response = $this->actingAs($this->owner)->post(
        route('organisations.membership-types.store', $this->org->slug),
        ['name' => 'Gold', 'slug' => 'gold', 'fee_amount' => 100.00, 'fee_currency' => 'EUR', 'duration_months' => 12]
    );
    
    $response->assertRedirect();
    $this->assertDatabaseHas('membership_types', ['slug' => 'gold']);
}

/** @test */
public function admin_cannot_create_type(): void
{
    $response = $this->actingAs($this->admin)->post(
        route('organisations.membership-types.store', $this->org->slug),
        ['name' => 'Silver', 'slug' => 'silver', 'fee_amount' => 75.00, 'fee_currency' => 'EUR', 'duration_months' => 12]
    );
    
    $response->assertForbidden();
}
```

### **Step 4.2: Create Type Controller**

**File: `app/Http/Controllers/Membership/MembershipTypeController.php`**
```php
<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Policies\MembershipPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MembershipTypeController extends Controller
{
    public function index(Request $request, Organisation $organisation)
    {
        $this->authorizeManageTypes($request->user(), $organisation);
        
        $types = MembershipType::where('organisation_id', $organisation->id)
            ->orderBy('sort_order')
            ->paginate(20);
        
        return Inertia::render('Organisations/Membership/Types/Index', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'types' => $types,
        ]);
    }
    
    public function store(Request $request, Organisation $organisation)
    {
        $this->authorizeManageTypes($request->user(), $organisation);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', Rule::unique('membership_types')->where('organisation_id', $organisation->id)],
            'fee_amount' => ['required', 'numeric', 'min:0'],
            'fee_currency' => ['required', 'string', 'size:3'],
            'duration_months' => ['nullable', 'integer', 'min:1'],
            'requires_approval' => ['boolean'],
            'is_active' => ['boolean'],
        ]);
        
        MembershipType::create([
            'id' => (string) Str::uuid(),
            'organisation_id' => $organisation->id,
            ...$validated,
            'created_by' => $request->user()->id,
        ]);
        
        return redirect()->route('organisations.membership-types.index', $organisation->slug)
            ->with('success', 'Membership type created successfully.');
    }
    
    private function authorizeManageTypes($user, Organisation $organisation): void
    {
        abort_if(!(new MembershipPolicy())->manageMembershipTypes($user, $organisation), 403);
    }
}
```

### **Step 4.3: Create Expiry Command**

**File: `app/Console/Commands/ProcessMembershipExpiryCommand.php`**
```php
<?php

namespace App\Console\Commands;

use App\Models\MembershipApplication;
use App\Models\MembershipFee;
use Illuminate\Console\Command;

class ProcessMembershipExpiryCommand extends Command
{
    protected $signature = 'membership:process-expiry';
    protected $description = 'Auto-reject expired applications and mark overdue fees.';
    
    public function handle(): int
    {
        $count = MembershipApplication::whereIn('status', ['submitted', 'under_review', 'draft'])
            ->where('expires_at', '<', now())
            ->update([
                'status' => 'rejected',
                'rejection_reason' => 'Application expired automatically.',
                'reviewed_at' => now(),
            ]);
        
        $this->info("Rejected {$count} expired application(s).");
        
        $overdueFees = MembershipFee::overdue()->update(['status' => 'overdue']);
        $this->info("Marked {$overdueFees} overdue fee(s).");
        
        return Command::SUCCESS;
    }
}
```

**Schedule in `routes/console.php`:**
```php
Schedule::command('membership:process-expiry')->daily();
```

### **Step 4.4: Update Election Eligibility Scope**

**File: `app/Models/ElectionMembership.php`**
```php
public function scopeEligible($query)
{
    return $query->where('election_memberships.status', 'active')
        ->where(function ($q) {
            $q->whereNull('election_memberships.expires_at')
              ->orWhere('election_memberships.expires_at', '>', now());
        })
        ->whereExists(function ($sub) {
            $sub->select(\DB::raw(1))
                ->from('organisation_users')
                ->join('members', 'members.organisation_user_id', '=', 'organisation_users.id')
                ->whereColumn('organisation_users.user_id', 'election_memberships.user_id')
                ->whereColumn('organisation_users.organisation_id', 'election_memberships.organisation_id')
                ->where('members.status', 'active')
                ->where(function ($mq) {
                    $mq->whereNull('members.membership_expires_at')
                       ->orWhere('members.membership_expires_at', '>', now());
                });
        });
}
```

### **✅ Phase 4 Verification**
```bash
php artisan test tests/Feature/Membership/MembershipTypeTest.php
php artisan test tests/Unit/Jobs/ProcessMembershipExpiryJobTest.php
# Expected: 10 tests passing
```

---

## 🧪 **Testing Strategy**

### **Test Hierarchy**
```
tests/
├── Unit/
│   ├── Policies/
│   │   └── MembershipPolicyTest.php (25 tests)
│   ├── Models/
│   │   ├── MembershipTypeTest.php (7 tests)
│   │   ├── MembershipApplicationTest.php (10 tests)
│   │   ├── MembershipFeeTest.php (6 tests)
│   │   ├── MembershipRenewalTest.php (6 tests)
│   │   └── MemberTest.php (8 tests)
│   └── Jobs/
│       └── ProcessMembershipExpiryJobTest.php (4 tests)
└── Feature/
    └── Membership/
        ├── MembershipApplicationTest.php (14 tests)
        ├── MembershipFeeTest.php (7 tests)
        ├── MembershipRenewalTest.php (7 tests)
        └── MembershipTypeTest.php (6 tests)
```

### **Running Tests**
```bash
# Run all tests
php artisan test

# Run specific phase
php artisan test tests/Unit/Policies/
php artisan test tests/Unit/Models/
php artisan test tests/Feature/Membership/

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter MembershipApplicationTest
```

---

## 🐛 **Common Issues & Solutions**

### **Issue 1: Global Scope Filtering in Public Routes**

**Problem:** `BelongsToTenant` scope filters out results when `current_organisation_id` is not in session.

**Solution:** Use `withoutGlobalScopes()` in public routes:
```php
$alreadyMember = OrganisationUser::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->where('user_id', $user->id)
    ->exists();
```

### **Issue 2: MySQL Partial Unique Index**

**Problem:** MySQL doesn't support `WHERE` clauses on unique indexes.

**Solution:** Use application-level validation instead:
```php
$exists = MembershipApplication::where('organisation_id', $org->id)
    ->where('user_id', $user->id)
    ->whereIn('status', ['draft', 'submitted', 'under_review'])
    ->exists();
```

### **Issue 3: Optimistic Locking Race Conditions**

**Problem:** Two admins approving the same application simultaneously.

**Solution:** Use `lock_version` column and check in update:
```php
$updated = static::where('id', $this->id)
    ->where('lock_version', $this->lock_version)
    ->update(['status' => 'approved', 'lock_version' => $this->lock_version + 1]);
```

---

## 📋 **Deployment Checklist**

### **Pre-Deployment**
- [ ] All tests passing (`php artisan test`)
- [ ] Test coverage ≥ 80%
- [ ] Migrations run on staging (`php artisan migrate`)
- [ ] Queue worker running for email notifications
- [ ] Scheduled jobs configured (`php artisan schedule:work`)

### **Environment Variables**
```env
MEMBERSHIP_GRACE_PERIOD_DAYS=30
MEMBERSHIP_SELF_RENEWAL_WINDOW_DAYS=90
MEMBERSHIP_APPLICATION_EXPIRY_DAYS=30
```

### **Post-Deployment**
- [ ] Verify public application form works
- [ ] Test admin approval/rejection flow
- [ ] Verify fee payment recording
- [ ] Test self-renewal functionality
- [ ] Confirm expiry job runs daily

---

## 📚 **Additional Resources**

- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Vue.js Documentation](https://vuejs.org/)
- [TDD Best Practices](https://martinfowler.com/bliki/TestDrivenDevelopment.html)

---

**🎉 Congratulations! You've successfully implemented a complete membership management system with full TDD coverage.**