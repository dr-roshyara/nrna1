<template>
    <VotingLayout
        :election="election"
        :page-title="`${$t('pages.vote-verify.header.title')} (Demo)`"
        :current-step="4"
        :total-steps="5"
    >
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Workflow Step Indicator - Step 4/5 -->
                <WorkflowStepIndicator workflow="VOTING" :currentStep="4" />

                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ $t('pages.vote-verify.header.title') }} <span class="text-sm bg-purple-100 text-purple-800 px-3 py-1 rounded-full">🎮 DEMO</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 mb-4">
                        {{ $t('pages.vote-verify.header.subtitle') }}
                    </p>

                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500/10 to-indigo-500/10 border border-blue-200 rounded-full">
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
                        <div class="bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-200 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
                            <div class="flex items-start gap-3 md:gap-4">
                                <div class="flex-shrink-0">
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

                        <!-- Verification Summary Section -->
                        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg p-4 md:p-8">
                            <!-- Section Header -->
                            <div class="mb-6 md:mb-8">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">
                                        Your Selected Votes
                                    </h2>
                                </div>
                                <p class="text-gray-600">
                                    Please review all your selections below before final submission.
                                </p>
                            </div>

                            <!-- Vote Summary List -->
                            <div class="divide-y">
                                <div
                                    v-for="(vote, index) in selected_votes"
                                    :key="index"
                                    class="p-4 md:p-6 hover:bg-indigo-50 transition"
                                >
                                    <div class="flex gap-4 md:gap-6 items-start">
                                        <!-- Candidate Image -->
                                        <div class="flex-shrink-0">
                                            <div class="w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden bg-gray-100">
                                                <img
                                                    v-if="vote.candidate_image"
                                                    :src="vote.candidate_image"
                                                    :alt="vote.candidate_name"
                                                    class="w-full h-full object-cover"
                                                />
                                                <div v-else class="flex items-center justify-center h-full">
                                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Vote Info -->
                                        <div class="flex-grow">
                                            <p class="text-xs md:text-sm text-gray-600 mb-1">Position:</p>
                                            <p class="text-lg md:text-xl font-bold text-gray-900 mb-3">
                                                {{ vote.post_name }}
                                            </p>
                                            <p class="text-xs md:text-sm text-gray-600 mb-1">Selected Candidate:</p>
                                            <p class="text-base md:text-lg font-semibold text-green-600">
                                                ✓ {{ vote.candidate_name }}
                                            </p>
                                        </div>

                                        <!-- Checkmark -->
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 md:w-7 md:h-7 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Demo Mode Info -->
                        <div class="bg-purple-50 border-2 border-purple-200 rounded-xl md:rounded-2xl p-4 md:p-6">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-purple-900 mb-1">🎮 Demo Election Mode</h3>
                                    <p class="text-purple-800 text-sm">
                                        This is a test election. You can vote multiple times for testing purposes. In real elections, you would only vote once.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button
                                @click="goBack"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition order-2 sm:order-1"
                            >
                                ← Change My Votes
                            </button>
                            <button
                                @click="submitVotes"
                                :disabled="loading"
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed order-1 sm:order-2"
                            >
                                <span v-if="loading" class="inline-block mr-2">⏳</span>
                                {{ loading ? 'Submitting...' : 'Submit My Vote' }}
                            </button>
                        </div>
                    </div>

                    <!-- Right Column - Vote Summary Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg p-4 md:p-6 relative md:sticky md:top-8">
                            <!-- Summary Stats -->
                            <div class="mb-6">
                                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Votes Summary
                                </h3>

                                <!-- Quick Stats -->
                                <div class="grid grid-cols-2 gap-2 md:gap-3 mb-6">
                                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-3 md:p-4 rounded-lg text-center">
                                        <div class="text-2xl md:text-3xl font-bold text-green-600">{{ total_votes }}</div>
                                        <div class="text-xs text-green-700 font-medium">Votes Selected</div>
                                    </div>
                                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-3 md:p-4 rounded-lg text-center">
                                        <div class="text-2xl md:text-3xl font-bold text-blue-600">{{ selected_votes.length }}</div>
                                        <div class="text-xs text-blue-700 font-medium">Positions</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vote List -->
                            <div class="border-t border-gray-200 pt-4 md:pt-6">
                                <h4 class="font-semibold text-gray-700 mb-3 text-xs md:text-sm uppercase tracking-wider">
                                    Your Selections
                                </h4>
                                <div class="space-y-3">
                                    <div
                                        v-for="(vote, idx) in selected_votes"
                                        :key="`summary-${idx}`"
                                        class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                                    >
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="text-xs md:text-sm font-medium text-gray-800 truncate">
                                                {{ vote.post_name }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 flex-shrink-0 ml-2">
                                                ✓
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-600 truncate">
                                            {{ vote.candidate_name }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Badge -->
                            <div class="mt-6 p-3 md:p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600 mr-2 md:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <div>
                                        <p class="font-medium text-blue-800 text-xs md:text-sm">
                                            Secure & Anonymous
                                        </p>
                                        <p class="text-blue-600 text-xs">
                                            Your votes are encrypted
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

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  election_name: String,
  selected_votes: Array,
  total_votes: Number,
  slug: String,
  useSlugPath: Boolean,
})

const loading = ref(false)

const goBack = () => {
  Inertia.get(
    route(props.useSlugPath ? 'slug.demo-vote.create' : 'demo-vote.create',
          props.useSlugPath ? { vslug: props.slug } : {})
  )
}

const submitVotes = () => {
  loading.value = true

  const form = useForm({
    confirmed: true,
  })

  const routeName = props.useSlugPath ? 'slug.demo-vote.store' : 'demo-vote.store'
  const params = props.useSlugPath ? { vslug: props.slug } : {}

  form.post(route(routeName, params), {
    onError: () => {
      loading.value = false
    },
  })
}
</script>
