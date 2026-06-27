# Claude Code CLI Prompt: Committee Management System - Phase 3 Implementation

## Role Definition

```yaml
role: Senior Software Architect & DDD Expert
focus: Clean Architecture, TDD, Event-Driven Design
constraints:
  - Zero Laravel in Domain layer
  - No updateOrCreate/firstOrCreate in repositories
  - Use case owns transactions
  - Events dispatched AFTER commit
  - TDD first: Red → Green → Refactor
```

## Phase 3A: Domain & Persistence Stabilization

### Prerequisites Check
```bash
# Run this first
php artisan test --testsuite=Unit --group=existing
# All 24+ tests must pass before starting
```

### TDD Workflow for Each Component

```yaml
Step Template:
  1. THINK: What is the behavior we want?
  2. WRITE TEST: Describe expected behavior in test name
  3. RUN TEST: Must fail (Red)
  4. IMPLEMENT: Minimal code to pass
  5. RUN TEST: Must pass (Green)
  6. REFACTOR: Clean up, no behavior change
  7. COMMIT: "[Phase 3A] Added [component]"
```

### Component 1: CommitteeStructureRegistry

```yaml
Task: Create strategy registry for committee types

Test First - Create file:
  tests/Unit/Contexts/Membership/Domain/Committee/CommitteeStructureRegistryTest.php

Test cases:
  1. forType_central_returns_CentralCommitteeStructure
  2. forType_province_returns_ProvinceCommitteeStructure
  3. forType_district_returns_DistrictCommitteeStructure
  4. forType_ward_returns_WardCommitteeStructure
  5. forType_youth_returns_YouthWingStructure
  6. forType_women_returns_WomenWingStructure
  7. forType_student_returns_StudentWingStructure
  8. forType_unknown_throws_DomainException

Implementation:
  File: app/Contexts/Membership/Domain/Committee/CommitteeStructureRegistry.php
  
  Content:
    final class CommitteeStructureRegistry {
        public static function forType(CommitteeType $type): CommitteeStructure {
            return match($type->value()) {
                'central'   => new CentralCommitteeStructure(),
                'province'  => new ProvinceCommitteeStructure(),
                'district'  => new DistrictCommitteeStructure(),
                'ward'      => new WardCommitteeStructure(),
                'youth'     => new YouthWingStructure(),
                'women'     => new WomenWingStructure(),
                'student'   => new StudentWingStructure(),
                default => throw new \DomainException("Unknown type: {$type->value()}"),
            };
        }
    }
```

### Component 2: CommitteeAssignment::reconstruct()

```yaml
Task: Add static factory for DB hydration

Test First - Add to existing file:
  tests/Unit/Contexts/Membership/Domain/Committee/CommitteeAssignmentTest.php

Test cases:
  1. reconstruct_creates_assignment_with_all_fields
  2. reconstruct_with_null_optional_fields_works
  3. reconstruct_without_leftDate_has_leftDate_null
  4. reconstruct_preserves_joinedDate_timezone
  5. reconstruct_does_not_fire_any_events

Implementation - Modify file:
  app/Contexts/Membership/Domain/Committee/CommitteeAssignment.php

Add method:
  public static function reconstruct(
      CommitteeAssignmentId $id,
      CommitteeId $committeeId,
      MemberId $memberId,
      RolePath $rolePath,
      DateTimeImmutable $joinedDate,
      NominationType $nominationType,
      ?DateTimeImmutable $electionDate,
      ?DateTimeImmutable $termEndDate,
      ?DateTimeImmutable $leftDate,
      ?TenantUserId $appointedByUserId,
      ?string $notes,
      array $metadata = []
  ): self {
      $assignment = new self();
      $assignment->id = $id;
      $assignment->committeeId = $committeeId;
      $assignment->memberId = $memberId;
      $assignment->rolePath = $rolePath;
      $assignment->joinedDate = $joinedDate;
      $assignment->nominationType = $nominationType;
      $assignment->electionDate = $electionDate;
      $assignment->termEndDate = $termEndDate;
      $assignment->leftDate = $leftDate;
      $assignment->appointedByUserId = $appointedByUserId;
      $assignment->notes = $notes;
      $assignment->metadata = $metadata;
      $assignment->isActive = $leftDate === null;
      // NO event recording - hydration only
      return $assignment;
  }
```

### Component 3: Committee::reconstruct() Fix

```yaml
Task: Make structure REQUIRED, add assignments parameter

Test First - Add to existing file:
  tests/Unit/Contexts/Membership/Domain/Committee/CommitteeReconstitutionTest.php

Test cases:
  1. reconstruct_without_structure_throws_LogicException
  2. reconstruct_with_structure_and_assignments_works
  3. reconstruct_with_assignments_exposes_them_via_getAssignments
  4. reconstruct_loaded_assignments_prevent_duplicate_assignment
  5. assign_member_on_reconstituted_committee_succeeds
  6. assign_member_on_reconstituted_committee_checks_structure_rules

Implementation - Modify file:
  app/Contexts/Membership/Domain/Committee/Committee.php

Update signature:
  public static function reconstruct(
      CommitteeId $id,
      TenantId $tenantId,
      CommitteeType $type,
      CommitteeName $name,
      string $code,
      ?GeoReference $operationalGeo,
      CommitteeStatus $status,
      CommitteeStructure $structure,  // REQUIRED - not nullable
      array $assignments = []
  ): self

Add guard at method start:
  if ($structure === null) {
      throw new \LogicException('CommitteeStructure must be provided to reconstruct()');
  }

Add after setting properties:
  $this->structure = $structure;
  $this->assignments = $assignments;
```

### Component 4: CommitteeAssignmentModel

```yaml
Task: Create Eloquent model for committee_assignments table

Implementation - Create file:
  app/Contexts/Membership/Infrastructure/Models/CommitteeAssignmentModel.php

Content:
  namespace App\Contexts\Membership\Infrastructure\Models;
  
  use Illuminate\Database\Eloquent\Model;
  
  final class CommitteeAssignmentModel extends Model {
      protected $table = 'committee_assignments';
      public $incrementing = false;
      protected $keyType = 'string';
      
      protected $fillable = [
          'id', 'committee_id', 'member_id', 'role_path', 'nomination_type',
          'election_date', 'term_end_date', 'joined_date', 'left_date',
          'is_active', 'appointed_by_user_id', 'notes', 'metadata', 'organisation_id',
      ];
      
      protected $casts = [
          'election_date' => 'datetime',
          'term_end_date' => 'datetime',
          'joined_date' => 'datetime',
          'left_date' => 'datetime',
          'is_active' => 'boolean',
          'metadata' => 'array',
      ];
  }

Then update CommitteeModel:
  Add relationship:
    public function assignments(): HasMany {
        return $this->hasMany(CommitteeAssignmentModel::class, 'committee_id');
    }
```

### Component 5: Repository Assignment Loading

```yaml
Task: Load assignments in all find* methods

Test First - Create file:
  tests/Unit/Contexts/Membership/Infrastructure/Repositories/CommitteeRepositoryTest.php

Test cases:
  1. findForTenant_loads_assignments_from_database
  2. findForTenant_with_no_assignments_returns_empty_array
  3. findForTenant_reconstitutes_assignments_correctly
  4. repository_loads_assignments_on_findAll

Implementation - Modify file:
  app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php

Update findForTenant:
  $model = CommitteeModel::with('assignments')->find($id->value());

Update findAllForTenant:
  $models = CommitteeModel::with('assignments')->get();

Update reconstitute method:
  $structure = CommitteeStructureRegistry::forType($type);
  $assignments = $model->assignments->map(fn($a) =>
      CommitteeAssignment::reconstruct(
          CommitteeAssignmentId::fromString($a->id),
          CommitteeId::fromString($a->committee_id),
          MemberId::fromString($a->member_id),
          RolePath::fromString($a->role_path),
          new DateTimeImmutable($a->joined_date),
          NominationType::fromString($a->nomination_type),
          $a->election_date ? new DateTimeImmutable($a->election_date) : null,
          $a->term_end_date ? new DateTimeImmutable($a->term_end_date) : null,
          $a->left_date ? new DateTimeImmutable($a->left_date) : null,
          $a->appointed_by_user_id ? TenantUserId::fromString($a->appointed_by_user_id) : null,
          $a->notes,
          $a->metadata ?? [],
      )
  )->all();
  
  return Committee::reconstruct(..., $structure, $assignments);
```

### Component 6: Repository Assignment Sync (No updateOrCreate)

```yaml
Task: Save assignments with explicit find→update OR create, delete removed

Test First - Add to CommitteeRepositoryTest.php:
  1. saveForTenant_persists_new_assignment
  2. saveForTenant_updates_existing_assignment
  3. saveForTenant_deletes_removed_assignment
  4. saveForTenant_syncs_multiple_assignments
  5. round_trip_assign_save_reload_see_assignment
  6. round_trip_remove_save_reload_see_no_assignment

Implementation - Modify EloquentCommitteeRepository.php saveForTenant:

Add after saving committee:
  // 1. Load current DB assignment IDs
  $existingIds = CommitteeAssignmentModel::where('committee_id', $committee->getId()->value())
      ->pluck('id')
      ->all();
  
  // 2. Track aggregate IDs
  $aggregateIds = [];
  
  // 3. Process each assignment (explicit find→update OR create)
  foreach ($committee->getAssignments() as $assignment) {
      $id = $assignment->getId()->value();
      $aggregateIds[] = $id;
      $data = $this->serializeAssignment($assignment);
      
      if (in_array($id, $existingIds, true)) {
          CommitteeAssignmentModel::where('id', $id)->update($data);
      } else {
          CommitteeAssignmentModel::create($data);
      }
  }
  
  // 4. Delete removed assignments
  CommitteeAssignmentModel::where('committee_id', $committee->getId()->value())
      ->whereNotIn('id', $aggregateIds)
      ->delete();

Add helper method:
  private function serializeAssignment(CommitteeAssignment $assignment): array {
      return [
          'id' => $assignment->getId()->value(),
          'committee_id' => $assignment->getCommitteeId()->value(),
          'member_id' => $assignment->getMemberId()->value(),
          'role_path' => $assignment->getRolePath()->value(),
          'nomination_type' => $assignment->getNominationType()->value(),
          'joined_date' => $assignment->getJoinedDate(),
          'election_date' => $assignment->getElectionDate(),
          'term_end_date' => $assignment->getTermEndDate(),
          'left_date' => $assignment->getLeftDate(),
          'is_active' => $assignment->isActive(),
          'notes' => $assignment->getNotes(),
          'metadata' => $assignment->getMetadata(),
          'organisation_id' => $assignment->getTenantId()->value(),
      ];
  }
```

## Phase 3B: Application Layer

### Component 7: RemoveMemberFromCommittee Use Case

```yaml
Task: Create use case for removing assignments

Test First - Create file:
  tests/Feature/Membership/CommitteeManagementTest.php

Test cases:
  1. remove_member_deletes_assignment_from_database
  2. remove_member_with_invalid_assignment_throws_exception
  3. remove_member_from_different_tenant_throws_exception
  4. remove_member_pulls_and_dispatches_events

Implementation - Create files:
  app/Contexts/Membership/Application/Committee/DTOs/RemoveMemberDto.php
  app/Contexts/Membership/Application/Committee/RemoveMemberFromCommittee.php

RemoveMemberDto:
  final readonly class RemoveMemberDto {
      public function __construct(
          public CommitteeId $committeeId,
          public TenantId $tenantId,
          public CommitteeAssignmentId $assignmentId,
          public ?string $notes = null,
      ) {}
  }

RemoveMemberFromCommittee:
  final class RemoveMemberFromCommittee {
      public function __construct(
          private CommitteeRepositoryInterface $repository,
          private EventBusInterface $eventBus
      ) {}
      
      public function execute(RemoveMemberDto $command): void {
          $events = DB::transaction(function () use ($command) {
              $committee = $this->repository->findForTenant(
                  $command->committeeId, 
                  $command->tenantId
              );
              
              if (!$committee) {
                  throw new CommitteeNotFoundException($command->committeeId);
              }
              
              $committee->removeMember($command->assignmentId, $command->notes);
              $this->repository->saveForTenant($committee, $command->tenantId);
              
              return $committee->pullEvents();
          });
          
          $this->eventBus->dispatchAll($events);
      }
  }
```

### Component 8: UpdateCommitteeDetails Use Case

```yaml
Task: Allow editing committee name, status, geo reference

Test First - Add to CommitteeManagementTest.php:
  1. update_committee_name_changes_database
  2. update_committee_status_changes_database
  3. update_committee_geo_changes_database
  4. update_committee_from_different_tenant_throws

Implementation:
  DTO: UpdateCommitteeDetailsCommand (committeeId, tenantId, name?, status?, geoReference?)
  Use Case: UpdateCommitteeDetails (same pattern as RemoveMemberFromCommittee)
```

### Component 9: CQRS Dashboard Query

```yaml
Task: Replace aggregate-based dashboard with direct DB query

Test First - Add to CommitteeManagementTest.php:
  1. dashboard_query_returns_committee_basic_info
  2. dashboard_query_returns_active_assignments
  3. dashboard_query_returns_member_names_with_assignments
  4. dashboard_query_returns_empty_assignments_when_none
  5. dashboard_query_throws_404_for_invalid_committee

Implementation - Create files:
  app/Contexts/Membership/Application/Committee/DTOs/CommitteeDashboardDTO.php
  app/Contexts/Membership/Application/Committee/DTOs/AssignmentDTO.php
  app/Contexts/Membership/Application/Committee/Queries/GetCommitteeDashboardQuery.php

GetCommitteeDashboardQuery:
  final class GetCommitteeDashboardQuery {
      public function execute(string $committeeId, string $organisationId): CommitteeDashboardDTO {
          $committee = DB::table('committees')
              ->where('id', $committeeId)
              ->where('organisation_id', $organisationId)
              ->first();
          
          if (!$committee) {
              throw new CommitteeNotFoundException($committeeId);
          }
          
          $assignments = DB::table('committee_assignments as ca')
              ->join('members as m', 'ca.member_id', '=', 'm.id')
              ->where('ca.committee_id', $committeeId)
              ->where('ca.is_active', true)
              ->select('ca.id', 'ca.member_id', 'm.name as member_name', 'ca.role_path', 'ca.joined_date')
              ->get()
              ->map(fn($a) => new AssignmentDTO(
                  id: $a->id,
                  memberId: $a->member_id,
                  memberName: $a->member_name,
                  roleLabel: RolePath::fromString($a->role_path)->label(),
                  joinedDate: Carbon::parse($a->joined_date)->format('Y-m-d'),
              ))->all();
          
          return new CommitteeDashboardDTO(
              id: $committee->id,
              name: $committee->name,
              type: $committee->type,
              status: $committee->status,
              geoReference: $committee->operational_geo_reference,
              assignments: $assignments,
          );
      }
  }
```

## Phase 3C: HTTP Layer

### Component 10: Routes with Throttling

```yaml
Task: Add all mutation routes to committee/committeeRoutes.php

Implementation:
  use App\Http\Controllers\Committee\CommitteeManagementController;
  use App\Http\Controllers\Committee\CommitteeMemberController;
  use App\Http\Controllers\Committee\MemberSearchController;
  
  Route::middleware(['auth', 'verified'])->prefix('organisations/{organisation}')->group(function () {
      // Committee CRUD
      Route::get('/committees/create', [CommitteeManagementController::class, 'create'])->name('committees.create');
      Route::post('/committees', [CommitteeManagementController::class, 'store'])->name('committees.store');
      Route::get('/committees/{committeeId}/edit', [CommitteeManagementController::class, 'edit'])->name('committees.edit');
      Route::patch('/committees/{committeeId}', [CommitteeManagementController::class, 'update'])->name('committees.update');
      
      // Member assignments (with rate limiting)
      Route::post('/committees/{committeeId}/members', [CommitteeMemberController::class, 'assign'])
          ->name('committees.members.assign')
          ->middleware('throttle:20,60');
      
      Route::delete('/committees/{committeeId}/members/{assignmentId}', [CommitteeMemberController::class, 'remove'])
          ->name('committees.members.remove')
          ->middleware('throttle:20,60');
      
      // Member search (JSON endpoint)
      Route::get('/members/search', [MemberSearchController::class, 'index'])->name('members.search');
  });
```

### Component 11: Controllers with Authorization

```yaml
Task: Create thin controllers that delegate to use cases

CommitteeManagementController.php:
  class CommitteeManagementController extends Controller {
      public function create(Organisation $organisation) {
          $this->authorize('manage-committee', $organisation);
          return Inertia::render('Committee/Create', [
              'committeeTypes' => CommitteeType::all(),
          ]);
      }
      
      public function store(StoreCommitteeRequest $request, Organisation $organisation) {
          $this->authorize('manage-committee', $organisation);
          
          $command = new CreateCommitteeCommand(/* map from request */);
          $committeeId = app(CreateCommittee::class)->execute($command);
          
          return redirect()->route('committee.dashboard', $committeeId->value())
              ->with('success', 'Committee created successfully.');
      }
      
      // edit(), update() follow same pattern
  }

CommitteeMemberController.php:
  class CommitteeMemberController extends Controller {
      public function assign(AssignMemberRequest $request, Organisation $organisation, string $committeeId) {
          $this->authorize('manage-committee', $organisation);
          
          $dto = new AssignMemberDto(
              committeeId: CommitteeId::fromString($committeeId),
              tenantId: TenantId::fromOrganisationId($organisation->id),
              memberId: MemberId::fromString($request->member_id),
              role: RolePath::fromString($request->role_path),
              nominationType: NominationType::fromString($request->nomination_type),
          );
          
          app(AssignMemberToCommittee::class)->execute($dto);
          
          return redirect()->back()->with('success', 'Member assigned successfully.');
      }
      
      public function remove(Organisation $organisation, string $committeeId, string $assignmentId) {
          $this->authorize('manage-committee', $organisation);
          
          $dto = new RemoveMemberDto(
              committeeId: CommitteeId::fromString($committeeId),
              tenantId: TenantId::fromOrganisationId($organisation->id),
              assignmentId: CommitteeAssignmentId::fromString($assignmentId),
          );
          
          app(RemoveMemberFromCommittee::class)->execute($dto);
          
          return redirect()->back()->with('success', 'Member removed successfully.');
      }
  }

MemberSearchController.php:
  class MemberSearchController extends Controller {
      public function index(Request $request, Organisation $organisation) {
          $this->authorize('view-members', $organisation);
          
          $query = $request->get('q');
          $limit = $request->get('limit', 10);
          
          if (strlen($query) < 2) {
              return response()->json([]);
          }
          
          $members = Member::withoutGlobalScopes()
              ->where('organisation_id', $organisation->id)
              ->where(function($q) use ($query) {
                  $q->where('name', 'ilike', "%{$query}%")
                    ->orWhere('member_id', 'ilike', "%{$query}%")
                    ->orWhere('email', 'ilike', "%{$query}%");
              })
              ->limit($limit)
              ->get(['id', 'member_id', 'name', 'email']);
          
          return response()->json($members);
      }
  }
```

## Phase 3D: Frontend Components

### Component 12: Dashboard.vue - Show Members

```yaml
Task: Update Dashboard.vue to display assignments

Brainstorming needed for:
  - Role badge colors (President: gold, Secretary: blue, Member: gray)
  - Empty state design
  - Loading states
  - Confirmation dialog for remove action

Implementation - Modify resources/js/Pages/Committee/Dashboard.vue:

Add to template after committee details:
  <div class="mt-8">
      <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Committee Members</h2>
          <button @click="showAssignModal = true" 
                  class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
              Add Member
          </button>
      </div>
      
      <div v-if="committee.assignments.length === 0" class="text-center py-8 text-gray-500">
          No members assigned yet. Click "Add Member" to assign.
      </div>
      
      <table v-else class="min-w-full bg-white border">
          <thead>
              <tr class="bg-gray-100">
                  <th class="px-4 py-2 text-left">Member</th>
                  <th class="px-4 py-2 text-left">Role</th>
                  <th class="px-4 py-2 text-left">Joined Date</th>
                  <th class="px-4 py-2 text-left">Actions</th>
              </tr>
          </thead>
          <tbody>
              <tr v-for="assignment in committee.assignments" :key="assignment.id" class="border-t">
                  <td class="px-4 py-2">
                      <div class="font-medium">{{ assignment.memberName }}</div>
                      <div class="text-sm text-gray-500">ID: {{ assignment.memberId }}</div>
                  </td>
                  <td class="px-4 py-2">
                      <span :class="roleBadgeClass(assignment.roleLabel)" class="px-2 py-1 rounded text-sm">
                          {{ assignment.roleLabel }}
                      </span>
                  </td>
                  <td class="px-4 py-2">{{ assignment.joinedDate }}</td>
                  <td class="px-4 py-2">
                      <button @click="removeMember(assignment.id)" 
                              class="text-red-600 hover:text-red-800">
                          Remove
                      </button>
                  </td>
              </tr>
          </tbody>
      </table>
  </div>
  
  <AssignMemberModal v-if="showAssignModal" 
                     @close="showAssignModal = false"
                     @assigned="handleMemberAssigned"
                     :committee-id="committee.id" />

Add script:
  const roleBadgeClass = (role) => {
      const classes = {
          'President': 'bg-yellow-100 text-yellow-800',
          'Secretary': 'bg-blue-100 text-blue-800',
          'Treasurer': 'bg-green-100 text-green-800',
          'Member': 'bg-gray-100 text-gray-800',
      };
      return classes[role] || 'bg-gray-100 text-gray-800';
  };
  
  const removeMember = async (assignmentId) => {
      if (confirm('Are you sure you want to remove this member?')) {
          await router.delete(route('committees.members.remove', {
              organisation: page.props.auth.organisation.id,
              committeeId: props.committee.id,
              assignmentId: assignmentId,
          }));
          router.reload();
      }
  };
```

### Component 13: AssignMemberModal.vue

```yaml
Task: Create member search and assignment modal

Brainstorming needed for:
  - Debounced search input
  - Search result rendering
  - Role select options based on committee type
  - Conditional election date field

Implementation - Create file:
  resources/js/Components/Committee/AssignMemberModal.vue

Template structure:
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h2 class="text-xl font-bold mb-4">Assign Member to Committee</h2>
          
          <!-- Search input -->
          <div class="mb-4">
              <label class="block text-sm font-medium mb-1">Search Member</label>
              <input type="text" v-model="searchQuery" @input="debouncedSearch"
                     placeholder="Type name, member ID, or email..."
                     class="w-full border rounded px-3 py-2">
              
              <!-- Search results -->
              <div v-if="searchResults.length > 0" class="mt-2 border rounded max-h-40 overflow-y-auto">
                  <div v-for="member in searchResults" :key="member.id"
                       @click="selectMember(member)"
                       class="p-2 hover:bg-gray-100 cursor-pointer border-b">
                      <div class="font-medium">{{ member.name }}</div>
                      <div class="text-sm text-gray-500">{{ member.member_id }} - {{ member.email }}</div>
                  </div>
              </div>
          </div>
          
          <!-- Selected member -->
          <div v-if="selectedMember" class="mb-4 p-2 bg-blue-50 rounded">
              <div class="text-sm text-gray-600">Selected Member:</div>
              <div class="font-medium">{{ selectedMember.name }}</div>
          </div>
          
          <!-- Role selection -->
          <div class="mb-4">
              <label class="block text-sm font-medium mb-1">Role</label>
              <select v-model="form.role_path" class="w-full border rounded px-3 py-2">
                  <option v-for="role in availableRoles" :value="role.value">
                      {{ role.label }}
                  </option>
              </select>
          </div>
          
          <!-- Nomination type -->
          <div class="mb-4">
              <label class="block text-sm font-medium mb-1">Nomination Type</label>
              <select v-model="form.nomination_type" class="w-full border rounded px-3 py-2">
                  <option value="elected">Elected</option>
                  <option value="appointed">Appointed</option>
                  <option value="volunteered">Volunteered</option>
              </select>
          </div>
          
          <!-- Election date (conditional) -->
          <div v-if="form.nomination_type === 'elected'" class="mb-4">
              <label class="block text-sm font-medium mb-1">Election Date</label>
              <input type="date" v-model="form.election_date" class="w-full border rounded px-3 py-2">
          </div>
          
          <!-- Actions -->
          <div class="flex justify-end gap-2">
              <button @click="$emit('close')" class="px-4 py-2 border rounded hover:bg-gray-50">
                  Cancel
              </button>
              <button @click="submitAssignment" :disabled="!canSubmit"
                      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:bg-gray-300">
                  Assign Member
              </button>
          </div>
      </div>
  </div>

Script:
  import { ref, computed } from 'vue';
  import { router } from '@inertiajs/vue3';
  import { debounce } from 'lodash';
  
  const searchQuery = ref('');
  const searchResults = ref([]);
  const selectedMember = ref(null);
  const form = ref({
      role_path: '',
      nomination_type: 'appointed',
      election_date: '',
  });
  
  const availableRoles = [
      { value: 'committee.president', label: 'President' },
      { value: 'committee.secretary', label: 'Secretary' },
      { value: 'committee.treasurer', label: 'Treasurer' },
      { value: 'committee.member', label: 'Member' },
  ];
  
  const canSubmit = computed(() => {
      return selectedMember.value && form.value.role_path;
  });
  
  const debouncedSearch = debounce(async () => {
      if (searchQuery.value.length >= 2) {
          const response = await fetch(route('members.search', {
              organisation: page.props.auth.organisation.id,
              q: searchQuery.value,
              limit: 10,
          }));
          searchResults.value = await response.json();
      }
  }, 300);
  
  const selectMember = (member) => {
      selectedMember.value = member;
      searchResults.value = [];
      searchQuery.value = member.name;
  };
  
  const submitAssignment = async () => {
      await router.post(route('committees.members.assign', {
          organisation: page.props.auth.organisation.id,
          committeeId: props.committeeId,
      }), {
          member_id: selectedMember.value.id,
          role_path: form.value.role_path,
          nomination_type: form.value.nomination_type,
          election_date: form.value.election_date || null,
      });
      
      emit('assigned');
      emit('close');
      router.reload();
  };
```

### Component 14: Create.vue & Edit.vue

```yaml
Task: Create committee management forms

Brainstorming needed for:
  - Geography reference auto-complete (if complex)
  - Committee type selection with dynamic behavior
  - Form validation UX

Implementation pattern - Create.vue:
  - Form fields: name, code, type, geo_reference, parent_committee_id (optional)
  - Submit to committees.store route
  - Redirect to dashboard on success

Edit.vue:
  - Same form pre-filled with existing data
  - Submit PATCH to committees.update route
```

## Phase 3E: Verification

### Final Checklist

```bash
# Run all tests
php artisan test

# Expected output:
#   PASS  Tests\Unit\Contexts\Membership\Domain\Committee\CommitteeAssignmentTest
#   PASS  Tests\Unit\Contexts\Membership\Domain\Committee\CommitteeReconstitutionTest
#   PASS  Tests\Unit\Contexts\Membership\Infrastructure\Repositories\CommitteeRepositoryTest
#   PASS  Tests\Feature\Membership\CommitteeManagementTest
#   
#   Tests:  24 passed
#   Assertions: 73

# Smoke test
php artisan tinker
>>> session(['current_organisation_id' => 'your-org-uuid']);
>>> app(\App\Contexts\Membership\Application\Committee\Queries\GetCommitteeDashboardQuery::class)
    ->execute('committee-id', 'org-id');

# Browser test
# Navigate to /committee/{id}/dashboard
# Verify: members table shows assignments
# Verify: Add Member button opens modal
# Verify: Search finds members
# Verify: Assign saves assignment
# Verify: Remove deletes assignment
```

## Execution Instructions for Claude

```yaml
When I say "Proceed with Phase 3A":
  - Start with Component 1 (CommitteeStructureRegistry)
  - Follow TDD workflow exactly
  - Run tests after each component
  - Stop and report if any test fails
  - Commit after Green phase

When I say "Brainstorm [feature]":
  - Generate 3-5 design options
  - List pros/cons of each
  - Recommend best option with rationale
  - Wait for my approval before implementing

When I say "Continue to next phase":
  - Verify current phase tests all pass
  - Move to next component in sequence
  - Maintain same strict TDD discipline

When test fails unexpectedly:
  - Print the full error message
  - Analyze root cause (Red-Green-Refactor violation?)
  - Suggest fix before implementing
  - Wait for confirmation
```

---

**Ready to proceed. Say "Proceed with Phase 3A - Component 1" to start.**

CHATGPT has this review of above plan  :
Short answer: **yes — I agree with this plan overall**, and it’s a strong, near production-ready execution plan.

But at a senior architecture level, “agree” doesn’t mean “ship as-is.” There are a few **important corrections and subtle risks** you should address before committing to implementation.

I’ll break this into:

1. ✅ What is excellent (keep as-is)
2. ⚠️ What needs adjustment (important but fixable)
3. ❌ What I would not approve without change
4. 🧠 Final verdict

---

# ✅ What You Did Very Well

This is **genuinely strong architecture work**.

### 1. Clear enforcement of architectural rules

* No Laravel in Domain ✅
* Use case owns transactions ✅
* Events after commit ✅
* No `updateOrCreate` ✅

👉 This is exactly what most teams *fail* to enforce.

---

### 2. TDD discipline is properly operationalized

Not just “we do TDD”, but:

* Red → Green → Refactor explicitly defined
* Test naming reflects behavior
* Phase gating via tests

👉 This is **senior-level execution maturity**

---

### 3. Aggregate reconstruction is now correct

You fixed a major flaw:

```php
structure REQUIRED
assignments INCLUDED
```

👉 This eliminates inconsistent aggregate states — **critical win**

---

### 4. Repository sync strategy is correct

This part is **especially good**:

```php
find → update OR create
delete removed
```

👉 This ensures:

* No orphan rows
* No drift
* True aggregate → DB consistency

Most systems get this wrong. You didn’t.

---

### 5. CQRS introduction is clean and pragmatic

* Dashboard uses query, not aggregate ✅
* DTO separation ✅
* No domain leakage to UI ✅

👉 This is **exactly the right level of CQRS (not overengineered)**

---

### 6. Thin controllers are correctly enforced

* Authorization ✅
* DTO mapping ✅
* No business logic ✅

👉 This keeps your application layer clean

---

# ⚠️ Important Adjustments (You Should Fix)

These are not blockers—but they *will matter in production*.

---

## ⚠️ 1. `CommitteeStructureRegistry` — static match is NOT scalable

You implemented:

```php
match($type->value()) { ... }
```

### Problem

* Violates Open/Closed Principle
* Every new type requires modifying central code

### Better (production-grade)

Use **injectable strategies**, not static match.

👉 Your current version is OK short-term, but:

**Mark it explicitly as temporary**, or refactor now.

---

## ⚠️ 2. Repository update query is slightly unsafe

```php
CommitteeAssignmentModel::where('id', $id)->update($data);
```

### Problem

* No tenant check
* No committee_id check

### Risk

Cross-tenant or wrong-aggregate updates (rare but dangerous)

### Fix

```php
CommitteeAssignmentModel::where('id', $id)
    ->where('committee_id', $committeeId)
    ->update($data);
```

---

## ⚠️ 3. Missing optimistic locking / concurrency protection

Right now:

* Two admins assign/remove simultaneously → last write wins

### Risk

* Silent data loss

### Minimal fix (recommended)

Add:

* `updated_at` check OR
* version column

👉 Not mandatory for MVP, but you should at least **acknowledge this risk**

---

## ⚠️ 4. Hard delete vs historical integrity

You implemented:

```php
delete removed assignments
```

### Problem

You already have:

```php
leftDate
isActive
```

👉 That implies **soft lifecycle modeling**

### Conflict

* Domain says: history matters
* Repo says: delete everything

### Decision needed:

#### Option A (recommended)

* DO NOT delete
* Set:

```php
left_date = now()
is_active = false
```

#### Option B

* Keep hard delete (simpler, less auditability)

👉 Right now your design is **internally inconsistent**

---

## ⚠️ 5. Domain leakage in query layer

Here:

```php
RolePath::fromString(...)->label()
```

### Problem

* Query layer now depends on Domain

### Better:

Either:

* Pre-store label in DB (denormalization), OR
* Use lightweight mapper (not full VO)

👉 Small issue, but matters at scale

---

## ⚠️ 6. Vue: double reload inefficiency

You do:

```js
router.post(...)
router.reload()
```

### Problem

* Redundant roundtrip
* Inertia already returns updated props

### Fix

Remove:

```js
router.reload()
```

---

# ❌ What I Would Not Approve Without Change

These are the only real blockers.

---

## ❌ 1. Missing tenant enforcement in repository queries

I don’t see consistent:

```php
where('organisation_id', $tenantId)
```

in:

* updates
* deletes
* selects

👉 This is a **multi-tenant data breach risk**

### Must fix everywhere:

* find
* update
* delete

---

## ❌ 2. Incomplete assignment serialization

You wrote:

```php
'organisation_id' => $assignment->getTenantId()->value(),
```

But:

👉 `CommitteeAssignment` does NOT show tenant awareness earlier.

### Risk

* Hidden coupling
* inconsistent source of truth

### Fix

Ensure:

* tenantId is explicitly part of assignment OR
* always derived from committee

---

# 🧠 Final Verdict

### I agree with this plan: **YES — with conditions**

---

## 🟢 Approval Level

| Area                 | Verdict              |
| -------------------- | -------------------- |
| Architecture         | ✅ Strong             |
| DDD correctness      | ✅ Very good          |
| Persistence strategy | ✅ Solid              |
| TDD discipline       | ✅ Excellent          |
| Production readiness | ⚠️ Needs minor fixes |

---

## 🔥 What this plan really is

This is no longer:

* junior
* mid-level

This is **approaching senior-level system design**, with:

* correct aggregate boundaries
* proper persistence strategy
* controlled side effects
* test-driven flow

---

## 🚀 Final Recommendation

Proceed with implementation **AFTER fixing these 3 things**:

1. ✅ Enforce tenant filtering in ALL repository operations
2. ✅ Decide: soft delete vs hard delete for assignments
3. ✅ Add safety constraints to update queries

---

If you want next step, I can:

* Review **Component 1 implementation before you code it**
* Or simulate **real production failure scenarios** against this design

Both will level this up from “very good” → “bulletproof.”

