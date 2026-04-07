# Claude CLI Prompt: Organisation Creation Page (TDD First)

## 📋 Copy This Complete Prompt into Claude CLI

```markdown
## Context
The application currently has no `/organisations` index route or `/organisations/create` page. Users need a proper page to create new organisations instead of using a problematic modal.

## TDD Approach
Write tests FIRST, then implement the feature.

## Phase 1: Write Tests (RED)

### Test File 1: `tests/Feature/Organisation/OrganisationCreationTest.php`

```php
<?php

namespace Tests\Feature\Organisation;

use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationCreationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_organisation_page_is_accessible_to_authenticated_users()
    {
        $response = $this->actingAs($this->user)
            ->get(route('organisations.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Organisations/Create')
        );
    }

    /** @test */
    public function unauthenticated_user_cannot_access_create_page()
    {
        $response = $this->get(route('organisations.create'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_organisation_with_valid_name()
    {
        $response = $this->actingAs($this->user)
            ->post(route('organisations.store'), [
                'name' => 'New Test Organisation',
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('organisations', [
            'name' => 'New Test Organisation',
            'type' => 'tenant',
        ]);

        // User becomes owner
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $this->user->id,
            'role' => 'owner',
        ]);

        // User's current organisation is updated
        $this->user->refresh();
        $this->assertNotNull($this->user->organisation_id);
    }

    /** @test */
    public function organisation_name_is_required()
    {
        $response = $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => '']);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function organisation_name_must_be_at_least_3_characters()
    {
        $response = $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Ab']);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function organisation_name_max_255_characters()
    {
        $response = $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => str_repeat('a', 256)]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function organisation_slug_is_generated_from_name()
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'My Amazing Organisation']);

        $this->assertDatabaseHas('organisations', [
            'name' => 'My Amazing Organisation',
            'slug' => 'my-amazing-organisation',
        ]);
    }

    /** @test */
    public function duplicate_slug_gets_counter_appended()
    {
        // Create first organisation
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Test Org']);

        // Create second user for second organisation
        $user2 = User::factory()->create();

        $this->actingAs($user2)
            ->post(route('organisations.store'), ['name' => 'Test Org']);

        $this->assertDatabaseHas('organisations', [
            'name' => 'Test Org',
            'slug' => 'test-org',
        ]);

        $this->assertDatabaseHas('organisations', [
            'name' => 'Test Org',
            'slug' => 'test-org-1',
        ]);
    }

    /** @test */
    public function organisation_index_page_lists_users_organisations()
    {
        // Create two organisations for this user
        $org1 = $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Org One'])
            ->assertRedirect();

        $org2 = $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Org Two'])
            ->assertRedirect();

        $response = $this->actingAs($this->user)
            ->get(route('organisations.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Organisations/Index')
                 ->has('organisations', 2)
        );
    }

    /** @test */
    public function user_only_sees_their_own_organisations()
    {
        // Create org for user1
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'User One Org']);

        // Create org for user2
        $user2 = User::factory()->create();
        $this->actingAs($user2)
            ->post(route('organisations.store'), ['name' => 'User Two Org']);

        // User1 should only see their org
        $response = $this->actingAs($this->user)
            ->get(route('organisations.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->where('organifications', fn($orgs) => 
                count($orgs) === 1 && $orgs[0]['name'] === 'User One Org'
            )
        );
    }

    /** @test */
    public function organisation_show_page_accessible_after_creation()
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Accessible Org']);

        $org = \App\Models\Organisation::where('name', 'Accessible Org')->first();

        $response = $this->actingAs($this->user)
            ->get(route('organisations.show', $org->slug));

        $response->assertStatus(200);
    }

    /** @test */
    public function non_member_cannot_access_organisation_show_page()
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Private Org']);

        $org = \App\Models\Organisation::where('name', 'Private Org')->first();

        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->get(route('organisations.show', $org->slug));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHasErrors();
    }
}
```

### Test File 2: `tests/Feature/Organisation/OrganisationIndexTest.php`

```php
<?php

namespace Tests\Feature\Organisation;

use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrganisationIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function index_page_shows_empty_state_when_no_organisations()
    {
        $response = $this->actingAs($this->user)
            ->get(route('organisations.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Organisations/Index')
                 ->has('organisations', 0)
        );
    }

    /** @test */
    public function index_page_shows_role_for_each_organisation()
    {
        $org = \App\Models\Organisation::factory()->create();
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $this->user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('organisations.index'));

        $response->assertInertia(fn($page) => 
            $page->where('organisations.0.role', 'admin')
        );
    }

    /** @test */
    public function index_page_has_link_to_create_new_organisation()
    {
        $response = $this->actingAs($this->user)
            ->get(route('organisations.index'));

        $response->assertSee('Create Organisation');
        $response->assertSee(route('organisations.create'));
    }
}
```

## Phase 2: Implementation (GREEN)

### Step 1: Add Routes to `routes/web.php`

```php
use App\Http\Controllers\OrganisationController;

// Organisation management (before organisation-scoped routes)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/organisations', [OrganisationController::class, 'index'])->name('organisations.index');
    Route::get('/organisations/create', [OrganisationController::class, 'create'])->name('organisations.create');
    Route::post('/organisations', [OrganisationController::class, 'store'])->name('organisations.store');
});
```

### Step 2: Add Controller Methods to `app/Http/Controllers/OrganisationController.php`

```php
/**
 * List all organisations the user belongs to
 */
public function index(): Response
{
    $user = auth()->user();
    
    $organisations = $user->organisationRoles()
        ->with('organisation')
        ->get()
        ->map(fn($role) => [
            'id' => $role->organisation->id,
            'name' => $role->organisation->name,
            'slug' => $role->organisation->slug,
            'role' => $role->role,
            'joined_at' => $role->created_at?->format('Y-m-d'),
        ]);
    
    return Inertia::render('Organisations/Index', [
        'organisations' => $organisations,
    ]);
}

/**
 * Show the form to create a new organisation
 */
public function create(): Response
{
    return Inertia::render('Organisations/Create');
}
```

### Step 3: Create Vue Components

**`resources/js/Pages/Organisations/Index.vue`** - List user's organisations with cards

**`resources/js/Pages/Organisations/Create.vue`** - Form to create new organisation

## Execution Order

```bash
# 1. Write tests (RED)
php artisan test tests/Feature/Organisation/OrganisationCreationTest.php --no-coverage
php artisan test tests/Feature/Organisation/OrganisationIndexTest.php --no-coverage

# Expected: All tests FAIL

# 2. Add routes

# 3. Add controller methods

# 4. Create Vue components

# 5. Run tests again (GREEN)
php artisan test tests/Feature/Organisation/OrganisationCreationTest.php --no-coverage
php artisan test tests/Feature/Organisation/OrganisationIndexTest.php --no-coverage

# 6. Full regression
php artisan test --no-coverage
```

## Success Criteria

- [ ] `GET /organisations/create` returns 200 with form
- [ ] `POST /organisations` creates organisation with slug
- [ ] User becomes owner automatically
- [ ] Duplicate slugs get counter appended
- [ ] `GET /organisations` lists user's organisations
- [ ] All tests pass
- [ ] No modal issues (standalone page)

Proceed with TDD implementation.
```

---

## Quick Summary

This prompt will make Claude:

1. **Write 12+ tests** covering creation, validation, slug generation, and listing
2. **Add routes** for index, create, and store
3. **Add controller methods** for index and create
4. **Create Vue components** (Index.vue and Create.vue)

**Copy the entire prompt into Claude CLI to execute!** 🚀