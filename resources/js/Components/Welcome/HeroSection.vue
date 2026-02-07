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
          <!-- Left Column - Accessible Election Notice Board -->
          <div class="relative pb-16" role="region" :aria-label="$t('pages.welcome.election-board.aria-label')">
            <!-- Election Notice Board -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">

              <!-- Board Header -->
              <div class="bg-blue-700 px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center">
                    <div class="text-white text-2xl mr-3" aria-hidden="true">🗳️</div>
                    <h2 class="text-white text-xl font-bold" id="election-board-heading">
                      {{ $t('pages.welcome.election-board.title') }}
                    </h2>
                  </div>
                  <div class="flex items-center" aria-live="polite">
                    <div class="w-3 h-3 bg-green-400 rounded-full mr-2" aria-hidden="true"></div>
                    <span class="text-white text-sm font-medium">{{ $t('pages.welcome.election-board.live') }}</span>
                  </div>
                </div>
                <p class="text-blue-200 text-sm">
                  {{ $t('pages.welcome.election-board.subtitle') }}
                </p>
              </div>

              <!-- Board Content -->
              <div class="p-6">

                <!-- Screen Reader Instructions -->
                <p class="sr-only">
                  {{ $t('pages.welcome.election-board.sr-instructions') }}
                </p>

                <!-- Elections List -->
                <div role="list" aria-labelledby="election-board-heading">
                  <h3 class="sr-only">{{ $t('pages.welcome.election-board.elections-list-title') }}</h3>

                  <!-- Election 1: Demo Election -->
                  <a href="/election/demo/start"
                     class="block mb-4 p-5 bg-gradient-to-r from-green-50 to-white rounded-xl border-2 border-green-300 hover:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all group"
                     role="listitem"
                     :aria-label="$t('pages.welcome.election-board.demo.aria-label')">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                      <div class="flex items-center mb-2 sm:mb-0">
                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3 flex-shrink-0" aria-hidden="true"></div>
                        <h4 class="font-bold text-gray-900 text-lg">{{ $t('pages.welcome.election-board.demo.title') }}</h4>
                      </div>
                      <div class="flex items-center">
                        <time datetime="Permanent" class="text-sm text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                          {{ $t('pages.welcome.election-board.demo.date-display') }}
                        </time>
                      </div>
                    </div>

                    <p class="text-gray-700 mb-3 text-sm">
                      {{ $t('pages.welcome.election-board.demo.description') }}
                    </p>

                    <div class="flex flex-wrap gap-2 mb-3">
                      <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">🌍 {{ $t('pages.welcome.election-board.public') }}</span>
                      <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">⏱️ 5 min</span>
                      <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">🔓 {{ $t('pages.welcome.election-board.no-signup') }}</span>
                    </div>

                    <div class="flex items-center justify-between text-blue-700 font-medium group-hover:text-blue-900 transition-colors">
                      <span>{{ $t('pages.welcome.election-board.demo.cta') }} →</span>
                    </div>
                  </a>

                  <!-- Election 2: Berlin NRNA -->
                  <a href="/election/berlin-nrna"
                     class="block mb-4 p-5 bg-gradient-to-r from-orange-50 to-white rounded-xl border-2 border-orange-300 hover:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all group"
                     role="listitem"
                     :aria-label="$t('pages.welcome.election-board.berlin.aria-label')">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                      <div class="flex items-center mb-2 sm:mb-0">
                        <div class="w-4 h-4 bg-orange-500 rounded-full mr-3 flex-shrink-0" aria-hidden="true"></div>
                        <h4 class="font-bold text-gray-900 text-lg">{{ $t('pages.welcome.election-board.berlin.title') }}</h4>
                      </div>
                      <div class="flex items-center">
                        <time datetime="2024-11-15/2024-11-30" class="text-sm text-gray-700 bg-orange-100 px-3 py-1 rounded-full">
                          {{ $t('pages.welcome.election-board.berlin.date-display') }}
                        </time>
                      </div>
                    </div>

                    <p class="text-gray-700 mb-3 text-sm">
                      {{ $t('pages.welcome.election-board.berlin.description') }}
                    </p>

                    <div class="flex flex-wrap gap-2 mb-3">
                      <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">👥 {{ $t('pages.welcome.election-board.berlin.voters') }}</span>
                      <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">🏛️ NRNA</span>
                      <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">🌐 DE/EN/NP</span>
                    </div>

                    <div class="flex items-center justify-between text-blue-700 font-medium group-hover:text-blue-900 transition-colors">
                      <span>{{ $t('pages.welcome.election-board.berlin.cta') }} →</span>
                    </div>
                  </a>

                  <!-- Election 3: Munich Indian -->
                  <a href="/election/munich-indian"
                     class="block p-5 bg-gradient-to-r from-gray-50 to-white rounded-xl border-2 border-gray-300 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all group"
                     role="listitem"
                     :aria-label="$t('pages.welcome.election-board.munich.aria-label')">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                      <div class="flex items-center mb-2 sm:mb-0">
                        <div class="w-4 h-4 bg-gray-400 rounded-full mr-3 flex-shrink-0" aria-hidden="true"></div>
                        <h4 class="font-bold text-gray-900 text-lg">{{ $t('pages.welcome.election-board.munich.title') }}</h4>
                      </div>
                      <div class="flex items-center">
                        <time datetime="2024-10-01/2024-10-15" class="text-sm text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                          {{ $t('pages.welcome.election-board.munich.date-display') }}
                        </time>
                      </div>
                    </div>

                    <p class="text-gray-700 mb-3 text-sm">
                      {{ $t('pages.welcome.election-board.munich.description') }}
                    </p>

                    <div class="flex flex-wrap gap-2 mb-3">
                      <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">📊 {{ $t('pages.welcome.election-board.munich.turnout') }}</span>
                      <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">✅ {{ $t('pages.welcome.election-board.results') }}</span>
                      <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">👥 {{ $t('pages.welcome.election-board.munich.voters') }}</span>
                    </div>

                    <div class="flex items-center justify-between text-blue-700 font-medium group-hover:text-blue-900 transition-colors">
                      <span>{{ $t('pages.welcome.election-board.munich.cta') }} →</span>
                    </div>
                  </a>
                </div>

                <!-- Quick Stats -->
                <div class="mt-6 grid grid-cols-2 gap-4" role="group" :aria-label="$t('pages.welcome.election-board.stats-aria-label')">
                  <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="text-2xl font-bold text-blue-900">3</div>
                    <div class="text-sm text-gray-700">{{ $t('pages.welcome.election-board.stats.elections') }}</div>
                  </div>
                  <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="text-2xl font-bold text-green-900">5K+</div>
                    <div class="text-sm text-gray-700">{{ $t('pages.welcome.election-board.stats.voters') }}</div>
                  </div>
                </div>

                <!-- Language Support -->
                <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                  <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                    <span class="mr-2" aria-hidden="true">🌐</span>
                    {{ $t('pages.welcome.election-board.languages.title') }}
                  </h4>
                  <div class="flex flex-wrap gap-2" role="list" :aria-label="$t('pages.welcome.election-board.languages.aria-label')">
                    <span class="px-3 py-1 bg-white text-gray-800 rounded-full text-xs border border-gray-300 font-medium" role="listitem">🇩🇪 {{ $t('pages.welcome.election-board.languages.german') }}</span>
                    <span class="px-3 py-1 bg-white text-gray-800 rounded-full text-xs border border-gray-300 font-medium" role="listitem">🇬🇧 {{ $t('pages.welcome.election-board.languages.english') }}</span>
                    <span class="px-3 py-1 bg-white text-gray-800 rounded-full text-xs border border-gray-300 font-medium" role="listitem">🇳🇵 {{ $t('pages.welcome.election-board.languages.nepali') }}</span>
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
