<template>
  <div class="space-y-2">
    <!-- Label -->
    <label
      v-if="label"
      :for="id"
      class="block text-sm font-semibold text-gray-900 dark:text-white"
    >
      {{ label }}
      <span v-if="required" class="text-red-600" aria-label="required">*</span>
    </label>

    <!-- Input field -->
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :aria-invalid="!!error"
      :aria-describedby="error ? `${id}-error` : undefined"
      @input="$emit('update:modelValue', $event.target.value)"
      class="w-full px-4 py-2.5 rounded-lg border-2 bg-white dark:bg-gray-800
             border-gray-300 dark:border-gray-600
             text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400
             focus:outline-hidden focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
             dark:focus:border-blue-400 dark:focus:ring-blue-400/20
             transition-all duration-200
             disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-50
             aria-invalid:border-red-500 dark:aria-invalid:border-red-400"
    />

    <!-- Helper text -->
    <p v-if="helper && !error" class="text-xs text-gray-600 dark:text-gray-400">
      ℹ {{ helper }}
    </p>

    <!-- Error message -->
    <p
      v-if="error"
      :id="`${id}-error`"
      class="text-xs text-red-600 dark:text-red-400 font-medium"
    >
      ⚠️ {{ error }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    required: true,
  },
  label: String,
  type: {
    type: String,
    default: 'text',
  },
  modelValue: {
    type: String,
    required: true,
  },
  placeholder: String,
  helper: String,
  error: String,
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['update:modelValue']);
</script>

<style scoped>
/* High contrast mode support */
@media (prefers-contrast: more) {
  input {
    border-width: 2px;
  }

  input:focus {
    outline: 2px solid;
    outline-offset: 2px;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  input {
    transition: none !important;
  }
}
</style>
