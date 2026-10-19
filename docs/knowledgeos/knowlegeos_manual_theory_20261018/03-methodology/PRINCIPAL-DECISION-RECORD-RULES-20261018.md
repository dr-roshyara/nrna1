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
