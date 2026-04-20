<template>
  <div class="results-page">

      <!-- Hero Header -->
      <header class="results-hero" role="banner">
        <div class="results-hero__inner">
          <div class="results-hero__header">
            <div>
              <!-- Organisation Logo -->
              <div v-if="final_result?.logo_url" class="results-hero__logo">
                <img
                  :src="final_result.logo_url"
                  :alt="final_result.org_name || 'Organisation logo'"
                  class="results-hero__logo-img"
                />
              </div>
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

            <!-- Organisation Button -->
            <a v-if="final_result?.org_slug"
               :href="route('organisations.show', final_result.org_slug)"
               class="results-org-button"
               :title="`Go to ${final_result.org_name}`">
              <svg class="results-org-button__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <span class="results-org-button__text">{{ final_result.org_name }}</span>
              <svg class="results-org-button__arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      </header>

      <!-- Action Buttons -->
      <section class="results-actions">
        <button
          @click="downloadPDF"
          class="results-btn results-btn--primary"
          :disabled="isDownloading"
          :aria-label="isDownloading ? 'Generating PDF...' : 'Download results as PDF'">
          <svg v-if="!isDownloading" class="results-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          <span v-if="isDownloading">Generating PDF...</span>
          <span v-else>Download PDF</span>
        </button>

        <button
          @click="printResults"
          class="results-btn results-btn--secondary"
          aria-label="Print results">
          <svg class="results-btn__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9a2 2 0 00-2 2v2a2 2 0 002 2h6a2 2 0 002-2v-2a2 2 0 00-2-2zm-6-4a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span>Print</span>
        </button>
      </section>

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
</template>

<script>
import PostResult from '@/Pages/Result/PostResult.vue'

export default {
  components: { PostResult },

  props: {
    final_result: { type: Object, default: null },
    posts:        { type: Array,  default: () => [] },
  },

  data() {
    return {
      isDownloading: false,
    };
  },

  mounted() {
    // Data logging
  },

  methods: {
    getPostResults(postId) {
      return this.final_result?.posts?.find(p => p.post_id === postId) ?? {}
    },
    formatNumber(n) {
      return new Intl.NumberFormat().format(n)
    },
    async downloadPDF() {
      this.isDownloading = true;
      try {
        const response = await fetch(
          route('result.download.pdf', this.final_result?.election_slug || '')
        );
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `election_results_${new Date().toISOString().split('T')[0]}.pdf`;
        document.body.appendChild(link);
        link.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(link);
      } catch (error) {
        console.error('Failed to download PDF:', error);
        alert('Failed to download PDF. Please try again.');
      } finally {
        this.isDownloading = false;
      }
    },
    printResults() {
      window.print();
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
.results-hero__logo {
  display: flex;
  justify-content: center;
  margin-bottom: 1.25rem;
}
.results-hero__logo-img {
  height: 80px;
  width: auto;
  max-width: 160px;
  object-fit: contain;
  border-radius: 0.5rem;
  background: rgba(255,255,255,0.10);
  padding: 0.5rem;
  backdrop-filter: blur(4px);
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

.results-hero__header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 2rem;
  flex-wrap: wrap;
}

.results-hero__header > div {
  flex: 1;
  min-width: 250px;
}

.results-org-button {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  background: rgba(212, 175, 55, 0.1);
  border: 1.5px solid rgba(212, 175, 55, 0.3);
  color: #d4af37;
  padding: 0.75rem 1.25rem;
  border-radius: 0.75rem;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-weight: 600;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  white-space: nowrap;
  height: fit-content;
}

.results-org-button:hover {
  background: rgba(212, 175, 55, 0.2);
  border-color: rgba(212, 175, 55, 0.6);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(212, 175, 55, 0.15);
}

.results-org-button:focus-visible {
  outline: 3px solid #d4af37;
  outline-offset: 2px;
  border-radius: 0.5rem;
}

.results-org-button__icon {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
}

.results-org-button__text {
  font-weight: 600;
}

.results-org-button__arrow {
  width: 16px;
  height: 16px;
  flex-shrink: 0;
  transition: transform 0.3s ease;
}

.results-org-button:hover .results-org-button__arrow {
  transform: translateX(3px);
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

/* ── Actions ────────────────────────────────────────── */
.results-actions {
  max-width: 56rem;
  margin: 0 auto 2rem;
  padding: 0 1rem;
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.results-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.5rem;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.2s ease;
  min-height: 44px;
}

.results-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.results-btn__icon {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
}

.results-btn--primary {
  background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
  color: #d4af37;
  border: 1.5px solid rgba(212, 175, 55, 0.3);
}

.results-btn--primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #1a2845 0%, #2a4575 100%);
  border-color: rgba(212, 175, 55, 0.6);
  box-shadow: 0 4px 12px rgba(212, 175, 55, 0.15);
}

.results-btn--secondary {
  background: #64748b;
  color: #fff;
}

.results-btn--secondary:hover:not(:disabled) {
  background: #475569;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.results-btn:focus-visible {
  outline: 3px solid #d4af37;
  outline-offset: 2px;
}

/* ── Print styles ──────────────────────────────────── */
@media print {
  .results-actions { display: none !important; }
  .results-hero__header { flex-direction: column; }
  .results-org-button { display: none !important; }
  .results-post { page-break-inside: avoid; }
  main { max-width: 100%; }
}

/* ── Skip-to-content (accessibility) ───────────────── */
.results-page :focus-visible {
  outline: 3px solid #d4af37;
  outline-offset: 2px;
  border-radius: 3px;
}
</style>
