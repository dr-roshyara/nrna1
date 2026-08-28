# S1-F003 · Two Phase 1 Kernel member-lists, 36 minutes apart, materially different

**Finding class:** DIVERGENCE between two project proposals (not a contradiction between phases)
**Status:** OPEN · UNRESOLVED · not adjudicated
**Lenses:** DDD · Zero · Boundary · Authority · Evidence

---

## Sources and provenance

| | Document A | Document B |
|---|---|---|
| **File** | `brainstorming/20260821-2032-how-to-change-eks-into-knowledgeos-kernel.md` | `brainstorming/20260821-2108-how-to-integrate-current-eks-into-knowledgeos-kernel.md` |
| **Provenance** | `P1` ORIGINAL_PROJECT | `P1` ORIGINAL_PROJECT, self-declared **revision** of A |
| **Phase / date** | 1 · 2026-08-21 **20:32** | 1 · 2026-08-21 **21:08** |
| **Trigger** | reasons from the newer baseline | ⟦C⟧ *"This **older** baseline materially changes the picture, and I would **revise my previous integration assessment**"* |

⟦I⟧ B is not an independent arrival — it explicitly revises A, **36 minutes later**, after consulting an
*older* baseline document. Provenance is the same author-context; the divergence is a change of mind
inside one evening, driven by new evidence about the existing runtime.

---

## The divergence

**A · Kernel = a deterministic runtime for governed knowledge state, authority, evidence, lifecycle, provenance**

```
authority mechanics · generic evidence mechanics · lifecycle · provenance
```

**B · Kernel = observation/evaluation mechanics with rules pushed out to a port**

```
Observation · Evidence · Rule evaluation protocol · Assessment · Result · Provenance
```

| Member | A | B |
|---|---|---|
| Provenance | ✔ | ✔ |
| Evidence | ✔ | ✔ |
| **Authority mechanics** | **✔** | **absent** |
| **Lifecycle** | **✔** | **absent** |
| **Observation** | absent | **✔** |
| **Assessment · Result · Rule-evaluation protocol** | absent | **✔** |

⟦I⟧ **Only Evidence and Provenance survive both lists.** Authority — which A called *"exactly the kind
of invariant a kernel can protect"* — is not in B. Under the Zero lens, the intersection of the two
earliest Phase 1 Kernel proposals is just `{Evidence, Provenance}`.

> ⚠ **CAUTION — the intersection is not a conclusion.** `{Evidence, Provenance}` is **the intersection
> of two historical proposals**, nothing more. It must **not** be read as *"therefore these are the
> Kernel"*, nor as a minimality result. Two proposals agreeing is not evidence of necessity; both could
> be wrong, and neither justified its list by an atomicity argument. Recorded as a historical fact
> about the proposals, not as a finding about the Kernel.

> ⚠ **The primary finding of this artifact is not `ChangeSet` and not the intersection.** It is that
> **before any philosophical or book-derived research existed, the project itself produced materially
> different answers to "what belongs in the Kernel?" within 36 minutes** — resting on two different
> implicit principles: A protects *governed state, authority, evidence, lifecycle, provenance*;
> B provides *observation/evaluation mechanics with domain rules externalised through a port*.

---

## Two new Kernel candidates from B

**ChangeSet** ⟦C⟧ *"may be a particularly important kernel abstraction"* — it *"deliberately separates
event detection **from** observation execution"*, and ⟦C⟧ the runtime *"doesn't care whether the change
originated from VS Code, Claude Code, Git, or another tool."*
⟦C⟧ *"Now we have something that genuinely looks like a reusable KnowledgeOS kernel/platform primitive."*
⟦I⟧ This is an **implemented** mechanism, not a proposal — evidence priority 1–3, not 7.
> ⚠ **`implemented ≠ Kernel-authoritative`.** Implementation raises the *evidence class*; it confers no
> architectural standing. A mechanism can be real, useful, source-agnostic — and still belong outside
> the boundary. Nothing follows about membership.

**Rule Port** ⟦C⟧ the kernel holds the port; EKS and PKS supply the rules:
`Kernel → Observation → Rule Port → {EKS engineering rules | PKS product rules}`.
⟦C⟧ Concrete rules (LCOM4, test-presence, architecture, DDD) and engineering recommendation are
explicitly **outside**: *"the Recommendation Engine probably does NOT belong wholesale in the kernel."*
⟦I⟧ A port that admits pluggable evaluators while the kernel keeps the protocol is structurally the
same move ⟦L⟧ v1.1 makes with the **Verification Port** and its mechanism altitude (§16). Reached here
in Phase 1, one day earlier, independently. **Classification: CONSISTENT.**
> ⚠ **A convergence to accumulate, not a ruling.** Two independent arrivals at a port-shaped solution
> is evidence worth counting; it is not confirmation that either is correct, and it decides nothing
> about `W:C-7` or the boundary.

---

## Method finding

⟦C⟧ B corrects the framing: do **not** ask *"What parts of EKS can we move into the KnowledgeOS
kernel?"* but *"**What is the actual relationship** between the existing EKS runtime, the existing AI
Engineering Platform, PKS, and the proposed KnowledgeOS kernel?"* — because ⟦C⟧ *"there are already
multiple architectural mechanisms that look like kernel primitives."*

⟦C⟧ Also: *"`.claude` definitely should not become the kernel"* · *"The correct next phase is therefore
NOT EP-01."*

⟦I⟧ The corpus was already resisting the move this programme was later built to prevent: deriving the
Kernel by relocating existing code rather than by identifying what must be protected.

---

## Why it matters

⟦I⟧ Session 2 is adjudicating a Kernel whose membership question (`W:C-7`, boundary) has been treated
as a Phase 2 problem. **The two earliest Phase 1 proposals do not agree with each other**, and their
intersection is two members. That is evidence about how underdetermined the membership question was
*before* any philosophical corpus existed — which is a different kind of pressure than a book-derived
challenge.

⟦I⟧ It also gives KCON-012 (atomicity-not-relatedness) something to bite on: neither A nor B justifies
its member list by an atomicity argument. Both list capabilities.

---

## Possible relevance

- **Boundary / `W:C-7`** — membership divergence at origin.
- **`W:C-3`** — authority present in A, absent in B, unexplained.
- **ChangeSet** — a CANDIDATE VALUE OBJECT or DOMAIN EVENT source; *implemented*, so it carries
  higher evidence weight than most Kernel candidates in the corpus.
- **Rule Port** — CANDIDATE PORT; ⟦L⟧ **CONSISTENT** with the Verification-Port pattern.

**Not adjudicated. No member list is preferred here.**

---

## Status

**OPEN · UNRESOLVED.** Recorded as a divergence, per the rule that contradictions are not resolved
during extraction. Related: `S1-F001` (authority temporal gap — same evening, same author-context),
ledger KCON-015/016/017, Kernel candidate matrix.
