#!/usr/bin/env python3
"""
SO-EXP-05 — Discharge part of the commissioned STEP 272.

QUESTION   Step 271 s271.36 commissions:
             "Derive the minimum epistemic-status structure from
              distinguishability, contradiction, missingness, evidence
              assessment, supersession and inference — while explicitly
              separating epistemic state from lifecycle and governance state."
             "What information must an epistemic result preserve so that no
              mandatory KnowledgeOS operation loses a distinction?"
           Step 272 DOES NOT EXIST (highest step = 271).
INPUT      The SIX sources Step 272 names, each modelled as an observable fact.
METHOD     Minimal-sufficient-statistic method (the same one s271.36 says
           "produced the strongest result for K"): enumerate the situations the
           six sources generate; enumerate the distinctions mandatory operations
           must preserve; compute the coarsest partition that preserves them all.
RESULT     the minimum Sigma structure, derived rather than chosen.
LIMITATION The operation set used is the class-1/class-2 set derived in
           SO-EXP-03.  A different mandatory-distinction list changes the answer;
           the list is stated explicitly so it can be attacked.
INDEPENDENCE  The METHOD is the corpus's (s271.36).  The first-order pass ran a
           similar necessity test before Step 271 existed; this run uses Step
           271's own six inputs, so it is convergent, not independent, on the
           conclusion that Sigma is multi-axial.
"""
import itertools, collections
def hr(t): print("\n" + "="*78 + f"\n{t}\n" + "="*78)

hr("SO-EXP-05  Deriving the minimum Sigma structure (Step 272, uncommissioned)")

# ---- the six sources Step 272 names, as observable facts -------------------
SOURCES = {
 "distinguishability": ["asked", "not_asked"],          # was the question posed?
 "evidence_assessment":["none","support","refute","both","insufficient"],
 "contradiction":      [False, True],                   # an active contradiction exists
 "missingness":        ["present","absent"],            # is the referent obtainable?
 "supersession":       [False, True],                   # replaced by a successor
 "inference":          ["asserted","derived"],          # how it entered K
}
print("  Step 272's six named sources, with the value sets this experiment gives them:")
for k,v in SOURCES.items(): print(f"    {k:<21} {v}")

SITS = [dict(zip(SOURCES, combo)) for combo in itertools.product(*SOURCES.values())]
# drop incoherent combinations
def coherent(s):
    if s["distinguishability"]=="not_asked" and s["evidence_assessment"]!="none": return False
    if s["distinguishability"]=="not_asked" and s["contradiction"]: return False
    if s["evidence_assessment"]!="both" and s["contradiction"]: return False
    return True
SITS = [s for s in SITS if coherent(s)]
print(f"\n  coherent situations generated: {len(SITS)}")

# ---- the distinctions mandatory operations must preserve -------------------
# Each is a decision some class-1 or class-2 operation must be able to make.
DECISIONS = {
 "usable in a determination": lambda s: (s["evidence_assessment"]=="support"
        and not s["contradiction"] and not s["supersession"] and s["missingness"]=="present"),
 "must open a conflict process": lambda s: s["contradiction"],
 "must be shown as a gap": lambda s: (s["distinguishability"]=="asked"
        and s["evidence_assessment"]=="none"),
 "out of scope (never asked)": lambda s: s["distinguishability"]=="not_asked",
 "needs more evidence": lambda s: s["evidence_assessment"]=="insufficient",
 "is contradicted": lambda s: s["evidence_assessment"]=="refute",
 "points at a successor": lambda s: s["supersession"],
 "referent unobtainable": lambda s: s["missingness"]=="absent",
 "retract-on-premise-withdrawal": lambda s: s["inference"]=="derived",
}
print(f"  mandatory distinctions (from the class-1/2 operations of SO-EXP-03): {len(DECISIONS)}")

sig = {i: tuple(f(s) for f in DECISIONS.values()) for i,s in enumerate(SITS)}
classes = collections.defaultdict(list)
for i,g in sig.items(): classes[g].append(i)
print(f"\n  => coarsest partition preserving every distinction: "
      f"{len(classes)} equivalence classes over {len(SITS)} situations")

# ---- which sources are irreducible? ---------------------------------------
hr("Which of the six sources is IRREDUCIBLE?")
def partition_without(drop):
    proj = {}
    for i,s in enumerate(SITS):
        proj.setdefault(tuple(v for k,v in s.items() if k!=drop), []).append(i)
    harmful = sum(1 for grp in proj.values() if len({sig[i] for i in grp})>1)
    return len(proj), harmful
print(f"  {'source dropped':<22}{'classes':>9}{'harmful collisions':>21}")
print("  " + "-"*52)
base_cls, base_harm = partition_without("__none__")
print(f"  {'(keep all)':<22}{base_cls:>9}{base_harm:>21}")
irreducible=[]
for k in SOURCES:
    c,h = partition_without(k)
    if h>base_harm: irreducible.append(k)
    print(f"  {k:<22}{c:>9}{h:>21}")
print(f"\n  IRREDUCIBLE sources: {irreducible}  ({len(irreducible)}/6)")

# ---- the derived structure -------------------------------------------------
hr("DERIVED MINIMUM SIGMA STRUCTURE")
print(f"""  Sigma is not an enum.  The minimum structure that loses no mandatory
  distinction is the product of the irreducible sources:

      Sigma = {' x '.join(irreducible)}

  cardinality of the full product : {len(SITS)} coherent situations
  cardinality after quotienting by
  the mandatory distinctions      : {len(classes)} classes

  So a faithful Sigma needs {len(classes)} distinguishable values, and they are NOT
  linearly ordered -- they are points in a {len(irreducible)}-dimensional product.

  SEPARATION Step 272 explicitly requires:
    epistemic  : distinguishability, evidence_assessment, contradiction, missingness
    lifecycle  : supersession
    governance : (none of the six -- authority is a SEPARATE axis, s187, and
                  s271 lists it outside Sigma)
    derivation : inference   (a provenance/lineage fact, not an epistemic one)

  => Even within Step 272's own six inputs, TWO are not epistemic at all.
     A Sigma built from all six would re-conflate exactly what s272 demands be
     separated.  The epistemic core is FOUR-dimensional.""")

# epistemic-only partition
EPI = ["distinguishability","evidence_assessment","contradiction","missingness"]
proj = collections.defaultdict(list)
for i,s in enumerate(SITS): proj[tuple(s[k] for k in EPI)].append(i)
print(f"\n  epistemic-only projection: {len(proj)} distinct epistemic states")
EPI_DEC = {k:v for k,v in DECISIONS.items()
           if k not in ("points at a successor","retract-on-premise-withdrawal")}
esig = {i: tuple(f(s) for f in EPI_DEC.values()) for i,s in enumerate(SITS)}
ecls = collections.defaultdict(list)
for i,g in esig.items(): ecls[g].append(i)
print(f"  epistemic distinctions to preserve: {len(EPI_DEC)}")
print(f"  minimum epistemic Sigma values: {len(ecls)}")
print("""
  This partially discharges the commissioned Step 272.  It does NOT close it:
  the value sets above are this session's modelling of the six named sources,
  and a different (defensible) value set changes the counts.  What is robust and
  does not depend on the value sets is the STRUCTURAL result: Sigma is a product
  of independently-varying axes, and two of Step 272's own six inputs belong to
  other axes.""")
