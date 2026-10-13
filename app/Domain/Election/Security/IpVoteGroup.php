<?php

namespace App\Domain\Election\Security;

/**
 * IpVoteGroup — which addresses count together for the per-IP vote limit (PBDIGIT-288 item 4, Human PO 2026-10-13).
 * Pure PHP.
 *
 * An IPv4 address counts on its own (an IPv4-mapped IPv6 address is that IPv4 address). An IPv6 address counts with
 * its network of the given prefix, /64 by default, because one household or device usually holds a whole /64 and can
 * change addresses within it. An unreadable address is its own group (exact match) and never throws.
 */
final class IpVoteGroup
{
    public const DEFAULT_IPV6_PREFIX = 64;

    /** The group key: the canonical IPv4 address, or the canonical IPv6 network as "network/prefix". */
    public static function of(string $ip, int $ipv6Prefix = self::DEFAULT_IPV6_PREFIX): string
    {
        $packed = @inet_pton($ip);
        if ($packed === false) {
            return $ip;
        }
        if (strlen($packed) === 4) {
            return inet_ntop($packed);
        }
        if (str_starts_with($packed, str_repeat("\0", 10)."\xff\xff")) {
            return inet_ntop(substr($packed, 12));
        }
        if ($ipv6Prefix < 1 || $ipv6Prefix > 128) {
            $ipv6Prefix = self::DEFAULT_IPV6_PREFIX;
        }

        return inet_ntop(self::network($packed, $ipv6Prefix)).'/'.$ipv6Prefix;
    }

    public static function sameGroup(string $a, string $b, int $ipv6Prefix = self::DEFAULT_IPV6_PREFIX): bool
    {
        return self::of($a, $ipv6Prefix) === self::of($b, $ipv6Prefix);
    }

    /**
     * For an IPv4 address (plain or IPv4-mapped): how it may be stored, [plain, mapped]; null for anything else.
     *
     * @return list<string>|null
     */
    public static function spellingsOfIpv4(string $ip): ?array
    {
        $group = self::of($ip);
        $packed = @inet_pton($group);

        return $packed !== false && strlen($packed) === 4 ? [$group, '::ffff:'.$group] : null;
    }

    private static function network(string $packed, int $prefix): string
    {
        $network = '';
        for ($i = 0; $i < 16; $i++) {
            $bits = max(0, min(8, $prefix - 8 * $i));
            $network .= chr(ord($packed[$i]) & (0xFF << (8 - $bits)) & 0xFF);
        }

        return $network;
    }
}
