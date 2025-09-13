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
                <!-- Temporary debug info - remove after testing -->

                <!-- Primary Actions Section -->
                <section class="mb-16" aria-labelledby="primary-actions">
                    <h2 id="primary-actions" class="text-3xl font-semibold text-gray-900 text-center mb-10">
                        मुख्य कार्यहरू | Main Actions
                    </h2>
                        <div class="flex justify-center mb-6">
                        <a v-if="showStartButton"
                            href="/dashboard"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg shadow-lg animate-bounce hover:animate-none transition-all duration-300"
                        >
                            <span class="mr-2">▶️</span>
                            यहाँ क्लिक गरेर शुरू गर्नुहोस् | Start clicing here 
                        </a>
                    </div>
                        <!-- <div v-if="true" class="p-4 bg-gray-100 text-xs">
                        Debug: {{ debugVotingStatus }}
                    </div> -->
                    <!-- Add this temporarily to see which user is logged in -->
                    <!-- <div class="p-4 bg-yellow-100 text-xs">
                        Logged in as: {{ authUser?.id }} ({{ authUser?.name }})
                        Expected: 2908 (Demo 1 NRNA Germany)
                    </div>
                     -->
                    <div class="max-w-md mx-auto mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-blue-600 px-6 py-3">
                            <div class="flex items-center justify-center text-white">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm uppercase tracking-wide">System Information</span>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 space-y-3">
                            <!-- IP Address Section -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm0 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Client IP Address</p>
                                    <p class="text-sm font-bold text-gray-900 font-mono">{{ ipAddress }}</p>
                                </div>
                            </div>
                            
                            <!-- User Info Section -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Authenticated User</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        
                                        {{ authUser?.name }} 
                                        <span class="text-xs text-gray-800 font-medium">(ID: {{ authUser?.id }})</span>
                                    </p>
                                </div>
                                	
                            </div>
                            <!-- User Email with Icon -->
                                    <div class="flex items-center mt-1">
                                        <svg class="w-3 h-3 text-gray-400 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                        <span class="text-xs text-gray-600">{{ authUser?.email }}</span>
                                    </div>

                        </div>
                    </div>                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
                        <!-- 🗳️ VOTING SECTION - FIXED DISPLAY -->
                        <div class="relative w-full">
                            <!-- Voting Button/Card -->
                            <component 
                                :is="canAccessVoting ? 'a' : 'div'"
                                :href="canAccessVoting ? votingLink : undefined"
                                :class="votingCardClasses"
                                :aria-label="votingAriaLabel"
                                :tabindex="canAccessVoting ? 0 : -1"
                                @click="handleVotingClick"
                                @keydown.enter="handleVotingClick"
                                @keydown.space.prevent="handleVotingClick"
                            >
                                <div class="relative z-10 w-full">
                                    <!-- Voting Icon -->
                                    <div class="flex items-center justify-center mb-6">
                                        <div class="bg-white/20 rounded-full p-6">
                                            <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 2h2v10h-2V5zm-2 4h2v6H9V9zm6-2h2v8h-2V7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Voting Title & Description -->
                                    <h3 class="text-3xl font-bold text-center mb-3 text-white">{{ votingTitle }}</h3>
                                    <p class="text-xl text-center opacity-90 mb-2 text-white">{{ votingSubtitle }}</p>
                                    <p class="text-sm text-center opacity-75 text-white mb-4">{{ votingDescription }}</p>
                                    
                                    <!-- Session Timer (if active) -->
                                    <div v-if="showVotingTimer" class="mt-4 text-center">
                                        <div class="bg-white/20 rounded-lg p-3">
                                            <p class="text-sm font-semibold text-white">समय बाँकी | Time Remaining</p>
                                            <p class="text-lg font-bold text-white">{{ votingTimeRemaining }} मिनेट | minutes</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Access Status Indicator -->
                                    <div class="mt-4 text-center">
                                        <span v-if="canAccessVoting" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            पहुँच उपलब्ध | Access Available
                                        </span>
                                        <span v-else class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            पहुँच अनुपलब्ध | Access Unavailable
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Hover effect overlay -->
                                <div :class="canAccessVoting ? 'absolute inset-0 bg-white/5 group-hover:bg-white/10 transition-colors duration-300' : ''"></div>
                            </component>
                            
                            <!-- 🚨 ERROR MESSAGE - ONLY SHOWN WHEN ACCESS IS DENIED -->
                            <div v-if="!canAccessVoting && ballotAccess" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-semibold text-red-800 mb-1">{{ ballotAccess.error_title || 'मतदान अनुपलब्ध | Voting Unavailable' }}</p>
                                        <p v-if="ballotAccess.error_message_nepali" class="text-red-700 mb-1">{{ ballotAccess.error_message_nepali }}</p>
                                        <p v-if="ballotAccess.error_message_english" class="text-red-700">{{ ballotAccess.error_message_english }}</p>

                                        <!-- Additional helpful info -->
                                        <div class="mt-2 text-xs text-red-600 space-y-1">
                                            <p v-if="!authUser?.is_voter">• You are not registered as a voter</p>
                                            <p v-if="authUser?.is_voter && !authUser?.can_vote">• Your voter status is pending approval</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 🔒 VOTING PERIOD INACTIVE MESSAGE - SHOWN WHEN USER IS ELIGIBLE BUT VOTING PERIOD IS INACTIVE -->
                            <div v-if="!canAccessVoting && ballotAccess?.can_access && !electionStatus?.voting_period_active && !votingStatus?.has_voted" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-semibold text-yellow-800 mb-1">⏱️ मतदान अवधि सक्रिय छैन | Voting Period Not Active</p>
                                        <p class="text-yellow-700 mb-1">तपाईं मतदान गर्न योग्य हुनुहुन्छ तर मतदान अवधि अझै सुरु भएको छैन।</p>
                                        <p class="text-yellow-700">You are eligible to vote, but the voting period has not started yet.</p>

                                        <!-- Additional helpful info -->
                                        <div class="mt-2 text-xs text-yellow-600">
                                            <p>• निर्वाचन प्रशासकले मतदान सुरु गरेपछि यो बटन सक्रिय हुनेछ</p>
                                            <p>• This button will become active when voting is started by election administrators</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Election Results - Controlled by backend -->
                        <div class="relative w-full">
                            <component
                                :is="electionStatus.results_published ? 'a' : 'div'"
                                :href="electionStatus.results_published ? getResultsRoute() : undefined"
                                :class="resultsCardClasses"
                            >
                                <div class="relative z-10 w-full">
                                    <div class="flex items-center justify-center mb-6">
                                        <div class="bg-white/20 rounded-full p-8">
                                            <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16,11V3H8v6H2v12h20V11H16z M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"/>
                                            </svg>
                                           <div class="text-center mx-auto text-xxl">🔒 </div> 
                                        </div>
                                    </div>
                                    <h3 class="text-3xl font-bold text-center mb-3 text-white">चुनाव परिणाम</h3>
                                    <p class="text-xl text-center opacity-90 mb-2 text-white">Election Results</p>
                                    <p class="text-sm text-center opacity-75 text-white">
                                        {{ electionStatus.results_published ? 'परिणाम उपलब्ध | Results Available' : '🔒  परिणाम अनुपलब्ध | Results Unavailable' }}
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
                    <h2 id="candidate-info" class="text-3xl font-semibold text-gray-900 text-center mb-10">
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
                    <h2 id="voter-info" class="text-3xl font-semibold text-gray-900 text-center mb-10">
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
                    <h2 id="admin-functions" class="text-3xl font-semibold text-gray-900 text-center mb-10">
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
        ipAddress: '', 
        ballotAccess: {
            type: [Object, null],
            default: () => null
        },
        votingStatus: {
            type: [Object, null],
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
        // Add this to computed properties in ElectionDashboard.vue
        debugVotingStatus() {
            return {
                has_ballot_access: this.canAccessVoting,
                has_code_record: this.votingStatus?.has_code,
                has_voted_per_code: this.votingStatus?.has_voted,
                can_vote_now_per_code: this.votingStatus?.can_vote_now,
                determined_link: this.votingLink
            };
            

        },

        showStartButton() {
                const noAuthUser = this.authUser === undefined || this.authUser === null;
                const noIpAddress = this.ipAddress === null || this.ipAddress === '';
                const noUserEmail = !this.authUser?.email;

                if (noAuthUser || noIpAddress || noUserEmail) {
                    return true;
                }
                if (this.$page.url === '/election'){
                    return true;
                }
                return false;
            },

        /**
         * ✅ ROBUST: Check if user can access voting
         * Users can access voting in two scenarios:
         * 1. They can vote (all conditions met + voting period active)
         * 2. They have already voted (can view their vote regardless of voting period)
         */
        canAccessVoting() {
            if (!this.ballotAccess || typeof this.ballotAccess !== 'object') {
                return false;
            }

            const canAccess = this.ballotAccess.can_access;
            let hasAccess = false;

            // Handle different data types for can_access
            if (typeof canAccess === 'boolean') hasAccess = canAccess;
            if (typeof canAccess === 'string') hasAccess = canAccess === 'true' || canAccess === '1';
            if (typeof canAccess === 'number') hasAccess = canAccess === 1;

            if (!hasAccess) {
                // Special case: If user has voted, they can still view their vote
                // even if voting period is inactive
                if (this.votingStatus?.has_voted) {
                    return true;
                }
                return false;
            }

            // User has access - now check if it's for voting or viewing
            // If user has already voted, they can always access (to view vote)
            if (this.votingStatus?.has_voted) {
                return true;
            }

            // For new voting, check if voting period is active
            if (!this.electionStatus?.voting_period_active) {
                return false;
            }

            return true;
        },
        
        /**
         * ✅ IMPROVED: Dynamic CSS classes for voting card
         */
        votingCardClasses() {
            const baseClasses = 'group relative overflow-hidden rounded-3xl p-10 text-white shadow-2xl transition-all duration-300 w-full min-h-[400px] flex flex-col justify-center';
            
            if (this.canAccessVoting) {
                return `${baseClasses} bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 hover:from-blue-700 hover:via-blue-800 hover:to-indigo-800 transform hover:scale-105 cursor-pointer focus:outline-none focus:ring-4 focus:ring-blue-300`;
            } else {
                return `${baseClasses} bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 cursor-not-allowed opacity-75`;
            }
        },
        
        /**
         * ✅ IMPROVED: Dynamic CSS classes for results card  
         */
        resultsCardClasses() {
            const baseClasses = 'group relative overflow-hidden rounded-3xl p-10 text-white shadow-2xl transition-all duration-300 w-full min-h-[400px] flex flex-col justify-center';
            
            if (this.electionStatus.results_published) {
                return `${baseClasses} bg-gradient-to-br from-green-600 via-green-700 to-emerald-700 hover:from-green-700 hover:via-green-800 hover:to-emerald-800 transform hover:scale-105 cursor-pointer focus:outline-none focus:ring-4 focus:ring-green-300`;
            } else {
                return `${baseClasses} bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 cursor-not-allowed opacity-75`;
            }
        },
        
        /**
         * ✅ Determine the appropriate voting link
         */
        votingLink() {
            if (!this.canAccessVoting) return '#';
            
            // If user has voted, go to vote verification
            if (this.votingStatus?.has_voted) {
                console.log("vote/show")
                
                return this.route ? this.route('vote.verify_to_show') : 'vote/verify_to_show';
            }
            
            // If voting session is active, continue voting
            if (this.votingStatus?.can_vote_now) {
                console.log("vote/Create")
                return this.route ? this.route('vote.create') : 'vote/create';

            }
            console.log("Code/Create")
                
            // Otherwise, start with code generation
            return this.route ? this.route('code.create') : 'code/create';
        },
        
        /**
         * Dynamic voting title based on status
         */
        votingTitle() {
            if (this.votingStatus?.has_voted) {
                return 'आफ्नो मत हेर्नुहोस्';
            }

            if (!this.canAccessVoting) {
                // Check if it's because voting period is inactive
                if (this.ballotAccess?.can_access && !this.electionStatus?.voting_period_active) {
                    return 'मतदान अवधि निष्क्रिय';
                }
                return 'मतदान अनुपलब्ध';
            }

            if (this.votingStatus?.can_vote_now) {
                return 'मतदान जारी राख्नुहोस्';
            }

            return 'मतदान गर्नुहोस्';
        },
        
        /**
         * Dynamic voting subtitle
         */
        votingSubtitle() {
            if (this.votingStatus?.has_voted) {
                return 'View Your Vote';
            }

            if (!this.canAccessVoting) {
                // Check if it's because voting period is inactive
                if (this.ballotAccess?.can_access && !this.electionStatus?.voting_period_active) {
                    return 'Voting Period Inactive';
                }
                return 'Voting Unavailable';
            }

            if (this.votingStatus?.can_vote_now) {
                return 'Continue Voting';
            }

            return 'Vote Here';
        },
        
        /**
         * Dynamic voting description
         */
        votingDescription() {
            if (this.votingStatus?.has_voted) {
                return 'तपाईंले मतदान गरिसक्नुभएको छ';
            }

            if (!this.canAccessVoting) {
                // Check if it's because voting period is inactive
                if (this.ballotAccess?.can_access && !this.electionStatus?.voting_period_active) {
                    return 'मतदान अवधि सुरु भएको छैन वा समाप्त भएको छ';
                }
                return 'मतदान गर्न सकिने अवस्था छैन';
            }

            if (this.votingStatus?.can_vote_now) {
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
            
            if (this.votingStatus?.has_voted) {
                return 'आफ्नो मत हेर्नुहोस् - View your vote';
            }
            
            return 'मतदान गर्नुहोस् - Click to vote';
        },
        
        /**
         * Show voting timer condition
         */
        showVotingTimer() {
            return this.votingStatus && 
                   this.votingStatus.can_vote_now && 
                   this.votingStatus.voting_time_remaining > 0;
        },
        
        /**
         * Formatted voting time remaining
         */
        votingTimeRemaining() {
            return this.votingStatus?.voting_time_remaining || 0;
        }
    },
    
    mounted() {
        this.setupAccessibility();
        
        // Auto-refresh timer if voting session is active
        if (this.showVotingTimer) {
            this.startSessionTimer();
        }
    },
    
    methods: {
        /**
         * ✅ Handle voting card clicks with better UX
         */
        handleVotingClick(event) {
            if (!this.canAccessVoting) {
                event.preventDefault();
                event.stopPropagation();
                
                // Show helpful feedback by highlighting error message
                this.$nextTick(() => {
                    const errorDiv = document.querySelector('.bg-red-50');
                    if (errorDiv) {
                        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        errorDiv.classList.add('ring-2', 'ring-red-400', 'ring-opacity-75');
                        setTimeout(() => {
                            errorDiv.classList.remove('ring-2', 'ring-red-400', 'ring-opacity-75');
                        }, 3000);
                    }
                });
                
                return false;
            }
            
            // For keyboard events, navigate programmatically
            if (event.type === 'keydown' && this.votingLink && this.votingLink !== '#') {
                window.location.href = this.votingLink;
            }
        },
        
        /**
         * Setup accessibility features
         */
        setupAccessibility() {
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
        
        /**
         * Start session timer for active voting sessions
         */
        startSessionTimer() {
            setInterval(() => {
                if (this.votingStatus && this.votingStatus.voting_time_remaining > 0) {
                    if (this.votingStatus.voting_time_remaining % 5 === 0) {
                        window.location.reload();
                    }
                }
            }, 60000);
        },
        
        /**
         * Helper method for route generation
         */
        route(name) {
            if (typeof route !== 'undefined') {
                return route(name);
            }
            return name;
        },

        /**
         * Safe method to get results route - avoid Ziggy route helper entirely
         */
        getResultsRoute() {
            // Don't use route() helper at all to avoid Ziggy errors
            // Use direct URL path based on publication status
            if (this.electionStatus && this.electionStatus.results_published) {
                return '/election/result';
            }
            return '#';
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

/* Improved focus styles */
.focus\:ring-4:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.5);
}

/* Ensure cards maintain proper sizing */
.min-h-\[400px\] {
    min-height: 400px;
}

/* Animation improvements */
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

/* High contrast mode */
@media (prefers-contrast: high) {
    .border-gray-100 {
        border-color: #000000 !important;
        border-width: 2px !important;
    }
}

/* Error highlight animation */
.ring-2 {
    animation: highlight 3s ease-in-out;
}

@keyframes highlight {
    0%, 100% { transform: scale(1); }
    10%, 90% { transform: scale(1.02); }
}

/* Ensure proper grid layout */
.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

@media (min-width: 1024px) {
    .lg\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Ensure cards are properly sized */
.relative.w-full {
    width: 100%;
}
</style>