# S2-F007 · The A/B divergence may be an **ill-typed comparison**, not a boundary divergence

**Class:** CHALLENGE
**Status:** OPEN · review record only · **not resolved, not adjudicated**

---

| | |
|---|---|
| **Source Session-1 artifact** | `session1/S1-F003-kernel-member-candidates-diverge.md` |
| **Commissioned question** | is the A/B divergence a genuine **Kernel-boundary** divergence, or a difference in **architectural altitude / capability decomposition**? |
| **Lens** | DDD · Zero · Boundary · Vocabulary |

---

## FINDING

S1-F003 computes a set intersection over the two member lists and reports `{Evidence, Provenance}`.

**The two lists appear to enumerate different *kinds* of thing. If so, the intersection is not merely inconclusive — it is ill-typed, and `{Evidence, Provenance}` is not an intersection at all.**

| | A's members | Kind |
|---|---|---|
| authority mechanics · generic evidence mechanics · lifecycle · provenance | **protected concerns** — *what must be preserved* | invariant families |

| | B's members | Kind |
|---|---|---|
| Observation · Rule evaluation protocol · Assessment · Result | **pipeline stages** — *what the runtime does, in order* | processing steps |
| Evidence · Provenance | protected concerns | invariant families |

**`Evidence` and `Provenance` survive both lists because they are the only two items appearing as *concerns* in both — i.e. the intersection marks where the two taxonomies coincide in kind, not where the proposals agree on membership.**

## EVIDENCE

Internal to S1-F003, no corpus read required:

1. **Self-description.** A is quoted as *"a deterministic runtime for governed knowledge **state**"* — a *what-is-protected* framing. B is quoted as *"observation/evaluation **mechanics**"* — a *how-it-runs* framing.
2. **B's list is a sequence.** `Observation → Rule evaluation protocol → Assessment → Result` reads as a pipeline; A's list has no ordering and none is implied.
3. **B's characteristic move is decomposition, not exclusion.** B *"deliberately separates event detection from observation execution"* and pushes rules to a port. Separating protocol from evaluator is a **capability-decomposition** act; it makes no claim about which invariants matter.
4. **B's own boundary statements are about mechanisms, not concerns** — *"the Recommendation Engine probably does NOT belong wholesale in the kernel"*, *"`.claude` definitely should not become the kernel"*.

## WHY IT MATTERS — the Zero-lens objection

S1-F003's sharpest observation is that **Authority is in A and absent from B**, and it flags this as unexplained divergence, noting A called authority *"exactly the kind of invariant a kernel can protect."*

> **But absence from a pipeline enumeration is not exclusion from a protection set.**

B never says authority does not belong; B is answering a question in which authority does not appear as a *stage*. **Reading B's silence as B's rejection is precisely the absence→decision collapse the Zero lens exists to forbid** — and it is the same discipline S1-F001's own ⟨Z-1⟩ citation invokes.

This does not make S1-F003 wrong. It means the artifact may be measuring **two answers to two different questions** and reporting the result as disagreement about one.

## WHAT THIS DOES NOT ESTABLISH

Deliberately not resolved, per commission:

- It does **not** follow that A and B agree. They may still diverge on membership; that cannot be settled from lists of different kinds.
- It does **not** follow that the divergence is unimportant. *That the project produced two differently-typed answers within 36 minutes* is arguably a stronger finding than membership disagreement — it suggests the **question itself** was unstable, not just the answer.
- It does **not** touch `W:C-7` or any boundary question. **No adjudication.**

## POSSIBLE IMPACT

Research framing. If the finding holds, S1-F003's primary claim strengthens and changes shape: from *"two proposals disagreed about members"* to ***"two proposals were not answering the same question"*** — which is a deeper form of underdetermination and needs no intersection arithmetic to state.

Suggested remedy for Session 1 to accept or reject: record the two lists as **differently-typed enumerations**, retire the intersection, and keep the 36-minute divergence as the finding.

## PROVENANCE

Derived solely from `session1/S1-F003`, using its own quotations. **Neither source document A nor B was read** — this review is restricted to `session1/`, which is also why the typing claim is offered as a reading of S1-F003's evidence rather than as verified from source.

## STATUS

**OPEN — CHALLENGE to the comparison's type-safety, not to the divergence's existence.** Session-1's document is not modified.
