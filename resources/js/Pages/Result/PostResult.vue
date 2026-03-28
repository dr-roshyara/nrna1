<template>
  <article class="post-card" :aria-labelledby="`post-heading-${post.id}`">

    <!-- Card header -->
    <header class="post-card__header">
      <div class="post-card__meta">
        <span class="post-card__index" aria-hidden="true">{{ String(postIndex + 1).padStart(2, '0') }}</span>
        <div>
          <h2 class="post-card__title" :id="`post-heading-${post.id}`">{{ post.name }}</h2>
          <p class="post-card__scope" v-if="post.state_name || post.is_national_wide">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ post.is_national_wide ? 'National' : post.state_name }}
          </p>
        </div>
      </div>
      <div class="post-card__stats" role="group" :aria-label="`Vote statistics for ${post.name}`">
        <div class="post-card__stat">
          <span class="post-card__stat-value">{{ formatNumber(result.total_votes_for_post || 0) }}</span>
          <span class="post-card__stat-key">Votes</span>
        </div>
        <div class="post-card__stat" v-if="result.no_vote_count > 0">
          <span class="post-card__stat-value post-card__stat-value--muted">{{ formatNumber(result.no_vote_count) }}</span>
          <span class="post-card__stat-key">Abstained</span>
        </div>
        <div class="post-card__stat" v-if="post.required_number">
          <span class="post-card__stat-value post-card__stat-value--accent">{{ post.required_number }}</span>
          <span class="post-card__stat-key">{{ post.required_number === 1 ? 'Seat' : 'Seats' }}</span>
        </div>
      </div>
    </header>

    <!-- No data -->
    <div v-if="!candidates || candidates.length === 0" class="post-card__no-data" role="status">
      No candidate data available.
    </div>

    <!-- Candidates -->
    <ol class="candidates" :aria-label="`Candidates for ${post.name}, sorted by votes`" v-else>
      <li
        v-for="(candidate, idx) in candidates"
        :key="candidate.candidacy_id"
        class="candidate"
        :class="{
          'candidate--winner': isWinner(idx),
          'candidate--runner': idx >= post.required_number && idx < (post.required_number + 2),
        }"
        :aria-label="candidateAriaLabel(candidate, idx)"
      >
        <!-- Rank -->
        <span class="candidate__rank" aria-hidden="true">
          <svg v-if="isWinner(idx)" class="candidate__crown" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 20h20v2H2v-2zM4 17l4-8 4 4 4-6 4 6H4z"/></svg>
          <template v-else>{{ idx + 1 }}</template>
        </span>

        <!-- Name + bar -->
        <div class="candidate__body">
          <div class="candidate__name-row">
            <span class="candidate__name">{{ candidate.name }}</span>
            <span class="candidate__tally">
              <strong class="candidate__votes">{{ formatNumber(candidate.vote_count) }}</strong>
              <span class="candidate__percent">{{ candidate.vote_percent?.toFixed(1) }}%</span>
            </span>
          </div>

          <!-- Progress bar — accessible -->
          <div
            class="candidate__bar-track"
            role="progressbar"
            :aria-valuenow="candidate.vote_percent || 0"
            aria-valuemin="0"
            aria-valuemax="100"
            :aria-label="`${candidate.name}: ${(candidate.vote_percent || 0).toFixed(1)}% of votes`"
          >
            <div
              class="candidate__bar-fill"
              :class="{ 'candidate__bar-fill--winner': isWinner(idx) }"
              :style="{ width: (candidate.vote_percent || 0) + '%' }"
            ></div>
          </div>
        </div>

      </li>
    </ol>

    <!-- Abstentions row -->
    <div v-if="result.no_vote_count > 0" class="post-card__abstain" aria-label="Abstentions">
      <span class="post-card__abstain-label">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Abstentions
      </span>
      <span class="post-card__abstain-count">{{ formatNumber(result.no_vote_count) }}</span>
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
  },

  methods: {
    isWinner(idx) {
      return idx < (this.post.required_number || 1)
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
/* ── Card shell ─────────────────────────────────────── */
.post-card {
  background: #fff;
  border-radius: 1rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
  overflow: hidden;
  transition: box-shadow 0.2s ease;
}
.post-card:focus-within {
  box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 20px rgba(0,0,0,0.08);
}

/* ── Card header ────────────────────────────────────── */
.post-card__header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1rem;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  flex-wrap: wrap;
}
.post-card__meta {
  display: flex;
  align-items: flex-start;
  gap: 0.9rem;
}
.post-card__index {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 1.6rem;
  font-weight: 800;
  color: #e2e8f0;
  line-height: 1;
  flex-shrink: 0;
  padding-top: 0.1rem;
  min-width: 2.2rem;
}
.post-card__title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 0.25rem;
  line-height: 1.25;
}
.post-card__scope {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.72rem;
  font-weight: 600;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  margin: 0;
}
.post-card__stats {
  display: flex;
  gap: 1.25rem;
  flex-shrink: 0;
}
.post-card__stat {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.1rem;
}
.post-card__stat-value {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 1.1rem;
  font-weight: 700;
  color: #0f172a;
  line-height: 1;
}
.post-card__stat-value--muted { color: #94a3b8; }
.post-card__stat-value--accent { color: #1e3a5f; }
.post-card__stat-key {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.65rem;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #94a3b8;
}

/* ── No data ────────────────────────────────────────── */
.post-card__no-data {
  padding: 2rem 1.5rem;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.875rem;
  color: #94a3b8;
  text-align: center;
}

/* ── Candidates list ────────────────────────────────── */
.candidates {
  list-style: none;
  margin: 0;
  padding: 0.5rem 0;
}
.candidate {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1.5rem;
  transition: background 0.15s ease;
  position: relative;
}
.candidate:hover {
  background: #f8fafc;
}
.candidate--winner {
  background: linear-gradient(90deg, rgba(212,175,55,0.05) 0%, transparent 60%);
}
.candidate--winner:hover {
  background: linear-gradient(90deg, rgba(212,175,55,0.09) 0%, #f8fafc 60%);
}

/* ── Rank badge ─────────────────────────────────────── */
.candidate__rank {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.8rem;
  font-weight: 700;
  color: #cbd5e1;
  width: 1.5rem;
  text-align: center;
  flex-shrink: 0;
}
.candidate--winner .candidate__rank {
  color: #d4af37;
}
.candidate__crown {
  width: 1rem;
  height: 1rem;
  color: #d4af37;
}

/* ── Candidate body ─────────────────────────────────── */
.candidate__body {
  flex: 1;
  min-width: 0;
}
.candidate__name-row {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 0.5rem;
  margin-bottom: 0.35rem;
}
.candidate__name {
  font-family: 'Georgia', 'Times New Roman', serif;
  font-size: 0.95rem;
  color: #1e293b;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  flex: 1;
  min-width: 0;
}
.candidate--winner .candidate__name {
  font-weight: 700;
  color: #0f172a;
}
.candidate__tally {
  display: flex;
  align-items: baseline;
  gap: 0.35rem;
  flex-shrink: 0;
}
.candidate__votes {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.875rem;
  font-weight: 700;
  color: #1e293b;
}
.candidate__percent {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.75rem;
  color: #64748b;
}

/* ── Bar ────────────────────────────────────────────── */
.candidate__bar-track {
  width: 100%;
  height: 6px;
  background: #f1f5f9;
  border-radius: 999px;
  overflow: hidden;
}
.candidate__bar-fill {
  height: 100%;
  background: #475569;
  border-radius: 999px;
  transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}
.candidate__bar-fill--winner {
  background: linear-gradient(90deg, #b8960c, #d4af37);
}

/* ── Abstentions ────────────────────────────────────── */
.post-card__abstain {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 1.5rem;
  border-top: 1px dashed #e2e8f0;
  background: #fafafa;
}
.post-card__abstain-label {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.75rem;
  font-weight: 500;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}
.post-card__abstain-count {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.875rem;
  font-weight: 700;
  color: #94a3b8;
}

/* ── Responsive ─────────────────────────────────────── */
@media (max-width: 480px) {
  .post-card__header {
    padding: 1rem;
    flex-direction: column;
    gap: 0.75rem;
  }
  .post-card__stats {
    gap: 1rem;
    align-self: stretch;
    justify-content: flex-start;
  }
  .post-card__stat {
    align-items: flex-start;
  }
  .candidate {
    padding: 0.65rem 1rem;
    gap: 0.5rem;
  }
  .post-card__abstain {
    padding: 0.65rem 1rem;
  }
}

/* ── Focus ──────────────────────────────────────────── */
.candidate:focus-visible {
  outline: 3px solid #d4af37;
  outline-offset: -2px;
}
</style>
