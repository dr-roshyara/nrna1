# Claude Code CLI Prompt Instructions

## Context

Based on the senior architect review of the Committee Structure design, several critical improvements are needed before proceeding with Phase 5 implementation. The review identified **7 architectural issues** that must be addressed.

---

## Pre-Execution Checklist

```bash
# Run these commands before starting
php artisan test --testsuite=Unit --group=committee-structure
# Expected: All current tests passing

# Check current Organisation creation flow
grep -r "PENDING_GOVERNANCE\|OrganisationStatus" app/Models/Organisation.php
# Expected: Not found (needs implementation)
```

---

## Task 1: Introduce Organisation Governance Status (MUST DO FIRST)

### Problem
Organisation creation is becoming a "god workflow" - too many responsibilities in one place.

### Fix
Add explicit governance status to Organisation model:

```yaml
action: create_migration
name: add_governance_status_to_organisations
path: database/migrations/2026_05_07_000001_add_governance_status_to_organisations.php

content: |
  Schema::table('organisations', function (Blueprint $table) {
      $table->string('governance_status', 30)->default('pending_setup')
          ->after('status')
          ->comment('pending_setup, governance_configured, active, suspended');
      $table->timestamp('governance_configured_at')->nullable();
      $table->uuid('governance_configured_by')->nullable();
  });

action: edit_file
path: app/Models/Organisation.php
add_to_fillable: 'governance_status', 'governance_configured_at', 'governance_configured_by'
add_to_casts: 'governance_status' => 'string'

action: run_command
command: php artisan migrate
```

---

## Task 2: Create OrganisationGovernanceStatus Enum

```yaml
action: create_file
path: app/Domain/ValueObjects/OrganisationGovernanceStatus.php

content: |
  <?php
  
  declare(strict_types=1);
  
  namespace App\Domain\ValueObjects;
  
  enum OrganisationGovernanceStatus: string
  {
      case PENDING_SETUP = 'pending_setup';
      case GOVERNANCE_CONFIGURED = 'governance_configured';
      case ACTIVE = 'active';
      case SUSPENDED = 'suspended';
      
      public function allowCommitteeCreation(): bool
      {
          return $this === self::ACTIVE;
      }
      
      public function allowGovernanceSetup(): bool
      {
          return in_array($this, [self::PENDING_SETUP, self::GOVERNANCE_CONFIGURED]);
      }
  }
```

---

## Task 3: Remove Auto-Creation Fallback (CRITICAL)

```yaml
action: edit_file
path: app/Contexts/Membership/Application/Committee/CreateCommittee.php

find: 'if (!$structure) {'
replace: |
  if (!$structure) {
      throw new DomainException(
          'No active committee structure. Organisation governance must be configured first.'
      );
  }

# REMOVE this entire block:
# $structure = $this->createDefaultFlatStructure($tenantId);
```

---

## Task 4: Create GovernanceSetupController

```yaml
action: create_file
path: app/Http/Controllers/GovernanceSetupController.php

content: |
  <?php
  
  declare(strict_types=1);
  
  namespace App\Http\Controllers;
  
  use App\Models\Organisation;
  use App\Contexts\Membership\Application\CommitteeStructure\DefineCommitteeStructure;
  use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructure;
  use Inertia\Inertia;
  
  final class GovernanceSetupController extends Controller
  {
      public function index(Organisation $organisation)
      {
          $this->authorize('setupGovernance', $organisation);
          
          if ($organisation->governance_status === 'active') {
              return redirect()->route('organisations.show', $organisation)
                  ->with('warning', 'Governance already configured and activated.');
          }
          
          $currentStructure = $this->structureRepo->findActiveByTenant($organisation->id);
          
          return Inertia::render('Governance/Setup', [
              'organisation' => $organisation,
              'currentStructure' => $currentStructure?->toArray(),
              'status' => $organisation->governance_status,
          ]);
      }
      
      public function store(Organisation $organisation, DefineCommitteeStructure $useCase)
      {
          $this->authorize('setupGovernance', $organisation);
          
          // Validate and save structure as DRAFT
          $structure = $useCase->execute([...]);
          
          return redirect()->route('governance.review', $organisation)
              ->with('success', 'Governance structure saved. Review and activate.');
      }
      
      public function activate(Organisation $organisation, ActivateCommitteeStructure $useCase)
      {
          $this->authorize('setupGovernance', $organisation);
          
          // Activate structure and mark org as governance_configured
          $useCase->execute([...]);
          
          $organisation->update([
              'governance_status' => 'governance_configured',
              'governance_configured_at' => now(),
              'governance_configured_by' => auth()->id(),
          ]);
          
          return redirect()->route('organisations.show', $organisation)
              ->with('success', 'Governance configured. You can now activate the organisation.');
      }
  }
```

---

## Task 5: Add Governance Policy Methods

```yaml
action: edit_file
path: app/Policies/OrganisationPolicy.php

add_methods: |
  public function setupGovernance(User $user, Organisation $organisation): bool
  {
      return $user->isOwnerOf($organisation) && 
             $organisation->governance_status !== 'active';
  }
  
  public function activateGovernance(User $user, Organisation $organisation): bool
  {
      return $user->isOwnerOf($organisation) && 
             $organisation->governance_status === 'governance_configured';
  }
```

---

## Task 6: Add Level Identity (LevelCode)

```yaml
action: edit_file
path: app/Contexts/Membership/Domain/Committee/CommitteeLevel.php

add_property_before_constructor: |
  private ?string $code = null;  // Stable identity for level

modify_constructor: |
  public function __construct(
      public int $index,
      public ?string $code,  // NEW: stable identity
      public string $name,
      public GeoPolicy $geoPolicy,
      public ?GeoScope $geoScope,
      public array $roleLimits,
      public int $minMembershipYears,
      public ?array $ageRange,
      public ?string $genderRequirement,
  ) {}

modify_create: |
  public static function create(
      int $index,
      ?string $code,  // NEW parameter
      string $name,
      GeoPolicy $geoPolicy,
      ?GeoScope $geoScope,
      array $roleLimits,
      int $minMembershipYears,
      ?array $ageRange,
      ?string $genderRequirement,
  ): self {
      // code can be null for custom levels, but if provided, must be unique
  }

add_method: |
  public function levelCode(): ?string { return $this->code; }
```

---

## Task 7: Add Activation Metadata

```yaml
action: edit_file
path: app/Contexts/Membership/Infrastructure/Database/Migrations/Landlord/2026_05_06_000001_create_committee_structures_tables.php

add_columns_to_committee_structures: |
  $table->uuid('activated_by')->nullable()->after('status');
  $table->timestamp('activated_at')->nullable()->after('activated_by');
  $table->text('activation_reason')->nullable()->after('activated_at');
  $table->json('activation_metadata')->nullable()->after('activation_reason');

action: edit_file
path: app/Contexts/Membership/Domain/Committee/CommitteeStructure.php

modify_activate_method: |
  public function activate(
      string $activatedBy,
      ?string $reason = null,
      array $metadata = []
  ): void {
      if ($this->status !== StructureStatus::DRAFT) {
          throw new DomainException('Only DRAFT structures can be activated');
      }
      if (empty($this->levels)) {
          throw new DomainException('Cannot activate structure without levels');
      }
      $this->status = StructureStatus::ACTIVE;
      $this->activatedBy = $activatedBy;
      $this->activatedAt = new DateTimeImmutable();
      $this->activationReason = $reason;
      $this->activationMetadata = $metadata;
      $this->recordEvent(new CommitteeStructureActivated(...));
  }
```

---

## Task 8: Create Governance Template Presets

```yaml
action: create_file
path: app/Contexts/Membership/Domain/Committee/GovernanceTemplatePreset.php

content: |
  <?php
  
  declare(strict_types=1);
  
  namespace App\Contexts\Membership\Domain\Committee;
  
  enum GovernanceTemplatePreset: string
  {
      case NEPAL = 'nepal';
      case WORLDWIDE = 'worldwide';
      case GERMANY = 'germany';
      case FLAT = 'flat';
      
      public function getStructure(): array
      {
          return match($this) {
              self::NEPAL => [
                  ['index' => 1, 'code' => 'province', 'name' => 'Province', 'geoPolicy' => 'REQUIRED', 'geoScope' => 'province'],
                  ['index' => 2, 'code' => 'district', 'name' => 'District', 'geoPolicy' => 'REQUIRED', 'geoScope' => 'district'],
                  ['index' => 3, 'code' => 'municipality', 'name' => 'Municipality', 'geoPolicy' => 'OPTIONAL', 'geoScope' => 'municipality'],
                  ['index' => 4, 'code' => 'ward', 'name' => 'Ward', 'geoPolicy' => 'OPTIONAL', 'geoScope' => 'ward'],
              ],
              self::WORLDWIDE => [
                  ['index' => 1, 'code' => 'continent', 'name' => 'Continent', 'geoPolicy' => 'NONE', 'geoScope' => null],
                  ['index' => 2, 'code' => 'region', 'name' => 'Region', 'geoPolicy' => 'REQUIRED', 'geoScope' => 'region'],
                  ['index' => 3, 'code' => 'country', 'name' => 'Country', 'geoPolicy' => 'REQUIRED', 'geoScope' => 'country'],
                  ['index' => 4, 'code' => 'state', 'name' => 'State/Province', 'geoPolicy' => 'OPTIONAL', 'geoScope' => 'state'],
              ],
              // ... other presets
          };
      }
  }
```

---

## Execution Order

```yaml
execute_in_order:
  1: Task 1 - Add governance status to organisations table
  2: Task 2 - Create OrganisationGovernanceStatus enum
  3: Task 3 - Remove auto-creation fallback (CRITICAL)
  4: Task 4 - Create GovernanceSetupController
  5: Task 5 - Add governance policy methods
  6: Task 6 - Add LevelCode to CommitteeLevel
  7: Task 7 - Add activation metadata
  8: Task 8 - Create governance template presets

verification:
  - Run all tests after each task
  - Ensure no regression in existing committee structure tests
  - New tests for governance status transitions
```

## Success Criteria

```yaml
checklist:
  - [ ] Organisation cannot create committees until governance configured
  - [ ] No auto-creation of default structure
  - [ ] Governance setup is separate dedicated flow
  - [ ] Each level has stable level_code (not just index)
  - [ ] Activation records who/when/why
  - [ ] Presets are domain assets, not UI shortcuts
  - [ ] All existing tests still pass
  - [ ] Organisation has explicit governance_status lifecycle
```

---

**Start with Task 1 - Governance Status Migration.** 🚀