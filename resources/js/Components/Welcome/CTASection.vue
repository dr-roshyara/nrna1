<template>
  <section class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto text-center">
        <!-- Heading -->
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
          {{ $t('pages.welcome.cta_section.title') }}
        </h2>

        <!-- Description -->
        <p class="text-xl mb-10 text-gray-600">
          {{ $t('pages.welcome.cta_section.description') }}
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
          <!-- Primary CTA -->
          <inertia-link
            :href="route('register')"
            class="inline-flex items-center justify-center px-8 py-4 bg-blue-800 text-white font-bold text-lg rounded-lg hover:bg-blue-900 focus:outline-hidden focus:ring-4 focus:ring-blue-300 transition-colors shadow-lg min-h-[44px]"
          >
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"/>
            </svg>
            {{ $t('pages.welcome.cta_section.btn_register') }}
          </inertia-link>

          <!-- Secondary CTA -->
          <a
            href="#contact"
            class="inline-flex items-center justify-center px-8 py-4 border-2 border-blue-800 text-blue-800 font-bold text-lg rounded-lg hover:bg-blue-50 focus:outline-hidden focus:ring-4 focus:ring-blue-100 transition-colors min-h-[44px]"
          >
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
            </svg>
            {{ $t('pages.welcome.cta_section.btn_demo') }}
          </a>
        </div>

        <!-- Pricing Badge -->
        <p class="text-gray-600 mb-12">{{ $t('pages.welcome.cta_section.pricing_badge') }}</p>

        <!-- Perks -->
        <div class="bg-gray-50 rounded-2xl p-8 border border-gray-200">
          <div class="grid md:grid-cols-3 gap-6">
            <div v-for="(perk, index) in perks" :key="index" class="text-center">
              <div class="text-5xl mb-4">{{ perk.icon }}</div>
              <div class="font-bold text-lg mb-2 text-gray-900">{{ perk.title }}</div>
              <div class="text-sm text-gray-600">{{ perk.description }}</div>
            </div>
          </div>
        </div>

        <!-- Footer Note -->
        <p class="text-sm text-gray-500 mt-8">
          {{ $t('pages.welcome.cta_section.footer_note') }}
        </p>
      </div>
    </div>
  </section>
</template>

<script>
import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
  name: 'CTASection',
  components: {
    InertiaLink,
  },
  props: {
    perks: {
      type: Array,
      default: () => [],
    },
  },
};
</script>

<style scoped>
/* Accessibility focus styles */
a:focus,
button:focus {
  outline: 2px solid #93c5fd;
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
  .bg-white {
    background: #ffffff !important;
  }

  .bg-blue-800 {
    background: #1e40af !important;
    border: 2px solid #ffffff !important;
  }

  .text-gray-900 {
    color: #000000 !important;
  }

  .border-blue-800 {
    border-color: #000000 !important;
    border-width: 2px !important;
  }

  .bg-gray-50 {
    background: #e0e7ff !important;
    border: 2px solid #000000 !important;
  }

  .text-gray-600 {
    color: #000000 !important;
  }
}
</style>
