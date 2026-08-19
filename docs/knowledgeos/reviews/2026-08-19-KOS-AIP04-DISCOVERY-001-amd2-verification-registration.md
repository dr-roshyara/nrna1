# Registration — Independent Verification of the AMD2-amended Capability Architecture Analysis

**Registered by:** Governance, on the delivered PO/ARB `REGISTER` act · 2026-08-19
**Work item:** `KOS-AIP04-DISCOVERY-001` · **grant:** `G-KOS-AIP04-VERIFY-AMD2` (AUTHORIZED) · **assignment:** `S1-verification-aip04-amd2` (verification) — `REGISTER` seq 19 · `HANDOFF` seq 20
**⛔ NOT STARTED** — state `CREATED`. **No verification performed · no substance reviewed · the Architecture amendment NOT accepted · the analysis, the OQ register and Track 1 untouched.**

---

## 1 · Preconditions — checked against the record

| | Precondition | Result |
|---|---|---|
| **1** | `G-KOS-AIP04-DECISION-PREP-AMD2` exists and is registered | ✅ AUTHORIZED |
| **2** | The AMD2-amended analysis exists | ✅ `aff41549` — *"existence separated from category, and it moved three of four verdicts"*. Spot-checked: `NOT YET ESTABLISHED` present · the `ContextAcknowledged` state series present · `C-14`'s provisional wording present · `OQ-J` reformulated to *"authoritative for WHICH claim?"*, split into the five authorities |
| **3** | Delivered under the active `S4-architecture-aip04-decision-prep` lane | ✅ `START` seq 18, lane `ACTIVE` and holding mutation ownership at delivery |
| **4** | No verification assignment already exists for this AMD2 delivery | ✅ none |
| **5** | No conflicting verification grant for this delivery | ✅ none |
| **6** | Verifier independent of the named producers | ⚠️ **bar registered, satisfaction NOT certified** — see §2 |
| **7** | `R-34`/`P-2` — producer must not verify or accept the same work | ✅ registered, and the producer is barred by name |

*Precondition 2 was checked for **existence and evident implementation**, not for correctness — whether AMD1/AMD2 were implemented **correctly** is the verification's question and Governance does not pre-empt it.*

## 2 · Independence — the bar, and the one limb that cannot be applied

**Barred by name:**

| Process | Ground |
|---|---|
| `claude-code-session:5e1dd9ee` | **producer of the capability analysis AND its Amendment 1**, and of the ADR-AIP-04 discovery, Amendment 1 and Correction #2. Its own artifact discloses that it *"analyses four capabilities of its own construction, and twice corrected their statuses"* — `R-34`/`P-2` bars it |
| `claude-code-session:1c8b041b` | ADR-AIP-04 Verification #1; Track-1 implementation |
| `claude-code-session:2da45a86` | ADR-AIP-04 Verification #2 |

### ⚠️ The limb that cannot be applied — recorded, not waived

The act bars *"the author of any review that materially caused AMD2, **if identifiable**."* **That review is not in the estate** — Governance searched and found no artifact for the cited *"Independent review of the capability architecture analysis."*

⇒ **its author is not identifiable, the bar cannot be applied to it, and the act's own qualifier — *"if identifiable"* — is what permits registration to proceed rather than stop.**

**Registered into the assignment:** the verifier **must state whether it authored that review.** If it cannot answer, **that limb of the bar is unsatisfied and must be disclosed as such.**

*`INV-ATTR-2`/`G-2` stand: independence here is **declared, not attestable**; `INV-ATTR-1`: no gate reads process identity.*

## 3 · The charge

> **Determine whether the amended analysis correctly implements the registered `AMD1` + `AMD2` epistemic and DDD corrections, AND whether it REMAINS A DECISION-PREPARATION ARTIFACT rather than silently becoming an architectural decision.**

*The second half is the one that needs a verifier. An analysis that answers its own open questions well is still out of scope — and that failure is invisible to anyone who only checks whether the answers are good.*

**Eleven test areas** `A`–`K`, with three decomposed:

| | Decomposed into |
|---|---|
| **`C-10`** | `ContextPublished` · `ContextSelected` · `ContextDelivered` · `ContextAvailable` · `ContextAcknowledged` · `ContextApplied` · `ContextVerified` — **and separately challenge** delivery · possession · applicability · execution · compliance authority |
| **`C-14`** | policy tiering · coverage mapping · enforcement execution · enforcement assurance |
| **`C-5`** | process separation · access separation · artifact isolation · independent execution · **organizational independence** · external attestation |

⛔ **Organizational independence must not be treated as established in a single-operator environment.**

**Thirteen verdicts `A`–`M`**, each PASS / PASS WITH NOTES / FAIL, then **READY FOR PO/ARB** or **RETURNED FOR CORRECTION**.

## 4 · Boundaries registered

**MAY:** inspect evidence, architecture and workflow records · **read-only** probes · independently re-derive claims · **temporary throwaway** probes · report insufficiency.
**MAY NOT:** modify Architecture · repair defects · **create the replacement architecture** · assign ownership · create bounded contexts or capabilities · make PO/ARB decisions · **modify the OQ register** · implement anything · **accept the Architecture amendment.**

**Written into the execution context verbatim, as the act requires:**
> **"Verification identifies insufficiency; it does not provide the replacement architecture."**

## 5 · Standing context, not reopened

The six-role adoption is **DECIDED** · Verification #3 is **FROZEN** · ownership of `C-5`/`C-10`/`C-14`/`C-19` is **DEFERRED** and the four role pairings remain **FORBIDDEN** · the pre-amendment analysis (`ba74dbdd`) remains **historical evidence and is not rewritten**.

## 6 · Lifecycle

`GRANT` + `ASSIGNMENT` + `HANDOFF` performed. ⛔ **`START` not performed** — confirmed against the engine rather than asserted:

> `refused: START requires the recorded human start act — a handoff alone never yields ACTIVE (G-3)`

*The decision-prep lane is **`HANDED_OFF`, not `COMPLETED`** — its closure remains a separate PO/ARB act (`G-1`).*

**Next actor: Human PO/ARB → `START S1-verification-aip04-amd2`.**

**Traceability:** the PO/ARB `REGISTER` act 2026-08-19 · `G-KOS-AIP04-VERIFY-AMD2` · seq 19–20 · amended analysis `aff41549` · original analysis `ba74dbdd` · `G-KOS-AIP04-DECISION-PREP` + `-AMD1` + `-AMD2` · seq 18 `START` · `R-34`/`P-2` · `INV-ATTR-1`/`INV-ATTR-2` · `G-1` · `G-2` · `G-3`
