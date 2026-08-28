# S1-F010 · Admission is the **first transition of a lifecycle**, not the core domain act — and the document proposes a rival state set including `WITHDRAWN`

**Finding ID:** S1-F010
**Finding class:** POSITION CHANGE on the core domain act + rival epistemic state set + explicit `WITHDRAWN` transition
**Status:** OPEN
**Lenses:** Temporal · Identity · DDD · Evidence · Zero

---

## Source and provenance

| | |
|---|---|
| **Source document** | `brainstorming/kernel/20260823-111647-epistemic-lifecycle-domain-discovery-admission-as-first-transition.md` (1,081 lines) |
| **Document type** | domain discovery — state-set derivation from three separate theory bases |
| **Provenance** | **`P4` MODEL_INTERPRETATION / structured brainstorming** (same register as `110950`: numbered sections, comparison tables, no external citations, no HPA act) ⟦PROVENANCE: partly UNCERTAIN⟧ |
| **Date / phase** | 2026-08-23 **11:16** · Phase 2 — **7 minutes after** `110950` |
| **Relationship stated** | ⟦C⟧ opens by asking *"What is the complete KnowledgeOS epistemic lifecycle, **independently of the Kernel**?"* — deliberately setting the Kernel aside |

---

## Finding 1 · A direct position change on the core domain act

⟦C⟧ The framing question, §1: *"Does it simply enter and persist?"* — with three candidate answers for
what Admission is: *"**The core domain act**"* · *"**The first transition of a larger lifecycle**"* ·
*"**A subordinate act**"*.

⟦C⟧ §7 (*"Is admission the core domain act or the first transition?"*) concludes:

> ⟦C⟧ **"Admission is the first transition of the epistemic lifecycle."**

⟦C⟧ Supporting reasoning: *"Admission is the transition from PROPOSED to ADMITTED. But after admission,
**many things can happen**."*

⟦INFERENCE⟧ **This reverses `S1-F009`, written seven minutes earlier**, which selected Admission as
*the core domain act* against six alternatives. Two documents, same day, same register, opposite
conclusions on the same question. ⟦INFERENCE⟧ Neither cites the other. **Recorded as a position change,
not reconciled** — and it matters because F009's equilibrium criterion is scoped *"during admission"*.
If admission is only the first of many transitions, a criterion bounded by admission cannot govern the
whole lifecycle.

---

## Finding 2 · Three independent state-set derivations ⟦MODEL⟧

The document derives candidate states **three times from three theory bases**, then compares:

**From Epistemic Theory** ⟦C⟧ `Proposed · Admitted · Contested · Superseded · Reconciled · Withdrawn ·
Rejected`
**From Constitutional Governance** ⟦C⟧ `Pending Review · Admitted · Under Challenge · Superseded ·
Reconciled · Rejected`
**From Evidence/Justification Theory** — including ⟦C⟧ `INSUFFICIENT_EVIDENCE`

⟦INFERENCE⟧ Method note worth preserving: deriving the same structure from three independent bases and
comparing is the strongest derivation method used anywhere in the corpus so far. `Admitted`,
`Superseded`, `Reconciled` and `Rejected` appear in **two of three** bases.

### ⟦L⟧ Comparison with the formal state set — comparison target only

⟦L⟧ v1.1 §9 holds **seven** states — `VALIDATED · QUESTIONABLE · CONFLICTED · UNKNOWN · ABSENT · FALSE ·
REJECTED` — and states *"No eighth state is introduced."*

| This document | ⟦L⟧ v1.1 counterpart |
|---|---|
| `Rejected` | `REJECTED` — **present** |
| `Contested` / `Under Challenge` | ≈ `CONFLICTED` — **plausible synonym**, not verified |
| `Admitted` | ≈ the admitted object itself; not a state in v1.1 |
| **`Withdrawn`** | ⚠ **no counterpart** |
| **`Superseded`** | ⚠ no state; v1.1 has the *event* `KnowledgeSuperseded` |
| **`Reconciled`** | ⚠ no state; v1.1 has *ContradictionResolved* |
| **`Proposed` / `Pending Review`** | ⚠ no counterpart — pre-admission is outside v1.1's state model |
| **`INSUFFICIENT_EVIDENCE`** | ⚠ no counterpart |

⟦INFERENCE⟧ **This is a rival state set, not an extension.** It contains at least four states with no
v1.1 counterpart and it models *pre-admission* states that v1.1 places outside the domain. ⚠ It is
**not** an eighth-state proposal against v1.1 — it is an independent derivation that never references
v1.1. **No gap is claimed and nothing is adjudicated.**

---

## Finding 3 · `WITHDRAWN` is given a complete transition specification ⟦MODEL⟧

⟦C⟧ **ADMITTED → WITHDRAWN (Withdrawal)**
- *"**What happens**: The claimant voluntarily retracts the claim."*
- *"**What changes**: The claim's state changes from ADMITTED to WITHDRAWN. It is no longer asserted."*
- *"**What is established**: The claim is no longer part of KnowledgeOS (**though its history
  remains**)."*
- *"**Domain event**: `KnowledgeWithdrawn`"*
⟦C⟧ Guard, from the transition table: *"Claimant authorized, no dependency violation."*

⟦C⟧ A second, distinct de-admission path: **ADMITTED → INSUFFICIENT_EVIDENCE (Evidence Invalidation)**,
event `KnowledgeEvidenceInvalidated` — *"Evidence supporting the claim is invalidated or removed."*

⟦INFERENCE⟧ **Directly relevant to unruled `W:C-15`** (retraction/withdrawal representation), and it
answers the question the workbook leaves open — *state, event, or disappearance?* — with **all three at
once**: a state (`WITHDRAWN`), an event (`KnowledgeWithdrawn`), and *"no longer part of KnowledgeOS"*
while *"its history remains"*.
⟦INFERENCE⟧ It also **separates two causes of de-admission** that the corpus elsewhere merges: *voluntary
retraction by an authorised claimant* vs *evidence invalidation*. Different actor, different guard,
different event. ⚠ **Recorded as a candidate distinction. `W:C-15` is Session 2's to rule; nothing is
proposed here.**

⟦INFERENCE⟧ Note the guard introduces `Claimant` as an authorised actor and a **dependency** notion
(*"no dependency violation"*) — neither appears in any earlier Session-1 finding.

---

## DDD interpretation

- `Proposed · Admitted · Contested · Superseded · Reconciled · Withdrawn · Rejected ·
  INSUFFICIENT_EVIDENCE` → **CANDIDATE STATES** (a rival set).
- `KnowledgeWithdrawn` · `KnowledgeEvidenceInvalidated` → **CANDIDATE DOMAIN EVENTS**.
- `Claimant` → **CANDIDATE ACTOR/ROLE** with an authorisation guard.
- *"no dependency violation"* → **CANDIDATE INVARIANT** (inter-claim dependency).
- Admission → **CANDIDATE first transition**, demoted from core act.
- ⟦INFERENCE⟧ *"though its history remains"* → history as **separate from state**, which is
  `S1-F007`'s conflation 6 (`History ≠ Provenance ≠ Audit Log`) touched from the lifecycle side.

---

## Relationship to previous Session-1 findings

- **`S1-F009`** — **reversed** on the core domain act (7 minutes apart, neither citing the other), and
  its equilibrium criterion is scoped to admission only.
- **`S1-F007`** — supports conflation 6: state and history are distinguished here in practice.
- **`S1-F008`** — the nine-capability map has *history recording* but no lifecycle beyond admission;
  this document supplies the lifecycle the map lacks.
- **`S1-F001`** — first corpus material that could bear on temporal validity, but note: `WITHDRAWN`
  concerns *the claim's* validity, **not** the *authority's* validity over time. **Different question,
  still open.**

---

## Classification, confidence, open questions

**Type:** MODEL (state set, transitions, events) + POSITION CHANGE (core act). **Not** a definition of
Knowledge; **not** a Kernel proposal — it explicitly reasons *"independently of the Kernel"*.
**Confidence:** high on quotations; **medium** on provenance; the v1.1 mapping is INFERENCE and the
`Contested ≈ CONFLICTED` equivalence is **unverified** (possible synonym only).
**Open questions:** Is Admission the core act or the first transition (F009 vs F010)? · Are `WITHDRAWN`
and `INSUFFICIENT_EVIDENCE` two states or one with two causes? · What is the *dependency* relation the
withdrawal guard invokes? · If history remains after withdrawal, what does *"no longer part of
KnowledgeOS"* mean precisely?

**Status:** OPEN. Rival state set recorded; no state proposed for adoption; `W:C-15` untouched.
