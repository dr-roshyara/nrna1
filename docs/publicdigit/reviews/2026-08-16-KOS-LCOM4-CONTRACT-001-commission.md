# KOS-LCOM4-CONTRACT-001 — contract correction commissioned (draft stage only)

**2026-08-16 · Session 2 (Governance)** · **Decision registered · lane created · ⛔ NOT STARTED — the START is the PO/ARB's act.**

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #10
 Responsibility : governance
 Operator       : Session 2 (Governance)                 [declared]
 Approver       : PO/ARB — cohesion-semantics decision 2026-08-16 [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

---

## 1 · The decision, registered verbatim

> **"Accepted recommendation: the cohesion metric should measure meaningful internal relationships between class behaviors, rather than depend on a particular programming syntax."**
>
> *"That means the contract should treat relevant internal calls consistently, including the `self::` / `static::` cases uncovered by verification, subject to the exact semantic wording being recorded in the contract. The existing implementation and fixture set should **not** be changed by the current verification lane; the contract correction needs its own governed act."*

**And the decision pattern the PO/ARB adopted for future work, registered because it now governs how Governance presents things:**

> **Evidence → business meaning → recommendation → your decision.**

## 2 · What was registered

| Act | Value |
|---|---|
| **Stage 1 lane closed** | `KOS-CONTRACT-NEUTRALITY-001` seq **7** — `S1-verification-lcom4-reference` **COMPLETE** by Governance (`G-1`); ownership released. **Explicitly not a correction** |
| **New work item** | **`KOS-LCOM4-CONTRACT-001`** — workflow `specification-correction`, four canonical roles |
| **Assignment** | seq **1** — `S4-architecture-lcom4-contract`, role `architecture` |
| **Grant** | **`G-KOS-LCOM4-CONTRACT-DRAFT`** — **DRAFT ONLY** |
| **Handoff** | seq **2** — bootstrap |
| **START** | ⛔ **NOT performed — the PO/ARB's act** |

**A separate work item, not a lane inside the experiment** — because the experiment's own grant says *"any correction requires a SEPARATE governed act."* Folding it in would have let the experiment correct the thing it was measuring.

## 3 · Scope — **draft only**

**The lane drafts a proposal. It does not change anything.**

1. **The sixth pinned decision** — defining what constitutes an **intra-class call** for edge purposes, giving effect to the accepted direction.
2. **Precisely how `self::` and `static::` are treated**, and whether any other syntax (dynamic calls, first-class callables, `parent::`) is in or out — **with the reason recorded for each**.
3. **The golden fixture(s) that would exercise the case** — since **no existing fixture does**.
4. **The expected values those fixtures would carry, and why** — **derived from the wording, not from the current implementation.**
5. **Which existing expectations, if any, change.**

> 🔴 **Binding: do NOT derive the wording from what the current PHP implementation happens to do. That inversion is the defect being corrected.** The contract must say what the metric *means*; the implementation must then conform to it — not the reverse.

> 🔒 **The accepted direction is the PO/ARB's decision and is not to be re-litigated.** Only its **exact wording** is being drafted. If drafting reveals that the direction cannot be stated coherently, **that is a finding to report — not a licence to choose a different direction.**

**Excluded:** modifying the contract, `expected.json`, the fixtures or the PHP implementation *(this lane drafts; application is a separate act after approval)* · writing Python · Stage 2 · re-verifying the PHP reference · target-architecture work · `KOS-ARCH-BASELINE-001` · Election work · self-certification · completing its own assignment (`G-1`).

## 4 · Required sequence after approval — recorded now, so it is not improvised later

```
draft wording  →  PO/ARB approval  →  apply correction (implementation)
      →  RE-VERIFY the PHP reference against the CORRECTED contract (verification)
      →  only then may KOS-CONTRACT-NEUTRALITY-001 Stage 2 be re-commissioned
```

**Three constraints carried into that sequence:**

| | |
|---|---|
| **`R-34`** | The process that **drafts or applies** the correction **must not** perform the re-verification. Written into the assignment's `executionContext`, where a startup check will read it |
| **`R8`** | Stage 2 has **no lane** — `S3-implementation-contract-neutrality` is `HANDED_OFF`. Re-commissioning Stage 2 requires a **new assignment**, not a resumption |
| **Ordering** | Re-verification comes **before** Stage 2, not after. The whole point of the amendment was that Python must not be tested against an unverified reference |

## 5 · Why the correction is not being applied now

**The finding is one week old in evidence terms and two hours old in the record — and the fix is genuinely small.** It would be easy to edit `expected.json`, add a fixture, and patch line 61.

> **That is exactly what the experiment's grant forbids, and for a good reason: the lane that discovered the gap would then be defining the correctness it discovered the gap against.** The separation costs one approval cycle and buys a contract whose wording was decided deliberately rather than reverse-engineered from a line of PHP.

**Governance also notes what it has NOT done:** it has **not** drafted the wording, **not** proposed the fixture, and **not** stated what the corrected values should be. **Those are the commissioned work, not the commission.**

## 6 · State

**`KOS-CONTRACT-NEUTRALITY-001`:** `S3-implementation-…` `HANDED_OFF` · `S1-verification-…` **COMPLETED** · owner `NULL` · **Stage 2 blocked and lane-less.**
**`KOS-LCOM4-CONTRACT-001`:** `S4-architecture-lcom4-contract` **CREATED** · **awaiting the PO/ARB START.**

**Untouched:** contract · `expected.json` · fixtures · `Lcom4Collector.php` · 0 Python files · `AST-015` `e19705ce` · `AST-016` `00c68cc9` · `KOS-ARCH-BASELINE-001` · Election.

---

*Technical references: PO/ARB decision 2026-08-16 (§1 verbatim) · Stage 1 verdict `29b3280f` · `KOS-CONTRACT-NEUTRALITY-001` seq 7 COMPLETE · `KOS-LCOM4-CONTRACT-001` seq 1–2 · `G-KOS-LCOM4-CONTRACT-DRAFT` · `R-34` · `R8` · `G-1` · `A-7`/`GOV-HUMAN-01` · `INV-ATTR-2` · contract at `scripts/observations/examples/lcom4/expected.json`.*
