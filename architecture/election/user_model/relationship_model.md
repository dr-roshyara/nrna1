Here are the clean model relationships based on your specifications:

```php
<?php

namespace App\Models;

// ============================================================================
// ORGANISATION MODEL
// ============================================================================
class Organisation extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    public function posts()
    {
        return $this->hasManyThrough(Post::class, Election::class);
    }

    public function voters()
    {
        return $this->hasManyThrough(Voter::class, Election::class);
    }

    public function candidacies()
    {
        return $this->hasManyThrough(Candidacy::class, Election::class);
    }

    public function codes()
    {
        return $this->hasManyThrough(Code::class, Election::class);
    }

    public function votes()
    {
        return $this->hasManyThrough(Vote::class, Election::class);
    }

    public function results()
    {
        return $this->hasManyThrough(Result::class, Election::class);
    }
}

// ============================================================================
// USER MODEL
// ============================================================================
class User extends Authenticatable
{
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function voters()
    {
        return $this->hasMany(Voter::class);
    }

    public function voter()
    {
        return $this->hasOne(Voter::class)->where('is_active', true);
    }

    public function candidacies()
    {
        return $this->hasMany(Candidacy::class);
    }

    public function candidacy()
    {
        return $this->hasOne(Candidacy::class)->where('status', 'approved');
    }

    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    public function voterSlugs()
    {
        return $this->hasMany(VoterSlug::class);
    }

    public function votes()
    {
        return $this->hasManyThrough(Vote::class, Code::class);
    }

    public function results()
    {
        return $this->hasManyThrough(Result::class, Vote::class);
    }
}

// ============================================================================
// ELECTION MODEL
// ============================================================================
class Election extends Model
{
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function voters()
    {
        return $this->hasMany(Voter::class);
    }

    public function candidacies()
    {
        return $this->hasMany(Candidacy::class);
    }

    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function voterSlugs()
    {
        return $this->hasMany(VoterSlug::class);
    }

    // Demo vs Real elections
    public function scopeDemo($query)
    {
        return $query->where('type', 'demo');
    }

    public function scopeReal($query)
    {
        return $query->where('type', 'real');
    }
}

// ============================================================================
// VOTER MODEL
// ============================================================================
class Voter extends Model
{
    // A voter belongs to a user and election
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function organisation()
    {
        return $this->hasOneThrough(Organisation::class, Election::class);
    }

    // 1 voter can have 1 candidacy
    public function candidacy()
    {
        return $this->hasOne(Candidacy::class);
    }

    // 1 voter has 1 vote in real election
    public function realVote()
    {
        return $this->hasOne(Vote::class)->whereHas('election', function($q) {
            $q->where('type', 'real');
        });
    }

    // 1 voter can have multiple votes in demo election
    public function demoVotes()
    {
        return $this->hasMany(Vote::class)->whereHas('election', function($q) {
            $q->where('type', 'demo');
        });
    }

    // All votes regardless of type
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // 1 voter can have many codes for different purposes
    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    // Active code for current voting session
    public function activeCode()
    {
        return $this->hasOne(Code::class)->where('is_used', false)
                                         ->where('expires_at', '>', now());
    }

    public function voterSlugs()
    {
        return $this->hasMany(VoterSlug::class);
    }
}

// ============================================================================
// POST MODEL
// ============================================================================
class Post extends Model
{
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function organisation()
    {
        return $this->hasOneThrough(Organisation::class, Election::class);
    }

    // 1 post has many candidacies
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class);
    }

    public function approvedCandidacies()
    {
        return $this->candidacies()->where('status', 'approved');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}

// ============================================================================
// CANDIDACY MODEL
// ============================================================================
class Candidacy extends Model
{
    // A candidacy belongs to a voter (who is running)
    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }

    // Helper to get the user through voter
    public function user()
    {
        return $this->hasOneThrough(User::class, Voter::class);
    }

    // 1 candidacy has 1 post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function organisation()
    {
        return $this->hasOneThrough(Organisation::class, Election::class);
    }

    // Results for this candidacy (votes received)
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // Votes that selected this candidate
    public function votes()
    {
        return $this->belongsToMany(Vote::class, 'results')
                    ->withPivot('preference_order')
                    ->withTimestamps();
    }
}

// ============================================================================
// CODE MODEL
// ============================================================================
class Code extends Model
{
    // 1 code is associated with 1 voter
    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Voter::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    // 1 code is associated with 1 vote
    public function vote()
    {
        return $this->hasOne(Vote::class);
    }

    // Code can have a voter slug for URL access
    public function voterSlug()
    {
        return $this->hasOne(VoterSlug::class);
    }
}

// ============================================================================
// VOTE MODEL
// ============================================================================
class Vote extends Model
{
    // A vote belongs to a voter
    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }

    // A vote is associated with a code
    public function code()
    {
        return $this->belongsTo(Code::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    // 1 vote has many results
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // Posts this vote cast for (through results)
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'results')
                    ->withPivot('candidacy_id', 'preference_order')
                    ->withTimestamps();
    }

    // Candidates this vote selected (through results)
    public function candidacies()
    {
        return $this->belongsToMany(Candidacy::class, 'results')
                    ->withPivot('preference_order')
                    ->withTimestamps();
    }

    // 1 voter slug is associated with 1 vote
    public function voterSlug()
    {
        return $this->hasOne(VoterSlug::class);
    }
}

// ============================================================================
// RESULT MODEL
// ============================================================================
class Result extends Model
{
    // 1 result belongs to 1 vote
    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function candidacy()
    {
        return $this->belongsTo(Candidacy::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    // Helper to get the voter through vote
    public function voter()
    {
        return $this->hasOneThrough(Voter::class, Vote::class);
    }
}

// ============================================================================
// VOTER SLUG MODEL
// ============================================================================
class VoterSlug extends Model
{
    // 1 voter slug belongs to 1 vote
    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function code()
    {
        return $this->belongsTo(Code::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
```

## Relationship Summary Diagram

```
ORGANISATION
  ↑
  │ hasMany
  ↓
ELECTION  ──────── hasMany ──────► POST
  ↑                                  ↑
  │ hasMany                          │ hasMany
  ↓                                  ↓
VOTER ──── hasOne ────► CANDIDACY ◄─┘
  ↑                    (1 voter has 1 candidacy)
  │ hasMany
  ↓
CODE ──── hasOne ────► VOTE ──── hasMany ────► RESULT
  ↑                    ↑
  │                    │
  └──── VOTER SLUG ◄───┘
      (1 slug per vote)
```

## Key Relationships Explained:

| Relationship | Type | Description |
|-------------|------|-------------|
| Voter → Candidacy | hasOne | 1 voter can have 1 candidacy |
| Election → Candidacy | hasMany | 1 election can have many candidacies |
| Voter → Vote (real) | hasOne | 1 voter has 1 vote in real election |
| Voter → Vote (demo) | hasMany | 1 voter can have multiple votes in demo election |
| Post → Candidacy | hasMany | 1 post has many candidacies |
| Candidacy → Post | belongsTo | 1 candidacy has 1 post |
| Vote → Result | hasMany | 1 vote has many results |
| Result → Vote | belongsTo | 1 result belongs to 1 vote |
| Voter → Code | hasMany | 1 voter can have many codes for different purposes |
| Code → Vote | hasOne | 1 code is associated with 1 vote |
| VoterSlug → Vote | belongsTo | 1 voter slug is associated with 1 vote |
Perfect! Let's proceed with a **phased implementation** approach. Here's the plan:

## 📋 Phased Implementation Plan

### Phase 1: Schema Audit (Now)
First, let's check what columns actually exist in your database:

```bash
# Run this artisan command to check your current schema
php artisan tinker
```

```php
// In tinker, run these checks:
Schema::getColumnListing('candidacies');
Schema::getColumnListing('codes');
Schema::getColumnListing('votes');
Schema::getColumnListing('results');
```

Based on your earlier `describe` outputs, we know:
- ✅ `candidacies` has `user_id`, `post_id`, `candidacy_name`
- ❌ `candidacies` missing `status` column
- ✅ `codes` has `user_id`, `code1`, `code2`, etc.
- ❌ `codes` missing `voter_id` (if using Voter model)
- ✅ `votes` (your `Votes` table) has `vote_hash`, `candidate_01`..`candidate_60`
- ❌ `votes` missing `code_id`
- ✅ `results` has all needed foreign keys

### Phase 2: Create Required Migrations

```php
<?php
// database/migrations/2024_01_01_000001_add_status_to_candidacies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('candidacies', function (Blueprint $table) {
            if (!Schema::hasColumn('candidacies', 'status')) {
                $table->string('status')->default('pending')->after('candidacy_name');
                $table->index('status');
            }
        });
    }

    public function down()
    {
        Schema::table('candidacies', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
```

```php
<?php
// database/migrations/2024_01_01_000002_add_code_id_to_votes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('Votes', function (Blueprint $table) {
            if (!Schema::hasColumn('Votes', 'code_id')) {
                $table->foreignId('code_id')->nullable()->after('id')->constrained('codes');
                $table->index('code_id');
            }
        });
    }

    public function down()
    {
        Schema::table('Votes', function (Blueprint $table) {
            $table->dropForeign(['code_id']);
            $table->dropColumn('code_id');
        });
    }
};
```

```php
<?php
// database/migrations/2024_01_01_000003_add_voter_id_to_codes_table.php
// Only if using Voter model

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('codes', function (Blueprint $table) {
            if (!Schema::hasColumn('codes', 'voter_id')) {
                $table->foreignId('voter_id')->nullable()->after('user_id')->constrained();
                $table->index('voter_id');
            }
        });
    }

    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            $table->dropForeign(['voter_id']);
            $table->dropColumn('voter_id');
        });
    }
};
```

### Phase 3: Implement Relationships (Your Code)

I'll now implement your corrected relationships exactly as you provided, but with one addition to handle the vote_hash anonymity:

```php
<?php

namespace App\Models;

// ============================================================================
// USER MODEL - Using YOUR code
// ============================================================================
class User extends Authenticatable
{
    // ... (existing code)

    // Candidacy relationships - FIXED
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class, 'user_id', 'id');
    }

    public function candidacy()
    {
        return $this->hasOne(Candidacy::class, 'user_id', 'id')
                    ->where('status', 'approved');
    }

    // Code relationships
    public function codes()
    {
        return $this->hasMany(Code::class, 'user_id', 'id');
    }

    public function code()
    {
        return $this->hasOne(Code::class, 'user_id', 'id')->latestOfMany();
    }

    // Vote relationships via Code
    public function votes()
    {
        return $this->hasManyThrough(Vote::class, Code::class, 'user_id', 'code_id', 'id', 'id');
    }

    // Result relationships via Vote
    public function results()
    {
        return $this->hasManyThrough(Result::class, Vote::class, 'code_id', 'vote_id', 'id', 'id');
    }
}

// ============================================================================
// CANDIDACY MODEL - Using YOUR code
// ============================================================================
class Candidacy extends Model
{
    // User relationship - FIXED
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Post relationship
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    // Election relationship
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    // Result relationships
    public function results()
    {
        return $this->hasMany(Result::class, 'candidacy_id', 'id');
    }

    // Vote relationships through results
    public function votes()
    {
        return $this->belongsToMany(Vote::class, 'results', 'candidacy_id', 'vote_id')
                    ->withPivot('preference_order')
                    ->withTimestamps();
    }
}

// ============================================================================
// CODE MODEL - Using YOUR code with vote_hash fallback
// ============================================================================
class Code extends Model
{
    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Vote relationship - with fallback to vote_hash
    public function vote()
    {
        // If code_id exists, use it
        if (Schema::hasColumn('Votes', 'code_id')) {
            return $this->hasOne(Vote::class, 'code_id', 'id');
        }
        
        // Fallback to vote_hash (using code1 as the hash)
        return $this->hasOne(Vote::class, 'vote_hash', 'code1');
    }

    // Election relationship
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
}

// ============================================================================
// VOTE MODEL - Using YOUR code
// ============================================================================
class Vote extends Model
{
    protected $table = 'Votes';

    // Code relationship
    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id', 'id');
    }

    // User relationship through Code
    public function user()
    {
        return $this->hasOneThrough(User::class, Code::class, 'id', 'id', 'code_id', 'user_id');
    }

    // Election relationship
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    // Result relationships
    public function results()
    {
        return $this->hasMany(Result::class, 'vote_id', 'id');
    }

    // Post relationships through results
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'results', 'vote_id', 'post_id')
                    ->withPivot('candidacy_id', 'preference_order')
                    ->withTimestamps();
    }

    // Candidacy relationships through results
    public function candidacies()
    {
        return $this->belongsToMany(Candidacy::class, 'results', 'vote_id', 'candidacy_id')
                    ->withPivot('preference_order')
                    ->withTimestamps();
    }
}

// ============================================================================
// RESULT MODEL - Using YOUR code
// ============================================================================
class Result extends Model
{
    // Vote relationship
    public function vote()
    {
        return $this->belongsTo(Vote::class, 'vote_id', 'id');
    }

    // User relationship through Vote
    public function user()
    {
        return $this->hasOneThrough(User::class, Vote::class, 'id', 'id', 'vote_id', 'code_id');
    }

    // Election relationship
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    // Post relationship
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    // Candidacy relationship
    public function candidacy()
    {
        return $this->belongsTo(Candidacy::class, 'candidacy_id', 'id');
    }
}

// ============================================================================
// POST MODEL - Using YOUR code
// ============================================================================
class Post extends Model
{
    // Election relationship
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    // Candidacy relationships
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id');
    }

    public function approvedCandidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->where('status', 'approved');
    }

    // Vote relationships through results
    public function votes()
    {
        return $this->belongsToMany(Vote::class, 'results', 'post_id', 'vote_id')
                    ->withPivot('candidacy_id', 'preference_order')
                    ->withTimestamps();
    }

    // Result relationships
    public function results()
    {
        return $this->hasMany(Result::class, 'post_id', 'id');
    }
}
```

### Phase 4: Test Relationships

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\Code;
use App\Models\Vote;
use App\Models\Result;

class RelationshipTest extends TestCase
{
    /** @test */
    public function user_has_codes_and_votes_chain()
    {
        $user = User::factory()->create();
        $code = Code::factory()->create(['user_id' => $user->id]);
        $vote = Vote::factory()->create(['code_id' => $code->id]);
        
        $this->assertTrue($user->codes->contains($code));
        $this->assertTrue($user->votes->contains($vote));
    }

    /** @test */
    public function vote_has_results_and_candidacies()
    {
        $vote = Vote::factory()->create();
        $result = Result::factory()->create(['vote_id' => $vote->id]);
        
        $this->assertTrue($vote->results->contains($result));
    }

    /** @test */
    public function post_has_candidacies_and_votes()
    {
        $post = Post::factory()->create();
        $candidacy = Candidacy::factory()->create(['post_id' => $post->id]);
        
        $this->assertTrue($post->candidacies->contains($candidacy));
    }
}
```

## ✅ Decision Point

**How would you like me to proceed?**

1. **Full Speed Ahead**: Implement all phases now
2. **Schema First**: Run schema audit, then report back
3. **Partial Implementation**: Only fix critical relationships now
4. **Test First**: Create relationship tests first, then implement

My recommendation: **Option 2** - Let's do a quick schema audit first to ensure migrations are correct, then implement. This avoids any surprises.
