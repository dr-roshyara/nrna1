/**
 * Election Approval Policy — Domain governance rule
 *
 * Determines the submission path for an election based on its expected
 * voter count. Elections at or below the self-service limit may bypass
 * governance review; elections above the limit require platform admin
 * approval.
 *
 * This is a pure domain policy with zero framework dependencies.
 * It can be tested in isolation without Vue, Inertia, or any browser API.
 *
 * ADR-001: Reuse Before Create — this policy was extracted from inline
 *          logic in Management.vue rather than creating a new abstraction layer.
 *
 * @see ElectionPhaseService — sibling domain service for lifecycle phases
 */

export class ElectionApprovalPolicy {
  /**
   * Maximum voter count that qualifies for self-service approval.
   * Elections at or below this threshold may skip governance review.
   * Elections above this threshold require platform admin approval.
   */
  static readonly SELF_SERVICE_LIMIT = 40

  /**
   * Determines whether an election can use the simplified auto-submit
   * path based on its expected voter count.
   *
   * @param expectedVoterCount - The expected number of voters for the election
   * @returns true if the election qualifies for self-service approval
   *
   * @example
   * ElectionApprovalPolicy.shouldAutoSubmit(30)  // → true  (≤40, self-service)
   * ElectionApprovalPolicy.shouldAutoSubmit(40)  // → true  (at limit)
   * ElectionApprovalPolicy.shouldAutoSubmit(41)  // → false (requires review)
   * ElectionApprovalPolicy.shouldAutoSubmit(0)   // → true  (no voters yet)
   */
  static shouldAutoSubmit(expectedVoterCount: number): boolean {
    return expectedVoterCount <= this.SELF_SERVICE_LIMIT
  }
}
