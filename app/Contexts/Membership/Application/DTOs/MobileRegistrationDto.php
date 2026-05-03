<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTOs;

use Illuminate\Http\Request;

/**
 * Mobile Registration DTO
 *
 * APPLICATION LAYER - Data Transfer Object
 *
 * Transfers data from HTTP layer (mobile controller) to Application Service.
 * Isolates HTTP concerns from business logic.
 *
 * Mobile Registration Characteristics:
 * - No tenant_user_id provided (user doesn't exist yet)
 * - Includes mobile-specific fields (device_id, app_version, platform)
 * - Public endpoint (no authentication)
 * - Creates member with DRAFT status
 *
 * Usage:
 * ```php
 * $dto = MobileRegistrationDto::fromRequest($request);
 * $member = $registrationService->register($dto);
 * ```
 */
final readonly class MobileRegistrationDto
{
    public function __construct(
        public string $tenantId,
        public string $fullName,
        public string $email,
        public ?string $phone = null,
        public ?string $memberId = null,
        public ?string $geoReference = null,

        // Mobile-specific fields
        public ?string $deviceId = null,
        public ?string $appVersion = null,
        public ?string $platform = null  // ios, android, web
    ) {}

    /**
     * Create DTO from HTTP Request
     *
     * Extracts and validates data from mobile registration request.
     * Tenant ID comes from route parameter.
     *
     * @param Request $request HTTP request from mobile app
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            tenantId: $request->route('tenant'),
            fullName: $request->input('full_name'),
            email: $request->input('email'),
            phone: $request->input('phone'),
            memberId: $request->input('member_id'),
            geoReference: $request->input('geo_reference'),

            // Mobile-specific
            deviceId: $request->input('device_id'),
            appVersion: $request->input('app_version'),
            platform: $request->input('platform')
        );
    }

    /**
     * Convert to array (for logging, debugging)
     */
    public function toArray(): array
    {
        return [
            'tenant_id' => $this->tenantId,
            'full_name' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
            'member_id' => $this->memberId,
            'geo_reference' => $this->geoReference,
            'device_id' => $this->deviceId,
            'app_version' => $this->appVersion,
            'platform' => $this->platform,
        ];
    }
}
