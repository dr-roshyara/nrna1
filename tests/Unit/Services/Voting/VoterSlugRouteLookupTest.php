<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Voting;

use App\Models\VoterSlug;
use App\Services\Voting\VoterSlugRouteLookup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-274 S1 (plan section 7, L-13): the {vslug} route lookup runs WITHOUT model events, so the VoterSlug
 * `retrieved` hook cannot write expiry state before ownership is checked; only the 'tenant' scope is removed
 * (SoftDeletes stays); the binding no longer evaluates expiry. Database-free: models are hydrated in memory
 * and SQL is only compiled, never executed; a final assertion proves no query ran.
 */
final class VoterSlugRouteLookupTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    private const ACTIVE_EXPIRED_ROW = [
        'id' => 'cccccccc-cccc-4ccc-8ccc-cccccccccccc', 'slug' => 'RAWSLUG', 'user_id' => 'u1', 'election_id' => 'e1',
        'organisation_id' => 'o1', 'is_active' => true, 'status' => 'active', 'has_voted' => false,
        'created_at' => '2026-10-11 10:00:00', 'expires_at' => '2026-10-11 10:30:00',
    ];

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-10-11 12:00:00', 'UTC'));
        DB::listen(function () {
            $this->queries++;
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /** A lookup whose fetch hydrates in memory (fires `retrieved` if events are on) instead of querying. */
    private function inMemoryLookup(array $row, bool $throw = false): VoterSlugRouteLookup
    {
        return new class ($row, $throw) extends VoterSlugRouteLookup {
            public function __construct(private array $row, private bool $throw)
            {
            }

            protected function fetch(string $modelClass, string $slug): ?Model
            {
                if ($this->throw) {
                    throw new \RuntimeException('lookup failed');
                }

                return $modelClass::hydrate([$this->row])->first();
            }
        };
    }

    public function test_the_lookup_fires_no_model_events_so_the_retrieved_hook_cannot_write(): void
    {
        $retrieved = 0;
        Event::listen('eloquent.retrieved: ' . VoterSlug::class, function () use (&$retrieved) {
            $retrieved++;
        });

        $slug = $this->inMemoryLookup(self::ACTIVE_EXPIRED_ROW)->find(VoterSlug::class, 'RAWSLUG');

        $this->assertSame(0, $retrieved, 'no retrieved event during the lookup');
        $this->assertTrue((bool) $slug->is_active, 'the expiry hook did not mark the slug inactive');
        $this->assertSame('active', $slug->status, 'the expiry hook did not rewrite the status');
        $this->assertSame(0, $this->queries, 'no query (the hook would have issued an UPDATE)');
    }

    public function test_the_dispatcher_is_restored_after_success_and_after_an_exception(): void
    {
        $dispatcher = Model::getEventDispatcher();

        $this->inMemoryLookup(self::ACTIVE_EXPIRED_ROW)->find(VoterSlug::class, 'RAWSLUG');
        $this->assertSame($dispatcher, Model::getEventDispatcher(), 'restored after success');

        try {
            $this->inMemoryLookup(self::ACTIVE_EXPIRED_ROW, throw: true)->find(VoterSlug::class, 'RAWSLUG');
            $this->fail('expected the lookup exception');
        } catch (\RuntimeException) {
        }
        $this->assertSame($dispatcher, Model::getEventDispatcher(), 'restored after an exception');
    }

    public function test_unrelated_model_events_fire_normally_after_the_lookup(): void
    {
        $retrieved = 0;
        Event::listen('eloquent.retrieved: ' . VoterSlug::class, function () use (&$retrieved) {
            $retrieved++;
        });

        $this->inMemoryLookup(self::ACTIVE_EXPIRED_ROW)->find(VoterSlug::class, 'RAWSLUG');
        // a later, unrelated hydration (not expired, so the model's own hook does nothing) fires its event
        VoterSlug::hydrate([array_merge(self::ACTIVE_EXPIRED_ROW, ['expires_at' => '2026-10-11 13:00:00'])]);

        $this->assertSame(1, $retrieved);
    }

    public function test_only_the_tenant_scope_is_removed_and_soft_deletes_stay_applied(): void
    {
        $lookup = new class extends VoterSlugRouteLookup {
            public function exposedQuery(string $modelClass, string $slug): Builder
            {
                return $this->query($modelClass, $slug);
            }
        };

        $builder = $lookup->exposedQuery(VoterSlug::class, 'RAWSLUG');
        $sql = $builder->toSql();

        $this->assertSame(['tenant'], $builder->removedScopes());
        $this->assertStringContainsString('"deleted_at" is null', $sql, 'SoftDeletes scope still applied');
        $this->assertStringContainsString('"slug" = ?', $sql);
        $this->assertStringNotContainsString('organisation_id', $sql, 'tenant scope removed');
        $this->assertSame(0, $this->queries, 'compiled, not executed');
    }

    public function test_the_route_binding_uses_the_lookup_and_no_longer_evaluates_expiry(): void
    {
        $expired = VoterSlug::withoutEvents(fn () => VoterSlug::hydrate([self::ACTIVE_EXPIRED_ROW])->first());
        $this->app->instance(VoterSlugRouteLookup::class, new class ($expired) extends VoterSlugRouteLookup {
            public array $asked = [];

            public function __construct(private ?Model $result)
            {
            }

            public function find(string $modelClass, string $slug): ?Model
            {
                $this->asked[] = [$modelClass, $slug];

                return $this->result;
            }
        });

        $resolved = $this->app['router']->getBindingCallback('vslug')('RAWSLUG', null);

        $this->assertSame($expired, $resolved, 'an expired current slug is returned (expiry is the guard\'s job), not a 410');
        $this->assertSame([[VoterSlug::class, 'RAWSLUG']], $this->app->make(VoterSlugRouteLookup::class)->asked);
    }

    public function test_the_route_binding_answers_404_when_the_lookup_finds_nothing(): void
    {
        $this->app->instance(VoterSlugRouteLookup::class, new class extends VoterSlugRouteLookup {
            public function find(string $modelClass, string $slug): ?Model
            {
                return null; // unknown, replaced or soft-deleted: indistinguishable
            }
        });

        $this->expectException(NotFoundHttpException::class);
        $this->app['router']->getBindingCallback('vslug')('UNKNOWN', null);
    }
}
