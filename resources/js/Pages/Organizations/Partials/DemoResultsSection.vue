<template>
  <section
    aria-labelledby="demo-results-heading"
    class="mb-12"
  >
    <div class="mb-6">
      <h2
        id="demo-results-heading"
        class="text-2xl font-bold text-gray-900 dark:text-white mb-2"
      >
        {{ $t('pages.organization-show.demo-results.title') }}
      </h2>
      <p class="text-gray-600 dark:text-gray-400 max-w-3xl">
        {{ $t('pages.organization-show.demo-results.description') }}
      </p>
    </div>

    <!-- Demo Results Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Organization Demo Results Card -->
      <button
        @click="navigateToDemoResult"
        class="group relative bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 p-6 border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 w-full text-left"
        :aria-label="$t('pages.organization-show.demo-results.org-demo-aria')"
      >
        <!-- Badge -->
        <div class="absolute top-4 right-4">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
            {{ $t('pages.organization-show.demo-results.mode-2') }}
          </span>
        </div>

        <!-- Icon -->
        <div class="mb-4">
          <div class="inline-flex p-3 bg-purple-100 dark:bg-purple-900 rounded-lg group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
          </div>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
          {{ $t('pages.organization-show.demo-results.org-demo-title') }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
          {{ $t('pages.organization-show.demo-results.org-demo-desc') }}
        </p>

        <!-- Features List -->
        <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-2 mb-4">
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            {{ $t('pages.organization-show.demo-results.feature-org-scoped') }}
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            {{ $t('pages.organization-show.demo-results.feature-download-pdf') }}
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            {{ $t('pages.organization-show.demo-results.feature-print') }}
          </li>
        </ul>

        <!-- CTA Button -->
        <span class="inline-flex items-center text-sm font-medium text-purple-600 dark:text-purple-400 group-hover:text-purple-700 dark:group-hover:text-purple-300">
          {{ $t('pages.organization-show.demo-results.button-view') }}
          <svg class="ml-2 w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>

        <!-- Hover Overlay -->
        <div class="absolute inset-0 bg-purple-50 dark:bg-purple-900/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity -z-10" aria-hidden="true" />
      </button>

      <!-- Global Demo Results Card -->
      <button
        @click="navigateToGlobalDemoResult"
        class="group relative bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 p-6 border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 w-full text-left"
        :aria-label="$t('pages.organization-show.demo-results.global-demo-aria')"
      >
        <!-- Badge -->
        <div class="absolute top-4 right-4">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            {{ $t('pages.organization-show.demo-results.mode-1') }}
          </span>
        </div>

        <!-- Icon -->
        <div class="mb-4">
          <div class="inline-flex p-3 bg-blue-100 dark:bg-blue-900 rounded-lg group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20H7m6 0v-2c0-.656.126-1.283.356-1.857m0 0a5 5 0 015.898-8.86c.474-.468.666-1.157.289-1.774A6 6 0 0021 9c0 .896-.153 1.755-.476 2.569m0 0c.321.895.476 1.674.476 2.569 0 2.191-.868 4.169-2.276 5.614" />
            </svg>
          </div>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
          {{ $t('pages.organization-show.demo-results.global-demo-title') }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
          {{ $t('pages.organization-show.demo-results.global-demo-desc') }}
        </p>

        <!-- Features List -->
        <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-2 mb-4">
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            {{ $t('pages.organization-show.demo-results.feature-public-demo') }}
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            {{ $t('pages.organization-show.demo-results.feature-accessible-all') }}
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            {{ $t('pages.organization-show.demo-results.feature-mobile-responsive') }}
          </li>
        </ul>

        <!-- CTA Button -->
        <span class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300">
          {{ $t('pages.organization-show.demo-results.button-explore') }}
          <svg class="ml-2 w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>

        <!-- Hover Overlay -->
        <div class="absolute inset-0 bg-blue-50 dark:bg-blue-900/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity -z-10" aria-hidden="true" />
      </button>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
      <div class="flex gap-3">
        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
        </svg>
        <div class="text-sm text-blue-900 dark:text-blue-200">
          <p class="font-medium mb-1">{{ $t('pages.organization-show.demo-results.info-title') }}</p>
          <p class="text-xs opacity-90">{{ $t('pages.organization-show.demo-results.info-description') }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

/**
 * Navigate to organization-scoped demo results (MODE 2)
 */
const navigateToDemoResult = () => {
  window.location.href = '/demo/result'
}

/**
 * Navigate to global public demo results (MODE 1)
 */
const navigateToGlobalDemoResult = () => {
  window.location.href = '/demo/global/result'
}
</script>
