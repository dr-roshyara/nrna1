# Rulebook v2 (PROPOSED): KnowledgeOS theory research

| Field | Value |
|---|---|
| Status | **PROPOSED, NOT IN FORCE.** Written by the Rule Reviewer, 2026-10-19, on the Principal's instruction "Proceed with Rulebook v2 as a proposal. Do not replace or modify the currently authoritative rulebook yet." |
| Authoritative until the Principal decides | `RULEBOOK-v1.3.md` and `REVIEWER-INSTRUCTIONS.md`. **The Professor works under v1.3.** Nothing in this file applies yet |
| Companion | `RULEBOOK-v2-PROPOSAL-REPORT.md`: inventory, mapping table (every v1.3 obligation → its place here), regression check, efficiency estimate, trial, open questions |
| If approved | replaces `RULEBOOK-v1.3.md` as the operating rules, forward only. v1.3, its amendments, trials and the register stay as history. `REVIEWER-INSTRUCTIONS.md` stays the single home of how a review is done |

*Each requirement appears once. Where a rule has a script that enforces it, the script is named. Rationale and history are in the report, not here. Markers: **[new]** = not in v1.3 · **[changed]** = in v1.3 in another form.*

---

## 1. Purpose and authority

1. **The work is the theory** of KnowledgeOS, built from the corpus and measured against `06-theory/RESEARCH-PURPOSE.md`. The wording need not match a source; **the meaning must**. The book attributes to a source what it says, no more and no less.
2. **What matters for each result:** is the concept precisely defined? Does the derivation follow? Are the assumptions explicit? Can an independent reviewer reproduce or challenge it? Is the decision and its authority recorded?
3. **Authorship** neither makes a result right nor wrong. It is recorded only where it affects provenance, accountability, independence or the reading of evidence.
4. **The Principal decides** the rules and every normative question. A recommendation is not an authorization. Rules apply forward only; past work is not corrected to later rules.

## 2. Access

1. **Corpus:** only the rows of `CORPUS-ALLOWED.tsv` (D-C1). Sealed lists, the sealed hold-out files and every file not on the list: never. Every read goes through `tmp/tools/close_source.py` (allowed-list check, fingerprint, depth, read log).
2. **Other evidence**, under §4:
   - outside works and the Principal's brainstorm folder (classed external, 2026-10-19): as now;
   - **repository records outside the allowed list: only within a scope the Principal authorizes [new; open question Q1]**. Until then, none.
3. **No corpus text** goes to any outside service or search.
4. **Reviewer access:** before a reviewer starts, one entry in `00-access/ACCESS-RECORD.md` lists the grant and the files of step 1 and step 2. **[changed: the closing line moves to the settlement, §8.5]**

## 3. Reading

1. **Index order is the coverage line.** Every allowed source is eventually read in index order, or, if it was read earlier out of order, checked against the book when index order reaches it. Only `full` and `skim` rows move the reading position (enforced by `close_source.py`).
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

**How strong is the claim (epistemic status)?** The existing labels stay, so no book text needs relabelling:
- **Src:** the corpus states it (provenance corpus, status "stated");
- **Ext:** an outside work states it;
- **Repo** [new]: a repository record shows it;
- **Ded:** it follows from cited premises;
- **Int:** an interpretation of cited evidence;
- **Hyp:** a conjecture, not yet supported.

A Ded, Int or Hyp names its premises, so its provenance is visible. **No claim is stronger than its evidence.**

### 4.2 Rules for each kind of evidence

- **Corpus first.** "The corpus says X" rests only on corpus anchors. Repository, external and testimony evidence may **corroborate, challenge, sharpen or fill a gap**, but **never replace** what the corpus says, and never turn a Src claim into something the source does not say.
- **Repository evidence** [new] shows what is *implemented*. The corpus often states *intent*. Where they differ, record both: a divergence between intent and implementation is a finding (Int or a fork), not a correction of the source.
- **External concepts:** one role each: *tool* (to analyse), *benchmark* (to compare), *gap-filler* (the theory needs it, the corpus lacks it). Mapping: the same idea under another name → Int; absent from the corpus → Hyp until supported; contradicting the corpus → a fork, which the outside concept does not win automatically. Validated is the *fit*: a credible source, the assumptions under which it applies, and one corpus case where it applies and one where it does not (or why there is none). Registered with origin `external`, PROVISIONAL until the reviewer's verdict. Cited under "External references".
- **Principal testimony** is recorded apart from the corpus and never counted as a corpus source.
- **Computed results** [new]: a result produced by a script (a model search, a count, a similarity) is a Ded only when the section cites the script and its output file, and the reviewer receives both. A model states and checks that its objects are distinct (no two names for one function) and states the pool and granularity its result depends on.

### 4.3 Claim strength

- **Scope before recording:** every derived result states its scope and assumptions ("shown for this case", "holds under …"). A source's proposal is not written as an established definition.
- **Generalisation:** a claim beyond its source's case ("always", "cannot", "the only", a law) is Hyp until a second, independent source or a proof supports it. The case it rests on is named. Sources with a shared origin count as one line of support.
- **Rates:** no rate, interval or percentage without a stated random sample; for a purposive sample, the count only.
- **Strength scan** before every review (`tmp/tools/strength_scan.py`): each strong word in new text ("always", "never", "only", "all", "none", "necessary", "sufficient", "impossible", "proves") is justified or weakened.
- **Formal models** are tested on their trivial cases (empty mechanism set, a mechanism that refuses everything, the unchanged state) before they go into the book. A model that a trivial case satisfies lacks "must-do" requirements.
- **Quotes** are in quotation marks immediately before a full anchor `"…" [S0031 L49–52]`; checked by `close_source.py` and the quote checkers. A paraphrase has no quotation marks.

## 5. The cycle for a full source

1. **Validate** each concept by reasoning: does it make sense; does it survive a real counterexample? Use computation and machine learning where they decide something (similarity for synonyms and word senses, dependency graphs, counterexample search); state their limits and verify every finding in the source text.
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
   2. integration (required): the argument rewritten claim by claim, **each claim keeping its number, meaning, assumptions, evidential limits and status**; evidence that supports or challenges a claim worked into that claim; uncertainty kept explicit where integration would need an unsupported inference; no new claims. The old text is kept in the review folder. `integration_check.py` checks the formatting only; the reviewer checks meaning claim by claim. Length is a diagnostic, not a limit.
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

The Professor sends, after the reviewer's blind inventory is saved:
1. **before:** the relevant earlier sections; **new:** what was added or changed; the whole theory map;
2. **changed theory of the previous settlement** (window, theory turn or consolidation), so no correction stays unreviewed;
3. **book search:** existing homes and the top-five hits read;
4. **dependencies** of each new or changed section, each checked for: definitions preserved · no established result contradicted or silently changed · no necessary concept missing; unverified dependencies marked;
5. **strength scan** result;
6. **undefined-term check** [new]: identifiers and corpus-specific terms in the new text with no definition in the book or glossary (script; candidate list, read by the reviewer);
7. for a two-step rewrite: old and new text, claim by claim;
8. for computed results: the script and its output.

### 8.3 Planted-gap tests (consolidation and whole-book reviews)

- Plants are made by the Principal or the Rule Reviewer, never by the Professor, in copies of written sections; no traces in the text.
- The answer key is sealed (sha256 in the access record) before the review.
- **Isolation** [new; lesson of the S0036–S0065 test]: the commission states that the copies are the versions under review; the reviewers do not open the original files of those sections, do not compare files, and the book search runs on the copies (`book_search.py` needs an exclude option: open item). A reviewer that reaches an original says so, and its omission score is reported as not blind.
- Result as a count ("found k of n"), never a rate. Every plant is repaired in the book afterwards.

### 8.4 Integration and outline

- **Whole-book cross-chapter review:** one reviewer reads the chapter reports and `BOOK-OUTLINE.md` and checks: one term with different meanings across chapters; a law resting on a concept another chapter rejects; the derivation chain from primitives to laws; one law with two homes.
- **`dependency_graph.py`** runs at every settlement and before every consolidation or whole-book review. When a concept becomes INVALID or changes in substance, it lists every downstream item transitively. **No automatic invalidation:** the Professor reassesses each item in the settlement; the next reviewer checks that. Its limit (explicit references only) is stated in each settlement.

### 8.5 Settlement (one file per review, `SETTLEMENT.md`)

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
2. **Trigger.** Raised by a reviewer or found by the Professor. It is written at the top of the settlement (or, if found while reading, as one line in the theory map's open items) with its evidence and its downstream list from `dependency_graph.py`.
3. **Immediate investigation.** The next turn is an investigation of that problem, out of any sequence: a theory turn under §9.3, reviewed by two reviewers. Dependent work stops until it is settled; unrelated reading may continue, building nothing on the affected items.
4. **The reading position is not lost:** the investigation's reads are `topic-full` or `branch-consult`, which never move the position (enforced by `close_source.py`). Reading resumes at the next source in index order.
5. **Closing:** the settlement states the outcome (holds · restated · INVALID), reassesses every downstream item, and the next window brief lists the changes (§8.2.2).

## 11. End of each turn

1. **Report, in three separate parts, never merged into one figure** [new]:
   - **source coverage:** sources read in index order (full / skim) out of the allowed total; topic-full and branch-consult reads; the reading position;
   - **concept coverage:** register counts by status; open forks; open TODOs and SEQ gaps; this window's blind-inventory items and their dispositions (§7);
   - **theory maturity**, per RESEARCH-PURPOSE deliverable (primitives, laws, kernel/application boundary, forks, kernel type and existence), each as: *not built · sketch · PROVISIONAL · VALIDATED (by review)*, from `BOOK-OUTLINE.md`.

   Plus what the theory gained (definitions, results, counterexamples, forks), and whether a theory question is ready (§9.5).
2. **Closing checklist:** each part of the instruction marked done, or not done with the reason.
3. **Commit** after each settlement: only the Professor's own paths; `git diff --cached --name-status` before, `git show --name-status HEAD` after; the commit ID in the report.
4. **Session log:** one line per review, pointing to its settlement and commit; it does not repeat the settlement. **CONTEXT** only when direction or state changes. [changed]

## 12. Pointers (not rules)

- How a review is done: `REVIEWER-INSTRUCTIONS.md`.
- Branch mechanics and enforcement: `AMENDMENT-32a-hybrid-research-method.md` (incl. §32b).
- Decisions and their history: `METHOD-VERSIONS.md`, `PRINCIPAL-DECISION-RECORD-RULES-20261018.md`, `00-access/ACCESS-RECORD.md`.
- Tools: `tmp/tools/` (close_source, book_search, branch_search, strength_scan, dependency_graph, integration_check, form_rewrite, split_chapter, quote checkers, kernel_model).
