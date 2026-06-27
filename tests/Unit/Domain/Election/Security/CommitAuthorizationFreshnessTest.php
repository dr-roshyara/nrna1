<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\CommitAuthorizationFreshness;
use PHPUnit\Framework\TestCase;

class CommitAuthorizationFreshnessTest extends TestCase
{
    public function test_fresh_token_within_window(): void
    {
        $now = new \DateTimeImmutable();
        $freshness = new CommitAuthorizationFreshness(
            commitTokenHash: 'hash123',
            viewTokenHash: 'view_hash123',
            issuedAt: $now,
            isUsed: false,
            usedAt: null,
        );

        $this->assertTrue($freshness->isFresh(3600));
    }

    public function test_stale_token_outside_window(): void
    {
        $now = new \DateTimeImmutable();
        $issuedAt = $now->modify('-2 hours');
        $freshness = new CommitAuthorizationFreshness(
            commitTokenHash: 'hash123',
            viewTokenHash: 'view_hash123',
            issuedAt: $issuedAt,
            isUsed: false,
            usedAt: null,
        );

        $this->assertFalse($freshness->isFresh(3600));
    }

    public function test_used_token_is_not_fresh(): void
    {
        $now = new \DateTimeImmutable();
        $freshness = new CommitAuthorizationFreshness(
            commitTokenHash: 'hash123',
            viewTokenHash: 'view_hash123',
            issuedAt: $now,
            isUsed: true,
            usedAt: $now,
        );

        $this->assertFalse($freshness->isFresh(3600));
    }

    public function test_is_replay_when_already_used(): void
    {
        $now = new \DateTimeImmutable();
        $freshness = new CommitAuthorizationFreshness(
            commitTokenHash: 'hash123',
            viewTokenHash: 'view_hash123',
            issuedAt: $now,
            isUsed: true,
            usedAt: $now,
        );

        $this->assertTrue($freshness->isReplay());
    }

    public function test_is_not_replay_when_unused(): void
    {
        $now = new \DateTimeImmutable();
        $freshness = new CommitAuthorizationFreshness(
            commitTokenHash: 'hash123',
            viewTokenHash: 'view_hash123',
            issuedAt: $now,
            isUsed: false,
            usedAt: null,
        );

        $this->assertFalse($freshness->isReplay());
    }
}
