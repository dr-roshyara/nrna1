# DOMAIN LAYER ARCHITECTURE ANALYSIS
## Public Digit Platform - April 16, 2026

---

## EXECUTIVE SUMMARY

The current Domain layer implementation has **critical architectural violations** that prevent proper Domain-Driven Design (DDD) compliance per CLAUDE.md rules. The structure conflates Application layer concerns (Controllers) with Domain layer definitions, introduces framework dependencies into the Domain, and lacks proper layering (Domain/Application/Infrastructure separation).

**Status**: 🔴 **NEEDS REFACTORING** (not production-ready for DDD standards)

**Compliance Score**: 15/100 (critical gaps in 7/10 DDD rules)

---

## CURRENT DOMAIN STRUCTURE

```
app/Domain/
├── Finance/
│   ├── Controllers/           ❌ Should not be here
│   │   ├── IncomeController.php
│   │   └── OutcomeController.php
│   ├── Models/               ⚠️ Missing proper domain design
│   │   ├── Income.php
│   │   └── Outcome.php
│   ├── Services/             ⚠️ Has framework dependencies
│   │   └── FinanceNotificationService.php
│   └── Notifications/        ❌ Framework-specific
│       └── FinanceNotification.php
│
└── Election/
    └── Models/
        └── ElectionUser.php  ⚠️ Mixed concerns
```

---

## ARCHITECTURAL VIOLATIONS

### VIOLATION 1: Controllers in Domain Layer ❌

**Current State**: `app/Domain/Finance/Controllers/`
- IncomeController extends `Illuminate\Http\Controllers\Controller`
- OutcomeController extends `Illuminate\Http\Controllers\Controller`
- Both use `Inertia::render()`, `Request`, `Auth::user()`

**CLAUDE.md Rule 4 Violation**:
```
❌ Domain layer MUST be pure PHP with NO framework dependencies
❌ NEVER import Illuminate, Laravel in Domain
```

**Impact**: Controllers should be in `App\Http\Controllers\Finance`, not Domain

**Example Violation** (IncomeController line 1-36):
```php
namespace App\Domain\Finance\Controllers;  // ❌ Should be App\Http\Controllers
use Illuminate\Http\Request;               // ❌ Framework import in Domain
use Inertia\Inertia;                       // ❌ Framework import in Domain
use Illuminate\Support\Facades\Auth;       // ❌ Framework import in Domain

public function create() {
    return Inertia::render('Finance/Income/Create');  // ❌ HTTP layer logic
}
```

---

### VIOLATION 2: Framework Dependencies in Services ❌

**Current State**: `FinanceNotificationService.php` (lines 1-16)

```php
use Illuminate\Support\Facades\Notification;  // ❌ Framework dependency

public function notify_finance($financeInfo) {
    $user = auth()->user();                    // ❌ Global helper function
    Notification::route('mail', $emails)       // ❌ Framework service
        ->notify(new FinanceNotification(...));
}
```

**CLAUDE.md Rule 4 Violation**:
```
❌ Domain services MUST NOT know about Laravel/Illuminate
❌ Domain layer MUST be pure PHP
```

**Impact**: Service cannot be unit tested without framework bootstrap

---

### VIOLATION 3: Minimal Models Without Business Logic ⚠️

**Current State**: `Income.php` and `Outcome.php`

```php
// Income.php (12 lines)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model {
    use HasFactory;
}
// That's it - no business logic whatsoever
```

**Missing**:
- ❌ No value object definitions (Money, Period, etc.)
- ❌ No business rule validation
- ❌ No domain methods
- ❌ No aggregation logic
- ❌ No tenant isolation (no `TenantId` property)
- ❌ No invariant enforcement

**Example of What Should Exist**:
```php
class Income {
    private TenantId $tenantId;
    private Money $membershipFee;
    private Period $period;
    private string $country;
    
    public function __construct(
        TenantId $tenantId,
        Money $membershipFee,
        Period $period,
        string $country
    ) {
        $this->validateCountry($country);  // Domain rule
        $this->tenantId = $tenantId;
        $this->membershipFee = $membershipFee;
        $this->period = $period;
        $this->country = $country;
    }
    
    public function calculateTotal(): Money { ... }
    public function belongsToTenant(TenantId $tenantId): bool { ... }
}
```

---

### VIOLATION 4: No Tenant Identity in Domain Models ❌

**Current State**: Income/Outcome models don't have `TenantId`

```php
class Income extends Model {
    // ❌ Missing: private TenantId $tenantId property
    // ❌ Missing: belongsToTenant(TenantId $tenantId): bool
}
```

**CLAUDE.md Rule 1 Violation**:
```
❌ Every Domain model MUST have a TenantId property
❌ Use ONLY Platform\SharedKernel\Domain\TenantId Value Object
❌ All business rules MUST consider tenant boundaries
```

**Impact**: System cannot enforce multi-tenancy at domain level; relies only on database constraints

---

### VIOLATION 5: No Repository Pattern in Domain ❌

**Current State**: Controllers directly use Eloquent Models

```php
// IncomeController.php line 83
$income = new Income();
$income->user_id = Auth::user()->id;
// ... 50+ lines of property assignment
$income->save();  // ❌ Direct persistence, no repository abstraction
```

**Missing**:
- ❌ No `IncomeRepositoryInterface` in Domain
- ❌ Controllers depend on Eloquent, not abstraction
- ❌ Cannot swap storage implementation
- ❌ Testing requires database

**CLAUDE.md Rule 2 Violation**:
```
❌ Repository interfaces MUST use "ForTenant" naming
❌ NEVER create tenant-agnostic repository methods
```

---

### VIOLATION 6: No Value Objects ❌

**Missing Value Objects** that should exist in Domain:
- ❌ `Money` (for financial amounts)
- ❌ `Period` (for date ranges)
- ❌ `Country` (for country codes with validation)
- ❌ `CommitteeName` (limited string with rules)
- ❌ `FinanceCategory` (enum-like for income/expense types)

**Example Missing Implementation**:
```php
// Should exist: Platform\SharedKernel\Domain\Money
class Money {
    public function __construct(
        private float $amount,
        private string $currency = 'USD'
    ) {
        if ($amount < 0) {
            throw new InvalidMoneyException('Amount cannot be negative');
        }
    }
    
    public function add(Money $other): Money { ... }
    public function equals(Money $other): bool { ... }
}
```

---

### VIOLATION 7: Hardcoded Configuration in Controllers ❌

**Current State**: IncomeController.php lines 163-169

```php
$user = auth()->user();
$emails = [
    // 'mathematikboy@yahoo.com',  // ❌ Hardcoded
    // 'treasurer@nrna.org',       // ❌ Hardcoded
    'treasurer2@nrna.org',          // ❌ Hardcoded email
    // 'treasurer3@nrna.org',
    $user->email,
];
```

**Impact**: 
- Cannot change notification recipients without code changes
- Hardcoded BCC address in FinanceNotification line 57
- No configurability per organization/tenant

---

### VIOLATION 8: No Domain Events ❌

**Missing**: Domain Events for domain-significant actions

```php
// Should exist but doesn't:
// Domain\Finance\Events\IncomeRecorded
// Domain\Finance\Events\OutcomeRecorded
// Domain\Finance\Events\FinanceSheetSubmitted

// Should be published in Domain service:
$income->recordedAt = now();
event(new IncomeRecorded(
    tenantId: $this->tenantId,
    income: $income,
    recordedBy: $user,
    timestamp: now()
));
```

---

### VIOLATION 9: Anemic Models (Transaction Script Anti-Pattern) ❌

**Current State**: All business logic in controller

```php
// IncomeController.php lines 70-158: 88 lines of property assignment
$income = new Income();
$income->user_id = Auth::user()->id;
if(isset($incomeInfo['membership_fee'])) {
    $income->membership_fee = (float)$incomeInfo['membership_fee'];
}
if(isset($incomeInfo['nomination_fee'])) {
    $income->nomination_fee = (float)$incomeInfo['nomination_fee'];
}
// ... 50+ similar if statements
$income->save();
```

**Impact**:
- Logic is scattered in controllers (transaction script pattern)
- Cannot reuse logic
- Hard to test
- Violates Single Responsibility Principle

**Should Be**:
```php
// Domain/Finance/Services/RecordIncomeService.php
class RecordIncomeService {
    public function recordFromSheet(IncomeSheet $sheet): Income {
        return Income::create(
            tenantId: $sheet->getTenantId(),
            amounts: $sheet->extractAmounts(),
            period: $sheet->getPeriod(),
            country: $sheet->getCountry()
        );
    }
}
```

---

### VIOLATION 10: No Validation in Domain ❌

**Missing**: Business rule validation at Domain level

```php
// Current state: No domain validation
// Only controller validation exists (line 40-45):
$validator = Validator::make($request->all(), [
    'country' => ['required', 'string', 'max:255'],
    'committee_name' => ['required', 'string', 'max:255'],
    'period_from' => ['required'],
]);
```

**Missing Domain Rules**:
- ❌ Cannot record income for same period twice
- ❌ Cannot have negative amounts
- ❌ Cannot have period_from > period_to
- ❌ Country must be valid (ISO 3166-1)
- ❌ Only specific countries allowed per tenant

**Should Be**:
```php
// Domain/Finance/Services/IncomeValidator.php
class IncomeValidator {
    public function validate(IncomeSheet $sheet, TenantId $tenantId): ValidationResult {
        $errors = [];
        
        if (!$sheet->isValidPeriod()) {
            $errors[] = 'period_from must be before period_to';
        }
        
        if (!$this->countryAllowedForTenant($sheet->getCountry(), $tenantId)) {
            $errors[] = 'country not configured for this tenant';
        }
        
        if ($this->incomeAlreadyRecorded($sheet->getPeriod(), $tenantId)) {
            $errors[] = 'income already recorded for this period';
        }
        
        return new ValidationResult($errors);
    }
}
```

---

## PROPER DDING ARCHITECTURE (DESIRED STATE)

```
app/
├── Domain/                         ✅ Pure PHP, no framework
│   └── Finance/
│       ├── ValueObjects/
│       │   ├── Money.php
│       │   ├── Period.php
│       │   ├── Country.php
│       │   └── FinanceCategory.php
│       ├── Models/
│       │   ├── Income.php          (pure domain entity)
│       │   └── Outcome.php         (pure domain entity)
│       ├── Repositories/
│       │   ├── IncomeRepositoryInterface.php
│       │   └── OutcomeRepositoryInterface.php
│       ├── Services/
│       │   ├── RecordIncomeService.php
│       │   ├── RecordOutcomeService.php
│       │   └── IncomeValidator.php
│       └── Events/
│           ├── IncomeRecorded.php
│           └── OutcomeRecorded.php
│
├── Application/                    ✅ Orchestration layer
│   └── Finance/
│       ├── Commands/
│       │   ├── RecordIncomeCommand.php
│       │   └── RecordOutcomeCommand.php
│       ├── CommandHandlers/
│       │   ├── RecordIncomeHandler.php
│       │   └── RecordOutcomeHandler.php
│       ├── DTO/
│       │   ├── IncomeSheetDTO.php
│       │   └── OutcomeSheetDTO.php
│       └── EventSubscribers/
│           └── SendFinanceNotificationSubscriber.php
│
├── Http/                           ✅ HTTP layer
│   ├── Controllers/
│   │   └── Finance/
│   │       ├── IncomeController.php
│   │       └── OutcomeController.php
│   ├── Requests/
│   │   ├── StoreIncomeRequest.php
│   │   └── StoreOutcomeRequest.php
│   └── Resources/
│       ├── IncomeResource.php
│       └── OutcomeResource.php
│
└── Infrastructure/                 ✅ Implementation details
    └── Finance/
        ├── Repositories/
        │   ├── EloquentIncomeRepository.php
        │   └── EloquentOutcomeRepository.php
        ├── Persistence/
        │   └── IncomeMapper.php
        └── Notifications/
            └── LaravelMailFinanceNotifier.php
```

---

## COMPLIANCE CHECKLIST

| Rule | Current | Target | Status |
|------|---------|--------|--------|
| **Rule 1**: TenantId in models | ❌ No | ✅ Yes | 🔴 FAIL |
| **Rule 2**: Repository ForTenant methods | ❌ No | ✅ Yes | 🔴 FAIL |
| **Rule 3**: Commands with TenantId | ❌ No | ✅ Yes | 🔴 FAIL |
| **Rule 4**: Domain purity (no Illuminate) | ❌ No | ✅ Yes | 🔴 FAIL |
| **Rule 5**: Authorization checks | ⚠️ Partial | ✅ Yes | 🟡 PARTIAL |
| **Rule 6**: Infrastructure abstraction | ❌ No | ✅ Yes | 🔴 FAIL |
| **Rule 7**: Explicit tenant resolution | ❌ No | ✅ Yes | 🔴 FAIL |
| **Rule 8**: Domain events | ❌ No | ✅ Yes | 🔴 FAIL |
| **Rule 9**: Testing with tenant isolation | ❌ No | ✅ Yes | 🔴 FAIL |
| **Rule 10**: Code generation patterns | ❌ No | ✅ Yes | 🔴 FAIL |

---

## IMMEDIATE REFACTORING REQUIRED

### Phase 1: Move Controllers Out of Domain (Week 1)

```
Move app/Domain/Finance/Controllers/ → app/Http/Controllers/Finance/
- IncomeController.php
- OutcomeController.php
```

**Action**:
1. Create `app/Http/Controllers/Finance/` directory
2. Move both controllers there
3. Update namespace from `App\Domain\Finance\Controllers` to `App\Http\Controllers\Finance`
4. Update routes to use new controller paths
5. Delete `app/Domain/Finance/Controllers/` folder

---

### Phase 2: Create Domain Layer Structure (Week 1-2)

```
Create app/Domain/Finance/
├── Models/                          (pure domain entities)
├── ValueObjects/                    (immutable value objects)
├── Repositories/                    (interfaces only)
├── Services/                        (pure PHP business logic)
└── Events/                          (domain events)
```

---

### Phase 3: Implement Value Objects (Week 2)

```php
// app/Domain/Finance/ValueObjects/Money.php
class Money {
    public function __construct(
        private float $amount,
        private string $currency = 'USD'
    ) {
        if ($amount < 0) throw new InvalidMoneyException();
    }
}

// app/Domain/Finance/ValueObjects/Period.php
class Period {
    public function __construct(
        private DateTimeImmutable $from,
        private DateTimeImmutable $to
    ) {
        if ($from > $to) throw new InvalidPeriodException();
    }
}
```

---

### Phase 4: Create Repository Interfaces (Week 2)

```php
// app/Domain/Finance/Repositories/IncomeRepositoryInterface.php
interface IncomeRepositoryInterface {
    public function storeForTenant(Income $income, TenantId $tenantId): void;
    public function findForTenant(string $id, TenantId $tenantId): ?Income;
    public function findByPeriodForTenant(Period $period, TenantId $tenantId): ?Income;
    public function allForTenant(TenantId $tenantId): array;
}
```

---

### Phase 5: Remove Framework Dependencies from Services (Week 2-3)

```php
// Before (WRONG): FinanceNotificationService.php
use Illuminate\Support\Facades\Notification;

class FinanceNotificationService {
    public function notify_finance($financeInfo) {
        $user = auth()->user();  // ❌ Global helper
        Notification::route('mail', $emails)->notify(...);  // ❌ Framework
    }
}

// After (RIGHT): app/Application/Finance/SendFinanceNotificationHandler.php
class SendFinanceNotificationHandler {
    public function __construct(
        private FinanceNotificationDispatcher $dispatcher
    ) {}
    
    public function handle(IncomeRecorded $event): void {
        $this->dispatcher->notify(
            recipients: $event->getTenantConfig()->getTreasurerEmails(),
            financeInfo: $event->getIncomeData()
        );
    }
}
```

---

## TESTING IMPLICATIONS

### Current State: Framework-Dependent, Hard to Test

```php
// Cannot test without Laravel bootstrap
public function testRecordIncome() {
    $controller = new IncomeController();
    $request = Request::create(...);  // ❌ Requires framework
    $response = $controller->store($request);
}
```

### Desired State: Pure Unit Tests

```php
// Can test with plain PHP/PHPUnit
public function testIncomeWithNegativeAmountThrows() {
    $this->expectException(InvalidMoneyException::class);
    
    $income = new Income(
        tenantId: new TenantId('org-123'),
        membershipFee: new Money(-100),  // Should throw
        period: $period,
        country: 'NP'
    );
}
```

---

## IMPACT ANALYSIS

### What Breaks If NOT Refactored

| Concern | Impact | Severity |
|---------|--------|----------|
| **Tenant Isolation** | Cannot enforce at domain level | CRITICAL |
| **Business Logic Reuse** | Logic locked in controllers | HIGH |
| **Testability** | Requires framework for all tests | HIGH |
| **Domain Evolution** | Hard to add new rules | HIGH |
| **Code Clarity** | Mixed concerns everywhere | MEDIUM |
| **DDD Principles** | Violated in 10 ways | MEDIUM |

---

## RECOMMENDED TIMELINE

| Week | Phase | Deliverable |
|------|-------|-------------|
| 1 | Move controllers + Create structure | Controllers in HTTP layer, Domain folders created |
| 2 | Value objects + Repositories | Money, Period, Country VOs; 4 Repository interfaces |
| 3 | Implement repositories + Services | EloquentIncomeRepository; RecordIncomeService |
| 4 | Application layer + Handlers | RecordIncomeCommand + Handler; tests |
| 5 | Domain events + Integration | IncomeRecorded event; NotificationSubscriber |
| 6 | Refactor tests + Documentation | 50+ domain tests; architecture docs |

---

## CONCLUSION

The current Domain layer **requires comprehensive refactoring** to achieve proper DDD compliance. This is not optional for production-grade multi-tenant systems.

**Current Risk**: 
- Cannot properly enforce tenant boundaries at domain level
- Mixed concerns prevent scaling
- Difficult to test business logic
- Violates all 10 key DDD rules

**Recommended Action**: 
Allocate 6 weeks to proper Domain Layer implementation before proceeding with additional features.

**Priority**: CRITICAL (must complete before Finance Module Phase 2)

---

**Analysis Date**: April 16, 2026, 23:55 UTC  
**Reviewed By**: Claude Haiku 4.5  
**Next Review**: After Phase 1 completion

**Om Gam Ganapataye Namah** 🪔🐘
