from __future__ import annotations
from dataclasses import dataclass, field
from enum import Enum
from itertools import product
from typing import Callable, FrozenSet, Iterable, Mapping, Optional, Tuple

class Status(str, Enum):
    PASS='PASS'; FAIL='FAIL'; UNKNOWN='UNKNOWN'; CONDITIONAL='CONDITIONAL'; UNDEFINED='UNDEFINED'; NOT_APPLICABLE='NOT_APPLICABLE'

class DependencyKind(str, Enum):
    DIRECT_CAUSAL='DIRECT_CAUSAL'
    COMMON_MODE='COMMON_MODE'
    PROVENANCE='PROVENANCE'
    TRANSFORMATION='TRANSFORMATION'
    ASSUMPTION='ASSUMPTION'
    MODEL='MODEL'

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
class DependencyEdge:
    src: str
    dst: str
    kind: DependencyKind
    material_targets: FrozenSet[str]
    scope: Scope
    regime: Regime
    provenance: Tuple[str, ...] = ()

@dataclass(frozen=True)
class DependencyGraph:
    nodes: FrozenSet[str]
    edges: Tuple[DependencyEdge, ...] = ()

@dataclass(frozen=True)
class Counterfactual:
    baseline: Mapping[str, int]
    intervention: Mapping[str, int]
    target: str

@dataclass(frozen=True)
class Assessment:
    subject: str
    target: str
    value: bool
    status: Status
    scope: Scope
    regime: Regime
    reason: str


def evaluate_node(node: str, world: Mapping[str, int]) -> int:
    # Synthetic benchmark nodes: E1/E2 are derived from named latent factors.
    if node == 'E1':
        return world['E1_base'] + world.get('source', 0) + world.get('model', 0) + world.get('assumption', 0) + world.get('transform', 0)
    if node == 'E2':
        return world['E2_base'] + world.get('source', 0) + world.get('model', 0) + world.get('assumption', 0) + world.get('transform', 0)
    raise KeyError(node)


def target_value(node: str, world: Mapping[str, int], target: str) -> int:
    if target == 'conclusion':
        return evaluate_node(node, world)
    if target == 'parity':
        return evaluate_node(node, world) % 2
    raise ValueError(target)


def direct_counterfactual_dependency(cf: Counterfactual, src: str, dst: str) -> bool:
    # A direct causal claim is established only if changing src changes dst's target value.
    return target_value(dst, cf.baseline, cf.target) != target_value(dst, cf.intervention, cf.target)


def common_mode_dependency(graph: DependencyGraph, a: str, b: str, target: str, scope: Optional[Scope] = None, regime: Optional[Regime] = None) -> bool:
    # Structural common-mode dependency: both nodes have an incoming edge from the same latent factor.
    def relevant(e: DependencyEdge) -> bool:
        return (e.dst in {a,b} and target in e.material_targets
                and (scope is None or e.scope == scope)
                and (regime is None or e.regime == regime))
    incoming_a = {e.src for e in graph.edges if e.dst == a and relevant(e)}
    incoming_b = {e.src for e in graph.edges if e.dst == b and relevant(e)}
    return bool(incoming_a & incoming_b)


def dependency_closure(graph: DependencyGraph, start: str) -> FrozenSet[str]:
    reached = {start}
    changed = True
    while changed:
        changed = False
        for e in graph.edges:
            if e.src in reached and e.dst not in reached:
                reached.add(e.dst); changed = True
    return frozenset(reached)


def assess_dependency(graph: DependencyGraph, a: str, b: str, target: str, scope: Scope, regime: Regime) -> Assessment:
    if a not in graph.nodes or b not in graph.nodes:
        return Assessment(f'{a}->{b}', target, False, Status.UNDEFINED, scope, regime, 'node absent')
    if a == b:
        return Assessment(f'{a}->{b}', target, False, Status.NOT_APPLICABLE, scope, regime, 'self relation not assessed')
    relevant = [e for e in graph.edges if e.dst == b and e.src == a and target in e.material_targets and e.scope == scope and e.regime == regime]
    if relevant:
        return Assessment(f'{a}->{b}', target, True, Status.PASS, scope, regime, 'explicit material edge')
    if common_mode_dependency(graph, a, b, target, scope, regime):
        return Assessment(f'{a}<->{b}', target, True, Status.PASS, scope, regime, 'shared material dependency factor')
    # Absence of a recorded edge is not proof of independence.
    return Assessment(f'{a}<->{b}', target, False, Status.UNKNOWN, scope, regime, 'no established dependency edge')


def make_world(kind: str) -> tuple[Mapping[str,int], DependencyGraph]:
    base = {'E1_base': 1, 'E2_base': 2, 'source': 0, 'model': 0, 'assumption': 0, 'transform': 0}
    s = Scope('S', 'synthetic', 'fixed')
    r = Regime('G')
    edges = []
    factors = []
    if kind == 'W1':
        pass
    elif kind == 'W2':
        base['source'] = 10; factors.append(('source', DependencyKind.COMMON_MODE))
    elif kind == 'W3':
        base['model'] = 10; factors.append(('model', DependencyKind.MODEL))
    elif kind == 'W4':
        base['assumption'] = 10; factors.append(('assumption', DependencyKind.ASSUMPTION))
    elif kind == 'W5':
        base['transform'] = 10; factors.append(('transform', DependencyKind.TRANSFORMATION))
    elif kind == 'W6':
        base['source'] = 10; base['model'] = 20; factors.extend([('source', DependencyKind.COMMON_MODE), ('model', DependencyKind.MODEL)])
    elif kind == 'W7':
        base['source'] = 10; base['assumption'] = 20; base['transform'] = 30
        factors.extend([('source', DependencyKind.COMMON_MODE), ('assumption', DependencyKind.ASSUMPTION), ('transform', DependencyKind.TRANSFORMATION)])
    else:
        raise ValueError(kind)
    for factor, kind_enum in factors:
        edges.extend([
            DependencyEdge(factor, 'E1', kind_enum, frozenset({'conclusion','parity'}), s, r, (factor,)),
            DependencyEdge(factor, 'E2', kind_enum, frozenset({'conclusion','parity'}), s, r, (factor,)),
        ])
    return base, DependencyGraph(frozenset({'E1','E2'} | {f for f,_ in factors}), tuple(edges))


def ground_truth_common_mode(kind: str) -> bool:
    return kind in {'W2','W3','W4','W5','W6','W7'}


def run_tests() -> int:
    s = Scope('S','synthetic','fixed'); r = Regime('G')
    count = 0

    # 1. Dependency is target-relative.
    g = make_world('W2')[1]
    assert assess_dependency(g, 'E1', 'E2', 'conclusion', s, r).value is True; count += 1

    # 2. W1 has no established dependency, but must not be called proven independent.
    g1 = make_world('W1')[1]
    a1 = assess_dependency(g1, 'E1', 'E2', 'conclusion', s, r)
    assert a1.status is Status.UNKNOWN and a1.value is False; count += 1

    # 3. Common-source dependency is structurally different from direct E1->E2 causation.
    g2 = make_world('W2')[1]
    assert not any(e.src == 'E1' and e.dst == 'E2' for e in g2.edges)
    assert common_mode_dependency(g2, 'E1', 'E2', 'conclusion'); count += 1

    # 4. Direct counterfactual effect is a different relation from common-mode dependency.
    baseline = {'E1_base':1,'E2_base':2,'source':0,'model':0,'assumption':0,'transform':0}
    intervention = dict(baseline); intervention['E2_base'] = 7
    cf = Counterfactual(baseline, intervention, 'conclusion')
    assert direct_counterfactual_dependency(cf, 'E2', 'E1') is False; count += 1

    # 5. W3-W5 are recognized without conflating their dependency kind.
    for kind, expected_kind in [('W3',DependencyKind.MODEL),('W4',DependencyKind.ASSUMPTION),('W5',DependencyKind.TRANSFORMATION)]:
        graph = make_world(kind)[1]
        kinds = {e.kind for e in graph.edges}
        assert expected_kind in kinds and len(kinds) == 1
    count += 1

    # 6. Mixed world has multiple dependency mechanisms.
    gm = make_world('W6')[1]
    assert {e.kind for e in gm.edges} == {DependencyKind.COMMON_MODE, DependencyKind.MODEL}; count += 1

    # 7. Multi-factor world retains all factors; a single-factor detector is incomplete.
    gw7 = make_world('W7')[1]
    assert {e.kind for e in gw7.edges} == {DependencyKind.COMMON_MODE, DependencyKind.ASSUMPTION, DependencyKind.TRANSFORMATION}; count += 1

    # 8. Dependency closure is transitive over established graph edges, but this is graph reachability, not automatic causal truth.
    s2 = Scope('S','synthetic','fixed'); r2 = Regime('G')
    gchain = DependencyGraph(frozenset({'A','B','C'}), (
        DependencyEdge('A','B',DependencyKind.TRANSFORMATION,frozenset({'z'}),s2,r2),
        DependencyEdge('B','C',DependencyKind.TRANSFORMATION,frozenset({'z'}),s2,r2),
    ))
    assert dependency_closure(gchain,'A') == frozenset({'A','B','C'}); count += 1

    # 9. Statistical association alone is not a logical dependency edge.
    # Same observed values can arise without an established structural edge.
    gs = make_world('W1')[1]
    assert not gs.edges and assess_dependency(gs,'E1','E2','conclusion',s,r).status is Status.UNKNOWN; count += 1

    # 10. Scope/regime mismatch blocks transfer of an established edge.
    other_scope = Scope('S2','synthetic-other','fixed')
    graph = make_world('W2')[1]
    assert assess_dependency(graph,'E1','E2','conclusion',other_scope,r).status is Status.UNKNOWN
    count += 1

    return count

if __name__ == '__main__':
    n = run_tests()
    print(f'R604.7 dependency tests: {n}/{n} passed')
