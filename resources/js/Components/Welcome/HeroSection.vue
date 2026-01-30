<template>
  <section class="relative bg-gradient-to-b from-blue-50 to-white py-16 md:py-24 lg:py-32">
    <!-- Official decorative elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-10 right-10 w-64 h-64 bg-blue-100 rounded-full opacity-20"></div>
      <div class="absolute bottom-10 left-10 w-64 h-64 bg-blue-100 rounded-full opacity-20"></div>
    </div>

    <div class="container mx-auto px-4 md:px-6 lg:px-8 relative z-10">
      <div class="text-center max-w-4xl mx-auto">
        <!-- Official Badge -->
        <div class="inline-flex items-center justify-center mb-6 px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
          <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          {{ $t('platform.name') }} - {{ $t('platform.tagline') }}
        </div>

        <!-- Main Heading - Mobile-First Scaling -->
        <div class="mb-8 md:mb-10 px-4">
          <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-blue-900 mb-3 md:mb-4 leading-tight">
            {{ $t('welcome.title') }}
          </h1>
          <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-semibold text-blue-700 leading-relaxed">
            {{ $t('welcome.subtitle') }}
          </p>
        </div>

        <!-- Description - Mobile-Optimized -->
        <div class="mb-12 md:mb-16 mx-4 md:mx-6 lg:mx-0 px-4 md:px-6 py-6 md:py-8 bg-white rounded-lg border border-blue-200 shadow-sm max-w-3xl mx-auto">
          <p class="text-base sm:text-lg md:text-xl text-gray-800 leading-relaxed font-medium">
            {{ $t('platform.description') }}
          </p>
        </div>

        <!-- Stats Section - Mobile-First, Touch-Friendly -->
        <div class="flex flex-col sm:flex-row sm:flex-wrap justify-center gap-4 mb-14 w-full max-w-4xl mx-auto px-4">
          <!-- Stat 1: Registered Voters -->
          <div class="bg-white rounded-lg p-5 md:p-6 border border-blue-200 shadow-sm flex-1 min-w-[140px] sm:min-w-0">
            <div class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-900 mb-2">10,000+</div>
            <p class="text-xs sm:text-sm md:text-base text-gray-700 font-medium leading-tight">Registered Voters</p>
          </div>

          <!-- Stat 2: System Uptime -->
          <div class="bg-white rounded-lg p-5 md:p-6 border border-blue-200 shadow-sm flex-1 min-w-[140px] sm:min-w-0">
            <div class="text-2xl sm:text-3xl md:text-4xl font-bold text-green-700 mb-2">99.8%</div>
            <p class="text-xs sm:text-sm md:text-base text-gray-700 font-medium leading-tight">System Uptime</p>
          </div>

          <!-- Stat 3: Security Certified -->
          <div class="bg-white rounded-lg p-5 md:p-6 border border-blue-200 shadow-sm flex-1 min-w-[140px] sm:min-w-0">
            <div class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-900 mb-2">ISO 27001</div>
            <p class="text-xs sm:text-sm md:text-base text-gray-700 font-medium leading-tight">Security Certified</p>
          </div>
        </div>

        <!-- CTA Buttons - Mobile-Friendly Touch Targets -->
        <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center items-stretch sm:items-center mb-12 px-4">
          <!-- Primary CTA - Min 44px height for mobile touch -->
          <a
            :href="route('login')"
            class="inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 min-h-[44px] bg-blue-900 text-white font-bold text-sm md:text-lg rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-colors shadow-sm md:shadow-lg"
          >
            <svg class="w-5 md:w-6 h-5 md:h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
            </svg>
            {{ $t('cta.start_voting_now') }}
          </a>

          <!-- Secondary CTA - Min 44px height for mobile touch -->
          <a
            href="#how-it-works"
            class="inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 min-h-[44px] border-2 border-blue-900 text-blue-900 font-bold text-sm md:text-lg rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-colors"
          >
            <svg class="w-5 md:w-6 h-5 md:h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            {{ $t('cta.how_it_works') }}
          </a>
        </div>

        <!-- Security Badges -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 max-w-2xl mx-auto">
          <p class="text-sm text-blue-800 font-semibold mb-4">{{ $t('common.trust_security') }}</p>
          <div class="flex flex-wrap justify-center gap-4 md:gap-8">
            <div class="flex items-center text-blue-700">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                <span class="text-green-600 font-bold">✓</span>
              </div>
              <span class="text-sm font-medium">{{ $t('trust.gdpr') }}</span>
            </div>
            <div class="flex items-center text-blue-700">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                <span class="text-green-600 font-bold">✓</span>
              </div>
              <span class="text-sm font-medium">{{ $t('trust.german_hosting') }}</span>
            </div>
            <div class="flex items-center text-blue-700">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                <span class="text-green-600 font-bold">✓</span>
              </div>
              <span class="text-sm font-medium">{{ $t('trust.encryption') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'HeroSection',
};
</script>
