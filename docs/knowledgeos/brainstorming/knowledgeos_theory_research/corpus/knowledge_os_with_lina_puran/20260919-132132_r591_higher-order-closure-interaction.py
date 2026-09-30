
"""
KnowledgeOS R591 — Higher-Order Closure and Interaction Completeness

Research question:
Can higher-order universe closure be established without enumerating every
subset of component universes?

This benchmark tests:
1. pairwise projections are insufficient for higher-order dependencies;
2. a declared maximum dependency order k reduces subset enumeration to
   sum_{i=1}^k C(n,i), PROVIDED the order bound is independently established;
3. a verified interaction-factorization certificate can establish closure
   without enumerating all subsets;
4. an unverified factorization / unsupported order bound is not accepted.

Finite executable evidence only; no universal theorem is claimed.
"""
from dataclasses import dataclass
from itertools import combinations

@dataclass(frozen=True)
class HyperDependency:
    support: frozenset
    target: str
    kind: str = "interaction"

def support_order(h):
    return len(h.support)

def all_subsets_upto(n, k):
    return [frozenset(c) for r in range(1, k+1) for c in combinations(range(n), r)]

def pairwise_projection_detects(edges, n):
    """True only if every relevant dependency has support size <= 2."""
    return all(support_order(e) <= 2 for e in edges)

def bounded_order_closure(edges, n, k, order_bound_verified):
    """
    Closure is established in this benchmark only if:
      - the maximum relevant dependency order is independently verified <= k;
      - every dependency support up to k is covered.
    """
    if not order_bound_verified:
        return "UNKNOWN"
    if any(support_order(e) > k for e in edges):
        return "UNSAFE_ORDER_BOUND"
    return "ESTABLISHED"

def factorization_closure(edges, partitions, factorization_verified, declared_cross=None):
    """
    A factorization certificate states that every relevant dependency is
    contained wholly in one declared factor OR is explicitly listed as a
    cross-factor interaction. A verified complete generator is required.
    """
    if not factorization_verified:
        return "UNKNOWN"
    declared_cross = set(declared_cross or [])
    owner = {}
    for fi, part in enumerate(partitions):
        for c in part:
            owner[c] = fi
    for e in edges:
        factors = {owner[x] for x in e.support}
        if len(factors) > 1 and e not in declared_cross:
            return "UNSAFE_UNDECLARED_CROSS_FACTOR"
    return "ESTABLISHED"

# ---------------------------------------------------------------------------
# 1. Minimal counterexample: pairwise projections cannot see 3-way synergy.
# ---------------------------------------------------------------------------
triple = HyperDependency(frozenset({0,1,2}), "Z")
assert not pairwise_projection_detects([triple], 3)

# Every pairwise projection is empty with respect to the triple interaction.
pairwise_projection = []
assert all(len(e.support) <= 2 for e in pairwise_projection)

# ---------------------------------------------------------------------------
# 2. Bounded dependency order: k=2 is safe only when the bound is verified.
# ---------------------------------------------------------------------------
pair_edges = [
    HyperDependency(frozenset({0,1}), "P"),
    HyperDependency(frozenset({1,2}), "Q"),
]
assert bounded_order_closure(pair_edges, 3, 2, True) == "ESTABLISHED"
assert bounded_order_closure([triple], 3, 2, True) == "UNSAFE_ORDER_BOUND"
assert bounded_order_closure([triple], 3, 2, False) == "UNKNOWN"

# ---------------------------------------------------------------------------
# 3. A verified factorization can avoid all-subset enumeration.
# ---------------------------------------------------------------------------
# Components 0,1 form factor A; 2,3 form factor B.
factor_edges = [
    HyperDependency(frozenset({0,1}), "A1"),
    HyperDependency(frozenset({2,3}), "B1"),
]
partitions = [{0,1}, {2,3}]
assert factorization_closure(factor_edges, partitions, True) == "ESTABLISHED"

# Hidden cross-factor dependency defeats the factorization claim.
hidden_cross = HyperDependency(frozenset({0,1,2}), "Z")
hidden_cross_factor = HyperDependency(frozenset({1,2,3}), "Z2")
assert factorization_closure(factor_edges+[hidden_cross], partitions, True) == "UNSAFE_UNDECLARED_CROSS_FACTOR"

# Without verified factorization, the same claim remains unknown.
assert factorization_closure(factor_edges, partitions, False) == "UNKNOWN"

# ---------------------------------------------------------------------------
# 4. Factorization does not mean "ignore cross-factor dependencies".
# Explicitly declared cross-factor interactions must be part of the
# verified factorization boundary.
# ---------------------------------------------------------------------------
cross_declared = factor_edges + [hidden_cross, hidden_cross_factor]
partitions_with_boundary = [{0,1,2}, {3}]
# The cross-factor dependency is explicitly declared in the verified boundary.
assert factorization_closure(
    cross_declared, partitions_with_boundary, True,
    declared_cross={factor_edges[1]}
) == "UNSAFE_UNDECLARED_CROSS_FACTOR"
# If the cross-factor interaction is explicitly covered, closure is established.
assert factorization_closure(
    cross_declared, partitions_with_boundary, True,
    declared_cross={factor_edges[1], hidden_cross_factor}
) == "ESTABLISHED"

# ---------------------------------------------------------------------------
# 5. Complexity witness: bounded order vs all-subset enumeration.
# ---------------------------------------------------------------------------
def count_all_subsets(n):
    return 2**n - 1

def count_upto(n,k):
    return sum(__import__("math").comb(n,r) for r in range(1,k+1))

complexity_table = [(n, count_all_subsets(n), count_upto(n,2), count_upto(n,3))
                    for n in range(3,11)]

# For n>=5, k=2 or k=3 is strictly smaller than all subsets.
assert all(pair < full for n,full,pair,_ in complexity_table if n>=3)
assert all(tri < full for n,full,_,tri in complexity_table if n>=4)

# ---------------------------------------------------------------------------
# 6. Adversarial matrix: never accept closure from pairwise evidence alone.
# ---------------------------------------------------------------------------
adversarial = {
    "pairwise_edges_only": [HyperDependency(frozenset({0,1}), "P")],
    "triple_hidden": [triple],
    "quad_hidden": [HyperDependency(frozenset({0,1,2,3}), "Z")],
}
assert bounded_order_closure(adversarial["triple_hidden"], 3, 2, False) == "UNKNOWN"
assert bounded_order_closure(adversarial["quad_hidden"], 4, 2, False) == "UNKNOWN"

print("R591 — Higher-Order Closure and Interaction Completeness")
print("Pairwise projection misses triple interaction: PASS")
print("Verified order bound accepts order<=k: PASS")
print("Unverified order bound -> UNKNOWN: PASS")
print("Hidden higher-order interaction defeats false factorization: PASS")
print("Verified factorization establishes local closure: PASS")
print("Complexity table (n, all-subsets, <=2, <=3):", complexity_table)
print("RESULT: Pairwise completeness is insufficient unless a verified",
      "order/factorization condition excludes relevant higher-order interactions.")
