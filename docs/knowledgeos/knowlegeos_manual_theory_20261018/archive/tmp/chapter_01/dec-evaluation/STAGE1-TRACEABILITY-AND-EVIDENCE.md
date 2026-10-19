# Stage 1: traceability (source → requirement) and the evidence and uncertainty register (2026-10-18)

## A. Source → requirement

**Status codes:**
- **SRC** source rule;
- **RV** reviewer-verified finding;
- **AR** author-reproduced counterexample;
- **DES** proposed design choice;
- **ASM** new assumption;
- **OBS** store observation;
- **GOV** open governance item.

| Stage 1 requirement | Source | Status |
|---|---|---|
| Decide only within authority; otherwise escalate to AI and the ARB | the Principal's business requirement (2026-10-18) | **governing requirement** |
| A recommendation is never a decision (H-1) | ES-001.2 ("documents record governance; they do not create it"); R-34; INV-ATTR-1 | SRC (ES-001 is hosted in a PROPOSED standard: noted) |
| A reserved choice is fixed only by a referenced ARB act (H-2) | G-2/R5b ("the record never manufactures authority"); ARCH-INV-1 | SRC |
| No defaults for reserved parameters (H-3) | review 2 R-17; the Principal's instruction | RV + DES |
| Append-only; no back-dating (H-4) | S0079, S0091, S0092, S0095, S0103 | SRC |
| V-layer semantics (validity at the time of the act, authority, currency) | TD-001 r1.1 (1)–(3); ALM-R2-SPEC S-4 | **C1 = b fragment:** Th-1/Th-2 RV. **The repaired V layer (Auth split, C1 = a_*, C7, RD 1 per ending kind):** specified, **unreviewed** |
| Completeness conditions (1)–(4) | review-2 false positives N1, N2, N3, R2-04, R2-14 | AR (the counterexamples) + DES (the conditions) |
| A store-absent field counts as ⊥ | the Principal's check | DES |
| The wildcard rule for relevance | needed for Lemma R | DES; proof pending |
| ∀θ ∈ Π | the Principal: "do not silently decide policy" | DES; Th-S2 proved |
| `NO_AUTHORITY_ON_RECORD` ≠ unauthorized | Th-5 existence; "never F under λ⁻ = ∞" | RV |
| Re-examination events | Th-4 (late records change recognition) | RV (construction) + DES (events) |
| Data prerequisites R-Q1…R-Q5 | the census; S0067 L94; S0071 §5.3; R-37; ADR-C6 (S0062) | OBS + SRC |
| Recording deadline / reversal remedy | T1-20; candidate | GOV |

## B. Evidence and uncertainty register

| Item | Evidence level | Remaining uncertainty |
|---|---|---|
| Th-1/Th-2 (validity exists, is unique and local) for C1 = b with explicit periods | **reviewer-verified** proofs; 6,000-trial differential (same-author oracle) | the oracle was not independent (R2-09) |
| Th-4 (late records change recognition) | **reviewer-verified** construction | — |
| No sound negatives under an open world (C1 = b) | **reviewer-verified** ("never F under λ⁻ = ∞"), plus the existence part of Th-5 | — |
| Policy quantification does not remove false positives (N1, N2, N3: 256/256) | **bounded evidence**, author probe on frozen ALM-r1 code | the r1 code is defective in other respects. The scenarios themselves are reviewer-derived |
| C1 is undeterminable without record times | **bounded evidence** (probe) | the r1 C1 = a code is partly defective (R2-08). This simple case does not touch the defect |
| Store field census | **observation**, read-only, 22 records, 2026-10-18 | the store is gitignored; its history and integrity are unverifiable (C-3) |
| Th-S1 safety | **proof sketch**; Lemma R not yet proved | depends on Comp (uncheckable from records) and on the unreviewed V-layer repairs |
| Th-S2 non-usurpation | **proved** (a short argument) | — |
| Th-S3/Th-S4 | stated; not proved | — |
| Stage 1 expected results on the R2 scenarios | **hand-derived** from the spec; not executed | an implementation and an independent oracle are needed |
| The independent oracle (ALM-r2 spec) | **pending**: the agent is still running and had written no files at the time of writing | see §C |

## C. Independent-oracle assessment (interim)

| Aspect | State |
|---|---|
| What it was given | `ALM-R2-SPEC.md` only, with an instruction to read no other file in `tmp/chapter_01/` and no implementation |
| What it targets | the **ALM-r2** spec (broader than Stage 1): the V layer, worlds, ⊥, υ, π and dates |
| Result | **not yet available**: the agent was still running and its output folder was empty |
| Independence limits (known now) | same model family. Spec-only access is **instructed, not enforced**: its own report must state what it read. It works from a specification written by the author, so shared spec misreadings remain possible |
| Relevance to Stage 1 | **partial.** Its V-layer functions (valid, auth, current, elig) can serve as a differential oracle for Stage 1's evaluation of φ under each θ. **Stage 1's own rules** (Clean, Rel, the reason codes, the events) are not covered: **a Stage 1 oracle must be built from STAGE1-SPEC by a separate agent** in the implementation round |
| What would have to be redone | everything Stage-1-specific, plus any V-layer function where the oracle's SPEC-FINDINGS reveal an ambiguity in ALM-R2-SPEC S-4 (to be assessed on completion) |

## C2. Independent-oracle assessment (final, 2026-10-18; after the agent reported)

| Aspect | Finding |
|---|---|
| Produced | `alm-r2/oracle-independent/`: `oracle.py` (943 lines), `selftest.py` (440 lines), `SPEC-FINDINGS.md` (47 findings), `README.md` |
| Method | **genuinely different from the author's code:** brute-force search for the unique solution of the Valid equations; literal world construction for the R layer |
| Verified by me | self-tests rerun: **118/118 pass**. Imports: standard library only (`itertools`, `json`, `math`; the self-test imports only `oracle`). **No reference to any ALM implementation file.** F-36 reproduced with the oracle's own functions: V `auth(x) = T`, `Rec_1 auth(x) = F` under complete records |
| Independence: what holds | separate context; specification-only input; a different algorithm; it **found 47 spec issues**, including 4 false theorem or claim statements. A transliterating oracle would not produce that |
| Independence: what does not hold | same model family. Spec-only access is **self-reported**: the files show no dependence, but reading cannot be proven absent. It works from the author's specification, so shared ambiguities remain; it **flagged them rather than resolving them** (F-08, F-10, F-12…F-17) |
| Material results | **Th-3(b) fails for Auth (F-36)**: Need/Form test *existence* of a target instead of *availability*. **Th-5a fails (F-38)**. **Th-8 needs a premise (F-39)**: entries must be appended in position order. **The S-6.2 eligibility note is false for unrecorded implementers (F-31).** Also F-02 (Auth depends on ρ under a_rho), F-07 (a_tau revocation counting), F-22 (an empty-scope grant is validly made by anyone), F-24 (Restr compares holders, IND-2 compares performers) |
| Consequences for Stage 1 | Stage 1 reuses ALM-R2-SPEC S-4. **F-36, F-37, F-02, F-06, F-07, F-22 and F-24 are V-layer specification defects that Stage 1 inherits.** Before any implementation round, S-4 must be corrected: Need/Form must read **available** acts; the a_* semantics need fixing (F-02, F-06, F-07); empty scopes need handling (F-22). Th-S1's Lemma R depends on the corrected locality. F-38, F-39 and F-31 concern parts Stage 1 does not use (negatives, dates, worlds) |
| What must be redone | (1) correct ALM-R2-SPEC S-4 (V layer), with each oracle finding dispositioned; (2) build a **Stage-1-specific oracle** from `STAGE1-SPEC.md` (Clean, Rel, reason codes, events), which this oracle does not cover |
| Status | **evidence**, not a review. It does not replace the fresh independent review in the exit criteria |
