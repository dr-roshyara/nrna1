<template>
  <section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4">

      <!-- Simple Value Grid -->
      <div class="max-w-6xl mx-auto">

        <!-- One Powerful Testimonial -->
        <div v-if="testimonial" class="bg-blue-50 rounded-2xl p-8 mb-12">
          <div class="text-4xl text-blue-300 mb-4">"</div>
          <p class="text-xl text-gray-700 mb-6">
            "{{ testimonial.quote }}"
          </p>
          <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
              <span class="font-bold text-blue-700">{{ getInitials(testimonial.author) }}</span>
            </div>
            <div>
              <div class="font-bold text-gray-900">{{ testimonial.author }}</div>
              <div class="text-sm text-gray-600">{{ testimonial.role }}, {{ testimonial.organization }}</div>
            </div>
          </div>
        </div>

        <!-- Simple Feature Grid -->
        <div class="grid md:grid-cols-2 gap-8">

          <!-- Left: Why PUBLIC DIGIT? -->
          <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $t('pages.welcome.value_proposition.why_title') }}</h3>
            <div class="space-y-4">
              <div v-for="(feature, index) in features" :key="index" class="flex items-start">
                <div class="shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                  <span class="text-lg">{{ feature.icon }}</span>
                </div>
                <div>
                  <div class="font-bold text-gray-900">{{ feature.title }}</div>
                  <div class="text-gray-600">{{ feature.description }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: For Whom? -->
          <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $t('pages.welcome.value_proposition.for_whom_title') }}</h3>
            <div class="space-y-3">
              <div v-for="(orgType, index) in orgTypes" :key="index" class="flex items-center px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="text-2xl mr-3">{{ orgType.icon }}</span>
                <span class="font-medium text-gray-900">{{ orgType.name }}</span>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>
</template>

<script>
export default {
  name: 'ValuePropositionSection',
  props: {
    features: {
      type: Array,
      required: true,
    },
    orgTypes: {
      type: Array,
      required: true,
    },
    testimonial: {
      type: Object,
      default: null,
    },
  },
  methods: {
    /**
     * Generate initials from a name
     * @param {string} name - Full name
     * @returns {string} Initials (max 2 characters)
     */
    getInitials(name) {
      return name
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .slice(0, 2);
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

/* Smooth transitions */
div {
  transition-property: background-color, border-color, color;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* High contrast mode */
@media (prefers-contrast: high) {
  section {
    background: #ffffff !important;
    border: 2px solid #000000;
  }

  .bg-blue-50 {
    background: #e0e7ff !important;
    border: 2px solid #000000 !important;
  }

  .text-gray-900 {
    color: #000000 !important;
  }

  .bg-gray-50 {
    background: #ffffff !important;
    border: 2px solid #000000 !important;
  }
}
</style>
