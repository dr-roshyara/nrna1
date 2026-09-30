# R7 NDB-1 / DC-1 / DC-2: semantic impact analysis (v2.4 decision package, deliverable B)

| | |
|---|---|
| **Kind** | Engineering analysis. ⚠ authority: generated. **It recommends and decides nothing.** Evidence for a human contract decision |
| **Subject** | the frozen R7 addendum v2.3 (sha256 `cdbf53cbe531cc92dc5decc9b29496188b9b6e7ffc0e2ec875bc5d4dc4a6f211`, re-verified unchanged 2026-09-27) and its implementation `ef9bdf7ba` |
| **Companion** | proposal `audit-p3b/20260927_R7-v2.4-CONTRACT-AMENDMENT-PROPOSAL.md` (A) · tests `scripts/tests/test_p3b_s5_r7_v24_proposal.py` (C) |
| **Method** | (1) every code consumer of each field located by search over `scripts/` (R7 modules and the historical gate); (2) the committed rev3 S4 objects of PB02–PB05 (20 objects; ledger outputs, read-only, not corpus sources) measured with S-ids masked; (3) the claims are demonstrated by tests. No corpus read, no dispatch, no activation, no ML |
| **Verdict of this analysis** | **No semantic conflict found.** The required separation can be demonstrated for all four fields (§2, §3). DC-1 is safe **only as a structural self-reference** and is not a source reference; this analysis recommends a value rule for it (§3.4) |

## 1. Findings in one table

| Field | S-id occurrences (objects) | Code consumers | Epistemic status | Can it reach Evidence / Claim / Reconstruction / Statistics / Canonical ids? |
|---|---|---|---|---|
| `absences.<dim>.reason` | 129 (20/20) | key presence only (`ab_keys`, `p3b_s5_verify.py:761`) and the whole-record lexical constraints (§2.3) | agent commentary (META) | **No** |
| `stage2_dispositions[*].reason` | 100 (11/20) | key presence only (`disp_keys`, `:688`) and the lexical constraints | agent commentary (META) | **No** |
| `timeline[*].historical_position` | 5 (4/20) | key presence only (`tl_keys`, `:565`) and the lexical constraints | agent commentary, **including ordering assertions** (META) | **No**: precedence is A.10-only (§3.3) |
| `escalations[*].field` | 7 (3/20), all `timeline[S####].order` under SCHEMA-LIMITATION | **structural:** `sl_esc` (`:535`, used `:544`, `:576`, `:580`; G-09 `:536`) and `esc_by_field` (`:532`, hub LOAD `:785`) | an **object-location address** (it points into the same object), not a source reference | **No** evidence, claim or relation. It can only exempt a *null* value from a historical schema gate, and v2.3 already does that; v2.4 does not change it |

Before, under v2.3: 241 R7-U "untyped S-id-bearing location" failures in 20/20 objects. After, under the proposed registry: **0**. Claim sets are identical in 20/20 objects, and the DC-1 value rule is satisfied in 7/7.

## 2. The general invariant: lexical source-id occurrence ≠ evidentiary support

### 2.1 Statement (proposed as contract invariant INV-LEX, addendum v2.4 §3.0)

> **An S-id occurring in a value is support for nothing unless the value's location is registry-typed EVIDENTIARY (or QUOTE of an EVIDENTIARY element).** A META or NONE occurrence is a *mention*. It creates no Evidence, no Claim, no Reconstruction input, no precedence, no lifecycle, no statistical membership and no canonical identity.
>
> A mention can only (a) be typed, (b) be **constrained** by the whole-record lexical gates (G-01, AUDIT, QUARANTINE, S3), (c) be constrained by a declared META rule, and (d) change the object's bytes and therefore its digests.
>
> (b) and (c) are **restrictive**: they can add a failure, never a claim and never a PASS.

### 2.2 Why it holds in the implementation (mechanism, not intent)

| Layer | Mechanism | Consequence for a mention |
|---|---|---|
| Claim | `U.claims(reg, obj)` enumerates claims from **fixed structural paths** (timeline points, births, `contradicted_by`, `superseded_by_sources`, FOUND absences, FOUND dispositions, edges with a source, d4/d5, census disagreements). **It does not read the registry argument** (`claims(FROZEN) = claims(PROPOSED) = claims([])`, tested) | adding a registry row cannot create a claim; a free-text value is never read |
| Evidence | `E.claim_violations` / `facts_index` / `anchored` work from the claim set and from witnessed reader bytes; `E.quote_violations` uses QUOTE rows only; `E.reading_rule_violations` reads only CONTRACT-DEVIATION `escalations[*].detail` (already META in v2.3) | a mention is never anchored and never required to be |
| Reconstruction | `C.precedence`/`dated_position` read 02-FILES metadata and `timeline[*].date_applies_to_file`; `summary_violations` and `lifecycle_violations` read `timeline_summary`, `superseded_by_sources`, `births` | none of the four fields is an input |
| Statistics | `S.frame_attributes(plan, slices)` takes **no object at all**; `estimate_v7` reads frozen records and outcomes | object text cannot affect membership or estimation |
| Canonicalization | claim ids are built from structural fields only (addendum §3.3); `U.canon` is a byte serialization for digests | claim ids are unchanged; only byte digests change, which is exactly what a digest is for |
| Registry | the proposed rows are META; F2 (`consumer_violations`) forbids any consumer from reading a META path; checked, 0 violations | no consumer may later start reading these fields without a contract change that F2 would flag |

### 2.3 What a mention is still subject to (fail-closed, unchanged by v2.4)

| Constraint | Code | Effect on a META mention |
|---|---|---|
| G-01 (`cited` = every S-id anywhere in the record, lexically) | `p3b_s5_verify.py:997` | an S-id not in 02-FILES → FAIL (U) |
| AUDIT | same `cited` set | an S-id neither in the batch's slices nor in its witnessed reads → FAIL (E) |
| QUARANTINE / SEAL | `:1028`, count-only | a hold-out id in META text → FAIL; the hold-out lists are never exposed |
| S3 | `C.s3_violations` (whole record) | an S-id range or a register pointer in META text → FAIL (R) |

Here lexical occurrence is used *against* the object and never for it. That asymmetry is the invariant.

### 2.4 The required separation, demonstrated

| Layer | Membership predicate | Is a META mention a member? | Where it is shown |
|---|---|---|---|
| META source mention | discovered ∧ typed META | yes, by definition | typing tests |
| Evidence | a fact anchored on witnessed bytes of the same run | **no**: never anchored and never required | `ComponentNonInterference`, `FullPathNonInterference.test_meta_only_mention` |
| Claim | an element of `U.claims()` | **no**: `claims()` does not read these paths | `test_claims_ignore_the_registry` and the 20/20 corpus identity |
| Reconstruction input | read by `summary_violations`, `lifecycle_violations`, `precedence` | **no** | projection keys `summary`, `lifecycle`, `precedence`, `contradiction_precedence` |
| Statistical evidence | frame/estimate inputs | **no**: the frame takes no object | `test_statistics_frame_is_object_independent` |
| Canonical truth | claim ids / theory objects | **no**: ids are structural; no theory object exists at this layer | projection key `claims` (canonical bytes) |

Control: the **same** S-id moved into an EVIDENTIARY location (`superseded_by_sources`) **does** create a claim and is judged as evidence. The boundary is the location's type and not the string (`test_mutation_moved_into_an_evidence_field_does_create_a_claim`).

## 3. Per-field answers (the ten questions)

### 3.1 `absences.<dim>.reason`

| # | Question | Answer (evidence) |
|---|---|---|
| 1 | Can it contain an S-id? | Yes: 129 occurrences in 20/20 committed objects (census narratives, e.g. "Stage 1 had one hit (S#### raw offset 275…)") |
| 2 | Is it a source reference? | **No.** The sources of an absence are `supplied_by.source_id` (FOUND, EVIDENTIARY) or the census (GENUINELY-UNDEFINED, ABSENCE-CENSUS). The reason *mentions* files |
| 3 | Can it create Evidence? | No (§2.2) |
| 4 | Can it create a Claim? | No: the ABSENCE claim is built from `resolution`/`supplied_by`; the census claim from the parent row |
| 5 | Can it affect Reconstruction? | No |
| 6 | Can it affect precedence? | No: A.10 only. 42 values across the three NDB-1 fields carry ordering words; none is read |
| 7 | Can it affect statistics? | No |
| 8 | Can it affect canonicalization? | No claim id or identity; only the object's byte digest |
| 9 | Exact consuming predicate | v2.3 R7: `U.typing_violations` (untyped → R7-U FAIL; the whole of NDB-1). Historical: `ab_keys` presence (`:761–767`). Lexical constraints: G-01/AUDIT `cited` (`:997`), QUARANTINE (`:1028`), R7 S3 (`r7_verify.py:159`) |
| 10 | Intended epistemic status | **META: human-readable justification, unverified, non-evidentiary.** Even when the parent absence is EVIDENTIARY, the reason child is commentary on the evidentiary fields |

### 3.2 `stage2_dispositions[*].reason`

| # | Answer |
|---|---|
| 1 | Yes: 100 occurrences in 11/20 |
| 2 | No. The disposition's source is `stage2_dispositions[*].source_id`; its finding is `by_dimension` |
| 3–4 | No. A FOUND disposition's ABSENCE claim is built from `source_id`, `hit_kind`, `hit_key`, `term_index` and `by_dimension` (`U.claims`) |
| 5–8 | No / No / No / digest only |
| 9 | v2.3 R7 `U.typing_violations`; historical `disp_keys` presence (`:688–697`); the same lexical constraints |
| 10 | **META** (commentary on a disposition) |

### 3.3 `timeline[*].historical_position`

| # | Answer |
|---|---|
| 1 | Yes: 5 occurrences in 4/20 |
| 2 | No. The point's source is `timeline[*].source_id` |
| 3–4 | No |
| 5–6 | **No, and this is the semantically sharpest case.** The values contain agent ordering assertions (masked examples: "2026-09-07 (after S####: cites V3 as its evidence packet)", "ordered before S#### by content"). R7 precedence (`C.dated_position`) uses only 02-FILES `best_historical_date(_basis)`, `order_evidence`, `mtime_block` and the point's `date_applies_to_file`. Content-inferred order is **by design not** a precedence basis (FD-1′c). The assertion therefore stays an unverified agent statement |
| 7–8 | No / digest only |
| 9 | v2.3 R7 `U.typing_violations`; historical `tl_keys` presence (`:565–569`); lexical constraints |
| 10 | **META: an agent's narrative of position. Explicitly NOT a precedence input.** v2.4 should say so in the row's semantics, so that no later consumer is tempted (§A.2 of the proposal) |

### 3.4 `escalations[*].field` (DC-1)

| # | Answer |
|---|---|
| 1 | Yes: 7 occurrences in 3/20 (PB02), all of the form `timeline[S####].order`, reason SCHEMA-LIMITATION. In 7/7 the S-id is a timeline point **of the same object** and that point's `order` is null |
| 2 | **No.** It is an *address* of a location in the object (Published-Language internal pointer). The S-id names a point whose own evidentiary status comes from `timeline[*]` |
| 3–4 | No: no claim path reads `escalations` |
| 5 | Not as a reconstruction input. It can exempt a **null** `order` (and, historically, a null `date_applies_to_file`/`change_vs_previous`) from G-05/F1 (rule (b), G-LOG-0023). It creates no relation. R7 validation still requires `change_vs_previous` ∈ closed enum regardless (stronger than rule (b)) |
| 6 | No: `timeline[*].order` is not an A.10 precedence basis in R7 |
| 7–8 | No / digest only |
| 9 | Historical `sl_esc` (`:535`; `:544` statuses; `:576` rule (b); `:580` F1; G-09 `:536` requires a SCHEMA-LIMITATION register record); `esc_by_field` hub LOAD (`:532`, `:785`); v2.3 R7 `U.typing_violations` |
| 10 | **A structural exemption key: META-class, with a self-reference value rule.** Recommended rule: *if it carries an S-id, the value must be exactly `timeline[<S-id>].<order\|date_applies_to_file\|change_vs_previous>` with `<S-id>` ∈ this object's `timeline[*].source_id`.* This makes "address, not source reference" mechanical and closes the free-text channel (`"S#### supports this"` in `field` would FAIL). Prototype tested (`DC1SelfReference`); satisfied by 7/7 corpus occurrences |

**Model-integrity check (a new dimension, an overloaded one, or another value?).** DC-1 is **another value** of the existing META type with a rule, just as `escalations[*].detail` already carries a rule. A new type such as STRUCTURAL is **not** introduced. It would be a new dimension that is neither necessary (META + rule suffices) nor orthogonal (it overlaps META).

## 4. DC-2 (test hygiene, not semantics)

- **Current state.** `test_p3b_s5_r6_verify.PositiveControl.test_valid_revision_6_batch_passes_full_verifier` fails with exactly one failure: `R7-U contract revision 6 is superseded by revision 7 (G-LOG-0088)`. The result is FAIL, while gate **R6 = PASS**.
- **What this shows.** The R6 machinery still accepts the batch. Only the supersession rule of addendum §10 rejects it.
- **Prepared repair.** Retarget that one test to assert exactly that: failures = [the supersession line], result = FAIL, and R6 = PASS. See the diff in proposal §F.
- **What it does not do.** It weakens nothing. It pins the supersession *and* R6's own acceptance.
- **Verification.** The patched suite ran in place from the scratchpad copy: **53/53 OK**. It has **not** been applied, because the R6 tests are immutable without authorization (G-LOG-0085).

## 5. Residual risks (reported, not resolved)

1. **Unverified narratives stay in the objects.** A META reason may *assert* something false (for example an ordering), and nothing verifies it. This is acceptable only because nothing consumes it. Any future consumer (report generation, a P4 theory extraction) must treat these fields as META. The F2 contract test enforces this for verifier consumers only, not for downstream tools. **This is a P4 governance point, recorded here.**
2. **A human reader may over-read META text.** A presentation-layer matter, out of R7 scope.
3. **Discovery depends on the regex `\bS\d{4}\b`.** Malformed ids (`S00012`, `s0001`) are not discovered and are inert under both registries (tested). This is unchanged behaviour.

**Traceability:** addendum v2.3 §2–§4, §10 · G-LOG-0023 (rule b), G-LOG-0085, G-LOG-0088 · implementation record §6 (NDB-1, DC-1, DC-2).
