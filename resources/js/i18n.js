import { createI18n } from 'vue-i18n';
import de from './locales/de.json';
import en from './locales/en.json';
import np from './locales/np.json';

// Get locale from multiple sources in priority order
function getInitialLocale() {
  // 1. Check localStorage first (user's saved preference)
  if (typeof localStorage !== 'undefined') {
    const saved = localStorage.getItem('preferred_locale');
    if (saved && ['de', 'en', 'np'].includes(saved)) {
      return saved;
    }
  }

  // 2. Check environment variable
  const envLocale = process.env.MIX_DEFAULT_LOCALE || 'de';
  if (['de', 'en', 'np'].includes(envLocale)) {
    return envLocale;
  }

  // 3. Default to German
  return 'de';
}

const initialLocale = getInitialLocale();

// Create i18n instance
const i18n = createI18n({
  legacy: false, // Use Vue 3 Composition API mode
  locale: initialLocale,
  fallbackLocale: 'en',
  messages: {
    de,
    en,
    np,
  },
  globalInjection: true,
  missingWarn: false,
  fallbackWarn: false,
});

export default i18n;
