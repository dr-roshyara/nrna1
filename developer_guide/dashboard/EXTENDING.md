# Extending the Dashboard System

## Adding a New Dashboard/Role

This guide walks through adding a completely new dashboard for a new role type.

### Example: Adding "Analyst" Role

**Goal:** Create a new dashboard for data analysts to view election results and statistics

---

## Step 1: Design the Role

### Questions to Answer

```
1. What will this role do?
   - View election statistics
   - Export results
   - Generate reports

2. How does user get this role?
   - organisation-level assignment? (admin assigns)
   - Election-specific? (admin assigns per election)
   - System-wide? (super-admin only)

3. What data can they see?
   - Only their organizations?
   - Multiple organizations?
   - All elections?

4. What actions can they perform?
   - View only?
   - Export data?
   - Generate reports?
   - Modify anything? NO!
```

**For "Analyst" role:**
- ✅ View election results
- ✅ Generate statistics
- ✅ Export reports
- ✅ organisation-level assignment
- ❌ Modify elections
- ❌ View individual votes

---

## Step 2: Create the Database Structure

### Option A: organisation-Level Role

If analysts are assigned per organisation:

```sql
-- No database changes needed!
-- Just use existing user_organization_roles table

INSERT INTO user_organization_roles (user_id, organisation_id, role, created_at, updated_at)
VALUES (5, 1, 'analyst', NOW(), NOW());
```

Then the query pattern is:

```php
// In User model
public function getAnalystOrganizations()
{
    return DB::table('user_organization_roles')
        ->where('user_id', $this->id)
        ->where('role', 'analyst')
        ->pluck('organisation_id');
}
```

### Option B: Election-Specific Role

If analysts are assigned per election:

```sql
-- Add to election_commission_members (or create new table)
INSERT INTO election_commission_members (user_id, election_id, role, created_at, updated_at)
VALUES (5, 1, 'analyst', NOW(), NOW());
```

Then the query pattern is:

```php
// In User model
public function getAnalystElections()
{
    return DB::table('election_commission_members')
        ->where('user_id', $this->id)
        ->where('role', 'analyst')
        ->pluck('election_id');
}
```

**For this example, we'll use Option A (organisation-level).**

---

## Step 3: Update User Model

**File:** `app/Models/User.php`

### Update getDashboardRoles()

```php
public function getDashboardRoles()
{
    return Cache::remember(
        "user_{$this->id}_dashboard_roles",
        3600,
        function () {
            // Get roles from new system
            $orgRoles = \DB::table('user_organization_roles')
                ->where('user_id', $this->id)
                ->distinct()
                ->pluck('role')
                ->toArray();

            // Get legacy Spatie roles
            $legacyRoles = $this->roles->pluck('name')->toArray();

            // Merge and unique
            return array_unique(array_merge($orgRoles, $legacyRoles));
        }
    );
}

// Add new method for analyst-specific check
public function isAnalyst(): bool
{
    return in_array('analyst', $this->getDashboardRoles());
}

// Add method to get analyst organizations
public function getAnalystOrganizations()
{
    return \DB::table('user_organization_roles')
        ->where('user_id', $this->id)
        ->where('role', 'analyst')
        ->pluck('organisation_id')
        ->toArray();
}
```

---

## Step 4: Create the Controller

**File:** `app/Http/Controllers/AnalystDashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AnalystDashboardController extends Controller
{
    /**
     * Show the analyst dashboard
     *
     * Analysts can view election results, statistics, and generate reports
     * for their assigned organizations.
     */
    public function index()
    {
        $user = Auth::user();

        // Get organizations where user is analyst
        $analystOrgs = $user->getAnalystOrganizations();

        // Get elections for these organizations
        $elections = \DB::table('elections')
            ->whereIn('organisation_id', $analystOrgs)
            ->get();

        // Calculate statistics for each election
        $electionStats = $elections->map(function ($election) {
            return [
                'id' => $election->id,
                'title' => $election->title,
                'organisation_id' => $election->organisation_id,
                'status' => $election->status,
                'total_votes' => $this->getVoteCount($election->id),
                'participation_rate' => $this->getParticipationRate($election->id),
                'results' => $this->getElectionResults($election->id),
            ];
        });

        return Inertia::render('Analyst/Dashboard', [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'elections' => $electionStats,
            'organizations' => $analystOrgs,
        ]);
    }

    private function getVoteCount($electionId)
    {
        return \DB::table('votes')
            ->where('election_id', $electionId)
            ->count();
    }

    private function getParticipationRate($electionId)
    {
        $votes = $this->getVoteCount($electionId);
        $eligible = \DB::table('users')
            ->join('user_organization_roles', 'users.id', '=', 'user_organization_roles.user_id')
            ->join('elections', 'user_organization_roles.organisation_id', '=', 'elections.organisation_id')
            ->where('elections.id', $electionId)
            ->where('user_organization_roles.role', 'voter')
            ->count();

        return $eligible > 0 ? ($votes / $eligible) * 100 : 0;
    }

    private function getElectionResults($electionId)
    {
        // Return results grouped by candidate
        return \DB::table('votes')
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->where('votes.election_id', $electionId)
            ->select('candidates.name', \DB::raw('COUNT(*) as vote_count'))
            ->groupBy('candidates.id', 'candidates.name')
            ->get();
    }
}
```

---

## Step 5: Create the Vue Component

**File:** `resources/js/Pages/Analyst/Dashboard.vue`

```vue
<template>
  <div class="analyst-dashboard min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />

    <!-- Main Content -->
    <main class="grow max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
      <!-- Welcome Section -->
      <div class="mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">
          {{ $t('pages.analyst-dashboard.header.title', { name: userName }) }}
        </h1>
        <p class="text-xl text-gray-600">
          {{ $t('pages.analyst-dashboard.header.subtitle') }}
        </p>
      </div>

      <!-- Election Statistics Grid -->
      <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
          {{ $t('pages.analyst-dashboard.elections.title') }}
        </h2>

        <div v-if="elections.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="election in elections" :key="election.id" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <h3 class="text-lg font-bold text-gray-900 mb-2">
              {{ election.title }}
            </h3>

            <div class="space-y-3 mb-4">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ $t('pages.analyst-dashboard.stats.votes') }}</span>
                <span class="font-semibold">{{ election.total_votes }}</span>
              </div>

              <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ $t('pages.analyst-dashboard.stats.participation') }}</span>
                <span class="font-semibold">{{ election.participation_rate.toFixed(1) }}%</span>
              </div>

              <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ $t('pages.analyst-dashboard.stats.status') }}</span>
                <span class="font-semibold">{{ election.status }}</span>
              </div>
            </div>

            <button
              @click="viewElectionDetails(election.id)"
              class="w-full p-2 bg-blue-600 text-white rounded-sm hover:bg-blue-700 transition-colors text-sm font-medium"
            >
              {{ $t('pages.analyst-dashboard.buttons.viewDetails') }}
            </button>
          </div>
        </div>

        <div v-else class="p-6 bg-blue-50 border border-blue-200 rounded-lg">
          <p class="text-center text-blue-900">
            {{ $t('pages.analyst-dashboard.noElections') }}
          </p>
        </div>
      </div>

      <!-- Export Options -->
      <div class="bg-linear-to-r from-blue-50 to-indigo-50 rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
          {{ $t('pages.analyst-dashboard.export.title') }}
        </h2>

        <div class="flex gap-4">
          <button
            @click="exportCSV"
            class="px-4 py-2 bg-green-600 text-white rounded-sm hover:bg-green-700 transition-colors"
          >
            {{ $t('pages.analyst-dashboard.export.csv') }}
          </button>

          <button
            @click="exportPDF"
            class="px-4 py-2 bg-red-600 text-white rounded-sm hover:bg-red-700 transition-colors"
          >
            {{ $t('pages.analyst-dashboard.export.pdf') }}
          </button>

          <button
            @click="exportJSON"
            class="px-4 py-2 bg-yellow-600 text-white rounded-sm hover:bg-yellow-700 transition-colors"
          >
            {{ $t('pages.analyst-dashboard.export.json') }}
          </button>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script>
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import PublicDigitFooter from "@/Jetstream/PublicDigitFooter.vue";

export default {
  name: 'AnalystDashboard',

  components: {
    ElectionHeader,
    PublicDigitFooter,
  },

  props: {
    userName: String,
    userEmail: String,
    elections: Array,
    organizations: Array,
  },

  methods: {
    viewElectionDetails(electionId) {
      // TODO: Navigate to election details page
      console.log('View election details:', electionId);
    },

    exportCSV() {
      // TODO: Export to CSV
      console.log('Export to CSV');
    },

    exportPDF() {
      // TODO: Export to PDF
      console.log('Export to PDF');
    },

    exportJSON() {
      // TODO: Export to JSON
      console.log('Export to JSON');
    },
  },

  mounted() {
    console.log('Analyst Dashboard mounted');
    console.log('Elections:', this.elections);
  },
};
</script>

<style scoped>
.analyst-dashboard {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

main {
  flex-grow: 1;
}
</style>
```

---

## Step 6: Add Translations

**File:** `resources/js/locales/pages/Analyst/en.json`

```json
{
  "header": {
    "title": "Analytics Dashboard, {name}",
    "subtitle": "View election statistics, results, and generate reports"
  },
  "elections": {
    "title": "Elections"
  },
  "stats": {
    "votes": "Total Votes",
    "participation": "Participation Rate",
    "status": "Status"
  },
  "buttons": {
    "viewDetails": "View Results"
  },
  "noElections": "You don't have access to any elections yet.",
  "export": {
    "title": "Export Reports",
    "csv": "Export as CSV",
    "pdf": "Export as PDF",
    "json": "Export as JSON"
  }
}
```

Create for all languages: `en.json`, `de.json`, `np.json`

---

## Step 7: Register Routes

**File:** `routes/web.php`

```php
Route::middleware(['auth'])->group(function () {
    // ... existing routes ...

    // Analyst dashboard (requires analyst role)
    Route::prefix('dashboard/analyst')->middleware(['role:analyst'])->group(function () {
        Route::get('/', [AnalystDashboardController::class, 'index'])
            ->name('analyst.dashboard');
    });
});
```

---

## Step 8: Update LoginResponse

**File:** `app/Http/Responses/LoginResponse.php`

```php
// In the routing logic
if (count($dashboardRoles) === 1) {
    $role = reset($dashboardRoles);
    return match($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'commission' => redirect()->route('commission.dashboard'),
        'voter' => redirect()->route('vote.dashboard'),
        'analyst' => redirect()->route('analyst.dashboard'),  // ADD THIS
        default => redirect()->route('role.selection'),
    };
}
```

---

## Step 9: Update Middleware

**File:** `app/Http/Kernel.php`

No changes needed! The existing `CheckUserRole` middleware will work with the new 'analyst' role.

---

## Step 10: Register Translations in i18n.js

**File:** `resources/js/i18n.js`

```javascript
// Import new translations
import analystDe from './locales/pages/Analyst/de.json';
import analystEn from './locales/pages/Analyst/en.json';
import analystNp from './locales/pages/Analyst/np.json';

// In messages object
messages: {
  de: {
    pages: {
      // ... existing ...
      'analyst': analystDe,
    }
  },
  en: {
    pages: {
      // ... existing ...
      'analyst': analystEn,
    }
  },
  np: {
    pages: {
      // ... existing ...
      'analyst': analystNp,
    }
  }
}
```

---

## Testing the New Role

### 1. Assign User to Analyst Role

```php
// In artisan tinker
$user = User::find(5);
$org = organisation::find(1);

DB::table('user_organization_roles')->insert([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'role' => 'analyst',
    'created_at' => now(),
    'updated_at' => now(),
]);

// Clear cache
Cache::forget("user_{$user->id}_dashboard_roles");
```

### 2. Test Login Flow

```
Login as analyst user →
getDashboardRoles() returns ['analyst'] →
Single role →
Redirect to /dashboard/analyst →
Show Analyst Dashboard
```

### 3. Test Multi-Role

```php
// Add same user as analyst AND voter
DB::table('user_organization_roles')->insert([
    'user_id' => 5,
    'organisation_id' => 1,
    'role' => 'voter',
    'created_at' => now(),
    'updated_at' => now(),
]);

// Clear cache
Cache::forget("user_5_dashboard_roles");

// Login → getDashboardRoles() returns ['analyst', 'voter'] →
// Multiple roles → Redirect to /dashboard/roles → Show role selection
```

---

## Complete Checklist

- [ ] Design the role (what can it do?)
- [ ] Create database structure (or use existing tables)
- [ ] Update User model (getDashboardRoles, helper methods)
- [ ] Create dashboard controller
- [ ] Create Vue component
- [ ] Create translation files (EN/DE/NP)
- [ ] Add routes in routes/web.php
- [ ] Update LoginResponse (add role to match statement)
- [ ] Update i18n.js (register translations)
- [ ] Test login flow
- [ ] Test multi-role switching
- [ ] Test access control (try accessing unauthorized routes)
- [ ] Verify translations in all 3 languages

---

## Advanced: Custom Permissions

For more complex permissions (e.g., "analyst can view elections but not votes"):

```php
// In User model
public function canViewVotes($electionId): bool
{
    // Only commission members can view votes
    return $this->hasDashboardRole('commission') ||
           $this->hasRole('admin');
}

// In controller
if (!Auth::user()->canViewVotes($electionId)) {
    abort(403, 'Unauthorized');
}
```

---

## Advanced: Role-Specific Middleware

For role-specific logic (beyond just access control):

```php
// app/Http/Middleware/AnalystMiddleware.php
public function handle($request, Closure $next)
{
    $user = $request->user();

    if (!$user->isAnalyst()) {
        return redirect()->route('role.selection');
    }

    // Inject analyst-specific data
    $request->analystOrganizations = $user->getAnalystOrganizations();

    return $next($request);
}
```

Then register in routes:

```php
Route::middleware(['auth', 'analyst'])->group(function () {
    // Routes that need analyst-specific data
});
```
