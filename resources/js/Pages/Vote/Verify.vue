<template>
    <election-layout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
            <div class="container mx-auto px-4">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">
                        Vote Verification
                    </h1>
                    <p class="text-xl text-gray-600">
                        मतदान पुष्टिकरण
                    </p>
                    <div class="mt-4 inline-flex items-center px-4 py-2 bg-blue-100 rounded-full">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span class="text-blue-800 font-medium">Secure Verification Process</span>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid lg:grid-cols-2 gap-8 max-w-7xl mx-auto">
                    
                    <!-- Left Column - Verification Form -->
                    <div class="order-2 lg:order-1">
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            
                            <!-- Timing Information -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-400 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold text-amber-800">Time Remaining</span>
                                </div>
                                <div class="text-2xl font-bold text-amber-900 mb-1">
                                    {{ formatTime(timing_info.remaining_time) }}
                                </div>
                                <p class="text-sm text-amber-700">
                                    Code sent {{ timing_info.total_duration }} minutes ago
                                    <br>
                                    <span class="text-xs">कोड {{ timing_info.total_duration }} मिनेट अघि पठाइएको</span>
                                </p>
                            </div>

                            <!-- Instructions -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    Enter Verification Code
                                </h2>
                                <p class="text-gray-600 mb-4 leading-relaxed">
                                    Please check your email for the verification code we sent {{ timing_info.total_duration }} minutes ago. 
                                    Enter the code below to confirm and save your vote.
                                </p>
                                <p class="text-gray-500 text-sm leading-relaxed">
                                    कृपया आफ्नो इमेल चेक गर्नुहोस् र तल दिइएको ठाउँमा प्राप्त भएको कोड हाल्नुहोस्। 
                                    यसले तपाईंको मतदानलाई पुष्टि र सुरक्षित गर्नेछ।
                                </p>
                            </div>

                            <!-- Validation Errors -->
                            <div v-if="hasErrors" class="mb-6">
                                <jet-validation-errors class="mb-4" />
                            </div>

                            <!-- Verification Form -->
                            <form @submit.prevent="submit" class="space-y-6">
                                <div>
                                    <label for="voting_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        Verification Code / पुष्टिकरण कोड
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="voting_code"
                                            type="text"
                                            v-model="form.voting_code"
                                            class="block w-full px-4 py-4 text-lg font-mono text-center tracking-widest border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 uppercase"
                                            placeholder="ENTER 6-DIGIT CODE"
                                            maxlength="6"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.voting_code,
                                                'border-green-300 focus:border-green-500 focus:ring-green-500': form.voting_code && form.voting_code.length === 6
                                            }"
                                            autocomplete="off"
                                        />
                                        <div v-if="form.voting_code && form.voting_code.length === 6" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">
                                        Enter the 6-digit code from your email
                                    </p>
                                </div>

                                <!-- Submit Button -->
                                <button
                                    type="submit"
                                    :disabled="form.processing || !form.voting_code || form.voting_code.length !== 6"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 disabled:scale-100 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                                >
                                    <span v-if="form.processing" class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Verifying...
                                    </span>
                                    <span v-else class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Confirm & Save Vote
                                    </span>
                                </button>
                            </form>

                            <!-- Security Note -->
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            <strong>Security Notice:</strong> Your vote is encrypted and anonymous. 
                                            The verification code ensures only you can confirm your selections.
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            सुरक्षा सूचना: तपाईंको मत गुप्त र सुरक्षित छ।
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Vote Summary -->
                    <div class="order-1 lg:order-2">
                        <div class="bg-white rounded-2xl shadow-xl p-8 sticky top-8">
                            
                            <!-- User Info -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                <h3 class="font-semibold text-blue-800 mb-2">Voter Information</h3>
                                <div class="space-y-1 text-sm">
                                    <p><span class="font-medium">Name:</span> {{ user_info.name }}</p>
                                    <p v-if="user_info.nrna_id"><span class="font-medium">NRNA ID:</span> {{ user_info.nrna_id }}</p>
                                    <p v-if="user_info.region"><span class="font-medium">Region:</span> {{ user_info.region }}</p>
                                </div>
                            </div>

                            <!-- Vote Summary Header -->
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">
                                    Your Vote Summary
                                </h3>
                                <p class="text-sm text-gray-600">
                                    तपाईंको मतदानको सारांश
                                </p>
                                
                                <!-- Quick Stats -->
                                <div class="mt-4 grid grid-cols-3 gap-4">
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-2xl font-bold text-green-600">{{ voting_summary.voted_posts }}</div>
                                        <div class="text-xs text-green-700">Voted</div>
                                    </div>
                                    <div class="text-center p-3 bg-red-50 rounded-lg">
                                        <div class="text-2xl font-bold text-red-600">{{ voting_summary.no_vote_posts }}</div>
                                        <div class="text-xs text-red-700">Skipped</div>
                                    </div>
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-2xl font-bold text-blue-600">{{ voting_summary.total_posts }}</div>
                                        <div class="text-xs text-blue-700">Total</div>
                                    </div>
                                </div>
                            </div>

                            <!-- National Posts -->
                            <div v-if="vote_data.national_posts && vote_data.national_posts.length > 0" class="mb-6">
                                <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                    National Posts
                                </h4>
                                <div class="space-y-3">
                                    <div 
                                        v-for="post in vote_data.national_posts" 
                                        :key="`national-${post.post_id}`"
                                        class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow"
                                    >
                                        <div class="flex items-start justify-between mb-2">
                                            <h5 class="font-medium text-gray-800">{{ post.post_name }}</h5>
                                            <span v-if="post.no_vote" 
                                                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Skipped
                                            </span>
                                            <span v-else 
                                                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ post.candidates.length }} Selected
                                            </span>
                                        </div>
                                        
                                        <div v-if="!post.no_vote && post.candidates.length > 0" class="space-y-1">
                                            <div v-for="candidate in post.candidates" 
                                                 :key="candidate.candidacy_id"
                                                 class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                {{ candidate.name }}
                                            </div>
                                        </div>
                                        
                                        <div v-else-if="post.no_vote" class="text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            No vote for this position
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Regional Posts -->
                            <div v-if="vote_data.regional_posts && vote_data.regional_posts.length > 0" class="mb-6">
                                <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                                    Regional Posts
                                </h4>
                                <div class="space-y-3">
                                    <div 
                                        v-for="post in vote_data.regional_posts" 
                                        :key="`regional-${post.post_id}`"
                                        class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow"
                                    >
                                        <div class="flex items-start justify-between mb-2">
                                            <h5 class="font-medium text-gray-800">{{ post.post_name }}</h5>
                                            <span v-if="post.no_vote" 
                                                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Skipped
                                            </span>
                                            <span v-else 
                                                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ post.candidates.length }} Selected
                                            </span>
                                        </div>
                                        
                                        <div v-if="!post.no_vote && post.candidates.length > 0" class="space-y-1">
                                            <div v-for="candidate in post.candidates" 
                                                 :key="candidate.candidacy_id"
                                                 class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                {{ candidate.name }}
                                            </div>
                                        </div>
                                        
                                        <div v-else-if="post.no_vote" class="text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            No vote for this position
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No Selections Message -->
                            <div v-if="voting_summary.total_posts === 0" class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-gray-500">No vote data found</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </election-layout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import ElectionLayout from "@/Layouts/ElectionLayout";

export default {
    name: 'VoteVerify',
    
    components: {
        ElectionLayout,
        JetValidationErrors,
    },
    
    props: {
        vote_data: {
            type: Object,
            required: true
        },
        user_info: {
            type: Object,
            required: true
        },
        timing_info: {
            type: Object,
            required: true
        },
        voting_summary: {
            type: Object,
            required: true
        }
    },
    
    setup() {
        const form = useForm({
            voting_code: "",
        });

        function submit() {
            form.post("/votes");
        }

        return { form, submit };
    },
    
    computed: {
        hasErrors() {
            return Object.keys(this.form.errors).length > 0;
        }
    },
    
    methods: {
        formatTime(minutes) {
            if (minutes <= 0) {
                return "Expired";
            }
            
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            
            if (hours > 0) {
                return `${hours}h ${mins}m`;
            }
            return `${mins}m`;
        }
    },
    
    mounted() {
        // Auto-focus on the code input
        this.$nextTick(() => {
            const codeInput = document.getElementById('voting_code');
            if (codeInput) {
                codeInput.focus();
            }
        });
        
        // Auto-format code input
        const codeInput = document.getElementById('voting_code');
        if (codeInput) {
            codeInput.addEventListener('input', (e) => {
                // Remove non-alphanumeric characters and convert to uppercase
                e.target.value = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
            });
        }
    }
}
</script>

<style scoped>
/* Custom animations */
@keyframes pulse-subtle {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.animate-pulse-subtle {
    animation: pulse-subtle 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Custom focus styles */
input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>