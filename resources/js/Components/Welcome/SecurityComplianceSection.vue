<template>
  <section class="py-16 md:py-24 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto">
        <!-- Section Header -->
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            {{ $t('pages.welcome.security.title') }}
          </h2>
          <p class="text-xl text-gray-600">
            {{ $t('pages.welcome.security.subtitle') }}
          </p>
        </div>

        <!-- Compliance Cards -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
          <div v-for="(card, index) in cards" :key="index" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:shadow-lg transition-shadow">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">
              {{ card.title }}
            </h3>
            <p class="text-gray-600">
              {{ card.description }}
            </p>
          </div>
        </div>

        <!-- Certifications/Badges -->
        <!-- <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
          <h3 class="text-2xl font-bold text-center text-gray-900 mb-8">{{ $t('pages.welcome.security.certifications_title') }}</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div v-for="(cert, index) in certifications" :key="index" class="text-center">
              <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl font-bold text-blue-800">
                {{ cert.icon }}
              </div>
              <div class="font-bold text-gray-900">{{ cert.label }}</div>
              <div class="text-sm text-gray-600">{{ cert.description }}</div>
            </div>
          </div>
        </div>
         -->
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'SecurityComplianceSection',
  props: {
    cards: {
      type: Array,
      default: () => [],
    },
    certifications: {
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
  .bg-gray-50 {
    background: #ffffff !important;
  }

  .border-gray-200,
  .border-gray-900 {
    border-color: #000000 !important;
    border-width: 2px !important;
  }

  .text-gray-900 {
    color: #000000 !important;
  }

  .bg-blue-50 {
    background: #e0e7ff !important;
    border: 2px solid #000000 !important;
  }
}
</style>
