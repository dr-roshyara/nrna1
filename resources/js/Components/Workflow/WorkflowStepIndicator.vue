<template>
    <div class="workflow-step-indicator">
        <div class="flex items-center justify-between gap-4">
            <!-- Workflow Name -->
            <div class="flex-1">
                <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-2">
                    {{ workflow }} Workflow
                </h3>
                <p class="text-gray-700 font-semibold">
                    Step {{ currentStep }} of {{ totalSteps }}
                </p>
            </div>

            <!-- Visual Progress -->
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <div v-for="step in totalSteps" :key="step" class="flex items-center gap-2">
                        <!-- Step Circle -->
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                            :class="{
                                'bg-blue-600 text-white': step === currentStep,
                                'bg-blue-200 text-blue-700': step < currentStep,
                                'bg-gray-200 text-gray-500': step > currentStep
                            }"
                        >
                            {{ step < currentStep ? '✓' : step }}
                        </div>

                        <!-- Connector Line (except for last step) -->
                        <div v-if="step < totalSteps" class="w-8 h-1 rounded-full"
                             :class="step < currentStep ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    </div>
                </div>

                <!-- Progress Bar Below -->
                <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
                    <div
                        class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full transition-all duration-500"
                        :style="{ width: (currentStep / totalSteps) * 100 + '%' }"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Step Labels -->
        <div class="mt-4 flex justify-between text-xs text-gray-600">
            <span v-for="step in totalSteps" :key="`label-${step}`">
                {{ getStepLabel(step) }}
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'WorkflowStepIndicator',

    props: {
        workflow: {
            type: String,
            default: 'VOTING',
            validator: (value) => ['VOTING', 'REGISTRATION', 'VERIFICATION'].includes(value)
        },
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

    methods: {
        getStepLabel(step) {
            const labels = {
                'VOTING': {
                    1: 'Code',
                    2: 'Agree',
                    3: 'Vote',
                    4: 'Verify',
                    5: 'Complete'
                },
                'REGISTRATION': {
                    1: 'Email',
                    2: 'Profile',
                    3: 'Verify',
                    4: 'Confirm',
                    5: 'Done'
                },
                'VERIFICATION': {
                    1: 'Review',
                    2: 'Confirm',
                    3: 'Verify',
                    4: 'Approve',
                    5: 'Complete'
                }
            };

            return labels[this.workflow]?.[step] || `Step ${step}`;
        }
    }
};
</script>

<style scoped>
.workflow-step-indicator {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>
