# S2-F011 · Altitude test on determinism — two results, one of which declines the easier reconciliation

**Class:** CHALLENGE + OPEN QUESTION
**Status:** OPEN · review record only · **`W:C-2` / C-K3 determinism remains OPEN and is NOT adjudicated here**

---

| | |
|---|---|
| **Source Session-1 artifact** | `session1/S1-F004`, Findings 2 and 3 |
| **Commissioned question** | is the determinism contradiction *"genuinely at the same architectural altitude"*? |
| **Lens** | Boundary · Vocabulary · Justification |

---

## RESULT 1 · The A/B determinism pair **is** at the same altitude — the test does not dissolve it

| | Claim | Altitude |
|---|---|---|
| **A** (`20260821-2032`) | the kernel is *"closer to: a **deterministic runtime**"* | **MECHANISM / implementation** |
| **B** (`20260821-2351`) | *"knowledge systems **lack**: deterministic execution"* | **MECHANISM / execution** |

**Both speak about mechanism. Both concern execution behaviour. The altitude test therefore gives no relief here.**

This is recorded as a **negative result on purpose.** An altitude reconciliation was available and would have been convenient; it does not hold on the evidence. `S2-F008`'s **modal** objection — a proposal cannot be contradicted by an observation about existing systems — stands on its own and is unaffected, but it is a different argument and should not be conflated with an altitude argument.

**Consequence: Session 1's decision to leave the contradiction UNRESOLVED survives this test.** So does the HPA's direction to keep determinism OPEN. Nothing here supports closing it.

## RESULT 2 · Determinism and *"epistemic accountability over time"* are **not** at the same altitude — and the external review may have treated them as if they were

| | Claim | Altitude |
|---|---|---|
| determinism | *"deterministic runtime"* | **MECHANISM / implementation** |
| the trustworthy core | *"preserving **epistemic accountability over time**, not retrieval performance"* | **PURPOSE / domain responsibility** |

**These are orthogonal, not competing.** A runtime could be deterministic *and* have accountability-over-time as its domain responsibility; the two answer different questions — *how does it behave?* and *what is it for?*

**The concern is what the external review appears to do with them.** Read together, its position takes the shape *"not deterministic execution — rather, epistemic accountability over time"*, i.e. **a negative at mechanism altitude and a positive at purpose altitude offered as one verdict.** If that is the review's structure, it is an altitude conflation in the source — and `S1-F004` **inherits it** by presenting Finding 2 (the negative) and Finding 3 (the positive) as the two halves of one external assessment.

## WHY IT MATTERS

Session 1's own findings are correctly *separated* — F2 and F3 are distinct entries, and Session 1 makes no claim that they compete. **The inheritance is structural, not asserted:** placing a mechanism-level rejection and a purpose-level proposal adjacently, both sourced to one review, invites a later reader to treat them as alternatives.

That matters because the two carry very different consequences. *"Determinism is unprecedented in this class of system"* is a burden on a design proposal. *"The core is accountability over time"* is a claim about purpose that **neither requires nor forbids determinism.** Fusing them would let a purpose claim silently dispose of a mechanism question — and the mechanism question is `W:C-2`, which is live and unruled.

## WHAT THIS DOES NOT ESTABLISH

- **Nothing about whether determinism should be a Kernel property.** Result 1 explicitly refuses the reconciliation that would have pointed that way.
- **Nothing about whether accountability-over-time is the core.** Its evidential standing is challenged separately and on different grounds in `S2-F012`.
- **No adjudication.** Neither result may be cited in `W:C-2`; the corpus is sealed for that purpose.

## POSSIBLE IMPACT

Research framing. Suggested for Session 1: record F2 and F3 with an explicit note that they sit at **different altitudes and are not alternatives**, so that the review's *"not X, but Y"* shape is not preserved as though X and Y were rivals.

## PROVENANCE

Derived solely from `session1/S1-F004` and its quotations. **No corpus document read** — including the external review, so its actual argumentative structure is inferred from S1-F004's quotations and is recorded as a reading, not as verified.

## STATUS

**OPEN.** Result 1 is a negative finding (no reconciliation available). Result 2 is a challenge to a structural inheritance, not to any Session-1 claim. Session-1 artifacts not modified.
