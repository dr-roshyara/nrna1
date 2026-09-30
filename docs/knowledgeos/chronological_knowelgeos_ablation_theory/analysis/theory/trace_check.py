"""Transparent trace checker: candidate T1 invariants over the recorded case traces (R-39, B, P1).
Each trace event carries its provenance label: SOURCE (quoted fact) or READING (interpretive, MEDIUM).
Results are MODEL-DERIVED from these encodings; a violation is reported with the shortest violating prefix
and the labels it depends on. Nothing here is a historical invariant by itself."""
import json

# (event, attrs, label). Order = recorded order (append-only logs / dated rows); within-day order only where the source fixes it.
TRACES = {
 "R-39 (DDD principles; ES-006.1)": [
   ("NecessityEvidence", {"contexts": 1}, "SOURCE"),                 # "with evidence from ONE context"
   ("Promote", {"target": "Engineering Platform", "by": "DA", "exception": True}, "SOURCE"),  # approved; explicit exception
   ("ValidationExpected", {}, "SOURCE")],                             # "Expected validation: the next bounded context …"
 "B (Layer Verification Rule)": [
   ("NecessityEvidence", {}, "SOURCE"), ("Proposal", {}, "SOURCE"),    # §4 cases; "Requested of the Decision Authority"
   ("FreezeAuthor", {}, "SOURCE")],                                   # "FROZEN 2026-08-01"; "not adopted"
 "P1 (R-41 / ES-004.3)": [
   ("NecessityEvidence", {}, "SOURCE"),                               # WP-1 closure inconsistency
   ("Promote", {"target": "Engineering Standard", "by": "PA", "exception": False}, "READING"),  # target rung derived from the ES-00x series
   ("FirstApplication", {}, "SOURCE"), ("Refine", {"by": "ARB"}, "SOURCE"),
   ("Validation", {}, "READING")],                                    # 'VALIDATION EXECUTED' read as the qualification analog
}

def before(tr, i, ev): return any(e[0] == ev for e in tr[:i])
def promos(tr): return [(i, e) for i, e in enumerate(tr) if e[0] == "Promote"]

INVARIANTS = {
 "I-a Promote -> NecessityEvidence before": lambda tr: [(i, "no evidence before") for i, e in promos(tr) if not before(tr, i, "NecessityEvidence")],
 "I-b Promote -> Proposal before (E2)": lambda tr: [(i, "no proposal recorded before") for i, e in promos(tr) if not before(tr, i, "Proposal")],
 "I-c Promote(>=Engineering Standard) -> Qualification/Validation before (E3)": lambda tr: [(i, "validation only after") for i, e in promos(tr)
     if e[1].get("target") == "Engineering Standard" and not (before(tr, i, "Qualification") or before(tr, i, "Validation"))],
 "I-d Promote -> explicit decision by a named authority": lambda tr: [(i, "no authority") for i, e in promos(tr) if not e[1].get("by")],
 "I-e FreezeAuthor does not imply Promote (frozen != adopted)": lambda tr: [(len(tr) - 1, "freeze treated as promotion")] if any(e[0] == "FreezeAuthor" for e in tr) and any(e[0] == "Promote" for e in tr) else [],
 "I-f below-norm Promote -> recorded exception": lambda tr: [(i, "norm-gap without recorded exception") for i, e in promos(tr)
     if (e[1].get("target") == "Engineering Standard" and not before(tr, i, "Validation")) and not e[1].get("exception")],
}

NEEDS_PROMOTION = {k for k in INVARIANTS if not k.startswith("I-e")}
ABSENCE_BASED = {"I-b Promote -> Proposal before (E2)"}   # fires only because an event is not recorded -> NOT-RECORDED, never VIOLATED
out = {}
for name, inv in INVARIANTS.items():
    out[name] = {}
    for case, tr in TRACES.items():
        v = inv(tr)
        if name in NEEDS_PROMOTION and not promos(tr): out[name][case] = "VACUOUS (no promotion)"; continue
        if not v: out[name][case] = "HOLDS"; continue
        i, why = v[0]; prefix = tr[:i + 1]
        deps = sorted({e[2] for e in prefix})
        key = "NOT-RECORDED (absence is not a violation)" if name in ABSENCE_BASED else "VIOLATED"
        out[name][case] = {key: why, "shortest_violating_prefix": [e[0] for e in prefix], "depends_on_labels": deps}
for k, v in out.items():
    print(k)
    for c, r in v.items(): print(f"   {c:34} {r if isinstance(r, str) else json.dumps(r)}")
json.dump(out, open(__file__.replace("trace_check.py", "TRACE-CHECK-RESULT.json"), "w"), indent=1)
