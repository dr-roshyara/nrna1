"""Semantic-observation dataset v1 + strict/possible minimal-pair witnesses + an information-gain ranking of the next observation.
Schema: SEMOBS/SCHEMA.md (frozen with this file). Data re-express events ALREADY READ (F-LOG-0115…0140); no corpus is read here.
UNK = not established by the source (never filled in). n/a = not applicable to the operation (schema §3). 'same:<obj>' = equal by identity.
Usage: python3 semobs.py [--selftest]"""
import itertools
import json
import sys

APPL = {"ADOPT": "akstc", "AUTHORIZE-IMPL": "akst", "AUTHORIZE-PLAN": "akst", "REGISTER": "ak", "OPEN-WORK": "akt", "START": "akst",
        "RAISE": "akstex", "ASSIGN-ID": "ksh", "SUPERSEDE": "akex", "ANNOTATE": "ak"}
VARS = "oraksteхhc".replace("х", "x")      # o r a k s t e x h c
U = "UNK"


def E(i, cl, genre, act, out, ground, **f):
    d = {v: U for v in VARS}; d.update(f); d.update(event_id=i, cluster=cl, genre=genre, speech_act=act, outcome=out, ground=ground); return d


OBS = [
 E("ADOPT R-81..85 by the Chief (declined)", "R-81..85-annotation", "register-row", "performed", "REFUSED", "RULE", o="ADOPT", r="RULING", a="ARB-CHIEF", k="same:R-81..85", s="PREPARED", t="same:R-81..85", c="same:R-81..85"),
 E("ADOPT R-81..85 by the DA (R-86)", "R-86", "register-row", "performed", "PERFORMED", "n/a", o="ADOPT", r="RULING", a="DECISION-AUTHORITY", k="same:R-81..85", s="PREPARED", t="same:R-81..85", c="same:R-81..85"),
 E("ADOPT R-91 by the DA (held)", "R-91", "register-row", "performed", "REFUSED", "RULE", o="ADOPT", r="RULING", a="DECISION-AUTHORITY", k="ruling", s="PREPARED", t="R-91", c="collapsed"),
 E("AUTHORIZE-IMPL R-70 (ARB)", "R-70", "register-row", "performed", "PERFORMED", "n/a", o="AUTHORIZE-IMPL", r="RULING", a="ARB", k="work", s="guards-met", t="own-slice"),
 E("AUTHORIZE-IMPL R-89 (Chief, PREPARED)", "R-89", "register-row", "performed", "NOT-IN-FORCE", "RULE", o="AUTHORIZE-IMPL", r="RULING", a="ARB-CHIEF", k="work", s="guards-met", t="own-slice"),
 E("AUTHORIZE-PLAN R-95 (Chief)", "R-95", "register-row", "performed", "PERFORMED", "n/a", o="AUTHORIZE-PLAN", r="RULING", a="ARB-CHIEF", k="work", s="guards-met", t="own-slice"),
 E("REGISTER operational acceptance (R-90)", "S0804-L576", "session-log", "reported", "REFUSED", "RULE", o="REGISTER", r="RULING", a="AUTHORITY", k="operational-acceptance"),
 E("REGISTER constitutional decision (rule)", "S0804-L576", "session-log", "reported", "PERFORMED", "n/a", o="REGISTER", r="RULING", a="AUTHORITY", k="constitutional-decision"),
 E("OPEN-WORK by a recording note (R-60)", "R-60", "register-row", "performed", "REFUSED", "RULE", o="OPEN-WORK", r="RULING", a="ARB", k="recording-note", t="WP-7B-R1"),
 E("OPEN-WORK by a ruling (R-60 refiled)", "R-60", "register-row", "performed", "PERFORMED", "n/a", o="OPEN-WORK", r="RULING", a="ARB", k="ruling", t="WP-7B-R1"),
 E("START WP-4B at R-72 (proviso unmet)", "R-72", "register-row", "performed", "REFUSED", "RULE", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-proviso-unmet", t="WP-4B"),
 E("START WP-4B 08-03 16:10", "git-6a67da5d7", "git", "performed", "PERFORMED", "n/a", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-full", t="WP-4B"),
 E("START WP-8 (permission only)", "R-79", "register-row", "performed", "REFUSED", "RULE", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="permission-only", t="WP-8"),
 E("START 7A after AUTHORIZE(7A)", "R-47", "register-row", "performed", "PERFORMED", "n/a", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-full(7A)", t="7A"),
 E("START 7B after AUTHORIZE(7A)", "R-47", "register-row", "performed", "REFUSED", "RULE", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-full(7A)", t="7B"),
 *[E(f"RAISE promoted #{n}", "R-36", "plan", "performed", "PERFORMED", "n/a", o="RAISE", r="RULING", a="ARB", k="engineering-behaviour", s="normal", t="AST-013", e="2+", x="none-stated") for n in (2, 3, 9, 10)],
 *[E(f"RAISE not promoted #{n}", "R-36", "plan", "performed", "REFUSED", "RULE", o="RAISE", r="RULING", a="ARB", k="engineering-behaviour", s="normal", t="AST-013", e="1", x="none-stated") for n in (5, 14, 17)],
 E("RAISE L493-B (single occurrence)", "S0815-L483", "session-log", "reported", "REFUSED", "RULE", o="RAISE", r="PROMOTION", a="PO/ARB", k="observation", s="normal", t="methodology", e="1"),
 E("RAISE P1 / R-41 (by PA instruction)", "R-41", "register-row", "performed", "PERFORMED", "n/a", o="RAISE", r="RULING", a="PA", k=U, s="normal", t="ES-004.3", e="1"),
 E("ASSIGN a never-used number", "register-numbering", "register-row", "performed", "PERFORMED", "n/a", o="ASSIGN-ID", k="identifier", s="no-holder", h="unused"),
 E("ASSIGN the retired number R-90", "S0804-L576", "session-log", "reported", "REFUSED", "RULE", o="ASSIGN-ID", k="identifier", s="no-holder", h="retired"),
 E("SUPERSEDE ADR-T14 (R-77)", "R-77", "register-row", "performed", "REFUSED", "RULE", o="SUPERSEDE", r="RULING", a="ARB", k="ADR", e="insufficient", x=U),
 E("SUPERSEDE §12 (R-83)", "R-83", "register-row", "performed", "REFUSED", "RULE", o="SUPERSEDE", r="RULING", a="ARB-CHIEF", k="design-rule", e="0", x=U),
 E("SUPERSEDE D-12 by ADR-MP", "ADR-MP", "ADR", "performed", "PERFORMED", "n/a", o="SUPERSEDE", r="ADR-ACCEPTANCE", a=U, k="decision-log-entry", e=U, x=U),
 E("SUPERSEDE PB-006 row declined (R-94)", "R-94", "register-row", "performed", "REFUSED", "CHOICE", o="SUPERSEDE", r="RULING", a="ARB-CHIEF", k="acceptance-record", e="2+", x=U),
 E("ANNOTATE PB-006 row chosen (R-94)", "R-94", "register-row", "performed", "PERFORMED", "n/a", o="ANNOTATE", r="RULING", a="ARB-CHIEF", k="acceptance-record"),
]


def outcome_class(ev): return "OK" if ev["outcome"] == "PERFORMED" else "NO"


def fields(ev): return set("or") | set(APPL.get(ev["o"], ""))


def pairs(obs):
    """Strict and possible single-field witnesses (schema R1–R3)."""
    strict, possible = {v: [] for v in VARS}, {v: [] for v in VARS}
    L = [e for e in obs if e["ground"] != "CHOICE"]
    for p, q in itertools.combinations(L, 2):
        if outcome_class(p) == outcome_class(q) or p["o"] != q["o"]: continue
        F = fields(p)
        diff = [v for v in F if p[v] != q[v] and U not in (p[v], q[v])]
        unk = [v for v in F if U in (p[v], q[v])]
        if len(diff) == 1:
            (strict if not unk else possible)[diff[0]].append((p["event_id"], q["event_id"], p["cluster"], q["cluster"]))
    # the operation itself: compare across operations with equal applicable-field values (o is always 'known')
    for p, q in itertools.combinations(L, 2):
        if outcome_class(p) == outcome_class(q) or p["o"] == q["o"]: continue
        F = (fields(p) & fields(q)) - {"o"}
        if all(p[v] == q[v] and p[v] != U for v in F) and F:
            strict["o"].append((p["event_id"], q["event_id"], p["cluster"], q["cluster"]))
    return strict, possible


def verdicts(strict, possible):
    out = {}
    for v in VARS:
        ind = {tuple(sorted({a, b})) for _, _, a, b in strict[v]}
        n = len(ind)
        out[v] = {"strict_witnesses": len(strict[v]), "independent_strict": n, "independent_clusters": sorted(map(list, ind)),
                  "possible_witnesses": len(possible[v]),
                  "verdict": "EMPIRICALLY SUPPORTED" if n >= 2 else ("WEAK" if n == 1 else ("NOT DEMONSTRATED (possible)" if possible[v] else "NOT DEMONSTRATED"))}
    return out


NAMES = {"o": "operation", "r": "route", "a": "authority", "k": "kind", "s": "state", "t": "target", "e": "evidence", "x": "exception", "h": "history", "c": "conformance"}
TEMPLATES = {  # what a distinguishing observation must contain, and where the register/logs could hold it (heuristic, no reading)
 "r": ("the same operation by the same authority on the same kind, via two routes, opposite outcomes", "not in this corpus (route ≡ host family); needs a designed record"),
 "c": ("two adoptions by the Decision Authority of rulings of the same kind, one role-collapsed, one conformant, with the conformance of both stated", "register rows with 'HELD' / 'not eligible' / 'self-certify' + a paired adoption"),
 "h": ("two acts with equal current state and different histories, opposite outcomes (a reuse / retirement / reopening case)", "register rows 'WITHDRAWN' / 'RETIRED' / 'reopen' / 'recycled'"),
 "e": ("the same operation, authority and kind in a DIFFERENT decision cluster from R-36, differing only in the evidence count", "session-log promotion dispositions; 'promotion criterion' / 'third instance' lines"),
 "x": ("the same barred act with and without a recorded exception, opposite outcomes", "rows citing 'exception' (R-39 family)"),
 "o": ("the same authority, kind and state, two operations, opposite outcomes, with the rule stated", "Chief acts: implementation vs planning authorization (R-89 / R-95 family)")}


def selftest():
    a = E("p", "c1", "g", "performed", "PERFORMED", "n/a", o="START", r="X", a="A", k="K", s="S1", t="T")
    b = E("q", "c2", "g", "performed", "REFUSED", "RULE", o="START", r="X", a="A", k="K", s="S2", t="T")
    c = dict(b, event_id="u", t=U)
    st, po = pairs([a, b, c])
    ok = [("strict single-field witness found", any(x[0] == "p" and x[1] == "q" for x in st["s"])),
          ("UNK makes it only possible", any("u" in x for x in po["s"]) and not any("u" in x for x in st["s"])),
          ("choice refusals excluded", all(e["ground"] != "CHOICE" for e in OBS if any(e["event_id"] in w[:2] for v in pairs(OBS)[0].values() for w in v)))]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    st, po = pairs(OBS); V = verdicts(st, po)
    weak = sorted(V, key=lambda v: (V[v]["independent_strict"], v))
    rank = [{"variable": NAMES[v], "independent_strict": V[v]["independent_strict"], "needed_observation": TEMPLATES.get(v, ("—", "—"))[0], "where": TEMPLATES.get(v, ("—", "—"))[1]}
            for v in weak if V[v]["independent_strict"] < 2]
    print(json.dumps({"labels": "SEMANTIC OBSERVATIONS re-expressed from already-read sources; results are EMPIRICAL-WITNESS counts under schema v1, not theory",
                      "n_observations": len(OBS), "n_clusters": len({e["cluster"] for e in OBS}),
                      "genres": sorted({e["genre"] for e in OBS}), "verdicts": {NAMES[v]: V[v] for v in VARS},
                      "next_observation_ranking (fewest independent witnesses first)": rank}, indent=1, ensure_ascii=False))
