<template>
    <election-layout>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <header class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">
                        निर्वाचन केन्द्र
                    </h1>
                    <p class="text-xl text-gray-600 mb-4">Election Center</p>
                    <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
                </header>

                <!-- Primary Actions Section -->
                <section class="mb-16" aria-labelledby="primary-actions">
                    <h2 id="primary-actions" class="text-3xl font-semibold text-gray-900 text-center mb-10">
                        मुख्य कार्यहरू | Main Actions
                    </h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
                        <!-- Vote Here - Dynamic based on eligibility and status -->
                        <div class="relative">
                            <component 
                                :is="canAccessVoting ? 'a' : 'div'"
                                :href="canAccessVoting ? votingLink : null"
                                :class="[
                                    'group relative overflow-hidden rounded-3xl p-10 text-white shadow-2xl transition-all duration-300',
                                    canAccessVoting 
                                        ? 'bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 hover:from-blue-700 hover:via-blue-800 hover:to-indigo-800 transform hover:scale-105 cursor-pointer focus:outline-none focus:ring-4 focus:ring-blue-300' 
                                        : 'bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 cursor-not-allowed'
                                ]"
                                :aria-label="votingAriaLabel"
                            >
                                <div class="relative z-10">
                                    <div class="flex items-center justify-center mb-6">
                                        <div class="bg-white/20 rounded-full p-6">
                                            <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 2h2v10h-2V5zm-2 4h2v6H9V9zm6-2h2v8h-2V7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-3xl font-bold text-center mb-3">{{ votingTitle }}</h3>
                                    <p class="text-xl text-center opacity-90 mb-2">{{ votingSubtitle }}</p>
                                    <p class="text-sm text-center opacity-75">{{ votingDescription }}</p>
                                    
                                    <!-- Session Timer (if active) -->
                                    <div v-if="votingStatus && votingStatus.voting_time_remaining > 0" class="mt-4 text-center">
                                        <div class="bg-white/20 rounded-lg p-3">
                                            <p class="text-sm font-semibold">समय बाँकी | Time Remaining</p>
                                            <p class="text-lg font-bold">{{ votingStatus.voting_time_remaining }} मिनेट | minutes</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-white/5 group-hover:bg-white/10 transition-colors duration-300"></div>
                            </component>
                            
                            <!-- Error/Status Message for Voting -->
                            <div v-if="!canAccessVoting" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-semibold text-red-800 mb-1">{{ ballotAccess.error_title }}</p>
                                        <p class="text-red-700 mb-1">{{ ballotAccess.error_message_nepali }}</p>
                                        <p class="text-red-700">{{ ballotAccess.error_message_english }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Election Results - Controlled by backend -->
                        <div class="relative">
                            <component
                                :is="electionStatus.results_published ? 'a' : 'div'"
                                :href="electionStatus.results_published ? 'election/result' : null"
                                :class="[
                                    'group relative overflow-hidden rounded-3xl p-10 text-white shadow-2xl transition-all duration-300',
                                    electionStatus.results_published
                                        ? 'bg-gradient-to-br from-green-600 via-green-700 to-emerald-700 hover:from-green-700 hover:via-green-800 hover:to-emerald-800 transform hover:scale-105 cursor-pointer focus:outline-none focus:ring-4 focus:ring-green-300'
                                        : 'bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 cursor-not-allowed'
                                ]"
                            >
                                <div class="relative z-10">
                                    <div class="flex items-center justify-center mb-6">
                                        <div class="bg-white/20 rounded-full p-6">
                                            <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16,11V3H8v6H2v12h20V11H16z M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-3xl font-bold text-center mb-3">चुनाव परिणाम</h3>
                                    <p class="text-xl text-center opacity-90 mb-2">Election Results</p>
                                    <p class="text-sm text-center opacity-75">
                                        {{ electionStatus.results_published ? 'परिणाम उपलब्ध' : 'परिणाम अनुपलब्ध' }}
                                    </p>
                                </div>
                            </component>
                            
                            <!-- Results Status Message -->
                            <div v-if="!electionStatus.results_published" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-semibold text-yellow-800 mb-1">परिणाम अझै उपलब्ध छैन | Results Not Available</p>
                                        <p class="text-yellow-700">निर्वाचन सम्पन्न भएपछि यो लिंक सक्रिय हुनेछ। This link will be active after the election is completed.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Candidate Information Section -->
                <section class="mb-16" aria-labelledby="candidate-info">
                    <h2 id="candidate-info" class="text-3xl font-semibold text-gray-900 mb-10">
                        उम्मेदवार सम्बन्धी जानकारी | Candidate Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Candidacy Posts -->
                        <a 
                            href="posts/index" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 border border-gray-100 hover:border-blue-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            <div class="text-center">
                                <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">उम्मेदवारीका पदहरू</h3>
                                <p class="text-gray-600">List of Candidacy Posts</p>
                            </div>
                        </a>

                        <!-- Candidacy List -->
                        <a 
                            href="candidacies/index" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 border border-gray-100 hover:border-purple-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-purple-200"
                        >
                            <div class="text-center">
                                <div class="bg-purple-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,2A3,3 0 0,1 15,5A3,3 0 0,1 12,8A3,3 0 0,1 9,5A3,3 0 0,1 12,2M21,9V7H15L13.5,7.5C13.1,7.4 12.6,7.5 12,7.5C11.4,7.5 10.9,7.4 10.5,7.5L9,7H3V9H9L10.5,9.5C10.9,9.6 11.4,9.5 12,9.5C12.6,9.5 13.1,9.6 13.5,9.5L15,9H21M12,10.5C11.2,10.5 10.5,11.2 10.5,12C10.5,12.8 11.2,13.5 12,13.5C12.8,13.5 13.5,12.8 13.5,12C13.5,11.2 12.8,10.5 12,10.5Z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">उम्मेदवारहरूको नामावली</h3>
                                <p class="text-gray-600">Candidacy List</p>
                            </div>
                        </a>

                        <!-- Candidacy Form -->
                        <a 
                            href="candidacy/create" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 border border-gray-100 hover:border-orange-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-orange-200"
                        >
                            <div class="text-center">
                                <div class="bg-orange-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-orange-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">उम्मेदवारी फारम</h3>
                                <p class="text-gray-600">Candidacy Form</p>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- Voter Information Section -->
                <section class="mb-16" aria-labelledby="voter-info">
                    <h2 id="voter-info" class="text-3xl font-semibold text-gray-900 mb-10">
                        मतदाता सम्बन्धी जानकारी | Voter Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Voter List -->
                        <a 
                            href="voters/index" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 border border-gray-100 hover:border-indigo-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-200"
                        >
                            <div class="text-center">
                                <div class="bg-indigo-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M16,4C16.88,4 17.67,4.84 17.67,5.84C17.67,6.84 16.88,7.68 16,7.68C15.12,7.68 14.33,6.84 14.33,5.84C14.33,4.84 15.12,4 16,4M16,8.48C18.67,8.48 20.33,10.5 20.33,12.85C20.33,15.2 18.67,17.22 16,17.22C13.33,17.22 11.67,15.2 11.67,12.85C11.67,10.5 13.33,8.48 16,8.48M16,9.68C14.12,9.68 12.67,11.04 12.67,12.85C12.67,14.66 14.12,16 16,16C17.88,16 19.33,14.66 19.33,12.85C19.33,11.04 17.88,9.68 16,9.68Z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">मतदाताहरूको नामावली</h3>
                                <p class="text-gray-600">Voter List</p>
                            </div>
                        </a>

                        <!-- Your Vote -->
                        <a 
                            href="vote/verify_to_show" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 border border-gray-100 hover:border-teal-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-teal-200"
                        >
                            <div class="text-center">
                                <div class="bg-teal-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-teal-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M10,17L5,12L6.41,10.59L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">तपाईंको मत</h3>
                                <p class="text-gray-600">Your Vote</p>
                            </div>
                        </a>

                        <!-- NRNA Members -->
                        <a 
                            href="users/index" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 border border-gray-100 hover:border-rose-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-rose-200"
                        >
                            <div class="text-center">
                                <div class="bg-rose-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-rose-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-rose-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2M4 18v-6h3v7H5.5c-.83 0-1.5-.67-1.5-1.5M22 22H10v-1h12v1M13.5 12.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5M5.5 6h2c.83 0 1.5.67 1.5 1.5V9H7v6H5V7.5C5 6.67 5.67 6 6.5 6"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">NRNA सदस्यहरू</h3>
                                <p class="text-gray-600">NRNA Members List</p>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- Administrative Section -->
                <section class="mb-16" aria-labelledby="admin-functions">
                    <h2 id="admin-functions" class="text-3xl font-semibold text-gray-900 mb-10">
                        प्रशासनिक कार्यहरू | Administrative Functions
                    </h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Election Committee -->
                        <a 
                            href="election/committee" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-10 border border-gray-100 hover:border-gray-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-gray-200"
                        >
                            <div class="flex items-center">
                                <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mr-8 group-hover:bg-gray-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">निर्वाचन कमिटी</h3>
                                    <p class="text-gray-600 text-lg">Election Committee</p>
                                </div>
                            </div>
                        </a>

                        <!-- General Information -->
                        <a 
                            href="#" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-10 border border-gray-100 hover:border-yellow-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-yellow-200"
                        >
                            <div class="flex items-center">
                                <div class="bg-yellow-100 rounded-full w-20 h-20 flex items-center justify-center mr-8 group-hover:bg-yellow-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">सामान्य जानकारी</h3>
                                    <p class="text-gray-600 text-lg">General Information</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- Help Section -->
                <section class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-12 text-center border border-blue-100">
                    <div class="max-w-3xl mx-auto">
                        <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-8">
                            <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,10.27 15.45,11.4 14.59,12.26L15.07,11.25M13,19H11V17H13V19Z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">सहायता चाहिएको छ? | Need Help?</h3>
                        <p class="text-gray-700 mb-8 text-lg leading-relaxed">
                            यदि तपाईंलाई कुनै सहायता चाहिएको छ भने, कृपया सम्पर्क गर्नुहोस्।<br>
                            If you need any assistance, please contact us.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-6 justify-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-4 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg">
                                📞 सम्पर्क गर्नुहोस् | Contact Us
                            </button>
                            <button class="bg-white hover:bg-blue-50 text-blue-600 border-2 border-blue-600 font-semibold px-8 py-4 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg">
                                📖 निर्देशिका | User Guide
                            </button>
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
        authUser: {
            type: Object,
            default: () => null
        },
        ballotAccess: {
            type: Object,
            default: () => ({
                can_access: false,
                error_title: '',
                error_message_nepali: '',
                error_message_english: ''
            })
        },
        votingStatus: {
            type: Object,
            default: () => null
        },
        electionStatus: {
            type: Object,
            default: () => ({
                is_active: false,
                results_published: false
            })
        }
    },
    
    computed: {
        /**
         * Check if user can access voting based on corrected architecture
         * Uses ballotAccess prop from backend (is_voter && can_vote)
         */
        canAccessVoting() {
            return this.ballotAccess && this.ballotAccess.can_access;
        },
        
        /**
         * Determine the appropriate voting link based on status
         */
        votingLink() {
            if (!this.canAccessVoting) return null;
            
            // If user has voted, go to vote verification
            if (this.votingStatus && this.votingStatus.has_voted) {
                return 'vote/verify_to_show';
            }
            
            // If voting session is active (can_vote_now), continue voting
            if (this.votingStatus && this.votingStatus.can_vote) {
                return 'vote/create';
            }
            
            // Otherwise, start with code generation
            return 'code/create';
        },
        
        /**
         * Dynamic voting title based on status
         */
        votingTitle() {
            if (!this.canAccessVoting) return 'मतदान अनुपलब्ध';
            
            if (this.votingStatus && this.votingStatus.has_voted) {
                return 'आफ्नो मत हेर्नुहोस्';
            }
            
            if (this.votingStatus && this.votingStatus.can_vote) {
                return 'मतदान जारी राख्नुहोस्';
            }
            
            return 'मतदान गर्नुहोस्';
        },
        
        /**
         * Dynamic voting subtitle
         */
        votingSubtitle() {
            if (!this.canAccessVoting) return 'Voting Unavailable';
            
            if (this.votingStatus && this.votingStatus.has_voted) {
                return 'View Your Vote';
            }
            
            if (this.votingStatus && this.votingStatus.can_vote) {
                return 'Continue Voting';
            }
            
            return 'Vote Here';
        },
        
        /**
         * Dynamic voting description
         */
        votingDescription() {
            if (!this.canAccessVoting) return 'मतदान अनुपलब्ध';
            
            if (this.votingStatus && this.votingStatus.has_voted) {
                return 'तपाईंले मतदान गरिसक्नुभएको छ';
            }
            
            if (this.votingStatus && this.votingStatus.can_vote_now) {
                return 'तपाईंको मतदान सत्र सक्रिय छ';
            }
            
            return 'यहाँ क्लिक गरेर मतदान गर्नुहोस्';
        },
        
        /**
         * ARIA label for voting section
         */
        votingAriaLabel() {
            if (!this.canAccessVoting) {
                return 'मतदान अनुपलब्ध - Voting not available';
            }
            
            if (this.votingStatus && this.votingStatus.has_voted) {
                return 'आफ्नो मत हेर्नुहोस् - View your vote';
            }
            
            return 'मतदान गर्नुहोस् - Click to vote';
        }
    },
    
    mounted() {
        this.setupAccessibility();
        
        // Auto-refresh timer if voting session is active
        if (this.votingStatus && this.votingStatus.can_vote_now && this.votingStatus.voting_time_remaining > 0) {
            this.startSessionTimer();
        }
    },
    
    methods: {
        setupAccessibility() {
            // Announce page load for screen readers
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.className = 'sr-only';
            announcement.textContent = 'निर्वाचन केन्द्र लोड भयो। Election Center loaded.';
            document.body.appendChild(announcement);
            
            setTimeout(() => {
                if (document.body.contains(announcement)) {
                    document.body.removeChild(announcement);
                }
            }, 1000);
        },
        
        startSessionTimer() {
            // Refresh page every minute to update remaining time
            setInterval(() => {
                if (this.votingStatus && this.votingStatus.voting_time_remaining > 0) {
                    // Could implement real-time countdown here
                    // For now, just refresh the page every 5 minutes to get updated data
                    if (this.votingStatus.voting_time_remaining % 5 === 0) {
                        window.location.reload();
                    }
                }
            }, 60000); // Every minute
        }
    }
};
</script>

<style scoped>
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

@media (prefers-reduced-motion: reduce) {
    .transition-all,
    .transition-colors {
        transition: none !important;
    }
    
    .transform,
    .hover\:scale-105:hover {
        transform: none !important;
    }
}

@media (prefers-contrast: high) {
    .border-gray-100 {
        border-color: #000000 !important;
        border-width: 2px !important;
    }
}
</style>