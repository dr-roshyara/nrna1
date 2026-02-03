<template>
  <section id="how-it-works" class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
          {{ $t('pages.welcome.how_it_works.title') }}
        </h2>
        <p class="text-xl text-gray-600">
          {{ $t('pages.welcome.how_it_works.subtitle') }}
        </p>
      </div>

      <!-- Timeline -->
      <div class="relative">
        <!-- Vertical line for timeline (desktop only) -->
        <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-blue-200"></div>

        <!-- Steps -->
        <div class="space-y-12 md:space-y-0">
          <div v-for="(step, index) in steps" :key="index" class="md:flex items-center" :class="{ 'md:flex-row-reverse': index % 2 === 1 }">
            <!-- Left content (odd steps on desktop) -->
            <div class="md:w-1/2" :class="{ 'md:pr-12 md:text-right': index % 2 === 0, 'md:pl-12': index % 2 === 1 }">
              <div class="inline-block">
                <!-- Step number circle -->
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-800 rounded-full text-2xl font-bold mb-4" :class="[index % 2 === 0 ? 'md:float-right' : 'md:float-left']">
                  {{ step.number }}
                </div>

                <!-- Step title and description -->
                <h3 class="text-2xl font-bold text-gray-900 mb-3">
                  {{ step.title }}
                </h3>
                <p class="text-gray-600">
                  {{ step.description }}
                </p>
              </div>
            </div>

            <!-- Center dot -->
            <div class="hidden md:flex justify-center md:w-8 my-8 md:my-0">
              <div class="w-8 h-8 bg-blue-800 rounded-full border-4 border-white"></div>
            </div>

            <!-- Right content (even steps on desktop) -->
            <div class="md:w-1/2 mt-8 md:mt-0" :class="{ 'md:pl-12': index % 2 === 0, 'md:pr-12': index % 2 === 1 }">
              <!-- Empty on desktop, content shown on mobile above -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'HowItWorksSection',
  props: {
    steps: {
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
  .bg-blue-100 {
    background: #e0e7ff !important;
    border: 2px solid #000000 !important;
  }

  .bg-blue-600 {
    background: #1e40af !important;
    border: 2px solid #ffffff !important;
  }

  .text-gray-900 {
    color: #000000 !important;
  }
}
</style>
