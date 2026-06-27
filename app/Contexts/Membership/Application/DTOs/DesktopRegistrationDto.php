<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTOs;

use Illuminate\Http\Request;

/**
 * Desktop Registration DTO
 *
 * APPLICATION LAYER - Data Transfer Object
 *
 * Transfers data from HTTP layer (desktop admin controller) to Application Service.
 * Isolates HTTP concerns from business logic.
 *
 * Desktop Registration Characteristics:
 * - tenant_user_id IS provided (admin creates member for existing user)
 * - No mobile-specific fields
 * - Requires authentication (session-based)
 * - Creates member with PENDING status (skips DRAFT)
 *
 * Business Flow:
 * 1. Admin creates tenant user account (via TenantAuth context)
 * 2. Admin creates member record (via Membership context)
 * 3. Member starts at PENDING status (awaiting approval)
 *
 * Usage:
 * ```php
 * $dto = DesktopRegistrationDto::fromRequest($request);
 * $member = $registrationService->register($dto);
 * ```
 */
final readonly class DesktopRegistrationDto
{
    public function __construct(
        public string $tenantId,
        public string $tenantUserId,  // REQUIRED: User already exists
        public string $fullName,
        public string $email,
        public ?string $phone = null,
        public ?string $memberId = null,
        public ?string $geoReference = null
    ) {}

    /**
     * Create DTO from HTTP Request
     *
     * Extracts and validates data from desktop admin registration request.
     * Tenant ID comes from route parameter.
     * Tenant User ID comes from request body (admin provides existing user ID).
     *
     * @param Request $request HTTP request from desktop admin
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            tenantId: $request->route('tenant'),
            tenantUserId: $request->input('tenant_user_id'),
            fullName: $request->input('full_name'),
            email: $request->input('email'),
            phone: $request->input('phone'),
            memberId: $request->input('member_id'),
            geoReference: $request->input('geo_reference')
        );
    }

    /**
     * Convert to array (for logging, debugging)
     */
    public function toArray(): array
    {
        return [
            'tenant_id' => $this->tenantId,
            'tenant_user_id' => $this->tenantUserId,
            'full_name' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
            'member_id' => $this->memberId,
            'geo_reference' => $this->geoReference,
        ];
    }
}
