<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Resources\Desktop;

use App\Contexts\Membership\Domain\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Desktop Member Resource
 *
 * CASE 4: Tenant Desktop API - Admin View
 * Provides comprehensive member data for administrative interfaces
 *
 * DIFFERENCES FROM MOBILE RESOURCE:
 * - Includes tenant_user_id (admins can see system linkage)
 * - Includes workflow metadata (approval status, actions available)
 * - Includes admin-specific links (approve, reject, edit)
 * - Includes audit trail information (created_at, updated_at)
 *
 * JSON:API Specification Compliance:
 * - Resource object structure (type, id, attributes, links)
 * - Consistent attribute naming (snake_case)
 * - Self-links for resource identification
 */
class DesktopMemberResource extends JsonResource
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
                // System Identifiers (Admin-visible)
                'member_id' => $member->member_id?->value(),
                'tenant_user_id' => $member->tenant_user_id, // Desktop shows linkage
                'tenant_id' => $member->tenant_id,

                // Personal Information
                'personal_info' => [
                    'full_name' => $member->personal_info?->fullName(),
                    'email' => $member->personal_info?->email()->value(),
                    'phone' => $member->personal_info?->phone(),
                ],

                // Membership Status
                'status' => $member->status->value(),
                'status_label' => $this->getStatusLabel($member->status->value()),

                // Geography
                'residence_geo_reference' => $member->residence_geo_reference,

                // Membership Details
                'membership_type' => $member->membership_type,
                'registration_channel' => $member->registration_channel,

                // Timestamps (Admin-visible)
                'created_at' => $member->created_at?->toIso8601String(),
                'updated_at' => $member->updated_at?->toIso8601String(),
                'email_verified_at' => $member->email_verified_at?->toIso8601String(),

                // Workflow Metadata
                'workflow' => $this->getWorkflowMetadata($member),
            ],

            'links' => [
                'self' => url("/{$member->tenant_id}/api/v1/members/{$member->id}"),
            ],

            // Admin-specific actions (conditionally included)
            'meta' => $this->getAdminMetadata($member, $request),
        ];
    }

    /**
     * Get human-readable status label
     */
    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'draft' => 'Draft',
            'pending' => 'Pending Approval',
            'approved' => 'Approved',
            'active' => 'Active',
            'suspended' => 'Suspended',
            'inactive' => 'Inactive',
            'archived' => 'Archived',
            default => ucfirst($status),
        };
    }

    /**
     * Get workflow metadata for admin decision-making
     *
     * @return array<string, mixed>
     */
    private function getWorkflowMetadata(Member $member): array
    {
        $status = $member->status->value();

        return [
            'can_approve' => $status === 'pending',
            'can_activate' => $status === 'approved',
            'can_suspend' => in_array($status, ['active', 'approved']),
            'can_archive' => !in_array($status, ['archived']),
            'requires_verification' => $status === 'draft' && $member->email_verified_at === null,
            'is_verified' => $member->email_verified_at !== null,
        ];
    }

    /**
     * Get admin-specific metadata
     *
     * @return array<string, mixed>
     */
    private function getAdminMetadata(Member $member, Request $request): array
    {
        // Check if authenticated user can manage this member
        // Note: For now, we assume auth middleware handles this
        // Future: Add policy-based permission checks

        $meta = [
            'permissions' => [
                'can_edit' => true, // TODO: Replace with policy check
                'can_delete' => $member->status->value() === 'draft',
                'can_approve' => $member->status->value() === 'pending',
            ],
        ];

        // Add action links if permissions allow
        if ($meta['permissions']['can_approve']) {
            $meta['actions'] = [
                'approve' => [
                    'method' => 'POST',
                    'url' => url("/{$member->tenant_id}/api/v1/members/{$member->id}/approve"),
                ],
                'reject' => [
                    'method' => 'POST',
                    'url' => url("/{$member->tenant_id}/api/v1/members/{$member->id}/reject"),
                ],
            ];
        }

        return $meta;
    }

    /**
     * Customize the response wrapper.
     *
     * @param Request $request
     * @param array<mixed> $data
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'message' => $this->getResponseMessage(),
        ];
    }

    /**
     * Get context-aware response message
     */
    private function getResponseMessage(): string
    {
        /** @var Member $member */
        $member = $this->resource;

        if ($member->wasRecentlyCreated) {
            return "Member created successfully with {$member->status->value()} status.";
        }

        return 'Member retrieved successfully.';
    }
}
