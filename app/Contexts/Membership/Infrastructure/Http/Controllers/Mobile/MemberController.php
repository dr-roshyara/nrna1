<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Controllers\Mobile;

use App\Contexts\Membership\Application\DTOs\MobileRegistrationDto;
use App\Contexts\Membership\Application\Services\MobileMemberRegistrationService;
use App\Contexts\Membership\Infrastructure\Http\Requests\Mobile\RegisterMemberRequest;
use App\Contexts\Membership\Infrastructure\Http\Resources\MobileMemberResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

/**
 * Mobile Member Controller
 *
 * INFRASTRUCTURE LAYER - HTTP Controller (THIN)
 *
 * Handles HTTP requests for mobile member operations.
 *
 * CASE 2 Routes: /{tenant}/mapi/v1/members/*
 * - POST /{tenant}/mapi/v1/members/register - Public registration (no auth)
 * - GET /{tenant}/mapi/v1/members/me - Get authenticated member profile
 *
 * Controller Responsibilities (THIN):
 * 1. Receive HTTP request
 * 2. Create DTO from request
 * 3. Delegate to Application Service
 * 4. Return HTTP response (JSON:API format)
 * 5. Handle exceptions → HTTP errors
 *
 * Business Logic:
 * - NONE (delegated to Application Service)
 *
 * Architecture:
 * - Controller is THIN (no business logic)
 * - Application Service orchestrates
 * - Domain decides business rules
 */
class MemberController extends Controller
{
    public function __construct(
        private readonly MobileMemberRegistrationService $registrationService
    ) {}

    /**
     * Register a new member via mobile app (self-registration)
     *
     * POST /{tenant}/mapi/v1/members/register
     *
     * Public endpoint (no authentication required)
     *
     * Business Flow:
     * 1. Validate request (RegisterMemberRequest)
     * 2. Create DTO from request
     * 3. Delegate to MobileMemberRegistrationService
     * 4. Return JSON:API response with 201 Created
     *
     * @param RegisterMemberRequest $request Validated HTTP request
     * @return JsonResponse JSON:API formatted response
     */
    public function register(RegisterMemberRequest $request): JsonResponse
    {
        try {
            // Step 1: Create DTO from validated request
            $dto = MobileRegistrationDto::fromRequest($request);

            // Step 2: Delegate to Application Service
            $member = $this->registrationService->register($dto);

            // Step 3: Return JSON:API response
            return (new MobileMemberResource($member))
                ->response()
                ->setStatusCode(201); // 201 Created

        } catch (InvalidArgumentException $e) {
            // Business rule violation (e.g., duplicate member_id)
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => [
                    'business_rule' => [$e->getMessage()],
                ],
            ], 422); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            // Unexpected error
            return response()->json([
                'message' => 'An error occurred during registration. Please try again.',
                'errors' => [
                    'system' => [config('app.debug') ? $e->getMessage() : 'Internal server error'],
                ],
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Get authenticated member profile
     *
     * GET /{tenant}/mapi/v1/members/me
     *
     * Requires Sanctum authentication
     *
     * @return JsonResponse JSON:API formatted response
     */
    public function me(): JsonResponse
    {
        // TODO: Implement in future iteration
        // - Get authenticated user's member record
        // - Return MobileMemberResource
        return response()->json([
            'message' => 'Not implemented yet',
        ], 501); // 501 Not Implemented
    }
}
