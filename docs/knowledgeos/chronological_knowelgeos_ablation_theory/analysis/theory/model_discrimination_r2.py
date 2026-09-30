"""Hypothesis discrimination r2 (supersedes model_discrimination.py for use; r1 stays as record). Deterministic; no ML.
Corrections over r1 (review 2026-09-27):
  * Ev, Qual, Val are SEPARATE requirements; which of Qual/Val the high-rung norm demands is a declared NORM-READING
    parameter (QUAL | VAL | QUAL_AND_VAL | QUAL_OR_VAL), never collapsed silently.
  * Observation = vector of independent components (below); NOT-RECORDED / UNKNOWN are open-world wildcards (never FALSE).
  * H3 split: H3a kind-dependent ORDERING; H3b kind-dependent SEMANTICS of the adoption act (work adoption = operational
    authorization, so epistemic requirements are out of its scope; knowledge adoption = epistemic standing).
  * The r1 "EIG" is renamed MDS-U: model discrimination score under a declared UNIFORM outcome model (artificial; not an
    empirical probability). The primary measure is LOGICAL: which hypothesis pairs a case can separate at all.
Labels: hypotheses HYPOTHESIS; attributes SOURCE-FACT / MODEL-ASSUMPTION as tagged; every output MODEL-DERIVED.
Usage: python3 model_discrimination_r2.py [--selftest] [case.json ...]"""
import itertools
import json
import math
import sys

REQ = ("EV", "QUAL", "VAL")              # ordering before P:  MET · DEVIATES · UNKNOWN · NOT-RECORDED · OUT-OF-SCOPE
FLAG = ("OBL", "EXC", "AUTH")            # PRESENT · ABSENT · UNKNOWN · NOT-RECORDED
SEM = "SEM"                              # adoption semantics: EPISTEMIC · OPERATIONAL · UNKNOWN · NOT-RECORDED
DOM = {**{r: ("MET", "DEVIATES") for r in REQ}, **{f: ("PRESENT", "ABSENT") for f in FLAG}, SEM: ("EPISTEMIC", "OPERATIONAL")}
WILD = {"UNKNOWN", "NOT-RECORDED"}
NORMS = ("QUAL", "VAL", "QUAL_AND_VAL", "QUAL_OR_VAL")
HYPS = ("H1", "H2", "H3a", "H3b", "H4", "H5")


def high_ok(v, norm):
    q, s = v["QUAL"] == "MET", v["VAL"] == "MET"
    return {"QUAL": q, "VAL": s, "QUAL_AND_VAL": q and s, "QUAL_OR_VAL": q or s}[norm]


def h1(v, a, norm):
    return v["EV"] == "MET" and (not a["high"] or high_ok(v, norm))


def permits(h, v, a, norm):
    """HYPOTHESIS predicates over a DEFINITE vector v and definite attributes a."""
    if h == "H1": return h1(v, a, norm)
    if h == "H2": return v["EV"] == "MET" and (not a["high"] or v["QUAL"] == "MET")          # Val may follow P
    if h == "H3a": return h1(v, a, norm) if a["kind"] == "knowledge" else (v["EV"] == "MET" and v["AUTH"] == "PRESENT")
    if h == "H3b": return (h1(v, a, norm) and v[SEM] == "EPISTEMIC") if a["kind"] == "knowledge" else \
                          (v[SEM] == "OPERATIONAL" and v["AUTH"] == "PRESENT")
    if h == "H4": return h1(v, a, norm) if a["force"] == "IN" else True
    if h == "H5": return h1(v, a, norm) or (any(v[r] == "DEVIATES" for r in REQ) and (v["OBL"] == "PRESENT" or v["VAL"] == "DEVIATES"))
    raise KeyError(h)


ATTR_DOM = {"force": ("IN", "AMB"), "kind": ("knowledge", "work"), "high": (False, True)}


def attr_completions(attrs):
    return [dict(zip(ATTR_DOM, c)) for c in itertools.product(*[ATTR_DOM[k] if attrs[k] == "UNKNOWN" else (attrs[k],) for k in ATTR_DOM])]


def completions(obs, a):
    """Definite vectors compatible with a partial observation. OUT-OF-SCOPE (and low rung) fixes QUAL/VAL to MET-equivalent."""
    keys = list(DOM); opts = []
    for k in keys:
        o = obs.get(k, "NOT-RECORDED")
        if k in ("QUAL", "VAL") and (o == "OUT-OF-SCOPE" or not a["high"]): opts.append(("MET",))
        elif o in WILD: opts.append(DOM[k])
        else: opts.append((o,))
    return [dict(zip(keys, c)) for c in itertools.product(*opts)]


def consistent(h, obs, attrs, norm):
    """CONSISTENT iff some attribute completion and some wildcard completion is permitted (open world)."""
    for a in attr_completions(attrs):
        if any(permits(h, v, a, norm) for v in completions(obs, a)): return "CONSISTENT"
    return "INCONSISTENT"


def separable_pairs(attrs, norm, alive):
    """LOGICAL measure: pairs (of alive hypotheses) separated by >= 1 definite outcome for >= 1 attribute completion."""
    out = {}
    for x, y in itertools.combinations(alive, 2):
        wit = None
        for a in attr_completions(attrs):
            for v in completions({}, a):
                if permits(x, v, a, norm) != permits(y, v, a, norm): wit = {"attrs": a, "outcome": v, "permitted_by": x if permits(x, v, a, norm) else y}; break
            if wit: break
        out[f"{x}|{y}"] = wit or "INDISTINGUISHABLE"
    return out


def mds_u(attrs, norm, alive):
    """Artificial: uniform prior over alive hypotheses; P(v|h,a) uniform over h's permitted definite vectors; attributes marginalized uniformly."""
    if len(alive) < 2: return 0.0
    H0 = math.log2(len(alive)); ac = attr_completions(attrs); vs = {json.dumps(v, sort_keys=True): v for a in ac for v in completions({}, a)}
    score = 0.0
    for key, v in vs.items():
        joint = {}
        for h in alive:
            p = 0.0
            for a in ac:
                perm = [w for w in completions({}, a) if permits(h, w, a, norm)]
                p += (1 / len(perm) if perm and any(json.dumps(w, sort_keys=True) == key for w in perm) else 0.0) / len(ac)
            joint[h] = p / len(alive)
        z = sum(joint.values())
        if z > 0: score += z * (H0 - (-sum(q / z * math.log2(q / z) for q in joint.values() if q > 0)))
    return round(score, 4)


# ---- development evidence (read earlier; development only) ----
P1 = {"name": "P1 (R-41 / ES-004.3)",
      "obs": {"EV": "MET", "QUAL": "NOT-RECORDED", "VAL": "DEVIATES", "OBL": "NOT-RECORDED", "EXC": "NOT-RECORDED", "AUTH": "PRESENT", "SEM": "UNKNOWN"},
      "attrs": {"force": "AMB", "kind": "UNKNOWN", "high": True},
      "tags": {"EV": "SOURCE (1 instance prior)", "QUAL": "SOURCE: no qualification event recorded at all -> NOT-RECORDED (r1 had merged it into dVal)",
               "VAL": "SOURCE: validation recorded after P", "AUTH": "SOURCE: PA", "force": "SOURCE-FACT (ES-006 PROPOSED)",
               "high": "SOURCE-DERIVED: ES-004.3 is hosted in an Engineering Standard document", "kind": "UNKNOWN (r1 had assumed knowledge)"}}


def survivors(cases, norm):
    return [h for h in HYPS if all(consistent(h, c["obs"], c["attrs"], norm) == "CONSISTENT" for c in cases)]


def selftest():
    A = {"force": "IN", "kind": "knowledge", "high": True}
    ok = [("silence refutes nothing", all(consistent(h, {}, A, n) == "CONSISTENT" for h in ("H1", "H2", "H4", "H5") for n in NORMS)),
          ("H1 refuted by EV DEVIATES", consistent("H1", {"EV": "DEVIATES"}, A, "QUAL") == "INCONSISTENT"),
          ("Qual/Val not collapsed: VAL DEV, QUAL MET: H1 ok under QUAL, refuted under VAL",
           consistent("H1", {"EV": "MET", "QUAL": "MET", "VAL": "DEVIATES"}, A, "QUAL") == "CONSISTENT" and
           consistent("H1", {"EV": "MET", "QUAL": "MET", "VAL": "DEVIATES"}, A, "VAL") == "INCONSISTENT"),
          ("H4 permits anything under AMB", consistent("H4", {"EV": "DEVIATES"}, dict(A, force="AMB"), "QUAL") == "CONSISTENT"),
          ("H3b: work adoption with SEM EPISTEMIC refuted", consistent("H3b", {"SEM": "EPISTEMIC"}, dict(A, kind="work"), "QUAL") == "INCONSISTENT"),
          ("H3a vs H3b: work, EV DEVIATES, SEM OPERATIONAL, AUTH PRESENT", consistent("H3a", {"EV": "DEVIATES", "SEM": "OPERATIONAL", "AUTH": "PRESENT"}, dict(A, kind="work"), "QUAL") == "INCONSISTENT"
           and consistent("H3b", {"EV": "DEVIATES", "SEM": "OPERATIONAL", "AUTH": "PRESENT"}, dict(A, kind="work"), "QUAL") == "CONSISTENT"),
          ("low rung puts QUAL/VAL out of scope", consistent("H1", {"EV": "MET", "VAL": "DEVIATES"}, dict(A, high=False), "VAL") == "CONSISTENT")]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


def report(cases_new):
    rep = {"labels": "all outputs MODEL-DERIVED; MDS-U is an artificial uniform-model score, NOT empirical information", "by_norm_reading": {}}
    for norm in NORMS:
        alive = survivors([P1], norm)
        r = {"P1 consistency": {h: consistent(h, P1["obs"], P1["attrs"], norm) for h in HYPS}, "alive_after_dev": alive}
        for c in cases_new:
            r[c["name"]] = {"consistency": {h: consistent(h, c["obs"], c["attrs"], norm) for h in alive}}
            alive2 = [h for h in alive if r[c["name"]]["consistency"][h] == "CONSISTENT"]
            r[c["name"]]["alive_after"] = alive2
            r[c["name"]]["still_indistinguishable_pairs"] = [p for p, w in separable_pairs(c["attrs"], norm, alive2).items() if w == "INDISTINGUISHABLE"]
        rep["by_norm_reading"][norm] = r
    return rep


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    cases = [json.load(open(p)) for p in sys.argv[1:] if not p.startswith("--")]
    rep = report(cases)
    if not cases:   # pre-read planning mode: per candidate attribute profile
        prof = {"L216 frozen pre-read (force AMB, kind UNKNOWN, high UNKNOWN)": {"force": "AMB", "kind": "UNKNOWN", "high": "UNKNOWN"},
                "IN-force knowledge (e.g. L493 under I-R39; assumption)": {"force": "IN", "kind": "knowledge", "high": "UNKNOWN"},
                "AMB knowledge (e.g. L82; assumption)": {"force": "AMB", "kind": "knowledge", "high": "UNKNOWN"}}
        for norm in NORMS:
            alive = rep["by_norm_reading"][norm]["alive_after_dev"]
            rep["by_norm_reading"][norm]["planning"] = {n: {"MDS-U": mds_u(a, norm, alive),
                "separable_pairs": sum(1 for w in separable_pairs(a, norm, alive).values() if w != "INDISTINGUISHABLE"),
                "of_pairs": len(alive) * (len(alive) - 1) // 2} for n, a in prof.items()}
    print(json.dumps(rep, indent=1, ensure_ascii=False))
