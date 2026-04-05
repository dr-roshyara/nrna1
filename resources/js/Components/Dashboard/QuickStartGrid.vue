<template>
  <section class="quick-start-grid-container w-full">
    <!-- Header with decorative accent -->
    <div v-if="title" class="grid-header mb-8 md:mb-10 text-center relative">
      <div class="absolute inset-x-0 -top-4 flex justify-center opacity-50">
        <span class="inline-block w-12 h-1 bg-primary-200 dark:bg-primary-800 rounded-full"></span>
      </div>

      <h2 class="grid-title font-bold text-gray-900 dark:text-white mb-2
                 text-2xl md:text-3xl lg:text-4xl
                 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700
                 dark:from-white dark:to-gray-300">
        {{ title }}
      </h2>

      <p v-if="subtitle" class="grid-subtitle text-gray-600 dark:text-gray-400
                               text-sm md:text-base max-w-2xl mx-auto
                               leading-relaxed">
        {{ subtitle }}
      </p>

      <!-- Animated gradient line -->
      <div class="mt-4 h-0.5 w-20 mx-auto bg-gradient-to-r from-primary-400 via-primary-500 to-primary-400
                  dark:from-primary-500 dark:via-primary-400 dark:to-primary-500
                  rounded-full animate-pulse-slow"></div>
    </div>

    <!-- Grid layout with dynamic columns -->
    <div
      class="quick-start-grid grid gap-4 md:gap-6 lg:gap-8 w-full"
      :class="gridClasses"
      role="grid"
    >
      <QuickStartCard
        v-for="card in cards"
        :key="card.id"
        :id="card.id"
        :icon="card.icon"
        :title="card.title"
        :description="card.description"
        :cta-text="card.cta"
        :is-primary="card.primary"
        :meta="card.meta"
        :priority="card.priority"
        :size="sizeFromPriority(card.priority)"
        @click="handleCardClick"
        role="gridcell"
      />
    </div>

    <!-- Empty state with animation -->
    <div v-if="!cards || !cards.length" class="text-center py-12 md:py-16">
      <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20
                  bg-gray-100 dark:bg-gray-800 rounded-2xl mb-4 animate-bounce">
        <span class="text-3xl md:text-4xl">📋</span>
      </div>
      <p class="text-gray-500 dark:text-gray-400 text-base md:text-lg">
        No actions available
      </p>
    </div>
  </section>
</template>

<script>
import { computed } from 'vue';
import QuickStartCard from './QuickStartCard.vue';

export default {
  name: 'QuickStartGrid',
  components: {
    QuickStartCard,
  },
  props: {
    cards: {
      type: Array,
      required: false,
      default: () => [],
      validator: (cards) => Array.isArray(cards) && cards.every(
        (card) => card.id && card.title && card.icon
      ),
    },
    title: {
      type: String,
      default: null,
    },
    subtitle: {
      type: String,
      default: null,
    },
    columns: {
      type: Number,
      default: 3,
      validator: (value) => [1, 2, 3, 4].includes(value),
    },
    priorityOrder: {
      type: Boolean,
      default: true,
    },
  },
  emits: ['card-clicked'],
  setup(props, { emit }) {
    const gridClasses = computed(() => {
      const base = {
        1: 'grid-cols-1',
        2: 'grid-cols-1 md:grid-cols-2',
        3: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
      };
      return base[props.columns] || base[3];
    });

    const sizeFromPriority = (priority) => {
      // Higher priority cards can be larger
      if (priority <= 1) return 'large';
      if (priority <= 2) return 'medium';
      return 'small';
    };

    const handleCardClick = (cardId) => {
      const card = props.cards.find((c) => c.id === cardId);
      emit('card-clicked', { cardId, card });

      // Track analytics if available
      if (window.gtag) {
        window.gtag('event', 'quick_start_click', {
          event_category: 'engagement',
          event_label: cardId,
        });
      }
    };

    return {
      gridClasses,
      sizeFromPriority,
      handleCardClick,
    };
  },
};
</script>

<style scoped>
@keyframes pulse-slow {
  0%, 100% {
    opacity: 0.6;
    width: 5rem;
  }
  50% {
    opacity: 1;
    width: 7rem;
  }
}

.animate-pulse-slow {
  animation: pulse-slow 3s ease-in-out infinite;
}

/* Gradient text effect */
.bg-clip-text {
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Responsive typography */
@media (max-width: 640px) {
  .grid-title {
    font-size: 1.5rem;
    line-height: 2rem;
  }

  .grid-subtitle {
    font-size: 0.875rem;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .animate-pulse-slow {
    animation: none;
    opacity: 0.8;
  }
}
</style>
