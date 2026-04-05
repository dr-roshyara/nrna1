<template>
  <footer class="trust-badge-bar fixed bottom-0 left-0 right-0 z-30
                 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md
                 border-t-2 border-gray-200 dark:border-gray-700
                 shadow-2xl transform transition-transform duration-300"
          :class="{ 'translate-y-full': isHidden }">

    <div class="max-w-6xl mx-auto px-4 md:px-6 py-3 md:py-4">
      <!-- Content -->
      <div class="flex items-center justify-between gap-4 flex-wrap md:flex-nowrap">

        <!-- Left: Trust message -->
        <div class="flex items-center gap-3">
          <span class="text-lg md:text-2xl">🛡️</span>
          <div class="flex-1">
            <p class="text-xs md:text-sm font-semibold text-gray-900 dark:text-white">
              {{ $t('trust_badge.message', { fallback: 'Your data is safe with us' }) }}
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-400">
              {{ $t('trust_badge.subtitle', { fallback: 'Enterprise-grade security & GDPR compliance' }) }}
            </p>
          </div>
        </div>

        <!-- Center: Badges -->
        <div class="flex gap-2 md:gap-3 flex-wrap justify-center md:justify-start">
          <!-- GDPR Badge -->
          <button @click="showTooltip('gdpr')"
                  class="inline-flex items-center gap-1.5 px-2.5 md:px-3 py-1.5 md:py-2
                         bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700
                         rounded-full hover:bg-green-200 dark:hover:bg-green-900/50
                         transition-colors group relative cursor-help">
            <span class="text-sm md:text-base">✓</span>
            <span class="text-xs md:text-sm font-semibold text-green-700 dark:text-green-400">GDPR</span>

            <!-- Tooltip -->
            <div class="hidden group-hover:block absolute bottom-full mb-2 left-0
                        bg-gray-900 dark:bg-white text-white dark:text-gray-900
                        text-xs px-2 py-1 rounded whitespace-nowrap">
              GDPR Compliant
            </div>
          </button>

          <!-- Data Hosted Badge -->
          <button @click="showTooltip('data')"
                  class="inline-flex items-center gap-1.5 px-2.5 md:px-3 py-1.5 md:py-2
                         bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-700
                         rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/50
                         transition-colors group relative cursor-help">
            <span class="text-sm md:text-base">🇩🇪</span>
            <span class="text-xs md:text-sm font-semibold text-blue-700 dark:text-blue-400">Germany</span>

            <div class="hidden group-hover:block absolute bottom-full mb-2 left-0
                        bg-gray-900 dark:bg-white text-white dark:text-gray-900
                        text-xs px-2 py-1 rounded whitespace-nowrap">
              Data Hosted in Germany
            </div>
          </button>

          <!-- Encryption Badge -->
          <button @click="showTooltip('encryption')"
                  class="inline-flex items-center gap-1.5 px-2.5 md:px-3 py-1.5 md:py-2
                         bg-purple-100 dark:bg-purple-900/30 border border-purple-300 dark:border-purple-700
                         rounded-full hover:bg-purple-200 dark:hover:bg-purple-900/50
                         transition-colors group relative cursor-help">
            <span class="text-sm md:text-base">🔐</span>
            <span class="text-xs md:text-sm font-semibold text-purple-700 dark:text-purple-400">E2E</span>

            <div class="hidden group-hover:block absolute bottom-full mb-2 left-0
                        bg-gray-900 dark:bg-white text-white dark:text-gray-900
                        text-xs px-2 py-1 rounded whitespace-nowrap">
              End-to-End Encryption
            </div>
          </button>
        </div>

        <!-- Right: Action buttons -->
        <div class="flex gap-2">
          <!-- Learn more link -->
          <button @click="openComplianceCenter"
                  class="px-3 md:px-4 py-1.5 md:py-2 text-xs md:text-sm font-semibold
                         text-blue-600 dark:text-blue-400
                         hover:text-blue-700 dark:hover:text-blue-300
                         transition-colors">
            Learn →
          </button>

          <!-- Dismiss button -->
          <button @click="dismiss"
                  class="px-2 md:px-3 py-1.5 md:py-2 text-gray-600 dark:text-gray-400
                         hover:text-gray-900 dark:hover:text-gray-200
                         transition-colors">
            ✕
          </button>
        </div>
      </div>
    </div>

    <!-- Expand/Collapse handle (mobile) -->
    <button v-if="isHidden"
            @click="isHidden = false"
            class="fixed bottom-0 left-1/2 transform -translate-x-1/2
                   px-4 py-2 bg-blue-600 text-white font-semibold rounded-t-lg
                   shadow-lg hover:bg-blue-700 transition-colors md:hidden">
      Show Trust Info ↑
    </button>
  </footer>

  <!-- Spacer for fixed footer -->
  <div v-if="!isDismissed" class="h-20 md:h-16"></div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  complianceData: {
    type: Object,
    default: () => ({
      gdpr: true,
      dataHostedGermany: true,
      endToEndEncryption: true,
    }),
  },
});

const emit = defineEmits(['open-compliance', 'dismiss']);

const isHidden = ref(false);
const isDismissed = ref(localStorage.getItem('dismiss_trust_bar') === 'true');

const openComplianceCenter = () => {
  emit('open-compliance');
};

const dismiss = () => {
  isDismissed.value = true;
  localStorage.setItem('dismiss_trust_bar', 'true');
  emit('dismiss');
};

const showTooltip = (type) => {
  // Emit event for analytics
  if (window.gtag) {
    window.gtag('event', 'trust_badge_click', {
      event_category: 'trust_engagement',
      badge_type: type,
    });
  }
};
</script>

<style scoped>
.trust-badge-bar {
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
}

@media (max-width: 768px) {
  .trust-badge-bar {
    bottom: auto;
    top: 0;
    border-top: none;
    border-bottom: 2px solid;
  }
}

@media (prefers-reduced-motion: reduce) {
  .trust-badge-bar {
    transition: none !important;
  }

  button {
    transform: none !important;
  }
}
</style>
