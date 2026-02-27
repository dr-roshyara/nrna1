<template>
    <VotingLayout>
        <div class="min-h-screen bg-linear-to-br from-green-50 via-emerald-50 to-blue-50 py-12 px-4">
            <div class="max-w-6xl mx-auto">
                <!-- Success Banner -->
                <div class="mb-12 bg-linear-to-r from-green-500 to-emerald-600 rounded-3xl shadow-2xl overflow-hidden">
                    <div class="relative px-8 py-16 md:py-20 text-center">
                        <!-- Animated Background -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-10 left-10 w-40 h-40 bg-white rounded-full blur-3xl animate-pulse"></div>
                            <div class="absolute bottom-10 right-10 w-40 h-40 bg-white rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                        </div>

                        <!-- Content -->
                        <div class="relative z-10">
                            <!-- Success Icon -->
                            <div class="mb-6 flex justify-center">
                                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Title -->
                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                                {{ $t('pages.vote_final.success_banner.title') }}
                            </h1>

                            <!-- Subtitle -->
                            <p class="text-xl text-green-50 mb-6 max-w-2xl mx-auto leading-relaxed">
                                {{ $t('pages.vote_final.success_banner.subtitle') }}
                            </p>

                            <!-- Confirmation Number -->
                            <div class="bg-white/20 backdrop-blur-xs rounded-2xl px-8 py-4 inline-block">
                                <p class="text-green-50 text-sm font-medium mb-1">{{ $t('pages.vote_final.success_banner.confirmation_number') }}</p>
                                <p class="text-white text-2xl font-mono font-bold tracking-wider">{{ confirmation_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gratitude Section -->
                <div class="mb-12 bg-white rounded-2xl shadow-lg p-8 md:p-12 border-l-4 border-green-500">
                    <div class="max-w-3xl mx-auto text-center">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            {{ $t('pages.vote_final.gratitude.thank_you') }}
                        </h2>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            {{ $t('pages.vote_final.gratitude.message') }}
                        </p>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid lg:grid-cols-3 gap-8 mb-12">
                    <!-- Left Column: Vote Summary -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Vote Summary -->
                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $t('pages.vote_final.vote_summary.title') }}
                            </h2>

                            <div class="space-y-6">
                                <!-- National Posts -->
                                <div v-if="has_national_posts">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b-2 border-blue-200">
                                        {{ $t('pages.vote_final.vote_summary.national_posts') }}
                                    </h3>
                                    <div class="space-y-4">
                                        <div v-for="(group, index) in national_posts_grouped" :key="index"
                                             class="p-4 bg-linear-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                                            <h4 class="font-semibold text-gray-900 mb-3 text-lg">
                                                {{ group.post_name }}
                                            </h4>
                                            <div v-if="group.candidates.length > 0" class="space-y-2">
                                                <div v-for="candidate in group.candidates" :key="candidate.candidacy_id"
                                                     class="flex items-center p-3 bg-white rounded-lg border border-blue-100">
                                                    <svg class="w-4 h-4 text-green-600 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-gray-800 font-medium">{{ candidate.user.name }}</span>
                                                </div>
                                            </div>
                                            <div v-else class="text-gray-600 italic text-sm">
                                                {{ $t('pages.vote_final.vote_summary.no_vote') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Regional Posts -->
                                <div v-if="has_regional_posts">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b-2 border-green-200">
                                        {{ $t('pages.vote_final.vote_summary.regional_posts') }}
                                    </h3>
                                    <div class="space-y-4">
                                        <div v-for="(group, index) in regional_posts_grouped" :key="index"
                                             class="p-4 bg-linear-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                                            <h4 class="font-semibold text-gray-900 mb-3 text-lg">
                                                {{ group.post_name }}
                                            </h4>
                                            <div v-if="group.candidates.length > 0" class="space-y-2">
                                                <div v-for="candidate in group.candidates" :key="candidate.candidacy_id"
                                                     class="flex items-center p-3 bg-white rounded-lg border border-green-100">
                                                    <svg class="w-4 h-4 text-green-600 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-gray-800 font-medium">{{ candidate.user.name }}</span>
                                                </div>
                                            </div>
                                            <div v-else class="text-gray-600 italic text-sm">
                                                {{ $t('pages.vote_final.vote_summary.no_vote') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- What Happens Next -->
                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                                <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $t('pages.vote_final.what_next.title') }}
                            </h2>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="p-6 bg-linear-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                                    <div class="flex items-start mb-3">
                                        <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold mr-3 shrink-0">1</div>
                                        <h3 class="font-semibold text-gray-900 text-lg">{{ $t('pages.vote_final.what_next.step_1_title') }}</h3>
                                    </div>
                                    <p class="text-gray-700 text-sm">{{ $t('pages.vote_final.what_next.step_1_description') }}</p>
                                </div>

                                <div class="p-6 bg-linear-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                                    <div class="flex items-start mb-3">
                                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3 shrink-0">2</div>
                                        <h3 class="font-semibold text-gray-900 text-lg">{{ $t('pages.vote_final.what_next.step_2_title') }}</h3>
                                    </div>
                                    <p class="text-gray-700 text-sm">{{ $t('pages.vote_final.what_next.step_2_description') }}</p>
                                </div>

                                <div class="p-6 bg-linear-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                    <div class="flex items-start mb-3">
                                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold mr-3 shrink-0">3</div>
                                        <h3 class="font-semibold text-gray-900 text-lg">{{ $t('pages.vote_final.what_next.step_3_title') }}</h3>
                                    </div>
                                    <p class="text-gray-700 text-sm">{{ $t('pages.vote_final.what_next.step_3_description') }}</p>
                                </div>

                                <div class="p-6 bg-linear-to-br from-orange-50 to-red-50 rounded-xl border border-orange-200">
                                    <div class="flex items-start mb-3">
                                        <div class="w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center font-bold mr-3 shrink-0">4</div>
                                        <h3 class="font-semibold text-gray-900 text-lg">{{ $t('pages.vote_final.what_next.step_4_title') }}</h3>
                                    </div>
                                    <p class="text-gray-700 text-sm">{{ $t('pages.vote_final.what_next.step_4_description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Important Info -->
                    <div class="space-y-8">
                        <!-- Important Information -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">
                                {{ $t('pages.vote_final.important_info.title') }}
                            </h2>

                            <div class="space-y-4">
                                <!-- Security -->
                                <div class="p-4 bg-linear-to-br from-blue-50 to-indigo-50 rounded-lg border-l-4 border-blue-600">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-sm">{{ $t('pages.vote_final.important_info.security.title') }}</h3>
                                            <p class="text-gray-700 text-xs mt-1">{{ $t('pages.vote_final.important_info.security.description') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Privacy -->
                                <div class="p-4 bg-linear-to-br from-purple-50 to-pink-50 rounded-lg border-l-4 border-purple-600">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-purple-600 mr-3 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-sm">{{ $t('pages.vote_final.important_info.privacy.title') }}</h3>
                                            <p class="text-gray-700 text-xs mt-1">{{ $t('pages.vote_final.important_info.privacy.description') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Verification -->
                                <div class="p-4 bg-linear-to-br from-green-50 to-emerald-50 rounded-lg border-l-4 border-green-600">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-sm">{{ $t('pages.vote_final.important_info.verification.title') }}</h3>
                                            <p class="text-gray-700 text-xs mt-1">{{ $t('pages.vote_final.important_info.verification.description') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Receipt Card -->
                        <div class="bg-linear-to-br from-gray-50 to-gray-100 rounded-2xl shadow-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $t('pages.vote_final.receipt.title') }}</h3>

                            <div class="space-y-3 mb-6 text-sm">
                                <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                    <span class="text-gray-600">{{ $t('pages.vote_final.receipt.timestamp') }}</span>
                                    <span class="font-mono text-gray-900">{{ submission_time }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                    <span class="text-gray-600">{{ $t('pages.vote_final.receipt.reference_number') }}</span>
                                    <span class="font-mono text-gray-900">{{ confirmation_number }}</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                    📥 {{ $t('pages.vote_final.receipt.download_button') }}
                                </button>
                                <button class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                    🖨️ {{ $t('pages.vote_final.receipt.print_button') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid md:grid-cols-3 gap-4 mb-12">
                    <button @click="returnDashboard"
                            class="bg-linear-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        {{ $t('pages.vote_final.actions.return_dashboard') }}
                    </button>
                    <button class="bg-linear-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        {{ $t('pages.vote_final.actions.view_results') }}
                    </button>
                    <button class="bg-linear-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        {{ $t('pages.vote_final.actions.help') }}
                    </button>
                </div>

                <!-- Footer Message -->
                <div class="text-center py-8 border-t border-gray-200">
                    <p class="text-lg text-gray-700 font-medium">
                        {{ $t('pages.vote_final.footer.message') }}
                    </p>
                </div>
            </div>
        </div>
    </VotingLayout>
</template>

<script>
import VotingLayout from "@/Components/Election/VotingLayout.vue";

export default {
    name: 'VoteFinal',
    components: {
        VotingLayout,
    },
    props: {
        vote: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            image_path: "/storage/images/",
        };
    },
    computed: {
        confirmation_number() {
            return Math.random().toString(36).substring(2, 12).toUpperCase();
        },
        submission_time() {
            return new Date().toLocaleString();
        },
        national_posts_grouped() {
            return this.groupVotesByPost('national');
        },
        regional_posts_grouped() {
            return this.groupVotesByPost('regional');
        },
        has_national_posts() {
            return this.national_posts_grouped && this.national_posts_grouped.length > 0;
        },
        has_regional_posts() {
            return this.regional_posts_grouped && this.regional_posts_grouped.length > 0;
        }
    },
    methods: {
        groupVotesByPost(type) {
            if (!this.vote || typeof this.vote !== 'object') return [];

            const grouped = {};

            Object.values(this.vote).forEach(item => {
                try {
                    const data = typeof item === 'string' ? JSON.parse(item) : item;

                    if (data && data.candidates && data.post_name) {
                        const key = data.post_name;
                        if (!grouped[key]) {
                            grouped[key] = {
                                post_name: data.post_name,
                                post_id: data.post_id,
                                candidates: []
                            };
                        }
                        grouped[key].candidates.push(...data.candidates);
                    }
                } catch (e) {
                    // Skip invalid entries
                }
            });

            return Object.values(grouped);
        },
        returnDashboard() {
            // Navigate back to dashboard
            this.$inertia.get(route('dashboard'));
        }
    },
    mounted() {
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};
</script>

<style scoped>
/* Animations */
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Apply animations */
@media (prefers-reduced-motion: no-preference) {
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }
}

/* Focus styles for accessibility */
button:focus-visible {
    outline: 3px solid #3b82f6;
    outline-offset: 2px;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .space-y-8 > * + * {
        margin-top: 1.5rem;
    }
}
</style>
