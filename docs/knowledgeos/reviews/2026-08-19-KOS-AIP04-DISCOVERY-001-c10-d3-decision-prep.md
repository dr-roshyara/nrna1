# C-10 — Decision 3 (authoritative state & lifecycle) · **DECISION-PREP, NOT THE DECISION**

**Work item:** KOS-AIP04-DISCOVERY-001 · **Prepared by:** Governance (`b64828fe`), on the PO/ARB act 2026-08-19
**Evidence base:** the canonical analysis (§5.1–5.4, A1.3) · **D1** · **D2**. ⛔ No second C-10 model.

> ⚠️ **The act says "Select A, B, or C" but names no option.** Governance recommends; it does not decide. **One line naming A, B or C lands it.**

---

## 1 · The framing constraint D2 imposes

**D2 = `NOT YET ESTABLISHED`, and the act forbids deciding existence beyond it.** So this decision cannot assert that C-10 *currently holds* authoritative state — it can only **specify the boundary** of what state/lifecycle would be C-10's.

⚠️ **This matters for option B in particular.** Per the D2 registration §6, **adopting the receipt as authoritative state would supply limb ① of the estate's existence test** (an adopted commitment about C-10). **A decision framed as constitutive would decide existence by the back door** — which this act forbids. **Framed as a boundary specification, it does not.**

## 2 · ⭐ The sharpest finding — D1's own subject-binding is not attestable

**D1 makes the receipt authoritative for the claim that a package was *"delivered to and acknowledged by a specified session execution at a specified time."***

> ## ⛔ **The platform cannot attest "a specified session execution."**

`INV-ATTR-1`: identity is **evidential only; no gate reads it.** `INV-ATTR-2` (**adopted**, `P-1`–`P-6`): *"self-declared identity must never be represented as independently attested."* And this work item has spent a day demonstrating it — a false identity claim (`ee77c6c2`), its erratum (`1f86623e`), and a provenance reconciliation that had to establish authorship from **write-class evidence** because the platform could not say who produced what.

⇒ **A C-10 receipt's binding to its subject session is only as strong as `INV-ATTR-2` permits — self-declared, not attested.** This does not defeat D1: the receipt still authoritatively carries *delivery* and *possession* (`OQ-J` claims 1–2). **But identity binding must NOT be placed inside C-10's authoritative state**, because C-10 cannot establish it. **It is `C-5`-shaped, and `C-5` owns no attestation record either.**

**Consequence: option B is unsound as written** — its list includes **identity binding**, which no capability in this estate can currently hold as authoritative state.

## 3 · The three options, tested

| | Assessment |
|---|---|
| **A — NO** | ⚠️ **Weak, and it creates the defect it avoids.** A says the lifecycle *"belongs elsewhere"* — but **§5.1 shows acknowledgement, receipt and monitoring/invalidation all have owner = 🔴 nobody.** A names no elsewhere, so it **assigns authoritative state to a vacuum** — precisely the `EKS-01` condition (*"Recording a rule is not sufficient"*). It also violates the standing DDD rule: **resolve ownership before protecting an invariant.** |
| **B — YES** | ⛔ **Over-reaches on two of its seven items.** **identity binding** — impossible (§2). **dispute** — the analysis's own bounded-context threshold is *"attestation records, assurance levels, exceptions, **disputes** and their adjudication"*; granting C-10 dispute adjudication **prejudges bounded-context status**, which this act forbids. Also, as an unconditional YES it is the constitutive form §1 warns against. |
| **C — SPLIT** | ✅ **Fits every decided constraint.** It matches **`OQ-J`'s 2-of-5 result** (receipt carries delivery + possession only); it matches **D1** (applicability by reference only, correctness not established); and applicability / transport / execution are **already owned or already excluded** — BC-6 `CAP-01` bootstrap, BC-7 `tokenRef` leakage (a defect, not a design), and the session's own lifecycle. |

⚠️ **One distinction not to collapse:** §5.2's *smallest coherent boundary* = **applicability + receipt** is about the **capability's scope**. C's *"applicability remains upstream"* is about the **receipt's authority**. **Different questions** — and D1 has already settled the second. Choosing C does **not** contradict §5.2.

## 4 · Recommendation — **C (SPLIT)**, with the boundary stated exactly

> ### **Recommended: C — C-10 would own the receipt/attestation lifecycle and state, and nothing else.**

**WOULD OWN** *(as authoritative state, conditional on realization — D2 unchanged)*

| State | Scope |
|---|---|
| **receipt identity** | the identifier of a receipt instance |
| **receipt lifecycle state** | issued → valid → **superseded / invalidated** |
| **invalidation** | the transition triggered when the referenced context version is superseded |
| **completeness** | whether a receipt exists for each context version a governed act requires at START |

**WOULD NOT OWN**

| Excluded | Why |
|---|---|
| **applicability determination** | upstream; **D1** — reference only; `OQ-J` claim 3 is *"a judgement, and unowned"* |
| **delivery transport** | **BC-6** (`CAP-01` bootstrap); BC-7's `tokenRef` leak is a **defect**, not an ownership claim |
| **session execution** | the session's own lifecycle (**BC-7**) |
| ⛔ **identity binding** | **`INV-ATTR-1`/`INV-ATTR-2`** — §2. Not holdable by C-10, or by anything in the estate today |
| ⛔ **dispute adjudication** | unowned; assigning it **prejudges bounded-context status** — forbidden by this act |
| **execution authority** | a **gate's** verdict — `C-14`-shaped (`OQ-J` claim 4) |
| **compliance / application** | `ContextApplied`, `ContextVerified` — **different claims, different evidence**; no receipt can supply them (`OQ-J` claims 3–5) |

**The three prohibitions are preserved:** **receipt ≠ application · receipt ≠ understanding · receipt ≠ compliance.**

### 4.1 · The wording that keeps C non-constitutive

Recommended form, if C is selected:

> *"C-10 would own the receipt lifecycle and state described above **if and when C-10 is realized**. This decision specifies the boundary; it does not establish the capability, and D2 (`NOT YET ESTABLISHED`) is unchanged."*

⚠️ **Without that clause, selecting C or B risks being read as satisfying limb ① and thereby moving D2** — the back-door existence decision this act forbids.

## 5 · Explicitly not decided here

⛔ C-10 existence beyond D2 · bounded context · ownership / stewardship · `OQ-K` · `OQ-B` (who owns applicability) · implementation technology · build order · **D4** (behaviour when knowledge or receipt is missing / stale / invalid / disputed) · **and the verdict itself.**

**Traceability:** PO/ARB act 2026-08-19 (D3) · **D1** · **D2** + its registration §6 (the two-limb test; the constitutive-decision warning) · canonical analysis §5.1, §5.2, §5.3 pt 22, A1.3 (seven states, `I-K1`, `OQ-J`) · `INV-ATTR-1`/`INV-ATTR-2` (adopted `P-1`–`P-6`) · `EKS-01` · BC-1 / BC-6 / BC-7 · `CAP-01`
