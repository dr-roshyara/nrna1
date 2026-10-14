<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Which database connections PHP source names, read from the source text (PBDIGIT-283 R5).
 *
 * Comments are removed with PHP's tokenizer first, so a mention in a comment never counts; a mention inside a string
 * literal may count, which errs on the safe side. Pure: no framework, no database.
 */
final class ConnectionReferences
{
    /** The source without comments and doc comments. */
    public static function withoutComments(string $source): string
    {
        $out = '';
        foreach (token_get_all($source) as $token) {
            if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }
            $out .= is_array($token) ? $token[1] : $token;
        }

        return $out;
    }

    /**
     * Connection names given as literals: `->connection('x')` / `::connection('x')` calls, and `$connection = 'x'`
     * (a model or service property, or a variable).
     *
     * @return list<string> in order of appearance, without duplicates
     */
    public static function named(string $source): array
    {
        $code = self::withoutComments($source);
        preg_match_all('/\bconnection\(\s*([\'"])(\w+)\1|\$connection\s*=\s*([\'"])(\w+)\3/', $code, $m, PREG_SET_ORDER);
        $names = [];
        foreach ($m as $match) {
            $names[] = ($match[2] ?? '') !== '' ? $match[2] : $match[4];
        }

        return array_values(array_unique($names));
    }

    /** Whether a class overrides getConnection(), which a migration could use to pick another connection. */
    public static function overridesGetConnection(string $source): bool
    {
        return preg_match('/\bfunction\s+getConnection\s*\(/', self::withoutComments($source)) === 1;
    }

    /**
     * The connection names configured in config/database.php's `connections` array (each entry has a 'driver').
     *
     * @return list<string>
     */
    public static function configuredConnections(string $configSource): array
    {
        preg_match_all('/([\'"])(\w+)\1\s*=>\s*\[\s*([\'"])driver\3/', self::withoutComments($configSource), $m);

        return $m[2];
    }
}
