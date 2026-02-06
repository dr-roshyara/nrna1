<template>
    <election-layout>
        <!-- ACCESSIBILITY: Skip link -->
        <a href="#main-content" class="skip-link">
            {{ $t('pages.election-dashboard.aria_labels.skip_to_content') }}
        </a>

        <main id="main-content" role="main" :aria-label="$t('pages.election-dashboard.aria_labels.main_content')" class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-indigo-100 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Primary Actions Section -->
                <section class="mb-16" aria-labelledby="primary-actions" role="region">
                    <h2 id="primary-actions" class="text-3xl font-semibold text-gray-900 text-center mb-10">
                        {{ $t('pages.election-dashboard.primary_actions.section_title') }}
                    </h2>
                    <div class="flex justify-center mb-6">
                        <a v-if="showStartButton"
                            href="/dashboard"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg shadow-lg animate-bounce hover:animate-none transition-all duration-300"
                        >
                            <span class="mr-2">▶️</span>
                            {{ $t('pages.election-dashboard.start_button.text') }}
                        </a>
                    </div>

                    <div class="max-w-md mx-auto mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-lg overflow-hidden" role="region" aria-labelledby="system-info-title">
                        <div class="bg-blue-600 px-6 py-3">
                            <div class="flex items-center justify-center text-white">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span id="system-info-title" class="font-semibold text-sm uppercase tracking-wide">{{ $t('pages.election-dashboard.system_info.title') }}</span>
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
                                    <p class="text-xs font-medium text-gray-700 uppercase tracking-wide">{{ $t('pages.election-dashboard.system_info.ip_label') }}</p>
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
                                    <p class="text-xs font-medium text-gray-700 uppercase tracking-wide">{{ $t('pages.election-dashboard.system_info.user_label') }}</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ authUser?.name }}
                                        <span class="text-xs text-gray-900 font-medium">(ID: {{ authUser?.id }})</span>
                                    </p>
                                </div>
                            </div>
                            <!-- User Email with Icon -->
                            <div class="flex items-center mt-1">
                                <svg class="w-3 h-3 text-gray-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span class="text-xs text-gray-700" :aria-label="$t('pages.election-dashboard.system_info.email_icon_alt')">{{ authUser?.email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-5xl mx-auto">
                        <!-- 🗳️ VOTING SECTION -->
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
                                class="group"
                            >
                                <div class="relative z-10 w-full h-full min-h-[400px] flex flex-col justify-center">
                                    <!-- Voting Icon -->
                                    <div class="flex items-center justify-center mb-6">
                                        <div class="bg-white/30 rounded-full p-6 group-hover:bg-white/40 transition-colors duration-300">
                                            <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 2h2v10h-2V5zm-2 4h2v6H9V9zm6-2h2v8h-2V7z"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Voting Title & Description -->
                                    <h3 class="text-3xl font-bold text-center mb-3 text-white">{{ votingTitle }}</h3>
                                    <p class="text-lg text-center text-white/95 mb-2 font-medium">{{ votingSubtitle }}</p>
                                    <p class="text-sm text-center text-white/80 mb-4">{{ votingDescription }}</p>

                                    <!-- Session Timer (if active) -->
                                    <div v-if="showVotingTimer" class="mt-4 text-center" aria-live="polite" aria-atomic="true">
                                        <div class="bg-white/30 rounded-lg p-3 backdrop-blur-sm">
                                            <p class="text-sm font-semibold text-white">{{ $t('pages.election-dashboard.voting_section.time_remaining_label') }}</p>
                                            <p class="text-lg font-bold text-white">{{ votingTimeRemaining }} {{ $t('pages.election-dashboard.voting_section.minutes_label') }}</p>
                                        </div>
                                    </div>

                                    <!-- Access Status Indicator -->
                                    <div class="mt-4 text-center">
                                        <span v-if="canAccessVoting" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-white text-blue-800 shadow-lg">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $t('pages.election-dashboard.voting_section.access_available') }}
                                        </span>
                                        <span v-else class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-600 text-white">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $t('pages.election-dashboard.voting_section.access_unavailable') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Hover effect overlay -->
                                <div :class="canAccessVoting ? 'absolute inset-0 bg-white/5 group-hover:bg-white/10 transition-colors duration-300' : ''"></div>
                            </component>

                            <!-- 🚨 ERROR MESSAGE -->
                            <div v-if="!canAccessVoting && ballotAccess" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-semibold text-red-800 mb-1">
                                            {{ getErrorTitle() }}
                                        </p>
                                        <p class="text-red-700">
                                            {{ getErrorMessage() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- 🔒 VOTING PERIOD INACTIVE MESSAGE -->
                            <div v-if="!canAccessVoting && ballotAccess?.can_access && !electionStatus?.voting_period_active && !votingStatus?.has_voted" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm">
                                        <p class="font-semibold text-yellow-800 mb-1">{{ $t('pages.election-dashboard.voting_section.period_inactive_title') }}</p>
                                        <p class="text-yellow-700 mb-1">{{ $t('pages.election-dashboard.voting_section.period_inactive_msg1') }}</p>

                                        <!-- Additional helpful info -->
                                        <div class="mt-2 text-xs text-yellow-600">
                                            <p>{{ $t('pages.election-dashboard.voting_section.period_inactive_msg2') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Election Results -->
                        <div class="relative w-full">
                            <component
                                :is="electionStatus.results_published ? 'a' : 'div'"
                                :href="electionStatus.results_published ? getResultsRoute() : undefined"
                                :class="resultsCardClasses"
                                class="group"
                            >
                                <div class="relative z-10 w-full h-full min-h-[400px] flex flex-col justify-center">
                                    <div class="flex items-center justify-center mb-6">
                                        <div class="bg-white/30 rounded-full p-6 group-hover:bg-white/40 transition-colors duration-300">
                                            <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M16,11V3H8v6H2v12h20V11H16z M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-3xl font-bold text-center mb-3 text-white">{{ $t('pages.election-dashboard.results_section.title') }}</h3>
                                    <p class="text-sm text-center text-white/80">
                                        {{ electionStatus.results_published ? $t('pages.election-dashboard.results_section.available') : $t('pages.election-dashboard.results_section.unavailable') }}
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
                                        <p class="font-semibold text-yellow-800 mb-1">{{ $t('pages.election-dashboard.results_section.not_published_title') }}</p>
                                        <p class="text-yellow-700">{{ $t('pages.election-dashboard.results_section.not_published_msg') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Candidate Information Section -->
                <section class="mb-16" aria-labelledby="candidate-info" role="region">
                    <h2 id="candidate-info" class="text-3xl font-semibold text-gray-900 text-center mb-10">
                        {{ $t('pages.election-dashboard.candidate_info.section_title') }}
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
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $t('pages.election-dashboard.candidate_info.posts_title') }}</h3>
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
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $t('pages.election-dashboard.candidate_info.list_title') }}</h3>
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
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $t('pages.election-dashboard.candidate_info.form_title') }}</h3>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- Voter Information Section -->
                <section class="mb-16" aria-labelledby="voter-info" role="region">
                    <h2 id="voter-info" class="text-3xl font-semibold text-gray-900 text-center mb-10">
                        {{ $t('pages.election-dashboard.voter_info.section_title') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Voter List -->
                        <a
                            href="voters"
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 border border-gray-100 hover:border-indigo-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-200"
                        >
                            <div class="text-center">
                                <div class="bg-indigo-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-200 transition-colors duration-300">
                                    <svg class="w-10 h-10 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M16,4C16.88,4 17.67,4.84 17.67,5.84C17.67,6.84 16.88,7.68 16,7.68C15.12,7.68 14.33,6.84 14.33,5.84C14.33,4.84 15.12,4 16,4M16,8.48C18.67,8.48 20.33,10.5 20.33,12.85C20.33,15.2 18.67,17.22 16,17.22C13.33,17.22 11.67,15.2 11.67,12.85C11.67,10.5 13.33,8.48 16,8.48M16,9.68C14.12,9.68 12.67,11.04 12.67,12.85C12.67,14.66 14.12,16 16,16C17.88,16 19.33,14.66 19.33,12.85C19.33,11.04 17.88,9.68 16,9.68Z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $t('pages.election-dashboard.voter_info.list_title') }}</h3>
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
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $t('pages.election-dashboard.voter_info.your_vote_title') }}</h3>
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
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $t('pages.election-dashboard.voter_info.members_title') }}</h3>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- Administrative Section -->
                <section class="mb-16" aria-labelledby="admin-functions" role="region">
                    <h2 id="admin-functions" class="text-3xl font-semibold text-gray-900 text-center mb-10">
                        {{ $t('pages.election-dashboard.admin_functions.section_title') }}
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
                                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ $t('pages.election-dashboard.admin_functions.committee_title') }}</h3>
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
                                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ $t('pages.election-dashboard.admin_functions.general_info_title') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- Help Section -->
                <section class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-12 text-center border border-blue-100" aria-label="Help and Support">
                    <div class="max-w-3xl mx-auto">
                        <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-8">
                            <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,10.27 15.45,11.4 14.59,12.26L15.07,11.25M13,19H11V17H13V19Z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">{{ $t('pages.election-dashboard.help_section.title') }}</h3>
                        <p class="text-gray-700 mb-8 text-lg leading-relaxed">
                            {{ $t('pages.election-dashboard.help_section.description') }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-6 justify-center">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-4 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg">
                                {{ $t('pages.election-dashboard.help_section.contact_button') }}
                            </button>
                            <button class="bg-white hover:bg-blue-50 text-blue-600 border-2 border-blue-600 font-semibold px-8 py-4 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg">
                                {{ $t('pages.election-dashboard.help_section.guide_button') }}
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </main>
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
        },
        useSlugPath: {
            type: Boolean,
            default: false
        },
        realElectionSlug: {
            type: String,
            default: null
        }
    },

    computed: {
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

        canAccessVoting() {
            if (!this.ballotAccess || typeof this.ballotAccess !== 'object') {
                return false;
            }

            const canAccess = this.ballotAccess.can_access;
            let hasAccess = false;

            if (typeof canAccess === 'boolean') hasAccess = canAccess;
            if (typeof canAccess === 'string') hasAccess = canAccess === 'true' || canAccess === '1';
            if (typeof canAccess === 'number') hasAccess = canAccess === 1;

            if (!hasAccess) {
                if (this.votingStatus?.has_voted) {
                    return true;
                }
                return false;
            }

            if (this.votingStatus?.has_voted) {
                return true;
            }

            if (!this.electionStatus?.voting_period_active) {
                return false;
            }

            return true;
        },

        votingCardClasses() {
            const baseClasses = 'group relative overflow-hidden rounded-3xl p-10 text-white shadow-2xl transition-all duration-300 w-full min-h-[400px] flex flex-col justify-center';

            if (this.canAccessVoting) {
                return `${baseClasses} bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 hover:from-blue-700 hover:via-blue-800 hover:to-indigo-800 transform hover:scale-105 cursor-pointer focus:outline-none focus:ring-4 focus:ring-blue-300`;
            } else {
                return `${baseClasses} bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 cursor-not-allowed opacity-75`;
            }
        },

        resultsCardClasses() {
            const baseClasses = 'group relative overflow-hidden rounded-3xl p-10 text-white shadow-2xl transition-all duration-300 w-full min-h-[400px] flex flex-col justify-center';

            if (this.electionStatus.results_published) {
                return `${baseClasses} bg-gradient-to-br from-green-600 via-green-700 to-emerald-700 hover:from-green-700 hover:via-green-800 hover:to-emerald-800 transform hover:scale-105 cursor-pointer focus:outline-none focus:ring-4 focus:ring-green-300`;
            } else {
                return `${baseClasses} bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 cursor-not-allowed opacity-75`;
            }
        },

        votingLink() {
            if (!this.canAccessVoting) return '#';

            if (this.votingStatus?.has_voted) {
                console.log("vote/verify_to_show")
                return this.route ? this.route('vote.verify_to_show') : 'vote/verify_to_show';
            }

            if (this.useSlugPath && this.realElectionSlug) {
                console.log("Start voting with slug:", this.realElectionSlug)
                return this.route ? this.route('slug.code.create', { vslug: this.realElectionSlug }) : `/v/${this.realElectionSlug}/code/create`;
            } else if (this.realElectionSlug) {
                console.log("Start voting with slug (fallback):", this.realElectionSlug)
                return this.route ? this.route('slug.code.create', { vslug: this.realElectionSlug }) : `/v/${this.realElectionSlug}/code/create`;
            } else {
                console.log("No election slug available")
                return '#';
            }
        },

        votingTitle() {
            if (this.votingStatus?.has_voted) {
                return this.$t('pages.election-dashboard.voting_section.title_view');
            }

            if (!this.canAccessVoting) {
                if (this.ballotAccess?.can_access && !this.electionStatus?.voting_period_active) {
                    return this.$t('pages.election-dashboard.voting_section.title_inactive');
                }
                return this.$t('pages.election-dashboard.voting_section.title_unavailable');
            }

            if (this.votingStatus?.can_vote_now) {
                return this.$t('pages.election-dashboard.voting_section.continue');
            }

            return this.$t('pages.election-dashboard.voting_section.title');
        },

        votingSubtitle() {
            if (this.votingStatus?.has_voted) {
                return this.$t('pages.election-dashboard.voting_section.title_view');
            }

            if (!this.canAccessVoting) {
                if (this.ballotAccess?.can_access && !this.electionStatus?.voting_period_active) {
                    return this.$t('pages.election-dashboard.voting_section.title_inactive');
                }
                return this.$t('pages.election-dashboard.voting_section.title_unavailable');
            }

            if (this.votingStatus?.can_vote_now) {
                return this.$t('pages.election-dashboard.voting_section.continue');
            }

            return this.$t('pages.election-dashboard.voting_section.title');
        },

        votingDescription() {
            if (this.votingStatus?.has_voted) {
                return this.$t('pages.election-dashboard.voting_section.already_voted');
            }

            if (!this.canAccessVoting) {
                if (this.ballotAccess?.can_access && !this.electionStatus?.voting_period_active) {
                    return this.$t('pages.election-dashboard.voting_section.period_not_started');
                }
                return this.$t('pages.election-dashboard.voting_section.not_available');
            }

            if (this.votingStatus?.can_vote_now) {
                return this.$t('pages.election-dashboard.voting_section.session_active');
            }

            return this.$t('pages.election-dashboard.voting_section.click_to_vote');
        },

        votingAriaLabel() {
            if (!this.canAccessVoting) {
                return this.$t('pages.election-dashboard.aria_labels.voting_unavailable');
            }

            if (this.votingStatus?.has_voted) {
                return this.$t('pages.election-dashboard.aria_labels.your_vote_link');
            }

            return this.$t('pages.election-dashboard.aria_labels.voting_available');
        },

        showVotingTimer() {
            return this.votingStatus &&
                   this.votingStatus.can_vote_now &&
                   this.votingStatus.voting_time_remaining > 0;
        },

        votingTimeRemaining() {
            return this.votingStatus?.voting_time_remaining || 0;
        }
    },

    mounted() {
        this.setupAccessibility();

        if (this.showVotingTimer) {
            this.startSessionTimer();
        }
    },

    methods: {
        handleVotingClick(event) {
            if (!this.canAccessVoting) {
                event.preventDefault();
                event.stopPropagation();

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

            if (event.type === 'keydown' && this.votingLink && this.votingLink !== '#') {
                window.location.href = this.votingLink;
            }
        },

        setupAccessibility() {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.className = 'sr-only';
            announcement.textContent = this.$t('pages.election-dashboard.aria_labels.page_loaded');
            document.body.appendChild(announcement);

            setTimeout(() => {
                if (document.body.contains(announcement)) {
                    document.body.removeChild(announcement);
                }
            }, 1000);
        },

        startSessionTimer() {
            setInterval(() => {
                if (this.votingStatus && this.votingStatus.voting_time_remaining > 0) {
                    if (this.votingStatus.voting_time_remaining % 5 === 0) {
                        window.location.reload();
                    }
                }
            }, 60000);
        },

        route(name) {
            if (typeof route !== 'undefined') {
                return route(name);
            }
            return name;
        },

        getResultsRoute() {
            if (this.electionStatus && this.electionStatus.results_published) {
                return '/election/result';
            }
            return '#';
        },

        getErrorTitle() {
            // Use translation-based error title based on user status
            if (!this.authUser?.is_voter) {
                return this.$t('pages.election-dashboard.voting_section.error_not_registered_title');
            }
            if (this.authUser?.is_voter && !this.authUser?.can_vote) {
                return this.$t('pages.election-dashboard.voting_section.error_approval_pending_title');
            }
            // Fallback to backend error title or default
            return this.ballotAccess.error_title || this.$t('pages.election-dashboard.voting_section.error_title_default');
        },

        getErrorMessage() {
            // Use translation-based error message based on user status
            if (!this.authUser?.is_voter) {
                return this.$t('pages.election-dashboard.voting_section.error_not_registered_msg');
            }
            if (this.authUser?.is_voter && !this.authUser?.can_vote) {
                return this.$t('pages.election-dashboard.voting_section.error_approval_pending_msg');
            }
            // Fallback to backend error messages
            if (this.ballotAccess.error_message_nepali && this.ballotAccess.error_message_english) {
                return `${this.ballotAccess.error_message_nepali} / ${this.ballotAccess.error_message_english}`;
            }
            return this.ballotAccess.error_message_nepali || this.ballotAccess.error_message_english || '';
        }
    }
};
</script>

<style scoped>
/* Screen Reader Only */
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

/* Skip Link */
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: #2563eb;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    z-index: 100;
    border-radius: 0 0 4px 0;
    font-weight: 600;
}

.skip-link:focus {
    top: 0;
}

/* Enhanced focus styles for WCAG AA compliance */
a:focus-visible,
button:focus-visible,
[role="button"]:focus-visible {
    outline: 3px solid #2563eb;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
}

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

/* Enhanced card styling and hover effects */
.group {
    position: relative;
}

/* Smooth transitions for all interactive elements */
a[class*="hover\:shadow"],
button[class*="hover\:shadow"],
a[class*="hover\:bg"],
button[class*="hover\:bg"] {
    transition: all 300ms ease-in-out;
}

/* Improve focus visibility for accessibility */
a:focus-visible,
button:focus-visible {
    outline: 3px solid #2563eb;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
}

/* Mobile responsive adjustments */
@media (max-width: 640px) {
    .min-h-\[400px\] {
        min-height: 320px;
    }

    .text-3xl {
        font-size: 1.875rem;
    }

    .px-10 {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }

    .py-8 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
}

/* Ensure button hover states are consistent */
button:hover,
a[href]:hover {
    transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
}

/* Improve backdrop blur for timer */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Card shadow improvements */
.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
}
</style>
