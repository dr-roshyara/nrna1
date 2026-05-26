<?php

namespace App\Domain\Election\Security;

/**
 * Typed classification for constitutional divergences between legacy and resolver.
 *
 * These are NOT bugs or errors. Divergences are expected as the resolver
 * takes more precise, constitutionally-aligned authority decisions.
 *
 * Each case documents a KNOWN ARTICLE that diverges and must be formally reviewed.
 */
enum ConstitutionalDivergenceType: string
{
    // Network Legitimacy Divergences
    case NetworkBindingThresholdDifference = 'network_binding_threshold';      // IP vote count limit computed differently
    case NetworkContinuityInterpretation = 'network_continuity_interpretation'; // Same-IP continuation logic differs
    case NetworkEvidenceHashingChange = 'network_evidence_hashing';             // Hash algorithm or salt changed

    // Device Legitimacy Divergences
    case DeviceFingerprintRequirementChange = 'device_fingerprint_requirement'; // Volatility assessment differs
    case DeviceContinuitySemantics = 'device_continuity_semantics';            // Fingerprint match interpretation differs
    case DeviceAttestationSourceDifference = 'device_attestation_source';       // Who certifies fingerprint changed

    // Verification Legitimacy Divergences
    case VerificationProtocolChange = 'verification_protocol_change';           // Officer capture vs self-certification differs
    case VerificationRevocationSemantics = 'verification_revocation_semantics'; // When attestation invalidates changed
    case RegistrarAuthorityScopeChange = 'registrar_authority_scope';          // Registrar can override changed

    // Constitutional Trust Composition
    case TrustCompositionLogic = 'trust_composition_logic';                    // How network+device+verification combine
    case TrustElevationThreshold = 'trust_elevation_threshold';                // Overlay can raise trust changed
    case TrustValidityScope = 'trust_validity_scope';                          // Trust expires differently (session/election/device)

    // Overlay Governance
    case OverlayPriorityOrdering = 'overlay_priority_ordering';                 // Which overlay wins changed
    case OverlaySignalInterpretation = 'overlay_signal_interpretation';        // Signal means something different
    case EmergencyGovernanceAuthority = 'emergency_governance_authority';       // Who can suspend changed

    // Authorization Protocol
    case BallotAuthorizationStructure = 'ballot_authorization_structure';     // Single vs dual code interpretation changed
    case ViewTokenVsCommitTokenSemantics = 'view_commit_token_semantics';     // Separated authorities changed
    case CommitTokenFreshnessRequirement = 'commit_token_freshness';           // Token reuse policy changed

    // Lifecycle Interaction
    case LifecycleStateSemantics = 'lifecycle_state_semantics';                // What Suspended/Active means changed
    case LifecycleTransitionGuards = 'lifecycle_transition_guards';            // When transitions allowed changed
    case ParticipationWindowEnforcement = 'participation_window_enforcement';   // Voting time window changed

    // Root Cause Unknown
    case UnclassifiedDivergence = 'unclassified_divergence';                   // Type unknown - requires investigation

    public function article(): string
    {
        return match ($this) {
            // Network articles
            self::NetworkBindingThresholdDifference => 'Election Constitution: Network Binding Strategy',
            self::NetworkContinuityInterpretation => 'Election Constitution: Network Continuity Preservation',
            self::NetworkEvidenceHashingChange => 'Security Privacy Policy: Evidence Hashing',

            // Device articles
            self::DeviceFingerprintRequirementChange => 'Election Constitution: Device Binding Strategy',
            self::DeviceContinuitySemantics => 'Security Policy: Device Continuity Verification',
            self::DeviceAttestationSourceDifference => 'Authority Scope: Device Attestation Sources',

            // Verification articles
            self::VerificationProtocolChange => 'Election Constitution: Voter Verification Protocol',
            self::VerificationRevocationSemantics => 'Security Article: Attestation Revocation Semantics',
            self::RegistrarAuthorityScopeChange => 'Authority Scope: Registrar Override Boundaries',

            // Trust articles
            self::TrustCompositionLogic => 'Constitutional Trust: Composition from Evidence',
            self::TrustElevationThreshold => 'Constitutional Trust: Elevation Thresholds',
            self::TrustValidityScope => 'Constitutional Trust: Validity Scoping',

            // Overlay articles
            self::OverlayPriorityOrdering => 'Overlay Governance: Priority Ordering',
            self::OverlaySignalInterpretation => 'Overlay Governance: Signal Semantics',
            self::EmergencyGovernanceAuthority => 'Overlay Governance: Emergency Authority',

            // Authorization articles
            self::BallotAuthorizationStructure => 'Ballot Authorization: Protocol Structure',
            self::ViewTokenVsCommitTokenSemantics => 'Ballot Authorization: Token Separation',
            self::CommitTokenFreshnessRequirement => 'Ballot Authorization: Token Freshness',

            // Lifecycle articles
            self::LifecycleStateSemantics => 'Election Lifecycle: State Semantics',
            self::LifecycleTransitionGuards => 'Election Lifecycle: Transition Guards',
            self::ParticipationWindowEnforcement => 'Election Lifecycle: Participation Window',

            // Unknown
            self::UnclassifiedDivergence => 'Unknown Constitutional Article (Investigation Required)',
        };
    }

    public function severity(): Severity
    {
        return match ($this) {
            // CRITICAL: Changes to core constitutional principles
            self::TrustCompositionLogic,
            self::OverlayPriorityOrdering,
            self::EmergencyGovernanceAuthority,
            self::RegistrarAuthorityScopeChange => Severity::Critical,

            // HIGH: Changes to core legitimacy assessment
            self::NetworkBindingThresholdDifference,
            self::DeviceFingerprintRequirementChange,
            self::VerificationProtocolChange,
            self::BallotAuthorizationStructure,
            self::LifecycleStateSemantics => Severity::High,

            // MEDIUM: Changes to interpretation or implementation
            self::NetworkContinuityInterpretation,
            self::DeviceContinuitySemantics,
            self::TrustElevationThreshold,
            self::OverlaySignalInterpretation,
            self::ViewTokenVsCommitTokenSemantics,
            self::LifecycleTransitionGuards => Severity::Medium,

            // LOW: Changes to policy or timing
            self::NetworkEvidenceHashingChange,
            self::DeviceAttestationSourceDifference,
            self::VerificationRevocationSemantics,
            self::TrustValidityScope,
            self::CommitTokenFreshnessRequirement,
            self::ParticipationWindowEnforcement => Severity::Low,

            // UNKNOWN: Requires investigation
            self::UnclassifiedDivergence => Severity::Unknown,
        };
    }
}

enum Severity: string
{
    case Critical = 'critical';   // Constitutional principle changed
    case High = 'high';           // Core legitimacy assessment changed
    case Medium = 'medium';       // Interpretation or implementation changed
    case Low = 'low';             // Policy or timing changed
    case Unknown = 'unknown';     // Not yet classified
}
