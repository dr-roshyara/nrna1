"""Hypothesis discrimination for the promotion transition model. Research instrument (not a test); deterministic; no ML.
Labels: every hypothesis = HYPOTHESIS; case attributes = SOURCE-FACT or MODEL-ASSUMPTION (heading/metadata reading) as tagged;
all computed outputs = MODEL-DERIVED. Nothing here is an EMPIRICAL-RESULT; it ranks which unread case would discriminate most.

Observable outcome of one Promote act (NOT-RECORDED != FALSE, so UNDET is always possible and carries no information):
  CONF                        Ev (and, if high, Qual/Val) before P
  (dEv|dVal, BARE|OBL|EXC)    deviation: dEv = no prior necessity evidence; dVal = evidence prior, validation only after P
                              marker: BARE = source positively records neither; OBL = later validation / obligation recorded;
                              EXC = exception recorded
Case attributes: force of the decisive requirement (IN / AMB), kind (knowledge / work), high (target >= Engineering Standard)."""
import itertools
import json
import math

DEVS = [(d, m) for d in ("dEv", "dVal") for m in ("BARE", "OBL", "EXC")]
OUTCOMES = ["CONF"] + DEVS


def allowed(h, force, kind, high):
    """Outcome set each hypothesis permits (HYPOTHESIS predicates, finite and explicit)."""
    if h == "H1":   # evidence-first norm governs practice
        return {"CONF"}
    if h == "H2":   # authorization before validation is normal practice; necessity evidence still precedes P
        return {"CONF"} | ({o for o in DEVS if o[0] == "dVal"} if high else set())
    if h == "H3":   # kind-dependent transition systems: knowledge = H1 order; governed work = authorize -> execute -> validate
        return {"CONF"} if kind == "knowledge" else {"CONF"} | {o for o in DEVS if o[0] == "dVal"}
    if h == "H4":   # apparent violations are force/applicability differences: none under IN force
        return {"CONF"} if force == "IN" else set(OUTCOMES)
    if h == "H5":   # provisional adoption -> validation obligation -> later validation
        return {"CONF"} | {o for o in DEVS if o[1] == "OBL"}
    raise KeyError(h)


HYPS = ["H1", "H2", "H3", "H4", "H5"]


def likelihood(h, o, attrs, u):
    """P(o | h, attrs). u = P(UNDET) (the source is silent); the rest uniform over h's permitted set.
    An attribute given as None is marginalized 50/50 (MODEL-ASSUMPTION: no preference)."""
    grid = [dict(zip(attrs, v)) for v in itertools.product(*[[attrs[k]] if attrs[k] is not None else vals
             for k, vals in (("force", ["IN", "AMB"]), ("kind", ["knowledge", "work"]), ("high", [False, True]))])]
    p = 0.0
    for g in grid:
        s = allowed(h, g["force"], g["kind"], g["high"])
        p += (u if o == "UNDET" else (1 - u) * (1 / len(s) if o in s else 0.0)) / len(grid)
    return p


def entropy(ps):
    return -sum(p * math.log2(p) for p in ps if p > 0)


def expected_info_gain(prior, attrs, u):
    H0 = entropy(prior.values()); eig = 0.0
    for o in OUTCOMES + ["UNDET"]:
        joint = {h: prior[h] * likelihood(h, o, attrs, u) for h in prior}
        z = sum(joint.values())
        if z > 0: eig += z * (H0 - entropy([v / z for v in joint.values()]))
    return eig


def distinguishing_pairs(attrs):
    """For fixed attributes: which hypothesis pairs are separated by at least one observable outcome (MODEL-DERIVED)."""
    out = {}
    for a, b in itertools.combinations(HYPS, 2):
        sa, sb = allowed(a, *attrs), allowed(b, *attrs)
        out[f"{a}|{b}"] = sorted(map(str, sa ^ sb)) or "INDISTINGUISHABLE"
    return out


# ---- development evidence (already read; development, never test) ----
# P1 (R-41 / ES-004.3): evidence prior (1 instance), Promote by PA, Validation recorded after -> (dVal, OBL).
# force AMB [SOURCE-FACT: ES-006 PROPOSED in all versions; I-R39 applicability UNKNOWN for a documentation standard];
# high True [MODEL-ASSUMPTION: READING target ES-00x]; kind knowledge [MODEL-ASSUMPTION: a documentation standard].
DEV_EVIDENCE = [("P1", ("dVal", "OBL"), {"force": "AMB", "kind": "knowledge", "high": True})]
# P2 and R-100: UNDET (no information). R-39: not encoded as an order trace (its record is a ruling, not an act trace).

# ---- unread candidates (metadata / headings only; attributes are MODEL-ASSUMPTION unless tagged) ----
# force IN only where an eligible interpretation (I-R39: DA, register, 2026-07-26, "methodology principles") is prior and
# plausibly applicable; otherwise the decisive requirement is ES-006.1 text -> AMB (SOURCE-FACT).
CANDIDATES = {
 "S-0815-L493 vocabulary ruling ('single occurrence')": {"force": "IN", "kind": "knowledge", "high": False},
 "S-0804-L205 meta-principle freeze adopted":            {"force": "IN", "kind": None, "high": False},
 "R-88 ADOPTED (S-0804-L328)":                           {"force": None, "kind": "knowledge", "high": False},
 "S-0817-L170 fork ruling":                              {"force": None, "kind": None, "high": False},
 "S-0804-L82 collector birth convention adopted":        {"force": "AMB", "kind": "knowledge", "high": False},
 "S-0804-L811 qualification split adopted":              {"force": "AMB", "kind": "knowledge", "high": None},
 "S-0804-L216/L525 discovery freeze adopted":            {"force": "AMB", "kind": "work", "high": False},
 "Plan Concept Paper ADOPTED (dc6b9e342)":               {"force": "AMB", "kind": "work", "high": None},
 "R-36 (pre-I-R39; ES-006.1 text only)":                 {"force": "AMB", "kind": "knowledge", "high": None},
}
REJECT_ONLY = ["S-0823 non-action 'from a single occurrence' (Reject: every hypothesis permits it -> EIG 0)"]


def posterior_after_dev(prior):
    post = dict(prior)
    for _, o, a in DEV_EVIDENCE:
        post = {h: post[h] * likelihood(h, o, a, 0.5) for h in post}
        z = sum(post.values()); post = {h: v / z for h, v in post.items()}
    return post


if __name__ == "__main__":
    prior = {h: 1 / len(HYPS) for h in HYPS}
    post = posterior_after_dev(prior)
    rep = {"labels": "all values MODEL-DERIVED; attributes MODEL-ASSUMPTION unless tagged in source comments",
           "development_update (P1 only)": {h: round(v, 4) for h, v in post.items()},
           "P1 permitted_by": {h: ("dVal", "OBL") in allowed(h, "AMB", "knowledge", True) for h in HYPS},
           "P1 permitted_by if kind=work": {h: ("dVal", "OBL") in allowed(h, "AMB", "work", True) for h in HYPS},
           "separability": {"force IN, knowledge, low": distinguishing_pairs(("IN", "knowledge", False)),
                            "force AMB, knowledge, low": distinguishing_pairs(("AMB", "knowledge", False))},
           "eig_bits": {}}
    for u in (0.25, 0.5, 0.75):
        rep["eig_bits"][f"u={u}"] = dict(sorted(((n, round(expected_info_gain(post, a, u), 4)) for n, a in CANDIDATES.items()),
                                              key=lambda x: -x[1]))
    rep["eig_bits"]["reject_only"] = REJECT_ONLY
    print(json.dumps(rep, indent=1, ensure_ascii=False))
