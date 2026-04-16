<template>
  <div class="stat-card" :data-color="color">
    <div class="stat-icon">
      <svg v-if="icon === 'alert-circle'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
      </svg>
      <svg v-else-if="icon === 'check-circle'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
      </svg>
      <svg v-else-if="icon === 'clock'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-2.828 2.829a1 1 0 101.415 1.415L9 10.586V6z" clip-rule="evenodd" />
      </svg>
    </div>
    <div class="stat-content">
      <div class="stat-value">
        {{ displayValue }}
      </div>
      <div class="stat-label">
        {{ label }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const props = defineProps({
  value: {
    type: [Number, String],
    required: true
  },
  label: {
    type: String,
    required: true
  },
  icon: {
    type: [Object, String],
    default: null
  },
  color: {
    type: String,
    default: 'blue' // blue, green, amber, red
  },
  currency: {
    type: Boolean,
    default: false
  }
})

const animatedValue = ref(0)

const displayValue = computed(() => {
  if (props.currency) {
    return new Intl.NumberFormat('de-DE', {
      style: 'currency',
      currency: 'EUR',
      minimumFractionDigits: 2
    }).format(animatedValue.value)
  }
  return Math.round(animatedValue.value)
})

onMounted(() => {
  // Animate from 0 to final value over 1.2 seconds
  const startTime = Date.now()
  const duration = 1200
  const finalValue = parseFloat(props.value) || 0

  const animate = () => {
    const elapsed = Date.now() - startTime
    const progress = Math.min(elapsed / duration, 1)

    // Easing: ease-out cubic
    const easeProgress = 1 - Math.pow(1 - progress, 3)

    animatedValue.value = finalValue * easeProgress

    if (progress < 1) {
      requestAnimationFrame(animate)
    } else {
      animatedValue.value = finalValue
    }
  }

  animate()
})
</script>

<style scoped>
.stat-card {
  display: flex;
  gap: 1rem;
  padding: 1.5rem;
  background-color: #ffffff;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
  animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(1rem);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.stat-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 3rem;
  height: 3rem;
  border-radius: 0.375rem;
  flex-shrink: 0;
}

.stat-card[data-color="blue"] .stat-icon {
  background-color: #dbeafe;
  color: #1d4ed8;
}

.stat-card[data-color="green"] .stat-icon {
  background-color: #dcfce7;
  color: #15803d;
}

.stat-card[data-color="amber"] .stat-icon {
  background-color: #fef3c7;
  color: #b45309;
}

.stat-card[data-color="red"] .stat-icon {
  background-color: #fee2e2;
  color: #dc2626;
}

.stat-icon :deep(svg) {
  width: 1.5rem;
  height: 1.5rem;
}

.stat-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.stat-value {
  font-family: 'Commit Mono', 'JetBrains Mono', monospace;
  font-size: 1.875rem;
  font-weight: 700;
  color: #111827;
  line-height: 1.2;
}

.stat-label {
  font-size: 0.875rem;
  color: #6b7280;
  margin-top: 0.25rem;
}
</style>
