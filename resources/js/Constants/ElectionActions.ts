/**
 * Constitutional action vocabulary — single source of truth
 * These 15 actions are defined in ElectionConstitution and orchestrated by the capability resolver.
 * Frontend code should NEVER hardcode action names; always reference this constant.
 */
export const ElectionActions = {
  SUBMIT_FOR_APPROVAL: 'submit_for_approval',
  APPROVE: 'approve',
  REJECT: 'reject',
  AUTO_SUBMIT: 'auto_submit',
  BEGIN_SETUP: 'begin_setup',
  REVISE_AND_RESUBMIT: 'revise_and_resubmit',
  COMPLETE_ADMINISTRATION: 'complete_administration',
  COMPLETE_NOMINATION: 'complete_nomination',
  APPLY_CANDIDACY: 'apply_candidacy',
  OPEN_VOTING: 'open_voting',
  CLOSE_VOTING: 'close_voting',
  PUBLISH_RESULTS: 'publish_results',
  ARCHIVE: 'archive',
  SUSPEND: 'suspend',
  RESUME: 'resume',
} as const

export type ElectionAction = typeof ElectionActions[keyof typeof ElectionActions]

/**
 * All 15 action names in array form (for validation, iteration, etc.)
 */
export const ALL_ELECTION_ACTIONS = Object.values(ElectionActions) as const
