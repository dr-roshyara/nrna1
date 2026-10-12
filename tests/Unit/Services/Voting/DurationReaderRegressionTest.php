<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Voting;

use App\Exceptions\Voting\VoterSlugLifetimeMisconfigured;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\Demo\DemoCodeController;
use App\Http\Controllers\Demo\DemoVoteController;
use App\Http\Controllers\VoteController;
use App\Models\Demo\DemoCode as DemoNamespaceDemoCode;
use App\Models\DemoCode;
use App\Models\DemoVoterSlug;
use App\Models\VoterSlug;
use App\Traits\Voting\CodeVerificationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Tests\CreatesApplication;

/**
 * PBDIGIT-274 S6 (database-free; plan section 6, O-10 = B): every reader of voting.time_in_minutes uses the one strict
 * rule (VoterSlugLifetime::fromConfig) and fails closed on an invalid value.
 *
 * - test_valid_value_unchanged_*: characterisation (A3). Written and executed against the code BEFORE S6, then
 *   re-executed after S6; a valid configured value must keep its meaning in R4 … R8.
 * - test_invalid_value_fails_closed_*: S6's own RED → GREEN.
 * - R3: the model default used an undefined key (always 30); it now follows the configured value (F1, one source).
 *
 * The configured value is 20 (not the default 30) so that a reader ignoring the configuration is visible. Elapsed
 * time is 25 minutes: expired under 20, not expired under 30. `pgsql` points at a non-existent socket.
 */
final class DurationReaderRegressionTest extends BaseTestCase
{
    use CreatesApplication;

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => '/nonexistent-s6-test-socket-dir',
            'database.connections.pgsql.url' => null,
            'voting.time_in_minutes' => 20,
        ]);
        DB::purge('pgsql');
        DB::listen(function () {
            $this->queries++;
        });
    }

    protected function tearDown(): void
    {
        $this->assertSame(0, $this->queries, 'database-free');
        parent::tearDown();
    }

    /** A code row whose own column is empty, so the reader falls back to the configuration. */
    private function code(array $attributes = []): object
    {
        return (object) ($attributes + [
            'voting_time_in_minutes' => null,
            'can_vote_now' => 1,
            'has_voted' => 0,
            'is_code_to_open_voting_form_usable' => 0,
            'vote_submitted' => 1,
            'code_to_open_voting_form_sent_at' => now()->subMinutes(25),
            'code_to_open_voting_form_used_at' => now()->subMinutes(25),
        ]);
    }

    private function model(string $class, array $attributes = []): Model
    {
        $model = (new \ReflectionClass($class))->newInstanceWithoutConstructor();
        $model->setRawAttributes($attributes + [
            'voting_time_in_minutes' => null,
            'code_to_open_voting_form_sent_at' => now()->subMinutes(25)->toDateTimeString(),
        ]);

        return $model;
    }

    private function invoke(object $target, string $method, array $args): mixed
    {
        return (new \ReflectionMethod($target, $method))->invokeArgs($target, $args);
    }

    private function property(object $target, string $name): mixed
    {
        return (new \ReflectionProperty($target, $name))->getValue($target);
    }

    private function trait(): object
    {
        return new class {
            use CodeVerificationTrait;

            public function expired(object $code): bool
            {
                return $this->hasVotingWindowExpired($code);
            }
        };
    }

    private function fireCreating(Model $slug): Model
    {
        $slug->organisation_id = 'o1'; // keeps BelongsToTenant's creating hook away from the database
        (new \ReflectionMethod($slug, 'fireModelEvent'))->invoke($slug, 'creating', false);

        return $slug;
    }

    private function assertFailsClosed(callable $reader): void
    {
        try {
            $reader();
            $this->fail('expected VoterSlugLifetimeMisconfigured');
        } catch (VoterSlugLifetimeMisconfigured $e) {
            $this->assertSame(503, $e->getHttpCode());
            $this->assertStringNotContainsString('20min', json_encode($e->getContext()), 'the value is never logged');
        }
    }

    // ── characterisation: a valid value keeps its meaning (A3) ─────────────────────────────────────────────

    public function test_valid_value_unchanged_R4_real_code_window(): void
    {
        $this->assertSame(20, $this->property(new CodeController(), 'votingTimeInMinutes'));
    }

    public function test_valid_value_unchanged_R5_demo_code_window(): void
    {
        $this->assertSame(20, $this->property(new DemoCodeController(), 'votingTimeInMinutes'));
    }

    public function test_valid_value_unchanged_R7_code_verification(): void
    {
        $this->assertTrue($this->trait()->expired($this->code()), '25 minutes > 20');
        $this->assertFalse($this->trait()->expired($this->code(['voting_time_in_minutes' => 40])), 'the code\'s own column wins');
        config(['voting.time_in_minutes' => 30]);
        $this->assertFalse($this->trait()->expired($this->code()), '25 minutes <= 30');
    }

    public function test_valid_value_unchanged_R8_real_submission_window(): void
    {
        $controller = new VoteController();

        $this->assertArrayHasKey('expired', $this->invoke($controller, 'validateVotingEligibility', [null, $this->code()]));
        $code = $this->code(); // send_second_voting_code takes the code by reference
        $this->assertSame('Voting window expired', $this->invoke($controller, 'send_second_voting_code', [&$code, null])['error']);
        $user = (object) ['id' => 'u1', 'name' => 'n', 'region' => null];
        $this->assertEquals(20, $this->invoke($controller, 'prepareSessionData', [[], $user, 25, $this->code()])['code_expires_in']);

        config(['voting.time_in_minutes' => 30]);
        $this->assertArrayNotHasKey('expired', $this->invoke($controller, 'validateVotingEligibility', [null, $this->code()]));
    }

    public function test_valid_value_unchanged_R8_demo_submission_window(): void
    {
        $controller = app(DemoVoteController::class);

        $this->assertArrayHasKey('expired', $this->invoke($controller, 'validateVotingEligibility', [null, $this->code()]));
        config(['voting.time_in_minutes' => 30]);
        $this->assertArrayNotHasKey('expired', $this->invoke($controller, 'validateVotingEligibility', [null, $this->code()]));
    }

    public function test_valid_value_unchanged_R8_demo_code_models(): void
    {
        $this->assertTrue($this->model(DemoCode::class)->isExpired());
        $this->assertTrue($this->model(DemoNamespaceDemoCode::class)->isExpired());
        config(['voting.time_in_minutes' => 30]);
        $this->assertFalse($this->model(DemoCode::class)->isExpired());
        $this->assertFalse($this->model(DemoNamespaceDemoCode::class)->isExpired());
    }

    // ── RED → GREEN: an invalid value fails closed in every reader ─────────────────────────────────────────

    public function test_invalid_value_fails_closed_R4_real_code_window(): void
    {
        config(['voting.time_in_minutes' => '20min']);
        $this->assertFailsClosed(fn () => new CodeController());
    }

    public function test_invalid_value_fails_closed_R5_demo_code_window(): void
    {
        config(['voting.time_in_minutes' => '20min']);
        $this->assertFailsClosed(fn () => new DemoCodeController());
    }

    public function test_invalid_value_fails_closed_R7_code_verification(): void
    {
        config(['voting.time_in_minutes' => '20min']);
        $this->assertFailsClosed(fn () => $this->trait()->expired($this->code()));
    }

    public function test_invalid_value_fails_closed_R8_real_submission_window(): void
    {
        config(['voting.time_in_minutes' => '20min']);
        $controller = new VoteController();
        $user = (object) ['id' => 'u1', 'name' => 'n', 'region' => null];

        $this->assertFailsClosed(fn () => $this->invoke($controller, 'validateVotingEligibility', [null, $this->code()]));
        $code = $this->code();
        $this->assertFailsClosed(function () use ($controller, &$code) {
            $this->invoke($controller, 'send_second_voting_code', [&$code, null]);
        });
        $this->assertFailsClosed(fn () => $this->invoke($controller, 'prepareSessionData', [[], $user, 25, $this->code()]));
    }

    public function test_invalid_value_fails_closed_R8_demo_submission_window(): void
    {
        config(['voting.time_in_minutes' => '20min']);
        $controller = app(DemoVoteController::class);

        $this->assertFailsClosed(fn () => $this->invoke($controller, 'validateVotingEligibility', [null, $this->code()]));
    }

    public function test_invalid_value_fails_closed_R8_demo_code_models(): void
    {
        config(['voting.time_in_minutes' => 0]);
        $this->assertFailsClosed(fn () => $this->model(DemoCode::class)->isExpired());
        $this->assertFailsClosed(fn () => $this->model(DemoNamespaceDemoCode::class)->isExpired());
    }

    // ── R3: the model default follows the configured value ─────────────────────────────────────────────────

    public function test_R3_default_expiry_follows_the_configured_value(): void
    {
        $this->freezeSecond();

        $this->assertTrue(now()->addMinutes(20)->equalTo($this->fireCreating(new VoterSlug())->expires_at));
        $this->assertTrue(now()->addMinutes(20)->equalTo($this->fireCreating(new DemoVoterSlug())->expires_at));
    }

    public function test_invalid_value_fails_closed_R3_default_expiry(): void
    {
        config(['voting.time_in_minutes' => '20min']);
        $this->assertFailsClosed(fn () => $this->fireCreating(new VoterSlug()));
        $this->assertFailsClosed(fn () => $this->fireCreating(new DemoVoterSlug()));
    }

    // ── the configuration keeps the raw value, so the strict rule sees it ─────────────────────────────────

    public function test_the_configuration_no_longer_truncates_the_environment_value(): void
    {
        $restore = getenv('VOTING_TIME_IN_MINUTES');
        try {
            foreach (['20min' => '20min', '5.9' => '5.9', '25' => '25'] as $env => $expected) {
                putenv("VOTING_TIME_IN_MINUTES={$env}");
                $_ENV['VOTING_TIME_IN_MINUTES'] = $_SERVER['VOTING_TIME_IN_MINUTES'] = $env;
                $this->assertSame($expected, (require base_path('config/voting.php'))['time_in_minutes']);
            }

            putenv('VOTING_TIME_IN_MINUTES');
            unset($_ENV['VOTING_TIME_IN_MINUTES'], $_SERVER['VOTING_TIME_IN_MINUTES']);
            $this->assertSame(30, (require base_path('config/voting.php'))['time_in_minutes'], 'missing → documented default (O-9 = a)');
        } finally {
            unset($_ENV['VOTING_TIME_IN_MINUTES'], $_SERVER['VOTING_TIME_IN_MINUTES']);
            putenv($restore === false ? 'VOTING_TIME_IN_MINUTES' : "VOTING_TIME_IN_MINUTES={$restore}");
            if ($restore !== false) {
                $_ENV['VOTING_TIME_IN_MINUTES'] = $_SERVER['VOTING_TIME_IN_MINUTES'] = $restore;
            }
        }
    }
}
