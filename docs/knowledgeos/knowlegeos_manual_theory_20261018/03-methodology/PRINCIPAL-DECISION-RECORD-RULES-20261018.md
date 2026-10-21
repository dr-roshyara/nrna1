# Principal decision record: working rules (2026-10-18)

| Field | Value |
|---|---|
| Decision (verbatim) | **"i agree the rules"** |
| Time | the Principal's in-session message, received ≈21:40Z (logged 2026-10-18T21:40:11Z) |
| Refers to | `tmp/readiness/RULE-REVIEW-DECISION-BRIEF-20261018.md`, sha256 `d560c185310b3480598aa5293bb180712f187ac8560ef2d46c1cbe72e1e0d37a` |
| Recorded by | Session B (Professor), in `tmp/`. Repository writes are still frozen (L-8, L-9). **This is not a governance-log entry** |

## What this record treats as approved

The rules as presented in the brief:
- **M-04 v1.0**, at sha256 `32611109…62ca41`, as the approved stable method (D-R1). This closes TODO-01.
- the approved rules AR-1…AR-12 (§3);
- the extraction rules R1–R4 (L-0b);
- the later instructions L-0a…L-15 as in force (§4).

## Not yet acted on: scope to be confirmed

The brief also recommended answers to D-R2…D-R10. Several of them **expand permissions**:
- **D-R5:** write into Chapters 0–2;
- **D-R6:** write the official records into the lane folders;
- **D-R7:** a named human reviewer;
- **D-R9:** version control.

"I agree the rules" does not say explicitly whether it adopts those recommendations. Under the standing instruction (*"Do not interpret a recommendation as an authorization"*), they are **not acted on until confirmed**.

**Separately, corpus access (§8 B, D-C1) is not decided** by this agreement. Corpus reading stays paused at S0008.

---

## Addendum (2026-10-18T21:44:03Z): which rules govern corpus access in this lane

**The Principal's words (in-session, verbatim), replying to my citation of the governance log:**
- "this is not for you ."
- "you have your own rules"
- "your rules are giein in : …/knowlegeos_manual_theory_20261018/03-methodology"

**Earlier the same evening:** "Begin Corpus Research and Construct the KnowledgeOS Theory … Write the theory manuscript in: …/06-theory/manuscript".

**Consequence under the approved rules (M-04 v1.0, step 1: "locked until the approvals are recorded"):**
- the governing record for this lane is its own access record, `00-access/ACCESS-RECORD.md`;
- it already records the Principal's corpus grant verbatim ("you can read everything", 2026-10-18 ≈12:00Z), with the confirmation "yes, sealed files stay excluded";
- the 12:15Z STOP in that record was caused only by checking the S-series governance log (G-LOG-01xx), which the Principal has now ruled does not govern this lane;
- **corpus reading therefore resumes at S0008.**

**Safeguards kept:**
- the allowed-list check before every file (sealed and hold-out files excluded);
- every read logged;
- no corpus content to sub-agents;
- an independent reviewer of corpus material only with separate approval (AR-11).

**Writing:** the manuscript directory `06-theory/manuscript` is authorized by the Principal's instruction. Records and the read log stay in `tmp/`.

---

## Addendum (2026-10-18T21:59:52Z): Principal Decision D-C1 — corpus access authorization

**Decision:** APPROVED. **Decision-maker:** the Principal. **Date:** 2026-10-18 (in-session message, received ≈21:59Z; recorded 2026-10-18T21:59:52Z).
**Answers:** D-C1 in `tmp/readiness/RULE-REVIEW-DECISION-BRIEF-20261018.md` (sha256 `d560c185…`), §8 B.
**Supersedes as access basis:** the inference recorded in the 21:44:03Z addendum above ("corpus reading therefore resumes at S0008"). That text is kept, not rewritten.

**Decision text (verbatim):**

> I authorize the Professor to continue reading the specified original KnowledgeOS corpus and constructing the theory from the source evidence.
>
> The authorization is subject to the following conditions:
>
> 1. The existing permitted corpus scope must be explicitly recorded.
> 2. Sealed and hold-out files remain excluded.
> 3. The applicable access restrictions and hold-out protections must be maintained.
> 4. The Professor must follow the authoritative research rules and preserve source traceability.
> 5. Incomplete definitions, contradictions, and unresolved concepts must be recorded as TODOs rather than resolved through unsupported assumptions.
> 6. This decision authorizes corpus reading and research. It does not independently authorize changes to frozen manuscript chapters or access by independent reviewers.
> 7. I accept the residual hold-out risk described in the corpus-access decision brief, subject to the exclusions and protections recorded above.
>
> Record this decision in the Principal decision record and the corpus access record. Verify that the scope and exclusions are explicit and that the records agree.
>
> Once the decision is formally recorded and the applicable safeguards are verified, the Professor may resume corpus reading from the next unread authorized source, S0011.

**Recorded scope (condition 1)** — identical in `00-access/ACCESS-RECORD.md`, entry of the same time:
- **Permitted:** the 2,734 rows of `knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`, sha256 `1f8e76380b1796ad5ab5f843a5829558b9f9753cd580f7a3409051caaebfc5c1` (2,735 lines incl. header; hash re-verified at recording).
- **Excluded (condition 2):** sealed lists; the 33 sealed hold-out files; the 45 hold-out concepts; measurement data and ledgers; password and `.env` files; any file not on the allowed list.
- **Protections (condition 3):** allowed-list check before every file; every read logged in `tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv` (time, S-ID, path, sha256, depth, purpose); no corpus content to sub-agents outside an approved review scope.
- **Accepted residual risk (condition 7):** the allowed list came from an unreviewed filter (brief: B-2 not met). The Principal accepts that risk, subject to the exclusions above.
- **Not authorized by this decision (condition 6):** changes to frozen chapters (D-R5 still open; L-6, L-9); reviewer access (governed separately by the AR-11 entries in `00-access/ACCESS-RECORD.md`).

**Discrepancy found at recording — for the Principal, not resolved here:**
- The decision names **S0011** as the next unread source. The read log shows **S0011, S0012 and S0013 were already read** (logged 21:50:19Z, 21:55:06Z, 21:56:47Z), with records `E-S0011.md`…`E-S0013.md`, **before** this decision was recorded. On the log, the next unread allowed source is **S0014** (`docs/knowledgeos/KnowledgeOS_Phase_B_Evidence_Reconciliation.md`, on the allowed list).
- S0008–S0013 were read on the 21:44Z inferred basis, not on D-C1. Whether D-C1 covers those reads retroactively, or they must be redone, is a **Principal decision** (cf. TODO-07). Book integrations from them (RC-13 onward) remain subject to condition 6.

---

## Addendum (2026-10-18T22:04:29Z): Rule ML-1 — manuscript layout APPROVED

**The Principal's words (in-session, verbatim, ≈22:03Z):** "then add rule each chapter has its own folder . each section is in seperate file in future. references should be written at end of files. what already done is exception."

- **Rule text:** `03-methodology/RULE-MANUSCRIPT-LAYOUT-20261018.md`; register entry 13 in `METHOD-VERSIONS.md`.
- **In short:** one folder per chapter; every future section in its own file; every section file ends with a References list (source path and S-ID required).
- **Exception:** existing work, including the single-file chapters CH-00, CH-01 and CH-02, is not split or retro-fitted.
- **Not changed:** the chapter freeze (L-6, L-9; D-R5 open), baselines (L-4), access and review rules.

---

## Addendum (2026-10-18T22:08:58Z): Decision D-R5 — writing into the chapters APPROVED

**The Principal's words (in-session, verbatim, ≈22:08Z):** "approve D-R5, professor may update chapters 0–XX . the rest of the chapters should be written . professor should use its ai intelligence to decide how how many chapters ."

**Answers:** D-R5 in `tmp/readiness/RULE-REVIEW-DECISION-BRIEF-20261018.md` (line 228): "May findings be written into Chapters 0–2 under the L-4 revision rule?" The option approved is **"yes, with a targeted review for foundational changes"**. The decision goes beyond the question: it covers **all** chapters, existing and future.

**What is authorized:**
1. **Update existing chapters.** The Professor may update Chapters 0, 1 and 2 with new findings (new ideas, refinements, corrections, evidence).
2. **Write the remaining chapters.** The Professor writes the chapters the theory still needs.
3. **Chapter structure is the Professor's call.** How many chapters there are, and how the theory is divided between them, is decided by the Professor's own reasoning.

**What this lifts:**
- L-6, as far as it says "do not … edit existing chapters";
- L-9, as far as it says "do not modify the manuscript".

**What stays in force:**
- **L-4 continuity rule.** A reviewed chapter is a versioned baseline. Each revision records the previous and new text, the reason and evidence, the type, the impact, and whether review is needed. §1.14.1 is part of Chapter 1, so it is read as covered by this decision, but it is revised only under L-4, like any other baselined section.
- **Targeted review of foundational changes** (part of the approved option). Reviewer access is already covered by the AR-11 standing approval (`00-access/ACCESS-RECORD.md`, 21:53:33Z).
- **Rule ML-1.** Each future section goes in its own file in its chapter's folder, with references at the end; CH-00, CH-01 and CH-02 stay single files (exception).
- **D-C1 condition 5.** Incomplete definitions, contradictions and unresolved concepts go into the chapter's `TODO.md`, not into unsupported assumptions.
- **Still frozen:** read-log rows, idea-register rows and concept-evolution entries in the official lane records (L-9, rest), pending D-R6; repository state outside the manuscript (L-8).

**Open, for the Principal (not decided here):**
- Whether this decision also covers the chapter edits made before it, at 21:45–21:49Z (batch C-1, written while L-6 and L-9 were in force).

**Recommendation (AI, not a rule):** the Professor records the chapter plan (the list of chapters, what each covers, and the reasons) in one file in `06-theory/manuscript/` and updates it when the plan changes, so the structural decision delegated here stays traceable.

---

## Addendum (2026-10-18T22:09:57Z): Rule ML-2 — chapter review folders APPROVED

**The Principal's words (in-session, verbatim, ≈22:09Z):** "professor is writing review in tmp file like this : docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/review/C1-S0006-S0010/PHASE2-REVIEW-REPORT.md. make rule that each chapter folder has a review folder and the review should be written there."

- **Rule text:** `03-methodology/RULE-MANUSCRIPT-LAYOUT-20261018.md`, section "Rule ML-2"; register entry 16.
- **In short:** every chapter folder has a `review/` subfolder; reviews of a chapter are written there, not in `tmp/`.
- **Not changed:** reviewer access (AR-11), blind-first order.
- **Open, for the Principal:** where reviews covering several chapters go; whether existing review files in `tmp/review/` and the manuscript root are moved or stay as an exception.

---

## Addendum (2026-10-18T22:10:26Z): Rule ML-2a — whole-book reviews outside the chapter folders APPROVED

**The Principal's words (in-session, verbatim, ≈22:10Z):** "if the review report is for all chapter then it can write outside the chapter folders"

- **Rule text:** `03-methodology/RULE-MANUSCRIPT-LAYOUT-20261018.md`, section "Rule ML-2a"; register entry 17.
- **In short:** a review of all chapters may be written outside the chapter folders; a single-chapter review stays in that chapter's `review/` folder.
- **Open, for the Principal:** the exact location for whole-book reviews; reviews of several but not all chapters.

---

## Addendum (2026-10-18T22:11:14Z): Rule ML-2b — location of all-chapter reviews APPROVED

**The Principal's words (in-session, verbatim):** "use 06-theory/manuscript/review/ for all-chapter reviews"

- **Rule text:** `03-methodology/RULE-MANUSCRIPT-LAYOUT-20261018.md`, section "Rule ML-2b"; register entry 18.
- **In short:** all-chapter review reports go in `06-theory/manuscript/review/`.
- **Still open:** reviews of several but not all chapters; moving existing review files.

---

## Addendum (2026-10-18T22:20:49Z): Rule CV-1 — concept IDs and validation APPROVED, with two statuses

**The Principal's words (in-session, verbatim):** "approve CV-1 with two statuses: validated and invalid"

- **Rule text:** `03-methodology/RULE-CONCEPT-VALIDATION-20261018.md` (sha256 `cb5a7c178ed1b74b98f81cbb7ba47c0c5bb41329a3b68c344c4f5e7a71ea6361`); register entry 21.
- **The proposed four statuses were reduced to two,** as decided: **VALIDATED** and **INVALID**. The reason for INVALID (refuted · partly valid · not verifiable · not yet checked) is kept in the `basis` field. No other substantive change from the PROPOSED text (entry 20).
- **In short:** every concept gets a `CON-NNNN` ID; INVALID concepts and ideas are marked under their ID, entered in the chapter `TODO.md`, and work continues; the independent reviewer makes a second attempt at the next five-file review; if it also fails, the item stays INVALID.
- **Still open:** the register location (`06-theory/CONCEPT-REGISTER.tsv` proposed; needs D-R6 or an exception to L-9); back-fill of Chapters 0–2.

---

## Addendum (2026-10-18T22:21:35Z): Rule CV-1 §2a — several concepts for the same thing

**The Principal's words (in-session, verbatim):** "if there are multiple concept for the same thing, each concept should be recorded in the corresponding chapter."

- **Rule text:** `03-methodology/RULE-CONCEPT-VALIDATION-20261018.md`, section 2a, and the new register field `same_subject_as` (sha256 `a04ffcebc88027bcbbd4521efd7be5ee104bf84709e24d80ad5ee21618ea3e8b`); register entry 22.
- **Read as:** each concept of the same thing gets its own ID, is recorded and explained in the chapter whose subject it belongs to, is validated on its own, is linked to the others, and carries a qualified name. The rest of CV-1 is unchanged.

---

## Addendum (2026-10-18T22:22:15Z): concept register location — exception to L-9

**The Principal's words (in-session, verbatim):** "use 06-theory/CONCEPT-REGISTER.tsv as exception to L-9"

- **Decided:** the CV-1 concept register is `06-theory/CONCEPT-REGISTER.tsv`. The Professor may create and write its rows despite L-9.
- **Scope of the exception:** this one file only. L-9 still freezes read-log rows, idea-register rows and concept-evolution entries, pending D-R6.
- **Consequence for CV-1 §4a:** until D-R6, an idea found INVALID is recorded in the chapter `TODO.md` (with its idea ID), not in the idea register's `verification` column.
- **Created:** the file with its header row only (the 13 fields of CV-1 §6); no concept rows.
- Rule text updated: `03-methodology/RULE-CONCEPT-VALIDATION-20261018.md` (sha256 `eeb1ae28f6eca0a233261fa9b61b8a02e25ac373cd7bdbf7d802309fcf4a951e`); register entry 23.

---

## Addendum (2026-10-18T22:25:42Z): Rule SQ-1 — sequential theory, gap check by the independent reviewer APPROVED

**The Principal's words (in-session, verbatim, ≈22:25Z):** "Independent reviwer should inspect that if the theory is defined sequencial way and there is should not be gap in between. if there is a gap in the defintion of concept and ideas and theory it should be recoreded too."

- **Rule text:** `03-methodology/RULE-SEQUENTIAL-THEORY-20261018.md` (sha256 `fedcca60131adaa91eb0afdb3197cd563703154a5e03467476c9fc7cb5eb6965`); register entry 24.
- **In short:** at each five-file review the independent reviewer checks that concepts are defined before use, each step rests on what came before, and forward references are marked; every gap gets a `SEQ-NNNN` ID and is recorded in the chapter `TODO.md` (and in the concept's `follow_up` when it concerns a definition); work continues.
- **Open:** a periodic whole-book sequence check; a one-off check of Chapters 0–2.

---

## Addendum (2026-10-18T22:29:39Z): rules apply forward; no retroactive correction of past work

**The Principal's words (in-session, verbatim):** "follow the rules in future . you must not correct everythign for the past work."

**Read as:**
- the rules (D-R5, ML-1/ML-2, CV-1 and the others) govern work **from now on**;
- past work is **not** corrected retroactively to meet rules that came later. In particular:
  - no CV-1 concept-ID **back-fill** of records and chapters written before CV-1 (this answers CV-1's open point 2: no one-off back-fill; concepts get IDs when new work touches them);
  - no further retroactive repair of the pre-D-R5 edit history: the C-2 revision index already appended to `BASELINE-REVISIONS.md` (22:29Z) stays as written; no further reconstruction;
  - no retroactive independent review of S0001–S0005; past findings keep the review status they have, stated as it is;
  - the edits made before D-R5 (RC-1 … RC-28) are left as they are; their timing stays visible in the record.
- **Not changed:** what is already written stays; nothing is deleted or relabelled.

## Addendum (2026-10-19T08:01:09Z): Rule RK-1 — record keeping and pace, APPROVED

- **Principal (verbatim):** "yes approve, write the addendum and apply from S0026"
- **Rule text:** `03-methodology/RULE-RECORD-KEEPING-20261019.md` (sha256 `dae25ab07f47827d85cb41dc9a5d0234b3852e45405566a2816d89f0102f0608`); register entry 25.
- **Effect:** from S0026, each fact about a source has one home; before and after hashes are recorded once per five-file batch; INVALID concepts are tracked in the concept register; chapter change logs are closed; at most two sources per turn; a strength check and a real counterexample attempt come before VALIDATED; records are shorter. Quote check, blind five-file review, CV-1 §8a and SQ-1 are unchanged.
- **Not changed:** work up to S0025 and the handling of review C4.

## Addendum (2026-10-19T08:11:45Z): Rule RV-1 — the independent reviewer's mandate, APPROVED

- **Principal's message:** a role definition of the independent reviewer (three responsibilities: conceptual correctness, mathematical derivation, sequential flow; source support assessed where it affects conceptual validity or the reasoning; routine evidence administration remains the Professor's; applied to each five-source batch and to periodic whole-book reviews), ending: "This is the proposed role definition; it should be recorded as a rule amendment before the Professor treats it as formally in force."
- **Recorded as:** `03-methodology/RULE-REVIEWER-MANDATE-20261019.md` (sha256 `383934dd40e220aab13516d5a126b81734759e4930bfa62ae74315dd1ad685d1`); register entry 26. In force from review C5.
- **Open:** the timing of whole-book reviews (rule §7).
- **Reading, stated so the Principal can correct it:** the message is taken as the Principal's instruction to record the definition as a rule in force, not as a proposal awaiting a further decision.

## Addendum (2026-10-19T08:24:52Z): RV-1 independence safeguards, APPROVED; two points left open

- **Principal (verbatim):** "yes record item 1, leave 2 and 3 open"
- **Recorded:** `RULE-REVIEWER-MANDATE-20261019.md`, addendum "keeping the reviewer independent" (sha256 `20d7424af1c26c11cd2cc7b7cd0bbafa8bb4b83412966344b9cbb73fceeab4dd`); register entry 26b.
- **Left open by the Principal:** (2) when whole-book reviews run; (3) commits of the workspace at checkpoints.

## Addendum (2026-10-19T08:31:40Z): Rulebook v1 APPROVED

- **Principal (verbatim):** "yes approve the rulebook, continue with S0028"
- **Text:** `03-methodology/RULEBOOK-v1.md` (sha256 `dc499ee76b56384d36359d0b17966d19274de4e9f68008258070306de44a64d2`); register entry 28. In force from S0028.

## Addendum (2026-10-19T08:51:41Z): Rulebook v1.1 — the core loop, APPROVED

- **Principal (verbatim):** "use ai intelligence-> read corpus file->validated the theory concept->check if already given->if yes->update->if not write new concept in new section/subsection->make reference at end of the file->mark as read->if you cant valid the concept with your intelligence, if you are intelligence say this concept might not valid->write in todos -> you should not care if the wording before and after match. you focus if the theory makes sense and valid . ->you ask reviwer after five files that you wrote these things, do you agree. thats it."
- **Text:** `03-methodology/RULEBOOK-v1.1.md` (sha256 `0f79fadd86b400ed8bf029b6a872f1ee26cc11bd6087fe59c4254886af056b41`), consolidated; register entries 29 and 29a. `RULEBOOK-v1.md` is superseded and kept as history.
- **Kept from earlier approvals:** the reviewer's blind first reading (RV-1); the Principal may drop it.
- **Open:** whole-book review timing; commits at checkpoints.

## Addendum (2026-10-19T08:55:12Z): Rulebook v1.2 — the reviewer reviews the transition, APPROVED

- **Principal (verbatim):** "I want to make sure that the reviewer must not  review the whole theory every time from start . reveiwer should start form transition. what was developed before, what is new ? if it mataches . if it valid mathematically, statistically , computationally. if the concept needs to be explained more clearly.  that's it"
- **Text:** `RULEBOOK-v1.1.md` §5, amended in place (before `0f79fadd86b400ed8bf029b6a872f1ee26cc11bd6087fe59c4254886af056b41`, after `8e2473a991a8f3f96a3c603f6ee0240bcc4dc69e71c19e010159d29d7aa97043`); register entry 30.

## Addendum (2026-10-19T09:09:28Z): Rulebook v1.3 APPROVED (items 1–7)

- **Principal (verbatim):** "hello professor! update the rules:", with a pasted review recommending items 1, 2, 4, 5 and 7, item 3 with its condition, and item 6 with three adjustments (whole-source scan; the index only suggests depth; the reviewer reads one skimmed source blind per batch; no-new-concept runs are information, not a stopping rule).
- **Text:** `03-methodology/RULEBOOK-v1.3.md` (sha256 `de5b50d62fc76e28cd705f1ff7e4ce7abd71ea8ca31a510459138a236d920c11`); register entries 24-corr, 31, 31.1–31.7.
- **Item 6 amends** the original instruction to read every source in full: every source is still opened and scanned in full; depth decides the integration effort.

## Addendum (2026-10-19T09:11:26Z): trial of a topic-driven research branch, APPROVED

- **Principal:** "yes" (to the trial on Zero/Gap with the four safeguards and bounds, including allowed-list-only searches out of order).
- **Register entry 32.** A trial, not a rule amendment.

## Addendum (2026-10-19T09:26:10Z): Amendment 32a (hybrid research method) APPROVED

- **Principal (verbatim, excerpt):** "I approve the five fixes and the operating limits in principle." / "verify that the script and records enforce the rules, rather than merely documenting them." / "Do not make unrelated changes or expand the authorization beyond this amendment."
- **Text:** `03-methodology/AMENDMENT-32a-hybrid-research-method.md` (sha256 `98304e2c0c93fafef318836c1b9285c1cb1015f0fcd426702ae114f2ba570dba`); register entry 32a. Entry 32 and the trial record are preserved.

## Addendum (2026-10-19T09:41:23Z): Rulebook §11 and Amendment 32b APPROVED

- **Principal (verbatim):** "yes" (to adding 32a to the rulebook, and to the snapshot rule).
- **Texts:** `RULEBOOK-v1.3.md` §11 (sha256 `bf97721363b689cfdb507ea7ee6afda912e28eec19657bd3f27952cffb7cfec3`); `AMENDMENT-32a-hybrid-research-method.md` §32b (sha256 `43b3d8e4f59727643529d5d90e8c2ad9e7ae230aef17eb1c0f04ae99da499735`); register entries 31a and 32b.

## Addendum (2026-10-19T09:56:23Z): review question 6 (coverage)

- **Principal (verbatim):** "Exactly for this reason the reviewer is there. you provide what you have done and the reviewer verify the validity and verify if any concept is left behind."
- **Recorded:** `RULEBOOK-v1.3.md` §6.3, question 6; register 31b. The proposed per-source triage line is **not** adopted.

## Addendum (2026-10-19T10:01:35Z): review question 6 reworded; claim-scoping adopted

- **Principal:** question 6 to read "What relevant theory, concept, distinction, assumption, or finding present in the reviewed sources is missing from the Professor's work?", answered from independent examination; the review continues to assess validity, support, overstatement, omission, faithfulness and needed corrections; claim-scoping before recording; **no separate source-triage rule** unless later authorized.
- **Recorded:** `RULEBOOK-v1.3.md` §4 and §6.3 (before `bbf24bfb559da03d62c5763b225d68d117c466b0a8ed71b0d105e09ef66bbd83`, after `bdfe1c8383061787bcffb5e4123d5f632b2524c908bc8b9266a5932769ed2e4d`); register 31c.

---

## Addendum (2026-10-19T10:17:28Z): Trial T1 — topic first, for the next five sources APPROVED AS A TRIAL

**The Principal's words (in-session, verbatim):** "we can adopt as trial for next five files and then compare both strategy ."

- **Trial text:** `03-methodology/TRIAL-T1-topic-first-20261019.md` (sha256 `daeefa898816d6fb967c87958f6c4bafe9098bfd08400cae83bae92249567676`); register entry 33.
- **In short:** one factual or definitional topic; five sources read in full in date order (`topic-full`, not moving the chronological position); section written; independent review with a per-concept verdict and question 6; then a comparison note against group C6 (S0031–S0035).
- **Reviewer access** for this trial is granted by this approval: the five trial sources, the new section and the theory map, listed in the access record before the reviewer starts.
- **Not changed:** Rulebook v1.3 §2 and §11 outside the trial; every source eventually opened and logged; no source skipped.

---

## Addendum (2026-10-19): repository evidence (Rulebook v2 proposal, Q1) — APPROVED, applies with v2 only

- **Question** (`RULEBOOK-v2-PROPOSAL-REPORT.md` §10, Q1): may the Professor read repository files outside the allowed list as evidence ("Repo")?
- **Principal (verbatim):** "Q1: may the Professor read repository files outside the allowed list as evidence, and which paths? yes ." Scope then chosen by the Principal from the options put: **"Implementation only"**; and when it applies: **"With v2 only"**.
- **Scope:** code and configuration in `app/`, `config/`, `routes/`, `database/migrations/`, `tests/`, `scripts/`. **Excluded:** all of `docs/knowledgeos/` outside `CORPUS-ALLOWED.tsv`; sealed lists, the sealed hold-out files and the hold-out concepts; `.env` and any secret; `*.sql` backups; `*.csv` and other data files; logs; `storage/`, `vendor/`, `node_modules/`. Every read logged with path, commit and sha256.
- **Applies:** from the start of a v2 trial or v2 adoption, whichever the Principal approves first. **Not in force under Rulebook v1.3.** D-C1 and its exclusions are unchanged.
- **When it becomes active:** a matching scope entry goes into `00-access/ACCESS-RECORD.md`.

## Addendum (2026-10-19T19:01:43Z): "theory first, evidence driven, continuously challenged" — recorded in Rulebook v1.3 §1

- **Principal (verbatim):** "yes, add them to v2 and record it in v1.3" (to the proposal to record the Principal's statement as a principle in v1.3 §1 and to add its consequences to the v2 proposal).
- **The statement's verbatim text:** `RULEBOOK-v1.3.md` §1 (its canonical home; not copied here). Register entry 54.
- **In force from now,** including the S0100 whole-book review. The v2 additions (Professor's role, attribution vs judgement, methods, canonical homes, optimisation at fixed points) remain PROPOSED with v2.

## Addendum (2026-10-19T20:09:00Z): whole-book review S0100 — the `--root` option of `dependency_graph.py` (clarification) and the isolation audit file

- **The record before this clarification was ambiguous.** The Principal's earlier instruction read: "The `--root` change to `dependency_graph.py` is approved, provided the frozen review copy and its sealed file list remain unchanged." The Rule Reviewer had recommended option 1 (no tool change, a link to the copy), and read the decision as option 1; the Professor read it as option 2 and added `--root`.
- **Principal (verbatim):** "Decision now: I confirm option 2: the `--root` tool change is approved, provided it does not alter the frozen copy or its sealed manifest. Record this clarification in the decision or session log so there is no ambiguity about authorization."
- **Condition checked:** the frozen copy matched its sealed manifest (119 of 119 files) at the Rule Reviewer's check on 2026-10-19, after the change.
- **`ISOLATION-AUDIT.md` (Principal):** "yes, add it now". The Professor creates `06-theory/manuscript/review/WHOLE-BOOK-S0100/ISOLATION-AUDIT.md` now and updates it after step 2 and after the integration review: per reviewer and stage, identity, result, files opened, searches run, any detected or suspected breach; an assessment (pass / breach / unresolved) with its evidence and its effect on affected findings; and the limit that the audit shows observed access, not that prohibited access was technically impossible. Stages not yet audited are marked pending; earlier results are not rewritten.

## Addendum (2026-10-20T07:16:47Z): S0100 stage 2 — decision briefs DB-1 to DB-7, the register column, and access to the Ch. 0–2 basis files

- **Principal (verbatim):** "yes" (to recording the three decisions of the merged instruction before it is sent).
- **DB-1 to DB-7** (`06-theory/manuscript/review/WHOLE-BOOK-S0100/DECISION-BRIEFS-S0100.md`), **accepted as the Professor recommended:**
  - DB-1: A1 is treated as a **norm**, with named readings; the empirical claim is kept as a separate hypothesis;
  - DB-2: independence is **graded and factor-relative**; instances inside one estate count as recurrence, not as independent confirmation;
  - DB-3: K4 (prevent or detect) is **not decided** until the evaluator and the kernel type exist;
  - DB-4: Ch. 8 is **split** into verification laws and validation of the book;
  - DB-5: the bearer of the dimensions stays a **definitional fork** (F37); the kernel can carry both, one bearer per dimension;
  - DB-6: Ch. 7 is **retired** as a separate chapter;
  - DB-7: `TARGET-TOC.md` is adopted **as a reading order with stable section numbers**; no renumbering. `CHAPTER-PLAN.md` is superseded by it as the reading order.
- **No new register column** for kernel/application marks: the register's fields stay as CV-1 fixes them; the marks are kept in `06-theory/manuscript/BOOK-OUTLINE.md` only (one home).
- **Access:** the Professor may read, **read-only**, the Ch. 0–2 basis files logged with purpose "basis:" in `archive/00-access/READ-LOG.tsv` (sha256 `4eb2b2a31b7bda885414454a6a39157442e41193040c88e67d44fbb18f50b2e6`; 110 files; the corpus frame row excluded), **for quote and meaning checks only**. Recorded in `00-access/ACCESS-RECORD.md` before use. Sealed and hold-out material never; D-C1 otherwise unchanged.

## Addendum (2026-10-20T07:24:00Z): rule freeze until the S0150 evaluation; four research practices tested through the instruction

- **Principal (verbatim):** "yes I agree" (to the Rule Reviewer's response to the senior research review of v1.3).
- **Rule freeze:** no new rule entries in `METHOD-VERSIONS.md` and no change to `RULEBOOK-v1.3.md` or `REVIEWER-INSTRUCTIONS.md` until the evaluation at the S0150 consolidation (entry 58), **except for a critical failure**: one that would let a wrong result through. Binding on the Rule Reviewer and the Professor alike.
- **Four practices, run through the Professor's instruction and evaluated at S0150, not rules:**
  1. **a research question per theory turn**, stated first: what must be explained, what is necessary or derivable, what would disprove the proposed answer;
  2. **prior work for every result that claims a new theorem, mechanism or generalisation**: a search of textbooks, peer-reviewed work and standards; definitions, assumptions and results compared, not names; the idea stated as established, adapted, extended, contradicted, or possibly new (qualified by the search); each work used recorded in `06-theory/EXTERNAL-SOURCES.md`; no corpus text in any search;
  3. **read before claiming:** text written by a forked or helper session counts as the Professor's only after the Professor has read it; until then it is listed in the brief as "not read by the author";
  4. **effort by risk:** editorial changes get the mechanical checks; changes of substance the window review; foundational changes two reviewers.
- **At S0150:** one consolidated rule revision, adopting what proved itself and dropping what did not; the decision whether v2 replaces v1.3 is taken there. The S0150 evaluation also records the Professor's time by activity (research vs verification and governance) and asks whether the same assurance could have been had with less work.
- **Evidence behind it:** `EXTERNAL-SOURCES.md` lists 29 works, all the Principal's brainstorms, none a textbook, paper or standard; recent results (history variables, pairwise vs global consistency, bitemporal state) restate known results under other names; `METHOD-VERSIONS.md` grew from entry 33 to 60 in two days.

## Addendum (2026-10-20T07:29:33Z): two further practices through the instruction (interpretation, not a rule change); two items pending for S0150

- **Principal (verbatim):** "yes" (to the Rule Reviewer's analysis of the senior research review of Rulebook v2).
- **Practice 5, challenge by type** — the Rule Reviewer's **interpretation of v1.3 entry 55** during the freeze: mathematical results are challenged by a countermodel or an invalid step; empirical claims by a contradicting observation and the sampling; definitions by boundary cases, coherence, consequences and a rival definition, with **no falsifier required**; normative questions by the decision-maker and the consequences of each option. "Not testable as stated" applies only to claims that should be testable.
- **Practice 6, verification marks:** each changed piece of theory is marked in the brief with author (Professor or helper) · read by the Professor (yes/no) · reasoning checked by the Professor (yes/no).
- **Pending for the S0150 consolidated revision** (no rule text changes now): challenge by type as a rule; the canonical home of a concept divided between the register (identity, status, review, links, at most a one-line gloss) and the defining section (definition and argument; it governs when they disagree). Listed in `RULEBOOK-v2-PROPOSAL-REPORT.md` §19.

## Addendum (2026-10-20T08:13:17Z): practice 2 revised — prior work, corpus first

- **Principal (verbatim):** "yes" (to revising practice 2 so that the corpus is searched before outside literature).
- **Replaces** practice 2 of the addendum "rule freeze until the S0150 evaluation" (that text stays as history). **Practice 2 now reads:** for every result that claims a new theorem, mechanism or generalisation:
  1. **search the allowed corpus first**, by logged search (`tmp/tools/branch_search.py --log`; terms from the register and the book, and the known names of the idea); relevant hits are read whole as `topic-full`, within the turn's limit of 8 sources, and never move the reading position;
  2. **what the corpus says is cited as Src**, with its own assumptions, even when a textbook says the same; when index order later reaches a source already read this way, it is checked against the source again, not only against the book;
  3. **then the outside literature**, as a **benchmark** (does the corpus's version match, adapt or contradict the established result?) or, where the corpus search found nothing within its stated scope, as a **gap-filler** (Ext); the idea is stated as established, adapted, extended, contradicted, or possibly new, qualified by both searches;
  4. **no corpus text** goes into any outside search.
- **Why:** about 96 % of the allowed corpus is unread; an outside search first would credit to Ext what the corpus says (against v1.3 §4), lose the corpus's own version of an idea, and frame later reading by hindsight.

## Addendum (2026-10-20T17:25:51Z): theory turn 4 — three decisions on the evaluator of current authority (CE*, Ch. 9 §9.13)

- **Principal (verbatim):** "i accept your recommendation" (the Rule Reviewer's recommendations on the three decisions put by the turn-4 settlement, `fb1e67be5`).
- **C1 (the fork of §1.13.1-2): records constitute authority within the kernel.** Whether a recorded act really occurred stays an assumption about the world, as in the trust turn (§9.9); the two questions are kept apart. Consistent with DB-1 (A1 as a norm).
- **Loops of authority: the domain D allows citations of earlier entries only** (the corpus's acyclic recommendation). Under it the forced (least fixed point) and grounded (well-founded) readings coincide. The book states the design limit: delegation patterns that would need a later entry must be written in order.
- **Self-cancelling acts** (a holder annuls the grant it acts under): **the answer is U, with a refusal reason**, not resolved by causal priority, which the book does not define. A priority rule may be added later only as a defined rule, on evidence.
- **With these decisions:** the turn-5 brief lists all of §9.13's settlement changes as changed theory, including the new acyclic proof; under practice 2 the Professor checks whether "acyclic, so the readings coincide" is a known result (for example Apt and Bezem 1991 on acyclic logic programs; the Kripke–Kleene and well-founded literature) and cites it if so.

## Addendum (2026-10-20T17:52:41Z): §9.13 v3 — mutual annulment confirmed as U

- **Principal (verbatim):** "yes" (to confirming the extension the Professor marked in §9.13 v3, `d4ffbc692`, §9.13 question R2).
- **Decision:** mutual annulment (two acts annulling each other, the other negative cycle) gives **U, with a refusal reason naming both acts**, as for a self-cancelling act. No priority rule is defined, so neither act prevails. §9.13 records it as decided, not as an extension.
- **Advice with it (not a decision):** if the turn-5 brief becomes too long, the review is split into two commissions, §9.13's changes and global consistency, both settled before turn 6.

## Addendum (2026-10-20T19:03:44Z): theory turn 5 — two decisions on the evaluator's domain (§9.13 v4)

- **Principal (verbatim):** "Shall I record the two decisions in the decision record? yes" (the Rule Reviewer's recommendations on the two decisions put by the turn-5 settlement, `454df3aa4`).
- **D is kept: citations of earlier entries only**, not D_acyclic (no citation cycles). Reasons: under C1 (records constitute authority) a grant exists only once recorded, so citing a grant recorded later would be authorization after the fact; and D is checkable at each new entry (a law on each step), while D_acyclic needs a check of the whole record and admits citations that point at nothing until the cited entry arrives. The design limit (delegation written in order) stands as stated in §9.13.
- **Indirect self-cancelling is covered by decision 3** (turn 4): an act that annuls any link of its own authorization chain gives **U**, with a refusal reason naming the act and the annulled link.
- **With these decisions (an instruction, not a rule change):** the turn-6 brief lists **all** changes of the turn-5 settlement as changed theory (§9.13 v4 and §9.14 v2 in full, including the new row and its proof), not "where used" (v1.3 §2 step 9); if the brief becomes too long, the review is split into commissions as in turn 5.

## Addendum (2026-10-20T22:18:11Z): decisions of turns 6 and 7 and of window C19, recorded together

- **Principal (verbatim):** "yes" (to recording F22 together with the turn-6 and turn-7 decisions, which were sent to the Professor on the Rule Reviewer's recommendations and applied in `979e10362` and the C19 settlement `51f2b3328`; recorded here afterwards).
- **Turn 6** (settlement `44cf59fb1`):
  1. **Decision 3 read as applied by the Professor:** an act that annuls a link of its own authorization chain is U only when nothing else decides it; T with another intact basis; F when the link is already defeated; the reason names the act and the link. Roots cannot be annulled (turn 5); a cited basis the act does not need is irrelevant (reasons follow only what the answer rests on).
  2. **Recorder and authentication status:** D requires both fields on every entry, apart from the actor; "unsigned" is an allowed value. Q1 **marks** answers resting on transcribed or unsigned entries; it does not refuse them.
  3. **Removal of a cited entry by tombstone:** the loss entry keeps identity, kind and the fields citations rely on and drops the content; the record stays in D; answers resting on it are marked "rests on removed content". A partial answer to F30.
- **Turn 7** (settlement `d1fb113e9`):
  1. **Prevent or detect:** prevent a violation when its effects cannot be undone by a later recorded act; otherwise detect it. Applied per law by the Professor, with reasons, for review (the Rule Reviewer's reading: prevent single use and once-only handling of held acts; detect the other three).
  2. **Ties:** acts at the same time are unordered; the answer is **U**.
  3. Tombstones (every field D requires; a removal is an authorized, recorded act the tombstone cites), the wider sound marks, consumption admitted into D, R6's option per law for F36, and R7 provisionally as Int: **the Professor's technical choices**, reviewed in C19.
  4. **Practice:** the Professor decides definitional and technical choices with stated reasons, marked for review, and brings the Principal only normative questions.
- **F22 (window C19): (C + O).** The kernel holds the contract; the contract names **one owner application per grant**, fixed at issuance; a change of owner is a recorded, authorized act; the kernel refuses a use of a grant not recorded by its owner. **Cost, to be stated in the book:** a grant cannot be used while its owner is unavailable, unless ownership is moved. Prior work (single writer / leader per partition) checked under practice 2, corpus first. Applied as changed theory in C20's review.

## Addendum (2026-10-21T05:28:30Z): window C20 — ownership transfer and "use" under F22 = (C + O)

- **Principal (verbatim):** "yes" (the Rule Reviewer's recommendations on the two questions of the C20 settlement, `0b3398e50`).
- **Ownership transfer:** only the grant's **issuer**, or an authority above the issuer's scope (to replace an absent issuer), may transfer ownership of a grant; **never the holder**. Reason: a holder choosing its own coordinator could move ownership to an application whose record lacks the earlier uses and use a single-use grant twice; the former rule ("any authority over the grant's scope") included the holder. A transfer must carry the record of earlier uses to the new owner (for example by citing the old owner's last use entry); how, is the Professor's technical work, tested by the random check.
- **A use:** every act under a grant marked **single-use** is a use and is owner-checked; grants that are not single-use need no owner check.
- **With it (instruction):** time per source reported from C20 on, as planned; a window's main work is its five new sources, kernel work proportionate unless a critical finding requires more.
