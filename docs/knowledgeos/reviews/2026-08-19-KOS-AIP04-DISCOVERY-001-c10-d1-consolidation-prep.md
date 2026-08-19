# C-10 `D1` — Receipt Claim Boundary **Consolidation and Selection** · **DECISION-PREP, NOT THE DECISION**

**Work item:** `KOS-AIP04-DISCOVERY-001` · **Prepared by:** Governance (`b64828fe`) · 2026-08-19

> ⛔ **The act carries no selection.** Its own rule — *"Selection MUST be recorded by proposition text"* — is not satisfied by any line in it. **Governance does not select on the PO/ARB's behalf.**
> ✅ **The act's "option letters are invalid" rule DISCHARGES defect `S-1`** (the `Option C` label denoting two different propositions). **Recorded as cured.**

---

## 1 · Disposition of previous D1 acts — **recommended, not decided**

**All three exist and were read before recommending.** ⚠️ **All three were recorded by `claude-code-session:4858c37c`** — the process whose Track-2 artifacts are `PROVENANCE-CONFLICTED` / `INDEPENDENTLY UNVERIFIED` (`b853a644`). *(Act A records a PO/ARB decision; the process recorded it, it did not make it.)*

| Record | What it is | **Recommended disposition** |
|---|---|---|
| **Act A** — `a97ff3b2` / text `a0b61305` | ✅ **a real decision**: `DECIDED — "Option C"`. Proposition: *"package P, **containing knowledge versions K**, was delivered to and acknowledged by session execution S at time T, **under applicability decision A**."* Eight required properties incl. **applicability reference** | **SUPERSEDED IN ITS PROPOSITION, RETAINED AS HISTORICAL.** ⚠️ **Its eight required properties must be RE-RECORDED** — any selection other than P1 changes that list |
| **Act B** — conflict record `43c7cd33` | ⛔ **not a decision** — a question with unselected options; proposition drops `K` and the applicability clause | **CONSOLIDATED into this act** (it is the source of the propositions) **and RETAINED as the conflict record** |
| **Stabilization act** | ⛔ **no selection**; raised `S-1` and `S-2` | **CONSOLIDATED.** `S-1` **cured** by the proposition-text rule |

⛔ **No prior record is silently replaced.** All three remain readable in history.

## 2 · ⭐ The propositions map onto the record's own unresolved defect

The conflict record identified a **referent gap**: `applicability reference` is a **REQUIRED property** of a receipt, while `ContextSelected` — *"the subset applicable to this act is determined"* — is 🔴 **does not occur · owner: nobody**. ⇒ **a conforming receipt was not presently populatable.**

| | Maps to | Effect on the referent gap | Conflict with standing decisions |
|---|---|---|---|
| **P1** delivery + ack + *"an applicability reference was recorded"* | conflict record **Reading (a)** | ⚠️ **GAP PERSISTS** — a required field pointing at an act that never occurs | none |
| **P2** delivery + ack; *applicability **fully outside** C-10* | **Reading (b)**, at maximum strength | ✅ gap closes | ⛔ **CONTRADICTS `D3.2`**, which states *"C-10 **MAY record references** to these external facts"*. *"Fully outside"* would forbid even recording one |
| **P3** delivery + ack; **MAY** contain external correlation references — traceability only, no ownership / applicability / correctness / authority | **Reading (a) with the required-field problem removed** | ✅ **gap closes** — the reference becomes **optional**, so a receipt is populatable when `ContextSelected` has not occurred | ✅ **none** — it *is* `D3.2`'s rule stated as language |

> ### ⭐ The decisive point, and it is structural rather than stylistic
> **The referent gap is caused by the reference being REQUIRED, not by its being recorded.** **P3 is the only proposition that makes it OPTIONAL and NON-AUTHORITATIVE** — dissolving the gap while keeping traceability. **P1 keeps the gap; P2 buys closure by contradicting `D3.2`.**

## 3 · DDD evaluation

| Test | P1 | P2 | **P3** |
|---|---|---|---|
| **Ubiquitous-language stability** — does *receipt* have one meaning? | 🟠 three claims of **mixed kind** — two about the world, one about the record | ✅ one meaning | ✅ **one meaning**; references are an **annotation**, not a claim |
| **Boundary alignment** — C-10 receipt lifecycle · BC-1 knowledge meaning + applicability authority · BC-6/7 transport + session | 🟠 elevates an applicability reference toward claim status | 🟠 over-corrects; severs traceability to BC-1 | ✅ **clean on all three** |
| **Anti-corruption** — *reference ≠ ownership · recording ≠ asserting · correlation ≠ authority* | 🟠 partial | ✅ by exclusion | ⭐ **states all three explicitly** — it is the anti-corruption rule as language |

## 4 · `E2` impact — mandatory receipt properties under each proposition

**Under P3 the required set is:** package identity `P` · session execution reference `S` · **delivery event** · **acknowledgement event** · time `T` · issuer / evidence basis.
**Optional, zero or more:** external correlation references, **each marked non-authoritative**.

⚠️ **Two residues no selection resolves — flagged, not decided:**

1. ⭐ **`knowledge versions K`.** Act A had it **in the proposition**; Act B dropped it; **all three new propositions are silent on it.** Act A still lists it as a required property. **Selecting any proposition leaves `K`'s status as a required property UNDECIDED — E2 must settle it.**
2. ⭐ **`S` is unattestable.** `INV-ATTR-1` — no gate reads identity; `D4.3` decided **M4 = `NOT ATTESTED`**. ⇒ **E2 must mark the session-execution reference `RECORDED, NOT ATTESTED`.** Under P3 this is coherent, because references are traceability-only by construction. **Under P1 it is sharper**, since *"acknowledgement occurred"* names a subject the platform cannot bind.

## 5 · `D5` impact — and a correction Governance owes the record

> ### ⚠️ **CORRECTION: the D5 provenance finding is SUPERSEDED BY EVENTS.**
> The register's `DEC-D5 §2` recorded *"no D5 analysis artifact exists."* **That was true when measured. It is no longer true.** **`10fbfb05` (2026-08-19 18:24:47) delivered `KOS-AIP04-C10-D5-ESTABLISHMENT-CRITERIA-PROPOSAL.md`, 300 lines** — *"Option A is unsatisfiable, INDEPENDENT is refused, and D1 is Class C evidence."*
> ⛔ **The finding is not withdrawn — it was accurate at the time and the commission gap it recorded was real. It is superseded, and this correction is additive.**

**Can establishment criteria now be finalized?** ⚠️ **Not until D1 selects.** `E2` (receipt completeness) is `D5`'s blocking dependency (`R-5`), and §4 shows **`E2`'s required-property set differs by proposition.** ⇒ **the delivered D5 proposal's `E2` treatment must be RECONCILED against whichever proposition is selected.** The PO/ARB's own sequence — **D1 → E2 → D5 adoption → D2 revisit → D6 → D7** — is therefore correct and is recorded as binding.

## 6 · Recommendation

> ### **Recommended: PROPOSITION 3**, on the structural ground in §2 — it is the only proposition that closes the referent gap **without** contradicting `D3.2`.

**Recommended selection text, for the PO/ARB to issue or amend:**

> **"Select the proposition: A C-10 receipt proves that delivery occurred and that acknowledgement occurred. A receipt MAY contain external correlation references; those references provide traceability only, and do not transfer ownership, do not assert applicability, do not assert correctness, and do not create authority."**

**Receipt CLAIMS:** delivery occurred · acknowledgement occurred.
**Receipt NON-CLAIMS:** applicability · correctness of applicability · knowledge correctness · understanding · application · compliance · verification · organizational independence.
**External reference rule:** *traceability only — no ownership transfer, no applicability assertion, no correctness assertion, no authority creation.*

## 7 · Not decided here

⛔ **the selection** · disposition of prior acts *(recommended only)* · `D2` capability existence · `D5` establishment criteria · `D6` category · `D7` ownership · implementation · `K`'s required-property status · `E2`'s final property set.

**Traceability:** PO/ARB act 2026-08-19 (D1 consolidation) · **Act A** `a97ff3b2`/`a0b61305` · **Act B** conflict record `43c7cd33` · stabilization act registration (`S-1`, `S-2`) · declined-selection `890bb1cc` · `b853a644` (provenance-conflicted recorder) · **D5 proposal `10fbfb05`** · `D3` (D3.2 reference rule) · `D4.3` (M4 = `NOT ATTESTED`) · `OQ-J` · canonical analysis line 551 (`ContextSelected` unowned) · `INV-ATTR-1`/`INV-ATTR-2`
