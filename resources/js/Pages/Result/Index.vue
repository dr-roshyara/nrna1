<template>
  <div class="flex flex-col min-h-screen bg-neutral-50 font-serif">
    <!-- Main Content -->
    <main class="flex-1 bg-gray-100">
      <!-- Hero Header -->
      <header class="relative overflow-hidden bg-gradient-to-br from-neutral-950 via-slate-900 to-neutral-900 py-16 px-4 sm:py-24" role="banner">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-accent-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent-500/3 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Accent gradient overlay -->
        <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(circle at 80% 20%, rgba(245,158,11,0.06) 0%, transparent 50%);"></div>

        <div class="relative mx-auto max-w-4xl">
          <div class="flex flex-col items-center justify-center gap-8 text-center">
            <div class="flex-1">
              <!-- Organisation Logo -->
              <div v-if="final_result?.logo_url" class="mb-5 flex justify-center">
                <img
                  :src="final_result.logo_url"
                  :alt="final_result.org_name || 'Organisation logo'"
                  class="h-20 w-auto max-w-40 rounded-lg bg-white/10 p-1 object-contain"
                />
              </div>
              <div class="mb-5 inline-flex items-center gap-1 rounded-full border border-accent-500/40 bg-accent-500/15 px-4 py-1" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="text-accent-500"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                <span class="text-xs font-bold uppercase tracking-wider text-accent-500">Official Results</span>
              </div>
              <h1 class="mb-1 text-4xl font-bold text-white sm:text-5xl">Election Results</h1>
              <p class="mb-8 text-lg text-neutral-500" v-if="final_result?.election_name">
                {{ final_result.election_name }}
              </p>
              <div class="inline-flex flex-col items-center gap-1 rounded-2xl border border-white/10 bg-white/5 px-8 py-4" aria-label="Total votes cast">
                <span class="text-5xl font-bold text-accent-500">{{ formatNumber(final_result?.total_votes || 0) }}</span>
                <span class="text-xs font-medium uppercase tracking-wider text-neutral-600">Total Votes Cast</span>
              </div>
            </div>

            <!-- Organisation Button -->
            <a v-if="final_result?.org_slug"
               :href="route('organisations.show', final_result.org_slug)"
               class="inline-flex items-center gap-3 rounded-lg border border-accent-500/30 bg-accent-500/10 px-5 py-3 font-semibold text-accent-500 transition-all duration-300 hover:border-accent-500/60 hover:bg-accent-500/20 hover:shadow-lg hover:-translate-y-0.5 focus-visible:outline-3 focus-visible:outline-offset-2 focus-visible:outline-accent-500 whitespace-nowrap h-fit"
               :title="`Go to ${final_result.org_name}`">
              <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <span class="font-semibold">{{ final_result.org_name }}</span>
              <svg class="h-4 w-4 flex-shrink-0 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      </header>

      <!-- Action Buttons -->
      <section class="mx-auto mb-8 max-w-3xl space-x-3 px-4">
        <Button
          @click="downloadPDF"
          variant="primary"
          size="lg"
          :disabled="isDownloading"
          :aria-label="isDownloading ? 'Generating PDF...' : 'Download results as PDF'">
          <svg v-if="!isDownloading" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          <span v-if="isDownloading">Generating PDF...</span>
          <span v-else>Download PDF</span>
        </Button>

        <Button
          @click="printResults"
          variant="secondary"
          size="lg"
          aria-label="Print results">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9a2 2 0 00-2 2v2a2 2 0 002 2h6a2 2 0 002-2v-2a2 2 0 00-2-2zm-6-4a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span>Print</span>
        </Button>
      </section>

      <!-- Main content -->
      <main class="mx-auto max-w-6xl px-4 py-12 bg-gray-50" id="main-content">

        <!-- Results Header Section -->
        <div v-if="posts && posts.length > 0" class="mb-12">
          <h2 class="text-lg font-bold uppercase tracking-wider text-neutral-600 mb-4">Results by Position</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-lg bg-white border border-neutral-200 p-6 hover:border-accent-300 transition-colors">
              <div class="text-xs font-bold uppercase text-neutral-600 mb-1">Total Positions</div>
              <div class="text-3xl font-black text-black">{{ posts.length }}</div>
            </div>
            <div class="rounded-lg bg-white border border-neutral-200 p-6 hover:border-accent-300 transition-colors">
              <div class="text-xs font-bold uppercase text-neutral-600 mb-1">Votes Cast</div>
              <div class="text-3xl font-black text-black">{{ formatNumber(final_result?.total_votes || 0) }}</div>
            </div>
            <div class="rounded-lg bg-white border border-neutral-200 p-6 hover:border-accent-300 transition-colors">
              <div class="text-xs font-bold uppercase text-neutral-600 mb-1">Status</div>
              <div class="text-3xl font-black text-accent-600">Complete</div>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-if="!posts || posts.length === 0" class="flex flex-col items-center justify-center min-h-96 rounded-xl bg-white border-2 border-dashed border-neutral-200 px-6 py-16 text-center" role="status" aria-live="polite">
          <div class="mb-4 flex items-center justify-center w-16 h-16 rounded-full bg-neutral-100">
            <svg class="h-8 w-8 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
          </div>
          <p class="text-lg font-semibold text-neutral-700 mb-2">No Results Available</p>
          <p class="text-neutral-600">Results for this election will appear here once voting is complete.</p>
        </div>

        <!-- Posts Grid -->
        <ol v-else class="grid grid-cols-1 gap-8" aria-label="Election results by position">
          <li
            v-for="(post, postIndex) in posts"
            :key="post.id"
            :aria-labelledby="`post-heading-${post.id}`"
            class="animate-fade-in"
            :style="{ animationDelay: `${postIndex * 100}ms` }"
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
      <footer class="border-t border-neutral-200 bg-white px-4 py-5" role="contentinfo">
        <div class="mx-auto max-w-3xl">
          <p class="flex items-center justify-center gap-2 text-center text-xs text-neutral-500">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Results are cryptographically verified. Each vote is anonymous and cannot be traced back to any individual voter.
          </p>
        </div>
      </footer>
    </main>

    <!-- Public Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script>
import Button from '@/Components/Button.vue'
import PostResult from '@/Pages/Result/PostResult.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

export default {
  components: { Button, PostResult, PublicDigitFooter },

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
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(12px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.5s ease-out forwards;
  opacity: 0;
}

@media print {
  section { display: none !important; }
  header { background: white; color: black; }
  footer { border-top: none; background: transparent; }
  main { max-width: 100%; padding: 0; }
  li { page-break-inside: avoid; }
  .text-accent-500 { color: black; }
  .text-white { color: black; }
  .animate-fade-in { animation: none; opacity: 1; }
}
</style>
