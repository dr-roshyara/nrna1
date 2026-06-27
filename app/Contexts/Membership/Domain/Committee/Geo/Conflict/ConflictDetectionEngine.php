<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Conflict;

use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;

final class ConflictDetectionEngine
{
    /**
     * Detect all conflicts in an authority classification.
     * Detection only — no winner selection or resolution.
     *
     * @return AuthorityConflict[]
     */
    public function detect(AuthorityClassification $classification): array
    {
        $conflicts = [];

        $authorityCount = ($classification->direct ? 1 : 0)
            + count($classification->delegated)
            + count($classification->overrides)
            + count($classification->exceptions);

        if ($authorityCount > 1) {
            $conflicts[] = new AuthorityConflict(
                ConflictType::MULTI_AUTHORITY,
                $this->extractIds($classification),
                'Multiple authority sources detected'
            );
        }

        if (!empty($classification->exceptions)) {
            $conflicts[] = new AuthorityConflict(
                ConflictType::EXCEPTION_PRESENT,
                array_map(fn($node) => $node->id, $classification->exceptions),
                'Exception zones are active'
            );
        }

        if (!empty($classification->overrides)) {
            $conflicts[] = new AuthorityConflict(
                ConflictType::OVERRIDE_PRESENT,
                array_map(fn($node) => $node->id, $classification->overrides),
                'Override authorities are present'
            );
        }

        if ($classification->direct && !empty($classification->delegated)) {
            $conflicts[] = new AuthorityConflict(
                ConflictType::DIRECT_VS_DELEGATED,
                $this->extractIds($classification),
                'Direct and delegated authorities coexist'
            );
        }

        return $conflicts;
    }

    /**
     * Extract all authority IDs from classification.
     *
     * @return string[]
     */
    private function extractIds(AuthorityClassification $classification): array
    {
        $ids = [];

        if ($classification->direct) {
            $ids[] = $classification->direct->id;
        }

        foreach ($classification->delegated as $node) {
            $ids[] = $node->id;
        }

        foreach ($classification->overrides as $node) {
            $ids[] = $node->id;
        }

        foreach ($classification->exceptions as $node) {
            $ids[] = $node->id;
        }

        return $ids;
    }
}
