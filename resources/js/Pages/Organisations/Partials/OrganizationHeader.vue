<template>
  <div class="mb-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-primary-100 overflow-hidden p-8">
    <div class="flex flex-col sm:flex-row sm:items-start gap-6">
      <!-- Logo — falls back to an initials avatar when no logo is set -->
      <div class="shrink-0">
        <img
          v-if="organisation.logo_url"
          :src="organisation.logo_url"
          :alt="`${organisation.name} logo`"
          class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover bg-white border-2 border-white shadow-md"
        >
        <div
          v-else
          class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 border-2 border-white shadow-md flex items-center justify-center"
          role="img"
          :aria-label="`${organisation.name} (no logo set)`"
        >
          <span class="text-3xl sm:text-4xl font-black text-white select-none">
            {{ (organisation.name ?? '?').charAt(0).toUpperCase() }}
          </span>
        </div>
      </div>

      <div class="flex-1 min-w-0">
        <!-- Badge Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
          <span
            class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-sm w-fit"
            role="status"
            aria-label="organisation status"
          >
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" clip-rule="evenodd" />
            </svg>
            {{ $t('pages.organisation-show.organisation.type_label') }}
          </span>
          <time
            :datetime="organisation.created_at"
            class="text-sm text-neutral-600 font-medium"
            aria-label="organisation creation date"
          >
            {{ $t('pages.organisation-show.organisation.created_on', { date: formatDate(organisation.created_at) }) }}
          </time>
        </div>

        <!-- organisation Name with Improved Hierarchy -->
        <div>
          <h1 class="text-4xl sm:text-5xl font-black text-neutral-900 leading-tight mb-2 tracking-tight">
            {{ organisation.name }}
          </h1>
          <div class="h-1 w-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full" aria-hidden="true"></div>
        </div>
      </div>
    </div>

    <!-- Contact Information with Enhanced Layout -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-6 pt-6 mt-6 border-t border-primary-200">
      <div class="flex items-center gap-3">
        <svg class="w-6 h-6 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <div>
          <p class="text-xs font-semibold text-neutral-600 uppercase tracking-wide mb-1">
            {{ $t('pages.organisation-show.organisation.email_label') }}
          </p>
          <a
            :href="`mailto:${organisation.email}`"
            :aria-label="`Email: ${organisation.email}`"
            class="text-primary-600 hover:text-primary-700 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded px-2 py-1"
          >
            {{ organisation.email }}
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()

defineProps({
  organisation: {
    type: Object,
    required: true,
    validator: (org) => {
      return org && typeof org.name === 'string' && (org.email === null || typeof org.email === 'string')
    }
  }
})

/**
 * Format date according to current locale
 * Uses native JavaScript Intl API for localization
 */
const formatDate = (dateString) => {
  if (!dateString) return ''

  try {
    const date = new Date(dateString)

    // Language to locale code mapping
    const localeMap = {
      de: 'de-DE',
      en: 'en-US',
      np: 'en-US' // Fallback to English for Nepali
    }

    const localeCode = localeMap[locale.value] || 'en-US'

    return date.toLocaleDateString(localeCode, {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  } catch (error) {
    console.warn('Date formatting error:', error)
    return new Date(dateString).toLocaleDateString()
  }
}
</script>

