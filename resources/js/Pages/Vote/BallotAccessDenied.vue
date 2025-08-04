<template>
    <election-layout>
        <div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 py-8 px-4">
            <div class="max-w-2xl mx-auto">
                <!-- Header Section -->
                <header class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full shadow-lg mb-6">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        मतदान पहुँच निषेध
                    </h1>
                    <p class="text-xl text-gray-700 mb-1">Ballot Access Denied</p>
                    <p class="text-sm text-gray-600">NRNA Election System</p>
                </header>

                <!-- Main Error Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-red-200">
                    <!-- Error Header -->
                    <div class="bg-gradient-to-r from-red-100 to-orange-100 px-6 py-6 border-b border-red-200">
                        <div class="flex items-center">
                            <div class="bg-red-200 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-red-800">{{ error_title }}</h2>
                                <p class="text-red-700 text-sm">Access to voting system restricted</p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Details -->
                    <div class="px-6 py-8">
                        <!-- User Information -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">
                                प्रयोगकर्ताको जानकारी | User Information
                            </h3>
                            <div class="text-sm text-gray-600">
                                <p><span class="font-medium">नाम | Name:</span> {{ user_name }}</p>
                                <p><span class="font-medium">समय | Time:</span> {{ currentDateTime }}</p>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        <div class="space-y-4 mb-6">
                            <!-- Nepali Message -->
                            <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-semibold text-red-800 mb-1">नेपालीमा:</h4>
                                        <p class="text-sm text-red-700 leading-relaxed">{{ error_message_nepali }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- English Message -->
                            <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-semibold text-red-800 mb-1">In English:</h4>
                                        <p class="text-sm text-red-700 leading-relaxed">{{ error_message_english }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Next Steps Based on Error Type -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                के गर्ने? | What to do next?
                            </h3>
                            
                            <!-- Not a Voter -->
                            <div v-if="error_type === 'not_voter'" class="space-y-4">
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="font-semibold text-blue-800 mb-2">
                                        📝 मतदाता दर्ता | Voter Registration
                                    </h4>
                                    <p class="text-sm text-blue-700 mb-3">
                                        <strong>नेपाली:</strong> मतदान गर्न तपाईं पहिले मतदाताको रूपमा दर्ता हुनुपर्छ।
                                    </p>
                                    <p class="text-sm text-blue-700 mb-3">
                                        <strong>English:</strong> You need to register as a voter first before you can participate in voting.
                                    </p>
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <button 
                                            @click="contactSupport"
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300"
                                        >
                                            📞 दर्ताका लागि सम्पर्क | Contact for Registration
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Not Verified -->
                            <div v-if="error_type === 'not_verified'" class="space-y-4">
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <h4 class="font-semibold text-yellow-800 mb-2">
                                        ⏳ प्रमाणीकरण प्रतीक्षा | Verification Pending
                                    </h4>
                                    <p class="text-sm text-yellow-700 mb-3">
                                        <strong>नेपाली:</strong> तपाईं दर्ता भएका मतदाता हुनुहुन्छ तर निर्वाचन समितिले अझै प्रमाणीकरण गरेको छैन।
                                    </p>
                                    <p class="text-sm text-yellow-700 mb-3">
                                        <strong>English:</strong> You are a registered voter but the election committee has not yet verified your eligibility.
                                    </p>
                                    
                                    <div class="bg-yellow-100 p-3 rounded-md mb-3">
                                        <p class="text-xs text-yellow-800">
                                            <strong>कृपया धैर्य गर्नुहोस् | Please be patient:</strong><br>
                                            निर्वाचन समितिले तपाईंको विवरण जाँच गरेर चाँडै प्रमाणीकरण गर्नेछ।<br>
                                            The election committee will review and verify your details soon.
                                        </p>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <button 
                                            @click="checkStatus"
                                            class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-yellow-300"
                                        >
                                            🔄 स्थिति जाँच गर्नुहोस् | Check Status
                                        </button>
                                        <button 
                                            @click="contactCommittee"
                                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-yellow-300"
                                        >
                                            📧 समितिलाई सम्पर्क | Contact Committee
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alternative Actions -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                अन्य विकल्पहरू | Other Options
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- View Candidates -->
                                <a 
                                    href="/candidacies/index"
                                    class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300"
                                >
                                    <div class="bg-purple-100 rounded-full p-2 mr-3">
                                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12,2A3,3 0 0,1 15,5A3,3 0 0,1 12,8A3,3 0 0,1 9,5A3,3 0 0,1 12,2M21,9V7H15L13.5,7.5C13.1,7.4 12.6,7.5 12,7.5C11.4,7.5 10.9,7.4 10.5,7.5L9,7H3V9H9L10.5,9.5C10.9,9.6 11.4,9.5 12,9.5C12.6,9.5 13.1,9.6 13.5,9.5L15,9H21M12,10.5C11.2,10.5 10.5,11.2 10.5,12C10.5,12.8 11.2,13.5 12,13.5C12.8,13.5 13.5,12.8 13.5,12C13.5,11.2 12.8,10.5 12,10.5Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">उम्मेदवारहरू हेर्नुहोस्</p>
                                        <p class="text-xs text-gray-600">View Candidates</p>
                                    </div>
                                </a>

                                <!-- Election Information -->
                                <a 
                                    href="/posts/index"
                                    class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300"
                                >
                                    <div class="bg-blue-100 rounded-full p-2 mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">निर्वाचन जानकारी</p>
                                        <p class="text-xs text-gray-600">Election Information</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Return to Dashboard -->
                        <div class="mt-8 text-center">
                            <a 
                                href="/dashboard"
                                class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300 shadow-lg"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                ड्यासबोर्डमा फर्कनुहोस् | Return to Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="bg-gray-50 px-6 py-4 border-t">
                        <div class="text-center">
                            <p class="text-xs text-gray-600 leading-relaxed">
                                <span class="font-semibold">सहायता चाहिएको छ? | Need Help?</span><br>
                                समस्या समाधानका लागि निर्वाचन समितिलाई सम्पर्क गर्नुहोस्।<br>
                                Contact the election committee for assistance with this issue.
                            </p>
                            <div class="mt-3 flex flex-col sm:flex-row gap-2 justify-center">
                                <a 
                                    href="mailto:election@nrna.org"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1"
                                >
                                    📧 election@nrna.org
                                </a>
                                <span class="hidden sm:inline text-gray-400">|</span>
                                <a 
                                    href="tel:+1234567890"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1"
                                >
                                    📞 +49 123 456 7890
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </election-layout>
</template>

<script>
import ElectionLayout from "@/Layouts/ElectionLayout";

export default {
    name: 'BallotAccessDenied',
    
    components: {
        ElectionLayout,
    },
    
    props: {
        error_type: {
            type: String,
            required: true,
            validator: value => ['not_voter', 'not_verified'].includes(value)
        },
        error_title: {
            type: String,
            required: true
        },
        error_message_nepali: {
            type: String,
            required: true
        },
        error_message_english: {
            type: String,
            required: true
        },
        user_name: {
            type: String,
            default: 'Unknown User'
        }
    },
    
    data() {
        return {
            currentDateTime: this.getCurrentDateTime()
        };
    },
    
    mounted() {
        // Accessibility announcement
        this.announcePageLoad();
        
        // Log access denial for audit (if needed)
        this.logAccessDenial();
    },
    
    methods: {
        /**
         * Get current date and time in readable format
         */
        getCurrentDateTime() {
            const now = new Date();
            return now.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                timeZoneName: 'short'
            });
        },
        
        /**
         * Contact support for registration
         */
        contactSupport() {
            // Could open a modal, redirect to contact form, or open email client
            const subject = encodeURIComponent('NRNA Voter Registration Request');
            const body = encodeURIComponent(`Dear Election Committee,

I would like to register as a voter for the NRNA election.

My Details:
Name: ${this.user_name}
Time of Request: ${this.currentDateTime}

Please let me know what documents and information you need for registration.

Thank you.`);
            
            window.open(`mailto:election@nrna.org?subject=${subject}&body=${body}`);
        },
        
        /**
         * Check verification status
         */
        checkStatus() {
            // Refresh the page to get updated status
            // In a real app, this might make an API call
            window.location.reload();
        },
        
        /**
         * Contact election committee
         */
        contactCommittee() {
            const subject = encodeURIComponent('NRNA Voter Verification Inquiry');
            const body = encodeURIComponent(`Dear Election Committee,

I am a registered voter but my account shows as "not verified". Could you please check my verification status?

My Details:
Name: ${this.user_name}
Time of Request: ${this.currentDateTime}

Please let me know if you need any additional information.

Thank you.`);
            
            window.open(`mailto:election@nrna.org?subject=${subject}&body=${body}`);
        },
        
        /**
         * Announce page load for screen readers
         */
        announcePageLoad() {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'assertive');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = `मतदान पहुँच निषेध। ${this.error_title}। Ballot access denied. ${this.error_title}.`;
            document.body.appendChild(announcement);
            
            setTimeout(() => {
                if (document.body.contains(announcement)) {
                    document.body.removeChild(announcement);
                }
            }, 3000);
        },
        
        /**
         * Log access denial for audit purposes
         */
        logAccessDenial() {
            // In a real application, you might want to log this to analytics
            // or send to a logging service for audit purposes
            console.log('Ballot Access Denied:', {
                user: this.user_name,
                error_type: this.error_type,
                timestamp: new Date().toISOString(),
                user_agent: navigator.userAgent
            });
        }
    }
};
</script>

<style scoped>
/* Screen reader only utility class */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .border-red-200,
    .border-yellow-200,
    .border-gray-200 {
        border-color: #000000 !important;
        border-width: 2px !important;
    }
    
    .text-red-700,
    .text-yellow-700,
    .text-blue-700 {
        color: #000000 !important;
        font-weight: 700 !important;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .transition-all,
    .transition-colors {
        transition: none !important;
    }
}

/* Print styles */
@media print {
    .bg-gradient-to-br,
    .shadow-xl,
    .shadow-lg {
        background: white !important;
        box-shadow: none !important;
        border: 2px solid #000 !important;
    }
    
    /* Hide interactive elements when printing */
    button,
    .bg-gray-50.hover\:bg-gray-100 {
        display: none !important;
    }
}

/* Focus improvements for better accessibility */
button:focus,
a:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

/* Ensure good color contrast */
.text-red-700 {
    color: #b91c1c;
}

.text-yellow-700 {
    color: #a16207;
}

.text-blue-700 {
    color: #1d4ed8;
}
</style>