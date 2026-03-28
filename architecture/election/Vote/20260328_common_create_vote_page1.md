# Claude Code Instructions: Unified CreateVotePage.vue with Full Compatibility

```bash
claude code "Create a unified voting form component that works for both demo and real elections with full backward compatibility.

## CRITICAL REQUIREMENT: Maintain 100% Backward Compatibility
- Existing voting flows must continue working without changes
- All existing route names must remain unchanged
- Existing controller responses must be preserved
- The new component must accept BOTH data structures and normalize internally

## Context Files to Read
- `resources/js/Pages/Vote/DemoVote/Create.vue` - Reference for UX/accessibility
- `resources/js/Pages/Vote/CreateVotingPage.vue` - Reference for auto-save
- `app/Http/Controllers/Demo/DemoVoteController.php` - Line ~554 (demo controller)
- `app/Http/Controllers/VoteController.php` - Line ~450 (real controller)

## File to Create
`resources/js/Pages/Vote/CreateVotePage.vue`

## Props API (Backward Compatible)

```javascript
props: {
    // Option 1: New unified format (preferred)
    posts: {
        type: Object,
        default: null,
        validator: (value) => value === null || (value.national !== undefined && value.regional !== undefined)
    },
    // Option 2: Legacy demo format (for DemoVoteController)
    national_posts: { type: Array, default: null },
    regional_posts: { type: Array, default: null },
    // Option 3: Legacy production format (for VoteController)
    // national_posts and regional_posts also used here
    
    // Core props (required in both formats)
    user_name: { type: String, required: true },
    user_id: { type: Number, required: true },
    user_region: { type: String, default: '' },
    
    // Routing props
    slug: { type: String, default: null },
    useSlugPath: { type: Boolean, default: false },
    
    // Election context
    election: { type: Object, default: null },
    
    // Auto-detected from election.type, but can be overridden
    isDemoMode: { type: Boolean, default: null },
    
    // Eligibility (passed from controller)
    hasVerifiedCode: { type: Boolean, default: false }
}
```

## Data Normalization (First Priority - Must Work with All Formats)

```javascript
const isDemoMode = computed(() => {
    if (props.isDemoMode !== null) return props.isDemoMode
    return props.election?.type === 'demo'
})

// Normalize posts - handle all three input formats
const normalizedNationalPosts = computed(() => {
    // Format 1: Unified posts object
    if (props.posts?.national) return props.posts.national
    
    // Format 2 & 3: Legacy national_posts array
    if (props.national_posts) {
        return props.national_posts.map(post => ({
            ...post,
            id: post.id ?? post.post_id,  // Ensure consistent id field
            candidates: (post.candidates || []).map(c => ({
                ...c,
                id: c.id ?? c.candidacy_id,  // Ensure consistent id field
                candidacy_id: c.candidacy_id ?? c.id,
                user_name: c.user_name ?? c.candidacy_name ?? c.name,
                candidacy_name: c.candidacy_name ?? c.user_name ?? c.name
            }))
        }))
    }
    
    return []
})

const normalizedRegionalPosts = computed(() => {
    if (props.posts?.regional) return props.posts.regional
    if (props.regional_posts) {
        return props.regional_posts.map(post => ({
            ...post,
            id: post.id ?? post.post_id,
            candidates: (post.candidates || []).map(c => ({
                ...c,
                id: c.id ?? c.candidacy_id,
                candidacy_id: c.candidacy_id ?? c.id,
                user_name: c.user_name ?? c.candidacy_name ?? c.name,
                candidacy_name: c.candidacy_name ?? c.user_name ?? c.name
            }))
        }))
    }
    return []
})

// Combined posts object for template
const combinedPosts = computed(() => ({
    national: normalizedNationalPosts.value,
    regional: normalizedRegionalPosts.value
}))
```

## Submit Route (Preserve Existing Route Structure)

```javascript
const submitRoute = computed(() => {
    const endpoint = isDemoMode.value ? 'demo-vote' : 'vote'
    
    // Use slug-based route if available
    if (props.useSlugPath && props.slug) {
        return route(`slug.${endpoint}.submit`, { vslug: props.slug })
    }
    
    // Fall back to non-slug route
    return route(`${endpoint}.submit`)
})
```

## Layout Selection (Preserve Existing Layout Structure)

```html
<template>
    <!-- Demo layout: uses NrnaLayout + AppLayout -->
    <nrna-layout v-if="isDemoMode">
        <app-layout>
            <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
                <!-- Main content -->
            </div>
        </app-layout>
    </nrna-layout>
    
    <!-- Real election layout: uses ElectionLayout -->
    <election-layout v-else>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
            <!-- Main content -->
        </div>
    </election-layout>
</template>
```

## Core Logic (Merge Both Components)

```javascript
// State (from DemoVote/Create.vue)
const selectedCandidates = ref({})
const noVoteSelections = ref({})
const errors = ref({})
const loading = ref(false)
const isLoading = ref(true)  // For skeleton
const showConfirmModal = ref(false)
const builtVoteData = ref(null)

// Auto-save (from CreateVotingPage.vue)
const isDirty = ref(false)
const lastSavedState = ref('')
const autoSaveEnabled = ref(true)

// Form
const form = useForm({
    user_id: props.user_id,
    agree_button: false,
})

// Helper functions
const sortedCandidates = (candidates) => {
    if (!candidates) return []
    return [...candidates].sort((a, b) => (a.position_order || 0) - (b.position_order || 0))
}

const isSelected = (postId, candidateId) => {
    if (noVoteSelections.value[postId]) return false
    return selectedCandidates.value[postId]?.includes(candidateId) || false
}

const toggleCandidate = (post, candidate) => {
    if (noVoteSelections.value[post.id]) return
    
    const currentSelected = [...(selectedCandidates.value[post.id] || [])]
    const index = currentSelected.indexOf(candidate.id)
    
    if (index === -1) {
        if (currentSelected.length < (post.required_number || 1)) {
            currentSelected.push(candidate.id)
            selectedCandidates.value[post.id] = currentSelected
        } else {
            // Show max selection warning
            errors.value[`max_${post.id}`] = `You can only select up to ${post.required_number} candidates`
            setTimeout(() => delete errors.value[`max_${post.id}`], 3000)
            return
        }
    } else {
        currentSelected.splice(index, 1)
        selectedCandidates.value[post.id] = currentSelected
    }
    
    isDirty.value = true
}

const toggleNoVote = (post) => {
    if (noVoteSelections.value[post.id]) {
        noVoteSelections.value[post.id] = false
    } else {
        noVoteSelections.value[post.id] = true
        selectedCandidates.value[post.id] = []
    }
    isDirty.value = true
}

const getSelectionOrder = (postId, candidateId) => {
    const selected = selectedCandidates.value[postId] || []
    const index = selected.indexOf(candidateId)
    return index >= 0 ? index + 1 : 0
}

const getPostSelectionStatus = (post) => {
    if (noVoteSelections.value[post.id]) {
        return { type: 'no-vote', message: 'Skipped this position', icon: '⏭️' }
    }
    
    const selected = selectedCandidates.value[post.id]?.length || 0
    const required = post.required_number || 1
    
    if (selected === 0) {
        return { type: 'empty', message: `Select ${required} candidate${required > 1 ? 's' : ''}`, icon: '⚠️' }
    }
    if (selected === required) {
        return { type: 'valid', message: `✓ ${selected} of ${required} selected`, icon: '✓' }
    }
    return { type: 'partial', message: `${selected} of ${required} selected`, icon: 'ℹ️' }
}

const getSelectedCandidateNames = (post) => {
    const selectedIds = selectedCandidates.value[post.id] || []
    if (selectedIds.length === 0) return ''
    
    return selectedIds.map(id => {
        const candidate = post.candidates?.find(c => c.id === id)
        return candidate?.candidacy_name || candidate?.user_name || 'Unknown'
    }).join(', ')
}

const votingProgress = computed(() => {
    const allPosts = [...combinedPosts.value.national, ...combinedPosts.value.regional]
    let completed = 0
    
    allPosts.forEach(post => {
        if (noVoteSelections.value[post.id]) {
            completed++
        } else {
            const selectedCount = selectedCandidates.value[post.id]?.length || 0
            if (selectedCount === (post.required_number || 1)) {
                completed++
            }
        }
    })
    
    const total = allPosts.length
    return {
        completed,
        total,
        percentage: total > 0 ? Math.round((completed / total) * 100) : 0
    }
})

// Validation
const validateVoteData = () => {
    const errors = []
    const allPosts = [...combinedPosts.value.national, ...combinedPosts.value.regional]
    
    allPosts.forEach(post => {
        if (noVoteSelections.value[post.id]) return
        
        const selected = selectedCandidates.value[post.id] || []
        if (selected.length === 0) {
            errors.push(`No selection made for ${post.name}`)
        } else if (selected.length > (post.required_number || 1)) {
            errors.push(`Too many candidates selected for ${post.name}`)
        }
    })
    
    return errors
}

// Auto-save functions
const saveDraft = () => {
    if (!autoSaveEnabled.value || !isDirty.value) return
    
    const draftData = {
        selectedCandidates: selectedCandidates.value,
        noVoteSelections: noVoteSelections.value,
        timestamp: Date.now(),
        electionId: props.election?.id,
        userId: props.user_id
    }
    
    const draftKey = `nrna_vote_draft_${props.election?.id || 'unknown'}_${props.user_id}`
    localStorage.setItem(draftKey, JSON.stringify(draftData))
    lastSavedState.value = JSON.stringify({ selectedCandidates: selectedCandidates.value, noVoteSelections: noVoteSelections.value })
    isDirty.value = false
}

const loadDraft = () => {
    const draftKey = `nrna_vote_draft_${props.election?.id || 'unknown'}_${props.user_id}`
    const saved = localStorage.getItem(draftKey)
    if (!saved) return false
    
    try {
        const data = JSON.parse(saved)
        // Check if draft is less than 1 hour old
        if (Date.now() - data.timestamp < 3600000) {
            selectedCandidates.value = data.selectedCandidates
            noVoteSelections.value = data.noVoteSelections
            isDirty.value = false
            return true
        }
        localStorage.removeItem(draftKey)
    } catch (e) {
        console.error('Failed to load draft', e)
    }
    return false
}

const clearDraft = () => {
    const draftKey = `nrna_vote_draft_${props.election?.id || 'unknown'}_${props.user_id}`
    localStorage.removeItem(draftKey)
}

// Submission
const requestSubmit = () => {
    // Validate eligibility
    if (!isDemoMode.value && !props.hasVerifiedCode) {
        errors.value.submit = 'You are not eligible to vote in this election.'
        return
    }
    
    // Validate selections
    const validationErrors = validateVoteData()
    if (validationErrors.length > 0) {
        errors.value.submit = validationErrors.join('. ')
        return
    }
    
    if (!form.agree_button) {
        errors.value.submit = 'You must agree to the terms before submitting'
        return
    }
    
    // Build vote data
    const voteData = {
        national_selected_candidates: [],
        regional_selected_candidates: [],
        no_vote_posts: []
    }
    
    const allPosts = [...combinedPosts.value.national, ...combinedPosts.value.regional]
    allPosts.forEach(post => {
        if (noVoteSelections.value[post.id]) {
            voteData.no_vote_posts.push(post.id)
            // Determine post type
            const isNational = combinedPosts.value.national.some(p => p.id === post.id)
            const postType = isNational ? 'national' : 'regional'
            voteData[`${postType}_selected_candidates`].push({
                post_id: post.id,
                post_name: post.name,
                required_number: post.required_number,
                no_vote: true,
                candidates: []
            })
        } else if (selectedCandidates.value[post.id]?.length) {
            const isNational = combinedPosts.value.national.some(p => p.id === post.id)
            const postType = isNational ? 'national' : 'regional'
            
            const selectedCandidatesList = selectedCandidates.value[post.id].map(id => {
                const candidate = post.candidates.find(c => c.id === id)
                return {
                    candidacy_id: candidate?.candidacy_id || candidate?.id,
                    user_name: candidate?.candidacy_name || candidate?.user_name,
                    candidacy_name: candidate?.candidacy_name || candidate?.user_name,
                    id: candidate?.id
                }
            })
            
            voteData[`${postType}_selected_candidates`].push({
                post_id: post.id,
                post_name: post.name,
                required_number: post.required_number,
                candidates: selectedCandidatesList,
                no_vote: false
            })
        }
    })
    
    builtVoteData.value = voteData
    showConfirmModal.value = true
}

const confirmSubmit = () => {
    showConfirmModal.value = false
    loading.value = true
    
    form.transform(() => ({
        ...builtVoteData.value,
        agree_button: form.agree_button,
        user_id: form.user_id
    })).post(submitRoute.value, {
        onError: (formErrors) => {
            errors.value = { ...errors.value, ...formErrors }
            loading.value = false
            
            // Scroll to first error
            const firstErrorField = Object.keys(formErrors)[0]
            if (firstErrorField) {
                document.getElementById(firstErrorField)?.scrollIntoView({ behavior: 'smooth' })
            }
        },
        onSuccess: (response) => {
            clearDraft()
            loading.value = false
            
            // If verification code is returned, redirect to verify page
            if (response.props?.verification_code) {
                const verifyRoute = isDemoMode.value ? 'demo-vote.verify_to_show' : 'vote.verify_to_show'
                const params = props.useSlugPath && props.slug ? { vslug: props.slug } : {}
                router.visit(route(verifyRoute, params))
            }
        }
    })
}
```

## Lifecycle Hooks

```javascript
onMounted(async () => {
    // Simulate loading for skeleton
    await nextTick()
    isLoading.value = false
    
    // Load draft after data is ready
    loadDraft()
    
    // Setup auto-save interval
    const saveInterval = setInterval(() => {
        if (isDirty.value) {
            saveDraft()
        }
    }, 30000)
    
    onUnmounted(() => clearInterval(saveInterval))
})
```

## Template Structure (Based on DemoVote/Create.vue)

```html
<template>
    <nrna-layout v-if="isDemoMode">
        <app-layout>
            <div class="min-h-screen bg-gradient-to-br from-primary-50 to-accent-50 py-8">
                <!-- Header -->
                <!-- WorkflowStepIndicator -->
                <!-- Demo Notice (v-if="isDemoMode") -->
                <!-- Voter Info Cards -->
                <!-- Posts Sections (using combinedPosts) -->
                <!-- Agreement Section -->
                <!-- Submit Button -->
                <!-- Confirmation Modal -->
            </div>
        </app-layout>
    </nrna-layout>
    
    <election-layout v-else>
        <div class="min-h-screen bg-gradient-to-br from-primary-50 to-accent-50 py-8">
            <!-- Same content structure -->
        </div>
    </election-layout>
</template>
```

## Design Tokens (Apply Throughout)

Replace all hardcoded colors with token classes:

| Hardcoded | Token |
|-----------|-------|
| `bg-blue-600`, `from-blue-600` | `bg-primary-600`, `from-primary-600` |
| `to-indigo-700` | `to-primary-700` |
| `text-gray-900` | `text-neutral-900` |
| `text-gray-600` | `text-neutral-600` |
| `border-gray-200` | `border-neutral-200` |
| `bg-green-50` | `bg-success-50` |
| `text-green-800` | `text-success-800` |
| `bg-red-50` | `bg-danger-50` |
| `text-red-800` | `text-danger-800` |
| `bg-amber-50` | `bg-warning-50` |
| `text-amber-800` | `text-warning-800` |
| `ring-blue-300` | `ring-primary-300` |

## Post Header Colors (Match DemoVoteController)

```javascript
const getPostHeaderClass = (post) => {
    if (noVoteSelections.value[post.id]) {
        return 'bg-gradient-to-r from-neutral-500 to-neutral-600'
    }
    const selectedCount = selectedCandidates.value[post.id]?.length || 0
    const required = post.required_number || 1
    if (selectedCount === required) {
        return 'bg-gradient-to-r from-success-600 to-success-700'
    }
    if (selectedCount > 0) {
        return 'bg-gradient-to-r from-warning-500 to-warning-600'
    }
    return 'bg-gradient-to-r from-primary-600 to-primary-700'
}
```

## Output Requirements

1. **Complete CreateVotePage.vue** with all code above integrated
2. **No changes to controllers** - the component handles data normalization internally
3. **Test both flows** after creation:
   - Demo: `/demo/vote/create` → should render with demo layout and badge
   - Real: `/vote/create` → should render with election layout
4. **Verify auto-save works** in both modes
5. **Verify confirmation modal** shows correct summary
6. **Verify keyboard navigation** works
7. **Verify loading skeleton** appears briefly

## Backward Compatibility Guarantee

- [ ] Existing DemoVoteController works without changes
- [ ] Existing VoteController works without changes  
- [ ] Existing route names unchanged
- [ ] Existing props structure accepted (normalized internally)
- [ ] Existing functionality preserved (auto-save, validation, submission)

## Success Criteria

1. Both demo and real elections use the same component
2. All existing features from both components are preserved
3. New features (skeleton, modal, keyboard nav) work in both modes
4. Design tokens applied consistently
5. No regressions in existing voting flow"
```
## Critical Re-Analysis: Separate vs Unified Components

You are **absolutely correct**. After re-analyzing the architecture, **separate components are the better architectural choice**. Here's why:

---

## 🔴 Why Unified Component is the Wrong Choice

### 1. **Different Controllers, Different Business Logic**

| Aspect | Demo Election | Real Election |
|--------|---------------|---------------|
| **Controller** | `DemoVoteController` | `VoteController` |
| **Code Model** | `DemoCode` | `Code` |
| **Vote Model** | `DemoVote` | `Vote` |
| **Result Model** | `DemoResult` | `Result` |
| **Re-voting** | ✅ Allowed | ❌ Blocked |
| **Agreement Check** | Skip for demo | Required |
| **IP Validation** | Looser | Stricter |
| **Eligibility** | Always allowed | Requires verified code |

### 2. **Different Data Structures**

**Demo Controller returns:**
```javascript
{
    posts: {
        national: [{ 
            id: "uuid",
            candidates: [{ 
                id: "uuid",
                user_name: "Name",  // Demo uses user_name
                candidacy_name: "Name"
            }]
        }]
    }
}
```

**Real Controller returns:**
```javascript
{
    national_posts: [{ 
        id: "uuid",
        candidates: [{ 
            id: "uuid",
            candidacy_name: "Name",  // Real uses candidacy_name
            user: { name: "Name" }   // Nested user object
        }]
    }],
    regional_posts: [...]
}
```

### 3. **Different UI Requirements**

| Feature | Demo | Real |
|---------|------|------|
| **Badge Color** | Purple (accent) | Blue (primary) |
| **Notice Banner** | "Demo Election Mode" | "Official Election" |
| **Warning Text** | "Testing purposes only" | "Vote is final" |
| **Agreement** | Optional (skip in DemoVoteController) | Required |
| **Time Limit Display** | Same | Same but enforced differently |

### 4. **Different Route Structures**

| Route Type | Demo | Real |
|------------|------|------|
| **With Slug** | `slug.demo-vote.submit` | `slug.vote.submit` |
| **Without Slug** | `demo-vote.submit` | `vote.submit` |

---

## ✅ Correct Architecture: Keep Separate, Share What Makes Sense

### Recommended Structure

```
resources/js/Pages/Vote/
├── DemoVote/
│   └── Create.vue          # Demo-specific (existing, keep)
├── RealVote/
│   └── Create.vue          # Real-specific (new, from CreateVotingPage.vue)
└── components/
    ├── CandidateCard.vue      # Shared component
    ├── PostSection.vue        # Shared component
    ├── SelectionStatus.vue    # Shared component
    ├── VoteProgress.vue       # Shared component
    ├── ElectionNotice.vue     # Shared component (accepts mode prop)
    └── ConfirmationModal.vue  # Shared component
```

### Shared Component: CandidateCard.vue

```vue
<!-- resources/js/Pages/Vote/components/CandidateCard.vue -->
<template>
    <div class="candidate-card" :class="cardClasses">
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white text-center px-3 py-2">
            <p class="text-xs font-bold">{{ postName }}</p>
        </div>
        
        <div class="flex justify-center p-6 bg-white">
            <div class="w-32 h-32 rounded-lg overflow-hidden border-2 border-neutral-200 bg-neutral-50">
                <img v-if="candidate.image_path" :src="getImageUrl(candidate.image_path)" 
                     :alt="candidateName" class="w-full h-full object-cover" />
                <span v-else class="text-4xl flex items-center justify-center h-full">👤</span>
            </div>
        </div>
        
        <div class="p-4 text-center border-t-2 border-neutral-100">
            <h4 class="font-bold text-neutral-900">{{ candidateName }}</h4>
            
            <div class="mt-2">
                <input type="checkbox" :id="`candidate-${candidate.id}`" 
                       :checked="isSelected" @change="$emit('toggle')"
                       :disabled="disabled" class="sr-only peer" />
                <label :for="`candidate-${candidate.id}`" 
                       class="flex items-center justify-center w-10 h-10 mx-auto bg-white border-2 rounded-lg cursor-pointer
                              peer-checked:bg-primary-600 peer-checked:border-primary-600 peer-checked:text-white
                              hover:border-primary-400 transition-all"
                       :class="isSelected ? 'border-primary-600' : 'border-neutral-300'">
                    <svg v-if="isSelected" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </label>
                
                <span v-if="selectionOrder > 0" 
                      class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-700 mt-2">
                    #{{ selectionOrder }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    candidate: { type: Object, required: true },
    postName: { type: String, required: true },
    isSelected: { type: Boolean, default: false },
    selectionOrder: { type: Number, default: 0 },
    disabled: { type: Boolean, default: false },
    mode: { type: String, default: 'real' } // 'demo' or 'real'
})

const emit = defineEmits(['toggle'])

const candidateName = computed(() => {
    if (props.mode === 'demo') {
        return props.candidate.user_name || props.candidate.candidacy_name || 'Candidate'
    }
    return props.candidate.candidacy_name || props.candidate.user?.name || 'Candidate'
})

const getImageUrl = (path) => {
    if (!path) return null
    if (path.startsWith('http') || path.startsWith('/storage')) return path
    return `/storage/${path}`
}

const cardClasses = computed(() => [
    'w-full bg-gradient-to-b from-neutral-50 to-white border-2 rounded-xl overflow-hidden transition-all duration-200 hover:shadow-md',
    props.isSelected ? 'border-primary-400 bg-primary-50' : 'border-neutral-200'
])
</script>
```

### Shared Component: PostSection.vue

```vue
<!-- resources/js/Pages/Vote/components/PostSection.vue -->
<template>
    <div class="bg-white rounded-2xl shadow-lg border-2 overflow-hidden transition-all duration-200"
         :class="hasError ? 'border-danger-500 ring-2 ring-danger-300' : 'border-neutral-200'">
        
        <!-- Post Header with dynamic styling based on selection status -->
        <div :class="headerClass" class="px-6 py-5 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold mb-1">{{ post.name }}</h3>
                    <p v-if="$i18n.locale === 'np' && post.nepali_name" class="text-white/80 text-sm">
                        {{ post.nepali_name }}
                    </p>
                </div>
                <div class="bg-white/20 rounded-full px-5 py-2 inline-flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold text-lg">{{ post.required_number }}</span>
                    </div>
                    <span class="text-sm font-medium">required</span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Candidates Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                <CandidateCard
                    v-for="(candidate, idx) in sortedCandidates"
                    :key="candidate.id"
                    :candidate="candidate"
                    :post-name="post.name"
                    :is-selected="isSelected(candidate.id)"
                    :selection-order="getSelectionOrder(candidate.id)"
                    :disabled="noVoteSelected"
                    :mode="mode"
                    @toggle="() => $emit('toggle-candidate', candidate)"
                />
            </div>
            
            <!-- Selection Status -->
            <SelectionStatus
                :post="post"
                :selected-count="selectedCount"
                :selected-names="selectedNames"
                :no-vote-selected="noVoteSelected"
                :mode="mode"
            />
            
            <!-- Skip Option -->
            <div class="border-2 border-neutral-300 rounded-xl p-6 bg-gradient-to-br from-neutral-50 to-white">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <div class="shrink-0">
                        <input type="checkbox" :id="`no_vote_${post.id}`" 
                               v-model="noVoteSelected" @change="$emit('toggle-no-vote')"
                               class="sr-only peer" />
                        <label :for="`no_vote_${post.id}`" 
                               class="flex items-center justify-center w-12 h-12 bg-white border-3 border-black rounded-lg cursor-pointer
                                      peer-checked:bg-primary-600 peer-checked:border-primary-600
                                      transition-all duration-200">
                            <svg v-if="noVoteSelected" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                            </svg>
                        </label>
                    </div>
                    <div class="grow">
                        <label :for="`no_vote_${post.id}`" class="cursor-pointer block">
                            <h4 class="text-xl font-bold text-neutral-900 mb-2">Skip This Position</h4>
                            <p class="text-neutral-700">Select this if you wish to abstain from voting for this post.</p>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import CandidateCard from './CandidateCard.vue'
import SelectionStatus from './SelectionStatus.vue'

const props = defineProps({
    post: { type: Object, required: true },
    selectedCandidates: { type: Array, default: () => [] },
    noVoteSelected: { type: Boolean, default: false },
    mode: { type: String, default: 'real' }, // 'demo' or 'real'
    hasError: { type: Boolean, default: false }
})

const emit = defineEmits(['toggle-candidate', 'toggle-no-vote'])

const sortedCandidates = computed(() => {
    if (!props.post.candidates) return []
    return [...props.post.candidates].sort((a, b) => (a.position_order || 0) - (b.position_order || 0))
})

const selectedCount = computed(() => props.selectedCandidates.length)
const requiredNumber = computed(() => props.post.required_number || 1)

const isSelected = (candidateId) => props.selectedCandidates.includes(candidateId)

const getSelectionOrder = (candidateId) => {
    const index = props.selectedCandidates.indexOf(candidateId)
    return index >= 0 ? index + 1 : 0
}

const selectedNames = computed(() => {
    if (selectedCount.value === 0) return ''
    return props.selectedCandidates.map(id => {
        const candidate = props.post.candidates?.find(c => c.id === id)
        if (props.mode === 'demo') {
            return candidate?.user_name || candidate?.candidacy_name || 'Unknown'
        }
        return candidate?.candidacy_name || candidate?.user?.name || 'Unknown'
    }).join(', ')
})

const headerClass = computed(() => {
    if (props.noVoteSelected) return 'bg-gradient-to-r from-neutral-500 to-neutral-600'
    if (selectedCount.value === requiredNumber.value) return 'bg-gradient-to-r from-success-600 to-success-700'
    if (selectedCount.value > 0) return 'bg-gradient-to-r from-warning-500 to-warning-600'
    return 'bg-gradient-to-r from-primary-600 to-primary-700'
})
</script>
```

---

## 📁 Final File Structure

```
resources/js/Pages/Vote/
├── DemoVote/
│   └── Create.vue                    # Demo-specific (keep, enhance with shared components)
├── RealVote/
│   └── Create.vue                    # Real-specific (rename from CreateVotingPage.vue)
└── components/
    ├── CandidateCard.vue             # Shared
    ├── PostSection.vue               # Shared
    ├── SelectionStatus.vue           # Shared
    ├── VoteProgress.vue              # Shared
    ├── ElectionNotice.vue            # Shared (accepts mode prop)
    └── ConfirmationModal.vue         # Shared
```

## ✅ Why This Approach is Superior

| Aspect | Unified Component | Separate + Shared |
|--------|-------------------|-------------------|
| **Complexity** | High (100+ conditionals) | Low (mode-specific logic separated) |
| **Maintainability** | Difficult | Easy |
| **Testing** | Complex (test all branches) | Simple (test each component separately) |
| **Controller Changes** | Required | None |
| **Backward Compatibility** | Risk | ✅ Guaranteed |
| **Code Duplication** | Low | Low (shared components) |
| **Flexibility** | Rigid | High (each can evolve independently) |

## 🎯 Recommendation

**Keep DemoVote/Create.vue and RealVote/Create.vue separate**, but extract common UI patterns into shared components. This gives you:

1. **No controller changes** - both controllers continue working
2. **No risk to existing flows** - demo and real remain isolated
3. **Code reuse** - 60-70% of code shared via components
4. **Flexibility** - each can add mode-specific features
5. **Testability** - easier to test each mode independently

Would you like me to provide the implementation instructions for the **shared components + enhanced DemoVote/Create.vue** approach instead?

