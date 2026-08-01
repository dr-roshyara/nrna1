## Perfect! You're in the Ideal Position

Since you have code but **no production data**, you have maximum flexibility with minimal risk. Here's the implementation approach:

# Complete UUID Multi-Tenancy Implementation for Laravel 11

## 1. Database Migrations

### Create Organisations Table
```php
<?php
// database/migrations/2024_01_01_000001_create_organisations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['platform', 'tenant'])->default('tenant');
            $table->boolean('is_default')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Ensure only one default platform organisation
            $table->index('type');
            $table->unique(['type', 'is_default'], 'organisations_unique_default_platform');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
```

### Create Users Table with UUID
```php
<?php
// database/migrations/2024_01_01_000002_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id'); // Current organisation
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('restrict');
                  
            $table->index('organisation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### Create User Organisation Roles Pivot
```php
<?php
// database/migrations/2024_01_01_000003_create_user_organisation_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_organisation_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('organisation_id');
            $table->string('role')->default('member'); // admin, member, etc.
            $table->json('permissions')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');
                  
            $table->unique(['user_id', 'organisation_id'], 'user_org_unique');
            $table->index(['organisation_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_organisation_roles');
    }
};
```

### Example Tenant Table (Elections)
```php
<?php
// database/migrations/2024_01_01_000004_create_elections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');
                  
            $table->index(['organisation_id', 'status', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
```

## 2. Models

### Organisation Model
```php
<?php
// app/Models/Organisation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organisation extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_default',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_default' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organisation_roles')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    public function roles()
    {
        return $this->hasMany(UserOrganisationRole::class);
    }

    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    public function isPlatform(): bool
    {
        return $this->type === 'platform';
    }

    public function isTenant(): bool
    {
        return $this->type === 'tenant';
    }

    public static function getDefaultPlatform(): ?self
    {
        return static::where('type', 'platform')
                     ->where('is_default', true)
                     ->first();
    }
}
```

### User Model
```php
<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasUuids, HasFactory, Notifiable, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'organisation_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function currentOrganisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'user_organisation_roles')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    public function organisationRoles()
    {
        return $this->hasMany(UserOrganisationRole::class);
    }

    public function belongsToOrganisation(string $organisationId): bool
    {
        return $this->organisationRoles()
                    ->where('organisation_id', $organisationId)
                    ->exists();
    }

    public function getRoleInOrganisation(string $organisationId): ?string
    {
        $role = $this->organisationRoles()
                     ->where('organisation_id', $organisationId)
                     ->first();
        
        return $role?->role;
    }
}
```

### UserOrganisationRole Model (Pivot)
```php
<?php
// app/Models/UserOrganisationRole.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOrganisationRole extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'user_organisation_roles';

    protected $fillable = [
        'user_id',
        'organisation_id',
        'role',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
```

### Example Tenant Model (Election)
```php
<?php
// app/Models/Election.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Election extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'title',
        'description',
        'status',
        'starts_at',
        'ends_at',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
```

## 3. Tenant Context Service

```php
<?php
// app/Services/TenantContext.php

namespace App\Services;

use App\Models\Organisation;
use App\Models\User;
use RuntimeException;

class TenantContext
{
    private ?Organisation $currentOrganisation = null;
    private ?User $currentUser = null;

    public function setContext(User $user, Organisation $organisation): void
    {
        if (!$user->belongsToOrganisation($organisation->id)) {
            throw new RuntimeException(
                "User {$user->id} does not belong to organisation {$organisation->id}"
            );
        }

        $this->currentUser = $user;
        $this->currentOrganisation = $organisation;
        
        session(['current_organisation_id' => $organisation->id]);
    }

    public function getCurrentOrganisation(): Organisation
    {
        if (!$this->currentOrganisation) {
            $this->resolveFromSession();
        }

        if (!$this->currentOrganisation) {
            throw new RuntimeException('No tenant context set');
        }

        return $this->currentOrganisation;
    }

    public function getCurrentOrganisationId(): string
    {
        return $this->getCurrentOrganisation()->id;
    }

    public function getCurrentUser(): User
    {
        if (!$this->currentUser) {
            throw new RuntimeException('No user context set');
        }

        return $this->currentUser;
    }

    public function clear(): void
    {
        $this->currentOrganisation = null;
        $this->currentUser = null;
        session()->forget('current_organisation_id');
    }

    private function resolveFromSession(): void
    {
        $organisationId = session('current_organisation_id');
        
        if (!$organisationId) {
            return;
        }

        $organisation = Organisation::find($organisationId);
        
        if ($organisation) {
            $this->currentOrganisation = $organisation;
        }
    }

    public function isPlatformContext(): bool
    {
        return $this->getCurrentOrganisation()->isPlatform();
    }

    public function isTenantContext(): bool
    {
        return $this->getCurrentOrganisation()->isTenant();
    }
}
```

## 4. Middleware

```php
<?php
// app/Http/Middleware/TenantMiddleware.php

namespace App\Http\Middleware;

use App\Services\TenantContext;
use App\Models\Organisation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function __construct(
        private readonly TenantContext $tenantContext
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return $next($request);
        }

        // Try to get organisation from route parameter
        $organisationId = $request->route('organisationId') ?? 
                         $request->route('organisation')?->id ??
                         $request->input('organisation_id');

        if ($organisationId) {
            $organisation = Organisation::find($organisationId);
            
            if (!$organisation) {
                abort(404, 'Organisation not found');
            }

            if (!$user->belongsToOrganisation($organisation->id)) {
                abort(403, 'You do not have access to this organisation');
            }

            $this->tenantContext->setContext($user, $organisation);
            
            // Update user's current organisation if changed
            if ($user->organisation_id !== $organisation->id) {
                $user->update(['organisation_id' => $organisation->id]);
            }
        } elseif ($user->organisation_id) {
            // Use user's current organisation
            $organisation = Organisation::find($user->organisation_id);
            
            if ($organisation && $user->belongsToOrganisation($organisation->id)) {
                $this->tenantContext->setContext($user, $organisation);
            }
        }

        return $next($request);
    }
}
```

Register in Kernel:
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\TenantMiddleware::class,
    ],
    'api' => [
        // ... other middleware
        \App\Http\Middleware\TenantMiddleware::class,
    ],
];

protected $routeMiddleware = [
    // ... other middleware
    'tenant' => \App\Http\Middleware\TenantMiddleware::class,
];
```

## 5. Registration Flow

```php
<?php
// app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use App\Services\TenantContext;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly TenantContext $tenantContext
    ) {}

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Get platform organisation
        $platformOrg = Organisation::getDefaultPlatform();
        
        if (!$platformOrg) {
            throw new \RuntimeException('Platform organisation not configured');
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organisation_id' => $platformOrg->id, // Initially assigned to platform
        ]);

        // Create pivot record for platform membership
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $platformOrg->id,
            'role' => 'member',
        ]);

        event(new Registered($user));

        // Set tenant context
        $this->tenantContext->setContext($user, $platformOrg);

        return response()->json([
            'user' => $user,
            'message' => 'Registration successful'
        ]);
    }
}
```

## 6. Repository Pattern Example

```php
<?php
// app/Repositories/ElectionRepository.php

namespace App\Repositories;

use App\Models\Election;
use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ElectionRepository
{
    public function __construct(
        private readonly TenantContext $tenantContext
    ) {}

    public function find(string $id): ?Election
    {
        return Election::where('organisation_id', $this->tenantContext->getCurrentOrganisationId())
            ->where('id', $id)
            ->first();
    }

    public function findOrFail(string $id): Election
    {
        $election = Election::where('organisation_id', $this->tenantContext->getCurrentOrganisationId())
            ->where('id', $id)
            ->firstOrFail();
            
        return $election;
    }

    public function getAll(array $filters = []): Collection
    {
        $query = Election::where('organisation_id', $this->tenantContext->getCurrentOrganisationId());

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['from_date'])) {
            $query->where('starts_at', '>=', $filters['from_date']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Election::where('organisation_id', $this->tenantContext->getCurrentOrganisationId());

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Election
    {
        $data['organisation_id'] = $this->tenantContext->getCurrentOrganisationId();
        
        return Election::create($data);
    }

    public function update(string $id, array $data): Election
    {
        $election = $this->findOrFail($id);
        $election->update($data);
        
        return $election->fresh();
    }

    public function delete(string $id): bool
    {
        $election = $this->findOrFail($id);
        
        return $election->delete();
    }

    public function getActiveElections(): Collection
    {
        return Election::where('organisation_id', $this->tenantContext->getCurrentOrganisationId())
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->get();
    }
}
```

## 7. Testing

### Test Factories
```php
<?php
// database/factories/OrganisationFactory.php

namespace Database\Factories;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganisationFactory extends Factory
{
    protected $model = Organisation::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'slug' => fn(array $attributes) => Str::slug($attributes['name']),
            'type' => 'tenant',
            'is_default' => false,
            'settings' => [],
        ];
    }

    public function platform(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'platform',
        ]);
    }

    public function default(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_default' => true,
        ]);
    }
}
```

```php
<?php
// database/factories/UserFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'organisation_id' => Organisation::factory(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function forOrganisation(Organisation $organisation): static
    {
        return $this->state(fn(array $attributes) => [
            'organisation_id' => $organisation->id,
        ]);
    }
}
```

### Tenant Isolation Test
```php
<?php
// tests/Feature/TenantIsolationTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $platformOrg;
    private Organisation $tenant1;
    private Organisation $tenant2;
    private User $user1;
    private User $user2;
    private TenantContext $tenantContext;

    protected function setUp(): void
    {
        parent::setUp();

        // Create platform organisation
        $this->platformOrg = Organisation::factory()
            ->platform()
            ->default()
            ->create(['name' => 'Platform']);

        // Create two tenant organisations
        $this->tenant1 = Organisation::factory()->create(['name' => 'Tenant 1']);
        $this->tenant2 = Organisation::factory()->create(['name' => 'Tenant 2']);

        // Create users with memberships
        $this->user1 = User::factory()
            ->forOrganisation($this->tenant1)
            ->create();

        $this->user1->organisations()->attach($this->tenant1->id, ['role' => 'admin']);

        $this->user2 = User::factory()
            ->forOrganisation($this->tenant2)
            ->create();

        $this->user2->organisations()->attach($this->tenant2->id, ['role' => 'member']);

        $this->tenantContext = app(TenantContext::class);
    }

    /** @test */
    public function user_cannot_access_other_tenant_data()
    {
        // Create election in tenant1
        $election = Election::factory()
            ->forOrganisation($this->tenant1)
            ->create(['title' => 'Tenant 1 Election']);

        // Act as user2 (belongs to tenant2)
        $this->actingAs($this->user2);
        $this->tenantContext->setContext($this->user2, $this->tenant2);

        // Attempt to access tenant1's election
        $response = $this->getJson("/api/elections/{$election->id}");

        // Should fail with 404 (not found, not 403 to avoid information leak)
        $response->assertStatus(404);
    }

    /** @test */
    public function user_can_access_own_tenant_data()
    {
        // Create election in tenant1
        $election = Election::factory()
            ->forOrganisation($this->tenant1)
            ->create(['title' => 'Tenant 1 Election']);

        // Act as user1 (belongs to tenant1)
        $this->actingAs($this->user1);
        $this->tenantContext->setContext($this->user1, $this->tenant1);

        // Access tenant1's election
        $response = $this->getJson("/api/elections/{$election->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.title', 'Tenant 1 Election');
    }

    /** @test */
    public function platform_user_can_access_all_tenants()
    {
        // Create platform admin user
        $platformUser = User::factory()
            ->forOrganisation($this->platformOrg)
            ->create();

        $platformUser->organisations()->attach($this->platformOrg->id, ['role' => 'admin']);

        // Create elections in both tenants
        Election::factory()
            ->forOrganisation($this->tenant1)
            ->create(['title' => 'Election 1']);

        Election::factory()
            ->forOrganisation($this->tenant2)
            ->create(['title' => 'Election 2']);

        // Act as platform user in platform context
        $this->actingAs($platformUser);
        $this->tenantContext->setContext($platformUser, $this->platformOrg);

        // Platform should see all elections (special platform logic)
        $response = $this->getJson('/api/admin/elections');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function query_without_tenant_context_fails_safely()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No tenant context set');

        // Attempt repository operation without context
        $repository = app(\App\Repositories\ElectionRepository::class);
        $repository->getAll();
    }

    /** @test */
    public function user_cannot_switch_to_unauthorised_organisation()
    {
        $this->actingAs($this->user1);

        // Attempt to switch to tenant2 (user1 doesn't belong here)
        $response = $this->postJson('/api/switch-organisation', [
            'organisation_id' => $this->tenant2->id
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_switch_to_authorised_organisation()
    {
        // Add user1 to tenant2 as well
        $this->user1->organisations()->attach($this->tenant2->id, ['role' => 'member']);

        $this->actingAs($this->user1);

        // Switch to tenant2
        $response = $this->postJson('/api/switch-organisation', [
            'organisation_id' => $this->tenant2->id
        ]);

        $response->assertStatus(200);

        // Verify context changed
        $this->assertEquals(
            $this->tenant2->id,
            $this->tenantContext->getCurrentOrganisationId()
        );

        // Verify user's current organisation updated
        $this->assertEquals(
            $this->tenant2->id,
            $this->user1->fresh()->organisation_id
        );
    }
}
```

## 8. Database Seeder

```php
<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create platform organisation
        $platform = Organisation::create([
            'name' => 'Public Digit Platform',
            'slug' => 'platform',
            'type' => 'platform',
            'is_default' => true,
            'settings' => [
                'allow_demo' => true,
                'max_tenants' => 1000,
            ],
        ]);

        // Create platform admin
        $admin = User::create([
            'name' => 'Platform Admin',
            'email' => 'admin@publicdigit.com',
            'password' => bcrypt('password'),
            'organisation_id' => $platform->id,
        ]);

        UserOrganisationRole::create([
            'user_id' => $admin->id,
            'organisation_id' => $platform->id,
            'role' => 'super-admin',
        ]);

        // Create demo tenant
        $demo = Organisation::create([
            'name' => 'Demo Organisation',
            'slug' => 'demo',
            'type' => 'tenant',
            'is_default' => false,
            'settings' => [
                'is_demo' => true,
                'max_members' => 10,
            ],
        ]);

        // Create demo user
        $demoUser = User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
            'organisation_id' => $demo->id,
        ]);

        UserOrganisationRole::create([
            'user_id' => $demoUser->id,
            'organisation_id' => $demo->id,
            'role' => 'admin',
        ]);

        // Add demo user to platform as member
        UserOrganisationRole::create([
            'user_id' => $demoUser->id,
            'organisation_id' => $platform->id,
            'role' => 'member',
        ]);
    }
}
```

This complete implementation provides:
- **UUID primary keys** throughout
- **Proper foreign key constraints**
- **Tenant context service** for explicit tenant handling
- **Middleware** for automatic tenant resolution
- **Repository pattern** with tenant scoping
- **Comprehensive tests** for isolation
- **Seeder** for platform setup

The system is now ready for development with proper multi-tenancy from day one.
## Complete UUID Multi-Tenancy Architecture Design

Based on your architecture document and choice for **Option 1 (Exact Implementation)**, here's the complete design presented in sections for your approval.

---

### Section 1: Database Schema

**Core Changes:**

| Table | Current (Integer) | New (UUID) |
|-------|-------------------|------------|
| organisations | `id` (bigint) | `id` (uuid) primary |
| | | `type` enum('platform','tenant') |
| | | `is_default` boolean |
| users | `id` (bigint) | `id` (uuid) primary |
| | `organisation_id` (bigint) | `organisation_id` (uuid) FK |
| user_organisation_roles | `id` (bigint) | `id` (uuid) primary |
| | `user_id` (bigint) | `user_id` (uuid) FK |
| | `organisation_id` (bigint) | `organisation_id` (uuid) FK |
| elections | `id` (bigint) | `id` (uuid) primary |
| | `organisation_id` (bigint) | `organisation_id` (uuid) FK |
| *All other tables* | *integer IDs* | *UUIDs* |

**Key Constraints:**
```sql
-- Ensure only one default platform organisation
organisations_unique_default_platform: unique(['type', 'is_default'])

-- Prevent duplicate memberships
user_org_unique: unique(['user_id', 'organisation_id'])
```

**Indexing Strategy:**
- All foreign keys indexed
- Composite indexes on frequently queried tenant columns
- Maintain existing query performance with UUIDs

**✅ Section 1 Status: Ready for Approval**

---

### Section 2: Models Layer

**Base Model Changes:**
```php
// All tenant-aware models use:
use Illuminate\Database\Eloquent\Concerns\HasUuids;

protected $keyType = 'string';
public $incrementing = false;
```

**Key Model Relationships:**

| Model | Relationships |
|-------|---------------|
| Organisation | `users()` (belongsToMany via pivot) |
| | `elections()` (hasMany) |
| | `roles()` (hasMany) |
| User | `currentOrganisation()` (belongsTo) |
| | `organisations()` (belongsToMany via pivot) |
| | `organisationRoles()` (hasMany) |
| Election | `organisation()` (belongsTo) |
| | Scopes: `forOrganisation()` |

**Key Methods:**
```php
// Organisation
isPlatform(): bool
isTenant(): bool
static getDefaultPlatform(): ?self

// User
belongsToOrganisation(string $organisationId): bool
getRoleInOrganisation(string $organisationId): ?string
```

**✅ Section 2 Status: Ready for Approval**

---

### Section 3: TenantContext Service

**Core Service:**
```php
class TenantContext
{
    // Manages current organisation in memory/session
    setContext(User $user, Organisation $org): void
    getCurrentOrganisation(): Organisation
    getCurrentOrganisationId(): string
    getCurrentUser(): User
    clear(): void
    isPlatformContext(): bool
    isTenantContext(): bool
}
```

**Features:**
- Session persistence for current organisation
- Automatic resolution from session
- Validation that user belongs to organisation
- Type-safe context methods

**✅ Section 3 Status: Ready for Approval**

---

### Section 4: Middleware

**Tenant Middleware:**
```php
class TenantMiddleware
{
    handle(Request $request, Closure $next)
    {
        // 1. Get authenticated user
        // 2. Resolve organisation from route/parameter
        // 3. Validate user membership
        // 4. Set TenantContext
        // 5. Update user's current organisation if needed
    }
}
```

**Registration:**
```php
// Kernel.php
'web' => [\App\Http\Middleware\TenantMiddleware::class]
'api' => [\App\Http\Middleware\TenantMiddleware::class]
'tenant' => \App\Http\Middleware\TenantMiddleware::class
```

**✅ Section 4 Status: Ready for Approval**

---

### Section 5: Repository Pattern

**Example Repository:**
```php
class ElectionRepository
{
    // All queries automatically scoped to current organisation
    find(string $id): ?Election
    findOrFail(string $id): Election
    getAll(array $filters = []): Collection
    paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    create(array $data): Election
    update(string $id, array $data): Election
    delete(string $id): bool
    getActiveElections(): Collection
}
```

**Key Feature:** Every method uses `TenantContext::getCurrentOrganisationId()` for implicit scoping

**✅ Section 5 Status: Ready for Approval**

---

### Section 6: Registration Flow

**New User Registration:**
```php
RegisteredUserController@store
{
    // 1. Get default platform organisation
    $platformOrg = Organisation::getDefaultPlatform();
    
    // 2. Create user with platform organisation_id
    $user = User::create([..., 'organisation_id' => $platformOrg->id]);
    
    // 3. Create pivot record for platform membership
    UserOrganisationRole::create([
        'user_id' => $user->id,
        'organisation_id' => $platformOrg->id,
        'role' => 'member'
    ]);
    
    // 4. Set tenant context to platform
    $tenantContext->setContext($user, $platformOrg);
}
```

**✅ Section 6 Status: Ready for Approval**

---

### Section 7: Testing Strategy

**Factory Updates:**
```php
// All factories generate UUIDs automatically via HasUuids
OrganisationFactory::platform()->default()
UserFactory::forOrganisation($organisation)
ElectionFactory::forOrganisation($organisation)
```

**Key Test Cases:**
```php
TenantIsolationTest
{
    test_user_cannot_access_other_tenant_data()
    test_user_can_access_own_tenant_data()
    test_platform_user_can_access_all_tenants()
    test_query_without_tenant_context_fails_safely()
    test_user_cannot_switch_to_unauthorised_organisation()
    test_user_can_switch_to_authorised_organisation()
}
```

**✅ Section 7 Status: Ready for Approval**

---

### Section 8: Database Seeder

**Initial Data:**
```php
DatabaseSeeder
{
    // 1. Create platform organisation (type='platform', is_default=true)
    // 2. Create platform admin user with super-admin role
    // 3. Create demo tenant organisation
    // 4. Create demo user with admin role in demo tenant
    // 5. Add demo user as member of platform
}
```

**✅ Section 8 Status: Ready for Approval**

---

### Section 9: Implementation Timeline

| Phase | Duration | Deliverables |
|-------|----------|--------------|
| 1. Database Migrations | 4 hours | All migration files with UUID schema |
| 2. Models & Relationships | 3 hours | All models updated with HasUuids |
| 3. TenantContext Service | 2 hours | Service class with tests |
| 4. Middleware | 1 hour | Tenant middleware with tests |
| 5. Repositories | 3 hours | Example repositories for key models |
| 6. Registration Flow | 2 hours | Updated registration controller |
| 7. Testing | 4 hours | Factories and isolation tests |
| 8. Seeding | 1 hour | Database seeders |
| **Total** | **20 hours** | **Complete UUID implementation** |

**✅ Section 9 Status: Ready for Approval**

---

## Summary: What Changes

| Component | From | To |
|-----------|------|-----|
| Primary Keys | Auto-increment integers | UUIDs |
| Foreign Keys | Integer references | UUID references |
| Tenant Trait | BelongsToTenant | TenantContext service |
| Query Scoping | Implicit trait | Explicit context + repositories |
| Platform Org | ID=1 assumption | Resolved via type='platform', is_default=true |
| Testing | Integer-based factories | UUID factories |

---

## Next Steps

Please approve each section (1-9) so I can generate the complete implementation code.

**Which section would you like to start with?**
