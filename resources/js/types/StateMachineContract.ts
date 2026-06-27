/**
 * StateMachineContract — Distributed Constitutional Runtime Protocol
 *
 * This is NOT merely a frontend TypeScript type. It is a DISTRIBUTED CONSTITUTIONAL
 * RUNTIME PROTOCOL flowing from ElectionCapabilityResolver (backend) to every page
 * that makes governance decisions.
 *
 * CONTRACT STABILITY GUARANTEE:
 * Changes to this schema require ADR + deprecation window (8 weeks minimum).
 * This interface is versioned via resolver_version (semver).
 *
 * GOVERNANCE VOCABULARY OWNERSHIP:
 * - denial_reason: Stable machine-readable keys (part of governance vocabulary)
 * - denial_detail: Localized UX text only (display only, never parsed)
 * - capabilities_metadata: Runtime validation and drift detection
 *
 * DISTRIBUTED AUTHORITY PRINCIPLE:
 * ElectionCapabilityResolver (backend) is the sole authority source.
 * Frontend NEVER derives, computes, or reconstructs authority.
 * Frontend ONLY reads pre-computed capability snapshots.
 *
 * FORBIDDEN PATTERNS:
 * ❌ Branching on denial_detail text
 * ❌ Comparing denial_detail to literal strings
 * ❌ Using denial_detail as i18n key directly
 * ❌ Reconstructing capabilities from lifecycle state + role
 * ❌ Using capabilities_trace in production code
 * ❌ Caching or persisting this contract client-side
 *
 * ALLOWED PATTERNS:
 * ✅ Display denial_detail as unmodified UX tooltip text
 * ✅ Branch on denial_reason (stable governance key)
 * ✅ Use denial_reason as i18n key lookup prefix
 * ✅ Read capabilities snapshot as source of truth
 * ✅ Emit telemetry using denial_reason for audit continuity
 * ✅ Verify constitution_hash for runtime drift detection
 *
 * @see ElectionCapabilityResolver (backend authority source)
 * @see useElectionCapabilities() (frontend access boundary)
 * @see ElectionConstitution (governance rules source)
 */

/**
 * Individual capability authority snapshot
 *
 * CRITICAL SEMANTICS:
 * - allowed: boolean → Constitutional authority snapshot (single source of truth)
 * - denial_reason: string | null → Stable governance vocabulary identifier
 *   * Machine-readable, never displayed
 *   * Renames require ADR (breaking change = MAJOR semver)
 *   * Used in logic, tests, telemetry
 *   * Examples: 'suspended', 'voting_window_not_defined', 'insufficient_role'
 *
 * - denial_detail: string | null → Localized UX explanation only
 *   * Human-readable, always displayed in UI
 *   * NEVER parsed, branched on, or used in logic
 *   * Changes are PATCH semver (UX copy)
 *   * Examples: "Voting dates must be set before opening the ballot"
 */
interface CapabilityEntry {
  allowed: boolean;

  /**
   * Stable machine-readable governance identifier.
   * GOVERNANCE VOCABULARY: changes require ADR + deprecation window.
   * Used by composable logic and test assertions.
   * NEVER branched on for display text.
   *
   * Examples:
   * - 'suspended' — election is under suspension overlay
   * - 'voting_window_not_defined' — voting dates not yet configured
   * - 'insufficient_role' — user role does not permit this action
   * - 'precondition_unmet' — state is correct but prerequisites unfulfilled
   * - 'legal_hold' — election under legal hold
   */
  denial_reason: string | null;

  /**
   * Localized UX explanation only.
   * NEVER parsed, branched on, or used in logic.
   * Display in UI tooltips/messages only.
   *
   * This field is for human users. Do not parse it.
   *
   * Examples:
   * - "Voting dates must be set before opening the ballot"
   * - "This election is suspended. Contact your administrator."
   * - "Only chief administrators can perform this action."
   */
  denial_detail: string | null;
}

/**
 * Complete capability authority map for this election state
 *
 * All capability keys MUST match ElectionConstitution::RULES exactly.
 * Tested by ElectionStateMachineCapabilitiesTest (PHP).
 *
 * Each key is a testable governance action that may be allowed or denied.
 */
interface CapabilitiesMap {
  submit_for_approval: CapabilityEntry;
  approve: CapabilityEntry;
  reject: CapabilityEntry;
  auto_submit: CapabilityEntry;
  begin_setup: CapabilityEntry;
  revise_and_resubmit: CapabilityEntry;
  complete_administration: CapabilityEntry;
  complete_nomination: CapabilityEntry;
  apply_candidacy: CapabilityEntry;
  open_voting: CapabilityEntry;
  close_voting: CapabilityEntry;
  publish_results: CapabilityEntry;
  archive: CapabilityEntry;
  suspend: CapabilityEntry;
  resume: CapabilityEntry;
}

/**
 * Metadata governing the distributed constitutional runtime projection
 *
 * CRITICAL: These fields are NOT decorative metadata. They are runtime
 * governance validation infrastructure.
 *
 * constitution_hash: MD5 of ElectionConstitution::RULES at derivation time
 * - Use case: Detect when backend constitution changed after frontend received snapshot
 * - If hash mismatches: capabilities cannot be trusted as current
 * - Trigger: Emit "governance drift" telemetry event to admin dashboard
 * - Future federation: External authorities verify constitution compatibility
 *
 * resolver_version: semver (MAJOR.MINOR.PATCH)
 * - MINOR: Add new capability (additive, non-breaking)
 * - MAJOR: Remove capability or rename denial_reason (breaking)
 * - PATCH: denial_detail text changes only
 * - Governance: Tracks compatibility between frontend and backend
 * - Forbidden use: Do NOT branch on resolver_version in component logic
 *
 * generated_at: ISO8601 timestamp
 * - When this snapshot was derived from ElectionConstitution
 * - Enables audit trail of constitutional changes
 * - Used for drift detection: if too stale, force re-fetch
 */
interface CapabilitiesMetadata {
  resolver_version: string;
  generated_at: string;
  constitution_hash: string;
}

/**
 * StateMachineContract — Distributed Constitutional Runtime Protocol
 *
 * OWNERSHIP AND AUTHORITY:
 * Backend (ElectionCapabilityResolver) computes this entire structure.
 * Frontend (useElectionCapabilities composable) reads and projects it.
 * No frontend component may reconstruct or derive authority.
 *
 * DISTRIBUTED AUTHORITY:
 * When this contract arrives at frontend, it represents a SOVEREIGNTY SNAPSHOT.
 * It is not a request for computation. It is a DECLARATION of what the user is
 * permitted to do, signed by ElectionCapabilityResolver authority.
 *
 * VERSIONING AND COMPATIBILITY:
 * - resolver_version governs compatibility (semver discipline)
 * - constitution_hash detects drift (constitutional changes)
 * - capabilities_metadata enables runtime validation
 *
 * PROJECTION AND DISPLAY:
 * currentState + completedStates: Visualization of constitutional progression
 * - Use for progress indicators, phase badges, lifecycle context
 * - Do NOT use to derive permission decisions (overlays diverge authority)
 *
 * capabilities: Sovereign authority snapshot
 * - Read with useElectionCapabilities() composable only
 * - Never cache or persist (stale cache = stale authority)
 * - Never reconstruct from state + role
 *
 * INVARIANTS (ENFORCED BY BEHAVIORAL + HEURISTIC TESTS):
 * 1. Vue renders authority — Vue does NOT derive authority
 * 2. useElectionCapabilities() is ONLY authorized access point
 * 3. ElectionLifecycleStates for visualization only, NEVER for permissions
 * 4. No capability reconstruction from lifecycle + role combination
 * 5. stateMachine never cached/persisted client-side
 * 6. capabilities_trace forbidden in all production paths
 */
export interface StateMachineContract {
  currentState: string;
  completedStates: string[];
  projectionAvailable: boolean;

  /**
   * Constitutional authority snapshot — THE authority source.
   * Derived by ElectionCapabilityResolver from:
   * - Election state
   * - Overlay conditions (suspension, legal hold, etc.)
   * - User role and permissions
   * - Election constitution rules
   *
   * Never compute or cache this client-side.
   * Access ONLY via useElectionCapabilities() composable.
   */
  capabilities: CapabilitiesMap;

  /**
   * Metadata for governing the distributed constitutional runtime.
   * Used for validation, drift detection, and compatibility verification.
   */
  capabilities_metadata: CapabilitiesMetadata;

  /**
   * EPHEMERAL DEBUG FIELD — ABSOLUTELY FORBIDDEN IN PRODUCTION CODE
   *
   * Contains derivation trace information (why was this capability denied?)
   * for development and debugging only.
   *
   * FORBIDDEN USAGE:
   * ❌ Component logic
   * ❌ Test assertions
   * ❌ Telemetry
   * ❌ Federation contracts
   * ❌ Any production code path
   *
   * ALLOWED USAGE:
   * ✅ Browser console inspection (developer debugging)
   * ✅ Local development tracing
   * ✅ Error reports (include trace if helpful)
   *
   * CRITICAL: This field is NOT a stable API.
   * Structure changes without notice.
   * Never depend on trace structure or content.
   *
   * Architecture test verifies: capabilities_trace references are zero in resources/js/Pages/
   */
  capabilities_trace?: unknown;
}
