<?php
/**
 * Research bridge (not production code): replays candidate histories through the REAL
 * Slice 0 kernel (decideAdmit / decideEnd + G*) as an admissibility filter.
 * Input (stdin): JSON list of histories; each history = list of ops
 *   {"k":"admit","id":"A","at":0,"rec":0} | {"k":"end","id":"E","period":"A","at":3,"rec":3}
 * Ops are decided in the given order (the caller sorts by recording time).
 * Output (stdout): one JSON object per history:
 *   {"admissible":true,"member":[bool per month 0..T-1]} | {"admissible":false,"op":i,"reason":"..."}
 * Admit uses an unlimited initial term (the research models assume membership continues
 * until an End). Months are used directly as TimePoint ordinals (abstract days).
 * Run: php -d error_reporting=E_ALL docs/.../kernel_admissibility.php T < histories.json
 */
declare(strict_types=1);
require __DIR__ . '/../../../../../vendor/autoload.php';

use App\Contexts\Membership\Domain\Organisational\{EndKind, FactId, Instant, Member, MemberId, PeriodId, PersonRef, Provenance, RecordedFact, TimePoint};
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

$T = (int) ($argv[1] ?? 6);
$tenant = TenantId::fromString('00000000-0000-4000-8000-000000000001');
$histories = json_decode(stream_get_contents(STDIN), true, flags: JSON_THROW_ON_ERROR);
foreach ($histories as $ops) {
    $log = [];
    $member = Member::fromLog(MemberId::fromString('M'), PersonRef::fromString('P'), $tenant);
    $verdict = null;
    foreach ($ops as $i => $op) {
        $prov = Provenance::decision('research');
        $decision = $op['k'] === 'admit'
            ? $member->decideAdmit(FactId::fromString($op['id']), TimePoint::of($op['at']), TimePoint::infinity(), $prov)
            : $member->decideEnd(FactId::fromString($op['id']), PeriodId::fromString($op['period']), TimePoint::of($op['at']), EndKind::fromString('research'), $prov);
        if (!$decision->isAccepted()) {
            $verdict = ['admissible' => false, 'op' => $i, 'reason' => $decision->refusal()->name];
            break;
        }
        $log[] = RecordedFact::of(count($log) + 1, Instant::of($op['rec']), $decision->fact());
        $member = Member::fromLog(MemberId::fromString('M'), PersonRef::fromString('P'), $tenant, ...$log);
    }
    if ($verdict === null) {
        $resolved = $member->resolve();
        $verdict = ['admissible' => true, 'member' => array_map(fn (int $t): bool => $resolved->isMember(TimePoint::of($t)), range(0, $T - 1))];
    }
    echo json_encode($verdict), "\n";
}
