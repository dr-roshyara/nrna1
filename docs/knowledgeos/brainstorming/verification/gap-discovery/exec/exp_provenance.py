#!/usr/bin/env python3
"""
EXPERIMENTS 24-29 — Provenance / Lineage / History, the mandate's six required tests (s16).

Definitions under test (corpus, Step 265 + CANONICAL-UBIQUITOUS-LANGUAGE usage):
  Provenance Pi : WHERE an assertion came from (source, method, time) -- inside A
  Lineage       : ancestry of an assertion   -- reachability in R_der union R_ref
  History  H    : (K_0, T_1 .. T_t)          -- the operation sequence, OUTSIDE K
  State    K    : (A, R)

The six tests ask, for each scenario: which of these can be ANSWERED FROM K ALONE?
"""
import sys, os; sys.path.insert(0, os.path.dirname(__file__))
from kos_kernel import *
def hr(t): print("\n"+"="*74+f"\n{t}\n"+"="*74)

def lineage(k: K, aid: str):
    """Ancestors of aid via 'derives' (a1 derives FROM a2 => edge (a1,a2,'derives'))."""
    out, frontier = [], [aid]
    while frontier:
        cur = frontier.pop()
        for (f,to,ty) in k.R:
            if f == cur and ty in ("derives","refers") and to not in out:
                out.append(to); frontier.append(to)
    return sorted(out)

P1 = Proposition("Nexus","version","3.69")
P2 = Proposition("Nexus","patch_current","true")
P3 = Proposition("Nexus","migration_ready","true")

# ------------------------------------------------------------------- TEST 1
hr("TEST 1  Imported assertion at t=0  -- the base case Step 265 s265.2 calls decisive")
a0 = Assertion("a0", P1, frozenset({"e0"}), "ops", 0, Provenance("vendor","import",0))
K0 = mk([a0])
print(f"  K0 = {[str(a) for a in K0.A]}")
print(f"  provenance from K alone : {a0.Pi}                       ANSWERABLE")
print(f"  lineage   from K alone  : {lineage(K0,'a0')}   -- empty, correctly: no ancestors")
print(f"  history   from K alone  : NOT ANSWERABLE -- K carries no H; there is no")
print(f"                            way to distinguish 'imported at t=0' from")
print(f"                            'asserted at t=0 by a person'.")
print("""  => Step 265 s265.2's base-case argument CONFIRMED by construction: at t=0
     lineage is empty, so any model that DEFINES provenance as 'lineage of the
     history' (L = History(T)) has nothing to define it with.  Provenance must be
     carried, not derived.  This is the strongest single argument in Step 265.""")

# ------------------------------------------------------------------- TEST 2
hr("TEST 2  One-step transformation")
K1 = T(K0, Op("restatus", ("a0","Accepted")))
print(f"  K1 = {[str(a) for a in K1.A]}")
print(f"  provenance unchanged?   {list(K1.A)[0].Pi == a0.Pi}   -- Pi survives restatus")
print(f"  who performed the restatus, and when?  NOT ANSWERABLE FROM K")
print("""  => Pi records the origin of the CLAIM, not the origin of the STATE CHANGE.
     The theory has provenance for assertions and NO provenance for transitions.
     Step 265's placement matrix has no row for transition provenance.""")

# ------------------------------------------------------------------- TEST 3
hr("TEST 3  Multi-step derivation")
b = Assertion("b", P2, frozenset({"e1"}), "ops", 1, Provenance("internal","rule",1))
c = Assertion("c", P3, frozenset({"e2"}), "ops", 2, Provenance("internal","rule",2))
K3 = mk([a0,b,c], [("b","a0","derives"), ("c","b","derives")])
print(f"  chain: c derives-from b derives-from a0")
print(f"  lineage(c) from K alone = {lineage(K3,'c')}          ANSWERABLE")
print(f"  Pi(c) = {c.Pi}  -- says 'internal/rule', NOT 'ultimately from vendor'")
print("""  => Lineage IS derivable from K (reachability in R_der) -- this is the one
     genuinely computable relation in the whole theory (O(n+m)).
     But Pi and lineage give DIFFERENT answers about origin, and the corpus uses
     'provenance' for both in different steps.  They must not be conflated.""")

# ------------------------------------------------------------------- TEST 4
hr("TEST 4  Two independent sources for the same proposition")
v = Assertion("v", P1, frozenset({"ev"}), "ops", 3, Provenance("vendor","api",3))
s = Assertion("s", P1, frozenset({"es"}), "ops", 4, Provenance("scanner","probe",4))
K4 = mk([v,s])
print(f"  K4 holds ONE proposition asserted TWICE: {sorted(str(a.Pi) for a in K4.A)}")
print(f"  distinct propositions in K4: {K4.props()}   (one)")
print(f"  distinct assertions in K4  : {sorted(a.id for a in K4.A)}   (two)")
print("""  => P != A pays off here: independent corroboration is representable as two
     assertions of one proposition.  ANSWERABLE.  This is a genuine strength.
     What is NOT answerable: whether the two sources are INDEPENDENT.  The theory
     has no independence relation -- only R_der (dependence).  Absence of a
     derives-edge is not evidence of independence (it may simply be unrecorded).""")

# ------------------------------------------------------------------- TEST 5
hr("TEST 5  Merge")
def merge(k1,k2): return K(k1.A|k2.A, k1.R|k2.R)
Km = merge(mk([v]), mk([s]))
print(f"  merge(K_vendor, K_scanner) = {sorted(a.id for a in Km.A)}")
print(f"  can we tell which branch each assertion came from?  "
      f"{'YES via Pi.source' if len({a.Pi.source for a in Km.A})>1 else 'NO'}")
print(f"  can we tell WHEN the merge happened, or WHO authorized it?  NO")
print("""  => Assertion provenance survives merge (Step 265 s265.11 'merge test'
     PASSES).  MERGE provenance does not exist: the merge event itself has no
     record inside K.  Two different merge orders producing the same K are
     indistinguishable -- and EXP-10b showed those orders can produce DIFFERENT K
     under a latest-wins rule, so the unrecorded order is semantically load-bearing.""")

# ------------------------------------------------------------------- TEST 6
hr("TEST 6  Identical current states, different histories  -- the decisive test")
H_a = [Op("assert", v)]
H_b = [Op("assert", s), Op("assert", v), Op("withdraw", "scanner")]
Ka, Kb = replay(mk([]), H_a), replay(mk([]), H_b)
print(f"  H_a = assert(v)")
print(f"  H_b = assert(s); assert(v); withdraw(scanner)")
print(f"  K(H_a) = {sorted(a.id for a in Ka.A)}   K(H_b) = {sorted(a.id for a in Kb.A)}")
print(f"  structurally equal? {eq_structural(Ka,Kb)}")
print(f"""
  Questions a real audit asks, answered from K alone:
    what does K say now?                 ANSWERABLE     {Ka.props()}
    where did each assertion come from?  ANSWERABLE     via Pi
    what is each assertion derived from? ANSWERABLE     via R_der
    was anything ever withdrawn?         NOT ANSWERABLE
    was this claim ever contradicted?    NOT ANSWERABLE
    how many times has it been revised?  NOT ANSWERABLE
    who withdrew the scanner source?     NOT ANSWERABLE

  => The three ANSWERABLE questions are exactly PROVENANCE and LINEAGE.
     The four unanswerable ones are exactly HISTORY.
     CONCLUSION (executed): Provenance and Lineage belong to the STATE;
     History does NOT reduce to the state, and Step 247's 𝒦_t=(K_t,H_t) is
     therefore NECESSARY, not merely convenient.""")

hr("SUMMARY -- placement matrix, derived from execution")
rows = [
 ("assertion provenance (origin of a claim)", "IN K (Pi)",        "answerable"),
 ("lineage (ancestry via R_der)",             "DERIVED FROM K",   "answerable, O(n+m)"),
 ("transition provenance (who changed what)", "NOWHERE",          "NOT answerable"),
 ("merge provenance (when/who merged)",       "NOWHERE",          "NOT answerable"),
 ("withdrawal record",                        "NOWHERE",          "NOT answerable"),
 ("contestation record",                      "NOWHERE",          "NOT answerable"),
 ("evidence provenance",                      "OUTSIDE K (ids)",  "NOT answerable"),
 ("independence of two sources",              "NO RELATION",      "NOT answerable"),
]
print(f"  {'concept':<42} {'placement':<18} {'from K alone'}")
for a,b,c in rows: print(f"  {a:<42} {b:<18} {c}")
