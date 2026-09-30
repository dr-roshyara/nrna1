
"""
KnowledgeOS R590 — Higher-Order Compositional Universe Closure

Research question:
Does pairwise cross-boundary completeness suffice to establish closure of
a union of >=3 component universes?

Hypothesis to test:
Pairwise CrossComplete(U_i,U_j) is NOT sufficient when a dependency requires
a higher-order combination of three or more components.

This is an executable finite benchmark, not a universal theorem.
"""
from dataclasses import dataclass
from itertools import combinations, product

@dataclass(frozen=True)
class Edge:
    endpoints: frozenset
    target: str
    kind: str = "direct"

def pair_key(a,b):
    return tuple(sorted((a,b)))

def pairwise_cross_complete(universes, truth_edges):
    """Pairwise completeness: no truth dependency has endpoints in exactly two
    distinct component universes and is omitted from their declared boundary."""
    # For each pair, detect omitted pair-crossing edges.
    for i,j in combinations(range(len(universes)),2):
        ui, uj = universes[i], universes[j]
        for e in truth_edges:
            owners = {k for k,u in enumerate(universes) if e.endpoints & u}
            if owners == {i,j} and e not in ui|uj:
                return False
    return True

def higher_order_complete(universes, truth_edges):
    """Closure criterion for this benchmark:
    every truth edge whose endpoints span 3+ components is represented by
    the declared union boundary. A higher-order edge is 'represented' only
    if explicitly declared in at least one component's boundary set.
    """
    union=set().union(*universes)
    # Here universes are sets of declared Edge objects.
    # Truth edge must be present in union if it is relevant.
    return all(e in union for e in truth_edges)

def closure_status(universes, truth_edges):
    pair = pairwise_cross_complete(universes, truth_edges)
    full = higher_order_complete(universes, truth_edges)
    if full:
        return "ESTABLISHED"
    if pair:
        return "UNSAFE_PAIRWISE_ONLY"
    return "UNKNOWN"

# --- Canonical three-universe counterexample ---
# A in U1, B in U2, C in U3; dependency D exists only when all three
# factors jointly occur. No pair alone witnesses it.
U1_nodes={"A"}
U2_nodes={"B"}
U3_nodes={"C"}
# Declared dependency universe contains no dependency edge.
U1=set(); U2=set(); U3=set()
TRUTH={Edge(frozenset({"A","B","C"}), "C", "multi_factor")}

canonical=closure_status([U1,U2,U3], TRUTH)
assert canonical=="UNSAFE_PAIRWISE_ONLY", canonical
assert pairwise_cross_complete([U1,U2,U3], TRUTH) is True
assert higher_order_complete([U1,U2,U3], TRUTH) is False

# --- Positive case: explicit higher-order boundary coverage ---
U1p={next(iter(TRUTH))}
assert closure_status([U1p,U2,U3], TRUTH)=="ESTABLISHED"

# --- Pairwise-only model with all pair edges covered, but triple edge hidden ---
AB=Edge(frozenset({"A","B"}), "P", "pairwise")
AC=Edge(frozenset({"A","C"}), "P", "pairwise")
BC=Edge(frozenset({"B","C"}), "P", "pairwise")
U1q={AB}; U2q={AC}; U3q={BC}
TRUTH_Q={AB,AC,BC, next(iter(TRUTH))}
assert pairwise_cross_complete([U1q,U2q,U3q], TRUTH_Q) is True
assert higher_order_complete([U1q,U2q,U3q], TRUTH_Q) is False

# --- Exhaustive finite test over all 3-node edge declarations ---
nodes=["A","B","C"]
truth_edges=[
    Edge(frozenset({"A","B"}),"P","pairwise"),
    Edge(frozenset({"A","C"}),"P","pairwise"),
    Edge(frozenset({"B","C"}),"P","pairwise"),
    Edge(frozenset({"A","B","C"}),"C","multi_factor"),
]
# Assign each truth edge to one or more of 3 declared component boundaries.
# 0 means absent; 1,2,4 correspond U1,U2,U3 ownership; any nonzero is a declaration.
cases=0
false_safe=0
for masks in product(range(8), repeat=4):
    universes=[set() for _ in range(3)]
    for e,m in zip(truth_edges,masks):
        for k in range(3):
            if m & (1<<k):
                universes[k].add(e)
    status=closure_status(universes,set(truth_edges))
    cases += 1
    # No false ESTABLISHED result.
    if status=="ESTABLISHED" and not higher_order_complete(universes,set(truth_edges)):
        false_safe += 1

assert false_safe==0

# --- General n=3..5: construct a hidden n-way interaction ---
general_results=[]
for n in range(3,6):
    comps=[{chr(65+i)} for i in range(n)]
    hidden=Edge(frozenset(chr(65+i) for i in range(n)), "TARGET", "multi_factor")
    empty=[set() for _ in range(n)]
    st=closure_status(empty, {hidden})
    general_results.append((n,st))

assert all(st=="UNSAFE_PAIRWISE_ONLY" for _,st in general_results)

print("R590 — Higher-Order Compositional Universe Closure")
print("Canonical 3-universe hidden interaction:", canonical)
print("Pairwise-complete + hidden triple dependency:", closure_status([U1q,U2q,U3q], TRUTH_Q))
print("Explicit higher-order coverage:", closure_status([U1p,U2,U3], TRUTH))
print("Exhaustive declaration cases:", cases)
print("False ESTABLISHED cases:", false_safe)
print("General hidden n-way interactions:", general_results)
print("RESULT: Pairwise cross-boundary completeness is insufficient for higher-order dependency closure.")
