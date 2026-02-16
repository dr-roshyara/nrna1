/**
 * useMeta - SEO Meta Tags Management Composable
 *
 * This composable manages dynamic page-level SEO meta tags for Public Digit.
 * It leverages Vue i18n translations to provide language-aware meta tags.
 *
 * USAGE:
 * ------
 * import { useMeta } from '@/composables/useMeta'
 *
 * // Simple page (uses translation defaults)
 * useMeta({ pageKey: 'home' })
 *
 * // Page with dynamic parameters
 * useMeta({
 *   pageKey: 'organizations.show',
 *   params: {
 *     organizationName: organization.name,
 *     memberCount: organization.members_count,
 *     electionCount: organization.elections_count
 *   }
 * })
 *
 * // With overrides
 * useMeta({
 *   pageKey: 'pricing',
 *   title: 'Custom Title',
 *   description: 'Custom Description',
 *   noindex: false,
 *   nofollow: false
 * })
 *
 * FEATURES:
 * ---------
 * - Automatic title formatting with "| Public Digit" suffix
 * - Dynamic parameter substitution ({organizationName}, etc.)
 * - OG (Open Graph) tags for social sharing
 * - Twitter Card tags
 * - Canonical URL handling
 * - Support for noindex/nofollow (sensitive pages)
 * - Language-aware meta tags via i18n
 *
 * @author Public Digit SEO Team
 * @version 1.0.0
 */

import { useI18n } from 'vue-i18n'
import { onMounted, computed, watch } from 'vue'

export function useMeta(config = {}) {
  const { t, locale } = useI18n()

  // Merge config with defaults
  const finalConfig = {
    pageKey: null,
    params: {},
    title: null,
    description: null,
    keywords: null,
    image: null,
    url: null,
    noindex: false,
    nofollow: false,
    type: 'website',
    ...config
  }

  /**
   * Get SEO data from translations with parameter substitution
   */
  const seoData = computed(() => {
    let title = finalConfig.title
    let description = finalConfig.description
    let keywords = finalConfig.keywords

    // If pageKey provided, load from translations
    if (finalConfig.pageKey && !title) {
      try {
        const pageTranslations = t(`seo.pages.${finalConfig.pageKey}`, null)
        if (pageTranslations && typeof pageTranslations === 'object') {
          title = pageTranslations.title || null
          description = pageTranslations.description || null
          keywords = pageTranslations.keywords || null
        }
      } catch (e) {
        // Fallback to site defaults if page not found
      }
    }

    // Fallback to site defaults
    if (!title) {
      title = t('seo.site.title', 'Public Digit')
    }
    if (!description) {
      description = t('seo.site.description', 'Secure digital voting platform for diaspora communities')
    }
    if (!keywords) {
      keywords = t('seo.site.keywords', 'online voting, digital elections, diaspora')
    }

    // Substitute template parameters
    if (finalConfig.params && Object.keys(finalConfig.params).length > 0) {
      Object.entries(finalConfig.params).forEach(([key, value]) => {
        const placeholder = `{${key}}`
        title = String(title).replace(new RegExp(placeholder, 'g'), value || '')
        description = String(description).replace(new RegExp(placeholder, 'g'), value || '')
        keywords = String(keywords).replace(new RegExp(placeholder, 'g'), value || '')
      })
    }

    // Format title with suffix
    title = formatTitle(title)

    // Truncate description to 160 characters
    description = truncateDescription(description, 160)

    return {
      title,
      description,
      keywords,
      image: finalConfig.image || getDefaultOGImage(),
      url: finalConfig.url || getCurrentUrl(),
      noindex: finalConfig.noindex,
      nofollow: finalConfig.nofollow,
      type: finalConfig.type,
      locale: locale.value
    }
  })

  /**
   * Format title with separator and site name
   */
  function formatTitle(title) {
    // Don't add suffix if already includes "Public Digit"
    if (title.includes('Public Digit')) {
      return title
    }
    // Don't add if title is just the site name
    if (title === t('seo.site.title')) {
      return title
    }
    return `${title} | Public Digit`
  }

  /**
   * Truncate description to max length, respecting word boundaries
   */
  function truncateDescription(text, maxLength = 160) {
    if (!text || text.length <= maxLength) {
      return text
    }
    const truncated = text.substring(0, maxLength).trim()
    // Remove last partial word
    return truncated.substring(0, truncated.lastIndexOf(' ')) + '...'
  }

  /**
   * Get default OG image based on page type
   */
  function getDefaultOGImage() {
    const baseUrl = window.location.origin
    return `${baseUrl}/images/og-default.jpg`
  }

  /**
   * Get current page URL
   */
  function getCurrentUrl() {
    return window.location.href
  }

  /**
   * Update document head with meta tags
   */
  function updateDocumentHead(data) {
    // Update title tag
    document.title = data.title

    // Update or create meta tags
    updateMetaTag('name', 'description', data.description)
    updateMetaTag('name', 'keywords', data.keywords)

    // Open Graph tags
    updateMetaTag('property', 'og:title', data.title)
    updateMetaTag('property', 'og:description', data.description)
    updateMetaTag('property', 'og:image', data.image)
    updateMetaTag('property', 'og:type', data.type)
    updateMetaTag('property', 'og:url', data.url)
    updateMetaTag('property', 'og:locale', getOGLocale(data.locale))

    // Twitter Card tags
    updateMetaTag('name', 'twitter:title', data.title)
    updateMetaTag('name', 'twitter:description', data.description)
    updateMetaTag('name', 'twitter:image', data.image)
    updateMetaTag('name', 'twitter:card', 'summary_large_image')

    // Robots meta tag
    updateRobotsMeta(data.noindex, data.nofollow)

    // Canonical URL
    updateCanonical(data.url)
  }

  /**
   * Update or create a meta tag
   */
  function updateMetaTag(attribute, name, content) {
    if (!content) return

    let tag = document.querySelector(`meta[${attribute}="${name}"]`)
    if (!tag) {
      tag = document.createElement('meta')
      tag.setAttribute(attribute, name)
      document.head.appendChild(tag)
    }
    tag.setAttribute('content', content)
  }

  /**
   * Update robots meta tag for noindex/nofollow
   */
  function updateRobotsMeta(noindex, nofollow) {
    const robots = []
    if (noindex) robots.push('noindex')
    else robots.push('index')

    if (nofollow) robots.push('nofollow')
    else robots.push('follow')

    updateMetaTag('name', 'robots', robots.join(', '))
  }

  /**
   * Update canonical link tag
   */
  function updateCanonical(url) {
    let link = document.querySelector('link[rel="canonical"]')
    if (!link) {
      link = document.createElement('link')
      link.rel = 'canonical'
      document.head.appendChild(link)
    }
    link.href = url
  }

  /**
   * Convert Vue i18n locale to OG locale format
   * en -> en_US, de -> de_DE, np -> ne_NP
   */
  function getOGLocale(locale) {
    const localeMap = {
      en: 'en_US',
      de: 'de_DE',
      np: 'ne_NP'
    }
    return localeMap[locale] || locale
  }

  // Update on initial mount
  onMounted(() => {
    updateDocumentHead(seoData.value)
  })

  // Update when locale changes
  watch(
    () => locale.value,
    () => {
      updateDocumentHead(seoData.value)
    }
  )

  // Update when config params change (for dynamic pages)
  watch(
    () => finalConfig.params,
    () => {
      updateDocumentHead(seoData.value)
    },
    { deep: true }
  )

  return {
    seoData,
    title: computed(() => seoData.value.title),
    description: computed(() => seoData.value.description),
    keywords: computed(() => seoData.value.keywords),
    url: computed(() => seoData.value.url),
    image: computed(() => seoData.value.image)
  }
}
