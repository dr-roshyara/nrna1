# Mandate: the strategic design of KnowledgeOS, derived from the theory (PROPOSED)

| Field | Value |
|---|---|
| Status | **PROPOSED, NOT APPROVED.** Written by the Governance Engineer, 2026-10-29, on the Principal's "yes, prepare all three". Part of stage 0b of `IMPLEMENTATION-STRATEGY-PROPOSED.md` |
| For | the **Professor**, reviewed by **Independent Reviewers** under Rulebook v1.4 |
| Starts | after the S0200 consolidation is settled and the Principal approves this mandate |
| Governing rules | Rulebook v1.4 (theory turns §12b, a chain of critical-path turns, challenge by type, prior work corpus first, the stability of claims) |

## 1. Purpose

Strategic design in the sense of Domain-Driven Design: **what KnowledgeOS should be as a system**, derived from the theory and the corpus, not from the existing code. It answers: which subdomains exist, which bounded contexts, how they relate, what crosses each boundary, and who owns each rule. It is the reference against which the existing EKS is later audited and new code is built.

**It is derived, not designed to taste.** Every element cites the book's claims and corpus sources it rests on, with its stability level. Where the evidence does not decide, the element stays open, named as open.

## 2. The five elements and when each counts as done

| # | Element | Question | Done when (closure criterion) |
|---|---|---|---|
| 1 | **Subdomains** | which parts are **core** (the reason KnowledgeOS exists), **supporting**, **generic**? | each candidate area classified with its evidence and level; unresolved cases named with what would decide them |
| 2 | **Bounded contexts** | which areas of responsibility have their own model and language? | each context has a responsibility statement, its concepts (CON-IDs), its evidence and level; **the contradictory admission rule (CON-0085 against S0150's test) is resolved or kept as a named fork**; the kernel/application boundary is the boundary test's result (§9.6–§9.17), not a new choice |
| 3 | **Context map** | how do the contexts relate (supplier/customer, conformist, shared kernel, anti-corruption layer, published language)? | each relationship with its direction and its evidence; the direction law (applications depend on the kernel, never the reverse) tested, not assumed |
| 4 | **Published language** | what does each context, above all the kernel, expose to others? | the kernel's published concepts and contracts listed (the record and its entry kinds, the evaluator's questions and answers T/F/U with refusal reasons, the owner rules, designation), each traced to its claim and level |
| 5 | **Ownership of invariants** | which context guarantees each invariant, and by which operation? | every invariant in the book mapped to its owner and its enforcing operation; invariants with no owner listed as gaps; the "owned versus preserved" test applied |

**Stability levels** (from the strategy, §3): each element and each line in it is marked S0 unstable · S1 experimental · S2 reviewed core · S3 established. **Tests and design documents never raise a level; a reviewer's verdict does.**

## 3. How

- **A chain of theory turns** (v1.4 §12b), one element per turn in the order 1 → 2 → 5 → 3 → 4 (ownership before the map, the map before the language). Each turn: a research question first; prior work corpus first (the corpus contains the August 2026 strategic-discovery and boundary documents of the estate: these are **evidence**, read as Src and judged by the theory); **outside literature** as benchmark (Evans, *Domain-Driven Design*, 2003; Vernon, *Implementing Domain-Driven Design*, 2013; context-mapping patterns), each checked against its text; two reviewers on different models; settled before the next turn.
- **No code, no reference architecture, no EKS files.** The Professor works from the corpus and the book only. The existing system is the Verification Auditor's subject, not the Professor's.
- **Where an element would need a normative choice** (for example, whether a capability *should* be in the kernel when both placements are consistent), it is prepared as a decision for the Principal, one question at a time, in business language.

## 4. Budget and stop

- **Budget:** five turns; each turn about one hour of review time (harness). If a turn exceeds twice that, the Professor stops and reports to the Principal through the Governance Engineer.
- **No loops:** a turn that opens new design questions records them in the Ch. 9 or Ch. 6 TODO; it does not start a sixth turn. The chain ends after element 4; reading in index order resumes.
- **Stop condition:** if an element exposes a critical gap in the theory itself (for example, the kernel's existence), the chain pauses and the gap goes to the Principal.

## 5. Output

A **strategic-design baseline** in the book (a new section, or Ch. 6 extended, as the Professor proposes in the first turn), plus a one-page **context map** and **ownership table** in `BOOK-OUTLINE.md`. Each line: element · claim and CON-ID · sources · level · open questions.
