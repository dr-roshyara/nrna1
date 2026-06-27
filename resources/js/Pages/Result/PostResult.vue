<template>
  <article class="rounded-xl overflow-hidden bg-white border border-neutral-200 shadow-sm hover:shadow-md transition-shadow duration-300" :aria-labelledby="`post-heading-${post.id}`">

    <!-- Post Header with Metadata -->
    <header class="px-6 py-7 border-b border-primary-100 bg-gradient-to-br from-primary-50 via-primary-50 to-white">
      <div class="flex items-start justify-between gap-4 mb-4">
        <div class="flex items-baseline gap-4 flex-1 min-w-0">
          <span class="text-6xl font-black text-primary-100" aria-hidden="true">{{ String(postIndex + 1).padStart(2, '0') }}</span>
          <div class="min-w-0 flex-1">
            <h2 class="text-3xl font-bold text-neutral-900 break-words" :id="`post-heading-${post.id}`">{{ post.name }}</h2>
            <p v-if="post.state_name || post.is_national_wide" class="text-sm text-neutral-600 mt-1">
              <span v-if="post.is_national_wide" class="inline-flex items-center gap-1">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-primary-600" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                National Post
              </span>
              <span v-else class="inline-flex items-center gap-1">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-primary-600" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ post.state_name }}
              </span>
            </p>
          </div>
        </div>
      </div>

      <!-- Stats Grid — Refined Spacing -->
      <div class="grid grid-cols-3 gap-6 mt-6 pt-4 border-t border-primary-100">
        <div class="space-y-1.5">
          <div class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">Votes Cast</div>
          <div class="text-3xl font-black text-neutral-900">{{ formatNumber(result.total_votes_for_post || 0) }}</div>
        </div>
        <div v-if="result.no_vote_count > 0" class="space-y-1.5">
          <div class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">Abstained</div>
          <div class="text-3xl font-black text-neutral-700">{{ formatNumber(result.no_vote_count) }}</div>
        </div>
        <div v-if="post.required_number" class="space-y-1.5">
          <div class="text-sm font-semibold text-neutral-600 uppercase tracking-wide">{{ post.required_number === 1 ? 'Seat' : 'Seats' }}</div>
          <div class="text-3xl font-black text-accent-600">{{ post.required_number }}</div>
        </div>
      </div>
    </header>

    <!-- No Data State -->
    <div v-if="!candidates || candidates.length === 0" class="flex flex-col items-center justify-center px-6 py-16 text-center">
      <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-neutral-100">
        <svg class="h-8 w-8 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
      </div>
      <p class="text-lg font-semibold text-neutral-900 mb-1">No Candidate Data</p>
      <p class="text-sm text-neutral-600">Results for this position will appear here once voting is complete.</p>
    </div>

    <!-- Results Sections -->
    <div v-else class="space-y-8 px-6 py-8">
      <!-- Elected Candidates Section -->
      <div v-if="candidates.some((c, idx) => isWinner(idx))">
        <div class="mb-6">
          <div class="flex items-center gap-2 mb-1">
            <svg class="w-5 h-5 text-accent-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 10.26H24L17.45 15.53L20.54 23.79L12 18.52L3.46 23.79L6.55 15.53L0 10.26H8.91L12 2Z"/></svg>
            <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-600">Elected</h3>
          </div>
          <div class="h-1 w-12 bg-gradient-to-r from-accent-600 to-accent-400 rounded-full"></div>
        </div>

        <!-- Winners List -->
        <div class="space-y-7">
          <div v-for="(candidate, idx) in candidates" :key="candidate.candidacy_id" v-show="isWinner(idx)" class="group">
            <div class="rounded-lg bg-gradient-to-br from-accent-50 to-white border border-accent-200 p-6 transition-all duration-300 hover:border-accent-400 hover:shadow-md">
              <!-- Winner Badge + Name -->
              <div class="flex items-start gap-3 mb-5">
                <div class="flex-shrink-0 flex h-8 w-8 items-center justify-center rounded-full bg-accent-600 text-white">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 10.26H24L17.45 15.53L20.54 23.79L12 18.52L3.46 23.79L6.55 15.53L0 10.26H8.91L12 2Z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="text-xl font-bold text-neutral-900 break-words">{{ candidate.name }}</h4>
                </div>
              </div>

              <!-- Stats Grid -->
              <div class="grid grid-cols-2 gap-5 mb-5">
                <div class="rounded-md bg-white border border-accent-100 p-3">
                  <div class="text-xs font-semibold text-neutral-600 uppercase mb-1">Vote Count</div>
                  <div class="text-2xl font-black text-accent-600">{{ formatNumber(candidate.vote_count) }}</div>
                </div>
                <div class="rounded-md bg-white border border-accent-100 p-3">
                  <div class="text-xs font-semibold text-neutral-600 uppercase mb-1">Vote Share</div>
                  <div class="text-2xl font-black text-accent-600">{{ candidate.vote_percent?.toFixed(1) }}%</div>
                </div>
              </div>

              <!-- Progress Bar Section -->
              <div class="space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-semibold text-neutral-600 uppercase">Vote Distribution</span>
                  <span class="text-xs font-bold text-accent-600">{{ candidate.vote_percent?.toFixed(1) }}%</span>
                </div>
                <div class="relative h-3 bg-neutral-100 rounded-full overflow-hidden">
                  <div
                    class="absolute h-full bg-gradient-to-r from-accent-600 to-accent-400 rounded-full transition-all duration-1000 ease-out"
                    :style="{ width: (candidate.vote_percent || 0) + '%' }"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Other Candidates Section -->
      <div v-if="candidates.some((c, idx) => !isWinner(idx))">
        <div class="mb-6">
          <div class="flex items-center gap-2 mb-1">
            <div class="h-5 w-5 rounded border border-neutral-400"></div>
            <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-600">Other Candidates</h3>
          </div>
          <div class="h-1 w-12 bg-neutral-300 rounded-full"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div
            v-for="(candidate, idx) in candidates"
            v-show="!isWinner(idx)"
            :key="candidate.candidacy_id"
            class="rounded-lg border border-neutral-200 bg-white p-4 transition-all duration-300 hover:border-primary-300 hover:shadow-md hover:bg-primary-50 group cursor-default"
          >
            <!-- Rank Badge + Name -->
            <div class="flex items-baseline gap-3 mb-3">
              <span class="text-2xl font-black text-neutral-300 group-hover:text-primary-400 min-w-fit">{{ idx + 1 }}</span>
              <span class="text-sm font-semibold text-neutral-900 break-words group-hover:text-primary-700">{{ candidate.name }}</span>
            </div>

            <!-- Vote Stats -->
            <div class="grid grid-cols-2 gap-3 mb-3">
              <div class="rounded bg-neutral-50 p-2 group-hover:bg-primary-100 transition-colors">
                <div class="text-xs text-neutral-600 font-semibold uppercase tracking-wide mb-0.5">Votes</div>
                <div class="font-black text-neutral-900 group-hover:text-primary-700">{{ formatNumber(candidate.vote_count) }}</div>
              </div>
              <div class="rounded bg-neutral-50 p-2 group-hover:bg-primary-100 transition-colors text-right">
                <div class="text-xs text-neutral-600 font-semibold uppercase tracking-wide mb-0.5">Share</div>
                <div class="font-black text-neutral-900 group-hover:text-primary-700">{{ candidate.vote_percent?.toFixed(1) }}%</div>
              </div>
            </div>

            <!-- Progress Bar -->
            <div class="h-2 bg-neutral-100 rounded-full overflow-hidden">
              <div
                class="h-full bg-neutral-400 group-hover:bg-primary-500 transition-all duration-500"
                :style="{ width: (candidate.vote_percent || 0) + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Abstentions Row -->
      <div v-if="result.no_vote_count > 0" class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 flex items-center justify-between hover:bg-neutral-100 transition-colors duration-200">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-200">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-neutral-700" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
          </div>
          <span class="text-sm font-semibold uppercase tracking-wider text-neutral-700">Did Not Vote</span>
        </div>
        <span class="text-lg font-black text-neutral-700">{{ formatNumber(result.no_vote_count) }}</span>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  name: 'PostResult',

  props: {
    post:               { type: Object, required: true },
    postIndex:          { type: Number, default: 0 },
    result:             { type: Object, default: () => ({}) },
    totalElectionVotes: { type: Number, default: 0 },
  },

  computed: {
    candidates() {
      if (!this.result?.candidates) return []
      return [...this.result.candidates].sort((a, b) => b.vote_count - a.vote_count)
    },
    winningVoteCount() {
      const requiredNumber = this.post.required_number || 1
      if (this.candidates.length === 0) return 0
      if (this.candidates.length <= requiredNumber) return this.candidates[this.candidates.length - 1]?.vote_count || 0
      return this.candidates[requiredNumber - 1]?.vote_count || 0
    },
  },

  methods: {
    isWinner(idx) {
      return this.candidates[idx]?.vote_count >= this.winningVoteCount
    },
    formatNumber(n) {
      return new Intl.NumberFormat().format(n)
    },
    candidateAriaLabel(candidate, idx) {
      const rank = idx + 1
      const winner = this.isWinner(idx) ? 'Elected. ' : ''
      return `Rank ${rank}. ${winner}${candidate.name}. ${this.formatNumber(candidate.vote_count)} votes, ${(candidate.vote_percent || 0).toFixed(1)} percent.`
    },
  },
}
</script>

<style scoped>
li:focus-visible {
  outline: 3px solid rgb(245, 158, 11);
  outline-offset: -2px;
}

/* Smooth entrance animations */
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(12px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

article {
  animation: slideUp 0.5s ease-out;
}

/* Refined progress bar animation */
@keyframes fillBar {
  from {
    width: 0 !important;
  }
}

.group:nth-child(1) div[style*='width'] {
  animation: fillBar 1.2s ease-out forwards;
}

.group:nth-child(2) div[style*='width'] {
  animation: fillBar 1.3s ease-out forwards;
}

.group:nth-child(3) div[style*='width'] {
  animation: fillBar 1.4s ease-out forwards;
}

/* Refined focus states for accessibility */
button:focus-visible,
a:focus-visible {
  outline: 2px solid rgb(245, 158, 11);
  outline-offset: 2px;
}
</style>
