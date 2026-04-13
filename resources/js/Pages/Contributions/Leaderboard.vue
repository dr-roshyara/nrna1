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
          <h1 class="text-2xl font-bold text-slate-900 mt-2">Leaderboard</h1>
          <p class="text-slate-500 mt-1">Top contributors in {{ organisation.name }}</p>
        </div>

        <!-- Podium (top 3) -->
        <div v-if="board.length >= 3" class="grid grid-cols-3 gap-3 mb-8">
          <!-- 2nd place -->
          <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center mt-6">
            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center mx-auto mb-3">
              <span class="text-lg font-bold text-slate-600">2</span>
            </div>
            <p class="font-semibold text-slate-800 text-sm truncate">{{ board[1].display_name }}</p>
            <p class="text-lg font-bold text-slate-600 mt-1">{{ board[1].total_points }}</p>
            <p class="text-xs text-slate-400">points</p>
          </div>

          <!-- 1st place -->
          <div class="bg-gradient-to-b from-amber-50 to-white rounded-2xl border-2 border-amber-300 p-5 text-center">
            <div class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-3 ring-4 ring-amber-200">
              <span class="text-xl font-bold text-amber-600">1</span>
            </div>
            <p class="font-bold text-slate-900 text-sm truncate">{{ board[0].display_name }}</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ board[0].total_points }}</p>
            <p class="text-xs text-slate-400">points</p>
          </div>

          <!-- 3rd place -->
          <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center mt-6">
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-3">
              <span class="text-lg font-bold text-orange-600">3</span>
            </div>
            <p class="font-semibold text-slate-800 text-sm truncate">{{ board[2].display_name }}</p>
            <p class="text-lg font-bold text-orange-600 mt-1">{{ board[2].total_points }}</p>
            <p class="text-xs text-slate-400">points</p>
          </div>
        </div>

        <!-- Empty state -->
        <div v-if="!board.length"
             class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
          <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-slate-700 mb-2">No contributions yet</h2>
          <p class="text-slate-400 text-sm">Be the first to log a contribution and claim the top spot!</p>
        </div>

        <!-- Full ranking table -->
        <div v-if="board.length" class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Full Rankings</h2>
          </div>
          <div class="divide-y divide-slate-100">
            <div v-for="entry in board" :key="entry.user_id"
                 class="flex items-center px-6 py-4 hover:bg-slate-50 transition-colors">
              <!-- Rank -->
              <div class="w-10 shrink-0">
                <span v-if="entry.rank <= 3"
                      class="w-7 h-7 rounded-full inline-flex items-center justify-center text-xs font-bold text-white"
                      :class="{
                        'bg-amber-400': entry.rank === 1,
                        'bg-slate-400': entry.rank === 2,
                        'bg-orange-400': entry.rank === 3,
                      }">
                  {{ entry.rank }}
                </span>
                <span v-else class="text-sm text-slate-400 font-medium">{{ entry.rank }}</span>
              </div>

              <!-- Name -->
              <div class="flex-1 min-w-0">
                <p class="font-medium text-slate-800 truncate">{{ entry.display_name }}</p>
              </div>

              <!-- Points -->
              <div class="text-right ml-4">
                <p class="font-bold text-purple-700">{{ entry.total_points.toLocaleString() }}</p>
                <p class="text-xs text-slate-400">pts</p>
              </div>

              <!-- Bar -->
              <div class="w-24 ml-4 hidden md:block">
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                  <div class="bg-purple-500 h-1.5 rounded-full transition-all"
                       :style="{ width: barWidth(entry.total_points) + '%' }"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Privacy note -->
        <p class="text-center text-xs text-slate-400 mt-6">
          Leaderboard respects privacy settings. Contributors with "private" visibility are excluded.
          "Anonymous" contributors appear as "Contributor #N".
        </p>

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
  board: { type: Array, default: () => [] },
})

const maxPoints = computed(() => {
  if (!props.board.length) return 1
  return props.board[0].total_points || 1
})

const barWidth = (points) => {
  return Math.max(2, (points / maxPoints.value) * 100)
}
</script>
