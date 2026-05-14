## User-Facing Implementation Guide

### What You Have Now

```
Governance Levels Table (configurable per tenant)
    │
    ├── Level 0: ICC (World)
    ├── Level 1: Region (Continent)
    ├── Level 2: NCC (Country)
    └── Level 3: LCC (State)
    
Geo Units Table (0-10 abstract levels)
    │
    ├── Level 0: ICC (World)
    ├── Level 1: REG-EU, REG-AS (Regions)
    ├── Level 2: NCC-NP, NCC-IN (Countries)
    └── Level 3: SLC-KTM, SLC-PKH (States)

Matrix Engine (validates combinations)
    │
    └── GovernancePolicy::assertAllowed(govLevel, geoLevel)
```

---

### Step-by-Step: Create a Committee

#### Step 1: Configure Governance Levels

```
Navigate: /organisations/{org}/governance/levels

Add levels for your organization:
  Level 0 → ICC Glocal Committee → Geo Level 0
  Level 1 → Region Committee → Geo Level 1  
  Level 2 → National Committee → Geo Level 2
  Level 3 → State Committee → Geo Level 3
```

#### Step 2: Define Geo Units

```
Navigate: /organisations/{org}/geo-units

Generate the Excel matrix:
  Geo Level 0 × Gov Level 0 → ICC (1 record)
  Geo Level 1 × Gov Level 1 → REG-EU, REG-AS (2 records)
  Geo Level 2 × Gov Level 2 → NCC-NP, NCC-IN (2 records)
  Geo Level 3 × Gov Level 3 → SLC-KTM, SLC-PKH (2 records)

Import the Excel → populates geo_units table
```

#### Step 3: Create a Committee

```php
// Example: Create NCC for Nepal
$handler = new CreateCommitteeHandler(
    repository: $committeeRepository,
    eventBus: $eventBus,
    policy: new GovernancePolicy(
        GovernanceMatrix::fromRows(
            GovernanceLevelDefinition::forTenant($tenantId)->toArray()
        )
    )
);

$committeeId = $handler->handle(new CreateCommitteeCommand(
    name: 'Nepal National Coordination Committee',
    assignment: new GovernanceAssignment(
        governanceLevel: 2,           // NCC
        geoLevel: 2,                  // Country
        geoUnitId: GeoUnitId::fromString('NCC-NP')
    )
));

// Result: Committee created with validated jurisdiction
// Event: CommitteeEstablished emitted
```

#### Step 4: Assign Members

```php
// Check eligibility before assigning
$eligibility = new CommitteeEligibilityPolicy();

$canJoin = $eligibility->isEligible(
    committee: $committee->getAssignment(),
    memberHome: new GovernanceAssignment(
        governanceLevel: 3,           // Member from LCC level
        geoLevel: 3,                  // State level
        geoUnitId: GeoUnitId::fromString('SLC-KTM')
    )
);

if ($canJoin) {
    $committee->assignMember($memberId, $role);
}
```

---

### What Happens Under the Hood

```
User Action: "Create NCC for Nepal"
    │
    ▼
CreateCommitteeCommand(name, GovernanceAssignment(2, 2, NCC-NP))
    │
    ▼
CreateCommitteeHandler
    │
    ▼
ConstitutionalCommittee::establish()
    │
    ├── GovernancePolicy::assertAllowed()
    │   └── GovernanceMatrix::isAllowed(2, 2)
    │       └── MatrixCell(governanceLevel: 2, geoLevel: 2, allowed: true)
    │           └── ✅ ALLOWED
    │
    ├── Records CommitteeEstablished event
    └── Persists to database
```

---

### What's Already Automated

| Action | Handled By |
|--------|-----------|
| Validate governance × geo combination | `GovernancePolicy::assertAllowed()` |
| Prevent invalid committees | `DomainException` thrown before persistence |
| Record establishment | `CommitteeEstablished` domain event |
| Backward compatibility | `LegacyJurisdiction` bridge |

---

### What You Need to Build Next

| Feature | Files to Create | Effort |
|---------|----------------|--------|
| **Committee creation UI** | Vue component + Laravel controller | 2-3 hours |
| **Geo unit management UI** | CRUD for geo_units table | 1-2 hours |
| **Member assignment with eligibility check** | `AssignMemberToCommitteeUseCase` | 2 hours |
| **Committee dashboard** | Projection showing committee + members + jurisdiction | 2-3 hours |

---

### Quick Start Commands

```bash
# Generate architecture baseline
php artisan architecture:baseline geography

# Check for new violations
php artisan architecture:baseline geography --diff

# Run all tests
php artisan test tests/Unit/Contexts/Membership/
```

---

**Ready to build the committee creation UI, or do you want to add the `geo_level` column to the governance levels table first?**