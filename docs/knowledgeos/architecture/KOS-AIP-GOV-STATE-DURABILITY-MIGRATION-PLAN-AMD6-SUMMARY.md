# `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN` — **AMD6 amendment summary**

**Status: 🟡 PROPOSED · ADDRESSED — ⛔ NOT CLOSED, NOT REGISTERED, NOT ACCEPTED.**
> ## ⛔ **MIGRATION NOT EXECUTED.** **Nothing was moved, copied, pinned, switched, marked, demoted, quarantined or removed. No quarantine store was created. No `.gitignore` or `.gitattributes` change. No runtime code change. No authority record modified. No grant registered. No aggregate created.**

**Canonical governed aggregate (`C-9`, verified before writing):** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · workflow `architecture-adr`. ⛔ **`KOS-AIP-GOV-STATE-DURABILITY` is the TRACK LABEL only and was not used as the aggregate key.**
**Amended artifact:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` *(AMD5 at `7d3abc59`, 833 lines → AMD6)*
**Input, consumed and not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD5-ARCHITECTURE-REVIEW.md` — the **INDEPENDENT** AMD5 review by `claude-code-session:ccf6c9c7`, verdict **`PASS WITH DESIGN CLARIFICATIONS`**, plus its **§18 commission note**.
**Commission, four REGISTERED grants:** `G-…-AMD6` *(`C-1`…`C-5` + the binding `C-4` refinement, flags `AA`/`AB`)* · `G-…-AMD6-C6-C8` · `G-…-AMD6-C9-C11` · `G-…-AMD6-C12`.
**Producing process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:bc1b47ef` — **author of AMD3, AMD4, AMD5 and the original technical review.** ⭐ **`C-12` restored this process's AUTHORING eligibility.** ⛔ **`C-12`, binding: it is BARRED from REVIEWING and from ACCEPTING AMD6.** **`R-34`/`P-2`.**
**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**.

---

# 1 · Scope

**AMD6 addresses ONLY the registered residuals and commission corrections.** ⛔ **No architecture redesigned · no fixed decision reopened · no phase reordered · no new authority boundary, owner, ledger or bounded context · no open question decided.**

| | |
|---|---|
| **Input verdict** | 🟡 `PASS WITH DESIGN CLARIFICATIONS` — `RD-10`/`DI-1`/`DI-2` **CLOSED** by the independent AMD5 review |
| **Residuals consumed** | `DI-5`/`RD-1` · `RD-7·b` · `RD-3·a` · `RD-3·b` · `DI-4` · `DI-6` · `DI-7` |
| **Commission corrections consumed** | `C-1`…`C-5` *(with the binding `C-4` refinement)* · `C-6`…`C-8` · `C-9`…`C-11` · `C-12` · flags `AA` · `AB` |
| **AMD6 disposition** | ⭐ **ALL ADDRESSED** |
| ⛔ **Self-closure** | **NONE** — §6 |
| **Gate state** | ⛔ **Phase 3 must not begin** · ⭐ **and Phase 7 now has a state in which it must not COMPLETE** |

---

# 2 · Disposition of every commissioned residual

## `DI-5` / `RD-1` — two competing Phase-5 enumerations → **ADDRESSED** *(§4.3 · §4 row 9 · §4.7 · §10 criteria 13 · 14 · 16)*

**Defect:** §4.3's block labelled *"mandated"* ended at **step 4**, while §4.5 and §4 row 9 carried a **step 5** — and **acceptance criterion 14 accepted on a step number the mandated block did not define.** `RD-1`'s reconciliation act had no slot at all.

**Correction — ONE canonical enumeration, in the block that is normative:**

```
1    RE-HASH                    source vs the frozen manifest
1b   RECONCILE / DISPOSE DELTA  three outcomes; the last point at which abandonment is FREE
2    READERS                    P-2, P-4
3    ⭐ THE WRITER               P-1        ← THE AUTHORITY-TRANSFER INSTANT
4    RUNTIME DEMOTION MARKER    runtime copy = DEMOTED
5    STAGING MARKER RETRACTION  the now-authoritative store loses NON-AUTHORITATIVE
```

⭐ **`1b` has THREE outcomes — no delta *(continue)* · delta ⊆ the declared list *(reconcile, continue)* · delta ⊄ it *(🔴 freeze violation ⇒ STOP · record · escalate · ⛔ NO authority switch)*.** ⭐ **The same order is reflected in the phase table, §4.3, the acceptance criteria and the operator instructions, and every other site now REFERS to the block instead of restating it.** **AMD4's four-step form is retained as labelled history.**

## `RD-7·b` — the overclaim → **ADDRESSED** *(§4.5 · §8)*

**Defect:** §4.5 asserted *"at NO point is the authoritative store labelled `NON-AUTHORITATIVE`"*. 🔴 **False: authority transfers at step 3 and the retraction is at step 5, so the store is mis-labelled throughout steps 3→4→5.** The old window box enumerated *"before the switch"* and *"after step 5"* and **omitted the interval at issue.**

**Correction — the claim is WITHDRAWN and restated to the strength of the evidence:** *the exposure falls from a **durable, versioned** false label to the **step 3→5 interval**; it is **irreducible without atomicity**; it is **RECORDED, not closed**; and §8 gains a row for an interruption inside it, whose safe direction is FORWARD.* ⭐ **The marker is observational — §4.3's writer switch determines authority — and no evidence byte is touched in any interval.** ⛔ **The defect was the DENIAL, not the window.**

## `RD-3·a` — `POST-DEMOTION` had no terminating condition → **ADDRESSED** *(§4.4 · §10 criterion 20 · §4.7 `P7·3`)*

**Defect:** criteria 12 and 15 are **jointly unsatisfiable** after a post-demotion event, yet §4.4 closed with *"then, for **either case**: re-verify … only then may removal proceed"* — **a promised path with no pass condition**, leaving three readings the plan did not choose between.

**Correction — the *"either case"* rule is WITHDRAWN and replaced per branch:**

| Branch | Terminating condition |
|---|---|
| ✅ **`PRE-SWITCH`** | reconcile under §6 → **re-verify** (Phase 4 semantics, all-or-nothing) → removal may proceed |
| 🔴 **`POST-DEMOTION`** | ⛔ **DOES NOT GATE ON RE-VERIFICATION AT ALL.** **Phase 7 is SUSPENDED · the demoted store is RETAINED ENTIRE · only a GOVERNED DISPOSITION releases it (`OPEN-M7`)** |

⭐ **All three unsafe readings are answered:** the comparison object **cannot** absorb the bytes *(it is fixed and recorded at slot 3)* · the branch is an **explicit suspended state** rather than a silently failing check · **removing the store to make the check pass is forbidden in terms.**

## `RD-3·b` / `C-2` / `C-3` / flag `AA` — quarantine had no location → **ADDRESSED** *(§4.4 · §4.5 · §4 row 11 · §10 criterion 19)*

**Defect:** the bytes were *"PRESERVED"* with **no destination named**, and Phase 1's *"left in place"* precedent means *inside the runtime records* — **exactly what Phase 7 removes.**

| Property | Rule |
|---|---|
| **destination** | a governed **QUARANTINE STORE** inside the governed evidence boundary and ⛔ **OUTSIDE every directory scanned by the `*.json` record predicate** *(`glob($recordDir . '/*.json')`, `session-resolve.php:130`)* — because **a quarantined record IS a `*.json` file** |
| **path** | ⛔ **not chosen here** — resolved at execution time through **existing placement governance**, exactly as §2 requires |
| **byte identity** | **original filename · original bytes · original hash.** ⛔ **NOT renamed · NOT re-encoded · NOT rewritten** — renaming would break the manifest linkage that **proves** preservation *(flag `AA`)* |
| **mechanism** | **byte-preserving PLACEMENT** into the quarantine store, with the demoted source store **RETAINED ENTIRE and UNMODIFIED.** ⛔ **A MOVE is refused** — it would alter the demoted store and break the recorded enumeration |
| **granularity** | ⭐ **STORE-LEVEL** (`C-2`). ⛔ **No partial-record removal semantics** |
| **marker** | a **THIRD** marker, **`QUARANTINED`** — directory-level, out-of-band, ⛔ never `*.json`, ⛔ **distinct from `NON-AUTHORITATIVE` (staging) and `DEMOTED` (runtime)** in name, subject and lifecycle |
| ⛔ **not authority** | a quarantined record is **not** authoritative evidence and is **never** promoted by being stored somewhere governed — `B′`'s own lesson applied to the new store |

## `DI-4` — §8 carried the refuted model as CURRENT → **ADDRESSED** *(§8)*

**Defect:** §8's Phase-7 row still read *"removal is refused until the difference is reconciled and re-verified"* — **the one-branch-for-both model `RD-3` refuted, standing unlabelled as current text in the section a Phase-7 operator consults.** Two competing current definitions, which §0.4.4 forbids.

**Correction:** §8's row now states **§4.4's split in the same terms** — `PRE-SWITCH` = stop-and-reconcile; `POST-DEMOTION` = **suspension** with no re-verification gate — **and the superseded single-branch wording is retained and LABELLED.** §8 also gains the steps-3→5 interruption row and the AMD6 triggers. ⭐ **§4.2 now carries the pointer `DI-1·r` asked for: Phase 7 is specified in §4.2, §4.4, §4.7 and §8.**

## `DI-6` — §11.1's gate claim contradicted its own column → **ADDRESSED** *(§11.1)*

**Defect:** AMD5's *"3 GATE EXECUTION (registration · Phase-0 · Phase-4b)"* **substituted registration — whose own Gates cell says it gates *acceptance* — for the remediation row, whose cell names three execution phases.** Two rows were also stale w.r.t. AMD5.

**Correction — NINE rows, and the gate claim is READ OFF the Gates column:** **three rows gate an execution phase unconditionally** *(Phase-0 declaration · Phase-4b authorization · Architecture remediation)* · ⭐ **one gates Phase 7 conditionally (`OPEN-M7`)** · **registration gates ACCEPTANCE** · **acceptance gates execution as a whole** · three rows gate nothing. **The registration row reads AMD3 … AMD6; the remediation row reads its true current state.** ⛔ **No dependency invented, no ownership moved.**

## `DI-7` / `C-1` / `C-6` — the glyph collision → **ADDRESSED** *(§0.6.2 · §4.4 · §4 row 11 · §8 · §10 criterion 15)*

⭐ **ONLY the newer family is renamed:** `CASE α` → **`PRE-SWITCH DISPOSITION`** · `CASE β` → **`POST-DEMOTION DISPOSITION`**. ⛔ **§6's `CASE A`/`CASE B` keep their names and their text.**
**Measured rather than assumed:** `CASE A` + `CASE B` = **23 occurrences**, cited across four delivered reviews and §10 criterion 10; `CASE α` + `CASE β` = **11 occurrences**, AMD5's own addition, cited nowhere outside the plan. ⭐ **The same rule `DI-1` used for numbers, applied to names.**
**`C-6` complied with:** the term is **`POST-DEMOTION`**. ⛔ **The malformed variant `C-6` forbids is not used and is not reproduced** — verified to occur in **no document under `docs/` or `.claude/`**, only inside the `C-6` grant itself, which is why `C-6` records that it *"corrects an unregistered chat draft, not the registered commission"*.

---

# 3 · ⭐⭐ `C-4` / `C-7` — the evidence-placement resolution

**The question Architecture had to answer, and which the commission deliberately did not answer for it:** *which Phase-5 records belong to the pre-switch source state, which to the post-switch authoritative state, and exactly where is each written?*

## 3.1 The derivation — nothing is invented

```
§3 row 1   an AUTHORITATIVELY resolved location lies inside the governed evidence boundary
§3 row 2   an explicit --dir override yields a NON-AUTHORITATIVE output location
slot 3     P-1's resolution changes here — the authority-transfer instant
     ⇒  before slot 3 the staging store CANNOT hold an authoritative record at all
     ⇒  after  slot 3 the demoted source CANNOT hold one either
```

⇒ **placement is FORCED by the resolver contract for every record except slot 3's own — and that one is the decision.**

## 3.2 The decision

| Record | Subject state | Destination |
|---|---|---|
| slot **1** re-hash · slot **1b** reconciliation or STOP · slot **2** reader switch | **PRE-SWITCH SOURCE** | **the source store** |
| ⭐ slot **3** the **`SWITCH-OVER RECORD`** | ⭐ **POST-SWITCH AUTHORITATIVE** | ⭐ **the authoritative store — its FIRST record** |
| slot **4** demotion-marker record · slot **5** retraction record · Phase 6 · Phase 7 | **POST-SWITCH AUTHORITATIVE** | **the authoritative store** |

⭐ **Why slot 3's evidence goes AFTER the switch is a TERMINATION argument, not a preference:** a pre-switch slot-3 record would land in the source **after** the copy had been reconciled to it ⇒ reconcile again ⇒ write again ⇒ ⛔ **non-terminating.** Writing it post-switch terminates in one step, because `P-1` has moved and the source receives nothing further.

## 3.3 What slot 3 does, and the object it produces

```
(i)   MEASURE the source, after slot 2's append has landed
(ii)  VERIFY it against  manifest + reconciled delta + the DECLARED pre-switch Phase-5 records
        → §4.0's SAME three outcomes; outcome C is a STOP BEFORE THE SWITCH
(iii) bring the durable copy to that state      (a MIGRATION MECHANICAL WRITE, not a governance append)
(iv)  ⭐ SWITCH P-1
(v)   ⭐ write the SWITCH-OVER RECORD into the authoritative store, CARRYING the enumeration
```

| `C-4`'s required property | Delivered by |
|---|---|
| switch evidence in the authoritative store, after the switch | the switch-over record is that store's **first** record |
| the Phase-7 re-hash is **not** self-detecting because of migration bookkeeping | the **`SOURCE FINAL-STATE ENUMERATION`** is Phase 7's comparison object ⇒ ⭐ **criterion 12's *"identical"* branch is REACHABLE** — `RD-10`'s degradation does **not** recur at the irreversible gate |
| the switch-over record remains a valid discriminator | ⭐ the boundary is **content-defined, not timestamp-defined** — which it must be, because only **2 of 216** transitions carry a time field |

⭐ **Two further consequences:** the enumeration is **fixed and recorded**, so it can never later **absorb** a disputed write *(`RD-3·a`'s worst reading, structurally excluded)*; and **two write classes are kept apart** — **governance appends** (`P-1`, authority follows the resolution) versus **migration mechanical writes** (the copy, the reconciliations), which confer nothing and are evidenced by appends that describe them.
✅ **§5 is honoured:** the pre-switch records are **carried across** by step (iii) ⇒ ⛔ **no migration evidence is left behind in runtime.**
⛔ **This is a decision proposal, NOT an implementation recipe** (`C-4` refinement, `C-7`): it fixes **subject-state → destination** and **the closing instant**, and leaves mechanism, layout and tooling to the execution act.

## 3.4 Residual windows — recorded, not denied

| Window | Status |
|---|---|
| ⚠️ **measure (i) → switch (iv)** | ⭐ **NEW with AMD6 and recorded here rather than discovered later.** A write inside it is genuinely `PRE-SWITCH` but falls outside the enumeration, so the tie-break classifies it `POST-DEMOTION` — **safe, imprecise, irreducible without atomicity** |
| ⚠️ **slot 3 → slot 4** | `RD-2`, carried from the AMD4 review, unchanged |
| ⚠️ **slot 3 → slot 5** | `RD-7·b`, restated honestly |
| ⚠️ **final re-hash → removal** | irreducible without a lock; `Increment 2` unauthorized |

---

# 4 · Quarantine and disposition semantics — `C-8`, unsoftened

**While ANY quarantine is UNDISPOSED:**

```
Phase 7                    remains BLOCKED            (no removal, of any store)
the affected store         remains PRESERVED ENTIRE
the quarantined bytes      remain PRESERVED, unrenamed, hash-verifiable
a governed DISPOSITION     is REQUIRED
⛔ B′'s TARGET END STATE   IS NOT REACHED — runtime does NOT become execution-only
⛔ the migration aggregate CANNOT LEGITIMATELY TRANSITION TO ITS COMPLETED STATE
```

⭐ **A single undisposed quarantine can block the migration's completion INDEFINITELY. That is the SAFE direction and AMD6 does not soften it.**
⭐ **The DDD reading, stated because the filesystem word hides it:** *a quarantine is a DOMAIN STATE with a business consequence, not a directory condition.* **The block is not a tooling limitation to engineer away — it is the aggregate refusing an invalid transition.**

## Disposition ownership — canonical discovery first (`ES-005.4`), then `OPEN-M7`

| | Finding |
|---|---|
| ✅ **the ACT CLASS exists** | §8: *"re-promoting a demoted source is a **governance act**, not a merge"* · §6.2: *"a resolution requiring a new governance decision is recorded separately"* ⇒ the same class, with the pair this plan already names — **PO/ARB decides · Governance registers** (`G-2`/`R5a`). ⛔ **AMD6 creates NO disposer and proposes NO new role** |
| 🔴 **the disposal PATH exists NOWHERE** | no admissible-outcome set, no discharge evidence, and **measured: the mechanism has no `DISPOSE` act** — `workflow-state.php`'s transition types are `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL` |

⇒ ⭐ **`OPEN-M7` is recorded (§11, §11.1) with both halves explicit:** *(a)* **confirm the disposer** — Architecture's reading is the existing pair **by extension of §8**, and ⛔ **Architecture does not decide it**; *(b)* **the admissible dispositions and the discharge evidence.** ⛔ **Governance said it does not choose between *"name the authority"* and *"record an OPEN item"*; AMD6 does the thing that invents nothing — it proposes the reading and records the question.**

---

# 5 · Plan-local four-layer trace — ⭐ **REQUIRED DELIVERED EVIDENCE**

> ⛔ **`C-12` is binding on BOTH halves: the trace is required, and IT DOES NOT DISCHARGE THE REVIEW GATE.** ⭐ **It is a self-check by the author — precisely what §0.4.1 says cannot close anything.**

| # | ACT | ENUMERATION | CRITERION | OPERATOR |
|---|---|---|---|---|
| 1 | step 1 re-hash | §4.3 slot 1 | 11 | `P5·1` |
| 2 | ⭐ step 1b reconcile / dispose, three outcomes | §4.3 slot 1b | **16** | `P5·1b` |
| 3 | step 2 reader switch | §4.3 slot 2 | 13 | `P5·2` |
| 4 | ⭐ step 3 close the enumeration, verify, switch `P-1` | §4.3 slot 3 | 13 · **17** | `P5·3` |
| 5 | ⭐ step 3 the `SWITCH-OVER RECORD`, authoritative store, first | §4.3 slot 3 + placement table | **17** | `P5·3` |
| 6 | ⭐ Phase-5 placement by subject-state | §4.3 placement table | **18** | `P5·ALL` |
| 7 | step 4 runtime `DEMOTED` marker | §4.3 slot 4 · §4.5 | 8 · 14 | `P5·4` |
| 8 | ⭐ step 5 retract `NON-AUTHORITATIVE` | §4.3 slot 5 · §4.5 | 14 | `P5·5` |
| 9 | ⭐ Phase 7 dispose by `PRE-SWITCH` / `POST-DEMOTION` | §4.4 split | 15 | `P7·1` |
| 10 | ⭐ Phase 7 quarantine mechanics | §4.4 · §4.5 | **19** | `P7·2` |
| 11 | ⭐ Phase 7 suspend · retain entire · no false pass | §4.4 · §4 row 11 | **20** | `P7·3` |

✅ **Every row resolves in all four columns.** ⛔ **That is the whole claim — it is not a claim that any of it is sound.**

---

# 6 · Self-closure, independence and status

| | |
|---|---|
| **AMD6 status** | 🟡 **PROPOSED · ADDRESSED** |
| ⛔ **Closed by AMD6** | **NOTHING.** ⛔ **An amendment may not close findings against itself** |
| **Findings' author** | `claude-code-session:ccf6c9c7` — **independent of this process** |
| **Remedies' author** | `claude-code-session:bc1b47ef` — **this process, which will NOT review them** |
| ⭐ **The §0.4.1 bound** | **recurs for the FOURTH time, and is stated rather than papered over: AMD6's remedies are unreviewed. `C-12` makes the authoring lawful; it does not make the remedies verified** |
| ⛔ **Reviewer eligibility** | **a separate gate.** ⛔ **Not satisfied by §5's trace, and not satisfied by this summary** |

---

# 7 · Open items after AMD6

| | Status |
|---|---|
| **`OPEN-M1`** `.gitignore` | ⏳ OPEN — untouched |
| **`OPEN-M2`** Phase 1/4 evidence vs the Phase 2 target | ⏳ OPEN — ⚠️ **further constrained by `C-4`'s placement decision, NOT decided; no phase reordered** |
| ✅ **`OPEN-M3`** | **CLOSED** (Option A) — ⛔ not reopened |
| **`OPEN-M4`** unbounded readers | ⏳ OPEN |
| **`OPEN-M5`** enforcing form / `AST-016` / `T-14` | ⏳ OPEN — ⛔ **NOT decided.** ⚠️ `RD-6` stands |
| ⭐ **`OPEN-M6`** amendment registration | ⏳ **OPEN — AMD3 + AMD4 + AMD5 + AMD6.** ⭐ **AMD6's COMMISSION is registered in four grants; NO amendment's DELIVERY is** |
| 🔴 ⭐ **`OPEN-M7`** *(NEW)* quarantine disposition — **the disposer AND the path** | ⏳ **OPEN.** 🔴 **The one open item that can block the migration's COMPLETION indefinitely** |
| **`INFO-2`** lane-registration provenance | ⏳ OPEN — a Governance matter |
| **`B′` · `R-CONFLICT` · `INV-ORDER` · `OPEN-M3` Option A · placement governance · Option D** | **FIXED — ⛔ not reopened by AMD6** |
| **Carried, outside AMD6's commission** | `RD-2` · `RD-4` · `RD-5` · `RD-6` · `RD-9` · `RD-10·r1` · `RD-10·r2` · `DI-1·r` *(partly repaired)* |

---

# 8 · Verification performed before delivery

| # | Check | Result |
|---|---|---|
| 1 | aggregate key | ✅ `KOS-AIP-GOV-STATE-DURABILITY-ADR`, read from the record's own `workItem`/`workflow` fields; ⛔ no aggregate created |
| 2 | exactly ONE normative Phase-5 sequence | ✅ §4.3's block; every other site refers to it; AMD4's four-step form labelled as history |
| 3 | the order is `1 · 1b · 2 · 3 · 4 · 5` | ✅ identical in the phase table, §4.3, §4.7 and criteria 13/14/16/17/18 |
| 4 | every Phase-5 record traced to a state and a destination | ✅ §4.3's placement table — 7 rows, no record unplaced |
| 5 | `POST-DEMOTION` terminology | ✅ used throughout *(32 occurrences in the plan)*; ⛔ **the malformed variant is neither used nor reproduced, in either artifact** |
| 6 | §6 `CASE A`/`CASE B` unchanged | ✅ §6 text untouched by this amendment |
| 7 | quarantine outside the `*.json` work-item glob | ✅ stated as a property, against `session-resolve.php:130` |
| 8 | quarantine distinct from staging and demotion | ✅ three markers, distinct in name, subject and lifecycle |
| 9 | Phase 7 cannot remove an undisposed quarantine | ✅ §4.4, §4 row 11, §4.7 `P7·3`, criterion 20 |
| 10 | §4.4 and §8 carry ONE current mismatch definition | ✅ §8 restated in §4.4's terms; superseded wording labelled |
| 11 | §11.1's count and Gates column agree | ✅ **9 data rows**, grouping sums to 9, gate claim read off the column |
| 12 | `OPEN-M6` spans AMD3 + AMD4 + AMD5 + AMD6 | ✅ §11, §11.1, header — with commission-vs-delivery stated precisely |
| 13 | every AMD6 act in the four-layer trace | ✅ 11 acts, all four columns resolve |
| 14 | no migration execution | ✅ **18 records · 216 transitions · 114 grants · 113 unique `grantId`s · 0 `CR` bytes · no `*.tmp*` · `git log` empty · `.gitattributes` unpinned · no durable target · no quarantine store · no grant registered** |
| ⭐ | tables well-formed | ✅ mechanically checked: **0 table blocks with mismatched column counts** |

⚠️ **The grant count moved 110 → 114 since AMD5, and it is `C-4`'s premise observed live: those four grants are AMD6's OWN commission, appended by `P-1` into the corpus the migration freezes and hashes.** ⛔ **A literal freeze would have had to prohibit the act that authorized this amendment.**

---

# 9 · Next actors

```
AMD6 (this amendment)                          author: bc1b47ef — ⛔ MUST NOT review or accept it
      ↓
FRESH INDEPENDENT Architecture review of AMD6              (C-11, C-12)
      |  ⛔ not bc1b47ef · ⛔ not ccf6c9c7 · ⛔ not 870305e0 · ⛔ not 1c8b041b · ⛔ not 9c908e70
      ↓
GOVERNANCE BOUNDED REVIEW                                  (C-11)
      |  completeness · provenance · lineage · current/superseded integrity ONLY
      |  ⛔ never citable as independent technical verification
      ↓
Governance registers AMD3 + AMD4 + AMD5 + AMD6             (OPEN-M6)
      ↓
PO/ARB — acceptance · OPEN-M5 if it chooses · ⭐ OPEN-M7
         the PHASE-0 FREEZE DECLARATION with its EXPECTED-DELTA LIST · the PHASE-4b AUTHORIZATION
      ↓
Migration execution — beginning at PHASE 0, never at Phase 3
```

**Traceability:** the four registered AMD6 grants *(`…-AMD6`, `…-AMD6-C6-C8`, `…-AMD6-C9-C11`, `…-AMD6-C12`)* · the **INDEPENDENT** AMD5 review by `claude-code-session:ccf6c9c7` *(its §4 `RD-7·a`/`RD-7·b`, §5 `RD-3·a`/`RD-3·b`/`RD-3·c`, §9 `DI-3·a`/`DI-3·b`, §11 interruption table, §12 `DI-4`…`DI-7`, §15 residuals, §16 verdict, §18 commission note)* · AMD5 `7d3abc59` · AMD4 `0a2fa71d` · AMD3 `bb1708b7` · technical review `a282d14b` · accepted design `ae451db9` · `B′` · `R-CONFLICT` *(quoted, unmodified)* · `INV-ORDER` · `ES-005.4` · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2`.

**AMD6 DELIVERED · STOPPING.** ⛔ **AMD6 IS NOT REVIEWED, NOT REGISTERED, NOT ACCEPTED.** ⛔ **THE MIGRATION IS NOT EXECUTED, NOT AUTHORIZED, AND MUST NOT BEGIN.**
