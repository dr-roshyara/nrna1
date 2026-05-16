<?php

declare(strict_types=1);

namespace Tests\Unit\Membership\Application;

use App\Contexts\Membership\Application\Commands\MemberImportCommand;
use App\Contexts\Membership\Application\Services\MemberImportService;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Application\Interfaces\TenantUserProvisioningInterface;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxWriterInterface;
use App\Contexts\Membership\Domain\Policies\MembershipTypePolicyInterface;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Membership\Domain\Events\MemberRegistered;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class MemberImportServiceTest extends TestCase
{
    private MemberRepositoryInterface & MockObject $memberRepository;
    private TenantUserProvisioningInterface & MockObject $userProvisioning;
    private OutboxWriterInterface & MockObject $outboxWriter;
    private MembershipTypePolicyInterface & MockObject $membershipTypePolicy;
    private MemberImportService $service;
    private TenantId $tenantId;
    private MembershipTypeId $defaultTypeId;

    protected function setUp(): void
    {
        $this->memberRepository = $this->createMock(MemberRepositoryInterface::class);
        $this->userProvisioning = $this->createMock(TenantUserProvisioningInterface::class);
        $this->outboxWriter = $this->createMock(OutboxWriterInterface::class);
        $this->membershipTypePolicy = $this->createMock(MembershipTypePolicyInterface::class);

        $this->service = new MemberImportService(
            $this->memberRepository,
            $this->userProvisioning,
            $this->outboxWriter,
            $this->membershipTypePolicy,
        );

        $this->tenantId = TenantId::fromString('550e8400-e29b-41d4-a716-446655440000');
        $this->defaultTypeId = MembershipTypeId::fromString('650e8400-e29b-41d4-a716-446655440000');
    }

    public function test_invalid_email_format_counted_as_failed_not_skipped(): void
    {
        $command = new MemberImportCommand(
            email: 'invalid-email',
            firstName: 'John',
            lastName: 'Doe',
        );

        $this->memberRepository
            ->expects($this->never())
            ->method('existsByEmailForTenant');

        $this->membershipTypePolicy
            ->expects($this->never())
            ->method('assertActive');

        $result = $this->service->import($this->tenantId, [$command], $this->defaultTypeId);

        $this->assertEquals(0, $result->imported);
        $this->assertEquals(0, $result->skipped);
        $this->assertEquals(1, $result->failed);
        $this->assertCount(1, $result->errors);
    }

    public function test_duplicate_email_is_skipped_not_failed(): void
    {
        $command = new MemberImportCommand(
            email: 'existing@example.com',
            firstName: 'Jane',
            lastName: 'Doe',
        );

        $this->memberRepository
            ->expects($this->once())
            ->method('existsByEmailForTenant')
            ->with($this->tenantId, 'existing@example.com')
            ->willReturn(true);

        $this->membershipTypePolicy
            ->expects($this->never())
            ->method('assertActive');

        $this->memberRepository
            ->expects($this->never())
            ->method('save');

        $result = $this->service->import($this->tenantId, [$command], $this->defaultTypeId);

        $this->assertEquals(0, $result->imported);
        $this->assertEquals(1, $result->skipped);
        $this->assertEquals(0, $result->failed);
        $this->assertEmpty($result->errors);
    }

    public function test_invalid_membership_type_fails_row(): void
    {
        $command = new MemberImportCommand(
            email: 'john@example.com',
            firstName: 'John',
            lastName: 'Doe',
        );

        $this->memberRepository
            ->method('existsByEmailForTenant')
            ->willReturn(false);

        $this->membershipTypePolicy
            ->expects($this->once())
            ->method('assertActive')
            ->willThrowException(new \InvalidArgumentException('Membership type is not active'));

        $result = $this->service->import($this->tenantId, [$command], $this->defaultTypeId);

        $this->assertEquals(0, $result->imported);
        $this->assertEquals(0, $result->skipped);
        $this->assertEquals(1, $result->failed);
        $this->assertStringContainsString('not active', $result->errors[0]['reason']);
    }

    public function test_missing_membership_type_id_uses_default(): void
    {
        $command = new MemberImportCommand(
            email: 'john@example.com',
            firstName: 'John',
            lastName: 'Doe',
            membershipTypeId: null,
        );

        $this->memberRepository
            ->method('existsByEmailForTenant')
            ->willReturn(false);

        $this->membershipTypePolicy
            ->expects($this->once())
            ->method('assertActive')
            ->with($this->defaultTypeId, $this->tenantId);

        $testUserId = TenantUserId::fromString('750e8400-e29b-41d4-a716-446655440000');
        $this->userProvisioning
            ->method('createForCsvImport')
            ->willReturn($testUserId);

        $this->memberRepository
            ->method('save');

        $this->outboxWriter
            ->method('store');

        $result = $this->service->import($this->tenantId, [$command], $this->defaultTypeId);

        $this->assertEquals(1, $result->imported);
    }

    public function test_membership_type_override_works(): void
    {
        $overrideTypeId = MembershipTypeId::fromString('850e8400-e29b-41d4-a716-446655440000');

        $command = new MemberImportCommand(
            email: 'john@example.com',
            firstName: 'John',
            lastName: 'Doe',
            membershipTypeId: $overrideTypeId->value(),
        );

        $this->memberRepository
            ->method('existsByEmailForTenant')
            ->willReturn(false);

        $this->membershipTypePolicy
            ->expects($this->once())
            ->method('assertActive')
            ->with($overrideTypeId, $this->tenantId);

        $testUserId = TenantUserId::fromString('750e8400-e29b-41d4-a716-446655440000');
        $this->userProvisioning
            ->method('createForCsvImport')
            ->willReturn($testUserId);

        $this->memberRepository
            ->method('save');

        $this->outboxWriter
            ->method('store');

        $result = $this->service->import($this->tenantId, [$command], $this->defaultTypeId);

        $this->assertEquals(1, $result->imported);
    }

    public function test_geo_unit_is_optional(): void
    {
        $command = new MemberImportCommand(
            email: 'john@example.com',
            firstName: 'John',
            lastName: 'Doe',
            geoUnitId: null,
        );

        $this->memberRepository
            ->method('existsByEmailForTenant')
            ->willReturn(false);

        $this->membershipTypePolicy
            ->method('assertActive');

        $testUserId = TenantUserId::fromString('750e8400-e29b-41d4-a716-446655440000');
        $this->userProvisioning
            ->method('createForCsvImport')
            ->willReturn($testUserId);

        $this->memberRepository
            ->method('save');

        $this->outboxWriter
            ->method('store');

        $result = $this->service->import($this->tenantId, [$command], $this->defaultTypeId);

        $this->assertEquals(1, $result->imported);
    }

    public function test_repository_failure_counted_as_failed(): void
    {
        $command = new MemberImportCommand(
            email: 'john@example.com',
            firstName: 'John',
            lastName: 'Doe',
        );

        $this->memberRepository
            ->method('existsByEmailForTenant')
            ->willReturn(false);

        $this->membershipTypePolicy
            ->method('assertActive');

        $testUserId = TenantUserId::fromString('750e8400-e29b-41d4-a716-446655440000');
        $this->userProvisioning
            ->method('createForCsvImport')
            ->willReturn($testUserId);

        $this->memberRepository
            ->expects($this->once())
            ->method('save')
            ->willThrowException(new \RuntimeException('Database error'));

        $result = $this->service->import($this->tenantId, [$command], $this->defaultTypeId);

        $this->assertEquals(0, $result->imported);
        $this->assertEquals(0, $result->skipped);
        $this->assertEquals(1, $result->failed);
        $this->assertStringContainsString('Database error', $result->errors[0]['reason']);
    }

    public function test_batch_with_mixed_rows_returns_correct_counts(): void
    {
        $commands = [
            new MemberImportCommand('valid1@example.com', 'John', 'Doe'),
            new MemberImportCommand('invalid-email', 'Jane', 'Doe'),
            new MemberImportCommand('existing@example.com', 'Bob', 'Smith'),
            new MemberImportCommand('valid2@example.com', 'Alice', 'Johnson'),
        ];

        $this->memberRepository
            ->method('existsByEmailForTenant')
            ->willReturnCallback(function ($tenant, $email) {
                return $email === 'existing@example.com';
            });

        $this->membershipTypePolicy
            ->method('assertActive');

        $testUserId = TenantUserId::fromString('750e8400-e29b-41d4-a716-446655440000');
        $this->userProvisioning
            ->method('createForCsvImport')
            ->willReturn($testUserId);

        $this->memberRepository
            ->method('save');

        $this->outboxWriter
            ->method('store');

        $result = $this->service->import($this->tenantId, $commands, $this->defaultTypeId);

        $this->assertEquals(2, $result->imported);
        $this->assertEquals(1, $result->skipped);
        $this->assertEquals(1, $result->failed);
    }

    public function test_valid_row_imports_member_and_records_outbox_event(): void
    {
        $command = new MemberImportCommand(
            email: 'john@example.com',
            firstName: 'John',
            lastName: 'Doe',
        );

        $this->memberRepository
            ->method('existsByEmailForTenant')
            ->with($this->tenantId, 'john@example.com')
            ->willReturn(false);

        $this->membershipTypePolicy
            ->method('assertActive')
            ->with($this->defaultTypeId, $this->tenantId);

        $testUserId = TenantUserId::fromString('750e8400-e29b-41d4-a716-446655440000');
        $this->userProvisioning
            ->method('createForCsvImport')
            ->willReturn($testUserId);

        $this->memberRepository
            ->method('save');

        $this->outboxWriter
            ->method('store');

        $result = $this->service->import($this->tenantId, [$command], $this->defaultTypeId);

        $this->assertEquals(1, $result->imported);
        $this->assertEquals(0, $result->skipped);
        $this->assertEquals(0, $result->failed);
        $this->assertEmpty($result->errors);
    }
}
