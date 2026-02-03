<template>
  <section class="py-16 md:py-24 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
          {{ $t('pages.welcome.ngo_features.title') }}
        </h2>
        <p class="text-xl text-gray-600">
          {{ $t('pages.welcome.ngo_features.subtitle') }}
        </p>
      </div>

      <!-- Feature Cards Grid -->
      <div class="grid md:grid-cols-3 gap-8 mb-16">
        <div v-for="(card, index) in featureCards" :key="index" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:shadow-lg transition-shadow">
          <!-- Icon -->
          <div class="text-5xl mb-6">{{ card.icon }}</div>

          <!-- Title -->
          <h3 class="text-xl font-bold text-gray-900 mb-3">
            {{ card.title }}
          </h3>

          <!-- Description -->
          <p class="text-gray-600 mb-4">
            {{ card.description }}
          </p>

          <!-- Features List -->
          <ul class="space-y-2">
            <li v-for="(feature, fIdx) in card.features" :key="fIdx" class="flex items-start text-sm text-gray-600">
              <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>{{ feature }}</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Organization Types -->
      <div class="border-t border-gray-200 pt-16">
        <h3 class="text-2xl font-bold text-center text-gray-900 mb-12">
          Ideal für verschiedene Organisationstypen
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div v-for="(orgType, index) in orgTypes" :key="index" class="text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">{{ orgType.icon }}</span>
            </div>
            <h4 class="font-bold text-gray-900">{{ orgType.label }}</h4>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'NGOFeaturesSection',
  props: {
    featureCards: {
      type: Array,
      default: () => [],
    },
    orgTypes: {
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
