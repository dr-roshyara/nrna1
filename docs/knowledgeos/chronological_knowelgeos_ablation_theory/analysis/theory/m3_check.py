"""M3 attack (pre-registration prompts/KNOWLEDGEOS-M3-PREREGISTRATION.md, 1ea8669e..., frozen 3e1e32f65).
Deterministic explicit-state BFS over three sub-models (A work acts, B ruling status/authority, C evidence targeting).
For every invariant: (1) check over all reachable states/transitions of M3; (2) RELAX the enforcing guard/frame and return the shortest
violating trace = the OBSERVATION TEMPLATE a source must record to falsify M3. All outputs MODEL-DERIVED. Usage: python3 m3_check.py [--selftest]"""
import json
import sys
from collections import deque


def bfs(init, ops):
    """ops: list of (label, guard(s), effect(s)->dict, frame:set). Returns (seen: key->(parent_key, label)), states, transitions."""
    k0 = tuple(sorted(init.items())); seen = {k0: None}; q = deque([init]); trans = []
    while q:
        s = q.popleft()
        for lab, g, eff, fr in ops:
            if not g(s): continue
            t = dict(s); t.update(eff(s))
            trans.append((s, lab, t, fr))
            kt = tuple(sorted(t.items()))
            if kt not in seen: seen[kt] = (tuple(sorted(s.items())), lab); q.append(t)
    return seen, trans


def path(seen, k):
    out = []
    while seen[k] is not None: k, lab = seen[k]; out.append(lab)
    return list(reversed(out))


def frame_violations(trans):
    return sorted({(lab, c) for s, lab, t, fr in trans for c in s if s[c] != t[c] and c not in fr})


def first_violation(seen, trans, state_pred=None, trans_pred=None):
    """Shortest trace to a state violating state_pred, or to a transition violating trans_pred (BFS order = shortest)."""
    best = None
    if state_pred:
        for k in seen:
            if not state_pred(dict(k)):
                p = path(seen, k)
                if best is None or len(p) < len(best): best = p
    if trans_pred:
        for s, lab, t, fr in trans:
            if not trans_pred(s, lab, t):
                p = path(seen, tuple(sorted(s.items()))) + [lab]
                if best is None or len(p) < len(best): best = p
    return best


# ---------------- A: work acts ----------------
IT = ["4B", "4C", "4D", "8"]
A0 = {**{f"auth.{x}": "none" for x in IT}, **{f"exec.{x}": "ns" for x in IT}, **{f"acc.{x}": "no" for x in IT + ["4"]},
      **{f"life.{x}": "open" for x in IT + ["4"]}, "perm.8": "no", "comm.8": "no", "board": "pending"}


def A_ops(relax=None):
    o = [("PERMIT(8)", lambda s: s["perm.8"] == "no", lambda s: {"perm.8": "yes"}, {"perm.8"}),
         ("COMMISSION(8)", lambda s: s["comm.8"] == "no", lambda s: {"comm.8": "yes"}, {"comm.8"}),
         ("BOARD-ACTS", lambda s: s["board"] == "pending", lambda s: {"board": "done"}, {"board"}),
         ("AUTHORIZE-COND(4B)", lambda s: s["auth.4B"] == "none", lambda s: {"auth.4B": "cond"}, {"auth.4B"}),
         ("SATISFY-PROVISO(4B)", lambda s: s["board"] == "done" and s["auth.4B"] == "cond", lambda s: {"auth.4B": "full"}, {"auth.4B"})]
    for x, pred in (("4C", "4B"), ("4D", "4C")):
        g = (lambda p, x: lambda s: s[f"acc.{p}"] == "yes" and s[f"auth.{x}"] == "none")(pred, x)
        e = (lambda x: lambda s: {f"auth.{x}": "full"})(x)
        if relax == "I-A5": e = (lambda x: lambda s: {f"auth.{x}": "full", f"acc.{x}": "yes", f"life.{x}": "closed"})(x)
        o.append((f"AUTHORIZE({x})", g, e, {f"auth.{x}"}))
    o.append(("AUTHORIZE(8)", lambda s: s["life.4"] == "closed" and s["auth.8"] == "none", lambda s: {"auth.8": "full"}, {"auth.8"}))
    for x in IT:
        if relax == "I-A1": gs = (lambda x: lambda s: s[f"exec.{x}"] == "ns")(x)
        elif relax == "I-A2": gs = (lambda x: lambda s: s[f"exec.{x}"] == "ns" and (s[f"auth.{x}"] == "full" or (x == "8" and s["perm.8"] == "yes")))(x)
        else: gs = (lambda x: lambda s: s[f"exec.{x}"] == "ns" and s[f"auth.{x}"] == "full")(x)
        o.append((f"START({x})", gs, (lambda x: lambda s: {f"exec.{x}": "ex"})(x), {f"exec.{x}"}))
        o.append((f"FINISH({x})", (lambda x: lambda s: s[f"exec.{x}"] == "ex")(x), (lambda x: lambda s: {f"exec.{x}": "done"})(x), {f"exec.{x}"}))
        ea = (lambda x: lambda s: {f"acc.{x}": "yes", f"life.{x}": "closed"})(x)
        if relax == "I-A4" and x == "4B": ea = lambda s: {"acc.4B": "yes", "life.4B": "closed", "acc.4": "yes", "life.4": "closed"}
        o.append((f"ACCEPT({x})", (lambda x: lambda s: s[f"exec.{x}"] == "done" and s[f"acc.{x}"] == "no")(x), ea,
                  {f"acc.{x}", f"life.{x}"} | ({"acc.4", "life.4"} if relax == "I-A4" and x == "4B" else set())))
        if relax == "I-A3":
            o.append((f"CLOSE({x})", (lambda x: lambda s: s[f"life.{x}"] == "open")(x), (lambda x: lambda s: {f"life.{x}": "closed"})(x), {f"life.{x}"}))
    o.append(("ACCEPT(§4)", lambda s: all(s[f"acc.{x}"] == "yes" for x in ("4B", "4C", "4D")) and s["acc.4"] == "no",
              lambda s: {"acc.4": "yes", "life.4": "closed"}, {"acc.4", "life.4"}))
    return o


A_INV = {
 "I-A1": dict(state=lambda s: all(s[f"exec.{x}"] == "ns" or s[f"auth.{x}"] == "full" for x in IT)),
 "I-A2": dict(state=lambda s: s["exec.8"] == "ns" or s["auth.8"] == "full"),
 "I-A3": dict(state=lambda s: all(s[f"life.{x}"] == "open" or s[f"acc.{x}"] == "yes" for x in IT + ["4"])),
 "I-A4": dict(state=lambda s: not (s["acc.4B"] == "yes" and s["acc.4"] == "yes" and s["acc.4D"] == "no")),
 "I-A5": dict(trans=lambda s, lab, t: not lab.startswith("AUTHORIZE") or all(s[c] == t[c] for c in s if c.startswith(("acc.", "life.")))),
}
R79 = ["BOARD-ACTS", "START(4B)", "ACCEPT(4B)", "AUTHORIZE(4C)", "START(4C)", "ACCEPT(4C)", "AUTHORIZE(4D)", "START(4D)", "ACCEPT(4D)", "ACCEPT(§4)", "AUTHORIZE(8)", "START(8)"]

# ---------------- B: ruling status / authority ----------------
RS = ["r1", "r2"]
B0 = {**{f"iss.{r}": "-" for r in RS}, **{f"st.{r}": "none" for r in RS}, **{f"reg.{r}": "unused" for r in RS}, **{f"text.{r}": "t0" for r in RS},
      **{f"ann.{r}": 0 for r in RS}, **{f"by.{r}": "-" for r in RS}, "sup.r1": "none", "deleg": "none"}


def B_ops(relax=None):
    o = []
    for r in RS:
        for iss in ("human", "chief"):
            def g(s, r=r): return s[f"st.{r}"] == "none" and s[f"reg.{r}"] == "unused"
            def e(s, r=r, iss=iss):
                st = "A" if (iss == "human" and relax != "I-B6") or (iss == "chief" and s["deleg"] == "chief") else "P"
                return {f"iss.{r}": iss, f"st.{r}": st, f"reg.{r}": "used", f"by.{r}": "issue" if st == "A" else "-"}
            o.append((f"ISSUE({r},{iss})", g, e, {f"iss.{r}", f"st.{r}", f"reg.{r}", f"by.{r}"}))
        for actor in ("Authority", "Chief"):
            def g(s, r=r, a=actor): return s[f"st.{r}"] in ("P", "H") and (a == "Authority" or relax == "I-B1")
            def e(s, r=r, a=actor):
                d = {f"st.{r}": "A", f"ann.{r}": min(2, s[f"ann.{r}"] + 1), f"by.{r}": a}
                if relax == "I-B2": d["deleg"] = "chief"
                return d
            o.append((f"ADOPT({r},{actor})", g, e, {f"st.{r}", f"ann.{r}", f"by.{r}"} | ({"deleg"} if relax == "I-B2" else set())))
        o.append((f"HOLD({r})", lambda s, r=r: s[f"st.{r}"] == "P", lambda s, r=r: {f"st.{r}": "H"}, {f"st.{r}"}))
        o.append((f"WITHDRAW({r})", lambda s, r=r: s[f"st.{r}"] == "P",
                  lambda s, r=r: {f"st.{r}": "W", f"reg.{r}": "unused" if relax == "I-B5" else "retired"}, {f"st.{r}", f"reg.{r}"}))
        o.append((f"ANNOTATE({r})", lambda s, r=r: s[f"st.{r}"] != "none" and s[f"ann.{r}"] < 2, lambda s, r=r: {f"ann.{r}": s[f"ann.{r}"] + 1}, {f"ann.{r}"}))
        if relax == "I-B4": o.append((f"AMEND-TEXT({r})", lambda s, r=r: s[f"st.{r}"] == "A" and s[f"text.{r}"] == "t0", lambda s, r=r: {f"text.{r}": "t1"}, {f"text.{r}"}))
    o.append(("SUPERSEDE(r2>r1)", lambda s: s["st.r1"] == "A" and s["st.r2"] == "A" and s["sup.r1"] == "none", lambda s: {"sup.r1": "r2"}, {"sup.r1"}))
    if relax == "I-B3": o.append(("INFER-SUPERSEDED(r1)", lambda s: s["st.r2"] == "A" and s["sup.r1"] == "none", lambda s: {"sup.r1": "r2"}, {"sup.r1"}))
    return o


B_INV = {
 "I-B1": dict(state=lambda s: all(not (s[f"st.{r}"] == "A" and s[f"iss.{r}"] == "chief") or s[f"by.{r}"] == "Authority" for r in RS)),
 "I-B2": dict(state=lambda s: all(not (s[f"iss.{r}"] == "chief" and s[f"by.{r}"] == "issue") for r in RS)),
 "I-B3": dict(trans=lambda s, lab, t: s["sup.r1"] == t["sup.r1"] or lab.startswith("SUPERSEDE")),
 "I-B4": dict(state=lambda s: all(s[f"text.{r}"] == "t0" for r in RS)),
 "I-B5": dict(state=lambda s: all((s[f"st.{r}"] != "W" or s[f"reg.{r}"] == "retired") and (s[f"st.{r}"] != "H" or s[f"reg.{r}"] == "used") for r in RS)),
 "I-B6": dict(state=lambda s: all(not (s[f"iss.{r}"] == "human" and s[f"st.{r}"] == "P") for r in RS)),
}

# ---------------- C: evidence targeting ----------------
C0 = {"stand.dec": "valid", "stand.imp": "valid", "ev.dec": "none", "ev.imp": "none"}


def C_ops(relax=None):
    o = []
    for t in ("dec", "imp"):
        eff = (lambda t: lambda s: {f"ev.{t}": "contra"})(t); fr = {f"ev.{t}"}
        if relax == "I-C1" and t == "imp": eff = lambda s: {"ev.imp": "contra", "stand.dec": "questioned"}; fr = {"ev.imp", "stand.dec"}
        o.append((f"INTAKE-CONTRA({t})", (lambda t: lambda s: s[f"ev.{t}"] == "none")(t), eff, fr))
        o.append((f"REVISE({t})", (lambda t: lambda s: s[f"ev.{t}"] == "contra" and s[f"stand.{t}"] == "valid")(t), (lambda t: lambda s: {f"stand.{t}": "questioned"})(t), {f"stand.{t}"}))
    return o


C_INV = {"I-C1": dict(state=lambda s: s["stand.dec"] == "valid" or s["ev.dec"] == "contra")}

TEMPLATES = {  # observation a source must record to falsify, and the best-observable source family (MODEL-ASSUMPTION ranking)
 "I-A1": ("an implementation start/commit for item X dated before X's full authorization (proviso satisfied)", "git history (mechanical timestamps) × rulings register", 1),
 "I-A2": ("WP-8 implementation work recorded while only planning permission existed", "git history × register (R-79)", 1),
 "I-A3": ("a work item recorded CLOSED with no acceptance act", "register / session logs", 2),
 "I-A4": ("a parent (§WP-4) recorded accepted upon a child's acceptance alone", "register", 2),
 "I-A5": ("an authorization act recorded as also accepting or closing work", "register", 2),
 "I-B1": ("a Chief-issued ruling adopted by the Chief's own act", "register annotations", 2),
 "I-B2": ("after an adoption, a Chief-issued ruling recorded governing at issue", "register", 2),
 "I-B3": ("a ruling treated as superseded with no supersession act", "register / session logs", 3),
 "I-B4": ("a ruling's decision text edited in place after first record", "git history of the register file (mechanical diff)", 1),
 "I-B5": ("a withdrawn identifier reissued", "register + git history", 1),
 "I-B6": ("a human-issued ruling recorded PREPARED", "register", 2),
 "I-C1": ("a decision's standing lowered on implementation-level evidence alone", "register / reviews", 3)}


def attack(name, init, opsf, inv):
    seen, trans = bfs(init, opsf())
    res = {"reachable_states": len(seen), "frame_violations": frame_violations(trans)}
    for k, p in inv.items():
        holds = first_violation(seen, trans, p.get("state"), p.get("trans")) is None
        rs, rt = bfs(init, opsf(k))
        cx = first_violation(rs, rt, p.get("state"), p.get("trans"))
        res[k] = {"holds_in_M3": holds, "relaxation_counterexample": cx, "template": TEMPLATES[k][0], "observable_in": TEMPLATES[k][1], "observability_rank": TEMPLATES[k][2]}
    return res, seen, trans


def selftest():
    bad = [(l, g, e, set() if l.startswith("START") else f) for l, g, e, f in A_ops()]
    _, tr = bfs(A0, bad)
    rA, sA, _ = attack("A", A0, A_ops, A_INV); rB, _, _ = attack("B", B0, B_ops, B_INV); rC, _, _ = attack("C", C0, C_ops, C_INV)
    all_hold = all(v["holds_in_M3"] for r in (rA, rB, rC) for k, v in r.items() if k.startswith("I-"))
    all_cx = all(v["relaxation_counterexample"] for r in (rA, rB, rC) for k, v in r.items() if k.startswith("I-"))
    ok = [("mutation: START frame emptied -> frame violation detected", frame_violations(tr) != []),
          ("M3 frames clean", rA["frame_violations"] == [] and rB["frame_violations"] == [] and rC["frame_violations"] == []),
          ("every invariant holds in M3", all_hold), ("every relaxation yields a counterexample (invariants are not vacuous)", all_cx)]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    rA, sA, tA = attack("A", A0, A_ops, A_INV); rB, _, _ = attack("B", B0, B_ops, B_INV); rC, _, _ = attack("C", C0, C_ops, C_INV)
    goal = [k for k in sA if dict(k)["exec.8"] != "ns"]
    shortest = min((path(sA, k) for k in goal), key=len) if goal else None
    forced = all(dict(k)["board"] == "done" and all(dict(k)[f"acc.{x}"] == "yes" for x in ("4B", "4C", "4D", "4")) for k in goal)
    sub = [lab for lab in (shortest or []) if lab in R79]
    liveness = {"START(8) reachable": bool(goal), "ACCEPT(§4) reachable": any(dict(k)["acc.4"] == "yes" for k in sA),
                "permission-without-authorization state reachable (I-A2 witness)": any(dict(k)["perm.8"] == "yes" and dict(k)["auth.8"] == "none" for k in sA)}
    seq = {"shortest_path_to_START(8)": shortest, "R-79 order restricted to shared steps": sub == [x for x in R79 if x in sub],
           "every START(8)-state has board acts + 4B,4C,4D,§4 accepted (sequence forced by guards)": forced}
    ranked = sorted([(v["observability_rank"], k) for r in (rA, rB, rC) for k, v in r.items() if k.startswith("I-")])
    print(json.dumps({"labels": "MODEL-DERIVED; development-data basis; relaxation counterexamples are observation templates, not observations",
                      "A_work": rA, "B_rulings": rB, "C_evidence": rC, "liveness": liveness, "R-79 sequence check": seq,
                      "next_read_ranking (rank 1 = mechanical, cross-source)": [k for r, k in ranked if r == 1]}, indent=1, ensure_ascii=False))
