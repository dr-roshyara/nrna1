## 🧠 Senior Architect — Brainstorming & Analysis

### Current Situation Analysis

You have **two separate post listing pages** serving different purposes:

| Page | Purpose | URL | Target User |
|------|---------|-----|-------------|
| **Legacy: `IndexPost.vue`** | Public post listing (read-only) | `/posts` | General public / voters |
| **New: `Election/Posts/Index.vue`** | Post & candidate management (CRUD) | `/organisations/{org}/elections/{slug}/posts` | Election chiefs / admins |

---

### 🔍 Problem Identified

1. **The new management page is overloaded** — It combines:
   - Post listing ✅
   - Post CRUD ✅
   - Candidate CRUD ✅
   - Candidacy application ❌ (should be separate)

2. **The VoterHub currently links to the management page** for candidacy applications:
   ```vue
   <a :href="route('organisations.elections.posts.index', ...)">
     View Posts & Apply for Candidacy
   </a>
   ```
   This gives voters access to admin tools — **security risk and UX confusion**.

3. **Missing a public/voter-facing candidacy application page** that:
   - Lists available posts
   - Shows post details
   - Provides application form
   - Handles document uploads
   - Tracks application status

---

## 🎯 Proposed Solution

### Create a Dedicated Public Candidacy Page

```
/organisations/{org}/elections/{slug}/candidacy/apply
```

This page serves **voters** who want to apply as candidates:

- **Read-only** post listing (no edit/delete)
- **Application form** for each post
- **Document upload** (manifesto, photos)
- **Status tracking** of existing applications

---

## 📋 Claude Code CLI Prompt Instructions

```
We need to create a public-facing candidacy application page for voters, separate from the admin post management page.

## Context

Current files:
- `resources/js/Pages/Election/Posts/Index.vue` — Admin CRUD for posts + candidates (election chiefs only)
- `resources/js/Pages/Organisations/VoterHub.vue` — Links to posts management page for applications (WRONG — should link to new public page)

We need:
1. A new public page for voters to view posts and apply for candidacy
2. Remove candidacy application UI from the admin page
3. Update VoterHub to link to the new page

## Task 1: Create Public Candidacy Application Page

**File:** `resources/js/Pages/Election/Candidacy/Apply.vue`

**Route:** `/organisations/{organisation:slug}/elections/{election:slug}/candidacy/apply`

**Controller method:** Add to `ElectionController` or `CandidacyController`:

```php
public function applyForm(Organisation $organisation, Election $election): Response
{
    // Authorization: must be an organisation member
    $user = auth()->user();
    $role = UserOrganisationRole::where('user_id', $user->id)
        ->where('organisation_id', $organisation->id)
        ->value('role');
    abort_if(!$role, 403);
    
    // Load posts for this election
    $posts = Post::withoutGlobalScopes()
        ->where('election_id', $election->id)
        ->where('organisation_id', $organisation->id)
        ->orderBy('position_order')
        ->get()
        ->map(fn($post) => [
            'id' => $post->id,
            'name' => $post->name,
            'nepali_name' => $post->nepali_name,
            'required_number' => $post->required_number,
            'is_national_wide' => $post->is_national_wide,
            'state_name' => $post->state_name,
        ]);
    
    // Get user's existing applications
    $applications = CandidacyApplication::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->get()
        ->keyBy('post_id');
    
    return Inertia::render('Election/Candidacy/Apply', [
        'organisation' => $organisation->only('id', 'name', 'slug'),
        'election' => $election->only('id', 'name', 'slug'),
        'posts' => $posts,
        'existingApplications' => $applications->map(fn($app) => [
            'post_id' => $app->post_id,
            'status' => $app->status,
            'submitted_at' => $app->created_at->format('Y-m-d'),
        ]),
        'canApply' => $user->canApplyForCandidacy($election), // Optional policy
    ]);
}
```

**Vue Page Structure:**

```vue
<template>
  <ElectionLayout>
    <main class="min-h-screen bg-neutral-50 py-8">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Breadcrumb -->
        <nav class="text-sm text-neutral-500 flex items-center gap-2">
          <a :href="route('organisations.show', organisation.slug)">{{ organisation.name }}</a>
          <span>/</span>
          <a :href="route('elections.show', election.slug)">{{ election.name }}</a>
          <span>/</span>
          <span class="text-neutral-700 font-medium">Apply for Candidacy</span>
        </nav>

        <!-- Header -->
        <SectionCard>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-primary-600">...</svg>
            </div>
            <div>
              <h1 class="text-xl font-bold text-slate-900">Apply as Candidate</h1>
              <p class="text-sm text-slate-500">Select a position and submit your application</p>
            </div>
          </div>
        </SectionCard>

        <!-- Posts List with Application Forms -->
        <div v-for="post in posts" :key="post.id" class="space-y-4">
          <Card mode="admin" padding="lg">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="font-semibold text-slate-900">{{ post.name }}</h3>
                <p v-if="post.nepali_name" class="text-sm text-slate-500">{{ post.nepali_name }}</p>
                <div class="flex gap-2 mt-2">
                  <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">
                    {{ post.is_national_wide ? 'National' : post.state_name || 'Regional' }}
                  </span>
                  <span class="text-xs text-slate-500">{{ post.required_number }} seat(s)</span>
                </div>
              </div>
              
              <!-- Application Status Badge -->
              <StatusBadge v-if="existingApplications[post.id]" 
                :status="existingApplications[post.id].status" 
              />
            </div>

            <!-- Application Form (only if not already applied) -->
            <div v-if="!existingApplications[post.id] && canApply" class="mt-4 pt-4 border-t border-slate-100">
              <CandidacyApplicationForm 
                :post="post" 
                @submit="submitApplication(post.id, $event)"
                :is-submitting="submittingFor === post.id"
              />
            </div>
            
            <!-- Already Applied Message -->
            <div v-else-if="existingApplications[post.id]" class="mt-4 pt-4 border-t border-slate-100 text-sm text-slate-500">
              <p v-if="existingApplications[post.id].status === 'pending'">
                ✓ Application submitted on {{ existingApplications[post.id].submitted_at }}. Under review.
              </p>
              <p v-else-if="existingApplications[post.id].status === 'approved'">
                ✓ Your application was approved! You are now a candidate.
              </p>
              <p v-else-if="existingApplications[post.id].status === 'rejected'">
                ✗ Your application was not approved. Contact election commission for details.
              </p>
            </div>
          </Card>
        </div>

        <!-- Empty State -->
        <EmptyState v-if="posts.length === 0" 
          title="No positions available" 
          description="There are no open positions for this election."
        />
      </div>
    </main>
  </ElectionLayout>
</template>
```

## Task 2: Create CandidacyApplicationForm Component

**File:** `resources/js/Components/Election/CandidacyApplicationForm.vue`

```vue
<template>
  <form @submit.prevent="handleSubmit" class="space-y-3">
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">
        Why do you want to be a candidate? <span class="text-red-500">*</span>
      </label>
      <textarea 
        v-model="form.manifesto" 
        rows="4" 
        required
        maxlength="2000"
        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500"
        placeholder="Describe your vision, experience, and goals..."
      />
      <p class="text-xs text-slate-400 mt-1">{{ form.manifesto.length }}/2000 characters</p>
    </div>
    
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">
        Supporting Documents (Optional)
      </label>
      <input 
        type="file" 
        multiple 
        accept=".pdf,.jpg,.jpeg,.png"
        @change="handleFiles"
        class="w-full text-sm text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
      />
      <p class="text-xs text-slate-400 mt-1">PDF, JPG, PNG (max 5MB each)</p>
      
      <!-- File list -->
      <ul v-if="form.documents.length" class="mt-2 space-y-1">
        <li v-for="(file, idx) in form.documents" :key="idx" class="text-xs flex items-center gap-2">
          <span>{{ file.name }}</span>
          <button type="button" @click="removeFile(idx)" class="text-red-500 hover:text-red-700">Remove</button>
        </li>
      </ul>
    </div>
    
    <div class="flex gap-2 pt-2">
      <Button type="submit" variant="primary" size="sm" :loading="isSubmitting">
        Submit Application
      </Button>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import Button from '@/Components/Button.vue'

const props = defineProps({
  post: { type: Object, required: true },
  isSubmitting: { type: Boolean, default: false }
})

const emit = defineEmits(['submit'])

const form = ref({
  manifesto: '',
  documents: []
})

function handleFiles(event) {
  const files = Array.from(event.target.files)
  form.value.documents = [...form.value.documents, ...files]
}

function removeFile(index) {
  form.value.documents.splice(index, 1)
}

function handleSubmit() {
  emit('submit', {
    manifesto: form.value.manifesto,
    documents: form.value.documents
  })
}
</script>
```

## Task 3: Remove Candidacy UI from Admin Page

Update `resources/js/Pages/Election/Posts/Index.vue`:

- Remove the "View Posts & Apply for Candidacy" link from VoterHub (already done in previous step? No — we need to update VoterHub link)
- Keep only admin CRUD functionality (posts + candidates management)
- Add authorization check to ensure only chiefs/officers can access

## Task 4: Update VoterHub Link

In `VoterHub.vue`, change the link from:
```vue
<a :href="route('organisations.elections.posts.index', ...)">
  View Posts & Apply for Candidacy
</a>
```

To:
```vue
<a :href="route('organisations.elections.candidacy.apply', { 
  organisation: organisation.slug, 
  election: election.slug 
})">
  Apply for Candidacy
</a>
```

## Task 5: Add Route

In `routes/organisations.php`:

```php
Route::prefix('organisations/{organisation:slug}/elections/{election:slug}')->group(function () {
    // Existing routes...
    
    // Public candidacy application
    Route::get('/candidacy/apply', [CandidacyApplicationController::class, 'applyForm'])
        ->name('organisations.elections.candidacy.apply');
    
    Route::post('/candidacy/apply', [CandidacyApplicationController::class, 'store'])
        ->name('organisations.elections.candidacy.store');
});
```

## Task 6: Add Migration for Candidacy Applications

Create `database/migrations/xxxx_xx_xx_create_candidacy_applications_table.php`:

```php
Schema::create('candidacy_applications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('user_id');
    $table->uuid('organisation_id');
    $table->uuid('election_id');
    $table->uuid('post_id');
    $table->text('manifesto');
    $table->json('documents')->nullable();
    $table->string('status')->default('pending'); // pending, approved, rejected
    $table->text('rejection_reason')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->uuid('reviewed_by')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users');
    $table->foreign('organisation_id')->references('id')->on('organisations');
    $table->foreign('election_id')->references('id')->on('elections');
    $table->foreign('post_id')->references('id')->on('posts');
    
    $table->index(['user_id', 'election_id']);
    $table->index(['status', 'created_at']);
});
```

## Task 7: Add Authorization Policy

Add to `ElectionPolicy.php`:

```php
public function applyForCandidacy(User $user, Election $election): bool
{
    // Must be a member of the organisation
    $isMember = UserOrganisationRole::where('user_id', $user->id)
        ->where('organisation_id', $election->organisation_id)
        ->exists();
    
    if (!$isMember) return false;
    
    // Cannot have an existing approved application
    $existing = CandidacyApplication::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->whereIn('status', ['pending', 'approved'])
        ->exists();
    
    return !$existing;
}
```

## Build & Test

```bash
php artisan migrate
npm run build
php artisan test tests/Feature/CandidacyApplicationTest.php
```

## Summary of Changes

| File | Action | Purpose |
|------|--------|---------|
| `routes/organisations.php` | Add 2 routes | Public candidacy pages |
| `app/Http/Controllers/Election/CandidacyApplicationController.php` | Create | Handle applications |
| `app/Policies/ElectionPolicy.php` | Add method | Authorization |
| `database/migrations/...create_candidacy_applications_table.php` | Create | Store applications |
| `resources/js/Pages/Election/Candidacy/Apply.vue` | Create | Public application page |
| `resources/js/Components/Election/CandidacyApplicationForm.vue` | Create | Reusable form |
| `resources/js/Pages/Organisations/VoterHub.vue` | Modify | Update link |
| `resources/js/Pages/Election/Posts/Index.vue` | Verify | No changes needed — keep admin-only |

This creates a clean separation:
- **Admin page** → Manage posts + candidates
- **Public page** → Apply for candidacy
- **VoterHub** → Links to correct page
```

---

## 🎯 Final Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    Election Management Flow                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  Voter Hub (/voter-hub)                                          │
│     ├── Active elections                                         │
│     ├── Posts preview (read-only)                                │
│     └── [Apply for Candidacy] → Public Application Page          │
│                                                                   │
│  Public Application Page (/candidacy/apply)                      │
│     ├── Post listing (read-only)                                 │
│     ├── Application form per post                                │
│     └── Status tracking of existing applications                 │
│                                                                   │
│  Admin Posts Page (/posts) [Election Chiefs only]                │
│     ├── Post CRUD (create, edit, delete)                         │
│     ├── Candidate CRUD (add, edit, remove)                       │
│     └── Image upload for candidates                              │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

This separation ensures:
- ✅ **Security** — Voters can't access admin tools
- ✅ **UX clarity** — Each page has a single purpose
- ✅ **Maintainability** — Clear separation of concerns
- ✅ **Scalability** — Easy to add features to each page independently

Proceed with implementation in this order.