<template>
  <ElectionLayout>
    <div role="status" aria-live="polite" class="sr-only">
      {{ t.title }} — {{ organisation.name }}
    </div>

    <main role="main" class="py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
          <Link :href="`/organisations/${organisation.slug}`"
                class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ t.back }}
          </Link>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ t.title }}</h1>
          <p class="text-gray-600">{{ t.description }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

          <!-- Main area -->
          <div class="lg:col-span-2">

            <!-- Step indicator -->
            <div class="mb-8">
              <div class="flex items-center">
                <div v-for="(step, idx) in steps" :key="step.id" class="flex items-center">
                  <div :class="['flex items-center justify-center w-10 h-10 rounded-full font-semibold',
                    step.current   ? 'bg-blue-600 text-white'
                    : step.completed ? 'bg-green-100 text-green-800'
                    : 'bg-gray-100 text-gray-600']">
                    <span v-if="step.completed">
                      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </span>
                    <span v-else>{{ idx + 1 }}</span>
                  </div>
                  <div v-if="idx < steps.length - 1"
                       :class="['flex-1 h-1 mx-2 w-8', step.completed ? 'bg-green-200' : 'bg-gray-200']" />
                </div>
              </div>
              <div class="flex gap-12 mt-2 text-xs text-gray-600">
                <span v-for="step in steps" :key="step.id">{{ step.label }}</span>
              </div>
            </div>

            <!-- Step 1: Upload -->
            <section v-if="currentStep === 'upload'" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ t.upload.title }}</h2>

              <div @drop="handleFileDrop" @dragover.prevent="isDragging = true" @dragleave="isDragging = false"
                   :class="['border-2 border-dashed rounded-lg p-12 text-center transition-colors',
                     isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50']">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="text-gray-600 mb-3">{{ t.upload.drag_hint }}</p>
                <button type="button" @click="$refs.fileInput.click()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  {{ t.upload.select_btn }}
                </button>
                <p class="text-xs text-gray-500 mt-4">{{ t.upload.formats_hint }}</p>
                <input ref="fileInput" type="file" accept=".csv,.xlsx,.xls"
                       @change="handleFileSelect" class="hidden" :aria-label="t.upload.aria_label" />
              </div>

              <div v-if="error" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-700"><strong>{{ t.error.label }}</strong> {{ error }}</p>
              </div>
            </section>

            <!-- Step 2: Preview -->
            <section v-if="currentStep === 'preview' && preview" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">
                {{ t.preview.title.replace('{count}', preview.rows.length) }}
              </h2>

              <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden mb-6">
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                  <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-gray-100 border-b border-gray-200">
                      <tr>
                        <th v-for="header in preview.headers" :key="header"
                            class="px-4 py-2 text-left font-medium text-gray-700">{{ header }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, index) in preview.rows.slice(0, 10)" :key="index"
                          class="border-b border-gray-200 hover:bg-gray-100">
                        <td v-for="header in preview.headers" :key="header" class="px-4 py-2 text-gray-900">
                          {{ row[header] || '-' }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div v-if="preview.rows.length > 10" class="px-4 py-2 bg-gray-50 border-t border-gray-200 text-xs text-gray-600">
                  {{ t.preview.showing.replace('{count}', preview.rows.length) }}
                </div>
              </div>

              <div v-if="validationErrors.length > 0" class="p-4 bg-amber-50 border border-amber-200 rounded-lg mb-6">
                <p class="font-semibold text-amber-900 mb-3">{{ t.preview.validation_issues }}</p>
                <ul class="space-y-1 text-sm text-amber-800">
                  <li v-for="(err, idx) in validationErrors.slice(0, 10)" :key="idx" class="flex items-start gap-2">
                    <span class="shrink-0 mt-0.5">•</span><span>{{ err }}</span>
                  </li>
                </ul>
              </div>

              <div class="flex gap-3">
                <button type="button" @click="resetFile"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                  {{ t.preview.change_file }}
                </button>
                <button v-if="validationErrors.length === 0" type="button" @click="startImport"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        :disabled="isUploading">
                  {{ isUploading ? t.preview.importing_btn : t.preview.import_btn }}
                  <span v-if="!isUploading" class="ml-1 text-green-200 text-xs">({{ preview.rows.length }})</span>
                </button>
              </div>
            </section>

            <!-- Step 3: Processing -->
            <section v-if="currentStep === 'processing'" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ t.processing.title }}</h2>

              <div class="mb-6">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                  <span>{{ t.processing.rows_label.replace('{processed}', jobStatus.processed_rows).replace('{total}', jobStatus.total_rows || '?') }}</span>
                  <span>{{ importProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3" role="progressbar" :aria-valuenow="importProgress" aria-valuemin="0" aria-valuemax="100">
                  <div class="bg-blue-600 h-3 rounded-full transition-all duration-500"
                       :style="{ width: importProgress + '%' }" />
                </div>
              </div>

              <div class="grid grid-cols-3 gap-4 text-center text-sm">
                <div class="bg-green-50 rounded-lg p-3">
                  <div class="text-2xl font-bold text-green-700">{{ jobStatus.imported_count }}</div>
                  <div class="text-green-600">{{ t.processing.imported }}</div>
                </div>
                <div class="bg-amber-50 rounded-lg p-3">
                  <div class="text-2xl font-bold text-amber-700">{{ jobStatus.skipped_count }}</div>
                  <div class="text-amber-600">{{ t.processing.skipped }}</div>
                </div>
                <div class="bg-blue-50 rounded-lg p-3">
                  <div class="text-2xl font-bold text-blue-700">{{ jobStatus.total_rows || '—' }}</div>
                  <div class="text-blue-600">{{ t.processing.total }}</div>
                </div>
              </div>

              <p class="text-xs text-gray-500 mt-4 text-center">{{ t.processing.background_hint }}</p>
            </section>

            <!-- Step 4: Success -->
            <section v-if="currentStep === 'success'" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <div class="text-center">
                <svg class="mx-auto w-16 h-16 text-green-600 mb-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ t.success.title }}</h2>
                <p class="text-gray-600 mb-2">
                  {{ t.success.imported_msg.replace('{count}', jobStatus.imported_count) }}
                </p>
                <p v-if="jobStatus.skipped_count > 0" class="text-amber-600 text-sm mb-6">
                  {{ t.success.skipped_msg.replace('{count}', jobStatus.skipped_count) }}
                </p>
                <Link :href="`/organisations/${organisation.slug}`"
                      class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                  {{ t.success.back_btn }}
                </Link>
              </div>
            </section>

            <!-- Error (outside upload step) -->
            <div v-if="error && currentStep !== 'upload'" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
              <p class="text-sm text-red-700"><strong>{{ t.error.label }}</strong> {{ error }}</p>
              <button @click="resetFile"
                      class="mt-2 text-sm text-red-600 underline focus:outline-none focus:ring-2 focus:ring-red-400 rounded">
                {{ t.error.try_again }}
              </button>
            </div>

          </div>

          <!-- Info panel -->
          <aside class="lg:col-span-1" aria-label="File format information">
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200 sticky top-4">
              <h3 class="font-semibold text-gray-900 mb-4">📋 {{ t.info_panel.title }}</h3>
              <div class="space-y-4 text-sm">

                <div>
                  <p class="font-medium text-gray-900 mb-1">{{ t.info_panel.formats_heading }}</p>
                  <ul class="text-gray-600 space-y-1">
                    <li v-for="fmt in t.info_panel.formats" :key="fmt">• {{ fmt }}</li>
                  </ul>
                </div>

                <div class="border-t border-blue-200 pt-4">
                  <p class="font-medium text-gray-900 mb-2">{{ t.info_panel.columns_heading }}</p>
                  <ul class="text-gray-600 space-y-1">
                    <li v-for="col in t.info_panel.columns" :key="col">• {{ col }}</li>
                  </ul>
                </div>

                <div class="border-t border-blue-200 pt-4">
                  <p class="font-medium text-gray-900 mb-2">{{ t.info_panel.scale_heading }}</p>
                  <p class="text-gray-600">{{ t.info_panel.scale_text }}</p>
                </div>

                <div class="border-t border-blue-200 pt-4">
                  <a :href="route('organisations.members.import.template', organisation.slug)"
                     class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
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
  </ElectionLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { Link } from '@inertiajs/vue3'
import { useMemberImport } from '@/composables/useMemberImport'

import pageDe from '@/locales/pages/Organisations/Members/Import/de.json'
import pageEn from '@/locales/pages/Organisations/Members/Import/en.json'
import pageNp from '@/locales/pages/Organisations/Members/Import/np.json'

const { locale } = useI18n()
const pageData = { de: pageDe, en: pageEn, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.de)

const props = defineProps({
  organisation: { type: Object, required: true }
})

// ── State ────────────────────────────────────────────────────────────────────
const fileInput        = ref(null)
const rawFile          = ref(null)
const isDragging       = ref(false)
const currentStep      = ref('upload')   // upload | preview | processing | success
const preview          = ref(null)
const error            = ref(null)
const isUploading      = ref(false)
const validationErrors = ref([])
const jobId            = ref(null)
const jobStatus        = ref({ imported_count: 0, skipped_count: 0, total_rows: 0, processed_rows: 0 })
const cancelPoll       = ref(null)

const importProgress = computed(() => {
  if (!jobStatus.value.total_rows) return 0
  return Math.round((jobStatus.value.processed_rows / jobStatus.value.total_rows) * 100)
})

const steps = computed(() => [
  { id: 'upload',     label: t.value.steps.upload,     current: currentStep.value === 'upload',     completed: preview.value !== null },
  { id: 'preview',    label: t.value.steps.review,     current: currentStep.value === 'preview',    completed: ['processing', 'success'].includes(currentStep.value) },
  { id: 'processing', label: t.value.steps.processing, current: currentStep.value === 'processing', completed: currentStep.value === 'success' },
  { id: 'success',    label: t.value.steps.complete,   current: currentStep.value === 'success',    completed: false },
])

const { parsePreview, uploadFile, pollStatus } = useMemberImport(props.organisation)

// ── File selection ────────────────────────────────────────────────────────────
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
  error.value            = null
  validationErrors.value = []
  rawFile.value          = file

  try {
    const validTypes = ['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']
    if (!validTypes.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/i)) {
      throw new Error(t.value.error.invalid_format)
    }

    const data = await parsePreview(file)
    preview.value     = { file: file.name, headers: data.headers, rows: data.rows }
    currentStep.value = 'preview'
  } catch (err) {
    error.value = err.message
  }
}

const resetFile = () => {
  cancelPoll.value?.()
  preview.value          = null
  rawFile.value          = null
  error.value            = null
  validationErrors.value = []
  jobId.value            = null
  jobStatus.value        = { imported_count: 0, skipped_count: 0, total_rows: 0, processed_rows: 0 }
  currentStep.value      = 'upload'
  if (fileInput.value) fileInput.value.value = ''
}

// ── Upload + poll ─────────────────────────────────────────────────────────────
const startImport = async () => {
  if (!rawFile.value) return
  isUploading.value = true
  error.value       = null

  try {
    const result  = await uploadFile(rawFile.value)
    jobId.value   = result.job_id
    currentStep.value = 'processing'

    cancelPoll.value = pollStatus(
      result.job_id,
      (status) => { jobStatus.value = status },
      (status) => { jobStatus.value = status; currentStep.value = 'success' },
      (err)    => { error.value = err.message; currentStep.value = 'preview' }
    )
  } catch (err) {
    error.value = err.message
  } finally {
    isUploading.value = false
  }
}
</script>
