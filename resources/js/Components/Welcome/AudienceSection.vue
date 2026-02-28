<template>
  <section class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
      <h2 class="text-3xl md:text-4xl font-bold text-center text-blue-900 mb-4">
        {{ $t('audience.for_organisations') }}
      </h2>
      <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
        {{ $t('platform.description') }}
      </p>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- NGOs Card -->
        <div class="bg-blue-50 rounded-2xl p-8 border border-blue-200 hover:shadow-lg transition-shadow">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
            <span class="text-2xl">🏛️</span>
          </div>
          <h3 class="text-xl font-bold text-blue-900 mb-4">
            {{ $t('audience.ngos') }}
          </h3>
          <ul class="space-y-3 text-gray-700">
            <li class="flex items-start">
              <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>{{ $t('audience.ngos_item1') }}</span>
            </li>
            <li class="flex items-start">
              <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>{{ $t('audience.ngos_item2') }}</span>
            </li>
          </ul>
        </div>

        <!-- Diaspora Card -->
        <div class="bg-blue-50 rounded-2xl p-8 border border-blue-200 hover:shadow-lg transition-shadow">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
            <span class="text-2xl">🌍</span>
          </div>
          <h3 class="text-xl font-bold text-blue-900 mb-4">
            {{ $t('audience.diaspora') }}
          </h3>
          <ul class="space-y-3 text-gray-700">
            <li class="flex items-start">
              <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>{{ $t('audience.diaspora_item1') }}</span>
            </li>
            <li class="flex items-start">
              <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>Multi-language & timezone support</span>
            </li>
          </ul>
        </div>

        <!-- Associations Card -->
        <div class="bg-blue-50 rounded-2xl p-8 border border-blue-200 hover:shadow-lg transition-shadow">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
            <span class="text-2xl">🤝</span>
          </div>
          <h3 class="text-xl font-bold text-blue-900 mb-4">
            {{ $t('audience.associations') }}
          </h3>
          <ul class="space-y-3 text-gray-700">
            <li class="flex items-start">
              <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>{{ $t('audience.associations_item1') }}</span>
            </li>
            <li class="flex items-start">
              <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>Flexible pricing & white-label options</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'AudienceSection',
};
</script>
