<template>
  <div class="state-progress">
    <ol class="flex items-center gap-0">
      <li
        v-for="(step, index) in progress"
        :key="step.state"
        class="flex items-center"
        :class="{ 'flex-1': index < progress.length - 1 }"
      >
        <!-- Step circle -->
        <div class="flex flex-col items-center">
          <div
            class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium border-2 transition-colors"
            :class="circleClass(step.status)"
          >
            <span v-if="step.status === 'completed'">✓</span>
            <span v-else>{{ index + 1 }}</span>
          </div>
          <span
            class="mt-1 text-xs text-center w-20 leading-tight"
            :class="labelClass(step.status)"
          >
            {{ step.label }}
          </span>
          <span
            v-if="step.status === 'blocked' && step.blockedReason"
            class="mt-0.5 text-xs text-red-500 text-center w-24 leading-tight"
          >
            {{ step.blockedReason }}
          </span>
        </div>

        <!-- Connector line (between steps) -->
        <div
          v-if="index < progress.length - 1"
          class="flex-1 h-0.5 mx-1 -mt-6"
          :class="connectorClass(step.status)"
        />
      </li>
    </ol>
  </div>
</template>

<script setup>
defineProps({
  progress: {
    type: Array,
    required: true,
  },
})

function circleClass(status) {
  return {
    completed: 'bg-green-500 border-green-500 text-white',
    current:   'bg-blue-600 border-blue-600 text-white',
    future:    'bg-white border-gray-300 text-gray-400',
    blocked:   'bg-white border-red-400 text-red-400',
  }[status] ?? 'bg-white border-gray-300 text-gray-400'
}

function labelClass(status) {
  return {
    completed: 'text-green-600 font-medium',
    current:   'text-blue-600 font-semibold',
    future:    'text-gray-400',
    blocked:   'text-red-500',
  }[status] ?? 'text-gray-400'
}

function connectorClass(status) {
  return {
    completed: 'bg-green-400',
    current:   'bg-gray-200',
    future:    'bg-gray-200',
    blocked:   'bg-gray-200',
  }[status] ?? 'bg-gray-200'
}
</script>
