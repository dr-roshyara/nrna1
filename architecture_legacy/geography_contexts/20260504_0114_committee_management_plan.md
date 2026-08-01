revised plan :  │ Single Source of Truth │ Aggregate owns assignments; DB mirrors aggregate exactly              │
 ├────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Explicit Persistence   │ ❌ updateOrCreate/firstOrCreate — ✅ explicit find → update OR create │
 ├────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Transaction Discipline │ Use case owns DB::transaction; repository is persistence-only         │
 ├────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Event Discipline       │ Domain records events; use case dispatches AFTER commit               │
 ├────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ CQRS (Lightweight)     │ Writes go through aggregates; reads use dedicated query/DTO           │
 ├────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Authorization          │ Every mutation controller method calls $this->authorize()             │
 └────────────────────────┴───────────────────────────────────────────────────────────────────────┘

 ---
 Pre-Phase-3 State (Audit)

 What EXISTS and is working

 ┌──────────────────────────────────────────────┬──────────────────────────────────────────┬──────────────────────────────────────────────────────────────┐
 │                  Component                   │                 Location                 │                            Notes                             │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ Committee aggregate                          │ Domain/Committee/Committee.php           │ form(), assignMember(), removeMember() all present           │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ CommitteeAssignment entity                   │ Domain/Committee/CommitteeAssignment.php │ assign() only — no reconstruct()                             │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ TenantId, TenantAggregateRoot, RecordsEvents │ app/Contexts/Shared/                     │ All exist                                                    │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ EloquentCommitteeRepository                  │ Infrastructure/Repositories/             │ findForTenant, saveForTenant, findAll present                │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ CommitteeModel                               │ Infrastructure/Models/                   │ BelongsToTenant, SoftDeletes — no assignments() relationship │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ AssignMemberToCommittee use case             │ Application/Committee/                   │ Present — crashes (see bugs)                                 │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ CreateCommittee use case                     │ Application/Committee/                   │ Working                                                      │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ GetCommitteeDashboard use case               │ Application/Committee/                   │ Returns 'assignments' => [] (bug)                            │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ CommitteeDashboardController                 │ Http/Controllers/Committee/              │ Read-only, no mutations                                      │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ Dashboard.vue                                │ resources/js/Pages/Committee/            │ Renders; assignments always empty                            │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ Routes                                       │ routes/committee/committeeRoutes.php     │ GET dashboard only                                           │
 ├──────────────────────────────────────────────┼──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┤
 │ TenantContext service                        │ app/Services/TenantContext.php           │ Fully working                                                │
 └──────────────────────────────────────────────┴──────────────────────────────────────────┴──────────────────────────────────────────────────────────────┘

 Critical Bugs in Existing Code

 1. Committee::reconstruct() leaves $structure uninitialized. assignMember() crashes at $this->structure->canAssignRole(). The AssignMemberToCommittee use case is broken.
 2. CommitteeDashboardView hard-codes 'assignments' => []. Dashboard never shows members.
 3. Repository never loads assignments. findForTenant() always returns Committee with empty $assignments.
 4. Repository never saves assignments. assignMember() + saveForTenant() silently drops all assignments.

 What is MISSING

 ┌─────────────────────────────────────────────┬─────────────────────────────────────────────────────────────┐
 │                   Missing                   │                           Impact                            │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ CommitteeAssignment::reconstruct()          │ Cannot hydrate assignments from DB                          │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ CommitteeAssignmentModel                    │ No Eloquent model for committee_assignments table           │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ CommitteeModel::assignments() relationship  │ Can't eager-load assignments                                │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ CommitteeStructureRegistry                  │ No way to resolve structure from type during reconstitution │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ RemoveMemberFromCommittee use case          │ No removal entry point                                      │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ UpdateCommitteeDetails use case             │ No edit entry point                                         │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ GetCommitteeDashboardQuery (CQRS read)      │ Dashboard coupled to aggregate                              │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ Controllers for create/edit/assign/remove   │ No HTTP surface for mutations                               │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ Routes: POST/PUT/DELETE                     │ No mutation routes                                          │
 ├─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────┤
 │ Create.vue, Edit.vue, AssignMemberModal.vue │ No UI pages                                                 │
 └─────────────────────────────────────────────┴─────────────────────────────────────────────────────────────┘

 ---
 Phase 3A — Domain & Persistence Stabilization

 ▎ No UI, no controllers. Only correctness. All tests must pass before 3B begins.

 3A.1 — Write Failing Tests (Red)

 tests/Unit/Contexts/Membership/Domain/Committee/CommitteeAssignmentReconstructTest.php
 - reconstruct_creates_assignment_from_persisted_data
 - reconstruct_preserves_all_fields
 - reconstruct_with_null_optional_fields
 - reconstructed_assignment_can_be_ended

 tests/Unit/Contexts/Membership/Domain/Committee/CommitteeReconstitutionTest.php
 - reconstruct_with_structure_and_assignments_exposes_them_correctly
 - reconstruct_enforces_structure_is_required (throws LogicException if null)
 - reconstruct_duplicate_check_uses_loaded_assignments
 - assign_member_on_reconstituted_committee_succeeds

 tests/Unit/Contexts/Membership/Infrastructure/Repositories/CommitteeRepositoryTest.php
 - repository_persists_assignment_on_save
 - repository_loads_assignments_on_find
 - repository_syncs_removed_assignments (deleted from DB when removed from aggregate)
 - repository_updates_existing_assignment_on_save
 - round_trip: assign_save_reload_see_assignment (committee saved with member, reloaded, assignment visible)
 - round_trip: remove_save_reload_see_no_assignment (committee saved after removal, reloaded, no active assignment)

 3A.2 — CommitteeAssignment::reconstruct()

 File: app/Contexts/Membership/Domain/Committee/CommitteeAssignment.php

 Add static factory — no events fired:
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
 ): self

 3A.3 — Committee::reconstruct() — structure REQUIRED

 File: app/Contexts/Membership/Domain/Committee/Committee.php

 Updated signature:
 public static function reconstruct(
     CommitteeId $id,
     TenantId $tenantId,
     CommitteeType $type,
     CommitteeName $name,
     string $code,
     ?GeoReference $operationalGeo,
     CommitteeStatus $status,
     CommitteeStructure $structure,   // REQUIRED — not nullable
     array $assignments = []
 ): self

 Hard guard at top of method:
 // structure is always required — missing it is a programming error, not a domain error
 assert($structure !== null, 'CommitteeStructure must be provided to reconstruct()');

 3A.4 — CommitteeStructureRegistry

 New file: app/Contexts/Membership/Domain/Committee/CommitteeStructureRegistry.php

 Strategy lookup — replaces any switch/case in repository:
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
             default     => throw new \DomainException("Unknown committee type: {$type->value()}"),
         };
     }
 }

 3A.5 — CommitteeAssignmentModel

 New file: app/Contexts/Membership/Infrastructure/Models/CommitteeAssignmentModel.php

 final class CommitteeAssignmentModel extends Model {
     protected $table = 'committee_assignments';
     protected $fillable = [
         'id', 'committee_id', 'member_id', 'role_path', 'nomination_type',
         'election_date', 'term_end_date', 'joined_date', 'left_date',
         'is_active', 'appointed_by_user_id', 'notes', 'metadata', 'organisation_id',
     ];
     protected $casts = [
         'election_date' => 'datetime', 'term_end_date' => 'datetime',
         'joined_date' => 'datetime', 'left_date' => 'datetime',
         'is_active' => 'boolean', 'metadata' => 'array',
     ];
 }

 Add assignments() relationship to CommitteeModel:
 public function assignments(): HasMany {
     return $this->hasMany(CommitteeAssignmentModel::class, 'committee_id');
 }

 3A.6 — Repository: Reconstitution with Assignments

 File: app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php

 All find* methods that return a reconstituted aggregate: add ->with('assignments').

 reconstitute() updated:
 $structure = CommitteeStructureRegistry::forType($type);
 $assignments = $model->assignments->map(fn($a) =>
     CommitteeAssignment::reconstruct(/* map all fields */)
 )->all();

 return Committee::reconstruct($id, $tenantId, $type, $name, $code, $geo, $status, $structure, $assignments);

 3A.7 — Repository: Explicit Assignment Sync

 saveForTenant() — assignment sync algorithm (no updateOrCreate — explicit find→write):

 // 1. Save committee record (explicit: find then update OR create)
 $existing = CommitteeModel::withoutGlobalScopes()
     ->where('organisation_id', $tenantId->value())
     ->where('id', $committee->getId()->value())
     ->first();

 if ($existing) {
     $existing->update($this->serialize($committee));
 } else {
     CommitteeModel::create($this->serialize($committee));
 }

 // 2. Load current DB assignment IDs
 $existingIds = CommitteeAssignmentModel::where('committee_id', $committee->getId()->value())
     ->pluck('id')
     ->all();

 // 3. Explicit find→update OR create per assignment (no updateOrCreate)
 $aggregateIds = [];
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

 // 4. Delete assignments removed from aggregate
 CommitteeAssignmentModel::where('committee_id', $committee->getId()->value())
     ->whereNotIn('id', $aggregateIds)
     ->delete();

 This guarantees DB = exact mirror of aggregate. No orphan rows. No ghost assignments.

 ---
 Phase 3B — Application Layer (Use Cases)

 ▎ All domain tests green before starting 3B.

 3B.1 — Write Feature Tests (Red)

 tests/Feature/Membership/CommitteeManagementTest.php
 - guest_cannot_create_committee (→ redirect login)
 - non_admin_cannot_create_committee (→ 403)
 - admin_can_create_committee (DB: committees row, event fired)
 - admin_can_assign_member_to_committee (DB: committee_assignments row)
 - assign_member_duplicate_throws (→ session error)
 - admin_can_remove_member_from_committee (DB: row deleted or left_date set)
 - admin_can_edit_committee_name (DB: committees.name updated)
 - dashboard_shows_active_assignments (not empty)
 - cannot_assign_member_from_different_tenant (→ 403)

 3B.2 — RemoveMemberFromCommittee Use Case

 New: app/Contexts/Membership/Application/Committee/RemoveMemberFromCommittee.php
 New: app/Contexts/Membership/Application/Committee/DTOs/RemoveMemberDto.php

 final readonly class RemoveMemberDto {
     public function __construct(
         public CommitteeId $committeeId,
         public TenantId $tenantId,
         public CommitteeAssignmentId $assignmentId,
         public ?string $notes = null,
     ) {}
 }

 Transaction pattern:
 $events = DB::transaction(function () use ($command) {
     $committee = $this->repo->findForTenant($command->committeeId, $command->tenantId);
     $committee->removeMember($command->assignmentId, $command->notes);
     $this->repo->saveForTenant($committee, $command->tenantId);
     return $committee->pullEvents();
 });
 $this->eventBus->dispatchAll($events);

 3B.3 — UpdateCommitteeDetails Use Case

 New: app/Contexts/Membership/Application/Committee/UpdateCommitteeDetails.php
 New: app/Contexts/Membership/Application/Committee/DTOs/UpdateCommitteeDetailsCommand.php

 Allows updating: name, status, geo reference.

 3B.4 — CQRS: GetCommitteeDashboardQuery (replaces aggregate-based view)

 Replace CommitteeDashboardView aggregate coupling with a direct DB query:

 New: app/Contexts/Membership/Application/Committee/Queries/GetCommitteeDashboardQuery.php

 final class GetCommitteeDashboardQuery {
     public function execute(string $committeeId, string $organisationId): CommitteeDashboardDTO {
         $committee = DB::table('committees')->where('id', $committeeId)
             ->where('organisation_id', $organisationId)->firstOrFail();

         $assignments = DB::table('committee_assignments')
             ->where('committee_id', $committeeId)
             ->where('is_active', true)
             ->get()
             ->map(fn($a) => new AssignmentDTO(
                 id: $a->id,
                 memberId: $a->member_id,
                 roleLabel: RolePath::fromString($a->role_path)->label(),
                 joinedDate: $a->joined_date,
             ))->all();

         return new CommitteeDashboardDTO(/* ... */);
     }
 }

 DTOs:
 - CommitteeDashboardDTO — id, name, type, status, geo, AssignmentDTO[]
 - AssignmentDTO — id, memberId, memberName (joined from members), roleLabel, joinedDate

 3B.5 — Update MembershipServiceProvider

 Add bindings:
 - RemoveMemberFromCommittee::class
 - UpdateCommitteeDetails::class
 - GetCommitteeDashboardQuery::class

 ---
 Phase 3C — HTTP Layer

 3C.1 — Routes

 File: routes/committee/committeeRoutes.php

 Route::middleware(['auth', 'verified'])->prefix('organisations/{organisation}')->group(function () {
     Route::get('/committees/create',             [CommitteeManagementController::class, 'create'])->name('committees.create');
     Route::post('/committees',                   [CommitteeManagementController::class, 'store'])->name('committees.store');
     Route::get('/committees/{committeeId}/edit', [CommitteeManagementController::class, 'edit'])->name('committees.edit');
     Route::patch('/committees/{committeeId}',    [CommitteeManagementController::class, 'update'])->name('committees.update');

     Route::post('/committees/{committeeId}/members',              [CommitteeMemberController::class, 'assign'])->name('committees.members.assign');
     Route::delete('/committees/{committeeId}/members/{assignId}', [CommitteeMemberController::class, 'remove'])->name('committees.members.remove');

     Route::get('/members/search', [MemberSearchController::class, 'index'])->name('members.search');
 });

 Rate limit assign/remove: ->middleware(['auth', 'throttle:20,60']).

 3C.2 — CommitteeManagementController (Thin)

 New: app/Http/Controllers/Committee/CommitteeManagementController.php

 - create() → authorize → render Committee/Create with committee types
 - store(Request) → authorize → validate → CreateCommitteeCommand → use case → redirect
 - edit(Organisation, string $committeeId) → authorize → load via query → render Committee/Edit
 - update(Request, Organisation, string $committeeId) → authorize → validate → use case → redirect

 3C.3 — CommitteeMemberController (Thin)

 New: app/Http/Controllers/Committee/CommitteeMemberController.php

 - assign(Request, Organisation, string $committeeId):
 $this->authorize('manage-committee', $organisation);
 // validate: member_id, role_path, nomination_type (+ optional election_date)
 // build AssignMemberDto → use case → redirect back with success flash
 - remove(Organisation, string $committeeId, string $assignmentId):
 $this->authorize('manage-committee', $organisation);
 // build RemoveMemberDto → use case → redirect back

 3C.4 — MemberSearchController

 New: app/Http/Controllers/Committee/MemberSearchController.php

 // GET /organisations/{org}/members/search?q=John&limit=10
 // Returns JSON: [{id, memberId, name}]
 // withoutGlobalScopes() ONLY paired with ->where('organisation_id', $org->id)

 ---
 Phase 3D — Frontend (Vue + Inertia)

 3D.1 — Update Dashboard.vue

 File: resources/js/Pages/Committee/Dashboard.vue

 - Render assignments array from page.props.committee.assignments (populated by CommitteeDashboardDTO)
 - Members table: member name, role badge (color by role level), joined date, "Remove" button
 - "Add Member" button → opens AssignMemberModal.vue
 - Remove → router.delete() → reload

 3D.2 — Create.vue

 New: resources/js/Pages/Committee/Create.vue

 Fields: name (text), code (text), type (select — options from page.props.committeeTypes), geo reference (text, optional).
 Submits via router.post().

 3D.3 — Edit.vue

 New: resources/js/Pages/Committee/Edit.vue

 Pre-filled with current committee data. Submits via router.patch().

 3D.4 — AssignMemberModal.vue

 New: resources/js/Components/Committee/AssignMemberModal.vue

 - Member search: debounced fetch() to members.search (JSON, not Inertia nav)
 - Role path select: options based on committee type (passed from backend)
 - Nomination type select: elected / appointed / volunteered
 - If elected: show election date field
 - Submits via router.post()
 - No optimistic UI — always reload from server after success

 ---
 Critical Files Reference

 Files to CREATE

 ┌────────────────────────────────────────────────────────────────────────────────────────┬──────────────────────────────────────────────────┐
 │                                          File                                          │                     Purpose                      │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Domain/Committee/CommitteeStructureRegistry.php                                        │ Strategy registry (resolves structure from type) │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Infrastructure/Models/CommitteeAssignmentModel.php                                     │ Eloquent model for committee_assignments         │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Application/Committee/RemoveMemberFromCommittee.php                                    │ Remove use case                                  │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Application/Committee/UpdateCommitteeDetails.php                                       │ Edit use case                                    │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Application/Committee/DTOs/RemoveMemberDto.php                                         │ DTO                                              │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Application/Committee/DTOs/UpdateCommitteeDetailsCommand.php                           │ DTO                                              │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Application/Committee/Queries/GetCommitteeDashboardQuery.php                           │ CQRS read                                        │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Application/Committee/DTOs/CommitteeDashboardDTO.php                                   │ Read DTO                                         │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Application/Committee/DTOs/AssignmentDTO.php                                           │ Read DTO                                         │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Http/Controllers/Committee/CommitteeManagementController.php                           │ Create/edit                                      │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Http/Controllers/Committee/CommitteeMemberController.php                               │ Assign/remove                                    │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ Http/Controllers/Committee/MemberSearchController.php                                  │ Member search JSON                               │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ resources/js/Pages/Committee/Create.vue                                                │ Creation form                                    │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ resources/js/Pages/Committee/Edit.vue                                                  │ Edit form                                        │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ resources/js/Components/Committee/AssignMemberModal.vue                                │ Assignment modal                                 │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ tests/Unit/Contexts/Membership/Domain/Committee/CommitteeAssignmentReconstructTest.php │ Unit                                             │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ tests/Unit/Contexts/Membership/Domain/Committee/CommitteeReconstitutionTest.php        │ Unit                                             │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ tests/Unit/Contexts/Membership/Infrastructure/Repositories/CommitteeRepositoryTest.php │ Unit                                             │
 ├────────────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────┤
 │ tests/Feature/Membership/CommitteeManagementTest.php                                   │ Feature                                          │
 └────────────────────────────────────────────────────────────────────────────────────────┴──────────────────────────────────────────────────┘

 Files to MODIFY

 ┌─────────────────────────────────────────────────────────────┬─────────────────────────────────────────────────────────────────────────────────┐
 │                            File                             │                                     Change                                      │
 ├─────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────┤
 │ Domain/Committee/Committee.php                              │ reconstruct(): CommitteeStructure required, array $assignments added            │
 ├─────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────┤
 │ Domain/Committee/CommitteeAssignment.php                    │ Add reconstruct() static factory                                                │
 ├─────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────┤
 │ Infrastructure/Models/CommitteeModel.php                    │ Add assignments() HasMany                                                       │
 ├─────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────┤
 │ Infrastructure/Repositories/EloquentCommitteeRepository.php │ Load assignments on find; explicit sync on save; use CommitteeStructureRegistry │
 ├─────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────┤
 │ Infrastructure/Providers/MembershipServiceProvider.php      │ Bind 3 new use cases + query                                                    │
 ├─────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────┤
 │ Http/Controllers/Committee/CommitteeDashboardController.php │ Switch to GetCommitteeDashboardQuery                                            │
 ├─────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────┤
 │ routes/committee/committeeRoutes.php                        │ Add all mutation routes                                                         │
 └─────────────────────────────────────────────────────────────┴─────────────────────────────────────────────────────────────────────────────────┘

 ---
 Non-Negotiable Rules

 1. Domain layer: zero Laravel. CommitteeAssignment::reconstruct() is pure PHP.
 2. CommitteeStructure always required in reconstruct(). No null, no skipped validation. Domain methods always enforce invariants — never rely on "DB already enforced it."
 3. withoutGlobalScopes() only with explicit organisation_id filter.
 4. Use case owns DB::transaction. Controller never wraps in transactions.
 5. Events dispatched AFTER transaction commit.
 6. Frontend: router.post() not raw fetch for mutations (Inertia 2.0). Search API can use fetch (JSON endpoint).
 7. reconstruct() fires NO domain events — hydration is silent.
 8. Authorization on every mutation — $this->authorize('manage-committee', $organisation).
 9. No updateOrCreate in assignment persistence — explicit find → update OR create.
 10. DB sync deletes removed assignments — aggregate is the single source of truth.
 11. Phase 3 scope is committee management only. Finance, membership migration, ULID work, and strangler-fig work are in separate later phases (see remainder of this document).

 ---
 Senior Architect Review Summary (incorporated)

 The following concerns from the senior architect review are addressed in the V2 plan:

 ┌─────────────────────────────────────────────────┬─────────────────────────────────────────────────────────────────────────────────────────────────┐
 │                     Concern                     │                                           Resolution                                            │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ Domain reconstitution gap                       │ 3A.2 (CommitteeAssignment::reconstruct) + 3A.3 (Committee::reconstruct with required structure) │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ Persistence round-trip correctness              │ Explicit round-trip tests added to 3A.1 repo tests                                              │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ Missing CommitteeAssignmentModel + relationship │ 3A.5                                                                                            │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ Empty assignments projection bug                │ 3B.4 (GetCommitteeDashboardQuery CQRS read)                                                     │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ RemoveMemberFromCommittee missing               │ 3B.2                                                                                            │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ updateOrCreate inconsistency                    │ 3A.7 — explicit find→update OR create in both committee and assignment saves                    │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ "DB already enforced" shortcut                  │ Rule #2 above — domain methods always enforce invariants                                        │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ Authorization gaps                              │ 3C.2/3C.3 — every mutation calls $this->authorize()                                             │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ Rate limiting                                   │ 3C.1 — throttle:20,60 on assign/remove routes                                                   │
 ├─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ Non-committee scope in plan                     │ All finance/membership sections are separate phases below, not part of Phase 3 delivery         │
 └─────────────────────────────────────────────────┴─────────────────────────────────────────────────────────────────────────────────────────────────┘

 Items from the prior critical analysis that are already handled or out of scope for Phase 3:
 - Idempotency in RecordFeePayment — already enforced via findByTransactionReference in Phase 3E (separate section)
 - Optimistic locking on Member aggregate — Phase 3B/C of migration plan (separate scope)
 - Async event dispatch — handled by Outbox pattern (Phase 4A, already complete)
 - Saga for ApproveApplication — Phase 3C of migration plan (separate scope)
 - Circuit breaker / retry — infrastructure layer, Phase 4+
 - Event sourcing snapshots — speculative future design, not needed for committee management

 ---
 Verification

 # Phase 3A must pass before 3B begins
 php artisan test tests/Unit/Contexts/Membership/Domain/Committee/
 php artisan test tests/Unit/Contexts/Membership/Infrastructure/Repositories/CommitteeRepositoryTest.php

 # Phase 3B
 php artisan test tests/Feature/Membership/CommitteeManagementTest.php

 # Full regression check after each phase
 php artisan test tests/Feature/Membership/
 php artisan test  # all tests must stay green

 ---
 Phase 3E — Finance Integration Implementation Plan (v4 — Corrected Architecture)

 Context

 Phase 3D complete (14/14 tests passing). Phase 3E fixes broken use cases, adds PaymentDetails as a Value Object inside Fee, adds PaymentPolicy domain service, adds FeeWaived event, and delivers a finance
 dashboard.

 Key decisions (v4 corrections):
 - Payment is a Value Object embedded in Fee — no separate aggregate, no dual-write, no second table
 - MembershipPaymentService (Phase 3A legacy service) is NOT created — all payment writes go through RecordFeePayment use case. Single write authority enforced from day one.
 - No feature flags for write paths — when a use case is wired to a controller action, the legacy write is deleted immediately (not feature-flagged)
 - Use case owns DB::transaction. Events are pulled from aggregates inside the transaction, then dispatched after the transaction commits
 - withoutGlobalScopes() is permitted only when paired immediately with an explicit organisation_id filter — this is the established tenant-safe pattern for this project. Never use withoutGlobalScopes()
 alone.
 - Jobs are thin dispatchers — all business logic lives in use cases, not job classes
 - Finance integration is event-driven: FeePaid domain event → integration event → Finance listener. Membership never calls Finance directly.

 ---
 Audit: Pre-3E State

 ┌───────────────────────────┬──────────────────────┬─────────────────────────────────────────────────────────┐
 │           File            │        State         │                          Issue                          │
 ├───────────────────────────┼──────────────────────┼─────────────────────────────────────────────────────────┤
 │ RecordFeePayment.php      │ Broken               │ Calls $eventBus->dispatch() — only dispatchAll() exists │
 ├───────────────────────────┼──────────────────────┼─────────────────────────────────────────────────────────┤
 │ RecordFeePayment.php      │ Wrong layer          │ DB::transaction belongs in use case, not controller     │
 ├───────────────────────────┼──────────────────────┼─────────────────────────────────────────────────────────┤
 │ WaiveFee.php              │ Broken               │ Takes raw FeeId/TenantId; no EventBus; no events        │
 ├───────────────────────────┼──────────────────────┼─────────────────────────────────────────────────────────┤
 │ RecordFeePaymentCommand   │ Incomplete           │ Has paymentMethod but never flows to aggregate          │
 ├───────────────────────────┼──────────────────────┼─────────────────────────────────────────────────────────┤
 │ Fee::markAsPaid()         │ Needs PaymentDetails │ Should accept a PaymentDetails value object             │
 ├───────────────────────────┼──────────────────────┼─────────────────────────────────────────────────────────┤
 │ Fee::waive()              │ No guard             │ Can waive a paid fee; fires no domain event             │
 ├───────────────────────────┼──────────────────────┼─────────────────────────────────────────────────────────┤
 │ EloquentFeeRepository     │ Missing import       │ MembershipTypeId not imported — crashes on reconstitute │
 ├───────────────────────────┼──────────────────────┼─────────────────────────────────────────────────────────┤
 │ MembershipServiceProvider │ Wrong binding        │ WaiveFee constructor gets no EventBus                   │
 └───────────────────────────┴──────────────────────┴─────────────────────────────────────────────────────────┘

 ---
 Audit: Additional Pre-3E Issues (v4)

 ┌──────────────────────────────────────────┬──────────────────────────────────────────────────────────────┬─────────────────────────────────────────────────────────────────────────────┐
 │                  Issue                   │                           Problem                            │                                     Fix                                     │
 ├──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────┤
 │ MembershipPaymentService (Phase 3A plan) │ Creates a second write path for payments                     │ ❌ DO NOT CREATE — route through RecordFeePayment use case                  │
 ├──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────┤
 │ Feature flags for write paths            │ Two concurrent writes violate single-write-authority         │ ❌ NO feature flags for writes — delete legacy write when use case is wired │
 ├──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────┤
 │ Domain logic in Job classes              │ GenerateAnnualMembershipFeesJob would contain business rules │ ✅ Job → delegates to use case only                                         │
 ├──────────────────────────────────────────┼──────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────┤
 │ Direct Finance calls                     │ Membership calling Finance directly crosses context boundary │ ✅ Event-driven: FeePaid → integration event → Finance listener             │
 └──────────────────────────────────────────┴──────────────────────────────────────────────────────────────┴─────────────────────────────────────────────────────────────────────────────┘

 ---
 Scope

 1. Database migration — add payment columns to membership_fees table (no separate payments table)
 2. Create PaymentDetails Value Object — embedded inside Fee, not a separate aggregate
 3. Create PaymentPolicy domain service — validates tenant, duplicate, and fee state before payment
 4. Update Fee aggregate — markAsPaid(PaymentDetails) with guard; waive(reason) with guard + event
 5. Create FeeWaived domain event
 6. Enhance RecordFeePaymentCommand — carry full payment metadata
 7. Create WaiveFeeCommand DTO
 8. Fix EloquentFeeRepository — add missing import; persist PaymentDetails fields; add findByTransactionReference
 9. Rewrite RecordFeePayment — uses PaymentPolicy; own transaction; events dispatched after commit
 10. Rewrite WaiveFee — WaiveFeeCommand; EventBus; events after commit
 11. Update MemberController — pass full payment metadata; switch to WaiveFeeCommand; no DB::transaction
 12. Create GetFinanceDashboard query + DTOs (reads from membership_fees directly — projection table deferred to Phase 4)
 13. Update MembershipServiceProvider — fix WaiveFee binding; bind GetFinanceDashboard
 14. Write all tests TDD-first

 NOT in scope: Payment aggregate, PaymentRepository, membership_payments table, PaymentId, refunds, partial payments, FinanceProjection table (Phase 4), integration event mapper (Phase 4), external providers.

 ---
 Domain Model (v4)

 Application ──approves──> Member
 Member ──owns──> Fee (aggregate root)
 Fee ──embeds──> PaymentDetails (value object, nullable — set when paid)
 PaymentPolicy ──validates──> Fee (domain service, used by use case)

 Fee is the single write target. No second aggregate, no dual-write.

 ---
 Critical Files

 New Files

 ┌─────────────────────────────────────────────────────────────────────────────────────────────────┬─────────────────────────────────────────────────────────┐
 │                                              File                                               │                         Purpose                         │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ Domain/Fee/ValueObjects/PaymentDetails.php                                                      │ Immutable payment metadata VO                           │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ Domain/Fee/Services/PaymentPolicy.php                                                           │ Domain service — validates tenant, duplicate, fee state │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ Domain/Fee/Events/FeeWaived.php                                                                 │ Domain event                                            │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ Infrastructure/Database/Migrations/Tenant/2026_05_04_add_payment_columns_to_membership_fees.php │ DB migration                                            │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ Application/Fee/DTOs/WaiveFeeCommand.php                                                        │ DTO                                                     │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ Application/Fee/DTOs/GetFinanceDashboardQuery.php                                               │ DTO                                                     │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ Application/Fee/DTOs/FinanceDashboardDTO.php                                                    │ DTO                                                     │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ Application/Fee/Queries/GetFinanceDashboard.php                                                 │ Read model                                              │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ tests/Unit/Contexts/Membership/Domain/Fee/FeeTest.php                                           │ Unit tests                                              │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ tests/Unit/Contexts/Membership/Domain/Fee/PaymentPolicyTest.php                                 │ Policy unit tests                                       │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ tests/Feature/Membership/FeePaymentTest.php                                                     │ Feature tests                                           │
 ├─────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┤
 │ tests/Feature/Membership/FeeWaiverTest.php                                                      │ Feature tests                                           │
 └─────────────────────────────────────────────────────────────────────────────────────────────────┴─────────────────────────────────────────────────────────┘

 Modified Files

 ┌────────────────────────────────────────────────────────┬───────────────────────────────────────────────────────────────────────┐
 │                          File                          │                                Change                                 │
 ├────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Domain/Fee/Fee.php                                     │ markAsPaid(PaymentDetails), waive(reason) with guard, FeeWaived event │
 ├────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Domain/Fee/Events/FeePaid.php                          │ Include paidAt from PaymentDetails                                    │
 ├────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Application/Fee/DTOs/RecordFeePaymentCommand.php       │ Add paymentMethod, transactionReference, paidAt, recordedByUserId     │
 ├────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Application/Fee/UseCases/RecordFeePayment.php          │ Idempotency guard (throw); own transaction; events after transaction  │
 ├────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Application/Fee/UseCases/WaiveFee.php                  │ WaiveFeeCommand; EventBus; events after transaction                   │
 ├────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Infrastructure/Repositories/EloquentFeeRepository.php  │ Fix MembershipTypeId import; persist/reconstitute PaymentDetails      │
 ├────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Infrastructure/Providers/MembershipServiceProvider.php │ Fix WaiveFee binding; bind GetFinanceDashboard                        │
 ├────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ Http/Controllers/MemberController.php                  │ Pass full payment metadata; switch to WaiveFeeCommand                 │
 └────────────────────────────────────────────────────────┴───────────────────────────────────────────────────────────────────────┘

 ---
 TDD Implementation Order

 STEP 0 — Write Failing Tests (Red Phase)

 tests/Unit/Contexts/Membership/Domain/Fee/FeeTest.php
 - fee_starts_as_pending
 - mark_as_paid_with_payment_details_transitions_status
 - mark_as_paid_stores_payment_details_on_fee
 - mark_as_paid_fires_fee_paid_event
 - mark_as_paid_on_already_paid_fee_throws_domain_exception
 - mark_as_paid_on_overdue_fee_is_allowed
 - waive_transitions_status_to_waived
 - waive_on_paid_fee_throws_domain_exception
 - waive_on_overdue_fee_throws_domain_exception
 - waive_fires_fee_waived_event_with_reason
 - paid_fee_has_payment_details
 - unpaid_fee_has_no_payment_details

 tests/Feature/Membership/FeePaymentTest.php (route: POST organisations.members.record-payment)
 - guest_cannot_record_payment (→ redirect to login)
 - non_admin_cannot_record_payment (→ 403)
 - record_payment_marks_fee_as_paid (DB: membership_fees.status=paid)
 - record_payment_persists_payment_method (DB: membership_fees.payment_method)
 - record_payment_persists_transaction_reference
 - record_payment_with_duplicate_transaction_reference_throws (→ session error)
 - cannot_pay_fee_from_different_tenant (→ 404)
 - cannot_pay_already_paid_fee (→ session error / DomainException)

 tests/Feature/Membership/FeeWaiverTest.php (route: PATCH organisations.members.waive-fees)
 - guest_cannot_waive (→ redirect to login)
 - non_admin_cannot_waive (→ 403)
 - waive_changes_status_to_waived (DB: membership_fees.status=waived)
 - paid_fee_cannot_be_waived (→ session error)
 - cannot_waive_fee_from_different_tenant (fee unchanged)

 ---
 STEP 1 — Database Migration

 New: Infrastructure/Database/Migrations/Tenant/2026_05_04_add_payment_columns_to_membership_fees.php

 Adds to existing membership_fees table:
 - payment_method varchar(50) nullable — bank_transfer|cash|card
 - transaction_reference varchar(200) nullable, unique per organisation (guard at DB level)
 - paid_at timestamp nullable
 - recorded_by uuid nullable

 No new tables. No fee_payment_id. No second write target.

 ---
 STEP 2 — PaymentDetails Value Object

 New: Domain/Fee/ValueObjects/PaymentDetails.php

 final readonly class PaymentDetails
 {
     public function __construct(
         public readonly string $method,               // bank_transfer|cash|card
         public readonly \DateTimeImmutable $paidAt,
         public readonly ?string $transactionReference = null,
         public readonly ?string $recordedByUserId = null,
     ) {
         if (!in_array($method, ['bank_transfer', 'cash', 'card'], true)) {
             throw new \DomainException("Invalid payment method: {$method}");
         }
     }
 }

 ---
 STEP 2b — PaymentPolicy Domain Service

 New: Domain/Fee/Services/PaymentPolicy.php

 Pure PHP — no Laravel. Validates all preconditions before payment can be recorded.

 final class PaymentPolicy
 {
     public function assertCanRecord(
         Fee $fee,
         TenantId $tenantId,
         ?string $transactionReference,
         FeeRepositoryInterface $feeRepository
     ): void {
         if (!$fee->getTenantId()->equals($tenantId)) {
             throw new \DomainException('Fee does not belong to this tenant');
         }

         if (!$fee->getStatus()->isPending() && !$fee->getStatus()->isOverdue()) {
             throw new \DomainException('Fee cannot be paid in its current state');
         }

         if ($transactionReference !== null) {
             $existing = $feeRepository->findByTransactionReference($transactionReference, $tenantId);
             if ($existing !== null) {
                 throw new \DomainException('Payment already recorded for this reference');
             }
         }
     }
 }

 Unit tests: tests/Unit/Contexts/Membership/Domain/Fee/PaymentPolicyTest.php
 - allows_pending_fee_for_matching_tenant
 - allows_overdue_fee_for_matching_tenant
 - throws_when_fee_belongs_to_different_tenant
 - throws_when_fee_is_already_paid
 - throws_when_transaction_reference_already_exists
 - allows_null_transaction_reference_without_duplicate_check

 ---
 STEP 3 — Update Fee Aggregate

 File: Domain/Fee/Fee.php

 Add field:
 private ?PaymentDetails $paymentDetails = null;

 Update markAsPaid():
 public function markAsPaid(PaymentDetails $payment): void {
     if (!$this->status->isPending() && !$this->status->isOverdue()) {
         throw new \DomainException('Fee cannot be paid in its current state');
     }
     $this->status = FeeStatus::paid();
     $this->paymentDetails = $payment;
     $this->recordThat(new FeePaid($this->id, $payment->paidAt));
 }

 Update waive():
 public function waive(string $reason = ''): void {
     if (!$this->status->isPending()) {
         throw new \DomainException('Only pending fees can be waived');
     }
     $this->status = FeeStatus::waived();
     $this->recordThat(new FeeWaived($this->id, $reason, new \DateTimeImmutable()));
 }

 Add getter:
 public function getPaymentDetails(): ?PaymentDetails { return $this->paymentDetails; }

 Update reconstitute() — accept optional ?PaymentDetails $paymentDetails = null.

 ---
 STEP 4 — FeeWaived Domain Event

 New: Domain/Fee/Events/FeeWaived.php
 final readonly class FeeWaived {
     public function __construct(
         private FeeId $feeId,
         private string $reason,
         private \DateTimeImmutable $occurredAt
     ) {}
     public function getFeeId(): FeeId { return $this->feeId; }
     public function getReason(): string { return $this->reason; }
     public function getOccurredAt(): \DateTimeImmutable { return $this->occurredAt; }
 }

 ---
 STEP 5 — Fix EloquentFeeRepository

 File: Infrastructure/Repositories/EloquentFeeRepository.php

 Add missing import:
 use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
 use App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails;

 In save() — persist PaymentDetails fields:
 $details = $fee->getPaymentDetails();
 $model->payment_method = $details?->method;
 $model->transaction_reference = $details?->transactionReference;
 $model->paid_at = $details?->paidAt;
 $model->recorded_by = $details?->recordedByUserId;

 In reconstitute() — rebuild PaymentDetails if paid:
 $paymentDetails = null;
 if ($record->payment_method !== null && $record->paid_at !== null) {
     $paymentDetails = new PaymentDetails(
         method: $record->payment_method,
         paidAt: new \DateTimeImmutable($record->paid_at),
         transactionReference: $record->transaction_reference,
         recordedByUserId: $record->recorded_by,
     );
 }

 return Fee::reconstitute(
     FeeId::fromString($record->id),
     MemberId::fromString($record->member_id),
     MembershipTypeId::fromString($record->membership_type_id),
     FeeStatus::fromString($record->status),
     TenantId::fromOrganisationId($record->organisation_id),
     (string) $record->amount,
     new \DateTimeImmutable($record->due_date),
     $paymentDetails,
 );

 ---
 STEP 6 — Enhance RecordFeePaymentCommand

 File: Application/Fee/DTOs/RecordFeePaymentCommand.php

 final readonly class RecordFeePaymentCommand {
     public function __construct(
         public readonly FeeId $feeId,
         public readonly TenantId $tenantId,
         public readonly string $paymentMethod,
         public readonly \DateTimeImmutable $paidAt,
         public readonly ?string $transactionReference = null,
         public readonly ?string $recordedByUserId = null,
     ) {}
 }

 Note: memberId removed — Fee aggregate already holds memberId; the use case reads it from the loaded fee.

 ---
 STEP 7 — Create WaiveFeeCommand

 New: Application/Fee/DTOs/WaiveFeeCommand.php

 final readonly class WaiveFeeCommand {
     public function __construct(
         public readonly FeeId $feeId,
         public readonly TenantId $tenantId,
         public readonly string $reason = '',
         public readonly ?string $waivedByUserId = null,
     ) {}
 }

 ---
 STEP 8 — Rewrite RecordFeePayment Use Case

 File: Application/Fee/UseCases/RecordFeePayment.php

 final class RecordFeePayment {
     public function __construct(
         private FeeRepositoryInterface $feeRepository,
         private PaymentPolicy $paymentPolicy,
         private LaravelEventBus $eventBus
     ) {}

     public function execute(RecordFeePaymentCommand $command): void {
         $events = DB::transaction(function () use ($command) {
             $fee = $this->feeRepository->find($command->feeId, $command->tenantId);
             if (!$fee) {
                 throw new \RuntimeException('Fee not found');
             }

             // All preconditions validated by domain service (throws DomainException)
             $this->paymentPolicy->assertCanRecord(
                 $fee,
                 $command->tenantId,
                 $command->transactionReference,
                 $this->feeRepository
             );

             $payment = new PaymentDetails(
                 method: $command->paymentMethod,
                 paidAt: $command->paidAt,
                 transactionReference: $command->transactionReference,
                 recordedByUserId: $command->recordedByUserId,
             );

             $fee->markAsPaid($payment);
             $this->feeRepository->save($fee, $command->tenantId);

             return $fee->pullEvents();
         });

         // Dispatch AFTER transaction commits — listeners see committed data
         $this->eventBus->dispatchAll($events);
     }
 }

 FeeRepositoryInterface needs findByTransactionReference(string $ref, TenantId $tenantId): ?Fee added.

 ---
 STEP 9 — Rewrite WaiveFee Use Case

 File: Application/Fee/UseCases/WaiveFee.php

 final class WaiveFee {
     public function __construct(
         private FeeRepositoryInterface $feeRepository,
         private LaravelEventBus $eventBus
     ) {}

     public function execute(WaiveFeeCommand $command): void {
         $events = DB::transaction(function () use ($command) {
             $fee = $this->feeRepository->find($command->feeId, $command->tenantId);
             if (!$fee) {
                 throw new \RuntimeException('Fee not found');
             }

             $fee->waive($command->reason);
             $this->feeRepository->save($fee, $command->tenantId);

             return $fee->pullEvents();
         });

         // Dispatch AFTER transaction commits
         $this->eventBus->dispatchAll($events);
     }
 }

 ---
 STEP 10 — Update FeeRepositoryInterface

 File: Domain/Repositories/FeeRepositoryInterface.php

 Add:
 public function findByTransactionReference(string $ref, TenantId $tenantId): ?Fee;

 Implement in EloquentFeeRepository:
 public function findByTransactionReference(string $ref, TenantId $tenantId): ?Fee
 {
     $record = $this->model
         ->withoutGlobalScopes()
         ->where('organisation_id', $tenantId->value())
         ->where('transaction_reference', $ref)
         ->first();

     return $record ? $this->reconstitute($record) : null;
 }

 ---
 STEP 11 — Update MemberController

 File: app/Http/Controllers/MemberController.php

 recordPayment():
 $validated = $request->validate([
     'fee_id'                  => 'required|uuid|exists:membership_fees,id',
     'payment_method'          => 'required|in:bank_transfer,cash,card',
     'transaction_reference'   => 'nullable|string|max:200',
 ]);

 $recordFeePayment->execute(new RecordFeePaymentCommand(
     feeId: FeeId::fromString($validated['fee_id']),
     tenantId: TenantId::fromOrganisationId($organisation->id),
     paymentMethod: $validated['payment_method'],
     paidAt: new \DateTimeImmutable(),
     transactionReference: $validated['transaction_reference'] ?? null,
     recordedByUserId: auth()->id(),
 ));

 waiveFees() — per fee (inside existing loop):
 $waiveFeeUseCase->execute(new WaiveFeeCommand(
     feeId: FeeId::fromString($fee->id),
     tenantId: TenantId::fromOrganisationId($organisation->id),
     reason: 'Waived by administrator',
     waivedByUserId: auth()->id(),
 ));

 No DB::transaction in controller — use case owns it.

 ---
 STEP 12 — Update MembershipServiceProvider

 File: Infrastructure/Providers/MembershipServiceProvider.php

 - Fix RecordFeePayment — inject only FeeRepositoryInterface + LaravelEventBus (no PaymentRepository)
 - Fix WaiveFee — inject FeeRepositoryInterface + LaravelEventBus
 - Bind GetFinanceDashboard

 ---
 STEP 13 — Create Finance Dashboard Query

 New: Application/Fee/Queries/GetFinanceDashboard.php
 New: Application/Fee/DTOs/GetFinanceDashboardQuery.php
 New: Application/Fee/DTOs/FinanceDashboardDTO.php

 Read model — direct DB::table('membership_fees') queries (no aggregates). Returns:
 - pendingTotal: float — sum of amount where status=pending
 - overdueTotal: float — sum of amount where status=overdue
 - paidThisMonth: float — sum of amount where status=paid and paid_at >= start of month
 - recentPayments: array — last 20 paid fees, fields: member name, amount, method, paid_at

 ---
 Transaction Boundary Rule

 Use Case  = owns DB::transaction; returns $events array from closure
 Use Case  = calls eventBus->dispatchAll($events) AFTER transaction commits
 Controller = validate + build command + call use case + HTTP response
 Domain    = zero awareness of transactions or event bus

 ---
 Non-Negotiable Rules

 1. Single write authority: All payment writes go through RecordFeePayment use case. No MembershipPaymentService. No feature flags for write paths — when a use case is wired to a controller, the old direct
 write is deleted immediately.
 2. Repository pattern: first() + conditional create. Never firstOrCreate, updateOrCreate.
 3. Tenant isolation: withoutGlobalScopes() is permitted only when paired immediately with an explicit ->where('organisation_id', $tenantId->value()). Never withoutGlobalScopes() alone.
 4. Transaction boundary: Use case owns DB::transaction. Controller never wraps in a transaction.
 5. Event dispatch: Pull events inside transaction, dispatch after commit — $events = DB::transaction(fn() => ...); $eventBus->dispatchAll($events).
 6. Idempotency: Throw DomainException on duplicate transactionReference — never silently return.
 7. Domain layer: Pure PHP only. Zero Laravel in Domain or Application layers.
 8. Finance integration: Membership fires domain events. Finance context listens. Membership never calls Finance directly.
 9. Jobs: Job classes are thin dispatchers only — all business logic lives in use cases.

 ---
 Verification

 php artisan test tests/Unit/Contexts/Membership/Domain/Fee/FeeTest.php
 php artisan test tests/Unit/Contexts/Membership/Domain/Fee/PaymentPolicyTest.php
 php artisan test tests/Feature/Membership/FeePaymentTest.php
 php artisan test tests/Feature/Membership/FeeWaiverTest.php
 php artisan test tests/Feature/Membership/MembershipApplicationTest.php  # must stay 14/14
 php artisan test tests/Feature/Membership/

 ---
 Legacy Membership → DDD Membership Context: Professional Migration Plan

 Context

 Why this plan exists: The system has two parallel membership implementations that must be unified:

 1. Legacy system (app/Models/, app/Http/Controllers/Membership/, app/Services/) — built iteratively, 95% complete (April 2026 audit), 42+ tests passing. Handles the full membership lifecycle: member
 registration, fee tracking, application approval, voter eligibility, election assignments.
 2. DDD Membership Context (app/Contexts/Membership/) — designed with proper DDD boundaries. Committee sub-context is complete (Phases 1–2). The Member sub-context does not exist yet in the DDD layer.

 Key constraint: The legacy system is production-running. Migration must be incremental (strangler fig), not a big-bang rewrite. Every phase must leave the system fully operational.

 Source documents analyzed:
 - architecture/membership/20260503_0133_membership_contexts_should be unified.md
 - architecture/membership/20260503_0134_membership_refactoring.md
 - architecture/membership/full_membership/20260416_AUDIT_REPORT.md
 - architecture/membership/election_only_or_full_membership.md
 - architecture/membership/20260405_0918_membership_ablauf.md
 - 10+ additional architecture review documents

 ---
 Current State Assessment

 What Already Exists (Keep, Extend)

 ┌─────────────┬────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┬───────────────────────────────────────────┬───────────────────┐
 │    Layer    │                                                         Component                                                          │                 Location                  │      Status       │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────┼───────────────────┤
 │ Models      │ Member, MembershipType, MembershipFee, MembershipRenewal, MembershipApplication, ElectionMembership, VoterInvitation       │ app/Models/                               │ ✅                │
 │             │                                                                                                                            │                                           │ Production-ready  │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────┼───────────────────┤
 │ Services    │ VoterEligibilityService, VoterImportService, MembershipPaymentService                                                      │ app/Services/                             │ ✅ Complete       │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────┼───────────────────┤
 │ Controllers │ MemberController, MembershipDashboardController, MembershipApplicationController, MembershipFeeController,                 │ app/Http/Controllers/Membership/          │ ✅ Working        │
 │             │ MembershipRenewalController, MembershipTypeController, OrganisationParticipantController                                   │                                           │                   │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────┼───────────────────┤
 │ Migrations  │ 18 migrations for members, fees, applications, renewals, election_memberships, voter_invitations                           │ database/migrations/                      │ ✅ Applied        │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────┼───────────────────┤
 │ DDD Context │ Committee aggregate, CommitteeAssignment, all 6 type strategies, domain events, repository interface                       │ app/Contexts/Membership/Domain/Committee/ │ ✅ Phase 2        │
 │             │                                                                                                                            │                                           │ complete          │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────┼───────────────────┤
 │ Tests       │ 42+ tests across voter eligibility, member management, dual-mode                                                           │ tests/Feature/, tests/Unit/               │ ✅ All passing    │
 └─────────────┴────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┴───────────────────────────────────────────┴───────────────────┘

 What is Dead/Broken (Delete Early)

 ┌──────────────────────────────┬─────────────────────────────────────────────────┐
 │             File             │                     Reason                      │
 ├──────────────────────────────┼─────────────────────────────────────────────────┤
 │ app/Models/Committee.php     │ No relationships defined — completely dead stub │
 ├──────────────────────────────┼─────────────────────────────────────────────────┤
 │ app/Models/CommitteeType.php │ No relationships — dead stub                    │
 ├──────────────────────────────┼─────────────────────────────────────────────────┤
 │ app/Models/Assignment.php    │ Missing return in users() — broken stub         │
 └──────────────────────────────┴─────────────────────────────────────────────────┘

 These three legacy stubs will be deleted in Phase 3A without any impact.

 What is Missing (Must Build)

 ┌────────────────────────────────────────────────────────────────────┬────────────────────────────────────────────────────────────────────────┐
 │                                Gap                                 │                                 Impact                                 │
 ├────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────┤
 │ MembershipPayment recording UI                                     │ Admins cannot record payments in the UI (only markPaid() exempts fees) │
 ├────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────┤
 │ Application approval UI                                            │ Applications exist but require manual DB updates to approve            │
 ├────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────┤
 │ Finance integration (FinanceReportService, InvoiceService)         │ No PDF invoices, no payment reconciliation                             │
 ├────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────┤
 │ Scheduled jobs (annual fees, renewal reminders, overdue detection) │ All renewal automation is missing                                      │
 ├────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────┤
 │ Member aggregate in DDD context                                    │ DDD layer has no Member entity                                         │
 ├────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────┤
 │ DDD use cases for membership lifecycle                             │ No application layer for Member domain                                 │
 └────────────────────────────────────────────────────────────────────┴────────────────────────────────────────────────────────────────────────┘

 ---
 Architecture Decisions (Non-Negotiable)

 These came from the multi-architecture-review analysis, including a post-plan review by ChatGPT and senior architect validation. Three critical corrections were added after that review — marked ⚠️ below.

 1. Keep Member aggregate lean.
 Member owns only: id, personal info, status, tenant. Fees belong to Fee aggregate. Applications belong to Application aggregate. Committee roles belong to Committee aggregate.

 2. Single database, GlobalScope tenancy.
 No separate tenant connection. All membership tables use organisation_id + BelongsToTenant GlobalScope. Repository methods accept TenantId parameter explicitly.

 3. Dual-mode preserved.
 The organisations.uses_full_membership boolean stays. Full membership mode: voter eligibility requires active Member with paid fees. Election-only mode: any organisation user can be a voter. All new code
 must respect this flag.

 4. Strangler fig — single write authority (⚠️ CRITICAL correction).
 The strangler fig does NOT mean two parallel write paths coexist long-term. The rule is: on the same day a DDD use case is wired to a controller action, the old direct Eloquent write in that action is
 deleted — not feature-flagged, deleted. Legacy models become READ ONLY for that entity from that moment forward. Two concurrent write paths on the same table — even temporarily — risk broken invariants and
 debugging nightmares. The write boundary must be singular at all times.

                  SAFE (single write authority)
 Controller  →  Use Case  →  Domain  →  Repository  →  DB
                  ↑
   (legacy is routed HERE, not around it)

 5. Domain services own business logic (⚠️ correction).
 VoterEligibilityService, FeeCalculationService, and RenewalPolicyService currently live in app/Services/ (application layer). These contain pure business rules and must move into
 app/Contexts/Membership/Domain/Services/. The app/Services/ wrappers become thin adapters that delegate to domain services. Use cases orchestrate — they call domain methods and domain services; they do NOT
 contain business logic.

 Use Case = orchestrate only
 Domain Service = complex business rules spanning multiple aggregates
 Aggregate = invariants and state transitions

 6. Event strategy: domain events vs integration events (⚠️ correction).
 Events are defined but the bus and dispatch strategy were missing. Clarification:
 - Domain events (MemberActivated, FeePaid) — synchronous, in-process, fired inside the aggregate via recordEvent(), dispatched within the same transaction
 - Integration events (MemberActivatedIntegration, FeePaidIntegration) — async, cross-context, dispatched to Laravel's event bus AFTER the transaction commits, consumed by Finance and Election contexts
 - Finance integration uses events: FeePaid event → Finance context listener creates Income record. No direct method calls across context boundaries.

 7. ULID consistency.
 All new DDD entities in NEW tables use ULID. The members table (legacy) keeps UUID until Phase 5. The repository interface abstracts the ID type — callers receive MemberId value objects and never interact
 with raw UUID or ULID strings. No mixed ID logic leaks outside the repository.

 8. No "one giant context".
 Membership Context internally is structured as sub-contexts: Core (Member), Committee (existing), Application, Billing (Fees), Election (ElectionMembership bridge). They share the namespace but have clean
 internal boundaries.

 ---
 Bounded Context Map

 Defines which context owns what and how they communicate. Cross-context communication is event-driven only — no direct repository or model access across context boundaries.

 ┌─────────────────────────────────────────────────────────────────┐
 │                    MEMBERSHIP CONTEXT                            │
 │  Member · MembershipType · Application · Fee · ElectionMembership│
 └───────────┬─────────────────────────┬───────────────────────────┘
             │ publishes                │ publishes
     MemberActivated             FeePaid (integration)
             │                         │
             ▼                         ▼
 ┌───────────────────┐      ┌──────────────────────┐
 │  ELECTION CONTEXT │      │   FINANCE CONTEXT     │
 │  Checks voter     │      │  Creates Income record│
 │  eligibility via  │      │  when FeePaid fires   │
 │  MemberStatus     │      └──────────────────────┘
 └───────────────────┘

 ┌─────────────────────┐
 │  IDENTITY/USER      │  External concept — User is referenced by
 │  (platform layer)   │  MemberId + email only. Not embedded.
 └─────────────────────┘

 Anti-Corruption Layer: app/Contexts/Membership/ACL/
   LegacyMemberAdapter.php    — wraps app/Models/Member for read-only legacy access
   LegacyFeeAdapter.php       — wraps app/Models/MembershipFee for read-only legacy access

 ---
 Migration Phases

 Phase 3A — Complete Legacy Gaps (Week 1–2)

 Goal: Close all functional gaps identified in the April 2026 audit before any architectural migration begins. These are self-contained features that don't require DDD refactoring.

 Step 1: Delete dead legacy stubs
 - Delete app/Models/Committee.php (no relationships, no usages)
 - Delete app/Models/CommitteeType.php (dead stub)
 - Delete app/Models/Assignment.php (broken stub)
 - Run: grep -r "App\Models\Committee" --include="*.php" to confirm zero usages

 Step 2: MembershipPayment + Finance Integration

 New files:
 - database/migrations/2026_05_XX_create_membership_payments_table.php — fields: id (UUID), member_id, fee_id, organisation_id, amount, currency, payment_method (bank_transfer/cash/card),
 transaction_reference (nullable), recorded_by, paid_at, timestamps
 - app/Services/MembershipPaymentService.php — recordPayment($member, $fee, $amount, $method) wraps in DB::transaction(), updates fee status, updates member fees_status, fires MembershipFeePaid event
 - resources/js/Pages/Members/Finance.vue — Outstanding fees table + Record Payment modal

 Modifications:
 - app/Http/Controllers/MemberController.php — add finance() and recordPayment() methods
 - routes/organisations.php — add GET /members/{member}/finance, POST /members/{member}/record-payment
 - resources/js/Pages/Members/Index.vue — add Finance link to each row

 Step 3: Application Approval UI

 New files:
 - resources/js/Pages/Organisations/Membership/Applications/Index.vue — list pending/approved/rejected applications with approve/reject actions
 - resources/js/Components/ApplicationApprovalModal.vue — select membership type, confirm approval

 Modifications:
 - app/Http/Controllers/Membership/MembershipApplicationController.php — add approve($application) and reject($application, reason) methods
 - app/Services/MembershipApplicationService.php — approveApplication() creates Member record + MembershipFee, rejectApplication() closes application, both fire domain events

 Step 4: Scheduled Jobs

 New files:
 - app/Jobs/GenerateAnnualMembershipFees.php — runs 30 days before expiry, creates pending MembershipFee
 - app/Jobs/SendRenewalReminder.php — sends email 14 days before due date
 - app/Jobs/MarkOverdueMembers.php — sets fees_status='overdue' when past due_date
 - app/Jobs/CleanupExpiredInvitations.php — soft-deletes used/expired VoterInvitations
 - app/Console/Kernel.php — register all 4 jobs as daily schedules

 Step 5: Phase 3A Tests

 php artisan test tests/Feature/Finance/MembershipPaymentTest.php
 php artisan test tests/Feature/Membership/ApplicationApprovalTest.php
 php artisan test tests/Feature/Jobs/
 php artisan test  # full suite, no regressions

 ---
 Phase 3B — Member Domain Layer (Week 3)

 Goal: Create the DDD domain for the Member sub-context. No database changes. No legacy touchpoints yet. Pure PHP domain classes.

 Directory: app/Contexts/Membership/Domain/Member/

 Aggregate Root:
 Member.php          — id (MemberId/ULID), status (MemberStatus), tenantId (TenantId)
                       personalInfo (PersonalInfo value object — name, email, phone)
                       membershipTypeId (MembershipTypeId — reference only, not embedded)
                       Methods: activate(), suspend(), archive()
 MemberId.php        — ULID value object
 MemberStatus.php    — active | inactive | suspended | archived

 Value Objects:
 Domain/Member/ValueObjects/
 ├── PersonalInfo.php        — readonly: fullName, email, phone
 └── MembershipTypeRef.php   — reference to MembershipType by ID only (no FK embedding)

 Domain Events:
 Domain/Member/Events/
 ├── MemberRegistered.php
 ├── MemberActivated.php
 ├── MemberSuspended.php
 └── MemberArchived.php

 Separate Aggregates (stub now, implement in 3C):
 Domain/Application/Application.php      — ApplicationId, status (ApplicationStatus), tenantId
 Domain/Application/ApplicationStatus.php — draft | submitted | approved | rejected
 Domain/Application/Events/ApplicationSubmitted.php
 Domain/Application/Events/ApplicationApproved.php
 Domain/Application/Events/ApplicationRejected.php

 Domain/Fee/Fee.php                      — FeeId, memberId (ref), amount, dueDate, status
 Domain/Fee/FeeStatus.php               — pending | paid | overdue | waived
 Domain/Fee/Events/FeePaid.php
 Domain/Fee/Events/FeeOverdue.php

 Repository Interfaces:
 Domain/Repositories/MemberRepositoryInterface.php
     findForTenant(MemberId, TenantId): ?Member
     saveForTenant(Member): void
     findByStatusForTenant(MemberStatus, TenantId): array
     findExpiringForTenant(TenantId, int $withinDays): array

 Domain/Repositories/ApplicationRepositoryInterface.php
 Domain/Repositories/FeeRepositoryInterface.php

 TDD — Unit Tests (write before implementation):

 tests/Unit/Contexts/Membership/Domain/Member/
 ├── MemberTest.php          — invariants: status transitions, event recording
 ├── MemberStatusTest.php    — valid/invalid transitions
 └── PersonalInfoTest.php    — validation: empty name, invalid email format

 ---
 Phase 3C — Infrastructure + Application Layer (Week 4–5)

 Goal: Wire the domain layer to the database using Eloquent models as persistence adapters. Create use cases that orchestrate domain logic.

 Infrastructure Models (new, alongside legacy):
 app/Contexts/Membership/Infrastructure/Models/
 ├── MemberContextModel.php      — Eloquent model for members table, BelongsToTenant, SoftDeletes
                                    NOTE: maps to same 'members' table as legacy Member model
                                    Uses withoutGlobalScopes() safety wrapper in repo
 ├── ApplicationContextModel.php — maps to membership_applications table
 └── FeeContextModel.php         — maps to membership_fees table

 Repositories:
 app/Contexts/Membership/Infrastructure/Repositories/
 ├── EloquentMemberRepository.php
 │     — findForTenant(MemberId, TenantId): uses MemberContextModel, GlobalScope handles tenant
 │     — saveForTenant(Member): updateOrCreate with domain→Eloquent serialization
 │     — reconstitute(MemberContextModel): Eloquent→domain reconstruction
 │
 ├── EloquentApplicationRepository.php
 └── EloquentFeeRepository.php

 Important: The context repositories map to the same tables as the legacy models. The difference is that the context layer goes through the domain aggregate before touching the DB. Legacy models continue to
 work in parallel via their own Eloquent queries.

 Context Migrations (idempotent guards):

 The members table already exists. Context migrations must use Schema::hasColumn() guards:
 // app/Contexts/Membership/Infrastructure/Database/Migrations/
 // 2026_05_XX_augment_members_for_context.php
 if (!Schema::hasColumn('members', 'context_version')) {
     Schema::table('members', function (Blueprint $table) {
         $table->string('context_version')->default('1')->after('updated_at');
     });
 }

 Do NOT create members table from the context. It already exists. Skip create_members_table.php entirely.

 Use Cases (Application Layer):
 app/Contexts/Membership/Application/Member/
 ├── RegisterMember.php          — creates Member aggregate, saves, fires events, sends invitation email
 ├── ActivateMember.php          — transitions status, saves, fires MemberActivated
 ├── SuspendMember.php           — transitions status, saves, fires MemberSuspended
 └── DTOs/
     ├── RegisterMemberCommand.php   — readonly: tenantId, email, fullName, phone, membershipTypeId
     └── MemberView.php              — flat view for controllers

 app/Contexts/Membership/Application/Application/
 ├── SubmitApplication.php       — creates Application aggregate, saves, fires ApplicationSubmitted
 ├── ApproveApplication.php      — approves, creates Member, creates Fee, fires events
 └── RejectApplication.php       — rejects, fires ApplicationRejected

 app/Contexts/Membership/Application/Fee/
 ├── RecordFeePayment.php        — DB::transaction: marks fee paid, updates member fees_status, fires FeePaid
 └── WaiveFee.php                — marks fee waived (exempt)

 Service Provider — Update:
 app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php
 Add bindings:
 - MemberRepositoryInterface::class → EloquentMemberRepository::class
 - ApplicationRepositoryInterface::class → EloquentApplicationRepository::class
 - FeeRepositoryInterface::class → EloquentFeeRepository::class
 Load migrations from context directory

 TDD — Integration Tests:
 tests/Unit/Contexts/Membership/Infrastructure/Repositories/EloquentMemberRepositoryTest.php
     — test_save_persists_for_current_tenant()
     — test_find_returns_null_for_different_tenant()
     — test_reconstitute_rebuilds_aggregate_from_eloquent()

 tests/Feature/Contexts/Membership/Application/RegisterMemberTest.php
     — test_member_is_created_with_correct_status()
     — test_member_registration_fires_domain_event()
     — test_duplicate_registration_for_same_tenant_raises_exception()

 ---
 Phase 3D — Strangler Fig: Route Through Context (Week 6–7)

 Goal: Update legacy controllers to delegate to DDD use cases. Legacy models are NOT deleted yet. Both paths remain active during this phase.

 Pattern for each controller method:

 // BEFORE (legacy):
 public function store(Request $request, Organisation $org) {
     $member = Member::create($request->validated()); // direct Eloquent
     return redirect()->back()->with('success', '...');
 }

 // AFTER (strangler fig — controller delegates to use case):
 public function store(StoreRequest $request, Organisation $org) {
     $command = $request->toCommand($this->tenantContext->currentTenantId());
     $memberId = app(RegisterMember::class)->execute($command); // DDD use case
     return redirect()->back()->with('success', '...');
 }

 Controllers to update (in order — lowest risk first):

 1. MembershipTypeController — CRUD for membership types (no complex domain logic)
 2. MemberController::store() — register new member
 3. MemberController::markPaid() → delegate to WaiveFee use case
 4. MemberController::recordPayment() → delegate to RecordFeePayment use case
 5. MembershipApplicationController::store() → delegate to SubmitApplication
 6. MembershipApplicationController::approve() → delegate to ApproveApplication
 7. MembershipApplicationController::reject() → delegate to RejectApplication

 Feature flag pattern (for high-risk switches):

 if (config('features.use_ddd_member_registration', false)) {
     $memberId = app(RegisterMember::class)->execute($command);
 } else {
     // Legacy path
     $member = Member::create($request->validated());
 }

 Enable flag in .env → FEATURE_DDD_MEMBER_REGISTRATION=true once the DDD path is proven in staging.

 Tests after each controller switch:
 - Run full test suite: php artisan test
 - Confirm 42+ tests still pass
 - Add feature-flag-specific tests covering both paths

 ---
 Phase 3E — Finance Integration (Week 8)

 Goal: Connect the MembershipPaymentService to the existing Income/Outcome finance module. Deliver a working finance dashboard.

 Key insight from architecture docs: The existing finance module (app/Domain/Finance/Controllers/, IncomeController, OutcomeController) is already present. Do NOT rebuild it — extend it.

 What to build:

 1. app/Services/FinanceReportService.php
   - generateReport(Organisation $org, string $period): array
   - Metrics: total_outstanding, collected_this_period, by_payment_method, new_members, renewals
 2. app/Http/Controllers/Finance/MembershipFinanceController.php
   - dashboard(Organisation $org): Response — loads stats + recent payments
   - report(Organisation $org, string $period): JsonResponse — period-based report
 3. resources/js/Pages/Finance/MembershipDashboard.vue
   - Stat cards: outstanding balance, collected YTD, overdue count
   - Recent payments table (paginated, 20 per page)
   - Period selector for reports
 4. app/Mail/MembershipInvoiceMail.php + resources/views/invoices/membership-fee.blade.php
   - PDF invoice generation using Laravel Snappy or DomPDF
   - Sent automatically when fee is created via ApproveApplication use case

 Routes:
 // routes/organisations.php
 Route::get('/finance/membership', [MembershipFinanceController::class, 'dashboard'])->name('finance.membership');
 Route::get('/finance/membership/report', [MembershipFinanceController::class, 'report'])->name('finance.membership.report');

 Caching strategy (per architecture review):
 - Cache dashboard stats with 5-minute TTL: "dashboard_stats_{$orgId}_{$role}"
 - Invalidate cache on: fee payment recorded, application approved, member status changed
 - Add composite DB indexes: (organisation_id, status, paid_at) on membership_payments

 ---
 Phase 4 — Dead Code Removal (Week 9)

 Pre-condition: All legacy controller methods have been switched to DDD use cases AND all tests pass via both paths with feature flags.

 What to remove:

 ┌───────────────────────────────────────────────────────────────────────────┬──────────────────────────────────────────┐
 │                                   File                                    │                  Action                  │
 ├───────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────┤
 │ app/Models/Committee.php                                                  │ Already deleted in Phase 3A              │
 ├───────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────┤
 │ app/Models/CommitteeType.php                                              │ Already deleted in Phase 3A              │
 ├───────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────┤
 │ app/Models/Assignment.php                                                 │ Already deleted in Phase 3A              │
 ├───────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────┤
 │ Legacy direct Eloquent calls in controllers (BEFORE delegation was added) │ Already replaced in 3D                   │
 ├───────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────┤
 │ Feature flag conditionals (once DDD path is the only path)                │ Remove if (config('features...')) blocks │
 ├───────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────┤
 │ Legacy PHP stubs that were replaced by DDD                                │ Delete after grepping for usages         │
 └───────────────────────────────────────────────────────────────────────────┴──────────────────────────────────────────┘

 What NOT to remove yet:
 - Legacy Eloquent models (app/Models/Member.php, etc.) — they still serve as read models for simple Eloquent queries
 - Legacy service classes — they are now called BY the DDD use cases in some cases

 Verification before deletion:
 grep -r "App\Models\Committee" --include="*.php" # must return 0 results
 grep -r "App\Models\CommitteeType" --include="*.php" # must return 0 results
 php artisan test # must pass 100%

 ---
 Phase 5 — ULID Migration (Deferred, Week 10+)

 Why deferred: UUID → ULID migration requires:
 - A member_id_mapping (uuid, ulid) table
 - Dual-write period (write to both ID columns simultaneously)
 - Gradual read switchover (read from ULID first, fall back to UUID)
 - All FK references across all tables updated
 - Zero-downtime cutover

 When to trigger Phase 5: Only after Phase 4 is complete AND the system has been running on the DDD path exclusively for at least 4 weeks with no incidents.

 Migration pattern (do not implement early):
 -- mapping table
 CREATE TABLE member_id_mapping (
     uuid CHAR(36) PRIMARY KEY,
     ulid CHAR(26) UNIQUE NOT NULL,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 );

 ---
 Critical Files Reference

 Files to CREATE (in order)

 ┌───────┬──────────────────────────────────────────────────────────────────────────────────┬──────────────────────────────────────────────┐
 │ Phase │                                       File                                       │                   Purpose                    │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ database/migrations/2026_05_XX_create_membership_payments_table.php              │ Payment tracking                             │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ app/Services/MembershipPaymentService.php                                        │ Payment recording, fee status update         │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ app/Services/MembershipApplicationService.php                                    │ Application approval/rejection orchestration │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ app/Jobs/GenerateAnnualMembershipFees.php                                        │ Renewal automation                           │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ app/Jobs/SendRenewalReminder.php                                                 │ Fee reminder emails                          │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ app/Jobs/MarkOverdueMembers.php                                                  │ Overdue detection                            │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ app/Jobs/CleanupExpiredInvitations.php                                           │ Invitation cleanup                           │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ resources/js/Pages/Members/Finance.vue                                           │ Payment UI                                   │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3A    │ resources/js/Pages/Organisations/Membership/Applications/Index.vue               │ Application approval UI                      │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Member/Member.php                                 │ Member aggregate root                        │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Member/MemberId.php                               │ ULID value object                            │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Member/MemberStatus.php                           │ Status enum-like                             │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Member/ValueObjects/PersonalInfo.php              │ Name, email, phone                           │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Member/Events/MemberRegistered.php                │ Domain event                                 │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Member/Events/MemberActivated.php                 │ Domain event                                 │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Member/Events/MemberSuspended.php                 │ Domain event                                 │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Application/Application.php                       │ Application aggregate                        │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Fee/Fee.php                                       │ Fee aggregate                                │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3B    │ app/Contexts/Membership/Domain/Repositories/MemberRepositoryInterface.php        │ Contract                                     │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Infrastructure/Models/MemberContextModel.php             │ Eloquent adapter                             │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Infrastructure/Repositories/EloquentMemberRepository.php │ DB access                                    │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Application/Member/RegisterMember.php                    │ Use case                                     │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Application/Member/ActivateMember.php                    │ Use case                                     │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Application/Member/DTOs/RegisterMemberCommand.php        │ DTO                                          │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Application/Application/ApproveApplication.php           │ Use case                                     │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Application/Application/RejectApplication.php            │ Use case                                     │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Application/Fee/RecordFeePayment.php                     │ Use case                                     │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3E    │ app/Services/FinanceReportService.php                                            │ Finance reports                              │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3E    │ app/Http/Controllers/Finance/MembershipFinanceController.php                     │ Finance dashboard                            │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3E    │ resources/js/Pages/Finance/MembershipDashboard.vue                               │ Finance UI                                   │
 ├───────┼──────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────────────────┤
 │ 3E    │ app/Mail/MembershipInvoiceMail.php                                               │ Invoice email                                │
 └───────┴──────────────────────────────────────────────────────────────────────────────────┴──────────────────────────────────────────────┘

 Files to MODIFY

 ┌───────┬────────────────────────────────────────────────────────────────────────────────┬──────────────────────────────────┐
 │ Phase │                                      File                                      │              Change              │
 ├───────┼────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────┤
 │ 3A    │ app/Http/Controllers/MemberController.php                                      │ Add finance(), recordPayment()   │
 ├───────┼────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────┤
 │ 3A    │ app/Http/Controllers/Membership/MembershipApplicationController.php            │ Add approve(), reject()          │
 ├───────┼────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────┤
 │ 3A    │ routes/organisations.php                                                       │ Add payment + application routes │
 ├───────┼────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────┤
 │ 3A    │ resources/js/Pages/Members/Index.vue                                           │ Add Finance column link          │
 ├───────┼────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────┤
 │ 3A    │ app/Console/Kernel.php                                                         │ Register scheduled jobs          │
 ├───────┼────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────┤
 │ 3C    │ app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php │ Bind new repositories            │
 ├───────┼────────────────────────────────────────────────────────────────────────────────┼──────────────────────────────────┤
 │ 3D    │ All 7 legacy controllers listed above                                          │ Delegate to DDD use cases        │
 └───────┴────────────────────────────────────────────────────────────────────────────────┴──────────────────────────────────┘

 Files to DELETE

 ┌───────┬──────────────────────────────┬────────────────────────────────────────────────┐
 │ Phase │             File             │                  Verification                  │
 ├───────┼──────────────────────────────┼────────────────────────────────────────────────┤
 │ 3A    │ app/Models/Committee.php     │ grep -r "App\Models\Committee" → 0 results     │
 ├───────┼──────────────────────────────┼────────────────────────────────────────────────┤
 │ 3A    │ app/Models/CommitteeType.php │ grep -r "App\Models\CommitteeType" → 0 results │
 ├───────┼──────────────────────────────┼────────────────────────────────────────────────┤
 │ 3A    │ app/Models/Assignment.php    │ grep -r "App\Models\Assignment" → 0 results    │
 └───────┴──────────────────────────────┴────────────────────────────────────────────────┘

 ---
 Verification: Phase-by-Phase

 # Phase 3A complete
 php artisan migrate
 php artisan test tests/Feature/Finance/MembershipPaymentTest.php
 php artisan test tests/Feature/Membership/ApplicationApprovalTest.php
 php artisan test  # full suite: still 42+ passing

 # Phase 3B complete
 php artisan test tests/Unit/Contexts/Membership/Domain/Member/

 # Phase 3C complete
 php artisan test tests/Unit/Contexts/Membership/Infrastructure/
 php artisan test tests/Feature/Contexts/Membership/Application/

 # Phase 3D complete (after each controller switch)
 php artisan test  # full suite must remain green

 # Phase 3E complete
 php artisan route:list | grep finance
 php artisan test tests/Feature/Finance/

 # Overall health check
 php artisan test --coverage

 ---
 Risk Registry

 ┌──────────────────────────────────────────────────────┬─────────────┬────────┬────────────────────────────────────────────────────────────────────────────┐
 │                         Risk                         │ Probability │ Impact │                                 Mitigation                                 │
 ├──────────────────────────────────────────────────────┼─────────────┼────────┼────────────────────────────────────────────────────────────────────────────┤
 │ Legacy tests break after strangler fig switch        │ Medium      │ High   │ Run full suite after each controller update; keep feature flags            │
 ├──────────────────────────────────────────────────────┼─────────────┼────────┼────────────────────────────────────────────────────────────────────────────┤
 │ Context repository conflicts with legacy GlobalScope │ Medium      │ Medium │ Test tenant isolation explicitly; use withoutGlobalScopes() only in tests  │
 ├──────────────────────────────────────────────────────┼─────────────┼────────┼────────────────────────────────────────────────────────────────────────────┤
 │ members table conflict (context + legacy both write) │ Low         │ High   │ MemberContextModel maps same table but uses separate save paths; test both │
 ├──────────────────────────────────────────────────────┼─────────────┼────────┼────────────────────────────────────────────────────────────────────────────┤
 │ MembershipPayment breaks Income module integration   │ Low         │ Medium │ Use DB::transaction; test Income creation in service tests                 │
 ├──────────────────────────────────────────────────────┼─────────────┼────────┼────────────────────────────────────────────────────────────────────────────┤
 │ Scheduled jobs fire on test environment              │ Low         │ Medium │ Gate all jobs behind app()->isProduction() check                           │
 ├──────────────────────────────────────────────────────┼─────────────┼────────┼────────────────────────────────────────────────────────────────────────────┤
 │ Phase 5 ULID migration causes downtime               │ High        │ High   │ Deferred until stable; use mapping table pattern when ready                │
 └──────────────────────────────────────────────────────┴─────────────┴────────┴────────────────────────────────────────────────────────────────────────────┘

 ---
 Excluded from This Plan

 ┌─────────────────────────────────────────┬────────────────────┐
 │                 Feature                 │       Phase        │
 ├─────────────────────────────────────────┼────────────────────┤
 │ ULID migration for members table        │ Phase 5 (deferred) │
 ├─────────────────────────────────────────┼────────────────────┤
 │ Full PBAC (Policy-Based Access Control) │ Phase 4+           │
 ├─────────────────────────────────────────┼────────────────────┤
 │ Audit logging integration               │ Phase 4            │
 ├─────────────────────────────────────────┼────────────────────┤
 │ Newsletter system                       │ Phase 3E+          │
 ├─────────────────────────────────────────┼────────────────────┤
 │ Online payment gateway (Stripe/PayPal)  │ Phase 4            │
 ├─────────────────────────────────────────┼────────────────────┤
 │ Multi-currency support                  │ Phase 4            │
 └─────────────────────────────────────────┴────────────────────┘

 ---
 Plan Author: Senior Software Architect review — May 3, 2026
 Supersedes: Committee Dashboard Browser Test Unblock plan (Phases 1–2 are complete)
 Status: Ready for Phase 3A implementation
╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌

 Claude has written up a plan and is ready to execute. Would you like to proceed?

 > 1. Yes, auto-accept edits
   2. Yes, manually approve edits
   3. No, refine with Ultraplan on Claude Code on the web
   4. Tell Claude what to change
      shift+tab to approve with this feedback