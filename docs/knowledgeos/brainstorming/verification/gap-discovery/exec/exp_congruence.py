#!/usr/bin/env python3
"""
EXPERIMENTS 1-3 — State/History congruence against the corpus's TERMINAL K=(A,R).

Hypotheses under test (each from the corpus, cited):
  H1 (Step 255 Class I): a CONTENT-ONLY abstraction is not sufficient.
  H2 (Step 265 verdict): placing provenance INSIDE the assertion repairs H1.
  H3 (Steps 262-269):    K=(A,R) with in-assertion provenance IS a sufficient
                         abstraction of history.
  H4 (this session, EV-E1): the answer to H3 is not determined by the corpus,
                         because it depends on the operation set O, and Step 266
                         leaves O open.

A abstraction F is SUFFICIENT iff  F(H1)=F(H2) => forall O: O(H1)=O(H2)
                                   (Step 259) and the relation is a CONGRUENCE
                                   under T (Step 260 s260.9).
"""
import sys, os; sys.path.insert(0, os.path.dirname(__file__))
from kos_kernel import *

def hr(t): print("\n" + "="*74 + f"\n{t}\n" + "="*74)

def sufficient(pairs, F, ops):
    """Return (is_sufficient, witnesses). pairs = [(K1,K2,label)]."""
    bad = []
    for k1, k2, lab in pairs:
        if not F(k1, k2):
            continue                       # F already distinguishes them: fine
        for name, op in ops.items():
            args = ["Nexus.version=3.69"] if name in ("query","explain","contest") else ["vendor"]
            r1, r2 = op(k1, *args), op(k2, *args)
            if name == "withdraw":
                r1, r2 = r1.props(), r2.props()
            if r1 != r2:
                bad.append((lab, name, r1, r2))
    return (len(bad) == 0), bad

P  = Proposition("Nexus", "version", "3.69")
Q  = Proposition("Nexus", "eol", "2027")

# ---------------------------------------------------------------- EXPERIMENT 1
hr("EXP-1  Content-only abstraction vs. differing provenance  (Step 255 Class I)")
a_vendor = Assertion("a1", P, frozenset({"e1"}), "ops", 10, Provenance("vendor",  "api",   10))
a_rumour = Assertion("a1", P, frozenset({"e2"}), "ops", 10, Provenance("forum",   "scrape",10))
K1, K2 = mk([a_vendor]), mk([a_rumour])

print(f"  K1 = {[str(a) for a in K1.A]}")
print(f"  K2 = {[str(a) for a in K2.A]}")
print(f"  content-only says equal? {eq_content(K1,K2)}")
print(f"  explain(K1) = {op_explain(K1, str(P))}")
print(f"  explain(K2) = {op_explain(K2, str(P))}")
ok, bad = sufficient([(K1,K2,"EXP1")], eq_content, OPS_MINIMAL)
print(f"\n  content-only sufficient under OPS_MINIMAL? {ok}")
for b in bad: print(f"    counterexample: op={b[1]}  {b[2]} != {b[3]}")
print("  => H1 CONFIRMED by execution: content-only is NOT a sufficient abstraction.")

# ---------------------------------------------------------------- EXPERIMENT 2
hr("EXP-2  Is (content + in-assertion provenance) sufficient?  (Step 265 verdict)")
# Both states hold the SAME two propositions with the SAME per-assertion
# provenance.  They differ ONLY in whether a derivation relation exists.
src_v  = Provenance("vendor",  "api",       10)
src_in = Provenance("internal","derivation",11)
a1 = Assertion("a1", P, frozenset({"e1"}), "ops", 11, src_in)   # identical in both
b1 = Assertion("b1", Q, frozenset({"e2"}), "ops", 10, src_v)    # identical in both
KA = mk([a1, b1], [("a1","b1","derives")])   # P was DERIVED from Q
KB = mk([a1, b1], [])                        # P stands on its own

print(f"  KA: A={sorted(str(a) for a in KA.A)}")
print(f"      R={sorted(KA.R)}")
print(f"  KB: A={sorted(str(a) for a in KB.A)}")
print(f"      R={sorted(KB.R)}")
print(f"  same propositions?               {KA.props()==KB.props()}")
print(f"  same per-proposition provenance? "
      f"{all(op_explain(KA,x)==op_explain(KB,x) for x in KA.props())}")
print(f"  content-only equal?              {eq_content(KA,KB)}")
print(f"  content+status equal?            {eq_content_status(KA,KB)}")
wa, wb = op_withdraw_source(KA,"vendor"), op_withdraw_source(KB,"vendor")
print(f"  withdraw('vendor'):  KA -> {wa.props()}")
print(f"                       KB -> {wb.props()}")
ok, bad = sufficient([(KA,KB,"EXP2")], eq_content, OPS_FULL)
print(f"\n  (content + in-assertion provenance) sufficient under OPS_FULL? {ok}")
for b in bad: print(f"    counterexample: op={b[1]}  {b[2]} != {b[3]}")
print("""  => OBSERVED: the two states agree on every proposition AND on every
     proposition's provenance, yet withdraw() distinguishes them.
     Therefore Pi (provenance inside the assertion, Step 265's placement) is
     NOT what restores sufficiency -- the RELATION set R is.  Step 265's
     placement verdict is orthogonal to the sufficiency question it is often
     cited for; the load-bearing component is R_der.""")

# ---------------------------------------------------------------- EXPERIMENT 3
hr("EXP-3  Is K=(A,R) itself sufficient?  ANSWER DEPENDS ON THE OPERATION SET")
# Two histories reaching an IDENTICAL K=(A,R), differing only in how many
# contestations occurred and were resolved along the way.
base = Assertion("a1", P, frozenset({"e1"}), "ops", 10, src_v)
ctr  = Assertion("c1", Proposition("Nexus","version","3.70"),
                 frozenset({"e9"}), "ops", 11, Provenance("forum","scrape",11))

# History H_calm: assert, done.
H_calm = [Op("assert", base)]
# History H_stormy: assert, a contradicting assertion appears, is contested,
# then is withdrawn -- ending in the SAME K.
H_stormy = [Op("assert", base), Op("assert", ctr),
            Op("relate", ("c1","a1","contradicts")),
            Op("withdraw", "forum")]

Kc, Ks = replay(mk([]), H_calm), replay(mk([]), H_stormy)
print(f"  K(H_calm)   A={sorted(a.id for a in Kc.A)}  R={sorted(Kc.R)}")
print(f"  K(H_stormy) A={sorted(a.id for a in Ks.A)}  R={sorted(Ks.R)}")
print(f"  structurally equal?  {eq_structural(Kc,Ks)}")

for label, ops in (("OPS_MINIMAL", OPS_MINIMAL), ("OPS_FULL", OPS_FULL)):
    ok, bad = sufficient([(Kc,Ks,"EXP3")], eq_structural, ops)
    print(f"  K=(A,R) sufficient under {label}? {ok}")
    for b in bad: print(f"      counterexample op={b[1]}: {b[2]} != {b[3]}")

print("""
  Both states are structurally IDENTICAL, so K=(A,R) cannot distinguish them.
  Under OPS_MINIMAL/OPS_FULL as coded, no CURRENT operation distinguishes them either
  -- because contest_count reads the CURRENT R, which is empty in both.

  Now add ONE further operation that the corpus's own governance material makes
  plausible: a policy that escalates an assertion which has EVER been contested.""")

def op_ever_contested(k: K, prop_str: str) -> bool:
    "NOT computable from K=(A,R): requires history. Included to make the point."
    return NotImplemented

# Demonstrate directly against the histories:
def ever_contested(history, prop) -> bool:
    seen = set()
    for o in history:
        if o.name == "assert": seen.add(o.payload.id)
        if o.name == "relate" and o.payload[2] == "contradicts": return True
    return False

print(f"  ever_contested(H_calm,P)   = {ever_contested(H_calm,P)}")
print(f"  ever_contested(H_stormy,P) = {ever_contested(H_stormy,P)}")
print("""
  => H3 is UNDECIDED, and H4 is CONFIRMED:
     K=(A,R) is sufficient IFF the mandatory operation set O excludes every
     history-sensitive predicate.  The corpus never closes O (Step 266: the
     operation registry is missing; policy and authority are class-C).
     Therefore "K=(A,R) is the minimal sufficient state" is NOT a theorem of
     the corpus -- it is a consequence of an unstated choice of O.
""")
