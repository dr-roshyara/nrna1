"""1an r3: separating-set minimization (committed before its first run). Revision of minimize_1an.py r2, whose outputs stand.
Why r3 (review B-r2, disclosed): D1 consistency-by-ignorance (UNK variables became minimal signatures) · D2 the M3 operation
test had zero power · D3 correction C2 was half-applied (violated schema R2) · D4/D5 identity tokens and object-identity
lookups · D7 clusters ignored. The r2 semantics are NOT silently changed: r3 is a new, separately frozen instrument.

ALL RESULTS ARE MODEL-RELATIVE (formal); never empirical falsification. Empirical status is imported read-only.

DATA: SEMOBS r4 via models_1al; variants BASE · REV · REV-TYPE as in r2, except C2 is applied coherently (schema R2): both
ADOPT events on R-81..85 get k=ruling and c=conformant (F-LOG-0153); REV-TYPE sets their k=UNK and R-91's k to its header type.
Identity tokens 'same:X' are treated as UNKNOWN values (equal only to themselves for R2 purposes, never a separating value).

SEPARATING SEMANTICS (per operation o; legality events = PERFORMED or RULE-grounded; CHOICE excluded):
  For each opposite-outcome pair (p,q): D(p,q) = {v in pool : p[v], q[v] both KNOWN (not UNK/n/a/token) and p[v] != q[v]}.
  D(p,q) = {}  -> pair is UNSEPARATED (undetermined on the pool; reported, never 'solved' by UNK).
  A guard V SEPARATES o iff V hits D(p,q) for every pair with D(p,q) != {}  (minimum hitting sets, exhaustive).
  Variable classes per (o, model):
    FORMAL-REQUIRED      in every minimum-cardinality AND every inclusion-minimal hitting set
    MODEL-COND-REDUNDANT omitted by some inclusion-minimal hitting set
    NEVER-SEPARATES      appears in no D(p,q)                          (untested; not 'redundant')
    IDENTITY-LOOKUP flag v's known values are pairwise distinct across o's legality events (>=2 events): a separation by v
                         memorizes objects; it is reported but flagged non-generalizing.
  Independence: for each FORMAL-REQUIRED variable, the number of distinct cluster-pairs among pairs whose ONLY separating
  variable in the pool is v (forced pairs).
  Models: M0 {a,k,s,t} · M1 +e · M2 +c · M4 +h · MF = APPL[o] ∪ {r}.
OPERATION TEST (M3, renamed MO): cross-operation opposite-outcome pairs with all MF-union variables known and equal except o.
  Power = number of cross-op pairs with D computable on >= 1 variable; if 0 -> operation UNTESTABLE.
Usage: python3 minimize_1an_r3.py [--selftest]"""
import itertools
import json
import os
import sys
import importlib.util

HERE = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(HERE)


def load(name, path):
    s = importlib.util.spec_from_file_location(name, path); m = importlib.util.module_from_spec(s); s.loader.exec_module(m); return m


EMPIRICAL = {"a": "SUPPORTED", "k": "SUPPORTED", "s": "SUPPORTED (target-indexed)", "t": "index of s; possible witness only",
             "e": "WEAK (overloaded)", "c": "WEAK (coarse grain)", "o": "NOT DEMONSTRATED", "r": "NOT DEMONSTRATED",
             "x": "NOT DEMONSTRATED", "h": "absorbed into status (formal)"}
MODELS = {"M0": set("akst"), "M1": set("akste"), "M2": set("akstc"), "M4": set("aksth")}


def isknown(v, U): return v not in (U, "n/a", None) and not (isinstance(v, str) and v.startswith("same:"))


def variant(obs, name):
    out = [dict(e) for e in obs]
    if name in ("REV", "REV-TYPE"):
        for e in out:
            if e["event_id"].startswith("RAISE L493-B"): e["a"] = "UNK"
            if e["event_id"].startswith("ADOPT R-81..85"): e["k"] = "ruling"; e["c"] = "conformant"
    if name == "REV-TYPE":
        for e in out:
            if e["event_id"].startswith("ADOPT R-81..85"): e["k"] = "UNK"
            if e["event_id"] == "ADOPT R-91 by the DA (held)": e["k"] = "determination-on-submitted-evidence"
    return out


def legal(obs): return [e for e in obs if e["ground"] != "CHOICE"]


def pairs(evs):
    return [(p, q) for p, q in itertools.combinations(evs, 2) if (p["outcome"] == "PERFORMED") != (q["outcome"] == "PERFORMED")]


def D(p, q, pool, U): return frozenset(v for v in pool if isknown(p[v], U) and isknown(q[v], U) and p[v] != q[v])


def hitting(sets, pool):
    sets = [s for s in sets if s]
    if not sets: return [], []
    hs = [frozenset(c) for n in range(len(pool) + 1) for c in itertools.combinations(sorted(pool), n) if all(frozenset(c) & s for s in sets)]
    incl = [h for h in hs if not any(g < h for g in hs)]
    mn = min(len(h) for h in incl); return incl, [h for h in incl if len(h) == mn]


def analyse_op(evs, pool, U):
    P = pairs(evs)
    if not P: return {"status": "UNCONSTRAINED"}
    Ds = [(p, q, D(p, q, pool, U)) for p, q in P]
    unsep = [(p["event_id"], q["event_id"]) for p, q, d in Ds if not d]
    incl, mins = hitting([d for _, _, d in Ds], pool)
    appear = set().union(*[d for _, _, d in Ds]) if Ds else set()
    cls = {}
    for v in sorted(pool):
        if v not in appear: cls[v] = "NEVER-SEPARATES"
        elif incl and all(v in h for h in incl): cls[v] = "FORMAL-REQUIRED"
        else: cls[v] = "MODEL-COND-REDUNDANT"
    ident = [v for v in sorted(pool) if len([e for e in evs if isknown(e[v], U)]) >= 2 and
             len({e[v] for e in evs if isknown(e[v], U)}) == len([e for e in evs if isknown(e[v], U)])]
    forced = {v: sorted({tuple(sorted((p["cluster"], q["cluster"]))) for p, q, d in Ds if d == frozenset({v})}) for v in cls if cls[v] == "FORMAL-REQUIRED"}
    return {"status": "SEPARABLE" if not unsep else "PARTIAL", "n_pairs": len(P), "unseparated_pairs": unsep,
            "minimal_guards": [sorted(h) for h in incl], "minimum_guards": [sorted(h) for h in mins], "variable_class": cls,
            "identity_lookup_flags": ident, "forced_cluster_pairs": {v: [list(c) for c in cs] for v, cs in forced.items()}}


def guard_analysis(sm, obs):
    U = sm.U; L = legal(obs); res = {}
    for o in sorted({e["o"] for e in L}):
        evs = [e for e in L if e["o"] == o]; appl = set(sm.APPL.get(o, "")) | {"r"}
        pools = {m: vs & appl for m, vs in MODELS.items()}; pools["MF"] = appl
        res[o] = {"events": len(evs), **{m: analyse_op(evs, pool, U) for m, pool in pools.items()}}
    return res


def operation_test(sm, obs):
    U = sm.U; L = legal(obs); pool = sorted(set("akstexhcr")); power = 0; decisive = []
    for p, q in pairs(L):
        if p["o"] == q["o"]: continue
        comp = [v for v in pool if isknown(p[v], U) and isknown(q[v], U)]
        if comp: power += 1
        if comp and all(p[v] == q[v] for v in comp) and len(comp) == len(pool): decisive.append((p["event_id"], q["event_id"]))
    return {"cross_op_pairs_with_comparable_vars": power, "fully_comparable_equal_pairs": decisive,
            "operation": "UNTESTABLE (no cross-operation pair is fully comparable)" if not decisive else "FORMAL-REQUIRED under MO"}


def selftest():
    U = "UNK"
    ev = [dict(event_id="p", cluster="c1", outcome="PERFORMED", a="A", s="x", r=U),
          dict(event_id="q", cluster="c2", outcome="REFUSED", a="A", s="y", r=U)]
    r = analyse_op(ev, {"a", "s", "r"}, U)
    ok = [("only s separates", r["minimal_guards"] == [["s"]]), ("UNK r never separates", r["variable_class"]["r"] == "NEVER-SEPARATES"),
          ("a equal never separates", r["variable_class"]["a"] == "NEVER-SEPARATES"),
          ("token is not a separating value", D(dict(ev[0], a="same:X"), dict(ev[1], a="B"), {"a"}, U) == frozenset()),
          ("unseparated pair reported", analyse_op([dict(ev[0], s="x"), dict(ev[1], s="x")], {"a", "s"}, U)["unseparated_pairs"] == [("p", "q")])]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    m1 = load("m1al", os.path.join(TH, "MODELS-1AL", "models_1al.py")); sm, obs = m1.r4_observations()
    out = {"labels": "FORMAL, MODEL-RELATIVE (separating semantics r3); NOT empirical falsification", "empirical_status_imported": EMPIRICAL, "variants": {}}
    for v in ("BASE", "REV", "REV-TYPE"):
        ob = variant(obs, v); out["variants"][v] = {"guards": guard_analysis(sm, ob), "operation_test": operation_test(sm, ob)}
    print(json.dumps(out, indent=1, ensure_ascii=False, default=str))
