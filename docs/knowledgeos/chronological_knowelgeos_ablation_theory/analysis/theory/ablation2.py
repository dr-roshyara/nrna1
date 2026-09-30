"""Non-circular ablation (F-series). No 'observation X requires component Y' statement exists anywhere in this file.
Data = coded events (raw field values + outcome + ground), taken from source facts already recorded (F-LOG-0115…0140; refs per event).
Semantics (frozen with this file, committed before its first run):
  * LEGALITY is deterministic: a model M (a set of retained fields) REPRESENTS the legality data iff no two events with a
    RULE-grounded outcome or PERFORMED have identical values on M's fields and different outcomes. 'NR'/'UNK' never match.
  * CHOICE may be nondeterministic: a CHOICE-declined option is legal. A guard-only model represents it as 'both legal', so
    choice never breaks representability; it only reduces what is EXPLAINED (the stated principle is not entailed).
  * An admissible reinterpretation may only merge values that the source itself equates; none are declared, so none are applied.
  * Complexity(M) = |fields| + special_cases, where special_cases = the number of conflicting pair-classes M must carve out as
    row-level exceptions to become representable. Score_λ(M) = special_cases + λ·|fields|. A field X is worth keeping at λ
    iff removing it adds more than λ special cases.
Fields: o operation · r route · a authority · k object kind · s state (current, incl. the proviso state) · t target/scope ·
e evidence (independent slices/instances) · x exception · h history summary (registry) · c conformance (role separation).
Usage: python3 ablation2.py [--selftest]"""
import itertools
import json
import sys

F = ("o", "r", "a", "k", "s", "t", "e", "x", "h", "c")


def ev(i, out, ground, ref, **kw):
    d = {f: "NR" for f in F}; d.update(kw); d.update(id=i, out=out, ground=ground, ref=ref); return d


EV = [
 # authority
 ev("ADOPT by Chief (R-81..85)", "REFUSED", "RULE", "'no self-issued act may adopt'", o="ADOPT", r="RULING", a="ARB-CHIEF", k="ruling", s="PREPARED", t="self", e="n/a", x="none", h="used", c="ok"),
 ev("ADOPT by DA (R-86)", "PERFORMED", "-", "R-86", o="ADOPT", r="RULING", a="DECISION-AUTHORITY", k="ruling", s="PREPARED", t="self", e="n/a", x="none", h="used", c="ok"),
 ev("ADOPT by DA (R-91)", "REFUSED", "RULE", "R-91 HELD: collapsed evidence submission and review", o="ADOPT", r="RULING", a="DECISION-AUTHORITY", k="ruling", s="PREPARED", t="self", e="n/a", x="none", h="used", c="collapsed"),
 ev("AUTHORIZE-IMPL in force (R-70, ARB)", "PERFORMED", "-", "R-70", o="AUTHORIZE-IMPL", r="RULING", a="ARB", k="work", s="guards-met", t="own-slice", e="n/a", x="none", h="n/a", c="ok"),
 ev("AUTHORIZE-IMPL not in force (R-89, Chief, PREPARED)", "REFUSED", "RULE", "R-89 'PREPARED, NOT ADOPTED … awaits the Decision Authority'", o="AUTHORIZE-IMPL", r="RULING", a="ARB-CHIEF", k="work", s="guards-met", t="own-slice", e="n/a", x="none", h="n/a", c="ok"),
 ev("AUTHORIZE-PLAN in force (R-95, Chief)", "PERFORMED", "-", "R-95 (no PREPARED marker)", o="AUTHORIZE-PLAN", r="RULING", a="ARB-CHIEF", k="work", s="guards-met", t="own-slice", e="n/a", x="none", h="n/a", c="ok"),
 # kind
 ev("REGISTER operational acceptance (R-90)", "REFUSED", "RULE", "'the register holds constitutional decisions'", o="REGISTER", r="RULING", a="AUTHORITY", k="operational-acceptance", s="normal", t="self", e="n/a", x="none", h="n/a", c="ok"),
 ev("REGISTER constitutional decision", "PERFORMED", "-", "the register's admissibility rule", o="REGISTER", r="RULING", a="AUTHORITY", k="constitutional-decision", s="normal", t="self", e="n/a", x="none", h="n/a", c="ok"),
 ev("OPEN-WORK by recording note (R-60)", "REFUSED", "RULE", "'A recording note cannot open a work package'", o="OPEN-WORK", r="RULING", a="ARB", k="recording-note", s="normal", t="WP", e="n/a", x="none", h="n/a", c="ok"),
 ev("OPEN-WORK by ruling (R-60 refiled / R-52)", "PERFORMED", "-", "R-60, R-52", o="OPEN-WORK", r="RULING", a="ARB", k="ruling", s="normal", t="WP", e="n/a", x="none", h="n/a", c="ok"),
 # state
 ev("START WP-4B proviso unmet (R-72)", "REFUSED", "RULE", "'SO IMPLEMENTATION DOES NOT BEGIN'", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-proviso-unmet", t="own-slice", e="n/a", x="none", h="n/a", c="ok"),
 ev("START WP-4B proviso discharged (git 08-03)", "PERFORMED", "-", "git 6a67da5d7 (σ reading of R-78: INTERPRETATION)", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-full", t="own-slice", e="n/a", x="none", h="n/a", c="ok"),
 ev("START WP-8 permission only (R-79)", "REFUSED", "RULE", "'NOT PERMITTED: any implementation work for WP-8'", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="permission-only", t="own-slice", e="n/a", x="none", h="n/a", c="ok"),
 # target / scope
 ev("START 7A after AUTHORIZE(7A) (R-47)", "PERFORMED", "-", "R-47 'First authorized activity: RED at 7A'", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-full", t="own-slice", e="n/a", x="none", h="n/a", c="ok"),
 ev("START 7B after AUTHORIZE(7A) (R-47)", "REFUSED", "RULE", "R-47 '7B and 7C are NOT authorized'", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-full", t="other-slice", e="n/a", x="none", h="n/a", c="ok"),
 ev("START 4C-2 after AUTHORIZE(4C-1) (R-89)", "REFUSED", "RULE", "R-89 'not implicitly and not by adjacency'", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="auth-full", t="other-slice", e="n/a", x="none", h="n/a", c="ok"),
 # evidence (register R-36 + the promotion matrix; one source cluster)
 *[ev(f"RAISE promoted #{n} (matrix)", "PERFORMED", "-", f"promotion matrix #{n} ({c} slices)", o="RAISE", r="RULING", a="ARB", k="engineering-behaviour", s="normal", t="AST-013", e="2+", x="none", h="n/a", c="ok") for n, c in ((2, 3), (3, 3), (9, 3), (10, "2-3"))],
 *[ev(f"RAISE not promoted #{n} (matrix)", "REFUSED", "RULE", f"promotion matrix #{n}: {why}", o="RAISE", r="RULING", a="ARB", k="engineering-behaviour", s="normal", t="AST-013", e="1", x="none", h="n/a", c="ok")
   for n, why in ((5, "'ONE demonstration → Candidate Pattern'"), (14, "'1 context only'"), (17, "'1 … replaced only on repeated evidence'"))],
 # history summary (registry)
 ev("ASSIGN a never-used number", "PERFORMED", "-", "register numbering", o="ASSIGN-ID", r="RULING", a="AUTHORITY", k="identifier", s="no-holder", t="self", e="n/a", x="none", h="unused", c="ok"),
 ev("ASSIGN the retired number R-90", "REFUSED", "RULE", "'The number R-90 is RETIRED, not recycled'", o="ASSIGN-ID", r="RULING", a="AUTHORITY", k="identifier", s="no-holder", t="self", e="n/a", x="none", h="retired", c="ok"),
 # choice (legal but not selected) — cannot break representability by construction of the semantics
 ev("SUPERSEDE declined (R-94)", "REFUSED", "CHOICE", "'superseding would blur chronology'", o="SUPERSEDE", r="RULING", a="ARB-CHIEF", k="acceptance-record", s="normal", t="PB-006 row", e="2+", x="none", h="n/a", c="ok"),
 ev("ANNOTATE chosen (R-94)", "PERFORMED", "-", "'ANNOTATE is adopted'", o="ANNOTATE", r="RULING", a="ARB-CHIEF", k="acceptance-record", s="normal", t="PB-006 row", e="2+", x="none", h="n/a", c="ok"),
]


def legality_data(events): return [e for e in events if e["out"] == "PERFORMED" or e["ground"] == "RULE"]


def conflicts(events, fields):
    """Pairs of legality events identical on `fields` (known values) with different outcomes."""
    out = []
    for p, q in itertools.combinations(legality_data(events), 2):
        if p["out"] == q["out"]: continue
        if all(p[f] == q[f] and p[f] not in ("NR", "UNK") for f in fields): out.append((p["id"], q["id"]))
    return out


def classes(pairs):
    """Special-case count = the number of distinct conflicting pair-classes (connected components of the conflict graph)."""
    parent = {}
    def find(x):
        parent.setdefault(x, x)
        while parent[x] != x: x = parent[x]
        return x
    for a, b in pairs: parent[find(a)] = find(b)
    return len({find(a) for p in pairs for a in p})


def explained_choices(fields, has_choice_layer):
    return [e["id"] for e in EV if e["ground"] == "CHOICE"] if has_choice_layer else []


def selftest():
    toy = [dict(ev("p", "PERFORMED", "-", "", o="X", a="A1")), dict(ev("q", "REFUSED", "RULE", "", o="X", a="A2"))]
    ok = [("toy: removing a creates a conflict", conflicts(toy, ("o",)) == [("p", "q")] and conflicts(toy, ("o", "a")) == []),
          ("NR never matches", conflicts([ev("p", "PERFORMED", "-", ""), ev("q", "REFUSED", "RULE", "")], ("o",)) == []),
          ("choice refusals never enter legality", all(e["ground"] != "CHOICE" for e in legality_data(EV))),
          ("full field set is representable", conflicts(EV, F) == [])]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    full = set(F); res = {}
    for x in F:
        c = conflicts(EV, sorted(full - {x})); sc = classes(c)
        res[x] = {"representable_without": not c, "special_cases_if_removed": sc,
                  "counterexamples": c[:6], "keep_at_lambda": {str(l): sc > l for l in (0.5, 1, 2)}}
    # search for a minimal representable field set (exhaustive; |F| = 10)
    minimal = []
    for n in range(1, len(F) + 1):
        for combo in itertools.combinations(F, n):
            if not conflicts(EV, combo): minimal.append(sorted(combo))
        if minimal: break
    score = {}
    for l in (0.5, 1, 2):
        best = min(((classes(conflicts(EV, list(combo))) + l * len(combo), sorted(combo)) for n in range(1, len(F) + 1) for combo in itertools.combinations(F, n)), key=lambda z: (z[0], len(z[1])))
        score[str(l)] = {"best_fields": best[1], "score": round(best[0], 2)}
    print(json.dumps({"labels": "MODEL-DERIVED; coded events (analyst-coded source facts); deterministic legality, nondeterministic choice",
                      "n_events": len(EV), "n_legality_events": len(legality_data(EV)),
                      "single_field_ablation": res, "minimal_representable_field_sets": minimal,
                      "complexity_optimum": score,
                      "choice_layer": {"representationally_necessary": False,
                                       "reason": "a nondeterministic legal set represents R-94; the choice layer only EXPLAINS the selection",
                                       "explained_only_with_choice_layer": explained_choices(F, True)}}, indent=1, ensure_ascii=False))
