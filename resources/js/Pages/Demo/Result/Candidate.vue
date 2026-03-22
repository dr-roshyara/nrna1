<template>
  <article
    class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden"
    :aria-labelledby="`post-title-${post.post_id}`"
  >
    <!-- Header with gradient -->
    <header class="bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-700 dark:to-purple-800 px-4 sm:px-6 py-4">
      <h2
        :id="`post-title-${post.post_id}`"
        class="text-lg sm:text-2xl lg:text-3xl font-bold text-white"
      >
        {{ post.name }}
      </h2>

      <div class="mt-2 flex flex-wrap gap-2 sm:gap-4 text-xs sm:text-sm text-white text-opacity-90">
        <span class="flex items-center gap-1">
          <span aria-hidden="true">📊</span>
          {{ final_result.total_votes_for_post || 0 }} {{ $t('pages.demo-result.candidate.votes') }}
        </span>
        <span v-if="final_result.no_vote_count > 0" class="flex items-center gap-1">
          <span aria-hidden="true">⊘</span>
          {{ $t('pages.demo-result.candidate.voters_abstained', { count: final_result.no_vote_count }) }}
        </span>
        <span v-if="post.state_name" class="flex items-center gap-1">
          <span aria-hidden="true">📍</span>
          {{ post.state_name }}
        </span>
      </div>
    </header>

    <!-- Results Content -->
    <div class="p-4 sm:p-6">
      <!-- Mobile: Card View -->
      <div class="block sm:hidden space-y-3">
        <div
          v-for="(candidate, index) in final_result.candidates"
          :key="candidate.candidacy_id"
          class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border-l-4 border-indigo-600"
        >
          <div class="flex justify-between items-start mb-3">
            <div>
              <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $t('pages.demo-result.candidate.rank') }} #{{ index + 1 }}</span>
              <p class="font-semibold text-gray-900 dark:text-white text-sm mt-1">{{ candidate.name }}</p>
            </div>
            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ candidate.vote_percent }}%</span>
          </div>

          <!-- Progress Bar -->
          <div class="space-y-2">
            <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
              <span>{{ candidate.vote_count }} {{ $t('pages.demo-result.candidate.votes') }}</span>
              <span>{{ final_result.total_votes_for_post }} {{ $t('pages.demo-result.stats.total_votes') }}</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 overflow-hidden">
              <div
                class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2.5 rounded-full transition-all duration-300"
                :style="{ width: `${candidate.vote_percent}%` }"
                :aria-valuenow="candidate.vote_percent"
                aria-valuemin="0"
                aria-valuemax="100"
                role="progressbar"
                :aria-label="`${candidate.name}: ${candidate.vote_percent}%`"
              />
            </div>
          </div>
        </div>

        <!-- No votes card -->
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border-l-4 border-red-400">
          <div class="flex justify-between items-start">
            <p class="font-semibold text-red-600 dark:text-red-400 text-sm">{{ $t('pages.demo-result.candidate.abstentions') }}</p>
            <span class="text-red-600 dark:text-red-400 font-bold text-sm">{{ noVotePercent }}%</span>
          </div>
          <p class="text-xs text-red-500 dark:text-red-400 mt-1">
            {{ $t('pages.demo-result.candidate.voters_abstained', { count: final_result.no_vote_count || 0 }) }}
          </p>
        </div>
      </div>

      <!-- Desktop: Table View -->
      <div class="hidden sm:block overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
            <tr>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('pages.demo-result.candidate.rank') }}
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('pages.demo-result.candidate.candidate') }}
              </th>
              <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('pages.demo-result.candidate.votes') }}
              </th>
              <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('pages.demo-result.candidate.percentage') }}
              </th>
              <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('pages.demo-result.candidate.trend') }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr
              v-for="(candidate, index) in final_result.candidates"
              :key="candidate.candidacy_id"
              class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
              <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                #{{ index + 1 }}
              </td>
              <td class="px-4 py-4 text-sm text-gray-900 dark:text-white font-medium">
                {{ candidate.name }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-600 dark:text-gray-300">
                {{ candidate.vote_count }}
              </td>
              <td class="px-4 py-4 whitespace-nowrap text-sm text-right font-bold text-indigo-600 dark:text-indigo-400">
                {{ candidate.vote_percent }}%
              </td>
              <td class="px-4 py-4 whitespace-nowrap">
                <div class="w-12 h-6 bg-gray-100 dark:bg-gray-700 rounded-sm inline-flex items-center justify-center overflow-hidden">
                  <div
                    class="bg-indigo-600 h-full"
                    :style="{ width: `${Math.min(candidate.vote_percent * 2, 100)}%` }"
                  />
                </div>
              </td>
            </tr>

            <!-- Abstentions row -->
            <tr class="bg-red-50 dark:bg-red-900/20 font-semibold">
              <td colspan="2" class="px-4 py-4 text-sm text-red-600 dark:text-red-400">
                {{ $t('pages.demo-result.candidate.abstentions') }}
              </td>
              <td class="px-4 py-4 text-right text-sm text-red-600 dark:text-red-400">
                {{ final_result.no_vote_count || 0 }}
              </td>
              <td class="px-4 py-4 text-right text-sm text-red-600 dark:text-red-400">
                {{ noVotePercent }}%
              </td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Summary Stats -->
      <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 grid grid-cols-2 sm:grid-cols-3 gap-4">
        <div class="text-center">
          <p class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ $t('pages.demo-result.candidate.total_votes') }}</p>
          <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1">
            {{ final_result.total_votes_for_post }}
          </p>
        </div>
        <div class="text-center">
          <p class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ $t('pages.demo-result.candidate.candidates') }}</p>
          <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1">
            {{ final_result.candidates.length }}
          </p>
        </div>
        <div v-if="post.required_number" class="text-center">
          <p class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ $t('pages.demo-result.candidate.to_select') }}</p>
          <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1">
            {{ post.required_number }}
          </p>
        </div>
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  final_result: {
    type: Object,
    required: true,
  },
  mode:   { type: String,  required: true },
  isDemo: { type: Boolean, default: true },
})

const candidatePercentSum = computed(() =>
  props.final_result.candidates.reduce((sum, c) => sum + parseFloat(c.vote_percent || 0), 0)
)

const noVotePercent = computed(() => {
  if (!props.final_result.total_votes_for_post) return '0.00'
  return Math.max(0, 100 - candidatePercentSum.value).toFixed(2)
})
</script>

<style scoped>
div[role="progressbar"] {
  animation: slideIn 0.5s ease-out;
}
@keyframes slideIn {
  from { width: 0; }
}
@media (max-width: 640px) {
  ::-webkit-scrollbar        { height: 4px; }
  ::-webkit-scrollbar-track  { background: #f1f5f9; }
  ::-webkit-scrollbar-thumb  { background: #cbd5e1; border-radius: 2px; }
}
</style>
