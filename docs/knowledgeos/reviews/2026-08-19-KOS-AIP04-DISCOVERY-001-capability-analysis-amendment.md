# Registration — mandatory amendment to the Capability Architecture Analysis

**Registered by:** Governance, on the delivered PO/ARB amendment act · 2026-08-19
**Work item:** `KOS-AIP04-DISCOVERY-001` · **grant:** `G-KOS-AIP04-DECISION-PREP-AMD2` (AUTHORIZED) · **lane:** `S4-architecture-aip04-decision-prep` — **`ACTIVE`** (seq 18)
**⚠ This amendment changes the required FORM and PRECISION of the analysis. It DECIDES NOTHING.**

> **The delivered analysis (`ba74dbdd`) remains historical evidence and is NOT rewritten by this act.**

---

## ⭐ 1 · The refinement everything else is an instance of

> **Separate capability EXISTENCE from capability CATEGORY. Two distinct questions, for every one of `C-5`, `C-10`, `C-14`, `C-19`:**

| | Question | Admissible answers |
|---|---|---|
| **A** | **Does the capability exist?** | **YES · NO · CONTESTED · NOT YET ESTABLISHED** |
| **B** | *If it exists*, what category fits? | bounded context · cross-context capability · stewardship · control-plane function · another explicitly justified category |

> ⛔ **Do not derive one answer from the other.**

*This is the sharpest correction in the act. "It has no aggregate of its own, therefore it is not a capability" is a **category** test doing an **existence** test's work — and the conclusion it produces is unsound in exactly one direction: a capability can be real and still belong to no context of its own. `NOT YET ESTABLISHED` is the answer that was previously unavailable, and it is the honest one for most of this surface.*

## 2 · Per-capability refinements

### `C-5`
Distinguish **authoritative assurance RESULT** from **`C-5`-owned authoritative AGGREGATE / STATE.**
⛔ **Absence of a `C-5`-owned aggregate may disqualify a separate bounded context but must NOT be used as proof that the capability itself does not exist.** *(Refinement 1, in the concrete.)*

**Single-operator constraint — six separations, distinguished explicitly:** process separation · access separation · artifact isolation · independent execution · **organizational independence** · external attestation.
⛔ **Do not treat organizational independence as established in a single-operator environment.**

### `C-10`
Distinguish, explicitly: `ContextPublished` · `ContextSelected` · `ContextDelivered` · `ContextAvailable` · `ContextAcknowledged` · `ContextApplied` · `ContextVerified`.
⛔ **Receipt is not proof of application or compliance.**

**`OQ-J` reformulated:** from *"is receipt authoritative?"* to **"authoritative for WHAT CLAIM?"** — considered separately across **delivery · possession · applicability · execution · compliance** authority.
*One question that could only be answered yes or no becomes five that can each be answered correctly. The old form invited a single authority claim to cover five different assertions.*

### `C-14`
The categorical *"no `C-14` capability"* is **replaced by provisional wording, registered verbatim**:

> **"On current evidence, no platform-wide `C-14` capability is established. A Tier-1 enforcement pattern exists within `CAP-09`, but whether that instance constitutes the same capability as `C-14` remains open."**

**Four separate analyses required:** policy **tiering** · coverage **mapping** · enforcement **execution** · enforcement **assurance**.

### Shared invariant — `OQ-L`
⛔ **Do not adopt the current wording automatically.** The analysis must define **party · issue · constrain · advisory vs authoritative result · machine-generated result vs independent ratification.**

**Candidate stronger formulation — may be considered, still `PROPOSED`:**
> *"A party subject to a governed control may not be the sole authority for issuing, accepting, or finalizing the control's verdict about its own compliance, separation, or conformance."*

## 3 · Dependency language

Distinguish **semantic** dependency · **evidence** dependency · **implementation** dependency.
⛔ **Do not present build order as architectural ownership.**

## 4 · ⚠️ Evidence status of the source, recorded before use

**The cited *"Independent review of `KOS-AIP04-DISCOVERY-001-capability-architecture-analysis`"* is NOT present in this estate.** Governance searched the repository; no such artifact exists, tracked or untracked.

⇒ **the refinements are registered on their own merits as PO/ARB instructions — not as findings inherited from a review.** The review **may not be cited as evidence until it is registered as an artifact**, and its independence is **unattestable here** (`INV-ATTR-2`/`G-2`). The estate's own rule applies: *agreement with an unread source is not independent evidence.*

*This is the third external input in two days whose artifact is absent from the estate (the 925-line role model, the external research, this review). **`INFERRED`: that recurrence is itself evidence bearing on `C-10` — governed knowledge arriving outside the governed channel — and the analysis may note it, but per `AMD1` must not assume `C-10` owns it.***

## 5 · Status

**Decides nothing:** not capability existence · not category · not ownership · not stewardship · not `C-10` receipt authority · not `C-14` Reading 1/2 · not `OQ-K` · not `OQ-L`.

**`AMD1` remains in force in full:** ownership deferred · the ten questions · the eleven-stage chain · **the four forbidden role pairings** · *do not assume every capability deserves its own bounded context* · external research admissible only as unregistered external input.
**The six-role adoption is not reopened. Verification #3 remains frozen.**

## 6 · Lane state — no further `START` is needed

`S4-architecture-aip04-decision-prep` is **`ACTIVE`** (seq 18) and holds mutation ownership. **This amendment takes effect immediately**; unlike a new assignment it needs no human `START`.

**Next actor: Architecture — amend the analysis under `AMD1` + `AMD2`, then STOP for PO/ARB.**

**Traceability:** the PO/ARB amendment act 2026-08-19 · `G-KOS-AIP04-DECISION-PREP` + `-AMD1` + `-AMD2` · analysis `ba74dbdd` (`OQ-J`/`OQ-K`/`OQ-L` as proposed additions) · seq 16–18 · `G-KOS-AIP04-VERIFY3` + `-AMD1` + `-AMD2` · `INV-ATTR-2` · `G-2` · `G-3`
