"""T-min v1 check (candidate theory T-MIN-V1-CANDIDATE-THEORY.md, a7e04491..., frozen b2f1d5d76). Deterministic.
Does v1's legality layer reproduce every coded outcome? PERFORMED ⇒ Legal · RULE-refused ⇒ ¬Legal · CHOICE-refused ⇒ Legal.
Open world: NR/UNK fields range over their domain. Per event: EXPLAINED (all completions agree) · OPEN (some agree) · UNEXPLAINED (none).
The evidence bar is evaluated for the variants G-R, G-K, G-O (G-E needs an effect field that is not coded → UNDETERMINED).
Also exports the event-level dataset (1z). Usage: python3 tminv1_check.py [--selftest]"""
import importlib.util
import itertools
import json
import os
import sys

_d = os.path.dirname(os.path.abspath(__file__))
_s = importlib.util.spec_from_file_location("t2", os.path.join(_d, "tmin2_check.py")); t2 = importlib.util.module_from_spec(_s); _s.loader.exec_module(t2)

CONFORMANCE = {"ADOPT-by-Authority(R-91)": "collapsed"}                       # (fitted) all other ADOPTs 'ok'
STRUCTURAL_RULE = {"SUPERSEDE(D-12) by ADR-MP"}                               # 'Rule honored: one architectural question per ADR'
DOM = {"eps": ["0", "1", "2+"], "x": ["none", "yes"], "r": ["RULING", "PROMOTION"], "o": ["RAISE", "CREATE-NORM"], "a": ["DECISION-AUTHORITY", "OTHER"]}


def legal(e, variant):
    o, s = e["o"], e["sigma"]
    if o == "ADOPT": return e["a"] == "DECISION-AUTHORITY" and s in ("PREPARED", "HELD") and CONFORMANCE.get(e["id"], "ok") == "ok"
    if o == "REGISTER": return e["k"] == "constitutional-decision"
    if o == "START": return "delivery questions" in s                          # proviso discharged (INTERPRETATION, F-LOG-0129)
    if o == "AUTHORIZE": return s != "predecessor not accepted"
    if o == "RAISE":
        if s == "frozen": return False
        bar = {"G-R": e["r"] == "PROMOTION", "G-K": e["k"] == "observation", "G-O": True}[variant]
        return (not bar) or e["eps"] == "2+" or e["x"] == "yes"
    if o == "SUPERSEDE": return e["eps"] == "2+" or e["id"] in STRUCTURAL_RULE
    if o == "ACCEPT": return s == "executed" and e["eps"] == "2+"
    if o == "CREATE-NORM": return e["a"] not in ("NR", "UNK")
    if o in ("ANNOTATE",): return True
    if o == "CORRECT-TEXT": return s.startswith("window-open")
    raise KeyError(o)


def expected(e):
    return True if e["out"] == "PERFORMED" else (False if e.get("ground") == "RULE" else True)


def completions(e):
    keys = [k for k in DOM if e.get(k) in ("NR", "UNK")]
    for vals in itertools.product(*[DOM[k] for k in keys]):
        c = dict(e); c.update(zip(keys, vals)); yield c


def check(events, variant):
    res = {}
    for e in events:
        oks = [legal(c, variant) == expected(e) for c in completions(e)]
        res[e["id"]] = "EXPLAINED" if all(oks) else ("OPEN" if any(oks) else "UNEXPLAINED")
    return res


REGIME = {  # (regime, source family) per event, for the event-level dataset
 "L493-A": ("post-template 08-15", "session-log"), "L493-B": ("post-template 08-15", "session-log"),
 "P1": ("pre-template human authority", "register"), "P2": ("08-16", "standards document"), "R-39": ("pre-template human authority", "register"),
 "ADOPT-by-Chief(R-81..85)": ("template (Chief-issued)", "register; 5 rows carry one pasted annotation → ONE event"),
 "ADOPT-by-Authority(R-86)": ("template (Chief-issued)", "register"), "ADOPT-by-Authority(R-91)": ("template (Chief-issued)", "register"),
 "START(WP-4B)": ("template era", "git history"), "START(WP-4B)@R-72": ("pre-template human authority", "register"), "START(WP-8)": ("pre-template human authority", "register"),
 "AUTHORIZE(4C)@R-72": ("pre-template human authority", "register"), "AUTHORIZE(4C-1)@R-89": ("template (Chief-issued)", "register"),
 "RAISE under freeze (L205)": ("08-04", "session-log"), "REGISTER(R-90 operational acceptance)": ("08-04", "session-log"),
 "REGISTER(constitutional decision)": ("all", "register (rule)"), "SUPERSEDE(ADR-T14) (R-77)": ("pre-template human authority", "register"),
 "SUPERSEDE(§12) (R-83)": ("template (Chief-issued)", "register"), "SUPERSEDE(PB-006 row) (R-94)": ("post-template Chief acts", "register"),
 "ANNOTATE(PB-006 row) (R-94)": ("post-template Chief acts", "register"), "ACCEPT(WP-4B) (R-87)": ("template era", "register"),
 "ACCEPT(WP-6) (R-43)": ("pre-template human authority", "register"), "CORRECT-TEXT in window (R-96)": ("post-template Chief acts", "git history of the register"),
 "SUPERSEDE(D-12) by ADR-MP": ("07-07", "ADR file")}


def selftest():
    e = dict(id="t", o="RAISE", r="PROMOTION", a="X", k="observation", sigma="normal", eps="1", x="none", out="REFUSED", ground="RULE")
    ok = [("L493-B-like refusal explained under G-R", check([e], "G-R")["t"] == "EXPLAINED"),
          ("performed low-evidence promotion is UNEXPLAINED under G-R", check([dict(e, out="PERFORMED")], "G-R")["t"] == "UNEXPLAINED"),
          ("NR evidence makes it OPEN", check([dict(e, out="PERFORMED", eps="NR")], "G-R")["t"] == "OPEN")]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    ev, _, _ = t2.split(t2.t.EV)
    out = {"labels": "MODEL-DERIVED; development events (v1 was built from them, so EXPLAINED is consistency, not validation)", "events": len(ev)}
    for v in ("G-R", "G-K", "G-O"):
        r = check(ev, v)
        out[v] = {"counts": {k: sum(1 for x in r.values() if x == k) for k in ("EXPLAINED", "OPEN", "UNEXPLAINED")},
                  "unexplained": [k for k, x in r.items() if x == "UNEXPLAINED"], "open": [k for k, x in r.items() if x == "OPEN"]}
    out["G-E"] = "UNDETERMINED (no effect field coded)"
    out["markov"] = [{"pair": m["pair"], "futures_equal": m["futures_equal"], "finite_fix_in_v1": True} for m in t2.t.MARKOV]
    out["dataset"] = [{"event_id": e["id"], **{k: e[k] for k in ("o", "r", "a", "k", "sigma", "eps", "x")}, "conformance": CONFORMANCE.get(e["id"], "ok" if e["o"] == "ADOPT" else "n/a"),
                       "outcome": e["out"], "ground": e.get("ground", "n/a"), "regime": REGIME[e["id"]][0], "source_family": REGIME[e["id"]][1], "ref": e["ref"]} for e in ev]
    print(json.dumps(out, indent=1, ensure_ascii=False))
