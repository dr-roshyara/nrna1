<template>
    <div class="min-h-screen flex flex-col">
        <PublicDigitHeader />

        <div class="flex-1">
        <!-- ACCESSIBILITY: Skip link -->
        <a href="#main-content" class="skip-link">
            {{ $t('pages.vote-show-verify.aria_labels.skip_to_content') }}
        </a>

        <!-- Workflow Step Indicator - Step 5/5 -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator :currentStep="5" />
            </div>
        </div>

        <!-- Page Header -->
        <header role="banner" class="text-center mb-12 pt-8 px-4">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                {{ $t('pages.vote-show-verify.header.title') }}
            </h1>
            <p class="text-xl text-gray-700 mb-4">
                {{ $t('pages.vote-show-verify.header.subtitle') }}
            </p>
            <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full" aria-hidden="true"></div>
        </header>

        <!-- Main Container -->
        <main id="main-content" role="main" class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Demo Vote Success Banner with Verification Code -->
                <div
                    v-if="is_demo && verification_code"
                    class="mb-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl shadow-lg overflow-hidden"
                >
                    <div class="px-8 py-10 text-center text-white">
                        <!-- Success Icon -->
                        <div class="mb-6">
                            <div class="mx-auto w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Success Message -->
                        <h1 class="text-3xl md:text-4xl font-bold mb-4">
                            {{ $t('pages.vote-show-verify.demo_success.title') }}
                        </h1>

                        <!-- Verification Code Display -->
                        <div class="max-w-2xl mx-auto">
                            <div class="bg-white bg-opacity-10 rounded-xl p-6 mb-6">
                                <p class="text-lg opacity-90 mb-4">{{ $t('pages.vote-show-verify.demo_success.code_label') }}</p>
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-4">
                                    <p class="text-3xl font-mono font-bold break-all tracking-wider">
                                        {{ verification_code }}
                                    </p>
                                </div>
                                <button
                                    @click="copyToClipboard"
                                    :class="copied ? 'bg-green-400 text-white' : 'bg-white text-green-600 hover:bg-gray-100'"
                                    class="font-bold py-2 px-6 rounded-lg transition-colors"
                                >
                                    {{ copied ? $t('pages.vote-show-verify.demo_success.copy_button_copied') : $t('pages.vote-show-verify.demo_success.copy_button_initial') }}
                                </button>
                            </div>

                            <!-- Instructions -->
                            <div class="bg-white bg-opacity-10 rounded-xl p-4 text-sm">
                                <p class="mb-2"><strong>{{ $t('pages.vote-show-verify.demo_success.instructions_title') }}</strong></p>
                                <ol class="text-left space-y-1 ml-4">
                                    <li>{{ $t('pages.vote-show-verify.demo_success.instructions_1') }}</li>
                                    <li>{{ $t('pages.vote-show-verify.demo_success.instructions_2') }}</li>
                                    <li>{{ $t('pages.vote-show-verify.demo_success.instructions_3') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Banner (if voted - for real elections) -->
                <div
                    v-else-if="has_voted"
                    class="mb-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl shadow-lg overflow-hidden"
                >
                    <div class="px-8 py-10 text-center text-white">
                        <!-- Success Icon -->
                        <div class="mb-6">
                            <div class="mx-auto w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Congratulations Message -->
                        <h1 class="text-3xl md:text-4xl font-bold mb-4">
                            {{ formatCongratulations(user_name) }}
                        </h1>

                        <!-- Success Description -->
                        <div class="max-w-2xl mx-auto">
                            <div class="bg-white bg-opacity-10 rounded-xl p-6 mb-6">
                                <p class="text-lg md:text-xl mb-4 leading-relaxed">
                                    {{ $t('pages.vote-show-verify.real_success.description') }}
                                </p>
                            </div>

                            <!-- Security Notice -->
                            <div class="flex items-center justify-center space-x-2 text-sm opacity-90">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>{{ $t('pages.vote-show-verify.real_success.security_notice') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Verification Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-8 text-center">
                        <div class="mx-auto w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">
                            {{ $t('pages.vote-show-verify.main_card.title') }}
                        </h2>
                        <p class="text-blue-100 text-lg">
                            {{ $t('pages.vote-show-verify.main_card.subtitle') }}
                        </p>
                    </div>

                    <!-- Election Type Selector -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200 px-8 py-8">
                        <div class="max-w-2xl mx-auto">
                            <p class="text-base md:text-lg font-bold text-gray-900 mb-6 text-center">{{ $t('pages.vote-show-verify.election_type.label') }}</p>
                            <div class="flex flex-col md:flex-row gap-6 md:gap-12">
                                <!-- Real Election Option -->
                                <label class="flex items-center cursor-pointer flex-1 p-6 rounded-xl border-3 hover:bg-white hover:shadow-lg transition-all duration-200"
                                    :class="form.electionType === 'real'
                                        ? 'border-blue-600 bg-blue-50'
                                        : 'border-gray-400 bg-gray-50 hover:border-blue-400'"
                                >
                                    <div class="relative shrink-0">
                                        <input
                                            type="radio"
                                            v-model="form.electionType"
                                            value="real"
                                            class="sr-only"
                                        />
                                        <div
                                            class="w-8 h-8 border-4 rounded-full transition-all duration-200 flex items-center justify-center"
                                            :class="form.electionType === 'real'
                                                ? 'border-blue-600 bg-white'
                                                : 'border-gray-500 bg-white hover:border-blue-500'"
                                        >
                                            <div
                                                v-if="form.electionType === 'real'"
                                                class="w-4 h-4 bg-blue-600 rounded-full"
                                            ></div>
                                        </div>
                                    </div>
                                    <span class="ml-4 text-lg font-bold text-gray-900">{{ $t('pages.vote-show-verify.election_type.real') }}</span>
                                </label>

                                <!-- Demo Election Option -->
                                <label class="flex items-center cursor-pointer flex-1 p-6 rounded-xl border-3 hover:bg-white hover:shadow-lg transition-all duration-200"
                                    :class="form.electionType === 'demo'
                                        ? 'border-green-600 bg-green-50'
                                        : 'border-gray-400 bg-gray-50 hover:border-green-400'"
                                >
                                    <div class="relative shrink-0">
                                        <input
                                            type="radio"
                                            v-model="form.electionType"
                                            value="demo"
                                            class="sr-only"
                                        />
                                        <div
                                            class="w-8 h-8 border-4 rounded-full transition-all duration-200 flex items-center justify-center"
                                            :class="form.electionType === 'demo'
                                                ? 'border-green-600 bg-white'
                                                : 'border-gray-500 bg-white hover:border-green-500'"
                                        >
                                            <div
                                                v-if="form.electionType === 'demo'"
                                                class="w-4 h-4 bg-green-600 rounded-full"
                                            ></div>
                                        </div>
                                    </div>
                                    <span class="ml-4 text-lg font-bold text-gray-900">{{ $t('pages.vote-show-verify.election_type.demo') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Form -->
                    <div class="p-8 md:p-12">
                        <!-- Real Election Section -->
                        <div v-if="form.electionType === 'real'">
                            <!-- Instructions -->
                            <div class="mb-8 text-center">
                                <div class="inline-flex items-center space-x-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $t('pages.vote-show-verify.real_election.instructions_title') }}</span>
                                </div>
                                <p class="text-gray-600 max-w-md mx-auto">
                                    {{ $t('pages.vote-show-verify.real_election.instructions') }}
                                </p>
                            </div>

                            <!-- Real Election Form -->
                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Verification Code Input -->
                                <div class="space-y-2">
                                    <label for="voting_code_real" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <span class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                            </svg>
                                            <span>{{ $t('pages.vote-show-verify.real_election.label') }}</span>
                                        </span>
                                    </label>

                                    <div class="relative">
                                        <input
                                            id="voting_code_real"
                                            type="text"
                                            v-model="form.voting_code"
                                            :placeholder="$t('pages.vote-show-verify.real_election.placeholder')"
                                            class="w-full px-6 py-4 text-lg font-mono border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 bg-gray-50 focus:bg-white"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-100': form.errors.voting_code,
                                                'border-green-300 focus:border-green-500 focus:ring-green-100': form.voting_code && !form.errors.voting_code
                                            }"
                                            autocomplete="off"
                                            :disabled="form.processing"
                                        />

                                        <!-- Input Icon -->
                                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                            <svg
                                                v-if="form.processing"
                                                class="w-5 h-5 text-blue-500 animate-spin"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <svg
                                                v-else-if="form.voting_code && !form.errors.voting_code"
                                                class="w-5 h-5 text-green-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <svg
                                                v-else
                                                class="w-5 h-5 text-gray-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4">
                                    <button
                                        type="submit"
                                        :disabled="form.processing || !form.voting_code"
                                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 disabled:from-gray-300 disabled:to-gray-400 text-white font-bold py-4 px-8 rounded-xl transition-all duration-200 transform hover:scale-[1.02] disabled:scale-100 focus:ring-4 focus:ring-blue-200 shadow-lg disabled:shadow-none"
                                    >
                                        <span v-if="form.processing" class="flex items-center justify-center space-x-3">
                                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>{{ $t('pages.vote-show-verify.real_election.processing_button') }}</span>
                                        </span>
                                        <span v-else class="flex items-center justify-center space-x-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $t('pages.vote-show-verify.real_election.submit_button') }}</span>
                                        </span>
                                    </button>
                                </div>

                                <!-- Error Display -->
                                <jet-validation-errors
                                    v-if="form.errors && Object.keys(form.errors).length > 0"
                                    class="mt-6"
                                />
                            </form>
                        </div>

                        <!-- Demo Election Section -->
                        <div v-else-if="form.electionType === 'demo'">
                            <!-- Instructions -->
                            <div class="mb-8 text-center">
                                <div class="inline-flex items-center space-x-2 bg-green-50 text-green-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $t('pages.vote-show-verify.demo_election.instructions_title') }}</span>
                                </div>
                                <p class="text-gray-600 max-w-md mx-auto">
                                    {{ $t('pages.vote-show-verify.demo_election.instructions') }}
                                </p>
                            </div>

                            <!-- Demo Election Form -->
                            <form @submit.prevent="submitDemo" class="space-y-6">
                                <!-- Verification Code Input -->
                                <div class="space-y-2">
                                    <label for="voting_code_demo" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <span class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $t('pages.vote-show-verify.demo_election.label') }}</span>
                                        </span>
                                    </label>

                                    <div class="relative">
                                        <input
                                            id="voting_code_demo"
                                            type="text"
                                            v-model="form.demo_voting_code"
                                            :placeholder="$t('pages.vote-show-verify.demo_election.placeholder')"
                                            class="w-full px-6 py-4 text-lg font-mono border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 bg-gray-50 focus:bg-white"
                                            :class="{
                                                'border-red-300 focus:border-red-500 focus:ring-red-100': form.errors.demo_voting_code,
                                                'border-green-300 focus:border-green-500 focus:ring-green-100': form.demo_voting_code && !form.errors.demo_voting_code
                                            }"
                                            autocomplete="off"
                                            :disabled="form.processing"
                                        />

                                        <!-- Input Icon -->
                                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                            <svg
                                                v-if="form.processing"
                                                class="w-5 h-5 text-green-500 animate-spin"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <svg
                                                v-else-if="form.demo_voting_code && !form.errors.demo_voting_code"
                                                class="w-5 h-5 text-green-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <svg
                                                v-else
                                                class="w-5 h-5 text-gray-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4">
                                    <button
                                        type="submit"
                                        :disabled="form.processing || !form.demo_voting_code"
                                        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-gray-300 disabled:to-gray-400 text-white font-bold py-4 px-8 rounded-xl transition-all duration-200 transform hover:scale-[1.02] disabled:scale-100 focus:ring-4 focus:ring-green-200 shadow-lg disabled:shadow-none"
                                    >
                                        <span v-if="form.processing" class="flex items-center justify-center space-x-3">
                                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>{{ $t('pages.vote-show-verify.demo_election.processing_button') }}</span>
                                        </span>
                                        <span v-else class="flex items-center justify-center space-x-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $t('pages.vote-show-verify.demo_election.submit_button') }}</span>
                                        </span>
                                    </button>
                                </div>

                                <!-- Error Display -->
                                <jet-validation-errors
                                    v-if="form.errors && Object.keys(form.errors).length > 0"
                                    class="mt-6"
                                />
                            </form>
                        </div>

                        <!-- Help Section -->
                        <div class="mt-12 pt-8 border-t border-gray-100">
                            <div class="text-center">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $t('pages.vote-show-verify.help.title') }}</h3>
                                <div class="grid md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                                    <!-- Help Item 1 -->
                                    <div class="bg-gray-50 rounded-lg p-4 text-left">
                                        <div class="flex items-start space-x-3">
                                            <div class="shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $t('pages.vote-show-verify.help.check_email.title') }}</h4>
                                                <p class="text-sm text-gray-600">{{ $t('pages.vote-show-verify.help.check_email.description') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Help Item 2 -->
                                    <div class="bg-gray-50 rounded-lg p-4 text-left">
                                        <div class="flex items-start space-x-3">
                                            <div class="shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $t('pages.vote-show-verify.help.contact_support.title') }}</h4>
                                                <p class="text-sm text-gray-600">{{ $t('pages.vote-show-verify.help.contact_support.description') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Notice -->
                <div class="mt-8 text-center">
                    <div class="inline-flex items-center space-x-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>{{ $t('pages.vote-show-verify.footer.security_message') }}</span>
                    </div>
                </div>
            </div>
        </main>
        </div>

        <PublicDigitFooter class="px-4" />
    </div>
</template>

<script>
import PublicDigitHeader from "@/Components/Jetstream/PublicDigitHeader.vue";
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";
import VoteFinal from "@/Pages/Vote/VoteFinal";
import WorkflowStepIndicator from "@/Components/Workflow/WorkflowStepIndicator";
import { useForm } from "@inertiajs/vue3";
import JetValidationErrors from "@/Components/Jetstream/ValidationErrors.vue";

export default {
    components: {
        PublicDigitHeader,
        PublicDigitFooter,
        VoteFinal,
        WorkflowStepIndicator,
        JetValidationErrors,
    },
    
    props: {
        vote: Object,
        has_voted: Boolean,
        user_name: String,
        verification_code: String,
        is_demo: Boolean,
        demo_vote_id: Number,
        default_election_type: String,
    },

    setup(props) {
        const form = useForm({
            voting_code: "",
            demo_voting_code: "",
            electionType: props.default_election_type || "real",
        });

        function submit() {
            // For real elections, use voting_code field
            form.post(route('vote.submit_code_to_view_vote'), {
                preserveScroll: true,
                onStart: () => {
                    // Optional: Add any loading state logic here
                },
                onSuccess: () => {
                    // Optional: Add success handling here
                },
                onError: () => {
                    // Form errors are automatically handled by Inertia
                },
                onFinish: () => {
                    // Optional: Add cleanup logic here
                }
            });
        }

        function submitDemo() {
            // For demo elections, create form with demo_voting_code as voting_code
            const demoForm = useForm({
                voting_code: form.demo_voting_code,
                election_type: 'demo'
            });

            demoForm.post(route('vote.submit_code_to_view_vote'), {
                preserveScroll: true,
                onStart: () => {
                    // Form is processing
                },
                onSuccess: () => {
                    // Vote verification successful
                },
                onError: () => {
                    // Errors are handled by Inertia form
                },
                onFinish: () => {
                    // Cleanup after submission
                }
            });
        }

        return { form, submit, submitDemo };
    },

    data() {
        return {
            copied: false,
        };
    },

    computed: {
        user_has_voted() {
            return this.has_voted;
        },
    },

    methods: {
        formatCongratulations(name) {
            const template = this.$t('pages.vote-show-verify.real_success.title');
            return template.replace('{name}', name);
        },
        copyToClipboard() {
            if (this.verification_code) {
                navigator.clipboard.writeText(this.verification_code).then(() => {
                    this.copied = true;
                    setTimeout(() => {
                        this.copied = false;
                    }, 2000);
                }).catch(() => {
                    // Fallback for older browsers
                    const textarea = document.createElement('textarea');
                    textarea.value = this.verification_code;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    this.copied = true;
                    setTimeout(() => {
                        this.copied = false;
                    }, 2000);
                });
            }
        }
    },

    mounted() {
        // Auto-populate demo verification code if it's available
        if (this.verification_code && this.is_demo) {
            this.form.demo_voting_code = this.verification_code;
            // Default to demo election
            this.form.electionType = 'demo';
        } else {
            // Focus on the real election input field
            this.$nextTick(() => {
                const input = document.getElementById('voting_code_real');
                if (input) {
                    input.focus();
                }
            });
        }
    }
};
</script>

<style scoped>
/* Skip Link for Accessibility */
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

/* Custom styles for better UX */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Animation for form validation */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.form-error {
    animation: shake 0.5s ease-in-out;
}

/* Custom focus ring */
.focus-ring:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .container-padding {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>