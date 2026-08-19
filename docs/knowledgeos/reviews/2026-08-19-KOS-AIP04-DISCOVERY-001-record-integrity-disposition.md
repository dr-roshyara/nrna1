# PO/ARB Decision Pack · **Part A — governance-record integrity**

**Prepared by:** Governance, on the delivered PO/ARB decision-preparation commission · 2026-08-19
**Work item:** `KOS-AIP04-DISCOVERY-001`
**⚠ This brief PRESENTS three questions and CHOOSES NONE.** Nothing was back-dated, restored, reconstructed or repaired. **Every option below requires a PO/ARB act to execute.**

---

## ⭐ Read this first — one of the three is not bookkeeping

**`X-2` is a live blocker, and it was measured rather than assumed.**

The work item's `mutationOwner` is **`None`**: seq 14 handed off to a lane that then never `START`ed, so ownership was released and never taken up. Consequently **no lane can hand off** — `Inv C` requires the current mutation owner — and `START` is a **conjunction** (`G-3`): it needs a predecessor handoff **and** a human act.

> **Probed on a scratch copy of the record, live file untouched (14 transitions before and after):**
> `refused: only the current mutation owner can hand off (Inv C)`

⇒ **Nothing further on this work item can be `START`ed until `X-2` is disposed.** The newly registered decision-preparation lane (`S4-architecture-aip04-decision-prep`, seq 15) is registered and **cannot receive a handoff** in the record's present state.

**This does not make the choice for the PO/ARB — but it prices it.** Option **A(1)** restores the chain; option **A(2)** leaves it stalled unless a separate repair act is authorized.

---

## A · Verification #3 — the missing `START`

**`OBSERVED`:** the Verification #3 report exists (`…-independent-verification-3-correction2.md`); the record ends at **seq 14 `HANDOFF`**. **There is no `START` transition.** The producing lane disclosed this itself as `X-2`, did not back-date it, and did not mutate the record. **It is the second occurrence at a "Verification #3" slot** in this estate (`KOS-ARCH-BASELINE-003` was the first).

| Option | What it records | Consequence |
|---|---|---|
| **A(1) TRANSCRIBE** | the missing human `START` as an **explicit governance transcription**, **preserving on the face of the record that the report was produced before the transition was recorded** | ✅ unblocks the lane chain · ⚠️ requires the PO/ARB to affirm that a human `START` was in fact given — **Governance cannot supply that fact** |
| **A(2) RETAIN AS OFF-RECORD EVIDENCE** | the report as evidence, and **the fact that the lifecycle transition was never recorded** | ✅ makes no claim about a human act that cannot be evidenced · 🔴 leaves `mutationOwner: None`, so **no successor can be `START`ed** without a further repair act |

**Not chosen. `PROPOSED` framings only.**
*One governance observation, `INFERRED`: a second occurrence at the same slot is a pattern, not an accident. Whichever option is taken, the recurrence is evidence for `C-10` — but per the commission, **do not infer that every lifecycle problem belongs to `C-10`.***

## B · The deleted session-log lines

**`OBSERVED`:** commit `9c93e2f0` removed **73 lines** from `.claude/sessions/2026-08-19.md`. Session logs are **append-only** (`ES-004.3`). The content is recoverable at `e8b3842b`. **Today's Governance entry was APPENDED beneath the truncation and records it; nothing was restored.**

| Option | What it means |
|---|---|
| **B(1) RESTORE** | re-append the removed content, marked as a restoration with its recovery reference — **the history becomes complete again, and the restoration is itself visible as an act** |
| **B(2) PRESERVE CURRENT HISTORY + RETAIN RECOVERY REFERENCE** | leave the file as it stands; the record carries the fact of the truncation and the commit that holds the content. **Nothing is lost — it is one lookup away** |
| **B(3) another explicitly justified disposition** | e.g. restore only the portions bearing on governed decisions |

⚠️ **Governance did not restore, and states why plainly:** restoring append-only history is **itself a history edit**, two lanes were writing this file the same day, and a restoration performed without authority would repeat the very defect it repairs.

## C · The deleted `D-1`…`D-5` register content

**`OBSERVED`:** the same commit **replaced** `2026-08-19-…-v3-decisions-registration.md` — 79 insertions against 166 deletions — rather than amending it. Removed:

| | Removed material | Why it matters |
|---|---|---|
| **1** | the **advocacy qualification** on the V-3 determination's producing process | ⚠️ **The ARB directed it be kept**: *"I would keep that qualification in the decision record"* |
| **2** | the **`D-2`/`D-3` coupling rule** — the two must not be split into contradictory decisions | ordering constraint on the decisions themselves |
| **3** | the **fifth-silence finding** — Decision 13.5's enumeration of four contract silences omitted dynamic property access | ⭐ **the replacing document's own premise check `P-b` independently confirms it**, so the deletion removed a finding its replacement corroborates |
| **4** | the **conformance-asserting residue** — evidence authored over ordinary fixtures but used to assert conformance | an unresolved gate question |

**All recoverable at `e8b3842b`.** ⛔ **Nothing was reconstructed or rewritten.**

| Option | What it means |
|---|---|
| **C(1) RESTORE the removed material** as an additive amendment to the current register | the ARB-directed qualification returns to the decision record; the register carries both readings and their history |
| **C(2) ACCEPT the current register as it stands** and record the removal as a known, referenced event | fewer edits; ⚠️ **a direct ARB instruction remains unexecuted** |
| **C(3) another explicitly justified disposition** | e.g. restore items 1–3 and route item 4 to the gate grant instead |

### ⚠️ C is entangled with an open Track-1 question

The replacing register reads `D-1`…`D-5` as **DECIDED**; the replaced one registered them as **OPEN**; and **nothing was appended to the append-only record**, so the record still reads *"dynamic-member expected evidence REMAINS blocked"* while the document reads *"out of scope"*.

**The disposition of C therefore depends on an answer Governance does not have:** *were `D-1`…`D-5` PO/ARB decisions, or recommendations transcribed as decisions?* **`OPEN`.** *Evidence since: the session log records a later delivery "in the imperative (`DECIDE:`)", which shifts the weight toward performative — but it is another lane's account of an act this lane never saw. It is evidence, not resolution.*

---

## What Governance did NOT do

⛔ No `START` invented or back-dated · ⛔ no history restored · ⛔ no register reconstructed · ⛔ no workflow history repaired · ⛔ no option chosen · ⛔ Verification #3 not re-run and its Verdict `G` not rewritten · ⛔ no capability, ownership, context, agent or service created.

## Where the rest of the Decision Pack is

**Parts B–O are registered as an assignment, not authored here.** `S4-architecture-aip04-decision-prep` (seq 15) under `G-KOS-AIP04-DECISION-PREP`, **`CREATED`, not started**, carrying the full sixteen-step capability sequence, the decided inputs, the per-capability constraints and the forbidden list.

**Role, recorded with its reason:** the commission is headed *Governance*, but **twelve of its fifteen parts are DDD capability analysis** — does a capability exist, what is its invariant, its reason to change, its boundary, its candidate bounded context. Under the adopted operating model that is **Architecture-role work**, and **Governance does not perform the work it routes.** The lane is therefore registered `role=architecture`. **Part A was not delegated — it is above.** **If the PO/ARB intended Governance to author the capability briefs, say so in the `START` act and the role is corrected.**

**Next actor: Human PO/ARB — dispose `A`, `B`, `C`; then `START` the decision-preparation lane (which `A` currently blocks).**

**Traceability:** the PO/ARB commission 2026-08-19 · `G-KOS-AIP04-DECISION-PREP` seq 15 · `G-KOS-AIP04-VERIFY3` + `-AMD1` (adoption as decided input) + `-AMD2` (verdict `G` re-scoped) · Verification #3 report (`X-2`, self-disclosed) · `9c93e2f0` · `e8b3842b` · `cc2507b0` · `ES-004.3` · `Inv C` · `G-3` · `G-1` · `INV-ATTR-2`/`G-2` · `R-34`/`P-2`

---

# ⬛ AMENDMENT A1 — the capability analysis is bounded; ownership decisions deferred · 2026-08-19

**Registered by:** Governance, on the delivered PO/ARB act revising the next-step plan · recorded as **`G-KOS-AIP04-DECISION-PREP-AMD1`**.

## A1.1 · What the amendment changes

> ⛔ **Do NOT decide ownership of `C-5`, `C-10`, `C-14` or `C-19`.** The lane produces **one bounded DDD capability analysis** across the four; **PO/ARB decides ownership afterwards.**

**The analysis method is superseded** — not the direction. `capability → bounded context/stewardship → ownership → adopted role → agent → service` still binds as the **direction**; the **method** is now the ten questions, because they force **meaning, invariant, evidence, authority and effect** to be settled *before* ownership rather than alongside it.

> ### ⭐ The central warning, registered
> **Do not assume every capability deserves its own bounded context.** Admissible outcomes: **bounded context · cross-context capability · stewardship · control-plane function · another explicit architectural category.**

## A1.2 · Hypotheses recorded AS hypotheses

`C-5` and `C-14` **may** be cross-context **control** capabilities · `C-10` **may** be a cross-**layer delivery** capability · `C-19` **may** remain delivery/stewardship.

⛔ **And the four role pairings are explicitly NOT decisions and must not be adopted:** `C-5 → Verification Engineer` · `C-10 → Knowledge Engineer` · `C-14 → Governance Engineer` · `C-19 → Communication Engineer`.

**Per-capability tests registered:** `C-5` — **separation is graded, not Boolean**; a bounded context is plausible only if it owns attestation records, assurance levels, exceptions, disputes and their lifecycle · `C-10` — test the nine-stage lifecycle and find **which parts form one coherent capability** · `C-14` — test `definition → evaluation → enforcement → protected action → audit`, with a lifecycle **distinct from policy authorship** · `C-19` — a Communication context is justified only if it owns Message, Audience, Channel, Consent, Delivery, Acknowledgement, Retention, Publication. ⛔ **Communication Engineer ≠ Communication bounded context.**

## A1.3 · ⚠️ The external research — evidence status recorded before use

**The cited research is NOT present in this estate.** Governance searched: the only Perplexity artifact is `architecture_legacy/ai_architecture/pks/20260803_1426_perplexity_view.md` (**2026-08-03**, a different subject — retrieval-layer-governed knowledge for AI agents). **It was not produced by this Governance lane, and this lane did not upload it.**

⇒ it is **external, ungoverned, and its provenance is not attestable here.** It may serve as **external corroborative input only**, and **must be registered as an artifact in the estate before any finding cites it.** The estate's own rule governs: *"agreement with an unread source is not independent evidence when authorship/exposure cannot be attested."*

⛔ **External research may not be treated as governed architecture, and may not substitute for primary evidence from this estate.**

*Its analytical content is nonetheless carried into the grant on its merits — the graded-separation model, the nine-stage knowledge lifecycle, the policy chain and its seven effects, and the communication-concept test are registered as **tests to apply**, not as findings.*

## A1.4 · Unchanged

Six-role adoption stands and is not reopened · the non-equivalences bind · Verification #3 frozen · the forbidden list stands · Part A above is incorporated, not re-derived.

⛔ **The lane remains NOT STARTED — and cannot be started until `X-2` is disposed.**
