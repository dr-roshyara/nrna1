"""
KnowledgeOS R592 — Dependency-Order Bound Soundness

Research question:
When may KnowledgeOS legitimately assert ord(D) <= k?

Finite executable evidence only. The benchmark tests:
1. false order bounds can create false closure;
2. exhaustive closed finite universes can establish a bound;
3. verified complete generators can establish a bound;
4. incomplete/unverified bounds remain UNKNOWN;
5. ML may propose a bound but cannot certify it;
6. scope/target/regime mismatch blocks certification.
"""
from dataclasses import dataclass
from math import comb

@dataclass(frozen=True)
class Dependency:
    support: frozenset
    target: str

@dataclass(frozen=True)
class OrderBoundEvidence:
    method: str
    scope: str
    bound_k: int
    universe_closed: bool
    generator_sound: bool
    generator_complete: bool
    exhaustive: bool
    contract_match: bool
    target_match: bool
    regime_match: bool

def assess_order_bound(e):
    if not (e.contract_match and e.target_match and e.regime_match):
        return "UNKNOWN"
    if e.method == "FINITE_EXPLICIT":
        return "ESTABLISHED" if e.universe_closed and e.exhaustive else "UNKNOWN"
    if e.method == "VERIFIED_GENERATOR":
        return ("ESTABLISHED"
                if e.universe_closed and e.generator_sound and e.generator_complete
                else "UNKNOWN")
    if e.method == "FORMAL_CONSTRAINT":
        return "ESTABLISHED" if e.universe_closed and e.generator_sound else "UNKNOWN"
    if e.method == "ML":
        return "UNKNOWN"
    return "UNKNOWN"

def closure_from_order_bound(edges, k, evidence):
    status = assess_order_bound(evidence)
    if status != "ESTABLISHED":
        return "UNKNOWN"
    if any(len(d.support) > k for d in edges):
        return "UNSAFE_FALSE_BOUND"
    return "ESTABLISHED"

triple = Dependency(frozenset({"A","B","C"}), "Z")
finite_evidence = OrderBoundEvidence(
    "FINITE_EXPLICIT", "A,B,C", 2, True, True, True, True, True, True, True
)
assert closure_from_order_bound([triple], 2, finite_evidence) == "UNSAFE_FALSE_BOUND"

pair_edges = [
    Dependency(frozenset({"A","B"}), "P"),
    Dependency(frozenset({"B","C"}), "Q"),
]
assert closure_from_order_bound(pair_edges, 2, finite_evidence) == "ESTABLISHED"

unverified = OrderBoundEvidence(
    "FORMAL_CONSTRAINT", "A,B,C", 2, False, False, False, False, True, True, True
)
assert closure_from_order_bound([pair_edges[0]], 2, unverified) == "UNKNOWN"

generator_evidence = OrderBoundEvidence(
    "VERIFIED_GENERATOR", "A,B,C", 2, True, True, True, False, True, True, True
)
assert closure_from_order_bound(pair_edges, 2, generator_evidence) == "ESTABLISHED"

incomplete_generator = OrderBoundEvidence(
    "VERIFIED_GENERATOR", "A,B,C", 2, True, True, False, False, True, True, True
)
assert closure_from_order_bound(pair_edges, 2, incomplete_generator) == "UNKNOWN"

ml_evidence = OrderBoundEvidence(
    "ML", "A,B,C", 2, True, True, True, False, True, True, True
)
assert assess_order_bound(ml_evidence) == "UNKNOWN"

bad_scope = OrderBoundEvidence(
    "FINITE_EXPLICIT", "different-scope", 2, True, True, True, True, False, True, True
)
assert assess_order_bound(bad_scope) == "UNKNOWN"

complexity = []
for n in range(3, 11):
    full = 2**n - 1
    upto2 = sum(comb(n,r) for r in range(1,3))
    upto3 = sum(comb(n,r) for r in range(1,4))
    complexity.append((n, full, upto2, upto3))

for r in range(3, 6):
    hidden = Dependency(frozenset(f"x{i}" for i in range(r)), "Z")
    assert closure_from_order_bound([hidden], 2, finite_evidence) == "UNSAFE_FALSE_BOUND"

print("R592 — Dependency-Order Bound Soundness")
print("False k=2 bound with hidden triple dependency: PASS")
print("Closed exhaustive universe establishes true bound: PASS")
print("Unverified bound -> UNKNOWN: PASS")
print("Verified complete generator establishes bound: PASS")
print("Incomplete generator -> UNKNOWN: PASS")
print("ML cannot certify universal order bound: PASS")
print("Scope/target/regime mismatch -> UNKNOWN: PASS")
print("Hidden order 3..5 defeats false k=2 bound: PASS")
print("Complexity table (n, all, <=2, <=3):", complexity)
print("RESULT: An order bound is admissible only when evidence excludes all",
      "relevant higher-order dependencies in the declared scope.")
