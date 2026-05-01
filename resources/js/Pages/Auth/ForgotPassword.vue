<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Header - same as home page -->
        <PublicDigitHeader />

        <!-- Main Content Area -->
        <main class="grow flex items-center justify-center py-8 md:py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl w-full">
                <!-- Card Container -->
                <div class="bg-white rounded-2xl shadow-xl border border-neutral-200 overflow-hidden">
                    <!-- Header Section -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 md:px-8 py-8 md:py-10 text-center">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                            {{ $t('pages.forgot-password.title') }}
                        </h1>
                        <p class="text-primary-100 text-sm md:text-base">
                            {{ $t('pages.forgot-password.subtitle') }}
                        </p>
                    </div>

                    <!-- Content Section -->
                    <div class="px-6 md:px-8 py-8 md:py-10">
                        <!-- Lock Icon -->
                        <div class="flex justify-center mb-6">
                            <div class="bg-primary-100 p-4 rounded-full">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="space-y-4 text-neutral-700 mb-8">
                            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 md:p-5">
                                <p class="text-sm md:text-base text-neutral-700 leading-relaxed">
                                    <span class="font-semibold text-neutral-900">{{ $t('pages.forgot-password.instructions.label') }}</span>
                                    {{ $t('pages.forgot-password.instructions.text') }}
                                </p>
                            </div>
                        </div>

                        <!-- Success Message -->
                        <div
                            v-if="status"
                            class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center"
                            role="alert"
                        >
                            <svg
                                class="w-5 h-5 text-green-600 mr-3 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                :aria-label="$t('pages.forgot-password.status.success_icon_label')"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ status }}</p>
                        </div>

                        <!-- Validation Errors -->
                        <div
                            v-if="form.errors.email"
                            class="mb-6 p-4 bg-danger-50 border border-danger-200 rounded-lg flex items-start"
                            role="alert"
                        >
                            <svg class="w-5 h-5 text-danger-600 mr-3 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-danger-800">
                                    {{ $t('pages.forgot-password.validation.error') }}
                                </p>
                                <p class="text-sm text-danger-700 mt-1">
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-neutral-700 mb-2">
                                    {{ $t('pages.forgot-password.form.email.label') }}
                                    <span class="text-danger-500 ml-1">*</span>
                                </label>

                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>

                                    <input
                                        id="email"
                                        type="email"
                                        class="w-full pl-10 pr-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-primary-500 text-base placeholder-gray-400"
                                        v-model="form.email"
                                        required
                                        autofocus
                                        autocomplete="email"
                                        :placeholder="$t('pages.forgot-password.form.email.placeholder')"
                                    />
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full bg-primary-600 hover:bg-primary-700 disabled:bg-neutral-400 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>

                                <span v-if="form.processing">
                                    {{ $t('pages.forgot-password.form.submit.processing') }}
                                </span>
                                <span v-else>
                                    {{ $t('pages.forgot-password.form.submit.normal') }}
                                </span>
                            </button>
                        </form>

                        <!-- Back to Login -->
                        <div class="mt-6 text-center">
                            <a
                                href="/login"
                                class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md px-2 py-1"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                {{ $t('pages.forgot-password.links.back_to_login') }}
                            </a>
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="bg-neutral-50 px-6 md:px-8 py-4 border-t border-neutral-200">
                        <p class="text-xs md:text-sm text-neutral-600 text-center">
                            <span class="font-semibold">{{ $t('pages.forgot-password.help.title') }}</span><br>
                            {{ $t('pages.forgot-password.help.message') }}
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer - same as home page -->
        <PublicDigitFooter class="px-4" />
    </div>
</template>

<script setup>
import { nextTick, onMounted } from 'vue';
import { useForm } from "@inertiajs/vue3";
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue';
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";

const props = defineProps({
    status: String
});

// Initialize form for password reset
const form = useForm({
    email: ''
});

// Focus email input on page load
onMounted(() => {
    nextTick(() => {
        document.getElementById('email')?.focus();
    });
});

// Submit form to request password reset email
const submit = () => {
    form.post(route('password.email'));
};
</script>

<style scoped>
/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .transition-colors,
    .animate-spin {
        transition: none !important;
        animation: none !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-primary-600 {
        background-color: #000000 !important;
    }

    .border-neutral-300 {
        border-color: #000000 !important;
        border-width: 2px !important;
    }
}

/* Print styles */
@media print {
    .bg-gradient-to-br,
    .shadow-xl {
        background: white !important;
        box-shadow: none !important;
        border: 2px solid #000 !important;
    }
}
</style>

