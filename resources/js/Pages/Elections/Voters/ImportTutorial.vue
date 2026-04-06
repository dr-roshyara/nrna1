<template>
  <ElectionLayout>
    <main role="main" class="py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
          <Link
            :href="route('elections.voters.import.create', { organisation: organisation.slug, election: election.slug })"
            class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ t.back }}
          </Link>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ t.title }}</h1>
          <p class="text-gray-600">{{ t.subtitle }}</p>
        </div>

        <div class="space-y-8">

          <!-- Overview -->
          <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-3 flex items-center gap-2">
              <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-sm font-bold">i</span>
              {{ t.section_overview.heading }}
            </h2>
            <p class="text-gray-700 leading-relaxed">{{ t.section_overview.body }}</p>
          </section>

          <!-- Requirements -->
          <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-3 flex items-center gap-2">
              <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-100 text-green-700 text-sm">✓</span>
              {{ t.section_requirements.heading }}
            </h2>
            <p class="text-gray-700 mb-4">{{ t.section_requirements.intro }}</p>
            <ul class="space-y-2 mb-4">
              <li
                v-for="(condition, idx) in t.section_requirements.conditions"
                :key="idx"
                class="flex items-start gap-3"
              >
                <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-700 text-xs font-bold flex items-center justify-center">
                  {{ idx + 1 }}
                </span>
                <span class="text-gray-700">{{ condition }}</span>
              </li>
            </ul>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3">
              <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
              </svg>
              <p class="text-amber-800 text-sm">{{ t.section_requirements.warning }}</p>
            </div>
          </section>

          <!-- File Format -->
          <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-3 flex items-center gap-2">
              <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-purple-100 text-purple-700 text-sm">📄</span>
              {{ t.section_file_format.heading }}
            </h2>
            <p class="text-gray-700 mb-4">{{ t.section_file_format.intro }}</p>

            <p class="font-medium text-gray-800 mb-2">{{ t.section_file_format.rules_heading }}</p>
            <ul class="space-y-2 mb-5">
              <li
                v-for="(rule, idx) in t.section_file_format.rules"
                :key="idx"
                class="flex items-start gap-2 text-gray-700 text-sm"
              >
                <span class="text-blue-500 mt-0.5">•</span>
                {{ rule }}
              </li>
            </ul>

            <p class="font-medium text-gray-800 mb-2">{{ t.section_file_format.example_heading }}</p>
            <pre class="bg-gray-900 text-green-400 rounded-lg p-4 text-sm font-mono overflow-x-auto mb-4">{{ t.section_file_format.example }}</pre>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-blue-800 text-sm">{{ t.section_file_format.download_hint }}</p>
            </div>
          </section>

          <!-- Steps -->
          <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">{{ t.section_steps.heading }}</h2>
            <div class="space-y-6">
              <div
                v-for="(step, idx) in t.section_steps.steps"
                :key="idx"
                class="flex gap-4"
              >
                <div class="flex-shrink-0 flex flex-col items-center">
                  <div class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold text-sm flex items-center justify-center">
                    {{ idx + 1 }}
                  </div>
                  <div v-if="idx < t.section_steps.steps.length - 1" class="w-0.5 h-full bg-blue-100 mt-2" />
                </div>
                <div class="pb-6">
                  <p class="font-semibold text-gray-900 mb-1">{{ step.title }}</p>
                  <p class="text-gray-600 text-sm leading-relaxed">{{ step.body }}</p>
                </div>
              </div>
            </div>
          </section>

          <!-- FAQ -->
          <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-5">{{ t.section_faq.heading }}</h2>
            <div class="space-y-4">
              <div
                v-for="(item, idx) in t.section_faq.items"
                :key="idx"
                class="border border-gray-100 rounded-lg p-4 bg-gray-50"
              >
                <p class="font-medium text-gray-900 mb-1 flex items-start gap-2">
                  <span class="text-blue-500 font-bold flex-shrink-0">Q.</span>
                  {{ item.question }}
                </p>
                <p class="text-gray-600 text-sm pl-5">{{ item.answer }}</p>
              </div>
            </div>
          </section>

          <!-- Back button -->
          <div class="flex justify-start pb-4">
            <Link
              :href="route('elections.voters.import.create', { organisation: organisation.slug, election: election.slug })"
              class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
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
  </ElectionLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Link } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

import pageEn from '@/locales/pages/Elections/Voters/ImportTutorial/en.json'
import pageDe from '@/locales/pages/Elections/Voters/ImportTutorial/de.json'
import pageNp from '@/locales/pages/Elections/Voters/ImportTutorial/np.json'

const { locale } = useI18n()
const pageData = { en: pageEn, de: pageDe, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.en)

defineProps({
  organisation: { type: Object, required: true },
  election:     { type: Object, required: true },
})
</script>
