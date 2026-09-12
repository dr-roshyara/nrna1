"""EXPERIMENT G — Phases A6, B, C, D and the ten negative tests."""
import itertools, json
from .evalc import (EVal, value, CONTENT_MODELS, eval_evidence, eval_provenance,
                    eval_status, eval_consistency, eval_governance, eval_temporal,
                    eval_operational)

CLASSES = ["content","evidence","provenance","status","consistency",
           "governance","temporal","operational"]

# ================================================================ deterministic cases
CASES = {
 "B1-factivity": dict(K=dict(content={"p"}, weight=1.0, evidence_seen=True, provenance=("a1",)),
                      r=dict(e_min=1.0, delta_defined=False), note="policy-trusted source, false report"),
 "B2-revision":  dict(K=dict(content={"p","np"}, weight=1.0, evidence_seen=True, provenance=("a1","a2")),
                      r=dict(e_min=1.0, delta_defined=False), note="stale evidence never retired"),
 "B3-no-source": dict(K=dict(content=set(), weight=0.0, evidence_seen=True, provenance=()),
                      r=dict(e_min=1.0, delta_defined=False), note="source/provenance removed"),
 "B4-blocked":   dict(K=dict(content={"p"}, weight=1.0, evidence_seen=True, provenance=("a1",)),
                      r=dict(e_min=1.0, delta_defined=False), note="only the blocked classes remain"),
 # separating cases required by PB-2/PB-3/PB-5 (permitted, deterministic)
 "S1-contradiction": dict(K=dict(content={"p","np"}, weight=1.0, evidence_seen=True, provenance=("a1",)),
                      r=dict(e_min=1.0, delta_defined=False), note="PB-2 separating case"),
 "S2-delta-defined": dict(K=dict(content={"p"}, weight=1.0, evidence_seen=True, provenance=("a1",)),
                      r=dict(e_min=1.0, delta_defined=True, postcondition_met=True),
                      note="PB-5: δ supplied, κ non-operational"),
 "S3-kappa-op":  dict(K=dict(content={"p"}, weight=1.0, evidence_seen=True, provenance=("a1",)),
                      r=dict(e_min=1.0, delta_defined=True, postcondition_met=True),
                      kappa="operational", note="PB-5: κ = operational, NOT excluded"),
}

def evaluate_case(case, content_model="delegated"):
    K, r = case["K"], case["r"]
    kc = case.get("kappa", "content")
    cm = CONTENT_MODELS[content_model]
    out = {}
    out["content"]     = cm(K["content"]) if content_model != "delegated" else cm(K["content"], False)
    out["evidence"]    = eval_evidence(K, r)
    out["provenance"]  = eval_provenance(K, r)
    out["status"]      = eval_status(K, r)
    out["consistency"] = eval_consistency(K, r)
    out["governance"]  = eval_governance(K, r)
    out["temporal"]    = eval_temporal(K, r)
    out["operational"] = eval_operational(K, r, kc)
    return out

# ================================================================ A6 / N2  is 3 values enough?
def A6_projection_loss():
    """Count distinguishable evaluation situations under full EVal vs under `value` alone."""
    full, vals = set(), set()
    rows = []
    for cm in CONTENT_MODELS:
        for name, case in CASES.items():
            ev = evaluate_case(case, cm)
            for cls, e in ev.items():
                full.add((cm, cls) + e.key_full())
                vals.add((cm, cls) + e.key_value())
                rows.append(dict(model=cm, case=name, cls=cls, value=e.value,
                                 reason=e.reason, evaluator=e.evaluator,
                                 theory_status=e.theory_status))
    return dict(distinct_under_full_EVal=len(full), distinct_under_value_only=len(vals),
                collapse_ratio=len(vals)/len(full),
                verdict=("value ∘ Eval is LOSSY: %d situations collapse to %d"
                         % (len(full), len(vals))),
                rows=rows)

# ================================================================ B1 totality  (N1)
def B1_totality():
    out = {}
    for cm in CONTENT_MODELS:
        undefined, restricted = [], []
        for name, case in CASES.items():
            ev = evaluate_case(case, cm)
            for cls, e in ev.items():
                if e.value == "UNDEFINED": undefined.append((name, cls))
                if e.domain_restricted:    restricted.append((name, cls))
        out[cm] = dict(undefined_results=undefined, domain_restricted=restricted,
                       total=not undefined)
    return out

# ================================================================ B5 / composition  (N6)
def C_composition():
    """Do NOT assume Kleene conjunction.  Test three composition rules."""
    T,F,U,C = "T","F","U","C"
    def kleene(v):
        if F in v: return F
        if all(x==T for x in v): return T
        return U
    def strong_u(v):          # U dominates: any U makes the composite U
        if U in v: return U
        if F in v: return F
        return T
    def bochvar(v):           # U is infectious even over F
        return U if U in v else (F if F in v else T)
    rules = dict(kleene=kleene, u_dominant=strong_u, bochvar=bochvar)
    # measure over the actual case evaluations
    res = {}
    for rn, fn in rules.items():
        per_case = {}
        for name, case in CASES.items():
            ev = evaluate_case(case, "delegated")
            vs = [ev[c].value for c in CLASSES]
            per_case[name] = fn(vs)
        res[rn] = per_case
    agree = all(res["kleene"][c]==res["u_dominant"][c]==res["bochvar"][c] for c in CASES)
    return dict(rules=res, all_rules_agree_on_these_cases=agree,
                note="On these cases the three rules agree, so the deterministic suite "
                     "CANNOT discriminate among them. Composition rule remains OPEN.",
                composition_level_question="requirement / evaluator / value — NOT decided here")

# ================================================================ D  Zero  (N8, N9)
BUCKET = {"UNOBSERVED":"agent","UNINTERPRETED":"agent","UNDERDETERMINED":"agent",
          "INSUFFICIENT_PROVENANCE":"agent","CONTRADICTORY_INPUT":"agent",
          "NO_EVALUATOR":"theory","NO_ORDERING":"theory","NO_TEMPORAL_SEMANTICS":"theory",
          "DELTA_UNDEFINED":"theory","NON_TERMINATING":"theory","CONTRADICTION":"agent",
          "UNOBSERVABLE":"world", None:"n/a"}

def D_zero(content_model="delegated"):
    out = {}
    for name, case in CASES.items():
        ev = evaluate_case(case, content_model)
        vals = {c: ev[c].value for c in CLASSES}
        rs   = {c: ev[c].reason for c in CLASSES}
        strict   = all(v=="T" for v in vals.values())
        weak     = not any(v=="F" for v in vals.values())
        reasoned = weak and not any(BUCKET.get(rs[c])=="agent"
                                    for c,v in vals.items() if v=="U")
        kleene   = "F" if "F" in vals.values() else ("U" if "U" in vals.values() else "T")
        out[name] = dict(values=vals, reasons=rs, strict=strict, reasoned=reasoned,
                         weak=weak, kleene=kleene,
                         u_buckets={c: BUCKET.get(rs[c]) for c,v in vals.items() if v=="U"})
    return out

def run():
    return dict(A6=A6_projection_loss(), B1=B1_totality(), C=C_composition(),
                D=D_zero(), D_4valued=D_zero("four_valued"))
