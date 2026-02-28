<template>
  <Modal
    :show="show"
    @close="handleClose"
    max-width="2xl"
  >
    <div class="p-6">
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
          {{ $t('modals.member_import.title') }}
        </h2>
        <p class="text-gray-600">
          {{ $t('modals.member_import.description') }}
        </p>
      </div>

      <!-- File Upload Area or Preview -->
      <div v-if="!preview" class="mb-6">
        <!-- File Upload Dropzone -->
        <div
          @drop="handleFileDrop"
          @dragover="isDragging = true"
          @dragleave="isDragging = false"
          :class="[
            'border-2 border-dashed rounded-lg p-8 text-center transition-colors',
            isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50'
          ]"
        >
          <!-- Icon -->
          <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>

          <!-- Text & Button -->
          <p class="text-gray-600 mb-2">
            {{ $t('modals.member_import.select_file') }}
          </p>
          <button
            type="button"
            @click="$refs.fileInput.click()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
            :disabled="isImporting"
          >
            <svg v-if="!isImporting" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span v-if="isImporting">{{ $t('modals.member_import.uploading') }}</span>
            <span v-else>{{ $t('modals.member_import.select_file') }}</span>
          </button>

          <!-- Supported Formats -->
          <p class="text-xs text-gray-500 mt-3">
            {{ $t('modals.member_import.supported_formats') }}
          </p>

          <!-- Hidden File Input -->
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
        <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-sm text-red-700">
            <strong>{{ $t('modals.member_import.error', { error: error }) }}</strong>
          </p>
        </div>
      </div>

      <!-- Preview Section -->
      <div v-else class="mb-6">
        <!-- Preview Header -->
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="font-semibold text-gray-900">
              {{ $t('modals.member_import.preview', { count: preview.rows.length }) }}
            </h3>
            <p class="text-sm text-gray-600 mt-1">
              {{ $t('modals.member_import.preview_headers') }}
            </p>
          </div>
          <button
            type="button"
            @click="resetPreview"
            class="text-sm text-blue-600 hover:text-blue-700"
            :disabled="isImporting"
          >
            {{ $t('modals.member_import.select_file') }}
          </button>
        </div>

        <!-- Preview Table -->
        <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
          <div class="overflow-x-auto max-h-96 overflow-y-auto">
            <table class="w-full text-sm">
              <!-- Headers -->
              <thead class="sticky top-0 bg-gray-100 border-b border-gray-200">
                <tr>
                  <th v-for="header in preview.headers" :key="header" class="px-4 py-2 text-left font-medium text-gray-700">
                    {{ header }}
                  </th>
                </tr>
              </thead>

              <!-- Sample Rows -->
              <tbody>
                <tr v-for="(row, index) in preview.rows.slice(0, 5)" :key="index" class="border-b border-gray-200 hover:bg-gray-100">
                  <td v-for="header in preview.headers" :key="header" class="px-4 py-2 text-gray-900">
                    {{ row[header] || '-' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Row Count Info -->
          <div v-if="preview.rows.length > 5" class="px-4 py-2 bg-gray-50 border-t border-gray-200 text-xs text-gray-600">
            {{ $t('modals.member_import.preview', { count: preview.rows.length }) }} ({{ $t('modals.member_import.preview', { count: '5' }) }} shown)
          </div>
        </div>

        <!-- Validation Errors (if any) -->
        <div v-if="validationErrors.length > 0" class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
          <p class="font-semibold text-amber-900 mb-2">Validation Issues:</p>
          <ul class="space-y-1 text-sm text-amber-800">
            <li v-for="(error, idx) in validationErrors.slice(0, 5)" :key="idx">
              • {{ error }}
            </li>
          </ul>
          <p v-if="validationErrors.length > 5" class="text-sm text-amber-800 mt-2">
            ...and {{ validationErrors.length - 5 }} more issues
          </p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
        <button
          type="button"
          @click="handleClose"
          class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium transition-colors"
          :disabled="isImporting"
        >
          {{ $t('modals.member_import.cancel') }}
        </button>

        <button
          v-if="preview"
          type="button"
          @click="submitImport"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="isImporting || validationErrors.length > 0"
        >
          <span v-if="isImporting">{{ $t('modals.member_import.importing') }}</span>
          <span v-else>{{ $t('modals.member_import.import') }}</span>
        </button>
      </div>

      <!-- Progress Indicator -->
      <div v-if="isImporting" class="mt-4">
        <div class="w-full bg-gray-200 rounded-full h-1">
          <div class="bg-blue-600 h-1 rounded-full" style="width: 50%;" />
        </div>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import Modal from '@/Components/Jetstream/Modal.vue'
import { useMemberImport } from '@/composables/useMemberImport'

const { t } = useI18n()

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  organisation: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close', 'imported'])

// File handling
const fileInput = ref(null)
const isDragging = ref(false)

// State management
const preview = ref(null)
const error = ref(null)
const isImporting = ref(false)
const validationErrors = ref([])

// Composable
const { parseFile, validateData, submitImport: apiSubmit } = useMemberImport(props.organisation)

/**
 * Handle file selection from input
 */
const handleFileSelect = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    processFile(file)
  }
}

/**
 * Handle drag & drop file drop
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
 * Process selected/dropped file
 */
const processFile = async (file) => {
  error.value = null
  preview.value = null
  validationErrors.value = []

  try {
    // Check file type
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
      // Still show preview even with errors
    }

    // Set preview
    preview.value = {
      file: file.name,
      headers: data.headers,
      rows: data.rows
    }
  } catch (err) {
    error.value = err.message
    preview.value = null
  }
}

/**
 * Reset preview to allow file selection again
 */
const resetPreview = () => {
  preview.value = null
  error.value = null
  validationErrors.value = []
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

  try {
    const result = await apiSubmit({
      headers: preview.value.headers,
      rows: preview.value.rows,
      fileName: preview.value.file
    })

    // Show success message
    emit('imported', result)
    handleClose()
  } catch (err) {
    error.value = err.message
    console.error('Import error:', err)
  } finally {
    isImporting.value = false
  }
}

/**
 * Handle modal close
 */
const handleClose = () => {
  resetPreview()
  emit('close')
}
</script>
