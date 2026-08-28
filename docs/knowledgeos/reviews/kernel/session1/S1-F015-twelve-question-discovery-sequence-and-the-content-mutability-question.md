# S1-F015 · A twelve-question discovery sequence, an explicit change/invariance table — and one question the corpus marks UNRESOLVED that nothing else has asked: **can a claim's content change?**

**Finding ID:** S1-F015
**Finding class:** METHOD (discovery sequence) + MODEL (change table, four lifecycle relations) + a **new unresolved question** with identity consequences
**Status:** OPEN
**Lenses:** Temporal · Identity · DDD · Evidence · Justification · Zero

---

## Source and provenance

| | |
|---|---|
| **Source document** | `brainstorming/kernel/20260823-114358-deepseek-kernel-twelve-fundamental-questions.md` (**2,506 lines** — largest early-kernel document) |
| **Provenance** | **`P4` MODEL_INTERPRETATION** (DeepSeek, per filename); ⚠ ⟦VERIFIED⟧ **zero external URLs**. Composite: the twelve questions are answered twice (§§1–12 from line 32, then again from line 1352), with a project section from ~line 584 — ⟦C⟧ *"The 12 questions form the correct discovery sequence"* → `P5` |
| **Date / phase** | 2026-08-23 **11:43** · Phase 2 — 7 min after `113645` |

⟦PROVENANCE NOTE⟧ The same twelve questions are answered **twice within one file**. ⟦INFERENCE⟧ Agreement
between the two passes is **not** independent corroboration.

---

## Finding 1 · The twelve-question sequence ⟦METHOD⟧

⟦C⟧ 1. *What exactly is being committed to?* · 2. *Who/what owns the commitment?* · 3. *What identity does
the commitment receive?* · 4. *What evidence is attached to it?* · 5. *What makes the justification
sufficient?* · 6. *What exactly is the epistemic state?* · 7. *What is confidence?* · 8. *What changes over
time?* · 9. *What must remain invariant?* · 10. *What constitutes supersession, retraction, contestation
and reconciliation?* · 11. *Entity / VO / Event / Relation / Policy / Aggregate responsibility* ·
12. *What is the smallest consistency boundary?*

⟦C⟧ Project endorsement: *"The 12 questions form the **correct discovery sequence**."*

⟦INFERENCE⟧ Note the ordering: the boundary question is **twelfth, not first** — the sequence derives the
boundary from commitment, ownership, identity, evidence, justification, state, confidence, change and
invariance. ⟦INFERENCE⟧ Every earlier Kernel formulation in the corpus (`S1-F009`, `F011`, `F012`, and the
Phase 1 lists) answered question 12 *first*. Recorded as a **method finding**: the corpus contains a
sequence that would have prevented its own eight competing formulations.

---

## Finding 2 · An explicit change/invariance table ⟦MODEL⟧

⟦C⟧ *"What changes over time?"*

| Aspect | ⟦C⟧ Changes? | ⟦C⟧ How |
|---|---|---|
| **Identity** | ❌ **NEVER** | *"Identity is immutable"* |
| Epistemic State | ✅ | *"Through events (Supersession, Contestation, Reconciliation, etc.)"* |
| Confidence | ✅ | *"evidence changes, historical stability, governance reassessment"* |
| Evidence References | ✅ | *"evidence admission, invalidation, restoration"* |
| Justification | ✅ | *"evidence changes, reasoning updates, governance reassessment"* |
| History | ✅ | *"the history grows"* |
| **Content** | ✅ **?** | ⚠ **UNRESOLVED** |

⟦C⟧ *"The Kernel preserves the **sequence of changes** (history), not just the current state."*

⟦INFERENCE⟧ This is the corpus's first **member-by-member** mutability specification. ⚠ It **contradicts
`S1-F013`'s Model A**, which declared Evidence, Justification, EpistemicState and Confidence all
*immutable* with change carried solely by History. Here four of those five are explicitly mutable. Two
documents nine minutes apart, incompatible on mutability. **Recorded, unresolved.**

---

## Finding 3 · The new unresolved question — **content mutability** ⟦QUESTION⟧

⟦C⟧ *"Can a claim's content change, or is that a new claim?"* — presented as two positions:

| ⟦C⟧ Position | Reasoning | Implication |
|---|---|---|
| **Content is immutable** | *"If the claim's content changes, it's a new claim; identity changes"* | *"Supersession is the mechanism for replacing claims"* |
| **Content is mutable** | *"A claim can be amended; content changes but identity persists"* | *"Amending is a lifecycle transition"* |

⟦C⟧ *"**This is UNRESOLVED and requires governance policy.**"*
⟦C⟧ And the consequence for history: *"If content is mutable, the history records **amendments**. If
content is immutable, the history records **supersession**."*

⟦INFERENCE⟧ **This is the sharpest formulation in the corpus of the question I have repeatedly recorded as
unasked** — *"does change create a new identity?"* Earlier findings (`S1-F010`, `S1-F013`, `S1-F014`) all
circle supersession and withdrawal without asking whether the *content* may change under a persisting
identity. Here it is stated, with both horns and their distinct history consequences.

⟦INFERENCE⟧ It also introduces **`Amendment` as a candidate lifecycle transition** — absent from
`S1-F010`'s rival state set and from every member list so far.

⟦L⟧ Comparison target only: v1.1 §9 holds transitions *"forward-only; nothing overwritten"* and
*"a revision creates a new state and retains the prior one"*, with INV-KOS-HISTORY-001 and *Revision ≠
Erasure*. ⟦INFERENCE⟧ That governs **state** succession; whether *content* may be amended under a stable
`KnowledgeId` is **NOT ADDRESSED** as far as this comparison reaches. ⚠ Recorded as an open research
question, **not** a gap claim, and **not adjudicated** — it is adjacent to unruled `W:C-4` (targeting an
existing `KnowledgeId`) and `W:C-15`.

---

## Finding 4 · Four lifecycle relations specified, with guards ⟦MODEL⟧

⟦C⟧ **Supersession** — *"a **relationship** between a new claim (superseder) and an old claim
(superseded)"*; changes: *"Superseded: ADMITTED → SUPERSEDED; Superseder: PROPOSED → ADMITTED"*; guard:
*"Superseder must be admitted; Superseded must be ADMITTED or CONTESTED"*; events `KnowledgeSuperseded`
+ `KnowledgeAdmitted`; *"relationship persists"*.
⟦C⟧ **Retraction (Withdrawal)** — *"Voluntary removal of a claim by its claimant"*; guard: *"Claimant must
be authorized to withdraw"*; event `KnowledgeWithdrawn`; and decisively: *"**Withdrawal is a property of
the claim, not a relationship** to another claim."*
⟦C⟧ **Contestation** — *"a relationship between a challenging claim and a challenged claim"*; event
`KnowledgeContested`; *"relationship persists until resolution"*.
⟦C⟧ **Reconciliation** — the fourth (per the section title).

⟦INFERENCE⟧ **This partially settles `S1-F013`'s blocker in a different way from `S1-F014`.** F014 said
all four are *relationships* projected into states. Here **three are relationships but withdrawal is a
property** — an explicit asymmetry F014 does not make. ⚠ Two documents seven minutes apart, disagreeing
on whether withdrawal is relational. **Recorded, unresolved.**

---

## DDD interpretation

- `Identity` → **CANDIDATE IMMUTABLE VALUE OBJECT** (⟦C⟧ *"NEVER"* changes — the corpus's strongest
  invariance statement so far).
- `Supersession` / `Contestation` / `Reconciliation` → **CANDIDATE RELATIONSHIPS** with guards.
- `Withdrawal` → **CANDIDATE PROPERTY** of the claim, not a relationship.
- `Amendment` → **CANDIDATE LIFECYCLE TRANSITION** (new).
- `Content` → **CANDIDATE member of undetermined mutability**.
- History → **CANDIDATE record of the sequence of changes**, whose *contents* depend on the content-
  mutability answer.

---

## Relationship to previous Session-1 findings

- **`S1-F013`** — **incompatible on mutability** (Model A: five immutable members; here: four mutable).
- **`S1-F014`** — **disagrees on withdrawal** (relationship vs property) while agreeing the other three
  are relational.
- **`S1-F010`** — supplies guards and events F010 lacked; adds `Amendment`, which F010's state set omits.
- **`S1-F001`** — still not addressed: this table concerns *claim* mutability, not **authority validity
  over time**. That measured gap remains untouched by every document processed so far.
- **`S1-F009` / `F011` / `F012`** — the twelve-question sequence implies all three answered the boundary
  question prematurely.

---

## Classification, confidence, open questions

**Type:** METHOD (sequence) · MODEL (change table, four relations) · QUESTION (content mutability,
marked UNRESOLVED by the source).
**Confidence:** high on quotations; **medium-low** evidential weight (model-generated, no citations,
questions answered twice in one file).
**Open questions:** **can content change under a persisting identity?** (source-marked UNRESOLVED) · is
withdrawal a property or a relationship (F014 vs F015)? · which members are immutable (F013 vs F015)? ·
is `Amendment` a distinct transition or a form of supersession?

**Status:** OPEN. Nothing adjudicated; three inter-document disagreements left standing.
