<template>
  <ElectionLayout>
    <!-- Flash -->
    <div v-if="page.props.flash?.success" class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 text-white text-sm font-medium px-5 py-3 shadow-xl flex items-center gap-2">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ page.props.flash.success }}
    </div>

    <main class="py-10 bg-slate-50 min-h-screen">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-500">
          <a :href="route('organisations.show', organisation.slug)" class="hover:text-slate-700 transition-colors">{{ organisation.name }}</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          <a :href="route('organisations.voter-hub', organisation.slug)" class="hover:text-slate-700 transition-colors">Voter Hub</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          <span class="text-slate-900 font-medium">My Applications</span>
        </nav>

        <!-- Header -->
        <SectionCard>
          <template #header>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                  <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                </div>
                <div>
                  <h1 class="text-xl font-bold text-slate-900">My Candidacy Applications</h1>
                  <p class="text-sm text-slate-500">Track the status of your nomination submissions</p>
                </div>
              </div>
              <a :href="route('organisations.candidacy.create', organisation.slug)"
                class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Application
              </a>
            </div>
          </template>
        </SectionCard>

        <!-- Empty State -->
        <EmptyState v-if="applications.length === 0"
          title="No applications yet"
          description="Submit your first candidacy nomination for an active election."
        />

        <!-- Applications Table -->
        <div v-else class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Election</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Position</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Submitted</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Details</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="app in applications" :key="app.id"
                class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors"
              >
                <td class="px-5 py-3 text-slate-700 font-medium">{{ app.election_name }}</td>
                <td class="px-5 py-3 text-slate-600">{{ app.post_name }}</td>
                <td class="px-5 py-3 text-slate-500">{{ app.created_at }}</td>
                <td class="px-5 py-3">
                  <span :class="statusClass(app.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ app.status_label }}
                  </span>
                </td>
                <td class="px-5 py-3">
                  <button v-if="app.manifesto" @click="expanded = expanded === app.id ? null : app.id"
                    class="text-primary-600 hover:text-primary-800 text-xs font-medium transition-colors"
                  >
                    {{ expanded === app.id ? 'Hide ↑' : 'View →' }}
                  </button>
                  <span v-else class="text-slate-300 text-xs">—</span>
                </td>
              </tr>
              <!-- Expanded manifesto row -->
              <template v-for="app in applications" :key="'exp-' + app.id">
                <tr v-if="expanded === app.id" class="bg-slate-50 border-b border-slate-100">
                  <td colspan="5" class="px-5 py-4">
                    <div class="flex gap-6 items-start">
                      <!-- Photo -->
                      <div v-if="app.photo" class="flex-shrink-0">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Photo</p>
                        <img :src="'/storage/' + app.photo" alt="Candidate photo"
                          class="w-20 h-20 object-cover rounded-lg border border-slate-200 shadow-sm"
                        />
                      </div>
                      <!-- Statement -->
                      <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Election Statement</p>
                        <p v-if="app.manifesto" class="text-sm text-slate-700 whitespace-pre-line leading-relaxed">{{ app.manifesto }}</p>
                        <p v-else class="text-sm text-slate-400 italic">No statement provided.</p>
                      </div>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>

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
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import SectionCard from '@/Components/SectionCard.vue'
import EmptyState from '@/Components/EmptyState.vue'

defineProps({
  organisation: { type: Object, required: true },
  applications: { type: Array,  default: () => [] },
})

const page     = usePage()
const expanded = ref(null)

function statusClass(status) {
  if (status === 'approved') return 'bg-emerald-100 text-emerald-700'
  if (status === 'rejected') return 'bg-red-100 text-red-700'
  return 'bg-amber-100 text-amber-700'
}
</script>
