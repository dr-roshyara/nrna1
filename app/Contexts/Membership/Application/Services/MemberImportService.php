<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Membership\Application\Commands\MemberImportCommand;
use App\Contexts\Membership\Application\DTOs\MemberImportResult;
use App\Contexts\Membership\Domain\Member\Member;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Application\Interfaces\TenantUserProvisioningInterface;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxWriterInterface;
use App\Contexts\Membership\Domain\Policies\MembershipTypePolicyInterface;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Member\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\Member\ValueObjects\MemberResidenceGeoIdentity;

final class MemberImportService
{
    public function __construct(
        private MemberRepositoryInterface $memberRepository,
        private TenantUserProvisioningInterface $userProvisioning,
        private OutboxWriterInterface $outboxWriter,
        private MembershipTypePolicyInterface $membershipTypePolicy,
    ) {}

    public function import(
        TenantId $tenantId,
        array $commands,
        MembershipTypeId $defaultTypeId,
    ): MemberImportResult {
        $imported = $skipped = $failed = 0;
        $errors = [];

        foreach ($commands as $i => $command) {
            try {
                // 1. Email format validation
                if (!filter_var($command->email, FILTER_VALIDATE_EMAIL)) {
                    throw new \InvalidArgumentException('Invalid email format');
                }

                // 2. Idempotency — skip, not fail
                if ($this->memberRepository->existsByEmailForTenant($tenantId, $command->email)) {
                    $skipped++;
                    continue;
                }

                // 3. Membership type validation
                $membershipTypeId = $command->membershipTypeId
                    ? MembershipTypeId::fromString($command->membershipTypeId)
                    : $defaultTypeId;

                $this->membershipTypePolicy->assertActive($membershipTypeId, $tenantId);

                // ========== STEP 1: Identity Provision (separate) ==========
                $tenantUserId = $this->userProvisioning->createForCsvImport(
                    $tenantId,
                    $command->email,
                    trim("{$command->firstName} {$command->lastName}"),
                );

                // ========== STEP 2: Domain Create (pure aggregate) ==========
                $geoIdentity = $command->geoUnitId
                    ? new MemberResidenceGeoIdentity((int) $command->geoUnitId)
                    : null;

                $member = Member::register(
                    $tenantId,
                    PersonalInfo::create(
                        trim("{$command->firstName} {$command->lastName}"),
                        $command->email
                    ),
                    $membershipTypeId,
                    $geoIdentity,
                );

                // ========== STEP 3: Persist (aggregate only, no context) ==========
                $this->memberRepository->save($member, $tenantId, $tenantUserId->value());

                // ========== STEP 4: Event Translation + Storage ==========
                foreach ($member->pullEvents() as $domainEvent) {
                    $this->outboxWriter->store($domainEvent, $tenantId->value());
                }

                $imported++;

            } catch (\InvalidArgumentException $e) {
                $failed++;
                $errors[] = ['row' => $i + 1, 'email' => $command->email ?? '', 'reason' => $e->getMessage()];
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = ['row' => $i + 1, 'email' => $command->email ?? '', 'reason' => $e->getMessage()];
            }
        }

        return new MemberImportResult($imported, $skipped, $failed, $errors);
    }
}
