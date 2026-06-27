<template>
  <component :is="isPublic ? PublicDigitLayout : ElectionLayout">
    <main role="main" class="py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header with Mode Badge -->
        <div class="mb-8">
          <Link
            v-if="!isPublic && organisation && election"
            :href="route('elections.voters.import.create', { organisation: organisation.slug, election: election.slug })"
            class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-4"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ t.back }}
          </Link>
          <div class="flex items-center justify-between mb-4">
            <div>
              <h1 class="text-4xl font-black text-neutral-900 mb-1 tracking-tight">{{ t.title }}</h1>
              <p class="text-lg text-neutral-600">{{ t.subtitle }}</p>
            </div>
            <div
              :class="[
                'px-5 py-3 rounded-xl font-bold text-sm uppercase tracking-wider flex items-center gap-2',
                uses_full_membership
                  ? 'bg-amber-50 border-2 border-amber-300 text-amber-900'
                  : 'bg-emerald-50 border-2 border-emerald-300 text-emerald-900'
              ]"
            >
              <svg class="w-5 h-5" :class="uses_full_membership ? 'text-amber-600' : 'text-emerald-600'" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path v-if="uses_full_membership" d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                <path v-else d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a6 6 0 00-9-5.618A6 6 0 004 18v1h12z" />
              </svg>
              {{ uses_full_membership ? 'Full Membership' : 'Election-Only' }}
            </div>
          </div>
        </div>

        <div class="space-y-8">

          <!-- MODE COMPARISON: Visual Side-by-Side -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Full Membership Mode Card -->
            <div
              class="relative overflow-hidden rounded-2xl border-2 border-amber-200 bg-gradient-to-br from-amber-50 via-white to-amber-50 shadow-lg hover:shadow-xl transition-shadow duration-300"
              :class="{ 'ring-4 ring-amber-300 ring-offset-2': uses_full_membership }"
            >
              <div class="absolute top-0 right-0 w-40 h-40 bg-amber-200 opacity-10 rounded-full -mr-20 -mt-20" />
              <div class="relative p-8 z-10">
                <!-- Mode Label -->
                <div class="mb-6">
                  <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-bold uppercase tracking-wider mb-3">
                    {{ uses_full_membership ? '✓ Current Mode' : 'Alternative' }}
                  </span>
                  <h3 class="text-2xl font-black text-amber-900 tracking-tight">Full Membership</h3>
                  <p class="text-amber-700 text-sm mt-1 font-medium">Pre-registered users only</p>
                </div>

                <!-- CSV Format -->
                <div class="mb-6 bg-white bg-opacity-70 rounded-lg p-4 border border-amber-100">
                  <p class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">CSV Format</p>
                  <code class="text-sm font-mono text-amber-900 leading-relaxed">
                    <div>email</div>
                    <div>john@example.com</div>
                    <div>jane@example.com</div>
                  </code>
                </div>

                <!-- Process Flow -->
                <div class="mb-6">
                  <p class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-3">Process</p>
                  <div class="space-y-2 text-sm">
                    <div class="flex gap-3 items-start">
                      <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-900 text-white text-xs font-bold flex-shrink-0">1</span>
                      <span class="text-neutral-700">User <strong>must exist</strong> in platform</span>
                    </div>
                    <div class="flex gap-3 items-start">
                      <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-900 text-white text-xs font-bold flex-shrink-0">2</span>
                      <span class="text-neutral-700">User <strong>must be member</strong> of organisation</span>
                    </div>
                    <div class="flex gap-3 items-start">
                      <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-900 text-white text-xs font-bold flex-shrink-0">3</span>
                      <span class="text-neutral-700">Status <strong>must be active</strong></span>
                    </div>
                  </div>
                </div>

                <!-- Use Cases -->
                <div class="bg-amber-900 bg-opacity-5 rounded-lg p-4 border border-amber-200">
                  <p class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">Best For</p>
                  <ul class="text-sm text-neutral-700 space-y-1">
                    <li>✓ Internal organisation votes</li>
                    <li>✓ Existing member participation</li>
                    <li>✓ Controlled, pre-vetted voters</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Election-Only Mode Card -->
            <div
              class="relative overflow-hidden rounded-2xl border-2 border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-50 shadow-lg hover:shadow-xl transition-shadow duration-300"
              :class="{ 'ring-4 ring-emerald-300 ring-offset-2': !uses_full_membership }"
            >
              <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-200 opacity-10 rounded-full -mr-20 -mt-20" />
              <div class="relative p-8 z-10">
                <!-- Mode Label -->
                <div class="mb-6">
                  <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-bold uppercase tracking-wider mb-3">
                    {{ !uses_full_membership ? '✓ Current Mode' : 'Alternative' }}
                  </span>
                  <h3 class="text-2xl font-black text-emerald-900 tracking-tight">Election-Only</h3>
                  <p class="text-emerald-700 text-sm mt-1 font-medium">Auto-create new users</p>
                </div>

                <!-- CSV Format -->
                <div class="mb-6 bg-white bg-opacity-70 rounded-lg p-4 border border-emerald-100">
                  <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-2">CSV Format</p>
                  <code class="text-sm font-mono text-emerald-900 leading-relaxed">
                    <div>firstname;lastname;email</div>
                    <div>John;Doe;john@example.com</div>
                    <div>Jane;Smith;jane@example.com</div>
                  </code>
                </div>

                <!-- Process Flow -->
                <div class="mb-6">
                  <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-3">Process</p>
                  <div class="space-y-2 text-sm">
                    <div class="flex gap-3 items-start">
                      <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-900 text-white text-xs font-bold flex-shrink-0">1</span>
                      <span class="text-neutral-700">Check if email exists</span>
                    </div>
                    <div class="flex gap-3 items-start">
                      <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-900 text-white text-xs font-bold flex-shrink-0">2</span>
                      <span class="text-neutral-700"><strong>Auto-create</strong> if new</span>
                    </div>
                    <div class="flex gap-3 items-start">
                      <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-900 text-white text-xs font-bold flex-shrink-0">3</span>
                      <span class="text-neutral-700"><strong>Send invitation</strong> email</span>
                    </div>
                  </div>
                </div>

                <!-- Use Cases -->
                <div class="bg-emerald-900 bg-opacity-5 rounded-lg p-4 border border-emerald-200">
                  <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-2">Best For</p>
                  <ul class="text-sm text-neutral-700 space-y-1">
                    <li>✓ Open elections (public participation)</li>
                    <li>✓ Rapid onboarding of new voters</li>
                    <li>✓ Flexible, inclusive voting</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Decision Guide -->
          <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-primary-200 p-6 shadow-sm">
            <div class="flex gap-4">
              <div class="flex-shrink-0 pt-1">
                <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 7a1 1 0 100-2 1 1 0 000 2zm5-1a1 1 0 11-2 0 1 1 0 012 0zM14 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-bold text-primary-900 mb-2">Which Mode Should You Use?</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                  <div>
                    <p class="font-semibold text-primary-900 mb-1">📋 Choose <strong>Full Membership</strong> if:</p>
                    <ul class="text-primary-800 space-y-1 ml-4">
                      <li>• All voters are existing platform users</li>
                      <li>• You want to restrict to formal members only</li>
                      <li>• Pre-registration ensures quality control</li>
                    </ul>
                  </div>
                  <div>
                    <p class="font-semibold text-indigo-900 mb-1">✨ Choose <strong>Election-Only</strong> if:</p>
                    <ul class="text-indigo-800 space-y-1 ml-4">
                      <li>• You need to invite new voters</li>
                      <li>• You want one-step onboarding</li>
                      <li>• Flexibility matters more than pre-vetting</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Overview -->
          <section class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-neutral-900 mb-3 flex items-center gap-2">
              <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-primary-100 text-primary-700 text-sm font-bold">i</span>
              {{ t.section_overview.heading }}
            </h2>
            <p class="text-neutral-700 leading-relaxed">{{ t.section_overview.body }}</p>
          </section>

          <!-- Requirements -->
          <section class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-neutral-900 mb-3 flex items-center gap-2">
              <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-100 text-green-700 text-sm">✓</span>
              {{ t.section_requirements.heading }}
            </h2>
            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-6 flex gap-3">
              <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-primary-800 text-sm">
                <strong>Your mode:</strong> {{ uses_full_membership ? 'Full Membership Mode' : 'Election-Only Mode' }}
              </p>
            </div>
            <p class="text-neutral-700 mb-4">
              {{ uses_full_membership ? t.section_requirements.intro_full : t.section_requirements.intro_election }}
            </p>
            <ul class="space-y-2 mb-4">
              <li
                v-for="(condition, idx) in (uses_full_membership ? t.section_requirements.conditions_full : t.section_requirements.conditions_election)"
                :key="idx"
                class="flex items-start gap-3"
              >
                <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-700 text-xs font-bold flex items-center justify-center">
                  {{ idx + 1 }}
                </span>
                <span class="text-neutral-700">{{ condition }}</span>
              </li>
            </ul>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3">
              <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
              </svg>
              <p class="text-amber-800 text-sm">
                {{ uses_full_membership ? t.section_requirements.warning_full : t.section_requirements.warning_election }}
              </p>
            </div>
          </section>

          <!-- File Format -->
          <section class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-neutral-900 mb-3 flex items-center gap-2">
              <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-purple-100 text-purple-700 text-sm">📄</span>
              {{ t.section_file_format.heading }}
            </h2>
            <p class="text-neutral-700 mb-4">{{ t.section_file_format.intro }}</p>

            <!-- Format Section based on Mode -->
            <div :class="uses_full_membership ? '' : ''">
              <p class="font-bold text-neutral-900 mb-2 text-lg">
                {{ uses_full_membership ? t.section_file_format.format_full.title : t.section_file_format.format_election.title }}
              </p>
              <p class="text-neutral-600 mb-4">
                {{ uses_full_membership ? t.section_file_format.format_full.description : t.section_file_format.format_election.description }}
              </p>

              <p class="font-medium text-neutral-800 mb-2">{{ uses_full_membership ? t.section_file_format.format_full.rules_heading : t.section_file_format.format_election.rules_heading }}</p>
              <ul class="space-y-2 mb-5">
                <li
                  v-for="(rule, idx) in (uses_full_membership ? t.section_file_format.format_full.rules : t.section_file_format.format_election.rules)"
                  :key="idx"
                  class="flex items-start gap-2 text-neutral-700 text-sm"
                >
                  <span class="text-primary-500 mt-0.5">•</span>
                  {{ rule }}
                </li>
              </ul>

              <p class="font-medium text-neutral-800 mb-2">{{ uses_full_membership ? t.section_file_format.format_full.example_heading : t.section_file_format.format_election.example_heading }}</p>
              <pre class="bg-neutral-900 text-green-400 rounded-lg p-4 text-sm font-mono overflow-x-auto mb-4">{{ uses_full_membership ? t.section_file_format.format_full.example : t.section_file_format.format_election.example }}</pre>
            </div>

            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 flex gap-3">
              <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-primary-800 text-sm">{{ t.section_file_format.download_hint }}</p>
            </div>
          </section>

          <!-- Steps -->
          <section class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-neutral-900 mb-6">{{ t.section_steps.heading }}</h2>
            <div class="space-y-6">
              <div
                v-for="(step, idx) in (uses_full_membership ? t.section_steps.steps_full : t.section_steps.steps_election)"
                :key="idx"
                class="flex gap-4"
              >
                <div class="flex-shrink-0 flex flex-col items-center">
                  <div class="w-9 h-9 rounded-full bg-primary-600 text-white font-bold text-sm flex items-center justify-center">
                    {{ idx + 1 }}
                  </div>
                  <div v-if="idx < (uses_full_membership ? t.section_steps.steps_full : t.section_steps.steps_election).length - 1" class="w-0.5 h-full bg-primary-100 mt-2" />
                </div>
                <div class="pb-6">
                  <p class="font-semibold text-neutral-900 mb-1">{{ step.title }}</p>
                  <p class="text-neutral-600 text-sm leading-relaxed">{{ step.body }}</p>
                </div>
              </div>
            </div>
          </section>

          <!-- FAQ -->
          <section class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-neutral-900 mb-5">{{ t.section_faq.heading }}</h2>
            <div class="space-y-4">
              <div
                v-for="(item, idx) in t.section_faq.items"
                :key="idx"
                class="border border-neutral-100 rounded-lg p-4 bg-neutral-50"
              >
                <p class="font-medium text-neutral-900 mb-1 flex items-start gap-2">
                  <span class="text-primary-500 font-bold flex-shrink-0">Q.</span>
                  {{ item.question }}
                </p>
                <p class="text-neutral-600 text-sm pl-5">{{ item.answer }}</p>
              </div>
            </div>
          </section>

          <!-- Back button (only show when not public) -->
          <div v-if="!isPublic && organisation && election" class="flex justify-start pb-4">
            <Link
              :href="route('elections.voters.import.create', { organisation: organisation.slug, election: election.slug })"
              class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg>
              {{ t.back_btn }}
            </Link>
          </div>

        </div>
      </div>
    </main>
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useMeta } from '@/composables/useMeta'
import { Link } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

import pageEn from '@/locales/pages/Elections/Voters/ImportTutorial/en.json'
import pageDe from '@/locales/pages/Elections/Voters/ImportTutorial/de.json'
import pageNp from '@/locales/pages/Elections/Voters/ImportTutorial/np.json'

const { locale } = useI18n()
const pageData = { en: pageEn, de: pageDe, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.en)

const props = defineProps({
  organisation:        { type: Object,  required: false, default: null },
  election:            { type: Object,  required: false, default: null },
  uses_full_membership: { type: Boolean, default: true },
  isPublic:            { type: Boolean, default: false },
})

// Set SEO meta tags
useMeta({
  pageKey: 'elections.voters.import.tutorial',
  noindex: false,
  nofollow: false,
})
</script>

