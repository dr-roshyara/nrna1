# The KnowledgeOS theory method on one page

*Working method under the approved M-04 v1.0, which is unchanged. It summarizes the working-practice note (`M-04-working-practice-20261018.md`) with the Principal's clarifications of 2026-10-18. It is not an amendment.*

> **The theory book is the deliverable.** Reviews, scripts, registers and reports exist to improve the book, not to become the work.

## Per source

1. **Check permission.** Authorized material only; never the restricted corpus.
2. **Read the whole source to understand it:** concepts, arguments, assumptions, examples, tables, code, appendices, limits, open questions.
3. **Compare each meaningful idea with the book:** new, already there, refinement, correction or contradiction, evidence, or open. A later document is not automatically right.
4. **Test it by reasoning:** do the premises support the conclusion; is there a counterexample; does it conflict with what is established; does the source really say it; is an inference being presented as a fact?
5. **Write it into the book**, precise and in plain business language. Keep separate what the source says, what was verified, what is inferred, and what is a hypothesis.
6. **Keep a light record:** idea, source reference, what was done with it, open question.
7. **Look back:** anything important missed? Does the change fit what it touches?
8. **Valid alternatives:** when several concepts are defensible, keep all of them. Explain their assumptions, differences and consequences, and give a recommendation marked as the AI's. The Principal decides where a decision is reserved.

## Judge the reasoning, not the phrasing

**Derivation stability is the central criterion of progress.** Different AI sessions may explain the same idea in different words; that is normal.

| What changes | What we do |
|---|---|
| different wording, same meaning | accept it; no revision |
| same conclusion, clearer explanation | improve the book when useful, not for stylistic consistency |
| different derivation, same conclusion | check the reasoning and its assumptions |
| same premises, conflicting conclusions | investigate the logical difference |
| new evidence changes a conclusion | reassess the affected theory |
| two concepts both defensible | keep both, explain the consequences, recommend one |
| a derivation contains a logical defect | correct it and assess its impact on dependent chapters |

**For each important claim the AI must establish:**
1. **premises**;
2. **assumptions**, stated and unstated but necessary;
3. **inference**: does the conclusion follow?
4. **counterexamples**;
5. **evidence**: does the source support it?
6. **dependencies**: does it contradict or change another established result?
7. **status**: established, disproved, conditional, hypothesis, or unresolved.

The written explanation can change; these obligations cannot disappear.

**Wording does matter when it changes meaning:** a definition, a quantifier ("always" vs "sometimes"), a scope, a condition, or a formal claim. Cosmetic differences are ignored; semantic precision is kept.

## Kinds of evidence, kept apart

| Kind | What it establishes | What it does not |
|---|---|---|
| **Formal proof** | the claim, under its stated assumptions | that the assumptions are right, or that the formal model matches the written theory |
| **Counterexample** | that a universal claim is false | how to repair it |
| **Generated tests** | candidate counterexamples and encoding defects | correctness. A test checks the encoding, not the text |
| **Source verification** | that the source supports the claim attributed to it | that the source is true |

A proof can contain an unstated assumption, so independent reasoning checks both the assumptions and the link between the written theory and the formal model.

## Who does what

| Work | AI reasoning | Python script |
|---|---|---|
| reading, understanding, judging relevance | ✅ entirely | — |
| comparing with the book | ✅ | flags overlaps and term drift only |
| testing ideas, counterexamples | ✅ | runs generated situations to find cases worth reasoning about |
| proofs | ✅ | — |
| writing theory and business explanations | ✅ entirely | — |
| alternatives and decision briefs | ✅ | — |
| independent challenge | ✅ a second, independent AI session | — |
| does a cited line exist; counts and fields in platform records; term search; fingerprints and diffs | interprets the results | ✅ mechanical |

## Review

- **Three perspectives:**
  1. the lead AI's own review;
  2. an independent AI reviewer, given the claims and the evidence but not the lead's conclusions, and asked to falsify;
  3. the Principal's chapter review (ChatGPT).
- **Findings are judged on evidence and reasoning, never by majority.** When reviewers disagree, the lead investigates the disagreement and reports the result, open if it stays open, instead of deferring or starting another round.
- **A new review round** follows only a **material** change to the theory, the evidence or a derivation, or a demonstrated unresolved defect. Never a cosmetic edit.

## Continuity

- **Baselines.** A reviewed chapter becomes a **versioned baseline** (date and hash): not immutable, but not reopened lightly.
- **When later work affects an earlier chapter,** do not reopen the whole chapter. Record the **affected claim**, assess the impact, and revise only what is necessary. A broader revision is justified only when the new derivation changes the foundations or reveals interconnected defects.
- **Reasons for revision:**
  - new evidence;
  - a new derivation;
  - a demonstrated contradiction;
  - a material traceability defect;
  - an authorized decision.

  Never editorial preference alone.
- **Each revision records:**
  1. previous and proposed statements;
  2. reason and evidence;
  3. type (substantive, cross-chapter, traceability, editorial);
  4. impact;
  5. whether review or a Principal decision is needed.
- **Open questions** are recorded and do not block the next batch, unless one makes the next step invalid or unauthorized.

## Not done any more

- line-by-line coverage marking;
- lists of non-substantive lines;
- strict per-row validation;
- an audit document per round;
- repeated full-book scans;
- re-wording stable text.

Records stay proportionate to the risk and significance of the finding.

## Boundaries

- M-04 v1.0 unchanged.
- No access beyond authorization.
- No batch without explicit authorization.
- Reserved decisions stay with the Principal.
- Workflow-state housekeeping (session log, context) is kept brief and is never confused with theoretical correctness.

> **Research continuously. Correct substantively. Review independently. Preserve alternatives. Decide at genuine governance boundaries. Finish the book.**
