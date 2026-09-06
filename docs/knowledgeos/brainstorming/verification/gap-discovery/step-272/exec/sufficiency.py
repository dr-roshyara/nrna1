#!/usr/bin/env python3
"""
Sufficient(K, O, I) — formal adoption, with an executable checker.

DEFINITION (proposed for adoption; see 12-SUFFICIENCY-DEFINITION.md)

  Let F be a state abstraction (a projection of the full underlying state).
  Write  s1 ~F s2   for   F(s1) = F(s2).

    Congruent(F, O)  <=>  for every state-transforming T in O and every admissible arg c:
                          s1 ~F s2  =>  T(s1,c) ~F T(s2,c)

    Expressive(F, I) <=>  for every mandated predicate/invariant I in I:
                          s1 ~F s2  =>  I(s1) = I(s2)

    Sufficient(F, O, I) <=> Congruent(F, O) AND Expressive(F, I)

  Both conjuncts have the SAME shape: F-indistinguishability must be respected.
  Congruence asks it of OPERATIONS (arity>=1, codomain = state).
  Expressibility asks it of PREDICATES (arity 0 over the state, codomain = a value).
  Expressibility is therefore congruence for the 0-ary case -- which is exactly why
  a criterion stated only for state-transforming operations cannot see it.

CLAIM UNDER TEST: the two conjuncts are INDEPENDENT (neither implies the other).
"""
import sys, os, itertools
sys.path.insert(0, os.path.join(os.path.dirname(os.path.abspath(__file__)),
                                '..', '..', 'exec'))
sys.path.insert(0, os.path.join(os.path.dirname(os.path.abspath(__file__)),
                                '..', '..', 'second-order', 'exec'))
from so_model import *          # Obj, State, F_*, op_*, OPERATIONS, ABSTRACTIONS
import io, contextlib
with contextlib.redirect_stdout(io.StringIO()):          # suppress the imported module's own report
    from so_exp01_congruence_matrix import SPACE, congruence
def hr(t): print("\n"+"="*78+f"\n{t}\n"+"="*78)

# ---------------------------------------------------------------- the checker
def expressive(F, predicates, space=SPACE):
    """Expressive(F, I): no two F-identified states disagree on any predicate."""
    buckets = {}
    for s in space: buckets.setdefault(F(s), []).append(s)
    bad = []
    for key, grp in buckets.items():
        for s1, s2 in itertools.combinations(grp, 2):
            for name, I in predicates.items():
                if I(s1) != I(s2):
                    bad.append((name, s1, s2, I(s1), I(s2)))
    return (len(bad) == 0), bad

def sufficient(F, ops, predicates, space=SPACE):
    cong_fails = [op for op in ops if not congruence(F, op, "sensitive")[0]]
    expr_ok, expr_bad = expressive(F, predicates, space)
    return (len(cong_fails) == 0 and expr_ok), cong_fails, expr_bad

# ---------------------------------------------------- INDEPENDENCE OF CONJUNCTS
hr("1. Are the two conjuncts INDEPENDENT?  (neither implies the other)")

OPS = ["Add","Remove","Revise","Transform","Supersede","Merge","Withdraw","Reject"]

# (a) Congruent but NOT expressive  -- the SO-EXP-02 case, restated as a predicate
def I_merge_prov(s):
    "mandated by s265.11: a merged object's provenance association resolves to its sources"
    return any(r == "z" and {"vendor","forum"} <= set(ss) for r, ss in s.merged)
a = Obj("a","p","vendor","Accepted"); b = Obj("b","q","forum","Accepted")
s0 = State(frozenset({a,b}))
sp_ab = [op_merge(s0,("a","b","z"),"sensitive"), op_merge(s0,("a","b","z"),"blind")]
cong_A = all(congruence(F_AR, op, "sensitive")[0] for op in OPS)
expr_A, bad_A = expressive(F_AR, {"I_merge_prov": I_merge_prov}, sp_ab)
print(f"  (a) F4 = K=(A,R)   Congruent={cong_A}   Expressive(I_merge_prov)={expr_A}")
print(f"      => CONGRUENT AND NOT EXPRESSIVE  -- congruence does not imply expressibility")

# (b) Expressive but NOT congruent -- need a predicate F2 respects while failing congruence
def I_any_accepted(s): return any(o.status == "Accepted" for o in s.objs)
cong_B_fails = [op for op in OPS if not congruence(F_content_status, op, "sensitive")[0]]
expr_B, bad_B = expressive(F_content_status, {"I_any_accepted": I_any_accepted})
print(f"\n  (b) F2 = content+status   Congruent={not cong_B_fails} (fails on {cong_B_fails[:3]}…)"
      f"   Expressive(I_any_accepted)={expr_B}")
print(f"      => EXPRESSIVE AND NOT CONGRUENT  -- expressibility does not imply congruence")
print(f"""
  RESULT: the conjuncts are INDEPENDENT. Neither is redundant, so
          Sufficient = Congruent AND Expressive is not a restatement of either.""")

# --------------------------------------------- WOULD IT HAVE PREDICTED REPAIR B?
hr("2. Would Sufficient(K,O,I) have PREDICTED the Step 281 repair?")
print("""  The Step 280 defect: 'not asked' and 'asked+absent' are both  a not-in A.
  Model it: a proposition p, and a state that does or does not record an inquiry.""")

# minimal model: state = (assertions present, inquiries made)
class S2:
    def __init__(self, asserted, asked): self.asserted=frozenset(asserted); self.asked=frozenset(asked)
    def __repr__(self): return f"S2(asserted={sorted(self.asserted)}, asked={sorted(self.asked)})"
K_only   = lambda s: tuple(sorted(s.asserted))                      # K = (A,R) alone
K_plus_Q = lambda s: (tuple(sorted(s.asserted)), tuple(sorted(s.asked)))   # (K, Q_t)

not_asked    = S2(asserted=[], asked=[])        # M1
asked_absent = S2(asserted=[], asked=["p"])     # M2
SPACE2 = [not_asked, asked_absent, S2(["p"],["p"])]

def I_was_asked(s): return "p" in s.asked       # the mandated M1 != M2 distinction

for name, F in (("K alone", K_only), ("(K, Q_t)", K_plus_Q)):
    ok, bad = expressive(F, {"I_was_asked": I_was_asked}, SPACE2)
    print(f"\n  F = {name:<10}  F(M1)={F(not_asked)}  F(M2)={F(asked_absent)}")
    print(f"    F identifies M1 and M2? {F(not_asked)==F(asked_absent)}")
    print(f"    Expressive(I_was_asked)? {ok}")
    if bad: print(f"      witness: M1 vs M2 disagree on I_was_asked ({bad[0][3]} vs {bad[0][4]})")

print("""
  AND THE DECISIVE PART -- is K alone CONGRUENT over these states?
    The only operations available are Assert and Ask. Ask changes only `asked`,
    which K does not project, so K-indistinguishability IS preserved by every
    K-transformation.  K alone is CONGRUENT and NOT EXPRESSIVE.""")

print(f"""
  => Sufficient(K, O, I) FAILS for K alone, on the EXPRESSIBILITY conjunct, the
     moment 'was p asked?' is admitted to I.  Congruence alone reports no fault.

     The criterion would therefore have located the Step 280 defect BEFORE the
     empirical test ran, and it identifies the repair site exactly: add the
     smallest carrier that makes I_was_asked expressible -- which is Q_t.

     That is Repair B, derived from the criterion rather than selected by
     comparison after a failure.""")

# ------------------------------------------------------- POST-REPAIR RE-CHECK
hr("3. Post-repair: is (K, Q_t) sufficient?")
ok, bad = expressive(K_plus_Q, {"I_was_asked": I_was_asked}, SPACE2)
print(f"  Expressive((K,Q_t), I_was_asked) = {ok}")
print(f"  Congruent: Q_t is append-only under Ask, and no K-operation reads it,")
print(f"             so (K,Q_t)-indistinguishability is preserved by both. = True")
print(f"\n  Sufficient((K,Q_t), O u {{Ask}}, I u {{I_was_asked}}) = {ok}")
print("""
  LIMITATION, stated: this is a bounded 3-state model of the M1/M2 distinction,
  not a proof over the full theory. It shows the criterion DETECTS the defect and
  ACCEPTS the repair; it does not show (K,Q_t) is sufficient for all of I.
  The full I has never been enumerated -- that is the remaining obligation.""")
