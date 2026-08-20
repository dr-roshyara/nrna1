# `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN` — **AMD5 amendment summary**

**Status: 🟡 PROPOSED.** ⛔ **MIGRATION HAS NOT EXECUTED. Nothing was moved, copied, pinned, switched, marked, demoted or removed.**
**Amended artifact:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` *(AMD4 at `0a2fa71d` → AMD5)*
**Input, consumed and not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD4-ARCHITECTURE-REVIEW.md` — **INDEPENDENT** AMD4 review by `claude-code-session:870305e0`, verdict **`PASS WITH DESIGN CLARIFICATIONS`**, ⭐ **`RC-1`…`RC-11` ALL CLOSED**.
**Producing process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:bc1b47ef` — **author of AMD3, AMD4 and the original technical review.** **`R-34`/`P-2`: this process must not verify or accept the plan or this amendment.**
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**.

---

# 1 · Scope

**AMD5 addresses ONLY the six residuals the independent AMD4 review raised.** ⛔ **No architecture redesigned · no fixed decision reopened · no phase reordered · no new authority boundary, owner, ledger or bounded context.**

| | |
|---|---|
| **Input verdict** | 🟡 `PASS WITH DESIGN CLARIFICATIONS` — ⭐ **`RC-1`…`RC-11` CLOSED** |
| **Residuals consumed** | **3 design gaps** — `RD-7` · `RD-3` · `RD-10` · **3 document-integrity defects** — `DI-1` · `DI-2` · `DI-3` |
| **AMD5 disposition** | ⭐ **all six ADDRESSED** |
| ⛔ **Self-closure** | **NONE** — §5 |
| **Gate state** | ⛔ **Phase 3 must not begin** |

---

# 2 · Disposition

## `RD-7` — staging marker retraction → **ADDRESSED** *(§4.5 · §4 rows 3 & 9 · §10 criterion 14)*

**Defect:** the staging `NON-AUTHORITATIVE` marker had a stated lifetime *"from Phase 2 until Phase 5"*, but **no phase retracted it** — and because the staging store is committed at `2c-commit`, the consequence was **durable and versioned: after the writer switch the AUTHORITATIVE store would carry an on-disk `NON-AUTHORITATIVE` label.**

**Correction — Phase 5 gains STEP 5, and the full marker lifecycle is now tabled:**

| When | Act | Store |
|---|---|---|
| Phase 2 | **WRITE** `NON-AUTHORITATIVE` | staging target |
| Phase 5 step 4 | **DEMOTE** | runtime copy |
| ⭐ **Phase 5 step 5** | ⭐ **RETRACT `NON-AUTHORITATIVE`** | **the now-authoritative store** |
| Phase 7 | removed with the runtime store | runtime copy |

✅ **All six `RD-7` requirements met:** retraction is **after the writer switch** · the authoritative store is **never left labelled non-authoritative** · **out-of-band** · **directory-level** · ⛔ **never `*.json`** · ⭐ **evidence bytes unchanged — marker management never touches a record, so every manifest hash and every Phase-4/5/7 comparison is unaffected** · **acceptance criterion 14 asserts the final marker state.**
⛔ **No new authority model:** a marker reports state and never confers or removes authority — §4.3's writer switch does that, and the marker is only its visible trace.

## `RD-3` — Phase-7 mismatch disposition → **ADDRESSED** *(§4.4 · §4 row 11 · §10 criterion 15)*

**Defect:** every Phase-7 mismatch routed to *"reconcile under §6 (CASE A / CASE B)"* — but §6.3 scopes the divergence window to Phases 3–5 and **CASE A appends the runtime tail as a legitimate extension.** ⇒ **a post-demotion write would be IMPORTED into authoritative evidence**, while §8 already rules that re-promoting a demoted source is a **governance act, not a merge**.

**Correction — the branch is SPLIT:**

| | **CASE α — pre-writer-switch** | **CASE β — post-writer-switch** |
|---|---|---|
| status of the bytes | ✅ **authoritatively produced evidence** | 🔴 **not authoritative — written against a demoted store** |
| disposition | ✅ **RECONCILE under §6** | ⛔ **QUARANTINE · record the conflict · ESCALATE.** ⛔ **Never imported** |

✅ **`R-CONFLICT` is UNCHANGED, and §4.4 states why quarantine is compliant:** the bytes are **preserved**, the conflict is **recorded**, provenance is intact, and **nothing is selected**. ⭐ **What quarantine refuses is the opposite error — silently PROMOTING bytes by merging them.** ⛔ **Post-demotion runtime bytes are NOT redefined as evidence.**
⭐ **Tie-break stated rather than implied:** the writer-switch instant is a recorded event with switch-over evidence; **if the ordering cannot be established from the records, the write is treated as CASE β** — the conservative direction, because β preserves without promoting.

## `RD-10` — migration-owned corpus writes → **ADDRESSED** *(§4.0 · §4 rows 1 & 9 · §10 criterion 11)*

**Defect:** Phase 0 declared *"no lane may append"*, **but the migration's own governance acts ARE corpus appends** — every transition and grant is written by `P-1` into the store being frozen and hashed. ⇒ **criterion 11's *"provably quiet"* branch was unreachable by construction, and a genuine violation became indistinguishable from the migration's own bookkeeping.**

**Correction — the freeze PARTITIONS writes instead of prohibiting all of them:**

| Class | Rule | Verification |
|---|---|---|
| ⛔ **unrelated corpus appends** | **prohibited** | 🔴 **freeze VIOLATION — detected, recorded, escalated** |
| ✅ **the migration lane's own records** — registration · acceptance · the Phase-0 declaration · reconciliation · switch-over · **Phase 6's demotion record** · cleanup | ⭐ **permitted and DECLARED IN ADVANCE** | ⭐ **EXPECTED DELTA INPUTS — reconciled separately** |

```
identical                                    →  ✅ the window was quiet
delta ⊆ the DECLARED migration-lane records  →  ✅ EXPECTED — reconcile separately, proceed
delta ⊄ the declared records                 →  🔴 FREEZE VIOLATION — stop, record, escalate
```

⭐ **The freeze keeps its evidential value because the test changed from *"did anything change?"* to *"did anything change that we did not declare?"*** ⛔ **"Freeze" is NOT redefined as "ignore all writes", and the ability to detect an unauthorized writer is NOT removed.** ⚠️ **Honest bound: the migration's own appends were always strict extensions handled safely by CASE A — what was lost was evidential clarity, not integrity.**

## `DI-1` — duplicate section identifiers → **ADDRESSED** *(§4 whole · map in §0.5.2)*

**Defect:** `## 4.1` and `## 4.2` each occurred **twice** with different content, with live cross-references bound to **both** senses — including inside `RC-4`'s own Phase-7 precondition — and §4's order was non-monotonic (`4.1 · 4.3 · 4.4 · 4.2 · 4.0 · 4.1 · 4.2`) while `RC-7b` had established order as authoritative for this artifact.

**Correction — §4 physically reordered and the AMD4 additions renumbered:**

| Was | Now | Content |
|---|---|---|
| `4.1` *(AMD4)* | **`4.4`** | the final integrity check (`RC-4`, `RD-3`) |
| `4.2` *(AMD4)* | **`4.3`** | the transfer instant (`RC-5`) |
| `4.3` *(AMD4)* | **`4.5`** | markers (`RC-6`, `RD-7`) |
| `4.4` *(AMD4)* | **`4.6`** | `-text` only (`RC-8`) |
| `4.0` | `4.0` | Phase 0 — the freeze (`RD-10`) |
| ✅ `4.1` *(original)* | ✅ **`4.1`** | Phase 3 — byte-preserving |
| ✅ `4.2` *(original)* | ✅ **`4.2`** | Phase 7 — removal ≠ deletion |

⭐ **The AMD4 additions moved and the originals did not, deliberately: renumbering the originals would have invalidated citations in two already-delivered independent reviews.** ✅ **Order is now `4.0 · 4.1 · 4.2 · 4.3 · 4.4 · 4.5 · 4.6`.** ✅ **All live `§4.x` references repointed and audited to resolve uniquely; the collision that had propagated into the AMD4 summary is corrected there too, with the correction stamped.** ⛔ **No governance history deleted.**
⚠️ **AMD5's own first draft reintroduced the defect** — inserting §0.5 left §0.4.4 after it. **Caught by the mandated check and repaired; §0 is monotonic.**

## `DI-2` — stale `Phase 2b` references → **ADDRESSED** *(§5 · §10 criteria 1 & 5 · §12)*

**Defect:** `RC-1` split the phase into `2b-pin` and `2c-commit`, but two **live** statements still said *"Phase 2b"* — §5's *"the manifest is committed at Phase 2b, in the same commit as the Phase-3 copy"* (**impossible**, since `2b-pin` runs before Phase 3) and **§10 criterion 1**, where *the acceptance object named a phase that no longer commits it*.

**Correction:** both now name **`2c-commit`**, each with the superseded wording **labelled**. §10 criterion 5's shorthand normalised to `2b-pin`; §12's fence updated. ✅ **§0.3's AMD3 lineage row retained as labelled history — correctly labelled, not a defect.** ⛔ **History not rewritten.**

## `DI-3` — dependency count → **ADDRESSED** *(§11.1)*

**Defect:** the heading asserted *"there are SIX"* over an **eight-row** table, and no grouping of the rows yielded six — **the same enumeration-versus-content mismatch `RC-7a` was raised for, one level down.**

**Correction — the count is reconciled TO THE ROWS, grouped by kind:** **2 governance acts · 4 PO/ARB acts · 1 Architecture remediation · 1 grouped open-questions row = 8**, of which ⭐ **3 gate execution** (registration · Phase-0 · Phase-4b), with acceptance gating the whole. ⛔ **No dependency invented to reach a number; no ownership moved.**

---

# 3 · Files and sections changed

| File | Sections |
|---|---|
| **`…-MIGRATION-PLAN.md`** *(AMD4 → AMD5)* | **§0.5** *(new — AMD5 record + `DI-1` map)* · §0.4.4 *(repositioned)* · header · **§4** *(reordered; rows 1, 3, 9, 11)* · **§4.0** *(`RD-10`)* · **§4.4** *(`RD-3`)* · **§4.5** *(`RD-7`)* · §5 *(`DI-2`)* · **§10** *(criteria 1, 5, 11; new 14, 15)* · **§11.1** *(`DI-3`)* · §12 · closing + traceability |
| **`…-AMD4-SUMMARY.md`** | §4.x references repointed, **correction stamped** — ⛔ no AMD4 disposition, finding or wording changed |
| **`…-AMD5-SUMMARY.md`** | this artifact |
| ⛔ **everything else** | **untouched** |

**Cross-cutting check executed after the corrections:** ✅ **no duplicate section identifier · monotonic within every top-level · phase-table rows 1…11 in execution order with no precondition pointing to a later phase · marker state never contradicts authority state · no post-demotion bytes importable · migration-owned writes identifiable · every acceptance criterion names a current phase · every `§4.x` reference resolves uniquely · no edit-transcript residue.**

---

# 4 · DDD / knowledge invariants preserved

**Execution state ≠ governance evidence** *(§4.0's partition names which writes are which)* · **evidence ≠ proof** *(§4.4's irreducible final interval is recorded, not implied away)* · ⭐ **evidence identity ≠ authority** *(`RD-3`: post-demotion bytes are preserved as evidence and refused as authority)* · ⭐ **authority ≠ storage location** *(`RD-7`: a marker reports state and never confers authority)* · **derived state ≠ authoritative state** · **historical location ≠ canonical future location**.

---

# 5 · What remains OPEN

| Item | Status |
|---|---|
| **`RD-7` · `RD-3` · `RD-10` · `DI-1` · `DI-2` · `DI-3`** | ⏳ **ADDRESSED · NOT CLOSED** — ⛔ **this process wrote these remedies and will not review them.** ✅ The findings themselves were `870305e0`'s, independent |
| **`OPEN-M6`** — AMD3 **and** AMD4 **and** AMD5 unregistered | ⏳ **OPEN.** Verified: **no `-AMD3`, `-AMD4` or `-AMD5` grant exists in the corpus** |
| **`OPEN-M5`** | ⏳ **OPEN — ⛔ not decided.** Does not gate the migration |
| **`OPEN-M1` · `OPEN-M2` · `OPEN-M4`** | ⏳ **not decided.** ⚠️ `RD-10` and `RC-1` constrain `OPEN-M2` further; neither resolves it |
| **`INFO-2`** lane-registration provenance | ⏳ open — a Governance act |
| **Phase-4b sub-case** | ⏳ whether a nonexistent **governed** directory is a location-resolution failure (⇒ 65) or a workflow-state condition (⇒ report, exit 0). **No test pins it; named, not decided** |

## Requires PO/ARB or Governance

**Governance:** registration of AMD3 + AMD4 + AMD5 · `INFO-2`. **PO/ARB:** ⭐ **the Phase-0 freeze declaration — which `RD-10` now requires to CARRY THE DECLARED EXPECTED-DELTA LIST** · the Phase-4b runtime-code authorization · `OPEN-M5` · plan acceptance.
⛔ **This process performs none of them, and does not register AMD5:** registration has exactly one writer and this process holds an Architecture role — *"a grant registers a recorded human act by reference; the record never manufactures authority"* (`G-2`/`R5b`).

---

# 6 · ⛔ Non-decisions and confirmation of non-execution

⛔ **AMD5 did NOT:** execute or begin any migration · move, copy, mark or remove any file · modify `.gitignore` · modify `.gitattributes` *(the `-text` pin is REQUIRED at `2b-pin` and NOT APPLIED)* · modify runtime code *(the Phase-4b resolver and the `RC-10` guard remain NAMED, NOT BUILT)* · modify any authority record · register any grant · decide `OPEN-M1`/`M2`/`M4`/`M5`/`M6` · reopen `B′`, `R-CONFLICT`, `OPEN-M3` or placement governance · change `R-CONFLICT`'s invariant text · alter `INV-ORDER` or `Option D` · reorder any phase · create an authority boundary, owner, ledger or bounded context · propose `Increment 2` · accept, approve or verify the plan · close the lane (`G-1`) · declare any `RD`/`DI` item closed.

> ## ⛔ **MIGRATION HAS NOT EXECUTED — verified after amending.**
> `.claude/runtime/` unchanged: **18 records · 216 transitions · 110 grants** · **0** `*.tmp*` remnants · **no durable target directory exists** · `.gitignore`, `.gitattributes` and `.claude/scripts/` **untouched** · **no grant registered.**

---

**AMD5 DELIVERED · STOPPING.**

**NEXT ACTOR = a fresh independent Architecture reviewer.** ⛔ **MUST NOT be `bc1b47ef` (AMD5's author), `1c8b041b`, `9c908e70`, or `870305e0` (the AMD4 reviewer).** ⚠️ **`5e1dd9ee` is disclosure-only, not automatically excluded.**
**Then:** Governance registers AMD3 + AMD4 + AMD5 (`OPEN-M6`) → PO/ARB acceptance, `OPEN-M5`, the Phase-0 declaration *(with its expected-delta list)* and the Phase-4b authorization → **execution beginning at PHASE 0, never at Phase 3.**

**Traceability:** the INDEPENDENT AMD4 review by `870305e0` *(`RC-1`…`RC-11` closed; `RD-7`, `RD-3`, `RD-10`, `DI-1`, `DI-2`, `DI-3`)* · AMD4 `0a2fa71d` · AMD3 `bb1708b7` · the AMD3 remedy review by `9c908e70` · technical review `a282d14b` · Governance review · implementation design `ae451db9` · original plan `3817eb2b` · `B′` · adopted `R-CONFLICT` · `OPEN-M3` Option A · `AMD1`/`AMD2` · the PO/ARB commission of 2026-08-20 *(unregistered)* · `ADR_20260801_1740` + `scripts/doc-placement.php` **exit 0** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a`/`R5b` · `R-34`/`P-2` · `G-1`.
