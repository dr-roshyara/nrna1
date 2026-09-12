"""KR-CONTR-2026-09 — SEPARATING THE THREE CONTRADICTION MODELS.  [EXP]

Baseline: KnowledgeOS Theory v1.2, unchanged.  Factivity DECIDED (R1): K_t -> A_t.
No contradiction model is adopted by this module.  It only asks what separates them.

Commissioned by Experiment G:
  "the deterministic suite discriminates the composition rules but NOT the
   contradiction models.  That is the experiment to design next -- a case that
   separates the three contradiction models.  Do not guess between them."

D-0 (Experiment G) established that all four Zero readings test only for F and U,
so any value outside {T,F,U} is invisible and every reading CLOSES on it.

PROVENANCE DISCIPLINE (inherited from zeroI.py): every case and every reason carries
the corpus item that forces it.  An invented case would make separation trivially
achievable and therefore worthless.
"""
import itertools

# ============================================================ values and reasons
T, F, U, C = "T", "F", "U", "C"      # C = the candidate fourth value
UNDEF = "UNDEFINED"                  # three-valued model's value on contradictory input
READABLE = {T, F, U}                 # what the four Zero readings can actually see

# Buckets are taken VERBATIM from kos12/expG.py BUCKET -- the authoritative table used
# by every prior Zero experiment.  They are NOT assigned here; C7 sweeps them anyway.
from .expG import BUCKET
REASONS = {
 "CONTRADICTORY_INPUT": (BUCKET["CONTRADICTORY_INPUT"],
    "corpus S1-contradiction: the AGENT holds {p, not-p}; Experiment G PB-2"),
 "CONTRADICTION":       (BUCKET["CONTRADICTION"],
    "four-valued model's reason; expG.BUCKET"),
 "NO_EVALUATOR":        (BUCKET["NO_EVALUATOR"],
    "corpus: Contr is undefined -> Sat_consistency has no evaluator; Experiment F"),
 "NONE":                (None, "no gap"),
}

# ============================================================ the three models
# Each maps a case to (value, reason) for the `content` requirement.
# Definitions are taken verbatim from Experiment G's D-0 table -- NOT invented here.
#
#   model        | content on contradictory input
#   -------------|--------------------------------
#   three_valued | UNDEFINED / CONTRADICTORY_INPUT   -> (U, CONTRADICTORY_INPUT)
#   four_valued  | C / CONTRADICTION                 -> (C, CONTRADICTORY_INPUT)
#   delegated    | U / NO_EVALUATOR (routes through Sat_consistency, itself undefined)

def m_three_valued(case):
    # evalc.eval_content_3valued: value="UNDEFINED" -- OUTSIDE {T,F,U}, not U.
    if case["contradiction"]:      return (UNDEF, "CONTRADICTORY_INPUT")
    if not case["has_evaluator"]:  return (U, "NO_EVALUATOR")
    return (T, "NONE") if case["supported"] else (F, "NONE")

def m_four_valued(case):
    # evalc.eval_content_4valued: value="C", reason="CONTRADICTION"
    if case["contradiction"]:      return (C, "CONTRADICTION")
    if not case["has_evaluator"]:  return (U, "NO_EVALUATOR")
    return (T, "NONE") if case["supported"] else (F, "NONE")

def m_delegated(case):
    # routes contradictory input through Sat_consistency, which is undefined
    # because Contr is undefined -> the reason becomes NO_EVALUATOR, and the fact
    # that the input was contradictory is LOST at the routing step.
    if case["contradiction"]:      return (U, "NO_EVALUATOR")
    if not case["has_evaluator"]:  return (U, "NO_EVALUATOR")
    return (T, "NONE") if case["supported"] else (F, "NONE")

MODELS = {"three_valued": m_three_valued,
          "four_valued":  m_four_valued,
          "delegated":    m_delegated}

# ============================================================ the case space
# Exhaustive over the parameterization, so "no separating case exists" is a
# statement about the whole space, not about a hand-picked suite.
PARAMS = ["contradiction", "has_evaluator", "supported"]

def case_space():
    out = []
    for bits in itertools.product([False, True], repeat=len(PARAMS)):
        c = dict(zip(PARAMS, bits))
        c["id"] = "".join(p[0].upper() if b else p[0] for p, b in zip(PARAMS, bits))
        out.append(c)
    return out

# The two corpus-attested cases that carry the separation (provenance, not invention):
X1 = {"id": "X1", "contradiction": True,  "has_evaluator": True,  "supported": True,
      "provenance": "corpus S1-contradiction: agent holds {p, not-p}"}
X2 = {"id": "X2", "contradiction": False, "has_evaluator": False, "supported": True,
      "provenance": "corpus: Sat_consistency has no evaluator (Contr undefined)"}

# ============================================================ Zero readings
# The four readings as recorded in Experiment G D-0, verbatim in behaviour.
def zero_weak(rs):      return not any(v == F for v, _ in rs)
def zero_reasoned(rs):  return zero_weak(rs) and not any(
                            v == U and REASONS[r][0] == "agent" for v, r in rs)
def zero_strict(rs):    return all(v == T for v, _ in rs)
# NOTE: weak/reasoned/kleene test only for F and U, so a value outside {T,F,U} is
# invisible to them and they CLOSE on it.  That is D-0, reproduced by construction.
def zero_kleene(rs):
    if any(v == F for v, _ in rs): return F
    if any(v == U for v, _ in rs): return U
    return T
BASE_READINGS = {"strict": zero_strict, "weak": zero_weak,
                 "reasoned": zero_reasoned, "kleene": zero_kleene}

# Extensions of a reading to a codomain containing C.  We do NOT choose one --
# all three natural treatments are swept, because choosing would be the rigging.
# The treatments apply to ANY value outside {T,F,U} -- both UNDEFINED and C.  This
# matters: the three-valued model's codomain is ALREADY extended, so the deficiency
# is in the readings, not only in the codomain.
C_TREATMENTS = ["as_blocking",   # unreadable value behaves like F
                "as_gap",        # unreadable value behaves like U
                "as_closing"]    # unreadable value ignored -- the D-0 defect

def apply_treatment(rs, treatment):
    out = []
    for v, r in rs:
        if v not in READABLE:
            v = {"as_blocking": F, "as_gap": U, "as_closing": T}[treatment]
        out.append((v, r))
    return out

# ============================================================ observation levels
# A model is compared to another by its KERNEL: the equivalence relation it induces
# on the case space.  Two models are separated by a PAIR (a,b) iff they disagree
# about whether a and b are distinguishable.  This is the right object -- the models
# differ as functions, and functions are separated on their induced partitions.
def obs_value(res):    return res[0]                    # value codomain only
def obs_boundary(res): return res                       # the boundary object B: (value, reason)
LEVELS = {"value": obs_value, "boundary": obs_boundary}

def induced_relation(model, level, cases):
    """The INDUCED EQUIVALENCE RELATION of the model at this observation level:
    the set of unordered case-pairs it identifies (conflates).

    TERMINOLOGY (review correction, 2026-09-02): this is NOT a "kernel".  For an
    arbitrary projection pi: X -> Y there need be no kernel in the algebraic sense.
    The correct object is  x1 ~_pi x2  iff  pi(x1) = pi(x2),  with Y = X/~_pi under
    appropriate conditions.  The governing rule is therefore
        "every projection declares what DISTINCTIONS IT IDENTIFIES"
    and not "every projection declares its kernel".
    """
    f, o = MODELS[model], LEVELS[level]
    conf = set()
    for a, b in itertools.combinations(cases, 2):
        if o(f(a)) == o(f(b)):
            conf.add((a["id"], b["id"]))
    return conf

# ============================================================ C1 / C2 -- readings
def C1_base_readings():
    """Do the four ORIGINAL readings separate the three models?  (D-0 control.)"""
    cases, rows = case_space(), []
    sep = {p: False for p in itertools.combinations(MODELS, 2)}
    for c in cases:
        rs = {m: [MODELS[m](c)] for m in MODELS}
        # base readings are blind to C -- it is not F and not U, so weak/reasoned close
        vals = {m: {n: r(rs[m]) for n, r in BASE_READINGS.items()} for m in MODELS}
        rows.append({"case": c["id"], **{m: vals[m] for m in MODELS}})
        for p in sep:
            if vals[p[0]] != vals[p[1]]: sep[p] = True
    return {"question": "do the four ORIGINAL Zero readings separate the models?",
            "rows": rows,
            "separated": {f"{a}|{b}": v for (a, b), v in sep.items()},
            "any_separation": any(sep.values())}

def C2_extended_readings():
    """Sweep all three treatments of C.  Under which does M4 separate?"""
    cases, out = case_space(), {}
    for t in C_TREATMENTS:
        sep = {p: False for p in itertools.combinations(MODELS, 2)}
        witness = {}
        for c in cases:
            rs = {m: apply_treatment([MODELS[m](c)], t) for m in MODELS}
            vals = {m: {n: r(rs[m]) for n, r in BASE_READINGS.items()} for m in MODELS}
            for p in sep:
                if vals[p[0]] != vals[p[1]] and not sep[p]:
                    sep[p] = True; witness[f"{p[0]}|{p[1]}"] = c["id"]
        out[t] = {"separated": {f"{a}|{b}": v for (a, b), v in sep.items()},
                  "witness": witness}
    return {"question": "does extending the readings to see C separate the models?",
            "treatments": out,
            "note": "as_closing reproduces the D-0 defect by construction"}

# ============================================================ C3 -- the theorem
def C3_value_multiset_identity():
    """M3 vs MD: are the value-multisets identical on EVERY case in the space?

    If yes, then EVERY reading that is a function of values alone gives identical
    results on both -- a theorem by exhaustion over the parameterization, not a
    sample.  No further reading needs to be tried.
    """
    cases = case_space(); diffs = []
    for c in cases:
        a, b = m_three_valued(c), m_delegated(c)
        if a[0] != b[0]:
            diffs.append({"case": c["id"], "three_valued": a, "delegated": b})
    reason_diffs = [{"case": c["id"], "three_valued": m_three_valued(c),
                     "delegated": m_delegated(c)}
                    for c in cases if m_three_valued(c)[1] != m_delegated(c)[1]]
    return {"question": "do M3 and MD ever differ in VALUE?",
            "cases_examined": len(cases),
            "value_differences": diffs,
            "value_differences_n": len(diffs),
            "reason_differences": reason_diffs,
            "reason_differences_n": len(reason_diffs),
            "theorem": ("if value_differences_n == 0 then NO function of values alone "
                        "separates M3 from MD -- exhaustive over the parameterization")}

# ============================================================ C4 -- the pair test
def C4_pair_separation():
    """The separating observable: do the models AGREE about which cases are alike?"""
    cases = case_space()
    res = {}
    for level in LEVELS:
        ks = {m: induced_relation(m, level, cases) for m in MODELS}
        pairs = {}
        for a, b in itertools.combinations(MODELS, 2):
            only_a, only_b = ks[a] - ks[b], ks[b] - ks[a]
            pairs[f"{a}|{b}"] = {
                "separated": bool(only_a or only_b),
                "conflated_only_by_" + a: sorted(only_a),
                "conflated_only_by_" + b: sorted(only_b)}
        res[level] = pairs
    # the named corpus pair
    named = {m: {"X1": MODELS[m](X1), "X2": MODELS[m](X2),
                 "distinguishes_X1_X2": MODELS[m](X1) != MODELS[m](X2),
                 "distinguishes_at_value_level": MODELS[m](X1)[0] != MODELS[m](X2)[0]}
             for m in MODELS}
    return {"question": "which models agree about which cases are alike?",
            "by_level": res,
            "named_corpus_pair": {"X1": X1["provenance"], "X2": X2["provenance"],
                                  "models": named}}

# ============================================================ C5 -- fourth value
def C5_fourth_value_necessity():
    """Does M4 make any distinction that M3 + the boundary object does not?"""
    cases = case_space()
    k_m4_value    = induced_relation("four_valued",  "value",    cases)
    k_m3_boundary = induced_relation("three_valued", "boundary", cases)
    k_m3_value    = induced_relation("three_valued", "value",    cases)
    return {"question": "is a fourth VALUE necessary, given the boundary object?",
            "m4_conflates_at_value":     sorted(k_m4_value),
            "m3_conflates_at_boundary":  sorted(k_m3_boundary),
            "m3_conflates_at_value":     sorted(k_m3_value),
            "m4_distinguishes_more_than_m3_boundary": sorted(k_m3_boundary - k_m4_value),
            "m3_boundary_distinguishes_more_than_m4": sorted(k_m4_value - k_m3_boundary),
            "equivalent": k_m4_value == k_m3_boundary}

# ============================================================ C6 -- D-0 control
def C6_d0_control():
    """Re-confirm: extend the codomain WITHOUT extending the readings -> closure
    silently swallows the new value."""
    rs = [MODELS["four_valued"](X1)]                     # (C, CONTRADICTORY_INPUT)
    return {"question": "does an unextended reading close on a contradictory state?",
            "state": "X1 -- agent holds {p, not-p}",
            "four_valued_value": rs[0],
            "zero_weak_closes":     zero_weak(rs),
            "zero_reasoned_closes": zero_reasoned(rs),
            "zero_kleene":          zero_kleene(rs),
            "d0_reproduced": zero_weak(rs) and zero_reasoned(rs)}

# ============================================================ C7 -- anti-rigging
def C7_bucket_sweep():
    """Does the C4 result depend on how reasons are assigned to buckets?

    The bucket assignment is the one place I could rig this.  So sweep ALL of them
    and report the result under each, rather than choosing one.
    """
    cases, out = case_space(), {}
    orig = {k: v for k, v in REASONS.items()}
    for ci, ne in itertools.product(["agent", "theory"], repeat=2):
        REASONS["CONTRADICTORY_INPUT"] = (ci, orig["CONTRADICTORY_INPUT"][1])
        REASONS["NO_EVALUATOR"]        = (ne, orig["NO_EVALUATOR"][1])
        k3 = induced_relation("three_valued", "boundary", cases)
        kd = induced_relation("delegated",    "boundary", cases)
        sep_reading = False
        for c in cases:
            a = [m_three_valued(c)]; b = [m_delegated(c)]
            if zero_reasoned(a) != zero_reasoned(b): sep_reading = True
        out[f"contr={ci},noeval={ne}"] = {
            "boundary_relations_differ": k3 != kd,
            "zero_reasoned_separates": sep_reading}
    REASONS.update(orig)
    return {"question": "is the separation an artifact of the bucket assignment?",
            "sweep": out,
            "boundary_separation_holds_in_all": all(
                v["boundary_relations_differ"] for v in out.values())}

# ============================================================ C8 -- the masking test
def C8_masking():
    """Why did D-0 report that all three models AGREE, when C1 finds MD separable?

    D-0 evaluated 7 cases across 8 REQUIREMENT CLASSES.  The other classes
    independently contribute U (D-0's 'U buckets: theory x5/x6' column).  A single
    U anywhere in the state fixes zero_weak/kleene regardless of what `content`
    returns -- so the content difference is MASKED.

    This test isolates the mask: same content values, varying only how many OTHER
    requirements are U, and from which bucket.
    """
    rows = []
    for n_other_U, bucket in [(0, None), (1, "theory"), (1, "agent"),
                              (5, "theory"), (6, "theory")]:
        others = []
        if n_other_U:
            r = "NO_EVALUATOR" if bucket == "theory" else "UNOBSERVED"
            if r not in REASONS: REASONS[r] = (BUCKET.get(r, bucket), "other requirement")
            others = [(U, r)] * n_other_U
        vals = {}
        for m in MODELS:
            rs = [MODELS[m](X1)] + others
            vals[m] = {"weak": zero_weak(rs), "reasoned": zero_reasoned(rs),
                       "kleene": zero_kleene(rs), "strict": zero_strict(rs)}
        distinct = len({tuple(sorted(v.items())) for v in vals.values()})
        rows.append({"other_U": n_other_U, "other_bucket": bucket,
                     "readings": vals, "distinct_reading_profiles": distinct,
                     "models_separable": distinct > 1})
    return {"question": "does masking by other requirements explain D-0's agreement?",
            "state": "X1 (agent holds {p, not-p}) plus N other requirements at U",
            "rows": rows,
            "masked_when_other_U_present": all(
                not r["models_separable"] for r in rows if r["other_U"] > 0),
            "separable_in_isolation": rows[0]["models_separable"],
            "interpretation": ("if separable at other_U=0 and masked at other_U>0, then "
                               "D-0's agreement is a MASKING artifact of the 8-class "
                               "setting, not a property of the models")}

# ============================================================ C9 -- isomorphism
def C9_m3_m4_isomorphism():
    """Are M3 and M4 the SAME model under a relabeling?

    C4 found them unseparated on 8 cases.  That is weak evidence.  If instead the
    relabeling  UNDEFINED <-> C ,  CONTRADICTORY_INPUT <-> CONTRADICTION  is a
    bijection that commutes with both models on EVERY case, then they are isomorphic
    as value assignments -- a theorem, not a sample.
    """
    RELABEL_V = {UNDEF: C}
    RELABEL_R = {"CONTRADICTORY_INPUT": "CONTRADICTION"}
    def phi(res):
        v, r = res
        return (RELABEL_V.get(v, v), RELABEL_R.get(r, r))
    cases = case_space()
    fails = [{"case": c["id"], "phi(m3)": phi(m_three_valued(c)), "m4": m_four_valued(c)}
             for c in cases if phi(m_three_valued(c)) != m_four_valued(c)]
    injective = len(set(RELABEL_V.values())) == len(RELABEL_V) and \
                not (set(RELABEL_V.values()) & (set(RELABEL_V) - set(RELABEL_V.values())))
    return {"question": "are M3 and M4 the same model under relabeling?",
            "relabeling": {"values": RELABEL_V, "reasons": RELABEL_R},
            "cases_checked": len(cases),
            "commutation_failures": fails,
            "isomorphic_as_value_assignments": not fails,
            "relabeling_is_injective": injective,
            "caveat": ("isomorphic as ASSIGNMENTS does not mean equivalent for all "
                       "purposes -- a genuine fourth value earns its keep in the "
                       "COMPOSITION algebra, not in the assignment.  See C10.")}

# ============================================================ C10 -- composition
def C10_composition_algebra():
    """Can M3 and M4 be separated by a COMPOSITION rule?

    VACUITY GUARD.  C9 shows M3 and M4 differ ONLY in the NAME of the unreadable
    token (UNDEFINED vs C).  Therefore any rule that is TOKEN-BLIND -- that treats
    an unreadable value by its unreadability rather than its identity -- CANNOT
    separate them, by construction.  Testing only token-blind rules would produce a
    guaranteed pass and prove nothing.

    So the sweep includes a TOKEN-SENSITIVE rule as a WITNESS that the test is
    capable of separating.  The real question is then not empirical but semantic:
    does the corpus supply a principled reason to treat C differently from
    'no value at all'?
    """
    def agg(vals, rule):
        un = [v for v in vals if v not in READABLE]
        rd = [v for v in vals if v in READABLE]
        if rule == "ignore":            vals = rd or [T]
        elif rule == "strict":
            if un: return un[0]
        elif rule == "absorbing":
            if un: return F
        elif rule == "belnap_blind":
            if un: return F if F in rd else un[0]
        elif rule == "belnap_designated":
            # TOKEN-SENSITIVE witness: C is a designated lattice element that
            # ABSORBS (C & T = C, C & F = C); UNDEFINED is an error that propagates
            # as a gap.  This is the only shape under which a fourth value does
            # algebraic work that 'no value' does not.
            if C in un:     return C
            if UNDEF in un: return U
        if F in vals: return F
        if U in vals: return U
        return T
    BLIND     = ["ignore", "strict", "absorbing", "belnap_blind"]
    SENSITIVE = ["belnap_designated"]
    cases, out = case_space(), {}
    for rule in BLIND + SENSITIVE:
        sep, witness = False, None
        for a, b in itertools.combinations(cases, 2):
            v1 = agg([m_three_valued(a)[0], m_three_valued(b)[0]], rule)
            v2 = agg([m_four_valued(a)[0],  m_four_valued(b)[0]],  rule)
            r1 = v1 if v1 in READABLE else "unreadable"
            r2 = v2 if v2 in READABLE else "unreadable"
            if r1 != r2 and not sep:
                sep, witness = True, {"pair": (a["id"], b["id"]), "m3": v1, "m4": v2}
        out[rule] = {"token_sensitive": rule in SENSITIVE,
                     "separates_m3_from_m4": sep, "witness": witness}
    blind_sep = any(out[r]["separates_m3_from_m4"] for r in BLIND)
    sens_sep  = any(out[r]["separates_m3_from_m4"] for r in SENSITIVE)
    return {"question": "does any composition rule separate M3 from M4?",
            "rules": out,
            "token_blind_rules_separate":     blind_sep,
            "token_sensitive_rule_separates": sens_sep,
            "test_is_non_vacuous": sens_sep,
            "corpus_supplies_a_token_sensitive_rule": False,
            "corpus_provenance": ("Experiment G: the composition rule AND its level "
                                  "(requirement / evaluator / value) are both OPEN. "
                                  "No designated-element semantics for C appears in "
                                  "the corpus."),
            "consequence": ("M3 and M4 separate IFF the composition rule is "
                            "token-sensitive.  The corpus supplies no such rule, and "
                            "composition is itself OPEN.  So the fourth-value question "
                            "is NOT independent: it is a COROLLARY of the composition "
                            "question.")}
