<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first for the recommendation inbox: the decision-capture entry point
 * commissioned by the EDA-proposal verdict ("what I would implement NOW").
 * Pure classification over existing streams — the inbox lists, it never decides.
 */
final class RecommendationInboxTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/RecommendationInbox.php';
    }

    private function recs(): array
    {
        return [
            ['id' => 'REC-a', 'rule' => 'R1', 'subject' => 'Election', 'text' => 'consider reviewing', 'ts' => '2026-08-04T10:00:00+02:00'],
            ['id' => 'REC-b', 'rule' => 'R5', 'subject' => 'Committee', 'text' => 'hotspot', 'ts' => '2026-08-04T08:00:00+02:00'],
            ['id' => 'REC-c', 'rule' => 'R5', 'subject' => 'Ballot', 'text' => 'hotspot', 'ts' => '2026-08-04T09:00:00+02:00'],
        ];
    }

    public function test_open_lists_undecided_recommendations_oldest_first(): void
    {
        $inbox = \RecommendationInbox::classify($this->recs(), []);

        $this->assertSame(['REC-b', 'REC-c', 'REC-a'], array_column($inbox['needs_decision'], 'id'));
        $this->assertSame([], $inbox['deferred']);
    }

    public function test_decided_recommendations_leave_the_inbox_by_latest_decision(): void
    {
        $decisions = [
            ['type' => 'decision', 'recommendation_id' => 'REC-a', 'decision' => 'ACCEPTED', 'ts' => '2026-08-04T11:00:00+02:00'],
            ['type' => 'rationale', 'recommendation_id' => 'REC-a', 'reason_code' => 'IMMEDIATE_VALUE'],
        ];
        $inbox = \RecommendationInbox::classify($this->recs(), $decisions);

        $this->assertSame(['REC-b', 'REC-c'], array_column($inbox['needs_decision'], 'id'));
    }

    public function test_deferred_recommendations_surface_separately_as_awaiting_redecision(): void
    {
        $decisions = [
            ['type' => 'decision', 'recommendation_id' => 'REC-b', 'decision' => 'DEFERRED', 'ts' => '2026-08-04T11:00:00+02:00'],
        ];
        $inbox = \RecommendationInbox::classify($this->recs(), $decisions);

        $this->assertSame(['REC-c', 'REC-a'], array_column($inbox['needs_decision'], 'id'));
        $this->assertSame(['REC-b'], array_column($inbox['deferred'], 'id'));
    }
}
