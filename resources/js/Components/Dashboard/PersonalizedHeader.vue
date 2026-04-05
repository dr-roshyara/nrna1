<template>
  <div class="personalized-header" role="banner">
    <div class="header-content">
      <!-- Welcome greeting -->
      <div class="greeting-section">
        <h1 class="welcome-title">
          <span class="greeting-text">
            {{ timeBasedGreeting }}
          </span>
          <span class="wave" aria-hidden="true">👋</span>
        </h1>

        <!-- User context -->
        <div class="user-context" v-if="organizationName || lastLoginText" role="complementary" aria-label="User context information">
          <span v-if="organizationName" class="context-item">
            {{ organizationName }}
          </span>
          <span v-if="userRole" class="context-item" :aria-label="`Role: ${userRole}`">
            • {{ userRole }}
          </span>
          <span v-if="lastLoginText" class="context-item">
            • {{ lastLoginText }}
          </span>
        </div>
      </div>

      <!-- Trust badges with tooltips -->
      <div class="trust-badges-section">
        <div class="trust-badges" role="list" aria-label="Trust indicators">
          <div
            v-for="(signal, index) in displayedTrustSignals"
            :key="signal.id"
            role="listitem"
            class="trust-badge-wrapper"
            :class="{ 'has-tooltip': signal.tooltip_key }"
          >
            <button
              class="trust-badge"
              :class="`trust-${signal.level}`"
              :aria-label="`${$t(signal.message_key)}: ${$t(signal.tooltip_key)}`"
              :aria-expanded="expandedTooltip === index"
              @click="toggleTooltip(index)"
              @keydown.escape="closeTooltip"
              v-click-outside="closeTooltip"
            >
              <span class="badge-icon" aria-hidden="true">{{ signal.icon }}</span>
              <span class="badge-text">{{ $t(signal.message_key) }}</span>
            </button>

            <!-- Tooltip -->
            <transition
              name="tooltip-fade"
              @enter="onTooltipEnter"
              @leave="onTooltipLeave"
            >
              <div
                v-if="expandedTooltip === index && signal.tooltip_key"
                class="tooltip"
                role="tooltip"
                :class="getTooltipPosition(index)"
                @click.stop
              >
                {{ $t(signal.tooltip_key) }}
              </div>
            </transition>
          </div>
        </div>

        <!-- More badges indicator -->
        <div v-if="hasMoreTrustSignals" class="more-badges-indicator">
          <button
            class="more-button"
            @click="showAllBadges = true"
            :aria-label="`Show ${remainingBadgesCount} more trust indicators`"
          >
            +{{ remainingBadgesCount }}
          </button>
        </div>
      </div>
    </div>

    <!-- Expanded badges modal (mobile/accessibility) -->
    <transition name="modal-fade">
      <div
        v-if="showAllBadges"
        class="badges-modal-overlay"
        @click="showAllBadges = false"
        role="dialog"
        aria-modal="true"
        aria-labelledby="all-badges-title"
      >
        <div class="badges-modal" @click.stop>
          <div class="modal-header">
            <h2 id="all-badges-title" class="modal-title">{{ $t('header.all_trust_indicators') }}</h2>
            <button
              class="modal-close"
              @click="showAllBadges = false"
              aria-label="Close trust indicators"
            >
              ✕
            </button>
          </div>

          <div class="badges-grid" role="list">
            <div
              v-for="signal in trustSignals"
              :key="signal.id"
              role="listitem"
              class="badge-item-expanded"
            >
              <div class="badge-icon-large" aria-hidden="true">{{ signal.icon }}</div>
              <div class="badge-content">
                <h3 class="badge-title">{{ $t(signal.message_key) }}</h3>
                <p class="badge-description">{{ $t(signal.tooltip_key) }}</p>
              </div>
              <div class="badge-indicator" :class="`trust-${signal.level}`" aria-hidden="true"></div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { formatDistanceToNow } from 'date-fns';
import { de, enUS } from 'date-fns/locale';

/**
 * PersonalizedHeader Component
 *
 * Displays a personalized welcome message, user context, and trust signals.
 * Features:
 * - Time-based greetings (Good morning/afternoon/evening)
 * - Last login information with relative time formatting
 * - Interactive trust badges with tooltips
 * - Full accessibility support (ARIA labels, keyboard navigation)
 * - Dark mode support
 * - Responsive design with mobile-optimized layouts
 * - Prefers-reduced-motion animation support
 *
 * @component PersonalizedHeader
 */
export default {
  name: 'PersonalizedHeader',

  /**
   * Props configuration
   */
  props: {
    /**
     * User object from backend (DashboardController)
     * @type {Object}
     * @property {string} display_name - User's display name (with consent check)
     * @property {string} identifier - Pseudonymized user identifier
     * @property {string} preferred_language - User's language preference
     */
    user: {
      type: Object,
      required: true,
      validator(value) {
        // Accept object with display_name or name property
        return value && (typeof value.display_name === 'string' || typeof value.name === 'string');
      },
    },

    /**
     * Organization name to display in context
     * @type {string}
     */
    organizationName: {
      type: String,
      default: null,
    },

    /**
     * User state object containing roles
     * @type {Object}
     * @property {Array<string>} roles - Array of user role strings
     */
    userState: {
      type: Object,
      required: true,
      default: () => ({ roles: [] }),
    },

    /**
     * Array of trust signals to display
     * @type {Array<Object>}
     * @property {string} id - Unique identifier
     * @property {string} icon - Emoji or icon character
     * @property {string} message_key - i18n key for badge text
     * @property {string} tooltip_key - i18n key for tooltip text
     * @property {number} level - Trust level (1, 2, or 3)
     */
    trustSignals: {
      type: Array,
      default: () => [],
      validator(value) {
        return Array.isArray(value) && value.every((signal) =>
          signal.id && signal.message_key && signal.tooltip_key && signal.icon
        );
      },
    },

    /**
     * Current user's locale for date formatting
     * @type {string}
     */
    locale: {
      type: String,
      default: 'de',
      validator(value) {
        return ['de', 'en', 'np'].includes(value);
      },
    },

    /**
     * Whether to show the wave animation (for prefers-reduced-motion)
     * @type {boolean}
     */
    showAnimations: {
      type: Boolean,
      default: true,
    },
  },

  /**
   * Component data
   */
  data() {
    return {
      /**
       * Currently expanded tooltip index (-1 if none)
       * @type {number}
       */
      expandedTooltip: -1,

      /**
       * Whether to show the expanded badges modal
       * @type {boolean}
       */
      showAllBadges: false,

      /**
       * Maximum number of badges to show inline
       * @type {number}
       */
      maxInlineBadges: 3,
    };
  },

  /**
   * Computed properties
   */
  computed: {
    /**
     * Get locale object for date-fns formatting
     * Maps app locales to date-fns locales (note: Nepali not available in date-fns, uses English fallback)
     * @returns {Object} date-fns locale object
     */
    dateLocale() {
      const locales = {
        de,         // German
        en: enUS,   // English (US)
        np: enUS,   // Nepali - fallback to English (date-fns doesn't support Nepali locale)
      };
      return locales[this.$i18n.locale] || de;
    },

    /**
     * Get user role label from userState.roles
     * @returns {string|null} Formatted role string or null
     */
    userRole() {
      const roles = this.userState?.roles || [];
      if (roles.length === 0) return null;
      if (roles.length === 1) {
        return this.getRoleLabel(roles[0]);
      }
      return `${roles.length} ${this.$t('header.roles')}`;
    },

    /**
     * Get formatted last login text
     * @returns {string|null} Formatted last login message
     */
    lastLoginText() {
      if (!this.user?.last_login_at) return null;

      try {
        const lastLogin = new Date(this.user.last_login_at);

        // Validate the date is valid
        if (isNaN(lastLogin.getTime())) {
          return null;
        }

        const relativeTime = formatDistanceToNow(lastLogin, {
          locale: this.dateLocale,
          addSuffix: true,
        });

        return `${this.$t('header.last_login')}: ${relativeTime}`;
      } catch (error) {
        // Silently fail - don't show broken date
        return null;
      }
    },

    /**
     * Get professional greeting with user's actual name
     * @returns {string} Localized greeting "Willkommen {name}"
     */
    timeBasedGreeting() {
      // Get user's display name from backend
      const displayName = this.user?.display_name
        || this.user?.name
        || this.$t('header.user');
      return this.$t('header.greeting', { name: displayName });
    },

    /**
     * Get trust signals to display inline (first N)
     * @returns {Array} Sliced trust signals array
     */
    displayedTrustSignals() {
      return this.trustSignals.slice(0, this.maxInlineBadges);
    },

    /**
     * Check if there are more trust signals than can be displayed
     * @returns {boolean} Whether more signals exist
     */
    hasMoreTrustSignals() {
      return this.trustSignals.length > this.maxInlineBadges;
    },

    /**
     * Count of remaining trust signals not shown inline
     * @returns {number} Count of hidden signals
     */
    remainingBadgesCount() {
      return Math.max(0, this.trustSignals.length - this.maxInlineBadges);
    },
  },

  /**
   * Lifecycle hooks
   */
  mounted() {
    // No lifecycle setup needed
  },

  beforeUnmount() {
    // No cleanup needed
  },

  /**
   * Methods
   */
  methods: {
    /**
     * Get localized label for a role
     * @param {string} role - Role identifier
     * @returns {string} Localized role label
     */
    getRoleLabel(role) {
      const roleKey = `header.role_${role}`;
      try {
        const label = this.$t(roleKey);
        // Check if translation exists or fallback to role name
        return label !== roleKey ? label : role;
      } catch {
        return role;
      }
    },

    /**
     * Toggle tooltip visibility for a badge
     * @param {number} index - Index of the badge
     */
    toggleTooltip(index) {
      this.expandedTooltip = this.expandedTooltip === index ? -1 : index;
    },

    /**
     * Close the currently open tooltip
     */
    closeTooltip() {
      this.expandedTooltip = -1;
    },

    /**
     * Get tooltip position class to prevent overflow
     * @param {number} index - Index of badge
     * @returns {string} CSS class for positioning
     */
    getTooltipPosition(index) {
      const totalBadges = this.displayedTrustSignals.length;
      // Position left tooltip on first badge, right on last
      if (index === 0) return 'tooltip-left';
      if (index === totalBadges - 1) return 'tooltip-right';
      return 'tooltip-center';
    },

    /**
     * Animation enter hook for tooltip
     * @param {HTMLElement} el - Element being animated
     */
    onTooltipEnter(el) {
      if (!this.showAnimations) {
        el.style.opacity = '1';
        return;
      }
      el.style.opacity = '0';
      // Trigger animation in next frame
      this.$nextTick(() => {
        el.style.transition = 'opacity 150ms ease-out';
        el.style.opacity = '1';
      });
    },

    /**
     * Animation leave hook for tooltip
     * @param {HTMLElement} el - Element being animated
     */
    onTooltipLeave(el) {
      if (!this.showAnimations) {
        el.style.opacity = '0';
        return;
      }
      el.style.transition = 'opacity 150ms ease-in';
      el.style.opacity = '0';
    },
  },
};
</script>

<style scoped>
/**
 * CSS Custom Properties for theming
 * Ensure these are defined in your global CSS or tailwind config
 */
:root {
  /* Light mode colors */
  --color-primary-50: #f0f4ff;
  --color-primary-200: #b8d4ff;
  --color-primary-300: #8ab8ff;
  --color-blue-50: #eff6ff;
  --color-blue-200: #bfdbfe;
  --color-blue-700: #1d4ed8;
  --color-green-50: #f0fdf4;
  --color-green-200: #86efac;
  --color-green-700: #15803d;
  --color-purple-50: #faf5ff;
  --color-purple-200: #e9d5ff;
  --color-purple-700: #6d28d9;
  --color-gray-50: #f9fafb;
  --color-gray-100: #f3f4f6;
  --color-gray-200: #e5e7eb;
  --color-gray-600: #4b5563;
  --color-gray-700: #374151;
  --color-gray-900: #111827;

  /* Dark mode colors */
  --color-dark-bg: #1f2937;
  --color-dark-surface: #111827;
  --color-dark-text: #f3f4f6;
}

.dark {
  --color-primary-50: #0f1728;
  --color-primary-200: #1e3a8a;
  --color-blue-50: #0c1117;
  --color-blue-200: #1e40af;
  --color-blue-700: #93c5fd;
  --color-green-50: #051b15;
  --color-green-200: #22c55e;
  --color-green-700: #86efac;
  --color-purple-50: #2e1065;
  --color-purple-200: #a78bfa;
  --color-purple-700: #e9d5ff;
  --color-gray-50: #f9fafb;
  --color-gray-100: #f3f4f6;
  --color-gray-200: #374151;
  --color-gray-600: #d1d5db;
  --color-gray-700: #e5e7eb;
  --color-gray-900: #f3f4f6;
}

/* Main container */
.personalized-header {
  background: linear-gradient(135deg, var(--color-primary-50) 0%, var(--color-blue-50) 100%);
  border-bottom: 1px solid var(--color-gray-200);
  padding: clamp(1.5rem, 5vw, 2rem);
  margin-bottom: clamp(1.5rem, 4vw, 2rem);
  border-radius: 12px;

  /* Smooth color transition for theme changes */
  transition: background-color 300ms ease-out, border-color 300ms ease-out;
}

.dark .personalized-header {
  background: linear-gradient(135deg, #1a2f4a 0%, #0f1f35 100%);
  border-bottom-color: var(--color-gray-200);
}

/* Content wrapper */
.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: clamp(1rem, 3vw, 1.5rem);
}

/* Greeting section */
.greeting-section {
  margin-bottom: 0;
}

.welcome-title {
  font-size: clamp(1.5rem, 5vw, 2rem);
  font-weight: 700;
  color: var(--color-gray-900);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  line-height: 1.2;
  letter-spacing: -0.5px;
}

.dark .welcome-title {
  color: var(--color-gray-50);
}

.greeting-text {
  display: inline-block;
  word-spacing: 100vw;
}

/* Wave animation with prefers-reduced-motion support */
.wave {
  display: inline-block;
  min-width: 1.5rem;
}

@media (prefers-reduced-motion: no-preference) {
  .wave {
    animation: wave 2s ease-in-out infinite;
  }
}

@keyframes wave {
  0%, 100% {
    transform: rotate(0deg);
  }
  50% {
    transform: rotate(20deg);
  }
}

/* User context */
.user-context {
  display: flex;
  gap: 1rem;
  font-size: clamp(0.8rem, 2vw, 0.95rem);
  color: var(--color-gray-600);
  margin-top: 0.5rem;
  flex-wrap: wrap;
  line-height: 1.4;
}

.dark .user-context {
  color: var(--color-gray-600);
}

.context-item {
  display: inline-flex;
  align-items: center;
  white-space: nowrap;
}

/* Trust badges section */
.trust-badges-section {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.trust-badges {
  display: flex;
  gap: clamp(0.5rem, 2vw, 1rem);
  flex-wrap: wrap;
  list-style: none;
  margin: 0;
  padding: 0;
}

/* Badge wrapper for tooltip positioning */
.trust-badge-wrapper {
  position: relative;
  display: inline-block;
}

/* Trust badge button */
.trust-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: clamp(0.4rem, 1.5vw, 0.5rem) clamp(0.75rem, 2vw, 1rem);
  background-color: white;
  border: 1.5px solid var(--color-gray-200);
  border-radius: 20px;
  font-size: clamp(0.75rem, 1.5vw, 0.85rem);
  font-weight: 500;
  cursor: pointer;
  transition: all 200ms ease;
  white-space: nowrap;

  /* Accessibility */
  font-family: inherit;
  text-decoration: none;
  outline: none;

  /* Touch target size */
  min-height: 2.5rem;
}

/* Focus state for keyboard navigation */
.trust-badge:focus-visible {
  outline: 2px solid var(--color-primary-200);
  outline-offset: 2px;
}

/* Hover state */
.trust-badge:hover {
  border-color: var(--color-primary-300);
  background-color: var(--color-primary-50);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.dark .trust-badge:hover {
  border-color: #3b82f6;
  background-color: #1e40af;
}

/* Active state */
.trust-badge[aria-expanded="true"] {
  border-color: var(--color-primary-300);
  background-color: var(--color-primary-50);
}

/* Dark mode badge styles */
.dark .trust-badge {
  background-color: #1f2937;
  border-color: #374151;
}

.dark .trust-badge:hover {
  background-color: #374151;
}

/* Badge icon */
.badge-icon {
  font-size: 1.1rem;
  min-width: 1.2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

/* Badge text */
.badge-text {
  color: var(--color-gray-700);
  transition: color 200ms ease;
}

.dark .badge-text {
  color: var(--color-gray-300);
}

/* Trust level styles */
.trust-1 {
  border-color: var(--color-blue-200);
  background-color: var(--color-blue-50);
}

.trust-1 .badge-text {
  color: var(--color-blue-700);
}

.trust-2 {
  border-color: var(--color-green-200);
  background-color: var(--color-green-50);
}

.trust-2 .badge-text {
  color: var(--color-green-700);
}

.trust-3 {
  border-color: var(--color-purple-200);
  background-color: var(--color-purple-50);
}

.trust-3 .badge-text {
  color: var(--color-purple-700);
}

.dark .trust-1 {
  border-color: #1e40af;
  background-color: #0c1117;
}

.dark .trust-1 .badge-text {
  color: var(--color-blue-200);
}

.dark .trust-2 {
  border-color: #16a34a;
  background-color: #051b15;
}

.dark .trust-2 .badge-text {
  color: var(--color-green-300);
}

.dark .trust-3 {
  border-color: #7c3aed;
  background-color: #2e1065;
}

.dark .trust-3 .badge-text {
  color: var(--color-purple-300);
}

/* Tooltip styles */
.tooltip {
  position: absolute;
  bottom: 100%;
  margin-bottom: 0.5rem;
  padding: 0.75rem 1rem;
  background-color: var(--color-gray-900);
  color: white;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 500;
  white-space: normal;
  max-width: 200px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
  z-index: 100;
  pointer-events: auto;
  word-wrap: break-word;
  line-height: 1.4;

  /* Dark mode */
  background-color: var(--color-gray-900);
  color: #f3f4f6;
}

.dark .tooltip {
  background-color: #111827;
  color: #f3f4f6;
}

/* Tooltip arrow */
.tooltip::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  width: 8px;
  height: 8px;
  background-color: var(--color-gray-900);
  border-radius: 1px;
  box-shadow: -1px -1px 2px rgba(0, 0, 0, 0.1);
}

.dark .tooltip::after {
  background-color: #111827;
}

/* Tooltip position variants */
.tooltip-left {
  left: 0;
}

.tooltip-left::after {
  left: 1.5rem;
}

.tooltip-right {
  right: 0;
  left: auto;
}

.tooltip-right::after {
  left: auto;
  right: 1.5rem;
}

.tooltip-center {
  left: 50%;
  transform: translateX(-50%);
}

.tooltip-center::after {
  left: 50%;
}

/* More badges indicator */
.more-badges-indicator {
  display: inline-block;
}

.more-button {
  padding: 0.4rem 0.8rem;
  background-color: var(--color-gray-50);
  border: 1.5px solid var(--color-gray-200);
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--color-gray-700);
  cursor: pointer;
  transition: all 200ms ease;
  min-height: 2.5rem;
  outline: none;
  font-family: inherit;
}

.more-button:focus-visible {
  outline: 2px solid var(--color-primary-200);
  outline-offset: 2px;
}

.more-button:hover {
  background-color: var(--color-primary-50);
  border-color: var(--color-primary-200);
  color: var(--color-primary-700);
}

.dark .more-button {
  background-color: #374151;
  border-color: #4b5563;
  color: var(--color-gray-300);
}

.dark .more-button:hover {
  background-color: #4b5563;
  border-color: #6b7280;
}

/* Badges modal overlay */
.badges-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: flex-end;
  z-index: 1000;
  padding: 1rem;

  @media (min-width: 640px) {
    align-items: center;
  }
}

.badges-modal {
  background-color: white;
  border-radius: 12px;
  max-width: 500px;
  width: 100%;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.dark .badges-modal {
  background-color: var(--color-dark-bg);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid var(--color-gray-200);
}

.dark .modal-header {
  border-bottom-color: #374151;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-gray-900);
  margin: 0;
}

.dark .modal-title {
  color: var(--color-gray-50);
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: var(--color-gray-600);
  cursor: pointer;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  outline: none;
  transition: color 200ms ease;
}

.modal-close:focus-visible {
  outline: 2px solid var(--color-primary-200);
  outline-offset: 2px;
}

.modal-close:hover {
  color: var(--color-gray-900);
}

.dark .modal-close:hover {
  color: var(--color-gray-50);
}

.badges-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  padding: 1.5rem;
  overflow-y: auto;
  list-style: none;
  margin: 0;
}

.badge-item-expanded {
  display: flex;
  gap: 1rem;
  align-items: flex-start;
  padding: 1rem;
  background-color: var(--color-gray-50);
  border-radius: 8px;
  border-left: 4px solid var(--color-primary-200);
}

.dark .badge-item-expanded {
  background-color: #374151;
  border-left-color: #1e40af;
}

.badge-icon-large {
  font-size: 2rem;
  min-width: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.badge-content {
  flex: 1;
  min-width: 0;
}

.badge-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--color-gray-900);
  margin: 0 0 0.5rem 0;
}

.dark .badge-title {
  color: var(--color-gray-50);
}

.badge-description {
  font-size: 0.85rem;
  color: var(--color-gray-600);
  margin: 0;
  line-height: 1.5;
}

.dark .badge-description {
  color: var(--color-gray-400);
}

.badge-indicator {
  flex-shrink: 0;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  margin-top: 0.25rem;
}

.badge-indicator.trust-1 {
  background-color: var(--color-blue-700);
}

.badge-indicator.trust-2 {
  background-color: var(--color-green-700);
}

.badge-indicator.trust-3 {
  background-color: var(--color-purple-700);
}

/* Transitions */
.tooltip-fade-enter-active,
.tooltip-fade-leave-active {
  transition: opacity 150ms ease-out;
}

.tooltip-fade-enter-from,
.tooltip-fade-leave-to {
  opacity: 0;
}

.tooltip-fade-enter-to,
.tooltip-fade-leave-from {
  opacity: 1;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 200ms ease-out;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-fade-enter-to,
.modal-fade-leave-from {
  opacity: 1;
}

/* Responsive design */
@media (max-width: 768px) {
  .personalized-header {
    padding: clamp(1rem, 4vw, 1.5rem);
    margin-bottom: clamp(1rem, 3vw, 1.5rem);
  }

  .welcome-title {
    font-size: clamp(1.25rem, 4vw, 1.5rem);
  }

  .user-context {
    gap: 0.5rem;
    font-size: 0.8rem;
  }

  .header-content {
    gap: 1rem;
  }

  .trust-badges-section {
    gap: 0.75rem;
  }

  .trust-badges {
    gap: 0.5rem;
  }

  .trust-badge {
    padding: 0.35rem 0.7rem;
    font-size: 0.75rem;
    min-height: auto;
  }

  .badge-icon {
    font-size: 0.95rem;
    min-width: 1rem;
  }

  .more-button {
    padding: 0.35rem 0.7rem;
    font-size: 0.75rem;
    min-height: auto;
  }

  /* Stack badges on very small screens */
  @media (max-width: 480px) {
    .trust-badges {
      width: 100%;
    }

    .trust-badge {
      flex: 1;
      justify-content: center;
      min-width: 70px;
    }

    .badge-text {
      display: none;
    }

    .badge-icon {
      font-size: 1rem;
    }

    /* Show more button with text on small screens */
    .more-button {
      display: none;
    }

    .maxInlineBadges {
      max-inline-size: 2;
    }
  }
}

/* Large screens */
@media (min-width: 1024px) {
  .personalized-header {
    padding: 2rem 2.5rem;
    margin-bottom: 2.5rem;
  }

  .header-content {
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
  }

  .greeting-section {
    flex: 1;
  }

  .trust-badges-section {
    flex-shrink: 0;
  }
}

/* Ultra-wide screens */
@media (min-width: 1536px) {
  .personalized-header {
    padding: 2.5rem 3rem;
  }

  .welcome-title {
    font-size: 2.25rem;
  }
}

/* Accessibility: High contrast mode */
@media (prefers-contrast: more) {
  .trust-badge {
    border-width: 2px;
  }

  .tooltip {
    border: 1px solid rgba(0, 0, 0, 0.2);
  }
}

/* Accessibility: Reduced motion */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }

  .wave {
    animation: none;
  }
}

/* Print styles */
@media print {
  .personalized-header {
    background: none;
    border: none;
    padding: 0;
    margin-bottom: 1rem;
  }

  .trust-badges {
    display: none;
  }

  .tooltip {
    display: none;
  }

  .more-button {
    display: none;
  }
}
</style>
