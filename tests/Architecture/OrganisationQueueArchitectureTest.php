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
            // Laravel's own routes: the dispatch()/dispatch_sync() helpers, X::dispatch…(), Bus::, Queue::, ->onConnection(
            $laravelRoute = preg_match('/(?<!->)\bdispatch(?:_sync|Sync|Now)?\s*\(|\bBus::|\bQueue::|->onConnection\s*\(|::dispatch\w*\s*\(/', $code) === 1;
            // ->dispatch( is allowed only as OrganisationJobDispatcher's method, decided PER CALL (Principal's review of task 8):
            // the receiver must be a variable or property declared `OrganisationJobDispatcher $name` in this file, or
            // app(OrganisationJobDispatcher::class). Any other receiver, or a raw Bus dispatcher import, is a hit.
            $rawBus = preg_match('/Illuminate\\\\(?:Contracts\\\\)?Bus\\\\(?:Queueing)?Dispatcher\b/', $code) === 1;
            $methodRoute = $rawBus || self::hasUnapprovedDispatchCall($code);
            if ($mentions && ($laravelRoute || $methodRoute)) {
                $hits[] = $path;
            }
        }

        return $hits;
    }

    /** true if some `->dispatch(` in $code is called on anything but a declared OrganisationJobDispatcher */
    private static function hasUnapprovedDispatchCall(string $code): bool
    {
        preg_match_all('/\bOrganisationJobDispatcher\s+\$(\w+)/', $code, $declared);
        $approved = array_flip($declared[1]);
        // every receiver of ->dispatch( : $name, $this->name, or app(OrganisationJobDispatcher::class)
        preg_match_all('/(app\(\s*\\\\?(?:[\w\\\\]+\\\\)?OrganisationJobDispatcher::class\s*\)|\$this->(\w+)|\$(\w+)|\S)\s*->\s*dispatch\s*\(/', $code, $calls, PREG_SET_ORDER);
        foreach ($calls as $call) {
            if (str_starts_with($call[1], 'app(')) {
                continue;
            }
            $name = ($call[2] ?? '') !== '' ? $call[2] : ($call[3] ?? '');
            if ($name === '' || !isset($approved[$name])) {
                return true;
            }
        }

        return false;
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

    /** task 8 (E-3): "no scheduled task touches an organisation database directly": the fan-out has no way to */
    public function test_the_fan_out_never_reaches_an_organisation_database(): void
    {
        $fanOut = [
            'app/Contexts/Platform/Application/FanOutToOrganisations.php',
            'app/Contexts/Platform/Infrastructure/Console/FanOutCommand.php',
            'app/Contexts/Platform/Infrastructure/Queue/FanOutJobDispatch.php',
            'app/Contexts/Platform/Infrastructure/PostgresRegisteredOrganisations.php',
        ];
        $sources = self::sources('app/Contexts/Platform');
        foreach ($fanOut as $path) {
            $this->assertArrayHasKey($path, $sources);
            $this->assertDoesNotMatchRegularExpression('/\b(?:OrganisationConnectionFactory|OrganisationContext|EstablishOrganisationContext)\b/', $sources[$path], $path);
        }
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

        // task 8 refinement: the dispatcher's own method is allowed; every Laravel route stays forbidden
        $routes = [
            'app/Contexts/X/ThroughTheDispatcher.php' => '<?php use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher;'
                . ' final class D { public function __construct(private readonly OrganisationJobDispatcher $dispatcher) {}'
                . ' public function go() { $this->dispatcher->dispatch(new RenewJob($o)); } }',
            'app/Contexts/X/UndeclaredReceiver.php' => '<?php use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher; $this->dispatcher->dispatch(new RenewJob($o));',
            'app/Contexts/X/Helper.php' => '<?php dispatch(new RenewJob($o));',
            'app/Contexts/X/HelperSync.php' => '<?php dispatch_sync(new RenewJob($o));',
            'app/Contexts/X/RawBus.php' => '<?php use Illuminate\Contracts\Bus\Dispatcher; use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher; $this->bus->dispatch(new RenewJob($o));',
            'app/Contexts/X/UnknownDispatcher.php' => '<?php $this->somebody->dispatch(new RenewJob($o));',
            'app/Contexts/X/Facade.php' => '<?php Bus::dispatch(new RenewJob($o));',
            // Principal's review of task 8: naming the approved class somewhere in the file is NOT enough
            'app/Contexts/X/NamesItButCallsAnother.php' => '<?php use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher;'
                . ' final class N { public function __construct(private OrganisationJobDispatcher $dispatcher, private Mailer $other) {}'
                . ' public function go() { $this->other->dispatch(new RenewJob($o)); } }',
            'app/Contexts/X/TypedProperty.php' => '<?php final class T { public function __construct(private readonly OrganisationJobDispatcher $jobs) {}'
                . ' public function go() { $this->jobs->dispatch(new RenewJob($o)); } }',
            'app/Contexts/X/TypedLocal.php' => '<?php function go(OrganisationJobDispatcher $d) { $d->dispatch(new RenewJob($o)); }',
            'app/Contexts/X/FromContainer.php' => '<?php app(OrganisationJobDispatcher::class)->dispatch(new RenewJob($o));',
            'app/Contexts/X/OneGoodOneBad.php' => '<?php final class M { public function __construct(private OrganisationJobDispatcher $jobs, private $bus) {}'
                . ' public function go() { $this->jobs->dispatch(new RenewJob($o)); $this->bus->dispatch(new RenewJob($o)); } }',
        ];
        $this->assertSame([
            'app/Contexts/X/UndeclaredReceiver.php',
            'app/Contexts/X/Helper.php', 'app/Contexts/X/HelperSync.php', 'app/Contexts/X/RawBus.php', 'app/Contexts/X/UnknownDispatcher.php',
            'app/Contexts/X/Facade.php', 'app/Contexts/X/NamesItButCallsAnother.php', 'app/Contexts/X/OneGoodOneBad.php',
        ], self::directDispatches([...$routes, 'app/Contexts/X/RenewJob.php' => '<?php class RenewJob extends OrganisationJob {}']));
        $this->assertSame(['app/Contexts/Newsletter/Infrastructure/Send.php'], self::organisationConnectionNamedOutsideTheQueue($planted));
        $this->assertSame(['app/Jobs/Rogue.php'], self::queuedWithoutTheMarker($planted));
    }
}
