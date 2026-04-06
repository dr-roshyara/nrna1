<template>
  <div class="space-y-3">

    <!-- Existing attachments -->
    <TransitionGroup name="attachment" tag="div" class="space-y-2">
      <div
        v-for="att in attachments"
        :key="att.id"
        class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm
               hover:border-slate-300 transition-all duration-200"
      >
        <!-- File type icon -->
        <div :class="iconBg(att.mime_type)" class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center">
          <component :is="fileIcon(att.mime_type)" class="w-5 h-5" />
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-slate-800 truncate">{{ att.original_name }}</p>
          <p class="text-xs text-slate-400 mt-0.5">{{ formatBytes(att.size) }}</p>
        </div>

        <!-- Delete -->
        <button
          type="button"
          @click="confirmDelete(att)"
          :disabled="deletingId === att.id"
          class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-slate-400
                 hover:text-red-500 hover:bg-red-50 transition-all duration-150 opacity-0 group-hover:opacity-100
                 disabled:opacity-30 disabled:cursor-not-allowed"
          :title="t.remove"
        >
          <svg v-if="deletingId !== att.id" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
          </svg>
          <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
          </svg>
        </button>
      </div>
    </TransitionGroup>

    <!-- Upload dropzone (hidden when at max) -->
    <div v-if="attachments.length < maxAttachments">

      <!-- Dropzone -->
      <div
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="handleDrop"
        :class="[
          'relative rounded-xl border-2 border-dashed px-6 py-8 text-center transition-all duration-200 cursor-pointer',
          dragging
            ? 'border-purple-400 bg-purple-50 scale-[1.01]'
            : 'border-slate-300 bg-slate-50 hover:border-purple-300 hover:bg-purple-50/40',
          uploading ? 'pointer-events-none' : ''
        ]"
        @click="!uploading && $refs.fileInput.click()"
      >
        <input
          ref="fileInput"
          type="file"
          class="hidden"
          :accept="acceptAttr"
          @change="handleFileChange"
        />

        <!-- Idle state -->
        <div v-if="!uploading" class="flex flex-col items-center gap-2">
          <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center mb-1">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
            </svg>
          </div>
          <p class="text-sm font-semibold text-slate-700">
            {{ t.drop_or }}
            <span class="text-purple-600 underline-offset-2 hover:underline">{{ t.browse }}</span>
          </p>
          <p class="text-xs text-slate-400">{{ t.formats }} · {{ t.max_size }}</p>
          <p class="text-xs text-slate-400">{{ attachments.length }}/{{ maxAttachments }} {{ t.attached }}</p>
        </div>

        <!-- Uploading state -->
        <div v-else class="flex flex-col items-center gap-3">
          <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-600 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
          </div>
          <p class="text-sm font-medium text-slate-600">{{ t.uploading }}</p>
          <!-- Progress bar -->
          <div class="w-full max-w-xs bg-slate-200 rounded-full h-1.5 overflow-hidden">
            <div
              class="bg-purple-500 h-full rounded-full transition-all duration-300"
              :style="{ width: uploadProgress + '%' }"
            />
          </div>
        </div>
      </div>

      <!-- Upload error -->
      <p v-if="uploadError" class="mt-2 text-xs text-red-600 flex items-center gap-1">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        {{ uploadError }}
      </p>
    </div>

    <!-- Max reached notice -->
    <p v-else class="text-xs text-slate-400 text-center py-2">
      {{ t.max_reached }}
    </p>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  newsletter:   { type: Object, required: true },
  organisation: { type: Object, required: true },
  // attachments passed as reactive prop so parent can update after upload/delete
  modelValue:   { type: Array, default: () => [] },
})
const emit = defineEmits(['update:modelValue'])

const { locale } = useI18n()

const translations = {
  en: {
    drop_or: 'Drop file here or', browse: 'browse',
    formats: 'PDF, JPG, PNG, DOC, DOCX, XLS, XLSX',
    max_size: 'up to 10 MB', attached: 'attached',
    uploading: 'Uploading…', remove: 'Remove attachment',
    max_reached: 'Maximum 3 attachments reached.',
    error_size: 'File exceeds 10 MB limit.',
    error_type: 'File type not supported.',
    error_upload: 'Upload failed. Please try again.',
  },
  de: {
    drop_or: 'Datei hier ablegen oder', browse: 'durchsuchen',
    formats: 'PDF, JPG, PNG, DOC, DOCX, XLS, XLSX',
    max_size: 'bis zu 10 MB', attached: 'angehängt',
    uploading: 'Hochladen…', remove: 'Anhang entfernen',
    max_reached: 'Maximal 3 Anhänge erreicht.',
    error_size: 'Datei überschreitet 10-MB-Limit.',
    error_type: 'Dateityp wird nicht unterstützt.',
    error_upload: 'Upload fehlgeschlagen. Bitte erneut versuchen.',
  },
  np: {
    drop_or: 'फाइल यहाँ राख्नुहोस् वा', browse: 'खोज्नुहोस्',
    formats: 'PDF, JPG, PNG, DOC, DOCX, XLS, XLSX',
    max_size: '१० MB सम्म', attached: 'संलग्न',
    uploading: 'अपलोड हुँदैछ…', remove: 'हटाउनुहोस्',
    max_reached: 'अधिकतम ३ संलग्नक पुगे।',
    error_size: 'फाइल १० MB भन्दा ठूलो छ।',
    error_type: 'फाइल प्रकार समर्थित छैन।',
    error_upload: 'अपलोड असफल। फेरि प्रयास गर्नुहोस्।',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

const attachments  = computed(() => props.modelValue)
const maxAttachments = 3
const acceptAttr   = '.pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx'
const allowedMimes = ['application/pdf', 'image/jpeg', 'image/png',
                      'application/msword',
                      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                      'application/vnd.ms-excel',
                      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']

const dragging       = ref(false)
const uploading      = ref(false)
const uploadProgress = ref(0)
const uploadError    = ref(null)
const deletingId     = ref(null)
const fileInput      = ref(null)

// ── file type helpers ────────────────────────────────────────────
const fileIcon = (mime) => {
  if (mime === 'application/pdf')  return PdfIcon
  if (mime?.startsWith('image/'))  return ImageIcon
  if (mime?.includes('word'))      return DocIcon
  if (mime?.includes('excel') || mime?.includes('spreadsheet')) return XlsIcon
  return FileIcon
}
const iconBg = (mime) => {
  if (mime === 'application/pdf')  return 'bg-red-100 text-red-600'
  if (mime?.startsWith('image/'))  return 'bg-green-100 text-green-600'
  if (mime?.includes('word'))      return 'bg-blue-100 text-blue-600'
  if (mime?.includes('excel') || mime?.includes('spreadsheet')) return 'bg-emerald-100 text-emerald-600'
  return 'bg-slate-100 text-slate-500'
}

const formatBytes = (bytes) => {
  if (!bytes) return '0 B'
  if (bytes < 1024)       return bytes + ' B'
  if (bytes < 1048576)    return (bytes / 1024).toFixed(1) + ' KB'
  return (bytes / 1048576).toFixed(1) + ' MB'
}

// ── upload ───────────────────────────────────────────────────────
const handleDrop = (e) => {
  dragging.value = false
  const file = e.dataTransfer.files[0]
  if (file) uploadFile(file)
}
const handleFileChange = (e) => {
  const file = e.target.files[0]
  if (file) uploadFile(file)
  e.target.value = ''
}

const uploadFile = async (file) => {
  uploadError.value = null

  if (file.size > 10 * 1024 * 1024) {
    uploadError.value = t.value.error_size
    return
  }

  uploading.value      = true
  uploadProgress.value = 0

  const formData = new FormData()
  formData.append('attachment', file)

  try {
    const responseText = await new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest()

      xhr.upload.onprogress = (ev) => {
        if (ev.lengthComputable) {
          uploadProgress.value = Math.round((ev.loaded / ev.total) * 90)
        }
      }

      xhr.onload = () => {
        uploadProgress.value = 100
        if (xhr.status >= 200 && xhr.status < 300) {
          resolve(xhr.responseText)
        } else {
          reject(new Error(xhr.responseText || String(xhr.status)))
        }
      }

      xhr.onerror = () => reject(new Error('network'))

      xhr.open('POST', route('organisations.membership.newsletters.attachments.store', [
        props.organisation.slug,
        props.newsletter.id,
      ]))
      xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content ?? '')
      xhr.setRequestHeader('Accept', 'application/json')
      xhr.send(formData)
    })

    const data = JSON.parse(responseText)
    emit('update:modelValue', [...attachments.value, data])
  } catch (err) {
    // Show server validation message if available
    try {
      const body = JSON.parse(err.message)
      uploadError.value = body?.error ?? body?.message ?? t.value.error_upload
    } catch {
      uploadError.value = t.value.error_upload
    }
  } finally {
    uploading.value      = false
    uploadProgress.value = 0
  }
}

// ── delete ───────────────────────────────────────────────────────
const confirmDelete = (att) => {
  deletingId.value = att.id
  router.delete(
    route('organisations.membership.newsletters.attachments.destroy', [
      props.organisation.slug, props.newsletter.id, att.id,
    ]),
    {
      preserveScroll: true,
      onSuccess: () => {
        emit('update:modelValue', attachments.value.filter(a => a.id !== att.id))
      },
      onFinish: () => { deletingId.value = null },
    }
  )
}

// ── inline SVG icon components ───────────────────────────────────
const PdfIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
  </svg>`,
}
const ImageIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
  </svg>`,
}
const DocIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
  </svg>`,
}
const XlsIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M3 10h18M3 14h18M10 3v18M14 3v18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
  </svg>`,
}
const FileIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
  </svg>`,
}
</script>

<style scoped>
.attachment-enter-active,
.attachment-leave-active {
  transition: all 0.25s ease;
}
.attachment-enter-from {
  opacity: 0;
  transform: translateY(-6px);
}
.attachment-leave-to {
  opacity: 0;
  transform: translateX(12px);
}
</style>
