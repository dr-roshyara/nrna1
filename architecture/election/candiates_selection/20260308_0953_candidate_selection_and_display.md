
## ✅ **YES - I've Analyzed Both Files and Found the Issue!**

The problem is a **MISMATCH between what the controller sends and what the Vue component expects**.

### 🔍 **The Problem Identified:**

#### **Backend (Controller) sends:**
```php
// From DemoVoteController.php line 386-434
return Inertia::render('Vote/DemoVote/Create', [
    'national_posts' => $national_posts,  // ✅ Separate arrays
    'regional_posts' => $regional_posts,  // ✅ Separate arrays
    'user_name' => $auth_user->name,
    // ...
]);
```

#### **Frontend (Create.vue) expects:**
```vue
props: {
    posts: {  // ❌ Looking for 'posts' but controller sends 'national_posts'/'regional_posts'
        type: Object,
        required: true,
        default: () => ({ national: [], regional: [] })
    },
    user_name: String,
    // ...
}
```

### 📊 **Data Flow Breakdown:**

| Layer | What's Being Sent | What's Expected | Match? |
|-------|-------------------|-----------------|--------|
| **Controller** | `national_posts` (array) | `posts.national` | ❌ NO |
| **Controller** | `regional_posts` (array) | `posts.regional` | ❌ NO |
| **Vue Template** | `v-for="post in posts.national"` | `posts.national` | ❌ NO (undefined) |
| **Vue Template** | `v-for="post in posts.regional"` | `posts.regional` | ❌ NO (undefined) |

### 🎯 **Root Cause:**

The controller was refactored but the frontend wasn't updated to match. The Vue component expects a **single `posts` object** with `national` and `regional` properties, but the controller sends **two separate arrays**.

### ✅ **The Fix (Two Options):**

#### **Option 1: Update Controller (Recommended)**
```php
// In DemoVoteController.php create() method
return Inertia::render('Vote/DemoVote/Create', [
    'posts' => [
        'national' => $national_posts,
        'regional' => $regional_posts
    ],
    'user_name' => $auth_user->name,
    'user_region' => $auth_user->region,
    'slug' => $voterSlug?->slug,
    'useSlugPath' => $voterSlug !== null,
    'election' => $election ? [
        'id' => $election->id,
        'name' => $election->name,
        'type' => $election->type,
    ] : null,
]);
```

#### **Option 2: Update Vue Component**
```vue
// In Create.vue - change props expectation
props: {
    national_posts: Array,  // ✅ Match what controller sends
    regional_posts: Array,  // ✅ Match what controller sends
    user_name: String,
    // ...
}

// And in template:
<create-votingform
    v-for="(post, postIndex) in national_posts"  // ✅ Use national_posts directly
    :key="post.post_id"
    :post="post"
    :postIndex="postIndex"
    :selectedVotes="selectedVotes"
    @update-votes="handleVoteUpdate"
/>
```

### 🚀 **Recommended Action:**

**Choose Option 1** - Update the controller to send a single `posts` object. This is cleaner and matches what the Vue component already expects.

The tests I wrote earlier would have caught this immediately! Let's implement the fix and run the tests.
#############

# 🤖 **CLAUDE CODE CLI PROMPT: Fix DemoVoteController Post/Candidate Loading with TDD**

```bash
## TASK: Debug and Fix DemoVoteController Post/Candidate Loading

### Current Problem
When accessing `/v/{slug}/demo-vote/create`, no candidates are displayed in the frontend Vue component. The error could be in:
1. Backend: Controller not properly fetching/formatting posts with candidates
2. Frontend: Vue component not correctly processing the data structure

### Current Data Flow
```
Backend (DemoVoteController@create) → Inertia props → Frontend Vue Component
```

---

## 📋 **PHASE 1: Create Tests to Identify the Issue (TDD RED)**

### Step 1.1: Create Controller Response Test

```bash
# Create test to verify controller returns correct data structure
Write(tests/Feature/Demo/DemoVoteControllerCreateTest.php)
```

```php
<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\Demo\DemoPost;
use App\Models\Demo\DemoCandidacy;
use App\Models\Demo\DemoCode;
use App\Models\Demo\DemoVoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class DemoVoteControllerCreateTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Election $election;
    protected DemoCode $code;
    protected DemoVoterSlug $slug;
    protected DemoPost $nationalPost;
    protected DemoPost $regionalPost;
    protected DemoCandidacy $candidate1;
    protected DemoCandidacy $candidate2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'region' => 'Test Region'
        ]);

        $this->election = Election::factory()->create([
            'type' => 'demo',
            'status' => 'active'
        ]);

        $this->code = DemoCode::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'has_agreed_to_vote' => true,
            'can_vote_now' => true
        ]);

        $this->slug = DemoVoterSlug::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-123'
        ]);

        // Create national post
        $this->nationalPost = DemoPost::factory()->create([
            'election_id' => $this->election->id,
            'is_national_wide' => 1,
            'post_id' => 'president',
            'name' => 'President',
            'display_order' => 1,
            'required_number' => 1
        ]);

        // Create regional post
        $this->regionalPost = DemoPost::factory()->create([
            'election_id' => $this->election->id,
            'is_national_wide' => 0,
            'state_name' => 'Test Region',
            'post_id' => 'member',
            'name' => 'Member',
            'display_order' => 2,
            'required_number' => 1
        ]);

        // Create candidates for national post
        $this->candidate1 = DemoCandidacy::factory()->create([
            'post_id' => $this->nationalPost->id,
            'user_id' => $this->user->id,
            'candidacy_id' => 'cand-001',
            'user_name' => 'Candidate 1',
            'position_order' => 1
        ]);

        $this->candidate2 = DemoCandidacy::factory()->create([
            'post_id' => $this->nationalPost->id,
            'user_id' => $this->user->id,
            'candidacy_id' => 'cand-002',
            'user_name' => 'Candidate 2',
            'position_order' => 2
        ]);

        // Create candidates for regional post
        DemoCandidacy::factory()->create([
            'post_id' => $this->regionalPost->id,
            'user_id' => $this->user->id,
            'candidacy_id' => 'cand-003',
            'user_name' => 'Regional Candidate 1',
            'position_order' => 1
        ]);
    }

    /**
     * RED TEST 1: Controller returns posts grouped by type
     */
    public function test_controller_returns_posts_grouped_by_type()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Vote/DemoVote/Create')
            ->has('posts', fn (Assert $posts) => $posts
                ->has('national')
                ->has('regional')
            )
        );
    }

    /**
     * RED TEST 2: National posts contain correct data structure
     */
    public function test_national_posts_have_correct_structure()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.national.0.id', $this->nationalPost->id)
            ->where('posts.national.0.post_id', $this->nationalPost->post_id)
            ->where('posts.national.0.name', $this->nationalPost->name)
            ->where('posts.national.0.required_number', $this->nationalPost->required_number)
            ->has('posts.national.0.candidates', 2)
        );
    }

    /**
     * RED TEST 3: Regional posts filtered by user region
     */
    public function test_regional_posts_filtered_by_user_region()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.regional.0.id', $this->regionalPost->id)
            ->where('posts.regional.0.name', $this->regionalPost->name)
            ->has('posts.regional.0.candidates', 1)
        );
    }

    /**
     * RED TEST 4: Candidates ordered by position_order
     */
    public function test_candidates_ordered_by_position_order()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.national.0.candidates.0.id', $this->candidate1->id)
            ->where('posts.national.0.candidates.1.id', $this->candidate2->id)
        );
    }

    /**
     * RED TEST 5: Candidate data has required fields
     */
    public function test_candidate_has_required_fields()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.national.0.candidates.0.id', $this->candidate1->id)
            ->where('posts.national.0.candidates.0.candidacy_id', $this->candidate1->candidacy_id)
            ->where('posts.national.0.candidates.0.user_name', $this->candidate1->user_name)
            ->where('posts.national.0.candidates.0.post_id', $this->candidate1->post_id)
        );
    }

    /**
     * RED TEST 6: No regional posts when user has no region
     */
    public function test_no_regional_posts_when_user_has_no_region()
    {
        $userWithoutRegion = User::factory()->create(['region' => null]);
        DemoCode::factory()->create([
            'user_id' => $userWithoutRegion->id,
            'election_id' => $this->election->id,
            'has_agreed_to_vote' => true
        ]);
        
        $this->actingAs($userWithoutRegion);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.regional', [])
        );
    }
}
```

### Step 1.2: Create Frontend Component Test

```bash
# Create Vue component test
Write(tests/Feature/Demo/DemoVoteFrontendTest.php)
```

```php
<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\Demo\DemoPost;
use App\Models\Demo\DemoCandidacy;
use App\Models\Demo\DemoCode;
use App\Models\Demo\DemoVoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoVoteFrontendTest extends TestCase
{
    use RefreshDatabase;

    /**
     * RED TEST 7: Frontend receives expected data structure
     */
    public function test_frontend_receives_expected_data_structure()
    {
        $user = User::factory()->create(['region' => 'Test Region']);
        $election = Election::factory()->create(['type' => 'demo']);
        $slug = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id
        ]);
        
        DemoCode::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'has_agreed_to_vote' => true
        ]);

        $nationalPost = DemoPost::factory()->create([
            'election_id' => $election->id,
            'is_national_wide' => 1,
            'name' => 'President'
        ]);

        DemoCandidacy::factory()->create([
            'post_id' => $nationalPost->id,
            'user_name' => 'Test Candidate'
        ]);

        $this->actingAs($user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $slug->slug]));

        // Log the actual response data for debugging
        \Log::info('Frontend response data', [
            'inertia' => $response->baseResponse->original->getData()['page']['props'] ?? null
        ]);

        $response->assertOk();
        
        // Check that Inertia props contain posts
        $props = $response->baseResponse->original->getData()['page']['props'];
        $this->assertArrayHasKey('posts', $props);
        $this->assertArrayHasKey('national', $props['posts']);
        $this->assertArrayHasKey('regional', $props['posts']);
    }
}
```

---

## 📋 **PHASE 2: Debug Current Controller (Before Fix)**

```bash
# Add debug logging to current controller
php artisan tinker
```

```php
// In tinker - Check if data exists in database
$electionId = 'your-election-id';
$posts = DemoPost::where('election_id', $electionId)->get();
$posts->count(); // How many posts?
$posts->first()->candidacies; // Any candidates?

// Check specific post
$post = DemoPost::with('candidacies')->where('election_id', $electionId)->first();
$post->toArray(); // See full structure
```

---

## 📋 **PHASE 3: Fix Controller (GREEN)**

### Step 3.1: Update DemoVoteController@create method

```bash
# Edit app/Http/Controllers/Demo/DemoVoteController.php
```

Replace the post fetching section (around lines 386-434) with:

```php
// --- Fetch National Posts and Candidates ---
if ($election->isDemo()) {
    // ✅ DEBUG: Log what we're fetching
    Log::info('Fetching demo posts for election', [
        'election_id' => $election->id,
        'user_region' => $auth_user->region
    ]);

    // National posts - WITH candidacies eagerly loaded
    $nationalPosts = DemoPost::where('election_id', $election->id)
        ->where('is_national_wide', 1)
        ->with(['candidacies' => function($query) {
            $query->orderBy('position_order');
        }])
        ->orderBy('display_order')
        ->get();

    Log::info('Found national posts', [
        'count' => $nationalPosts->count(),
        'post_ids' => $nationalPosts->pluck('id')
    ]);

    $national_posts = $nationalPosts->map(function ($post) {
        return [
            'id' => $post->id,
            'post_id' => $post->post_id,
            'name' => $post->name,
            'nepali_name' => $post->nepali_name,
            'required_number' => $post->required_number,
            'display_order' => $post->display_order,
            'candidates' => $post->candidacies->map(function ($c) {
                return [
                    'id' => $c->id,
                    'candidacy_id' => $c->candidacy_id,
                    'user_id' => $c->user_id,
                    'user_name' => $c->user_name ?? 'Demo Candidate',
                    'post_id' => $c->post_id,
                    'image_path_1' => $c->image_path_1,
                    'candidacy_name' => $c->candidacy_name,
                    'proposer_name' => $c->proposer_name,
                    'supporter_name' => $c->supporter_name,
                    'position_order' => $c->position_order,
                ];
            })->values()->toArray(),
        ];
    })->values();

    // Regional posts - only if user has region
    $regional_posts = collect();
    if (!empty($auth_user->region)) {
        Log::info('Fetching regional posts for region', [
            'region' => $auth_user->region
        ]);

        $regionalPostsQuery = DemoPost::where('election_id', $election->id)
            ->where('is_national_wide', 0)
            ->where('state_name', trim($auth_user->region))
            ->with(['candidacies' => function($query) {
                $query->orderBy('position_order');
            }])
            ->orderBy('display_order')
            ->get();

        Log::info('Found regional posts', [
            'count' => $regionalPostsQuery->count()
        ]);

        $regional_posts = $regionalPostsQuery->map(function ($post) {
            return [
                'id' => $post->id,
                'post_id' => $post->post_id,
                'name' => $post->name,
                'nepali_name' => $post->nepali_name,
                'required_number' => $post->required_number,
                'display_order' => $post->display_order,
                'candidates' => $post->candidacies->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'candidacy_id' => $c->candidacy_id,
                        'user_id' => $c->user_id,
                        'user_name' => $c->user_name ?? 'Demo Candidate',
                        'post_id' => $c->post_id,
                        'image_path_1' => $c->image_path_1,
                        'candidacy_name' => $c->candidacy_name,
                        'proposer_name' => $c->proposer_name,
                        'supporter_name' => $c->supporter_name,
                        'position_order' => $c->position_order,
                    ];
                })->values()->toArray(),
            ];
        })->values();
    }

    // ✅ CRITICAL: Combine into single 'posts' structure
    $posts = [
        'national' => $national_posts,
        'regional' => $regional_posts
    ];

    Log::info('Final posts structure', [
        'national_count' => count($posts['national']),
        'regional_count' => count($posts['regional']),
        'sample' => json_encode($posts, JSON_PRETTY_PRINT)
    ]);
}
```

### Step 3.2: Update the Inertia render to pass posts

```php
// At the end of create() method, update the return:

return Inertia::render('Vote/DemoVote/Create', [
    'posts' => $posts,  // ✅ Use combined structure
    'user_name' => $auth_user->name,
    'user_region' => $auth_user->region,
    'slug' => $voterSlug?->slug,
    'useSlugPath' => $voterSlug !== null,
    'election' => $election ? [
        'id' => $election->id,
        'name' => $election->name,
        'type' => $election->type,
        'description' => $election->description,
        'is_active' => $election->is_active,
    ] : null,
]);
```

---

## 📋 **PHASE 4: Update Frontend Component**

### Step 4.1: Update Vue template to use correct structure

```bash
# Edit resources/js/Pages/Vote/DemoVote/Create.vue
```

Update the template section:

```vue
<template>
    <nrna-layout>
        <app-layout>
            <div class="mt-6 text-center max-w-4xl mx-auto">
                <!-- Success Message -->
                <div class="m-auto text-center bg-gradient-to-r from-green-500 to-blue-600 text-white py-6 px-8 rounded-xl shadow-lg mb-8">
                    <div class="text-4xl mb-3">🎉</div>
                    <p class="text-xl font-bold mb-2">Welcome {{ user_name }}!</p>
                    <p class="text-lg mb-2">Your code has been verified. You can now vote!</p>
                    <p class="mb-3">Please select the candidates of your choice</p>
                    <p class="text-sm opacity-90">आपको कोड सत्यापित भएको छ। कृपया अब आफ्नो इच्छा अनुसार मतदान गर्न सक्नु हुने छ।</p>
                </div>

                <!-- Validation Errors -->
                <jet-validation-errors class="mb-6 mx-auto text-center" />

                <!-- Voting Form -->
                <form @submit.prevent="submit" class="text-center mx-auto mt-8">
                    <!-- National Posts Section -->
                    <div v-if="posts.national && posts.national.length" class="mb-8">
                        <h2 class="text-2xl font-bold mb-4 text-gray-800">National Posts</h2>
                        <create-votingform
                            v-for="(post, postIndex) in posts.national"
                            :key="post.id"
                            :post="post"
                            :postIndex="postIndex"
                            :selectedVotes="selectedVotes"
                            @update-votes="handleVoteUpdate"
                        />
                    </div>

                    <!-- Regional Posts Section -->
                    <div v-if="posts.regional && posts.regional.length" class="mb-8">
                        <h2 class="text-2xl font-bold mb-4 text-gray-800">Regional Posts</h2>
                        <create-votingform
                            v-for="(post, postIndex) in posts.regional"
                            :key="post.id"
                            :post="post"
                            :postIndex="postIndex"
                            :selectedVotes="selectedVotes"
                            @update-votes="handleVoteUpdate"
                        />
                    </div>

                    <!-- No Posts Message -->
                    <div v-if="(!posts.national || !posts.national.length) && (!posts.regional || !posts.regional.length)" 
                         class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 mb-6">
                        <p class="text-yellow-800 text-lg">No voting positions available for this election.</p>
                    </div>

                    <!-- Errors -->
                    <div v-if="errors.votes" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <p class="text-red-800 font-semibold">{{ errors.votes }}</p>
                    </div>

                    <!-- Agreement and Submit Section -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mt-8">
                        <!-- Agreement Section -->
                        <div class="border-2 border-blue-300 rounded-lg p-6 mb-6 bg-blue-50">
                            <!-- Header -->
                            <div class="flex flex-col items-center justify-center mb-6">
                                <div class="text-3xl mb-2">✅</div>
                                <h3 class="text-xl font-bold text-red-700 mb-1">Voting Agreement | मतदान समझौता</h3>
                                <p class="text-lg font-semibold text-red-700">मतदान गरेको स्विकार</p>
                            </div>

                            <!-- Checkbox -->
                            <div class="flex justify-center mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        v-model="form.agree_button"
                                        class="w-5 h-5 text-blue-600 border-2 border-gray-400 rounded-sm focus:ring-blue-500 focus:ring-2"
                                    />
                                    <span class="ml-3 text-lg font-medium text-gray-900">I agree to the terms</span>
                                </label>
                            </div>

                            <!-- Agreement Text -->
                            <div class="bg-white rounded-lg p-4 border border-gray-200 mb-4">
                                <p class="text-gray-700 mb-3 leading-relaxed">
                                    By clicking this button, I confirm that I have chosen the candidates correctly and I followed the online rules to vote the candidates.
                                </p>
                                <p class="text-gray-700 text-sm leading-relaxed">
                                    यो बटनमा थिचेर मैले माथि छाने आनुसार मतदान गरेको साचो हो। मैले बिद्दुतिय नियम हरुलाई पलना गरेर आफ्नो मत जाहेर गरेर मतदान गरेको कुरा स्विकार्छु।
                                </p>
                            </div>

                            <!-- Checkbox Error -->
                            <div v-if="errors.agree_button" class="text-red-600 text-sm mb-4 bg-red-50 p-2 rounded-sm">
                                {{ errors.agree_button }}
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold text-xl py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105"
                                :disabled="!form.agree_button"
                                :class="{ 'opacity-50 cursor-not-allowed': !form.agree_button }"
                            >
                                <span class="mr-2">🗳️</span>
                                Submit Your Vote
                            </button>
                        </div>
                    </div>

                    <!-- Form Validation Errors -->
                    <div class="mx-auto text-center mt-6">
                        <jet-validation-errors class="mb-4 mx-auto text-center" />
                    </div>
                </form>
            </div>
        </app-layout>
    </nrna-layout>
</template>
```

### Step 4.2: Update Vue script props

```javascript
props: {
    posts: {
        type: Object,
        required: true,
        default: () => ({ national: [], regional: [] })
    },
    user_name: String,
    user_id: Number,
    slug: String,
    useSlugPath: Boolean,
    election: Object,
},
```

---

## 📋 **PHASE 5: Run Tests (GREEN Verification)**

```bash
# Run the tests we created
php artisan test tests/Feature/Demo/DemoVoteControllerCreateTest.php
php artisan test tests/Feature/Demo/DemoVoteFrontendTest.php

# Run all tests
php artisan test

# Expected: All tests passing (GREEN)
```

---

## 📋 **PHASE 6: Manual Verification**

```bash
# 1. Check database has data
php artisan tinker
```

```php
$election = Election::where('type', 'demo')->first();
$posts = DemoPost::with('candidacies')
    ->where('election_id', $election->id)
    ->get();
$posts->toArray(); // Verify data exists
exit
```

```bash
# 2. Check browser console
# Open browser dev tools (F12)
# Check Network tab for Inertia response
# Check Vue devtools for props data

# 3. Check Laravel logs
tail -f storage/logs/laravel.log | grep "Fetching demo posts"
```

---

## 📋 **VERIFICATION CHECKLIST**

```markdown
## Debug Checklist

### Backend Checks
- [ ] Election exists with type 'demo'
- [ ] Posts exist for this election
- [ ] Posts have is_national_wide flag set correctly
- [ ] Candidacies exist for posts
- [ ] Controller logs show data being fetched
- [ ] Inertia props contain 'posts' with national/regional structure

### Frontend Checks
- [ ] Vue component receives props correctly
- [ ] Template loops through posts.national
- [ ] Template loops through posts.regional
- [ ] create-votingform component receives post data
- [ ] No console errors

### Data Structure Verification
Expected structure in Vue:
```javascript
props: {
    posts: {
        national: [
            {
                id: 1,
                post_id: 'president',
                name: 'President',
                candidates: [
                    { id: 1, user_name: 'Candidate 1' }
                ]
            }
        ],
        regional: [
            {
                id: 2,
                post_id: 'member',
                name: 'Member',
                candidates: []
            }
        ]
    }
}
```
```

---

## 📋 **COMMIT CHANGES**

```bash
git checkout -b fix/demo-vote-candidates

git add app/Http/Controllers/Demo/DemoVoteController.php
git add resources/js/Pages/Vote/DemoVote/Create.vue
git add tests/Feature/Demo/DemoVoteControllerCreateTest.php
git add tests/Feature/Demo/DemoVoteFrontendTest.php

git commit -m "fix: DemoVoteController post/candidate loading

- Add comprehensive tests for post/candidate loading
- Fix controller to properly fetch posts with eager loaded candidacies
- Update frontend to use correct posts structure
- Add debug logging for troubleshooting
- Ensure posts ordered by display_order
- Ensure candidates ordered by position_order

All tests passing (GREEN)"
```

---

## 📋 **SUMMARY**

| Test | Purpose | Status |
|------|---------|--------|
| Test 1 | Posts grouped by type | 🔴 RED (failing) |
| Test 2 | National posts structure | 🔴 RED (failing) |
| Test 3 | Regional posts filtered | 🔴 RED (failing) |
| Test 4 | Candidates ordered | 🔴 RED (failing) |
| Test 5 | Candidate fields | 🔴 RED (failing) |
| Test 6 | No region handling | 🔴 RED (failing) |
| Test 7 | Frontend data | 🔴 RED (failing) |

**Ready to implement fixes and make all tests pass!** 🚀
```