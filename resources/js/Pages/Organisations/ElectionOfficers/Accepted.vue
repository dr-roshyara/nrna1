<template>
  <div class="min-h-screen bg-neutral-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <div class="mx-auto h-12 w-12 text-green-500 mb-4">
          <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-neutral-900">Appointment Accepted!</h2>
        <p class="mt-2 text-sm text-neutral-600">
          You are now an election officer for <strong>{{ officer.organisation.name }}</strong>
        </p>
      </div>

      <div class="mt-8 bg-white shadow sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-neutral-900 capitalize">Role: {{ officer.role }}</h3>

        <div class="mt-6">
          <Link
            :href="route('organisations.show', officer.organisation.slug)"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 transition-colors"
          >
            Go to Organisation Dashboard
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  officer: Object,
  message: String,
})
</script>

