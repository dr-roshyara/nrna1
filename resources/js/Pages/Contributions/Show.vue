<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-12 px-4">
      <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="mb-8">
          <Link :href="route('organisations.contributions.index', organisation.slug)"
                class="text-purple-600 hover:text-purple-700 text-sm mb-2 inline-flex items-center gap-1">
            &larr; Back to My Contributions
          </Link>
          <h1 class="text-2xl font-bold text-slate-900 mt-2">{{ contribution.title }}</h1>
          <div class="flex items-center gap-2 mt-2">
            <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                  :class="trackBadgeClass(contribution.track)">
              {{ contribution.track }}
            </span>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                  :class="statusBadgeClass(contribution.status)">
              {{ contribution.status }}
            </span>
            <span class="text-xs text-slate-400">{{ formatDate(contribution.created_at) }}</span>
          </div>
        </div>

        <!-- Points Card -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl border border-purple-200 p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-purple-600 font-medium">Points Awarded</p>
              <p class="text-4xl font-bold text-purple-700 mt-1">{{ contribution.calculated_points ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-purple-100 flex items-center justify-center">
              <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- Details Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6 space-y-5">
          <h2 class="font-semibold text-slate-800 text-lg">Details</h2>

          <div>
            <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Description</p>
            <p class="text-slate-700 whitespace-pre-line">{{ contribution.description }}</p>
          </div>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
              <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Effort</p>
              <p class="text-slate-900 font-medium">{{ contribution.effort_units }} hrs</p>
            </div>
            <div>
              <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Proof Type</p>
              <p class="text-slate-900 font-medium">{{ formatProofType(contribution.proof_type) }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Recurring</p>
              <p class="text-slate-900 font-medium">{{ contribution.is_recurring ? 'Yes' : 'No' }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Outcome Bonus</p>
              <p class="text-slate-900 font-medium">{{ contribution.outcome_bonus }} pts</p>
            </div>
          </div>

          <!-- Team Skills -->
          <div v-if="contribution.team_skills && contribution.team_skills.length">
            <p class="text-xs text-slate-400 uppercase tracking-wide mb-2">Team Skills</p>
            <div class="flex flex-wrap gap-2">
              <span v-for="skill in contribution.team_skills" :key="skill"
                    class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-medium">
                {{ skill }}
              </span>
            </div>
          </div>
        </div>

        <!-- Verification / Approval Info -->
        <div v-if="contribution.verified_at || contribution.approved_at"
             class="bg-white rounded-2xl border border-slate-200 p-6 mb-6 space-y-4">
          <h2 class="font-semibold text-slate-800 text-lg">Review History</h2>

          <div v-if="contribution.verified_at" class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-slate-800">Verified</p>
              <p class="text-xs text-slate-400">{{ formatDate(contribution.verified_at) }}</p>
              <p v-if="contribution.verifier_notes" class="text-sm text-slate-600 mt-1">{{ contribution.verifier_notes }}</p>
            </div>
          </div>

          <div v-if="contribution.approved_at" class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-slate-800">Approved</p>
              <p class="text-xs text-slate-400">{{ formatDate(contribution.approved_at) }}</p>
            </div>
          </div>
        </div>

        <!-- Points Ledger -->
        <div v-if="contribution.ledger_entries && contribution.ledger_entries.length"
             class="bg-white rounded-2xl border border-slate-200 p-6 mb-6">
          <h2 class="font-semibold text-slate-800 text-lg mb-4">Points Ledger</h2>
          <div class="divide-y divide-slate-100">
            <div v-for="entry in contribution.ledger_entries" :key="entry.id"
                 class="flex items-center justify-between py-3">
              <div>
                <p class="text-sm font-medium text-slate-800">{{ entry.action }}</p>
                <p v-if="entry.reason" class="text-xs text-slate-400">{{ entry.reason }}</p>
                <p class="text-xs text-slate-400">{{ formatDate(entry.created_at) }}</p>
              </div>
              <p class="font-bold text-lg"
                 :class="entry.points >= 0 ? 'text-green-600' : 'text-danger-600'">
                {{ entry.points >= 0 ? '+' : '' }}{{ entry.points }}
              </p>
            </div>
          </div>
        </div>

        <!-- Status Message -->
        <div v-if="contribution.status === 'pending'"
             class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 text-center">
          <p class="text-sm text-yellow-700">This contribution is awaiting admin review.</p>
        </div>
        <div v-else-if="contribution.status === 'rejected'"
             class="bg-danger-50 border border-danger-200 rounded-2xl p-4 text-center">
          <p class="text-sm text-danger-700">This contribution was rejected.</p>
          <p v-if="contribution.verifier_notes" class="text-xs text-danger-500 mt-1">{{ contribution.verifier_notes }}</p>
        </div>

      </div>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

defineProps({
  organisation: { type: Object, required: true },
  contribution: { type: Object, required: true },
})

const trackBadgeClass = (track) => ({
  micro:    'bg-primary-100 text-primary-700',
  standard: 'bg-amber-100 text-amber-700',
  major:    'bg-purple-100 text-purple-700',
}[track] || 'bg-slate-100 text-slate-700')

const statusBadgeClass = (status) => ({
  draft:     'bg-slate-100 text-slate-600',
  pending:   'bg-yellow-100 text-yellow-700',
  verified:  'bg-primary-100 text-primary-700',
  approved:  'bg-green-100 text-green-700',
  completed: 'bg-green-100 text-green-700',
  rejected:  'bg-danger-100 text-danger-700',
  appealed:  'bg-orange-100 text-orange-700',
}[status] || 'bg-slate-100 text-slate-600')

const formatProofType = (type) => {
  if (!type) return ''
  return type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
}

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>

