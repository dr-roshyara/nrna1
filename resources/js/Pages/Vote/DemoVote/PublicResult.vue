<template>
    <nrna-layout>
        <!-- Workflow Step Indicator -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator :currentStep="5" />
            </div>
        </div>

        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 px-4">
            <div class="max-w-3xl mx-auto">

                <!-- Page Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ $t('pages.Vote.DemoVote.PublicResult.header.title') }}
                    </h1>
                    <p class="text-gray-600">{{ $t('pages.Vote.DemoVote.PublicResult.header.subtitle') }}</p>
                    <div class="w-16 h-1 bg-blue-600 mx-auto rounded-full mt-4"></div>
                </div>

                <!-- ── STEP 1: Entry form (not yet verified) ── -->
                <div v-if="!verified" class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-8 text-center">
                        <div class="mx-auto w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-1">
                            {{ $t('pages.Vote.DemoVote.PublicResult.form.card_title') }}
                        </h2>
                        <p class="text-blue-100 text-sm">
                            {{ $t('pages.Vote.DemoVote.PublicResult.form.card_subtitle') }}
                        </p>
                    </div>

                    <div class="p-8">
                        <!-- Instructions -->
                        <div class="mb-6 text-center">
                            <div class="inline-flex items-center gap-2 bg-green-50 text-green-700 px-4 py-2 rounded-full text-sm font-medium mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $t('pages.Vote.DemoVote.PublicResult.form.instructions_badge') }}</span>
                            </div>
                            <p class="text-gray-600 max-w-md mx-auto text-sm">
                                {{ $t('pages.Vote.DemoVote.PublicResult.form.instructions_body') }}
                            </p>
                        </div>

                        <!-- Your receipt code display -->
                        <div v-if="receipt_hash" class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <p class="text-blue-800 text-xs font-semibold uppercase tracking-wider mb-2">
                                {{ $t('pages.Vote.DemoVote.PublicResult.form.your_receipt_label') }}
                            </p>
                            <div class="flex items-center gap-3">
                                <span class="flex-1 font-mono font-bold text-blue-700 tracking-widest text-2xl select-all">
                                    {{ receipt_hash }}
                                </span>
                                <button type="button" @click="copyReceiptCode"
                                        class="shrink-0 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    {{ copiedReceipt ? $t('pages.Vote.DemoVote.PublicResult.form.copied') : $t('pages.Vote.DemoVote.PublicResult.form.copy_button') }}
                                </button>
                            </div>
                            <p class="text-blue-600 text-xs mt-2">
                                {{ $t('pages.Vote.DemoVote.PublicResult.form.receipt_hint') }}
                            </p>
                        </div>

                        <!-- Receipt hash input form -->
                        <form @submit.prevent="submitReceiptHash" class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $t('pages.Vote.DemoVote.PublicResult.form.input_label') }}
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    v-model="enteredHash"
                                    :placeholder="$t('pages.Vote.DemoVote.PublicResult.form.input_placeholder')"
                                    class="w-full px-6 py-4 text-lg font-mono border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all bg-gray-50 focus:bg-white uppercase"
                                    :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-100': errors.receipt_hash }"
                                    autocomplete="off"
                                    :disabled="submitting"
                                />
                                <p v-if="errors.receipt_hash" class="mt-2 text-red-600 text-sm">
                                    {{ errors.receipt_hash }}
                                </p>
                            </div>

                            <button
                                type="submit"
                                :disabled="submitting || !enteredHash"
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-gray-300 disabled:to-gray-400 text-white font-bold py-4 px-8 rounded-xl transition-all shadow-lg disabled:shadow-none"
                            >
                                <span v-if="submitting" class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span>{{ $t('pages.Vote.DemoVote.PublicResult.form.processing') }}</span>
                                </span>
                                <span v-else class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $t('pages.Vote.DemoVote.PublicResult.form.submit_button') }}</span>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ── STEP 2: Verified — show results ── -->
                <template v-if="verified">

                    <!-- Success Banner -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl shadow-lg overflow-hidden mb-6">
                        <div class="px-8 py-8 text-center text-white">
                            <div class="mx-auto w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold mb-1">{{ $t('pages.Vote.DemoVote.PublicResult.success.title') }}</h2>
                            <p class="text-green-100 text-sm">{{ $t('pages.Vote.DemoVote.PublicResult.success.subtitle') }}</p>
                        </div>
                    </div>

                    <!-- Receipt -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                        <div class="bg-gray-800 px-6 py-4">
                            <h2 class="text-white font-bold">{{ $t('pages.Vote.DemoVote.PublicResult.receipt.title') }}</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border">
                                <span class="text-gray-600 text-sm">{{ $t('pages.Vote.DemoVote.PublicResult.receipt.hash_label') }}</span>
                                <span class="font-mono font-bold text-blue-700 tracking-widest text-lg select-all">{{ receipt_hash }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border">
                                <span class="text-gray-600 text-sm">{{ $t('pages.Vote.DemoVote.PublicResult.receipt.timestamp_label') }}</span>
                                <span class="font-mono text-gray-900 text-sm">{{ voted_at }}</span>
                            </div>
                            <div class="flex gap-2 pt-1">
                                <button @click="copyHash"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                                    {{ copied ? $t('pages.Vote.DemoVote.PublicResult.receipt.copied') : $t('pages.Vote.DemoVote.PublicResult.receipt.copy_button') }}
                                </button>
                                <button @click="printPage"
                                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                                    {{ $t('pages.Vote.DemoVote.PublicResult.receipt.print_button') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Voted Candidates -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                        <div class="bg-blue-600 px-6 py-4">
                            <h2 class="text-white font-bold">{{ $t('pages.Vote.DemoVote.PublicResult.votes.title') }}</h2>
                        </div>
                        <div class="p-6">
                            <!-- National -->
                            <div v-if="national_posts && national_posts.length > 0" class="mb-6">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                    {{ $t('pages.Vote.DemoVote.PublicResult.votes.national_posts') }}
                                </h3>
                                <div v-for="post in national_posts" :key="post.post_id" class="mb-4">
                                    <p class="text-sm font-bold text-gray-700 mb-2">{{ post.post_name }}</p>
                                    <div v-if="post.no_vote" class="flex items-center gap-2 p-3 bg-red-50 rounded-lg border border-red-200">
                                        <span class="text-red-500">—</span>
                                        <span class="text-red-700 text-sm italic">{{ $t('pages.Vote.DemoVote.PublicResult.votes.abstained') }}</span>
                                    </div>
                                    <div v-else>
                                        <div v-for="candidate in post.candidates" :key="candidate.candidacy_id"
                                             class="flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200 mb-1">
                                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-gray-900 text-sm font-medium">{{ candidate.candidacy_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Regional -->
                            <div v-if="regional_posts && regional_posts.length > 0">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                    {{ $t('pages.Vote.DemoVote.PublicResult.votes.regional_posts') }}
                                </h3>
                                <div v-for="post in regional_posts" :key="post.post_id" class="mb-4">
                                    <p class="text-sm font-bold text-gray-700 mb-2">{{ post.post_name }}</p>
                                    <div v-if="post.no_vote" class="flex items-center gap-2 p-3 bg-red-50 rounded-lg border border-red-200">
                                        <span class="text-red-500">—</span>
                                        <span class="text-red-700 text-sm italic">{{ $t('pages.Vote.DemoVote.PublicResult.votes.abstained') }}</span>
                                    </div>
                                    <div v-else>
                                        <div v-for="candidate in post.candidates" :key="candidate.candidacy_id"
                                             class="flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200 mb-1">
                                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-gray-900 text-sm font-medium">{{ candidate.candidacy_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="(!national_posts || !national_posts.length) && (!regional_posts || !regional_posts.length)"
                                 class="text-center text-gray-500 py-6">
                                {{ $t('pages.Vote.DemoVote.PublicResult.votes.no_votes') }}
                            </div>
                        </div>
                    </div>

                    <!-- Demo Info -->
                    <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-5 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <div>
                                <h4 class="font-bold text-purple-900 mb-1">{{ $t('pages.Vote.DemoVote.PublicResult.demo_info.title') }}</h4>
                                <p class="text-purple-800 text-sm">{{ $t('pages.Vote.DemoVote.PublicResult.demo_info.body') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a :href="route('public-demo.start')"
                           class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-lg text-center">
                            {{ $t('pages.Vote.DemoVote.PublicResult.buttons.try_again') }}
                        </a>
                        <a href="/"
                           class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold rounded-lg transition text-center">
                            {{ $t('pages.Vote.DemoVote.PublicResult.buttons.home') }}
                        </a>
                    </div>

                </template>

            </div>
        </div>
    </nrna-layout>
</template>

<script>
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator.vue'
import { router, usePage } from '@inertiajs/vue3'

export default {
    components: { NrnaLayout, WorkflowStepIndicator },

    props: {
        verified:        { type: Boolean, default: false },
        receipt_hash:    { type: String, default: null },
        voted_at:        { type: String, default: null },
        national_posts:  { type: Array, default: () => [] },
        regional_posts:  { type: Array, default: () => [] },
        is_public_demo:  { type: Boolean, default: true },
        slug:            { type: String, required: true },
        errors:          { type: Object, default: () => ({}) },
    },

    data() {
        return {
            enteredHash: '',
            submitting: false,
            copied: false,
            copiedReceipt: false,
        }
    },

    methods: {
        copyReceiptCode() {
            if (!this.receipt_hash) return
            navigator.clipboard.writeText(this.receipt_hash).then(() => {
                this.copiedReceipt = true
                setTimeout(() => { this.copiedReceipt = false }, 2000)
            })
        },
        submitReceiptHash() {
            this.submitting = true
            router.post(
                route('public-demo.result.verify', this.slug),
                { receipt_hash: this.enteredHash },
                {
                    preserveScroll: true,
                    onFinish: () => { this.submitting = false },
                }
            )
        },
        copyHash() {
            if (!this.receipt_hash) return
            navigator.clipboard.writeText(this.receipt_hash).then(() => {
                this.copied = true
                setTimeout(() => { this.copied = false }, 2000)
            })
        },
        printPage() {
            window.print()
        },
    }
}
</script>
