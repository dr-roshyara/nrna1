<template>
  <section class="insights-accordion-section w-full">
    <!-- Header -->
    <div class="mb-8 md:mb-10">
      <div class="flex items-center gap-3 mb-3">
        <span class="text-2xl md:text-3xl">{{ icon }}</span>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
          {{ title }}
        </h2>
      </div>
      <p v-if="subtitle" class="text-gray-600 dark:text-gray-400 text-sm md:text-base">
        {{ subtitle }}
      </p>
    </div>

    <!-- Accordion items -->
    <div class="space-y-2 md:space-y-3">
      <div v-for="(item, index) in items"
           :key="item.id || index"
           class="accordion-item group">

        <!-- Accordion header (trigger) -->
        <button
          @click="toggleItem(index)"
          @keydown.enter="toggleItem(index)"
          @keydown.space.prevent="toggleItem(index)"
          :aria-expanded="isExpanded(index)"
          :aria-controls="`accordion-content-${index}`"
          class="w-full px-4 md:px-6 py-4 md:py-5
                 bg-white dark:bg-gray-800
                 border-2 border-gray-200 dark:border-gray-700
                 rounded-xl
                 group-hover:border-blue-400 dark:group-hover:border-blue-600
                 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/50
                 transition-all duration-300
                 flex items-center justify-between gap-4
                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                 dark:focus:ring-offset-gray-900">

          <!-- Left: Icon + Title -->
          <div class="flex items-center gap-3 md:gap-4 flex-1 text-left">
            <!-- Category badge -->
            <div v-if="item.category"
                 class="flex-shrink-0 px-2.5 py-1 rounded-full text-xs font-semibold
                        bg-blue-100 dark:bg-blue-900/30
                        text-blue-700 dark:text-blue-400
                        group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50
                        transition-colors duration-300">
              {{ item.category }}
            </div>

            <!-- Title -->
            <div class="flex-1">
              <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white
                         group-hover:text-blue-700 dark:group-hover:text-blue-400
                         transition-colors duration-300">
                {{ item.title }}
              </h3>
              <p v-if="item.brief"
                 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                {{ item.brief }}
              </p>
            </div>
          </div>

          <!-- Right: Toggle icon -->
          <div class="flex-shrink-0 ml-2">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full
                        bg-gray-100 dark:bg-gray-700
                        group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30
                        transition-colors duration-300">
              <span class="transform transition-transform duration-300"
                    :class="{ 'rotate-180': isExpanded(index) }">
                ▼
              </span>
            </span>
          </div>
        </button>

        <!-- Accordion content (collapsible) -->
        <transition
          name="accordion"
          @enter="onEnter"
          @leave="onLeave">
          <div v-show="isExpanded(index)"
               :id="`accordion-content-${index}`"
               :aria-hidden="!isExpanded(index)"
               class="accordion-content overflow-hidden">

            <div class="px-4 md:px-6 py-4 md:py-5 pt-0
                        border-x-2 border-b-2 border-gray-200 dark:border-gray-700
                        bg-gray-50 dark:bg-gray-800/50 rounded-b-xl
                        space-y-4">

              <!-- Content -->
              <p class="text-sm md:text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ item.content }}
              </p>

              <!-- Details (optional) -->
              <ul v-if="item.details && item.details.length > 0"
                  class="space-y-2">
                <li v-for="(detail, dIndex) in item.details"
                    :key="dIndex"
                    class="flex items-start gap-2 text-xs md:text-sm text-gray-700 dark:text-gray-300">
                  <span class="flex-shrink-0 w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30
                             flex items-center justify-center text-blue-600 dark:text-blue-400
                             text-0.75 font-bold mt-0.5">
                    ✓
                  </span>
                  <span>{{ detail }}</span>
                </li>
              </ul>

              <!-- Action link (optional) -->
              <div v-if="item.action"
                   class="pt-2 border-t border-gray-200 dark:border-gray-700">
                <button @click="handleAction(item)"
                        class="text-sm font-semibold text-blue-600 dark:text-blue-400
                               hover:text-blue-700 dark:hover:text-blue-300
                               flex items-center gap-1
                               transition-colors duration-300">
                  {{ item.action.label }}
                  <span class="transition-transform duration-300 group-hover:translate-x-0.5">
                    →
                  </span>
                </button>
              </div>

              <!-- Helpful feedback (optional) -->
              <div v-if="showFeedback && isExpanded(index)"
                   class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                  {{ $t('insights.helpful_label', { fallback: 'Was this helpful?' }) }}
                </p>
                <div class="flex gap-2">
                  <button @click="handleFeedback(index, 'yes')"
                          class="flex-1 px-3 py-1.5 text-xs font-medium rounded
                                 bg-green-100 dark:bg-green-900/30
                                 text-green-700 dark:text-green-400
                                 hover:bg-green-200 dark:hover:bg-green-900/50
                                 transition-colors duration-300">
                    👍 Yes
                  </button>
                  <button @click="handleFeedback(index, 'no')"
                          class="flex-1 px-3 py-1.5 text-xs font-medium rounded
                                 bg-gray-100 dark:bg-gray-700
                                 text-gray-700 dark:text-gray-400
                                 hover:bg-gray-200 dark:hover:bg-gray-600
                                 transition-colors duration-300">
                    👎 No
                  </button>
                </div>
              </div>
            </div>
          </div>
        </transition>
      </div>
    </div>

    <!-- Empty state -->
    <div v-if="!items.length"
         class="text-center py-12">
      <p class="text-gray-600 dark:text-gray-400">
        {{ emptyLabel }}
      </p>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: String,
    default: null,
  },
  icon: {
    type: String,
    default: '💡',
  },
  items: {
    type: Array,
    required: true,
    validator: (arr) => arr.every(item => item.title && item.content),
  },
  singleOpen: {
    type: Boolean,
    default: false,
  },
  showFeedback: {
    type: Boolean,
    default: true,
  },
  emptyLabel: {
    type: String,
    default: 'No insights available',
  },
});

const emit = defineEmits(['item-action', 'item-feedback']);

// State
const expandedItems = ref(new Set());

// Methods: Toggle item
const toggleItem = (index) => {
  if (props.singleOpen) {
    expandedItems.value.clear();
  }

  if (expandedItems.value.has(index)) {
    expandedItems.value.delete(index);
  } else {
    expandedItems.value.add(index);
  }
};

// Methods: Check if expanded
const isExpanded = (index) => {
  return expandedItems.value.has(index);
};

// Methods: Handle action button
const handleAction = (item) => {
  emit('item-action', item);
};

// Methods: Handle feedback
const handleFeedback = (index, helpful) => {
  const item = props.items[index];
  emit('item-feedback', { item, index, helpful });
};

// Animation hooks
const onEnter = (el) => {
  el.style.maxHeight = el.scrollHeight + 'px';
};

const onLeave = (el) => {
  el.style.maxHeight = '0px';
};
</script>

<style scoped>
/* Accordion animation */
.accordion-content {
  max-height: 0px;
  transition: max-height 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  overflow: hidden;
}

.accordion-enter-active,
.accordion-leave-active {
  transition: max-height 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .accordion-content,
  .accordion-enter-active,
  .accordion-leave-active {
    transition: none !important;
    animation: none !important;
  }

  button span {
    transform: none !important;
  }
}
</style>
