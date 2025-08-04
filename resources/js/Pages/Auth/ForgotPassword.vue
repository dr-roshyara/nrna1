<template>
   <nrna-layout>
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50 py-8 px-4">
            <div class="max-w-md mx-auto">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full shadow-lg mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        पासवर्ड रिसेट
                    </h1>
                    <p class="text-lg text-gray-700 mb-1">Password Reset</p>
                    <p class="text-sm text-gray-600">NRNA Election System</p>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Instructions -->
                    <div class="bg-blue-50 px-6 py-6 border-b border-blue-100">
                        <div class="space-y-3 text-sm">
                            <p class="text-gray-700 leading-relaxed">
                                <span class="font-semibold text-gray-900">नेपाली:</span> 
                                आफ्नो इमेल ठेगाना प्रविष्ट गर्नुहोस्। हामी तपाईंलाई नयाँ पासवर्ड सेट गर्नको लागि लिंक पठाउनेछौं।
                            </p>
                            
                            <p class="text-gray-700 leading-relaxed">
                                <span class="font-semibold text-gray-900">English:</span> 
                                Enter your email address and we'll send you a password reset link.
                            </p>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="px-6 py-8">
                        <!-- Success Message -->
                        <div 
                            v-if="status" 
                            class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center"
                            role="alert"
                        >
                            <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ status }}</p>
                        </div>

                        <!-- Validation Errors -->
                        <jet-validation-errors class="mb-6" />

                        <!-- Form -->
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    इमेल ठेगाना | Email Address
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    
                                    <input
                                        id="email"
                                        type="email"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base placeholder-gray-400"
                                        v-model="form.email"
                                        required
                                        autofocus
                                        autocomplete="email"
                                        placeholder="your@email.com"
                                    />
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                            >
                                <svg 
                                    v-if="form.processing" 
                                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" 
                                    fill="none" 
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                
                                <span v-if="form.processing">
                                    पठाउँदै... | Sending...
                                </span>
                                <span v-else>
                                    रिसेट लिंक पठाउनुहोस् | Send Reset Link
                                </span>
                            </button>
                        </form>

                        <!-- Back to Login -->
                        <div class="mt-6 text-center">
                            <a 
                                href="/login" 
                                class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md px-2 py-1"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                लगइनमा फर्कनुहोस् | Back to Login
                            </a>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="bg-gray-50 px-6 py-4 border-t">
                        <div class="text-center">
                            <p class="text-xs text-gray-600">
                                <span class="font-semibold">सहायता चाहिएको छ? | Need Help?</span><br>
                                समस्या भएमा निर्वाचन कमिटीलाई सम्पर्क गर्नुहोस्।<br>
                                Contact the election committee for assistance.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </nrna-layout>
</template>

<script>
    import NrnaLayout from '@/Layouts/NrnaLayout'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            NrnaLayout,
            JetValidationErrors
        },

        props: {
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        mounted() {
            // Focus email input on page load
            this.$nextTick(() => {
                document.getElementById('email')?.focus();
            });
        },

        methods: {
            submit() {
                this.form.post(this.route('password.email'));
            }
        }
    }
</script>

<style scoped>
/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .transition-colors,
    .animate-spin {
        transition: none !important;
        animation: none !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-blue-600 {
        background-color: #000000 !important;
    }
    
    .border-gray-300 {
        border-color: #000000 !important;
        border-width: 2px !important;
    }
}

/* Print styles */
@media print {
    .bg-gradient-to-br,
    .shadow-xl {
        background: white !important;
        box-shadow: none !important;
        border: 2px solid #000 !important;
    }
}
</style>