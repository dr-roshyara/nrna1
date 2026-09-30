"""Normative / observed / explanation conformance check (research hypothesis; MODEL-DERIVED results).
Normative language L(N) for promotion under ES-006.1 (from ES-006.1 L17/L20; bar excluded, as in T1 r1):
  NecessityEvidence  <  [Qualification or Validation, if target >= Engineering Standard]  <  Promote(explicit, named authority)
Observed traces carry provenance labels. Explanation layer, checked in order:
  recorded exception (SOURCE) -> DEVIATES-WITH-RECORDED-EXCEPTION
  norm status PROPOSED (SOURCE) -> DEVIATES-NORM-NOT-IN-FORCE
  otherwise -> DEVIATES-UNEXPLAINED
A requirement that fails only because an event is not recorded yields NOT-RECORDED, never a deviation."""
import json

NORM = {"id": "ES-006.1", "status": "PROPOSED", "status_label": "SOURCE (ES-006 L3; all 7 versions 2026-07-11..07-26, L0-REL-18)",
        "rule_provenance": "ARB, refined 2026-07-11", "rule_provenance_label": "SOURCE (ES-006.1 L14)"}
# L0-REL-18: force attaches per hosted rule (its own decision provenance); the container document stays PROPOSED.
# When the container is PROPOSED but the rule is ARB-dated, force is AMBIGUOUS: neither reading is chosen.
HIGH_RUNGS = {"Engineering Standard", "Stable Engineering Capability"}

CASES = {
 "R-39": {"in_scope": ("YES", "SOURCE: 'the normal promotion rule (ES-006.1 …)'"), "target": ("UNKNOWN", "rung not stated"),
          "trace": [("NecessityEvidence", "SOURCE"), ("Promote", "SOURCE"), ("ValidationExpected", "SOURCE")],
          "exception_recorded": ("YES", "SOURCE: 'Governance exception'")},
 "B (Layer Verification Rule)": {"in_scope": ("YES", "READING: methodology module"), "target": ("UNKNOWN", ""),
          "trace": [("NecessityEvidence", "SOURCE"), ("Proposal", "SOURCE"), ("FreezeAuthor", "SOURCE")], "exception_recorded": ("NO", "")},
 "P1 (R-41 / ES-004.3)": {"in_scope": ("YES", "READING (MEDIUM): ES-006 L5 scope covers 'standards candidates'"),
          "target": ("Engineering Standard", "READING: ES-00x standard series"),
          "trace": [("NecessityEvidence", "SOURCE"), ("Promote", "SOURCE"), ("FirstApplication", "SOURCE"), ("Refine", "SOURCE"), ("Validation", "SOURCE+CORROBORATED (EV-02)")],
          "exception_recorded": ("NO", "SOURCE: none in the four contemporaneous records")},
}

def classify(c):
    if c["in_scope"][0] != "YES": return "OUT-OF-SCOPE", []
    tr = [e for e, _ in c["trace"]]
    if "Promote" not in tr: return "VACUOUS (no promotion)", []
    i = tr.index("Promote"); pre = tr[:i]; deps = [lab for _, lab in c["trace"][:i + 1]] + [c["in_scope"][1]]
    if "NecessityEvidence" not in pre: return "NOT-RECORDED (evidence)", deps
    tgt = c["target"][0]
    if tgt == "UNKNOWN": qual = "UNDETERMINED"
    elif tgt in HIGH_RUNGS: qual = "OK" if ("Qualification" in pre or "Validation" in pre) else "DEVIATES"
    else: qual = "OK"
    if qual == "OK":
        return ("CONFORMS" if c["exception_recorded"][0] != "YES" else "CONFORMS (exception recorded anyway)"), deps
    if qual == "UNDETERMINED":
        return ("UNDETERMINED (rung) — exception recorded" if c["exception_recorded"][0] == "YES" else "UNDETERMINED (rung)"), deps
    deps += [c["target"][1]]
    if c["exception_recorded"][0] == "YES": return "DEVIATES-WITH-RECORDED-EXCEPTION", deps
    if NORM["status"] == "PROPOSED" and NORM.get("rule_provenance"):
        return "DEVIATES — explanation UNDETERMINED (norm force AMBIGUOUS: document PROPOSED, rule ARB-dated)", deps + [NORM["status_label"], NORM["rule_provenance_label"]]
    if NORM["status"] == "PROPOSED": return "DEVIATES-NORM-NOT-IN-FORCE", deps + [NORM["status_label"]]
    return "DEVIATES-UNEXPLAINED", deps

res = {}
for name, c in CASES.items():
    cls, deps = classify(c)
    res[name] = {"class": cls, "depends_on": sorted(set(deps)), "shortest_trace": [e for e, _ in c["trace"]][: [e for e, _ in c["trace"]].index("Promote") + 1] if any(e == "Promote" for e, _ in c["trace"]) else []}
    print(f"{name:30} {cls}\n{'':30} depends on: {sorted(set(deps))}")
json.dump({"norm": NORM, "results": res}, open(__file__.replace("conformance_check.py", "CONFORMANCE-RESULT.json"), "w"), indent=1, ensure_ascii=False)
