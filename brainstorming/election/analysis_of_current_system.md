  backward compatibility with the demo/real voting system.

  Based on my analysis:

  Current Voting System (5-Step Workflow):
  1. Step 1: CodeController::create - User enters code
  2. Step 2: CodeController::showAgreement - User accepts agreement
  3. Step 3: VoteController::create - User votes
  4. Step 4: VoteController::verify - User verifies vote
  5. Step 5: Complete page (rendered via Inertia)

  Current Architecture:
  - Uses codes table to track verification state
  - Single source of truth: can_vote_now field (0 = needs verification, 1 = verified)
  - Uses votes table to store votes with candidate selections
  - IP validation for security
  - Slug-based voting system with voter progress tracking
  - VoterProgressService for step management

  Existing Database Tables:
  - users (main user table)
  - codes (verification codes with state tracking)
  - votes (cast votes with 60 candidate columns)
  - results (election results)
  - voter_registrations (newly added in Phase 1)
  - voter_slugs (slug-based voting access)

  Two Architectural Approaches from User's Reference:

  Approach 1 (from demo_or_real.md):
  - Use elections table with type enum ('demo', 'real')
  - Use voter_registrations table for multi-election support
  - Already implemented in Phase 1

  Approach 2 (from demo_or_real1.md):
  - Same-code-separate-tables using model inheritance
  - BaseVote abstract class with Vote (real) and DemoVote (demo) subclasses
  - Separate tables: votes/demo_votes, codes/demo_codes, results/demo_results
  - Service factory pattern

  My Recommendation:

  The Phase 1 implementation already provides the strongest foundation—elections table with type support,
  voter_registrations for tracking, and flexible architecture without foreign key constraints. Rather than duplicating
  tables across demo and real systems, I should enhance the existing Code and Vote models by adding election_id fields,
  leveraging the elections enum type that's already in place. This avoids unnecessary complexity while maintaining
  backward compatibility and supporting multiple concurrent elections.

● Now I have a complete understanding of the voting system. Let me create a comprehensive backward-compatible plan for
  the demo/real voting system.

  FINAL PLAN: Backward-Compatible Demo/Real Voting System

  ---
  Executive Summary

  You have already implemented a solid Elections + VoterRegistrations foundation in Phase 1. This plan leverages that
  architecture to add demo/real voting capability without breaking existing voting workflow.

  Key Principle: Instead of duplicating tables (votes/demo_votes), we enhance the existing tables with election_id
  filtering and election type awareness, keeping code changes minimal.

  ---
  Current Voting Flow (5 Steps)
  ┌──────┬───────────────────────────────┬─────────────────────┬─────────────────────────┐
  │ Step │          Controller           │        Route        │       Key Action        │
  ├──────┼───────────────────────────────┼─────────────────────┼─────────────────────────┤
  │ 1    │ CodeController::create        │ slug.code.create    │ User enters 6-char code │
  ├──────┼───────────────────────────────┼─────────────────────┼─────────────────────────┤
  │ 2    │ CodeController::showAgreement │ slug.code.agreement │ User accepts agreement  │
  ├──────┼───────────────────────────────┼─────────────────────┼─────────────────────────┤
  │ 3    │ VoteController::create        │ slug.vote.create    │ User selects candidates │
  ├──────┼───────────────────────────────┼─────────────────────┼─────────────────────────┤
  │ 4    │ VoteController::verify        │ slug.vote.verify    │ User verifies choices   │
  ├──────┼───────────────────────────────┼─────────────────────┼─────────────────────────┤
  │ 5    │ Inertia render                │ slug.vote.complete  │ Final submission page   │
  └──────┴───────────────────────────────┴─────────────────────┴─────────────────────────┘
  Single Source of Truth: codes.can_vote_now field (0 = needs verification, 1 = verified)

  ---
  Architecture Analysis

  What's Already Built (Phase 1):

  ✅ Elections table with type enum ('demo', 'real')
  ✅ VoterRegistrations table (no foreign keys)
  ✅ Multi-election support designed in
  ✅ Election seeder creates demo + real elections

  What We Need to Add (Phase 2):

  - Link existing codes table to elections via election_id
  - Link existing votes table to elections via election_id
  - Filter all queries by election type
  - Create demo/real election selection flow
  - Maintain backward compatibility

  ---
  Recommended Approach: Hybrid Model

  NOT table duplication (too complex)
  YES to election-scoped filtering (minimal changes)

  Diagram:

  Elections Table (demo_or_real_voting)
  ├── id=1, type='demo', slug='demo-election', status='active'
  └── id=2, type='real', slug='real-election', status='active'

  Codes Table (shared, filtered by election_id)
  ├── id=101, user_id=1, election_id=1, can_vote_now=1 ← DEMO
  ├── id=102, user_id=2, election_id=1, can_vote_now=0 ← DEMO
  └── id=201, user_id=1, election_id=2, can_vote_now=1 ← REAL

  Votes Table (shared, filtered by election_id)
  ├── id=501, user_id=1, election_id=1, candidate_01=X ← DEMO
  └── id=502, user_id=1, election_id=2, candidate_01=Y ← REAL

  ---
  Implementation Steps (In Order)

  STEP 1: Database Migrations

  Create 3 migrations to add election_id to existing tables:

  php artisan make:migration add_election_id_to_codes_table
  php artisan make:migration add_election_id_to_votes_table
  php artisan make:migration add_election_id_to_results_table

  Migration Content (example for codes):

  Schema::table('codes', function (Blueprint $table) {
      $table->foreignId('election_id')
            ->nullable()
            ->after('user_id')
            ->constrained('elections')
            ->cascadeOnDelete();

      // Unique constraint: one code per user per election
      $table->unique(['user_id', 'election_id']);

      // Index for frequent queries
      $table->index(['election_id', 'can_vote_now']);
  });

  Migration Data Logic:
  - Default existing codes to election_id=1 (first/demo election from seeder)
  - Allows gradual migration without breaking existing data
  - Fresh installations get proper election_id from the start

  ---
  STEP 2: Model Updates

  Update Code Model:
  // app/Models/Code.php
  class Code extends Model {
      protected $fillable = [
          'user_id',
          'election_id',  // NEW
          'code1',
          // ... rest of fields
      ];

      public function election() {
          return $this->belongsTo(Election::class);
      }
  }

  Update Vote Model:
  // app/Models/Vote.php
  class Vote extends Model {
      protected $fillable = [
          'user_id',
          'election_id',  // NEW
          'candidate_01', // ... rest
      ];

      public function election() {
          return $this->belongsTo(Election::class);
      }
  }

  Update Election Model (from Phase 1):
  // app/Models/Election.php
  class Election extends Model {
      public function codes() {
          return $this->hasMany(Code::class);
      }

      public function votes() {
          return $this->hasMany(Vote::class);
      }

      // Helper methods
      public function isDemo(): bool {
          return $this->type === 'demo';
      }

      public function isReal(): bool {
          return $this->type === 'real';
      }
  }

  ---
  STEP 3: Route Enhancements

  New Routes for Election Selection:

  Add to routes/election/electionRoutes.php:

  // NEW: Election selection gateway
  Route::get('/election/select', [ElectionController::class, 'selectElection'])
      ->name('election.select');

  Route::post('/election/select', [ElectionController::class, 'storeElection'])
      ->name('election.store');

  // Existing 5-step voting flow now scoped to election
  Route::prefix('/{election:slug}')->middleware('election.access')->group(function () {
      // Step 1-5 routes remain unchanged
      Route::get('code/create', [CodeController::class, 'create'])->name('slug.code.create');
      // ... rest of voting routes
  });

  ---
  STEP 4: Controller Updates

  New ElectionController:

  // app/Http/Controllers/ElectionController.php
  class ElectionController extends Controller {
      public function selectElection(Request $request) {
          $user = auth()->user();

          // Get available elections (demo + real)
          $elections = Election::where('status', 'active')->get();

          return Inertia::render('Election/Select', [
              'elections' => $elections->map(fn($e) => [
                  'id' => $e->id,
                  'slug' => $e->slug,
                  'name' => $e->name,
                  'type' => $e->type,
                  'description' => $e->type === 'demo'
                      ? 'Test the voting system'
                      : 'Official election',
              ]),
          ]);
      }

      public function storeElection(Request $request) {
          $election = Election::findOrFail($request->election_id);

          // Store selected election in session
          session(['selected_election_id' => $election->id]);

          // Redirect to voting flow
          return redirect()->route('slug.code.create', ['election' => $election->slug]);
      }
  }

  Update CodeController to Use Election Context:

  // app/Http/Controllers/CodeController.php
  public function create(Request $request) {
      $user = $this->getUser($request);
      $election = $request->route('election') ??  // From route
                 Election::find(session('selected_election_id')); // From session

      // Get or create code FOR THIS ELECTION
      $code = $this->getOrCreateCode($user, $election);

      return Inertia::render('Code/CreateCode', [
          'election_type' => $election->type,
          'is_demo' => $election->isDemo(),
          // ... rest of data
      ]);
  }

  private function getOrCreateCode(User $user, Election $election): Code {
      $code = Code::where('user_id', $user->id)
                 ->where('election_id', $election->id)  // NEW
                 ->first();

      if (!$code) {
          $code = Code::create([
              'user_id' => $user->id,
              'election_id' => $election->id,  // NEW
              'code1' => $this->generateCode(),
              // ... rest of fields
          ]);
      }

      return $code;
  }

  Update VoteController Similarly:

  public function create(Request $request) {
      $voter = $request->attributes->get('voter') ?? auth()->user();
      $election = $request->route('election');

      // Get code FOR THIS ELECTION
      $code = Code::where('user_id', $voter->id)
                 ->where('election_id', $election->id)
                 ->first();

      // Get candidates for THIS ELECTION
      $national_posts = Post::where('election_id', $election->id)
                            ->where('is_national_wide', 1)
                            ->with('candidates')
                            ->get();

      return Inertia::render('Vote/CreateVotingPage', [
          'election_type' => $election->type,
          'national_posts' => $national_posts,
          // ...
      ]);
  }

  public function store(Request $request) {
      $voter = $request->attributes->get('voter') ?? auth()->user();
      $election = $request->route('election');

      // Create vote FOR THIS ELECTION
      $vote = Vote::create([
          'user_id' => $voter->id,
          'election_id' => $election->id,  // NEW
          'candidate_01' => $request->input('candidate_01'),
          // ... rest of candidates
      ]);

      return response()->json(['success' => true]);
  }

  ---
  STEP 5: Query Scope Helpers

  Add scopes to Code and Vote models for cleaner queries:

  // app/Models/Code.php
  class Code extends Model {
      public function scopeForElection($query, Election $election) {
          return $query->where('election_id', $election->id);
      }

      public function scopeForDemoElection($query) {
          return $query->whereHas('election', fn($q) => $q->where('type', 'demo'));
      }

      public function scopeForRealElection($query) {
          return $query->whereHas('election', fn($q) => $q->where('type', 'real'));
      }
  }

  // Usage in controllers:
  $code = Code::forElection($election)->where('user_id', $user->id)->first();
  $demoVotes = Code::forDemoElection()->count();

  ---
  STEP 6: Demo Data Cleanup (Optional)

  Add a scheduled command to clean demo election data:

  // app/Console/Kernel.php
  protected function schedule(Schedule $schedule) {
      // Cleanup demo data daily at 3 AM
      $schedule->call(function () {
          $demoElection = Election::where('type', 'demo')->first();
          if ($demoElection) {
              Code::where('election_id', $demoElection->id)->delete();
              Vote::where('election_id', $demoElection->id)->delete();
              Result::where('election_id', $demoElection->id)->delete();
          }
      })->dailyAt('03:00');
  }

  ---
  Backward Compatibility Strategy

  For Existing Elections (no election_id set):

  1. Default code queries check election_id IS NULL for old data
  2. Election selection flow is OPTIONAL - if no election is selected, use first active election
  3. All new elections MUST have election_id
  4. Gradual migration: old data works, new data is election-scoped

  Safe Fallback:

  // In CodeController::getOrCreateCode()
  $election = $election ?? Election::where('type', 'demo')->first();
  // Ensures we always have an election context

  ---
  Testing Strategy

  PHPUnit Tests to Add:

  // tests/Feature/DemoRealVotingTest.php
  class DemoRealVotingTest extends TestCase {
      public function test_demo_and_real_elections_are_separate() {
          $demo = Election::factory()->demo()->create();
          $real = Election::factory()->real()->create();

          $user = User::factory()->create();

          // Code for demo
          Code::factory()->create(['user_id' => $user->id, 'election_id' => $demo->id]);

          // Code for real (same user, different election)
          Code::factory()->create(['user_id' => $user->id, 'election_id' => $real->id]);

          // Each should be independent
          $this->assertEquals(1, Code::forElection($demo)->count());
          $this->assertEquals(1, Code::forElection($real)->count());
      }

      public function test_user_can_vote_in_demo_and_real() {
          // ...
      }

      public function test_demo_data_cleanup_works() {
          // ...
      }
  }

  ---
  Migration Checklist

  Phase 2a: Database & Models (Week 1)
  - Create 3 migrations (codes, votes, results)
  - Update Code, Vote, Election models
  - Add relationships and scopes
  - Test migrations with existing data

  Phase 2b: Controllers & Routes (Week 2)
  - Create ElectionController::selectElection
  - Update CodeController to use election_id
  - Update VoteController to use election_id
  - Add new routes for election selection
  - Test 5-step flow with election context

  Phase 2c: Frontend (Week 3)
  - Create Election/Select.vue page
  - Update Code/CreateCode.vue to show election type
  - Update Vote pages to show "Demo" vs "Real" badge
  - Add election context to all steps

  Phase 2d: Testing & Cleanup (Week 4)
  - Run full test suite
  - Verify backward compatibility
  - Test demo data cleanup
  - Load testing with multiple elections

  ---
  Why This Approach?
  ┌─────────────────────┬──────────────────────────────────────┬─────────────────────────────────┬─────────────────────┐
  │       Aspect        │    Hybrid Approach (Recommended)     │        Table Duplication        │  Single Table Flag  │
  ├─────────────────────┼──────────────────────────────────────┼─────────────────────────────────┼─────────────────────┤
  │ Code Changes        │ Minimal (election_id in queries)     │ Massive (duplicate controllers) │ Small (add if flag) │
  ├─────────────────────┼──────────────────────────────────────┼─────────────────────────────────┼─────────────────────┤
  │ Backward Compatible │ ✅ YES                               │ ❌ Breaks existing              │ ✅ YES              │
  ├─────────────────────┼──────────────────────────────────────┼─────────────────────────────────┼─────────────────────┤
  │ Performance         │ ✅ Good (indexes on election_id)     │ ⚠️ More tables                  │ ✅ Good             │
  ├─────────────────────┼──────────────────────────────────────┼─────────────────────────────────┼─────────────────────┤
  │ Data Separation     │ ✅ Clean (separate election records) │ ✅ Very clean                   │ ⚠️ Mixed data       │
  ├─────────────────────┼──────────────────────────────────────┼─────────────────────────────────┼─────────────────────┤
  │ Future Scaling      │ ✅ Supports many elections           │ ❌ One demo, one real only      │ ⚠️ Limited          │
  ├─────────────────────┼──────────────────────────────────────┼─────────────────────────────────┼─────────────────────┤
  │ Using Phase 1       │ ✅ Leverages elections table         │ ❌ Ignores it                   │ ⚠️ Partially        │
  └─────────────────────┴──────────────────────────────────────┴─────────────────────────────────┴─────────────────────┘
  ---
  Key Implementation Files (Summary)
  ┌─────────────────────────────────────────────┬───────────────────────────────┬───────────────────────┐
  │                    File                     │            Change             │        Impact         │
  ├─────────────────────────────────────────────┼───────────────────────────────┼───────────────────────┤
  │ database/migrations/*.php                   │ Add election_id columns       │ Database schema       │
  ├─────────────────────────────────────────────┼───────────────────────────────┼───────────────────────┤
  │ app/Models/Code.php                         │ Add election_id + scopes      │ Model relationships   │
  ├─────────────────────────────────────────────┼───────────────────────────────┼───────────────────────┤
  │ app/Models/Vote.php                         │ Add election_id + scopes      │ Model relationships   │
  ├─────────────────────────────────────────────┼───────────────────────────────┼───────────────────────┤
  │ app/Models/Election.php                     │ Add relations to codes/votes  │ Election connections  │
  ├─────────────────────────────────────────────┼───────────────────────────────┼───────────────────────┤
  │ app/Http/Controllers/ElectionController.php │ NEW                           │ Election selection UI │
  ├─────────────────────────────────────────────┼───────────────────────────────┼───────────────────────┤
  │ app/Http/Controllers/CodeController.php     │ Use election context          │ Step 1-2 scoping      │
  ├─────────────────────────────────────────────┼───────────────────────────────┼───────────────────────┤
  │ app/Http/Controllers/VoteController.php     │ Use election context          │ Step 3-5 scoping      │
  ├─────────────────────────────────────────────┼───────────────────────────────┼───────────────────────┤
  │ routes/election/electionRoutes.php          │ Add election selection routes │ New routing           │
  └─────────────────────────────────────────────┴───────────────────────────────┴───────────────────────┘
  ---
  Risk Mitigation

  Risk 1: Breaking Existing Votes
  - Mitigation: Default all old codes/votes to first election
  - Mitigation: Test with existing data before deployment

  Risk 2: Data Inconsistency
  - Mitigation: Unique constraint (user_id, election_id) prevents duplicates
  - Mitigation: Cascade delete maintains referential integrity

  Risk 3: Performance Regression
  - Mitigation: Index on (election_id, can_vote_now, user_id)
  - Mitigation: Benchmark before/after with load testing

  ---
  Next Steps

  1. Approval: Confirm this plan aligns with your vision
  2. Implementation: Start with Phase 2a (migrations & models)
  3. Review: Code review after each phase
  4. Testing: Full test coverage before Phase 2b
  5. Deployment: Gradual rollout with monitoring

  ---
  This plan provides maximum backward compatibility while leveraging your Phase 1 election infrastructure. Would you
  like me to proceed with implementation starting with Phase 2a migrations?