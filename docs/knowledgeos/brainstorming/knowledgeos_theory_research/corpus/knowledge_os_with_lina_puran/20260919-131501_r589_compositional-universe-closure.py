
from dataclasses import dataclass
from itertools import product
from typing import FrozenSet, Iterable, Tuple

# R589 — Compositional Universe Closure Benchmark
#
# Question:
# Does closure of U1 and closure of U2 compose into closure of U1 ∪ U2?
#
# Evidence class: finite executable reference model.
# This is not a universal theorem for arbitrary real-world universes.

@dataclass(frozen=True)
class Edge:
    source: str
    target: str
    kind: str = "dependency"
    material: bool = True

@dataclass(frozen=True)
class ClosureCertificate:
    universe: FrozenSet[Edge]
    scope: str
    target: str
    contract: str
    temporal_scope: str
    established: bool = True

@dataclass(frozen=True)
class CompositionResult:
    status: str  # ESTABLISHED / CONDITIONAL / UNKNOWN
    reason: str

def material(edges: Iterable[Edge]) -> FrozenSet[Edge]:
    return frozenset(e for e in edges if e.material)

def same_claim(a: ClosureCertificate, b: ClosureCertificate) -> bool:
    return (
        a.scope == b.scope
        and a.target == b.target
        and a.contract == b.contract
        and a.temporal_scope == b.temporal_scope
    )

def cross_boundary_edges(
    u1: FrozenSet[Edge], u2: FrozenSet[Edge], ground_truth: FrozenSet[Edge]
) -> FrozenSet[Edge]:
    nodes1 = {e.source for e in u1} | {e.target for e in u1}
    nodes2 = {e.source for e in u2} | {e.target for e in u2}
    union = material(u1 | u2)
    return frozenset(
        e for e in material(ground_truth)
        if e not in union
        and ((e.source in nodes1 and e.target in nodes2)
             or (e.source in nodes2 and e.target in nodes1))
    )

def compose_closure(
    c1: ClosureCertificate,
    c2: ClosureCertificate,
    ground_truth_union: FrozenSet[Edge],
    *,
    cross_boundary_verified: bool,
    overlap_compatible: bool,
) -> CompositionResult:
    # Individual certificates must themselves be established.
    if not c1.established or not c2.established:
        return CompositionResult("UNKNOWN", "An input closure certificate is not established")

    # Their claims must be compatible before composition.
    if not same_claim(c1, c2):
        return CompositionResult(
            "UNKNOWN",
            "Scope/target/contract/temporal claims are not aligned"
        )

    if not overlap_compatible:
        return CompositionResult(
            "UNKNOWN",
            "Overlapping universes have incompatible boundary semantics"
        )

    union = material(c1.universe | c2.universe)
    missing = material(ground_truth_union) - union

    # This is the benchmark oracle for cross-boundary completeness.
    # Production KnowledgeOS does not receive ground truth.
    if missing:
        if cross_boundary_verified:
            # An explicit verification could only be sound if it actually
            # accounts for the missing relation. In this finite benchmark,
            # a verified cross-boundary claim means no missing relation.
            return CompositionResult(
                "UNKNOWN",
                "Benchmark inconsistency: cross-boundary verification contradicts hidden relation"
            )
        return CompositionResult(
            "UNKNOWN",
            "Cross-boundary dependency exists outside the component universes"
        )

    if not cross_boundary_verified:
        return CompositionResult(
            "CONDITIONAL",
            "Component closure does not by itself establish cross-boundary closure"
        )

    return CompositionResult(
        "ESTABLISHED",
        "Component universes and cross-boundary completeness are verified"
    )

def make_case(name, u1, u2, gt, cross_verified, overlap=True,
              same_claims=True):
    base = dict(
        scope="production",
        target="certificate-impact",
        contract="dependency-v1",
        temporal_scope="2026-09-19",
    )
    c1 = ClosureCertificate(frozenset(u1), **base)
    if same_claims:
        c2 = ClosureCertificate(frozenset(u2), **base)
    else:
        c2 = ClosureCertificate(
            frozenset(u2),
            scope="development",
            target="certificate-impact",
            contract="dependency-v1",
            temporal_scope="2026-09-19",
        )
    return name, c1, c2, frozenset(gt), cross_verified, overlap

def run_adversarial_cases():
    E1 = Edge("A", "B")
    E2 = Edge("C", "D")
    CROSS = Edge("B", "C", "cross_boundary")

    cases = [
        make_case(
            "C1 Independent closed universes",
            [E1], [E2], [E1, E2], True
        ),
        make_case(
            "C2 Hidden cross-boundary dependency",
            [E1], [E2], [E1, E2, CROSS], False
        ),
        make_case(
            "C3 Cross-boundary verification present",
            [E1], [E2], [E1, E2], True
        ),
        make_case(
            "C4 Cross-boundary verification absent but no hidden edge",
            [E1], [E2], [E1, E2], False
        ),
        make_case(
            "C5 Incompatible scopes",
            [E1], [E2], [E1, E2], True, same_claims=False
        ),
        make_case(
            "C6 Incompatible overlap semantics",
            [E1], [E2], [E1, E2], True, overlap=False
        ),
    ]

    results = []
    for name, c1, c2, gt, cross_verified, overlap in cases:
        result = compose_closure(
            c1, c2, gt,
            cross_boundary_verified=cross_verified,
            overlap_compatible=overlap,
        )
        results.append((name, result.status, result.reason))

    expected = {
        "C1 Independent closed universes": "ESTABLISHED",
        "C2 Hidden cross-boundary dependency": "UNKNOWN",
        "C3 Cross-boundary verification present": "ESTABLISHED",
        "C4 Cross-boundary verification absent but no hidden edge": "CONDITIONAL",
        "C5 Incompatible scopes": "UNKNOWN",
        "C6 Incompatible overlap semantics": "UNKNOWN",
    }

    for name, status, _ in results:
        assert status == expected[name], (name, status, expected[name])

    return results

def exhaustive_two_component_test():
    # Two possible internal edges plus one possible cross-boundary edge.
    e1 = Edge("A", "B")
    e2 = Edge("C", "D")
    cross = Edge("B", "C", "cross_boundary")
    universe = [e1, e2, cross]

    cases = 0
    failures = 0
    unsafe_established = 0

    for bits in product((False, True), repeat=3):
        gt = frozenset(e for e, b in zip(universe, bits) if b)

        # Component certificates only contain their respective internal
        # portions. They may both be individually closed in their own scopes.
        u1 = frozenset(e1 for _ in [0] if bits[0])
        u2 = frozenset(e2 for _ in [0] if bits[1])

        base = dict(
            scope="production",
            target="certificate-impact",
            contract="dependency-v1",
            temporal_scope="2026-09-19",
        )
        c1 = ClosureCertificate(u1, **base)
        c2 = ClosureCertificate(u2, **base)

        # Cross-boundary verification is true exactly when no cross edge exists.
        cross_verified = not bits[2]

        result = compose_closure(
            c1, c2, gt,
            cross_boundary_verified=cross_verified,
            overlap_compatible=True,
        )

        # Each component certificate is locally complete for its own
        # projected universe. The only unresolved question is whether the
        # union has cross-boundary dependencies.
        expected = "UNKNOWN" if bits[2] else "ESTABLISHED"

        cases += 1
        if result.status != expected:
            failures += 1

        # Never accept ESTABLISHED if a hidden cross-boundary edge exists.
        if result.status == "ESTABLISHED" and bits[2]:
            unsafe_established += 1

    return cases, failures, unsafe_established

def test_noncommutative_scope_composition():
    # A practical temporal example:
    # U1 is closed for t1, U2 is closed for t2. Their union is not a single
    # closed claim until temporal alignment is established.
    e1 = Edge("A", "B")
    e2 = Edge("C", "D")
    c1 = ClosureCertificate(
        frozenset([e1]), "production", "impact", "v1", "2026-09-18"
    )
    c2 = ClosureCertificate(
        frozenset([e2]), "production", "impact", "v1", "2026-09-19"
    )
    r = compose_closure(
        c1, c2, frozenset([e1, e2]),
        cross_boundary_verified=True,
        overlap_compatible=True,
    )
    assert r.status == "UNKNOWN"

if __name__ == "__main__":
    results = run_adversarial_cases()
    cases, failures, unsafe = exhaustive_two_component_test()
    test_noncommutative_scope_composition()

    established = sum(s == "ESTABLISHED" for _, s, _ in results)
    conditional = sum(s == "CONDITIONAL" for _, s, _ in results)
    unknown = sum(s == "UNKNOWN" for _, s, _ in results)

    print("R589 Compositional Universe Closure Benchmark: PASS")
    print(f"Adversarial composition cases: {len(results)}")
    print(f"ESTABLISHED: {established}")
    print(f"CONDITIONAL: {conditional}")
    print(f"UNKNOWN: {unknown}")
    print(f"Exhaustive component cases: {cases}")
    print(f"Exhaustive composition failures: {failures}")
    print(f"Unsafe ESTABLISHED results with hidden cross-boundary edge: {unsafe}")
    print("Cross-boundary dependency requirement: PASS")
    print("Scope/target/contract alignment: PASS")
    print("Temporal alignment: PASS")
    print("Overlap compatibility: PASS")
    print("No automatic closure under union: PASS")
    print("Evidence class: finite executable reference model")
    print("Not a universal theorem for arbitrary real-world dependency systems.")

    assert failures == 0
    assert unsafe == 0
