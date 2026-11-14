<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Contexts\Platform\Infrastructure\Queue\OrganisationJob;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 7 (E-2; tactical design §3.10 Q-3, Q-6, §3.10.2): the architecture half of T-4, T-20, T-22, T-25.
 * - T-22 / Q-6: no organisation job uses SerializesModels or holds a Model property (identifiers only);
 * - T-20: only OrganisationJob puts work on the `organisation` connection; nothing else in app/ names it;
 * - T-25: outside the dispatcher (and, from task 9, the relay), no class under app/Contexts that names an organisation
 *   job calls dispatch(, Bus::, Queue::, ->onConnection( or ::dispatch;
 * - T-4: every queued class that uses an OrganisationContext is an OrganisationJob (carries the marker).
 * Comments are ignored. The organisation job fixtures under tests/Fixtures are checked for Q-6 too.
 */
final class OrganisationQueueArchitectureTest extends TestCase
{
    private const QUEUE_DIR = 'app/Contexts/Platform/Infrastructure/Queue/';
    private const DISPATCHER = 'app/Contexts/Platform/Infrastructure/Queue/OrganisationJobDispatcher.php';
    private const BASE = 'app/Contexts/Platform/Infrastructure/Queue/OrganisationJob.php';

    /** @return array<string, string> repository-relative path => code without comments */
    private static function sources(string ...$dirs): array
    {
        $root = dirname(__DIR__, 2);
        $out = [];
        foreach ($dirs as $dir) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("{$root}/{$dir}", \FilesystemIterator::SKIP_DOTS));
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
        }

        return $out;
    }

    /** @param array<string, string> $sources @return list<string> names of the organisation job classes */
    private static function organisationJobNames(array $sources): array
    {
        $names = ['OrganisationJob'];
        foreach ($sources as $code) {
            if (preg_match('/class\s+(\w+)\s+extends\s+\\\\?(?:[\w\\\\]+\\\\)?OrganisationJob\b/', $code, $m) === 1) {
                $names[] = $m[1];
            }
        }

        return array_values(array_unique($names));
    }

    /** @param array<string, string> $sources @return list<string> */
    public static function directDispatches(array $sources): array
    {
        $names = self::organisationJobNames($sources);
        $hits = [];
        foreach ($sources as $path => $code) {
            if ($path === self::DISPATCHER || $path === self::BASE || !str_starts_with($path, 'app/Contexts/')) {
                continue;
            }
            $mentions = preg_match('/\b(?:' . implode('|', $names) . ')\b/', $code) === 1;
            if ($mentions && preg_match('/\bdispatch(?:Sync|Now)?\s*\(|\bBus::|\bQueue::|->onConnection\s*\(|::dispatch\w*\s*\(/', $code) === 1) {
                $hits[] = $path;
            }
        }

        return $hits;
    }

    /** @param array<string, string> $sources @return list<string> */
    public static function organisationConnectionNamedOutsideTheQueue(array $sources): array
    {
        $hits = [];
        foreach ($sources as $path => $code) {
            if (str_starts_with($path, self::QUEUE_DIR)) {
                continue;
            }
            if (preg_match("/onConnection\\s*\\(\\s*['\"]organisation['\"]|connection\\s*=\\s*['\"]organisation['\"]|Queue::connection\\s*\\(\\s*['\"]organisation['\"]|OrganisationJob::CONNECTION/", $code) === 1) {
                $hits[] = $path;
            }
        }

        return $hits;
    }

    /** @param array<string, string> $sources @return list<string> */
    public static function queuedWithoutTheMarker(array $sources): array
    {
        $hits = [];
        foreach ($sources as $path => $code) {
            if (str_starts_with($path, self::QUEUE_DIR)) {
                continue;
            }
            if (str_contains($code, 'ShouldQueue') && str_contains($code, 'OrganisationContext')
                && preg_match('/extends\s+\\\\?(?:[\w\\\\]+\\\\)?OrganisationJob\b/', $code) !== 1) {
                $hits[] = $path;
            }
        }

        return $hits;
    }

    public function test_t22_organisation_jobs_carry_identifiers_only(): void
    {
        $sources = self::sources('app', 'tests/Fixtures');
        $checked = 0;
        foreach ($sources as $path => $code) {
            if (preg_match('/namespace\s+([\w\\\\]+);.*?class\s+(\w+)\s+extends\s+\\\\?(?:[\w\\\\]+\\\\)?OrganisationJob\b/s', $code, $m) !== 1) {
                continue;
            }
            $class = new \ReflectionClass($m[1] . '\\' . $m[2]);
            $this->assertTrue($class->isSubclassOf(OrganisationJob::class), $path);
            $this->assertNotContains('Illuminate\\Queue\\SerializesModels', array_keys(self::traitsOf($class)), "{$path}: SerializesModels (Q-6)");
            foreach ($class->getProperties() as $property) {
                $type = $property->getType();
                foreach ($type instanceof \ReflectionUnionType ? $type->getTypes() : ($type === null ? [] : [$type]) as $t) {
                    $this->assertFalse($t instanceof \ReflectionNamedType && !$t->isBuiltin() && is_a($t->getName(), \Illuminate\Database\Eloquent\Model::class, true),
                        "{$path}: Model property \${$property->getName()} (Q-6)");
                }
            }
            $checked++;
        }
        $this->assertGreaterThan(0, $checked, 'the check found the organisation jobs');
    }

    /** @return array<string, true> */
    private static function traitsOf(\ReflectionClass $class): array
    {
        $traits = [];
        for ($c = $class; $c !== false; $c = $c->getParentClass()) {
            foreach ($c->getTraitNames() as $t) {
                $traits[$t] = true;
            }
        }

        return $traits;
    }

    public function test_t20_only_the_organisation_job_names_the_organisation_connection(): void
    {
        $this->assertSame([], self::organisationConnectionNamedOutsideTheQueue(self::sources('app')));
    }

    public function test_t25_organisation_jobs_are_dispatched_by_the_dispatcher_only(): void
    {
        $this->assertSame([], self::directDispatches(self::sources('app')));
    }

    public function test_t4_every_queued_class_using_an_organisation_context_is_an_organisation_job(): void
    {
        $this->assertSame([], self::queuedWithoutTheMarker(self::sources('app')));
    }

    public function test_the_checks_see_planted_violations(): void
    {
        $planted = [
            'app/Contexts/Membership/Infrastructure/Jobs/RenewJob.php' => '<?php class RenewJob extends OrganisationJob {}',
            'app/Contexts/Membership/Infrastructure/Renewals.php' => '<?php RenewJob::dispatch($org);',
            'app/Contexts/Newsletter/Infrastructure/Send.php' => "<?php \$job->onConnection('organisation');",
            'app/Jobs/Rogue.php' => '<?php final class Rogue implements ShouldQueue { public function handle(OrganisationContext $c) {} }',
            self::DISPATCHER => '<?php $this->bus->dispatch($job); // OrganisationJob',
        ];

        $this->assertSame(['app/Contexts/Membership/Infrastructure/Renewals.php'], self::directDispatches($planted));
        $this->assertSame(['app/Contexts/Newsletter/Infrastructure/Send.php'], self::organisationConnectionNamedOutsideTheQueue($planted));
        $this->assertSame(['app/Jobs/Rogue.php'], self::queuedWithoutTheMarker($planted));
    }
}
