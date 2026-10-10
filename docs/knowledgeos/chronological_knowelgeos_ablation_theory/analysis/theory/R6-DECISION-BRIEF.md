# R6 decision brief (r3: final for the human gate)

**Status:** r3, 2026-10-10, final for the R6 gate. §6 is resolved by the **Human ARB act F-LOG-0245**: a fresh-subagent run suffices; **cross-family is WAIVED, not satisfied**. The pre-framed cases A–C of r2 are kept below for the record. r1 was reviewed PASS_WITH_LIMITATIONS (`LOOP-1BS/REVIEW-r1.md`).

## 1. Claims tested in R6
| Id | Claim | Type |
|---|---|---|
| R6-1 | **D1, the strong form, under alternative readings.** R4 Prop 4.1 survives the alternative readings R-a … R-k. | formal claim → finite check per reading |
| R6-2 | **The finite R5 results reproduce** under an independent implementation. | computational result |
| R6-3 | **The V1b reading-robustness results reproduce.** | computational result |
| R6-4 | **The computational core reproduces** on a universe whose values appear in no bundle (blind slice U_P2: V = {p, q, r}, P = {p, q}). | computational result |

## 2. Evidence and provenance
| Evidence | Artifact | Independence |
|---|---|---|
| **V1a.** Lemma M; reading table | `LOOP-1BN/`, R4 §9 | Claude analyst and reviewers |
| **V1b.** Blind enumeration with planted controls. Every scorable sealed prediction confirmed; P1.7 was falsified, but this had been pre-identified; K4b-global and ≈σ,f,Cap items unscorable. The analyst's spec-extraction error was repaired by a blind supplementary run. | `LOOP-1BN/V1B-RETURN/` | isolated Claude worker |
| **F-1.** Lem 3.7's wording overstated its proof; fixed by erratum (R4 r3.1); no conclusion changed. The frozen bundles keep the old wording; the unit is pre-registered as a known (c) candidate. | `LOOP-1BR/` | Claude reviewer: PASS |
| **Bundle-02, within-family** (Sonnet 5.5 vs sealed Opus 5.5) | `LOOP-1BP/RETURN-WF-SONNET/`, `COMPARISON-WF-SONNET{,-r12}/` | WITHIN-FAMILY, cross-model |
| **Blind slice, Claude side** (Opus 5.5), calibrated against sealed V1b | `LOOP-1BQ/CLAUDE-SIDE/`, `CALIBRATION.json` | isolated Claude worker |
| **Blind slice, within-family** (Sonnet 5.5 vs Opus 5.5) | `LOOP-1BQ/RETURN-WF-SONNET/`, `COMPARISON-WF-SONNET/` | WITHIN-FAMILY, cross-model |
| **Interpretation-sensitivity map** | `LOOP-1BQ/SENSITIVITY/` | Claude worker and reviewer |
| **Blind slice, non-Claude** | (none) | **WAIVED by the Human ARB (F-LOG-0245): not run** |
| **Blind slice, fresh Haiku 4.5 subagent** (F-LOG-0245) | `LOOP-1BQ/RETURN-WF-HAIKU/`, `COMPARISON-WF-HAIKU-ADAPTED/REVIEW-STEP-C.md` | within-family. **Implementation failure:** all (a) plus UNTESTABLE/INCOMPLETE; no evidence against the reference (F-LOG-0246) |
| **Bundle-02, non-Claude** | `~/F-XCHECK-RETURN-01/` | **NOT RUN** (optional; only 42 BLIND units) |

**Tool-path deviations in the Sonnet runs:** all benign and recorded. Each run read its own persisted tool output. Bundle-02's run wrote a helper in /tmp. The slice run hit blocked commands (a `../prog` draft, a malformed-path `cd`, and an `mv`) and rewrote its program into its cwd.

## 3. Comparator integrity
- **Bundle-02 comparator** `LOOP-1BP/compare_xcheck.py` r12: fail-closed.
  - Reviewed in rounds r1–r10 and r12. **r11 was not reviewed, by decision, because it only grows EXPOSED.**
  - Silent false agreements were found and fixed in r2–r6. Planted and adversarial controls pass.
  - r12 limitations: an R5 witness's slot is not compared; example roles are accepted in role matching. These are anyof units (NONLICENSING tier), but every scored unit still enters the licence condition.
  - The G3 disagreement in the bundle-02 run was a **comparator artifact** (a nested witness role), fixed by r12.
- **Slice comparator** `LOOP-1BQ/compare_slice.py`: reuses the fail-closed parser. Review found no silent agreement. 67 distinct BLIND quantities. **Amendment A1 (de-duplication; tiers only shrink BLIND) was not re-reviewed.** 14 negative controls pass.

## 4. Results so far (all Claude-family)
| Test | Agreement | Disagreements | Classification |
|---|---|---|---|
| Bundle-02, Sonnet vs Opus | BLIND 42/42 | 40 at r11; 39 at r12 | all (c): the K4b reading of H-D⁺ (34); composite-witness semantics (5); the comparator artifact (1, fixed). **The licence was not granted:** 191 INCOMPLETE (format) and 1,425 unrecognized keys. These were mapped by hand and agree; the A9 con-only/d-only split is not cross-confirmed. Human ruling F-LOG-0242: (c) accepted |
| Slice calibration (fresh Claude worker vs sealed V1b) | 161 substantive + 20 null/null | 0 | U (i)-S and (ii)-S scope sizes and counts are not calibrated |
| Blind slice, Sonnet vs Opus | 67/67 distinct BLIND (118/118 units); 300/300 EXPOSED | 0 | byte-identical output; copying ruled out by two clean re-runs |
| Sensitivity | 63/67 robust under the identified single-clause readings | 4 sensitive: (iii)-L n_cex under R-g, R-g+, R-h, and the **R-h (iii)-L least counterexample** | every sensitive reading contradicts sealed V1b in U. Conditional on V1b and on U-detectable readings. AMB-3 is downgraded to weak. Pairwise combinations not run |

## 5. What the Claude-family evidence establishes, and what it does not
- **Establishes, claim by claim:**
  - **R5/V1b (bundle-02):** two Claude programs (Opus 5.5 and Sonnet 5.5) agree on the 42 BLIND units, and on every hand-mapped value once the two readings of the spec ambiguities are aligned. Every disagreement traces to spec ambiguity, format, or one comparator artifact. Most bundle-02 units were derivable from the bundle text, so the blind content is small.
  - **Blind slice:** two Claude programs (Opus and Sonnet) agree on all 67 distinct blind quantities. They are independent in authorship, but **structurally convergent at the (iii)-L core**. The slice's U frame also matches sealed V1b, which is a third program, on 161 substantive values.
- **Does not establish:**
  - cross-family reproduction;
  - correctness of the shared interpretation (both Claude programs made identical choices on every ambiguous clause);
  - the generic theorems;
  - empirical validity;
  - anything about H-D versus H-B beyond R1.

## 6. Cross-family outcome: RESOLVED by human waiver
- **Status: CROSS-FAMILY VALIDATION WAIVED by the Human ARB chief (F-LOG-0245).** It was not attempted, so it is neither satisfied nor failed.
- **Fresh-subagent evidence accepted under the waiver:** Sonnet 5.5, 67/67 distinct blind quantities (F-LOG-0243).
- **A second fresh subagent (Haiku 4.5) failed to implement the task:** all (a) plus UNTESTABLE (F-LOG-0246). It is recorded, and it is not counted for or against.

### Pre-framed cases of r2, kept for the record
The return is processed under PREREG-SLICE (with Amendment A1) and the D0–D4 procedure in `SENSITIVITY.md`, after sealing. **D0 runs first:** if the return's U-frame values disagree with sealed V1b, that is classified before anything else.

- **Case A, full agreement.** This needs **all** of: 118/118 BLIND units (67 distinct); every scored unit AGREEMENT or NOT_APPLICABLE; no AMBIGUOUS flag; no unrecognized key. Then: *"Cross-family computational reproduction of the slice core is supported, under the implementer's recorded interpretation and within the tested scope."* If its NOTES record a different reading at an AMB point, that point is inert **for these values**.
- **Case B, disagreement.**
  - No theory change, and no assumption that either side is wrong.
  - Process: D0; isolate; apply D1–D4; audit the implementation; compare mathematically; reviewer.
  - Classes:
    - an implementation error is (a) for the checker, (b) for the Claude side;
    - an interpretation difference, spec ambiguity or UNDETERMINED result is (c);
    - a mathematical or theoretical discrepancy is (d).
  - **Every non-(a) result goes to the human.**
- **Case B′, a partial, INCOMPLETE or deviated return** (missing or malformed files, unrecognized keys, the sandbox fallback) **without any disagreement.**
  - Classes follow the comparator. The licence is not granted.
  - Agreeing units are reported as unit-level facts. Missing units are listed, never inferred.
  - Status: *"cross-family reproduction partial: N of 67 distinct blind quantities"*. A rerun or a syntax-only adapter is a human decision.
- **Case C, no return obtained:** *"CROSS-FAMILY VALIDATION: NOT COMPLETED"*. This is neither success nor failure. Every downstream claim stays at within-family strength.

## 7. Conclusion (provisional, pending §6)
- **R6-1:** D1's strong form is reading-relative.
  - The weak form survives (R4 Prop 9.6) **in the O-form only**, for (O, 𝓗) with N ≥ 1, P ≠ V and o ≠ ∅.
  - It holds for every tested reading **except the (ii)-O contrast under R-j**.
  - R-a was untested (it needs two targets); R-k was rejected.
  - This is formal derivation plus finite verification.
- **R6-2 and R6-3:** reproduced within the Claude family, with small blind content. Cross-family: not run.
- **R6-4:** reproduced within the Claude family (Opus 5.5 and Sonnet 5.5, 67/67). Cross-family: **waived** (F-LOG-0245).

## 8. Remaining uncertainty
- **Shared Claude interpretation:** the main risk. It stays **untested** because cross-family was waived. Only a non-Claude implementer could test it.
- **Readings invisible in U, and combinations of readings:** named, not bounded.
- **Finite universes only:** U and U_P2. Two targets (R-a) and more records have not been tested.
- **One corpus.**
- **Every reviewer is Claude-family.**

## 9. Decision that follows R6 (for the human)
- **A. Accept the corpus-level non-identifiability of H-D vs H-B, and proceed with bounded claims.**
  - Note 1: R1's non-identifiability rests on H5, adopted with the F-LOG-0190 condition **knowingly waived** (D02, host-side gap disclosed).
  - Note 2: the formal statement is reading-relative and O-only (see §7).
- **B. Authorize E-A5 / Protocol C.**
  - What it can do: a sealed, unexecuted protocol that queries the host repository's metadata. It reopens H1 and needs authentication.
  - **Its recorded limits:**
    - current state only;
    - event retention unverified;
    - Q3 defaults to INDEPENDENCE_NOT_DETERMINABLE;
    - its R4 suitability verdict is "Undetermined".
  - Even a positive result (Q4 = YES) yields a **pre-registered experiment**, not an automatic reopening of R1.
- **C. Defer the decision.** The cross-family wait is moot under the waiver; deferring now means waiting for some other trigger.

V2, V5, V6 and R7 are conditional on this decision.
