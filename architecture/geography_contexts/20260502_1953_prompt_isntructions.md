## Claude Code CLI Prompt Instructions — Implement Geography Contexts

Based on the entire discussion and the three architectural reviews, here are the complete instructions for Claude Code CLI.

---

```markdown
## Task: Implement Geographic Context for Multi-Tenant Committee Management

### Project Location
`C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu`

### Context
You have already copied the Geography Context from the old project to:
`app/Contexts/Geography/`

The copied code contains:
- GeographyMirrorService (mirrors official geography to tenants)
- 0-10 level hierarchy support
- PostgreSQL ltree path storage
- Continent support (Level 0)

### Your Mission
Extend and integrate the Geographic Context to support:
1. **Multi-organisation committee hierarchy** (committees mapped to geographic units)
2. **Member address storage** with primary residence + cross-boundary assignments
3. **Geographic permission boundaries** (committees can only manage members in their area)
4. **Committee dashboards** with precomputed aggregates
5. **Newsletter system** with recipient snapshots for audit
6. **Audit logging** for all permission-sensitive actions

### Architectural Principles (From Reviews)

1. **Geography is NOT the source of truth for permissions** - Use separate authorization layer
2. **Cross-boundary members** - Use `member_geo_assignments` table, not single `geo_path`
3. **Performance at scale** - Use `geo_closure` table for fast subtree queries, not recursive CTEs
4. **Newsletter snapshots** - Store recipient lists at send time for auditability
5. **Audit logging** - Track all permission-sensitive actions
6. **Optional geography** - Members can be added without geography (fast onboarding)

---

## Phase 1: Database Schema (Execute First)

### Step 1.1: Create Required Tables

Create migration files in order:

**Migration 1:** `database/migrations/2026_01_15_000001_create_geo_closure_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('landlord')->create('geo_closure', function (Blueprint $table) {
            $table->unsignedBigInteger('ancestor_id');
            $table->unsignedBigInteger('descendant_id');
            $table->integer('depth')->default(0);
            $table->primary(['ancestor_id', 'descendant_id']);
            $table->foreign('ancestor_id')->references('id')->on('geo_administrative_units')->onDelete('cascade');
            $table->foreign('descendant_id')->references('id')->on('geo_administrative_units')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('geo_closure');
    }
};
```

**Migration 2:** `database/migrations/2026_01_15_000002_create_member_geo_assignments_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_geo_assignments', function (Blueprint $table) {
            $table->id();
            $table->uuid('member_id');
            $table->unsignedBigInteger('geo_unit_id');
            $table->string('relationship_type')->default('primary_residence');
            $table->boolean('is_primary')->default(false);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();
            
            $table->foreign('geo_unit_id')->references('id')->on('geo_administrative_units')->onDelete('cascade');
            $table->index(['member_id', 'geo_unit_id']);
            $table->index(['geo_unit_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_geo_assignments');
    }
};
```

**Migration 3:** `database/migrations/2026_01_15_000003_create_committees_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->unsignedBigInteger('geo_unit_id')->nullable();
            $table->uuid('parent_committee_id')->nullable();
            $table->integer('level')->default(1);
            $table->string('name');
            $table->json('name_local')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('geo_unit_id')->references('id')->on('geo_administrative_units')->onDelete('set null');
            $table->foreign('parent_committee_id')->references('id')->on('committees')->onDelete('cascade');
            $table->index(['organisation_id', 'level']);
            $table->index(['geo_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
```

**Migration 4:** `database/migrations/2026_01_15_000004_create_member_committees_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_committees', function (Blueprint $table) {
            $table->uuid('member_id');
            $table->uuid('committee_id');
            $table->string('role')->nullable();
            $table->date('joined_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->primary(['member_id', 'committee_id']);
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_committees');
    }
};
```

**Migration 5:** `database/migrations/2026_01_15_000005_create_committee_effective_permissions_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_effective_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('committee_id');
            $table->string('resource_type'); // 'member', 'finance', 'newsletter'
            $table->string('action'); // 'create', 'read', 'update', 'delete', 'send'
            $table->unsignedBigInteger('scope_geo_unit_id')->nullable();
            $table->string('granted_to_role')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
            $table->foreign('scope_geo_unit_id')->references('id')->on('geo_administrative_units')->onDelete('set null');
            $table->index(['committee_id', 'resource_type', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_effective_permissions');
    }
};
```

**Migration 6:** `database/migrations/2026_01_15_000006_create_newsletter_recipient_snapshots_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_recipient_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('newsletter_id');
            $table->uuid('member_id');
            $table->string('geo_context_path')->nullable();
            $table->timestamp('permission_checked_at')->nullable();
            $table->boolean('was_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['newsletter_id', 'member_id']);
            $table->index(['member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_recipient_snapshots');
    }
};
```

**Migration 7:** `database/migrations/2026_01_15_000007_create_audit_log_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->uuid('actor_id')->nullable();
            $table->string('action');
            $table->string('target_type');
            $table->string('target_id');
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->json('geo_context')->nullable();
            $table->timestamps();
            
            $table->index(['target_type', 'target_id']);
            $table->index(['actor_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
```

**Migration 8:** `database/migrations/2026_01_15_000008_add_geography_to_members_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_geo_unit_id')->nullable()->after('email');
            $table->foreign('primary_geo_unit_id')->references('id')->on('geo_administrative_units')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['primary_geo_unit_id']);
            $table->dropColumn('primary_geo_unit_id');
        });
    }
};
```

### Step 1.2: Run Migrations

```bash
php artisan migrate --database=landlord
php artisans tenants:artisan "migrate"
```

---

## Phase 2: Populate Geo Closure Table

### Step 2.1: Create Closure Populator Command

**File:** `app/Console/Commands/PopulateGeoClosure.php`

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateGeoClosure extends Command
{
    protected $signature = 'geo:populate-closure {--tenant= : Tenant slug}';
    protected $description = 'Populate geo_closure table for fast hierarchy queries';

    public function handle()
    {
        $connection = $this->option('tenant') ? 'tenant' : 'landlord';
        
        $this->info("Populating geo_closure for {$connection} connection...");
        
        // Clear existing
        DB::connection($connection)->table('geo_closure')->truncate();
        
        // Insert all self-references (depth 0)
        DB::connection($connection)->statement(
            "INSERT INTO geo_closure (ancestor_id, descendant_id, depth)
             SELECT id, id, 0 FROM geo_administrative_units"
        );
        
        // Insert ancestor-descendant relationships
        DB::connection($connection)->statement(
            "WITH RECURSIVE tree AS (
                SELECT id, parent_id, 0 as depth FROM geo_administrative_units WHERE parent_id IS NULL
                UNION ALL
                SELECT c.id, c.parent_id, t.depth + 1
                FROM geo_administrative_units c
                JOIN tree t ON c.parent_id = t.id
            )
            INSERT INTO geo_closure (ancestor_id, descendant_id, depth)
            SELECT t1.id, t2.id, t2.depth - t1.depth
            FROM geo_administrative_units t1
            CROSS JOIN LATERAL (
                SELECT id, depth FROM tree WHERE id IN (
                    WITH RECURSIVE descendants AS (
                        SELECT id FROM geo_administrative_units WHERE id = t1.id
                        UNION ALL
                        SELECT c.id FROM geo_administrative_units c
                        JOIN descendants d ON c.parent_id = d.id
                    )
                    SELECT id FROM descendants
                )
            ) t2
            WHERE t1.id != t2.id"
        );
        
        $count = DB::connection($connection)->table('geo_closure')->count();
        $this->info("✅ Populated {$count} closure records");
    }
}
```

### Step 2.2: Run Populator

```bash
php artisan geo:populate-closure
php artisan tenants:artisan "geo:populate-closure" --all
```

---

## Phase 3: Domain Models

### Step 3.1: Create Committee Model

**File:** `app/Models/Committee.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Committee extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id', 'organisation_id', 'geo_unit_id', 'parent_committee_id',
        'level', 'name', 'name_local', 'is_active'
    ];
    
    protected $casts = [
        'name_local' => 'array',
        'is_active' => 'boolean',
    ];
    
    public function geoUnit(): BelongsTo
    {
        return $this->belongsTo(GeoAdministrativeUnit::class, 'geo_unit_id');
    }
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Committee::class, 'parent_committee_id');
    }
    
    public function children(): HasMany
    {
        return $this->hasMany(Committee::class, 'parent_committee_id');
    }
    
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_committees')
            ->withPivot('role', 'joined_at', 'is_active')
            ->withTimestamps();
    }
    
    public function getAllDescendantGeoUnits(): array
    {
        return DB::table('geo_closure')
            ->where('ancestor_id', $this->geo_unit_id)
            ->pluck('descendant_id')
            ->toArray();
    }
}
```

### Step 3.2: Create MemberGeoAssignment Model

**File:** `app/Models/MemberGeoAssignment.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberGeoAssignment extends Model
{
    protected $table = 'member_geo_assignments';
    
    protected $fillable = [
        'member_id', 'geo_unit_id', 'relationship_type', 
        'is_primary', 'valid_from', 'valid_to'
    ];
    
    protected $casts = [
        'is_primary' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    
    public function geoUnit()
    {
        return $this->belongsTo(GeoAdministrativeUnit::class, 'geo_unit_id');
    }
}
```

### Step 3.3: Update Member Model

**File:** `app/Models/Member.php` (add these relationships)

```php
public function primaryGeoUnit(): BelongsTo
{
    return $this->belongsTo(GeoAdministrativeUnit::class, 'primary_geo_unit_id');
}

public function geoAssignments(): HasMany
{
    return $this->hasMany(MemberGeoAssignment::class);
}

public function committees(): BelongsToMany
{
    return $this->belongsToMany(Committee::class, 'member_committees')
        ->withPivot('role', 'joined_at', 'is_active')
        ->withTimestamps();
}

public function getPrimaryGeoPath(): ?string
{
    if (!$this->primary_geo_unit_id) {
        return null;
    }
    
    return DB::table('geo_administrative_units')
        ->where('id', $this->primary_geo_unit_id)
        ->value('path');
}
```

---

## Phase 4: Permission Service

### Step 4.1: Create Permission Service

**File:** `app/Services/GeographicPermissionService.php`

```php
<?php

namespace App\Services;

use App\Models\Committee;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class GeographicPermissionService
{
    /**
     * Get all members a committee can access (including descendants)
     */
    public function getAccessibleMembers(Committee $committee, Member $actor): array
    {
        // Check if actor has permission
        if (!$this->canViewMembers($committee, $actor)) {
            return [];
        }
        
        // Get all descendant geo units
        $descendantGeoIds = DB::table('geo_closure')
            ->where('ancestor_id', $committee->geo_unit_id)
            ->pluck('descendant_id')
            ->toArray();
        
        // Get members via primary residence OR geo assignments
        return Member::where(function($q) use ($descendantGeoIds) {
                $q->whereIn('primary_geo_unit_id', $descendantGeoIds)
                  ->orWhereHas('geoAssignments', function($sq) use ($descendantGeoIds) {
                      $sq->whereIn('geo_unit_id', $descendantGeoIds);
                  });
            })
            ->get()
            ->toArray();
    }
    
    /**
     * Check if actor can send newsletter to target geography
     */
    public function canSendNewsletter(Committee $committee, Member $actor, ?int $targetGeoUnitId = null): bool
    {
        $permission = $this->getEffectivePermission($committee, 'newsletter', 'send');
        
        if (!$permission) {
            return false;
        }
        
        if (!$targetGeoUnitId) {
            return true; // Send to committee's entire scope
        }
        
        // Check if target is within committee's geographic scope
        return DB::table('geo_closure')
            ->where('ancestor_id', $committee->geo_unit_id)
            ->where('descendant_id', $targetGeoUnitId)
            ->exists();
    }
    
    /**
     * Get effective permission for a committee
     */
    private function getEffectivePermission(Committee $committee, string $resourceType, string $action): ?object
    {
        $cacheKey = "committee.perm.{$committee->id}.{$resourceType}.{$action}";
        
        return Cache::remember($cacheKey, 3600, function() use ($committee, $resourceType, $action) {
            return DB::table('committee_effective_permissions')
                ->where('committee_id', $committee->id)
                ->where('resource_type', $resourceType)
                ->where('action', $action)
                ->where(function($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->first();
        });
    }
    
    private function canViewMembers(Committee $committee, Member $actor): bool
    {
        // Check if actor is office bearer with permission
        $officeBearer = DB::table('committee_office_bearers')
            ->where('committee_id', $committee->id)
            ->where('member_id', $actor->id)
            ->where('is_current', true)
            ->first();
            
        if (!$officeBearer) {
            return false;
        }
        
        // Check if office bearer role has member view permission
        return DB::table('committee_effective_permissions')
            ->where('committee_id', $committee->id)
            ->where('granted_to_role', $officeBearer->position)
            ->where('resource_type', 'member')
            ->where('action', 'read')
            ->exists();
    }
}
```

---

## Phase 5: Committee Dashboard Controller

### Step 5.1: Create Dashboard Controller

**File:** `app/Http/Controllers/CommitteeDashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Services\GeographicPermissionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommitteeDashboardController extends Controller
{
    public function __construct(
        private GeographicPermissionService $permissionService
    ) {}
    
    public function index(Committee $committee)
    {
        $this->authorize('view', $committee);
        
        $user = auth()->user();
        $member = $user->member;
        
        $accessibleMembers = $this->permissionService->getAccessibleMembers($committee, $member);
        
        return Inertia::render('Committee/Dashboard', [
            'committee' => $committee->load(['geoUnit', 'parent', 'children']),
            'stats' => $this->getStats($committee),
            'recentMembers' => array_slice($accessibleMembers, 0, 10),
            'canSendNewsletter' => $this->permissionService->canSendNewsletter($committee, $member),
        ]);
    }
    
    private function getStats(Committee $committee): array
    {
        // Use precomputed aggregates or cache
        return Cache::remember("committee.stats.{$committee->id}", 300, function() use ($committee) {
            $descendantGeoIds = DB::table('geo_closure')
                ->where('ancestor_id', $committee->geo_unit_id)
                ->pluck('descendant_id')
                ->toArray();
            
            return [
                'total_members' => Member::whereIn('primary_geo_unit_id', $descendantGeoIds)->count(),
                'active_members' => Member::whereIn('primary_geo_unit_id', $descendantGeoIds)
                    ->where('membership_status', 'active')
                    ->count(),
                'committee_count' => Committee::where('parent_committee_id', $committee->id)->count(),
            ];
        });
    }
}
```

### Step 5.2: Add Routes

**File:** `routes/web.php`

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/committee/{committee}/dashboard', [CommitteeDashboardController::class, 'index'])
        ->name('committee.dashboard');
    Route::get('/committee/{committee}/members', [CommitteeMemberController::class, 'index'])
        ->name('committee.members');
    Route::post('/committee/{committee}/newsletter', [CommitteeNewsletterController::class, 'send'])
        ->name('committee.newsletter.send');
});
```

---

## Phase 6: Newsletter System with Snapshots

### Step 6.1: Create Newsletter Controller

**File:** `app/Http/Controllers/CommitteeNewsletterController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Services\GeographicPermissionService;
use App\Jobs\SendCommitteeNewsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommitteeNewsletterController extends Controller
{
    public function send(Request $request, Committee $committee, GeographicPermissionService $permissionService)
    {
        $user = auth()->user();
        $member = $user->member;
        
        // Verify permission
        if (!$permissionService->canSendNewsletter($committee, $member)) {
            abort(403, 'You do not have permission to send newsletters for this committee.');
        }
        
        $validated = $request->validate([
            'subject' => 'required|string|max:500',
            'content' => 'required|string',
            'target_geo_unit_id' => 'nullable|exists:geo_administrative_units,id',
        ]);
        
        // Compute allowed recipients based on geographic scope
        $targetGeoId = $validated['target_geo_unit_id'] ?? $committee->geo_unit_id;
        
        // Verify target is within committee's scope
        if (!$permissionService->canSendNewsletter($committee, $member, $targetGeoId)) {
            abort(403, 'Target geography is outside your committee\'s scope.');
        }
        
        // Get recipient list
        $recipientIds = $this->getRecipientsInGeoScope($targetGeoId);
        
        // Create newsletter record
        $newsletterId = DB::table('committee_newsletters')->insertGetId([
            'committee_id' => $committee->id,
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'target_geo_unit_id' => $targetGeoId,
            'target_member_count' => count($recipientIds),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create recipient snapshots (for audit)
        $snapshots = [];
        foreach ($recipientIds as $recipientId) {
            $snapshots[] = [
                'newsletter_id' => $newsletterId,
                'member_id' => $recipientId,
                'permission_checked_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('newsletter_recipient_snapshots')->insert($snapshots);
        
        // Dispatch job
        SendCommitteeNewsletter::dispatch($newsletterId);
        
        // Log audit
        Log::info('Newsletter queued', [
            'committee_id' => $committee->id,
            'actor_id' => $user->id,
            'recipient_count' => count($recipientIds),
            'geo_scope' => $targetGeoId,
        ]);
        
        return back()->with('success', "Newsletter queued for {$newsletterId}. Sending to {$newsletterId} recipients.");
    }
    
    private function getRecipientsInGeoScope(int $geoUnitId): array
    {
        $descendantIds = DB::table('geo_closure')
            ->where('ancestor_id', $geoUnitId)
            ->pluck('descendant_id')
            ->toArray();
        
        return Member::whereIn('primary_geo_unit_id', $descendantIds)
            ->where('membership_status', 'active')
            ->pluck('id')
            ->toArray();
    }
}
```

---

## Phase 7: Audit Trail Service

### Step 7.1: Create Audit Service

**File:** `app/Services/AuditService.php`

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditService
{
    public static function log(string $action, string $targetType, string $targetId, array $oldValue = null, array $newValue = null, array $geoContext = null): void
    {
        DB::table('audit_log')->insert([
            'actor_id' => Auth::id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'old_value' => $oldValue ? json_encode($oldValue) : null,
            'new_value' => $newValue ? json_encode($newValue) : null,
            'geo_context' => $geoContext ? json_encode($geoContext) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
```

---

## Verification Checklist

After implementation, verify:

```bash
# Run migrations
php artisan migrate --database=landlord
php artisan tenants:artisan "migrate"

# Populate closure table
php artisan geo:populate-closure
php artisan tenants:artisan "geo:populate-closure" --all

# Test committee creation
php artisan tinker
>>> $committee = Committee::create([...])

# Test member geo assignment
>>> MemberGeoAssignment::create([...])

# Test permission check
>>> app(GeographicPermissionService::class)->canSendNewsletter($committee, $member)

# Run tests
php artisan test
```

---

## Files Summary

| File | Purpose |
|------|---------|
| `database/migrations/...` | 8 migration files |
| `app/Console/Commands/PopulateGeoClosure.php` | Closure populator |
| `app/Models/Committee.php` | Committee model |
| `app/Models/MemberGeoAssignment.php` | Cross-boundary assignments |
| `app/Services/GeographicPermissionService.php` | Permission logic |
| `app/Http/Controllers/CommitteeDashboardController.php` | Dashboard |
| `app/Http/Controllers/CommitteeNewsletterController.php` | Newsletter |
| `app/Services/AuditService.php` | Audit logging |

---

Proceed with implementation. 🚀
```
In the old project we have two kinds of database, one landord database and one tenant database. that means landord is service provider and tenant is each organisation that wants to use the  Geography contexts etc.  So we have these files in the contexts including the migration. 

nabra@LAPTOP-5874DSDS MINGW64 ~/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu (postgressql)
$ find app/Contexts/Geography/
app/Contexts/Geography/
app/Contexts/Geography/Application
app/Contexts/Geography/Application/Jobs
app/Contexts/Geography/Application/Jobs/InstallGeographyModule.php
app/Contexts/Geography/Application/Services
app/Contexts/Geography/Application/Services/DailyGeographySync.php
app/Contexts/Geography/Application/Services/GeographyAntiCorruptionLayer.php
app/Contexts/Geography/Application/Services/GeographyCandidateService.php
app/Contexts/Geography/Application/Services/GeographyMirrorService.php
app/Contexts/Geography/Application/Services/GeographyService.php
app/Contexts/Geography/Domain
app/Contexts/Geography/Domain/Entities
app/Contexts/Geography/Domain/Entities/GeoAdministrativeUnit.php
app/Contexts/Geography/Domain/Exceptions
app/Contexts/Geography/Domain/Exceptions/CountryNotSupportedException.php
app/Contexts/Geography/Domain/Exceptions/InvalidHierarchyException.php
app/Contexts/Geography/Domain/Exceptions/InvalidParentChildException.php
app/Contexts/Geography/Domain/Exceptions/MaxHierarchyDepthException.php
app/Contexts/Geography/Domain/Exceptions/MissingRequiredLevelException.php
app/Contexts/Geography/Domain/Models
app/Contexts/Geography/Domain/Models/Country.php
app/Contexts/Geography/Domain/Models/GeoAdministrativeUnit.php
app/Contexts/Geography/Domain/Models/TenantGeographyProfile.php
app/Contexts/Geography/Domain/Repositories
app/Contexts/Geography/Domain/Repositories/FuzzyMatchingRepositoryInterface.php
app/Contexts/Geography/Domain/Repositories/GeoUnitRepositoryInterface.php
app/Contexts/Geography/Domain/Services
app/Contexts/Geography/Domain/Services/FuzzyMatchingService.php
app/Contexts/Geography/Domain/Services/GeographyPathService.php
app/Contexts/Geography/Domain/ValueObjects
app/Contexts/Geography/Domain/ValueObjects/CountryCode.php
app/Contexts/Geography/Domain/ValueObjects/GeographicCode.php
app/Contexts/Geography/Domain/ValueObjects/GeographyHierarchy.php
app/Contexts/Geography/Domain/ValueObjects/GeographyLevel.php
app/Contexts/Geography/Domain/ValueObjects/GeoPath.php
app/Contexts/Geography/Domain/ValueObjects/GeoUnitId.php
app/Contexts/Geography/Domain/ValueObjects/LocalizedName.php
app/Contexts/Geography/Domain/ValueObjects/MatchCategory.php
app/Contexts/Geography/Domain/ValueObjects/MatchResult.php
app/Contexts/Geography/Domain/ValueObjects/PotentialMatches.php
app/Contexts/Geography/Domain/ValueObjects/SimilarityScore.php
app/Contexts/Geography/Http
app/Contexts/Geography/Http/Controllers
app/Contexts/Geography/Http/Controllers/AdministrativeUnitController.php
app/Contexts/Geography/Http/Controllers/CountryController.php
app/Contexts/Geography/Http/Controllers/GeographyController.php
app/Contexts/Geography/Http/Requests
app/Contexts/Geography/Http/Requests/AdministrativeUnitRequest.php
app/Contexts/Geography/Http/Requests/GeographyHierarchyRequest.php
app/Contexts/Geography/Http/Resources
app/Contexts/Geography/Http/Resources/AdministrativeUnitResource.php
app/Contexts/Geography/Http/Resources/CountryResource.php
app/Contexts/Geography/Http/Resources/GeographyHierarchyResource.php
app/Contexts/Geography/Infrastructure
app/Contexts/Geography/Infrastructure/Database
app/Contexts/Geography/Infrastructure/Database/Migrations
app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord
app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/2025_01_01_000001_create_countries_table.php
app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/2025_01_01_000002_create_geo_administrative_units_table.php
app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/2026_01_14_000003_extend_level_range_add_continents.php
app/Contexts/Geography/Infrastructure/Database/Migrations/Neuer Ordner
app/Contexts/Geography/Infrastructure/Database/Migrations/Tenant
app/Contexts/Geography/Infrastructure/Database/Migrations/Tenant/2025_01_01_000001_create_geo_administrative_units_table.php
app/Contexts/Geography/Infrastructure/Database/Migrations/Tenant/2026_01_14_000001_extend_admin_level_range_to_0_10.php
app/Contexts/Geography/Infrastructure/Database/Seeders
app/Contexts/Geography/Infrastructure/Database/Seeders/CountriesSeeder.php
app/Contexts/Geography/Infrastructure/Database/Seeders/NepalGeographySeeder.php
app/Contexts/Geography/Infrastructure/Providers
app/Contexts/Geography/Infrastructure/Providers/GeographyServiceProvider.php
app/Contexts/Geography/Infrastructure/Repositories
app/Contexts/Geography/Infrastructure/Repositories/EloquentFuzzyMatchingRepository.php
app/Contexts/Geography/Infrastructure/Repositories/EloquentGeoUnitRepository.php
app/Contexts/Geography/Infrastructure/Services
app/Contexts/Geography/Infrastructure/Services/GeographyLookupService.php

nabra@LAPTOP-5874DSDS MINGW64 ~/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu (postgressql)
## Excellent — The Geographic Context Structure Is Already Complete

Your copied Geography Context already has:
- ✅ **Landlord migrations** (countries, geo_administrative_units, continents)
- ✅ **Tenant migrations** (mirrored geography)
- ✅ **Domain models** (GeoAdministrativeUnit, Country, TenantGeographyProfile)
- ✅ **Value objects** (CountryCode, GeographyLevel, GeoPath, etc.)
- ✅ **Services** (GeographyMirrorService, GeographyPathService, FuzzyMatchingService)
- ✅ **Repositories** (EloquentGeoUnitRepository)
- ✅ **Seeders** (CountriesSeeder, NepalGeographySeeder)
- ✅ **Service Provider** (GeographyServiceProvider)

---

## What Still Needs to Be Added

The copied code provides the **geography foundation**. Now you need to add the **committee management, permissions, member assignments, and dashboards** on top.

Here's the **Claude Code CLI prompt** to complete the implementation:

---

```markdown
## Task: Complete Geographic Context Implementation for Public Digit Platform

### Project Location
`C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu`

### Current State (Already Copied)
✅ Geography Context with landlord/tenant migrations
✅ GeoAdministrativeUnit model (0-10 levels support)
✅ GeographyMirrorService (mirrors to tenants)
✅ TenantGeographyProfile (diaspora, nepal_party, german_ngo)
✅ CountriesSeeder, NepalGeographySeeder
✅ GeographyServiceProvider registered

### What Needs to Be Added

---

## Phase 1: Add Missing Migrations (Tenant Database)

Create these migration files in `database/migrations/tenant/`:

### Migration 1: `2026_01_15_000001_create_committees_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->unsignedBigInteger('geo_unit_id')->nullable();
            $table->uuid('parent_committee_id')->nullable();
            $table->integer('level')->default(1);
            $table->string('name');
            $table->json('name_local')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['organisation_id', 'level']);
            $table->index(['geo_unit_id']);
            $table->foreign('geo_unit_id')->references('id')->on('geo_administrative_units')->onDelete('set null');
            $table->foreign('parent_committee_id')->references('id')->on('committees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
```

### Migration 2: `2026_01_15_000002_create_committee_office_bearers_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_office_bearers', function (Blueprint $table) {
            $table->id();
            $table->uuid('committee_id');
            $table->uuid('member_id');
            $table->string('position');
            $table->json('position_local')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->date('term_start');
            $table->date('term_end')->nullable();
            $table->boolean('is_current')->default(true);
            $table->json('permissions')->nullable();
            $table->timestamps();
            
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->index(['committee_id', 'is_current']);
            $table->unique(['committee_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_office_bearers');
    }
};
```

### Migration 3: `2026_01_15_000003_create_member_committees_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_committees', function (Blueprint $table) {
            $table->uuid('member_id');
            $table->uuid('committee_id');
            $table->string('role')->nullable();
            $table->date('joined_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->primary(['member_id', 'committee_id']);
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_committees');
    }
};
```

### Migration 4: `2026_01_15_000004_add_primary_geo_to_members_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_geo_unit_id')->nullable()->after('email');
            $table->foreign('primary_geo_unit_id')->references('id')->on('geo_administrative_units')->onDelete('set null');
            $table->index(['primary_geo_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['primary_geo_unit_id']);
            $table->dropColumn('primary_geo_unit_id');
        });
    }
};
```

### Migration 5: `2026_01_15_000005_create_geo_closure_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geo_closure', function (Blueprint $table) {
            $table->unsignedBigInteger('ancestor_id');
            $table->unsignedBigInteger('descendant_id');
            $table->integer('depth')->default(0);
            $table->primary(['ancestor_id', 'descendant_id']);
            $table->foreign('ancestor_id')->references('id')->on('geo_administrative_units')->onDelete('cascade');
            $table->foreign('descendant_id')->references('id')->on('geo_administrative_units')->onDelete('cascade');
        });
        
        // Create index for faster lookups
        Schema::table('geo_closure', function (Blueprint $table) {
            $table->index(['ancestor_id', 'depth']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geo_closure');
    }
};
```

---

## Phase 2: Create Console Command for Closure Population

**File:** `app/Console/Commands/PopulateGeoClosure.php`

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateGeoClosure extends Command
{
    protected $signature = 'geo:populate-closure {--tenant= : Tenant slug or --all for all tenants}';
    protected $description = 'Populate geo_closure table for fast hierarchy queries';

    public function handle()
    {
        $tenantOption = $this->option('tenant');
        
        if ($tenantOption === 'all') {
            $this->populateAllTenants();
            return;
        }
        
        if ($tenantOption) {
            $this->populateForTenant($tenantOption);
            return;
        }
        
        $this->populateForLandlord();
    }
    
    private function populateForLandlord(): void
    {
        $this->info('Populating geo_closure for landlord database...');
        $this->populate(DB::connection('landlord'));
        $this->info('✅ Landlord closure populated');
    }
    
    private function populateForTenant(string $slug): void
    {
        $this->info("Populating geo_closure for tenant: {$slug}...");
        tenancy()->initialize($slug);
        $this->populate(DB::connection('tenant'));
        $this->info("✅ Tenant {$slug} closure populated");
    }
    
    private function populateAllTenants(): void
    {
        $tenants = DB::connection('landlord')->table('tenants')->get();
        
        foreach ($tenants as $tenant) {
            $this->populateForTenant($tenant->slug);
        }
    }
    
    private function populate($connection): void
    {
        // Clear existing
        $connection->table('geo_closure')->truncate();
        
        // Insert self-references (depth 0)
        $connection->statement(
            "INSERT INTO geo_closure (ancestor_id, descendant_id, depth)
             SELECT id, id, 0 FROM geo_administrative_units"
        );
        
        // Insert ancestor-descendant relationships using recursive CTE
        $connection->statement(
            "WITH RECURSIVE unit_tree AS (
                SELECT 
                    id as root_id,
                    id as unit_id,
                    0 as depth
                FROM geo_administrative_units
                
                UNION ALL
                
                SELECT 
                    ut.root_id,
                    gu.id as unit_id,
                    ut.depth + 1
                FROM geo_administrative_units gu
                INNER JOIN unit_tree ut ON gu.parent_id = ut.unit_id
                WHERE ut.depth < 10
            )
            INSERT INTO geo_closure (ancestor_id, descendant_id, depth)
            SELECT DISTINCT root_id, unit_id, depth
            FROM unit_tree
            WHERE root_id != unit_id"
        );
        
        $count = $connection->table('geo_closure')->count();
        $this->info("  Inserted {$count} closure records");
    }
}
```

---

## Phase 3: Add Models for Committee Management

### File: `app/Models/Committee.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Committee extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'organisation_id', 'geo_unit_id', 'parent_committee_id',
        'level', 'name', 'name_local', 'is_active'
    ];
    
    protected $casts = [
        'name_local' => 'array',
        'is_active' => 'boolean',
    ];
    
    public function geoUnit()
    {
        return $this->belongsTo(GeoAdministrativeUnit::class, 'geo_unit_id');
    }
    
    public function parent()
    {
        return $this->belongsTo(Committee::class, 'parent_committee_id');
    }
    
    public function children()
    {
        return $this->hasMany(Committee::class, 'parent_committee_id');
    }
    
    public function officeBearers()
    {
        return $this->hasMany(CommitteeOfficeBearer::class);
    }
    
    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_committees')
            ->withPivot('role', 'joined_at', 'is_active')
            ->withTimestamps();
    }
}
```

### File: `app/Models/CommitteeOfficeBearer.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeOfficeBearer extends Model
{
    protected $fillable = [
        'committee_id', 'member_id', 'position', 'position_local',
        'is_primary', 'term_start', 'term_end', 'is_current', 'permissions'
    ];
    
    protected $casts = [
        'position_local' => 'array',
        'permissions' => 'array',
        'is_primary' => 'boolean',
        'is_current' => 'boolean',
        'term_start' => 'date',
        'term_end' => 'date',
    ];
    
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
```

### File: `app/Models/Member.php` (add these relationships)

```php
// Add to existing Member model
public function primaryGeoUnit()
{
    return $this->belongsTo(GeoAdministrativeUnit::class, 'primary_geo_unit_id');
}

public function committees()
{
    return $this->belongsToMany(Committee::class, 'member_committees')
        ->withPivot('role', 'joined_at', 'is_active')
        ->withTimestamps();
}

public function officeBearers()
{
    return $this->hasMany(CommitteeOfficeBearer::class);
}
```

---

## Phase 4: Create Permission Service

**File:** `app/Services/GeographicPermissionService.php`

```php
<?php

namespace App\Services;

use App\Models\Committee;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class GeographicPermissionService
{
    /**
     * Get all descendants of a geo unit using closure table
     */
    public function getDescendantGeoUnits(int $ancestorId): array
    {
        return DB::table('geo_closure')
            ->where('ancestor_id', $ancestorId)
            ->pluck('descendant_id')
            ->toArray();
    }
    
    /**
     * Get all members a committee can access (including descendants)
     */
    public function getAccessibleMembers(Committee $committee, Member $actor): array
    {
        if (!$this->canViewMembers($committee, $actor)) {
            return [];
        }
        
        $descendantGeoIds = $this->getDescendantGeoUnits($committee->geo_unit_id);
        
        return Member::whereIn('primary_geo_unit_id', $descendantGeoIds)
            ->orWhereHas('committees', function($q) use ($committee) {
                $q->where('committees.id', $committee->id);
            })
            ->get()
            ->toArray();
    }
    
    /**
     * Check if actor can send newsletter
     */
    public function canSendNewsletter(Committee $committee, Member $actor, ?int $targetGeoUnitId = null): bool
    {
        // Check if actor is an office bearer with newsletter permission
        $officeBearer = DB::table('committee_office_bearers')
            ->where('committee_id', $committee->id)
            ->where('member_id', $actor->id)
            ->where('is_current', true)
            ->first();
            
        if (!$officeBearer) {
            return false;
        }
        
        $permissions = is_string($officeBearer->permissions) 
            ? json_decode($officeBearer->permissions, true) 
            : $officeBearer->permissions;
            
        if (empty($permissions['can_send_newsletter'])) {
            return false;
        }
        
        if (!$targetGeoUnitId) {
            return true;
        }
        
        // Verify target is within committee's geographic scope
        return DB::table('geo_closure')
            ->where('ancestor_id', $committee->geo_unit_id)
            ->where('descendant_id', $targetGeoUnitId)
            ->exists();
    }
    
    private function canViewMembers(Committee $committee, Member $actor): bool
    {
        $officeBearer = DB::table('committee_office_bearers')
            ->where('committee_id', $committee->id)
            ->where('member_id', $actor->id)
            ->where('is_current', true)
            ->first();
            
        if (!$officeBearer) {
            return false;
        }
        
        $permissions = is_string($officeBearer->permissions) 
            ? json_decode($officeBearer->permissions, true) 
            : $officeBearer->permissions;
            
        return !empty($permissions['can_view_members']);
    }
}
```

---

## Phase 5: Create Committee Service Provider

**File:** `app/Providers/CommitteeServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GeographicPermissionService;

class CommitteeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GeographicPermissionService::class, function ($app) {
            return new GeographicPermissionService();
        });
    }
    
    public function boot(): void
    {
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\PopulateGeoClosure::class,
            ]);
        }
    }
}
```

Add to `config/app.php`:

```php
'providers' => [
    // ...
    App\Providers\CommitteeServiceProvider::class,
],
```

---

## Phase 6: Run Everything

```bash
# 1. Run tenant migrations
php artisan tenants:artisan "migrate"

# 2. Populate closure tables
php artisan geo:populate-closure
php artisan tenants:artisan "geo:populate-closure" --all

# 3. Clear cache
php artisan optimize:clear

# 4. Test
php artisan tinker
>>> $committee = Committee::first();
>>> $service = app(GeographicPermissionService::class);
>>> $service->getDescendantGeoUnits($committee->geo_unit_id);
```

---

## Summary of Files to Create

| File | Purpose |
|------|---------|
| `database/migrations/tenant/2026_01_15_000001_create_committees_table.php` | Committees table |
| `database/migrations/tenant/2026_01_15_000002_create_committee_office_bearers_table.php` | Office bearers |
| `database/migrations/tenant/2026_01_15_000003_create_member_committees_table.php` | Member-committee links |
| `database/migrations/tenant/2026_01_15_000004_add_primary_geo_to_members_table.php` | Member primary geo |
| `database/migrations/tenant/2026_01_15_000005_create_geo_closure_table.php` | Closure table for performance |
| `app/Console/Commands/PopulateGeoClosure.php` | Closure populator command |
| `app/Models/Committee.php` | Committee model |
| `app/Models/CommitteeOfficeBearer.php` | Office bearer model |
| `app/Services/GeographicPermissionService.php` | Permission service |
| `app/Providers/CommitteeServiceProvider.php` | Service provider |

---

Proceed with implementation. 🚀
```
## Good Clarification — Single Database with GlobalScope

You're right. Your project uses **single database** with `GlobalScope` for tenant isolation, not separate landlord/tenant databases.

---

## Simplified Approach for Your Architecture

| Original Plan (Multi-DB) | Your Project (Single DB + GlobalScope) |
|--------------------------|----------------------------------------|
| Landlord DB (geo master) | `geo_administrative_units` with `tenant_id` + GlobalScope |
| Tenant DB (mirrored) | Same table with tenant filtering |
| `tenants:artisan` commands | Standard Laravel migrations |
| Separate connections | Single connection + GlobalScope |

---

## Do You Need Full Membership Context Now?

**Answer: NO — Keep simple now, extend later.**

Here's the minimal viable approach for Phase 1:

---

## Minimal Implementation (Now)

### What You Actually Need

| Component | Needed Now? | Why |
|-----------|-------------|-----|
| Geographic units (0-10 levels) | ✅ Yes | Foundation |
| Geo closure table (performance) | ✅ Yes | Fast hierarchy queries |
| Committees mapped to geo units | ✅ Yes | Core structure |
| Committee office bearers | ✅ Yes | Who leads committees |
| Member primary geography | ✅ Yes | Where members live |
| Member-committee association | ✅ Yes | Which committee members belong to |
| Geographic permissions | ✅ Yes | Boundary-based access |
| Committee dashboard | ✅ Yes | Minimal view |
| **Full membership management** | ❌ **No** | Existing system already has members |
| **Newsletter system** | ❌ **No** | Phase 2 |
| **Finance tracking** | ❌ **No** | Phase 3 |
| **Audit logging** | ⏸️ **Optional** | Add later |

---

## Simplified Schema (Single Database with GlobalScope)

```sql
-- Geographic units (with tenant isolation)
CREATE TABLE geo_administrative_units (
    id BIGSERIAL PRIMARY KEY,
    tenant_id UUID NOT NULL,  -- GlobalScope filters this
    country_code CHAR(2),
    level SMALLINT CHECK (level BETWEEN 0 AND 10),
    parent_id BIGINT REFERENCES geo_administrative_units(id),
    path LTREE,  -- For fast hierarchy queries
    name JSONB,
    name_local JSONB,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Committee table (simple)
CREATE TABLE committees (
    id UUID PRIMARY KEY,
    tenant_id UUID NOT NULL,
    organisation_id UUID NOT NULL,
    geo_unit_id BIGINT REFERENCES geo_administrative_units(id),
    parent_committee_id UUID REFERENCES committees(id),
    name VARCHAR(255),
    level INT DEFAULT 1,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Committee office bearers (who leads)
CREATE TABLE committee_office_bearers (
    id BIGSERIAL PRIMARY KEY,
    committee_id UUID REFERENCES committees(id),
    member_id UUID REFERENCES members(id),
    position VARCHAR(100),
    is_primary BOOLEAN DEFAULT false,
    is_current BOOLEAN DEFAULT true,
    term_start DATE,
    term_end DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Member geography link (simple)
ALTER TABLE members ADD COLUMN primary_geo_unit_id BIGINT REFERENCES geo_administrative_units(id);

-- Member-committee association (simple)
CREATE TABLE member_committees (
    member_id UUID REFERENCES members(id),
    committee_id UUID REFERENCES committees(id),
    role VARCHAR(100),
    joined_at DATE,
    is_active BOOLEAN DEFAULT true,
    PRIMARY KEY (member_id, committee_id)
);

-- Geo closure table (performance)
CREATE TABLE geo_closure (
    ancestor_id BIGINT REFERENCES geo_administrative_units(id),
    descendant_id BIGINT REFERENCES geo_administrative_units(id),
    depth INT DEFAULT 0,
    PRIMARY KEY (ancestor_id, descendant_id)
);
```

---

## What to Do Now (Phase 1)

| Step | Action | Complexity |
|------|--------|------------|
| 1 | Use existing `geo_administrative_units` table | ✅ Already have |
| 2 | Add `geo_closure` table | Medium |
| 3 | Create `committees` table | Low |
| 4 | Create `committee_office_bearers` | Low |
| 5 | Add `primary_geo_unit_id` to members | Low |
| 6 | Create `member_committees` pivot | Low |
| 7 | Create populator command for closure | Medium |
| 8 | Create basic committee dashboard | Medium |

---

## Simplified Code (No Full Membership Context)

### Closure Populator (Simplified)

```php
// app/Console/Commands/PopulateGeoClosure.php
class PopulateGeoClosure extends Command
{
    public function handle()
    {
        // Clear existing
        DB::table('geo_closure')->truncate();
        
        // Insert self-references
        DB::statement(
            "INSERT INTO geo_closure (ancestor_id, descendant_id, depth)
             SELECT id, id, 0 FROM geo_administrative_units"
        );
        
        // Insert all ancestor-descendant relationships
        DB::statement(
            "WITH RECURSIVE tree AS (
                SELECT id, parent_id, 0 as depth 
                FROM geo_administrative_units 
                WHERE parent_id IS NULL
                
                UNION ALL
                
                SELECT c.id, c.parent_id, t.depth + 1
                FROM geo_administrative_units c
                JOIN tree t ON c.parent_id = t.id
            )
            INSERT INTO geo_closure (ancestor_id, descendant_id, depth)
            SELECT t1.id, t2.id, t2.depth - t1.depth
            FROM geo_administrative_units t1
            JOIN tree t2 ON t2.id IN (
                WITH RECURSIVE descendants AS (
                    SELECT id FROM geo_administrative_units WHERE id = t1.id
                    UNION ALL
                    SELECT c.id FROM geo_administrative_units c
                    JOIN descendants d ON c.parent_id = d.id
                )
                SELECT id FROM descendants
            )
            WHERE t1.id != t2.id"
        );
    }
}
```

### Simple Committee Dashboard

```php
// app/Http/Controllers/CommitteeController.php
class CommitteeController extends Controller
{
    public function dashboard(Committee $committee)
    {
        // Get all descendant geo units using closure table
        $descendantGeoIds = DB::table('geo_closure')
            ->where('ancestor_id', $committee->geo_unit_id)
            ->pluck('descendant_id');
        
        // Count members in this committee's geographic area
        $memberCount = Member::whereIn('primary_geo_unit_id', $descendantGeoIds)->count();
        
        // Get sub-committees
        $subCommittees = Committee::where('parent_committee_id', $committee->id)->get();
        
        // Get office bearers
        $officeBearers = $committee->officeBearers()
            ->with('member')
            ->where('is_current', true)
            ->get();
        
        return inertia('Committee/Dashboard', [
            'committee' => $committee,
            'memberCount' => $memberCount,
            'subCommittees' => $subCommittees,
            'officeBearers' => $officeBearers,
        ]);
    }
}
```

---

## Phased Roadmap

| Phase | What | When |
|-------|------|------|
| **Phase 1** | Simple geography + committees + member geo links | Now |
| **Phase 2** | Geographic permissions + committee dashboards | Next |
| **Phase 3** | Newsletter system (if needed) | Later |
| **Phase 4** | Full membership management (if needed) | Much later |

---

## Summary

| Question | Answer |
|----------|--------|
| Do you need separate landlord/tenant DBs? | ❌ No — use GlobalScope |
| Do you need full membership context? | ❌ Not now — extend later |
| What should you implement now? | Simple committees + member geo links + basic dashboard |
| What can wait? | Newsletters, finance, audit, full membership |

**Start with the minimal schema. Add complexity only when needed.** 🚀

    ## Good Clarification — Single Database with GlobalScope

You're right. Your project uses **single database** with `GlobalScope` for tenant isolation, not separate landlord/tenant databases.

---

## Simplified Approach for Your Architecture

| Original Plan (Multi-DB) | Your Project (Single DB + GlobalScope) |
|--------------------------|----------------------------------------|
| Landlord DB (geo master) | `geo_administrative_units` with `tenant_id` + GlobalScope |
| Tenant DB (mirrored) | Same table with tenant filtering |
| `tenants:artisan` commands | Standard Laravel migrations |
| Separate connections | Single connection + GlobalScope |

---

## Do You Need Full Membership Context Now?

**Answer: NO — Keep simple now, extend later.**

Here's the minimal viable approach for Phase 1:

---

## Minimal Implementation (Now)

### What You Actually Need

| Component | Needed Now? | Why |
|-----------|-------------|-----|
| Geographic units (0-10 levels) | ✅ Yes | Foundation |
| Geo closure table (performance) | ✅ Yes | Fast hierarchy queries |
| Committees mapped to geo units | ✅ Yes | Core structure |
| Committee office bearers | ✅ Yes | Who leads committees |
| Member primary geography | ✅ Yes | Where members live |
| Member-committee association | ✅ Yes | Which committee members belong to |
| Geographic permissions | ✅ Yes | Boundary-based access |
| Committee dashboard | ✅ Yes | Minimal view |
| **Full membership management** | ❌ **No** | Existing system already has members |
| **Newsletter system** | ❌ **No** | Phase 2 |
| **Finance tracking** | ❌ **No** | Phase 3 |
| **Audit logging** | ⏸️ **Optional** | Add later |

---

## Simplified Schema (Single Database with GlobalScope)

```sql
-- Geographic units (with tenant isolation)
CREATE TABLE geo_administrative_units (
    id BIGSERIAL PRIMARY KEY,
    tenant_id UUID NOT NULL,  -- GlobalScope filters this
    country_code CHAR(2),
    level SMALLINT CHECK (level BETWEEN 0 AND 10),
    parent_id BIGINT REFERENCES geo_administrative_units(id),
    path LTREE,  -- For fast hierarchy queries
    name JSONB,
    name_local JSONB,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Committee table (simple)
CREATE TABLE committees (
    id UUID PRIMARY KEY,
    tenant_id UUID NOT NULL,
    organisation_id UUID NOT NULL,
    geo_unit_id BIGINT REFERENCES geo_administrative_units(id),
    parent_committee_id UUID REFERENCES committees(id),
    name VARCHAR(255),
    level INT DEFAULT 1,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Committee office bearers (who leads)
CREATE TABLE committee_office_bearers (
    id BIGSERIAL PRIMARY KEY,
    committee_id UUID REFERENCES committees(id),
    member_id UUID REFERENCES members(id),
    position VARCHAR(100),
    is_primary BOOLEAN DEFAULT false,
    is_current BOOLEAN DEFAULT true,
    term_start DATE,
    term_end DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Member geography link (simple)
ALTER TABLE members ADD COLUMN primary_geo_unit_id BIGINT REFERENCES geo_administrative_units(id);

-- Member-committee association (simple)
CREATE TABLE member_committees (
    member_id UUID REFERENCES members(id),
    committee_id UUID REFERENCES committees(id),
    role VARCHAR(100),
    joined_at DATE,
    is_active BOOLEAN DEFAULT true,
    PRIMARY KEY (member_id, committee_id)
);

-- Geo closure table (performance)
CREATE TABLE geo_closure (
    ancestor_id BIGINT REFERENCES geo_administrative_units(id),
    descendant_id BIGINT REFERENCES geo_administrative_units(id),
    depth INT DEFAULT 0,
    PRIMARY KEY (ancestor_id, descendant_id)
);
```

---

## What to Do Now (Phase 1)

| Step | Action | Complexity |
|------|--------|------------|
| 1 | Use existing `geo_administrative_units` table | ✅ Already have |
| 2 | Add `geo_closure` table | Medium |
| 3 | Create `committees` table | Low |
| 4 | Create `committee_office_bearers` | Low |
| 5 | Add `primary_geo_unit_id` to members | Low |
| 6 | Create `member_committees` pivot | Low |
| 7 | Create populator command for closure | Medium |
| 8 | Create basic committee dashboard | Medium |

---

## Simplified Code (No Full Membership Context)

### Closure Populator (Simplified)

```php
// app/Console/Commands/PopulateGeoClosure.php
class PopulateGeoClosure extends Command
{
    public function handle()
    {
        // Clear existing
        DB::table('geo_closure')->truncate();
        
        // Insert self-references
        DB::statement(
            "INSERT INTO geo_closure (ancestor_id, descendant_id, depth)
             SELECT id, id, 0 FROM geo_administrative_units"
        );
        
        // Insert all ancestor-descendant relationships
        DB::statement(
            "WITH RECURSIVE tree AS (
                SELECT id, parent_id, 0 as depth 
                FROM geo_administrative_units 
                WHERE parent_id IS NULL
                
                UNION ALL
                
                SELECT c.id, c.parent_id, t.depth + 1
                FROM geo_administrative_units c
                JOIN tree t ON c.parent_id = t.id
            )
            INSERT INTO geo_closure (ancestor_id, descendant_id, depth)
            SELECT t1.id, t2.id, t2.depth - t1.depth
            FROM geo_administrative_units t1
            JOIN tree t2 ON t2.id IN (
                WITH RECURSIVE descendants AS (
                    SELECT id FROM geo_administrative_units WHERE id = t1.id
                    UNION ALL
                    SELECT c.id FROM geo_administrative_units c
                    JOIN descendants d ON c.parent_id = d.id
                )
                SELECT id FROM descendants
            )
            WHERE t1.id != t2.id"
        );
    }
}
```

### Simple Committee Dashboard

```php
// app/Http/Controllers/CommitteeController.php
class CommitteeController extends Controller
{
    public function dashboard(Committee $committee)
    {
        // Get all descendant geo units using closure table
        $descendantGeoIds = DB::table('geo_closure')
            ->where('ancestor_id', $committee->geo_unit_id)
            ->pluck('descendant_id');
        
        // Count members in this committee's geographic area
        $memberCount = Member::whereIn('primary_geo_unit_id', $descendantGeoIds)->count();
        
        // Get sub-committees
        $subCommittees = Committee::where('parent_committee_id', $committee->id)->get();
        
        // Get office bearers
        $officeBearers = $committee->officeBearers()
            ->with('member')
            ->where('is_current', true)
            ->get();
        
        return inertia('Committee/Dashboard', [
            'committee' => $committee,
            'memberCount' => $memberCount,
            'subCommittees' => $subCommittees,
            'officeBearers' => $officeBearers,
        ]);
    }
}
```

---

## Phased Roadmap

| Phase | What | When |
|-------|------|------|
| **Phase 1** | Simple geography + committees + member geo links | Now |
| **Phase 2** | Geographic permissions + committee dashboards | Next |
| **Phase 3** | Newsletter system (if needed) | Later |
| **Phase 4** | Full membership management (if needed) | Much later |

---

## Summary

| Question | Answer |
|----------|--------|
| Do you need separate landlord/tenant DBs? | ❌ No — use GlobalScope |
| Do you need full membership context? | ❌ Not now — extend later |
| What should you implement now? | Simple committees + member geo links + basic dashboard |
| What can wait? | Newsletters, finance, audit, full membership |

**Start with the minimal schema. Add complexity only when needed.** 🚀
