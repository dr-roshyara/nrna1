"""Distinction-preservation engine + witness generator.

Outcomes are NOT yes/no.  Per spec: preserved | collapsed | indeterminate |
not-representable -- and every collapse carries a WITNESS.
"""
import itertools
from .models import NOT_REPRESENTABLE
from .scenarios import SCENARIOS, BY_NAME, REQUIRED, REQUIRED_PROVENANCE

PRESERVED, COLLAPSED, INDETERMINATE, NOT_REPR = (
    "preserved", "collapsed", "indeterminate", "not-representable")

def outcome(model, a, b):
    """Classify how `model` treats the required distinction (a,b)."""
    sa, sb = BY_NAME[a], BY_NAME[b]
    ra, rb = model.evaluate(sa), model.evaluate(sb)
    if ra == NOT_REPRESENTABLE or rb == NOT_REPRESENTABLE:
        return NOT_REPR, {"a": str(ra), "b": str(rb)}
    if ra != rb:
        return PRESERVED, None
    return COLLAPSED, {"situation_a": a, "situation_b": b,
                       "representation": model.name,
                       "identical_representation": str(ra),
                       "required_distinction": f"{a} != {b}"}

def distinction_matrix(models, required=None):
    required = required or REQUIRED
    rows, witnesses = {}, []
    for m in models:
        res = {}
        for a, b in required:
            o, w = outcome(m, a, b)
            res[f"{a}|{b}"] = o
            if w and o == COLLAPSED: witnesses.append(w)
            elif w and o == NOT_REPR:
                witnesses.append({"situation_a": a, "situation_b": b,
                                  "representation": m.name,
                                  "not_representable": w})
        rows[m.name] = {
            "outcomes": res,
            "preserved":        sum(1 for v in res.values() if v == PRESERVED),
            "collapsed":        sum(1 for v in res.values() if v == COLLAPSED),
            "not_representable":sum(1 for v in res.values() if v == NOT_REPR),
            "adequate": all(v == PRESERVED for v in res.values())}
    return {"required_provenance": REQUIRED_PROVENANCE,
            "n_required": len(required), "models": rows, "witnesses": witnesses}

def full_collapse_report(models):
    """Every collapse over ALL scenario pairs, not only required ones."""
    names = [s.name for s in SCENARIOS]
    out = {}
    for m in models:
        groups = {}
        for n in names:
            r = m.evaluate(BY_NAME[n])
            groups.setdefault(str(r), []).append(n)
        merged = {k: v for k, v in groups.items() if len(v) > 1}
        out[m.name] = {"n_distinct_representations": len(groups),
                       "n_scenarios": len(names),
                       "merged_classes": merged}
    return out

def equivalent_under_required(m1, m2, required=None):
    """R1 ~=_{R_req} R2 -- do two representations agree on which required
    distinctions they preserve?  This is the right question; 'which looks better'
    is not."""
    required = required or REQUIRED
    d1 = {f"{a}|{b}": outcome(m1, a, b)[0] for a, b in required}
    d2 = {f"{a}|{b}": outcome(m2, a, b)[0] for a, b in required}
    diff = {k: [d1[k], d2[k]] for k in d1 if d1[k] != d2[k]}
    return {"m1": m1.name, "m2": m2.name, "equivalent": not diff, "differences": diff}
