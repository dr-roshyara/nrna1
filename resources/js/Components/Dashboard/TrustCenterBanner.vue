<template>
  <section class="trust-center-banner group relative w-full overflow-hidden">
    <!-- Animated background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-transparent to-green-50
                dark:from-blue-950/30 dark:via-transparent dark:to-green-950/30
                opacity-0 group-hover:opacity-100 transition-opacity duration-500">
    </div>

    <div class="relative px-6 md:px-8 lg:px-10 py-8 md:py-10 max-w-5xl">
      <!-- Header with animated shield -->
      <div class="flex items-start gap-4 md:gap-6">
        <!-- Animated shield icon -->
        <div class="flex-shrink-0">
          <div class="relative w-16 h-16 md:w-20 md:h-20">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-500
                        rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity blur-lg">
            </div>
            <div class="relative flex items-center justify-center w-full h-full
                        bg-gradient-to-br from-blue-100 to-blue-50
                        dark:from-blue-900/40 dark:to-blue-800/30
                        rounded-2xl group-hover:scale-110 transition-transform duration-300">
              <span class="text-2xl md:text-3xl transform group-hover:rotate-12 transition-transform duration-300">
                🛡️
              </span>
              <!-- Verification badge -->
              <div class="absolute -bottom-1 -right-1 bg-green-500 text-white text-xs font-bold
                          rounded-full w-5 h-5 md:w-6 md:h-6 flex items-center justify-center
                          shadow-lg border-2 border-white dark:border-gray-900">
                ✓
              </div>
            </div>
          </div>
        </div>

        <!-- Title and description -->
        <div class="flex-1 pt-1">
          <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2
                     group-hover:text-blue-700 dark:group-hover:text-blue-400
                     transition-colors duration-300">
            {{ $t('trust_center.title', { org: 'PublicDigit' }) }}
          </h2>

          <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base leading-relaxed">
            {{ $t('trust_center.description') }}
          </p>

          <!-- Compliance badges row -->
          <div class="flex flex-wrap gap-2 md:gap-3 mt-4">
            <!-- GDPR badge -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 md:px-4 md:py-2
                        bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800
                        rounded-full group-hover:border-green-400 dark:group-hover:border-green-600
                        transition-colors duration-300 cursor-help"
                 :title="$t('compliance.gdpr_tooltip')">
              <span class="text-lg">🇪🇺</span>
              <span class="text-xs md:text-sm font-semibold text-green-700 dark:text-green-400">
                GDPR
              </span>
            </div>

            <!-- Data hosted badge -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 md:px-4 md:py-2
                        bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800
                        rounded-full group-hover:border-blue-400 dark:group-hover:border-blue-600
                        transition-colors duration-300 cursor-help"
                 :title="$t('compliance.data_hosted_tooltip')">
              <span class="text-lg">🇩🇪</span>
              <span class="text-xs md:text-sm font-semibold text-blue-700 dark:text-blue-400">
                {{ $t('compliance.data_hosted_label') }}
              </span>
            </div>

            <!-- E2E Encryption badge -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 md:px-4 md:py-2
                        bg-purple-100 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-800
                        rounded-full group-hover:border-purple-400 dark:group-hover:border-purple-600
                        transition-colors duration-300 cursor-help"
                 :title="$t('compliance.encryption_tooltip')">
              <span class="text-lg">🔐</span>
              <span class="text-xs md:text-sm font-semibold text-purple-700 dark:text-purple-400">
                E2E
              </span>
            </div>
          </div>
        </div>

        <!-- Action button -->
        <button @click="openComplianceCenter"
                class="flex-shrink-0 px-4 md:px-6 py-2.5 md:py-3 mt-2
                       bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600
                       text-white font-semibold rounded-lg
                       transition-all duration-300
                       hover:shadow-lg hover:scale-105 active:scale-95
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                       dark:focus:ring-offset-gray-900">
          <span class="hidden md:inline">{{ $t('trust_center.learn_more') }}</span>
          <span class="md:hidden">→</span>
        </button>
      </div>

      <!-- Trust score progress bar -->
      <div class="mt-6 md:mt-8 pt-6 md:pt-8 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
            {{ $t('trust_center.trust_level_label') }}
          </h3>
          <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
            {{ trustScorePercentage }}%
          </span>
        </div>

        <!-- Animated progress bar -->
        <div class="relative w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
          <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-blue-500 via-green-500 to-green-400
                      rounded-full transition-all duration-1000 ease-out"
               :style="{ width: trustScorePercentage + '%' }">
          </div>
        </div>

        <!-- Trust level text -->
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
          {{ trustLevelLabel }}
        </p>
      </div>
    </div>

    <!-- Expandable DPO Contact Card (Optional) -->
    <transition name="slide-down">
      <div v-if="showDpoInfo"
           class="border-t border-gray-200 dark:border-gray-700 px-6 md:px-8 lg:px-10 py-6 md:py-8
                  bg-gradient-to-r from-gray-50 to-transparent
                  dark:from-gray-800/50 dark:to-transparent">

        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4
                   flex items-center gap-2">
          <span class="text-lg">👤</span>
          {{ $t('trust_center.dpo_title') }}
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <a :href="`mailto:${dpoEmail}`"
             class="flex items-center gap-3 p-4 bg-white dark:bg-gray-700 rounded-lg
                    border border-gray-200 dark:border-gray-600
                    hover:border-blue-400 dark:hover:border-blue-600
                    transition-colors group">
            <div class="text-2xl">✉️</div>
            <div>
              <p class="text-xs text-gray-600 dark:text-gray-400">{{ $t('trust_center.email') }}</p>
              <p class="text-sm font-semibold text-gray-900 dark:text-white
                       group-hover:text-blue-600 dark:group-hover:text-blue-400">
                {{ dpoEmail }}
              </p>
            </div>
          </a>

          <div class="flex items-center gap-3 p-4 bg-white dark:bg-gray-700 rounded-lg
                      border border-gray-200 dark:border-gray-600">
            <div class="text-2xl">🏛️</div>
            <div>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                {{ $t('trust_center.authority') }}
              </p>
              <p class="text-sm font-semibold text-gray-900 dark:text-white">
                Berlin Data Protection
              </p>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Toggle DPO Info button -->
    <button @click="showDpoInfo = !showDpoInfo"
            class="w-full px-6 py-3 text-sm font-medium text-gray-600 dark:text-gray-400
                   hover:text-gray-900 dark:hover:text-gray-200
                   border-t border-gray-200 dark:border-gray-700
                   hover:bg-gray-50 dark:hover:bg-gray-800/50
                   transition-colors duration-300
                   flex items-center justify-center gap-2">
      <span>{{ showDpoInfo ? $t('common.hide') : $t('common.show') }}</span>
      <span class="transform transition-transform duration-300"
            :class="{ 'rotate-180': showDpoInfo }">
        ▼
      </span>
    </button>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';

const showDpoInfo = ref(false);

const props = defineProps({
  userState: {
    type: Object,
    default: () => ({}),
  },
  complianceData: {
    type: Object,
    default: () => ({
      gdprCompliant: true,
      trustScore: 95,
      dataProtectionOfficer: 'compliance@publicdigit.eu',
    }),
  },
});

const emit = defineEmits(['open-compliance-center']);

// Computed: Trust score percentage
const trustScorePercentage = computed(() => {
  return props.complianceData?.trustScore || 95;
});

// Computed: Trust level label
const trustLevelLabel = computed(() => {
  const score = trustScorePercentage.value;
  if (score >= 90) return 'Excellent - Fully compliant with all standards';
  if (score >= 75) return 'Good - High compliance level';
  if (score >= 60) return 'Satisfactory - Core compliance met';
  return 'Needs improvement';
});

// Computed: DPO Email
const dpoEmail = computed(() => {
  return props.complianceData?.dataProtectionOfficer || 'dpo@publicdigit.eu';
});

// Methods
const openComplianceCenter = () => {
  emit('open-compliance-center');
  if (window.gtag) {
    window.gtag('event', 'open_compliance_center', {
      event_category: 'trust_engagement',
    });
  }
};
</script>

<style scoped>
.trust-center-banner {
  background: linear-gradient(135deg,
    #f8fafc 0%,
    #f0f9ff 100%);
}

.dark .trust-center-banner {
  background: linear-gradient(135deg,
    #0f172a 0%,
    #0c2a47 100%);
}

/* Smooth transitions for expandable content */
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 300ms cubic-bezier(0.34, 1.56, 0.64, 1);
}

.slide-down-enter-from {
  opacity: 0;
  max-height: 0;
}

.slide-down-leave-to {
  opacity: 0;
  max-height: 0;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation: none !important;
    transition: none !important;
  }
}
</style>
