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

import welcomeDe from './locales/pages/Welcome/de.json';
import welcomeEn from './locales/pages/Welcome/en.json';
import welcomeNp from './locales/pages/Welcome/np.json';

import authDe from './locales/pages/Auth/de.json';
import authEn from './locales/pages/Auth/en.json';
import authNp from './locales/pages/Auth/np.json';

import electionDe from './locales/pages/Election/de.json';
import electionEn from './locales/pages/Election/en.json';
import electionNp from './locales/pages/Election/np.json';

import electionDashboardDe from './locales/pages/Dashboard/ElectionDashboard/de.json';
import electionDashboardEn from './locales/pages/Dashboard/ElectionDashboard/en.json';
import electionDashboardNp from './locales/pages/Dashboard/ElectionDashboard/np.json';

import electionNavigationDe from './locales/pages/ElectionNavigation/de.json';
import electionNavigationEn from './locales/pages/ElectionNavigation/en.json';
import electionNavigationNp from './locales/pages/ElectionNavigation/np.json';

import createCodeDe from './locales/pages/Code/CreateCode/de.json';
import createCodeEn from './locales/pages/Code/CreateCode/en.json';
import createCodeNp from './locales/pages/Code/CreateCode/np.json';

import agreementDe from './locales/pages/Code/Agreement/de.json';
import agreementEn from './locales/pages/Code/Agreement/en.json';
import agreementNp from './locales/pages/Code/Agreement/np.json';

import votingDe from './locales/pages/Voting/de.json';
import votingEn from './locales/pages/Voting/en.json';
import votingNp from './locales/pages/Voting/np.json';

import voteVerifyDe from './locales/pages/VoteVerify/de.json';
import voteVerifyEn from './locales/pages/VoteVerify/en.json';
import voteVerifyNp from './locales/pages/VoteVerify/np.json';

import voteShowVerifyDe from './locales/pages/VoteShowVerify/de.json';
import voteShowVerifyEn from './locales/pages/VoteShowVerify/en.json';
import voteShowVerifyNp from './locales/pages/VoteShowVerify/np.json';

import voteFinalDe from './locales/pages/VoteFinal/de.json';
import voteFinalEn from './locales/pages/VoteFinal/en.json';
import voteFinalNp from './locales/pages/VoteFinal/np.json';

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
      welcome: welcomeDe,
      auth: authDe,
      election: electionDe,
      'election-dashboard': electionDashboardDe,
      'election-navigation': electionNavigationDe,
      'code-create': createCodeDe,
      'code-agreement': agreementDe,
      voting: votingDe,
      'vote-verify': voteVerifyDe,
      'vote-show-verify': voteShowVerifyDe,
      'vote-final': voteFinalDe,
    },
  },
  en: {
    ...en,
    pages: {
      'voting-start': votingStartEn,
      'voting-election': votingElectionEn,
      pricing: pricingEn,
      welcome: welcomeEn,
      auth: authEn,
      election: electionEn,
      'election-dashboard': electionDashboardEn,
      'election-navigation': electionNavigationEn,
      'code-create': createCodeEn,
      'code-agreement': agreementEn,
      voting: votingEn,
      'vote-verify': voteVerifyEn,
      'vote-show-verify': voteShowVerifyEn,
      'vote-final': voteFinalEn,
    },
  },
  np: {
    ...np,
    pages: {
      'voting-start': votingStartNp,
      'voting-election': votingElectionNp,
      pricing: pricingNp,
      welcome: welcomeNp,
      auth: authNp,
      election: electionNp,
      'election-dashboard': electionDashboardNp,
      'election-navigation': electionNavigationNp,
      'code-create': createCodeNp,
      'code-agreement': agreementNp,
      voting: votingNp,
      'vote-verify': voteVerifyNp,
      'vote-show-verify': voteShowVerifyNp,
      'vote-final': voteFinalNp,
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
