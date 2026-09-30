# M4-A residual: focused blinding analysis (human decision required)

**Commission:** human ruling after G-LOG-0044, §2 ("Perform a focused blinding analysis of this residual … Do NOT choose the outcome silently").
**Independent review:** CONFIRMED WITH OBSERVATIONS (2 minor findings corrected here, no blockers).
**Status:** analysis complete. **The residual is a reviewer-visible role cue.** The remediation choice is returned to the human. **S5a stays gated.**
**Reproduce:** `python3 scripts/p3b_s5a_m4_residual.py`. It runs in memory on the pre-S5 design data through the S5a engine's own generators, control draw and blind build. It writes nothing and reports counts and AUCs only.
**Data scope:** pre-S5 design data only. No S5 outcome exists or is read, and no statistical test is altered.

## 1. What an analyst sees

A blind unit is `{unit_key, members, evidence}`, where `evidence[member]` is a list of `{source_id, row_line, anchor}` (A.7; `p3b_s5a_controls.build_blind`). Every member key is written, including those whose list is empty. The analyst can therefore see:
- pointer counts per member;
- whether any member has no pointer;
- the number of distinct sources;
- which sources are shared between members.

The analyst cannot see role, generator, defining property or the stripping counts, which are held in the reveal file only.

**AUC** = P(feature of a candidate unit > feature of a control unit) + ties/2, reported as max(AUC, 1 − AUC). A value of 0.5 means no signal.

## 2. Measurements (pre-S5 design data)

**G-SHARED-GROUP** (530 candidate units, 1,018 control units):

| Feature | Before M4-A | After M4-A |
|---|---|---|
| Units with a member that has no pointer (candidates / controls) | 17 / 33 (3.2% / 3.2%) | **101 / 36 (19.1% / 3.5%)** |
| AUC: shared source present | **0.657** | 0.500 |
| AUC: any empty member | 0.500 | 0.578 |
| AUC: minimum pointers per member | 0.522 | **0.601** |
| AUC: total pointers | 0.507 | 0.550 |
| AUC: distinct sources | 0.539 | 0.575 |

Across the whole blind file (2,565 units), units with an empty member rise from 62 to 149 after M4-A. Of those 149, 101 are G-SHARED-GROUP candidates. For a unit with an empty member, P(G-SHARED-GROUP candidate) is 101/149 = 0.68, against a base rate of 530/2,565 = 0.21.

**Other generators, for comparison:**

| Generator | Max AUC before M4-A | Max AUC after M4-A | Cells |
|---|---|---|---|
| G-NOTATION | 0.557 (distinct sources) | 0.567 | report-only (PARTIAL) |
| G-TYPE-SIM | 0.616 (total pointers) | 0.616 (unchanged) | report-only (PARTIAL) |

The G-TYPE-SIM cue is **pre-existing and independent of M4**; it is recorded here and is not addressed by this analysis.

## 3. Classification (the four distinctions the ruling asks for)

1. **Genuine loss of source-pointer information caused by M4-A: YES.**
   - 84 additional candidate units and 3 additional control units now have a member left with no pointer.
   - These are members whose only evidence rows cite a source that another member of the unit also cites. M4-A strips exactly those rows.
2. **Structural difference inherent in the construction: YES, and M4-A turns it into a visible cue.**
   - G-SHARED-GROUP candidates are members of one P2a group, and group members often co-occur in the same source. Their controls, by construction, share no group, and rarely a source (21 of 1,018 control units before M4-A; the earlier figure of 20 of 1,024 counted control records, not units).
   - Before M4-A, empty members were balanced (3.2% / 3.2%).
   - A mild pre-existing distinct-sources difference (AUC 0.539) exists independently of M4-A.
3. **Reviewer-visible role cue: YES.**
   - The empty list and the pointer counts are written in the blind file.
   - The best single visible feature is minimum pointers per member, AUC 0.601. The original cue, before M4-A, was AUC 0.657.
   - M4-A therefore **reduced but did not remove** role information.
4. **Artifact not available to the reviewer: NO.** The feature is in the analyst's only input.

## 4. Simulated remediations

All are deterministic, symmetric in role and independent of outcome, and all leave the generator definition untouched. The G-SHARED-GROUP family has 530 candidate and 1,018 control units before any remedy.

| Remedy | Candidate units kept | Control units kept | Best AUC |
|---|---|---|---|
| M4-A as implemented | 530 | 1,018 | 0.601 |
| **R1**: withhold every G-SHARED-GROUP-family unit with a member that has no pointer (both roles) | 429 (−101) | 982 (−36) | 0.565 (distinct sources) |
| **R1 + cap 1**: R1, and show exactly one pointer per member (lowest `(source_id, row_line)`) | 429 | 982 | **0.502** |
| R1 + cap 2 | 429 | 982 | 0.529 |
| R1 + cap 3 | 429 | 982 | 0.545 |
| R1 + cap 5 | 429 | 982 | 0.549 |
| cap 1 without R1 | 530 | 1,018 | 0.578 |

**Smallest remediation that removes the measured cue: R1 + cap 1.** It is the only simulated option whose best feature is at chance (0.502).

These AUCs are point estimates. At n = 429 / 982 the standard error is about 0.017, so R1 + cap 2 (0.529) is about 1.7 SE from chance and is not clearly separated from it (independent review, observation 3).

## 5. Costs and open points of each remediation

- **R1 withholds 19% of G-SHARED-GROUP candidates (101 of 530) and 36 control units.**
  - The withheld candidates are those whose members' evidence is entirely shared-source. Plausibly these are the most group-typical candidates, so power falls and the analysed population is selected by the blinded representation. The rule is outcome-independent, but not effect-independent.
  - A withheld control reduces its candidate's r_s. Without a redraw (item 5: no adaptation), a candidate whose controls are all withheld becomes NO-CONTROL-AVAILABLE.
- **Cap 1** gives the analyst one pointer per member in the G-SHARED-GROUP family instead of the full list. The 2 whole-file candidate-check reads per set are unchanged, but the analyst has less to choose them from.
- **Cross-generator uniformity.** Applying the cap only to the G-SHARED-GROUP family makes those units look uniform. That reveals the family, but not the role within it. Applying the cap to every unit would avoid this, but would change the G-NOTATION and G-TYPE-SIM material as well.
- **Governance.** Withholding units, or capping pointers, is a new blinding and exclusion rule beyond frozen §9E.2 item 4 and A.7. It changes the pass plan (O-12) and the pass contract, and it probably needs §26 change control. K = 64 and the 64-cell family would be unchanged.
- **Features not measured by the cap simulation (independent review):**
  - Arity is matched: AUC 0.500.
  - The count of non-null anchors (AUC 0.55) tracks total pointers.
  - **Semantic similarity of member label names** (members of one P2a group may have similar names) is visible to the analyst and was not measured. No pointer remedy can remove it; it is part of what the analyst judges.
- **Proxy data.** These are design data. On the real pass inputs the effect sizes must be re-measured before the blind file is released (a pre-analysis check by the same script).

## 6. Decision required (human)

| Option | Effect |
|---|---|
| **A. R1 + cap 1** (G-SHARED-GROUP family) | Measured cue removed (0.502). Costs: 19% of candidates withheld, one pointer per member |
| **B. R1 only** | Empty-member cue removed; distinct-sources cue 0.565 remains |
| **C. Accept the residual** | G-SHARED-GROUP cells recorded as not blind; their status is reported accordingly (e.g. report-only, like the PARTIAL generators) |
| **D. Another remedy** (e.g. cap applied to all generators) | Specified by the human, then simulated and reviewed |

Until a decision is recorded and implemented, **S5a stays gated**. S5 production is a separate lane under the human ruling after G-LOG-0044. H-19 stays SEALED and S5c PROHIBITED.

## Traceability

Human rulings after G-LOG-0044 (H-19 = A, M4 = A, items 1–9, range semantics, M4 residual) · `scripts/p3b_s5a_controls.py` (M4-A, commit 953754457) · `scripts/p3b_s5a_m4_residual.py` · frozen §9E.2 items 4 and 6, Appendix A.7 · S5 plan v2.3.2 §H.

## 7. Human ruling A (R1 + cap 1): implementation finding; scope decision required

**Ruling (human):** Option A. Withhold, symmetrically, every G-SHARED-GROUP unit (candidate or control) with a member that has an empty evidence list. For the remaining G-SHARED-GROUP units, show exactly one deterministic pointer per member. The ruling describes this as **removing the measured pointer-derived role cue, not as proof of complete blinding**.

**Implemented as ruled** (`scripts/p3b_s5a_controls.py`):
- `M4R1_WITHHOLD`, `M4R1_CAP = 1`, `withheld_sets()`.
- Withheld units are not written to the blind file.
- `scripts/p3b_s5a_cells.py` `build_pools` removes them symmetrically, with cause `WITHHELD-M4R1`, from every cell that contains them.
- `check_m4a` refuses at build time a unit with a shared-source pointer, a member without a pointer, or more than one pointer per member.
- Tests are in `tests/test_p3b_s5a_mechanics.py`.

**Measured directly on the implemented engine** (`python3 scripts/p3b_s5a_m4_residual.py`, modes `implemented_A` and `scope_variants`):

| Scope of R1 + cap 1 | Units withheld | G-SHARED-GROUP best AUC | G-NOTATION best AUC | G-TYPE-SIM best AUC |
|---|---|---|---|---|
| none (M4-A only) | 0 | 0.601 | 0.567 | 0.616 |
| **G-SHARED-GROUP units only (as ruled)** | 137 | **0.502** | **0.896** (total pointers) | 0.616 |
| A'1: every unit with a candidate/control role (M4-A stripping still G-SHARED-GROUP only) | 149 | 0.502 | 0.510 | 0.506 |
| A'2: stripping and R1 + cap 1 on every such unit | 164 | 0.502 | 0.510 | 0.501 |

**Finding.** Applied to G-SHARED-GROUP units only, the rule removes the G-SHARED-GROUP cue as designed. It **creates a strong new role cue in G-NOTATION** (AUC 0.567 → 0.896):
- Many G-NOTATION candidate units also carry a G-SHARED-GROUP role, so they are capped to one pointer per member.
- G-NOTATION control units mostly are not, so they keep full lists.

G-NOTATION's cells are report-only (PARTIAL) at present, so no registered test is affected today. But the ruled scope introduces a new visible cue, and in the real pass the same mechanism would reach any generator whose units overlap the G-SHARED-GROUP family (e.g. G-DEPENDENCY, G-COCHANGE). Section 5's "cross-generator uniformity" point anticipated a family cue, not this cross-generator role cue.

**Smallest revision that removes it: A'1.** Apply R1 + cap 1 to every blind unit with a candidate or control role, and keep M4-A stripping on G-SHARED-GROUP only.
- 12 more units are withheld (149 instead of 137). These are the units of other generators that have an empty member.
- Best AUC is at or near chance for all three generators. A'1 also removes the pre-existing G-TYPE-SIM cue (0.616 → 0.506).
- Costs: every analyst sees one pointer per member for every unit, and the G-TYPE-SIM candidate / G-NOTATION control counts change slightly.

Under §26 item 3 ("if execution invalidates this annex, stop … present the revision, and wait for approval"), the scope change is returned to the human. The engine currently implements the ruled scope, and S5a stays gated.

**Pre-release re-measurement.** The "actual S5a inputs" exist only after the production batches are accepted (the pass snapshot). The re-measurement on them is therefore a **pre-release gate of the S5a pass**: the same script, run on the pass inputs before the blind file is released. It cannot be run before production.

## 8. Scope decision package (human request after §7)

**Reproduce:** `python3 scripts/p3b_s5a_m4_residual.py`, key `scope_decomposition`. It uses the pre-S5 design data and the §2 feature method; no S5 outcomes.

**Feature definitions** (all computed on the blind unit as written):

| Feature | Definition |
|---|---|
| `total_ptrs` | sum over the unit's members of the number of evidence pointers written for that member |
| `min_ptrs` | minimum of that number over members |
| `any_empty` | 1 if some member has no pointer |
| `distinct_sources` | number of distinct `source_id` over the unit's pointers |
| `shared_src` | number of `source_id` cited by two or more members |
| `all_single` | 1 if every member shows exactly one pointer (family visibility) |

AUC = max(AUC, 1 − AUC), as in §1.

### 8.1 Cause of the G-NOTATION cue (AUC 0.896)

**Overlap with the G-SHARED-GROUP family.** A unit is in the family if it carries a G-SHARED-GROUP candidate or control role.

| Units | Total | In the G-SHARED-GROUP family |
|---|---|---|
| G-NOTATION candidates | 87 | 80 (92%) |
| G-NOTATION controls | 149 | 4 (3%) |

Labels sharing a notation are usually members of one P2a group.

**Separating the two parts of rule A** (G-SHARED-GROUP scope):

| Variant | G-NOTATION cand / ctrl units | G-NOTATION best AUC (feature) | Mean `total_ptrs` cand / ctrl | Family visible: `all_single` AUC (family vs non-family) |
|---|---|---|---|---|
| M4-A only | 87 / 149 | 0.567 (`distinct_sources`) | 41.9 / 45.8 | 0.529 |
| R1 only | 77 / 149 | 0.540 (`any_empty`) | 45.2 / 45.8 | 0.533 |
| **cap 1 only** | 87 / 149 | **0.907** (`total_ptrs`) | 5.4 / 45.2 | **0.949** |
| **R1 + cap 1 (as ruled)** | 77 / 149 | **0.896** (`total_ptrs`) | 6.0 / 45.2 | **0.994** |

**Cause: the one-pointer cap, applied to the G-SHARED-GROUP family only.**
- The cap makes family membership reviewer-visible: every member shows exactly one pointer (AUC 0.994).
- Almost every G-NOTATION candidate is in the family and almost no G-NOTATION control is. Family visibility therefore reveals role in G-NOTATION.
- R1, the empty-member withholding, contributes only 0.540 (G-NOTATION candidates lose their empty members to R1, but controls keep 12).
- It is not caused by a change in population composition, and it is not specific to how G-NOTATION's evidence is represented.
- **This is a genuine cross-generator blinding problem.** In the real pass the same mechanism applies to any generator whose candidate units overlap the G-SHARED-GROUP family more than its control units do (G-DEPENDENCY and G-COCHANGE can only be measured on S5 data).

### 8.2 A'1, defined exactly

**Rule.**
1. M4-A stripping stays as ruled: G-SHARED-GROUP units only.
2. Then, for **every blind unit that carries a candidate or control role of any generator**: if some member has no pointer, the unit is withheld (symmetric removal, `WITHHELD-M4R1`, in every cell containing it).
3. Otherwise each member shows exactly one pointer, the lowest `(source_id, row_line)`.

SELF-DERIVED-only units are unaffected.

**Affected generators:** all. In the design data these are G-SHARED-GROUP, G-NOTATION and G-TYPE-SIM; in the real pass G-DEPENDENCY and G-COCHANGE as well.

**Withheld units by role** (a unit with several roles counts in each):

| Generator: role | As ruled | A'1 |
|---|---|---|
| G-SHARED-GROUP candidates | 101 | 101 |
| G-SHARED-GROUP controls | 36 | 36 |
| G-NOTATION candidates | 10 | 10 |
| G-NOTATION controls | 0 | **12** |
| G-TYPE-SIM candidates / controls | 0 / 0 | 0 / 0 |
| Distinct units withheld | 137 | 149 |

**Units kept, and best AUC per generator and feature:**

| Generator | M4-A only (units kept; best AUC) | As ruled | A'1 |
|---|---|---|---|
| G-SHARED-GROUP | 530 / 1,018; `min_ptrs` 0.601 | 429 / 982; 0.502 | 429 / 982; 0.502 (all features ≤ 0.502) |
| G-NOTATION | 87 / 149; `distinct_sources` 0.567 | 77 / 149; **`total_ptrs` 0.896** | 77 / 137; 0.510 (`total_ptrs`, `distinct_sources`); others 0.500 |
| G-TYPE-SIM | 300 / 565; `total_ptrs` 0.616 | 300 / 565; 0.616 | 300 / 565; 0.506 (`shared_src`); `total_ptrs` 0.501 |

**Family visibility under A'1.**
- `all_single` AUC is 0.500: every unit shows one pointer per member.
- `total_ptrs` between family and non-family units still reaches 0.886. Under A'1, `total_ptrs` equals the arity, so this measures the **arity difference between generators**. It is inherent to the generators and unchanged by any variant.
- Within a generator, arity is matched exactly (controls have exact arity), and the measured within-generator role AUCs are at or near 0.51.

**What an analyst still sees under A'1:**
- members, including their label names;
- arity;
- one pointer per member (`source_id`, `row_line`, `anchor`).

Label-name semantics and anchors are **not measured**.

### 8.3 Properties of A'1

| Property | Assessment |
|---|---|
| Symmetric | yes, between candidate and control: whether the rule applies never depends on which of the two roles a unit holds. It applies to every unit with a candidate or control role (under the ruled scope: every unit with a G-SHARED-GROUP role) |
| Outcome-independent | yes: decided on the blinded representation before any disposition |
| K = 64 / registered cells | unchanged |
| Candidate and control populations | change: 149 units withheld (A: 137) |
| Power: `m` of the SHARED-INVARIANT cells (M4-A only → A → A'1) | G-SHARED-GROUP 262 → 217 → 217; G-NOTATION 57 → 52 → 52; G-TYPE-SIM 41 → 41 → 41 |
| Evidence shown to analysts | one pointer per member **for every unit**. The mean number of pointers per unit falls from about 96 to 2.9 (G-TYPE-SIM candidates) and from about 42 to 2.1 (G-NOTATION). Candidate-check reads (2 whole-file reads per set) are unchanged |
| Pre-existing G-TYPE-SIM cue | removed (0.616 → 0.501 on `total_ptrs`) |
| §26 | required. It changes the blind material of every generator and withholds units outside the ruled scope; it is a blinding/exclusion rule that could change a research conclusion (§26.7). It needs a human decision (§26.2). §26.1 asks for a new annex file unless the human rules that the rule stays at pass-plan level (O-12) |

### 8.4 Is the current scope (R1 + cap 1, G-SHARED-GROUP only) defensible?

**Not as a blinded design.**
- It removes the G-SHARED-GROUP cue (0.502) but makes family membership visible (0.994). That produces a G-NOTATION role cue of 0.896.
- It is tolerable only if every other generator's cells stay report-only, or if no other generator overlaps the family asymmetrically. The second condition cannot be established before S5 data exist.

### 8.5 Technical consequences of the choices (no authorization implied)

| Choice | G-SHARED-GROUP / G-NOTATION / G-TYPE-SIM best AUC | Cost |
|---|---|---|
| Keep scope as ruled | 0.502 / **0.896** / 0.616 | cross-generator role cue; family visible |
| **A'1** | 0.502 / 0.510 / 0.506 | 12 more units withheld; one pointer per member for every unit; §26 record |
| A'2 (also strip shared sources everywhere) | 0.502 / 0.510 / 0.501 | 164 withheld (15 G-TYPE-SIM units); no measured gain over A'1 |
| R1 only, G-SHARED-GROUP scope (option B of §6) | 0.565 / 0.540 / 0.616 | residual G-SHARED-GROUP cue; less evidence loss |

Among the measured variants, **A'1 is the smallest one that brings every measured generator to ≤ 0.51 with no pointer-derived family signal.** The strongest supported statement is limited to the measured pointer-derived features. It does not establish complete blinding: label-name semantics and anchors are unmeasured.

**Provenance binding** (`89d9ad382`):
- One identity (`rule`, `cap`, `withheld_units`, `withheld_sets_sha256`, `canonicalization`) is written to the blind and reveal headers and to the PRE-ANALYSIS and LINK6 pass-record entries, with hard-failure checks.
- It is scope-neutral: it hashes whatever set the final rule withholds.
- No blind file or pass record has been produced or released.
- The identity now records the **scope** (`M4R1_SCOPE`). `check_m4r1_binding` refuses a record whose rule, integer cap or scope differs from `M4R1_APPROVED`, so a scope change cannot pass the approval check unnoticed (independent review of `ed1a0d06c`, finding 1). `M4R1_APPROVED` must be set to the final ruled identity, including scope, before any pass.

**Independent reproduction** (fresh reviewer, own scripts, no reuse of the analysis functions): **REPRODUCED WITH OBSERVATIONS.**
- Every number in §8.1–§8.3 matched, and the evidence matched `build_blind` unit by unit.
- The mechanism is confirmed: the cap alone gives 0.907; R1 alone gives 0.503–0.540.
- Observations:
  - Under A'1 a negligible family signal remains, because shared-source stripping applies to the family only: 0 vs 5 units show a shared source (AUC 0.502).
  - "Smallest" means fewest withheld units: A'1 withholds 149, A'2 164.
  - "Not specific to G-NOTATION's evidence representation" is supported by the cap-only result but was not measured directly.
