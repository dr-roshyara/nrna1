# M-04 Amendment A-1 — corrective review (version 2) — 2026-10-18

| Field | Value |
|---|---|
| Commissioned by | the Principal's corrective review of A-1, findings 1–7 |
| Status of A-1 | **PROPOSED.** Version 2 replaces version 1. **Not approved; not applied as approved method** |
| Approved method | **M-04 v1.0**, unchanged in wording. Reconstructed text and hash in `METHOD-VERSIONS.md`, entry 1 |
| Access | no corpus material read; no reviewer permission changed; only files in `03-methodology/` written (list in §5) |

## A. The revised proposal

The complete text is the section "Amendment A-1 — PROPOSED … (version 2)" in `M-04-working-method-business-language.md`, parts (a)–(h). Parts (g) and (h) are new; parts (a)–(d) and (f) are rewritten; part (e) is unchanged.

## B. Diffs

| Diff | File | What it shows |
|---|---|---|
| approved M-04 v1.0 → M-04 with A-1 v2 | `M-04-A1-v2-diff-against-approved-v1.0.diff` (full context) | **One line removed**: the status line, re-added with the note "Amendment A-1 below is PROPOSED and not covered by this approval". **Every other change is an addition** inside the amendment section. No approved sentence is altered |
| A-1 v1 → A-1 v2 | `M-04-A1-v1-to-v2.diff` (full context) | the corrections of this review |

**Checked.** Removing the amendment section from the current file and restoring the original status line reproduces the approved baseline **byte for byte**.

## C. Finding-by-finding response

| # | Finding | Response | What changed | Remains open |
|---|---|---|---|---|
| 1 | Seven claim kinds mix dimensions | **Corrected.** "Source claim" and "researcher deduction" are **origins**; "decision" is a **standing**; "logical", "implementation" and "empirical" are **domains**; "hypothesis" is a **conclusion**. They could not be one exclusive list | (a): five dimensions (origin · domain · verification with coverage · standing · written conclusion); the evidence rule restated per dimension; the domain table keeps v1's "needs / can establish" content; two register columns from the next batch on | old register rows keep compound marks (not relabelled; stated as a limitation) |
| 2 | Unit of extraction; executable sampling | **Corrected** | (b): the unit is a **claim** with its qualifications; where claims are found (tables, footnotes, examples, code, diagrams, qualifications); split, join and repeat rules; one claim per register row. (f): a deterministic sampling rule with explicit cases *N ≥ n*, *0 < N < n* and *N = 0* | judging whether a passage carries two or more claims remains the auditor's judgement; disputes go to adjudication |
| 3 | Contradiction resolution | **Corrected.** v1 already excluded date and majority (partly covered) but gave no procedure | (c): seven ordered steps (meaning · scope · kind · provenance · authority and adoption · evidence · reconciliation); five outcomes with reasons; date, count and preference excluded as reasons on their own; method decisions reserved to the Principal | — |
| 4 | Glossary existence-only test | **Corrected** | (d): five-part check (single definition · every occurrence of changed terms plus five sampled terms · terms that may not be used bare · missing new terms · disposition); counts reported, with scope | cost: a central term can have about 100 occurrences ("standing": 112 in Chapters 0–2) |
| 5 | Exact five audit measures | **Corrected, with one question for the Principal** | (f): M1 omissions · M2 interpretation errors · M3 overstatements · M4 residual defects · M5 open conflicts, each with counts, denominator, scope and limitation; foundational vs ordinary defined; **no target values**. The default sample size *n* = 10 is a sample size, not a success threshold | **(i)** v1's five measures do not include errors of logic or mathematics, although the approved audit checks "that the mathematics holds" and the Chapters 0–2 audit found 9. v2 reports them alongside, under the approved scope, and leaves adding a sixth measure to the Principal. **(ii)** See the correction below on the Chapters 0–2 audit |
| 6 | Evidence classes | **Corrected** | (g): confirmed · reported · deduction · hypothetical; a hypothetical never presented as an observed defect. v1's "Covers a gap **shown** in simulation A" (A-1 v1, (b)) presented a hypothetical as showing a gap; removed. The evidence for each part is classed in §C.1 | — |
| 7 | Approval and version control | **Corrected, with a limitation** | (h) and `METHOD-VERSIONS.md`: entries with SHA-256 of exact texts; the Principal alone decides; editorial changes get their own entries; the approved v1.0 baseline **reconstructed** and stored without a header; A-1 v1 preserved; full diffs | **(i)** the baseline's identity with what the Principal approved needs the Principal's confirmation of its hash; **(ii)** the workspace is **untracked by git**, so hashes detect changes but cannot prevent them. Version control is a Principal decision |

**A correction to the earlier review.** `M-04-review-20261018.md` §D, test 5, expected the Chapters 0–2 audit to be "recorded with the five measures of A-1(f)". **It was not.** `AUDIT-DISPOSITION-ch00-02.md` records a different, ad hoc set:

| A-1 v2 measure | What the disposition recorded |
|---|---|
| M1 omissions | **not measured** (the term does not occur in the disposition) |
| M2 interpretation errors | 4 |
| M3 overstatements | 12 |
| M4 residual defects | 7 of 29 pilot-review corrections missing or partial (closest equivalent); post-audit residuals "not yet measured" |
| M5 open conflicts | 5, listed with reasons |
| (outside the five) | logic or mathematics errors 9; reference grades S 66 of 84 |

No severity split was made. This is a confirmed instance of finding 5.

### C.1 Evidence for each part, classed (part g)

| Part | Confirmed (with location) | Reported | Deduction | Hypothetical (motivation only) |
|---|---|---|---|---|
| (a) | **71 of 173** register rows carry compound marks such as "SOURCE FACT + DEDUCTION", and the same column mixes origin ("SOURCE FACT") with verification ("VERIFIED RESULT") (`06-theory/IDEA-REGISTER.tsv`, column `epistemic`); 12 overstatements in the audit (`AUDIT-DISPOSITION-ch00-02.md`) | Principal finding 1 | — | simulations A and B |
| (b) | V-2 needed a unit-of-count rule outside M-04 (`V2-REVIEWER-INSTRUCTION.md` §2); V-2 misses lay "within lines I extracted from", invisible to line accounting (`V2-REPORT.md` L50); the 71 compound rows above | Principal finding 2 | — | simulation A |
| (c) | 10 contradiction rows with free-text outcomes and no fixed vocabulary (register); a later document reused labels P-8 and OQ-11 for different content, so ordering by date would have misread it (register B3-F2-03) | Principal finding 3 | — | simulation B |
| (d) | pilot 1, "authority scope" with two meanings (`REVIEW-pilot-01.md` L27); pilot 2, "standing obligation" (`REVIEW-pilot-02.md` L66); batch 3: the glossary **existed** and still carried a refuted formula, and a bare "lifecycle" with a new meaning was found (`REVIEW-batch-03.md` §4, #1 and #10). **So an existence test would have passed while the defects were present** | Principal finding 4 | — | — |
| (e) | — | — | implied by approved step 6a | — |
| (f) | the audit disposition table above | Principal finding 5 | — | — |
| (g) | A-1 v1 (b) wording (`M-04-A1-v1-superseded.md` L24) | Principal finding 6 | — | — |
| (h) | `git status` reports the workspace as untracked; the scratchpad copy of M-04 contained an unapproved section (see `METHOD-VERSIONS.md`) | Principal finding 7 | — | — |

## D. Acceptance criteria (testable on authorized background material)

**Test bed.** The next five authorized background files, in chronological order: `…1034`, `…1040`, `…1044`, `…1057`, `…1144` (`docs/knowledge_tranfer/`). The checks are conformance checks: they show whether A-1 was applied as written, not whether the theory is right. **None has been run.** They apply only if the Principal approves A-1.

| # | Criterion | How tested | Passes when |
|---|---|---|---|
| AC-1 | Dimensions recorded (a) | script over the batch's register rows | every row has `claim_domain` and `standing`; `epistemic` holds exactly one of Src, Ind, Int, Ded, Hyp, Open; `verification` states coverage unless "not examined" |
| AC-2 | Strength rule (a) | the auditor measures M3 on a sample drawn by the rule in (f) | M3 reported with *n*, and every overstatement found has a disposition |
| AC-3 | Unit and omissions (b), (f) | the auditor, **before seeing the register**, lists passages carrying two or more claims, samples them by rule (seed "batch-04"), and extracts the claims | M1 reported with *N* eligible, *n* sampled and the severity split; every omission has a disposition |
| AC-4 | Contradictions (c) | reading every row classed as a contradiction | each has one of the five outcomes and a reason naming the deciding step; none rests on date, count or preference alone |
| AC-5 | Glossary use (d) | the five-file review | the review reports the five counts, the term list and the number of occurrences read; every inconsistency has a disposition |
| AC-6 | Audit measures (f) | the audit report | M1–M5 each present with count, denominator, *n*, scope and severity, or marked "not measured" with a reason; logic and mathematics errors reported alongside |
| AC-7 | Evidence classes (g) | reading A-1 and any later method change | every stated reason carries one class; no hypothetical is cited as an observed defect |
| AC-8 | Versions (h) | `sha256sum` and `diff` as in `METHOD-VERSIONS.md` | hashes recompute; the stored diff equals a fresh diff; A-1's status is PROPOSED until a Principal decision is recorded |

**Dry-run evidence that the procedures can be executed** (not acceptance):
- **sampling rule:** applied to the 12 invariant rows of `…0905` §3 with batch id "batch-04", it selects 10 rows deterministically (L93, 95, 92, 96, 100, 97, 94, 91, 98, 99);
- **glossary check:** occurrence counts in Chapters 0–2 range from 5 ("withheld", "pinned") to 112 ("standing");
- **AC-8:** the baseline round-trip and both hashes recompute (§E).

## E. Verification performed for this proposal

| Check | Result |
|---|---|
| Approved wording unchanged | diff against the baseline: one line removed (the status line, re-added with the PROPOSED note); all other lines are additions inside the amendment |
| Baseline round-trip | removing the amendment and restoring the status line gives the baseline byte for byte |
| Hashes | baseline `32611109…62ca41`; A-1 v1 `597ba7bf…a6b1`; M-04 with A-1 v2 `bc5162b4…37adb` |
| Counterexample attempt on (a) | "V3 records an ARB ruling" = origin cited-only · domain normative · verification not examined (original record not located) · standing exploratory ruling · conclusion Src. It fits without overlap. v1 forced a choice between "source claim" and "decision" |
| Counterexample attempt on (c) | the P-8/OQ-11 label reuse: step 1 (meaning) settles it as **not a contradiction**, a term clash; date order would have treated the later document as overriding |
| Overstatement check of this review | the dry runs are labelled as executability evidence, not acceptance; "reconstructed" is kept on the baseline; the untracked workspace is stated as a limitation |
| Scope of writing | only files in `03-methodology/`; nothing else changed |

## F. Recommendation

**Approve with specified exclusions.**

- **Approve:** parts (a), (b), (c), (d), (e), (f) without a sixth measure, and (g).
- **Approve (h) as a recording practice only.** Excluded from approval until the Principal decides:
  1. **confirmation of the reconstructed v1.0 baseline hash** `32611109…62ca41`. Until then, entry 1 of `METHOD-VERSIONS.md` is a reconstruction, not a verified identity;
  2. **whether to place the workspace under version control.** This is outside the amendment's authority.
- **Decide separately:** whether errors of logic or mathematics become a sixth audit measure (§C, finding 5 (i)).

If the Principal prefers not to decide these points now, the alternative is **return for further correction** on (h) alone. Parts (a)–(g) do not depend on it.

**A-1 remains PROPOSED.** Until the Principal decides, I apply only the approved M-04 v1.0.

## Files written in this round

- `M-04-working-method-business-language.md`: amendment section replaced (v1 → v2); approved text untouched
- `M-04-v1.0-approved-baseline.md`: reconstructed approved text, no header
- `M-04-A1-v1-superseded.md`: A-1 v1 as it stood
- `M-04-A1-v2-diff-against-approved-v1.0.diff`
- `M-04-A1-v1-to-v2.diff`
- `METHOD-VERSIONS.md`
- this review
