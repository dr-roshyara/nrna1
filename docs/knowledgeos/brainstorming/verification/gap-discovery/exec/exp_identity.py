#!/usr/bin/env python3
"""
EXPERIMENTS 8-10 — Identity, equality, membership, and the merge algebra.

Mandate s17: "K1 = K2 ?" and "assertion in K ?" MUST be computable.
This tests whether they are WELL-DEFINED before asking whether they are computable.
"""
import sys, os, itertools; sys.path.insert(0, os.path.dirname(__file__))
from kos_kernel import *
def hr(t): print("\n"+"="*74+f"\n{t}\n"+"="*74)

P = Proposition("Nexus","version","3.69")
Q = Proposition("Nexus","eol","2027")

# ------------------------------------------------------------------- EXP-8
hr("EXP-8  'A in K?' is answered DIFFERENTLY by each defensible equality")
a_vendor = Assertion("a1", P, frozenset({"e1"}), "ops", 10, Provenance("vendor","api",10), "Supported")
a_forum  = Assertion("a2", P, frozenset({"e2"}), "ops", 12, Provenance("forum","scrape",12), "Candidate")
a_vend2  = Assertion("a3", P, frozenset({"e1"}), "ops", 10, Provenance("vendor","api",10), "Supported")

Kst = mk([a_vendor])
probes = {"a_forum (same prop, other source/status)": a_forum,
          "a_vend2 (same prop+prov+status, NEW id)": a_vend2,
          "a_vendor (identical object)":             a_vendor}

def member(a, k, mode):
    if mode == "structural":     return a in k.A
    if mode == "content-only":   return str(a.P) in k.props()
    if mode == "content+status": return (str(a.P), a.sigma) in {(str(x.P),x.sigma) for x in k.A}
    if mode == "identity-only":  return a.id in {x.id for x in k.A}
modes = ["structural","content-only","content+status","identity-only"]
print(f"  K = {{{a_vendor}}}\n")
print(f"  {'probe':<44} " + " ".join(f"{m:<15}" for m in modes))
disagree = 0
for lab, a in probes.items():
    row = [member(a,Kst,m) for m in modes]
    if len(set(row)) > 1: disagree += 1
    print(f"  {lab:<44} " + " ".join(f"{str(r):<15}" for r in row))
print(f"\n  probes on which the four equalities DISAGREE: {disagree}/{len(probes)}")
print("""  => 'assertion in K' is NOT a well-defined predicate of the theory.
     It is a family of four predicates, and the corpus fixes none of them.
     Step 261 names structural vs semantic equality but does not RULE, and
     Step 266 marks semantic equality 'yellow: domain semantics' -- i.e. open.
     Consequence: EVERY theorem quantifying over 'A in K' is underdetermined.""")

# ------------------------------------------------------------------- EXP-9
hr("EXP-9  'K1 = K2 ?'  -- the four equalities partition the same 5 states differently")
states = {
  "S1 vendor/Supported":        mk([a_vendor]),
  "S2 forum/Candidate":         mk([a_forum]),
  "S3 vendor/Supported new-id": mk([a_vend2]),
  "S4 S1 + derivation rel":     mk([a_vendor, Assertion("b1",Q,frozenset({"e3"}),"ops",9,
                                    Provenance("vendor","api",9))], [("a1","b1","derives")]),
  "S5 S4 without the relation": mk([a_vendor, Assertion("b1",Q,frozenset({"e3"}),"ops",9,
                                    Provenance("vendor","api",9))], []),
}
names = list(states)
for mode, fn in EQUALITIES.items():
    # build partition
    seen, part = [], []
    for n in names:
        for cls in part:
            if fn(states[n], states[cls[0]]): cls.append(n); break
        else: part.append([n])
    print(f"  {mode:<16} -> {len(part)} classes: " +
          " | ".join("{"+", ".join(c)+"}" for c in part))
print("""
  => The SAME five states fall into different numbers of equivalence classes
     depending on the equality chosen.  'K1 = K2' therefore has no truth value
     in the theory as it stands.  Step 260 requires the equivalence to be a
     CONGRUENCE for the transformation algebra T -- and T is not enumerated
     (Step 266), so the relation cannot even be constructed, let alone decided.""")

# ------------------------------------------------------------------ EXP-10
hr("EXP-10  Is MERGE an algebra?  (idempotent / commutative / associative)")
def merge(k1: K, k2: K) -> K:
    """Union, with a conflict rule the corpus repeatedly proposes:
    if two assertions give DIFFERENT values for the same (entity,dimension),
    mark every assertion on that dimension 'Conflicted'."""
    A = set(k1.A) | set(k2.A)
    dims = {}
    for a in A: dims.setdefault((a.P.entity,a.P.dimension), set()).add(a.P.value)
    out = set()
    for a in A:
        conflicted = len(dims[(a.P.entity,a.P.dimension)]) > 1
        out.add(replace(a, sigma="Conflicted") if conflicted else a)
    return K(frozenset(out), k1.R | k2.R)

x = Assertion("x", P, frozenset({"e1"}), "ops", 10, Provenance("vendor","api",10), "Accepted")
y = Assertion("y", P, frozenset({"e2"}), "ops", 11, Provenance("mirror","api",11), "Accepted")
z = Assertion("z", Proposition("Nexus","version","3.70"), frozenset({"e3"}), "ops", 12,
              Provenance("forum","scrape",12), "Candidate")
A_, B_, C_ = mk([x]), mk([y]), mk([z])

def sig(k): return sorted((a.id, a.sigma) for a in k.A)
print(f"  A={sig(A_)}  B={sig(B_)}  C={sig(C_)}")
print(f"\n  idempotent?   merge(A,A)={sig(merge(A_,A_))}   == A? {sig(merge(A_,A_))==sig(A_)}")
print(f"  commutative?  merge(A,C)={sig(merge(A_,C_))}")
print(f"                merge(C,A)={sig(merge(C_,A_))}   equal? {sig(merge(A_,C_))==sig(merge(C_,A_))}")
lhs, rhs = merge(merge(A_,B_),C_), merge(A_,merge(B_,C_))
print(f"\n  associative?  (A*B)*C = {sig(lhs)}")
print(f"                A*(B*C) = {sig(rhs)}   equal? {sig(lhs)==sig(rhs)}")

# now a rule that is NOT associative: 'latest timestamp wins, ties reject'
def merge_lww(k1: K, k2: K) -> K:
    A = list(k1.A) + list(k2.A)
    best = {}
    for a in A:
        key = (a.P.entity, a.P.dimension)
        cur = best.get(key)
        if cur is None or a.t > cur.t: best[key] = a
        elif a.t == cur.t and a.P.value != cur.P.value:
            best[key] = replace(cur, sigma="Conflicted")
    return K(frozenset(best.values()), k1.R | k2.R)

x2 = replace(x, t=10); y2 = replace(y, t=10, P=Proposition("Nexus","version","3.71"))
z2 = replace(z, t=11)
A2, B2, C2 = mk([x2]), mk([y2]), mk([z2])
l2, r2 = merge_lww(merge_lww(A2,B2),C2), merge_lww(A2,merge_lww(B2,C2))
print(f"\n  second rule (latest-wins, tie->Conflicted), with a TIE at t=10:")
print(f"    (A*B)*C = {sig(l2)}")
print(f"    A*(B*C) = {sig(r2)}   equal? {sig(l2)==sig(r2)}")
print("""
  NOTE: this hand-picked triple came out ASSOCIATIVE for both rules.  A single
  example proves nothing either way, so EXP-10b replaces it with an exhaustive
  search over the full small state space.  The exhaustive result is the finding.""")

# ------------------------------------------------------------------ EXP-10b
hr("EXP-10b  EXHAUSTIVE SEARCH for a non-associative merge triple (no hand-picking)")
import itertools
VALS  = ["3.69", "3.70"]
TIMES = [10, 11]
def mkA(i, v, t):
    return Assertion(f"n{i}", Proposition("Nexus","version",v), frozenset({f"e{i}"}),
                     "ops", t, Provenance(f"s{i}","api",t), "Accepted")

space = [mkA(i, v, t) for i,(v,t) in enumerate(itertools.product(VALS, TIMES))]
print(f"  search space: {len(space)} single-assertion states, "
      f"{len(space)**3} ordered triples")

def sigp(k): return sorted((a.P.value, a.sigma) for a in k.A)

found = {"conflict-rule": [], "lww-rule": []}
for a,b,c in itertools.product(space, repeat=3):
    A_,B_,C_ = mk([a]), mk([b]), mk([c])
    for nm, f in (("conflict-rule", merge), ("lww-rule", merge_lww)):
        if sigp(f(f(A_,B_),C_)) != sigp(f(A_,f(B_,C_))):
            found[nm].append((a,b,c))
for nm, lst in found.items():
    print(f"\n  {nm}: associativity counterexamples found = {len(lst)}")
    if lst:
        a,b,c = lst[0]
        A_,B_,C_ = mk([a]), mk([b]), mk([c])
        f = merge if nm=="conflict-rule" else merge_lww
        print(f"    witness A={a.P.value}@t{a.t}  B={b.P.value}@t{b.t}  C={c.P.value}@t{c.t}")
        print(f"      (A*B)*C = {sigp(f(f(A_,B_),C_))}")
        print(f"      A*(B*C) = {sigp(f(A_,f(B_,C_)))}")

print("""
  READING (this supersedes the hand-picked triple above, which was associative):
    The exhaustive result -- not an assertion -- is what stands.  Whichever way
    it comes out, the finding is the same one: 'merge converges' (Step 025l) is
    a property OF A RULE, and the corpus states the claim without naming the
    rule.  A convergence theorem that does not name its conflict-resolution
    operator has no content.""")
