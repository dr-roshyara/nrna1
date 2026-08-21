# AMD6 — **INDEPENDENT** Principal Architecture review (REMEDY validation)

**Work item / aggregate:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · workflow `architecture-adr` · track label `KOS-AIP-GOV-STATE-DURABILITY`
⛔ **The track label is NOT the aggregate key.** Independently re-verified before opening the artifact: the only matching corpus record is `.claude/runtime/workflow/KOS-AIP-GOV-STATE-DURABILITY-ADR.json`, `workItem` = `KOS-AIP-GOV-STATE-DURABILITY-ADR`, `workflow` = `architecture-adr`.

**Artifact reviewed:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` — **AMD6**, commit `8307beca`, **1206 lines** *(secondary: the AMD6 summary at the same commit)*
**Input consumed, not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD5-ARCHITECTURE-REVIEW.md` — the residuals **as `claude-code-session:ccf6c9c7` stated them** (its §4 `RD-7·a`/`RD-7·b`, §5 `RD-3·a`/`RD-3·b`/`RD-3·c`, §9 `DI-3·a`/`DI-3·b`, §11 interruption table, §12 `DI-4`…`DI-7`, §15 residuals, §16 verdict, §18 commission note). ⛔ **Not derived from AMD6's restatement.**
**Date:** 2026-08-21
**Verdict:** 🟡 **PASS WITH DESIGN CLARIFICATIONS** — **all 7 commissioned residuals CLOSED · the central `C-4`/`C-7` decision ADDRESSED BUT NOT CLOSED · 🔴 one NEW unsafe-direction gap (`DV-1`) that must be repaired before Phase 5 · ⛔ Phase 3 must not begin**

**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, **exit 0**.

---

# 1 · Reviewer identity

**Reviewer process:** `claude-code-session:dd639043`

| Excluded process | Role | Match? |
|---|---|---|
| `claude-code-session:bc1b47ef` | **AMD6 author** · AMD3/AMD4/AMD5 author · technical review `a282d14b` | ✅ **no** |
| `claude-code-session:ccf6c9c7` | **independent AMD5 reviewer** — author of the findings AMD6 repairs | ✅ **no** |
| `claude-code-session:870305e0` | independent AMD4 reviewer — author of the prior `RD`/`DI` residuals | ✅ **no** |
| `claude-code-session:9c908e70` | independent AMD3 reviewer — `RC-1`…`RC-11` | ✅ **no** |
| `claude-code-session:1c8b041b` | original migration-plan producer | ✅ **no** |
| `claude-code-session:5e1dd9ee` | implementation design `ae451db9` *(disclosure-only)* | ✅ **no** |

**The exclusion check was performed BEFORE the artifact was opened.**

# 2 · Independence

> **This reviewer has no prior authorship or material participation in the migration plan, AMD3, AMD4, AMD5, AMD6, or the prior independent Architecture reviews.**

⚠️ **Self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`) — the same limitation every reviewer in this chain has recorded, and the same limitation `B′` exists to remove for the corpus itself.
⭐ **The §0.4.1 bound is DISCHARGED for the commissioned set:** the findings are `ccf6c9c7`'s, the remedies are `bc1b47ef`'s, and this judgement is a sixth process's. ⭐ **This review authors no remedy text and therefore hands no new self-review exposure forward.** **`R-34`/`P-2`: evidence and a recommendation only — this review accepts nothing, and nothing of its own.**

---

# 3 · AMD6 governance status — disclosed, not resolved

| | |
|---|---|
| **AMD6** | 🟡 **PROPOSED · ADDRESSED — NOT CLOSED · NOT REGISTERED · NOT ACCEPTED** |
| **Commission** | ✅ **registered, in four grants — independently verified in the corpus:** `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6` · `…-AMD6-C6-C8` · `…-AMD6-C9-C11` · `…-AMD6-C12`. ⭐ **All four are held inside the canonical aggregate record** *(`KOS-AIP-GOV-STATE-DURABILITY-ADR.json`, 11 grants · 6 transitions)* — no second aggregate, no label-derived key. ⭐ **AMD6 is the first amendment in this chain whose commission is registered at all** |
| **Delivery** | ⛔ **NOT registered.** Independently verified: `grep -oh 'G-KOS-GOV-STATE-DURABILITY[A-Za-z0-9-]*'` over the corpus → `…-MIGRATION-PLAN`, `-AMD1`, `-AMD2`, `-AMD6`, `-AMD6-C6-C8`, `-AMD6-C9-C11`, `-AMD6-C12`, `-DECISION`, `-OPEN-M3-DECISION`, and the bare `G-KOS-GOV-STATE-DURABILITY` — ⛔ **no `-AMD3`, no `-AMD4`, no `-AMD5`, and no grant recording ANY amendment as DELIVERED.** ⭐ **The plan's commission-versus-delivery distinction is exact** |
| **`OPEN-M6`** | ⏳ **OPEN — four amendments deep** |
| **Execution state** | ⛔ **nothing executed** — see §10 |

⛔ **This review does not register AMD6, does not accept it, does not call it adopted, manufactures no authority, and executes nothing.** Registration has exactly one writer — Governance (`G-2`/`R5a`).

---

# 4 · Residual-by-residual assessment

**Method, applied to every residual:** `FINDING → AMD6 REMEDY → TECHNICAL PROPERTY → PRIMARY EVIDENCE → JUDGEMENT`. ⛔ **No residual is classified `CLOSED` because the requested paragraph exists, because terminology changed, or because an acceptance criterion was added.**

## 4.1 `DI-5` / `RD-1` — Phase-5 normative order → ⭐ **CLOSED**

**Required property:** exactly ONE current normative enumeration of Phase 5's internal order, containing a reconciliation slot and a retraction step, with the acceptance object resting on it.

```
Phase 5 — MANDATED INTERNAL ORDER   (§4.3, labelled canonical)
  1    RE-HASH                   source vs the frozen manifest
  1b   RECONCILE / DISPOSE DELTA three outcomes
  2    READERS                   P-2, P-4
  3    ⭐ THE WRITER              P-1              ← the authority-transfer instant
  4    RUNTIME DEMOTION MARKER   runtime = DEMOTED
  5    STAGING MARKER RETRACTION the now-authoritative store loses NON-AUTHORITATIVE
```

| Test | Finding |
|---|---|
| the block is labelled *"mandated"* and declares itself the only normative enumeration | ✅ §4.3, and it says so in terms |
| the phase table refers to it | ✅ §4 row 9 |
| acceptance criteria refer to it | ✅ criteria 13 · 14 · 16 · 17 · 18 |
| operator instructions refer to it | ✅ §4.7's heading and `P5·1`…`P5·5` |
| §8's windows refer to it | ✅ the steps-3→5 row and the slot-`1b`/`3(ii)` triggers |
| ⭐ **criterion 14 now cites a step the mandated block DEFINES** | ✅ **the material half of `DI-5`, and it is repaired** |
| no competing CURRENT enumeration exists | ✅ **enumerated mechanically.** AMD4's `re-hash · readers · writer LAST` form survives only in §0.4.2 *(AMD4's own disposition table)* and is named **superseded** by criterion 13 and by §4.3's history note. §4 row 9 restates the order **verbatim and identically**, explicitly subordinated (*"defined ONCE in §4.3 and not restated here in a competing form"*) |
| ⭐ **slot `1b` has all three outcomes, and only one continues** | ✅ **A** identical → continue · **B** delta ⊆ declared → reconcile as a named step, continue · 🔴 **C** delta ⊄ declared → **STOP · RECORD · ESCALATE · NO AUTHORITY SWITCH**, with *"slots 2–5 do not run, the source is still authoritative"* |
| the *"last cheap abandonment point"* argument is technically coherent | ✅ **and it is coherent because it is bounded honestly.** At `1b` no reader and no writer has moved, so abandonment is *"stop"*, never *"undo"* (§8's first window row is the same fact). ⭐ **The plan does NOT claim the same for slot `3(ii)`** — it records that `3(ii)` is still pre-authority-transfer and still safe, **but must revert the slot-2 reader switches**, i.e. *"a small undo rather than a pure stop"*. **That distinction is correct and is exactly the kind of claim this chain has previously overstated** |

⭐ **`RD-1` is closed by the same edit, as the AMD5 review predicted.** ⚠️ **Recorded, not blocking:** §4 row 9's verbatim second copy is the same duplication surface that produced `DI-5` in the first place. It is identical today and explicitly subordinated, so it is not a regression — but the drift risk is structural, and a pointer would carry the same information with no second copy.

## 4.2 `RD-7·b` — the marker window → ⭐ **CLOSED**

**Required property:** the *"at NO point"* claim withdrawn; the real interval documented; not confused with authority; interruption handling safe; evidence bytes untouched.

| Test | Finding |
|---|---|
| the false claim is **withdrawn and shown** | ✅ §4.5 reproduces the superseded box and marks the offending line 🔴 **FALSE. WITHDRAWN** — *"a claim that quietly changes is worse than one that visibly does"* |
| the interval really exists and is stated | ✅ a four-row interval table: `Phase 2 → step 3` true · **`step 3 → step 4` FALSE** · **`step 4 → step 5` FALSE** · `after step 5` unlabelled |
| the restated claim matches the evidence exactly | ✅ *"REDUCED from a durable, versioned false label to the step 3→5 interval; IRREDUCIBLE WITHOUT ATOMICITY; RECORDED, NOT CLOSED"* |
| ⛔ not confused with authority | ✅ *"a marker still reports state; it never confers or removes authority — §4.3's writer switch does that, and this is only its visible trace"* |
| writer switch remains the authority transfer | ✅ slot 3, unmoved; §8's boundary still *"the writer switch"* |
| ⭐ **a safe-direction rollback is actually DEFINED** | ✅ **it is, and it is the right direction.** §8's new row: *"NOT a rollback, and NOT an authority question: authority already moved at step 3 … the safe direction is FORWARD — complete steps 4 and 5, which is a marker act that touches no evidence byte."* ⛔ **And it forbids the two wrong moves in terms: *"Do NOT retract-then-re-write"* and *"do NOT treat the stale label as evidence that the switch did not happen"*** |
| evidence bytes untouched in every interval | ✅ marker ops are out-of-band, directory-level, ⛔ never `*.json` — re-confirmed against `session-resolve.php:130`'s `glob($recordDir . '/*.json')` |
| criterion 14 does not overclaim | ✅ **AMD6 narrows it itself:** *"this accepts the FINAL state. It does NOT assert the label was correct throughout"* |

⭐ **This is the cleanest remedy in AMD6: the defect was the denial, the denial is gone, and nothing was reordered to hide it.**

## 4.3 `RD-3·a` — post-demotion disposition → ⭐ **CLOSED**

**Required property:** a terminating condition that does not run through the pre-switch re-verification gate, under which no post-demotion bytes can become authoritative and Phase 7 cannot complete.

| Test | Finding |
|---|---|
| the *"for either case"* rule is **withdrawn** and labelled | ✅ §4.4 quotes it as superseded history per §0.4.4 — verified: no *"either case"* wording survives as current |
| `POST-DEMOTION` does **not** gate on re-verification | ✅ **stated absolutely:** *"IT DOES NOT GATE ON RE-VERIFICATION … There is no re-verify that can pass, and the plan no longer implies one"* |
| the branch is per-branch, not shared | ✅ a two-row terminating-condition table; `PRE-SWITCH` keeps reconcile → re-verify → removal |
| no post-demotion bytes can become authoritative by reconciliation | ✅ the import prohibition is unchanged and unambiguous, and the comparison object can no longer absorb them — see the next row |
| ⭐ **reading 1 — the object silently absorbs the bytes** | ⛔ **STRUCTURALLY EXCLUDED, not discouraged:** the `SOURCE FINAL-STATE ENUMERATION` is **fixed and recorded at slot 3**, so there is nothing for it to absorb into. ⭐ **This is the strongest of the three answers, because it removes the failure mode rather than prohibiting it** |
| ⭐ **reading 2 — the re-verify silently keeps failing** | ✅ **replaced by an explicit named state: Phase 7 is SUSPENDED**, and the store is **RETAINED ENTIRE** — the gap `ccf6c9c7` named (*"the plan nowhere says the demoted store is retained indefinitely"*) is filled |
| ⭐ **reading 3 — remove the store to make the check pass** | ⛔ **forbidden in terms at four sites:** §4.4, §4 row 11, criterion 20, §4.7 `P7·3` (*"removing the store to make a check pass destroys the bytes quarantine exists to preserve"*) |
| Phase 7 cannot complete while a quarantine is undisposed | ✅ and criterion 20 makes it **positively** demonstrable: *"the migration aggregate is NOT recorded as COMPLETED, and runtime is NOT reported as execution-only"* |
| ⭐ **is the termination a terminating GOVERNANCE state, or an implementation dead end?** | ✅ **a governance state, and the plan is explicit about which:** the only exit is a **governed disposition** (`OPEN-M7`), the disposer is named as a **reading, not a decision**, and §0.6.4 states the DDD form — *"an undisposed conflict means the migration aggregate CANNOT LEGITIMATELY TRANSITION TO ITS COMPLETED STATE — so the block is not a tooling limitation to be engineered away, it is the aggregate refusing an invalid transition."* ⭐ **A dead end has no owner; this has an owner and an unbuilt path, which is a gap that governance can close** |

## 4.4 `RD-3·b` / `C-2` / `C-3` / flag `AA` — quarantine → ⭐ **CLOSED** *(one clarification, `DV-6`)*

| Test | Finding |
|---|---|
| **STORE-level** protection (`C-2`) | ✅ *"the demoted store, its records and its marker are retained **whole**"* |
| ⛔ no partial-record removal semantics | ✅ **stated as a prohibition**, with the reason: it would break `RC-6`'s directory-level marker rule |
| original filename · bytes · hash identity retained | ✅ all three, with flag `AA`'s reason stated non-stylistically: *"renaming would break the manifest linkage that PROVES the bytes were preserved"* |
| ⛔ no rename · no re-encoding | ✅ |
| quarantine **by directory, never by filename manipulation** | ✅ flag `AA` satisfied in terms |
| ⭐ **outside every `*.json` work-item glob** | ✅ **stated as a property — and independently verified ACHIEVABLE:** `session-resolve.php:130` is `glob($recordDir . '/*.json')`, which is **non-recursive**, so a governed store that is not itself a scanned directory is genuinely invisible to the record predicate. ⭐ **The reason is also stated correctly: a quarantined record IS a `*.json` file, so anything inside a scanned directory would be read as a work item** |
| ⛔ no path invented | ✅ *"the physical sub-path is resolved at execution time through EXISTING placement governance"* — consistent with §2 and with the binding `C-4` refinement |
| `QUARANTINED` marker explicit and distinct | ✅ a **third** marker; §4.5's *"two markers"* row is now *"the THREE markers"* with the old form labelled superseded; *"distinct in name, subject and lifecycle, and no one of them implies another"* |
| quarantine remains **non-authoritative** | ✅ *"NOT authority … a location does not confer authority"* — ⭐ **`B′`'s own lesson applied to the new store, which is the correct derivation rather than a new rule** |
| ⭐ **does Phase 7 actually REFUSE removal of any store holding an undisposed quarantine?** | ✅ **yes, at four independent sites** — §4.4's `C-8` box (*"no removal, of any store"*), §4 row 11, criterion 20, §4.7 `P7·3`. ⛔ **Not implied anywhere; stated everywhere** |
| the full lifecycle is traceable | ✅ `POST-DEMOTION WRITE → QUARANTINE → RETAIN → GOVERNED DISPOSITION REQUIRED → possible future release`, carried by §4.5's lifecycle row (*"retracted only by a governed disposition"*) and §4.4's consequence box |

⭐ **The mechanism choice is right and non-obvious: quarantine is a byte-preserving PLACEMENT (a copy into the governed store) while the demoted store is retained UNMODIFIED.** ⚠️ **See `DV-6` — the rule is correct and doubly guarded, but the reason given for refusing a MOVE is inverted, and the correct reason is the stronger one.**

## 4.5 `OPEN-M7` — quarantine disposition → ⭐ **correctly SPLIT, correctly NOT DECIDED**

⛔ **This review does not decide `OPEN-M7`.**

| Test | Finding |
|---|---|
| the existing **act class** is identified | ✅ §8's *"re-promoting a demoted source is a **governance act**, not a merge"* and §6.2's *"recorded separately"* |
| the existing **authority pattern** is identified | ✅ the pair this plan already names — **PO/ARB decides · Governance registers** (`G-2`/`R5a`, §11.1) |
| the **missing disposal mechanism** is identified | ✅ *"no admissible-outcome set, no discharge evidence"* — and **measured, not asserted:** the mechanism's transition vocabulary is `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL`. ⭐ **Independently re-verified in `workflow-state.php` — there is no `DISPOSE`** |
| ⛔ **no new owner invented** | ✅ **canonical discovery ran BEFORE anything was named** (`ES-005.4`, §0.6.4), and the result is *"consume or extend"*, not *"create a second"* |
| the disposer statement is a **proposal, not a decision** | ✅ **verified in the wording, twice:** *"Architecture's reading is the existing PO/ARB·Governance pair **by extension of §8**, and ⛔ Architecture does not decide it"* · §11.1's actor cell reads ⛔ **UNDETERMINED — that IS the question** |
| the consequence is **not softened** (`C-8`) | ✅ **the six-line consequence block is unsoftened**, and it explicitly refuses the softened form (*"NOT softened to 'Phase 7 remains blocked'"*) — Phase 7 blocked · store preserved · no removal · **`B′`'s target end state NOT reached** · runtime NOT execution-only · a governed disposition REQUIRED |
| the indefinite block is owned as the safe direction | ✅ *"A single undisposed quarantine can block the migration's completion INDEFINITELY, and that is the safe direction"* |

⭐ **This is how a gap should be returned to governance: the class is found, the actor is proposed by extension, the mechanism's absence is measured, and nothing is decided.**

## 4.6 `DI-4` — §8 / §4.4 consistency → ⭐ **CLOSED**

| Test | Finding |
|---|---|
| exactly ONE current mismatch model | ✅ **verified by reading both sections against each other.** §4.4 and §8 now carry the same split in the same terms |
| the semantic split is intact in both | ✅ `PRE-SWITCH` → reconcile under §6 **and** re-verify → removal may proceed · `POST-DEMOTION` → **suspension**: quarantine · retain the store ENTIRE · escalate · ⛔ no removal · ⛔ **no re-verification gate, because none can pass** |
| the refuted single-branch wording is retained and **LABELLED** | ✅ quoted inside §8's row as superseded history per §0.4.4, with the reason: *"until AMD6 it stood here as CURRENT text in the section a Phase-7 operator consults"* |
| no current operator instruction contradicts the split | ✅ §4.7 `P7·1`/`P7·2`/`P7·3` agree with §4.4 and §8 in every branch, and §4.7 states its own subordination: *"if this section and a normative section ever disagree, the normative section governs and the disagreement is a defect to report, not a choice to make"* |
| ⭐ **`DI-1·r` also repaired** | ✅ §4.2 now carries the dispersal pointer — *"Phase 7 is specified in §4.2, §4.4, §4.7 and §8"* — which is the mechanism by which `DI-4` survived two amendments. **Outside AMD6's commission; repaired anyway and labelled as such** |

## 4.7 `DI-6` — dependency / gate consistency → ⭐ **CLOSED**

⛔ **The count and the gate claim were re-derived from the rows, not read from the prose.**

| Test | Finding |
|---|---|
| row count | ✅ **9 data rows, counted mechanically:** registration · Phase-0 declaration · Phase-4b authorization · `OPEN-M5` · plan acceptance · `RC`/`RD`/`DI` remediation · `INFO-2` · `OPEN-M7` · the grouped `OPEN-M1`/`M2`/`M4` row |
| grouping sums to the rows | ✅ Governance 2 + PO/ARB 4 + Architecture 1 + `OPEN-M7` 1 + grouped 1 = **9** |
| ⭐ **the gate claim is DERIVED from the Gates column** | ✅ **every cell walked independently:** registration → *"acceptance"* · Phase 0 → *"Phase 1"* · Phase 4b → *"Phase 5"* · remediation → *"Phases 3 · 4b · 7"* · `OPEN-M7` → *"Phase 7 — CONDITIONALLY"* · plan acceptance → *"execution as a whole"* · `OPEN-M5` → ⛔ *"nothing"* · `INFO-2` → *"—"* · grouped → *"—"*. ⇒ **three rows gate an execution phase unconditionally, one gates Phase 7 conditionally, registration gates ACCEPTANCE, acceptance gates execution as a whole, three gate nothing.** ⭐ **That is exactly what §11.1 claims, and it is read off rather than asserted over** |
| `DI-3·a` is genuinely repaired | ✅ **the substitution is gone:** the remediation row is now IN the execution set and registration is NOT, which is what its own cell always said. AMD5's *"3 GATE EXECUTION"* set is quoted as superseded history |
| registration versus acceptance semantics | ✅ kept apart, and the commission-versus-delivery distinction is stated precisely at three sites (header, §11 `OPEN-M6`, §11.1) |
| current AMD3…AMD6 registration state | ✅ the row reads **AMD3 + AMD4 + AMD5 + AMD6**, `DI-3·b`'s staleness gone — **independently verified against the corpus** (§3 above) |
| the remediation row reads its true current state | ✅ `RC-1`…`RC-11` CLOSED by the AMD4 review · `RD-10`/`DI-1`/`DI-2` CLOSED by the AMD5 review · `RD-7`/`RD-3`/`DI-3` ADDRESSED by AMD5 · the AMD6 set ADDRESSED — ⛔ **NOT self-closed** |
| ⭐ `OPEN-M7`'s conditional Phase-7 gate | ✅ present, and the conditionality is exact: *"only if a `POST-DEMOTION` quarantine exists, and then ABSOLUTELY"* |
| ⛔ no dependency invented to reach a number | ✅ **AMD6 adds exactly one row and one open question, and states why: *"a Phase-7 block with no owner is worse than a named gap"*** |

## 4.8 `DI-7` / `C-1` / `C-6` — terminology boundary → ⭐ **CLOSED**

| Test | Finding |
|---|---|
| ⭐ **§6's `CASE A` / `CASE B` UNCHANGED** | ✅ **verified by byte comparison, not by reading:** §6 in full is **byte-identical** between `7d3abc59` and `8307beca`; §6.1's invariant text is md5-identical. ⛔ **AMD6 touched no part of §6** |
| only the NEWER family renamed | ✅ `CASE α`/`CASE β` → **`PRE-SWITCH DISPOSITION`** / **`POST-DEMOTION DISPOSITION`** |
| the direction is the safe one | ✅ **and it is measured, not assumed.** Re-measured at the AMD5 baseline: `CASE A` **15** + `CASE B` **8** = **23**, cited in four delivered reviews and §10 criterion 10; `CASE α` **5** + `CASE β` **6** = **11**, AMD5's own addition, cited nowhere outside the plan. ⭐ **This is `DI-1`'s rule — preserve the cited identifiers, rename the new ones — correctly applied to names instead of numbers** |
| expected current terminology | ✅ **`PRE-SWITCH` (32 occurrences) · `POST-DEMOTION` (18)**, used throughout §4.4, §4 row 11, §8, §4.7 and criterion 15 |
| no malformed terminology in the current artifact | ✅ **verified.** The variant `C-6` forbids does not occur in the plan or the summary. ⛔ **This review does not reproduce it either, for the same reason AMD6 does not: reproducing it would be the only way to introduce it** |
| historical material **labelled**, not silently rewritten | ✅ **all 9 residual `CASE α`/`CASE β` sites walked** — every one is inside a disposition/naming/history cell that names AMD5 as the source. ⛔ **No unlabelled current use** |
| §8's trigger list disambiguated | ✅ *"`CASE B` here means §6's `CASE B` and nothing else"* — ⭐ **and the collision is gone by construction: there is no `β` left to mistake for a `B`** |

---

# 5 · `C-4` / `C-7` — the Phase-5 evidence-placement decision → 🟡 **ADDRESSED BUT NOT CLOSED**

**This is the most consequential thing AMD6 does, and it is reviewed as a decision, not as prose. The reasoning below was reconstructed independently before AMD6's version was accepted.**

## 5.1 The derivation is genuine — it IS forced, not chosen

**Independently re-derived from §3, whose rows I read directly:**

```
§3 row 1  an AUTHORITATIVELY resolved location lies inside the governed evidence boundary
§3 row 2  an explicit --dir override yields a NON-AUTHORITATIVE output location, and confers no authority
slot 3    P-1's resolution changes here — the authority-transfer instant (§4.3, RC-5, unmoved)
```

| Record | Is the destination FORCED? | Independent finding |
|---|---|---|
| slots **1 · 1b · 2** | ✅ **yes → the source** | before slot 3 the only way to write into the staging store is `--dir`, which §3 row 2 makes **non-authoritative**. ⇒ an authoritative pre-switch append **cannot** land anywhere but the source |
| slots **4 · 5** | ✅ **yes → the authoritative store** | after slot 3, a write into the demoted source is **exactly** the `POST-DEMOTION` class §4.4 quarantines |
| slot **3's own evidence** | ⛔ **NOT forced — this is the only real decision** | and AMD6 correctly isolates it as such |

⭐ **The claim that placement is derived rather than chosen is TRUE for six of the seven rows, and AMD6 says so precisely instead of claiming the seventh is derived too.** ✅ **The placement table's 7 rows account for every Phase-5, Phase-6 and Phase-7 record — no record is left unplaced.**

## 5.2 The termination argument — ⭐ **it holds, and it is the right resolution**

**Tested against the phase semantics, not accepted from the text:**

```
IF slot 3's evidence were written PRE-SWITCH:
    slots 1/1b/2 append to the source
    step (iii) reconciles the durable copy TO that source state    [mechanical write]
    slot 3's own evidence lands in the SOURCE
        ⇒ the source now differs from the copy again
        ⇒ reconcile again                                          [mechanical write]
        ⇒ …and that write must itself be evidenced (see below)
        ⇒ which appends to the source again
        ⇒ ⛔ NON-TERMINATING
IF written POST-SWITCH:
    P-1 has moved ⇒ the source receives nothing further ⇒ the copy is final ⇒ terminates in one step
```

⭐ **This is a genuine fixed-point problem, not a tidiness preference: a store's final state cannot be recorded INSIDE that store, because recording changes it.** ⭐ **Putting the closing record on the far side of the switch is the correct resolution, and it is the only one available without atomicity.** ✅ **The decision is architecturally sound.**

⚠️ **But see `DV-4`: the argument's load-bearing premise is not in the argument.** As written, the recursion reads as stoppable after one iteration — a reconciliation is a mechanical write **into the copy**, so it need not append to the source at all. What makes it non-terminating is a rule stated in a **different row of a different table**: *"a mechanical write on a store confers no authority and **is evidenced by a governance append that describes it**."* Pre-switch, that append lands in the source, which re-opens the delta. ⇒ **the decision is right; the derivation omits the step that makes it a derivation.**

## 5.3 The three `C-4` properties, tested one by one

| `C-4` property | Finding |
|---|---|
| switch evidence lands in the **authoritative** store **after** the switch | ✅ **delivered.** Slot 3's ordered sub-steps put `(iv) SWITCH P-1` before `(v) write the SWITCH-OVER RECORD`, and `P5·3` forbids the alternative in terms: ⛔ *"Never write the switch-over record into the source"*. ⚠️ **The *"FIRST record"* wording is false — `DV-3`** |
| the Phase-7 re-hash is **not** self-detecting because bookkeeping went into the source | ✅ **delivered in mechanism** — the source's expected content is **CLOSED at slot 3** and **recorded** as the `SOURCE FINAL-STATE ENUMERATION`, which **is** criterion 12's comparison object. ⭐ **`RD-10`'s degradation genuinely does not recur at the irreversible gate.** ⚠️ **Subject to `DV-2`: the enumeration's third operand is *"the DECLARED pre-switch Phase-5 records"*, and §4.0 — untouched by AMD6 — does not require the declaration to contain them** |
| the switch-over record remains a **valid discriminator** | ✅ **delivered, and the reasoning is right for the right reason.** The boundary is **content-defined**, not timestamp-defined, *"because only **2 of 216** transitions carry any time field"* — a measured constraint, not a stylistic preference. `⊆ enumeration` → accounted for · `outside it` → `POST-DEMOTION` by construction · `unestablishable` → `POST-DEMOTION` (AMD5's tie-break, unchanged, conservative) |

## 5.4 The residual measure→switch window — ⭐ **conservatively and correctly treated**

**The ambiguity, tested as the commission requires:** a write landing between `(i) MEASURE` and `(iv) SWITCH` is **genuinely pre-switch**, yet falls **outside** the captured enumeration, so the tie-break classifies it `POST-DEMOTION`.

| Test | Finding |
|---|---|
| the window is **recorded, not discovered later** | ✅ §4.3's residual-window table names it ⭐ *"NEW with AMD6 and recorded here rather than discovered later"* |
| the misclassification direction is safe | ✅ `POST-DEMOTION` **preserves without promoting** ⇒ the error costs precision, never bytes |
| the imprecision is admitted | ✅ *"safe, imprecise, and irreducible without atomicity"* |
| no atomicity is invented | ✅ *"`Increment 2` is not authorized, so this is recorded, NOT closed"* |
| it has a §8 row | ✅ and the plan states the pattern deliberately: *"AMD6 shrinks two windows and INVENTS NO ATOMICITY IT CANNOT DELIVER"* |

⭐ **AMD6 is trading precision for safety with the trade stated. That is the correct treatment of an irreducible ambiguity, and it is the treatment the AMD5 review asked for when it said the defect was the denial, not the window.**

## 5.5 🔴 `DV-1` — the durable copy is written twice after its last verification and re-verified nowhere before Phase 7 removes the source

⛔ **This is the one finding in this review whose failure direction is UNSAFE, and it arises from the mechanism that delivers `C-4`'s second property.**

```
Phase 4      verify the durable copy vs the FROZEN MANIFEST — byte · hash · count ·
             sequence · provenance — ALL-OR-NOTHING                    (§4 row 7)
                                    ↓
slot 1b      reconcile the declared delta INTO THE DURABLE COPY       (P5·1b)   ← write #1
slot 3(iii)  bring the durable copy to the source's closing state     (P5·3)    ← write #2
slot 3(iv)   SWITCH P-1  → the copy is now the authoritative store
                                    ↓
Phase 7      FINAL RE-HASH:  runtime source  vs  the recorded ENUMERATION
             identical → ✅ removal permitted                          (§4.4, criterion 12)
                                    ↓
             ⛔ neither operand of that gate is the durable copy
```

| Test | Finding |
|---|---|
| is the copy verified after write #1 or #2? | 🔴 **no.** Every `re-verify` / `all-or-nothing` site was enumerated mechanically. The only Phase-4-semantics re-verification on the Phase-7 path is inside §4.4's **`PRE-SWITCH` exception branch** and `P7·2`. ⭐ **The plan re-verifies the copy on the EXCEPTION path and not on the NORMAL path — and the normal path is the one that ends in removal** |
| does the final re-hash cover it? | 🔴 **no.** It compares **source vs the enumeration recorded in the switch-over record**. Both operands are independent of the copy's content, so an incomplete or non-byte-preserving write #1/#2 leaves the gate reporting `identical` |
| does any criterion supply the missing act? | 🔴 **no.** **Criterion 18** asserts the property — *"pre-switch records … **carried across by slot 3(iii)**"* — and names **§4.3's placement table** as its evidence. **A specification is not a verifying act.** **Criterion 2** has the same shape: it asserts *"no record lost relative to the frozen manifest and both reconciliation inputs"* while its named evidence (*Phase 4 count + superset check + the Phase-5 re-hash*) all predates write #1 |
| what is lost if it fires? | 🔴 **the reconciled `1b` delta and the pre-switch Phase-5 records** — precisely the records that exist in the source and nowhere else at the moment Phase 7 removes it — **with no gate having failed** |
| does AMD6 claim otherwise? | 🔴 **yes, and this is why it is material.** §4.4 states that *"§4.2's justification for Phase 7 — 'a redundant, demoted, **BYTE-VERIFIED** copy' — becomes true at the moment of removal rather than true as of Phase 4."* **For those records it is not true at either moment.** §5's *"no migration evidence is left behind in runtime"* and criterion 18 rest on the same unverified write |

⭐ **Stated fairly, because attribution matters here:** the reconciliation write pre-dates AMD6 (`RD-10`/§4.0 created the act; AMD6 gave it a numbered slot). AMD6's own additions are **slot 3(iii)'s carry-across, criterion 18's assertion, and the strengthened byte-verified claim.** ⭐ **And AMD6's specificity is what makes this findable at all — before it, Phase-5 record placement was unspecified, so the gap could not be stated.** ⛔ **That is a reason to record the finding precisely, not a reason to withhold it: this is the `RC-4` shape — *an act named in a specification and carried by no phase* — and it now sits on the path to the irreversible act.**

⇒ **Repair, inside the current envelope, requiring no decision and no reordering:** make a **Phase-4-semantics, all-or-nothing re-verification of the durable copy** a mandated part of slot 3 — after `(iii)` and **before** `(iv) SWITCH P-1` — and make it criterion 18's demonstrating act. ⭐ **Placing it before the switch also means it fails at the last cheap stop point, which is where §4.3 already puts every other pre-switch check.**

## 5.6 🔴 `DV-2` — the enumeration's third operand has no counterpart in §4.0, which AMD6 did not touch

**`C-4` gives the Phase-7 comparison object a third operand:** `manifest + reconciled delta +` ⭐ **the DECLARED pre-switch Phase-5 records** — cited at §4.3 slot `3(ii)`, §4.4's precondition, criteria 12 · 17 · 18, and `P5·3`.

| Test | Finding |
|---|---|
| where are the declaration's contents specified? | **§4.0 alone** — *"the Phase-0 declaration MUST enumerate, in advance, the work items and record classes whose appends are expected"*, over an illustrative list |
| does that list name the pre-switch Phase-5 records? | 🔴 **partly.** It names *"the reconciliation records"* (slot `1b`) — it does **not** name **slot 1's re-hash comparison record** or **slot 2's reader-switch evidence**, both of which `C-4` newly and explicitly places into the source |
| did AMD6 update §4.0? | ⛔ **no — verified mechanically: §4.0 is untouched by `8307beca`** |
| consequence if PO/ARB writes the declaration from §4.0's list | 🔴 **slot `3(ii)` yields outcome C — FREEZE VIOLATION — in the NORMAL case ⇒ STOP BEFORE THE SWITCH.** ⭐ **That is `RD-10`'s degradation, relocated from Phase 7 (where `C-4` cured it) to slot `3(ii)` (which AMD6 created)** |
| failure direction | ✅ **SAFE** — a stop, nothing switched, nothing destroyed. ⛔ **But it makes a gate unsatisfiable in the normal case, which is the defect class `C-4` exists to remove** |
| is the cure available? | ✅ **yes, and it is already recorded:** `RD-10·r2`'s **class-based** declaration (*"records written by this lane for this work item"*), which §4.0's own *"record classes"* wording permits |

⚠️ **Also inert-stale:** §4.0's list still names *"the switch-over record"* among the records expected in the **source** delta. After `C-4` that record is never written into the source, so it can never appear there. Harmless, but it is a list that no longer matches the design it feeds.

⇒ **Repair:** state — in §4.0, where the declaration is specified — that the declared set MUST cover **the Phase-5 pre-switch record classes (slots 1, 1b, 2)**, and prefer the class-based form. ⭐ **AMD6 already raises the stakes on this act in §11.1 (*"AMD6 raises the stakes without changing the act"*); what it does not do is say what the act must now contain.**

## 5.7 🔴 `DV-3` — *"the FIRST record in the authoritative store"* is false under the plan's own record vocabulary

**Four current sites, one of which is the acceptance object:** §0.6.3's `C-4`-property table · §4.3's placement table · §4 row 9's *Produces* cell · ⭐ **§10 criterion 17**.

| Reading | Finding |
|---|---|
| *"record"* = a work-item `*.json` file | 🔴 **false.** By slot `3(v)` the store holds the byte-preserving copy of all 18 records **plus** everything carried across at `3(iii)`. §4 row 2 fixes the predicate as *"EXACTLY `*.json`"*, so those copies **are** records |
| *"record"* = a transition/grant entry | 🔴 **false, and it is not even a new file.** By the plan's own counting vocabulary — *"18 records · 216 transitions · 114 grants"* — the switch-over record is a **transition appended inside an existing record**, following 216 carried-across transitions |
| ⭐ what is TRUE, and sufficient | ✅ **the switch-over record is the first GOVERNANCE APPEND made into the authoritative store AFTER the writer switch.** ⭐ **That is exactly what the discriminator needs, and the plan already owns the vocabulary to say it — §4.3 distinguishes GOVERNANCE APPENDS from MIGRATION MECHANICAL WRITES and then does not use the distinction in the claim** |

⛔ **Criterion 17 is therefore not demonstrable as written** — a verifier checking it literally must fail it. ⭐ **This is the `DI-5` shape recurring on the acceptance object: a criterion naming something the design does not produce.** ✅ **Nothing about the placement decision is wrong; only the claim about it is.**

## 5.8 ⚠️ `DV-5` — the readers-first justification now names the wrong bound

**§4.3:** *"this order creates only a briefly-stale-read window, and **the re-hash has already bounded that**."*

🔴 **Post-`C-4` the second clause is false.** Slot 2 switches the readers to the copy; slots 1, 1b and 2 append **into the source**. ⇒ **the stale-read window is non-empty BY CONSTRUCTION and contains at least slot 2's own record**, and **slot 1's re-hash precedes all three**, so it cannot bound them. ✅ **What does bound it is slot `3(i)`–`(iii)` — a mechanism AMD6 supplies.** ⇒ **the design is sound; the sentence credits the wrong instrument, and the window is absent from §4.3's own residual-window table.** ⭐ **Bounded and self-referential: only this work item's own record is affected, and only until `3(iii)`.**

## 5.9 ⚠️ `DV-6` — the MOVE refusal is correct and its stated reason is inverted

**§4.4:** *"A MOVE is refused: it would alter the demoted store's content and so break the recorded `SOURCE FINAL-STATE ENUMERATION` and the manifest linkage."*

🔴 **A post-demotion record is OUTSIDE the enumeration by definition.** Moving it out of the demoted store therefore makes the source **match** the enumeration ⇒ the final re-hash reports `identical` ⇒ **criterion 12's removal branch opens while a quarantine stands undisposed.** ⭐ **That is `RD-3·a`'s third unsafe reading — *"an implementer removes the store to make the check pass"* — in a subtler form the section does not name: not removing the store, but removing the one record that makes it fail.**

✅ **The rule itself is right and independently guarded** — `P7·2` forbids the move in terms, and criterion 20 / §4 row 11 / `P7·3` refuse removal while any quarantine is undisposed, so the laundering path is closed twice over. ⛔ **But the reason given is the weaker of the two available, and an implementer reasoning from it would conclude a move is harmless to the enumeration.** ⇒ **state the load-bearing reason: a move would make the source spuriously satisfy criterion 12.**

---

# 6 · Four-layer trace assessment — ⭐ **TRACE PRESENT** · ⛔ **NOT ASSURANCE** · ⚠️ **one row does not resolve**

## 6.1 The trace, checked mechanically

| Test | Finding |
|---|---|
| 11 acts, all four columns populated | ✅ |
| every `NORMATIVE ENUMERATION` cell resolves | ✅ §4.3 slots 1 · 1b · 2 · 3 · 4 · 5, the placement table, §4.5's lifecycle, §4.4's split, §4 row 11 — **all exist** |
| every `ACCEPTANCE CRITERION` cell resolves | ✅ criteria **8 · 11 · 13 · 14 · 15 · 16 · 17 · 18 · 19 · 20** — §10 carries **20 criteria, numbered 1…20, each exactly once** *(counted mechanically)* |
| every `OPERATOR INSTRUCTION` cell resolves | 🔴 **NO — see `DV-7`** |

## 6.2 ⚠️ `DV-7` — the trace's own closing claim is false for row 6

```
§0.6.5 row 6  "Phase-5 evidence placement by SUBJECT-STATE"  →  operator instruction:  P5·ALL
§4.7 defines:  P5·1 · P5·1b · P5·2 · P5·3 · P5·4 · P5·5 · P7·1 · P7·2 · P7·3
⛔ P5·ALL is defined NOWHERE — it occurs exactly ONCE in the artifact, in that cell
```

**The trace closes:** *"✅ Every row resolves in all four columns; that is the whole claim."* ⛔ **That claim is false for row 6.**

✅ **The substance is present** — `P5·1` through `P5·5` each state their destination (*"a pre-switch append into the source"* ×3, *"into the AUTHORITATIVE store"* ×3), and `P5·3` forbids the wrong one in terms. **So criterion 18 has operator coverage; the identifier it cites does not exist.**

⭐ **This is the fifth occurrence in this chain of the shape `RD-7·a`/`DI-5` named — an object citing an identifier its normative section does not define — and it occurs INSIDE the `C-12` self-check delivered to mitigate exactly this quality risk.** ⛔ **That is not an argument against the trace. It is the trace's second half proving itself: a self-check by the author found ten rows and missed the eleventh, which is precisely why `C-12` says it cannot discharge the review gate.**

## 6.3 The `C-12` boundary itself → ⭐ **correctly held**

✅ **AMD6 states the disqualifying half FIRST, twice** — §0.6.5's opening line and the closing block: *"the trace is DELIVERED EVIDENCE, not assurance … it DOES NOT DISCHARGE THE REVIEW GATE."* ✅ **The summary repeats it and adds the sharper form:** *"Reviewer eligibility is a separate gate — not satisfied by §5's trace, and not satisfied by this summary."*
✅ **No item is self-closed.** Verified across §0.6.1, §11.1's remediation row and the closing block: **every AMD6 item is `ADDRESSED`, none is `CLOSED`**, and the artifact states *"AMD6 IS NOT REVIEWED, NOT REGISTERED, NOT ACCEPTED."*
✅ **`C-12`'s authoring/reviewing split is recorded accurately** — `bc1b47ef` eligible to author, ⛔ barred from reviewing and accepting, with the estate's rule stated as *author ≠ independent reviewer*, not *author ≠ every prior reviewer*, and **repeated authoring named as a QUALITY RISK rather than assumed away.**

---

# 7 · `C-9` — aggregate identity → ⭐ **CLOSED, and verified independently rather than accepted as recorded**

| Test | Primary evidence |
|---|---|
| the canonical aggregate key | ✅ **read from the record's own fields:** `workItem` = `KOS-AIP-GOV-STATE-DURABILITY-ADR`, `workflow` = `architecture-adr` |
| exactly one matching record exists | ✅ the workflow record directory listed and filtered for `STATE-DURAB` → **one file** |
| the track label was not used as a key | ✅ no `KOS-AIP-GOV-STATE-DURABILITY.json` exists |
| ⛔ no aggregate created, initialised or forked | ✅ **18 records before and after** — the corpus record count is unchanged |
| the commission grants live in the canonical aggregate | ✅ **all four AMD6 grants are inside `KOS-AIP-GOV-STATE-DURABILITY-ADR.json`** *(11 grants · 6 transitions, exactly as the plan reports)*. ⭐ **Grant IDs carry the track-label prefix; that is a naming convention, not an aggregate key, and no grant sits outside the aggregate** |

---

# 8 · DDD / domain-model review

| Distinction | Held? |
|---|---|
| **Execution state ≠ governance evidence** | ✅ §1.6 and §9 untouched. ⭐ **Strengthened by `C-4`'s two write classes: GOVERNANCE APPENDS (authority follows the resolution, §3) versus MIGRATION MECHANICAL WRITES (the Phase-3 copy, the `1b` reconciliation, `3(iii)`)** — *"a mechanical write on a store confers no authority"* |
| **Evidence ≠ proof** | ✅ and it is where `DV-1` bites: criterion 18 and §5 assert the carry-across as evidence of a property nothing verifies. **The distinction the plan teaches is the one `DV-1` violates** |
| **Evidence identity ≠ authority** | ✅ untouched — identity still reported, never validated (§3 row 3) |
| **Authority ≠ storage location** | ✅ ⭐ **this is the sharpest thing AMD6 adds, and it is applied twice:** post-demotion bytes are non-authoritative *"because of WHEN they were written, not where"*, and the quarantine store gets `B′`'s own lesson turned on it — *"a location does not confer authority"* |
| **Quarantine ≠ authority** | ✅ explicit: *"NOT authoritative evidence, NOT readable as a work item, NEVER promoted by the act of being stored somewhere governed"* |
| **Migration mechanical write ≠ governance append** | ✅ ⭐ **newly and correctly separated by `C-4`** — and it is the premise the termination argument depends on without citing (`DV-4`) |
| **Historical location ≠ canonical future location** | ✅ §9 untouched; Phase 7 is still removal of a redundant verified copy — ⚠️ **and `DV-1` is the one place that could stop being true** |
| ⭐ **the migration aggregate must not reach COMPLETED while a governed quarantine is undisposed** | ✅ **modelled as a domain-state transition, not filesystem housekeeping** — §0.6.4 and §4.4 both state it, and criterion 20 makes it positively demonstrable. ⭐ *"The block is not a tooling limitation to be engineered away — it is the aggregate refusing an invalid transition."* **That is the correct DDD reading and it is load-bearing, not decorative** |

⛔ **No new bounded context · no ledger · no second authority boundary · no second owner · no new role · `Increment 2` not proposed.** ✅ **Verified.**

---

# 9 · Regression check — `RC-1` … `RC-11`

⛔ **No `RC` item is re-opened.** Checked only where AMD6 could reach: the diff `7d3abc59 → 8307beca` touches §0.4/§0.5/§0.6, §4 (header table, §4.2, §4.3, §4.4, §4.5, §4.7), §8, §10, §11/§11.1 and §12. ⭐ **§1, §2, §3, §3.1, §4.0, §4.1, §4.6, §5, §6 (all of it), §7 and §9 are UNTOUCHED — enumerated mechanically from the hunk ranges, not assumed.**

| | Finding |
|---|---|
| **`RC-1`** phase ordering | ✅ **intact** — `0 · 1 · 2 · 2b-pin · 3 · 2c-commit · 4 · 4b · 5 · 6 · 7`, unchanged; rows still in execution order; no precondition points forward |
| **`RC-4`** final integrity gate | ✅ **intact and strengthened** — the gate survives and its comparison object is now named, recorded and fixed, which is what made it satisfiable. ⚠️ **`DV-1` is adjacent, not a regression: `RC-4` guarded the SOURCE against removal-without-recheck, and that guard holds. The unverified operand is the COPY** |
| **`RC-5`** writer-switch authority transfer | ✅ **intact** — slot 3 is still the instant; §8's boundary is still *"the writer switch"*, explicitly *"and AMD6 does not move it"* |
| **`RC-5b`** dormant resolver | ✅ **intact** — §4 row 8 unchanged: *built, tested, NOT WIRED*; Phase 5 remains the sole activating act |
| **`RC-6`** marker boundary | ✅ **improved** — three markers, directory-level, out-of-band, ⛔ never `*.json`. ⭐ **And §4 row 3's stale *"for the whole 3→5 window"* wording — which the AMD5 review flagged as uncorrected — is now repaired and LABELLED as superseded, outside AMD6's commission** |
| **`RC-8`** byte preservation | ✅ **intact** — §4.1 and §4.6 untouched; re-verified: `.gitattributes` is still `* text=auto` with **no `-text` pin**, and **0 of 18 records contain a `CR` byte** |
| **`RC-9`** canonical comparison | ✅ **untouched** by this diff |
| **`RC-10`** refusal before `mkdir` | ✅ **intact** — §3.1 untouched, still recorded and not implemented |
| **`RC-11`** writer claim | ✅ **intact** — §1.3 untouched; *"ONE WRITER OF GOVERNANCE EVIDENCE"*, superseded heading still labelled |
| **`RC-2` · `RC-3` · `RC-7a/b`** | ✅ **untouched** — §3's table and §4's `INV-ORDER` block unchanged |

---

# 10 · Live-state verification — ⛔ **AMD6 did NOT execute the migration**

**Re-measured independently at review time, not quoted:**

| Check | Result |
|---|---|
| corpus | ✅ **18 records · 216 transitions · 114 grants · 113 unique `grantId`s** — the plan's figures verify exactly |
| `CR` bytes in records | ✅ **0 of 18** |
| `*.tmp*` remnants | ✅ **0** |
| `.gitattributes` | ✅ **`* text=auto`** — ⛔ **no `-text` pin applied** (required at `2b-pin`, correctly not performed) |
| `.gitignore` | ✅ **unmodified** — `.claude/runtime/` still excluded at lines 25 and 32 |
| files changed by `8307beca` | ✅ **6 — all documentation:** the plan, the AMD6 summary, the AMD5 review, `CONTEXT.md`, and two session logs. ⛔ **No runtime code, no `.gitignore`, no `.gitattributes`, no record** |
| durable evidence target | ✅ **does not exist** |
| ⭐ **quarantine store** | ✅ **does not exist** — repo-wide search for any `*quarantin*` directory returns nothing |
| grant registered by this act | ✅ **none** — the four AMD6 grants are the **commission**, pre-existing this delivery |
| migration acts | ⛔ **none.** No file moved or copied as migration · no marker written · no record placed, moved, renamed or copied · no demotion · no removal |
| ⚠️ **the 110 → 114 grant delta** | ✅ **verified and correctly interpreted.** The four new grants are AMD6's own commission, appended by `P-1` into the very corpus the migration freezes and hashes. ⭐ **`RD-10`'s phenomenon and `C-4`'s premise, observed live — and the plan's inference is sound: a literal freeze would have had to prohibit the act that authorized this amendment** |

⚠️ **Corpus counts are treated as OBSERVATIONS, not universal truth** — the corpus has **no git history** (`git log` on `.claude/runtime/workflow/` → empty; `git check-ignore` → `.gitignore:32`), so nothing in it is attestable by anyone. **That is the defect `B′` exists to close, and it is the reason no count in this review is an acceptance criterion.**

---

# 11 · Document integrity

⛔ **This review does NOT record *"no material document-integrity defect found."*** **Three defects; two are material because they land on the acceptance object.**

| | Finding |
|---|---|
| 🔴 **`DV-3`** | ***"the FIRST record in the authoritative store"* is false at four sites, one of which is criterion 17** — the acceptance object. **Not demonstrable as written** |
| ⚠️ **`DV-7`** | **the four-layer trace cites `P5·ALL`, which §4.7 does not define, under a claim that every row resolves** |
| ⚠️ **`DV-4` · `DV-5` · `DV-6`** | **three arguments that do not support the rules they justify** *(termination premise uncited · stale-read bound misattributed · MOVE-refusal reason inverted)*. **Each rule is correct; each stated reason is not the load-bearing one** |
| ✅ | **Duplicate current sections / identifiers:** ⛔ **none.** §4 = `4 · 4.0 · 4.1 · 4.2 · 4.3 · 4.4 · 4.5 · 4.6 · 4.7`, **each exactly once, strictly monotonic** — `DI-1` not regressed, and §4.7 was appended rather than inserted. Every top-level heading `0 … 12` unique |
| ✅ | **Acceptance-reference integrity:** 20 criteria, numbered 1…20, each once; every criterion cited by the trace exists |
| ✅ | **Stale phase numbers:** ⛔ **none current.** Every *"Phase 2b"* occurrence is inside labelled AMD3/AMD5 history — `DI-2` not regressed |
| ✅ | **Conflicting current instructions:** ⛔ **none found.** §4.4 / §8 / §4.7 agree in every branch; §4.7 subordinates itself explicitly |
| ✅ | **Superseded material labelled, not silently rewritten:** ✅ **and this is done unusually well** — the withdrawn *"at NO point"* box, the withdrawn *"for either case"* rule, AMD4's four-step order, AMD5's *"3 GATE EXECUTION"* claim, AMD5's `CASE α`/`CASE β` names, the *"two markers"* row, the *"for the whole 3→5 window"* wording, criterion 12's superseded object, §11.1's superseded rows — **all retained and labelled** |
| ✅ | **Amendment lineage:** coherent — AMD3 `bb1708b7` → AMD4 `0a2fa71d` → AMD5 `7d3abc59` → AMD6 `8307beca`, each with its own traceability block, none rewritten |
| ✅ | **Tables well-formed:** ⛔ **0 mismatched column counts across 52 table blocks** *(re-checked mechanically — the summary's claim verifies)* |
| ✅ | **Tool / edit transcript residue:** ⛔ **none** |
| ⚠️ | **Cosmetic, carried, not in AMD6's commission:** §0.6.2's *"`CASE A`/`CASE B` occur **23 times** in this plan"* was **exact at `7d3abc59`** *(re-measured: 15 + 8)* but is **35** in the delivered artifact, because AMD6 itself added citations. **The measurement is the right basis for the decision; the present tense is stale at delivery.** Also carried unrepaired: §0.4/§0.5/§0.6 are `#` where §0.1–0.3 are `##`, and the duplicated `---` before §0.5 |

---

# 12 · Open questions — preserved, none decided

| | Status | Note |
|---|---|---|
| **`OPEN-M1`** `.gitignore` | ⏳ **OPEN** | ✅ independently confirmed unmodified by `8307beca` |
| **`OPEN-M2`** Phase 1/4 evidence vs the Phase 2 target | ⏳ **OPEN** | ⚠️ **further constrained by `C-4`'s placement decision; NOT decided.** No phase reordered |
| ✅ **`OPEN-M3`** | **CLOSED** (Option A) | ⛔ **not reopened** |
| **`OPEN-M4`** unbounded readers | ⏳ **OPEN** | §1.4 untouched; the marker's advisory reach is restated, not widened |
| **`OPEN-M5`** enforcing form / `AST-016` / `T-14` | ⏳ **OPEN — ⛔ NOT decided here.** ⚠️ **`RD-6` stands: its scope includes `T-14`'s contract, which AMD6 does not address and was not asked to** | §3's reporting-form rows untouched; the migration still executes on the reporting form, so it does not gate |
| **`OPEN-M6`** AMD3 + AMD4 + AMD5 + **AMD6** registration | ⏳ **OPEN — four deep** | ✅ independently verified against the corpus (§3) |
| 🔴 **`OPEN-M7`** quarantine disposer **and** disposal path | ⏳ **OPEN — ⛔ NOT decided here** | ⭐ **correctly split: the act class exists, the disposer is PROPOSED as the existing PO/ARB · Governance pair by extension of §8, and the PATH exists nowhere — with `workflow-state.php`'s missing `DISPOSE` act as measured evidence.** 🔴 **It is the one open item that can block the migration's COMPLETION indefinitely** |
| **`INFO-2`** lane-registration provenance | ⏳ **OPEN** | a Governance matter |
| **`B′` · `R-CONFLICT` · `INV-ORDER` · placement governance · Option D** | **FIXED** | ⛔ **not reopened — verified: §6 byte-identical, §6.1 md5-identical, §9 and §4's `INV-ORDER` block untouched** |

⛔ **Nothing here infers acceptance from the existence of AMD3, AMD4, AMD5 or AMD6.**

---

# 13 · Residual risks

| | Risk | Class | Direction | Gate |
|---|---|---|---|---|
| 🔴 **`DV-1`** | **the durable copy is written at slot `1b` and slot `3(iii)` after its last all-or-nothing verification and is re-verified nowhere; Phase 7's gate compares the source to a recorded enumeration, so a silent copy-side failure passes and Phase 7 removes the source** | design gap | 🔴 ⛔ **UNSAFE — the only one in this chain** | ⭐ **Phase 5 — MUST be repaired before Phase 5 executes** |
| 🔴 **`DV-2`** | **`"the DECLARED pre-switch Phase-5 records"` is a new operand of the Phase-7 comparison object, and §4.0 — untouched — does not require the Phase-0 declaration to contain slot 1's or slot 2's records ⇒ slot `3(ii)` yields a freeze violation in the normal case** | design gap · dependency | ✅ **safe (stop)** | **Phase-0 declaration · acceptance** |
| 🔴 **`DV-3`** | ***"the FIRST record in the authoritative store"*** is false under the plan's own record vocabulary, at four sites including **criterion 17** | overclaim · material | ✅ safe | **acceptance** |
| ⚠️ **`DV-4`** | the `C-4` termination argument's load-bearing premise *(mechanical writes are evidenced by a governance append)* is stated elsewhere and not in the argument | specification | ✅ safe | acceptance |
| ⚠️ **`DV-5`** | §4.3's *"the re-hash has already bounded that"* is false post-`C-4`; the bound is slot `3(i)`–`(iii)`, and the window is absent from the residual-window table | specification | ✅ safe | acceptance |
| ⚠️ **`DV-6`** | the MOVE refusal's stated reason is inverted; the load-bearing reason *(a move makes the source spuriously satisfy criterion 12)* is unstated. **Rule correct and doubly guarded** | specification | ✅ safe | Phase 7 |
| ⚠️ **`DV-7`** | the four-layer trace cites the undefined `P5·ALL` under a claim that every row resolves | integrity | ✅ safe | acceptance |
| ⚠️ **carried, and NOT AMD6's commission** | **`RD-2`** *(slot 3→4 unmarked stale runtime)* · **`RD-4`** *(the AUTHORITATIVE `INV-R1` branch has no pinned coverage — inside Phase 4b)* · **`RD-5`** · **`RD-6`** *(`OPEN-M5`'s scope includes `T-14`)* · **`RD-9`** *(`2c-commit` does not name `.gitattributes`)* · **`RD-10·r1`** *(masking at declared-record granularity)* · **`RD-10·r2`** *(happy-path declared list — ⭐ now enlarged by `DV-2`)* · **`DI-1·r`** *(partly repaired by §4.2's pointer)* | open | — | as recorded by `870305e0` / `ccf6c9c7` |
| ⚠️ **pre-existing, out of scope** | read-modify-write without lock/lease/CAS (`Increment 2`) · no `fsync` *(crash durability explicitly unclaimed, §5)* · unbounded ad-hoc readers (`OPEN-M4`) · the measure→switch, 3→4, 3→5 and final-rehash→removal windows, **each named, bounded and given a §8 row** | out of scope | — | — |

---

# 14 · Verdict

| Residual | Verdict |
|---|---|
| 🔴 **`DI-5` / `RD-1`** Phase-5 normative order | ⭐ **CLOSED** — one mandated block `1 · 1b · 2 · 3 · 4 · 5`; every other site refers to it; slot `1b`'s three outcomes are complete and outcome C stops before any switch; criterion 14 cites a defined step; the abandonment-cost argument is coherent and does **not** overstate slot `3(ii)` |
| 🔴 **`RD-7·b`** marker window | ⭐ **CLOSED** — the false claim is withdrawn and shown, the interval is tabulated per step, the restated claim matches the evidence exactly, and §8's new row defines a safe forward direction and forbids the two wrong moves |
| 🔴 **`RD-3·a`** post-demotion terminating condition | ⭐ **CLOSED** — no re-verification gate; Phase 7 **SUSPENDED**; the store **RETAINED ENTIRE**; all three unsafe readings answered, the first **structurally excluded** rather than prohibited; and the termination is a governance state with an owner, not a dead end |
| 🔴 **`RD-3·b`** quarantine | ⭐ **CLOSED** — store-level, byte-identity-preserving, by location and never by renaming, outside the record glob *(independently verified achievable — the glob is non-recursive)*, a distinct third marker, and Phase 7's refusal stated at four sites. ⚠️ **`DV-6` recorded** |
| 🔴 **`DI-4`** §8 / §4.4 consistency | ⭐ **CLOSED** — one current mismatch model, identical in both sections and in §4.7; superseded wording retained and labelled; `DI-1·r`'s pointer added |
| ⚠️ **`DI-6`** dependency / gate consistency | ⭐ **CLOSED** — **9 rows, re-counted**; grouping sums to 9; **every Gates cell walked and the gate claim genuinely read off the column**; registration/acceptance semantics kept apart; rows current to AMD6; no dependency invented |
| ⚠️ **`DI-7` / `C-1` / `C-6`** terminology | ⭐ **CLOSED** — **§6 byte-identical**, only the newer family renamed, the direction measured rather than assumed, all 9 residual sites labelled history, §8's trigger disambiguated, the forbidden variant absent |
| ⭐ **`C-2` · `C-3` · `C-5` · `C-8` · `C-9` · `C-10`…`C-12`** | ⭐ **CLOSED as recorded** — and `C-8`, `C-9` and `C-12` were verified against primary evidence rather than accepted as recorded *(§4.5, §7, §6.3)*. ⚠️ **`DV-7` recorded against the `C-12` trace** |
| 🔴 ⭐ **`C-4` / `C-7`** Phase-5 evidence placement | 🟡 **ADDRESSED BUT NOT CLOSED** — ⭐ **the decision is architecturally sound, genuinely derived for six of seven rows, and the termination argument holds.** ⛔ **But it introduces `DV-1` (unsafe direction), depends on an unspecified operand (`DV-2`), and rests on a claim that is false as written (`DV-3`)** |

## 🟡 **OVERALL: PASS WITH DESIGN CLARIFICATIONS**

⛔ **NOT BLOCKED — and the four conditions were tested one by one, not assumed:**

| Condition | Finding |
|---|---|
| **AMD6 introduces a technically unsafe property** | ⚠️ **This is the close call, and it is recorded as one rather than smoothed over.** `DV-1` is a real unsafe-direction gap on the path to the irreversible act. **Three things keep it out of `BLOCKED`:** *(1)* the copy-side write **pre-dates AMD6** — `RD-10`/§4.0 created the reconciliation act; AMD6 added the carry-across and the claims about it; *(2)* ⭐ **AMD6's specification is what makes the gap findable at all** — before it, Phase-5 placement was unspecified, so the finding could not be stated, and penalising the amendment that made a latent gap legible would invert the incentive this whole chain runs on; *(3)* the repair is **one added verification inside the current envelope**, requiring no decision, no reordering and no new actor. ⛔ **It is nonetheless an ABSOLUTE pre-Phase-5 condition, recorded in §5.5 and §13, and it must not be read as a wording nit** |
| **a required execution gate is impossible** | ⛔ **no.** `DV-2` can make slot `3(ii)` fail in the normal case **if** the Phase-0 declaration is written narrowly — but it fails **safe** (stop before any switch, nothing destroyed), the declaration is an explicitly named PO/ARB dependency, and the cure (`RD-10·r2`'s class-based form) is already recorded. ⭐ **And `C-4` genuinely makes criterion 12's *"identical"* branch reachable, which is the gate `RD-10` had rendered unreachable** |
| **a fixed architectural decision is violated** | ⛔ **no, and this was verified rather than reasoned.** §6 **byte-identical** · §6.1 **md5-identical** · `B′` intact · `INV-ORDER` untouched · `OPEN-M3` Option A untouched · placement governance and Option D unchanged · no phase reordered · no second authority boundary, owner, role or ledger · the aggregate key used exactly as registered and **no aggregate created**. ⭐ **Quarantine is `R-CONFLICT`-compliant as argued, and `RD-3·b`'s Phase-7 exposure — which the AMD5 review left open — is now closed by the retention rule** |
| **the migration would remain unsafe under AMD6** | ⛔ **no, as things stand.** `DV-1` is reachable only through Phase 5, and Phase 5 cannot begin: Phase 4b, the Phase-0 declaration, `OPEN-M6` registration and plan acceptance are **all outstanding and all belong to other actors**. ⭐ **Measured against AMD5, AMD6 makes the design safer: it removes the branch that promised an undefined route to removal, gives quarantine a location and a retention rule, makes the Phase-7 comparison object reachable, and withdraws two overclaims** |

⛔ **`OPEN-M7` and `OPEN-M5` remaining open is NOT a reason to block, and neither is treated as one here.**

⚠️ **Repairs required, in the order their gates fall:**

```
BEFORE PHASE 5   🔴 DV-1  re-verify the durable copy (Phase-4 semantics, all-or-nothing)
                          after slot 1b and after slot 3(iii), BEFORE slot 3(iv),
                          and make it criterion 18's demonstrating act
BEFORE PHASE 0   🔴 DV-2  §4.0 must require the declared set to cover the Phase-5
                          pre-switch record classes (slots 1, 1b, 2) — class-based form
BEFORE ACCEPTANCE 🔴 DV-3  restate "the FIRST record" as "the first governance append
                          into the authoritative store after the writer switch" (4 sites,
                          including criterion 17)
                 ⚠️ DV-4  cite the evidencing premise inside the termination argument
                 ⚠️ DV-5  attribute the stale-read bound to slot 3(i)–(iii); add the window
                 ⚠️ DV-7  resolve or define P5·ALL
AT PHASE 7       ⚠️ DV-6  state the load-bearing reason the MOVE is refused
```

⭐ **Every one is a wording, sequencing or enumeration repair by the plan's owner inside the current envelope. None requires a decision. None reopens anything. `DV-1` is the only one that changes what an executor must DO.**

⛔ **`PHASE 3 MUST NOT BEGIN`** — and not because of this verdict: `OPEN-M6` registration, the Phase-0 freeze declaration *(now carrying `RD-10`'s expected-delta list and `DV-2`'s addition)*, the Phase-4b authorization *(with `RD-4` still open inside it)*, `OPEN-M7` and plan acceptance are all outstanding, and **every one belongs to another actor.**

---

# 15 · Explicit non-decisions and limitations

⛔ **This review did NOT:** modify AMD6 · modify the AMD6 summary · modify the migration plan · register AMD6, AMD5, AMD4 or AMD3 · accept AMD6 · call anything adopted · execute or begin any migration act · modify runtime code · touch `.gitignore` or `.gitattributes` · create a quarantine store or a durable target · decide `OPEN-M5` or `OPEN-M7` · name or create a disposer · reopen `B′`, `R-CONFLICT`, `INV-ORDER`, `OPEN-M3` or `RC-1`…`RC-11` · reorder any phase · write or propose any remedy text.

⚠️ **Limitations, stated rather than implied:**
- the reviewer's identity is **self-declared and not attestable** (`INV-ATTR-2`/`G-2`);
- findings rest on the artifact plus the repository at `8307beca`, **not on any execution** — nothing here has been observed running;
- the corpus has **no git history**, so its content is unattestable by anyone — **the defect `B′` exists to close**, and the reason every count above is an observation;
- I did **not** run the contract suites and did not re-derive `RC-2`/`RC-3`'s test evidence, because `RC-1`…`RC-11` are closed background here;
- `DV-1` and `DV-2` are derived from the specification and the criteria **as written**, not observed in execution — `DV-1` requires a silent copy-side write failure to fire, and `DV-2` requires the Phase-0 declaration to be written from §4.0's illustrative list;
- `DV-3`'s falsity depends on the plan's own record vocabulary (§1.1's counting and §4 row 2's `*.json` predicate); under a different definition of *"record"* it would be a looser overclaim rather than a false one, **but not a true statement under any reading I could construct**;
- the `C-6` forbidden variant was verified absent **without being reproduced**, so that verification is negative evidence only.

**Next actors, in order — unchanged by this review:**

```
this review  (independent Architecture, claude-code-session:dd639043)
      ↓   ⭐ the §0.4.1 bound is discharged for the commissioned set: findings ccf6c9c7,
      |      remedies bc1b47ef, judgement here — and this review authors no remedy,
      |      so it hands no new self-review exposure forward
      ↓
GOVERNANCE BOUNDED REVIEW                       (C-11)
      |   completeness · provenance · amendment lineage · current/superseded integrity ONLY
      |   ⛔ NOT technical, design-soundness or migration-safety verification
      |   ⚠️ if the reviewer is b64828fe, C-11's verbatim disclosure is MANDATORY
      ↓
Governance registers AMD3 + AMD4 + AMD5 + AMD6  (OPEN-M6)
      ↓
PO/ARB — acceptance (⚠️ DV-1 · DV-2 · DV-3 first) · OPEN-M5 if it chooses (⚠️ RD-6)
         ⭐ OPEN-M7 — or Phase 7 has no exit
         the PHASE-0 FREEZE DECLARATION with its EXPECTED-DELTA LIST (RD-10, C-4, ⚠️ DV-2)
         the PHASE-4b AUTHORIZATION (⚠️ RD-4)
      ↓
Migration execution — beginning at PHASE 0, never at Phase 3   (⚠️ DV-1 before Phase 5)
      ↓
Post-migration verification
```

**Traceability:** AMD6 at **`8307beca`** *(plan 833 → **1206** lines; 6 files changed, all documentation)* · AMD5 `7d3abc59` · AMD4 `0a2fa71d` · AMD3 `bb1708b7` · technical review `a282d14b` · accepted design `ae451db9` · **the four REGISTERED AMD6 commission grants**, read from the corpus · **the INDEPENDENT AMD5 review by `claude-code-session:ccf6c9c7`** — `RD-7·a`/`RD-7·b` (its §4), `RD-3·a`/`RD-3·b`/`RD-3·c` (its §5), `DI-3·a`/`DI-3·b` (its §9), its §11 interruption table, §12 `DI-4`…`DI-7`, §15 residuals, §16 verdict, **§18 commission note** · the independent AMD4 review by `870305e0` and the independent AMD3 review by `9c908e70` *(background; `RC-1`…`RC-11` closed, not reopened)*.

**Primary evidence re-derived here, not quoted:** `git diff 7d3abc59 8307beca` — **hunk ranges mapped to sections**, establishing that §1, §2, §3, §3.1, §4.0, §4.1, §4.6, §5, §6, §7 and §9 are **untouched** · **§6 in full is BYTE-IDENTICAL** across the amendment and **§6.1 is md5-identical** · plan headings enumerated mechanically — §4 = `4 · 4.0 · 4.1 · 4.2 · 4.3 · 4.4 · 4.5 · 4.6 · 4.7`, each once, monotonic · §10 = **20 criteria, 1…20, each once** · §11.1 = **9 data rows**, grouping sums to 9, **every Gates cell walked** · §4.3's placement table = **7 data rows** · **52 table blocks, 0 with mismatched column counts** · §4.7 defines **9** operator IDs and the trace cites a **tenth (`P5·ALL`) that does not exist** · every `re-verify`/`all-or-nothing` site enumerated *(the basis of `DV-1`)* · name counts re-measured at the AMD5 baseline — `CASE A` **15** + `CASE B` **8**, `CASE α` **5** + `CASE β` **6** — and in the delivered artifact — **20 + 15** · **all 9 residual `CASE α`/`CASE β` sites walked, every one labelled history** · `session-resolve.php:130` = `glob($recordDir . '/*.json')`, **non-recursive** · `workflow-state.php` transition vocabulary `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL` — ⛔ **no `DISPOSE`** · `.gitattributes` = `* text=auto`, **no pin** · `.gitignore:25,32` · **corpus re-measured 2026-08-21: 18 records · 216 transitions · 114 grants · 113 unique `grantId`s · 0 records contain `CR` · no `*.tmp*` · `git log` empty · no durable target · no quarantine store · no `-AMD3`/`-AMD4`/`-AMD5` grant** · the canonical aggregate read from its own `workItem`/`workflow` fields, **11 grants · 6 transitions, all four AMD6 grants inside it** · `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, **exit 0** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2` · `ES-005.4`.

**INDEPENDENT AMD6 REVIEW DELIVERED · STOPPING.** ⛔ **AMD6 IS NOT REGISTERED, NOT ACCEPTED, AND NOT ADOPTED BY THIS REVIEW.** ⛔ **THE MIGRATION IS NOT EXECUTED, NOT AUTHORIZED, AND MUST NOT BEGIN.**
