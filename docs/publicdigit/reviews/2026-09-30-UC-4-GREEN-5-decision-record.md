# UC-4 / `GREEN-5` — **PO/ARB DECISION RECORD** (recording surface)

**Work item:** `EM-IMPL-002` · **Epic:** `PBDIGIT-EPIC-03` · **Date prepared:** 2026-09-30
**Prepared by:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Evidence base:** `2026-09-30-UC-4-GREEN-5-scoping-decision-brief.md` · `PBDIGIT-73` (`7ee452ee0`, with its §9 corrections)

> ⛔ **THIS PROCESS IS NOT THE PO/ARB AND DECIDES NOTHING.** The decision blocks in §4 and §5 are **BLANK** and are the PO/ARB's to fill. This surface prepares them; it does not complete, infer or pre-fill either one. **Analysis is not repeated here** — it lives in the brief. *(Modelled on `2026-08-18-EM-DOM-001-decision-recording-surface.md`: "This surface is only the place where a decision is written down.")*
>
> ⛔ **A and B are NOT ranked.** No recommendation is offered, and neither "smaller/cheaper/faster" nor "architecturally cleaner" appears as an argument anywhere below. Per the commission: **evidence → consequences → decision record → STOP.**

---

> # ⚠️ CORRECTION NOTICE — READ BEFORE FILLING ANY BLOCK
>
> **A fresh-context cross-check (2026-09-30) found three material errors in this record's own evidence. Each was then re-verified from source by this process before being recorded. They change the character of the decision, and two of them touch the text below.**
>
> - **`M-1`** — §2's row *"UC-4 UNAUTHORIZED … no authorization record names it"* is **WRONG**. UC-4 is **authorized-but-HELD**. ⇒ **whether Option A is an *amendment* or a *hold release* is now genuinely open, and §4 prejudged it.**
> - **`M-2`** — the binding RED pin is a **FIFTH** test, **scope-wide over every handler file**. *"Assume present"* does **not** exempt UC-4 from it. §4's *"the existing four RED tests … already match this scope"* is incomplete.
> - **`M-3`** — UC-4's **first RED may be unreachable for a reason independent of the gate question**. ⇒ §8's Option-A path *"existing four REDs → GREEN"* is **not achievable as written**.
>
> **Full detail, with citations: §11.** Nothing in §2/§4/§8 was deleted; the affected claims are marked inline.

---

## 1 · Decision

> ### `UC-4 / GREEN-5 gate assumption`

---

## 2 · Existing state

| Fact | State |
|---|---|
| **UC-4 / `GREEN-5`** | ⚠️ **SUPERSEDED — see §11 `M-1`: authorized-but-HELD, not unauthorized.** *(as originally written:)* 🔴 **UNAUTHORIZED** and unimplemented — `ReportPeriodExpiryHandler.php:33` throws `BadMethodCallException('EM-IMPL-002 GREEN-5 pending: UC-4 behaviour is not implemented yet.')` |
| **`H-2`** | ✅ **COMPLETE** — RED → GREEN → independently verified (2026-08-19). Re-run 2026-09-30: OK, 3 tests / 21 assertions. **Not to be reopened.** |
| **`ADR-1`** (`ADR_20260817_2145_Aggregate_Absence_Semantics`) | ✅ **DECIDED** — PO/ARB 2026-08-18, governed composite absence semantics |
| **`ADR-1` Rule-8 gate** | ⛔ **BLOCKED** — *"THE NORMALIZATION SLICE IS BLOCKED. A SEPARATE DOMAIN SLICE IS REQUIRED FIRST."* The contract §6(b) requires does not exist |
| **`BND-1`** (lifecycle-phase ownership) | ⏸️ **DEFERRED** — `D3`, PO/ARB 2026-08-18. It is gate **Q2**: no contract discriminates *required-but-absent* from *not-yet-reached-phase* |
| **`BND-3`** (overlay boundary) | ⏸️ **DEFERRED** — `D4`, *"not before `BND-1`"* |
| **`ADR-1` §6(c) normalization slice** | scope authorized for **UC-1, UC-2, UC-3 only** — ⛔ **UC-4 is not in scope** |

**Label discipline (`PBDIGIT-73`).** `GREEN-5` is overloaded and the two meanings stay distinguished by name:

- **UC-4 / `ReportPeriodExpiry` slice** — the code's `GREEN-5`; the subject of this decision.
- **`ADR-1` §6(c) `AcceptanceDecision` normalization slice** — what the Act-B design map called `GREEN-5`; **not** the subject of this decision.

⛔ **Historical RED guards are not renumbered.**

---

## 3 · Question

> # **May UC-4 (`ReportPeriodExpiry`) assume that an established `AcceptanceGateDecision` exists, as an explicit scope boundary / command precondition?**

**Supporting fact (verified, both readings preserved):** all four UC-4 RED tests establish a **present** gate via `seedGate()` (`OperatingCoreApplicationTestCase.php:128-138`); **none** exercises the repository returning `null`. ⚠️ **And that does not by itself show UC-4 is absence-safe** — the binding pin cannot flag an unimplemented method body, and the fixtures do not establish that UC-4's behaviour needs the gate either way (brief §2.2, §2.4).

---

## 4 · OPTION A — *YES, UC-4 may assume an established `AcceptanceGateDecision` exists*

### Consequences if selected

1. The assumption is a **scope boundary for UC-4**, recorded as a **command precondition**.
2. It is **NOT** an Application-layer interpretation of `null`. `ADR-1` §6(b) and the `6-guard` continue to forbid a handler classifying the cause of absence; the prohibited shape `if ($gate === null) { /* decide it */ }` remains prohibited.
3. It does **NOT** resolve the two absence meanings in `ADR-1` §6(a).
4. It does **NOT** resolve `BND-1`.
5. It does **NOT** resolve `BND-3`.
6. It does **NOT** authorize the `ADR-1` §6(c) normalization slice — that remains BLOCKED at its Rule-8 gate.
7. It does **NOT** authorize UC-4 implementation by itself.
8. UC-4 **still requires its own explicit implementation authorization**.

### ⚠️ This option AMENDS the recorded Rule-8 sequence

> ⚠️ **SUPERSEDED IN CHARACTER — see §11 `M-1`.** This heading asserts "amendment". Because UC-4 proves to be **authorized-but-held**, the classification *amendment* vs *hold release* is **open and is itself part of what the PO/ARB decides**. The paragraph below is retained as originally written.

The `ADR-1` Rule-8 gate §5 records:

```
ADR-1 signed
  → Rule-8 dependency gate
  → PO/ARB authorizes DOMAIN slice
  → domain RED / implementation / verification
  → normalization slice for UC-1/UC-2/UC-3
  → AbsentAggregateReferenceRedTest becomes GREEN
  → GREEN-5
```

`GREEN-5` is recorded **last**. Selecting Option A moves UC-4 / `GREEN-5` **earlier** than that. **This must not be treated as a local engineering clarification.** If Option A is selected, the decision record must carry the amendment sentence in §4.1 below.

### 4.1 · Required amendment sentence (part of Option A, not optional)

> *"The Rule-8 sequence is amended only to permit the UC-4 slice to proceed under the recorded 'established gate exists' precondition."*

### 4.2 · Residual risk, stated as part of the option

If UC-4's implementation proves that absence of `AcceptanceGateDecision` is **reachable and semantically relevant**, the slice **STOPS immediately**, invents no semantics, and returns to governance under `ADR-1` §6(b).

### ⬜ DECISION BLOCK A — **BLANK**

```text
[ ] SELECTED — Option A

PO/ARB, in your own words:




Required amendment sentence (§4.1) — include verbatim if A is selected:
"The Rule-8 sequence is amended only to permit the UC-4 slice to proceed
 under the recorded 'established gate exists' precondition."

Signed / date:
```

---

## 5 · OPTION B — *NO, UC-4 may not assume a present gate; the absence case must be defined first*

### Consequences if selected

1. **UC-4 remains blocked.**
2. **The existing Rule-8 sequence remains unchanged** — no amendment is made.
3. **`BND-1` becomes the relevant next governance/domain dependency** — it is gate Q2, the missing discriminator.
4. The next act is a **separately authorized DOMAIN slice**.
5. **No fallback semantics may be invented.**
6. **No** sentinel, `Unknown`, exception interpretation, lifecycle interpretation or application-side classification may be introduced. `ADR-1` §4's shape question remains open.
7. **UC-4 remains unauthorized.**

⛔ **The missing domain contract is not designed, named or placed by this decision.**

### ⬜ DECISION BLOCK B — **BLANK**

```text
[ ] SELECTED — Option B

PO/ARB, in your own words:




Signed / date:
```

---

## 5a · ⬜ STATE C — clarification requested before deciding

```text
[ ] CLARIFICATION REQUESTED

What is unclear / what further evidence is required:




Signed / date:
```

---

## 6 · Decision owner

> ## **PO/ARB.**

Engineering supplied evidence and consequences. **It did not choose, did not rank A against B, and did not recommend.** *(`R-34`/`EP-02` — engineering never accepts its own work.)*

---

## 7 · Engineering authorization

> ## ⛔ **NOT AUTHORIZED** — and remains so until **both**: (i) the PO/ARB decision is recorded above, **and** (ii) the implementation slice is **separately** authorized.

A recorded decision on §3 is **not** implementation authorization. The two are separate acts.

---

## 8 · What happens after the decision

### If Option A is selected

The next stage is a **separate authorization step for UC-4 / `ReportPeriodExpiry`**. Only after that explicit authorization:

```
existing four REDs → implementation → GREEN → independent verification → STOP
```

The existing four UC-4 RED tests **remain authoritative** unless a separately authorized change says otherwise. ⚠️ **SUPERSEDED in part — see §11 `M-2` (a fifth, scope-wide pin also binds) and `M-3` (the first RED may be unreachable).** **If implementation discovers that absence of `AcceptanceGateDecision` is reachable and semantically relevant: STOP immediately, invent no semantics, return to governance under `ADR-1` §6(b).**

### If Option B is selected

⛔ **Do not touch UC-4.** The next stage is preparation of the **separately authorized DOMAIN slice** addressing the missing discriminator / `BND-1`. That is a different bounded slice.

### If State C is recorded

Engineering answers the clarification and returns here. **No implementation.**

---

## 9 · Independence status of the evidence — stated, not claimed away

⚠️ **The evidence base was NOT independently validated.** Both `PBDIGIT-73` and the decision brief were produced by **this same process**, which then re-checked its own work and found four errors in it (`PBDIGIT-73` §9 · brief §2.6). **A later session is not independence**, and this record does not claim otherwise.

**If the PO/ARB wants independent validation before deciding**, it requires a **fresh lane that is not this process**, which must: read the repository directly · read `ADR-1` and its Rule-8 gate · read the decision brief · verify the exact decision question · **verify whether Option A really constitutes an amendment to the recorded sequence** · report contradictions · **make no decision** · **implement nothing**.

⚠️ **Note on mechanism, recorded because this estate measured it:** a **subagent is not an independent lane** — an empirical probe (2026-08-22) established that a subagent reports its **parent's** `CLAUDE_CODE_SESSION_ID`, so it *is* the parent session and fails the identity bar. Genuine independence requires a **separate session the human starts**. Any in-session re-derivation is a **fresh-context cross-check**, which is useful against inherited framing but is **not** governance independence, and is labelled as such wherever it appears.

---

## 10 · Explicit non-actions taken in preparing this record

⛔ UC-4 not implemented · `ReportPeriodExpiry` not implemented · no RED test changed · no RED test created · `AcceptanceGateDecision` not modified · no domain contract created · `BND-1` not resolved · `BND-3` not resolved · `ADR-1` not modified · `ADR-2` not modified · Act B not reopened · `GREEN-*` stages not renumbered · `PBDIGIT-72` not modified · `EM-GOV-063` not made reachable · aggregate ownership unchanged · persistence architecture unchanged · unrelated OperatingCore RED guards not fixed · the stale `AbsentAggregateReferenceRedTest` docblock **not repaired** (deliberately out of scope for this decision) · `app/` and `tests/` **byte-untouched**.

---

> ## **Evidence is not a decision. A decision is not authorization. Authorization is not implementation. Implementation is not verification.**

**Traceability:** decision brief `2026-09-30-UC-4-GREEN-5-scoping-decision-brief.md` · `PBDIGIT-73` + §9 corrections · `ADR_20260817_2145` §6(a)(b)(c)(d)/`6-guard`/constraints ①–⑨ · `2026-08-18-EM-IMPL-002-rule8-gate-adr1.md` §1/§5 · `2026-08-18-EM-DOM-001-decision-recording-surface.md` (`D1`–`D4`, and the surface pattern this record follows) · `PBDIGIT-72` · `ReportPeriodExpiryHandler.php:33` · `ReportPeriodExpiryHandlerRedTest.php` · `OperatingCoreApplicationTestCase.php:128-138` · `R-34`/`EP-02` · Rule 8

---

## 11 · ⚠️ CORRECTIONS to this record's evidence (`ES-004.3`, additive)

**Provenance, stated plainly.** A **fresh-context cross-check** was run on 2026-09-30 against the primary sources, instructed to read this record and the brief **last**. It reported these. **Each was then re-verified from source by the preparing process before being written here** — they are recorded on the strength of that re-verification, not on the cross-check's assertion. ⚠️ **That cross-check is NOT governance independence** (§9: a subagent carries its parent's session identity); it is a check against inherited framing.

### `M-1` · UC-4 is **authorized-but-HELD**, not unauthorized — and this reopens the amendment question

**What this record said (§2, §7) and the brief said (§1):** *"UC-4 / `GREEN-5` is NOT AUTHORIZED — no authorization record names it."*

**Verified position:**

| Evidence | Source |
|---|---|
| *"I grant the Implementation lane authority to execute **EM-IMPL-002: the Increment-2 Application Layer** as bounded by the authorized EM-ARCH-002 proposal…"* | `2026-08-17-EM-ARCH-002-boundary-authorization-registration.md:19` (PO/ARB verbatim) |
| The bounding proposal **names UC-4 in scope** — UC-4 row, UC-4 effects row, §8d UC-4 flow, and *"one handler per governed act (**UC-1…UC-4**, + UC-5/UC-6 if confirmed)"* | `2026-08-17-EM-ARCH-002-increment2-application-layer-proposal.md:61, :114, :251, :281` |
| *"GREEN-4 ACCEPTED and NOT reverted. ⏸️ **GREEN-5/6/7 ON HOLD** until three items close."* | `2026-08-17-EM-IMPL-002-architecture-hold.md:3` |
| *"Proceed to GREEN-5 immediately — ❌ **NOT YET**"* | `2026-08-17-EM-IMPL-002-red-amendment-approval.md:10` |

⇒ **UC-4 falls inside a granted scope and is HELD.** Both phrasings — *"on hold"*, *"not yet"* — presuppose granted-but-withheld work, not unauthorized work.

> ### 🔴 **Consequence for the decision itself, and it is the important one.**
> This record's §4 asserts that Option A **amends** the Rule-8 sequence. That framing assumed UC-4 was unauthorized. **If UC-4 is authorized-but-held, permitting it may instead be a HOLD RELEASE — which is a different governance act with a different weight.** The corpus contains a vocabulary precedent for exactly this distinction: a comparable question was recorded as *"a clarification, **not** an amendment"* (`2026-08-18-EM-DOM-001-rule8-gate-post-decisions.md:30`).
>
> ⛔ **The preparing process does not classify it.** **Whether Option A is an amendment or a hold release is now part of what the PO/ARB decides**, and §4.1's amendment sentence should be included **only if the PO/ARB classifies it as an amendment.** No document in the corpus defines what constitutes an amendment to a Rule-8 "consequent sequence", and none classifies this case.

### `M-2` · The binding RED pin is a **FIFTH** test and is **scope-wide** — *"assume present"* does not exempt UC-4

**Verified:** `AbsentAggregateReferenceRedTest.php:54` sets `HANDLER_DIR` to the handler directory and `:99` globs `HANDLER_DIR . '/*.php'` — **every** handler file, which includes `ReportPeriodExpiryHandler.php`. The pin triggers on a **`find()`-plus-dereference shape**, not on absence being *reachable*.

So under Option A, a UC-4 implementation faces this, derived from the pin's and the ADR's own text:

| If the implementation… | Then |
|---|---|
| dereferences a `find()` result with **no guard** | it **adds a violation** to an already-failing pin |
| guards with `?? throw` | that is the shape `ADR-1` §6(b) **expressly prohibits** (and is what UC-1/UC-2 do today, which the Rule-8 gate §3 says normalization cannot generalise) |
| guards with an **early return** | that classifies gate absence as a *legitimate lifecycle state* — which the **`6-guard`** forbids absent an authorized domain contract |

⇒ **§4's *"the existing four RED tests … already match this scope"* is incomplete.** The pin is a fifth binding test, it is currently RED, and UC-4's handler is inside its scope the moment it has a body.

### `M-3` · UC-4's **first RED may be unreachable** for a reason independent of the gate question

**Verified:** test 1 (`test_an_expired_halted_recovery_report_appends_the_terminal_consequence`) asserts `RecoveryPeriodExpired` with `recoveryFailed` and rendering *"Election Discontinued"*. That event is produced only by `ExpiryConsequence::onHaltedRecoveryExpiry(...)`, whose signature **requires `HaltedAtGate $halt`** (`ExpiryConsequence.php:38-45`). And the already-implemented UC-3 handler records why no caller can supply one:

> *"P-7 … takes a `HaltedAtGate`, and **NO port in the authorized six-port universe can supply one** — the protocol is append-only (no read side), AG-3 carries no gate, and AG-2 records no halt. This handler therefore does NOT call P-7 and **does NOT construct a `HaltedAtGate` (constructing one would invent the halt fact)**."*
> — `FillCommitteeSeatHandler.php:77-82`

⇒ **§8's Option-A path — *"existing four REDs → implementation → GREEN → independent verification"* — is NOT achievable as written.** This is the **converse** of what §8 and the brief §8 already said about `PBDIGIT-72`: they correctly recorded that authorizing UC-4 would not make `EM-GOV-063` reachable, but **neither stated that the same missing producer blocks UC-4's own first RED.**

⚠️ **This does not decide anything.** It means Option A, if chosen, should be scoped with its eyes open: either UC-4's slice is bounded to the REDs it *can* satisfy, or the `HaltedAtGate` producer gap is addressed first — and **that gap is `PBDIGIT-72`'s second, independent cause, which this decision does not touch.**

### Minor corrections

- **`m-4`** — *"all four UC-4 RED tests"* **undercounts the exercises.** There are **seven** UC-4 handler exercises suite-wide: the four in `ReportPeriodExpiryHandlerRedTest`, plus `HistoryKindAssignmentRedTest` (scenarios E and F) and `RefusalTaxonomyRedTest`. **All seven establish a present gate; none exercises `null`** — so the substantive conclusion stands and strengthens, but the count in §3 and in the brief is wrong.
- **`m-5`** — `PBDIGIT-73`'s framing that the design map *"says `GREEN-5` = the normalization slice"* **overstates it.** `…act-b-domain-design-map.md:276` is a **table row listing two items that share a blocker**, not an equation; the same document treats them as **distinct** at `:240` and `:308`. The label is overloaded **in planning prose**; the design map does not itself define `GREEN-5` as the normalization slice. **The label-collision finding survives** (the code says UC-4; `ADR-1` §6(c) scopes normalization to UC-1/2/3) — only its attribution to the design map was too strong.
- **`m-6`** — `PBDIGIT-73` cites commit `8ea13835a` for `FillCommitteeSeatHandler`. **Wrong:** that file is UC-3/GREEN-4 and landed in **`4651a3e76`**; `8ea13835a` is GREEN-2/UC-1.

### What survives unchanged

✅ Code arc `GREEN-5`=UC-4 · `GREEN-6`=UC-5 · `GREEN-7`=UQ-1…4 · ✅ `ADR-1` DECIDED 2026-08-18 · ✅ its Rule-8 gate **BLOCKED** · ✅ §6(c) scoped to **UC-1/UC-2/UC-3 only**, UC-4 outside it · ✅ every UC-4 exercise seeds a **present** gate, none pins absence · ✅ `AcceptanceGateDecision` in **six** application files · ✅ the pin's two current sites and its blindness to an unimplemented body · ✅ `H-2` COMPLETE · ✅ `BND-1`/`BND-3` DEFERRED · ✅ the stale pin docblock (`:38-39`).

### The decision question is unchanged

§3 stands exactly as written. **What changed is its character** (`M-1`) and **two feasibility facts Option A must be chosen with** (`M-2`, `M-3`). ⛔ **Still no option selected, no ranking, no recommendation.**

---

## 12 · ⚠️ `M-7` — the decision question maps onto only PART of UC-4 (new evidence, 2026-09-30)

**Two different "gate" facts are in play, and conflating them has been distorting the question.**

| Fact | What it is | Obtainable today? |
|---|---|---|
| **`AcceptanceGateDecision`** (AG-2) | the established constitutional decision | ✅ **yes** — held in AG-2, seeded by `seedGate()`. **This is the subject of §3's question.** |
| **`HaltedAtGate`** | the *halt* fact | ⛔ **no** — *"NO port in the authorized six-port universe can supply one"* (`FillCommitteeSeatHandler.php:77-82`) |

**Where the gate decision actually enters UC-4 — verified chain:**

```
onHaltedRecoveryExpiry(…, OperationalCondition $operational)   refuses if Inoperative
        ← $committee->unableToFunction(RequiredVotes $required)      (ElectionCommittee.php:125)
              ← $required = $decision->requiredVotes()               (AcceptanceGateDecision.php:117)
                    ← thresholdRule->requiredVotesFor($constitutedSize)
```

⇒ **The `AcceptanceGateDecision` is needed for the `¬Inoperative` precondition, which the authorized flow scopes to the HALTED branch only.** `§8d` of the authorized proposal shows the handler reading **`find(AG-3)`** and applying *"preconditions: kind · **¬Inoperative (halted branch)** · ¬recoverySucceeded · isExpiredAt"* — **no AG-2 lookup appears in the authorized flow at all.**

**And the restoration branch needs neither fact.** `ExpiryConsequence::onRestorationExpiry()` takes only `ElectionId`, `RecoveryProcess`, `RecordedInstant` (`:73-90`) — no gate decision, no halt.

### Consequence, stated as evidence

| UC-4 branch | Needs `AcceptanceGateDecision`? | Needs `HaltedAtGate`? |
|---|---|---|
| **Restoration expiry** (RED test 2) | **No** | **No** |
| **Halted-recovery expiry** (RED test 1) | **Yes** — via `¬Inoperative` | **Yes** — and it is unobtainable |
| **Refusal paths** (RED tests 3, 4) | test 4 is the halted branch ⇒ yes; test 3 depends on whether the handler pre-checks `isExpiredAt` before calling P-6, which `§8d` places on the handler side | ⚠️ **undetermined** — the handler is unimplemented, so this is design detail for the UC-4 slice, not established here |

> ### 🔴 **A scoping option neither this record nor the brief contained**
> **An Option-A slice bounded to the RESTORATION branch would require neither the `AcceptanceGateDecision` nor the `HaltedAtGate`** — it sidesteps `M-3` entirely and most of §3's question.
>
> ⛔ **This is offered as evidence, not as a recommendation, and not as a third option.** The preparing process does **not** propose it, rank it, or suggest the slice be scoped that way. **Whether UC-4 is commissioned whole, bounded to a branch, or not at all is the PO/ARB's to decide** — and bounding a slice by branch is itself a scoping act requiring its own authorization.

### Effect on §3

**§3's question is unchanged in wording but narrower in reach than it appears:** it governs the **halted branch's `¬Inoperative` precondition**, not UC-4 as a whole. A PO/ARB answer of *"yes, assume present"* would not make the halted branch implementable (`M-3`: the halt fact is still unobtainable), and a *"no"* would not block the restoration branch (which needs no gate).

⛔ **No option selected. No ranking. No recommendation.**

---

## 13 · ⚠️ AUDIT CORRECTIONS to §11 and §12 — separate-session audit of `M-1`, `M-2`, `M-3`, `M-7` (`ES-004.3`, additive)

**Provenance, stated plainly.** Recorded 2026-09-30 by `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578`, a **separate session the human started** (not the preparing process `84c0f6f6…`, not a subagent of it). ⚠️ **Self-declared, not attestable** (`INV-ATTR-1/2`, `G-2`) — §9's independence bar is met in *mechanism* only as far as this declaration can be trusted. The audit read the repository and governing documents directly. It **implemented nothing, changed no test, and decided nothing**. **Nothing above is deleted or rewritten**; this section corrects by addition.

**Test state, run by this audit (read-only):** `AbsentAggregateReferenceRedTest` **FAILS** — exactly `FillCommitteeSeatHandler.php: $committee` and `$decision`. `ReportPeriodExpiryHandlerRedTest` — **4 errors**, all `BadMethodCallException` (`ReportPeriodExpiryHandler.php:33`). UC-4 handler exercises suite-wide: **7** (4 + 2 + 1) — ✅ `m-4` confirmed.

### `A-1` · `M-1` — **confirmed, with one uncited source**

✅ Confirmed: the grant (`…boundary-authorization-registration.md:19`, signed §3b) · UC-4 named in the bounding proposal (`:61`, `:114`, `:251`, `:281`) · the hold (`architecture-hold.md:3`) · *"NOT YET"* (`red-amendment-approval.md:10`) · the *"a clarification, not an amendment"* precedent (`rule8-gate-post-decisions.md:30`).

⚠️ **Not cited in §11:** the hold's own ordered steps end *"→ **only then GREEN-5 authorization.**"* (`architecture-hold.md:34`). The hold record therefore itself anticipates a further **authorization act** for GREEN-5. This is evidence bearing on the *amendment* vs *hold-release* classification; it does **not** settle it. It is consistent with §7 (separate authorization required).

### `A-2` · `M-2` — **mechanics confirmed; the table attributes ADR prohibitions to the pin**

✅ Confirmed: `HANDLER_DIR` (`AbsentAggregateReferenceRedTest.php:54`) · glob over every handler file (`:99`) · the pin is currently RED.

⚠️ **Overstated:**
- The pin **mechanically passes** both `?? throw` and an early return; it fails **only on silence** (docblock `:28-36`; `absenceIsDecidedExplicitly()` `:165-188`). Rows 2 and 3 of §11's `M-2` table are prohibitions from **`ADR-1` §6(b) / `6-guard`**, not failures of the pin. The pin is a fifth binding test; the ADR is what forbids the shapes that would satisfy it.
- `ADR-1` §6(b)'s prohibition is **conditional** — *"when the `null` itself is being used by the Application layer to conclude that the caller violated a business invariant."* The characterisation of `?? throw` as *"expressly"* prohibited rests on the Rule-8 gate (`rule8-gate-adr1.md:27`), not on the ADR's literal text.

⚠️ **Omitted:**
- The **only** lookup the authorized flow names (`§8d`: `find(AG-3)`, `RecoveryProcess`) has a **sanctioned** absence answer — an early return is conformant (Rule-8 gate Q4 `:18`; `ADR-1` §6(c); constraint ⑦). §11's "early return" row holds only for AG-1/AG-2 lookups.
- The committed GREEN-1 handler surface already **injects** `ElectionCommitteeRepository` and `AcceptanceGateDecisionRepository` (`ReportPeriodExpiryHandler.php:23-24`), although `§8d` shows no AG-1/AG-2 read.

### `A-3` · `M-3` — **conclusion holds; its evidence predates Act B, and one attribution is wrong**

✅ Confirmed: test 1 requires `RecoveryPeriodExpired` · produced only by `onHaltedRecoveryExpiry(…, HaltedAtGate $halt, …)` (`ExpiryConsequence.php:38-45`) · **no production code constructs a `HaltedAtGate`** (the only construction path is the `ElectionOperationalStatus::operativeHalted()` factory, `:27`, which nothing calls) · the UC-4 handler's collaborators cannot supply one. *(Fact, no inference drawn: `$halt` is required by the signature and is not read in the method body, `:46-65`.)*

🔴 **Material omission.** §11 relies on the UC-3 docblock (`FillCommitteeSeatHandler.php:77-82`, authored 2026-08-17). **Since then a domain retrieval contract has landed:** `Domain/OperatingCore/Port/RecordedOperationalStatus` (`216552aa7`, 2026-08-19, `(PBDIGIT-68)` EM-DOM-001 Act B GREEN), returning `ElectionOperationalStatus` — which carries `?HaltedAtGate` and `OperationalCondition`. **Neither this record nor the brief mentions it.**
- Its docblock: *"NO ADAPTER AND NO CALLER IS AUTHORIZED by this declaration"* (`:56-58`).
- Design map `V-9` (`…act-b-domain-design-map.md:71`, `:197`): the "six-port universe" is the Application's **injected collaborator set**; Act B *"wires it nowhere … Act D will enlarge it to seven and therefore needs its own authorization."*
- ⇒ The quoted sentence remains **true today** (the port is outside the six), and *"unobtainable today"* stands — **but the reason is no longer "no contract exists"; it is "the contract exists and is not wired, recorded or persisted (Acts C/D unauthorized)".** `PBDIGIT-72:29-30` had already framed Act B this way (*nameable, not retrievable*; still needs a caller).

🔴 **Contradiction.** §11 `M-3` calls the `HaltedAtGate` producer gap *"`PBDIGIT-72`'s **second**, independent cause."* `PBDIGIT-72:44-45` lists it as cause **1**; cause **2** is *"UC-4 is itself unimplemented."*

### `A-4` · `M-7` — **partly confirmed; its central claim exceeds the evidence**

✅ Confirmed: `onRestorationExpiry()` takes only `ElectionId`, `RecoveryProcess`, `RecordedInstant` (`ExpiryConsequence.php:73-77`) · `§8d` shows only `find(AG-3)` and *"¬Inoperative (halted branch)"* · chain line references `ElectionCommittee.php:125`, `AcceptanceGateDecision.php:117/119`.

⚠️ **Not established by the evidence:**
1. **That AG-2 enters the halted branch *only* via `¬Inoperative`.** P-6 also takes `bool $recoverySucceeded`. Tests 1 and 3 seed AG-2 positions annotated *"halt by decision — recovery never succeeded"* (`ReportPeriodExpiryHandlerRedTest.php:45`), which indicates the fixture expects that flag to derive from AG-2 as well. The handler is unimplemented, so this is **undetermined**, not settled either way.
2. **That AG-2 is *the* source of `¬Inoperative`.** Deriving it via `unableToFunction($decision->requiredVotes())` is **UC-3's pattern** (`FillCommitteeSeatHandler.php:106-112`), not the only conceivable source: `ElectionOperationalStatus::condition()` exists (unwired, see `A-3`).
3. **That the restoration branch needs no gate.** True of P-6's **signature**; but P-6's docblock reads *"Restoration-period expiry **while Inoperative**"* (`:69`) without checking it, and test 2 seeds a gate **and** an Inoperative committee (`:66-68`). Whether the handler must itself guard Inoperative on that branch is **undetermined**; no RED pins it either way.

🔴 **Contradiction with governing text.** §12's *"a 'no' would not block the restoration branch"* speaks to **technical need**. The governing records block GREEN-5 **as a whole**, not per branch: design map `:276` (*"GREEN-5 — 🔴 blocked by `BND-1`"*, gate `G-6`) and the Rule-8 consequent sequence (`rule8-gate-adr1.md:35-43`, GREEN-5 last). §12 does not distinguish technical dependency from governance status.

### `A-5` · Record integrity

- §12 (`M-7`) exists **only in the working tree** at the time of this audit — uncommitted.
- The only commit touching this file is **`a72528b29`**, subject *"docs: exclude large derived files from history"* — a subject that does not describe this record (traceability gap; not repaired here).

### Overall assessment (evidence, not a decision)

§11/§12 move the record toward the evidence and their central findings survive. They are **inaccurate or incomplete** in four respects: the omitted `RecordedOperationalStatus` contract (`A-3`, bearing on `A-4`) · the `PBDIGIT-72` cause numbering (`A-3`) · pin verdicts attributed to shapes the ADR forbids (`A-2`) · `M-7`'s "only `¬Inoperative`" and "restoration unblocked" claims (`A-4`). **§3's question stands as written.** Decision Blocks **A, B and State C remain BLANK.**

⛔ **No option selected. No ranking. No recommendation. No implementation. `app/` and `tests/` byte-untouched by this audit.**
