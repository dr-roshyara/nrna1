<template>
  <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg p-4 mb-3 border-l-4 border-blue-400">
    <h6 class="font-semibold text-slate-900 mb-1">{{ level }}</h6>
    <p class="text-sm text-slate-700">{{ description }}</p>
  </div>
</template>

<script setup>
defineProps({
  level: String,
  description: String,
})
</script>
