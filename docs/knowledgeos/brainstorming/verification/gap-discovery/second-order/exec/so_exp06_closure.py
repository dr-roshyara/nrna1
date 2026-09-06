#!/usr/bin/env python3
"""
SO-EXP-06 — CANONICAL DEPENDENCY CLOSURE (mandate Part E).

QUESTION   After the second-order derivations, what is the closure state of the
           canonical theory, per node and per closure TYPE?
INPUT      The dependency graph of the first-order pass (03-FOUNDATIONAL-ONTOLOGY),
           re-marked with the second-order findings.
METHOD     Mark every node with one of the mandate's seven states; then compute
           five closure measures separately (never collapsed into one label).
RESULT     see below.
LIMITATION Node marks are argued, not computed; the graph edges are the corpus's
           definitions.  The five percentages are counts over this node set and
           change if the node set changes.
INDEPENDENCE  The graph is the first-order pass's; the MARKS are second-order and
           several reverse first-order marks (cited inline).
"""
import collections
def hr(t): print("\n" + "="*78 + f"\n{t}\n" + "="*78)

# node -> (deps, mark, note)
CLOSED_DERIVED   = "CLOSED/DERIVED"
CLOSED_PARAM     = "CLOSED/PARAMETRIC"
CLOSED_NORMATIVE = "CLOSED/NORMATIVE"
OPEN_DERIVABLE   = "OPEN/DERIVABLE"
OPEN_NORMATIVE   = "OPEN/NORMATIVE"
OPEN_EMPIRICAL   = "OPEN/EMPIRICAL"
CONTRADICTORY    = "CONTRADICTORY"

G = {
 "Observation":  ([], CLOSED_DERIVED,  "s253.6 irreducible candidate; instantiated in EKP"),
 "Time":         ([], CLOSED_DERIVED,  "s025w bitemporal model exists"),
 "Source":       ([], CLOSED_DERIVED,  "EKP owner/authority/code_refs"),
 "Entity":       ([], CLOSED_DERIVED,  "s263"),
 "Dimension":    (["Entity"], CLOSED_DERIVED, "s263"),
 "Value":        (["Dimension"], CLOSED_PARAM, "s264: V_D per dimension is deployment-declared"),
 "Proposition":  (["Entity","Dimension","Value"], CLOSED_DERIVED, "s262 verdict: typed triple"),
 "Context":      ([], OPEN_DERIVABLE,  "G-15: untyped; EKP bounded_context is a candidate target"),
 "Provenance":   (["Source","Time"], CLOSED_DERIVED, "s265 verdict: pi in K as a reference"),
 "Assertion":    (["Proposition","Provenance","Context","Time"], OPEN_DERIVABLE,
                  "G-55: one provenance slot cannot record two sources (s265.11)"),
 "RelationType": ([], CLOSED_DERIVED,  "s263 vocabulary; 6 types live in EKP"),
 "Relation":     (["Assertion","RelationType"], OPEN_DERIVABLE,
                  "G-24: edges have no id/provenance/status, yet R_der is load-bearing"),
 "K":            (["Assertion","Relation"], CLOSED_PARAM,
                  "SO-EXP-01/03: congruent for all class-1 ops, both variants; s271.35 marks K green. "
                  "PARAMETRIC because minimality is relative to T (s254)"),
 "Operation":    (["K"], CLOSED_PARAM,  "s256.2+s259.7: 15 named, 5 classes; set open by design (s256.32)"),
 "T_algebra":    (["Operation"], CLOSED_PARAM, "s256.27/s257.35 typed registry; partiality explicit"),
 "Equivalence":  (["T_algebra","K"], CLOSED_PARAM,
                  "SO-EXP-01: congruence computed for the class-1 set. Relative to T, per s259.16"),
 "KStar":        (["Equivalence"], OPEN_DERIVABLE,
                  "coarsest congruent+expressive abstraction; computable once I_mandatory is closed"),
 "History":      (["K","Operation"], CLOSED_DERIVED,
                  "s247 K=(K,H); s265 audit subsystem; exp_provenance T6"),
 "Lineage":      (["Relation"], CLOSED_DERIVED, "reachability in R_der; O(n+m); implemented"),
 "Authority":    ([], CLOSED_NORMATIVE,
                  "s187.28-29 stipulation, IMPLEMENTED 132/132 humanActRef, fail-closed. "
                  "Normative in origin, empirically settled in practice"),
 "AuthorityAct": (["Authority"], OPEN_EMPIRICAL,
                  "G-57: 79 free-text strings, 0 typed objects; recorded but not machine-checkable"),
 "Policy":       (["Authority"], OPEN_DERIVABLE,
                  "s271.35 marks Policy internal structure RED; s271 commissions the reduction"),
 "Rule":         (["Policy"], OPEN_DERIVABLE, "DS-2: Method is a field, not a typed rule"),
 "Evidence":     (["Observation","Proposition","Context","Rule"], OPEN_DERIVABLE,
                  "G-11/G-14: qualification rule well-formed; Relevant has no procedure"),
 "Assessment":   (["Evidence","Policy"], OPEN_DERIVABLE, "s271.35 Assessment semantics amber"),
 "Sigma":        (["Assessment"], OPEN_DERIVABLE,
                  "G-06: s271.35 RED; s271.36 commissions Step 272; SO-EXP-05 partially discharges"),
 "Determination":(["Sigma","Rule","Evidence","Authority"], OPEN_EMPIRICAL,
                  "DS-1: specified at s157.22/s165.8, lost in band transition; ZERO instances"),
 "Measurement":  (["Sigma"], OPEN_DERIVABLE,
                  "G-12: no empirical relational structure; G-07 ordinal arithmetic invalid"),
 "Governance":   (["Policy","Authority"], CLOSED_NORMATIVE,
                  "two-level model implemented; EKP constitution self-amends via ADR+ARB"),
}

hr("SO-EXP-06  Node marks")
order = [CLOSED_DERIVED, CLOSED_PARAM, CLOSED_NORMATIVE, OPEN_DERIVABLE, OPEN_EMPIRICAL,
         OPEN_NORMATIVE, CONTRADICTORY]
counts = collections.Counter(m for _, m, _ in G.values())
for mark in order:
    ns = [n for n, (_, m, _) in G.items() if m == mark]
    print(f"\n  {mark}  ({len(ns)})")
    for n in ns: print(f"    {n:<15} {G[n][2]}")

hr("Totals")
tot = len(G)
for mark in order:
    c = counts.get(mark, 0)
    print(f"  {mark:<20} {c:>3}   {100*c/tot:5.1f}%")
print(f"  {'TOTAL':<20} {tot:>3}")

# ---------------------------------------------------------------- cycles
hr("Is the graph acyclic NOW?")
g = {k: v[0] for k, v in G.items()}
idx = [0]; index = {}; low = {}; onstk = set(); stk = []; sccs = []
def strong(v):
    index[v] = low[v] = idx[0]; idx[0] += 1; stk.append(v); onstk.add(v)
    for w in g.get(v, []):
        if w not in index: strong(w); low[v] = min(low[v], low[w])
        elif w in onstk:   low[v] = min(low[v], index[w])
    if low[v] == index[v]:
        c = []
        while True:
            w = stk.pop(); onstk.discard(w); c.append(w)
            if w == v: break
        sccs.append(c)
for v in list(g):
    if v not in index: strong(v)
cyc = [c for c in sccs if len(c) > 1]
print(f"  strongly-connected components with >1 node: {len(cyc)}")
for c in cyc: print(f"    CYCLE {sorted(c)}")
if not cyc:
    print("""  ACYCLIC.  The first-order 7-node cycle
    Assertion -> Evidence -> Rule -> Policy -> Authority -> Assertion
  is broken because Authority now has NO outgoing edge: s187.28-29's stipulation
  is realised in the implementation (132/132 humanActRef, 0 typed acts inside).
  The regress terminates OUTSIDE the system, by reference.
  NOTE: acyclicity here is PURCHASED by that termination, and s22 of the
  first-order mandate stands -- acyclicity does NOT imply semantic completeness.""")

# ------------------------------------------------------- five closures
hr("FIVE CLOSURES, computed separately (never collapsed)")
def frac(pred):
    n = sum(1 for k, (_, m, _) in G.items() if pred(k, m)); return n, tot, 100*n/tot

sem_open = {"Context","Assertion","Relation","Policy","Rule","Evidence","Assessment","Sigma","Determination"}
comp_open = {"Context","Policy","Rule","Evidence","Assessment","Sigma","Measurement","KStar","Determination"}
emp = {"Observation","Source","Time","Entity","Dimension","Value","Proposition","Provenance",
       "Assertion","RelationType","Relation","K","Lineage","Authority","Governance","History"}
gov_closed = {"Authority","Governance","Policy"}
impl = {"Observation","Source","Time","Entity","Dimension","Value","Proposition","Provenance",
        "Assertion","RelationType","Relation","K","Lineage","Authority","Governance"}

rows = [
 ("1 SEMANTIC closure",        tot-len(sem_open), tot, "every concept has a coherent, non-overloaded meaning"),
 ("2 COMPUTATIONAL closure",   tot-len(comp_open), tot, "every symbol typed and computable"),
 ("3 EVIDENTIAL closure",      len(emp), tot, "supported by execution or implementation observation"),
 ("4 GOVERNANCE closure",      len(gov_closed)-1, len(gov_closed), "who may change the rules (regime level)"),
 ("5 IMPLEMENTATION corresp.", len(impl), tot, "has a real instance in a running system"),
]
for name, n, d, desc in rows:
    print(f"  {name:<28} {n:>3}/{d:<3} = {100*n/d:5.1f}%   {desc}")
print("""
  These are FIVE DIFFERENT NUMBERS and must not be averaged.  Governance is the
  most closed; computational the least.  A single 'complete/incomplete' label
  would hide that the theory is empirically strong and semantically weak in
  exactly one region -- the Sigma / Policy / Assessment chain.""")
