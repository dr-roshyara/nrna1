<template>
  <ElectionLayout>
    <!-- Flash Messages -->
    <div v-if="page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 rounded-lg mx-4 mt-4 px-5 py-4 flex items-center gap-3">
      <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <p class="text-sm font-medium text-emerald-800">{{ page.props.flash.success }}</p>
    </div>

    <div v-if="page.props.flash?.error" class="bg-danger-50 border border-danger-200 rounded-lg mx-4 mt-4 px-5 py-4 flex items-center gap-3">
      <svg class="w-5 h-5 text-danger-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <p class="text-sm font-medium text-danger-800">{{ page.props.flash.error }}</p>
    </div>

    <div class="container mx-auto py-8 px-4">
      <div class="mb-6">
        <Link
          :href="route('elections.management', election.slug)"
          class="text-primary-600 hover:text-primary-800 inline-flex items-center gap-1 transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to Management
        </Link>
        <h1 class="text-2xl font-bold text-slate-800 mt-4">Election Timeline Settings</h1>
        <p class="text-slate-500">Configure dates for each phase of the election lifecycle</p>
      </div>

      <ElectionTimelineSettings
        :election="election"
        :organisation="organisation"
        @form-changed="formChanged = true"
        @save-success="formChanged = false"
      />
    </div>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import ElectionTimelineSettings from './Partials/ElectionTimelineSettings.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const page = usePage()

const props = defineProps({
  election: Object,
  organisation: Object,
})

const formChanged = ref(false)

// Warn before leaving if unsaved changes
if (typeof window !== 'undefined') {
  window.addEventListener('beforeunload', (e) => {
    if (formChanged.value) {
      e.preventDefault()
      e.returnValue = 'You have unsaved changes. Leave anyway?'
    }
  })
}
</script>

