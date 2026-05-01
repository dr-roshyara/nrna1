<template>
  <form @submit.prevent="$emit('submit')" class="space-y-4">

    <!-- Server validation errors summary -->
    <div v-if="Object.keys(errors).length"
         class="bg-danger-50 border border-danger-200 rounded-lg px-4 py-3 text-sm text-danger-800 space-y-1">
      <p v-for="(msg, field) in errors" :key="field">{{ Array.isArray(msg) ? msg[0] : msg }}</p>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">
          Position Name <span class="text-danger-500">*</span>
        </label>
        <input v-model="form.name" type="text" required maxlength="255"
               :class="inputClass('name')" />
        <p v-if="errors.name" class="text-xs text-danger-600 mt-1">{{ firstError('name') }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">Nepali Name</label>
        <input v-model="form.nepali_name" type="text" maxlength="255"
               :class="inputClass('nepali_name')" />
        <p v-if="errors.nepali_name" class="text-xs text-danger-600 mt-1">{{ firstError('nepali_name') }}</p>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">Scope</label>
        <select v-model="form.is_national_wide" :class="inputClass('is_national_wide')">
          <option :value="true">National</option>
          <option :value="false">Regional</option>
        </select>
      </div>
      <div v-if="!form.is_national_wide">
        <label class="block text-sm font-medium text-neutral-700 mb-1">
          State / Region <span class="text-danger-500">*</span>
        </label>
        <input v-model="form.state_name" type="text"
               :required="!form.is_national_wide" maxlength="255"
               :class="inputClass('state_name')" />
        <p v-if="errors.state_name" class="text-xs text-danger-600 mt-1">{{ firstError('state_name') }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">
          Seats <span class="text-danger-500">*</span>
        </label>
        <input v-model.number="form.required_number" type="number" min="1" max="50" step="1" required
               :class="inputClass('required_number')" />
        <p v-if="errors.required_number" class="text-xs text-danger-600 mt-1">{{ firstError('required_number') }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">Display Order</label>
        <input v-model.number="form.position_order" type="number" min="0" max="999" step="1"
               :class="inputClass('position_order')" />
        <p v-if="errors.position_order" class="text-xs text-danger-600 mt-1">{{ firstError('position_order') }}</p>
      </div>
    </div>

    <div class="flex gap-2">
      <Button type="submit" variant="primary" size="sm" :loading="isSubmitting" :disabled="isSubmitting">
        {{ editMode ? 'Save Changes' : 'Add Position' }}
      </Button>
      <Button type="button" variant="outline" size="sm" :disabled="isSubmitting" @click="$emit('cancel')">
        Cancel
      </Button>
    </div>

  </form>
</template>

<script setup>
import Button from '@/Components/Button.vue'

const props = defineProps({
  form:         { type: Object,  required: true },
  editMode:     { type: Boolean, default: false },
  isSubmitting: { type: Boolean, default: false },
  errors:       { type: Object,  default: () => ({}) },
})
defineEmits(['submit', 'cancel'])

function inputClass(field) {
  return [
    'w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
    props.errors[field] ? 'border-danger-500' : 'border-neutral-300',
  ]
}

function firstError(field) {
  const e = props.errors[field]
  return Array.isArray(e) ? e[0] : e
}
</script>

