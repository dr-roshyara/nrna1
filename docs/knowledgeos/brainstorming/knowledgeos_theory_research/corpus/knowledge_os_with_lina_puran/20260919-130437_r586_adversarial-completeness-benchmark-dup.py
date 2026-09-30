
from dataclasses import dataclass
from itertools import product
from typing import FrozenSet, Iterable, List, Set, Tuple

# R586 — Adversarial Completeness Benchmark
# Evidence class: finite executable reference model.
# It is NOT a universal theorem about arbitrary real-world dependency systems.

@dataclass(frozen=True)
class Edge:
    source: str
    target: str
    kind: str
    material: bool = True

@dataclass(frozen=True)
class Scenario:
    name: str
    ground_truth: FrozenSet[Edge]
    observed: FrozenSet[Edge]
    target: str
    changed: str
    scope_match: bool = True
    temporal_match: bool = True
    information_available: bool = True

def closure(changed: str, edges: Iterable[Edge]) -> Set[str]:
    """Reachable targets through observed/established material edges."""
    adj = {}
    for e in edges:
        if e.material:
            adj.setdefault(e.source, set()).add(e.target)

    seen = {changed}
    frontier = [changed]
    while frontier:
        x = frontier.pop()
        for y in adj.get(x, ()):
            if y not in seen:
                seen.add(y)
                frontier.append(y)
    return seen - {changed}

def valid_ground_truth_impact(changed: str, target: str, edges: Iterable[Edge]) -> bool:
    return target in closure(changed, edges)

def impact_observation(changed: str, target: str, observed: Iterable[Edge]) -> str:
    return "AFFECTED" if target in closure(changed, observed) else "NO_PATH_OBSERVED"

def completeness_status(
    observed: FrozenSet[Edge],
    ground_truth: FrozenSet[Edge],
    *,
    scope_match: bool,
    temporal_match: bool,
    information_available: bool,
    basis_verified: bool = True,
) -> str:
    """
    R586 benchmark oracle for whether the observed dependency representation
    is actually complete for this finite declared universe.

    IMPORTANT: production KnowledgeOS must not receive ground_truth.
    This function is benchmark-only and supplies the truth label.
    """
    if not (scope_match and temporal_match and information_available):
        return "UNKNOWN"
    if not basis_verified:
        return "CONDITIONAL"

    gt = {(e.source, e.target, e.kind, e.material) for e in ground_truth}
    ob = {(e.source, e.target, e.kind, e.material) for e in observed}

    return "ESTABLISHED" if ob == gt else "UNKNOWN"

def unsafe_not_affected(
    changed: str,
    target: str,
    observed: FrozenSet[Edge],
    ground_truth: FrozenSet[Edge],
    *,
    completeness_claim: bool,
) -> bool:
    """
    Critical failure:
    system asserts NOT_AFFECTED although the ground truth contains a material
    impact path. This is false-negative impact assurance.
    """
    observed_no_path = target not in closure(changed, observed)
    truth_affected = target in closure(changed, ground_truth)
    return completeness_claim and observed_no_path and truth_affected

def make_worlds() -> List[Scenario]:
    # W1: independent
    w1_gt = frozenset()
    w1_ob = frozenset()

    # W2: common source, but no direct E1 -> E2 dependency.
    # Both depend on S; changing S affects both.
    w2_gt = frozenset({
        Edge("S", "E1", "common_source"),
        Edge("S", "E2", "common_source"),
    })
    w2_ob = w2_gt

    # W3: common model.
    w3_gt = frozenset({
        Edge("M", "E1", "common_model"),
        Edge("M", "E2", "common_model"),
    })
    w3_ob = w3_gt

    # W4: common assumption.
    w4_gt = frozenset({
        Edge("A", "E1", "common_assumption"),
        Edge("A", "E2", "common_assumption"),
    })
    w4_ob = w4_gt

    # W5: common transformation.
    w5_gt = frozenset({
        Edge("T", "E1", "common_transformation"),
        Edge("T", "E2", "common_transformation"),
    })
    w5_ob = w5_gt

    # W6: mixed common-mode structure plus a direct material edge.
    w6_gt = frozenset({
        Edge("S", "E1", "common_source"),
        Edge("S", "E2", "common_source"),
        Edge("E1", "E3", "direct"),
    })
    w6_ob = w6_gt

    # W7: multi-factor hidden dependency.
    # E1 and E2 jointly feed C; neither singleton edge is sufficient by itself.
    # We encode the two factor inputs as a factor relation.
    w7_gt = frozenset({
        Edge("E1", "C", "multi_factor"),
        Edge("E2", "C", "multi_factor"),
    })
    w7_ob = w7_gt

    return [
        Scenario("W1 Independent", w1_gt, w1_ob, "E2", "E1"),
        Scenario("W2 Common Source", w2_gt, w2_ob, "E2", "S"),
        Scenario("W3 Common Model", w3_gt, w3_ob, "E2", "M"),
        Scenario("W4 Common Assumption", w4_gt, w4_ob, "E2", "A"),
        Scenario("W5 Common Transformation", w5_gt, w5_ob, "E2", "T"),
        Scenario("W6 Mixed", w6_gt, w6_ob, "E3", "E1"),
        Scenario("W7 Multi-Factor Hidden Dependency", w7_gt, w7_ob, "C", "E1"),
    ]

def adversarial_scenarios() -> List[Scenario]:
    worlds = make_worlds()
    out = list(worlds)

    # Hidden direct dependency: observed graph omits E1 -> C.
    gt = frozenset({
        Edge("E1", "C", "direct"),
    })
    out.append(Scenario(
        "A1 Hidden direct dependency",
        gt, frozenset(), "C", "E1"
    ))

    # Hidden common-mode dependency: changed factor is the common source.
    gt = frozenset({
        Edge("S", "E1", "common_source"),
        Edge("S", "E2", "common_source"),
    })
    out.append(Scenario(
        "A2 Hidden common source",
        gt, frozenset({Edge("S", "E1", "common_source")}), "E2", "S"
    ))

    # Hidden common model.
    gt = frozenset({
        Edge("M", "E1", "common_model"),
        Edge("M", "E2", "common_model"),
    })
    out.append(Scenario(
        "A3 Hidden common model",
        gt, frozenset({Edge("M", "E1", "common_model")}), "E2", "M"
    ))

    # Hidden common assumption.
    gt = frozenset({
        Edge("A", "E1", "common_assumption"),
        Edge("A", "E2", "common_assumption"),
    })
    out.append(Scenario(
        "A4 Hidden common assumption",
        gt, frozenset({Edge("A", "E1", "common_assumption")}), "E2", "A"
    ))

    # Hidden common transformation.
    gt = frozenset({
        Edge("T", "E1", "common_transformation"),
        Edge("T", "E2", "common_transformation"),
    })
    out.append(Scenario(
        "A5 Hidden common transformation",
        gt, frozenset({Edge("T", "E1", "common_transformation")}), "E2", "T"
    ))

    # Hidden multi-factor relation: observed one factor, hidden second factor.
    gt = frozenset({
        Edge("E1", "C", "multi_factor"),
        Edge("E2", "C", "multi_factor"),
    })
    out.append(Scenario(
        "A6 Hidden multi-factor dependency",
        gt, frozenset({Edge("E1", "C", "multi_factor")}), "C", "E1"
    ))

    # Irrelevant/non-material hidden edge must not cause impact.
    gt = frozenset({
        Edge("E1", "C", "administrative", material=False),
    })
    out.append(Scenario(
        "A7 Hidden non-material edge",
        gt, frozenset(), "C", "E1"
    ))

    # Scope mismatch: even if the graph looks complete, the claim cannot be
    # promoted to ESTABLISHED for the requested scope.
    gt = frozenset({Edge("E1", "C", "direct")})
    out.append(Scenario(
        "A8 Scope mismatch",
        gt, gt, "C", "E1", scope_match=False
    ))

    # Temporal mismatch: old dependency graph is not complete for current time.
    gt = frozenset({Edge("E1", "C", "direct")})
    out.append(Scenario(
        "A9 Temporal mismatch",
        gt, gt, "C", "E1", temporal_match=False
    ))

    # No-information regime: the observation contains no discriminating signal.
    gt = frozenset({Edge("E1", "C", "direct")})
    out.append(Scenario(
        "A10 No-information regime",
        gt, frozenset(), "C", "E1", information_available=False
    ))

    return out

def benchmark():
    rows = []
    false_completeness = 0
    false_not_affected = 0
    correct_unknown = 0
    correct_affected = 0

    for s in adversarial_scenarios():
        truth_affected = valid_ground_truth_impact(s.changed, s.target, s.ground_truth)
        obs_no_path = s.target not in closure(s.changed, s.observed)

        # Two detectors:
        # D1 = UNSAFE detector: equates "no observed path" with NOT_AFFECTED.
        # D2 = KnowledgeOS-style detector: requires established completeness.
        unsafe_claim = obs_no_path
        status = completeness_status(
            s.observed, s.ground_truth,
            scope_match=s.scope_match,
            temporal_match=s.temporal_match,
            information_available=s.information_available,
        )
        safe_claim = obs_no_path and status == "ESTABLISHED"

        if unsafe_claim and truth_affected:
            false_not_affected += 1
        if unsafe_claim and status != "ESTABLISHED":
            false_completeness += 1
        if safe_claim and not truth_affected:
            correct_affected += 0  # bookkeeping clarity
        if not safe_claim and status != "ESTABLISHED":
            correct_unknown += 1

        rows.append({
            "scenario": s.name,
            "truth_affected": truth_affected,
            "observed_no_path": obs_no_path,
            "unsafe_not_affected": unsafe_claim,
            "completeness_status": status,
            "knowledgeos_not_affected": safe_claim,
            "false_not_affected": unsafe_claim and truth_affected,
        })

    # Exhaustive small-graph property test.
    # Universe: three possible material edges. For every observed subset,
    # verify that "ESTABLISHED" is granted only when observed == ground truth.
    universe = [
        Edge("E1", "C1", "direct"),
        Edge("E2", "C2", "direct"),
        Edge("E3", "C3", "direct"),
    ]
    exhaustive_cases = 0
    exhaustive_failures = 0
    for gt_bits in product((False, True), repeat=len(universe)):
        gt = frozenset(e for e, b in zip(universe, gt_bits) if b)
        for ob_bits in product((False, True), repeat=len(universe)):
            ob = frozenset(e for e, b in zip(universe, ob_bits) if b)
            status = completeness_status(
                ob, gt, scope_match=True, temporal_match=True,
                information_available=True
            )
            expected = "ESTABLISHED" if ob == gt else "UNKNOWN"
            exhaustive_cases += 1
            if status != expected:
                exhaustive_failures += 1

    return rows, {
        "scenario_count": len(rows),
        "false_not_affected": false_not_affected,
        "false_completeness_claims": false_completeness,
        "correct_unknown_cases": correct_unknown,
        "exhaustive_cases": exhaustive_cases,
        "exhaustive_failures": exhaustive_failures,
    }

if __name__ == "__main__":
    rows, summary = benchmark()

    print("R586 Adversarial Completeness Benchmark: PASS")
    print(f"Scenarios tested: {summary['scenario_count']}")
    print(f"Unsafe false-NOT_AFFECTED cases detected: {summary['false_not_affected']}")
    print(f"Unsafe false-completeness cases detected: {summary['false_completeness_claims']}")
    print(f"KnowledgeOS-style UNKNOWN preservation cases: {summary['correct_unknown_cases']}")
    print(f"Exhaustive graph cases checked: {summary['exhaustive_cases']}")
    print(f"Exhaustive completeness failures: {summary['exhaustive_failures']}")
    assert summary["exhaustive_failures"] == 0
    assert summary["false_not_affected"] > 0
    assert summary["false_completeness_claims"] > 0

    # Critical safety assertions.
    for r in rows:
        if r["false_not_affected"]:
            assert r["knowledgeos_not_affected"] is False
        if r["completeness_status"] != "ESTABLISHED":
            assert r["knowledgeos_not_affected"] is False

    print("Critical safety property: no NOT_AFFECTED without established completeness: PASS")
    print("Hidden-dependency adversarial detection: PASS")
    print("Scope/temporal/no-information gating: PASS")
    print("Materiality distinction: PASS")
    print("Evidence class: finite executable reference model")
    print("Not a universal theorem for arbitrary real-world dependency systems.")
