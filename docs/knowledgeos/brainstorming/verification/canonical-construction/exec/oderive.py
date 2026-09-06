#!/usr/bin/env python3
"""
Can O (the mandatory operation set) be DERIVED rather than chosen?

Derivation principle under test (stated, then applied):
  An operation o is MANDATORY iff removing o makes some corpus-stated
  non-collapse law INEXPRESSIBLE -- i.e. two states the corpus requires to be
  distinct become identical, or a required distinction cannot be reached.

This is a derivation, not a preference: the laws are corpus text, the forcing
relation is entailment, and the result is whatever survives.
"""
# corpus non-collapse laws, with measured repetition counts (evidence weight)
LAWS = {
 "Expression != Meaning":        71, "Unknown != False":            42+27+16+12,
 "Evidence != Authority":        34+11,"Candidate != Knowledge":      26,
 "Identity != State":            23+18,"Observation != Interpretation":21,
 "Authority != Truth":           20,  "Knowledge != Understanding":  20,
 "UNKNOWN != ABSENT":            19,  "Determination != Decision":   19,
 "Provenance != Lineage":        18,  "Evidence != Knowledge":       18,
 "Decision != Authorization":    18+11,"Evidence != Justification":  17,
 "Policy != Authority":          16,  "Capability != Authority":     16,
 "State != History":             12,  "Validation != Assessment":    11,
 "EpistemicStrength != GovernanceStatus": 11,
 "Observation != Evidence":      20,  "Proposition != Assertion":    10,
 "UNRESOLVED != INVALID":         9,
 "Remove != Withdraw":            1,  "Reject != Remove":            1,
 "Validate !~ Transform":         1,  "Command != Transformation":   1,
}
# which operation(s) each law FORCES to exist, and why
FORCES = {
 "Remove != Withdraw":            [("Remove","a state must be able to lose a member"),
                                   ("Withdraw","retraction that is not deletion must be reachable")],
 "Reject != Remove":              [("Reject","inadmissibility must be recordable without deletion")],
 "Candidate != Knowledge":        [("Promote","a candidate must be able to become knowledge")],
 "UNKNOWN != ABSENT":             [("Add","absence is the complement of presence: Add must exist"),
                                   ("<D_t>","recognising a dimension without asserting a value")],
 "UNRESOLVED != INVALID":         [("Assess","unresolved is an assessment outcome, not a deletion")],
 "Validation != Assessment":      [("Validate","structural check"),("Assess","epistemic grading")],
 "Validate !~ Transform":         [("Validate","must NOT be a state transformation")],
 "Decision != Authorization":     [("Authorize","authorization is a distinct governance operation")],
 "Determination != Decision":     [("Determine","the recorded outcome is not the act that produced it")],
 "Evidence != Authority":         [("Authorize","authority cannot be produced by evidence accumulation")],
 "State != History":              [("Replay","history must be foldable back to state")],
 "Identity != State":             [("Revise","content may change while identity persists"),
                                   ("Supersede","identity may change while content persists")],
 "Proposition != Assertion":      [("Add","asserting is an act distinct from the proposition")],
 "Observation != Evidence":       [("Qualify","an observation must be able to BECOME evidence")],
 "Provenance != Lineage":         [("Derive","lineage arises from derivation operations")],
 "EpistemicStrength != GovernanceStatus": [("Assess","Sigma axis"),("Authorize","Gamma axis")],
 "Expression != Meaning":         [("<serialize>","canonical form must be separable from content")],
}
forced = {}
for law, ops in FORCES.items():
    for op, why in ops:
        forced.setdefault(op, []).append((law, why, LAWS.get(law,0)))

print("="*78); print("DERIVATION TEST — is O forced by the corpus's own non-collapse laws?"); print("="*78)
print(f"  non-collapse laws extracted (distinct) : {len(LAWS)}")
print(f"  laws with a forcing consequence        : {len(FORCES)}")
print(f"  operations FORCED                      : {len([o for o in forced if not o.startswith('<')])}")
print()
print("  OPERATION      FORCED BY                                    weight")
print("  " + "-"*74)
for op in sorted(forced, key=lambda o:-max(w for _,_,w in forced[o])):
    laws = forced[op]
    w = max(x[2] for x in laws)
    tag = "" if not op.startswith("<") else "  (not an operation - a STRUCTURE)"
    print(f"  {op:<14s} {laws[0][0]:<44s} {w:>4d}{tag}")
    for l,_,ww in laws[1:]:
        print(f"  {'':<14s} {l:<44s} {ww:>4d}")

DERIVED = sorted(o for o in forced if not o.startswith("<"))
print()
print("="*78); print("RESULT 1 — what IS derivable"); print("="*78)
print(f"  |O_forced| = {len(DERIVED)}")
print("  " + ", ".join(DERIVED))

# Step 256's candidate vocabulary + 259.7's additions
S256 = ["Add","Remove","Revise","Transform","Supersede","Merge","Split","Reject","Withdraw"]
S259 = ["Validate","Assess","Authorize","Promote","Reintroduce","Replay"]
CAND = S256 + S259
print()
print("="*78); print("RESULT 2 — what is NOT forced by any law"); print("="*78)
unforced = [o for o in CAND if o not in DERIVED]
for o in unforced:
    print(f"  {o:<14s} named in the corpus, forced by NO non-collapse law")
print()
print(f"  forced-but-unnamed (new): {[o for o in DERIVED if o not in CAND]}")

print()
print("="*78); print("RESULT 3 — the closure question"); print("="*78)
print("  Is O_forced CLOSED?  i.e. does membership follow from the laws alone?")
print()
print("  NO -- and the reason is exact:")
print("   * 'Transform', 'Merge', 'Split' are named in 256.2 and forced by NO law.")
print("     A corpus that never states 'Merge != X' gives no entailment that Merge")
print("     must exist. Their necessity is asserted, not entailed.")
print("   * 'Qualify' and 'Determine' ARE forced (by Observation!=Evidence and")
print("     Determination!=Decision) but appear in NEITHER 256.2 NOR 259.7.")
print("     The corpus's own enumeration MISSES two operations its own laws force.")
print()
print("  => The laws under-determine O in one direction and over-determine the")
print("     enumeration in the other. Set-membership is therefore NOT derivable.")
print()
print("="*78); print("RESULT 4 — what IS derivable without any human input"); print("="*78)
print("  (a) a NON-EMPTY LOWER BOUND: the",len(DERIVED),"forced operations MUST be in O.")
print("  (b) an UPPER BOUND: O subset of (256.2 union 259.7 union forced) =",
      len(set(CAND)|set(DERIVED)),"candidates.")
print("  (c) the CLASS PARTITION (259.7) and the congruence rule (259.8) --")
print("      these are derivable, because congruence is a property of an algebra's")
print("      operations on its carrier: an operation reading H is not an operation on K.")
print("  (d) NOT derivable: which members of (upper - lower) are MANDATORY.")
print()
print("  |lower| =",len(DERIVED)," |upper| =",len(set(CAND)|set(DERIVED)),
      " |undetermined band| =",len(set(CAND)|set(DERIVED))-len(DERIVED))
