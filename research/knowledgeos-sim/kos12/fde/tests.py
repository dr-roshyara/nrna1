"""The five tests plus the E12/E13 guards and the composition probe."""
import itertools
from .evaluation import Standing, fde_conflict_detector
from .boundary import (Boundary, FlatReason, LocusModalityReason, StructuredBoundary,
                       REASON_REPRESENTATIONS)
from .models import (ClassicalModel, K3Model, FDEModel, StructuredModel,
                     _boundary_of, _standing, NOT_REPRESENTABLE)
from .scenarios import SCENARIOS, BY_NAME, REQUIRED, pos, neg, active
from .harness import (distinction_matrix, full_collapse_report,
                      equivalent_under_required, outcome, PRESERVED)

MODELS = lambda: [ClassicalModel(), K3Model(), FDEModel(),
                  StructuredModel(StructuredBoundary)]

# ---------------------------------------------------------------- 1
def DistinctionPreservationTest():
    return distinction_matrix(MODELS())

# ---------------------------------------------------------------- 2
def CollapseTest():
    return {"question": "where does each representation merge scenarios?",
            "note": "over ALL scenario pairs, not only required ones",
            "models": full_collapse_report(MODELS())}

# ---------------------------------------------------------------- 3
def ContrTest():
    """FDEConflict(p) =?= Contr(p).

    Priest's FDE represents a glut.  That does NOT establish that KnowledgeOS epistemic
    contradiction is the same condition.  Contr is tested WITH a frame qualifier phi,
    because KR-CONTR-EVAL S15 showed Pos ^ Neg OVER-GENERATES: it classifies supersession
    and temporal separation as contradiction.
    """
    def contr(sc, phi):
        p_, n_ = pos(sc), neg(sc)
        if not (p_ and n_): return False
        for a in p_:
            for b in n_:
                if all(getattr(a, f) == getattr(b, f) for f in phi):
                    return True
        return False
    PHIS = {"phi_empty  (Pos^Neg only)": (),
            "phi_time":                  ("time",),
            "phi_context":               ("context",),
            "phi_time_context":          ("time", "context"),
            "phi_time_context_layer":    ("time", "context", "layer")}
    rows, agreement = [], {}
    for label, phi in PHIS.items():
        diffs = []
        for sc in SCENARIOS:
            f = fde_conflict_detector(_standing(sc))
            c = contr(sc, phi)
            if f != c: diffs.append({"scenario": sc.name, "FDEConflict": f, "Contr": c})
        agreement[label] = {"equivalent": not diffs, "divergences": diffs}
    return {"question": "is FDEConflict the same predicate as Contr?",
            "by_frame_qualifier": agreement,
            "equivalent_under_any_tested_phi": any(
                v["equivalent"] for v in agreement.values()),
            "note": ("FDEConflict is a DETECTOR over Standing. It has no access to the "
                     "frame, so it cannot implement any phi other than the empty one.")}

# ---------------------------------------------------------------- 4
def ZeroTest():
    """Zero is a CONSUMER, downstream of Standing and Boundary.  FDE != Boundary != Zero."""
    fde, structured = FDEModel(), StructuredModel(StructuredBoundary)
    # can Zero be computed from Standing ALONE?  group scenarios by Standing and see
    # whether each group is homogeneous in its boundary.
    groups = {}
    for sc in SCENARIOS:
        groups.setdefault(str(fde.evaluate(sc)), []).append(
            (sc.name, str(_boundary_of(sc))))
    ambiguous = {k: v for k, v in groups.items()
                 if len({b for _, b in v}) > 1}
    unsupported = [v for k, v in groups.items() if k == "(False, False)"]
    return {"question": "can Zero be determined from Standing alone?",
            "standing_classes": {k: [n for n, _ in v] for k, v in groups.items()},
            "standing_classes_with_multiple_boundaries": {
                k: v for k, v in ambiguous.items()},
            "zero_determinable_from_standing_alone": not ambiguous,
            "the_00_class": unsupported,
            "conclusion": ("(0,0) cannot say WHY there is no support. Standing therefore "
                           "underdetermines Zero, and Zero must consume Boundary as well. "
                           "FDE-like Standing != Boundary != Zero.")}

# ---------------------------------------------------------------- 5
def InvariantTest():
    """I1-I5 and the PROPOSED I11, over the four representations."""
    pairs = {"I1_Contr_ne_NoEvidence":  ("DirectContradiction","NoEvidence"),
             "I2_Contr_ne_Absent":      ("DirectContradiction","Absent"),
             "I3_Contr_ne_NotAssessed": ("DirectContradiction","NotAssessed"),
             "I4_Contr_ne_Insufficient":("DirectContradiction","InsufficientEvidence"),
             "I5_Contr_ne_Unobservable":("DirectContradiction","Unobservable"),
             "I11_Contr_ne_Satisfied_PROPOSED":
                                        ("DirectContradiction","PositiveEvidence")}
    out = {}
    for iid, (a, b) in pairs.items():
        out[iid] = {m.name: outcome(m, a, b)[0] for m in MODELS()}
    return {"question": "which invariants hold under which representation?",
            "results": out,
            "note": ("I11 is PROPOSED and unratified. 'not-representable' is reported "
                     "distinctly from 'preserved' -- a model that cannot express a "
                     "situation has not satisfied an invariant about it.")}

# ---------------------------------------------------------------- E12 guard
def E12_guard():
    """Is the boundary/reason component a genuine categorical field, or a disguised
    complete state identifier?  Re-run inside THIS harness rather than cited."""
    names = [s.name for s in SCENARIOS]
    res = {}
    for rep in REASON_REPRESENTATIONS:
        vals = {n: rep.of(_boundary_of(BY_NAME[n])) for n in names}
        distinct = {str(v) for v in vals.values()}
        collapsed = [[a, b] for a, b in itertools.combinations(names, 2)
                     if vals[a] == vals[b]]
        # adequacy of reason ALONE
        class _R:
            name = f"reason-only[{rep.name}]"
            def evaluate(self, sc): return rep.of(_boundary_of(sc))
            def representable(self, sc): return True
        alone = distinction_matrix([_R()])["models"][f"reason-only[{rep.name}]"]
        res[rep.name] = {
          "n_scenarios": len(names), "n_distinct_values": len(distinct),
          "is_identity_encoding": len(distinct) == len(names),
          "n_pairs_collapsed": len(collapsed),
          "adequate_alone": alone["adequate"],
          "preserved_alone": f"{alone['preserved']}/{len(REQUIRED)}"}
    return {"question": "genuine categorical field, or disguised state identifier?",
            "by_representation": res,
            "verdict_rule": ("genuine iff NOT an identity encoding AND it collapses "
                             "pairs AND it is not adequate alone")}

# ---------------------------------------------------------------- E13 guard
def E13_representation_equivalence():
    """R1 ~=_{R_req} R2 across the three reason representations -- the right question."""
    mods = [StructuredModel(r) for r in REASON_REPRESENTATIONS]
    pairs = [equivalent_under_required(a, b)
             for a, b in itertools.combinations(mods, 2)]
    mat = distinction_matrix(mods)
    return {"question": "are the reason representations equivalent under R_req?",
            "pairwise": pairs,
            "adequacy": {k: {"adequate": v["adequate"], "preserved": v["preserved"],
                             "collapsed": v["collapsed"]}
                         for k, v in mat["models"].items()},
            "note": ("equivalence UNDER R_req is not identity. A representation can be "
                     "adequate while losing distinctions R_req does not demand.")}

# ---------------------------------------------------------------- composition probe
def CompositionProbe():
    """Combine two evidence items into a Standing WITHOUT selecting an algebra first.

    Candidate rules are enumerated and their consequences reported. No rule is adopted;
    this only prepares KR-COMP.
    """
    E = [("single_positive",      [True]),
         ("single_negative",      [False]),
         ("two_agreeing",         [True, True]),
         ("contradictory",        [True, False]),
         ("contradictory_weighted",[True, False]),
         ("superseded",           [True, False])]
    def rule_union(pols, **kw):       return Standing(any(p for p in pols),
                                                      any(not p for p in pols))
    def rule_majority(pols, **kw):
        p, n = sum(pols), sum(1 for x in pols if not x)
        return Standing(p > n, n > p)
    def rule_last_wins(pols, **kw):   return Standing(pols[-1], not pols[-1])
    def rule_strict(pols, **kw):
        if any(pols) and any(not p for p in pols): return Standing(False, False)
        return Standing(any(pols), any(not p for p in pols))
    RULES = {"union (FDE-like)": rule_union, "majority": rule_majority,
             "last-wins (recency)": rule_last_wins, "strict (conflict->none)": rule_strict}
    rows = []
    for label, pols in E:
        rows.append({"case": label,
                     **{r: RULES[r](pols).configuration() for r in RULES}})
    return {"question": "which composition rules produce a conflicting Standing?",
            "rules_enumerated": list(RULES),
            "rows": rows,
            "conflict_producing_rules": [r for r in RULES
                if RULES[r]([True, False]).configuration() == "conflicting"],
            "warning": ("NO rule is adopted. Enumerated first, witnesses second -- the "
                        "ordering KR-CONTR-EVAL S21.1 requires of KR-COMP.")}
