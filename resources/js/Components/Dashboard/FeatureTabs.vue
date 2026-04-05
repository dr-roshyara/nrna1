<template>
  <section class="feature-tabs-section w-full">
    <!-- Header -->
    <div class="mb-8 md:mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3
                 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700
                 dark:from-white dark:to-gray-300">
        {{ title }}
      </h2>
      <p v-if="subtitle" class="text-gray-600 dark:text-gray-400 text-base md:text-lg">
        {{ subtitle }}
      </p>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex flex-wrap gap-2 md:gap-3 mb-8 border-b-2 border-gray-200 dark:border-gray-700 pb-4">
      <button v-for="(feature, index) in features"
              :key="feature.id || index"
              @click="activeTab = index"
              :aria-selected="activeTab === index"
              :class="[
                'px-4 md:px-6 py-2.5 md:py-3 font-semibold rounded-lg transition-all duration-300',
                activeTab === index
                  ? 'bg-blue-600 text-white shadow-lg'
                  : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'
              ]"
              role="tab">
        <span class="mr-2">{{ feature.icon }}</span>
        {{ feature.category }}
      </button>
    </div>

    <!-- Tab Content -->
    <transition name="fade-slide" mode="out-in">
      <div :key="activeTab" class="space-y-6">
        <!-- Feature card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-200 dark:border-gray-700
                    hover:border-blue-400 dark:hover:border-blue-600
                    shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">

          <!-- Featured image/illustration area -->
          <div v-if="activeFeature.image"
               class="relative h-48 md:h-64 lg:h-72 bg-gradient-to-br from-blue-100 to-cyan-100
                      dark:from-blue-900/30 dark:to-cyan-900/30 flex items-center justify-center">
            <span class="text-6xl md:text-8xl opacity-30">
              {{ activeFeature.icon }}
            </span>
          </div>

          <!-- Content -->
          <div class="px-6 md:px-8 lg:px-10 py-8 md:py-10">
            <!-- Title -->
            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-3">
              {{ activeFeature.title }}
            </h3>

            <!-- Description -->
            <p class="text-gray-700 dark:text-gray-300 text-base md:text-lg leading-relaxed mb-6">
              {{ activeFeature.description }}
            </p>

            <!-- Benefits list -->
            <div v-if="activeFeature.benefits"
                 class="space-y-3 mb-8">
              <div v-for="(benefit, index) in activeFeature.benefits"
                   :key="index"
                   class="flex items-start gap-3">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 dark:bg-green-900/30
                           flex items-center justify-center text-green-600 dark:text-green-400 mt-0.5">
                  ✓
                </span>
                <span class="text-gray-700 dark:text-gray-300">{{ benefit }}</span>
              </div>
            </div>

            <!-- CTA Button -->
            <button v-if="activeFeature.action"
                    @click="handleAction(activeFeature)"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600
                           hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-lg
                           shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95
                           transition-all duration-300
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                           dark:focus:ring-offset-gray-900">
              {{ activeFeature.action.label }}
              <span class="ml-2 inline-block transform group-hover:translate-x-1">→</span>
            </button>
          </div>
        </div>

        <!-- Additional info cards (optional) -->
        <div v-if="activeFeature.details"
             class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
          <div v-for="(detail, index) in activeFeature.details"
               :key="index"
               class="p-4 md:p-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl
                      border border-gray-200 dark:border-gray-700">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
              {{ detail.title }}
            </h4>
            <p class="text-sm text-gray-700 dark:text-gray-400">
              {{ detail.description }}
            </p>
          </div>
        </div>
      </div>
    </transition>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: 'Why Choose PublicDigit?',
  },
  subtitle: {
    type: String,
    default: null,
  },
  features: {
    type: Array,
    required: true,
    validator: (arr) => arr.every(f => f.title && f.description && f.category && f.icon),
  },
});

const emit = defineEmits(['action-click']);

const activeTab = ref(0);

const activeFeature = computed(() => {
  return props.features[activeTab.value] || props.features[0];
});

const handleAction = (feature) => {
  emit('action-click', feature);
};
</script>

<style scoped>
.bg-clip-text {
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

@media (prefers-reduced-motion: reduce) {
  .fade-slide-enter-active,
  .fade-slide-leave-active {
    transition: none !important;
  }

  button {
    transform: none !important;
  }
}
</style>
