<template>
    <nrna-layout>
        <app-layout>
            <!-- Skip Link for Keyboard Users -->
            <a
                href="#main-content"
                class="skip-link"
                @click.prevent="skipToContent"
            >
                {{ $t('pages.Vote.DemoVote.Create.skip_link') }}
            </a>

            <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
                <div id="main-content" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" tabindex="-1">

                    <!-- Header with proper ARIA -->
                    <header class="text-center mb-12" role="banner">
                        <div class="inline-flex items-center gap-3 mb-4">
                            <h1 class="text-4xl font-bold text-gray-900">{{ $t('pages.Vote.DemoVote.Create.page_header.title') }}</h1>
                            <div
                                class="bg-purple-700 text-white px-4 py-2 rounded-full font-semibold text-sm flex items-center gap-2"
                                style="background-color: #6b21a5;"
                            >
                                <span class="text-xl" aria-hidden="true">🎮</span>
                                <span>{{ $t('pages.Vote.DemoVote.Create.page_header.badge') }}</span>
                            </div>
                        </div>
                        <p class="text-xl text-gray-600 mb-4">{{ $t('pages.Vote.DemoVote.Create.page_header.welcome') }} {{ user_name }}!</p>
                        <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full" aria-hidden="true"></div>
                    </header>

                    <!-- Voter Information Cards with Screen Reader Text -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
                        <!-- Voter Card -->
                        <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-3 rounded-lg mr-4 shrink-0" aria-hidden="true">
                                    <span class="text-green-600 text-2xl">👤</span>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">
                                        <span class="sr-only">{{ $t('pages.Vote.DemoVote.Create.voter_info.current_voter') }}</span>
                                        {{ $t('pages.Vote.DemoVote.Create.voter_info.current_voter') }}
                                    </p>
                                    <p class="font-bold text-gray-900 text-lg">
                                        {{ user_name }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Election Card -->
                        <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-blue-200">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-lg mr-4 shrink-0" aria-hidden="true">
                                    <span class="text-blue-600 text-2xl">📋</span>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">
                                        <span class="sr-only">{{ $t('pages.Vote.DemoVote.Create.voter_info.current_election') }}</span>
                                        {{ $t('pages.Vote.DemoVote.Create.voter_info.current_election') }}
                                    </p>
                                    <p class="font-bold text-gray-900 text-lg">
                                        {{ election?.name || 'Demo Election' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Card with Live Region -->
                        <div
                            class="bg-white rounded-xl p-6 shadow-lg border-2 border-purple-200"
                            role="status"
                            aria-live="polite"
                            aria-atomic="true"
                        >
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-3 rounded-lg mr-4 shrink-0" aria-hidden="true">
                                    <span class="text-purple-600 text-2xl">📊</span>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">{{ $t('pages.Vote.DemoVote.Create.voter_info.progress') }}</p>
                                    <p class="font-bold text-gray-900 text-lg" aria-hidden="true">
                                        {{ votingProgress.completed }}/{{ votingProgress.total }}
                                    </p>
                                    <span class="sr-only">
                                        {{ votingProgress.completed }} of {{ votingProgress.total }} positions completed
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Workflow Step Indicator - Step 3/5 -->
                    <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-6 md:py-8 mb-8">
                        <WorkflowStepIndicator :currentStep="3" /> 
                    </div>

                    <!-- Demo Mode Notice -->
                    <div
                        class="max-w-4xl mx-auto bg-purple-50 border-2 border-purple-300 rounded-lg p-6 mb-8"
                        role="complementary"
                        aria-label="Demo mode information"
                    >
                        <div class="flex items-start gap-3">
                            <div class="text-2xl" aria-hidden="true">🎮</div>
                            <div class="text-left">
                                <h3 class="font-bold text-purple-900 text-lg mb-2">{{ $t('pages.Vote.DemoVote.Create.demo_notice.title') }}</h3>
                                <p class="text-purple-800">{{ $t('pages.Vote.DemoVote.Create.demo_notice.message') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- =========================================== -->
                    <!-- NATIONAL POSTS SECTION                      -->
                    <!-- =========================================== -->
                    <section
                        v-if="posts.national?.length"
                        class="mb-12"
                        aria-labelledby="national-posts-title"
                    >
                        <h2 id="national-posts-title" class="text-3xl font-bold text-gray-900 text-center mb-8">
                            {{ $t('pages.Vote.DemoVote.Create.voting_section.national_posts') }}
                        </h2>
                        <div class="space-y-8">
                            <div
                                v-for="post in posts.national"
                                :key="post.id"
                                class="bg-white rounded-2xl shadow-lg border-2 overflow-hidden transition-all duration-200"
                                :class="postsWithErrors.includes(post.name) ? 'border-red-500 ring-2 ring-red-300' : 'border-gray-200'"
                                :aria-labelledby="`post-title-${post.id}`"
                            >
                                <!-- Post Header -->
                                <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-5 text-white">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div>
                                            <h3 :id="`post-title-${post.id}`" class="text-2xl font-bold mb-1">
                                                {{ post.name }}
                                            </h3>
                                            <p v-if="$i18n.locale === 'np'" class="text-blue-100 text-sm opacity-90">
                                                {{ post.nepali_name || post.name }}
                                            </p>
                        
                                        </div>
                                        <div
                                            class="bg-white/20 backdrop-blur-xs rounded-full px-5 py-2 inline-flex items-center gap-3"
                                            :aria-label="`Select up to ${post.required_number} candidate${post.required_number > 1 ? 's' : ''}`"
                                        >
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="font-bold text-lg">{{ post.required_number }}</span>
                                            </div>
                                            <span class="text-sm font-medium">{{ $t('pages.Vote.DemoVote.Create.voting_section.required') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Candidates Grid -->
                                <div class="p-6">
                                    <!-- Selection Status Summary for Screen Readers -->
                                    <div
                                        class="sr-only"
                                        role="status"
                                        aria-live="polite"
                                        :aria-label="getSelectionStatusForPost(post)"
                                    ></div>

                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8"
                                        role="group"
                                        :aria-label="`Candidates for ${post.name}`"
                                    >
                                        <div
                                            v-for="candidate in sortedCandidates(post.candidates)"
                                            :key="candidate.id"
                                            class="candidate-card relative"
                                        >
                                            <!-- Candidate Card with Full Accessibility -->
                                            <div
                                                class="w-full bg-gradient-to-b from-gray-50 to-white border-2 rounded-xl overflow-hidden transition-all duration-200"
                                                :class="[
                                                    isSelected(post.id, candidate) ? 'border-blue-600 bg-blue-50' : 'border-gray-200',
                                                    noVoteSelections[post.id] ? 'opacity-50' : ''
                                                ]"
                                                :aria-selected="isSelected(post.id, candidate)"
                                                :aria-disabled="noVoteSelections[post.id]"
                                            >
                                                <!-- Post Label -->
                                                <div
                                                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center px-3 py-2"
                                                    :class="{ 'opacity-75': noVoteSelections[post.id] }"
                                                >
                                                    <p class="text-xs font-bold">{{ $t('pages.Vote.DemoVote.Create.position_card.candidates_label') }} {{ post.name }}</p>
                                                </div>

                                                <!-- Candidate Photo -->
                                                <div class="flex justify-center p-6 bg-white">
                                                    <div class="w-32 h-32 rounded-lg overflow-hidden border-2 border-gray-200 bg-gray-100 flex items-center justify-center">
                                                        <img
                                                            v-if="candidate.image_path_1"
                                                            :src="getImageUrl(candidate.image_path_1)"
                                                            :alt="candidate.candidacy_name"
                                                                class="w-full h-full object-cover"
                                                        />
                                                        <span v-else class="text-4xl">👤</span>
                                                    </div>
                                                </div>

                                                <!-- Candidate Info -->
                                                <div class="p-4 text-center bg-white border-t-2 border-gray-100">
                                                    <h4 class="font-bold text-gray-900">{{ candidate.candidacy_name }}</h4>
                                                    <p class="text-xs text-gray-500 mt-1">Position #{{ candidate.position_order }}</p>

                                                    <!-- Selection Checkbox with Full Accessibility -->
                                                    <div class="mt-3">
                                                        <input
                                                            type="checkbox"
                                                            :id="`candidate-${candidate.id}`"
                                                            :checked="isSelected(post.id, candidate)"
                                                            @change="toggleCandidate(post, candidate)"
                                                            :disabled="noVoteSelections[post.id]"
                                                            :aria-label="`Select ${candidate.candidacy_name} for ${post.name}`"
                                                            :aria-describedby="`candidate-desc-${candidate.id}`"
                                                            class="sr-only peer"
                                                        />
                                                        <label
                                                            :for="`candidate-${candidate.id}`"
                                                            class="flex items-center justify-center w-10 h-10 mx-auto bg-white border-2 border-gray-300 rounded-lg cursor-pointer
                                                                   peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white
                                                                   peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:border-blue-500
                                                                   peer-disabled:opacity-50 peer-disabled:cursor-not-allowed
                                                                   hover:border-blue-400 transition-all duration-200"
                                                            :class="{ 'cursor-not-allowed opacity-50': noVoteSelections[post.id] }"
                                                        >
                                                            <svg v-if="isSelected(post.id, candidate)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </label>

                                                        <!-- Selection Order Badge -->
                                                        <div v-if="getSelectionOrder(post.id, candidate.id) > 0" class="mt-2">
                                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                </svg>
                                                                #{{ getSelectionOrder(post.id, candidate.id) }}
                                                            </span>
                                                        </div>

                                                        <!-- Hidden description for screen readers -->
                                                        <div :id="`candidate-desc-${candidate.id}`" class="sr-only">
                                                            {{ candidate.user_name }}, position {{ candidate.position_order }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selection Status Section -->
                                    <div class="mb-8 bg-gradient-to-r from-gray-50 to-blue-50 border-2 border-gray-200 rounded-xl p-5">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <!-- Status Message -->
                                            <div class="flex-1">
                                                <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $t('pages.Vote.DemoVote.Create.voting_section.selected') }}</h3>

                                                <!-- Status Box -->
                                                <div
                                                    class="rounded-lg p-4"
                                                    :class="{
                                                        'bg-green-50 border border-green-200 text-green-800': getPostSelectionStatus(post).type === 'valid',
                                                        'bg-blue-50 border border-blue-200 text-blue-800': getPostSelectionStatus(post).type === 'partial',
                                                        'bg-red-50 border border-red-200 text-red-800': getPostSelectionStatus(post).type === 'empty',
                                                        'bg-gray-50 border border-gray-200 text-gray-800': getPostSelectionStatus(post).type === 'no-vote'
                                                    }"
                                                >
                                                    <div class="flex items-start gap-3">
                                                        <div class="text-lg shrink-0">{{ getPostSelectionStatus(post).icon }}</div>
                                                        <div>
                                                            <p class="font-semibold">{{ getPostSelectionStatus(post).message }}</p>
                                                            <p v-if="getSelectedCandidateNames(post)" class="text-sm opacity-90 mt-1">
                                                                Selected: <span class="font-medium">{{ getSelectedCandidateNames(post) }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Selection Counter -->
                                            <div class="bg-white border-2 border-gray-200 rounded-lg px-6 py-3 text-center shrink-0">
                                                <div class="text-2xl font-bold text-blue-600">{{ selectedCandidates[post.id]?.length || 0 }}</div>
                                                <div class="text-sm text-gray-600">of {{ post.required_number }}</div>
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="mt-4">
                                            <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                                <span>Progress</span>
                                                <span>{{ Math.min(100, Math.round(((selectedCandidates[post.id]?.length || 0) / post.required_number) * 100)) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div
                                                    class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                                                    :style="{ width: Math.min(100, Math.round(((selectedCandidates[post.id]?.length || 0) / post.required_number) * 100)) + '%' }"
                                                    role="progressbar"
                                                    :aria-valuenow="Math.min(100, Math.round(((selectedCandidates[post.id]?.length || 0) / post.required_number) * 100))"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- No Vote Option with Full Accessibility -->
                                    <div
                                        class="border-2 border-gray-300 rounded-xl p-6 bg-gradient-to-br from-gray-50 to-white"
                                        role="group"
                                        :aria-label="`Skip voting for ${post.name}`"
                                    >
                                        <div class="flex flex-col md:flex-row md:items-center gap-6">
                                            <div class="shrink-0">
                                                <input
                                                    type="checkbox"
                                                    :id="`no_vote_${post.id}`"
                                                    v-model="noVoteSelections[post.id]"
                                                    @change="toggleNoVote(post)"
                                                    :aria-label="`Skip voting for ${post.name}`"
                                                    :aria-describedby="`no-vote-desc-${post.id}`"
                                                    class="sr-only peer"
                                                />
                                                <label
                                                    :for="`no_vote_${post.id}`"
                                                    class="flex items-center justify-center w-12 h-12 bg-white border-3 border-black rounded-lg cursor-pointer
                                                           peer-checked:bg-blue-600 peer-checked:border-blue-600
                                                           peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:border-blue-500
                                                           transition-all duration-200 hover:border-black"
                                                >
                                                    <svg v-if="noVoteSelections[post.id]" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </label>
                                            </div>
                                            <div class="grow">
                                                <label :for="`no_vote_${post.id}`" class="cursor-pointer block">
                                                    <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $t('pages.Vote.DemoVote.Create.position_card.skip_this_position') }}</h4>
                                                    <p :id="`no-vote-desc-${post.id}`" class="text-gray-700">
                                                        Select this if you wish to abstain from voting for this post.
                                                    </p>
                                                </label>
                                                <div v-if="noVoteSelections[post.id]" class="mt-4 p-4 bg-gray-100 rounded-lg" role="status" aria-live="polite">
                                                    <p class="font-semibold text-gray-800">
                                                        You have chosen to skip {{ post.name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- =========================================== -->
                    <!-- REGIONAL POSTS SECTION                      -->
                    <!-- =========================================== -->
                    <section
                        v-if="posts.regional?.length"
                        class="mb-12"
                        aria-labelledby="regional-posts-title"
                    >
                        <h2 id="regional-posts-title" class="text-3xl font-bold text-gray-900 text-center mb-8">
                            {{ $t('pages.Vote.DemoVote.Create.voting_section.regional_posts') }}
                        </h2>
                        <div class="space-y-8">
                            <div
                                v-for="post in posts.regional"
                                :key="post.id"
                                class="bg-white rounded-2xl shadow-lg border-2 overflow-hidden transition-all duration-200"
                                :class="postsWithErrors.includes(post.name) ? 'border-red-500 ring-2 ring-red-300' : 'border-gray-200'"
                                :aria-labelledby="`post-title-${post.id}`"
                            >
                                <!-- Post Header -->
                                <div class="bg-gradient-to-r from-green-700 to-teal-800 px-6 py-5 text-white">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div>
                                            <h3 :id="`post-title-${post.id}`" class="text-2xl font-bold mb-1">
                                                {{ post.name }}
                                            </h3>
                                            <p v-if="$i18n.locale === 'np' && post.nepali_name" class="text-green-100 text-sm opacity-90">
                                                {{ post.nepali_name }}
                                            </p>
                                            <p v-if="post.state_name" class="text-green-100 text-sm">
                                                Region: {{ post.state_name }}
                                            </p>
                                        </div>
                                        <div
                                            class="bg-white/20 backdrop-blur-xs rounded-full px-5 py-2 inline-flex items-center gap-3"
                                            :aria-label="`Select up to ${post.required_number} candidate${post.required_number > 1 ? 's' : ''}`"
                                        >
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="font-bold text-lg">{{ post.required_number }}</span>
                                            </div>
                                            <span class="text-sm font-medium">{{ $t('pages.Vote.DemoVote.Create.voting_section.required') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Candidates Grid -->
                                <div class="p-6">
                                    <!-- Selection Status Summary for Screen Readers -->
                                    <div
                                        class="sr-only"
                                        role="status"
                                        aria-live="polite"
                                        :aria-label="getSelectionStatusForPost(post)"
                                    ></div>

                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8"
                                        role="group"
                                        :aria-label="`Candidates for ${post.name}`"
                                    >
                                        <div
                                            v-for="candidate in sortedCandidates(post.candidates)"
                                            :key="candidate.id"
                                            class="candidate-card relative"
                                        >
                                            <!-- Candidate Card with Full Accessibility -->
                                            <div
                                                class="w-full bg-gradient-to-b from-gray-50 to-white border-2 rounded-xl overflow-hidden transition-all duration-200"
                                                :class="[
                                                    isSelected(post.id, candidate) ? 'border-green-600 bg-green-50' : 'border-gray-200',
                                                    noVoteSelections[post.id] ? 'opacity-50' : ''
                                                ]"
                                                :aria-selected="isSelected(post.id, candidate)"
                                                :aria-disabled="noVoteSelections[post.id]"
                                            >
                                                <!-- Post Label -->
                                                <div
                                                    class="bg-gradient-to-r from-green-600 to-green-700 text-white text-center px-3 py-2"
                                                    :class="{ 'opacity-75': noVoteSelections[post.id] }"
                                                >
                                                    <p class="text-xs font-bold">{{ $t('pages.Vote.DemoVote.Create.position_card.candidates_label') }} {{ post.name }}</p>
                                                </div>

                                                <!-- Candidate Photo -->
                                                <div class="flex justify-center p-6 bg-white">
                                                    <div class="w-32 h-32 rounded-lg overflow-hidden border-2 border-gray-200 bg-gray-100 flex items-center justify-center">
                                                        <img
                                                            v-if="candidate.image_path_1"
                                                            :src="getImageUrl(candidate.image_path_1)"
                                                            :alt="candidate.user_name"
                                                            class="w-full h-full object-cover"
                                                        />
                                                        <span v-else class="text-4xl">👤</span>
                                                    </div>
                                                </div>

                                                <!-- Candidate Info -->
                                                <div class="p-4 text-center bg-white border-t-2 border-gray-100">
                                                    <h4 class="font-bold text-gray-900">{{ candidate.user_name }}</h4>
                                                    <p class="text-xs text-gray-500 mt-1">Position #{{ candidate.position_order }}</p>

                                                    <!-- Selection Checkbox with Full Accessibility -->
                                                    <div class="mt-3">
                                                        <input
                                                            type="checkbox"
                                                            :id="`candidate-${candidate.id}`"
                                                            :checked="isSelected(post.id, candidate)"
                                                            @change="toggleCandidate(post, candidate)"
                                                            :disabled="noVoteSelections[post.id]"
                                                            :aria-label="`Select ${candidate.candidacy_name} for ${post.name}`"
                                                            :aria-describedby="`candidate-desc-${candidate.id}`"
                                                            class="sr-only peer"
                                                        />
                                                        <label
                                                            :for="`candidate-${candidate.id}`"
                                                            class="flex items-center justify-center w-10 h-10 mx-auto bg-white border-2 border-gray-300 rounded-lg cursor-pointer
                                                                   peer-checked:bg-green-600 peer-checked:border-green-600 peer-checked:text-white
                                                                   peer-focus:ring-4 peer-focus:ring-green-300 peer-focus:border-green-500
                                                                   peer-disabled:opacity-50 peer-disabled:cursor-not-allowed
                                                                   hover:border-green-400 transition-all duration-200"
                                                            :class="{ 'cursor-not-allowed opacity-50': noVoteSelections[post.id] }"
                                                        >
                                                            <svg v-if="isSelected(post.id, candidate)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </label>

                                                        <!-- Hidden description for screen readers -->
                                                        <div :id="`candidate-desc-${candidate.id}`" class="sr-only">
                                                            {{ candidate.user_name }}, position {{ candidate.position_order }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selection Status Section (Regional) -->
                                    <div class="mb-8 bg-gradient-to-r from-gray-50 to-green-50 border-2 border-gray-200 rounded-xl p-5">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <!-- Status Message -->
                                            <div class="flex-1">
                                                <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $t('pages.Vote.DemoVote.Create.voting_section.selected') }}</h3>

                                                <!-- Status Box -->
                                                <div
                                                    class="rounded-lg p-4"
                                                    :class="{
                                                        'bg-green-50 border border-green-200 text-green-800': getPostSelectionStatus(post).type === 'valid',
                                                        'bg-blue-50 border border-blue-200 text-blue-800': getPostSelectionStatus(post).type === 'partial',
                                                        'bg-red-50 border border-red-200 text-red-800': getPostSelectionStatus(post).type === 'empty',
                                                        'bg-gray-50 border border-gray-200 text-gray-800': getPostSelectionStatus(post).type === 'no-vote'
                                                    }"
                                                >
                                                    <div class="flex items-start gap-3">
                                                        <div class="text-lg shrink-0">{{ getPostSelectionStatus(post).icon }}</div>
                                                        <div>
                                                            <p class="font-semibold">{{ getPostSelectionStatus(post).message }}</p>
                                                            <p v-if="getSelectedCandidateNames(post)" class="text-sm opacity-90 mt-1">
                                                                Selected: <span class="font-medium">{{ getSelectedCandidateNames(post) }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Selection Counter -->
                                            <div class="bg-white border-2 border-gray-200 rounded-lg px-6 py-3 text-center shrink-0">
                                                <div class="text-2xl font-bold text-green-600">{{ selectedCandidates[post.id]?.length || 0 }}</div>
                                                <div class="text-sm text-gray-600">of {{ post.required_number }}</div>
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="mt-4">
                                            <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                                <span>Progress</span>
                                                <span>{{ Math.min(100, Math.round(((selectedCandidates[post.id]?.length || 0) / post.required_number) * 100)) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div
                                                    class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all duration-500"
                                                    :style="{ width: Math.min(100, Math.round(((selectedCandidates[post.id]?.length || 0) / post.required_number) * 100)) + '%' }"
                                                    role="progressbar"
                                                    :aria-valuenow="Math.min(100, Math.round(((selectedCandidates[post.id]?.length || 0) / post.required_number) * 100))"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- No Vote Option with Full Accessibility -->
                                    <div
                                        class="border-2 border-gray-300 rounded-xl p-6 bg-gradient-to-br from-gray-50 to-white"
                                        role="group"
                                        :aria-label="`Skip voting for ${post.name}`"
                                    >
                                        <div class="flex flex-col md:flex-row md:items-center gap-6">
                                            <div class="shrink-0">
                                                <input
                                                    type="checkbox"
                                                    :id="`no_vote_${post.id}`"
                                                    v-model="noVoteSelections[post.id]"
                                                    @change="toggleNoVote(post)"
                                                    :aria-label="`Skip voting for ${post.name}`"
                                                    :aria-describedby="`no-vote-desc-${post.id}`"
                                                    class="sr-only peer"
                                                />
                                                <label
                                                    :for="`no_vote_${post.id}`"
                                                    class="flex items-center justify-center w-12 h-12 bg-white border-3 border-black rounded-lg cursor-pointer
                                                           peer-checked:bg-green-600 peer-checked:border-green-600
                                                           peer-focus:ring-4 peer-focus:ring-green-300 peer-focus:border-green-500
                                                           transition-all duration-200 hover:border-black"
                                                >
                                                    <svg v-if="noVoteSelections[post.id]" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </label>
                                            </div>
                                            <div class="grow">
                                                <label :for="`no_vote_${post.id}`" class="cursor-pointer block">
                                                    <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $t('pages.Vote.DemoVote.Create.position_card.skip_this_position') }}</h4>
                                                    <p :id="`no-vote-desc-${post.id}`" class="text-gray-700">
                                                        Select this if you wish to abstain from voting for this post.
                                                    </p>
                                                </label>
                                                <div v-if="noVoteSelections[post.id]" class="mt-4 p-4 bg-gray-100 rounded-lg" role="status" aria-live="polite">
                                                    <p class="font-semibold text-gray-800">
                                                        You have chosen to skip {{ post.name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Error Display with Live Region -->
                    <div
                        v-if="errors.submit"
                        class="max-w-4xl mx-auto mt-6 p-4 bg-red-50 border-2 border-red-300 rounded-lg"
                        role="alert"
                        aria-live="assertive"
                        aria-atomic="true"
                    >
                        <div class="flex items-center gap-3">
                            <span class="text-red-600 text-xl" aria-hidden="true">⚠️</span>
                            <span class="text-red-800 font-medium">{{ errors.submit }}</span>
                        </div>
                    </div>

                    <!-- Agreement and Submit Section -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mt-8 max-w-4xl mx-auto">
                        <div class="border-2 border-blue-300 rounded-lg p-6 mb-6 bg-blue-50">
                            <!-- Agreement Checkbox -->
                            <div class="flex flex-col items-center justify-center mb-6">
                                <div class="text-3xl mb-2" aria-hidden="true">✅</div>
                                <h3 class="text-xl font-bold text-red-700 mb-1">{{ $t('pages.Vote.DemoVote.Create.voting_agreement.title') }}</h3>
                            </div>

                            <div class="flex justify-center mb-4">
                                <label class="flex items-center cursor-pointer gap-3">
                                    <input
                                        type="checkbox"
                                        v-model="form.agree_button"
                                        class="w-10 h-10 text-blue-600 border-3 border-gray-600 rounded focus:ring-4 focus:ring-blue-300 focus:border-blue-500"
                                        :aria-label="form.agree_button ? 'You have agreed to the terms' : 'You must agree to the terms to submit'"
                                        aria-describedby="agreement-desc"
                                    />
                                    <span class="text-lg font-medium text-gray-900">{{ $t('pages.Vote.DemoVote.Create.voting_agreement.agree_label') }}</span>
                                </label>
                            </div>
                            <p id="agreement-desc" class="text-sm text-gray-600 text-center">
                                By agreeing, you confirm your selections are correct and follow voting rules
                            </p>

                            <!-- Submit Button -->
                            <div class="flex justify-center mt-6">
                                <button
                                    type="button"
                                    @click="submit"
                                    class="w-full  bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-700 hover:to-purple-700
                                           text-white font-bold text-xl py-10 px-10 m-3 rounded-xl shadow-lg
                                           focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-offset-2
                                           disabled:opacity-50 disabled:cursor-not-allowed
                                           transform transition-all duration-200 hover:scale-105"
                                    :disabled="!form.agree_button || loading"
                                    :aria-label="getSubmitButtonLabel()"
                                >
                                    <span class="flex items-center justify-center gap-2">
                                        <span class="text-3xl font-bold " aria-hidden="true">🗳️</span>
                                        <span  class="text-xl font-bold">{{ loading ? $t('pages.Vote.DemoVote.Create.buttons.submitting') : $t('pages.Vote.DemoVote.Create.buttons.submit_vote') }}</span>
                                    </span>
                                </button>
                            </div>

                            <!-- Loading State Announcement -->
                            <div v-if="loading" class="sr-only" role="status" aria-live="polite">
                                Your vote is being submitted. Please wait.
                            </div>
                        </div>
                    </div>

                    <!-- Information Footer -->
                    <section
                        class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mt-12"
                        aria-label="Additional information"
                    >
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-200">
                            <div class="flex items-start gap-4">
                                <div class="text-4xl" aria-hidden="true">🔒</div>
                                <div>
                                    <h3 class="font-bold text-blue-900 text-lg mb-2">{{ $t('pages.Vote.DemoVote.Create.demo_notice.security_note') }}</h3>
                                    <p class="text-blue-800 text-sm">{{ $t('pages.Vote.DemoVote.Create.security.encryption_note') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border-2 border-green-200">
                            <div class="flex items-start gap-4">
                                <div class="text-4xl" aria-hidden="true">⏱️</div>
                                <div>
                                    <h3 class="font-bold text-green-900 text-lg mb-2">{{ $t('pages.Vote.DemoVote.Create.alerts.session_timeout') }}</h3>
                                    <p class="text-green-800 text-sm">{{ $t('pages.Vote.DemoVote.Create.voter_info.voting_time_remaining') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-200">
                            <div class="flex items-start gap-4">
                                <div class="text-4xl" aria-hidden="true">❓</div>
                                <div>
                                    <h3 class="font-bold text-purple-900 text-lg mb-2">{{ $t('pages.Vote.DemoVote.Create.buttons.need_help') }}</h3>
                                    <p class="text-purple-800 text-sm">{{ $t('pages.Vote.DemoVote.Create.help_section.contact_admin') }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </app-layout>
    </nrna-layout>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator.vue'
import { useForm } from '@inertiajs/vue3'

export default {
    name: 'EnhancedCreate',

    components: {
        AppLayout,
        NrnaLayout,
        WorkflowStepIndicator,
    },

    props: {
        posts: {
            type: Object,
            required: true,
            default: () => ({ national: [], regional: [] })
        },
        user_name: String,
        user_id: Number,
        user_region: String,
        slug: String,
        useSlugPath: Boolean,
        election: Object,
    },

    setup(props) {
        // State
        const selectedCandidates = ref({})
        const noVoteSelections = ref({})
        const errors = ref({})
        const loading = ref(false)

        const form = useForm({
            user_id: props.user_id,
            agree_button: false,
        })

        // Helper: Sort candidates by position_order
        const sortedCandidates = (candidates) => {
            if (!candidates) return []
            return [...candidates].sort((a, b) => (a.position_order || 0) - (b.position_order || 0))
        }

        // Helper: Validate candidate belongs to post
        const validateCandidateBelongsToPost = (post, candidateId) => {
            return post.candidates?.some(c => c.id === candidateId) || false
        }

        // Check if candidate is selected
        const isSelected = (postId, candidate) => {
            if (noVoteSelections.value[postId]) return false
            return selectedCandidates.value[postId]?.includes(candidate.id) || false
        }

        // Get selection status for screen readers
        const getSelectionStatusForPost = (post) => {
            if (noVoteSelections.value[post.id]) {
                return `You have chosen to skip ${post.name}`
            }
            const count = selectedCandidates.value[post.id]?.length || 0
            if (count === 0) {
                return `No candidates selected for ${post.name}. You need to select ${post.required_number}.`
            }
            return `Selected ${count} of ${post.required_number} candidates for ${post.name}`
        }

        // Show max selection warning
        const showMaxSelectionWarning = (postName, max) => {
            const warning = `You can only select up to ${max} candidate${max > 1 ? 's' : ''} for ${postName}`
            errors.value[`max_${postName}`] = warning

            // Auto-clear after 3 seconds
            setTimeout(() => {
                delete errors.value[`max_${postName}`]
            }, 3000)
        }

        // Toggle candidate selection
        const toggleCandidate = (post, candidate) => {
            // Validate inputs
            if (!post || !candidate) {
                console.error('Invalid post or candidate')
                return
            }

            // Validate candidate belongs to post
            if (!validateCandidateBelongsToPost(post, candidate.id)) {
                console.error('Candidate does not belong to post')
                return
            }

            const postId = post.id
            const required = post.required_number || 1

            // Clear any max selection error for this post
            delete errors.value[`max_${post.name}`]

            // Clear no-vote if it was selected
            if (noVoteSelections.value[postId]) {
                noVoteSelections.value[postId] = false
            }

            // Initialize array if needed
            if (!selectedCandidates.value[postId]) {
                selectedCandidates.value[postId] = []
            }

            const index = selectedCandidates.value[postId].indexOf(candidate.id)

            if (index === -1) {
                // Add if under limit
                if (selectedCandidates.value[postId].length < required) {
                    selectedCandidates.value[postId].push(candidate.id)
                } else {
                    // Show warning when trying to exceed limit
                    showMaxSelectionWarning(post.name, required)
                    return
                }
            } else {
                // Remove
                selectedCandidates.value[postId].splice(index, 1)
            }
        }

        // Toggle no-vote option
        const toggleNoVote = (post) => {
            const postId = post.id

            if (noVoteSelections.value[postId]) {
                // Clear candidates if no-vote is selected
                selectedCandidates.value[postId] = []
            }
        }

        // Extract post names from error messages
        const postsWithErrors = computed(() => {
            if (!errors.value.submit) return []

            // Parse error message like "No selection made for Vice President. No selection made for State Representative - Europe"
            const errorText = errors.value.submit
            const postNames = []

            // Find all "No selection made for {PostName}" patterns
            const regex = /No selection made for (.+?)(?:\.|$)/g
            let match
            while ((match = regex.exec(errorText)) !== null) {
                postNames.push(match[1].trim())
            }

            return postNames
        })

        // Get selection status per post
        const getPostSelectionStatus = (post) => {
            if (noVoteSelections.value[post.id]) {
                return {
                    type: 'no-vote',
                    message: `You have chosen to skip ${post.name}`,
                    color: 'gray',
                    icon: '⏭️'
                }
            }

            const selected = selectedCandidates.value[post.id]?.length || 0
            const required = post.required_number || 1

            if (selected === 0) {
                return {
                    type: 'empty',
                    message: `No candidates selected. Select ${required} candidate${required > 1 ? 's' : ''}.`,
                    color: 'red',
                    icon: '⚠️'
                }
            } else if (selected === required) {
                return {
                    type: 'valid',
                    message: `✓ Valid selection (${selected} of ${required})`,
                    color: 'green',
                    icon: '✓'
                }
            } else {
                return {
                    type: 'partial',
                    message: `${selected} of ${required} selected`,
                    color: 'blue',
                    icon: 'ℹ️'
                }
            }
        }

        // Get selected candidate names for a post
        const getSelectedCandidateNames = (post) => {
            const selectedIds = selectedCandidates.value[post.id] || []
            if (selectedIds.length === 0) return ''

            const selectedNames = selectedIds
                .map(id => {
                    const candidate = post.candidates?.find(c => c.id === id)
                    return candidate?.candidacy_name || candidate?.user_name || 'Unknown'
                })
                .filter(name => name)

            return selectedNames.join(', ')
        }

        // Get selection order for a candidate
        const getSelectionOrder = (postId, candidateId) => {
            const selectedIds = selectedCandidates.value[postId] || []
            const index = selectedIds.indexOf(candidateId)
            return index >= 0 ? index + 1 : 0
        }

        // Calculate voting progress
        const votingProgress = computed(() => {
            const allPosts = [...(props.posts.national || []), ...(props.posts.regional || [])]
            const totalPosts = allPosts.length

            let completedPosts = 0
            allPosts.forEach(post => {
                if (noVoteSelections.value[post.id]) {
                    completedPosts++
                } else {
                    const selectedCount = selectedCandidates.value[post.id]?.length || 0
                    if (selectedCount === post.required_number) {
                        completedPosts++
                    }
                }
            })

            return {
                completed: completedPosts,
                total: totalPosts,
                percentage: totalPosts ? Math.round((completedPosts / totalPosts) * 100) : 0
            }
        })

        // Get submit button label for screen readers
        const getSubmitButtonLabel = () => {
            if (loading.value) return 'Submitting your vote, please wait'
            if (!form.agree_button) return 'You must agree to the terms before submitting'
            return 'Submit your vote'
        }

        // Skip to main content
        const skipToContent = () => {
            document.getElementById('main-content')?.focus()
        }

        // Validate all posts before submission
        const validateVoteData = () => {
            const validationErrors = []
            const allPosts = [...(props.posts.national || []), ...(props.posts.regional || [])]

            allPosts.forEach(post => {
                if (noVoteSelections.value[post.id]) {
                    // No-vote selected - valid
                    return
                }

                const selected = selectedCandidates.value[post.id] || []
                if (selected.length === 0) {
                    validationErrors.push(`No selection made for ${post.name}`)
                } else if (selected.length > post.required_number) {
                    validationErrors.push(`Too many candidates selected for ${post.name} (max ${post.required_number})`)
                }
            })

            return validationErrors
        }

        // Submit vote
        const submit = () => {
            errors.value = {}

            // Validate at least one selection
            const hasVotes = Object.keys(selectedCandidates.value).length > 0 ||
                            Object.keys(noVoteSelections.value).length > 0

            if (!hasVotes) {
                errors.value.submit = 'Please select at least one candidate or choose to skip'
                return
            }

            if (!form.agree_button) {
                errors.value.submit = 'You must agree to the terms before submitting'
                return
            }

            // Validate each post's selections
            const validationErrors = validateVoteData()
            if (validationErrors.length > 0) {
                errors.value.submit = validationErrors.join('. ')
                return
            }

            loading.value = true

            // Prepare vote data
            const voteData = {
                national_selected_candidates: [],
                regional_selected_candidates: [],
                no_vote_posts: []
            }

            // Process all posts
            const allPosts = [...(props.posts.national || []), ...(props.posts.regional || [])]
            allPosts.forEach(post => {
                if (noVoteSelections.value[post.id]) {
                    voteData.no_vote_posts.push(post.id)
                    // Also add to the appropriate candidates array so verify page can display the abstention
                    const isNationalNoVote = props.posts.national.some(p => p.id === post.id)
                    const noVotePostType = isNationalNoVote ? 'national' : 'regional'
                    voteData[`${noVotePostType}_selected_candidates`].push({
                        post_id: post.id,
                        post_name: post.name,
                        required_number: post.required_number,
                        no_vote: true,
                        candidates: []
                    })
                } else if (selectedCandidates.value[post.id]?.length) {
                    // ✅ FIX: Determine post type by checking which array it comes from
                    // (post.is_national_wide is NOT available in props)
                    const isNational = props.posts.national.some(p => p.id === post.id);
                    const postType = isNational ? 'national' : 'regional';

                    console.log(`📌 Post "${post.name}" ist ${isNational ? 'NATIONAL' : 'REGIONAL'}`);
                    console.log(`   - Selected candidate IDs: ${selectedCandidates.value[post.id]}`);
                    console.log(`   - Available candidates in post: ${post.candidates?.length || 0}`);

                    // Get full candidate objects
                    const selectedCandidatesList = selectedCandidates.value[post.id].map(id => {
                        const candidate = post.candidates.find(c => c.id === id)
                        console.log(`   - Looking for candidate ID ${id}: ${candidate ? '✅ FOUND' : '❌ NOT FOUND'}`);
                        return {
                            candidacy_id: candidate?.candidacy_id,
                            user_name: candidate?.candidacy_name,
                            candidacy_name: candidate?.candidacy_name,
                            id: candidate?.id
                        }
                    })

                    voteData[`${postType}_selected_candidates`].push({
                        post_id: post.id,
                        post_name: post.name,
                        required_number: post.required_number,
                        candidates: selectedCandidatesList
                    })
                }
            })

            // 🔴 DEBUG: Log complete vote data structure
            console.log('📊 FINAL VOTE DATA STRUCTURE:', {
                national_posts_count: voteData.national_selected_candidates.length,
                regional_posts_count: voteData.regional_selected_candidates.length,
                first_national: voteData.national_selected_candidates[0] ? {
                    post_name: voteData.national_selected_candidates[0].post_name,
                    candidates_count: voteData.national_selected_candidates[0].candidates?.length || 0,
                    first_candidate: voteData.national_selected_candidates[0].candidates?.[0]
                } : 'NO NATIONAL POSTS',
                first_regional: voteData.regional_selected_candidates[0] ? {
                    post_name: voteData.regional_selected_candidates[0].post_name,
                    candidates_count: voteData.regional_selected_candidates[0].candidates?.length || 0
                } : 'NO REGIONAL POSTS'
            });

            console.log('📊 FINAL VOTE DATA (FULL):', voteData);

            // Submit via Inertia
            const routeName = props.useSlugPath ? 'slug.demo-vote.submit' : 'demo-vote.submit'
            const params = props.useSlugPath ? { vslug: props.slug } : {}

            form.transform(() => ({
                ...voteData,
                agree_button: form.agree_button,
                user_id: form.user_id
            })).post(route(routeName, params), {
                onError: (formErrors) => {
                    errors.value = { ...errors.value, ...formErrors }
                    loading.value = false

                    // Announce errors to screen readers
                    const errorElement = document.querySelector('[role="alert"]')
                    if (errorElement) {
                        errorElement.focus()
                    }
                },
                onSuccess: () => {
                    loading.value = false
                }
            })
        }

        // Helper: Prepend /storage/ to image paths stored without prefix
        const getImageUrl = (path) => {
            if (!path) return null
            if (path.startsWith('http') || path.startsWith('/storage')) return path
            return `/storage/${path}`
        }

        // DEBUG: Log props to verify data flow
        onMounted(() => {
            console.log('===== VUE PROPS DEBUG =====');
            console.log('Posts received:', props.posts);
            console.log('National posts:', props.posts?.national);
            console.log('Regional posts:', props.posts?.regional);

            if (props.posts?.national?.length) {
                console.log('First national post candidates:', props.posts.national[0]?.candidates);
            }
            if (props.posts?.regional?.length) {
                console.log('First regional post candidates:', props.posts.regional[0]?.candidates);
            }
        })

        return {
            // State
            selectedCandidates,
            noVoteSelections,
            errors,
            loading,
            form,

            // Computed
            votingProgress,
            postsWithErrors,

            // Methods
            sortedCandidates,
            isSelected,
            getSelectionStatusForPost,
            toggleCandidate,
            toggleNoVote,
            getSubmitButtonLabel,
            skipToContent,
            submit,
            getPostSelectionStatus,
            getSelectedCandidateNames,
            getSelectionOrder,
            getImageUrl
        }
    }
}
</script>

<style scoped>
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
    transition: top 0.2s ease-in-out;
}

.skip-link:focus {
    top: 0;
    outline: 3px solid #ffbf47;
    outline-offset: 2px;
}

/* Candidate Cards */
.candidate-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.candidate-card:hover:not(.cursor-not-allowed) {
    transform: translateY(-4px);
}

/* Focus Styles - High Contrast */
:focus-visible {
    outline: 3px solid #2563eb;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
}

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
    border-width: 0;
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    .candidate-card,
    .candidate-card:hover,
    .skip-link,
    .transition-all,
    .transition-transform {
        transition: none !important;
        transform: none !important;
    }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
    .candidate-card {
        border-width: 3px !important;
    }

    .ring-4 {
        outline: 4px solid Highlight !important;
    }

    button:focus-visible,
    input:focus-visible,
    label:focus-visible {
        outline: 4px solid Highlight !important;
    }
}
</style>
