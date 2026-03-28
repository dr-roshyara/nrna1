<template>
  <ElectionLayout>
    <!-- Flash Messages -->
    <div v-if="page.props.flash?.success" class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 text-white text-sm font-medium px-5 py-3 shadow-xl flex items-center gap-2">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ page.props.flash.success }}
    </div>
    <div v-if="page.props.flash?.error" class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-red-600 text-white text-sm font-medium px-5 py-3 shadow-xl flex items-center gap-2">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      {{ page.props.flash.error }}
    </div>

    <main class="py-10 bg-slate-50 min-h-screen">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-500">
          <a :href="route('organisations.show', organisation.slug)" class="hover:text-slate-700 transition-colors">{{ organisation.name }}</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          <a :href="route('organisations.voter-hub', organisation.slug)" class="hover:text-slate-700 transition-colors">Voter Hub</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          <span class="text-slate-900 font-medium">Apply for Candidacy</span>
        </nav>

        <!-- No Active Elections -->
        <EmptyState v-if="activeElections.length === 0"
          title="No active elections"
          description="There are no active elections accepting candidacy applications right now."
        />

        <!-- Form -->
        <CandidacyApplicationForm
          v-else
          :organisation="organisation"
          :active-elections="activeElections"
          :applied-election-ids="appliedElectionIds"
        />

        <!-- Back Link -->
        <div class="text-center pt-2">
          <a :href="route('organisations.voter-hub', organisation.slug)" class="text-sm text-slate-500 hover:text-slate-700 transition-colors">
            ← Back to Voter Hub
          </a>
        </div>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { usePage } from '@inertiajs/vue3'
import EmptyState from '@/Components/EmptyState.vue'
import CandidacyApplicationForm from '@/Pages/Organisations/Partials/CandidacyApplicationForm.vue'

defineProps({
  organisation:       { type: Object, required: true },
  activeElections:    { type: Array,  default: () => [] },
  appliedElectionIds: { type: Array,  default: () => [] },
})

const page = usePage()
</script>
