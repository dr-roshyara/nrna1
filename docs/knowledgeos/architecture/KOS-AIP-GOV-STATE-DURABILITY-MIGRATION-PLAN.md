# `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN`

**Status: 🟡 PROPOSED · AMENDED (AMD3 + AMD4 + AMD5 + **AMD6**, 2026-08-21).** Planning only — **no migration executed, no file moved, no default changed, no `.gitignore` or `.gitattributes` touched, no authority record modified.**
**Canonical governed aggregate (C-9, verified before writing):** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · workflow `architecture-adr`. ⛔ **`KOS-AIP-GOV-STATE-DURABILITY` is the TRACK LABEL ONLY and is never the workflow aggregate key** — independently re-verified here: the only matching record is `.claude/runtime/workflow/KOS-AIP-GOV-STATE-DURABILITY-ADR.json`, `workItem` = `KOS-AIP-GOV-STATE-DURABILITY-ADR`, `workflow` = `architecture-adr`. ⛔ **No aggregate was created, initialised or forked by this amendment.**
**Lane:** `S4b-architecture-gov-state-impl-design` (seq 4 REGISTER · 5 HANDOFF · 6 START) · **Grants:** `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN` **+ AMD1 + AMD2** · ⭐ **AMD6's COMMISSION is registered in FOUR grants** — `…-AMD6` (`C-1`…`C-5` + the binding `C-4` refinement) · `…-AMD6-C6-C8` · `…-AMD6-C9-C11` · `…-AMD6-C12` · ⚠️ **and that is the COMMISSION, not the amendment: AMD3, AMD4, AMD5 and AMD6 are ALL UNREGISTERED AS DELIVERED AMENDMENTS — §0.2, §11.1 (`OPEN-M6`, now four deep)**
**Date:** 2026-08-19 · **amended 2026-08-20 (AMD3 · AMD4 · AMD5) · amended 2026-08-21 (AMD6).** **Producing process of the original plan, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b`. **Producing process of AMD3, AMD4, AMD5 and AMD6:** `claude-code-session:bc1b47ef` — ⚠️ **author of the technical review `a282d14b` whose `CL` clarifications AMD3 closed**, and ⭐ **AUTHORING-ELIGIBLE for AMD6 by `C-12`, which partially superseded `C-10` and restored exactly this process's authoring eligibility while leaving every other bar standing.** ⛔ **`C-12`, binding: this process is BARRED FROM REVIEWING AND FROM ACCEPTING AMD6.** ✅ **AMD4's `RC` findings are `claude-code-session:9c908e70`'s, AMD5's `RD`/`DI` findings are `claude-code-session:870305e0`'s, and AMD6's `RD-3·a`/`RD-3·b`/`RD-7·b`/`DI-4`…`DI-7` findings are `claude-code-session:ccf6c9c7`'s — all three INDEPENDENT of this process (§0.4.1, §0.5, §0.6).** **`R-34`/`P-2`: no producing process may verify or accept this plan.**

**Placement resolved through existing governance, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**. ⇒ the AMD2 exit-2 `PENDING`/escalate branch **did not fire**; `architecture/` matches the artifact class.

---

# 0 · Amendment 3 — ⚠️ **its two bounds first, because they limit what everything below may claim**

**Authorizing human act, cited by reference and not paraphrased into authority:** the PO/ARB instruction of 2026-08-20 registering the technical Architecture review and routing the next act — *"The next act should be an amendment to the migration plan that closes CL-1, CL-2, and CL-3"*, with the three secondary corrections named in the same act (authority transfer at Phase 5 · `KOS_MECHANISM_PATH` as a write-capable path · grants need their own reconciliation model).

## 0.1 ⛔ **BOUND 1 — AMD3 ADDRESSES `CL-1`/`CL-2`/`CL-3`. It does NOT close them.**

**AMD3 is written by `bc1b47ef`, which authored the technical review that raised `CL-1`–`CL-12` *and proposed the remedies now written in below*.** ⇒ **two `R-34` exposures, not one:**

| Exposure | Consequence |
|---|---|
| **the clarifications are this process's own** | ⛔ **it may not assess whether they are closed** — that is verifying its own work |
| ⭐ **the REMEDIES are also this process's own** | ⭐ **the remedies have never been reviewed by anyone.** They were *proposed* inside a review; a review cannot review its own proposal |

> ## ⭐ **CONSEQUENCE FOR THE NEXT REVIEWER, stated so it cannot be skipped: the next review must review the REMEDY, not merely check that text was added.**
> **A completeness pass confirming *"§6 now says X"* establishes nothing here, because the author of §6's new text is the author of the finding that demanded it.** ⇒ **the gates `CL-1`/`CL-2`/`CL-3` remain `⏳ ADDRESSED · NOT CLOSED` until a process that is neither `1c8b041b` nor `bc1b47ef` judges the remedy sound.**

## 0.2 ⚠️ **BOUND 2 — AMD3 carries NO GRANT, and this plan does not manufacture one**

**`AMD1` and `AMD2` each carry their own registered grant. There is no `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD3`.**

⭐ **The authority exists; its REGISTRATION does not.** The mechanism's own semantics settle which is which: *"a grant registers a recorded human act **by reference** — the record never manufactures authority"* (`workflow-state.php:326`, `G-2`/`R5b`). ⇒ **the human act above IS the authority; the grant would be its record.**
⛔ **Registration is a Governance act with exactly one writer** (`--writer-role=governance`, `G-2`/`R5a`) **and is NOT performed here** — this process holds an Architecture role in this chain, and registering its own authorization would be the manufacture the mechanism forbids.

⇒ **`OPEN-M6`: AMD3 must be registered by Governance before the amended plan is accepted.** *(Precedent for producing while a registration lags: the S4b lane's `executionContext` names `5e1dd9ee` while `1c8b041b` produced the plan — `INFO-2` of the self-review, disposed as *"a Governance act, not performed"*.)*

## 0.3 What AMD3 changes — the full list, so no change is silent

| # | Change | Closes |
|---|---|---|
| 1 | §1.1 · §10 criterion 1 — the corpus figures become **planning observations**; the acceptance object becomes a **frozen, committed manifest** | `CL-2` |
| 2 | §4 — **Phase 0 (declared write freeze)** added; Phase 1 produces the manifest; Phase 4 is **all-or-nothing**; Phase 5 gains a **re-hash** precondition | `CL-1`, `CL-2` |
| 3 | §6.3 · §10 criterion 2 — the completeness claim is **restated to what is provable**, and the *"dense ⇒ nothing lost"* inference is **withdrawn** | `CL-1` |
| 4 | §3 `INV-R5` · §4 Phase 4b — the act that **produces the resolver** is named, and criteria 6/7 are realigned to what the phases deliver | `CL-3` |
| 5 | §4 · §8 — **authority transfers at Phase 5**; Phase 6 **records** it; the rollback rule is corrected for the 5→6 window | `CL-4` |
| 6 | §4 — **two on-disk markers**: staging *non-authoritative*, runtime *demoted* | `CL-5` |
| 7 | §4 Phase 2b — **`.gitattributes` pin** required before Phase 3; manifest committed with the copy | `CL-6` |
| 8 | §6.4 — **grant reconciliation** by position-plus-content, ⛔ never by `grantId` | `CL-7` |
| 9 | §6.3 — CASE A's prefix comparison **specified** as element-wise over the decoded array | `CL-8` |
| 10 | §3 `INV-R1` — **split** into location-refusal (exit 65) and workflow-state `UNRESOLVABLE` (report, exit 0) | `CL-9` |
| 11 | §1.5 · §3 `INV-R3` — `P-4` restated as a **write-capable** path; reporting-vs-enforcing forms separated | `CL-10` |
| 12 | §2 · §5 — the two senses of *"durable"* separated | `CL-11` |
| 13 | §4 Phase 1 — the **record predicate** stated as exactly `*.json`, with quarantine | `CL-12` |

⛔ **AMD3 changes NO decision.** `B′` · `R-CONFLICT` · `OPEN-M3`'s Option A · placement governance · `INV-ORDER` · `Option D` — **all untouched.** ⛔ **`Increment 2` (locks, leases, hooks, enforcement) is NOT proposed** — §1.3 now *reports* a pre-existing writer property and this plan stops claiming a guarantee that contradicts it.

---

# 0.4 · Amendment 4 — closing the independent review's residuals

**Input, consumed and not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD3-ARCHITECTURE-REVIEW.md` — the **INDEPENDENT** AMD3 remedy review by `claude-code-session:9c908e70`, verdict **`PASS WITH DESIGN CLARIFICATIONS`**: **10 `CL` CLOSED · 2 ADDRESSED-NOT-CLOSED (`CL-5`, `CL-6`) · 0 unclosed**, with **`RC-1`…`RC-11`** residual and **`RC-1`/`RC-2`/`RC-3`/`RC-4` gate-class**.
**Authorizing human act, cited by reference:** the PO/ARB commission of 2026-08-20 — *"Produce the next amendment… MUST close or explicitly resolve the residuals identified by the independent AMD3 review."* ⚠️ **Also unregistered — `OPEN-M6` spanned AMD3 and AMD4 at this point in the lineage; ⭐ it now spans AMD3 + AMD4 + AMD5 + AMD6 (§11.1, AMD6 `C-5`).**

## 0.4.1 ⭐ **AMD3's §0.1 bound is now PARTLY DISCHARGED, and the discharge is asymmetric. That matters more than the disposition table.**

| | Then (AMD3) | Now (AMD4) |
|---|---|---|
| **who found the problems** | ⛔ **`bc1b47ef` — the same process that wrote the remedies** | ✅ **`9c908e70` — independent, no prior participation** |
| **had the remedies been reviewed?** | ⛔ **no — a review cannot review its own proposal** | ✅ **YES. That is exactly what `9c908e70` did, and 10 of 12 closed** |
| **who writes THIS amendment's remedies?** | — | ⛔ **`bc1b47ef` again** |

> ## ⭐ **So the exposure has CHANGED SHAPE rather than disappeared, and the honest statement is narrow:**
> ✅ **`CL-1`…`CL-12`'s remedies are no longer self-reviewed — an independent process tested them against the code and closed ten.** ⭐ **The `RC` findings AMD4 closes are SOMEONE ELSE'S findings, which is a materially stronger position than AMD3's.**
> ⛔ **But `RC-1`…`RC-11`'s REMEDIES are again written by the process that will not review them.** ⇒ **AMD4 does NOT declare any `RC` closed. It records them `ADDRESSED`, and a fresh independent review decides closure.** **The same bound, one level down — and it will recur until an amendment produces no new remedy.**

## 0.4.2 `RC-1`…`RC-11` disposition — ⭐ **every item ADDRESSED; ⛔ none self-closed**

| # | Class | Where AMD4 addresses it | Disposition |
|---|---|---|---|
| ⭐ **`RC-1`** | **gate · Phase 3** | §4 table — **`2b-pin` (before the copy) and `2c-commit` (at/after the copy) are now SEPARATE phases**; Phase 3's precondition is `2b-pin`, which no longer depends on Phase 3's output | **ADDRESSED — circularity removed** |
| ⭐ **`RC-2`** | **gate · Phase 4b** | §3 table — **row 1 binds the AUTHORITATIVE resolution; new row 2 states a governed override yields a NON-AUTHORITATIVE location and is NOT a boundary violation**; both contract suites' `--dir` usage quoted as the evidence | **ADDRESSED — pinned suites preserved** |
| ⭐ **`RC-3`** | **gate · Phase 4b** | §3 table — **reporting form marked ✅ *"THIS IS WHAT PHASE 4b IMPLEMENTS"*; enforcing form marked ⛔ *"DEFERRED TO `OPEN-M5`, NOT CURRENT"*** | **ADDRESSED — one current definition per row** |
| 🔴 ⭐ **`RC-4`** | **gate · Phase 7** | **§4.4 — a FINAL re-hash against `manifest + reconciled delta` is Phase 7's precondition**; mismatch ⇒ stop, reconcile, re-verify, never remove. **New acceptance criterion 12** · new §8 rollback trigger | **ADDRESSED — the destructive act is guarded** |
| **`RC-5`** | design | **§4.3 — Phase 5's internal order MANDATED: re-hash · readers · WRITER LAST**; the transfer instant is the writer switch; §8's boundary restated. ⚠️ **AMD6 (`DI-5`): this three-step enumeration is SUPERSEDED — the canonical order is `1 · 1b · 2 · 3 · 4 · 5` (§4.3). AMD4's form is retained here as its own history** | **ADDRESSED** |
| **`RC-5b`** | design | §3 `INV-R5` + §4 table row 8 — **Phase 4b delivers the resolver DORMANT; Phase 5 is the sole activating act** | **ADDRESSED** |
| **`RC-6`** | design | **§4.5 — directory-level · out-of-band · in-file marking FORBIDDEN by byte preservation · ⛔ never `*.json` · advisory reach stated** | **ADDRESSED** |
| **`RC-7a`** | coherence | §4 — the `INV-ORDER` preservation argument now enumerates **all three** additions, including `2b`/`2c` inside the 2–4 span | **ADDRESSED** |
| **`RC-7b`** | coherence | §4 — **the table is sorted by EXECUTION and numbered `1…11`; labels are lineage, row order is authoritative** | **ADDRESSED** |
| **`RC-7`** *(dependencies)* | coherence | **§11.1 — six prerequisites with actors**, including the Phase-0 and Phase-4b acts AMD3 introduced and did not carry | **ADDRESSED** |
| **`RC-8`** | design | **§4.6 — `text eol=lf` WITHDRAWN; `-text` only**, with the `CR` argument; hash semantics (SHA-256, working-tree bytes) recorded in Phase 1 | **ADDRESSED** |
| **`RC-9`** | wording | §6.3 — **canonical re-encode DEFINED as recursive key ordering**, comparison-only | **ADDRESSED** |
| **`RC-10`** | design brief | **§3.1 — refusal MUST precede `saveRecord`'s `mkdir`**, because a mis-resolved path *materializes* rather than failing | **ADDRESSED — recorded, not implemented** |
| **`RC-11`** | wording | **§1.3 — *"one writer of governance evidence"*, with the discriminating test** (*references `runtime/workflow` AND writes*) and the three candidates disposed | **ADDRESSED — closes `INFO-1`** |
| **`CL-5`** | reopened | closed via `RC-6` (§4.5) | **ADDRESSED** |
| **`CL-6`** | reopened | closed via `RC-1` (§4) and `RC-8` (§4.6) | **ADDRESSED** |

⛔ **AMD4 changes NO decision.** `B′` · `R-CONFLICT` · `OPEN-M3` Option A · placement governance · `INV-ORDER` · `Option D` — **all untouched.** ⛔ **`OPEN-M5` NOT decided** · ⛔ **no ledger** · ⛔ **no bounded context** · ⛔ **no `.gitignore` change** · ⛔ **no `.gitattributes` change** *(the `-text` pin is REQUIRED at Phase 2b-pin and NOT performed)* · ⛔ **no runtime code, including the Phase-4b resolver and the `RC-10` guard** · ⛔ **migration NOT executed.**

## 0.4.3 ⭐ The live-corpus rule, preserved verbatim from AMD3

**Planning counts are HISTORICAL OBSERVATIONS.** **At execution, Phase 1 MUST re-inventory the live corpus, and the resulting manifest is the execution acceptance object.** ⛔ **No current grant or transition count is hard-coded as universal truth** — §1.1 and §10 criterion 1.

## 0.4.4 Canonical-document rule

**One canonical CURRENT definition per section.** **Superseded wording remains ONLY where explicitly labelled** — §1.3's heading, §3's enforcing row, §4.6's withdrawn form, §6.3's withdrawn invariant, §8's superseded rule, §10's superseded criteria. ⛔ **Governance history is not deleted, and no two competing current definitions are left standing** — the one such defect the independent review found (`RC-3`) is repaired in §3.

---
---

# 0.5 · Amendment 5 — residual correction *(AMD4 review)*

**Input, consumed and not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD4-ARCHITECTURE-REVIEW.md` — the **INDEPENDENT** AMD4 review by `claude-code-session:870305e0`, verdict **`PASS WITH DESIGN CLARIFICATIONS`**: ⭐ **`RC-1`…`RC-11` ALL CLOSED**, with six new residuals — **three design gaps (`RD-7`, `RD-3`, `RD-10`) and three document-integrity defects (`DI-1`, `DI-2`, `DI-3`)**.
**Baseline:** AMD4 at **`0a2fa71d`** · **authorizing human act, cited by reference:** the PO/ARB commission of 2026-08-20 (*"AMD5 — MIGRATION PLAN RESIDUAL CORRECTION"*). ⚠️ **Also unregistered — `OPEN-M6` spanned AMD3, AMD4 and AMD5 at this point in the lineage; ⭐ it now spans AMD3 + AMD4 + AMD5 + AMD6 (§11.1, AMD6 `C-5`).**
**Producing process:** `claude-code-session:bc1b47ef` — ⚠️ **author of AMD3, AMD4 and the original technical review.** ✅ **`RD`/`DI` findings are `870305e0`'s, independent.** ⛔ **The §0.4.1 bound recurs unchanged: AMD5 records every item `ADDRESSED`, never `CLOSED`.**

## 0.5.1 Disposition — ⭐ **all six ADDRESSED; ⛔ none self-closed**

| # | Kind | Correction | Where |
|---|---|---|---|
| 🔴 ⭐ **`RD-7`** | design gap | ⭐ **Phase 5 gains STEP 5: RETRACT the staging `NON-AUTHORITATIVE` marker, ordered AFTER the writer switch.** Full marker lifecycle tabled; both windows shown closed; evidence bytes provably untouched. **New acceptance criterion 14** | **§4.5** · §4 rows 3 & 9 · §10 |
| 🔴 ⭐ **`RD-3`** | design gap | ⭐ **The Phase-7 mismatch disposition is SPLIT: CASE α (pre-writer-switch) reconciles under §6; CASE β (post-demotion write) is QUARANTINED, recorded and ESCALATED — never imported.** ⚠️ **AMD5's NAMES for these two branches were superseded by AMD6 — `PRE-SWITCH DISPOSITION` / `POST-DEMOTION DISPOSITION`, §0.6.2. The dispositions themselves are unchanged; this row is AMD5's history.** Tie-break stated: unestablishable ordering ⇒ treat as β. **New acceptance criterion 15** | **§4.4** · §4 row 11 · §10 |
| 🔴 ⭐ **`RD-10`** | design gap | ⭐ **Freeze semantics PARTITIONED: unrelated appends prohibited; the migration lane's OWN records explicitly permitted and DECLARED IN ADVANCE as EXPECTED DELTA INPUTS.** Verification gains a third outcome, so a real violation stays detectable. **Criterion 11 restated** | **§4.0** · §4 rows 1 & 9 · §10 |
| 🔴 ⭐ **`DI-1`** | integrity | ⭐ **`§4.1`/`§4.2` collisions removed and §4 renumbered MONOTONICALLY** — see the map in §0.5.2. **All 21 live `§4.x` references repointed and audited to resolve uniquely** | **§4 (whole)** |
| ⚠️ **`DI-2`** | integrity | ⭐ **Stale *"Phase 2b"* corrected to `2c-commit` in the two live places** — §5's manifest-commit sentence and §10 criterion 1 — **each with the superseded wording labelled.** §10 criterion 5's shorthand normalised to `2b-pin`. **§0.3 and §12's AMD3 lineage retained as labelled history** | §5 · §10 · §12 |
| ⚠️ **`DI-3`** | integrity | ⭐ **The count is reconciled TO THE ROWS: EIGHT, grouped by kind, with the 3 that gate execution named.** ⛔ **No dependency invented to reach a number; no ownership moved** | **§11.1** |

## 0.5.2 ⭐ `DI-1` — the §4 renumbering map, so every prior reference remains resolvable

| Was | Now | Content | Note |
|---|---|---|---|
| `4.1` *(AMD4)* | ⭐ **`4.4`** | the FINAL integrity check (`RC-4`, and now `RD-3`) | **renumbered — AMD4 addition** |
| `4.2` *(AMD4)* | ⭐ **`4.3`** | the transfer INSTANT (`RC-5`) | **renumbered — AMD4 addition** |
| `4.3` *(AMD4)* | ⭐ **`4.5`** | marker mechanism (`RC-6`, and now `RD-7`) | **renumbered — AMD4 addition** |
| `4.4` *(AMD4)* | ⭐ **`4.6`** | `-text` only (`RC-8`) | **renumbered — AMD4 addition** |
| `4.0` | `4.0` | Phase 0 — the freeze (and now `RD-10`) | **unchanged; moved into position** |
| `4.1` *(original)* | ✅ **`4.1`** | Phase 3 — what byte-preserving forbids | ⭐ **UNCHANGED — the ORIGINAL plan's number is preserved**, because the Governance review and the AMD3 review cite it |
| `4.2` *(original)* | ✅ **`4.2`** | Phase 7 — removal is NOT deletion | ⭐ **UNCHANGED, same reason** |

> ⭐ **The AMD4 additions moved and the originals did not — deliberately.** ⛔ **Renumbering the originals would have invalidated citations in two already-delivered independent reviews.** ✅ **Order is now monotonic — `4.0 · 4.1 · 4.2 · 4.3 · 4.4 · 4.5 · 4.6` — which `RC-7b` established as authoritative for this artifact.** ⛔ **No governance history was deleted; nothing was renamed to hide a change.**

⛔ **AMD5 changes NO decision.** `B′` · `R-CONFLICT` *(the invariant is untouched — §4.4 states why quarantine is compliant)* · `OPEN-M3` Option A · placement governance · `INV-ORDER` · `Option D` — **all intact.** ⛔ **No second authority boundary · no second owner · no ledger · no bounded context.** ⛔ **`OPEN-M1`/`OPEN-M2`/`OPEN-M4`/`OPEN-M5`/`OPEN-M6` NOT decided** *(`RD-10` and `RC-1` constrain `OPEN-M2` further; neither resolves it)*. ⛔ **No phase reordered — the sequence is unchanged: `0 · 1 · 2 · 2b-pin · 3 · 2c-commit · 4 · 4b · 5 · 6 · 7`.** ⛔ **`Increment 2` not proposed** — §4.4 records the final-re-hash-to-removal interval as **irreducible without a lock**.


---

# 0.6 · Amendment 6 — residual correction *(AMD5 review)* and the registered `C-1`…`C-12` commission corrections

**Input, consumed and not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD5-ARCHITECTURE-REVIEW.md` — the **INDEPENDENT** AMD5 review by `claude-code-session:ccf6c9c7`, verdict **`PASS WITH DESIGN CLARIFICATIONS`**: **`RD-10`/`DI-1`/`DI-2` CLOSED**, **`RD-7`/`RD-3`/`DI-3` ADDRESSED-NOT-CLOSED**, five items named for repair, plus its §18 commission note.
**Authorizing acts, cited by reference and not paraphrased into authority — ⭐ FOUR REGISTERED GRANTS, which is the first amendment in this chain whose commission is registered at all:**

| Grant | Carries |
|---|---|
| `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6` | `C-1`…`C-5` + the **binding `C-4` refinement** *(the commission does NOT hard-code the mechanism)* + governance flags **AA** *(quarantine by location, never by renaming)* and **AB** *(the indefinite block has no named disposer)* |
| `G-…-AMD6-C6-C8` | `C-6` **POST-DEMOTION** terminology · `C-7` finding-derived sequence **≠** pre-decided mechanism · `C-8` the quarantine **consequence**, which must not be softened |
| `G-…-AMD6-C9-C11` | `C-9` canonical aggregate identity · `C-10` author eligibility · `C-11` post-AMD6 routing |
| `G-…-AMD6-C12` | `C-12` **AUTHOR ≠ INDEPENDENT REVIEWER** — restores `bc1b47ef`'s **authoring** eligibility, leaves **reviewing** and **accepting** barred |

**Producing process:** `claude-code-session:bc1b47ef` — **author of AMD3, AMD4, AMD5 and the original technical review.** ⭐ **`C-12` makes this authoring lawful and says why: the estate's independence rule is *author ≠ independent reviewer*, not *author ≠ every prior reviewer*; repeated authoring is a QUALITY RISK, not automatically an independence failure.** ⛔ **The §0.4.1 bound therefore recurs for the FOURTH time and AMD6 does not pretend otherwise: `ccf6c9c7`'s findings are independent of this process, but AMD6's REMEDIES are again written by a process that will not review them.** ⇒ **every item below is `ADDRESSED`, ⛔ NEVER `CLOSED`.**
⭐ **`C-12`'s quality-risk mitigation is delivered in §0.6.5 — the plan-local four-layer trace — and `C-12` is equally binding on what that trace is NOT: it is REQUIRED DELIVERED EVIDENCE and it DOES NOT DISCHARGE THE REVIEW GATE.**

## 0.6.1 Disposition — ⭐ **every commissioned residual ADDRESSED; ⛔ none self-closed**

| # | Kind | Correction | Where |
|---|---|---|---|
| 🔴 ⭐ **`DI-5` / `RD-1`** | specification | ⭐ **ONE canonical Phase-5 sequence — `1 · 1b · 2 · 3 · 4 · 5` — in the block that is labelled *"mandated"*.** `1b` *(reconcile / dispose the delta)* closes `RD-1`; step 5 *(retraction)* closes `DI-5`, and criterion 14 now cites a step the mandated block defines | **§4.3** · §4 row 9 · §4.7 · §10 |
| 🔴 ⭐ **`RD-7·b`** | overclaim | ⭐ **The *"at NO point"* claim is WITHDRAWN and restated to what the design supports:** the exposure falls from a **durable, versioned** false label to the **step 3→5 interval**, which is **irreducible without atomicity** and is **RECORDED, not closed** — with a §8 row for an interruption inside it | **§4.5** · §8 |
| 🔴 ⭐ **`RD-3·a`** | design gap | ⭐ **`POST-DEMOTION` gets an explicit TERMINATING CONDITION: it does NOT gate on re-verification at all.** Phase 7 is **SUSPENDED**, the demoted store is **RETAINED ENTIRE**, and only a governed disposition releases it. The three unsafe readings are named and refused | **§4.4** · §10 criterion 20 |
| 🔴 ⭐ **`RD-3·b`** | design gap | ⭐ **Quarantine gets a LOCATION with stated properties** — inside the governed evidence boundary, **outside every `*.json` record glob**, resolved through existing placement governance — **and Phase 7 may not remove a store holding an undisposed quarantine** | **§4.4** · §4.5 · §4 row 11 |
| 🔴 ⭐ **`DI-4`** | integrity | ⭐ **§8's Phase-7 row now carries the SAME CURRENT mismatch semantics as §4.4** — the split, not the refuted single branch. **The superseded single-branch wording is retained and LABELLED**, per §0.4.4 | **§8** |
| ⚠️ **`DI-6`** | integrity | ⭐ **§11.1 reconciled again: NINE rows, grouped by kind, and the gate claim now QUOTES its own Gates column** instead of contradicting it. The registration row reads **AMD3 … AMD6**; the remediation row reads its true current state | **§11.1** |
| ⚠️ **`DI-7` / `C-1` / `C-6`** | integrity | ⭐ **ONLY the newer family is renamed — `CASE α`/`CASE β` become `PRE-SWITCH DISPOSITION` / `POST-DEMOTION DISPOSITION`. ⛔ §6's `CASE A`/`CASE B` are UNTOUCHED** — see §0.6.2 | **§4.4** · §4 row 11 · §10 criterion 15 |
| ⭐ **`C-2`** | design | **Quarantine protection is STORE-LEVEL.** ⛔ **No partial-record removal semantics are introduced** — the demoted store, its records and its marker are retained **entire** | **§4.4** · §4.5 |
| ⭐ **`C-3`** | design | **Quarantine mechanics: outside every glob · a THIRD marker (`QUARANTINED`) · the *"two markers"* row becomes THREE** | **§4.5** |
| 🔴 ⭐ **`C-4` / `C-7`** | ⭐ **the central architectural decision** | ⭐ **Phase-5 evidence placement is DECIDED, per subject-state, and DERIVED from §3's resolver contract rather than chosen** — §0.6.3, normative in **§4.3** | **§4.3** · §4 row 9 · §10 criteria 12 · 17 · 18 |
| ⭐ **`C-5`** | integrity | **`OPEN-M6` spans AMD3 + AMD4 + AMD5 + AMD6**, and the distinction is stated precisely: **AMD6's COMMISSION is registered; no amendment's DELIVERY is** | §11 · **§11.1** |
| ⭐ **`C-8`** | governance surface | **The consequence is preserved UNSOFTENED**, and canonical discovery ran before anything was named ⇒ ⛔ **no disposer invented**; ⭐ **`OPEN-M7`** recorded — §0.6.4 | §4.4 · **§11** · §11.1 |
| ⭐ **`C-9`** | identity | **Aggregate key verified before writing and used exactly as registered** — header. ⛔ **No `init`, no second aggregate, no label-derived key** | header · §12 |
| ⭐ **`C-10` / `C-11` / `C-12`** | routing | **Routing recorded as delivered, not as satisfied:** this author must not review or accept AMD6 · a **fresh** independent Architecture reviewer is required · then the **bounded** Governance review · then PO/ARB | §0.6 · footer |

⚠️ **Carried forward and NOT in AMD6's commission, recorded so they are not lost:** `RD-1` is closed by `DI-5`'s single edit, as the AMD5 review predicted · **`RD-2`** *(step 3→4 unmarked stale runtime)* · **`RD-4`** *(the AUTHORITATIVE `INV-R1` branch has no pinned coverage)* · **`RD-5`** · **`RD-6`** *(`OPEN-M5`'s scope includes `T-14`)* · **`RD-9`** *(`2c-commit` does not name `.gitattributes`)* · **`RD-10·r1`/`r2`** · **`DI-1·r`** *(Phase 7's rules are dispersed — §4.2 now carries the pointer the residual asked for, which is the one part AMD6 could repair without reaching outside its scope)*. ⛔ **AMD6 repairs only what it was commissioned to repair.**

## 0.6.2 ⭐ `DI-7` / `C-1` / `C-6` — the naming map: **the NEWER family is renamed, and only it**

| Was *(AMD5)* | Now *(AMD6)* | Why this direction |
|---|---|---|
| `CASE α` — pre-writer-switch | ⭐ **`PRE-SWITCH DISPOSITION`** | ⭐ **Measured, not assumed:** `CASE A`/`CASE B` occur **23 times** in this plan *(15 + 8)* and are cited in the Governance review, the AMD3 review, the AMD4 review, the AMD5 review and §10 criterion 10; `CASE α`/`CASE β` occur **11 times** *(5 + 6)*, are AMD5's own addition, and are cited nowhere outside this plan |
| `CASE β` — post-writer-switch | ⭐ **`POST-DEMOTION DISPOSITION`** | ⛔ **Renaming §6 would have invalidated citations in four delivered reviews** — the exact trap `DI-1`'s remedy avoided when it renumbered the AMD4 additions and preserved the originals (§0.5.2). **The same rule, applied to names instead of numbers** |

> ⭐ **`C-6`, complied with and stated precisely so the record does not imply a defect it never carried:** the term is **`POST-DEMOTION`**. ⛔ **The malformed variant `C-6` forbids is NOT used, is NOT reproduced here, and never appeared in the record** — independently re-verified: the forbidden string occurs **nowhere in any document under `docs/` or `.claude/` except inside the `C-6` grant that forbids it**, and `C-6` itself records that it therefore *"corrects an unregistered chat draft, not the registered commission"*. ⭐ **AMD6 does not reproduce the term, because reproducing it would be the only way this artifact could introduce it.**
> ⛔ **§6's `CASE A` *(strict extension)* and `CASE B` *(same sequence, different content)* keep their names, their text and their meaning.** ⭐ **The glyph collision `DI-7` raised disappears by construction: there is no longer a `β` to mistake for a `B`.** *(AMD5's names are retained as labelled history wherever a reference needs to resolve.)*

## 0.6.3 ⭐⭐ `C-4` / `C-7` — **the Phase-5 evidence-placement decision.** This is the most consequential thing AMD6 does

**The question, as `C-7` obliges Architecture to answer it and as the commission deliberately declined to answer for it:** *which Phase-5 records belong to the PRE-SWITCH SOURCE state, which belong to the POST-SWITCH AUTHORITATIVE state, and exactly where is each written?*

⭐ **The answer is DERIVED from decisions this plan already carries, not chosen — which is why it introduces no mechanism, no new act and no new authority:**

```
§3 row 1   an AUTHORITATIVELY resolved location is inside the governed evidence boundary
§3 row 2   an explicit --dir override yields a NON-AUTHORITATIVE output location
§4.3      P-1's resolution changes at step 3 — that IS the authority-transfer instant
      ⇒  BEFORE step 3 the authoritative store cannot receive an authoritative record AT ALL
      ⇒  AFTER  step 3 the demoted source cannot receive one either
```

⇒ **placement is forced by the resolver contract, and the only real decision left is the one C-4 names: WHERE step 3's OWN evidence goes.** ⭐ **AMD6 decides: into the AUTHORITATIVE store, AFTER the switch — and the argument is a TERMINATION argument, not a tidiness one:**

```
if step 3's evidence were written BEFORE the switch it would land in the source
    ⇒ the source gains a record AFTER the copy was reconciled to it
    ⇒ either that record is left behind (evidence loss at Phase 7) or it is reconciled — which writes again
    ⇒ ⛔ THE PRE-SWITCH RECONCILIATION DOES NOT TERMINATE
writing step 3's evidence AFTER the switch terminates the recursion in one step
```

⭐ **Three consequences, each of which is one of `C-4`'s three required properties:**

| `C-4` property | How the decision delivers it |
|---|---|
| switch evidence lands in the **authoritative** store **after** the switch | ⭐ **DV CORRECTION (`DV-3`/`C-16`): the `SWITCH-OVER RECORD` is the FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER**, by construction *(⛔ **superseded wording, retained as labelled history per §0.4.4:** *"the FIRST record in the authoritative store"* — **false, not merely imprecise: Phase 3 has already copied governance records into that store, so the switch-over record cannot be its first record. `RECORD EXISTENCE ≠ AUTHORITY ESTABLISHMENT`)* |
| the Phase-7 re-hash is **not** self-detecting merely because bookkeeping went into the source | ⭐ the source's expected content is **CLOSED at step 3** and **recorded** as the `SOURCE FINAL-STATE ENUMERATION`, which **is** Phase 7's comparison object ⇒ **criterion 12's *"identical"* branch is REACHABLE in the normal case** — `RD-10`'s degradation does **not** recur at the irreversible gate |
| the switch-over record remains a **valid discriminator** | ⭐ the boundary becomes **content-defined, not timestamp-defined** — which matters because only **2 of 216** transitions carry any time field |

⛔ **What this decision is NOT:** it is **not an implementation recipe** (`C-4` refinement, `C-7`) — it fixes **subject-state → destination** and **the closing instant**, and it leaves the mechanism, the file layout and the tooling to the execution act. ⛔ **No phase is reordered, no slot is added, no bounded context appears, and `§5`'s rule that migration evidence belongs to the governance boundary is honoured rather than contradicted — the pre-switch records are CARRIED ACROSS by the same closing reconciliation, so nothing is left behind in runtime.** **Normative text: §4.3. Operator form: §4.7.**

## 0.6.4 ⭐ `C-8` — quarantine disposition: **canonical discovery FIRST, then an explicit OPEN question**

**`C-8` and flag `AB` forbid inventing a disposer and require the existing authority/responsibility model to be inspected first (`ES-005.4` — consume or extend, never create a second). ⭐ It was, and the result splits cleanly in two:**

| | Finding |
|---|---|
| ✅ **the ACT CLASS already exists** | §8 already rules that **re-promoting a demoted source is a GOVERNANCE ACT, not a merge**, and §6.2 already rules that **a resolution requiring a new governance decision is recorded separately** ⇒ the disposition of a quarantined record is **the same class of act**, and its actors are the pair this plan already names: **PO/ARB decides · Governance registers** (`G-2`/`R5a`, §11.1). ⛔ **AMD6 therefore creates NO disposer and proposes NO new role** |
| 🔴 **the disposal PATH exists NOWHERE** | ⛔ **no admissible-outcome set, no discharge evidence, and — measured — no act in the mechanism's own vocabulary:** `workflow-state.php`'s transition types are `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL` — **there is no `DISPOSE`** |

⇒ ⭐ **`OPEN-M7` is recorded (§11, §11.1), with both halves explicit:** *(a)* **confirm the disposer** — Architecture's reading is the existing PO/ARB·Governance pair **by extension of §8**, and ⛔ **Architecture does not decide it**; *(b)* **the admissible dispositions and the evidence that discharges a quarantine**, which no artifact defines.
⛔ **The consequence is preserved exactly as `C-8` requires and is NOT softened to *"Phase 7 remains blocked"*:** while any quarantine is undisposed — **Phase 7 remains blocked · the affected store remains preserved · NO Phase-7 removal occurs · `B′`'s TARGET END STATE IS NOT REACHED · runtime does NOT become fully execution-only · a governed disposition is REQUIRED.** ⭐ **A single undisposed quarantine can block the migration's completion INDEFINITELY, and that is the safe direction.**
⭐ **The DDD reading, stated because the filesystem word hides it:** *a quarantine is a DOMAIN STATE with a business consequence, not a directory condition.* **An undisposed conflict means the migration aggregate CANNOT LEGITIMATELY TRANSITION TO ITS COMPLETED STATE** — so the block is not a tooling limitation to be engineered away, it is the aggregate refusing an invalid transition.

## 0.6.5 ⭐ `C-12` — the plan-local four-layer trace: **REQUIRED DELIVERED EVIDENCE**

> ⛔ **BOTH halves of `C-12` are binding, and this section states the second one first: THIS TRACE IS NOT INDEPENDENT ASSURANCE AND DOES NOT DISCHARGE THE REVIEW GATE.** ⭐ **It is a self-check by the author, delivered because the commission requires it as evidence — and a self-check by the author is exactly what `§0.4.1` says cannot close anything.**

**Every act AMD6 introduces or re-specifies, traced ACT → NORMATIVE ENUMERATION → ACCEPTANCE CRITERION → OPERATOR INSTRUCTION:**

| # | ACT | NORMATIVE ENUMERATION | ACCEPTANCE CRITERION | OPERATOR INSTRUCTION |
|---|---|---|---|---|
| **1** | **step 1 — re-hash the source against the frozen manifest** | §4.3 slot **1** | §10 criterion **11** | §4.7 **`P5·1`** |
| **2** | ⭐ **step 1b — reconcile / DISPOSE the delta, three outcomes** *(closes `RD-1`)* | §4.3 slot **1b** | §10 criterion **16** | §4.7 **`P5·1b`** |
| **3** | **step 2 — switch the READERS (`P-2`, `P-4`)** | §4.3 slot **2** | §10 criterion **13** | §4.7 **`P5·2`** |
| **4** | ⭐ **step 3 — CLOSE the source enumeration, verify it, switch `P-1`** | §4.3 slot **3** | §10 criteria **13 · 17** | §4.7 **`P5·3`** |
| **5** | ⭐ **step 3 — write the `SWITCH-OVER RECORD` into the AUTHORITATIVE store, first** | §4.3 slot **3** + the placement table | §10 criterion **17** | §4.7 **`P5·3`** |
| **6** | ⭐ **Phase-5 evidence placement by SUBJECT-STATE** | §4.3 placement table | §10 criterion **18** | ⭐ **DV CORRECTION (`DV-7`):** §4.7 **`P5·1`** · **`P5·1b`** · **`P5·2`** · **`P5·3`** · **`P5·4`** · **`P5·5`** *(⛔ **superseded cell: a WILDCARD OPERATOR TOKEN that §4.7 defined NOWHERE.** **It occurred exactly once in this artifact — in this cell — under a closing claim that every row resolves.** ⭐ **`DV-7` requires its REMOVAL, so it is not reproduced here; the withdrawn token is named verbatim in the independent AMD6 review §6.2 at `40a4f06e`, which is the durable lineage record.** ⭐ **Resolved by citing the six instructions that actually exist and that together carry the placement rule — ⛔ NO token was invented to make the trace pass)* |
| **7** | **step 4 — write the runtime `DEMOTED` marker** | §4.3 slot **4** · §4.5 lifecycle | §10 criteria **8 · 14** | §4.7 **`P5·4`** |
| **8** | ⭐ **step 5 — RETRACT the staging `NON-AUTHORITATIVE` marker** *(closes `DI-5`)* | §4.3 slot **5** · §4.5 lifecycle | §10 criterion **14** | §4.7 **`P5·5`** |
| **9** | ⭐ **Phase 7 — dispose a mismatch by `PRE-SWITCH` / `POST-DEMOTION`** | §4.4 split | §10 criterion **15** | §4.7 **`P7·1`** |
| **10** | ⭐ **Phase 7 — QUARANTINE: location, bytes, name, hash, `QUARANTINED` marker** | §4.4 · §4.5 | §10 criterion **19** | §4.7 **`P7·2`** |
| **11** | ⭐ **Phase 7 — SUSPEND: retain the store entire, escalate, do not re-verify to a pass** | §4.4 · §4 row 11 | §10 criterion **20** | §4.7 **`P7·3`** |

✅ **Every row resolves in all four columns; that is the whole claim.** ⛔ **It is not a claim that any of it is sound.**
> ⚠️ ⭐ **DV CORRECTION (`DV-7`), recorded rather than quietly fixed: this closing claim was FALSE for row 6 when AMD6 delivered it** — the row cited a wildcard operator token that §4.7 does not define, **so the trace asserted its own completeness while carrying an unresolvable reference.** ✅ **The claim is TRUE as the table now stands** *(re-measured mechanically: every `P[57]·…` identifier cited in a trace row is defined in §4.7 — §0.7.6)*. ⭐ **The lesson is the one `C-13` registered as the assurance-tooling boundary: a self-check that can assert its own success is EVIDENCE, never assurance.**

## 0.6.6 What AMD6 does NOT do

⛔ **AMD6 changes NO decision.** `B′` · `R-CONFLICT` *(§6.1's invariant text is untouched; §4.4's quarantine argument remains labelled migration interpretation)* · `OPEN-M3` Option A · existing placement governance · `INV-ORDER` · `Option D` — **all intact.** ⛔ **`OPEN-M1`/`OPEN-M2`/`OPEN-M4`/`OPEN-M5`/`OPEN-M6` NOT decided · quarantine ownership NOT decided (`OPEN-M7`) · no second authority boundary · no second owner · no ledger · no bounded context · no phase reordered** — the sequence is still `0 · 1 · 2 · 2b-pin · 3 · 2c-commit · 4 · 4b · 5 · 6 · 7` — **and `Increment 2` is not proposed.**
⛔ **AMD6 is `PROPOSED`. It declares no remedy `CLOSED`, accepts nothing, registers nothing, and executes nothing.**

---

# 0.7 · **DV CORRECTION** — the `DV-1`…`DV-7` residual repair *(independent AMD6 Architecture review)*

**Input, consumed and not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6-ARCHITECTURE-REVIEW.md` — the **INDEPENDENT** AMD6 review by `claude-code-session:dd639043`, **583 lines, committed at `40a4f06e`**, verdict 🟡 `PASS WITH DESIGN CLARIFICATIONS`. ⭐ **Read as the primary explanation of WHY each residual exists** (§5.5–§5.9, §6.2, §12, §15) — ⛔ **the commission defines WHAT must be corrected; the review is not reduced to checklist text.**
**Canonical governed aggregate:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · workflow `architecture-adr`. ⛔ **`KOS-AIP-GOV-STATE-DURABILITY` is the TRACK LABEL only and is NOT the aggregate key.**
**Producing process, self-declared and ⛔ NOT attestable** (`INV-ATTR-1`/`INV-ATTR-2`, `G-2`): `claude-code-session:f7e57e4a` — **a fresh lane.** ⛔ **It authored no prior amendment and reviewed none. It is barred from REVIEWING and from ACCEPTING this correction** (`R-34`/`P-2`).
**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**.

## 0.7.1 🔴 ⭐ **MANDATORY PRE-AUTHORING DECLARATION** — the reading interpretation, stated in the artifact BEFORE the edit, because numbering interpretation is a recorded recurring failure mode *(flag `AG`)*

**Declared, item by item, so nothing is chosen silently:**

| # | Declaration |
|---|---|
| **1** | ⭐ **The REGISTERED reading-order index was consumed as the index — ⛔ NOT a hand-copied list.** `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-READING-ORDER`, read from the canonical aggregate record. **All six cited grant IDs were resolved mechanically against the record and ALL SIX RESOLVE** *(§0.7.2)* |
| **2** | ⭐ **`C-13` was consumed IN FULL EXCEPT its superseded sequence clause** — comparison-object identity, the failure branch, the propagation control, the second-artifact control, the assurance-tooling boundary, and flag `AE`'s `DV-1`↔`DV-2` coupling are all live obligations here |
| **3** | ⭐ **`C-15` is AUTHORITATIVE for the corrected slot-3 order.** ⛔ **`C-13`'s former ordering — *"final copy-side write, then construct and close the enumeration"* — is NOT used: it would have re-closed the enumeration AFTER the write it constrains, rebuilding the absorption defect the review had structurally excluded** |
| **4** | ⭐ **`C-16` is AUTHORITATIVE for `DV-3` terminology** |
| **5** | ⭐ **The canonical Phase-5 slots REMAIN `1 · 1b · 2 · 3 · 4 · 5`** *(flag `AG` resolved in favour of reading 2 by `C-15`)*. ⛔ **Nothing is renumbered** |
| **6** | ⭐ **The FINAL DURABLE-COPY VERIFICATION is a SUB-STEP OF SLOT 3 — `3(iii-b)`** |
| **7** | ⭐ **Slot 3 is NOT renumbered to slot 4.** ⛔ **The forms `4(i)` / `4(ii)` / `4(iii)` are EXPRESSLY FORBIDDEN for slot-3 operations and appear nowhere.** **Slot 4 remains the RUNTIME DEMOTION MARKER; slot 5 remains the STAGING MARKER RETRACTION** |

> ⭐ **Why the declaration is load-bearing and not ceremony:** the correction's own scope includes the propagation control, which requires any Phase-5 change to reach the single canonical normative sequence, every reference, the acceptance criteria, the operator instructions, the cross-references and the summary artifact. **An author starting from an ambiguous slot number propagates the ambiguity into all six places — which is exactly how `DI-1` produced duplicate identifiers and `DI-5` a specification collision.**

## 0.7.2 The commission, resolved from the record — **six grants, all six resolve**

| # | Registered grant ID | Carries |
|---|---|---|
| **1** | `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-DV-CORRECTION` | `DV-1`…`DV-7` in full · `DV-1` as 🔴 **SAFETY-CRITICAL / UNSAFE DIRECTION** · slots `1/1b/2/3/4/5` · `DV-3`'s target terminology · criterion **17** named |
| **2** | `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-PROVENANCE-PREREQ` | the authoring gate *(the AMD6 review and summary durably committed by their own producers)* |
| **3** | `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-DV-CORRECTION-C13` | comparison-object identity · failure branch · propagation · second-artifact control · tooling boundary · flag `AE` — ⚠️ **its SEQUENCE CLAUSE superseded by `C-15`** |
| **4** | `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-C14-SLOT-NUMBERING` | flag `AG` — the slot-numbering ambiguity · ⭐ **the receipt-is-evidence-ABOUT-verification separation** |
| **5** | `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-C15-CORRECTED-AUTHORING` | the corrected slot-3 sequence · flag `AG` resolved · ⛔ no `FES`/`CFS`, no second comparison object |
| **6** | `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-C16-DV3-SEMANTICS` | `DV-3`'s canonical concept and its four-part acceptance criterion · the two DDD non-confusions |

> ⭐ **The gate is OPEN in the RECORD, not merely in conversation:** `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-GATE-OPEN` records `GATE OPEN` against fourteen verified conditions, including the AMD6 review committed at `40a4f06e` with its producer disclosure in both artifact and commit message. ⛔ **No new commission was created and the gate was not reinterpreted.**
> ⚠️ **Two ID-transcription defects are recorded in the chain and BOTH were avoided by resolving from the record:** the reading-order index's own citation of `C-16` inserted a `MIGRATION-PLAN-` segment, and a hand-copied gate-request list omitted it from `C-13`. ⭐ **Only the base commission and `C-13` carry the longer prefix. Losing `C-13` while keeping `C-15` is the worse failure mode — the author would hold the corrected sequence but not the finding that `DV-1` cannot be closed independently of `DV-2`.**

## 0.7.3 Disposition — ⭐ **all seven ADDRESSED · ⛔ NONE self-closed**

| | Residual | Where the repair lands |
|---|---|---|
| 🔴 **`DV-1`** *(UNSAFE DIRECTION)* | the durable copy is written after its last all-or-nothing verification and re-verified nowhere before Phase 7 removes the source | ⭐ **§4.3 slot `3(iii-b)` — the FINAL DURABLE-COPY VERIFICATION** · §4.7 `P5·3` · §4.4 · criteria **2 · 12 · 18** · §8 |
| 🔴 **`DV-2`** | the enumeration's third operand has no counterpart in §4.0 | ⭐ **§4.0 — the declared set MUST cover the pre-switch classes of slots 1, 1b and 2** · criteria **11 · 16** |
| 🔴 **`DV-3`** | the **now-superseded wording** *"the FIRST record in the authoritative store"* is false under the plan's own record vocabulary | ⭐ **§0.6.3 · §4 row 9 · §4.3 · criterion 17 — all restated as FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER** |
| ⚠️ **`DV-4`** | the `C-4` termination argument's load-bearing premise is stated elsewhere | ⭐ **§4.3's termination argument now carries the premise INSIDE it, as an enumerated five-step derivation** · §0.6.3 |
| ⚠️ **`DV-5`** | the readers-first justification credits the wrong bound | ⭐ **§4.3 — the bound is slot `3(i)`–`3(iii)`, demonstrated at `3(iii-b)`; the window is added to the residual-window table** |
| ⚠️ **`DV-6`** | the MOVE refusal's stated reason is inverted | ⭐ **§4.4 — QUARANTINE LAUNDERING stated as the load-bearing reason** · §4.7 `P7·2` |
| ⚠️ **`DV-7`** | the four-layer trace cites an **undefined wildcard operator token** under a claim that every row resolves | ⭐ **§0.6.5 row 6 — the token is REMOVED and replaced by the six operator IDs §4.7 actually defines; ⛔ no token was invented, and ⛔ the withdrawn token is not reproduced anywhere in this artifact** |

> ⛔ **STATUS: `PROPOSED` · `ADDRESSED` · NOT CLOSED.** ⭐ **This process wrote these repairs and MUST NOT close them, review them or accept them.** **`DV-1`…`DV-7` remain OPEN findings.** **A FRESH INDEPENDENT ARCHITECTURE REVIEW is mandatory, then the Governance bounded review, then PO/ARB.**
> 🔴 ⭐ **Flag `AE`, honoured rather than restated: `DV-1` and `DV-2` are ONE coupled verification problem.** **A correction that fixed `DV-1`'s timing while leaving `DV-2`'s declaration scope unfixed would produce a verification that RUNS AT THE RIGHT MOMENT AGAINST AN INCOMPLETE OBJECT** — because the enumeration's third operand *is* the declared pre-switch set. ⭐ **The independent reviewer should test the pair together, not each in isolation.**

## 0.7.4 The DV correction's own four-layer trace — ⭐ **DELIVERED EVIDENCE** · ⛔ **NOT ASSURANCE**

| # | ACT | NORMATIVE ENUMERATION | ACCEPTANCE CRITERION | OPERATOR INSTRUCTION |
|---|---|---|---|---|
| **1** | 🔴 ⭐ **`3(iii-b)` — the FINAL DURABLE-COPY VERIFICATION, all-or-nothing, before the authority transfer** | §4.3 slot **`3(iii-b)`** | §10 criteria **2 · 12 · 18** | §4.7 **`P5·3`** |
| **2** | ⭐ **the declared set covers the pre-switch classes of slots 1, 1b, 2** | §4.0 declaration rule | §10 criteria **11 · 16** | §4.7 **`P5·1b`** · **`P5·3`** |
| **3** | ⭐ **the authority-boundary discriminator is the FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER** | §4.3 placement table | §10 criterion **17** | §4.7 **`P5·3`** |
| **4** | ⭐ **a MOVE out of the demoted store is refused because it would LAUNDER THE QUARANTINE** | §4.4 quarantine mechanism | §10 criteria **19 · 20** | §4.7 **`P7·2`** · **`P7·3`** |

✅ **Every row resolves in all four columns** *(re-measured mechanically over §4.3, §4.7 and §10 — §0.7.6)*. ⛔ **It is NOT a claim that any of it is sound, and it DOES NOT discharge the review gate** — the same bound `C-12` places on §0.6.5.

## 0.7.5 ⭐ **The VERIFICATION RECEIPT — placement resolved by CONSUMING existing governance** *(`ES-005.4`)*, ⛔ **no new mechanism**

**The question the commission left open, stated as it was left:** *the receipt's destination is not established by the commission; it must not be chosen silently.*

**Canonical discovery ran FIRST, and it found two things rather than none:**

| Searched for | Found |
|---|---|
| an existing **receipt** concept in the estate | ✅ **YES, and it is a DIFFERENT domain concept** — `KOS-AIP04-DISCOVERY-001`'s C-10 receipt *(context-package delivery / acknowledgement, `DECIDED: Option C`)*. ⛔ **Not consumed as the same object; a delivery receipt is not a verification receipt.** ⭐ **What IS consumed is its invariant, which transfers exactly: `AUTHORITY IS CLAIM-SCOPED`** |
| an existing **placement rule** that determines the destination | ✅ **YES — §4.3's placement table** *(subject-state → destination, itself DERIVED from §3's resolver contract)* **plus §5** *(migration evidence is itself governance evidence and belongs to the governance boundary)*. ⭐ **The rule is determinate; applying it yields ONE answer** |

**⇒ OUTCOME A — existing governed placement owns the receipt. The derivation, not a choice:**

```
the receipt exists only AFTER the comparison result exists            (C-14: evidence ABOUT verification)
    ⇒ it cannot be part of what it attests, and it is NOT an operand of 3(iii-b)
before slot 3(iv) the staging store cannot receive an authoritative record at all   (§3 row 2)
    ⇒ the receipt cannot be an authoritative append there yet
if the receipt were appended into the SOURCE at 3(iii-b) it would land
after the enumeration was CLOSED at 3(ii)
    ⇒ the source would hold content outside the closed enumeration
    ⇒ Phase 7's final re-hash reports a delta in the NORMAL case
    ⇒ ⛔ the DV-2 defect class, re-created — and the C-4 recursion, re-opened
⇒ the receipt is a POST-SWITCH record, and it is CARRIED BY the SWITCH-OVER RECORD at 3(v),
  exactly as the SOURCE FINAL-STATE ENUMERATION already is
```

| Property | Rule |
|---|---|
| ⭐ **destination** | **the AUTHORITATIVE store, CARRIED BY the `SWITCH-OVER RECORD` at `3(v)`** — ⛔ **no new store, no new record class, no new provenance mechanism, no second evidence boundary.** ⭐ **The carrier already exists and already carries one such object** |
| ⭐ **claim scope** | **authoritative for *"the comparison at `3(iii-b)` was performed against the closed enumeration, and its result was `PASS`"*** — ⛔ **NOT for *"the durable copy is correct"***. **The C-10 invariant, applied: `AUTHORITY IS CLAIM-SCOPED`** |
| ⛔ **not an operand** | **the receipt is ⛔ NOT part of the `SOURCE FINAL-STATE ENUMERATION` and ⛔ NOT an input to the verification it records.** ⭐ **Without this, a receipt written into the compared corpus would change the object it attests — the `DV-1` circularity** |
| ⭐ **the FAIL branch** | **on `FAIL` there is no authority transfer, so there is no authoritative store to append to.** ⇒ **the failure record is an ORDINARY PRE-SWITCH APPEND INTO THE SOURCE, exactly as §4.3 already prescribes for outcome C at slots `1b` and `3(ii)`.** ✅ **Consistent and safe: Phase 7 never runs, nothing is destroyed, and no removal gate is ever consulted** |
| ⛔ **not decided here** | **the physical form and layout** — resolved at execution time through EXISTING placement governance, as §2 already requires for the migration target |

> ⭐ **Recorded honestly as the boundary of outcome A:** the governing RULE is determinate and is consumed, ⚠️ **but the placement table gains a ROW that did not exist before, because the act did not exist before.** ⛔ **That is an application of the existing rule, not a new rule.** **If the independent reviewer judges otherwise, the correct disposition is outcome B — record the unresolved placement question and route it through existing governance — and ⛔ NOT the invention of a store.**

## 0.7.6 What the DV correction does NOT do

⛔ **No decision is reopened:** `B′` · `R-CONFLICT` · `OPEN-M3` Option A · existing placement governance · `INV-ORDER` — **all intact.** ⛔ **Nothing introduced:** no `FES`, no `CFS`, ⛔ **no second comparison object**, no new authority boundary, no new bounded context, no new owner, no new governance gate, no new assurance class, no new routing model, no new provenance mechanism, no ledger. ⛔ **No phase reordered · no slot renumbered · no criterion added or removed** *(§10 remains **20 criteria, 1…20, each exactly once**)* · **`OPEN-M5` and `OPEN-M7` NOT decided** · **quarantine ownership NOT invented** · **no `.gitignore` or `.gitattributes` change** · **no assurance routing changed.**

> ⭐ **`DV-1`'s repair adds no actor, no decision and no reordering — it is ONE verification inside the current envelope, placed at the last cheap stop point, which is where §4.3 already puts every other pre-switch check.**
> ⛔ **PHASE 3 MUST NOT BEGIN. PHASE 5 REMAINS PROHIBITED. MIGRATION NOT EXECUTED.**
> ⭐ **Assurance-tooling boundary, honoured (`C-13`):** the property measurements cited in this section are **EVIDENCE produced by a deterministic checker.** ⛔ **They are NOT assurance, they do NOT self-approve the repair, and Architecture's repair still requires an independent judge** — the same distinction the estate enforces as *trace-is-not-review* and *self-review-is-not-independent-verification*.

---

# 1 · Current-state inventory — `OBSERVED`, measured, nothing modified

## 1.1 The evidence corpus — ⚠️ **AMD3: these are PLANNING OBSERVATIONS, never an execution criterion**

| | Measured 2026-08-19 | Re-measured 2026-08-20 |
|---|---|---|
| **Authority records** | **18** JSON files under `.claude/runtime/workflow/` | **18** |
| **Transitions** | **216** | **216** |
| **Grants** | **109** | ⚠️ **110** — and ⭐ **only 109 UNIQUE `grantId`s** (§6.4) |
| **Size** | **780 KB** | — |

> ## ⭐ **AMD3 (`CL-2`) — the figures are not merely stale. They are UNATTESTABLE, and that is the stronger reason.**
> ```
> git log --oneline -- .claude/runtime/workflow/<any record>   →   (empty)
> ```
> **The corpus has NO history, because it is gitignored — the very defect `B′` exists to close.** ⇒ **`18 / 216 / 109` cannot be re-derived from anything at any later date.** ⛔ **A criterion pinned to it is not evidence, and it also cannot distinguish *"a record is missing"* from *"the corpus legitimately grew"* — and the corpus grew during the ADR, during the design, during planning, and again during the Governance review.**
> ✅ **The delta is fully accounted for:** the 110th grant is `G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION`, registered after `3817eb2b`. ⭐ **The `109`-unique-`grantId` figure is an INDEPENDENT fact and explains nothing about the delta** (§6.4). ⇒ **the acceptance object becomes the Phase-1 frozen manifest (§4, §10 criterion 1); these figures stay as the planning record they always were.**

**Per-record shape varies and the migration must not normalise it.** Two records carry **grants with zero transitions** (`KOS-ARCH-V3-BOUNDARY-VALIDATION` 0/3 · `KOS-ACTIVATION-REPORTING-001` 0/0) — **an authorized-but-uncommissioned lane is a legitimate shape, not a defect to tidy.**

## 1.2 ⭐ The durability defect, stated as measurement

```
.gitignore:25   .claude/runtime/
.gitignore:32   .claude/runtime/          ← the rule is present TWICE
git check-ignore -v .claude/runtime/workflow/KOS-CONTRACT-NEUTRALITY-001.json  →  IGNORED
```

> **The authoritative governance record is UNVERSIONED.** Every narrative artifact that cites it is versioned; **the record they cite is not.** ⇒ **historical content is unattestable by anyone**, which is the finding `B′` exists to close.

## 1.3 ⭐ **AMD4 (`RC-11`): ONE WRITER OF GOVERNANCE EVIDENCE** — *(superseded heading: "Writers — exactly one")*

| Writer | Evidence |
|---|---|
| `.claude/scripts/workflow-state.php:105` | `$tmp = $path . '.tmp.' . getmypid(); file_put_contents($tmp, …); rename($tmp, $path);` — **atomic write via temp + rename**, and it `mkdir`s the parent |

> ## ⛔ **AMD4 (`RC-11`, closing `INFO-1`) — the unqualified claim is WITHDRAWN, and the discriminating test is cited rather than assumed.**
> **`grep -rln file_put_contents .claude/scripts/` returns THREE files**, so a reader checking *"exactly one writer"* would reasonably doubt it. **The discriminating test — the one that makes the claim checkable — is: *references `runtime/workflow` AND writes*.**
>
> | Candidate | Disposition |
> |---|---|
> | `workflow-state.php:105` | ✅ **the one writer of governance evidence** |
> | `session-resolve.php:22` | **a docblock MENTION, not a call** |
> | `session-changes-logger.sh:60` | **a real write that never references `runtime/workflow`** — session state, not evidence |
>
> ⭐ **And AMD3's own evidence made the unqualified form indefensible: `CL-10`/§1.5 establishes that `KOS_MECHANISM_PATH` makes a SECOND write path reachable.** ⇒ *"one writer"* is true of **the committed code paths today** and false as a **structural guarantee**. ⛔ **This plan does not claim that arbitrary writes to the directory are impossible — §1.4 and `INV-R4` already say the opposite.**

✅ **A single writer of governance evidence is still the migration's biggest asset:** the cutover has one code path to switch, not many — and §4.3 uses exactly that fact to make the authority-transfer instant a single event.

> ## 🔴 **AMD3 (`CL-1`) — the write is atomic; the READ-MODIFY-WRITE is not. This is REPORTED, not repaired.**
> ```
> append :  loadRecord($path)                              (:303)   ← read whole JSON
>           $t['seq'] = count($record['transitions']) + 1   (:309)   ← compute from what was read
>           saveRecord($path, $record)                      (:311)   ← tmp + rename, ATOMIC per write
> ```
> **No lock, no lease, no CAS, no version check** — and the file's own header says so: *"Increment 2 is not authorized: no lock, no lease, no hook, no enforcement."* **Two concurrent appends read the same `count()`, compute the same `seq`, and the second `rename` wins.**
>
> **Reproduced (technical review, `mktemp` dirs via `--dir`, never against the corpus):**
> ```
> 23 / 30 concurrent-append trials silently lost a transition
>     the losing process exited 0 and reported {"ok":true,"seq":2}
>     EVERY survivor was DENSE and MONOTONIC
> the same loss reproduced on the `grant` path
> ```
> ⛔ **CONSEQUENCE — and it is the one claim in this plan that had to be withdrawn: DENSITY IS NOT A COMPLETENESS PROOF. It is preserved BY the loss, because the clobbering writer reuses the sequence number the lost writer took.** ⇒ §6.3 and §10 criterion 2 are restated.
> ⛔ **This is a PRE-EXISTING property of the writer, present with or without relocation. Repairing it is `Increment 2` and is NOT proposed here.** ⚠️ **But three things about the migration interact with it, so it cannot be left unaddressed: the migration WRITES INTO THE CORPUS IT MIGRATES (§5) · it deliberately widens the window (§6.3) · and a loss inside the reconciliation tail is imported as a legitimate extension (§6.3).** ⇒ **Phase 0's declared freeze (§4) exists for exactly this.**

## 1.4 Readers — **enumerable in code, unbounded in fact**

| Reader | Nature |
|---|---|
| `.claude/scripts/session-resolve.php` | **read-only by construction** — its own docblock: *"no `file_put_contents`, no `mkdir`/`rename`/`unlink`"* |
| **Ad-hoc readers** | 🔴 **not enumerable.** The records are plain JSON on disk; any `cat`, `jq`, editor or agent reads them **without resolving anything** |

> ## ⚠️ **A limit that must be stated rather than assumed away**
> **The Single Authority Resolver Invariant can bind COMPONENTS that resolve a path. It cannot bind a reader that needs no resolver.** ⇒ **the invariant is ENFORCEABLE for writers and tooling, and ADVISORY for ad-hoc reads.** **Authority must therefore rest on the record's governed LOCATION and PROVENANCE, not on an assumption that every reader passed through a resolver.** *(`INFERRED`, and it bounds what §3 can promise.)*

## 1.5 ⭐ Path sources — **THREE, on TWO DIFFERENT AXES.** The decision recorded one.

**The commission's Governance Note asked for every default, because the durability decision recorded only `workflow-state.php:81`. Measured:**

### Axis 1 — WHERE THE RECORD LIVES

| # | Source | Evidence |
|---|---|---|
| **P-1** | **default** | `workflow-state.php:81` — `$dir = $opts['dir'] ?? (dirname(__DIR__) . '/runtime/workflow');` *(the one the decision named)* |
| **P-2** | **default** | 🔴 `session-resolve.php:74` — `$recordDir = rtrim($opts['dir'] ?? (dirname(__DIR__) . '/runtime/workflow'), '/');` **— the second default the decision omits** |
| **P-3a/b** | **two independent `--dir` overrides** | one on each script |

⇒ **`RA-2` confirmed and made precise: two components compute the same default INDEPENDENTLY.** **Neither consults the other. A change to one silently diverges from the other** — which is the exact failure `B′` was decided to remove.

### Axis 2 — WHICH MECHANISM INTERPRETS IT

| # | Source | Evidence |
|---|---|---|
| **P-4** | **environment variable** | 🔴 `session-resolve.php:90` — `$mechanism = getenv('KOS_MECHANISM_PATH') ?: (__DIR__ . '/workflow-state.php');` |

> ## ⭐ **A finding beyond `RA-2` as recorded: authority has TWO location axes, not one.**
> **`RA-2` describes where the RECORD lives. `P-4` decides which PROGRAM is treated as the authority INTERPRETER — and it is settable from the environment.**
> **A resolver that governs only the record path leaves the interpreter divertible: the right bytes read by the wrong mechanism.** ⇒ **§3's invariant must cover both axes or it closes half the hole.**

> ## 🔴 **AMD3 (`CL-10`) — `P-4` is WORSE than "the right bytes read by the wrong mechanism". It is a WRITE-CAPABLE path.**
> ```php
> $mechanism = getenv('KOS_MECHANISM_PATH') ?: (__DIR__ . '/workflow-state.php');   // :90
> proc_open(array_merge(['php', $mechanism], $args), …);                            // :103
> askMechanism($mechanism, ['fold', $workItem, '--dir=' . $recordDir]);             // :156
> ```
> ⭐ **`session-resolve.php` EXECUTES the environment-named program and HANDS IT the authority record directory as an argument.** ⇒ **substitution is not only an interpretation path — it is a write path, through a component whose own bytes contain no write call.**
> ✅ **The script is honest about this** (*"technically capable of substituting the workflow interpreter… NOT a hardened boundary and must not be described as one"*), and `T-11` genuinely pins read purity **of that file**. ⛔ **`T-11` does not, and cannot, pin the purity of a substituted subprocess.**
> ⚠️ **Relocation RAISES the value of this path:** after Phase 5 its target is the durable, versioned, authoritative store. ⇒ **`INV-R3` is more necessary than the original plan argued — which is why `OPEN-M3`'s Option A scope extension was required, not optional.**

## 1.6 Runtime clients that must **NOT** migrate — the boundary proved by contrast

Five shell scripts touch `.claude/runtime/` and **none is a governance-evidence client**: `ddd-principles-reminder` · `dev-guide-reminder` · `discipline-gate-reminder` (uses `.claude/runtime/YYYY-MM-DD-…`) · `session-changes-logger` · `session-log-reminder`. **They hold ephemeral reminder/session state.**

✅ **This is the `B′` boundary demonstrated on real files: `.claude/runtime/` legitimately holds runtime state AND illegitimately holds authority evidence. The migration moves the second and leaves the first.** ⛔ **Migrating them would be the mirror error — treating storage location as ownership.**

---

# 2 · Target boundary — conceptual first, ⛔ no physical path invented

| Boundary | Holds | Durability | Authority |
|---|---|---|---|
| **Execution / runtime** | reminder state, per-session scratch, tmp files | ephemeral, may be deleted | **none** |
| **Governance evidence** | transitions · grants · session lineage · the migration's own records | **durable, versioned, append-only in practice** | ✅ **authoritative** |
| **Knowledge artifacts** | ADRs, reviews, plans that *interpret* evidence | durable | interpretive, **never authoritative over the record** |
| **Derived state** | the fold; `authorityState`; resolver answers | recomputable | ⛔ **never a second authority source** |

**Resolved placement root: `docs/knowledgeos` (resolver, exit 0).** ⛔ **The final sub-path and file layout are not chosen here** — that is a placement application at execution time through the same resolver, and inventing it now would bypass the governance the plan is required to use.

---

# 3 · Authority resolution — the Single Authority Resolver Invariant (resolves `RA-2`)

> ## **INVARIANT: every reader and writer of governance evidence resolves the authority location through ONE governed mechanism. No component embeds an independent authority path.**

**And, on §1.5's finding, it must cover both axes:**

> ## ⭐ **AMD4 (`RC-2`, `RC-3`) — THE TABLE BELOW IS THE SPECIFICATION PHASE 4b IMPLEMENTS, so it now carries EXACTLY ONE CURRENT DEFINITION PER ROW.**
> **AMD3 added rulings *around* this table without reconciling the cells inside it, leaving two defects that landed on the same implementer:** row 1's boundary check would have **refused every pinned contract test** (`RC-2`), and row 2 stated the **enforcing** form four lines above the note saying the migration executes the **reporting** form (`RC-3` — *the one conflicting current definition in the artifact*).

| | Resolution input | Output | Validation | Failure | Unresolved |
|---|---|---|---|---|---|
| ⭐ **Record location — AUTHORITATIVE resolution** *(AMD4, `RC-2`)* | work-item id | the durable evidence location | the **authoritatively resolved** location is **inside the governed evidence boundary** | ⛔ **refusal to produce an answer at all — exit 65** | ⛔ **refuse** |
| ⭐ **Record location — GOVERNED OVERRIDE** *(AMD4, `RC-2`)* | an explicit `--dir` | ⭐ **a NON-AUTHORITATIVE output location** | ⛔ **NONE. An override target OUTSIDE the governed boundary is NOT a boundary violation** — it is a technical output location that **carries no authority** | ✅ **no refusal** | n/a |
| ⭐ **Mechanism identity — REPORTING form** *(AMD4, `RC-3`)* — ✅ **THIS IS WHAT PHASE 4b IMPLEMENTS** | none | the interpreter actually used | ⛔ **none on legitimacy.** The interpreter's **identity is REPORTED** (`interpreter.path`/`isDefault`, `[substituted]`) | ✅ **no refusal — substitution is permitted and never confers authority** | n/a |
| ⚠️ **Mechanism identity — ENFORCING form** — ⛔ **DEFERRED TO `OPEN-M5`, NOT CURRENT** | none — fixed by governance | the authority interpreter | the interpreter is the governed one | ⛔ refuse | ⛔ refuse |

> ## ⛔ **AMD4 (`RC-2`) — why the boundary check had to be split, with the evidence.**
> ```
> SessionAssignmentResolverContractTest::resolve()  →  --dir=sys_get_temp_dir().'/kos-disc-…'
> WorkflowStateRecordContractTest                   →  --dir=sys_get_temp_dir().'/kos-orch-…'
> ```
> ⭐ **BOTH pinned contract suites drive BOTH components through `--dir` into a temp directory that is, by construction, OUTSIDE the governed evidence boundary.** ⇒ **an implementer applying AMD3's boundary cell literally would have made the entire pinned suite exit 65 with no report.**
> ✅ **§7 already carried the reconciliation** — *"an override may select where bytes are written, and may never confer authority on the result"* — ⛔ **but §3, which is what Phase 4b builds from, did not.** **Now it does, and it is `INV-R1`'s row-2, not a footnote.**
> ⚠️ **One sub-case is left to Phase 4b's implementer and is recorded rather than silently decided:** today `!is_dir($recordDir)` yields `UNRESOLVABLE`/exit 0 (`session-resolve.php:127`). **Whether a NONEXISTENT GOVERNED directory is *"location cannot be resolved"* (⇒ 65) or a workflow-state condition (⇒ report, exit 0) is not pinned by any test.** ⛔ **AMD4 does not decide it; it names it as a Phase-4b design decision.**

> ## ⛔ **AMD4 (`RC-3`) — the enforcing form is DEFERRED, and row 4 above is labelled so it cannot be built from by mistake.**
> **Verified in the test bodies: `T-15` sets `KOS_MECHANISM_PATH` to a substitute and asserts verdict `RESOLVED` with the substituted path reported; `T-13(b)` sets it to `/nonexistent` and asserts a report is still produced naming the unavailable mechanism.** ⇒ **an implementer building Phase 4b from the enforcing row breaks `T-15` outright and `T-13(b)`'s second assertion.**

⛔ **`INV-R1` — the resolver MUST NOT silently fall back to runtime.** A missing or invalid target is a **refusal**, never a downgrade. *(A silent fallback would re-create the split `B′` removed, and would do it invisibly.)*

> ## ⭐ **AMD3 (`CL-9`) — `INV-R1` is SPLIT, because as first worded it broke a pinned contract.**
> **The original wording — *"refuse and exit non-zero"* — collides with `AST-016` AMENDMENT 1, pinned by `T-12`: *"RESOLVED · UNASSIGNED · AMBIGUOUS · UNRESOLVABLE all exit 0. Non-zero is reserved for usage errors (64) and for refusal to produce a report at all (65)."*** ⇒ **an implementer following Phase 5 literally would have broken `T-12`.**
>
> | Condition | Behaviour | Contract |
> |---|---|---|
> | **the authority LOCATION cannot be resolved, or resolves OUTSIDE the governed boundary** | ⛔ **refuse to produce a report at all — exit 65** | ✅ **AMENDMENT 1's own *"refusal to produce a report"* clause** |
> | **the location resolved; the WORKFLOW STATE is `UNRESOLVABLE`** | ✅ **produce the report — exit 0** | ✅ **`T-12` untouched** |
>
> ⭐ **`INV-R1` binds the FIRST. `T-12` governs the SECOND. So worded, NO adopted contract is amended and NO test changes.**

⛔ **`INV-R2` — `P-1` and `P-2` are replaced by ONE resolution, not synchronised.** Keeping two defaults "in agreement" is the defect, not the fix.

⛔ **`INV-R3` — `P-4` is brought under governance** (`OPEN-M3`, Option A: the resolver governs authority-record location **and** interpreter selection).

> ## ⚠️ **AMD3 (`CL-10`) — `INV-R3` has TWO implementable forms, and only one is inside this migration.**
> | Form | Consequence |
> |---|---|
> | ✅ **REPORTING** — substitution permitted, **authority never conferred on its output**, substitution reported (today's behaviour, `C-1`) | **`T-13(b)` · `T-14` · `T-15` all keep passing** — `T-13(b)` and `T-15` **deliberately set** `KOS_MECHANISM_PATH` |
> | 🔴 **ENFORCING** — the literal reading: *"an environment variable may not select the authority interpreter"* | **removes the exact seam those two tests exercise** ⇒ **amending `AST-016`'s AMENDMENT-2 contract, which is beyond a relocation** |
>
> ⭐ **THIS MIGRATION EXECUTES ON THE REPORTING FORM.** It changes no adopted contract. **Enforcement is a separately authorized act, and whether it authorizes the contract amendment is `OPEN-M5` — routed to PO/ARB, decided by nobody here.**

⚠️ **`INV-R4` — the invariant binds components, not the filesystem** (§1.4). It is enforceable at every resolving call site and **advisory for ad-hoc reads**; the plan does not claim otherwise.

> ## ⭐ **AMD3 (`CL-3`) — `INV-R5`: THE RESOLVER MUST EXIST BEFORE PHASE 5 CONSUMES IT.**
> **The original plan switched every path *"to the §3 resolver"* while no phase produced one, and §7 declined to restrict the overrides that criteria 6 and 7 require closed.** ⇒ **the seven phases asserted an outcome they could not reach.**
> ⭐ **§3 is a SPECIFICATION, not an artifact.** The artifact is produced by a **named act — Phase 4b (§4) — an authorized implementation slice against `workflow-state.php` and `session-resolve.php`,** which is **runtime-code change and therefore outside this planning artifact's fence** (§12). **Phase 5 may not begin until that act has landed and its own RED/GREEN evidence exists.**
> ⛔ **What `INV-R5` does NOT do:** it does not design the resolver, choose its shape, or restrict `--dir`/`KOS_MECHANISM_PATH` (§7 stands). **It names the missing act and refuses to let Phase 5 assume it.**
> ⭐ **AMD4 (`RC-5b`): Phase 4b delivers the resolver DORMANT — built, tested, NOT WIRED.** ⛔ **If 4b wired it, writers would redirect at 4b, 4b would BE the switch, and §4.3's transfer instant would be indeterminate.** **Phase 5 is the sole activating act.**

## 3.1 ⭐ **AMD4 (`RC-10`) — a Phase-4b design brief the plan owes the implementer: REFUSAL MUST PRECEDE `mkdir`**

```php
function saveRecord(string $path, array $record): void {
    if (!is_dir(dirname($path))) { mkdir(dirname($path), 0777, true); }     // :101–102
```

> ⛔ **The writer CREATES ITS PARENT DIRECTORY.** ⇒ **a resolver returning a wrong-but-well-formed path does not fail loudly — it silently MANUFACTURES A NEW EVIDENCE LOCATION.** ⭐ **That is `B′`'s original defect re-created by the very component built to close it: a location acquiring authority because a write happened to land there.**
> ⇒ **`INV-R1`'s refusal MUST be enforced BEFORE `saveRecord` is reached. A refusal downstream of this `mkdir` is not a refusal — the damage is done by the time it fires.**
> ⛔ **AMD4 records this as a Phase-4b design constraint and does NOT implement it** (§12: no runtime code). ⚠️ **It is also the reason `RC-2`'s override row must not be read as loosening row 1: an override yields a non-authoritative location, and the AUTHORITATIVE resolution is exactly where this constraint bites.**

---

# 4 · Migration phases — **AMD4: the table is now in EXECUTABLE ORDER, and Phase 7 is guarded**

> ## ⭐ **`INV-ORDER` (Flag R, promoted): durability is CREATED and VERIFIED (2–4) BEFORE authority is DEMOTED (6), and removal is LAST (7).**
> ### **CONSEQUENCE: at no point in the sequence does the authority record exist in only one place.**
> **Any future re-ordering is therefore visibly a violation, not a preference.**
>
> ## ✅ **AMD4 (`RC-7a`) — the preservation argument now covers ITS OWN FULL CHANGE SET. AMD3's version enumerated two of three additions and omitted the one that lands INSIDE the 2–4 span.**
> | Added phase | Where | Does it disturb `INV-ORDER`? |
> |---|---|---|
> | **0 · freeze** | **prefixed BEFORE** the sequence | ✅ no — nothing is reordered |
> | ⭐ **2b-pin · 2c-commit** | ⭐ **INSIDE the 2–4 span** *(the omission `RC-7a` names)* | ✅ **no — both CREATE durability guarantees, which is what 2–4 is for.** 2–4 still precede 6; 7 is still last |
> | **4b · resolver** | between verification and the switch | ✅ no — it gates the switch, it does not demote anything |
>
> ⛔ **`OPEN-M2`'s reordering question is still NOT resolved** — AMD4 constrains it further (`RC-1`'s split pins *when* the manifest is committed) **without deciding it.**

> ## ⭐ **AMD4 (`RC-1`, `RC-7b`) — EXECUTABLE ORDER. The table is sorted by execution, not by label.**
> **AMD3's table listed `2b` above `2` while `2b`'s own precondition was *"Phase 2 target resolved"*, and folded two acts with DIFFERENT ordering constraints into one phase — making Phase 3's precondition unsatisfiable (`RC-1`). Labels are retained for lineage; ⭐ THE ROW ORDER IS AUTHORITATIVE:**
> ```
> 0  →  1  →  2  →  2b-pin  →  3  →  2c-commit  →  4  →  4b  →  5  →  6  →  7
>                   ↑ BEFORE the copy          ↑ AT/AFTER the copy
> ```

| # | Phase | Act | Precondition | Produces |
|---|---|---|---|---|
| 1 | ⭐ **0 · Declared write freeze** *(AMD3, `CL-1`)* | ⭐ **AMD5 (`RD-10`): PO/ARB declares the window — ⛔ NO UNRELATED lane may append, ✅ and the MIGRATION LANE's OWN records are explicitly permitted and DECLARED IN ADVANCE as EXPECTED DELTA INPUTS (§4.0).** ⛔ **A GOVERNANCE ACT, not a lock** | the authorizing human act | **freeze declaration + the declared expected-delta list** |
| 2 | **1 · Inventory** | enumerate every record, transition, grant; **hash each file**. ⭐ **AMD3 (`CL-12`): the record predicate is EXACTLY `*.json`** — matching `session-resolve.php:130` — and ⛔ **any `*.tmp.<pid>` remnant is QUARANTINED: never migrated, never deleted**. ⭐ **AMD4 (`RC-8` minor): the manifest RECORDS ITS HASH SEMANTICS — algorithm (SHA-256) and subject (WORKING-TREE BYTES, not the git blob)** | **Phase 0 declared** | ⭐ **the FROZEN MANIFEST** — hashes + counts + hash semantics (§5) |
| 3 | **2 · Durable target** | resolve the target through existing placement governance; create it. ⭐ **`CL-5`/`RC-6`: the target carries a NON-AUTHORITATIVE MARKER from HERE until it is RETRACTED at Phase 5 step 5 — see §4.5's lifecycle.** *(Superseded wording: AMD4/AMD5 said *"for the whole 3→5 window"*, which contradicted the same section's lifecycle table once `RD-7` added the retraction — an AMD6 `DI-5` consistency repair, no change of substance.)* | resolver exit 0 | placement evidence |
| 4 | ⭐ **2b-pin · Byte-integrity pin** *(AMD4, `RC-1`/`RC-8`)* | **pin the evidence path in `.gitattributes` as `-text`.** ⛔ **AMD4 (`RC-8`): `-text` ONLY. `text eol=lf` is NOT equivalent and is withdrawn — see §4.6** | **Phase 2 target resolved** | the pin |
| 5 | **3 · Byte-preserving copy** | copy **bytes exactly** | ⭐ **AMD4: Phases 1 and 2b-pin complete** *(no longer "2b", which could not complete before Phase 3)* | the durable copy |
| 6 | ⭐ **2c-commit · Manifest + copy committed** *(AMD4, `RC-1`)* | **commit the Phase-1 manifest AND the Phase-3 copy in ONE commit**, so manifest and artifact cannot drift | **Phase 3 complete** | committed manifest + copy |
| 7 | **4 · Integrity verification** | byte equality · **hash equality** · record count · sequence continuity · provenance continuity — **against the FROZEN MANIFEST**. ⭐ **ALL-OR-NOTHING: a per-file PASS set that does not exhaust the manifest is a FAIL, not progress** | Phase 2c-commit complete | **verification evidence** |
| 8 | ⭐ **4b · Resolver exists, DORMANT** *(AMD3 `CL-3`/`INV-R5`; AMD4 `RC-5b`)* | the separately authorized implementation slice that **PRODUCES** the §3 resolver has landed with its own RED/GREEN evidence. ⭐ **AMD4 (`RC-5b`): it delivers the resolver DORMANT — BUILT BUT NOT WIRED. ⛔ Phase 4b changes no component's effective resolution, so it is NOT a switch and cannot collapse into Phase 5.** ⛔ **Runtime-code change — outside this plan's fence (§12); named here, not performed** | Phase 4 PASSED | resolver *(dormant)* + its test evidence |
| 9 | **5 · Authority path switch** — ⭐ **the SOLE activating act** | ⭐ **AMD6 (`DI-5`/`RD-1`): THE MANDATED INTERNAL ORDER IS `1 · 1b · 2 · 3 · 4 · 5`, defined ONCE in §4.3 and not restated here in a competing form** — **1** re-hash · **1b** reconcile/dispose the delta · **2** readers (`P-2`, `P-4`) · **3** THE WRITER (`P-1`) LAST *(the transfer instant)* · **4** runtime `DEMOTED` marker · **5** RETRACT the staging `NON-AUTHORITATIVE` marker. *(`P-3a/b` are NOT restricted — §7 stands.)* ⭐ **AMD5 (`RD-10`), applied at slot 1b: identical ⇒ quiet; a delta WITHIN the declared migration-lane records ⇒ EXPECTED, reconcile separately; a delta OUTSIDE them ⇒ 🔴 FREEZE VIOLATION — STOP, record, escalate, ⛔ NO authority switch.** ⭐ **AMD6 (`C-4`/`C-7`): each Phase-5 record is written to the store its SUBJECT-STATE requires — pre-switch records into the source, post-switch records into the authoritative store — and the source's expected content is CLOSED at slot 3 and recorded in the `SWITCH-OVER RECORD` (§4.3)** 🔴 ⭐ **DV CORRECTION (`DV-1`): slot 3 carries a mandatory sub-step `3(iii-b)` — the FINAL DURABLE-COPY VERIFICATION, all-or-nothing, AFTER the final copy-side write and BEFORE the authority transfer. ⛔ On FAIL: STOP · record · escalate · NO authority transfer (§4.3).** | **Phase 4 PASSED · Phase 4b landed** | ⭐ **the `SWITCH-OVER RECORD` carrying the `SOURCE FINAL-STATE ENUMERATION`** *(⭐ **the FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER** — `DV-3`/`C-16`; ⛔ **superseded wording:** *"the FIRST record in the authoritative store"*)* · re-hash comparison · ⭐ **the `3(iii-b)` VERIFICATION RECEIPT, carried by that record** · ⭐ **the reconciled delta** · the marker records |
| 10 | **6 · Authority demotion** | ⭐ **RECORD the transfer that the Phase-5 WRITER SWITCH already performed.** ⛔ **Phase 6 does not CAUSE the transfer** — §4.3 | Phase 5 complete | demotion record |
| 11 | **7 · Runtime cleanup** | remove the obsolete runtime copy | 🔴 ⭐ **AMD4 (`RC-4`): Phases 4 AND 5 passed **AND A FINAL RE-HASH PASSES** against the `SOURCE FINAL-STATE ENUMERATION` — see §4.4. ⛔ The Phase-5 slot-1 re-hash is NOT sufficient.** ⭐ **AMD6 (`DI-7`, renaming AMD5's `CASE α`/`CASE β`): a mismatch is disposed by `PRE-SWITCH DISPOSITION` (reconcile under §6, then re-verify) or `POST-DEMOTION DISPOSITION` (QUARANTINE · retain · escalate) — never one branch for both.** 🔴 ⭐ **AMD6 (`RD-3·a`/`RD-3·b`/`C-2`/`C-8`): Phase 7 REMOVES NOTHING while any quarantine is UNDISPOSED — the store is retained ENTIRE, Phase 7 is SUSPENDED, and `B′`'s target end state is NOT reached (`OPEN-M7`)** | cleanup evidence · **final re-hash evidence** · ⭐ **or a quarantine + escalation record and NO removal** |

## 4.0 ⭐ Phase 0 — why a freeze, and why it is not a lock

**The original §6.3 rejected quiescing as *"cannot be guaranteed — no lock exists, and no lane can be prevented from acting."* ✅ That is correct about LOCKS and does not settle the question, because a freeze does not need one.**

```
Phase 0   PO/ARB declares the window (a governance act — the program's existing vocabulary)
Phase 1   hash all records → COMMIT the manifest
Phase 5   RE-HASH and compare
              unchanged →  ✅ the window was PROVABLY quiet ⇒ no reconciliation, and no loss possible
              changed   →  reconcile the delta under §6, and RECORD that loss INSIDE the delta is undetectable
```

> ⭐ **This is what converts an unprovable claim into a provable one.** *"No record was lost"* is not provable in general (§1.3). ***"No write occurred during the window"* is provable by comparing the manifest's hashes.** ⛔ **The freeze is DECLARATORY: a lane that appends anyway is a governance violation, detected by the re-hash — not a prevented act.**

### 🔴 ⭐ **AMD5 (`RD-10`) — FREEZE SEMANTICS. A literal freeze is not self-consistent for the lane doing the migrating.**

**The defect, stated as the sequence it produces:**

```
Phase 0 declares "no lane may append to the corpus"
    …but the migration's OWN governance acts ARE corpus appends —
       every transition and grant is written by P-1 into .claude/runtime/workflow/*.json,
       which is the store being frozen and hashed
⇒ the Phase-1 manifest is stale the moment the migration records its next transition
⇒ the Phase-5 re-hash reports a DELTA in the NORMAL case
⇒ criterion 11's "the window was PROVABLY quiet" branch is UNREACHABLE BY CONSTRUCTION
⇒ the headline gain degrades to "always reconcile"
⇒ ⛔ and a genuine freeze VIOLATION becomes indistinguishable from the migration's own bookkeeping
```

> ⛔ **AMD5 does NOT redefine "freeze" as "ignore all writes", and does NOT remove the ability to detect an unauthorized writer.** **It partitions the writes.**

| Class | Rule | Verification treatment |
|---|---|---|
| ⛔ **UNRELATED corpus appends** — any other lane, any other work item | **PROHIBITED for the window's duration** | 🔴 **a freeze VIOLATION. Detected, recorded, escalated — never silently reconciled** |
| ✅ ⭐ **MIGRATION-LANE records** — this work item's own transitions and grants: `OPEN-M6`'s registration · plan acceptance · the Phase-0 declaration itself · the reconciliation records · ⛔ **superseded wording:** *"the switch-over record"* — ⛔ **WITHDRAWN from the source delta by the `DV-2` block below; the switch-over record lands in the AUTHORITATIVE store as the FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER (§4.3)** · **Phase 6's demotion record** · the cleanup record | ⭐ **EXPLICITLY PERMITTED, and DECLARED IN ADVANCE** | ⭐ **EXPECTED DELTA INPUTS — reconciled SEPARATELY from the frozen baseline, and named as such** |

**⇒ the Phase-0 declaration MUST enumerate, in advance, the work items and record classes whose appends are expected.** ⭐ **The verification then has three outcomes rather than two, and the middle one is the normal case:**

> ## 🔴 ⭐ **DV CORRECTION (`DV-2`) — WHAT THE DECLARED SET MUST COVER. This is the operand §4.0 owed and did not supply.**
> **The defect, stated as the sequence it produces:**
> ```
> C-4 gives the Phase-7 comparison object a THIRD operand:
>       manifest + reconciled delta + THE DECLARED PRE-SWITCH PHASE-5 RECORDS
> the declaration's contents are specified HERE, in §4.0, and nowhere else
> §4.0's illustrative list names the reconciliation records (slot 1b)
>       ⛔ it does NOT name slot 1's re-hash comparison record
>       ⛔ it does NOT name slot 2's reader-switch evidence
>       …both of which C-4 newly and EXPLICITLY places into the source
> ⇒ a declaration written from that list makes slot 3(ii) yield OUTCOME C —
>   a FREEZE VIOLATION — IN THE NORMAL CASE  ⇒  STOP BEFORE THE SWITCH
> ⇒ that is RD-10's degradation, RELOCATED from Phase 7 (where C-4 cured it)
>   to slot 3(ii) (which AMD6 created)
> ```
> **⇒ THE REQUIREMENT, and it is normative:** ⭐ **the declared set MUST COVER THE PHASE-5 PRE-SWITCH RECORD CLASSES — SLOT 1, SLOT 1b AND SLOT 2** *(the re-hash comparison record · the reconciliation or outcome-C record · the reader-switch evidence)*. ⭐ **The CLASS-BASED form is PREFERRED — *"records written by this lane for this work item"*, which §4.0's own *"record classes"* wording already permits** *(`RD-10·r2`, consumed rather than restated)*.
> ⚠️ **And one entry in the migration-lane list above is now INERT-STALE, corrected rather than left standing: *"the switch-over record"* is a migration-lane record, but after `C-4` it is NEVER written into the source** ⇒ ⛔ **it can never appear in a SOURCE delta and MUST NOT be declared as one.** ✅ **Harmless in effect; corrected because a list that no longer matches the design it feeds is the `DI-1` defect shape.**
> 🔴 ⭐ **Flag `AE` — the coupling, stated where it bites: `DV-1`'s repair verifies the durable copy AGAINST the enumeration, and this operand IS part of that enumeration.** ⛔ **Fixing `DV-1`'s timing while leaving this declaration narrow yields a verification that runs at the right moment against an INCOMPLETE object.** ⭐ **The two are one problem.**
> ⛔ **This states what the Phase-0 declaration MUST CONTAIN. It does NOT write the declaration — that is the PO/ARB's act, and it remains outstanding.**

```
Phase 5 / Phase 7 re-hash vs manifest
    identical                                    →  ✅ the window was quiet
    delta ⊆ the DECLARED migration-lane records  →  ✅ EXPECTED. Reconcile the declared delta, proceed
    delta ⊄ the declared records                 →  🔴 FREEZE VIOLATION. Stop, record, escalate
```

⭐ **This is what keeps the freeze's evidential value: an unauthorized writer is still detectable, because the test is no longer *"did anything change?"* but *"did anything change that we did not declare?"*** ⚠️ **Honest bound: the migration's own appends are strict extensions, so CASE A already handled them SAFELY — ⛔ what was lost was EVIDENTIAL CLARITY, not integrity.** ⛔ **`OPEN-M2` is further constrained and still NOT decided; no phase is reordered.**

## 4.1 ⛔ Phase 3 — what "byte-preserving" forbids

**No parse-and-reserialize · no formatting normalisation · no sequence renumbering · no timestamp rewriting · no "cleanup" of historical records.**

⚠️ **This is not stylistic. The single writer emits `json_encode(..., JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)`. A copy that re-encoded through any other setting — key order, slash escaping, unicode escaping, trailing newline — would produce a semantically equal file with a different hash, and Phase 4 could no longer distinguish "copied correctly" from "silently rewritten".** ⇒ **byte preservation is what makes verification meaningful.**

## 4.2 ⭐ Phase 7 — **removal is NOT deletion** (Flag P, promoted to invariant)

> ⚠️ **AMD6 (`DI-1·r`, the one part of that residual AMD6 could repair inside its scope) — PHASE 7 IS SPECIFIED IN FOUR PLACES, and a Phase-7 operator needs all four:** **§4.2** *(removal is not deletion)* · **§4.4** *(the final re-hash and the mismatch split)* · **§4.7** *(operator instructions)* · **§8** *(the rollback windows)*. ⭐ **The dispersal is what let §8's stale disposition row survive two amendments (`DI-4`); this pointer costs one line.**

The adopted invariant forbids resolving a conflict by **deleting historical evidence**. **Phase 7 is not that act, and the plan states the distinction rather than relying on the reader to infer it:**

| Deletion (forbidden) | Removal (Phase 7) |
|---|---|
| evidence ceases to exist | evidence **persists at the verified durable target** |
| no surviving copy | **a byte-verified copy exists and is authoritative** |
| history is lost | history is **relocated, provenance intact** |
| may occur at any time | **only after Phase 4 hash verification PASSED and Phase 5 switched** |

> **Phase 7 removes a redundant, demoted, byte-verified copy. It destroys nothing.** ⛔ **Without the Phase-4 precondition stated, Phase 7 reads on its face as history deletion and cannot be told apart from the act the invariant prohibits — which is precisely why the precondition is written into the phase and not into a footnote.**

> ## ⭐ **AMD3 (`CL-4`) — WHERE AUTHORITY TRANSFERS. Two accepted artifacts disagreed; the disagreement is now resolved.**
> | Source | Said |
> |---|---|
> | implementation design `ae451db9` §3.2/§4.1 | authority moves **at the switch** — *"before step 3: runtime; after step 3: the governance evidence boundary"* |
> | this plan, as first written | **Phase 6** *"declares the runtime copy no longer authoritative"* — a separate later act |
>
> ⭐ **RULING (mechanism, not preference): AUTHORITY TRANSFERS AT THE PHASE-5 WRITER SWITCH, because that is what redirects WRITERS.** After it, an append exists **only** in the new store. **Phase 6 is the RECORD of a transfer that has already happened.**
> ⛔ **CONSEQUENCE FOR ROLLBACK, and §8 is corrected accordingly: after the writer switch, rollback is RECONCILIATION, never *"stop"*.** *"Stop"* would abandon appends that exist only in the new store, or re-promote a store that is **provably behind** — which is the governance act §8 reserved for after Phase 6.

## 4.3 ⭐ **AMD4 (`RC-5`) — the transfer INSTANT, because Phase 5 is not atomic**

**AMD3 said *"on completion of Phase 5."* 🔴 Phase 5 switches `P-1`, `P-2` and `P-4` — several call sites, so its completion is not an instant.** ⭐ **Authority moves the moment the WRITER's resolution changes, which is INSIDE Phase 5** ⇒ **for the interval between the writer switch and Phase 5's completion, AMD3's *"before Phase 5 ⇒ stop"* rule was false for exactly the reason `CL-4` corrected one window later.**

✅ **The fix is cheap because there is only ONE writer to order — which is what the mandated block below is for.**

### 🔴 ⭐ **AMD6 (`DI-5` / `RD-1`) — THE ONE MANDATED INTERNAL ORDER. This block is the ONLY normative enumeration of Phase 5's internal order in this artifact.**

**The defect it repairs, stated as the AMD5 review stated it:** the block below **ended at step 4**, while §4.5's lifecycle table and §4 row 9 carried a **step 5**, and **acceptance criterion 14 accepted on *"Phase 5 step 5"* — a step number the *mandated* block did not define.** ⭐ **Two current, non-identical enumerations of the same order, with the acceptance object resting on the one not labelled *"mandated"*.** ⚠️ **`RD-1`'s reconciliation act had no slot in this block either — the same defect shape, which is why ONE edit closes both.**

```
Phase 5 — MANDATED INTERNAL ORDER   (AMD6: this enumeration is canonical; ⛔ no other is current)

  1    RE-HASH                    source vs the frozen manifest
  1b   RECONCILE / DISPOSE DELTA  three outcomes — and the last point at which abandonment is FREE
  2    READERS                    P-2 (session-resolve), P-4 (mechanism selection)
  3    ⭐ THE WRITER               P-1 (workflow-state)      ← ⭐ THE AUTHORITY-TRANSFER INSTANT
  4    RUNTIME DEMOTION MARKER    runtime copy = DEMOTED
  5    STAGING MARKER RETRACTION  the now-authoritative store loses NON-AUTHORITATIVE
```

⛔ **Every other site that names this order — §4 row 9, §4.5's lifecycle, §4.7's operator instructions, §8's windows and §10's criteria 13/14/16/17/18 — REFERS to this block and does not restate it in a competing form.** *(Superseded enumeration: AMD4's four-step block `1 · 2 · 3 · 4`, and AMD5's step 5 carried outside it. Retained here as labelled history, per §0.4.4.)*

> ⭐ **Readers first, writer last, and the reason is directional: a reader pointed at the new store before the writer moves reads a copy that is byte-verified and merely not-yet-current; a WRITER pointed at the new store before the readers move would produce evidence no reader can see.** ⛔ **The reverse order creates an invisible-write window; this order creates only a briefly-stale-read window.**
>
> ## ⚠️ ⭐ **DV CORRECTION (`DV-5`) — WHAT ACTUALLY BOUNDS THE STALE-READ WINDOW. The order is right; the instrument credited was wrong.**
> ⛔ **Superseded clause, retained as labelled history per §0.4.4:** *"…and the re-hash has already bounded that."* 🔴 **False after `C-4`.** **Slot 2 switches the readers to the copy, while slots 1, 1b and 2 all append INTO THE SOURCE** ⇒ **the window is NON-EMPTY BY CONSTRUCTION and contains at least slot 2's own record — and slot 1's re-hash PRECEDES all three, so it cannot bound them.**
> **⇒ THE ACTUAL BOUND, and the two things it is important not to conflate:**
> ```
> SOURCE FINAL-STATE ENUMERATION      the FIXED comparison state — measured at 3(i), CLOSED at 3(ii)
> 3(iii)                              brings the durable copy TO that state  → this is what CLOSES the window
> FINAL DURABLE-COPY VERIFICATION     3(iii-b) — verification of the durable REPRESENTATION against that
>                                     fixed state  → this is what DEMONSTRATES the window closed
> ```
> ⭐ **The bound is slot `3(i)`–`3(iii)`; `3(iii-b)` is not the bound but its evidence.** ⛔ **A comparison object is not a verification, and this correction does not let one stand in for the other.**
> ✅ **Remaining bounded observation window, stated explicitly rather than implied away:** a reader switched at slot **2** observes the copy WITHOUT the source-side appends of slots 1, 1b and 2 **until `3(iii)` carries them across.** ⭐ **It is narrow and SELF-REFERENTIAL — only THIS work item's own migration bookkeeping is affected, and only until `3(iii)`.** ⛔ **It is irreducible without atomicity, and it is given its own row in the residual-window table below.**

**⇒ §8's rollback boundary is *"the writer switch"*, not *"Phase 5"*.**

### ⭐ **AMD6 (`RD-1`) — slot `1b` has THREE outcomes, and only one of them continues**

| Outcome | Condition | Disposition |
|---|---|---|
| **A** | the re-hash is **identical** to the frozen manifest | ✅ **the window was provably quiet — continue to slot 2** |
| **B** | the delta lies **⊆ the Phase-0 DECLARED migration-lane records** (§4.0) | ✅ **EXPECTED. Reconcile the declared delta as a NAMED step, then continue to slot 2** |
| 🔴 **C** | the delta lies **⊄ the declared records** — an undeclared append, i.e. a **freeze violation** | ⛔ **STOP · RECORD · ESCALATE · NO AUTHORITY SWITCH.** ⭐ **Nothing is reordered and nothing is destroyed: slots 2–5 do not run, the source is still authoritative, and the escalation record is an ordinary pre-switch append into it** |

⭐ **This is `RD-10`'s three-way test, consumed rather than restated — §4.0 defines it once, and slot `1b` and slot `3(ii)` are the two places Phase 5 applies it.**

| Stop point | Cost of abandoning, stated precisely because the two are NOT equivalent |
|---|---|
| ⭐ **slot `1b`** | ⭐ **FREE.** **No reader and no writer has moved**, so *"stop"* is still *"stop"* and never *"undo"* (§8) |
| ⚠️ **slot `3(ii)`** | ⭐ **Still BEFORE the authority transfer — nothing irreversible, no evidence at risk — ⚠️ but the slot-2 READER switches must be reverted, so it is a small *"undo"* rather than a pure *"stop"*.** ⛔ **It is nonetheless a MANDATORY stop: passing an undeclared delta through the switch would launder it into the closed enumeration** |
| 🔴 ⭐ **DV CORRECTION: slot `3(iii-b)`** | ⭐ **Still BEFORE the authority transfer — `P-1` has NOT moved, the source is STILL authoritative, and ⛔ NOTHING has been removed.** ⚠️ **Same cost class as `3(ii)`: the slot-2 reader switches must be reverted, so it is a small *"undo"*.** ⛔ **It is a MANDATORY stop and NOT a judgement call: a `FAIL` here means the durable copy does NOT hold what the source holds, so transferring authority would make the copy authoritative while INCOMPLETE — and Phase 7 would then remove the only store that has the missing records.** ⭐ **The failure record is an ordinary PRE-SWITCH append into the source (§0.7.5)** |

### 🔴 ⭐⭐ **AMD6 (`C-4` / `C-7`) — WHERE EACH PHASE-5 RECORD IS WRITTEN. The decision, and the derivation.**

**`C-4` required this to be answered and `C-7` required Architecture — not the commission — to answer it.** ⭐ **The answer is DERIVED from §3 and from this section, so it adds no mechanism:**

```
§3 row 1  an AUTHORITATIVELY resolved location lies inside the governed evidence boundary
§3 row 2  an explicit --dir override yields a NON-AUTHORITATIVE output location
slot 3    P-1's resolution changes here — this IS the authority-transfer instant
     ⇒  before slot 3 the staging store CANNOT receive an authoritative record at all
          (a write there would be an override write, non-authoritative by §3 row 2)
     ⇒  after  slot 3 the demoted source CANNOT receive one either
          (a write there is exactly the POST-DEMOTION class §4.4 quarantines)
```

⭐ **So placement is FORCED by the resolver contract for every record except one — slot 3's own — and that one is the decision `C-4` isolates:**

| Record | Subject state | ⭐ Destination | Why |
|---|---|---|---|
| slot **1** re-hash comparison | ✅ **PRE-SWITCH SOURCE** | **the source store** | it is an observation OF the source, made while `P-1` still resolves there |
| slot **1b** reconciliation — or the outcome-C STOP/escalation | ✅ **PRE-SWITCH SOURCE** | **the source store** | same; and outcome C never reaches slot 3 |
| slot **2** reader-switch evidence | ✅ **PRE-SWITCH SOURCE** | **the source store** | the writer has not moved, so this is still an authoritative append into the source |
| ⭐ slot **3** the **`SWITCH-OVER RECORD`** | ⭐ **POST-SWITCH AUTHORITATIVE** | ⭐ **the AUTHORITATIVE store — as the FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER** | ⭐ **the decision. See the termination argument below** *(⛔ **superseded wording:** *"its FIRST record"* — `DV-3`/`C-16`)* |
| ⭐ **DV CORRECTION: slot `3(iii-b)`'s VERIFICATION RECEIPT** | ⭐ **POST-SWITCH AUTHORITATIVE** | ⭐ **the AUTHORITATIVE store — CARRIED BY the `SWITCH-OVER RECORD` at `3(v)`** | ⭐ **derived, not chosen — §0.7.5.** **It is evidence ABOUT the verification, so it exists only after the comparison result** ⇒ ⛔ **it is NOT part of the `SOURCE FINAL-STATE ENUMERATION` and NOT an operand of `3(iii-b)`.** ⛔ **On `FAIL` there is no transfer, so the failure record is an ordinary PRE-SWITCH append into the source — as for outcome C** |
| ⭐ **DV REPAIR (`RV-1`): slot `3(iii)`'s MIGRATION MECHANICAL WRITE — its EVIDENCING GOVERNANCE APPEND** | ⭐ **POST-SWITCH AUTHORITATIVE** | ⭐ **the AUTHORITATIVE store — CARRIED BY the `SWITCH-OVER RECORD` at `3(v)`** | ⭐ **derived, not chosen — `PREMISE 2`: a MIGRATION MECHANICAL WRITE confers no authority and IS EVIDENCED BY A GOVERNANCE APPEND THAT DESCRIBES IT — the identical five-line derivation as the receipt (§0.7.5).** ⛔ **The `3(iii-b)` receipt does NOT discharge it — the receipt attests the COMPARISON, not the WRITE.** ⛔ **Placed PRE-SWITCH it would land after the enumeration closed at `3(ii)` and re-open the closed delta — the `DV-2` defect class, `C-4` recursion** |
| slot **4** demotion-marker record | ⭐ **POST-SWITCH AUTHORITATIVE** | **the authoritative store** | `P-1` already resolves there; writing it into runtime would be a post-demotion write |
| slot **5** retraction record | ⭐ **POST-SWITCH AUTHORITATIVE** | **the authoritative store** | as above |
| Phase 6 demotion record · Phase 7 cleanup record | **POST-SWITCH AUTHORITATIVE** | **the authoritative store** | unchanged by AMD6; recorded here so the set is complete |

> ## ⭐ **WHY slot 3's evidence goes AFTER the switch: it is a TERMINATION argument, not a preference.**
> ### ⚠️ ⭐ **DV CORRECTION (`DV-4`) — THE LOAD-BEARING PREMISE IS NOW INSIDE THE ARGUMENT.** ⛔ **It previously sat in a different row of a different table, so the recursion read as stoppable after one iteration and the derivation omitted the step that made it a derivation.**
> ```
> PREMISE 1  the pre-switch records (slots 1, 1b, 2) are GOVERNANCE APPENDS INTO THE SOURCE
>            — because P-1 still resolves there until slot 3(iv)                        (§3, §4.3)
> PREMISE 2  ⭐ THE LOAD-BEARING ONE, PREVIOUSLY UNCITED HERE:
>            a MIGRATION MECHANICAL WRITE on a store CONFERS NO AUTHORITY, and
>            IS EVIDENCED BY A GOVERNANCE APPEND THAT DESCRIBES IT.
>            ⇒ a reconciliation is NOT a silent act: it PRODUCES an append
>            ⇒ and pre-switch, that append lands in the SOURCE                          (§4.3)
>            ⭐ `RV-1` (repair): step `3(iii)` is a THIRD mechanical write under this
>            premise — its evidencing append is CARRIED POST-SWITCH by the
>            `SWITCH-OVER RECORD` at `3(v)`, alongside the enumeration and the
>            receipt (§4.3 placement-table row)
> ------------------------------------------------------------------------------------------
> the durable copy must be brought to the source's state before authority moves,
>     or authoritatively-produced records are left behind and Phase 7 destroys them
> if slot 3's OWN evidence were written pre-switch, it would land in the source
>     ⇒ the source gains a record AFTER the copy was reconciled to it
>     ⇒ reconcile again → which, BY PREMISE 2, is itself evidenced by a further
>       source-side append → which re-opens the delta → ⛔ NON-TERMINATING
>       ⭐ WITHOUT PREMISE 2 the recursion appears to stop after one iteration,
>          because a mechanical write into the COPY need not touch the source at all.
>          PREMISE 2 IS WHAT MAKES IT NON-TERMINATING.
> writing slot 3's evidence AFTER the switch terminates it in one step:
>     P-1 has moved, so the source receives nothing further, and the copy is final
> ```
> ⛔ **This is not a claim that the pre-switch records are unimportant — it is the reason they can be closed at all.**
> ⭐ **Stated as the five steps `C-15` requires, so the termination argument is complete on its own terms:** *(1)* the `SOURCE FINAL-STATE ENUMERATION` is **CLOSED at `3(ii)`**; *(2)* the durable copy is **brought to that fixed state at `3(iii)`**; *(3)* the **FINAL DURABLE-COPY VERIFICATION at `3(iii-b)` consumes that fixed object** and creates no other; *(4)* a **`PASS` is MANDATORY** — ⛔ no human discretion bypasses a `FAIL`; *(5)* the **authority transfer at `3(iv)` can occur ONLY AFTER that `PASS`.**

### ⭐ **AMD6 (`C-4`) — the `SOURCE FINAL-STATE ENUMERATION`, and why it makes Phase 7 reachable**

**Slot 3 is where the source's expected content stops growing. AMD6 makes that instant explicit and RECORDED rather than implicit:**

```
slot 3, in order            ⛔ slot 3 is NOT renumbered. These are SUB-STEPS OF SLOT 3.
                            ⛔ The forms 4(i) / 4(ii) / 4(iii) are FORBIDDEN for these operations.
                               Slot 4 is the RUNTIME DEMOTION MARKER; slot 5 the STAGING MARKER RETRACTION.

    (i)   MEASURE the source — records, hashes, counts — after slot 2's append has landed
    (ii)  VERIFY the measurement against the expected set
              = frozen manifest + the reconciled 1b delta + the DECLARED pre-switch Phase-5 records
          → the SAME three-outcome test as slot 1b (§4.0), applied to the CLOSING measurement.
            ⛔ Outcome C here is a STOP BEFORE THE SWITCH, for the same reason it is at 1b
          ⭐ THE SOURCE FINAL-STATE ENUMERATION IS CONSTRUCTED AND CLOSED HERE — AT (ii),
            BEFORE THE FINAL COPY-SIDE WRITE. From this instant it is IMMUTABLE.
    (iii) bring the durable copy to that ALREADY-CLOSED state
              — a MIGRATION MECHANICAL WRITE, not a governance append
    (iii-b) 🔴 ⭐ FINAL DURABLE-COPY VERIFICATION            ← DV CORRECTION (DV-1). MANDATORY.
          COMPARE the FINAL durable copy against the ALREADY-CLOSED
          SOURCE FINAL-STATE ENUMERATION — PHASE-4 SEMANTICS, ALL-OR-NOTHING
          (byte · hash · count · sequence · provenance).
              PASS  → continue to (iv)
              FAIL  → ⛔ STOP · RECORD · ESCALATE · ⛔ NO AUTHORITY TRANSFER
          ⛔ THE ENUMERATION IS NEVER CONSTRUCTED OR CLOSED AGAIN HERE. It is CONSUMED, not rebuilt.
          ⛔ NO SECOND COMPARISON OBJECT IS CREATED.
          ⛔ The VERIFICATION RECEIPT is evidence ABOUT this comparison — it is NOT an operand of it
             and NOT part of the enumeration (§0.7.5).
    (iv)  ⭐ SWITCH P-1                                       ← THE AUTHORITY-TRANSFER INSTANT
    (v)   ⭐ write the SWITCH-OVER RECORD into the authoritative store, CARRYING the enumeration
              AND the (iii-b) VERIFICATION RECEIPT
```

> ## 🔴 ⭐ **DV CORRECTION (`DV-1`) — WHY `3(iii-b)` EXISTS. This is the one repair in this correction that changes what an EXECUTOR must DO.**
> **The defect, stated as the sequence the independent review stated it as:**
> ```
> Phase 4        verify the durable copy vs the FROZEN MANIFEST — ALL-OR-NOTHING     (§4 row 7)
> slot 1b        reconcile the declared delta INTO THE DURABLE COPY                  ← copy-side write #1
> slot 3(iii)    bring the durable copy to the source's closing state                ← copy-side write #2
> slot 3(iv)     SWITCH P-1 → the copy IS the authoritative store
> Phase 7        FINAL RE-HASH: runtime source vs the RECORDED ENUMERATION
>                identical → removal permitted
> ⛔ NEITHER OPERAND OF THAT GATE IS THE DURABLE COPY
> ```
> **⇒ two copy-side writes occurred after the copy's last all-or-nothing verification, and NOTHING re-verified it before the irreversible act.** 🔴 **What is lost if it fires: the reconciled `1b` delta and the pre-switch Phase-5 records — precisely the records that exist in the source and NOWHERE ELSE at the moment Phase 7 removes it — WITH NO GATE HAVING FAILED.**
> ⛔ **Why no existing gate supplies it:** **criterion 18 ASSERTED the carry-across and named §4.3's placement table as its evidence — ⭐ a specification is not a verifying act**; **criterion 2's named evidence all PREDATES write #1**; and the only Phase-4-semantics re-verification on the Phase-7 path sat inside §4.4's `PRE-SWITCH` EXCEPTION branch and `P7·2`. ⭐ **The plan re-verified the copy on the EXCEPTION path and not on the NORMAL path — and the normal path is the one that ends in removal.**
> ⭐ **`3(iii-b)` covers BOTH copy-side writes, because it runs after both.** ⭐ **Placing it before `(iv)` also means it fails at the LAST CHEAP STOP POINT — where §4.3 already puts every other pre-switch check.**
> ⭐ **Attribution, stated fairly:** the reconciliation write PRE-DATES AMD6 *(`RD-10`/§4.0 created the act; AMD6 gave it a numbered slot)*; AMD6's own additions were `3(iii)`'s carry-across, criterion 18's assertion and the strengthened *"byte-verified"* claim. ⭐ **AMD6's specificity is what made the gap findable at all — before it, Phase-5 placement was unspecified, so the finding could not be stated.**
> ⛔ **This adds ONE verification inside the current envelope. It requires NO decision, NO reordering, NO new actor, and it renumbers NOTHING.**

| Property | Consequence |
|---|---|
| ⭐ **the enumeration is the Phase-7 comparison object** | ⭐ **criterion 12's *"identical"* branch becomes REACHABLE in the normal case** — `RD-10`'s degradation does **not** recur at the irreversible gate, which is exactly what `C-4` demanded |
| ⭐ **it is FIXED and RECORDED at slot 3** | ⛔ **it can never later *absorb* a disputed write** — the first of the three unsafe readings `RD-3·a` named is structurally excluded, not merely discouraged. 🔴 ⭐ **DV CORRECTION (`C-15`): this holds ONLY because the enumeration is CLOSED AT `(ii)`, BEFORE the final copy-side write. Closing it AFTER that write would re-open the absorption failure this row claims is excluded** |
| ⭐ **verification at (ii) precedes the switch** | ⭐ **an undeclared pre-switch write is normally caught HERE, at the cheapest point, instead of at Phase 7** |
| 🔴 ⭐ **DV CORRECTION (`DV-1`): the FINAL DURABLE-COPY VERIFICATION at `(iii-b)` precedes the switch** | 🔴 ⭐ **the durable copy — the operand no gate previously covered — is verified with PHASE-4 SEMANTICS, ALL-OR-NOTHING, after the LAST copy-side write and BEFORE authority moves.** ⭐ **This is criterion 18's DEMONSTRATING ACT, and it is what makes §4.2's *"a redundant, demoted, BYTE-VERIFIED copy"* true of the pre-switch Phase-5 records at all.** ⛔ **A `FAIL` bars the authority transfer; there is no discretionary override** |
| ⭐ **DV CORRECTION: the comparison object and its verification are DISTINCT** | ⭐ **`SOURCE FINAL-STATE ENUMERATION` = the FIXED comparison state · `FINAL DURABLE-COPY VERIFICATION` = the verification of the durable REPRESENTATION against it.** ⛔ **One never stands in for the other, and ⛔ NO SECOND COMPARISON OBJECT EXISTS** — no `FES`, no `CFS`, no re-derived enumeration |
| ⭐ **two write classes are kept apart** | **GOVERNANCE APPENDS** (`P-1`; authority follows the resolution, §3) vs **MIGRATION MECHANICAL WRITES** (the Phase-3 copy, the 1b reconciliation, step (iii)). ⛔ **A mechanical write on a store confers no authority and is evidenced by a governance append that describes it** |
| ✅ **§5 is honoured, not contradicted** | the pre-switch records are **carried across** by step (iii) ⇒ ⛔ **no migration evidence is left behind in runtime**, which is what §5 forbids |

### ⭐ **AMD6 (`C-4`) — the switch-over record as DISCRIMINATOR, and the residual windows, recorded not denied**

**`RD-3` classifies a Phase-7 mismatch by whether the write preceded or followed the switch. ⚠️ Only 2 of 216 transitions carry any time field, so an ordering test cannot rest on timestamps.** ⭐ **With the enumeration recorded, the boundary is CONTENT-DEFINED:**

```
source content ⊆ the recorded enumeration        → ✅ accounted for
source content ⊇ it, with content OUTSIDE it     → that content arrived after the enumeration was taken
                                                    ⇒ POST-DEMOTION by construction, because P-1 no longer resolves there
ordering cannot be established at all            → ⛔ treat as POST-DEMOTION (AMD5's tie-break, unchanged —
                                                    the conservative direction: it preserves without promoting)
```

| Residual window | Status |
|---|---|
| ⚠️ **measure (i) → switch (iv)** | ⭐ **NEW with AMD6 and recorded here rather than discovered later.** A write landing inside it is genuinely `PRE-SWITCH` yet falls outside the enumeration, so the tie-break classifies it `POST-DEMOTION` — **safe (preserve, never promote), imprecise, and irreducible without atomicity.** ⛔ **`Increment 2` is not authorized, so this is recorded, NOT closed** |
| ⚠️ ⭐ **DV CORRECTION (`DV-5`): reader switch (slot 2) → carry-across `3(iii)` — THE STALE-READ WINDOW** | ⭐ **Added because it was ABSENT from this table while §4.3 asserted the re-hash had bounded it.** **A reader switched at slot 2 observes the copy WITHOUT the source-side appends of slots 1, 1b and 2, until `3(iii)` carries them across; `3(iii-b)` then DEMONSTRATES that it did.** ✅ **Narrow and SELF-REFERENTIAL — only this work item's own migration bookkeeping — and NON-EMPTY BY CONSTRUCTION, so it is stated rather than denied.** ⛔ **Irreducible without atomicity; recorded, NOT closed** |
| ⚠️ **slot 3 → slot 4** (`RD-2`) | unmarked stale runtime — **carried from the AMD4 review, unchanged, not AMD6's commission** |
| ⚠️ **slot 3 → slot 5** (`RD-7·b`) | the authoritative store is briefly labelled `NON-AUTHORITATIVE` — **§4.5, restated honestly by AMD6** |
| ⚠️ **final re-hash → removal** | irreducible without a lock — **§4.4, unchanged** |

⭐ **The pattern is deliberate: AMD6 shrinks two windows and INVENTS NO ATOMICITY IT CANNOT DELIVER. Each remaining window is named, bounded, and given a §8 row.**

⛔ **§8's rollback boundary remains *"the writer switch"* — slot 3 — and AMD6 does not move it.**

## 4.4 🔴 ⭐ **AMD4 (`RC-4`) — THE FINAL INTEGRITY CHECK. This is the one path by which the migration itself could destroy evidence.**

**The gap the independent review found, stated as the sequence:**

```
Phase 5   re-hash vs manifest  ← the LAST integrity check of the runtime copy under AMD3
Phase 5   switch readers, then the writer     ← not atomic with the re-hash
Phase 6   record the transfer
Phase 7   REMOVE the runtime copy             ← AMD3 precondition: "Phases 4 AND 5 passed" — NO re-check
```

> ⛔ **A write landing after the Phase-5 re-hash but before the WRITER is switched goes to the RUNTIME copy. It is not in the frozen manifest and not in either reconciliation input — and Phase 7 destroys it.**
> ⭐ **And note precisely why AMD3's own restatement cannot catch it:** the bounded claim is *"no record lost **relative to** the frozen manifest and the observed inputs"*, and a gap-window write is in **neither**. **The restatement is honest and here it is self-satisfying. The withdrawn absolute claim would have caught this.** ⛔ **That is NOT an argument for restoring the absolute claim — §6.3's withdrawal stands, and §1.3's evidence is unchanged. It is an argument for a final check.**

**⇒ Phase 7's precondition, in full:**

```
FINAL RE-HASH:  runtime source   vs   the SOURCE FINAL-STATE ENUMERATION            (§4.3)
                                      = frozen manifest + reconciled delta
                                        + the DECLARED pre-switch Phase-5 records
    identical  →  ✅ removal permitted
    different  →  ⛔ STOP. Do NOT remove.  →  disposition per the SPLIT below
```

> ⭐ **AMD6 (`C-4`) — the comparison object is now NAMED, RECORDED and FIXED, and that is what makes this gate satisfiable.** **It is the enumeration the `SWITCH-OVER RECORD` carries (§4.3), closed at slot `3(ii)`.** ⇒ **`identical` is reachable in the normal case** *(superseded wording: AMD4/AMD5 said *"frozen manifest + reconciled delta"*, which omitted Phase 5's own pre-switch records and therefore reported a delta in the NORMAL case — the defect `C-4` names)*, ⛔ **and because the object is fixed at slot `3(ii)` — BEFORE the final copy-side write — it can never later ABSORB a disputed write.**

> ## 🔴 ⭐ **DV CORRECTION (`DV-1`) — WHAT THIS GATE DOES AND DOES NOT COVER, stated here because §4.4 is the section a Phase-7 operator consults.**
> ```
> THIS gate's two operands:   the runtime SOURCE   vs   the RECORDED ENUMERATION
> ⛔ NEITHER OPERAND IS THE DURABLE COPY
> ```
> ⇒ **this gate protects the SOURCE against removal-without-recheck, and that guard holds — ⛔ but it has never covered the COPY.** ⭐ **The copy's own coverage is `3(iii-b)`, the FINAL DURABLE-COPY VERIFICATION (§4.3), which runs after the LAST copy-side write and BEFORE authority moves.** ⛔ **`RC-4` and `DV-1` are ADJACENT, not duplicates: two different operands, two different gates, both required.**
> ⭐ **Consequence for this section's own claim:** §4.2's justification for Phase 7 — *"a redundant, demoted, **BYTE-VERIFIED** copy"* — **becomes true of the reconciled `1b` delta and the pre-switch Phase-5 records only because `3(iii-b)` verifies them.** ⛔ **Without `3(iii-b)` that claim was true of neither Phase 4 nor the moment of removal, for exactly those records.**

### 🔴 ⭐ **AMD5 (`RD-3`) — THE MISMATCH DISPOSITION IS SPLIT. One trigger, two causes, OPPOSITE correct dispositions.**

**AMD4 sent every Phase-7 mismatch to *"reconcile under §6 (CASE A / CASE B)"*. ⛔ That model does not cover its own trigger: §6.3 scopes the divergence window to Phases 3–5, and CASE A APPENDS THE RUNTIME TAIL AS A LEGITIMATE EXTENSION.**

| | ⭐ **`PRE-SWITCH DISPOSITION`** *(gap-window write)* | ⭐ **`POST-DEMOTION DISPOSITION`** *(a write to a DEMOTED store)* |
|---|---|---|
| **What happened** | a write landed while **runtime was still authoritative** — after Phase 5's slot-1 re-hash and before the slot-3 writer switch (§4.3). ⭐ **AMD6: slot `3(ii)`'s closing verification now catches this in every part of the window EXCEPT the irreducible measure→switch interval** | a write landed **after authority transferred** — via `P-3a` `--dir`, an ad-hoc write, or an unswitched client |
| **Status of the bytes** | ✅ **governance evidence, authoritatively produced** | 🔴 **NOT authoritative evidence. Produced against a store that no longer held authority** |
| ⭐ **Disposition** | ✅ **RECONCILE under §6 (CASE A / CASE B)** — the existing model is CORRECT here | ⛔ **QUARANTINE. Record the conflict as evidence. ESCALATE under governance.** ⛔ **NEVER import into the authoritative store** |
| ⭐ **AMD6 name** | ⭐ **`PRE-SWITCH`** *(AMD5 called this `CASE α`)* | ⭐ **`POST-DEMOTION`** *(AMD5 called this `CASE β`)* — ⭐ **`C-6`'s mandated term, and the only one used here** |
| ⭐ **AMD6 terminating condition** | ✅ **reconcile → re-verify → removal may proceed** | 🔴 ⛔ **DOES NOT GATE ON RE-VERIFICATION AT ALL — Phase 7 is SUSPENDED, the store is RETAINED ENTIRE, and only a governed DISPOSITION releases it. See the AMD6 subsection below** |
| **Why** | runtime was the authority when the bytes were written, so §6's union preserves both sides legitimately | ⭐ **§6 CASE A would IMPORT non-authoritative bytes into the authoritative store — and §8 already rules that re-promoting a demoted source is a GOVERNANCE ACT, not a merge** |

> ⭐ **AMD6 (`DI-7`/`C-1`/`C-6`) — ONLY THIS FAMILY IS RENAMED. §6's `CASE A`/`CASE B` keep their names and their text**, because they are cited in four delivered reviews and in §10 criterion 10, whereas `CASE α`/`CASE β` were AMD5's own addition and are cited nowhere outside this plan (§0.6.2). ⭐ **The glyph collision in §8's trigger list therefore disappears by construction — there is no `β` left to mistake for a `B`.**

> ## ⛔ **AMD5 does NOT change the adopted `R-CONFLICT` invariant, and does NOT redefine post-demotion runtime bytes as evidence.**
> **`R-CONFLICT` forbids silently selecting a side, deleting historical evidence, or rewriting sequence history. ✅ Quarantine does none of those: the bytes are PRESERVED, the conflict is RECORDED, provenance is intact, and nothing is chosen.** ⭐ **What quarantine refuses is the opposite error — silently PROMOTING bytes by merging them.** ⛔ **The §6.2 clause labelling applies unchanged: this is migration interpretation, not invariant text.**
> ⚠️ **How the two cases are distinguished, since the plan must say and not imply: the writer-switch instant (§4.3) is a recorded event, and Phase 5 produces the `SWITCH-OVER RECORD`.** ⭐ **AMD6 (`C-4`) makes the test CONTENT-DEFINED rather than timestamp-defined — which it has to be, because only 2 of 216 transitions carry any time field: source content INSIDE the recorded `SOURCE FINAL-STATE ENUMERATION` is accounted for; content OUTSIDE it arrived after the enumeration was taken and is `POST-DEMOTION` by construction, since `P-1` no longer resolves there (§4.3).** ⛔ **If the ordering cannot be established from the records at all, the write is treated as `POST-DEMOTION` and escalated — AMD5's tie-break, unchanged, and still the conservative direction because it preserves without promoting.**

⭐ **One honest consequence, offered as analysis and NOT as a closure claim:** slot 3's closing verification normally catches an undeclared pre-switch write **before** the switch, so at Phase 7 the `PRE-SWITCH` branch survives mainly for the irreducible measure→switch interval (§4.3). ⛔ **The branch is NOT removed — it remains specified, because *"normally"* is not *"always"*.**

### 🔴 ⭐ **AMD6 (`RD-3·a`) — `POST-DEMOTION` GETS ITS TERMINATING CONDITION. The previous *"for either case"* rule promised a path it never defined.**

**The defect, as the independent AMD5 review derived it from the criteria as written:**

```
criterion 12   removal requires   runtime source  ==  the comparison object
criterion 15   POST-DEMOTION means those bytes are NOT reconciled — deliberately
⇒ after a POST-DEMOTION event the source can NEVER equal the comparison object
⇒ ⛔ AMD5's closing rule — "then, for EITHER case: re-verify … only then may removal proceed" —
     promised a route to removal that has no pass condition
```

⛔ **AMD6 WITHDRAWS the *"for either case"* rule and replaces it with a per-branch terminating condition.** *(Superseded wording: *"Then, for either case: re-verify (Phase 4 semantics, all-or-nothing) → only then may removal proceed."* Retained here as labelled history per §0.4.4.)*

| Branch | ⭐ Terminating condition |
|---|---|
| ✅ **`PRE-SWITCH`** | **reconcile under §6 → RE-VERIFY (Phase 4 semantics, all-or-nothing) → only then may removal proceed.** ⭐ **Unchanged — this is the branch the rule was always true for** |
| 🔴 ⛔ **`POST-DEMOTION`** | ⭐ **IT DOES NOT GATE ON RE-VERIFICATION.** **Phase 7 is SUSPENDED and handed to governance; the demoted store is RETAINED ENTIRE; the only act that can release Phase 7 is a GOVERNED DISPOSITION of the quarantine (`OPEN-M7`).** ⛔ **There is no re-verify that can pass, and the plan no longer implies one** |

> ## ⛔ **The three readings `RD-3·a` named, and what AMD6 does with each**
> | Reading | AMD6 |
> |---|---|
> | the comparison object silently absorbs the quarantined bytes | ⛔ **STRUCTURALLY EXCLUDED** — the object is fixed and recorded at slot 3 (§4.3), so there is nothing for it to absorb into |
> | the re-verify simply keeps failing forever | ⭐ **REPLACED BY AN EXPLICIT STATE:** Phase 7 is **SUSPENDED**, not silently failing, and **the plan now says the demoted store is RETAINED** — the gap the review named |
> | an implementer removes the store to make the check pass | ⛔ **FORBIDDEN IN TERMS** — criterion 20, §4 row 11 and §4.7 `P7·3` all refuse removal while a quarantine is undisposed |

### 🔴 ⭐ **AMD6 (`RD-3·b` / `C-2` / `C-3` / flag `AA`) — QUARANTINE HAS A LOCATION, and it is achieved BY LOCATION, never by renaming**

**The defect, as the review stated it:** `POST-DEMOTION` said the bytes were *"PRESERVED"*, **but the artifact named no destination** — and Phase 1's *"left in place"* precedent means *inside the runtime records*, **which is exactly what Phase 7 removes.**

| Property | Rule |
|---|---|
| ⭐ **destination** | **a governed QUARANTINE STORE — inside the governed evidence boundary** *(so the bytes are themselves durable and attestable)* **and ⛔ OUTSIDE EVERY directory scanned by the `*.json` record predicate** — `glob($recordDir . '/*.json')` at `session-resolve.php:130`, and §4's Phase-1 predicate. ⭐ **A quarantined record IS a `*.json` file, so anything inside a scanned directory would be read as a work item** |
| ⛔ **not chosen here** | **the physical sub-path is resolved at execution time through EXISTING placement governance**, exactly as §2 requires for the migration target. ⛔ **AMD6 invents no path and no layout** |
| ⭐ **byte identity** | ⭐ **the record keeps its ORIGINAL FILENAME, its ORIGINAL BYTES and therefore its ORIGINAL HASH IDENTITY.** ⛔ **NOT renamed · NOT re-encoded · NOT rewritten** — flag `AA`'s reason, stated because it is not stylistic: **renaming would break the manifest linkage that PROVES the bytes were preserved** |
| ⭐ **mechanism** | **a BYTE-PRESERVING PLACEMENT of the conflicting record into the quarantine store, while the demoted source store is RETAINED ENTIRE and UNMODIFIED.** 🔴 ⭐ **A MOVE IS REFUSED — and the reason is `QUARANTINE LAUNDERING`, stated below. ⛔ It is NOT *"a move would break the enumeration"*: that reason is INVERTED, and an implementer reasoning from it would conclude a move is harmless** *(⛔ **superseded wording, retained as labelled history per §0.4.4:** *"it would alter the demoted store's content and so break the recorded `SOURCE FINAL-STATE ENUMERATION` and the manifest linkage"* — `DV-6`)* |
| ⭐ **granularity** | ⭐ **STORE-LEVEL** (`C-2`). ⛔ **NO PARTIAL-RECORD REMOVAL SEMANTICS ARE INTRODUCED**, and none are needed: the demoted store, its records and its marker are retained **whole**, which also keeps `RC-6`'s directory-level marker rule intact |
| ⭐ **marker** | **a THIRD marker — `QUARANTINED` — on the quarantine store: directory-level, out-of-band, ⛔ never `*.json` (§4.5).** ⛔ **Distinct from `NON-AUTHORITATIVE` (staging) and `DEMOTED` (runtime): different name, different subject, different lifecycle** |
| ⛔ **what quarantine is NOT** | ⛔ **NOT authority.** A quarantined record is **not** authoritative evidence, is **not** readable as a work item, and is **never** promoted by the act of being stored somewhere governed. ⭐ **`B′`'s own lesson, applied to the new store: a location does not confer authority** |

> ## 🔴 ⭐ **DV CORRECTION (`DV-6`) — WHY A MOVE IS REFUSED: `QUARANTINE LAUNDERING`. The rule was always right; the reason given was the weaker one, and inverted.**
> **The load-bearing reason, as the chain it produces:**
> ```
> a POST-DEMOTION record is OUTSIDE the SOURCE FINAL-STATE ENUMERATION  — BY DEFINITION
>     ⇒ its presence in the demoted store is exactly WHY the final re-hash FAILS
> MOVE the record out of the demoted store
>     ⇒ the quarantined evidence is HIDDEN from the store being compared
>     ⇒ the source now MATCHES the enumeration — it SPURIOUSLY SATISFIES criterion 12
>     ⇒ the final re-hash reports "identical"
>     ⇒ ⛔ criterion 12's REMOVAL BRANCH OPENS while a quarantine stands UNDISPOSED
>     ⇒ ⛔ REMOVAL BECOMES ELIGIBLE INCORRECTLY
> ⇒ THIS IS QUARANTINE LAUNDERING. MOVE REMAINS PROHIBITED.
> ```
> ⭐ **It is `RD-3·a`'s third unsafe reading — *"an implementer removes the store to make the check pass"* — in the subtler form the section did not name: not removing the STORE, but removing THE ONE RECORD THAT MAKES IT FAIL.**
> ⛔ **The inverted reason is withdrawn, not softened.** *"A move would break the enumeration"* is **false in the direction that matters**: a post-demotion record was never IN the enumeration, so moving it does not break the comparison — **it makes the comparison PASS when it should FAIL.** ⚠️ **That is the opposite of harmless, and it is why the weaker reason had to go.**
> ✅ **The rule is DOUBLY GUARDED independently of this reasoning, and that is stated so the finding is not overdrawn:** `P7·2` forbids the move in terms, and **criterion 20 · §4 row 11 · `P7·3`** refuse removal while any quarantine is undisposed. ⭐ **The laundering path is closed twice over — but an implementer must be able to reason to it, which is what this correction restores.**
> ⛔ **This is a `RATIONALE` correction. It changes NO rule, adds NO act, and does NOT decide `OPEN-M7`.**

> ## 🔴 ⭐ **AMD6 (`C-8`, UNSOFTENED) — the consequence, which is a DOMAIN state and not a filesystem condition**
> **While ANY quarantine is UNDISPOSED:**
> ```
> Phase 7                     remains BLOCKED           (no removal, of any store)
> the affected store          remains PRESERVED ENTIRE
> the quarantined bytes       remain PRESERVED, unrenamed, hash-verifiable
> a governed DISPOSITION      is REQUIRED
> ⛔ B′'s TARGET END STATE    IS NOT REACHED — runtime does NOT become execution-only
> ⛔ the migration aggregate  CANNOT LEGITIMATELY TRANSITION TO ITS COMPLETED STATE
> ```
> ⭐ **A single undisposed quarantine can block the migration's completion INDEFINITELY. ✅ That is the SAFE direction and AMD6 does not soften it** — the alternative is promoting bytes that were written against a store that no longer held authority.
> ⛔ **AMD6 INVENTS NO DISPOSER.** **Canonical discovery ran first (`ES-005.4`, §0.6.4): the ACT CLASS exists — §8's *"re-promoting a demoted source is a GOVERNANCE ACT"* and §6.2's *"recorded separately"*, with PO/ARB deciding and Governance registering — but the disposal PATH exists nowhere, and the mechanism's own vocabulary has no `DISPOSE` act** (`REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL`). ⇒ ⭐ **`OPEN-M7` (§11): confirm the disposer, and define the admissible dispositions and the discharge evidence. ⛔ Architecture decides neither.**

⚠️ **AMD5 records the irreducible residual rather than implying it away: a write inside the interval between the FINAL re-hash and the removal itself cannot be caught without a lock, and `Increment 2` is unauthorized.** ⭐ **The exposure shrinks from three phases to one step, which is the whole gain.**

⭐ **Why this closes it and costs almost nothing: the check is 18 hash comparisons against an object that already exists, and it is the only guard standing between the last verification and an irreversible act.** ✅ **`§4.2`'s justification for Phase 7 — *"a redundant, demoted, BYTE-VERIFIED copy"* — becomes true at the moment of removal rather than true as of Phase 4.** ⚠️ **`RV-2` repair — scoped to match the `DV-1` block above:** ⛔ **superseded wording (unqualified):** *"becomes true at the moment of removal rather than true as of Phase 4"* ⛔ **current wording:** *"becomes true of the reconciled `1b` delta and the pre-switch Phase-5 records, at the moment of removal, **only because `3(iii-b)` verifies them** — and was true of **neither** Phase 4 **nor** the moment of removal, for exactly those records, without that verification."*

## 4.5 ⭐ **AMD4 (`RC-6`) — marker mechanism, specified so it is implementable without re-deriving the constraints**

| Property | Rule |
|---|---|
| **granularity** | **DIRECTORY-LEVEL**, one marker per store — not per record |
| **placement** | ⛔ **OUT-OF-BAND. In-file marking is FORBIDDEN** — it would alter evidence bytes and break §4.1's byte preservation and every hash in the manifest |
| **naming** | ⛔ **MUST NOT match `*.json`** — that glob is `session-resolve.php:130`'s record predicate and §4's Phase-1 predicate; a `*.json` marker would be read as a work item |
| ⭐ **the THREE markers** *(AMD6, `C-3`)* | **staging target: `NON-AUTHORITATIVE`**, from Phase 2 until it is RETRACTED at Phase 5 step 5 · **runtime copy: `DEMOTED`**, written at Phase 5 step 4 · ⭐ **quarantine store: `QUARANTINED`**, written only if a `POST-DEMOTION` disposition occurs (§4.4) and retracted only by a governed disposition (`OPEN-M7`). *(Superseded wording: AMD4/AMD5 said *"the two markers"* — stale the moment quarantine acquired a store of its own.)* ⛔ **The three are DISTINCT in name, subject and lifecycle, and no one of them implies another** |
| ⚠️ **reach** | **ADVISORY for the ad-hoc reader class §1.4 already bounds.** ⛔ **The marker does not make the invariant enforceable against a reader that resolves nothing** — it makes the state *visible* to a reader who looks. **Stated, not assumed away** |

### 🔴 ⭐ **AMD5 (`RD-7`) — THE STAGING MARKER IS RETRACTED. No phase did that, and the omission was durable.**

**AMD4 gave the staging marker a lifetime — *"from Phase 2 until Phase 5"* — but Phase 5's mandated steps wrote only the runtime DEMOTED marker, and no row removed the staging one.** ⭐ **Because the staging store is COMMITTED at `2c-commit`, the consequence was versioned and permanent: after the writer switch the AUTHORITATIVE store would carry an on-disk `NON-AUTHORITATIVE` label — the exact reader-visible contradiction the marker exists to prevent.** ⚠️ **Same defect shape as `RC-1` and `RC-7`: an act named in a specification and carried by no phase.**

**⇒ marker lifecycle, complete and ordered — this is the full set of marker transitions in the migration:**

| When | Act | Store |
|---|---|---|
| **Phase 2** | **WRITE** `NON-AUTHORITATIVE` | staging target |
| **Phase 5 step 4** *(after the writer switch)* | ⛔ **DEMOTE** — write `DEMOTED` | runtime copy |
| ⭐ **Phase 5 step 5** *(AMD5, `RD-7` — immediately after step 4)* | ⭐ **RETRACT** `NON-AUTHORITATIVE` | ⭐ **the now-authoritative store** |
| ⭐ **Phase 7, ONLY on a `POST-DEMOTION` disposition** *(AMD6, `C-3`)* | ⭐ **WRITE `QUARANTINED`** — and ⛔ **retract it only by a governed disposition (`OPEN-M7`)** | ⭐ **the quarantine store** |
| **Phase 7** | the runtime store and its `DEMOTED` marker are removed together — ⛔ **AMD6: NOT while any quarantine is UNDISPOSED (§4.4)** | runtime copy |

> ⭐ **ORDERING IS LOAD-BEARING AND FOLLOWS `RD-7`'s REQUIREMENT: retraction occurs AFTER the writer switch, never before.** ⛔ **Retracting earlier would label a store authoritative while the writer still appended elsewhere — asserting an authority transfer that had not happened.** ⇒ **the two windows are both closed, and neither is left open:**
> ### 🔴 ⭐ **AMD6 (`RD-7·b`) — THE CLOSURE CLAIM IS WITHDRAWN AND RESTATED. It was stronger than the design it described.**
> **Superseded claim, retained because a claim that quietly changes is worse than one that visibly does:**
> ```
> before the writer switch :  staging = NON-AUTHORITATIVE   ✅ true
> after  step 5           :  authoritative store unlabelled ✅ true · runtime = DEMOTED ✅ true
> ⛔ at NO point is the authoritative store labelled NON-AUTHORITATIVE      ← 🔴 FALSE. WITHDRAWN.
> ```
> ⛔ **It is falsified by the ordering this same section calls load-bearing: authority transfers at step 3, the retraction is at step 5** ⇒ **through steps 3→4→5 the store that is ALREADY authoritative is on-disk labelled `NON-AUTHORITATIVE`.** ⭐ **The old box enumerated *"before the switch"* and *"after step 5"* and OMITTED the interval between them — which is precisely the interval at issue.**
>
> | Interval | Staging / authoritative store | Runtime store | Authority holder |
> |---|---|---|---|
> | Phase 2 → step 3 | `NON-AUTHORITATIVE` ✅ **true** | unlabelled | **runtime** |
> | ⚠️ **step 3 → step 4** | 🔴 **`NON-AUTHORITATIVE` — now FALSE** | unlabelled *(`RD-2`, still open)* | ⭐ **the durable store** |
> | ⚠️ **step 4 → step 5** | 🔴 **`NON-AUTHORITATIVE` — still FALSE** | `DEMOTED` ✅ | ⭐ **the durable store** |
> | after step 5 | ✅ **unlabelled** | `DEMOTED` ✅ | **the durable store** |
>
> ⭐ **RESTATED CLAIM, to exactly the strength of the evidence:** *the exposure is REDUCED from a **durable, versioned** false label to the **step 3→5 interval**; it is **IRREDUCIBLE WITHOUT ATOMICITY**; it is **RECORDED, NOT CLOSED**; and §8 carries a row for an interruption inside it.*
> ⛔ **This is NOT a reason to reorder anything** — it is the same class the AMD4 review recorded as `RD-2` for the step 3→4 window and declined to block on. **The defect was the DENIAL, not the window.** ⭐ **And nothing here is unsafe: the marker is OBSERVATIONAL — §4.3's writer switch determines authority, a wrong marker misleads a reader who looks, and no evidence byte is touched in any interval.**
> ✅ **`RD-7`'s four other requirements, each satisfied by the rules already in this section rather than by new machinery: retraction is OUT-OF-BAND · DIRECTORY-LEVEL · ⛔ never `*.json` · and ⭐ EVIDENCE BYTES DO NOT CHANGE — marker management never touches a record, so every hash in the manifest and every Phase-4/5/7 comparison is unaffected.**
> ⛔ **No new authority model is introduced. A marker still reports state; it never confers or removes authority — §4.3's writer switch does that, and this is only its visible trace.**

## 4.6 ⭐ **AMD4 (`RC-8`) — `-text` and `text eol=lf` are NOT interchangeable. The alternative is withdrawn.**

| Form | Effect | Verdict |
|---|---|---|
| ⛔ `text eol=lf` | **keeps text normalisation ON** and merely fixes the checkout direction. **A record that ever contained a raw `CR` would be silently rewritten on commit** — the exact class of silent byte rewrite the pin exists to prevent | 🔴 **WITHDRAWN — it cannot support an unconditional claim** |
| ✅ **`-text`** | **disables conversion in BOTH directions** | ⭐ **the only form that makes byte preservation unconditional** |

✅ **Measured today: 0 of 18 records contain a `CR` byte, so both forms behave identically NOW.** ⛔ **The claim is about a FUTURE verifier, so it must not rest on a present coincidence.**

## 4.7 ⭐ **AMD6 — OPERATOR INSTRUCTIONS for Phase 5 and Phase 7.** ⛔ **This section RESTATES no rule; it SEQUENCES the ones above**

> ⛔ **Normative authority stays where it is:** the Phase-5 order is §4.3's mandated block, the Phase-7 disposition is §4.4, the markers are §4.5, the freeze test is §4.0. ⭐ **This section exists because `C-12` requires every AMD6 act to reach an OPERATOR INSTRUCTION, and because the AMD5 review found the plan's Phase-7 guidance dispersed across three sections with one of them stale (`DI-4`).** ⛔ **If this section and a normative section ever disagree, the normative section governs and the disagreement is a defect to report, not a choice to make.**

### **PHASE 5 — in the mandated order `1 · 1b · 2 · 3 · 4 · 5` (§4.3). ⛔ Do not begin without the Phase-4b authorization and a PASSED Phase 4.**

| ID | Do | ⛔ STOP if |
|---|---|---|
| **`P5·1`** | **Re-hash the source** against the frozen manifest. **Record the comparison as a pre-switch append into the source** | the manifest is not the committed `2c-commit` object |
| **`P5·1b`** | **Classify the delta by §4.0's three outcomes.** **A** identical → continue · **B** delta ⊆ the declared list → **reconcile it into the durable copy as a NAMED step**, record it in the source, continue · **C** delta ⊄ the declared list | ⛔ **outcome C: STOP · record · escalate · DO NOT switch anything.** ⭐ **This is the last point at which abandonment is FREE — `P5·3(ii)`'s stop is still safe, but it must revert the reader switches (§4.3)** |
| **`P5·2`** | **Switch the READERS — `P-2`, then `P-4`.** Record it as a pre-switch append into the source | a reader cannot resolve the durable location *(`INV-R1`: refuse, do not fall back)* |
| **`P5·3`** | ⭐ **(i) MEASURE the source · (ii) VERIFY it against `manifest + reconciled delta + the declared pre-switch Phase-5 records`, by §4.0's SAME three outcomes — ⭐ THE `SOURCE FINAL-STATE ENUMERATION` IS CLOSED HERE, AND IS IMMUTABLE FROM THIS INSTANT · (iii) bring the durable copy to that ALREADY-CLOSED state · 🔴 ⭐ (iii-b) FINAL DURABLE-COPY VERIFICATION — compare the FINAL durable copy against that ALREADY-CLOSED enumeration, PHASE-4 SEMANTICS, ALL-OR-NOTHING · (iv) SWITCH `P-1` · (v) write the `SWITCH-OVER RECORD` into the AUTHORITATIVE store, carrying the enumeration AND the `(iii-b)` verification receipt** | ⛔ **(ii) yields outcome C — STOP BEFORE THE SWITCH.** 🔴 ⛔ **(iii-b) FAILS — STOP · RECORD · ESCALATE · DO NOT SWITCH `P-1`. There is no discretionary override and no partial pass** *(`DV-1`)*. ⛔ **Never re-construct or re-close the enumeration at (iii) or (iii-b) WITHIN THIS SLOT-3 RUN — it is CONSUMED, not rebuilt; ⛔ never create a second comparison object.** ⚠️ **`RV-7` repair — scoping clause: a `3(iii-b)` FAIL is a STOP, not a completion — a LATER re-attempt RE-MEASURES at (i) and RE-CLOSES a FRESH enumeration at (ii); the closed object belongs to the aborted run only.** ⛔ **Never write the switch-over record into the source; that is the placement defect `C-4` exists to prevent** |
| **`P5·4`** | **Write the runtime store's `DEMOTED` marker** — directory-level, out-of-band, ⛔ never `*.json`. Record it in the authoritative store | the marker would touch a record's bytes |
| **`P5·5`** | ⭐ **RETRACT the staging store's `NON-AUTHORITATIVE` marker.** Record it in the authoritative store | ⛔ **step 3 has not completed — retracting earlier asserts a transfer that has not happened** |

⚠️ **Between `P5·3(i)` and `P5·3(iv)`, and between `P5·3` and `P5·5`, the windows §4.3 and §4.5 name are open. They are irreducible without atomicity. ⛔ Do not invent a lock; §8 tells you what an interruption there means.**

### **PHASE 7 — ⛔ the IRREVERSIBLE act. Nothing here is a judgement call.**

| ID | Do | ⛔ STOP if |
|---|---|---|
| **`P7·1`** | **Run the FINAL re-hash: source vs the `SOURCE FINAL-STATE ENUMERATION` recorded in the switch-over record.** **Identical → removal is permitted.** **Different → classify by §4.4: content inside the enumeration is accounted for; content outside it is `POST-DEMOTION`; unestablishable ordering is `POST-DEMOTION`** | ⛔ **any mismatch: STOP. DO NOT REMOVE. Classify before doing anything else** |
| **`P7·2`** | **`PRE-SWITCH`** → reconcile under §6, **re-verify (Phase 4 semantics, all-or-nothing)**, then remove. **`POST-DEMOTION`** → ⭐ **place the record byte-for-byte in the governed QUARANTINE STORE (original filename, original bytes, original hash; ⛔ never renamed, never rewritten), mark that store `QUARANTINED`, record the conflict, escalate** | ⛔ **do not import `POST-DEMOTION` bytes into authoritative evidence** · 🔴 ⛔ **do not MOVE the record out of the demoted store — a MOVE hides the quarantined evidence, makes the source SPURIOUSLY SATISFY criterion 12, and so opens the removal branch while the quarantine stands undisposed. That is `QUARANTINE LAUNDERING` (§4.4)** · ⛔ **do not place the quarantine inside any directory scanned by the `*.json` record predicate** |
| **`P7·3`** | ⭐ **If ANY quarantine is undisposed: SUSPEND Phase 7. Retain the demoted store ENTIRE — records and marker. Await a governed DISPOSITION (`OPEN-M7`). Do NOT re-verify to a pass; there is none** | ⛔ **removing the store to make a check pass destroys the bytes quarantine exists to preserve.** ⛔ **Do NOT report `B′`'s end state as reached — it is not** |

---

# 5 · Migration evidence — it is itself governance evidence (Flag Q, promoted)

**The migration's own acts produce records that evidence the relocation's integrity:** the Phase-1 inventory · the Phase-4 hash comparison · reconciliation records · the switch-over record — ⭐ **carrying the `SOURCE FINAL-STATE ENUMERATION` AND the `3(iii-b)` VERIFICATION RECEIPT** *(`RV-4` repair — the receipt was omitted here; §0.7.5's derivation cites this section as governing migration-evidence placement)* · the demotion record · the cleanup record.

> ⛔ **Under `B′` these belong to the GOVERNANCE EVIDENCE boundary, not to runtime.** **If migration evidence were left in `.claude/runtime/`, the migration would generate exactly the class of record the decision was made to protect and leave it in the location the decision rejects.**

> ## ⚠️ **AMD3 (`CL-11`) — *"durable"* has been carrying TWO different properties. They are separated here.**
> | Sense | Status after migration |
> |---|---|
> | **ATTESTABILITY** — the record is versioned, so any later reader can verify what it said | ✅ **this is what the migration cures, and it is the whole point of `B′`** |
> | **CRASH DURABILITY** — the bytes survive power loss | 🔴 **UNCHANGED.** `saveRecord` does **not** `fsync`; `rename` gives atomicity of *visibility*, not durability. **Pre-existing, out of scope, and not claimed** |

✅ **They land at the same resolved durable target as the corpus they attest.** ⭐ **AMD5 (`DI-2`): the Phase-1 frozen manifest is committed at `2c-commit`, in the same commit as the Phase-3 copy, so manifest and artifact cannot drift.** *(Superseded wording: AMD3/AMD4 said *"committed at Phase 2b"* — **impossible after `RC-1`'s split, because `2b-pin` executes BEFORE Phase 3**.)* ⚠️ **Ordering consequence:** Phase 1 and Phase 4 produce evidence **before** Phase 2's target may exist — so either the target is created first (Phase 2 before Phase 1's write) or the early evidence is staged and committed to the durable boundary as soon as the target exists. **`OPEN-M2` records this; the plan does not resolve it by reordering the mandated phases.**

---

# 6 · `R-CONFLICT` — the adopted invariant, then migration guidance, **kept apart**

## 6.1 The adopted invariant — quoted, not paraphrased, not extended

> **"A governance evidence conflict MUST NOT be resolved by silently selecting one side, deleting historical evidence, or rewriting sequence history; resolution MUST preserve provenance, sequence integrity and reconstruction capability."**

⛔ **Nothing in §6.2 is invariant text.**

## 6.2 **Migration interpretation** *(operational guidance for this migration only)*

> *If resolution requires a new governance decision, that decision is recorded separately while the conflicting historical evidence is preserved.*

**Labelled as guidance per AMD2 requirement 1, and Flag S is closed on that basis: this clause is NOT adopted into the invariant.**

## 6.3 Why a divergence window exists at all

**Phases 3–5 span real time, and the corpus is live.** A writer may append to the runtime record after Phase 3's copy and before Phase 5's switch. **The window is unavoidable; what is designed is its handling.**

### CASE A — strict extension *(common prefix identical)*

**Migration interpretation:** preserve **both** inputs · preserve the **union** · preserve **every** sequence · **append the missing tail**. ⛔ **This is not selection of one side** — the union is a superset of both.

> ## ⭐ **AMD3 (`CL-8`) — what *"common prefix"* is a prefix OF. The original wording was not implementable.**
> ⛔ **`saveRecord` rewrites the ENTIRE file on every append, so a FILE-LEVEL byte-prefix comparison is meaningless** — the closing brackets move, and the byte prefixes diverge at the first structural difference no matter how well the content agrees.
> ✅ **The comparison is ELEMENT-WISE over the decoded `transitions` array**, element `i` against element `i`, each compared by canonical re-encode.
> ⭐ **AMD4 (`RC-9`) — *"canonical re-encode"* is now DEFINED: RECURSIVE KEY ORDERING before encoding.** In PHP, `json_decode`→`json_encode` preserves **insertion** order, so two semantically equal objects with different key order would compare unequal. ⛔ **That fails SAFE — a false CASE B (escalate) rather than a false CASE A (silent merge) — and it is low-risk today because one writer produced both copies. It is defined anyway, because "fails safe" is not the same as "defined".**
> ⛔ **Canonicalization is COMPARISON-ONLY. It never touches the copied bytes** (§4.1).
> ⭐ **And this is legitimate: §4.1 forbids parse-and-reserialize for the COPY, not for the COMPARISON.** Without saying so, an implementer either performs the wrong comparison or believes parsing is prohibited.

### CASE B — same sequence, different content

**Migration interpretation:** preserve **both** records · **record the conflict** · ⛔ **do not select a winner silently** · **escalate**.

### Post-reconciliation invariants — ⚠️ **AMD3 (`CL-1`): three are checkable; the fourth was WITHDRAWN**

```
sequence set is DENSE          (no gaps introduced)                  ✅ checkable
sequence is MONOTONIC                                               ✅ checkable
result is a SUPERSET of both inputs                                 ✅ checkable — of the INPUTS OBSERVED
no record lost RELATIVE TO THE FROZEN MANIFEST AND BOTH INPUTS      ✅ checkable  ← AMD3 replacement
```

> ## ⛔ **WITHDRAWN: *"NO record silently dropped"* as an absolute guarantee.**
> **It does not follow from the three properties above it, and §1.3 demonstrates it is false in the general case: a lost transition leaves a PERFECTLY DENSE sequence, because the clobbering writer reuses the lost writer's sequence number.**
>
> | Claim | Status |
> |---|---|
> | *"No record was lost relative to the frozen manifest and the observed inputs"* | ✅ **provable — and it is what §10 criterion 2 now says** |
> | *"No record could ever have been lost before observation"* | ⛔ **NOT provable by this or any migration.** Only `Increment 2` could make it so, and `Increment 2` is unauthorized |
>
> ⭐ **And the sharpest operational consequence: a loss INSIDE the reconciliation tail is imported as a legitimate extension.** CASE A appends runtime's tail `M+1…N` after an identical prefix; **a clobbered tail is still dense and still a strict extension**, so CASE A fires and the loss migrates in, indistinguishable from correctness. ⇒ **Phase 0's freeze is the only thing that removes the exposure, by removing the window.**

⭐ **Dense, monotonic `seq` is what makes CASE A mechanically decidable** — a prefix comparison is exact, so "is this an extension or a divergence?" is answered by inspection rather than by judgement. ⚠️ **AMD3: that is ALL it makes decidable. Deciding CASE A vs CASE B is not the same as proving completeness, and the original plan used the one property for both.**

## 6.4 ⭐ **AMD3 (`CL-7`) — RECORD 2: grants have their own reconciliation model, because they have no `seq`**

**Measured across the corpus:**

| | |
|---|---|
| **transitions** | **216** — ✅ **every one carries `seq`**; dense and monotonic in **18 of 18** records |
| **grants** | **110** — 🔴 **ZERO carry `seq`.** No sequence, no ordering identity *(and only **2 of 216** transitions carry any time field, so there is no time dimension to substitute)* |

> ⛔ **§6.3's invariants are stated over `seq`, and `seq` does not exist on grants.** ⇒ **the only mechanical completeness test this plan specified did not reach the Authority State — the authority-bearing half, and the half `G-2` gives a single writer.**

**And the obvious substitute key is unsound. Measured: 110 grants, 109 unique `grantId`s.**

```
G-KOS-GOVGAPS-VERIFY   in KOS-GOV-GAPS-001.json        (index 0)   distinct humanActRef, distinct scope, AUTHORIZED
G-KOS-GOVGAPS-VERIFY   in KOS-GOV-GAPS-VERIFY-001.json (index 0)   distinct humanActRef, distinct scope, AUTHORIZED
```

⭐ **Two distinct authority acts share one identifier.** ⇒ ⛔ **any verification, dedup or merge keyed on `grantId` would treat them as one — a silent loss of an authority act, which is precisely what `R-CONFLICT` forbids.**

### Migration interpretation *(operational guidance for this migration only — ⛔ not invariant text)*

> *Grant reconciliation is **positional and content-based**: grant `i` of the evidence copy is compared with grant `i` of the runtime copy by canonical re-encode of the whole grant object. **CASE A** = the runtime array is a strict extension of an identical prefix ⇒ append the tail. **CASE B** = any positional disagreement ⇒ preserve both, record the conflict, escalate. ⛔ **`grantId` is NEVER the comparison key** — not for identity, not for dedup, not for the completeness count.*

✅ **Bounded honestly, so the finding is not overdrawn:** the two colliding grants sit in **different work items**, hence **different files**, so **per-record reconciliation is unaffected today** *(grant identity inside a record is array position, which byte-preserving copy preserves)*. ⭐ **And this is NOT the cause of the `109`/`110` delta** — that is `G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION` (§1.1). **Two independent facts; neither explains the other.**

---

# 7 · `--dir` override — analysis, ⛔ no restriction implemented here

> ## **INVARIANT (AMD1): an EXECUTION OVERRIDE MUST NOT CHANGE GOVERNANCE AUTHORITY OWNERSHIP.**

**Today `--dir` is unrestricted on both `P-1` and `P-2`, and `KOS_MECHANISM_PATH` is unrestricted on `P-4`.** ⇒ **an invocation can currently divert the authoritative record — and the interpreter — to an arbitrary ungoverned location.**

**The legitimate use survives and must be preserved:** tests and dry runs need to write **somewhere harmless**. **The distinction the design must carry is between a TECHNICAL OUTPUT LOCATION and GOVERNANCE AUTHORITY STORAGE:**

| Use | Verdict |
|---|---|
| `--dir` to a scratch/test location, output **not authoritative** | ✅ legitimate — it produces test artifacts, not evidence |
| `--dir` to redirect **the authoritative record** | ⛔ **must be prevented** — it transfers authority by invocation |
| `KOS_MECHANISM_PATH` to a test harness | ⚠️ same split, on the interpreter axis |

🟡 **PROPOSED shape, not implemented:** an override may select **where bytes are written**, and may **never** confer authority on the result; authority attaches only to the governed resolved location. **The validation mechanism is design work for the implementation act.** ⛔ **This plan does not restrict the flag.**

> ⚠️ **AMD3 (`CL-3`) — the consequence for acceptance, made explicit rather than left to the reader.** **Because §7 restricts nothing, `P-3a/b` survive Phase 5 untouched.** ⇒ **§10 criterion 6 was rewritten to *"every DEFAULT authority-resolution path is unified"*, which is what the phases can deliver.** ⛔ **Override hardening — `--dir` and `KOS_MECHANISM_PATH` alike — is a SEPARATELY AUTHORIZED ACT and is not a migration phase.** ✅ **`INV-R3`'s reporting form (§3) is what this migration carries; the enforcing form is `OPEN-M5`.**

---

# 8 · Rollback

**Trigger conditions:** Phase 4 verification fails · Phase 5 leaves any component unswitched · **a §6 `CASE B` conflict** *(same sequence, different content)* **is discovered mid-migration** · the resolver cannot resolve · ⭐ **AMD6: Phase-5 slot `1b` or slot `3(ii)` yields a FREEZE VIOLATION (§4.0 outcome C)** · 🔴 ⭐ **DV CORRECTION (`DV-1`): Phase-5 slot `3(iii-b)`'s FINAL DURABLE-COPY VERIFICATION FAILS (§4.3) — a MANDATORY STOP BEFORE THE AUTHORITY TRANSFER** · ⭐ **AMD6: the Phase-7 final re-hash yields a `POST-DEMOTION DISPOSITION` (§4.4) — which is a SUSPENSION, not a rollback**.
⭐ **AMD6 (`DI-7`): *"`CASE B`"* here means §6's `CASE B` and nothing else.** ⛔ **The glyph collision the AMD5 review flagged is gone — AMD5's `CASE β` is now `POST-DEMOTION DISPOSITION` (§0.6.2), so this trigger is unambiguous to an operator.**

| Rollback MUST preserve | Rollback MUST NOT |
|---|---|
| historical evidence | ⛔ delete evidence |
| sequence integrity | ⛔ rewrite history |
| provenance | ⛔ silently choose one source |
| reconstructability | ⛔ restore an obsolete runtime source **as authority** without explicit governance handling |

> ## ⛔ **AMD3 (`CL-4`) — CORRECTED. The original rule was right before Phase 5 and WRONG after it.**
> **Superseded text:** *"`INV-ORDER` makes rollback cheap before Phase 6: until authority is demoted, the runtime copy is still authoritative and the durable copy is additive — so rollback before Phase 6 is 'stop', not 'undo'."*
> 🔴 **False in the 5→6 window.** Per §4's ruling, **authority transfers on completion of Phase 5**, so appends after the switch exist **only** in the new store. *"Stop"* would abandon them, or would re-promote a store that is **provably behind** — a change of authority, i.e. the very governance act the sentence deferred to after Phase 6.

**⚠️ AMD4 (`RC-5`): the boundary is THE WRITER SWITCH, not "Phase 5" — because Phase 5 is not atomic (§4.3).**

| Window | Rollback is |
|---|---|
| ⭐ **before the Phase-5 WRITER switch** *(AMD4 — includes the reader switches)* | ✅ **"STOP", not "undo"** — the writer still appends to runtime, so the runtime copy is still authoritative and the durable copy is purely additive. `INV-ORDER` is what makes this cheap. 🔴 ⭐ **DV CORRECTION (`DV-1`): slot `3(iii-b)` falls INSIDE this window, which is the point of putting it there — a failed durable-copy verification is a *"stop"* whose only cost is reverting the slot-2 reader switches, and ⛔ NOTHING has been removed** |
| ⭐ **after the writer switch, before Phase 6** | 🔴 **RECONCILIATION under §6, never "stop"** — and the safe direction is normally **forward**: complete Phase 6 to record the transfer the writer switch performed |
| ⚠️ ⭐ **an interruption INSIDE Phase 5 steps 3→5** *(AMD6, `RD-7·b`)* | ⭐ **NOT a rollback, and NOT an authority question: authority already moved at step 3.** **The durable store is authoritative and is, for this interval, MIS-LABELLED `NON-AUTHORITATIVE`; runtime may be unmarked (steps 3→4, `RD-2`).** ⇒ ⭐ **the safe direction is FORWARD — complete steps 4 and 5, which is a marker act that touches no evidence byte.** ⛔ **Do NOT retract-then-re-write, and do NOT treat the stale label as evidence that the switch did not happen — §4.3's writer switch is the fact, the marker is only its trace (§4.5)** |
| **after Phase 6** | **a GOVERNANCE ACT** — re-promoting a demoted source is a change of authority and must be recorded as one |
| 🔴 ⭐ **at Phase 7, on a FINAL re-hash mismatch** — ⭐ **AMD6 (`DI-4`): the CURRENT semantics are §4.4's SPLIT, and they are stated here in the same terms** | ⛔ **NOT a rollback.** ⭐ **`PRE-SWITCH DISPOSITION` → a STOP-AND-RECONCILE: removal is refused until the difference is reconciled under §6 AND re-verified.** 🔴 ⭐ **`POST-DEMOTION DISPOSITION` → a SUSPENSION: quarantine · retain the store ENTIRE · escalate · ⛔ NO removal and ⛔ NO re-verification gate, because none can pass; only a governed disposition releases Phase 7 (`OPEN-M7`).** **This is the one trigger that guards an irreversible act** *(⛔ **superseded single-branch wording, retained as labelled history per §0.4.4:** *"NOT a rollback — a STOP-AND-RECONCILE. Removal is refused until the difference is reconciled and re-verified."* **That is the one-branch-for-both model `RD-3` refuted, and until AMD6 it stood here as CURRENT text in the section a Phase-7 operator consults.**)* |

⛔ **AMD3 adds one prohibited act: leaving the 5→6 window open.** It is the one state in which the two accepted artifacts could be read as disagreeing about which store is authoritative, and it is also the state in which the runtime copy is stale **with no on-disk signal** — which is why Phase 5 now writes the demotion marker in the same act (`CL-5`).

---

# 9 · DDD / knowledge model

```
EXECUTION            "what is the runtime doing?"        → ephemeral, no authority
GOVERNANCE EVIDENCE  "what authority must survive?"      → durable, authoritative
KNOWLEDGE            "what does the evidence mean?"      → interpretive, durable
AUTHORITY            "who may decide what it means?"     → PO/ARB; not a storage concept
```

**Kept apart, per the commission:** *evidence bytes ≠ evidence meaning ≠ evidence authority* · *historical location ≠ canonical future location*.

> ## ⭐ **The rule this whole migration exists to honour: do not let STORAGE LOCATION accidentally become DOMAIN OWNERSHIP.**
> **`.claude/runtime/` acquired authority because the record happened to be written there — not because runtime owns governance. The migration corrects a location, and in doing so stops an accident from reading as a decision.**

⛔ **Derived state — the fold, `authorityState`, resolver answers — is recomputable and is never a second authority source.** ⛔ **Historical records are not rewritten to make the migration look cleaner.**

---

# 10 · Acceptance criteria

**⚠️ AMD3 rewrote criteria 1, 2, 6 and 7. The superseded text is shown, because a criterion that quietly changes is worse than one that visibly does.**

| # | Criterion | Demonstrated by |
|---|---|---|
| ⭐ **1** | every authority record **in the frozen manifest** accounted for *(AMD3, `CL-2`)* | ⭐ **AMD5 (`DI-2`): the Phase-1 manifest, committed at `2c-commit`** — ⛔ **not** the literals `18 / 216 / 109`, which are unattestable (§1.1) *(superseded: "Phase 1 inventory vs 18 / 216 / 109"; and "committed at Phase 2b", which `RC-1`'s split made impossible)* |
| ⭐ **2** | **no record lost RELATIVE TO the frozen manifest and both reconciliation inputs** *(AMD3, `CL-1`; 🔴 ⭐ **DV CORRECTION `DV-1`**)* | Phase 4 count + superset check + **the Phase-5 re-hash** + 🔴 ⭐ **the `3(iii-b)` FINAL DURABLE-COPY VERIFICATION** (§4.3). ⛔ **The absolute form is withdrawn** (§6.3) *(superseded: "no record silently lost")*. ⚠️ ⭐ **`DV-1`, stated on the criterion: every previously named piece of evidence PREDATES the two copy-side writes at slot `1b` and slot `3(iii)`, so this criterion asserted a property nothing verified for the records written after Phase 4. `3(iii-b)` closes that** |
| 3 | sequence integrity preserved | density + monotonicity check ⚠️ **for transitions; grants per §6.4** |
| 4 | provenance preserved | per-record provenance continuity |
| 5 | byte integrity preserved | **hash equality**, per file — ⭐ **and the `.gitattributes` pin (`2b-pin`) is what keeps it re-checkable by a FUTURE verifier** |
| ⭐ **6** | **every DEFAULT authority-resolution path is unified** *(AMD3, `CL-3`)* | `P-1` and `P-2` replaced by one resolution (Phase 4b + Phase 5). ⚠️ **`P-3a/b` are NOT restricted — §7 stands, and override hardening is a separately authorized act** *(superseded: "all writers use one authority-resolution path")* |
| ⭐ **7** | **all readers resolve through the same boundary, and `P-4` is governed in its REPORTING form** *(AMD3, `CL-3`/`CL-10`)* | `P-2` retired; `P-4` under the resolver per `OPEN-M3` Option A; **§1.4's advisory limit stated**; ⚠️ **enforcement deferred to `OPEN-M5`** *(superseded: "`P-4` governed")* |
| 8 | runtime is not an authority source after cutover | ⭐ **Phase 5's demotion marker** *(the on-disk fact)* **and** Phase 6's demotion record *(the governance fact)* |
| 9 | migration evidence is itself durable | §5 |
| 10 | conflict handling follows the adopted invariant | §6, CASE A / CASE B, ⭐ **and §6.4 for grants** |
| ⭐ **11** | *(AMD3 `CL-1`; ⭐ **AMD5 `RD-10`**)* **the window is shown to have been quiet, OR its delta is shown to lie WITHIN the declared migration-lane records and is reconciled as a named step** | **Phase 5 re-hash vs the frozen manifest + the Phase-0 declared expected-delta list** (§4.0). ⛔ **A delta outside the declared list is a FREEZE VIOLATION, not a reconciliation** *(superseded: "the window is shown to have been quiet, or its delta is reconciled" — unreachable, because the migration's own appends always produce a delta)*. 🔴 ⭐ **DV CORRECTION (`DV-2`): the declared list MUST COVER THE PHASE-5 PRE-SWITCH RECORD CLASSES — SLOTS 1, 1b AND 2 — and the CLASS-BASED form is preferred (§4.0). ⛔ A declaration written from §4.0's former illustrative list makes slot `3(ii)` yield outcome C in the NORMAL case, which relocates `RD-10`'s degradation instead of curing it** |
| 🔴 ⭐ **12** | *(AMD4, `RC-4`; ⭐ **AMD6 `C-4`**)* **the runtime copy is verified IMMEDIATELY BEFORE it is removed** | ⭐ **Phase 7's FINAL re-hash vs the `SOURCE FINAL-STATE ENUMERATION` recorded in the switch-over record** — `manifest + reconciled delta + the declared pre-switch Phase-5 records` (§4.3, §4.4). ⛔ **Criterion 11 does not imply this one — the Phase-5 slot-1 re-hash precedes the writer switch, and the destructive act is three phases later.** ⭐ **AMD6: the *"identical"* branch is REACHABLE, because the comparison object now accounts for Phase 5's own pre-switch bookkeeping** *(superseded object: *"`manifest + reconciled delta`"*, which omitted those records and so reported a delta in the NORMAL case)*. 🔴 ⭐ **DV CORRECTION (`DV-1`), stated so this criterion is not read as more than it is: BOTH OPERANDS OF THIS GATE ARE INDEPENDENT OF THE DURABLE COPY — it verifies the SOURCE immediately before removal, and it has never verified the COPY. The copy's coverage is `3(iii-b)` (criterion 18). ⛔ Criterion 12 does NOT imply criterion 18, and neither implies the other** |
| ⭐ **13** | *(AMD4, `RC-5`; ⭐ **AMD6 `DI-5`**)* **authority moved at ONE identifiable instant** | ⭐ **Phase 5's mandated internal order `1 · 1b · 2 · 3 · 4 · 5` — the writer at slot 3** (§4.3), **executed in that order and in no other** *(superseded enumeration: *"re-hash · readers · writer LAST"*, which named neither slot `1b` nor step 5)* |
| 🔴 ⭐ **14** | *(AMD5, `RD-7`; ⭐ **AMD6 `DI-5`/`RD-7·b`**)* ⭐ **the FINAL MARKER STATE is correct: the authoritative store carries NO `NON-AUTHORITATIVE` label, and the runtime store is marked `DEMOTED` until removed** | **Phase 5 step 5's retraction record + step 4's demotion marker** (§4.5) — ⭐ **and *"step 5"* is now a step §4.3's MANDATED block defines, which it was not when this criterion was written.** ⛔ **Criterion 8 asserts runtime is not an authority source; it does NOT assert the authoritative store is unlabelled — that is this criterion.** ⚠️ **AMD6, stated so the criterion is not read as more than it is: this accepts the FINAL state. It does NOT assert the label was correct throughout — the steps 3→5 interval is a recorded, irreducible residual (§4.5, §8)** |
| ⭐ **15** | *(AMD5, `RD-3`; ⭐ **AMD6 `DI-7`**)* **every Phase-7 mismatch was disposed by ITS OWN branch** | ⭐ **`PRE-SWITCH DISPOSITION` reconciliation records, or `POST-DEMOTION DISPOSITION` quarantine + escalation records** (§4.4). ⛔ **No post-demotion runtime bytes were imported into authoritative evidence** *(superseded names: AMD5's `CASE α` / `CASE β` — §0.6.2)* |
| ⭐ **16** | *(AMD6, `RD-1`)* ⭐ **every Phase-5 slot-`1b` delta was disposed by exactly ONE of §4.0's three outcomes, and an outcome-C delta stopped Phase 5 BEFORE any switch** | **the slot-`1b` reconciliation record, or the outcome-C stop-and-escalation record** (§4.3, §4.0). ⛔ **No delta was carried past slot `1b` undisposed, and no freeze violation was reconciled as though it were declared.** ⭐ **DV CORRECTION (`DV-2`): the SAME three-outcome test at slot `3(ii)` is judged against a declared set that COVERS SLOTS 1, 1b AND 2 — so ⛔ NORMAL MIGRATION BOOKKEEPING IS NEVER CLASSIFIED AS AN UNDECLARED FREEZE VIOLATION, and a genuine violation remains detectable** |
| 🔴 ⭐ **17** | *(AMD6, `C-4`; 🔴 ⭐ **DV CORRECTION `DV-3`/`C-16`**)* ⭐ **the `SWITCH-OVER RECORD` is the FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER, and CARRIES the `SOURCE FINAL-STATE ENUMERATION` that Phase 7 compares against** *(⛔ **superseded criterion text, retained as labelled history per §0.4.4:** *"is the FIRST record in the authoritative store"* — **NOT DEMONSTRABLE AS WRITTEN: a verifier checking it literally must FAIL it, because Phase 3 has already copied 18 records into that store and §4 row 2 fixes the record predicate as EXACTLY `*.json`. This was the acceptance object resting on a claim the design does not produce — the `DI-5` shape recurring)* | **the switch-over record itself** (§4.3 slot 3), demonstrated by **ALL FOUR of `C-16`'s required demonstrations:** *(1)* ⭐ **the durable store MAY ALREADY CONTAIN copied governance records** — it does, by Phase 3; *(2)* ⭐ **those copied records DO NOT THEMSELVES ESTABLISH AUTHORITY** — `RECORD EXISTENCE ≠ AUTHORITY ESTABLISHMENT`; *(3)* ⭐ **the FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER is the authority-boundary discriminator** — the domain event is the transfer at slot `3(iv)`, not a file position; *(4)* ⭐ **later governance evidence belongs to the POST-TRANSFER state.** ⛔ **It was not written into the source; the enumeration was CLOSED at `3(ii)` and verified against the declared set BEFORE the switch; and the enumeration was not modified afterwards.** ⛔ **The discriminator is NEVER identified by physical first record, filename order, directory order, copied-record position or lowest sequence number — `PHYSICAL STORAGE ORDER ≠ DOMAIN EVENT ORDER`** |
| 🔴 ⭐ **18** | *(AMD6, `C-4`/`C-7`; 🔴 ⭐ **DV CORRECTION `DV-1`**)* ⭐ **every Phase-5 evidence record is in the store its SUBJECT-STATE requires, ⭐ AND THE CARRY-ACROSS IS VERIFIED RATHER THAN ASSERTED** | 🔴 ⭐ **THE `3(iii-b)` FINAL DURABLE-COPY VERIFICATION — Phase-4 semantics, all-or-nothing, against the already-closed `SOURCE FINAL-STATE ENUMERATION`, PASSED before `P-1` was switched** (§4.3, §4.7 `P5·3`) — **plus §4.3's placement table** for pre-switch records *(slots 1, 1b, 2)* in the source and post-switch records *(slots 3, 4, 5)* in the authoritative store. ⛔ **No post-switch record is in the demoted store, and ⛔ no migration evidence is left behind in runtime (§5).** ⚠️ ⭐ **`DV-1`, stated on the criterion itself: this criterion previously named §4.3's PLACEMENT TABLE as its evidence — ⛔ a SPECIFICATION IS NOT A VERIFYING ACT, and no phase carried the act. `3(iii-b)` is now the demonstrating act** |
| 🔴 ⭐ **19** | *(AMD6, `RD-3·b`/`C-2`/`C-3`/flag `AA`)* ⭐ **every quarantined record is intact and unreadable as a work item** | **original filename · original bytes · original hash · ⛔ not renamed · ⛔ not rewritten · located OUTSIDE every directory scanned by the `*.json` record predicate · the quarantine store carries `QUARANTINED`, distinct from `NON-AUTHORITATIVE` and `DEMOTED`** (§4.4, §4.5). ⛔ **No partial-record removal semantics were used** |
| 🔴 ⭐ **20** | *(AMD6, `RD-3·a`/`C-8`)* ⭐ **Phase 7 removed NOTHING while any quarantine was undisposed, and `B′`'s end state was not claimed** | **the demoted store RETAINED ENTIRE · the escalation record · ⛔ NO removal evidence · ⛔ NO re-verification asserted as a pass in that branch** (§4.4, §4.7 `P7·3`). ⭐ **Positively: the migration aggregate is NOT recorded as COMPLETED, and runtime is NOT reported as execution-only, while a quarantine is undisposed (`OPEN-M7`)** |

---

# 11 · Open architectural questions

| | Question | Why it is not closed here |
|---|---|---|
| **`OPEN-M1`** | **Does the governed evidence location remain gitignored?** `B′` requires durability; `.gitignore:25`/`:32` currently exclude the runtime path. **The target is under `docs/knowledgeos`, which is versioned — so the defect resolves by relocation** — but **the plan is forbidden to touch `.gitignore`**, and whether the runtime rule is later narrowed is a separate act | STOP constraint: no `.gitignore` change |
| **`OPEN-M2`** | **Phase 1/4 evidence precedes the Phase 2 target** (§5). Stage-then-commit, or create the target first? | Reordering the mandated phases is not Architecture's to do |
| ✅ **`OPEN-M3`** | ~~`P-4` is a second authority axis the decision did not record — is it within `RA-2`'s letter?~~ | ⭐ **CLOSED by PO/ARB: Option A, explicit scope extension** — the Single Authority Resolver governs **both** authority-record location **and** interpreter selection. **This entry is retained as history; the question is decided** |
| **`OPEN-M4`** | **The reader set is unbounded** (§1.4). The invariant is advisory for ad-hoc reads | A total guarantee would require a storage change — **a ledger — which the scope fence excludes** |
| ⭐ **`OPEN-M5`** *(AMD3, `CL-10`)* | **`INV-R3`'s ENFORCING form removes the seam that `T-13(b)` and `T-15` deliberately exercise. Does bringing `P-4` under governance authorize amending `AST-016`'s AMENDMENT-2 contract, or is enforcement a separate act after the migration?** | ⛔ **A contract question, not a boundary question. `OPEN-M3` is not reopened.** ✅ **The migration executes on the REPORTING form, so this does not gate it** |
| ⭐ **`OPEN-M6`** *(AMD3 §0.2; ⭐ **AMD6 `C-5`**)* | ⭐ **NO DELIVERED AMENDMENT IS REGISTERED — the question now spans AMD3 + AMD4 + AMD5 + AMD6.** ⚠️ **And the distinction matters, so it is stated rather than blurred: AMD6's COMMISSION *is* registered, in four grants** *(`…-AMD6`, `…-AMD6-C6-C8`, `…-AMD6-C9-C11`, `…-AMD6-C12`)* — **AMD3's, AMD4's and AMD5's commissions are not, and NO amendment's DELIVERY is.** **Independently re-verified here:** `grep -o 'G-KOS-GOV-STATE-DURABILITY[A-Z0-9-]*' .claude/runtime/workflow/*.json` → `…-MIGRATION-PLAN`, `-AMD1`, `-AMD2`, `-DECISION`, `-OPEN-M3-DECISION`, `-AMD6`, `-AMD6-C6-C8`, `-AMD6-C9-C11`, `-AMD6-C12` — ⛔ **no `-AMD3`, no `-AMD4`, no `-AMD5`, and no grant recording any amendment as DELIVERED** | ⛔ **Registration has exactly one writer — Governance** (`G-2`/`R5a`). **This process holds an Architecture role and will not register its own authorization** |
| 🔴 ⭐ **`OPEN-M7`** *(AMD6, `C-8` / flag `AB`)* | ⭐ **WHO DISPOSES OF A QUARANTINED GOVERNANCE-EVIDENCE RECORD, AND BY WHAT PATH?** **Two halves, and only the first has a candidate answer.** *(a)* **The disposer:** canonical discovery (`ES-005.4`, §0.6.4) finds the ACT CLASS already governed — §8's *"re-promoting a demoted source is a **governance act**"* and §6.2's *"recorded separately"* ⇒ **the existing pair, PO/ARB decides · Governance registers.** ⛔ **Architecture proposes that reading and does NOT decide it; no new disposer, role or owner is created.** *(b)* **The path:** ⛔ **it exists NOWHERE** — no admissible-outcome set, no discharge evidence, and **measured: the mechanism has no `DISPOSE` act at all** *(`workflow-state.php` transition types: `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL`)* | ⛔ **A governance question, not a boundary question, and `C-8`/flag `AB` explicitly leave the choice to PO/ARB.** 🔴 **It is the one open item that can block the migration's COMPLETION indefinitely: while a quarantine is undisposed, Phase 7 is suspended and `B′`'s end state is not reached (§4.4)** |

## 11.1 🔴 ⭐ **EXECUTION-GATE RECONCILIATION — ⭐ AMD6 (`DI-6`): NINE ROWS, GROUPED BY KIND, and the gate claim now QUOTES the Gates column instead of contradicting it.**

> ⭐ **The lineage of this one table is the clearest evidence of the defect shape this chain keeps reproducing:** AMD3 said *"two require another actor"* · AMD4 said *"there are SIX"* over **eight** rows *(`DI-3`)* · **AMD5 reconciled the count to the rows but then added a *"3 GATE EXECUTION"* set that its own Gates column contradicts, and left two rows stale** *(`DI-6` = `DI-3·a` + `DI-3·b`)*. ⭐ **AMD6 reconciles the COUNT, the GROUPING and the GATES to the rows, and invents no dependency to reach a number.**
>
> | Kind | Rows | Who |
> |---|---|---|
> | **Governance acts** | **2** — amendment registration (`OPEN-M6`) · `INFO-2` provenance | **Governance** *(one writer, `G-2`/`R5a`)* |
> | **PO/ARB acts** | **4** — Phase-0 declaration · Phase-4b authorization · `OPEN-M5` · plan acceptance | **PO/ARB** |
> | **Architecture remediation** | **1** — `RC`/`RD`/`DI` correction *(this act)* | **Architecture** |
> | ⭐ **Conditional prerequisite — an OPEN governance question** *(AMD6)* | **1** — `OPEN-M7` quarantine disposition | **PO/ARB · Governance** *(⛔ undetermined — that IS the question)* |
> | **Open questions carried, not prerequisites** | **1** grouped row — `OPEN-M1` · `OPEN-M2` · `OPEN-M4` | PO/ARB |
> | ⭐ **TOTAL** | ⭐ **9 rows** | |
>
> ### ⭐ **AMD6 (`DI-6`) — WHICH ROWS GATE EXECUTION, read off the Gates column rather than asserted over it**
>
> ```
> Gates column names an EXECUTION PHASE          →  Phase-0 declaration      (Phase 1)
>                                                   Phase-4b authorization   (Phase 5)
>                                                   Architecture remediation (Phases 3 · 4b · 7)
>                                                   ⭐ OPEN-M7               (Phase 7 — CONDITIONAL)
> Gates column names ACCEPTANCE                  →  amendment registration
> Gates column names EXECUTION AS A WHOLE        →  plan acceptance
> Gates column names NOTHING                     →  OPEN-M5 · INFO-2 · OPEN-M1/M2/M4
> ```
>
> ⇒ ⭐ **THREE rows gate an execution phase unconditionally, ONE gates Phase 7 conditionally, registration gates ACCEPTANCE, and acceptance gates execution as a whole.** ⛔ **Superseded claim, retained as labelled history per §0.4.4:** AMD5's *"3 GATE EXECUTION (registration · Phase-0 · Phase-4b)"* — **which substituted registration, whose own cell says it gates *acceptance*, for the remediation row, whose cell names three execution phases.**

| Prerequisite | Actor | Gates | Status |
|---|---|---|---|
| ⭐ **AMD3 + AMD4 + AMD5 + AMD6 registration** (`OPEN-M6`) | **Governance** — one writer, `G-2`/`R5a` | acceptance | ⏳ **OPEN — FOUR amendments deep.** Independently verified: **no `-AMD3`, `-AMD4` or `-AMD5` grant exists in the corpus, and no grant records any amendment as DELIVERED.** ⭐ **AMD6's COMMISSION is registered in four grants — that is the commission, not the amendment** *(superseded wording: AMD5 said *"AMD3 + AMD4"*, stale on the day AMD5 shipped and staler now — `DI-6`)* |
| ⭐ **Phase 0 freeze declaration** — ⭐ **now carrying `RD-10`'s EXPECTED-DELTA LIST, which `C-4` makes load-bearing** | **PO/ARB** | **Phase 1** | ⏳ **required — introduced by AMD3 and not carried in its §11.** ⭐ **AMD6 raises the stakes without changing the act: the declared list is what Phase-5 slots `1b` and `3(ii)` test against, and what makes Phase 7's *"identical"* branch reachable** |
| ⭐ **Phase 4b runtime-code authorization** | **PO/ARB** | **Phase 5** | ⏳ **required — introduced by AMD3 and not carried in its §11.** ⚠️ **`RD-4` is still open inside it: the AUTHORITATIVE `INV-R1` branch has no pinned coverage** |
| **`OPEN-M5`** — enforcing form / `AST-016` contract amendment | **PO/ARB** | ⛔ **nothing** | ⏳ open — ✅ **does not gate the migration: the reporting form (§3) changes no adopted contract.** ⚠️ **`RD-6` stands: its scope includes `T-14`'s contract** |
| **Plan acceptance** | **PO/ARB** | **execution as a whole** | ⏳ after registration |
| ⭐ **`RC`/`RD`/`DI` technical remediation** | **Architecture** *(this act)* | Phases 3 · 4b · 7 | ⭐ **`RC-1`…`RC-11` CLOSED by the independent AMD4 review · `RD-10`/`DI-1`/`DI-2` CLOSED by the independent AMD5 review · `RD-7`/`RD-3`/`DI-3` ADDRESSED by AMD5 · `RD-3·a`/`RD-3·b`/`RD-7·b`/`DI-4`/`DI-5`/`DI-6`/`DI-7` + `C-1`…`C-12` ADDRESSED by AMD6** — ⛔ **NOT self-closed** (§0.4.1, §0.6) *(superseded status: AMD5's *"`RC-1`…`RC-11` … addressed by AMD4"*, stale in both halves — `DI-6`)* |
| **`INFO-2`** lane-registration provenance | **Governance** | — | ⏳ open — ⛔ not a technical Architecture issue |
| 🔴 ⭐ **`OPEN-M7`** — quarantine disposition: **the disposer AND the path** | ⛔ **UNDETERMINED — Architecture proposes the existing PO/ARB · Governance pair and decides nothing** | ⭐ **Phase 7 — CONDITIONALLY: only if a `POST-DEMOTION` quarantine exists, and then ABSOLUTELY** | ⏳ **OPEN (AMD6, `C-8`/flag `AB`).** 🔴 **While undisposed: Phase 7 suspended · the store retained entire · `B′`'s end state NOT reached** |
| `OPEN-M1` · `OPEN-M2` · `OPEN-M4` | PO/ARB | — | ⏳ unchanged; **`RC-1`'s split and `RD-10` constrain `OPEN-M2` further without deciding it** |

⚠️ **`OPEN-M5`, `OPEN-M6` and `OPEN-M7` require another actor, and so do the Phase-0 and Phase-4b acts.** ⛔ **None is decided here, and `OPEN-M3` — now closed — is not reopened by any of them.** ⭐ **AMD6 adds exactly one row and one open question, and it adds them because a Phase-7 block with no owner is worse than a named gap.**

---

# 12 · What this plan does NOT do

⛔ **No migration executed** · no file moved or copied · no default changed · **no `.gitignore` change** · ⭐ **no `.gitattributes` change** *(the pin is REQUIRED at `2b-pin` and is NOT performed — AMD5 `DI-2`)* · **no runtime code modified** — ⭐ **including the Phase-4b resolver, which AMD3 NAMES and does not build** · **no authority record modified** · **no grant registered** (§0.2) · no ledger introduced · no new placement rule · no repository layout chosen · no implementation technology selected · **`R-CONFLICT` not modified** — ⭐ **§6.4's grant rules are labelled *"Migration interpretation"* and are NOT invariant text** · no `--dir` restriction implemented · **`Increment 2` not proposed** · **no acceptance and no self-verification** · ⛔ **and AMD3 does not declare its own clarifications closed** (§0.1).

⛔ **AMD4 adds nothing to the DOING column.** Every `RC` remedy is a **sequencing, specification or wording change to this planning artifact**: `RC-1`/`RC-7b` reorder phases · `RC-2`/`RC-3` reconcile §3's cells · `RC-4` adds a precondition · `RC-5`/`RC-5b`/`RC-6`/`RC-8`/`RC-9` specify mechanism · `RC-10` records a design brief · `RC-11` narrows a claim. ⛔ **The `-text` pin is REQUIRED and NOT APPLIED. The Phase-4b resolver and the `RC-10` refusal guard are NAMED and NOT BUILT.**

⛔ **AMD6 adds nothing to the DOING column either.** Every AMD6 remedy is a **sequencing, specification, naming or enumeration change to this planning artifact**: `DI-5`/`RD-1` fix one enumeration · `RD-7·b` withdraws an overclaim · `RD-3·a` states a terminating condition · `RD-3·b`/`C-2`/`C-3` state a destination's PROPERTIES *(⛔ never a path — §2)* · `DI-4`/`DI-6`/`DI-7` repair document integrity · `C-4`/`C-7` DECIDE a placement and ⛔ **do not implement it** · `C-8` records `OPEN-M7`. ⛔ **No quarantine store was created. No marker was written. No record was placed, moved, renamed or copied.**

⛔ **THE DV CORRECTION ADDS NOTHING TO THE DOING COLUMN EITHER.** **Every `DV` remedy is a sequencing, specification, rationale or enumeration change to this planning artifact:** `DV-1` **adds ONE verification sub-step** *(`3(iii-b)`)* **and names its demonstrating criterion — ⛔ it does not perform it** · `DV-2` **states what the Phase-0 declaration must CONTAIN — ⛔ it does not write the declaration, which remains the PO/ARB's outstanding act** · `DV-3` **replaces a false claim with the domain concept** · `DV-4` **moves a premise into the argument that depends on it** · `DV-5` **re-attributes a bound and adds the window to the residual table** · `DV-6` **replaces an inverted rationale** · `DV-7` **resolves an unresolvable reference.** ⛔ **No slot renumbered · no criterion added or removed · no second comparison object · no `FES`/`CFS` · no new store, owner, boundary, gate, assurance class, routing model, provenance mechanism or ledger · `OPEN-M5` and `OPEN-M7` untouched · quarantine ownership NOT invented.**

**Verified after amending (2026-08-20, AMD4):** `.claude/runtime/` unchanged — **18 records, 216 transitions, 110 grants**, and **no `*.tmp*` remnant**; `.claude/scripts/` clean; `.gitignore` and `.gitattributes` untouched; **no durable target directory exists**; **no grant registered.**

⭐ **Verified BEFORE AND AFTER amending (2026-08-21, AMD6) — re-measured here, not quoted:** `.claude/runtime/workflow/` holds **18 records · 216 transitions · ⚠️ 114 grants · 113 unique `grantId`s**, **no `*.tmp*` remnant**, **0 of 18 records contain a `CR` byte**, `git log` on the corpus → **empty**, `git check-ignore` → `.gitignore:32`; `.gitattributes` is still **`* text=auto` with NO `-text` pin**; `.claude/scripts/` untouched; **no durable target exists**; **no quarantine store exists**; **no grant registered by this act**; the canonical aggregate is **`KOS-AIP-GOV-STATE-DURABILITY-ADR`** *(workflow `architecture-adr`, **11 grants · 6 transitions**)* and ⛔ **no second aggregate was created**.
> ⭐ **The grant count moved 110 → 114 between AMD5 and AMD6, and that is not noise — it is `RD-10`'s phenomenon and `C-4`'s premise, observed live: the four grants are AMD6's OWN commission, appended by `P-1` into the very corpus the migration freezes and hashes.** ⛔ **A literal freeze would have had to prohibit the act that authorized this amendment.** ⚠️ **The `113`-unique figure is the same independent `grantId`-collision fact §6.4 records; it explains nothing about the delta.**

---

**MIGRATION PLAN AMENDED (AMD6, then the DV CORRECTION) · STOPPING.** ⛔ **THE MIGRATION IS NOT EXECUTED AND MUST NOT BE.**

> ## 🔴 ⭐ **DV CORRECTION — STATUS, stated where it cannot be missed.**
> **`DV-1`…`DV-7` are `PROPOSED` · `ADDRESSED` · ⛔ NOT CLOSED, NOT REGISTERED, NOT ACCEPTED.** ⛔ **This process authored these repairs and MUST NOT review, close or accept them — §0.4.1's bound recurs for the fifth time.**
> 🔴 ⭐ **`DV-1` was classified UNSAFE DIRECTION by the independent AMD6 review. PHASE 5 MUST NOT EXECUTE UNTIL `DV-1`'S REPAIR HAS BEEN INDEPENDENTLY REVIEWED — the repair being present in this artifact is NOT the discharge of that condition.**
> ⭐ **Where each DV correction bites:** ⭐ **`DV-1` at §4.3 slot `3(iii-b)` — the only one that changes what an executor must DO** · **`DV-2` at §4.0's declaration rule, whose act belongs to the PO/ARB** · **`DV-3` at §0.6.3, §4 row 9, §4.3 and criterion 17 — the acceptance object** · **`DV-4`/`DV-5` inside §4.3's arguments** · **`DV-6` at §4.4 and `P7·2` — the irreversible act** · **`DV-7` at §0.6.5's trace.**
> ⛔ **NEXT ACTOR: a FRESH INDEPENDENT ARCHITECTURE REVIEW.** **Then the Governance bounded review — completeness, provenance, lineage and current/superseded document integrity only, ⛔ never citable as independent technical verification, with the `C-11` verbatim disclosure if performed by `b64828fe` — then PO/ARB.** ⛔ **Migration execution additionally awaits `OPEN-M6` registration of AMD3…AMD6, plan acceptance, the Phase-0 freeze declaration and Phase-4b authorization. ⛔ NONE of which this correction addresses or authorizes.**

> ## ⛔ **PHASE 3 MUST NOT BEGIN.** ✅ **`RC-1`…`RC-11` were CLOSED by the independent AMD4 review; `RD-10`, `DI-1` and `DI-2` by the independent AMD5 review.** ⛔ **`RD-7`, `RD-3`, `DI-3` and every AMD6 item — `RD-3·a`, `RD-3·b`, `RD-7·b`, `DI-4`, `DI-5`, `DI-6`, `DI-7`, `C-1`…`C-12` — are `ADDRESSED · NOT CLOSED`** — §0.4.1's bound recurs for the fourth time: this process wrote these remedies and will not review them (`C-12`).
> ⭐ **Where each AMD6 correction bites:** ⭐ **`C-4`/`C-7` at Phase 5 slot 3 — the placement decision, and the one that makes Phase 7's gate satisfiable** · **`DI-5`/`RD-1` at §4.3's mandated block** · **`RD-7·b` at §4.5 and §8** · **`RD-3·a`/`RD-3·b`/`C-2`/`C-3`/`C-8` at Phase 7** · **`DI-4`/`DI-6`/`DI-7` at §8, §11.1 and the naming** — and the Phase-7 cluster is again the one attached to the **irreversible** act, because it decides whether the migration may complete at all while a conflict stands undisposed.
> 🔴 ⭐ **AMD6's own honest bound, stated where it cannot be missed: the four-layer trace in §0.6.5 is DELIVERED EVIDENCE, not assurance. `C-12` is binding on both halves — the trace is required, and it DOES NOT DISCHARGE THE REVIEW GATE.**

**Next actors, in order:**

```
AMD6 (this amendment)                                   author: bc1b47ef — ELIGIBLE to author (C-12)
      ↓                                                         ⛔ BARRED from reviewing and accepting
FRESH INDEPENDENT Architecture review of AMD6           (C-11, C-12)
      |   ⛔ NOT bc1b47ef (this author) · ⛔ NOT ccf6c9c7 (raised the C-findings; barred both ways)
      |   ⛔ NOT 870305e0 (author of the RD/DI findings AMD6 repairs) · ⛔ NOT 1c8b041b · ⛔ NOT 9c908e70
      |   ⭐ "FRESH to this chain" is the requirement, and flag AC is moot for AMD6 for exactly that reason
      ↓   ⭐ reviews the REMEDY; the §0.4.1 bound recurs until an amendment produces no new remedy
GOVERNANCE BOUNDED REVIEW                               (C-11)
      |   completeness · provenance · amendment lineage · current/superseded document integrity
      |   ⛔ NOT technical, design-soundness or migration-safety verification
      |   ⚠️ if the reviewer is b64828fe, C-11's verbatim disclosure is MANDATORY in the review
      ↓
Governance registers AMD3 + AMD4 + AMD5 + AMD6          (OPEN-M6)
      ↓
PO/ARB — acceptance · OPEN-M5 if it chooses (⚠️ RD-6) · ⭐ OPEN-M7 (⚠️ or Phase 7 has no exit)
         the PHASE-0 FREEZE DECLARATION, carrying the EXPECTED-DELTA LIST (RD-10, and now C-4)
         the PHASE-4b AUTHORIZATION (⚠️ RD-4)
      ↓
Migration execution — beginning at PHASE 0, never at Phase 3
      ↓
Post-migration verification
```

**Traceability (AMD6):** ⭐ **four REGISTERED grants, cited by reference and not paraphrased into authority** — `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6` *(`C-1`…`C-5`, the binding `C-4` refinement, flags `AA`/`AB`)* · `…-AMD6-C6-C8` *(`C-6` terminology · `C-7` finding-derived sequence ≠ pre-decided mechanism · `C-8` the unsoftenable consequence)* · `…-AMD6-C9-C11` *(`C-9` aggregate identity · `C-10` eligibility · `C-11` routing)* · `…-AMD6-C12` *(`AUTHOR ≠ INDEPENDENT REVIEWER`; partially supersedes `C-10`; restores this author's authoring eligibility only)* · **the INDEPENDENT AMD5 review by `claude-code-session:ccf6c9c7`** — `RD-7·a`/`RD-7·b` *(its §4)*, `RD-3·a`/`RD-3·b`/`RD-3·c` *(its §5)*, `DI-4`…`DI-7` *(its §12)*, `DI-3·a`/`DI-3·b` *(its §9)*, its §11 interruption table, §15 residual table, §16 verdict and **§18 commission note** · AMD5 at **`7d3abc59`** · AMD4 at `0a2fa71d` · AMD3 at `bb1708b7` · technical review `a282d14b` · accepted design `ae451db9` · **primary evidence re-derived here, not quoted:** the canonical aggregate `KOS-AIP-GOV-STATE-DURABILITY-ADR` *(`workItem` and `workflow` fields read directly; **11 grants · 6 transitions**)* · `session-resolve.php:130` = `glob($recordDir . '/*.json')` · `session-resolve.php:74` · `workflow-state.php:101–102` *(`mkdir`)* · the mechanism's transition vocabulary `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL` — ⛔ **no `DISPOSE`, which is `OPEN-M7`'s evidence** · `.gitattributes` = `* text=auto`, **no pin** · `.gitignore:25,32` · **corpus re-measured 2026-08-21: 18 records · 216 transitions · 114 grants · 113 unique `grantId`s · 0 records contain `CR` · no `*.tmp*` · `git log` empty · no durable target · no quarantine store** · **name occurrence counts measured, not assumed: `CASE A` 15 + `CASE B` 8 = 23 versus `CASE α` 5 + `CASE β` 6 = 11** · **the malformed variant `C-6` forbids occurs in NO document — only inside the `C-6` grant itself — and AMD6 does not reproduce it** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2` · `ES-005.4`.

**Traceability (AMD5):** the PO/ARB commission of 2026-08-20 *(cited §0.5, **unregistered** — `OPEN-M6`)* · **the INDEPENDENT AMD4 review by `claude-code-session:870305e0`** — `RC-1`…`RC-11` **all CLOSED**; residuals `RD-7` *(§6 markers, no retraction phase)*, `RD-3` *(§4 `RC-4` disposition branch)*, `RD-10` *(§4.1 freeze self-consistency)*, `DI-1`/`DI-2`/`DI-3` *(§6 document integrity)*, its §5 open-question status and §13 verdict · AMD4 at **`0a2fa71d`** · **verified before amending:** `## 4.1` at two lines and `## 4.2` at two lines with live references bound to both senses · §4 subsection order `4.1 · 4.3 · 4.4 · 4.2 · 4.0 · 4.1 · 4.2` non-monotonic · *"Phase 2b"* live in §5 and §10 criterion 1 · §11.1 **8 rows** under a *"SIX"* heading · **corpus 18 / 216 / 110, no `*.tmp*`, no durable target, nothing executed** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2`.

**Traceability (AMD4):** the PO/ARB commission of 2026-08-20 *(cited §0.4, **unregistered** — `OPEN-M6`)* · **the INDEPENDENT AMD3 remedy review by `claude-code-session:9c908e70`** — `RC-1`…`RC-11`, its §3.4 (`RC-4` sequence gap), §5.1 circularity test, §6 `CL`-by-`CL` classification, §7.3 (`RC-10`), §8 canonical-integrity findings, §12 dependency table, §13 verdict *(10 CLOSED · 2 ADDRESSED · 0 unclosed)* · AMD3 at **`bb1708b7`** · technical review **`a282d14b`** · `INFO-1` *(closed by `RC-11`)* · **contract evidence re-cited:** `SessionAssignmentResolverContractTest` harness `--dir=sys_get_temp_dir()`, `T-8`, `T-11`, `T-12`, `T-13(b)`, `T-15` · `WorkflowStateRecordContractTest:39,70` · `workflow-state.php:101–102` *(`RC-10`'s `mkdir`)* · `session-resolve.php:127,130` · `.gitattributes:1` · **0 of 18 records contain `CR`** · **no `-AMD3`/`-AMD4` grant exists in the corpus** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2`.

**Traceability (AMD3):** the PO/ARB act of 2026-08-20 routing this amendment *(cited §0, **unregistered** — `OPEN-M6`)* · **the technical Architecture review `a282d14b`** — `CL-1`…`CL-12`, its §2 failure analysis, §3 concurrency experiment *(23/30 trials)*, §4 resolver analysis, §5 `R-CONFLICT` analysis, §6 freeze design · `G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION` *(Option A — closes `OPEN-M3`)* · the Governance completeness/provenance review *(`INFO-1` confirmed; `INFO-2` answered by §1.1 and §4)* · **re-measured 2026-08-20:** 18 records / 216 transitions / **110 grants** / **109 unique `grantId`s** / `seq` dense-and-monotonic 18-of-18 / **0-of-110 grants carry `seq`** / 2-of-216 transitions carry a time field / `git log` on any record → **empty** / `git check-attr text` → **auto** · `AST-016` contract `T-11`/`T-12`/`T-13`/`T-15` *(unamended)* · `workflow-state.php:303,309,311,326` · `session-resolve.php:90,103,130,156` · `INV-ATTR-2`/`G-2` · `G-2`/`R5a`/`R5b` · `R-34`/`P-2`.

**Traceability:** `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN` + `AMD1` (flags P/Q/R promoted to invariants; seven sections; CASE A/B) + `AMD2` (wording discipline; placement via the resolver; the Single Authority Resolver Invariant; the escalation trigger) · accepted design `ae451db9` · `B′` · the adopted `R-CONFLICT` invariant (quoted §6.1) · Decision 3 (existing placement governance) · `ADR_20260801_1740` + `scripts/doc-placement.php` (exit 0 → `docs/knowledgeos`) · **primary evidence read directly:** `.claude/scripts/workflow-state.php:25,81,100–107` · `.claude/scripts/session-resolve.php:22,74,90` · the five runtime-client shell scripts · `.gitignore:25,32` · all 18 workflow records · `RA-2` · `E-1` · `INV-ATTR-2`/`G-2` · `R-34`/`P-2`.
