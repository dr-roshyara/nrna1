<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\RedemptionThrottle;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;

/**
 * PBDIGIT-349 I-3, INV-ID-9 (D-197): failed redemptions counted per code and per client in a fixed window on the
 * central database's clock. At the limit, attempts are refused until the window ends: throttling SLOWS an attacker (and
 * the holder while an attack is under way) and never disables the claim. Limits: config/identity.php, values [TU].
 *
 * Privacy (review R-2; I-TDD-02 §16, INV-ID-8):
 * - the CODE key is the claim code's SHA-256 hash, passed explicitly and checked to be one (never inferred);
 * - the CLIENT key (e.g. an IP address) is stored only as HMAC-SHA-256 under a server secret: a plain hash of an IPv4
 *   address could be reversed by trying all 2^32 addresses;
 * - rows whose window has ended are deleted whenever a failure is recorded. They are ignored by every check once the
 *   window ends, but if no failure is recorded again the last ended rows stay until the next one (residual, reported);
 * - without a usable secret every call is refused as unavailable (fail closed); the secret is never stored or logged.
 * Concurrency (review R-5, residual risk): the check and the count are separate statements, so a burst of simultaneous
 * attempts can exceed the limit by its own size. With about 100 bits per code this does not make guessing feasible.
 */
final class PostgresRedemptionThrottle implements RedemptionThrottle
{
    private const MINIMUM_SECRET_LENGTH = 16;

    public function __construct(
        private readonly Connection $db,
        private readonly int $perCode,
        private readonly int $perClient,
        private readonly int $windowSeconds,
        private readonly string $clientKeySecret,
    ) {
        if ($perCode < 1 || $perClient < 1 || $windowSeconds < 1) {
            throw new \InvalidArgumentException('Throttle limits and window must be positive.');
        }
    }

    public function isThrottled(string $codeKey, string $clientKey): bool
    {
        [$code, $client] = [self::codeKey($codeKey), $this->clientKey($clientKey)];
        try {
            return $this->db->selectOne(
                "SELECT 1 FROM identity_redemption_failures
                 WHERE window_start > clock_timestamp() - make_interval(secs => ?)
                   AND ((scope = 'code' AND key_hash = ? AND failures >= ?) OR (scope = 'client' AND key_hash = ? AND failures >= ?))",
                [$this->windowSeconds, $code, $this->perCode, $client, $this->perClient],
            ) !== null;
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not check throttling (' . $e::class . ').', previous: $e);
        }
    }

    public function recordFailure(string $codeKey, string $clientKey): void
    {
        [$code, $client] = [self::codeKey($codeKey), $this->clientKey($clientKey)];
        try {
            $this->db->delete('DELETE FROM identity_redemption_failures WHERE window_start <= clock_timestamp() - make_interval(secs => ?)', [$this->windowSeconds]);
            foreach (['code' => $code, 'client' => $client] as $scope => $key) {
                $this->db->statement(
                    "INSERT INTO identity_redemption_failures (scope, key_hash, window_start, failures) VALUES (?, ?, clock_timestamp(), 1)
                     ON CONFLICT (scope, key_hash) DO UPDATE SET
                       failures = CASE WHEN identity_redemption_failures.window_start > clock_timestamp() - make_interval(secs => ?)
                                       THEN identity_redemption_failures.failures + 1 ELSE 1 END,
                       window_start = CASE WHEN identity_redemption_failures.window_start > clock_timestamp() - make_interval(secs => ?)
                                       THEN identity_redemption_failures.window_start ELSE clock_timestamp() END",
                    [$scope, $key, $this->windowSeconds, $this->windowSeconds],
                );
            }
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not record the attempt (' . $e::class . ').', previous: $e);
        }
    }

    /** The code key IS the claim code's SHA-256 hash (lower-case hex); anything else is a programming error. */
    private static function codeKey(string $codeKey): string
    {
        if (preg_match('/^[0-9a-f]{64}$/', $codeKey) !== 1) {
            throw new \InvalidArgumentException('A throttle code key is the claim code\'s SHA-256 hash.');
        }

        return $codeKey;
    }

    /** The client key, always keyed: HMAC-SHA-256 under the server secret. No usable secret → refused (fail closed). */
    private function clientKey(string $clientKey): string
    {
        if (strlen($this->clientKeySecret) < self::MINIMUM_SECRET_LENGTH) {
            throw new IdentityUnavailable('The redemption throttle has no usable client-key secret.');
        }

        return hash_hmac('sha256', $clientKey, $this->clientKeySecret);
    }
}
