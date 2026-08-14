<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SendVoterInvitation;
use App\Models\VoterInvitation;
use Tests\TestCase;

/**
 * KOS-OQ-001 — voter-invitation delivery retry count is configuration-backed.
 *
 * Boundary: docs/publicdigit/reviews/2026-08-14-KOS-OQ-001-implementation-boundary-proposal.md
 * (Revision 2, §4) · grant G-KOS-OQ-001-IMPL.
 *
 * Observable: the job's OWN public method `tries()` — not the queue, not a
 * worker, not a payload, not a mocked framework class. Hermetic: the
 * VoterInvitation is constructed, never persisted, so no database is used.
 *
 * Framework contract this pins (evidence: Illuminate\Queue\Queue::getJobTries(),
 * `$job->tries ?? $job->tries()`): the PROPERTY wins whenever it is set, so a
 * `tries()` method alongside a `public $tries` would never be called and the
 * configuration parameter would be inert. T4 guards exactly that.
 */
class SendVoterInvitationRetryConfigTest extends TestCase
{
    private function job(): SendVoterInvitation
    {
        return new SendVoterInvitation(new VoterInvitation());
    }

    /** T1 — with the shipped configuration, existing behaviour is preserved. */
    public function test_t1_default_configuration_preserves_three_attempts(): void
    {
        $this->assertSame(3, $this->job()->tries(),
            'T1: the shipped default must remain 3 delivery attempts');
    }

    /** T2 — an explicitly configured value is genuinely consumed. */
    public function test_t2_configured_value_is_consumed(): void
    {
        config(['election.invitation_send_attempts' => 5]);

        $this->assertSame(5, $this->job()->tries(),
            'T2: the configuration key must be read, not merely exposed');
    }

    /** T3 — the fallback lives in the code path, not only in the config file. */
    public function test_t3_absent_configuration_key_falls_back_to_three(): void
    {
        $election = config('election');
        unset($election['invitation_send_attempts']);
        config(['election' => $election]);

        $this->assertSame(3, $this->job()->tries(),
            'T3: with the key absent entirely, the job must still report 3 and not error');
    }

    /** T4 — shadowing guard: a `tries` property would silence the method. */
    public function test_t4_no_tries_property_shadows_the_method(): void
    {
        $this->assertFalse(
            property_exists(SendVoterInvitation::class, 'tries'),
            'T4: a `tries` property takes precedence in Queue::getJobTries() and would '
            . 'make the configured retry count inert — the property must not exist'
        );
    }
}
