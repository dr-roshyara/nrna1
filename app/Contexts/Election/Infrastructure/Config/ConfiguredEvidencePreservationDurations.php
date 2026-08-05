<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Config;

use App\Contexts\Election\Application\Port\EvidencePreservationDurations;
use DateInterval;
use RuntimeException;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Reads Policy 2's three durations, applying the override precedence the roadmap
 * requires: **organisation → election type → default**. Infrastructure, so the config
 * repository is fair game (house Rule 2).
 *
 * **A deliberate mirror of `ConfiguredAdjudicationDurations`** (ARB direction at GREEN
 * authorization): same precedence algorithm, same fail-closed discipline, same shape.
 * It differs only in **vocabulary**, **owned parameters** and **configuration keys** —
 * no new resolution algorithm is invented here.
 *
 * TWO HOMES, DELIBERATELY — and this is AP-2 preserved, not violated:
 *   - **CW and LSM** are Election-retention parameters and live in
 *     `config/election_preservation.php`;
 *   - **MAD** is read from **`config/adjudication.php`**, its ONE canonical home. This
 *     adapter consumes that key; it never declares one of its own.
 *
 * The values are INTERIM bootstraps; this adapter's job is to make replacing them a
 * configuration change rather than a code change.
 *
 * FAIL CLOSED (AP-1). No clamping, no substitute value, no default of our own. A
 * missing, non-numeric or non-positive duration is a CONFIGURATION ERROR, and silently
 * correcting it would mean this adapter chose a duration — which is Q-2's to own, never
 * infrastructure's. That exact defect (`max(1, $days)`) once shipped and passed every
 * automated gate; the fitness guard `DurationPolicyOwnershipTest` now watches this file.
 */
final class ConfiguredEvidencePreservationDurations implements EvidencePreservationDurations
{
    private const CONTESTATION_WINDOW = 'contestation_window_days';
    private const LEGAL_SAFETY_MARGIN = 'legal_safety_margin_days';

    /** MAD's ONE home — read, never redeclared (AP-2). */
    private const MAD = 'maximum_adjudication_duration_days';

    public function __construct(private readonly Config $config)
    {
    }

    public function contestationWindow(
        ?string $electionType = null,
        ?string $organisationId = null,
    ): DateInterval {
        return $this->resolve('election_preservation', self::CONTESTATION_WINDOW, $electionType, $organisationId);
    }

    public function maximumAdjudicationDuration(
        ?string $electionType = null,
        ?string $organisationId = null,
    ): DateInterval {
        // Adjudication's config file, not Adjudication's CODE. Reading a
        // governance-owned configuration value is not a context crossing: both contexts
        // are downstream of Q-2, not of each other (R-44).
        return $this->resolve('adjudication', self::MAD, $electionType, $organisationId);
    }

    public function legalSafetyMargin(
        ?string $electionType = null,
        ?string $organisationId = null,
    ): DateInterval {
        return $this->resolve('election_preservation', self::LEGAL_SAFETY_MARGIN, $electionType, $organisationId);
    }

    private function resolve(string $namespace, string $key, ?string $electionType, ?string $organisationId): DateInterval
    {
        $days = $this->override("{$namespace}.per_organisation.{$organisationId}", $key, $organisationId)
            ?? $this->override("{$namespace}.per_election_type.{$electionType}", $key, $electionType)
            ?? $this->configuredDefault($namespace, $key);

        // A non-positive duration is a configuration error, not an invitation to
        // substitute one of our own. Compare and throw — never clamp.
        if ($days < 1) {
            throw new RuntimeException(sprintf(
                '%s.%s resolved to %d; a Policy 2 duration must be at least one day. Fix the '
                . 'configuration -- this adapter will not choose one.',
                $namespace,
                $key,
                $days,
            ));
        }

        return new DateInterval('P'.$days.'D');
    }

    /**
     * The declared default lives in its config file and NOWHERE else — the number is the
     * ARB's, so this adapter must not carry a second copy of it. A missing or non-numeric
     * value is therefore a configuration error.
     */
    private function configuredDefault(string $namespace, string $key): int
    {
        $value = $this->config->get("{$namespace}.{$key}");

        if (!is_numeric($value)) {
            throw new RuntimeException(sprintf(
                '%s.%s is missing or non-numeric. Policy 2 durations are business policy (Q-2) '
                . 'and each has exactly one home.',
                $namespace,
                $key,
            ));
        }

        return (int) $value;
    }

    private function override(string $path, string $key, ?string $scope): ?int
    {
        if ($scope === null || $scope === '') {
            return null;
        }

        $scoped = $this->config->get($path);
        $value = is_array($scoped) ? ($scoped[$key] ?? null) : null;

        return is_numeric($value) ? (int) $value : null;
    }
}
