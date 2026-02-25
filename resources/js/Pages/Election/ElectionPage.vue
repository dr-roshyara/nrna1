<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Header with proper ARIA landmark -->
        <header role="banner">
            <ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />
        </header>

        <!-- Main content with landmark -->
        <main class="flex-grow py-12 md:py-20 bg-white" role="main">
            <div class="container mx-auto px-4 md:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Page heading (screen reader only) -->
                    <h1 class="sr-only">{{ activeElection.name }} - {{ $t('pages.election.voting_page.title') }}</h1>

                    <!-- Election Status Banner - WCAG AA Compliant -->
                    <div class="mb-8 p-6 bg-gradient-to-r from-green-100 to-blue-100 border-2 border-green-300 rounded-lg"
                         role="alert"
                         aria-live="polite"
                         aria-label="Election status: Voting is currently active">
                        <div class="flex items-center gap-4">
                            <div class="text-4xl" role="img" aria-label="Ballot box">🗳️</div>
                            <div class="flex-grow">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                    {{ activeElection.name }}
                                </h2>
                                <p class="text-green-800 font-semibold">
                                    <span class="sr-only">Status: </span>
                                    ✅ {{ $t('pages.election.voting_page.status_active') }}
                                </p>
                            </div>
                            <div v-if="votingTimeRemaining" class="text-right">
                                <div class="text-sm text-gray-700 font-medium mb-1">
                                    {{ $t('pages.election.voting_page.time_remaining') }}
                                </div>
                                <div class="text-2xl font-bold text-green-700"
                                     :aria-label="getTimeRemainingLabel()">
                                    {{ formatTimeRemaining(votingTimeRemaining) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Voter Status Check - Already Voted -->
                    <div v-if="authUser.has_voted" class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded"
                         role="alert">
                        <div class="flex gap-3">
                            <span class="text-2xl" role="img" aria-label="Warning">📋</span>
                            <div>
                                <h3 class="font-bold text-yellow-800 mb-1">{{ $t('pages.election.voting_page.voter_status') }}</h3>
                                <p class="text-yellow-700">
                                    {{ $t('pages.election.voting_page.already_voted') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Voter Status Check - Not Eligible -->
                    <div v-if="!canVoteNow" class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded"
                         role="alert">
                        <div class="flex gap-3">
                            <span class="text-2xl" role="img" aria-label="Error">❌</span>
                            <div>
                                <h3 class="font-bold text-red-800 mb-1">{{ $t('pages.election.voting_page.voter_status') }}</h3>
                                <p class="text-red-700">
                                    {{ $t('pages.election.voting_page.not_eligible') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Election Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Election Details -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                {{ $t('pages.election.voting_page.details') }}
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">
                                        {{ $t('pages.election.voting_page.election_type') }}
                                    </p>
                                    <p class="text-gray-900">
                                        {{ activeElection.type === 'demo' ? $t('pages.election.voting_page.demo') : $t('pages.election.voting_page.real') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-700 font-medium">
                                        {{ $t('pages.election.voting_page.voting_period') }}
                                    </p>
                                    <p class="text-gray-900">
                                        {{ formatDate(activeElection.start_date) }} - {{ formatDate(activeElection.end_date) }}
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        ({{ activeElection.timezone || 'UTC' }})
                                    </p>
                                </div>

                                <div v-if="activeElection.description">
                                    <p class="text-sm text-gray-700 font-medium">
                                        {{ $t('pages.election.voting_page.description') }}
                                    </p>
                                    <p class="text-gray-900 text-sm">{{ activeElection.description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Voting Instructions -->
                        <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-400">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                {{ $t('pages.election.voting_page.voting_instructions') }}
                            </h3>

                            <ol class="space-y-3 text-sm">
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold"
                                          aria-hidden="true">1</span>
                                    <span class="text-gray-700">{{ $t('pages.election.voting_page.step1') }}</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold"
                                          aria-hidden="true">2</span>
                                    <span class="text-gray-700">{{ $t('pages.election.voting_page.step2') }}</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold"
                                          aria-hidden="true">3</span>
                                    <span class="text-gray-700">{{ $t('pages.election.voting_page.step3') }}</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold"
                                          aria-hidden="true">4</span>
                                    <span class="text-gray-700">{{ $t('pages.election.voting_page.step4') }}</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold"
                                          aria-hidden="true">5</span>
                                    <span class="text-gray-700">{{ $t('pages.election.voting_page.step5') }}</span>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <!-- Key Information Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-purple-100 to-purple-50 p-6 rounded-lg border border-purple-300">
                            <div class="text-3xl mb-2" role="img" aria-label="Lock">🔒</div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $t('pages.election.voting_page.secure') }}</h4>
                            <p class="text-sm text-gray-700">{{ $t('pages.election.voting_page.secure_desc') }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-green-100 to-green-50 p-6 rounded-lg border border-green-300">
                            <div class="text-3xl mb-2" role="img" aria-label="Checkmark">✓</div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $t('pages.election.voting_page.verified') }}</h4>
                            <p class="text-sm text-gray-700">{{ $t('pages.election.voting_page.verified_desc') }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-blue-100 to-blue-50 p-6 rounded-lg border border-blue-300">
                            <div class="text-3xl mb-2" role="img" aria-label="Globe">🌍</div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $t('pages.election.voting_page.global') }}</h4>
                            <p class="text-sm text-gray-700">{{ $t('pages.election.voting_page.global_desc') }}</p>
                        </div>
                    </div>

                    <!-- Voter Information Section -->
                    <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h3 class="font-bold text-gray-900 mb-3">👤 {{ $t('pages.election.voting_page.voter_status') }}</h3>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li>• <strong>{{ $t('pages.election.voting_page.verified_voter') }}:</strong> {{ authUser.name }}</li>
                            <li>• <strong>📧 {{ $t('common.email') }}:</strong> {{ authUser.email }}</li>
                            <li v-if="ipAddress">• <strong>📍 {{ $t('pages.election.voting_page.voting_from') }}:</strong> {{ ipAddress }}</li>
                        </ul>
                    </div>

                    <!-- Important Rules Section -->
                    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg" role="note">
                        <h3 class="font-bold text-red-800 mb-3">⚠️ {{ $t('pages.election.voting_page.important_rules') }}</h3>
                        <ul class="text-sm text-red-700 space-y-2">
                            <li>• {{ $t('pages.election.voting_page.rule_no_share') }}</li>
                            <li>• {{ $t('pages.election.voting_page.rule_one_vote') }}</li>
                            <li>• {{ $t('pages.election.voting_page.rule_no_change') }}</li>
                            <li>• {{ $t('pages.election.voting_page.rule_deadline') }} {{ formatDate(activeElection.end_date) }}</li>
                        </ul>
                    </div>

                    <!-- Call to Action -->
                    <div class="text-center mb-12">
                        <InertiaLink
                            :href="route('slug.code.create', { vslug: activeElection.slug })"
                            :disabled="authUser.has_voted || !canVoteNow"
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-700 to-blue-800 text-white font-bold rounded-lg hover:shadow-lg transition duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :aria-label="`${$t('pages.election.voting_page.start_voting')} for ${activeElection.name}`">
                            <span class="mr-2" role="img" aria-hidden="true">🗳️</span>
                            {{ $t('pages.election.voting_page.start_voting') }}
                        </InertiaLink>
                    </div>

                    <!-- Additional Information -->
                    <div class="pt-8 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="font-bold text-gray-900 mb-3">{{ $t('pages.election.voting_page.need_help') }}</h4>
                                <p class="text-sm text-gray-700 mb-4">{{ $t('pages.election.voting_page.help_desc') }}</p>
                                <a href="mailto:support(at)publicdigit.com"
                                   class="text-blue-700 hover:text-blue-800 hover:underline font-medium text-sm">
                                    {{ $t('pages.election.voting_page.contact_support') }}
                                </a>
                            </div>

                            <div>
                                <h4 class="font-bold text-gray-900 mb-3">{{ $t('pages.election.voting_page.important_notes') }}</h4>
                                <ul class="text-sm text-gray-700 space-y-2">
                                    <li>• {{ $t('pages.election.voting_page.note1') }}</li>
                                    <li>• {{ $t('pages.election.voting_page.note2') }} {{ formatDate(activeElection.end_date) }}</li>
                                    <li>• {{ $t('pages.election.voting_page.note3') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <PublicDigitFooter />
    </div>
</template>

<script>
import { Link as InertiaLink } from '@inertiajs/inertia-vue3';
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";

export default {
    components: {
        InertiaLink,
        ElectionHeader,
        PublicDigitFooter,
    },

    props: {
        activeElection: {
            type: Object,
            required: true,
        },
        authUser: {
            type: Object,
            required: true,
        },
        ipAddress: {
            type: String,
            default: null,
        },
    },

    data() {
        return {
            votingTimeRemaining: null,
            timerInterval: null,
        };
    },

    computed: {
        canVoteNow() {
            return this.authUser.can_vote_now === 1 && !this.authUser.has_voted;
        },
    },

    mounted() {
        this.startTimer();
    },

    beforeUnmount() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }
    },

    methods: {
        startTimer() {
            this.updateTimeRemaining();
            this.timerInterval = setInterval(() => {
                this.updateTimeRemaining();
            }, 1000);
        },

        updateTimeRemaining() {
            if (!this.activeElection.end_date) {
                this.votingTimeRemaining = null;
                return;
            }

            const endTime = new Date(this.activeElection.end_date).getTime();
            const now = new Date().getTime();
            const remaining = endTime - now;

            if (remaining <= 0) {
                this.votingTimeRemaining = null;
                if (this.timerInterval) {
                    clearInterval(this.timerInterval);
                }
            } else {
                this.votingTimeRemaining = remaining;
            }
        },

        formatTimeRemaining(ms) {
            const totalSeconds = Math.floor(ms / 1000);
            const days = Math.floor(totalSeconds / (24 * 3600));
            const hours = Math.floor((totalSeconds % (24 * 3600)) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            if (days > 0) {
                return `${days}d ${hours}h`;
            } else if (hours > 0) {
                return `${hours}h ${minutes}m`;
            } else if (minutes > 0) {
                return `${minutes}m ${seconds}s`;
            } else {
                return `${seconds}s`;
            }
        },

        getTimeRemainingLabel() {
            const totalSeconds = Math.floor(this.votingTimeRemaining / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            if (hours > 0) {
                return `${hours} hours and ${minutes} minutes remaining`;
            } else if (minutes > 0) {
                return `${minutes} minutes and ${seconds} seconds remaining`;
            } else {
                return `${seconds} seconds remaining`;
            }
        },

        formatDate(date) {
            if (!date) return '';
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(date).toLocaleDateString(this.$i18n.locale, options);
        },
    },
};
</script>

<style scoped>
/* Screen reader only text */
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

/* Ensure proper focus states for keyboard navigation */
a:focus,
button:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

/* Smooth transitions for all interactions */
* {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Disable transitions for reduced motion preference */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .text-gray-900,
    .text-gray-800,
    .text-gray-700 {
        color: #000000 !important;
    }

    .text-red-800,
    .text-red-700,
    .text-yellow-800,
    .text-yellow-700 {
        color: #000000 !important;
    }

    .border,
    .border-l-4 {
        border-width: 2px !important;
    }
}
</style>
