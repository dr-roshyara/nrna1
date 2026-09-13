<script setup>
import { ref, computed, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'

// Committee-only candidate photo edit — see
// app/Http/Controllers/Election/CandidacyPhotoController.php. This component
// never persists crop coordinates/zoom as domain state: the cropper's own
// getResult().canvas is flattened to a single image blob at save time, and
// that flattened image is the only thing sent to the server. The backend is
// the authoritative security boundary (role + lifecycle) — this component
// only ever mutates state through the existing PATCH endpoint it's given.
const props = defineProps({
  photoUrl: { type: String, default: null },
  updateUrl: { type: String, required: true },
  candidateName: { type: String, default: '' },
})

const emit = defineEmits(['close', 'updated'])

const cropperRef = ref(null)
const selectedFileUrl = ref(null)
const saving = ref(false)
const errorMessage = ref(null)

const currentSrc = computed(() => selectedFileUrl.value || props.photoUrl)

function revokeSelectedFileUrl() {
  if (selectedFileUrl.value) {
    URL.revokeObjectURL(selectedFileUrl.value)
    selectedFileUrl.value = null
  }
}

function onFileChange(event) {
  const file = event.target.files && event.target.files[0]
  if (!file) return

  errorMessage.value = null
  revokeSelectedFileUrl()
  selectedFileUrl.value = URL.createObjectURL(file)
}

function cancel() {
  revokeSelectedFileUrl()
  errorMessage.value = null
  emit('close')
}

function save() {
  if (!currentSrc.value || !cropperRef.value || saving.value) return

  const result = cropperRef.value.getResult()
  if (!result || !result.canvas) return

  result.canvas.toBlob(function (blob) {
    if (!blob) return

    const formData = new FormData()
    formData.append('_method', 'patch')
    formData.append('photo', blob, 'photo.jpg')

    saving.value = true
    errorMessage.value = null

    router.post(props.updateUrl, formData, {
      forceFormData: true,
      preserveScroll: true,
      onSuccess: function () {
        revokeSelectedFileUrl()
        emit('updated')
      },
      onError: function (errors) {
        errorMessage.value = errors.photo || 'Could not update the photo. Please try again.'
      },
      onFinish: function () {
        saving.value = false
      },
    })
  }, 'image/jpeg', 0.9)
}

onBeforeUnmount(function () {
  revokeSelectedFileUrl()
})
</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-900/50 p-4" @click.self="cancel">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
      <h2 class="text-lg font-bold text-neutral-900 mb-1">Edit Photo</h2>
      <p class="text-sm text-neutral-500 mb-4">{{ candidateName }}</p>

      <div v-if="currentSrc" class="mb-4 rounded-xl overflow-hidden border border-neutral-200" style="height: 320px;">
        <Cropper
          ref="cropperRef"
          :src="currentSrc"
          :stencil-props="{ aspectRatio: 1 }"
          class="w-full h-full"
        />
      </div>
      <div v-else class="mb-4 rounded-xl border-2 border-dashed border-neutral-200 h-40 flex items-center justify-center text-sm text-neutral-500">
        No photo yet, choose one below
      </div>

      <label class="block mb-4">
        <span class="text-sm font-medium text-neutral-700">
          <template v-if="currentSrc">Choose a different photo</template>
          <template v-else>Choose a photo</template>
        </span>
        <input
          type="file"
          accept="image/*"
          class="mt-1 block w-full text-sm text-neutral-600"
          @change="onFileChange"
        />
      </label>

      <p v-if="errorMessage" class="text-sm text-danger-700 bg-danger-50 border border-danger-200 rounded-lg px-3 py-2 mb-4">
        {{ errorMessage }}
      </p>

      <div class="flex justify-end gap-3">
        <button
          type="button"
          data-testid="cancel-button"
          class="px-4 py-2 rounded-lg border border-neutral-200 text-sm font-medium text-neutral-600 hover:bg-neutral-50"
          @click="cancel"
        >
          Cancel
        </button>
        <button
          type="button"
          data-testid="save-button"
          :disabled="!currentSrc || saving"
          class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed"
          @click="save"
        >
          <template v-if="saving">Saving...</template>
          <template v-else>Save</template>
        </button>
      </div>
    </div>
  </div>
</template>
