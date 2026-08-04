<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first for the FileSaveTrigger: pure change detection over mtime
 * snapshots. The trigger detects and delegates — no recommendation logic,
 * no collector knowledge (commission 2026-08-04).
 */
final class FileSaveTriggerTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/ChangeSet.php';
        require_once dirname(__DIR__, 2) . '/scripts/observations/FileSaveTrigger.php';
    }

    public function test_detects_modified_and_new_files(): void
    {
        $previous = ['app/A.php' => 100, 'app/B.php' => 100];
        $current  = ['app/A.php' => 100, 'app/B.php' => 200, 'app/C.php' => 150];

        $this->assertSame(['app/B.php', 'app/C.php'], \FileSaveTrigger::detect($previous, $current));
    }

    public function test_no_changes_detects_nothing(): void
    {
        $snap = ['app/A.php' => 100];

        $this->assertSame([], \FileSaveTrigger::detect($snap, $snap));
    }

    public function test_builds_changeset_with_file_save_source(): void
    {
        $cs = \FileSaveTrigger::changeSet(['app/B.php'], '2026-08-04T21:00:00+02:00');

        $this->assertSame('file-save', $cs->source);
        $this->assertNull($cs->commitId);
        $this->assertSame(['app/B.php'], $cs->changedFiles);
    }
}
