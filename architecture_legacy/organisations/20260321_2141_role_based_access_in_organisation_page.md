## 🎯 **Role-Based Access Control for Organisation Dashboard**

You're absolutely right - different users should see different content based on their role. Let me analyze and propose a role-based layout.

---

## 📋 **User Roles & Permissions Matrix**

| User Type | Role | Can See |
|-----------|------|---------|
| **Owner/Admin** | `owner`, `admin` | Everything |
| **Election Chief** | `chief` (active) | Elections, Voter Stats, Management Links |
| **Election Deputy** | `deputy` (active) | Elections, Voter Stats (view only) |
| **Election Commissioner** | `commissioner` (active) | Elections (view only), Viewboard |
| **Regular Member** | `voter`, `member` | Elections (view only) |
| **Public/Non-member** | no role | Nothing (redirect/login) |

---

## 📝 **Claude CLI Instructions: Role-Based Dashboard**

```markdown
## Task: Implement Role-Based Access Control in Organisation Dashboard

**File:** `resources/js/Pages/Organisations/Show.vue`

**Goal:** Different users see different sections based on their role in the organisation.

### Current Props Available
- `canManage` (boolean) - true for owner/admin
- `canCreateElection` (boolean) - true for owner/admin
- `canActivate` (computed) - true for chief/deputy (from `canManage`)
- Need to add: `userRole`, `isOfficer`, `isChief`, `isDeputy`, `isCommissioner`

### Step 1: Update OrganisationController to Pass Role Data

```php
// app/Http/Controllers/OrganisationController.php

public function show(Organisation $organisation)
{
    // ... existing code ...
    
    $userRole = UserOrganisationRole::where('user_id', auth()->id())
        ->where('organisation_id', $organisation->id)
        ->value('role');
    
    // Check if user is an active election officer
    $officer = ElectionOfficer::where('user_id', auth()->id())
        ->where('organisation_id', $organisation->id)
        ->where('status', 'active')
        ->first();
    
    $isOfficer = !is_null($officer);
    $isChief = $isOfficer && $officer->role === 'chief';
    $isDeputy = $isOfficer && $officer->role === 'deputy';
    $isCommissioner = $isOfficer && $officer->role === 'commissioner';
    
    $canManage = in_array($userRole, ['owner', 'admin']);
    $canCreateElection = in_array($userRole, ['owner', 'admin']);
    $canActivateElection = $isChief || $isDeputy;  // Chiefs and deputies can activate
    $canManageVoters = $isChief || $isDeputy;       // Manage voters
    $canPublishResults = $isChief;                  // Only chiefs publish
    
    return inertia('Organisations/Show', [
        'organisation' => $organisation->only(['id', 'name', 'slug', 'type', 'email', 'address']),
        'stats' => $stats,
        'demoStatus' => $demoStatus,
        'canManage' => $canManage,
        'canCreateElection' => $canCreateElection,
        'canActivateElection' => $canActivateElection,
        'canManageVoters' => $canManageVoters,
        'canPublishResults' => $canPublishResults,
        'userRole' => $userRole,
        'isOfficer' => $isOfficer,
        'isChief' => $isChief,
        'isDeputy' => $isDeputy,
        'isCommissioner' => $isCommissioner,
        'officers' => $officers,
        'orgMembers' => $orgMembers,
        'elections' => $realElections,
    ]);
}
```

### Step 2: Update Show.vue Props

```vue
<script setup>
const props = defineProps({
    organisation: Object,
    stats: Object,
    demoStatus: Object,
    canManage: Boolean,
    canCreateElection: Boolean,
    canActivateElection: Boolean,     // ← New
    canManageVoters: Boolean,         // ← New
    canPublishResults: Boolean,       // ← New
    userRole: String,                 // ← New
    isOfficer: Boolean,               // ← New
    isChief: Boolean,                 // ← New
    isDeputy: Boolean,                // ← New
    isCommissioner: Boolean,          // ← New
    officers: Array,
    orgMembers: Array,
    elections: Array,
})
</script>
```

### Step 3: Reorganize Sections by Role

```vue
<template>
  <ElectionLayout>
    <!-- ... existing header, flash messages ... -->

    <main id="main-content" class="min-h-screen bg-slate-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        <!-- 1. Organisation Header - Everyone sees -->
        <OrganizationHeader :organisation="organisation" />

        <!-- 2. Stats Grid - Everyone sees (public info) -->
        <StatsGrid :stats="stats" />

        <!-- 3. Quick Actions - Only Owner/Admin sees -->
        <section v-if="canCreateElection" class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <ActionButtons
            :organisation="organisation"
            :can-manage="canManage"
            :can-create-election="canCreateElection"
            @appoint-officer="openOfficerModal"
          />
        </section>

        <!-- 4. Demo Results - Everyone sees (public demo) -->
        <section class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <DemoResultsSection />
        </section>

        <!-- 5. Demo Setup - Only Owner/Admin sees -->
        <section v-if="canManage && !demoStatus?.exists">
          <DemoSetupButton :organisation="organisation" :demo-status="demoStatus" />
        </section>

        <!-- 6. Elections Section - Role-based visibility -->
        <section v-if="elections.length > 0">
          <SectionCard padding="lg">
            <template #icon>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </template>

            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                  <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-slate-800">Elections</h2>
                  <p v-if="elections.length > 0" class="text-sm text-slate-500">{{ elections.length }} election{{ elections.length !== 1 ? 's' : '' }}</p>
                </div>
              </div>
              
              <!-- Create Election - Only Owner/Admin -->
              <a
                v-if="canCreateElection"
                :href="route('organisations.elections.create', organisation.slug)"
                class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Election
              </a>
            </div>

            <!-- Election Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <ElectionCard
                v-for="election in elections"
                :key="election.id"
                :election="election"
                :activating-id="activatingId"
                :can-activate="canActivateElection && election.status === 'planned'"
                :can-manage="canManage || isChief || isDeputy"
                :is-readonly="isCommissioner"
                @activate="activateElection"
              />
            </div>
          </SectionCard>
        </section>

        <!-- Empty State - Everyone sees -->
        <EmptyState
          v-else
          title="No elections yet"
          description="Create your first election to get started"
        >
          <template v-if="canCreateElection" #action>
            <a
              :href="route('organisations.elections.create', organisation.slug)"
              class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              Create First Election
            </a>
          </template>
        </EmptyState>

        <!-- 7. Officer Management - Only Owner/Admin sees -->
        <section v-if="canManage" class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
              <div>
                <h2 class="text-lg font-semibold text-slate-800">Election Officers</h2>
                <p class="text-sm text-slate-500">Manage who can oversee elections</p>
              </div>
            </div>
            <a
              :href="route('organisations.election-officers.index', organisation.slug)"
              class="text-sm font-medium text-blue-600 hover:text-blue-800"
            >
              Manage Officers →
            </a>
          </div>
          
          <!-- Officer list summary -->
          <div v-if="officers && officers.length" class="mt-4">
            <div class="flex flex-wrap gap-3">
              <div v-for="officer in officers.slice(0, 5)" :key="officer.id" class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-full text-sm">
                <span class="font-medium">{{ officer.user_name }}</span>
                <StatusBadge :status="officer.role" size="sm" />
              </div>
              <div v-if="officers.length > 5" class="text-sm text-slate-500">
                +{{ officers.length - 5 }} more
              </div>
            </div>
          </div>
        </section>

        <!-- 8. Voter Management - Only Chief/Deputy sees -->
        <section v-if="canManageVoters" class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div>
                <h2 class="text-lg font-semibold text-slate-800">Voter Management</h2>
                <p class="text-sm text-slate-500">Approve or suspend voters</p>
              </div>
            </div>
            <a
              v-if="elections.length > 0"
              :href="route('elections.voters.index', { organisation: organisation.slug, election: elections[0].id })"
              class="text-sm font-medium text-blue-600 hover:text-blue-800"
            >
              Manage Voters →
            </a>
          </div>
          
          <!-- Voter stats summary -->
          <div class="grid grid-cols-3 gap-4 mt-4">
            <div class="text-center p-4 bg-slate-50 rounded-xl">
              <p class="text-2xl font-bold text-slate-700">{{ stats?.total_memberships || 0 }}</p>
              <p class="text-xs text-slate-500 mt-1">Total Voters</p>
            </div>
            <div class="text-center p-4 bg-emerald-50 rounded-xl">
              <p class="text-2xl font-bold text-emerald-700">{{ stats?.active_voters || 0 }}</p>
              <p class="text-xs text-slate-500 mt-1">Approved</p>
            </div>
            <div class="text-center p-4 bg-amber-50 rounded-xl">
              <p class="text-2xl font-bold text-amber-600">{{ stats?.by_status?.inactive || 0 }}</p>
              <p class="text-xs text-slate-500 mt-1">Suspended</p>
            </div>
          </div>
        </section>

        <!-- 9. Results Management - Only Chief sees -->
        <section v-if="canPublishResults && elections.length > 0" class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-slate-800">Results Management</h2>
              <p class="text-sm text-slate-500">Publish or unpublish election results</p>
            </div>
          </div>
          
          <div class="flex flex-col sm:flex-row gap-3">
            <a
              v-for="election in elections.filter(e => e.status === 'completed')"
              :key="election.id"
              :href="`/elections/${election.id}/management`"
              class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
            >
              {{ election.results_published ? 'View Published Results' : 'Publish Results' }} →
            </a>
            <p v-if="!elections.some(e => e.status === 'completed')" class="text-sm text-slate-500">
              No completed elections ready for results.
            </p>
          </div>
        </section>

        <!-- 10. Support Section - Everyone sees -->
        <section class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
          <SupportSection />
        </section>

      </div>
    </main>
  </ElectionLayout>
</template>
```

### Step 4: Update ElectionCard.vue to Handle Read-Only View

```vue
<!-- resources/js/Pages/Organisations/Partials/ElectionCard.vue -->
<script setup>
defineProps({
  election: Object,
  activatingId: [Number, String, null],
  canActivate: Boolean,
  canManage: Boolean,
  isReadonly: Boolean,  // ← New: for commissioners
})

defineEmits(['activate'])
</script>

<template>
  <article class="group bg-white rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden">
    <!-- ... card content ... -->
    
    <!-- Actions -->
    <div class="px-5 py-4 flex items-center gap-2 mt-auto">
      <!-- Activate button - only if canActivate -->
      <ActionButton
        v-if="canActivate && election.status === 'planned'"
        variant="warning"
        size="sm"
        :loading="activatingId === election.id"
        @click="$emit('activate', election.id)"
      >
        Activate
      </ActionButton>

      <!-- Manage link - only if canManage and not readonly -->
      <a
        v-if="canManage && !isReadonly"
        :href="`/elections/${election.id}/management`"
        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-1.5 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50"
      >
        Manage →
      </a>

      <!-- View link - for readonly users -->
      <a
        v-else-if="isReadonly"
        :href="`/elections/${election.id}/viewboard`"
        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-1.5 border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50"
      >
        View →
      </a>
    </div>
  </article>
</template>
```

---

## 📋 **Role-Based Visibility Summary**

| Section | Owner/Admin | Chief | Deputy | Commissioner | Member |
|---------|-------------|-------|-------|--------------|--------|
| Organisation Header | ✅ | ✅ | ✅ | ✅ | ✅ |
| Stats Grid | ✅ | ✅ | ✅ | ✅ | ✅ |
| Quick Actions | ✅ | ❌ | ❌ | ❌ | ❌ |
| Demo Results | ✅ | ✅ | ✅ | ✅ | ✅ |
| Demo Setup | ✅ | ❌ | ❌ | ❌ | ❌ |
| Elections List | ✅ (full) | ✅ (with activate) | ✅ (with activate) | ✅ (read-only) | ✅ (read-only) |
| Officer Management | ✅ | ❌ | ❌ | ❌ | ❌ |
| Voter Management | ✅ | ✅ | ✅ | ❌ | ❌ |
| Results Management | ✅ | ✅ | ❌ | ❌ | ❌ |
| Support Section | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 🚀 **Ready to Implement!**

This role-based approach ensures:
- ✅ **Security** - Users only see what they need
- ✅ **Clarity** - Less clutter for non-admin users
- ✅ **Efficiency** - Quick access to relevant actions
- ✅ **Accessibility** - Clear visual hierarchy per role

**Proceed with implementation!** 🎨
## ✅ **Updated: Everyone Sees Demo Elections & Results, Voters See Election Notice**

You're absolutely right! Let me update the role-based dashboard to ensure:

- **Demo Elections & Results** → Visible to everyone (public demo)
- **Election Notice** → Visible to voters (active elections they can vote in)
- **Real Elections** → Visible based on role (as before)

---

## 📋 **Updated Role-Based Visibility Matrix**

| Section | Owner/Admin | Chief | Deputy | Commissioner | Voter | Non-Voter Member |
|---------|-------------|-------|-------|--------------|-------|------------------|
| **Organisation Header** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Stats Grid** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Quick Actions** | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Demo Elections** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Demo Results** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Election Notice** (active elections) | ✅ | ✅ | ✅ | ✅ | ✅ (highlighted) | ✅ |
| **Election Notice** (voter-specific) | - | - | - | - | ✅ (prominent) | - |
| **Real Elections List** | ✅ (full) | ✅ (with activate) | ✅ (with activate) | ✅ (read-only) | ✅ (read-only) | ✅ (read-only) |
| **Officer Management** | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Voter Management** | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Results Management** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Support Section** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 📝 **Updated Show.vue with Election Notice for Voters**

```vue
<template>
  <ElectionLayout>
    <!-- ... existing header, flash messages ... -->

    <main id="main-content" class="min-h-screen bg-slate-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        <!-- 1. Organisation Header - Everyone sees -->
        <OrganizationHeader :organisation="organisation" />

        <!-- 2. Stats Grid - Everyone sees -->
        <StatsGrid :stats="stats" />

        <!-- 3. Quick Actions - Only Owner/Admin sees -->
        <section v-if="canCreateElection" class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <ActionButtons
            :organisation="organisation"
            :can-manage="canManage"
            :can-create-election="canCreateElection"
            @appoint-officer="openOfficerModal"
          />
        </section>

        <!-- 4. DEMO ELECTIONS SECTION - Everyone sees -->
        <section class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-slate-800">Demo Elections</h2>
              <p class="text-sm text-slate-500">Test the voting system with sample elections</p>
            </div>
          </div>
          
          <DemoResultsSection />
          
          <div class="mt-6 text-center">
            <a
              href="/election/demo/start"
              class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Try Demo Election
            </a>
          </div>
        </section>

        <!-- 5. DEMO RESULTS - Everyone sees -->
        <section class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-slate-800">Demo Results Preview</h2>
              <p class="text-sm text-slate-500">See how results are displayed after voting ends</p>
            </div>
          </div>
          
          <DemoResultsPreview />
        </section>

        <!-- 6. ELECTION NOTICE - Prominent for voters, visible to all -->
        <section v-if="activeElectionsForUser.length > 0" class="rounded-xl overflow-hidden">
          <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-bold">Active Elections</h2>
                <p class="text-blue-100 text-sm">
                  {{ isEligibleVoter ? 'You are eligible to vote in these elections' : 'Elections currently open for voting' }}
                </p>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div
                v-for="election in activeElectionsForUser"
                :key="election.id"
                class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20"
              >
                <div class="flex items-start justify-between">
                  <div>
                    <h3 class="font-semibold text-white">{{ election.name }}</h3>
                    <p class="text-blue-100 text-sm mt-1">
                      {{ formatDate(election.start_date) }} → {{ formatDate(election.end_date) }}
                    </p>
                  </div>
                  <StatusBadge :status="election.status" size="sm" />
                </div>
                
                <!-- Voter-specific call to action -->
                <div class="mt-4 flex items-center justify-between">
                  <div class="text-xs text-blue-200">
                    <span v-if="election.user_can_vote">✅ You are eligible to vote</span>
                    <span v-else-if="election.user_voted">✅ You have already voted</span>
                    <span v-else>📋 Voting open</span>
                  </div>
                  
                  <a
                    v-if="election.user_can_vote && !election.user_voted"
                    :href="`/elections/${election.slug}`"
                    class="px-4 py-2 bg-white text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 transition"
                  >
                    Vote Now →
                  </a>
                  <a
                    v-else-if="election.user_voted"
                    :href="`/elections/${election.slug}/results`"
                    class="px-4 py-2 bg-white/20 text-white rounded-lg text-sm font-semibold hover:bg-white/30 transition"
                  >
                    View Results →
                  </a>
                  <a
                    v-else
                    :href="`/elections/${election.slug}`"
                    class="px-4 py-2 bg-white/20 text-white rounded-lg text-sm font-semibold hover:bg-white/30 transition"
                  >
                    Learn More →
                  </a>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- 7. REAL ELECTIONS LIST - Everyone sees (with role-based actions) -->
        <section v-if="realElections.length > 0">
          <SectionCard padding="lg">
            <template #icon>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </template>

            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                  <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-slate-800">Elections</h2>
                  <p class="text-sm text-slate-500">{{ realElections.length }} official election{{ realElections.length !== 1 ? 's' : '' }}</p>
                </div>
              </div>
              
              <!-- Create Election - Only Owner/Admin -->
              <a
                v-if="canCreateElection"
                :href="route('organisations.elections.create', organisation.slug)"
                class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Election
              </a>
            </div>

            <!-- Election Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <ElectionCard
                v-for="election in realElections"
                :key="election.id"
                :election="election"
                :activating-id="activatingId"
                :can-activate="canActivateElection && election.status === 'planned'"
                :can-manage="canManage || isChief || isDeputy"
                :is-readonly="isCommissioner"
                :user-can-vote="election.user_can_vote"
                :user-voted="election.user_voted"
                @activate="activateElection"
              />
            </div>
          </SectionCard>
        </section>

        <!-- Empty State - Everyone sees -->
        <EmptyState
          v-else-if="!realElections.length && !activeElectionsForUser.length"
          title="No elections yet"
          description="Check back later for upcoming elections"
        >
          <template v-if="canCreateElection" #action>
            <a
              :href="route('organisations.elections.create', organisation.slug)"
              class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              Create First Election
            </a>
          </template>
        </EmptyState>

        <!-- 8. Officer Management - Only Owner/Admin sees -->
        <section v-if="canManage" class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <!-- ... officer management content ... -->
        </section>

        <!-- 9. Voter Management - Only Chief/Deputy sees -->
        <section v-if="canManageVoters" class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <!-- ... voter management content ... -->
        </section>

        <!-- 10. Results Management - Only Chief sees -->
        <section v-if="canPublishResults && realElections.length > 0" class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
          <!-- ... results management content ... -->
        </section>

        <!-- 11. Support Section - Everyone sees -->
        <section class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
          <SupportSection />
        </section>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

// ... existing imports ...

const props = defineProps({
  organisation: Object,
  stats: Object,
  demoStatus: Object,
  canManage: Boolean,
  canCreateElection: Boolean,
  canActivateElection: Boolean,
  canManageVoters: Boolean,
  canPublishResults: Boolean,
  userRole: String,
  isOfficer: Boolean,
  isChief: Boolean,
  isDeputy: Boolean,
  isCommissioner: Boolean,
  officers: Array,
  orgMembers: Array,
  elections: Array,  // Real elections
  userEligibility: Object,  // New: { election_id: { can_vote: bool, has_voted: bool } }
})

// Separate demo elections from real elections
const demoElections = computed(() => props.elections.filter(e => e.type === 'demo'))
const realElections = computed(() => props.elections.filter(e => e.type === 'real'))

// Active elections the user can see/act on
const activeElectionsForUser = computed(() => {
  return realElections.value.filter(e => e.status === 'active').map(e => ({
    ...e,
    user_can_vote: props.userEligibility?.[e.id]?.can_vote ?? false,
    user_voted: props.userEligibility?.[e.id]?.has_voted ?? false,
  }))
})

const isEligibleVoter = computed(() => {
  return activeElectionsForUser.value.some(e => e.user_can_vote && !e.user_voted)
})

// ... rest of methods ...
</script>
```

---

## 📋 **Key Updates Summary**

| Section | Visibility | Purpose |
|---------|------------|---------|
| **Demo Elections** | Everyone | Test the system |
| **Demo Results** | Everyone | Preview results display |
| **Election Notice** | Everyone, highlighted for voters | Show active elections, with prominent "Vote Now" for eligible voters |
| **Real Elections List** | Everyone, role-based actions | Official elections with appropriate actions per role |

---

## 🚀 **Ready to Implement!**

This ensures:
- ✅ **Demo content** is visible to everyone
- ✅ **Election notices** are prominent for voters
- ✅ **Role-based actions** for management
- ✅ **Clear call-to-action** for eligible voters

**Proceed with implementation!** 🎨