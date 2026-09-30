"""M0 verifier (pre-registration prompts/KNOWLEDGEOS-M0-TRANSITION-MODEL-PREREGISTRATION.md, fde7995f...).
Deterministic explicit-state enumeration; no ML, no statistics. Every output is MODEL-DERIVED; coded development acts are
MODEL-ASSUMPTION encodings of SOURCE facts (readings tagged). Usage: python3 m0_check.py [--selftest]"""
import itertools
import json
import sys
from collections import deque

DOM = {"standing": ("none", "candidate", "adopted"), "validation": ("none", "validated"), "evidence": (0, 1, 2),
       "contra": ("no", "yes"), "identity": ("observation", "rule"), "regime": ("open", "frozen"),
       "governance": ("open", "closed"), "execution": ("open", "closed")}
C = tuple(DOM)
FRAME = {"INTAKE": {"evidence"}, "CONTRA": {"contra"}, "RAISE": {"standing"}, "CREATE-NORM": {"standing", "identity"},
         "LAPSE": {"standing"}, "VALIDATE": {"validation"}, "RECLASSIFY": {"identity"}, "FREEZE": {"regime"},
         "REOPEN": {"regime"}, "GOV-CLOSE": {"governance"}, "EXEC-CLOSE": {"execution"}, "REJECT": set()}
NEEDS_AUTH = {"RAISE", "CREATE-NORM", "LAPSE", "FREEZE", "REOPEN", "GOV-CLOSE"}
BARRED = {"RAISE", "CREATE-NORM"}
INIT = dict(standing="none", validation="none", evidence=0, contra="no", identity="observation", regime="open", governance="open", execution="open")
VARIANTS = ("G-R", "G-K", "G-O", "G-E")
MODELS = [(v, fs) for v in VARIANTS for fs in (False, True)]          # fs = force-sensitive (the H4 form)
INVS = [dict(op=o, route=r, auth=a, effect=e, exc=x, force=f) for o in FRAME for r in ("RULING", "PROMOTION", "DIRECTIVE")
        for a in ("named", "none") for e in ("normative", "epistemic") for x in (False, True) for f in ("IN", "AMB")]


def mname(m): return m[0] + ("/force-sensitive" if m[1] else "/force-insensitive")


def apply(s, inv, frame=FRAME):
    """Effect of an operation (None if not applicable in s). Writes only coordinates the op intends to change."""
    o, t = inv["op"], dict(s)
    if o == "INTAKE": t["evidence"] = min(2, s["evidence"] + 1)
    elif o == "CONTRA": t["contra"] = "yes"
    elif o == "RAISE":
        if s["standing"] == "adopted": return None
        t["standing"] = {"none": "candidate", "candidate": "adopted"}[s["standing"]]
    elif o == "CREATE-NORM":
        if s["standing"] != "none": return None
        t["standing"], t["identity"] = "adopted", "rule"
    elif o == "LAPSE":
        if s["standing"] == "none": return None
        t["standing"] = {"candidate": "none", "adopted": "candidate"}[s["standing"]]
    elif o == "VALIDATE": t["validation"] = "validated"
    elif o == "RECLASSIFY": t["identity"] = "rule" if s["identity"] == "observation" else "observation"
    elif o == "FREEZE":
        if s["regime"] == "frozen": return None
        t["regime"] = "frozen"
    elif o == "REOPEN":
        if s["regime"] == "open": return None
        t["regime"] = "open"
    elif o == "GOV-CLOSE": t["governance"] = "closed"
    elif o == "EXEC-CLOSE": t["execution"] = "closed"
    return t


def bar_needed(variant, s, inv):
    return {"G-R": inv["route"] == "PROMOTION", "G-K": s["identity"] == "observation",
            "G-O": inv["op"] == "RAISE", "G-E": inv["effect"] == "epistemic"}[variant]


def guard(model, s, inv, has_exec_close=False):
    o = inv["op"]
    if o in NEEDS_AUTH and inv["auth"] != "named": return False
    if o == "RAISE" and s["regime"] == "frozen": return False
    if o == "REOPEN" and s["contra"] != "yes": return False
    if o == "EXEC-CLOSE" and not has_exec_close: return False
    if o in BARRED and bar_needed(model[0], s, inv) and not (model[1] and inv["force"] == "AMB"):
        return s["evidence"] == 2 or inv["exc"]
    return True


def key(s): return tuple(s[c] for c in C)


def successors(model, s, has_exec_close=False):
    for inv in INVS:
        if guard(model, s, inv, has_exec_close):
            t = apply(s, inv)
            if t is not None: yield inv, t


def reachable(model, has_exec_close=False):
    seen = {key(INIT): None}; q = deque([INIT])
    while q:
        s = q.popleft()
        for inv, t in successors(model, s, has_exec_close):
            if key(t) not in seen: seen[key(t)] = (key(s), inv["op"]); q.append(t)
    return seen


def path(seen, k):
    out = []
    while seen[k] is not None: k, op = seen[k]; out.append(op)
    return list(reversed(out))


def frame_violations(frame=FRAME):
    """P-frame: every enabled transition changes only coordinates in Frame(op)."""
    bad = []
    for m in MODELS:
        seen = reachable(m)
        for k in seen:
            s = dict(zip(C, k))
            for inv, t in successors(m, s):
                ch = {c for c in C if s[c] != t[c]}
                if not ch <= frame[inv["op"]]: bad.append((mname(m), inv["op"], sorted(ch)))
    return bad


def freeze_alternative():
    """P-frame-fit for L205-A1 with the alternative Frame(FREEZE) = {standing}: the freeze must block future RAISE while the
    existing set's standing is unchanged (SOURCE). With standing unchanged the post-state equals the pre-state on every
    coordinate the alternative frame allows, so any state-reading guard enables exactly the same RAISE invocations."""
    s = dict(INIT, standing="adopted", evidence=2)   # an existing adopted item; freeze leaves standing as is
    post = dict(s)                                    # alternative frame: only standing may change, and it does not
    raise_before = [i for i in INVS if i["op"] == "RAISE" and guard(("G-O", False), dict(s, standing="candidate"), i)]
    raise_after = [i for i in INVS if i["op"] == "RAISE" and guard(("G-O", False), dict(post, standing="candidate"), i)]
    return {"alternative frame {standing} can express 'discovery stops'": raise_before != raise_after,
            "M0 frame {regime} can express it": any(guard(("G-O", False), dict(s, standing="candidate"), i) for i in INVS if i["op"] == "RAISE")
            and not any(guard(("G-O", False), dict(s, standing="candidate", regime="frozen"), i) for i in INVS if i["op"] == "RAISE")}


def gate_invariance():
    """P-gate-invariance: for which variants can RECLASSIFY change whether a RAISE is enabled (L205-A3 'same gate')."""
    out = {}
    for v in VARIANTS:
        m = (v, False); changed = False
        for vals in itertools.product(*DOM.values()):
            s = dict(zip(C, vals)); t = apply(s, {"op": "RECLASSIFY"})
            if any(guard(m, s, i) != guard(m, t, i) for i in INVS if i["op"] == "RAISE"): changed = True; break
        out[v] = "RECLASSIFY CAN change the gate" if changed else "gate invariant under RECLASSIFY"
    return out


def markov_retired_ids(depth=4):
    """P-markov with the R-90 rule ('RETIRED, not recycled'). Item-level state: holder ∈ {none, held}. Ops ASSIGN, WITHDRAW.
    ASSIGN is enabled iff the identifier was never withdrawn: a HISTORY-reading guard. Checks whether the enabled set is a
    function of (a) holder only and (b) holder + registry ∈ {unused, used, retired}."""
    def run(trace):
        holder, withdrawn, reg = "none", False, "unused"
        for op in trace:
            if op == "ASSIGN":
                if holder != "none" or withdrawn: return None
                holder, reg = "held", "used"
            else:
                if holder != "held": return None
                holder, withdrawn, reg = "none", True, "retired"
        enabled = frozenset(o for o in ("ASSIGN", "WITHDRAW") if (o == "ASSIGN" and holder == "none" and not withdrawn) or (o == "WITHDRAW" and holder == "held"))
        return holder, reg, enabled
    res = {"holder only": {}, "holder + registry": {}}; witness = None
    for n in range(depth + 1):
        for tr in itertools.product(("ASSIGN", "WITHDRAW"), repeat=n):
            r = run(tr)
            if r is None: continue
            h, reg, en = r
            for name, k in (("holder only", h), ("holder + registry", (h, reg))):
                prev = res[name].setdefault(k, (en, tr))
                if prev[0] != en and name == "holder only" and witness is None:
                    witness = {"state": h, "trace_1": list(prev[1]), "enabled_1": sorted(prev[0]), "trace_2": list(tr), "enabled_2": sorted(en)}
    markov = {name: all(len({v[0] for kk, v in d.items() if kk == k}) == 1 for k in d) for name, d in res.items()}
    # recompute properly: collect all enabled sets per state
    per = {"holder only": {}, "holder + registry": {}}
    for n in range(depth + 1):
        for tr in itertools.product(("ASSIGN", "WITHDRAW"), repeat=n):
            r = run(tr)
            if r is None: continue
            per["holder only"].setdefault(r[0], set()).add(r[2]); per["holder + registry"].setdefault((r[0], r[1]), set()).add(r[2])
    markov = {name: all(len(v) == 1 for v in d.values()) for name, d in per.items()}
    return {"markov": markov, "witness": witness}


def distinguishing():
    """P-dist: for each model pair, the shortest trace (legal under both) to a state where some invocation is legal under
    exactly one. Reported with the fields a source must record to observe it."""
    out = {}
    for a, b in itertools.combinations(MODELS, 2):
        seen = {key(INIT): None}; q = deque([INIT]); found = None
        while q and not found:
            s = q.popleft()
            for inv in INVS:
                ga, gb = guard(a, s, inv), guard(b, s, inv)
                if ga != gb and apply(s, inv) is not None:
                    found = {"prefix": path(seen, key(s)), "state": {c: s[c] for c in ("standing", "evidence", "identity", "regime")},
                             "invocation": inv, "legal_under": mname(a if ga else b)}; break
            for inv in INVS:
                if guard(a, s, inv) and guard(b, s, inv):
                    t = apply(s, inv)
                    if t is not None and key(t) not in seen: seen[key(t)] = (key(s), inv["op"]); q.append(t)
        out[f"{mname(a)} | {mname(b)}"] = found or "EQUIVALENT (no distinguishing invocation reachable)"
    return out


# ---- coded development acts (MODEL-ASSUMPTION encodings; None = NOT-RECORDED, open world) ----
DEV = [
 {"id": "L493-A", "happened": True, "op": ["CREATE-NORM"], "route": ["RULING"], "identity": ["rule", "observation"], "effect": ["normative"], "evidence": None, "exc": None, "force": ["IN", "AMB"], "auth": "named"},
 {"id": "L493-B", "happened": False, "reason_bar": True, "op": ["RAISE"], "route": ["PROMOTION"], "identity": ["observation"], "effect": ["epistemic"], "evidence": [1], "exc": [False], "force": ["AMB"], "auth": "named",
  "note": "refused 'single occurrence, and ES-006.1 forbids promoting from one': the stated reason is the bar"},
 {"id": "P1", "happened": True, "op": ["RAISE", "CREATE-NORM"], "route": ["RULING"], "identity": ["observation", "rule"], "effect": ["normative", "epistemic"], "evidence": [1], "exc": None, "force": ["AMB"], "auth": "named",
  "note": "READINGS: route RULING ('by explicit PA instruction'); op RAISE if read as generalizing the WP-1 correction, CREATE-NORM if a new rule"},
 {"id": "R-39", "happened": True, "op": ["RAISE"], "route": ["RULING"], "identity": ["observation", "rule"], "effect": ["epistemic", "normative"], "evidence": None, "exc": [True], "force": ["IN", "AMB"], "auth": "named"},
 {"id": "R-100", "happened": True, "op": ["CREATE-NORM", "RAISE"], "route": ["RULING"], "identity": ["rule", "observation"], "effect": ["normative"], "evidence": None, "exc": None, "force": ["IN", "AMB"], "auth": "named"},
 {"id": "P2", "happened": True, "op": ["RAISE"], "route": ["RULING", "PROMOTION"], "identity": ["observation", "rule"], "effect": ["epistemic", "normative"], "evidence": [2], "exc": None, "force": ["AMB"], "auth": "named"},
]


def dev_consistency(closed_world_exc=False):
    out = {}
    for m in MODELS:
        r = {}
        for d in DEV:
            evs = d["evidence"] or [0, 1, 2]; excs = d["exc"] or ([False] if closed_world_exc else [False, True])
            ok_any = False; reason_ok = False
            for op, ro, idn, ef, ev, x, f in itertools.product(d["op"], d["route"], d["identity"], d["effect"], evs, excs, d["force"]):
                s = dict(INIT, identity=idn, evidence=ev, standing="none" if op == "CREATE-NORM" else "candidate")
                g = guard(m, s, dict(op=op, route=ro, auth=d["auth"], effect=ef, exc=x, force=f))
                if d["happened"] and g: ok_any = True
                if not d["happened"]:
                    ok_any = True                                  # a refusal is always permitted
                    if not g: reason_ok = True                    # the stated reason (the bar) is what the guard says
            r[d["id"]] = "CONSISTENT" if ok_any and (d["happened"] or reason_ok) else ("REASON-INCONSISTENT" if ok_any else "INCONSISTENT")
        out[mname(m)] = r
    return out


def p1_conditional():
    """P1 per reading, with the unrecorded exception closed to 'none' (sensitivity): which guard models permit the adoption."""
    out = {}
    for op, idn, ef in itertools.product(("RAISE", "CREATE-NORM"), ("observation", "rule"), ("normative", "epistemic")):
        s = dict(INIT, identity=idn, evidence=1, standing="none" if op == "CREATE-NORM" else "candidate")
        inv = dict(op=op, route="RULING", auth="named", effect=ef, exc=False, force="AMB")
        out[f"op={op} identity={idn} effect={ef}"] = sorted(mname(m) for m in MODELS if not guard(m, s, inv))
    return {"models REFUTED by P1 under each reading (exception closed to none)": out}


def selftest():
    bad_frame = dict(FRAME, VALIDATE=set())
    checks = [("frame axiom holds for M0", frame_violations() == []),
              ("mutation: shrinking Frame(VALIDATE) is detected", frame_violations(bad_frame) != []),
              ("RAISE blocked while frozen", not any(guard(("G-R", False), dict(INIT, standing="candidate", regime="frozen"), i) for i in INVS if i["op"] == "RAISE")),
              ("REOPEN needs contra", not guard(("G-R", False), dict(INIT, regime="frozen"), dict(INVS[0], op="REOPEN", auth="named"))),
              ("force-sensitive lifts bar at AMB", guard(("G-O", True), dict(INIT, standing="candidate"), dict(op="RAISE", route="RULING", auth="named", effect="epistemic", exc=False, force="AMB"))),
              ("force-insensitive keeps bar at AMB", not guard(("G-O", False), dict(INIT, standing="candidate"), dict(op="RAISE", route="RULING", auth="named", effect="epistemic", exc=False, force="AMB")))]
    for n, ok in checks: print(("ok   " if ok else "FAIL ") + n)
    return all(ok for _, ok in checks)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    rep = {"labels": "MODEL-DERIVED; development data only; no EMPIRICAL-RESULT"}
    rep["P-frame violations"] = frame_violations()
    rep["P-frame-fit L205-A1 (FREEZE frame)"] = freeze_alternative()
    reach = {}
    for m in MODELS:
        seen = reachable(m); ks = [dict(zip(C, k)) for k in seen]
        def first(pred):
            for k in seen:
                if pred(dict(zip(C, k))): return path(seen, k)
            return None
        reach[mname(m)] = {"reachable_states": len(seen),
                           "adopted & not validated": first(lambda s: s["standing"] == "adopted" and s["validation"] == "none"),
                           "governance closed & execution open": first(lambda s: s["governance"] == "closed" and s["execution"] == "open"),
                           "execution closed (item without close transition)": first(lambda s: s["execution"] == "closed"),
                           "LAPSE enabled from a reachable adopted state (reversibility)": any(s["standing"] == "adopted" and any(
                               inv["op"] == "LAPSE" and guard(m, s, inv) for inv in INVS) for s in ks),
                           "REOPEN enabled from a reachable frozen state without contra": any(s["regime"] == "frozen" and s["contra"] == "no" and any(
                               inv["op"] == "REOPEN" and guard(m, s, inv) for inv in INVS) for s in ks)}
    rep["P-reach / P-planes / P-rev"] = reach
    rep["P-gate-invariance (L205-A3)"] = gate_invariance()
    rep["P-markov (R-90 retired identifiers)"] = markov_retired_ids()
    rep["P-dev open world"] = dev_consistency(False)
    rep["P-dev closed-world sensitivity (unrecorded exception = none)"] = dev_consistency(True)
    rep["P-dev P1 reading-conditional"] = p1_conditional()
    rep["P-dist"] = distinguishing()
    print(json.dumps(rep, indent=1, ensure_ascii=False, default=str))
