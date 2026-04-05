<template>
  <section class="metrics-counter-section w-full">
    <!-- Header -->
    <div class="mb-8 md:mb-12 text-center">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3
                 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700
                 dark:from-white dark:to-gray-300">
        {{ title }}
      </h2>
      <p v-if="subtitle" class="text-gray-600 dark:text-gray-400 text-base md:text-lg max-w-2xl mx-auto">
        {{ subtitle }}
      </p>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <div v-for="(metric, index) in metrics"
           :key="metric.id || index"
           class="metric-card group relative"
           :style="{ '--stagger-delay': (index * 100) + 'ms' }">

        <!-- Background gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-cyan-50
                    dark:from-blue-900/30 dark:to-cyan-900/30
                    rounded-2xl opacity-0 group-hover:opacity-100
                    transition-opacity duration-500">
        </div>

        <!-- Card content -->
        <div class="relative px-6 md:px-8 py-8 md:py-10
                    bg-white dark:bg-gray-800 rounded-2xl
                    border-2 border-gray-200 dark:border-gray-700
                    group-hover:border-blue-400 dark:group-hover:border-blue-600
                    shadow-sm group-hover:shadow-lg
                    transition-all duration-300
                    flex flex-col items-center text-center">

          <!-- Icon/Indicator -->
          <div v-if="metric.icon"
               class="mb-4 text-3xl md:text-4xl
                      transform group-hover:scale-110 group-hover:rotate-3
                      transition-transform duration-300">
            {{ metric.icon }}
          </div>

          <!-- Live indicator dot (optional) -->
          <div v-if="metric.live"
               class="absolute top-4 right-4 w-2.5 h-2.5 bg-green-500 rounded-full
                      animate-pulse shadow-lg">
          </div>

          <!-- Counter value -->
          <div class="mb-2">
            <span class="text-4xl md:text-5xl lg:text-6xl font-bold
                        text-blue-600 dark:text-blue-400
                        text-transparent bg-clip-text
                        bg-gradient-to-r from-blue-600 to-cyan-600
                        dark:from-blue-400 dark:to-cyan-400">
              {{ animatedValue(metric, index) }}
            </span>
            <span v-if="metric.suffix"
                  class="text-xl md:text-2xl font-bold text-gray-600 dark:text-gray-400 ml-1">
              {{ metric.suffix }}
            </span>
          </div>

          <!-- Label -->
          <p class="text-sm md:text-base font-semibold text-gray-900 dark:text-white mb-1">
            {{ metric.label }}
          </p>

          <!-- Description (optional) -->
          <p v-if="metric.description"
             class="text-xs md:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            {{ metric.description }}
          </p>

          <!-- Tooltip indicator -->
          <button v-if="metric.tooltip"
                  @click="toggleTooltip(index)"
                  class="mt-3 text-xs text-blue-600 dark:text-blue-400
                         hover:text-blue-700 dark:hover:text-blue-300
                         transition-colors group/tooltip">
            <span class="inline-block w-4 h-4 rounded-full border-2 border-current
                        flex items-center justify-center text-0.75">
              ?
            </span>
            <span class="ml-1 inline-block group-hover/tooltip:block hidden absolute
                        bg-gray-900 dark:bg-white text-white dark:text-gray-900
                        text-xs rounded px-2 py-1 whitespace-nowrap">
              {{ metric.tooltip }}
            </span>
          </button>
        </div>

        <!-- Tooltip (on hover) -->
        <transition name="fade">
          <div v-if="expandedTooltips[index]"
               class="absolute -bottom-12 left-1/2 transform -translate-x-1/2
                      bg-gray-900 dark:bg-white text-white dark:text-gray-900
                      text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-lg z-10">
            {{ metric.tooltip }}
          </div>
        </transition>
      </div>
    </div>

    <!-- Footer note -->
    <p v-if="footnote"
       class="text-xs md:text-sm text-gray-600 dark:text-gray-400 text-center mt-8 md:mt-12">
      {{ footnote }}
    </p>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: 'Live Metrics',
  },
  subtitle: {
    type: String,
    default: null,
  },
  metrics: {
    type: Array,
    required: true,
    validator: (arr) => arr.every(m => m.value !== undefined && m.label),
  },
  animated: {
    type: Boolean,
    default: true,
  },
  duration: {
    type: Number,
    default: 2000, // milliseconds
  },
  footnote: {
    type: String,
    default: null,
  },
});

const emit = defineEmits(['complete']);

// State
const displayValues = ref([]);
const expandedTooltips = ref({});
const animationComplete = ref(false);

// Methods: Initialize display values
const initializeValues = () => {
  displayValues.value = props.metrics.map(() => 0);
};

// Methods: Animate counter
const animateCounter = (targetValue, duration, callback) => {
  const startTime = Date.now();
  const startValue = 0;
  const difference = targetValue - startValue;

  const animate = () => {
    const elapsed = Date.now() - startTime;
    const progress = Math.min(elapsed / duration, 1);

    // Easing function: ease-out exponential
    const easeOutExpo = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
    const currentValue = Math.round(startValue + difference * easeOutExpo);

    callback(currentValue);

    if (progress < 1) {
      requestAnimationFrame(animate);
    }
  };

  requestAnimationFrame(animate);
};

// Methods: Get animated value
const animatedValue = (metric, index) => {
  return displayValues.value[index] ?? 0;
};

// Methods: Toggle tooltip
const toggleTooltip = (index) => {
  expandedTooltips.value[index] = !expandedTooltips.value[index];
};

// Lifecycle: Start animations
onMounted(() => {
  if (!props.animated) {
    displayValues.value = props.metrics.map(m => m.value);
    return;
  }

  initializeValues();
  const delayPerMetric = props.duration / (props.metrics.length + 1);

  props.metrics.forEach((metric, index) => {
    setTimeout(() => {
      animateCounter(metric.value, props.duration, (value) => {
        displayValues.value[index] = value;

        // Emit complete when last counter finishes
        if (index === props.metrics.length - 1 && value === metric.value) {
          animationComplete.value = true;
          emit('complete');
        }
      });
    }, index * (delayPerMetric * 0.5));
  });
});
</script>

<style scoped>
.metric-card {
  animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
  animation-delay: var(--stagger-delay);
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Gradient text effect */
.bg-clip-text {
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Smooth transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .metric-card,
  .metric-card * {
    animation: none !important;
    transition: none !important;
  }
}

/* Tooltip positioning */
.group-hover\/tooltip:block {
  display: block;
}

@media (hover: none) {
  .group-hover\/tooltip:block {
    display: none;
  }
}
</style>
