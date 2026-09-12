"""KR-ZERO-2026-09-02-I — TYPED FACET VOCABULARY + EXTENDED SUITE.  [EXP]

Every type carries a PROVENANCE: the corpus item, invariant or prior experimental
result that FORCES the distinction.  A type with no provenance is invented, and
inventing types would make separation trivially achievable and therefore worthless.

A parsimony test then removes each type and checks whether separation actually needed it.
"""
import itertools

# ============================================================ typed vocabularies
# facet -> { type : provenance }
VOCAB = {
 "G_value": {
   "none":          "|A_t| = 0 (Determine, v1.1 run); ZI-01 Unknown ≠ Absent",
   "single":        "|A_t| = 1",
   "rivals":        "|A_t| > 1 (underdetermined); ZI-05 Unresolved ≠ False",
   "contradictory": "Experiment G PB-2: {p,¬p} has no value in {T,F,U}; ZI-09",
 },
 "G_dimension": {
   "known":   "ZI-06 UnknownDimension ≠ UnknownValue",
   "unknown": "ZI-06; corpus Z2 'unknown dimension' gap boundary",
 },
 "G_assessment": {
   "not-assessed":                "ZI-02 NotAssessed ≠ LowConfidence; state S9",
   "insufficient-magnitude":      "Experiment J diagnosis: S3 below admissibility threshold",
   "insufficient-discrimination": "Experiment J diagnosis: S5 non-discriminating",
   "sufficient":                  "the complement; required for a satisfied requirement",
 },
 "G_evidence": {
   "none":             "ZI-04 NoEvidence ≠ InvalidEvidence",
   "present":          "the complement",
   "invalid":          "ZI-04",
   "not-yet-gathered": "four-way unknown taxonomy: UNOBSERVED ≠ absent (a channel exists)",
 },
 "G_interpretation": {
   "resolved":      "the complement",
   "ambiguous":     "v1.1 run AD-9: one token, several admissible readings",
   "uninterpreted": "four-way unknown taxonomy: UNINTERPRETED",
 },
 "G_conflict": {
   "none":                    "the complement",
   "unresolved":              "ZI-09 Conflict ≠ Invalidity",
   "resolved-by-supersession":"CE-3: supersession named in v1.2 §VIII",
 },
 "G_assumption": {
   "explicit": "v1.1 run P17: assumptions enter warrant assessment",
   "implicit": "Zero Concept §17 'Zero and hidden assumptions'",
   "unknown":  "Zero Concept §17",
 },
 "G_temporal": {
   "current":                "the complement",
   "stale":                  "CE-3: superseded evidence never retired; ZI-08",
   "no-temporal-semantics":  "Experiment G A4: valid/observation/record time undistinguished",
 },
 "G_scope": {
   "in-scope":     "the complement",
   "out-of-scope": "ZI-03 NotApplicable ≠ Unknown; Zero Concept §20 applicability",
 },
 "G_model": {
   "available":       "the complement",
   "no-evaluator":    "Experiment G: governance/consistency have none",
   "delta-undefined": "Step 290 open",
   "no-ordering":     "⪰ undefined",
 },
 "G_observability": {
   "observed":                "the complement",
   "channel-exists-unqueried":"four-way taxonomy: UNOBSERVED",
   "no-channel":              "four-way taxonomy: UNOBSERVABLE; state S4",
 },
}

# ZI invariants that the ELEVEN facets cannot host --------------------------------
UNHOSTED = {
 "ZI-07 Representation ≠ Reality":
   "§26's eleven facets contain NO representation/reality facet. A 12th facet would be "
   "required; this experiment does not invent one.",
 "ZI-10 NoKnownGap ≠ Complete":
   "a property of the boundary SET (B = ∅ ≠ complete), not of any facet. It is a "
   "meta-level invariant and cannot be expressed facet-wise.",
}

# ============================================================ extended state suite
def S(**kw): return tuple(sorted(kw.items()))

STATES = {
 # --- the nine from KR-ZERO-H -------------------------------------------------
 "S1_contradiction":   S(G_value="contradictory", G_conflict="unresolved"),
 "S2_absence":         S(G_value="none", G_evidence="none"),
 "S3_evidence_insufficient": S(G_value="single", G_evidence="present",
                               G_assessment="insufficient-magnitude"),
 "S4_unobservable":    S(G_value="none", G_observability="no-channel"),
 "S5_underdetermined": S(G_value="rivals", G_evidence="present",
                         G_assessment="insufficient-discrimination"),
 "S6_theory_incomplete": S(G_model="no-evaluator"),
 "S7_agent_remediable":S(G_value="none", G_evidence="not-yet-gathered",
                         G_observability="channel-exists-unqueried"),
 "S8_not_applicable":  S(G_scope="out-of-scope"),
 "S9_not_assessed":    S(G_evidence="present", G_assessment="not-assessed"),
 # --- ADDED to exercise the unused facets and the untestable invariants --------
 "S10_invalid_evidence":  S(G_value="single", G_evidence="invalid"),          # ZI-04, ZI-09
 "S11_unknown_dimension": S(G_dimension="unknown", G_value="none"),           # ZI-06
 "S12_stale":             S(G_value="single", G_temporal="stale"),            # ZI-08
 "S13_ambiguous":         S(G_value="rivals", G_interpretation="ambiguous"),  # ZI-05
 "S14_implicit_assumption": S(G_value="single", G_assumption="implicit"),
 "S15_no_known_gap":      S(G_value="single", G_assessment="sufficient",
                            G_evidence="present"),                            # ZI-10
 "S16_uninterpreted":     S(G_evidence="present", G_interpretation="uninterpreted"),
 "S17_no_ordering":       S(G_model="no-ordering"),
 "S18_delta_undefined":   S(G_model="delta-undefined"),
}

CRITICAL = {"Contradiction":"S1_contradiction", "Absence":"S2_absence",
            "EvidenceInsufficiency":"S3_evidence_insufficient",
            "Unobservable":"S4_unobservable", "Underdetermined":"S5_underdetermined",
            "TheoryIncomplete":"S6_theory_incomplete"}

ZI_PAIRS = {
 "ZI-01 Unknown ≠ Absent":              ("S7_agent_remediable","S2_absence"),
 "ZI-02 NotAssessed ≠ LowConfidence":   ("S9_not_assessed","S3_evidence_insufficient"),
 "ZI-03 NotApplicable ≠ Unknown":       ("S8_not_applicable","S5_underdetermined"),
 "ZI-04 NoEvidence ≠ InvalidEvidence":  ("S2_absence","S10_invalid_evidence"),
 "ZI-05 Unresolved ≠ False":            ("S13_ambiguous","S10_invalid_evidence"),
 "ZI-06 UnknownDimension ≠ UnknownValue":("S11_unknown_dimension","S2_absence"),
 "ZI-08 PreviouslyUnknown ≠ PreviouslyAbsent":("S12_stale","S2_absence"),
 "ZI-09 Conflict ≠ Invalidity":         ("S1_contradiction","S10_invalid_evidence"),
}

def pi_value(name):
    """The coarse projection: every non-satisfied state is U; S15 is the only T."""
    return "T" if name == "S15_no_known_gap" else "U"

# ============================================================ tests
def separation(states=None, drop_type=None):
    """drop_type = (facet, type) -> merge that type into a generic, then re-test."""
    st = states or STATES
    def key(n):
        t = st[n]
        if drop_type:
            f0, v0 = drop_type
            t = tuple((f, ("<merged>" if (f == f0 and v == v0) else v)) for f, v in t)
        return t
    coll = [(a,b) for a,b in itertools.combinations(st,2) if key(a)==key(b)]
    crit = [(x,y) for x,y in itertools.combinations(CRITICAL,2)
            if key(CRITICAL[x])==key(CRITICAL[y])]
    return coll, crit

def run():
    coll, crit = separation()
    # parsimony: is every declared type load-bearing?
    parsimony = {}
    for f, types in VOCAB.items():
        for t in types:
            c2, k2 = separation(drop_type=(f, t))
            parsimony[f"{f}:{t}"] = dict(
                needed=bool(c2) or bool(k2),
                new_collisions=[p for p in c2 if p not in coll],
                breaks_critical=bool(k2) and not crit)
    # ZI results
    zi = {}
    for k,(a,b) in ZI_PAIRS.items():
        zi[k] = dict(testable=True, distinct=(STATES[a]!=STATES[b]), states=(a,b))
    for k,v in UNHOSTED.items(): zi[k] = dict(testable=False, reason=v)
    # projection collapse
    proj = {n: pi_value(n) for n in STATES}
    used = {}
    for n in STATES:
        for f,_ in STATES[n]: used[f]=used.get(f,0)+1
    return dict(n_states=len(STATES), n_pairs=len(STATES)*(len(STATES)-1)//2,
                collisions=coll, all_distinct=not coll,
                critical_collisions=crit, critical_distinct=not crit,
                parsimony=parsimony,
                n_types=sum(len(v) for v in VOCAB.values()),
                n_load_bearing=sum(1 for v in parsimony.values() if v["needed"]),
                zi=zi, projection=proj,
                facets_used=used,
                facets_unused=[f for f in VOCAB if f not in used])
