<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Config;

use App\Contexts\Adjudication\Application\Port\AdjudicationDurations;
use DateInterval;
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

        return new DateInterval('P'.max(1, $days).'D');
    }

    private function configuredDefault(): int
    {
        $value = $this->config->get('adjudication.'.self::KEY, 60);

        return is_numeric($value) ? (int) $value : 60;
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
