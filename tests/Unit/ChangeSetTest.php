<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * TDD-first for ChangeSet: the technology-neutral object every trigger
 * produces and the only thing the Observation Runtime consumes.
 * NOT GitCommit, NOT VSCodeFile, NOT PullRequest (commission 2026-08-04).
 */
final class ChangeSetTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__, 2) . '/scripts/observations/ChangeSet.php';
    }

    public function test_carries_technology_neutral_fields(): void
    {
        $cs = new \ChangeSet(
            changedFiles: ['app/Models/Election.php', 'resources/js/app.js'],
            source: 'file-save',
            timestamp: '2026-08-04T21:00:00+02:00',
            commitId: null
        );

        $this->assertSame('file-save', $cs->source);
        $this->assertNull($cs->commitId);
        $this->assertSame(['app/Models/Election.php', 'resources/js/app.js'], $cs->changedFiles);
    }

    public function test_php_files_filter_returns_only_php_sources(): void
    {
        $cs = new \ChangeSet(
            changedFiles: ['app/Models/Election.php', 'resources/js/app.js', 'app/Models/User.blade.php'],
            source: 'commit',
            timestamp: '2026-08-04T21:00:00+02:00',
            commitId: 'abc1234'
        );

        // blade templates are not production classes — same rule as test-presence
        $this->assertSame(['app/Models/Election.php'], $cs->phpClassFiles());
    }
}
