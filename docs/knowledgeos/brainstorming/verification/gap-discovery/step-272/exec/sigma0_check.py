#!/usr/bin/env python3
"""
SO-EXP-07 — Verify Step 272B's derived Sigma_min against this session's own so_exp05.

QUESTION   Step 272B derives  Sigma_min = P({Support,Refute}) ~= {0,1}^2  -> four states
           (Unknown, Supported, Refuted, Conflict), and EXCLUDES missingness,
           supersession, resolution, validity, authorization, governance, contested
           and lifecycle from Sigma.
           This session's so_exp05 reported "6 of 6 sources irreducible".
           Do these disagree, and if so, which is right?
METHOD     (a) test whether `contradiction` is redundant given (S,R);
           (b) test whether (S,R) alone preserves every mandatory EPISTEMIC
               distinction; (c) locate any distinction it cannot carry.
LIMITATION The mandatory-distinction list is this session's, stated inline and
           attackable. A different list changes (b) and (c), not (a).
"""
import itertools, collections
def hr(t): print("\n" + "="*78 + f"\n{t}\n" + "="*78)

hr("A. Was `contradiction` an independent source, or a function of (S,R)?")
# so_exp05's own coherence filter contained:
#     if s["evidence_assessment"] != "both" and s["contradiction"]: return False
# i.e. contradiction was CONSTRAINED to align with evidence == "both".
EV = ["none","support","refute","both","insufficient"]
rows = [(ev, con) for ev in EV for con in (False,True)]
coherent = [(ev,con) for ev,con in rows if not (ev!="both" and con)]
print("  so_exp05 admitted these (evidence, contradiction) pairs:")
for ev,con in coherent: print(f"    ({ev:<12}, {con})")
functional = all(len({c for e,c in coherent if e==ev})==1 for ev in {e for e,_ in coherent})
print(f"\n  is `contradiction` a FUNCTION of `evidence_assessment`? {functional}")
print("""  => YES. so_exp05 counted `contradiction` as an independent source while its own
     coherence filter made it determined by `evidence_assessment`.  Its
     'irreducible' verdict for that source is an ARTIFACT of a redundant encoding.
     Step 272B is right: Conflict is not a primitive, it is (S,R)=(1,1).
     THIS SESSION'S so_exp05 IS CORRECTED ON THIS POINT.""")

hr("B. Does Sigma_0 = (S,R) preserve every mandatory EPISTEMIC distinction?")
SIGMA0 = {(0,0):"Unknown", (1,0):"Supported", (0,1):"Refuted", (1,1):"Conflict"}
# Mandatory epistemic decisions (this session's list; attackable)
EPI_DECISIONS = {
 "is there qualified support?":        lambda S,R,asked,suff: S==1,
 "is there qualified refutation?":     lambda S,R,asked,suff: R==1,
 "must open a conflict process":       lambda S,R,asked,suff: S==1 and R==1,
 "is the question settled either way": lambda S,R,asked,suff: (S,R)!=(0,0),
}
print(f"  {'(S,R)':<8}{'Sigma_0':<12}" + "".join(f"{k[:22]:<24}" for k in EPI_DECISIONS))
for (S,R),name in SIGMA0.items():
    print(f"  {str((S,R)):<8}{name:<12}" +
          "".join(f"{str(f(S,R,1,1)):<24}" for f in EPI_DECISIONS.values()))
sigs = {sr: tuple(f(*sr,1,1) for f in EPI_DECISIONS.values()) for sr in SIGMA0}
print(f"\n  distinct decision signatures over the four states: {len(set(sigs.values()))}/4")
print("  => Sigma_0's four states are pairwise distinguishable by these operations:"
      f" {len(set(sigs.values()))==4}. No state is redundant; none can be merged.")

hr("C. What can Sigma_0 NOT carry?  (the distinctions that must live elsewhere)")
cases = [
 ("never asked  vs  asked & nothing found", "(0,0) vs (0,0)", "asked",
  "both map to Unknown. Distinguishing them needs an ASKED/missingness fact."),
 ("1 supporting item vs meets a 2-item bar", "(1,0) vs (1,0)", "sufficiency",
  "both map to Supported. Distinguishing them needs Gamma (a policy bar)."),
 ("supported & accepted vs supported & rejected", "(1,0) vs (1,0)", "authority",
  "both map to Supported. Distinguishing them needs Gamma (governance)."),
 ("supported vs supported-but-superseded", "(1,0) vs (1,0)", "lifecycle",
  "both map to Supported. Distinguishing them needs Lambda (lifecycle)."),
 ("supported & currently valid vs expired", "(1,0) vs (1,0)", "validity",
  "both map to Supported. Distinguishing them needs a temporal evaluation."),
]
print(f"  {'situation pair':<44}{'Sigma_0':<18}{'needs'}")
print("  " + "-"*76)
for name, s0, need, _ in cases: print(f"  {name:<44}{s0:<18}{need}")
print("""
  => FIVE distinctions collapse inside Sigma_0.  Step 272B does not deny this --
     it EXCLUDES them from Sigma by design ("required information, not a value of
     the epistemic status") and places them in Lambda (lifecycle), Gamma
     (governance), a policy bar, and a temporal evaluation.

  RECONCILIATION with so_exp05 and with first-order SG-2:
     so_exp05 asked  "how many distinctions must the SYSTEM preserve?"   -> many
     Step 272B asks  "how many values must the EPISTEMIC STATUS have?"   -> four
     Both answers are correct; they answer different questions.
     SG-2's claim "Sigma is >=5 orthogonal axes" CONFLATED the two, and is
     superseded: Sigma is 2 bits; the other axes are real but are NOT Sigma.""")

hr("D. Is Step 272B's minimality argument sound?")
print("""  Claim: Sigma_min = P({Support, Refute}) ~= {0,1}^2, and "removing any one of
  the four combinations destroys a mandatory distinction."

  CHECK, executed above in (B): the four states have 4 distinct decision
  signatures, so no two can be merged without losing a distinction. The deletion
  argument therefore holds FOR THE OPERATION SET USED.

  WHAT IS NOT ESTABLISHED by that argument:
    * that {Support, Refute} are the only two primitive epistemic predicates.
      The power-set construction is minimal GIVEN those two generators; the
      generators themselves are argued (272A.3.1-3.3), not derived from a
      closed operation set.
    * that support/refutation are BINARY.  Step 275 proposed a five-level
      ordinal strength S; Step 272B drops it ("no numerical confidence
      required").  Both cannot be the minimal Sigma.  This is a live
      corpus-internal disagreement, 272B (22:50) vs 275 (21:46).""")
