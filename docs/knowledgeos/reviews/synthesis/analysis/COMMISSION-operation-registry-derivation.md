# COMMISSION — CANONICAL OPERATION REGISTRY DERIVATION

**Authority:** HPA ruling **GN-79** (2026-08-31), Option 1 of
`DRAFT-HPA-RULING-operation-registry.md`. **Commissioned lane: ARCHITECTURE / THEORY.**
**Status: ISSUED · NOT STARTED.** This commission authorizes a *derivation*, not a ratification:
the deliverable returns for a **separate ratification act**.

> **The operation registry is NOT ESTABLISHED. Operations and Transformations remain
> `IMPLEMENTATION BLOCKER — CANONICAL SEMANTICS NOT ESTABLISHED` until that later act.**

---

## 1 · Objective

Produce a canonical operation registry for the KnowledgeOS state, fit for ratification, such that
an engineer can implement state-changing behaviour **without making architectural decisions that
belong to the HPA.**

## 2 · Why this commission exists (findings it answers)

The governed surface — v0.2 (AUTHORIZED), v0.1, FA-1…FA-9 (RATIFIED), the repository architecture
corpus — **defines zero operations** and **no pre/post-condition specification**. It ratifies
legality constraints (I-12's covering relation, A6's authority-crossing) over a transition relation
that is never defined. Nine capabilities are canonically *required*; none is canonically *defined*.
Candidates exist outside the governed surface, **they do not agree**, and the criterion that would
discriminate them **has never been executed**.

## 3 · Scope — four tasks

**T-1 · Membership.** Determine the operation set. Enumerate it, state the inclusion criterion,
and state explicitly whether the set is claimed **closed**.

**T-2 · Execute the minimality/necessity test.** Run the criterion over the candidate set and
record the artifact: inputs, procedure, outcomes. **This is the task that has never been performed
by anyone.** If minimality cannot be shown, deliver anyway and mark it
`MINIMALITY NOT ESTABLISHED` — a truthful negative is an acceptable outcome; an unproven positive
is not.

**T-3 · Reconcile the two known conflicts.** The two operations recorded as unreconciled against
the executed algebra must be resolved or explicitly excluded with reasons.

**T-4 · Per-operation contracts.** For every member, all eleven fields, none left implicit:
`name · purpose · input state · preconditions · state transition · postconditions · invariant
obligations · evidence effect · authority effect · replay semantics · failure semantics`.

## 4 · Acceptance standard (binding — Part C of GN-79)

| # | Requirement |
|---|---|
| C-1 | Enumerated membership + inclusion criterion + explicit closure claim (or explicit refusal to claim closure) |
| C-2 | The minimality test **executed**, with its artifact preserved |
| C-3 | The two known conflicts reconciled or excluded with reasons |
| C-4 | The eleven-field contract, per member |
| C-5 | The three prerequisites **delivered with the contracts or explicitly deferred, stated either way**: a **closed invariant register** · **typed rejection semantics** · a **state identity and equality rule** |
| C-6 | Conformance with what is already ratified — I-12's covering relation · **A6's asymmetry (an authority act crosses the boundary; evidence never does)** · I-11's policy-change route · I-5/I-6's evidence-composition laws. These are constraints on the answer, not part of the question |
| C-7 | Provenance per member, with its evidence class. A member whose source class cannot be named is a proposal, not a member |
| C-8 | **Independent review before the ratification act** — by a party that did not author the deliverable |

**On C-5:** the contracts cannot be completed without these three. "Invariant obligations" has no
codomain without a closed register; "failure semantics" presupposes a rejection vocabulary; a
postcondition is undecidable without state equality. The lane may defer any of them, but must say
so and say what that leaves unfillable.

## 5 · Explicit non-authorizations

This commission does **not** authorize: adoption of any existing candidate **by default** ·
promotion of any verification-lane construct into canon · resolution of **GC-1 / I-11** ·
definition of Σ, `Q_t`, identity, equality, replay, measurement or the Evidence object beyond what
T-4/C-5 strictly require · selection of an evidence-aggregation operator (**OQ-3 stays open by
ruling**) · movement of any of **OQ-1…OQ-12** · adjudication of the self-attested authority strings
in the research track · any modification of v0.2, the Final Architecture, Edition 1, Part II or
Part III · any book prose · any declaration that the theory is complete or incomplete.

## 6 · Deliverables

1. The registry: membership + criterion + closure claim.
2. The executed minimality-test artifact (T-2).
3. The conflict reconciliation (T-3).
4. Per-operation contracts (T-4).
5. A statement on each C-5 prerequisite: delivered, or deferred with consequences named.
6. A provenance table (C-7).
7. An independent review record (C-8).

## 7 · Stop conditions

Stop and return to the HPA if: membership cannot be determined without a normative choice (name the
choice; do not make it) · the minimality test returns a negative (deliver it as a negative) · any
task would require resolving GC-1 · any task would require modifying a ratified artifact · the
three C-5 prerequisites prove to be the real blocker rather than a dependency.

## 8 · On delivery

Deliverable → **independent review (C-8)** → **a separate HPA ratification act**. Only then does
the registry become canon, and only then may the implementation contract's Operations and
Transformations sections be written. **Delivery is not adoption.**

## 9 · Sequence

```
1 canonical state            ratified (partial: named, not typed)
2 canonical operations       ◀── THIS COMMISSION (derivation only)
3 legal transformations      blocked behind 2
4 authority / evidence       partial; blocked in part behind 2
5 replay semantics           blocked behind 2 and behind identity/equality
6 ratify the contract        a later, separate act
7 implementation spec + code only after 6
```

## 10 · ⚠ Executor not yet assigned — a question for the HPA

GN-79 commissions the **architecture/theory lane**. **This book-production session is not that
lane** (GN-71: not theory-discovery, not theory-verification), and it has not begun any part of
T-1…T-4. Who executes this commission is a separate decision:

- assign it to the theory/verification lane that already holds the candidate analyses; **or**
- authorize an independent fresh-context derivation agent under this commission; **or**
- explicitly authorize this session to execute it — which would be a **lane change** and needs to
  be stated as one, not inferred.

**Until an executor is assigned, this commission is ISSUED and NOT STARTED.** The book lane's
remaining role is to record the act, keep the blocker visible, and document the outcome when it
arrives through the synchronization gate.
