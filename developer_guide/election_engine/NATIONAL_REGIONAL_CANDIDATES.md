# 🗳️ National and Regional Candidates Architecture Guide

**Version:** 2.0
**Last Updated:** February 2026
**Status:** Production Ready
**Audience:** Backend & Frontend Developers

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Architecture Principles](#architecture-principles)
3. [Data Model](#data-model)
4. [Backend Implementation](#backend-implementation)
5. [Frontend Implementation](#frontend-implementation)
6. [Regional Filtering Logic](#regional-filtering-logic)
7. [Complete Data Flow](#complete-data-flow)
8. [Common Patterns](#common-patterns)
9. [Testing Strategy](#testing-strategy)
10. [Troubleshooting](#troubleshooting)

---

## Overview

### Purpose

The voting system supports **two types of elections**:

- **National Elections**: President, Vice President, Secretary (all voters see same candidates)
- **Regional Elections**: State Representatives (voters see only candidates from their region)

### Key Principle

> **Posts define regions, not candidates.**
>
> The `Post` model stores region information (`is_national_wide`, `state_name`).
> Candidates only know which post they're running for, never which region.
> This ensures **single source of truth** and **no data redundancy**.

### Example

```
National Post (President):
├─ is_national_wide: 1
├─ state_name: NULL
└─ Candidates: John, Jane, Bob (all voters see these)

Regional Post (State Rep - Bayern):
├─ is_national_wide: 0
├─ state_name: "Bayern"
└─ Candidates: Hans, Anna (only Bayern voters see these)

Regional Post (State Rep - Hessen):
├─ is_national_wide: 0
├─ state_name: "Hessen"
└─ Candidates: Klaus, Maria (only Hessen voters see these)
```

---

## Architecture Principles

### 1. Clean Separation of Concerns

**Posts** → Determine visibility (national vs regional)
**Candidates** → Represent people running for positions
**Users** → Have a region property that filters what they see

### 2. Single Source of Truth

Region information is stored **ONCE** on the `Post` model:
- `is_national_wide`: Indicates if post is national (1) or regional (0)
- `state_name`: Specifies which region (only if regional)

**NOT** on candidates (no redundancy, no inconsistency).

### 3. Database-Level Filtering

Filtering happens in the **database query**, not in application code:

```php
// ✅ CORRECT: Filter at DB level
$posts = Post::where('is_national_wide', 0)
    ->where('state_name', $user->region)
    ->get();

// ❌ WRONG: Filter in application
$posts = Post::where('is_national_wide', 0)->get();
$filtered = $posts->filter(fn($p) => $p->state_name === $user->region);
```

### 4. Regional Isolation

Voters **cannot** see posts or candidates from other regions:

- Bayern voter → sees Bayern posts only
- Hessen voter → sees Hessen posts only
- National posts → visible to all

---

## Data Model

### Posts Table

```sql
-- posts table (real elections)
CREATE TABLE posts (
    id BIGINT PRIMARY KEY,
    election_id BIGINT NOT NULL,
    post_id INT NOT NULL,
    name VARCHAR(255),              -- "President", "State Rep", etc.
    nepali_name VARCHAR(255),
    is_national_wide BOOLEAN,       -- 1 = national, 0 = regional
    state_name VARCHAR(100),        -- NULL for national, "Bayern", etc. for regional
    required_number INT,            -- How many to elect
    position_order INT,             -- Display order
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX (election_id, is_national_wide),
    INDEX (election_id, state_name)
);

-- demo_posts table (demo elections)
CREATE TABLE demo_posts (
    id BIGINT PRIMARY KEY,
    election_id BIGINT NOT NULL,
    post_id INT NOT NULL,
    name VARCHAR(255),
    nepali_name VARCHAR(255),
    is_national_wide BOOLEAN,
    state_name VARCHAR(100),        -- Region filter
    required_number INT,
    position_order INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX (election_id, is_national_wide),
    INDEX (election_id, state_name)
);
```

### Candidates Table

```sql
-- candidacies table (real elections)
CREATE TABLE candidacies (
    id BIGINT PRIMARY KEY,
    post_id BIGINT NOT NULL,        -- FK to posts.id
    user_id BIGINT NOT NULL,
    candidacy_id INT UNIQUE,
    image_path_1 VARCHAR(255),
    image_path_2 VARCHAR(255),
    image_path_3 VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- ⚠️ NO region column! (Single source of truth is on Post)
    FOREIGN KEY (post_id) REFERENCES posts(id)
);

-- demo_candidacies table (demo elections)
CREATE TABLE demo_candidacies (
    id BIGINT PRIMARY KEY,
    post_id INT NOT NULL,           -- Links to demo_posts.post_id
    candidacy_id INT,
    user_id INT,
    user_name VARCHAR(255),
    election_id BIGINT NOT NULL,
    candidacy_name VARCHAR(255),
    proposer_name VARCHAR(255),
    supporter_name VARCHAR(255),
    image_path_1 VARCHAR(255),
    image_path_2 VARCHAR(255),
    image_path_3 VARCHAR(255),
    position_order INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- ⚠️ NO region column! (Region comes from Post)
    INDEX (post_id),
    INDEX (election_id)
);
```

### Users Table (Relevant Fields)

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    region VARCHAR(100),            -- "Bayern", "Hessen", etc.
    can_vote_now BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Key Design Principle

```
❌ DON'T add region to candidacies table
✅ DO filter by Post.state_name when fetching candidates

Why?
- Candidate runs for a POSITION, not a REGION
- Position (Post) determines the region
- If region changes on post, all candidates automatically affected
- Single source of truth eliminates sync issues
```

---

## Backend Implementation

### 1. VoteController - Real Elections

**File:** `app/Http/Controllers/VoteController.php`

#### National Posts Query

```php
public function create(Request $request)
{
    $auth_user = $this->getUser($request);
    $election = $this->getElection($request);

    // Get ALL candidates for this election
    $allCandidates = Candidacy::where('election_id', $election->id)
        ->with('user')
        ->get();

    // Group by post_id for efficient lookup
    $groupedCandidates = $allCandidates->groupBy('post_id');

    // NATIONAL POSTS: visible to all voters
    $national_posts = QueryBuilder::for(Post::class)
        ->where('election_id', $election->id)
        ->where('is_national_wide', 1)              // ← Filter by national flag
        ->orderBy('position_order')
        ->get()
        ->map(function ($post) use ($groupedCandidates) {
            return [
                'post_id' => $post->post_id,
                'name' => $post->name,
                'nepali_name' => $post->nepali_name,
                'required_number' => $post->required_number,
                'position_order' => $post->position_order,
                'candidates' => $groupedCandidates
                    ->get($post->post_id, collect())
                    ->map(function ($c) {
                        return [
                            'candidacy_id' => $c->candidacy_id,
                            'user' => [
                                'id' => $c->user_id,
                                'name' => $c->user->name,
                            ],
                            'image_path_1' => $c->image_path_1,
                            'candidacy_name' => $c->candidacy_name,
                            'position_order' => $c->position_order,
                        ];
                    })->values(),
            ];
        })->values();

    return $national_posts;
}
```

#### Regional Posts Query

```php
public function create(Request $request)
{
    $auth_user = $this->getUser($request);
    $election = $this->getElection($request);

    $groupedCandidates = $allCandidates->groupBy('post_id');

    // REGIONAL POSTS: visible only to voters from that region
    $regional_posts = collect();

    if (!empty($auth_user->region)) {
        $regional_posts = QueryBuilder::for(Post::class)
            ->where('election_id', $election->id)
            ->where('is_national_wide', 0)          // ← Filter: regional only
            ->where('state_name', trim($auth_user->region))  // ← Filter: user's region
            ->orderBy('position_order')
            ->get()
            ->map(function ($post) use ($groupedCandidates) {
                return [
                    'post_id' => $post->post_id,
                    'name' => $post->name,
                    'nepali_name' => $post->nepali_name,
                    'required_number' => $post->required_number,
                    'position_order' => $post->position_order,
                    'candidates' => $groupedCandidates
                        ->get($post->post_id, collect())
                        ->map(function ($c) {
                            return [
                                'candidacy_id' => $c->candidacy_id,
                                'user' => [
                                    'id' => $c->user_id,
                                    'name' => $c->user->name,
                                ],
                                'image_path_1' => $c->image_path_1,
                                'candidacy_name' => $c->candidacy_name,
                                'position_order' => $c->position_order,
                            ];
                        })->values(),
                ];
            })->values();
    }

    return $regional_posts;
}
```

#### Return to Frontend

```php
public function create(Request $request)
{
    // ... queries above ...

    return Inertia::render('Vote/CreateVotingPage', [
        'national_posts' => $national_posts,    // All national posts
        'regional_posts' => $regional_posts,    // Only user's region posts
        'user_region' => $auth_user->region,    // For display labels
        'election_name' => $election->name,
    ]);
}
```

### 2. DemoVoteController - Demo Elections

**File:** `app/Http/Controllers/Demo/DemoVoteController.php`

**Same logic as VoteController, but using Demo models:**

```php
public function create(Request $request)
{
    $auth_user = $this->getUser($request);
    $election = $this->getElection($request);

    // Get ALL demo candidates for this election
    $allCandidates = DemoCandidacy::where('election_id', $election->id)
        ->orderBy('position_order')
        ->get();

    $groupedCandidates = $allCandidates->groupBy('post_id');

    // NATIONAL POSTS
    $national_posts = DemoPost::where('election_id', $election->id)
        ->where('is_national_wide', 1)
        ->orderBy('position_order')
        ->get()
        ->map(function ($post) use ($groupedCandidates) {
            // ... map candidates ...
        })->values();

    // REGIONAL POSTS
    $regional_posts = DemoPost::where('election_id', $election->id)
        ->where('is_national_wide', 0)
        ->where('state_name', trim($auth_user->region))
        ->orderBy('position_order')
        ->get()
        ->map(function ($post) use ($groupedCandidates) {
            // ... map candidates ...
        })->values();

    return Inertia::render('Vote/CreateVotingPage', [
        'national_posts' => $national_posts,
        'regional_posts' => $regional_posts,
        'user_region' => $auth_user->region,
        // ...
    ]);
}
```

---

## Frontend Implementation

### CreateVotingPage.vue - Main Component

**File:** `resources/js/Pages/Vote/DemoVote/CreateVotingPage.vue`

```vue
<template>
  <election-layout>
    <!-- National Posts Section -->
    <section v-if="national_posts && national_posts.length > 0" class="mb-12">
      <h2 class="text-3xl font-bold text-center mb-8">
        National Candidates
      </h2>
      <div class="space-y-8">
        <div v-for="(post, postIndex) in national_posts"
             :key="`national-${post.post_id}`"
             class="bg-white rounded-xl shadow-lg p-6">
          <create-votingform
            :candidates="post.candidates"
            :post="post"
            @add_selected_candidates="handleCandidateSelection('national', postIndex, $event)"
          />
        </div>
      </div>
    </section>

    <!-- Regional Posts Section -->
    <section v-if="regional_posts && regional_posts.length > 0" class="mb-12">
      <h2 class="text-3xl font-bold text-center mb-8">
        Candidates for {{ user_region }} Region
      </h2>
      <div class="space-y-8">
        <div v-for="(post, postIndex) in regional_posts"
             :key="`regional-${post.post_id}`"
             class="bg-white rounded-xl shadow-lg p-6">
          <create-votingform
            :candidates="post.candidates"
            :post="post"
            @add_selected_candidates="handleCandidateSelection('regional', postIndex, $event)"
          />
        </div>
      </div>
    </section>

    <!-- No Regional Posts Fallback -->
    <section v-if="!regional_posts || regional_posts.length === 0 && user_region">
      <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-8 text-center">
        <h2 class="text-2xl font-bold text-yellow-800 mb-4">
          No Regional Candidates
        </h2>
        <p class="text-yellow-700">
          There are currently no candidates available for your region ({{ user_region }}).
        </p>
      </div>
    </section>
  </election-layout>
</template>

<script setup>
import { ref, computed } from 'vue'
import CreateVotingform from './CreateVotingform.vue'

const props = defineProps({
  national_posts: {
    type: Array,
    default: () => []
  },
  regional_posts: {
    type: Array,
    default: () => []
  },
  user_region: {
    type: String,
    default: null
  },
  election_name: {
    type: String,
    default: 'Election'
  }
})

const national_selected_candidates = ref([])
const regional_selected_candidates = ref([])

// Handle selections separately for national/regional
function handleCandidateSelection(type, postIndex, selectionData) {
  if (type === 'national') {
    national_selected_candidates.value[postIndex] = selectionData
  } else if (type === 'regional') {
    regional_selected_candidates.value[postIndex] = selectionData
  }
}
</script>
```

### CreateVotingform.vue - Per-Post Component

**File:** `resources/js/Pages/Vote/DemoVote/CreateVotingform.vue`

```vue
<template>
  <section class="candidate-selection">
    <!-- Post Header -->
    <div class="bg-linear-to-r from-blue-600 to-indigo-700 px-6 py-5 text-white">
      <h2 :id="`post-title-${post.post_id}`" class="text-2xl font-bold mb-1">
        {{ post.name }}
      </h2>
      <p class="text-blue-100">
        Select {{ post.required_number }} candidate(s)
      </p>
    </div>

    <!-- Main Content -->
    <div class="p-6">
      <!-- Candidates Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
        <div
          v-for="candidate in candidates"
          :key="`${post.post_id}-${candidate.candidacy_id}`"
          class="candidate-card relative flex flex-col items-center"
          :class="{
            'ring-4 ring-blue-500': isSelected(candidate),
            'opacity-60': noVoteSelected
          }"
        >
          <!-- Candidate Card -->
          <div class="w-full bg-white border-2 border-gray-200 rounded-xl overflow-hidden">
            <!-- Post Name Label -->
            <div class="bg-linear-to-r from-blue-500 to-blue-600 text-white text-center px-3 py-2">
              <p class="text-xs font-bold">
                Candidate for {{ post.name }}
              </p>
            </div>

            <!-- Photo -->
            <div class="flex justify-center p-8 bg-white">
              <div class="w-40 h-40 shrink-0 rounded-lg overflow-hidden border-2 border-gray-200">
                <show-candidate
                  :candidacy_image_path="candidate.image_path_1"
                  :candidacy_name="candidate.candidacy_name"
                />
              </div>
            </div>

            <!-- Checkbox Section -->
            <div class="w-full border-t-2 border-gray-200 p-6 flex flex-col items-center">
              <!-- Candidate Name -->
              <h3 class="text-sm font-bold text-gray-900 line-clamp-2 text-center mb-3">
                {{ candidate.user?.name || 'Unknown' }}
              </h3>

              <!-- Checkbox -->
              <input
                type="checkbox"
                :id="`candidate-${post.post_id}-${candidate.candidacy_id}`"
                :checked="isSelected(candidate)"
                @change="toggleSelection(candidate)"
                :disabled="noVoteSelected"
                class="w-5 h-5 cursor-pointer"
              />
              <label
                :for="`candidate-${post.post_id}-${candidate.candidacy_id}`"
                class="text-sm font-medium text-gray-700 ml-2 cursor-pointer"
              >
                Select
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Skip/No Vote Option -->
      <div class="border-2 border-gray-300 rounded-xl p-6 bg-gray-50">
        <input
          type="checkbox"
          :id="`no_vote_${post.post_id}`"
          v-model="noVoteSelected"
          @change="handleNoVoteChange"
          class="w-4 h-4 cursor-pointer"
        />
        <label
          :for="`no_vote_${post.post_id}`"
          class="ml-2 font-bold text-gray-900 cursor-pointer"
        >
          I don't want to vote for this position
        </label>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import ShowCandidate from '@/Shared/ShowCandidate'

const props = defineProps({
  post: {
    type: Object,
    required: true
  },
  candidates: {
    type: Array,
    default: () => []
  },
  postIndex: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['add_selected_candidates'])

// Store selections KEYED BY POST_ID to prevent cross-post interference
const selectedByPost = ref({})
const noVoteSelected = ref(false)

// Get selected candidates for THIS specific post
const selected = computed(() => selectedByPost.value[props.post.post_id] || [])

function isSelected(candidate) {
  return selected.value.includes(candidate.candidacy_id)
}

function toggleSelection(candidate) {
  if (noVoteSelected.value) return

  const currentSelected = [...selected.value]
  const index = currentSelected.indexOf(candidate.candidacy_id)

  if (index === -1) {
    // Add candidate
    if (currentSelected.length < props.post.required_number) {
      currentSelected.push(candidate.candidacy_id)
    }
  } else {
    // Remove candidate
    currentSelected.splice(index, 1)
  }

  // Store keyed by post_id ← CRITICAL for isolation
  const newSelected = { ...selectedByPost.value }
  newSelected[props.post.post_id] = currentSelected
  selectedByPost.value = newSelected

  informSelectedCandidates()
}

function handleNoVoteChange() {
  if (noVoteSelected.value) {
    // Clear selections for this post
    const newSelected = { ...selectedByPost.value }
    newSelected[props.post.post_id] = []
    selectedByPost.value = newSelected
  }

  informSelectedCandidates()
}

function informSelectedCandidates() {
  const selectionData = {
    post_id: props.post.post_id,
    post_name: props.post.name,
    required_number: props.post.required_number,
    no_vote: noVoteSelected.value,
    candidates: props.candidates
      .filter(c => selected.value.includes(c.candidacy_id))
      .map(c => ({
        candidacy_id: c.candidacy_id,
        user_id: c.user?.id,
        name: c.user?.name,
        post_id: c.post_id || props.post.post_id
      }))
  }

  emit('add_selected_candidates', selectionData)
}
</script>
```

---

## Regional Filtering Logic

### Decision Tree

```
User accesses voting page
│
├─ Get user.region
│
├─ Query national posts
│  └─ WHERE is_national_wide = 1
│     └─ Show to all users
│
├─ Query regional posts
│  ├─ WHERE is_national_wide = 0
│  ├─ WHERE state_name = user.region
│  └─ Show only if user has this region
│
└─ Combine both arrays
   └─ Send to frontend as separate arrays
```

### SQL Queries

```sql
-- National posts (visible to everyone)
SELECT * FROM posts
WHERE election_id = ?
  AND is_national_wide = 1
ORDER BY position_order;

-- Regional posts (visible only to user's region)
SELECT * FROM posts
WHERE election_id = ?
  AND is_national_wide = 0
  AND state_name = ?  -- user.region parameter
ORDER BY position_order;

-- Candidates for a specific post
SELECT c.* FROM candidacies c
WHERE c.post_id = ?
  AND c.election_id = ?  -- Implicit via post relationship
ORDER BY c.position_order;
```

### Performance Considerations

**Indexes to create:**

```sql
-- Optimize national posts query
CREATE INDEX posts_election_national
ON posts(election_id, is_national_wide);

-- Optimize regional posts query
CREATE INDEX posts_election_region
ON posts(election_id, is_national_wide, state_name);

-- Optimize candidate lookup
CREATE INDEX candidacies_post
ON candidacies(post_id);
```

---

## Complete Data Flow

### Sequence Diagram

```
┌─────────┐                ┌──────────────┐              ┌──────────┐
│  User   │                │  Controller  │              │ Database │
└────┬────┘                └──────┬───────┘              └────┬─────┘
     │                            │                           │
     │  GET /vote/create          │                           │
     ├───────────────────────────>│                           │
     │                            │                           │
     │                            │  Query national posts     │
     │                            ├──────────────────────────>│
     │                            │  WHERE is_national_wide=1 │
     │                            │<──────────────────────────┤
     │                            │  [President, VP, Sec]     │
     │                            │                           │
     │                            │  Query regional posts     │
     │                            ├──────────────────────────>│
     │                            │  WHERE is_national_wide=0 │
     │                            │    AND state_name='Bayern'│
     │                            │<──────────────────────────┤
     │                            │  [State Rep Bayern]       │
     │                            │                           │
     │                            │  Query candidates         │
     │                            ├──────────────────────────>│
     │                            │  For each post            │
     │                            │<──────────────────────────┤
     │                            │  [Candidates by post_id]  │
     │                            │                           │
     │ Inertia response           │                           │
     │ national_posts[]           │                           │
     │ regional_posts[]           │                           │
     │<───────────────────────────┤                           │
     │                            │                           │
     │  CreateVotingPage renders  │                           │
     │  ├─ National section       │                           │
     │  └─ Regional section       │                           │
     │                            │                           │
     │  User selects candidates   │                           │
     │  (state: selectedByPost)   │                           │
     │                            │                           │
     │  Submit votes              │                           │
     ├───────────────────────────>│                           │
     │  POST /vote/submit         │                           │
     │  {national: [...],         │                           │
     │   regional: [...]}         │                           │
     │                            │  Save votes               │
     │                            ├──────────────────────────>│
     │                            │                           │
     │  Success response          │                           │
     │<───────────────────────────┤                           │
```

### Example Data Flow

**Input:**
- User: Hans (Region: Bayern)
- Election ID: 1

**Backend Processing:**

```php
// Step 1: Get all candidates
$candidates = DemoCandidacy::where('election_id', 1)->get();
// Returns: 20 candidates total

// Step 2: Group by post_id
$grouped = [
    1 => [Candidate 1, Candidate 2, Candidate 3],      // President
    2 => [Candidate 4, Candidate 5, Candidate 6],      // VP
    3 => [Candidate 7, Candidate 8, Candidate 9],      // Secretary
    4 => [Candidate 10, Candidate 11],                 // State Rep Bayern
    5 => [Candidate 12, Candidate 13]                  // State Rep Hessen
]

// Step 3: Query national posts
$national = DemoPost::where('election_id', 1)
    ->where('is_national_wide', 1)
    ->get();
// Returns: [President, VP, Secretary]

// Step 4: Query regional posts for Bayern
$regional = DemoPost::where('election_id', 1)
    ->where('is_national_wide', 0)
    ->where('state_name', 'Bayern')
    ->get();
// Returns: [State Rep Bayern]

// Step 5: Build response
return [
    'national_posts' => [
        {
            'post_id' => 1,
            'name' => 'President',
            'required_number' => 1,
            'candidates' => [Candidate 1, 2, 3]
        },
        {
            'post_id' => 2,
            'name' => 'VP',
            'required_number' => 1,
            'candidates' => [Candidate 4, 5, 6]
        },
        // ... Secretary
    ],
    'regional_posts' => [
        {
            'post_id' => 4,
            'name' => 'State Rep',
            'state_name' => 'Bayern',
            'candidates' => [Candidate 10, 11]
        }
    ]
]
```

**Frontend:**
- Renders National section with 3 posts
- Renders Regional section with 1 post (Bayern only)
- Hans cannot see State Rep Hessen candidates

---

## Common Patterns

### Pattern 1: Adding a New Regional Post

**Step 1: Create Post**
```php
DemoPost::create([
    'election_id' => 1,
    'post_id' => 5,
    'name' => 'District Mayor',
    'is_national_wide' => 0,
    'state_name' => 'Bavaria',      // ← Specifies region
    'required_number' => 1,
    'position_order' => 5
]);
```

**Step 2: Add Candidates**
```php
DemoCandidacy::create([
    'post_id' => 5,
    'candidacy_id' => 50,
    'election_id' => 1,
    'user_name' => 'Klaus Mueller',
    'candidacy_name' => 'Klaus Mueller'
]);
```

**Result:**
- Candidates are automatically regional (inherited from post)
- No region column on candidate needed

### Pattern 2: Filtering by User Region

**Backend:**
```php
// Get posts for user's region
$posts = Post::where('election_id', $election->id)
    ->where('is_national_wide', 0)
    ->where('state_name', $user->region)  // ← User's region
    ->get();

// Candidates are fetched for these posts
foreach ($posts as $post) {
    $candidates = $post->candidates;
    // These are ONLY candidates for this post
    // Region is implicit from the post
}
```

**Frontend:**
```vue
<!-- Loop through regional posts -->
<div v-for="post in regional_posts" :key="post.post_id">
  <!-- Post region comes from post.state_name -->
  <p>Region: {{ post.state_name }}</p>

  <!-- Candidates are already filtered by backend -->
  <div v-for="candidate in post.candidates">
    {{ candidate.user.name }}
  </div>
</div>
```

### Pattern 3: Validating Regional Integrity

```php
// When displaying votes
public function validateVote($vote)
{
    $voter = User::find($vote->user_id);
    $post = Post::find($vote->post_id);

    // If regional post, verify voter's region matches
    if (!$post->is_national_wide) {
        if ($post->state_name !== $voter->region) {
            throw new \Exception('Voter region mismatch');
        }
    }
}
```

---

## Testing Strategy

### Test Case 1: National Posts Visibility

```php
public function test_all_voters_see_national_posts()
{
    $user1 = User::factory()->create(['region' => 'Bayern']);
    $user2 = User::factory()->create(['region' => 'Hessen']);
    $election = Election::factory()->create();

    $nationalPost = Post::create([
        'election_id' => $election->id,
        'name' => 'President',
        'is_national_wide' => 1
    ]);

    // Both users see same national posts
    $this->actingAs($user1)->get("/vote/create")
        ->assertSee('President');

    $this->actingAs($user2)->get("/vote/create")
        ->assertSee('President');
}
```

### Test Case 2: Regional Posts Isolation

```php
public function test_voters_see_only_their_regions_posts()
{
    $user1 = User::factory()->create(['region' => 'Bayern']);
    $user2 = User::factory()->create(['region' => 'Hessen']);
    $election = Election::factory()->create();

    $bayernPost = Post::create([
        'election_id' => $election->id,
        'name' => 'Bayern State Rep',
        'is_national_wide' => 0,
        'state_name' => 'Bayern'
    ]);

    $hessenPost = Post::create([
        'election_id' => $election->id,
        'name' => 'Hessen State Rep',
        'is_national_wide' => 0,
        'state_name' => 'Hessen'
    ]);

    // Bayern user sees Bayern post only
    $this->actingAs($user1)->get("/vote/create")
        ->assertSee('Bayern State Rep')
        ->assertDontSee('Hessen State Rep');

    // Hessen user sees Hessen post only
    $this->actingAs($user2)->get("/vote/create")
        ->assertSee('Hessen State Rep')
        ->assertDontSee('Bayern State Rep');
}
```

### Test Case 3: Candidate Assignment

```php
public function test_candidates_assigned_to_correct_posts()
{
    $post1 = Post::create(['name' => 'President', 'is_national_wide' => 1]);
    $post2 = Post::create([
        'name' => 'State Rep',
        'is_national_wide' => 0,
        'state_name' => 'Bayern'
    ]);

    $candidate1 = Candidacy::create(['post_id' => $post1->id]);
    $candidate2 = Candidacy::create(['post_id' => $post2->id]);

    // Candidates are accessible via their post
    $this->assertEquals(1, $post1->candidates->count());
    $this->assertEquals(1, $post2->candidates->count());
}
```

---

## Troubleshooting

### Issue 1: Voters See Candidates from Wrong Region

**Symptoms:**
- Bayern voter sees Hessen candidates
- Candidates appear in multiple regions

**Root Cause:**
- Regional filter not applied in query
- Candidates grouped incorrectly

**Solution:**
```php
// ❌ WRONG: Missing region filter
$posts = Post::where('is_national_wide', 0)->get();

// ✅ CORRECT: Include region filter
$posts = Post::where('is_national_wide', 0)
    ->where('state_name', $user->region)
    ->get();
```

### Issue 2: Regional Posts Don't Show

**Symptoms:**
- Regional section not visible
- Even though region exists in database

**Root Cause:**
- Post has wrong `state_name`
- User region doesn't match post region

**Solution:**
```php
// Check post state_name
$post = Post::find(1);
echo $post->state_name;  // Should be "Bayern", not "Bavaria"

// Check user region
$user = User::find(1);
echo $user->region;      // Should match post state_name exactly

// Verify case sensitivity
trim($user->region) === trim($post->state_name);
```

### Issue 3: Candidates Missing from Post

**Symptoms:**
- Post visible but no candidates
- "No candidates available"

**Root Cause:**
- Candidates not created for this post
- post_id mismatch

**Solution:**
```php
// Verify candidates exist for post
$candidates = Candidacy::where('post_id', $post->id)->get();
echo $candidates->count();  // Should be > 0

// Check post_id in candidate
$candidate = Candidacy::first();
echo $candidate->post_id;   // Should match post.id
```

### Issue 4: Cross-Post Selection Interference

**Symptoms:**
- Selecting candidate for President affects Vice President
- Same candidacy_id appears in multiple posts

**Root Cause:**
- State not keyed by post_id
- Simple array instead of object

**Solution:**
```javascript
// ❌ WRONG: Simple array causes interference
data: {
  selected: [1, 2, 3]  // Can't distinguish which post
}

// ✅ CORRECT: Keyed by post_id
data: {
  selectedByPost: {
    1: [1, 2, 3],      // Post 1 selections
    2: [4, 5],         // Post 2 selections
  }
}
```

### Issue 5: Region Column Creeping into Candidates Table

**Symptoms:**
- Data inconsistency (region on both post and candidate)
- Updates missing when region changes

**Prevention:**
```php
// ❌ DON'T create migration
Schema::table('candidacies', function (Blueprint $table) {
    $table->string('region');  // ← WRONG!
});

// ✅ CORRECT: Region stays on Post only
// Post.state_name is single source of truth
```

---

## Best Practices

### 1. Always Filter at Database Level

```php
// ✅ GOOD
$posts = Post::where('is_national_wide', 0)
    ->where('state_name', $user->region)
    ->get();

// ❌ BAD
$posts = Post::where('is_national_wide', 0)->get();
$posts = $posts->filter(fn($p) => $p->state_name === $user->region);
```

### 2. Use Indexes for Performance

```sql
CREATE INDEX posts_election_region
ON posts(election_id, is_national_wide, state_name);
```

### 3. Validate Region Matching

```php
// Before processing vote
if (!$post->is_national_wide && $post->state_name !== $user->region) {
    abort(403, 'Invalid region');
}
```

### 4. Use Descriptive Variable Names

```php
// ✅ GOOD
$nationalPosts = [];
$regionalPosts = [];

// ❌ BAD
$posts = [];
$posts2 = [];
```

### 5. Group Candidates Early

```php
// Group once, use multiple times
$groupedCandidates = $candidates->groupBy('post_id');

// Use in loops
foreach ($posts as $post) {
    $post->candidates = $groupedCandidates->get($post->id);
}
```

---

## Summary

| Aspect | Rule |
|--------|------|
| **Region Storage** | On Post model only (is_national_wide + state_name) |
| **Candidate Region** | No region field (inherited from post) |
| **Filtering** | Database-level queries, not application logic |
| **Visibility** | National posts → all users; Regional posts → user's region only |
| **Performance** | Use indexes on (election_id, is_national_wide, state_name) |
| **Testing** | Test isolation between regions |
| **Single Source of Truth** | Post.state_name is authoritative |

---

## References

- [VoteController.php](../../app/Http/Controllers/VoteController.php) - Real elections
- [DemoVoteController.php](../../app/Http/Controllers/Demo/DemoVoteController.php) - Demo elections
- [CreateVotingPage.vue](../../resources/js/Pages/Vote/DemoVote/CreateVotingPage.vue) - Main component
- [CreateVotingform.vue](../../resources/js/Pages/Vote/DemoVote/CreateVotingform.vue) - Per-post component

---

**Last Updated:** February 2026
**Maintained By:** Development Team
**Version:** 2.0 - Production Ready ✅
