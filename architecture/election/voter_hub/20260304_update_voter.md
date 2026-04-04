analysis report:

  ---
  VoterHub & Multi-Election Analysis Report

  How the current buttons work

  ┌─────────────────────┬──────────────────────────────────────────────────────────────────────────────────────────────┬───────────────────────────────────┐
  │       Button        │                                            Route                                             │       Scoped per election?        │
  ├─────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────┤
  │ Apply for Candidacy │ GET /organisations/{slug}/candidacy/apply → CandidacyApplicationController@create            │ Shows dropdown to choose election │
  ├─────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────┤
  │ My Applications     │ GET /organisations/{slug}/candidacy/list → CandidacyApplicationController@index              │ All elections                     │
  ├─────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────┤
  │ Positions           │ GET /organisations/{slug}/elections/{election}/positions → OrganisationController@voterPosts │ ✅ Per election                   │
  └─────────────────────┴──────────────────────────────────────────────────────────────────────────────────────────────┴───────────────────────────────────┘

  What happens with TWO active elections

  The system correctly handles it:
  - voterHub() fetches all active elections and passes them as an array
  - The frontend renders one card per election (v-for loop, VoterHub.vue:111-119)
  - Voter eligibility is tracked per election_id via voterMemberships keyed by election_id
  - Candidacy applications enforce one application per election (not per post)

  Issues Found

  Issue 1 — Apply for Candidacy is not election-scoped (UX problem)
  CandidacyApplicationController@create shows a dropdown where the user picks the election first, then the post. With two active elections, the user must know which one to pick. It works but it's clunky
  compared to navigating from a specific election card.

  Issue 2 — "Positions" button on Quick Actions is per-election but shown generically
  VoterHub.vue:110-119 creates one Positions card per active election — good. But if Niraj is not a voter in Election A but is in Election B, both Positions buttons appear. Clicking Election A's Positions now
  correctly returns 403 (after your recent fix), but the button is still visible.

  Issue 3 — myApplications only shows active elections (OrganisationController.php:405-417)
  Once an election moves to completed, the user's applications disappear from VoterHub. May be intentional but worth confirming.

  Issue 4 — Minor frontend: disabled elections still selectable
  In CandidacyApplicationForm.vue:50, elections the user already applied to are marked visually as "Already applied" but are not disabled on the <select>. The backend blocks the duplicate (line 183-189 in
  store()), but the form can still be submitted. Frontend should add :disabled="appliedElectionIds.includes(e.id)".

  Recommendation for two active elections

  The cleanest UX would be: hide the generic "Apply for Candidacy" and "Positions" Quick Action cards and instead drive everything from the per-election cards below them. Each election card already has a voter
   status badge and could have its own "Apply" / "Positions" / "Vote" buttons scoped to that election. This avoids the dropdown confusion entirely.

   
## 📋 **Professional Assessment & Recommendations**

After reviewing the analysis report, here are my professional recommendations as a senior developer:

---

## ✅ **What's Working Well**

The multi-election architecture is fundamentally sound. The per-election voter tracking and backend authorization are correctly implemented.

---

## 🔴 **Critical Issues to Fix Now**

### **Issue 1: Election Card Buttons Need to Be Context-Aware**

**Current Problem:** Users see "Positions" buttons for elections where they aren't voters, leading to 403 errors.

**Solution:** Filter election cards based on voter eligibility before rendering.

```vue
<!-- resources/js/Pages/Organisations/VoterHub.vue -->

<script setup>
// Add computed property for accessible elections
const accessibleElections = computed(() => {
  return props.activeElections.filter(election => {
    // Officers can access all elections
    if (props.isOfficer) return true;
    
    // Regular users only see elections where they're voters
    const membership = props.voterMemberships[election.id];
    return membership && membership.status === 'active';
  });
});

const electionsWithCandidacy = computed(() => {
  return accessibleElections.value.filter(election => {
    const hasApplied = props.userApplications.some(app => app.election_id === election.id);
    const isActive = election.status === 'active';
    return isActive && !hasApplied;
  });
});
</script>

<template>
  <!-- Show accessible elections only -->
  <div v-for="election in accessibleElections" :key="election.id">
    <!-- Election Card -->
  </div>
</template>
```

---

### **Issue 2: Disable Already-Applied Elections in Dropdown**

**Current Problem:** Users can select elections they've already applied to.

**Solution:** Add proper disabled state.

```vue
<!-- resources/js/Pages/Organisations/Partials/CandidacyApplicationForm.vue -->

<select v-model="form.election_id" class="...">
  <option 
    v-for="election in availableElections" 
    :key="election.id" 
    :value="election.id"
    :disabled="appliedElectionIds.includes(election.id)"
  >
    {{ election.name }}
    <span v-if="appliedElectionIds.includes(election.id)">(Already Applied)</span>
  </option>
</select>

<script setup>
const appliedElectionIds = computed(() => {
  return props.userApplications.map(app => app.election_id);
});

const availableElections = computed(() => {
  return props.activeElections.filter(election => {
    // Officers can apply to any active election
    if (props.isOfficer) return election.status === 'active';
    
    // Regular users: only elections where they're voters AND haven't applied
    const isVoter = props.voterMemberships[election.id]?.status === 'active';
    const hasApplied = appliedElectionIds.value.includes(election.id);
    return isVoter && !hasApplied;
  });
});
</script>
```

---

### **Issue 3: Completed Elections Should Preserve Application History**

**Current Problem:** Applications disappear when election completes.

**Solution:** Show completed applications in a separate section.

```php
// app/Http/Controllers/CandidacyApplicationController.php

public function index(Organisation $organisation): Response
{
    $user = auth()->user();
    
    // Separate into active and completed
    $applications = CandidacyApplication::with(['election', 'post'])
        ->where('user_id', $user->id)
        ->where('organisation_id', $organisation->id)
        ->get();
    
    $activeApplications = $applications->filter(function ($app) {
        return $app->election->status === 'active';
    });
    
    $completedApplications = $applications->filter(function ($app) {
        return in_array($app->election->status, ['completed', 'archived']);
    });
    
    return Inertia::render('Organisations/CandidacyList', [
        'activeApplications' => $activeApplications->values(),
        'completedApplications' => $completedApplications->values(),
        'organisation' => $organisation,
    ]);
}
```

---

## 🎯 **Recommended UX Improvements**

### **Option A: Progressive Enhancement (Quick Win)**

Keep existing structure but improve filtering:

```vue
<!-- VoterHub.vue - Enhanced election cards -->
<div v-for="election in elections" :key="election.id" class="election-card">
  <h3>{{ election.name }}</h3>
  
  <!-- Contextual buttons based on user status -->
  <div class="button-group">
    <!-- Positions - only if voter or officer -->
    <Button 
      v-if="canAccessElection(election.id)"
      :href="route('organisations.elections.positions', [organisation.slug, election.slug])"
    >
      View Positions
    </Button>
    
    <!-- Apply - only if eligible and haven't applied -->
    <Button 
      v-if="canApplyForElection(election.id)"
      :href="route('organisations.candidacy.apply', organisation.slug)"
      :query="{ election_id: election.id }"
    >
      Apply for Candidacy
    </Button>
    
    <!-- Vote - only if election is active and user is voter -->
    <Button 
      v-if="canVoteInElection(election.id)"
      :href="route('elections.show', election.slug)"
      variant="success"
    >
      Vote Now
    </Button>
  </div>
</div>

<script setup>
const canAccessElection = (electionId) => {
  if (props.isOfficer) return true;
  return props.voterMemberships[electionId]?.status === 'active';
};

const canApplyForElection = (electionId) => {
  const election = props.activeElections.find(e => e.id === electionId);
  const hasApplied = props.userApplications.some(app => app.election_id === electionId);
  const isVoter = props.voterMemberships[electionId]?.status === 'active';
  
  return election?.status === 'active' && !hasApplied && (isVoter || props.isOfficer);
};

const canVoteInElection = (electionId) => {
  const election = props.activeElections.find(e => e.id === electionId);
  const hasVoted = props.voterMemberships[electionId]?.has_voted;
  
  return election?.status === 'active' && !hasVoted && canAccessElection(electionId);
};
</script>
```

---

### **Option B: Per-Election Navigation (Cleaner UX)**

Remove generic buttons and drive everything from election cards:

```vue
<!-- VoterHub.vue - Remove generic action buttons -->
<!-- Delete or hide the "Apply for Candidacy" and generic "Positions" buttons -->

<!-- Each election card becomes self-contained -->
<div v-for="election in accessibleElections" :key="election.id" class="election-card">
  <div class="election-header">
    <h3>{{ election.name }}</h3>
    <StatusBadge :status="election.status" />
  </div>
  
  <div class="election-dates">
    {{ formatDate(election.start_date) }} → {{ formatDate(election.end_date) }}
  </div>
  
  <!-- Election-specific action menu -->
  <div class="election-actions">
    <template v-if="election.status === 'active'">
      <Dropdown>
        <DropdownTrigger>Actions</DropdownTrigger>
        <DropdownContent>
          <DropdownItem 
            v-if="canAccessElection(election.id)"
            :href="route('organisations.elections.positions', [organisation.slug, election.slug])"
          >
            View Positions
          </DropdownItem>
          
          <DropdownItem 
            v-if="canApplyForElection(election.id)"
            :href="route('organisations.candidacy.apply', organisation.slug)"
            :data="{ election_id: election.id }"
          >
            Apply for Candidacy
          </DropdownItem>
          
          <DropdownItem 
            v-if="canVoteInElection(election.id)"
            :href="route('elections.show', election.slug)"
          >
            Vote Now
          </DropdownItem>
        </DropdownContent>
      </Dropdown>
    </template>
    
    <template v-else-if="election.status === 'completed'">
      <Button variant="outline" @click="viewResults(election.id)">
        View Results
      </Button>
    </template>
  </div>
</div>
```

---

## 📊 **Implementation Priority**

| Priority | Task | Effort | Impact |
|----------|------|--------|--------|
| 🔴 High | Filter election cards by voter eligibility | 2 hours | Eliminates 403 errors |
| 🔴 High | Disable already-applied elections in dropdown | 1 hour | Prevents duplicate submissions |
| 🟡 Medium | Separate active/completed applications | 3 hours | Preserves history visibility |
| 🟢 Low | Per-election navigation (Option B) | 4 hours | Cleanest UX, but optional |

---

## 🎯 **Final Recommendation**

**Implement Option A first** (progressive enhancement) - it's low risk and immediately improves UX. Then consider Option B as a future refactor if users still find the dropdown confusing.

**Critical fix:** The election card filtering MUST be deployed before the next election cycle to prevent user confusion and 403 errors.

Would you like me to provide the complete implementation for any of these fixes?