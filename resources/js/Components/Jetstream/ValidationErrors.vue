<template>
  <div v-if="Object.keys(errors).length > 0" class="mb-4">
    <div class="px-4 py-2 bg-red-50 border border-red-200 rounded-md">
      <div v-for="(messages, field) in errors" :key="field" class="text-red-700 text-sm">
        <div v-if="Array.isArray(messages)">
          <div v-for="(message, index) in messages" :key="index">{{ message }}</div>
        </div>
        <div v-else>{{ messages }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'JetValidationErrors',
  props: {
    errors: {
      type: Object,
      default: () => ({}),
    },
  },
}
</script>
