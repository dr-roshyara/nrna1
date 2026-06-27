<template>
    <div
        class="workflow-step-indicator"
        role="progressbar"
        :aria-valuenow="currentStep"
        :aria-valuemin="1"
        :aria-valuemax="totalSteps"
        :aria-label="`Step ${currentStep} of ${totalSteps}: ${activeLabel}`"
    >
        <!-- Desktop: full labelled stepper -->
        <div class="hidden sm:block">
            <div class="flex items-start justify-between w-full relative">
                <!-- Track line (behind everything) -->
                <div class="absolute top-4 left-0 right-0 h-px bg-neutral-200 z-0" aria-hidden="true">
                    <div
                        class="h-full bg-primary-500 transition-all duration-700 ease-out"
                        :style="{ width: trackFillPercent }"
                    ></div>
                </div>

                <!-- Steps -->
                <div
                    v-for="(label, index) in resolvedLabels"
                    :key="index"
                    class="relative z-10 flex flex-col items-center"
                    :style="{ width: `${100 / totalSteps}%` }"
                >
                    <!-- Circle -->
                    <div
                        class="step-circle flex items-center justify-center rounded-full border-2 transition-all duration-300"
                        :class="circleClass(index + 1)"
                        :aria-label="circleAriaLabel(index + 1)"
                    >
                        <!-- Completed: checkmark -->
                        <svg v-if="index + 1 < currentStep" class="w-3.5 h-3.5" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                            <path d="M2 7l3.5 3.5L12 3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <!-- Current: filled dot -->
                        <div v-else-if="index + 1 === currentStep" class="w-2 h-2 rounded-full bg-white" aria-hidden="true"></div>
                        <!-- Upcoming: step number -->
                        <span v-else class="text-xs font-semibold leading-none" aria-hidden="true">{{ index + 1 }}</span>
                    </div>

                    <!-- Label -->
                    <span
                        class="mt-2.5 text-center leading-tight transition-colors duration-300 px-1"
                        :class="labelClass(index + 1)"
                        style="font-size: 0.7rem; max-width: 4.5rem;"
                    >{{ label }}</span>
                </div>
            </div>
        </div>

        <!-- Mobile: compact pill + label + bar -->
        <div class="sm:hidden">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-600 text-white text-xs font-bold leading-none">
                        {{ currentStep }}
                    </span>
                    <span class="text-sm font-semibold text-neutral-800">{{ activeLabel }}</span>
                </div>
                <span class="text-xs font-medium text-neutral-400 tabular-nums">{{ currentStep }}/{{ totalSteps }}</span>
            </div>
            <div class="h-1 w-full bg-neutral-200 rounded-full overflow-hidden">
                <div
                    class="h-full bg-primary-500 rounded-full transition-all duration-700 ease-out"
                    :style="{ width: (currentStep / totalSteps) * 100 + '%' }"
                ></div>
            </div>
        </div>
    </div>
</template>

<script>
const DEFAULT_LABELS = ['Code', 'Agreement', 'Vote', 'Verify', 'Complete']

export default {
    name: 'WorkflowStepIndicator',

    props: {
        currentStep: {
            type: Number,
            required: true,
            validator: (v) => v >= 1 && v <= 10
        },
        totalSteps: {
            type: Number,
            default: 5
        },
        stepLabels: {
            type: Array,
            default: () => DEFAULT_LABELS
        }
    },

    computed: {
        resolvedLabels() {
            if (this.stepLabels && this.stepLabels.length === this.totalSteps) {
                return this.stepLabels
            }
            return Array.from({ length: this.totalSteps }, (_, i) =>
                DEFAULT_LABELS[i] ?? `Step ${i + 1}`
            )
        },

        activeLabel() {
            return this.resolvedLabels[this.currentStep - 1] ?? `Step ${this.currentStep}`
        },

        trackFillPercent() {
            if (this.currentStep <= 1) return '0%'
            const segments = this.totalSteps - 1
            const filled = this.currentStep - 1
            return `${(filled / segments) * 100}%`
        }
    },

    methods: {
        circleClass(step) {
            if (step < this.currentStep) {
                return 'w-8 h-8 bg-primary-600 border-primary-600 text-white'
            }
            if (step === this.currentStep) {
                return 'w-8 h-8 bg-primary-600 border-primary-600 text-white ring-4 ring-primary-100'
            }
            return 'w-8 h-8 bg-white border-neutral-300 text-neutral-400'
        },

        labelClass(step) {
            if (step < this.currentStep) return 'text-primary-600 font-medium'
            if (step === this.currentStep) return 'text-primary-700 font-semibold'
            return 'text-neutral-400 font-normal'
        },

        circleAriaLabel(step) {
            if (step < this.currentStep) return `Step ${step} completed`
            if (step === this.currentStep) return `Step ${step} current`
            return `Step ${step} upcoming`
        }
    }
}
</script>

<style scoped>
.workflow-step-indicator {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.875rem;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(255,255,255,0.8) inset;
}

.step-circle {
    background-clip: padding-box;
}

@media (min-width: 768px) {
    .workflow-step-indicator {
        padding: 1.5rem 2rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .transition-all,
    .transition-colors {
        transition: none !important;
    }
}
</style>
