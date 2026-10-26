<?php

namespace App\Domain\Election\Security;

/**
 * IpBindingNetwork — which addresses satisfy IP Binding for a captured address (PBDIGIT-315, PO 2026-10-26,
 * superseding PBDIGIT-247 B3 "exact address"). Pure PHP.
 *
 * An IPv4 address binds its /24 network (106.101.7.23 → 106.101.7.0/24); an IPv4-mapped IPv6 address is that IPv4
 * address; an IPv6 address binds its /64 network. Separate from IpVoteGroup on purpose: the per-IP vote limit counts
 * an IPv4 address on its own. An unreadable or missing address has no network and never matches (fail closed, B1).
 */
final class IpBindingNetwork
{
    public const IPV4_PREFIX = 24;
    public const IPV6_PREFIX = 64;

    /** The network key "network/prefix", or null for an unreadable or missing address. */
    public static function of(?string $ip): ?string
    {
        $packed = $ip === null ? false : @inet_pton($ip);
        if ($packed === false) {
            return null;
        }
        if (str_starts_with($packed, str_repeat("\0", 10)."\xff\xff")) {
            $packed = substr($packed, 12);
        }
        $prefix = strlen($packed) === 4 ? self::IPV4_PREFIX : self::IPV6_PREFIX;

        return inet_ntop(self::network($packed, $prefix)).'/'.$prefix;
    }

    public static function matches(?string $captured, ?string $request): bool
    {
        $network = self::of($captured);

        return $network !== null && $network === self::of($request);
    }

    private static function network(string $packed, int $prefix): string
    {
        $network = '';
        for ($i = 0, $n = strlen($packed); $i < $n; $i++) {
            $bits = max(0, min(8, $prefix - 8 * $i));
            $network .= chr(ord($packed[$i]) & (0xFF << (8 - $bits)) & 0xFF);
        }

        return $network;
    }
}
