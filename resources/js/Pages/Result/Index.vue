<template>
  <election-layout>
    <div class="results-page">

      <!-- Hero Header -->
      <header class="results-hero" role="banner">
        <div class="results-hero__inner">
          <div class="results-hero__badge" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            Official Results
          </div>
          <h1 class="results-hero__title">Election Results</h1>
          <p class="results-hero__subtitle" v-if="final_result?.election_name">
            {{ final_result.election_name }}
          </p>
          <div class="results-hero__stat" aria-label="Total votes cast">
            <span class="results-hero__stat-number">{{ formatNumber(final_result?.total_votes || 0) }}</span>
            <span class="results-hero__stat-label">Total Votes Cast</span>
          </div>
        </div>
      </header>

      <!-- Main content -->
      <main class="results-main" id="main-content">

        <!-- Empty state -->
        <div v-if="!posts || posts.length === 0" class="results-empty" role="status" aria-live="polite">
          <svg class="results-empty__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <p class="results-empty__text">No results available yet.</p>
        </div>

        <!-- Posts list -->
        <ol class="results-posts" aria-label="Election results by position" v-else>
          <li
            v-for="(post, postIndex) in posts"
            :key="post.id"
            class="results-post"
            :aria-labelledby="`post-heading-${post.id}`"
          >
            <post-result
              :post="post"
              :post-index="postIndex"
              :result="getPostResults(post.id)"
              :total-election-votes="final_result?.total_votes || 0"
            />
          </li>
        </ol>

      </main>

      <!-- Footer note -->
      <footer class="results-footer" role="contentinfo">
        <p class="results-footer__note">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Results are cryptographically verified. Each vote is anonymous and cannot be traced back to any individual voter.
        </p>
      </footer>

    </div>
  </election-layout>
</template>

<script>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import PostResult from '@/Pages/Result/PostResult.vue'

export default {
  components: { ElectionLayout, PostResult },

  props: {
    final_result: { type: Object, default: null },
    posts:        { type: Array,  default: () => [] },
  },

  methods: {
    getPostResults(postId) {
      return this.final_result?.posts?.find(p => p.post_id === postId) ?? {}
    },
    formatNumber(n) {
      return new Intl.NumberFormat().format(n)
    },
  },
}
</script>

<style scoped>
/* ── Reset / base ───────────────────────────────────── */
.results-page {
  min-height: 100vh;
  background: #f1f5f9;
  font-family: 'Georgia', 'Times New Roman', serif;
}

/* ── Hero ───────────────────────────────────────────── */
.results-hero {
  background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #0f2d4a 100%);
  color: #fff;
  padding: 3rem 1rem 2.5rem;
  position: relative;
  overflow: hidden;
}
.results-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 80% 20%, rgba(212,175,55,0.08) 0%, transparent 60%);
  pointer-events: none;
}
.results-hero__inner {
  max-width: 56rem;
  margin: 0 auto;
  text-align: center;
  position: relative;
}
.results-hero__badge {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  background: rgba(212,175,55,0.15);
  border: 1px solid rgba(212,175,55,0.4);
  color: #d4af37;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 0.35rem 0.9rem;
  border-radius: 2rem;
  margin-bottom: 1.2rem;
}
.results-hero__title {
  font-size: clamp(2rem, 5vw, 3.25rem);
  font-weight: 700;
  letter-spacing: -0.02em;
  margin: 0 0 0.4rem;
  line-height: 1.1;
}
.results-hero__subtitle {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 1rem;
  color: #94a3b8;
  margin: 0 0 2rem;
}
.results-hero__stat {
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 1rem;
  padding: 1rem 2rem;
  gap: 0.2rem;
}
.results-hero__stat-number {
  font-size: 2.5rem;
  font-weight: 700;
  line-height: 1;
  color: #d4af37;
}
.results-hero__stat-label {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.75rem;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #94a3b8;
}

/* ── Main ───────────────────────────────────────────── */
.results-main {
  max-width: 56rem;
  margin: 0 auto;
  padding: 2rem 1rem 3rem;
}

/* ── Empty state ────────────────────────────────────── */
.results-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  padding: 4rem 1rem;
  color: #64748b;
  text-align: center;
}
.results-empty__icon {
  width: 3rem;
  height: 3rem;
  opacity: 0.4;
}
.results-empty__text {
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 1rem;
}

/* ── Posts list ─────────────────────────────────────── */
.results-posts {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.results-post {
  /* individual post card styled inside PostResult */
}

/* ── Footer ─────────────────────────────────────────── */
.results-footer {
  border-top: 1px solid #e2e8f0;
  background: #fff;
  padding: 1.25rem 1rem;
}
.results-footer__note {
  max-width: 56rem;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-size: 0.75rem;
  color: #94a3b8;
  text-align: center;
}

/* ── Skip-to-content (accessibility) ───────────────── */
.results-page :focus-visible {
  outline: 3px solid #d4af37;
  outline-offset: 2px;
  border-radius: 3px;
}
</style>
