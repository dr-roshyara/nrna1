<?php

namespace Tests\Feature\Security;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * PBDIGIT-305: the unauthenticated mail diagnostic routes must not exist.
 * GET /test/email sent mail and returned the mail configuration (SMTP username included);
 * GET /test-email sent a verification-code notification to the first user in the database.
 */
final class PublicMailTestRoutesRemovedTest extends TestCase
{
    /** @return array<string, array{string}> */
    public static function removedPaths(): array
    {
        return [
            'mail config + send' => ['/test/email'],
            'verification code to first user' => ['/test-email'],
        ];
    }

    /** @dataProvider removedPaths */
    public function test_the_public_mail_test_route_is_gone_and_sends_nothing(string $path): void
    {
        Mail::fake();
        Notification::fake();

        $this->get($path)->assertNotFound();

        Mail::assertNothingSent();
        Notification::assertNothingSent();
    }

    public function test_the_named_route_is_no_longer_registered(): void
    {
        $this->assertFalse(Route::has('test.email'));
    }
}
