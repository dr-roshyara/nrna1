# S5a blinding annex — M4-R1 scope revision A'1 (§26 controlled change)

**Type:** change-controlled annex under frozen core protocol v1.7 §26.

**Amends:**
- the S5a blinding procedure of S5 plan v2.3.2 (`audit-p3b/20260925_0106_s5-plan-v2.3.md`, §H);
- pass plan O-12;
- as implementation of frozen §9E.2 item 4 and Appendix A.7.

**Supersedes:** the G-SHARED-GROUP-only scope of the M4 residual rule (human ruling "M4 residual = A", report `audit-p3b/20260925_0425_m4a-residual-blinding-analysis.md` §7).

**Decision:** human ruling "M4-R1 scope decision: APPROVE A'1" (H-01 class, §26.2), 2026-09-25. It will be recorded in `P3B-GOVERNANCE-LOG.md` with the evidence below, as §26.1 requires.

**Unchanged:** the frozen protocol text, K = 64, the registered 64-cell family, the test, the multiplicity procedure, the control draw and the generator definitions.

## 1. Rule (A'1)

1. **M4-A (unchanged).** In every blind unit with a G-SHARED-GROUP candidate or control role, evidence pointers to a source cited by two or more of the unit's members are stripped.
2. **M4-R1, scope A'1.** For **every blind unit that carries a candidate or control role of any generator**:
   - if any member has an empty evidence list, the whole unit is **withheld**. It is not written to the blind file, and it is removed symmetrically (cause `WITHHELD-M4R1`) from every cell that contains it, before any hub or RC-13 unscoring;
   - otherwise each member shows **exactly one** evidence pointer, the lowest `(source_id, row_line)`.
3. The rule is applied identically to candidate and control units. Whether it applies never depends on which of the two roles a unit holds.
4. The rule is decided on the blinded representation before any disposition, analysis or outcome exists.
5. Units with only a SELF-DERIVED role are outside the rule.

**Identity** (`p3b_s5a_controls.m4r1_record`, approval check `check_m4r1_binding`):

| Field | Value |
|---|---|
| `rule` | `R1 + cap 1` |
| `cap` | `1` (integer) |
| `scope` | `all candidate/control units across all generators` |
| `withheld_units` | produced with the withheld set |
| `withheld_sets_sha256` | produced with the withheld set |
| `canonicalization` | sha256 over UTF-8 of `p3b_s5_common.canon` (`json.dumps` with `sort_keys=True`, `separators=(',', ':')`, `ensure_ascii=False`) of the sorted list of withheld member lists |

The identity is written to four places:
- the blind header (`P3B-CROSS-BLIND.jsonl`);
- the reveal header;
- the pass-record entries PRE-ANALYSIS-BLIND-REVEAL and LINK6-POOLS-PREREVEAL.

Any mismatch between them is a hard failure, and so is any rule, cap or scope other than the approved identity.

## 2. Why the G-SHARED-GROUP-only scope was rejected

All measurements below use the pre-S5 design data (`scripts/p3b_s5a_m4_residual.py`), with no S5 outcomes. AUC = max(AUC, 1 − AUC) of a visible feature between candidate and control units.

- Applied to G-SHARED-GROUP units only, the one-pointer cap makes membership of the G-SHARED-GROUP family visible. "Every member shows exactly one pointer" separates family units from all others with AUC 0.994.
- 80 of 87 G-NOTATION candidate units (92%) are family units, against 4 of 149 G-NOTATION control units (3%).
- Family visibility therefore becomes a G-NOTATION role cue: **`total_ptrs` AUC 0.896** (mean pointers per unit 6.0 for candidates, 45.2 for controls).
- The cap causes it: the cap alone gives 0.907, while R1 alone gives 0.540.
- The same mechanism would reach any generator whose candidate units overlap the family more than its control units do.
- Independent reproduction with separately written code: **REPRODUCED WITH OBSERVATIONS**. Every number matched.

## 3. Measurements under A'1 (design data)

| Generator | Candidate / control units | Best AUC before (M4-A only) | Best AUC under A'1 |
|---|---|---|---|
| G-SHARED-GROUP | 530 / 1,018 → 429 / 982 | 0.601 (`min_ptrs`) | 0.502 |
| G-NOTATION | 87 / 149 → 77 / 137 | 0.567 (`distinct_sources`) | 0.510 |
| G-TYPE-SIM | 300 / 565 → 300 / 565 | 0.616 (`total_ptrs`) | 0.506 |

**Withheld: 149 distinct units** (G-SHARED-GROUP family only: 137).

| Generator: role | Units withheld |
|---|---|
| G-SHARED-GROUP candidates | 101 |
| G-SHARED-GROUP controls | 36 |
| G-NOTATION candidates | 10 |
| G-NOTATION controls | 12 |
| G-TYPE-SIM | 0 |

A unit with several roles counts in each. G-DEPENDENCY and G-COCHANGE have no design data and are measured at the pre-release gate (§5).

## 4. Consequences

- **Population:** the withheld units leave every cell that contains them. The size `m` of the SHARED-INVARIANT cells changes as follows (M4-A only → A'1):

  | Generator | m |
  |---|---|
  | G-SHARED-GROUP | 262 → 217 |
  | G-NOTATION | 57 → 52 |
  | G-TYPE-SIM | 41 → 41 |

  Power falls accordingly. The selection depends on the blinded representation, never on outcomes.
- **Evidence richness:** every analyst sees one pointer per member for every unit. Mean pointers per unit fall from about 96 to 2.9 (G-TYPE-SIM candidates) and from about 42 to 2.1 (G-NOTATION). The candidate-check reads are unchanged (2 whole-file reads per set).
- **K = 64 and the registered cells:** unchanged.
- **Symmetry:** preserved between candidate and control.
- **Outcome-independence:** preserved.

## 5. Pre-release gate (actual S5a inputs)

The actual S5a inputs exist only after the production batches are accepted and the pass snapshot exists.

Sequence:
1. Construct and accept the S5a pass inputs.
2. Run the same measurement (`scripts/p3b_s5a_m4_residual.py`, bound to the pass snapshot) on them, **before the blind file is released**, without S5 outcomes.
3. Release the blind file only if the registered condition passes.
4. Otherwise stop and return to governance. Nothing proceeds automatically.

**Registered condition (human ruling, recorded in G-LOG-0045):** for every generator with a tested S5a cell, the best measured reviewer-visible feature AUC on the actual S5a pass inputs must be **≤ 0.55**.
- It is a blinding-integrity gate, not a significance threshold.
- It uses the registered features and AUC, with no S5 outcomes.
- The threshold is never tuned after the measurement, and populations and cells are never modified to pass.
- A generator is testable at pre-release time if it has blinding FULL and a registered cell with pre-reveal m ≥ 20: the outcome-free part of the registered test gate.
- Implementation: `scripts/p3b_s5a_m4_residual.py gate`, which writes `audit-p3b/S5A-BLINDING-GATE.json` and a frozen PRE-RELEASE-BLINDING-GATE pass-record entry.
- PASS allows the blind file to be released. FAIL stops the pass for a human ruling.

## 6. Limitation

A'1 removes the **measured pointer-derived** role cues. It does **not** establish that no reviewer-visible role cue exists:
- the semantic similarity of member label names and the pointer anchors are visible and unmeasured;
- arity differs between generators (family vs non-family `total_ptrs` AUC 0.886 under A'1), but within each generator it is matched exactly;
- a negligible family signal remains from the family-only M4-A stripping (AUC 0.502).

## Traceability

Human rulings after G-LOG-0044: H-19 = A; M4 = A; items 1–9; range semantics; M4 residual = A; M4-R1 scope = A'1. Evidence: `audit-p3b/20260925_0425_m4a-residual-blinding-analysis.md` §2–§8 and `scripts/p3b_s5a_m4_residual.py`. Implementation: `scripts/p3b_s5a_controls.py`, `scripts/p3b_s5a_cells.py`. Protocol: v1.7 §9E.2 items 4 and 6, Appendix A.7, §26.
