# Rulebook v1.3: KnowledgeOS theory research

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-19 ("update the rules", with a review recommending items 1–7 and the adjustments to items 1 and 6) |
| Replaces | `RULEBOOK-v1.1.md` (with its v1.2 amendment of §5) as the operating rules; v1.1 is kept as history |
| Applies | from S0031 onward; no retroactive change |

## 1. Purpose

> **What matters for the theory** (Principal, 2026-10-19):
> 1. Is the concept precisely defined?
> 2. Does the mathematical derivation follow logically?
> 3. Are the assumptions explicit?
> 4. Can an independent reviewer reproduce or challenge the result?
> 5. Is the decision and its responsible authority recorded?
>
> **AI authorship does not automatically make a result wrong, and human authorship does not automatically make it correct.** What matters is the evidence, the reasoning, the verification process, and the authority behind the decision. **Record authorship only when it affects provenance, accountability, independence, or the interpretation of evidence**; do not spend time reconstructing who typed every sentence.

Build the theory of KnowledgeOS from the corpus. **The work is the theory.** What matters is whether it makes sense and is valid. The wording need not match the source word for word, but **the meaning must**: what the book attributes to a source is what the source says, no more and no less. *(v1.3 item 2)*

## 2. The core loop, per source

1. **Triage, then read** (§3). The source is read in **index order**, with the theory map (`06-theory/THEORY-MAP.md`) in mind. Index order is the coverage line; topic turns for open forks run beside it (§12).
2. **Validate** each concept or idea by reasoning. Does it make sense? Does it survive a real counterexample?
   - **Computation and machine learning** are used where they decide something: for example, similarity to find one concept under several names, or a dependency graph of the register. Their limits are stated, and every finding is verified in the source text. *(Principal, 2026-10-19)*
3. **Is it already in the book?** If yes, update that section. If no, write it in a new section or subsection.
   - **Book search before writing** (Principal, 2026-10-19: "yes, add those two lines"). Before writing a new section or claim, search the book and the concept register for its key terms and their synonyms, by script (for example `tmp/tools/book_search.py`). List the existing homes: sections and CON-IDs. Record them in the window's step-8 file as "existing homes: …". Extend an existing home rather than writing a second one, and cite it.
   - **Read the hits:** read the top 5 hits of the search (the lexical and the meaning search) before writing, and note for each in the step-8 file: *same* / *extends* / *different*. A hit that is not read does not count as checked.
4. **References** go at the end of the file: source, S-ID, lines.
5. **Mark it read** with `tmp/tools/close_source.py`, which:
   - checks the allowed list;
   - records the fingerprint and the **depth** (full or skim);
   - **checks every quote the book attributes to the source** (§4);
   - checks the concept tags;
   - writes the read log.
6. **If a concept might not be valid,** say so in the text and add it to that chapter's `TODO.md`.
7. **Update the theory map.**
8. **Dependent-chapters check, before every review** (Principal, 2026-10-19: "yes i approve"). For each new or changed section, list the earlier chapters, concepts and derived results it relies on. Then check each one:
   - **definitions:** are its definitions and distinctions preserved?
   - **results:** does the section contradict or silently change an established result?
   - **omissions:** is a necessary concept or relationship from it missing?

   **A dependency on a concept without an independent VALIDATED verdict is marked "unverified"** and not treated as established. Check only the relevant dependencies; do not reread the whole book. Fix what the check finds before commissioning the review, and give the reviewer the list of dependencies.
9. **The previous settlement's changes are reviewed too** (Principal, 2026-10-19). Every review brief lists, as changed theory, the text changed in the previous settlement (window, theory turn or consolidation), so no correction stays unreviewed until the next consolidation. *(Evidence: the T3 settlement introduced 3 defects that no reviewer saw afterwards.)*

## 3. Reading depth (v1.3 item 6)

- **Index order is kept**, so that sources are not chosen by the questions already held.
- **Every source is opened and scanned in full.** Triage is never done from the index entry alone.
- **The index's own summary and "repeats/extends" note may only suggest a depth.** The Professor's scan of the source decides it:
  - **full:** the source carries theory, so the whole loop of §2 applies;
  - **skim:** the source repeats earlier material or is purely operational. It is marked read with depth "skim" and one line on whether it adds anything new.
- **Safeguard:** at each five-source review, the reviewer reads one skimmed source blind, chosen by the reviewer, to check that skimming is not losing theory.
- **New concepts per source are tracked.** A run of sources that add nothing is **information** (the corpus is repeating itself there). It is **not** a reason to stop or to skip the rest of that family.

## 4. Writing

- **Section order:** plain language → definitions → claims and derivations → open questions → **what does not hold** → references.
- **Generalisation rule** (Principal, 2026-10-19): a claim that goes beyond its source's case (from one case to a class, a law, "always", "cannot", "the only") is written as **Hyp** until a second, independent source or a proof supports it. Only then may it be written as Ded or as a law. State the case it rests on ("shown for S0044's RD-6 only").
  - **No rate or interval without a random sample:** before any rate, interval or "x % of …", state how the sample was drawn. For a purposive or self-selected sample, give only the count, with no rate and no generalisation.
- **What does not hold** (Principal, 2026-10-19): refuted claims (INVALID) move out of the main text into the section's closing subsection "What does not hold", each with its ID and the reason. The main text carries only what holds or is open. Applies to new sections now, and to older sections when they are rewritten.
- **Every chapter is a folder of section files** (`NN.MM-<short-name>.md`) with an index (`NN.00-index.md`). Chapters 0–2, until now single files, are split once by script along their `##` headings. Section numbers are unchanged, and the joined result must be byte-for-byte identical to the original.
- **Rewriting a section of Ch. 0–2 has two steps** (Principal, 2026-10-19), one section per reading window:
  1. **Scaffolding** (`tmp/tools/form_rewrite.py`), kept as the basis for step 2: plain language, a numbered claims index, open questions, "What does not hold", references.
  2. **Integration** (required; step 1 alone is not a rewrite):
     - rewrite the actual argument, claim by claim, along the claims index; **every claim keeps its number**;
     - appended evidence goes into the argument of the claim it **supports or challenges**;
     - **each claim keeps its meaning, assumptions, evidential limits and status.** No provisional or invalid claim is silently turned into a validated one;
     - revision tags and revision history go only into the chapter's `NN.99-change-log.md`; inline revision tags and INVALID markers are removed from the book prose (refuted claims stay in "What does not hold");
     - where evidence cannot be integrated without resolving an open question or making an unsupported inference, **the uncertainty is kept explicit**;
     - no new claims.
  - **The reviewer checks both:**
    1. **meaning preservation:** each numbered claim stays faithful to its evidence and its previous status;
    2. **book form:** the argument is actually rewritten, the evidence is integrated, revision history is separated, and no inline revision tags or INVALID markers remain.
  - **Scripts check the formatting** (revision tags and INVALID markers in the text: 0). They do not replace the claim-by-claim reasoning review.
  - **Length is a diagnostic, not a limit:** the goal is clarity and less repetition, not shorter text at the expense of completeness.
  - **Order:** the sections that received step 1 only get step 2 first, **starting with §1.12**, then §1.13, then §1.14, one per window, from the next eligible window.
- **Book outline:** `06-theory/manuscript/BOOK-OUTLINE.md` arranges the sections by the deliverables of `RESEARCH-PURPOSE.md`: primitives, laws, the kernel/application boundary, the forks, the kernel's type and existence. It shows what is missing. It is updated at every consolidation review, and new sections are placed in it when they are written.
- **Labels:** each claim is labelled as the source's statement (**Src**), a derivation (**Ded**), an interpretation (**Int**) or a hypothesis (**Hyp**). No claim is stronger than its evidence. No revision numbers or correction notes appear in the theory text.
- **Claim-scoping before recording** (Principal, 2026-10-19T10:01:35Z): before each derived result is written, its **scope and assumptions** are stated: "holds under these assumptions", "shown for this case", "sufficient for the present maturity level". A proposal or interpretation from a source is not written as an established definition. This self-check does not replace the independent review.
- **Quotes** are written in quotation marks **immediately before a full anchor**: `"…" [S0031 L49]` or `[S0031 L49–52]`. The script checks them; a paraphrase carries no quotation marks. *(v1.3 item 1)*
- **Concepts from outside the corpus** (Principal, 2026-10-19: "yes it can make an additional list of files naming external sources"):
  - **Label Ext:** an outside work says it. It is never written as something the corpus says (Src).
  - **One role each:**
    - *tool:* a method used to analyse corpus concepts;
    - *benchmark:* an established concept the corpus concept is compared with;
    - *gap-filler:* a concept the corpus lacks but the theory needs.
  - **Mapped to the corpus:**
    - the same idea under another name → Int, with the outside concept as reference;
    - absent from the corpus → Hyp, until a derivation or corpus evidence supports it;
    - contradicting the corpus → a fork. The outside concept does not win automatically.
  - **Fit, not the outside theory, is validated:**
    - a credible source (a standard text or peer-reviewed work);
    - the assumptions under which it applies here, stated;
    - at least one corpus case where it applies, and one where it does not, or a reason why there is none.
  - **Registered** with origin `external`, PROVISIONAL until the reviewer's verdict on the fit.
  - **Listed** in `06-theory/EXTERNAL-SOURCES.md`: one row per outside work, with an ID (`EXT-nnn`), the full citation, its type, its role, and where the book uses it.
  - **Cited at the end of the section** under "External references", separate from the corpus references.
  - **Boundary:** outside concepts may analyse, sharpen or criticise the corpus's theory, but never replace what the corpus says.
  - **No corpus text** goes into an outside search.

- **Formal models are tested on their trivial cases** before they go into the book: the empty mechanism set, a mechanism that refuses everything, and the unchanged state. Use a script where possible. If a trivial case satisfies the model, it lacks "must-do" requirements beside its "must-never" constraints.

## 5. Parallel lines (forks)

- Name both lines and find the premise where they part.
- Classify the fork:
  - **factual:** decided by evidence, testing first logic, then a deciding observation, then explanatory reach;
  - **definitional:** keep both meanings, each under its own concept ID;
  - **normative:** decided by the Principal or the ARB.
- If nothing decides, keep both lines open. Open forks are listed in the theory map.

## 6. Review, after every five sources (the transition)

> **From 2026-10-19 the reviewer works under `03-methodology/REVIEWER-INSTRUCTIONS.md`** (Principal: "if you agree with the prompt instructions then follow it"). It replaces how the steps below are carried out: a ranked blind inventory, handed back before any comparison; the completeness check; the validity tests; verdicts only from the reviewer; a report of about two pages. Where the text below differs, the instructions apply.

1. The reviewer reads the five new sources **blind**, plus **one skimmed source** of its choice (§3). That reading is frozen.
2. The Professor sends a **transition brief** in two parts:
   - **before:** the relevant earlier sections;
   - **new:** what was added or changed.

   The reviewer also receives the **whole theory map**. *(v1.3 item 4)*
3. The reviewer answers six questions about the new part only:
   1. Does it match what came before? Is it in order?
   2. Is it valid mathematically?
   3. Is it valid statistically?
   4. Is it valid computationally?
   5. Does the concept need to be explained more clearly?
   6. **Coverage: "What relevant theory, concept, distinction, assumption, or finding present in the reviewed sources is missing from the Professor's work?"** The reviewer answers from its **own independent examination** of the sources (its blind reading), not by repeating omissions the Professor already knows. *(Wording per the Principal, 2026-10-19T10:01:35Z; question first added as register 31b.)*

   For questions 2–4, **"not applicable, with a reason"** is a valid answer. *(v1.3 item 5)*

   **What the review continues to assess** (Principal, 2026-10-19T10:01:35Z). These are mapped onto the questions above, not added as new ones:
   - the validity of the reasoning and derivations (questions 2–4);
   - whether conclusions are supported by the evidence (the refutation step, with source support where it matters);
   - whether any claim is overstated or insufficiently qualified (the refutation step; §4 claim-scoping);
   - whether relevant concepts or distinctions are omitted (question 6);
   - whether the book represents the source material faithfully (the comparison with the blind reading);
   - whether corrections are needed before the work is accepted (the verdict; findings stand unless disproved).

   **Division of labour:** the Professor writes what he finds and does not keep a separate log of omissions. The reviewer's blind reading is the check on coverage, and its refutation the check on validity.
4. The reviewer may disagree, and gives reasons. A finding stands unless disproved with evidence. Every report states that its independence is process independence, not personal independence.
5. After the review comes one **theory-only turn**: settle the findings and consolidate the sections.

## 7. Limits

- Only allowed files are opened; sealed or hold-out files never.
- **Up to five sources per turn** (one review group). The Professor stops earlier when a source is heavy. The end-of-turn report is the short theory summary of §10. *(v1.3 item 3)*
- The Principal decides the rules and all normative questions.
- Changes apply forward only.

## 8. Dropped (since v1)

- the per-source record files;
- the long justification in each register entry: the concept register is an index of ID, term, status and location;
- per-source change-ledger rows.

The quote check is **kept**, now on the book sections (§4).

## 9. Open (the Principal's decision)

- ~~When whole-book reviews run~~: decided 2026-10-19, see §12c.
- ~~Whether the workspace is committed at checkpoints~~: decided 2026-10-19, see §10.

## 10. Each turn ends with

What the **theory** gained: definitions, derived results, counterexamples, forks opened or settled, and open questions. Plus, per source, the depth read and the number of new concepts.

- **A closing checklist:** every part of the instruction is marked done, or not done with the reason. Nothing is left out silently.
- **A commit at every checkpoint** (after each review settlement; Principal, 2026-10-19: "yes, approve 1 and 2"):
  - only the Professor's own paths;
  - check `git diff --cached --name-status` before, and `git show --name-status HEAD` after;
  - report the commit ID.

## 11. Research branches (Amendments 32a and 32b) — added 2026-10-19T09:41:23Z

Full text: `03-methodology/AMENDMENT-32a-hybrid-research-method.md` (including §32b). In short:

- **When:** only when an open question blocks understanding of the current chapter. One branch at a time; one turn; about ten sources; a closing note in `review/BRANCH-nn-…/`.
- **Opening:** `close_source.py --open-branch BRANCH-nn <slug>` takes a fingerprinted **snapshot of the theory map** before the branch starts (32b). That snapshot is the "before" in the branch review.
- **Logging:**
  - every source opened in a branch gets a read-log row with depth `branch-consult` and its passages;
  - every search gets a `scan` row with its pattern, scope and limits (`branch_search.py --log`);
  - **branch rows never move the reading position.**
- **Status:** concepts from a branch stay **PROVISIONAL** until the branch's independent review is recorded. A directly evidenced defect may be INVALID at once.
- **Predictions:** before chronological reading reaches a branch-consulted source, the branch's predictions are recorded and fingerprinted. They are scored at comparison, and a later synthesis does not confirm an earlier source.
- **Search:** search terms come from the register and the book (including E1–E10). A search reports findings within its scope, never proof of absence.
- **The theory map shows two counts:** sources read in order, and sources consulted in branches.
- **Enforced by the scripts:** `full` and `skim` only for the next source in order; `branch-consult` only with passages and an existing snapshot; no VALIDATED branch concept before its review.

## 12. The mixed method (Principal, 2026-10-19: "yes, approve 1 and 2")

- **Chronological reading for coverage:** sources in index order, five per review group (§2, §6). Every allowed source is eventually read this way, or checked against the book if a topic turn read it first.
- **Topic turns for open forks**, under the conditions of Trial T2 (`TRIAL-T2-topic-first-20261019.md`):
  - a fork that blocks a chapter, or a topic the Principal names;
  - sources chosen by a rule fixed before reading;
  - sources already read on the topic are included;
  - the fork is left open in the first draft;
  - strength scan and step 8 before review;
  - one reviewer commission.

  Topic rows (`topic-full`) never move the reading position.
- **A consolidation review every 25 sources read in order.** The reviewer gives verdicts on concepts marked VALIDATED without an independent verdict, and checks the order (SEQ) of the chapters touched since the last consolidation.
  - **Two reviewers on different models** (for example Opus and Sonnet), commissioned independently, with neither seeing the other's report. Their findings are compared in the settlement: found by both, found by one only. Points found by one only are checked like any other finding.
  - **A planted-gap test** with each consolidation: some points are removed from, or distorted in, a copy of sections already written, by the Principal or the Rule Reviewer (never by the Professor). The answer key is sealed (sha256 in the access record) before the review. Plants must leave no traces in the text. The result is reported as a count ("found k of n"), not as a rate. Every planted point is repaired in the book afterwards, whether found or not.
  - *(Principal, 2026-10-19, after the C10 test: found 6 of 7 planted gaps; a second reviewer on another model found about ten real omissions the first review had missed.)*
- **Research branches (§11)** remain for consulting passages only. A topic turn reads whole sources.

### 12b. Theory turns (R1; Principal, 2026-10-19: "yes, apply R1–R3 as proposed")

- **When:** after every second completed reading window. Only windows in index order count; consolidation reviews, whole-book reviews and theory turns do not.
- **What:** one deliverable of `RESEARCH-PURPOSE.md`, chosen from the "missing" entries of `BOOK-OUTLINE.md`:
  - (a) **a primitive:** a precise definition, and an independence model (a model in which it alone fails while the other primitives hold). If none exists, it is derivable and not a primitive;
  - (b) **the derivation chain** from the primitives to the laws;
  - (c) **a boundary test** of one concrete capability, as three separate analyses: is it necessary? Must the kernel provide it? Can an application context provide it without breaking kernel invariants?
- **How:**
  - up to 8 sources, consulted through a logged topic search under the §12a conditions;
  - reads are `topic-full`, and the reading position does not move;
  - **two reviewers on different models** (for example Opus and Sonnet), independently and in parallel, in topic-review mode; the settlement compares their findings (both / one only) *(Principal, 2026-10-19; evidence: in the consolidation S0036–S0065, 18 of 33 findings were raised by only one of two reviewers)*;
  - the result goes into the book and `BOOK-OUTLINE.md`.
- **Reserved questions:** architectural questions reserved for the Principal (for example F22) are prepared, not decided.
- **Unchanged:** chronological reading and every existing review rule.
- **First theory turn:** the boundary test for F22 (recording authority), directly after the consolidation review S0036–S0065 is settled. From then on: two reading windows, one theory turn.

### 12c. Whole-book review (R2)

- **When:** once S0100 has been read in index order, then every 100 sources read in index order. Sources read in topic turns do not count. At that point it replaces the regular consolidation review.
- **Scope:** **all chapters**, not only recent changes:
  - definitions, derivations and assumptions;
  - forks;
  - cross-chapter dependencies;
  - the architecture view (primitives → laws → results; completeness and minimality of the primitives).
- **How:**
  - the book is divided into parts, chapter by chapter;
  - each part is reviewed by **two reviewers on different models**, independently and in parallel;
  - plus a planted-gap test (§12, prepared by the Principal or the Rule Reviewer, never by the Professor; key sealed before the review);
  - **then a final cross-chapter integration review** (Principal, 2026-10-19): one reviewer reads the chapter reports and `BOOK-OUTLINE.md`, and checks across the parts:
    - the same term used with different meanings in different chapters;
    - a law in one chapter that rests on a concept another chapter rejects;
    - the derivation chain from the primitives to the laws, across all chapters;
    - duplicates (one law with two homes).
- **Result:** cross-chapter defects, merged in one settlement; `BOOK-OUTLINE.md` updated.

### 12d. Generated dependency graph (R3)

- `tmp/tools/dependency_graph.py` builds a graph of concepts and sections from their **explicit** references: CON-IDs, § citations, "existing homes", "rests on" and "depends on".
- **When a concept becomes INVALID or changes in substance,** the script lists every downstream item, transitively (B depends on A, C on B: both are listed).
- **No automatic invalidation:**
  - the Professor reassesses each listed item on evidence in the settlement;
  - the reviewer checks that reassessment in the next review.
- **When it runs:** at every settlement, and before every consolidation or whole-book review. Its list goes into the settlement.
- **Limit, stated in each report:** the graph sees only explicit references. Implicit dependencies remain the task of step 8 and of the reviewers.

### 12a. Flexible reading window (Principal, 2026-10-19: "if you find a topic in a different file while searching, you can take that file also in the research reading window")

- **Window size, in general: 5 to 8 sources.** That is 5 in index order, plus up to 3 added.
- **Trial T3 closed (2026-10-19): a 10 + 4 window is not adopted as the default.** Its own review missed 6 defects that the consolidation found, and its settlement introduced 3 more. Ten sources in index order remain allowed only for a coherent series of short documents, with at most 2 added and the review split into two parallel commissions.
- **The window is not fixed at five.** Its core is the next five sources in index order. If a topic in those sources leads the Professor to another allowed source that bears on it, that source may join the window.
- **Conditions for an added source:**
  - it was found by a **logged search** (`branch_search.py --log`, terms from the register and the book), not picked by memory;
  - **one line on why:** which concept or open question of this window it serves;
  - it is read **whole**, as `topic-full`, and **never moves the reading position**. When index order reaches it later, it is only checked against the book;
  - it goes into the **reviewer's blind step** with the others;
  - usually **at most three added sources per window**, so the review stays thorough. More only with a stated reason.
- **Against picking what fits:** where the search finds several candidates, take the earliest and the latest relevant one, not only the one that supports the current reading.
