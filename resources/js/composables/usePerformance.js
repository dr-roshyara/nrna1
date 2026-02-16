import { ref, onMounted, computed } from 'vue'

/**
 * usePerformance - Vue 3 Composable
 *
 * Monitors and exposes performance metrics
 * Used by PerformanceMonitor component
 */
export function usePerformance() {
  const pageLoadTime = ref(0)
  const domContentTime = ref(0)
  const lcpTime = ref(0)
  const fidTime = ref(0)
  const clsValue = ref(0)
  const memoryUsage = ref(null)

  /**
   * Initialize performance monitoring
   */
  onMounted(() => {
    if (typeof window === 'undefined') return

    // Page Load Time
    window.addEventListener('load', () => {
      const perfData = window.performance.timing
      pageLoadTime.value = perfData.loadEventEnd - perfData.navigationStart
      domContentTime.value = perfData.domContentLoadedEventEnd - perfData.navigationStart
    })

    // Web Vitals
    if ('PerformanceObserver' in window) {
      trackLCP()
      trackFID()
      trackCLS()
    }

    // Memory
    if (performance.memory) {
      memoryUsage.value = Math.round(performance.memory.usedJSHeapSize / 1048576)
    }
  })

  /**
   * Track Largest Contentful Paint
   */
  const trackLCP = () => {
    try {
      new PerformanceObserver((list) => {
        const entries = list.getEntries()
        if (entries.length > 0) {
          const lastEntry = entries[entries.length - 1]
          lcpTime.value = Math.round(lastEntry.renderTime || lastEntry.loadTime)
        }
      }).observe({ entryTypes: ['largest-contentful-paint'] })
    } catch (e) {
      console.warn('LCP monitoring not supported', e)
    }
  }

  /**
   * Track First Input Delay
   */
  const trackFID = () => {
    try {
      new PerformanceObserver((list) => {
        list.getEntries().forEach((entry) => {
          fidTime.value = Math.round(entry.processingDuration)
        })
      }).observe({ entryTypes: ['first-input'] })
    } catch (e) {
      console.warn('FID monitoring not supported', e)
    }
  }

  /**
   * Track Cumulative Layout Shift
   */
  const trackCLS = () => {
    try {
      let clsScore = 0
      new PerformanceObserver((list) => {
        list.getEntries().forEach((entry) => {
          if (!entry.hadRecentInput) {
            clsScore += entry.value
            clsValue.value = parseFloat(clsScore.toFixed(3))
          }
        })
      }).observe({ entryTypes: ['layout-shift'] })
    } catch (e) {
      console.warn('CLS monitoring not supported', e)
    }
  }

  /**
   * Determine performance ratings
   */
  const ratings = computed(() => ({
    lcp: lcpTime.value < 2500 ? 'good' : lcpTime.value < 4000 ? 'needs-improvement' : 'poor',
    fid: fidTime.value < 100 ? 'good' : fidTime.value < 300 ? 'needs-improvement' : 'poor',
    cls: clsValue.value < 0.1 ? 'good' : clsValue.value < 0.25 ? 'needs-improvement' : 'poor'
  }))

  return {
    pageLoadTime,
    domContentTime,
    lcpTime,
    fidTime,
    clsValue,
    memoryUsage,
    ratings
  }
}
