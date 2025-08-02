<template>
   <nrna-layout> 
    <app-layout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <!-- Header Section with User Icon -->
                <div class="text-center">
                    <div class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full flex items-center justify-center mb-6 shadow-lg">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Voter Authentication
                    </h2>
                    <p class="text-lg text-gray-600 mb-1">
                        Welcome, please verify your identity
                    </p>
                    <p class="text-base text-gray-500">
                        स्वागतम्, कृपया आफ्नो पहिचान प्रमाणित गर्नुहोस्
                    </p>
                </div>

                <!-- User Info Card (if available) -->
                <div v-if="name || nrna_id" class="bg-white rounded-xl shadow-md p-4 border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p v-if="name" class="text-sm font-medium text-gray-900 truncate">
                                {{ name }}
                            </p>
                            <p v-if="nrna_id" class="text-xs text-gray-500">
                                ID: {{ nrna_id }}
                            </p>
                            <p v-if="state" class="text-xs text-blue-600">
                                📍 {{ state }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Eligible Voter
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <!-- Validation Errors -->
                    <div v-if="Object.keys(errors).length > 0" class="mb-6">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <jet-validation-errors />
                                </div>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Instructions with User Icons -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 bg-blue-600 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Voter Instructions / मतदाता निर्देशनहरू
                                    </h3>
                                    <div class="text-sm text-blue-700 space-y-2">
                                        <div class="flex items-start space-x-2">
                                            <span class="text-blue-500 mt-0.5">1.</span>
                                            <span>Enter the unique voting code sent to your registered email/SMS</span>
                                        </div>
                                        <div class="flex items-start space-x-2">
                                            <span class="text-blue-500 mt-0.5">2.</span>
                                            <span class="text-xs">तपाईंको दर्ता गरिएको इमेल/एसएमएसमा पठाइएको अद्वितीय मतदान कोड प्रविष्ट गर्नुहोस्</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Voting Code Input with User Icon -->
                        <div class="space-y-2">
                            <label for="voting_code" class="block text-sm font-medium text-gray-700 flex items-center">
                                <svg class="h-4 w-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Your Personal Voting Code / तपाईंको व्यक्तिगत मतदान कोड
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                </div>
                                <input 
                                    id="voting_code"
                                    v-model="form.voting_code"
                                    type="text"
                                    :class="[
                                        'block w-full pl-10 pr-12 py-4 text-lg font-mono border rounded-lg shadow-sm transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400',
                                        errors.voting_code 
                                            ? 'border-red-300 bg-red-50' 
                                            : 'border-gray-300 bg-white hover:border-gray-400'
                                    ]"
                                    placeholder="Enter your voting code here"
                                    autocomplete="off"
                                    spellcheck="false"
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg v-if="!errors.voting_code && form.voting_code" class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <svg v-else-if="errors.voting_code" class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Field-specific error -->
                            <div v-if="errors.voting_code" class="flex items-center mt-2">
                                <svg class="h-4 w-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-red-600">{{ errors.voting_code }}</span>
                            </div>
                        </div>

                        <!-- Submit Button with User Authentication Icon -->
                        <div class="pt-4">
                            <button 
                                type="submit" 
                                :disabled="form.processing || !form.voting_code"
                                :class="[
                                    'group relative w-full flex justify-center py-4 px-4 border border-transparent text-lg font-medium rounded-lg text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
                                    form.processing || !form.voting_code
                                        ? 'bg-gray-400 cursor-not-allowed'
                                        : 'bg-blue-600 hover:bg-blue-700 hover:shadow-lg transform hover:-translate-y-0.5'
                                ]"
                            >
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg v-if="form.processing" class="h-5 w-5 animate-spin text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <svg v-else class="h-5 w-5 text-blue-300 group-hover:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </span>
                                <span v-if="form.processing" class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.25-4.5l-.02.016A12.054 12.054 0 016.25 7.5M6.25 7.5a12 12 0 01-12 0v5.25"></path>
                                    </svg>
                                    Authenticating User...
                                </span>
                                <span v-else class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Authenticate & Proceed to Vote
                                </span>
                            </button>
                        </div>

                        <!-- Help Text with Support User Icon -->
                        <div class="text-center pt-4">
                            <div class="flex items-center justify-center text-sm text-gray-600 mb-2">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Need help? Contact our support team
                            </div>
                            <p class="text-xs text-gray-500 flex items-center justify-center">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                सहायता चाहिन्छ? हाम्रो सहयोग टोलीलाई सम्पर्क गर्नुहोस्
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Security Note with User Privacy Icons -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-yellow-600 rounded-full flex items-center justify-center">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Personal Security Notice
                            </h3>
                            <p class="text-sm text-yellow-700 mt-1">
                                🔐 Your voting code is unique and confidential. Do not share it with anyone.
                            </p>
                            <p class="text-xs text-yellow-600 mt-1">
                                🛡️ तपाईंको मतदान कोड अद्वितीय र गोप्य छ। यसलाई कसैसँग साझा नगर्नुहोस्।
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>    
    </nrna-layout> 
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3'
import JetValidationErrors from '@/Jetstream/ValidationErrors' 
import AppLayout from '@/Layouts/AppLayout'
import NrnaLayout from '@/Layouts/NrnaLayout'

export default {
    props:{
        name: String,
        nrna_id: String, 
        state: String,
        errors: Object,
    },   
    setup () {
        const form = useForm({
            voting_code: '',
        })

        function submit() {
            form.post('/codes')
        }

        return { form, submit }
    },    
    components:{
        NrnaLayout,
        AppLayout,
        JetValidationErrors
    }   
}
</script>