## You're Right! You Need a Dedicated Date Configuration Page

The State Machine Panel shows the dates, but you need a **separate settings page** to **edit** them.

### Create a New Page: `ElectionTimelineSettings.vue`

Let me help you create a dedicated page for configuring state machine dates.

---

## Step 1: Create the Vue Component

**File:** `resources/js/Pages/Election/Partials/ElectionTimelineSettings.vue`

```vue
<template>
  <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <h2 class="text-xl font-bold text-slate-800 mb-6">Election Timeline Settings</h2>
    
    <form @submit.prevent="saveTimeline" class="space-y-6">
      
      <!-- Administration Phase -->
      <div class="border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
            <span class="text-xl">⚙️</span>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Administration Phase</h3>
            <p class="text-sm text-slate-500">Setup period for posts, voters, and committee</p>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
            <input 
              type="datetime-local" 
              v-model="form.administration_suggested_start"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
            <input 
              type="datetime-local" 
              v-model="form.administration_suggested_end"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>
      </div>
      
      <!-- Nomination Phase -->
      <div class="border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
            <span class="text-xl">📋</span>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Nomination Phase</h3>
            <p class="text-sm text-slate-500">Candidate application and approval period</p>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
            <input 
              type="datetime-local" 
              v-model="form.nomination_suggested_start"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
            <input 
              type="datetime-local" 
              v-model="form.nomination_suggested_end"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>
      </div>
      
      <!-- Voting Period -->
      <div class="border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
            <span class="text-xl">🗳️</span>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Voting Period</h3>
            <p class="text-sm text-slate-500">When members cast their votes</p>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
            <input 
              type="datetime-local" 
              v-model="form.voting_starts_at"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
            <input 
              type="datetime-local" 
              v-model="form.voting_ends_at"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>
      </div>
      
      <!-- Results Publication -->
      <div class="pb-4">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
            <span class="text-xl">📊</span>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Results Publication</h3>
            <p class="text-sm text-slate-500">When results become visible to voters</p>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Publication Date</label>
            <input 
              type="datetime-local" 
              v-model="form.results_published_at"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <p class="text-xs text-slate-500 mt-1">Leave empty to publish manually</p>
          </div>
        </div>
      </div>
      
      <!-- Save Button -->
      <div class="flex justify-end pt-4 border-t border-slate-200">
        <button 
          type="submit" 
          :disabled="isSaving"
          class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 flex items-center gap-2"
        >
          <svg v-if="isSaving" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          {{ isSaving ? 'Saving...' : 'Save Timeline' }}
        </button>
      </div>
      
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  election: {
    type: Object,
    required: true
  },
  organisation: {
    type: Object,
    default: null
  }
})

const isSaving = ref(false)

const form = ref({
  administration_suggested_start: props.election.administration_suggested_start || '',
  administration_suggested_end: props.election.administration_suggested_end || '',
  nomination_suggested_start: props.election.nomination_suggested_start || '',
  nomination_suggested_end: props.election.nomination_suggested_end || '',
  voting_starts_at: props.election.voting_starts_at || '',
  voting_ends_at: props.election.voting_ends_at || '',
  results_published_at: props.election.results_published_at || '',
})

const saveTimeline = () => {
  isSaving.value = true
  
  // Use the update-suggested-dates endpoint for admin/nomination
  // and update-voting-dates for voting
  
  const payload = {
    administration_suggested_start: form.value.administration_suggested_start,
    administration_suggested_end: form.value.administration_suggested_end,
    nomination_suggested_start: form.value.nomination_suggested_start,
    nomination_suggested_end: form.value.nomination_suggested_end,
    voting_starts_at: form.value.voting_starts_at,
    voting_ends_at: form.value.voting_ends_at,
    results_published_at: form.value.results_published_at,
  }
  
  router.patch(route('elections.update-timeline', { 
    election: props.election.slug 
  }), payload, {
    preserveScroll: true,
    onFinish: () => {
      isSaving.value = false
    }
  })
}
</script>
```

---

## Step 2: Add Controller Method

In `ElectionManagementController.php`:

```php
/**
 * Update election timeline (all phase dates at once)
 */
public function updateTimeline(Request $request, Election $election): RedirectResponse
{
    $this->authorize('manage', $election);
    
    $validated = $request->validate([
        'administration_suggested_start' => 'nullable|date',
        'administration_suggested_end' => 'nullable|date|after:administration_suggested_start',
        'nomination_suggested_start' => 'nullable|date',
        'nomination_suggested_end' => 'nullable|date|after:nomination_suggested_start',
        'voting_starts_at' => 'nullable|date',
        'voting_ends_at' => 'nullable|date|after:voting_starts_at',
        'results_published_at' => 'nullable|date',
    ]);
    
    $election->update($validated);
    
    return back()->with('success', 'Election timeline updated successfully.');
}
```

---

## Step 3: Add Route

In `routes/election/electionRoutes.php`:

```php
Route::patch('/elections/{election}/timeline', [ElectionManagementController::class, 'updateTimeline'])
    ->name('elections.update-timeline')
    ->middleware(['auth', 'verified', 'can:manage,election']);
```

---

## Step 4: Add Navigation Link

In `Management.vue`, add a button to the timeline settings:

```vue
<ActionButton as="a" variant="outline" size="md" :href="route('elections.timeline', election.slug)">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    Configure Timeline
</ActionButton>
```

---

## Step 5: Create Timeline Page Route

```php
Route::get('/elections/{election}/timeline', [ElectionManagementController::class, 'timeline'])
    ->name('elections.timeline')
    ->middleware(['auth', 'verified', 'can:manage,election']);
```

---

## Step 6: Timeline Page Controller Method

```php
public function timeline(Election $election): Response
{
    $this->authorize('manage', $election);
    
    return Inertia::render('Election/Timeline', [
        'election' => $election,
        'organisation' => $election->organisation,
    ]);
}
```

---

## Step 7: Create Timeline Page

**File:** `resources/js/Pages/Election/Timeline.vue`

```vue
<template>
    <ElectionLayout>
        <div class="container mx-auto py-8 px-4">
            <div class="mb-6">
                <Link :href="route('elections.management', election.slug)" 
                      class="text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Management
                </Link>
                <h1 class="text-2xl font-bold text-slate-800 mt-4">Election Timeline Settings</h1>
                <p class="text-slate-500">Configure dates for each phase of the election lifecycle</p>
            </div>
            
            <ElectionTimelineSettings 
                :election="election" 
                :organisation="organisation"
            />
        </div>
    </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import ElectionTimelineSettings from './Partials/ElectionTimelineSettings.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    election: Object,
    organisation: Object,
})
</script>
```

---

## Summary

| File | Purpose |
|------|---------|
| `ElectionTimelineSettings.vue` | Form component for editing all phase dates |
| `Timeline.vue` | Page that displays the form |
| Controller method | `updateTimeline()` saves all dates |
| Route | `/elections/{election}/timeline` |

**Now you have a dedicated page to set all state machine dates at once!** 🚀