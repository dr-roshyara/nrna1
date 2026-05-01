<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
    <!-- Header -->
    <section class="relative py-16 md:py-24 lg:py-32 bg-primary-900 text-white">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-6">
          <a href="/voting" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            {{ $t('pages.voting-election.cta.back') }}
          </a>
        </div>

        <div class="text-center max-w-3xl mx-auto">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-4 md:mb-6 leading-tight">
            {{ $t('pages.voting-election.title') }}
          </h1>
          <p class="text-lg sm:text-xl md:text-2xl opacity-90 leading-relaxed">
            {{ $t('pages.voting-election.subtitle') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Filter Options -->
    <section class="py-8 md:py-12 bg-white border-b border-primary-100">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="flex flex-wrap gap-3 justify-center">
          <button
            v-for="(label, key) in filterOptions"
            :key="key"
            @click="activeFilter = key"
            :class="[
              'px-4 md:px-6 py-2 md:py-3 rounded-full font-semibold transition-colors min-h-[44px]',
              activeFilter === key
                ? 'bg-primary-900 text-white'
                : 'bg-primary-50 text-primary-900 hover:bg-primary-100'
            ]"
          >
            {{ label }}
          </button>
        </div>
      </div>
    </section>

    <!-- Empty State -->
    <section class="py-16 md:py-24">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto">
          <div class="w-16 h-16 md:w-20 md:h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 md:w-10 md:h-10 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>

          <h2 class="text-2xl md:text-3xl font-bold text-primary-900 mb-3">
            {{ $t('pages.voting-election.empty_state.title') }}
          </h2>
          <p class="text-neutral-600 mb-8">
            {{ $t('pages.voting-election.empty_state.description') }}
          </p>

          <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a
              href="/voting"
              class="inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-primary-900 text-white font-bold rounded-lg hover:bg-primary-800 transition-colors shadow-lg min-h-[44px] md:min-h-[56px]"
            >
              <svg class="w-5 h-5 md:w-6 md:h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
              </svg>
              {{ $t('pages.voting-election.cta.back') }}
            </a>

            <a
              href="/voting"
              class="inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 border-2 border-primary-900 text-primary-900 font-bold rounded-lg hover:bg-primary-50 transition-colors min-h-[44px] md:min-h-[56px]"
            >
              {{ $t('pages.voting-election.cta.learn_more') }}
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Info Section -->
    <section class="py-16 md:py-20 bg-primary-50 border-t border-primary-200">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
          <h3 class="text-2xl md:text-3xl font-bold text-primary-900 mb-6 text-center">
            {{ $t('platform.name') }}
          </h3>
          <p class="text-neutral-700 text-center mb-8">
            {{ $t('platform.description') }}
          </p>

          <div class="grid md:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-lg border border-primary-200">
              <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                <span class="text-primary-600 font-bold">✓</span>
              </div>
              <p class="font-semibold text-primary-900">{{ $t('trust.gdpr') }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-primary-200">
              <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                <span class="text-primary-600 font-bold">✓</span>
              </div>
              <p class="font-semibold text-primary-900">{{ $t('trust.german_hosting') }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-primary-200">
              <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                <span class="text-primary-600 font-bold">✓</span>
              </div>
              <p class="font-semibold text-primary-900">{{ $t('trust.encryption') }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'VotingElection',
  data() {
    return {
      activeFilter: 'all',
      filterOptions: {
        all: this.$t('pages.voting-election.filters.all'),
        active: this.$t('pages.voting-election.filters.active'),
        pending: this.$t('pages.voting-election.filters.pending'),
        closed: this.$t('pages.voting-election.filters.closed')
      },
      elections: []
    };
  },
  watch: {
    activeFilter(newFilter) {
      // Filter elections based on status
      console.log('Filter changed to:', newFilter);
    }
  }
};
</script>

<style scoped>
/* Accessible focus styles */
a:focus,
button:focus {
  outline: 2px solid #2563eb;
  outline-offset: 2px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* High contrast mode */
@media (prefers-contrast: high) {
  .bg-gradient-to-r,
  .bg-gradient-to-b {
    background: #ffffff !important;
  }

  .text-primary-900 {
    color: #000000 !important;
  }

  .border-primary-200,
  .border-primary-100 {
    border-color: #000000 !important;
    border-width: 2px !important;
  }
}
</style>

