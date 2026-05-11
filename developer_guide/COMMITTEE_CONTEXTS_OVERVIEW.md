# Developer Guide: Membership, Geography, and Committee Contexts

**Version:** 1.0  
**Status:** GEO-3.4 Complete  
**Audience:** Developers building features in these domains  
**Last Updated:** 2026-05-08

---

## Quick Navigation

- [Membership Context](#membership-context) — User roles, organization membership, permissions
- [Geography Context](#geography-context) — Regions, countries, geo-references, locations
- [Committee Context](#committee-context) — Constitutional governance, authorities, lineages
- [Cross-Context Integration](#cross-context-integration) — How they interact

---

## Membership Context

**Location:** `app/Contexts/Membership/`

### Purpose

Manages user memberships, roles, permissions, and organizational relationships.

### Key Concepts

#### User Membership
```php
// A user's membership in an organization
class UserOrganisationRole
{
    public UserId $userId;
    public OrganisationId $organisationId;
    public Role $role;  // admin, editor, viewer, etc.
}
```

#### Roles (Spatie Permission)
- **admin** — Full access, can manage users and roles
- **editor** — Can create/edit content
- **viewer** — Read-only access
- **member** — Basic membership status

### Current Implementation

#### Models
- `app/Models/User` — Laravel user model
- `User::class` extends `Authenticatable`
- Uses Laravel Fortify for authentication

#### Application Layer
- DTOs in `Application/Member/` for type-safe data
- Use cases in `Application/Member/` for business logic
- Repository pattern for data access

#### Domain Layer (GEO-3.4+)
- Pure PHP, no Laravel dependencies
- Membership decisions flow through constitutional governance
- Membership legitimacy validated via temporal windows

### Adding New Membership Features

```php
// Example: Add a new membership field
// Step 1: Create migration
Schema::create('user_organisation_roles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id');
    $table->foreignId('organisation_id');
    $table->string('role');
    $table->timestamps();
});

// Step 2: Create domain model
final readonly class UserOrganisationRole
{
    public function __construct(
        public UserId $userId,
        public OrganisationId $organisationId,
        public Role $role,
    ) {}
}

// Step 3: Create repository interface (domain)
interface UserOrganisationRoleRepository
{
    public function findByUser(UserId): array;
    public function save(UserOrganisationRole): void;
}

// Step 4: Implement repository (infrastructure)
class EloquentUserOrganisationRoleRepository implements UserOrganisationRoleRepository
{
    public function findByUser(UserId $id): array
    {
        return UserOrganisationRoleModel::where('user_id', $id->toString())
            ->get()
            ->map(fn($m) => $this->toDomain($m))
            ->all();
    }
}

// Step 5: Use in application layer
class JoinOrganisationCommand
{
    public function __construct(
        private UserOrganisationRoleRepository $repository,
    ) {}
    
    public function execute(JoinOrganisationDto $dto): void
    {
        $role = new UserOrganisationRole(
            UserId::fromString($dto->userId),
            OrganisationId::fromString($dto->organisationId),
            Role::from($dto->role),
        );
        
        $this->repository->save($role);
    }
}
```

---

## Geography Context

**Location:** `app/Contexts/Geography/`

### Purpose

Manages geographic hierarchies (countries, regions, states) and geographic references for organizational scope.

### Key Concepts

#### Geographic Structure
```php
// Hierarchical geographic data
class Country
{
    public string $code;        // 'DE', 'US', etc.
    public string $name;        // 'Germany', 'United States'
}

class Region
{
    public string $code;        // 'BY' (Bavaria)
    public string $countryCode; // 'DE'
    public string $name;        // 'Bayern'
}

class GeoAdministrativeUnit
{
    public string $code;
    public string $name;
    public int $level;          // 1=country, 2=region, 3=subregion
    public ?string $parentCode;
}
```

#### GeoReference (Value Object)
```php
// Identifies a specific geographic location
final readonly class GeoReference
{
    public function __construct(
        public string $countryCode,
        public ?string $regionCode,
        public ?string $geoPath,  // Canonical path: "DE/BY/..."
    ) {}
    
    public static function forCountry(string $code): self
    public static function forRegion(string $country, string $region): self
    public function getLevel(): int
}
```

### Current Implementation

#### Models
- `DemoCountry` — Demo mode countries
- `DemoRegion` — Demo mode regions
- `Country` — Production countries
- `GeoAdministrativeUnit` — Multi-level geographic hierarchy

#### Application Layer
- `GeographyService` — Geographic lookups and validation
- DTOs for geographic data transfer

#### Domain Layer (GEO-3.4+)
- `GeoReference` — Immutable geographic identifier
- `GeoPath` — Canonical geographic path notation
- Geographic scope validation in constitutional decisions

### Adding New Geographic Data

```php
// Example: Add support for sub-regions
// Step 1: Create migration (hierarchical)
Schema::create('geo_administrative_units', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->string('name');
    $table->integer('level');  // 1=country, 2=region, 3=subregion
    $table->string('parent_code')->nullable();
    $table->timestamps();
});

// Step 2: Create domain VO
final readonly class GeoAdministrativeUnit
{
    public function __construct(
        public string $code,
        public string $name,
        public int $level,
        public ?string $parentCode,
    ) {}
    
    public function isCountry(): bool { return $this->level === 1; }
    public function isRegion(): bool { return $this->level === 2; }
    public function getParentPath(): ?string { /* lookup parent */ }
}

// Step 3: Use in geography service
class GeographyService
{
    public function getUnitsAtLevel(int $level): array
    {
        return GeoAdministrativeUnit::where('level', $level)->get();
    }
    
    public function getUnitsUnder(string $parentCode): array
    {
        return GeoAdministrativeUnit::where('parent_code', $parentCode)->get();
    }
}
```

### Geographic Scope in Voting

```php
// Voters see posts appropriate to their region
class DemoVoteService
{
    public function getEligiblePosts(User $voter): Collection
    {
        $regionalCode = $voter->region;  // e.g., 'Bayern'
        
        return DemoPost::query()
            ->where('is_national_wide', true)  // Always visible
            ->orWhere('state_name', $regionalCode)  // Regional posts
            ->get();
    }
}
```

---

## Committee Context

**Location:** `app/Contexts/Membership/Domain/Committee/`

### Purpose

Manages organizational committees, authorities, governance decisions, and constitutional legitimacy.

### Key Concepts

#### Committee (Domain Aggregate)
```php
final class Committee
{
    private CommitteeId $id;
    private CommitteeName $name;
    private ?GeoReference $operationalGeo;
    private TemporalAuthorityWindow $authorityWindow;
    private ConstitutionalScope $scope;
    
    public function __construct(
        CommitteeId $id,
        CommitteeName $name,
        GeoReference $geoReference,
        DateTimeImmutable $validFrom,
        ?DateTimeImmutable $validUntil,
    ) { /* ... */ }
    
    // Business logic methods
    public function changeName(CommitteeName $newName): void { }
    public function updateOperationalGeo(GeoReference $geo, ?string $region, ?string $country): void { }
    public function extendTerm(DateTimeImmutable $newEnd): void { }
    
    // Events
    public function recordedEvents(): array { }
}
```

#### Authority Graph
```php
// Organizational authority relationships
class JurisdictionNode
{
    public string $id;
    public CommitteeId $committeeId;
    public TemporalAuthorityWindow $window;
    public ConstitutionalScope $scope;
}

class DelegationEdge
{
    public string $fromNodeId;
    public string $toNodeId;
    public DelegationType $type;  // DIRECT, EXCEPTION, OVERRIDE
}

class GeoAuthorityGraph
{
    public function addNode(JurisdictionNode): void { }
    public function addEdge(DelegationEdge): void { }
    public function resolveAuthority(CapabilityType, DateTimeImmutable): ?JurisdictionNode { }
}
```

#### Constitutional Governance
```php
// Governance decisions go through constitutional arbitration
class ConstitutionalGovernanceDecision
{
    public GovernanceDecision $governanceDecision;        // Who wins operationally
    public ConstitutionalDecision $constitutionalDecision;  // Is it constitutional?
    
    public function isConstitutionallyValid(): bool
    {
        return $this->constitutionalDecision->legitimacy->isValid();
    }
}

class ConstitutionalDecision
{
    public ?JurisdictionNode $winner;
    public GovernanceLegitimacy $legitimacy;  // LEGITIMATE, EXPIRED, PENDING, etc.
    public ConstitutionalReason $reason;
    public DateTimeImmutable $evaluatedAt;
    public ConstitutionalArbitrationTrace $trace;
}
```

### Current Implementation

#### Models (Infrastructure)
- `CommitteeModel` — Eloquent model for committees
- `JurisdictionNodeModel` — Database representation of authorities
- `DelegationEdgeModel` — Database representation of authority relationships

#### Domain Layer (GEO-3.4+)
- `Committee` — Domain aggregate (pure PHP)
- `JurisdictionNode`, `DelegationEdge` — Value objects
- `GeoAuthorityGraph` — Authority relationship management
- `ConstitutionalGovernanceDecision` — Governance outcome
- `GovernanceDecisionKernel` — Operational decision logic
- `ConstitutionalArbitrationKernel` — Constitutional judgment
- `GovernanceReplayService` — Historical decision replay
- `GovernanceDecisionSnapshot` — Immutable decision archive

#### Application Layer
- `CreateCommitteeCommand` / `UpdateCommitteeDetailsCommand` — Use cases
- DTOs for type-safe data transfer
- Controllers invoke use cases, which use domain repositories

### Creating Governance Decisions

```php
// Step 1: Evaluate authority graph
$graph = $geoAuthorityGraph->build($committee, $evaluationTime);

// Step 2: Classify authorities
$classification = $kernel->classify($capability, $graph);

// Step 3: Resolve via constitutional arbitration
$decision = $constitutionalKernel->decide(
    capability: $capability,
    context: $capabilityContext,
    at: $evaluationTime  // Point-in-time evaluation
);

// Step 4: Verify decision (5-dimensional check)
$certification = ReplayCertification::certify(
    record: $archaeology->replay($decision->id()),
    original: $snapshot,
    certifiedAt: now(),
);

if (!$certification->isEquivalent) {
    // Handle semantic drift
    Log::warning('Constitutional drift detected', [
        'breaches' => $certification->equivalenceBreaches,
        'reason' => $certification->certificationReason,
    ]);
}

// Step 5: Persist decision snapshot
$replayService->persist($decision, $capability);
```

### Validating Committee Authority

```php
class CommitteeValidationService
{
    public function validateAuthority(
        Committee $committee,
        CapabilityType $capability,
        ?DateTimeImmutable $evaluationTime = null,
    ): ConstitutionalGovernanceDecision {
        $evaluationTime ??= new DateTimeImmutable();
        
        // Check temporal validity
        if (!$committee->getAuthorityWindow()->isActiveAt($evaluationTime)) {
            throw new CommitteeNotActiveException($committee->getId());
        }
        
        // Check constitutional scope
        $requiredScope = $capability->getRequiredScope();
        if (!$committee->getScope()->isCompatibleWith($requiredScope)) {
            throw new ScopeMismatchException($committee->getScope(), $requiredScope);
        }
        
        // Resolve via arbitration kernel
        return $this->kernel->decide($ctx, $capability, $evaluationTime);
    }
}
```

---

## Cross-Context Integration

### Geography + Committee

Committees have geographic scope. Voting eligibility filtered by geography.

```php
// Voter in Bayern region
$voter = User::find(1);  // region='Bayern'

// Get eligible posts
$posts = DemoPost::query()
    ->where('is_national_wide', true)
    ->orWhere('state_name', $voter->region)
    ->get();

// Committee governance must match voter scope
$committee = Committee::find(1);
// $committee->operationalGeo = GeoReference::forRegion('DE', 'BY')
```

### Membership + Committee

Committee membership (who's on the committee) vs. voter eligibility (who can vote).

```php
// Committee members (admin, moderator)
$members = UserOrganisationRole::where('role', 'committee_admin')->get();

// Voters eligible for this committee's elections
$voterGeoScope = $committee->getOperationalGeo();
$eligibleVoters = User::where('region', $voterGeoScope->regionCode)->get();
```

### Membership + Geography + Committee

Organizational context across all three domains.

```php
class OrganisationCommitteeService
{
    public function getOrganisationCommittees(
        OrganisationId $org,
        ?GeoReference $geoFilter = null,
    ): Collection {
        $committees = Committee::whereOrganisationId($org)->get();
        
        if ($geoFilter) {
            $committees = $committees->filter(
                fn($c) => $c->getOperationalGeo()->isCompatibleWith($geoFilter)
            );
        }
        
        return $committees;
    }
    
    public function validateUserCanVote(
        User $user,
        Committee $committee,
        DateTimeImmutable $at,
    ): bool {
        // 1. User must have membership in org
        if (!$user->hasOrganisationMembership($committee->getOrganisationId())) {
            return false;
        }
        
        // 2. User must be in correct geography
        $userGeo = $user->getGeoReference();
        $committeeGeo = $committee->getOperationalGeo();
        if (!$this->geography->isCompatible($userGeo, $committeeGeo)) {
            return false;
        }
        
        // 3. Committee must be constitutionally active
        $constitutionalDecision = $this->governance->validateCommitteeAuthority(
            $committee,
            CapabilityType::VOTING,
            $at,
        );
        
        return $constitutionalDecision->isConstitutionallyValid();
    }
}
```

---

## Development Workflow

### Adding a Feature

#### 1. **Identify the Context**
   - Is it about users? → Membership Context
   - Is it about locations? → Geography Context
   - Is it about governance? → Committee Context

#### 2. **Write Tests First (TDD)**
   ```php
   public function test_committee_extends_term_updates_authority_window()
   {
       $committee = Committee::create(/* ... */);
       $newEnd = new DateTimeImmutable('+2 years');
       
       $committee->extendTerm($newEnd);
       
       $this->assertTrue($committee->getAuthorityWindow()->validUntil->equals($newEnd));
   }
   ```

#### 3. **Implement Domain Logic**
   ```php
   public function extendTerm(DateTimeImmutable $newEnd): void
   {
       if ($newEnd < $this->authorityWindow->validFrom) {
           throw new InvalidTermException('End before start');
       }
       
       $this->authorityWindow = new TemporalAuthorityWindow(
           $this->authorityWindow->validFrom,
           $newEnd,
       );
       
       $this->record(new CommitteeTermExtended(
           $this->id,
           $newEnd,
       ));
   }
   ```

#### 4. **Create Application Use Case**
   ```php
   class ExtendCommitteeTermCommand
   {
       public function __construct(private CommitteeRepository $repo) {}
       
       public function execute(ExtendCommitteeTermDto $dto): void
       {
           $committee = $this->repo->findById($dto->committeeId);
           $committee->extendTerm($dto->newEnd);
           $this->repo->save($committee);
       }
   }
   ```

#### 5. **Create HTTP Controller**
   ```php
   class CommitteeController extends Controller
   {
       public function extendTerm(Request $request, ExtendCommitteeTermCommand $command)
       {
           $command->execute(
               ExtendCommitteeTermDto::fromRequest($request)
           );
           
           return redirect()->back()->with('success', 'Term extended');
       }
   }
   ```

#### 6. **Add Tests**
   - Domain tests (logic)
   - Application tests (use cases)
   - Feature tests (HTTP)
   - No fitness tests needed (architecture enforces boundaries)

### Running Tests

```bash
# All tests
php artisan test

# Specific context
php artisan test tests/Unit/Domain/Committee/

# Specific file
php artisan test tests/Unit/Domain/Committee/CommitteeTest.php

# Architecture fitness
php artisan test tests/Unit/Domain/Committee/Constitutional/ArchitectureFitnessTest.php
```

---

## Common Patterns

### Repository Pattern (Domain → Infrastructure Boundary)

```php
// Domain (pure PHP, no framework)
interface CommitteeRepository
{
    public function findById(CommitteeId): ?Committee;
    public function save(Committee): void;
}

// Infrastructure (Eloquent allowed)
class EloquentCommitteeRepository implements CommitteeRepository
{
    public function findById(CommitteeId $id): ?Committee
    {
        $model = CommitteeModel::find($id->toString());
        return $model ? $this->toDomain($model) : null;
    }
}
```

### Value Object Pattern

```php
final readonly class CommitteeId
{
    private function __construct(private string $value) {
        if (empty($value)) throw new InvalidArgumentException();
    }
    
    public static function generate(): self
    {
        return new self(Str::uuid()->toString());
    }
    
    public static function fromString(string $value): self
    {
        return new self($value);
    }
    
    public function toString(): string { return $this->value; }
}
```

### Domain Event Pattern

```php
final readonly class CommitteeCreated
{
    public function __construct(
        public CommitteeId $id,
        public CommitteeName $name,
        public DateTimeImmutable $occurredAt,
    ) {}
}

// In domain aggregate
public function recordEvent(object $event): void
{
    $this->events[] = $event;
}
```

---

## Troubleshooting

### "Unknown column in where clause"
**Cause:** Model query using wrong table or column name  
**Fix:** Check model `$table` property and column names match migrations

### "Call to undefined method"
**Cause:** Using implementation method instead of interface  
**Fix:** Inject interface, not concrete class (`CommitteeRepository`, not `EloquentCommitteeRepository`)

### "Governance decision not found"
**Cause:** Decision never persisted, or persisted with different ID  
**Fix:** Check `GovernanceReplayService::persist()` was called

### "Scope mismatch exception"
**Cause:** Committee scope (NATIONAL) doesn't match decision scope (REGIONAL)  
**Fix:** Verify committee's `ConstitutionalScope` matches decision context

---

## References

- [Constitutional Governance Architecture Handbook](../docs/CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md)
- [Architecture Decision Records](../docs/ARCHITECTURE_DECISION_RECORDS.md)
- [Package Boundary Audit Report](../docs/PACKAGE_BOUNDARY_AUDIT_REPORT.md)

---

**Last Updated:** 2026-05-08  
**Next Update:** GEO-3.5 Membership Governance features
