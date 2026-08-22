# `KOS-AIP-GOV-STATE-DURABILITY` — **DV CORRECTION SUMMARY** *(`DV-1`…`DV-7`)*

**Status: 🟡 `PROPOSED` · `ADDRESSED` — ⛔ NOT CLOSED, NOT REGISTERED, NOT ACCEPTED.**

> ## ⛔ **MIGRATION NOT EXECUTED.**
> **Nothing was moved, copied, pinned, switched, marked, demoted, quarantined or removed. No durable target was created. No quarantine store was created. No `.gitignore` or `.gitattributes` change. No runtime code change. No authority record modified. No grant registered. No aggregate created. No acceptance registered.**
> ⛔ **PHASE 3 MUST NOT BEGIN. PHASE 5 REMAINS PROHIBITED.**

**Canonical governed aggregate:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · **workflow** `architecture-adr`. ⛔ **`KOS-AIP-GOV-STATE-DURABILITY` is the TRACK LABEL only and was NOT used as the aggregate key.**
**Commission:** the existing **DV-CORRECTION** commission — ⛔ **no new commission was created and the gate was not reinterpreted.**
**Authorization:** `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-GATE-OPEN` — ⭐ **`GATE OPEN`, recorded in the governed channel** against fourteen verified conditions.
**Primary finding source:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6-ARCHITECTURE-REVIEW.md` — the **INDEPENDENT** AMD6 review by `claude-code-session:dd639043`, **583 lines, committed at `40a4f06e`**, verdict 🟡 `PASS WITH DESIGN CLARIFICATIONS`. ⭐ **Consumed as the explanation of WHY each residual exists — §5.5–§5.9, §6.2, §12, §15 — and NOT reduced to checklist text.**
**Amended artifacts:** the migration plan and the AMD6 summary, both baselined at **`8307beca`**.
**Producing process, self-declared and ⛔ NOT attestable** (`INV-ATTR-1`/`INV-ATTR-2`, `G-2`): **`claude-code-session:f7e57e4a`** — **a fresh lane.** ⛔ **It is NOT `dd639043`, authored no prior amendment in this chain, and reviewed none. It is BARRED from reviewing and from accepting this correction** (`R-34`/`P-2`).
**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**.

---

# 1 · Pre-authoring declaration — **the seven items, as required**

**Recorded in the amended artifact itself at plan §0.7.1, before any edit was made.** *(The declaration is required because flag `AG` identified numbering interpretation as a recurring failure mode.)*

| # | Declaration |
|---|---|
| **1** | ⭐ **The REGISTERED reading-order index was consumed** — `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-READING-ORDER`, read from the canonical aggregate record. ⛔ **NOT a hand-copied list** |
| **2** | ⭐ **`C-13` consumed IN FULL EXCEPT its superseded sequence clause** |
| **3** | ⭐ **`C-15` is AUTHORITATIVE for the corrected slot-3 order** |
| **4** | ⭐ **`C-16` is AUTHORITATIVE for `DV-3` terminology** |
| **5** | ⭐ **Canonical Phase-5 slots REMAIN `1 · 1b · 2 · 3 · 4 · 5`** |
| **6** | ⭐ **The final verification is a SUB-STEP of slot 3 — `3(iii-b)`** |
| **7** | ⭐ **Slot 3 is NOT renumbered to slot 4.** ⛔ **`4(i)`/`4(ii)`/`4(iii)` appear nowhere as identifiers** |

## 1.1 The commission resolved from the record — ⭐ **six cited grant IDs, ALL SIX RESOLVE**

**Resolved mechanically from `.claude/runtime/workflow/KOS-AIP-GOV-STATE-DURABILITY-ADR.json` (22 grants):**

| # | Registered grant ID | Index |
|---|---|---|
| 1 | `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-DV-CORRECTION` | ✅ 11 |
| 2 | `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-PROVENANCE-PREREQ` | ✅ 13 |
| 3 | `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-DV-CORRECTION-C13` | ✅ 12 |
| 4 | `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-C14-SLOT-NUMBERING` | ✅ 14 |
| 5 | `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-C15-CORRECTED-AUTHORING` | ✅ 15 |
| 6 | `G-KOS-GOV-STATE-DURABILITY-DV-CORRECTION-C16-DV3-SEMANTICS` | ✅ 16 |

⭐ **No ID was repaired by interpretation and no ID was guessed.** ⚠️ **Two transcription defects are recorded in the chain and BOTH were avoided by resolving from the record:** the reading-order index's own citation of `C-16` inserted a `MIGRATION-PLAN-` segment; a hand-copied gate-request list omitted it from `C-13`. ⭐ **Only the base commission and `C-13` carry the longer prefix.**

---

# 2 · `DV-1` + `DV-2` — **ONE coupled verification problem** *(flag `AE`)*

## 2.1 The `SOURCE FINAL-STATE ENUMERATION` — **the single comparison object**

| Property | As corrected |
|---|---|
| **established from** | the governed **source** state, measured at `3(i)` |
| **CLOSED** | ⭐ **at `3(ii)` — BEFORE the final copy-side write** |
| **immutability** | ⭐ **IMMUTABLE for the final verification from that instant** |
| **cardinality** | ⭐ **ONE. It is the single comparison object** |
| ⛔ **never** | **reconstructed from the final durable copy** · **closed again after the final copy-side write** · **`FES`/`CFS`** · **a second comparison object** |

> ⭐ **`C-13`'s sequence clause was superseded for a load-bearing reason, and the reason is preserved here rather than dropped:** `C-13` ordered the enumeration's construction **after** the final copy-side write. **That ordering would have closed the enumeration after the write it exists to constrain — reinstating the *"the object silently absorbs the bytes"* failure mode which the independent review had recorded as STRUCTURALLY EXCLUDED precisely because the object is fixed at slot 3.** ⛔ **An author following `C-13` literally would have rebuilt the exact defect.** ⭐ **`C-13`'s other content stands in full.**

## 2.2 ⭐ **The canonical slot-3 sequence — `1 / 1b / 2 / 3 / 4 / 5` preserved, slot 3 sub-divided**

```
slot 3, in order            ⛔ slot 3 is NOT renumbered. These are SUB-STEPS OF SLOT 3.

  3(i)     MEASURE the source, after slot 2's append has landed        (existing act)
  3(ii)    VERIFY against manifest + reconciled delta + the DECLARED
           pre-switch Phase-5 records — §4.0's three outcomes          (existing act)
           ⭐ THE ENUMERATION IS CONSTRUCTED AND CLOSED HERE
  3(iii)   bring the durable copy to that ALREADY-CLOSED state
           (a MIGRATION MECHANICAL WRITE, not a governance append)
  3(iii-b) 🔴 FINAL DURABLE-COPY VERIFICATION                    ← NEW. THE DV-1 REPAIR.
           compare the FINAL durable copy against the ALREADY-CLOSED enumeration
           PHASE-4 SEMANTICS · ALL-OR-NOTHING (byte · hash · count · sequence · provenance)
               PASS → continue to 3(iv)
               FAIL → ⛔ STOP · RECORD · ESCALATE · ⛔ NO AUTHORITY TRANSFER
  3(iv)    ⭐ SWITCH P-1 / AUTHORITY TRANSFER
  3(v)     write the SWITCH-OVER RECORD into the authoritative store, carrying the
           enumeration AND the 3(iii-b) verification receipt

slot 4  RUNTIME DEMOTION MARKER          (unchanged)
slot 5  STAGING MARKER RETRACTION        (unchanged)
```

⛔ **The forms `4(i)` / `4(ii)` / `4(iii)` were NOT created for slot-3 operations** — mechanically verified: **zero step labels of that shape in any of the three artifacts** *(structural check `T1`, validated non-vacuous — §11)*.

## 2.3 🔴 **The FINAL DURABLE-COPY VERIFICATION — what it repairs**

**The defect, as the independent review established it:**

```
Phase 4       verify the durable copy vs the FROZEN MANIFEST — ALL-OR-NOTHING
slot 1b       reconcile the declared delta INTO THE DURABLE COPY      ← copy-side write #1
slot 3(iii)   bring the durable copy to the source's closing state    ← copy-side write #2
slot 3(iv)    SWITCH P-1 → the copy IS the authoritative store
Phase 7       FINAL RE-HASH: runtime source vs the RECORDED ENUMERATION → removal permitted
⛔ NEITHER OPERAND OF THAT GATE IS THE DURABLE COPY
```

| Test | Finding, as recorded |
|---|---|
| was the copy verified after write #1 or #2? | 🔴 **No.** The only Phase-4-semantics re-verification on the Phase-7 path sat inside §4.4's `PRE-SWITCH` **exception** branch and `P7·2`. ⭐ **The plan re-verified the copy on the EXCEPTION path and not on the NORMAL path — and the normal path is the one that ends in removal** |
| did any criterion supply the act? | 🔴 **No.** **Criterion 18 ASSERTED the carry-across and named a SPECIFICATION as its evidence — a specification is not a verifying act.** **Criterion 2's named evidence all PREDATES write #1** |
| what is lost if it fires? | 🔴 **the reconciled `1b` delta and the pre-switch Phase-5 records** — the records that exist in the source and **nowhere else** at the moment Phase 7 removes it — **with no gate having failed** |

⭐ **`3(iii-b)` covers BOTH copy-side writes because it runs after both, and it is placed at the last cheap stop point — where §4.3 already puts every other pre-switch check.** ⭐ **It is criterion 18's demonstrating act.** ⛔ **It adds no actor, no decision and no reordering.**

## 2.4 `DV-2` — **expected pre-switch evidence**

**The single expected-state model now explicitly covers `slot 1`, `slot 1b` and `slot 2`:** the re-hash comparison record · the reconciliation (or outcome-C) record · the reader-switch evidence. ⭐ **The CLASS-BASED form is preferred** — *"records written by this lane for this work item"* — which §4.0's own *"record classes"* wording already permits.

| | |
|---|---|
| ⛔ **the slot-3 authority-transfer append** | **is NOT a pre-switch source delta** and MUST NOT be declared as one — after `C-4` it is never written into the source at all |
| ⛔ **second declaration** | **none created.** ⭐ **One expected-state model, one comparison object** |
| ✅ **the property secured** | ⭐ **normal migration bookkeeping does NOT become an undeclared freeze violation** — while a genuine violation remains detectable, because the test is *"did anything change that we did not declare?"* |
| ⚠️ **inert-stale entry corrected** | §4.0's migration-lane list named *"the switch-over record"* among records expected in the **SOURCE** delta. **After `C-4` it can never appear there** |

> 🔴 ⭐ **The coupling, stated because it is why the pair must be reviewed together:** the enumeration's **third operand IS the declared pre-switch set.** ⛔ **Fixing `DV-1`'s timing while leaving `DV-2`'s declaration narrow would produce a verification that RUNS AT THE RIGHT MOMENT AGAINST AN INCOMPLETE OBJECT.**

---

# 3 · `DV-3` — **authority-boundary semantics**

**Every current occurrence of the now-superseded wording *"first record in the authoritative store"* is replaced by the canonical domain concept:**

> ## ⭐ **`FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER`**

```
BEFORE the transfer   the SOURCE store is authoritative
AUTHORITY TRANSFER    slot 3(iv) — the domain event
AFTER  the transfer   the DURABLE GOVERNANCE-EVIDENCE STORE is authoritative
```

**The discriminator is the GOVERNANCE EVENT / AUTHORITY TRANSITION.** ⛔ **It is NOT the first physical record · NOT the first JSON file · NOT the first directory entry · NOT the first copied record · NOT the lowest sequence number.**

**Why the old wording was FALSE and not merely imprecise:** ⭐ **the durable store ALREADY CONTAINS copied governance records before authority is transferred, because Phase 3 copies them.** ⇒ **the authority-transfer record cannot be the physical first record. The wording asserted something the migration's own phase order makes impossible.**

**`C-16`'s four required demonstrations, all four now carried by criterion 17:** *(1)* the durable store **may already contain** copied governance records; *(2)* those copied records **do not themselves establish authority**; *(3)* the **first governance append after the authority transfer is the discriminator**; *(4)* **later governance evidence belongs to the POST-TRANSFER state.**

**DDD non-confusions, both recorded:** ⭐ **`PHYSICAL STORAGE ORDER ≠ DOMAIN EVENT ORDER`** · ⭐ **`RECORD EXISTENCE ≠ AUTHORITY ESTABLISHMENT`.**

| Surface | State |
|---|---|
| **plan §0.6.3** `C-4`-property table | ✅ corrected |
| **plan §4 row 9** *Produces* cell | ✅ corrected |
| **plan §4.3** placement table | ✅ corrected |
| 🔴 **plan §10 criterion 17** — **the acceptance object** | ✅ corrected, **with all four demonstrations** |
| **AMD6 summary** §3.2 · §3.3 · §5 row 5 | ✅ corrected |
| ⭐ **VERIFICATION LOGIC** — the surface `C-16` flagged as most likely to be missed | ⭐ **MEASURED AND EMPTY: no implemented verification logic exists.** ⚠️ **`RV-5` repair — the measurement is corrected:** `.claude/scripts/` contains **13 entries** — **10 shell scripts**, a `README.md`, and the two PHP files `session-resolve.php` + `workflow-state.php`. ⛔ **superseded wording:** *"contains exactly `session-resolve.php` and `workflow-state.php`"* — **a false measurement.** **None of the shell scripts references the workflow record store, and neither PHP file identifies any discriminator by physical position, first-record, filename order or lowest sequence.** ⛔ **Nothing to correct — and nothing invented to appear thorough.** ⚠️ **A future implementer MUST NOT locate the discriminator positionally: such a check would pass its own test while encoding the false semantic** |

⛔ **No second concept and no synonym was introduced while replacing the wording.**

---

# 4 · `DV-4` — **the `C-4` termination argument**

**The load-bearing premise now sits INSIDE the argument, as an enumerated derivation** *(plan §4.3; the compressed form in §0.6.3 carries it too)*:

```
PREMISE 1  the pre-switch records (slots 1, 1b, 2) are GOVERNANCE APPENDS INTO THE SOURCE
PREMISE 2  ⭐ THE LOAD-BEARING ONE, PREVIOUSLY UNCITED HERE:
           a MIGRATION MECHANICAL WRITE confers NO AUTHORITY and
           IS EVIDENCED BY A GOVERNANCE APPEND THAT DESCRIBES IT
           ⇒ pre-switch, that append lands in the SOURCE
⇒ a pre-switch slot-3 record re-opens the delta → reconcile → which is ITSELF evidenced
  by a further source-side append → ⛔ NON-TERMINATING
⭐ WITHOUT PREMISE 2 the recursion appears to stop after ONE iteration, because a
   mechanical write into the COPY need not touch the source at all.
```

⭐ **`RV-1` repair — step `3(iii)` is a THIRD mechanical write under `PREMISE 2`:** its evidencing append is CARRIED POST-SWITCH by the `SWITCH-OVER RECORD` at `3(v)`, alongside the enumeration and the receipt (plan §4.3 placement-table row). ⛔ The `3(iii-b)` receipt does NOT discharge it — the receipt attests the COMPARISON, not the WRITE.

**And the five steps `C-15` requires are stated in the argument itself:** *(1)* the enumeration is **CLOSED at `3(ii)`**; *(2)* the durable copy is **brought to that fixed state at `3(iii)`**; *(3)* the **final verification at `3(iii-b)` CONSUMES that fixed object**; *(4)* a **`PASS` is MANDATORY** — ⛔ no discretion bypasses a `FAIL`; *(5)* the **authority transfer can occur ONLY AFTER that `PASS`.**

⛔ **No hidden premise in another table or section is relied on.**

---

# 5 · `DV-5` — **the stale-read boundary**

⛔ **Superseded clause:** *"…and the re-hash has already bounded that."* 🔴 **False after `C-4`:** slot 2 switches the readers while slots 1, 1b and 2 all append **into the source**, so the window is **non-empty by construction** and contains at least slot 2's own record — **and slot 1's re-hash precedes all three, so it cannot bound them.**

| Concept | Role — ⛔ **and one never stands in for the other** |
|---|---|
| ⭐ **`SOURCE FINAL-STATE ENUMERATION`** | **the FIXED comparison state** — measured at `3(i)`, closed at `3(ii)` |
| ⭐ **`FINAL DURABLE-COPY VERIFICATION`** | **verification of the durable REPRESENTATION against that fixed state** — `3(iii-b)` |

**⇒ the actual bound is slot `3(i)`–`3(iii)`; `3(iii-b)` is not the bound but its evidence.**

**Remaining bounded observation window, stated explicitly and given its own residual-table row in BOTH artifacts:** a reader switched at slot **2** observes the copy **without** the source-side appends of slots 1, 1b and 2, **until `3(iii)` carries them across.** ✅ **Narrow and SELF-REFERENTIAL — only this work item's own migration bookkeeping — and irreducible without atomicity.** ⛔ **Recorded, NOT closed.**

---

# 6 · `DV-6` — **the quarantine MOVE refusal: `QUARANTINE LAUNDERING`**

**The actual rationale, preserved and stated as the chain it produces:**

```
a POST-DEMOTION record is OUTSIDE the SOURCE FINAL-STATE ENUMERATION — BY DEFINITION
    ⇒ its presence in the demoted store is exactly WHY the final re-hash FAILS
MOVE it out of the demoted store
    ⇒ HIDES the quarantined evidence
    ⇒ the source SPURIOUSLY SATISFIES criterion 12  → "identical"
    ⇒ ⛔ the REMOVAL BRANCH OPENS while a quarantine stands UNDISPOSED
    ⇒ ⛔ REMOVAL BECOMES ELIGIBLE INCORRECTLY
⇒ THIS IS QUARANTINE LAUNDERING. MOVE REMAINS PROHIBITED.
```

⭐ **It is `RD-3·a`'s third unsafe reading — *"an implementer removes the store to make the check pass"* — in the subtler form the section did not name: not removing the STORE, but removing THE ONE RECORD THAT MAKES IT FAIL.**

⛔ **The inverted reason is WITHDRAWN, not softened.** *"A move would break the enumeration"* is **false in the direction that matters**: a post-demotion record was never IN the enumeration, so a move does not break the comparison — **it makes the comparison PASS when it should FAIL.** ⛔ **It was NOT replaced with a generic *"data integrity"* explanation.**

✅ **Stated so the finding is not overdrawn: the rule is doubly guarded independently of the reasoning** — `P7·2` forbids the move in terms, and **criterion 20 · §4 row 11 · `P7·3`** refuse removal while any quarantine is undisposed. ⭐ **What this correction restores is an implementer's ability to REASON to the prohibition.**

⛔ **No rule changed · no act added · `OPEN-M7` NOT decided · quarantine ownership NOT invented.**

---

# 7 · `DV-7` — **trace integrity**

**The undefined wildcard operator token is REMOVED from both artifacts.** §4.7 defines exactly **nine** operator IDs — `P5·1 · P5·1b · P5·2 · P5·3 · P5·4 · P5·5 · P7·1 · P7·2 · P7·3` — and the trace cited a tenth that existed only in the citing cell.

**Trace row 6 now cites the six instructions that actually carry the placement rule:** `P5·1` · `P5·1b` · `P5·2` · `P5·3` · `P5·4` · `P5·5`.

⛔ **NO token was invented to satisfy the trace.** ⭐ **The required four-layer shape holds — `ACT → NORMATIVE ENUMERATION → ACCEPTANCE CRITERION → OPERATOR INSTRUCTION` — and every reference resolves** *(mechanically re-measured: every `P5·`/`P7·` identifier cited in any trace row of either artifact is defined in plan §4.7)*.

⚠️ ⭐ **Recorded rather than quietly fixed: the trace's closing claim *"every row resolves"* was FALSE for row 6 as AMD6 delivered it** — the self-check asserted its own completeness while carrying an unresolvable reference. ⭐ **That is exactly the assurance-tooling boundary `C-13` registered: a self-check that can assert its own success is EVIDENCE, never assurance.**

> ⚠️ **One judgment disclosed for the reviewer rather than buried:** `DV-7` says *remove*, while §0.4.4's canonical-document rule normally requires superseded text to be SHOWN. **Both are honoured by removing the token and citing the independent review §6.2 at `40a4f06e` as the durable record of the withdrawn identifier.** ⭐ **`DV-3`, `DV-5` and `DV-6` are treated differently and deliberately — their commissions say *replace every CURRENT occurrence*, so their superseded wording IS retained as labelled history.** **If the reviewer judges either treatment wrong, it is a wording disposition, not a design change.**

---

# 8 · ⭐ **The VERIFICATION RECEIPT — placement outcome**

**The commission established the semantics and deliberately left the destination open:** the receipt is **evidence ABOUT verification**, is **NOT part of the `SOURCE FINAL-STATE ENUMERATION`**, is **NOT an input to the verification it records**, and is **created after the comparison result exists.**

## 8.1 Canonical discovery ran FIRST *(`ES-005.4`)* — **it found two things, not none**

| Searched for | Found |
|---|---|
| an existing **receipt** concept | ✅ **YES — and it is a DIFFERENT domain concept.** `KOS-AIP04-DISCOVERY-001`'s **C-10 receipt** *(context-package delivery / acknowledgement, `DECIDED: Option C`)*. ⛔ **NOT consumed as the same object — a delivery receipt is not a verification receipt.** ⭐ **What IS consumed is its invariant, which transfers exactly: `AUTHORITY IS CLAIM-SCOPED`** |
| an existing **placement rule** | ✅ **YES — §4.3's placement table** *(subject-state → destination, itself DERIVED from §3's resolver contract)* **plus §5** *(migration evidence is itself governance evidence)*. ⭐ **The rule is determinate** |

## 8.2 ⭐ **OUTCOME A — existing governed placement owns the receipt**

```
the receipt exists only AFTER the comparison result                (C-14)
    ⇒ it cannot be part of what it attests, and is NOT an operand of 3(iii-b)
before 3(iv) the staging store cannot receive an authoritative record at all   (§3 row 2)
    ⇒ the receipt cannot be an authoritative append there yet
if appended into the SOURCE at 3(iii-b) it would land AFTER the enumeration closed at 3(ii)
    ⇒ the source would hold content outside the closed enumeration
    ⇒ Phase 7's final re-hash reports a delta in the NORMAL case
    ⇒ ⛔ the DV-2 defect class re-created, and the C-4 recursion re-opened
⇒ the receipt is a POST-SWITCH record, CARRIED BY the SWITCH-OVER RECORD at 3(v),
  exactly as the SOURCE FINAL-STATE ENUMERATION already is
```

| | |
|---|---|
| ⭐ **destination** | **the AUTHORITATIVE store, CARRIED BY the `SWITCH-OVER RECORD` at `3(v)`** |
| ⭐ **why that carrier** | **it already exists and already carries one such object** ⇒ ⛔ **no new store, record class, evidence boundary or provenance mechanism.** ⭐ **It also keeps the `DV-3` discriminator SINGLE — the receipt never competes with the switch-over record for the *first governance append* identity** |
| ⭐ **claim scope** | **authoritative for *"the `3(iii-b)` comparison was performed against the closed enumeration and its result was `PASS`"*** — ⛔ **NOT for *"the durable copy is correct"*** |
| ⭐ **the `FAIL` branch** | **no transfer ⇒ no authoritative store to append to** ⇒ **the failure record is an ORDINARY PRE-SWITCH APPEND INTO THE SOURCE, exactly as §4.3 already prescribes for outcome C.** ✅ **Safe: Phase 7 never runs, nothing is destroyed, no removal gate is consulted** |
| ⛔ **not decided** | **physical form and layout** — resolved at execution time through existing placement governance, as §2 already requires |

> ⭐ **The boundary of outcome A, recorded honestly:** the governing RULE is consumed unchanged, ⚠️ **but the placement table gains a ROW, because the act did not exist before.** ⛔ **That is an application of the existing rule, not a new rule.** **If the independent reviewer judges otherwise, the correct disposition is OUTCOME B — record the unresolved placement question and route it through existing governance — and ⛔ NEVER the invention of a store.**

---

# 9 · Exact sections changed

**Baseline `8307beca` · plan `1206 → 1435` lines · AMD6 summary `285 → 310` lines · `287 insertions, 33 deletions`.**

| Artifact · section | Change | Findings |
|---|---|---|
| **plan §0.6.3** `C-4` property table | the discriminator claim restated; superseded wording labelled | `DV-3` |
| **plan §0.6.3** termination derivation | premise carried into the compressed argument | `DV-4` |
| **plan §0.6.5** trace row 6 + closing claim | undefined token removed; six real IDs cited; false claim recorded | `DV-7` |
| ⭐ **plan §0.7** *(NEW)* | the DV correction: declaration · commission resolution · disposition · its own trace · receipt placement · non-actions | all |
| **plan §4 row 9** | `3(iii-b)` named; *Produces* cell corrected and extended | `DV-1` · `DV-3` |
| **plan §4.0** | ⭐ **the declared set MUST cover slots 1, 1b, 2**; class-based form preferred; inert-stale entry corrected | `DV-2` |
| **plan §4.3** readers-first note | the bound re-attributed; comparison-object vs verification separated; window stated | `DV-5` |
| **plan §4.3** slot-3 block | ⭐ **`3(iii-b)` inserted**; enumeration closed at `(ii)`; already-closed at `(iii)`/`(iii-b)`; forbidden forms barred | `DV-1` · `C-15` |
| **plan §4.3** `DV-1` rationale block *(new)* | why `3(iii-b)` exists, with the defect sequence and fair attribution | `DV-1` |
| **plan §4.3** property table | two rows added; the absorption row now cites the closing instant it depends on | `DV-1` · `C-15` |
| **plan §4.3** placement table | ⭐ **receipt row added** | `DV-1` · receipt |
| **plan §4.3** stop-cost table | `3(iii-b)` stop point and its cost class | `DV-1` |
| **plan §4.3** residual-window table | ⭐ **stale-read window row added** | `DV-5` |
| **plan §4.3** termination argument | ⭐ **`PREMISE 2` moved INSIDE**; `C-15`'s five steps stated | `DV-4` |
| **plan §4.4** Phase-7 precondition | what the gate does and does NOT cover; `RC-4` vs `DV-1` kept apart | `DV-1` |
| **plan §4.4** quarantine mechanism row | inverted reason withdrawn and labelled | `DV-6` |
| **plan §4.4** laundering block *(new)* | ⭐ **`QUARANTINE LAUNDERING` stated as the load-bearing reason** | `DV-6` |
| **plan §4.7** `P5·3` | `(iii-b)` added with its STOP condition and the no-re-close prohibition | `DV-1` |
| **plan §4.7** `P7·2` | the MOVE prohibition carries its real reason | `DV-6` |
| **plan §10** criteria **2 · 11 · 12 · 16 · 17 · 18** | the demonstrating act supplied; criterion 17 fully restated | `DV-1` · `DV-2` · `DV-3` |
| **plan §8** triggers + rollback windows | `3(iii-b)` FAIL as a mandatory stop inside the pre-switch window | `DV-1` |
| **plan §12** + closing block | the DOING column; status; next actor; where each correction bites | all |
| **AMD6 summary** header · §3.2 · §3.3 · §3.4 · §4 · §5 | propagation notice · placement · slot-3 · stale-read · laundering · trace | `DV-1` · `DV-3` · `DV-5` · `DV-6` · `DV-7` |

⭐ **§10 still carries exactly 20 criteria, `1…20`, each once — no criterion was added or removed.**

---

# 10 · Second-artifact propagation

| Artifact | Status |
|---|---|
| **migration plan** | ✅ **corrected** |
| **AMD6 summary** *(`docs/knowledgeos/architecture/`)* | ✅ **corrected.** ⚠️ **`C-13` cites this artifact under `docs/knowledgeos/reviews/`, where it does not exist — a path error the later discharge-correction grant already recorded. The real path was used** |
| **acceptance criteria** *(plan §10)* | ✅ **corrected** |
| **operator instructions** *(plan §4.7)* | ✅ **corrected** |
| ⭐ **verification logic** | ✅ **MEASURED AND EMPTY** — no implemented logic exists; `session-resolve.php` and `workflow-state.php` contain no positional discriminator |
| **AMD4 / AMD5 summaries · implementation design · ADR** | ✅ **checked mechanically — none carries the superseded wording or the withdrawn token** |
| ⛔ **the independent reviews** *(AMD3…AMD6, the commission reviewer note)* | ⛔ **NOT modified.** ⭐ **They are the FINDING SOURCES and historical records; rewriting a review to match a repair would destroy the evidence the repair rests on** |
| ⛔ **session logs** | ⛔ **NOT modified — append-only** |
| ⚠️ **`docs/knowledgeos/backlog/EKS-06-*`** · brainstorming notes | ⚠️ **left as-is: they RECORD the defect rather than assert the design.** ⭐ **`EKS-06` cites the withdrawn token as its subject, which remains accurate as a finding record** |
| **`.claude/CONTEXT.md`** | ✅ **updated as mutable runtime state** |

⛔ **NO CURRENT ARTIFACT RETAINS:** old `DV-3` wording as a current claim · old slot numbering · the withdrawn operator token · stale `DV-6` reasoning · stale `DV-1`/`DV-2` ordering. *(Mechanically re-measured — §11.)*

---

# 11 · Verification — ⭐ **EVIDENCE, ⛔ NOT ASSURANCE**

**Method: `RED` → smallest correction → `GREEN`. Properties were tested, not paragraphs.** ⚠️ **`RV-6` repair — the property-level counts are withdrawn as irreconcilable:** ⛔ **superseded wording:** *"Baseline measured BEFORE any edit: `26` of `35` properties FAILING. After the correction: `43` of `43` PASSING"* — ⛔ **and, in Traceability,** *"`37/37` properties re-measured GREEN from a `26`-failing RED baseline"* — **three mutually irreconcilable counts (`35`, `43`, `37`) for one measurement. The reconcilable measured evidence is the table below: 18 property tests (`T1`…`T18`), ALL PASSING after the correction.** ⛔ **No property-level count beyond the 18 table rows is asserted** *(the suite grew as the third deliverable was added; three tests were re-specified mid-run to encode the property rather than the string — see below).*

| # | Property | Result |
|---|---|---|
| **T1** | ⭐ **no slot-3 operation is LABELLED `4(i)`/`4(ii)`/`4(iii)`** — structural, over all three artifacts | ✅ |
| **T2** | exactly **ONE** normative enumeration of the Phase-5 order; canonical list `1 · 1b · 2 · 3 · 4 · 5` intact | ✅ |
| **T3** | **no CURRENT claim** uses the `DV-3` physical-position wording — both artifacts, five patterns | ✅ |
| **T4** | the canonical `DV-3` concept is present in both artifacts; `RECORD EXISTENCE ≠ AUTHORITY` stated | ✅ |
| **T5** | `3(iii-b)` present in both artifacts and in operator instruction `P5·3`; the verification is NAMED | ✅ |
| **T6** | ⭐ **`(iii-b)` PRECEDES `(iv) SWITCH` inside the §4.3 slot-3 block** *(parsed, not grepped)* | ✅ |
| **T7** | a `FAIL` bars the authority transfer | ✅ |
| **T8** | the enumeration is `ALREADY-CLOSED`, and ⭐ **is NOT re-constructed or re-closed after `(iii)`** *(the block's tail is parsed for construction INSTRUCTIONS, ignoring prohibitions)* | ✅ |
| **T9** | the withdrawn operator token occurs **zero** times in both artifacts | ✅ |
| **T10** | ⭐ **every operator ID cited in any trace row of either artifact is DEFINED in plan §4.7** | ✅ |
| **T11** | `QUARANTINE LAUNDERING` and *"spuriously satisfy"* present; the inverted reason is not a CURRENT claim — both artifacts | ✅ |
| **T12** | §4.0 requires the declared set to cover slots 1, 1b and 2 | ✅ |
| **T13** | the re-hash-bounds claim is not CURRENT; the stale-read window has a residual-table row in both artifacts | ✅ |
| **T14** | ⭐ **the evidencing premise sits INSIDE the termination argument** *(the argument's own span is parsed)* | ✅ |
| **T15** | acceptance criteria `1…20`, each exactly once | ✅ |
| **T16** | operator IDs unique | ✅ |
| **T17** | the receipt is recorded as excluded from the comparison target | ✅ |
| **T18** | ⭐ **this correction summary is itself clean** — withdrawn token absent · no current `DV-3` wording · no forbidden step label · canonical slot list intact · `MIGRATION NOT EXECUTED` and `NOT CLOSED` both stated | ✅ |

> ⚠️ **Test re-specifications disclosed rather than hidden, because a test quietly loosened to pass is worse than a failing test.** **Three tests initially failed on the correction's OWN labelled-history quotations and on PROHIBITIONS naming a forbidden form.** ⭐ **They were re-specified to measure the PROPERTY rather than the raw string:** *"no CURRENT claim uses X"* *(current = not on a line labelling it superseded/withdrawn/forbidden)* · *"no INSTRUCTION re-constructs X"* *(the block's tail parsed, prohibitions ignored)* · *"no STEP LABEL is `4(x)`"* *(structural, anchored at line start)*. ⛔ **No test was deleted and no threshold was lowered.**
> ⭐ **And the structural test was validated as NON-VACUOUS before being trusted:** the same regex applied to the `3(…)` family returns a **non-zero** count, so it demonstrably detects step labels of this shape — ⛔ **a test that passes because it matches nothing is not evidence.**

> ⛔ **`C-13`'s assurance-tooling boundary, honoured: the checker PRODUCES EVIDENCE. Architecture repaired the design. AN INDEPENDENT REVIEWER STILL JUDGES THE REPAIR. The tool does not self-approve, and this section is NOT a review.**

**Corpus re-measured after amending — ⭐ nothing executed:** `.claude/runtime/workflow/` holds **18 records · 216 transitions · 125 grants**; **no `*.tmp*` remnant**; `.gitignore` and `.gitattributes` **untouched**; **no durable target exists**; **no quarantine store exists**; **no grant registered by this act**; the canonical aggregate is **`KOS-AIP-GOV-STATE-DURABILITY-ADR`** *(workflow `architecture-adr`, **22 grants**)* and ⛔ **no second aggregate was created.**
> ⭐ **The grant count moved `114 → 125` between AMD6 and this correction — `RD-10`'s phenomenon and `C-4`'s premise, observed live again: the new grants are THIS correction's OWN commission, appended by `P-1` into the very corpus the migration freezes and hashes.** ⛔ **A literal freeze would have had to prohibit the acts that authorized this repair.** ⭐ **It is also live evidence for `DV-2`: the declared set must be CLASS-BASED, because the exact grant list is not knowable in advance.**

---

# 12 · Current OPEN items — ⛔ **none closed by this correction**

| | Item | State |
|---|---|---|
| 🔴 | **`DV-1`…`DV-7`** | ⛔ **`PROPOSED` · `ADDRESSED` · NOT CLOSED.** ⭐ **This process MUST NOT self-close them** |
| 🔴 | **`DV-1`'s Phase-5 bar** | ⛔ **PHASE 5 MUST NOT EXECUTE until the repair is INDEPENDENTLY REVIEWED.** ⭐ **The repair being present in the artifact is NOT the discharge of that condition** |
| 🔴 | **AMD6 itself** | ⛔ **`PROPOSED` · `ADDRESSED` · NOT CLOSED · NOT REGISTERED · NOT ACCEPTED** |
| 🔴 | **`OPEN-M6`** | ⛔ **no delivered amendment is registered — AMD3 + AMD4 + AMD5 + AMD6. Registration has exactly one writer, Governance** |
| 🔴 | **`OPEN-M7`** | ⛔ **quarantine disposer and path — NOT decided, NOT invented.** ⭐ **It can block the migration's COMPLETION indefinitely** |
| ⚠️ | **`OPEN-M5`** | ⛔ **NOT decided** |
| ⚠️ | **`OPEN-M1` · `OPEN-M2` · `OPEN-M4`** | ⛔ **unchanged** |
| ⏳ | **Phase-0 freeze declaration** | ⛔ **PO/ARB's act, outstanding.** ⭐ **`DV-2` states what it must CONTAIN; it does NOT write it** |
| ⏳ | **Phase-4b authorization · plan acceptance** | ⛔ **outstanding, and both belong to other actors** |
| ⚠️ | **the receipt's physical form** | **deferred to execution-time placement governance, as §2 already requires** |
| ⚠️ | **carried, NOT this correction's commission** | `RD-2` · `RD-4` · `RD-5` · `RD-6` · `RD-9` · `RD-10·r1` · `RD-10·r2` *(enlarged by `DV-2`)* · `DI-1·r` |

---

# 13 · Routing — ⛔ **STOP. The author does not proceed past here.**

```
THIS CORRECTION            authored by claude-code-session:f7e57e4a  (a fresh lane)      ✅ DONE
        ↓
FRESH INDEPENDENT          ⛔ MANDATORY. ⛔ NOT the author. ⛔ NOT dd639043.
ARCHITECTURE REVIEW           ⭐ Test DV-1 and DV-2 TOGETHER (flag AE), not in isolation.
        ↓
GOVERNANCE BOUNDED         completeness · provenance · lineage · current/superseded integrity ONLY.
REVIEW                     ⛔ NEVER citable as independent technical verification.
                           ⭐ C-11 verbatim disclosure mandatory if performed by b64828fe.
        ↓
PO/ARB                     decision and acceptance authority.
```

⛔ **The author MUST NOT review this correction, MUST NOT accept it, and MUST NOT perform the independent Architecture review.**
⛔ **Authority boundaries preserved:** **Architecture** is technical design authority · **Governance** is provenance, registration and completeness authority · **PO/ARB** is decision and acceptance authority · **the Reviewer** is independent judgement. ⭐ **No role absorbed another's authority in this correction.**

**DDD principles preserved:** `Evidence ≠ Proof` · `Evidence identity ≠ Authority` · `Authority ≠ Storage location` · `Execution state ≠ Governance evidence` · `Quarantine ≠ Authority` · `Record existence ≠ Authority establishment`. ⛔ **No domain authority is derived from physical storage order anywhere in the corrected artifacts.**

> ## ⛔ **MIGRATION NOT EXECUTED. PHASE 3 MUST NOT BEGIN. PHASE 5 PROHIBITED.**

---

**Traceability:** ⭐ **six REGISTERED grants, cited by reference and not paraphrased into authority** — `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-DV-CORRECTION` · `…-DV-CORRECTION-PROVENANCE-PREREQ` · `…-MIGRATION-PLAN-DV-CORRECTION-C13` *(sequence clause superseded)* · `…-DV-CORRECTION-C14-SLOT-NUMBERING` · `…-DV-CORRECTION-C15-CORRECTED-AUTHORING` · `…-DV-CORRECTION-C16-DV3-SEMANTICS` · the registered index `…-DV-CORRECTION-READING-ORDER` · the authorization `…-DV-CORRECTION-GATE-OPEN` · the discharge records `…-PREREQ-DISCHARGE-OBLIGATIONS` / `…-PREREQ-DISCHARGE-CORRECTION` / `…-PREREQ-SUMMARY-PROVENANCE-SATISFIED` · **the INDEPENDENT AMD6 Architecture review by `claude-code-session:dd639043` at `40a4f06e`** — its §5.5 *(`DV-1`)*, §5.6 *(`DV-2`)*, §5.7 *(`DV-3`)*, §5.8 *(`DV-5`)*, §5.9 *(`DV-6`)*, §6.1–§6.2 *(`DV-7`)*, §4 row 234 *(`DV-4`)*, §12, §15, §16 and §17 · the commission reviewer note `KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-COMMISSION-REVIEWER-NOTE.md` · plan and AMD6 summary baselined at **`8307beca`** · **primary evidence re-derived here, not quoted:** the canonical aggregate read from its own `workItem`/`workflow` fields *(22 grants)*; corpus **18 records · 216 transitions · 125 grants**; `.claude/scripts/` = **13 entries** (10 shell scripts + `README.md` + `session-resolve.php` + `workflow-state.php`), **no positional discriminator in any of them** *(`RV-5` repair — the earlier "only two files" measurement was false)*; `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, **exit 0**; **18 property tests (`T1`…`T18`) ALL PASSING after the correction** *(`RV-6` repair — the earlier `37/37` GREEN from a `26`-failing RED baseline is withdrawn as irreconcilable with §11)* · `C-10` receipt decision *(`AUTHORITY IS CLAIM-SCOPED`)* in `KOS-AIP04-DISCOVERY-001` · `B′` · `R-CONFLICT` · `OPEN-M3` Option A · `INV-ORDER` · `INV-ATTR-1`/`INV-ATTR-2` · `G-2`/`R5a` · `R-34`/`P-2` · `ES-004.3` · `ES-005.4` · `ES-006.1`.
