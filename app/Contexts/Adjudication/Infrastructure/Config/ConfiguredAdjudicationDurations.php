<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Config;

use App\Contexts\Adjudication\Application\Port\AdjudicationDurations;
use DateInterval;
use RuntimeException;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Reads the temporal parameters from `config/adjudication.php`, applying the
 * override precedence roadmap §WP-6 requires: **organisation → election type →
 * default**. Infrastructure, so the config repository is fair game (house Rule 2).
 *
 * The values are INTERIM bootstraps; this adapter's job is to make replacing them a
 * configuration change rather than a code change.
 */
final class ConfiguredAdjudicationDurations implements AdjudicationDurations
{
    private const KEY = 'maximum_adjudication_duration_days';

    public function __construct(private readonly Config $config)
    {
    }

    public function maximumAdjudicationDuration(
        ?string $electionType = null,
        ?string $organisationId = null,
    ): DateInterval {
        $days = $this->override("adjudication.per_organisation.{$organisationId}", $organisationId)
            ?? $this->override("adjudication.per_election_type.{$electionType}", $electionType)
            ?? $this->configuredDefault();

        // No clamping and no substitute value. A non-positive horizon is a CONFIGURATION
        // ERROR, and silently correcting it would mean this adapter chose a duration --
        // which is Q-2's to own, never infrastructure's. Fail closed, per the house
        // config discipline (EG-002a: a config value that cannot be trusted blocks).
        if ($days < 1) {
            throw new RuntimeException(sprintf(
                'adjudication.%s resolved to %d; a Maximum Adjudication Duration must be at '
                . 'least one day. Fix the configuration -- this adapter will not choose one.',
                self::KEY,
                $days,
            ));
        }

        return new DateInterval('P'.$days.'D');
    }

    /**
     * The declared default lives in `config/adjudication.php` and NOWHERE else — the
     * number is the ARB's, so this adapter must not carry a second copy of it. A missing
     * or non-numeric value is therefore a configuration error, not an invitation to
     * substitute a value of our own.
     */
    private function configuredDefault(): int
    {
        $value = $this->config->get('adjudication.'.self::KEY);

        if (!is_numeric($value)) {
            throw new RuntimeException(
                'adjudication.'.self::KEY.' is missing or non-numeric. The Maximum Adjudication '
                . 'Duration is business policy (Q-2) and has exactly one home: config/adjudication.php.',
            );
        }

        return (int) $value;
    }

    private function override(string $path, ?string $scope): ?int
    {
        if ($scope === null || $scope === '') {
            return null;
        }

        $scoped = $this->config->get($path);
        $value = is_array($scoped) ? ($scoped[self::KEY] ?? null) : null;

        return is_numeric($value) ? (int) $value : null;
    }
}
