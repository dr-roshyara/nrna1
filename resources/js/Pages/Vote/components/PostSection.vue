<template>
    <div class="rounded-2xl border-2 shadow-sm overflow-hidden transition-all duration-200 post-card-enter"
         :id="`post-${post.id}`"
         :class="cardBorderClass"
         :style="{ animationDelay: postIndex * 80 + 'ms' }">

        <!-- Post Header — gradient transitions with selection state -->
        <div class="bg-gradient-to-r text-white px-6 py-4 transition-all duration-300" :class="headerGradient">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-serif font-semibold leading-tight">{{ post.name }}</h3>
                    <p v-if="post.nepali_name" class="text-sm opacity-80 font-sans mt-0.5">{{ post.nepali_name }}</p>
                </div>
                <span class="shrink-0 bg-white/20 text-white text-xs font-mono font-bold px-3 py-1 rounded-full">
                    Select {{ post.required_number || 1 }}
                    {{ (post.required_number || 1) === 1 ? 'candidate' : 'candidates' }}
                </span>
            </div>
        </div>

        <!-- Error Alert -->
        <div v-if="hasError && errorMessage"
             class="bg-danger-50 border-b border-danger-200 px-6 py-3 flex items-center gap-2"
             role="alert">
            <span class="text-danger-600 shrink-0" aria-hidden="true">⚠️</span>
            <p class="text-danger-800 text-sm font-sans font-medium">{{ errorMessage }}</p>
        </div>

        <!-- Candidate Grid -->
        <div class="bg-white p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <div v-for="candidate in sortedCandidates"
                     :key="candidate.id"
                     :ref="el => { if (el) cardRefs.push(el) }"
                     :data-candidate-id="candidate.id"
                     :data-post-id="post.id"
                     tabindex="0"
                     role="checkbox"
                     :aria-checked="isSelected(candidate)"
                     :aria-label="`${candidate.candidacy_name || candidate.user_name} for ${post.name}`"
                     class="relative cursor-pointer rounded-xl border-2 overflow-hidden
                            transition-all duration-200 hover:shadow-md focus:outline-none
                            focus:ring-2 focus:ring-primary-400 focus:ring-offset-2"
                     :class="isSelected(candidate)
                         ? 'border-primary-400 bg-primary-50 shadow-md'
                         : 'border-neutral-200 bg-white hover:border-primary-300'"
                     @click="$emit('toggle-candidate', candidate)"
                     @keydown.enter.prevent="$emit('toggle-candidate', candidate)"
                     @keydown.space.prevent="$emit('toggle-candidate', candidate)">

                    <!-- Selection order badge -->
                    <span v-if="isSelected(candidate)"
                          class="absolute top-2 right-2 z-10 w-7 h-7 bg-primary-600 text-white
                                 text-xs font-bold rounded-full flex items-center justify-center shadow-sm">
                        #{{ selectionOrder(candidate) }}
                    </span>

                    <!-- Post label (small gradient bar at top of each card) -->
                    <div class="bg-gradient-to-r px-2 py-1 text-white text-xs font-sans font-semibold text-center"
                         :class="headerGradient">
                        {{ post.name }}
                    </div>

                    <!-- Candidate Photo -->
                    <div class="flex justify-center pt-4 pb-2 bg-white">
                        <div class="w-24 h-24 rounded-lg overflow-hidden border-2 bg-neutral-50"
                             :class="isSelected(candidate) ? 'border-primary-300' : 'border-neutral-200'">
                            <img v-if="getImageUrl(candidate.image_path)"
                                 :src="getImageUrl(candidate.image_path)"
                                 :alt="candidate.candidacy_name || candidate.user_name"
                                 class="w-full h-full object-cover"
                                 @error="e => e.target.style.display = 'none'" />
                            <div v-else
                                 class="w-full h-full flex items-center justify-center text-3xl text-neutral-300"
                                 aria-hidden="true">👤</div>
                        </div>
                    </div>

                    <!-- Candidate Name -->
                    <div class="px-2 pb-3 text-center">
                        <p class="font-sans font-bold text-neutral-900 text-sm leading-tight">
                            {{ candidate.candidacy_name || candidate.user_name }}
                        </p>
                        <p v-if="candidate.position_order" class="text-xs text-neutral-400 font-mono mt-0.5">
                            #{{ candidate.position_order }}
                        </p>
                    </div>

                    <!-- Hidden checkbox for form semantics -->
                    <input type="checkbox"
                           :checked="isSelected(candidate)"
                           class="sr-only"
                           tabindex="-1"
                           aria-hidden="true" />
                </div>
            </div>
        </div>

        <!-- Selection Status -->
        <SelectionStatus
            :post="post"
            :selected-candidates="selectedCandidatesObjects"
            :no-vote-selected="noVoteSelected"
        />

        <!-- Skip Position (No Vote) -->
        <div class="px-6 pb-5">
            <label class="inline-flex items-center gap-3 cursor-pointer group
                          px-4 py-2.5 rounded-lg border border-neutral-200
                          hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-150">
                <input type="checkbox"
                       :checked="noVoteSelected"
                       @change="$emit('toggle-no-vote')"
                       class="w-5 h-5 text-neutral-600 rounded border-neutral-400
                              focus:ring-2 focus:ring-neutral-400 focus:ring-offset-1 cursor-pointer" />
                <span class="font-sans text-neutral-600 text-sm font-medium group-hover:text-neutral-800">
                    ⏭️ Skip this position (No vote)
                </span>
            </label>
        </div>
    </div>
</template>

<script>
import SelectionStatus from './SelectionStatus.vue'

export default {
    name: 'PostSection',
    components: { SelectionStatus },

    props: {
        post:           { type: Object,  required: true },
        selectedCandidates: { type: Array,   default: () => [] },  // array of candidate IDs
        noVoteSelected: { type: Boolean, default: false },
        hasError:       { type: Boolean, default: false },
        errorMessage:   { type: String,  default: '' },
        postIndex:      { type: Number,  default: 0 },
    },

    emits: ['toggle-candidate', 'toggle-no-vote'],

    data() {
        return {
            // Local refs for keyboard navigation — exposed on component instance
            // Parent collects via $refs.postSection[n].cardRefs
            cardRefs: [],
        }
    },

    computed: {
        sortedCandidates() {
            return [...(this.post.candidates || [])]
                .sort((a, b) => (a.position_order || 0) - (b.position_order || 0))
        },
        headerGradient() {
            if (this.noVoteSelected) return 'from-neutral-500 to-neutral-600'
            const n   = this.selectedCandidates.length
            const req = this.post.required_number || 1
            if (n === req) return 'from-success-600 to-success-700'
            if (n > 0)     return 'from-yellow-500 to-yellow-600'
            return 'from-primary-700 to-indigo-800'
        },
        cardBorderClass() {
            if (this.hasError)       return 'border-danger-500 ring-2 ring-danger-200'
            if (this.noVoteSelected) return 'border-neutral-300'
            const n   = this.selectedCandidates.length
            const req = this.post.required_number || 1
            if (n === req) return 'border-success-400'
            if (n > 0)     return 'border-yellow-400'
            return 'border-neutral-200'
        },
        // Full candidate objects passed to SelectionStatus for name display
        selectedCandidatesObjects() {
            return this.selectedCandidates
                .map(id => (this.post.candidates || []).find(c => c.id === id))
                .filter(Boolean)
        },
    },

    methods: {
        isSelected(candidate) {
            return this.selectedCandidates.includes(candidate.id)
        },
        selectionOrder(candidate) {
            return this.selectedCandidates.indexOf(candidate.id) + 1
        },
        getImageUrl(path) {
            if (!path) return null
            if (path.startsWith('http') || path.startsWith('/storage')) return path
            return `/storage/${path}`
        },
    },
}
</script>

<style scoped>
@keyframes slideInUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
.post-card-enter {
    animation: slideInUp 0.35s ease-out both;
}
</style>
