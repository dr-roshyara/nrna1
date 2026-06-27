<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Resources;

use App\Contexts\Membership\Domain\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Mobile Member Resource
 *
 * INFRASTRUCTURE LAYER - HTTP Resource
 *
 * Transforms Member aggregate into JSON:API format for mobile clients.
 *
 * JSON:API Structure:
 * - data.id: Member system ID (ULID)
 * - data.type: Resource type ("member")
 * - data.attributes: Member attributes
 * - data.links.self: Link to member resource
 *
 * Mobile-Specific:
 * - Simplified response (less verbose than desktop)
 * - Includes verification status
 * - Includes registration channel
 * - No admin-specific fields
 *
 * @property Member $resource
 */
class MobileMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Member $member */
        $member = $this->resource;

        return [
            'id' => $member->id,
            'type' => 'member',
            'attributes' => [
                'member_id' => $member->member_id?->value(),
                'tenant_user_id' => $member->tenant_user_id,
                'tenant_id' => $member->tenant_id,
                'personal_info' => [
                    'full_name' => $member->personal_info->fullName(),
                    'email' => $member->personal_info->email()->value(),
                    'phone' => $member->personal_info->phone(),
                ],
                'status' => $member->status->value(),
                'residence_geo_reference' => $member->residence_geo_reference,
                'membership_type' => $member->membership_type,
                'registration_channel' => $member->registration_channel,
                'created_at' => $member->created_at?->toIso8601String(),
                'updated_at' => $member->updated_at?->toIso8601String(),
            ],
            'links' => [
                'self' => route('mobile.api.v1.show', [
                    'tenant' => $member->tenant_id,
                    'member' => $member->id,
                ]),
            ],
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        /** @var Member $member */
        $member = $this->resource;

        return [
            'message' => $this->getSuccessMessage($member),
            'meta' => [
                'verification_required' => $member->status->isDraft(),
                'can_vote' => $member->canVote(),
                'can_hold_committee_role' => $member->canHoldCommitteeRole(),
            ],
        ];
    }

    /**
     * Get success message based on member status
     */
    private function getSuccessMessage(Member $member): string
    {
        return match ($member->status->value()) {
            'draft' => 'Registration successful. Please check your email for verification.',
            'pending' => 'Registration successful. Awaiting admin approval.',
            'approved' => 'Your membership has been approved.',
            'active' => 'Welcome! Your membership is active.',
            default => 'Registration successful.',
        };
    }
}
