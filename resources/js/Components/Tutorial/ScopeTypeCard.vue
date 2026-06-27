<template>
  <div class="border border-slate-300 rounded-lg p-6 mb-6 bg-gradient-to-br from-slate-50 to-white">
    <h5 class="font-bold text-lg text-slate-900 mb-2">{{ title }}</h5>
    <p class="text-slate-700 mb-3">{{ description }}</p>

    <div class="space-y-3 mb-4">
      <div v-if="useCase" class="text-sm">
        <span class="font-medium text-slate-900">Use Case: </span>
        <span class="text-slate-700">{{ useCase }}</span>
      </div>
      <div v-if="example" class="text-sm">
        <span class="font-medium text-slate-900">Example: </span>
        <span class="text-slate-700">{{ example }}</span>
      </div>
      <div v-if="result" class="text-sm bg-blue-50 p-2 rounded border border-blue-200">
        <span class="font-medium text-blue-900">Result: </span>
        <span class="text-blue-800">{{ result }}</span>
      </div>
    </div>

    <slot />
  </div>
</template>

<script setup>
defineProps({
  title: String,
  description: String,
  useCase: String,
  example: String,
  result: String,
})
</script>
