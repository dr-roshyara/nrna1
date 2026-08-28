# `EM-DOM-001` — **PO/ARB decision dossier**: required-votes equivalence · dependency decomposition · `w8` · `BND-1` · `BND-2`

**Status: 🟡 DOSSIER — NO DECISION, NO AUTHORITY.** This is Architecture/DDD analysis material prepared **for the human PO/ARB**. ⛔ It approves nothing, authorizes nothing, designates nothing and signs nothing. **Every draft ruling in §9 is unsigned wording offered for the human to adopt, amend or reject.**
**Date:** 2026-08-18 · **Type:** read-only analysis · **HEAD at preparation:** `4796ef1f` · **Domain core:** `app/Contexts/Election/Domain/OperatingCore` byte-identical to the RED baseline `1f4b4c5f`; `git status app tests` clean.
**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` holds this work item's siblings.

> ## **AI prepares the decision. The human owns the decision.**

---

# 1 · Provenance statement

## 1.1 What this dossier is built on, and what it is not built on

| Source | Provenance | How it is used here |
|---|---|---|
| **The frozen domain core and the three Application handlers** | ✅ **read directly, in this session, today** | **the only evidentiary basis for §2** — every fact carries a `file:line` |
| `docs/publicdigit/architecture/2026-08-18-EM-DOM-001-phase1-domain-decision-map.md` | ✅ **ACCEPTED as an analysis deliverable** (its own terms: acceptance does **not** approve `R-1`/`R-2`/`R-3`, does **not** authorize implementation, does **not** resolve `BND-1`/`BND-2`/`BND-3`) | source of the **exact** `BND` statements (§6, §7) |
| `2026-08-18-EM-DOM-001-inbound-findings-verification.md` | 🟡 **a VERIFICATION RECORD** — explicitly *"not a ruling, not an acceptance, not a dossier"* | its three corrections are **re-verified independently here**, not inherited |
| `2026-08-18-EM-DOM-001-architecture-investigation-and-decision-dossiers.md` | 🟡 earlier decision material, produced **before** the verification record | **superseded on two premises** (§1.3); everything else stands |
| A message presenting eight items as **"AUTHORITATIVE Architecture findings"** | 🔴 **INVALID PROVENANCE** | ⛔ **not treated as an Architecture ruling anywhere in this dossier.** No Architecture/ARB lane has been designated in this programme and no such investigation artifact exists in the repository |
| `ADR_20260817_2145` §6 · `ADR_20260817_2300` §6 | ✅ **PO/ARB DECISIONS, signed 2026-08-18, present in the repository** | cited **as decided**, quoted, and never re-litigated (§5.4, §6.4) |

## 1.2 The authority position, stated explicitly

⛔ **No Architecture/ARB ruling exists for `BND-1`, `BND-2` or `BND-3`.** ⛔ **`R-1`, `R-2` and `R-3` are not approved designs** — they remain a proposal set. ⛔ **No Architecture/ARB implementation lane has been authorized.** ⛔ **The Domain lane has stopped, pending those decisions.** ⛔ **Nothing in this dossier is implementation authorization**, and the `EM-IMPL-002` Rule-8 gate for ADR-2 stands at **🔴 BLOCKED** pending a separately authorized domain slice.

**Process identity, self-declared and NOT attestable (`INV-ATTR-2`/`G-2`).** 🔴 **Prior-position disclosure, recorded before the analysis rather than after:** this session earlier today performed the independent read-only gate review of `ADR_20260817_2145`/`ADR_20260817_2300` and executed the `AMD1` evidence amendment on a different work item. **It therefore has a prior position on the `w8` characterization used in §5**, and that position is *the same correction* §5 relies on. **Mitigation: §5 re-derives it from the pinned test fixture and the frozen event class, both cited, so the claim can be checked without trusting this session.**

## 1.3 The two premises this dossier corrects, and what follows from correcting them

1. 🔴 **"The equivalence is protected by a restoration-path invariant"** — **false as a reason.** The `EM-GOV-057` guard exists but **does not run on UC-3's path** (§2 `F-2`, §3). The equivalence is a consequence of `ThresholdRule` being numerically inert. **Correct classification: verified current-model equivalence, with a future divergence risk.**
2. 🔴 **`BND-1` written as `"BND-1 / EM-OPEN-055"`** — **two different questions merged** (§6.2). ⛔ The earlier dossier's `BND-1` section rests partly on that coupling; **it is superseded to the extent it does.** Its elimination of the four candidate discriminators is **not** withdrawn.

⚠️ **Why this matters and is not pedantry:** premise 1's reasoning would make the equivalence robust under a second `ThresholdRule`; the real reasoning makes it **fragile**. Premise 2 risks `BND-1` being deferred *as* `EM-OPEN-055` and then never answered on its own terms. **A ruling that absorbed either premise would carry it forward as decided.**

---

# 2 · Verified facts

**All verified in this session against the working tree at `4796ef1f`. Nothing was modified.**

| # | Fact | Evidence |
|---|---|---|
| **F-1** | `AcceptanceGateDecision::requiredVotes()` returns `$this->thresholdRule->requiredVotesFor($this->constitutedSize)` | `Gate/AcceptanceGateDecision.php:117-119` |
| **F-2** | `ThresholdRule::requiredVotesFor(int)` is **pure delegation**: `return RequiredVotes::forConstitutedSize($constitutedSize);` — it contributes nothing numeric | `Gate/ThresholdRule.php:46-48` |
| **F-3** | `ThresholdRule::fromName()` admits exactly one name, `TWO_THIRDS_OF_COMMITTEE_VOTES`; `RequiredVotes::forConstitutedSize(int)` exists | `Gate/ThresholdRule.php:26,29-38` · `Gate/RequiredVotes.php:21` |
| **F-4** | The `EM-GOV-057` denominator-equality guard lives **inside `intervalState()`** — `if ($committee->constitutedSize() !== $this->constitutedSize) { throw … }` | `Gate/AcceptanceGateDecision.php:171,178-181` |
| **F-5** | **`intervalState()` is called by UC-1 only.** UC-2 and UC-3 never call it — so **no denominator-equality check runs on the restoration path** | UC-1 `ExpressCommitteePositionHandler.php:100,124` · UC-2 (no call site) · UC-3 (no call site) |
| **F-6** | **UC-1's dependency on `AcceptanceGateDecision` is CONSTITUTIVE, not a denominator read:** the decision is the aggregate acted upon — `expressPosition()`, `intervalState()`, `save()`; UC-1 never calls `requiredVotes()` | `ExpressCommitteePositionHandler.php:90,100,105,117,124` |
| **F-7** | **UC-2 uses `AcceptanceGateDecision` SOLELY as a denominator source** — `return $decision->requiredVotes();` inside `establishedDenominator()`, one `find()` site | `RecordVacancyEventHandler.php:202-205` |
| **F-8** | **UC-3 uses it TWICE, for two different things:** `requiredVotes()` (denominator) and `gate()` (the restoration return target passed to `ElectionRestored`) | `FillCommitteeSeatHandler.php:107,112,136` and `:140 → :156` |
| **F-9** | `P-7 ResumptionTarget::resolve(HaltedAtGate $halt): GateDesignation` returns `$halt->gate`; `HaltedAtGate` is `{GateDesignation $gate, RecordedInstant $haltedAt}` | `Policy/ResumptionTarget.php` · `Condition/HaltedAtGate.php` |
| **F-10** | `PeriodKind` has exactly two kinds — `HaltedElectionRecovery`, `CommitteeRestoration` — and `EM-GOV-062` makes them mutually exclusive | `Recovery/PeriodKind.php` |
| **F-11** | **The `w8` pin seeds `RecoveryProcess(PeriodKind::CommitteeRestoration, 20)`.** No `HaltedElectionRecovery` period exists in it | `FillCommitteeSeatHandlerRedTest.php:118` (test `test_w8_…`) |
| **F-12** | `ElectionRestored(ElectionId, GateDesignation $returnsToGate, RecordedInstant)` — **`$returnsToGate` is non-nullable** | `Event/ElectionRestored.php` |
| **F-13** | UC-2's message *"the denominator cannot be read"* asserts an impossibility the model does not have — the constituted size is readable from the Committee | `RecordVacancyEventHandler.php:210` · ⛔ **observation only; no authorization to change it** |

---

# 3 · Corrected interpretation of the required-votes equivalence

> ## **Classification: VERIFIED CURRENT-MODEL EQUIVALENCE, with a future divergence risk if `ThresholdRule` becomes variable or non-delegating.**

**In the current model** (`F-1`+`F-2`+`F-3`):

```
AcceptanceGateDecision::requiredVotes()
        ≡  RequiredVotes::forConstitutedSize( ElectionCommittee::constitutedSize() )
```

**⚠️ The reason is the load-bearing part, and the earlier reason was wrong.** The equivalence holds because **`ThresholdRule` carries a name and delegates**, and exactly one rule exists (`EM-GOV-036`, `D-6` — no menu). ⛔ **It is NOT held by a restoration-path invariant:** the `EM-GOV-057` guard is inside `intervalState()` (`F-4`), and **UC-3 never calls it** (`F-5`).

**Consequence the ruling must carry explicitly, if it rests on this equivalence at all:**

> **"The denominator is obtainable from the Committee" holds WHILE exactly one, numerically-inert `ThresholdRule` exists. Add one rule whose `requiredVotesFor()` is not pure delegation, and the equivalence breaks SILENTLY on the restoration path — there is no guard on that path to catch it."**

## What this does NOT establish — stated so it cannot be over-read

⛔ **`AcceptanceGateDecision` is NOT redundant.** It owns the gate designation, the constituted denominator bound at establishment (`EM-GOV-057`), position expression, and interval classification. ⛔ **No refactor of `AcceptanceGateDecision` is proposed or authorized.** ⛔ **`establishedAcceptanceDecision()` must not be removed.** ⛔ **This is a code fact, not implementation authorization.**

---

# 4 · Dependency decomposition

**Three distinct dependencies have been travelling under one name. They separate cleanly, and they separate differently per use case.**

| Dependency | What it needs from `AcceptanceGateDecision` | UC-1 | UC-2 | UC-3 |
|---|---|---|---|---|
| **D-A · denominator** | the constituted size / `requiredVotes()` | — *(never read; `F-6`)* | ✅ **its only use** (`F-7`) | ✅ one of two uses (`F-8`) |
| **D-B · `AcceptanceDecision` semantics** | the decision as a domain concept — establishment, position expression, interval classification | ✅ **constitutive — it IS the aggregate acted upon** (`F-6`) | ✅ *"an established constitutional decision"* is the invariant its absence violates | ✅ same |
| **D-C · provenance / resumption target** | `gate()` used as the restoration return target | — | — | 🔴 **yes — and untouched** (`F-8`) |

> ## **The finding, stated exactly: the DENOMINATOR dependency is separable from the PROVENANCE dependency.**

⛔ **What must NOT be said, and why each is false:**

| ⛔ Statement | Why it is wrong |
|---|---|
| *"`DEP-2` is completely resolved"* | `D-A` becomes separable **subject to §3's condition**; `D-B` remains, and `DEP-2`'s discriminator question (`BND-1`) is untouched |
| *"UC-3 is unblocked"* | `D-C` is `DEP-7`/`DEP-6`, fully open (§8), and ADR-2 §6(f) states UC-3 **must not** be normalized until an authorized domain representation exists |
| *"all `AcceptanceDecision` dependency has disappeared"* | `D-B` is present in **all three** handlers, and is constitutive in UC-1 |
| *"the denominator dependency is removable from UC-1"* | 🔴 **Correction of record (`F-6`):** UC-1 has **no** denominator read. Its dependency is the aggregate itself and is not removable. **The denominator-only dependency is UC-2's; UC-3 has denominator + provenance.** ⚠️ Recorded because a ruling written on "UC-1" would authorize work at the wrong site |

⛔ **No refactoring is proposed, recommended or authorized by this section.**

---

# 5 · `w8` semantic analysis — **Restoration ≠ Resumption**

## 5.1 The two causal paths, traced on the model's own types

```
PATH A — halt
   halt  →  HaltedAtGate{gate, haltedAt}  →  RecoveryProcess(HaltedElectionRecovery)
         →  restoration  →  RESUMPTION TARGET EXISTS   (P-7 resolves it: F-9)

PATH B — unachievable / vacancy condition
   vacancy arithmetic  →  InoperativeOnset  →  RecoveryProcess(CommitteeRestoration)
         →  operability restored  →  restoration  →  NO RESUMPTION TARGET
```

## 5.2 What Path B lacks — and what it does **not** lack

✅ **Path B has a KNOWN causal origin:** an unachievable/inoperative condition, recorded, with its own period kind (`F-10`) and its own onset policy. ⛔ **Path B is NOT "unknown provenance".**
🔴 **What it lacks is a prior HALTED process, and therefore a resumption target** — `P-7` consumes `HaltedAtGate` (`F-9`), and no halt fact exists on this path.

> ## **Therefore the sharper statement is: `Restoration ≠ Resumption`. The gap is a RESUMPTION-TARGET gap, not an ignorance-of-cause gap.**

## 5.3 Verified against the pinned case, because the wording matters

**`w8` is `restoration without a prior HALT`, not `restoration without a `RecoveryProcess``:** the fixture seeds a `CommitteeRestoration` period (`F-11`). A `RecoveryProcess` **exists** in `w8`; the `HaltedElectionRecovery` period does not. **Both descriptions name legitimate domain possibilities — but they are different cases, and only the halt-absent one is pinned by an accepted RED test.**

## 5.4 🔴 FINDING — a PO/ARB ruling on this question **already exists**, and this dossier stops at that boundary

**`ADR_20260817_2300` §6 is signed (`PO/ARB DECISION — 2026-08-18: APPROVED`) and it already rules the semantics**, verbatim: §6(a) *"The Domain shall distinguish between (i) why a `RecoveryProcess` exists, and (ii) why a `Restoration` is permitted to occur … must not be collapsed"*; §6(b) *"A Restoration without a prior `RecoveryProcess` is a legitimate domain possibility"* with fabricated halts, fabricated processes, sentinels and *"unknown"* values expressly forbidden; §6(h) *"the Domain must provide an explicit representation for that causal path"* and *"if the existing non-nullable `GateDesignation` requirement is incompatible with the approved causal model, that incompatibility is a DOMAIN-MODEL GAP requiring a separately authorized domain slice."*

⇒ **`Restoration ≠ Resumption` is not a new question for the PO/ARB to decide; it is a SHARPENING of a ruling they have already made.** What is **not** yet in place is:

1. 🔴 **the authorization** for the domain slice §6(e)/§6(h) require (Rule-8 gate: **BLOCKED**); and
2. ⚠️ **a vocabulary reconciliation**: the signed text is framed on *"without a prior `RecoveryProcess`"*, while the pinned case (`F-11`) is *"without a prior halt"*. **Under the ruling's own §6(a) — do not collapse the two causal questions — the halt-absent framing is the one that matches the evidence.** The human may wish to confirm that §6(b)/(h) are read as covering the halt-absent path.

⛔ **No representation is chosen here.** Not nullability · not a sentinel · not `UnknownGate` · not a new enum value · not a new aggregate · not a new event or fact · not a replacement representation · not a protocol read · not a repository change. **Representation follows meaning, and the meaning is the human's already-recorded ruling.**

---

# 6 · `BND-1` — no ruling, and it must stay its own question

## 6.1 The exact question

> **Who owns "lifecycle PHASE" for the OperatingCore — the discriminator that separates `ADR-1` §6(a) row 2 (`AcceptanceDecision` required-but-absent = *violation*) from row 3 (the election has not yet reached the phase in which an acceptance decision exists = *legitimate lifecycle state*)?**

**The discovered requirement:** the OperatingCore contains **no phase concept at all**. `ElectionLifecycleState` and `ElectionConstitution` live in `app/Domain/Election/` — a different bounded context — and carry no halt/gate/operative vocabulary. **Resolving it needs one of:** (a) a new OperatingCore phase concept *(a new strategic concept)* · (b) a Published-Language interaction with the legacy context *(a context-map change, `AIP-14`)* · (c) a ruling that AG-2's **establishment** is itself the phase marker *(a governance reading of ADR-1 rows 2–3)*.

## 6.2 Why it is NOT `EM-OPEN-055`, and must not be merged

| | Subject |
|---|---|
| **`BND-1`** | the **discriminator/meaning** required by ADR-1's absence semantics — who owns lifecycle phase for the OperatingCore |
| **`EM-OPEN-055`** | **who may participate** in election-wide acceptance decisions, and under which predetermined decision rule — twelve parts (nomination · transmission · approval · counts · equality · threshold · quorum · unavailability · replacement · immutability · failure) |

⛔ **Different subjects, different owners, different evidence.** ⛔ **Resolving `EM-OPEN-055` would NOT resolve `BND-1`.** **Two concrete harms if merged:** `BND-1` acquires the weight of a twelve-part governance question it does not belong to, and — worse — it gets *"deferred as `EM-OPEN-055`"* and is then never answered on its own terms.

## 6.3 Current dependency status *(dependency analysis, not a ruling)*

| | Status |
|---|---|
| **On the restoration critical path?** | ⛔ **No longer** — §3/§4 remove the denominator need that put it there *(subject to §3's condition)* |
| **Still live for** | **UC-2** *(`D-A` + `D-B`)* and **`ADR-1`'s `AcceptanceDecision` absence semantics** |
| ⚠️ **Newly relevant** | **`ADR-1` §6(c) authorizes ONE normalization slice covering UC-1/UC-2/UC-3, "subject to the Rule-8 gate", with the criterion that each handler conform to the governed meaning of every absent reference.** For `AcceptanceDecision` that means **distinguishing row 2 from row 3 — which is exactly `BND-1`.** ⇒ **`BND-1` is a gating input to the slice the signed ADR-1 contemplates**, and §6(b) already says a missing contract is a **DOMAIN-MODEL GAP**, not permission to interpret |
| **`BND-3` ordering** | `BND-1` before `BND-3` still stands, but with `BND-1` off the restoration path **the strength of that dependency should be re-examined by whoever rules** |

⛔ **`BND-1` is not closed, not rejected, not implemented, not merged.**

---

# 7 · `BND-2` — the authorization question, prepared and not answered

## 7.1 The exact question

> **Does the existing `EM-DOM-001` authorization permit creation of the Domain identity/retrieval contract required to make the operational overlay retrievable recorded truth (`R-1`) — or is that "modifying repositories", which the authorization prohibits?**

**The tension is textual and real:** the prohibition list says ⛔ *"do not modify … repositories"*, while `EM-GOV-059(b)` requires the halt to be **retained recorded truth, not recomputed**, and ADR-2 §6(e) requires *"confirmation that the resulting contract can be consumed by the Application layer"* — which presupposes a loadable contract. **`EM-ARCH-001` §2b modelled the overlay as a *type*, not as something with identity.**

## 7.2 The five distinguishable acts

| | Act | Architectural scope | Authorization scope | Implementation consequence | New authorization required? | What becomes impossible if excluded |
|---|---|---|---|---|---|---|
| **A** | **Modify an existing repository interface** *(e.g. add a halt accessor to an AG-1/AG-2/AG-3 repository)* | changes a published domain contract; **attaches the overlay to an aggregate whose boundary is unsettled (`BND-3`)** | 🔴 **Squarely inside the prohibition** on the plainest reading | every implementer of that interface changes | ✅ **Yes — unambiguously** | nothing that B does not also provide; **A is the reading nobody needs** |
| **B** | **Create a NEW Domain-owned identity + retrieval contract for the overlay** *(domain-side only, no infrastructure, no adapter)* | ⭐ **the `R-1` act.** Adds a domain contract; **does not settle the overlay's boundary** *(that is `BND-3`)* | ⚠️ **THE AMBIGUITY.** Is a *new* interface "modifying repositories" (forbidden) or "the domain representation ADR-1/ADR-2 require" (authorized)? **The Domain lane declined to read this and stopped — correctly** | one new domain interface; **no runtime behaviour until C exists** | ⚠️ **This is the question. Both readings are defensible from the text as written** | 🔴 **`DEP-5b` and `DEP-6` cannot be discharged at all** — `P-7`'s input has no other legitimate supplier; every alternative is an already-prohibited `DEP-7`/`DEP-8`/`DEP-9` path |
| **C** | **Implement persistence / adapter support** for B | Infrastructure layer; no domain semantics | 🔴 **Outside** the domain-slice framing; Infrastructure is not in the `EM-DOM-001` scope | migrations/adapters; the contract becomes usable at runtime | ✅ **Yes — a separate slice** | B exists but nothing can be loaded ⇒ the overlay stays non-retrievable in practice |
| **D** | **Change an Application repository call** *(wire UC-1/UC-2/UC-3 to the new contract)* | Application orchestration only | 🔴 **Explicitly prohibited** — *"do not modify the Application layer … UC-1/UC-2/UC-3"*; and **ADR-2 §6(f) bars UC-3 normalization until the contract exists** | handlers consume the contract | ✅ **Yes — and it must come AFTER B+C** | the contract exists and is never consumed; **`w8` keeps today's proxy (§8)** |
| **E** | **Introduce a new Domain identity / persistence MODEL** *(the overlay as a fourth aggregate, or a lifecycle aggregate, or a projection)* | 🔴 **This is `BND-3`, not `BND-2`** — identity + persistence does **not** by itself imply "aggregate" | beyond any current authorization; needs the lifecycle-ownership answer first | fixes a seam that may be the wrong seam | ✅ **Yes — plus an architecture confirmation of the boundary** | nothing immediately; ⚠️ **choosing E early risks fixing the wrong seam** |

## 7.3 The consequence that must not be discovered late

> ⚠️ **If `BND-2` is answered "out of scope", `DEP-5b` and `DEP-6` cannot be discharged at all** — not "later", not "differently". `P-7` needs a `HaltedAtGate` that is recorded truth, and **no authorized supplier exists** without B. **An "out of scope" answer is therefore a decision to stop that work, and should be taken knowing that**, not as a narrow reading of a prohibition list.
> ⚠️ **And B does not settle `BND-3`.** Authorizing B decides *that* the overlay gets identity and retrieval — **never what shape it takes.**

⛔ **This section decides nothing. Architecture states consequences; the human chooses.**

---

# 8 · UC-3 provenance status — **independently open**

**Preserved verbatim as a fact:** UC-3 passes **`$decision->gate()`** — the *established acceptance decision's* gate — as `ElectionRestored::$returnsToGate` (`F-8`, `F-12`).

⛔ **This is NOT equivalent to establishing a legitimate `HaltedAtGate`.** It is a **ruled proxy**: exact while exactly one acceptance decision is established, and **silently wrong the day two designations are established at once**. The handler deliberately does not call `P-7` and does not construct a `HaltedAtGate` — *constructing one would invent the halt fact.*

⛔ **The provenance/resumption-target problem is NOT resolved by the denominator dependency being separable.** They are different dependencies (§4 `D-A` vs `D-C`). **It remains `DEP-7`/`DEP-6`, and ADR-2 §6(f) states UC-3 must not be normalized until the approved causal model has an authorized domain representation.**

---

# 9 · Draft wording for the human PO/ARB

## Draft A — `w8`

> ### **PROPOSED PO/ARB WORDING — NOT DECIDED**
>
> *"`Restoration` and `Resumption` are distinct domain concepts. Both restoration paths have a known causal origin; only the halt path has a resumption target.*
>
> *This clarifies, and does not amend, `ADR_20260817_2300` §6(a)/(b): the case pinned by `w8` is restoration **without a prior halt** — the accepted RED fixture seeds a `CommitteeRestoration` period, so a `RecoveryProcess` exists there while no `HaltedElectionRecovery` period does. §6(b)'s "legitimate domain possibility" and §6(h)'s explicit-representation obligation are to be read as covering that halt-absent path.*
>
> *No representation is selected by this clarification: no nullability, no sentinel, no `UnknownGate`, no new enum value, no new aggregate, no new event, no replacement representation, no protocol read, no repository change. Representation follows meaning, and remains the domain slice §6(e)/(h) require.*
>
> *This clarification grants no implementation authority. The Rule-8 gate for ADR-2 remains as recorded."*

## Draft B — `BND-2`

> ### **PROPOSED PO/ARB WORDING — NOT DECIDED**
>
> **Option B-IN — the contract is in scope**
> *"Act **B** of the `BND-2` dossier — creating a NEW Domain-owned identity and retrieval contract for the operational overlay, domain-side only — is IN SCOPE of `EM-DOM-001`. The prohibition on 'modifying repositories' is read as forbidding changes to existing repository interfaces (act A), not as forbidding the domain representation that `ADR-1`/`ADR-2` require.*
> *This authorizes act B only. Acts **C** (persistence/adapter), **D** (Application call sites) and **E** (a new identity/persistence model) remain unauthorized and each requires its own act. This does not settle the overlay's boundary — `BND-3` remains open — and `R-1`'s FORM is not approved by this ruling. `app/`, `tests/` and the frozen Domain core remain untouched until a domain slice is separately authorized under Rule 8, with its own RED test."*
>
> **Option B-OUT — the contract is out of scope**
> *"Act **B** is OUT OF SCOPE of `EM-DOM-001`. I record the consequence explicitly: `DEP-5b` and `DEP-6` cannot be discharged under this authorization, because `P-7`'s input has no other legitimate supplier and every alternative is an already-prohibited path. Work depending on them stops here rather than being solved another way, and a new authorization is required to resume it."*
>
> ⚠️ **Both options are offered because the text supports both readings. Architecture does not choose between them.**

---

# 10 · Recommended next actor, and what remains unauthorized

> ## **Next actor: the HUMAN PO/ARB.** Nothing below the decision line can proceed without an act.

> ⚠️ **AMENDED at PO direction, 2026-08-18 — DECISIONS and CLARIFICATIONS are not the same act.** The table below ranked four items by urgency; the PO's correction is that **only `BND-2` and `BND-1` are outstanding DECISIONS**, while **`w8` is a CLARIFICATION of the already-signed `ADR-2` §6(b)/(h)** and ⛔ **must not be re-run as a fresh decision** — doing so would reopen a ruling that was properly made. **`BND-3` is presented as not yet ripe.** **The recording surface built on this correction is `2026-08-18-EM-DOM-001-decision-recording-surface.md`**; where the two differ, that document governs the sequencing.

| Order | Item | Why it is first |
|---|---|---|
| **1** | **`BND-2`** (Draft B) | it is the only item that can **dead-end** `DEP-5b`/`DEP-6`; every downstream plan changes shape depending on the answer |
| **2** | **`w8` clarification** (Draft A) | cheap, and it prevents the halt-absent case being read out of a ruling framed on `RecoveryProcess` absence |
| **3** | **`BND-1`** | gating input to the `ADR-1` §6(c) normalization slice; needs a **named owner of lifecycle phase**, via (a), (b) or (c) of §6.1 |
| **4** | **`BND-3`** | ⛔ only after `BND-1`, and only if `BND-2` = in scope; a boundary chosen earlier may fix the wrong seam |

⛔ **Remaining unauthorized, and untouched by this dossier:** implementation of `R-1`/`R-2`/`R-3` *(not approved designs)* · any change to `app/`, `tests/` or the frozen Domain core · any repository, port or protocol change · UC-1/UC-2/UC-3 normalization *(ADR-1 §6(c) is subject to the Rule-8 gate; ADR-2 §6(f) bars UC-3)* · the `RecordVacancyEventHandler:210` message *(`F-13`, observation only)* · any ADR decision-block text · any code prepared in anticipation of a decision.

**Traceability:** Phase-1 map §§2/4/8 (ACCEPTED as analysis) · inbound-findings verification record (Corrections 1–3, re-verified here) · earlier investigation dossier *(superseded on the two premises in §1.3)* · `ADR_20260817_2145` §6(a)–(d) · `ADR_20260817_2300` §6(a)–(h) · `EM-IMPL-002` Rule-8 gate (**BLOCKED**) · `EM-GOV-036`/`057`/`059(b)`/`059(c)`/`062` · `D-6` · `EM-OPEN-055` · `EM-ARCH-001` §2b · `AIP-14` · code sites `F-1`…`F-13` · baseline `1f4b4c5f` · HEAD `4796ef1f`.
