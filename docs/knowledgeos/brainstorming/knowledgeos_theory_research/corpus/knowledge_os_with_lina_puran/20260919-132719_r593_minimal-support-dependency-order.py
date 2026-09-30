"""
KnowledgeOS R593 — Minimal Support and Dependency Order

Finite executable benchmark.

Research question:
How can KnowledgeOS determine the true minimal support of a dependency,
and therefore its dependency order, without confusing representation
with the actual sufficient support?

Tests:
- AND dependencies
- alternative minimal explanations
- redundant factors
- 3-way interactions
- XOR
- minimality/sufficiency evidence
- false-minimality adversarial case
- ML candidate vs verified support
"""
from dataclasses import dataclass
from itertools import combinations

def minimal_supports(factors, target, evaluator):
    sufficient = []
    factors = list(factors)
    for r in range(1, len(factors) + 1):
        for c in combinations(factors, r):
            s = frozenset(c)
            if evaluator(s, target):
                sufficient.append(s)
    return sorted(
        [s for s in sufficient if not any(t < s for t in sufficient)],
        key=lambda x: (len(x), sorted(x))
    )

def dependency_order(factors, target, evaluator):
    supports = minimal_supports(factors, target, evaluator)
    return max((len(s) for s in supports), default=0), supports

and_eval = lambda s,z: z=="Z" and {"A","B"} <= set(s)
order, supports = dependency_order(["A","B","C"],"Z",and_eval)
assert order == 2 and supports == [frozenset({"A","B"})]

or_eval = lambda s,z: z=="Z" and (
    {"A","B"} <= set(s) or {"C","D"} <= set(s)
)
order, supports = dependency_order(["A","B","C","D"],"Z",or_eval)
assert order == 2
assert set(supports)=={frozenset({"A","B"}),frozenset({"C","D"})}

triple_eval = lambda s,z: z=="Z" and {"A","B","C"} <= set(s)
order, supports = dependency_order(["A","B","C","D"],"Z",triple_eval)
assert order == 3

xor_eval = lambda s,z: z=="Z" and len({"A","B"} & set(s)) == 1
order, supports = dependency_order(["A","B"],"Z",xor_eval)
assert order == 1 and set(supports)=={
    frozenset({"A"}),frozenset({"B"})
}

@dataclass(frozen=True)
class MinimalSupportEvidence:
    supports_exhaustively_enumerated: bool
    sufficiency_verified: bool
    minimality_verified: bool
    scope_match: bool
    target_match: bool
    contract_match: bool

def assess_minimal_supports(e):
    return "ESTABLISHED" if (
        e.supports_exhaustively_enumerated
        and e.sufficiency_verified
        and e.minimality_verified
        and e.scope_match
        and e.target_match
        and e.contract_match
    ) else "UNKNOWN"

assert assess_minimal_supports(
    MinimalSupportEvidence(True,True,True,True,True,True)
) == "ESTABLISHED"

ground_truth = lambda s,z: z=="Z" and (
    {"A","B"} <= set(s) or {"C","D","E"} <= set(s)
)
true_order, _ = dependency_order(
    ["A","B","C","D","E"],"Z",ground_truth
)
assert true_order == 3

faulty = lambda s,z: z=="Z" and (
    {"A","B"} <= set(s) or {"C","D"} <= set(s)
)
faulty_order, _ = dependency_order(
    ["A","B","C","D","E"],"Z",faulty
)
assert faulty_order == 2
assert faulty_order < true_order

def assess_ml_candidate(candidate_supports, verified_supports):
    return "CONFIRMED" if set(candidate_supports)==set(verified_supports) else "CANDIDATE_ONLY"

assert assess_ml_candidate(
    [frozenset({"A","B"})],
    [frozenset({"A","B"}),frozenset({"C","D","E"})]
) == "CANDIDATE_ONLY"

print("R593 — Minimal Support and Dependency Order")
print("AND minimal support: PASS")
print("Alternative minimal supports: PASS")
print("Redundant factor elimination: PASS")
print("Three-way dependency order: PASS")
print("XOR/cancellation representation: PASS")
print("Minimality/sufficiency evidence gating: PASS")
print("False-minimality adversarial case: PASS")
print("ML candidate remains candidate-only: PASS")
print("RESULT: dependency order must be computed from verified minimal supports,",
      "not from arbitrary dependency representation.")
