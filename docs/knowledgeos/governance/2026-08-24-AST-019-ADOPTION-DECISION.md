# `AST-019` — **ADOPTION DECISION** · ✅ **ADOPTED**

**Asset:** `AST-019` / `ActivateCommissionedFreshSession` — `.claude/scripts/activate-commissioned-fresh-session.php`
**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Component:** `CMP-004` · **Governance tier:** 2
**Date:** 2026-08-24 · **Recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(recording the PO/ARB's act; Governance does not adopt — `R-34`/`EP-02`)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **This record covers ADOPTION ONLY.** Authorization for future use is a **separate act**, recorded separately in `2026-08-24-AST-019-AUTHORIZATION-DECISION.md`. `§38` forbids collapsing the two, so neither is inferred from the other here.

---

## 1 · The decision (verbatim)

> **"1 and 2"** — *"1. Accept / adopt AST-019"* and *"2. Authorize for future use"*, then confirmed: **"both"**.
> — PO/ARB, in-session, 2026-08-24

**This record acts on part 1 only.**

## 2 · What is adopted

**`AST-019` / `ActivateCommissionedFreshSession`** — the constrained fresh-session self-binding capability, domain concept **`BindRuntimeToRequestedResponsibility`**. A fresh runtime may bind itself to an *already-commissioned* responsibility through the canonical mechanism: **human declares the intended responsibility · runtime declares its process identity · the governed bootstrap binds the two.** It never self-chooses role, scope, work item, or authority.

Adopted **as repaired** — at commit `d8a5ee93`, not as originally implemented.

## 3 · The condition precedent, and that it is satisfied

The PO/ARB's own §38 act of 2026-08-23 attached exactly one condition:

> *"Keep AST-019 / AMENDMENT-001 not adopted and not authorized **pending independent verification**."*

**That verification has occurred.** Independent re-verification by a fresh lane (`be8aecec`) returned **PASS WITH FINDINGS** on 2026-08-24 (`seq 14`). The condition is discharged. Nothing else was ever attached to it.

## 4 · The evidence this adoption rests on

**It does not rest on green tests.** The verifier established the pre-repair failure **itself**, from committed pre-repair source `b9369797` in a disposable worktree, then established post-repair success **on the authoritative record rather than on the `ACTIVATED` result string**:

| | Pre-repair (`b9369797`) | Post-repair (`d8a5ee93`) |
|---|---|---|
| `check` | `READY` | `READY` |
| `activate` | `exit 65` · `INCOMPLETE_SEQUENCE` · `written=[REGISTER]` | `exit 0` · `ACTIVATED` |
| `REGISTER.predecessor` | **`null`** (wrong) | **`'OWNER'`** |
| `HANDOFF.from` | refused by `AST-015` | **`'OWNER'`** |
| record | **orphan stranded `CREATED`** | exactly 3 transitions · lane `ACTIVE` · ownership moved · **no orphan** |
| `--json` under `display_errors=1` | unparseable | valid, zero diagnostics on STDOUT **and** STDERR |

**`O-1` — the blind spot that let the original defect hide — is closed on evidence, not by assertion:** `GO-26`…`GO-30` were independently confirmed **5/5 RED against pre-repair code** (56 assertions) and green at HEAD, with a test diff of **+293/−0**, so `GO-01`…`GO-25` were not weakened to achieve it.

**Counts:** `30 / 402` AST-019 · `152 / 1709` full WorkflowEngine regression, **no new failures** · `43 / 409` L3.
**Boundaries preserved:** `AST-015`/`016`/`017`/`018` and `operating-model.php` **byte-identical** · adopted `L1`/`L2`/`L3` unchanged · `AST-015` sole writer (3 appends, no raw store access, no second fold) · `Inv E` CONTINUATION count still **0** · human `START` gate intact.

**Separation of duties held at every step:** producer `1899d8bf` ≠ implementer `84e5c1f7` ≠ first verifier `84c0f6f6` ≠ re-verifier `be8aecec` ≠ scoping Governance `5928b9f9`. The re-verifier's bars were enforced through `AST-018 appoint --exclude`, so eligibility is recorded **by the mechanism** rather than by an author's judgement.

## 5 · Findings carried, not resolved

**Three non-blocking findings are carried into adoption.** No rule makes a non-blocking finding block adoption, and this estate's practice is the opposite — `AST-016` was **qualified with two conditions** (2026-08-15) and the parent `KOS-OPERATING-MODEL-001` was **adopted with `F-2`/`F-4`/`F-5` open**.

| Finding | Why it does not block adoption |
|---|---|
| **`RV-F1`** — orphan `REGISTER` if ownership moves between analysis and write | **Not a regression; a strict improvement.** Pre-repair the identical orphan fired **deterministically on every live-owner activation**; post-repair it requires a genuine concurrent ownership mutation, and fail-closed is now **tested rather than asserted**. The non-atomicity is a pre-existing architectural property of a three-append sequence. **It bears on *use*, and is therefore addressed in the authorization record.** |
| **`RV-F2`** — committed tests don't cover *partial write **with** a live owner* | **No defect.** Behaviour is correct and all four failure paths were exercised with correct `OWNER` provenance; the gap is that the assurance rests on the report rather than a committed regression test. |
| **`RV-O3`** — `F-5` still open, unrepaired, unmitigated | **Compliance with a standing decision, not a finding against the repair.** The repair diff touches `mechanismPath()` **zero** times — evidence of scope discipline. |

**"PASS WITH FINDINGS" is not a qualified rejection.** The verifier states the reason for the wording: it is not plain `PASS` *"merely because the tests are green"*.

## 6 · What this adoption does **not** do

- **It does not authorize use.** See the separate authorization record.
- **It does not resolve `RV-F1`, `RV-F2`, or `F-5`**, and it creates no follow-up work item — none was commissioned.
- **It does not resolve** `ASD-001`, `O-4`, `O-6`, `Q-1`, `Q-2`, or `REVIEW_INDEPENDENCE_POLICY §22`.
- **It does not reopen or rewrite** the first verification's **FAIL** verdict, which stands as history.
- **It does not reopen** the parent `KOS-OPERATING-MODEL-001` (`L1`+`L2`+`L3` `ADOPTED` · `AUTHORIZED`).
- **It creates no wiring.** No hook, no `SESSION_START` path.

## 7 · State after this decision

```
AST-019 / AMENDMENT-001    IMPLEMENTED · VERIFIED · ADOPTED · AUTHORIZED: see the separate record
```

**Registry updated:** `AST-019` `adoption: verify → adopted`, with a `verified:` block (date + method) and a `finding_open:` block carrying `RV-F1`/`RV-F2`/`RV-O3`.

**Traceability:** PO/ARB act 2026-08-24 (§1) · decision preparation `…-AST-019-ADOPTION-DECISION-PREPARATION.md` · re-verification `…-REPAIR-001-INDEPENDENT-RE-VERIFICATION.md` (PASS WITH FINDINGS, `seq 14`) · repair `d8a5ee93` · grant `G-REPAIR-001` · first verdict `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (FAIL) · condition precedent `2026-08-23-KOS-OPERATING-MODEL-001-AUTHORIZATION-DECISION.md:15` · adoption-separation precedent `2026-08-23-KOS-OPERATING-MODEL-001-ADOPTION-DECISION.md:41` · `AST-016` qualification-with-conditions (2026-08-15) · `§38` · `R-34`/`EP-02` · `ES-004.3`
