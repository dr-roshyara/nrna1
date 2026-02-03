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
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
          <!-- Left Column - Main Content -->
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
              <inertia-link
                :href="route('register')"
                class="inline-flex items-center justify-center px-8 py-4 bg-blue-800 text-white font-bold text-lg rounded-lg hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-colors shadow-lg min-h-[44px]"
              >
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                </svg>
                {{ $t('pages.welcome.hero.cta_primary') }}
              </inertia-link>

              <a
                href="/election/demo/start"
                class="inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white font-bold text-lg rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition-colors shadow-lg min-h-[44px]"
                title="Try demo election without registration"
              >
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.5 1.5H19a.5.5 0 01.5.5v8a.5.5 0 01-.5.5h-8.5V19a.5.5 0 01-.5.5H1a.5.5 0 01-.5-.5v-8a.5.5 0 01.5-.5H9V2a.5.5 0 01.5-.5z"/>
                </svg>
                🎯 {{ $t('pages.welcome.hero.cta_demo', 'Try Demo') }}
              </a>

              <a
                href="#how-it-works"
                class="inline-flex items-center justify-center px-8 py-4 border-2 border-blue-800 text-blue-800 font-bold text-lg rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-colors min-h-[44px]"
              >
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                </svg>
                {{ $t('pages.welcome.hero.cta_secondary') }}
              </a>
            </div>
          </div>

          <!-- Right Column - Dashboard Mockup -->
          <div class="relative pb-16">
            <!-- Dashboard Mockup -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200">
              <!-- Browser Header -->
              <div class="bg-gray-800 px-6 py-4 flex items-center">
                <div class="flex space-x-2">
                  <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                  <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                  <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                </div>
                <div class="text-white text-sm font-medium mx-auto">
                  Election Dashboard
                </div>
              </div>

              <!-- Dashboard Content -->
              <div class="p-6">
                <!-- Stats Row -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                  <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-700">2.3K</div>
                    <div class="text-sm text-gray-600">Registered Voters</div>
                  </div>
                  <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-700">85%</div>
                    <div class="text-sm text-gray-600">Participation</div>
                  </div>
                </div>

                <!-- Progress Chart -->
                <div class="mb-6">
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Live Results</span>
                    <span class="text-xs text-gray-500">Real-time</span>
                  </div>
                  <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: 65%"></div>
                  </div>
                </div>

                <!-- Language Support -->
                <div>
                  <div class="text-sm font-medium text-gray-700 mb-3">Multilingual Elections</div>
                  <div class="flex space-x-2">
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">🇩🇪 Deutsch</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">🇬🇧 English</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">🇳🇵 नेपाली</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Floating Security Badge -->
            <div class="absolute -bottom-12 -left-4 bg-white px-6 py-3 rounded-lg shadow-lg border border-gray-200">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                  <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0110 1.944 11.954 11.954 0 0117.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div>
                  <div class="font-bold text-gray-900">{{ $t('pages.welcome.hero.security_badge_title') }}</div>
                  <div class="text-sm text-gray-600">{{ $t('pages.welcome.hero.security_badge_subtitle') }}</div>
                </div>
              </div>
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
