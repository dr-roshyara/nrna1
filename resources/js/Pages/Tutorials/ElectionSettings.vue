<script setup>
import { useMeta } from '@/composables/useMeta'
import { computed } from 'vue'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'

useMeta({
  pageKey: 'tutorials.election-settings',
  url: '/help/election-setup',
  type: 'article',
})

const locale = computed(() => (window.location.pathname.includes('/de') ? 'de' : window.location.pathname.includes('/np') ? 'np' : 'en'))

// Import locale JSON based on current language
const localeModule = import.meta.glob('../../locales/pages/Tutorials/ElectionSettings/*.json', { eager: true })
const t = computed(() => {
  const lang = locale.value
  const key = `../../locales/pages/Tutorials/ElectionSettings/${lang}.json`
  return localeModule[key]?.default || localeModule['../../locales/pages/Tutorials/ElectionSettings/en.json'].default
})

// Track active section for scroll-spy
const activeSection = computed(() => 'overview')
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <!-- PublicDigit Header with breadcrumbs -->
    <PublicDigitHeader
      :breadcrumbs="[
        { label: $t('navigation.home', 'Home'), url: '/' },
        { label: $t('navigation.help', 'Help & Guides'), url: null },
        { label: t.page?.title || 'Election Setup Guide', url: null }
      ]"
      :disable-language-selector="false"
    />

    <div class="flex-1">
    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-teal-600 via-teal-500 to-cyan-600 text-white py-16 md:py-20 border-b-4 border-amber-400">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center gap-2">
          <span class="inline-block px-4 py-1 bg-white/20 backdrop-blur text-sm font-bold rounded-full border border-white/30">
            {{ t.meta.badge_label }}
          </span>
          <span class="flex items-center gap-1 text-sm text-white/80">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ t.meta.read_time }}
          </span>
        </div>
        <h1 class="text-5xl md:text-6xl font-black mb-4 leading-tight">
          {{ t.page.title }}
        </h1>
        <p class="text-xl md:text-2xl text-white/95 max-w-3xl font-light leading-relaxed">
          {{ t.page.subtitle }}
        </p>
      </div>
    </header>

    <main class="py-12 md:py-16 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">

          <!-- Sticky Sidebar TOC - Desktop only -->
          <aside class="hidden lg:block lg:col-span-1">
            <nav class="sticky top-8 bg-white rounded-xl border-2 border-slate-200 shadow-lg p-6" role="navigation" aria-label="Table of contents">
              <p class="text-xs font-black text-slate-600 uppercase tracking-widest mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM15 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z" />
                </svg>
                {{ t.toc.label }}
              </p>
              <nav class="space-y-2">
                <a
                  v-for="item in t.toc.items"
                  :key="item.id"
                  :href="'#' + item.id"
                  class="block py-2.5 px-3 text-sm font-semibold text-slate-700 hover:text-teal-700 hover:bg-teal-50 rounded-lg transition-colors duration-150 border-l-3 border-transparent hover:border-teal-500"
                >
                  {{ item.label }}
                </a>
              </nav>
            </nav>
          </aside>

          <!-- Main Content -->
          <div class="lg:col-span-3 space-y-10">

            <!-- Mobile TOC Accordion -->
            <details class="lg:hidden bg-white rounded-xl border-2 border-slate-200 shadow-md overflow-hidden group">
              <summary class="px-6 py-4 font-bold text-slate-900 cursor-pointer select-none flex items-center justify-between hover:bg-slate-50 transition-colors">
                <span class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM15 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z" />
                  </svg>
                  {{ t.toc.label }}
                </span>
                <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </summary>
              <div class="px-6 pb-4 space-y-2 border-t border-slate-100">
                <a
                  v-for="item in t.toc.items"
                  :key="item.id"
                  :href="'#' + item.id"
                  class="block py-2 text-sm font-semibold text-slate-700 hover:text-teal-700 transition-colors"
                >
                  {{ item.label }}
                </a>
              </div>
            </details>

            <!-- Section: Overview -->
            <section id="overview" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 text-white font-bold text-lg flex items-center justify-center">1</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_overview.heading }}</h2>
              </div>
              <p class="text-lg text-slate-700 leading-relaxed mb-6">{{ t.section_overview.body }}</p>
              <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-lg p-6 flex gap-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                  <p class="font-bold text-blue-900 text-lg">💡 {{ t.section_overview.tip_label }}</p>
                  <p class="text-blue-800 text-base mt-2">{{ t.section_overview.tip }}</p>
                </div>
              </div>
            </section>

            <!-- Section: Before You Begin -->
            <section id="prerequisites" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-amber-400 to-orange-600 text-white font-bold text-lg flex items-center justify-center">2</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_prerequisites.heading }}</h2>
              </div>
              <p class="text-slate-700 mb-8 text-lg font-medium">{{ t.section_prerequisites.intro }}</p>
              <div class="space-y-4">
                <div v-for="(step, idx) in t.section_prerequisites.steps" :key="idx" class="flex gap-4 p-5 bg-gradient-to-r from-slate-50 to-white rounded-lg border border-slate-200 hover:border-amber-300 transition-colors">
                  <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">{{ idx + 1 }}</div>
                  <div>
                    <p class="font-bold text-slate-900 text-lg">{{ step.title }}</p>
                    <p class="text-slate-600 text-base mt-1">{{ step.body }}</p>
                  </div>
                </div>
              </div>
            </section>

            <!-- Section: Voter Access Control -->
            <section id="access" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-cyan-400 to-teal-600 text-white font-bold text-lg flex items-center justify-center">3</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_access.heading }}</h2>
              </div>
              <p class="text-slate-700 mb-8 text-lg">{{ t.section_access.intro }}</p>

              <!-- IP Restriction Subsection -->
              <div class="mb-10 pb-10 border-b-2 border-slate-200">
                <h3 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                  {{ t.section_access.ip_restriction.heading }}
                </h3>
                <p class="text-slate-700 mb-6 text-base leading-relaxed">{{ t.section_access.ip_restriction.body }}</p>

                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-lg p-6 mb-8 flex gap-4">
                  <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                  <div>
                    <p class="font-bold text-amber-900 text-lg">🔍 {{ t.section_access.ip_restriction.analogy_label }}</p>
                    <p class="text-amber-800 text-base mt-2">{{ t.section_access.ip_restriction.analogy }}</p>
                  </div>
                </div>

                <!-- Max Per IP Table -->
                <div class="mb-8 p-5 bg-slate-50 rounded-lg border border-slate-200">
                  <p class="font-bold text-slate-900 text-lg mb-4">{{ t.section_access.ip_restriction.max_per_ip.heading }}</p>
                  <p class="text-slate-700 mb-4 text-base">{{ t.section_access.ip_restriction.max_per_ip.body }}</p>
                  <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ t.section_access.ip_restriction.max_per_ip.table_caption }}</p>
                  <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                      <thead class="bg-gradient-to-r from-slate-100 to-slate-50 border-b-2 border-slate-300">
                        <tr>
                          <th class="text-left px-4 py-3 font-bold text-slate-900">Scenario</th>
                          <th class="text-right px-4 py-3 font-bold text-slate-900">Recommended</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-200">
                        <tr v-for="(row, idx) in t.section_access.ip_restriction.max_per_ip.table" :key="idx" class="hover:bg-teal-50 transition-colors">
                          <td class="px-4 py-4 text-slate-700">{{ row.scenario }}</td>
                          <td class="text-right px-4 py-4 font-bold text-teal-700 font-mono">{{ row.recommended }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- IP Whitelist Subsection -->
                <div class="p-5 bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg border-2 border-blue-200">
                  <p class="font-bold text-slate-900 text-lg mb-3">{{ t.section_access.ip_restriction.whitelist.heading }}</p>
                  <p class="text-slate-700 mb-5 text-base">{{ t.section_access.ip_restriction.whitelist.body }}</p>
                  <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-3">{{ t.section_access.ip_restriction.whitelist.format_label }}</p>
                  <ul class="space-y-2 mb-5">
                    <li v-for="(fmt, idx) in t.section_access.ip_restriction.whitelist.formats" :key="idx" class="flex items-start gap-3">
                      <span class="text-teal-600 font-bold mt-1">✓</span>
                      <code class="bg-slate-700 text-yellow-300 px-2 py-1 rounded font-mono text-xs">{{ fmt }}</code>
                    </li>
                  </ul>
                  <div class="bg-white border border-blue-200 rounded p-4 mt-4">
                    <p class="font-bold text-blue-900 text-sm mb-2">❓ {{ t.section_access.ip_restriction.whitelist.cidr_tip_label }}</p>
                    <p class="text-blue-800 text-sm">{{ t.section_access.ip_restriction.whitelist.cidr_tip }}</p>
                  </div>
                </div>
              </div>
            </section>

            <!-- Section: Ballot Options -->
            <section id="ballot" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 text-white font-bold text-lg flex items-center justify-center">4</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_ballot.heading }}</h2>
              </div>
              <p class="text-slate-700 mb-8 text-lg">{{ t.section_ballot.intro }}</p>

              <!-- No Vote Option -->
              <div class="mb-10 pb-10 border-b-2 border-slate-200">
                <h3 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                  {{ t.section_ballot.no_vote.heading }}
                </h3>
                <p class="text-slate-700 mb-5 text-base leading-relaxed">{{ t.section_ballot.no_vote.body }}</p>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg p-6 mb-6 flex gap-4">
                  <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <div>
                    <p class="font-bold text-green-900 text-lg">{{ t.section_ballot.no_vote.when_label }}</p>
                    <p class="text-green-800 text-base mt-1">{{ t.section_ballot.no_vote.when }}</p>
                  </div>
                </div>
                <p class="text-slate-600 text-base italic bg-slate-50 p-4 rounded-lg border border-slate-200">💬 {{ t.section_ballot.no_vote.label_tip }}</p>
              </div>

              <!-- Selection Constraints Table -->
              <div class="space-y-4">
                <p class="font-bold text-slate-900 text-lg">{{ t.section_ballot.selection_constraint.heading }}</p>
                <p class="text-slate-700 text-base mb-5">{{ t.section_ballot.selection_constraint.body }}</p>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ t.section_ballot.selection_constraint.table_caption }}</p>
                <div class="space-y-3">
                  <div v-for="(row, idx) in t.section_ballot.selection_constraint.table" :key="idx" class="p-5 bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg border-2 border-slate-200 hover:border-purple-400 hover:shadow-md transition-all">
                    <p class="font-bold text-slate-900 text-lg">{{ row.type }}</p>
                    <p class="text-slate-700 text-base mt-2">{{ row.description }}</p>
                    <p class="text-slate-600 text-sm italic mt-3 flex items-center gap-2">
                      <span class="text-purple-600">→</span>
                      {{ row.example }}
                    </p>
                  </div>
                </div>
              </div>
            </section>

            <!-- Section: Voter Verification -->
            <section id="verification" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-2">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-red-400 to-pink-600 text-white font-bold text-lg flex items-center justify-center">5</div>
                <div class="flex-1">
                  <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_verification.heading }}</h2>
                  <span class="inline-block mt-2 px-3 py-1 bg-red-100 text-red-800 font-bold text-xs rounded-full">{{ t.section_verification.badge }}</span>
                </div>
              </div>
              <p class="text-slate-700 mb-8 text-lg mt-6">{{ t.section_verification.intro }}</p>

              <!-- Verification Modes -->
              <div class="mb-10">
                <p class="font-bold text-slate-900 text-lg mb-5">{{ t.section_verification.modes_heading }}</p>
                <div class="space-y-3">
                  <div v-for="(mode, idx) in t.section_verification.modes" :key="idx" class="p-5 bg-slate-50 rounded-lg border-2 border-slate-200 hover:border-red-400 transition-colors">
                    <p class="font-bold text-slate-900 text-base">{{ mode.label }}</p>
                    <p class="text-slate-700 text-sm mt-2">{{ mode.description }}</p>
                  </div>
                </div>
              </div>

              <!-- Verification Workflow -->
              <div class="mb-10 pb-10 border-b-2 border-slate-200">
                <p class="font-bold text-slate-900 text-lg mb-5">{{ t.section_verification.workflow_heading }}</p>
                <ol class="space-y-3">
                  <li v-for="(step, idx) in t.section_verification.workflow_steps" :key="idx" class="flex gap-4 p-4 bg-gradient-to-r from-slate-50 to-white rounded-lg border border-slate-200">
                    <span class="flex-shrink-0 font-bold text-white bg-red-600 w-7 h-7 rounded-full flex items-center justify-center text-sm">{{ idx + 1 }}</span>
                    <span class="text-slate-700 text-base">{{ step }}</span>
                  </li>
                </ol>
              </div>

              <!-- Bypass Note -->
              <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 rounded-lg p-6 flex gap-4">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <div>
                  <p class="font-bold text-yellow-900 text-lg">⚡ {{ t.section_verification.bypass_note_label }}</p>
                  <p class="text-yellow-800 text-base mt-2">{{ t.section_verification.bypass_note }}</p>
                </div>
              </div>
            </section>

            <!-- Section: Common Scenarios -->
            <section id="scenarios" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 text-white font-bold text-lg flex items-center justify-center">6</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_scenarios.heading }}</h2>
              </div>
              <p class="text-slate-700 mb-8 text-lg">{{ t.section_scenarios.intro }}</p>
              <div class="space-y-6">
                <div v-for="(scenario, idx) in t.section_scenarios.scenarios" :key="idx" class="p-6 rounded-xl border-3 transition-all hover:shadow-lg" :class="scenario.tag_color === 'green' ? 'border-green-400 bg-green-50' : scenario.tag_color === 'red' ? 'border-red-400 bg-red-50' : 'border-blue-400 bg-blue-50'">
                  <div class="flex items-start justify-between mb-4 flex-wrap gap-2">
                    <h3 class="text-2xl font-bold text-slate-900">{{ scenario.title }}</h3>
                    <span class="inline-block px-3 py-1 font-bold text-white text-xs rounded-full" :class="scenario.tag_color === 'green' ? 'bg-green-600' : scenario.tag_color === 'red' ? 'bg-red-600' : 'bg-blue-600'">
                      {{ scenario.tag }}
                    </span>
                  </div>
                  <p class="text-slate-700 text-base mb-5">{{ scenario.description }}</p>
                  <div class="bg-white rounded-lg p-4 border-2" :class="scenario.tag_color === 'green' ? 'border-green-200' : scenario.tag_color === 'red' ? 'border-red-200' : 'border-blue-200'">
                    <table class="w-full text-sm">
                      <thead>
                        <tr class="border-b-2 border-slate-300">
                          <th class="text-left px-3 py-2 font-bold text-slate-900">Setting</th>
                          <th class="text-left px-3 py-2 font-bold text-slate-900">Configuration</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-200">
                        <tr v-for="(setting, sidx) in scenario.settings" :key="sidx" class="hover:bg-slate-50">
                          <td class="px-3 py-2 text-slate-700 font-semibold">{{ setting.setting }}</td>
                          <td class="px-3 py-2 text-slate-700 font-mono text-xs bg-slate-100 rounded">{{ setting.value }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <p class="text-slate-700 text-sm italic mt-4 border-t-2 pt-4" :class="scenario.tag_color === 'green' ? 'border-green-200' : scenario.tag_color === 'red' ? 'border-red-200' : 'border-blue-200'">
                    💡 <strong>Why:</strong> {{ scenario.rationale }}
                  </p>
                </div>
              </div>
            </section>

            <!-- Section: FAQ -->
            <section id="faq" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 text-white font-bold text-lg flex items-center justify-center">7</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_faq.heading }}</h2>
              </div>
              <div class="space-y-4">
                <details v-for="(item, idx) in t.section_faq.items" :key="idx" class="group bg-gradient-to-r from-slate-50 to-white rounded-lg border-2 border-slate-200 hover:border-indigo-400 transition-colors">
                  <summary class="cursor-pointer select-none px-6 py-4 font-bold text-slate-900 text-lg flex items-center justify-between hover:bg-indigo-50 rounded-t-lg transition-colors">
                    <span>{{ item.question }}</span>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </summary>
                  <div class="px-6 pb-4 text-slate-700 text-base leading-relaxed border-t border-slate-200 bg-indigo-50">
                    {{ item.answer }}
                  </div>
                </details>
              </div>
            </section>

            <!-- Back Link -->
            <div class="mt-12 pt-8 border-t-2 border-slate-200 flex items-center justify-center gap-4">
              <button
                @click="window.history.back()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-900 font-bold rounded-lg hover:bg-slate-200 focus:outline-none focus:ring-4 focus:ring-slate-300 transition-colors"
                aria-label="Go back to previous page"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
              </button>
              <a
                href="/"
                class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 text-white font-bold rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-300 transition-colors"
                aria-label="Go to home page"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V5" />
                </svg>
                Home
              </a>
            </div>
          </div>
        </div>
      </div>
    </main>
    </div>
  </div>
</template>

<style scoped>
/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Animation for sections */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

section {
  animation: slideIn 0.6s ease-out;
}

/* Focus styles for accessibility */
:focus-visible {
  outline: 3px solid #0369a1;
  outline-offset: 2px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Code block styling */
code {
  font-family: 'Courier New', Courier, monospace;
}

/* Smooth hover effects */
a {
  transition: all 0.2s ease;
}

/* Table row hover states */
table tbody tr {
  transition: background-color 0.2s ease;
}
</style>
