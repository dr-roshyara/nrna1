<?php

namespace App\Services\Voting;

use App\Exceptions\Voting\VoterSlugLifetimeMisconfigured;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * The single voter-slug lifetime rule (PBDIGIT-274, plan L-1 … L-3).
 *
 * A slug ends at min(expires_at, created_at + minutes); it is expired when now >= that end, and a missing
 * timestamp counts as expired. Instants are compared, so the clock's time zone does not matter.
 * Pure: no database, no clock of its own (callers pass "now").
 */
final class VoterSlugLifetime
{
    private function __construct(private readonly int $minutes)
    {
    }

    /**
     * Strict (O-10 = B): an int > 0, or a string of digits without a leading zero. Anything else fails closed
     * with a reason code; there is no fallback. A missing environment variable is resolved to the documented
     * default 30 by config/voting.php before this point (O-9 = a); null here means the key itself is absent.
     */
    public static function fromConfig(mixed $raw): self
    {
        if ($raw === null || $raw === '') {
            throw new VoterSlugLifetimeMisconfigured('missing', get_debug_type($raw));
        }
        if (is_int($raw)) {
            $minutes = $raw;
        } elseif (is_string($raw) && preg_match('/^-?[1-9][0-9]*$|^-?0$/', $raw) === 1) {
            $minutes = (int) $raw;
        } else {
            throw new VoterSlugLifetimeMisconfigured('not_integer', get_debug_type($raw));
        }
        if ($minutes <= 0) {
            throw new VoterSlugLifetimeMisconfigured('non_positive', get_debug_type($raw));
        }

        return new self($minutes);
    }

    public function minutes(): int
    {
        return $this->minutes;
    }

    public function endsAt(Model $slug): ?CarbonImmutable
    {
        if ($slug->expires_at === null || $slug->created_at === null) {
            return null;
        }
        $expiresAt = CarbonImmutable::instance($slug->expires_at);
        $capped = CarbonImmutable::instance($slug->created_at)->addMinutes($this->minutes);

        return $expiresAt->lessThan($capped) ? $expiresAt : $capped;
    }

    public function isExpired(Model $slug, CarbonInterface $now): bool
    {
        $end = $this->endsAt($slug);

        return $end === null || $now->greaterThanOrEqualTo($end);
    }

    /** L-1: the issuer may reuse only a slug that is current, active, unvoted, not marked expired and within its lifetime. */
    public function isReusable(Model $slug, CarbonInterface $now): bool
    {
        if (method_exists($slug, 'trashed') && $slug->trashed()) {
            return false;
        }

        return (bool) $slug->is_active
            && !(bool) $slug->has_voted
            && $slug->status !== 'expired'
            && !$this->isExpired($slug, $now);
    }
}
