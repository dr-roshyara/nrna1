<template>
  <div class="min-h-screen bg-neutral-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
      <div class="mx-auto h-12 w-12 text-yellow-500 mb-4">
        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
      </div>
      <h2 class="text-3xl font-extrabold text-neutral-900">{{ title }}</h2>
      <p class="mt-2 text-sm text-neutral-600">{{ message }}</p>
      <div class="mt-6">
        <Link href="/" class="text-amber-600 hover:underline text-sm">Go to Home</Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  title: String,
  message: String,
  status: Number,
})
</script>

