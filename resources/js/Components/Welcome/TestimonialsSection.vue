<template>
  <section class="py-16 md:py-24 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
          {{ $t('pages.welcome.testimonials.title') }}
        </h2>
        <p class="text-xl text-gray-600">
          {{ $t('pages.welcome.testimonials.subtitle') }}
        </p>
      </div>

      <!-- Testimonials Grid -->
      <div class="grid md:grid-cols-3 gap-8">
        <div v-for="(testimonial, index) in testimonials" :key="index" class="bg-white rounded-2xl border border-blue-200 p-8 shadow-sm hover:shadow-lg transition-shadow">
          <!-- Quotation Mark -->
          <div class="text-5xl text-blue-200 mb-4">"</div>

          <!-- Quote Text -->
          <p class="text-lg italic text-gray-700 mb-6">
            {{ testimonial.quote }}
          </p>

          <!-- Divider -->
          <hr class="border-gray-200 mb-6" />

          <!-- Author Info -->
          <div class="flex items-center">
            <!-- Avatar -->
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
              <span class="font-bold text-blue-700 text-sm">{{ testimonial.avatar }}</span>
            </div>

            <!-- Author Details -->
            <div>
              <p class="font-bold text-gray-900">{{ testimonial.author }}</p>
              <p class="text-sm text-gray-600">{{ testimonial.role }}</p>
              <p class="text-xs text-gray-500">{{ testimonial.organization }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'TestimonialsSection',
  props: {
    testimonials: {
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

  .bg-white {
    background: #ffffff !important;
    border: 2px solid #000000 !important;
  }

  .border-blue-200 {
    border-color: #000000 !important;
  }

  .text-gray-900 {
    color: #000000 !important;
  }

  .bg-blue-100 {
    background: #e0e7ff !important;
    border: 2px solid #000000 !important;
  }

  .text-blue-200 {
    color: #7dd3fc !important;
  }
}
</style>
