<template>
    <div class="language-switcher flex items-center gap-2">
        <select
            v-model="currentLocale"
            @change="switchLanguage"
            :disabled="orgForcesLanguage"
            :title="orgForcesLanguage ? 'Your organization has set a default language' : ''"
            class="px-3 py-2 border border-gray-300 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500"
            :class="{ 'opacity-50 cursor-not-allowed': orgForcesLanguage }"
        >
            <option value="de">Deutsch</option>
            <option value="en">English</option>
            <option value="np">नेपाली</option>
        </select>

        <!-- Reset button: clears saved preferences and re-detects location -->
        <button
            @click="resetLanguage"
            type="button"
            title="Clear saved language preference and detect your location automatically"
            class="px-2 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors"
        >
            🔄
        </button>
    </div>
</template>

<script>
export default {
    name: 'LanguageSwitcher',

    data() {
        return {
            currentLocale: this.$i18n.locale,
        };
    },

    computed: {
        orgForcesLanguage() {
            return this.$page.props?.organisation?.default_language !== null
                && this.$page.props?.organisation?.default_language !== undefined;
        },
    },

    methods: {
        switchLanguage() {
            // Change locale
            this.$i18n.locale = this.currentLocale;

            // Set cookie - read by SetLocale middleware on server
            document.cookie = `locale=${this.currentLocale};path=/;max-age=31536000;SameSite=Lax`;

            // Save preference to localStorage (persists across page reloads)
            localStorage.setItem('preferred_locale', this.currentLocale);

            // No need to reload - Vue will reactively update all $t() calls
        },

        resetLanguage() {
            // Clear all stored language preferences
            localStorage.removeItem('preferred_locale');
            document.cookie = 'locale=; max-age=0; path=/';

            // Show debug info in console
            console.log('🔄 Language preferences cleared. Reloading to detect location...');
            console.log('Browser timezone:', Intl.DateTimeFormat().resolvedOptions().timeZone);

            // Reload page to trigger geo-detection in app.js
            location.reload();
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
.language-switcher {
    display: inline-block;
}
</style>
