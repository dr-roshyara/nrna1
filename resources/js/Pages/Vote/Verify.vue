<template>
    <VotingLayout
        :election="election"
        :page-title="$t('pages.vote-verify.header.title')"
        :current-step="4"
        :total-steps="5"
    >
        <div class="w-full bg-linear-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Workflow Step Indicator - Step 4/5 -->
                <WorkflowStepIndicator workflow="VOTING" :currentStep="4" />

                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ $t('pages.vote-verify.header.title') }}
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 mb-4">
                        {{ $t('pages.vote-verify.header.subtitle') }}
                    </p>

                    <div class="inline-flex items-center px-4 py-2 bg-linear-to-r from-blue-500/10 to-indigo-500/10 border border-blue-200 rounded-full">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="text-sm sm:text-base font-medium text-blue-800">
                            {{ $t('pages.vote-verify.secure_process') }}
                        </span>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid lg:grid-cols-3 gap-6 md:gap-8">
                    <!-- Left Column - Verification Form -->
                    <div class="lg:col-span-2 space-y-6 md:space-y-8">
                        <!-- Critical Warning Card -->
                        <div class="bg-linear-to-br from-red-50 to-orange-50 border-2 border-red-200 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
                            <div class="flex items-start gap-3 md:gap-4">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-xl font-bold text-red-800 mb-2">
                                        {{ $t('pages.vote-verify.critical_warning.title') }}
                                    </h3>
                                    <p class="text-red-700 text-sm md:text-base">
                                        {{ $t('pages.vote-verify.critical_warning.message') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Code Section -->
                        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg p-4 md:p-8">
                            <!-- Section Header -->
                            <div class="mb-6 md:mb-8">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-linear-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">
                                        {{ $t('pages.vote-verify.verification_title') }}
                                    </h2>
                                </div>
                                <p class="text-gray-600">
                                    {{ $t('pages.vote-verify.verification_subtitle') }}
                                </p>
                            </div>

                            <!-- Status and Instructions -->
                            <div class="space-y-4 md:space-y-6 mb-6 md:mb-8">
                                <!-- Vote Complete Status -->
                                <div class="p-3 md:p-4 bg-linear-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center mb-1">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-semibold text-green-800">
                                            {{ $t('pages.vote-verify.status_complete') }}
                                        </span>
                                    </div>
                                    <p class="text-green-700 text-xs md:text-sm">
                                        {{ $t('pages.vote-verify.status_message') }}
                                    </p>
                                </div>

                                <!-- Email Notification -->
                                <div class="p-3 md:p-4 bg-linear-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600 mr-2 md:mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-blue-800 font-medium text-sm md:text-base">
                                                {{ $t('pages.vote-verify.email_notice.title') }}
                                            </p>
                                            <p class="text-blue-600 text-xs md:text-sm">
                                                {{ $t('pages.vote-verify.email_notice.message') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Required Banner -->
                                <div class="p-3 bg-linear-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-lg">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-semibold text-amber-800 text-sm md:text-base">
                                            {{ $t('pages.vote-verify.action_required') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Code Input Form -->
                            <form @submit.prevent="submit" class="space-y-6 md:space-y-8">
                                <!-- Code Input -->
                                <div>
                                    <label for="voting_code" class="block text-sm md:text-base font-bold text-gray-800 mb-3">
                                        <div class="flex items-center mb-1">
                                            <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <span>{{ $t('pages.vote-verify.code_input.label') }}</span>
                                        </div>
                                    </label>

                                    <div class="relative">
                                        <input
                                            id="voting_code"
                                            type="text"
                                            v-model="form.voting_code"
                                            class="w-full px-4 md:px-6 py-4 md:py-5 text-xl md:text-2xl font-mono text-center tracking-widest border-2 md:border-3 rounded-xl md:rounded-2xl focus:ring-3 md:focus:ring-4 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 uppercase shadow-xs md:shadow-lg"
                                            :class="{
                                                'border-red-300 bg-red-50': form.errors.voting_code,
                                                'border-green-400 bg-green-50': form.voting_code && form.voting_code.length === 6,
                                                'border-gray-300': !form.voting_code || form.voting_code.length !== 6
                                            }"
                                            :placeholder="$t('pages.vote-verify.code_input.placeholder')"
                                            maxlength="6"
                                            autocomplete="off"
                                            autofocus
                                        />

                                        <!-- Character Indicators -->
                                        <div class="mt-4 flex justify-center gap-1 md:gap-2">
                                            <div v-for="i in 6" :key="i"
                                                 class="w-10 h-10 md:w-12 md:h-12 rounded-lg border-2 flex items-center justify-center"
                                                 :class="{
                                                     'border-blue-500 bg-blue-50': form.voting_code && form.voting_code.length >= i,
                                                     'border-gray-300': !form.voting_code || form.voting_code.length < i
                                                 }">
                                                <span v-if="form.voting_code && form.voting_code.length >= i"
                                                      class="text-lg md:text-xl font-bold text-gray-900">
                                                    {{ form.voting_code.charAt(i-1) }}
                                                </span>
                                                <span v-else class="text-gray-400 text-base md:text-lg">_</span>
                                            </div>
                                        </div>

                                        <!-- Input Status -->
                                        <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                            <div class="text-xs md:text-sm text-gray-600">
                                                <span v-if="form.voting_code">
                                                    {{ form.voting_code.length }}/6 {{ $t('pages.vote-verify.code_input.characters') }}
                                                </span>
                                                <span v-else>{{ $t('pages.vote-verify.code_input.instruction') }}</span>
                                            </div>
                                            <div v-if="form.voting_code && form.voting_code.length === 6 && !form.errors.voting_code"
                                                 class="flex items-center text-green-600 text-xs md:text-sm">
                                                <svg class="w-4 h-4 md:w-5 md:h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $t('pages.vote-verify.code_input.ready') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Validation Errors -->
                                    <div v-if="form.errors.voting_code" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-red-700 text-sm">{{ form.errors.voting_code }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4 md:pt-6">
                                    <button
                                        type="submit"
                                        :disabled="form.processing || !form.voting_code || form.voting_code.length !== 6"
                                        class="w-full bg-linear-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-4 md:py-5 px-6 md:px-8 rounded-xl md:rounded-2xl transition-all duration-300 transform hover:scale-[1.02] disabled:scale-100 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                                    >
                                        <div class="flex items-center justify-center">
                                            <div class="text-center">
                                                <div class="flex items-center justify-center">
                                                    <svg v-if="form.processing"
                                                         class="animate-spin h-5 w-5 md:h-6 md:w-6 text-white mr-2 md:mr-3"
                                                         fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <svg v-else class="w-5 h-5 md:w-6 md:h-6 mr-2 md:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="text-base md:text-lg">
                                                        <span v-if="form.processing">
                                                            {{ $t('pages.vote-verify.save_button.processing') }}
                                                        </span>
                                                        <span v-else>
                                                            {{ $t('pages.vote-verify.save_button.text') }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </button>

                                    <!-- Button Status -->
                                    <div class="mt-3 text-center">
                                        <p class="text-xs text-gray-500" v-if="!form.voting_code || form.voting_code.length !== 6">
                                            {{ $t('pages.vote-verify.save_button.help_incomplete') }}
                                        </p>
                                        <p class="text-xs text-green-600 font-medium" v-else>
                                            ✓ {{ $t('pages.vote-verify.save_button.help_complete') }}
                                        </p>
                                    </div>

                                    <!-- Security Notice -->
                                    <div class="mt-4 p-3 md:p-4 bg-linear-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-start">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-yellow-600 mt-0.5 mr-2 md:mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <p class="text-yellow-800 text-sm font-medium">
                                                    {{ $t('pages.vote-verify.security_notice.title') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column - Vote Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg p-4 md:p-6 relative md:sticky md:top-8">
                            <!-- Voter Information -->
                            <div class="mb-6 p-4 md:p-5 bg-linear-to-br from-gray-50 to-blue-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-linear-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="font-bold text-gray-900 text-sm md:text-base">
                                            {{ $t('pages.vote-verify.voter_info.title') }}
                                        </h3>
                                        <p class="text-xs text-gray-500">
                                            {{ $t('pages.vote-verify.voter_info.subtitle') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-xs md:text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ $t('pages.vote-verify.voter_info.name') }}:</span>
                                        <span class="font-medium text-gray-900">{{ user_info.name }}</span>
                                    </div>
                                    <div v-if="user_info.user_id" class="flex justify-between">
                                        <span class="text-gray-600">{{ $t('pages.vote-verify.voter_info.user_id') }}:</span>
                                        <span class="font-medium text-gray-900">{{ user_info.user_id }}</span>
                                    </div>
                                    <div v-if="user_info.region" class="flex justify-between">
                                        <span class="text-gray-600">{{ $t('pages.vote-verify.voter_info.region') }}:</span>
                                        <span class="font-medium text-gray-900">{{ user_info.region }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Vote Summary -->
                            <div class="mb-6">
                                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $t('pages.vote-verify.summary.title') }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $t('pages.vote-verify.summary.subtitle') }}
                                </p>

                                <!-- Quick Stats -->
                                <div class="grid grid-cols-3 gap-2 md:gap-3 mb-6">
                                    <div class="bg-linear-to-br from-green-50 to-emerald-50 p-2 md:p-3 rounded-lg text-center">
                                        <div class="text-xl md:text-2xl font-bold text-green-600">{{ voting_summary.voted_posts || 0 }}</div>
                                        <div class="text-xs text-green-700 font-medium">
                                            {{ $t('pages.vote-verify.summary.stats.voted') }}
                                        </div>
                                    </div>
                                    <div class="bg-linear-to-br from-gray-100 to-gray-200 p-2 md:p-3 rounded-lg text-center">
                                        <div class="text-xl md:text-2xl font-bold text-gray-600">{{ voting_summary.no_vote_posts || 0 }}</div>
                                        <div class="text-xs text-gray-700 font-medium">
                                            {{ $t('pages.vote-verify.summary.stats.skipped') }}
                                        </div>
                                    </div>
                                    <div class="bg-linear-to-br from-blue-50 to-indigo-50 p-2 md:p-3 rounded-lg text-center">
                                        <div class="text-xl md:text-2xl font-bold text-blue-600">{{ voting_summary.total_posts || 0 }}</div>
                                        <div class="text-xs text-blue-700 font-medium">
                                            {{ $t('pages.vote-verify.summary.stats.total') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Post Selections -->
                                <div class="space-y-4">
                                    <!-- National Posts -->
                                    <div v-if="vote_data.national_posts && vote_data.national_posts.length > 0">
                                        <h4 class="font-semibold text-gray-700 mb-2 text-xs md:text-sm uppercase tracking-wider">
                                            {{ $t('pages.vote-verify.summary.national_posts') }}
                                        </h4>
                                        <div class="space-y-2">
                                            <div v-for="post in vote_data.national_posts"
                                                 :key="`national-${post.post_id}`"
                                                 class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-xs md:text-sm font-medium text-gray-800 truncate">
                                                        {{ post.post_name }}
                                                    </span>
                                                    <span v-if="post.no_vote"
                                                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 shrink-0 ml-2">
                                                        {{ $t('pages.vote-verify.summary.stats.skipped') }}
                                                    </span>
                                                    <span v-else
                                                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 shrink-0 ml-2">
                                                        {{ post.candidates.length }}
                                                    </span>
                                                </div>
                                                <div v-if="!post.no_vote && post.candidates.length > 0" class="mt-1">
                                                    <div v-for="candidate in post.candidates"
                                                         :key="candidate.candidacy_id"
                                                         class="flex items-center text-xs text-gray-600">
                                                        <svg class="w-3 h-3 text-green-500 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span class="truncate">{{ candidate.name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Regional Posts -->
                                    <div v-if="vote_data.regional_posts && vote_data.regional_posts.length > 0">
                                        <h4 class="font-semibold text-gray-700 mb-2 text-xs md:text-sm uppercase tracking-wider">
                                            {{ $t('pages.vote-verify.summary.regional_posts') }}
                                        </h4>
                                        <div class="space-y-2">
                                            <div v-for="post in vote_data.regional_posts"
                                                 :key="`regional-${post.post_id}`"
                                                 class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-xs md:text-sm font-medium text-gray-800 truncate">
                                                        {{ post.post_name }}
                                                    </span>
                                                    <span v-if="post.no_vote"
                                                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 shrink-0 ml-2">
                                                        {{ $t('pages.vote-verify.summary.stats.skipped') }}
                                                    </span>
                                                    <span v-else
                                                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 shrink-0 ml-2">
                                                        {{ post.candidates.length }}
                                                    </span>
                                                </div>
                                                <div v-if="!post.no_vote && post.candidates.length > 0" class="mt-1">
                                                    <div v-for="candidate in post.candidates"
                                                         :key="candidate.candidacy_id"
                                                         class="flex items-center text-xs text-gray-600">
                                                        <svg class="w-3 h-3 text-green-500 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span class="truncate">{{ candidate.name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Empty State -->
                                    <div v-if="!vote_data.national_posts?.length && !vote_data.regional_posts?.length"
                                         class="text-center py-4 md:py-6">
                                        <svg class="w-10 h-10 md:w-12 md:h-12 text-gray-300 mx-auto mb-2 md:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-gray-500 text-xs md:text-sm">
                                            {{ $t('pages.vote-verify.summary.no_data') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Badge -->
                            <div class="mt-6 p-3 md:p-4 bg-linear-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600 mr-2 md:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <div>
                                        <p class="font-medium text-blue-800 text-sm">
                                            {{ $t('pages.vote-verify.security_badge.title') }}
                                        </p>
                                        <p class="text-blue-600 text-xs">
                                            {{ $t('pages.vote-verify.security_badge.message') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </VotingLayout>
</template>

<script>
import VotingLayout from "@/Components/Election/VotingLayout.vue";
import { useForm } from "@inertiajs/vue3";
import JetValidationErrors from "@/Components/Jetstream/ValidationErrors.vue";
import WorkflowStepIndicator from "@/Components/Workflow/WorkflowStepIndicator.vue";

export default {
    name: 'VoteVerify',

    components: {
        VotingLayout,
        JetValidationErrors,
        WorkflowStepIndicator,
    },

    props: {
        election: {
            type: Object,
            default: null
        },
        vote_data: {
            type: Object,
            required: true
        },
        user_info: {
            type: Object,
            required: true
        },
        timing_info: {
            type: Object,
            required: true
        },
        voting_summary: {
            type: Object,
            required: true
        },
        debug_code: String,
        has_valid_email: Boolean,
        slug: String,
        useSlugPath: Boolean,
    },

    setup(props) {
        // Initialize form with vote data from the props
        const form = useForm({
            voting_code: "",
            national_selected_candidates: props.vote_data?.national_selected_candidates || [],
            regional_selected_candidates: props.vote_data?.regional_selected_candidates || [],
            no_vote_option: props.vote_data?.no_vote_option || false,
            agree_button: true, // User confirms they've reviewed the vote
        });

        function submit() {
            const currentPath = window.location.pathname;
            const slugMatch = currentPath.match(/\/v\/([^\/]+)\//);

            if (slugMatch) {
                const slug = slugMatch[1];
                form.post(`/v/${slug}/vote/verify`);
            } else {
                form.post("/votes");
            }
        }

        return { form, submit };
    },

    computed: {
        hasErrors() {
            return Object.keys(this.form.errors).length > 0;
        }
    },

    mounted() {
        this.$nextTick(() => {
            const codeInput = document.getElementById('voting_code');
            if (codeInput) {
                codeInput.focus();
                codeInput.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                });
            }
        });
    }
}
</script>

<style scoped>
/* Custom styles */
input::placeholder {
    color: #cbd5e1;
    letter-spacing: 0.1em;
}

input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Truncate text */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Character indicator animation */
@keyframes pulse {
    0%, 100% {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    50% {
        border-color: #60a5fa;
        background-color: #dbeafe;
    }
}

input:focus + div .border-blue-500 {
    animation: pulse 1.5s infinite;
}

/* Button hover effects */
button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Disabled state styling */
button:disabled {
    opacity: 0.7;
}

/* Custom scrollbar styling */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
