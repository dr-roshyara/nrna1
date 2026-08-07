<?php

namespace Tests\Feature\Logging;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Infrastructure regression — logging configuration. (No product story;
 * discovered while verifying an unrelated migration.)
 *
 * Invariant: a logging channel referenced by configuration must be defined.
 *
 * config/login-routing.php names an analytics channel (default 'login') and
 * UserOrganisationObserver logs to it on every role change — but the channel
 * was missing from config/logging.php, so every such log fell back to the
 * emergency logger ("Log [login] is not defined").
 */
class LoginAnalyticsChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_configured_analytics_channel_is_defined(): void
    {
        $channel = config('login-routing.analytics.channel', 'login');

        $this->assertArrayHasKey(
            $channel,
            config('logging.channels'),
            "Channel [{$channel}] is named by login-routing config but not defined in config/logging.php."
        );
    }

    public function test_a_role_change_logs_through_the_configured_channel(): void
    {
        $organisation = Organisation::factory()->create();
        $user = User::factory()->create();

        $channel = config('login-routing.analytics.channel', 'login');
        $tolerantLogger = \Mockery::mock(\Psr\Log\LoggerInterface::class)->shouldIgnoreMissing();

        // The assertion: the observer must ask for the configured channel.
        Log::shouldReceive('channel')->with($channel)->once()->andReturn($tolerantLogger);
        // Unrelated logging elsewhere in the write path is tolerated, not asserted.
        Log::shouldReceive('channel')->withAnyArgs()->zeroOrMoreTimes()->andReturn($tolerantLogger);
        Log::shouldIgnoreMissing();

        UserOrganisationRole::create([
            'organisation_id' => $organisation->id,
            'user_id'         => $user->id,
            'role'            => 'member',
        ]);
    }
}
