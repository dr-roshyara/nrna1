## 🏛️ CORRECT ARCHITECTURE: Organisation, User, Election, VoterSlug Consistency

Here's the proper architecture with clear error handling when mismatches occur:

```
┌─────────────────────────────────────────────────────────────────┐
│                    TENANT ARCHITECTURE                           │
│                                                                   │
│  organisations                                                  │
│  ├── id: 1 → "Platform" (SYSTEM-WIDE)                           │
│  ├── id: 2 → "Namaste Nepal ev" (Your Org)                      │
│  └── id: 3 → "Another Org"                                       │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         ELECTIONS                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  ID  │ Type │ Organisation │ Purpose                    │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │  1   │ demo │      1       │ 🌍 GLOBAL DEMO (all users) │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │  2   │ real │      1       │ 🏛️ Platform Real (rare)    │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │  3   │ real │      2       │ 🗳️ Namaste's Real Election │    │
│  ├─────────────────────────────────────────────────────────┤    │
│  │  4   │ demo │      2       │ 🧪 Namaste's Private Demo  │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## 🔗 THE CONSISTENCY RULES

### Rule 1: **User Belongs to ONE Organisation**
```php
// User 42 (you) has organisation_id = 2
User: [
    id: 42,
    name: "Your Name",
    organisation_id: 2  // ← Your org
]
```

### Rule 2: **Election Belongs to ONE Organisation (or Platform)**
```php
// Elections visible to you:
$yourElections = Election::where('organisation_id', 2)  // Your org's elections
    ->orWhere('organisation_id', 1)                      // Platform elections
    ->get();
```

### Rule 3: **VoterSlug MUST Match ALL Three**
```php
// When you start voting, VoterSlug locks to:
$voterSlug = VoterSlug::create([
    'user_id' => 42,                    // You
    'organisation_id' => 2,              // Your org
    'election_id' => 3,                  // Specific election
    // ↑ ALL THREE MUST MATCH!
]);
```

## 🚨 ERROR HANDLING AT EVERY LAYER

### Layer 1: **Election Selection** - User-Friendly Errors

```php
// In DemoElectionResolver.php
public function getDemoElectionForUser(User $user): Election
{
    // Try org-specific demo first
    $orgDemo = Election::where('type', 'demo')
        ->where('organisation_id', $user->organisation_id)
        ->where('is_active', true)
        ->first();
    
    if ($orgDemo) {
        return $orgDemo;
    }
    
    // Fallback to global demo
    $globalDemo = Election::where('type', 'demo')
        ->where('organisation_id', 1)  // Platform org
        ->where('is_active', true)
        ->first();
    
    if ($globalDemo) {
        return $globalDemo;
    }
    
    // ❌ NO DEMO AVAILABLE - Show user-friendly message
    throw new NoDemoElectionException(
        'No demo election is currently available. ' .
        'Please contact your organisation administrator to set up a demo election.'
    );
}
```

### Layer 2: **VoterSlug Creation** - Validate Before Creating

```php
// In VoterSlugService.php
public function getOrCreateActiveSlug(User $user): VoterSlug
{
    // VALIDATION 1: Does user have valid organisation?
    if (!$user->organisation_id) {
        throw new InvalidVoterException(
            'Your account is not associated with any organisation. ' .
            'Please contact support.'
        );
    }
    
    // VALIDATION 2: Is there an active election for this user?
    $activeElection = Election::where('organisation_id', $user->organisation_id)
        ->where('status', 'active')
        ->first();
    
    if (!$activeElection && !$this->isDemoRequest()) {
        throw new NoActiveElectionException(
            'There is no active election for your organisation at this time. ' .
            'Please check back later or contact your administrator.'
        );
    }
    
    // Create slug only after validations pass
    return $this->createSlug($user, $election);
}
```

### Layer 3: **Middleware Chain** - Clear Error Messages

#### 1. `VerifyVoterSlug` Middleware
```php
public function handle($request, $next)
{
    $voterSlug = VoterSlug::withoutGlobalScopes()
        ->withEssentialRelations()
        ->where('slug', $request->route('vslug'))
        ->first();
    
    if (!$voterSlug) {
        Log::warning('Invalid voter slug accessed', [
            'slug' => $request->route('vslug'),
            'ip' => $request->ip()
        ]);
        
        // ❌ USER-FRIENDLY ERROR
        return redirect()->route('dashboard')
            ->with('error', 'Your voting session is invalid or has expired. Please start again from the election dashboard.');
    }
    
    // Check ownership
    if ($voterSlug->user_id !== auth()->id()) {
        Log::warning('User tried to access another user\'s voting session', [
            'slug_user' => $voterSlug->user_id,
            'auth_user' => auth()->id()
        ]);
        
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access this voting session.');
    }
    
    $request->attributes->set('voter_slug', $voterSlug);
    return $next($request);
}
```

#### 2. `VerifyVoterSlugConsistency` Middleware
```php
public function handle($request, $next)
{
    $voterSlug = $request->attributes->get('voter_slug');
    
    // Check election exists
    $election = $voterSlug->election;
    if (!$election) {
        Log::critical('Voter slug references non-existent election', [
            'slug_id' => $voterSlug->id,
            'election_id' => $voterSlug->election_id
        ]);
        
        return redirect()->route('dashboard')
            ->with('error', 'The election associated with your voting session no longer exists. Please contact support.');
    }
    
    // Check organisation consistency
    $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
    $electionIsPlatform = $election->organisation_id === 1;
    $userIsPlatform = $voterSlug->organisation_id === 1;
    
    if (!$orgsMatch && !$electionIsPlatform && !$userIsPlatform) {
        Log::error('Organisation mismatch in voting session', [
            'slug_org' => $voterSlug->organisation_id,
            'election_org' => $election->organisation_id,
            'user_org' => auth()->user()->organisation_id
        ]);
        
        // ❌ SPECIFIC ERROR
        return redirect()->route('dashboard')
            ->with('error', 'There is a configuration mismatch with your voting session. Please contact your administrator.');
    }
    
    return $next($request);
}
```

#### 3. `VoteEligibility` Middleware
```php
public function handle($request, $next)
{
    $user = auth()->user();
    $election = $request->attributes->get('election');
    
    // Check if user belongs to election's organisation
    if ($election->organisation_id !== 1 &&  // Not platform election
        $user->organisation_id !== $election->organisation_id) {
        
        Log::warning('User attempted to vote in election from wrong organisation', [
            'user_org' => $user->organisation_id,
            'election_org' => $election->organisation_id
        ]);
        
        return redirect()->route('dashboard')
            ->with('error', 'You are not eligible to vote in this election. ' .
                   'This election is only for members of organisation #' . $election->organisation_id);
    }
    
    return $next($request);
}
```

### Layer 4: **Frontend Error Display** - In Dashboard.vue

```vue
<template>
  <div>
    <!-- Error Alert Component -->
    <div v-if="$page.props.flash.error" class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 rounded-lg">
      <div class="flex items-start">
        <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
          <p class="text-red-800 font-medium">{{ $page.props.flash.error }}</p>
          <p v-if="showHelp" class="text-red-700 text-sm mt-1">
            If this issue persists, please contact support at <a href="mailto:support@publicdigit.com" class="underline">support@publicdigit.com</a>
          </p>
        </div>
      </div>
    </div>
    
    <!-- Demo Mode Info -->
    <div v-if="isDemoMode" class="mb-4 p-4 bg-purple-100 border-l-4 border-purple-500 rounded-lg">
      <p class="text-purple-800">
        🎮 You are in demo mode. Your votes will not affect real elections.
      </p>
    </div>
    
    <!-- Main Dashboard Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Election Status Card -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Election Status</h3>
        <div v-if="election">
          <p class="text-3xl font-bold text-green-600">{{ election.status }}</p>
          <p class="text-sm text-gray-600 mt-2">{{ election.name }}</p>
        </div>
        <div v-else class="text-gray-500">
          <p>No active election</p>
        </div>
      </div>
      
      <!-- Voting Access Card -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Voting Access</h3>
        <div v-if="canVote">
          <Link :href="route('election.demo.start')" 
                class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            Try Demo Election
          </Link>
        </div>
        <div v-else class="text-gray-500">
          <p>You are not eligible to vote</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

// Check if user is in demo mode
const isDemoMode = computed(() => {
  return page.props.election?.type === 'demo' || 
         page.props.flash?.success?.includes('🎮')
})

// Show help link for persistent errors
const showHelp = computed(() => {
  const error = page.props.flash?.error || ''
  return error.includes('configuration') || 
         error.includes('contact support') ||
         error.includes('persists')
})
</script>
```

## 📋 COMPLETE ERROR HANDLING MATRIX

| Scenario | Error Message | Where Shown |
|----------|---------------|-------------|
| No demo election available | "No demo election is currently available. Please contact your administrator." | Dashboard |
| User has no organisation | "Your account is not associated with any organisation. Please contact support." | Dashboard |
| No active election | "There is no active election for your organisation at this time." | Dashboard |
| Invalid/expired voter slug | "Your voting session is invalid or has expired. Please start again." | Voting page redirect |
| Wrong user's slug | "You do not have permission to access this voting session." | Voting page redirect |
| Election no longer exists | "The election associated with your session no longer exists. Contact support." | Dashboard |
| Organisation mismatch | "There is a configuration mismatch with your voting session. Contact administrator." | Dashboard |
| Wrong org election access | "You are not eligible to vote in this election." | Voting page redirect |

## 🎯 YOUR SPECIFIC CASE

For you (user with organisation_id = 2):

```php
// Should see:
$yourElections = [
    Election::find(3), // Real election for org 2
    Election::find(4), // Demo election for org 2
    Election::find(1), // Global demo (org 1)
];

// Should NOT see:
$electionsYouCannotAccess = [
    Election::find(2), // Real election for org 1
];
```

If you're getting a redirect without error, add this debug code to your `startDemo()` method:

```php
try {
    $demoElection = $this->demoResolver->getDemoElectionForUser($authUser);
    
    if (!$demoElection) {
        Log::error('No demo election found', ['user_org' => $authUser->organisation_id]);
        return redirect()->route('dashboard')
            ->with('error', 'No demo election found for your organisation. Please run: php artisan demo:setup --org=' . $authUser->organisation_id);
    }
    
    // Debug output
    Log::info('Demo election found', [
        'election_id' => $demoElection->id,
        'election_org' => $demoElection->organisation_id,
        'user_org' => $authUser->organisation_id
    ]);
    
    // Continue...
} catch (\Exception $e) {
    Log::error('Demo start failed', ['error' => $e->getMessage()]);
    return redirect()->route('dashboard')
        ->with('error', 'Failed to start demo: ' . $e->getMessage());
}
```

This will show you exactly why it's failing! 🎯
