# Rulebook v2 (PROPOSED): KnowledgeOS theory research

| Field | Value |
|---|---|
| Status | **PROPOSED, NOT IN FORCE** (revision 1). Written by the Rule Reviewer, 2026-10-19, on the Principal's instruction "Proceed with Rulebook v2 as a proposal. Do not replace or modify the currently authoritative rulebook yet." |
| Authoritative until the Principal decides | `RULEBOOK-v1.3.md` and `REVIEWER-INSTRUCTIONS.md`. **The Professor works under v1.3.** Nothing in this file applies yet |
| Companion | `RULEBOOK-v2-PROPOSAL-REPORT.md`: inventory, mapping table (every v1.3 obligation → its place here), regression check, efficiency estimate, trial, open questions |
| If approved | replaces `RULEBOOK-v1.3.md` as the operating rules, forward only. v1.3, its amendments, trials and the register stay as history. `REVIEWER-INSTRUCTIONS.md` stays the single home of how a review is done |

*Each requirement appears once. Where a rule has a script that enforces it, the script is named. Rationale and history are in the report, not here. Markers: **[new]** = not in v1.3 · **[changed]** = in v1.3 in another form. Revision 1 (2026-10-19) adds the Principal's answer to Q1 and the refinements accepted from the architecture review (report §13); revision 2 the trial criteria (report §14) and §0, theory first (report §15).*

**Every rule serves at least one of four purposes** [new]: **discover** the theory without narrowing it early · establish **validity** · expose **uncertainty** (counterexamples, open choices) · **communicate** the theory faithfully. A rule that serves none is challenged; the report lists the purposes per section.

**Terms** [new; one meaning each]:

| Term | Meaning |
|---|---|
| **reading window** | the next sources in index order plus any added sources (§3.3), read, written and reviewed together |
| **settlement** | the work after a review: every finding gets an outcome, dependencies are reassessed, corrections are made (§8.5). **A settlement is not a theory turn** (v1.3 §6.5 called it a "theory-only turn") |
| **theory turn** | an investigation of one named theoretical question (§9) |
| **critical investigation** | a theory turn opened at once by a critical foundational problem (§10) |
| **consolidation** / **whole-book review** | a review of accumulated work over a stated scope (§8.1) |
| **blind** | the reviewer writes and hands back its own inventory of the sources **before** it sees any of the Professor's work (book, map, register, brief). Before that it may have only the sources, `RESEARCH-PURPOSE.md` and its instructions |

---

## 0. Theory first (the governing strategy) [new; Principal, 2026-10-19: "make sure that we strongly apply research oriented theory first strategy"]

**This is a research programme, not a document pipeline.** Every other section of this rulebook serves the theory; none of them is an end in itself.

1. **The method of theory building**, applied to every material concept, law and fork:
   **question** (what must the theory explain?) → **evidence** (what do the sources say, read whole and in order?) → **definition** (what is inside the concept and what is not?) → **assumptions** (stated) → **derivation** (each step justified) → **falsification attempt** (§5.1) → **status** (only by independent review).
2. **Three kinds of progress, kept apart** (§11.1): reading more sources is corpus progress; rewriting a chapter is book progress; **only a better definition, an exposed assumption, a resolved contradiction, a tested boundary, or a derivation strengthened or refuted is theory progress.** A turn that produced none says so.
3. **Corpus first, theory open:** chronological reading exists so that unexpected concepts, contradictions and rival explanations can appear. Focused research supplements it and never narrows the theory early: an open fork stays open until evidence or derivation closes it.
4. **The derivation chain is explicit:** primitives → definitions → assumptions → propositions → laws. Every law and proposition names, in its "rests on" line, the steps it comes from, and whether each rests on corpus evidence, an added assumption or an unresolved hypothesis.
5. **Priority when time or attention is short**, in this order:
   1. a critical foundational problem (§10);
   2. the validity of new and changed theory (falsification, review, settlement);
   3. coverage of the corpus in index order;
   4. book form (rewrites, outline, layout).

   A lower priority never displaces a higher one. **A lower priority may wait; it is never dropped.** Corpus coverage in particular is non-negotiable: an interruption for a critical problem is recorded, and index-order reading resumes at the next unread source without skipping any. **A procedure is never satisfied at the theory's expense:** a needed correction is not left out to keep a rewrite "pure", a finding is not dropped to keep a settlement short, and a source is not skimmed to keep a window on time.
6. **When a rule obstructs a necessary theory step,** the Professor does not bend the theory and does not silently bypass the rule: it records the conflict in the settlement and raises it with the Principal, who decides.
7. **Polished prose, agreement between sessions, a completed checklist or a commit establish nothing about validity.** Evidence, derivation and independent challenge do.

## 1. Purpose and authority

1. **The work is the theory** of KnowledgeOS, built from the corpus and measured against `06-theory/RESEARCH-PURPOSE.md`. The wording need not match a source; **the meaning must**. The book attributes to a source what it says, no more and no less.
2. **What matters for each result:** is the concept precisely defined? Does the derivation follow? Are the assumptions explicit? Can an independent reviewer reproduce or challenge it? Is the decision and its authority recorded?
3. **Authorship** neither makes a result right nor wrong. It is recorded only where it affects provenance, accountability, independence or the reading of evidence.
4. **The Principal decides** the rules and every normative question. A recommendation is not an authorization. Rules apply forward only; past work is not corrected to later rules.

## 2. Access

1. **Corpus:** only the rows of `CORPUS-ALLOWED.tsv` (D-C1). Sealed lists, the sealed hold-out files and every file not on the list: never. Every read goes through `tmp/tools/close_source.py` (allowed-list check, fingerprint, depth, read log).
2. **Other evidence**, under §4:
   - outside works and the Principal's brainstorm folder (classed external, 2026-10-19): as now;
   - **repository records** [new; Principal, 2026-10-19, Q1: "yes", scope "implementation only", applies with v2 only]: **code and configuration** in `app/`, `config/`, `routes/`, `database/migrations/`, `tests/`, `scripts/`. **Excluded:** all of `docs/knowledgeos/` outside the allowed list; sealed and hold-out material; `.env` and any secret; `*.sql` backups; `*.csv` and other data files; logs; `storage/`, `vendor/`, `node_modules/`. Every read is logged with path, commit and sha256. **Not in force while v1.3 applies.**
3. **No corpus text** goes to any outside service or search.
4. **Reviewer access:** before a reviewer starts, one entry in `00-access/ACCESS-RECORD.md` lists the grant and the files of step 1 and step 2. **[changed: the closing line moves to the settlement, §8.5]**

## 3. Reading

1. **Index order is the coverage line.** Every allowed source is read when index order reaches it. Only `full` and `skim` rows move the reading position (enforced by `close_source.py`).
   - **A source read earlier out of order** (`topic-full`, `branch-consult`) [changed]: when index order reaches it, it joins that window like any other source and **the reviewer reads it blind again**. Earlier evidence may be reused, but the window's disposition is made against **the source**, not only against the book, since the book may carry an earlier misreading. Branch predictions about it are scored. A discrepancy that cannot be resolved stays open.
2. **Every source is opened and scanned whole.** The scan, not the index entry, decides the depth:
   - **full:** it carries theory; the whole cycle of §5 applies;
   - **skim:** it repeats earlier material or is purely operational; one line on whether it adds anything. The reviewer reads one skimmed source of the window blind.
3. **A reading window** is the next five sources in index order, plus at most three added sources. Ten in index order only for a coherent series of short documents, with at most two added and the review split into two parallel commissions. **[changed: one statement replaces v1.3 §7 "up to five per turn" and §12a]**
4. **An added source** joins only if a logged search (`branch_search.py --log`, terms from the register and the book) found it; one line says which concept or question it serves; it is read whole as `topic-full`; it goes into the reviewer's blind step. Where several candidates are found, take the earliest and the latest relevant one, not only the one that fits.
5. A run of sources that add nothing new is **information**, not a reason to stop or skip.

## 4. Evidence and claims [changed: provenance and status separated]

### 4.1 Two separate questions for every claim

**Where does the evidence come from (provenance)?**

| Provenance | What it is | How it is cited |
|---|---|---|
| **corpus** | an allowed source | `[S0031 L49]` |
| **repository** [new] | code, configuration or a record in the repository outside the allowed list, read within an authorized scope | `[REPO path@commit Lnn]` |
| **external** | an outside work or a Principal brainstorm (`EXT-nnn`) | `[EXT-013 §2]`, listed in `06-theory/EXTERNAL-SOURCES.md` |
| **Principal testimony** | a statement by the Principal about the corpus | `[PT-001]`, in `06-theory/PRINCIPAL-TESTIMONY.md` |
| **own reasoning** | the Professor's derivation, interpretation or conjecture | the premises it rests on (§, CON-ID, anchors) |

**Four separate questions, never inferred from one another** [changed]:

| Question | Values | Where it is recorded |
|---|---|---|
| **1. reasoning type:** what kind of claim? | **Src** stated by a source · **Ded** follows from cited premises · **Int** interpretation of cited evidence · **Hyp** conjecture | the label in the text |
| **2. provenance:** where does the evidence come from? | the table above | the anchor; **Ext** and **Repo** are written as labels in the text where an outside work or a repository record states it (as Ext is now) |
| **3. review status:** has it survived review? | VALIDATED · PROVISIONAL · INVALID | `CONCEPT-REGISTER.tsv` (not in the prose) |
| **4. decision authority:** who may settle it? | evidence (factual) · definition (definitional) · Principal or ARB (normative), with its state: open · decided (where) | the fork in the theory map; reserved questions |

**One does not imply another.** A Ded can be INVALID (a wrong step). A Hyp can be well supported and still not a Ded. A reviewer can validate a derivation under its assumptions without the authority to settle a normative question, and without showing that the assumptions hold. No existing book text needs relabelling. **No claim is stronger than its evidence.**

### 4.2 Rules for each kind of evidence

- **Corpus first.** "The corpus says X" rests only on corpus anchors. Repository, external and testimony evidence may **corroborate, challenge, sharpen or fill a gap**, but **never replace** what the corpus says, and never turn a Src claim into something the source does not say.
- **Repository evidence** [new] shows what is *implemented*. The corpus often states *intent*. Where they differ, record both: a divergence between intent and implementation is a finding (Int or a fork), not a correction of the source.
- **External concepts:** one role each: *tool* (to analyse), *benchmark* (to compare), *gap-filler* (the theory needs it, the corpus lacks it). Mapping: the same idea under another name → Int; absent from the corpus → Hyp until supported; contradicting the corpus → a fork, which the outside concept does not win automatically. Validated is the *fit*: a credible source, the assumptions under which it applies, and one corpus case where it applies and one where it does not (or why there is none). Registered with origin `external`, PROVISIONAL until the reviewer's verdict. Cited under "External references".
- **Principal testimony** is recorded apart from the corpus and never counted as a corpus source.
- **Computed results** [new]: a result produced by a script (a model search, a count, a similarity) is a Ded only when the section cites the script and its output file, and the reviewer receives both. A model states and checks that its objects are distinct (no two names for one function) and states the pool and granularity its result depends on.

### 4.3 Claim strength

- **Scope before recording:** every derived result states its scope and assumptions ("shown for this case", "holds under …"). A source's proposal is not written as an established definition.
- **Generalisation** [changed: v1.3 let a second source turn a claim into a Ded]: a claim beyond its source's case ("always", "cannot", "the only", a law) is written as one of four kinds, and its acceptance test is whether **the strength and scope of the conclusion are justified by its evidence and reasoning**:
  1. **restricted:** stated for the case examined ("shown for S0044's RD-6");
  2. **empirical generalisation:** supported by independent observations; it stays Hyp or Int, and its support is stated as a count of independent lines. More examples strengthen it; they never make it a Ded or a universal law;
  3. **derivation:** a Ded from stated premises, valid without a second source; its generality is that of its premises;
  4. **conjecture:** Hyp, unproven.

  Sources with a shared origin count as one line of support.
- **Rates:** no rate, interval or percentage without a stated random sample; for a purposive sample, the count only.
- **Strength scan** before every review (`tmp/tools/strength_scan.py`): each strong word in new text ("always", "never", "only", "all", "none", "necessary", "sufficient", "impossible", "proves") is justified or weakened.
- **Formal models** are tested on their trivial cases (empty mechanism set, a mechanism that refuses everything, the unchanged state) before they go into the book. A model that a trivial case satisfies lacks "must-do" requirements.
- **Quotes** are in quotation marks immediately before a full anchor `"…" [S0031 L49–52]`; checked by `close_source.py` and the quote checkers. A paraphrase has no quotation marks.

## 5. The cycle for a full source

1. **Validate** each concept by reasoning: does it make sense; does it survive a real counterexample?
   - **Falsification before review** [new]: for each new concept, law or settled fork, the Professor attempts, and records in the brief, **(a)** a counterexample within its stated scope, **(b)** its boundary: the case where it should stop holding, and **(c)** at least one **rival explanation** of the same evidence, with the reason the book prefers its own reading or keeps both open. **A rival explanation must be materially different** (another mechanism, another premise, or another reading of the source), never a weak alternative set up to be rejected. "None found" is a valid answer, with the attempt shown.
   - **A definition is testable:** it states what falls outside the concept and where it stops applying, not only a description of what is inside.
   - The Professor proposes and challenges; **it never gives its own work a review status** (§7). Use computation and machine learning where they decide something (similarity for synonyms and word senses, dependency graphs, counterexample search); state their limits and verify every finding in the source text.
2. **Find the existing home** before writing: search the book and the register by script (`book_search.py`, lexical and `--meaning`), **read the top five hits** and mark each *same / extends / different*. Extend an existing home rather than writing a second; cite it.
3. **Write** into that home or a new section (§6).
4. **Record concepts** in `06-theory/CONCEPT-REGISTER.tsv` (§7).
5. **Mark the source read** with `close_source.py` (quotes and concept tags checked).
6. **Update** `06-theory/THEORY-MAP.md`: concepts, forks, open questions.

## 6. Writing the book

1. **Section order:** plain language → definitions → claims and derivations → open questions → **What does not hold** (refuted claims, each with its ID and reason) → references (corpus, then "External references").
2. **Layout:** every chapter is a folder of section files `NN.MM-<name>.md` with `NN.00-index.md` and a `TODO.md`; `NN.99-change-log.md` is added when a section is first revised. Revision history lives only in the change log; no revision tags or correction notes in the theory text.
3. **Rewriting a section of Ch. 0–2** has two steps, one section per window, in the order §1.12 → §1.13 → §1.14:
   1. scaffolding (`form_rewrite.py`): plain language, numbered claims index, open questions, What does not hold, references;
   2. integration (required): the argument rewritten claim by claim, **each claim keeping its number, meaning, assumptions, evidential limits and status**; evidence that supports or challenges a claim worked into that claim; uncertainty kept explicit where integration would need an unsupported inference. The old text is kept in the review folder.
   - **Three kinds of change** [changed: replaces "no new claims"]:
     - *editorial:* rephrasing or restructuring without changing meaning: allowed;
     - *evidence integration:* evidence that supports, qualifies or challenges the claim, with its status and limits kept: allowed;
     - *substantive:* a new proposition, definition, assumption or derivation, or a changed status. **Allowed only as a marked theory change** ("new in this rewrite", with label, provenance and dependencies), listed in the brief as new theory and reviewed as such. A needed correction is never left out to keep the rewrite "pure". `integration_check.py` checks the formatting only; the reviewer checks meaning claim by claim. Length is a diagnostic, not a limit.
4. **Book outline:** `BOOK-OUTLINE.md` arranges sections by the RESEARCH-PURPOSE deliverables and shows what is missing; new sections are placed in it when written. `CHAPTER-PLAN.md` stays in force until the Principal decides on `TARGET-TOC.md` (§9.4).
5. **Defined before used** [changed: made checkable]: a term, identifier or code (for example "lane", "BC-7", "I-4") is defined in the book, or in the glossary, before or where it is first used. The undefined-term check (§8.2) lists candidates.

## 7. Concepts, forks and open items: every one gets a disposition [changed]

**No new register.** Each material item has exactly one home:

| Item | Home | Disposition values |
|---|---|---|
| concept | `CONCEPT-REGISTER.tsv` (CON-NNNN) | VALIDATED · PROVISIONAL · INVALID. **Only an independent reviewer's verdict makes VALIDATED.** Several concepts for one thing: each its own ID, linked by `same_subject_as` |
| contradiction between lines | `THEORY-MAP.md`, fork F-nn | factual (decided by logic, then a deciding observation, then explanatory reach) · definitional (both meanings kept, each its own ID) · normative (Principal or ARB). Open until decided |
| dependency | the section's "rests on" line; `dependency_graph.py` | established · **unverified** (rests on a concept without an independent VALIDATED verdict) |
| open question, incomplete definition, sequence gap | chapter `TODO.md` (sequence gaps as SEQ-NNNN) | open · resolved (where) |

- Nothing is resolved by an unsupported assumption (D-C1 condition 5).
- **Nothing is built on an unreviewed concept** as if established; it is cited as unverified.
- **Concepts from a branch or external origin** stay PROVISIONAL until their review is recorded; a directly evidenced defect may be INVALID at once (enforced by `close_source.py` for branch origin).
- **Coverage disposition at each settlement:** every item of the reviewer's blind inventory that is not COVERED receives one disposition in the settlement table: *added (§…)* · *fork F-nn* · *TODO (chapter)* · *excluded (reason)* · *deferred to (turn)*. No item is left without one. This is the concept-coverage record of §10.

## 8. Review

**How a review is done** (blind inventory first and handed back, completeness, validity tests, verdicts, report, severity) **lives in `REVIEWER-INSTRUCTIONS.md` only.** This section says when reviews happen and what the Professor supplies. **[changed: v1.3 §6's six questions and assessment list are moved there; nothing in them is dropped, see report §3]**

### 8.1 When

| Review | When | Reviewers | Mode |
|---|---|---|---|
| **window review** | after every reading window | one (a 10-source window: split into two parallel commissions) | transition |
| **theory-turn review** | after every theory turn (§9) | two, on different models, independent | topic |
| **consolidation** | every 25 sources read in index order | two, on different models, independent; planted-gap test | consolidation |
| **whole-book review** | at S0100, then every 100 in index order (replaces the consolidation then) | two per part, chapter by chapter; planted-gap test; then one cross-chapter integration review | consolidation, all chapters |

Only sources read in index order count towards 25 and 100.

### 8.2 The brief (one file per review, `BRIEF.md`) [changed: STEP8-AND-SCAN renamed; content unchanged plus one check]

**Sequence** [new, stated once]: (1) the reviewer receives the sources (and may consult `RESEARCH-PURPOSE.md`); (2) it writes its own inventory of their theory, omissions and counterexamples included; (3) it hands that inventory back, and it is saved and frozen; (4) only then does it receive the brief below and the Professor's work; (5) disagreements are settled on evidence.

The Professor sends, after the reviewer's blind inventory is saved:
1. **before:** the relevant earlier sections; **new:** what was added or changed; the whole theory map;
2. **changed theory of the previous settlement** (window, theory turn or consolidation), so no correction stays unreviewed;
3. **book search:** existing homes and the top-five hits read;
4. **dependencies** of each new or changed section, each checked for: definitions preserved · no established result contradicted or silently changed · no necessary concept missing; unverified dependencies marked. **This check finds implicit, meaning-level dependencies; the graph (§8.4) finds declared ones and their transitive reach. Neither replaces the other, and neither is repeated twice;**
5. **strength scan** result, and the **falsification attempts** of §5.1 (counterexample, boundary, rival explanation);
6. **undefined-term check** [new]: identifiers and corpus-specific terms in the new text with no definition in the book or glossary (script; candidate list, read by the reviewer);
7. for a two-step rewrite: old and new text, claim by claim;
8. for computed results: the script and its output.

### 8.3 Planted-gap tests (consolidation and whole-book reviews)

- Plants are made by the Principal or the Rule Reviewer, never by the Professor, in copies of written sections; no traces in the text.
- The answer key is sealed (sha256 in the access record) before the review.
- **Isolation** [new; lesson of the S0036–S0065 test]: the commission states that the copies are the versions under review; the reviewers do not open the original files of those sections, do not compare files, and the book search runs on the copies (`book_search.py` needs an exclude option: open item). A reviewer that reaches an original says so, and its omission score is reported as not blind. **Evidence, not only a declaration:** the report lists every file the reviewer opened and every search it ran; the Rule Reviewer checks that list against the originals before scoring.
- **Bounded conclusion:** a planted-gap result shows whether the process caught these defects under these conditions. It is never reported as general reliability.
- Result as a count ("found k of n"), never a rate. Every plant is repaired in the book afterwards.

### 8.4 Integration and outline

- **Whole-book cross-chapter review:** one reviewer reads the chapter reports and `BOOK-OUTLINE.md` and checks: one term with different meanings across chapters; a law resting on a concept another chapter rejects; the derivation chain from primitives to laws; one law with two homes.
- **`dependency_graph.py`** runs at every settlement and before every consolidation or whole-book review. When a concept becomes INVALID or changes in substance, it lists every downstream item transitively. **No automatic invalidation:** the Professor reassesses each item in the settlement; the next reviewer checks that. Its limit (explicit references only) is stated in each settlement.

### 8.5 Settlement (one file per review, `SETTLEMENT.md`)

**A window (or turn) is settled** [new: the acceptance contract] only when all of these are recorded:
1. every source has a disposition (full / skim with its line / re-read of an earlier topic source, §3.1);
2. every finding has an outcome (accepted and done · rejected with evidence · open);
3. every blind-inventory item not COVERED has a disposition (§7);
4. every listed dependency has an assessment, or is marked **blocked** with its reason;
5. the checks have run (quotes, strength scan, dependency graph, integration check where it applies);
6. the next reading position is stated.

An item may stay open, but only with **the role that owns it, the reason, and the next action**. "Settled" means the process is complete. **It never means the theory is valid:** that is the review status of each concept. A commit shows the work is saved, not that it is correct.

The settlement is **the record of the review**. It contains:
- the findings and what was done with each (accepted · rejected with evidence), and, for two reviewers, which were found by both and which by one only;
- the **coverage dispositions** (§7);
- the **running measure**: findings, critical findings, material omissions, defects found later in earlier-passed work; reviewer harness time; **Professor harness time [new]**; skims in the window [new];
- the dependency-graph list and its reassessment;
- **the independence line** [moved from the access record's closing line]: inventory saved before comparison; number of commissions and step-2 messages; whether a reviewer reached material it should not have;
- the planted-gap score, where there was a test.

A finding stands unless disproved with evidence. **Engineering supplies evidence; the Professor never accepts its own work as validated.**

## 9. Theory turns [changed: question-driven, no quota]

1. **A theory turn is opened when a question is ready**, not after a fixed number of windows. A question is ready when:
   - it is a "missing" entry in `BOOK-OUTLINE.md` or an open fork or question in the theory map; and
   - the book already holds enough evidence to attempt it, or a logged search shows the sources to read.

   The Professor names the question and its completion condition before starting.
2. **What a turn delivers:** one RESEARCH-PURPOSE deliverable step, for example a primitive (a definition and an independence model: one in which it alone fails while the others hold; if none exists, it is derivable), a step of the derivation chain to the laws, or a boundary test of one capability as three separate analyses (necessary? must the kernel provide it? can an application provide it without breaking kernel invariants?).
3. **How:** up to 8 sources found by a logged search, read as `topic-full` (the reading position does not move); sources already read on the topic are included; a fork stays open in the first draft; two reviewers (§8.1). Passages may be consulted instead of whole sources as `branch-consult`, which requires a theory-map snapshot (`close_source.py --open-branch`) and records predictions before index order reaches those sources (Amendment 32a/32b mechanics, unchanged).
4. **Questions reserved for the Principal** (for example F22) are prepared, not decided.
5. **No silence:** each window's end report states whether a theory question is ready and, if one has been ready for two windows without a turn, why. **This is a report line, not a quota.**
6. **With each whole-book review,** the Professor also writes `TARGET-TOC.md`: a table of contents organised by the RESEARCH-PURPOSE deliverables, every existing section mapped onto it, missing chapters named, the renumbering cost from the dependency graph. A proposal only; the Principal decides.

## 10. Critical foundational problems [new]

1. **Definition (narrow).** A problem is *critical foundational* only if, on evidence, it would **invalidate or materially change** one of:
   - a primitive or an axiom;
   - a concept with an independent VALIDATED verdict that the dependency graph shows **at least one other section depends on**;
   - a law, or a step of the derivation chain to the laws;
   - a conclusion of a chapter.

   **Not critical:** a local error with no dependants; a wording or form issue; a source defect the theory does not rely on; a missing concept that changes no existing result (that is high: an omission). The reviewer's severity "critical" (`REVIEWER-INSTRUCTIONS.md`) uses the same definition.

   **The one severity scale** [changed: all three levels here, one rationale line per finding]: **critical** as above · **high:** a material defect, omission, contradiction, wrong status or unsupported generalisation that weakens an important claim, with contained impact; resolved before the topic is finalised · **normal:** a local defect or clarification that does not undermine the main derivation; to the next consolidation. A critical finding triggers investigation and reassessment of each dependant, never automatic invalidation.
2. **Trigger.** Raised by a reviewer or found by the Professor. It is written at the top of the settlement (or, if found while reading, as one line in the theory map's open items) with its evidence and its downstream list from `dependency_graph.py`.
3. **Immediate investigation.** The next turn is an investigation of that problem, out of any sequence: a theory turn under §9.3, reviewed by two reviewers. Dependent work stops until it is settled; unrelated reading may continue, building nothing on the affected items.
4. **The reading position is not lost:** the investigation's reads are `topic-full` or `branch-consult`, which never move the position (enforced by `close_source.py`). Reading resumes at the next source in index order.
5. **Closing:** the settlement states the outcome (holds · restated · INVALID), reassesses every downstream item, and the next window brief lists the changes (§8.2.2).

## 11. End of each turn

1. **Report, in three separate parts, never merged into one figure** [new]. They influence one another but do not substitute for one another: reading many sources does not settle a foundational question, and a rewrite can make the book clearer without improving the theory.
   - **corpus progress:** sources read in index order (full / skim) out of the allowed total; topic-full and branch-consult reads; earlier topic sources re-read in order; the reading position;
   - **theory progress:**
     - concept coverage: register counts by status; open forks; open TODOs and SEQ gaps; this window's blind-inventory items and their dispositions (§7);
     - maturity, per RESEARCH-PURPOSE deliverable (primitives, laws, kernel/application boundary, forks, kernel type and existence): *not built · sketch · PROVISIONAL · VALIDATED (by review)*, from `BOOK-OUTLINE.md`;
     - what changed: definitions, results, counterexamples, assumptions exposed, forks opened or settled;
   - **book progress:** sections rewritten (two-step), sections still in the old form, outline gaps.

   Plus whether a theory question is ready (§9.5).
2. **Closing checklist:** each part of the instruction marked done, or not done with the reason.
3. **Commit** after each settlement: only the Professor's own paths; `git diff --cached --name-status` before, `git show --name-status HEAD` after; the commit ID in the report.
4. **Session log:** one line per review, pointing to its settlement and commit; it does not repeat the settlement. **CONTEXT** only when direction or state changes. [changed]

## 12. Pointers (not rules)

- How a review is done: `REVIEWER-INSTRUCTIONS.md`.
- Branch mechanics and enforcement: `AMENDMENT-32a-hybrid-research-method.md` (incl. §32b).
- Decisions and their history: `METHOD-VERSIONS.md`, `PRINCIPAL-DECISION-RECORD-RULES-20261018.md`, `00-access/ACCESS-RECORD.md`.
- Tools: `tmp/tools/` (close_source, book_search, branch_search, strength_scan, dependency_graph, integration_check, form_rewrite, split_chapter, quote checkers, kernel_model).
