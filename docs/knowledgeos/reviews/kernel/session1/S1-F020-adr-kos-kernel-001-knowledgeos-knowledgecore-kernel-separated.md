# S1-F020 · A drafted ADR separates **KnowledgeOS ⊃ KnowledgeCore ⊃ Kernel** and defines the Kernel by a *question*, not a member list

**Finding ID:** S1-F020
**Finding class:** ARCHITECTURE PROPOSAL (drafted ADR, status PROPOSED) — the corpus's only artifact that separates the three altitudes explicitly
**Status:** OPEN — proposal only, never adopted in this corpus
**Implementation relevance:** **POSSIBLE IMPLEMENTATION CANDIDATE** (a scope clarification, not a mechanism)
**Lenses:** DDD · Boundary · Zero

---

## Source and provenance

| | |
|---|---|
| **Source document** | `brainstorming/kernel/20260823-232146-adr-kos-kernel-001-kernel-scope-and-boundary-proposal.md` (753 lines) |
| **Provenance** | **`P7` ARCHITECTURE_PROPOSAL** — a drafted ADR embedded in a brainstorming document. Framing sentence is project-voiced: ⟦C⟧ *"I would make this ADR **clarifying, not redesigning**"* |
| **Declared status in-document** | ⟦C⟧ *"**Status:** PROPOSED · Decision Owners: HPA / Architecture Review Board · Decision Type: Architectural scope clarification"* |
| **Date / phase** | 2026-08-23 **23:21** · Phase 2 |
| ⚠ **Standing** | **PROPOSED, not adopted.** ⟦C⟧ *"It does **not authorize implementation** and does **not reopen** the existing KnowledgeOS Constitution."* Census T-7 flags this document as one of three at highest risk of being read as architecture. |

---

## Finding 1 · The three-altitude separation ⟦DEFINITION — proposal⟧

⟦C⟧ **2.1** *"KnowledgeOS is **not synonymous with the Kernel**… The Kernel is therefore a **proper subset
of the KnowledgeOS architecture**. The Kernel must not be interpreted as 'all of KnowledgeOS'."*

⟦C⟧ **2.2** *"**KnowledgeCore** is the bounded context containing the authoritative domain model for
admitted knowledge and its constitutional lifecycle."* And a boundary rule: ⟦C⟧ *"External contexts may
produce expressions, interpretations, candidates, evidence, decisions, projections, or other inputs.
**Those contexts do not become Kernel responsibilities merely because their outputs are consumed by
KnowledgeCore.**"*

⟦C⟧ **2.3** *"The Kernel is the **smallest authoritative protection boundary**"*, defined by the question:
> ⟦C⟧ *"**What is the smallest authoritative boundary that must exist to preserve KnowledgeCore's
> constitutional invariants?**"*
⟦C⟧ *"The Kernel SHALL therefore be derived from: domain invariants; consistency requirements; …"*

⟦INFERENCE⟧ **This is the first document in the corpus to keep KnowledgeOS, KnowledgeCore and Kernel
apart as three distinct altitudes** — the separation the extraction brief insists on. Every earlier
formulation (S1-F009 … F019) answers *"what is the Kernel"* without fixing what surrounds it.
⟦INFERENCE⟧ The consumption rule in 2.2 is the sharpest anti-absorption statement found: it blocks the
mechanism by which every capability list grew — a capability enters the Kernel because the Kernel uses
its output.
⟦INFERENCE⟧ And note the method: the Kernel is defined **by a question and a derivation rule**, not by
enumeration. That directly answers the failure mode S1-F007 diagnosed and S1-F016/F017 falsified —
membership by capability enumeration.

---

## Finding 2 · The ADR records the falsification as settled context ⟦FACT⟧

⟦C⟧ *"The brainstorming phase intentionally explored multiple hypotheses, including an
epistemic-state-transition kernel, a large KnowledgeAggregate, an invariant-enforcement mechanism, and an
admission boundary. **The subsequent DDD work falsified the assumption that conceptual relatedness alone
establishes an aggregate or consistency boundary.**"*

⟦INFERENCE⟧ Written ~30 minutes after `S1-F019` re-asserted the aggregate hypothesis **without**
acknowledging that falsification. This document does acknowledge it. ⟦INFERENCE⟧ So within one evening the
corpus contains both a document that ignores the falsification and a proposal that treats it as
established context. Recorded; **not reconciled**.

⟦C⟧ It also names the ambiguity it exists to remove — four competing senses of "Kernel": *"the wider
ecosystem"* · *"KnowledgeCore as the bounded context"* · *"the constitutional/invariant altitude"* ·
*"the smallest executable/authoritative boundary"*.
⟦INFERENCE⟧ **A four-way vocabulary collision on the word *Kernel* itself**, stated by the corpus. This
subsumes several apparent disagreements in earlier findings: documents may have been answering different
questions under one word.

---

## DDD interpretation

- `KnowledgeOS` → **CANDIDATE system boundary** (contains domain + supporting contexts).
- `KnowledgeCore` → **CANDIDATE CORE-DOMAIN BOUNDED CONTEXT**.
- `Kernel` → **CANDIDATE smallest authoritative protection boundary within KnowledgeCore**, derived from
  invariants and consistency requirements.
- The 2.2 consumption rule → **CANDIDATE POLICY** governing context boundaries.

⟦L⟧ Comparison target only: v1.1 §16 sets kernel/mechanism/representation altitudes and §4 makes the core
domain *Knowledge Identity*. ⟦INFERENCE⟧ **CONSISTENT in structure** (a subset relation with invariants at
the centre); the ADR adds an explicit named middle layer (`KnowledgeCore`) and the consumption rule.
**Not adjudicated.**

---

## Relationship to previous Session-1 findings

- **`S1-F007` / `S1-F016` / `S1-F017`** — this proposal's derivation rule is the constructive answer to
  their negative results.
- **`S1-F019`** — same evening, opposite treatment of the falsification.
- **`S1-F009`…`S1-F012`, `S1-F019`** — the four-way *Kernel* collision may explain part of their apparent
  disagreement.
- **`S1-F008`** — its nine capabilities would now need re-derivation from invariants, not assertion from
  "existing law".

---

## Classification, confidence, open questions

**Type:** ARCHITECTURE PROPOSAL (PROPOSED) · DEFINITION (three altitudes) · FACT (falsification as
context) · POLICY (consumption rule).
**Confidence:** high on quotations; **the standing is unambiguous — proposed, unadopted, non-authorising.**
**Open questions:** was this ADR ever ruled on? (no adoption record found in `kernel/`) · does
`KnowledgeCore` correspond to any earlier document's *Knowledge Context* (`S1-F007`)? · which invariants
does the derivation rule actually yield?

**Status:** OPEN. Proposal recorded, **not** treated as a decision.
