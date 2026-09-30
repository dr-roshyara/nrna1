"""T-A deterministic aggregation (pre-registration r3, sections 3.0, 4, 5.0, 5.1, 5; checklist steps 8, 9a).

Validates ta_evidence records, applies the precedence-ordered per-question verdict (5.0), the axiom ->
proposition map (5.1), and the candidate-level precedence (5), and computes expected_finding_matched
AFTER classification (anti-anchoring, 4). Counts only: no probabilities, rates or intervals (5, 11).

No corpus input exists yet: this tool is run only after an authorized release. Standard library only.
Usage: python3 aggregate.py --scope {UNIVERSAL,MECHANISM_CLASS,NAMED_MECHANISM} --released M-1,M-4 records.jsonl
Without --scope it exits 2 (HD-S fixed the scope as UNIVERSAL; it must still be passed explicitly).
"""
from __future__ import annotations

import argparse
import json
import sys

AXIOM_OF = {"F-A2e": "A2e", "F-A3g": "A3g", "F-A4": "A4", "F-A5e": "A5e", "F-A5g": "A5g",
            "F-A6": "A6", "F-A0": "A0", "F-A3m": "A3m"}
INTERPRETIVE = ("Q-H6", "Q-GS", "Q-D4")
TESTS = tuple(AXIOM_OF) + INTERPRETIVE
CORE = ("A2e", "A3g", "A4", "A5e", "A5g", "A6")
# 5.1 single-removal map (computed identically by model.py and verifier.py on chain3 / V / diamond).
PROPS_OF = {"A2e": ["D1", "D3", "D3+"], "A3g": ["D2", "D3", "D3+", "D5"], "A4": ["D3", "D3+"],
            "A5e": ["D3", "D3+"], "A5g": ["D2", "D3", "D3+"], "A6": ["D1", "D3", "D3+", "D5"],
            "A0": ["D3+", "D5"], "A3m": ["D3+", "D5"]}
# Section 2 "why needed" column. A question is NOT_RUN only if none of its materials is released.
# F-A3m is not named in section 2; it shares the F-A0/F-A3m question, so it takes M-1 and M-4 (derived).
MATERIAL = {"F-A2e": {"M-1"}, "F-A3g": {"M-1"}, "F-A4": {"M-1"}, "F-A5e": {"M-1"}, "F-A5g": {"M-1"},
            "F-A6": {"M-4"}, "F-A0": {"M-4"}, "F-A3m": {"M-1", "M-4"},
            "Q-H6": {"M-1", "M-3"}, "Q-GS": {"M-2", "M-3"}, "Q-D4": {"M-2"}}
# 3.2 predictions translated to 5.0 verdicts (a translation of the prose, confirmed or rejected at HD-1).
PREDICTED = {"F-A2e": {"SUPPORTED"}, "F-A3g": {"SUPPORTED", "NOT_EVIDENCED"}, "F-A4": {"INCONCLUSIVE"},
             "F-A5e": {"INCONCLUSIVE"}, "F-A5g": {"NOT_EVIDENCED"},
             "F-A6": {"INCONCLUSIVE", "COUNTEREXAMPLE_FOUND"},
             "F-A0": {"SUPPORTED", "NOT_EVIDENCED"}, "F-A3m": {"SUPPORTED", "NOT_EVIDENCED"}}
CLASSES = ("DIRECT_SUPPORT", "DIRECT_COUNTEREXAMPLE", "AMBIGUOUS", "NOT_EVIDENCED")
COMPETING = ("DIRECT_SUPPORT", "DIRECT_COUNTEREXAMPLE", "NOT_BEARING")
REQUIRED = ("test", "source_id", "source_version", "location", "original_wording",
            "reconstructed_interpretation", "classification", "mechanism_ref", "confidence",
            "reader", "independence_class", "discovery_channel")
SCOPES = ("UNIVERSAL", "MECHANISM_CLASS", "NAMED_MECHANISM")  # HD-S (r3) = UNIVERSAL; others kept for tests

# r3 (pre-registration 3.0): operation typing. Kind-free tests need no typing.
KINDS = ("GOV", "EVID", "EVIDREF", "WORK", "COMP")
KIND_FREE = ("F-A6", "F-A0")
TYPING_BASES = ("SOURCE_DECLARED", "ACTION_SEMANTICS", "UNKNOWN")  # never actor, object, lexicon or effect
# 3.0 (g): the kind a counterexample must have, and the effect component it must state.
CE_KIND = {"F-A2e": {"GOV"}, "F-A3g": {"EVID", "EVIDREF"}, "F-A4": {"COMP"}, "F-A5e": {"WORK"},
           "F-A5g": {"WORK"}, "F-A3m": {"EVID", "EVIDREF"}, "F-A6": None, "F-A0": None}
CE_COMPONENT = {"F-A2e": ("evidential",), "F-A3g": ("grant",), "F-A5e": ("evidential",), "F-A5g": ("grant",),
                "F-A3m": ("evidential",), "F-A6": ("bar",), "F-A0": ("bar",)}
OUTCOME_TO_CLASS = {"DIRECT_SUPPORT": "DIRECT_SUPPORT", "DIRECT_COUNTEREXAMPLE": "DIRECT_COUNTEREXAMPLE",
                    "NOT_BEARING": "NOT_EVIDENCED"}


def validate_typing(r, i):
    """3.0 (a)-(g): operation block, effect block, typing order, SP-S, UNKNOWN enumeration, counterexample form."""
    t, c = r.get("test"), r.get("classification")
    if t not in AXIOM_OF: return []  # interpretive questions carry no typing requirement
    op, ef = r.get("operation"), r.get("effect")
    if not isinstance(op, dict) or not isinstance(ef, dict):
        return [f"record {i}: operation and effect blocks are required (3.0 a)"]
    err = []
    if not op.get("action"): err.append(f"record {i}: operation.action is required")
    if op.get("typing_recorded_before_effect") is not True:
        err.append(f"record {i}: typing must be recorded before the effect (3.0 a)")
    kind, basis = op.get("assigned_kind"), op.get("typing_basis")
    if kind not in KINDS + ("UNKNOWN",): err.append(f"record {i}: bad assigned_kind {kind!r}")
    if basis not in TYPING_BASES:
        err.append(f"record {i}: typing_basis {basis!r} not allowed (never actor, object, lexicon or effect)")
    if (kind == "UNKNOWN") != (basis == "UNKNOWN"):
        err.append(f"record {i}: assigned_kind UNKNOWN iff typing_basis UNKNOWN")
    if basis == "SOURCE_DECLARED" and not op.get("source_declared_type"):
        err.append(f"record {i}: SOURCE_DECLARED needs source_declared_type")
    if basis == "ACTION_SEMANTICS" and not op.get("typing_justification"):
        err.append(f"record {i}: ACTION_SEMANTICS needs typing_justification")
    split = op.get("split_basis", "NONE")
    if split not in ("NONE", "SOURCE_EXPLICIT"): err.append(f"record {i}: split_basis {split!r} not allowed (SP-S)")
    if split == "SOURCE_EXPLICIT" and not op.get("split_quote"):
        err.append(f"record {i}: SOURCE_EXPLICIT split needs split_quote")
    if kind == "UNKNOWN":
        if not op.get("ambiguity_reason"): err.append(f"record {i}: UNKNOWN needs ambiguity_reason")
        if t not in KIND_FREE:
            adm, obk = op.get("admissible_kinds") or [], op.get("outcome_by_kind") or {}
            if len(adm) < 2 or not set(adm) <= set(KINDS) or set(obk) != set(adm) \
                    or not set(obk.values()) <= set(OUTCOME_TO_CLASS):
                err.append(f"record {i}: UNKNOWN needs >= 2 admissible_kinds and an outcome for each (3.0 e)")
            else:
                outs = set(obk.values())
                if len(outs) == 1 and c != OUTCOME_TO_CLASS[outs.pop()]:
                    err.append(f"record {i}: equal outcomes over admissible kinds fix the classification (3.0 e)")
                elif len(set(obk.values())) > 1:
                    worst = "DIRECT_COUNTEREXAMPLE" if "DIRECT_COUNTEREXAMPLE" in obk.values() else \
                        ("DIRECT_SUPPORT" if "DIRECT_SUPPORT" in obk.values() else "NOT_BEARING")
                    if c != "AMBIGUOUS" or r.get("competing_classification") != worst:
                        err.append(f"record {i}: differing outcomes require AMBIGUOUS with the most adverse "
                                   f"competing_classification {worst} (3.0 e)")
    if c == "DIRECT_COUNTEREXAMPLE":
        need = CE_KIND[t]
        kinds = set(op.get("admissible_kinds") or []) if kind == "UNKNOWN" else {kind}
        if need is not None and not (kinds and kinds <= need):
            err.append(f"record {i}: a {t} counterexample needs kind {sorted(need)}, not {kind} (3.0 g)")
        if t == "F-A4":
            if sum(bool(ef.get(k)) for k in ("evidential", "grant", "standing")) < 2:
                err.append(f"record {i}: an F-A4 counterexample must state effects on >= 2 components (3.0 g)")
        elif not all(ef.get(k) for k in CE_COMPONENT[t]):
            err.append(f"record {i}: a {t} counterexample must state the {CE_COMPONENT[t]} effect (3.0 g)")
    return err


def validate(r, i):
    err = [f"record {i}: missing {k}" for k in REQUIRED if r.get(k) in (None, "")]
    t, c = r.get("test"), r.get("classification")
    if t not in TESTS: err.append(f"record {i}: unknown test {t!r}")
    if c not in CLASSES: err.append(f"record {i}: unknown classification {c!r}")
    if r.get("confidence") not in ("HIGH", "MEDIUM", "LOW"): err.append(f"record {i}: bad confidence")
    if r.get("independence_class") not in ("SELF", "SECONDARY_REVIEW", "INDEPENDENT"):
        err.append(f"record {i}: bad independence_class")
    if r.get("discovery_channel") not in ("COMPLETE_READING", "R1", "R2", "R3", "R4"):
        err.append(f"record {i}: bad discovery_channel")
    if r.get("expected_finding_matched") is not None:
        err.append(f"record {i}: expected_finding_matched must not be filled by the reader (4, r2)")
    if c == "AMBIGUOUS":
        if not r.get("competing_interpretation"): err.append(f"record {i}: AMBIGUOUS needs competing_interpretation")
        if r.get("competing_classification") not in COMPETING:
            err.append(f"record {i}: AMBIGUOUS needs competing_classification in {COMPETING}")
    if c == "DIRECT_COUNTEREXAMPLE":
        if r.get("countermodel_effect_stated") not in (True, False):
            err.append(f"record {i}: DIRECT_COUNTEREXAMPLE needs countermodel_effect_stated true/false")
        eff = r.get("effect_instantiates") or []
        if eff and not r.get("countermodel_effect_stated"):
            err.append(f"record {i}: effect_instantiates requires countermodel_effect_stated = true")
        if t in AXIOM_OF and not set(eff) <= set(PROPS_OF[AXIOM_OF[t]]):
            err.append(f"record {i}: effect_instantiates {eff} not within 5.1 row {PROPS_OF[AXIOM_OF[t]]}")
    if t == "F-A6" and r.get("a6_level") not in ("OBJECT_LEVEL", "META_LEVEL", "NEITHER_OR_UNCLEAR"):
        err.append(f"record {i}: F-A6 needs a6_level")
    return err + validate_typing(r, i)


def counts(rs):
    c = {k: 0 for k in ("N", "S", "C", "A", "U")}
    for r in rs:
        c["N"] += 1
        c[{"DIRECT_SUPPORT": "S", "DIRECT_COUNTEREXAMPLE": "C", "AMBIGUOUS": "A",
           "NOT_EVIDENCED": "U"}[r["classification"]]] += 1  # record-level NOT_EVIDENCED counts as U (5, r2)
    return c


def verdict(rs, run):
    """5.0 precedence: first matching rule decides."""
    if not run: return "NOT_RUN"
    c = counts(rs)
    if c["C"] >= 1: return "COUNTEREXAMPLE_FOUND"
    if any(r["classification"] == "AMBIGUOUS" and r["competing_classification"] == "DIRECT_COUNTEREXAMPLE"
           for r in rs): return "INCONCLUSIVE"
    if c["S"] >= 1: return "SUPPORTED"
    if c["A"] >= 1: return "INCONCLUSIVE"
    return "NOT_EVIDENCED"


def aggregate(records, released, scope):
    errs = [e for i, r in enumerate(records) for e in validate(r, i)]
    if errs: return {"valid": False, "errors": errs}
    out = {"valid": True, "scope": scope, "released": sorted(released), "questions": {}}
    for t in TESTS:
        rs = [r for r in records if r["test"] == t]
        run = bool(MATERIAL[t] & released)
        q = {"run": run, "counts": counts(rs) if run else None}
        if t in AXIOM_OF:
            q["verdict"] = verdict(rs, run)
            mechs = sorted({r["mechanism_ref"] for r in rs})
            q["per_mechanism"] = {m: verdict([r for r in rs if r["mechanism_ref"] == m], run) for m in mechs}
            q["expected_finding_matched"] = (q["verdict"] in PREDICTED[t]) if run else None
            unk = sum(1 for r in rs if r["operation"].get("assigned_kind") == "UNKNOWN")
            q["coverage"] = {"N_typed": len(rs) - unk, "N_unknown": unk} if run else None  # 3.0 (h)
        else:  # interpretive: counts per reading, no verdict
            q["readings"] = sorted({r["reconstructed_interpretation"] for r in rs})
        out["questions"][t] = q
    # 5.1 propositions
    props = {}
    for t, ax in AXIOM_OF.items():
        q = out["questions"][t]
        if q["verdict"] == "COUNTEREXAMPLE_FOUND":
            for m, v in q["per_mechanism"].items():
                if v != "COUNTEREXAMPLE_FOUND": continue
                for p in PROPS_OF[ax]:
                    props.setdefault(p, {}).setdefault(m, "UNSUPPORTED")
                for r in records:
                    if r["test"] == t and r["mechanism_ref"] == m and r["classification"] == "DIRECT_COUNTEREXAMPLE":
                        for p in r.get("effect_instantiates") or []:
                            props[p][m] = "FALSIFIED"
    out["propositions"] = props
    # 5 candidate-level precedence
    core = {ax: out["questions"][t] for t, ax in AXIOM_OF.items() if ax in CORE}
    ce = [ax for ax, q in core.items() if q["verdict"] == "COUNTEREXAMPLE_FOUND"]
    if ce:
        if scope == "UNIVERSAL":
            h = "REFUTED_AS_STATED"
        else:
            all_mech = all(v == "COUNTEREXAMPLE_FOUND" for ax in ce for v in core[ax]["per_mechanism"].values())
            h = "REFUTED_AS_STATED" if all_mech else "WEAKENED"
    elif any(q["verdict"] == "INCONCLUSIVE" for q in core.values()):
        h = "INCONCLUSIVE"
    elif any(q["verdict"] in ("NOT_EVIDENCED", "NOT_RUN") for q in core.values()):
        h = "PARTIALLY_UNTESTED"
    else:
        h = "SURVIVES_T-A"
    out["H-F2-1a"] = h
    out["axioms_counterexampled"] = ce
    if "A6" in ce:
        out["A6_replacement_mechanism"] = "UNRESOLVED (characterize from evidence; >= 2 alternatives; re-check rule)"
    ext = [out["questions"][t]["verdict"] for t in ("F-A0", "F-A3m")]
    out["stability_extension"] = "REFUTED_AS_STATED" if "COUNTEREXAMPLE_FOUND" in ext else "NOT_REFUTED_IN_T-A"
    return out


def main(argv=None):
    ap = argparse.ArgumentParser()
    ap.add_argument("records")
    ap.add_argument("--scope", choices=SCOPES)
    ap.add_argument("--released", default="")
    a = ap.parse_args(argv)
    if a.scope is None:
        print("STOP: --scope is required (HD-S, pre-registration r3 3.0: UNIVERSAL); no verdict computed.",
              file=sys.stderr)
        return 2
    with open(a.records, encoding="utf-8") as f:
        records = [json.loads(line) for line in f if line.strip()]
    res = aggregate(records, {x.strip() for x in a.released.split(",") if x.strip()}, a.scope)
    json.dump(res, sys.stdout, indent=1, sort_keys=True, ensure_ascii=False)
    print()
    return 0 if res["valid"] else 1


if __name__ == "__main__":
    sys.exit(main())
