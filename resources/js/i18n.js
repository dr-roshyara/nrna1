import { createI18n } from 'vue-i18n';
import de from './locales/de.json';
import en from './locales/en.json';
import np from './locales/np.json';

// Import page-specific translations
import votingStartDe from './locales/pages/voting-start/de.json';
import votingStartEn from './locales/pages/voting-start/en.json';
import votingStartNp from './locales/pages/voting-start/np.json';

import votingElectionDe from './locales/pages/voting-election/de.json';
import votingElectionEn from './locales/pages/voting-election/en.json';
import votingElectionNp from './locales/pages/voting-election/np.json';

import pricingDe from './locales/pages/pricing/de.json';
import pricingEn from './locales/pages/pricing/en.json';
import pricingNp from './locales/pages/pricing/np.json';

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

// Merge page-specific translations with core translations
const messages = {
  de: {
    ...de,
    pages: {
      'voting-start': votingStartDe,
      'voting-election': votingElectionDe,
      pricing: pricingDe,
    },
  },
  en: {
    ...en,
    pages: {
      'voting-start': votingStartEn,
      'voting-election': votingElectionEn,
      pricing: pricingEn,
    },
  },
  np: {
    ...np,
    pages: {
      'voting-start': votingStartNp,
      'voting-election': votingElectionNp,
      pricing: pricingNp,
    },
  },
};

// Create i18n instance
const i18n = createI18n({
  legacy: false, // Use Vue 3 Composition API mode
  locale: initialLocale,
  fallbackLocale: 'en',
  messages,
  globalInjection: true,
  missingWarn: false,
  fallbackWarn: false,
});

export default i18n;
