# EKS-11 — The "sole writer" is redirectable by environment: an asymmetric, unbounded test seam

**Status:** **BACKLOG · ARCHITECTURE — HELD BY STANDING DECISION** — the hold was recorded 2026-08-23 with the `REPAIR-001` authorization (*"`F-5` is HELD OUTSIDE this repair, pending a separate Architecture decision"*); registered here 2026-08-24 on the PO/ARB act *"F-5 → open Architecture matter"*. ⛔ **Not commissioned; starting requires a human authorization act.**
**Class:** architecture problem — assurance boundary / test seam (`CMP-004`).
**Not:** a privilege-escalation vulnerability · a defect introduced by `REPAIR-001` · an adopted rule.
**Registered by:** Governance (`5928b9f9`). ⛔ **Decides nothing; this is a tracking home for an existing hold, not a new finding.**
**Source finding:** `F-5`, independent verification 2026-08-23; **re-confirmed untouched** as `RV-O3`, 2026-08-24.

---

## 1 · Problem

`mechanismPath()` returns `getenv('KOS_MECHANISM_PATH') ?: MECHANISM`. **Both reads and writes go wherever it points.** The equivalent paths in `AST-017` and `AST-018` are **hard constants** — so the seam is **asymmetric across sibling assets**, and **no `GO` test bounds it**.

Consequently the docblock's unconditional claim that *"AST-015 is the sole interpreter and sole writer"* **is not literally true**, and a reader of the contract would not know the seam exists.

## 2 · Evidence

- Pointing the variable at a foreign script: that script was invoked for `fold`, and its **fabricated facts were consumed as authoritative** (`AST-019` refused `NOT_ELIGIBLE` on the spy's invented lane).
- **`RV-O3` (2026-08-24):** `git diff b9369797 d8a5ee93` touches `mechanismPath()` **zero** times — the repair did **not** quietly settle this. The same re-verification then used the seam as its own **test vehicle**, which is direct evidence that it is live and useful.

## 3 · The genuine tension — why this is a decision and not a fix

**It is not a privilege escalation:** anyone who can set the environment can already execute code. **And it is the seam that makes hermetic verification possible** — `GO-20`, the `HERMETIC-AST019-*` stores, and the `rv-race.php` probe all depend on it. Removing it would weaken the estate's ability to verify its own capabilities.

**So the question is not "close it?" but "what is it?"** — a bounded, documented test seam, or an undocumented hole in a sole-writer guarantee. **Today it is documented as neither.**

## 4 · Candidate directions (none chosen)

State the override in the docblock as a **bounded test seam** and bound it with a `GO` test · and/or gate it behind an explicit test-mode flag · and/or resolve the **asymmetry** by deciding deliberately whether `AST-017`/`AST-018` should expose the same seam. **The decision belongs to Architecture.**

## 5 · Non-actions

Nothing implemented · `mechanismPath()` unchanged · `AST-019` not reopened; its `ADOPTED`/`AUTHORIZED` states untouched · no ADR · the standing hold is preserved exactly as recorded.

**Traceability:** `F-5` in `../reviews/2026-08-23-KOS-OPERATING-MODEL-001-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` §12 · `RV-O3` in `../reviews/2026-08-24-…-REPAIR-001-INDEPENDENT-RE-VERIFICATION.md` §14 · the hold `../reviews/2026-08-23-KOS-OPERATING-MODEL-001-AMENDMENT-001-REPAIR-001-AUTHORIZATION.md` §5 · scope determination `../reviews/2026-08-24-…-REPAIR-001-RE-VERIFICATION-DETERMINATION.md` §4–5
