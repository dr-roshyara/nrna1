<template>
    <nrna-layout>
        <main class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50">

            <!-- Hero Section -->
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600/5 to-transparent pointer-events-none"></div>
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium mb-5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $t('pages.Vote.DemoVote.Guide.hero.badge') }}</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-4">
                        {{ $t('pages.Vote.DemoVote.Guide.hero.title') }}
                        <span class="block text-purple-600">{{ $t('pages.Vote.DemoVote.Guide.hero.title_accent') }}</span>
                    </h1>
                    <p class="text-xl text-slate-600 max-w-2xl mx-auto mb-8">
                        {{ $t('pages.Vote.DemoVote.Guide.hero.subtitle') }}
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a :href="start_url"
                           class="group inline-flex items-center gap-2 px-8 py-4 bg-purple-600 text-white font-semibold rounded-xl hover:bg-purple-700 transition-all shadow-lg hover:shadow-xl">
                            <span>🎪</span>
                            {{ $t('pages.Vote.DemoVote.Guide.hero.cta_start') }}
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <button @click="scrollToSteps"
                                class="px-8 py-4 border-2 border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                            {{ $t('pages.Vote.DemoVote.Guide.hero.cta_learn') }} ↓
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step Overview -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-10" ref="stepsSection">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-5 text-center">
                        {{ $t('pages.Vote.DemoVote.Guide.overview.title') }}
                    </h2>
                    <div class="flex flex-wrap sm:flex-nowrap justify-between items-center gap-2">
                        <div v-for="(step, idx) in stepDefs" :key="step.number" class="flex-1 min-w-[60px] text-center relative">
                            <div v-if="idx < stepDefs.length - 1"
                                 class="absolute top-5 left-1/2 w-full h-0.5 bg-slate-200 hidden sm:block"></div>
                            <div class="relative z-10 w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold mb-1 transition-colors"
                                 :class="step.color">
                                {{ step.number }}
                            </div>
                            <p class="text-xs font-medium text-slate-600">{{ $t(step.labelKey) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Cards -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 space-y-6">

                <!-- Step 1: Code Entry -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-purple-200 transition-all">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="shrink-0">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-2xl font-bold shadow-md">1</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $t('pages.Vote.DemoVote.Guide.step1.title') }}</h3>
                            <p class="text-slate-600 mb-4">{{ $t('pages.Vote.DemoVote.Guide.step1.description') }}</p>

                            <!-- Code display demo -->
                            <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-200">
                                <p class="text-sm font-medium text-slate-700 mb-3">{{ $t('pages.Vote.DemoVote.Guide.step1.example_label') }}</p>
                                <div class="flex items-center gap-4 flex-wrap">
                                    <div class="bg-white border-2 border-blue-400 rounded-xl px-6 py-3 text-2xl font-mono font-bold tracking-wider text-blue-800 shadow-sm select-all">
                                        A7B3K9M2
                                    </div>
                                    <span class="text-slate-400 text-lg">→</span>
                                    <div class="flex gap-1">
                                        <div v-for="i in 8" :key="i"
                                             class="w-9 h-9 rounded-lg border-2 border-slate-300 bg-white flex items-center justify-center font-mono text-slate-400 text-sm">_</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start gap-2 text-sm text-amber-700 bg-amber-50 rounded-lg p-3">
                                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $t('pages.Vote.DemoVote.Guide.step1.note') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Agreement -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-purple-200 transition-all">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="shrink-0">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-white flex items-center justify-center text-2xl font-bold shadow-md">2</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $t('pages.Vote.DemoVote.Guide.step2.title') }}</h3>
                            <p class="text-slate-600 mb-4">{{ $t('pages.Vote.DemoVote.Guide.step2.description') }}</p>
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                <label class="flex items-center gap-3 cursor-default">
                                    <input type="checkbox" checked disabled class="w-5 h-5 text-purple-600 rounded border-slate-300 shrink-0"/>
                                    <span class="text-sm text-slate-600">{{ $t('pages.Vote.DemoVote.Guide.step2.checkbox_example') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Vote Selection -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-purple-200 transition-all">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="shrink-0">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 text-white flex items-center justify-center text-2xl font-bold shadow-md">3</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $t('pages.Vote.DemoVote.Guide.step3.title') }}</h3>
                            <p class="text-slate-600 mb-4">{{ $t('pages.Vote.DemoVote.Guide.step3.description') }}</p>

                            <!-- Candidate card example -->
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 mb-4">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-lg">👤</div>
                                        <div>
                                            <p class="font-medium text-slate-800 text-sm">Dr. Anna Schmidt</p>
                                            <p class="text-xs text-slate-500">{{ $t('pages.Vote.DemoVote.Guide.step3.candidate_example_role') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium">{{ $t('pages.Vote.DemoVote.Guide.step3.select_btn') }}</div>
                                        <div class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium bg-white">{{ $t('pages.Vote.DemoVote.Guide.step3.skip_btn') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- No-vote box -->
                            <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl">⏭️</span>
                                    <div>
                                        <p class="font-semibold text-amber-800 mb-1">{{ $t('pages.Vote.DemoVote.Guide.step3.no_vote_title') }}</p>
                                        <p class="text-sm text-amber-700">{{ $t('pages.Vote.DemoVote.Guide.step3.no_vote_body') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Confirm + Why Two Codes -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-purple-200 transition-all">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="shrink-0">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 text-white flex items-center justify-center text-2xl font-bold shadow-md">4</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $t('pages.Vote.DemoVote.Guide.step4.title') }}</h3>
                            <p class="text-slate-600 mb-4">{{ $t('pages.Vote.DemoVote.Guide.step4.description') }}</p>

                            <!-- Why two codes -->
                            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5 mb-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-indigo-800 mb-1">{{ $t('pages.Vote.DemoVote.Guide.step4.two_codes_title') }}</p>
                                        <p class="text-sm text-indigo-700">{{ $t('pages.Vote.DemoVote.Guide.step4.two_codes_body') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Selection preview -->
                            <div class="bg-white rounded-xl border border-slate-200 p-4">
                                <p class="text-sm font-medium text-slate-700 mb-2">📋 {{ $t('pages.Vote.DemoVote.Guide.step4.preview_label') }}</p>
                                <ul class="space-y-1 text-sm text-slate-600">
                                    <li>• {{ $t('pages.Vote.DemoVote.Guide.step4.preview_line1') }}</li>
                                    <li>• {{ $t('pages.Vote.DemoVote.Guide.step4.preview_line2') }}</li>
                                    <li>• {{ $t('pages.Vote.DemoVote.Guide.step4.preview_line3') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Done + Receipt -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-purple-200 transition-all">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="shrink-0">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 text-white flex items-center justify-center text-2xl font-bold shadow-md">5</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $t('pages.Vote.DemoVote.Guide.step5.title') }}</h3>
                            <p class="text-slate-600 mb-4">{{ $t('pages.Vote.DemoVote.Guide.step5.description') }}</p>

                            <!-- Receipt code example -->
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-200 mb-4 text-center">
                                <div class="inline-block bg-white border-2 border-green-400 rounded-xl px-6 py-3 mb-2">
                                    <code class="text-xl font-mono font-bold tracking-wider text-green-800 select-all">A7B3K9M2P4R6</code>
                                </div>
                                <p class="text-sm text-green-700">{{ $t('pages.Vote.DemoVote.Guide.step5.receipt_caption') }}</p>
                            </div>

                            <!-- Anonymity guarantee -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-blue-800 mb-1">{{ $t('pages.Vote.DemoVote.Guide.step5.anon_title') }}</p>
                                        <p class="text-sm text-blue-700">{{ $t('pages.Vote.DemoVote.Guide.step5.anon_body') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Verify demo -->
                            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                                <p class="text-sm font-medium text-slate-700 mb-2">🔍 {{ $t('pages.Vote.DemoVote.Guide.step5.verify_label') }}</p>
                                <div class="flex gap-2">
                                    <input type="text"
                                           :placeholder="$t('pages.Vote.DemoVote.Guide.step5.verify_placeholder')"
                                           class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-sm font-mono bg-white" disabled/>
                                    <div class="px-4 py-2 bg-slate-700 text-white rounded-lg text-sm">
                                        {{ $t('pages.Vote.DemoVote.Guide.step5.verify_btn') }}
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">{{ $t('pages.Vote.DemoVote.Guide.step5.verify_note') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Final CTA -->
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-8 text-center text-white">
                    <h2 class="text-2xl font-bold mb-3">{{ $t('pages.Vote.DemoVote.Guide.cta.title') }}</h2>
                    <p class="text-purple-100 mb-6">{{ $t('pages.Vote.DemoVote.Guide.cta.subtitle') }}</p>
                    <a :href="start_url"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-white text-purple-700 font-semibold rounded-xl hover:bg-purple-50 transition-all shadow-lg">
                        <span>🎪</span>
                        {{ $t('pages.Vote.DemoVote.Guide.cta.button') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Footer badges -->
            <div class="border-t border-slate-200 py-6 text-center text-sm text-slate-400">
                <div class="flex flex-wrap justify-center gap-6">
                    <span>♿ {{ $t('pages.Vote.DemoVote.Guide.footer.accessible') }}</span>
                    <span>🔒 {{ $t('pages.Vote.DemoVote.Guide.footer.gdpr') }}</span>
                    <span>🗳️ {{ $t('pages.Vote.DemoVote.Guide.footer.encrypted') }}</span>
                </div>
            </div>

        </main>
    </nrna-layout>
</template>

<script>
import NrnaLayout from '@/Layouts/NrnaLayout.vue'
import { useMeta } from '@/composables/useMeta'

export default {
    components: { NrnaLayout },

    props: {
        start_url: { type: String, required: true },
    },

    setup() {
        useMeta({ pageKey: 'demo-guide' })
    },

    data() {
        return {
            stepDefs: [
                { number: 1, labelKey: 'pages.Vote.DemoVote.Guide.overview.step1', color: 'bg-blue-100 text-blue-700' },
                { number: 2, labelKey: 'pages.Vote.DemoVote.Guide.overview.step2', color: 'bg-emerald-100 text-emerald-700' },
                { number: 3, labelKey: 'pages.Vote.DemoVote.Guide.overview.step3', color: 'bg-purple-100 text-purple-700' },
                { number: 4, labelKey: 'pages.Vote.DemoVote.Guide.overview.step4', color: 'bg-orange-100 text-orange-700' },
                { number: 5, labelKey: 'pages.Vote.DemoVote.Guide.overview.step5', color: 'bg-green-100 text-green-700' },
            ],
        }
    },

    mounted() {
        this.injectStructuredData()
    },

    methods: {
        scrollToSteps() {
            this.$refs.stepsSection?.scrollIntoView({ behavior: 'smooth' })
        },

        injectStructuredData() {
            const baseUrl = window.location.origin
            const guideUrl = `${baseUrl}/public-demo/guide`

            // HowTo schema
            const howTo = {
                '@context': 'https://schema.org',
                '@type': 'HowTo',
                name: this.$t('pages.Vote.DemoVote.Guide.hero.title') + ' ' + this.$t('pages.Vote.DemoVote.Guide.hero.title_accent'),
                description: this.$t('pages.Vote.DemoVote.Guide.hero.subtitle'),
                totalTime: 'PT5M',
                step: [
                    { '@type': 'HowToStep', position: 1, name: this.$t('pages.Vote.DemoVote.Guide.overview.step1'), text: 'Your personal 8-character code is displayed on screen. Enter it to start.' },
                    { '@type': 'HowToStep', position: 2, name: this.$t('pages.Vote.DemoVote.Guide.overview.step2'), text: 'Read and accept the election rules to proceed.' },
                    { '@type': 'HowToStep', position: 3, name: this.$t('pages.Vote.DemoVote.Guide.overview.step3'), text: 'Choose your preferred candidates for each position. A no-vote option is available.' },
                    { '@type': 'HowToStep', position: 4, name: this.$t('pages.Vote.DemoVote.Guide.overview.step4'), text: 'Review your selections and confirm by entering your code a second time.' },
                    { '@type': 'HowToStep', position: 5, name: this.$t('pages.Vote.DemoVote.Guide.overview.step5'), text: 'Your vote is counted. Save your 12-character receipt code to verify your vote later.' },
                ],
            }

            // BreadcrumbList schema
            const breadcrumb = {
                '@context': 'https://schema.org',
                '@type': 'BreadcrumbList',
                itemListElement: [
                    { '@type': 'ListItem', position: 1, name: 'Home', item: baseUrl },
                    { '@type': 'ListItem', position: 2, name: 'Demo Election Guide', item: guideUrl },
                ],
            }

            // Organization schema
            const organization = {
                '@context': 'https://schema.org',
                '@type': 'Organization',
                name: 'PublicDigit',
                url: baseUrl,
                description: 'Secure online voting platform for democratic organizations',
                potentialAction: { '@type': 'ViewAction', target: guideUrl },
            }

            ;[howTo, breadcrumb, organization].forEach((schema, i) => {
                const id = `ld-json-guide-${i}`
                let el = document.getElementById(id)
                if (!el) {
                    el = document.createElement('script')
                    el.type = 'application/ld+json'
                    el.id = id
                    document.head.appendChild(el)
                }
                el.textContent = JSON.stringify(schema)
            })
        },
    },
}
</script>
