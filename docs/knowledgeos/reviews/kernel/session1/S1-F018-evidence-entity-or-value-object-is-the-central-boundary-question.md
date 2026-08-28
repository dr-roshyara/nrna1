# S1-F018 · **"Is Evidence an Entity or a Value Object?"** — named as *the* central unresolved question that determines the Kernel's shape; plus a fourth classification of the lifecycle relations

**Finding ID:** S1-F018
**Finding class:** QUESTION identified as decisive + fourth incompatible classification of supersession/retraction/contestation/reconciliation + two new candidate concepts
**Status:** OPEN
**Lenses:** DDD · Evidence · Nyāya/pramāṇa · Dharma · Zero · Temporal

---

## Source and provenance

| | |
|---|---|
| **Source document** | `brainstorming/kernel/20260823-123619-kernel-domain-discovery-twelve-questions-across-all-lenses.md` (1,087 lines) |
| **Provenance** | **`P4` MODEL_INTERPRETATION** — answers `S1-F015`'s twelve questions again, this time **through the lens set** (Nyāya/pramāṇa, Dharma, Viveka, Gaṇeśa). ⚠ ⟦VERIFIED⟧ 0 external URLs |
| **Date / phase** | 2026-08-23 **12:36** · Phase 2 — same minute as the consolidation (`123630`) |
| **Register discipline** | uses explicit `FACT:` / `DDD INTERPRETATION:` / `UNRESOLVED:` / `CLASSIFICATION:` labels, and marks one section ⟦C⟧ *"(hypothesis only)"* |

⟦PROVENANCE NOTE⟧ This is the **third pass over the same twelve questions** (after the two passes inside
`114358`). Agreement across passes is **not** independent corroboration.

---

## Finding 1 · The decisive question, named ⟦QUESTION⟧

⟦C⟧ **"UNRESOLVED — CRITICAL:"**
- ⟦C⟧ *"Is Evidence an **Entity** (shareable across multiple claims) or a **Value Object** (immutable
  snapshot unique to this claim)?"*
- ⟦C⟧ *"If **Entity**: cannot be inside the KnowledgeClaim aggregate; the evidence-grounding invariant
  becomes **procedural, not atomic**."*
- ⟦C⟧ *"If **Value Object** (snapshot): can be inside the aggregate but **cannot be shared** and may make
  the aggregate **unbounded**."*
- ⟦C⟧ *"This is the **central unresolved aggregate boundary question that determines the Kernel's
  shape**."*

⟦INFERENCE⟧ **This is the sharpest and most consequential question in the corpus so far**, and it explains
several earlier results rather than adding to them:
- It explains why `S1-F016`'s atomicity test failed on *Identity + Evidence*: if Evidence is an Entity,
  atomicity is **impossible in principle**, not merely unproven.
- It explains why `S1-F017` closed the phase with ⟦C⟧ *"evidence ownership and evidence grounding remain
  unresolved"* — the ownership question is downstream of this classification.
- It states a **genuine dilemma with a cost on each horn**: Entity ⇒ the grounding invariant is procedural
  (so not aggregate-protectable); Value Object ⇒ no sharing and a potentially unbounded aggregate.

⟦INFERENCE⟧ Both horns damage a member list that includes Evidence — and **every** list and formulation in
the corpus includes it (it is one of two survivors of the Phase 1 intersection, `S1-F003`). ⚠ Recorded as
the corpus's own question; **not answered here, not adjudicated.**

⟦C⟧ Its own tentative classification hedges both ways: *"Evidence is likely an **Entity** (if
shared/referenced) or a **Value Object** (if admitted as immutable snapshot). The act
**EvidenceAdmitted** is a Domai[n Event]."*

---

## Finding 2 · Evidence characterised through the pramāṇa lens ⟦INTERPRETATION⟧

⟦C⟧ *"Evidence is **admitted, representation-agnostic supporting material** that grounds the knowledge
claim. It enters the domain at the gate and becomes domain-[owned]"*
⟦C⟧ *"**Nyāya / pramāṇa lens:** Evidence is *pramāṇa* — the means of knowledge. It is **external to
*pramā* (knowledge) but constitutive of it**. You cannot have valid knowledge without a me[ans]."*

⟦INFERENCE⟧ *"External but constitutive"* is a precise statement of why the Entity/VO question is hard: a
constitutive element that is external cannot be a value object, but an external element inside an
atomicity boundary is a contradiction. The lens sharpens the dilemma rather than resolving it.

---

## Finding 3 · A **fourth** classification of the four lifecycle relations ⟦MODEL — hypothesis only⟧

⟦C⟧ Labelled *"DDD INTERPRETATION (hypothesis only)"*:

| Concept | ⟦C⟧ Constitution | ⟦C⟧ Rationale |
|---|---|---|
| **Supersession** | **Relationship + Domain Event** | *"'A supersedes B' is a relation between two KnowledgeClaims… It is NOT a state of[…]"* |
| **Retraction** | **Domain Event + State transition** | *"`KnowledgeRetracted` event transitions the claim's EpistemicState to `RETRACTED`. It is an **irreversible** withdrawal of epistemic[…]"* |
| **Contestation** | **Derived condition** | *"Exists when an unresolved **Challenge** entity/relationship is present. **It is NOT a state of the knowledge claim.** The claim remains in its[…]"* |
| **Reconciliation** | **Domain Event + Relationship** | *"`ReconciliationOccurred` event establishes a resolved relation between conflicting claims **without supersession**"* |

⟦C⟧ Nyāya mapping: *"Supersession is like *bādha* (sublating stronger knowledge overriding weaker).
Retraction is like *pramāṇa-bādha* (defeat of the means of knowledge)."*
⟦C⟧ **UNRESOLVED:** *"Whether these concepts belong in the core Kernel or in a surrounding bounded
context. **The existing law does not require them.**"*

⟦INFERENCE⟧ **Four documents, four incompatible classifications of the same four relations:**

| | `S1-F010` (11:16) | `S1-F014` (11:36) | `S1-F015` (11:43) | **this (12:36)** |
|---|---|---|---|---|
| Supersession | state | relationship→state | relationship | relationship + event |
| Retraction/Withdrawal | state + event | relationship→state | **property** | **event + state transition, irreversible** |
| Contestation | state | relationship→state | relationship | **derived condition — not a state** |
| Reconciliation | state | relationship→state | relationship | event + relationship |

⟦INFERENCE⟧ This is the classification blocker `S1-F013` named, now demonstrated across four attempts in
80 minutes. **No two agree.** ⚠ It also supplies two claims no other pass makes: retraction is
**irreversible**, and contestation leaves the claim's state **unchanged**.

⟦INFERENCE⟧ And a limit worth recording: ⟦C⟧ *"the existing law does not require them"* — i.e. none of the
four relations is law-mandated. That undercuts treating any of them as a Kernel member.

---

## Finding 4 · Two new candidate concepts ⟦MODEL⟧

**`AdmissionContract`** — ⟦C⟧ *"(which defines sufficiency criteria)"*, with ⟦C⟧ **UNRESOLVED:** *"whether
[it] is part of the same aggregate, a separate **Policy**, or an external constitutional
specification[…]"*
⟦INFERENCE⟧ New to Session 1. It is the object `S1-F008`'s *"contract-conformance enforcement"* capability
would enforce, and its placement is exactly the `W:F-CM-1b`-shaped question (inside / outside / part of
the boundary) at research altitude.

**`Challenge`** — an ⟦C⟧ *"entity/relationship"* whose unresolved presence *constitutes* contestation.
⟦INFERENCE⟧ New to Session 1; it is the corpus-side analogue of what ⟦L⟧ v1.1 holds as `ConflictRecord`,
and it bears on unruled `W:C-7` (`CONFLICTED` ↔ `ConflictRecord` cardinality). Noted; **not adjudicated.**

---

## Finding 5 · Ownership placed on the claim, via the dharma lens ⟦INTERPRETATION⟧

⟦C⟧ *"The commitment is owned by the **KnowledgeClaim aggregate** — the structural locus of epistemic
accountability."*
⟦C⟧ *"**Dharma lens:** the aggregate bears the **dharma** (constitutional obligation)… It is the
duty-bearer. **If the aggregate is split, the dharma[…]**"*
⟦C⟧ *"**Nyāya lens:** the *pramātṛ* (knower/subject) in this structural analogy is the **aggregate
itself**."*

⟦INFERENCE⟧ The dharma argument is the corpus's only argument **against** splitting the aggregate — every
other force runs toward decomposition (`S1-F016`, `S1-F017`). It argues that duty needs a single
duty-bearer. ⚠ Recorded as an argument, not a result; it is lens-derived and the sentence is truncated in
the source.
⟦INFERENCE⟧ Note it also makes the *aggregate* the knower — which conflicts with `S1-F012`'s
*accountable epistemic object* framing and with any participant-relative account of knowledge.

---

## DDD interpretation

- `Evidence` → **CANDIDATE ENTITY *or* VALUE OBJECT — undetermined, and decisive.**
- `EvidenceAdmitted` · `KnowledgeRetracted` · `ReconciliationOccurred` · `SupersessionOccurred` →
  **CANDIDATE DOMAIN EVENTS**.
- `AdmissionContract` → **CANDIDATE POLICY / aggregate member / external specification — undetermined.**
- `Challenge` → **CANDIDATE ENTITY or RELATIONSHIP**.
- `Contestation` → **CANDIDATE DERIVED CONDITION**, explicitly not a state.
- `KnowledgeClaim` → **CANDIDATE AGGREGATE ROOT** (⟦C⟧ *"Entity… has domain identity, lifecycle, mutable
  state"*).

---

## Relationship to previous Session-1 findings

- **`S1-F016`** — explains its Identity+Evidence failure as *possibly structural*, not merely unproven.
- **`S1-F017`** — supplies the question behind its *"evidence ownership… unresolved"*.
- **`S1-F010`/`F014`/`F015`** — **fourth incompatible classification**; no two of the four agree.
- **`S1-F013`** — its named blocker now demonstrated four times over.
- **`S1-F008`** — `AdmissionContract` is the missing object behind its *contract-conformance* capability.
- **`S1-F012`** — the dharma/duty-bearer argument conflicts with its accountable-object framing.

---

## Classification, confidence, open questions

**Type:** QUESTION (decisive, source-marked CRITICAL) · MODEL (fourth relation classification, *hypothesis
only*) · INTERPRETATION (pramāṇa, dharma) · two new CANDIDATE CONCEPTS.
**Confidence:** high on quotations; **medium-low** evidential weight (model-generated, no citations, third
pass over the same questions); several source sentences are truncated mid-clause.
**Open questions:** **Entity or Value Object for Evidence?** · where does `AdmissionContract` live? · is
`Challenge` an entity or a relationship? · is retraction irreversible? · does contestation leave state
unchanged? · can the dharma single-duty-bearer argument be reconciled with the decomposition pressure?

**Status:** OPEN. The decisive question is recorded **as a question**; nothing resolved, nothing adjudicated.
