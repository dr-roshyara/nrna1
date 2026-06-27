<?php

namespace App\Domain\Election\Security;

/**
 * DivergenceCategory
 *
 * Typed taxonomy for OLD vs NEW pipeline divergence during strangler fig migration.
 * Replaces string-based divergence_type with a formal enum.
 *
 * Each category maps to a specific type of replay mismatch:
 * - EVIDENCE_MISMATCH: Evidence collection differs between pipelines
 * - ORDERING_MISMATCH: Policy evaluation order differs
 * - OVERLAY_MISMATCH: Overlay signals differ
 * - CONSTITUTION_MISMATCH: Constitutional hash or interpretation differs
 * - SERIALIZATION_MISMATCH: Replay serialization differs
 * - THRESHOLD_INTERPRETATION_MISMATCH: Threshold math differs
 */
enum DivergenceCategory: string
{
    case EVIDENCE_MISMATCH = 'evidence_mismatch';
    case ORDERING_MISMATCH = 'ordering_mismatch';
    case OVERLAY_MISMATCH = 'overlay_mismatch';
    case CONSTITUTION_MISMATCH = 'constitution_mismatch';
    case SERIALIZATION_MISMATCH = 'serialization_mismatch';
    case THRESHOLD_INTERPRETATION_MISMATCH = 'threshold_interpretation_mismatch';
}
