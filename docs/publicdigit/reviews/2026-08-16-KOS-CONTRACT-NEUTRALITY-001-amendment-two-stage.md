# KOS-CONTRACT-NEUTRALITY-001 — Amendment 1: two stages, Stage 2 contingent

**2026-08-16 · Session 2 (Governance)** · **Amendment registered. Stage 1 lane created. ⛔ Stage 1 NOT STARTED — the START is the PO/ARB's act.**

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #8
 Responsibility : governance
 Operator       : Session 2 (Governance)                    [declared]
 Approver       : PO/ARB — two-stage amendment 2026-08-16    [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

---

## 1 · The amendment, registered verbatim

> *"Amend `KOS-CONTRACT-NEUTRALITY-001` **before executing the START**. The existing LCOM4 implementation has been identified as potentially incorrect… *
> ***Stage 1 — Reference verification:*** *independently verify the existing PHP LCOM4 implementation against the existing contract, algorithm decisions, golden fixtures, and expected results.*
> ***Stage 2 — Python conformance:*** *only if Stage 1 passes… **PHP result = Python result = expected result**.*
> *The experiment **must not silently treat the current PHP implementation as the definition of correctness**.*
> *If Stage 1 fails, stop and report one of: reference implementation defect, contract ambiguity/defect, fixture/expectation defect.*
> *Do not modify the PHP implementation, contract, fixtures, or expectations within this work item. Any correction requires a separate governed act.*
> *The existing self-verification separation remains: the implementation lane must not certify its own result."*

## 2 · ⏱️ Timing — reported, not glossed

**The amendment says "before executing the START." The START was already registered** at seq 3, on the PO/ARB's explicit instruction in the immediately preceding act. **The log is append-only: it stands and cannot be withdrawn.**

> **What matters more, and is measured:** **no implementation work had been performed when the amendment landed.**
>
> | | |
> |---|---|
> | Python files written | **0** (tracked and on disk) |
> | Changes to contract / fixtures / expectations | **0** |
> | Changes to the PHP implementation | **0** |
>
> **Only reading had occurred.** The amendment therefore lands **before any work product exists** — which is the outcome it was aiming for, reached by a different route. **Nothing needs unwinding.**

## 3 · 🔑 The structural problem the amendment exposed — and how it was resolved

**Stage 1 is a VERIFICATION act. The started lane is `role: implementation`. `R8` makes role immutable per assignment.**

> **So the implementation lane could not perform Stage 1 at all.** Had Governance simply "done Stage 1" under the existing lane, it would have performed verification under an implementation grant — the exact category error this programme has spent two days eliminating.

**Resolved by creating a separate verification assignment**, not by stretching the existing one:

| Act | Value |
|---|---|
| Amendment grant | **`G-KOS-CONTRACT-EXP-AMD1`** — the two-stage structure and Stage 2's contingency |
| **Stage 1 assignment** | seq **4** — `S1-verification-lcom4-reference`, role **`verification`** |
| Stage 1 grant | **`G-KOS-CONTRACT-STAGE1-VERIFY`** |
| Handoff | seq **5** — `S3-implementation-…` **→** `S1-verification-…` |
| Stage 1 START | ⛔ **NOT performed — the PO/ARB's act** |

### 3.1 · The mechanism refused an incorrect act, and was right

Governance first attempted a **bootstrap handoff** (`from: null`) to the verification lane. **`AST-015` refused it:**

```
refused: bootstrap handoff (from=null) is only valid while no owner exists
```

**Correct refusal.** `S3-implementation-…` was `ACTIVE` and held mutation ownership, so a bootstrap handoff would have created a second entry point while an owner existed (Inv C). **The mechanism forced the right act: a handoff *from* the owner.**

> ⭐ **This is the first production use of the "Option C sequential interrupt" pattern** identified in `KOS-EXEC-TOPOLOGY-001`'s architecture proposal — implementation hands off to another role mid-work, rather than a second lane running concurrently. **It was recommended there on analysis; it has now been exercised, and the mechanism enforced it rather than merely permitting it.**

### 3.2 · Consequence for Stage 2 — `R8`

`S3-implementation-contract-neutrality` is now **`HANDED_OFF`**. **Per `R8`, resuming implementation for Stage 2 requires a NEW SessionAssignment** — the handoff reserves none. **Stage 2 is not merely gated on Stage 1's verdict; it has no lane to run in until one is registered.** That is a stronger guarantee than a written contingency.

## 4 · Stage 1 scope

**Independently verify** that the existing PHP LCOM4 collector produces correct results against: the contract's `_variant` statement · the **five pinned algorithm decisions** · the **seven golden fixtures** · `expected.json`.

**Required output:** a verdict — and on failure, **exactly one** classification:

| | Classification | What it implies |
|---|---|---|
| **1** | **Reference implementation defect** | The PHP is wrong; the contract stands |
| **2** | **Contract ambiguity/defect** | ⭐ **Improve the CONTRACT, not the architecture** |
| **3** | **Fixture/expectation defect** | The pinned expectations are wrong |

**🔴 Binding: do NOT treat the PHP implementation as the definition of correctness.** Verify it **against** the contract and fixtures — the whole point of the amendment.

**Excluded:** modifying the PHP implementation, contract, fixtures or expectations *(any correction is a separate governed act)* · writing any Python · Stage 2 · target-architecture work · `KOS-ARCH-BASELINE-001` · Election work · self-certification · completing its own assignment (`G-1`).

## 5 · ⚠️ An independence question the amendment creates — surfaced, not resolved

**`R-34` is satisfied for Stage 1:** the PHP collector was **not** written by this process, so verifying it is not self-verification.

**But a subtler question arises for Stage 2:** if the same process performs Stage 1 verification *and* Stage 2 implementation, then **the implementer will have previously certified the reference it implements against.** That is not an `R-34` breach — different artifacts — **but it is the same family of concern**, and it is exactly the class `DEC-2`/`A-5.2` were created for.

> **Governance does not resolve this.** It notes that **if the PO/ARB wants Stage 1 and Stage 2 performed by different processes, that must be decided when Stage 2's assignment is registered — not after.** Recorded now so the choice is available rather than discovered late.

## 6 · What has NOT been done

**No Stage 1 START** · **no Python written** · no change to the PHP implementation, contract, fixtures or expectations · no Stage 2 assignment (`R8` — it does not exist) · no verdict formed · `KOS-ARCH-BASELINE-001` untouched · no Election artifact touched · `AST-015` `e19705ce` / `AST-016` `00c68cc9`.

**Work item state:** `S3-implementation-…` **HANDED_OFF** · `S1-verification-…` **CREATED** · `mutationOwner: NULL` · resolver reports **`AMBIGUOUS`** (two assignments, neither operable) — **correct, and it will resolve once Stage 1 is started.**

---

*Technical references: PO/ARB amendment 2026-08-16 (§1 verbatim) · seq 3 START (registered before the amendment, on the preceding PO act) · seq 4 REGISTER · seq 5 HANDOFF · grants `G-KOS-CONTRACT-EXP`, `G-KOS-CONTRACT-EXP-AMD1`, `G-KOS-CONTRACT-STAGE1-VERIFY` · refused bootstrap handoff (Inv C) · `R8` role immutability · `R-34` · `A-5.2` · Option-C interrupt pattern (`KOS-EXEC-TOPOLOGY-001` ADP) · contract at `scripts/observations/examples/lcom4/`.*
