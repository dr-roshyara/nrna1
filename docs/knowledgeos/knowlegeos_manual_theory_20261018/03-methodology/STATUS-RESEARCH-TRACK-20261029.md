# Research track: status for the Principal (snapshot 2026-10-29)

*Governance Engineer, 2026-10-29, on the Principal's request ("now do the same … for the research side"). A dated snapshot; it decides nothing. The current state is in `GOVERNANCE-ENGINEER-STATE.md`, the decisions in `PRINCIPAL-DECISION-RECORD-RULES-20261018.md`, the deliverables' status in `06-theory/manuscript/BOOK-OUTLINE.md`; where this file and those differ, they govern. Repository at `560b171c5`. Progress is reported against the theory's deliverables (`06-theory/RESEARCH-PURPOSE.md`), not activity (mandate duty 10).*

*Labels: **verified** (checked in the records) · **reported** (another role's claim) · **inferred** (the Governance Engineer's reading).*

## In one sentence

The theory has **a precise but conditional core**, and nearly all of it is still provisional: the kernel's rules can be stated and tested on models, but the minimal set of primitives they should follow from has not been found yet.

## Reading and the register

| Measure | Value | Label |
|---|---|---|
| Sources read in index order | S0001–S0204 settled; S0205–S0207 drafted (window C39), before review | verified |
| Corpus size | about 2,734 sources, so roughly 7–8 % read | reported (strategy §2) |
| Concepts in the register | 265: **52 VALIDATED, 117 PROVISIONAL, 96 INVALID** (as of S0200; no new concept since) | verified (`0ae02e66b`) |
| Next consolidation | **S0225** (all of Ch. 9 reviewed in full); 7 of 25 sources counted | inferred from window ranges |
| Next planted-gap test | **S0250** | verified (rulebook: every second consolidation) |

## Progress against the five deliverables

| Deliverable | Where it stands | Label |
|---|---|---|
| **1. The kernel's primitives** | Candidates named (knowledge object and identity, lifecycle, authority, evidence, relationships, policy, history; plus application-as-recorder, trust, conflict, requirement). Only authority and relationships are validated, each with limits; the others are partly done. **No fixed minimal set.** | verified (BOOK-OUTLINE §1) |
| **2. The laws between them** | Many laws stated and several validated (for example: only an act confers standing; a check blind to subjects cannot enforce a rule about subjects). **Missing: the derivation of laws from the primitives as one chain**; minimality has never been tested. | verified (BOOK-OUTLINE §2) |
| **3. The kernel/application boundary** | Two boundary tests done (recording authority; the evaluator). One design question decided by the Principal and closed as a sketch (F22). The strategic design (Ch. 10) derived from it: turns 1–5 settled, all at the lowest stability level. | verified |
| **4. The open forks** | Kept in `THEORY-MAP.md` (F1–F37, F13a). Several decided by the Principal (below). | verified |
| **5. The kernel's type and existence** | The evaluator of current authority (CE\*) is defined and model-tested; required capabilities, the kernel type, the trust boundary and the designated record are worked out, all provisional. **Whether a minimal kernel exists, and is unique, is open.** | verified (BOOK-OUTLINE §5); the model result `ce_model.py` v22, 46 PASS, 0 FAIL, is reported (the previous Governance Engineer session's re-run, `GOVERNANCE-ENGINEER-STATE.md`; not re-run in this session) |

## Recent work (2026-10-29)

- **Consolidation S0182–S0200 settled** (`e5da8af5a`), with planted-gap test 2 of 4.
- **Strategic design turns 1–5 settled** (`dca72c57f`).
- **G1 theory turn settled** (`8e4b1a420`): how evidence sits in the record. The evidence kinds it models are proposals, not adopted.
- **Window C38 settled** (`b626c1fba`): 14 review findings, all accepted; checked by the Governance Engineer (three minor points kept for later, in `GOVERNANCE-ENGINEER-STATE.md`).
- **Window C39 drafted** (`e36088f56`), not yet reviewed.

## How well the review catches problems (planted-gap tests)

| Test | Found | Label |
|---|---|---|
| S0100 | 4 of 8 | verified (result files) |
| S0150 | 3 of 5 | verified |
| S0200 | 2 of 4 | verified |

**What keeps being missed:** an ordinary word used as if it were a defined term (missed in every test), and a claim that was removed (seen once, but read as an index error). **Improving:** a stale reference elsewhere in the book was caught for the first time at S0200. The test's proposals P1–P3 were **decided as D2–D4** ("accept as recommended", addendum 2026-10-29T02:08:48Z) and are in effect: the R2 tool's `--scope` (`d587cfaf5`), the two lines in `REVIEWER-INSTRUCTIONS.md`, the brief template §1.1. *(Correction, Governance Engineer, 2026-10-29: the first version of this snapshot called them "still open"; that was wrong.)*

## The Principal's design decisions so far (verified, decision record)

T10-01 (b) promise · T10-05 (B) admission · T10-10 (1) own state · T10-16 (b) the record is owned by no area · T9-12 (c) all evidence shown, only authorised evidence counts · T9-16 (b) withdrawing or restoring reliance counts only with a grant over the item's scope; restore keeps its own entry.

## What comes next

| # | Step | Who |
|---|---|---|
| 1 | Apply T9-16 (b) to the model | Professor (authorised) |
| 2 | Review and settle window C39; the Governance Engineer checks it | Professor, reviewer, Governance Engineer |
| 3 | **Question T9-17** (how support and refutation combine), then adopting the evidence kinds, then naming T10-21 — each to the Principal one at a time | Professor → Principal |
| 4 | Revise Ch. 10 for T10-17 to T10-21 | Professor |
| 5 | Confirm the implementation-candidates list | Professor |
| 6 | **S0225 consolidation** (Ch. 9 in full; briefs checked by the Governance Engineer) | Professor and reviewers |
| 7 | **S0250 consolidation with a planted-gap test**, prepared by the Governance Engineer and checked by a separate session before reviewers start | Governance Engineer, Professor |

**The next decision likely to reach the Principal: T9-17.** Nothing needs a decision today.
