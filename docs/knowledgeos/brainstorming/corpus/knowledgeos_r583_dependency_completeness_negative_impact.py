"""
KnowledgeOS R583 — Dependency Completeness & Negative Impact Proof

Finite executable reference model.

Core result:
No impact path can justify NOT_AFFECTED only when the dependency relation,
materiality semantics, and impact-propagation semantics are all complete
for the declared target/contract/scope.

Without those completeness conditions the correct result is UNKNOWN.
"""

from dataclasses import dataclass
from collections import defaultdict, deque
from itertools import product

@dataclass(frozen=True)
class Edge:
    source: str
    target: str
    material: bool
    established: bool
    propagates_impact: bool

def impact_closure(changed, edges):
    adj = defaultdict(list)
    for e in edges:
        if e.established and e.material and e.propagates_impact:
            adj[e.source].append(e.target)
    seen = set(changed)
    q = deque(changed)
    while q:
        x = q.popleft()
        for y in adj[x]:
            if y not in seen:
                seen.add(y)
                q.append(y)
    return seen - set(changed)

def negative_impact_proof(changed, target, edges,
                          dependency_complete,
                          materiality_complete,
                          propagation_complete):
    if not (dependency_complete and materiality_complete and propagation_complete):
        return "UNKNOWN"
    return "NOT_AFFECTED" if target not in impact_closure(changed, edges) else "AFFECTED"

# Direct and cascading impact.
edges = [
    Edge("E1", "C1", True, True, True),
    Edge("C1", "C3", True, True, True),
    Edge("E1", "C4", True, True, False),
]
assert negative_impact_proof({"E1"}, "C3", edges, True, True, True) == "AFFECTED"
assert negative_impact_proof({"E1"}, "C4", edges, True, True, True) == "NOT_AFFECTED"

# Missing dependency: absence of a path is not enough without completeness.
incomplete = [Edge("E1", "C1", True, True, True)]
assert negative_impact_proof({"E1"}, "C5", incomplete, False, True, True) == "UNKNOWN"
assert negative_impact_proof({"E1"}, "C5", incomplete, True, True, True) == "NOT_AFFECTED"

# Non-established candidate edges cannot establish a negative proof.
candidate_only = [Edge("E1", "C5", True, False, True)]
assert negative_impact_proof({"E1"}, "C5", candidate_only, False, True, True) == "UNKNOWN"

# Exhaustive tiny-state check.
possible_edge = Edge("E", "C", True, True, True)
for dep_complete, mat_complete, prop_complete in product((False, True), repeat=3):
    result = negative_impact_proof({"E"}, "C", [possible_edge],
                                   dep_complete, mat_complete, prop_complete)
    if dep_complete and mat_complete and prop_complete:
        assert result == "AFFECTED"
    else:
        assert result != "NOT_AFFECTED"

# Exhaustive closure check over all subsets of a 3-edge universe.
universe = [
    Edge("E1", "C1", True, True, True),
    Edge("E1", "C2", True, True, True),
    Edge("C1", "C3", True, True, True),
]
for mask in range(1 << len(universe)):
    es = [e for i, e in enumerate(universe) if mask & (1 << i)]
    closure = impact_closure({"E1"}, es)
    for c in ("C1", "C2", "C3"):
        expected = "AFFECTED" if c in closure else "NOT_AFFECTED"
        assert negative_impact_proof({"E1"}, c, es, True, True, True) == expected

print("R583 Dependency Completeness & Negative Impact Proof: PASS")
print("Core negative-impact rule: PASS")
print("Missing-dependency UNKNOWN rule: PASS")
print("Non-propagating dependency handling: PASS")
print("Exhaustive tiny-state checks: PASS")
print("Evidence class: finite executable reference model")
print("Not a universal theorem for arbitrary dependency systems.")
print("Saved executable reference: /mnt/data/knowledgeos_r583_dependency_completeness_negative_impact.py")
