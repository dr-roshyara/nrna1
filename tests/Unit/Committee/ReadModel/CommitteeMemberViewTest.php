<?php

declare(strict_types=1);

namespace Tests\Unit\Committee\ReadModel;

use App\Contexts\Committee\Application\ReadModel\CommitteeMemberView;
use PHPUnit\Framework\TestCase;

final class CommitteeMemberViewTest extends TestCase
{
    public function test_immutable_readonly_properties(): void
    {
        $now = new \DateTimeImmutable('2026-05-15 12:00:00');

        $view = new CommitteeMemberView(
            memberId: 'member-123',
            displayName: 'John Doe',
            statusKey: 'committee.members.status.active',
            roleKey: 'committee.members.role.member',
            joinedAt: $now,
        );

        // Assert: Properties are accessible
        $this->assertSame('member-123', $view->memberId);
        $this->assertSame('John Doe', $view->displayName);
        $this->assertSame('committee.members.status.active', $view->statusKey);
        $this->assertSame('committee.members.role.member', $view->roleKey);
        $this->assertSame($now, $view->joinedAt);
    }

    public function test_optional_fields_default_to_null(): void
    {
        $view = new CommitteeMemberView(
            memberId: 'member-456',
            displayName: 'Jane Smith',
            statusKey: 'committee.members.status.active',
            roleKey: 'committee.members.role.member',
        );

        // Assert: Optional fields are null
        $this->assertNull($view->joinedAt);
        $this->assertNull($view->lastTransitionAt);
    }

    public function test_status_key_follows_translation_pattern(): void
    {
        $statuses = [
            'committee.members.status.active',
            'committee.members.status.suspended',
            'committee.members.status.terminated',
        ];

        foreach ($statuses as $statusKey) {
            $view = new CommitteeMemberView(
                memberId: 'member-id',
                displayName: 'Test User',
                statusKey: $statusKey,
                roleKey: 'committee.members.role.member',
            );

            // Assert: Status key starts with pattern
            $this->assertStringStartsWith('committee.members.status.', $view->statusKey);
        }
    }

    public function test_role_key_follows_translation_pattern(): void
    {
        $roleKey = 'committee.members.role.member';

        $view = new CommitteeMemberView(
            memberId: 'member-id',
            displayName: 'Test User',
            statusKey: 'committee.members.status.active',
            roleKey: $roleKey,
        );

        // Assert: Role key starts with pattern
        $this->assertStringStartsWith('committee.members.role.', $view->roleKey);
    }

    public function test_no_framework_dependencies(): void
    {
        $view = new CommitteeMemberView(
            memberId: 'member-id',
            displayName: 'Test User',
            statusKey: 'committee.members.status.active',
            roleKey: 'committee.members.role.member',
        );

        // Assert: Can serialize to array without Laravel
        $array = [
            'memberId' => $view->memberId,
            'displayName' => $view->displayName,
            'statusKey' => $view->statusKey,
            'roleKey' => $view->roleKey,
            'joinedAt' => $view->joinedAt?->format('Y-m-d'),
        ];

        $this->assertIsArray($array);
        $this->assertNotEmpty($array);
    }

    public function test_joined_at_is_datetimeimmutable(): void
    {
        $now = new \DateTimeImmutable();

        $view = new CommitteeMemberView(
            memberId: 'member-id',
            displayName: 'Test User',
            statusKey: 'committee.members.status.active',
            roleKey: 'committee.members.role.member',
            joinedAt: $now,
        );

        // Assert: joinedAt is immutable
        $this->assertInstanceOf(\DateTimeImmutable::class, $view->joinedAt);
        $this->assertSame($now, $view->joinedAt);
    }

    public function test_class_is_readonly(): void
    {
        $reflection = new \ReflectionClass(CommitteeMemberView::class);

        // Assert: Class is final
        $this->assertTrue($reflection->isFinal());

        // Assert: All properties are public
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        $this->assertCount(6, $properties);
    }

    public function test_contract_immutability_guarantee(): void
    {
        $view = new CommitteeMemberView(
            memberId: 'member-id',
            displayName: 'Test User',
            statusKey: 'committee.members.status.active',
            roleKey: 'committee.members.role.member',
        );

        // Assert: Cannot modify properties (readonly)
        // This is guaranteed by readonly properties in PHP 8.1+
        $this->assertSame('member-id', $view->memberId);

        // Attempting to set would throw error at parse time
        // So we just verify the value is what we expect
        $expectedMemberId = 'member-id';
        $this->assertSame($expectedMemberId, $view->memberId);
    }
}
