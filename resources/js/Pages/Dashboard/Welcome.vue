<template>
  <DashboardLayout>
    <!-- Utility Layer: Header with trust signals -->
    <PersonalizedHeader
      :user="user"
      :organization-name="organizationName"
      :user-state="userState"
      :trust-signals="trustSignals"
    />

    <main class="welcome-container">
      <!-- Alert Space: Pending actions (critical) -->
      <section v-if="hasPendingActions" class="alert-space">
        <PendingActionsBlock :pending-actions="userState.pending_actions" />
      </section>

      <!-- Main Grid: 2-column on desktop, 1-column on mobile -->
      <div class="layout-grid">

        <!-- Main Column: Primary actions and content -->
        <div class="main-column">

          <!-- Context Layer: Organization Status (progress indicator) -->
          <div v-if="hasStatusBlock" class="card-glass mb-8">
            <OrganizationStatusBlock
              :user-state="userState"
              :onboarding-step="userState.onboarding_step"
            />
          </div>

          <!-- Action Layer: Quick Start (primary goal) -->
          <QuickStartGrid
            v-if="hasActionBlock"
            :cards="filteredActionCards"
            :title="$t('dashboard.quick_start_title')"
            :subtitle="$t('dashboard.quick_start_subtitle')"
            class="mb-12"
            @card-clicked="handleActionClick"
          />

          <!-- Content blocks from pipeline -->
          <div v-if="contentBlocks.length > 0" class="pipeline-blocks">
            <component
              v-for="block in contentBlocks"
              :key="block.id"
              :is="getBlockComponent(block)"
              :block="block"
              :user-state="userState"
              class="block-item"
            />
          </div>

          <!-- Trust Center Banner -->
          <TrustCenterBanner class="mt-12" />

          <!-- Metrics Counter Section -->
          <MetricCounter
            class="mt-12"
            :title="$t('metrics.title', { fallback: 'Platform Impact' })"
            :subtitle="$t('metrics.subtitle', { fallback: 'Real-time statistics across all organizations' })"
            :metrics="metricsData"
            :animated="true"
            :duration="2000"
            :footnote="$t('metrics.footnote', { fallback: 'Updated in real-time across all organizations' })"
            @complete="handleMetricsComplete"
          />

          <!-- Insights Accordion -->
          <InsightsAccordion
            class="mt-12"
            :title="$t('insights.title', { fallback: 'Get Started' })"
            :subtitle="$t('insights.subtitle', { fallback: 'Learn key features and best practices' })"
            :icon="$t('insights.icon', { fallback: '💡' })"
            :items="insightsData"
            :show-feedback="true"
            @item-action="handleInsightAction"
            @item-feedback="handleInsightFeedback"
          />

          <!-- Features Tabs -->
          <FeatureTabs
            class="mt-12"
            :title="$t('features.title', { fallback: 'Why Choose Public Digit?' })"
            :subtitle="$t('features.subtitle', { fallback: 'Powerful features for democratic decision-making' })"
            :features="featuresData"
            @action-click="handleFeatureAction"
          />

          <!-- Success Stories Carousel -->
          <SuccessCarousel
            v-if="!isNewUser && showUserCases"
            class="mt-12"
            :title="$t('success.title', { fallback: 'Success Stories' })"
            :subtitle="$t('success.subtitle', { fallback: 'See how organizations are transforming with Public Digit' })"
            :cases="successCases"
            :autoplay="true"
            :autoplay-interval="8000"
            @action-click="handleSuccessAction"
          />
        </div>

      </div>
    </main>

    <!-- Trust Badge Bar (sticky footer) -->
    <TrustBadgeBar
      @open-compliance="handleComplianceOpen"
      @dismiss="handleTrustDismiss"
    />

    <!-- Help Widget -->
    <HelpWidget
      sticky
      position="bottom-right"
      @action="handleHelpAction"
    />
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '@/Layouts/PublicDigitLayout.vue';
import PersonalizedHeader from '@/Components/Dashboard/PersonalizedHeader.vue';
import QuickStartGrid from '@/Components/Dashboard/QuickStartGrid.vue';
import HelpWidget from '@/Components/Dashboard/HelpWidget.vue';
import OrganizationStatusBlock from '@/Components/Dashboard/OrganizationStatusBlock.vue';
import PendingActionsBlock from '@/Components/Dashboard/PendingActionsBlock.vue';

// Import new dashboard components
import TrustCenterBanner from '@/Components/Dashboard/TrustCenterBanner.vue';
import MetricCounter from '@/Components/Dashboard/MetricCounter.vue';
import InsightsAccordion from '@/Components/Dashboard/InsightsAccordion.vue';
import FeatureTabs from '@/Components/Dashboard/FeatureTabs.vue';
import SuccessCarousel from '@/Components/Dashboard/SuccessCarousel.vue';
import TrustBadgeBar from '@/Components/Dashboard/TrustBadgeBar.vue';

// Import Dashboard welcome locale files
import welcomeDashboardDe from '@/locales/pages/Dashboard/welcome/de.json';
import welcomeDashboardEn from '@/locales/pages/Dashboard/welcome/en.json';
import welcomeDashboardNp from '@/locales/pages/Dashboard/welcome/np.json';

export default {
  name: 'DashboardWelcome',
  components: {
    DashboardLayout,
    PersonalizedHeader,
    QuickStartGrid,
    HelpWidget,
    OrganizationStatusBlock,
    PendingActionsBlock,
    TrustCenterBanner,
    MetricCounter,
    InsightsAccordion,
    FeatureTabs,
    SuccessCarousel,
    TrustBadgeBar,
  },
  setup() {
    return {};
  },
  props: {
    user: {
      type: Object,
      required: true,
    },
    userState: {
      type: Object,
      required: true,
    },
    trustSignals: {
      type: Array,
      required: false,
      default: () => [],
    },
    contentBlocks: {
      type: Array,
      required: false,
      default: () => [],
    },
    compliance: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      // Block visibility flags
      showAllBlocks: false,
      // User cases section visibility (can be dismissed)
      showUserCases: true,
      // Dashboard welcome locale data
      dashboardWelcomeData: {
        de: welcomeDashboardDe,
        en: welcomeDashboardEn,
        np: welcomeDashboardNp,
      },
      // Analytics tracking
      interactionMetrics: {
        trustBadgeClicks: 0,
        insightViews: 0,
        featureExplores: 0,
      },
    };
  },
  computed: {
    /**
     * Get current locale
     */
    currentLocale() {
      return this.$i18n.locale;
    },

    /**
     * Get dashboard welcome data for current locale
     */
    dashboardWelcome() {
      return this.dashboardWelcomeData[this.currentLocale] || this.dashboardWelcomeData.de;
    },

    /**
     * Extract GDPR banner points array
     */
    gdprBannerPoints() {
      return this.dashboardWelcome.gdpr_banner?.points || [];
    },

    /**
     * Extract user cases - German organizations points
     */
    userCasesGermanOrgPoints() {
      return this.dashboardWelcome.user_cases?.german_orgs?.points || [];
    },

    /**
     * Extract user cases - Diaspora communities points
     */
    userCasesDiasporaPoints() {
      return this.dashboardWelcome.user_cases?.diaspora?.points || [];
    },

    /**
     * Extract value propositions points
     */
    valuePropsPointsArray() {
      return this.dashboardWelcome.value_props?.points || [];
    },

    /**
     * Extract tips points array
     */
    tipsPointsArray() {
      return this.dashboardWelcome.tips?.points || [];
    },

    /**
     * Extract organization name from user state if available
     */
    organizationName() {
      // This would be populated from user state in real implementation
      return null;
    },

    /**
     * Check if quick start action block should render
     */
    hasActionBlock() {
      return (
        Array.isArray(this.contentBlocks) &&
        this.contentBlocks.some((block) => block.content?.type === 'action_cards')
      );
    },

    /**
     * Check if organization status block should render
     */
    hasStatusBlock() {
      return (
        this.userState.primary_role === 'admin' &&
        this.userState.onboarding_step >= 2 &&
        this.userState.onboarding_step < 5
      );
    },

    /**
     * Check if there are pending actions to display
     */
    hasPendingActions() {
      return (
        this.userState.pending_actions &&
        this.userState.pending_actions.length > 0
      );
    },

    /**
     * Build quick start action cards from content blocks
     */
    actionCards() {
      // Ensure contentBlocks is an array
      if (!Array.isArray(this.contentBlocks)) {
        return [];
      }

      const actionBlock = this.contentBlocks.find(
        (block) => block.content?.type === 'action_cards'
      );

      if (!actionBlock || !actionBlock.content || !actionBlock.content.cards) {
        return [];
      }

      // Map backend card data directly - use provided title/description/cta if available
      return actionBlock.content.cards.map((card) => ({
        id: card.id,
        icon: card.icon,
        title: card.title || this.$t(`actions.${card.id}.title`, { fallback: card.id }),
        description: card.description || this.$t(`actions.${card.id}.description`, { fallback: '' }),
        cta: card.cta || this.$t(`actions.${card.id}.cta`, { fallback: 'Get started' }),
        primary: card.primary || false,
        meta: card.meta || null,
      }));
    },

    /**
     * Determine if user is a new user (no organization, onboarding step 0)
     */
    isNewUser() {
      return (
        this.userState?.is_new_user === true ||
        this.userState?.onboarding_step === 0
      );
    },

    /**
     * Determine if user is an admin with completed setup (step 5)
     */
    isAdminComplete() {
      return (
        this.userState?.primary_role === 'admin' &&
        this.userState?.onboarding_step === 5
      );
    },

    /**
     * Filter action cards for NEW_USER state (show only first 3 cards)
     * For other users, show all cards
     */
    filteredActionCards() {
      if (this.isNewUser) {
        return this.actionCards.slice(0, 3);
      }
      return this.actionCards;
    },

    /**
     * Show tips card only for users with low confidence score
     * Helps guide users who are still learning the platform
     */
    shouldShowTips() {
      return this.userState?.confidence_score < 70;
    },

    /**
     * Show value props only for advanced/confident users
     * Users with high confidence have already experienced the core value
     */
    shouldShowValueProps() {
      return this.userState?.confidence_score >= 70;
    },

    /**
     * Build metrics data for MetricCounter component
     */
    metricsData() {
      return [
        {
          id: 'organizations',
          value: 42,
          label: this.$t('metrics.organizations_label', { fallback: 'Organizations' }),
          suffix: '',
          icon: '🏛️',
          description: this.$t('metrics.organizations_desc', { fallback: 'Using Public Digit' }),
          tooltip: 'Total organizations utilizing the platform',
        },
        {
          id: 'members',
          value: 8500,
          label: this.$t('metrics.members_label', { fallback: 'Active Members' }),
          suffix: '+',
          icon: '👥',
          description: this.$t('metrics.members_desc', { fallback: 'Active voters' }),
          tooltip: 'Total registered members across all organizations',
          live: true,
        },
        {
          id: 'elections',
          value: 234,
          label: this.$t('metrics.elections_label', { fallback: 'Elections Conducted' }),
          suffix: '',
          icon: '🗳️',
          description: this.$t('metrics.elections_desc', { fallback: 'Democratic processes' }),
          tooltip: 'Total elections facilitated',
        },
      ];
    },

    /**
     * Build insights data for InsightsAccordion component
     */
    insightsData() {
      return [
        {
          id: 'start_election',
          title: this.$t('insights.start_election.title', { fallback: 'Getting Started: Create Election' }),
          brief: this.$t('insights.start_election.brief', { fallback: '5 minutes setup' }),
          category: this.$t('insights.start_election.category', { fallback: 'Setup' }),
          content: this.$t('insights.start_election.content', { fallback: 'Set up your first election in 5 easy steps. This guide covers all essential features.' }),
          details: [
            this.$t('insights.start_election.detail_1', { fallback: 'Define election title and description' }),
            this.$t('insights.start_election.detail_2', { fallback: 'Set voting period and rules' }),
            this.$t('insights.start_election.detail_3', { fallback: 'Add candidates or referendum options' }),
          ],
          action: { label: this.$t('insights.learn_more', { fallback: 'Learn More' }) },
        },
        {
          id: 'security_features',
          title: this.$t('insights.security.title', { fallback: 'Security & Privacy Features' }),
          brief: this.$t('insights.security.brief', { fallback: 'GDPR + E2E encryption' }),
          category: this.$t('insights.security.category', { fallback: 'Trust' }),
          content: this.$t('insights.security.content', { fallback: 'Your election data is protected by enterprise-grade encryption and GDPR compliance measures.' }),
          details: [
            this.$t('insights.security.detail_1', { fallback: 'End-to-end encryption for all votes' }),
            this.$t('insights.security.detail_2', { fallback: 'GDPR Article 32 compliance' }),
            this.$t('insights.security.detail_3', { fallback: 'Data hosted securely in Germany' }),
          ],
          action: { label: this.$t('insights.read_security', { fallback: 'Read Security Policy' }) },
        },
        {
          id: 'best_practices',
          title: this.$t('insights.best_practices.title', { fallback: 'Best Practices: Voter Engagement' }),
          brief: this.$t('insights.best_practices.brief', { fallback: 'Tips for high participation' }),
          category: this.$t('insights.best_practices.category', { fallback: 'Tips' }),
          content: this.$t('insights.best_practices.content', { fallback: 'Learn proven strategies to increase voter participation and engagement in your elections.' }),
          details: [
            this.$t('insights.best_practices.detail_1', { fallback: 'Send timely reminders to members' }),
            this.$t('insights.best_practices.detail_2', { fallback: 'Provide clear voting instructions' }),
            this.$t('insights.best_practices.detail_3', { fallback: 'Show real-time participation rates' }),
          ],
          action: { label: this.$t('insights.explore_guide', { fallback: 'Explore Guide' }) },
        },
      ];
    },

    /**
     * Build features data for FeatureTabs component
     */
    featuresData() {
      return [
        {
          id: 'democratic-voting',
          category: this.$t('features.voting.category', { fallback: 'Voting' }),
          icon: '🗳️',
          title: this.$t('features.voting.title', { fallback: 'Secure Voting' }),
          description: this.$t('features.voting.description', { fallback: 'Anonymous, tamper-proof voting with real-time result aggregation.' }),
          image: true,
          benefits: [
            this.$t('features.voting.benefit_1', { fallback: 'Anonymous ballot protection' }),
            this.$t('features.voting.benefit_2', { fallback: 'Cryptographic vote verification' }),
            this.$t('features.voting.benefit_3', { fallback: 'Real-time result aggregation' }),
          ],
          details: [
            {
              title: this.$t('features.voting.detail_1_title', { fallback: 'Encryption Standard' }),
              description: this.$t('features.voting.detail_1_desc', { fallback: 'AES-256 with end-to-end protection' }),
            },
            {
              title: this.$t('features.voting.detail_2_title', { fallback: 'Voter Verification' }),
              description: this.$t('features.voting.detail_2_desc', { fallback: 'Prove your vote was recorded without revealing it' }),
            },
          ],
          action: { label: this.$t('features.start_voting', { fallback: 'Start Voting' }) },
        },
        {
          id: 'member-management',
          category: this.$t('features.members.category', { fallback: 'Members' }),
          icon: '👥',
          title: this.$t('features.members.title', { fallback: 'Member Management' }),
          description: this.$t('features.members.description', { fallback: 'Flexible member roles, permission controls, and profile management.' }),
          image: true,
          benefits: [
            this.$t('features.members.benefit_1', { fallback: 'Role-based access control' }),
            this.$t('features.members.benefit_2', { fallback: 'Bulk member import' }),
            this.$t('features.members.benefit_3', { fallback: 'Digital member profiles' }),
          ],
          details: [
            {
              title: this.$t('features.members.detail_1_title', { fallback: 'Roles' }),
              description: this.$t('features.members.detail_1_desc', { fallback: 'Admin, officer, and member tiers' }),
            },
            {
              title: this.$t('features.members.detail_2_title', { fallback: 'Import' }),
              description: this.$t('features.members.detail_2_desc', { fallback: 'CSV or API bulk uploads' }),
            },
          ],
          action: { label: this.$t('features.manage_members', { fallback: 'Manage Members' }) },
        },
        {
          id: 'reporting-analytics',
          category: this.$t('features.analytics.category', { fallback: 'Analytics' }),
          icon: '📊',
          title: this.$t('features.analytics.title', { fallback: 'Reporting & Analytics' }),
          description: this.$t('features.analytics.description', { fallback: 'Detailed election results, participation trends, and compliance reports.' }),
          image: true,
          benefits: [
            this.$t('features.analytics.benefit_1', { fallback: 'Real-time result dashboards' }),
            this.$t('features.analytics.benefit_2', { fallback: 'Participation analytics' }),
            this.$t('features.analytics.benefit_3', { fallback: 'GDPR compliance audits' }),
          ],
          details: [
            {
              title: this.$t('features.analytics.detail_1_title', { fallback: 'Dashboards' }),
              description: this.$t('features.analytics.detail_1_desc', { fallback: 'Interactive election result visualization' }),
            },
            {
              title: this.$t('features.analytics.detail_2_title', { fallback: 'Reports' }),
              description: this.$t('features.analytics.detail_2_desc', { fallback: 'Downloadable CSV and PDF exports' }),
            },
          ],
          action: { label: this.$t('features.view_analytics', { fallback: 'View Analytics' }) },
        },
      ];
    },

    /**
     * Build success stories for SuccessCarousel component
     */
    successCases() {
      return [
        {
          name: this.$t('success.case_1.org_name', { fallback: 'German Union' }),
          type: this.$t('success.case_1.type', { fallback: 'Trade Union (500 members)' }),
          quote: this.$t('success.case_1.quote', { fallback: 'Public Digit transformed our voting from postal to fully digital in just 2 weeks.' }),
          contact: this.$t('success.case_1.contact', { fallback: 'Maria Schmidt' }),
          role: this.$t('success.case_1.role', { fallback: 'Operations Director' }),
          contactInitial: 'M',
          icon: '🏛️',
          logo: '🇩🇪',
          metrics: [
            { value: '98%', label: this.$t('success.participation', { fallback: 'Participation' }) },
            { value: '2h', label: this.$t('success.completion_time', { fallback: 'Avg. Time' }) },
          ],
          action: { label: this.$t('success.read_story', { fallback: 'Read Full Story' }) },
        },
        {
          name: this.$t('success.case_2.org_name', { fallback: 'Nepal Diaspora Network' }),
          type: this.$t('success.case_2.type', { fallback: 'Community Organization (2000 members)' }),
          quote: this.$t('success.case_2.quote', { fallback: 'Our members across 15 countries can now vote securely from home.' }),
          contact: this.$t('success.case_2.contact', { fallback: 'Rajesh Kumar' }),
          role: this.$t('success.case_2.role', { fallback: 'Community Lead' }),
          contactInitial: 'R',
          icon: '🌏',
          logo: '🇳🇵',
          metrics: [
            { value: '62%', label: this.$t('success.participation', { fallback: 'Participation' }) },
            { value: '15', label: this.$t('success.countries', { fallback: 'Countries' }) },
          ],
          action: { label: this.$t('success.read_story', { fallback: 'Read Full Story' }) },
        },
        {
          name: this.$t('success.case_3.org_name', { fallback: 'Tech Association' }),
          type: this.$t('success.case_3.type', { fallback: 'Professional Network (800 members)' }),
          quote: this.$t('success.case_3.quote', { fallback: 'GDPR compliance was automatic—one less thing to worry about.' }),
          contact: this.$t('success.case_3.contact', { fallback: 'Anna Weber' }),
          role: this.$t('success.case_3.role', { fallback: 'Compliance Officer' }),
          contactInitial: 'A',
          icon: '💻',
          logo: '🔐',
          metrics: [
            { value: '100%', label: this.$t('success.compliant', { fallback: 'GDPR Compliant' }) },
            { value: '0', label: this.$t('success.incidents', { fallback: 'Incidents' }) },
          ],
          action: { label: this.$t('success.read_story', { fallback: 'Read Full Story' }) },
        },
      ];
    },

  },
  methods: {
    /**
     * Handle quick start action click
     */
    handleActionClick(cardData) {
      const { cardId, card } = cardData;

      // Track event
      this.$emit('action-clicked', cardId);

      // Route to appropriate page based on action
      switch (cardId) {
        case 'create_organization':
          this.$inertia.visit(route('organisations.create'));
          break;
        case 'join_organization':
          this.$inertia.visit('/organizations/join');
          break;
        case 'setup_election':
          this.$inertia.visit(`/elections/create`);
          break;
        case 'vote_now':
          this.$inertia.visit('/elections');
          break;
        case 'request_assistance':
          this.showHelpWidget();
          break;
        default:
          console.warn('Unknown action:', cardId);
      }
    },

    /**
     * Handle help widget action
     */
    handleHelpAction(action) {
      switch (action) {
        case 'live_request':
          this.$emit('help-requested', 'live');
          break;
        case 'contact_support':
          window.open('/help/contact', '_blank');
          break;
        case 'documentation':
          window.open('/help/docs', '_blank');
          break;
        case 'book_training':
          window.open('/help/training', '_blank');
          break;
      }
    },

    /**
     * Dynamically load block component based on content type
     */
    getBlockComponent(block) {
      const contentType = block.content?.type;
      const componentMap = {
        'action_cards': 'QuickStartGrid',
        'organization_status': 'OrganizationStatusBlock',
        'pending_actions': 'PendingActionsBlock',
      };

      return componentMap[contentType] || 'div';
    },

    /**
     * Show help widget (scroll to it or focus)
     */
    showHelpWidget() {
      const widget = this.$refs.helpWidget;
      if (widget) {
        widget.$el.scrollIntoView({ behavior: 'smooth' });
      }
    },

    /**
     * Handle metrics animation completion
     */
    handleMetricsComplete() {
      if (window.gtag) {
        window.gtag('event', 'metrics_animation_complete', {
          event_category: 'engagement',
        });
      }
    },

    /**
     * Handle insight accordion action
     */
    handleInsightAction(item) {
      if (window.gtag) {
        window.gtag('event', 'insight_action', {
          event_category: 'engagement',
          insight_id: item.id,
        });
      }
      // Route based on action
      if (item.id === 'start_election') {
        this.$inertia.visit('/elections/create');
      } else if (item.id === 'security_features') {
        window.open('/help/security', '_blank');
      } else if (item.id === 'best_practices') {
        window.open('/help/best-practices', '_blank');
      }
    },

    /**
     * Handle insight feedback (helpful/not helpful)
     */
    handleInsightFeedback({ item, index, helpful }) {
      if (window.gtag) {
        window.gtag('event', 'insight_feedback', {
          event_category: 'engagement',
          insight_id: item.id,
          helpful: helpful === 'yes',
        });
      }
    },

    /**
     * Handle feature tab action
     */
    handleFeatureAction(feature) {
      if (window.gtag) {
        window.gtag('event', 'feature_explored', {
          event_category: 'engagement',
          feature_id: feature.id,
        });
      }
      this.interactionMetrics.featureExplores++;
    },

    /**
     * Handle success carousel action
     */
    handleSuccessAction(caseData) {
      if (window.gtag) {
        window.gtag('event', 'success_story_view', {
          event_category: 'engagement',
          organization: caseData.name,
        });
      }
    },

    /**
     * Handle compliance center open
     */
    handleComplianceOpen() {
      if (window.gtag) {
        window.gtag('event', 'compliance_opened', {
          event_category: 'trust_engagement',
        });
      }
      window.open('/compliance', '_blank');
    },

    /**
     * Handle trust badge bar dismissal
     */
    handleTrustDismiss() {
      if (window.gtag) {
        window.gtag('event', 'trust_badge_dismissed', {
          event_category: 'engagement',
        });
      }
    },
  },
  mounted() {
    // Log compliance verification for auditing
    if (this.compliance.gdpr_article_32_compliant) {
      console.log(
        '%c✓ GDPR Article 32 Compliant',
        'color: green; font-weight: bold;'
      );
      console.log(
        'Data Protection Officer:',
        this.compliance.data_protection_officer_email
      );
    }
  },
};
</script>

<style scoped>
/* Main Container */
.welcome-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem 1.5rem;
}

.alert-space {
  margin-bottom: 2rem;
  width: 100%;
}

/* Single Column Layout */
.layout-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  align-items: start;
}

.main-column {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

/* Glassmorphism Cards */
.card-glass {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
  border-radius: 16px;
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.card-glass:hover {
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

/* Pipeline Blocks */
.pipeline-blocks {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.block-item {
  width: 100%;
}

/* Transition helpers */
.transition-all {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Responsive Design */
@media (max-width: 768px) {
  .welcome-container {
    padding: 1rem 1rem;
  }

  .layout-grid {
    gap: 1.5rem;
  }

  .card-glass {
    padding: 1.25rem;
  }
}

@media (max-width: 480px) {
  .welcome-container {
    padding: 0.75rem 0.5rem;
  }

  .card-glass {
    border-radius: 8px;
    padding: 1rem;
  }
}
</style>
