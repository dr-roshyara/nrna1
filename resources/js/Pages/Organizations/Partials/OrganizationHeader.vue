<template>
  <div class="mb-8">
    <!-- Organization Badge & Date -->
    <div class="flex items-center gap-2 mb-3">
      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" clip-rule="evenodd" />
        </svg>
        {{ $t('pages.organization-show.organization.type_label') }}
      </span>
      <time
        :datetime="organization.created_at"
        class="text-sm text-gray-500"
      >
        {{ $t('pages.organization-show.organization.created_on', { date: formatDate(organization.created_at) }) }}
      </time>
    </div>

    <!-- Organization Name -->
    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
      {{ organization.name }}
    </h1>

    <!-- Email Contact -->
    <div class="flex items-center text-gray-700">
      <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
      </svg>
      <a
        :href="`mailto:${organization.email}`"
        :aria-label="$t('pages.organization-show.organization.email_label')"
        class="hover:text-blue-600 transition-colors font-medium"
      >
        {{ organization.email }}
      </a>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()

defineProps({
  organization: {
    type: Object,
    required: true,
    validator: (org) => {
      return org && typeof org.name === 'string' && typeof org.email === 'string'
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
