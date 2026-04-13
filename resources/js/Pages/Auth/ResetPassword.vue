<template>
    <nrna-layout>
        <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-br from-slate-50 to-slate-100">
            <div class="w-full max-w-md">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Reset Your Password</h1>
                    <p class="text-slate-600">Create a strong new password for your account</p>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-lg shadow-lg p-8 border border-slate-200">
                    <!-- Error Messages -->
                    <div v-if="Object.keys(form.errors).length > 0" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div class="flex-1">
                                <p v-for="(error, field) in form.errors" :key="field" class="text-sm text-red-700 font-medium">
                                    {{ error }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Success Message (after redirect would happen, but showing for UX) -->
                    <div v-if="resetSuccess" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm text-green-700 font-medium">Password reset successfully! Redirecting...</p>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Email Field (read-only) -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <input
                                    id="email"
                                    type="email"
                                    :value="form.email"
                                    disabled
                                    class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg text-slate-600 cursor-not-allowed"
                                />
                                <svg class="absolute right-3 top-3.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">This cannot be changed during password reset</p>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">
                                New Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    @input="checkPasswordStrength"
                                    placeholder="Enter a strong password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    :class="form.password ? 'border-slate-300' : 'border-slate-200'"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-3.5 text-slate-500 hover:text-slate-700 transition"
                                >
                                    <svg v-if="!showPassword" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                        <path d="M15.171 13.576l1.414 1.414A10.016 10.016 0 0120.458 10C19.184 5.943 15.394 3 10.916 3c-1.673 0-3.249.496-4.488 1.353l1.395 1.395a4 4 0 015.261 5.261z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Password Strength Indicator -->
                            <div v-if="form.password" class="mt-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-semibold text-slate-700">Strength:</span>
                                    <span :class="['text-xs font-semibold', passwordStrengthColor]">
                                        {{ passwordStrengthLabel }}
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div
                                        :style="{ width: passwordStrengthPercent + '%' }"
                                        :class="['h-full transition-all duration-300', passwordStrengthBarColor]"
                                    />
                                </div>
                                <ul class="mt-3 space-y-1 text-xs text-slate-600">
                                    <li :class="passwordStrengthChecks.length ? 'text-green-600' : ''">
                                        <span v-if="passwordStrengthChecks.length" class="mr-2">✓</span>
                                        <span v-else class="mr-2 text-slate-400">○</span>
                                        At least 8 characters
                                    </li>
                                    <li :class="passwordStrengthChecks.uppercase ? 'text-green-600' : ''">
                                        <span v-if="passwordStrengthChecks.uppercase" class="mr-2">✓</span>
                                        <span v-else class="mr-2 text-slate-400">○</span>
                                        Contains uppercase letter (A-Z)
                                    </li>
                                    <li :class="passwordStrengthChecks.number ? 'text-green-600' : ''">
                                        <span v-if="passwordStrengthChecks.number" class="mr-2">✓</span>
                                        <span v-else class="mr-2 text-slate-400">○</span>
                                        Contains number (0-9)
                                    </li>
                                    <li :class="passwordStrengthChecks.special ? 'text-green-600' : ''">
                                        <span v-if="passwordStrengthChecks.special" class="mr-2">✓</span>
                                        <span v-else class="mr-2 text-slate-400">○</span>
                                        Contains special character (!@#$%^&*)
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-900 mb-2">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    v-model="form.password_confirmation"
                                    placeholder="Re-enter your new password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    :class="[
                                        form.password_confirmation ? 'border-slate-300' : 'border-slate-200',
                                        form.password_confirmation && passwordsMatch ? 'ring-1 ring-green-300' : '',
                                        form.password_confirmation && !passwordsMatch ? 'ring-1 ring-red-300' : ''
                                    ]"
                                />
                                <button
                                    type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute right-3 top-3.5 text-slate-500 hover:text-slate-700 transition"
                                >
                                    <svg v-if="!showConfirmPassword" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0119.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                        <path d="M15.171 13.576l1.414 1.414A10.016 10.016 0 0120.458 10C19.184 5.943 15.394 3 10.916 3c-1.673 0-3.249.496-4.488 1.353l1.395 1.395a4 4 0 015.261 5.261z" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="form.password_confirmation && !passwordsMatch" class="mt-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18.169 12.842A8.003 8.003 0 002.999 7C3 5.668 3.331 4.406 3.901 3.289l-1.414-1.414A9.969 9.969 0 001 7a10 10 0 0018.834 0 9.963 9.963 0 00-.098-.93l-1.608.608A8.003 8.003 0 0118.169 12.842zM1.831 7.158A8.003 8.003 0 0017.001 13c0 1.332-.331 2.594-.901 3.711l1.414 1.414a9.969 9.969 0 002.486-3.711 10 10 0 00-18.834 0 9.964 9.964 0 00.098.93l1.608-.608zM10 15a5 5 0 110-10 5 5 0 010 10zm0-2a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-red-600 font-medium">Passwords do not match</p>
                            </div>
                            <div v-else-if="form.password_confirmation && passwordsMatch" class="mt-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-green-600 font-medium">Passwords match</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing || !passwordsMatch || !form.password || !form.password_confirmation"
                            class="w-full px-4 py-3 mt-6 font-semibold text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg hover:from-purple-700 hover:to-purple-800 disabled:from-slate-400 disabled:to-slate-400 disabled:cursor-not-allowed transition duration-200 flex items-center justify-center gap-2"
                        >
                            <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span v-if="form.processing">Resetting...</span>
                            <span v-else>Reset Password</span>
                        </button>
                    </form>

                    <!-- Back to Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-slate-600">
                            Remember your password?
                            <Link :href="route('login')" class="font-semibold text-purple-600 hover:text-purple-700 transition">
                                Sign in
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </nrna-layout>
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3'
import NrnaLayout from '@/Layouts/NrnaLayout.vue'

export default {
    components: {
        Link,
        NrnaLayout,
    },

    props: {
        email: String,
        token: String,
    },

    data() {
        return {
            form: useForm({
                token: this.token,
                email: this.email,
                password: '',
                password_confirmation: '',
            }),
            showPassword: false,
            showConfirmPassword: false,
            resetSuccess: false,
            passwordStrengthChecks: {
                length: false,
                uppercase: false,
                number: false,
                special: false,
            },
        }
    },

    computed: {
        passwordsMatch() {
            return this.form.password === this.form.password_confirmation && this.form.password.length > 0
        },

        passwordStrengthPercent() {
            let strength = 0
            if (this.passwordStrengthChecks.length) strength += 25
            if (this.passwordStrengthChecks.uppercase) strength += 25
            if (this.passwordStrengthChecks.number) strength += 25
            if (this.passwordStrengthChecks.special) strength += 25
            return strength
        },

        passwordStrengthLabel() {
            const percent = this.passwordStrengthPercent
            if (percent === 0) return 'None'
            if (percent <= 25) return 'Weak'
            if (percent <= 50) return 'Fair'
            if (percent <= 75) return 'Good'
            return 'Strong'
        },

        passwordStrengthColor() {
            const percent = this.passwordStrengthPercent
            if (percent === 0) return 'text-slate-500'
            if (percent <= 25) return 'text-red-600'
            if (percent <= 50) return 'text-orange-600'
            if (percent <= 75) return 'text-yellow-600'
            return 'text-green-600'
        },

        passwordStrengthBarColor() {
            const percent = this.passwordStrengthPercent
            if (percent === 0) return 'bg-slate-300'
            if (percent <= 25) return 'bg-red-500'
            if (percent <= 50) return 'bg-orange-500'
            if (percent <= 75) return 'bg-yellow-500'
            return 'bg-green-500'
        },
    },

    methods: {
        checkPasswordStrength() {
            const pwd = this.form.password
            this.passwordStrengthChecks = {
                length: pwd.length >= 8,
                uppercase: /[A-Z]/.test(pwd),
                number: /[0-9]/.test(pwd),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(pwd),
            }
        },

        submit() {
            this.form.post(route('password.reset.store'), {
                onSuccess: () => {
                    this.resetSuccess = true
                    setTimeout(() => {
                        window.location.href = route('login')
                    }, 2000)
                },
                onError: () => {
                    this.resetSuccess = false
                },
            })
        },
    },
}
</script>
