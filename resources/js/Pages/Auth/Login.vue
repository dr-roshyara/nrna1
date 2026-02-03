<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <ElectionHeader :isLoggedIn="false" />

        <!-- Login Section -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 md:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                            {{ $t('pages.auth.login.heading') }}
                        </h1>
                        <p class="text-xl text-gray-600">
                            {{ $t('pages.auth.login.subtitle') }}
                        </p>
                    </div>

                    <!-- Login Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 md:p-12">
                        <!-- Status Messages -->
                        <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm font-medium text-green-800">{{ status }}</p>
                        </div>

                        <!-- Validation Errors -->
                        <jet-validation-errors class="mb-6" />

                        <!-- Login Form -->
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Email Field -->
                            <div>
                                <jet-label for="email" :value="$t('pages.auth.login.fields.email.label')" class="font-bold" />
                                <jet-input
                                    id="email"
                                    type="email"
                                    class="mt-2 block w-full"
                                    v-model="form.email"
                                    :placeholder="$t('pages.auth.login.fields.email.placeholder')"
                                    required
                                    autofocus
                                    autocomplete="email"
                                />
                            </div>

                            <!-- Password Field -->
                            <div>
                                <jet-label for="password" :value="$t('pages.auth.login.fields.password.label')" class="font-bold" />
                                <jet-input
                                    id="password"
                                    type="password"
                                    class="mt-2 block w-full"
                                    v-model="form.password"
                                    :placeholder="$t('pages.auth.login.fields.password.placeholder')"
                                    required
                                    autocomplete="current-password"
                                />
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between pt-4">
                                <label class="flex items-center">
                                    <jet-checkbox
                                        name="remember"
                                        v-model:checked="form.remember"
                                    />
                                    <span class="ml-2 text-sm text-gray-600">
                                        {{ $t('pages.auth.login.fields.remember.label') }}
                                    </span>
                                </label>

                                <inertia-link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-700 transition underline"
                                >
                                    {{ $t('pages.auth.login.links.forgot_password') }}
                                </inertia-link>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-6 border-t border-gray-200">
                                <jet-button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full"
                                    :class="{ 'opacity-50': form.processing }"
                                >
                                    <span v-if="!form.processing">{{ $t('pages.auth.login.buttons.login') }}</span>
                                    <span v-else class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ $t('pages.auth.login.buttons.logging_in') }}
                                    </span>
                                </jet-button>
                            </div>
                        </form>

                        <!-- Social Login Divider -->
                        <div class="mt-8">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">{{ $t('pages.auth.login.divider') }}</span>
                                </div>
                            </div>
                            <!-- Google login commented out - can be uncommented later if needed -->
                        </div>

                        <!-- Registration Section -->
                        <div v-if="canRegister" class="mt-8 pt-8 border-t border-gray-200">
                            <div class="text-center space-y-4">
                                <p class="text-sm text-gray-600">
                                    {{ $t('pages.auth.login.messages.register_prompt') }}
                                </p>
                                <inertia-link
                                    :href="route('register')"
                                    class="inline-flex items-center justify-center px-8 py-3 border border-blue-600 text-sm font-medium rounded-lg text-blue-600 bg-white hover:bg-blue-50 transition duration-200 hover:shadow-md"
                                >
                                    {{ $t('pages.auth.login.links.register') }}
                                </inertia-link>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Badge -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            <svg class="inline-block w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $t('pages.auth.login.security') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <PublicDigitFooter />
    </div>
</template>

<script>
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import PublicDigitFooter from "@/Jetstream/PublicDigitFooter.vue";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
    components: {
        ElectionHeader,
        PublicDigitFooter,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
        canLogin: Boolean,
        canRegister: Boolean,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: "",
                password: "",
                remember: false,
            }),
        };
    },

    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    remember: this.form.remember ? "on" : "",
                }))
                .post(this.route("login"), {
                    onFinish: () => this.form.reset("password"),
                });
        },
    },
};
</script>

<style scoped>
/* Accessibility focus styles */
a:focus,
button:focus {
  outline: 2px solid #2563eb;
  outline-offset: 2px;
}

input:focus,
select:focus {
  outline: none;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* High contrast mode */
@media (prefers-contrast: high) {
  .text-gray-900,
  .text-gray-700 {
    color: #000000 !important;
  }

  .text-gray-600,
  .text-gray-500 {
    color: #000000 !important;
  }

  .bg-white {
    background: #ffffff !important;
    border: 2px solid #000000 !important;
  }

  input,
  select {
    border: 2px solid #000000 !important;
  }

  a {
    text-decoration: underline !important;
  }
}
</style>
