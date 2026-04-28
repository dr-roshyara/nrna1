<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Unified Admin Header -->
        <header class="sticky top-0 z-40 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white shadow-lg border-b border-gold/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Top Row: Logo + Controls -->
                <div class="flex items-center justify-between py-4 gap-4">
                    <!-- Logo and Branding -->
                    <div class="flex items-center gap-3">
                        <img
                            src="/images/logo-2.png"
                            alt="PUBLIC DIGIT Logo"
                            class="w-10 h-10 md:w-12 md:h-12 object-contain"
                        />
                        <div class="flex flex-col">
                            <h1 class="text-lg md:text-xl font-bold text-white">PublicDigit</h1>
                            <span class="text-xs text-gold-light/70">{{ $t('platform.tagline') }}</span>
                        </div>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center gap-4 ml-auto">
                        <!-- Language Selector -->
                        <div class="relative">
                            <select
                                :value="currentLocale"
                                @change="handleLanguageChange"
                                class="appearance-none bg-white/5 border border-gold/30 rounded-md px-3 py-2 text-xs md:text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-gold cursor-pointer transition-all"
                            >
                                <option value="de" class="bg-slate-800">DE</option>
                                <option value="en" class="bg-slate-800">EN</option>
                                <option value="np" class="bg-slate-800">NP</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gold">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>

                        <!-- Back to App Link -->
                        <Link
                            href="/dashboard"
                            class="px-3 py-2 text-sm text-gold hover:text-gold-light border border-gold/30 rounded-md transition"
                        >
                            ← Back to App
                        </Link>
                    </div>
                </div>

                <!-- Admin Navigation Tabs -->
                <nav class="flex gap-1 border-t border-white/10 pt-0">
                    <Link
                        href="/platform/dashboard"
                        class="px-4 py-3 text-sm font-medium text-white/70 hover:text-white border-b-2 border-transparent hover:border-gold transition duration-200"
                    >
                        🏠 Dashboard
                    </Link>
                    <Link
                        href="/platform/elections/pending"
                        class="px-4 py-3 text-sm font-medium text-white/70 hover:text-white border-b-2 border-transparent hover:border-gold transition duration-200"
                    >
                        ⏳ Pending Approvals
                    </Link>
                    <Link
                        href="/platform/elections/all"
                        class="px-4 py-3 text-sm font-medium text-white/70 hover:text-white border-b-2 border-transparent hover:border-gold transition duration-200"
                    >
                        📊 All Elections
                    </Link>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>

        <!-- Public Footer -->
        <PublicDigitFooter />
    </div>
</template>

<script>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

export default {
    components: { Link, PublicDigitFooter },
    setup() {
        const { locale } = useI18n()
        const currentLocale = ref(locale.value)

        const handleLanguageChange = (event) => {
            const newLocale = event.target.value
            if (['de', 'en', 'np'].includes(newLocale)) {
                currentLocale.value = newLocale
                locale.value = newLocale
                localStorage.setItem('preferred_locale', newLocale)
                const date = new Date()
                date.setFullYear(date.getFullYear() + 1)
                document.cookie = `locale=${newLocale}; expires=${date.toUTCString()}; path=/`
            }
        }

        return { currentLocale, handleLanguageChange }
    }
}
</script>
