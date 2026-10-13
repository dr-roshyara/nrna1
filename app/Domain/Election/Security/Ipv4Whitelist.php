<?php

namespace App\Domain\Election\Security;

/**
 * Ipv4Whitelist — an election's network whitelist (P-WHITELIST) as raw IPv4 CIDR ranges / exact
 * addresses (Q3-D5, PBDIGIT-246). Pure PHP.
 *
 * Matching happens inside the Security Verdict Authority on the raw request IP; only the result
 * ("whitelisted") enters the evidence. Scope: IPv4 only (PO 2026-10-05). A malformed entry or a
 * non-IPv4 request IP matches nothing and never throws.
 */
final class Ipv4Whitelist
{
    /** @param list<string> $entries */
    private function __construct(private readonly array $entries) {}

    /** From the election's ip_whitelist value (array cast; a JSON string or null is tolerated). */
    public static function fromEntries(mixed $entries): self
    {
        if (is_string($entries)) {
            $decoded = json_decode($entries, true);
            $entries = is_array($decoded) ? $decoded : [];
        }

        return new self(is_array($entries) ? array_values(array_filter($entries, 'is_string')) : []);
    }

    public function contains(string $ip): bool
    {
        $address = self::toLong($ip);
        if ($address === null) {
            return false;
        }

        foreach ($this->entries as $entry) {
            if (self::entryMatches(trim($entry), $address)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether an entry is one this matcher can read: an exact IPv4 address or an IPv4 CIDR range with a prefix of
     * 0–32 (PBDIGIT-285: server-side validation of the election's whitelist uses the same parser as matching).
     */
    public static function isValidEntry(string $entry): bool
    {
        $entry = trim($entry);
        if (! str_contains($entry, '/')) {
            return self::toLong($entry) !== null;
        }
        [$network, $bits] = explode('/', $entry, 2);

        return ctype_digit($bits) && (int) $bits <= 32 && self::toLong($network) !== null;
    }

    private static function entryMatches(string $entry, int $address): bool
    {
        if (! str_contains($entry, '/')) {
            return self::toLong($entry) === $address;
        }

        [$network, $bits] = explode('/', $entry, 2);
        if (! ctype_digit($bits) || (int) $bits > 32) {
            return false;
        }

        $base = self::toLong($network);
        if ($base === null) {
            return false;
        }

        $prefix = (int) $bits;
        $mask = $prefix === 0 ? 0 : (0xFFFFFFFF << (32 - $prefix)) & 0xFFFFFFFF;

        return ($address & $mask) === ($base & $mask);
    }

    private static function toLong(string $ip): ?int
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            return null;
        }

        $long = ip2long($ip);

        return $long === false ? null : $long;
    }
}
