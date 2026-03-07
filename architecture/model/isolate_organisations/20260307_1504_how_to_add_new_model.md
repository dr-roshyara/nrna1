## 📋 **NEW MODEL CREATION CHECKLIST - Voter Model Example**

### When creating ANY new model (like Voter), you MUST include these points:

---

## 🏛️ **1. DATABASE LAYER (Migration)**

```php
// database/migrations/xxxx_create_voters_table.php

public function up()
{
    Schema::create('voters', function (Blueprint $table) {
        // ✅ UUID primary key (no auto-increment)
        $table->uuid('id')->primary();
        
        // ✅ MUST HAVE: organisation_id with foreign key
        $table->uuid('organisation_id');
        $table->foreign('organisation_id')
              ->references('id')
              ->on('organisations')
              ->onDelete('cascade');  // or restrict based on business logic
        
        // ✅ Other business fields
        $table->uuid('user_id')->nullable();
        $table->uuid('election_id');
        $table->string('status')->default('pending');
        
        // ✅ Timestamps + SoftDeletes
        $table->timestamps();
        $table->softDeletes();
        
        // ✅ CRITICAL: Composite indexes for performance
        $table->index(['organisation_id', 'status']);
        $table->index(['organisation_id', 'election_id']);
        $table->index(['organisation_id', 'user_id']);
        
        // ✅ Unique constraints (consider if they need to be org-scoped)
        $table->unique(['organisation_id', 'user_id', 'election_id']); 
        // NOT just unique('user_id') - that would be global!
    });
}
```

---

## 🧠 **2. MODEL LAYER (The Model Itself)**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;  // ✅ CRITICAL: Add this!

class Voter extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;  // ✅ MUST include trait

    protected $fillable = [
        'organisation_id',  // ✅ MUST be fillable
        'user_id',
        'election_id',
        'status',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // ============================================
    // ✅ RELATIONSHIPS (all must respect org isolation)
    // ============================================

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function user()
    {
        // ✅ User relationship MUST be scoped to same organisation
        return $this->belongsTo(User::class)
            ->whereHas('organisations', function ($q) {
                $q->where('organisation_id', $this->organisation_id);
            });
    }

    public function election()
    {
        // ✅ Election relationship auto-scoped via BelongsToTenant
        return $this->belongsTo(Election::class);
    }

    // ============================================
    // ✅ SCOPES (for convenience)
    // ============================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForElection($query, string $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    // ============================================
    // ✅ BUSINESS LOGIC METHODS
    // ============================================

    public function approve(): void
    {
        $this->status = 'approved';
        $this->verified_at = now();
        $this->save();
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
```

---

## 🧪 **3. TEST LAYER (TDD First)**

```php
// tests/Feature/VoterIsolationTest.php

/** @test */
public function voter_queries_auto_scope_to_current_organisation()
{
    // Arrange
    session(['current_organisation_id' => $this->orgA->id]);
    
    // Create voters in both orgs
    Voter::factory()->create(['organisation_id' => $this->orgA->id]);
    Voter::factory()->create(['organisation_id' => $this->orgB->id]);
    
    // Act
    $voters = Voter::all();
    
    // Assert - only orgA voters
    $this->assertCount(1, $voters);
}

/** @test */
public function voter_cannot_be_created_without_organisation_context()
{
    session()->forget('current_organisation_id');
    
    $this->expectException(\RuntimeException::class);
    
    Voter::create(['name' => 'Test']);  // Should fail - no org context
}

/** @test */
public function voter_relationships_respect_organisation_boundaries()
{
    // Test user relationship only returns users from same org
    // Test election relationship auto-scopes
}

/** @test */
public function voter_factory_sets_organisation_id_correctly()
{
    $voter = Voter::factory()->create();
    $this->assertNotNull($voter->organisation_id);
}
```

---

## 🏭 **4. FACTORY LAYER**

```php
// database/factories/VoterFactory.php

class VoterFactory extends Factory
{
    protected $model = Voter::class;

    public function definition()
    {
        // ✅ ALWAYS set organisation_id from session or default
        $orgId = session('current_organisation_id') 
            ?? Organisation::getDefaultPlatform()->id;
        
        return [
            'id' => Str::uuid(),
            'organisation_id' => $orgId,
            'user_id' => User::factory(),
            'election_id' => Election::factory(),
            'status' => 'pending',
        ];
    }

    // ✅ State for specific scenarios
    public function approved()
    {
        return $this->state([
            'status' => 'approved',
            'verified_at' => now(),
        ]);
    }

    public function forOrganisation(Organisation $org)
    {
        return $this->state([
            'organisation_id' => $org->id,
        ]);
    }
}
```

---

## 🔒 **5. MIDDLEWARE & ROUTES**

```php
// routes/web.php - Protect routes with middleware

Route::middleware(['auth', 'verified', 'ensure.organisation.member'])
    ->prefix('organisations/{organisation}')
    ->group(function () {
        Route::get('/voters', [VoterController::class, 'index']);
        Route::post('/voters', [VoterController::class, 'store']);
        Route::get('/voters/{voter}', [VoterController::class, 'show']);
    });
```

---

## 📊 **6. CONTROLLER LAYER**

```php
class VoterController extends Controller
{
    public function index()
    {
        // ✅ Auto-scoped - no manual where needed!
        $voters = Voter::with(['user', 'election'])
            ->pending()
            ->paginate(20);
        
        return inertia('Voters/Index', ['voters' => $voters]);
    }
    
    public function store(Request $request)
    {
        // ✅ organisation_id auto-filled by BelongsToTenant
        $voter = Voter::create($request->validated());
        
        return redirect()->route('voters.index');
    }
}
```

---

## ✅ **COMPLETE CHECKLIST FOR NEW MODEL**

| Layer | Check | Done? |
|-------|-------|-------|
| **Database** | `organisation_id` column with FK | ⬜ |
| | UUID primary key | ⬜ |
| | Composite indexes on `(org_id, *)` | ⬜ |
| | Org-scoped unique constraints | ⬜ |
| **Model** | `use BelongsToTenant` trait | ⬜ |
| | `organisation_id` in `$fillable` | ⬜ |
| | Relationships respect org scoping | ⬜ |
| | Convenience scopes (pending, etc.) | ⬜ |
| **Tests** | Isolation test for auto-scoping | ⬜ |
| | Test for creation without context | ⬜ |
| | Test relationships respect org | ⬜ |
| **Factory** | Sets `organisation_id` correctly | ⬜ |
| | Has states for common scenarios | ⬜ |
| **Routes** | Protected by `ensure.organisation.member` | ⬜ |
| **Controller** | No manual org_id in queries | ⬜ |

---

## 🎯 **KEY PRINCIPLES TO REMEMBER**

1. **NEVER** create a model without `organisation_id`
2. **ALWAYS** use `BelongsToTenant` trait
3. **ALWAYS** test isolation first (TDD)
4. **NEVER** use global unique constraints without considering org scope
5. **ALWAYS** add composite indexes on `(organisation_id, other_field)`

**Following this checklist ensures your new model is automatically protected by the multi-layer isolation architecture.**