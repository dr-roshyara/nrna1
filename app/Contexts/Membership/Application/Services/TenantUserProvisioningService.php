<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Membership\Application\Interfaces\TenantUserProvisioningInterface;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\TenantAuth\Domain\Models\TenantUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * Tenant User Provisioning Service
 *
 * Anti-Corruption Layer Pattern: Membership Context → TenantAuth Context
 *
 * Cross-Context Coordination:
 * - Membership Context needs TenantUsers for Members
 * - TenantAuth Context owns TenantUser creation
 * - This service coordinates between the two contexts
 *
 * Business Rule: Every Member MUST have a TenantUser identity
 *
 * Architecture:
 * - Uses TenantUser::createWithRole() from TenantAuth context
 * - Returns TenantUser ID for linking to Member aggregate
 * - Generates secure random password for CSV imports
 * - Sends welcome email with password reset link (optional)
 *
 * @see TenantUser::createWithRole() in TenantAuth context
 * @see Member aggregate in Membership context
 */
final class TenantUserProvisioningService implements TenantUserProvisioningInterface
{
    /**
     * Default role for CSV-imported users
     */
    private const DEFAULT_MEMBER_ROLE = 'member';

    /**
     * {@inheritdoc}
     *
     * Creates TenantUser for CSV-imported member
     *
     * Business Rules:
     * - Email must be unique per tenant
     * - Default role: 'member' (not committee member)
     * - Password generated securely (user must reset)
     * - Welcome email sent if requested
     *
     * @param TenantId $tenantId Tenant context
     * @param string $email Member email (unique identifier)
     * @param string $fullName Member full name
     * @param array $options Additional options:
     *   - send_welcome_email: bool (default: false)
     *   - phone: string|null
     *   - role: string (default: 'member')
     *
     * @return TenantUserId TenantUser identifier value object
     * @throws \Exception If email already exists for tenant
     */
    public function createForCsvImport(
        TenantId $tenantId,
        string $email,
        string $fullName,
        array $options = []
    ): TenantUserId {
        // Validate email uniqueness for tenant
        $existingUser = TenantUser::where('email', $email)->first();
        if ($existingUser) {
            throw new \DomainException("User with email '{$email}' already exists for this tenant");
        }

        // Parse full name into first_name and last_name (Universal Core Schema)
        $nameParts = $this->parseFullName($fullName);

        // Prepare user data (Universal Core Schema compliance)
        $userData = [
            'tenant_id' => $tenantId->toString(),
            'first_name' => $nameParts['first_name'],
            'last_name' => $nameParts['last_name'],
            'email' => $email,
            'password_hash' => $this->generateSecurePassword(), // User must reset
            'must_change_password' => true, // Force password reset on first login
            'status' => 'active',
            'phone' => $options['phone'] ?? null,
        ];

        // Create TenantUser with role (cross-context call to TenantAuth)
        $role = $options['role'] ?? self::DEFAULT_MEMBER_ROLE;
        $tenantUser = TenantUser::createWithRole($userData, $role);

        // Send welcome email if requested
        if ($options['send_welcome_email'] ?? false) {
            $this->sendWelcomeEmail($tenantUser);
        }

        // Return TenantUser ID as value object for linking to Member aggregate
        return new TenantUserId($tenantUser->id);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmailAvailable(TenantId $tenantId, string $email): bool
    {
        return !TenantUser::where('tenant_id', $tenantId->toString())
            ->where('email', $email)
            ->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function batchCreateForCsvImport(
        TenantId $tenantId,
        array $users,
        array $options = []
    ): array {
        $created = [];

        // Wrap in transaction for all-or-nothing consistency
        DB::connection('tenant')->transaction(function () use ($tenantId, $users, $options, &$created) {
            foreach ($users as $userData) {
                $email = $userData['email'];
                $fullName = $userData['full_name'];

                try {
                    $tenantUserId = $this->createForCsvImport($tenantId, $email, $fullName, $options);
                    $created[$email] = $tenantUserId;
                } catch (\DomainException $e) {
                    // Skip duplicates (already exists)
                    \Log::info('Batch import skipped duplicate', [
                        'email' => $email,
                        'tenant_id' => $tenantId->toString(),
                    ]);
                }
            }
        });

        return $created;
    }

    /**
     * Parse full name into first_name and last_name
     *
     * Business Rule: Universal Core Schema requires separate first_name/last_name
     *
     * Examples:
     * - "John Doe" → first_name: "John", last_name: "Doe"
     * - "Ram Bahadur Thapa" → first_name: "Ram Bahadur", last_name: "Thapa"
     * - "Sita" → first_name: "Sita", last_name: "" (empty string)
     *
     * @param string $fullName Full name from CSV
     * @return array{first_name: string, last_name: string}
     */
    private function parseFullName(string $fullName): array
    {
        $fullName = trim($fullName);

        // Split by last space (last word = last name, rest = first name)
        $lastSpacePos = strrpos($fullName, ' ');

        if ($lastSpacePos === false) {
            // Single word name (e.g., "Sita")
            return [
                'first_name' => $fullName,
                'last_name' => '',
            ];
        }

        // Multi-word name (e.g., "Ram Bahadur Thapa")
        return [
            'first_name' => trim(substr($fullName, 0, $lastSpacePos)),
            'last_name' => trim(substr($fullName, $lastSpacePos + 1)),
        ];
    }

    /**
     * Generate secure random password
     *
     * Business Rule: CSV-imported users get random password and must reset
     *
     * @return string Bcrypt hash of random password
     */
    private function generateSecurePassword(): string
    {
        // Generate 16-character random password
        $randomPassword = Str::random(16);

        // Hash with bcrypt (Laravel default)
        return bcrypt($randomPassword);
    }

    /**
     * Send welcome email to new user
     *
     * @param TenantUser $tenantUser Newly created user
     * @return void
     *
     * @todo Implement email sending (Week 2)
     */
    private function sendWelcomeEmail(TenantUser $tenantUser): void
    {
        // @todo Week 2: Integrate with email service
        // For MVP, we skip email sending (users manually notified)
        // Log for tracking
        \Log::info('Welcome email would be sent', [
            'tenant_user_id' => $tenantUser->id,
            'email' => $tenantUser->email,
        ]);
    }
}
