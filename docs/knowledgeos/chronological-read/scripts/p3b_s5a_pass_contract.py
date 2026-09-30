#!/usr/bin/env python3
"""P3b S5a/S5b pass contract (§19.2 "The S5a/S5b passes get their own persisted contract"; deliverable O-13).
Infrastructure; G-LOG-0041. Assembles a binding preamble plus the §19.2-listed sections copied verbatim from v1.7.

  p3b_s5a_pass_contract.py        writes prompts/<stamp>_p3b-pass-contract.md (refuses if the pass plan is absent)
"""
import datetime
import importlib.util
import json
import os
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))


def _load(name):
    spec = importlib.util.spec_from_file_location(name, os.path.join(_HERE, name + ".py"))
    m = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(m)
    return m


c = _load("p3b_s5_common")
prep = _load("p3b_s5_prepare")
CELLS = _load("p3b_s5a_cells")
PASS_PLAN = "audit-p3b/S5A-PASS-PLAN.json"
SECTIONS = ["## 9D Research scales", "## 9E Cross-object and corpus-level research passes", "## 13.9a", "## 13.9b",
            "## 13.10 Disconfirmation discipline", "## 13.10a", "## 13.11", "## 14.4c", "# 20. QUALITY GATES"]

PREAMBLE = """# P3b PASS CONTRACT — S5a cross-object pass and S5b corpus pass

**Governing documents:** frozen core **v1.7** (sha256 `{protocol_sha}`), H-19 addendum v1.5 (sealed {seal}), approved
S5 plan v2.3.2 (sha256 `{plan_sha}`), the pass plan `{pass_plan}` (output_sha256 `{pp_sha}`; it must carry a human
approval entry in the governance log before any pass runs), governance log up to G-LOG-0045. The protocol text below
is verbatim; the core governs. **H-19 stays SEALED; S5c is PROHIBITED** (P3B-ESC-0001, G-LOG-0045): no pass unseals,
reads, scores or refers to hold-out material, and nothing in this contract authorizes S5c.

## A. Inputs and blinding (§9E.2 item 4)

1. **S5a analyst input: the blinded file `P3B-CROSS-BLIND.jsonl` only** (A.7; §9E.2 item 4). Each unit carries its
   `unit_key`, its member labels and their evidence pointers `{{source_id, row_line, anchor}}`; defining-link pointers are
   stripped. Every unit shows **exactly one pointer per member** (blinding annex M4-R1, scope A'1), and some sets are
   withheld from the file; neither carries information about role. Do not infer anything from how many pointers a
   unit shows. **You receive no object records, timelines, dependency edges, generator, role or defining property.** The
   reveal file is sealed until every unit is dispositioned (the reveal script refuses otherwise).
2. **Candidate-check reads:** at most **2 whole-file reads per analysed set**, only through
   `python3 docs/knowledgeos/chronological-read/scripts/p3b_read_source.py --run OA####-R2 --batch OA#### --label
   <set key> --step 10 S####`. No other file may be opened, listed or searched. No helper agents.
3. **Model:** {model} (context-window variants are the same model); record the exact served id and generation
   parameters on every record.

## B. What you record

1. **One disposition per blinded unit**, exactly in the schema below (`DISPOSITION_SCHEMA`, id
   `p3b-s5a-disposition-v1`; the engine validates it and refuses anything else): `disposition` ANALYSED | DEFERRED |
   FAILED; for ANALYSED, each of the 14 classes `RECORDED` (the class is recorded at ≥ ANALYSED with evidence) |
   `NOT-RECORDED` | `UNDETERMINED`. **A class you omit is UNDETERMINED, never NOT-RECORDED.** DEFERRED and FAILED need
   a `reason` and are undetermined for every class. UNDETERMINED is handled by symmetric removal (plan §H.4). Use the
   vocabulary below and nothing else; any other structure is `UNMAPPED` research content (§9E.2 item 11).
2. **ISC routing rule (binding):** {isc}
3. **CROSS-OBJECT and CORPUS findings** are register records with the §9E mandatory content (checked by G-13). Their
   `control_comparison` cites `cell_id` (a list for CORPUS) and `results_sha256` of `audit-p3b/S5A-CELL-RESULTS.json`;
   the generator, class and outcome must match the cited cell(s) (for CORPUS: the §9E.2 item 8 derivation). Findings
   never alter object records or P3a verdicts.
4. **Claim scope (v1.7 §23 item 3b):** no finding may rest on a hub label's absence resolution; absence-based
   statements cover the non-hub labels only (claims A/B, never C).
5. **No STATUS in the passes' analysis step.** TESTED and STATUS follow §13.9: every CORPUS record in S5b, others in
   the separate test pass (plan §C.2). Research records are discovery artifacts, not theory components; nothing is
   canonicalized and no theory is written (§1D, G-LOG-0028).

## C. Vocabulary (binding; plan §G.1)

```json
{vocab}
```

## D. Dispositions schema (binding; exported by the engine)

```json
{dschema}
```

---

# PROTOCOL TEXT (verbatim from the frozen core v1.7)

"""


def main():
    c.assert_sealed()
    c.verify_frozen()
    pp = json.load(open(os.path.join(c.CR, PASS_PLAN), encoding="utf-8"))
    plines = open(os.path.join(c.CR, c.PROTOCOL), encoding="utf-8").read().split("\n")
    parts = ["\n".join(prep.extract(plines, h)).rstrip() + "\n" for h in SECTIONS]
    text = PREAMBLE.format(protocol_sha=c.PROTOCOL_SHA256, seal=c.SEAL_ID, plan_sha=c.PLAN_SHA256, pass_plan=PASS_PLAN,
                           pp_sha=pp["header"]["output_sha256"], model=c.MODEL_ID, isc=pp["body"]["isc_routing_rule"],
                           vocab=json.dumps(pp["body"]["vocabulary"], indent=1, ensure_ascii=False),
                           dschema=json.dumps(CELLS.DISPOSITION_SCHEMA, indent=1, ensure_ascii=False)) + "\n".join(parts)
    rel = f"prompts/{datetime.datetime.now().strftime('%Y%m%d_%H%M')}_p3b-pass-contract.md"
    with open(os.path.join(c.CR, rel), "w", encoding="utf-8") as f:
        f.write(text)
    print(f"S5A PASS CONTRACT: {rel} sha256 {c.sha256_bytes(text.encode())[:16]} ({len(parts)} verbatim sections)")
    c.assert_sealed()
    return 0


if __name__ == "__main__":
    sys.exit(main())
