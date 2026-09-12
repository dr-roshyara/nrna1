"""KR-ZERO-2026-09-02-H — BOUNDARY SEPARATION EXPERIMENT.  [EXP]

Research question:
  Can the Zero Lens represent all boundary conditions encountered in G WITHOUT
  requiring Zero itself to adopt the evaluation codomain?

Acceptance:  Boundary_i ≠ Boundary_j  whenever the theory requires the corresponding
             epistemic conditions to remain distinct.
Critical:    Contradiction ≠ Absence ≠ EvidenceInsufficiency ≠ Unobservable
             ≠ Underdetermined ≠ TheoryIncomplete — preserved WITHOUT assigning
             them different truth values.

NOT asked here:  Zero(S_i) = T/F/U/C.      No evaluator is invented.
"""
from dataclasses import dataclass, field

# --- §26 candidate boundary FACETS (not a partition; a condition may span several)
FACETS = ["G_value", "G_dimension", "G_assessment", "G_evidence", "G_interpretation",
          "G_conflict", "G_assumption", "G_temporal", "G_scope", "G_model",
          "G_observability"]

@dataclass(frozen=True)
class Boundary:
    facets: tuple            # ((facet, condition), ...) — sorted, hashable
    def d(self):  return dict(self.facets)
    def kinds(self): return tuple(sorted({f for f, _ in self.facets}))

def B(**kw):
    return Boundary(tuple(sorted((k, v) for k, v in kw.items())))

# --- the nine adversarial states from G, as specified -------------------------
STATES = {
 "S1_contradiction":  dict(desc="K = {p, ¬p}",
    b=B(G_value="both p and ¬p asserted", G_conflict="p vs ¬p, unresolved")),
 "S2_absence":        dict(desc="K = ∅",
    b=B(G_value="nothing asserted about p")),
 "S3_evidence_insufficient": dict(desc="K = {p}, evidence insufficient",
    b=B(G_value="p asserted", G_evidence="present but below the admissibility threshold",
        G_assessment="assessed, insufficient")),
 "S4_unobservable":   dict(desc="p is unobservable through available channels",
    b=B(G_observability="no channel exists for p", G_value="nothing asserted about p")),
 "S5_underdetermined":dict(desc="evidence cannot separate the rivals",
    b=B(G_value="rivals live", G_evidence="present, non-discriminating",
        G_assessment="assessed, non-unique")),
 "S6_theory_incomplete": dict(desc="the theory supplies no evaluator",
    b=B(G_model="no evaluator defined by the theory")),
 "S7_agent_remediable": dict(desc="uncertainty the agent could still resolve",
    b=B(G_value="nothing asserted about p", G_evidence="not yet gathered; a channel exists",
        G_observability="channel exists, unqueried")),
 "S8_not_applicable": dict(desc="the requirement does not apply",
    b=B(G_scope="requirement out of scope for this inquiry")),
 "S9_not_assessed":   dict(desc="evidence held, never assessed",
    b=B(G_evidence="present", G_assessment="not assessed")),
}

# --- the coarse projection π : 𝓑 → V, i.e. what Sat_c would have produced ------
def pi_value(name, b):
    """The projection the previous experiments used. Deliberately coarse."""
    d = b.d()
    if "G_conflict" in d:                     return "U"   # no value existed for it (or C)
    if "G_scope" in d:                        return "U"
    return "U"      # every one of the nine is 'not established' → U

# --- the critical six --------------------------------------------------------
CRITICAL = {"Contradiction": "S1_contradiction", "Absence": "S2_absence",
            "EvidenceInsufficiency": "S3_evidence_insufficient",
            "Unobservable": "S4_unobservable", "Underdetermined": "S5_underdetermined",
            "TheoryIncomplete": "S6_theory_incomplete"}

# --- ZI-01..ZI-10, mapped onto the state suite -------------------------------
ZI = {
 "ZI-01 Unknown ≠ Absent":            ("S5_underdetermined", "S2_absence"),
 "ZI-02 NotAssessed ≠ LowConfidence": ("S9_not_assessed", "S3_evidence_insufficient"),
 "ZI-03 NotApplicable ≠ Unknown":     ("S8_not_applicable", "S5_underdetermined"),
 "ZI-04 NoEvidence ≠ InvalidEvidence":(None, None),   # needs a state the suite lacks
 "ZI-05 Unresolved ≠ False":          ("S5_underdetermined", None),
 "ZI-06 UnknownDimension ≠ UnknownValue": (None, None),
 "ZI-07 Representation ≠ Reality":    (None, None),
 "ZI-08 PreviouslyUnknown ≠ PreviouslyAbsent": (None, None),
 "ZI-09 Conflict ≠ Invalidity":       ("S1_contradiction", None),
 "ZI-10 NoKnownGap ≠ Complete":       (None, None),
}

def run():
    import itertools
    names = list(STATES)
    # 1. pairwise separation at the BOUNDARY
    collisions = []
    for a, b in itertools.combinations(names, 2):
        if STATES[a]["b"] == STATES[b]["b"]:
            collisions.append((a, b))
    # 2. the critical six
    crit = {k: STATES[v]["b"] for k, v in CRITICAL.items()}
    crit_collisions = [(x, y) for x, y in itertools.combinations(crit, 2)
                       if crit[x] == crit[y]]
    # 3. do they collapse under the projection?
    proj = {n: pi_value(n, STATES[n]["b"]) for n in names}
    all_same_value = len(set(proj.values())) == 1
    # 4. ZI checks
    zi = {}
    for k, (a, b) in ZI.items():
        if a is None:
            zi[k] = dict(testable=False, reason="the nine-state suite contains no case for it")
        elif b is None:
            zi[k] = dict(testable=False,
                         reason="requires a truth-value comparison, which this experiment does not ask")
        else:
            zi[k] = dict(testable=True, distinct=(STATES[a]["b"] != STATES[b]["b"]),
                         states=(a, b))
    # 5. facet usage
    used = {}
    for n in names:
        for f, _ in STATES[n]["b"].facets: used[f] = used.get(f, 0) + 1
    return dict(
      n_states=len(names), n_pairs=len(names)*(len(names)-1)//2,
      boundary_collisions=collisions,
      all_nine_distinct=not collisions,
      critical_six_collisions=crit_collisions,
      critical_six_distinct=not crit_collisions,
      projection=proj, all_project_to_same_value=all_same_value,
      zi=zi,
      facets_used=used,
      facets_unused=[f for f in FACETS if f not in used])
