<template>
  <ElectionLayout>
    <!-- Breadcrumb Schema for SEO -->

    <!-- Accessibility: Screen reader announcement -->
    <div role="status" aria-live="polite" class="sr-only">
      {{ $t('pages.organization-show.accessibility.page_loaded', { organization: organization.name }) }}
    </div>

    <!-- Main Content -->
    <main role="main" class="py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
          <Link
            :href="`/organizations/${organization.slug}`"
            class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ $t('modals.member_import.cancel') }}
          </Link>

          <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ $t('modals.member_import.title') }}
          </h1>
          <p class="text-gray-600">
            {{ $t('modals.member_import.description') }}
          </p>
        </div>

        <!-- Two Column Layout: Upload + Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

          <!-- Upload Section (2 columns) -->
          <div class="lg:col-span-2">

            <!-- Step Indicator -->
            <div class="mb-8">
              <div class="flex items-center">
                <div v-for="(step, idx) in steps" :key="step.id" class="flex items-center">
                  <div
                    :class="[
                      'flex items-center justify-center w-10 h-10 rounded-full font-semibold',
                      step.current ? 'bg-blue-600 text-white' : step.completed ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'
                    ]"
                  >
                    <span v-if="step.completed">
                      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </span>
                    <span v-else>{{ idx + 1 }}</span>
                  </div>

                  <!-- Connector Line -->
                  <div v-if="idx < steps.length - 1" :class="['flex-1 h-1 mx-2', step.completed ? 'bg-green-100' : 'bg-gray-200']" />
                </div>
              </div>

              <!-- Step Labels -->
              <div class="flex justify-between mt-2 text-xs text-gray-600">
                <div v-for="step in steps" :key="step.id">
                  {{ step.label }}
                </div>
              </div>
            </div>

            <!-- Step 1: File Upload -->
            <section v-if="currentStep === 'upload'" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">
                {{ $t('modals.member_import.title') }}
              </h2>

              <!-- Upload Area -->
              <div
                @drop="handleFileDrop"
                @dragover.prevent="isDragging = true"
                @dragleave="isDragging = false"
                :class="[
                  'border-2 border-dashed rounded-lg p-12 text-center transition-colors',
                  isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50'
                ]"
              >
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>

                <p class="text-gray-600 mb-3">
                  {{ $t('modals.member_import.select_file') }}
                </p>

                <button
                  type="button"
                  @click="$refs.fileInput.click()"
                  class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                  :disabled="isImporting"
                >
                  <svg v-if="!isImporting" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  {{ $t('modals.member_import.select_file') }}
                </button>

                <p class="text-xs text-gray-500 mt-4">
                  {{ $t('modals.member_import.supported_formats') }}
                </p>

                <input
                  ref="fileInput"
                  type="file"
                  accept=".csv,.xlsx,.xls"
                  @change="handleFileSelect"
                  class="hidden"
                  aria-label="Select member import file"
                />
              </div>

              <!-- Error Message -->
              <div v-if="error" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-700">
                  <strong>Error:</strong> {{ error }}
                </p>
              </div>
            </section>

            <!-- Step 2: Preview -->
            <section v-if="currentStep === 'preview' && preview" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">
                {{ $t('modals.member_import.preview', { count: preview.rows.length }) }}
              </h2>

              <!-- Preview Table -->
              <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden mb-6">
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                  <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-gray-100 border-b border-gray-200">
                      <tr>
                        <th v-for="header in preview.headers" :key="header" class="px-4 py-2 text-left font-medium text-gray-700">
                          {{ header }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, index) in preview.rows.slice(0, 10)" :key="index" class="border-b border-gray-200 hover:bg-gray-100">
                        <td v-for="header in preview.headers" :key="header" class="px-4 py-2 text-gray-900">
                          {{ row[header] || '-' }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div v-if="preview.rows.length > 10" class="px-4 py-2 bg-gray-50 border-t border-gray-200 text-xs text-gray-600">
                  Showing 10 of {{ preview.rows.length }} rows
                </div>
              </div>

              <!-- Validation Errors -->
              <div v-if="validationErrors.length > 0" class="p-4 bg-amber-50 border border-amber-200 rounded-lg mb-6">
                <p class="font-semibold text-amber-900 mb-3">Validation Issues:</p>
                <ul class="space-y-1 text-sm text-amber-800">
                  <li v-for="(error, idx) in validationErrors.slice(0, 10)" :key="idx" class="flex items-start gap-2">
                    <span class="shrink-0 mt-0.5">•</span>
                    <span>{{ error }}</span>
                  </li>
                </ul>
                <p v-if="validationErrors.length > 10" class="text-sm text-amber-800 mt-3">
                  ...and {{ validationErrors.length - 10 }} more issues
                </p>
              </div>

              <!-- Actions -->
              <div class="flex gap-3">
                <button
                  type="button"
                  @click="resetFile"
                  class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium transition-colors"
                >
                  {{ $t('modals.member_import.select_file') }}
                </button>

                <button
                  v-if="validationErrors.length === 0"
                  type="button"
                  @click="submitImport"
                  class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium transition-colors disabled:opacity-50"
                  :disabled="isImporting"
                >
                  {{ isImporting ? $t('modals.member_import.importing') : $t('modals.member_import.import') }}
                </button>

                <button
                  v-else
                  type="button"
                  disabled
                  class="px-4 py-2 bg-gray-300 text-gray-600 rounded-md cursor-not-allowed font-medium"
                  :title="$t('modals.member_import.validation.invalid_format')"
                >
                  {{ $t('modals.member_import.import') }} (Fix errors)
                </button>
              </div>
            </section>

            <!-- Step 3: Success -->
            <section v-if="currentStep === 'success'" class="bg-white rounded-lg shadow-xs p-6 mb-6">
              <div class="text-center">
                <svg class="mx-auto w-16 h-16 text-green-600 mb-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                  Success!
                </h2>

                <p class="text-gray-600 mb-6">
                  {{ successMessage }}
                </p>

                <Link
                  :href="`/organizations/${organization.slug}`"
                  class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                  Back to Organization
                </Link>
              </div>
            </section>

            <!-- Progress Bar -->
            <div v-if="isImporting" class="mt-6">
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="{ width: importProgress + '%' }" />
              </div>
              <p class="text-sm text-gray-600 mt-2">{{ importProgress }}% Complete</p>
            </div>

          </div>

          <!-- Information Panel (1 column) -->
          <aside class="lg:col-span-1">
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200 sticky top-4">
              <h3 class="font-semibold text-gray-900 mb-4">
                📋 File Format
              </h3>

              <div class="space-y-4 text-sm">
                <div>
                  <p class="font-medium text-gray-900 mb-1">Supported Formats:</p>
                  <ul class="text-gray-600 space-y-1">
                    <li>• CSV (.csv)</li>
                    <li>• Excel (.xlsx, .xls)</li>
                  </ul>
                </div>

                <div class="border-t border-blue-200 pt-4">
                  <p class="font-medium text-gray-900 mb-2">Required Columns:</p>
                  <ul class="text-gray-600 space-y-1">
                    <li>• Email (required)</li>
                    <li>• First Name</li>
                    <li>• Last Name</li>
                  </ul>
                </div>

                <div class="border-t border-blue-200 pt-4">
                  <p class="font-medium text-gray-900 mb-2">Optional Columns:</p>
                  <ul class="text-gray-600 space-y-1">
                    <li>• Phone</li>
                    <li>• Region</li>
                    <li>• Join Date</li>
                  </ul>
                </div>

                <div class="border-t border-blue-200 pt-4">
                  <a href="/templates/members.csv" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Template
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
import { Link } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { useMemberImport } from '@/composables/useMemberImport'
import { useMeta } from '@/composables/useMeta'

const { t } = useI18n()

const props = defineProps({
  organization: {
    type: Object,
    required: true
  }
})

// State
const fileInput = ref(null)
const isDragging = ref(false)
const currentStep = ref('upload') // upload, preview, success
const preview = ref(null)
const error = ref(null)
const isImporting = ref(false)
const validationErrors = ref([])
const importProgress = ref(0)
const successMessage = ref('')

// Steps for progress indicator
const steps = computed(() => [
  { id: 'upload', label: 'Upload', current: currentStep.value === 'upload', completed: preview.value !== null },
  { id: 'preview', label: 'Review', current: currentStep.value === 'preview', completed: currentStep.value === 'success' },
  { id: 'success', label: 'Complete', current: currentStep.value === 'success', completed: false }
])

// Composable
const { parseFile, validateData, submitImport: apiSubmit } = useMemberImport(props.organization)

/**
 * Handle file selection
 */
const handleFileSelect = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    processFile(file)
  }
}

/**
 * Handle drag & drop
 */
const handleFileDrop = (event) => {
  event.preventDefault()
  isDragging.value = false

  const file = event.dataTransfer?.files?.[0]
  if (file) {
    processFile(file)
  }
}

/**
 * Process file and show preview
 */
const processFile = async (file) => {
  error.value = null
  validationErrors.value = []

  try {
    // Validate file type
    const validTypes = ['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']
    if (!validTypes.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/i)) {
      throw new Error(t('modals.member_import.validation.invalid_format'))
    }

    // Parse file
    const data = await parseFile(file)

    // Validate data
    const validation = await validateData(data)
    if (!validation.valid) {
      validationErrors.value = validation.errors
    }

    // Show preview
    preview.value = {
      file: file.name,
      headers: data.headers,
      rows: data.rows
    }

    currentStep.value = 'preview'
  } catch (err) {
    error.value = err.message
  }
}

/**
 * Reset file selection
 */
const resetFile = () => {
  preview.value = null
  error.value = null
  validationErrors.value = []
  currentStep.value = 'upload'
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

/**
 * Submit import to server
 */
const submitImport = async () => {
  if (!preview.value) return

  isImporting.value = true
  error.value = null
  importProgress.value = 0

  try {
    // Simulate progress (real progress would come from server)
    const progressInterval = setInterval(() => {
      importProgress.value = Math.min(importProgress.value + Math.random() * 30, 90)
    }, 500)

    const result = await apiSubmit({
      headers: preview.value.headers,
      rows: preview.value.rows,
      fileName: preview.value.file
    })

    clearInterval(progressInterval)
    importProgress.value = 100

    // Show success
    successMessage.value = t('modals.member_import.success', { count: result.imported_count || preview.value.rows.length })
    currentStep.value = 'success'
  } catch (err) {
    error.value = err.message
    currentStep.value = 'preview'
    importProgress.value = 0
    console.error('Import error:', err)
  } finally {
    isImporting.value = false
  }
}

// SEO Meta Tags
useMeta({
  pageKey: 'organizations.show',
  params: {
    organization: `${props.organization.name} - Member Import`
  }
})
</script>
