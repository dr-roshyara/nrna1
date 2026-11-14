<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The D-90 lifecycle of ONE publication (design revision 3 §8, accepted 2026-11-14): the one definition of "open" used
 * by the ledger, ConfirmPublication, duplicate prevention and the read model.
 *
 * A finding f is OPEN at k iff f ≤ k and there is no confirmation of f ≤ k, no closure of f ≤ k, and no published
 * correction q of the publication with f < q ≤ k. Everything is read "as at k", so a later entry never changes an
 * earlier answer. k = null means "everything known".
 */
final class FindingLifecycle
{
    /**
     * @param array<int, GovernanceNotice> $findings position => finding
     * @param array<int, array{int, GovernanceNotice}> $resolutions finding position => [position, notice]
     * @param list<int> $corrections positions of published corrections of this publication
     */
    private function __construct(private readonly int $publication, private readonly array $findings, private readonly array $resolutions, private readonly array $corrections)
    {
    }

    /**
     * @param list<array{0: int, 1: GovernanceNotice}> $notices [position, notice]: this publication's findings, and the
     *   confirmations and closures of those findings
     * @param list<int> $corrections
     */
    public static function of(int $publication, array $notices, array $corrections): self
    {
        $findings = $resolutions = [];
        foreach ($notices as [$position, $notice]) {
            if ($notice->kind === NoticeKind::Finding) {
                if ($notice->concerns !== $publication) {
                    throw new \InvalidArgumentException("Finding at {$position} concerns another publication.");
                }
                $findings[$position] = $notice;
            }
        }
        foreach ($notices as [$position, $notice]) {
            if ($notice->kind !== NoticeKind::Finding) {
                if (!isset($findings[$notice->concerns])) {
                    throw new \InvalidArgumentException("Notice at {$position} resolves no finding of publication {$publication}.");
                }
                $resolutions[$notice->concerns] = [$position, $notice];
            }
        }
        ksort($findings);
        sort($corrections);

        return new self($publication, $findings, $resolutions, $corrections);
    }

    public function openFindingAt(?int $k = null): ?int
    {
        $open = null;
        foreach ($this->findings as $f => $finding) {
            if ($k === null || $f <= $k) {
                $open = $this->resolvedAt($f, $k) === null ? $f : $open;
            }
        }

        return $open;
    }

    public function correctedAt(?int $k = null): ?int
    {
        foreach ($this->corrections as $q) {
            if ($k === null || $q <= $k) {
                return $q;
            }
        }

        return null;
    }

    /** §8.4 (a): the original approver before any confirmation, the latest confirmer after it. */
    public function countedApproverAt(PersonReference $original, ?int $k = null): PersonReference
    {
        $counted = $original;
        $latest = -1;
        foreach ($this->resolutions as [$position, $notice]) {
            if ($notice->kind === NoticeKind::Confirmation && ($k === null || $position <= $k) && $position > $latest) {
                $counted = $notice->confirmedBy ?? $counted;
                $latest = $position;
            }
        }

        return $counted;
    }

    public function statusAt(int $k): ApprovalStatus
    {
        $history = [];
        foreach ($this->findings as $f => $finding) {
            if ($f <= $k) {
                $history[] = ['position' => $f, 'event' => 'finding'];
                [$position, $resolution] = $this->resolutions[$f] ?? [null, null];
                if ($position !== null && $position <= $k) {
                    $history[] = ['position' => $position, 'event' => $resolution->kind->value];
                }
            }
        }
        $corrected = $this->correctedAt($k);
        if ($corrected !== null) {
            $history[] = ['position' => $corrected, 'event' => 'correction'];
        }
        usort($history, static fn (array $a, array $b): int => $a['position'] <=> $b['position']);

        if ($corrected !== null) {
            return new ApprovalStatus(ApprovalStatusKind::Corrected, $corrected, $history);
        }
        $latest = null;
        foreach ($this->findings as $f => $finding) {
            if ($f <= $k) {
                $latest = $f;
            }
        }
        if ($latest === null) {
            return new ApprovalStatus(ApprovalStatusKind::Approved, null, $history);
        }
        [$position, $resolution] = $this->resolutions[$latest] ?? [null, null];
        $kind = match (true) {
            $position === null || $position > $k => ApprovalStatusKind::ApprovalDefective,
            $resolution->kind === NoticeKind::Confirmation => ApprovalStatusKind::Confirmed,
            default => ApprovalStatusKind::FindingClosed,
        };

        return new ApprovalStatus($kind, null, $history);
    }

    /** The position resolving finding f as at k (its confirmation, its closure, or a later correction), or null. */
    private function resolvedAt(int $f, ?int $k): ?int
    {
        $candidates = [];
        [$position] = $this->resolutions[$f] ?? [null];
        if ($position !== null && ($k === null || $position <= $k)) {
            $candidates[] = $position;
        }
        foreach ($this->corrections as $q) {
            if ($q > $f && ($k === null || $q <= $k)) {
                $candidates[] = $q;
            }
        }

        return $candidates === [] ? null : min($candidates);
    }
}
