"""T1 r3 instrument (frozen with prompts/KNOWLEDGEOS-T1-R3-PREREGISTRATION.md). Rule / Interpretation / Practice.
All outputs are MODEL-DERIVED from the encoded records. NOT-RECORDED never becomes DEVIATES.
Usage: python3 t1r3_check.py [case.json ...]   (no argument = sanity run on DEVELOPMENT cases; never tests)"""
import json
import sys

A = {"ARB", "DA", "PA", "Authority"}
HIGH = {"Engineering Standard", "Stable Engineering Capability"}
APPLIES = {"SOURCE-STATED", "SOURCE-DERIVED"}


def force(rule, t):
    prov, st = rule.get("prov"), rule.get("doc_status"); has = bool(prov) and prov[1] <= t
    if has and st in ("ADOPTED", "RATIFIED"): return "IN-FORCE"
    if has and st == "PROPOSED": return "AMBIGUOUS"
    if not has and st == "PROPOSED": return "NOT-IN-FORCE"
    return "UNKNOWN"


def act_index(tr):
    for i, e in enumerate(tr):
        if e["e"] in ("Promote", "Reject"): return i
    return None


def req_status(req, tr, i, target):
    """MET / UNMET / NOT-RECORDED / UNKNOWN for one requirement at act index i."""
    t = req["type"]
    if t in ("before", "before_if_high"):
        if t == "before_if_high":
            if target == "UNKNOWN": return "UNKNOWN"
            if target not in HIGH: return "MET"
        names = set(req["event"].split("|"))
        if any(e["e"] in names for e in tr[:i]): return "MET"
        return "UNMET" if any(e["e"] in names for e in tr[i + 1:]) else "NOT-RECORDED"
    if t == "decision": return "MET" if tr[i].get("authority") else "NOT-RECORDED"
    if t == "attr_min":
        ev = [e for e in tr[:i] if e["e"] == req["event"]]
        vals = [e[req["attr"]] for e in ev if req["attr"] in e]
        if not vals: return "NOT-RECORDED"
        return "MET" if max(vals) >= req["min"] else "UNMET"
    return "UNKNOWN"


def judge(reqs, tr, i, target):
    """Conformance of the act at i to a requirement set. Promote: UNMET -> DEVIATES. Reject: an UNMET requirement explains it."""
    act = tr[i]["e"]; per = {r["key"]: req_status(r, tr, i, target) for r in reqs}
    cls = {}
    for k, s in per.items():
        if s == "MET": cls[k] = "CONFORMS" if act == "Promote" else "MET (does not explain the rejection)"
        elif s == "UNMET": cls[k] = "DEVIATES" if act == "Promote" else "CONFORMS (rejection follows the unmet requirement)"
        else: cls[k] = s
    return cls


def relation(text_reqs, ireqs):
    ts = {r["key"]: r.get("strength", 1) for r in text_reqs}; rel = {}
    for r in ireqs:
        k, s = r["key"], r.get("strength", 1)
        rel[k] = "ADDS" if k not in ts else ("RESTATES" if s == ts[k] else ("NARROWS" if s > ts[k] else "WIDENS"))
    return rel


def evaluate(c):
    tr, rule = c["trace"], c["rule"]; i = act_index(tr); tgt = c["target"][0]
    out = {"case": c["name"], "reading_dependencies": sorted({e["label"] for e in tr if e["label"].startswith("READING")} |
           {v[1] for v in (c["target"], rule["applicability"]) if str(v[1]).startswith("READING")})}
    if rule["applicability"][0] == "NOT": out["text"] = "OUT-OF-SCOPE"; return out
    if i is None: out["text"] = "VACUOUS"; return out
    t_act = c["t_act"]; F = force(rule, t_act); out["force"] = F; exc = any(e["e"] == "ExceptionRecorded" for e in tr)
    text = judge(rule["text_requirements"], tr, i, tgt); out["text"] = text
    def explain(cls):
        return {k: ("EXCEPTION" if exc else {"AMBIGUOUS": "FORCE-AMBIGUOUS", "NOT-IN-FORCE": "FORCE-NOT-IN-FORCE"}.get(F, "UNEXPLAINED"))
                for k, v in cls.items() if v == "DEVIATES"}
    out["text_explanations"] = explain(text)
    interps = []
    for iv in c.get("interpretations", []):
        eligible = iv["authority"] in A and iv["host_adm"] == "NORMATIVE" and bool(iv.get("source"))
        rec = {"id": iv["id"], "t": iv["t"], "eligible": eligible, "relation_to_text": relation(rule["text_requirements"], iv["requirements"])}
        if iv["t"] > t_act: rec["judges_act"] = "NO (interpretation postdates the act)"
        elif iv["applicability"][0] not in APPLIES: rec["judges_act"] = f"NO (applicability {iv['applicability'][0]})"
        else:
            rec["judges_act"] = "YES"; cls = judge(iv["requirements"], tr, i, tgt); rec["conformance"] = cls
            rec["explanations"] = {k: ("EXCEPTION" if exc else "UNEXPLAINED") for k, v in cls.items() if v == "DEVIATES"}
        interps.append(rec)
    out["interpretations"] = interps
    # divergence pattern (MODEL-DERIVED)
    T_eq_I = all(set(r["relation_to_text"].values()) <= {"RESTATES"} for r in interps) if interps else True
    O_T = not any(v == "DEVIATES" for v in text.values())
    judged = [r for r in interps if r.get("judges_act") == "YES"]
    O_I = all(not any(v == "DEVIATES" for v in r["conformance"].values()) for r in judged) if judged else None
    if F == "UNKNOWN": pat = "UNRESOLVED (force unknown)"
    elif T_eq_I and O_T: pat = "T = I = O (direct conformance)"
    elif not T_eq_I and O_I is True: pat = "T != I and O |= I (practice follows interpretation drift)"
    elif not T_eq_I and O_I is False: pat = "T != I and O !|= I (layered divergence)"
    elif T_eq_I and not O_T: pat = "T = I and O !|= T (practice deviation)"
    else: pat = "T != I; O vs I UNDETERMINED (no interpretation judges this act)"
    out["pattern"] = pat
    # claims
    acts = [e for e in tr if e["e"] in ("StatusChange", "Promote", "Reject")]
    c1 = "TRIGGERED" if any(e["e"] == "StatusChange" and not e.get("authority") for e in acts) else ("UNDETERMINABLE" if any(not e.get("authority") for e in acts) else "NOT-TRIGGERED")
    c2 = "TRIGGERED" if any((e["e"] == "FreezeAuthor" and e.get("treated_as") == "Promote") or (e["e"] == "Decide" and e.get("is_derived_state")) for e in tr) else "NOT-TRIGGERED"
    c3_text = F == "IN-FORCE" and any(v == "UNEXPLAINED" for v in out["text_explanations"].values())
    c3_int = any(r["eligible"] and r.get("judges_act") == "YES" and any(v == "UNEXPLAINED" for v in r["explanations"].values()) for r in interps)
    c3 = "TRIGGERED" if (c3_text or c3_int) else ("UNDETERMINABLE" if F == "UNKNOWN" else "NOT-TRIGGERED")
    c4 = "NOT-TRIGGERED" if all(e.get("authority") for e in tr if e["e"] in ("Promote", "Reject")) else "UNDETERMINABLE"
    out["claims"] = {"C1": c1, "C2": c2, "C3": c3, "C4": c4}
    return out


# ---- DEVELOPMENT cases (sanity only) ----
E = lambda e, label="SOURCE", **a: {"e": e, "label": label, **a}
ES0061 = {"id": "ES-006.1", "prov": ["ARB", "2026-07-11"], "doc_status": "PROPOSED", "applicability": ["SOURCE-STATED", "SOURCE"],
          "text_requirements": [{"key": "ev", "type": "before", "event": "NecessityEvidence"},
                                {"key": "q", "type": "before_if_high", "event": "Qualification|Validation"},
                                {"key": "dec", "type": "decision"}]}
BAR_INTERPS = [  # the confirmed genealogy (F-LOG-0107/0108), graded bar strength: 1 instances, 2 repeated+not domain-specific, 3 bounded contexts
 {"id": "I-R39", "t": "2026-07-26", "source": "rulings L25", "authority": "DA", "host_adm": "NORMATIVE", "applicability": ["SOURCE-DERIVED", "methodology principles"],
  "requirements": [{"key": "bar", "type": "attr_min", "event": "NecessityEvidence", "attr": "contexts", "min": 2, "strength": 3}]},
 {"id": "I-MEMORY", "t": "2026-08-01", "source": ".claude/MEMORY.md L389", "authority": "none", "host_adm": "NON-NORMATIVE", "applicability": ["SOURCE-DERIVED", "engineering/ principles"],
  "requirements": [{"key": "bar", "type": "attr_min", "event": "NecessityEvidence", "attr": "contexts", "min": 2, "strength": 3}]},
 {"id": "I-CLAUDE", "t": "2026-08-01", "source": ".claude/CLAUDE.md L581", "authority": "none", "host_adm": "NON-NORMATIVE", "applicability": ["SOURCE-DERIVED", "methodology"],
  "requirements": [{"key": "bar", "type": "attr_min", "event": "NecessityEvidence", "attr": "instances", "min": 2, "strength": 1}]},
 {"id": "I-ES0013", "t": "2026-08-16", "source": "ES-001 L82", "authority": "ARB", "host_adm": "NORMATIVE", "applicability": ["SOURCE-STATED", "ES-001.3 itself"],
  "requirements": [{"key": "bar", "type": "attr_min", "event": "NecessityEvidence", "attr": "instances", "min": 2, "strength": 1}]}]
DEV = [
 {"name": "DEV P2 (ES-001.3)", "rule": ES0061, "target": ["Engineering Standard", "READING: ES-00x"], "t_act": "2026-08-16", "interpretations": BAR_INTERPS,
  "trace": [E("NecessityEvidence", instances=8), E("Promote", authority="ARB"), E("Refine", authority="ARB")]},
 {"name": "DEV P1 (R-41 / ES-004.3)", "rule": dict(ES0061, applicability=["SOURCE-DERIVED", "READING (MEDIUM): standards candidates"]), "target": ["Engineering Standard", "READING: ES-00x"], "t_act": "2026-07-30",
  "interpretations": [dict(BAR_INTERPS[0], applicability=["UNKNOWN", "R-39 speaks of principles; P1 is a documentation standard"])] + BAR_INTERPS[1:],
  "trace": [E("NecessityEvidence", instances=1), E("Promote", authority="PA"), E("Refine", authority="ARB"), E("Validation")]},
]

if __name__ == "__main__":
    cases = [json.load(open(p)) for p in sys.argv[1:]] or DEV
    print(json.dumps([evaluate(c) for c in cases], indent=1, ensure_ascii=False))
