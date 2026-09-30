"""T-min tests (pre-registration prompts/KNOWLEDGEOS-T-MIN-PREREGISTRATION.md, frozen fa1ccea9e). Deterministic.
Events are coded from SOURCE facts already recorded in F-LOG-0102..0128 (refs per event); 'UNK' never matches in a pair.
Usage: python3 tmin_check.py [--selftest]"""
import itertools
import json
import sys

VARS = ("o", "r", "a", "k", "sigma", "eps", "x")
U = "UNK"
EV = [
 dict(id="L493-A", o="CREATE-NORM", r="RULING", a="PO/ARB", k="rule", sigma="normal", eps="NR", x="NR", out="PERFORMED", ref="F-LOG-0115"),
 dict(id="L493-B", o="RAISE", r="PROMOTION", a="PO/ARB", k="observation", sigma="normal", eps="1", x="none", out="REFUSED", ref="F-LOG-0115"),
 dict(id="P1", o=U, r="RULING", a="PA", k="rule", sigma="normal", eps="1", x="NR", out="PERFORMED", ref="F-LOG-0102/0103 (op reading RAISE|CREATE-NORM)"),
 dict(id="P2", o="RAISE", r=U, a="ARB", k="rule", sigma="normal", eps="2+", x="NR", out="PERFORMED", ref="F-LOG-0106"),
 dict(id="R-39", o="RAISE", r="RULING", a="DA", k="principle", sigma="normal", eps="NR", x="yes", out="PERFORMED", ref="F-LOG-0103"),
 dict(id="ADOPT-by-Chief(R-81..85)", o="ADOPT", r="RULING", a="ARB-CHIEF", k="ruling", sigma="PREPARED", eps="n/a", x="none", out="REFUSED", ref="R-81..R-85 annotation: 'The Chief declines to resolve an ambiguity about the Chief's own authority in the Chief's own favour'"),
 dict(id="ADOPT-by-Authority(R-86)", o="ADOPT", r="RULING", a="DECISION-AUTHORITY", k="ruling", sigma="PREPARED", eps="n/a", x="none", out="PERFORMED", ref="R-86"),
 dict(id="ADOPT-by-Authority(R-91)", o="ADOPT", r="RULING", a="DECISION-AUTHORITY", k="ruling", sigma="PREPARED", eps="n/a", x="none", out="REFUSED", ref="R-91 HELD: 'collapsed evidence submission and constitutional review'"),
 dict(id="START(WP-4B)", o="START", r="EXECUTION", a="ENGINEERING", k="work", sigma="auth-in-principle; governance questions declared delivery questions (R-78)", eps="n/a", x="none", out="PERFORMED", ref="git 6a67da5d7 08-03 16:10 (F-LOG-0128)"),
 dict(id="START(WP-4B)@R-72", o="START", r="EXECUTION", a="ENGINEERING", k="work", sigma="auth-in-principle; proviso unmet", eps="n/a", x="none", out="REFUSED", ref="R-72 'SO IMPLEMENTATION DOES NOT BEGIN'"),
 dict(id="START(WP-8)", o="START", r="EXECUTION", a="ENGINEERING", k="work", sigma="permission only", eps="n/a", x="none", out="REFUSED", ref="R-79 'NOT PERMITTED: any implementation work for WP-8'"),
 dict(id="AUTHORIZE(4C)@R-72", o="AUTHORIZE", r="RULING", a="ARB", k="work", sigma="predecessor not accepted", eps="n/a", x="none", out="REFUSED", ref="R-72 'WP-4C AND WP-4D ARE EXPRESSLY NOT AUTHORIZED'"),
 dict(id="AUTHORIZE(4C-1)@R-89", o="AUTHORIZE", r="RULING", a="ARB-CHIEF", k="work", sigma="predecessor accepted", eps="n/a", x="none", out="PERFORMED", ref="R-89 (PREPARED)"),
 dict(id="RAISE under freeze (L205)", o="RAISE", r=U, a=U, k="principle", sigma="frozen", eps="NR", x="none", out="REFUSED", ref="L205 'no new meta-principles … minted from here'"),
 dict(id="REGISTER(R-90 operational acceptance)", o="REGISTER", r="RULING", a="AUTHORITY", k="operational-acceptance", sigma="normal", eps="n/a", x="none", out="REFUSED", ref="2026-08-04 L576 section: withdrawn, 'the register holds constitutional decisions'"),
 dict(id="REGISTER(constitutional decision)", o="REGISTER", r="RULING", a="AUTHORITY", k="constitutional-decision", sigma="normal", eps="n/a", x="none", out="PERFORMED", ref="same source (the register's admissibility rule)"),
 dict(id="SUPERSEDE(ADR-T14) (R-77)", o="SUPERSEDE", r="RULING", a="ARB", k="ADR", sigma="normal", eps="insufficient", x="none", out="REFUSED", ref="R-77 'NOT SUFFICIENT TO SUPERSEDE AN ACCEPTED ADR'"),
 dict(id="SUPERSEDE(§12) (R-83)", o="SUPERSEDE", r="RULING", a="ARB-CHIEF", k="design-rule", sigma="normal", eps="0", x="none", out="REFUSED", ref="R-83 'no evidence was presented for superseding it'"),
 dict(id="ACCEPT(WP-4B) (R-87)", o="ACCEPT", r="RULING", a="ACCEPTING-AUTHORITY", k="work", sigma="executed", eps="2+", x="none", out="PERFORMED", ref="R-87"),
 dict(id="ACCEPT(WP-6) (R-43)", o="ACCEPT", r="RULING", a="ARB", k="work", sigma="executed", eps="2+", x="none", out="PERFORMED", ref="R-43"),
 dict(id="SUPERSEDE(PB-006 row) (R-94)", o="SUPERSEDE", r="RULING", a="ARB-CHIEF", k="acceptance-record", sigma="normal", eps="2+", x="none", out="REFUSED", ref="R-94 'superseding would blur chronology' (added 1y, F-LOG-0130)"),
 dict(id="ANNOTATE(PB-006 row) (R-94)", o="ANNOTATE", r="RULING", a="ARB-CHIEF", k="acceptance-record", sigma="normal", eps="2+", x="none", out="PERFORMED", ref="R-94 'ANNOTATE is adopted … preserves both truths' (added 1y)"),
 dict(id="CORRECT-TEXT in window (R-96)", o="CORRECT-TEXT", r="RULING", a="ARB-CHIEF", k="ruling", sigma="window-open, uncited", eps="n/a", x="none", out="PERFORMED", ref="F-LOG-0127"),
]


def known(v): return v not in (U, "NR")


def minimal_pairs(events):
    """x NECESSARY iff a pair agrees on all other variables (known, equal), differs in x (both known) and has different outcomes.
    A pair that agrees on ALL variables yet differs in outcome is a SUFFICIENCY counterexample (a variable is missing)."""
    nec = {v: [] for v in VARS}; insuff = []
    for e, f in itertools.combinations(events, 2):
        if e["out"] == f["out"]: continue
        diff = [v for v in VARS if e[v] != f[v]]
        if any(not known(e[v]) or not known(f[v]) for v in VARS): continue
        if len(diff) == 1: nec[diff[0]].append((e["id"], f["id"]))
        elif len(diff) == 0: insuff.append((e["id"], f["id"]))
    return nec, insuff


MARKOV = [
 {"pair": ["R-90 (withdrawn)", "R-91 (held)"], "apparent_state": "not adopted", "futures_equal": False,
  "finite_fix": "status ∈ {PREPARED, ADOPTED, HELD, WITHDRAWN} + registry {unused, used, retired}", "ref": "F-LOG-0122, 0123"},
 {"pair": ["WP-4B at R-72 (08-02)", "WP-4B at 16:10 (08-03)"], "apparent_state": "authorization = in principle (conditional)", "futures_equal": False,
  "finite_fix": "authorization carries its proviso state (unmet / discharged); the proviso is R-72's own ('no unresolved governance blockers'), not R-79's Board acts",
  "ref": "R-72, R-78, git (F-LOG-0128), this entry"},
 {"pair": ["a Chief ruling before R-86", "a Chief ruling after R-86"], "apparent_state": "issued by Chief", "futures_equal": True,
  "finite_fix": "none needed: adoption creates no delegation (R-86/R-87/R-89 explicit)", "ref": "F-LOG-0123"},
]


def selftest():
    e = [dict(id="a", o="X", r="R", a="A", k="K", sigma="s", eps="1", x="none", out="PERFORMED"),
         dict(id="b", o="X", r="P", a="A", k="K", sigma="s", eps="1", x="none", out="REFUSED"),
         dict(id="c", o="X", r=U, a="A", k="K", sigma="s", eps="1", x="none", out="REFUSED"),
         dict(id="d", o="X", r="R", a="A", k="K", sigma="s", eps="1", x="none", out="REFUSED")]
    n, i = minimal_pairs(e)
    ok = [("single-variable pair found", ("a", "b") in n["r"]), ("UNK never forms a pair", all("c" not in p for p in n["r"])),
          ("all-equal, different outcome -> sufficiency counterexample", ("a", "d") in i)]
    for m, g in ok: print(("ok   " if g else "FAIL ") + m)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    nec, insuff = minimal_pairs(EV)
    print(json.dumps({"labels": "MODEL-DERIVED from coded development events (selected, not a sample); UNDETERMINED != redundant",
                      "events": len(EV),
                      "variable_necessity": {v: ({"NECESSARY": p} if p else "UNDETERMINED") for v, p in nec.items()},
                      "sufficiency_counterexamples": insuff, "markov": MARKOV}, indent=1, ensure_ascii=False))
