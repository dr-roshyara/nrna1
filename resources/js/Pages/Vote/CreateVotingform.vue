<template>
    <section
        class="candidate-selection bg-white rounded-2xl shadow-lg border-2 border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-xl"
        :aria-labelledby="`post-title-${post.post_id}`"
        role="region"
    >
        <!-- Post Header with clear requirements -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 :id="`post-title-${post.post_id}`" class="text-2xl font-bold mb-1">
                        {{ post.name }}
                    </h2>
                    <p class="text-blue-100 text-sm opacity-90">
                        {{ post.nepali_name || post.name }}
                    </p>
                </div>

                <!-- Selection Requirements Badge -->
                <div class="bg-white/20 backdrop-blur-sm rounded-full px-5 py-2 inline-flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold text-lg">{{ post.required_number }}</span>
                    </div>
                    <span class="text-sm font-medium">
                        {{ $t('pages.voting.candidate_selection.required_candidates') }}
                    </span>
                </div>
            </div>

            <!-- Selection Mode Indicator -->
            <div v-if="selectAllRequired" class="mt-3 bg-yellow-500/20 border border-yellow-500/30 rounded-lg px-4 py-2 inline-flex items-center">
                <svg class="w-4 h-4 text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="text-yellow-100 text-sm font-medium">
                    {{ $t('pages.voting.candidate_selection.all_required') }}
                </span>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="p-6">
            <!-- Instructions -->
            <div class="mb-8 text-center">
                <p class="text-gray-700 mb-4 leading-relaxed">
                    {{ $t('pages.voting.candidate_selection.instruction', { number: post.required_number, position: post.name }) }}
                </p>
                <p class="text-gray-600 text-sm">
                    {{ $t('pages.voting.candidate_selection.instruction_nepali', { number: post.required_number, position: post.nepali_name || post.name }) }}
                </p>
            </div>

            <!-- Candidates List - Vertical Card Layout (Passport Style) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-8 mb-8">
                <div
                    v-for="(candidate, index) in candidatesWithState"
                    :key="candidate.candidacy_id"
                    class="candidate-card relative flex flex-col items-center"
                    :class="{
                        'ring-4 ring-blue-500 ring-offset-2': isSelected(candidate),
                        'opacity-60 grayscale': noVoteSelected,
                        'cursor-not-allowed': candidate.disabled || noVoteSelected
                    }"
                >
                    <!-- Candidate Card - Portrait Style -->
                    <div class="w-full bg-gradient-to-b from-gray-50 to-white border-2 border-gray-200 rounded-xl overflow-hidden transition-all duration-200 hover:border-blue-300 flex flex-col"
                         :class="{
                             'border-blue-400 bg-blue-50': isSelected(candidate),
                             'border-gray-300': !isSelected(candidate)
                         }">

                        <!-- Post Name Label (Top) -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-center px-3 py-2">
                            <p class="text-xs font-bold leading-tight">
                                {{ $t('pages.voting.candidate_selection.candidate_for_post', { post: post.name }) }}
                            </p>
                            <p class="text-xs font-bold leading-tight text-blue-100 mt-1">
                                {{ post.nepali_name }}
                            </p>
                        </div>

                        <!-- Passport Photo Area (Center) -->
                        <div class="flex justify-center p-8 bg-white">
                            <div class="w-40 h-40 flex-shrink-0 rounded-lg overflow-hidden border-2 border-gray-200">
                                <show-candidate
                                    :candidacy_image_path="candidate.image_path_1"
                                    :post_name="post.name"
                                    :post_nepali_name="post.nepali_name"
                                    :candidacy_name="candidate.user?.name || $t('pages.voting.candidate_selection.unknown')"
                                />
                            </div>
                        </div>

                        <!-- Checkbox Section (with Name Above) -->
                        <div class="w-full border-t-2 border-gray-200 p-6 flex flex-col items-center bg-white">
                            <!-- Candidate Name (Just Above Checkbox) -->
                            <div class="text-center px-3 pb-2 w-full">
                                <h3 class="text-sm font-bold text-gray-900 line-clamp-2">
                                    {{ candidate.user?.name || $t('pages.voting.candidate_selection.unknown') }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    #{{ index + 1 }}
                                </p>
                            </div>

                            <!-- Checkbox -->
                            <div class="relative mb-2">
                                <!-- Hidden checkbox for screen readers -->
                                <input
                                    type="checkbox"
                                    :id="`candidate-${candidate.candidacy_id}`"
                                    :value="candidate.candidacy_id"
                                    v-model="selected"
                                    @change="updateBoxes"
                                    :disabled="candidate.disabled || noVoteSelected"
                                    :aria-label="$t('pages.voting.candidate_selection.select_candidate', { name: candidate.user?.name || $t('pages.voting.candidate_selection.unknown') })"
                                    class="sr-only peer"
                                />

                                <!-- Visual checkbox -->
                                <label
                                    :for="`candidate-${candidate.candidacy_id}`"
                                    class="flex items-center justify-center w-14 h-14 bg-white border-4 border-gray-300 rounded-lg cursor-pointer
                                           peer-checked:bg-blue-600 peer-checked:border-blue-600
                                           peer-focus:ring-4 peer-focus:ring-blue-200 peer-focus:border-blue-500
                                           peer-disabled:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:border-gray-200
                                           transition-all duration-200 hover:border-blue-400 hover:shadow-md"
                                >
                                    <svg v-if="isSelected(candidate)" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span v-else class="text-gray-400 font-bold text-lg">
                                        ✓
                                    </span>
                                </label>
                            </div>

                            <!-- Selection Indicator -->
                            <div v-if="isSelected(candidate)" class="w-full">
                                <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-full">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    #{{ selectionOrder(candidate) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selection Status -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 border-2 border-gray-200 rounded-xl p-5">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Status Message -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">
                                {{ $t('pages.voting.candidate_selection.selection_status') }}
                            </h3>

                            <div :class="statusClasses">
                                <div class="flex items-center gap-3">
                                    <div :class="statusIconClasses" class="flex-shrink-0">
                                        {{ statusIcon }}
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ selectionStatus.message }}</p>
                                        <p v-if="selectedNames" class="text-sm opacity-90 mt-1">
                                            {{ $t('pages.voting.candidate_selection.selected_candidates') }}:
                                            <span class="font-medium">{{ selectedNames }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selection Counter -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg px-6 py-3 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ selected.length }}</div>
                            <div class="text-sm text-gray-600">
                                {{ $t('pages.voting.candidate_selection.of') }} {{ maxSelections }}
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                            <span>{{ $t('pages.voting.candidate_selection.progress') }}</span>
                            <span>{{ selectionProgress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div
                                class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                                :style="{ width: selectionProgress + '%' }"
                                role="progressbar"
                                :aria-valuenow="selectionProgress"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Large Skip Button -->
            <div class="border-2 border-gray-300 rounded-xl p-6 mb-6 bg-gradient-to-br from-gray-50 to-white">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <!-- Checkbox Area -->
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <input
                                type="checkbox"
                                :id="`no_vote_${post.post_id}`"
                                v-model="noVoteSelected"
                                @change="handleNoVoteChange"
                                class="sr-only peer"
                                :aria-label="$t('pages.voting.candidate_selection.skip_position_aria')"
                            />
                            <label
                                :for="`no_vote_${post.post_id}`"
                                class="flex items-center justify-center w-12 h-12 bg-white border-3 border-gray-600 rounded-lg cursor-pointer
                                       peer-checked:bg-blue-600 peer-checked:border-blue-600
                                       peer-focus:ring-4 peer-focus:ring-blue-200 peer-focus:border-blue-500
                                       transition-all duration-200 hover:border-gray-700 hover:shadow-lg"
                            >
                                <svg v-if="noVoteSelected" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </label>
                        </div>
                    </div>

                    <!-- Skip Content -->
                    <div class="flex-grow">
                        <label :for="`no_vote_${post.post_id}`" class="cursor-pointer block">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                {{ $t('pages.voting.candidate_selection.skip_position') }}
                            </h3>
                            <p class="text-gray-700 mb-3">
                                {{ $t('pages.voting.candidate_selection.skip_description') }}
                            </p>
                            <p class="text-gray-600 text-sm">
                                {{ $t('pages.voting.candidate_selection.skip_description_nepali') }}
                            </p>
                        </label>

                        <!-- Confirmation When Selected -->
                        <div v-if="noVoteSelected" class="mt-4 p-4 bg-gray-100 border-2 border-gray-300 rounded-lg flex items-center">
                            <svg class="w-6 h-6 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $t('pages.voting.candidate_selection.skip_confirmation', { position: post.name }) }}
                                </p>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ $t('pages.voting.candidate_selection.skip_instruction') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import ShowCandidate from '@/Shared/ShowCandidate'

export default {
    name: 'CreateVotingform',

    components: {
        ShowCandidate
    },

    props: {
        candidates: {
            type: Array,
            required: true,
            default: () => []
        },
        post: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            selected: [],
            candidatesWithState: [],
            noVoteSelected: false
        }
    },

    computed: {
        maxSelections() {
            return this.post?.required_number || 1;
        },

        selectAllRequired() {
            return import.meta.env?.VITE_SELECT_ALL_REQUIRED === 'yes';
        },

        hasValidSelection() {
            if (this.noVoteSelected) return true;

            if (this.selectAllRequired) {
                return this.selected.length === this.maxSelections;
            } else {
                return this.selected.length <= this.maxSelections;
            }
        },

        selectionStatus() {
            if (this.noVoteSelected) {
                return {
                    type: 'no-vote',
                    message: this.$t('pages.voting.candidate_selection.status_no_vote'),
                    color: 'gray'
                };
            }

            if (this.selectAllRequired) {
                if (this.selected.length === this.maxSelections) {
                    return {
                        type: 'valid',
                        message: this.$t('pages.voting.candidate_selection.status_valid', { number: this.maxSelections }),
                        color: 'green'
                    };
                } else {
                    return {
                        type: 'invalid',
                        message: this.$t('pages.voting.candidate_selection.status_invalid', { number: this.maxSelections }),
                        color: 'red'
                    };
                }
            } else {
                if (this.selected.length === 0) {
                    return {
                        type: 'empty',
                        message: this.$t('pages.voting.candidate_selection.status_empty'),
                        color: 'gray'
                    };
                } else if (this.selected.length === this.maxSelections) {
                    return {
                        type: 'full',
                        message: this.$t('pages.voting.candidate_selection.status_full', { number: this.maxSelections }),
                        color: 'green'
                    };
                } else {
                    return {
                        type: 'partial',
                        message: this.$t('pages.voting.candidate_selection.status_partial', {
                            selected: this.selected.length,
                            total: this.maxSelections
                        }),
                        color: 'blue'
                    };
                }
            }
        },

        statusClasses() {
            const base = "rounded-lg p-4";
            const colors = {
                'green': 'bg-green-50 border border-green-200 text-green-800',
                'blue': 'bg-blue-50 border border-blue-200 text-blue-800',
                'red': 'bg-red-50 border border-red-200 text-red-800',
                'gray': 'bg-gray-50 border border-gray-200 text-gray-800'
            };
            return `${base} ${colors[this.selectionStatus.color] || colors.gray}`;
        },

        statusIcon() {
            const icons = {
                'green': '✓',
                'blue': 'ℹ️',
                'red': '⚠️',
                'gray': '⏭️'
            };
            return icons[this.selectionStatus.color] || 'ℹ️';
        },

        statusIconClasses() {
            const classes = {
                'green': 'bg-green-100 text-green-600',
                'blue': 'bg-blue-100 text-blue-600',
                'red': 'bg-red-100 text-red-600',
                'gray': 'bg-gray-100 text-gray-600'
            };
            return `w-10 h-10 rounded-full flex items-center justify-center text-lg ${classes[this.selectionStatus.color] || classes.gray}`;
        },

        selectedNames() {
            if (this.noVoteSelected || this.selected.length === 0) return '';

            const selectedCandidates = this.candidatesWithState.filter(candidate =>
                this.selected.includes(candidate.candidacy_id)
            );
            return selectedCandidates.map(candidate =>
                candidate.user?.name || this.$t('pages.voting.candidate_selection.unknown')
            ).join(', ');
        },

        selectionProgress() {
            if (this.noVoteSelected) return 100;
            if (this.maxSelections === 0) return 0;
            return Math.min(100, Math.round((this.selected.length / this.maxSelections) * 100));
        }
    },

    watch: {
        candidates: {
            immediate: true,
            handler(newCandidates) {
                // Sort by position_order to ensure consistent display
                const sortedCandidates = [...newCandidates].sort((a, b) => {
                    const orderA = a.position_order || 0;
                    const orderB = b.position_order || 0;
                    return orderA - orderB;
                });
                this.candidatesWithState = sortedCandidates.map(candidate => ({
                    ...candidate,
                    disabled: false
                }));
            }
        },

        selected: {
            handler() {
                this.informSelectedCandidates();
            }
        },

        noVoteSelected: {
            handler() {
                this.informSelectedCandidates();
            }
        }
    },

    methods: {
        isSelected(candidate) {
            return this.selected.includes(candidate.candidacy_id);
        },

        selectionOrder(candidate) {
            return this.selected.indexOf(candidate.candidacy_id) + 1;
        },

        informSelectedCandidates() {
            let selectionData;

            if (this.noVoteSelected) {
                selectionData = {
                    post_id: this.post.post_id,
                    post_name: this.post.name,
                    required_number: this.post.required_number,
                    no_vote: true,
                    candidates: []
                };
            } else {
                const selectedCandidates = this.candidatesWithState.filter(candidate =>
                    this.selected.includes(candidate.candidacy_id)
                );

                const hasNoCandidatesSelected = selectedCandidates.length === 0;

                selectionData = {
                    post_id: this.post.post_id,
                    post_name: this.post.name,
                    required_number: this.post.required_number,
                    no_vote: hasNoCandidatesSelected,
                    candidates: selectedCandidates.map(candidate => ({
                        candidacy_id: candidate.candidacy_id,
                        user_id: candidate.user?.user_id || candidate.user?.id,
                        name: candidate.user?.name,
                        post_id: candidate.post_id || this.post.post_id
                    }))
                };
            }

            this.$emit('add_selected_candidates', selectionData);
        },

        handleNoVoteChange() {
            if (this.noVoteSelected) {
                this.selected = [];
                this.candidatesWithState.forEach(candidate => {
                    candidate.disabled = true;
                });
            } else {
                this.candidatesWithState.forEach(candidate => {
                    candidate.disabled = false;
                });
            }

            this.informSelectedCandidates();
        },

        updateBoxes() {
            if (this.noVoteSelected) {
                return;
            }

            this.candidatesWithState.forEach(candidate => {
                candidate.disabled = false;
            });

            if (this.selected.length >= this.maxSelections) {
                this.candidatesWithState.forEach(candidate => {
                    if (!this.selected.includes(candidate.candidacy_id)) {
                        candidate.disabled = true;
                    }
                });
            }
        }
    }
}
</script>

<style scoped>
.candidate-selection {
    scroll-margin-top: 2rem;
}

.candidate-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.candidate-card:hover:not(.cursor-not-allowed) {
    transform: translateY(-4px);
}

/* Screen Reader Only */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

/* Focus styles for accessibility */
input:focus-visible + label,
button:focus-visible,
[role="button"]:focus-visible {
    outline: 3px solid #2563eb;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .candidate-card,
    .candidate-card:hover,
    .transition-all {
        transition: none !important;
        transform: none !important;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .candidate-card {
        border-width: 2px !important;
    }

    .ring-4 {
        outline: 3px solid #000 !important;
    }
}

/* Large touch targets for mobile */
@media (max-width: 640px) {
    .candidate-card {
        padding: 1rem;
    }

    input[type="checkbox"] + label {
        min-width: 48px;
        min-height: 48px;
    }
}
</style>