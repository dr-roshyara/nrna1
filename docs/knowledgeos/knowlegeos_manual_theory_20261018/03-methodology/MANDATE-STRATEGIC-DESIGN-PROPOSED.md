# Mandate: the strategic design of KnowledgeOS, derived from the theory (PROPOSED)

| Field | Value |
|---|---|
| Status | **APPROVED (revision 1)** by the Principal, 2026-10-29, S0200 decision D10 ("accept as recommended"; decision record, addendum 2026-10-29T02:08:48Z). The file name keeps "PROPOSED" so that citations hold. Revision 1, 2026-10-29 (Governance Engineer, on the Principal's instruction to prepare revision 1). Revision 0 written 2026-10-28 (commit 066ee3b70) on the Principal's "yes, prepare all three". Part of stage 0b of `IMPLEMENTATION-STRATEGY-PROPOSED.md`; roles as in its §0 |
| For | the **Professor**, reviewed by **Independent Reviewers** under Rulebook v1.4 |
| Starts | after the S0200 consolidation is settled **and** the Principal approves this mandate |
| Governing rules | Rulebook v1.4 (theory turns §12b, a chain of critical-path turns, challenge by type, prior work corpus first, the stability of claims). **This mandate adds no rule to the rulebook;** its acceptance criteria (§5) bind this chain only |

## 1. Purpose

Strategic design in the sense of Domain-Driven Design: **what KnowledgeOS should be as a system**, derived from the theory and the corpus, not from the existing code. It answers: which subdomains exist, which bounded contexts, how they relate, what crosses each boundary, and who owns each rule. It is the reference against which the existing EKS is later audited and new code is built.

**It is derived, not designed to taste.** Every element cites the book's claims and corpus sources it rests on, with its stability level. No part of the book is "approved theory"; each claim carries its own level, and the element inherits the lowest level it rests on. Where the evidence does not decide, the element stays open, named as open.

**Domain meaning, not technical interface.** The baseline states domain meaning and the **published domain contract** (what a context promises its consumers, and under which conditions). It **does not determine or approve** a technical interface, API, transport, data format or implementation mechanism. Any such interface is a separate engineering decision under the repository's engineering process and the Principal's authorisation; the baseline may name what an interface must preserve, never what it is.

## 2. The five elements and when each counts as done

| # | Element | Question | Done when (closure criterion) |
|---|---|---|---|
| 1 | **Subdomains** | which parts are **core** (the reason KnowledgeOS exists), **supporting**, **generic**? | each candidate area classified with its evidence and level, **or marked provisional** with what would decide it; no classification taken from the size, complexity or present structure of any code |
| 2 | **Bounded contexts** | which areas of responsibility have their own model and language? | each context has a responsibility statement, its concepts (CON-IDs), the rules it is responsible for, what it receives and publishes, its evidence and level; **the contradictory admission rule (CON-0085 against S0150's test) is resolved or kept as a named fork** (§2a); the kernel/application boundary is the boundary test's result (§9.6–§9.17), not a new choice |
| 3 | **Context map** | how do the contexts relate (supplier/customer, conformist, shared kernel, anti-corruption layer, published language)? | each relationship with its direction, which side is authoritative, how it is integrated (in domain terms), the language both sides share, **what changes downstream when the upstream context changes**, and its evidence; every relationship that depends on an open fork marked as such (§2a); the direction law handled as a candidate law (§2b) |
| 4 | **Published language** | what does each context, above all the kernel, expose to others? | the kernel's published concepts and contracts listed (the record and its entry kinds, the evaluator's questions and answers T/F/U with refusal reasons, the owner rules, designation), each traced to its claim and level; **as meanings and promises, with no technical interface** (§1) |
| 5 | **Ownership of invariants** | which context guarantees each invariant, and by which operation? | every invariant in the book mapped to its owner and its enforcing operation; invariants with no owner listed as gaps; the "owned versus preserved" test applied; the question in §2c answered or left open with reasons |

**Stability levels** (from the strategy, §3): each element and each line in it is marked S0 unstable · S1 experimental · S2 reviewed core · S3 established. **Tests and design documents never raise a level; a reviewer's verdict does.**

### 2a. Open forks and the context map

The fork between CON-0085 and S0150's test stays open unless a turn resolves it on evidence, through review, or the Principal decides it as a normative question. **No interpretation is selected silently.** While it is open, every context-map relationship whose existence, direction or content depends on it is marked **conditional**, with:

- the open question;
- the relationship affected;
- why the relationship depends on it;
- what evidence or decision would resolve it;
- what stays provisional until then.

The same applies to any other open fork a turn finds. The map is not presented as complete while relationships in it are conditional; its header counts them.

### 2b. The direction law, a candidate to be tested

"Applications depend on the kernel, never the reverse" is a **candidate law**, not an established rule. The turn on the context map states:

- **its exact meaning** (what counts as "depend");
- **its falsifier**: the observable evidence that would show it false, too broad or underspecified (for example, a corpus source in which a kernel decision needs information only an application can provide, or a book invariant that cannot be enforced without such information);
- the evidence **for**, the evidence **against**, the evidence that **does not decide**, and its scope limits.

No evidence is invented, and the law is not described as established before a reviewer's verdict.

### 2c. A question for the ownership turn (not a rule)

Would ownership be clearer if a context's relation to an invariant were one of:

- **owns / enforces** — authoritative for the rule, guarantees it by its operations;
- **preserves** — respects a rule established elsewhere;
- **observes** — can detect or report a violation, cannot prevent it;
- **assumes** — relies on a condition it cannot itself establish?

The Professor decides, with the reviewers, whether these distinctions are **supported by the book and useful**; four categories are not required because they were proposed. Whatever the answer, a context that relies on world assumptions (L1–L11 of §9.15) is **not** described as owning or guaranteeing them. Unresolved terminology goes to the Ch. 6 or Ch. 9 TODO.

## 3. How

- **A chain of theory turns** (v1.4 §12b), one element per turn in the order 1 → 2 → 5 → 3 → 4 (ownership before the map, the map before the language). Each turn: a research question first; prior work corpus first (the corpus contains the August 2026 strategic-discovery and boundary documents of the estate: these are **evidence**, read as Src and judged by the theory); **outside literature** as benchmark (Evans, *Domain-Driven Design*, 2003; Vernon, *Implementing Domain-Driven Design*, 2013; context-mapping patterns), each checked against its text; two reviewers on different models; settled before the next turn.
- **No code, no reference architecture, no EKS files.** The Professor works from the corpus and the book only. The existing system is the Verification Auditor's subject, not the Professor's.
- **Where an element would need a normative choice** (for example, whether a capability *should* be in the kernel when both placements are consistent), it is prepared as a decision for the Principal, one question at a time, in business language.
- **Operational failure is out of scope unless the book treats it.** What happens operationally when an upstream context fails is recorded as an **open** question in the map; no theory of failure is invented for the map.

## 4. Priorities, budget and stop

**Priority order:** (1) correctness and research integrity; (2) unresolved questions recorded explicitly, with their consequences; (3) time and budget. **The budget controls the work, never the answer:** a turn that cannot settle a question within its budget records the question, the evidence considered, the consequence of leaving it open, and what would resolve it — it does not choose an unsupported answer.

- **Budget:** five turns; each turn about one hour of review time (harness). If a turn exceeds twice that, the Professor stops and reports to the Principal through the Governance Engineer.
- **No loops:** a turn that opens new design questions records them in the Ch. 9 or Ch. 6 TODO; it does not start a sixth turn. The chain ends after element 4; reading in index order resumes. More turns need the Principal's approval.
- **Stop condition:** if an element exposes a critical gap in the theory itself (for example, the kernel's existence), the chain pauses and the gap goes to the Principal.

## 5. Output and acceptance

A **strategic-design baseline** in the book (a new section, or Ch. 6 extended, as the Professor proposes in the first turn), plus a one-page **context map** and **ownership table** in `BOOK-OUTLINE.md`. Each line: element · claim and CON-ID · sources · level · open questions. The baseline carries a **version** (the commit at which its last turn was settled), so that an audit can name what it compared against.

**Acceptance criteria for the baseline** (this chain only; not rulebook rules):

| Element | Accepted when | Not acceptable |
|---|---|---|
| Subdomains | every candidate classified with evidence and level, or marked provisional with its decider | a classification taken from existing code structure |
| Bounded contexts | each has a responsibility, owned concepts, rules, evidence and named open questions | a folder, module or namespace treated as a context by default |
| Ownership | every known invariant has an owner and operation, or is listed as a gap | assuming the place that enforces a rule owns it |
| Context map | every relationship with direction, authority, integration, language, change effect and evidence; conditional ones marked | lines drawn without stated meaning; an open fork silently resolved |
| Published language | meanings and promises fixed and traced | a technical interface fixed, or convenience redefining a concept |
| Whole baseline | reviewed by two independent reviewers; open questions visible; levels stated | "done" read as "the theory is complete" or as authorisation to build |

**Acceptance means the baseline is fit to audit against.** It does not answer an open question, raise any claim's level, or authorise any implementation.

## 6. Revision 1 (2026-10-29): what changed and why

| # | Change | Kind | Reason | Section |
|---|---|---|---|---|
| 1 | The baseline fixes no technical interface; meaning and published contract only | substantive | keep domain design apart from engineering decisions (external DDD review, 2026-10-29) | §1, §2 row 4 |
| 2 | Relationships that depend on an open fork are marked conditional, with five items each | substantive | the map must not look complete while it rests on the CON-0085 / S0150 fork | §2a, §2 row 3 |
| 3 | The direction law is a candidate law with a stated meaning and falsifier | substantive | "tested, not assumed" needed a test | §2b |
| 4 | Owns / preserves / observes / assumes as a **question** for the ownership turn | substantive | may sharpen ownership; must be earned by the book, not imposed | §2c |
| 5 | Context-map rows carry authority, integration, language and change effect; operational failure stays open | substantive | a relationship needs a stated meaning; failure is outside the present theory | §2 row 3, §3 |
| 6 | Priority order: correctness, then open questions, then budget | substantive | a budget must not force an answer | §4 |
| 7 | Acceptance criteria in this mandate, not in the rulebook; acceptance authorises nothing | substantive | no rule churn; keep "done" apart from "authorised" | header, §5 |
| 8 | Baseline carries a version | substantive | the audit must name its baseline (auditor mandate §4a) | §5 |
| 9 | Subdomains may be provisional; never classified from code structure | clarification | already implied; made explicit | §2 row 1 |
| 10 | "No approved theory": elements inherit the lowest level they rest on | clarification | the book is not approved as a whole | §1 |
| 11 | Revision 0's date read "2026-10-29"; it was written 2026-10-28 (commit 066ee3b70) | correction | factual error | header |
