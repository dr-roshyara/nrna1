<template>
    <election-layout>
        <!-- Accessibility: skip link -->
        <a href="#main-content"
           class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50
                  focus:bg-white focus:px-4 focus:py-2 focus:rounded-lg focus:shadow-lg
                  focus:font-semibold focus:text-primary-700 focus:outline-none">
            {{ $t('pages.voting.aria_labels.skip_to_content') }}
        </a>

        <!-- Live region for screen readers -->
        <div role="status" aria-live="polite" aria-atomic="true" class="sr-only">
            {{ votingProgress.completed }} of {{ votingProgress.total }} positions completed
        </div>

        <div class="min-h-screen bg-gradient-to-br from-primary-50 to-indigo-50 py-8">
            <div id="main-content" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" tabindex="-1">

                <!-- ── Header ── -->
                <header class="text-center mb-12">
                    <div class="inline-flex items-center gap-2 bg-success-50 text-success-700
                                border border-success-200 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        <span aria-hidden="true">✓</span>
                        {{ $t('pages.voting.header.verified_badge') }}
                    </div>
                    <h1 class="text-4xl font-serif text-neutral-900 mb-3">
                        {{ $t('pages.voting.header.title') }}
                    </h1>
                    <p class="text-xl font-sans text-neutral-600 mb-4">
                        {{ $t('pages.voting.header.subtitle', { name: user_name }) }}
                    </p>
                    <div class="w-24 h-1 bg-primary-600 mx-auto rounded-full" aria-hidden="true"></div>
                </header>

                <!-- ── Info Cards ── -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-10">
                    <!-- Voter card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border-2 border-success-200">
                        <div class="flex items-center gap-4">
                            <div class="bg-success-50 p-3 rounded-lg shrink-0">
                                <span class="text-success-600 text-2xl" aria-hidden="true">👤</span>
                            </div>
                            <div class="text-left min-w-0">
                                <p class="text-xs font-sans font-semibold uppercase tracking-wide text-neutral-500">
                                    {{ $t('pages.voting.voter_info.label') }}
                                </p>
                                <p class="font-sans font-bold text-neutral-900 truncate">{{ user_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border-2 border-primary-200">
                        <div class="flex items-center gap-4">
                            <div class="bg-primary-50 p-3 rounded-lg shrink-0">
                                <span class="text-primary-600 text-2xl" aria-hidden="true">📊</span>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-sans font-semibold uppercase tracking-wide text-neutral-500">
                                    {{ $t('pages.voting.voter_info.progress') }}
                                </p>
                                <p class="font-mono font-bold text-primary-700 text-lg">
                                    {{ votingProgress.completed }}/{{ votingProgress.total }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Region card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border-2 border-indigo-200">
                        <div class="flex items-center gap-4">
                            <div class="bg-indigo-50 p-3 rounded-lg shrink-0">
                                <span class="text-indigo-600 text-2xl" aria-hidden="true">📍</span>
                            </div>
                            <div class="text-left min-w-0">
                                <p class="text-xs font-sans font-semibold uppercase tracking-wide text-neutral-500">
                                    {{ $t('pages.voting.voter_info.region') }}
                                </p>
                                <p class="font-sans font-bold text-neutral-900 truncate">
                                    {{ user_region || $t('pages.voting.voter_info.national_only') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Workflow Step Indicator ── -->
                <WorkflowStepIndicator :currentStep="3" class="mb-10 max-w-4xl mx-auto" />

                <!-- ── Election Constraint Hint ── -->
                <div v-if="election" class="constraint-hint mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded max-w-4xl mx-auto">
                    <template v-if="election.selection_constraint_type === 'exact'">
                        ⚠️ You must select exactly {{ election.selection_constraint_max }} candidate(s) per post.
                    </template>
                    <template v-else-if="election.selection_constraint_type === 'minimum'">
                        ⚠️ Select at least {{ election.selection_constraint_min }} candidate(s) per post.
                    </template>
                    <template v-else-if="election.selection_constraint_type === 'range'">
                        ⚠️ Select between {{ election.selection_constraint_min }} and {{ election.selection_constraint_max }} candidate(s) per post.
                    </template>
                    <template v-else-if="election.selection_constraint_type === 'maximum'">
                        Select up to {{ election.selection_constraint_max }} candidate(s) per post.
                    </template>
                </div>

                <!-- ── Loading Skeleton ── -->
                <div v-if="isLoading" class="space-y-8" aria-busy="true" aria-label="Loading voting form">
                    <div v-for="i in 3" :key="i" class="animate-pulse rounded-2xl overflow-hidden shadow-sm">
                        <div class="h-20 bg-neutral-300 rounded-t-2xl"></div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 p-6 bg-white rounded-b-2xl border border-neutral-200">
                            <div v-for="j in 4" :key="j" class="h-44 bg-neutral-100 rounded-xl"></div>
                        </div>
                    </div>
                </div>

                <!-- ── Main Voting Form ── -->
                <form v-else
                      @submit.prevent="requestSubmit"
                      @keydown.right.prevent="moveFocus(1)"
                      @keydown.left.prevent="moveFocus(-1)"
                      @keydown.space="handleSpaceKey"
                      class="space-y-8">

                    <!-- National Posts -->
                    <section v-if="normalizedNationalPosts.length" class="mb-12">
                        <h2 class="text-3xl font-serif text-neutral-900 text-center mb-8">
                            {{ $t('pages.voting.sections.national') }}
                        </h2>
                        <div class="space-y-8">
                            <PostSection
                                v-for="(post, index) in normalizedNationalPosts"
                                :key="post.id"
                                :ref="el => { if (el) postSectionRefs.push(el) }"
                                :post="post"
                                :selected-candidates="selectedCandidates[post.id] || []"
                                :no-vote-selected="noVoteSelections[post.id] || false"
                                :no-vote-enabled="election?.no_vote_option_enabled ?? true"
                                :no-vote-label="election?.no_vote_option_label ?? 'Abstain'"
                                :has-error="!!postErrors[post.id]"
                                :error-message="postErrors[post.id] || ''"
                                :post-index="index"
                                @toggle-candidate="candidate => toggleCandidate(post, candidate)"
                                @toggle-no-vote="() => toggleNoVote(post)"
                            />
                        </div>
                    </section>

                    <!-- Regional Empty State -->
                    <div v-if="hasRegionButNoPosts"
                         class="bg-warning-50 border-2 border-yellow-300 rounded-xl p-8 text-center mb-12">
                        <span class="text-5xl mb-4 block" aria-hidden="true">⚠️</span>
                        <h3 class="text-2xl font-sans font-bold text-yellow-800 mb-2">
                            {{ $t('pages.voting.regional_empty.title') }}
                        </h3>
                        <p class="text-yellow-700 font-sans">
                            {{ $t('pages.voting.regional_empty.message', { region: user_region }) }}
                        </p>
                        <p class="text-yellow-600 text-sm mt-2 font-sans">
                            {{ $t('pages.voting.regional_empty.national_only') }}
                        </p>
                    </div>

                    <!-- Regional Posts -->
                    <section v-if="normalizedRegionalPosts.length" class="mb-12">
                        <h2 class="text-3xl font-serif text-neutral-900 text-center mb-8">
                            {{ $t('pages.voting.sections.regional') }} — {{ user_region }}
                        </h2>
                        <div class="space-y-8">
                            <PostSection
                                v-for="(post, index) in normalizedRegionalPosts"
                                :key="post.id"
                                :ref="el => { if (el) postSectionRefs.push(el) }"
                                :post="post"
                                :selected-candidates="selectedCandidates[post.id] || []"
                                :no-vote-selected="noVoteSelections[post.id] || false"
                                :no-vote-enabled="election?.no_vote_option_enabled ?? true"
                                :no-vote-label="election?.no_vote_option_label ?? 'Abstain'"
                                :has-error="!!postErrors[post.id]"
                                :error-message="postErrors[post.id] || ''"
                                :post-index="normalizedNationalPosts.length + index"
                                @toggle-candidate="candidate => toggleCandidate(post, candidate)"
                                @toggle-no-vote="() => toggleNoVote(post)"
                            />
                        </div>
                    </section>

                    <!-- Live Vote Summary (shown once at least one post is complete) -->
                    <section v-if="votingProgress.completed > 0" class="mb-12 max-w-4xl mx-auto">
                        <VoteSummary
                            :national-selections="liveSummary.national_selected_candidates"
                            :regional-selections="liveSummary.regional_selected_candidates"
                        />
                    </section>

                    <!-- Validation Error List -->
                    <div v-if="Object.keys(postErrors).length"
                         role="alert"
                         class="bg-danger-50 border border-danger-200 rounded-lg p-4 max-w-4xl mx-auto">
                        <p class="font-sans font-semibold text-danger-800 mb-2">
                            {{ $t('pages.voting.errors.validation_title') }}
                        </p>
                        <ul class="list-disc list-inside text-danger-700 font-sans text-sm space-y-1">
                            <li v-for="(msg, id) in postErrors" :key="id">{{ msg }}</li>
                        </ul>
                    </div>

                    <!-- ── Agreement & Submit ── -->
                    <div class="bg-white rounded-xl shadow-lg border border-neutral-200 p-8 max-w-4xl mx-auto">
                        <div class="border-2 border-primary-200 rounded-xl p-6 bg-primary-50 mb-6">
                            <h3 class="text-xl font-serif text-primary-800 text-center mb-5">
                                {{ $t('pages.voting.agreement.title') }}
                            </h3>
                            <div class="flex justify-center mb-4">
                                <label class="flex items-start gap-4 cursor-pointer max-w-sm">
                                    <input
                                        type="checkbox"
                                        v-model="form.agree_button"
                                        class="w-6 h-6 mt-0.5 shrink-0 text-primary-600 rounded
                                               border-2 border-neutral-400
                                               focus:ring-4 focus:ring-primary-300 focus:ring-offset-1
                                               cursor-pointer"
                                    />
                                    <span class="font-sans font-medium text-neutral-900 leading-relaxed">
                                        {{ $t('pages.voting.agreement.checkbox_label') }}
                                    </span>
                                </label>
                            </div>
                            <p class="text-sm font-sans text-neutral-500 text-center">
                                {{ $t('pages.voting.agreement.final_warning') }}
                            </p>
                            <p v-if="form.errors.agree_button"
                               class="text-danger-600 text-sm text-center mt-2 font-sans">
                                {{ form.errors.agree_button }}
                            </p>
                        </div>

                        <!-- Overall progress bar -->
                        <div class="mb-6">
                            <div class="flex justify-between text-xs font-mono text-neutral-500 mb-1">
                                <span>{{ $t('pages.voting.agreement.progress_label') }}</span>
                                <span>{{ votingProgress.completed }}/{{ votingProgress.total }}</span>
                            </div>
                            <div class="w-full bg-neutral-100 rounded-full h-2 overflow-hidden">
                                <div class="h-full rounded-full bg-primary-600 transition-all duration-500"
                                     :style="{ width: votingProgress.percentage + '%' }"></div>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <button
                                type="submit"
                                :disabled="!canSubmit"
                                class="w-full max-w-md py-5 px-8 rounded-xl font-sans font-bold text-xl
                                       shadow-md transition-colors duration-150
                                       focus:outline-none focus:ring-4 focus:ring-offset-2"
                                :class="canSubmit
                                    ? 'bg-primary-600 hover:bg-primary-700 text-white focus:ring-primary-300 cursor-pointer'
                                    : 'bg-neutral-200 text-neutral-400 cursor-not-allowed'"
                            >
                                <span class="flex items-center justify-center gap-2">
                                    <span aria-hidden="true">🗳️</span>
                                    <span>{{ loading
                                        ? $t('pages.voting.submit.submitting')
                                        : $t('pages.voting.submit.review_submit') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>

                </form>

                <!-- ── Footer Info Cards ── -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mt-12">
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-200 text-center">
                        <span class="text-3xl mb-2 block" aria-hidden="true">🔒</span>
                        <p class="font-sans font-semibold text-neutral-800 text-sm">
                            {{ $t('pages.voting.footer.security_title') }}
                        </p>
                        <p class="font-sans text-xs text-neutral-500 mt-1">
                            {{ $t('pages.voting.footer.security_body') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-200 text-center">
                        <span class="text-3xl mb-2 block" aria-hidden="true">🕐</span>
                        <p class="font-sans font-semibold text-neutral-800 text-sm">
                            {{ $t('pages.voting.footer.time_title') }}
                        </p>
                        <p class="font-sans text-xs text-neutral-500 mt-1">
                            {{ $t('pages.voting.footer.time_body') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-200 text-center">
                        <span class="text-3xl mb-2 block" aria-hidden="true">❓</span>
                        <p class="font-sans font-semibold text-neutral-800 text-sm">
                            {{ $t('pages.voting.footer.help_title') }}
                        </p>
                        <p class="font-sans text-xs text-neutral-500 mt-1">
                            {{ $t('pages.voting.footer.help_body') }}
                        </p>
                    </div>
                </div>

                <div class="h-12"></div>
            </div>
        </div>

        <!-- ── Confirmation Modal ── -->
        <ConfirmationModal
            :show="showConfirmModal"
            :vote-data="builtVoteData"
            :user-name="user_name"
            @confirm="confirmSubmit"
            @cancel="showConfirmModal = false"
        />

    </election-layout>
</template>

<script>
import { useForm } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator.vue'
import VoteSummary from '@/Pages/Vote/VoteSummary.vue'
import PostSection from './components/PostSection.vue'
import ConfirmationModal from './components/ConfirmationModal.vue'

export default {
    name: 'CreateVotingPage',

    components: {
        ElectionLayout,
        WorkflowStepIndicator,
        VoteSummary,
        PostSection,
        ConfirmationModal,
    },

    props: {
        national_posts: { type: Array,   default: () => [] },
        regional_posts:  { type: Array,   default: () => [] },
        user_name:       { type: String,  required: true },
        user_id:         { type: Number,  required: true },
        user_region:     { type: String,  default: '' },
        slug:            { type: String,  default: null },
        useSlugPath:     { type: Boolean, default: false },
        election:        { type: Object,  default: null },
    },

    setup(props) {
        const form = useForm({
            user_id:      props.user_id,
            agree_button: false,
        })
        return { form }
    },

    data() {
        return {
            selectedCandidates: {},
            noVoteSelections:   {},
            postErrors:         {},
            loading:            false,
            isLoading:          true,
            showConfirmModal:   false,
            builtVoteData:      null,
            isDirty:            false,
            postSectionRefs:    [],
            currentFocusIndex:  0,
            autoSaveInterval:   null,
        }
    },

    computed: {
        normalizedNationalPosts() {
            return this.national_posts.map(post => ({
                ...post,
                id: post.id ?? post.post_id,
                candidates: (post.candidates || []).map(c => ({
                    ...c,
                    id:             c.id ?? c.candidacy_id,
                    candidacy_id:   c.candidacy_id ?? c.id,
                    candidacy_name: c.candidacy_name || c.user?.name,
                    user_name:      c.user?.name || c.candidacy_name,
                })),
            }))
        },

        normalizedRegionalPosts() {
            return (this.regional_posts || []).map(post => ({
                ...post,
                id: post.id ?? post.post_id,
                candidates: (post.candidates || []).map(c => ({
                    ...c,
                    id:             c.id ?? c.candidacy_id,
                    candidacy_id:   c.candidacy_id ?? c.id,
                    candidacy_name: c.candidacy_name || c.user?.name,
                    user_name:      c.user?.name || c.candidacy_name,
                })),
            }))
        },

        allPosts() {
            return [...this.normalizedNationalPosts, ...this.normalizedRegionalPosts]
        },

        votingProgress() {
            let completed = 0
            this.allPosts.forEach(post => {
                if (this.noVoteSelections[post.id]) {
                    completed++
                } else if ((this.selectedCandidates[post.id]?.length || 0) === (post.required_number || 1)) {
                    completed++
                }
            })
            const total = this.allPosts.length
            return {
                completed,
                total,
                percentage: total ? Math.round((completed / total) * 100) : 0,
            }
        },

        canSubmit() {
            return this.form.agree_button &&
                   !this.loading &&
                   this.votingProgress.completed === this.votingProgress.total
        },

        hasRegionButNoPosts() {
            return !!this.user_region &&
                   this.normalizedRegionalPosts.length === 0 &&
                   !this.isLoading
        },

        submitRoute() {
            return this.useSlugPath && this.slug
                ? route('slug.vote.submit', { vslug: this.slug })
                : route('vote.submit')
        },

        draftKey() {
            return `nrna_vote_draft_${this.election?.id || 'real'}_${this.user_id}`
        },

        liveSummary() {
            return this.buildVoteData()
        },

        allCandidateCards() {
            const cards = []
            this.postSectionRefs.forEach(section => {
                if (section && section.cardRefs) {
                    cards.push(...section.cardRefs)
                }
            })
            return cards
        },
    },

    watch: {
        selectedCandidates: {
            deep: true,
            handler() { this.isDirty = true },
        },
        noVoteSelections: {
            deep: true,
            handler() { this.isDirty = true },
        },
    },

    async mounted() {
        await this.$nextTick()
        this.isLoading = false
        this.loadDraft()
        this.autoSaveInterval = setInterval(() => this.saveDraft(), 30_000)
    },

    beforeUpdate() {
        // Clear ref array before each re-render so stale refs don't accumulate
        this.postSectionRefs = []
    },

    beforeUnmount() {
        if (this.autoSaveInterval) clearInterval(this.autoSaveInterval)
    },

    methods: {
        toggleCandidate(post, candidate) {
            if (this.noVoteSelections[post.id]) return

            const current = [...(this.selectedCandidates[post.id] || [])]
            const index = current.indexOf(candidate.id)

            if (index === -1) {
                if (current.length < (post.required_number || 1)) {
                    current.push(candidate.id)
                    this.selectedCandidates = { ...this.selectedCandidates, [post.id]: current }
                } else {
                    this.postErrors = {
                        ...this.postErrors,
                        [post.id]: `Max ${post.required_number} candidate(s) allowed`,
                    }
                    setTimeout(() => {
                        const errs = { ...this.postErrors }
                        delete errs[post.id]
                        this.postErrors = errs
                    }, 3000)
                    return
                }
            } else {
                current.splice(index, 1)
                this.selectedCandidates = { ...this.selectedCandidates, [post.id]: current }
            }

            this.isDirty = true
        },

        toggleNoVote(post) {
            const next = !this.noVoteSelections[post.id]
            this.noVoteSelections = { ...this.noVoteSelections, [post.id]: next }
            if (next) {
                this.selectedCandidates = { ...this.selectedCandidates, [post.id]: [] }
            }
            this.isDirty = true
        },

        validateAllPosts() {
            const errors = []
            this.allPosts.forEach(post => {
                if (this.noVoteSelections[post.id]) return
                const n   = this.selectedCandidates[post.id]?.length || 0
                const req = post.required_number || 1
                if (n === 0)        errors.push({ postId: post.id, msg: `No selection for: ${post.name}` })
                else if (n !== req) errors.push({ postId: post.id, msg: `Select exactly ${req} candidate(s) for: ${post.name}` })
            })
            return errors
        },

        buildVoteData() {
            const voteData = {
                national_selected_candidates: [],
                regional_selected_candidates: [],
                no_vote_posts: [],
            }

            this.allPosts.forEach(post => {
                const isNational = this.normalizedNationalPosts.some(p => p.id === post.id)
                const key = isNational
                    ? 'national_selected_candidates'
                    : 'regional_selected_candidates'

                if (this.noVoteSelections[post.id]) {
                    voteData.no_vote_posts.push(post.id)
                    voteData[key].push({
                        post_id: post.id, post_name: post.name,
                        required_number: post.required_number, no_vote: true, candidates: [],
                    })
                } else if (this.selectedCandidates[post.id]?.length) {
                    const candidatesList = this.selectedCandidates[post.id].map(id => {
                        const c = post.candidates.find(c => c.id === id)
                        return {
                            candidacy_id:   c?.candidacy_id || c?.id,
                            user_name:      c?.user_name,
                            candidacy_name: c?.candidacy_name,
                        }
                    })
                    voteData[key].push({
                        post_id: post.id, post_name: post.name,
                        required_number: post.required_number, no_vote: false,
                        candidates: candidatesList,
                    })
                }
            })

            return voteData
        },

        requestSubmit() {
            this.postErrors = {}

            const validationErrors = this.validateAllPosts()
            if (validationErrors.length) {
                const errs = {}
                validationErrors.forEach(({ postId, msg }) => { errs[postId] = msg })
                this.postErrors = errs
                this.scrollToFirstError()
                return
            }

            if (!this.form.agree_button) return

            this.builtVoteData = this.buildVoteData()
            this.showConfirmModal = true
        },

        confirmSubmit() {
            this.showConfirmModal = false
            this.loading = true

            this.form.transform(() => ({
                ...this.builtVoteData,
                agree_button: this.form.agree_button,
                user_id:      this.form.user_id,
            })).post(this.submitRoute, {
                onError: (errors) => {
                    this.loading = false
                    if (errors.vote) alert(errors.vote)
                },
                onSuccess: () => {
                    this.clearDraft()
                    this.loading = false
                },
            })
        },

        scrollToFirstError() {
            const firstError = this.allPosts.find(p => this.postErrors[p.id])
            if (firstError) {
                const el = document.getElementById(`post-${firstError.id}`)
                el?.scrollIntoView({ behavior: 'smooth', block: 'center' })
                el?.focus()
            }
        },

        moveFocus(direction) {
            const cards = this.allCandidateCards
            if (!cards.length) return
            this.currentFocusIndex = (this.currentFocusIndex + direction + cards.length) % cards.length
            cards[this.currentFocusIndex]?.focus()
        },

        handleSpaceKey(e) {
            const cards = this.allCandidateCards
            const focused = cards[this.currentFocusIndex]
            if (focused && focused === document.activeElement) {
                e.preventDefault()
                // Use string comparison — IDs may be UUIDs, not numeric
                const candidateId = focused.dataset.candidateId
                const postId      = focused.dataset.postId
                const post        = this.allPosts.find(p => String(p.id) === String(postId))
                const candidate   = post?.candidates.find(c => String(c.id) === String(candidateId))
                if (post && candidate) this.toggleCandidate(post, candidate)
            }
        },

        saveDraft() {
            if (!this.isDirty) return
            try {
                localStorage.setItem(this.draftKey, JSON.stringify({
                    selectedCandidates: this.selectedCandidates,
                    noVoteSelections:   this.noVoteSelections,
                    savedAt:            Date.now(),
                }))
                this.isDirty = false
            } catch (e) {
                // Storage quota exceeded — silently ignore
            }
        },

        loadDraft() {
            try {
                const raw = localStorage.getItem(this.draftKey)
                if (!raw) return
                const data = JSON.parse(raw)
                if (Date.now() - data.savedAt < 3_600_000) {
                    this.selectedCandidates = data.selectedCandidates ?? {}
                    this.noVoteSelections   = data.noVoteSelections   ?? {}
                    this.isDirty = false
                } else {
                    localStorage.removeItem(this.draftKey)
                }
            } catch (e) {
                localStorage.removeItem(this.draftKey)
            }
        },

        clearDraft() {
            localStorage.removeItem(this.draftKey)
        },
    },
}
</script>

<style scoped>
@keyframes slideInUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
