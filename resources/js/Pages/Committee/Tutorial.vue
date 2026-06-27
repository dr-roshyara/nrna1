<template>
  <PublicDigitLayout>
    <!-- Content -->
    <div class="flex-1 max-w-6xl w-full mx-auto px-6 py-12">
      <!-- Tab Navigation -->
      <div class="flex gap-3 mb-8 border-b border-slate-200">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'px-6 py-3 font-semibold transition-all duration-200',
            activeTab === tab.id
              ? 'text-primary-600 border-b-2 border-primary-600'
              : 'text-slate-600 hover:text-slate-900'
          ]"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Tab Content -->
      <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-8">
        <!-- Create Committee Tab -->
        <div v-if="activeTab === 'create'" class="space-y-8">
          <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-4">
              {{ $t('pages.committee.tutorial.create.heading') }}
            </h2>
            <p class="text-slate-600 mb-6">
              {{ $t('pages.committee.tutorial.create.description') }}
            </p>
          </div>

          <!-- Step Cards -->
          <div class="space-y-6">
            <!-- Step 1 -->
            <div class="border-l-4 border-primary-600 pl-6 py-4 bg-primary-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                  1
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.create.step1_title') }}
                  </h3>
                  <p class="text-slate-600 mb-3">
                    {{ $t('pages.committee.tutorial.create.step1_text') }}
                  </p>
                  <code class="bg-slate-900 text-slate-100 px-3 py-1 rounded text-sm font-mono">
                    /organisations/publicdigit/committees/create
                  </code>
                </div>
              </div>
            </div>

            <!-- Step 2 -->
            <div class="border-l-4 border-accent-600 pl-6 py-4 bg-accent-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-accent-600 text-white rounded-full flex items-center justify-center font-bold">
                  2
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.create.step2_title') }}
                  </h3>
                  <p class="text-slate-600 mb-3">
                    {{ $t('pages.committee.tutorial.create.step2_text') }}
                  </p>
                  <div class="bg-white rounded p-4 border border-slate-200">
                    <ul class="space-y-2 text-sm text-slate-700">
                      <li>✓ {{ $t('pages.committee.tutorial.create.field_name') }}</li>
                      <li>✓ {{ $t('pages.committee.tutorial.create.field_code') }}</li>
                      <li>✓ {{ $t('pages.committee.tutorial.create.field_type') }}</li>
                      <li>✓ {{ $t('pages.committee.tutorial.create.field_geo') }}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <!-- Step 3 -->
            <div class="border-l-4 border-green-600 pl-6 py-4 bg-green-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                  3
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.create.step3_title') }}
                  </h3>
                  <p class="text-slate-600">
                    {{ $t('pages.committee.tutorial.create.step3_text') }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Pro Tips -->
          <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
            <h4 class="font-bold text-blue-900 mb-2">{{ $t('pages.committee.tutorial.pro_tip') }}</h4>
            <p class="text-blue-800 text-sm">
              {{ $t('pages.committee.tutorial.create.pro_tip') }}
            </p>
          </div>
        </div>

        <!-- Edit Committee Tab -->
        <div v-if="activeTab === 'edit'" class="space-y-8">
          <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-4">
              {{ $t('pages.committee.tutorial.edit.heading') }}
            </h2>
            <p class="text-slate-600 mb-6">
              {{ $t('pages.committee.tutorial.edit.description') }}
            </p>
          </div>

          <div class="space-y-6">
            <div class="border-l-4 border-primary-600 pl-6 py-4 bg-primary-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                  1
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.edit.step1_title') }}
                  </h3>
                  <p class="text-slate-600">
                    {{ $t('pages.committee.tutorial.edit.step1_text') }}
                  </p>
                </div>
              </div>
            </div>

            <div class="border-l-4 border-accent-600 pl-6 py-4 bg-accent-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-accent-600 text-white rounded-full flex items-center justify-center font-bold">
                  2
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.edit.step2_title') }}
                  </h3>
                  <p class="text-slate-600 mb-3">
                    {{ $t('pages.committee.tutorial.edit.step2_text') }}
                  </p>
                  <div class="bg-white rounded p-4 border border-slate-200">
                    <ul class="space-y-2 text-sm text-slate-700">
                      <li>• {{ $t('pages.committee.tutorial.edit.option_name') }}</li>
                      <li>• {{ $t('pages.committee.tutorial.edit.option_status') }}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="border-l-4 border-green-600 pl-6 py-4 bg-green-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                  3
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.edit.step3_title') }}
                  </h3>
                  <p class="text-slate-600">
                    {{ $t('pages.committee.tutorial.edit.step3_text') }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Assign Members Tab -->
        <div v-if="activeTab === 'assign'" class="space-y-8">
          <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-4">
              {{ $t('pages.committee.tutorial.assign.heading') }}
            </h2>
            <p class="text-slate-600 mb-6">
              {{ $t('pages.committee.tutorial.assign.description') }}
            </p>
          </div>

          <div class="space-y-6">
            <div class="border-l-4 border-primary-600 pl-6 py-4 bg-primary-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                  1
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.assign.step1_title') }}
                  </h3>
                  <p class="text-slate-600">
                    {{ $t('pages.committee.tutorial.assign.step1_text') }}
                  </p>
                </div>
              </div>
            </div>

            <div class="border-l-4 border-accent-600 pl-6 py-4 bg-accent-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-accent-600 text-white rounded-full flex items-center justify-center font-bold">
                  2
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.assign.step2_title') }}
                  </h3>
                  <p class="text-slate-600 mb-3">
                    {{ $t('pages.committee.tutorial.assign.step2_text') }}
                  </p>
                  <div class="bg-white rounded p-4 border border-slate-200">
                    <ul class="space-y-2 text-sm text-slate-700">
                      <li>🔍 {{ $t('pages.committee.tutorial.assign.member_search') }}</li>
                      <li>👤 {{ $t('pages.committee.tutorial.assign.member_select') }}</li>
                      <li>⚙️ {{ $t('pages.committee.tutorial.assign.role_select') }}</li>
                      <li>🎯 {{ $t('pages.committee.tutorial.assign.nomination_type') }}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="border-l-4 border-green-600 pl-6 py-4 bg-green-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                  3
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.assign.step3_title') }}
                  </h3>
                  <p class="text-slate-600">
                    {{ $t('pages.committee.tutorial.assign.step3_text') }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-amber-50 border-l-4 border-amber-600 p-4 rounded">
            <h4 class="font-bold text-amber-900 mb-2">{{ $t('pages.committee.tutorial.note') }}</h4>
            <p class="text-amber-800 text-sm">
              {{ $t('pages.committee.tutorial.assign.nomination_note') }}
            </p>
          </div>
        </div>

        <!-- Remove Members Tab -->
        <div v-if="activeTab === 'remove'" class="space-y-8">
          <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-4">
              {{ $t('pages.committee.tutorial.remove.heading') }}
            </h2>
            <p class="text-slate-600 mb-6">
              {{ $t('pages.committee.tutorial.remove.description') }}
            </p>
          </div>

          <div class="space-y-6">
            <div class="border-l-4 border-primary-600 pl-6 py-4 bg-primary-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                  1
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.remove.step1_title') }}
                  </h3>
                  <p class="text-slate-600">
                    {{ $t('pages.committee.tutorial.remove.step1_text') }}
                  </p>
                </div>
              </div>
            </div>

            <div class="border-l-4 border-red-600 pl-6 py-4 bg-red-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center font-bold">
                  2
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.remove.step2_title') }}
                  </h3>
                  <p class="text-slate-600">
                    {{ $t('pages.committee.tutorial.remove.step2_text') }}
                  </p>
                </div>
              </div>
            </div>

            <div class="border-l-4 border-green-600 pl-6 py-4 bg-green-50 rounded-r-lg">
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                  3
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $t('pages.committee.tutorial.remove.step3_title') }}
                  </h3>
                  <p class="text-slate-600">
                    {{ $t('pages.committee.tutorial.remove.step3_text') }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded">
            <h4 class="font-bold text-red-900 mb-2">⚠️ {{ $t('pages.committee.tutorial.warning') }}</h4>
            <p class="text-red-800 text-sm">
              {{ $t('pages.committee.tutorial.remove.warning') }}
            </p>
          </div>
        </div>

        <!-- FAQ Tab -->
        <div v-if="activeTab === 'faq'" class="space-y-6">
          <h2 class="text-2xl font-bold text-slate-900 mb-6">
            {{ $t('pages.committee.tutorial.faq.heading') }}
          </h2>

          <div v-for="(faq, idx) in faqs" :key="idx" class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <button
              @click="expandedFaq = expandedFaq === idx ? null : idx"
              class="w-full text-left flex items-center justify-between"
            >
              <h3 class="font-bold text-slate-900">{{ faq.q }}</h3>
              <span class="text-primary-600 text-lg">{{ expandedFaq === idx ? '−' : '+' }}</span>
            </button>
            <p v-if="expandedFaq === idx" class="text-slate-600 mt-3">
              {{ faq.a }}
            </p>
          </div>
        </div>
      </div>
    </div>

  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useMeta } from '@/composables/useMeta'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

// Set SEO meta tags and title
useMeta({ pageKey: 'committee_tutorial' })

const { t } = useI18n()

const activeTab = ref('create')
const expandedFaq = ref(null)

const tabs = [
  { id: 'create', label: '📋 Create' },
  { id: 'edit', label: '✏️ Edit' },
  { id: 'assign', label: '👥 Assign Members' },
  { id: 'remove', label: '🗑️ Remove Members' },
  { id: 'faq', label: '❓ FAQ' }
]

const breadcrumbs = computed(() => [])

const faqs = computed(() => [
  {
    q: t('pages.committee.tutorial.faq.q1'),
    a: t('pages.committee.tutorial.faq.a1')
  },
  {
    q: t('pages.committee.tutorial.faq.q2'),
    a: t('pages.committee.tutorial.faq.a2')
  },
  {
    q: t('pages.committee.tutorial.faq.q3'),
    a: t('pages.committee.tutorial.faq.a3')
  },
  {
    q: t('pages.committee.tutorial.faq.q4'),
    a: t('pages.committee.tutorial.faq.a4')
  },
  {
    q: t('pages.committee.tutorial.faq.q5'),
    a: t('pages.committee.tutorial.faq.a5')
  },
  {
    q: t('pages.committee.tutorial.faq.q6'),
    a: t('pages.committee.tutorial.faq.a6')
  }
])
</script>

<style scoped>
/* Smooth transitions */
button {
  transition: all 0.3s ease;
}
</style>
