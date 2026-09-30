"""M2 generalization checker (pre-registration prompts/KNOWLEDGEOS-M2-DELTA-PREREGISTRATION.md, 3f76392f..., frozen 7339f3f01).
Imports m1_check.py read-only and applies ONLY the frozen M2 deltas. Regime: pre-template, human authority (R-42..R-80).
Rows are SOURCE-FACT encodings; results are derived mechanically. Usage: python3 m2_check.py [--selftest]"""
import copy
import importlib.util
import json
import os
import sys

_s = importlib.util.spec_from_file_location("m1", os.path.join(os.path.dirname(os.path.abspath(__file__)), "m1_check.py"))
m1 = importlib.util.module_from_spec(_s); _s.loader.exec_module(m1)
m1.FRAME.update({"ADOPT": {"status", "annotation-role"}, "ALLOCATE": {"obligation"}, "PERMIT-CONSIDERATION": {"consideration"}})   # the M2 deltas

CASES = [
 {"id": "R-43", "ops": ["ACCEPT"], "op_basis": "headline 'WP-6 ACCEPTED'; R-62 annotation: 'transition type reads ACCEPTANCE, not Approval … completed work is accepted and closed … one transition no longer carries two types'",
  "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("acceptance", "self", "WP-6 … ACCEPTED"), ("work-lifecycle", "self", "WP-6 CLOSED/ACCEPTED"), ("classification", "self", "WP-7 entry conditions satisfied")],
  "authorizations": [], "governing_at_issue": True, "awaits_decision_act": False, "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "preservations": ["DECISION TEXT IS UNCHANGED and the acceptance STANDS (R-53 annotation)", "decision unchanged; Semantics unchanged — only the type label is corrected (R-62)",
                    "Recorded limit on the evidence (not a qualification of the acceptance)"],
  "r43_pointer_to_r53": True, "r53_note_in_decision_text": False},
 {"id": "R-44", "ops": ["RATIFY", "APPROVE", "RECLASSIFY-FINDING"], "op_basis": "headline 'A-1 RATIFIED'; 'Approved realization: option (d)'; 'reclassified as an Architecture–Enforcement Alignment Gap' (no row text defines these as vocabulary ops)",
  "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("norm", "self:RATIFY", "the invariant is binding"), ("design", "self:APPROVE", "Election declares its OWN consumer-side port"), ("classification", "self:RECLASSIFY-FINDING", "G-1 … reclassified"), ("finding-status", "self", "G-1 RESOLVED")],
  "authorizations": [], "governing_at_issue": True, "awaits_decision_act": False, "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "preservations": ["preserving TP-1 and AP-2", "Deptrac unmodified, domain model unchanged"]},
 {"id": "R-72", "ops": ["AUTHORIZE"], "op_basis": "headline 'WP-4B AUTHORIZED … SUBJECT TO A PROVISO THAT IS NOT YET SATISFIED'",
  "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("authorization", "self", "WP-4B authorized in principle; execution contingent and the contingency is unmet")],
  "authorizations": [{"scope": "WP-4B (in principle; proviso unmet)", "quote": "WP-4C AND WP-4D ARE EXPRESSLY NOT AUTHORIZED … §WP-4 CLOSURE IS EXPRESSLY NOT AUTHORIZED"}],
  "governing_at_issue": True, "awaits_decision_act": False, "guard_observation": "authorization in principle; its EFFECT waits on a proviso (three ARB decisions + one scope ruling)",
  "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "preservations": ["WP-4C AND WP-4D EXPRESSLY NOT AUTHORIZED", "§WP-4 CLOSURE … NOT AUTHORIZED: closure is an ACCEPTANCE OUTCOME, NOT AN AUTHORIZATION DECISION", "no RED begins"]},
 {"id": "R-77", "ops": ["REJECT"], "op_basis": "headline 'THE INCONSISTENCY CHARACTERIZATION IS NOT ADOPTED'",
  "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [], "change_reading_note": "'Characterization rejected' coded as the act's outcome, not a coordinate change; under the reading 'the characterization acquires status REJECTED', P4 would be VIOLATED (Frame+(REJECT) = ∅)",
  "authorizations": [], "governing_at_issue": True, "awaits_decision_act": False, "evidence_recorded": True, "evidence_changes_standing_alone": False,
  "preservations": ["ADR-T14 is not superseded", "R-73–R-76 stand · WP-4B's authorization (R-72) stands · no ADR is amended, withdrawn or annotated as superseded",
                    "an explicit governance act—not inference—shall amend or supersede it", "No further architectural redesign is authorized on this question"]},
 {"id": "R-79", "ops": ["DEFER", "PERMIT"], "op_basis": "headline 'WP-8 DEFERRED'; 'a deferral directive'; annotation: 'R-79 grants PERMISSION for WP-8 planning activities only'",
  "refers_to_other_rulings": True, "amends_other_text": False,
  "changes": [("work-lifecycle", "self:DEFER", "WP-8 deferred until §WP-4 closes"), ("permission", "self:PERMIT", "planning permitted, implementation not")],
  "authorizations": [], "governing_at_issue": True, "awaits_decision_act": False, "evidence_recorded": False, "evidence_changes_standing_alone": False,
  "preservations": ["It authorizes nothing, allocates nothing, and does not reopen R-73–R-78", "annotation; the DECISION is unchanged",
                    "PERMISSION · AUTHORIZATION · COMMISSIONING · EXECUTION. R-79 SUPPLIES ONLY THE FIRST"]},
]


def p1_r43(c):
    if c["id"] != "R-43": return "UNTESTABLE"
    return "SUPPORTED" if c["r43_pointer_to_r53"] and not c["r53_note_in_decision_text"] else "VIOLATED"


def p3prime(c): return "VIOLATED" if c["awaits_decision_act"] or not c["governing_at_issue"] else "SUPPORTED"


def p6(c):
    if not c["changes"] and c["ops"] != ["REJECT"]: return "UNTESTABLE"
    return "SUPPORTED" if c["preservations"] else "VIOLATED"


def p4(c): return "SUPPORTED" if not c["changes"] else m1.p4(c)


PRED = {"P1 record": m1.p1, "P1-R43 cross-row": p1_r43, "P2 scope": m1.p2, "P3′ no PREPARED phase": p3prime, "P4 operations/frames (M2)": p4,
        "P5 target": m1.p5, "P6 frame generality": p6}


def ledger(cases):
    L = {k: {c["id"]: f(c) for c in cases} for k, f in PRED.items()}
    S = {k: {"supported": sum(v == "SUPPORTED" for v in r.values()), "violated": sum(str(v).startswith("VIOLATED") for v in r.values()),
             "untestable": sum(v == "UNTESTABLE" for v in r.values()), "not_modelled": sum(str(v).startswith("NOT-MODELLED") for v in r.values())} for k, r in L.items()}
    return L, S


def selftest():
    m = copy.deepcopy(CASES)
    m[0]["r43_pointer_to_r53"] = False; m[1]["awaits_decision_act"] = True; m[2]["preservations"] = []; m[2]["authorizations"][0]["scope"] = None
    L, _ = ledger(m)
    ok = [("M2 delta applied: ADOPT frame has annotation-role", "annotation-role" in m1.FRAME["ADOPT"]),
          ("mutation: missing R-43 pointer -> VIOLATED", L["P1-R43 cross-row"]["R-43"] == "VIOLATED"),
          ("mutation: awaiting decision act -> P3′ VIOLATED", L["P3′ no PREPARED phase"]["R-44"] == "VIOLATED"),
          ("mutation: no preservation -> P6 VIOLATED", L["P6 frame generality"]["R-72"] == "VIOLATED"),
          ("mutation: unscoped authorization -> P2 VIOLATED", L["P2 scope"]["R-72"] == "VIOLATED"),
          ("unmodelled ops -> NOT-MODELLED", ledger(CASES)[0]["P4 operations/frames (M2)"]["R-79"].startswith("NOT-MODELLED"))]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    L, S = ledger(CASES)
    print(json.dumps({"scope": "5 rows, pre-template human-authority regime (R-42..R-80); selected by a frozen category-diversity rule; not IID",
                      "ledger": L, "summary": S, "reading_notes": {c["id"]: c.get("change_reading_note") for c in CASES if c.get("change_reading_note")},
                      "guard_observations": {c["id"]: c["guard_observation"] for c in CASES if c.get("guard_observation")}}, indent=1, ensure_ascii=False))
