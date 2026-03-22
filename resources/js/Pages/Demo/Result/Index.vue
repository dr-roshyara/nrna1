<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col">
    <!-- Election Header -->
    <PublicDigitHeader />

    <!-- Mode Indicator Banner -->
    <mode-indicator :mode="mode" :organisation-id="organisation_id" />

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 flex-1">
      <!-- Header Section -->
      <header class="mb-8 sm:mb-12">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
          {{ page_title || $t('pages.demo-result.page_title') }}
        </h1>

        <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 max-w-2xl">
          {{ $t('pages.demo-result.subtitle') }}
        </p>
      </header>

      <!-- Stats Dashboard -->
      <section
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 sm:mb-12"
        :aria-label="$t('pages.demo-result.stats_aria')"
      >
        <stat-card
          icon="users"
          :value="final_result.total_votes"
          :label="$t('pages.demo-result.stats.total_votes')"
          :aria-label="`${$t('pages.demo-result.stats.total_votes')}: ${final_result.total_votes}`"
        />
        <stat-card
          icon="clipboard-list"
          :value="posts.length"
          :label="$t('pages.demo-result.stats.positions')"
          :aria-label="`${$t('pages.demo-result.stats.positions')}: ${posts.length}`"
        />
        <stat-card
          icon="calendar"
          :value="new Date().toLocaleDateString()"
          :label="$t('pages.demo-result.stats.generated_date')"
          :aria-label="`${$t('pages.demo-result.stats.generated_date')}: ${new Date().toLocaleDateString()}`"
        />
        <stat-card
          icon="shield-check"
          :value="mode === 'global' ? $t('pages.demo-result.mode_indicator.mode1') : $t('pages.demo-result.mode_indicator.mode2')"
          :label="$t('pages.demo-result.stats.election_mode')"
          :aria-label="`${$t('pages.demo-result.stats.election_mode')}: ${mode}`"
        />
      </section>

      <!-- Action Buttons -->
      <section class="mb-8 sm:mb-12 flex flex-wrap gap-3">
        <button
          @click="downloadPDF"
          class="inline-flex items-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 text-white rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 min-h-[44px] sm:min-h-[48px]"
          :disabled="isDownloading"
          :aria-label="$t('pages.demo-result.actions.download_aria')"
        >
          <svg v-if="!isDownloading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          <span class="hidden sm:inline" v-if="!isDownloading">{{ $t('pages.demo-result.actions.download_pdf') }}</span>
          <span class="sm:hidden" v-if="!isDownloading">{{ $t('pages.demo-result.actions.download_pdf_short') }}</span>
          <span v-if="isDownloading">{{ $t('pages.demo-result.actions.generating') }}</span>
        </button>

        <button
          @click="printResults"
          class="inline-flex items-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-800 text-white rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-900 min-h-[44px] sm:min-h-[48px]"
          :aria-label="$t('pages.demo-result.actions.print_aria')"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9a2 2 0 00-2 2v2a2 2 0 002 2h6a2 2 0 002-2v-2a2 2 0 00-2-2zm-6-4a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span class="hidden sm:inline">{{ $t('pages.demo-result.actions.print') }}</span>
        </button>
      </section>

      <!-- Results Grid -->
      <section
        class="space-y-6 sm:space-y-8"
        role="region"
        :aria-label="$t('pages.demo-result.results_aria')"
      >
        <candidate-card
          v-for="post in posts"
          :key="post.post_id"
          :post="post"
          :final_result="getPostResults(post.post_id)"
          :mode="mode"
          :is-demo="true"
        />
      </section>

      <!-- Empty State -->
      <section v-if="!posts || posts.length === 0" class="text-center py-16 sm:py-20">
        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-lg text-gray-500 dark:text-gray-400">{{ $t('pages.demo-result.empty.message') }}</p>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">{{ $t('pages.demo-result.empty.hint') }}</p>
      </section>

    </main>

    <!-- Public Digit Footer -->
    <public-digit-footer />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue';
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue';
import ModeIndicator from './ModeIndicator.vue';
import CandidateCard from './Candidate.vue';
import StatCard from '@/Components/StatCard.vue';

const isDownloading = ref(false);

const props = defineProps({
  final_result: { type: Object, required: true },
  posts:        { type: Array,  required: true },
  mode: {
    type: String,
    required: true,
    validator: (v) => ['global', 'organisation'].includes(v)
  },
  organisation_id: { type: Number,  default: null },
  is_demo:         { type: Boolean, default: true },
  page_title:      { type: String,  default: null },
});

const getPostResults = (postId) =>
  props.final_result?.posts?.find(p => p.post_id === postId) || {};

const downloadPDF = async () => {
  isDownloading.value = true;
  try {
    const route = props.mode === 'global'
      ? '/demo/global/result/download-pdf'
      : '/demo/result/download-pdf';

    const response = await fetch(route);
    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `demo_election_results_${props.mode}_${new Date().toISOString().split('T')[0]}.pdf`;
    document.body.appendChild(link);
    link.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(link);
  } catch (error) {
    console.error('Failed to download PDF:', error);
    alert('Failed to download PDF. Please try again.');
  } finally {
    isDownloading.value = false;
  }
};

const printResults = () => window.print();
</script>

<style scoped>
@media print {
  .no-print { display: none !important; }
  section   { page-break-inside: avoid; }
  article   { page-break-inside: avoid; }
}
@media (prefers-contrast: more) {
  button:focus { outline: 3px solid currentColor; outline-offset: 2px; }
}
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style>
