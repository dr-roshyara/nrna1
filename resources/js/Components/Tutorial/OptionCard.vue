<template>
  <div class="border border-slate-200 rounded-lg p-5 mb-4 hover:shadow-md transition-shadow">
    <h5 class="font-semibold text-slate-900 mb-2">{{ title }}</h5>
    <p class="text-sm text-slate-700">{{ description }}</p>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  description: String,
})
</script>
