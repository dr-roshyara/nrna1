"""T1 r2 instrument (frozen with prompts/KNOWLEDGEOS-T1-R2-PREREGISTRATION.md). Implements §2 Force/Applicability, §4 conformance,
§5 explanation and §6 claims C1–C4. Results are MODEL-DERIVED from the encoded traces; every result lists its READING dependencies.
Usage: python3 t1r2_check.py [case.json ...]    (no argument = sanity run on the DEVELOPMENT cases, which are never tests)"""
import json
import sys

AUTHORITIES = {"ARB", "DA", "PA", "Authority"}
HIGH_RUNGS = {"Engineering Standard", "Stable Engineering Capability"}


def force(rule, t):
    prov, st = rule.get("prov"), rule.get("doc_status")
    has = bool(prov) and prov[1] <= t
    if has and st in ("ADOPTED", "RATIFIED"): return "IN-FORCE"
    if has and st == "PROPOSED": return "AMBIGUOUS"
    if not has and st == "PROPOSED": return "NOT-IN-FORCE"
    return "UNKNOWN"


def req_order(tr, i, names):
    """CONFORMS if a named event precedes Promote; DEVIATES if one exists only after it; NOT-RECORDED if absent."""
    before = any(e["e"] in names for e in tr[:i]); after = any(e["e"] in names for e in tr[i + 1:])
    return "CONFORMS" if before else ("DEVIATES" if after else "NOT-RECORDED")


def evaluate(c):
    tr = c["trace"]; reads = sorted({e["label"] for e in tr if e["label"].startswith("READING")} | ({c["applicability"][1]} if c["applicability"][1].startswith("READING") else set()) | ({c["target"][1]} if str(c["target"][1]).startswith("READING") else set()))
    out = {"case": c["name"], "applicability": c["applicability"][0], "reading_dependencies": reads}
    if c["applicability"][0] == "NOT": out["requirements"] = "OUT-OF-SCOPE"; return out
    pi = [i for i, e in enumerate(tr) if e["e"] == "Promote"]
    F = force(c["rule"], c.get("t_promote", "9999")); out["force"] = F; out["doc_status"] = c["rule"].get("doc_status")
    out["provisional_promote"] = bool(pi) and any(e["e"] == "ValidationExpected" for e in tr[pi[0] + 1:])
    reqs = {}
    if not pi: reqs = {r: "VACUOUS" for r in ("R-ev", "R-q", "R-dec", "R-prop")}
    else:
        i = pi[0]; p = tr[i]; tgt = c["target"][0]
        reqs["R-ev"] = req_order(tr, i, {"NecessityEvidence"})
        reqs["R-q"] = "UNDETERMINED" if tgt == "UNKNOWN" else ("CONFORMS" if tgt not in HIGH_RUNGS else req_order(tr, i, {"Qualification", "Validation"}))
        a = p.get("authority"); reqs["R-dec"] = "NOT-RECORDED" if not a else "CONFORMS"
        if a and a not in AUTHORITIES: out["new_authority"] = a
        reqs["R-prop"] = req_order(tr, i, {"Proposal"})
    exc = any(e["e"] == "ExceptionRecorded" for e in tr)
    expl = {}
    for r, v in reqs.items():
        if v != "DEVIATES": continue
        expl[r] = "EXCEPTION-RECORDED" if exc else {"NOT-IN-FORCE": "FORCE-NOT-IN-FORCE", "AMBIGUOUS": "FORCE-AMBIGUOUS"}.get(F, "UNEXPLAINED")
    out["requirements"] = reqs; out["explanations"] = expl
    # claims
    sc = [e for e in tr if e["e"] in ("StatusChange", "Promote")]
    c1 = "TRIGGERED" if any(e["e"] == "StatusChange" and not e.get("authority") and not any(d["e"] == "Decide" for d in tr) for e in sc) else \
         ("UNDETERMINABLE" if any(not e.get("authority") for e in sc) else "NOT-TRIGGERED")
    c2 = "TRIGGERED" if any(e.get("treated_as") == "Promote" and e["e"] == "FreezeAuthor" for e in tr) or any(e["e"] == "Decide" and e.get("is_derived_state") for e in tr) else "NOT-TRIGGERED"
    dev = [r for r, v in reqs.items() if v == "DEVIATES"]
    c3 = "NOT-TRIGGERED" if not dev else ("TRIGGERED" if F == "IN-FORCE" and any(expl[r] == "UNEXPLAINED" for r in dev) else ("UNDETERMINABLE" if F == "UNKNOWN" else "NOT-TRIGGERED (deviation reported under force " + F + ")"))
    c4 = "NOT-TRIGGERED" if all(e.get("authority") for e in tr if e["e"] == "Promote") else "UNDETERMINABLE"
    out["claims"] = {"C1": c1, "C2": c2, "C3": c3, "C4": c4}
    return out


E = lambda e, label="SOURCE", **a: {"e": e, "label": label, **a}
ES0061 = {"id": "ES-006.1", "prov": ("ARB", "2026-07-11"), "doc_status": "PROPOSED"}
DEV_CASES = [
 {"name": "DEV R-39", "applicability": ("SOURCE-STATED", "SOURCE: 'the normal promotion rule (ES-006.1'"), "target": ("UNKNOWN", ""), "rule": ES0061, "t_promote": "2026-07-26",
  "trace": [E("NecessityEvidence"), E("Promote", authority="DA"), E("ExceptionRecorded"), E("ValidationExpected")]},
 {"name": "DEV B (Layer Verification Rule)", "applicability": ("SOURCE-DERIVED", "READING: methodology module"), "target": ("UNKNOWN", ""), "rule": ES0061, "t_promote": "2026-08-01",
  "trace": [E("NecessityEvidence"), E("Proposal"), E("FreezeAuthor")]},
 {"name": "DEV P1 (R-41 / ES-004.3)", "applicability": ("SOURCE-DERIVED", "READING (MEDIUM): ES-006 L5 'standards candidates'"), "target": ("Engineering Standard", "READING: ES-00x series"), "rule": ES0061, "t_promote": "2026-07-30",
  "trace": [E("NecessityEvidence"), E("Promote", authority="PA"), E("Refine", authority="ARB"), E("Validation", "SOURCE+CORROBORATED")]},
]

if __name__ == "__main__":
    cases = [json.load(open(p)) for p in sys.argv[1:]] or DEV_CASES
    res = [evaluate(c) for c in cases]
    print(json.dumps(res, indent=1, ensure_ascii=False))
