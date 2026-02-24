# Developer Guide: Demo Election Results Pages

**Version:** 1.0
**Last Updated:** 2026-02-23
**Target Audience:** Backend Developers, Frontend Developers, QA Engineers

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [MODE 1 vs MODE 2 System](#mode-1-vs-mode-2-system)
3. [Data Flow](#data-flow)
4. [File Structure](#file-structure)
5. [Backend Implementation](#backend-implementation)
6. [Frontend Implementation](#frontend-implementation)
7. [Testing Guide](#testing-guide)
8. [Troubleshooting](#troubleshooting)
9. [Performance Considerations](#performance-considerations)
10. [Security Best Practices](#security-best-practices)

---

## Architecture Overview

The Demo Election Results system provides two parallel views of demo election data:

```
┌─────────────────────────────────────────────────────────┐
│         DEMO ELECTION RESULTS SYSTEM                     │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  MODE 1 (Global Demo)          MODE 2 (Org Demo)       │
│  ─────────────────────         ─────────────────       │
│                                                          │
│  /demo/global/result           /demo/result             │
│  organisation_id = NULL        organisation_id = X      │
│                                                          │
│  Public                        Org-Scoped              │
│  Visible to ALL users          Visible to ORG members  │
│                                                          │
│  DemoPost (global)             DemoPost (org)           │
│  DemoCandidacy (global)        DemoCandidacy (org)      │
│  DemoVote (global)             DemoVote (org)           │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### Key Principle: Complete Data Isolation

**MODE 1 Query:**
```php
DemoPost::withoutGlobalScopes()->whereNull('organisation_id')->get();
```

**MODE 2 Query:**
```php
DemoPost::get(); // BelongsToTenant trait auto-applies scope
```

---

## MODE 1 vs MODE 2 System

### MODE 1: Global Demo (Public Testing Environment)

**Database Records:**
```sql
-- MODE 1 data
SELECT * FROM demo_posts WHERE organisation_id IS NULL;
SELECT * FROM demo_candidacies WHERE organisation_id IS NULL;
SELECT * FROM demo_votes WHERE organisation_id IS NULL;
```

**Access Control:**
```
Middleware: auth:sanctum, verified
Users: Any authenticated user
Isolation: Global scope bypass (withoutGlobalScopes())
```

**Use Cases:**
- Public demo showcasing platform features
- Training/onboarding environment
- General public testing
- No organisational context required

**Route:**
```php
GET /demo/global/result  →  DemoResultController@indexGlobal()
GET /demo/global/result/download-pdf  →  DemoResultController@downloadGlobalPDF()
```

---

### MODE 2: Organisation Demo (Organisation-Scoped Testing)

**Database Records:**
```sql
-- MODE 2 data for specific organisation
SELECT * FROM demo_posts WHERE organisation_id = 1;
SELECT * FROM demo_candidacies WHERE organisation_id = 1;
SELECT * FROM demo_votes WHERE organisation_id = 1;
```

**Access Control:**
```
Middleware: auth:sanctum, verified, election, vote.organisation
Users: Organisation members only
Isolation: BelongsToTenant trait (automatic scoping)
Session: current_organisation_id required
```

**Use Cases:**
- Organisation-specific demo elections
- Testing with organisation context
- Member engagement demos
- Pre-production testing

**Route:**
```php
GET /demo/result  →  DemoResultController@index()
GET /demo/result/download-pdf  →  DemoResultController@downloadPDF()
```

---

## Data Flow

### Complete Request Flow

```
1. USER REQUEST
   ↓
   GET /demo/global/result  (or /demo/result)
   ↓

2. MIDDLEWARE CHAIN
   ├─ auth:sanctum        → Verify authentication
   ├─ verified            → Check email verification
   ├─ election            → Resolve election context (MODE 2 only)
   └─ vote.organisation   → Enforce organisation context (MODE 2 only)
   ↓

3. CONTROLLER (DemoResultController)
   ├─ indexGlobal()  (MODE 1)
   │  ├─ DemoPost::withoutGlobalScopes()->whereNull('organisation_id')
   │  ├─ DemoCandidacy::withoutGlobalScopes()->where('organisation_id', null)
   │  └─ DemoVote::withoutGlobalScopes()->where('organisation_id', null)
   │
   └─ index()  (MODE 2)
      ├─ DemoPost::get()  [BelongsToTenant auto-filters]
      ├─ DemoCandidacy::get()  [BelongsToTenant auto-filters]
      └─ DemoVote::get()  [BelongsToTenant auto-filters]
   ↓

4. RESULT PROCESSING
   ├─ Count total votes
   ├─ Iterate each post
   ├─ Get all candidates for post
   ├─ Count votes per candidate
   ├─ Calculate percentages
   ├─ Handle "no vote" option
   └─ Sort by vote count
   ↓

5. DATA TRANSFORMATION
   ├─ Build results array
   ├─ Add mode indicator
   ├─ Add organisation context
   └─ Format for Vue component
   ↓

6. INERTIA RESPONSE
   Return Inertia::render('Demo/Result/Index', [
       'final_result' => $results,
       'posts' => $posts,
       'mode' => 'global' or 'organisation',
       'organisation_id' => null or X,
       'is_demo' => true,
       'page_title' => ...
   ])
   ↓

7. FRONTEND RENDERING
   ├─ ModeIndicator.vue      (Show MODE 1/2 banner)
   ├─ StatsCards.vue         (Display totals)
   ├─ CandidateCard.vue      (Show results per position)
   └─ ActionButtons.vue      (PDF, Print)
   ↓

8. USER INTERACTION
   ├─ View results
   ├─ Download PDF
   ├─ Print page
   └─ Navigate back
```

---

## File Structure

```
nrna-eu/
├── app/Http/Controllers/Demo/
│   └── DemoResultController.php          ✅ MAIN LOGIC
│
├── routes/
│   └── election/
│       └── electionRoutes.php            ✅ ROUTE DEFINITIONS
│
├── resources/js/
│   ├── Pages/Demo/Result/
│   │   ├── Index.vue                     ✅ MAIN PAGE
│   │   ├── ModeIndicator.vue             ✅ MODE BANNER
│   │   └── Candidate.vue                 ✅ RESULTS CARD
│   │
│   ├── Components/
│   │   └── StatCard.vue                  ✅ STATS COMPONENT
│   │
│   └── Pages/Organizations/Partials/
│       └── DemoResultsSection.vue        ✅ ORG PAGE LINK
│
├── tests/
│   ├── Unit/Controllers/
│   │   └── DemoResultControllerTest.php  ✅ UNIT TESTS
│   │
│   └── Feature/
│       └── DemoResultPageTest.php        ✅ FEATURE TESTS
│
└── resources/js/locales/pages/Demo/Result/
    ├── en.json                           ✅ ENGLISH
    ├── de.json                           ✅ GERMAN
    └── np.json                           ✅ NEPALI
```

---

## Backend Implementation

### DemoResultController Structure

```php
<?php
namespace App\Http\Controllers\Demo;

class DemoResultController extends Controller {

    // MODE 2: Organisation-scoped results
    public function index() {
        // Queries with BelongsToTenant scope
        return Inertia::render('Demo/Result/Index', [
            'mode' => 'organisation',
            'organisation_id' => session('current_organisation_id'),
        ]);
    }

    // MODE 1: Global demo results
    public function indexGlobal() {
        // Queries with withoutGlobalScopes()->whereNull('organisation_id')
        return Inertia::render('Demo/Result/Index', [
            'mode' => 'global',
            'organisation_id' => null,
        ]);
    }

    // Helper: Extract results with mode-aware scoping
    private function getElectionResultsData($posts, $mode = 'organisation') {
        // Switch queries based on mode
        if ($mode === 'global') {
            // withoutGlobalScopes() + whereNull('organisation_id')
        } else {
            // Regular queries with auto-scoping
        }
    }

    // PDF Downloads
    public function downloadPDF() { /* MODE 2 */ }
    public function downloadGlobalPDF() { /* MODE 1 */ }

    // Verification Endpoints
    public function verifyResults($postId) { /* Check integrity */ }
    public function statisticalVerification($postId) { /* Detect anomalies */ }
}
```

### Vote Counting Logic

**Key Challenge:** Votes are stored as JSON in `candidate_01` through `candidate_60` fields

```php
// Vote structure in database:
{
    "candidate_01": {
        "post_id": "PRES",
        "candidates": [
            {"candidacy_id": "CAND_001"}
        ],
        "no_vote": false
    },
    "candidate_02": { ... }
}

// Counting logic:
foreach ($votes as $vote) {
    for ($i = 1; $i <= 60; $i++) {
        $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
        $candidateData = json_decode($vote->$field, true);

        if ($candidateData['post_id'] === $postId) {
            if (isset($candidateData['no_vote']) && $candidateData['no_vote']) {
                $noVoteCount++;
            } else {
                foreach ($candidateData['candidates'] as $candidate) {
                    $candidateVotes[$candidate['candidacy_id']]++;
                }
            }
        }
    }
}
```

### BelongsToTenant Trait

**How it works:**

```php
class DemoPost extends Model {
    use BelongsToTenant;
    // Automatically adds: where('organisation_id', session('current_organisation_id'))
}

// When you call:
DemoPost::get();

// It executes:
DemoPost::where('organisation_id', session('current_organisation_id'))->get();
```

**To bypass (MODE 1):**

```php
DemoPost::withoutGlobalScopes()->whereNull('organisation_id')->get();
// Removes BelongsToTenant scope AND adds explicit NULL check
```

---

## Frontend Implementation

### Vue Component Hierarchy

```
Index.vue (Main Page)
├── ModeIndicator.vue (Blue/Purple banner)
├── StatCard.vue × 4 (Total votes, positions, date, mode)
├── Button: Download PDF
├── Button: Print
└── Candidate.vue × N (One per position)
    ├── Header (gradient, position name)
    ├── Mobile view (cards with progress bars)
    ├── Desktop view (full table)
    └── Footer (summary stats)
```

### Key Props Flow

**Index → Candidate (for each position):**

```vue
:post="{
  post_id: 'PRES',
  name: 'President',
  state_name: 'Bayern',
  required_number: 1
}"

:final_result="{
  post_id: 'PRES',
  post_name: 'President',
  candidates: [
    {
      candidacy_id: 'CAND_001',
      name: 'John Doe',
      vote_count: 150,
      vote_percent: 35.5
    },
    ...
  ],
  no_vote_count: 45,
  total_votes_for_post: 423
}"

:mode="'global' or 'organisation'"
:is-demo="true"
```

### Responsive Design Implementation

**Mobile-First Strategy:**

```vue
<template>
  <!-- Mobile card view (visible on <640px) -->
  <div class="block sm:hidden">
    <!-- Cards with progress bars -->
  </div>

  <!-- Desktop table view (visible on ≥640px) -->
  <div class="hidden sm:block">
    <!-- Full table with mini charts -->
  </div>
</template>
```

**Tailwind Breakpoints:**
- `xs`: Mobile (< 640px)
- `sm`: Tablets (≥ 640px)
- `md`: Medium devices (≥ 768px)
- `lg`: Large devices (≥ 1024px)

---

## Testing Guide

### Setup Test Database

```bash
# Create test database
php artisan migrate:fresh --env=testing

# Seed with demo data
php artisan db:seed --class=DemoDataSeeder --env=testing
```

### Running Tests

**Run all demo result tests:**

```bash
php artisan test tests/Unit/Controllers/DemoResultControllerTest.php
php artisan test tests/Feature/DemoResultPageTest.php
```

**Run specific test:**

```bash
php artisan test tests/Unit/Controllers/DemoResultControllerTest.php::test_mode_1_returns_global_demo_results
```

**Run with code coverage:**

```bash
php artisan test --coverage tests/Unit/Controllers/DemoResultControllerTest.php
```

### Test Categories

#### Unit Tests (DemoResultControllerTest.php)

Tests business logic in isolation:

```php
✅ test_mode_1_returns_global_demo_results()
✅ test_mode_2_returns_organisation_scoped_results()
✅ test_data_isolation_mode1_does_not_see_mode2_data()
✅ test_vote_counting_is_accurate()
✅ test_no_vote_option_counted_correctly()
✅ test_authentication_is_required()
✅ test_pdf_download_works_for_mode_1()
✅ test_pdf_download_works_for_mode_2()
✅ test_verify_results_endpoint()
```

#### Feature Tests (DemoResultPageTest.php)

Tests complete user workflows:

```php
✅ test_any_authenticated_user_can_access_mode1()
✅ test_unauthenticated_user_cannot_access_mode1()
✅ test_mode2_requires_organisation_context()
✅ test_mode2_filters_data_by_organisation()
✅ test_mode1_and_mode2_have_different_data()
✅ test_page_displays_correct_mode_indicator()
✅ test_handles_large_dataset_efficiently()
✅ test_vote_percentage_calculated_correctly()
✅ test_candidates_sorted_by_votes()
```

### Test Data Factory

**Create test posts:**

```php
DemoPost::factory()
    ->count(5)
    ->create([
        'organisation_id' => null,  // MODE 1
    ]);

// Or for MODE 2:
DemoPost::factory()
    ->count(5)
    ->create([
        'organisation_id' => 1,  // MODE 2
    ]);
```

**Create test votes:**

```php
DemoVote::create([
    'organisation_id' => null,
    'voting_code' => 'CODE123',
    'candidate_01' => json_encode([
        'post_id' => 'PRES',
        'candidates' => [
            ['candidacy_id' => 'CAND_001']
        ]
    ]),
]);
```

---

## Troubleshooting

### Issue: MODE 2 returns empty results

**Symptoms:**
```
POST count: 0
Results: []
```

**Causes:**
1. ❌ `current_organisation_id` not set in session
2. ❌ Demo data created with wrong `organisation_id`
3. ❌ Route missing `vote.organisation` middleware

**Fix:**

```php
// In test or controller:
session(['current_organisation_id' => 1]);

// Create data:
DemoPost::create(['organisation_id' => 1, ...]);

// Check middleware:
Route::middleware([..., 'vote.organisation'])->get('/demo/result', ...);
```

### Issue: MODE 1 shows MODE 2 data

**Symptoms:**
```
MODE 1 includes organisation-specific posts
Data leakage detected
```

**Causes:**
1. ❌ Missing `withoutGlobalScopes()`
2. ❌ Missing `whereNull('organisation_id')`
3. ❌ BelongsToTenant trait not bypassed

**Fix:**

```php
// WRONG:
DemoPost::get();  // Scoped to current org!

// RIGHT:
DemoPost::withoutGlobalScopes()
    ->whereNull('organisation_id')
    ->get();
```

### Issue: Vote percentages incorrect

**Symptoms:**
```
Percentages don't add up to 100%
Candidates shown as 0% but have votes
```

**Causes:**
1. ❌ Using `total_votes` instead of `total_votes_for_post`
2. ❌ Excluding "no vote" from denominator
3. ❌ Rounding errors

**Fix:**

```php
// WRONG:
$percent = ($voteCount / $totalVotes) * 100;

// RIGHT:
$percent = ($voteCount / $totalVotesForPost) * 100;
```

### Issue: PDF download fails

**Symptoms:**
```
404 Not Found
Failed to download PDF
```

**Causes:**
1. ❌ TCPDF not installed
2. ❌ Route not registered
3. ❌ Middleware preventing access

**Fix:**

```bash
# Install TCPDF:
composer require tecnickcom/tcpdf

# Check routes:
php artisan route:list | grep "download-pdf"

# Check permissions:
# Verify auth middleware is configured
```

---

## Performance Considerations

### Query Optimization

**Current approach:** N+1 for candidates

```php
// For each post, we query candidates
$candidates = DemoCandidacy::where('post_id', $post->post_id)->get();
```

**Optimized approach:**

```php
// Eager load all candidates
$candidates = DemoCandidacy::whereIn(
    'post_id',
    $posts->pluck('post_id')
)->get();

// Group by post
$candidatesByPost = $candidates->groupBy('post_id');
```

### Vote Query Optimization

**Current:** Fetches all votes, processes in PHP

```php
$votes = DemoVote::where(...)->get();  // Could be 10,000+ records
foreach ($votes as $vote) {
    // Process JSON in PHP
}
```

**Large dataset impact:**
- Memory usage: O(n) where n = number of votes
- Processing time: Linear scan through all votes

**For >50,000 votes:**
- Consider database-level aggregation
- Use SQL JSON functions (MySQL 5.7+)
- Implement pagination for results

### Caching Strategy

**Future improvement:** Cache results by mode

```php
// Cache MODE 1 results for 1 hour
$results = Cache::remember(
    'demo-results:mode:global',
    3600,
    function() {
        return $this->getElectionResultsData($posts, 'global');
    }
);

// Invalidate on vote creation
Cache::forget('demo-results:mode:global');
```

---

## Security Best Practices

### 1. Data Isolation Verification

**Always test MODE separation:**

```php
// Test that MODE 1 query returns NULL org_id only
$posts = DemoPost::withoutGlobalScopes()->whereNull('organisation_id')->get();
$this->assertTrue($posts->every(fn($p) => $p->organisation_id === null));

// Test that MODE 2 query respects organisation_id
session(['current_organisation_id' => 1]);
$posts = DemoPost::get();
$this->assertTrue($posts->every(fn($p) => $p->organisation_id === 1));
```

### 2. Authentication Enforcement

**All routes require authentication:**

```php
Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    // MODE 1
    Route::get('/demo/global/result', ...);

    // MODE 2
    Route::middleware(['vote.organisation'])->group(function() {
        Route::get('/demo/result', ...);
    });
});
```

**Never allow unauthenticated access:**

```php
// WRONG:
Route::get('/demo/result', ...);

// RIGHT:
Route::middleware('auth:sanctum')->get('/demo/result', ...);
```

### 3. CSRF Protection

**Inertia routes handled automatically:**

```php
// CSRF token included in response
// No manual token management needed
```

### 4. SQL Injection Prevention

**Use Eloquent, never raw SQL:**

```php
// WRONG:
DB::select("SELECT * FROM demo_posts WHERE organisation_id = " . $orgId);

// RIGHT:
DemoPost::where('organisation_id', $orgId)->get();
```

### 5. Vote Counting Accuracy

**Assume data integrity:**

```php
// Trust that votes are valid JSON
$data = json_decode($vote->candidate_01, true);

// Validate required fields
if (!isset($data['post_id'])) {
    // Skip malformed vote
    continue;
}
```

---

## Contributing Guidelines

### Before Submitting Changes

**1. Run tests:**

```bash
php artisan test tests/Unit/Controllers/DemoResultControllerTest.php
php artisan test tests/Feature/DemoResultPageTest.php
```

**2. Check data isolation:**

- [ ] MODE 1 only sees NULL organisation_id
- [ ] MODE 2 only sees specific organisation_id
- [ ] No cross-mode data leakage

**3. Verify accessibility:**

- [ ] All text readable at 16px on mobile
- [ ] Color contrast ≥ 4.5:1
- [ ] Keyboard navigable
- [ ] ARIA labels present

**4. Test on multiple devices:**

- [ ] iPhone SE (375px)
- [ ] iPad (768px)
- [ ] Desktop (1024px+)

**5. Update documentation:**

- [ ] Update this guide if implementation changes
- [ ] Update translation keys if UI text changes
- [ ] Update tests if logic changes

---

## Common Development Tasks

### Add New Post Status Field

**Backend:**

```php
// 1. Create migration
php artisan make:migration add_status_to_demo_posts

// 2. Add to DemoPost model
$fillable = ['post_id', 'name', ..., 'status'];

// 3. Update query
$posts = DemoPost::where('status', 'active')->get();

// 4. Update tests
```

**Frontend:**

```vue
<!-- 1. Update Candidate.vue -->
<span v-if="post.status === 'closed'" class="badge">Closed</span>

<!-- 2. Add to translation keys -->
components.candidate-card.status-closed: "This position is closed"
```

### Add New Vote Field

**Backend:**

```php
// 1. Update DemoVote schema
// Add new column (e.g., voter_mood, energy_level)

// 2. Update vote counting logic
// Handle new field in JSON processing

// 3. Update tests
// Test counting with new field
```

### Implement Caching

```php
// Add to getElectionResultsData()
$cacheKey = sprintf('demo-results:%s:%s',
    $mode,
    $mode === 'global' ? 'all' : session('current_organisation_id')
);

return Cache::remember($cacheKey, 3600, function() use ($posts, $mode) {
    // Existing logic
});
```

---

## Resources

- **Test Files:** `/tests/Unit/Controllers/`, `/tests/Feature/`
- **Translation Keys:** `TRANSLATION_KEYS_DEMO_RESULTS.md`
- **Architecture Rules:** `CLAUDE.md` (Multi-Tenancy Rules)
- **Public Digit Docs:** Internal wiki

---

## Support

For questions or issues:

1. Check this guide
2. Review test cases (examples)
3. Check existing issues
4. Ask in dev channel

**Happy coding!** 🚀

