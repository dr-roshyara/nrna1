"""
KnowledgeOS R582 — Selective Revalidation Impact Closure

Finite executable reference model.
It tests graph closure given an established dependency relation.
It does NOT prove that the dependency relation itself is complete.
"""

from dataclasses import dataclass
from collections import defaultdict, deque

@dataclass(frozen=True)
class DependencyEdge:
    source: str
    target: str
    kind: str
    material: bool = True
    established: bool = True

def selective_impact(changed, edges):
    adj = defaultdict(list)
    for e in edges:
        if e.established and e.material:
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

edges = [
    DependencyEdge("E1", "C1", "evidence_dependency"),
    DependencyEdge("C1", "C3", "certificate_dependency"),
    DependencyEdge("E2", "C2", "evidence_dependency"),
]

assert selective_impact({"E1"}, edges) == {"C1", "C3"}
assert selective_impact({"E2"}, edges) == {"C2"}
assert selective_impact({"E3"}, edges) == set()

# Non-material relationships do not trigger revalidation.
edges_nonmaterial = edges + [
    DependencyEdge("E1", "C4", "observed_similarity", material=False)
]
assert selective_impact({"E1"}, edges_nonmaterial) == {"C1", "C3"}

# Critical limitation: missing dependencies cannot be recovered by graph traversal.
incomplete_edges = [
    DependencyEdge("E1", "C1", "evidence_dependency")
]
assert selective_impact({"E1"}, incomplete_edges) == {"C1"}

# If E1 actually affects C5 but the edge is absent, closure cannot find C5.
hidden_actual_dependency = ("E1", "C5")
assert "C5" not in selective_impact({"E1"}, incomplete_edges)

print("R582 Selective Revalidation Impact Closure: PASS")
print("Established dependency closure tests: PASS")
print("Materiality filtering tests: PASS")
print("Missing-edge limitation demonstrated: PASS")
print("Evidence class: finite executable reference model")
print("Important: graph closure is complete only relative to a complete dependency relation.")
print("Saved executable reference: /mnt/data/knowledgeos_r582_selective_revalidation_impact.py")
