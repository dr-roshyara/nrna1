<template>
  <div class="border-l-4 border-purple-400 bg-purple-50 p-4 mb-4">
    <h4 class="font-semibold text-purple-900 mb-1">{{ label }}</h4>
    <p class="text-sm text-purple-800 mb-2">{{ description }}</p>
    <p v-if="example" class="text-xs text-purple-700 italic">{{ example }}</p>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  description: String,
  example: String,
})
</script>
