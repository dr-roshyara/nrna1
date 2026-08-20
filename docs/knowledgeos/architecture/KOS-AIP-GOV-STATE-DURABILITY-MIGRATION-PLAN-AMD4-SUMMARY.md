# `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN` — **AMD4 amendment summary**

**Status: 🟡 PROPOSED.** ⛔ **MIGRATION HAS NOT EXECUTED. Nothing was moved, copied, pinned, switched, demoted or removed.**
**Amended artifact:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` *(AMD3 at `bb1708b7` → AMD4)*
**Input, consumed and not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD3-ARCHITECTURE-REVIEW.md` — **INDEPENDENT** AMD3 remedy review by `claude-code-session:9c908e70`, verdict **`PASS WITH DESIGN CLARIFICATIONS`**.
**Producing process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:bc1b47ef` — **author of AMD3 and of the technical review `a282d14b`.** **`R-34`/`P-2`: this process must not verify or accept the plan or this amendment.**
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**.

---

> ⚠️ **AMD5 (`DI-1`) correction, 2026-08-20:** §4 subsection references below were repointed after the plan's §4 was renumbered monotonically — **`4.1`→`4.4` · `4.2`→`4.3` · `4.3`→`4.5` · `4.4`→`4.6`** (map: plan §0.5.2). ⛔ **No disposition, finding or wording of AMD4 was changed.**

# 1 · Where AMD4 stands, in one table

| | |
|---|---|
| **Input verdict** | 🟡 `PASS WITH DESIGN CLARIFICATIONS` — **10 `CL` CLOSED · 2 ADDRESSED-NOT-CLOSED (`CL-5`, `CL-6`) · 0 unclosed** |
| **Residuals consumed** | **`RC-1`…`RC-11`**, of which **`RC-1`/`RC-2`/`RC-3`/`RC-4` are gate-class** |
| **AMD4 disposition** | ⭐ **all 11 `RC` items + `CL-5` + `CL-6` ADDRESSED** |
| ⛔ **AMD4 self-closure** | **NONE. Not one item is declared closed by this process** (§4) |
| **Decisions changed** | ⛔ **none** |
| **Gate state** | ⛔ **Phase 3 must not begin** |

---

# 2 · `RC-1` … `RC-11` disposition

| # | Class | Gate | What AMD4 changed | Where |
|---|---|---|---|---|
| ⭐ **`RC-1`** | gate | **Phase 3** | **The circular phase is SPLIT.** `2b-pin` (pin `.gitattributes`) executes **before** the copy; `2c-commit` (commit manifest **with** the copy) executes **at/after** it. **Phase 3's precondition is now `2b-pin`, which no longer depends on Phase 3's own output** | §4 table rows 4–6 |
| ⭐ **`RC-2`** | gate | **Phase 4b** | **`INV-R1`'s boundary check now binds the AUTHORITATIVE resolution only.** A new row states that a **governed override yields a NON-AUTHORITATIVE output location and is NOT a boundary violation** — with both contract suites' `--dir=sys_get_temp_dir()` usage quoted as the evidence | §3 table rows 1–2 |
| ⭐ **`RC-3`** | gate | **Phase 4b** | **The two mechanism-selection forms are separated and labelled:** reporting = ✅ *"THIS IS WHAT PHASE 4b IMPLEMENTS"*; enforcing = ⛔ *"DEFERRED TO `OPEN-M5`, NOT CURRENT"*. **The one conflicting current definition in the artifact is gone** | §3 table rows 3–4 |
| 🔴 ⭐ **`RC-4`** | gate | **Phase 7** | **A FINAL re-hash is now Phase 7's precondition:** runtime source vs **`manifest + reconciled delta`**; any difference ⇒ **stop, reconcile under §6, re-verify, only then remove.** New **acceptance criterion 12** and a fourth §8 rollback trigger | §4.4 · §4 row 11 · §10 · §8 |
| **`RC-5`** | design | — | **Phase 5's internal order is MANDATED — re-hash · readers (`P-2`, `P-4`) · WRITER (`P-1`) LAST — and the authority-transfer instant is the writer switch.** §8's boundary restated from *"Phase 5"* to *"the writer switch"* | §4.3 · §8 |
| **`RC-5b`** | design | — | **Phase 4b delivers the resolver DORMANT — built, tested, NOT WIRED. Phase 5 is the sole activating act**, so 4b cannot collapse into it | §3 `INV-R5` · §4 row 8 |
| **`RC-6`** | design | — | **Marker mechanism specified:** directory-level · out-of-band · ⛔ **in-file marking FORBIDDEN by byte preservation** · ⛔ **never `*.json`** · **advisory reach stated** | §4.5 |
| **`RC-7a`** | coherence | — | The `INV-ORDER` preservation argument now enumerates **all three** additions — including `2b`/`2c`, the ones landing **inside** the 2–4 span | §4 |
| **`RC-7b`** | coherence | — | **The phase table is sorted by EXECUTION and numbered `1…11`.** Labels are lineage; **row order is authoritative** | §4 |
| **`RC-7`** *(deps)* | coherence | — | **Six prerequisites enumerated with actors**, including the **Phase-0 freeze** and **Phase-4b authorization** that AMD3 introduced and did not carry | §11.1 |
| **`RC-8`** | design | — | **`text eol=lf` WITHDRAWN; `-text` only** — it keeps normalisation on and would silently rewrite a record containing a raw `CR`. **Hash semantics recorded: SHA-256 over working-tree bytes** | §4.6 · §4 row 2 |
| **`RC-9`** | wording | — | **"Canonical re-encode" DEFINED as recursive key ordering**, and **comparison-only** | §6.3 |
| **`RC-10`** | design brief | — | **Refusal MUST precede `saveRecord`'s `mkdir`** — a mis-resolved path otherwise *materializes* a new evidence location, re-creating `B′`'s defect in the component built to close it | §3.1 |
| **`RC-11`** | wording | — | **"One writer of GOVERNANCE EVIDENCE"**, with the discriminating test (*references `runtime/workflow` **and** writes*) and all three `file_put_contents` candidates disposed. ⛔ **No claim that arbitrary directory writes are impossible.** **Closes `INFO-1`** | §1.3 |
| **`CL-5`** | reopened | — | closed via `RC-6` | §4.5 |
| **`CL-6`** | reopened | — | closed via `RC-1` + `RC-8` | §4 · §4.6 |

---

# 3 · The one finding worth restating on its own

> ## 🔴 ⭐ **`RC-4` is the only residual that guarded an IRREVERSIBLE act, and the reason it slipped through is instructive.**
> ```
> Phase 5   re-hash vs manifest      ← the last integrity check of the runtime copy under AMD3
> Phase 5   switch readers, writer   ← not atomic with the re-hash
> Phase 6   record the transfer
> Phase 7   REMOVE                   ← AMD3 precondition: "Phases 4 AND 5 passed" — no re-check
> ```
> **A write landing after the Phase-5 re-hash but before the writer switch goes to the RUNTIME copy — it is in neither the frozen manifest nor either reconciliation input — and Phase 7 destroys it.**
> ⭐ **AMD3's own bounded claim cannot catch it:** *"no record lost **relative to** the frozen manifest and the observed inputs"* is **self-satisfying** against a write that is in neither. **The withdrawn absolute claim would have caught this.**
> ⛔ **That is not an argument for restoring the absolute claim** — §1.3's evidence stands and §6.3's withdrawal stands. **It is an argument for a final check, which is what AMD4 adds.**

---

# 4 · ⛔ What remains OPEN — and why AMD4 closes nothing itself

## 4.1 The provenance bound, restated because it is the reason for this section

| | Then (AMD3) | Now (AMD4) |
|---|---|---|
| who found the problems | ⛔ `bc1b47ef` — the process that also wrote the remedies | ✅ **`9c908e70` — independent** |
| had the remedies been reviewed? | ⛔ no | ✅ **yes — 10 of 12 closed** |
| who writes **this** amendment's remedies? | — | ⛔ **`bc1b47ef` again** |

> ⭐ **The exposure changed shape rather than disappearing.** ✅ **AMD4 closes SOMEONE ELSE'S findings — materially stronger than AMD3.** ⛔ **But its own remedies are again unreviewed by anyone.** ⇒ **`RC-1`…`RC-11` are `ADDRESSED`, never `CLOSED`. The bound recurs until an amendment produces no new remedy.**

## 4.2 Open items

| Item | Status |
|---|---|
| **`RC-1`…`RC-11`** | ⏳ **ADDRESSED · NOT CLOSED** — a fresh independent review decides |
| **`OPEN-M6`** — AMD3 **and** AMD4 unregistered | ⏳ **OPEN.** Verified: **no `-AMD3` or `-AMD4` grant exists in the corpus** |
| **`OPEN-M5`** — enforcing form / `AST-016` contract amendment | ⏳ **OPEN — ⛔ not decided.** ✅ Does not gate the migration: the reporting form changes no adopted contract |
| **`OPEN-M1`** `.gitignore` · **`OPEN-M2`** evidence-before-target · **`OPEN-M4`** unbounded readers | ⏳ unchanged. **`RC-1`'s split constrains `OPEN-M2` further without deciding it** |
| **`INFO-2`** lane-registration provenance | ⏳ open — a Governance act |
| **Phase-4b sub-case** | ⏳ whether a **nonexistent GOVERNED directory** is a location-resolution failure (⇒ 65) or a workflow-state condition (⇒ report, exit 0). **No test pins it; AMD4 names it and does not decide it** |

---

# 5 · What requires PO/ARB or Governance

| Prerequisite | Actor | Gates |
|---|---|---|
| **AMD3 + AMD4 registration** | **Governance** — one writer, `G-2`/`R5a` | acceptance |
| ⭐ **Phase 0 freeze declaration** | **PO/ARB** | **Phase 1** |
| ⭐ **Phase 4b runtime-code authorization** | **PO/ARB** | **Phase 5** |
| **`OPEN-M5`** | **PO/ARB** | ⛔ nothing |
| **Plan acceptance** | **PO/ARB** | execution |

⛔ **This process performs none of them.** **Registration has exactly one writer and this process holds an Architecture role — registering its own authorization would be the manufacture `G-2`/`R5b` forbids** (*"a grant registers a recorded human act by reference; the record never manufactures authority"*).

---

# 6 · Explicit non-decisions

⛔ **AMD4 did NOT:** execute or begin any migration · move, copy or remove any file · modify `.gitignore` · modify `.gitattributes` *(the `-text` pin is REQUIRED at `2b-pin` and NOT APPLIED)* · modify runtime code *(the Phase-4b resolver and the `RC-10` refusal guard are NAMED, NOT BUILT)* · modify any authority record · register any grant · decide `OPEN-M5` · reopen `B′`, `R-CONFLICT`, `OPEN-M3` or placement governance · alter `INV-ORDER` or `Option D` · propose `Increment 2` · create a ledger, bounded context, capability, owner or rule · accept, approve or verify the plan · close the lane (`G-1`) · declare any `RC` closed.

> ## ⛔ **MIGRATION HAS NOT EXECUTED.**
> **Verified after amending:** `.claude/runtime/` unchanged — **18 records · 216 transitions · 110 grants**, **no `*.tmp*` remnant** · `.claude/scripts/` clean · `.gitignore` and `.gitattributes` untouched · **no durable target directory exists** · **no grant registered.**

---

**AMD4 DELIVERED · STOPPING.**
**Next: fresh independent technical review → Governance registers AMD3 + AMD4 (`OPEN-M6`) → PO/ARB acceptance, `OPEN-M5`, the Phase-0 declaration and the Phase-4b authorization → execution beginning at PHASE 0.**

**Traceability:** the INDEPENDENT AMD3 remedy review by `9c908e70` *(`RC-1`…`RC-11`; §3.4, §5.1, §6, §7.3, §8, §12, §13)* · AMD3 `bb1708b7` · technical review `a282d14b` *(`CL-1`…`CL-12`)* · Governance review *(`INFO-1` closed by `RC-11`; `INFO-2` open)* · implementation design `ae451db9` · original plan `3817eb2b` · `B′` · adopted `R-CONFLICT` · `OPEN-M3` Option A · `AMD1`/`AMD2` · the PO/ARB commission of 2026-08-20 *(unregistered)* · `ADR_20260801_1740` + `scripts/doc-placement.php` **exit 0** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a`/`R5b` · `R-34`/`P-2` · `G-1`.
