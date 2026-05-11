<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Replay;

use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalScope;
use App\Contexts\Membership\Domain\Committee\Constitutional\Replay\ScopeAwareReplayValidator;
use App\Contexts\Membership\Domain\Committee\Constitutional\Replay\ScopeMismatchException;
use PHPUnit\Framework\TestCase;

class ScopeAwareReplayValidatorTest extends TestCase
{
    /**
     * @test
     * National snapshot validates under national replay scope
     */
    public function test_national_snapshot_validates_under_national_scope(): void
    {
        $validator = new ScopeAwareReplayValidator();

        // Should not throw
        $validator->validate(
            snapshotScope: ConstitutionalScope::NATIONAL,
            replayScope: ConstitutionalScope::NATIONAL
        );

        $this->assertTrue(true);
    }

    /**
     * @test
     * National snapshot throws when replayed under regional scope
     */
    public function test_national_snapshot_throws_when_replayed_under_regional_scope(): void
    {
        $validator = new ScopeAwareReplayValidator();

        $this->expectException(ScopeMismatchException::class);

        $validator->validate(
            snapshotScope: ConstitutionalScope::NATIONAL,
            replayScope: ConstitutionalScope::REGIONAL
        );
    }

    /**
     * @test
     * Null scope snapshot validates under any scope
     */
    public function test_null_scope_snapshot_validates_under_any_scope(): void
    {
        $validator = new ScopeAwareReplayValidator();

        // Both should not throw
        $validator->validate(snapshotScope: null, replayScope: ConstitutionalScope::NATIONAL);
        $validator->validate(snapshotScope: null, replayScope: ConstitutionalScope::REGIONAL);

        $this->assertTrue(true);
    }

    /**
     * @test
     * Scope mismatch exception contains both scopes
     */
    public function test_scope_mismatch_exception_message_contains_both_scopes(): void
    {
        $validator = new ScopeAwareReplayValidator();

        try {
            $validator->validate(
                snapshotScope: ConstitutionalScope::REGIONAL,
                replayScope: ConstitutionalScope::NATIONAL
            );
            $this->fail('Expected ScopeMismatchException');
        } catch (ScopeMismatchException $e) {
            $message = $e->getMessage();
            $this->assertStringContainsString('regional', strtolower($message));
            $this->assertStringContainsString('national', strtolower($message));
        }
    }
}
