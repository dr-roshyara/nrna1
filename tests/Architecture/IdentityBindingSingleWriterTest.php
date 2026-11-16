<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-349 I-3, PI-44 ("no alternate path: only the shared redemption service redeems claims") — review R-4.
 * A source scan over app/, routes/ and database/ for every way of WRITING a binding or REDEEMING a claim:
 *   W1 raw SQL:      INSERT INTO / UPDATE / DELETE FROM identity_bindings;
 *   W2 query builder: table('identity_bindings') … then an insert…, upsert, update… or delete call (across lines, to the
 *                     end of the statement);
 *   W3 Eloquent:      a model bound to identity_bindings ($table = 'identity_bindings');
 *   W4 redeeming:     a claim state set to 'redeemed', in SQL (state = 'redeemed') or in an array ('state' => 'redeemed').
 * The only allowed writer is PostgresClaims. The same scanner must find EVERY rule in the planted bypasses of
 * tests/Fixtures/IdentityBindingBypass (*.php.fixture, never loaded), so a new bypass of these kinds fails here.
 *
 * WHAT THIS DOES NOT PROVE (stated, not hidden): it is a text scan of known shapes, not a proof that no other writer
 * exists. Known misses: dynamic or quoted / schema-qualified table names; TRUNCATE, MERGE, COPY; a builder kept in a
 * variable and written later; ->from(...) or a getTable() override; 'redeemed' passed as a bound parameter; a second
 * writer inside PostgresClaims.php itself (the whole file is allowed); code outside the scanned roots; other database
 * clients. The database is only a partial backstop: an existing binding cannot be updated or deleted (trigger), but a
 * rogue INSERT (or TRUNCATE) is limited only by UNIQUE (organisation_id, person_ref) and (organisation_id, person_id).
 * No test enforces that only Identity's infrastructure reaches Identity's tables; the rest rests on code review.
 */
final class IdentityBindingSingleWriterTest extends TestCase
{
    private const ALLOWED = ['app/Contexts/Identity/Infrastructure/PostgresClaims.php'];

    /** @var array<string, string> rule => pattern */
    private const RULES = [
        'W1 raw SQL write' => '/\b(INSERT\s+INTO|UPDATE|DELETE\s+FROM)\s+identity_bindings\b/i',
        'W2 query-builder write' => '/table\(\s*[\'"]identity_bindings[\'"]\s*\)[^;]*?->\s*(insert\w*|upsert|update\w*|delete)\s*\(/is',
        'W3 Eloquent model' => '/\$table\s*=\s*[\'"]identity_bindings[\'"]/',
        'W4 redeeming a claim' => '/\bstate\s*=\s*\'redeemed\'|[\'"]state[\'"]\s*=>\s*[\'"]redeemed[\'"]/i',
    ];

    /** @return list<string> "rule: relative path" for every match under $root (app, routes, database) */
    private static function findings(string $root, string $suffix): array
    {
        $out = [];
        foreach (['app', 'routes', 'database'] as $top) {
            if (!is_dir("$root/$top")) {
                continue;
            }
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("$root/$top", \FilesystemIterator::SKIP_DOTS)) as $file) {
                if (!str_ends_with($file->getFilename(), $suffix)) {
                    continue;
                }
                $source = (string) file_get_contents($file->getPathname());
                $relative = substr($file->getPathname(), strlen($root) + 1);
                foreach (self::RULES as $rule => $pattern) {
                    if (preg_match($pattern, $source) === 1) {
                        $out[] = "{$rule}: {$relative}";
                    }
                }
            }
        }
        sort($out);

        return $out;
    }

    public function test_only_the_shared_redemption_service_writes_bindings_or_redeems_claims(): void
    {
        $findings = self::findings(dirname(__DIR__, 2), '.php');
        $outside = array_values(array_filter($findings, static fn (string $f): bool => !in_array(substr($f, strpos($f, ': ') + 2), self::ALLOWED, true)));

        $this->assertSame([], $outside, 'PI-44: no alternate writer of bindings and no other path that redeems a claim');
        $this->assertContains('W1 raw SQL write: app/Contexts/Identity/Infrastructure/PostgresClaims.php', $findings, 'the scanner sees the legitimate writer (it is not dead)');
        $this->assertContains('W4 redeeming a claim: app/Contexts/Identity/Infrastructure/PostgresClaims.php', $findings);
    }

    public function test_the_guard_finds_a_planted_bypass_of_every_rule(): void
    {
        $findings = self::findings(dirname(__DIR__) . '/Fixtures/IdentityBindingBypass', '.php.fixture');

        foreach (array_keys(self::RULES) as $rule) {
            $this->assertNotEmpty(array_filter($findings, static fn (string $f): bool => str_starts_with($f, "{$rule}: ")), "no planted bypass of '{$rule}' was found");
        }
        $this->assertContains('W2 query-builder write: database/seeders/RogueBindingSeeder.php.fixture', $findings, 'a builder chain over several lines');
    }
}
