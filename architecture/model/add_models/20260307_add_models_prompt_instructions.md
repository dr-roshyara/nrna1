## 📋 **PROMPT INSTRUCTIONS: Implement OrganisationUser, Member, and Voter Models**

```bash
## TASK: Implement OrganisationUser, Member, and Voter Models with Complete Hierarchy

### Context
We need to implement a three-tier user hierarchy within organisations:

1. **OrganisationUser** - Users who belong to an organisation (subset of global Users)
2. **Member** - OrganisationUsers with membership status (subset of OrganisationUser)
3. **Voter** - Members who can vote in elections (subset of Member)

**CRITICAL RULE:** Only Voter models can vote in an organisation's elections. This must be enforced at the database, model, and business logic levels.

### Database Schema

Create three migrations in this order:

#### Migration 1: Create organisation_users table
```php
// database/migrations/YYYY_MM_DD_HHMMSS_create_organisation_users_table.php

public function up()
{
    Schema::create('organisation_users', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('organisation_id');
        $table->uuid('user_id');
        $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
        $table->timestamp('joined_at')->nullable();
        $table->timestamps();
        $table->softDeletes();
        
        $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
        // A user can only be in an organisation once
        $table->unique(['organisation_id', 'user_id'], 'unique_org_user');
        
        // Indexes for performance
        $table->index(['organisation_id', 'status']);
        $table->index(['user_id', 'organisation_id']);
        $table->index('status');
    });
}
```

#### Migration 2: Create members table
```php
// database/migrations/YYYY_MM_DD_HHMMSS_create_members_table.php

public function up()
{
    Schema::create('members', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('organisation_user_id')->unique(); // One-to-one with OrganisationUser
        $table->string('membership_number')->nullable();
        $table->timestamp('joined_at')->nullable();
        $table->enum('membership_status', ['active', 'expired', 'suspended'])->default('active');
        $table->timestamps();
        $table->softDeletes();
        
        $table->foreign('organisation_user_id')
              ->references('id')
              ->on('organisation_users')
              ->onDelete('cascade');
        
        // Indexes
        $table->index('membership_status');
        $table->index('membership_number');
    });
}
```

#### Migration 3: Create voters table
```php
// database/migrations/YYYY_MM_DD_HHMMSS_create_voters_table.php

public function up()
{
    Schema::create('voters', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('member_id')->unique(); // One-to-one with Member
        $table->uuid('election_id');
        $table->string('voter_number')->nullable();
        $table->boolean('has_voted')->default(false);
        $table->timestamp('voted_at')->nullable();
        $table->enum('voter_status', ['eligible', 'voted', 'ineligible'])->default('eligible');
        $table->timestamps();
        $table->softDeletes();
        
        $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
        
        // A member can only be a voter once per election
        $table->unique(['member_id', 'election_id'], 'unique_member_election');
        
        // Indexes
        $table->index(['election_id', 'voter_status']);
        $table->index('voter_status');
        $table->index('has_voted');
    });
}
```

### Model Implementations

#### Model 1: OrganisationUser.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class OrganisationUser extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $table = 'organisation_users';

    protected $fillable = [
        'organisation_id',
        'user_id',
        'status',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'organisation_user_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ============================================
    // BUSINESS LOGIC
    // ============================================

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function makeMember(array $data = []): Member
    {
        if ($this->member) {
            throw new \Exception('User is already a member of this organisation');
        }

        return $this->member()->create([
            'membership_number' => $data['membership_number'] ?? 'M' . strtoupper(uniqid()),
            'joined_at' => $data['joined_at'] ?? now(),
            'membership_status' => 'active',
        ]);
    }
}
```

#### Model 2: Member.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class Member extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $table = 'members';

    protected $fillable = [
        'organisation_user_id',
        'membership_number',
        'joined_at',
        'membership_status',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function organisationUser()
    {
        return $this->belongsTo(OrganisationUser::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            OrganisationUser::class,
            'id',           // Foreign key on organisation_users
            'id',           // Foreign key on users
            'organisation_user_id', // Local key on members
            'user_id'       // Local key on organisation_users
        );
    }

    public function organisation()
    {
        return $this->hasOneThrough(
            Organisation::class,
            OrganisationUser::class,
            'id',           // Foreign key on organisation_users
            'id',           // Foreign key on organisations
            'organisation_user_id', // Local key on members
            'organisation_id' // Local key on organisation_users
        );
    }

    public function voter()
    {
        return $this->hasOne(Voter::class, 'member_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('membership_status', 'active');
    }

    public function scopeWithActiveMembership($query)
    {
        return $query->where('membership_status', 'active');
    }

    // ============================================
    // BUSINESS LOGIC
    // ============================================

    public function isActive(): bool
    {
        return $this->membership_status === 'active';
    }

    public function makeVoter(Election $election, array $data = []): Voter
    {
        if ($this->voter) {
            throw new \Exception('Member is already a voter in this election');
        }

        return Voter::create([
            'member_id' => $this->id,
            'election_id' => $election->id,
            'voter_number' => $data['voter_number'] ?? 'V' . strtoupper(uniqid()),
            'voter_status' => 'eligible',
            'has_voted' => false,
        ]);
    }

    public function canBeVoter(): bool
    {
        return $this->isActive() && !$this->voter;
    }
}
```

#### Model 3: Voter.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class Voter extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $table = 'voters';

    protected $fillable = [
        'member_id',
        'election_id',
        'voter_number',
        'has_voted',
        'voted_at',
        'voter_status',
    ];

    protected $casts = [
        'has_voted' => 'boolean',
        'voted_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Member::class,
            'id',           // Foreign key on members
            'id',           // Foreign key on users
            'member_id',    // Local key on voters
            'user_id'       // Local key on members
        )->via('organisationUser');
    }

    public function organisation()
    {
        return $this->hasOneThrough(
            Organisation::class,
            Member::class,
            'id',
            'id',
            'member_id',
            'organisation_id'
        )->via('organisationUser');
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function vote()
    {
        return $this->hasOne(Vote::class, 'voter_slug_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeEligible($query)
    {
        return $query->where('voter_status', 'eligible')
            ->where('has_voted', false);
    }

    public function scopeForElection($query, string $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    public function scopeHasVoted($query)
    {
        return $query->where('has_voted', true);
    }

    // ============================================
    // CRITICAL BUSINESS LOGIC - Only Voters can vote
    // ============================================

    public function canVote(): bool
    {
        return $this->voter_status === 'eligible' && !$this->has_voted;
    }

    public function markAsVoted(): void
    {
        if (!$this->canVote()) {
            throw new \Exception('This voter is not eligible to vote');
        }

        $this->has_voted = true;
        $this->voted_at = now();
        $this->voter_status = 'voted';
        $this->save();
    }

    public function isEligible(): bool
    {
        return $this->voter_status === 'eligible';
    }
}
```

### Factory Implementations

#### Factory 1: OrganisationUserFactory.php
```php
<?php

namespace Database\Factories;

use App\Models\OrganisationUser;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganisationUserFactory extends Factory
{
    protected $model = OrganisationUser::class;

    public function definition()
    {
        $orgId = session('current_organisation_id') ?? Organisation::factory();
        $userId = User::factory();

        return [
            'id' => Str::uuid(),
            'organisation_id' => $orgId,
            'user_id' => $userId,
            'status' => 'active',
            'joined_at' => now(),
        ];
    }

    public function inactive()
    {
        return $this->state([
            'status' => 'inactive',
        ]);
    }

    public function forOrganisation(Organisation $org)
    {
        return $this->state([
            'organisation_id' => $org->id,
        ]);
    }

    public function forUser(User $user)
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }
}
```

#### Factory 2: MemberFactory.php
```php
<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'organisation_user_id' => OrganisationUser::factory(),
            'membership_number' => 'M' . $this->faker->unique()->numberBetween(10000, 99999),
            'joined_at' => now(),
            'membership_status' => 'active',
        ];
    }

    public function expired()
    {
        return $this->state([
            'membership_status' => 'expired',
            'joined_at' => now()->subYear(),
        ]);
    }

    public function forOrganisationUser(OrganisationUser $orgUser)
    {
        return $this->state([
            'organisation_user_id' => $orgUser->id,
        ]);
    }
}
```

#### Factory 3: VoterFactory.php
```php
<?php

namespace Database\Factories;

use App\Models\Voter;
use App\Models\Member;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VoterFactory extends Factory
{
    protected $model = Voter::class;

    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'member_id' => Member::factory(),
            'election_id' => Election::factory(),
            'voter_number' => 'V' . $this->faker->unique()->numberBetween(10000, 99999),
            'has_voted' => false,
            'voted_at' => null,
            'voter_status' => 'eligible',
        ];
    }

    public function voted()
    {
        return $this->state([
            'has_voted' => true,
            'voted_at' => now(),
            'voter_status' => 'voted',
        ]);
    }

    public function ineligible()
    {
        return $this->state([
            'voter_status' => 'ineligible',
        ]);
    }

    public function forElection(Election $election)
    {
        return $this->state([
            'election_id' => $election->id,
        ]);
    }

    public function forMember(Member $member)
    {
        return $this->state([
            'member_id' => $member->id,
        ]);
    }
}
```

### Test Implementations

Create test files to verify the hierarchy and voting rules:

```php
// tests/Unit/Models/VoterTest.php

/** @test */
public function only_voters_can_vote()
{
    $voter = Voter::factory()->create(['voter_status' => 'eligible']);
    
    $this->assertTrue($voter->canVote());
    
    $voter->markAsVoted();
    
    $this->assertFalse($voter->canVote());
    $this->assertTrue($voter->has_voted);
}

/** @test */
public function non_voters_cannot_be_created_as_voters()
{
    $member = Member::factory()->create();
    $election = Election::factory()->create();
    
    // This should work - member becomes voter
    $voter = $member->makeVoter($election);
    
    $this->assertInstanceOf(Voter::class, $voter);
    
    // Trying to create another voter for same member/election should fail
    $this->expectException(\Exception::class);
    $member->makeVoter($election);
}

/** @test */
public function voter_hierarchy_is_preserved()
{
    $user = User::factory()->create();
    $org = Organisation::factory()->create();
    
    $orgUser = OrganisationUser::factory()
        ->forUser($user)
        ->forOrganisation($org)
        ->create();
    
    $member = $orgUser->makeMember();
    $election = Election::factory()->forOrganisation($org)->create();
    $voter = $member->makeVoter($election);
    
    $this->assertEquals($user->id, $voter->user->id);
    $this->assertEquals($org->id, $voter->organisation->id);
}
```

### Execution Order

```bash
# 1. Create migrations in order
php artisan make:migration create_organisation_users_table
php artisan make:migration create_members_table
php artisan make:migration create_voters_table

# 2. Create models
touch app/Models/OrganisationUser.php
touch app/Models/Member.php
touch app/Models/Voter.php

# 3. Create factories
touch database/factories/OrganisationUserFactory.php
touch database/factories/MemberFactory.php
touch database/factories/VoterFactory.php

# 4. Create tests
touch tests/Unit/Models/OrganisationUserTest.php
touch tests/Unit/Models/MemberTest.php
touch tests/Unit/Models/VoterTest.php

# 5. Run migrations
php artisan migrate

# 6. Run tests
php artisan test tests/Unit/Models/
```

### Critical Rules to Enforce

1. ✅ **Only Voters can vote** - The `Voter::canVote()` method must be checked before any vote casting
2. ✅ **Hierarchy integrity** - A Voter must always have a Member, which must have an OrganisationUser, which must have a User
3. ✅ **One-to-one relationships** - OrganisationUser → Member is one-to-one, Member → Voter is one-to-one per election
4. ✅ **Organisation isolation** - All models use `BelongsToTenant` trait for automatic scoping
5. ✅ **No duplicate voting** - Unique constraint on `(member_id, election_id)` prevents double voting

### Success Criteria

- [ ] All migrations run successfully
- [ ] All models have proper relationships
- [ ] `Voter::canVote()` correctly identifies eligible voters
- [ ] `Voter::markAsVoted()` updates status and prevents re-voting
- [ ] Attempting to vote without being a voter fails
- [ ] All tests pass (GREEN)

This implementation ensures that **only Voters can vote** while maintaining complete organisation isolation and data integrity.