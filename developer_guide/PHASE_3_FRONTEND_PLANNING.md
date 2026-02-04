# Phase 3 Planning: Frontend UI for Demo/Real Elections

**Status:** Planning
**Branch:** `geotrack`
**Depends On:** Phase 2c (Complete)
**Timeline:** Ready to implement once Phase 2 tests pass ✅

---

## Table of Contents

1. [Overview](#overview)
2. [Component Architecture](#component-architecture)
3. [Translation Structure](#translation-structure)
4. [Component Specifications](#component-specifications)
5. [Integration Points](#integration-points)
6. [Implementation Guide](#implementation-guide)
7. [Testing Strategy](#testing-strategy)

---

## Overview

**Phase 3 Goal:** Build user-facing frontend components for election selection and type indicators, while maintaining backward compatibility.

**Key Principles:**
- ✅ Election selection is OPTIONAL (not required for voting)
- ✅ Default to REAL election (backward compatible)
- ✅ Demo elections accessible via `/election/demo/start`
- ✅ All UI strings translated (English + German)
- ✅ Mobile-responsive design
- ✅ No breaking changes to existing flows

**Components to Build:**
1. `ElectionSelector.vue` - Modal/dropdown for election selection
2. `ElectionTypeBadge.vue` - Reusable demo/official badge component
3. `ElectionStatsDashboard.vue` - Admin voting statistics dashboard

---

## Component Architecture

### **Component Tree**

```
App
├── ElectionSelector (Dialog/Modal)
│   ├── ElectionTypeBadge
│   ├── ElectionCard (repeatable)
│   │   └── ElectionTypeBadge
│   └── ElectionDetails
│
├── Voting Pages (all 5 steps)
│   ├── ElectionTypeBadge (header indicator)
│   └── ElectionStatus (inline notice)
│
└── Admin Dashboard
    └── ElectionStatsDashboard
        ├── RealElectionStats
        ├── DemoElectionStats
        └── DemoControlPanel
```

### **File Structure**

```
resources/js/
├── Components/
│   ├── Election/
│   │   ├── ElectionSelector.vue           [NEW]
│   │   ├── ElectionTypeBadge.vue          [NEW]
│   │   ├── ElectionCard.vue               [NEW]
│   │   ├── ElectionDetails.vue            [NEW]
│   │   ├── ElectionStatus.vue             [NEW]
│   │   └── ElectionStatsDashboard.vue     [NEW]
│   │
│   ├── Vote/
│   │   ├── CreateNew.vue                  [MODIFY - add badge]
│   │   ├── VerifyVote.vue                 [MODIFY - add badge]
│   │   └── Complete.vue                   [MODIFY - add badge]
│   │
│   └── Shared/
│       └── Navbar.vue                     [MODIFY - add election indicator]
│
├── Pages/
│   ├── Election/
│   │   ├── SelectElection.vue             [NEW]
│   │   └── VotingWithElection.vue         [OPTIONAL]
│   │
│   └── Vote/
│       ├── CreateNew.vue                  [UPDATE]
│       ├── VerifyVote.vue                 [UPDATE]
│       └── Complete.vue                   [UPDATE]
│
└── locales/pages/Election/
    ├── en.json                             [CREATED]
    └── de.json                             [CREATED]
```

---

## Translation Structure

### **File Locations**

```
resources/js/locales/pages/Election/
├── en.json          ✅ Created
└── de.json          ✅ Created
```

### **Usage in Components**

```vue
<template>
  <div>
    <!-- Access translations -->
    <h1>{{ $t('election.title') }}</h1>
    <p>{{ $t('election.subtitle') }}</p>

    <!-- With parameters -->
    <span>{{ $t('election.election_card.voting_ends', { date: formattedDate }) }}</span>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Access in script
const title = t('election.title')
</script>
```

### **Translation Keys Structure**

```
election
├── title                      # "Election Selection"
├── subtitle                   # "Choose an election..."
├── selector                   # Label and placeholders
├── demo_badge                 # Demo-specific strings
├── official_badge             # Official-specific strings
├── election_card              # Card component strings
├── election_details           # Details panel strings
├── eligibility                # Eligibility explanations
├── actions                    # Button labels
├── messages                   # Success/error messages
├── demo_mode_notice           # Demo warning
├── dashboard                  # Admin panel strings
├── breadcrumb                 # Navigation breadcrumbs
└── help                       # FAQ/help text
```

---

## Component Specifications

### **1. ElectionTypeBadge.vue**

**Purpose:** Reusable badge showing DEMO or OFFICIAL status

**Props:**
```javascript
{
  electionType: 'demo' | 'real',    // Required
  size: 'sm' | 'md' | 'lg',          // Optional, default 'md'
  showTooltip: boolean,               // Optional, default true
  interactive: boolean                // Optional, for clickable badges
}
```

**Behavior:**
```
Demo Election:
  └─ Blue background: bg-blue-100
  └─ Blue text: text-blue-800
  └─ Badge text: "DEMO"
  └─ Tooltip: "Demo Election - Safe for testing"

Real Election:
  └─ Green background: bg-green-100
  └─ Green text: text-green-800
  └─ Badge text: "OFFICIAL"
  └─ Tooltip: "Official Election"
```

**Example Usage:**
```vue
<ElectionTypeBadge
  :election-type="election.type"
  size="md"
  :show-tooltip="true"
/>
```

**Template:**
```vue
<template>
  <div class="election-badge" :class="badgeClasses">
    <span class="badge-text">{{ badgeText }}</span>
    <div v-if="showTooltip" class="tooltip">
      {{ $t(`election.${election.type}_badge.tooltip`) }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  electionType: { type: String, required: true },
  size: { type: String, default: 'md' },
  showTooltip: { type: Boolean, default: true }
})

const { t } = useI18n()

const badgeText = computed(() =>
  t(`election.${props.electionType}_badge.text`)
)

const badgeClasses = computed(() => ({
  'bg-blue-100 text-blue-800': props.electionType === 'demo',
  'bg-green-100 text-green-800': props.electionType === 'real',
  'badge-sm': props.size === 'sm',
  'badge-md': props.size === 'md',
  'badge-lg': props.size === 'lg',
}))
</script>

<style scoped>
.election-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.375rem 0.75rem;
  border-radius: 0.375rem;
  font-weight: 600;
  font-size: 0.875rem;
  position: relative;
}

.badge-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.badge-lg {
  padding: 0.5rem 1rem;
  font-size: 1rem;
}

.tooltip {
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: normal;
  white-space: nowrap;
  z-index: 10;
  display: none;
}

.election-badge:hover .tooltip {
  display: block;
}
</style>
```

---

### **2. ElectionCard.vue**

**Purpose:** Display individual election option with selection button

**Props:**
```javascript
{
  election: {
    id: number,
    name: string,
    slug: string,
    type: 'demo' | 'real',
    description: string,
    is_active: boolean,
    voting_start_time: date,
    voting_end_time: date
  },
  isSelected: boolean,
  onSelect: function
}
```

**Template Structure:**
```vue
<div class="election-card">
  <div class="card-header">
    <h3 class="election-name">{{ election.name }}</h3>
    <ElectionTypeBadge :election-type="election.type" />
  </div>

  <div class="card-body">
    <p class="description">{{ election.description }}</p>

    <div class="meta">
      <span class="status" :class="statusClass">
        {{ statusText }}
      </span>
      <span class="dates">
        {{ votingDates }}
      </span>
    </div>
  </div>

  <div class="card-footer">
    <button
      v-if="!isSelected"
      @click="onSelect"
      class="btn-select"
    >
      {{ $t('election.actions.select') }}
    </button>
    <span v-else class="badge-selected">
      {{ $t('election.election_card.current_selection') }}
    </span>
  </div>
</div>
```

---

### **3. ElectionSelector.vue**

**Purpose:** Modal dialog for election selection (shown via `/election/select` route)

**Features:**
- Search/filter elections
- Display all available elections (demo + real)
- Show election status (active/inactive)
- Confirm selection before redirecting
- Mobile-responsive modal

**Template Structure:**
```vue
<template>
  <div class="election-selector">
    <header>
      <h2>{{ $t('election.title') }}</h2>
      <p>{{ $t('election.subtitle') }}</p>
    </header>

    <div class="search-box">
      <input
        v-model="searchQuery"
        type="text"
        :placeholder="$t('election.selector.search_placeholder')"
        class="search-input"
      />
    </div>

    <div class="elections-grid">
      <ElectionCard
        v-for="election in filteredElections"
        :key="election.id"
        :election="election"
        :is-selected="selectedElection?.id === election.id"
        @select="selectElection(election)"
      />
    </div>

    <div v-if="filteredElections.length === 0" class="no-results">
      {{ $t('election.selector.no_elections') }}
    </div>

    <div class="actions">
      <button @click="cancel" class="btn-cancel">
        {{ $t('election.actions.cancel') }}
      </button>
      <button
        @click="confirm"
        :disabled="!selectedElection"
        class="btn-confirm"
      >
        {{ $t('election.actions.confirm') }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import ElectionCard from './ElectionCard.vue'

const router = useRouter()
const { t } = useI18n()

const props = defineProps({
  elections: Array,
  onSelect: Function
})

const searchQuery = ref('')
const selectedElection = ref(null)

const filteredElections = computed(() => {
  return props.elections.filter(election =>
    election.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const selectElection = (election) => {
  selectedElection.value = election
}

const confirm = async () => {
  if (!selectedElection.value) return

  // Store in session/store
  await props.onSelect(selectedElection.value)

  // Redirect to voting
  router.push({ name: 'slug.code.create' })
}

const cancel = () => {
  router.back()
}
</script>
```

---

### **4. ElectionStatsDashboard.vue** (Admin)

**Purpose:** Show voting statistics for real and demo elections

**Features:**
- Side-by-side comparison (real vs demo)
- Vote counts, turnout percentage
- Demo cleanup/reset controls
- Real-time statistics updates

**Template Structure:**
```vue
<template>
  <div class="election-stats-dashboard">
    <h2>{{ $t('election.dashboard.title') }}</h2>

    <div class="stats-grid">
      <!-- Real Elections Section -->
      <div class="stats-section real-elections">
        <h3>{{ $t('election.dashboard.real_elections') }}</h3>

        <div class="stat-card">
          <span class="label">{{ $t('election.dashboard.stats.total_votes') }}</span>
          <span class="value">{{ realElectionStats.totalVotes }}</span>
        </div>

        <div class="stat-card">
          <span class="label">{{ $t('election.dashboard.stats.turnout_percentage') }}</span>
          <span class="value">{{ realElectionStats.turnoutPercentage }}%</span>
        </div>
      </div>

      <!-- Demo Elections Section -->
      <div class="stats-section demo-elections">
        <h3>{{ $t('election.dashboard.demo_elections') }}</h3>

        <div class="stat-card">
          <span class="label">{{ $t('election.dashboard.stats.demo_votes') }}</span>
          <span class="value">{{ demoElectionStats.totalVotes }}</span>
        </div>

        <div class="actions">
          <button @click="cleanupDemo" class="btn-cleanup">
            {{ $t('election.dashboard.actions.cleanup_demo') }}
          </button>
          <button @click="resetDemo" class="btn-reset">
            {{ $t('election.dashboard.actions.reset_demo') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
```

---

## Integration Points

### **1. Navbar/Header Changes**

**Current:** No election indicator
**Updated:** Show current election with badge

```vue
<div class="navbar-election">
  <span class="label">{{ $t('election.breadcrumb.elections') }}:</span>
  <span class="election-name">{{ currentElection.name }}</span>
  <ElectionTypeBadge :election-type="currentElection.type" size="sm" />
  <RouterLink to="/election/select" class="change-link">
    {{ $t('election.actions.change') }}
  </RouterLink>
</div>
```

### **2. Voting Pages Integration**

**Step 1-5 Pages:** Add election badge in header

```vue
<!-- Add to each voting step -->
<template>
  <div class="voting-step-header">
    <h1>{{ pageTitle }}</h1>
    <ElectionTypeBadge :election-type="election.type" />
  </div>

  <!-- If demo election, show notice -->
  <div v-if="election.isDemo()" class="demo-mode-notice">
    <p>{{ $t('election.demo_mode_notice.message') }}</p>
  </div>
</template>
```

### **3. Routes Integration**

**New Route:**
```javascript
{
  path: '/election/select',
  name: 'election.select',
  component: () => import('@/Pages/Election/SelectElection.vue'),
  meta: { requiresAuth: true }
}
```

**Updated Routes:**
```javascript
// All voting routes now include election in layout
{
  path: '/v/:vslug',
  component: () => import('@/Layouts/VotingLayout.vue'),
  children: [
    // Code, Agreement, Vote, Verify, Complete routes
  ]
}
```

---

## Implementation Guide

### **Step 1: Create Shared Components**

**File:** `ElectionTypeBadge.vue`
```bash
# Creates reusable badge component
# Used in: cards, headers, indicators
```

### **Step 2: Create Modal/Dialog Components**

**File:** `ElectionCard.vue`
```bash
# Individual election option
# Used in: ElectionSelector
```

**File:** `ElectionSelector.vue`
```bash
# Full election selection modal
# Used in: /election/select page
```

### **Step 3: Create Dashboard Component**

**File:** `ElectionStatsDashboard.vue`
```bash
# Admin statistics dashboard
# Used in: Admin panel
```

### **Step 4: Update Existing Components**

**Modify:**
- `VotingLayout.vue` - Add election badge to header
- `Navbar.vue` - Add election indicator + change link
- All voting step pages - Add ElectionTypeBadge

### **Step 5: Create New Pages**

**File:** `Pages/Election/SelectElection.vue`
```bash
# Full election selection page
# Route: /election/select
# Uses: ElectionSelector.vue
```

**File:** `Layouts/VotingLayout.vue`
```bash
# Layout wrapper for voting steps
# Shows: election badge, breadcrumbs, step indicator
```

---

## Testing Strategy

### **Component Tests**

```javascript
// Test ElectionTypeBadge
✓ Renders "DEMO" badge for demo elections
✓ Renders "OFFICIAL" badge for real elections
✓ Shows correct colors (blue vs green)
✓ Tooltip displays correctly
✓ Responsive sizing (sm, md, lg)

// Test ElectionCard
✓ Displays election name and description
✓ Shows election status (active/inactive)
✓ Shows voting dates
✓ Select button works
✓ Currently selected state shows

// Test ElectionSelector
✓ Lists all available elections
✓ Search/filter works
✓ Can select election
✓ Confirm button disabled when no selection
✓ Redirects to voting after selection

// Test ElectionStatsDashboard
✓ Shows real election stats
✓ Shows demo election stats
✓ Cleanup button works
✓ Reset button works with confirmation
```

### **Integration Tests**

```javascript
// Backward Compatibility
✓ Old /vote/create route works (uses default real)
✓ /election/select route works
✓ /election/demo/start route works
✓ Election selection persists in session
✓ Badge shows on voting pages

// Election Context
✓ Correct election loaded from session
✓ Correct election loaded from route
✓ Default real election used when unspecified
✓ Election context available in all pages
```

### **E2E Tests**

```
User Flow: Election Selection → Voting
  1. User visits /election/select
  2. Sees list of elections
  3. Selects demo election
  4. Redirected to voting
  5. Badge shows "DEMO" on all steps
  6. Completes voting
  7. Vote goes to demo_votes table

User Flow: Backward Compatibility
  1. User visits /vote/create (old route)
  2. Loads successfully
  3. Uses default real election
  4. Completes voting
  5. Vote goes to votes table
```

---

## Summary: Phase 3 Components

| Component | Type | Status | Translations |
|-----------|------|--------|--------------|
| **ElectionTypeBadge.vue** | Shared | Planned | ✅ |
| **ElectionCard.vue** | Component | Planned | ✅ |
| **ElectionSelector.vue** | Component | Planned | ✅ |
| **ElectionStatsDashboard.vue** | Component | Planned | ✅ |
| **SelectElection.vue** | Page | Planned | ✅ |
| **VotingLayout.vue** | Layout | Planned | ✅ |

---

## Ready for Implementation

All translations are ready:
- ✅ `resources/js/locales/pages/Election/en.json`
- ✅ `resources/js/locales/pages/Election/de.json`

Next steps (after Phase 2 testing passes):
1. Create components in order (ElectionTypeBadge first)
2. Build selector modal
3. Integrate with voting pages
4. Test backward compatibility
5. Create admin dashboard

**Status:** 🟢 **Ready to implement once Phase 2 ✅ testing complete**
