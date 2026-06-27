<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * Base class for constitutional (pure domain) tests.
 *
 * These tests verify domain behavior without database access, HTTP context, or
 * Laravel runtime dependencies. They execute in milliseconds and can run anywhere.
 *
 * Constitutional tests are the source of truth for governance semantics.
 */
abstract class PureDomainTestCase extends TestCase
{
    protected function createTenantId(): TenantId
    {
        return TenantId::fromString($this->createUuid());
    }

    protected function createMemberId(): MemberId
    {
        return MemberId::fromString($this->createUuid());
    }

    protected function createCommitteeId(): CommitteeId
    {
        return CommitteeId::fromString($this->createAlphanumeric(26));
    }

    protected function createUuid(): string
    {
        return (string) \Illuminate\Support\Str::uuid();
    }

    protected function createAlphanumeric(int $length): string
    {
        return (string) \Illuminate\Support\Str::random($length);
    }
}
