<template>
  <section class="relative bg-gradient-to-b from-blue-50 to-white py-16 md:py-24">
    <!-- Background Pattern -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-10 right-10 w-64 h-64 bg-blue-100 rounded-full opacity-10"></div>
      <div class="absolute bottom-10 left-10 w-64 h-64 bg-blue-100 rounded-full opacity-10"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-50 rounded-full opacity-5"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 lg:px-8 relative z-10">
      <div class="max-w-6xl mx-auto">
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-start">
          <!-- Left Column - Accessible Election Notice Board -->
          <div class="relative pb-16" role="region" :aria-label="$t('pages.welcome.hero.election-board.aria-label')">
            <!-- Election Notice Board - SIMPLIFIED -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">

              <!-- Browser-style Header -->
              <div class="bg-gray-800 px-6 py-3 flex items-center justify-between">
                <div class="flex space-x-2">
                  <div class="w-3 h-3 bg-red-400 rounded-full" aria-hidden="true"></div>
                  <div class="w-3 h-3 bg-yellow-400 rounded-full" aria-hidden="true"></div>
                  <div class="w-3 h-3 bg-green-400 rounded-full" aria-hidden="true"></div>
                </div>
                <h2 class="text-white text-lg font-bold flex-1 text-center" id="election-board-heading">
                  {{ $t('pages.welcome.hero.election-board.title') }}
                </h2>
                <div class="w-12"></div>
              </div>

              <!-- Board Content -->
              <div class="p-6">

                <!-- Elections List - SIMPLE -->
                <div class="space-y-4">

                  <!-- Demo Election -->
                  <a href="/election/demo/start"
                     class="block p-4 bg-green-50 rounded-lg border border-green-200 hover:border-green-400 transition-colors">
                    <div class="flex justify-between items-start">
                      <div>
                        <div class="font-bold text-gray-900">🧪 {{ $t('pages.welcome.hero.election-board.demo.title') }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $t('pages.welcome.hero.election-board.demo.description') }}</div>
                      </div>
                      <div class="text-green-700 font-medium whitespace-nowrap ml-2">{{ $t('pages.welcome.hero.election-board.demo.cta') }} →</div>
                    </div>
                  </a>

                  <!-- Berlin NRNA -->
                  <a href="/election/berlin-nrna"
                     class="block p-4 bg-orange-50 rounded-lg border border-orange-200 hover:border-orange-400 transition-colors">
                    <div class="flex justify-between items-start">
                      <div>
                        <div class="font-bold text-gray-900">🇩🇪 {{ $t('pages.welcome.hero.election-board.berlin.title') }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $t('pages.welcome.hero.election-board.berlin.date-display') }} • {{ $t('pages.welcome.hero.election-board.berlin.voters') }}</div>
                      </div>
                      <div class="text-blue-700 font-medium whitespace-nowrap ml-2">{{ $t('pages.welcome.hero.election-board.berlin.cta') }} →</div>
                    </div>
                  </a>

                  <!-- Munich Indian -->
                  <a href="/election/munich-indian"
                     class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-gray-400 transition-colors">
                    <div class="flex justify-between items-start">
                      <div>
                        <div class="font-bold text-gray-900">🇮🇳 {{ $t('pages.welcome.hero.election-board.munich.title') }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $t('pages.welcome.hero.election-board.munich.date-display') }} • {{ $t('pages.welcome.hero.election-board.munich.turnout') }}</div>
                      </div>
                      <div class="text-gray-700 font-medium whitespace-nowrap ml-2">{{ $t('pages.welcome.hero.election-board.munich.cta') }} →</div>
                    </div>
                  </a>
                </div>

                <!-- Language Support - SIMPLE -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                  <div class="text-sm text-gray-700 mb-2">{{ $t('pages.welcome.hero.election-board.languages.title') }}</div>
                  <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">🇩🇪 {{ $t('pages.welcome.hero.election-board.languages.german') }}</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">🇬🇧 {{ $t('pages.welcome.hero.election-board.languages.english') }}</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">🇳🇵 {{ $t('pages.welcome.hero.election-board.languages.nepali') }}</span>
                  </div>
                </div>
              </div>
            </div>

          </div>
          
          <!-- Right Column - Main Content -->
          <div class="mb-12 lg:mb-0">
            <!-- Badge -->
            <div class="inline-flex items-center mb-6 px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              {{ $t('pages.welcome.hero.badge') }}
            </div>

            <!-- Main Heading -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 leading-tight">
              {{ $t('pages.welcome.hero.title') }}
            </h1>

            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-gray-600 mb-8 leading-relaxed">
              {{ $t('pages.welcome.hero.subtitle') }}
            </p>

            <!-- Feature Badges -->
            <div class="grid grid-cols-2 gap-4 mb-10">
              <div v-for="(badge, index) in badges" :key="index" class="flex items-center">
                <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                  <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <span class="text-gray-700 font-medium">{{ badge.label }}</span>
              </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
              <!-- Try Demo Button -->
              <a
                href="/election/demo/start"
                class="inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white font-bold text-lg rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition-colors shadow-lg min-h-[44px]"
                title="Try demo election without registration"
              >
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.5 1.5H19a.5.5 0 01.5.5v8a.5.5 0 01-.5.5h-8.5V19a.5.5 0 01-.5.5H1a.5.5 0 01-.5-.5v-8a.5.5 0 01.5-.5H9V2a.5.5 0 01.5-.5z"/>
                </svg>
                {{ $t('pages.welcome.hero.cta_demo', 'Demo versuchen') }}
              </a>

              <!-- Schedule Demo Button -->
              <a
                href="/register"
                class="inline-flex items-center justify-center px-8 py-4 border-2 border-blue-800 text-blue-800 font-bold text-lg rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-colors min-h-[44px]"
                title="Schedule a personalized demo"
              >
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"/>
                  <path fill-rule="evenodd" d="M2 13a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2z" clip-rule="evenodd"/>
                </svg>
                {{ $t('pages.welcome.hero.cta_register', 'Als Kunde registieren') }}
              </a>
            </div>
          </div>

          
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { Link as InertiaLink } from '@inertiajs/inertia-vue3';

export default {
  name: 'HeroSection',
  components: {
    InertiaLink,
  },
  props: {
    badges: {
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
  section {
    background: linear-gradient(180deg, #ffffff, #ffffff) !important;
    border: 2px solid #000000 !important;
  }

  .bg-blue-50 {
    background: #e0e7ff !important;
    border: 2px solid #000000 !important;
  }

  .text-gray-900 {
    color: #000000 !important;
  }

  .border-blue-200,
  .border-gray-200 {
    border-color: #000000 !important;
    border-width: 2px !important;
  }

  .bg-gray-100 {
    background: #ffffff !important;
    border: 2px solid #000000 !important;
  }
}
</style>
