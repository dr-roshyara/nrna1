<template>
  <section :id="id" class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 mb-8">
    <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ title }}</h3>
    <p class="text-slate-700 mb-6">{{ intro }}</p>
    <div class="space-y-4">
      <slot />
    </div>
  </section>
</template>

<script setup>
defineProps({
  id: String,
  title: String,
  intro: String,
})
</script>
