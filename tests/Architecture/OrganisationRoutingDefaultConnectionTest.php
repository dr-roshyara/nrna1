<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 T-10 part 1 (delta §3.3 C-1, C-3): organisation-routing code never uses Laravel's default (central)
 * connection, never opens a default-connection transaction, and never re-points the default. Grows with the slice:
 * add each organisation-context class to ORGANISATION_CODE as it is built (tasks 6, 7, 9, 10).
 */
final class OrganisationRoutingDefaultConnectionTest extends TestCase
{
    private const ORGANISATION_CODE = [
        'app/Contexts/Platform/Infrastructure/OrganisationConnectionFactory.php',
        'app/Contexts/Platform/Infrastructure/OrganisationContext.php',
    ];

    /** @return list<string> what in $source would use or change the default connection */
    private static function defaultConnectionUses(string $source): array
    {
        $code = '';
        foreach (token_get_all($source) as $t) {
            $code .= is_array($t) ? (in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true) ? '' : $t[1]) : $t;
        }
        $found = [];
        foreach ([
            'DB::connection() without a name' => '/DB::connection\(\s*\)/',
            'DB::transaction (default connection)' => '/DB::transaction\s*\(/',
            'a raw query on the default connection' => '/DB::(select|selectOne|insert|update|delete|statement|table)\s*\(/',
            're-pointing the default' => '/database\.default|setDefaultConnection/',
        ] as $label => $pattern) {
            if (preg_match($pattern, $code) === 1) {
                $found[] = $label;
            }
        }

        return $found;
    }

    public function test_the_detector_finds_each_forbidden_use(): void
    {
        $bad = '<?php DB::connection(); DB::transaction(fn () => 1); DB::select("x"); config(["database.default" => "x"]);';

        $this->assertSame(['DB::connection() without a name', 'DB::transaction (default connection)', 'a raw query on the default connection', 're-pointing the default'], self::defaultConnectionUses($bad));
        $this->assertSame([], self::defaultConnectionUses('<?php // DB::transaction( in a comment is fine' . "\n" . 'DB::connection($name); DB::purge($name);'));
    }

    public function test_organisation_routing_code_never_uses_the_default_connection(): void
    {
        $root = dirname(__DIR__, 2);
        foreach (self::ORGANISATION_CODE as $file) {
            $this->assertFileExists("$root/$file");
            $this->assertSame([], self::defaultConnectionUses((string) file_get_contents("$root/$file")), $file);
        }
    }
}
