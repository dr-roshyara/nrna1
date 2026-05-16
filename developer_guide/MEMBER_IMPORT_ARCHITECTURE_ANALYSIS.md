# Member Import Feature - Architecture Analysis Report

**Date:** 2026-05-16  
**Status:** ⚠️ **CRITICAL ARCHITECTURAL MISMATCH**  
**Severity:** HIGH  
**Priority:** URGENT - Refactoring Required  

---

## Executive Summary

The member import feature **does NOT follow the DDD (Domain-Driven Design) architecture** used elsewhere in the codebase. It bypasses the Member domain aggregate entirely and writes directly to the database using Eloquent, violating core architectural principles.

### Key Issues

| Issue | Impact | Severity |
|-------|--------|----------|
| Direct Eloquent DB writes instead of domain aggregate | No domain validation, no events, no audit trail | 🔴 CRITICAL |
| Bypasses MemberRepository interface | Tightly coupled to database, hard to test | 🔴 CRITICAL |
| No MemberRegistered events emitted | Missing event handlers, no CQRS sync | 🟠 HIGH |
| No duplicate member detection at domain level | Potential data inconsistency | 🟠 HIGH |
| Mixed concern: parsing CSV + importing in one class | Hard to test, maintain, extend | 🟡 MEDIUM |

---

## Step 1: Current Implementation Analysis

### 1.1 Route Definitions

```
GET    /organisations/{slug}/members/import           → MemberImportController::create()
GET    /organisations/{slug}/members/import/tutorial  → MemberImportController::tutorial()
GET    /organisations/{slug}/members/import/template  → MemberImportController::template()
POST   /organisations/{slug}/members/import           → MemberImportController::store()
GET    /organisations/{slug}/members/import/{id}/status → MemberImportController::status()
```

**Controller:** `app/Http/Controllers/Organisations/MemberImportController.php`

### 1.2 Current Architecture

```
HTTP Request (File Upload)
    ↓
MemberImportController.store()
    - Validates file type/size
    - Stores file in storage
    - Creates MemberImportJob record
    - Dispatches ProcessMemberImportJob queue job (async)
    ↓
ProcessMemberImportJob (Queue Worker)
    - Parses CSV file (auto-detects delimiter)
    - Reads headers (flexible column matching: email, firstname, lastname, etc.)
    - In chunks of 500:
        - Extracts row data
        - Calls insertChunk() for bulk insert
    ↓
insertChunk() Method (THE PROBLEM 🔴)
    - Uses DB::table('users')->insert()           ❌ Direct Eloquent
    - Uses DB::table('user_organisation_roles')->insert()  ❌ Direct Eloquent
    - Uses DB::table('organisation_users')->insert()       ❌ Direct Eloquent
    - Uses DB::table('members')->insert()         ❌ Direct Eloquent (NOT aggregate)
    ↓
Database Updated
    But... NO domain events fired! ❌
    No MemberRegistered events dispatched
    No MemberContextModel/read model updated
    No event listeners triggered
```

### 1.3 File Locations

| Component | File Path |
|-----------|-----------|
| **HTTP Controller** | `app/Http/Controllers/Organisations/MemberImportController.php` |
| **Queue Job** | `app/Jobs/ProcessMemberImportJob.php` |
| **Job Model** | `app/Models/MemberImportJob.php` |
| **Member Domain (Correct!)** | `app/Contexts/Membership/Domain/Member/Member.php` |
| **Member Repository Interface** | `app/Contexts/Membership/Domain/Repositories/MemberRepositoryInterface.php` |
| **Member Repository Impl** | `app/Contexts/Membership/Infrastructure/Repositories/EloquentMemberRepository.php` |
| **Member Eloquent Model** | `app/Models/Member.php` |
| **Vue Component** | `resources/js/Pages/Organisations/Members/Import.vue` |

### 1.4 What Exists But Is NOT Used

✅ **Member Domain Aggregate** (exists, not used by import)
```php
Member::register(
    TenantId $tenantId,
    PersonalInfo $personalInfo,
    MembershipTypeId $membershipTypeId,
    ?MemberResidenceGeoIdentity $residenceGeoIdentity = null
): self
```
- Records `MemberRegistered` event ✅
- Enforces domain rules ✅
- Type-safe personal info ✅

✅ **MemberRepository Interface** (exists, not used)
```php
interface MemberRepositoryInterface {
    public function save(Member $member): void;
    public function findById(MemberId $id, TenantId $tenantId): ?Member;
    // ... other methods
}
```

✅ **Domain Events** (exist but never fired)
```php
class MemberRegistered extends DomainEvent {
    public MemberId $id;
    public TenantId $tenantId;
    public PersonalInfo $personalInfo;
    public MembershipTypeId $membershipTypeId;
    public DateTimeImmutable $occurredAt;
}
```

---

## Step 2: Data Flow Analysis

### Current (WRONG) Flow

```
CSV Row: "john@example.com, John, Doe, active, paid"
    ↓
ProcessMemberImportJob::insertChunk()
    ↓
    1. Check if user exists in users table by email
       ✗ Direct DB query, no repository
    ↓
    2. If user doesn't exist:
       • Generate UUID for user
       • DB::table('users')->insert()  ❌ NO USER AGGREGATE
       ✗ Direct insert, bypasses any User validation
    ↓
    3. Create/update user_organisation_roles
       • DB::table('user_organisation_roles')->insert()  ❌ Direct insert
    ↓
    4. Create/update organisation_users
       • DB::table('organisation_users')->insert()  ❌ Direct insert
    ↓
    5. Create members record
       • DB::table('members')->insert()  ❌ BYPASSES MEMBER AGGREGATE
       ✗ NO Member::register() call
       ✗ NO MemberRegistered event emitted
    ↓
Database Updated ✓ (but incomplete)
    - users table ✓
    - user_organisation_roles table ✓
    - organisation_users table ✓
    - members table ✓
    - NO domain events ✗
    - NO read models updated ✗
    - NO projections updated ✗
```

### Correct (DDD) Flow Should Be

```
CSV Row: "john@example.com, John, Doe, active, paid"
    ↓
MemberImportService
    1. Parse CSV row → MemberImportCommand DTO
    2. For each row:
       ↓
       Validate:
         - Email format ✓
         - Required fields (name, email) ✓
         - Geo unit exists (if provided) ✓
         - Membership type exists ✓
         - Duplicate member detection ✓
       ↓
       Find or Create User (via UserRepository or UserService)
       ↓
       Create OrganisationUser (if needed)
       ↓
       Call Member::register(
           $tenantId,
           PersonalInfo::fromArray([...]),
           $membershipTypeId,
           $residenceGeoIdentity
       )
       ↓
       Get MemberRegistered event from aggregate
       ↓
       $memberRepository->save($member)
           → Persists to members table
           → Event listener receives MemberRegistered event
           → Updates read models / projections
           → Triggers other event handlers
    ↓
Domain Event Dispatched ✓
    MemberRegistered($memberId, $tenantId, ...)
    ↓
Event Listeners Triggered ✓
    - MemberContextModelProjector
    - MembershipTypeEventHandler
    - WelcomeEmailHandler (maybe)
    - AuditLogHandler (maybe)
    ↓
System Fully Synchronized ✓
```

---

## Step 3: Detailed Gap Analysis

### 3.1 Domain Aggregate Usage

| Aspect | Required | Current | Status |
|--------|----------|---------|--------|
| **Use Member::register()** | YES | ❌ Uses DB::insert | 🔴 WRONG |
| **Validate PersonalInfo** | YES | ❌ No validation | 🔴 WRONG |
| **Check Membership Type** | YES | ✓ Queries it | 🟡 PARTIAL |
| **Emit MemberRegistered** | YES | ❌ Never fires | 🔴 WRONG |
| **Use Repository.save()** | YES | ❌ Uses DB directly | 🔴 WRONG |
| **Respect Geo Unit** | YES | ❌ Not handled | 🔴 MISSING |

### 3.2 Validation Gaps

#### Currently Implemented
✓ Email exists check  
✓ CSV column auto-detection  
✓ Date parsing with fallbacks  
✓ Status enum validation (active|expired|suspended|ended)  
✓ Fees status validation (paid|unpaid|partial|exempt)  
✓ Transaction wrapping (DB::transaction)  
✓ Chunk processing (500 rows at a time)  

#### Missing
❌ Email format validation (RFC 5322)  
❌ Duplicate member detection at domain level  
❌ Required field validation (first_name, last_name, email)  
❌ Geo unit existence check (if geo_unit_id provided)  
❌ Membership type existence check (if specified)  
❌ Personal info value object creation  
❌ MemberResidenceGeoIdentity creation  
❌ Duplicate email across entire platform check  

### 3.3 Event Emission

| Event | Should Fire | Currently Fires | Impact |
|-------|------------|-----------------|--------|
| **MemberRegistered** | For each new member | ❌ NO | No event handlers run, no projections update |
| **MemberActivated** | For imported active members | ❌ NO | State out of sync |
| **OrganisationUserCreated** | When creating org link | ❌ NO | Missing event trail |
| **UserRegistered** | When creating new user | ❌ NO | Missing event trail |

---

## Step 4: Architecture Alignment Score

### DDD Compliance Checklist

| Principle | Requirement | Implemented | Score |
|-----------|------------|-------------|-------|
| **Domain Aggregate** | Use Member aggregate | ❌ NO | 0/10 |
| **Repository Pattern** | Inject & use repository | ❌ NO | 0/10 |
| **Domain Events** | Record & dispatch events | ❌ NO | 0/10 |
| **Value Objects** | Use PersonalInfo, MemberId, etc. | ❌ PARTIAL | 2/10 |
| **Separation of Concerns** | CSV parsing ≠ domain logic | ❌ MIXED | 3/10 |
| **Validation** | Domain enforces rules | ❌ NO | 0/10 |
| **Tenant Isolation** | Scoped by tenant | ✓ YES | 10/10 |
| **Idempotency** | Update if exists, create if not | ✓ YES | 10/10 |

**Overall Score: 2.5/10** 🔴 **CRITICAL**

### Comparison to Correct Implementation (Committee Member Assignment)

```
Committee Member Assignment (Phase 1 - Done Correctly) ✅
  ├─ Domain Aggregate (Committee) ✅
  ├─ Repository Interface ✅
  ├─ Repository Implementation ✅
  ├─ Event Emission (MemberAssignedToCommittee) ✅
  ├─ Event Listener (CommitteeMemberProjectionListener) ✅
  ├─ Read Model Sync ✅
  └─ API Controller using Aggregate ✅
  Result: 100% DDD Aligned ✓

Member Import Feature (Current) ❌
  ├─ Domain Aggregate (Member) ✓ AVAILABLE BUT NOT USED
  ├─ Repository Interface ✓ AVAILABLE BUT NOT USED
  ├─ Repository Implementation ✓ AVAILABLE BUT NOT USED
  ├─ Event Emission ❌ NEVER FIRES
  ├─ Event Listener ❌ CAN'T SYNC (no events)
  ├─ Read Model Sync ❌ MANUAL DB WRITES
  └─ API Controller using Aggregate ❌ USES DB DIRECTLY
  Result: 25% DDD Aligned ✗
```

---

## Step 5: Identified Issues

### Issue #1: No Domain Event Emission 🔴 CRITICAL

**Problem:** When importing members, MemberRegistered event is never fired.

**Consequence:**
- Event listeners don't run
- No email invitations sent (if email handler exists)
- No audit trail created
- No read models updated automatically
- System becomes inconsistent

**Example:**
```php
// WRONG - Current Implementation
DB::table('members')->insert([
    'id' => (string) Str::uuid(),
    'organisation_id' => $org->id,
    // ... other fields
]);
// Event fires: NONE ❌

// CORRECT - Should Be
$member = Member::register($tenantId, $personalInfo, $membershipTypeId);
$memberRepository->save($member);
// Event fires: MemberRegistered ✅
// Listeners run automatically
```

### Issue #2: Bypasses Domain Validation 🔴 CRITICAL

**Problem:** No use of Member aggregate means no domain rules enforced.

**Validation missing:**
- Email format validation
- Personal info completeness
- Membership type validity
- Duplicate member check

**Example:**
```php
// WRONG - No validation
DB::table('members')->insert(['email' => 'invalid-email']);
// Result: Invalid member created ❌

// CORRECT - Would be rejected
try {
    $member = Member::register(
        $tenantId,
        PersonalInfo::fromArray(['email' => 'invalid-email']),
        $membershipTypeId
    );
} catch (InvalidPersonalInfoException $e) {
    // Invalid email rejected at domain level ✓
}
```

### Issue #3: Tight Database Coupling 🟠 HIGH

**Problem:** Direct DB::table() calls make testing impossible without real database.

**Current:**
```php
DB::table('members')->insert([...]);  // 🔴 Hard to test
```

**Should Be:**
```php
$this->memberRepository->save($member);  // ✓ Testable via mock
```

### Issue #4: No Geo Unit Handling 🟠 HIGH

**Problem:** CSV might include geo unit, but import ignores it.

**Missing:**
```php
// Not handled:
if (!empty($row['geoUnitId'])) {
    $geoUnit = GeoUnit::find($row['geoUnitId']);
    $residenceGeo = MemberResidenceGeoIdentity::from($geoUnit);
    $member = Member::register(..., $residenceGeo);
}
```

### Issue #5: Mixed Concerns 🟡 MEDIUM

**Problem:** CSV parsing + importing is one class. Hard to test separately.

**Current:**
```
ProcessMemberImportJob.php
├─ CSV parsing (detectDelimiter, parseDate, etc.)
├─ Data extraction (column matching)
├─ Database writes
└─ All mixed together
```

**Should Be:**
```
MemberImportService
├─ MemberCsvParser (parsing only)
├─ MemberImportValidator (validation only)
├─ MemberImportCommand (DTO/command)
├─ Member aggregate (domain logic)
└─ MemberRepository (persistence)
```

### Issue #6: No Duplicate Detection 🟡 MEDIUM

**Problem:** If member already exists, import tries to update but uses DB directly.

**Current:** (lines 224-242 of ProcessMemberImportJob)
```php
if (isset($existingMembers[$row['email']])) {
    // Update path exists but:
    // 1. No domain events (MemberActivated, etc.)
    // 2. Direct DB update bypasses aggregate
    // 3. No validation of new state
}
```

---

## Step 6: Refactoring Recommendation

### Severity: **URGENT** 🔴

The import feature must be refactored to use domain aggregates. This is **blocking** several features:
1. Event-driven integrations (email, notifications)
2. Member state tracking (active/suspended/terminated)
3. Audit trail generation
4. CQRS read model consistency

### Recommended Approach

**Phase 1: Create Import Service Layer (TDD)**
1. Create `MemberImportService` in Application layer
2. Create `MemberImportCommand` DTO
3. Create `MemberImportValidator`
4. Create `MemberCsvParser`
5. Write tests FIRST (TDD)

**Phase 2: Refactor Job to Use Service**
1. Update `ProcessMemberImportJob` to call service
2. Remove direct DB inserts
3. Use Member aggregate + repository

**Phase 3: Add Event Handling**
1. Ensure MemberRegistered events fire
2. Update event listeners
3. Test end-to-end

**Phase 4: Enhance Vue Component**
1. Add preview validation feedback
2. Show import statistics
3. Handle error reporting

### Time Estimate

| Phase | Hours | Notes |
|-------|-------|-------|
| **Phase 1: Service Layer** | 8-10 | TDD approach, proper separation |
| **Phase 2: Job Refactor** | 6-8 | Use service, remove DB code |
| **Phase 3: Events** | 4-5 | Ensure events fire, add handlers |
| **Phase 4: UI Enhancement** | 3-4 | Better feedback, validation display |
| **Testing & QA** | 4-6 | Integration tests, manual testing |
| **Total** | **25-33 hours** | Approximately 1 week for one developer |

---

## Step 7: Step-by-Step Refactoring Plan

### Step 7.1: Create MemberImportCommand (DTO)

**File:** `app/Contexts/Membership/Application/Member/Commands/MemberImportCommand.php`

```php
final readonly class MemberImportCommand
{
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $membershipTypeId,
        public string $status = 'active',
        public string $feesStatus = 'unpaid',
        public ?\DateTime $joinedAt = null,
        public ?\DateTime $expiresAt = null,
        public ?string $geoUnitId = null,
    ) {}
}
```

### Step 7.2: Create MemberImportValidator

**File:** `app/Contexts/Membership/Application/Member/Services/MemberImportValidator.php`

```php
final class MemberImportValidator
{
    public function validate(MemberImportCommand $command): ValidationResult
    {
        $errors = [];

        // Email validation
        if (!filter_var($command->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format: {$command->email}";
        }

        // Required fields
        if (empty(trim($command->firstName)) && empty(trim($command->lastName))) {
            $errors[] = "At least first name or last name is required";
        }

        // Membership type
        $membershipType = MembershipType::find($command->membershipTypeId);
        if (!$membershipType) {
            $errors[] = "Membership type not found";
        }

        // Geo unit (if provided)
        if ($command->geoUnitId) {
            $geoUnit = GeoUnit::find($command->geoUnitId);
            if (!$geoUnit) {
                $errors[] = "Geo unit not found";
            }
        }

        return new ValidationResult(
            success: empty($errors),
            errors: $errors
        );
    }
}
```

### Step 7.3: Create MemberImportService

**File:** `app/Contexts/Membership/Application/Member/Services/MemberImportService.php`

```php
final class MemberImportService
{
    public function __construct(
        private MemberRepositoryInterface $memberRepository,
        private MemberImportValidator $validator,
        // ... other dependencies
    ) {}

    /**
     * Import a single member via domain aggregate
     */
    public function import(
        MemberImportCommand $command,
        TenantId $tenantId,
        string $createdBy
    ): Result
    {
        // Validate
        $validation = $this->validator->validate($command);
        if (!$validation->success) {
            return Result::failed($validation->errors);
        }

        try {
            // Create domain aggregate
            $personalInfo = PersonalInfo::create(
                email: $command->email,
                firstName: $command->firstName,
                lastName: $command->lastName
            );

            $membershipTypeId = MembershipTypeId::fromString($command->membershipTypeId);

            $residenceGeo = $command->geoUnitId
                ? MemberResidenceGeoIdentity::fromGeoUnitId($command->geoUnitId)
                : null;

            // Register member (fires MemberRegistered event)
            $member = Member::register(
                $tenantId,
                $personalInfo,
                $membershipTypeId,
                $residenceGeo
            );

            // Save via repository (events dispatched to listeners)
            $this->memberRepository->save($member);

            return Result::success($member->getId());

        } catch (\Throwable $e) {
            return Result::failed([$e->getMessage()]);
        }
    }
}
```

### Step 7.4: Refactor ProcessMemberImportJob

**Before:** Uses `insertChunk()` with direct DB inserts
**After:** Uses MemberImportService

```php
class ProcessMemberImportJob implements ShouldQueue
{
    public function __construct(
        private MemberImportService $importService
    ) {}

    private function insertChunk(array $chunk, Organisation $org, string $initiatedBy): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::transaction(function () use (
            $chunk, $org, $initiatedBy,
            &$imported, &$skipped, &$errors
        ) {
            foreach ($chunk as $row) {
                $command = new MemberImportCommand(
                    email: $row['email'],
                    firstName: $row['firstName'],
                    lastName: $row['lastName'],
                    membershipTypeId: $this->resolveMembershipTypeId($org),
                    status: $row['status'],
                    feesStatus: $row['feesStatus'],
                    joinedAt: $this->parseDate($row['joinedAt']),
                    expiresAt: $this->parseDate($row['expiresAt']),
                    geoUnitId: $row['geoUnitId'] ?? null,
                );

                // Use service instead of DB::insert()
                $result = $this->importService->import(
                    $command,
                    TenantId::fromString($org->id),
                    $initiatedBy
                );

                if ($result->success) {
                    $imported++;
                } else {
                    $skipped++;
                    $errors = array_merge($errors, $result->errors);
                }
            }
        });

        return [$imported, $skipped, $errors];
    }
}
```

---

## Step 8: Testing Strategy

### 8.1 Unit Tests

**File:** `tests/Unit/Membership/MemberImportServiceTest.php`

```php
class MemberImportServiceTest extends TestCase
{
    public function test_import_valid_member_fires_event()
    {
        $this->expectsEvent(MemberRegistered::class);

        $command = new MemberImportCommand(
            email: 'john@example.com',
            firstName: 'John',
            lastName: 'Doe',
            membershipTypeId: $this->membershipTypeId,
        );

        $result = $this->service->import(
            $command,
            $this->tenantId,
            $this->userId
        );

        $this->assertTrue($result->success);
    }

    public function test_import_invalid_email_rejected()
    {
        $command = new MemberImportCommand(
            email: 'invalid-email',  // Invalid!
            firstName: 'John',
            lastName: 'Doe',
            membershipTypeId: $this->membershipTypeId,
        );

        $result = $this->service->import($command, $this->tenantId, $this->userId);

        $this->assertFalse($result->success);
        $this->assertContains('Invalid email', $result->errors[0]);
    }

    public function test_import_duplicate_member_prevented()
    {
        // Create first member
        $this->service->import($command, $tenantId, $userId);

        // Try to import same email
        $result = $this->service->import($command, $tenantId, $userId);

        $this->assertFalse($result->success);
    }
}
```

### 8.2 Integration Tests

**File:** `tests/Feature/Membership/MemberImportJobTest.php`

```php
class MemberImportJobTest extends TestCase
{
    public function test_import_job_creates_members_with_events()
    {
        Event::fake();

        // Create CSV file
        $csv = "email,firstname,lastname\njohn@example.com,John,Doe";
        Storage::put('test.csv', $csv);

        // Create import job
        $importJob = MemberImportJob::create([...]);

        // Process job
        $job = new ProcessMemberImportJob($importJob->id);
        $job->handle();

        // Verify
        Event::assertDispatched(MemberRegistered::class);
        $this->assertDatabaseHas('members', ['email' => 'john@example.com']);
    }
}
```

---

## Step 9: Rollout Plan

### Phase A: Preparation (Week 1)
- [ ] Create service layer (TDD)
- [ ] Write unit tests
- [ ] Code review

### Phase B: Refactoring (Week 2)
- [ ] Update ProcessMemberImportJob
- [ ] Write integration tests
- [ ] Test in staging

### Phase C: Deployment (Week 3)
- [ ] Deploy to production
- [ ] Monitor event emission
- [ ] Update documentation

### Phase D: Cleanup (Week 4)
- [ ] Remove old code
- [ ] Update Vue component for better UX
- [ ] Performance optimization

---

## Conclusion

### Current State
❌ **NOT DDD Compliant**  
❌ **NOT Event-Driven**  
❌ **NOT Testable**  
❌ **NOT Maintainable**  

### After Refactoring
✅ **Full DDD Compliance**  
✅ **Event-Driven Architecture**  
✅ **Fully Testable**  
✅ **Maintainable & Extensible**  

### Action Required
**URGENT:** Schedule refactoring in next sprint. This blocks event-driven features and creates technical debt.

**Estimated Effort:** 25-33 hours  
**Business Impact:** Enables email notifications, audit trails, event-driven integrations  

---

**Report prepared by:** Claude Code Analysis  
**Next Review:** After refactoring completion  

