<template>
    <div class="language-switcher">
        <select
            v-model="currentLocale"
            @change="switchLanguage"
            class="px-3 py-2 border border-gray-300 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500"
        >
            <option value="de">Deutsch</option>
            <option value="en">English</option>
            <option value="np">नेपाली</option>
        </select>
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

    methods: {
        switchLanguage() {
            // Change locale
            this.$i18n.locale = this.currentLocale;

            // Save preference to localStorage (persists across page reloads)
            localStorage.setItem('preferred_locale', this.currentLocale);

            // No need to reload - Vue will reactively update all $t() calls
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
