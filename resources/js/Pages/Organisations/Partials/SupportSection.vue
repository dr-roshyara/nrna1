<template>
  <section
    aria-labelledby="support-heading"
    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-200 overflow-hidden"
  >
    <div class="px-6 py-8 sm:px-8">
      <!-- Title -->
      <h2
        id="support-heading"
        class="text-2xl font-bold text-gray-900 mb-2"
      >
        {{ $t('pages.organisation-show.support.title') }}
      </h2>

      <!-- Description -->
      <p class="text-base text-gray-700 mb-8 max-w-2xl">
        {{ $t('pages.organisation-show.support.description') }}
      </p>

      <!-- Contact Options -->
      <div class="space-y-6">
        <!-- Email Contact -->
        <div class="flex items-start gap-4 p-4 bg-white rounded-lg hover:bg-blue-50 transition-colors focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 rounded-lg">
          <div class="p-2 bg-blue-100 rounded-lg shrink-0 mt-1">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
              {{ $t('pages.organisation-show.support.email_us') }}
            </p>
            <a
              :href="`mailto:${decodedEmailAddress}`"
              :aria-label="`Email: ${decodedEmailAddress}`"
              class="text-blue-600 hover:text-blue-700 font-semibold text-base transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded px-2 py-1"
            >
              {{ decodedEmailAddress }}
            </a>
          </div>
        </div>

        <!-- Phone Contact -->
        <div class="flex items-start gap-4 p-4 bg-white rounded-lg hover:bg-green-50 transition-colors focus-within:ring-2 focus-within:ring-green-500 focus-within:ring-offset-2 rounded-lg">
          <div class="p-2 bg-green-100 rounded-lg shrink-0 mt-1">
            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773c.418 1.02 1.004 2.49 2.986 4.472 1.982 1.982 3.452 2.568 4.472 2.986l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
              {{ $t('pages.organisation-show.support.call_us') }}
            </p>
            <a
              :href="`tel:${phoneNumber}`"
              :aria-label="`Call: ${phoneNumber}`"
              class="text-green-600 hover:text-green-700 font-semibold text-base transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded px-2 py-1"
            >
              {{ phoneNumber }}
            </a>
          </div>
        </div>

        <!-- Support Hours -->
        <div class="flex items-start gap-4 p-4 bg-white rounded-lg">
          <div class="p-2 bg-gray-100 rounded-lg shrink-0 mt-1">
            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-1.414 1.414a1 1 0 001.414 1.414L9 10.414V6z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
              {{ $t('pages.organisation-show.support.hours') }}
            </p>
            <p class="text-base text-gray-700 font-medium">
              {{ $t('pages.organisation-show.support.hours') }}
            </p>
          </div>
        </div>
      </div>

      <!-- Additional Help Links (optional) -->
      <div v-if="showAdditionalLinks" class="mt-8 pt-8 border-t border-blue-200">
        <div class="flex flex-col sm:flex-row gap-3">
          <a
            href="/handbook"
            class="inline-flex items-center justify-center px-6 py-3 border-2 border-blue-300 text-sm font-semibold rounded-lg text-blue-600 hover:bg-blue-100 hover:border-blue-500 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747 0-6.002-4.5-10.747-10-10.747z" />
            </svg>
            {{ $t('pages.organisation-show.support.handbook') }}
          </a>
          <a
            href="/book-webinar"
            class="inline-flex items-center justify-center px-6 py-3 border-2 border-blue-300 text-sm font-semibold rounded-lg text-blue-600 hover:bg-blue-100 hover:border-blue-500 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            {{ $t('pages.organisation-show.support.book_webinar') }}
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

defineProps({
  showAdditionalLinks: {
    type: Boolean,
    default: true
  }
})

/**
 * Decode email address from translation key
 * Replaces HTML entity &#64; with @ to avoid translation system issues
 */
const decodedEmailAddress = computed(() => {
  const email = t('pages.organisation-show.support.email_address')
  return email.replace(/&#64;/g, '@')
})

/**
 * Phone number from translation
 */
const phoneNumber = computed(() => {
  return t('pages.organisation-show.support.phone_number')
})
</script>
