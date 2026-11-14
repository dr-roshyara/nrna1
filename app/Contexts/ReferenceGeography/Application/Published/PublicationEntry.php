<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/**
 * One publication, as the feed gives it (G-5c; v3.11 §10 RevisionPublished / GovernanceNoticePublished, one per
 * position). Immutable: the same position always reads the same. Provenance only, never persons. A consumer may raise
 * review signals from it; it must never change belonging or membership automatically (context map).
 */
final readonly class PublicationEntry
{
    /**
     * @param string $kind revision | notice
     * @param list<string> $claimKinds the kinds of fact it published (sorted)
     * @param list<string> $subjects the places it concerns (sorted unit ids)
     * @param list<array{identity: string, canonical: ?string}> $merges identities merged (canonical null: a reversal)
     * @param string|null $noticeKind G-6c-6 (D-90): finding | confirmation | closure for a notice; null for a revision
     * @param int|null $concernsPosition a notice's referent: the publication a finding flags, the finding a
     *   confirmation or closure resolves
     * @param int|null $correctsPosition a revision published as a full-coverage correction of that publication
     *   (it resolves that publication's open finding)
     * The three are additive and optional, so existing consumers are unaffected; never persons, never merge bases.
     */
    public function __construct(
        public int $position,
        public string $recordedAt,
        public string $kind,
        public ?string $revisionId,
        public ?int $contentVersion,
        public string $governanceRule,
        public array $claimKinds,
        public array $subjects,
        public array $merges,
        public ?string $noticeKind = null,
        public ?int $concernsPosition = null,
        public ?int $correctsPosition = null,
    ) {
    }
}
