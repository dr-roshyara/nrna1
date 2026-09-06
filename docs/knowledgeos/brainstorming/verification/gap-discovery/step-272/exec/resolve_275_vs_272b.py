#!/usr/bin/env python3
"""
SO-EXP-09 — RESOLVE Step 275 (graded strength) vs Step 272B (binary polarity).

QUESTION   Is S = {None, Weak, Moderate, Strong, VeryStrong}
           (a) part of Sigma, (b) orthogonal to Sigma, (c) derivable,
           (d) policy-dependent, or (e) obsolete?
METHOD     four tests: nesting, operation-necessity, derivability, measurement
           admissibility.
LIMITATION The mandatory-operation list is 272B's own O_sem; a different list
           changes test 2 only. Test 2's per-operation judgements are argued and
           are the most attackable part.
"""
def hr(t): print("\n"+"="*78+f"\n{t}\n"+"="*78)
GRADES = ["None","Weak","Moderate","Strong","VeryStrong"]

hr("0. PROVENANCE — the scale is real, and it survives a 2240 -> 4 reduction")
print("""  Located in the corpus (NOT invented by Step 275):
    Q14  20260826-182409   "| Support | S | None, Weak, Moderate, Strong, Very Strong |"
    Q16  20260826-183636   same table
    "complete mathematical model" 20260826-174215   same
    closure-03 20260827-133340   "overall_support: SupportLevel"

  Its ORIGINAL home (STEP-TRACE-B2, tracing Q14):
    Sigma = (Acquisition, Support, Resolution, Validity, Conflict)
          = 7 x 5 x 4 x 4 x 4 = 2240 states

  Reduction sequence:
    Q14    08-26        |Sigma| = 2240    five dimensions
    S275   08-30 21:46  |Sigma| = 15      D x S = 3 x 5
    S272B  08-30 22:50  |Sigma| = 4       {0,1}^2

  272B's exclusions map EXACTLY onto Q14's dimensions:
    Acquisition -> excluded (provenance)    Resolution -> excluded (272A.9)
    Validity    -> excluded (272A.10)       Conflict   -> derived as (1,1)
    Support     -> KEPT, collapsed 5 -> 2""")

hr("TEST 1 — ORTHOGONAL or NESTED?")
print("  Reviewer's hypothesis: Sigma _|_ Strength, coexisting.")
print("  Test: can the polarity bit be recovered from the grade?\n")
def s_bit(g): return int(g != "None")
for g in GRADES: print(f"    Support={g:<11} -> S bit = {s_bit(g)}")
combos   = [(b,g) for b in (0,1) for g in GRADES]
possible = [(b,g) for b,g in combos if b == s_bit(g)]
print(f"""
  S = (Support > None) is TOTAL and DETERMINISTIC: the binary is a QUOTIENT of
  the graded scale. Orthogonality would require the combinations to vary freely.
    (S bit, grade) combinations: {len(combos)} constructible, {len(possible)} possible
    impossible: {[c for c in combos if c not in possible]}
  => ORTHOGONALITY REFUTED. The relation is NESTING.
     Sigma_0 is the COARSENING of the graded model, not a peer of it.""")

hr("TEST 2 — Does any mandatory operation NEED the grades?")
needs = [
 ("Assert",False,"writes an assertion; reads no strength"),
 ("Retract",False,"removes evidence; strength recomputed after, not read before"),
 ("Supersede",False,"replacement relation; lifecycle, not strength"),
 ("Infer",False,"derivation structure; strength propagation is a POLICY choice"),
 ("Merge",False,"272B's own law is OR over polarity; grades play no role"),
 ("Support",False,"asserts a link; the grade is a property of the LINK, not of Sigma"),
 ("Refute",False,"symmetric"),
 ("Qualify",True,"decides whether an observation COUNTS as evidence -- reads a bar"),
 ("Assess",True,"produces a verdict against a policy bar -- reads quantity/quality"),
 ("DetectContradiction",False,"needs only S AND R"),
 ("Resolve",False,"272A.9 places Resolution outside Sigma"),
 ("Query",False,"reads whatever Sigma exposes"),
 ("Compare",True,"RANKS two propositions -- needs an order the binary cannot give"),
 ("Replay",False,"replays operations"),
 ("Trace",False,"lineage"),
 ("Authorize",False,"governance; 272A.7 separates it"),
 ("Validate",False,"272A.10 separates validity"),
]
print(f"  {'operation':<22}{'needs grades?':<16}reason")
print("  " + "-"*76)
for o,n,r in needs: print(f"  {o:<22}{('YES' if n else 'no'):<16}{r}")
yes = [o for o,n,_ in needs if n]
print(f"\n  operations needing grades: {yes}   ({len(yes)}/{len(needs)})")

hr("TEST 3 — Must the grade live IN Sigma?")
print("""  Apply the argument that settled D-4 (SO-EXP-08 A9): Sigma is DERIVED from the
  evidence set. Strength is a function of the SAME set:
      S(A)        = exists x in e : polarity(x)=support
      R(A)        = exists x in e : polarity(x)=refute
      strength(A) = f(e, policy)
  Qualify / Assess / Compare all read `e` and a policy. None needs a grade
  PRE-STORED on Sigma.

  DECISIVE: strength is POLICY-RELATIVE; (S,R) is POLICY-INVARIANT.
      two supporting items, bar=2 -> "Moderate";  bar=3 -> "Weak"
      the same evidence, a different policy, a different grade.
      the POLARITY of that evidence does not move when the bar moves.
  A component whose value changes with policy cannot sit inside a
  policy-independent Sigma. Step 271 s271.3 already boxes: Policy NOT-IN Identity(K).""")

hr("TEST 4 — Is the five-level scale well-typed?  (exhaustive, not hand-picked)")
import itertools
ENCODINGS = {
 "A  1,2,3,4":   {"None":0,"Weak":1,"Moderate":2,"Strong":3,"VeryStrong":4},
 "B  1,2,3,10":  {"None":0,"Weak":1,"Moderate":2,"Strong":3,"VeryStrong":10},
 "C  5,6,7,8":   {"None":0,"Weak":5,"Moderate":6,"Strong":7,"VeryStrong":8},
}
for e in ENCODINGS.values():
    assert [e[g] for g in GRADES] == sorted(e[g] for g in GRADES), "encoding must be order-preserving"
print("  All three encodings are strictly increasing => all ADMISSIBLE for an ordinal scale.")
print("  Exhaustive search over portfolios of size 1-3 and thresholds 1..7,")
print("  for a mean-rule decision that FLIPS between admissible encodings:\n")
flips = []
for n in (1,2,3):
    for port in itertools.combinations_with_replacement(GRADES, n):
        for thr in range(1,8):
            verd = {k: (sum(e[g] for g in port)/n) >= thr for k,e in ENCODINGS.items()}
            if len(set(verd.values())) > 1:
                flips.append((port, thr, verd))
print(f"  flipping (portfolio, threshold) pairs found: {len(flips)}")
for port, thr, verd in flips[:4]:
    print(f"    portfolio {list(port)}, rule mean >= {thr}:")
    for k,e in ENCODINGS.items():
        print(f"        {k:<14} mean={sum(e[g] for g in port)/len(port):<7.3f} PROCEED={verd[k]}")
print(f"""
  => {len(flips)} distinct decision rules over this scale FLIP under an
     order-preserving re-encoding. The scale is at most ORDINAL, and any
     average / threshold / percentage rule over it is MEANINGLESS in Roberts'
     sense (first-order MT-3, here re-established exhaustively rather than by a
     hand-picked case).
     MT-5 also stands: no empirical relational structure was ever given for the
     scale, so even its ORDINALITY is assumed rather than established.
     NOTE, honestly: not every rule flips -- min/max/median are invariant, and
     many (portfolio, threshold) pairs agree. The finding is that MEANINGFULNESS
     must be checked per rule, not that every numeric use is wrong.""")

hr("RESOLUTION")
print("""  The five options, adjudicated:

    (a) part of Sigma       -> NO.  policy-relative (T3); Sigma is not.
    (b) orthogonal to Sigma -> NO.  REFUTED (T1) -- it NESTS; 5 of 10 (bit,grade)
                                    combinations are impossible.
    (c) derivable           -> YES. strength = f(evidence, policy), over the same
                                    evidence set Sigma is derived from.
    (d) policy-dependent    -> YES. and that is precisely what excludes it from Sigma.
    (e) obsolete            -> NO.  three operations need it: Qualify, Assess, Compare.

  THEREFORE:

      Sigma    = P({Support, Refute})   policy-INVARIANT, derived from evidence
      Strength = f(evidence, policy)    policy-RELATIVE, an ASSESSMENT output

  Sigma is the coarsest POLICY-INVARIANT abstraction of the evidence set.
  Strength is a POLICY-RELATIVE refinement of the SAME evidence set.

  They are neither competing definitions of Sigma nor orthogonal dimensions.
  Step 275 and Step 272B answer DIFFERENT questions, and the contradiction
  dissolves once Assessment is separated from Sigma -- which s271.10-11
  (Assessment != Truth, Assessment != Inference) and 272A.7 already require.

  NOT SETTLED BY THIS:
    * the CARDINALITY. Nothing fixes five levels. Qualify/Assess/Compare need an
      ORDER, not five names. The 5 levels are an unjustified inheritance from Q14.
    * whether Compare is mandatory at all -- it appears in O_sem with no definition.""")
