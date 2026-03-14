import { createI18n } from 'vue-i18n';
import de from './locales/de.json';
import en from './locales/en.json';
import np from './locales/np.json';

// Import common translations
import commonDe from './locales/common/de.json';
import commonEn from './locales/common/en.json';
import commonNp from './locales/common/np.json';

// Import page-specific translations
import votingStartDe from './locales/pages/voting-start/de.json';
import votingStartEn from './locales/pages/voting-start/en.json';
import votingStartNp from './locales/pages/voting-start/np.json';

import roleSelectionDe from './locales/pages/RoleSelection/de.json';
import roleSelectionEn from './locales/pages/RoleSelection/en.json';
import roleSelectionNp from './locales/pages/RoleSelection/np.json';

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

import verifyEmailDe from './locales/pages/Auth/VerifyEmail/de.json';
import verifyEmailEn from './locales/pages/Auth/VerifyEmail/en.json';
import verifyEmailNp from './locales/pages/Auth/VerifyEmail/np.json';

import forgotPasswordDe from './locales/pages/Auth/ForgotPassword/de.json';
import forgotPasswordEn from './locales/pages/Auth/ForgotPassword/en.json';
import forgotPasswordNp from './locales/pages/Auth/ForgotPassword/np.json';

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

import adminDe from './locales/pages/Admin/de.json';
import adminEn from './locales/pages/Admin/en.json';
import adminNp from './locales/pages/Admin/np.json';

import commissionDe from './locales/pages/Commission/de.json';
import commissionEn from './locales/pages/Commission/en.json';
import commissionNp from './locales/pages/Commission/np.json';

import voteDashboardDe from './locales/pages/Vote/Dashboard/de.json';
import voteDashboardEn from './locales/pages/Vote/Dashboard/en.json';
import voteDashboardNp from './locales/pages/Vote/Dashboard/np.json';

import voteDemoCreateDe from './locales/pages/Vote/DemoVote/Create/de.json';
import voteDemoCreateEn from './locales/pages/Vote/DemoVote/Create/en.json';
import voteDemoCreateNp from './locales/pages/Vote/DemoVote/Create/np.json';

import welcomeDashboardDe from './locales/pages/Welcome/Dashboard/de.json';
import welcomeDashboardEn from './locales/pages/Welcome/Dashboard/en.json';
import welcomeDashboardNp from './locales/pages/Welcome/Dashboard/np.json';

import organisationDe from './locales/pages/Dashboard/organisation/de.json';
import organisationEn from './locales/pages/Dashboard/organisation/en.json';
import organisationNp from './locales/pages/Dashboard/organisation/np.json';

import organisationShowDe from './locales/pages/Organisation/de.json';
import organisationShowEn from './locales/pages/Organisation/en.json';
import organisationShowNp from './locales/pages/Organisation/np.json';

import organisationShowPageDe from './locales/pages/Organisations/Show/de.json';
import organisationShowPageEn from './locales/pages/Organisations/Show/en.json';
import organisationShowPageNp from './locales/pages/Organisations/Show/np.json';

import securityDe from './locales/pages/Security/de.json';
import securityEn from './locales/pages/Security/en.json';
import securityNp from './locales/pages/Security/np.json';

import votingSecurityDe from './locales/pages/VotingSecurity/de.json';
import votingSecurityEn from './locales/pages/VotingSecurity/en.json';
import votingSecurityNp from './locales/pages/VotingSecurity/np.json';

// Get locale from multiple sources in priority order
function getInitialLocale() {
  // 1. Check for server-provided locale (from Inertia props - highest priority)
  // This will be injected via window variable by app.js
  if (typeof window !== 'undefined' && window.__initialLocale) {
    const serverLocale = window.__initialLocale;
    if (['de', 'en', 'np'].includes(serverLocale)) {
      return serverLocale;
    }
  }

  // 2. Check localStorage first (user's saved preference)
  if (typeof localStorage !== 'undefined') {
    const saved = localStorage.getItem('preferred_locale');
    if (saved && ['de', 'en', 'np'].includes(saved)) {
      return saved;
    }
  }

  // 3. Check environment variable
  const envLocale = process.env.MIX_DEFAULT_LOCALE || 'de';
  if (['de', 'en', 'np'].includes(envLocale)) {
    return envLocale;
  }

  // 4. Default to German
  return 'de';
}

const initialLocale = getInitialLocale();

// Merge page-specific translations with core translations
const messages = {
  de: {
    ...de,
    ...commonDe,
    organisation: organisationDe,
    organisations: {
      demo: organisationShowPageDe.demo,
    },
    modals: organisationShowPageDe.modals,
    pages: {
      'voting-start': votingStartDe,
      'role-selection': roleSelectionDe,
      'voting-election': votingElectionDe,
      pricing: pricingDe,
      welcome: welcomeDe,
      auth: authDe,
      'verify-email': verifyEmailDe,
      'forgot-password': forgotPasswordDe,
      election: electionDe,
      'election-dashboard': electionDashboardDe,
      'election-navigation': electionNavigationDe,
      'code-create': createCodeDe,
      'code-agreement': agreementDe,
      voting: votingDe,
      'vote-verify': voteVerifyDe,
      'vote-show-verify': voteShowVerifyDe,
      'vote-final': voteFinalDe,
      'role-selection': roleSelectionDe,
      'admin': adminDe,
      'commission': commissionDe,
      'vote-dashboard': voteDashboardDe,
      'welcome-dashboard': welcomeDashboardDe,
      'organisation-show': { ...organisationShowDe, ...organisationShowPageDe },
      security: securityDe,
      votingSecurity: votingSecurityDe,
      'Vote': {
        'DemoVote': {
          'Create': voteDemoCreateDe
        }
      }
    },
  },
  en: {
    ...en,
    ...commonEn,
    organisation: organisationEn,
    organisations: {
      demo: organisationShowPageEn.demo,
    },
    modals: organisationShowPageEn.modals,
    pages: {
      'voting-start': votingStartEn,
      'role-selection': roleSelectionEn,
      'voting-election': votingElectionEn,
      pricing: pricingEn,
      welcome: welcomeEn,
      auth: authEn,
      'verify-email': verifyEmailEn,
      'forgot-password': forgotPasswordEn,
      election: electionEn,
      'election-dashboard': electionDashboardEn,
      'election-navigation': electionNavigationEn,
      'code-create': createCodeEn,
      'code-agreement': agreementEn,
      voting: votingEn,
      'vote-verify': voteVerifyEn,
      'vote-show-verify': voteShowVerifyEn,
      'vote-final': voteFinalEn,
      'role-selection': roleSelectionEn,
      'admin': adminEn,
      'commission': commissionEn,
      'vote-dashboard': voteDashboardEn,
      'welcome-dashboard': welcomeDashboardEn,
      'organisation-show': { ...organisationShowEn, ...organisationShowPageEn },
      security: securityEn,
      votingSecurity: votingSecurityEn,
      'Vote': {
        'DemoVote': {
          'Create': voteDemoCreateEn
        }
      }
    },
  },
  np: {
    ...np,
    ...commonNp,
    organisation: organisationNp,
    organisations: {
      demo: organisationShowPageNp.demo,
    },
    modals: organisationShowPageNp.modals,
    pages: {
      'voting-start': votingStartNp,
      'role-selection': roleSelectionNp,
      'voting-election': votingElectionNp,
      pricing: pricingNp,
      welcome: welcomeNp,
      auth: authNp,
      'verify-email': verifyEmailNp,
      'forgot-password': forgotPasswordNp,
      election: electionNp,
      'election-dashboard': electionDashboardNp,
      'election-navigation': electionNavigationNp,
      'code-create': createCodeNp,
      'code-agreement': agreementNp,
      voting: votingNp,
      'vote-verify': voteVerifyNp,
      'vote-show-verify': voteShowVerifyNp,
      'vote-final': voteFinalNp,
      'role-selection': roleSelectionNp,
      'admin': adminNp,
      'commission': commissionNp,
      'vote-dashboard': voteDashboardNp,
      'welcome-dashboard': welcomeDashboardNp,
      'organisation-show': { ...organisationShowNp, ...organisationShowPageNp },
      security: securityNp,
      votingSecurity: votingSecurityNp,
      'Vote': {
        'DemoVote': {
          'Create': voteDemoCreateNp
        }
      }
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
  warnHtmlMessage: false,
  onWarn: () => {}, // suppress all vue-i18n warnings
});

export default i18n;
