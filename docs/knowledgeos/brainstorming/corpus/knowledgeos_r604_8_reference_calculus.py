from __future__ import annotations
from dataclasses import dataclass
from enum import Enum
from itertools import combinations, product
from typing import FrozenSet, Mapping, Tuple

class Status(str, Enum):
    PASS='PASS'; FAIL='FAIL'; UNKNOWN='UNKNOWN'; CONDITIONAL='CONDITIONAL'; UNDEFINED='UNDEFINED'; NOT_APPLICABLE='NOT_APPLICABLE'

@dataclass(frozen=True)
class Scope:
    id: str
    population: str
    time: str

@dataclass(frozen=True)
class Regime:
    id: str
    axioms: Tuple[str, ...] = ()

@dataclass(frozen=True)
class Factor:
    id: str
    kind: str

@dataclass(frozen=True)
class Intervention:
    baseline: Mapping[str, int]
    changed: FrozenSet[str]

@dataclass(frozen=True)
class DependencyAssessment:
    factors: FrozenSet[str]
    target: str
    scope: Scope
    regime: Regime
    status: Status
    material: bool
    minimal: bool
    reason: str


def target(world: Mapping[str, int], target_name: str) -> int:
    # W7-style synthetic interaction: the target changes only when all three
    # factors are jointly activated. This deliberately defeats a local
    # single-factor detector at the all-zero baseline.
    if target_name == 'interaction':
        return int(world.get('source', 0) and world.get('assumption', 0) and world.get('transform', 0))
    if target_name == 'source_only':
        return world.get('source', 0)
    if target_name == 'sum':
        return world.get('source', 0) + world.get('assumption', 0) + world.get('transform', 0)
    raise ValueError(target_name)


def intervene(baseline: Mapping[str, int], factors: FrozenSet[str], value: int = 1) -> Mapping[str, int]:
    w = dict(baseline)
    for f in factors:
        w[f] = value
    return w


def joint_materiality(baseline: Mapping[str, int], factors: FrozenSet[str], target_name: str) -> bool:
    if not factors:
        return False
    base = target(baseline, target_name)
    changed = target(intervene(baseline, factors), target_name)
    return changed != base


def minimal_joint_sets(baseline: Mapping[str, int], available: FrozenSet[str], target_name: str) -> FrozenSet[FrozenSet[str]]:
    material = []
    items = sorted(available)
    for r in range(1, len(items)+1):
        for combo in combinations(items, r):
            s = frozenset(combo)
            if joint_materiality(baseline, s, target_name):
                material.append(s)
    mins = [s for s in material if not any(t < s for t in material)]
    return frozenset(mins)


def local_single_factor_detector(baseline: Mapping[str, int], available: FrozenSet[str], target_name: str) -> FrozenSet[str]:
    return frozenset(f for f in available if joint_materiality(baseline, frozenset({f}), target_name))


def assess_joint_dependency(baseline: Mapping[str, int], available: FrozenSet[str], target_name: str, scope: Scope, regime: Regime) -> DependencyAssessment:
    mins = minimal_joint_sets(baseline, available, target_name)
    if not mins:
        return DependencyAssessment(frozenset(), target_name, scope, regime, Status.UNKNOWN, False, False, 'no demonstrated material intervention set under this baseline')
    # The assessment is CONDITIONAL because materiality is relative to the declared baseline/intervention model.
    factors = min(mins, key=lambda s: (len(s), tuple(sorted(s))))
    return DependencyAssessment(factors, target_name, scope, regime, Status.CONDITIONAL, True, True,
                                'minimal jointly material factor set under declared intervention model')


def run_tests() -> int:
    s = Scope('S','synthetic','fixed')
    r = Regime('G', ('deterministic-intervention',))
    available = frozenset({'source','assumption','transform'})
    baseline = {'source':0,'assumption':0,'transform':0}
    n = 0

    # 1. The empty intervention is not a dependency factor set.
    assert not joint_materiality(baseline, frozenset(), 'interaction'); n += 1

    # 2. In W7-style interaction, no single factor is locally material at baseline.
    assert local_single_factor_detector(baseline, available, 'interaction') == frozenset(); n += 1

    # 3. All three factors jointly change the target.
    assert joint_materiality(baseline, available, 'interaction'); n += 1

    # 4. The minimal jointly material set is all three factors.
    assert minimal_joint_sets(baseline, available, 'interaction') == frozenset({available}); n += 1

    # 5. The same factors are not automatically a minimal set for another target.
    assert minimal_joint_sets(baseline, available, 'source_only') == frozenset({frozenset({'source'})}); n += 1

    # 6. Dependency assessment retains target/scope/regime and is conditional on the intervention model.
    a = assess_joint_dependency(baseline, available, 'interaction', s, r)
    assert a.status is Status.CONDITIONAL and a.factors == available and a.scope == s and a.regime == r; n += 1

    # 7. A single-factor detector is incomplete for the W7 interaction.
    assert not local_single_factor_detector(baseline, available, 'interaction') and a.material; n += 1

    # 8. Minimality means no proper subset is material under the same baseline.
    for f in available:
        assert not joint_materiality(baseline, available - {f}, 'interaction')
    n += 1

    # 9. Intervention order is irrelevant for a simultaneous set intervention,
    # but sequential intervention is a different operation and must not be conflated.
    w1 = intervene(baseline, frozenset({'source','assumption'}))
    w2 = intervene(w1, frozenset({'transform'}))
    assert target(w2, 'interaction') == 1
    n += 1

    # 10. Scope/regime remain part of the dependency assessment; the same
    # mathematical factor set is not silently transferred to another context.
    s2 = Scope('S2','other-population','fixed')
    a2 = assess_joint_dependency(baseline, available, 'interaction', s2, r)
    assert a2.scope == s2 and a2.status is Status.CONDITIONAL
    n += 1

    return n

if __name__ == '__main__':
    n = run_tests()
    print(f'R604.8 multi-factor dependency tests: {n}/{n} passed')
