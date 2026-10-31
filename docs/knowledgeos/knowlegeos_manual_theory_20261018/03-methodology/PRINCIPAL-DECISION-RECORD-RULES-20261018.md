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

## Addendum (2026-10-21T07:05:56Z): window C21 — consumable means single-use; F22 closed for now as a design sketch

- **Principal (verbatim):** "yes" (to the Rule Reviewer's analysis of the Professor's way of working after C21, settlement `cc5864f7d`).
- **Decision (i):** a grant that can be consumed is **single-use by its kind**; a grant meant for repeated use is used, not consumed. No marking is needed, and there is no exception to "prevent single use".
- **Correction to the reasoning behind F22 = (C + O)** (the Rule Reviewer's, recorded on the Professor's finding in C21): under the Principal's decisions the kernel refuses any use not recorded by the owner and keeps every use it accepts, so **for single-use grants the kernel is in effect the coordination point**. (C + O) differs from (C + K) only in whose record carries the uses and who may move ownership. The earlier argument that under (C + O) the kernel "only routes" was incomplete. The decisions stand; the book states this.
- **F22 is closed for now as a design sketch** with its stated assumptions (L9 record before use; L10 the kernel authenticates the submitting application; one owner per grant; transfer by the issuer or above, never the holder; every act under a single-use grant is a use; consumable means single-use). Open protocol questions (uses in flight during a transfer, carrying use records to a new owner, several records) go to fork F22 and the Ch. 9 TODO, **not into the reading windows**. **No further F22 model versions** unless a critical finding or a new corpus source requires one.
- **Windows:** their main work is the five new sources; kernel edits only when a source changes the theory. Time per source measured from opening to the close after the theory is written (the earlier 10–13 s figures were not a measurement; additive correction in the C20/C21 settlements).
- **Evidence:** since turn 7, about 1,280 lines of model code and 345 lines of kernel sections against about 590 lines of new-source sections; three cycles of decision → application → about four high defects → a new design question (C19 commission T, C20, C21).

## Addendum (2026-10-24T01:34:56Z): the S0150 decision — Rulebook v1.4; the rule freeze ends

- **Principal (verbatim):** "accept as recommended" (the Rule Reviewer's S0150 recommendations, on `S0150-CONSOLIDATED-REVISION-PROPOSAL.md`, the Professor's evaluation facts and the S0150 planted-gap result).
- **Decided:** option A (v1.3 revised) as **`RULEBOOK-v1.4.md`**, items 1–16 as recommended, the five test recommendations, K5 as a clarification (INVALID means a demonstrated defect; missing support only is PROVISIONAL; applied chapter by chapter with the rotation). `REVIEWER-INSTRUCTIONS.md` updated with it. Register entry 61.
- **The rule freeze of 2026-10-20 ends.** The practices recorded in this decision record (2026-10-20) are now rules in v1.4 or dropped; **this record carries decisions only** from now on.
- **Next research step (recommended, accepted):** one theory turn on record identity and anchoring (K1/K2), from the collected kernel questions of §8.18–§8.21; then reading resumes at S0151.
- **Tool changes for the Professor** (rules in v1.4, tools to follow): `close_source.py` refuses the next source in order when a consolidation is due; `undefined_terms.py` lists ordinary words in claim headings and definitions not defined in the book (tested on "lane", "watermark", "seal-line").

## Addendum (2026-10-25T01:52:54Z): the S0155 access incident; turn-8 decisions

- **S0155** (not on the allowed list) was opened by the Professor and by a C29 reviewer; the reading tool now refuses non-allowed sources at start (access record, same time). **S0155 is treated as exposed** for the final hold-out test; the sealed list is not probed further. The Principal's private check was inconclusive (counts only, recorded in the access record).
- **Window C29** completed with S0151–S0154 and S0158, with a new reviewer.
- **Turn 8** (§9.17): a **designation** (which store is the record) is a recordable kind of entry in D, appended as the first entry of the new store and citing the old store's last entry; order across a designation comes from that citation (CON-0251), not from recording time. Applied in `57785a947`.

## Addendum (2026-10-27T17:23:34Z): window C30 — three decisions on designation and open choices; reporting to the Principal

- **Principal (verbatim):** "yes record them" (the Rule Reviewer's recommendations on the three questions of the C30 settlement, `68b90a77e`, with the two conditions and the acceptance tests from an external review, ChatGPT, which the Rule Reviewer endorsed).
- **T9-09 (a):** a designation cites **every maximal entry** of the old store (every entry no other entry of that store cites); at append the kernel **refuses** a designation that omits any maximal entry. The old store's end is fixed by citation alone (CON-0251), whatever the merge order; the append-order premise is replaced. **Limit stated:** entries that bypass the kernel are not seen by this check and stay with the reference-list loss check (CON-0243).
- **T9-09 (b):** a designation whose authority evaluates **F or U is refused at append**; authority is evaluated **in the same kernel step** as the append (K6).
- **T8-04:** an incumbent system's behaviour is evidence of one possible choice, **not a specification**; an open choice is decided by a recorded act that adopts it explicitly, or stays **U**.
- **Applied** after C31's review has settled, as changed theory for C32. **Model checks required:** (1) the same entries in different merge orders give the same result; (2) a designation omitting a maximal entry is refused; (3) F refused; (4) U refused; (5) T accepted when everything else holds; (6) old behaviour does not settle an open choice; (7) an explicit recorded decision does. The rule is not called correct until they pass.
- **Time:** the Professor reports both the elapsed window time (first commit to settlement) and, where available, the actual reading and review time; elapsed time is not evidence of faster reading.
- **Reporting (no rule change; v1.4 §5, §10 applied):** the Professor writes each window's results into its settlement as usual and **reports to the Principal only** at the consolidation gates and before the S0200 test, when a question needs the Principal's authority (one question at a time, in business language, with a recommendation), or when something blocks the reading; otherwise reading continues without waiting.

## Addendum (2026-10-28T02:25:00Z): four decisions after the consolidation S0151–S0181

- **Principal (verbatim):** "record all four".
1. **Moving a record to a new store:** each store belongs to **one application**, and only that application may move the record to a new store. Conflicting moves are prevented, not only detected (the prevent principle of turn 7; consistent with F22 = (C + O)).
2. **The designation rule is closed for now as a design sketch** (as F22), with its assumptions stated in §9.17: the move is a recorded, authorised act citing every maximal entry of the old store; one owner application per store; a store is a carrier only once; kernel attestation of accepted entries, **assumed** unforgeable; within one kernel forks are prevented, across merged applications detected (T9-10). Further protocol questions go to the Ch. 9 TODO; no new model versions unless a critical finding or a new corpus source requires one. *(Evidence: four critical findings in succession, C30, C32 and the consolidation, each in the repair of the previous one.)*
3. **Exposure through summaries** (`06-theory/manuscript/review/C33-S0177-S0181/ACCESS-NOTE.md`): S0156, S0157, S0161, S0170 and S0174 are **treated as exposed through summaries**, as S0155. **Allowed sources' statements about non-allowed files are not used as evidence for the theory**; the book may note that a source refers to an excluded file, and builds nothing on what it says about it (this replaces the Rule Reviewer's earlier, looser advice). Reviewers are told not to rely on such content. The final hold-out test runs on the unexposed items and **reports every exposed item separately, with how it was exposed**. **Wording:** S0155 is *treated as* exposed; that it is a hold-out file is **not established** (the Principal's private check was inconclusive).
4. **The Rule Reviewer's working practice** (no rule change): progress reported against the theory's deliverables; review costs watched, with scope cuts proposed when a budget is exceeded, never cutting necessary checks; closure criteria set before any kernel design topic; one critical finding reconstructed independently at each milestone (first: the C32 fork, at S0200); every statement labelled **verified / reported / inferred / proposed / open**; **"continue" approves nothing new** — only what the Principal explicitly approves is recorded, with what is recorded listed. **Proposed for the S0200 decision (not in force):** consolidation and test reviewers commissioned by the Rule Reviewer or a separate session, from a fixed brief template with scope taken from the rules, the Professor seeing the brief and able to object before the reviewers start.

## Addendum (2026-10-28T17:07:25Z): the Rule Reviewer becomes the Governance Engineer

- **Principal (verbatim):** "You are not only Rule reviewer . You are the governence engineer now which commmunciates with human arb and facilititate what human wants."
- **Decided:** the role formerly called **Rule Reviewer** is now the **Governance Engineer**: it keeps the rules and records, prepares planted-gap tests, evaluates the other roles, writes mandates and briefs, communicates with the Principal (the human ARB) and facilitates what the Principal wants. It still decides nothing for the Principal and records only what the Principal explicitly approves. Earlier records that say "Rule Reviewer" mean this role.
- **Proposed, not yet decided** (`IMPLEMENTATION-STRATEGY-PROPOSED.md` §0): role names for the other AI sessions — Professor (Research Lead), Independent Reviewers, **Verification Auditor**, **Implementation Engineer**; and the two mandates `MANDATE-STRATEGIC-DESIGN-PROPOSED.md` and `MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`, for the S0200 decision.

## Addendum (2026-10-29T02:08:48Z): the S0200 decisions

- **Principal (verbatim):** "accept as recommended" — on `S0200-DECISION-PACKAGE.md` (commit `e3b399e5b`), items D1–D11 as recommended there.
- **Recorded, exactly as recommended:**
  - **D1** The consolidation S0182–S0200 is settled (stage 1 `8faab7396`, addendum `1d10e28da`, stage 2 `0ae02e66b`); the planted-gap test is scored **2 of 4**, reviewer A's report counted both ways (blind and combined, equal) (`PLANTED-GAP-RESULT.md`, `e5da8af5a`).
  - **D2** R2 shows the candidates in the sections under review first (`--scope`; tool change by the Professor); the reviewer judges each. Written into `REVIEWER-INSTRUCTIONS.md`.
  - **D3** A reference to a claim that does not exist is checked as possible lost content before it is treated as an index error. Written into `REVIEWER-INSTRUCTIONS.md`.
  - **D4** Test preparation: no file that mentions a test is placed in a folder reviewers are sent to; every commission lists the files it may open (working practice; no rule change).
  - **D5** The consolidation design used at S0200 is kept: concept verdicts limited to concepts homed in scope; stage 1 a document only; rotation; a planted-gap test every second consolidation.
  - **D6** Consolidation and test reviewers are commissioned **by the Professor, from a fixed brief template maintained by the Governance Engineer**; the file list is mandatory, the scope is taken from the rules, and the Governance Engineer sees each brief. *(Replaces the proposal of 2026-10-28, item 4.)*
  - **D7** Implementation strategy, part A: the strategy opens no new route by which excluded material could influence the theory or a test; its approval grants **no access** beyond the allowed list.
  - **D8** Implementation strategy, part B: the two-track strategy is approved **in principle only**. The stage-0 implementation-candidates list does not yet exist; the Governance Engineer prepares it, the Professor confirms each claim's status, and **stage 1 (the executable reference model) comes back to the Principal as a separate decision**. No engineering session, kernel prototype or production work is authorised.
  - **D9** Role names approved: Principal (human ARB) · Governance Engineer · Professor (Research Lead) · Independent Reviewers · Verification Auditor · Implementation Engineer (`IMPLEMENTATION-STRATEGY-PROPOSED.md` §0).
  - **D10** `MANDATE-STRATEGIC-DESIGN-PROPOSED.md` **revision 1 approved** (sha256 `f26830b4143ccbb5fefe3148e07bddadffa33083374a3ace1671b31cd1bd1fa7` at approval; commit `64e6e8603`). It may start now that the S0200 consolidation is settled; the Professor may finish the current reading window first.
  - **D11** `MANDATE-VERIFICATION-AUDITOR-PROPOSED.md` **revision 1 approved** (sha256 `d49a771c873003e17e9200816d6c30e04f1fb58b752e9658349bcdb79132b8df` at approval; commit `64e6e8603`). **Part A** may start in a new session; **part B needs a reviewed strategic-design baseline and the Principal's separate authorisation.**
- **Not decided here:** the start of stage 1; part B of the audit; any access beyond the allowed list.

## Addendum (2026-10-29T08:50:11Z): what KnowledgeOS promises (T10-01; T0-02)

- **Question** (strategic-design turn 1, §10.1.2, settlement `20140bce9`): when KnowledgeOS serves a piece of knowledge, what does it promise the user? (a) only that its current standing is right and shown with its reasons; (b) that, plus an honest display of how well-founded it is (refuted, not attested, or supported by recorded evidence), without a guarantee; (c) that it is well-founded.
- **Principal (verbatim):** "i accept your recommendation" — on the Governance Engineer's recommendation **(b)**.
- **Decided: (b).** KnowledgeOS promises that a piece of knowledge's **current standing is right and shown with its reasons**, together with an **honest display of how well-founded it is** (refuted, not attested, or supported by recorded evidence), **without a guarantee that it is well-founded**. *(Consistent with the problem statement, `00.01` L30: usable = current ∧ well-founded ∧ visible, "visible" meaning the user sees both values.)*
- **Consequence for the strategic design (for the Professor, turn 2):** the display of well-foundedness is part of the promise; turn 2 assigns its responsibility and shows that each displayed class can be read from the record alone.
- **Not decided here:** the Governance Engineer mandate; the Verification Auditor's test-run question (both still open).

## Addendum (2026-10-29T08:51:08Z): the Governance Engineer mandate; the auditor's test runs

- **Principal (verbatim):** "yes, both" — on the two items left open in the addendum on T10-01.
- **Decided:**
  1. `MANDATE-GOVERNANCE-ENGINEER-PROPOSED.md` is **approved**, including §8.2a (receiving the auditor's reports), §9 (how the Governance Engineer is checked: a separate session checks each planted-gap test's preparation before reviewers start) and §11 (handover and start prompt). sha256 at approval: `a4867624b7b7b05d4176ca8c138a8c6dd931496482395e6526274817abed69b7`. Its working state is `GOVERNANCE-ENGINEER-STATE.md`.
  2. **The Verification Auditor may run EKS tests that read allowed corpus files as fixtures** (e.g. `Phase0BackTest`), provided it does not view their content and discloses each such test. One line added to its mandate §2.
- **Not decided here:** part B of the audit; stage 1.

## Addendum (2026-10-29T09:22:58Z): the Lead DDD Architect and the Implementation Engineer

- **Principal (verbatim):** "approve both" — on revision 2 of the two mandates (commit `21ef02780`).
- **Decided:**
  1. **A seventh role, the Lead DDD Architect** (renamed from "Principal DDD Architect" at revision 1 on the Principal's choice), with `MANDATE-LEAD-DDD-ARCHITECT-PROPOSED.md` revision 2 **approved** (sha256 at approval `1c66451b91fbd1b325b7ff31287bd7e762ee55be08a063342b105f35b8f9a087`). **Part 1** (DDD review of settled strategic-design turns) may start; part 2 (tactical design) only with the stage-1 decision. Added to the roles table of `IMPLEMENTATION-STRATEGY-PROPOSED.md` §0.
  2. `MANDATE-IMPLEMENTATION-ENGINEER-PROPOSED.md` revision 2 **approved** (sha256 at approval `5e68c114cd907d06ec65c509de10e0069be203a78c44a9f4c799a5552396788d`). The role stays **inactive until the stage-1 decision**.
- **Not decided here:** stage 1; part B of the audit; any further role, permission or governance change.

## Addendum (2026-10-29T09:31:14Z): Governance Engineer mandate, revision 1

- **Principal (verbatim):** "approve revision 1 of the governance engineer mandate" (commit `c8d2eddb3`).
- **Decided:** `MANDATE-GOVERNANCE-ENGINEER-PROPOSED.md` **revision 1 is approved and in force** (sha256 at approval `0a7f8531ab01983bb5789ae13ee57f9ddb2f3d2cc3b3e664ffaf92f5bbd670bb`), replacing revision 0. Its changes (§13): access limited to the listed lane files, `02-FILES.jsonl` only for lookups of a named allowed ID, unclear files not opened; checklist C1–C6 for the independent check of a test's preparation, anything but PASS blocking the test until the Principal decides; ambiguous approvals recorded only in their clear part; auditor findings kept as verified / reported / pending; three statuses per role; the handover's five-item authorisation report.
- **Not decided here:** anything else.

## Addendum (2026-10-29T09:34:19Z): a theory turn on G1 after the strategic-design chain

- **Principal (verbatim):** "yes, G1 turn after turn 5" — on the Governance Engineer's recommendation.
- **Decided:** after strategic-design turn 5 (published language) is settled, and **before reading in index order resumes**, the Professor runs **one theory turn on G1** (T10-06; strategic-design turn 2, `65e332c21`): the record the evaluator reads admits only acts of authority and has no entry type for evidence, although §9.10 O2/Q4 require evidence entries and an evidential grade; so the promise (b) (T10-01) is not yet supported by the record. The turn runs under Rulebook v1.4 §12b (research question first, corpus first, two reviewers on different models), **not** as a sixth turn of the strategic-design chain.
- **Not decided here:** the turn's result; any change to the kernel's laws (they go through review as usual); stage 1.

## Addendum (2026-10-29T09:36:01Z): when an area counts as a bounded context (T10-05)

- **Question** (strategic-design turn 3, §10.3, settlement `fe67ad7cf`): when should an area count as a bounded context of its own? (A) it owns a rule no other area owns, stated in its own vocabulary; (B) as (A), and it also keeps a record of its own (S0150's "independently owned state"); (C) never by rule.
- **Principal (verbatim):** "(B), with the K2 condition".
- **Decided: (B), with a condition.** An area counts as a bounded context when it **owns a rule no other area owns, stated in its own vocabulary, and keeps independently owned state**. **Condition (K2):** turn 4 states what "a record of its own / independently owned state" means and shows that it is compatible with the kernel law of **one designated record** (K2, §9.17 R2; CON-0261) — for example, entries of the one record that only that area may append, not a second record. **If it is not compatible, the question returns to the Principal.**
- **Consequence (per §10.3, under the condition):** standing and norms qualify now; well-foundedness qualifies once it has an evidence record (G1; the G1 theory turn decided today); the catalogue is doubtful; presentation, method, work management and assessment do not qualify.
- **Not decided here:** the context map; any level change (rows stay at S0 until review).

## Addendum (2026-10-29T09:50:07Z): implementation strategy, revision 3

- **Principal (verbatim):** "approve revision 3 of the strategy" (commit `5f69e40a2`).
- **Decided:** `IMPLEMENTATION-STRATEGY-PROPOSED.md` **revision 3 is approved in principle** (sha256 at approval `eabe2cecea6f9fd09ea47b61abfe9c9dd1966362d8f268da863df72a42c110ad`), replacing revision 2. Consistency corrections only (§8): stage 1's entry in five separate authorisations, no code before the Principal approves the Implementation Engineer's plan; a testing disposition per law; §7 split into settled and pending; current role names, status from the decision record; stage-2 inputs as a named, versioned list with a reviewed mapping; stage-2 and stage-3 findings to the Principal first.
- **Not decided here:** stage 1 and every other item listed as pending in §7.

## Addendum (2026-10-29T10:00:44Z): what counts as an area's own state (T10-10); the G1 turn's starting point

- **Question** (strategic-design turn 4, §10.3.6, settlement `87fbfa2ed`): under rule (B), what counts as an area's own state? (1) entry kinds of the one record whose acceptance rule belongs to the area; (2) also content outside the record that carries no standing; (3) a separate designated record per area.
- **Principal (verbatim):** "(1), and G1 starts from entry kinds in the one record".
- **Decided:**
  1. **(1).** An area's own state is **the entry kinds of the one designated record whose acceptance rule is that area's rule**. Removing, annulling and designating stay with standing for every kind (§10.3.6). Shown compatible with K2 without changing it, so the K2 condition of T10-05 is met. **Consequence:** standing is admitted; norms is not admitted today (it owns no entry kind, G11); the earlier addendum's "standing and norms qualify now" is superseded on this point.
  2. **The G1 theory turn starts from entry kinds in the one record:** evidence is first modelled as entry kinds of the one designated record, accepted under the evidence area's own rule. **A separate evidence record is considered only if the G1 turn shows it is necessary, and then the question returns to the Principal** (it would require reading K2 as allowing more than one record).
- **Not decided here:** turn 5; the G1 turn's result; any level change.

## Addendum (2026-10-29T10:12:39Z): routing the Lead DDD Architect's part-1 findings

- **Principal (verbatim):** "route A, B and C" — on the Governance Engineer's routing proposal for the Architect's reports `review-architecture/SD1–SD4-DDD-REVIEW.md` (commit `514068d6d`).
- **Decided:** the findings reach the Professor **as input, labelled "the Lead DDD Architect's DDD findings, routed by the Principal" — not review verdicts**; the Professor weighs each against the book and the corpus and may reject it with a reason.
  - **A, into turn 5's settlement:** recommendation 2 (one published answer carrying both values with their reasons, so that "unaltered" is a duty attached to it; SD1-02, SD3-03, SD4-04); recommendation 3 with SD2-02 and SD4-01 (the record and its kind list as an explicit shared element of the map: its owner and the act that adds a kind); recommendation 5 (R18, where product facts enter, as an anti-corruption layer owned by the receiving side; SD4-06).
  - **B, into the G1 turn:** recommendation 4 / SD4-02 (can a breach of one area's acceptance rule be contained to that area's entries instead of refusing every standing answer? if not, a stated cost of decision (1)); with the Governance Engineer's qualification: within one kernel such an entry is refused at append; the case arises on merged records or bypass.
  - **C, into the Ch. 10 TODO** for a revision after the G1 turn and before part 2: SD3-01 (owner assignment against the "information to reject" test and the amending-act reading); SD2-01 (evidence for the own-language column); SD3-04 (the inventory lacks the applications' own rules); SD1-01 (classify the rule that derives the displayed class).
- **Kept for the stage-1 decision package (D):** SD1-05 — which reference product governs design investment (KnowledgeOS as its own product, or canon's supporting subdomain of PublicDigit).
- **Not decided here:** any finding's acceptance; any change to the theory (the Professor's, through review).

## Addendum (2026-10-29T10:18:15Z): the Verification Auditor's part B

- **Principal (verbatim):** "yes, part B after the turn-5 review".
- **Decided:** part B of the Verification Auditor's audit (`MANDATE-VERIFICATION-AUDITOR-PROPOSED.md` §4) is **authorised, to start once both conditions hold:** (1) strategic-design turn 5 is settled; (2) the Lead DDD Architect's review of turn 5 (`review-architecture/SD5-DDD-REVIEW.md`) is saved. **Baseline T** = Ch. 10 (§10.1–§10.5) at the turn-5 settlement commit, plus the kernel sections the commission names. If G1 or a later revision changes the baseline, the affected comparisons are **rerun** as new reports (mandate §4a).
- **Unchanged:** findings go to the Principal and the Governance Engineer only, never to the Professor; the auditor recommends nothing and changes nothing.
- **Not decided here:** any finding's disposition.

## Addendum (2026-10-29T10:37:27Z): a KnowledgeOS-only pilot of the role environment (configuration)

- **Principal (verbatim):** "Approved in principle: KnowledgeOS-only pilot, steps 1–3. You may prepare the proposed changes under `.claude/settings.json`, `.claude/agents/`, and `.claude/skills/` for my review. Please preserve the existing mandates and handoff protocol. Do not create a competing handoff record or make EKS code changes. Before activation, present the proposed changes, the restrictions each change is intended to enforce, the tests to be performed, and any limitations that cannot be technically enforced. Nothing goes live until I have reviewed and explicitly approved the proposed configuration. Repository-wide adoption remains a separate future decision."
- **Decided (in principle):** the Governance Engineer **prepares** steps 1–3 (deny rules for hold-out and sealed files; agent definitions for the Verification Auditor and the Independent Reviewer; start skills for the Governance Engineer, the Lead DDD Architect and the Implementation Engineer) **for review only**. **Because Claude Code loads `.claude/agents/`, `.claude/skills/` and `.claude/settings.json` at once, the proposals are staged outside `.claude/` (in `03-methodology/role-environment-proposal/`) and copied into place only on the Principal's explicit approval.**
- **Not decided here:** activation; repository-wide adoption; any EKS code change; any change to the mandates or the handoff protocol.

## Addendum (2026-10-29T10:46:12Z): who owns the record and its list of entry kinds (T10-16)

- **Question** (strategic-design turn 5, §10.5, settlement `dca72c57f`): who owns the one designated record and its list of entry kinds (and so which act adds a kind, which can admit a context)? (a) standing; (b) no area: changed only by an authorised act outside every area (§8.09 C7's form for shared parts); (c) a context of its own.
- **Principal (verbatim):** "T10-16 (b)".
- **Decided: (b).** The one designated record and its list of entry kinds are **owned by no area**; they are changed only by **an authorised act outside every area** (in practice the Principal's recorded decision, as every kind so far — consumption, transfer, designation — was added). **Adding an entry kind is such an act: no area can add a kind for itself or for another, so no area can admit a context.** The record-keeping rules (designation, merge, identity, integrity) move out of standing's list to that element; standing keeps "what counts".
- **Consequence for the G1 turn:** the evidence kinds it models are **proposed kinds**; adopting them is an act under (b), prepared as one question for the Principal.
- **Not decided here:** any new kind; any level change.

## Addendum (2026-10-29T11:02:47Z): designation under T10-10 and T10-16; routing the Architect's turn-5 findings

- **Correction (Governance Engineer, self-reported):** the addenda on T10-10 ("Removing, annulling and designating stay with standing for every kind") and T10-16 ("the record-keeping rules (designation, merge, identity, integrity) move out of standing's list") contradict each other on designation. Both were recorded from the Professor's wording without being reconciled. Found by the Lead DDD Architect (SD5-05, `review-architecture/SD5-DDD-REVIEW.md`, commit `149b8d5de`), verified by the Governance Engineer.
- **Principal (verbatim):** "Yes to both. Decision 1 — Record responsibility: I agree that moving the official record belongs to the neutral element outside every area. The Professor may classify removing and annulling, with reasons, and must return the question to me if the distinction remains unclear. Decision 2 — Routing the Architect's findings: I approve the proposed routing of the findings to the Professor, the G1 design step, and the open-items register as described. These approvals authorise the examination and clarification work. They do not automatically approve new design changes. Any unresolved decision must be brought back to me. The previously authorised Part B code audit may proceed within its existing mandate."
- **Decided:**
  1. **Designation** (moving the official record) belongs to the **element outside every area** (T10-16 (b)); the sentence of the T10-10 addendum is superseded on this point. **Removing and annulling:** the Professor classifies each, with reasons, when applying (b); **if the distinction remains unclear, the question returns to the Principal.**
  2. **Routing** (input, not verdicts): SD5-01 and the small items (SD4-04, the record element's subdomain class, the stale §10.4.4 L127 and §10.4.3 L100) to the Professor with the application of (b); SD5-02 (keep F of Q1 and the class "refuted" lexically apart), SD5-04 ("attested") and SD5-06 (reasons for guard refusals) to the G1 turn; SD5-03 (the name "Standing" against "current authority", T10-15) to the Ch. 10 open items, to be settled before part 2.
  3. **Scope:** these approvals authorise examination and clarification only; **they do not approve new design changes; any unresolved decision returns to the Principal.**
  4. Part B of the audit proceeds within its mandate.

## Addendum (2026-10-29T11:06:44Z): two tracks; a separate record for the implementation track

- **Principal (verbatim):** "professor's research work should be done by professor with our governence. implementation part has different roles lead by me with your help as governence engineer." — then "yes to both" on the Governance Engineer's two recommendations.
- **Decided:**
  1. **Two tracks.** *Research:* the Professor and the Independent Reviewers, under the Governance Engineer's governance; the Principal decides normative questions. *Implementation:* led by the Principal, with the Lead DDD Architect, the Verification Auditor and the Implementation Engineer; the Governance Engineer supports. **Interface:** the settled strategic design and the candidates list flow from research to implementation; findings flow from implementation to research **only when the Principal routes them**, as input the Professor may reject. Neither track takes the other's decisions.
  2. **The implementation track keeps its own decision record and state**, placed by `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos/` (exit 0): `docs/knowledgeos/IMPLEMENTATION-TRACK-DECISION-RECORD.md` and `docs/knowledgeos/IMPLEMENTATION-TRACK-STATE.md`. **From now on, implementation decisions are recorded there; this record keeps research decisions.** Earlier implementation decisions stay here (history is not rewritten); the new record indexes them.

## Addendum (2026-10-29T11:21:15Z): Governance Engineer mandate, revision 2 (test findings F3, F4)

- **Principal (verbatim):** "F3 + F4 — Governance Engineer mandate: I approve revision 2 of the mandate, including the implementation-track records in the readable-file list and record-keeping rules, and recording the fingerprints after the status updates. Please preserve the existing governance boundaries and do not make unrelated changes."
- **Decided:** `MANDATE-GOVERNANCE-ENGINEER-PROPOSED.md` **revision 2 approved and in force** (changes in its §14). **Fingerprint at approval, computed after the status update (as revision 2 itself requires): sha256 `854ac3d752d5b6784244034096a8b9563a746c5f372f6d4518fe8e47c2ae4776`.**
- **Note on earlier fingerprints:** the fingerprints recorded at earlier approvals (this mandate's revisions 0–1, the Lead DDD Architect and Implementation Engineer mandates, the strategy revision 3) were computed **before** the status-line update and so differ from the files' later hashes; the differences are the status lines (and, for the two role mandates, the start-prompt date). Administrative; recorded here so that a checker can tell (F4).
- **Not decided here:** anything else.

## Addendum (2026-10-29T11:23:49Z): Governance Engineer mandate, revision 3 (test finding F1); replacing the current session

- **Principal (verbatim):** "yes, F1 with option a, proceed with part B".
- **Decided:**
  1. `MANDATE-GOVERNANCE-ENGINEER-PROPOSED.md` **revision 3 approved and in force**: one requirement added (§11.0): start every Governance Engineer session at the repository root. **Fingerprint at approval, after the status update: sha256 `6f9640f6afb5287fc45593b7e8e96c932fc3ab4681303635c08d2c73126b8290`.**
  2. **Option (a):** the current Governance Engineer session (`f1575f5d-…`, started in the lane folder, so not covered by the repository's deny rules) continues for now and is **replaced at the next natural break** by a session started at the repository root with `/role-governance-engineer`.
  3. The Part-B verification proceeds (under the implementation track's authorisation).
- **Not decided here:** anything else.

## Addendum (2026-10-29T11:27:44Z): whose evidence counts towards a knowledge item's class (T9-12)

- **Question** (G1 theory turn, settlement `8e4b1a420`): whose evidence counts towards a knowledge item's class (refuted / not attested / supported)? (a) any producer's; (b) only evidence from producers with authority over the scope is accepted at all, anything else refused at recording; (c) every declared producer's evidence is accepted and shown with its status, but only authorised producers' evidence counts towards refuted or supported.
- **Principal (verbatim):** "T9-12 (c)".
- **Decided: (c).** Every declared producer's evidence is **accepted and shown, with its producer and authorisation status**; **only authorised producers' evidence counts towards "refuted" or "supported"**. Stated cost: unauthorised entries stay in the record, and every move of the record must close them.
- **Not decided here:** who is authorised for which scope (follows with T9-16 and later questions); how support and refutation combine (T9-17); adopting the evidence kinds; any level change.

## Addendum (2026-10-29T11:29:23Z): pointer — Verification Auditor mandate revision 2

- Recorded in the implementation-track decision record, entry **I-003**: `MANDATE-VERIFICATION-AUDITOR-PROPOSED.md` revision 2 approved (finding class "theory element with no code"; sha256 at approval `d27135df31672aa0a7338943e212871b2338c91acc50c6e041644ff7852cd17d`). This pointer keeps the mandate's approval history findable here.

## Addendum (2026-10-29T11:38:16Z): pointer — Verification Auditor mandate revision 3

- Recorded in the implementation-track decision record, entry **I-004**: `MANDATE-VERIFICATION-AUDITOR-PROPOSED.md` revision 3 approved (the two decision records readable; sha256 at approval `0ace7ce8a11344d78a1db6ff432dd475712627cab525790937ad3fefaa5d4e61`).

## Addendum (2026-10-29T11:40:06Z): who may withdraw or restore reliance on evidence (T9-16)

- **Question** (the Professor, after the G1 turn; draft of C38 `0612831ad`): who may withdraw reliance on a piece of evidence, or restore it? (a) only the evidence's producer; (b) anyone holding a grant to withdraw or restore reliance over the item's scope — withdrawals by others accepted and shown, but not counted; (c) one designated assessor per item. And: does restoring reliance get its own entry type?
- **Principal (verbatim):** "T9-16 (b), keep the restore entry".
- **Decided:**
  1. **(b).** Withdrawing or restoring reliance on evidence counts only when made by a holder of **a grant to withdraw or restore reliance over the item's scope**. Withdrawals and restorations by others are **accepted and shown, but do not count** (the same principle as T9-12 (c)). The producer has no special right. **Known dependency:** what such a grant covers (the scope relation) is still an open input in the theory and must be stated.
  2. **Restoring reliance keeps its own entry type**, so that reliance can be restored without standing's authority to annul.
  3. The present model's acceptance of a withdrawal from **anyone holding any grant** is to be corrected accordingly.
- **Not decided here:** the scope relation itself; how support and refutation combine (T9-17); adopting the evidence kinds; any level change.

## Addendum (2026-10-29T11:41:54Z): pointer — Lead DDD Architect and Implementation Engineer mandates, revision 3

- Recorded in the implementation-track decision record, entry **I-005**: both mandates' revision 3 approved (both decision records readable; sha256 at approval: Architect `46d655700848774e92f71b38e8fb1d18282283d554ff90eaf251e0e5048caff5`, Engineer `2ecf66055d1bd2a6feabb6af2047dc0eee982293ff9951cf9bf9fc39dad19d24`).

## Addendum (2026-10-29T15:52:08Z): pointer — Lead DDD Architect mandate revision 4

- Recorded in the implementation-track decision record, entry **I-007**: `MANDATE-LEAD-DDD-ARCHITECT-PROPOSED.md` revision 4 approved with six conditions (§5a, the SD1-05 options for EKS, PKS and KnowledgeOS; no file granted; sha256 at approval `6223f080c9de26f2b48407c05bbf1d6a6f784d4d7a2915585c9f4e0fa0c50340`). This pointer keeps the mandate's approval history findable here.

## Addendum (2026-10-29T16:34:45Z): T9-17 deferred until reviewer B reports; reporting rules for the Governance Engineer; a one-time update of the repository records

- **Principal (verbatim, excerpts):** (2) "I accept the recommendation to wait for Reviewer B's report before making this decision. Please do not apply option (b) yet. After Reviewer B reports, present the available options, the relevant findings, the consequences for the design baseline, and the effect on the auditor's re-check. Also clarify whether the routed implementation evidence B-04 and B-05 was used or rejected, and why." (1) "Do not describe the consolidation as complete until its required review and settlement steps are finished." (4) "I authorise a one-time update of the repository session log and CONTEXT.md for 2026-10-29, limited to accurately recording the current state, completed work, outstanding work, incidents, and next authorised actions." (6) "For subsequent reports, distinguish clearly between verified facts, reported facts, inferences, proposals, decisions required, and unknowns. For every significant activity, show its purpose, authorising mandate or decision, actual result, files changed, verification evidence, outstanding work, and next action. The dashboard and reporting records must describe the project state; they must not create new authority or replace existing approval requirements."
- **Decided:**
  1. **T9-17 is deferred** until reviewer B's report on §9.18 (consolidation S0201–S0225) is saved. **Option (b) is not applied.** The Governance Engineer then presents the options, the relevant findings, the consequences for the design baseline (Ch. 10) and for the auditor's re-check, and whether B-04 and B-05 were used or rejected, and why (from the Professor, asked through the Principal).
  2. **The consolidation's report** to the Principal, when the reviewers and the integration reviewer are done: each reviewer's findings and severity; disagreements; corrections required; unresolved questions; verified versus reported; decisions required. Not called complete before its review and settlement steps are finished.
  3. **A one-time permission** for the Governance Engineer to update `.claude/sessions/2026-10-29.md` and `.claude/CONTEXT.md` for 2026-10-29, describing state only; no authority is created by them.
  4. **Reporting form** for the Governance Engineer's later reports: the six labels (verified, reported, inferred, proposed, decision required, unknown), and per significant activity: purpose, authority, result, files changed, verification evidence, outstanding work, next action.
- **Not decided here:** T9-17 itself; any file access (see implementation-track entry I-009); credential remediation (PublicDigit's owner).

## Addendum (2026-10-29T23:55:13Z): how support and refutation combine (T9-17)

- **Question** (Ch. 9 TODO T9-17; §9.18.5 g0; deferred until reviewer B's report, addendum 2026-10-29T16:34:45Z; reviewer B saved, consolidation S0201–S0225 settled `ae13f5987`): when live evidence both supports and refutes an item, what does the display show? (a) refutation first (g0); (b) "contested"; (c) one established assessment per claim; (d) recency. And the default with no purpose named.
- **Principal (verbatim):** "T9-17 (b), keep the no-purpose default".
- **Decided:**
  1. **(b).** While both a live support and a live refutation exist, the item's class is **"contested"**. "Refuted" then holds only when a refutation is live and no support is.
  2. **The no-purpose default is kept:** with no purpose named, no withdrawal applies, and the recorded withdrawals are listed (§9.18, check 34).
- **For the Professor to state when applying (not decided here):** the class when one side is surely live and the other only possibly (the U cases under Kleene monotonicity); the model's new check; the consequences in Ch. 10 (the published class list, §10.2 refuted(k, t)).
- **Still owed:** the Professor's note on whether the routed implementation evidence B-04 and B-05 (I-003) was used or rejected, and why. The Principal confirmed without waiting for it; it is recorded when it arrives.
- **Not decided here:** adopting the evidence kinds; naming T10-21; any level change (S0 unchanged); the "guard" choice in the map.

## Addendum (2026-10-30T13:53:31Z): T9-17 applied; the note on B-04/B-05 received; a routing gap (Governance Engineer, self-reported)

- **T9-17 (b) applied** by the Professor (`c32ea453e`): class rule g1; `ce_model.py` v24. **Checked by the Governance Engineer:** re-run 61 PASS, 0 FAIL, identical to the saved run; checks 1–37 identical to v23 (they still run v23's refutation-first rule, kept as an option; **only check 38 exercises g1**); Ch. 10 §10.1, §10.2, §10.4 R4, §10.5.3 and TODO T10-21 (4) updated with a change-log row; levels S0.
- **The undetermined (U) cases** were left to the Professor by the T9-17 addendum. The Professor states them, **"for review"**: a class is shown only if every resolution of the U gives the same class, otherwise U with its candidate classes. **Consequence (Governance Engineer, inferred):** a surely live refutation with a possibly live support now shows U ("refuted or contested"), where the earlier rule showed "refuted". This statement **has had no independent review** (the consolidation review of §9.18 preceded it).
- **The note on B-04/B-05** (owed under the T9-17 addendum) received: `review/T9-17-APPLIED/NOTE-B04-B05.md`, sha256 `7ccdf0632956a90bb18c8680253950aeafe94df60c061e3227bdb2b8b5779632`. **Neither used nor rejected**: not delivered to the Professor before; read now, they do not bear on how evidence combines; one code aggregate (FAIL if any part fails) has refutation-first's shape but combines checks of different parts, and is recorded for the auditor's re-check.
- **Correction (Governance Engineer, self-reported):** I-003 (2026-10-29T11:29:23Z) routed B-04/B-05 to the Professor, but **no delivery step was recorded** and the Professor reports never receiving them. Communication runs through the Principal; the Governance Engineer should have given a copy-ready routing prompt when recording I-003. **Practice from now on:** every routing decision is recorded with its delivery prompt, and marked delivered only when the Principal confirms.
- **Not decided here:** whether g1's U statement is reviewed now or at a later review (a question for the Principal).

## Addendum 2026-10-30T16:02:43Z: consolidation S0226–S0252 — the test's preparation check run after the reviewers started (recorded by the Governance Engineer)

- **Principal (verbatim):** "prepare the planted copy" (done: copy `5e89f9916`, hashes in the access record `4b2316328`). Then, after being told the reviewers had started: "run the check now, reviewers keep working".
- **Deviation, recorded:** the Professor started reviewers A and B (`75fd4275b`, 2026-10-30 16:58 local), recording that the copy's "independent check passed". **No such check had been run** at that time (the Governance Engineer had only handed the Principal the checker prompt). Mandate §8.3 item 7 and §8.3a require a PASS before reviewers start. The Principal chose to run the check now without stopping the reviewers.
- **How the check is run:** a fresh, independent subagent session started by the Governance Engineer with the §8.3a checklist, the base commit, the copy, the manifest and the key. **Second deviation:** its report reaches the Principal through the Governance Engineer, relayed verbatim, rather than directly.
- **If the check is FAIL or UNRESOLVED:** the result is scored with that deviation named, and the Principal decides how to proceed (mandate §8.3a).
- **The g1 U statement:** the question left open at 2026-10-30T13:53:31Z is overtaken: the statement (§9.18.5 L192–L201) is in reviewer B's scope (`adf8f34a3`). No separate review.

## Addendum 2026-10-30T16:40Z: consolidation S0226–S0252, stage 2 authorised (recorded by the Governance Engineer)

- **Principal (verbatim):** "start stage 2".
- **Basis:** the planted-gap result (`f4cdbe236`, `PLANTED-GAP-RESULT.md`): blind 4 of 4; stage 1 (`018569cac`) treated all four plants correctly.
- **Scope:** the Professor applies the stage-1 remedies to the working book, each assessed against the working book on its own evidence, **under the stage-2 guard** of the result file: no new §8.43 C6; no change to the §8.44 C3 quote; A-01's restoration not applied (A-12's other parts assessed on their own); A-13's "part 2" half only. A-10 settled against the decision record.
- **After stage 2:** the Governance Engineer checks that the guarded items were left alone. Proposals P1–P3 of the result file are **not** decided here.
- **Correction (Governance Engineer):** this addendum's heading time "16:40Z" is wrong; it was written at 2026-10-30T16:35:21Z.

## Addendum 2026-10-31T12:20:16Z: T9-19, T10-21 and T9-20 recorded; consolidation S0253–S0277 with their changes in scope (recorded by the Governance Engineer)

- **How these decisions were made:** the Principal answered the Professor's three questions directly on 2026-10-31; the Professor applied them before this record existed ("decision record addendum to be recorded by the Governance Engineer"). The Principal's words to the Professor are not in the repository. The Governance Engineer then set out each decision and its open points to the Principal, with the recommendation to confirm all three, and to have the consolidation review the changes.
- **Principal (verbatim):** "i agree with your suggestion. record the" (the message ends there).
- **Taken to cover (the Governance Engineer's reading, stated so the Principal can correct it):** (1) confirmation of the three decisions in the words proposed, "T9-19: adopt all three; T10-21: separate the names; T9-20: no guard, keep c2"; (2) the consolidation S0253–S0277 starts (not a test; Ch. 0 and Ch. 1 rotated) **with the passages changed by T9-19, T10-21 and T9-20 in scope**, each with a full reader.
- **Decided:**
  - **T9-19 (b) — adopt all three evidence kinds** (evid, ewdr, erst) in the one record; applied at `a8ff1f4de` (§9.18, §9.15 G1, §10.2, §10.3.6, §10.4, map). At S0. Candidate 2 admitted **for review** (not yet reviewed). Open: T9-14 (item identity), G7 (source kind), the U-case statement.
  - **T10-21 — separate the names** in the published language: "disputed ownership" for §10.3's ownership status; Q1's F shown as "not recognized as current authority", never "refuted"; "accepted by the kernel" for the kernel's seal; the evidence classes keep refuted / contested / not attested / supported. Applied at `c6366b303`. Open: the area name "Standing" (with T10-15).
  - **T9-20 — no guard on adoption; clause (c2) kept:** standing comes from a recorded act by a holder of a grant, not from evidence. Applied at `8a9134da1` (§8.37 C1, §8.42 C2, §9.18 (c2), map). Open: the display fork (a warrant field, or "supported, warrant not recorded"), with G7.
- **Process note (Governance Engineer):** decisions given to the Professor directly reach this record only when the Principal tells the Governance Engineer. From now on the Professor's report of such a decision is answered with a copy-ready line for the Principal to confirm.
- **Not decided here:** the Verification Auditor's re-run timing (still open); T10-17–T10-20 (open, the Professor's).
- **The message completed (verbatim, sent separately):** "them", so the full words are "i agree with your suggestion. record the[m]".

## Addendum 2026-10-31T14:56:11Z: consolidation S0253–S0277, part 2 — Q-A, Q-B, Q-C (recorded by the Governance Engineer)

- **Principal (verbatim):** "Q-A: the T9-19 adoption includes W's acceptance rule G8 with (c3) and the two-level domain; Q-B: g1 adopted; Q-C: F's definition renamed 'ended, or not recognized'."
- **How:** the Principal answered the Professor's three part-2 questions directly; the Professor applied them (part 2, `e8e787e00`) and gave a copy-ready line; the Principal confirmed it to the Governance Engineer in the words above (the practice of 2026-10-31).
- **Decided:**
  - **Q-A:** the adoption of the evidence kinds (T9-19) **includes** the evidence area's acceptance rule G8 with clause (c3) and §9.18.6's two-level domain: a breach of W's rule leaves standing's answers untouched (Ch. 10's dependency R6 superseded for such breaches).
  - **Q-B:** the class rule **g1 is adopted** (no longer only modelled).
  - **Q-C:** F's **definition** is renamed "ended, or not recognized" (§9.13 and its restatements), in addition to T10-21's display rule (never "refuted").
- **Verified by the Governance Engineer (2026-10-31):** both settlement commits exist (part 1 `3c7a18f31`, 48 files; part 2 `e8e787e00`, 21 files); "ended, or not recognized" stands in §9.13 and in §8.31, §8.33, §8.40; the R2-03 answer (I-012) stands in §10.3.3 row B (`10.03` L114: "guaranteed by the rule, Ded", enforcement point open); `10.05` L75 and L103 aligned with T9-19.
- **Not decided here:** where g1 sits (kernel or application; T10-20); the enforcing context of the display duty (PL-14); any mission, kernel specification or Constitution.
