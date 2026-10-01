# Gate 1 (A′) return -02 — review (evidence only; no decision; A′ unchanged)

| | |
|---|---|
| Coder | fresh Claude subagent (Opus 5.5), bundle -02 only — **human decision replaced the non-Claude coder** (see COMMISSIONING.md deviation) |
| Isolation evidence | transcript tool calls: Read ×5 (the 5 bundle files only) · Write ×2 (CODING.json, NOTES.md). Bundle inputs re-hashed unchanged. |
| Seal | `RETURN-02.sha256`, committed 4bd42c3a5 before reading |
| Structure | valid JSON · 36 unique ids R01..R36 · none missing / extra |
| Frozen comparator | 178e86214, unchanged · output `RETURN-02-COMPARISON.json` |

## Frozen results (both REF-C and REF-CR identical)
- act.state: agreement 1.000, κ 1.000 (5 categories in the reference, 24/7/3/1/1)
- norm.deontic: agreement 1.000, κ 1.000 (8 categories)
- dataset membership: 36/36 (24 both · 7 NORM-only · 2 ACT-only · 3 neither); UNKNOWN share 0.083 (the 3 no-span records)
- REF-CR: 7 reviewer corrections target `*.status.*` fields and are not applied by the comparator (as designed), so REF-CR = REF-C on the measured fields.

## Supplementary (NOT frozen, written after opening; descriptive only)
- Epistemic status tag on the headline fields: act.state 29/36, norm.deontic 35/36. All disagreements lie on the **SOURCE_FACT ↔ INFERENCE boundary** (e.g. R01: same ACT_OCCURRED, one coder calls it SOURCE_FACT, the other INFERENCE). Classification: **schema** (§3 does not fix when mapping a source verb to a category is itself inference). None changes a category.
- Free-text fields (operation, actor, target, about_*, stated_by) exact-string match 11–23/36. Exact match understates semantic agreement; **no semantic comparison was done**. No claim is made about these fields.

## What this does and does not show
- **Shows:** a second, context-isolated Claude run reproduces the 1ax categorical coding exactly — within-family reproducibility of act.state / norm.deontic / membership on these 36 records is high.
- **Does not show:** cross-family independence. Shared model-family bias would produce exactly this result, so perfect agreement is **not** evidence against correlated error. Gate 1 proper (non-Claude coder) remains **unsatisfied**.
- A′ is not modified.

## Open for human decision
1. Accept this as a within-family check and close Gate 1 on that weaker basis, or still commission a non-Claude coder (bundle -02 stays valid).
2. Whether the SOURCE_FACT/INFERENCE boundary in SCHEMA-r2 §3 needs a rule (schema change → only via your decision).

> **Annotation (2026-10-01, F-LOG-0202):** the "Shows" line above ("within-family reproducibility … is high") is superseded by RETURN-03-CLAUDE (Sonnet 5.5: κ 0.50 / 0.62, schema-underdetermined). Read RETURN-02 as **same-model** (Opus 5.5) reproducibility only. See `RETURN-03-CLAUDE-REVIEW.md`. Original text above unchanged.
