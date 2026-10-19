# Sufficient State and Accountable Records: a reconstructed S-Series theory (manuscript v1)

| Field | Value |
|---|---|
| Status | **NEW MANUSCRIPT, under construction.** It does not replace `../S-SERIES-THEORY-MANUSCRIPT.md` (v0.4, unapproved draft, unchanged) and does not edit any research record. |
| Author | Session B (Professor), 2026-10-18 |
| Evidence standard | Every claim carries one label (per part for composite items): **DEF** (definition) · **ASM** (modelling assumption) · **LIT** (cited literature) · **THM** (proved here) · **CE** (counterexample) · **FIN** (finite computation; protocol and code identity recorded) · **ANA** (analytic argument) · **CONJ** · **OPEN** · **SRC** (corpus-reported, unverified) · **ARCH** (architectural proposal) · **STAKE** (stakeholder or Principal decision). Review status per chapter: DRAFT → INTERNALLY REVIEWED (same model family) → EXTERNALLY REVIEWED. **No chapter is externally reviewed.** |
| Access boundary | Built only from authorized material: this workspace and Session B's earlier recorded reads. **No new corpus reading** while the gate is BLOCKED. Corpus constructs appear as **SRC motivation** with explicit conditional mappings. |

## 1. Reconstruction: the problem the corpus is trying to solve

The corpus asks for the smallest state a knowledge system must hold to do six things:
- evaluate invariants;
- decide operation legality;
- execute transformations;
- distinguish identities;
- decide equality;
- replay deterministically.

It insists on separating **state, evidence, history, operations and governance acts**. It requires **contract-relative evaluation** (Gap/Zero), a **decision loop** and **non-monotone correction**. *(SRC: step 285; documents 30 and 38; Session A's families. Their detail is in v0.4 §15.)*

Read as mathematics, this is **two coupled problems**:

| Problem | Question | Where it is treated |
|---|---|---|
| **P1: sufficient state** | What is the minimal information a system must *hold* to answer its required decisions now and in every future, possibly under constraints on how that information may be read or updated? | Part II |
| **P2: accountable records** | What must a system *retain* so that past decisions can be recovered, checked, contested and corrected, given faults, parties with different trust, privacy and deletion rules, and storage costs? | Part III |

**The link between them:** both are governed by **one information order on functions of history** (Ch. 2), and both reduce "minimal" to a **greatest element of a constrained family of equivalence relations**. Part IV states when such an element exists, when it fails, and what that means for architecture.

## 2. Research questions

| RQ | Question | Chapter |
|---|---|---|
| RQ1 | Which minimal state answers every required decision now and in every future? Is it unique? Is it computable? | 3 |
| RQ2 | How do policy, decoder and update constraints change the minimum? When is it unique? | 4 |
| RQ3 | How is contract satisfaction (gap, Zero) structured? | 5 |
| RQ4 | What survives approximation and nondeterminism? | 6 |
| RQ5 | What must a record keep so that past decisions can be **recovered**? | 7 |
| RQ6 | What must it keep so that decisions can be **checked**, under explicit fault and trust models, in single and multi-step processes? | 8, 9 |
| RQ7 | What do **affected parties** need, compared with auditors? | 10 |
| RQ8 | How must records handle **corrections, supersession and revision limits**? | 11 |
| RQ9 | How do **privacy and deletion** constrain all of the above? | 12 |
| RQ10 | What does it **cost** to store, under named encodings? | 13 |
| RQ11 | Which results hold for **randomized** records? | 14 (currently OPEN; no chapter of results yet) |

## 3. Table of contents and dependency structure

**Part I. Foundations**
1. Problem, objectives, scope and evidence standard
2. The formal system model: inputs, histories, decisions, realizations, records, and the information order

**Part II. Sufficient state (P1)**

3. Behavioural equivalence and the canonical state
4. Constrained state: policies, decoders, updates and schemas
5. Contracts, gaps and Zero (an evaluation layer)
6. Approximation and nondeterminism

**Part III. Accountable records (P2)**

7. Records and recoverability
8. Verification under fault and trust models
9. Multi-step verification
10. Contestability: auditors and affected parties
11. Corrections, supersession and bitemporal history
12. Privacy, deletion and retention
13. Storage and encodings

**Part IV. Synthesis and use**

14. Integrated theory, limitations and open problems (including randomized records)
15. From theory to architecture: a traceability map
16. Validation strategy: external review, source validation, reference model

```
1 → 2 ─┬→ 3 ─┬→ 4 → 6
       │     └→ 5
       └→ 7 ─┬→ 8 → 9 → 10
             ├→ 11 → 12 → 13
             └→ (8, 11) → 12
   {3–13} → 14 → 15 → 16
```

*Forward references used in the text:* 11 → 10 (Ch. 10's level (iii) uses DEF 11.6a); 6 → 5 (Ch. 5 cites Prop. 6.6); 10 → 12 (Ch. 12 cites CE 10.5).

*Rationale:* Ch. 2's information order (Lemma 2.6) is the single foundation for both parts. Ch. 7 needs Ch. 2, not Ch. 3, apart from its resumable variant, which uses Ch. 3.

## 4. Evidence map (the inputs to the reconstruction; re-derived, not copied)

| Material | Status entering v1 |
|---|---|
| The deterministic kernel results (Nerode, decidability, constrained minimality, approximation, nondeterminism) | internally reviewed reports 1805–1951. **Re-proved in Ch. 3–6** |
| The O-10 record theory (recoverability, verification, F2-7J, contestability, corrections, privacy, storage) | internally reviewed reports 2016–0615 and `../O-10-SYNTHESIS.md`. **Re-proved in Ch. 7–13** |
| Corpus constructs: Gap/Zero, the five core operations, ABK-1, the T-7 witness, the eight primitives, the layered pipeline | **SRC only.** Used as motivation, with conditional mappings. Verification awaits the gate |
| Withdrawn claims (O-10-SYNTHESIS §7, plus earlier) | **not reinstated.** The chapters cite the corrected forms |

## 5. Traceability skeleton (filled in as chapters stabilize; Ch. 15 completes it)

| Theory item | System concept | Guarantee or test |
|---|---|---|
| decision set 𝔇 (Ch. 2) | versioned specification of required decisions | changing 𝔇 is a schema-migration event |
| input completeness (Ch. 2) | events carry every datum a decision reads | ARCH: a determinism/replay test (re-running the decisions on the stored event log reproduces the recorded answers). Purity itself cannot be checked at runtime |
| canonical state (Ch. 3) | the kernel as a fold over events | a regression test: equivalent histories give equal answers |
| counterexamples | regression tests | each CE becomes a test case |

## 6. Working method

- **Chapters are drafted in dependency order.** Each one is reviewed by a refute-first reviewer, with independent checks where computation matters, before it is marked INTERNALLY REVIEWED.
- **Later results that expose a weakness reopen the earlier chapter.**
- **Outputs are kept to three:** this manuscript, a short backlog (§7), and validation evidence in `../synthetic-checks/`.

## 7. Backlog (at most five)

1. ~~Chapters 1–3~~: **INTERNALLY REVIEWED** (2026-10-18).
2. ~~Chapters 4–6~~: **INTERNALLY REVIEWED** (2026-10-18). The Ch. 4 review found and fixed one error, Thm 4.5(b),(c).
3. ~~Chapters 7–10~~: **INTERNALLY REVIEWED**. Ch. 10 had two rounds.
4. ~~Chapters 11–13~~: **INTERNALLY REVIEWED**.
5. Chapters 14–16: drafted, with the cross-chapter consistency review done and its corrections applied. **Next:** re-review the late additions, then the external review package.
