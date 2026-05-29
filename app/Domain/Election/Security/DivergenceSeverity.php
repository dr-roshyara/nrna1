<?php

namespace App\Domain\Election\Security;

/**
 * DivergenceSeverity
 *
 * Operational severity of sovereignty divergence.
 * Determines alerting threshold, migration blocking criteria, and telemetry priority.
 *
 * CONSTITUTIONAL LAW:
 * CRITICAL and EXISTENTIAL divergences BLOCK sovereignty transfer.
 * HIGH divergences require investigation before transfer.
 * WARNING divergences require documentation before transfer.
 * INFO divergences are expected during shadow mode convergence.
 */
enum DivergenceSeverity: string
{
    case Info        = 'info';
    case Warning     = 'warning';
    case High        = 'high';
    case Critical    = 'critical';
    case Existential = 'existential';

    /**
     * Determine severity from divergence type and outcome pair.
     *
     * | Divergence Type | Legacy | Constitutional | Severity |
     * |-----------------|--------|---------------|----------|
     * | ProceduralOverreach | Denied | Allowed | CRITICAL |
     * | SovereigntyLeak | Allowed | Denied | CRITICAL |
     * | LineageMismatch | Denied | Denied | WARNING |
     * | TopologyMismatch | Allowed | Allowed | INFO |
     * | ConstitutionalUncertainty | * | Deferred | HIGH |
     * | Exposure | Allowed | Investigate | HIGH |
     */
    public static function fromDivergence(
        DivergenceType $type,
        LegitimacyOutcome $legacy,
        LegitimacyOutcome $constitutional,
    ): self {
        return match (true) {
            $type === DivergenceType::ProceduralOverreach => self::Critical,
            $type === DivergenceType::SovereigntyLeak     => self::Critical,
            $type === DivergenceType::Exposure            => self::High,
            $type === DivergenceType::ConstitutionalUncertainty => self::High,
            $type === DivergenceType::LineageMismatch     => self::Warning,
            $type === DivergenceType::TopologyMismatch    => self::Info,
            default => self::Warning,
        };
    }

    /**
     * Whether this severity blocks sovereignty transfer.
     */
    public function blocksTransfer(): bool
    {
        return match ($this) {
            self::Critical,
            self::Existential => true,
            self::High,
            self::Warning,
            self::Info => false,
        };
    }

    /**
     * Whether this severity requires immediate investigation.
     */
    public function requiresInvestigation(): bool
    {
        return match ($this) {
            self::Critical,
            self::Existential,
            self::High => true,
            self::Warning,
            self::Info => false,
        };
    }
}
