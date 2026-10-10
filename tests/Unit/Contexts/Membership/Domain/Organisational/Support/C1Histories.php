<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational\Support;

use App\Contexts\Membership\Domain\Organisational\Decision;

/**
 * Bounded C-1 histories for property tests (PBDIGIT-268, plan step 12): candidates from the g6 port
 * (generation only), decided and committed by the kernel. Fact ids: Admit of period p = "A<p>";
 * End, Annul and Void at log position k = "E<k>", "X<k>", "V<k>".
 */
final class C1Histories
{
    /**
     * Every history the kernel accepts with up to $maxLength facts, the empty one included.
     *
     * @return list<array{list<array>, list<string>, MemberHistory}> [g6 facts, fact ids, kernel history]
     */
    public static function accepted(int $h, int $maxLength): array
    {
        $oracle = new G6CorrectionOracle($h);
        $all = $frontier = [[[], [], MemberHistory::start()]];
        for ($depth = 1; $depth <= $maxLength; $depth++) {
            $next = [];
            foreach ($frontier as [$s, $ids, $history]) {
                foreach ($oracle->candidates($s) as $f) {
                    [$id, $decision] = self::decide($history, $f, $ids, count($s));
                    if ($decision->isAccepted()) {
                        $next[] = [[...$s, $f], [...$ids, $id], (clone $history)->commit($decision)];
                    }
                }
            }
            $all = [...$all, ...$next];
            $frontier = $next;
        }

        return $all;
    }

    /** @return array{string, Decision} the new fact's id and the kernel's decision on g6 fact $f at position $k */
    public static function decide(MemberHistory $h, array $f, array $ids, int $k): array
    {
        return match ($f['k']) {
            'Admit' => ["A{$f['pid']}", $h->admit("A{$f['pid']}", $f['v'])],
            'End' => ["E$k", $h->end("E$k", "A{$f['pid']}", $f['v'])],
            'Annul' => ["X$k", $h->annul("X$k", $ids[$f['t']])],
            'Void' => ["V$k", $h->void("V$k", $ids[$f['t']])],
        };
    }
}
