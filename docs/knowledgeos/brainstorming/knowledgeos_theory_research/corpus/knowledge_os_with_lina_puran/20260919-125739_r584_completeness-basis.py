"""
KnowledgeOS R584 — Completeness Basis & Negative Impact Assurance

Finite executable reference model.

Purpose:
Distinguish a declared completeness assumption from an established
completeness basis. A negative impact conclusion is allowed only when
completeness is established for the declared target, scope and contract.

This does not prove completeness for arbitrary real-world dependency systems.
"""

from dataclasses import dataclass
from collections import defaultdict, deque
from itertools import product

@dataclass(frozen=True)
class CompletenessBasis:
    mode: str
    scope: str
    target: str
    contract: str
    coverage_claimed: bool
    independently_verified: bool

@dataclass(frozen=True)
class Edge:
    source: str
    target: str
    material: bool = True
    established: bool = True
    propagates: bool = True

def completeness_status(basis):
    if basis.mode == "NONE" or not basis.coverage_claimed:
        return "UNKNOWN"
    if basis.mode in {"CLOSED_WORLD", "EXHAUSTIVE_MODEL", "VERIFIED_GENERATOR"}:
        return "ESTABLISHED" if basis.independently_verified else "CONDITIONAL"
    return "UNKNOWN"

def closure(changed, edges):
    adj = defaultdict(list)
    for e in edges:
        if e.established and e.material and e.propagates:
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

def impact_assessment(changed, target, edges, basis):
    if target in closure(changed, edges):
        return "AFFECTED"
    if completeness_status(basis) == "ESTABLISHED":
        return "NOT_AFFECTED"
    return "UNKNOWN"

# No completeness basis => no negative proof.
none = CompletenessBasis("NONE", "S", "Z", "C", True, False)
edges = [Edge("E1", "C1")]
assert impact_assessment({"E1"}, "C2", edges, none) == "UNKNOWN"

# Independently verified closed-world contract => negative proof permitted.
closed_verified = CompletenessBasis("CLOSED_WORLD", "S", "Z", "C", True, True)
assert completeness_status(closed_verified) == "ESTABLISHED"
assert impact_assessment({"E1"}, "C2", edges, closed_verified) == "NOT_AFFECTED"

# Unverified completeness claim remains conditional.
closed_unverified = CompletenessBasis("CLOSED_WORLD", "S", "Z", "C", True, False)
assert completeness_status(closed_unverified) == "CONDITIONAL"
assert impact_assessment({"E1"}, "C2", edges, closed_unverified) == "UNKNOWN"

# Exhaustive finite model.
finite_verified = CompletenessBasis("EXHAUSTIVE_MODEL", "finite-S", "Z", "C", True, True)
assert completeness_status(finite_verified) == "ESTABLISHED"

# Verified generator.
generator_verified = CompletenessBasis("VERIFIED_GENERATOR", "S", "Z", "C", True, True)
assert completeness_status(generator_verified) == "ESTABLISHED"

# ML/candidate output alone cannot establish completeness.
candidate = CompletenessBasis("VERIFIED_GENERATOR", "S", "Z", "C", True, False)
assert completeness_status(candidate) == "CONDITIONAL"
assert impact_assessment({"E1"}, "C2", edges, candidate) == "UNKNOWN"

# Completeness is indexed by scope, target and contract.
b1 = CompletenessBasis("CLOSED_WORLD", "scope-A", "target-A", "contract-A", True, True)
b2 = CompletenessBasis("CLOSED_WORLD", "scope-B", "target-B", "contract-B", True, True)
assert (b1.scope, b1.target, b1.contract) != (b2.scope, b2.target, b2.contract)

# Exhaustive finite check.
universe = ["C1", "C2", "C3"]
for allowed in product((False, True), repeat=len(universe)):
    known = {c for c, ok in zip(universe, allowed) if ok}
    es = [Edge("E1", c) for c in known]
    b = CompletenessBasis("EXHAUSTIVE_MODEL", "finite", "Z", "C", True, True)
    for c in universe:
        expected = "AFFECTED" if c in known else "NOT_AFFECTED"
        assert impact_assessment({"E1"}, c, es, b) == expected

print("R584 Completeness Basis & Negative Impact Assurance: PASS")
print("No-basis UNKNOWN rule: PASS")
print("Verified closed-world negative proof: PASS")
print("Unverified completeness remains UNKNOWN: PASS")
print("Exhaustive finite completeness checks: PASS")
print("Scope/target/contract indexing: PASS")
print("Evidence class: finite executable reference model")
print("Not a universal theorem for arbitrary real-world dependency systems.")
print("Saved executable reference: /mnt/data/knowledgeos_r584_completeness_basis.py")
