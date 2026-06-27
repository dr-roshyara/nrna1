<template>
  <div class="border-l-4 border-slate-300 bg-slate-50 p-4">
    <h5 class="font-semibold text-slate-900 mb-2">{{ question }}</h5>
    <p class="text-slate-700 text-sm">{{ answer }}</p>
  </div>
</template>

<script setup>
defineProps({
  question: String,
  answer: String,
})
</script>
