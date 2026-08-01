## 📋 **PROFESSIONAL PROMPT INSTRUCTIONS: Demo Vote Multi-Mode Implementation**

```
## CONTEXT
We have a multi-tenant voting system with two distinct demo modes:

┌─────────────────────────────────────────────────────────────────────────────┐
│  MODE 1: Public Demo (No Organisation)                                     │
│  ├── organisation_id = NULL                                                │
│  ├── Anyone can vote without belonging to an organisation                 │
│  ├── Uses PUBLIC demo data (posts, candidates) with NULL org_id           │
│  └── Visible to ALL users regardless of org status                        │
├─────────────────────────────────────────────────────────────────────────────┤
│  MODE 2: Organisation-Specific Demo (Has Organisation)                    │
│  ├── organisation_id = X (user's org)                                      │
│  ├── Only users belonging to the organisation can vote                    │
│  ├── Uses ORG-SPECIFIC demo data (posts, candidates) with org_id = X      │
│  ├── Each organisation has ISOLATED demo data                             │
│  └── Organisation admins can create/manage their own demo candidates      │
└─────────────────────────────────────────────────────────────────────────────┘

## CORE REQUIREMENTS

### 1. Mode 1: Public Demo (No Organisation)
```php
// User without organisation
$user = User::create(['organisation_id' => null]);

// Can see and vote in public demo
$publicDemo = Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->whereNull('organisation_id')  // Public demo data
    ->first();

// Uses public demo components:
// - DemoPost with organisation_id = NULL
// - DemoCandidacy with organisation_id = NULL
// - DemoCode with organisation_id = NULL
// - DemoVote with organisation_id = NULL
// - DemoResult with organisation_id = NULL
```

### 2. Mode 2: Organisation-Specific Demo (Has Organisation)
```php
// User belongs to Organisation 1
$user = User::create(['organisation_id' => 1]);

// Can only see Org 1's demo data
$orgDemo = Election::where('type', 'demo')
    ->where('organisation_id', 1)  // Org-specific demo
    ->first();

// Uses org-specific demo components with org_id = 1:
// - DemoPost with organisation_id = 1
// - DemoCandidacy with organisation_id = 1
// - DemoCode with organisation_id = 1
// - DemoVote with organisation_id = 1
// - DemoResult with organisation_id = 1

// Organisation admins can create custom candidates
$post = DemoPost::create([
    'election_id' => $orgDemo->id,
    'name' => 'President',
    'organisation_id' => 1  // Explicit org context
]);

DemoCandidacy::create([
    'post_id' => $post->id,
    'name' => 'John Doe',
    'organisation_id' => 1
]);
```

## DATABASE SCHEMA REQUIREMENTS

All demo tables MUST have nullable organisation_id:

```sql
-- demo_posts table
CREATE TABLE demo_posts (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    election_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    nepali_name VARCHAR(255),
    position_order INT,
    required_number INT,
    organisation_id BIGINT UNSIGNED NULL,  -- NULL for Mode 1, value for Mode 2
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_organisation_id (organisation_id),
    FOREIGN KEY (election_id, organisation_id) REFERENCES elections(id, organisation_id)
);

-- demo_candidacies table
CREATE TABLE demo_candidacies (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    post_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    name VARCHAR(255) NOT NULL,
    nepali_name VARCHAR(255),
    image VARCHAR(255),
    symbol VARCHAR(255),
    organisation_id BIGINT UNSIGNED NULL,  -- NULL for Mode 1, value for Mode 2
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_organisation_id (organisation_id),
    FOREIGN KEY (post_id, organisation_id) REFERENCES demo_posts(id, organisation_id)
);
```

## MODEL UPDATES

### DemoPost Model
```php
// app/Models/DemoPost.php
use App\Traits\BelongsToTenant;

class DemoPost extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'election_id',
        'name',
        'nepali_name',
        'position_order',
        'required_number',
        'organisation_id'  // NULL for Mode 1, value for Mode 2
    ];
    
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }
    
    public function candidacies()
    {
        return $this->hasMany(DemoCandidacy::class, 'post_id');
    }
}
```

### DemoCandidacy Model
```php
// app/Models/DemoCandidacy.php
use App\Traits\BelongsToTenant;

class DemoCandidacy extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'post_id',
        'user_id',
        'name',
        'nepali_name',
        'image',
        'symbol',
        'organisation_id'  // NULL for Mode 1, value for Mode 2
    ];
    
    public function post()
    {
        return $this->belongsTo(DemoPost::class, 'post_id');
    }
}
```

## TESTS TO IMPLEMENT (TDD APPROACH)

### Test 1: Mode 1 - Public Demo Works Without Organisation
```php
// tests/Feature/DemoVoteMode1Test.php

public function test_mode1_public_demo_allows_voting_without_org()
{
    // Create user with NO organisation
    $user = User::factory()->create(['organisation_id' => null]);
    $this->actingAs($user);
    session(['current_organisation_id' => null]);
    
    // Create public demo election (NULL org)
    $election = Election::withoutGlobalScopes()->create([
        'name' => 'Public Demo',
        'slug' => 'public-demo',
        'type' => 'demo',
        'organisation_id' => null
    ]);
    
    // Create public demo post (NULL org)
    $post = DemoPost::create([
        'election_id' => $election->id,
        'name' => 'President',
        'organisation_id' => null
    ]);
    
    // Create public demo candidates (NULL org)
    $candidate1 = DemoCandidacy::create([
        'post_id' => $post->id,
        'name' => 'Candidate A',
        'organisation_id' => null
    ]);
    
    $candidate2 = DemoCandidacy::create([
        'post_id' => $post->id,
        'name' => 'Candidate B',
        'organisation_id' => null
    ]);
    
    // Create demo code (NULL org)
    $code = DemoCode::create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'code1' => 'PUBLIC1',
        'code2' => 'PUBLIC2',
        'organisation_id' => null
    ]);
    
    // Verify user can see public demo data
    $this->assertEquals(1, DemoPost::count());
    $this->assertEquals(2, DemoCandidacy::count());
    $this->assertEquals(1, DemoCode::count());
    
    // Simulate voting
    $vote = DemoVote::create([
        'election_id' => $election->id,
        'voting_code' => 'hashed_value',
        'organisation_id' => null
    ]);
    
    DemoResult::create([
        'vote_id' => $vote->id,
        'candidate_id' => $candidate1->id,
        'organisation_id' => null
    ]);
    
    // Verify vote was recorded with NULL org
    $this->assertDatabaseHas('demo_votes', [
        'id' => $vote->id,
        'organisation_id' => null
    ]);
}
```

### Test 2: Mode 2 - Organisation-Specific Demo Works
```php
// tests/Feature/DemoVoteMode2Test.php

public function test_mode2_org_demo_only_shows_org_specific_data()
{
    // Create Organisation 1 user
    $user1 = User::factory()->create(['organisation_id' => 1]);
    $this->actingAs($user1);
    session(['current_organisation_id' => 1]);
    
    // Create Organisation 2 user
    $user2 = User::factory()->create(['organisation_id' => 2]);
    
    // Create Org 1 demo election
    $election1 = Election::create([
        'name' => 'Org 1 Demo',
        'slug' => 'org1-demo',
        'type' => 'demo',
        'organisation_id' => 1
    ]);
    
    // Create Org 1 demo posts and candidates
    $post1 = DemoPost::create([
        'election_id' => $election1->id,
        'name' => 'President',
        'organisation_id' => 1
    ]);
    
    DemoCandidacy::create([
        'post_id' => $post1->id,
        'name' => 'Org 1 Candidate A',
        'organisation_id' => 1
    ]);
    
    // Create Org 2 demo election (separate)
    $election2 = Election::create([
        'name' => 'Org 2 Demo',
        'slug' => 'org2-demo',
        'type' => 'demo',
        'organisation_id' => 2
    ]);
    
    $post2 = DemoPost::create([
        'election_id' => $election2->id,
        'name' => 'President',
        'organisation_id' => 2
    ]);
    
    DemoCandidacy::create([
        'post_id' => $post2->id,
        'name' => 'Org 2 Candidate B',
        'organisation_id' => 2
    ]);
    
    // As Org 1 user, should ONLY see Org 1 data
    $this->actingAs($user1);
    session(['current_organisation_id' => 1]);
    
    $this->assertEquals(1, DemoPost::count());
    $this->assertEquals('President', DemoPost::first()->name);
    $this->assertEquals(1, DemoPost::first()->organisation_id);
    
    $this->assertEquals(1, DemoCandidacy::count());
    $this->assertEquals('Org 1 Candidate A', DemoCandidacy::first()->name);
}
```

### Test 3: Mode 1 and Mode 2 Data Isolation
```php
// tests/Feature/DemoVoteIsolationTest.php

public function test_mode1_and_mode2_data_are_completely_isolated()
{
    // MODE 1: Public demo data (NULL org)
    session(['current_organisation_id' => null]);
    $publicElection = Election::withoutGlobalScopes()->create([
        'name' => 'Public Demo',
        'type' => 'demo',
        'organisation_id' => null
    ]);
    
    $publicPost = DemoPost::create([
        'election_id' => $publicElection->id,
        'name' => 'Public Post',
        'organisation_id' => null
    ]);
    
    $publicCandidate = DemoCandidacy::create([
        'post_id' => $publicPost->id,
        'name' => 'Public Candidate',
        'organisation_id' => null
    ]);
    
    // MODE 2: Org 1 demo data (org_id = 1)
    session(['current_organisation_id' => 1]);
    $org1Election = Election::create([
        'name' => 'Org 1 Demo',
        'type' => 'demo',
        'organisation_id' => 1
    ]);
    
    $org1Post = DemoPost::create([
        'election_id' => $org1Election->id,
        'name' => 'Org 1 Post',
        'organisation_id' => 1
    ]);
    
    $org1Candidate = DemoCandidacy::create([
        'post_id' => $org1Post->id,
        'name' => 'Org 1 Candidate',
        'organisation_id' => 1
    ]);
    
    // MODE 2: Org 2 demo data (org_id = 2)
    session(['current_organisation_id' => 2]);
    $org2Election = Election::create([
        'name' => 'Org 2 Demo',
        'type' => 'demo',
        'organisation_id' => 2
    ]);
    
    $org2Post = DemoPost::create([
        'election_id' => $org2Election->id,
        'name' => 'Org 2 Post',
        'organisation_id' => 2
    ]);
    
    $org2Candidate = DemoCandidacy::create([
        'post_id' => $org2Post->id,
        'name' => 'Org 2 Candidate',
        'organisation_id' => 2
    ]);
    
    // VERIFY ISOLATION
    
    // MODE 1 user sees only NULL org data
    session(['current_organisation_id' => null]);
    $this->assertCount(1, DemoPost::all());
    $this->assertEquals('Public Post', DemoPost::first()->name);
    
    // MODE 2 Org 1 user sees only org 1 data
    session(['current_organisation_id' => 1]);
    $this->assertCount(1, DemoPost::all());
    $this->assertEquals('Org 1 Post', DemoPost::first()->name);
    
    // MODE 2 Org 2 user sees only org 2 data
    session(['current_organisation_id' => 2]);
    $this->assertCount(1, DemoPost::all());
    $this->assertEquals('Org 2 Post', DemoPost::first()->name);
}
```

### Test 4: Organisation Admins Can Create Custom Demo Candidates
```php
// tests/Feature/DemoCandidateCreationTest.php

public function test_org_admin_can_create_custom_demo_candidates()
{
    // Org admin user
    $admin = User::factory()->create(['organisation_id' => 5]);
    $this->actingAs($admin);
    session(['current_organisation_id' => 5]);
    
    // Create org-specific demo election
    $election = Election::create([
        'name' => 'Org 5 Demo',
        'type' => 'demo',
        'organisation_id' => 5
    ]);
    
    // Create custom post
    $post = DemoPost::create([
        'election_id' => $election->id,
        'name' => 'Custom Position',
        'nepali_name' => 'कस्टम पद',
        'position_order' => 1,
        'required_number' => 2,
        'organisation_id' => 5
    ]);
    
    // Create custom candidates
    $candidate1 = DemoCandidacy::create([
        'post_id' => $post->id,
        'name' => 'Custom Candidate 1',
        'nepali_name' => 'कस्टम उम्मेदवार १',
        'image' => 'candidate1.jpg',
        'symbol' => 'symbol1.png',
        'organisation_id' => 5
    ]);
    
    $candidate2 = DemoCandidacy::create([
        'post_id' => $post->id,
        'name' => 'Custom Candidate 2',
        'nepali_name' => 'कस्टम उम्मेदवार २',
        'image' => 'candidate2.jpg',
        'symbol' => 'symbol2.png',
        'organisation_id' => 5
    ]);
    
    // Verify custom data exists with correct org
    $this->assertDatabaseHas('demo_posts', [
        'id' => $post->id,
        'organisation_id' => 5,
        'name' => 'Custom Position'
    ]);
    
    $this->assertDatabaseHas('demo_candidacies', [
        'id' => $candidate1->id,
        'organisation_id' => 5,
        'name' => 'Custom Candidate 1'
    ]);
    
    $this->assertDatabaseHas('demo_candidacies', [
        'id' => $candidate2->id,
        'organisation_id' => 5,
        'name' => 'Custom Candidate 2'
    ]);
    
    // Other orgs cannot see this data
    $otherUser = User::factory()->create(['organisation_id' => 6]);
    $this->actingAs($otherUser);
    session(['current_organisation_id' => 6]);
    
    $this->assertCount(0, DemoPost::all());
    $this->assertCount(0, DemoCandidacy::all());
}
```

### Test 5: Complete Demo Voting Flow in Both Modes
```php
// tests/Feature/DemoVoteCompleteFlowTest.php

public function test_complete_demo_voting_flow_mode1()
{
    // MODE 1: No organisation
    $user = User::factory()->create(['organisation_id' => null]);
    $this->actingAs($user);
    session(['current_organisation_id' => null]);
    
    // Get public demo election
    $election = Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->whereNull('organisation_id')
        ->first();
    
    // Get demo code
    $code = DemoCode::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();
    
    // Submit vote
    $vote = DemoVote::create([
        'election_id' => $election->id,
        'voting_code' => Hash::make($code->code1 . $code->code2),
        'organisation_id' => null
    ]);
    
    // Get candidates for this election
    $posts = DemoPost::where('election_id', $election->id)->get();
    foreach ($posts as $post) {
        $candidates = DemoCandidacy::where('post_id', $post->id)->get();
        foreach ($candidates as $candidate) {
            DemoResult::create([
                'vote_id' => $vote->id,
                'candidate_id' => $candidate->id,
                'organisation_id' => null
            ]);
        }
    }
    
    // Verify vote recorded
    $this->assertDatabaseHas('demo_votes', [
        'id' => $vote->id,
        'organisation_id' => null
    ]);
    
    $this->assertGreaterThan(0, DemoResult::where('vote_id', $vote->id)->count());
}

public function test_complete_demo_voting_flow_mode2()
{
    // MODE 2: Organisation 1
    $user = User::factory()->create(['organisation_id' => 1]);
    $this->actingAs($user);
    session(['current_organisation_id' => 1]);
    
    // Get org 1 demo election
    $election = Election::where('type', 'demo')
        ->where('organisation_id', 1)
        ->first();
    
    // Get org 1 demo code
    $code = DemoCode::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();
    
    // Submit vote
    $vote = DemoVote::create([
        'election_id' => $election->id,
        'voting_code' => Hash::make($code->code1 . $code->code2),
        'organisation_id' => 1
    ]);
    
    // Get candidates for this election
    $posts = DemoPost::where('election_id', $election->id)->get();
    foreach ($posts as $post) {
        $candidates = DemoCandidacy::where('post_id', $post->id)->get();
        foreach ($candidates as $candidate) {
            DemoResult::create([
                'vote_id' => $vote->id,
                'candidate_id' => $candidate->id,
                'organisation_id' => 1
            ]);
        }
    }
    
    // Verify vote recorded with org 1
    $this->assertDatabaseHas('demo_votes', [
        'id' => $vote->id,
        'organisation_id' => 1
    ]);
    
    $this->assertGreaterThan(0, DemoResult::where('vote_id', $vote->id)->count());
    
    // Org 2 user cannot see this data
    $user2 = User::factory()->create(['organisation_id' => 2]);
    $this->actingAs($user2);
    session(['current_organisation_id' => 2]);
    
    $this->assertCount(0, DemoVote::where('election_id', $election->id)->get());
}
```

## DEMO SETUP COMMAND UPDATES

```php
// app/Console/Commands/SetupDemoElection.php

public function handle()
{
    $orgId = $this->option('org') ?? session('current_organisation_id');
    $mode = $orgId ? "MODE 2 (Organisation ID: {$orgId})" : "MODE 1 (Public Demo)";
    
    $this->info("🚀 Setting up demo election in {$mode}");
    
    // Set context
    session(['current_organisation_id' => $orgId]);
    
    // Create or get demo election
    $election = Election::withoutGlobalScopes()->updateOrCreate(
        [
            'type' => 'demo',
            'organisation_id' => $orgId
        ],
        [
            'name' => $orgId ? "Organisation {$orgId} Demo" : "Public Demo Election",
            'slug' => $orgId ? "org-{$orgId}-demo" : "public-demo",
            'description' => $orgId 
                ? "Demo election for Organisation {$orgId}"
                : "Public demo election for testing",
            'start_date' => now(),
            'end_date' => now()->addDays(365)
        ]
    );
    
    $this->info("✅ Demo Election: {$election->name}");
    
    // Create default posts if none exist
    if (DemoPost::where('election_id', $election->id)->count() == 0) {
        $post1 = DemoPost::create([
            'election_id' => $election->id,
            'name' => 'President',
            'nepali_name' => 'राष्ट्रपति',
            'position_order' => 1,
            'required_number' => 1,
            'organisation_id' => $orgId
        ]);
        
        $post2 = DemoPost::create([
            'election_id' => $election->id,
            'name' => 'Vice President',
            'nepali_name' => 'उपाध्यक्ष',
            'position_order' => 2,
            'required_number' => 1,
            'organisation_id' => $orgId
        ]);
        
        $this->info("✅ Created 2 demo posts");
        
        // Create default candidates
        DemoCandidacy::create([
            'post_id' => $post1->id,
            'name' => 'Candidate A',
            'nepali_name' => 'उम्मेदवार ए',
            'organisation_id' => $orgId
        ]);
        
        DemoCandidacy::create([
            'post_id' => $post1->id,
            'name' => 'Candidate B',
            'nepali_name' => 'उम्मेदवार बी',
            'organisation_id' => $orgId
        ]);
        
        DemoCandidacy::create([
            'post_id' => $post2->id,
            'name' => 'Candidate C',
            'nepali_name' => 'उम्मेदवार सी',
            'organisation_id' => $orgId
        ]);
        
        $this->info("✅ Created 3 demo candidates");
    }
    
    $this->table(['Component', 'Count'], [
        ['Demo Posts', DemoPost::where('election_id', $election->id)->count()],
        ['Demo Candidates', DemoCandidacy::whereIn('post_id', 
            DemoPost::where('election_id', $election->id)->pluck('id')
        )->count()],
    ]);
}
```

## EXPECTED TEST RESULTS

```bash
$ php artisan test --filter=DemoVote

PASS  Tests\Feature\DemoVoteMode1Test
✓ mode1 public demo allows voting without org

PASS  Tests\Feature\DemoVoteMode2Test
✓ mode2 org demo only shows org specific data

PASS  Tests\Feature\DemoVoteIsolationTest
✓ mode1 and mode2 data are completely isolated

PASS  Tests\Feature\DemoCandidateCreationTest
✓ org admin can create custom demo candidates

PASS  Tests\Feature\DemoVoteCompleteFlowTest
✓ complete demo voting flow mode1
✓ complete demo voting flow mode2

Tests:  6 passed, 0 failed
```

## VERIFICATION QUERIES

```sql
-- Check Mode 1 data (public demo)
SELECT COUNT(*) FROM demo_posts WHERE organisation_id IS NULL;
SELECT COUNT(*) FROM demo_candidacies WHERE organisation_id IS NULL;
SELECT COUNT(*) FROM demo_votes WHERE organisation_id IS NULL;

-- Check Mode 2 data for each organisation
SELECT organisation_id, COUNT(*) 
FROM demo_posts 
WHERE organisation_id IS NOT NULL 
GROUP BY organisation_id;

-- Verify no cross-org data leakage
SELECT p.organisation_id as post_org, 
       c.organisation_id as candidate_org,
       COUNT(*) 
FROM demo_posts p
JOIN demo_candidacies c ON p.id = c.post_id
WHERE p.organisation_id != c.organisation_id
GROUP BY p.organisation_id, c.organisation_id;
-- Expected: 0 rows

-- Verify votes match candidate orgs
SELECT v.organisation_id as vote_org,
       r.organisation_id as result_org,
       COUNT(*)
FROM demo_votes v
JOIN demo_results r ON v.id = r.vote_id
WHERE v.organisation_id != r.organisation_id
GROUP BY v.organisation_id, r.organisation_id;
-- Expected: 0 rows
```

## SUMMARY CHECKLIST

- [ ] DemoPost model has BelongsToTenant trait
- [ ] DemoCandidacy model has BelongsToTenant trait
- [ ] Both models have 'organisation_id' in $fillable
- [ ] Database migrations have nullable organisation_id
- [ ] Mode 1: Users without org can vote using public data
- [ ] Mode 2: Users with org see only their org's demo data
- [ ] Organisation admins can create custom demo candidates
- [ ] Complete data isolation between orgs and public demo
- [ ] Demo setup command works for both modes
- [ ] All 6+ tests pass
```

This implementation ensures COMPLETE isolation between:
- Public demo data (NULL org) → visible to everyone
- Organisation-specific demo data (org_id = X) → visible only to that organisation
- Each organisation has their own isolated demo environment
## 📋 **PROMPT INSTRUCTIONS: Enhanced `php artisan demo:setup` Command for Both Modes**

```
## CONTEXT
The `php artisan demo:setup` command must work seamlessly for BOTH demo modes:

┌─────────────────────────────────────────────────────────────────────────────┐
│  MODE 1: Public Demo (No Organisation)                                     │
│  ├── Command: php artisan demo:setup                                       │
│  ├── Creates: Public demo election with organisation_id = NULL            │
│  ├── Creates: Public demo posts with organisation_id = NULL               │
│  ├── Creates: Public demo candidates with organisation_id = NULL          │
│  └── Visible to: ALL users regardless of organisation                     │
├─────────────────────────────────────────────────────────────────────────────┤
│  MODE 2: Organisation-Specific Demo (Has Organisation)                    │
│  ├── Command: php artisan demo:setup --org=5                              │
│  ├── Creates: Org-specific demo election with organisation_id = 5         │
│  ├── Creates: Org-specific demo posts with organisation_id = 5            │
│  ├── Creates: Org-specific demo candidates with organisation_id = 5       │
│  └── Visible to: ONLY users belonging to organisation 5                   │
└─────────────────────────────────────────────────────────────────────────────┘

## COMMAND REQUIREMENTS

### 1. Command Signature
```php
// app/Console/Commands/SetupDemoElection.php

protected $signature = 'demo:setup
                        {--org= : Organisation ID to create demo for (uses session if not provided)}
                        {--force : Force recreation of demo data}
                        {--clean : Delete existing demo data without confirmation}';
```

### 2. Mode Detection Logic
```php
public function handle()
{
    // Determine which mode we're running in
    $orgId = $this->option('org');
    $sessionOrg = session('current_organisation_id');
    
    if ($orgId) {
        // MODE 2: Explicit organisation ID provided
        $mode = 'organisation';
        $modeDescription = "MODE 2 (Organisation ID: {$orgId})";
    } elseif ($sessionOrg) {
        // MODE 2: Using session organisation context
        $orgId = $sessionOrg;
        $mode = 'organisation';
        $modeDescription = "MODE 2 (Organisation ID: {$orgId} from session)";
    } else {
        // MODE 1: No organisation - public demo
        $orgId = null;
        $mode = 'public';
        $modeDescription = "MODE 1 (Public Demo - No Organisation)";
    }
    
    $this->info("╔════════════════════════════════════════════════════════╗");
    $this->info("║  Demo Election Setup                                    ║");
    $this->info("╠════════════════════════════════════════════════════════╣");
    $this->info("║  Mode: {$modeDescription}");
    $this->info("╚════════════════════════════════════════════════════════╝");
    $this->newLine();
}
```

### 3. Context Setting
```php
// Set the correct context for creation
if ($orgId === null) {
    // MODE 1: Public demo - session NULL
    session(['current_organisation_id' => null]);
    $this->info("🌐 Setting context: Public Demo (organisation_id = NULL)");
} else {
    // MODE 2: Organisation demo - session = orgId
    session(['current_organisation_id' => $orgId]);
    $this->info("🏢 Setting context: Organisation {$orgId}");
}
```

### 4. Clean Existing Data
```php
// Handle existing demo data
if ($this->option('force') || $this->option('clean')) {
    $this->warn("⚠️  Removing existing demo data for this context...");
    
    // Delete in correct order (respect foreign keys)
    DemoResult::whereIn('vote_id', 
        DemoVote::where('election_id', 
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $orgId)
                ->pluck('id')
        )->pluck('id')
    )->delete();
    
    DemoVote::whereIn('election_id',
        Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $orgId)
            ->pluck('id')
    )->delete();
    
    DemoCode::whereIn('election_id',
        Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $orgId)
            ->pluck('id')
    )->delete();
    
    DemoCandidacy::whereIn('post_id',
        DemoPost::whereIn('election_id',
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $orgId)
                ->pluck('id')
        )->pluck('id')
    )->delete();
    
    DemoPost::whereIn('election_id',
        Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $orgId)
            ->pluck('id')
    )->delete();
    
    Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->where('organisation_id', $orgId)
        ->delete();
    
    $this->info("✅ Existing demo data removed.");
}
```

### 5. Create Demo Election
```php
// Create demo election (organisation_id auto-filled by trait)
$election = Election::create([
    'name' => $orgId ? "Organisation {$orgId} Demo" : "Public Demo Election",
    'slug' => $orgId ? "org-{$orgId}-demo" : "public-demo",
    'type' => 'demo',
    'description' => $orgId 
        ? "Demo election for testing within Organisation {$orgId}"
        : "Public demo election for testing the voting system",
    'start_date' => now()->format('Y-m-d'),
    'end_date' => now()->addDays(365)->format('Y-m-d'),
]);

$this->info("✅ Demo Election Created:");
$this->table(
    ['Property', 'Value'],
    [
        ['ID', $election->id],
        ['Name', $election->name],
        ['Type', $election->type],
        ['Organisation ID', $election->organisation_id ?? 'NULL (Public Demo)'],
        ['Slug', $election->slug],
    ]
);
```

### 6. Create Demo Posts
```php
// Define posts based on mode
$posts = [
    [
        'name' => 'President',
        'nepali_name' => 'राष्ट्रपति',
        'position_order' => 1,
        'required_number' => 1,
    ],
    [
        'name' => 'Vice President',
        'nepali_name' => 'उपाध्यक्ष',
        'position_order' => 2,
        'required_number' => 1,
    ],
    [
        'name' => 'General Secretary',
        'nepali_name' => 'महासचिव',
        'position_order' => 3,
        'required_number' => 1,
    ],
];

$createdPosts = [];
foreach ($posts as $postData) {
    $post = DemoPost::create(array_merge($postData, [
        'election_id' => $election->id,
    ]));
    $createdPosts[] = $post;
    $this->line("   📌 Created post: {$post->name}");
}

$this->info("✅ Created " . count($createdPosts) . " demo posts");
```

### 7. Create Demo Candidates
```php
// Define candidates based on mode
$candidates = [
    // President candidates
    ['post_index' => 0, 'name' => 'Ram Bahadur Thapa', 'nepali_name' => 'राम बहादुर थापा'],
    ['post_index' => 0, 'name' => 'Sita Devi Sharma', 'nepali_name' => 'सीता देवी शर्मा'],
    ['post_index' => 0, 'name' => 'Hari Prasad Poudel', 'nepali_name' => 'हरि प्रसाद पौडेल'],
    
    // Vice President candidates
    ['post_index' => 1, 'name' => 'Gita Kumari Rai', 'nepali_name' => 'गीता कुमारी राई'],
    ['post_index' => 1, 'name' => 'Krishna Bahadur Gurung', 'nepali_name' => 'कृष्ण बहादुर गुरुङ'],
    
    // General Secretary candidates
    ['post_index' => 2, 'name' => 'Mina Tamang', 'nepali_name' => 'मिना तामाङ'],
    ['post_index' => 2, 'name' => 'Suman Khadka', 'nepali_name' => 'सुमन खड्का'],
];

$createdCandidates = [];
foreach ($candidates as $candidateData) {
    $post = $createdPosts[$candidateData['post_index']];
    
    $candidate = DemoCandidacy::create([
        'post_id' => $post->id,
        'name' => $candidateData['name'],
        'nepali_name' => $candidateData['nepali_name'],
        'image' => "candidate_{$candidateData['post_index']}.jpg",
        'symbol' => "symbol_{$candidateData['post_index']}.png",
    ]);
    
    $createdCandidates[] = $candidate;
    $this->line("   👤 Created candidate: {$candidate->name} for {$post->name}");
}

$this->info("✅ Created " . count($createdCandidates) . " demo candidates");
```

### 8. Create Demo Codes
```php
// Create demo codes for testing
$codes = [
    ['code1' => 'DEMO111', 'code2' => 'CODE111'],
    ['code1' => 'DEMO222', 'code2' => 'CODE222'],
    ['code1' => 'DEMO333', 'code2' => 'CODE333'],
];

$createdCodes = [];
foreach ($codes as $index => $codeData) {
    $code = DemoCode::create([
        'code1' => $codeData['code1'],
        'code2' => $codeData['code2'],
        'user_id' => auth()->id(), // Will be assigned when user logs in
        'election_id' => $election->id,
    ]);
    $createdCodes[] = $code;
    $this->line("   🔑 Created code: {$codeData['code1']}-{$codeData['code2']}");
}

$this->info("✅ Created " . count($createdCodes) . " demo codes");
```

### 9. Summary Table
```php
$this->newLine();
$this->info("📊 Setup Summary:");
$this->table(
    ['Component', 'Count', 'Organisation ID'],
    [
        ['Demo Election', 1, $election->organisation_id ?? 'NULL'],
        ['Demo Posts', count($createdPosts), $createdPosts[0]->organisation_id ?? 'NULL'],
        ['Demo Candidates', count($createdCandidates), $createdCandidates[0]->organisation_id ?? 'NULL'],
        ['Demo Codes', count($createdCodes), $createdCodes[0]->organisation_id ?? 'NULL'],
    ]
);

$this->newLine();
$this->info("✅ Demo setup completed successfully!");

if ($orgId === null) {
    $this->warn("   This is a PUBLIC demo - visible to ALL users");
    $this->warn("   Organisation ID: NULL");
} else {
    $this->warn("   This is an ORGANISATION-SPECIFIC demo");
    $this->warn("   Only visible to users in Organisation {$orgId}");
    $this->warn("   To access: php artisan demo:setup --org={$orgId}");
}
```

## TESTS FOR DEMO SETUP COMMAND

```php
// tests/Feature/DemoSetupCommandTest.php

public function test_demo_setup_command_works_in_mode1()
{
    // Clear any existing demo data
    Artisan::call('demo:setup', ['--clean' => true]);
    
    // Run demo:setup without org (MODE 1)
    Artisan::call('demo:setup');
    $output = Artisan::output();
    
    // Verify output indicates MODE 1
    $this->assertStringContainsString('MODE 1 (Public Demo)', $output);
    $this->assertStringContainsString('Organisation ID: NULL', $output);
    
    // Verify database records created with NULL org
    $this->assertDatabaseHas('elections', [
        'type' => 'demo',
        'organisation_id' => null
    ]);
    
    $this->assertDatabaseHas('demo_posts', [
        'organisation_id' => null
    ]);
    
    $this->assertDatabaseHas('demo_candidacies', [
        'organisation_id' => null
    ]);
    
    $this->assertDatabaseHas('demo_codes', [
        'organisation_id' => null
    ]);
}

public function test_demo_setup_command_works_in_mode2_with_explicit_org()
{
    // Run demo:setup with explicit org ID (MODE 2)
    Artisan::call('demo:setup', ['--org' => 5, '--force' => true]);
    $output = Artisan::output();
    
    // Verify output indicates MODE 2
    $this->assertStringContainsString('MODE 2 (Organisation ID: 5)', $output);
    $this->assertStringContainsString('Organisation ID: 5', $output);
    
    // Verify database records created with org_id = 5
    $this->assertDatabaseHas('elections', [
        'type' => 'demo',
        'organisation_id' => 5
    ]);
    
    $this->assertDatabaseHas('demo_posts', [
        'organisation_id' => 5
    ]);
    
    $this->assertDatabaseHas('demo_candidacies', [
        'organisation_id' => 5
    ]);
    
    $this->assertDatabaseHas('demo_codes', [
        'organisation_id' => 5
    ]);
}

public function test_demo_setup_command_works_in_mode2_with_session_context()
{
    // Set session context
    session(['current_organisation_id' => 8]);
    
    // Run demo:setup without org (should use session)
    Artisan::call('demo:setup', ['--force' => true]);
    $output = Artisan::output();
    
    // Verify output indicates MODE 2 with session org
    $this->assertStringContainsString('MODE 2 (Organisation ID: 8 from session)', $output);
    
    // Verify database records created with org_id = 8
    $this->assertDatabaseHas('elections', [
        'type' => 'demo',
        'organisation_id' => 8
    ]);
}

public function test_demo_setup_force_option_recreates_data()
{
    // First creation
    Artisan::call('demo:setup', ['--org' => 3]);
    $firstElection = Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->where('organisation_id', 3)
        ->first();
    $firstId = $firstElection->id;
    
    // Force recreate
    Artisan::call('demo:setup', ['--org' => 3, '--force' => true]);
    $secondElection = Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->where('organisation_id', 3)
        ->first();
    $secondId = $secondElection->id;
    
    // Should be different IDs (old one deleted, new one created)
    $this->assertNotEquals($firstId, $secondId);
}

public function test_demo_setup_creates_correct_number_of_records()
{
    Artisan::call('demo:setup', ['--org' => 4, '--force' => true]);
    
    // Count records
    $electionCount = Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->where('organisation_id', 4)
        ->count();
    
    $postCount = DemoPost::whereIn('election_id', 
        Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', 4)
            ->pluck('id')
    )->count();
    
    $candidacyCount = DemoCandidacy::whereIn('post_id',
        DemoPost::whereIn('election_id',
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', 4)
                ->pluck('id')
        )->pluck('id')
    )->count();
    
    $codeCount = DemoCode::whereIn('election_id',
        Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', 4)
            ->pluck('id')
    )->count();
    
    // Verify expected counts
    $this->assertEquals(1, $electionCount);
    $this->assertEquals(3, $postCount); // 3 posts
    $this->assertEquals(7, $candidacyCount); // 7 candidates
    $this->assertEquals(3, $codeCount); // 3 codes
}

public function test_demo_setup_mode1_and_mode2_data_are_isolated()
{
    // Create MODE 1 public demo
    Artisan::call('demo:setup', ['--force' => true]);
    
    // Create MODE 2 org 5 demo
    Artisan::call('demo:setup', ['--org' => 5, '--force' => true]);
    
    // Create MODE 2 org 6 demo
    Artisan::call('demo:setup', ['--org' => 6, '--force' => true]);
    
    // Verify each has its own data
    $publicPosts = DemoPost::whereIn('election_id',
        Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->whereNull('organisation_id')
            ->pluck('id')
    )->count();
    
    $org5Posts = DemoPost::whereIn('election_id',
        Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', 5)
            ->pluck('id')
    )->count();
    
    $org6Posts = DemoPost::whereIn('election_id',
        Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', 6)
            ->pluck('id')
    )->count();
    
    $this->assertEquals(3, $publicPosts);
    $this->assertEquals(3, $org5Posts);
    $this->assertEquals(3, $org6Posts);
}

public function test_demo_setup_clean_option_deletes_without_confirmation()
{
    // Create demo data
    Artisan::call('demo:setup', ['--org' => 7]);
    
    // Verify data exists
    $this->assertDatabaseHas('elections', [
        'type' => 'demo',
        'organisation_id' => 7
    ]);
    
    // Run clean
    Artisan::call('demo:setup', ['--org' => 7, '--clean' => true]);
    
    // Verify data is gone
    $this->assertDatabaseMissing('elections', [
        'type' => 'demo',
        'organisation_id' => 7
    ]);
}
```

## COMMAND USAGE EXAMPLES

```bash
# MODE 1: Create public demo (visible to everyone)
php artisan demo:setup

# MODE 2: Create demo for specific organisation
php artisan demo:setup --org=5

# MODE 2: Create demo using current session context
php artisan demo:setup

# Force recreate (delete existing and create new)
php artisan demo:setup --force
php artisan demo:setup --org=5 --force

# Clean delete (no confirmation)
php artisan demo:setup --clean
php artisan demo:setup --org=5 --clean

# View help
php artisan help demo:setup
```

## EXPECTED OUTPUT EXAMPLES

### MODE 1 Output:
```
╔════════════════════════════════════════════════════════╗
║  Demo Election Setup                                    ║
╠════════════════════════════════════════════════════════╣
║  Mode: MODE 1 (Public Demo - No Organisation)
╚════════════════════════════════════════════════════════╝

🌐 Setting context: Public Demo (organisation_id = NULL)

✅ Demo Election Created:
┌───────────────┬─────────────────────────────┐
│ Property      │ Value                       │
├───────────────┼─────────────────────────────┤
│ ID            │ 42                          │
│ Name          │ Public Demo Election         │
│ Type          │ demo                         │
│ Organisation ID │ NULL                       │
│ Slug          │ public-demo                  │
└───────────────┴─────────────────────────────┘

   📌 Created post: President
   📌 Created post: Vice President
   📌 Created post: General Secretary
✅ Created 3 demo posts

   👤 Created candidate: Ram Bahadur Thapa for President
   👤 Created candidate: Sita Devi Sharma for President
   👤 Created candidate: Hari Prasad Poudel for President
   👤 Created candidate: Gita Kumari Rai for Vice President
   👤 Created candidate: Krishna Bahadur Gurung for Vice President
   👤 Created candidate: Mina Tamang for General Secretary
   👤 Created candidate: Suman Khadka for General Secretary
✅ Created 7 demo candidates

   🔑 Created code: DEMO111-CODE111
   🔑 Created code: DEMO222-CODE222
   🔑 Created code: DEMO333-CODE333
✅ Created 3 demo codes

📊 Setup Summary:
┌──────────────────┬───────┬──────────────────┐
│ Component        │ Count │ Organisation ID  │
├──────────────────┼───────┼──────────────────┤
│ Demo Election    │ 1     │ NULL             │
│ Demo Posts       │ 3     │ NULL             │
│ Demo Candidates  │ 7     │ NULL             │
│ Demo Codes       │ 3     │ NULL             │
└──────────────────┴───────┴──────────────────┘

✅ Demo setup completed successfully!
⚠️   This is a PUBLIC demo - visible to ALL users
⚠️   Organisation ID: NULL
```

### MODE 2 Output:
```
╔════════════════════════════════════════════════════════╗
║  Demo Election Setup                                    ║
╠════════════════════════════════════════════════════════╣
║  Mode: MODE 2 (Organisation ID: 5)
╚════════════════════════════════════════════════════════╝

🏢 Setting context: Organisation 5

✅ Demo Election Created:
┌───────────────┬─────────────────────────────┐
│ Property      │ Value                       │
├───────────────┼─────────────────────────────┤
│ ID            │ 43                          │
│ Name          │ Organisation 5 Demo          │
│ Type          │ demo                         │
│ Organisation ID │ 5                           │
│ Slug          │ org-5-demo                   │
└───────────────┴─────────────────────────────┘

... (similar post/candidate/code creation)

📊 Setup Summary:
┌──────────────────┬───────┬──────────────────┐
│ Component        │ Count │ Organisation ID  │
├──────────────────┼───────┼──────────────────┤
│ Demo Election    │ 1     │ 5                │
│ Demo Posts       │ 3     │ 5                │
│ Demo Candidates  │ 7     │ 5                │
│ Demo Codes       │ 3     │ 5                │
└──────────────────┴───────┴──────────────────┘

✅ Demo setup completed successfully!
⚠️   This is an ORGANISATION-SPECIFIC demo
⚠️   Only visible to users in Organisation 5
⚠️   To access: php artisan demo:setup --org=5
```

## IMPLEMENTATION CHECKLIST

- [ ] Command signature includes --org, --force, --clean options
- [ ] Command detects mode correctly (explicit org, session org, or null)
- [ ] Sets session context appropriately for data creation
- [ ] Properly cleans existing data before recreation (when --force)
- [ ] Creates demo election with correct organisation_id
- [ ] Creates demo posts with correct organisation_id
- [ ] Creates demo candidates with correct organisation_id
- [ ] Creates demo codes with correct organisation_id
- [ ] Shows clear summary with organisation context
- [ ] All 6+ tests pass for both modes
- [ ] Mode 1 data has organisation_id = NULL
- [ ] Mode 2 data has organisation_id = provided org
- [ ] Data is properly isolated between modes
```

This ensures the `php artisan demo:setup` command works perfectly for BOTH modes, creating properly isolated demo data for public testing or organisation-specific testing.
 