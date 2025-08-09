<template>
    <election-layout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <header class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">
                        निर्वाचन परिणाम | Election Results
                    </h1>
                    <p class="text-xl text-gray-600 mb-4">{{ election_info.name }}</p>
                    <div class="w-24 h-1 bg-green-600 mx-auto rounded-full"></div>
                </header>

                <!-- Election Summary -->
                <section class="mb-12">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
                            मतदान सांख्यिकी | Voting Statistics
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Total Votes -->
                            <div class="text-center p-6 bg-blue-50 rounded-xl">
                                <div class="text-3xl font-bold text-blue-600 mb-2">
                                    {{ vote_statistics.total_votes.toLocaleString() }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    कुल मत | Total Votes Cast
                                </div>
                            </div>

                            <!-- Eligible Voters -->
                            <div class="text-center p-6 bg-green-50 rounded-xl">
                                <div class="text-3xl font-bold text-green-600 mb-2">
                                    {{ vote_statistics.total_eligible_voters.toLocaleString() }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    योग्य मतदाता | Eligible Voters
                                </div>
                            </div>

                            <!-- Turnout -->
                            <div class="text-center p-6 bg-purple-50 rounded-xl">
                                <div class="text-3xl font-bold text-purple-600 mb-2">
                                    {{ vote_statistics.turnout_percentage }}%
                                </div>
                                <div class="text-sm text-gray-600">
                                    मतदान प्रतिशत | Voter Turnout
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Results by Position -->
                <section class="space-y-12">
                    <div 
                        v-for="(result, postId) in results" 
                        :key="postId"
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
                    >
                        <!-- Position Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                            <div class="flex justify-between items-center text-white">
                                <div>
                                    <h3 class="text-2xl font-bold">{{ result.post_name }}</h3>
                                    <p class="text-blue-100">{{ result.state_name }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm opacity-90">आवश्यक संख्या | Required</div>
                                    <div class="text-2xl font-bold">{{ result.required_number }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Candidates Results -->
                        <div class="p-8">
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-600">
                                        यस पदको लागि कुल मत | Total Votes for this Position
                                    </span>
                                    <span class="text-lg font-bold text-gray-900">
                                        {{ result.total_votes_for_post.toLocaleString() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Winners Section -->
                            <div v-if="result.winners.length > 0" class="mb-8">
                                <h4 class="text-lg font-semibold text-green-700 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    विजेता | Winners
                                </h4>
                                <div class="grid gap-4">
                                    <div 
                                        v-for="winner in result.winners" 
                                        :key="winner.candidacy_id"
                                        class="bg-green-50 border border-green-200 rounded-xl p-4"
                                    >
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                <div class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-4">
                                                    {{ winner.position }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">
                                                        {{ winner.user.name }}
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        ID: {{ winner.candidacy_id }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xl font-bold text-green-700">
                                                    {{ winner.vote_count.toLocaleString() }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ winner.percentage }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- All Candidates Results -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-700 mb-4">
                                    सबै उम्मेदवारहरूको परिणाम | All Candidates Results
                                </h4>
                                <div class="space-y-4">
                                    <div 
                                        v-for="candidate in result.candidates" 
                                        :key="candidate.candidacy_id"
                                        :class="[
                                            'border rounded-xl p-4 transition-all duration-200',
                                            candidate.is_winner 
                                                ? 'border-green-300 bg-green-50' 
                                                : 'border-gray-200 bg-gray-50 hover:bg-gray-100'
                                        ]"
                                    >
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                <div :class="[
                                                    'rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold mr-4',
                                                    candidate.is_winner 
                                                        ? 'bg-green-600 text-white' 
                                                        : 'bg-gray-400 text-white'
                                                ]">
                                                    {{ candidate.position }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900 flex items-center">
                                                        {{ candidate.user.name }}
                                                        <span v-if="candidate.is_winner" class="ml-2 text-green-600">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        Candidacy ID: {{ candidate.candidacy_id }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ candidate.vote_count.toLocaleString() }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ candidate.percentage }}%
                                                </div>
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                                    <div 
                                                        :class="[
                                                            'h-2 rounded-full transition-all duration-500',
                                                            candidate.is_winner ? 'bg-green-600' : 'bg-blue-600'
                                                        ]"
                                                        :style="{ width: candidate.percentage + '%' }"
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer Information -->
                <section class="mt-16 bg-white rounded-2xl shadow-lg p-8 border border-gray-100 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        निर्वाचन जानकारी | Election Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
                        <div>
                            <strong>मतदान अवधि | Voting Period:</strong><br>
                            {{ formatDate(election_info.voting_start) }} - {{ formatDate(election_info.voting_end) }}
                        </div>
                        <div>
                            <strong>परिणाम प्रकाशित | Results Published:</strong><br>
                            {{ formatDate(results_published_at) }}
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </election-layout>
</template>

<script>
import ElectionLayout from "@/Layouts/ElectionLayout";

export default {
    components: {
        ElectionLayout,
    },
    
    props: {
        election_info: {
            type: Object,
            required: true
        },
        vote_statistics: {
            type: Object,
            required: true
        },
        posts: {
            type: Array,
            required: true
        },
        results: {
            type: Object,
            required: true
        },
        candidates: {
            type: Array,
            required: true
        },
        total_votes_cast: {
            type: Number,
            required: true
        },
        results_published_at: {
            type: String,
            required: true
        }
    },
    
    methods: {
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
};
</script>

<style scoped>
/* Ensure proper grid layout */
.grid {
    display: grid;
}

/* Animation for progress bars */
.transition-all {
    transition: all 0.5s ease-in-out;
}

/* Responsive text sizing */
@media (max-width: 640px) {
    .text-4xl {
        font-size: 2rem;
    }
    
    .text-3xl {
        font-size: 1.5rem;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .border-gray-200 {
        border-color: #000000 !important;
        border-width: 2px !important;
    }
}
</style>