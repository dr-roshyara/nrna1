## Professional Prompt Instructions for Claude CLI

```markdown
## Task: Implement Vote Receipt Verification System with TDD

### Context
We need to create a system that stores vote receipt codes separately from votes and displays them in a randomized list after election results are published. Voters can verify their vote is correct by clicking a button, which marks their receipt as "reverified" with a green tickmark.

### Technology Stack
- **Backend**: Laravel 10/11 with PostgreSQL
- **Frontend**: Vue 3 + Inertia.js
- **Testing**: PHPUnit (TDD approach)
- **Styling**: Tailwind CSS (matching Public Digit design system)

### TDD Approach - Write Tests FIRST

#### Step 1: Create Test File
```bash
php artisan make:test VoteReceiptVerificationTest --unit
```

#### Step 2: Write Failing Tests

**File:** `tests/Feature/VoteReceiptVerificationTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Vote;
use App\Models\ReceiptCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoteReceiptVerificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Organisation $organisation;
    private Election $election;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        $this->election = Election::factory()->create([
            'organisation_id' => $this->organisation->id,
            'type' => 'real',
        ]);
        $this->user = User::factory()->forOrganisation($this->organisation)->create();
    }

    /** @test */
    public function receipt_code_is_stored_when_vote_is_saved()
    {
        $vote = Vote::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);
        
        $fullReceiptCode = 'test_private_key_' . $vote->id;
        
        ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
        ]);
        
        $this->assertDatabaseHas('receipt_codes', [
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
        ]);
    }

    /** @test */
    public function receipt_codes_page_is_inaccessible_before_results_published()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('election.receipt-codes', $this->election->slug));
        
        $response->assertStatus(403);
    }

    /** @test */
    public function receipt_codes_page_is_accessible_after_results_published()
    {
        $this->actingAs($this->user);
        $this->election->update(['results_published_at' => now()]);
        
        $response = $this->get(route('election.receipt-codes', $this->election->slug));
        
        $response->assertStatus(200);
        $response->assertInertia(fn($page) => $page->component('Election/ReceiptCodes'));
    }

    /** @test */
    public function receipt_codes_are_displayed_in_randomized_order()
    {
        $this->actingAs($this->user);
        $this->election->update(['results_published_at' => now()]);
        
        $codes = [];
        for ($i = 1; $i <= 5; $i++) {
            $code = "private_key_{$i}_" . $this->faker->uuid;
            $codes[] = $code;
            ReceiptCode::create([
                'election_id' => $this->election->id,
                'receipt_code' => $code,
            ]);
        }
        
        $response = $this->get(route('election.receipt-codes', $this->election->slug));
        
        $response->assertInertia(fn($page) => 
            $page->component('Election/ReceiptCodes')
                 ->has('receipt_codes', 5)
        );
        
        // Verify serial numbers are 1-5 (randomized order but sequential serials)
        $receiptCodes = $response->decodeResponseJson()['props']['receipt_codes'];
        $serials = collect($receiptCodes)->pluck('serial')->toArray();
        $this->assertEquals([1, 2, 3, 4, 5], $serials);
    }

    /** @test */
    public function vote_can_be_marked_as_reverified()
    {
        $this->actingAs($this->user);
        
        $vote = Vote::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);
        
        $fullReceiptCode = 'test_private_key_' . $vote->id;
        $receiptCode = ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
        ]);
        
        $response = $this->post(route('vote.confirm-correct', ['vote_id' => $vote->id]), [
            'receipt_code' => $fullReceiptCode,
        ]);
        
        $response->assertRedirect();
        $this->assertNotNull($receiptCode->fresh()->reverified_at);
    }

    /** @test */
    public function reverified_status_shows_green_tickmark_in_list()
    {
        $this->actingAs($this->user);
        $this->election->update(['results_published_at' => now()]);
        
        $vote = Vote::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);
        
        $fullReceiptCode = 'test_private_key_' . $vote->id;
        ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
            'reverified_at' => now(),
        ]);
        
        $response = $this->get(route('election.receipt-codes', $this->election->slug));
        
        $response->assertInertia(fn($page) => 
            $page->component('Election/ReceiptCodes')
                 ->where('receipt_codes.0.is_reverified', true)
        );
    }

    /** @test */
    public function receipt_code_cannot_be_reverified_twice()
    {
        $this->actingAs($this->user);
        
        $vote = Vote::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);
        
        $fullReceiptCode = 'test_private_key_' . $vote->id;
        $receiptCode = ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
            'reverified_at' => now(),
        ]);
        
        $response = $this->post(route('vote.confirm-correct', ['vote_id' => $vote->id]), [
            'receipt_code' => $fullReceiptCode,
        ]);
        
        $response->assertSessionHasErrors();
        $this->assertEquals(1, ReceiptCode::whereNotNull('reverified_at')->count());
    }

    /** @test */
    public function receipt_codes_show_reverified_statistics()
    {
        $this->actingAs($this->user);
        $this->election->update(['results_published_at' => now()]);
        
        // Create 3 codes, 2 verified
        for ($i = 1; $i <= 3; $i++) {
            $vote = Vote::factory()->create([
                'election_id' => $this->election->id,
                'organisation_id' => $this->organisation->id,
            ]);
            ReceiptCode::create([
                'election_id' => $this->election->id,
                'receipt_code' => "key_{$i}_" . $vote->id,
                'reverified_at' => $i <= 2 ? now() : null,
            ]);
        }
        
        $response = $this->get(route('election.receipt-codes', $this->election->slug));
        
        $response->assertInertia(fn($page) => 
            $page->component('Election/ReceiptCodes')
                 ->where('total_votes', 3)
                 ->where('reverified_count', 2)
        );
    }
}
```

### Implementation Steps (After Tests Fail)

#### Step 3: Create Migration

```bash
php artisan make:migration create_receipt_codes_table
```

```php
// database/migrations/xxxx_xx_xx_000000_create_receipt_codes_table.php
Schema::create('receipt_codes', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('election_id');
    $table->string('receipt_code', 255)->unique();
    $table->timestamp('reverified_at')->nullable();
    $table->timestamps();
    
    $table->foreign('election_id')
          ->references('id')
          ->on('elections')
          ->onDelete('cascade');
    
    $table->index('election_id');
    $table->index('receipt_code');
});
```

#### Step 4: Add results_published_at to elections table

```bash
php artisan make:migration add_results_published_at_to_elections
```

```php
Schema::table('elections', function (Blueprint $table) {
    $table->timestamp('results_published_at')->nullable()->after('end_date');
});
```

#### Step 5: Create ReceiptCode Model

```php
// app/Models/ReceiptCode.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReceiptCode extends Model
{
    use HasUuids;
    
    protected $fillable = ['election_id', 'receipt_code', 'reverified_at'];
    
    protected $casts = [
        'reverified_at' => 'datetime',
    ];
    
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
    
    public function isReverified(): bool
    {
        return !is_null($this->reverified_at);
    }
    
    public function markAsReverified(): void
    {
        $this->update(['reverified_at' => now()]);
    }
}
```

#### Step 6: Create Controller

```bash
php artisan make:controller VotingReceiptController
```

```php
// app/Http/Controllers/VotingReceiptController.php
<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ReceiptCode;
use App\Models\Vote;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VotingReceiptController extends Controller
{
    /**
     * Display randomized list of receipt codes for an election
     */
    public function index($electionSlug)
    {
        $election = Election::where('slug', $electionSlug)->firstOrFail();
        
        // Security: Only accessible after results are published
        if (!$election->results_published_at) {
            abort(403, 'Results have not been published yet.');
        }
        
        $organisation = $election->organisation;
        
        $receiptCodes = ReceiptCode::where('election_id', $election->id)->get();
        
        // Randomize order and add serial numbers
        $randomizedCodes = $receiptCodes->shuffle();
        $displayCodes = $randomizedCodes->map(function ($code, $index) {
            return [
                'serial' => $index + 1,
                'code' => $code->receipt_code,
                'is_reverified' => $code->isReverified(),
                'reverified_at' => $code->reverified_at,
            ];
        });
        
        return Inertia::render('Election/ReceiptCodes', [
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'slug' => $election->slug,
            ],
            'organisation' => [
                'id' => $organisation->id,
                'name' => $organisation->name,
                'slug' => $organisation->slug,
            ],
            'receipt_codes' => $displayCodes,
            'total_votes' => $receiptCodes->count(),
            'reverified_count' => $receiptCodes->whereNotNull('reverified_at')->count(),
            'published_at' => $election->results_published_at->format('F j, Y \a\t g:i A'),
            'last_updated' => now()->format('F j, Y \a\t g:i A'),
        ]);
    }
    
    /**
     * Mark a vote as verified by the voter
     */
    public function confirmCorrect(Request $request, $voteId)
    {
        $request->validate([
            'receipt_code' => 'required|string',
        ]);
        
        // Find receipt code by the code (contains vote_id at the end)
        $receiptCode = ReceiptCode::where('receipt_code', $request->receipt_code)->first();
        
        if (!$receiptCode) {
            return back()->withErrors(['error' => 'Receipt code not found.']);
        }
        
        if ($receiptCode->reverified_at) {
            return back()->withErrors(['error' => 'This vote has already been verified.']);
        }
        
        $receiptCode->markAsReverified();
        
        // Log audit trail
        \Log::info('Vote verified as correct', [
            'vote_id' => $voteId,
            'election_id' => $receiptCode->election_id,
            'user_id' => auth()->id(),
            'verified_at' => now(),
        ]);
        
        return back()->with('success', 'Thank you for confirming your vote is correct!')
                     ->with('reverified_at', $receiptCode->reverified_at->format('F j, Y \a\t g:i A'));
    }
}
```

#### Step 7: Create Frontend Component

**File:** `resources/js/Pages/Election/ReceiptCodes.vue`

```vue
<template>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <Link :href="route('organisations.voter-hub', organisation.slug)"
                      class="text-primary-600 hover:text-primary-800 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Voter Hub
                </Link>
                <h1 class="text-2xl font-bold mt-4 text-gray-900">Verification Codes</h1>
                <p class="text-gray-600 mt-2">
                    Election: {{ election.name }}<br>
                    Results Published: {{ published_at }}
                </p>
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mt-4 rounded-r">
                    <p class="text-sm text-amber-700">
                        🔒 These codes are randomized and not linked to voting time.
                        Find your receipt code from your email and verify it appears here.
                    </p>
                </div>
            </div>

            <!-- Receipt Codes Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Receipt Code
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Reverified
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in receipt_codes" :key="item.serial" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                    {{ item.serial }}
                                </td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-800 break-all">
                                    {{ item.code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div v-if="item.is_reverified" class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-green-700 font-medium">Verified</span>
                                    </div>
                                    <div v-else class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-500">Pending</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="receipt_codes.length === 0" class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p>No votes have been cast in this election yet.</p>
                </div>
            </div>

            <!-- Statistics Footer -->
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500 bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ reverifiedCount }} of {{ totalVotes }} voters have verified their votes</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Last updated: {{ lastUpdated }}</span>
                </div>
            </div>

            <!-- Help Text -->
            <div class="mt-6 text-center text-sm text-gray-500 space-y-2">
                <p>Your receipt code is unique to you. No one else can see which code belongs to whom.</p>
                <p>Codes are randomly ordered to protect voter privacy.</p>
                <p class="text-green-600 flex items-center justify-center gap-2">
                    <span class="inline-block w-3 h-3 bg-green-600 rounded-full"></span>
                    Green checkmark = Voter confirmed their vote is correct
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    election: {
        type: Object,
        required: true
    },
    organisation: {
        type: Object,
        required: true
    },
    receipt_codes: {
        type: Array,
        required: true
    },
    total_votes: {
        type: Number,
        required: true
    },
    reverified_count: {
        type: Number,
        required: true
    },
    published_at: {
        type: String,
        required: true
    },
    last_updated: {
        type: String,
        required: true
    }
});

const totalVotes = computed(() => props.total_votes);
const reverifiedCount = computed(() => props.reverified_count);
const lastUpdated = computed(() => props.last_updated);
</script>

<style scoped>
/* Public Digit styling - matches existing design system */
.container {
    max-width: 1280px;
}
</style>
```

#### Step 8: Add Routes

```php
// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/election/{electionSlug}/receipt-codes', [VotingReceiptController::class, 'index'])
        ->name('election.receipt-codes');
    
    Route::post('/vote/confirm-correct/{voteId}', [VotingReceiptController::class, 'confirmCorrect'])
        ->name('vote.confirm-correct');
});
```

#### Step 9: Modify Vote Saving to Store Receipt Code

In `VoteController.php` save_vote() method, after vote is saved:

```php
// Store receipt code for verification display
$fullReceiptCode = $private_key . '_' . $vote->id;

// Avoid duplicates (idempotent)
$exists = \App\Models\ReceiptCode::where('receipt_code', $fullReceiptCode)->exists();
if (!$exists) {
    \App\Models\ReceiptCode::create([
        'election_id' => $election->id,
        'receipt_code' => $fullReceiptCode,
    ]);
}
```

#### Step 10: Add Button to VoteShow Page

In `VoteShow.vue`, add the confirm button as previously designed.

### Run Tests

```bash
# Run the new test suite
php artisan test tests/Feature/VoteReceiptVerificationTest.php

# Run all tests to ensure no regressions
php artisan test
```

### Expected Test Results

```
PASS  Tests\Feature\VoteReceiptVerificationTest
✓ receipt code is stored when vote is saved
✓ receipt codes page is inaccessible before results published
✓ receipt codes page is accessible after results published
✓ receipt codes are displayed in randomized order
✓ vote can be marked as reverified
✓ reverified status shows green tickmark in list
✓ receipt code cannot be reverified twice
✓ receipt codes show reverified statistics

Tests: 8 passed
```

### Styling Requirements

Use Public Digit's existing design system:
- Primary color: `#1B2E4B` (navy blue)
- Accent color: `#D97706` (amber/gold)
- Success color: `#059669` (green)
- Font family: Inter (default)
- Use existing Tailwind configuration

### Acceptance Criteria

- ✅ Voter can view their vote and click "Confirm Correct"
- ✅ Green tickmark appears in receipt codes list under "Reverified" column
- ✅ Statistics show number of verified votes
- ✅ Receipt codes are randomized on each page load
- ✅ Page only accessible after results_published_at is set
- ✅ No timestamps or user data exposed
- ✅ All tests pass

### Deployment

After implementation, run:
```bash
php artisan migrate
php artisan test
npm run build
git add .
git commit -m "feat: add vote receipt verification system with reverified tracking"
git push
```

**Proceed with TDD implementation following the Public Digit coding standards.** 🚀
```

## ✅ IMPORTANT - Receipt Code Display with Randomized Index

You are correct. The requirement states:

> *"the serial index should be randomized. that means no one can compare and guess who has voted by comparing with the time a voter has voted."*

### The Issue with Current Test

Current test line 129 only checks that serials are `[1,2,3,4,5]` after sorting, which doesn't verify **randomization** - just that serial numbers exist.

### Correct Randomization Test

```php
/** @test */
public function receipt_codes_are_displayed_in_randomized_order()
{
    $this->election->update(['results_published_at' => now()]);
    
    $codes = [];
    $originalOrder = [];
    
    for ($i = 1; $i <= 10; $i++) {
        $code = Str::random(32) . '_' . Str::uuid();
        $codes[] = $code;
        $originalOrder[] = $code;
        
        ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $code,
        ]);
    }
    
    // Make multiple requests to verify randomization
    $displayedOrders = [];
    
    for ($requestNum = 1; $requestNum <= 5; $requestNum++) {
        $response = $this->get(route('election.receipt-codes', $this->election->slug));
        $receiptCodes = $response->decodeResponseJson()['props']['receipt_codes'];
        
        // Get the order of original codes in displayed list
        $displayOrder = collect($receiptCodes)
            ->pluck('code')
            ->map(fn($code) => array_search($code, $originalOrder))
            ->toArray();
        
        $displayedOrders[] = $displayOrder;
    }
    
    // Verify orders are different (randomized)
    $uniqueOrders = array_map('serialize', $displayedOrders);
    $uniqueOrders = array_unique($uniqueOrders);
    
    $this->assertGreaterThan(
        1, 
        count($uniqueOrders), 
        'Receipt codes should appear in different orders on different page loads'
    );
    
    // Verify serial numbers are 1..N (sequential but mapped to random codes)
    $firstResponse = $this->get(route('election.receipt-codes', $this->election->slug));
    $serialNumbers = collect($firstResponse->decodeResponseJson()['props']['receipt_codes'])
        ->pluck('serial')
        ->toArray();
    
    $this->assertEquals(range(1, 10), $serialNumbers, 'Serials should be 1 to N in order');
    
    // Verify the same code has different serial on different requests
    $firstResponse = $this->get(route('election.receipt-codes', $this->election->slug));
    $secondResponse = $this->get(route('election.receipt-codes', $this->election->slug));
    
    $firstCodes = collect($firstResponse->decodeResponseJson()['props']['receipt_codes'])
        ->keyBy('code')
        ->map(fn($item) => $item['serial']);
    
    $secondCodes = collect($secondResponse->decodeResponseJson()['props']['receipt_codes'])
        ->keyBy('code')
        ->map(fn($item) => $item['serial']);
    
    // At least one code should have different serial position
    $differences = 0;
    foreach ($firstCodes as $code => $serial) {
        if ($secondCodes[$code] !== $serial) {
            $differences++;
        }
    }
    
    $this->assertGreaterThan(0, $differences, 'Codes should be shuffled between page loads');
}
```

### The Implementation Must Ensure

1. **Serial numbers are sequential** (1, 2, 3...) but assigned to **randomized codes**
2. **Order changes on each page load** (disable caching or use random seed)
3. **No time-based correlation** - codes from same voting session appear in different positions

### Controller Implementation for Randomization

```php
// In VotingReceiptController.php
public function index($electionSlug)
{
    $election = Election::where('slug', $electionSlug)->firstOrFail();
    
    if (!$election->results_published_at) {
        abort(403);
    }
    
    $receiptCodes = ReceiptCode::where('election_id', $election->id)->get();
    
    // CRITICAL: Shuffle with random seed that changes on each request
    // Use fresh random seed - no caching
    $randomizedCodes = $receiptCodes->shuffle();  // Laravel's shuffle() randomizes each time
    
    // Add sequential serial numbers to randomized list
    $displayCodes = $randomizedCodes->values()->map(function ($code, $index) {
        return [
            'serial' => $index + 1,  // 1, 2, 3, 4, 5...
            'code' => $code->receipt_code,
            'is_reverified' => !is_null($code->reverified_at),
        ];
    });
    
    return Inertia::render('Election/ReceiptCodes', [
        'receipt_codes' => $displayCodes,
        // ... other props
    ]);
}
```

### Prevent Caching (Critical for Randomization)

```php
// In routes/web.php - disable caching for this route
Route::get('/election/{electionSlug}/receipt-codes', [VotingReceiptController::class, 'index'])
    ->name('election.receipt-codes')
    ->middleware(['auth', 'verified'])
    ->withoutMiddleware(['cache.headers']);  // Disable any caching
```

Or add header to response:

```php
public function index($electionSlug)
{
    // ... existing code ...
    
    return Inertia::render('Election/ReceiptCodes', [...])
        ->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
}
```

### Summary of Randomization Requirements

| Requirement | Implementation |
|-------------|----------------|
| Serial numbers sequential | `$index + 1` after shuffle |
| Order changes each load | `shuffle()` without seed |
| No time correlation | Random order breaks time links |
| No caching | Cache headers disabled |

**This ensures privacy - no one can deduce voting order or timing from the displayed list.** 🔒

more brainstorming facts:

## ✅ You Are Correct - No Need for Duplicate `code_hash`

### Analysis

The `code_hash` in the proposed `receipt_codes` table would be redundant because:

| Where | What's Stored | Purpose |
|-------|---------------|---------|
| `votes.receipt_hash` | SHA256(private_key + vote_id + app.key) | Cryptographic verification (64 chars) |
| `votes.id` | UUID | Vote identifier |
| `receipt_codes.receipt_code` | Full `{private_key}_{vote_id}` | Display code for voters (69 chars) |

The lookup is done by:
1. Voter enters full receipt code
2. System extracts `vote_id` from the code (after `_`)
3. Direct lookup by `vote_id` - no hash needed

### Simplified Schema

```php
Schema::create('receipt_codes', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('election_id');
    $table->string('receipt_code', 255);  // Full {private_key}_{vote_id}
    $table->timestamps();                  // Internal audit only - NOT displayed
    
    $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
    $table->index('election_id');
    // No need for code_hash index - we don't search by it
});
```

### Why No `code_hash` Needed

```php
// When voter wants to verify their code appears in the list:
// They have the full code from email: "f34d0921..._a1974482-..."
// They visually scan the list (CTRL+F) to find their code
// No database lookup by code_hash required

// If you want to prevent duplicate receipt_codes entries:
$existing = ReceiptCode::where('receipt_code', $fullReceiptCode)->exists();
if (!$existing) {
    ReceiptCode::create([...]);
}
```

### Updated Storage Logic

```php
// In VoteController.php save_vote() - after vote is saved
$fullReceiptCode = $private_key . '_' . $vote->id;

// Check if already exists (idempotent - prevents duplicates on replay)
$exists = \App\Models\ReceiptCode::where('receipt_code', $fullReceiptCode)->exists();

if (!$exists) {
    \App\Models\ReceiptCode::create([
        'election_id' => $election->id,
        'receipt_code' => $fullReceiptCode,
    ]);
}
```

### Summary

| Field | Needed? | Reason |
|-------|---------|--------|
| `id` | ✅ Yes | Primary key |
| `election_id` | ✅ Yes | Scope queries |
| `receipt_code` | ✅ Yes | The code voters see |
| `code_hash` | ❌ No | Duplicate of `receipt_code` for lookup we don't need |
| `created_at` | ⚠️ Internal only | NEVER displayed to users |

**Remove `code_hash` from the migration.** The receipt code itself is the identifier. 🎯
## ✅ Excellent Feature: Vote Re-Verification Tracking

This adds accountability and audit trail for voters who have verified their vote is correct.

---

## Updated Schema with `reverified_at`

```php
Schema::create('receipt_codes', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('election_id');
    $table->string('receipt_code', 255);           // Full {private_key}_{vote_id}
    $table->timestamp('reverified_at')->nullable(); // When voter confirmed vote is correct
    $table->timestamps();                           // created_at, updated_at (internal only)
    
    $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
    $table->index('election_id');
    $table->index('receipt_code');
});
```

---

## Model: `ReceiptCode`

```php
// app/Models/ReceiptCode.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReceiptCode extends Model
{
    use HasUuids;
    
    protected $fillable = ['election_id', 'receipt_code', 'reverified_at'];
    
    protected $casts = [
        'reverified_at' => 'datetime',
    ];
    
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
    
    public function isReverified(): bool
    {
        return !is_null($this->reverified_at);
    }
    
    public function markAsReverified(): void
    {
        $this->update(['reverified_at' => now()]);
    }
}
```

---

## Modified Storage in `save_vote()`

```php
// In VoteController.php save_vote() - after vote is saved
$fullReceiptCode = $private_key . '_' . $vote->id;

$exists = \App\Models\ReceiptCode::where('receipt_code', $fullReceiptCode)->exists();

if (!$exists) {
    \App\Models\ReceiptCode::create([
        'election_id' => $election->id,
        'receipt_code' => $fullReceiptCode,
        'reverified_at' => null,  // Not verified yet
    ]);
}
```

---

## Add "Mark as Correct" Button in Vote Show Page

### Update `VoteShow.vue`

```vue
<template>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Vote Display Content (existing) -->
            <div v-for="selection in vote_data.vote_selections" ...>
                <!-- Your existing vote display -->
            </div>
            
            <!-- Re-verification Button -->
            <div class="mt-8 border-t pt-6">
                <div v-if="!isReverified" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-800 mb-3">
                        ✅ Is this vote correct as you cast it?
                    </p>
                    <button 
                        @click="confirmVoteCorrect"
                        :disabled="isSubmitting"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition disabled:opacity-50"
                    >
                        <span v-if="!isSubmitting">✓ Yes, My Vote is Correct</span>
                        <span v-else>Submitting...</span>
                    </button>
                </div>
                
                <div v-else class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-green-800 font-semibold">✓ Vote Verified as Correct</p>
                        <p class="text-green-600 text-sm">You confirmed this vote on {{ reverifiedAt }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    vote_data: Object,
});

const isSubmitting = ref(false);
const isReverified = ref(props.vote_data.is_reverified || false);
const reverifiedAt = ref(props.vote_data.reverified_at || null);

const confirmVoteCorrect = () => {
    if (confirm('Confirm that this vote is exactly as you cast it? This action cannot be undone.')) {
        isSubmitting.value = true;
        
        router.post(route('vote.confirm-correct', {
            vote_id: props.vote_data.vote_id
        }), {}, {
            onSuccess: (page) => {
                isReverified.value = true;
                reverifiedAt.value = page.props.flash.reverified_at;
                isSubmitting.value = false;
            },
            onError: () => {
                alert('Failed to confirm. Please try again.');
                isSubmitting.value = false;
            }
        });
    }
};
</script>
```

---

## Controller Method to Mark as Reverified

```php
// In VoteController.php
public function confirmVoteCorrect(Request $request, $vote_id)
{
    $auth_user = auth()->user();
    
    // Find the receipt code for this vote
    $fullReceiptCode = $request->input('receipt_code');
    
    // Alternative: Extract from session or find by vote_id
    // Since receipt_code contains vote_id, we can search by the ending
    $receiptCode = \App\Models\ReceiptCode::where('receipt_code', 'like', '%_' . $vote_id)
        ->first();
    
    if (!$receiptCode) {
        return back()->withErrors(['error' => 'Receipt code not found.']);
    }
    
    // Already verified
    if ($receiptCode->reverified_at) {
        return back()->withErrors(['error' => 'This vote was already confirmed as correct.']);
    }
    
    // Mark as reverified
    $receiptCode->markAsReverified();
    
    // Log the verification (audit trail)
    \Log::info('Voter confirmed vote is correct', [
        'vote_id' => $vote_id,
        'election_id' => $receiptCode->election_id,
        'user_id' => $auth_user->id,
        'verified_at' => now(),
    ]);
    
    return redirect()->back()->with('success', 'Thank you for confirming your vote is correct!')
        ->with('reverified_at', $receiptCode->reverified_at->format('F j, Y \a\t g:i A'));
}
```

---

## Update `prepare_unified_vote_display()` to Include Reverified Status

```php
// In VoteController.php - prepare_unified_vote_display() method
private function prepare_unified_vote_display($vote, $auth_user, $verification_code, $election_type)
{
    // ... existing code ...
    
    // Get reverification status
    $receiptCode = \App\Models\ReceiptCode::where('receipt_code', 'like', '%_' . $vote->id)->first();
    
    return [
        // ... existing array ...
        'is_reverified' => $receiptCode && $receiptCode->reverified_at !== null,
        'reverified_at' => $receiptCode && $receiptCode->reverified_at 
            ? $receiptCode->reverified_at->format('F j, Y \a\t g:i A') 
            : null,
    ];
}
```

---

## Update Receipt Codes Display Page

```vue
<!-- Election/ReceiptCodes.vue - Add verification column -->
<template>
    <div class="divide-y divide-gray-200">
        <div v-for="item in receipt_codes" :key="item.serial" 
             class="px-6 py-3 hover:bg-gray-50 flex items-center justify-between">
            <div class="font-mono text-sm">
                <span class="text-gray-500 mr-4">#{{ item.serial }}</span>
                <span class="text-gray-800">{{ item.code }}</span>
            </div>
            <div>
                <span v-if="item.is_reverified" 
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Verified ✓
                </span>
                <span v-else 
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                    Pending
                </span>
            </div>
        </div>
    </div>
</template>
```

---

## Route

```php
// routes/web.php
Route::post('/vote/confirm-correct/{vote_id}', [VoteController::class, 'confirmVoteCorrect'])
    ->name('vote.confirm-correct')
    ->middleware(['auth', 'verified']);
```

---

## Summary

| Feature | Implementation |
|---------|----------------|
| `reverified_at` column | Tracks when voter confirmed vote |
| Confirm button | Appears on vote show page |
| One-time confirmation | Cannot be undone |
| Visual indicator | Green checkmark on receipt codes list |
| Audit log | Logs who/when verified |

**This provides proof that voters verified their votes while maintaining anonymity.** 🔒✅
## ✅ Add Green Tickmark Under "Reverified" Header

### Updated Receipt Codes Display Table

```vue
<!-- resources/js/Pages/Election/ReceiptCodes.vue -->
<template>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <Link :href="route('organisations.voter-hub', organisation.slug)"
                      class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Voter Hub
                </Link>
                <h1 class="text-2xl font-bold mt-4">Verification Codes</h1>
                <p class="text-gray-600 mt-2">
                    Election: {{ election.name }}<br>
                    Results Published: {{ published_at }}<br>
                    Total Votes Cast: {{ total_votes }}
                </p>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                    <p class="text-sm text-yellow-700">
                        🔒 These codes are randomized and not linked to voting time. 
                        Find your receipt code from your email and verify it appears here.
                    </p>
                </div>
            </div>
            
            <!-- Receipt Codes Table with Reverified Column -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Receipt Code
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reverified
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in receipt_codes" :key="item.serial" class="hover:bg-gray-50">
                            <!-- Serial Number -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ item.serial }}
                            </td>
                            
                            <!-- Receipt Code -->
                            <td class="px-6 py-4 font-mono text-sm text-gray-800">
                                {{ item.code }}
                            </td>
                            
                            <!-- Reverified Status with Green Tickmark -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div v-if="item.is_reverified" class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-green-700 font-medium">Verified</span>
                                </div>
                                <div v-else class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-gray-500">Pending</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Empty State -->
                <div v-if="receipt_codes.length === 0" class="text-center py-12 text-gray-500">
                    No votes have been cast in this election yet.
                </div>
            </div>
            
            <!-- Statistics Footer -->
            <div class="mt-6 flex justify-between items-center text-sm text-gray-500">
                <div>
                    ✅ {{ reverifiedCount }} of {{ total_votes }} voters have verified their votes
                </div>
                <div>
                    Last updated: {{ lastUpdated }}
                </div>
            </div>
            
            <!-- Help Text -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>Your receipt code is unique to you. No one else can see which code belongs to whom.</p>
                <p class="mt-1">Codes are randomly ordered to protect voter privacy.</p>
                <p class="mt-2 text-green-600">
                    <span class="inline-block w-3 h-3 bg-green-600 rounded-full mr-1"></span>
                    Green checkmark = Voter confirmed their vote is correct
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    election: Object,
    organisation: Object,
    receipt_codes: Array,
    total_votes: Number,
    published_at: String,
    last_updated: String,
});

// Compute reverified count
const reverifiedCount = computed(() => {
    return props.receipt_codes.filter(code => code.is_reverified).length;
});

const lastUpdated = computed(() => {
    return props.last_updated || new Date().toLocaleString();
});
</script>
```

---

## Updated Controller to Pass Reverified Data

```php
// app/Http/Controllers/ElectionReceiptController.php
public function index($electionSlug)
{
    $election = Election::where('slug', $electionSlug)->firstOrFail();
    
    // Security: Only show if results are published
    if (!$election->results_published_at) {
        abort(403, 'Results have not been published yet.');
    }
    
    // Get organisation for navigation
    $organisation = $election->organisation;
    
    // Get all receipt codes for this election
    $receiptCodes = ReceiptCode::where('election_id', $election->id)
        ->orderBy('created_at')  // Internal order - will be shuffled for display
        ->get();
    
    // Create randomized list with serial numbers
    $randomizedCodes = $receiptCodes->shuffle();
    
    // Add display data including reverified status
    $displayCodes = $randomizedCodes->map(function ($code, $index) {
        return [
            'serial' => $index + 1,
            'code' => $code->receipt_code,
            'is_reverified' => !is_null($code->reverified_at),
            'reverified_at' => $code->reverified_at ? $code->reverified_at->format('Y-m-d H:i:s') : null,
        ];
    });
    
    return Inertia::render('Election/ReceiptCodes', [
        'election' => [
            'id' => $election->id,
            'name' => $election->name,
            'slug' => $election->slug,
        ],
        'organisation' => [
            'id' => $organisation->id,
            'name' => $organisation->name,
            'slug' => $organisation->slug,
        ],
        'receipt_codes' => $displayCodes,
        'total_votes' => $receiptCodes->count(),
        'reverified_count' => $receiptCodes->whereNotNull('reverified_at')->count(),
        'published_at' => $election->results_published_at->format('F j, Y \a\t g:i A'),
        'last_updated' => now()->format('F j, Y \a\t g:i A'),
    ]);
}
```

---

## Visual Preview

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  # │ Receipt Code                                                    │ Reverified │
├────┼─────────────────────────────────────────────────────────────────┼────────────┤
│  1 │ f34d0921af807500bb658971e6c4ea09_a1974482-9345-4132-9d25-5cda4ab95070 │ ✅ Verified │
│  2 │ a7b3c9d2e5f8a1b4c6d9e2f3a5b7c8d9e1_a1974482-9345-4132-9d25-5cda4ab95071 │ ⭘ Pending   │
│  3 │ b8c4d9e2f3a5b7c8d9e1f2a3b4c5d6e7f8_a1974482-9345-4132-9d25-5cda4ab95072 │ ✅ Verified │
└─────────────────────────────────────────────────────────────────────────────────┘

✅ 2 of 3 voters have verified their votes
```

---

## Summary

| Element | Description |
|---------|-------------|
| **Green tickmark SVG** | ✅ Checkmark circle icon |
| **"Verified" text** | Green color, bold font |
| **"Pending" state** | Gray clock icon with "Pending" text |
| **Header** | "Reverified" column title |
| **Statistics footer** | Shows count of verified votes |
| **Legend** | Explains green checkmark meaning |

**This provides transparent verification tracking while maintaining voter anonymity.** 🔒✅