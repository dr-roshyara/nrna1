import { computed, ref } from 'vue';

/**
 * Dashboard composition logic
 * Centralizes state management for Welcome page
 */
export function useDashboard(props) {
  // State
  const showUserCases = ref(true);
  const expandedAccordions = ref({});
  const animatedCounters = ref({});
  const hoveredCard = ref(null);

  // Computed: User is new (no organizations, step 0)
  const isNewUser = computed(() => {
    return props.userState?.is_new_user === true ||
           props.userState?.onboarding_step === 0;
  });

  // Computed: Admin with incomplete setup
  const isAdminComplete = computed(() => {
    return props.userState?.primary_role === 'admin' &&
           props.userState?.onboarding_step === 5;
  });

  // Computed: Show organization status block
  const hasStatusBlock = computed(() => {
    return props.userState?.primary_role === 'admin' &&
           props.userState?.onboarding_step >= 2 &&
           props.userState?.onboarding_step < 5;
  });

  // Computed: Show quick start grid
  const hasActionBlock = computed(() => {
    return Array.isArray(props.contentBlocks) &&
           props.contentBlocks.some(block => block.content?.type === 'action_cards');
  });

  // Computed: Check for pending actions
  const hasPendingActions = computed(() => {
    return Array.isArray(props.userState?.pending_actions) &&
           props.userState.pending_actions.length > 0;
  });

  // Computed: Extract action cards
  const actionCards = computed(() => {
    if (!Array.isArray(props.contentBlocks)) return [];
    const actionBlock = props.contentBlocks.find(
      block => block.content?.type === 'action_cards'
    );
    return actionBlock?.content?.cards || [];
  });

  // Computed: Filter cards for new users (first 3)
  const filteredActionCards = computed(() => {
    if (isNewUser.value) {
      return actionCards.value.slice(0, 3);
    }
    return actionCards.value;
  });

  // Computed: Determine user confidence level
  const confidenceLevel = computed(() => {
    const score = props.userState?.confidence_score || 0;
    if (score >= 80) return 'expert';
    if (score >= 60) return 'intermediate';
    if (score >= 40) return 'beginner';
    return 'new';
  });

  // Computed: Visibility based on confidence
  const shouldShowAdvanced = computed(() => {
    return props.userState?.confidence_score >= 70;
  });

  // Computed: Visibility for educational content
  const shouldShowTips = computed(() => {
    return props.userState?.confidence_score < 70;
  });

  // Methods: Toggle accordion
  const toggleAccordion = (key) => {
    expandedAccordions.value[key] = !expandedAccordions.value[key];
  };

  // Methods: Dismiss user cases
  const dismissUserCases = () => {
    showUserCases.value = false;
    localStorage.setItem('dismiss_user_cases', 'true');
  };

  // Methods: Track interaction
  const trackInteraction = (eventName, context = {}) => {
    if (window.gtag) {
      window.gtag('event', eventName, {
        event_category: 'dashboard_engagement',
        user_confidence: confidenceLevel.value,
        ...context,
      });
    }
  };

  // Methods: Get block component
  const getBlockComponent = (block) => {
    const componentMap = {
      'action_cards': 'QuickStartGrid',
      'organization_status': 'OrganizationStatusBlock',
      'pending_actions': 'PendingActionsBlock',
    };
    return componentMap[block.content?.type] || 'div';
  };

  return {
    // State
    showUserCases,
    expandedAccordions,
    animatedCounters,
    hoveredCard,

    // Computed
    isNewUser,
    isAdminComplete,
    hasStatusBlock,
    hasActionBlock,
    hasPendingActions,
    actionCards,
    filteredActionCards,
    confidenceLevel,
    shouldShowAdvanced,
    shouldShowTips,

    // Methods
    toggleAccordion,
    dismissUserCases,
    trackInteraction,
    getBlockComponent,
  };
}
