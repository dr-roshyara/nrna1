# Chapter 1. Problem, objectives, scope and evidence standard

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18; all corrections applied). Not externally reviewed.*

## 1.1 The problem in plain words

A knowledge system receives a stream of inputs (assertions, observations, requests, corrections, governance acts) and must make **required decisions**:
- Is an operation legal?
- Does an invariant hold?
- Are two things the same?
- Is a contract satisfied?
- What is the current answer to a question?

Two questions follow.

1. **What must the system hold?** The smallest internal state from which every required decision can be answered, now and after any future inputs. If that state is too small, decisions become wrong. If it is too large, the system keeps information it does not need, which costs storage, privacy and clarity.
2. **What must the system retain?** Enough for others, later, to establish **what** was decided, **whether** it was right, to **challenge** it, and to **correct** it. All of this under faults, under different parties' trust, under privacy and deletion rules, and at a storage cost.

These are the two problems this manuscript treats: **P1, sufficient state** (Part II) and **P2, accountable records** (Part III).

*The corpus's own formulation, SRC (v0.4 §15.1):* find "the smallest semantically coherent state representation that contains everything the KnowledgeOS kernel must know, and nothing that merely happens to surround that knowledge". The state must suffice to evaluate invariants, decide legality, execute transformations, distinguish identities, decide equality and replay deterministically.

## 1.2 Objectives

- **O1.** Precise definitions under which "sufficient", "minimal", "recoverable", "verifiable", "contestable" and "correctable" have a single meaning.
- **O2.** Theorems stating **when** a minimal object exists, **when** it is unique, and **when** it is computable, with counterexamples marking each boundary.
- **O3.** A map from theorems to system guarantees, from assumptions to runtime requirements, and from counterexamples to regression tests (Ch. 15).
- **O4.** A clear separation of **mathematical consequence** from **stakeholder choice**.

## 1.3 Scope

**In scope:**
- deterministic decision systems over discrete input histories;
- explicit fault models;
- explicit trust models;
- append-only records.

**Partly in scope:**
- nondeterminism (Ch. 6);
- approximation, where a loss must be declared (Ch. 6).

**Out of scope for proved results:**
- randomized records and cryptographic mechanisms (Ch. 14 lists them as open);
- legal adequacy of remedies;
- the question of whether the **real** KnowledgeOS satisfies the assumptions. That is an empirical question, blocked until the source-validation gate passes (Ch. 16).

## 1.4 Evidence standard

**Labels.** Every claim carries exactly one label:

| Label | Meaning |
|---|---|
| **DEF** | definition |
| **ASM** | a modelling assumption about the system being modelled (true or false of a real system; not provable here) |
| **LIT** | a standard result from the literature, cited and not re-proved; marked "unverified" where Session B has not checked the original source |
| **THM** | proved in this manuscript; the proof is given |
| **CE** | an explicit counterexample, checkable by hand |
| **FIN** | a finite computation with a frozen protocol and recorded code identity |
| **ANA** | an analytic argument short of a full proof |
| **CONJ** | conjecture |
| **OPEN** | open question |
| **SRC** | reported by the corpus, unverified |
| **ARCH** | architectural proposal |
| **STAKE** | a decision reserved for stakeholders or the Principal |

**Composite items.** An item with several parts may carry one label per part, e.g. "THM + CE" for a proved statement with a counterexample showing a limit. EXAMPLE means a CE-type illustration with no refutation intended.

**Chapter status.** Each chapter carries one of:
- **DRAFT**;
- **INTERNALLY REVIEWED**: a refute-first review by a sub-agent of the same model family, which is **not** independent;
- **EXTERNALLY REVIEWED**: a human or other qualified external reviewer.

**Rules.**
- A theorem's status never exceeds the weakest result it depends on.
- Finite computations refute or illustrate; they do not prove general statements.
- Claims withdrawn during the research are listed in Ch. 14 and are not reused.

## 1.5 What the theory does not claim

- It does not claim that KnowledgeOS **is** a system of the kind modelled. Ch. 16 lists the assumptions that the real system must be checked against.
- It does not choose:
  - which decisions are critical;
  - what is retained;
  - how far back corrections may reach;
  - who is trusted.

  The theory shows the **consequences** of these choices. The choices are **STAKE**.

## 1.6 How to read the manuscript

| Reader | Path |
|---|---|
| **Business reader** | §1.1, the "Plain meaning" paragraph of each chapter, and Ch. 15 |
| **Mathematician** | Ch. 2, then any chapter in dependency order (00-ARCHITECTURE §3) |
| **Engineer** | Ch. 2, Ch. 7–13 statements, then Ch. 15 |
