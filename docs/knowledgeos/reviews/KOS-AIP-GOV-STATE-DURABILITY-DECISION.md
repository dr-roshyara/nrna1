# `KOS-AIP-GOV-STATE-DURABILITY` — **Decision**

**Work item:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · **Registered by:** Governance (`b64828fe`) on the PO/ARB **DECISION ACT**, 2026-08-19
**Decision object:** `KOS-AIP-GOV-STATE-DURABILITY-ADR.md` @ **`67a8e75e`** · **Grant:** `G-KOS-GOV-STATE-DURABILITY-DECISION`
⛔ **Governance records this decision; it did not make it.** Both selections were supplied by the human PO/ARB. *(Placement per Decision 3 — this file sits with the estate's other adoption decisions under `docs/knowledgeos/reviews/`.)*

---

## 1 · Decision summary

> # ✅ **STATUS: DECIDED**

| | Selection |
|---|---|
| **D1 · durability model** | ✅ **`B′` — Governance Evidence Relocation** |
| **D2 · `R-CONFLICT`** | ✅ **`ADOPT`** |
| **D3 · placement governance** | ✅ **Existing governance applies** |

## 2 · Selected options

### D1 — `B′` Governance Evidence Relocation

> **Governance evidence becomes a first-class durable artifact. Runtime remains execution-only. Authority records do not belong to runtime storage.**
> ⭐ **The authority record is NOT exported from runtime — it belongs to the governance evidence boundary.**

### D2 — `ADOPT` the `R-CONFLICT` invariant

> **A governance evidence conflict MUST NOT be resolved by: silently selecting one side · deleting historical evidence · rewriting sequence history.**
> **Resolution MUST preserve: provenance · sequence integrity · reconstruction capability.**

### D3 — Existing placement governance applies

> **ADR placement continues under existing placement governance. No new placement rule created. `ADR:OQ-2` remains open under its existing owner.**

## 3 · Rationale — as delivered

| D1 | separates execution context from governance evidence context · aligns with **Execution mechanism ≠ Governance evidence boundary** · **prevents merge conflicts from becoming governance conflicts** · addresses the observed failure — **documents survived, authority lineage did not** |
|---|---|
| **D2** | already demonstrated by existing practice · **formalizes existing behaviour** · prevents silent history rewriting |
| **D3** | a new rule would **duplicate existing governance responsibility** · `OQ-2` already has an existing owner |

**Governance corroboration, run rather than assumed** *(evidence, not authority)*:

- **D1's observed failure is dated and reproducible:** commit **`de998173`** committed the narrative lineage — sixteen review documents — while **all 28 grants and 33 transitions stayed outside git**, `.claude/runtime/` being ignored at `.gitignore:25` and `:32`.
- **D2's four precedents**, all on `KOS-AIP04-DISCOVERY-001`: `S5`'s `HANDED_OFF` **clarified, never "fixed"** · `CORRECTION 1` and its `ERRATUM` **both retained** · **Act A** superseded with the record retained historical · **`ca6039a8`** findings intact, only assurance refused.
- **D3:** `ADR_20260801_1740` governs placement · `documentation-placement.yaml` carries the cross-product rules and **already references `ADR:OQ-2` at line 31** · `scripts/doc-placement.php --scope=cross-product --maturity=adopted` → `engineering`, **exit 0** — the **exit-2 unruled signal did not fire**.

## 4 · Rejected alternatives

| | Why rejected |
|---|---|
| **A · track runtime directly** | **couples execution location to authority location**; merge conflicts become governance conflicts |
| **B · export governed snapshots** | **runtime remains the origin of authority**; durability is a **derived copy, not first-class** |
| **C · runtime-only** | ⭐ **the observed failure mode in this estate** — authority lineage cannot be reconstructed. *The ADR's own principle: **"An aggregate cannot depend on accidental reconstruction."*** |

## 5 · Consequences

> ### ⭐ **D2 constrains how D1 is implemented — this is the load-bearing consequence.**
> **The relocation is itself an operation on governance evidence, so `R-CONFLICT` binds it:** the migration **must preserve sequence integrity and provenance**, **must not delete or re-create history**, and **if a relocated record and a runtime record ever diverge, `R-CONFLICT` governs** — neither side may be silently chosen.

**Facts recorded, each requiring separate implementation authorization:**

| | |
|---|---|
| **the engine's default contradicts `B′`** | `workflow-state.php:81` defaults `--dir` to `.claude/runtime/workflow` ⇒ **the default path currently implies runtime storage** |
| **`.gitignore` is correct for runtime and must stay** | `.claude/runtime/` at lines 25 / 32 is **right under `B′`** — runtime *should* be ephemeral. **It must not be relaxed for runtime**; the relocated governance evidence must be durable **elsewhere** |
| **relocation, not duplication** | *"not exported from runtime"* ⇒ **after relocation, runtime does not hold the authority record** |
| **migration subject** | **28 grants + 33 transitions** on `KOS-AIP04-DISCOVERY-001`, and **2 grants + 3 transitions** on this work item |

⚠️ **Until relocation happens, the estate remains in the `C` condition the decision rejects** — the authority record is real, in use, and outside durable history. **The decision changes the target, not yet the state.**

## 6 · Follow-up actions

1. ✅ **Implementation planning may now begin** — the act's rule *"implementation planning occurs only after adoption"* is satisfied for D1 and D2. ⛔ **No implementation is authorized by this decision.**
2. **Define where the governance evidence boundary lives** — ⛔ **explicitly a non-decision here** (§7).
3. **Reconcile `workflow-state.php`'s default `--dir`** with `B′`.
4. **Migrate the existing authority state under `R-CONFLICT` constraints** — preserve sequence, delete nothing, rewrite nothing.
5. **`ADR:OQ-2` remains open under its existing owner** — not reassigned, not closed.

## 7 · Explicit non-decisions

⛔ **C-10 existence · `D2` capability establishment · `D6` category · `D7` ownership · Correction #3 verification · implementation technology · repository layout details.**

⭐ **"Repository layout details" is explicitly reserved:** `B′` decides that the authority record **belongs to the governance evidence boundary**, ⛔ **not where that boundary physically sits.** Choosing a path is a separate act.

**Traceability:** PO/ARB DECISION ACT 2026-08-19 *(both acts; the second names D3 explicitly and reserves repository layout)* · ADR `67a8e75e` · position registration `2026-08-19-KOS-AIP-GOV-STATE-DURABILITY-po-arb-position-registration.md` §1–§3 and its appended DECISION-ACT registration · `G-KOS-GOV-STATE-DURABILITY-DECISION` · `G-KOS-GOV-STATE-DURABILITY` · seq 1–3 · commit `de998173` · `.gitignore:25`/`:32` · `workflow-state.php:81` · `ADR_20260801_1740` + `documentation-placement.yaml:31` + `scripts/doc-placement.php` · `ES-005.4` · `ES-006.1` · `G-2`/`R5b`
