#!/usr/bin/env python3
"""
SO-EXP-02 — CONGRUENCE IS NOT THE ONLY CRITERION.

QUESTION   SO-EXP-01 found F4 = K=(A,R) congruent for Merge under BOTH variants.
           But is F4 congruent because the operation preserves nothing, or
           because F4 CANNOT SEE what the operation preserves?
INPUT      the same bounded model.
METHOD     (a) show F4 passes Merge/sensitive;
           (b) then test whether the invariant that the sensitive variant exists
               to enforce -- s256.9 / s257.20's  Prov(x3) >= Prov(x1) u Prov(x2)
               -- is EXPRESSIBLE as a predicate on the abstraction.
           An abstraction can be congruent for T while being unable to state the
           invariant T is required to maintain.  Those are different properties.
RESULT     see below.
LIMITATION Bounded domain; the invariant tested is the one the corpus names, not
           an exhaustive invariant set.
INDEPENDENCE  New.  s259.15 states only the congruence criterion; no corpus step
           and no prior verification artifact distinguishes congruence from
           invariant-expressibility.
"""
import sys, os
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from so_model import *
def hr(t): print("\n" + "="*78 + f"\n{t}\n" + "="*78)

hr("SO-EXP-02  Congruence vs. invariant-expressibility")

a = Obj("a", "p", "vendor",  "Accepted")
b = Obj("b", "q", "forum",   "Accepted")
s = State(frozenset({a, b}))

print(f"  s        = {sorted(map(str, s.objs))}")
res_blind     = op_merge(s, ("a", "b", "z"), "blind")
res_sensitive = op_merge(s, ("a", "b", "z"), "sensitive")
print(f"  Merge blind      -> objs={sorted(map(str,res_blind.objs))}  merged={sorted(res_blind.merged)}")
print(f"  Merge sensitive  -> objs={sorted(map(str,res_sensitive.objs))}  "
      f"merged={[(r,sorted(ss)) for r,ss in res_sensitive.merged]}")

print(f"\n  F4(blind result)     = {F_AR(res_blind)}")
print(f"  F4(sensitive result) = {F_AR(res_sensitive)}")
print(f"  F4 distinguishes them? {F_AR(res_blind) != F_AR(res_sensitive)}")
print(f"  F5 distinguishes them? {F_AR_merge(res_blind) != F_AR_merge(res_sensitive)}")

print("""
  READING: F4 is congruent for Merge/sensitive precisely BECAUSE F4 projects
  away the `merged` record.  The provenance the sensitive variant exists to
  preserve is invisible in F4.  Congruence is satisfied vacuously.""")

# ------------------------------------------------------- invariant test
hr("Is the corpus's own merge-provenance invariant EXPRESSIBLE in each abstraction?")
print("""  Invariant under test (s256.9, restated s257.20):
        Prov(x3) >= Prov(x1) u Prov(x2)
  A predicate is EXPRESSIBLE in abstraction F iff its truth value is determined
  by F(state) -- i.e. it cannot differ between two states F identifies.""")

# Two states that F identifies but on which the invariant differs.
holds     = res_sensitive                       # merge record present
violates  = res_blind                           # merge record absent
print(f"\n  state_holds    : invariant TRUE   (merged = {[(r,sorted(ss)) for r,ss in holds.merged]})")
print(f"  state_violates : invariant FALSE  (merged = {sorted(violates.merged)})")

def invariant_holds(st, rid, need):
    return any(r == rid and need <= ss for r, ss in st.merged)

need = frozenset({"vendor", "forum"})
print(f"\n  invariant(state_holds)    = {invariant_holds(holds,'z',need)}")
print(f"  invariant(state_violates) = {invariant_holds(violates,'z',need)}")

print(f"\n  {'abstraction':<26} {'identifies the two states?':<28} {'invariant expressible?'}")
print("  " + "-"*76)
for name, F in ABSTRACTIONS.items():
    same = F(holds) == F(violates)
    expressible = not same          # if F identifies them, the invariant cannot be stated in F
    print(f"  {name:<26} {str(same):<28} {'NO' if same else 'YES'}")

print("""
  => DERIVED RESULT.  The terminal model K=(A,R) is:
       * CONGRUENT for every mandatory state-transforming operation tested
         (SO-EXP-01, both dependency variants, 208-state domain), AND
       * UNABLE TO EXPRESS the merge-provenance invariant that the corpus's own
         s256.9 / s257.20 propose as possibly mandatory.

  These two facts are consistent, and together they show that s259.15's
  congruence criterion is NECESSARY BUT NOT SUFFICIENT for state adequacy.
  A second criterion is required:

        For every mandatory invariant I,  I must be expressible as a
        predicate on the candidate state.

  The corpus states the congruence criterion (259.15) and separately states the
  invariant (256.9) and never composes them.  This is the same 'correct local
  results that are never composed' pattern the first-order pass recorded.""")
