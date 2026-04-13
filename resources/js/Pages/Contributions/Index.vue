<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-12 px-4">
      <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <Link :href="route('organisations.show', organisation.slug)"
                  class="text-purple-600 hover:text-purple-700 text-sm mb-2 inline-flex items-center gap-1">
              &larr; Back to {{ organisation.name }}
            </Link>
            <h1 class="text-2xl font-bold text-slate-900 mt-2">My Contributions</h1>
            <p class="text-slate-500 mt-1">Track your impact and points</p>
          </div>
          <Link :href="route('organisations.contributions.create', organisation.slug)"
                class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition-colors">
            + Log Contribution
          </Link>
        </div>

        <!-- Weekly Points Summary -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-slate-500">Weekly Micro-track Points</p>
              <p class="text-2xl font-bold text-slate-900 mt-1">{{ weeklyPoints }} <span class="text-sm font-normal text-slate-400">/ {{ weeklyCap }}</span></p>
            </div>
            <div class="text-right">
              <p class="text-sm text-slate-500">Remaining</p>
              <p class="text-2xl font-bold" :class="weeklyRemaining > 0 ? 'text-green-600' : 'text-red-500'">
                {{ weeklyRemaining }}
              </p>
            </div>
          </div>
          <div class="mt-3 w-full bg-slate-100 rounded-full h-2">
            <div class="bg-purple-600 h-2 rounded-full transition-all"
                 :style="{ width: Math.min(100, (weeklyPoints / weeklyCap) * 100) + '%' }"></div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="flex gap-3 mb-6">
          <Link :href="route('organisations.leaderboard', organisation.slug)"
                class="text-sm text-purple-600 hover:text-purple-700 font-medium">
            View Leaderboard &rarr;
          </Link>
        </div>

        <!-- Empty state -->
        <div v-if="!contributions.data.length"
             class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
          <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-slate-700 mb-2">No contributions yet</h2>
          <p class="text-slate-400 text-sm mb-6">Log your first contribution to start earning points.</p>
          <Link :href="route('organisations.contributions.create', organisation.slug)"
                class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition-colors">
            + Log Contribution
          </Link>
        </div>

        <!-- Contributions list -->
        <div v-else class="space-y-3">
          <Link v-for="c in contributions.data" :key="c.id"
                :href="route('organisations.contributions.show', [organisation.slug, c.id])"
                class="block bg-white rounded-2xl border border-slate-200 p-5 hover:border-purple-300 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                        :class="trackBadgeClass(c.track)">
                    {{ c.track }}
                  </span>
                  <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                        :class="statusBadgeClass(c.status)">
                    {{ c.status }}
                  </span>
                </div>
                <h3 class="font-semibold text-slate-900 truncate">{{ c.title }}</h3>
                <p class="text-xs text-slate-400 mt-1">
                  {{ c.effort_units }} hrs &middot; {{ c.proof_type.replace('_', ' ') }}
                  <span v-if="c.is_recurring"> &middot; recurring</span>
                </p>
              </div>
              <div class="text-right ml-4 shrink-0">
                <p class="text-lg font-bold" :class="c.calculated_points > 0 ? 'text-purple-700' : 'text-slate-400'">
                  {{ c.calculated_points ?? '---' }}
                </p>
                <p class="text-xs text-slate-400">pts</p>
              </div>
            </div>
            <p class="text-xs text-slate-400 mt-2">{{ formatDate(c.created_at) }}</p>
          </Link>
        </div>

        <!-- Pagination -->
        <div v-if="contributions.links && contributions.links.length > 3" class="flex justify-center gap-1 mt-8">
          <template v-for="link in contributions.links" :key="link.label">
            <Link v-if="link.url"
                  :href="link.url"
                  class="px-3 py-1.5 rounded-lg text-sm transition-colors"
                  :class="link.active ? 'bg-purple-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                  v-html="link.label" />
            <span v-else
                  class="px-3 py-1.5 rounded-lg text-sm text-slate-300"
                  v-html="link.label" />
          </template>
        </div>

      </div>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  contributions: { type: Object, required: true },
  weeklyPoints: { type: Number, default: 0 },
  weeklyCap: { type: Number, default: 100 },
})

const weeklyRemaining = computed(() => Math.max(0, props.weeklyCap - props.weeklyPoints))

const trackBadgeClass = (track) => ({
  micro:    'bg-blue-100 text-blue-700',
  standard: 'bg-amber-100 text-amber-700',
  major:    'bg-purple-100 text-purple-700',
}[track] || 'bg-slate-100 text-slate-700')

const statusBadgeClass = (status) => ({
  draft:     'bg-slate-100 text-slate-600',
  pending:   'bg-yellow-100 text-yellow-700',
  verified:  'bg-blue-100 text-blue-700',
  approved:  'bg-green-100 text-green-700',
  completed: 'bg-green-100 text-green-700',
  rejected:  'bg-red-100 text-red-700',
  appealed:  'bg-orange-100 text-orange-700',
}[status] || 'bg-slate-100 text-slate-600')

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>
