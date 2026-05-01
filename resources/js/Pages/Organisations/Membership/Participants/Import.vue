<template>
  <PublicDigitLayout>
    <div role="status" aria-live="polite" class="sr-only">
      {{ t.title }} — {{ organisation.name }}
    </div>

    <main role="main" class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
          <Link :href="route('organisations.membership.participants.index', organisation.slug)"
                class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ t.back }}
          </Link>
          <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ t.title }}</h1>
          <p class="text-neutral-600">{{ t.description }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

          <!-- Main area -->
          <div class="lg:col-span-3">

            <!-- Step indicator -->
            <div class="mb-8">
              <div class="flex items-center">
                <div v-for="(step, idx) in steps" :key="step.id" class="flex items-center">
                  <div :class="['flex items-center justify-center w-10 h-10 rounded-full font-semibold',
                    step.current    ? 'bg-primary-600 text-white'
                    : step.completed ? 'bg-green-100 text-green-800'
                    : 'bg-neutral-100 text-neutral-600']">
                    <span v-if="step.completed">
                      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </span>
                    <span v-else>{{ idx + 1 }}</span>
                  </div>
                  <div v-if="idx < steps.length - 1"
                       :class="['flex-1 h-1 mx-2 w-8', step.completed ? 'bg-green-200' : 'bg-neutral-200']" />
                </div>
              </div>
              <div class="flex gap-12 mt-2 text-xs text-neutral-600">
                <span v-for="step in steps" :key="step.id">{{ step.label }}</span>
              </div>
            </div>

            <!-- Flash success -->
            <div v-if="$page.props.flash?.success && currentStep === 'done'"
                 class="bg-white rounded-lg shadow-xs p-6 mb-6 text-center">
              <svg class="mx-auto w-16 h-16 text-green-600 mb-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <h2 class="text-2xl font-bold text-neutral-900 mb-2">{{ t.success.title }}</h2>
              <p class="text-neutral-600 mb-6">{{ $page.props.flash.success }}</p>
              <Link :href="route('organisations.membership.participants.index', organisation.slug)"
                    class="inline-flex items-center px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                {{ t.success.back_btn }}
              </Link>
            </div>

            <!-- Step 1: Upload -->
            <section v-if="currentStep === 'upload'" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <h2 class="text-xl font-semibold text-neutral-900 mb-4">{{ t.upload.title }}</h2>

              <div @drop="handleFileDrop" @dragover.prevent="isDragging = true" @dragleave="isDragging = false"
                   :class="['border-2 border-dashed rounded-lg p-16 text-center transition-colors cursor-pointer',
                     isDragging ? 'border-primary-500 bg-primary-50' : 'border-neutral-300 bg-neutral-50']"
                   @click="$refs.fileInput.click()">
                <svg class="mx-auto h-16 w-16 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="text-neutral-600 mb-3">{{ t.upload.drag_hint }}</p>
                <button type="button"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        @click.stop="$refs.fileInput.click()">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  {{ t.upload.select_btn }}
                </button>
                <p class="text-xs text-neutral-500 mt-4">{{ t.upload.formats_hint }}</p>
                <input ref="fileInput" type="file" accept=".csv,.xlsx,.xls"
                       @change="handleFileSelect" class="hidden" :aria-label="t.upload.aria_label" />
              </div>

              <div v-if="error" class="mt-6 p-4 bg-danger-50 border border-danger-200 rounded-lg">
                <p class="text-sm text-danger-700"><strong>{{ t.error.label }}</strong> {{ error }}</p>
              </div>
            </section>

            <!-- Step 2: Preview -->
            <section v-if="currentStep === 'preview' && previewData" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <h2 class="text-xl font-semibold text-neutral-900 mb-2">
                {{ t.preview.title
                    .replace('{total}', previewData.stats.total)
                    .replace('{valid}', previewData.stats.valid)
                    .replace('{invalid}', previewData.stats.invalid) }}
              </h2>

              <!-- Stats bar -->
              <div class="flex gap-4 mb-6">
                <div class="flex-1 bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                  <div class="text-2xl font-bold text-green-700">{{ previewData.stats.valid }}</div>
                  <div class="text-xs text-green-600">Valid</div>
                </div>
                <div class="flex-1 bg-danger-50 border border-danger-200 rounded-lg p-3 text-center">
                  <div class="text-2xl font-bold text-danger-700">{{ previewData.stats.invalid }}</div>
                  <div class="text-xs text-danger-600">Invalid</div>
                </div>
                <div class="flex-1 bg-neutral-50 border border-neutral-200 rounded-lg p-3 text-center">
                  <div class="text-2xl font-bold text-neutral-700">{{ previewData.stats.total }}</div>
                  <div class="text-xs text-neutral-600">Total</div>
                </div>
              </div>

              <!-- Row table -->
              <div class="bg-neutral-50 rounded-lg border border-neutral-200 overflow-hidden mb-6">
                <div class="overflow-x-auto max-h-[32rem] overflow-y-auto">
                  <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-100 border-b border-neutral-200">
                      <tr>
                        <th class="px-3 py-2 text-left font-medium text-neutral-700">{{ t.preview.table.row }}</th>
                        <th class="px-3 py-2 text-left font-medium text-neutral-700">{{ t.preview.table.email }}</th>
                        <th class="px-3 py-2 text-left font-medium text-neutral-700">{{ t.preview.table.type }}</th>
                        <th class="px-3 py-2 text-left font-medium text-neutral-700">{{ t.preview.table.status }}</th>
                        <th class="px-3 py-2 text-left font-medium text-neutral-700">{{ t.preview.table.errors }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="row in previewData.preview" :key="row.row"
                          :class="['border-b border-neutral-200', row.errors?.length ? 'bg-danger-50' : 'hover:bg-neutral-100']">
                        <td class="px-3 py-2 text-neutral-500">{{ row.row }}</td>
                        <td class="px-3 py-2 text-neutral-900">{{ row.email }}</td>
                        <td class="px-3 py-2 text-neutral-700">{{ row.participant_type }}</td>
                        <td class="px-3 py-2">
                          <span :class="row.errors?.length
                            ? 'text-danger-700 font-medium'
                            : 'text-green-700 font-medium'">
                            {{ row.status }}
                          </span>
                        </td>
                        <td class="px-3 py-2 text-danger-600 text-xs">
                          <ul v-if="row.errors?.length" class="space-y-1">
                            <li v-for="(err, idx) in row.errors" :key="idx" class="flex items-start gap-1">
                              <span class="flex-shrink-0 mt-0.5">•</span>
                              <span>{{ err }}</span>
                            </li>
                          </ul>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div v-if="previewData.stats.invalid > 0" class="p-3 bg-amber-50 border border-amber-200 rounded-lg mb-4 text-sm text-amber-800">
                {{ t.preview.has_errors.replace('{invalid}', previewData.stats.invalid) }}
              </div>
              <div v-else class="p-3 bg-green-50 border border-green-200 rounded-lg mb-4 text-sm text-green-800">
                {{ t.preview.no_errors }}
              </div>

              <!-- How-to-fix hints when there are invalid rows -->
              <div v-if="previewData.stats.invalid > 0" class="p-4 bg-primary-50 border border-primary-200 rounded-lg mb-4">
                <p class="text-sm font-semibold text-primary-800 mb-2">💡 {{ t.preview.fix_hints.heading }}</p>
                <ul class="text-xs text-primary-700 space-y-1">
                  <li v-for="(hint, idx) in t.preview.fix_hints.items" :key="idx">• {{ hint }}</li>
                </ul>
              </div>

              <div class="flex gap-3">
                <button type="button" @click="resetFile"
                        class="px-4 py-2 border border-neutral-300 text-neutral-700 rounded-md hover:bg-neutral-50 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                  {{ t.preview.change_file }}
                </button>
                <button v-if="previewData.stats.valid > 0"
                        type="button" @click="runImport"
                        :disabled="isImporting"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50">
                  {{ isImporting
                      ? t.preview.importing_btn
                      : t.preview.import_btn.replace('{count}', previewData.stats.valid) }}
                </button>
              </div>

              <div v-if="error" class="mt-4 p-4 bg-danger-50 border border-danger-200 rounded-lg">
                <p class="text-sm text-danger-700"><strong>{{ t.error.label }}</strong> {{ error }}</p>
              </div>
            </section>

          </div>

          <!-- Info panel -->
          <aside class="lg:col-span-1" aria-label="File format information">
            <div class="bg-primary-50 rounded-lg p-6 border border-primary-200 sticky top-4">
              <h3 class="font-semibold text-neutral-900 mb-4">📋 {{ t.info_panel.title }}</h3>
              <div class="space-y-4 text-sm">

                <!-- Pre-import checklist -->
                <div class="p-3 bg-white rounded border border-primary-100">
                  <p class="text-xs font-semibold text-primary-800 mb-2">✅ {{ t.info_panel.checklist_heading }}</p>
                  <ul class="text-xs text-neutral-600 space-y-1">
                    <li v-for="(item, idx) in t.info_panel.checklist" :key="idx">• {{ item }}</li>
                  </ul>
                </div>

                <div>
                  <p class="font-medium text-neutral-900 mb-1">{{ t.info_panel.formats_heading }}</p>
                  <ul class="text-neutral-600 space-y-1">
                    <li v-for="fmt in t.info_panel.formats" :key="fmt">• {{ fmt }}</li>
                  </ul>
                </div>

                <div class="border-t border-primary-200 pt-4">
                  <p class="font-medium text-neutral-900 mb-2">{{ t.info_panel.columns_heading }}</p>
                  <ul class="text-neutral-600 space-y-1">
                    <li v-for="col in t.info_panel.columns" :key="col">• {{ col }}</li>
                  </ul>
                </div>

                <div class="border-t border-primary-200 pt-4">
                  <p class="text-xs text-amber-700 bg-amber-50 rounded p-2">{{ t.info_panel.note }}</p>
                </div>

                <div class="border-t border-primary-200 pt-4">
                  <a :href="route('organisations.membership.participants.import.template', organisation.slug)"
                     class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ t.info_panel.download_template }}
                  </a>
                </div>

              </div>
            </div>
          </aside>

        </div>
      </div>
    </main>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { router, Link, usePage } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

import pageEn from '@/locales/pages/Organisations/Membership/Participants/Import/en.json'
import pageDe from '@/locales/pages/Organisations/Membership/Participants/Import/de.json'
import pageNp from '@/locales/pages/Organisations/Membership/Participants/Import/np.json'

const { locale } = useI18n()
const pageData = { en: pageEn, de: pageDe, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.en)

const props = defineProps({
  organisation: { type: Object, required: true },
})

// ── State ─────────────────────────────────────────────────────────────────────
const fileInput   = ref(null)
const rawFile     = ref(null)
const isDragging  = ref(false)
const currentStep = ref('upload')   // upload | preview | done
const previewData = ref(null)
const error       = ref(null)
const isImporting = ref(false)

const steps = computed(() => [
  { id: 'upload',  label: t.value.steps.upload,  current: currentStep.value === 'upload',  completed: previewData.value !== null },
  { id: 'preview', label: t.value.steps.review,  current: currentStep.value === 'preview', completed: currentStep.value === 'done' },
  { id: 'done',    label: t.value.steps.complete, current: currentStep.value === 'done',   completed: false },
])

// ── File selection ─────────────────────────────────────────────────────────────
const handleFileSelect = (event) => {
  const file = event.target.files?.[0]
  if (file) processFile(file)
}

const handleFileDrop = (event) => {
  event.preventDefault()
  isDragging.value = false
  const file = event.dataTransfer?.files?.[0]
  if (file) processFile(file)
}

const processFile = async (file) => {
  error.value   = null
  rawFile.value = file

  const validExt = /\.(csv|xlsx|xls)$/i
  if (!validExt.test(file.name)) {
    error.value = t.value.error.invalid_format
    return
  }

  const formData = new FormData()
  formData.append('file', file)

  try {
    const response = await fetch(
      route('organisations.membership.participants.import.preview', props.organisation.slug),
      {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        },
        body: formData,
      }
    )

    if (!response.ok) {
      const data = await response.json().catch(() => ({}))
      throw new Error(data.message ?? `Server error ${response.status}`)
    }

    previewData.value = await response.json()
    currentStep.value = 'preview'
  } catch (err) {
    error.value = err.message
  }
}

const resetFile = () => {
  previewData.value = null
  rawFile.value     = null
  error.value       = null
  currentStep.value = 'upload'
  if (fileInput.value) fileInput.value.value = ''
}

// ── Import ────────────────────────────────────────────────────────────────────
const runImport = () => {
  if (!rawFile.value || isImporting.value) return
  isImporting.value = true
  error.value       = null

  const formData = new FormData()
  formData.append('file', rawFile.value)
  formData.append('confirmed', '1')

  router.post(
    route('organisations.membership.participants.import', props.organisation.slug),
    formData,
    {
      forceFormData: true,
      preserveScroll: true,
      onSuccess: () => { currentStep.value = 'done' },
      onError: (errors) => { error.value = Object.values(errors).flat().join(' ') },
      onFinish: () => { isImporting.value = false },
    }
  )
}
</script>

