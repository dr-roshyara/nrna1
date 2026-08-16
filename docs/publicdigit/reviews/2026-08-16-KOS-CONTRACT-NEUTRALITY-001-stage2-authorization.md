# KOS-CONTRACT-NEUTRALITY-001 — Stage 2 authorized
# Python contract-neutrality experiment against the corrected, verified contract

**2026-08-16 · Session 2 (Governance)** · **Lane created · ⛔ NOT STARTED — the START is the PO/ARB's act.**

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #14
 Responsibility : governance
 Operator       : Session 2 (Governance)                   [declared]
 Approver       : PO/ARB — Stage-2 authorization 2026-08-16 [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

---

## 1 · The authorization, registered verbatim

> **"AUTHORIZE a new Python Stage-2 contract-neutrality experiment for the corrected and independently verified LCOM4 capability."**

## 2 · The premise was checked before acting — and it holds

**The authorization asserts the capability is "corrected and independently verified."** Governance verified that rather than assuming it:

| Claim | Evidence |
|---|---|
| **Corrected** | `286cad1e` — the approved correction applied: seven pinned decisions, ten fixtures, conformed reference |
| **Independently verified** | `9894febf` — **VERDICT: PASS**, **24 falsification cases, none broke it** |
| **By a different process** | ✅ **`R-34` satisfied.** This process drafted and applied the correction and **declined to verify it**; another process performed the verification |
| **Verifier changed nothing** | ✅ `scripts/observations/` clean; its commit touches only its own report |
| **Quality of the verification** | It **read the contract first and used it as the oracle** — not the implementation — and **disclosed its own tooling error** (a false *"15 mismatch"* report from its own comparison script) |

> **The premise is true. That is worth stating explicitly, because it has not always been** — earlier in this programme a referenced finding (`V-4`) had no referent, and a backlog item asserted as open had already been fixed. **Here the claim checks out on every leg.**

**One piece of bookkeeping was owed and is now done:** the verification lane was still `ACTIVE` and holding mutation ownership — **the verdict existed in prose while the lifecycle stayed open.** That is the recurring `E-2` pattern. `COMPLETE` recorded at seq 11; ownership released.

## 3 · What was registered

| Act | Value |
|---|---|
| **Work item** | `KOS-CONTRACT-NEUTRALITY-001` — **the existing experiment**, reused, not duplicated |
| **Assignment** | seq **8** — `S3-implementation-python-stage2`, role `implementation`, predecessor `S3-implementation-contract-neutrality` |
| **Grant** | **`G-KOS-CONTRACT-STAGE2`** — `AUTHORIZED` |
| **Handoff** | seq **9** — bootstrap (ownership was unheld) |
| **START** | ⛔ **NOT performed — the PO/ARB's act** |

**A new assignment, not a new work item.** `R8` required a fresh `SessionAssignment` because the original Stage-2 lane is `HANDED_OFF` — but the *work item* is the contract-neutrality experiment itself and was never closed. **Creating a second work item would have fragmented one experiment across two records.**

## 4 · The question — now genuinely worth asking

> **Can the corrected language-neutral LCOM4 contract be implemented independently in Python and produce the same observations as the verified PHP reference?**

**This is a stronger experiment than the original**, and the reason is precise: the reference is no longer merely *the thing that exists* — it has been shown to conform to a **deliberately defined** contract. In the original design, a Python/PHP agreement would have proved only that two implementations agreed; **it could not have distinguished "the contract is sufficient" from "both implementations guessed the same way."**

**Method binding, and it is the crux:**

> 🔴 **Implement FROM THE CONTRACT TEXT — not by porting `Lcom4Collector.php`.** Porting would test transliteration rather than whether the written contract is sufficient, **which is the entire question.**

**Required outcome classification:** `pass` (contract is language-neutral) · `fail-by-contract-ambiguity` (**still under-specified — improve the CONTRACT**) · `fail-by-implementation-defect` (contract sound, Python wrong).

**Forbidden:** new platform architecture · packaging (`pyproject`/`setup`/`requirements`) · repository split · service boundary · database · API · Python framework · migration · replacing PHP · new EKS design · modifying the contract, fixtures, expectations or the PHP implementation · touching `KOS-ARCH-BASELINE-001` or `KOS-LCOM4-CONTRACT-001` · Election work (`A-8`) · self-certification · completing its own assignment (`G-1`).

**Hard stop:** once the comparison produces its evidence, **stop** and hand it to the ARB. **Evidence only — it adopts no language, opens no gate, and does not decide PHP's fate.**

## 5 · 🟠 A routing recommendation — and it is not `R-34`

> ### **Recommended: Stage 2 should be performed by a process that did NOT draft the corrected contract — i.e. not this one.**

**`R-34` does not require this.** `R-34` bars implementer→verifier of the same work; implementing Python is new work, so it is not barred.

**The reason is the experiment's own validity:**

> **This process wrote the contract's wording. Implementing from a contract you authored tests your memory of what you meant, not whether the words are sufficient for someone who wasn't there.** A pass under those conditions would be **the weakest possible evidence** — it could not distinguish a good contract from a good memory.
>
> **The entire value of Stage 2 is that an independent reader can reach the same answer from the text alone.** Handing it to the author quietly removes that.

**Recorded in the assignment's `executionContext`**, where a startup check will read it — as a **recommendation**, marked distinct from the `R-34` obligation that also applies (**whoever implements Stage 2 must not verify it**).

**Options:** route Stage 2 to a different process *(recommended)* · accept the weaker evidence knowingly · defer. **This is the PO/ARB's call, and it does not block the START.**

## 6 · State

**`KOS-LCOM4-CONTRACT-001`** — all three lanes terminal (`COMPLETED`/`HANDED_OFF`/`COMPLETED`), owner `NULL`. **The correction chain is finished: drafted → approved → applied → independently verified PASS.**

**`KOS-CONTRACT-NEUTRALITY-001`** — `S3-implementation-python-stage2` **CREATED**, awaiting the START. **0 Python files.** Contract, fixtures and reference untouched since the verified state (`Lcom4Collector.php` sha256 `4136519b…`).

---

*Technical references: PO/ARB authorization 2026-08-16 (§1 verbatim) · corrected application `286cad1e` · independent verification `9894febf` (PASS, 24 falsification cases) · `KOS-LCOM4-CONTRACT-001` seq 11 COMPLETE · `KOS-CONTRACT-NEUTRALITY-001` seq 8–9 · `G-KOS-CONTRACT-STAGE2` · `R8` · `R-34` · `G-1` · `A-8` · `INV-ATTR-2` · `E-2` pattern.*
