<template>
  <header class="sticky top-0 z-40 bg-gradient-to-r from-blue-900 to-blue-700 text-white shadow-lg">
    <div class="container mx-auto px-3 md:px-6 lg:px-8">
      <!-- Top Row: Logo + Controls -->
      <div class="flex items-center justify-between py-3 md:py-4">
        <!-- Logo and Branding -->
        <div class="flex items-center space-x-2 flex-1 min-w-0">
          <img src="/images/logo-2.png" alt="PUBLIC DIGIT" class="w-10 h-10 md:w-12 md:h-12 object-contain flex-shrink-0" />
          <div class="flex flex-col min-w-0">
            <h1 class="text-sm md:text-lg font-bold leading-tight truncate">
              {{ $t('platform.name') }}
            </h1>
            <span class="text-xs md:text-sm font-normal text-blue-200 truncate hidden sm:block">
              {{ $t('platform.tagline') }}
            </span>
          </div>
        </div>

        <!-- Right Controls: Language + Auth -->
        <div class="flex items-center gap-2 md:gap-4 ml-2 flex-shrink-0">
          <!-- Language Selector - Compact on Mobile -->
          <div class="relative">
            <select
              v-model="currentLocale"
              @change="switchLanguage"
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

          <!-- Auth Links - Responsive -->
          <div class="flex gap-2">
            <a
              v-if="!isLoggedIn"
              :href="route('login')"
              class="inline-flex items-center px-3 md:px-4 py-2 bg-white text-blue-900 font-semibold text-xs md:text-sm rounded hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition-colors whitespace-nowrap"
            >
              {{ $t('navigation.login') }}
            </a>
            <!-- Logout form - POST request required -->
            <form v-if="isLoggedIn" @submit.prevent="logout" class="inline">
              <button
                type="submit"
                class="inline-flex items-center px-3 md:px-4 py-2 border-2 border-white text-white font-semibold text-xs md:text-sm rounded hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition-colors whitespace-nowrap"
              >
                {{ $t('navigation.logout') }}
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Navigation Row - Desktop Only -->
      <nav class="hidden md:flex items-center justify-between py-3 border-t border-blue-600/50">
        <div class="flex items-center space-x-6">
          <a href="/" class="text-white font-medium hover:text-blue-200 transition-colors text-sm">
            {{ $t('navigation.home') }}
          </a>
          <a href="#about" class="text-white font-medium hover:text-blue-200 transition-colors text-sm">
            {{ $t('navigation.about') }}
          </a>
          <a href="#faq" class="text-white font-medium hover:text-blue-200 transition-colors text-sm">
            {{ $t('navigation.faq') }}
          </a>
        </div>

        <!-- Demo Link - Special CTA -->
        <a
          href="/election/demo/start"
          class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white font-semibold text-sm rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition-colors whitespace-nowrap"
          :title="$t('navigation.demo_title', 'Try demo election without registration')"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M10.5 1.5H19a.5.5 0 01.5.5v8a.5.5 0 01-.5.5h-8.5V19a.5.5 0 01-.5.5H1a.5.5 0 01-.5-.5v-8a.5.5 0 01.5-.5H9V2a.5.5 0 01.5-.5z"/>
          </svg>
          {{ $t('navigation.demo', 'Try Demo') }}
        </a>
      </nav>
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
  },

  data() {
    return {
      currentLocale: this.$i18n?.locale || 'de',
    };
  },

  methods: {
    switchLanguage() {
      if (this.$i18n) {
        this.$i18n.locale = this.currentLocale;
        localStorage.setItem('preferred_locale', this.currentLocale);
      }
    },
    logout() {
      this.$inertia.post(route('logout'));
    },
  },

  watch: {
    '$i18n.locale'(newLocale) {
      this.currentLocale = newLocale;
    },
  },
};
</script>

<style scoped>
/* Fix select dropdown color in Firefox */
select option {
  background-color: #1a365d;
  color: white;
}
</style>
