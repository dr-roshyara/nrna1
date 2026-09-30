"""M1 hold-out checker (pre-registration prompts/KNOWLEDGEOS-M1-MODULAR-PREREGISTRATION.md, 4544310b..., frozen 27a09b5fd).
Rows are SOURCE-FACT encodings (quotes in CASES); prediction results are derived MECHANICALLY from the encoded flags by the frozen
rules. Every result is a hold-out result WITHIN ONE TEMPLATE REGIME (no IID claim). Usage: python3 m1_check.py [--selftest]"""
import copy
import json
import sys

FRAME = {"AUTHORIZE": {"authorization"}, "ADOPT": {"status"}, "HOLD": {"status"}, "WITHDRAW": {"status", "registry"},
         "ANNOTATE": {"annotation"}, "ACCEPT": {"acceptance"}, "CLOSE-WORK": {"work-lifecycle"}, "OPEN-WORK": {"work-lifecycle"},
         "SUBDIVIDE": {"work-structure"}, "DETERMINE": {"evidence-status", "classification"}, "FREEZE": {"regime"}, "LIFT": {"regime"},
         "CONTRA": {"contra"}, "CREATE-NORM": {"norm", "standing"}, "RAISE": {"standing"}, "REJECT": set()}
STATUS = {"PREPARED", "ADOPTED", "HELD", "WITHDRAWN"}

# per row: ops performed (coded first, from headline + performative verbs); changes = (coordinate, performing op or 'self', quote)
CASES = [
 {"id": "R-82", "ops": ["CREATE-NORM"], "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("norm", "self", "fixes the redrive predicate's meaning"), ("status", "R-86:ADOPT", "ADOPTED … (adoption act: R-86)"),
              ("annotation", "R-86:ADOPT", "PROVENANCE ANNOTATION … retained as HISTORICAL RECORD … the condition it imposed is discharged")],
  "authorizations": [], "statuses": ["PREPARED", "ADOPTED"],
  "status_facts": {"prepared_not_governing": True, "adopted_by_non_issuer": True, "text_unchanged_by_status_act": True},
  "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "frame_minus": ["does not allocate PM-6's confirmation half", "does not rule that a confirmed-semantics marker would be wrong"]},
 {"id": "R-83", "ops": ["CREATE-NORM"], "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("norm", "self", "the redrive's obligation is now fully specified"), ("status", "R-86:ADOPT", "ADOPTED (R-86)"),
              ("annotation", "R-86:ADOPT", "retained as HISTORICAL RECORD … condition discharged")],
  "authorizations": [], "statuses": ["PREPARED", "ADOPTED"],
  "status_facts": {"prepared_not_governing": True, "adopted_by_non_issuer": True, "text_unchanged_by_status_act": True},
  "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "frame_minus": ["APPLIES §12 rather than superseding it", "reopens neither ADR-T1's transaction separation nor R-76's scope", "chooses no mechanism"],
  "note": "'cannot be achieved by SCOPING — only by SUPERSEDING §12' (an operation-type constraint)"},
 {"id": "R-84", "ops": ["DETERMINE", "ALLOCATE"], "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("classification", "self", "§12's absence … was a PLAN DEFECT, not a scope gap"), ("obligation", "self:ALLOCATE", "WP-4B must implement §12's reconcile"),
              ("status", "R-86:ADOPT", "ADOPTED (R-86)"), ("annotation", "R-86:ADOPT", "condition discharged")],
  "authorizations": [{"scope": "WP-4B existing scope", "quote": "repaired by engineering under existing authorization; it does not require new scope"}],
  "statuses": ["PREPARED", "ADOPTED"], "status_facts": {"prepared_not_governing": True, "adopted_by_non_issuer": True, "text_unchanged_by_status_act": True},
  "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "frame_minus": ["R-76 is NOT widened", "PM-6's confirmation half stays out of scope", "does NOT adopt … Translator as the mechanism"],
  "note": "'a citation was treated as coverage'; 'reuse of CONCEPTS, never automatic reuse of IMPLEMENTATIONS'"},
 {"id": "R-85", "ops": ["AUTHORIZE", "CREATE-NORM"], "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("authorization", "self", "engineering is authorized to amend K2's SETUP"), ("norm", "self", "a FURTHER keystone is required for model B"),
              ("status", "R-86:ADOPT", "ADOPTED (R-86)"), ("annotation", "R-86:ADOPT", "condition discharged")],
  "authorizations": [{"scope": "K2 setup only", "quote": "amend K2's SETUP so it reaches concluded-but-unmarked without traversing the seam"}],
  "statuses": ["PREPARED", "ADOPTED"], "status_facts": {"prepared_not_governing": True, "adopted_by_non_issuer": True, "text_unchanged_by_status_act": True},
  "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "frame_minus": ["no production code changes on account of K2", "K3 stands as written", "no mechanism", "does not endorse amending a test to obtain a green suite"],
  "note": "target split: 'the intent is sound and the mechanism does not reach it'"},
 {"id": "R-86", "ops": ["ADOPT", "AUTHORIZE", "LIFT"], "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("status", "self", "the five become GOVERNING"), ("annotation", "self", "their provenance annotations become HISTORICAL RECORD"),
              ("regime", "self", "WP-4B BATCH 7 IS RELEASED"), ("authorization", "self", "engineering is AUTHORIZED to execute R-81's …, R-84's …, R-85's …")],
  "authorizations": [{"scope": "three named repairs (R-81, R-84, R-85)", "quote": "engineering is AUTHORIZED to execute R-81's per-process isolation, R-84's §12 reconcile, and R-85's K2 setup amendment"}],
  "statuses": ["PREPARED", "ADOPTED"], "status_facts": {"adopted_by_non_issuer": True, "no_delegation": True, "text_unchanged_by_status_act": True},
  "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "frame_minus": ["reopens no crash-model reasoning", "R-76 remains unamended", "PM-6's confirmation half remains UNALLOCATED", "ChallengeRaised PROMOTION and ALLOCATION remain OPEN",
                  "governance maintenance B-4..B-12 remains UNOPENED", "ADOPTION AUTHORITY IS NOT DELEGATED"],
  "note": "Option B declined: 'amending simply because we could would reopen a governance cycle without new evidence' (reopening needs new evidence; cf. L216)"},
 {"id": "R-87", "ops": ["ACCEPT", "CLOSE-WORK", "PERMIT-CONSIDERATION"], "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("work-lifecycle", "self", "WP-4B is CLOSED"), ("acceptance", "self", "§WP-4 advances by acceptance … never by authorization"),
              ("consideration", "self:PERMIT-CONSIDERATION", "WP-4C may be CONSIDERED (consideration is not authorization)"),
              ("acceptance", "self", "R-81..R-85 stand as implemented and verified")],
  "authorizations": [{"scope": "closing WP-4B", "quote": "Engineering is authorized to close WP-4B"}],
  "statuses": [], "status_facts": {"no_delegation": True},
  "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "frame_minus": ["§WP-4 is NOT closed", "WP-8 remains DEFERRED", "ChallengeRaised … remain OPEN", "PM-6's confirmation half remains UNALLOCATED",
                  "B-4..B-12 remains UNOPENED", "ENG-012 remains OPEN … no evidence classifies it as a defect", "enforceHorizon()'s … defect remains UNREPAIRED",
                  "no standing delegation of acceptance or adoption authority"],
  "note": "authority named 'ACCEPTING AUTHORITY'; 'advances by acceptance — never by authorization' (an operation-specific frame, source-stated)"},
 {"id": "R-89", "ops": ["AUTHORIZE", "DETERMINE", "CREATE-NORM"], "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("authorization", "self", "WP-4C-1 may be planned and implemented"), ("authorization", "self", "§WP-4 gains an executable package"),
              ("classification", "self", "a SCOPE-INTERPRETATION question, not a missing governing rule"), ("norm", "self", "OBLIGATION PLACED ON THE PLAN")],
  "authorizations": [{"scope": "WP-4C-1 (plan and implement)", "quote": "WP-4C-2 is NOT authorized, not implicitly and not by adjacency"}],
  "statuses": ["PREPARED", "ADOPTED"], "status_facts": {"prepared_not_governing": True, "no_delegation": True},
  "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "frame_minus": ["WP-4C-2 is NOT authorized", "no catalog is edited and no catalog version is created", "§15.3 is not answered", "§WP-4 remains OPEN",
                  "WP-4D unauthorized", "WP-8 remains DEFERRED", "COL-5a remains UNASSESSED", "no new architectural principle is introduced"],
  "note": "catalog rule 'version, never mutate' (ADR-T5): the two-layer principle outside rulings; path-independence stated: 'R-88's adoption was a SINGLE ACT …'"},
]


def p1(c): return "UNTESTABLE" if not c["refers_to_other_rulings"] else ("VIOLATED" if c["amends_other_text"] else "SUPPORTED")


def p2(c):
    if not c["authorizations"]: return "UNTESTABLE"
    return "VIOLATED" if any(not a.get("scope") or a.get("extends") for a in c["authorizations"]) else "SUPPORTED"


def p3a(c):
    if not c["statuses"]: return "UNTESTABLE"
    return "VIOLATED" if any(s not in STATUS for s in c["statuses"]) else "SUPPORTED"


def p3b(c):
    f = c["status_facts"]
    if not f: return "UNTESTABLE"
    bad = [k for k, v in f.items() if v is False]
    return "VIOLATED: " + ",".join(bad) if bad else "SUPPORTED"


def p4(c):
    """Every stated change lies in Frame+ of the op that the source attributes it to (self -> the row's ops; 'R-x:OP' -> OP)."""
    res = []
    for coord, by, _ in c["changes"]:
        ops = [by.split(":")[1]] if ":" in by and not by.startswith("self") else ([by.split(":")[1]] if by.startswith("self:") else c["ops"])
        if any(o not in FRAME for o in ops) and not any(coord in FRAME.get(o, set()) for o in ops): res.append("NOT-MODELLED")
        elif any(coord in FRAME[o] for o in ops if o in FRAME): res.append("FIT")
        else: res.append(f"VIOLATED({coord} ∉ Frame+({'/'.join(ops)}))")
    v = [r for r in res if r.startswith("VIOLATED")]
    return ("VIOLATED: " + "; ".join(sorted(set(v)))) if v else ("NOT-MODELLED (partial)" if "NOT-MODELLED" in res else "SUPPORTED")


def p5(c): return "UNTESTABLE" if not c["evidence_recorded"] else ("VIOLATED" if c["evidence_changes_standing_alone"] else "SUPPORTED")


def consistency(c):
    performed = " ".join(q.lower() for _, _, q in c["changes"])
    clash = [m for m in c["frame_minus"] if m.lower() in performed]
    return "CONSISTENT" if not clash else "CLASH: " + "; ".join(clash)


PRED = {"P1 record": p1, "P2 scope": p2, "P3a status-vocabulary": p3a, "P3b status-semantics": p3b, "P4 operations/frames": p4, "P5 target": p5}


def ledger(cases):
    L = {k: {c["id"]: f(c) for c in cases} for k, f in PRED.items()}
    summary = {k: {"supported": sum(v == "SUPPORTED" for v in r.values()), "violated": sum(str(v).startswith("VIOLATED") for v in r.values()),
                   "untestable": sum(v == "UNTESTABLE" for v in r.values()), "not_modelled": sum(str(v).startswith("NOT-MODELLED") for v in r.values())}
               for k, r in L.items()}
    return L, summary


def selftest():
    m = copy.deepcopy(CASES)
    m[6]["authorizations"].append({"scope": None}); m[0]["statuses"].append("SUPERSEDED"); m[1]["amends_other_text"] = True
    m[2]["evidence_changes_standing_alone"] = True; m[3]["changes"].append(("registry", "self", "x"))
    L, _ = ledger(m)
    ok = [("mutation P2 unscoped authorization -> VIOLATED", L["P2 scope"]["R-89"] == "VIOLATED"),
          ("mutation P3a fifth status -> VIOLATED", L["P3a status-vocabulary"]["R-82"] == "VIOLATED"),
          ("mutation P1 in-place amendment -> VIOLATED", L["P1 record"]["R-83"] == "VIOLATED"),
          ("mutation P5 evidence-alone standing change -> VIOLATED", L["P5 target"]["R-84"] == "VIOLATED"),
          ("mutation P4 change outside frame -> VIOLATED", L["P4 operations/frames"]["R-85"].startswith("VIOLATED")),
          ("unmodelled op is NOT-MODELLED, not VIOLATED", p4({"ops": ["ALLOCATE"], "changes": [("obligation", "self:ALLOCATE", "x")]}).startswith("NOT-MODELLED"))]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    L, S = ledger(CASES)
    print(json.dumps({"scope": "7 prospective hold-out rows within ONE template regime (R-81..R-91); not IID",
                      "ledger": L, "summary": S, "frame_minus_consistency": {c["id"]: consistency(c) for c in CASES},
                      "frame_minus_counts": {c["id"]: len(c["frame_minus"]) for c in CASES}}, indent=1, ensure_ascii=False))
