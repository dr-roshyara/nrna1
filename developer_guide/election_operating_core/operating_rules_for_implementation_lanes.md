# Operating rules for implementation lanes — Election Operating Core

**Source:** PO/ARB, 2026-08-17 (registered verbatim in substance) · **Binds:** every lane, human or AI, implementing in this subsystem — not only `EM-IMPL-002`.
**Role framing, as given:** *"Your responsibility is not only to make tests pass. Your responsibility is to preserve bounded-context ownership, domain sovereignty, and architectural integrity."*

## Rule 1 — Never implement an unresolved ownership question

Before modifying production code, ask: **"Does this change DECIDE meaning, or only IMPLEMENT an already-decided meaning?"** If it decides meaning: **STOP · create architecture evidence · request the decision.**

## Rule 2 — Distinguish absence from representation

⛔ Never reason `null → exception`. ✅ Reason: **missing reference → what invariant does it represent? → who owns that invariant? → what representation follows?**

## Rule 3 — Application-layer limitation

**MAY:** coordinate · call domain operations · record outcomes · translate approved results.
**MUST NOT:** classify domain meaning · invent lifecycle states · calculate constitutional rules · reconstruct causal provenance.

## Rule 4 — Protocol limitation

Protocol/event history is **evidence of what happened**, never **authority explaining why it happened**. Before using protocol data ask: *does this answer "what happened?" or "why is this valid?"* — **only the first belongs to the protocol.**

## Rule 5 — Frozen-domain discipline

If a domain change appears necessary: ⛔ do not modify the domain. ✅ **Create a proposal → explain invariant ownership → request authorization → create a dedicated domain RED slice.**

## Rule 6 — RED integrity

A RED test **must** detect a real architectural violation, and **must not** choose the final design or encode an implementation preference.
✅ **Good RED:** *absence must have explicit meaning.* ⛔ **Bad RED:** *absence must throw `ExceptionX`.*

## Rule 7 — Pre-GREEN checklist *(all boxes required)*

```
[ ] ADR decision exists
[ ] Ownership is explicit
[ ] Authorization exists
[ ] No hidden domain decision in application code
[ ] Tests express approved semantics
```

---

## First application of Rule 7 — the current state, 2026-08-17

| Box | Now |
|---|---|
| ADR decision exists | ❌ **both `§6` blocks read `LEFT BLANK`** |
| Ownership is explicit | ❌ absence meaning and provenance owner both undecided |
| Authorization exists | ❌ no normalization or domain slice authorized |
| No hidden domain decision in application code | ⚠️ **that is exactly the open defect** — three handlers hold three different absence interpretations |
| Tests express approved semantics | ⚠️ the absence pin expresses the *requirement for a decision*, not an approved semantic |

> **Rule 7 fails at every box. That is why the freeze holds — and it is the instrument saying so, not a preference.**

**Currently barred until both ADRs are signed:** handler implementation · exception normalization · repository changes · domain changes · provenance workaround · protocol reads.

**After the decisions, the ordered sequence:** extract the approved invariants → determine the owning bounded context → create the authorized implementation slice → update RED if required → implement **only** the approved meaning → re-run the frozen domain suite → continue the GREEN sequence.

**Traceability.** `ADR_20260817_2145` · `ADR_20260817_2300` · architecture hold `bf6f5141` · the two standing rules (*"Application consumes established constitutional values…"* adopted; *"Protocol records decisions; it does not become the source of causal authority"* candidate).
