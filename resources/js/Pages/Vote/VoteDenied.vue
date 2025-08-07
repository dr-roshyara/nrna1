<template>
    <NrnaLayout :canLogin="true" :canRegister="false">
        <div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ title_english || 'Vote Denied' }}
                    </h1>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6" v-if="title_nepali">
                        {{ title_nepali }}
                    </h2>
                    
                    <!-- User Info -->
                    <div class="inline-flex items-center px-4 py-2 bg-white rounded-full shadow-sm border">
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-gray-600 font-medium">{{ user_name }}</span>
                        <span class="mx-2 text-gray-300">•</span>
                        <span class="text-sm text-gray-500">{{ getCurrentDateTime() }}</span>
                    </div>
                </div>

                <!-- Main Content Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    
                    <!-- Denial Type Badge -->
                    <div class="px-6 py-4 bg-red-600 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500 text-white">
                                    {{ denial_type || 'Access Denied' }}
                                </span>
                            </div>
                            <div class="text-sm opacity-90">
                                NRNA Election System
                            </div>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    <div class="px-8 py-8">
                        
                        <!-- English Message -->
                        <div class="mb-6 p-6 bg-red-50 rounded-lg border-l-4 border-red-400">
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-red-400 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-red-800 mb-2">Error Details</h3>
                                    <p class="text-red-700 leading-relaxed text-base">
                                        {{ error_message_english }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Nepali Message -->
                        <div class="mb-6 p-6 bg-orange-50 rounded-lg border-l-4 border-orange-400" v-if="error_message_nepali">
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-orange-400 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-orange-800 mb-2">त्रुटि विवरण</h3>
                                    <p class="text-orange-700 leading-relaxed text-base">
                                        {{ error_message_nepali }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Details (if available) -->
                        <div class="mb-6" v-if="showTechnicalDetails">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Technical Information
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <!-- IP Information -->
                                    <div v-if="current_ip || original_ip || registered_ip">
                                        <h4 class="font-semibold text-gray-700 mb-2">IP Address Information</h4>
                                        <div class="space-y-1">
                                            <div v-if="current_ip" class="flex justify-between">
                                                <span class="text-gray-600">Current IP:</span>
                                                <span class="font-mono text-gray-800">{{ current_ip }}</span>
                                            </div>
                                            <div v-if="original_ip" class="flex justify-between">
                                                <span class="text-gray-600">Original IP:</span>
                                                <span class="font-mono text-gray-800">{{ original_ip }}</span>
                                            </div>
                                            <div v-if="registered_ip" class="flex justify-between">
                                                <span class="text-gray-600">Registered IP:</span>
                                                <span class="font-mono text-gray-800">{{ registered_ip }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rate Limit Information -->
                                    <div v-if="votes_from_ip || max_votes_allowed">
                                        <h4 class="font-semibold text-gray-700 mb-2">Rate Limit Information</h4>
                                        <div class="space-y-1">
                                            <div v-if="votes_from_ip" class="flex justify-between">
                                                <span class="text-gray-600">Votes from IP:</span>
                                                <span class="font-semibold text-red-600">{{ votes_from_ip }}</span>
                                            </div>
                                            <div v-if="max_votes_allowed" class="flex justify-between">
                                                <span class="text-gray-600">Maximum allowed:</span>
                                                <span class="font-semibold text-green-600">{{ max_votes_allowed }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Time Information -->
                                    <div v-if="expired_minutes || time_limit">
                                        <h4 class="font-semibold text-gray-700 mb-2">Time Information</h4>
                                        <div class="space-y-1">
                                            <div v-if="expired_minutes" class="flex justify-between">
                                                <span class="text-gray-600">Minutes expired:</span>
                                                <span class="font-semibold text-red-600">{{ expired_minutes }}</span>
                                            </div>
                                            <div v-if="time_limit" class="flex justify-between">
                                                <span class="text-gray-600">Time limit:</span>
                                                <span class="font-semibold text-green-600">{{ time_limit }} min</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Voting Hours -->
                                    <div v-if="voting_start || voting_end">
                                        <h4 class="font-semibold text-gray-700 mb-2">Voting Hours</h4>
                                        <div class="space-y-1">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Allowed hours:</span>
                                                <span class="font-mono text-gray-800">{{ voting_start }} - {{ voting_end }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Solutions Section -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="h-6 w-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                How to Resolve This Issue
                            </h3>

                            <!-- English Solution -->
                            <div class="mb-4 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-400" v-if="solution_english">
                                <div class="flex items-start">
                                    <svg class="h-6 w-6 text-blue-400 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-blue-800 mb-2">Solution</h4>
                                        <p class="text-blue-700 leading-relaxed">
                                            {{ solution_english }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Nepali Solution -->
                            <div class="mb-4 p-6 bg-green-50 rounded-lg border-l-4 border-green-400" v-if="solution_nepali">
                                <div class="flex items-start">
                                    <svg class="h-6 w-6 text-green-400 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-green-800 mb-2">समाधान</h4>
                                        <p class="text-green-700 leading-relaxed">
                                            {{ solution_nepali }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-8 bg-gray-100 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Need Help? Contact Election Committee
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600 mb-1">Email:</p>
                                    <a href="mailto:election@nrna.org" class="text-blue-600 hover:text-blue-800 font-medium">
                                        election@nrna.org
                                    </a>
                                </div>
                                <div>
                                    <p class="text-gray-600 mb-1">Reference ID:</p>
                                    <span class="font-mono text-gray-800 bg-white px-2 py-1 rounded">
                                        {{ generateReferenceId() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link :href="route('dashboard')" 
                                  class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Return to Dashboard
                            </Link>

                            <button @click="toggleTechnicalDetails" 
                                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          :d="showTechnicalDetails ? 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'" />
                                </svg>
                                {{ showTechnicalDetails ? 'Hide' : 'Show' }} Technical Details
                            </button>

                            <button @click="copyErrorDetails" 
                                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copy Error Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        NRNA Election System • {{ getCurrentYear() }} • Secure Voting Platform
                    </p>
                </div>

                <!-- Success Message for Copy Operation -->
                <div v-show="copySuccess" 
                     class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded-lg shadow-lg transition-all duration-300">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Error details copied to clipboard!
                    </div>
                </div>
            </div>
        </div>
    </NrnaLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import NrnaLayout from '@/Layouts/NrnaLayout.vue';

export default {
    name: 'VoteDenied',
    
    components: {
        Link,
        NrnaLayout,
    },

    props: {
        // Required props
        user_name: {
            type: String,
            required: true
        },
        
        // Error information
        denial_type: {
            type: String,
            default: 'Access Denied'
        },
        error_type: {
            type: String,
            default: null
        },
        title_english: {
            type: String,
            default: 'Vote Denied'
        },
        title_nepali: {
            type: String,
            default: null
        },
        error_message_english: {
            type: String,
            required: true
        },
        error_message_nepali: {
            type: String,
            default: null
        },
        solution_english: {
            type: String,
            default: null
        },
        solution_nepali: {
            type: String,
            default: null
        },

        // Technical details (optional - depends on error type)
        current_ip: {
            type: String,
            default: null
        },
        original_ip: {
            type: String,
            default: null
        },
        registered_ip: {
            type: String,
            default: null
        },
        votes_from_ip: {
            type: Number,
            default: null
        },
        max_votes_allowed: {
            type: Number,
            default: null
        },
        expired_minutes: {
            type: Number,
            default: null
        },
        time_limit: {
            type: Number,
            default: null
        },
        voting_start: {
            type: String,
            default: null
        },
        voting_end: {
            type: String,
            default: null
        },
        code_sent_at: {
            type: String,
            default: null
        }
    },

    data() {
        return {
            showTechnicalDetails: false,
            copySuccess: false,
        };
    },

    methods: {
        toggleTechnicalDetails() {
            this.showTechnicalDetails = !this.showTechnicalDetails;
        },

        getCurrentDateTime() {
            return new Date().toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        getCurrentYear() {
            return new Date().getFullYear();
        },

        generateReferenceId() {
            const timestamp = Date.now().toString(36);
            const random = Math.random().toString(36).substr(2, 5);
            return `VD-${timestamp}-${random}`.toUpperCase();
        },

        async copyErrorDetails() {
            const errorDetails = this.generateErrorDetailsText();
            
            try {
                await navigator.clipboard.writeText(errorDetails);
                this.copySuccess = true;
                setTimeout(() => {
                    this.copySuccess = false;
                }, 3000);
            } catch (err) {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = errorDetails;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                this.copySuccess = true;
                setTimeout(() => {
                    this.copySuccess = false;
                }, 3000);
            }
        },

        generateErrorDetailsText() {
            let details = `NRNA Election System - Vote Denied Report\n`;
            details += `Generated: ${this.getCurrentDateTime()}\n`;
            details += `Reference ID: ${this.generateReferenceId()}\n\n`;
            
            details += `User: ${this.user_name}\n`;
            details += `Denial Type: ${this.denial_type}\n`;
            details += `Error Type: ${this.error_type || 'N/A'}\n\n`;
            
            details += `Error Message (English):\n${this.error_message_english}\n\n`;
            
            if (this.error_message_nepali) {
                details += `Error Message (Nepali):\n${this.error_message_nepali}\n\n`;
            }
            
            if (this.solution_english) {
                details += `Solution (English):\n${this.solution_english}\n\n`;
            }
            
            if (this.solution_nepali) {
                details += `Solution (Nepali):\n${this.solution_nepali}\n\n`;
            }
            
            details += `Technical Details:\n`;
            if (this.current_ip) details += `Current IP: ${this.current_ip}\n`;
            if (this.original_ip) details += `Original IP: ${this.original_ip}\n`;
            if (this.registered_ip) details += `Registered IP: ${this.registered_ip}\n`;
            if (this.votes_from_ip !== null) details += `Votes from IP: ${this.votes_from_ip}\n`;
            if (this.max_votes_allowed !== null) details += `Max votes allowed: ${this.max_votes_allowed}\n`;
            if (this.expired_minutes !== null) details += `Expired minutes: ${this.expired_minutes}\n`;
            if (this.time_limit !== null) details += `Time limit: ${this.time_limit} minutes\n`;
            if (this.voting_start) details += `Voting hours: ${this.voting_start} - ${this.voting_end}\n`;
            
            details += `\nContact: election@nrna.org\n`;
            details += `Please include this report when contacting support.`;
            
            return details;
        }
    },

    mounted() {
        // Auto-show technical details for certain error types
        const technicalErrorTypes = [
            'ip_mismatch', 'ip_rate_limit', 'user_ip_mismatch', 
            'outside_voting_hours', 'multiple_sessions'
        ];
        
        if (technicalErrorTypes.includes(this.error_type)) {
            this.showTechnicalDetails = true;
        }
    }
};
</script>

<style scoped>
/* Custom animations */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

/* Ensure proper text rendering for Nepali text */
.nepali-text {
    font-family: 'Noto Sans Devanagari', 'Arial Unicode MS', sans-serif;
    line-height: 1.6;
}

/* Responsive improvements */
@media (max-width: 640px) {
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

/* Print styles */
@media print {
    .bg-gradient-to-br,
    .bg-red-50,
    .bg-orange-50,
    .bg-blue-50,
    .bg-green-50,
    .bg-gray-50 {
        background-color: white !important;
    }
    
    .shadow-lg,
    .shadow-sm {
        box-shadow: none !important;
    }
    
    button {
        display: none !important;
    }
}
</style>