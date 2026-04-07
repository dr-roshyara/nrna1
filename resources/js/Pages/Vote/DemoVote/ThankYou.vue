<template>
    <nrna-layout>
        <!-- Workflow Step Indicator -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator :currentStep="5" />
            </div>
        </div>

        <div class="min-h-screen m-auto py-8">
            <div class="mt-4 mx-auto flex flex-col items-center justify-center max-w-4xl px-4">

                <!-- Title -->
                <p class="py-4 text-2xl md:text-3xl font-bold text-blue-600 text-center">
                    {{ $t('pages.Vote.DemoVote.ThankYou.title') }}
                </p>

                <!-- Thank You Message -->
                <div class="text-left w-full bg-white rounded-lg shadow-lg p-6 md:p-8 mb-6">
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-3">
                        {{ $t('pages.Vote.DemoVote.ThankYou.thank_you_card.title') }}
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-2">
                        {{ $t('pages.Vote.DemoVote.ThankYou.thank_you_card.salutation') }}
                    </p>
                    <p class="text-gray-700 leading-relaxed mb-2">
                        {{ $t('pages.Vote.DemoVote.ThankYou.thank_you_card.body1') }}
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $t('pages.Vote.DemoVote.ThankYou.thank_you_card.body2') }}
                    </p>
                </div>

                <!-- Receipt + Vote Grid (two columns on md+) -->
                <div class="w-full grid md:grid-cols-2 gap-6 mb-6">

                    <!-- Receipt Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl shadow-lg p-6 border border-gray-200 flex flex-col">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            {{ $t('pages.Vote.DemoVote.ThankYou.receipt.title') }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            {{ $t('pages.Vote.DemoVote.ThankYou.receipt.subtitle') }}
                        </p>

                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg border">
                                <span class="text-gray-600">{{ $t('pages.Vote.DemoVote.ThankYou.receipt.hash_label') }}</span>
                                <span class="font-mono font-bold text-blue-700 tracking-widest text-base select-all">
                                    {{ receipt_hash }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg border">
                                <span class="text-gray-600">{{ $t('pages.Vote.DemoVote.ThankYou.receipt.timestamp_label') }}</span>
                                <span class="font-mono text-gray-900 text-xs">{{ voted_at }}</span>
                            </div>
                        </div>

                        <!-- Verify vote link -->
                        <!-- <a v-if="is_public_demo && verify_url"
                           :href="verify_url"
                           class="w-full mb-2 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $t('pages.Vote.DemoVote.ThankYou.receipt.verify_button') }}
                        </a>
                        <a v-else-if="!is_public_demo"
                           :href="route('demo-vote.verify_to_show')"
                           class="w-full mb-2 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $t('pages.Vote.DemoVote.ThankYou.receipt.verify_button') }}
                        </a>
                         -->

                        <div class="space-y-2">
                            <button @click="copyHash"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                                {{ copied ? $t('pages.Vote.DemoVote.ThankYou.receipt.copied') : $t('pages.Vote.DemoVote.ThankYou.receipt.copy_button') }}
                            </button>
                            <button @click="printPage"
                                    class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                                {{ $t('pages.Vote.DemoVote.ThankYou.receipt.print_button') }}
                            </button>
                        </div>
                    </div>

                    <!-- Votes Summary Grid -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                        <!-- National Posts -->
                        <div v-if="national_posts && national_posts.length > 0" class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                {{ $t('pages.Vote.DemoVote.ThankYou.receipt.national_posts') }}
                            </h4>
                            <div v-for="post in national_posts" :key="post.post_id" class="mb-3">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-1">{{ post.post_name }}</p>
                                <div v-if="post.no_vote" class="flex items-center gap-2 p-2 bg-red-50 rounded-lg border border-red-200">
                                    <span class="text-red-500 text-xs">—</span>
                                    <span class="text-red-700 text-sm italic">{{ $t('pages.Vote.DemoVote.ThankYou.receipt.no_vote') }}</span>
                                </div>
                                <div v-else>
                                    <div v-for="candidate in post.candidates" :key="candidate.candidacy_id"
                                         class="flex items-center gap-2 p-2 bg-green-50 rounded-lg border border-green-200 mb-1">
                                        <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-gray-900 text-sm font-medium">{{ candidate.candidacy_name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Regional Posts -->
                        <div v-if="regional_posts && regional_posts.length > 0">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                {{ $t('pages.Vote.DemoVote.ThankYou.receipt.regional_posts') }}
                            </h4>
                            <div v-for="post in regional_posts" :key="post.post_id" class="mb-3">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-1">{{ post.post_name }}</p>
                                <div v-if="post.no_vote" class="flex items-center gap-2 p-2 bg-red-50 rounded-lg border border-red-200">
                                    <span class="text-red-500 text-xs">—</span>
                                    <span class="text-red-700 text-sm italic">{{ $t('pages.Vote.DemoVote.ThankYou.receipt.no_vote') }}</span>
                                </div>
                                <div v-else>
                                    <div v-for="candidate in post.candidates" :key="candidate.candidacy_id"
                                         class="flex items-center gap-2 p-2 bg-green-50 rounded-lg border border-green-200 mb-1">
                                        <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-gray-900 text-sm font-medium">{{ candidate.candidacy_name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demo Info -->
                <div class="bg-purple-50 border-2 border-purple-200 rounded-lg w-full p-5 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <div>
                            <h4 class="font-bold text-purple-900 mb-1">{{ $t('pages.Vote.DemoVote.ThankYou.demo_info.title') }}</h4>
                            <p class="text-purple-800 text-sm leading-relaxed">{{ $t('pages.Vote.DemoVote.ThankYou.demo_info.body') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="w-full flex flex-col sm:flex-row gap-3 mb-6">
                    <a v-if="is_public_demo"
                       :href="route('public-demo.start')"
                       class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-lg text-center">
                        {{ $t('pages.Vote.DemoVote.ThankYou.buttons.try_again') }}
                    </a>
                    <button v-else @click="voteAgain"
                            class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-lg">
                        {{ $t('pages.Vote.DemoVote.ThankYou.buttons.try_again') }}
                    </button>
                    <button v-if="!is_public_demo" @click="goToDashboard"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold rounded-lg transition">
                        {{ $t('pages.Vote.DemoVote.ThankYou.buttons.dashboard') }}
                    </button>
                </div>

                <!-- Security Footer -->
                <div class="w-full bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <div>
                            <p class="text-blue-900 font-semibold text-sm">{{ $t('pages.Vote.DemoVote.ThankYou.security.title') }}</p>
                            <p class="text-blue-700 text-xs">{{ $t('pages.Vote.DemoVote.ThankYou.security.body') }}</p>
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
    components: { NrnaLayout, WorkflowStepIndicator },

    props: {
        is_public_demo: { type: Boolean, default: false },
        election_type: String,
        receipt_hash: String,
        voted_at: String,
        verify_url: String,
        national_posts: { type: Array, default: () => [] },
        regional_posts: { type: Array, default: () => [] },
        votes_count: Number,
        slug: String,
        useSlugPath: Boolean,
        // legacy props kept for auth-based demo
        name: String,
        election_name: String,
        votes: Array,
    },

    data() {
        return { copied: false }
    },

    methods: {
        copyHash() {
            navigator.clipboard.writeText(this.receipt_hash).then(() => {
                this.copied = true
                setTimeout(() => { this.copied = false }, 2000)
            })
        },
        printPage() {
            window.print()
        },
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
