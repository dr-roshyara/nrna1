<template>
  <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
    <button
      @click="$emit('toggle')"
      @keydown.enter="$emit('toggle')"
      @keydown.space.prevent="$emit('toggle')"
      :aria-expanded="expanded"
      class="w-full text-left px-4 py-3 -mx-4 -my-3 flex items-center justify-between rounded
             hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors
             focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
             dark:focus:ring-offset-gray-900"
    >
      <h3 class="font-semibold text-gray-900 dark:text-white">
        {{ title }}
      </h3>
      <span
        class="text-gray-400 text-xl transform transition-transform duration-300"
        :class="{ 'rotate-180': expanded }"
        aria-hidden="true"
      >
        ▼
      </span>
    </button>

    <!-- Expandable content -->
    <transition
      name="accordion"
      @enter="onEnter"
      @leave="onLeave"
    >
      <div v-show="expanded" class="accordion-content">
        <div class="px-4 py-4 text-gray-700 dark:text-gray-300">
          <slot></slot>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  expanded: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['toggle']);

// Smooth accordion animation hooks
const onEnter = (el) => {
  el.style.maxHeight = el.scrollHeight + 'px';
};

const onLeave = (el) => {
  el.style.maxHeight = '0px';
};
</script>

<style scoped>
.accordion-content {
  max-height: 0px;
  overflow: hidden;
  transition: max-height 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@media (prefers-reduced-motion: reduce) {
  .accordion-content {
    transition: none !important;
  }

  button span {
    transform: none !important;
  }
}
</style>
