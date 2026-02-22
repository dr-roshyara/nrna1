<template>
  <header class="sticky top-0 z-40 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 text-white shadow-lg border-b border-blue-600/30 relative">
    <div class="container mx-auto px-3 md:px-6 lg:px-8 relative">
      <!-- Top Row: Logo + Controls -->
      <div class="flex items-center justify-between py-3 md:py-4 gap-3">
        <!-- Logo and Branding -->
        <div class="flex items-center gap-2 flex-1 min-w-0">
          <!-- Logo with subtle hover effect -->
          <div class="flex-shrink-0 transform hover:scale-105 transition-transform duration-300 ease-out">
            <img
              src="/images/logo-2.png"
              alt="PUBLIC DIGIT Logo"
              class="w-10 h-10 md:w-12 md:h-12 object-contain"
            />
          </div>

          <!-- Brand Text -->
          <div class="flex flex-col min-w-0">
            <h1 class="text-sm md:text-lg font-bold leading-tight truncate text-white">
              {{ $t('platform.name') }}
            </h1>
            <span class="text-xs md:text-sm font-normal text-blue-200/80 truncate hidden sm:block">
              {{ $t('platform.tagline') }}
            </span>
          </div>
        </div>

        <!-- Right Controls: Language + Auth + Mobile Menu -->
        <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">
          <!-- Language Selector - Compact on Mobile -->
          <div class="relative">
            <select
              :value="currentLocale"
              @change="handleLanguageChange"
              class="appearance-none bg-white/10 text-white border border-white/30 rounded px-2 md:px-4 py-2 text-xs md:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all cursor-pointer"
              :aria-label="$t('common.select_language')"
            >
              <option value="de" class="bg-blue-900 text-white">DE</option>
              <option value="en" class="bg-blue-900 text-white">EN</option>
              <option value="np" class="bg-blue-900 text-white">NP</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 md:px-2 text-white">
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
              class="inline-flex items-center px-3 md:px-4 py-2 bg-white text-blue-900 font-semibold text-xs md:text-sm rounded hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition-all duration-200 whitespace-nowrap group"
            >
              <svg class="w-4 h-4 mr-1 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
              </svg>
              {{ $t('navigation.login') }}
            </a>

            <!-- Logout button - POST request via Axios with CSRF protection -->
            <button
              v-if="isLoggedIn"
              type="button"
              @click="logout"
              :disabled="isLoggingOut"
              class="inline-flex items-center px-3 md:px-4 py-2 border-2 border-white text-white font-semibold text-xs md:text-sm rounded hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition-all duration-200 whitespace-nowrap group disabled:opacity-50 disabled:cursor-not-allowed"
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
            class="md:hidden p-2 rounded-lg hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200"
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
      <nav class="hidden md:flex items-center justify-between py-3 border-t border-blue-600/50" role="navigation" :aria-label="$t('common.main_navigation')">
        <div class="flex items-center gap-1">
          <a
            href="/"
            class="text-white font-medium hover:text-blue-100 focus:outline-none focus:ring-2 focus:ring-white/50 px-3 py-2 rounded transition-colors duration-200 text-sm"
          >
            {{ $t('navigation.home') }}
          </a>
          <a
            href="/about"
            class="text-white font-medium hover:text-blue-100 focus:outline-none focus:ring-2 focus:ring-white/50 px-3 py-2 rounded transition-colors duration-200 text-sm"
          >
            {{ $t('navigation.about') }}
          </a>
          <a
            href="/faq"
            class="text-white font-medium hover:text-blue-100 focus:outline-none focus:ring-2 focus:ring-white/50 px-3 py-2 rounded transition-colors duration-200 text-sm"
          >
            {{ $t('navigation.faq') }}
          </a>
        </div>

        <!-- Demo Link - Special CTA -->
        <a
          href="/election/demo/start"
          class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold text-sm rounded hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition-all duration-200 whitespace-nowrap shadow-md hover:shadow-lg group"
          :title="$t('navigation.demo_title', 'Try demo election without registration')"
        >
          <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M10.5 1.5H19a.5.5 0 01.5.5v8a.5.5 0 01-.5.5h-8.5V19a.5.5 0 01-.5.5H1a.5.5 0 01-.5-.5v-8a.5.5 0 01.5-.5H9V2a.5.5 0 01.5-.5z"/>
          </svg>
          {{ $t('navigation.demo', 'Try Demo') }}
        </a>
      </nav>

      <!-- Mobile Menu - Dropdown for small screens -->
      <div
        v-if="showMobileMenu"
        class="md:hidden absolute top-full left-0 right-0 bg-gradient-to-b from-blue-900 to-blue-950 border-t border-blue-600/50 shadow-2xl py-4 px-0 space-y-2 z-50"
        role="region"
        :aria-label="$t('common.mobile_navigation')"
      >
        <!-- Mobile Navigation Links -->
        <div class="space-y-1 px-3">
          <a
            href="/"
            @click="showMobileMenu = false"
            class="block px-4 py-3 text-white hover:bg-white/20 active:bg-white/30 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            🏠 {{ $t('navigation.home') }}
          </a>
          <a
            href="/about"
            @click="showMobileMenu = false"
            class="block px-4 py-3 text-white hover:bg-white/20 active:bg-white/30 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            ℹ️ {{ $t('navigation.about') }}
          </a>
          <a
            href="/faq"
            @click="showMobileMenu = false"
            class="block px-4 py-3 text-white hover:bg-white/20 active:bg-white/30 rounded-lg transition-colors duration-150 text-sm font-medium min-h-[44px] flex items-center"
          >
            ❓ {{ $t('navigation.faq') }}
          </a>
        </div>

        <!-- Mobile Demo CTA -->
        <div class="pt-3 border-t border-blue-600/50 px-3">
          <a
            href="/election/demo/start"
            @click="showMobileMenu = false"
            class="block px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold text-sm rounded-lg hover:from-green-600 hover:to-emerald-600 active:from-green-700 active:to-emerald-700 transition-all duration-150 text-center min-h-[44px] flex items-center justify-center shadow-md"
          >
            🎪 {{ $t('navigation.demo', 'Try Demo') }}
          </a>
        </div>

        <!-- Mobile Auth -->
        <div class="pt-3 border-t border-blue-600/50 space-y-2 sm:hidden px-3">
          <a
            v-if="!isLoggedIn"
            :href="route('login')"
            @click="showMobileMenu = false"
            class="block px-4 py-3 bg-white text-blue-900 font-semibold text-sm rounded-lg hover:bg-blue-50 active:bg-blue-100 transition-all duration-150 text-center min-h-[44px] flex items-center justify-center shadow-md"
          >
            🔐 {{ $t('navigation.login') }}
          </a>
          <button
            v-if="isLoggedIn"
            type="button"
            @click="logout"
            :disabled="isLoggingOut"
            class="w-full px-4 py-3 border-2 border-white text-white font-semibold text-sm rounded-lg hover:bg-white/20 active:bg-white/30 transition-all duration-150 min-h-[44px] flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
          >
            🚪 {{ isLoggingOut ? $t('navigation.logging_out', 'Logging out...') : $t('navigation.logout') }}
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script>

export default {
  name: 'ElectionHeader',

  props: {
    isLoggedIn: {
      type: Boolean,
      default: false,
    },
    locale: {
      type: String,
      default: null,
      validator: (value) => value === null || ['de', 'en', 'np'].includes(value),
    },
  },

  data() {
    return {
      currentLocale: this.getInitialLocale(),
      showMobileMenu: false,
      handleEscapeKey: null,
      handleResize: null,
      isLoggingOut: false,
    };
  },

  created() {
    // Sync Vue I18n with the determined locale
    if (this.$i18n) {
      const locale = this.getInitialLocale();
      if (locale && locale !== this.$i18n.locale) {
        this.$i18n.locale = locale;
        console.log('📦 Initialized locale:', locale);
      }
    }
  },

  methods: {
    getInitialLocale() {
      // Priority 1: Check localStorage for user preference
      const savedLocale = localStorage.getItem('preferred_locale');
      if (savedLocale && ['de', 'en', 'np'].includes(savedLocale)) {
        console.log('✅ Using saved locale from localStorage:', savedLocale);
        return savedLocale;
      }

      // Priority 2: Use backend locale if provided
      if (this.locale && ['de', 'en', 'np'].includes(this.locale)) {
        console.log('📦 Using backend locale:', this.locale);
        return this.locale;
      }

      // Priority 3: Use i18n's current locale
      if (this.$i18n) {
        console.log('📦 Using i18n locale:', this.$i18n.locale);
        return this.$i18n.locale;
      }

      // Fallback to German
      return 'de';
    },

    /**
     * Handle language change from select dropdown
     */
    handleLanguageChange(event) {
      const newLocale = event.target.value;

      if (!['de', 'en', 'np'].includes(newLocale)) {
        console.error('❌ Invalid locale:', newLocale);
        return;
      }

      console.log('🌐 Language change requested:', newLocale);
      this.currentLocale = newLocale;
      this.switchLanguage(newLocale);
    },

    /**
     * Switch application language
     * 1. Update Vue I18n (immediate frontend change)
     * 2. Save preference to localStorage
     * 3. Set cookie for Laravel backend
     * 4. Reload page to let Laravel apply new locale
     */
    switchLanguage(locale) {
      console.log('🔄 Switching to locale:', locale);

      // 1. Update Vue I18n immediately
      if (this.$i18n) {
        this.$i18n.locale = locale;
        console.log('✅ Vue I18n locale updated to:', locale);
      }

      // 2. Save preference to localStorage
      localStorage.setItem('preferred_locale', locale);
      console.log('💾 Preference saved to localStorage:', localStorage.getItem('preferred_locale'));

      // 3. Set cookie for Laravel backend (try without SameSite first)
      const date = new Date();
      date.setFullYear(date.getFullYear() + 1);

      // Try basic cookie first (without SameSite)
      const cookieString = `locale=${locale}; expires=${date.toUTCString()}; path=/`;
      console.log('🍪 Setting cookie:', cookieString);

      document.cookie = cookieString;

      // Debug immediately
      console.log('🍪 document.cookie after setting:', document.cookie);
      console.log('🍪 Cookie includes locale?', document.cookie.includes('locale='));

      // Extract the actual value
      const cookieMatch = document.cookie.match(/locale=([^;]+)/);
      const actualValue = cookieMatch ? cookieMatch[1] : 'NOT FOUND';
      console.log('🍪 Cookie value extracted:', actualValue);
      console.log('🍪 Is it the value we set?', actualValue === locale);

      // Verify cookie was actually set
      setTimeout(() => {
        const allCookies = document.cookie;
        console.log('🍪 All cookies after 50ms:', allCookies);
        const hasCookie = allCookies.includes(`locale=${locale}`);
        console.log('🍪 Cookie still present?', hasCookie);

        if (!hasCookie) {
          console.warn('⚠️ Cookie NOT FOUND in document.cookie!');
          console.warn('⚠️ This means the cookie was not set or was immediately deleted');
        }
      }, 50);

      // 4. Reload page after brief delay to let UI update
      console.log('🔄 Will reload in 300ms...');
      setTimeout(() => {
        console.log('🔄 Reloading now...');
        console.log('🍪 Final cookie check before reload:', document.cookie);
        window.location.reload(true); // Force reload from server
      }, 300);
    },

    /**
     * Toggle mobile menu visibility
     */
    toggleMobileMenu() {
      this.showMobileMenu = !this.showMobileMenu;
    },

    /**
     * Close mobile menu
     */
    closeMobileMenu() {
      this.showMobileMenu = false;
    },

    /**
     * Logout user via fetch with proper CSRF token handling
     * PROVEN PATTERN: Same approach used in OrganizationCreateModal (working)
     *
     * Token retrieval strategy:
     * 1. Try meta tag first (most reliable - set by Laravel)
     * 2. Fallback to XSRF-TOKEN cookie (Laravel default)
     * 3. Error if neither found - forces page refresh to regenerate token
     *
     * Benefits:
     * - Matches proven working pattern from modal
     * - Robust CSRF token retrieval with fallback
     * - Proper error handling for 419 (expired token)
     * - credentials: 'same-origin' ensures cookies sent
     * - No token expiration issues
     */
    logout() {
      console.log('🚪 Logout initiated');
      this.closeMobileMenu();
      this.isLoggingOut = true;

      try {
        // Get CSRF token with fallback strategy
        const getCsrfToken = () => {
          // Try meta tag first (most reliable)
          const metaElement = document.querySelector('meta[name="csrf-token"]');
          const metaToken = metaElement?.getAttribute('content') || metaElement?.content;

          if (metaToken) {
            console.log('✓ CSRF token retrieved from meta tag');
            return metaToken;
          }

          // Fallback: try to extract from cookie (Laravel default is XSRF-TOKEN)
          const name = 'XSRF-TOKEN';
          const decodedCookie = decodeURIComponent(document.cookie)
            .split(';')
            .map(c => c.trim())
            .find(c => c.startsWith(name + '='));

          if (decodedCookie) {
            console.log('✓ CSRF token retrieved from cookie');
            return decodeURIComponent(decodedCookie.substring(name.length + 1));
          }

          console.warn('⚠️ CSRF token not found in meta tag or cookies');
          return null;
        };

        const csrfToken = getCsrfToken();
        if (!csrfToken) {
          const error = 'CSRF token not found. Please refresh the page.';
          this.isLoggingOut = false;
          alert(error);
          console.error('❌ CSRF token retrieval failed');
          return;
        }

        console.log('📤 Sending logout request with CSRF token');

        // Use fetch with proper CSRF headers (matching OrganizationCreateModal pattern)
        fetch(this.route('logout'), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
          },
          credentials: 'same-origin', // CRITICAL: ensures cookies are sent
          body: JSON.stringify({}),
        })
          .then(response => {
            if (!response.ok) {
              console.error(`❌ Request failed with status ${response.status}`);

              // Special handling for CSRF token mismatch (419)
              if (response.status === 419) {
                console.error('CSRF token verification failed - token expired');
                const error = '⚠️ CSRF token expired. Please refresh the page and try again.';
                alert(error);
                window.location.reload();
                return;
              }

              // Other errors
              throw new Error(`Logout failed with status ${response.status}`);
            }
            console.log('✓ Logout successful');
            return response.json();
          })
          .then(result => {
            console.log('✓ Session cleared, redirecting...');
            // Redirect to home/login
            window.location.href = '/';
          })
          .catch(error => {
            console.error('❌ Logout error:', error);
            this.isLoggingOut = false;
            alert('Logout failed. Please try again or refresh the page.');
          });
      } catch (error) {
        console.error('❌ Logout error:', error);
        this.isLoggingOut = false;
        alert('Logout error. Please refresh and try again.');
      }
    },
  },

  watch: {
    /**
     * When Laravel sends new locale (after page reload)
     * Respects user's saved language preference from localStorage
     * Only uses backend locale if user hasn't set a preference
     */
    locale(newLocale) {
      // Check if user has a saved language preference first
      const savedLocale = localStorage.getItem('preferred_locale');

      if (savedLocale && ['de', 'en', 'np'].includes(savedLocale)) {
        // User has a saved preference - always respect it
        this.currentLocale = savedLocale;
        if (this.$i18n && this.$i18n.locale !== savedLocale) {
          this.$i18n.locale = savedLocale;
          console.log('✅ Using saved language preference:', savedLocale);
        }
      } else if (newLocale && ['de', 'en', 'np'].includes(newLocale)) {
        // No saved preference and backend sent a locale - use it
        this.currentLocale = newLocale;
        if (this.$i18n && this.$i18n.locale !== newLocale) {
          this.$i18n.locale = newLocale;
          console.log('📡 Backend locale synced to i18n:', newLocale);
        }
      }
      // If both savedLocale and newLocale are empty, don't change anything
    },
  },

  mounted() {
    /**
     * Close mobile menu on escape key press
     */
    this.handleEscapeKey = (event) => {
      if (event.key === 'Escape' && this.showMobileMenu) {
        this.closeMobileMenu();
      }
    };

    document.addEventListener('keydown', this.handleEscapeKey);

    /**
     * Close mobile menu on window resize
     */
    this.handleResize = () => {
      if (window.innerWidth >= 768 && this.showMobileMenu) {
        this.closeMobileMenu();
      }
    };

    window.addEventListener('resize', this.handleResize);
  },

  beforeUnmount() {
    /**
     * Cleanup event listeners (Vue 3 lifecycle hook)
     */
    if (this.handleEscapeKey) {
      document.removeEventListener('keydown', this.handleEscapeKey);
    }
    if (this.handleResize) {
      window.removeEventListener('resize', this.handleResize);
    }
  },
};
</script>

<style scoped>
/* ============================================
   LANGUAGE SELECTOR STYLES
   ============================================ */

/* Fix select dropdown color in Firefox */
select option {
  background-color: #1a365d;
  color: white;
}

/* Ensure select styles work on all browsers */
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

/* ============================================
   ANIMATIONS
   ============================================ */

/* Smooth transitions for interactive elements */
@media (prefers-reduced-motion: no-preference) {
  a,
  button,
  select {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
  }
}

/* Respect user's motion preferences */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* ============================================
   FOCUS STYLES FOR ACCESSIBILITY
   ============================================ */

/* Enhanced focus visible for keyboard navigation */
a:focus-visible,
button:focus-visible,
select:focus-visible {
  outline: 2px solid white;
  outline-offset: 2px;
}

/* ============================================
   RESPONSIVE ADJUSTMENTS
   ============================================ */

/* Mobile-first approach */
@media (max-width: 768px) {
  /* Reduce padding on mobile */
  select {
    font-size: 14px;
  }

  /* Ensure buttons don't get too small */
  a,
  button {
    min-height: 44px;
    padding: 0.5rem 0.75rem;
  }
}

/* ============================================
   TEXT GRADIENT EFFECT
   ============================================ */

/* Subtle text gradient for brand name */
.bg-clip-text {
  background-attachment: fixed;
}

/* ============================================
   COLOR SCHEME
   ============================================ */

/* Ensure high contrast for accessibility */
header {
  background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #1e3a8a 100%);
}

/* ============================================
   PRINT STYLES
   ============================================ */

@media print {
  header {
    position: relative;
    box-shadow: none;
    border: none;
  }

  /* Hide interactive elements in print */
  select,
  a:not([href="/print"]),
  button,
  .mobile-menu {
    display: none;
  }
}
</style>
