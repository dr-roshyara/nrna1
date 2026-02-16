import { computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

/**
 * useBreadcrumbs - Vue 3 Composable
 *
 * Manages breadcrumb data for both HTML display and JSON-LD schema
 * Reactive to page changes via Inertia.js
 */
export function useBreadcrumbs() {
  const page = usePage()

  /**
   * Get breadcrumb array from Inertia props
   */
  const breadcrumbs = computed(() => {
    return page.props.breadcrumbs || [
      { label: 'Home', url: '/' }
    ]
  })

  /**
   * Generate JSON-LD BreadcrumbList schema
   */
  const jsonLd = computed(() => {
    const items = breadcrumbs.value.map((item, index) => ({
      '@type': 'ListItem',
      'position': index + 1,
      'name': item.label,
      'item': item.url
    }))

    return {
      '@context': 'https://schema.org',
      '@type': 'BreadcrumbList',
      'itemListElement': items
    }
  })

  /**
   * Get breadcrumb labels as string (e.g., "Home > Products > Item")
   * Useful for analytics and debugging
   */
  const breadcrumbTrail = computed(() => {
    return breadcrumbs.value
      .map(item => item.label)
      .join(' › ')
  })

  return {
    breadcrumbs,
    jsonLd,
    breadcrumbTrail
  }
}
