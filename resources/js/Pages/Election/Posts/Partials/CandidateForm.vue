<template>
  <form @submit.prevent="$emit('submit')" class="space-y-4">

    <!-- Server validation errors -->
    <div v-if="Object.keys(errors).length"
         class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-800 space-y-1">
      <p v-for="(msg, field) in errors" :key="field">{{ Array.isArray(msg) ? msg[0] : msg }}</p>
    </div>

    <!-- Name + User ID -->
    <div class="grid grid-cols-2 gap-3">
      <div>
        <label class="block text-xs font-medium text-neutral-700 mb-1">
          Candidate Name <span v-if="!form.user_id" class="text-red-500">*</span>
        </label>
        <input v-model="form.name" type="text" :required="!form.user_id"
               maxlength="255" placeholder="Full name"
               :class="inputClass('name')" />
        <p v-if="errors.name" class="text-xs text-red-600 mt-1">{{ firstError('name') }}</p>
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-700 mb-1">User ID (optional)</label>
        <input v-model="form.user_id" type="text" placeholder="Link to a registered user"
               :class="inputClass('user_id')" />
        <p v-if="errors.user_id" class="text-xs text-red-600 mt-1">{{ firstError('user_id') }}</p>
      </div>
    </div>

    <!-- Bio + Status + Position Order (edit mode) -->
    <div :class="editMode ? 'grid grid-cols-2 gap-3' : ''">
      <div>
        <label class="block text-xs font-medium text-neutral-700 mb-1">Bio / Statement</label>
        <textarea v-model="form.description" rows="3" maxlength="2000"
                  placeholder="Short biography or election statement"
                  :class="inputClass('description')" />
        <p v-if="errors.description" class="text-xs text-red-600 mt-1">{{ firstError('description') }}</p>
      </div>
      <div v-if="editMode" class="space-y-3">
        <div>
          <label class="block text-xs font-medium text-neutral-700 mb-1">Status</label>
          <select v-model="form.status" :class="inputClass('status')">
            <option value="approved">Approved</option>
            <option value="pending">Pending</option>
            <option value="rejected">Rejected</option>
            <option value="withdrawn">Withdrawn</option>
          </select>
          <p v-if="errors.status" class="text-xs text-red-600 mt-1">{{ firstError('status') }}</p>
        </div>
        <div>
          <label class="block text-xs font-medium text-neutral-700 mb-1">Display Order</label>
          <input v-model.number="form.position_order" type="number" min="0" max="999" step="1"
                 :class="inputClass('position_order')" />
          <p v-if="errors.position_order" class="text-xs text-red-600 mt-1">{{ firstError('position_order') }}</p>
        </div>
      </div>
    </div>

    <!-- Photo uploads -->
    <div>
      <label class="block text-xs font-medium text-neutral-700 mb-2">
        Candidate Photos
        <span class="text-neutral-400 font-normal ml-1">(up to 3 images, max 2 MB each — JPG, PNG, WEBP)</span>
      </label>
      <div class="grid grid-cols-3 gap-3">
        <div v-for="(label, i) in ['Main Photo', 'Photo 2', 'Photo 3']" :key="i">
          <!-- Existing image preview (edit mode) -->
          <div v-if="editMode && existingImages[i]" class="mb-1">
            <img :src="`/storage/${existingImages[i]}`"
                 :alt="`Current ${label} for ${form.name || 'candidate'}`"
                 class="w-full h-20 object-cover rounded-lg border border-neutral-200" />
            <p class="text-xs text-neutral-400 mt-0.5 text-center">Current</p>
          </div>
          <p class="text-xs text-neutral-500 mb-1">{{ label }}</p>
          <input
            type="file"
            accept="image/jpeg,image/png,image/webp,image/jpg"
            @change="e => handleImage(e, i)"
            class="w-full text-xs text-neutral-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer"
          />
          <p v-if="fileSizeErrors[i]" class="text-xs text-red-600 mt-1">{{ fileSizeErrors[i] }}</p>
          <p v-if="errors[`image_${i + 1}`]" class="text-xs text-red-600 mt-1">
            {{ firstError(`image_${i + 1}`) }}
          </p>
          <!-- New file preview -->
          <div v-if="previews[i]" class="mt-1">
            <img :src="previews[i]" :alt="`New ${label} preview`"
                 class="w-full h-20 object-cover rounded-lg border border-primary-200" />
          </div>
        </div>
      </div>
    </div>

    <div class="flex gap-2 pt-2">
      <Button type="submit" variant="primary" size="sm" :loading="isSubmitting" :disabled="isSubmitting || hasFileSizeErrors">
        {{ editMode ? 'Save Changes' : 'Add Candidate' }}
      </Button>
      <Button type="button" variant="outline" size="sm" :disabled="isSubmitting" @click="$emit('cancel')">
        Cancel
      </Button>
    </div>

  </form>
</template>

<script setup>
import { ref, computed } from 'vue'
import Button from '@/Components/Button.vue'

const MAX_FILE_SIZE = 2 * 1024 * 1024 // 2 MB

const props = defineProps({
  form:           { type: Object,  required: true },
  editMode:       { type: Boolean, default: false },
  isSubmitting:   { type: Boolean, default: false },
  errors:         { type: Object,  default: () => ({}) },
  existingImages: { type: Array,   default: () => [null, null, null] },
})
defineEmits(['submit', 'cancel'])

const previews      = ref([null, null, null])
const fileSizeErrors = ref([null, null, null])

const hasFileSizeErrors = computed(() => fileSizeErrors.value.some(e => e !== null))

function handleImage(event, index) {
  const file = event.target.files[0]
  fileSizeErrors.value[index] = null

  if (!file) {
    props.form[`image_${index + 1}`] = null
    previews.value[index] = null
    return
  }

  if (file.size > MAX_FILE_SIZE) {
    fileSizeErrors.value[index] = `File exceeds 2 MB limit (${(file.size / 1024 / 1024).toFixed(1)} MB)`
    event.target.value = ''
    props.form[`image_${index + 1}`] = null
    previews.value[index] = null
    return
  }

  props.form[`image_${index + 1}`] = file
  const reader = new FileReader()
  reader.onload = e => { previews.value[index] = e.target.result }
  reader.readAsDataURL(file)
}

function inputClass(field) {
  return [
    'w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
    props.errors[field] ? 'border-red-500' : 'border-neutral-300',
  ]
}

function firstError(field) {
  const e = props.errors[field]
  return Array.isArray(e) ? e[0] : e
}
</script>
