<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 5b (task 4 limit; tactical design §3.6 step 3): organisation databases are prepared ONLY in the
 * dedicated provisioning process. Properties of the whole `app/` tree (comments ignored):
 * - only the provisioning command enters the dedicated process;
 * - only the preparer (and the wiring) uses the organisation migration set;
 * - only the provisioning command uses the preparer.
 * A request path that migrated an organisation database in-process would break one of these.
 */
final class OrganisationProvisioningProcessTest extends TestCase
{
    private const COMMAND = 'app/Contexts/Platform/Infrastructure/Console/PrepareOrganisationDatabaseCommand.php';
    private const PREPARER = 'app/Contexts/Platform/Infrastructure/OrganisationDatabasePreparer.php';
    private const PROVIDER = 'app/Contexts/Platform/Infrastructure/Providers/PlatformServiceProvider.php';

    /** @return array<string, string> repository-relative path => code without comments */
    private static function appCode(): array
    {
        $root = dirname(__DIR__, 2);
        $out = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root . '/app', \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $code = '';
            foreach (token_get_all((string) file_get_contents($file->getPathname())) as $t) {
                $code .= is_array($t) ? (in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true) ? '' : $t[1]) : $t;
            }
            $out[substr($file->getPathname(), strlen($root) + 1)] = $code;
        }

        return $out;
    }

    /** @return list<string> */
    private static function filesMatching(string $pattern, string ...$except): array
    {
        $hits = [];
        foreach (self::appCode() as $path => $code) {
            if (preg_match($pattern, $code) === 1 && !in_array($path, $except, true)) {
                $hits[] = $path;
            }
        }
        sort($hits);

        return $hits;
    }

    public function test_only_the_provisioning_command_enters_the_dedicated_process(): void
    {
        $this->assertSame([self::COMMAND], self::filesMatching('/DedicatedProvisioningProcess::enter\s*\(/'));
    }

    public function test_only_the_preparer_and_the_wiring_use_the_organisation_migration_set(): void
    {
        $this->assertSame([self::PREPARER, self::PROVIDER], self::filesMatching('/\bOrganisationMigrations\b/',
            'app/Contexts/Platform/Infrastructure/OrganisationMigrations.php'));
    }

    public function test_only_the_provisioning_command_uses_the_preparer(): void
    {
        $this->assertSame([self::COMMAND], self::filesMatching('/\bOrganisationDatabasePreparer\b/', self::PREPARER));
    }

    public function test_the_preparer_requires_its_dedicated_process_before_anything_else(): void
    {
        $code = self::appCode()[self::PREPARER];
        $body = substr($code, (int) strpos($code, 'function prepare('));
        $this->assertMatchesRegularExpression('/^function prepare\([^)]*\): void\s*\{\s*DedicatedProvisioningProcess::requireFor\(\$id\);/', $body);
    }
}
