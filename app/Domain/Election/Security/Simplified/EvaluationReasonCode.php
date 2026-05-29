<?php

namespace App\Domain\Election\Security\Simplified;

enum EvaluationReasonCode: string
{
    case ALL_POLICIES_PASSED = 'all_policies_passed';
    case VERIFICATION_REQUIRED_NOT_SATISFIED = 'verification_required_not_satisfied';
    case NETWORK_EVIDENCE_INSUFFICIENT = 'network_evidence_insufficient';
    case DEVICE_EVIDENCE_INSUFFICIENT = 'device_evidence_insufficient';
    case SESSION_CONTINUITY_FAILED = 'session_continuity_failed';
    case EVIDENCE_CONFLICT_DETECTED = 'evidence_conflict_detected';
    case OVERLAY_ELEVATION_REQUIRED = 'overlay_elevation_required';
    case CONSTITUTIONAL_REVIEW_REQUIRED = 'constitutional_review_required';
    case REPLAY_DETERMINISM_UNCERTAIN = 'replay_determinism_uncertain';
    case UNKNOWN_REASON = 'unknown_reason';
}
