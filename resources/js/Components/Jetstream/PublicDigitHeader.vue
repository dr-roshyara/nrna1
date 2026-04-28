<template>
  <header
    class="sticky top-0 z-40 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white shadow-lg border-b border-gold/20 relative"
    :class="electionMode ? 'font-serif' : 'font-sans'"
  >
    <div class="container mx-auto px-3 md:px-6 lg:px-8 relative">
      <!-- Top Row: Logo + Controls -->
      <div class="flex items-center justify-between py-3 md:py-4 gap-3">
        <!-- Logo and Branding -->
        <div class="flex items-center gap-2 flex-1 min-w-0">
          <!-- Logo with subtle hover effect -->
          <div class="shrink-0 transform hover:scale-105 transition-transform duration-300 ease-out">
            <img
              src="/images/logo-2.png"
              alt="PUBLIC DIGIT Logo"
              class="w-10 h-10 md:w-12 md:h-12 object-contain"
            />
          </div>

          <!-- Brand Text -->
          <div class="flex flex-col min-w-0">
            <h1
              class="text-sm md:text-lg font-bold leading-tight truncate text-white"
              :class="electionMode ? 'font-serif tracking-wide' : ''"
            >
              {{ $t('platform.name') }}
            </h1>
            <span class="text-xs md:text-sm font-normal text-gold-light/80 truncate hidden sm:block">
              {{ $t('platform.tagline') }}
            </span>
          </div>
        </div>

        <!-- Right Controls: Language + Auth + Mobile Menu -->
        <div class="flex items-center gap-2 md:gap-3 shrink-0">
          <!-- Language Selector -->
          <div v-if="!disableLanguageSelector" class="relative">
            <select
              :value="currentLocale"
              @change="handleLanguageChange"
              class="appearance-none bg-white/5 border border-gold/30 rounded-md px-2 md:px-4 py-2 text-xs md:text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent cursor-pointer transition-all"
              :aria-label="$t('common.select_language')"
            >
              <option value="de" class="bg-slate-800 text-white">DE</option>
              <option value="en" class="bg-slate-800 text-white">EN</option>
              <option value="np" class="bg-slate-800 text-white">NP</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 md:px-2 text-gold">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>

          <!-- Auth Links - Desktop -->
          <div class="hidden sm:flex gap-2">
            <a
              v-if="!isLoggedIn"
              :href="route('login')"
              class="inline-flex items-center px-3 md:px-4 py-2 bg-white text-slate-900 font-semibold text-xs md:text-sm rounded-md hover:bg-gold hover:text-white focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 whitespace-nowrap group"
            >
              <svg class="w-4 h-4 mr-1 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
              </svg>
              {{ $t('navigation.login') }}
            </a>

            <!-- Logout button -->
            <button
              v-if="isLoggedIn"
              type="button"
              @click="logout"
              :disabled="isLoggingOut"
              class="inline-flex items-center px-3 md:px-4 py-2 border border-gold text-gold font-semibold text-xs md:text-sm rounded-md hover:bg-gold hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 whitespace-nowrap group disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              {{ isLoggingOut ? $t('navigation.logging_out', 'Logging out...') : $t('navigation.logout') }}
            </button>
          </div>

          <!-- Mobile Menu Toggle Button -->
          <button
            @click="toggleMobileMenu"
            :aria-expanded="showMobileMenu"
            :aria-label="showMobileMenu ? $t('common.close_menu') : $t('common.open_menu')"
            class="md:hidden p-2 rounded-md hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-gold transition-all duration-200"
          >
            <svg v-if="!showMobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Navigation Row - Desktop Only -->
      <nav
        class="hidden md:flex items-center justify-between py-3 border-t border-gold/20"
        role="navigation"
        :aria-label="$t('common.main_navigation')"
      >
        <div class="flex items-center gap-1">
          <Link
            href="/"
            class="px-3 py-2 text-white/80 hover:text-gold focus:outline-none focus:ring-2 focus:ring-gold/50 rounded-sm transition-colors duration-200 text-sm font-medium"
          >
            {{ $t('navigation.home') }}
          </Link>
          <Link
            href="/about"
            class="px-3 py-2 text-white/80 hover:text-gold focus:outline-none focus:ring-2 focus:ring-gold/50 rounded-sm transition-colors duration-200 text-sm font-medium"
          >
            {{ $t('navigation.about') }}
          </Link>
          <Link
            href="/faq"
            class="px-3 py-2 text-white/80 hover:text-gold focus:outline-none focus:ring-2 focus:ring-gold/50 rounded-sm transition-colors duration-200 text-sm font-medium"
          >
            {{ $t('navigation.faq') }}
          </Link>
          <Link
            href="/security"
            class="px-3 py-2 text-white/80 hover:text-gold focus:outline-none focus:ring-2 focus:ring-gold/50 rounded-sm transition-colors duration-200 text-sm font-medium"
          >
            {{ $t('navigation.security') }}
          </Link>
          <Link
            :href="route('public.election-architecture')"
            class="px-3 py-2 text-white/80 hover:text-gold focus:outline-none focus:ring-2 focus:ring-gold/50 rounded-sm transition-colors duration-200 text-sm font-medium"
          >
            {{ $t('navigation.election_architecture', 'Architecture') }}
          </Link>
          <Link
            href="/demo/result"
            class="px-3 py-2 text-white/80 hover:text-gold focus:outline-none focus:ring-2 focus:ring-gold/50 rounded-sm transition-colors duration-200 text-sm font-medium"
          >
            {{ $t('navigation.demo_result') }}
          </Link>
          <Link
            :href="route('public-demo.guide')"
            class="px-3 py-2 text-white/80 hover:text-gold focus:outline-none focus:ring-2 focus:ring-gold/50 rounded-sm transition-colors duration-200 text-sm font-medium"
          >
            {{ $t('navigation.demo_guide') }}
          </Link>
          <!-- Platform Admin Link -->
          <Link
            v-if="$page.props.user?.is_platform_admin"
            :href="route('platform.dashboard')"
            class="px-3 py-2 text-purple-300 hover:text-purple-100 focus:outline-none focus:ring-2 focus:ring-purple-400/50 rounded-sm transition-colors duration-200 text-sm font-medium border-l border-purple-400/30 ml-1 pl-4"
          >
            🔑 Platform Admin
          </Link>
        </div>

        <!-- Demo Link - Gold CTA -->
        <Link
          :href="$page.props.auth && $page.props.auth.user ? route('election.demo.start') : route('public-demo.start')"
          class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-slate-50 font-semibold text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 whitespace-nowrap shadow-md hover:shadow-lg group"
          :title="$t('navigation.demo_title', 'Try demo election without registration')"
        >
          <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M10.5 1.5H19a.5.5 0 01.5.5v8a.5.5 0 01-.5.5h-8.5V19a.5.5 0 01-.5.5H1a.5.5 0 01-.5-.5v-8a.5.5 0 01.5-.5H9V2a.5.5 0 01.5-.5z"/>
          </svg>
          {{ $t('navigation.demo', 'Try Demo') }}
        </Link>
      </nav>

      <!-- Breadcrumb Navigation with JSON-LD Schema -->
      <nav
        v-if="breadcrumbs && breadcrumbs.length > 0"
        class="border-t border-gold/20 py-2"
        aria-label="Breadcrumb"
      >
        <ol class="container mx-auto px-3 md:px-6 flex flex-wrap items-center gap-1 text-xs md:text-sm">
          <li v-for="(item, index) in breadcrumbs" :key="index" class="flex items-center">
            <a
              v-if="index < breadcrumbs.length - 1"
              :href="item.url"
              class="text-gold-light hover:text-gold transition-colors focus:outline-none focus:ring-2 focus:ring-gold/50 px-1 py-0.5 rounded"
            >
              {{ item.label }}
            </a>
            <span v-else class="text-white/70 px-1 py-0.5 font-medium">
              {{ item.label }}
            </span>
            <span v-if="index < breadcrumbs.length - 1" class="text-gold/50 mx-1" aria-hidden="true">/</span>
          </li>
        </ol>
      </nav>

      <!-- JSON-LD BreadcrumbList Schema for SEO -->
      <div v-if="jsonLdString" v-html="jsonLdString" style="display: none;"></div>

      <!-- Mobile Menu - Dropdown for small screens -->
      <div
        v-if="showMobileMenu"
        class="md:hidden absolute top-full left-0 right-0 bg-gradient-to-b from-slate-900 to-slate-950 border-t border-gold/20 shadow-2xl py-4 px-0 space-y-2 z-50"
        role="region"
        :aria-label="$t('common.mobile_navigation')"
      >
        <!-- Mobile Navigation Links -->
        <div class="space-y-1 px-3">
          <Link href="/" @click="closeMobileMenu"
            class="block px-4 py-3 text-white/80 hover:text-gold hover:bg-white/5 active:bg-white/10 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            🏠 {{ $t('navigation.home') }}
          </Link>
          <Link href="/about" @click="closeMobileMenu"
            class="block px-4 py-3 text-white/80 hover:text-gold hover:bg-white/5 active:bg-white/10 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            ℹ️ {{ $t('navigation.about') }}
          </Link>
          <Link href="/faq" @click="closeMobileMenu"
            class="block px-4 py-3 text-white/80 hover:text-gold hover:bg-white/5 active:bg-white/10 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            ❓ {{ $t('navigation.faq') }}
          </Link>
          <Link href="/security" @click="closeMobileMenu"
            class="block px-4 py-3 text-white/80 hover:text-gold hover:bg-white/5 active:bg-white/10 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            🔒 {{ $t('navigation.security') }}
          </Link>
          <Link :href="route('public.election-architecture')" @click="closeMobileMenu"
            class="block px-4 py-3 text-white/80 hover:text-gold hover:bg-white/5 active:bg-white/10 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            🏛️ {{ $t('navigation.election_architecture', 'Architecture') }}
          </Link>
          <Link href="/demo/result" @click="closeMobileMenu"
            class="block px-4 py-3 text-white/80 hover:text-gold hover:bg-white/5 active:bg-white/10 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            📊 {{ $t('navigation.demo_result') }}
          </Link>
          <Link :href="route('public-demo.guide')" @click="closeMobileMenu"
            class="block px-4 py-3 text-white/80 hover:text-gold hover:bg-white/5 active:bg-white/10 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            ❓ {{ $t('navigation.demo_guide') }}
          </Link>
        </div>

        <!-- Mobile Demo CTA - Gold -->
        <div class="pt-3 border-t border-gold/20 px-3">
          <Link
            :href="$page.props.auth && $page.props.auth.user ? route('election.demo.start') : route('public-demo.start')"
            @click="closeMobileMenu" 
            class="block px-4 py-3  text-slate-50  bg-green-600 font-semibold text-sm rounded-lg hover:from-gold-gold hover:to-white active:opacity-90 transition-all duration-150 text-center min-h-[44px] flex items-center justify-center shadow-md"
          >
            🎪 {{ $t('navigation.demo', 'Try Demo') }}
          </Link>
        </div> 

        <!-- Mobile Auth -->
        <div class="pt-3 border-t border-gold/20 space-y-2 sm:hidden px-3">
          <a
            v-if="!isLoggedIn"
            :href="route('login')"
            @click="closeMobileMenu"
            class="block px-4 py-3 bg-white text-slate-900 font-semibold text-sm rounded-lg hover:bg-gold hover:text-slate-900 active:opacity-90 transition-all duration-150 text-center min-h-[44px] flex items-center justify-center shadow-md"
          >
            🔐 {{ $t('navigation.login') }}
          </a>
          <button
            v-if="isLoggedIn"
            type="button"
            @click="logout"
            :disabled="isLoggingOut"
            class="w-full px-4 py-3 border border-gold text-gold font-semibold text-sm rounded-lg hover:bg-gold hover:text-slate-900 active:opacity-90 transition-all duration-150 min-h-[44px] flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
          >
            🚪 {{ isLoggingOut ? $t('navigation.logging_out', 'Logging out...') : $t('navigation.logout') }}
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { route } from 'ziggy-js'

const { t, locale } = useI18n()
const page = usePage()

const props = defineProps({
  locale: {
    type: String,
    default: null,
    validator: (value) => value === null || ['de', 'en', 'np'].includes(value),
  },
  disableLanguageSelector: {
    type: Boolean,
    default: false,
  },
  breadcrumbs: {
    type: Array,
    default: () => [],
    validator: (value) => {
      return value.every(item =>
        typeof item.label === 'string' &&
        (typeof item.url === 'string' || item.url === null)
      )
    },
  },
  electionMode: {
    type: Boolean,
    default: false, // switches to font-serif when true (election public pages)
  },
})

const currentLocale = ref(getInitialLocale())
const showMobileMenu = ref(false)
const isLoggingOut = ref(false)

const isLoggedIn = computed(() => !!page.props.user)
const breadcrumbs = computed(() => props.breadcrumbs || page.props.breadcrumbs || [])

// JSON-LD BreadcrumbList schema for SEO
const jsonLdSchema = computed(() => {
  if (!breadcrumbs.value || breadcrumbs.value.length === 0) return null
  return {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    'itemListElement': breadcrumbs.value.map((item, index) => ({
      '@type': 'ListItem',
      'position': index + 1,
      'name': item.label,
      'item': item.url,
    })),
  }
})

const jsonLdString = computed(() => {
  if (!jsonLdSchema.value) return ''
  return `<script type="application/ld+json">${JSON.stringify(jsonLdSchema.value)}<\/script>`
})

function getInitialLocale() {
  const savedLocale = localStorage.getItem('preferred_locale')
  if (savedLocale && ['de', 'en', 'np'].includes(savedLocale)) return savedLocale
  if (props.locale && ['de', 'en', 'np'].includes(props.locale)) return props.locale
  if (locale && ['de', 'en', 'np'].includes(locale.value)) return locale.value
  return 'de'
}

function handleLanguageChange(event) {
  const newLocale = event.target.value
  if (!['de', 'en', 'np'].includes(newLocale)) return
  currentLocale.value = newLocale
  switchLanguage(newLocale)
}

function switchLanguage(newLocale) {
  if (locale) locale.value = newLocale
  localStorage.setItem('preferred_locale', newLocale)
  const date = new Date()
  date.setFullYear(date.getFullYear() + 1)
  document.cookie = `locale=${newLocale}; expires=${date.toUTCString()}; path=/`
}

function toggleMobileMenu() {
  showMobileMenu.value = !showMobileMenu.value
}

function closeMobileMenu() {
  showMobileMenu.value = false
}

function logout() {
  closeMobileMenu()
  isLoggingOut.value = true
  router.post(route('logout'), {}, {
    preserveState: false,
    preserveScroll: true,
    onFinish: () => { isLoggingOut.value = false },
    onError: () => {
      isLoggingOut.value = false
      alert('Logout failed. Please try again.')
    },
  })
}

let handleEscapeKey = null
let handleResize = null

onMounted(() => {
  if (locale && currentLocale.value !== locale.value) {
    locale.value = currentLocale.value
  }

  handleEscapeKey = (event) => {
    if (event.key === 'Escape' && showMobileMenu.value) closeMobileMenu()
  }
  document.addEventListener('keydown', handleEscapeKey)

  handleResize = () => {
    if (window.innerWidth >= 768 && showMobileMenu.value) closeMobileMenu()
  }
  window.addEventListener('resize', handleResize)
})

onBeforeUnmount(() => {
  if (handleEscapeKey) document.removeEventListener('keydown', handleEscapeKey)
  if (handleResize) window.removeEventListener('resize', handleResize)
})

watch(() => props.locale, (newLocale) => {
  const savedLocale = localStorage.getItem('preferred_locale')
  if (savedLocale && ['de', 'en', 'np'].includes(savedLocale)) {
    currentLocale.value = savedLocale
    if (locale && locale.value !== savedLocale) locale.value = savedLocale
  } else if (newLocale && ['de', 'en', 'np'].includes(newLocale)) {
    currentLocale.value = newLocale
    if (locale && locale.value !== newLocale) locale.value = newLocale
  }
})
</script>

<style scoped>
/* ============================================
   GOLD ACCENT TOKENS
   ============================================ */
:root {
  --gold:       #b5862b;
  --gold-dark:  #92400e;
  --gold-light: #d4a84b;
}

.text-gold        { color: var(--gold); }
.text-gold-light  { color: var(--gold-light); }
.bg-gold          { background-color: var(--gold); }
.border-gold      { border-color: rgba(181, 134, 43, 0.4); }

/* Gradient helpers */
.from-gold        { --tw-gradient-from: var(--gold); }
.to-gold-dark     { --tw-gradient-to: var(--gold-dark); }
.hover\:from-gold-dark:hover { --tw-gradient-from: var(--gold-dark); }
.hover\:to-gold:hover        { --tw-gradient-to: var(--gold); }

/* Hover states */
.hover\:bg-gold:hover   { background-color: var(--gold); }
.hover\:text-gold:hover { color: var(--gold); }

/* Fractional opacity utilities used in template */
.border-gold\/20  { border-color: rgba(181, 134, 43, 0.2); }
.border-gold\/30  { border-color: rgba(181, 134, 43, 0.3); }
.text-gold\/50    { color: rgba(181, 134, 43, 0.5); }
.text-gold-light\/80 { color: rgba(212, 168, 75, 0.8); }
.focus\:ring-gold:focus { --tw-ring-color: rgba(181, 134, 43, 0.6); }
.focus\:ring-gold\/50:focus { --tw-ring-color: rgba(181, 134, 43, 0.5); }

/* ============================================
   LANGUAGE SELECTOR
   ============================================ */
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

select option {
  background-color: #1e293b; /* slate-800 */
  color: white;
}

/* ============================================
   ANIMATIONS — respects prefers-reduced-motion
   ============================================ */
@media (prefers-reduced-motion: no-preference) {
  a, button, select {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
  }
}

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* ============================================
   ACCESSIBILITY — keyboard focus
   ============================================ */
a:focus-visible,
button:focus-visible,
select:focus-visible {
  outline: 2px solid rgba(181, 134, 43, 0.8);
  outline-offset: 2px;
}

/* ============================================
   MOBILE TOUCH TARGETS
   ============================================ */
@media (max-width: 768px) {
  select { font-size: 14px; }
  a, button { min-height: 44px; }
}

/* ============================================
   PRINT
   ============================================ */
@media print {
  header {
    position: relative;
    box-shadow: none;
    border: none;
  }
  select, button { display: none; }
}
</style>
