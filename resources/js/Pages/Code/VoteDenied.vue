<template>
    <nrna-layout>
        <app-layout>
            <div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-100 py-8">
                <div class="max-w-4xl mx-auto px-4">
                    
                    <!-- Denial Header -->
                    <div class="text-center mb-8">
                        <div class="bg-gradient-to-r from-red-500 to-orange-600 text-white py-6 px-8 rounded-xl shadow-lg">
                            <div class="text-4xl mb-3">🚫</div>
                            <h1 class="text-2xl font-bold mb-2">{{ title_english }}</h1>
                            <h2 class="text-xl mb-2">{{ title_nepali }}</h2>
                            <p class="text-lg opacity-90">{{ user_name }}, your voting access has been restricted.</p>
                            <p class="text-sm opacity-80">{{ user_name }}, तपाईंको मतदान पहुँच प्रतिबन्धित गरिएको छ।</p>
                        </div>
                    </div>

                    <!-- Denial Details -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-red-600 to-orange-600 text-white py-4 px-6">
                            <h2 class="text-xl font-bold text-center">{{ denial_type }} | मतदान अस्वीकृत</h2>
                        </div>
                        
                        <div class="p-8">
                            
                            <!-- Warning Icon and Type -->
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            <strong>Access Denied:</strong> {{ denial_type }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Message -->
                            <div class="space-y-4 mb-6">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Reason | कारण</h3>
                                    
                                    <div class="space-y-3 text-gray-700">
                                        <p class="leading-relaxed">
                                            {{ message_english }}
                                        </p>
                                        <p class="leading-relaxed text-sm">
                                            {{ message_nepali }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Technical Details (if rate limit) -->
                            <div v-if="denial_reason === 'ip_rate_limit'" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <h4 class="text-md font-semibold text-yellow-800 mb-2">Technical Details | प्राविधिक विवरण</h4>
                                <div class="text-sm text-yellow-700 space-y-1">
                                    <p><strong>Your IP Address:</strong> {{ client_ip }}</p>
                                    <p><strong>Votes from this IP:</strong> {{ votes_from_ip || 'Multiple' }}</p>
                                    <p><strong>Maximum allowed:</strong> {{ max_votes_allowed || 7 }}</p>
                                    <p class="text-xs mt-2 opacity-75">
                                        यो सुरक्षा उपाय मतदानमा दोहोरो मतदान र धोखाधडी रोक्न लागू गरिएको हो।
                                    </p>
                                </div>
                            </div>

                            <!-- IP Mismatch Details -->
                            <div v-if="denial_reason === 'ip_mismatch'" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <h4 class="text-md font-semibold text-blue-800 mb-2">Security Information | सुरक्षा जानकारी</h4>
                                <div class="text-sm text-blue-700 space-y-1">
                                    <p><strong>Current IP:</strong> {{ client_ip }}</p>
                                    <p><strong>Security Requirement:</strong> Same IP as registration</p>
                                    <p class="text-xs mt-2 opacity-75">
                                        यो सुरक्षा आवश्यकता मतदान प्रक्रियाको अखण्डता सुनिश्चित गर्न लागू गरिएको हो।
                                    </p>
                                </div>
                            </div>

                            <!-- Solution -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                <h4 class="text-md font-semibold text-green-800 mb-2">What can you do? | तपाईं के गर्न सक्नुहुन्छ?</h4>
                                <div class="text-sm text-green-700 space-y-2">
                                    <p>{{ solution_english }}</p>
                                    <p class="text-xs opacity-75">{{ solution_nepali }}</p>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="bg-gray-100 rounded-lg p-4 mb-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">Need Help? | सहायता चाहिन्छ?</h4>
                                <div class="text-sm text-gray-700 space-y-1">
                                    <p><strong>Election Committee Contact:</strong></p>
                                    <p>📧 Email: election@nrna.org</p>
                                    <p>📞 Phone: +49-XXX-XXXXXXX</p>
                                    <p class="text-xs mt-2 opacity-75">
                                        Please provide your NRNA ID ({{ nrna_id }}) when contacting support.
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="text-center space-y-4">
                                <button 
                                    @click="goToDashboard"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-lg py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105"
                                >
                                    <span class="mr-2">🏠</span>
                                    Go to Dashboard | ड्यासबोर्डमा जानुहोस्
                                </button>
                                
                                <button 
                                    @click="contactSupport"
                                    class="w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold text-lg py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105"
                                >
                                    <span class="mr-2">📞</span>
                                    Contact Support | सहायता सम्पर्क गर्नुहोस्
                                </button>
                            </div>

                            <!-- Footer Info -->
                            <div class="mt-8 text-center text-xs text-gray-500">
                                <p>Reference ID: {{ denial_reason }}-{{ Date.now() }}</p>
                                <p>NRNA ID: {{ nrna_id }}</p>
                                <p>Time: {{ new Date().toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </app-layout>
    </nrna-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import NrnaLayout from '@/Layouts/NrnaLayout'

export default {
    components: {
        AppLayout,
        NrnaLayout,
    },
    
    props: {
        user_name: String,
        nrna_id: String,
        client_ip: String,
        denial_reason: String,
        denial_type: String,
        title_english: String,
        title_nepali: String,
        message_english: String,
        message_nepali: String,
        solution_english: String,
        solution_nepali: String,
        votes_from_ip: Number,
        max_votes_allowed: Number,
    },
    
    methods: {
        goToDashboard() {
            this.$inertia.visit(route('dashboard'))
        },
        
        contactSupport() {
            // Open email client with pre-filled email
            const subject = encodeURIComponent(`Voting Access Issue - ${this.denial_type}`)
            const body = encodeURIComponent(`
Hello,

I am experiencing a voting access issue:

Name: ${this.user_name}
NRNA ID: ${this.nrna_id}
Issue Type: ${this.denial_type}
My IP: ${this.client_ip}
Time: ${new Date().toLocaleString()}

Details: ${this.message_english}

Please assist me with resolving this issue.

Thank you.
            `)
            
            window.location.href = `mailto:de.election@nrna.org?subject=${subject}&body=${body}`
        }
    }
}
</script>