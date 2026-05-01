<script setup>
import { useMeta } from '@/composables/useMeta'
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

useMeta({
  pageKey: 'tutorials.election-settings',
  url: '/help/election-setup',
  type: 'article',
})

// Detect locale from cookie, fallback to 'de' (server default)
const getCookie = (name) => {
  const nameEQ = name + '='
  const cookies = document.cookie.split(';')
  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].trim()
    if (cookie.indexOf(nameEQ) === 0) {
      return cookie.substring(nameEQ.length)
    }
  }
  return null
}

const locale = ref(getCookie('locale') || 'de')

// Watch for cookie changes from PublicDigitHeader
let localeCheckInterval = null
onMounted(() => {
  const checkLocaleChange = () => {
    const newLocale = getCookie('locale') || 'de'
    if (newLocale !== locale.value) {
      locale.value = newLocale
    }
  }

  // Check for locale changes every 100ms
  localeCheckInterval = setInterval(checkLocaleChange, 100)
})

// Cleanup interval on component unmount
onBeforeUnmount(() => {
  if (localeCheckInterval) {
    clearInterval(localeCheckInterval)
  }
})

// Import locale JSON based on current language
const localeModule = import.meta.glob('../../locales/pages/Tutorials/ElectionSettings/*.json', { eager: true })
const t = computed(() => {
  const lang = locale.value
  const key = `../../locales/pages/Tutorials/ElectionSettings/${lang}.json`
  return localeModule[key]?.default || localeModule['../../locales/pages/Tutorials/ElectionSettings/de.json'].default
})

// Track active section for scroll-spy
const activeSection = computed(() => 'overview')
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <!-- PublicDigit Header without breadcrumbs -->
    <PublicDigitHeader
      :breadcrumbs="[]"
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
              <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-primary-500 rounded-lg p-6 flex gap-4">
                <svg class="w-6 h-6 text-primary-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                  <p class="font-bold text-primary-900 text-lg">💡 {{ t.section_overview.tip_label }}</p>
                  <p class="text-primary-800 text-base mt-2">{{ t.section_overview.tip }}</p>
                </div>
              </div>
            </section>

            <!-- Section: Election Lifecycle / State Machine -->
            <section id="statemachine" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-violet-600 text-white font-bold text-lg flex items-center justify-center">2</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_statemachine.heading }}</h2>
              </div>
              <p class="text-lg text-slate-700 leading-relaxed mb-6">{{ t.section_statemachine.intro }}</p>

              <!-- Key Concept Box -->
              <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-violet-50 border-l-4 border-indigo-500 rounded-lg p-6 mb-10 flex gap-4">
                <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                </svg>
                <div>
                  <p class="font-bold text-indigo-900 text-lg">💡 {{ t.section_statemachine.key_concept }}</p>
                </div>
              </div>

              <!-- Timeline Description -->
              <p class="text-slate-700 mb-8 text-lg font-medium">{{ t.section_statemachine.timeline.description }}</p>

              <!-- Interactive Timeline - Horizontal Layout -->
              <div class="mb-12 overflow-x-auto pb-4">
                <div class="flex gap-4 min-w-max md:min-w-full md:flex-wrap md:gap-4 px-4 md:px-0">
                  <div
                    v-for="(phase, idx) in t.section_statemachine.timeline.phases"
                    :key="idx"
                    class="flex-1 min-w-[280px] bg-gradient-to-br rounded-xl border-2 p-6 cursor-pointer transform transition-all hover:scale-105 hover:shadow-lg"
                    :class="{
                      'from-slate-100 to-slate-50 border-slate-300': phase.color === 'slate',
                      'from-amber-100 to-amber-50 border-amber-300': phase.color === 'amber',
                      'from-purple-100 to-purple-50 border-purple-300': phase.color === 'purple',
                      'from-orange-100 to-orange-50 border-orange-300': phase.color === 'orange',
                      'from-emerald-100 to-emerald-50 border-emerald-300': phase.color === 'emerald'
                    }"
                  >
                    <!-- Phase Number and Icon -->
                    <div class="flex items-center gap-3 mb-4">
                      <span class="text-3xl">{{ phase.icon }}</span>
                      <div>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-600">Phase {{ phase.number }}</p>
                        <h3 class="text-xl font-black text-slate-900">{{ phase.name }}</h3>
                      </div>
                    </div>

                    <!-- Phase Description -->
                    <p class="text-sm font-semibold text-slate-700 mb-3">{{ phase.description }}</p>

                    <!-- Duration/Trigger -->
                    <div class="text-xs mb-4">
                      <p v-if="phase.duration" class="text-slate-600"><strong>Duration:</strong> {{ phase.duration }}</p>
                      <p v-if="phase.triggers" class="text-slate-600"><strong>Triggered:</strong> {{ phase.triggers }}</p>
                    </div>

                    <!-- What Happens -->
                    <div class="bg-white bg-opacity-60 rounded p-3 mb-4 border border-slate-200">
                      <p class="text-sm text-slate-700">{{ phase.what_happens }}</p>
                    </div>

                    <!-- Note if exists -->
                    <div v-if="phase.note" class="bg-amber-100 border-l-2 border-amber-500 text-amber-800 text-xs p-2 rounded mb-4">
                      <strong>⚠️ Note:</strong> {{ phase.note }}
                    </div>

                    <!-- Available Actions -->
                    <div>
                      <p class="text-xs font-bold text-slate-700 uppercase mb-2">Available actions:</p>
                      <ul class="space-y-1">
                        <li v-for="(action, aidx) in phase.actions" :key="aidx" class="flex items-start gap-2 text-xs text-slate-600">
                          <span class="text-emerald-600 font-bold mt-0.5">✓</span>
                          <span>{{ action }}</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Dates Section -->
              <div class="mb-12 pb-10 border-b-2 border-slate-200">
                <h3 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                  {{ t.section_statemachine.dates_section.heading }}
                </h3>
                <p class="text-slate-700 mb-6 text-base">{{ t.section_statemachine.dates_section.description }}</p>

                <div class="space-y-4">
                  <div v-for="(date, didx) in t.section_statemachine.dates_section.dates" :key="didx" class="p-5 bg-gradient-to-r from-slate-50 to-white rounded-lg border-2 border-slate-200 hover:border-indigo-300 transition-colors">
                    <div class="flex items-start gap-4">
                      <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-sm">{{ didx + 1 }}</div>
                      <div class="flex-1">
                        <p class="font-bold text-slate-900 text-lg">{{ date.field }}</p>
                        <p class="text-slate-700 text-base mt-2">
                          <strong>Variable:</strong> <code class="bg-slate-100 px-2 py-1 rounded font-mono text-sm">{{ date.variable }}</code>
                        </p>
                        <p class="text-slate-700 text-base mt-2"><strong>Triggers:</strong> {{ date.when }}</p>
                        <p class="text-slate-700 text-base mt-2"><strong>Sets:</strong> {{ date.sets }}</p>
                        <div class="mt-3 p-3 bg-amber-50 border-l-2 border-amber-500 rounded">
                          <p class="text-sm text-amber-900"><strong>⚡ Important:</strong> {{ date.important }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Timeline Example -->
                <div class="mt-8 p-6 bg-primary-50 border-2 border-primary-200 rounded-lg">
                  <p class="font-bold text-primary-900 text-base mb-3">📌 Example Timeline</p>
                  <p class="text-primary-800 text-sm">{{ t.section_statemachine.dates_section.example }}</p>
                </div>
              </div>

              <!-- Permissions Matrix -->
              <div class="mb-12">
                <h3 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                  {{ t.section_statemachine.permissions_section.heading }}
                </h3>
                <p class="text-slate-700 mb-6 text-base">{{ t.section_statemachine.permissions_section.description }}</p>

                <div class="overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-slate-100 to-slate-50 border-b-2 border-slate-300">
                      <tr>
                        <th class="text-left px-4 py-3 font-bold text-slate-900">Action</th>
                        <th class="text-center px-3 py-3 font-bold text-slate-700 text-xs bg-slate-50">Admin</th>
                        <th class="text-center px-3 py-3 font-bold text-slate-700 text-xs bg-amber-50">Nomination</th>
                        <th class="text-center px-3 py-3 font-bold text-slate-700 text-xs bg-purple-50">Voting</th>
                        <th class="text-center px-3 py-3 font-bold text-slate-700 text-xs bg-orange-50">Pending</th>
                        <th class="text-center px-3 py-3 font-bold text-slate-700 text-xs bg-emerald-50">Results</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                      <tr v-for="(row, ridx) in t.section_statemachine.permissions_section.matrix" :key="ridx" class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-4 text-slate-700 font-semibold">{{ row.action }}</td>
                        <td class="text-center px-3 py-4">
                          <span v-if="row.administration" class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">✓</span>
                          <span v-else class="text-slate-400">—</span>
                        </td>
                        <td class="text-center px-3 py-4">
                          <span v-if="row.nomination" class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">✓</span>
                          <span v-else class="text-slate-400">—</span>
                        </td>
                        <td class="text-center px-3 py-4">
                          <span v-if="row.voting" class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">✓</span>
                          <span v-else class="text-slate-400">—</span>
                        </td>
                        <td class="text-center px-3 py-4">
                          <span v-if="row.results_pending" class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">✓</span>
                          <span v-else class="text-slate-400">—</span>
                        </td>
                        <td class="text-center px-3 py-4">
                          <span v-if="row.results" class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">✓</span>
                          <span v-else class="text-slate-400">—</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Tips -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div v-for="(tip, tidx) in t.section_statemachine.tips" :key="tidx" class="p-5 bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg border-2 border-primary-200 hover:shadow-md transition-shadow">
                  <p class="text-3xl mb-2">{{ tip.icon }}</p>
                  <p class="font-bold text-slate-900 text-base mb-2">{{ tip.label }}</p>
                  <p class="text-slate-700 text-sm">{{ tip.text }}</p>
                </div>
              </div>
            </section>

            <!-- Section: Before You Begin -->
            <section id="prerequisites" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-amber-400 to-orange-600 text-white font-bold text-lg flex items-center justify-center">3</div>
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
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-cyan-400 to-teal-600 text-white font-bold text-lg flex items-center justify-center">4</div>
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
                <div class="p-5 bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg border-2 border-primary-200">
                  <p class="font-bold text-slate-900 text-lg mb-3">{{ t.section_access.ip_restriction.whitelist.heading }}</p>
                  <p class="text-slate-700 mb-5 text-base">{{ t.section_access.ip_restriction.whitelist.body }}</p>
                  <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-3">{{ t.section_access.ip_restriction.whitelist.format_label }}</p>
                  <ul class="space-y-2 mb-5">
                    <li v-for="(fmt, idx) in t.section_access.ip_restriction.whitelist.formats" :key="idx" class="flex items-start gap-3">
                      <span class="text-teal-600 font-bold mt-1">✓</span>
                      <code class="bg-slate-700 text-yellow-300 px-2 py-1 rounded font-mono text-xs">{{ fmt }}</code>
                    </li>
                  </ul>
                  <div class="bg-white border border-primary-200 rounded p-4 mt-4">
                    <p class="font-bold text-primary-900 text-sm mb-2">❓ {{ t.section_access.ip_restriction.whitelist.cidr_tip_label }}</p>
                    <p class="text-primary-800 text-sm">{{ t.section_access.ip_restriction.whitelist.cidr_tip }}</p>
                  </div>
                </div>
              </div>
            </section>

            <!-- Section: Ballot Options -->
            <section id="ballot" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 text-white font-bold text-lg flex items-center justify-center">5</div>
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
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-red-400 to-pink-600 text-white font-bold text-lg flex items-center justify-center">6</div>
                <div class="flex-1">
                  <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_verification.heading }}</h2>
                  <span class="inline-block mt-2 px-3 py-1 bg-danger-100 text-danger-800 font-bold text-xs rounded-full">{{ t.section_verification.badge }}</span>
                </div>
              </div>
              <p class="text-slate-700 mb-8 text-lg mt-6">{{ t.section_verification.intro }}</p>

              <!-- Verification Modes -->
              <div class="mb-10">
                <p class="font-bold text-slate-900 text-lg mb-5">{{ t.section_verification.modes_heading }}</p>
                <div class="space-y-3">
                  <div v-for="(mode, idx) in t.section_verification.modes" :key="idx" class="p-5 bg-slate-50 rounded-lg border-2 border-slate-200 hover:border-danger-400 transition-colors">
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
                    <span class="flex-shrink-0 font-bold text-white bg-danger-600 w-7 h-7 rounded-full flex items-center justify-center text-sm">{{ idx + 1 }}</span>
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
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 text-white font-bold text-lg flex items-center justify-center">7</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">{{ t.section_scenarios.heading }}</h2>
              </div>
              <p class="text-slate-700 mb-8 text-lg">{{ t.section_scenarios.intro }}</p>
              <div class="space-y-6">
                <div v-for="(scenario, idx) in t.section_scenarios.scenarios" :key="idx" class="p-6 rounded-xl border-3 transition-all hover:shadow-lg" :class="scenario.tag_color === 'green' ? 'border-green-400 bg-green-50' : scenario.tag_color === 'red' ? 'border-danger-400 bg-danger-50' : 'border-primary-400 bg-primary-50'">
                  <div class="flex items-start justify-between mb-4 flex-wrap gap-2">
                    <h3 class="text-2xl font-bold text-slate-900">{{ scenario.title }}</h3>
                    <span class="inline-block px-3 py-1 font-bold text-white text-xs rounded-full" :class="scenario.tag_color === 'green' ? 'bg-green-600' : scenario.tag_color === 'red' ? 'bg-danger-600' : 'bg-primary-600'">
                      {{ scenario.tag }}
                    </span>
                  </div>
                  <p class="text-slate-700 text-base mb-5">{{ scenario.description }}</p>
                  <div class="bg-white rounded-lg p-4 border-2" :class="scenario.tag_color === 'green' ? 'border-green-200' : scenario.tag_color === 'red' ? 'border-danger-200' : 'border-primary-200'">
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
                  <p class="text-slate-700 text-sm italic mt-4 border-t-2 pt-4" :class="scenario.tag_color === 'green' ? 'border-green-200' : scenario.tag_color === 'red' ? 'border-danger-200' : 'border-primary-200'">
                    💡 <strong>Why:</strong> {{ scenario.rationale }}
                  </p>
                </div>
              </div>
            </section>

            <!-- Section: FAQ -->
            <section id="faq" class="bg-white rounded-2xl border-2 border-slate-200 shadow-lg p-8 md:p-10 hover:shadow-xl transition-shadow scroll-mt-8">
              <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 text-white font-bold text-lg flex items-center justify-center">8</div>
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

    <!-- PublicDigit Footer -->
    <PublicDigitFooter />
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

