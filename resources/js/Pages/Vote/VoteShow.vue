<template>
    <nrna-layout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 px-4">
            <div class="max-w-6xl mx-auto">
                
                <!-- Page Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        Vote Verification Successful
                    </h1>
                    <p class="text-lg text-gray-600">
                        Below are the voting details for the submitted verification code
                    </p>
                </div>

                <!-- Voter Information Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl md:text-2xl font-bold text-white mb-1">
                                    Voter Information
                                </h2>
                                <p class="text-blue-100">
                                    {{ vote_data.is_own_vote ? 'Your voting record' : 'Verification result for submitted code' }}
                                </p>
                            </div>
                            <div class="hidden md:block">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Voter Details -->
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Voter Name</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ vote_data.voter_info.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Voter ID</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ vote_data.voter_info.user_id }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Region</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ vote_data.voter_info.region }}</p>
                                </div>
                            </div>
                            
                            <!-- Vote Details -->
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Vote Date</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ vote_data.vote_info.voted_at }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Vote ID</label>
                                    <p class="text-lg font-semibold text-gray-900">#{{ vote_data.vote_id }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Verification Status</label>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span class="text-lg font-semibold text-green-600">Verified</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vote Summary Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                    <div class="px-6 py-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Vote Summary</h3>
                        <p class="text-gray-600">Overview of voting participation</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <div class="text-3xl font-bold text-blue-600 mb-1">{{ vote_data.summary.total_positions }}</div>
                                <div class="text-sm font-medium text-blue-700">Total Positions</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <div class="text-3xl font-bold text-green-600 mb-1">{{ vote_data.summary.positions_voted }}</div>
                                <div class="text-sm font-medium text-green-700">Positions Voted</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-xl">
                                <div class="text-3xl font-bold text-purple-600 mb-1">{{ vote_data.summary.candidates_selected }}</div>
                                <div class="text-sm font-medium text-purple-700">Candidates Selected</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Vote Option Display -->
                <div v-if="vote_data.vote_info.no_vote_option" class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Right to Reject Exercised</h3>
                        <p class="text-lg text-gray-600 max-w-md mx-auto">
                            This voter chose to exercise their <span class="font-semibold text-red-600">right to reject all candidates</span> 
                            for all positions in this election.
                        </p>
                    </div>
                </div>

                <!-- Vote Selections -->
                <div v-else-if="vote_data.vote_selections && vote_data.vote_selections.length > 0">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="px-6 py-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Vote Selections</h3>
                            <p class="text-gray-600">Detailed breakdown of all voting choices</p>
                        </div>
                        
                        <div class="divide-y divide-gray-100">
                            <div 
                                v-for="(selection, index) in vote_data.vote_selections" 
                                :key="index"
                                class="p-6"
                            >
                                <!-- Position Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900">
                                            {{ selection.post_name }}
                                        </h4>
                                        <p v-if="selection.post_nepali_name" class="text-sm text-gray-600">
                                            {{ selection.post_nepali_name }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-500">Position ID</div>
                                        <div class="text-sm text-gray-900">{{ selection.post_id }}</div>
                                    </div>
                                </div>

                                <!-- No Vote for this position -->
                                <div v-if="selection.no_vote" class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">No Vote Cast</p>
                                            <p class="text-sm text-gray-600">Voter chose not to vote for this position</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Candidates Selected -->
                                <div v-else-if="selection.candidates && selection.candidates.length > 0" class="space-y-4">
                                    <div 
                                        v-for="(candidate, candidateIndex) in selection.candidates"
                                        :key="candidateIndex"
                                        class="bg-green-50 border border-green-200 rounded-xl p-4"
                                    >
                                        <div class="flex items-start space-x-4">
                                            <!-- Candidate Image -->
                                            <div class="flex-shrink-0">
                                                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                                    {{ getCandidateInitial(candidate) }}
                                                </div>
                                            </div>
                                            
                                            <!-- Candidate Info -->
                                            <div class="flex-1 min-w-0">
                                                <h5 class="text-lg font-bold text-gray-900 mb-1">
                                                    {{ getCandidateName(candidate) }}
                                                </h5>
                                                
                                                <div class="grid md:grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="font-medium text-gray-700">Candidate ID:</span>
                                                        <span class="text-gray-900 ml-1">{{ candidate.candidacy_id }}</span>
                                                    </div>
                                                    <div v-if="candidate.user_info && candidate.user_info.user_id">
                                                        <span class="font-medium text-gray-700">User ID:</span>
                                                        <span class="text-gray-900 ml-1">{{ candidate.user_info.user_id }}</span>
                                                    </div>
                                                    <div v-if="candidate.proposer_name">
                                                        <span class="font-medium text-gray-700">Proposer:</span>
                                                        <span class="text-gray-900 ml-1">{{ candidate.proposer_name }}</span>
                                                    </div>
                                                    <div v-if="candidate.supporter_name">
                                                        <span class="font-medium text-gray-700">Supporter:</span>
                                                        <span class="text-gray-900 ml-1">{{ candidate.supporter_name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Selection Badge -->
                                            <div class="flex-shrink-0">
                                                <div class="inline-flex items-center space-x-1 bg-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span>Selected</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- No candidates for this position -->
                                <div v-else class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-yellow-200 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">No Selection Made</p>
                                            <p class="text-sm text-gray-600">No candidates were selected for this position</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Vote Data Available -->
                <div v-else class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Vote Selections Found</h3>
                        <p class="text-gray-600 max-w-md mx-auto">
                            No voting selections were found for this verification code.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <button
                        @click="goToVerifyAnother"
                        class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Verify Another Code
                    </button>
                    
                    <button
                        @click="goToDashboard"
                        class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Go to Dashboard
                    </button>
                </div>

                <!-- Security Notice -->
                <div class="mt-8 text-center">
                    <div class="inline-flex items-center space-x-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>This vote record is cryptographically secured and tamper-proof</span>
                    </div>
                </div>
            </div>
        </div>
    </nrna-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/ElectionLayout.vue";

export default {
    components: {
        NrnaLayout,
    },
    
    props: {
        vote_data: {
            type: Object,
            required: true
        },
        viewing_user: {
            type: Object,
            required: true
        }
    },

    methods: {
        goToVerifyAnother() {
            this.$inertia.visit(route('vote.verify_to_show'));
        },
        
        goToDashboard() {
            this.$inertia.visit(route('dashboard'));
        },

        /**
         * Get the best available name for a candidate
         * Name should come from User table, not candidacy table
         */
        getCandidateName(candidate) {
            // Priority 1: Get name from user_info.name (User table)
            if (candidate.user_info && candidate.user_info.name && 
                candidate.user_info.name.trim() !== '' && 
                candidate.user_info.name !== 'Unknown') {
                return candidate.user_info.name;
            }
            
            // Priority 2: Use candidacy_name (this now comes from User table via backend)
            if (candidate.candidacy_name && 
                candidate.candidacy_name.trim() !== '' && 
                !candidate.candidacy_name.includes('Unknown')) {
                return candidate.candidacy_name;
            }
            
            // Priority 3: Use user_name field (backup in candidacy table)
            if (candidate.user_name && candidate.user_name.trim() !== '') {
                return candidate.user_name;
            }
            
            // Priority 4: Use name field (backup in candidacy table)
            if (candidate.name && candidate.name.trim() !== '') {
                return candidate.name;
            }
            
            // Priority 5: Generate from candidacy_id
            if (candidate.candidacy_id) {
                // Convert "DE_TEST_2025_07" to "Candidate DE TEST 2025 07"
                const cleaned = candidate.candidacy_id.replace(/[_-]/g, ' ');
                return `Candidate ${cleaned}`;
            }
            
            return 'Unknown Candidate';
        },

        /**
         * Get the first letter of candidate name for avatar
         */
        getCandidateInitial(candidate) {
            const name = this.getCandidateName(candidate);
            if (name && name.length > 0 && !name.includes('Unknown')) {
                return name.charAt(0).toUpperCase();
            }
            return 'C';
        }
    },

    computed: {
        isOwnVote() {
            return this.vote_data.is_own_vote;
        },
        
        hasVoteSelections() {
            return this.vote_data.vote_selections && this.vote_data.vote_selections.length > 0;
        }
    },

    mounted() {
        // Log vote view for audit purposes
        console.log('Vote record displayed:', {
            vote_id: this.vote_data.vote_id,
            is_own_vote: this.vote_data.is_own_vote,
            viewing_timestamp: new Date().toISOString()
        });
    }
};
</script>

<style scoped>
/* Custom scrollbar for better UX */
.vote-selections::-webkit-scrollbar {
    width: 6px;
}

.vote-selections::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.vote-selections::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.vote-selections::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Animation for cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
}
</style>