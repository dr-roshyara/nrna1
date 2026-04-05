<template>
  <section class="success-carousel-section w-full">
    <!-- Header -->
    <div class="mb-8 md:mb-12 flex items-center justify-between">
      <div>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
          {{ title }}
        </h2>
        <p v-if="subtitle" class="text-gray-600 dark:text-gray-400 text-base md:text-lg">
          {{ subtitle }}
        </p>
      </div>
      <!-- Navigation dots -->
      <div class="flex gap-2">
        <button v-for="(_, index) in cases"
                :key="index"
                @click="activeIndex = index"
                :class="[
                  'w-2.5 h-2.5 rounded-full transition-all duration-300',
                  activeIndex === index
                    ? 'bg-blue-600 w-8'
                    : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500'
                ]"
                :aria-label="`Go to slide ${index + 1}`">
        </button>
      </div>
    </div>

    <!-- Carousel container -->
    <div class="relative overflow-hidden rounded-2xl">
      <!-- Slides -->
      <div class="relative h-auto">
        <transition name="carousel-fade" mode="out-in">
          <div :key="activeIndex" class="carousel-slide">
            <!-- Slide content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-center">
              <!-- Left: Content -->
              <div class="order-2 md:order-1 px-6 md:px-8 lg:px-10 py-8 md:py-0">
                <!-- Logo/Badge -->
                <div v-if="activeCase.logo"
                     class="mb-4 inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20
                            bg-gradient-to-br from-blue-100 to-cyan-100
                            dark:from-blue-900/30 dark:to-cyan-900/30
                            rounded-2xl">
                  <span class="text-2xl md:text-3xl">{{ activeCase.logo }}</span>
                </div>

                <!-- Organization name and type -->
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                  {{ activeCase.name }}
                </h3>

                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-4">
                  {{ activeCase.type }}
                </p>

                <!-- Quote/Testimonial -->
                <blockquote class="text-lg md:text-xl text-gray-700 dark:text-gray-300
                                  font-medium italic mb-6 leading-relaxed
                                  border-l-4 border-blue-600 dark:border-blue-400 pl-4">
                  "{{ activeCase.quote }}"
                </blockquote>

                <!-- Attribution -->
                <div class="flex items-center gap-3 mb-6">
                  <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-lg">{{ activeCase.contactInitial }}</span>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900 dark:text-white">
                      {{ activeCase.contact }}
                    </p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                      {{ activeCase.role }}
                    </p>
                  </div>
                </div>

                <!-- Metrics -->
                <div v-if="activeCase.metrics"
                     class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                  <div v-for="(metric, index) in activeCase.metrics"
                       :key="index"
                       class="text-center">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                      {{ metric.value }}
                    </p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                      {{ metric.label }}
                    </p>
                  </div>
                </div>

                <!-- CTA Button -->
                <button v-if="activeCase.action"
                        @click="handleAction(activeCase)"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg
                               shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95
                               transition-all duration-300">
                  {{ activeCase.action.label }} →
                </button>
              </div>

              <!-- Right: Image/Icon -->
              <div class="order-1 md:order-2 relative h-64 md:h-96 lg:h-full
                          bg-gradient-to-br from-blue-100 to-cyan-100
                          dark:from-blue-900/20 dark:to-cyan-900/20
                          rounded-2xl md:rounded-none
                          flex items-center justify-center overflow-hidden">
                <div class="text-8xl md:text-9xl opacity-40">
                  {{ activeCase.icon }}
                </div>
                <!-- Animated accent -->
                <div class="absolute inset-0 opacity-0 hover:opacity-100
                           bg-gradient-to-t from-blue-600/10 to-transparent
                           transition-opacity duration-500">
                </div>
              </div>
            </div>
          </div>
        </transition>
      </div>

      <!-- Navigation arrows (hidden on mobile) -->
      <div class="hidden md:flex absolute inset-y-0 left-0 right-0 pointer-events-none
                  md:pointer-events-auto">
        <button @click="prevSlide"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10
                       p-2 rounded-full bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800
                       shadow-lg transition-all duration-300 hover:scale-110">
          ←
        </button>
        <button @click="nextSlide"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10
                       p-2 rounded-full bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800
                       shadow-lg transition-all duration-300 hover:scale-110">
          →
        </button>
      </div>
    </div>

    <!-- Autoplay indicator -->
    <div v-if="autoplay" class="mt-4 text-xs text-gray-500 dark:text-gray-400 text-center">
      Auto-advancing in {{ autoplayDelay }}s
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: 'Success Stories',
  },
  subtitle: {
    type: String,
    default: null,
  },
  cases: {
    type: Array,
    required: true,
    validator: (arr) => arr.every(c => c.name && c.quote && c.icon),
  },
  autoplay: {
    type: Boolean,
    default: true,
  },
  autoplayInterval: {
    type: Number,
    default: 8000, // 8 seconds
  },
});

const emit = defineEmits(['action-click']);

const activeIndex = ref(0);
const autoplayDelay = ref(8);
let autoplayTimer;
let countdownTimer;

const activeCase = computed(() => {
  return props.cases[activeIndex.value] || props.cases[0];
});

const nextSlide = () => {
  activeIndex.value = (activeIndex.value + 1) % props.cases.length;
  resetAutoplay();
};

const prevSlide = () => {
  activeIndex.value = (activeIndex.value - 1 + props.cases.length) % props.cases.length;
  resetAutoplay();
};

const startAutoplay = () => {
  if (!props.autoplay) return;

  autoplayTimer = setInterval(() => {
    nextSlide();
  }, props.autoplayInterval);

  countdownTimer = setInterval(() => {
    autoplayDelay.value = (autoplayDelay.value % (props.autoplayInterval / 1000)) + 1;
  }, 1000);
};

const resetAutoplay = () => {
  clearInterval(autoplayTimer);
  clearInterval(countdownTimer);
  autoplayDelay.value = 8;
  startAutoplay();
};

const handleAction = (caseData) => {
  emit('action-click', caseData);
};

onMounted(() => {
  startAutoplay();
});

onUnmounted(() => {
  clearInterval(autoplayTimer);
  clearInterval(countdownTimer);
});
</script>

<style scoped>
.carousel-fade-enter-active,
.carousel-fade-leave-active {
  transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.carousel-fade-enter-from {
  opacity: 0;
  transform: translateX(20px);
}

.carousel-fade-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}

@media (prefers-reduced-motion: reduce) {
  .carousel-fade-enter-active,
  .carousel-fade-leave-active {
    transition: none !important;
  }

  button {
    transform: none !important;
  }
}
</style>
