# Common Workflows: Step-by-Step Examples

Real-world examples showing how to accomplish common tasks.

---

## 📋 Workflow 1: Create a New Committee

### Scenario
An administrator wants to create a district committee for Kathmandu.

### Step 1: Write Tests

```php
// tests/Feature/Committee/CreateCommitteeTest.php
public function test_create_district_committee()
{
    $org = Organisation::factory()->create();
    $user = User::factory()->for($org)->create();
    
    $this->actingAs($user);
    
    $response = $this->post('/committees', [
        'name' => 'Kathmandu District',
        'code' => 'KATH-DIST',
        'type' => 'district',
        'geo_reference' => 'np.3.15',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('committees', [
        'name' => 'Kathmandu District',
        'code' => 'KATH-DIST',
        'type' => 'district',
        'operational_geo_reference' => 'np.3.15',
    ]);
}
```

### Step 2: Create Domain Event

```php
// app/Contexts/Membership/Domain/Events/CommitteeFormed.php
final class CommitteeFormed extends AbstractDomainEvent
{
    public function __construct(
        private readonly CommitteeId $committeeId,
        private readonly CommitteeType $type,
        private readonly CommitteeName $name,
        private readonly ?GeoReference $geoReference,
    ) {
        parent::__construct();
    }

    public function getCommitteeId(): CommitteeId { return $this->committeeId; }
    public function getType(): CommitteeType { return $this->type; }
    public function getName(): CommitteeName { return $this->name; }
    public function getGeoReference(): ?GeoReference { return $this->geoReference; }
}
```

### Step 3: Create DTO

```php
// app/Contexts/Membership/Application/Committee/DTOs/CreateCommitteeCommand.php
final readonly class CreateCommitteeCommand
{
    public function __construct(
        public TenantId $tenantId,
        public CommitteeType $type,
        public string $name,
        public string $code,
        public ?string $geoReference = null,
    ) {}
}
```

### Step 4: Use Case

```php
// app/Contexts/Membership/Application/Committee/CreateCommittee.php
final class CreateCommittee
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees,
        private readonly EventBus $eventBus
    ) {}

    public function execute(CreateCommitteeCommand $command): CommitteeId
    {
        return DB::transaction(function () use ($command) {
            $committee = Committee::form(
                type: $command->type,
                name: CommitteeName::of($command->name),
                code: CommitteeCode::of($command->code),
                tenantId: $command->tenantId,
                geoReference: $command->geoReference 
                    ? GeoReference::fromString($command->geoReference) 
                    : null
            );

            $this->committees->saveForTenant($committee);
            $this->eventBus->dispatchAll($committee->pullEvents());

            return $committee->getId();
        });
    }
}
```

### Step 5: Form Request

```php
// app/Http/Requests/CommitteeRequest.php
class CommitteeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:committees',
            'type' => 'required|in:central,province,district,ward,youth,women,student',
            'geo_reference' => 'nullable|string|regex:/^np\.\d+(\.\d+)*$/',
        ];
    }

    public function getCommand(TenantContextInterface $tenantContext): CreateCommitteeCommand
    {
        return new CreateCommitteeCommand(
            tenantId: $tenantContext->currentTenantId(),
            type: CommitteeType::from($this->input('type')),
            name: $this->input('name'),
            code: $this->input('code'),
            geoReference: $this->input('geo_reference'),
        );
    }
}
```

### Step 6: Controller

```php
// app/Http/Controllers/CommitteeController.php
final class CommitteeController
{
    public function __construct(
        private readonly CreateCommittee $createCommittee,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function store(CommitteeRequest $request): RedirectResponse
    {
        try {
            $id = $this->createCommittee->execute(
                $request->getCommand($this->tenantContext)
            );

            return redirect()
                ->route('committee.show', $id)
                ->with('success', 'Committee created successfully');
        } catch (InvalidGeographyException) {
            return back()->withErrors([
                'geo_reference' => 'Invalid geography for this committee type'
            ]);
        }
    }
}
```

### Step 7: Route

```php
// routes/committee/committeeRoutes.php
Route::middleware(['auth', 'verified', 'ensure.organisation'])->group(function () {
    Route::post('/committees', [CommitteeController::class, 'store'])->name('committee.store');
});
```

### Step 8: Vue Component

```vue
<!-- resources/js/Pages/Committee/Create.vue -->
<template>
    <form @submit.prevent="submitForm">
        <div>
            <label>Name</label>
            <input v-model="form.name" type="text" required />
            <span v-if="errors.name" class="error">{{ errors.name }}</span>
        </div>

        <div>
            <label>Code</label>
            <input v-model="form.code" type="text" required />
            <span v-if="errors.code" class="error">{{ errors.code }}</span>
        </div>

        <div>
            <label>Type</label>
            <select v-model="form.type" required>
                <option value="central">Central</option>
                <option value="district">District</option>
                <option value="province">Province</option>
            </select>
        </div>

        <div v-if="form.type !== 'central'">
            <label>Geography</label>
            <input 
                v-model="form.geo_reference" 
                type="text" 
                placeholder="np.3.15"
                required 
            />
        </div>

        <button type="submit">Create Committee</button>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    code: '',
    type: 'central',
    geo_reference: '',
})

const submitForm = () => {
    form.post('/committees')
}
</script>
```

---

## 👥 Workflow 2: Assign Member to Committee

### Scenario
A district coordinator assigns an elected member as chairperson of a district committee.

### Step 1: Form Request

```php
// app/Http/Requests/AssignMemberRequest.php
class AssignMemberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'member_id' => 'required|string|exists:members',
            'role_path' => 'required|string|in:1.0.0,2.0.0,3.0.0,4.0.0',
            'nomination_type' => 'required|in:elected,nominated,appointed',
            'term_end_date' => 'nullable|date|after:today',
        ];
    }
}
```

### Step 2: Use Case

```php
// app/Contexts/Membership/Application/Committee/AssignMemberToCommittee.php
final class AssignMemberToCommittee
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees,
        private readonly EventBus $eventBus
    ) {}

    public function execute(AssignMemberDto $dto): void
    {
        DB::transaction(function () use ($dto) {
            $committee = $this->committees->findForTenant(
                $dto->committeeId,
                $dto->tenantId
            ) ?? throw new CommitteeNotFoundException($dto->committeeId);

            $committee->assignMember(
                memberId: $dto->memberId,
                rolePath: $dto->rolePath,
                nominationType: $dto->nominationType,
                memberGeography: $dto->memberGeography,
                termEndDate: $dto->termEndDate,
            );

            $this->committees->saveForTenant($committee);
            $this->eventBus->dispatchAll($committee->pullEvents());
        });
    }
}
```

### Step 3: Controller

```php
// app/Http/Controllers/CommitteeAssignmentController.php
final class CommitteeAssignmentController
{
    public function __construct(
        private readonly AssignMemberToCommittee $assignMember,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function store(
        string $committeeId,
        AssignMemberRequest $request
    ): RedirectResponse {
        try {
            $this->assignMember->execute(new AssignMemberDto(
                committeeId: CommitteeId::fromString($committeeId),
                tenantId: $this->tenantContext->currentTenantId(),
                memberId: MemberId::fromString($request->input('member_id')),
                rolePath: RolePath::fromString($request->input('role_path')),
                nominationType: NominationType::from($request->input('nomination_type')),
                termEndDate: $request->input('term_end_date')
                    ? new DateTimeImmutable($request->input('term_end_date'))
                    : null,
            ));

            return redirect()
                ->route('committee.show', $committeeId)
                ->with('success', 'Member assigned successfully');

        } catch (InvalidMemberAssignmentException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
```

### Step 4: Route

```php
Route::post('/committee/{committeeId}/assign-member', 
    [CommitteeAssignmentController::class, 'store']
)->name('committee.assign-member');
```

---

## 📊 Workflow 3: Query Committee Dashboard Data

### Scenario
User visits `/committee/01KQNEV4GREY80JNE1EE5YDSVB/dashboard` and sees:
- Committee details
- List of members in committee
- Sub-committees (if any)

### Step 1: Use Case

```php
// app/Contexts/Membership/Application/Committee/GetCommitteeDashboard.php
final class GetCommitteeDashboard
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees
    ) {}

    public function execute(CommitteeId $id, TenantId $tenantId): CommitteeDashboardView
    {
        $committee = $this->committees->findForTenant($id, $tenantId)
            ?? throw new CommitteeNotFoundException($id);

        // Find sub-committees (committees within this one's geography)
        $subCommittees = [];
        if ($committee->getOperationalGeoReference() !== null) {
            $subCommittees = $this->committees->findByGeographyForTenant(
                $committee->getOperationalGeoReference(),
                $tenantId
            );
        }

        return new CommitteeDashboardView($committee, $subCommittees);
    }
}
```

### Step 2: View Model

```php
// app/Contexts/Membership/Application/Committee/Views/CommitteeDashboardView.php
final readonly class CommitteeDashboardView
{
    public function __construct(
        private Committee $committee,
        private array $subCommittees
    ) {}

    private function resolveLevel(): int
    {
        return match ($this->committee->type()->value()) {
            'central' => 1,
            'province' => 2,
            'district' => 3,
            'ward' => 4,
            default => 5,
        };
    }

    public function toArray(): array
    {
        return [
            'committee' => [
                'id' => $this->committee->getId()->value(),
                'name' => $this->committee->getName()->value(),
                'code' => $this->committee->getCode()->value(),
                'type' => $this->committee->type()->value(),
                'level' => $this->resolveLevel(),
                'status' => $this->committee->getStatus()->value(),
                'geo_reference' => $this->committee->getOperationalGeoReference()?->value(),
            ],
            'sub_committees' => array_map(
                fn (Committee $c) => [
                    'id' => $c->getId()->value(),
                    'name' => $c->getName()->value(),
                    'code' => $c->getCode()->value(),
                    'type' => $c->type()->value(),
                ],
                $this->subCommittees
            ),
            'assignments' => array_map(
                fn (CommitteeAssignment $a) => [
                    'id' => $a->getId()->value(),
                    'member_id' => $a->getMemberId()->value(),
                    'role' => $a->getRolePath()->value(),
                    'joined_date' => $a->getJoinedDate()->format('Y-m-d'),
                ],
                $this->committee->getAssignments()
            ),
        ];
    }
}
```

### Step 3: Controller

```php
// app/Http/Controllers/CommitteeDashboardController.php
final class CommitteeDashboardController
{
    public function __construct(
        private readonly GetCommitteeDashboard $useCase,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function show(string $committeeId): Response
    {
        try {
            $view = $this->useCase->execute(
                CommitteeId::fromString($committeeId),
                $this->tenantContext->currentTenantId()
            );
        } catch (CommitteeNotFoundException) {
            abort(404);
        }

        return Inertia::render('Committee/Dashboard', $view->toArray());
    }
}
```

### Step 4: Vue Component

```vue
<!-- resources/js/Pages/Committee/Dashboard.vue -->
<template>
    <div class="committee-dashboard">
        <!-- Header -->
        <div class="header">
            <h1>{{ committee.name }}</h1>
            <p class="code">Code: {{ committee.code }}</p>
            <p class="type">{{ formatType(committee.type) }}</p>
        </div>

        <!-- Info Cards -->
        <div class="cards">
            <div class="card">
                <h3>Level</h3>
                <p>{{ committee.level }}</p>
            </div>
            <div class="card">
                <h3>Status</h3>
                <p>{{ committee.status }}</p>
            </div>
            <div class="card">
                <h3>Members</h3>
                <p>{{ assignments.length }}</p>
            </div>
        </div>

        <!-- Assignments Table -->
        <div class="assignments">
            <h2>Members</h2>
            <table v-if="assignments.length > 0">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="a in assignments" :key="a.id">
                        <td>{{ a.member_id }}</td>
                        <td>{{ formatRole(a.role) }}</td>
                        <td>{{ a.joined_date }}</td>
                    </tr>
                </tbody>
            </table>
            <p v-else>No members assigned</p>
        </div>

        <!-- Sub-Committees -->
        <div v-if="subCommittees.length > 0" class="sub-committees">
            <h2>Sub-Committees</h2>
            <ul>
                <li v-for="c in subCommittees" :key="c.id">
                    <a :href="`/committee/${c.id}/dashboard`">
                        {{ c.name }} ({{ c.type }})
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
    committee: Object,
    sub_committees: Array,
    assignments: Array,
})

const formatType = (type) => {
    const types = {
        central: 'Central Committee',
        district: 'District Committee',
        province: 'Province Committee',
    }
    return types[type] || type
}

const formatRole = (rolePath) => {
    const roles = {
        '1.0.0': 'Chairperson',
        '2.0.0': 'Vice Chairperson',
        '3.0.0': 'Secretary',
    }
    return roles[rolePath] || rolePath
}
</script>
```

---

## 🧪 Workflow 4: Testing the Full Flow

### Integration Test

```php
// tests/Integration/Committee/CreateAndAssignTest.php
class CreateAndAssignTest extends TestCase
{
    use RefreshDatabase;

    private TenantId $tenantId;
    private CommitteeRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantId = TenantId::fromString('test-org-uuid');
        $this->repo = app(CommitteeRepositoryInterface::class);
    }

    public function test_full_committee_workflow()
    {
        // 1. Create committee
        $createUseCase = app(CreateCommittee::class);
        $committeeId = $createUseCase->execute(new CreateCommitteeCommand(
            tenantId: $this->tenantId,
            type: CommitteeType::district(),
            name: 'Kathmandu District',
            code: 'KATH-DIST',
            geoReference: 'np.3.15',
        ));

        // 2. Verify it exists
        $committee = $this->repo->findForTenant($committeeId, $this->tenantId);
        $this->assertNotNull($committee);
        $this->assertEquals('Kathmandu District', $committee->getName()->value());

        // 3. Assign member
        $assignUseCase = app(AssignMemberToCommittee::class);
        $assignUseCase->execute(new AssignMemberDto(
            committeeId: $committeeId,
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            rolePath: RolePath::chairperson(),
            nominationType: NominationType::elected(),
        ));

        // 4. Reload and verify assignment
        $committee = $this->repo->findForTenant($committeeId, $this->tenantId);
        $this->assertCount(1, $committee->getAssignments());

        // 5. Query dashboard
        $dashboardUseCase = app(GetCommitteeDashboard::class);
        $view = $dashboardUseCase->execute($committeeId, $this->tenantId);
        $data = $view->toArray();

        $this->assertEquals('Kathmandu District', $data['committee']['name']);
        $this->assertCount(1, $data['assignments']);
    }
}
```

---

## 🎯 Quick Reference: What to Edit for Each Task

| Task | Files to Edit | Use Case |
|------|---------------|----------|
| Add form field | FormRequest + Vue | Form submission |
| Add database column | Migration | Schema change |
| Add business rule | Domain aggregate | Validation logic |
| Add event listener | EventServiceProvider + Listener class | React to event |
| Add dashboard section | Vue component + View model | Display data |
| Add API endpoint | Route + Controller | HTTP access |
| Add repository method | Repository interface + Implementation | Data access |

---

**Next:** Read [08_FAQ.md](./08_FAQ.md) for common questions
