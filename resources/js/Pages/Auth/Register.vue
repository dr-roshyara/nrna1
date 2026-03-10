<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <PublicDigitHeader />

        <!-- Registration Section -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 md:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                            {{ $t('pages.auth.register.title') }}
                        </h1>
                        <p class="text-xl text-gray-600">
                            {{ $t('pages.auth.register.subtitle') }}
                        </p>
                    </div>

                    <!-- Registration Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 md:p-12">
                        <!-- Validation Errors -->
                        <div v-if="form.errors && Object.keys(form.errors).length > 0" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="text-red-800">
                                <p class="font-bold mb-2">{{ $t('pages.auth.register.messages.validation_error') || 'Please fix the following errors:' }}</p>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li v-for="(errors, field) in form.errors" :key="field" class="text-red-700">
                                        <strong>{{ field }}:</strong> {{ Array.isArray(errors) ? errors[0] : errors }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- First Name -->
                            <div>
                                <label for="firstname" class="block font-bold text-base md:text-lg text-gray-900 mb-2">
                                    {{ $t('pages.auth.register.fields.firstName.label') }}
                                </label>
                                <input
                                    id="firstname"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    v-model="form.firstName"
                                    :placeholder="$t('pages.auth.register.fields.firstName.placeholder')"
                                    required
                                    autofocus
                                    autocomplete="given-name"
                                />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="lastName" class="block font-bold text-base md:text-lg text-gray-900 mb-2">
                                    {{ $t('pages.auth.register.fields.lastName.label') }}
                                </label>
                                <input
                                    id="lastName"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    v-model="form.lastName"
                                    :placeholder="$t('pages.auth.register.fields.lastName.placeholder')"
                                    required
                                    autocomplete="family-name"
                                />
                            </div>

                            <!-- Region Selection -->
                            <div>
                                <label for="region" class="block font-bold text-base md:text-lg text-gray-900 mb-2">
                                    {{ $t('pages.auth.register.fields.region.label') }}
                                </label>
                                <select
                                    name="region"
                                    id="region"
                                    v-model="form.region"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                >
                                    <option value="">{{ $t('pages.auth.register.fields.region.placeholder') }}</option>
                                    <option value="Europe">{{ $t('pages.auth.register.fields.region.options.europe') }}</option>
                                    <option value="America">{{ $t('pages.auth.register.fields.region.options.america') }}</option>
                                    <option value="Africa">{{ $t('pages.auth.register.fields.region.options.africa') }}</option>
                                    <option value="Asia Pacific">{{ $t('pages.auth.register.fields.region.options.asia_pacific') }}</option>
                                    <option value="Middle East Asia">{{ $t('pages.auth.register.fields.region.options.middle_east_asia') }}</option>
                                    <option value="Oceania">{{ $t('pages.auth.register.fields.region.options.oceania') }}</option>
                                </select>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block font-bold text-base md:text-lg text-gray-900 mb-2">
                                    {{ $t('pages.auth.register.fields.email.label') }}
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    v-model="form.email"
                                    :placeholder="$t('pages.auth.register.fields.email.placeholder')"
                                    required
                                    autocomplete="email"
                                />
                                <p class="text-xs text-gray-500 mt-2">{{ $t('pages.auth.register.fields.email.help') }}</p>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block font-bold text-base md:text-lg text-gray-900 mb-2">
                                    {{ $t('pages.auth.register.fields.password.label') }}
                                </label>
                                <p class="text-xs text-gray-600 mt-1 mb-2">
                                    <span class="text-red-500 font-medium">{{ $t('pages.auth.register.fields.password.help') }}</span>
                                    <br>
                                    {{ $t('pages.auth.register.fields.password.requirements') }}
                                </p>
                                <input
                                    id="password"
                                    type="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    v-model="form.password"
                                    :placeholder="$t('pages.auth.register.fields.password.placeholder')"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block font-bold text-base md:text-lg text-gray-900 mb-2">
                                    {{ $t('pages.auth.register.fields.password_confirmation.label') }}
                                </label>
                                <p class="text-xs text-gray-600 mt-1 mb-2">
                                    {{ $t('pages.auth.register.fields.password_confirmation.help') }}
                                </p>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    v-model="form.password_confirmation"
                                    :placeholder="$t('pages.auth.register.fields.password_confirmation.placeholder')"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>

                            <!-- Terms and Privacy Policy -->
                            <div class="pt-6 border-t-2 border-gray-300">
                                <div class="bg-blue-50 rounded-lg p-6 md:p-8 border-2 border-blue-200">
                                    <label for="terms" class="cursor-pointer">
                                        <div class="flex items-start gap-4">
                                            <!-- Large Checkbox -->
                                            <div class="shrink-0">
                                                <input
                                                    type="checkbox"
                                                    name="terms"
                                                    id="terms"
                                                    v-model="form.terms"
                                                    required
                                                    class="w-6 h-6 cursor-pointer text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                                />
                                            </div>

                                            <!-- Agreement Text -->
                                            <div class="grow">
                                                <p class="text-base md:text-lg font-semibold text-gray-900 mb-2">
                                                    {{ $t('pages.auth.register.messages.agreement') }}
                                                </p>
                                                <p class="text-sm md:text-base text-gray-700 leading-relaxed">
                                                    <span class="text-gray-700">{{ $t('pages.auth.register.messages.i_agree') }}</span>
                                                    <a
                                                        target="_blank"
                                                        :href="route('terms.show')"
                                                        class="text-blue-600 hover:text-blue-800 font-bold underline hover:no-underline transition"
                                                    >
                                                        {{ $t('pages.auth.register.links.terms') }}
                                                    </a>
                                                    <span class="text-gray-700">{{ $t('pages.auth.register.messages.and') }}</span>
                                                    <a
                                                        target="_blank"
                                                        :href="route('policy.show')"
                                                        class="text-blue-600 hover:text-blue-800 font-bold underline hover:no-underline transition"
                                                    >
                                                        {{ $t('pages.auth.register.links.privacy') }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Visual Indicator -->
                                    <div v-if="form.terms" class="mt-4 flex items-center text-green-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium text-sm">{{ $t('pages.auth.register.messages.agreement_accepted') }}</span>
                                    </div>

                                    <!-- Error Indicator -->
                                    <div v-else-if="form.errors && form.errors.terms" class="mt-4 flex items-center text-red-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium text-sm">{{ $t('pages.auth.register.messages.agreement_required') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <a
                                    :href="route('login')"
                                    class="text-sm text-gray-600 hover:text-gray-900 transition"
                                >
                                    {{ $t('pages.auth.register.links.already_registered') }}
                                    <span class="font-medium text-blue-600">{{ $t('pages.auth.register.links.go_to_login') }}</span>
                                </a>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="!form.processing">{{ $t('pages.auth.register.buttons.register') }}</span>
                                    <span v-else>{{ $t('pages.auth.register.buttons.registering') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Trust Badge -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            <svg class="inline-block w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $t('pages.welcome.hero.security_badge_title') }} — {{ $t('pages.welcome.hero.security_badge_subtitle') }}</span>
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
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue';
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";

export default {
    components: {
        PublicDigitHeader,
        PublicDigitFooter,
    },

    data() {
        return {
            form: this.$inertia.form({
                firstName: "",
                lastName: "",
                email: "",
                password: "",
                password_confirmation: "",
                terms: false,
                region: "",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("register"), {
                onFinish: () =>
                    this.form.reset("password", "password_confirmation"),
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
