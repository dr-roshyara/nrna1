# AMD5 — **INDEPENDENT** Principal Architecture review (REMEDY validation)

**Work item:** `KOS-AIP-GOV-STATE-DURABILITY`
**Artifact reviewed:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` — **AMD5**, commit `7d3abc59`, 833 lines *(secondary: the AMD5 summary and the AMD4 summary's `DI-1` correction at the same commit)*
**Input consumed, not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD4-ARCHITECTURE-REVIEW.md` — `RD-7`, `RD-3`, `RD-10`, `DI-1`, `DI-2`, `DI-3` **as its author stated them** (§3 `RC-4`/`RC-6`, §4.1, §6, §8). ⛔ **Not derived from AMD5's restatement.**
**Date:** 2026-08-20
**Verdict:** 🟡 **PASS WITH DESIGN CLARIFICATIONS** — **3 of 6 CLOSED · 3 ADDRESSED BUT NOT CLOSED · ⛔ Phase 3 must not begin**

**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, exit 0.

---

# 1 · Reviewer identity

**Reviewer process:** `claude-code-session:ccf6c9c7`

| Excluded process | Role | Match? |
|---|---|---|
| `claude-code-session:1c8b041b` | original migration-plan producer | ✅ **no** |
| `claude-code-session:bc1b47ef` | technical review `a282d14b` · AMD3 · AMD4 · **AMD5** producer | ✅ **no** |
| `claude-code-session:9c908e70` | independent AMD3 reviewer · author of `RC-1`…`RC-11` | ✅ **no** |
| `claude-code-session:870305e0` | independent AMD4 reviewer · author of `RD-7`/`RD-3`/`RD-10`/`DI-1`/`DI-2`/`DI-3` | ✅ **no** |
| `claude-code-session:5e1dd9ee` | implementation design `ae451db9` *(disclosure-only)* | ✅ **no** |

# 2 · Independence

> **This reviewer has no prior authorship or material participation in the migration plan, AMD3, AMD4, AMD5, or the independent Architecture reviews.**

⚠️ **Self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`) — the same limitation every prior reviewer recorded, and the reason the corpus's own unversioned state (`B′`) matters.
⭐ **The §0.4.1 bound is DISCHARGED for the six residuals:** the findings are `870305e0`'s, the remedies are `bc1b47ef`'s, the judgement below is a fourth process's. **This review produces no remedy text, so it hands no new self-review exposure forward.** **`R-34`/`P-2`: evidence and a recommendation only — this review accepts nothing, and nothing of its own.**

# 3 · AMD5 governance status — disclosed, not resolved

| | |
|---|---|
| **AMD5** | 🟡 **PROPOSED · AMENDED — NOT REGISTERED** |
| **Grant** | ⛔ **none.** Independently verified: `grep -o 'G-KOS-GOV-STATE-DURABILITY[A-Z0-9-]*' .claude/runtime/workflow/*.json` → `…-MIGRATION-PLAN`, `-AMD1`, `-AMD2`, `-DECISION`, `-OPEN-M3-DECISION` — **no `-AMD3`, no `-AMD4`, no `-AMD5`** |
| **`OPEN-M6`** | ⏳ **OPEN — now spans AMD3, AMD4 and AMD5** |
| **Execution state** | ⛔ **nothing executed.** `.gitattributes` is still `* text=auto` with **no `-text` pin** · no durable target exists · the corpus is still ignored (`git check-ignore` → `.gitignore:32`) and has **no git history** · corpus **18 records / 216 transitions / 110 grants**, re-measured here |

⛔ **This review does not register AMD5, AMD4 or AMD3, does not call any of them adopted, does not accept AMD5, and manufactures no authority.** Registration has exactly one writer — Governance (`G-2`/`R5a`).

---

# 4 · `RD-7` — staging marker retraction → 🟡 **ADDRESSED BUT NOT CLOSED**

**The finding, as `870305e0` stated it:** §4.3 gave the staging marker a lifetime *"from Phase 2 until Phase 5"*, but Phase 5's mandated **step 4 wrote only the runtime `DEMOTED` marker** and no row removed the staging one. Because the staging store is committed at `2c-commit`, the consequence is **durable and versioned**: after the writer switch the AUTHORITATIVE store carries an on-disk `NON-AUTHORITATIVE` label. **Remedy asked for: add the retraction to the Phase-5 act, ordered after the writer switch.**

**AMD5's remedy:** §4.5 gains a four-row marker lifecycle table ending in ⭐ *"Phase 5 step 5 — RETRACT `NON-AUTHORITATIVE`"*, plus §4 row 9, §4.5's *"until it is RETRACTED in Phase 5"*, and **new acceptance criterion 14**.

## Independent test, requirement by requirement

| `RD-7` requirement | Finding |
|---|---|
| staging marker exists during the correct window | ✅ written at **Phase 2** (§4 row 3, §4.5 lifecycle), retracted in Phase 5 |
| writer switch occurs **before** retraction | ✅ step 3 → step 5 |
| retraction occurs **after** authority transfer | ✅ and the reason is stated correctly: retracting earlier would assert a transfer that had not happened |
| the authoritative store cannot **remain** labelled `NON-AUTHORITATIVE` | ✅ **the durable, versioned defect is closed** — this was the substance of `RD-7` |
| runtime gets the `DEMOTED` marker | ✅ step 4 |
| marker ops do not modify evidence bytes | ✅ **explicitly argued and correct** — out-of-band, directory-level, never `*.json`; independently re-confirmed against `session-resolve.php:130`'s `glob($recordDir.'/*.json')` |
| acceptance criteria capture the final marker state | ✅ criterion 14, and it correctly distinguishes itself from criterion 8 |
| interruption before/after retraction has coherent semantics | 🔴 **NO — see `RD-7·b`** |

## 🔴 `RD-7·a` — the retraction is **not in the mandated order it is numbered against**

**§4.3's block is labelled *"Phase 5, mandated internal order"* and it still terminates at step 4:**

```
1  RE-HASH        source vs frozen manifest
2  READERS        P-2, P-4
3  ⭐ THE WRITER   P-1                        ← THE AUTHORITY-TRANSFER INSTANT
4  MARKERS        runtime copy = DEMOTED      ← the block ends here
```

⛔ **There is no step 5 in the mandated order.** The retraction is carried by §4 row 9 and by §4.5's lifecycle table — **and acceptance criterion 14 cites *"Phase 5 step 5"*, a step number the mandated block does not define.** ⇒ **the artifact now has two current, non-identical enumerations of Phase 5's internal order, and the acceptance object depends on the one that is not labelled *"mandated"*.** ⭐ **This is the fourth occurrence in this chain of the defect shape `RD-7` itself named — an act named in a specification and not carried where the specification is normative** — and it is the same shape as the still-open `RD-1`, whose reconciliation step also has no slot in that block. ⇒ **one edit closes both: make the block `1 · 1b reconcile · 2 · 3 · 4 · 5`.**

## 🔴 `RD-7·b` — the closure claim is **stronger than the design it describes**

**§4.5 asserts:**

```
before the writer switch :  staging = NON-AUTHORITATIVE   ✅ true
after  step 5           :  authoritative store unlabelled ✅ true · runtime = DEMOTED ✅ true
⛔ at NO point is the authoritative store labelled NON-AUTHORITATIVE
```

⛔ **The final line is false, and it is falsified by the ordering the same section calls load-bearing.** Authority transfers at **step 3**. The retraction is at **step 5**. ⇒ **throughout steps 3→4→5 the store that is already authoritative is on-disk labelled `NON-AUTHORITATIVE`** — the reader-visible contradiction, reduced from *durable and versioned* to a two-step interval, **not eliminated**. The window box enumerates *"before the writer switch"* and *"after step 5"* and **omits the interval between them**, which is precisely the interval at issue.

⚠️ **The residual is irreducible without atomicity and is not a reason to reorder anything** — it is the exact class `870305e0` recorded as `RD-2` for the step 3→4 window and declined to block on. **The defect is the denial, not the window.** The plan's own standard applies: *the strongest statement must never exceed the evidence.* ⇒ **restate as: *"the exposure is reduced from a durable versioned label to the step 3→5 interval; it is irreducible without atomicity and is recorded, not closed"*, and give §8 a row for an interruption inside steps 3–5.**

⭐ **Nothing here is unsafe:** an incorrect marker misleads a reader who looks; it confers and removes no authority (§4.3 does that), and no evidence byte is touched in any window.

---

# 5 · `RD-3` — Phase-7 mismatch disposition → 🟡 **ADDRESSED BUT NOT CLOSED**

**The finding, as `870305e0` stated it:** §4.1 sent **every** Phase-7 mismatch to *"reconcile under §6 (CASE A / CASE B)"*, but §6.3 scopes the divergence window to Phases 3–5 and **CASE A appends the runtime tail as a legitimate extension** ⇒ a post-writer-switch write would be **imported** into authoritative evidence, while §8 already rules that re-promoting a demoted source is a **governance act, not a merge**. **Remedy asked for: split the disposition — gap-window writes reconcile; post-demotion writes are quarantined and escalated, never silently imported.**

**AMD5's remedy:** §4.4 gains a two-column `CASE α` / `CASE β` split, a tie-break, an `R-CONFLICT` compliance argument, and **new acceptance criterion 15**.

## What is genuinely closed

| Test | Finding |
|---|---|
| the split exists, with opposite dispositions | ✅ α reconciles under §6; β **quarantine · record the conflict · escalate · ⛔ never import** |
| α's routing is now formally in scope | ✅ **independently checked:** α is *"after the Phase-5 re-hash, before the writer switch"*, i.e. **inside** §6.3's *"Phases 3–5"* window. The routing AMD4 broke is correct for α |
| the tie-break is stated and conservative | ✅ *"if the ordering cannot be established from the records, treat as `CASE β`"* — and the justification is right: **β preserves without promoting** |
| distinguishing mechanism is stated, not implied | ✅ the writer switch is a recorded event and Phase 5 produces switch-over evidence; α precedes that record, β follows it |
| `R-CONFLICT` is **not** modified | ✅ **independently confirmed** — §6.1's quoted invariant is byte-identical to AMD4's, §6.2's *"migration interpretation"* labelling is unchanged, and no §6 text was touched by `7d3abc59` |
| no demoted bytes are **silently promoted** | ✅ **this is the substance of `RD-3`, and it is achieved** — β's import prohibition is unambiguous |
| the final re-hash mismatch remains an unconditional STOP | ✅ *"⛔ STOP. Do NOT remove."* precedes the split; §4 row 11 and criterion 12 hold |

## 🔴 `RD-3·a` — **`CASE β` has no terminating condition, and criteria 12 and 15 are jointly unsatisfiable in it**

**The two acceptance criteria, read together:**

```
criterion 12  removal requires  runtime source  ==  manifest + RECONCILED delta
criterion 15  CASE β means      those bytes are NOT reconciled — deliberately
```

⛔ **After a β event the runtime source therefore never equals `manifest + reconciled delta`, so §4.4's own gate can never be satisfied** — yet §4.4 closes with *"Then, **for either case**: re-verify (Phase 4 semantics, all-or-nothing) → only then may removal proceed"*, which promises a path to removal in β and defines no pass condition for it. **Three readings, and the plan does not choose:**

| Reading | Consequence |
|---|---|
| the reference object silently absorbs the quarantined bytes | 🔴 **that is the promotion β exists to forbid** |
| the re-verify simply keeps failing | ⚠️ **safe, but Phase 7 can then never complete after any β, and the plan nowhere says the demoted store is retained indefinitely** |
| an implementer removes the store to make the check pass | 🔴 **destroys the bytes quarantine promised to preserve** |

⇒ **state β's terminating condition explicitly.** The safe form is available and costs one clause: **β does not gate on re-verification at all — it hands Phase 7 to governance and the demoted store is RETAINED until governance disposes of the quarantined record.**

## 🔴 `RD-3·b` — quarantine has **no location**, and Phase 7 is the act that removes where the bytes live

**β says the bytes are *"PRESERVED"*. §4.5 says that at Phase 7 *"the runtime store and its `DEMOTED` marker are removed together."* The β bytes are inside runtime records.** ⛔ **The artifact never names a quarantine destination.** Phase 1's precedent — *"any `*.tmp.<pid>` remnant is QUARANTINED: never migrated, never deleted"* — means *left in place*, and **in place is exactly what Phase 7 removes.**

⚠️ **This is where an `R-CONFLICT` compliance risk actually sits — not in the invariant text, which AMD5 correctly leaves alone, but in the invariant's *"reconstruction capability"* limb once Phase 7 executes.** AMD5's compliance argument (*bytes preserved · conflict recorded · provenance intact · nothing chosen*) is sound **for the moment of quarantine** and is not yet established **through Phase 7**. ⇒ **name the destination (the governed evidence boundary, out of the record glob, provenance recorded) and state that Phase 7 cannot remove a store holding an undisposed quarantined record.**

## 🔴 `RD-3·c` — §8 still carries the **superseded single-branch disposition, unlabelled**

**§8's fourth window row — the one AMD4 added for `RC-4` — still reads:**

> *"at Phase 7, on a FINAL re-hash mismatch → NOT a rollback — a STOP-AND-RECONCILE. **Removal is refused until the difference is reconciled and re-verified.**"*

⛔ **That is the one-branch-for-both model `RD-3` refuted, standing as CURRENT text in the section a Phase-7 operator consults for what to do**, and it is unlabelled — a direct violation of §0.4.4 (*one canonical current definition per section; superseded wording only where explicitly labelled*). ⚠️ **§8's trigger list is affected too:** *"a **CASE B** conflict is discovered mid-migration"* now sits beside §4.4's **`CASE β`**. **`CASE B` (§6: same sequence, different content) and `CASE β` (§4.4: post-demotion write) are different classes with near-identical glyphs** ⇒ **rename one pair.** *(The AMD4 summary carries the old wording too; that artifact is scoped to AMD4 and AMD5's `DI-1` note says so — historical, not a defect.)*

## Residual observation, offered as analysis rather than as a finding

⭐ **At Phase 7, `CASE α` will normally present as `CASE B`, not `CASE A`.** A gap-window write took runtime's next `seq`; the first post-switch append took the *authoritative* store's next `seq` — **the same number**, against different content. ⇒ §6 fires **CASE B** (preserve both · record · escalate), not the tail-append of CASE A. ✅ **Safe, and it means both branches normally escalate**; the split's operative value is the **import prohibition**, which holds.

---

# 6 · `RD-10` — freeze semantics → ⭐ **CLOSED**

**The finding, as `870305e0` stated it:** Phase 0 declared *"no lane may append to the corpus"*, but the migration's own transitions and grants **are** corpus appends into the store being frozen ⇒ the manifest is stale immediately, the Phase-5 re-hash reports a delta in the normal case, criterion 11's *"provably quiet"* branch is **unreachable by construction**, and **a genuine violation becomes indistinguishable from the migration's own bookkeeping**. **Remedy asked for: the Phase-0 declaration explicitly carves out the migration lane's own records and names them as expected delta inputs, so a violation stays detectable.**

| Test | Finding |
|---|---|
| the partition exists | ✅ §4.0: **unrelated appends PROHIBITED** *(violation → detected, recorded, escalated, never silently reconciled)* · **migration-lane records EXPLICITLY PERMITTED and DECLARED IN ADVANCE** |
| the declaration must be explicit | ✅ *"the Phase-0 declaration MUST enumerate, in advance, the work items and record classes whose appends are expected"*, and §4 row 1 **produces** *"freeze declaration + the declared expected-delta list"* |
| three-way outcome | ✅ `identical` → quiet · `delta ⊆ declared` → **EXPECTED, reconcile separately** · `delta ⊄ declared` → 🔴 **FREEZE VIOLATION, stop, record, escalate** — and it is applied at **both** re-hash points (*"Phase 5 / Phase 7"*), which the Phase-7 gate needs |
| criterion 11 restated, superseded text labelled | ✅ and the reason is recorded: the old form was *"unreachable, because the migration's own appends always produce a delta"* |
| ⭐ **migration-owned records still distinguishable from an unauthorized writer** | ✅ **the test changes from *"did anything change?"* to *"did anything change that we did not declare?"* — detection is preserved, which is exactly what `RD-10` asked for** |
| ⛔ freeze NOT redefined as *"ignore all writes"* | ✅ stated explicitly, and the honest bound is kept: *"what was lost was EVIDENTIAL CLARITY, not integrity"* |
| `OPEN-M2` further constrained, not decided | ✅ · no phase reordered ✅ |

⭐ **This remedy is complete, correctly bounded, and lands the obligation on the actor who owns it (PO/ARB writes the declaration).** Two residuals, recorded not blocking:

| | Residual |
|---|---|
| ⚠️ **`RD-10·r1`** | **detection granularity is the declared record.** An append **into a declared record of this work item** by an unauthorized actor is inside the declared set and therefore masked. **Narrower than the original defect, not a return of it.** ⇒ have the reconciliation **name each delta transition**, so an undeclared transition inside a declared file is still caught |
| ⚠️ **`RD-10·r2`** | §4.0's illustrative list is the **happy path** — registration · acceptance · the declaration · reconciliation · switch-over · demotion · cleanup. **Exception-path records (a β quarantine record, an escalation, a rollback) are not named**, and criterion 11 makes an undeclared delta a violation. ⇒ the declaration should be **class-based** (*"records written by this lane for this work item"*), which §4.0's own *"record classes"* wording already permits |

---

# 7 · `DI-1` — section identifier integrity → ⭐ **CLOSED**

| Test | Finding |
|---|---|
| §4 identifiers unique | ✅ **verified mechanically:** `4 · 4.0 · 4.1 · 4.2 · 4.3 · 4.4 · 4.5 · 4.6`, **each exactly once** (lines 348, 384, 432, 438, 460, 478, 526, 558). ⛔ **The `## 4.1`×2 / `## 4.2`×2 collisions are gone** |
| ordering monotonic | ✅ strictly increasing in document order — `RC-7b`'s authoritative-order rule satisfied |
| every live `§4.x` reference resolves to exactly one section | ✅ **all 37 current references walked.** The two reused numbers are the risk and both are correct: `§4.1` → Phase-3 byte preservation (§4.5 placement, §6.3 canonicalization ×2), `§4.2` → Phase-7 removal-is-not-deletion (§4.4's *"justification for Phase 7"*) |
| ⭐ the AMD4 additions renumbered **without breaking historical citations** | ✅ **and the count is exact: AMD4 contained precisely 21 `§4.x` references** *(7 + 7 + 3 + 4)* — the plan's claim of *"all 21 live references repointed and audited"* **verifies** |
| original historical numbers intact where required | ✅ `4.1` and `4.2` keep their numbers **with the reason stated** — renumbering them would invalidate citations in two delivered reviews. ⭐ **The right trade, and the map in §0.5.2 makes both directions resolvable** |
| the AMD4 **summary** references corrected | ✅ `4.1→4.4 · 4.2→4.3 · 4.3→4.5 · 4.4→4.6`, **labelled as an AMD5 `DI-1` correction that changed no AMD4 disposition or finding** — the second-artifact propagation is repaired |
| no current cross-reference points to superseded numbering | ✅ none found |
| ⛔ amendment history deleted to remove duplication | ⛔ **none** — nothing was renamed to hide a change |

⚠️ **Residual `DI-1·r`, cosmetic but load-bearing for §8:** numbering is monotonic while **phase order is not** — §4.1 = Phase 3, §4.2 = Phase 7, §4.3 = Phase 5, §4.4 = Phase 7 again. **Phase 7's rules are now dispersed across §4.2, §4.4 and §8**, which is how §8's stale disposition row (`RD-3·c`) survived this amendment. ⇒ a *"Phase 7 is specified in §4.2, §4.4 and §8"* pointer in §4.2 would cost one line.

---

# 8 · `DI-2` — Phase-2b reference integrity → ⭐ **CLOSED**

**Every current occurrence of *"Phase 2b"* audited** (`grep -n "Phase 2b\|2b-pin\|2c-commit"`):

| Site | State |
|---|---|
| **§5** manifest-commit sentence | ✅ **`2c-commit`**, with *"superseded: AMD3/AMD4 said Phase 2b — impossible after `RC-1`'s split"* labelled |
| **§10 criterion 1** *(the acceptance object)* | ✅ **`2c-commit`**, superseded wording labelled — **the material half of `DI-2` is repaired** |
| §10 criterion 5 | ✅ shorthand normalised to **`2b-pin`** |
| §12 | ✅ *"the pin is REQUIRED at `2b-pin` and is NOT performed"* — corrected beyond what `DI-2` asked |
| **§4 phase dependency table** | ✅ current throughout: rows 4/5/6/7 use `2b-pin` / `2c-commit`; row 5's precondition explicitly notes *"no longer 2b, which could not complete before Phase 3"* |
| **§11.1 prerequisite table** | ✅ no `2b` reference |
| §0.3 row 7 · §0.5.1 · §4.5 | ✅ **labelled history** — §0.3 is *"What AMD3 changes"*, §0.5.1 quotes the defect, §4.5 quotes AMD4's lifetime wording. ⛔ **Not defects**, and consistent with the ruling `870305e0` already made on §0.3 |

⭐ **Current semantics are unambiguous everywhere: `2b-pin` pins only; `2c-commit` commits manifest + copy.** ⚠️ **`RD-9` is still open and untouched** — `2c-commit` still does not name `.gitattributes`, which criterion 5's future-verifier claim needs. **Out of this review's six; recorded so it is not lost.**

---

# 9 · `DI-3` — dependency enumeration → 🟡 **ADDRESSED BUT NOT CLOSED**

**The finding, as `870305e0` stated it:** §11.1's heading asserted *"there are SIX"* over an **eight-row** table, and no grouping of the rows yields six.

## What is closed

✅ **The count is reconciled TO THE ROWS. Independently counted: the table has exactly 8 rows, and the grouping sums to 8** — Governance 2 *(registration · `INFO-2`)* + PO/ARB 4 *(Phase-0 · Phase-4b · `OPEN-M5` · acceptance)* + Architecture 1 + one grouped open-questions row.
✅ **No dependency invented to reach a number. No prerequisite missing. No ownership moved** — every actor cell matches AMD4's, and Governance remains the sole registration writer.

## 🔴 `DI-3·a` — the new *"3 GATE EXECUTION"* claim contradicts the same table's **Gates** column

```
grouping block says:  3 gate execution  =  registration · Phase-0 · Phase-4b
the Gates column says:  registration          → "acceptance"        (not execution)
                        Phase 0               → "Phase 1"           ✅
                        Phase 4b              → "Phase 5"           ✅
                        RC/RD/DI remediation  → "Phases 3 · 4b · 7"  ← gates execution, and is NOT in the named set
```

⛔ **The named set substitutes registration for remediation.** Under any reading of *"gates execution"*, the remediation row — whose Gates cell names three execution phases — belongs in it, and registration's own cell says it gates *acceptance*. ⭐ **This is the enumeration-versus-content mismatch `DI-3` was raised for, reproduced one level down inside the correction that fixed it** — the third occurrence of this shape in the chain (`RC-7a` → `DI-3` → here).

## 🔴 `DI-3·b` — two rows were **not updated for AMD5**, while the block above them was

| Row | Defect |
|---|---|
| *"**AMD3 + AMD4** registration (`OPEN-M6`)"* · status *"no `-AMD3` grant exists, and none for AMD4"* | ⛔ **omits AMD5**, contradicting §0.5 and the plan's own status line, which both say `OPEN-M6` **now spans AMD3, AMD4 and AMD5** |
| *"**`RC-1`…`RC-11`** technical remediation … ✅ **addressed by AMD4** — ⛔ NOT self-closed (§0.4)"* | ⛔ **stale in two ways:** the grouping block above calls this row *"`RC`/`RD`/`DI` correction (this act)"*, and the current state is **`RC-1`…`RC-11` CLOSED by the independent AMD4 review** with **`RD`/`DI` ADDRESSED by AMD5** |

⇒ **the heading's primary claim (eight rows, grouped by kind) agrees with the table; the two claims AMD5 added on top of it do not.** Mechanical to repair; no decision involved.

---

# 10 · Regression check — `RC-1` … `RC-11`

⛔ **`RC-1`…`RC-11` are NOT re-opened.** Checked only where AMD5 could reach them.

| | Finding |
|---|---|
| **`RC-1`** phase ordering | ✅ **intact.** Sequence unchanged — `0 · 1 · 2 · 2b-pin · 3 · 2c-commit · 4 · 4b · 5 · 6 · 7`; all 11 preconditions re-walked and **none points forward**; rows still numbered in execution order |
| **`RC-4`** final re-hash gates removal | ✅ **the gate itself is intact** — §4 row 11, §4.4's precondition and criterion 12 all hold. ⚠️ **`RC-4`'s own §8 row is now STALE** relative to §4.4 (`RD-3·c`) — the gate did not regress; its rollback statement did |
| **`RC-5`** writer-switch transfer | ✅ intact — §4.3 substantively unchanged; the transfer instant is still step 3. ⚠️ **the block's step list is now incomplete** (`RD-7·a`) |
| **`RC-6`** marker model coherent | ✅ **improved** — the lifecycle is now complete and the *"two markers"* row states the retraction. ⚠️ **§4 row 3 still says the marker holds *"for the whole 3→5 window"***, though it is written at Phase 2 and retracted at step 5; AMD5's disposition table claims row 3 was corrected, but its only change was a section-number repoint |
| **`RC-8`** byte preservation | ✅ intact — §4.6 unchanged; independently re-verified: `.gitattributes` is still `* text=auto`, **0 of 18 records contain `CR`**, no pin applied |
| **`RC-10`** refusal before `mkdir` | ✅ intact — §3.1 untouched, still ordered before `saveRecord`, still recorded and not implemented |
| **`RC-11`** writer wording | ✅ intact — *"ONE WRITER OF GOVERNANCE EVIDENCE"*, superseded heading still labelled, and **neither forbidden restoration is present** |
| **`RC-2` · `RC-3` · `RC-5b` · `RC-7a/b` · `RC-9`** | ✅ untouched by `7d3abc59` beyond section-number repoints |

⭐ **No `RC` item is materially regressed by AMD5.**

---

# 11 · Cross-cutting state-machine review

```
0 → 1 → 2 → 2b-pin → 3 → 2c-commit → 4 → 4b → 5 → 6 → 7
```

| Property | Finding |
|---|---|
| no precondition points forward | ✅ **all 11 verified** against row order |
| marker state matches authority state | 🟡 **at every point EXCEPT steps 3→5**, where the authoritative store is still labelled `NON-AUTHORITATIVE` (`RD-7·b`). Bounded, unrecorded, denied |
| no post-demotion write is importable | ✅ **the prohibition is unambiguous** (β). 🟡 **but the β branch has no terminating condition and no quarantine location** (`RD-3·a/b`) |
| migration-owned writes are identifiable | ✅ **yes, at declared-record granularity** (§4.0), with `RD-10·r1`'s masking residual |
| all acceptance criteria reference current phase names | 🟡 **1, 5, 11, 12, 13, 15 ✅. Criterion 14 cites *"Phase 5 step 5"*, which the mandated-order block does not define** |
| all section references resolve uniquely | ✅ all 37 `§4.x` references |
| the irreversible act stays guarded | ✅ **unconditional STOP before any disposition branch; nothing is destroyed on mismatch.** The final-re-hash-to-removal interval is correctly recorded as irreducible without a lock (`Increment 2` unauthorized) |

**Interruption semantics, re-derived:**

| Interruption | Handling |
|---|---|
| `2b-pin` → 3 · 3 → `2c-commit` · `2c` → 4 | ✅ unchanged and coherent; the staging marker is what makes the committed-but-unverified state honest |
| Phase 5 step 1→2 · 2→3 | ✅ stale-read window, re-hash-bounded |
| Phase 5 step **3→4** | ⚠️ `RD-2` — unmarked stale runtime. **Recorded by the AMD4 review, still open, not AMD5's to fix** |
| Phase 5 step **4→5** | 🔴 **NEW with AMD5 and unrecorded** — authoritative store labelled `NON-AUTHORITATIVE`; §8 has no row for it (`RD-7·b`) |
| Phase 7 mismatch, cause **α** | ✅ reconcile under §6 — in scope, and normally resolves as CASE B by `seq` collision |
| Phase 7 mismatch, cause **β** | 🟡 quarantine + escalate ✅, **then undefined** (`RD-3·a/b`) |

---

# 12 · Document integrity

⛔ **This review does NOT record *"no material document-integrity defect found."*** **Four defects; two are material because they land on the acceptance object and on the operator-facing rollback section.**

| | Defect |
|---|---|
| 🔴 **`DI-4`** | ⭐ **§8's Phase-7 row states the SUPERSEDED single-branch disposition as CURRENT, unlabelled** — *"Removal is refused until the difference is reconciled and re-verified"* — the exact model `RD-3` refuted. **Two competing current definitions of the Phase-7 mismatch disposition (§4.4 and §8), which §0.4.4 forbids** |
| 🔴 **`DI-5`** | ⭐ **Two current, non-identical enumerations of Phase 5's internal order** — §4.3's *"mandated"* block ends at step 4; §4.5 and §4 row 9 add step 5; **acceptance criterion 14 cites a step number the mandated block does not define** |
| ⚠️ **`DI-6`** | **§11.1's *"3 GATE EXECUTION"* set contradicts its own Gates column, and two rows are stale w.r.t. AMD5** (`DI-3·a/b`) |
| ⚠️ **`DI-7`** | ⭐ **Glyph collision: `CASE B` (§6 — same sequence, different content) vs `CASE β` (§4.4 — post-demotion write).** §8's trigger *"a CASE B conflict"* is now ambiguous to an operator. **Rename one pair** |
| ✅ | **Duplicate current sections / identifiers:** ⛔ **none** — every heading `0 … 12` unique; §4 monotonic |
| ✅ | **Stale phase references:** ⛔ **none** — `DI-2` closed; every current site uses `2b-pin`/`2c-commit` |
| ✅ | **Misleading counts:** the *"SIX"* is repaired and the *"21 references"* claim verifies; ⚠️ the *"3 gate execution"* claim does not (`DI-6`) |
| ✅ | **Unlabelled superseded text:** one instance — §8's row (`DI-4`). Everything else is labelled: §1.3's heading · §3 row 4 · §4.6's withdrawn form · §5 · §6.3's withdrawn invariant · §10's superseded criteria 1/2/6/7/11 · §0.5.2's map |
| ✅ | **Tool/edit transcript residue:** ⛔ **none.** Two trivia only: a **duplicated `---`** before §0.5 (an artifact of the insertion), and §0.4/§0.5 as `#` where §0.1–0.3 are `##` |
| ⚠️ | §827's AMD5 traceability cites *"`RD-10` (§4.1 freeze self-consistency)"* — that is the **AMD4 review's** §4.1, in a plan whose own §4.1 is Phase-3 byte preservation. **Contextually clear, ambiguous in isolation** |

---

# 13 · Open questions — preserved, none decided

| | Status | Note |
|---|---|---|
| **`OPEN-M1`** `.gitignore` | ⏳ **OPEN** | untouched — independently confirmed `.gitignore` unmodified by `7d3abc59` |
| **`OPEN-M2`** Phase 1/4 evidence vs Phase 2 target | ⏳ **OPEN** | ⚠️ **further constrained by `RD-10`; NOT decided.** No phase reordered |
| ✅ **`OPEN-M3`** | **CLOSED** (Option A) | ⛔ **not reopened** |
| **`OPEN-M4`** unbounded readers | ⏳ **OPEN** | `RD-5` still inside its envelope |
| **`OPEN-M5`** enforcing form / `AST-016` / `T-14` | ⏳ **OPEN — ⛔ NOT decided here.** ⚠️ **`RD-6` stands unchanged: its scope includes `T-14`'s contract, which AMD5 does not address and was not asked to** | a PO/ARB contract question |
| **`OPEN-M6`** AMD3 + AMD4 + **AMD5** registration | ⏳ **OPEN** | ✅ independently verified: **no `-AMD3`, `-AMD4` or `-AMD5` grant exists.** ⚠️ §11.1's row still says *"AMD3 + AMD4"* (`DI-3·b`) |
| **`INFO-2`** lane-registration provenance | ⏳ **OPEN** | a Governance matter |
| **`B′` · `R-CONFLICT` · `INV-ORDER` · placement governance · Option D** | **FIXED** | ⛔ **not reopened; AMD5 changes none of them — independently confirmed against the diff** |

⛔ **Nothing here infers acceptance from the existence of AMD3, AMD4 or AMD5.**

---

# 14 · DDD / knowledge-engineering check

| Distinction | Held? |
|---|---|
| **Execution state ≠ governance evidence** | ✅ §1.6 unchanged; markers are execution signals, records are evidence, and `RD-7`'s remedy keeps marker management out of the record bytes |
| **Evidence ≠ proof** | ✅ **strengthened by `RD-10`:** the freeze's claim moves from *"nothing changed"* (unprovable while the migration writes) to *"nothing changed that we did not declare"* (provable), **and the loss of evidential clarity is named rather than assumed away** |
| **Evidence identity ≠ authority** | ✅ untouched — identity still reported, never validated |
| **Authority ≠ storage location** | ✅ **this is `RD-3`'s whole substance and it is correct:** post-demotion bytes are *"NOT authoritative evidence"* **because of when they were written, not where** |
| **Derived state ≠ authoritative state** | ✅ *"a marker still reports state; it never confers or removes authority — §4.3's writer switch does that"* — ⭐ **the sharpest sentence AMD5 adds.** ⚠️ criterion 14 makes the marker an acceptance object, which is legitimate: it accepts the **visible trace**, not the authority |
| **Historical ≠ canonical future location** | ✅ Phase 7 is still removal of a redundant verified copy — ⚠️ **and `RD-3·b` is the one place that could stop being true, if a quarantined record is removed with the store** |

⭐ **No new bounded context, no ledger, no second authority boundary, no second owner, no decision changed. `Increment 2` is not proposed.**

---

# 15 · Residual risks

| | Risk | Class | Gate |
|---|---|---|---|
| 🔴 **`RD-3·a`** | **`CASE β` has no terminating condition** — criteria 12 and 15 are jointly unsatisfiable in it, and *"for either case … only then may removal proceed"* promises a path that is undefined | design gap | **Phase 7** |
| 🔴 **`RD-3·b`** | **quarantine has no location, and Phase 7 removes the store the bytes live in** ⇒ `R-CONFLICT`'s reconstruction limb is not yet established through Phase 7 | design gap | **Phase 7** |
| 🔴 **`RD-3·c`/`DI-4`** | **§8 still states the refuted single-branch disposition as current, unlabelled** | integrity · material | **acceptance** |
| 🔴 **`RD-7·a`/`DI-5`** | **§4.3's mandated order ends at step 4** while criterion 14 accepts on *"step 5"* | specification · material | **acceptance · Phase 5** |
| 🔴 **`RD-7·b`** | **the *"at NO point"* claim is false for steps 3→5**; the window is real, irreducible, and unrecorded | overclaim | **Phase 5** |
| ⚠️ **`DI-6`** | §11.1's *"3 gate execution"* set contradicts its Gates column; registration and remediation rows stale w.r.t. AMD5 | integrity | acceptance |
| ⚠️ **`DI-7`** | **`CASE B` vs `CASE β`** glyph collision, live in §8's trigger list | integrity | acceptance |
| ⚠️ **`RD-10·r1`** | freeze detection is at **declared-record** granularity; an undeclared append inside a declared record is masked | accepted residual | Phase 0 declaration |
| ⚠️ **`RD-10·r2`** | §4.0's declared list enumerates the **happy path**; exception-path records could read as violations | specification | Phase 0 declaration |
| ⚠️ **carried from the AMD4 review, unaddressed and not AMD5's commission** | **`RD-1`** *(no slot for reconciliation in Phase 5's order — same edit as `RD-7·a`)* · **`RD-4`** *(the AUTHORITATIVE `INV-R1` branch has no pinned coverage — Phase 4b)* · **`RD-9`** *(`2c-commit` does not name `.gitattributes`)* · **`RD-6`** *(`OPEN-M5` scope includes `T-14`)* · **`RD-2`**, **`RD-5`** *(accepted residuals)* | open | as recorded by `870305e0` |
| ⚠️ **pre-existing, not AMD5's** | read-modify-write without lock/lease/CAS (`Increment 2`) · no `fsync` · unbounded ad-hoc readers (`OPEN-M4`) | out of scope | — |

---

# 16 · Verdict

| Residual | Verdict |
|---|---|
| 🔴 **`RD-7`** staging marker retraction | 🟡 **ADDRESSED BUT NOT CLOSED** — the durable versioned defect is genuinely fixed; the act is missing from the **mandated** order that criterion 14 numbers against, and the residual step 3→5 window is **denied rather than recorded** |
| 🔴 **`RD-3`** Phase-7 mismatch disposition | 🟡 **ADDRESSED BUT NOT CLOSED** — the split, the tie-break and the import prohibition are **correct and sufficient to stop the promotion**; **β has no terminating condition, no quarantine location, and §8 still carries the refuted model** |
| 🔴 **`RD-10`** freeze semantics | ⭐ **CLOSED** — the partition is complete, detection is preserved, the obligation lands on PO/ARB, and the bound is stated honestly. Two residuals recorded |
| 🔴 **`DI-1`** section identifiers | ⭐ **CLOSED** — collisions gone, order monotonic, all **21** pre-existing references audited *(count verified independently)*, originals preserved with reasons, second-artifact propagation repaired |
| ⚠️ **`DI-2`** Phase-2b references | ⭐ **CLOSED** — both live sites corrected with labels; the dependency table and every acceptance criterion use current phase names |
| ⚠️ **`DI-3`** dependency enumeration | 🟡 **ADDRESSED BUT NOT CLOSED** — the eight-row count **is** reconciled to the rows; the *"3 gate execution"* claim AMD5 added contradicts the same table's Gates column, and two rows are stale w.r.t. AMD5 |

## 🟡 **OVERALL: PASS WITH DESIGN CLARIFICATIONS**

⛔ **NOT BLOCKED, and the four `BLOCKED` conditions are tested one by one:**

| Condition | Finding |
|---|---|
| **a remedy is technically unsound** | ⛔ **no.** All six move in the right direction; `RD-10`/`DI-1`/`DI-2` are complete; `RD-7` and `RD-3` are **incomplete, not wrong** — and every premise I re-derived from the repository held |
| **a required execution gate is impossible** | ⛔ **no** for the normal path. ⚠️ **`RD-3·a` makes Phase 7's gate unsatisfiable after a `CASE β` event** — but it fails in the **safe** direction (removal refused, nothing destroyed), so it is a clarification, not an impossibility |
| **a fixed architectural decision is violated** | ⛔ **no.** `R-CONFLICT` §6.1 byte-identical · `B′` intact · `OPEN-M3` Option A untouched · `INV-ORDER` holds · placement governance and Option D unchanged · no phase reordered · no second authority boundary. **Quarantine is compliant as argued — ⚠️ subject to `RD-3·b` at Phase 7** |
| **migration remains unsafe under the amended design** | ⛔ **no.** The irreversible act is still gated by an unconditional STOP, `RD-3` removes the one branch that would have imported demoted bytes, and `RD-7` removes a durable false label. **AMD5 makes the design safer than AMD4** |

⚠️ **But five items must be repaired before Phase 7 and before acceptance:** `RD-3·a` (β's terminating condition) · `RD-3·b` (quarantine location, and that Phase 7 cannot remove a store holding an undisposed quarantined record) · `RD-3·c`/`DI-4` (§8's stale row) · `RD-7·a`/`DI-5` (step 5 into the mandated order — one edit also closes `RD-1`) · `RD-7·b` (restate the claim and record the step 3→5 window). ⭐ **Each is a wording, sequencing or enumeration repair by the plan's owner inside the current envelope. None requires a decision, and none reopens anything.**

⛔ **`PHASE 3 MUST NOT BEGIN`** — and not because of this verdict: `OPEN-M6` registration, the Phase-0 freeze declaration *(now also carrying `RD-10`'s expected-delta list)*, the Phase-4b authorization *(and `RD-4`'s missing coverage)* and plan acceptance are all outstanding, and every one belongs to another actor.

---

# 17 · Explicit non-decisions and limitations

⛔ **This review did NOT:** modify the migration plan · register AMD5, AMD4 or AMD3 · accept AMD5 · call anything adopted · execute or begin any migration act · modify runtime code · touch `.gitignore` or `.gitattributes` · decide `OPEN-M5` or `OPEN-M6` · reopen `B′`, `R-CONFLICT`, `OPEN-M3` or `RC-1`…`RC-11` · reorder any phase · write or propose any remedy text.
⚠️ **Limitations, stated rather than implied:** the reviewer's identity is **self-declared and not attestable** (`INV-ATTR-2`) · findings rest on the artifact plus the repository at `7d3abc59`, not on any execution · the corpus has **no git history** (re-verified: `git log` on `.claude/runtime/workflow/` is empty; `git check-ignore` → `.gitignore:32`), so its content is unattestable by anyone — the defect `B′` exists to close · I did **not** run the contract suites and did not re-derive `RC-2`/`RC-3`'s test evidence, because `RC-1`…`RC-11` are closed background here · `RD-3·a`'s unsatisfiability is derived from criteria 12 and 15 as written, not observed in execution.

**Next actors, in order:**

```
this review
      ↓
the plan's owner repairs RD-3·a · RD-3·b · DI-4 · DI-5 · RD-7·b   (no decision required)
      ↓   ⭐ the §0.4.1 bound recurs once more — AMD6's remedies would again be unreviewed
Governance registers AMD3 + AMD4 + AMD5                            (OPEN-M6)
      ↓
PO/ARB — acceptance · OPEN-M5 if it chooses (⚠️ RD-6 enlarges it)
      ↓
PO/ARB — the PHASE-0 FREEZE DECLARATION, now carrying the EXPECTED-DELTA LIST (RD-10)
         the PHASE-4b AUTHORIZATION (⚠️ RD-4)
      ↓
Migration execution — beginning at PHASE 0, never at Phase 3
```

**Traceability:** AMD5 at **`7d3abc59`** *(plan 706 → 833 lines)* · AMD4 at `0a2fa71d` · AMD3 at `bb1708b7` · technical review `a282d14b` · accepted design `ae451db9` · **the independent AMD4 review by `claude-code-session:870305e0`** — `RD-7` (its §3 `RC-6`), `RD-3` (its §3 `RC-4`), `RD-10` (its §4.1), `DI-1`/`DI-2`/`DI-3` (its §6), §8 residual table, §9 verdict · **the independent AMD3 review by `claude-code-session:9c908e70`** (`RC-1`…`RC-11`, closed, not reopened) · **primary evidence re-derived here, not quoted:** `git diff 0a2fa71d 7d3abc59` (458 lines, 6 files; `.gitignore` and `.gitattributes` absent from the change set) · plan headings enumerated mechanically — §4 = `4 · 4.0 · 4.1 · 4.2 · 4.3 · 4.4 · 4.5 · 4.6`, each once, monotonic · **37 current `§4.x` references walked; AMD4 contained exactly 21** · §11.1 = **8 rows**, grouping sums to 8 · `.claude/scripts/session-resolve.php:130` (`glob('*.json')`) · `.gitattributes` = `* text=auto`, **no pin** · **re-measured 2026-08-20: 18 records · 216 transitions · 110 grants · 0 records contain `CR` · no `-AMD3`/`-AMD4`/`-AMD5` grant · `git log` on the corpus → empty · no durable target exists** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2`.

**INDEPENDENT REVIEW DELIVERED · STOPPING.** ⛔ **THE MIGRATION IS NOT EXECUTED, NOT AUTHORIZED, AND MUST NOT BEGIN.**
