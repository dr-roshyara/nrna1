"""T-min with the legality/choice split (F-LOG-0130 refinement). tmin_check.py is imported read-only and left unchanged.
RULE  = the refusal cites a rule that forbids the act (a legality datum).
CHOICE = the act was one of several options and was not chosen, on a stated principle (a choice datum, not legality).
Two analyses: LEGALITY uses PERFORMED + RULE refusals; CHOICE uses PERFORMED + CHOICE refusals. Grounds are coded from the
quotes recorded in each event's ref (F-LOG-0115..0131). Frozen by commit before the first run. Usage: python3 tmin2_check.py"""
import copy
import importlib.util
import json
import os

_s = importlib.util.spec_from_file_location("t", os.path.join(os.path.dirname(os.path.abspath(__file__)), "tmin_check.py"))
t = importlib.util.module_from_spec(_s); _s.loader.exec_module(t)

GROUND = {
 "L493-B": ("RULE", "'ES-006.1 forbids promoting from one'"),
 "ADOPT-by-Chief(R-81..85)": ("RULE", "'the one reading no self-issued act may adopt'"),
 "ADOPT-by-Authority(R-91)": ("RULE", "'Event D SEPARATES evidence submission from constitutional review'"),
 "START(WP-4B)@R-72": ("RULE", "'SO IMPLEMENTATION DOES NOT BEGIN' (proviso unmet)"),
 "START(WP-8)": ("RULE", "'NOT PERMITTED: any implementation work for WP-8'"),
 "AUTHORIZE(4C)@R-72": ("CHOICE", "'GROUNDS FOR THE SLICE-AT-A-TIME FORM' — a principled choice by the authorizing body"),
 "RAISE under freeze (L205)": ("RULE", "the freeze: 'no new meta-principles … minted from here'"),
 "REGISTER(R-90 operational acceptance)": ("RULE", "'the register holds constitutional decisions'"),
 "SUPERSEDE(ADR-T14) (R-77)": ("RULE", "'NOT SUFFICIENT TO SUPERSEDE AN ACCEPTED ADR' (an evidence standard)"),
 "SUPERSEDE(§12) (R-83)": ("RULE", "'only by SUPERSEDING §12 — and no evidence was presented for superseding it'"),
 "SUPERSEDE(PB-006 row) (R-94)": ("CHOICE", "retain · annotate · supersede → 'superseding would blur chronology'"),
}
EXTRA = [dict(id="SUPERSEDE(D-12) by ADR-MP", o="SUPERSEDE", r="ADR-ACCEPTANCE", a="NR", k="decision-log-entry", sigma="normal", eps="NR", x="none",
              out="PERFORMED", ref="ADR-MP status 'supersedes … D-12 (now a pointer)'; 'Rule honored: one architectural question per ADR' (F-LOG-0132)")]


def split(events):
    ev = copy.deepcopy(events) + EXTRA
    for e in ev:
        if e["out"] == "REFUSED": e["ground"] = GROUND[e["id"]][0]
    legality = [e for e in ev if e["out"] == "PERFORMED" or e.get("ground") == "RULE"]
    choice = [e for e in ev if e["out"] == "PERFORMED" or e.get("ground") == "CHOICE"]
    return ev, legality, choice


if __name__ == "__main__":
    ev, leg, ch = split(t.EV)
    out = {"labels": "MODEL-DERIVED; selected coded events; UNDETERMINED != redundant",
           "events": len(ev), "refusals": {g: sum(1 for e in ev if e.get("ground") == g) for g in ("RULE", "CHOICE")}}
    for name, s in (("LEGALITY", leg), ("CHOICE", ch)):
        nec, ins = t.minimal_pairs(s)
        out[name] = {"events": len(s), "variable_necessity": {v: ({"NECESSARY": p} if p else "UNDETERMINED") for v, p in nec.items()}, "sufficiency_counterexamples": ins}
    out["grounds"] = GROUND
    print(json.dumps(out, indent=1, ensure_ascii=False))
