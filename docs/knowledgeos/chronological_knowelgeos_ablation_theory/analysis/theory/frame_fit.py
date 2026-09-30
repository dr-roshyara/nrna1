"""1q frame experiment (spec FRAME-1Q/SPEC.json, frozen e772eb53d). Deterministic; M0 (m0_check.py) imported read-only.
Cases are SOURCE-FACT encodings of rulings-register rows; the M0 operation mapping is INTERPRETATION and tagged.
Usage: python3 frame_fit.py [--selftest]"""
import importlib.util
import itertools
import json
import os
import sys

_s = importlib.util.spec_from_file_location("m0", os.path.join(os.path.dirname(os.path.abspath(__file__)), "m0_check.py"))
m0 = importlib.util.module_from_spec(_s); _s.loader.exec_module(m0)

# changed / preserved: M0 coordinates where one applies, otherwise a quoted non-M0 coordinate (prefix "~")
CASES = [
 {"id": "R-47", "ops": ["AUTHORIZE"], "m0_op": None, "changed": ["~authorization(7A)", "~execution-responsibility"],
  "preserved": ["~authorization(7B,7C)", "~observable-behaviour", "~status(Layer Verification Rule)=PROPOSED"],
  "non_effects": [("7B and 7C are NOT authorized", "RELATION"), ("7A is inert — no observable behaviour changes until 7C", "INVARIANT"),
                  ("silence is not adoption (R-34)", "INVARIANT")],
  "guard_stated": "WP-6 ACCEPTED ∧ WP-7 PLAN APPROVED, satisfied by R-43 ∧ R-46", "history": None},
 {"id": "R-53", "ops": ["ANNOTATE", "RECORD-COUNTER-EVIDENCE"], "m0_op": "CONTRA", "m0_map": "INTERPRETATION: reproduced evidence contradicting the acceptance evidence",
  "changed": ["contra", "~annotation(R-43 forward pointer)"], "preserved": ["standing", "~decision-text(R-43)", "~epistemic(FALSE vs UNSUPPORTED)=UNDETERMINED"],
  "non_effects": [("AN ANNOTATION, NOT AN AMENDMENT; R-43's decision text is unchanged", "INVARIANT"), ("the WP-6 acceptance STANDS", "STATE-COORDINATE"),
                  ("does not determine whether the gate was ACTUALLY EXECUTED", "INVARIANT")], "guard_stated": "annotation permitted where decision text and history are not (ES-004.3)", "history": None},
 {"id": "R-66", "ops": ["ACCEPT", "CLOSE-WORK", "OPEN-WORK"], "m0_op": "GOV-CLOSE", "m0_map": "INTERPRETATION: 'WP-7 IS CLOSED' as governance closure",
  "changed": ["governance", "~acceptance(7C)", "~open(WP-7B-R1)"], "preserved": ["~deletion-mechanism", "~bounded-context ownership, context map, PL, UL, invariants", "~authorization(WP-8)"],
  "non_effects": [("how deletion is performed is unchanged", "INVARIANT"), ("no change to bounded-context ownership … strategic invariants", "INVARIANT"),
                  ("WP-8 requires formal definition and authorization before it begins", "RELATION"), ("release gated on C-2", "GUARD")], "guard_stated": "all eight R-65 requirements satisfied", "history": None},
 {"id": "R-81", "ops": ["AUTHORIZE", "LIFT-FREEZE(scoped)"], "m0_op": "REOPEN", "m0_map": "INTERPRETATION: freeze lifted, but 'FOR THIS REPAIR ONLY'",
  "changed": ["regime(scoped: this repair)", "~authorization(isolation slice)"], "preserved": ["regime(rest of Batch 7)=frozen", "~crash-model", "~R-76 scope", "~mechanism choice", "~decision-text"],
  "non_effects": [("adopts no crash model", "INVARIANT"), ("does not widen R-76", "RELATION"), ("does not choose the mechanism", "RELATION"),
                  ("DECISION TEXT above is unchanged", "INVARIANT")], "guard_stated": "evidence: defect under crash models A, B and C alike",
  "history": "PREPARED -> ADOPTED (R-86); 'the condition it imposed is discharged'"},
 {"id": "R-91", "ops": ["DETERMINE", "HOLD"], "m0_op": None, "changed": ["~evidence-status=CORRECT", "~reachability=LATENT", "~status=HELD"],
  "preserved": ["~decisions(ADR-T1, §Transaction, R-72, R-83, R-84)", "~authorization(repair)=none", "~crash-model set", "~WP-4D unauthorized", "~decision-text", "~number (not retired)"],
  "non_effects": [("an implementation that deviates from a sound decision is evidence about the IMPLEMENTATION, not about the decision", "INVARIANT"),
                  ("no repair is authorized", "RELATION"), ("R-83's crash-model set is NOT extended", "INVARIANT"),
                  ("HELD, not withdrawn … its number is not retired", "STATE-COORDINATE"),
                  ("Architecture governance answers 'is the decision still correct?'; execution governance answers 'who is authorized to repair'", "RELATION")],
  "guard_stated": "Event D separates evidence submission from constitutional review", "history": "R-90 WITHDRAWN + number RETIRED vs R-91 HELD + number kept"},
]
PRECISION = {  # per row: matched phrase class -> TRUE-FRAME (1) / FALSE (0), labelled after reading
 "R-47": {"REMAINS": 1, "IS-NOT": 1, "NOT-AUTHORIZE": 1},
 "R-53": {"UNCHANGED": 1, "REMAINS": 1, "IS-NOT": 1, "NOT-RESOLVE": 1},
 "R-66": {"UNCHANGED": 1, "NEITHER": 0},
 "R-81": {"DOES-NOT-CHANGE": 1, "UNCHANGED": 1, "RETAINED": 1, "WHAT-IT-CHANGES": 1},
 "R-91": {"DOES-NOT-CHANGE": 1, "UNCHANGED": 1, "REMAINS": 1, "IS-NOT": 1, "NOT-AUTHORIZE": 1, "RETAINED": 1, "WHAT-IT-CHANGES": 1}}


def fit(case):
    op = case["m0_op"]
    if op is None: return "NOT-MODELLED"
    frame = m0.FRAME[op]
    ch_m0 = [c.split("(")[0] for c in case["changed"] if not c.startswith("~")]
    pr_m0 = [c.split("(")[0] for c in case["preserved"] if not c.startswith("~")]
    scoped = any("(" in c and not c.startswith("~") for c in case["changed"])
    if any(c not in frame for c in ch_m0): return "FRAME-CONTRADICTION"
    if any(c in frame and not scoped for c in pr_m0): return "FRAME-CONTRADICTION"
    extra = [c for c in case["changed"] if c.startswith("~")]
    if scoped: return "PARTIAL-FIT (M0 coordinate is global; the source changes it for one scope only)"
    return "PARTIAL-FIT (non-M0 coordinates also change)" if extra else "FRAME-FIT"


def has_frame_structure(case):
    return bool(case["changed"]) and bool(case["preserved"] or case["non_effects"])


def ruling_status_markov(depth=5):
    """R-90 / R-91 extension of M0's P-markov: identifier-level status. Ops: ISSUE (unused->prepared), ADOPT (prepared|held->adopted),
    HOLD (prepared->held), WITHDRAW (prepared->withdrawn, number retired). Question: is 'may the number be issued / adopted' a
    function of a finite status? Compared summaries: (a) adopted? only; (b) status in {unused, prepared, adopted, held, withdrawn}."""
    T = {("unused", "ISSUE"): "prepared", ("prepared", "ADOPT"): "adopted", ("held", "ADOPT"): "adopted",
         ("prepared", "HOLD"): "held", ("prepared", "WITHDRAW"): "withdrawn"}
    per = {"adopted? only": {}, "5-valued status": {}}
    for n in range(depth + 1):
        for tr in itertools.product(("ISSUE", "ADOPT", "HOLD", "WITHDRAW"), repeat=n):
            s = "unused"; ok = True
            for op in tr:
                if (s, op) not in T: ok = False; break
                s = T[(s, op)]
            if not ok: continue
            en = frozenset(op for op in ("ISSUE", "ADOPT", "HOLD", "WITHDRAW") if (s, op) in T)
            per["adopted? only"].setdefault(s == "adopted", set()).add(en)
            per["5-valued status"].setdefault(s, set()).add(en)
    return {k: all(len(v) == 1 for v in d.values()) for k, d in per.items()}


def selftest():
    bad = dict(CASES[1], changed=["standing"])      # a CONTRA that changed standing must be a contradiction
    ok = [("R-53 fits CONTRA frame", fit(CASES[1]).startswith(("FRAME-FIT", "PARTIAL-FIT"))),
          ("mutation: CONTRA changing standing -> FRAME-CONTRADICTION", fit(bad) == "FRAME-CONTRADICTION"),
          ("unmodelled op is never a contradiction", fit(CASES[0]) == "NOT-MODELLED"),
          ("status markov: 5-valued suffices, adopted?-only does not", ruling_status_markov() == {"adopted? only": False, "5-valued status": True})]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    lab = [v for r in PRECISION.values() for v in r.values()]
    cls = {}
    for r in PRECISION.values():
        for k, v in r.items(): cls.setdefault(k, []).append(v)
    types = {}
    for c in CASES:
        for _, t in c["non_effects"]: types[t] = types.get(t, 0) + 1
    rep = {"labels": "SOURCE-FACT encodings; M0 mappings INTERPRETATION; computed values MODEL-DERIVED; sample selected on frame phrases (not representative)",
           "frame_structure": {c["id"]: has_frame_structure(c) for c in CASES},
           "m0_fit": {c["id"]: fit(c) for c in CASES},
           "stated_guards": {c["id"]: c["guard_stated"] for c in CASES},
           "non_effect_types (candidate, not decided)": types,
           "decision_text_immutability_occurrences": [c["id"] for c in CASES if any("decision-text" in p for p in c["preserved"])],
           "authorization_scope_bounded": [c["id"] for c in CASES if any("authorization" in p for p in c["preserved"])],
           "status_markov (R-90/R-91)": ruling_status_markov(),
           "lexical_precision": {"overall": f"{sum(lab)}/{len(lab)}", "per_class": {k: f"{sum(v)}/{len(v)}" for k, v in sorted(cls.items())},
                                 "contrast": "count-term locate (F-LOG-0120) 0/1 on R-88"}}
    print(json.dumps(rep, indent=1, ensure_ascii=False))
