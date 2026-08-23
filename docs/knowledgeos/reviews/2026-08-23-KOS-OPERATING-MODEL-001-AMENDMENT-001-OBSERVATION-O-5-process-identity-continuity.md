# Observation `O-5` — **process context continuity ≠ workflow session identity continuity**

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` *(where it was discovered — its reach is wider)*
**Document type:** governance **observation**. Not a finding, not a defect ruling, not a proposal, not a decision.
**Status:** **RECORDED · NOT PROMOTED** — `ES-006.1`, single occurrence; the methodology is **FROZEN**.
**Date:** 2026-08-23
**Recorded by:** Governance-recording process — `claude-code-session:930c65a4-ff37-4a8a-b165-24150606b539`
*(self-declared, not attestable — `INV-ATTR-1/2`, `G-2`. `P-3` responsibility; **holds no lane**; own bootstrap returns `UNRESOLVED · operable: false`. Recording ≠ appointing · deciding · adopting · authorizing.)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **Recorded as a separate observation on the PO/ARB's explicit direction:** *"This deserves a separate governance observation."* It is filed on its own so it is not buried inside a repair record, and so that it can be picked up later on its own evidence rather than on this slice's.

---

## 1 · The observation, stated once

```
process context continuity
        ≠
workflow session identity continuity
```

**A Claude restart can produce a different `CLAUDE_CODE_SESSION_ID` while retaining essentially the same working conversation and context.**

Therefore: **`CLAUDE_CODE_SESSION_ID` alone is not a durable participant identity across session restarts.** It identifies a *runtime process instance*. It does not identify a *participant in a governed workflow* over time.

This is the **temporal extension of `INV-ATTR-1/2`**, not a new subject. `INV-ATTR-1/2` already establishes that a process identity is **self-declared and not attestable**. `O-5` adds that it is also **not stable**: the same participating context can, without any deception and without any act by anyone, report a different identifier after a restart.

## 2 · Provenance of the claim — kept separate from what was measured

| | |
|---|---|
| **Reported by the PO/ARB (human), 2026-08-23** | The governance process that had authored `ASD-001`, scoped `REPAIR-001`, recorded the `CONTINUATION` and the grant, and written the candidate-declaration prompt, **restarted and reported the runtime identity `e40f3fd0…`**, while its record signature throughout is `5928b9f9-b4d5-46e9-8c71-c295dace18f8`. |
| **Measured by this process** | `e40f3fd0` occurs **zero times** anywhere under `docs/` or `.claude/`, and zero times in history. `5928b9f9` occurs in **15 files**. This process's own id, `930c65a4`, occurs **zero times**. |
| **NOT measured, and not claimed** | This process **cannot** observe another process's runtime environment and did **not** verify the restart itself. The restart is recorded as the **human's report**; only the record-side asymmetry above is this process's own evidence. |

The asymmetry is the whole observation: **the record attributes participation to the old identifier, while the runtime reports the new one.** Nothing in the mechanism connects the two, and nothing should be silently invented to connect them.

## 3 · Why it matters — the failure runs in two directions, and they are not symmetric

**Direction A — false independence (the dangerous one; the one that actually materialised).**
A restarted process presents an identifier that appears on no bar list. Every UUID-keyed identity bar passes **mechanically**, and `AST-018 appoint` could have accepted `e40f3fd0…` as a fresh implementation candidate — while that process carried the complete prior context of the work it would be appointed to repair, including having **scoped the very slice**. Nothing malicious is required. **A restart is enough.** The independence guarantee would have been destroyed *retrospectively*, and the record would have looked clean.

**Direction B — lost continuity (the tolerable one).**
A legitimately continuing process can no longer demonstrate that it is the registered lane holder, so it presents as unattributed. This process is a live instance of exactly that: `session-bootstrap.php` returns **`UNRESOLVED · operable: false`** for `930c65a4` — *"no governed lane is attributable to this process… absence is not permission."* The cost is a re-establishment step. The guarantee is intact.

**The rule below deliberately errs toward Direction B.** A wrongly-barred process needs one governed act to be re-established. A wrongly-admitted one cannot be repaired at all, because the check it performed was never independent and no later act makes it so.

## 4 · The corollary that carries the actual load

> **Independence must be declared and assessed on PRIOR PARTICIPATION, not on identity difference.**
> A different UUID is **necessary but not sufficient** evidence of independence.

A bar list of UUIDs is a *convenience aid* for a candidate's self-assessment. It is not the bar. The bar is participation, and only the candidate can disclose it — which is why the candidate-declaration step asks for `PRIOR PARTICIPATION` **in full**, and why concealment, not participation, is what forfeits an appointment.

## 5 · The safe rule — for now, and only this

> **A restarted process is a new candidate until Governance explicitly establishes its participation.**

⛔ **The explicitly rejected remedy.** This must **not** be solved by making the system treat UUIDs as permanent human or process identities. The PO/ARB's assessment, recorded as given: *"That would be dangerous."* Binding a durable participant identity to a runtime identifier would convert a self-declared, unattestable, unstable string into a standing credential — and would make Direction A **worse**, not better, by lending the mechanism a confidence it has not earned.

**What this observation does NOT do:** it proposes no mechanism, no new identity engine, no schema change, no amendment to `AST-015`–`AST-019`, and no change to `INV-ATTR-1/2` or `G-2`. `ES-005.4` applies: were a remedy ever warranted, it would **extend an existing independence rule** — it would not become a capability of its own.

## 6 · Where a remedy would belong, if one is ever warranted

**`REVIEW_INDEPENDENCE_POLICY §22`**, which remains a **placeholder**. That placeholder is already carrying the disposition of `84c0f6f6`'s ordering deviation and the open `F-5`. `O-5` is a further datum for the same empty section — recorded, not counted into a promotion argument.

## 7 · Lifecycle

| | |
|---|---|
| **Occurrences** | **1** |
| **Rung (`ES-006.1`)** | **observation** |
| **Promotion** | **NOT PROPOSED.** Needs a second independent occurrence. **Never promote methodology from a single occurrence.** |
| **Methodology freeze** | Respected — no protocol refinement, no review-model evolution proposed here |
| **Effect on any workflow state** | **None.** No transition, no appointment, no grant, no adoption, no authorization. |

---

**Traceability:** PO/ARB direction 2026-08-23 (*"This deserves a separate governance observation"*) · `…-AMENDMENT-001-REPAIR-001-IMPLEMENTER-SELECTION-DECISION.md` (the decision this observation informed) · `…-AMENDMENT-001-REPAIR-001-AUTHORIZATION.md` §6 (the by-role independence table it qualifies) · `…-APPOINTMENT-SEQUENCING-DEFECT-001.md` (`ASD-001`) · `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (`O-1`…`O-3`) · `O-4` (`…-REPAIR-001-AUTHORIZATION.md` §3) · `INV-ATTR-1/2` · `G-2`/`G-3` · `REVIEW_INDEPENDENCE_POLICY §22` (placeholder) · `R-34`/`EP-02` · `ES-005.4` · `ES-006.1` · measured: `grep -rl` over `docs/` + `.claude/`, `session-bootstrap.php --process-label=930c65a4-…` → `UNRESOLVED`
