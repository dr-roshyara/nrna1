<template>
  <div v-if="isDevelopment" class="performance-monitor" :class="{ expanded: isExpanded }">
    <div class="monitor-header">
      <h3 class="monitor-title">⚡ Performance</h3>
      <button class="toggle-btn" @click="toggleExpanded" :aria-label="isExpanded ? 'Collapse' : 'Expand'">
        {{ isExpanded ? '▼' : '▶' }}
      </button>
    </div>

    <div v-if="isExpanded" class="monitor-content">
      <!-- Page Load Metrics -->
      <div class="metrics-group">
        <div class="group-label">Page Load</div>
        <div class="metric">
          <span class="label">DOM Load:</span>
          <span class="value">{{ domContentTime }}ms</span>
        </div>
        <div class="metric">
          <span class="label">Full Load:</span>
          <span class="value">{{ pageLoadTime }}ms</span>
        </div>
      </div>

      <!-- Core Web Vitals -->
      <div class="metrics-group">
        <div class="group-label">Core Web Vitals</div>
        <div class="metric">
          <span class="label">LCP:</span>
          <span :class="['value', lcpStatus]">{{ lcpTime }}ms</span>
          <span class="hint">({{ lcpHint }})</span>
        </div>
        <div class="metric">
          <span class="label">FID:</span>
          <span :class="['value', fidStatus]">{{ fidTime }}ms</span>
          <span class="hint">({{ fidHint }})</span>
        </div>
        <div class="metric">
          <span class="label">CLS:</span>
          <span :class="['value', clsStatus]">{{ clsValue }}</span>
          <span class="hint">({{ clsHint }})</span>
        </div>
      </div>

      <!-- Memory -->
      <div v-if="memoryUsage" class="metrics-group">
        <div class="group-label">Memory</div>
        <div class="metric">
          <span class="label">Used:</span>
          <span class="value">{{ memoryUsage }}MB</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const isExpanded = ref(false)
const pageLoadTime = ref(0)
const domContentTime = ref(0)
const lcpTime = ref(0)
const fidTime = ref(0)
const clsValue = ref(0)
const memoryUsage = ref(null)

/**
 * Check if running in development mode
 */
const isDevelopment = computed(() => {
  return import.meta.env.DEV || process.env.NODE_ENV === 'development'
})

/**
 * Determine LCP status and hint
 */
const lcpStatus = computed(() => {
  if (lcpTime.value < 2500) return 'good'
  if (lcpTime.value < 4000) return 'needs-improvement'
  return 'poor'
})

const lcpHint = computed(() => {
  if (lcpStatus.value === 'good') return 'Good'
  if (lcpStatus.value === 'needs-improvement') return 'Needs Improvement'
  return 'Poor'
})

/**
 * Determine FID status and hint
 */
const fidStatus = computed(() => {
  if (fidTime.value < 100) return 'good'
  if (fidTime.value < 300) return 'needs-improvement'
  return 'poor'
})

const fidHint = computed(() => {
  if (fidStatus.value === 'good') return 'Good'
  if (fidStatus.value === 'needs-improvement') return 'Needs Improvement'
  return 'Poor'
})

/**
 * Determine CLS status and hint
 */
const clsStatus = computed(() => {
  if (clsValue.value < 0.1) return 'good'
  if (clsValue.value < 0.25) return 'needs-improvement'
  return 'poor'
})

const clsHint = computed(() => {
  if (clsStatus.value === 'good') return 'Good'
  if (clsStatus.value === 'needs-improvement') return 'Needs Improvement'
  return 'Poor'
})

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

  // Largest Contentful Paint (LCP)
  if ('PerformanceObserver' in window) {
    try {
      new PerformanceObserver((list) => {
        const entries = list.getEntries()
        if (entries.length > 0) {
          const lastEntry = entries[entries.length - 1]
          lcpTime.value = Math.round(lastEntry.renderTime || lastEntry.loadTime)
        }
      }).observe({ entryTypes: ['largest-contentful-paint'] })
    } catch (e) {
      // LCP observer not supported
    }

    // First Input Delay (FID) / Interaction to Next Paint (INP)
    try {
      new PerformanceObserver((list) => {
        list.getEntries().forEach((entry) => {
          fidTime.value = Math.round(entry.processingDuration)
        })
      }).observe({ entryTypes: ['first-input', 'first-input'] })
    } catch (e) {
      // FID observer not supported
    }

    // Cumulative Layout Shift (CLS)
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
      // CLS observer not supported
    }
  }

  // Memory usage (if available)
  if (performance.memory) {
    memoryUsage.value = Math.round(performance.memory.usedJSHeapSize / 1048576) // Convert to MB
  }
})

/**
 * Toggle expanded state
 */
const toggleExpanded = () => {
  isExpanded.value = !isExpanded.value
}
</script>

<style scoped>
.performance-monitor {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #1a1a1a;
  color: #00ff00;
  border: 2px solid #00ff00;
  border-radius: 8px;
  padding: 12px;
  font-family: 'Courier New', monospace;
  font-size: 11px;
  max-width: 320px;
  z-index: 9999;
  box-shadow: 0 4px 12px rgba(0, 255, 0, 0.2);
}

.monitor-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  cursor: pointer;
  user-select: none;
}

.monitor-title {
  margin: 0;
  font-size: 13px;
  font-weight: bold;
  color: #00ff00;
}

.toggle-btn {
  background: none;
  border: none;
  color: #00ff00;
  cursor: pointer;
  font-size: 11px;
  padding: 0;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toggle-btn:hover {
  opacity: 0.8;
}

.monitor-content {
  display: flex;
  flex-direction: column;
  gap: 10px;
  animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.metrics-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  padding-bottom: 8px;
  border-bottom: 1px solid rgba(0, 255, 0, 0.2);
}

.metrics-group:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.group-label {
  color: #00ff00;
  font-weight: bold;
  font-size: 10px;
  text-transform: uppercase;
  opacity: 0.7;
}

.metric {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 8px;
}

.label {
  color: #00ff00;
  min-width: 70px;
}

.value {
  color: #fff;
  font-weight: bold;
  min-width: 60px;
  text-align: right;
}

.value.good {
  color: #00ff00;
}

.value.needs-improvement {
  color: #ffff00;
}

.value.poor {
  color: #ff6b6b;
}

.hint {
  color: #888;
  font-size: 10px;
}

/* Responsive design */
@media (max-width: 640px) {
  .performance-monitor {
    bottom: 10px;
    right: 10px;
    max-width: 280px;
    padding: 10px;
    font-size: 10px;
  }

  .monitor-title {
    font-size: 12px;
  }
}

/* Light mode fallback */
@media (prefers-color-scheme: light) {
  .performance-monitor {
    background: #f5f5f5;
    color: #000;
    border-color: #333;
  }

  .monitor-title,
  .toggle-btn,
  .label,
  .group-label {
    color: #333;
  }

  .value {
    color: #000;
  }

  .hint {
    color: #999;
  }
}
</style>
