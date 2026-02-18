<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
    <!-- Breadcrumb Schema for SEO -->
    <BreadcrumbSchema />

    <!-- Header Section -->
    <section class="relative py-16 md:py-24 lg:py-32 bg-blue-900 text-white">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-4 md:mb-6 leading-tight">
            {{ $t('pages.pricing.title') }}
          </h1>
          <p class="text-lg sm:text-xl md:text-2xl opacity-90 leading-relaxed">
            {{ $t('pages.pricing.subtitle') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Description -->
    <section class="py-8 md:py-12 bg-white border-b border-blue-100">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <p class="text-center text-base md:text-lg text-gray-700 max-w-2xl mx-auto">
          {{ $t('pages.pricing.description') }}
        </p>
      </div>
    </section>

    <!-- Pricing Plans -->
    <section class="py-16 md:py-24 bg-white">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          <!-- Starter Plan -->
          <div class="flex flex-col border border-blue-200 rounded-2xl p-8 md:p-10 hover:shadow-lg transition-shadow bg-white">
            <h3 class="text-2xl md:text-3xl font-bold text-blue-900 mb-2">
              {{ $t('pages.pricing.plans.starter.name') }}
            </h3>
            <p class="text-gray-600 mb-6">
              {{ $t('pages.pricing.plans.starter.description') }}
            </p>

            <div class="mb-8">
              <div class="flex items-baseline gap-2">
                <span class="text-5xl md:text-6xl font-bold text-blue-900">
                  {{ $t('pages.pricing.plans.starter.price') }}
                </span>
                <span class="text-gray-600">
                  {{ $t('pages.pricing.plans.starter.period') }}
                </span>
              </div>
            </div>

            <ul class="space-y-4 mb-10 flex-grow">
              <li v-for="(feature, index) in starterFeatures" :key="index" class="flex items-start gap-3">
                <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                  <span class="text-blue-600 font-bold text-sm">✓</span>
                </div>
                <span class="text-gray-700">{{ feature }}</span>
              </li>
            </ul>

            <button class="w-full py-3 md:py-4 bg-blue-100 text-blue-900 font-bold rounded-lg hover:bg-blue-200 transition-colors">
              {{ $t('pages.pricing.plans.starter.cta') }}
            </button>
          </div>

          <!-- Professional Plan (Popular) -->
          <div class="flex flex-col border-2 border-blue-900 rounded-2xl p-8 md:p-10 shadow-xl bg-white relative md:scale-105 md:-mt-4">
            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-blue-900 text-white px-4 py-1 rounded-full text-sm font-semibold">
              {{ $t('pages.pricing.plans.professional.badge') }}
            </div>

            <h3 class="text-2xl md:text-3xl font-bold text-blue-900 mb-2">
              {{ $t('pages.pricing.plans.professional.name') }}
            </h3>
            <p class="text-gray-600 mb-6">
              {{ $t('pages.pricing.plans.professional.description') }}
            </p>

            <div class="mb-8">
              <div class="flex items-baseline gap-2">
                <span class="text-5xl md:text-6xl font-bold text-blue-900">
                  {{ $t('pages.pricing.plans.professional.price') }}
                </span>
                <span class="text-gray-600">
                  {{ $t('pages.pricing.plans.professional.period') }}
                </span>
              </div>
            </div>

            <ul class="space-y-4 mb-10 flex-grow">
              <li v-for="(feature, index) in professionalFeatures" :key="index" class="flex items-start gap-3">
                <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                  <span class="text-blue-600 font-bold text-sm">✓</span>
                </div>
                <span class="text-gray-700">{{ feature }}</span>
              </li>
            </ul>

            <button class="w-full py-3 md:py-4 bg-blue-900 text-white font-bold rounded-lg hover:bg-blue-800 transition-colors">
              {{ $t('pages.pricing.plans.professional.cta') }}
            </button>
          </div>

          <!-- Enterprise Plan -->
          <div class="flex flex-col border border-blue-200 rounded-2xl p-8 md:p-10 hover:shadow-lg transition-shadow bg-white">
            <h3 class="text-2xl md:text-3xl font-bold text-blue-900 mb-2">
              {{ $t('pages.pricing.plans.enterprise.name') }}
            </h3>
            <p class="text-gray-600 mb-6">
              {{ $t('pages.pricing.plans.enterprise.description') }}
            </p>

            <div class="mb-8">
              <div class="flex items-baseline gap-2">
                <span class="text-3xl md:text-4xl font-bold text-blue-900">
                  {{ $t('pages.pricing.plans.enterprise.price') }}
                </span>
                <span class="text-gray-600">
                  {{ $t('pages.pricing.plans.enterprise.period') }}
                </span>
              </div>
            </div>

            <ul class="space-y-4 mb-10 flex-grow">
              <li v-for="(feature, index) in enterpriseFeatures" :key="index" class="flex items-start gap-3">
                <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                  <span class="text-blue-600 font-bold text-sm">✓</span>
                </div>
                <span class="text-gray-700">{{ feature }}</span>
              </li>
            </ul>

            <button class="w-full py-3 md:py-4 bg-blue-100 text-blue-900 font-bold rounded-lg hover:bg-blue-200 transition-colors">
              {{ $t('pages.pricing.plans.enterprise.cta') }}
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Included -->
    <section class="py-16 md:py-24 bg-blue-50 border-y border-blue-200">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-blue-900 mb-12">
          {{ $t('pages.pricing.features.title') }}
        </h2>

        <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
          <div v-for="(feature, index) in includedFeatures" :key="index" class="flex items-center gap-4 p-4 bg-white rounded-lg border border-blue-100">
            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
              <span class="text-green-600 font-bold">✓</span>
            </div>
            <span class="text-gray-700 font-medium">{{ feature }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 md:py-24 bg-white">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-blue-900 mb-12">
          {{ $t('pages.pricing.faq.title') }}
        </h2>

        <div class="max-w-2xl mx-auto space-y-6">
          <div v-for="(item, index) in faqQuestions" :key="index" class="border border-blue-200 rounded-lg overflow-hidden">
            <button
              @click="toggleFaq(index)"
              class="w-full px-6 py-4 bg-blue-50 hover:bg-blue-100 transition-colors flex items-center justify-between"
            >
              <span class="font-semibold text-blue-900">{{ item.q }}</span>
              <svg
                :class="['w-5 h-5 transition-transform', openFaq.includes(index) ? 'rotate-180' : '']"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <div v-if="openFaq.includes(index)" class="px-6 py-4 bg-white border-t border-blue-200">
              <p class="text-gray-700">{{ item.a }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-20 bg-gradient-to-r from-blue-900 to-blue-800">
      <div class="container mx-auto px-4 md:px-6 lg:px-8">
        <div class="text-center text-white max-w-2xl mx-auto">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            {{ $t('pages.pricing.title') }}
          </h2>
          <p class="text-lg opacity-90 mb-8">
            {{ $t('pages.pricing.description') }}
          </p>
          <button class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-900 font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-lg min-h-[44px] md:min-h-[56px]">
            {{ $t('pages.pricing.cta.start_trial') }}
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import pricingDe from '../locales/pages/pricing/de.json';
import pricingEn from '../locales/pages/pricing/en.json';
import pricingNp from '../locales/pages/pricing/np.json';
import { useMeta } from '@/composables/useMeta';
import BreadcrumbSchema from '@/Components/BreadcrumbSchema.vue';

export default {
  name: 'Pricing',
  components: {
    BreadcrumbSchema,
  },
  data() {
    return {
      openFaq: [],
      pricingData: {
        de: pricingDe,
        en: pricingEn,
        np: pricingNp
      }
    };
  },
  created() {
    /**
     * SEO Meta Tags for Pricing Page
     *
     * Automatically sets language-aware meta tags based on current locale
     * Reads from 'pricing' page key in i18n translations
     */
    useMeta({ pageKey: 'pricing' });
  },
  computed: {
    currentLocale() {
      return this.$i18n.locale;
    },
    pricing() {
      return this.pricingData[this.currentLocale] || this.pricingData.de;
    },
    starterFeatures() {
      return this.pricing.plans.starter.features || [];
    },
    professionalFeatures() {
      return this.pricing.plans.professional.features || [];
    },
    enterpriseFeatures() {
      return this.pricing.plans.enterprise.features || [];
    },
    includedFeatures() {
      return this.pricing.features.included || [];
    },
    faqQuestions() {
      return this.pricing.faq.questions || [];
    }
  },
  methods: {
    toggleFaq(index) {
      if (this.openFaq.includes(index)) {
        this.openFaq = this.openFaq.filter(i => i !== index);
      } else {
        this.openFaq.push(index);
      }
    }
  }
};
</script>

<style scoped>
/* Accessible focus styles */
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

  .text-blue-900 {
    color: #000000 !important;
  }

  .border-blue-200,
  .border-blue-100 {
    border-color: #000000 !important;
    border-width: 2px !important;
  }
}
</style>
