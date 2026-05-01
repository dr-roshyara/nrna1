/**
 * Maps election domain states to design system UI tokens.
 * Eliminates scattered if/else color logic across 200+ pages.
 *
 * Usage:
 *   const { badge, label } = mapElectionStateToUI(election.state)
 *   <span :class="`badge badge-${badge}`>{{ label }}</span>
 */

export function mapElectionStateToUI(state) {
  const stateMap = {
    // Draft / Pending
    draft:            { badge: 'neutral',  label: 'Draft',             color: 'text-neutral-600' },
    pending_approval: { badge: 'warning',  label: 'Pending Approval',  color: 'text-warning-600' },
    rejected:         { badge: 'danger',   label: 'Rejected',          color: 'text-danger-600' },

    // Active / Voting
    approved:         { badge: 'primary',  label: 'Approved',          color: 'text-primary-600' },
    active:           { badge: 'primary',  label: 'Active',            color: 'text-primary-600' },
    voting_open:      { badge: 'success',  label: 'Voting Open',       color: 'text-success-600' },
    voting_closed:    { badge: 'neutral',  label: 'Voting Closed',     color: 'text-neutral-600' },

    // Results
    published:        { badge: 'success',  label: 'Results Published', color: 'text-success-600' },
    archived:         { badge: 'neutral',  label: 'Archived',          color: 'text-neutral-600' },
  }

  return stateMap[state] ?? {
    badge: 'neutral',
    label: state ? state.charAt(0).toUpperCase() + state.slice(1).replace(/_/g, ' ') : 'Unknown',
    color: 'text-neutral-600'
  }
}

/**
 * Maps membership status to UI badge.
 */
export function mapMembershipStatusToUI(status) {
  const statusMap = {
    active:        { badge: 'success',  label: 'Active',     color: 'text-success-600' },
    pending:       { badge: 'warning',  label: 'Pending',    color: 'text-warning-600' },
    inactive:      { badge: 'neutral',  label: 'Inactive',   color: 'text-neutral-600' },
    has_voted:     { badge: 'success',  label: 'Voted',      color: 'text-success-600' },
    not_voted:     { badge: 'neutral',  label: 'Not Voted',  color: 'text-neutral-600' },
  }

  return statusMap[status] ?? { badge: 'neutral', label: status, color: 'text-neutral-600' }
}

/**
 * Maps post type to UI styling.
 */
export function mapPostTypeToUI(isNational) {
  return isNational
    ? { badge: 'primary', label: 'National', icon: 'globe' }
    : { badge: 'neutral',  label: 'Regional', icon: 'map' }
}
