# C04 — Structural reconstruction and structural analysis · v1.1

**Treatment:** §2 INHERITED-UNCHANGED (v3.5 P1 via the extraction contract XC, sha256 `013770d5…a9714993`, adapted per
protocol v1.0 §F-3 #7, #13). §3 F-SPECIFIC (Level 2, lens STRUCTURAL). **Gates:** `RECONSTRUCTED`, `ANALYZED`.

## 1. Order
Structural reconstruction consumes the content inventory; it never re-reads the file for new content. If
reconstruction finds content missing from the inventory, the file goes back: `AUDIT-FAILED` → re-enter at
`CONTENT-EXTRACTED`, never a silent addition.

## 2. Structural reconstruction — XC STEPS 2–12 (Level 1)
Apply XC as written, with the v1.0 adaptations (`f_id`, `content_sha256`, `read_run`, `order_evidence` incl.
`LIST-POSITION`, `content_identical_to_s`, `page`, `first_seen_in_f`). Additionally, every contribution carries
`inventory_refs[]`, and **every inventory item is carried by ≥ 1 contribution** (the gate refuses otherwise).

## 3. Structural analysis (Level 2, lens STRUCTURAL)
Questions: how is the file built? One document or several concatenated responses? Argument structure (claim →
support → conclusion)? Section hierarchy and what each part answers? Internal dependency order of its definitions?
What is announced but never delivered? Records go to `ANALYSIS.jsonl` (schema in C06 §2) with `lens: "STRUCTURAL"`.

## 4. Lens checklist — `ANALYSIS-CHECKLIST.json`
```json
{"STRUCTURAL":{"status":"APPLIED|NOT-APPLICABLE","reason":"…"},
 "MATHEMATICAL":{…},"LOGICAL":{…},"DDD":{…},
 "STATISTICAL-ML":{"status":"DEFERRED-TO-CHECKPOINT|NOT-APPLICABLE|APPLIED","reason":"…"}}
```
APPLIED requires ≥ 1 record with that lens; a lens with records must be APPLIED; only STATISTICAL-ML may be
DEFERRED-TO-CHECKPOINT. NOT-APPLICABLE needs a reason grounded in the file (e.g. "no formula, no formal claim").
