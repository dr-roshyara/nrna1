# Traceability: TD-001 · v1 §1.14.1 · M2 review · corpus · ALM baseline (2026-10-18)

**Subject:** the ALM **baseline**, unchanged: `alm/alm.py` sha256 `ebc1386a80731c35…`, `alm/ALM-FORMAL-MODEL.md` `647403780527af5a…`.

**Sources:**
- v1 = `v1-recon/CH-01-v1-reconstructed.md` (§1.14.1 = L493–694);
- M2 review = `1.14.1-repair/REVIEW-2-RECORD-M2.md`;
- register = `s11411-repair/M2-TRACEABILITY-AND-DECISIONS.md` L56–80;
- TD-001 = `TD-001-RESEARCH-REPORT.md` Addendum 2 (#1–#11) and Addendum 3;
- ALM review = `alm/review-1/`.

**Tests:**
- `alm/tests_alm.py` (32 checks): run `cd alm && python3 -B tests_alm.py`;
- `alm/tests_v1cases.py`: run `python3 -B tests_v1cases.py` (output `alm/run-v1cases-20261018.txt`);
- reviewer CEs: run `python3 -B review-1/ce_reproduced_on_tmp_baseline.py`.

**Status values:**
- **COVERED:** expressed, and a test agrees.
- **PARTIAL:** expressed in part, or with a known divergence.
- **CONTRADICTED:** the baseline gives a different answer, or a reviewed CE refutes it.
- **NOT EXPRESSIBLE:** the concept is absent from ALM.
- **NOT TESTED:** expressible, but no test exists.

*"Same topic in ALM" is never counted as coverage.*

## A. v1 §1.14.1 known formal defects (v1 L629–643)

| v1 defect | ALM treatment | Status | Evidence |
|---|---|---|---|
| 1 · υ non-monotone; no least fixed point | ALM has no υ. Its strict-order recursion gives uniqueness (Thm 1), but only by **excluding** unverifiable endings | **PARTIAL** (avoided, not solved) | `T1_unique_fixed_point_equals_recursion`; review T-4 (self-consistency only) |
| 2 · issuance and delegation unchecked; a parent's ending does not reach the child | issuance is checked (a grant is valid only if its issuer is authorized). **Parent ending does not reach the child** | **PARTIAL + hidden policy** (δ = no) | `circular_delegation_without_root_invalid`; `tests_v1cases` 11c HIDDEN-POLICY; CE3 |
| 3 · no adoption on record; annulled adoption; expiry not an act | unrecorded adoption → UNDETERMINED. No annulment. Expiry via period | **PARTIAL** | `tests_v1cases` 14 (agrees under C1-b); 6 NOT EXPRESSIBLE |
| 4 · intervals mix act time and total order; same-moment race; no recording time | **unchanged in kind**: periods use τ, order uses A0 (τ, tie-break). No consumption. No recording time | **CONTRADICTED** (not repaired) | review P-2, CE8 |
| 5 · σ outside the fixed point; covers / actor–holder undefined; ω | covers defined but wildcard-only; actor = holder conflates process and holder | **CONTRADICTED** | review C-3/CE4, A-6 |
| 6 · revoked-actor cycle: U or causal priority? (policy) | resolved **silently** by causal priority (strict ≺) | **CONTRADICTED** (policy decided silently) | `strict_semantics_resolves_mutual_revocation_by_A0_order` |
| 7 · live grant `authority` field may name the issuer, not the holder | assumes a holder field | **NOT ADDRESSED** (data issue) | — |

## B. v1 business safeguards (v1 L537–549)

| Safeguard | Status | Evidence |
|---|---|---|
| 1 · who may decide what: kind, area, time in effect | **PARTIAL** | scope pairs (C-3 wildcard defect); periods ✓ (`decision_by_expired_authority_is_invalid`) |
| 2 · a record is not the decision; a cancellation counts only if authorized | **COVERED** | `tests_v1cases` 11a |
| 3 · late and corrected records | **PARTIAL**: late ✓, correction / error-annulment ✗ | `tests_v1cases` 10; case 6 NOT EXPRESSIBLE |
| 4 · authorized ≠ well-supported | **NOT EXPRESSIBLE** (out of ALM's scope; §1.17.2) | — |
| 5 · one headline with all reasons; "cannot determine" when gaps could change the answer | **NOT EXPRESSIBLE** (no headline; contradictions and missing fields not modelled) | review C-6; `tests_v1cases` 8 PARTIAL |
| 6 · no freezing by an unverifiable entry (υ) | **NOT EXPRESSIBLE** | `tests_v1cases` 15 |

## C. v1 cases 1–15 (v1 L652–668)

| Case | Status | Test |
|---|---|---|
| 1 alternative grants | COVERED (but ALM is existential, v1 uses cited bases) | `tests_v1cases` 1 |
| 2 joint authority + suspension | NOT EXPRESSIBLE (C4, suspension) | 2 |
| 3 grant ended before the act | COVERED | 3 |
| 4 consumed grant | NOT EXPRESSIBLE (C13) | 4 |
| 5 supersession, invalid successor | NOT EXPRESSIBLE (C5 σ) | 5 |
| 6 annulment, backdated effective time | NOT EXPRESSIBLE (RD 2) | 6 |
| 7 re-adoption after revocation | COVERED | 7 |
| 8 ran out + revoked: headline | PARTIAL (value ✓; headline ✗, C8) | 8 |
| 9 cited / unknown citation | NOT EXPRESSIBLE (C2): **semantic difference**, since ALM ignores citations | 9 |
| 10 late-recorded cancellation | COVERED (V = ρ now; R at the act's position = ρ at-the-time) | 10 |
| 11a unauthorized ending | COVERED | 11a |
| 11b authorized ending | COVERED (earlier acts per RD 1) | 11b |
| 11c delegated grant after the parent ends | **CONTRADICTED / hidden policy** (δ = no) | 11c |
| 12 same-moment adoption and revocation | **CONTRADICTED** (`(False, False)` vs v1 `(revoked, current)`) | 12; review C-4/A-5 |
| 13 mutual annulment | NOT EXPRESSIBLE (the grant analogue is resolved by A0, not U) | 13 |
| 14 no adoption on record | COVERED under C1-b | 14 |
| 15 unverifiable revocation | NOT EXPRESSIBLE (RD 6 υ) | 15 |

**Tally (from `run-v1cases-20261018.txt`):** 7 agree · 1 partial · 1 diverges · 1 hidden policy · 7 not expressible. The 16 rows are cases 1–15, with case 11 split into 11a–c.

## D. M2 review findings (REVIEW-2-RECORD-M2.md)

| G | Topic | ALM status | Evidence |
|---|---|---|---|
| G-1 | greedy υ suppression | NOT EXPRESSIBLE (no υ) | — |
| G-2 | "from its own act time, never retroactively" | **PARTIAL**: REVOKE is prospective at act time. No `late` options. END_CLAIM ignores order (C-4) | `revocation_prospective`; CE2 |
| G-3 | consumption reaching claims | NOT EXPRESSIBLE | v1 case 4 |
| G-4 | issuance debits quota | NOT EXPRESSIBLE (no quota) | — |
| G-5 | late-filed grant → permanently U | **COVERED in kind**: recognition becomes AUTHORIZED once the grant is recorded (an up-flip) | `S0103_after_transcription_authorized`; T4 up-flips (2198) |
| G-6 | question-instant derivation | PARTIAL: closed claim periods (C16 inclusive) | CE12 |
| G-7 | headline order | NOT EXPRESSIBLE | — |
| G-8 | expiry vs β | NOT TESTED | — |
| G-9 | containment with a missing `to` (Kleene) | NOT EXPRESSIBLE (no missing periods, C7 π) | — |
| G-10 | narrowing over-attributed to sources | **CONSISTENT**: ALM excludes narrowing as unsourced (N) | ALM §6 |
| G-11 / G-12 | brief and traceability defects | n/a to the model. **The same class recurs**: ALM's symbols collide with the register | `REVIEW-ALM-1-DISPOSITIONS.md` |
| G-13 | C6 override direction | NOT TESTED (U not implemented) | `test_two_current_claims_without_U` |
| G-14 | T2′/T3 overclaims | n/a. **Analogous recurrence:** ALM Thm 3 is overclaimed | review P-1 |
| G-15 | κ_g under C1-a | **CONTRADICTED**: C1 decided toward C1-b in V | review F-3 |
| G-16 | the author's encodings hide defects | **RECURRED (5th time):** the narrow alphabet hid P-1, C-1, C-4 and C-5 | review T-1/T-2 |

## E. TD-001 statements (Addendum 2, #1–#11) and r1.1 recognition

| # | Statement | ALM | Status | Evidence |
|---|---|---|---|---|
| 1 | validity requires the required authority | V2 (internal only) | COVERED internally; external obligations NOT EXPRESSIBLE (T1-15) | `S0058_…` |
| 2 | subject: decision / rule / exception | a generic claim | PARTIAL (T1-04) | — |
| 3 | "currently authoritative" | V3 | COVERED as a term | — |
| 4–5 | "recorded act" / "recorded permission" (the r1 defect) | V uses acts | **DECIDED toward C1-b** (should be a parameter) | review F-3 |
| 6 | authority at the time of the act | V2 at τ(e) | **COVERED** | `S0058_anachronistic…`, `revocation_prospective`, `decision_by_expired_authority_is_invalid` |
| 7 | own validity period | claim_period | PARTIAL (ι conflates C14 with claim periods) | CE12 |
| 8 | no **later** authorized ending | V3(c) | **CONTRADICTED** ("later" lost) | CE2 |
| 9 | carry-over under the organisation's rules | κ global binary | PARTIAL (per-kind RD 1 collapsed; δ hidden) | F-2, CE1, CE3 |
| 10 | recognition as the records stood | R | **CONTRADICTED** (Thm 3 fails; μ absent) | CE7, P-2 |
| 11 | "cannot determine, never 'not authorized'" | single λ | **CONTRADICTED** (λ < ∞ violates it; λ = ∞ gives no positives) | F-1, CE6, `S0103_before_transcription_…closed_world` |

## F. Corpus examples and scoped rules

| Example | Status | Test / evidence |
|---|---|---|
| S0058 L77 anachronistic grant | COVERED | `S0058_anachronistic_grant_does_not_authorize_earlier_work` |
| S0092 L35 / S0095 L141 given but unrecorded | COVERED | `S0092_…` (3 tests) |
| S0103 L191 late transcription | COVERED (C1-b) | `S0103_…` (4 tests; one is a λ=0 policy test, review T-8) |
| S0052 conditional approval via a later act | COVERED (generic proviso) | `R72_proviso_unmet…`, `R86_after_confirm…`. **Mislabelled:** they encode S0052's pattern; R-72 → R-86 is an inference (E-4) |
| S0104 valid but not eligible | PARTIAL (bars not relational; any/all) | `S0104_valid_but_not_eligible`; CE5 |
| S0074 a grant barring no one | COVERED | `S0074_grant_barring_no_one_eligible` |
| S0065 acceptance without independent verification | NOT TESTED | — |
| OQ-6 no revocation ever observed (governance) | **UNSUPPORTED PREMISE**: REVOKE is an ALM primitive with no governance instance | review E-8 |
| ADR-001 / Stress Test 8 | PARTIAL: they support act-time validity. κ = persist evidence downgraded (ADR-001 partly superseded; Stress Test 8 per-type) | review E-1, E-2 |
| IND-1 acceptance | **CONTRADICTED** (modelled as eligibility; the checkpoint says authority) | review E-8 |
| IND-2 implementer ≠ independent verifier of the same work | PARTIAL (not relational) | `A1.4_general_bar…`; review E-5 |
| IND-3 permit with disclosure | NOT TESTED in the baseline | — |
| IND-4 closure by own lane | NOT EXPRESSIBLE (no COMPLETE kind) | — |
| IND-5 grant bars | PARTIAL (C-5) | CE5 |
| IND-6 general gate rule (proposed) | NOT EXPRESSIBLE | — |
| IND-7 asserted / attested | NOT EXPRESSIBLE | — |

## G. ALM theorems after review 1

| Claim | Status |
|---|---|
| Thm 1 existence and uniqueness; Cor 1.1 no self-validating cycle | **SURVIVES** (premise: a strict well-founded order supplied; P-6 wording fixed) |
| Lemma 2 locality | **WITHDRAWN as stated** (P-1) |
| Thm 3 soundness under completeness | **WITHDRAWN as stated** (P-1, P-2). It needs the claim/act structure fixed and ≺ recorded |
| Thm 4 non-monotone recognition | **SURVIVES** with the added premise λ⁻ < ∞ (P-5) |
| Thm 5 reproducibility + ADR-C6 consequence | **CONSEQUENCE WITHDRAWN** (P-4). Corrected content: prefixes are recoverable under *unverifiable* append-only; μ is missing |
| Thm 6 open-world negatives | **CORE SURVIVES** (every records-based negative is defeasible); its stated consequence is withdrawn (P-3) |
| Prop 7 | bullets 1–2 survive by construction; bullet 3 corrected (P-8) |
| Prop 8 layer consistency | **WITHDRAWN** (vacuous; P-9) |

## H. Parameters ↔ the reserved-decision register (no new numbering)

| ALM | Register | Note |
|---|---|---|
| κ (carry-over) | **RD 1** (ending policy P, per kind; includes δ) | ALM collapsed per-kind into a global binary; δ hidden |
| ι (interval) | **C14** (grants); claim periods have no register item | ALM's ι collides with **RD 4 (ι)** |
| λ (absence horizon) | nearest: **C1 / C2** (missing record → U or F); **not C11** | ALM's λ collides with **C11 (λ)** |
| U (uniqueness) | **C6** | — |
| A0 tie-break | v1 case 12 (record position) and **C10** | ALM diverges from v1 |
| perpetual root | **C12** | — |
| bars any/all | none (new) | must become a C-item if retained: **C17 (candidate, not minted)** |
| δ transitivity of cascade | **RD 1** (δ) | — |

## Summary

- **ALM covers the TD-001 core** (#6; corpus timing cases). For the rest:
  - **#8, #10 and #11 are CONTRADICTED** by reviewed counterexamples;
  - **#4/#5 (C1) is decided rather than parameterized**;
  - **7 of the 16 v1 case rows cannot be expressed**;
  - 1 v1 case diverges and 1 hides a policy.
- **The M2 pattern G-16** (encodings hiding defects) **recurred.**
- **ALM is not a sound candidate as stated.** It is a valid *framework* (V / R / O layering and Thm 1), with defined minimum corrective work (`alm/review-1/REVIEW-ALM-1-DISPOSITIONS.md`, last section).
