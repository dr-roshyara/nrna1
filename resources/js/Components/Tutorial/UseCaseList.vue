<template>
  <ul class="space-y-2 bg-slate-50 rounded-lg p-4">
    <li v-for="(item, index) in items" :key="index" class="flex gap-2 text-sm text-slate-700">
      <span class="text-purple-600 font-bold">•</span>
      <span>{{ item }}</span>
    </li>
  </ul>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    required: true,
  },
})
</script>
