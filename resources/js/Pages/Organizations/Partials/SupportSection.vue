<template>
  <section
    aria-labelledby="support-heading"
    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border-l-4 border-blue-500 overflow-hidden"
  >
    <div class="px-4 py-6 sm:px-6">
      <!-- Title -->
      <h2
        id="support-heading"
        class="text-lg font-bold text-gray-900 mb-2"
      >
        {{ $t('pages.organization-show.support.title') }}
      </h2>

      <!-- Description -->
      <p class="text-sm text-gray-700 mb-6">
        {{ $t('pages.organization-show.support.description') }}
      </p>

      <!-- Contact Options -->
      <div class="space-y-4">
        <!-- Email Contact -->
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
          </svg>
          <div>
            <p class="text-sm font-medium text-gray-700">
              {{ $t('pages.organization-show.support.email_us') }}
            </p>
            <a
              :href="`mailto:${decodedEmailAddress}`"
              :aria-label="`Email: ${decodedEmailAddress}`"
              class="text-blue-600 hover:text-blue-700 font-semibold text-sm"
            >
              {{ decodedEmailAddress }}
            </a>
          </div>
        </div>

        <!-- Phone Contact -->
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773c.418 1.02 1.004 2.49 2.986 4.472 1.982 1.982 3.452 2.568 4.472 2.986l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
          </svg>
          <div>
            <p class="text-sm font-medium text-gray-700">
              {{ $t('pages.organization-show.support.call_us') }}
            </p>
            <a
              :href="`tel:${phoneNumber}`"
              :aria-label="`Call: ${phoneNumber}`"
              class="text-green-600 hover:text-green-700 font-semibold text-sm"
            >
              {{ phoneNumber }}
            </a>
          </div>
        </div>

        <!-- Support Hours -->
        <div class="flex items-start gap-3 pt-2">
          <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-1.414 1.414a1 1 0 001.414 1.414L9 10.414V6z" clip-rule="evenodd" />
          </svg>
          <div>
            <p class="text-xs text-gray-600">
              {{ $t('pages.organization-show.support.hours') }}
            </p>
          </div>
        </div>
      </div>

      <!-- Additional Help Links (optional) -->
      <div v-if="showAdditionalLinks" class="mt-6 pt-6 border-t border-blue-200">
        <div class="flex flex-col sm:flex-row gap-3">
          <a
            href="/handbook"
            class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-600 hover:bg-blue-100 transition-colors"
          >
            {{ $t('pages.organization-show.support.handbook') }}
          </a>
          <a
            href="/book-webinar"
            class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-600 hover:bg-blue-100 transition-colors"
          >
            {{ $t('pages.organization-show.support.book_webinar') }}
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
  const email = t('pages.organization-show.support.email_address')
  return email.replace(/&#64;/g, '@')
})

/**
 * Phone number from translation
 */
const phoneNumber = computed(() => {
  return t('pages.organization-show.support.phone_number')
})
</script>
