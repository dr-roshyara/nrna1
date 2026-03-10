<template>
    <div class="workflow-step-indicator">
        <!-- Step Circles + Connectors -->
        <div class="flex items-center justify-center w-full gap-2">
            <div v-for="step in totalSteps" :key="step" class="flex items-center gap-2">
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
                <div v-if="step < totalSteps" class="w-8 h-1 rounded-full"
                     :class="step < currentStep ? 'bg-blue-600' : 'bg-gray-300'"></div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full transition-all duration-500"
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
