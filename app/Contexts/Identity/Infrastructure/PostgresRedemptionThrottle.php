<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\RedemptionThrottle;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;

/**
 * PBDIGIT-349 I-3, INV-ID-9 (D-197): failed redemptions counted per code (its hash) and per client (the hash of the
 * client key) in a fixed window on the central database's clock. At the limit, attempts are refused until the window
 * ends: throttling SLOWS an attacker (and the holder while an attack is under way) and never disables the claim.
 * The limits come from config/identity.php; their values are [TU] (to be confirmed by the Principal).
 */
final class PostgresRedemptionThrottle implements RedemptionThrottle
{
    public function __construct(
        private readonly Connection $db,
        private readonly int $perCode,
        private readonly int $perClient,
        private readonly int $windowSeconds,
    ) {
        if ($perCode < 1 || $perClient < 1 || $windowSeconds < 1) {
            throw new \InvalidArgumentException('Throttle limits and window must be positive.');
        }
    }

    public function isThrottled(string $codeKey, string $clientKey): bool
    {
        try {
            return $this->db->selectOne(
                "SELECT 1 FROM identity_redemption_failures
                 WHERE window_start > clock_timestamp() - make_interval(secs => ?)
                   AND ((scope = 'code' AND key_hash = ? AND failures >= ?) OR (scope = 'client' AND key_hash = ? AND failures >= ?))",
                [$this->windowSeconds, self::hashKey($codeKey), $this->perCode, self::hashKey($clientKey), $this->perClient],
            ) !== null;
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not check throttling (' . $e::class . ').', previous: $e);
        }
    }

    public function recordFailure(string $codeKey, string $clientKey): void
    {
        try {
            foreach (['code' => $codeKey, 'client' => $clientKey] as $scope => $key) {
                $this->db->statement(
                    "INSERT INTO identity_redemption_failures (scope, key_hash, window_start, failures) VALUES (?, ?, clock_timestamp(), 1)
                     ON CONFLICT (scope, key_hash) DO UPDATE SET
                       failures = CASE WHEN identity_redemption_failures.window_start > clock_timestamp() - make_interval(secs => ?)
                                       THEN identity_redemption_failures.failures + 1 ELSE 1 END,
                       window_start = CASE WHEN identity_redemption_failures.window_start > clock_timestamp() - make_interval(secs => ?)
                                       THEN identity_redemption_failures.window_start ELSE clock_timestamp() END",
                    [$scope, self::hashKey($key), $this->windowSeconds, $this->windowSeconds],
                );
            }
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not record the attempt (' . $e::class . ').', previous: $e);
        }
    }

    /** A code key is already a SHA-256 hex hash; any other key (a client key) is hashed, never stored raw. */
    private static function hashKey(string $key): string
    {
        return preg_match('/^[0-9a-f]{64}$/', $key) === 1 ? $key : hash('sha256', $key);
    }
}
