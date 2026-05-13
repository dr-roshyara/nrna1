# Phase B Preparation: Governance Access Policies

**Status:** Ready to begin  
**Prerequisite:** Phase C.1 complete ✅  
**Risk Level:** Medium (introduces authorization boundaries)

---

## What Phase B Accomplishes

Introduces **WHO can perform governance operations**.

Current gap: Any valid caller reaching the application service can:
- CreateCommittee
- ActivateCommitteeStructure
- EvolveCommitteeStructure
- DeprecateCommittee

**Phase B enforces:** Governance capability policies that determine legal callers.

---

## Phase B Scope

### 1. Governance Capability Policies

Replace the permissive placeholder:

```php
// Current Phase C seam - always permits
final class PermissiveGovernanceAccessPolicy implements GovernanceAccessPolicyInterface {
    public function assertCanCreateCommittee(TenantId $tenantId): void {
        // Phase C: Allow all - Phase B will override
    }
}
```

With actual capability enforcement:

```php
final class GovernanceCapabilityPolicy implements GovernanceAccessPolicyInterface {
    public function assertCanCreateCommittee(TenantId $tenantId): void {
        // Check: Does caller have CREATE_COMMITTEE capability?
        // Check: Is organisation in DRAFT or ACTIVE governance state?
        // Throw DomainException if not authorized
    }
    
    public function assertCanActivateStructure(TenantId $tenantId): void {
        // Check: Does caller have ACTIVATE_STRUCTURE capability?
    }
    
    public function assertCanEvolveStructure(TenantId $tenantId): void {
        // Check: Does caller have EVOLVE_STRUCTURE capability?
    }
}
```

### 2. Metadata Axis Formalization

Separate:
```text
temporal metadata    (effective_from, effective_until)
workflow metadata    (activated_by, approval_reason)
operational metadata (last_modified_by, modification_reason)
```

Create:
```php
final class GovernanceWorkflow {
    public string $activatedBy;
    public string $activationReason;
    public DateTimeImmutable $approvedAt;
}

final class GovernanceAudit {
    public string $lastModifiedBy;
    public string $modificationReason;
    public DateTimeImmutable $modifiedAt;
}
```

### 3. Organisational Governance State

Introduce governance status on organisation:

```php
enum GovernanceStatus: string {
    case DRAFT = 'draft';           // Organisation created, structure not activated
    case ACTIVE = 'active';         // Governance active, committees can be created
    case FROZEN = 'frozen';         // Accepting no new governance changes
    case ARCHIVED = 'archived';     // Legacy organisation, read-only
}
```

Enforce transitions:
- DRAFT → ACTIVE only if base structure exists and is ACTIVE
- ACTIVE ↔ FROZEN (reversible)
- FROZEN → ARCHIVED (irreversible)

---

## Phase B Architecture

### Container Binding Pattern

```php
// Replaces PermissiveGovernanceAccessPolicy
$this->app->bind(
    GovernanceAccessPolicyInterface::class,
    GovernanceCapabilityPolicy::class
);
```

### Use Case Integration

```php
final class InternalCreateCommittee implements CreateCommitteeUseCase {
    public function execute(array $command): Committee {
        // Step 0: Check governance access (Phase B - now actual enforcement)
        $this->accessPolicy->assertCanCreateCommittee($tenantId);
        
        // Check organisation governance status
        $org = $this->organisationRepository->find($tenantId);
        if ($org->governanceStatus !== GovernanceStatus::ACTIVE) {
            throw new DomainException('Governance is not in ACTIVE state');
        }
        
        // ... rest of committee creation
    }
}
```

---

## Phase B Tests (TDD Sequence)

### T1: Container Binding
```php
// Verify PermissiveGovernanceAccessPolicy is replaced
$policy = app(GovernanceAccessPolicyInterface::class);
assert($policy instanceof GovernanceCapabilityPolicy);
```

### T2: Capability Enforcement
```php
// Verify unauthorised users cannot create committees
$this->shouldThrow(DomainException::class)
    ->when($policy->assertCanCreateCommittee($unauthorisedUserTenantId));
```

### T3: Governance State Enforcement
```php
// Verify committees cannot be created in DRAFT governance state
$org->governanceStatus = GovernanceStatus::DRAFT;
$this->shouldThrow(DomainException::class)
    ->when($useCase->execute($command));
```

### T4: State Transitions
```php
// Verify governance state transitions are enforced
$org->transitionTo(GovernanceStatus::FROZEN);
$org->transitionTo(GovernanceStatus::ACTIVE);
// This is valid
assert($org->governanceStatus === GovernanceStatus::ACTIVE);

// This is invalid
$this->shouldThrow(InvalidTransitionException::class)
    ->when($org->transitionTo(GovernanceStatus::DRAFT));
```

---

## Phase B Files to Create

| File | Purpose |
|------|---------|
| `GovernanceCapabilityPolicy.php` | Real policy enforcement (replaces permissive) |
| `GovernanceWorkflow.php` | Workflow metadata VO |
| `GovernanceAudit.php` | Audit metadata VO |
| `GovernanceStatus.php` | Enum for organisation governance state |
| `Migrations/2026_05_XX_add_governance_status_to_organisations.php` | Add governance_status column |
| `Tests/GovernanceCapabilityPolicyTest.php` | Policy enforcement tests |
| `Tests/GovernanceStateTransitionTest.php` | State machine tests |

---

## Phase B Dependencies

### Required Before Starting
- ✅ Phase C (transactional consistency)
- ✅ Phase C.1 (snapshot semantics)
- ✅ Organisation model (user_organisation_role relationship)

### External Integrations
- User role checking (Spatie Permission)
- Organisation state repository
- Tenant ID context resolution

---

## Critical Phase B Constraint

**Lock Ordering (G-009) Becomes Critical**

Phase B introduces:
```text
organisation → governance_status check → structure access check → committee mutation
```

Lock acquisition order:
1. organisation (for governance status check)
2. committee_structures (FOR UPDATE lock)
3. committees (if modifying)

**Document in ADR:** Lock acquisition order is non-negotiable.

---

## Risk Handoff

From Phase C to Phase B:

| Responsibility | Phase C | Phase B |
|---|---|---|
| Transactional consistency | ✅ | ✅ inherited |
| Lock safety | ✅ | ✅ inherited + enforce ordering |
| Snapshot immutability | ✅ | ✅ inherited |
| Governance capability | ⏳ | ✅ owns |
| Organisational state | ✅ basic | ✅ extended |
| Metadata separation | ⏳ partial | ✅ formalizes |

---

## Go/No-Go Checklist for Phase B Start

- [ ] Phase C.1 tests all passing (10/10)
- [ ] GOVERNANCE_ARCHITECTURE.md reviewed and locked
- [ ] GovernanceAccessPolicyInterface seam verified in use cases
- [ ] Organisation model extended with governance_status field
- [ ] User role system (Spatie) verified for integration
- [ ] Lock ordering ADR drafted
- [ ] Metadata separation bounded contexts sketched
- [ ] Test infrastructure ready for capability policy tests

---

## Next Meeting Point

Begin Phase B when:
1. All Phase C.1 tests stable over 48 hours
2. Architecture document reviewed by team
3. Risk assessment approved
4. GovernanceCapabilityPolicy spike completed

**Estimated Phase B Duration:** 2-3 weeks (governance policy implementation + state machine + tests)
