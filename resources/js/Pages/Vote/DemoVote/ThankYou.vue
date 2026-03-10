<template>
    <nrna-layout>
        <!-- Workflow Step Indicator - Step 5/5 -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator :currentStep="5" />
            </div>
        </div>

        <div class="min-h-screen m-auto py-2">
            <div class="mt-10 mx-auto flex flex-col items-center justify-center max-w-4xl px-4">
                <p class="py-4 text-2xl md:text-3xl font-bold text-blue-600">Thank you for voting! मतदान गर्नु भएकोमा धन्यवाद।</p>

                <!-- Official Thank You Message - Bilingual -->
                <div class="text-left w-full md:w-3/4 bg-white rounded-lg shadow-lg p-6 md:p-8 mb-8">
                    <!-- English Section -->
                    <div class="mb-6 md:mb-8">
                        <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-3">
                            🎮 Demo Election - Thank You Message
                        </h2>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            Dear Voter,
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            Thank you very much for participating in this demo election. Your vote is an important part of the democratic process.
                            This test election demonstrates how our online voting system works securely and transparently.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            Your votes have been recorded successfully and will be counted when the election closes.
                            All votes are kept anonymous and secure throughout the process.
                        </p>
                    </div>

                    <div class="border-t border-gray-300 my-6 md:my-8"></div>

                    <!-- Nepali Section -->
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-3">
                            नेपाली संदेश
                        </h2>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            आदरणिय मतदाता ज्यु,
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            यस अमुल्य मतदान गर्नु भएकोमा धेरै धन्यवाद। आपनो मत लोकतान्त्रिक प्रक्रियाको महत्वपूर्ण भाग हो।
                            यो परीक्षण निर्वाचन हाम्रो अनलाइन मतदान प्रणालीले कसरी सुरक्षित र पारदर्शी तरिकाले काम गर्छ भनी प्रदर्शन गर्दछ।
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            आपनो मत सफलतापूर्वक दर्ता गरिएको छ र निर्वाचन बन्द हुँदा गणना गरिनेछ।
                            सम्पूर्ण प्रक्रियामा सबै मत गुप्त र सुरक्षित राखिन्छ।
                        </p>
                    </div>
                </div>

                <!-- Votes Summary Card -->
                <div class="bg-white rounded-lg shadow-lg w-full md:w-3/4 overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <h3 class="text-xl md:text-2xl font-bold text-white flex items-center">
                            <svg class="w-5 h-5 md:w-6 md:h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Your Vote Summary
                        </h3>
                    </div>

                    <div class="p-6 md:p-8 space-y-4">
                        <!-- Election Info -->
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-sm">
                            <p class="text-green-900 font-semibold">
                                {{ election_name }}
                            </p>
                            <p class="text-green-700 text-sm mt-1">
                                Total positions voted: <strong>{{ votes_count }}</strong>
                            </p>
                        </div>

                        <!-- Votes List -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-700 text-sm uppercase tracking-wider">Selected Candidates:</h4>
                            <div
                                v-for="(vote, index) in votes"
                                :key="index"
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border-l-4 border-green-500"
                            >
                                <div class="grow">
                                    <p class="text-sm text-gray-600 font-medium">{{ vote.post_name }}</p>
                                    <p class="font-semibold text-gray-900">✓ {{ vote.candidate_name }}</p>
                                </div>
                                <div class="text-xs text-gray-500 shrink-0 ml-2">
                                    {{ vote.submitted_at }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demo Info Section -->
                <div class="bg-purple-50 border-2 border-purple-200 rounded-lg w-full md:w-3/4 p-6 md:p-8 mb-8">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <div>
                            <h4 class="font-bold text-purple-900 mb-2">🎮 Demo Election Mode</h4>
                            <p class="text-purple-800 text-sm leading-relaxed">
                                This is a test election. You can vote multiple times for testing and demonstration purposes.
                                In real elections, you would only be able to vote once per election.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="w-full md:w-3/4 flex flex-col sm:flex-row gap-3 mb-8">
                    <button
                        @click="voteAgain"
                        class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-lg"
                    >
                        🗳️ Vote Again (Demo Testing)
                    </button>
                    <button
                        @click="goToDashboard"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold rounded-lg transition"
                    >
                        Return to Dashboard
                    </button>
                </div>

                <!-- Security Info -->
                <div class="w-full md:w-3/4 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 md:p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <div>
                            <p class="text-blue-900 font-semibold text-sm md:text-base">Secure & Anonymous</p>
                            <p class="text-blue-700 text-xs md:text-sm">All votes are recorded anonymously and securely encrypted.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nrna-layout>
</template>

<script>
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator.vue'
import { router } from '@inertiajs/vue3'

export default {
    props: {
        name: String,
        election_name: String,
        votes_count: Number,
        votes: Array,
        slug: String,
        useSlugPath: Boolean,
    },
    components: {
        NrnaLayout,
        WorkflowStepIndicator,
    },
    methods: {
        goToDashboard() {
            router.get(route('dashboard'))
        },
        voteAgain() {
            router.get(
                route(this.useSlugPath ? 'slug.demo-vote.create' : 'demo-vote.create',
                      this.useSlugPath ? { vslug: this.slug } : {})
            )
        }
    }
}
</script>
