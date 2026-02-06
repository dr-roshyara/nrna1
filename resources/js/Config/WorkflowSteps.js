/**
 * Workflow Step Configuration
 * Defines all multi-step workflows in the system
 * Each workflow is a state machine with defined steps
 */

export const WORKFLOWS = {
  // Voting Workflow - 5 steps
  VOTING: {
    name: 'voting',
    displayName: 'Voter Voting Workflow',
    totalSteps: 5,
    steps: [
      {
        step: 1,
        name: 'Code Creation',
        route: 'slug.code.create',
        description: 'Generate voting code',
        translations: {
          en: 'Code Creation',
          de: 'Code-Erstellung',
          np: 'कोड सृजना'
        }
      },
      {
        step: 2,
        name: 'Agreement',
        route: 'slug.code.agreement',
        description: 'Accept voting agreement',
        translations: {
          en: 'Accept Agreement',
          de: 'Vereinbarung akzeptieren',
          np: 'सहमति स्वीकार गर्नुहोस्'
        }
      },
      {
        step: 3,
        name: 'Ballot Selection',
        route: 'slug.vote.create',
        description: 'Select candidates',
        translations: {
          en: 'Cast Your Vote',
          de: 'Stimme Abgeben',
          np: 'मतदान गर्नुहोस्'
        }
      },
      {
        step: 4,
        name: 'Verification',
        route: 'slug.vote.verify',
        description: 'Review & verify choices',
        translations: {
          en: 'Verify Your Vote',
          de: 'Stimme Überprüfen',
          np: 'आफ्नो मत पुष्टि गर्नुहोस्'
        }
      },
      {
        step: 5,
        name: 'Completion',
        route: 'slug.vote.complete',
        description: 'Final receipt',
        translations: {
          en: 'Thank You',
          de: 'Vielen Dank',
          np: 'धन्यवाद'
        }
      }
    ]
  },

  // Delegate Voting Workflow - 4 steps
  DELEGATE_VOTING: {
    name: 'delegate_voting',
    displayName: 'Delegate Voting Workflow',
    totalSteps: 4,
    steps: [
      {
        step: 1,
        name: 'Code Generation',
        route: 'deligatecode.create',
        description: 'Generate delegate code',
        translations: {
          en: 'Generate Code',
          de: 'Code generieren',
          np: 'कोड सृजना गर्नुहोस्'
        }
      },
      {
        step: 2,
        name: 'Candidate Selection',
        route: 'deligatevote.create',
        description: 'Select candidates',
        translations: {
          en: 'Select Candidates',
          de: 'Kandidaten auswählen',
          np: 'उम्मेदवारहरू छनौट गर्नुहोस्'
        }
      },
      {
        step: 3,
        name: 'Verification',
        route: 'deligatevote.verifiy',
        description: 'Review votes',
        translations: {
          en: 'Verify Votes',
          de: 'Stimmen überprüfen',
          np: 'मतहरू पुष्टि गर्नुहोस्'
        }
      },
      {
        step: 4,
        name: 'Submission',
        route: 'deligatevote.store',
        description: 'Final submission',
        translations: {
          en: 'Submit',
          de: 'Einreichen',
          np: 'जमा गर्नुहोस्'
        }
      }
    ]
  },

  // Finance Income Workflow - 3 steps
  FINANCE_INCOME: {
    name: 'finance_income',
    displayName: 'Finance Income Submission',
    totalSteps: 3,
    steps: [
      {
        step: 1,
        name: 'Create Form',
        route: 'finance.income.create',
        description: 'Enter income data',
        translations: {
          en: 'Enter Income Details',
          de: 'Einnahmendaten eingeben',
          np: 'आय विवरण प्रविष्ट गर्नुहोस्'
        }
      },
      {
        step: 2,
        name: 'Verification',
        route: 'finance.income.verify',
        description: 'Review data',
        translations: {
          en: 'Verify Information',
          de: 'Informationen überprüfen',
          np: 'जानकारी पुष्टि गर्नुहोस्'
        }
      },
      {
        step: 3,
        name: 'Submit',
        route: 'finance.income.store',
        description: 'Save & notify',
        translations: {
          en: 'Submit Income',
          de: 'Einnahmen einreichen',
          np: 'आय जमा गर्नुहोस्'
        }
      }
    ]
  },

  // Finance Outcome Workflow - 3 steps
  FINANCE_OUTCOME: {
    name: 'finance_outcome',
    displayName: 'Finance Outcome Submission',
    totalSteps: 3,
    steps: [
      {
        step: 1,
        name: 'Create Form',
        route: 'finance.outcome.create',
        description: 'Enter expense data',
        translations: {
          en: 'Enter Expense Details',
          de: 'Ausgabendaten eingeben',
          np: 'खर्च विवरण प्रविष्ट गर्नुहोस्'
        }
      },
      {
        step: 2,
        name: 'Verification',
        route: 'finance.outcome.verify',
        description: 'Review data',
        translations: {
          en: 'Verify Information',
          de: 'Informationen überprüfen',
          np: 'जानकारी पुष्टि गर्नुहोस्'
        }
      },
      {
        step: 3,
        name: 'Submit',
        route: 'finance.outcome.store',
        description: 'Save & notify',
        translations: {
          en: 'Submit Expenses',
          de: 'Ausgaben einreichen',
          np: 'खर्चहरू जमा गर्नुहोस्'
        }
      }
    ]
  }
};

/**
 * Get workflow configuration by name
 */
export function getWorkflow(workflowName) {
  return WORKFLOWS[workflowName.toUpperCase()];
}

/**
 * Get total steps for a workflow
 */
export function getTotalSteps(workflowName) {
  const workflow = getWorkflow(workflowName);
  return workflow ? workflow.totalSteps : 0;
}

/**
 * Get step configuration
 */
export function getStep(workflowName, stepNumber) {
  const workflow = getWorkflow(workflowName);
  if (!workflow) return null;
  return workflow.steps.find(s => s.step === stepNumber);
}

/**
 * Get step translations
 */
export function getStepTranslation(workflowName, stepNumber, locale = 'en') {
  const step = getStep(workflowName, stepNumber);
  if (!step) return '';
  return step.translations[locale] || step.translations.en;
}

/**
 * Calculate progress percentage
 */
export function calculateProgress(workflowName, currentStep) {
  const workflow = getWorkflow(workflowName);
  if (!workflow) return 0;
  return (currentStep / workflow.totalSteps) * 100;
}
