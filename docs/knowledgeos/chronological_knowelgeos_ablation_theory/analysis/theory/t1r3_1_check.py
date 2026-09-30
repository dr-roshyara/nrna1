"""T1 r3.1 instrument — corrects r3's vacuous-conformance defect (F-LOG-0110). r3 (t1r3_check.py) stays frozen and is only imported.
Three separate layers (never conflated):
  A requirement:  MET · DEVIATES · UNKNOWN · NOT-RECORDED · OUT-OF-SCOPE
  B aggregate:    CONFORMS iff every applicable requirement MET · DEVIATES iff >=1 DEVIATES ·
                  UNDETERMINABLE iff no DEVIATES and >=1 UNKNOWN/NOT-RECORDED · OUT-OF-SCOPE iff no applicable requirement / no act
  C claim:        SUPPORTED · REFUTED · UNDETERMINABLE · NOT-TRIGGERED
C3: REFUTED iff a DEVIATES against (Text with Force IN-FORCE) or (an eligible, applicable, prior Interpretation) has no recorded exception;
    SUPPORTED iff every such DEVIATES has a recorded exception; NOT-TRIGGERED iff every relevant set CONFORMS; UNDETERMINABLE otherwise
    (incl. a Text DEVIATES under AMBIGUOUS / UNKNOWN force). All results MODEL-DERIVED.
Usage: python3 t1r3_1_check.py [--selftest] [case.json ...]"""
import importlib.util
import json
import os
import sys

_spec = importlib.util.spec_from_file_location("r3", os.path.join(os.path.dirname(os.path.abspath(__file__)), "t1r3_check.py"))
r3 = importlib.util.module_from_spec(_spec); _spec.loader.exec_module(r3)   # frozen r3, used read-only (req_status, relation, force, A)


def req_level(reqs, tr, i, target):
    """Layer A, for a Promote act. (Reject acts: an UNMET requirement is recorded as MET-for-rejection; see note in the pre-registration.)"""
    out = {}
    for r in reqs:
        s = r3.req_status(r, tr, i, target)
        out[r["key"]] = {"MET": "MET", "UNMET": "DEVIATES", "NOT-RECORDED": "NOT-RECORDED", "UNKNOWN": "UNKNOWN"}.get(s, "UNKNOWN")
    return out


def aggregate(level):
    """Layer B."""
    vals = list(level.values())
    if not vals: return "OUT-OF-SCOPE"
    if "DEVIATES" in vals: return "DEVIATES"
    if all(v == "MET" for v in vals): return "CONFORMS"
    return "UNDETERMINABLE"


def evaluate(c):
    tr, rule = c["trace"], c["rule"]; i = r3.act_index(tr); tgt = c["target"][0]
    out = {"case": c["name"], "instrument": "r3.1"}
    if rule["applicability"][0] == "NOT" or i is None:
        out.update(text_aggregate="OUT-OF-SCOPE", claims={k: "NOT-TRIGGERED" for k in ("C1", "C2", "C3", "C4")}); return out
    if tr[i]["e"] != "Promote":
        out.update(text_aggregate="OUT-OF-SCOPE (act is not a Promote; rejection semantics not pre-registered for aggregation)"); return out
    t_act = c["t_act"]; F = r3.force(rule, t_act); exc = any(e["e"] == "ExceptionRecorded" for e in tr)
    tl = req_level(rule["text_requirements"], tr, i, tgt); ta = aggregate(tl)
    out.update(force=F, text_level=tl, text_aggregate=ta)
    relevant = []   # (source, aggregate, has_deviation, eligible_for_C3)
    relevant.append(("TEXT", ta, ta == "DEVIATES", F == "IN-FORCE"))
    ints = []
    for iv in c.get("interpretations", []):
        eligible = iv["authority"] in r3.A and iv["host_adm"] == "NORMATIVE" and bool(iv.get("source"))
        rec = {"id": iv["id"], "eligible": eligible, "relation_to_text": r3.relation(rule["text_requirements"], iv["requirements"])}
        if iv["t"] > t_act: rec["judges"] = "NO (postdates)"
        elif iv["applicability"][0] not in r3.APPLIES: rec["judges"] = f"NO (applicability {iv['applicability'][0]})"
        else:
            lv = req_level(iv["requirements"], tr, i, tgt); ag = aggregate(lv)
            rec.update(judges="YES", level=lv, aggregate=ag)
            if eligible: relevant.append((iv["id"], ag, ag == "DEVIATES", True))
        ints.append(rec)
    out["interpretations"] = ints
    # C3
    decisive = [(s, a, d) for s, a, d, el in relevant if el]
    devs = [s for s, a, d in decisive if d]
    if devs: c3 = "SUPPORTED" if exc else "REFUTED"
    elif ta == "DEVIATES" and F in ("AMBIGUOUS", "UNKNOWN"): c3 = "UNDETERMINABLE (text deviation under force " + F + ")"
    elif decisive and all(a == "CONFORMS" for s, a, d in decisive): c3 = "NOT-TRIGGERED"
    else: c3 = "UNDETERMINABLE"
    acts = [e for e in tr if e["e"] in ("StatusChange", "Promote", "Reject")]
    c1 = "REFUTED" if any(e["e"] == "StatusChange" and not e.get("authority") for e in acts) else ("UNDETERMINABLE" if any(not e.get("authority") for e in acts) else "SUPPORTED")
    c2 = "REFUTED" if any((e["e"] == "FreezeAuthor" and e.get("treated_as") == "Promote") or (e["e"] == "Decide" and e.get("is_derived_state")) for e in tr) else "NOT-TRIGGERED"
    names = [e.get("authority") for e in tr if e["e"] in ("Promote", "Reject")]
    c4 = "UNDETERMINABLE" if any(not n for n in names) else "SUPPORTED"
    new_auth = sorted({n for n in names if n and n not in r3.A})
    out["claims"] = {"C1": c1, "C2": c2, "C3": c3, "C4": c4}
    if new_auth: out["observations"] = {"NEW-AUTHORITY": new_auth}
    # divergence pattern
    T_eq_I = all(set(r["relation_to_text"].values()) <= {"RESTATES"} for r in ints) if ints else True
    judged = [r for r in ints if r.get("judges") == "YES"]
    OI = "UNDETERMINED" if not judged or any(r["aggregate"] == "UNDETERMINABLE" for r in judged) else ("NO" if any(r["aggregate"] == "DEVIATES" for r in judged) else "YES")
    OT = {"CONFORMS": "YES", "DEVIATES": "NO"}.get(ta, "UNDETERMINED")
    out["pattern"] = {"T=I": T_eq_I, "O|=T": OT, "O|=I": OI}
    return out


def selftest():
    tr = [{"e": "Promote", "label": "SOURCE", "authority": "ARB"}]
    agg = lambda *v: aggregate({f"r{k}": x for k, x in enumerate(v)})
    checks = [("MET+MET = CONFORMS", agg("MET", "MET") == "CONFORMS"),
              ("MET+UNKNOWN = UNDETERMINABLE", agg("MET", "UNKNOWN") == "UNDETERMINABLE"),
              ("NOT-RECORDED+UNKNOWN != CONFORMS", agg("NOT-RECORDED", "UNKNOWN") == "UNDETERMINABLE"),
              ("MET+DEVIATES = DEVIATES", agg("MET", "DEVIATES") == "DEVIATES"),
              ("no requirement = OUT-OF-SCOPE", agg() == "OUT-OF-SCOPE")]
    base = {"name": "T", "rule": {"id": "X", "prov": ["ARB", "2026-01-01"], "doc_status": "ADOPTED", "applicability": ["SOURCE-STATED", ""],
            "text_requirements": [{"key": "ev", "type": "before", "event": "NecessityEvidence"}]}, "target": ["UNKNOWN", ""], "t_act": "2026-02-01"}
    dev = dict(base, trace=[{"e": "Promote", "label": "SOURCE", "authority": "ARB"}, {"e": "NecessityEvidence", "label": "SOURCE"}])
    dexc = dict(base, trace=dev["trace"] + [{"e": "ExceptionRecorded", "label": "SOURCE"}])
    met = dict(base, trace=[{"e": "NecessityEvidence", "label": "SOURCE"}, {"e": "Promote", "label": "SOURCE", "authority": "ARB"}])
    amb = dict(dev, rule=dict(base["rule"], doc_status="PROPOSED"))
    nr = dict(base, trace=[{"e": "Promote", "label": "SOURCE", "authority": "ARB"}])
    checks += [("IN-FORCE deviation, no exception -> C3 REFUTED", evaluate(dev)["claims"]["C3"] == "REFUTED"),
               ("IN-FORCE deviation + exception -> C3 SUPPORTED", evaluate(dexc)["claims"]["C3"] == "SUPPORTED"),
               ("all MET -> C3 NOT-TRIGGERED", evaluate(met)["claims"]["C3"] == "NOT-TRIGGERED"),
               ("deviation under AMBIGUOUS force -> C3 UNDETERMINABLE", evaluate(amb)["claims"]["C3"].startswith("UNDETERMINABLE")),
               ("only NOT-RECORDED -> C3 UNDETERMINABLE", evaluate(nr)["claims"]["C3"] == "UNDETERMINABLE")]
    for n, ok in checks: print(("ok   " if ok else "FAIL ") + n)
    return all(ok for _, ok in checks)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    cases = [json.load(open(p)) for p in sys.argv[1:] if not p.startswith("--")] or r3.DEV
    print(json.dumps([evaluate(c) for c in cases], indent=1, ensure_ascii=False))
