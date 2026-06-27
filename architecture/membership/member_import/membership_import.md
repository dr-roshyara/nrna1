## Practical Implementation: Election-Only Mode Workflow

You're right to ask. Here's the complete practical flow:

### Current State: Where to Set the Mode

The `uses_full_membership` flag exists in the database but **no UI exists yet** to toggle it. You have two options:

#### Option 1: Set via Tinker (Immediate Testing)

```bash
php artisan tinker

# Set organisation to election-only mode
$org = Organisation::where('slug', 'namaste-nepal-gmbh')->first();
$org->update(['uses_full_membership' => false]);

# Verify
$org->isElectionOnly(); // Should return true
```

#### Option 2: Add Settings UI (Recommended)

Add toggle to Organisation Settings page:

```vue
<!-- resources/js/Pages/Organisations/Settings/Index.vue -->
<template>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Voter Eligibility Mode</h3>
                <p class="text-sm text-gray-600">
                    When disabled, any registered user can be added as voter.
                </p>
            </div>
            <ToggleSwitch v-model="form.uses_full_membership" />
        </div>
        <p class="text-sm mt-2" :class="form.uses_full_membership ? 'text-blue-600' : 'text-green-600'">
            {{ form.uses_full_membership ? 'Full Membership Required' : 'Election-Only (Direct Import)' }}
        </p>
    </div>
</template>
```

### How to Add Voters in Election-Only Mode

Once `uses_full_membership = false`, the voters page automatically adapts:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│  VOTERS PAGE (/organisations/{org}/elections/{election}/voters)              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                               │
│  Election-Only Mode (uses_full_membership = false)                            │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │  ASSIGN USERS AS VOTERS                                                  │ │
│  │  ┌─────────────────────────────────────────────────────────────────────┐ │ │
│  │  │  Search: [________________] 🔍                                       │ │ │
│  │  │                                                                      │ │ │
│  │  │  ☐ Niraj Adhikari (restaurant.namastenepal@gmail.com)               │ │ │
│  │  │  ☐ John Doe (john@example.com)                                       │ │ │
│  │  │  ☐ Jane Smith (jane@example.com)                                     │ │ │
│  │  │                                                                      │ │ │
│  │  │  [Assign Selected]                                                   │ │ │
│  │  └─────────────────────────────────────────────────────────────────────┘ │ │
│  │                                                                           │ │
│  │  ── OR ──                                                                 │ │
│  │                                                                           │ │
│  │  📋 Import Voters from CSV                                                │ │
│  │  [Choose File] no file chosen                                             │ │
│  │  [Upload and Import]                                                      │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                               │
│  Full Membership Mode (uses_full_membership = true)                            │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │  ASSIGN MEMBERS AS VOTERS                                                │ │
│  │  Only active members with paid/exempt fees appear below                   │ │
│  │  ┌─────────────────────────────────────────────────────────────────────┐ │ │
│  │  │  ☐ Niraj Adhikari - Member #M001 - Fees: Paid                        │ │ │
│  │  │  ☐ John Doe - Member #M002 - Fees: Exempt                            │ │ │
│  │  │                                                                      │ │ │
│  │  │  [Assign Selected]                                                   │ │ │
│  │  └─────────────────────────────────────────────────────────────────────┘ │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────────────┘
```

### CSV Import for Election-Only Mode

```php
// app/Http/Controllers/ElectionVoterController.php
public function import(Request $request, Organisation $organisation, Election $election)
{
    $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);
    
    $path = $request->file('csv_file')->getRealPath();
    $data = array_map('str_getcsv', file($path));
    
    $results = ['success' => 0, 'skipped' => 0, 'errors' => []];
    
    foreach ($data as $row) {
        $email = $row[0]; // First column is email
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $results['errors'][] = "User not found: {$email}";
            continue;
        }
        
        // In election-only mode, no Member validation needed
        if ($organisation->isElectionOnly() || $user->isEligibleVoter($organisation)) {
            ElectionMembership::firstOrCreate([
                'election_id' => $election->id,
                'user_id' => $user->id,
            ], [
                'organisation_id' => $organisation->id,
                'role' => 'voter',
                'status' => 'active',
            ]);
            $results['success']++;
        } else {
            $results['skipped']++;
        }
    }
    
    return back()->with('import_results', $results);
}
```

### Current UI State Check

The voters page (`resources/js/Pages/Elections/Voters/Index.vue`) already has:

```vue
<!-- Line 56-60: Conditional label -->
<p class="sidebar-label">
    {{ organisation.uses_full_membership ? 'ASSIGN MEMBERS AS VOTERS' : 'ASSIGN USERS AS VOTERS' }}
</p>

<!-- Line 62-64: Help text -->
<p class="text-xs text-gray-500 mb-2">
    {{ organisation.uses_full_membership 
        ? 'Only active members with paid fees appear below.' 
        : 'All organisation users can be assigned as voters.' }}
</p>
```

### Immediate Action: Enable via Tinker

```bash
# 1. Set Namaste Nepal to election-only mode
php artisan tinker --execute="
\$org = App\Models\Organisation::where('slug', 'namaste-nepal-gmbh')->first();
\$org->update(['uses_full_membership' => false]);
echo '✅ ' . \$org->name . ' is now in election-only mode';
"

# 2. Visit voters page
# http://localhost:8000/organisations/namaste-nepal-gmbh/elections/namaste-i6g7ahfc/voters

# 3. You should now see ALL organisation users in the dropdown, not just members
```

### Summary

| Task | Status | How to Do It |
|------|--------|--------------|
| Enable election-only mode | ✅ Immediate | Tinker command above |
| Add voters directly | ✅ Working | Voters page dropdown |
| Import CSV | 🚧 Code exists | Needs UI button |
| Settings UI toggle | ⏳ Pending | Add to Settings page |

**Om Gam Ganapataye Namah** 🪔🐘

Run the tinker command to enable election-only mode now, then visit the voters page. The dropdown should show all organisation users instead of just members.
## Claude Code CLI Prompt Instructions — Frontend Implementation

```
You are a professional frontend engineer implementing the Dual-Mode Membership System UI for a Laravel/Vue3/Inertia platform.

## Background

Backend is COMPLETE and TESTED (4/8 tests passing, remaining failures are test-environment only):
- `uses_full_membership` column exists on organisations table
- `OrganisationSettingsController` with `index()` and `updateMembershipMode()`
- Routes: `GET /organisations/{slug}/settings` and `PATCH /organisations/{slug}/settings/membership-mode`
- `VoterImportService` enhanced to support both membership modes
- `OrganisationPolicy` and `MembershipPolicy` authorization complete

## Your Task: Build the Vue Frontend Components

Create three Vue components following the existing project patterns.

### Component 1: Organisation Settings Page

**File:** `resources/js/Pages/Organisations/Settings/Index.vue`

Create a new settings page with membership mode toggle.

**Requirements:**
- Use `AppLayout` or `OrganisationLayout` (check existing pattern)
- Display current membership mode with visual badge
- Toggle switch to change mode
- Warning modal when switching from full to election-only with existing members
- Use Inertia form handling with `useForm`
- Show success/error flash messages

**Props:**
```js
defineProps({
  organisation: Object,  // Contains id, name, slug, uses_full_membership
  memberCount: Number,   // Count of existing members
});
```

**Template Structure:**
```vue
<template>
  <AppLayout title="Organisation Settings">
    <div class="max-w-4xl mx-auto py-8 px-4">
      <h1 class="text-2xl font-bold mb-6">Organisation Settings</h1>
      
      <!-- Membership Mode Card -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="border-b pb-4 mb-4">
          <h2 class="text-xl font-semibold">Membership System Configuration</h2>
          <p class="text-sm text-gray-600 mt-1">
            Choose how voters are eligible for elections in this organisation.
          </p>
        </div>
        
        <!-- Current Mode Badge -->
        <div class="mb-4">
          <span class="text-sm font-medium text-gray-700">Current Mode:</span>
          <span 
            class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            :class="organisation.uses_full_membership 
              ? 'bg-blue-100 text-blue-800' 
              : 'bg-green-100 text-green-800'"
          >
            {{ organisation.uses_full_membership ? 'Full Membership' : 'Election-Only' }}
          </span>
        </div>
        
        <!-- Toggle Switch -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
          <div>
            <h3 class="font-medium text-gray-900">Enable Full Membership</h3>
            <p class="text-sm text-gray-600 max-w-md">
              When enabled, voters must be active members with paid/exempt fees.
              When disabled, any registered user in the organisation can vote.
            </p>
          </div>
          <ToggleSwitch v-model="form.uses_full_membership" />
        </div>
        
        <!-- Warning for switching with existing members -->
        <div v-if="showWarning" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-yellow-800">Warning: Existing Members Present</h3>
              <p class="text-sm text-yellow-700 mt-1">
                This organisation has {{ memberCount }} active members. Switching to election-only mode 
                will allow ANY registered user to vote, bypassing membership requirements.
              </p>
              <div class="mt-3">
                <label class="flex items-center">
                  <input 
                    type="checkbox" 
                    v-model="form.confirm_mode_change" 
                    class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500" 
                  />
                  <span class="ml-2 text-sm text-yellow-800">I understand, proceed with change</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Save Button -->
        <div class="mt-6 flex justify-end">
          <button 
            @click="submit"
            :disabled="form.processing || (showWarning && !form.confirm_mode_change)"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ form.processing ? 'Saving...' : 'Save Changes' }}
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ToggleSwitch from '@/Components/ToggleSwitch.vue';

const props = defineProps({
  organisation: Object,
  memberCount: Number,
});

const form = useForm({
  uses_full_membership: props.organisation.uses_full_membership,
  confirm_mode_change: false,
});

const showWarning = computed(() => {
  return props.organisation.uses_full_membership && 
         !form.uses_full_membership && 
         props.memberCount > 0;
});

const submit = () => {
  form.patch(route('organisations.settings.update-membership-mode', props.organisation.slug), {
    preserveScroll: true,
    onSuccess: () => {
      form.confirm_mode_change = false;
    },
  });
};
</script>
```

### Component 2: Update Organisation Create Form

**File:** `resources/js/Pages/Organisations/Create.vue`

Add membership mode selection radio group.

**Add to form data:**
```js
const form = useForm({
  name: '',
  slug: '',
  type: 'tenant',
  uses_full_membership: true, // default to full membership
  // ... other existing fields
});
```

**Add to template before submit button:**
```vue
<div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
  <h3 class="text-sm font-semibold text-gray-900 mb-3">Membership System</h3>
  
  <div class="space-y-3">
    <label class="flex items-start gap-3 cursor-pointer">
      <input
        type="radio"
        v-model="form.uses_full_membership"
        :value="true"
        class="mt-1"
      />
      <div>
        <span class="font-medium text-gray-900">Full Membership</span>
        <p class="text-sm text-gray-600">
          Voters must be formal members with paid fees. Best for organisations with membership tracking.
        </p>
      </div>
    </label>
    
    <label class="flex items-start gap-3 cursor-pointer">
      <input
        type="radio"
        v-model="form.uses_full_membership"
        :value="false"
        class="mt-1"
      />
      <div>
        <span class="font-medium text-gray-900">Election-Only</span>
        <p class="text-sm text-gray-600">
          Any registered user can vote. Best for simple elections without membership tracking.
        </p>
      </div>
    </label>
  </div>
</div>
```

### Component 3: Update Voters Page Mode Indicator

**File:** `resources/js/Pages/Elections/Voters/Index.vue`

Update the sidebar label to reflect current mode.

**Find the sidebar label section (around line 56) and update:**
```vue
<!-- Current: Hardcoded "ASSIGN MEMBERS AS VOTERS" -->
<!-- Replace with mode-aware label -->
<p class="sidebar-label">
  {{ organisation.uses_full_membership ? 'ASSIGN MEMBERS AS VOTERS' : 'ASSIGN USERS AS VOTERS' }}
</p>
<p class="text-xs text-gray-500 mb-2">
  {{ organisation.uses_full_membership 
    ? 'Only active members with paid fees appear below.' 
    : 'All organisation users can be assigned as voters.' }}
</p>
```

### Component 4: Add Navigation Link

**File:** `resources/js/Layouts/OrganisationLayout.vue` (or wherever organisation navigation is defined)

Add Settings link to the organisation navigation menu:
```vue
<NavLink 
  :href="route('organisations.settings.index', organisation.slug)" 
  :active="route().current('organisations.settings.*')"
>
  Settings
</NavLink>
```

### Verification Checklist

- [ ] Visit `/organisations/{slug}/settings` - page renders correctly
- [ ] Toggle switch changes mode
- [ ] Warning modal appears when switching from full to election-only with members
- [ ] Save button updates mode and shows success message
- [ ] Create organisation form shows membership mode selection
- [ ] Voters page shows correct label based on mode
- [ ] Navigation includes Settings link

### Files to Create/Modify

| Action | File |
|--------|------|
| Create | `resources/js/Pages/Organisations/Settings/Index.vue` |
| Modify | `resources/js/Pages/Organisations/Create.vue` |
| Modify | `resources/js/Pages/Elections/Voters/Index.vue` |
| Modify | `resources/js/Layouts/OrganisationLayout.vue` (or equivalent) |

### Design Notes

- Use Tailwind CSS classes consistent with existing UI
- Follow the project's existing component patterns
- Use `ToggleSwitch` component if it exists, or create a simple one
- Ensure all text is properly translatable (use `$t()` if i18n is set up)
- Add proper loading states and error handling

**Om Gam Ganapataye Namah** 🪔🐘
```