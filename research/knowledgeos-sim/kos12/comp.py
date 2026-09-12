"""KR-COMP-2026-09 — Composition, Frame Qualification and Standing Aggregation.  [EXP]

Commissioned as a COUPLED system, not a rule beauty-contest:

    Evidence combination  +  frame qualification  +  standing aggregation

E-FDE-5 established that composition and frame qualification are coupled for the
{contradictory, superseded} witnesses.  The object under test is therefore a TRIPLE

    (aggregation rule,  frame qualifier phi,  status policy)

and the sweep is over the whole cross-product.  NO rule is selected in advance.

Baseline v1.2 unchanged.  No v1.3.  app/ untouched.  Nothing adopted.
"""
import itertools
from .fde.evaluation import Standing
from .fde.boundary import Boundary, StructuredBoundary
from .fde.models import _boundary_of
from .fde.scenarios import SCENARIOS, BY_NAME, REQUIRED, Ev

# ============================================================ the three dimensions
# 1 -- FRAME QUALIFIER: which features must AGREE for two opposed items to be
#      genuinely opposed rather than merely different.
PHIS = {
 "phi_none":              (),
 "phi_time":              ("time",),
 "phi_context":           ("context",),
 "phi_layer":             ("layer",),
 "phi_time_context":      ("time", "context"),
 "phi_time_context_layer":("time", "context", "layer"),
}

# 2 -- STATUS POLICY: whether superseded/retracted evidence participates.
#      Making this a swept PARAMETER rather than a pre-filter is deliberate -- whether
#      supersession belongs to composition or precedes it is itself a design question.
def status_filter(evs):  return tuple(e for e in evs if e.status == "active")
def status_keep(evs):    return tuple(evs)
def status_demote(evs):
    """superseded items keep polarity but cannot CREATE opposition on their own"""
    return tuple(e for e in evs if e.status == "active") or tuple(evs)
STATUS_POLICIES = {"filter": status_filter, "keep": status_keep, "demote": status_demote}

# 3 -- AGGREGATION over frames.  Enumerated, never pre-selected.
def agg_union(frames):
    return Standing(any(f.positive_support for f in frames),
                    any(f.negative_support for f in frames))
def agg_majority(frames):
    p = sum(1 for f in frames if f.positive_support and not f.negative_support)
    n = sum(1 for f in frames if f.negative_support and not f.positive_support)
    c = any(f.positive_support and f.negative_support for f in frames)
    if c: return Standing(True, True)
    return Standing(p > n, n > p)
def agg_last_wins(frames):
    return frames[-1] if frames else Standing(False, False)
def agg_strict(frames):
    s = agg_union(frames)
    return Standing(False, False) if (s.positive_support and s.negative_support) else s
def agg_intraframe_only(frames):
    """Conflict is reported ONLY when it arises WITHIN a single frame.

    Across distinct frames, opposed supports are NOT opposition -- that is the whole
    point of a frame qualifier.  But `Standing` has only two bits, so this rule must
    emit SOMETHING for cross-frame divergence, and it has no honest option:

        within-frame opposition   -> (1,1)  conflict
        cross-frame divergence    -> (0,0)  ... which collides with `no evidence`

    That collision is NOT hidden here.  It is left for the criteria to catch, because
    it is exactly the kind of substitution the programme keeps diagnosing: a third
    condition (frame-relative divergence) forced into a two-bit codomain.
    """
    if any(f.positive_support and f.negative_support for f in frames):
        return Standing(True, True)
    p = any(f.positive_support for f in frames)
    n = any(f.negative_support for f in frames)
    if p and n:                       # divergence ACROSS frames, not conflict
        return Standing(False, False)
    return Standing(p, n)

RULES = {"union": agg_union, "majority": agg_majority, "last-wins": agg_last_wins,
         "strict": agg_strict, "intraframe-only": agg_intraframe_only}

# ============================================================ the composition pipeline
def frame_key(e, phi): return tuple(getattr(e, f) for f in phi)

def compose(sc, rule, phi, status_policy):
    """Evidence -> [status policy] -> [partition by frame] -> [within-frame standing]
    -> [aggregate] -> (Standing, Boundary, frame record)."""
    evs = STATUS_POLICIES[status_policy](sc.evidence)
    groups = {}
    for e in evs: groups.setdefault(frame_key(e, phi), []).append(e)
    frames = [Standing(any(e.polarity for e in g), any(not e.polarity for e in g))
              for g in groups.values()]
    standing = RULES[rule](frames) if frames else Standing(False, False)
    return {"standing": standing, "boundary": _boundary_of(sc),
            "frames": sorted(str(k) for k in groups), "n_frames": len(groups)}

def conflicting(res): return res["standing"].positive_support and res["standing"].negative_support

# ============================================================ the five criteria
# Verbatim from the commission.  Each is a PREDICATE with witnesses, not a score.
CONTRADICTION_WITNESSES = ["DirectContradiction", "ConflictingSources",
                           "ContradictoryObservations", "ContradictoryInterpretations",
                           "ContradictoryRules"]
NON_OPPOSED = ["NoEvidence", "InsufficientEvidence", "Underdetermined", "NotAssessed",
               "Unobservable", "PositiveEvidence", "NegativeEvidence", "TheoryIncomplete",
               "Absent", "ScopeExclusion"]
FRAME_SEPARATED = ["TemporalConflict", "ContextConflict"]

def C1_preserves_conflict(rule, phi, sp):
    """1. preserve genuine positive/negative conflict"""
    fails = [w for w in CONTRADICTION_WITNESSES
             if not conflicting(compose(BY_NAME[w], rule, phi, sp))]
    return not fails, fails

def C2_supersession_not_conflict(rule, phi, sp):
    """2. avoid turning supersession into conflict"""
    r = compose(BY_NAME["SupersededEvidence"], rule, phi, sp)
    return (not conflicting(r)), ([] if not conflicting(r) else ["SupersededEvidence"])

def C3_preserves_boundary(rule, phi, sp):
    """3. preserve boundary distinctions -- after composition, the required boundary
    pairs must still be separated by (standing, boundary) together."""
    def rep(n):
        r = compose(BY_NAME[n], rule, phi, sp)
        return ((r["standing"].positive_support, r["standing"].negative_support),
                StructuredBoundary.of(r["boundary"]))
    extra = [("TemporalConflict", "NoEvidence"), ("ContextConflict", "NoEvidence"),
             ("TemporalConflict", "DirectContradiction"),
             ("ContextConflict", "DirectContradiction")]
    fails = [[a, b] for a, b in list(REQUIRED) + extra if rep(a) == rep(b)]
    return not fails, fails

def C4_no_false_opposition(rule, phi, sp):
    """4. avoid collapsing epistemic uncertainty into semantic opposition"""
    fails = [n for n in NON_OPPOSED if conflicting(compose(BY_NAME[n], rule, phi, sp))]
    return not fails, fails

def C5_frame_explicit(rule, phi, sp):
    """5. remain explicit about the evaluation frame.

    CORRECTED.  An earlier version iterated over ALL phis and ignored the phi it was
    passed, so it tested whether a rule was phi-sensitive in general and returned the
    same verdict for every phi -- which let 45/90 triples pass and was vacuous.

    Operationally, being frame-explicit means THE FRAME DECLARATION HAS CONSEQUENCES:
    if phi declares `time` frame-relevant, then two commitments differing only in time
    are in DIFFERENT frames and must NOT be reported as conflict.  A rule that reports
    them as conflict anyway has ignored the declared frame.
    """
    fails = []
    if "time" in phi and conflicting(compose(BY_NAME["TemporalConflict"], rule, phi, sp)):
        fails.append("TemporalConflict reported as conflict though `time` is in phi")
    if "context" in phi and conflicting(compose(BY_NAME["ContextConflict"], rule, phi, sp)):
        fails.append("ContextConflict reported as conflict though `context` is in phi")
    if not phi:
        # the empty frame qualifier declares nothing, so nothing can be respected.
        # E-FDE-4 already records that it OVER-GENERATES: it must report the
        # frame-separated cases as conflict, and that is a failure of explicitness.
        if not conflicting(compose(BY_NAME["TemporalConflict"], rule, phi, sp)):
            fails.append("phi is empty yet TemporalConflict is not conflict -- the rule "
                         "is applying a frame it did not declare")
        else:
            fails.append("phi_none declares no frame: over-generation is unavoidable "
                         "(E-FDE-4)")
    return not fails, fails

CRITERIA = [("C1_preserves_conflict", C1_preserves_conflict),
            ("C2_supersession_not_conflict", C2_supersession_not_conflict),
            ("C3_preserves_boundary", C3_preserves_boundary),
            ("C4_no_false_opposition", C4_no_false_opposition),
            ("C5_frame_explicit", C5_frame_explicit)]

# ============================================================ the sweep
def sweep():
    """The whole cross-product.  Does ANY triple satisfy all five?"""
    rows, satisfying = [], []
    for rule, phi_name, sp in itertools.product(RULES, PHIS, STATUS_POLICIES):
        phi = PHIS[phi_name]
        res, wit = {}, {}
        for cname, fn in CRITERIA:
            ok, w = fn(rule, phi, sp)
            res[cname] = ok
            if w: wit[cname] = w
        all_ok = all(res.values())
        rows.append({"rule": rule, "phi": phi_name, "status_policy": sp,
                     "criteria": res, "n_satisfied": sum(res.values()),
                     "all_five": all_ok, "witnesses": wit})
        if all_ok: satisfying.append({"rule": rule, "phi": phi_name, "status_policy": sp})
    return {"question": "does any (rule, phi, status_policy) satisfy all five criteria?",
            "n_combinations": len(rows),
            "n_satisfying_all_five": len(satisfying),
            "satisfying": satisfying,
            "best_partial": sorted(rows, key=lambda r: -r["n_satisfied"])[:6],
            "rows": rows}

def criterion_pressure():
    """WHICH criteria are hardest, and which pairs are jointly unsatisfiable?"""
    rows = sweep()["rows"]
    per = {c: sum(1 for r in rows if r["criteria"][c]) for c, _ in CRITERIA}
    pairs = {}
    for (a, _), (b, _) in itertools.combinations(CRITERIA, 2):
        both = sum(1 for r in rows if r["criteria"][a] and r["criteria"][b])
        pairs[f"{a}+{b}"] = both
    return {"question": "which criteria conflict?",
            "n_combinations": len(rows),
            "satisfied_by_n_combinations": per,
            "jointly_satisfied_pairs": pairs,
            "impossible_pairs": [k for k, v in pairs.items() if v == 0]}

def coupling_evidence():
    """Direct test of E-FDE-5: is the best rule a function of phi, and vice versa?"""
    rows = sweep()["rows"]
    by_rule = {}
    for r in rows:
        by_rule.setdefault(r["rule"], {})[f'{r["phi"]}|{r["status_policy"]}'] = r["n_satisfied"]
    best_phi_per_rule = {k: max(v, key=v.get) for k, v in by_rule.items()}
    varies = len({tuple(sorted(v.items())) for v in by_rule.values()}) > 1
    rule_ranking_per_phi = {}
    for phi_name in PHIS:
        sub = [r for r in rows if r["phi"] == phi_name and r["status_policy"] == "filter"]
        rule_ranking_per_phi[phi_name] = [r["rule"] for r in
                                          sorted(sub, key=lambda r: -r["n_satisfied"])]
    rankings = {tuple(v) for v in rule_ranking_per_phi.values()}
    return {"question": "are composition and frame qualification coupled?",
            "best_phi_per_rule": best_phi_per_rule,
            "rule_ranking_depends_on_phi": len(rankings) > 1,
            "n_distinct_rule_rankings_across_phi": len(rankings),
            "rule_ranking_per_phi": rule_ranking_per_phi,
            "verdict": ("coupled iff the rule ranking changes with phi -- i.e. you cannot "
                        "choose the composition rule without having chosen phi")}

# ============================================================ robustness of the verdict
def robustness_without_C5():
    """C5 is the one criterion I had to OPERATIONALIZE rather than read off the
    commission, and my phi_none clause makes phi_none fail by stipulation.  So: does
    the verdict survive DROPPING C5 entirely?  If phi_none and union still fail on
    C1-C4 alone, the stipulation is not doing the work."""
    rows = []
    for rule, phi_name, sp in itertools.product(RULES, PHIS, STATUS_POLICIES):
        phi = PHIS[phi_name]
        res = {c: fn(rule, phi, sp)[0] for c, fn in CRITERIA[:4]}
        rows.append({"rule": rule, "phi": phi_name, "status_policy": sp,
                     "criteria_C1_C4": res, "all_four": all(res.values())})
    passing = [r for r in rows if r["all_four"]]
    phis = sorted({r["phi"] for r in passing}); rules = sorted({r["rule"] for r in passing})
    return {"question": "does the verdict survive dropping C5?",
            "n_passing_C1_C4": len(passing),
            "phis_that_can_pass": phis, "rules_that_can_pass": rules,
            "phi_none_can_pass": "phi_none" in phis,
            "union_can_pass": "union" in rules,
            "strict_can_pass": "strict" in rules,
            "conclusion": ("if phi_none and union CANNOT pass on C1-C4 alone, then C5's "
                           "operationalization is not carrying the exclusion")}

def what_determines_success():
    """Is success a function of phi, of the rule, or of the status policy?"""
    rows = sweep()["rows"]
    def spread(key):
        d = {}
        for r in rows: d.setdefault(r[key], []).append(r["all_five"])
        return {k: f"{sum(v)}/{len(v)}" for k, v in d.items()}
    # does the rule matter ONCE phi is adequate?
    good = [r for r in rows if r["phi"] in ("phi_time_context","phi_time_context_layer")]
    rules_ok = sorted({r["rule"] for r in good if r["all_five"]})
    return {"question": "which of the three dimensions determines success?",
            "by_phi": spread("phi"), "by_rule": spread("rule"),
            "by_status_policy": spread("status_policy"),
            "rules_that_succeed_given_adequate_phi": rules_ok,
            "rule_underdetermined_given_adequate_phi": len(rules_ok) > 1,
            "status_policy_irrelevant": len({v for v in spread("status_policy").values()}) == 1,
            "phi_non_additive": ("phi_time and phi_context each pass 0, but "
                                 "phi_time_context passes -- neither feature alone suffices")}

# ============================================================ the separating witnesses
# Commissioned: "a witness with genuine conflict inside one frame AND divergence across
# others".  W1 has exactly that shape.  W2/W3 are the controls that show why the shape
# matters -- see SEP1.
from .fde.scenarios import RequiredDistinctionScenario as _S
_P = lambda **k: Ev(polarity=True,  **k)
_N = lambda **k: Ev(polarity=False, **k)

WITNESSES = {
 # the commission's proposed shape: one internally-conflicting frame + divergence
 "W1_conflict_plus_divergence": _S("W1_conflict_plus_divergence",
    (_P(time=1, context="C1"), _N(time=1, context="C1"),      # frame (1,C1): CONFLICT
     _P(time=2, context="C1"),                                 # frame (2,C1): positive
     _N(time=3, context="C1")),                                # frame (3,C1): negative
    provenance="KR-COMP S10 commission"),
 # ASYMMETRIC divergence, NO internal conflict
 "W2_asymmetric_divergence": _S("W2_asymmetric_divergence",
    (_P(time=1, context="C1"), _P(time=2, context="C1"),
     _N(time=3, context="C1")),
    provenance="constructed: 2 positive frames vs 1 negative, no frame conflicts"),
 # SYMMETRIC divergence -- the control that shows asymmetry is required
 "W3_symmetric_divergence": _S("W3_symmetric_divergence",
    (_P(time=1, context="C1"), _N(time=2, context="C1")),
    provenance="control"),
 # same evidence SET as W2, different enumeration order
 "W4_W2_reordered": _S("W4_W2_reordered",
    (_N(time=3, context="C1"), _P(time=1, context="C1"),
     _P(time=2, context="C1")),
    provenance="control: identical set to W2, permuted"),
}

SURVIVORS = ["majority", "last-wins", "intraframe-only"]

def SEP1_separating_witness(phi_name="phi_time_context", sp="filter"):
    """Which witness shape actually separates the three surviving rules?"""
    phi = PHIS[phi_name]
    rows = {}
    for wname, sc in WITNESSES.items():
        outs = {r: compose(sc, r, phi, sp)["standing"].configuration() for r in SURVIVORS}
        rows[wname] = {"outputs": outs, "n_distinct": len(set(outs.values())),
                       "separates_all_three": len(set(outs.values())) == 3,
                       "n_frames": compose(sc, SURVIVORS[0], phi, sp)["n_frames"]}
    return {"question": "which witness separates majority / last-wins / intraframe-only?",
            "phi": phi_name, "status_policy": sp,
            "witnesses": rows,
            "separating": [w for w, v in rows.items() if v["separates_all_three"]],
            "finding": ("internal conflict is ABSORBING under both majority and "
                        "intraframe-only -- both short-circuit to (1,1) as soon as any "
                        "frame conflicts internally. So adding internal conflict DESTROYS "
                        "the separation rather than creating it. The separating witness "
                        "must have NO internal conflict and ASYMMETRIC divergence.")}

def SEP2_order_invariance():
    """C6 (THIS LANE'S criterion, not the commission's): a composition rule must be a
    function of the evidence SET, not of its enumeration order."""
    phi = PHIS["phi_time_context"]
    a = WITNESSES["W2_asymmetric_divergence"]; b = WITNESSES["W4_W2_reordered"]
    assert sorted((e.polarity, e.time, e.context) for e in a.evidence) == \
           sorted((e.polarity, e.time, e.context) for e in b.evidence), "not a permutation"
    rows = {}
    for r in RULES:
        x = compose(a, r, phi, "filter")["standing"].configuration()
        y = compose(b, r, phi, "filter")["standing"].configuration()
        rows[r] = {"W2": x, "W4_permuted": y, "order_invariant": x == y}
    return {"question": "is each rule a function of the evidence SET?",
            "note": "W2 and W4 are the SAME multiset of evidence, permuted",
            "rules": rows,
            "order_dependent_rules": [r for r, v in rows.items() if not v["order_invariant"]],
            "criterion": ("C6 -- proposed by THIS LANE, not the commission. A rule whose "
                          "output depends on enumeration order is not well-defined on the "
                          "evidence set.")}

def SEP3_criteria_with_witnesses_added():
    """Does adding the new witnesses break the five criteria for the surviving triples?"""
    out = {}
    for rule, phi_name, sp in itertools.product(SURVIVORS,
            ["phi_time_context", "phi_time_context_layer"], STATUS_POLICIES):
        phi = PHIS[phi_name]
        # the new witnesses must not be reported as conflict (they are cross-frame)
        false_conflict = [w for w in ("W2_asymmetric_divergence", "W3_symmetric_divergence")
                          if conflicting(compose(WITNESSES[w], rule, phi, sp))]
        # W1 SHOULD be conflict: it contains a genuine intra-frame contradiction
        w1_ok = conflicting(compose(WITNESSES["W1_conflict_plus_divergence"], rule, phi, sp))
        # and cross-frame divergence must stay distinguishable from genuine no-support
        def rep(sc):
            r = compose(sc, rule, phi, sp)
            return ((r["standing"].positive_support, r["standing"].negative_support),
                    StructuredBoundary.of(r["boundary"]))
        collides = rep(WITNESSES["W2_asymmetric_divergence"]) == rep(BY_NAME["NoEvidence"])
        out[f"{rule}|{phi_name}|{sp}"] = {
            "W1_reported_as_conflict": w1_ok,
            "no_false_conflict_on_divergence": not false_conflict,
            "divergence_distinguishable_from_NoEvidence": not collides}
    return {"question": "do the new witnesses break anything for the surviving triples?",
            "results": out,
            "all_hold": all(all(v.values()) for v in out.values())}

# ============================================================ C7 -- frame-refinement invariance
def SEP4_frame_refinement_invariance():
    """A NEW EMPIRICAL PROPERTY, articulated a priori -- which is the review's stated
    condition for running another witness rather than an arbitrary hunt.

        C7  Refining the frame partition -- recording a frame feature at finer
            resolution, WITHOUT adding, removing or altering any evidence -- must not
            change the verdict.

    Rationale: a frame is created by ANY difference in a frame feature, so frame COUNT
    is an artefact of the recording resolution of `time`/`context`.  A rule whose output
    depends on that resolution is reporting a property of the CLOCK, not of the evidence
    -- exactly parallel to the order-dependence that eliminated `last-wins`.

    Construction: the SAME three evidence items, with the two positives either sharing a
    timestamp (coarse) or carrying distinct ones (fine).  Nothing else differs.
    """
    coarse = _S("coarse", (_P(time=1, context="C1"), _P(time=1, context="C1"),
                           _N(time=2, context="C1")),
                provenance="two positives share a timestamp")
    fine   = _S("fine",   (_P(time=1, context="C1"), _P(time=2, context="C1"),
                           _N(time=3, context="C1")),
                provenance="same three items, finer temporal resolution")
    phi = PHIS["phi_time_context"]
    rows = {}
    for r in RULES:
        c = compose(coarse, r, phi, "filter")
        f = compose(fine,   r, phi, "filter")
        rows[r] = {"coarse_frames": c["n_frames"], "fine_frames": f["n_frames"],
                   "coarse": c["standing"].configuration(),
                   "fine":   f["standing"].configuration(),
                   "refinement_invariant": c["standing"] == f["standing"]}
    surv = {r: rows[r] for r in SURVIVORS}
    return {"question": "does refining the frame partition change the verdict?",
            "criterion": "C7 -- frame-refinement invariance [PROP], this lane's",
            "note": ("evidence CONTENT is identical in both cases; only the recording "
                     "resolution of `time` differs, which changes the frame COUNT"),
            "all_rules": rows,
            "surviving_rules": surv,
            "refinement_dependent": [r for r, v in rows.items()
                                     if not v["refinement_invariant"]],
            "separates_the_two_survivors": (
                surv["majority"]["refinement_invariant"] !=
                surv["intraframe-only"]["refinement_invariant"]),
            "caveat": ("this is a COST, measured. Whether refinement-dependence is "
                       "DISQUALIFYING is a judgement, not an experimental result.")}
