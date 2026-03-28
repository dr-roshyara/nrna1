# Claude Code Instructions: Enhance Real Election Create.vue Page

```bash
claude code "Enhance the real election voting form (CreateVotingPage.vue) with the superior UX/accessibility features from DemoVote/Create.vue, while preserving all real election-specific logic.

## Context
- **Target File:** `resources/js/Pages/Vote/CreateVotingPage.vue` (current real election form)
- **Reference File:** `resources/js/Pages/Vote/DemoVote/Create.vue` (has superior UX/accessibility)
- **Goal:** Enhance real election form with better UX, but keep real election rules (no re-voting, strict validation)
- **No changes to:** DemoVote/Create.vue, DemoVoteController, VoteController logic

## Current Real Election Form Issues
1. Uses child component `CreateVotingform` instead of inline candidate rendering
2. Missing real-time validation feedback per post
3. No selection order badges
4. No visual progress bar per post
5. No loading skeleton
6. No confirmation modal before submission
7. No auto-save draft
8. Missing regional empty state handling
9. No keyboard shortcuts for accessibility
10. Hardcoded colors (needs design tokens)

## Files to Read
- `resources/js/Pages/Vote/DemoVote/Create.vue` - UX reference
- `resources/js/Pages/Vote/CreateVotingPage.vue` - Current real election form
- `resources/js/Pages/Vote/components/SelectionStatus.vue` - Will need to create
- `resources/css/tokens.css` - Design tokens

## File to Modify
`resources/js/Pages/Vote/CreateVotingPage.vue`

## Requirements

### 1. Keep Existing Real Election Rules (CRITICAL)
- ✅ Must work with `VoteController` (not DemoVoteController)
- ✅ Must submit to real election routes (`vote.submit`, `slug.vote.submit`)
- ✅ Must maintain all existing validation logic
- ✅ Must preserve anonymity (no user_id in vote submission)
- ✅ Must respect `select_all_required` config
- ✅ Must block re-voting (handled by controller)

### 2. Add Missing UX Features from DemoVote/Create.vue

#### a. Inline Candidate Rendering (Replace CreateVotingform)
Replace the child component with direct inline rendering of candidate cards (DemoVote pattern). This gives better control over styling and interactions.

#### b. Selection Order Badges
For multi-candidate posts, show selection order (#1, #2, #3) on selected candidates.

#### c. Real-time Validation Per Post
Show colored status indicators per post:
- Green border + checkmark when selection meets requirements
- Yellow when partially complete
- Red when empty
- Gray when skipped

#### d. Visual Progress Bar Per Post
Add a progress bar showing selection completion percentage per post.

#### e. Post Header Color Changes
Change post header gradient based on selection status:
- Green gradient when complete
- Yellow gradient when partial
- Gray gradient when skipped
- Default blue gradient when empty

#### f. Loading Skeleton
Show animated skeleton cards while data loads (first render only, ~1 frame).

#### g. Confirmation Modal
Show modal with vote summary before final submission. User must confirm.

#### h. Auto-save Draft (from CreateVotingPage.vue)
Preserve existing auto-save functionality but enhance with:
- Debounced save (1 second after last change)
- Dirty tracking (only save when changed)
- Clear draft on successful submission
- Load draft on page load if less than 1 hour old

#### i. Regional Empty State
Show friendly message when user has region but no regional candidates:
```html
<div class="bg-warning-50 border-2 border-warning-200 rounded-xl p-8 text-center mb-12">
    <span class="text-4xl mb-4 block">⚠️</span>
    <h3 class="text-2xl font-bold text-warning-800 mb-2">No Regional Candidates</h3>
    <p class="text-warning-700">There are currently no candidates available for your region {{ user_region }}.</p>
</div>
```

#### j. Keyboard Shortcuts
- Arrow keys to navigate between candidate cards
- Spacebar to toggle selection on focused card
- Enter to confirm and open modal (when all selections complete)

### 3. Apply Design Tokens
Replace all hardcoded Tailwind colors with token classes:

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

### 4. Component Structure

```vue
<template>
    <election-layout>
        <!-- Accessibility: Skip link -->
        <a href="#main-content" class="skip-link">Skip to main content</a>
        
        <div class="min-h-screen bg-gradient-to-br from-primary-50 to-accent-50 py-8">
            <div id="main-content" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" tabindex="-1">
                
                <!-- Header -->
                <header class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-neutral-900 mb-4">
                        {{ $t('pages.voting.header.title') }}
                    </h1>
                    <p class="text-xl text-neutral-600 mb-4">
                        {{ $t('pages.voting.header.subtitle', { name: user_name }) }}
                    </p>
                    <div class="w-24 h-1 bg-primary-600 mx-auto rounded-full"></div>
                </header>
                
                <!-- Workflow Step Indicator -->
                <WorkflowStepIndicator :currentStep="3" />
                
                <!-- Voter Info Cards (with tokens) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
                    <!-- Voter Card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-primary-100">
                        <div class="flex items-center gap-4">
                            <div class="bg-primary-50 p-3 rounded-lg">
                                <span class="text-primary-600 text-2xl">👤</span>
                            </div>
                            <div>
                                <p class="text-sm text-neutral-500 font-medium uppercase tracking-wide">Voter</p>
                                <p class="font-bold text-primary-800 text-lg">{{ user_name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-accent-100">
                        <div class="flex items-center gap-4">
                            <div class="bg-accent-50 p-3 rounded-lg">
                                <span class="text-accent-600 text-2xl">📊</span>
                            </div>
                            <div>
                                <p class="text-sm text-neutral-500 font-medium uppercase tracking-wide">Progress</p>
                                <p class="font-bold text-accent-700 text-lg">{{ votingProgress.completed }}/{{ votingProgress.total }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Election Card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-primary-100">
                        <div class="flex items-center gap-4">
                            <div class="bg-primary-50 p-3 rounded-lg">
                                <span class="text-primary-600 text-2xl">📋</span>
                            </div>
                            <div>
                                <p class="text-sm text-neutral-500 font-medium uppercase tracking-wide">Election</p>
                                <p class="font-bold text-primary-800 text-lg">{{ election?.name || 'Real Election' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loading Skeleton -->
                <div v-if="isLoading" class="space-y-8">
                    <div v-for="i in 3" :key="i" class="animate-pulse">
                        <div class="h-24 bg-neutral-200 rounded-t-xl"></div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-white rounded-b-xl">
                            <div v-for="j in 3" :key="j" class="h-48 bg-neutral-200 rounded-xl"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Voting Form -->
                <form v-else @submit.prevent="requestSubmit" class="space-y-8">
                    <!-- National Posts Section -->
                    <section v-if="normalizedNationalPosts.length" class="mb-12">
                        <h2 class="text-3xl font-bold text-neutral-900 text-center mb-8">
                            National Posts
                        </h2>
                        <div class="space-y-8">
                            <PostSection
                                v-for="(post, index) in normalizedNationalPosts"
                                :key="post.id"
                                :post="post"
                                :selected-candidates="selectedCandidates[post.id] || []"
                                :no-vote-selected="noVoteSelections[post.id] || false"
                                :has-error="postErrors[post.id]"
                                mode="real"
                                @toggle-candidate="(candidate) => toggleCandidate(post, candidate)"
                                @toggle-no-vote="() => toggleNoVote(post)"
                            />
                        </div>
                    </section>
                    
                    <!-- Regional Posts Section -->
                    <section v-if="normalizedRegionalPosts.length" class="mb-12">
                        <h2 class="text-3xl font-bold text-neutral-900 text-center mb-8">
                            Regional Posts - {{ user_region }}
                        </h2>
                        <div class="space-y-8">
                            <PostSection
                                v-for="(post, index) in normalizedRegionalPosts"
                                :key="post.id"
                                :post="post"
                                :selected-candidates="selectedCandidates[post.id] || []"
                                :no-vote-selected="noVoteSelections[post.id] || false"
                                :has-error="postErrors[post.id]"
                                mode="real"
                                @toggle-candidate="(candidate) => toggleCandidate(post, candidate)"
                                @toggle-no-vote="() => toggleNoVote(post)"
                            />
                        </div>
                    </section>
                    
                    <!-- Regional Empty State -->
                    <div v-if="hasRegionButNoPosts" class="bg-warning-50 border-2 border-warning-200 rounded-xl p-8 text-center mb-12">
                        <span class="text-4xl mb-4 block">⚠️</span>
                        <h3 class="text-2xl font-bold text-warning-800 mb-2">No Regional Candidates</h3>
                        <p class="text-warning-700">There are currently no candidates available for your region {{ user_region }}.</p>
                        <p class="text-warning-600 text-sm mt-2">You can still vote for national positions.</p>
                    </div>
                    
                    <!-- Agreement Section -->
                    <div class="bg-white rounded-xl shadow-lg border border-neutral-200 p-8 max-w-4xl mx-auto">
                        <div class="border-2 border-primary-200 rounded-lg p-6 bg-primary-50">
                            <div class="flex flex-col items-center">
                                <div class="text-3xl mb-2">✅</div>
                                <h3 class="text-xl font-bold text-primary-800 mb-4">Voting Agreement</h3>
                            </div>
                            
                            <div class="flex justify-center mb-4">
                                <label class="flex items-center cursor-pointer gap-3">
                                    <input type="checkbox" v-model="form.agree_button"
                                           class="w-10 h-10 text-primary-600 border-3 border-neutral-600 rounded 
                                                  focus:ring-4 focus:ring-primary-300 focus:border-primary-500" />
                                    <span class="text-lg font-medium text-neutral-900">
                                        I confirm my selections are correct
                                    </span>
                                </label>
                            </div>
                            
                            <p class="text-sm text-neutral-600 text-center">
                                By agreeing, you confirm your selections are correct and follow voting rules.
                                Your vote is final and cannot be changed.
                            </p>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-center mt-6">
                            <button type="submit"
                                    :disabled="!form.agree_button || loading"
                                    class="w-full max-w-md py-5 px-8 rounded-xl font-bold text-xl transition-all duration-200 shadow-md
                                           focus:outline-none focus:ring-4 focus:ring-offset-2"
                                    :class="canSubmit ? 'bg-primary-600 hover:bg-primary-700 text-white focus:ring-primary-300 cursor-pointer' 
                                                   : 'bg-neutral-300 text-neutral-500 cursor-not-allowed'">
                                <span class="flex items-center justify-center gap-2">
                                    <span class="text-2xl">🗳️</span>
                                    <span>{{ loading ? 'Submitting...' : 'Review & Submit' }}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Confirmation Modal -->
                <ConfirmationModal
                    :show="showConfirmModal"
                    :vote-data="builtVoteData"
                    :user-name="user_name"
                    @confirm="confirmSubmit"
                    @cancel="showConfirmModal = false"
                />
                
                <!-- Spacer for sticky elements -->
                <div class="h-8"></div>
            </div>
        </div>
    </election-layout>
</template>
```

### 5. Script Structure

```javascript
<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator.vue'
import PostSection from './components/PostSection.vue'
import ConfirmationModal from './components/ConfirmationModal.vue'

// Props (existing from VoteController)
const props = defineProps({
    national_posts: { type: Array, default: () => [] },
    regional_posts: { type: Array, default: () => [] },
    user_name: { type: String, required: true },
    user_id: { type: Number, required: true },
    user_region: { type: String, default: '' },
    slug: { type: String, default: null },
    useSlugPath: { type: Boolean, default: false },
    election: { type: Object, default: null },
})

// Normalize posts (ensure consistent id field)
const normalizedNationalPosts = computed(() => {
    return props.national_posts.map(post => ({
        ...post,
        id: post.id ?? post.post_id,
        candidates: (post.candidates || []).map(c => ({
            ...c,
            id: c.id ?? c.candidacy_id,
            candidacy_id: c.candidacy_id ?? c.id,
            candidacy_name: c.candidacy_name || c.user?.name,
            user_name: c.user?.name || c.candidacy_name
        }))
    }))
})

const normalizedRegionalPosts = computed(() => {
    return (props.regional_posts || []).map(post => ({
        ...post,
        id: post.id ?? post.post_id,
        candidates: (post.candidates || []).map(c => ({
            ...c,
            id: c.id ?? c.candidacy_id,
            candidacy_id: c.candidacy_id ?? c.id,
            candidacy_name: c.candidacy_name || c.user?.name,
            user_name: c.user?.name || c.candidacy_name
        }))
    }))
})

// State
const selectedCandidates = ref({})
const noVoteSelections = ref({})
const postErrors = ref({})
const loading = ref(false)
const isLoading = ref(true)
const showConfirmModal = ref(false)
const builtVoteData = ref(null)
const isDirty = ref(false)
let autoSaveInterval = null

// Form
const form = useForm({
    user_id: props.user_id,
    agree_button: false,
})

// Computed
const hasRegionButNoPosts = computed(() => {
    return props.user_region && 
           normalizedRegionalPosts.value.length === 0 && 
           !isLoading.value
})

const allPosts = computed(() => [
    ...normalizedNationalPosts.value,
    ...normalizedRegionalPosts.value
])

const votingProgress = computed(() => {
    let completed = 0
    allPosts.value.forEach(post => {
        if (noVoteSelections.value[post.id]) {
            completed++
        } else {
            const selected = selectedCandidates.value[post.id]?.length || 0
            if (selected === (post.required_number || 1)) {
                completed++
            }
        }
    })
    return {
        completed,
        total: allPosts.value.length,
        percentage: allPosts.value.length ? Math.round((completed / allPosts.value.length) * 100) : 0
    }
})

const canSubmit = computed(() => {
    if (loading.value) return false
    if (!form.agree_button) return false
    if (votingProgress.value.completed < votingProgress.value.total) return false
    return true
})

// Methods
const toggleCandidate = (post, candidate) => {
    if (noVoteSelections.value[post.id]) return
    
    const current = [...(selectedCandidates.value[post.id] || [])]
    const index = current.indexOf(candidate.id)
    
    if (index === -1) {
        if (current.length < (post.required_number || 1)) {
            current.push(candidate.id)
            selectedCandidates.value[post.id] = current
        } else {
            postErrors.value[post.id] = `You can only select up to ${post.required_number} candidates`
            setTimeout(() => delete postErrors.value[post.id], 3000)
            return
        }
    } else {
        current.splice(index, 1)
        selectedCandidates.value[post.id] = current
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

// Validation
const validateAllPosts = () => {
    const errors = []
    allPosts.value.forEach(post => {
        if (noVoteSelections.value[post.id]) return
        
        const selected = selectedCandidates.value[post.id]?.length || 0
        if (selected === 0) {
            errors.push(`No selection made for ${post.name}`)
        } else if (selected !== (post.required_number || 1)) {
            errors.push(`Please select exactly ${post.required_number} candidate(s) for ${post.name}`)
        }
    })
    return errors
}

// Build vote data
const buildVoteData = () => {
    const voteData = {
        national_selected_candidates: [],
        regional_selected_candidates: [],
        no_vote_posts: []
    }
    
    allPosts.value.forEach(post => {
        const isNational = normalizedNationalPosts.value.some(p => p.id === post.id)
        const postType = isNational ? 'national' : 'regional'
        
        if (noVoteSelections.value[post.id]) {
            voteData.no_vote_posts.push(post.id)
            voteData[`${postType}_selected_candidates`].push({
                post_id: post.id,
                post_name: post.name,
                required_number: post.required_number,
                no_vote: true,
                candidates: []
            })
        } else if (selectedCandidates.value[post.id]?.length) {
            const selectedCandidatesList = selectedCandidates.value[post.id].map(id => {
                const candidate = post.candidates.find(c => c.id === id)
                return {
                    candidacy_id: candidate?.candidacy_id || candidate?.id,
                    user_name: candidate?.user?.name || candidate?.candidacy_name,
                    candidacy_name: candidate?.candidacy_name || candidate?.user?.name
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
    
    return voteData
}

// Submit flow
const requestSubmit = () => {
    // Validate all posts
    const validationErrors = validateAllPosts()
    if (validationErrors.length > 0) {
        postErrors.value = {}
        validationErrors.forEach(error => {
            const post = allPosts.value.find(p => error.includes(p.name))
            if (post) postErrors.value[post.id] = error
        })
        return
    }
    
    if (!form.agree_button) {
        alert('Please agree to the terms before submitting')
        return
    }
    
    builtVoteData.value = buildVoteData()
    showConfirmModal.value = true
}

const confirmSubmit = () => {
    showConfirmModal.value = false
    loading.value = true
    
    const submitRoute = props.useSlugPath && props.slug
        ? route('slug.vote.submit', { vslug: props.slug })
        : route('vote.submit')
    
    form.transform(() => ({
        ...builtVoteData.value,
        agree_button: form.agree_button,
        user_id: form.user_id
    })).post(submitRoute, {
        onError: (errors) => {
            loading.value = false
            if (errors.vote) {
                alert(errors.vote)
            }
        },
        onSuccess: () => {
            clearDraft()
            loading.value = false
        }
    })
}

// Auto-save
const saveDraft = () => {
    if (!isDirty.value) return
    
    const draftKey = `nrna_vote_draft_${props.election?.id || 'real'}_${props.user_id}`
    const draftData = {
        selectedCandidates: selectedCandidates.value,
        noVoteSelections: noVoteSelections.value,
        timestamp: Date.now()
    }
    localStorage.setItem(draftKey, JSON.stringify(draftData))
    isDirty.value = false
}

const loadDraft = () => {
    const draftKey = `nrna_vote_draft_${props.election?.id || 'real'}_${props.user_id}`
    const saved = localStorage.getItem(draftKey)
    if (!saved) return
    
    try {
        const data = JSON.parse(saved)
        if (Date.now() - data.timestamp < 3600000) {
            selectedCandidates.value = data.selectedCandidates
            noVoteSelections.value = data.noVoteSelections
            isDirty.value = false
        } else {
            localStorage.removeItem(draftKey)
        }
    } catch (e) {
        console.error('Failed to load draft', e)
    }
}

const clearDraft = () => {
    const draftKey = `nrna_vote_draft_${props.election?.id || 'real'}_${props.user_id}`
    localStorage.removeItem(draftKey)
}

// Lifecycle
onMounted(async () => {
    await nextTick()
    isLoading.value = false
    loadDraft()
    
    autoSaveInterval = setInterval(() => {
        if (isDirty.value) saveDraft()
    }, 30000)
})

onUnmounted(() => {
    if (autoSaveInterval) clearInterval(autoSaveInterval)
})
</script>
```

### 6. Create Shared Components

**PostSection.vue** (as defined in previous response)
**SelectionStatus.vue** (simple status display)
**ConfirmationModal.vue** (modal with vote summary)

### 7. CSS for Staggered Animation

```css
<style scoped>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.post-card-enter {
    animation: slideInUp 0.35s ease-out both;
}
</style>
```

### 8. Add to each post card:
```html
:style="{ animationDelay: (postIndex * 80) + 'ms' }"
```

## Output Requirements

1. **Complete CreateVotingPage.vue** with all enhancements
2. **Create PostSection.vue** component
3. **Create SelectionStatus.vue** component  
4. **Create ConfirmationModal.vue** component
5. **No changes to DemoVote/Create.vue**
6. **No changes to VoteController** (keeps working)
7. **All tests pass** (manual testing)

## Verification Checklist

- [ ] Real election form renders with new layout
- [ ] Loading skeleton appears briefly on load
- [ ] Selection order badges appear (#1, #2)
- [ ] Post header changes color based on selection status
- [ ] Progress bars update in real-time
- [ ] Regional empty state appears when applicable
- [ ] Confirmation modal shows before submission
- [ ] Auto-save saves draft every 30 seconds
- [ ] Draft loads on page refresh
- [ ] Draft cleared after successful submission
- [ ] Keyboard navigation works (arrow keys, space)
- [ ] All existing validation rules preserved
- [ ] Submit works with real election routes
- [ ] No regressions in existing voting flow"
```