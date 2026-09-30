#!/usr/bin/env python3
"""P3b S5a/S5b G-13 pass verifier for CROSS-OBJECT and CORPUS register records (frozen protocol v1.7 §9E mandatory
content, §9E.2 items 8-9; S5 plan v2.3.2 §H.4 hub rule, §H.7 G-13 addition; O-17 + O-24; G-LOG-0041).

INFRASTRUCTURE ONLY. Building and testing this script is authorized (G-LOG-0041); executing S5a/S5b is not.

Checks per record (scale CROSS-OBJECT or CORPUS):
  1. mandatory §9E content present (CORPUS: plus participating_findings, underlying_objects, independent_occurrences,
     exceptions, explanatory_content); every participating object carries source evidence (S-id + anchor), its
     acceptance state at research time and its tier;
  2. generator_basis.basis equals the pre-registered (generator x class) mapping (CONTROL for control-set findings);
  3. DISCOVERY: control_comparison.cell_id is a registered cell id (CROSS) or a list of them (CORPUS);
     DESCRIPTIVE: control_comparison is NOT-APPLICABLE and cites no cell;
  4. control_comparison.results_sha256 equals the output_sha256 of S5A-CELL-RESULTS.json (whose body re-hashes to it);
  5. generator and structure class equal the cited cell's (CORPUS: every cited cell has the record's class, and the
     cited list is exactly the DISCOVERY cells of the participating findings' generators);
  6. outcome: CROSS = the cell's outcome; CORPUS = the §9E.2 item 8 derivation in the frozen outcome vocabulary
     (see corpus_derivation: DIFFERENTIATED iff >= 1 contributing DISCOVERY generator and every contributing DISCOVERY
     cell is DIFFERENTIATED; otherwise UNDIFFERENTIATED if all were tested, else the most restrictive component status
     REPORT-ONLY-PARTIAL-BLIND > UNDERPOWERED > UNDIFFERENTIATED; NOT-APPLICABLE with no DISCOVERY generator);
  7. no finding cites a hub's absence resolution as basis (§23 item 3b; plan §H.4).

The register record schema used here is the one this verifier defines for the S5a/S5b pass contract (O-13, not yet
written): see REQUIRED_* below.

  python3 p3b_s5a_g13.py --register REGISTER.jsonl --results S5A-CELL-RESULTS.json      (exit 1 on any violation)
"""
import argparse
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)
import p3b_s5_common as C  # noqa: E402
import p3b_s5a_cells as CELLS  # noqa: E402

REQUIRED_CROSS = ("participating_objects", "derived_from_records", "common_structure", "differences",
                  "competing_explanation", "disconfirmation", "falsification_condition", "temporal_scope",
                  "generator_basis", "structure_class", "control_comparison", "research_status")
REQUIRED_CORPUS_EXTRA = ("participating_findings", "underlying_objects", "independent_occurrences", "exceptions",
                         "explanatory_content")
ABSENCE_KINDS = ("ABSENCE", "ABSENCE-RESOLUTION", "ABSENCE-DIMENSION")


def _present(rec, k):
    v = rec.get(k)
    if k in ("differences", "exceptions"):
        return v is not None                              # may legitimately be empty, but must be stated
    return v not in (None, "", [], {})


def _cites_hub_absence(obj, hubs):
    """True if any item cites a hub's absence resolution: a dict marked ABSENCE* (kind/record_type) or carrying
    absence_dimension/absence_resolution for a hub label, or a string reference 'absence...' naming a hub."""
    if isinstance(obj, dict):
        lab = obj.get("working_label") or obj.get("label")
        marked = (str(obj.get("kind", "")).upper() in ABSENCE_KINDS or str(obj.get("record_type", "")).upper() in ABSENCE_KINDS
                  or "absence_dimension" in obj or "absence_resolution" in obj or "absences" in obj)
        if marked and lab in hubs:
            return True
        return any(_cites_hub_absence(v, hubs) for v in obj.values())
    if isinstance(obj, list):
        return any(_cites_hub_absence(v, hubs) for v in obj)
    if isinstance(obj, str) and "absence" in obj.lower():
        return any(re.search(r"(?<![A-Za-z0-9._-])" + re.escape(h) + r"(?![A-Za-z0-9._-])", obj) for h in hubs)
    return False


RESTRICTIVENESS = ("REPORT-ONLY-PARTIAL-BLIND", "UNDERPOWERED", "UNDIFFERENTIATED")   # most restrictive first


def corpus_derivation(cls, contributing_generators, cell_outcomes):
    """§9E.2 item 8, in the frozen outcome vocabulary (§9E.2 item 6):
      * no contributing DISCOVERY generator (all DESCRIPTIVE) -> NOT-APPLICABLE (no cell is cited);
      * DIFFERENTIATED iff every contributing DISCOVERY cell is DIFFERENTIATED;
      * otherwise UNDIFFERENTIATED if every contributing DISCOVERY cell was tested (DIFFERENTIATED/UNDIFFERENTIATED);
        else the most restrictive component status: REPORT-ONLY-PARTIAL-BLIND, then UNDERPOWERED, then UNDIFFERENTIATED
        (the §9E.2 item 6 precedence PARTIAL > UNDERPOWERED > test result)."""
    disc = [g for g in CELLS.GENERATORS if g in set(contributing_generators) and CELLS.basis(g, cls) == "DISCOVERY"]
    cells = [f"{g}:{cls}" for g in disc]
    if not disc:
        return "NOT-APPLICABLE", cells
    outs = [cell_outcomes.get(c) for c in cells]
    if all(o == "DIFFERENTIATED" for o in outs):
        return "DIFFERENTIATED", cells
    if all(o in ("DIFFERENTIATED", "UNDIFFERENTIATED") for o in outs):
        return "UNDIFFERENTIATED", cells
    for status in RESTRICTIVENESS:
        if status in outs:
            return status, cells
    raise C.S5Error(f"cited cell outcomes outside the frozen vocabulary: {outs}")


def verify(register, results_header, results_body, hubs):
    """Returns a list of violations {rs_id, check, detail}. results_* is the parsed S5A-CELL-RESULTS.json."""
    viol = []
    body_sha = C.sha256_bytes(json.dumps(results_body, indent=1, sort_keys=True, ensure_ascii=False).encode("utf-8"))
    out_sha = results_header.get("output_sha256")
    if body_sha != out_sha:
        viol.append({"rs_id": None, "check": "results-artifact", "detail": "results body does not re-hash to output_sha256"})
    cells = {c["cell_id"]: c for c in results_body.get("cells", [])}
    registered = set(c["cell_id"] for c in CELLS.registered_cells())
    by_id = {r.get("rs_id"): r for r in register}
    hubs = frozenset(hubs)
    for rec in register:
        scale = rec.get("scale")
        if scale not in ("CROSS-OBJECT", "CORPUS"):
            continue
        rid = rec.get("rs_id")

        def bad(check, detail):
            viol.append({"rs_id": rid, "check": check, "detail": detail})
        req = REQUIRED_CROSS + (REQUIRED_CORPUS_EXTRA if scale == "CORPUS" else ())
        for k in req:
            if not _present(rec, k):
                bad("mandatory-content", f"missing {k}")
        for po in rec.get("participating_objects") or []:
            ev = po.get("source_evidence") or []
            if not ev or not all(isinstance(e, dict) and re.fullmatch(r"S\d{4}", str(e.get("source_id", ""))) and e.get("anchor")
                                 for e in ev):
                bad("mandatory-content", f"participant {po.get('working_label')} lacks S-id + anchor evidence")
            for k in ("acceptance_state", "tier"):
                if not po.get(k):
                    bad("mandatory-content", f"participant {po.get('working_label')} lacks {k}")
        gb = rec.get("generator_basis") or {}
        gen, cls, cc = gb.get("generator"), rec.get("structure_class"), rec.get("control_comparison")
        if _cites_hub_absence(rec.get("derived_from_records"), hubs) or _cites_hub_absence(rec.get("participating_objects"), hubs) \
                or _cites_hub_absence(rec.get("basis"), hubs):
            bad("hub-absence", "cites a hub's absence resolution as basis (§23 item 3b)")
        if gb.get("basis") == "CONTROL":
            if isinstance(cc, dict) and cc.get("outcome") == "DIFFERENTIATED":
                bad("control-finding", "a control-set finding cannot carry a DIFFERENTIATED comparison")
            continue
        if scale == "CROSS-OBJECT":
            self_derived = gen in ("G-TOPIC", "G-HYP")      # never counted; no cell exists
            expected = CELLS.basis(gen, cls) if gen in CELLS.GENERATORS else gb.get("basis") if self_derived else None
            if expected != "UNMAPPED" and gb.get("basis") != expected:
                bad("mapping", f"generator_basis {gb.get('basis')} != pre-registered mapping {expected}")
            if expected in ("DESCRIPTIVE", "UNMAPPED") or self_derived:
                if cc != "NOT-APPLICABLE":
                    bad("control-comparison", "DESCRIPTIVE / UNMAPPED / SELF-DERIVED finding must carry NOT-APPLICABLE "
                                              "and cite no cell")
                continue
            if not isinstance(cc, dict) or not isinstance(cc.get("cell_id"), str):
                bad("cell-id", "DISCOVERY CROSS-OBJECT finding must cite one cell_id")
                continue
            cid = cc["cell_id"]
            if cid not in registered or cid not in cells:
                bad("cell-id", f"cell {cid} is not a registered cell of the results")
                continue
            if cc.get("results_sha256") != out_sha:
                bad("results-sha256", "results_sha256 != output_sha256 of S5A-CELL-RESULTS.json")
            cell = cells[cid]
            if cell["generator"] != gen or cell["class"] != cls:
                bad("generator-class", f"record ({gen}, {cls}) != cell ({cell['generator']}, {cell['class']})")
            if cc.get("outcome") != cell.get("outcome"):
                bad("outcome", f"claimed {cc.get('outcome')} != cell outcome {cell.get('outcome')}")
        else:
            contributing = []
            for fid in rec.get("participating_findings") or []:
                f = by_id.get(fid)
                if f is None:
                    bad("participating-findings", f"{fid} not in the register")
                    continue
                contributing.append((f.get("generator_basis") or {}).get("generator"))
            if cc == "NOT-APPLICABLE":
                derived, _ = corpus_derivation(cls, contributing, {})
                if derived != "NOT-APPLICABLE":
                    bad("control-comparison", "CORPUS finding with a contributing DISCOVERY generator must cite its cells")
                continue
            if not isinstance(cc, dict) or not isinstance(cc.get("cell_id"), list):
                bad("cell-id", "CORPUS finding must cite a list of cell_ids")
                continue
            if cc.get("results_sha256") != out_sha:
                bad("results-sha256", "results_sha256 != output_sha256 of S5A-CELL-RESULTS.json")
            for cid in cc["cell_id"]:
                if cid not in registered or cid not in cells:
                    bad("cell-id", f"cell {cid} is not a registered cell of the results")
                elif cells[cid]["class"] != cls:
                    bad("generator-class", f"cited cell {cid} has class {cells[cid]['class']} != {cls}")
            derived, want_cells = corpus_derivation(cls, contributing, {k: v.get("outcome") for k, v in cells.items()})
            if sorted(cc["cell_id"]) != sorted(want_cells):
                bad("generator-class", f"cited cells {sorted(cc['cell_id'])} != DISCOVERY cells of contributing generators {want_cells}")
            if cc.get("outcome") != derived:
                bad("outcome", f"claimed {cc.get('outcome')} != §9E.2 item 8 derivation {derived}")
    return viol


def main(argv=None):
    import p3b_s5a_controls as K
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    ap.add_argument("--register", required=True)
    ap.add_argument("--results", required=True)
    a = ap.parse_args(argv)
    C.assert_sealed()
    rpath = K.check_artifact_path(a.register)
    with open(rpath, encoding="utf-8") as f:
        reg = [json.loads(l) for l in f if l.strip()]
    reg = [r for r in reg if "header" not in r]
    h, body = K.read_json_artifact(a.results, verify=False)
    v = verify(reg, h, body, C.hubs())
    C.assert_sealed()
    print(json.dumps({"records_checked": sum(1 for r in reg if r.get("scale") in ("CROSS-OBJECT", "CORPUS")),
                      "violations": v, "g13": "PASS" if not v else "FAIL"}, indent=1))
    return 0 if not v else 1


if __name__ == "__main__":
    sys.exit(main())
