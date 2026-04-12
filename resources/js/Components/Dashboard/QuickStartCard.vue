<template>
  <button
    class="quick-start-card group relative w-full
           flex flex-col items-start
           bg-white dark:bg-gray-900
           rounded-2xl
           border-2 border-transparent
           transition-all duration-300 ease-out
           hover:scale-[1.02] hover:-translate-y-1
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500
           active:scale-[0.99] active:translate-y-0"
    :class="[
      isPrimary ? 'card-primary shadow-lg hover:shadow-2xl' : 'card-secondary shadow-md hover:shadow-xl',
      sizeClasses,
      { 'cursor-pointer': !disabled },
      { 'opacity-60 cursor-not-allowed': disabled },
    ]"
    :disabled="disabled"
    @click="handleClick"
    @keydown.enter="handleClick"
    @keydown.space.prevent="handleClick"
    :aria-label="`${title}: ${description}. ${ctaText}`"
    :aria-disabled="disabled"
  >
    <!-- Glass morphism overlay for primary cards -->
    <div v-if="isPrimary"
         class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-transparent
                rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
    </div>

    <!-- Card content wrapper with relative positioning -->
    <div class="relative w-full flex flex-col gap-4 z-10">

      <!-- Header: Icon + Meta -->
      <div class="flex items-start justify-between w-full">
        <!-- Animated icon container -->
        <div class="icon-container relative"
             :class="iconSizeClasses">
          <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-primary-50
                      dark:from-primary-900/30 dark:to-primary-800/30
                      rounded-2xl opacity-0 group-hover:opacity-100
                      transition-opacity duration-300 blur-xl">
          </div>
          <div class="relative flex items-center justify-center w-full h-full
                      bg-gradient-to-br from-gray-50 to-gray-100
                      dark:from-gray-800 dark:to-gray-900
                      group-hover:from-primary-50 group-hover:to-primary-100
                      dark:group-hover:from-primary-900/50 dark:group-hover:to-primary-800/50
                      rounded-2xl transition-all duration-300
                      shadow-sm group-hover:shadow-md">
            <span class="transform transition-transform duration-300
                       group-hover:scale-110 group-hover:rotate-3
                       text-2xl md:text-3xl lg:text-4xl">
              {{ icon }}
            </span>
          </div>
        </div>

        <!-- Meta chip with micro-interaction -->
        <span v-if="meta"
              class="meta-chip inline-flex items-center px-3 py-1.5
                     text-xs font-medium rounded-full
                     bg-gray-100 dark:bg-gray-800
                     text-gray-700 dark:text-gray-300
                     border border-gray-200 dark:border-gray-700
                     group-hover:bg-primary-50 group-hover:border-primary-200
                     dark:group-hover:bg-primary-900/30 dark:group-hover:border-primary-700
                     transition-all duration-300">
          <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
          {{ meta }}
        </span>
      </div>

      <!-- Content area with fluid typography -->
      <div class="content-area flex flex-col gap-2 w-full">
        <h3 class="card-title font-semibold text-gray-900 dark:text-white
                   transition-colors duration-300
                   group-hover:text-primary-700 dark:group-hover:text-primary-400"
            :class="titleSizeClasses">
          {{ title }}
        </h3>

        <p class="card-description text-gray-600 dark:text-gray-400
                  leading-relaxed transition-all duration-300
                  group-hover:text-gray-900 dark:group-hover:text-gray-300"
           :class="descriptionSizeClasses">
          {{ description }}
        </p>
      </div>

      <!-- CTA Section with animated arrow -->
      <div class="cta-section flex items-center justify-between w-full pt-2
                  border-t border-gray-100 dark:border-gray-800
                  group-hover:border-primary-200 dark:group-hover:border-primary-800
                  transition-colors duration-300">

        <span class="cta-text font-semibold
                     text-primary-600 dark:text-primary-400
                     group-hover:text-primary-700 dark:group-hover:text-primary-300
                     transition-colors duration-300
                     text-sm md:text-base">
          {{ ctaText }}
        </span>

        <div class="cta-arrow flex items-center gap-1
                    text-primary-500 group-hover:text-primary-600
                    transition-all duration-300
                    group-hover:translate-x-1">
          <span class="text-sm font-medium">Go</span>
          <span class="transform transition-transform duration-300
                       group-hover:translate-x-1">→</span>
        </div>
      </div>
    </div>

    <!-- Loading shimmer effect (optional) -->
    <div v-if="loading"
         class="absolute inset-0 -translate-x-full
                bg-gradient-to-r from-transparent via-white/20 to-transparent
                animate-shimmer rounded-2xl">
    </div>
  </button>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'QuickStartCard',
  props: {
    id: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      default: '✨',
    },
    title: {
      type: String,
      required: true,
    },
    description: {
      type: String,
      required: true,
    },
    ctaText: {
      type: String,
      default: 'Get started',
    },
    isPrimary: {
      type: Boolean,
      default: false,
    },
    size: {
      type: String,
      default: 'medium',
      validator: (value) => ['small', 'medium', 'large'].includes(value),
    },
    meta: {
      type: String,
      default: null,
    },
    priority: {
      type: Number,
      default: 3,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['click'],
  setup(props, { emit }) {
    const sizeClasses = computed(() => {
      const sizes = {
        small: 'p-4 md:p-5',
        medium: 'p-5 md:p-6 lg:p-7',
        large: 'p-6 md:p-7 lg:p-8',
      };
      return sizes[props.size] || sizes.medium;
    });

    const iconSizeClasses = computed(() => {
      const sizes = {
        small: 'w-10 h-10 md:w-12 md:h-12',
        medium: 'w-12 h-12 md:w-14 md:h-14 lg:w-16 lg:h-16',
        large: 'w-14 h-14 md:w-16 md:h-16 lg:w-20 lg:h-20',
      };
      return sizes[props.size] || sizes.medium;
    });

    const titleSizeClasses = computed(() => {
      const sizes = {
        small: 'text-base md:text-lg',
        medium: 'text-lg md:text-xl lg:text-2xl',
        large: 'text-xl md:text-2xl lg:text-3xl',
      };
      return sizes[props.size] || sizes.medium;
    });

    const descriptionSizeClasses = computed(() => {
      const sizes = {
        small: 'text-xs md:text-sm',
        medium: 'text-sm md:text-base',
        large: 'text-base md:text-lg',
      };
      return sizes[props.size] || sizes.medium;
    });

    const handleClick = () => {
      if (!props.disabled && !props.loading) {
        emit('click', props.id);
      }
    };

    return {
      sizeClasses,
      iconSizeClasses,
      titleSizeClasses,
      descriptionSizeClasses,
      handleClick,
    };
  },
};
</script>

<style scoped>
/* Card primary gradient */
.card-primary {
  background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);
}

.dark .card-primary {
  background: linear-gradient(135deg, #111827 0%, rgba(88, 28, 235, 0.2) 100%);
}

/* Card secondary */
.card-secondary {
  background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
}

.dark .card-secondary {
  background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
}

/* Hover states */
.card-primary:hover {
  background: linear-gradient(135deg, #ffffff 0%, #ede9fe 100%);
}

.dark .card-primary:hover {
  background: linear-gradient(135deg, #111827 0%, rgba(88, 28, 235, 0.4) 100%);
}

/* Shimmer animation */
@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}

.animate-shimmer {
  animation: shimmer 2s infinite;
}

/* Touch target optimization */
@media (max-width: 640px) {
  .quick-start-card {
    min-height: 3rem;
  }

  .meta-chip {
    padding: 0.25rem 0.75rem;
    font-size: 0.7rem;
  }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  .quick-start-card,
  .quick-start-card *,
  .group-hover\:scale-110,
  .group-hover\:translate-x-1 {
    transition: none !important;
    animation: none !important;
    transform: none !important;
  }
}

/* High contrast mode */
@media (prefers-contrast: more) {
  .quick-start-card {
    border-width: 2px;
    border-color: #d1d5db;
  }

  .dark .quick-start-card {
    border-color: #4b5563;
  }

  .card-primary {
    border-color: #8b5cf6;
  }
}
</style>
