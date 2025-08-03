<template>
    <nrna-layout>
        <!-- Main Container -->
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Success Banner (if voted) -->
                <div
                    v-if="has_voted"
                    class="mb-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl shadow-lg overflow-hidden"
                >
                    <div class="px-8 py-10 text-center text-white">
                        <!-- Success Icon -->
                        <div class="mb-6">
                            <div class="mx-auto w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Congratulations Message -->
                        <h1 class="text-3xl md:text-4xl font-bold mb-4">
                            🎉 Congratulations {{ user_name }}!
                        </h1>
                        
                        <!-- Success Description -->
                        <div class="max-w-2xl mx-auto">
                            <div class="bg-white bg-opacity-10 rounded-xl p-6 mb-6">
                                <p class="text-lg md:text-xl mb-4 leading-relaxed">
                                    Thank you for participating in the election! Your vote has been successfully recorded. 
                                    To verify your vote, please enter the <span class="font-bold text-yellow-200">verification code</span> 
                                    sent to your email.
                                </p>
                                
                                <!-- Nepali Text -->
                                <div class="pt-4 border-t border-white border-opacity-20">
                                    <p class="text-base opacity-90 leading-relaxed">
                                        मतदान गर्नुभएकोमा धन्यवाद! आफ्नो मत प्रमाणित गर्न तल दिइएको कोड भर्नुहोस्। 
                                        कृपया आफ्नो पासवर्ड गोप्य राख्नुहोस् र अरूलाई नदिनुहोस्।
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Security Notice -->
                            <div class="flex items-center justify-center space-x-2 text-sm opacity-90">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>Keep your verification code private and secure</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Verification Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-8 text-center">
                        <div class="mx-auto w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">
                            Verify Your Vote
                        </h2>
                        <p class="text-blue-100 text-lg">
                            Enter your verification code to view your voting choices
                        </p>
                    </div>

                    <!-- Verification Form -->
                    <div class="p-8 md:p-12">
                        <!-- Instructions -->
                        <div class="mb-8 text-center">
                            <div class="inline-flex items-center space-x-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>Check Your Email</span>
                            </div>
                            <p class="text-gray-600 max-w-md mx-auto">
                                A verification code has been sent to your registered email address. 
                                Please enter it below to view your voting record.
                            </p>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Verification Code Input -->
                            <div class="space-y-2">
                                <label for="voting_code" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                        <span>Verification Code</span>
                                    </span>
                                </label>
                                
                                <div class="relative">
                                    <input
                                        id="voting_code"
                                        type="text"
                                        v-model="form.voting_code"
                                        placeholder="Enter your verification code here"
                                        class="w-full px-6 py-4 text-lg font-mono border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 bg-gray-50 focus:bg-white"
                                        :class="{
                                            'border-red-300 focus:border-red-500 focus:ring-red-100': form.errors.voting_code,
                                            'border-green-300 focus:border-green-500 focus:ring-green-100': form.voting_code && !form.errors.voting_code
                                        }"
                                        autocomplete="off"
                                        :disabled="form.processing"
                                    />
                                    
                                    <!-- Input Icon -->
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                        <svg 
                                            v-if="form.processing" 
                                            class="w-5 h-5 text-blue-500 animate-spin" 
                                            fill="none" 
                                            viewBox="0 0 24 24"
                                        >
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <svg 
                                            v-else-if="form.voting_code && !form.errors.voting_code" 
                                            class="w-5 h-5 text-green-500" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <svg 
                                            v-else 
                                            class="w-5 h-5 text-gray-400" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button
                                    type="submit"
                                    :disabled="form.processing || !form.voting_code"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 disabled:from-gray-300 disabled:to-gray-400 text-white font-bold py-4 px-8 rounded-xl transition-all duration-200 transform hover:scale-[1.02] disabled:scale-100 focus:ring-4 focus:ring-blue-200 shadow-lg disabled:shadow-none"
                                >
                                    <span v-if="form.processing" class="flex items-center justify-center space-x-3">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Verifying...</span>
                                    </span>
                                    <span v-else class="flex items-center justify-center space-x-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Verify & View My Vote</span>
                                    </span>
                                </button>
                            </div>

                            <!-- Error Display -->
                            <jet-validation-errors 
                                v-if="form.errors && Object.keys(form.errors).length > 0"
                                class="mt-6"
                            />
                        </form>

                        <!-- Help Section -->
                        <div class="mt-12 pt-8 border-t border-gray-100">
                            <div class="text-center">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                                <div class="grid md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                                    <!-- Help Item 1 -->
                                    <div class="bg-gray-50 rounded-lg p-4 text-left">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">Check Email</h4>
                                                <p class="text-sm text-gray-600">Look for verification code in your inbox or spam folder</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Help Item 2 -->
                                    <div class="bg-gray-50 rounded-lg p-4 text-left">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">Contact Support</h4>
                                                <p class="text-sm text-gray-600">Send screenshot to administrator if issues persist</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Notice -->
                <div class="mt-8 text-center">
                    <div class="inline-flex items-center space-x-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Your vote is encrypted and secure</span>
                    </div>
                </div>
            </div>
        </div>
    </nrna-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/ElectionLayout.vue";
import VoteFinal from "@/Pages/Vote/VoteFinal";
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
    components: {
        NrnaLayout,
        VoteFinal,
        JetValidationErrors,
    },
    
    props: {
        vote: Object,
        has_voted: Boolean,
        user_name: String,
    },

    setup() {
        const form = useForm({
            voting_code: "",
        });

        function submit() {
                                        form.post(route('vote.submit_code_to_view_vote'), {
                preserveScroll: true,
                onStart: () => {
                    // Optional: Add any loading state logic here
                },
                onSuccess: () => {
                    // Optional: Add success handling here
                },
                onError: () => {
                    // Form errors are automatically handled by Inertia
                },
                onFinish: () => {
                    // Optional: Add cleanup logic here
                }
            });
        }

        return { form, submit };
    },

    data() {
        return {
            // Additional reactive data if needed
        };
    },

    computed: {
        user_has_voted() {
            return this.has_voted;
        },
    },

    mounted() {
        // Focus on the input field when component mounts
        this.$nextTick(() => {
            const input = document.getElementById('voting_code');
            if (input) {
                input.focus();
            }
        });
    }
};
</script>

<style scoped>
/* Custom styles for better UX */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Animation for form validation */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.form-error {
    animation: shake 0.5s ease-in-out;
}

/* Custom focus ring */
.focus-ring:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .container-padding {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>