#!/usr/bin/env python3
r"""
inventory.py — TASK 1: the COMPLETE candidate operation universe, mechanically
assembled from the corpus, with per-source membership and pairwise disagreement.

COMMISSION GN-79 / GN-80. NOTHING HERE IS RATIFIED.

Every entry below was read out of the named artifact by direct inspection.
The script does no interpretation: it unions, diffs and counts.
"""
from itertools import combinations

# ---------------------------------------------------------------- THE SOURCES
# key -> (human label, enumerated operation names, excluded names)
SRC = {
"256.2": ("step 256 §256.2 — 'the candidate operation vocabulary' (BOXED, and "
          "self-labelled 'a reconstruction target')",
    ["Add","Remove","Revise","Transform","Supersede","Merge","Split","Reject","Withdraw"], []),
"256.x": ("step 256 §§256.19-256.22 — signed operations outside the transformation family",
    ["Validate","Approve"], ["Command","Event"]),
"257":   ("step 257 — the four discriminating operations + two named-only observers",
    ["Revise","Transform","Supersede","Merge","ExplainRevision","SupersessionHistory"], []),
"259.7": ("step 259 §259.7/§259.8 — the operations the prompt 'requires searching for', "
          "plus the primary-congruence state-transforming set",
    ["Split","Reject","Withdraw","Validate","Assess","Authorize","Promote","Reintroduce",
     "Replay"], []),
"272A":  ("step 272A §272A.14/§272A.17 — O_sem = O_S u O_E u O_O u O_H u O_G, "
          "with the 19-row Mandatory Distinction Register",
    ["Assert","Retract","Supersede","Infer","Merge",
     "Support","Refute","Qualify","Assess","DetectContradiction","Resolve",
     "Query","Compare","Identity","Equal",
     "Replay","Trace",
     "Authorize","Validate"],
    ["Serialize","Deserialize","Save","Load","Delete"]),
"277":   ("step 277 §277.2/§277.3 — the reconstructed historical operation inventory",
    ["Assert","Retract","Supersede","Merge","Split",
     "Support","Refute","Qualify","LinkEvidence",
     "Trace","Replay","LineageQuery","ProvenanceQuery",
     "Authorize","Validate","Approve","Reject","ChangePolicy",
     "Query","Compare","Evaluate","Explain"],
    ["Save","Load","Serialize","Deserialize","Delete"]),
"277.T": ("step 277 §277.31 — T_candidate, the candidate state-transition kernel "
          "('T_candidate != T_minimal')",
    ["Assert","Retract","Supersede","Merge","Split","LinkEvidence"], []),
"249":   ("step 249 §249.4/§249.19 — the operation matrix",
    ["Transform","Validate","Add","Revise","Merge","Supersede","Split","Remove",
     "Reject","Replay"], []),
"250":   ("step 250 §250.3 + §§250.11-250.12 — the corpus-derived candidate inventory",
    ["Create","Observe","Infer","Assess","Validate","Accept","Reject","Revise","Supersede",
     "Merge","Split","Withdraw","Commit","Transform","Replay","Remove","Add"], []),
"232":   ("step 232 §232.3 + CQRS layer — 'the core operations'",
    ["Add","Remove","Revise","Merge","Split","Supersede","Validate","Reject","Transform",
     "Apply","Reassess"], ["Command","Event"]),
"algD":  ("verification/KNOWLEDGE-STATE-ALGEBRA.md §2 — 'the twelve operations' "
          "(+ split, query, which carry laws in §4 but have no row)",
    ["create","assert","retract","relate","merge","supersede","refine","resolve","remove",
     "replay","validate","assess","split","query"], []),
"algE":  ("verification/TRANSFORMATION-CANONICAL-MODEL.md §2 — the fully instantiated "
          "transformation, five operations + identity",
    ["assert","relate","retract","merge","noop"], []),
"oderive":("canonical-construction/02-OPERATION-UNIVERSE.md + exec/oderive.py — the "
          "14 operations reported as FORCED by the corpus's non-collapse laws",
    ["Authorize","Revise","Supersede","Promote","Qualify","Add","Determine","Derive",
     "Replay","Assess","Validate","Remove","Withdraw","Reject"], []),
"274":   ("step 274 §274.6/§274.30 — candidate core transitions ('every cell of the "
          "closure matrix is ?')",
    ["Assert","Retract","Supersede","Contest","Assess","Merge","Derive","QualifyEvidence",
     "Replay","Validate"], []),
"276":   ("step 276 §276.4 — O_core = O_S u O_E u O_H u O_G u O_Q u O_X, SIX families, "
          "INCLUDING the representation ops 272A and 277 both EXCLUDE",
    ["Assert","Retract","Supersede","Merge","Split",
     "Support","Refute","Qualify","LinkEvidence",
     "Trace","Replay","LineageQuery","ProvenanceQuery",
     "Authorize","Validate","ChangePolicy","Approve","Reject",
     "Query","Compare","Evaluate","Explain",
     "Save","Load","Serialize","Deserialize","Delete"], []),
"281x":  ("step-281/exec/test_repair_selection.py — the set the EXECUTED harness calls "
          "'the mandatory operation set'",
    ["assert","relate","retract","merge","replay","validate","assess","contradicts",
     "supersede"], []),
"hand":  ("handoff/02-O-CORE-AUTHORITATIVE-DETERMINATION.md — the executed algebra's "
          "additional member",
    ["relate"], []),
}

# ---- PRE-CANONICAL / EARLIER-LANE enumerations. Counted SEPARATELY: these are
# ---- historical evidence of search (FA-1 L5 rule: history is never authority),
# ---- and folding them into the headline count would overstate the live universe.
HISTORICAL = {
"007 O_K":  ["Introduce","Corroborate","Qualify","Contradict","Supersede","Resolve",
             "Retract","Invalidate"],
"009 R_K":  ["Contextualize","Corroborate","Downgrade","Retract","Supersede","Accept",
             "Reject","Defer","Resolve"],
"051 Sigma":["Observe","RegisterEvidence","AssertClaim","ValidateClaim","CreateDecision",
             "Authorize","Execute","ObserveOutcome","ReviseKnowledge"],
"025a-5":   ["Add","Assess","Commit","Conflict","Resolve","Invalidate","Retract",
             "Supersede","Derive"],
"187 R":    ["Record","Derive","Evaluate","Determine","Approve","Decide","Execute"],
"205 cmds": ["CreateProposition","SupersedeProposition","RegisterEvidence",
             "ValidateEvidence","SupersedeEvidence","CreateAssessment",
             "ReassessProposition","ProposeDecision","ApproveDecision","RejectDecision",
             "SupersedeDecision","IssueAction","CancelAction","CompleteAction"],
"032 pipe": ["Observe","Capture","Infer","Model","Predict","Decide","Authorize","Validate"],
"034 I":    ["Retrieve","Observe","Measure","Validate","Clarify","Experiment","Simulate",
             "Ask","Compare","Recalculate"],
"025z Lord":["Observe","Ask","Infer","AcquireEvidence","Decide","RequestApproval","Act",
             "Abstain"],
"kernel":   ["assert","relate","withdraw","restatus","explain","query","contest_count"],
}

# names that denote the SAME operation under different spellings/eras.
# Only case/era aliases are merged. Genuine semantic disputes are NOT merged.
ALIAS = {"create":"Create","assert":"Assert","retract":"Retract","merge":"Merge",
         "supersede":"Supersede","remove":"Remove","replay":"Replay",
         "validate":"Validate","assess":"Assess","split":"Split","query":"Query",
         "refine":"Refine","relate":"Relate"}
# NAME COLLISIONS: same token, demonstrably different semantics across sources.
COLLISION = {
 "resolve": ("algD 'resolve' = ADD A resolves-EDGE (a relation constructor)",
             "272A 'Resolve' = RESOLVE AN UNRESOLVED EPISTEMIC PROBLEM (O_E)"),
 "Create":  ("250 §250.4 'Create != necessarily Add' — a domain LIFECYCLE transition",
             "algD 'create' = produce the EMPTY state, output `0`"),
 "Reject":  ("256.11 Status(x): Proposed -> Rejected — an EPISTEMIC disposition on an item",
             "277 §277.3 'Reject | Governance transition | Governance state | External'"),
 "Transform":("256.7/249 = the generic state transformer K x Params -> K",
             "250 §250.23 — THREE distinct senses: epistemic / state / representation"),
}

def norm(n): return ALIAS.get(n, n)

REPR_EXCLUDED = {"Serialize","Deserialize","Save","Load","Delete"}

def main():
    P="="*78
    print(P); print("TASK 1 — THE COMPLETE CANDIDATE OPERATION UNIVERSE (mechanically unioned)")
    print("commission GN-79 / GN-80 · NOTHING RATIFIED"); print(P)

    sets = {k: set(map(norm, v[1])) for k,v in SRC.items()}
    excl = {k: set(map(norm, v[2])) for k,v in SRC.items()}
    universe = set().union(*sets.values())
    all_excl = set().union(*excl.values())

    print(f"\nsources inventoried                        : {len(SRC)}")
    for k,(lab,ops,ex) in SRC.items():
        print(f"  {k:<9s} |{len(set(map(norm,ops))):>3d} named |{len(set(map(norm,ex))):>2d} excluded | {lab[:88]}")

    print(f"\nDISTINCT SEMANTIC-CORE OPERATION NAMES     : {len(universe)}")
    print(f"names EXCLUDED by at least one source      : {len(all_excl)}  "
          f"({', '.join(sorted(all_excl))})")
    print(f"grand total distinct names (core+excluded) : {len(universe | all_excl)}")

    print("\n--- MEMBERSHIP MATRIX -------------------------------------------------------")
    keys = list(SRC)
    hdr = "op".ljust(21) + "".join(k[:6].rjust(8) for k in keys) + "   n"
    print(hdr); print("-"*len(hdr))
    def count(o): return sum(o in sets[k] for k in keys)
    for op in sorted(universe, key=lambda o: (-count(o), o)):
        row = op.ljust(21)
        for k in keys:
            if op in sets[k]:   row += "X".rjust(8)
            elif op in excl[k]: row += "-".rjust(8)
            else:               row += " ".rjust(8)
        print(row + f"{count(op):>4d}")
    print("X = enumerated as an operation   - = explicitly EXCLUDED by that source")

    print("\n--- SOURCES THAT NAME AN OPERATION EXACTLY ONCE (singletons) ----------------")
    once = [op for op in sorted(universe) if sum(op in sets[k] for k in keys) == 1]
    print(f"{len(once)} names appear in exactly ONE source:")
    for op in once:
        src = [k for k in keys if op in sets[k]][0]
        print(f"  {op:<22s} only in {src}")

    print("\n--- PAIRWISE DISAGREEMENT (symmetric difference over the semantic core) -----")
    print("Only the enumerations that CLAIM to be an operation universe are compared.")
    claim = ["256.2","259.7","272A","277","277.T","249","250","232","algD","oderive",
             "274","276","281x"]
    print("pair".ljust(20) + "|A\\B|".rjust(7) + "|B\\A|".rjust(7) + "  Jaccard   verdict")
    print("-"*78)
    for a,b in combinations(claim,2):
        A,B = sets[a], sets[b]
        j = len(A&B)/len(A|B)
        print(f"{a+' vs '+b:<20s}{len(A-B):>7d}{len(B-A):>7d}{j:>10.2f}   "
              f"{'IDENTICAL' if A==B else ('A subset B' if A<B else ('B subset A' if B<A else 'INCOMPARABLE'))}")
    print("\nNO TWO ENUMERATIONS AGREE." if not any(sets[a]==sets[b] for a,b in combinations(claim,2))
          else "\nsome enumerations agree.")

    print("\n--- THE TWO NAMED UNRECONCILED OPERATIONS (commission T-3) ------------------")
    for op in ("Split","LinkEvidence"):
        srcs = [k for k in keys if op in sets[k]]
        print(f"  {op:<14s} named by: {', '.join(srcs)}")

    print("\n--- FORCED-BUT-UNNAMED / NAMED-BUT-UNFORCED (oderive's two-sided gap) ------")
    forced = sets["oderive"]
    named  = sets["256.2"] | sets["259.7"]
    print(f"  forced by oderive yet ABSENT from 256.2 u 259.7 : "
          f"{', '.join(sorted(forced - named))}")
    print(f"  named in 256.2 u 259.7 yet forced by NO law     : "
          f"{', '.join(sorted(named - forced))}")

    print("\n--- NAME COLLISIONS (same token, different semantics) -----------------------")
    for tok,(a,b) in COLLISION.items():
        print(f"  {tok}:\n      (1) {a}\n      (2) {b}")

    print("\n--- OPERATION-COUNT CLAIMS ACROSS THE RECORD -------------------------------")
    for k in claim:
        print(f"  {k:<9s} |O| = {len(sets[k]):>2d}")
    print(f"  UNION     |O| = {len(universe):>2d}   <- the complete candidate universe")
    inter = set.intersection(*[sets[k] for k in claim])
    print(f"  INTERSECTION of all claiming enumerations = {len(inter)}  "
          f"({', '.join(sorted(inter)) if inter else 'EMPTY'})")
    print("\n--- THE ONE THING EVERY SOURCE AGREES ON --------------------------------")
    reprs = {"Serialize","Deserialize","Save","Load"}
    exc_by = [k for k in keys if reprs & excl[k]]
    inc_by = [k for k in keys if reprs & sets[k]]
    print(f"  representation ops {sorted(reprs)}")
    print(f"    EXCLUDED by : {exc_by}")
    print(f"    INCLUDED by : {inc_by}")
    print(f"  -> AGREEMENT HOLDS: {not inc_by}")

    print("\n--- PRE-CANONICAL / EARLIER-LANE ENUMERATIONS (counted separately) ------")
    hu = set()
    for k,v in HISTORICAL.items():
        hu |= set(map(norm,v))
        print(f"  {k:<12s} |O| = {len(set(map(norm,v))):>2d}")
    print(f"  historical union                 |O| = {len(hu):>2d}")
    print(f"  historical names NOT in the live universe : {len(hu - universe)}")
    print("    " + ", ".join(sorted(hu - universe)))
    print(f"  GRAND UNION (live + historical)   |O| = {len(universe | hu):>2d}")
    print("\n" + P)

if __name__ == "__main__":
    main()
