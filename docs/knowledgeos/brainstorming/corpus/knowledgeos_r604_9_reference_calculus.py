from __future__ import annotations
from dataclasses import dataclass
from enum import Enum
from itertools import combinations
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
class InterventionSpecification:
    """A declared contrast from a baseline. Values, not only factor names, matter."""
    baseline: Mapping[str, int]
    assignments: Mapping[str, int]

@dataclass(frozen=True)
class DependencyAssessment:
    factor_sets: FrozenSet[FrozenSet[str]]
    target: str
    scope: Scope
    regime: Regime
    status: Status
    reason: str


def target(world: Mapping[str, int], name: str) -> int:
    a,b,c,d = (world.get(k,0) for k in ('A','B','C','D'))
    if name == 'AND3':
        return a & b & c
    if name == 'OR_OF_PAIRS':
        return int((a & b) or (c & d))
    if name == 'XOR2':
        return a ^ b
    if name == 'REDUNDANT':
        return int(a and b)  # C is deliberately irrelevant
    if name == 'SOURCE_ONLY':
        return a
    raise ValueError(name)


def apply_intervention(spec: InterventionSpecification, factors: FrozenSet[str]) -> Mapping[str, int]:
    w = dict(spec.baseline)
    for f in factors:
        if f in spec.assignments:
            w[f] = spec.assignments[f]
    return w


def material(spec: InterventionSpecification, factors: FrozenSet[str], target_name: str) -> bool:
    if not factors:
        return False
    base = target(spec.baseline, target_name)
    changed = target(apply_intervention(spec, factors), target_name)
    return changed != base


def minimal_material_sets(spec: InterventionSpecification, available: FrozenSet[str], target_name: str) -> FrozenSet[FrozenSet[str]]:
    material_sets = []
    items = sorted(available)
    for r in range(1, len(items)+1):
        for combo in combinations(items, r):
            s = frozenset(combo)
            if material(spec, s, target_name):
                material_sets.append(s)
    mins = [s for s in material_sets if not any(t < s for t in material_sets)]
    return frozenset(mins)


def local_detector(spec: InterventionSpecification, available: FrozenSet[str], target_name: str) -> FrozenSet[str]:
    return frozenset(f for f in available if material(spec, frozenset({f}), target_name))


def assess(spec: InterventionSpecification, available: FrozenSet[str], target_name: str, scope: Scope, regime: Regime) -> DependencyAssessment:
    mins = minimal_material_sets(spec, available, target_name)
    if not mins:
        return DependencyAssessment(frozenset(), target_name, scope, regime, Status.UNKNOWN,
                                    'no demonstrated material intervention set under this declared contrast')
    return DependencyAssessment(mins, target_name, scope, regime, Status.CONDITIONAL,
                                'minimal material intervention sets under the declared baseline and assignments')


def run_tests() -> int:
    s = Scope('S','synthetic','fixed')
    r = Regime('G', ('deterministic-intervention','finite-domain'))
    n = 0

    # 1. R604.8-style AND3: all three are jointly material, but no singleton is.
    spec = InterventionSpecification({'A':0,'B':0,'C':0}, {'A':1,'B':1,'C':1})
    avail = frozenset({'A','B','C'})
    assert local_detector(spec, avail, 'AND3') == frozenset(); n += 1
    assert minimal_material_sets(spec, avail, 'AND3') == frozenset({avail}); n += 1

    # 2. Alternative minimal explanations: OR of two independent pairs.
    # The full intervention {A,B,C,D} is material, but it is NOT minimal.
    spec2 = InterventionSpecification({'A':0,'B':0,'C':0,'D':0}, {'A':1,'B':1,'C':1,'D':1})
    avail2 = frozenset({'A','B','C','D'})
    assert minimal_material_sets(spec2, avail2, 'OR_OF_PAIRS') == frozenset({frozenset({'A','B'}), frozenset({'C','D'})}); n += 1

    # 3. Redundant factor: C is not part of any minimal explanation.
    spec3 = InterventionSpecification({'A':0,'B':0,'C':0}, {'A':1,'B':1,'C':1})
    assert minimal_material_sets(spec3, frozenset({'A','B','C'}), 'REDUNDANT') == frozenset({frozenset({'A','B'})}); n += 1

    # 4. Target-relative dependency: SOURCE_ONLY has a different minimal set.
    assert minimal_material_sets(spec3, frozenset({'A','B','C'}), 'SOURCE_ONLY') == frozenset({frozenset({'A'})}); n += 1

    # 5. Intervention values matter: changing A to 0 is not an intervention if baseline is already 0.
    noop = InterventionSpecification({'A':0,'B':0}, {'A':0,'B':1})
    assert not material(noop, frozenset({'A'}), 'SOURCE_ONLY'); n += 1

    # 6. XOR shows that 'multi-factor interaction' must not be conflated with
    # 'no single-factor materiality': each singleton is material at 00.
    xor = InterventionSpecification({'A':0,'B':0}, {'A':1,'B':1})
    assert local_detector(xor, frozenset({'A','B'}), 'XOR2') == frozenset({'A','B'}); n += 1
    assert not material(xor, frozenset({'A','B'}), 'XOR2'); n += 1

    # 7. Scope/regime are retained and assessment remains CONDITIONAL.
    a = assess(spec2, avail2, 'OR_OF_PAIRS', s, r)
    assert a.status is Status.CONDITIONAL and len(a.factor_sets) == 2 and a.scope == s and a.regime == r; n += 1

    # 8. No available material set means UNKNOWN, not FAIL or INDEPENDENT.
    impossible = InterventionSpecification({'A':0,'B':0}, {'A':1,'B':1})
    assert assess(impossible, frozenset({'A','B'}), 'AND3', s, r).status is Status.UNKNOWN; n += 1

    # 9. Empty available factor set cannot establish independence.
    assert assess(impossible, frozenset(), 'SOURCE_ONLY', s, r).status is Status.UNKNOWN; n += 1

    # 10. A minimal set is minimal only relative to the declared contrast and target;
    # changing the target can change the explanation family.
    assert minimal_material_sets(spec2, avail2, 'SOURCE_ONLY') == frozenset({frozenset({'A'})}); n += 1

    return n

if __name__ == '__main__':
    n = run_tests()
    print(f'R604.9 dependency stress tests: {n}/{n} passed')
