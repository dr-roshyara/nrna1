<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Voting;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use LogicException;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-274 S5 (database-free): the legacy VoterSlugService no longer handles real elections and never extends
 * a link in place. `pgsql` points at a non-existent socket; every test asserts that no query ran.
 */
final class VoterSlugServiceFixedLifetimeTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => '/nonexistent-s5-test-socket-dir',
            'database.connections.pgsql.url' => null,
        ]);
        DB::purge('pgsql');
        DB::listen(function () {
            $this->queries++;
        });
    }

    private function user(): User
    {
        $user = (new \ReflectionClass(User::class))->newInstanceWithoutConstructor();
        $user->setRawAttributes(['id' => 'uuuuuuuu-uuuu-4uuu-8uuu-uuuuuuuuuuuu']);

        return $user;
    }

    public function test_get_or_create_slug_refuses_a_real_election_before_any_query(): void
    {
        $election = (new Election())->forceFill(['id' => 'eeeeeeee-eeee-4eee-8eee-eeeeeeeeeeee', 'type' => 'real']);

        try {
            app(VoterSlugService::class)->getOrCreateSlug($this->user(), $election);
            $this->fail('expected a LogicException');
        } catch (LogicException $e) {
            $this->assertStringContainsString('VoterSlugIssuer', $e->getMessage());
        }
        $this->assertSame(0, $this->queries, 'no in-place rewrite, no cleanup, no hard delete for real elections');
    }

    public function test_extend_slug_expiry_never_extends(): void
    {
        $expires = now()->addMinutes(5)->startOfSecond();
        $slug = (new VoterSlug())->forceFill(['id' => 's1', 'is_active' => true, 'expires_at' => $expires]);
        $slug->exists = true;   // a persisted row: an update would really write (and hit the dead socket)
        $slug->syncOriginal();

        $this->assertFalse(app(VoterSlugService::class)->extendSlugExpiry($slug));
        $this->assertEquals($expires, $slug->expires_at);
        $this->assertSame(0, $this->queries, 'nothing written');
    }
}
