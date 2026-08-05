# EPIC-002 Canonical Domain Model Decision

**Kind:** ARB decision record — Strategic DDD gate between evaluation and Context Mapping. **Authority:** generated to capture and justify the ARB's decision; the decision itself belongs to the ARB, not to this document.
**Role change (per ARB instruction):** this artifact does not discover or evaluate. It records what has been decided, what has been rejected, what remains deferred, and what baseline that leaves for the next phase.
**Inputs:** `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md`, `EPIC-002_Strategic_Domain_Discovery.md`, `EPIC-002_Bounded_Context_Discovery.md`, `EPIC-002_Domain_Decomposition_Evaluation.md`, `EPIC-002_Evidence_Family_Independence_Audit.md`. No new sources.
**Explicitly out of scope:** Context Map, relationship patterns (Partnership/ACL/Shared Kernel/Published Language), aggregates, entities, value objects, repositories, APIs, events, services, databases, implementation architecture, IDDs.

---

## 1. Summary of evaluated alternatives

Three whole-domain models were evaluated against nine identical criteria (cohesion, coupling, language consistency, responsibility/decision ownership, evidence traceability, uncertainty handling, architectural stability, evolvability):

- **Model A (Minimal, 2 boundaries)** — merges Collection, Custody, and Contemporaneity into one "Evidentiary Record" boundary alongside a standalone Adjudication boundary.
- **Model B (Maximal, 6–7 boundaries)** — splits every discovered responsibility into its own boundary, including Separability as a standalone boundary.
- **Model C (the catalogue)** — four candidate positions (Collection; Custody, contested between two alternatives; Adjudication, contested with one alternative; Contemporaneous Record-Fixing), with Separability carried as a cross-cutting constraint rather than a boundary.

## 2. Decision

**Model C's four-position skeleton is adopted as the architectural baseline for continuing work.** Verbatim, per the ARB's own stated standard: Model C is **"the best-supported working decomposition under the current evaluation criteria."**

**This is not a canonical-model ratification.** No individual Candidate Domain Boundary is ratified as a Bounded Context by this document. Two of the skeleton's four positions remain genuinely undetermined between named alternatives (§4), and a third carries an open question about whether it should merge with a neighboring position. A decision record that declared these settled would be recording a ruling nobody made.

## 3. Rejected alternatives

- **Model A — rejected.** It hides the program's two most informative tensions (custody-vs-self-verification; adjudication-vs-authority-validity) inside one boundary rather than resolving them — the tensions do not disappear, they simply stop being visible in the model's own structure. It also forces three semantically distinct senses of "evidence" (raw input, custody-object, record) into a single boundary that the Language Boundary Analysis found genuinely discontinuous.
- **Model B — rejected.** It manufactures a boundary (Separability) with no independent responsibility to own — its "activity" would duplicate a constraint (K2) that Collection, Custody, and Contemporaneity must already respect on their own. It also over-commits on the thinnest evidence in the program (Authority-Validity's single, secondary-sourced, jurisdiction-specific candidate) by giving it full boundary status regardless of evidence strength.

## 4. Deferred decisions (three, explicitly symmetric — none resolved, none partially resolved)

1. **CB-1 / CB-4 merger** — both Collection and Contemporaneous Record-Fixing are decision-light (Collection owns no decision at all; Record-Fixing owns only D2) and both feed Adjudication. Per the ARB's own instruction: **not answered here** — to be validated against the actual domain, not settled by evidentiary analysis alone.
2. **CB-2 vs. CB-2-Alt (custody vs. self-verification)** — the literature itself is split (T1), and two refuted stronger versions on the self-verification side do not settle which reading, if either exclusively, applies. **Not resolved here.**
3. **CB-3 vs. CB-3-Alt (adjudication vs. authority-validity)** — Adjudication is the strongest decision-owning candidate in the program (8 independent families, tied with Collection — corrected from an earlier 11; see `EPIC-002_Evidence_Family_Independence_Audit.md`), but whether Authority-Validity is a genuine split or an artifact of one thin, secondary-sourced candidate is **not resolved here.**

All three carry equal status: open, unresolved, awaiting either further domain validation or an explicit future ARB ruling — not one treated as closer to settled than the others.

## 5. What this decision establishes

- **Architectural baseline:** four candidate positions — Collection, Custody (two named alternatives), Adjudication (with one possible split), Contemporaneous Record-Fixing — with Separability carried forward as a cross-cutting constraint, not a position.
- **Not established:** which specific boundaries will become canonical Bounded Contexts; how many boundaries the domain will ultimately have; whether custody resolves toward one design or both; whether adjudication is one capability or two.

## 6. Open questions for the ARB (carried forward, not narrowed)

1. Should the CB-1/CB-4 merger be tested against implementation experience before Context Mapping, or held open indefinitely as an acceptable architectural uncertainty (as the program has already done with P5)?
2. Should CB-2/CB-2-Alt be resolved as an architectural choice now, or remain an explicit documented alternative into Context Mapping?
3. Same question for CB-3/CB-3-Alt — is the Authority-Validity evidence sufficient to ever justify separate status, or should it be dropped as a candidate given how thin it is?
4. How should "Separability as a cross-cutting constraint" be carried forward operationally, given it will not have its own boundary?
5. Given none of the three deferred items are resolved, is a **partial** Context Map — covering only the positions without an open alternative — an acceptable next step, or does the ARB want all three items settled first?

## 7. Architectural-baseline statement for Context Mapping

**Context Mapping remains not yet authorized.** Two of the skeleton's four positions (Custody, Adjudication) are still genuinely undetermined between named alternatives, and a third (the Collection/Record-Fixing pair) carries an open merger question. A complete Context Map requires knowing what the boundaries actually are; that is not yet the case for half the skeleton. Proceeding requires either (a) an explicit ARB ruling on one or more of the three deferred items, or (b) an explicit ARB decision to begin a partial or provisional Context Map that documents the open alternatives as unresolved rather than presupposing an answer — a choice for the ARB, not one made by this record.

---

**Stop condition:** this record captures and justifies the decision as made; it does not extend it. **STOP.** Context Map, relationship-pattern assignment, Tactical DDD, and implementation specifications remain not authorized until the ARB rules on one or more of the open questions in §6.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md`, `EPIC-002_Strategic_Domain_Discovery.md`, `EPIC-002_Bounded_Context_Discovery.md`, `EPIC-002_Domain_Decomposition_Evaluation.md`, `EPIC-002_Evidence_Family_Independence_Audit.md` · No new sources consulted.*
