# `KOS-AIP-GOV-STATE-DURABILITY` — DV-correction chain — **Architecture `RV-1…RV-7` Repair Disposition**

**Work item:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · **Act:** Architecture repair of `RV-1…RV-7`, per the PO/ARB-authorized commission registered at `8b92a100`, 2026-08-22
**Registered by:** this session, in the Architecture repair role — **self-declared, not attestable** (`INV-ATTR-1`/`INV-ATTR-2`)
**Grant:** `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-RV-REPAIR` (AUTHORIZED) · **Prompt contract:** *"Repair `RV-1…RV-7` only, produce evidence, stop"* — ⛔ **NOT** *"continue migration"*
**Findings source:** the independent DV review by `a8ce5a39` · the bounded review @ `ff50a2cf` (verdict 🟡 CHAIN NOT READY; `RV-1` the gateway)

> ## ⛔ What this record is / is not
> This is the **Architecture repair disposition** — a per-finding record of what was changed, where, and the diff that evidences it. ⛔ **It is not a verification, not an acceptance, and it closes nothing.** ⛔ `DV-1…DV-7` and `RV-1…RV-7` remain **OPEN**; closure is the PO/ARB's acceptance act (`R-34`/`P-2`). ⛔ **Migration is NOT authorized. PHASE 3 MUST NOT BEGIN. PHASE 5 REMAINS PROHIBITED.** The producing process does not review its own repair.

---

## 0 · Method

1. **Re-derived every finding from the independent review's per-finding table** (§8 for `RV-1`; §9 rows for `RV-2…RV-7`) — quotes below are verbatim.
2. **Located each finding's physical site** in the plan (`docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md`) and — for `RV-5`/`RV-6`, whose findings physically live there — the DV-correction summary (`docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-SUMMARY.md`).
3. **Applied the minimal surgical repair per finding**, append-only per §0.4.4 — superseded wording is SHOWN and labelled; nothing was silently deleted.
4. **Re-verified the measured facts** the repairs cite: `.claude/scripts/` = **13 entries** (10 shell scripts + `README.md` + `session-resolve.php` + `workflow-state.php`); the summary's test table lists **18** tests (`T1`…`T18`), all ✅.
5. **No finding closed, nothing accepted** — this record is evidence for the bounded re-verification and the PO/ARB decision, not a verdict.

---

## 1 · `RV-1` (gateway) — **Option A** (placement-table row), stated on the record

### Finding (verbatim, independent review §8)

> **"step `3(iii)`'s mechanical write is required to be evidenced by a governance append, and no section places that append."** … *"§4.3's placement table has **no row** for it · §4.7 `P5·3` describes `(iii)` as an act and produces no record · §5's evidence list does not name it."* … *"does the table claim completeness? — 🔴 yes — its last row is captioned 'unchanged by AMD6; recorded here so the set is complete.'"* … *"does the receipt discharge it? — ⛔ no, on the correction's own words. … It attests the COMPARISON, not the WRITE. `PREMISE 2` requires an append that **describes the write**."* … *"is the repair already derived in this artifact? — ✅ yes — by the author, for the receipt, in the same section. The identical five-line derivation applies unchanged, and it terminates the same way: **post-switch, carried by the `SWITCH-OVER RECORD` at `3(v)`, alongside the enumeration and the receipt**."* ⇒ **"add ONE row to §4.3's placement table for step `(iii)`'s evidencing append, with its subject-state and destination — and, if that destination is `3(v)`'s carrier, say so where `PREMISE 2` is stated."**

### Option chosen: **A** — add the row. ⛔ Not the exemption.

### Justification (why Option A, not B)

1. **`PREMISE 2` is now the load-bearing premise of the `C-4` termination argument** (`DV-4` moved it inside the argument). It names **three** mechanical writes — the Phase-3 copy, the `1b` reconciliation, and step `(iii)`. The first two are evidenced with a placed destination; leaving step `(iii)` un-evidenced would make `PREMISE 2` apply to only two of the three writes it names. An unstated exemption would weaken the derivation `DV-4` was raised to complete — exactly what the review flags.
2. **The `3(iii-b)` receipt does NOT discharge it** — on the correction's own words, the receipt's claim scope is the comparison performed and its `PASS`, explicitly not *"the durable copy is correct."* `PREMISE 2` requires an append that **describes the write**. The receipt and the step-(iii) evidencing append are distinct evidence.
3. **The destination is forced, not chosen** — it is the identical five-line derivation the author already used for the receipt (§0.7.5), and the review confirms it applies unchanged:
   - the staging store **cannot** receive an authoritative record before `3(iv)` (§3 row 2 — a write there would be an override write);
   - the demoted source **cannot** receive one after `3(iv)` (§4.4 post-demotion quarantine class);
   - a **pre-switch** source append would land **after the enumeration closed at `3(ii)`**, re-opening the closed delta — the `DV-2` defect class, `C-4` recursion — and would make Phase 7's final re-hash report a delta in the **normal** case, suspending Phase 7 (`P7·3`) while the quarantine stands undisposed (`RD-10`'s degradation, relocated a third time).
   - ⇒ the evidencing append is **POST-SWITCH AUTHORITATIVE**, **carried by the `SWITCH-OVER RECORD` at `3(v)`**, alongside the enumeration and the receipt.

### Repair (evidence)

- **plan §4.3 placement table — ONE new row at line 796** (between the `3(iii-b)` receipt row and the slot-4 row):
  `| ⭐ **DV REPAIR (`RV-1`): slot `3(iii)`'s MIGRATION MECHANICAL WRITE — its EVIDENCING GOVERNANCE APPEND** | ⭐ **POST-SWITCH AUTHORITATIVE** | ⭐ **the AUTHORITATIVE store — CARRIED BY the `SWITCH-OVER RECORD` at `3(v)`** | ⭐ **derived, not chosen — `PREMISE 2` … the identical five-line derivation as the receipt (§0.7.5).** ⛔ **The `3(iii-b)` receipt does NOT discharge it — the receipt attests the COMPARISON, not the WRITE.** ⛔ **Placed PRE-SWITCH it would land after the enumeration closed at `3(ii)` and re-open the closed delta — the `DV-2` defect class, `C-4` recursion** |`
  The row carries the three things the commission asked the row to place: **location** (the AUTHORITATIVE store, carried by the SWITCH-OVER RECORD at `3(v)`) · **evidence artifact** (the evidencing governance append) · **provenance reference** (`PREMISE 2`, §0.7.5).
- **plan §4.3 PREMISE 2 — note added at lines 811–814** where the premise is stated: step `(iii)` is a THIRD mechanical write under the premise; its evidencing append is CARRIED POST-SWITCH by the `SWITCH-OVER RECORD` at `3(v)`, alongside the enumeration and the receipt.
- **summary §4 PREMISE 2 — propagation note added at line 175** (the summary compresses the same premise; leaving it silent would create a second-artifact inconsistency).

---

## 2 · `RV-2` — plan §4.4 overclaim

### Finding (verbatim)

> **"The exact §4.2 claim `DV-1` was raised against survives as CURRENT, UNLABELLED text in the same section that corrects it."** … *"§4.4's closing paragraph still reads '…becomes true at the moment of removal rather than true as of Phase 4', while §4.4's own `DV-1` block states it was 'true of **neither** Phase 4 **nor** the moment of removal' … ⇒ **two current, contradictory statements of one claim, ~100 lines apart, inside the section a Phase-7 operator consults.**"*

### Disposition

Scoped the closing paragraph's claim to match the `DV-1` block. The unqualified wording is **labelled superseded** and the current wording now carries the `DV-1` scope: *"becomes true of the reconciled `1b` delta and the pre-switch Phase-5 records, at the moment of removal, **only because `3(iii-b)` verifies them** — and was true of **neither** Phase 4 **nor** the moment of removal, for exactly those records, without that verification."*

### Evidence

- **plan §4.4, line 1043** — appended the `RV-2` repair clause after the existing sentence. The superseded wording is shown and labelled (`⛔ **superseded wording (unqualified):** … ⛔ **current wording:** …`). The two statements are no longer in contradiction.

---

## 3 · `RV-3` — plan §4.0 unlabelled superseded switch-over record

### Finding (verbatim)

> **"§4.0's migration-lane class list still names 'the switch-over record' among the records expected in the SOURCE delta, and the entry is NOT labelled superseded in place."** … *"⛔ **§0.4.4 requires the label where the wording stands** — which is exactly how all five `DV-3` sites were treated."*

### Disposition

Labelled the entry **superseded in place** within the list, per §0.4.4 — the same treatment the five `DV-3` sites received. The normative sentence *"the Phase-0 declaration MUST enumerate…"* now points at a list whose switch-over-record entry is withdrawn on the record.

### Evidence

- **plan §4.0, line 655** — the migration-lane cell now reads: `… the reconciliation records · ⛔ **superseded wording:** *"the switch-over record"* — ⛔ **WITHDRAWN from the source delta by the `DV-2` block below; the switch-over record lands in the AUTHORITATIVE store as the FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER (§4.3)** · **Phase 6's demotion record** · the cleanup record`. The `DV-2` block below already withdrew it; the label now stands where the wording stands.

---

## 4 · `RV-4` — plan §5 evidence enumeration omits the `3(iii-b)` receipt

### Finding (verbatim)

> **"§5's current enumeration of migration evidence — 'the Phase-1 inventory · the Phase-4 hash comparison · reconciliation records · the switch-over record · the demotion record · the cleanup record' — omits the `3(iii-b)` VERIFICATION RECEIPT this correction creates."** … *"§5 is the section §0.7.5's derivation **cites** as governing migration-evidence placement."*

### Disposition

Added the `3(iii-b)` VERIFICATION RECEIPT to §5's evidence enumeration, carried by the switch-over record (matching the §4.3 placement-table row). No existing item was removed.

### Evidence

- **plan §5, line 1129** — the enumeration now reads: `… the switch-over record — ⭐ **carrying the `SOURCE FINAL-STATE ENUMERATION` AND the `3(iii-b)` VERIFICATION RECEIPT** *(`RV-4` repair …)* · the demotion record · the cleanup record.`

---

## 5 · `RV-5` — summary false measurement (`.claude/scripts/`)

### Finding (verbatim)

> **"An overclaim in the `DV-3` verification-logic evidence — re-measured, not assumed. The summary states '`.claude/scripts/` contains exactly `session-resolve.php` and `workflow-state.php`.' 🔴 It contains 13 entries — 11 shell scripts plus a `README.md` and those two PHP files. … ⭐ the finding is that the stated measurement is false while its conclusion is true — the estate's own final rule, inverted."**

### Disposition

Corrected the measurement in both places it appears. The stated count was false; the conclusion (no positional discriminator) is re-affirmed and still holds. The repair states the true measurement and marks the old wording superseded.

### Evidence (measured, not assumed)

- `.claude/scripts/` contains **13 entries**: **10** shell scripts (`claude-code-trigger.sh` · `db-safety-check.sh` · `ddd-principles-reminder.sh` · `dev-guide-reminder.sh` · `discipline-gate-reminder.sh` · `engineering-placement-guard.sh` · `inject-context.sh` · `project-knowledge-guard.sh` · `session-changes-logger.sh` · `session-log-reminder.sh`) + `README.md` + `session-resolve.php` + `workflow-state.php`. ⚠️ The review's sub-breakdown said "11 shell scripts"; the re-measured count is **10** shell scripts (the total of 13 entries is correct).
- **summary §3, line 153** — the VERIFICATION LOGIC cell now states 13 entries, labels the old "contains exactly …" wording **superseded wording / false measurement**, and keeps the (still-true) conclusion: none of the shell scripts references the workflow record store, and neither PHP file locates any discriminator positionally.
- **summary Traceability, line 405** — `.claude/scripts/` = **13 entries** (10 shell scripts + `README.md` + the two PHP files), **no positional discriminator in any of them** *(`RV-5` repair …)*.

---

## 6 · `RV-6` — summary irreconcilable counts

### Finding (verbatim)

> **"Three mutually irreconcilable CURRENT counts for one measurement, in one artifact:** §11 says *'`26` of `35` properties FAILING' → '`43` of `43` PASSING'*; the Traceability line says *'**37/37** properties re-measured GREEN'*; the table itself lists **18** tests (`T1`…`T18`). ⛔ **Nothing reconciles tests to properties.** ⭐ **It sits inside the section that argues a self-check must not assert more than it measured.**"

### Disposition

Withdrew the irreconcilable property-level counts (`35`, `43`, `37`) and re-anchored the evidence on the one count the artifact actually measures and tabulates: **18 property tests (`T1`…`T18`), all PASSING after the correction**. Both repair sites now state the same reconcilable figure. The withdrawn counts are shown, labelled superseded — nothing was silently deleted.

### Evidence

- **summary §11, line 330** — method sentence now opens with the `RV-6` repair, labels the `26/35 → 43/43` and Traceability's `37/37` as **superseded wording / irreconcilable**, and asserts only the 18-table-row evidence.
- **summary Traceability, line 405** — **18 property tests (`T1`…`T18`) ALL PASSING after the correction** *(`RV-6` repair — the earlier `37/37` … is withdrawn as irreconcilable with §11)*.

---

## 7 · `RV-7` — plan §4.7 `P5·3` scoping ambiguity

### Finding (verbatim)

> **"Operator-facing scoping ambiguity.** §4.7 `P5·3`'s STOP-if cell places, in one cell, *'(iii-b) FAILS — STOP · RECORD · ESCALATE'* **and** *'⛔ Never re-construct or re-close the enumeration at (iii) or (iii-b).'* ✅ **Correct scoped to the slot-3 run.** ⚠️ **An operator resuming after remediation could read it as barring a fresh enumeration on a re-attempt** … **One scoping clause removes it.**"

### Disposition

Added the scoping clause: the *never re-construct / never re-close* prohibition is scoped **WITHIN THIS SLOT-3 RUN**, and a `3(iii-b)` FAIL is a **STOP, not a completion** — a later re-attempt **RE-MEASURES at (i)** and **RE-CLOSES a FRESH enumeration at (ii)**; the closed object belongs to the aborted run only. This matches the plan's existing treatment of a `3(iii-b)` FAIL as a pre-switch stop (§8), which implies a later re-measure.

### Evidence

- **plan §4.7, line 1111** (`P5·3` STOP-if cell) — the cell now reads: `⛔ **Never re-construct or re-close the enumeration at (iii) or (iii-b) WITHIN THIS SLOT-3 RUN — it is CONSUMED, not rebuilt; ⛔ never create a second comparison object.** ⚠️ **`RV-7` repair — scoping clause: a `3(iii-b)` FAIL is a STOP, not a completion — a LATER re-attempt RE-MEASURES at (i) and RE-CLOSES a FRESH enumeration at (ii); the closed object belongs to the aborted run only.**`

---

## 8 · Artifacts touched (this repair only)

| Artifact | Change | Why here |
|---|---|---|
| `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` | `RV-1` (row + PREMISE 2 note) · `RV-2` · `RV-3` · `RV-4` · `RV-7` | the plan is where those findings physically live |
| `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-SUMMARY.md` | `RV-5` · `RV-6` (+ `RV-1` propagation note) | ⚠️ **scope note:** `RV-5` and `RV-6` are findings whose physical sites are in the **summary**, not the plan — the independent review's per-finding table lists *summary §3 · §10* and *summary §11 + Traceability*. Repairing them is the `RV` repair the commission authorized, not an artifact beyond it. The summary was the DV correction's own deliverable and is the artifact those findings cite |

⛔ **Not modified:** the independent review, the bounded review, the commission registration, `DECISION.md`, any workflow/runtime file, any `.gitignore`/`.gitattributes`, any script.

---

## 9 · What this does NOT do

- ⛔ **No migration.** **PHASE 3 MUST NOT BEGIN · PHASE 5 REMAINS PROHIBITED.** The migration stays frozen until PO/ARB acceptance.
- ⛔ **No finding closed, nothing accepted.** `DV-1…DV-7` and `RV-1…RV-7` remain **OPEN**. Closure is the PO/ARB's acceptance act (`R-34`/`P-2`). This repair produces evidence; it does not self-certify.
- ⛔ **No design change, no failure-direction change, no reordering, no new actor, no new mechanism.** Each repair is wording / measurement / current-vs-superseded document consistency, exactly as the commission's §2 table authorizes.
- ⛔ **No new commission, no grant, no workflow transition** — no `.claude/runtime/` file and no `.claude/scripts/workflow-state.php` was touched.
- ⛔ **No second comparison object was created** — the `SOURCE FINAL-STATE ENUMERATION` remains closed at `3(ii)`, immutable, never re-constructed (the `RV-7` repair reaffirms this for the aborted run and permits a fresh enumeration only on a re-attempt's re-measure).
- ⛔ **No artifact beyond the `RV` repair and its evidence.** The append-only discipline (§0.4.4) was preserved: superseded wording is SHOWN and labelled, never silently deleted.
- ⛔ **The producing process does not review its own repair.** A fresh independent technical verification remains the route for design-soundness; the next governed step is the **Governance bounded re-verification**.

---

## 10 · Next actor (advisory — assigns nothing)

```
ARCHITECTURE             this act — ✅ RV-1…RV-7 repair COMMITTED · evidence above · no finding closed
        ↓
GOVERNANCE BOUNDED       ⏳ re-verification pass once this repair is committed (per the commission's
REVIEW                   authorized sequence and the bounded review's §7 routing)
        ↓
PO/ARB                   ⏳ decision and acceptance — the DV-1 Phase-5 bar is theirs to discharge
```

---

**Traceability:** the work item `KOS-AIP-GOV-STATE-DURABILITY-ADR` · PO/ARB act 2026-08-22 quoted in the commission registration `8b92a100` (*"Repair `RV-1…RV-7` only, produce evidence, stop"*) · grant `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-RV-REPAIR` (AUTHORIZED) · independent DV review by `a8ce5a39` (routing + per-finding table) · bounded review @ `ff50a2cf` (🟡 CHAIN NOT READY; `RV-1` gateway) · DV correction `2f0301c2` · plan baseline `8307beca` · repaired plan + summary working-tree diffs (verified above) · `R-34`/`P-2` (producer bar; acceptance is the PO/ARB's) · §0.4.4 (append-only, superseded text shown) · `G-2`/`R5b` · `G-3` · `INV-ATTR-1`/`INV-ATTR-2` (self-declared identity) · `ES-004.3` · `ES-005.4` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`); `reviews/` per the review README convention
