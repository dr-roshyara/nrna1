# C-10 Decision 1 — Receipt semantics · **DECIDED: Option C** (recorded as delivered)

**Work item:** `KOS-AIP04-DISCOVERY-001` · **PO/ARB act 2026-08-19** · **Recording process:** `claude-code-session:4858c37c` — self-declared, **not third-party attested**; ⚠️ **the process whose two Track-2 artifacts are `PROVENANCE-CONFLICTED` / `INDEPENDENTLY UNVERIFIED`** (`b853a644`).

⛔ **This document records a decision made by the PO/ARB. It makes none.**

---

## 1 · The decision, as delivered

> ## **DECIDED — Option C: authoritative delivery / acknowledgement only.**
>
> **"The C-10 system records an authoritative fact that context package P was delivered to and acknowledged by session execution S at time T, under applicability decision A."**

**The receipt DOES establish:** which package · which knowledge versions · which session execution · which assignment · **applicability *reference*** · delivery event · acknowledgement event · time · issuer / evidence basis.

**The receipt does NOT establish:** understanding · application · compliance · verification · **correctness of applicability** · organizational independence.

> ### ⭐ **Invariant recorded with the decision: `AUTHORITY IS CLAIM-SCOPED`.**
> **Authoritative for:** *"P was delivered to and acknowledged by S at T"*.
> ⛔ **NOT authoritative for:** *"S understood P"* · *"S applied P"* · *"S complied with P"*.
> **Purpose, as stated by the act: to prevent the receipt becoming a general-purpose "knowledge satisfied" certificate.**

**Also directed:** *"delivered"* and *"acknowledged"* are to be defined **operationally and separately** — ⛔ not done by this record.

## 2 · Attribution — corrected on the face of the record

⚠️ **The act opens *"I agree with the substantive decision"* and attributes the Option-C selection to this process. That attribution is incorrect.** `890bb1cc` **declined to select** on decision-authority grounds and **flagged Option C as NOT SATISFIABLE AS DRAFTED**. ⛔ **The selection is the PO/ARB's own act, and the record must not show a process having made it** — this work item has already recorded misattribution four times.

## 3 · ⭐ The act's refinement RESOLVES the defect that was flagged — recorded, because it is a real fix

**The impediment was:** Option C bound the receipt to *"under applicability decision A"*, while `OQ-J` row 3 holds that **a receipt cannot carry applicability authority** — *"it is a judgement, and unowned."*

**The act's refinement dissolves it by splitting reference from correctness:**

| | |
|---|---|
| **establishes** | an **applicability *reference*** — a pointer to decision `A` |
| **does NOT establish** | the **correctness of applicability** — whether `A` was right |

⭐ **A pointer to a judgement is not an assertion that the judgement was sound.** `OQ-J` row 3's prohibition is therefore honoured, not overridden: **the receipt carries claims 1 (delivery) and 2 (possession) and references — without asserting — claim 3.** ✅ **The authority defect is closed.**

## 4 · 🔴 The residual, which the refinement does NOT close — the REFERENT GAP

**`OBSERVED`, from primary text:** the object the receipt is now required to reference **does not exist**.

| Evidence | Line |
|---|---|
| `ContextSelected` — *"the subset **applicable to this act** is determined"* — **🔴 does not occur · owner: 🔴 nobody** | 551 |
| *"a receipt for an **applicable** version presupposes someone determines applicability"* — **IMPLEMENTATION** dependency | 682 |
| *"applicable"* — one of `I-K1`'s **three undefined load-bearing terms** | 564 |

> ### ⭐ **The decision commits the receipt to reference an applicability decision that the estate never produces.**
> **This is no longer an authority problem — it is an existence problem about the referent.** **Two consequences follow, and both are PO/ARB's:** either **① the applicability reference is optional or null until `ContextSelected` has an owner** — in which case the receipt as defined is not yet fully populatable — or **② adopting this decision creates a standing obligation to establish `ContextSelected` and its owner.**
> ⛔ **This record chooses neither, and proposes no schema.** **`OQ-B` (who owns C-10) and the `ContextSelected` owner question remain `OPEN`.**

## 5 · ⚠️ Basis note — the external research may corroborate, never authorize

**The act cites *"the Perplexity review"* twice in support** — *"the distinction the Perplexity review emphasizes"* and *"Perplexity correctly points out…"*.

**Governed status, re-verified:** `docs/knowledgeos/brainstorming/perpleixity_research_on_roles.md` — **1595 lines, STILL UNTRACKED**, classified **Class C · `EXTERNAL / CORROBORATIVE`** at analysis lines 84 and 412, with the rule stated twice: ⛔ ***"never project authority, never `DECIDED`."***

⇒ ⭐ **The decision stands on its own merits and needs no external support — the distinction it draws is independently sound (§3).** ⛔ **But a `DECIDED` act must not rest on untracked external material, because citing it does not convert it, and `AMD2` bars its use as authority until registered.** **Recorded so the basis is not later read as external-derived.**

## 6 · Not adopted, and not present

⛔ **No typed `KnowledgeDeliveryReceipt` structure is adopted** — and, verified: **no such type exists anywhere in the estate** (`docs/`, `app/`, `.claude/`). It is neither this process's proposal nor any lane's committed work. **Schema follows the semantic decision; the domain structure is Architecture's to determine.**

## 7 · The sequence, as directed — ⛔ ownership and bounded context NOT reached

```
C-10 D1  receipt semantics          ✅ DECIDED — Option C, claim-scoped
   ↓
C-10 D2  does the capability exist? ← NEXT
   ↓
C-10 D3  does it own authoritative state / lifecycle?
   ↓
C-10 D4  missing / disputed receipt behaviour
   ↓
category  →  stewardship / ownership
```

**Registered for `D3` when it is reached:** whether C-10 owns enough of — authoritative receipt state · receipt lifecycle · invalidation · dispute · identity binding · applicability reference · decision rights.
⚠️ **And the act's own caution, recorded:** the existence of authoritative receipt state **strengthens** the bounded-context case but **does not by itself decide it.**

⛔ **Still `OPEN` and untouched:** C-10 **existence** (`NOT YET ESTABLISHED`) · category · ownership / stewardship · the **Knowledge Engineer pairing (still a forbidden hypothesis)** · `OQ-K` · `OQ-B` · bounded context · implementation · the operational definitions of *delivered* and *acknowledged* · the `ContextSelected` owner.
**Preserved:** `created ≠ published ≠ selected ≠ retrieved ≠ delivered ≠ acknowledged ≠ applied ≠ verified` · `immutable ≠ authoritative ≠ authentic ≠ complete ≠ independent`.

**STOP.** ⛔ **No existence, category, ownership, stewardship or implementation decision · no schema · no new C-10 model (`ES-005.4`) · nothing accepted or closed.**
**Next actor: 🔵 PO/ARB — C-10 Decision 2.** ⚠️ **And the fresh-verification gate (`b853a644` §5) still admits no known process.**

**Traceability:** PO/ARB C-10 Decision-1 act 2026-08-19 · the declined-selection record `890bb1cc` · canonical analysis §5, §A1.3 (`OQ-J` five authorities; seven states), lines 84, 412, 551, 564, 682 · provenance disposition `b853a644` · `AMD2` grant · `ES-005.4` · `G-1` · `INV-ATTR-1`/`INV-ATTR-2`.

---

# ✅ AUTHORITATIVE DECISION TEXT (formal PO/ARB act, 2026-08-19) — appended; §1 above is the earlier draft and is superseded as to wording

> ## **STATUS: DECIDED — APPROVED, Option C.**
>
> **"The C-10 system records an authoritative fact that context package P, containing knowledge versions K, was delivered to and acknowledged by session execution S at time T, under applicability decision A."**

| | |
|---|---|
| **THE RECEIPT ESTABLISHES** | which package · which knowledge versions · which session execution · which assignment · **applicability reference** · delivery event · acknowledgement event · time · issuer / evidence basis |
| **THE RECEIPT DOES NOT ESTABLISH** | **comprehension** · understanding · application · compliance · verification · **correctness of applicability** · organizational independence |

> ### **CORE INVARIANT: `AUTHORITY IS CLAIM-SCOPED`.**
> *"The receipt is authoritative only for the claim that delivery and acknowledgment occurred. It is not authoritative for understanding, application, or compliance."*

**REQUIRED PROPERTIES (eight, as decided):** stable receipt identity · package and knowledge versions · session/assignment identity · **applicability reference** · delivery and acknowledgement timestamps · issuer and assurance basis · **invalidation semantics** · **immutable provenance linkage**.

**NON-DECISIONS, explicitly remaining `OPEN`:** C-10 capability existence (`D2`) · authoritative state / lifecycle (`D3`) · missing / disputed receipt behaviour (`D4`) · architectural category · ownership / stewardship · implementation technology.

## What changed from the draft in §1 — recorded so the diff is not silent

| | |
|---|---|
| decision statement | **+ *"containing knowledge versions K"*** |
| not-established list | **+ comprehension** *(now distinct from understanding)* |
| ⭐ **new section** | **REQUIRED PROPERTIES — eight items**, including two the draft did not carry: **invalidation semantics** and **immutable provenance linkage** |
| non-decisions | now **enumerated explicitly**, incl. *implementation technology* |

## Two readings recorded, so the formal act is not later over-read

**① `invalidation semantics` is a REQUIRED PROPERTY while `D3` (authoritative state / lifecycle) stays `OPEN`.** ⭐ **Read as: the receipt MUST HAVE invalidation semantics; WHAT they are is `D3`'s to decide.** ⛔ **This decision does not pre-empt `D3`** — it establishes that a receipt without invalidation semantics would not satisfy `D1`, nothing more.
**② `immutable provenance linkage` is consistent with the act's own distinction `immutable ≠ authoritative`.** Immutability is required **as a property**; it confers no additional authority beyond the claim-scoped one.

## 🔴 The referent gap is now SHARPER, not resolved

**`applicability reference` has moved from a listed establishment to a REQUIRED PROPERTY.** **`ContextSelected` — *"the subset applicable to this act is determined"* — remains `🔴 does not occur · owner: 🔴 nobody`** (canonical analysis line 551).

> ⭐ **Consequence, stated precisely: a receipt conforming to this decision is NOT PRESENTLY POPULATABLE, because one of its eight required properties references an act the estate never performs and nobody owns.**
> **The authority question is closed** — reference ≠ correctness, per §3 above, and that holds. **The existence question about the referent is not.** ⛔ **Neither disposition is chosen here:** whether the reference may be null/optional until `ContextSelected` has an owner, or whether this decision creates a standing obligation to establish it, is **PO/ARB's** — and it bears directly on `D2`, since a capability whose required output cannot yet be produced is exactly the `NOT YET ESTABLISHED` question `D2` asks.

⛔ **§§2–6 above stand unchanged:** the attribution correction · the refinement that closed the authority defect · the basis note on the untracked external research *(**never project authority, never `DECIDED`** — analysis lines 84, 412)* · and **no typed `KnowledgeDeliveryReceipt` adopted** *(verified: no such type exists in the estate)*.

**Next actor: 🔵 PO/ARB — C-10 `D2`: does C-10 exist as a distinct capability?** ⚠️ **The fresh-verification gate (`b853a644` §5) still admits no known process.**
