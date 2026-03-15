<template>
    <div
        class="workflow-step-indicator"
        role="progressbar"
        :aria-valuenow="currentStep"
        :aria-valuemin="1"
        :aria-valuemax="totalSteps"
        :aria-label="`Step ${currentStep} of ${totalSteps}`"
    >
        <!-- Mobile: compact counter + progress bar only -->
        <div class="flex items-center justify-between mb-2 sm:hidden">
            <span class="text-xs font-semibold text-blue-700">
                {{ currentStep }}/{{ totalSteps }}
            </span>
            <span class="text-xs text-gray-500">
                {{ Math.round((currentStep / totalSteps) * 100) }}%
            </span>
        </div>

        <!-- Tablet + Desktop: step circles with connectors (better spaced) -->
        <div class="hidden sm:flex items-center justify-between w-full max-w-2xl mx-auto px-2">
            <div
                v-for="(step, index) in steps"
                :key="step"
                class="flex items-center flex-1 last:flex-none"
                :class="index === 0 ? 'justify-start' : index === totalSteps - 1 ? 'justify-end' : 'justify-center'"
            >
                <!-- Step circle -->
                <div
                    class="flex items-center justify-center rounded-full font-bold transition-all duration-300 focus:outline-none
                           w-8 h-8 text-xs
                           md:w-9 md:h-9 md:text-sm
                           lg:w-10 lg:h-10 lg:text-sm"
                    :class="{
                        'bg-blue-600 text-white ring-4 ring-blue-200': step === currentStep,
                        'bg-blue-500 text-white': step < currentStep,
                        'bg-gray-200 text-gray-500': step > currentStep
                    }"
                    :aria-label="step < currentStep ? `Step ${step} completed` : step === currentStep ? `Step ${step} current` : `Step ${step} upcoming`"
                >
                    <span v-if="step < currentStep" aria-hidden="true">✓</span>
                    <span v-else aria-hidden="true">{{ step }}</span>
                </div>

                <!-- Connector line (flex-grow to fill space) -->
                <div
                    v-if="step < totalSteps"
                    class="h-1 rounded-full transition-all duration-500 flex-1 mx-2
                           min-w-[1rem] max-w-[4rem]"
                    :class="step < currentStep ? 'bg-blue-500' : 'bg-gray-200'"
                    aria-hidden="true"
                ></div>
            </div>
        </div>

        <!-- Progress bar (all sizes) -->
        <div
            class="w-full bg-gray-200 rounded-full mt-4 h-1.5 sm:h-1"
            aria-hidden="true"
        >
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full transition-all duration-500"
                :style="{ width: (currentStep / totalSteps) * 100 + '%' }"
            ></div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'WorkflowStepIndicator',

    props: {
        currentStep: {
            type: Number,
            required: true,
            validator: (value) => value >= 1 && value <= 5
        },
        totalSteps: {
            type: Number,
            default: 5
        }
    },

    computed: {
        steps() {
            return Array.from({ length: this.totalSteps }, (_, i) => i + 1)
        }
    }
};
</script>

<style scoped>
.workflow-step-indicator {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-radius: 0.75rem;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

@media (min-width: 640px) {
    .workflow-step-indicator {
        padding: 1.25rem;
    }
}

@media (min-width: 768px) {
    .workflow-step-indicator {
        padding: 1.5rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .transition-all,
    .transition-colors {
        transition: none !important;
    }
}
</style>
