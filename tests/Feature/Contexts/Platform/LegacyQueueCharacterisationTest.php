<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Fixtures\Platform\Queue\LegacyStyleFailingJob;
use Tests\Fixtures\Platform\Queue\LegacyStyleJob;
use Tests\Fixtures\Platform\Queue\LegacyStyleQueuedMail;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 7, T-19 (tactical design §3.10 Q-1; §3.10.1): LEGACY QUEUE CHARACTERISATION, behaviour not
 * configuration. Written and first run against the UNTOUCHED queue configuration (step 1), then run again, UNCHANGED,
 * after the `organisation` connection is added (step 4):
 * - a legacy-shaped queued job and a legacy-shaped queued mail execute INLINE during dispatch (the side effect is
 *   observable when dispatch returns);
 * - a failing legacy-shaped job throws to its caller and writes NO `failed_jobs` row.
 * The fixtures carry no connection of their own, exactly like the 25 legacy ShouldQueue classes (none sets one).
 */
final class LegacyQueueCharacterisationTest extends MembershipPostgresTestCase
{
    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        LegacyStyleJob::$ran = [];
    }

    public function test_a_legacy_job_runs_inline_during_dispatch(): void
    {
        LegacyStyleJob::dispatch('first');

        $this->assertSame(['first'], LegacyStyleJob::$ran, 'already ran when dispatch returned');
    }

    public function test_a_legacy_queued_mail_is_sent_inline(): void
    {
        $transport = Mail::mailer()->getSymfonyTransport();
        $before = count($transport->messages());

        Mail::to('t19@example.org')->send(new LegacyStyleQueuedMail());

        $this->assertCount($before + 1, $transport->messages(), 'already sent when send returned');
    }

    public function test_a_failing_legacy_job_throws_to_its_caller_and_writes_no_failed_job(): void
    {
        $failedBefore = (int) DB::table('failed_jobs')->count();
        try {
            LegacyStyleFailingJob::dispatch();
            $this->fail('the failure did not reach the caller');
        } catch (\RuntimeException $e) {
            $this->assertSame('legacy-style job failed', $e->getMessage());
        }
        $this->assertSame($failedBefore, (int) DB::table('failed_jobs')->count(), 'no failed_jobs row');
    }
}
