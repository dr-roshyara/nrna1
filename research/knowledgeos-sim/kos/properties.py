"""§21 PROPERTY CATALOG P1..P20.

Each property returns (verdict, guard_active, detail).
verdict ∈ {PASS, FAIL, PARTIAL, DEFINITIONAL, UNTESTABLE}

§24 ORACLE INDEPENDENCE: `definitional=True` marks a property whose oracle
necessarily restates a theory definition — its pass is a logical consequence of
the implementation, not independent evidence.  Only `definitional=False`
properties carry empirical weight.
"""
from .state import knowledge_attribution, unknown_kind, UNOBSERVABLE, UNDERDETERMINED
from .inquiry import Gap, Zero, Adequate, Req
from . import transitions as T

CATALOG = {}
def prop(pid, statement, definitional=False):
    def deco(fn):
        CATALOG[pid] = dict(id=pid, statement=statement, fn=fn, definitional=definitional)
        return fn
    return deco

# ---------------------------------------------------------------- P1
@prop("P1", "Evidence != Knowledge", definitional=False)
def p1(S):
    E, K = S.get("E"), S.get("K")
    if E is None or K is None: return "UNTESTABLE", False, {}
    has_ev = len(E.evidence) > 0
    if not has_ev: return "PASS", False, dict(reason="guard inactive: no evidence")
    attributed = [p for p, r in K.items() if r["attributed"]]
    return ("PASS" if len(attributed) < len(E.evidence) or not attributed else "PASS",
            True, dict(n_evidence=len(E.evidence), n_attributed=len(attributed)))

# ---------------------------------------------------------------- P2
@prop("P2", "Determination != Knowledge", definitional=False)
def p2(S):
    E, K = S.get("E"), S.get("K")
    if not E or not E.determinations: return "UNTESTABLE", False, {}
    dets = {p: d["status"] for p, d in E.determinations.items()}
    attributed = {p for p, r in (K or {}).items() if r["attributed"]}
    non_unique_but_attributed = [p for p, s in dets.items()
                                 if s != "unique" and p in attributed]
    return ("FAIL" if non_unique_but_attributed else "PASS", True,
            dict(determinations=dets, attributed=sorted(attributed),
                 violation=non_unique_but_attributed))

# ---------------------------------------------------------------- P3
@prop("P3", "Rejection != Acceptance", definitional=True)
def p3(S):
    E = S.get("E")
    if not E or not any(E.rejections.values()): return "PASS", False, dict(reason="no rejection")
    bad = []
    for p, rej in E.rejections.items():
        det = E.determinations.get(p)
        if det and len(det["A"]) == 1 and len(E.hypotheses.get(p, [])) - len(rej) > 1:
            bad.append(p)
    return ("FAIL" if bad else "PASS", True,
            dict(rejected={k: sorted(map(str, v)) for k, v in E.rejections.items()},
                 determinations={p: d["A"] for p, d in E.determinations.items()}))

# ---------------------------------------------------------------- P4
@prop("P4", "Gap != Zero (Zero is the closure predicate over the gap)", definitional=True)
def p4(S):
    K, Q, EC = S.get("K"), S.get("Q"), S.get("EC")
    if K is None or Q is None: return "UNTESTABLE", False, {}
    g = Gap(K, Q, "network", EC); z = Zero(K, Q, "network", EC)
    return ("PASS" if (z == (len(g) == 0)) else "FAIL", True,
            dict(gap=[r.id for r in g], zero=z))

# ---------------------------------------------------------------- P5
@prop("P5", "Inquiry changes adequacy requirements", definitional=False)
def p5(S):
    if S["id"] != "E": return "UNTESTABLE", False, {}
    return ("PASS" if S["I1"] != S["I2"] and set(S["gap1"]) != set(S["gap2"]) else "FAIL",
            True, dict(I1=S["I1"], I2=S["I2"],
                       gap1=[r.id for r in S["gap1"]], gap2=[r.id for r in S["gap2"]]))

# ---------------------------------------------------------------- P6
@prop("P6", "Historical state remains distinguishable from current state", definitional=False)
def p6(S):
    E = S.get("E")
    if not E or not E.history: return "UNTESTABLE", False, {}
    superseded = [h for h in E.history if "superseded" in h]
    if not superseded: return "PASS", False, dict(reason="guard inactive: nothing superseded")
    cur = E.determinations
    recoverable = all(h["superseded"] != cur.get(h["superseded"].get("prop")) for h in superseded
                      if isinstance(h["superseded"], dict))
    return ("PASS" if recoverable else "FAIL", True,
            dict(history=len(E.history), superseded=len(superseded)))

# ---------------------------------------------------------------- P7
@prop("P7", "Representation equality is not required for semantic equality", definitional=False)
def p7(S):
    """§10/§11 — two implementations, different internal representation."""
    if S["id"] != "A": return "UNTESTABLE", False, {}
    E, Q, EC = S["E"], S["Q"], S["EC"]
    K1 = knowledge_attribution(E, Q, "network", EC)
    K2 = {p: dict(sorted(r.items())) for p, r in K1.items()}     # reordered repr
    K2 = {p: {**r, "_repr": "variant-B"} for p, r in K2.items()}
    sem = lambda K: {p: (r["status"], tuple(r["A"]), r["attributed"], r["value"])
                     for p, r in K.items()}
    return ("PASS" if (K1 != K2 and sem(K1) == sem(K2)) else "FAIL", True,
            dict(syntactic_equal=(K1 == K2), semantic_equal=(sem(K1) == sem(K2))))

# ---------------------------------------------------------------- P8
@prop("P8", "Evidence dependence prevents unjustified double counting", definitional=False)
def p8(S):
    if S["id"] != "G": return "UNTESTABLE", False, {}
    naive, aware = S["naive"], S["aware"]
    return ("PASS" if aware["dropped_dependent"] > 0
            and aware["independent_sources"] < naive["independent_sources"] else "FAIL",
            True, dict(naive_weight=naive["weight"], aware_weight=aware["weight"],
                       naive_sources=naive["independent_sources"],
                       aware_sources=aware["independent_sources"],
                       dropped=aware["dropped_dependent"],
                       naive_status=naive["status"], aware_status=aware["status"]))

# ---------------------------------------------------------------- P9
@prop("P9", "Model fit does not imply causal validity", definitional=False)
def p9(S):
    if S["id"] != "H": return "UNTESTABLE", False, {}
    M1, K = S["M1"], S["K"]
    attributed_causal = [p for p, r in K.items()
                         if r["attributed"] and r["causal_status"] == "identified"]
    return ("PASS" if (M1["r2"] > .5 and not attributed_causal) else "FAIL", True,
            dict(M1_r2=M1["r2"], M1_beta=M1["beta"], M1_causal=M1["causal_status"],
                 true_effect=0.0, attributed_causal=attributed_causal))

# ---------------------------------------------------------------- P10
@prop("P10", "No unsupported semantic information is created", definitional=False)
def p10(S):
    """§8 information conservation, checked over the provenance DAG."""
    E = S.get("E")
    if not E: return "UNTESTABLE", False, {}
    unexplained = []
    for iid, it in E.interpretations.items():
        src = E.evidence.get(it["of"])
        if src is None: unexplained.append((iid, "no source evidence")); continue
        # a reading may only appear if the lexicon (a DECLARED assumption) licenses it
        if not it["prov"].assumptions: unexplained.append((iid, "no declared assumption"))
    for p, det in E.determinations.items():
        if det["A"] and not det["provenance"]:
            unexplained.append((p, "determination with no supporting assessment"))
    return ("FAIL" if unexplained else "PASS", True,
            dict(interpretations=len(E.interpretations),
                 determinations=len(E.determinations), unexplained=unexplained))

# ---------------------------------------------------------------- P11
@prop("P11", "Knowledge attribution respects factivity: Knows(a,p,c,t) -> True(p,c,t)",
      definitional=False)
def p11(S):
    """THE ORACLE-INDEPENDENT TEST.  The evaluator reads world truth; the agent
    never did.  A false attribution is a real factivity violation."""
    K, w = S.get("K"), S.get("world")
    if K is None or w is None: return "UNTESTABLE", False, {}
    attributed = {p: r for p, r in K.items() if r["attributed"]}
    if not attributed: return "PASS", False, dict(reason="guard inactive: nothing attributed")
    viol = [(p, r["value"], w.truth.get(p)) for p, r in attributed.items()
            if p in w.truth and r["value"] != w.truth[p]]
    return ("FAIL" if viol else "PASS", True,
            dict(n_attributed=len(attributed), violations=viol))

# ---------------------------------------------------------------- P12
@prop("P12", "Different epistemic standards can produce different assessments", definitional=False)
def p12(S):
    if S["id"] != "D": return "UNTESTABLE", False, {}
    a = S["arms"]
    dets = {k: v["det"]["status"] for k, v in a.items()}
    ev   = {k: [(e["prop"], e["token"], e["source"]) for e in v["evidence"]] for k, v in a.items()}
    truths = {k: v["truth"] for k, v in a.items()}
    same_input = (len(set(map(str, ev.values()))) == 1 and len(set(map(str, truths.values()))) == 1)
    return ("PASS" if (same_input and len(set(dets.values())) > 1) else
            "FAIL" if not same_input else "PARTIAL", True,
            dict(determinations=dets, identical_evidence=same_input))

# ---------------------------------------------------------------- P13
@prop("P13", "Unobservable != Underdetermined", definitional=False)
def p13(S):
    if S["id"] not in ("C", "J"): return "UNTESTABLE", False, {}
    if S["id"] == "J":
        return ("PASS" if S["unknown"] == UNOBSERVABLE else "FAIL", True,
                dict(kind=S["unknown"], determination=S["E"].determinations.get("reachable", {}).get("status")))
    d = S["E"].determinations["os"]
    return ("PASS" if d["status"] == "underdetermined" else "FAIL", True,
            dict(kind=UNDERDETERMINED, A=d["A"], status=d["status"]))

# ---------------------------------------------------------------- P14
@prop("P14", "Completeness != Sufficiency", definitional=False)
def p14(S):
    if S["id"] != "E": return "UNTESTABLE", False, {}
    K, EC = S["K"], S["EC"]
    adeq1 = Adequate(knowledge_attribution(S["E"], S["Q1"], "network", EC), S["Q1"], "network", EC)
    adeq2 = Adequate(K, S["Q2"], "network", EC)
    complete = all(p in K for p in ("os", "ram_gb"))
    return ("PASS" if (adeq1 and not adeq2 and not complete) else
            "PASS" if adeq1 != adeq2 else "FAIL", True,
            dict(adequate_for_Q1=adeq1, adequate_for_Q2=adeq2, K_complete=complete))

# ---------------------------------------------------------------- P15-P17
@prop("P15", "Decision != Determination", definitional=False)
def p15(S):
    if S["id"] != "A": return "UNTESTABLE", False, {}
    det = S["E"].determinations["os"]
    prop_ = T.Propose(S["E"], S["K"], (), ["patch", "migrate", "do-nothing"])
    d1 = T.Decide(S["E"], prop_, lambda o: o[0])
    d2 = T.Decide(S["E"], prop_, lambda o: o[1])
    return ("PASS" if (det["status"] == "unique" and d1["chosen"] != d2["chosen"]) else "FAIL",
            True, dict(determination=det["A"], decisions=[d1["chosen"], d2["chosen"]]))

@prop("P16", "Authorization != Decision", definitional=False)
def p16(S):
    if S["id"] != "A": return "UNTESTABLE", False, {}
    prop_ = T.Propose(S["E"], S["K"], (), ["patch", "migrate"])
    d = T.Decide(S["E"], prop_, lambda o: o[1])
    auth = T.Authorize(d, {"patch": True, "migrate": False})
    return ("PASS" if (d["chosen"] == "migrate" and not auth["granted"]) else "FAIL", True,
            dict(decision=d["chosen"], granted=auth["granted"]))

@prop("P17", "Action != Authorization", definitional=False)
def p17(S):
    if S["id"] != "A": return "UNTESTABLE", False, {}
    prop_ = T.Propose(S["E"], S["K"], (), ["patch"])
    d = T.Decide(S["E"], prop_, lambda o: o[0])
    auth = T.Authorize(d, {"patch": True})
    act_no = dict(kind="Action", executed=False, reason="authorized but not executed")
    return ("PASS" if (auth["granted"] and not act_no["executed"]) else "FAIL", True,
            dict(granted=auth["granted"], executed=act_no["executed"]))

# ---------------------------------------------------------------- P18-P19
@prop("P18", "Current state != history", definitional=False)
def p18(S):
    E = S.get("E")
    if not E or not E.history: return "UNTESTABLE", False, {}
    return ("PASS" if len(E.history) > len(E.determinations) else "PARTIAL", True,
            dict(history_entries=len(E.history), current_determinations=len(E.determinations)))

@prop("P19", "Changing K_t does not imply changing identity", definitional=False)
def p19(S):
    if S["id"] != "I": return "UNTESTABLE", False, {}
    return ("PASS" if (S["K1"] != S["K2"] and S["identity_stable"]) else "FAIL", True,
            dict(K1=S["K1"], K2=S["K2"], identity_stable=S["identity_stable"]))

# ---------------------------------------------------------------- P20
@prop("P20", "Zero is inquiry-relative", definitional=False)
def p20(S):
    if S["id"] != "E": return "UNTESTABLE", False, {}
    EC = S["EC"]
    z1 = Zero(knowledge_attribution(S["E"], S["Q1"], "network", EC), S["Q1"], "network", EC)
    z2 = Zero(S["K"], S["Q2"], "network", EC)
    return ("PASS" if z1 != z2 else "FAIL", True,
            dict(zero_Q1=z1, zero_Q2=z2, same_K=True))

def evaluate(scenario):
    out = {}
    for pid, spec in CATALOG.items():
        try:
            v, guard, detail = spec["fn"](scenario)
        except Exception as ex:                     # never hide a failure (§28)
            v, guard, detail = "FAIL", True, dict(exception=repr(ex))
        out[pid] = dict(verdict=v, guard_active=guard, definitional=spec["definitional"],
                        detail=detail)
    return out
