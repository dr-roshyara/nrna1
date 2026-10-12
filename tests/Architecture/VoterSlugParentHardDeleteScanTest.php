<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-274 S9b (plan section 10.4, O-12 = a): a static scan for application hard deletes of the parents of voter
 * slugs (users, organisations, elections). A hard delete cascades in the database and destroys voting-link history
 * (voter_slugs, voter_slug_steps, codes).
 *
 * This documents the CURRENT state only. It is limited evidence: it cannot see raw SQL built elsewhere, future
 * jobs or database administration. The known sites are allow-listed with their reason; any new one fails the test
 * and needs a decision.
 */
final class VoterSlugParentHardDeleteScanTest extends TestCase
{
    /** file => reason. Both re-create a DEMO election: the query is filtered to type = 'demo' (real slugs never point there). */
    private const ALLOWED = [
        'app/Console/Commands/SetupDemoElection.php' => 'demo election re-creation (type = demo)',
        'app/Console/Commands/SetupPublicDemoElection.php' => 'public demo election re-creation (type = demo)',
    ];

    public function test_no_application_forcedelete_of_slug_parents(): void
    {
        $root = dirname(__DIR__, 2);
        $found = [];

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root.'/app', \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $relative = substr($file->getPathname(), strlen($root) + 1);
            $source = file_get_contents($file->getPathname());

            // Eloquent force deletes on the parent models (instance or query), and raw table deletes on the parents.
            $patterns = [
                '/\b(User|Organisation|Election)::[^;]*->forceDelete\(/s',
                '/\$\w*(user|organisation|organization|election)\w*->forceDelete\(/i',
                "/DB::table\\(\\s*'(users|organisations|elections)'\\s*\\)[^;]*->delete\\(/s",
                '/\bDELETE\s+FROM\s+"?(users|organisations|elections)\b/i',
            ];
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $source)) {
                    $found[$relative] = true;
                }
            }
        }

        $unexpected = array_values(array_diff(array_keys($found), array_keys(self::ALLOWED)));
        sort($unexpected);
        $this->assertSame([], $unexpected, 'new hard delete of a voter-slug parent: decide under O-12 before allowing it');
        foreach (array_keys(self::ALLOWED) as $allowed) {
            $this->assertArrayHasKey($allowed, $found, "allow-listed site {$allowed} no longer matches: remove it from the list");
        }
    }
}
