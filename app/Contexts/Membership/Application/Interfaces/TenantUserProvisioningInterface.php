<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Interfaces;

use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * TenantUserProvisioning Interface
 *
 * Cross-Context Coordination: TenantAuth ↔ Membership
 *
 * This interface enables Membership Context to request TenantUser creation
 * from TenantAuth Context without tight coupling.
 *
 * Architecture Pattern: Anti-Corruption Layer
 * - Membership Context depends on this interface (abstraction)
 * - TenantAuth Context provides implementation (concrete)
 * - No direct dependency between contexts
 *
 * Use Cases:
 * 1. CSV Import: Bulk member registration requires auto-creating digital identities
 * 2. Self-Registration: Member signs up, need to create auth identity first
 * 3. Admin-Created Members: Admin adds member, system creates login credentials
 *
 * Global Platform: Supports ANY political party worldwide
 */
interface TenantUserProvisioningInterface
{
    /**
     * Create a TenantUser for CSV import
     *
     * Business Rules:
     * - Email must be unique within tenant
     * - Generate secure random password
     * - User MUST change password on first login
     * - Send welcome email with credentials (optional, based on config)
     *
     * @param TenantId $tenantId The tenant identifier
     * @param string $email User's email address (will be login username)
     * @param string $fullName User's full name for display
     * @param array<string, mixed> $options Optional parameters:
     *                                      - 'send_welcome_email' => bool (default: false for bulk imports)
     *                                      - 'assign_role' => string (default: 'member')
     *                                      - 'phone' => string (optional)
     * @return TenantUserId The created TenantUser's identifier
     * @throws \DomainException If email already exists or tenant not found
     */
    public function createForCsvImport(
        TenantId $tenantId,
        string $email,
        string $fullName,
        array $options = []
    ): TenantUserId;

    /**
     * Check if email is available for a tenant
     *
     * Used for duplicate detection before attempting to create user
     *
     * @param TenantId $tenantId The tenant identifier
     * @param string $email Email address to check
     * @return bool True if email is available (not taken)
     */
    public function isEmailAvailable(TenantId $tenantId, string $email): bool;

    /**
     * Batch create TenantUsers for CSV import
     *
     * Optimized for bulk operations with transaction support
     *
     * @param TenantId $tenantId The tenant identifier
     * @param array<int, array{email: string, full_name: string}> $users Array of user data
     * @param array<string, mixed> $options Optional parameters
     * @return array<string, TenantUserId> Map of email => TenantUserId
     * @throws \DomainException If any email already exists
     */
    public function batchCreateForCsvImport(
        TenantId $tenantId,
        array $users,
        array $options = []
    ): array;
}
