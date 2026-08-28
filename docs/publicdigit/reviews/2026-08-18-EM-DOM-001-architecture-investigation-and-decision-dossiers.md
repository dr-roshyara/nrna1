# `EM-DOM-001` — Architecture investigation + decision dossiers for the **human PO/ARB**

**Date:** 2026-08-18 · **Lane:** independent Architecture / ARB review · **Type:** 🔴 **READ-ONLY INVESTIGATION AND DECISION MATERIAL**
**Follows:** `2026-08-18-EM-DOM-001-architecture-review.md` (verdict **BLOCKED**)

> ## ⛔ **This document contains NO decision.**
> **It is not a PO/ARB act, does not simulate one, and records no signature.** Every draft ruling in §8 is **unsigned wording offered for the human PO/ARB to adopt, amend or reject.** **AI prepares the decision. The human owns the decision.**

**Process identity, self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b` — the same process that produced the architecture review. **Prior position disclosed:** it raised the `AcceptanceGateDecision` hypothesis tested below, and therefore had an interest in its confirmation. **Mitigation: §2 states the exact condition that would have refuted it, and that condition was tested against the source rather than argued.**

**Nothing was modified.** No code · no tests · no ADR · no Domain artifact · no repository · no port · no authorization.

---

# 1 · Executive architectural finding

> ## ✅ **CONFIRMED EQUIVALENCE — and the domain enforces it itself.**
>
> **`$decision->requiredVotes()` ≡ `RequiredVotes::forConstitutedSize($committee->constitutedSize())` in Model A.** This is not an observed coincidence: `AcceptanceGateDecision::intervalState()` **throws** if the two denominators differ — *"The constituted denominator bound at establishment must match the committee record (EM-GOV-057)."* **The model refuses to operate unless the equality holds.**

> ## ⚠️ **BUT the hypothesis does NOT dissolve `BND-1`. It DECOUPLES it — which is a different and, on the evidence, better result.**

**The three use cases separate cleanly, and they were being treated as one problem:**

| | Use case | AG-2 used for | `DEP-2` on its critical path? |
|---|---|---|---|
| **UC-1** | `RecordVacancyEventHandler` | **the denominator, and nothing else** *(one call site, line 205)* | ⛔ **NO — removable outright** |
| **UC-3** | `FillCommitteeSeatHandler` | the denominator (line 107) **+** `$decision->gate()` (line 140 = **`DEP-7`**) | ⛔ **NO — removable once the `w8` meaning is ruled** |
| **UC-2** | `ExpressCommitteePositionHandler` | `expressPosition()` · `intervalState()` · `save()` — **AG-2 is irreplaceable** | ✅ **YES — genuinely remains** |

> ### ⭐ **The consequence for sequencing, which is the point of this investigation:**
> **`BND-1` — and therefore the twelve-part `EM-OPEN-055` governance question — blocks UC-2 normalization ONLY.** **It does not block the restoration chain** (`DEP-5b`, `DEP-6`, `w8`, `BND-2`, `BND-3`). **Those were bundled and are separable.**

⛔ **This is a factual determination about data flow and invariants. It authorizes nothing and proposes no refactor.**

---

# 2 · Investigation 1 — is `AcceptanceGateDecision` semantically necessary in Model A?

## 2.1 The ten commissioned questions, answered on repository evidence

| # | Question | Answer | Evidence |
|---|---|---|---|
| **1** | Semantically necessary in Model A? | ✅ **YES — absolutely, as a domain concept.** It owns `I-7`…`I-12`: one position per seat, append-only positions, replacement-occupant rule, named-rule evaluation, derived interval state, recorded dissent. ⚠️ **The question was never whether AG-2 is necessary; it is whether the RESTORATION PATH necessarily depends on it. Those are different questions and the framing must not be allowed to blur.** | `AcceptanceGateDecision` docblock + `expressPosition()`, `apply()`, `positions()` |
| **2** | Any invariant `ElectionCommittee` does not own? | ✅ **YES — `I-7`…`I-9`, `I-12` are about POSITIONS**, which AG-1 knows nothing of. **`I-10` (named-rule arithmetic) is the only one touching the denominator, and it is bound to AG-1's value.** | both aggregates read directly |
| **3** | Is `constitutedSize` merely a copy? | ✅ **YES — and a domain-ENFORCED copy.** `intervalState()` throws unless `$committee->constitutedSize() === $this->constitutedSize`. `EM-GOV-057` fixes it "at constitution, moved by nothing" on **both** sides — AG-1's accessor carries the same citation. | `AcceptanceGateDecision::intervalState()`; `ElectionCommittee::constitutedSize()` |
| **4** | Is `requiredVotes()` mathematically equivalent? | ✅ **CONFIRMED EQUIVALENCE.** Chain: `requiredVotes()` → `ThresholdRule::requiredVotesFor($n)` → **pure delegation** → `RequiredVotes::forConstitutedSize($n)`, with `$n` domain-enforced equal to AG-1's. **No branch, no state, no configuration intervenes.** | the three classes, read end to end |
| **5** | Does any behaviour depend on AG-2 as an **independent concept** rather than its values? | **On UC-2 — YES, decisively.** On **UC-1 — NO**: one call site, `requiredVotes()`. On **UC-3 — only via `$decision->gate()`, which is `DEP-7`** and which the prior review showed has **no legitimate answer at all** in the `w8` path. | `grep '\$decision'` across all three handlers |
| **6** | Does `establishedAcceptanceDecision()` add domain meaning? | ⛔ **NO.** It scans **both** designations and returns the **first non-null** — so it does not ask "is gate N's phase reached?", it asks "does **any** decision exist?" ⚠️ **And `establishedRequiredVotes()` throws *"the denominator cannot be read"* while the handler is holding the `ElectionCommittee` from which it can be read. The exception's stated reason is false in Model A.** | `FillCommitteeSeatHandler:206-216`; `RecordVacancyEventHandler:199-213` |
| **7** | Does any handler establish an invariant proving AG-2 independently required **on this path**? | ⛔ **NO for UC-1/UC-3.** ✅ **YES for UC-2**, which targets a **named** gate (`$command->gate`) and mutates it. | as above |
| **8** | Do permitted `ThresholdRule` variants falsify the equivalence? | ⛔ **NO — there is exactly ONE permitted value.** `fromName()` throws on anything but `TWO_THIRDS_OF_COMMITTEE_VOTES`: *"Model A consumes adopted `EM-GOV-036` directly; no menu exists (`D-6`)."* | `ThresholdRule` |
| **9** | Model-A-only? | ✅ **YES, and the code says so in its own words.** The conclusion must never be quoted without that scope. | `ThresholdRule` docblock |
| **10** | What future change invalidates it? | **(a) a permitted threshold menu** (`D-6`; `EM-OPEN-077`/`076`) — the rule would then carry information and `requiredVotesFor()` could branch; **(b) any relaxation of `EM-GOV-057`'s "fixed at constitution"**, which would let the two denominators drift and would break `intervalState()`'s guard first. | `ThresholdRule`; `EM-GOV-057` |

## 2.2 The refuting condition, stated before the result and then tested

**The hypothesis would have been REFUTED by a variable `ThresholdRule`** — a menu of rules would make `requiredVotes()` carry information AG-1 does not hold. **Tested: `ThresholdRule::fromName()` accepts one string and throws otherwise.** *Falsifier stated, tested, not met.*

**A second, stronger falsifier emerged during the test and also failed:** if AG-2's `constitutedSize` could legitimately differ from AG-1's, the values could diverge at runtime. **`intervalState()`'s guard makes divergence a thrown exception rather than a silent difference.** ⇒ **the equivalence is enforced, not merely observed.**

## 2.3 What this does NOT establish — stated so it cannot be over-read

⛔ **It does not establish that AG-2 is redundant.** AG-2 owns five invariants AG-1 cannot express.
⛔ **It does not authorize removing `establishedAcceptanceDecision()` or `establishedRequiredVotes()`.** No refactor is proposed, and none is authorized.
⛔ **It does not extend beyond Model A.**
⛔ **It does not settle where the denominator OUGHT to be read from.** It establishes only that **two readings are provably equal today** — which is what the critical-path question turns on. **Choosing the reading is a domain-design act requiring its own authorization.**

---

# 3 · `DEP-2` critical-path determination

> **`DEP-2` remains a real domain dependency. It is NOT on the critical path of UC-1, and it is on UC-3's path only through `DEP-7`, which is independently prohibited.**

```
UC-1  RecordVacancyEventHandler
        needs: the denominator
        AG-2 supplies it as a domain-enforced copy of AG-1's value
        ⇒ DEP-2 NOT load-bearing                                  ⛔ off the critical path

UC-3  FillCommitteeSeatHandler
        needs: the denominator          → same as UC-1
        needs: $returnsToGate           → currently $decision->gate()  = DEP-7
                                          which the review showed has NO legitimate
                                          answer in the w8 path at all
        ⇒ DEP-2 NOT load-bearing once the w8 MEANING is ruled     ⛔ off the critical path (conditional)

UC-2  ExpressCommitteePositionHandler
        needs: THE named gate's decision, to mutate and to classify
        AG-2 is irreplaceable; its absence is exactly ADR-1 §6(a) rows 2 vs 3
        ⇒ DEP-2 GENUINELY REMAINS                                 ✅ on the critical path
```

**Consistency check against ADR-1 §6(c)**, which lists the normalization targets: *"UC-2: Committee **and** AcceptanceDecision absence each conform to their respective governed invariants."* ✅ **The determination matches the ADR's own scoping — UC-2 is where the AcceptanceDecision absence question actually lives.**

> ### ⭐ **The architectural gain: two problems that were travelling as one are separable.**
> **The restoration/`w8` chain and the UC-2 acceptance-absence question share no blocking dependency.** ⇒ **`EM-OPEN-055` need not be ruled to make progress on restoration.**

---

# 4 · Is `BND-1` still necessary?

> ## **YES — but narrower, and no longer blocking this slice's restoration work.**

| | Before this investigation | After |
|---|---|---|
| **Scope** | the discriminator for **all three** use cases | **UC-2 only** |
| **Blocks** | `DEP-2`, and through it the whole slice | **UC-2 normalization only** |
| **Depends on** | the gate↔lifecycle-phase binding · `EM-OPEN-055` (🔴 OPEN, twelve parts) | **unchanged — still the same governance question** |
| **On the restoration critical path?** | assumed **yes** | ⛔ **NO** |

**Nothing in the prior review's `BND-1` analysis is withdrawn.** All four candidate discriminators remain eliminated; the foreclosure argument stands. **What changes is only how much it blocks.**

⚠️ **`BND-3` remains blocked on `BND-1` as before** — the overlay's consistency boundary still depends on who owns lifecycle transitions. **This investigation does not touch that, and does not claim to.**

---

# 5 · `BND-2` decision dossier — the authorization boundary

> **Decision required from the PO/ARB. Architecture states consequences and does not choose.**

**The phrase under interpretation, from the act of 2026-08-18:** *"This is not authorization to modify the Application layer, normalize UC-1/UC-2/UC-3, **change repositories**, add protocol reads, reconstruct provenance in the Application layer, or implement GREEN-5."*

## 5.1 The six distinguishable acts the phrase might cover

| | Act | Present in this codebase? |
|---|---|---|
| **i** | **Change an existing repository INTERFACE** (`ElectionCommitteeRepository`, `AcceptanceGateDecisionRepository`, `RecoveryProcessRepository`) | ✅ three exist, in `Domain/OperatingCore/Repository/` |
| **ii** | **Create a NEW domain-owned repository/retrieval contract** for the operational overlay | ⛔ none exists |
| **iii** | **Implement an ADAPTER** for any of them | ⛔ **none exists for the OperatingCore at all** — *"No storage technology is chosen (G-6); no adapter exists in this increment"* |
| **iv** | **Add persistence** (schema, migration, storage technology) | ⛔ none |
| **v** | **Change an Application repository CALL** | ✅ the handlers call `find()`/`save()` |
| **vi** | **Introduce a new domain identity/retrieval MODEL** (give the overlay identity) | ⛔ the overlay is today a `final readonly` value object with a private constructor |

> ⭐ **The architectural fact that makes the phrase ambiguous:** in the OperatingCore a "repository" is a **domain contract** — an interface in `Domain/`, persistence-independent, with **no implementation anywhere**. **The word therefore names (i) and (ii) as naturally as it names (iii) and (iv), and the act does not say which.**

## 5.2 The two readings and what each costs

| | **Reading NARROW** — the prohibition covers (i), (iii), (iv), (v): *do not change what exists, do not build persistence* | **Reading BROAD** — it also covers (ii) and (vi): *no new retrieval contract of any kind* |
|---|---|---|
| **`DEP-5b`** | ✅ dischargeable — the overlay can be given a domain-owned retrieval contract, still with no adapter | ⛔ **not dischargeable** |
| **`DEP-6` (halted path)** | ✅ dischargeable — `P-7` becomes reachable from recorded truth | ⛔ **not dischargeable** |
| **`DEP-6` (`w8` path)** | ⚠️ unaffected either way — it needs §6's meaning ruling, not a contract | ⚠️ unaffected |
| **New authorization required?** | ⛔ no | ✅ **yes, or the slice ends here** |
| **Consistency with ADR-2 §6(e)** | ✅ satisfies *"confirmation that the resulting contract can be consumed by the Application layer"* | ⚠️ **that clause presupposes a loadable contract; under BROAD it cannot be satisfied within this slice** |
| **Risk** | the prohibition is read more narrowly than the author may have meant | **`DEP-5b`/`DEP-6`(halted) are UNACHIEVABLE — not deferred** |

## 5.3 The consequence that must not be discovered late

⚠️ **Under BROAD, every legitimate supplier of `HaltedAtGate` is already prohibited** — Application construction (`DEP-9`) · protocol reconstruction (`DEP-8`) · `$decision->gate()` (`DEP-7`) · copying it onto AG-3 (two owners, ADR-2 §6(g)). ⇒ **`P-7` stays permanently unreachable and `DEP-5b` cannot be discharged by any permitted means.** **That is a legitimate outcome if chosen deliberately, and a poor one if reached by accident.**

**Decision required from the PO/ARB:** does *"change repositories"* prohibit **creating** a new domain-owned retrieval contract (act ii), or only **changing** existing ones and building persistence (acts i, iii, iv, v)? **Draft wording: §8, Draft B.**

---

# 6 · `w8` decision dossier — the meaning of restoration without a `RecoveryProcess`

> **Meaning only. ⛔ No representation is selected, proposed, or implied. `nullable` · sentinel · enum · new event · optional `GateDesignation` · new aggregate · proxy · protocol lookup are all OUT OF SCOPE here by instruction, and none is recommended.**

## 6.1 The two paths, traced on the model's own state machine

```
PATH A — restoration after a halt
   gate HALTED (decided failure or impossibility)   → HaltedAtGate RECORDED (gate + instant)
   → RecoveryProcess runs (PeriodKind::HaltedElectionRecovery)
   → Committee restored
   ⇒ the election RESUMES PROGRESSION at the unresolved gate     [P-7 applies]

PATH B — restoration after an unachievable condition            [ = w8 ]
   gate OPEN (reached, undecided)                   → NO HaltedAtGate is ever recorded
   → vacancy event breaches the arithmetic          → INOPERATIVE at that recorded event
                                                      (EM-GOV-065; InoperativeOnset)
   → seats filled to sufficiency
   ⇒ the election returns to OPERATIVE and the gate is OPEN AGAIN  [P-7 INAPPLICABLE]
```

*Source: `EM-ARCH-001` §5d — `"(restoration: non-vacant ≥ required, undecided) ▶ OPEN again (EM-GOV-059(c))"` — and `RecordVacancyEventHandler`, which consults no halt.*

## 6.2 The six commissioned questions

**① What does Restoration mean in path A?** **Progression, which was halted, RESUMES** — at the unresolved gate, restoring the *ability to decide* and never a deemed decision (`EM-GOV-059`(c), `005`).

**② What does Restoration mean in path B?** **Operability is restored; progression RESUMES NOTHING, because it was never halted.** The election returns to the condition it was already in — an open, undecided gate.

**③ Is provenance semantically mandatory in both?** ⭐ **A correction to the framing, and it matters.** **Path B is not "restoration without provenance."** Its causal origin exists and is already recorded: **the vacancy event that breached the arithmetic**, carried as `InoperativeOnset` in `ElectionBecameInoperative`. ⇒ **Both paths have provenance. What path B lacks is a RESUMPTION TARGET, not a cause.** **This maps exactly onto ADR-2 §6(a)'s separation:** *why restoration is permitted* is answered in both; *where restoration returns* is answered only in A.

**④ If not, what does "absence of provenance" mean?** **The question does not arise, on ③.** What must acquire a named domain meaning is **the absence of a resumption target** — *"progression was never halted, so there is nothing to resume."* ⚠️ **That is a positive domain statement, not an absence** — and stating it that way is what keeps it out of ADR-2 §6(b)'s prohibited list (no sentinel, no *"unknown"*, no fabricated halt).

**⑤ What invariant must the Restoration fact satisfy?** **It must state which condition was resolved, and must not assert a resumption target where progression was never halted.** ⚠️ **The current fact violates the second half by construction:** `ElectionRestored.$returnsToGate` is non-nullable, so path B **must** supply a gate, and `FillCommitteeSeatHandler` supplies `$decision->gate()` — **a value with no causal relationship to the restoration.** *The handler's own docblock concedes the insufficiency.*

**⑥ What must a later Domain design provide?** **A way to record a restoration whose meaning is *"operability restored, progression never halted"* as distinct from *"progression resumed at gate X"* — without fabricating a halt, a recovery process, a sentinel gate or an "unknown" value.** ⛔ **Which mechanism does that is a domain-design act requiring its own authorization, and is deliberately not proposed here.**

## 6.3 Why this is independent of `BND-1` and `BND-2`

**It needs no lifecycle-phase owner** (the paths are distinguished by whether a halt was recorded, which the model already knows) **and no retrieval contract** (it is a question about a fact's meaning, not about loading state). ⇒ **It can be ruled today.** ⚠️ **And it is the only place where the model is fabricating a value in committed code right now**, which is an argument for ruling it first.

---

# 7 · Recommended next actor

> ## 🔵 **The human PO/ARB — on TWO rulings, not three. `BND-1` is no longer urgent.**

| Order | Act | Why now |
|---|---|---|
| **1** | **Rule the `w8` MEANING** (§6; draft wording §8-C) | **Independent of everything else**, and the only place committed code fabricates a value today |
| **2** | **Read the authorization phrase** (§5; draft wording §8-B) | One sentence; **unblocks `DEP-5b`/`DEP-6`(halted) or ends them explicitly** |
| **3** | ⏸️ **`BND-1` / `EM-OPEN-055` — DEFER** | Now blocks **UC-2 normalization only**. A twelve-part governance question should not be rushed to unblock work it no longer blocks |

**After 1 and 2:** a **separately authorized** Domain design step proposes the `w8` representation and returns it for approval — *meaning → authorized design → representation proposal → approval → implementation.* ⛔ **The Domain lane does not resume before those rulings.**

---

# 8 · Draft decision wording — ⛔ **UNSIGNED. For the human PO/ARB to adopt, amend or reject.**

> ## ⚠️ **These are DRAFTS prepared by Architecture. They are NOT decisions, carry NO authority, and must NOT be recorded as acts unless the human PO/ARB issues them in their own voice.**

### Draft B — authorization boundary *(unsigned)*

> *"**Decision required from PO/ARB — `EM-DOM-001` authorization boundary.** The prohibition *'change repositories'* in the authorization of 2026-08-18 is to be read as [**NARROW: prohibiting modification of the three existing domain-owned repository interfaces, the creation of any adapter, and the introduction of any persistence technology — while permitting the creation of one new domain-owned retrieval contract for the operational status, with no adapter** / **BROAD: additionally prohibiting the creation of any new domain-owned retrieval contract, with the consequence that `DEP-5b` and the halted path of `DEP-6` are unachievable within `EM-DOM-001` and require a separate authorization**]. No scope beyond this sentence is authorized."*

### Draft C — the `w8` meaning *(unsigned)*

> *"**Decision required from PO/ARB — restoration without a `RecoveryProcess`.** Where an election became Inoperative through the Committee arithmetic without progression having been halted at a gate, restoration means [**operability is restored and progression resumes nothing, because progression was never halted; the election returns to the open, undecided gate it was already at**]. The causal origin of such a restoration is the recorded vacancy event that breached the arithmetic, not a halt. The Domain must be able to record this meaning without fabricating a halt, a recovery process, a sentinel gate or an 'unknown' value. **This ruling decides MEANING only; it selects no representation and authorizes no implementation.** A separately authorized Domain design step shall propose the representation and return it for approval."*

### Draft A — deferral *(unsigned)*

> *"**Decision required from PO/ARB — `BND-1` sequencing.** On the finding that `DEP-2` is not load-bearing for UC-1 and is load-bearing for UC-3 only through the prohibited `DEP-7`, the gate↔lifecycle-phase question and `EM-OPEN-055` are [**deferred; they block UC-2 normalization only and are not a precondition for the restoration work**]. `BND-1` and `BND-3` remain unruled."*

---

# 9 · What remains UNAUTHORIZED

⛔ **All implementation** — no code, no tests, no RED tests, no scaffolding.
⛔ **Any refactor arising from §2** — `establishedAcceptanceDecision()` and `establishedRequiredVotes()` **stay exactly as committed.** **A confirmed equivalence is not permission to act on it.**
⛔ **Application normalization** · UC-1 · UC-2 · UC-3 · UC-4 · **GREEN-5.**
⛔ **`DEP-7` repair** — and specifically: **not before the `w8` meaning is ruled**, or the fabrication relocates rather than resolves.
⛔ **Any `DEP-8`/`DEP-9`/`DEP-11`/`DEP-12` path.** ⛔ **Any change to `DEP-10`'s four protected sites.**
⛔ **Creating the overlay's identity or retrieval contract** — pending §5.
⛔ **Selecting the `w8` representation** — pending §6, and then only under a separate authorization.
⛔ **Ruling `BND-1`, `BND-2` or `BND-3`.** ⛔ **Modifying any ADR.** ⛔ **Re-running the Rule-8 gate** — this investigation is not an authorization and must not be treated as one.
⛔ **`AbsentAggregateReferenceRedTest` stays RED** and is nobody's target.
⛔ **Correcting the `HaltedAtGate` docblock** — to be done with the `BND-1` ruling, never before (prior review §6.3).
⚠️ **Outstanding Governance correction, not performed here:** the Amendment-1 record states `DEP-10` protects **two** sites; it protects **four** (prior review §6.4).

---

**INVESTIGATION COMPLETE · STOPPING.** ⛔ **No code · no tests · no commits · no ADR change · no implementation · no re-run of the gate · no PO/ARB act simulated, drafted-as-signed, or recorded.** **This process does not verify or accept its own findings.**

**Traceability:** prior review `2026-08-18-EM-DOM-001-architecture-review.md` · referral · Phase-1 map (evidence, not premise) · authorization record + Amendment 1 · ADR-1 §6(a)/(c) · ADR-2 §6(a)/(b)/(e)/(g)/(h) · `EM-ARCH-001` §5d · `EM-GOV-033`/`035`/`036`/`038`/`057`/`059`/`065`/`066`/`068` · `D-3`, `D-6` · `EM-OPEN-055` (OPEN), `EM-OPEN-076`/`077` · source read directly: `AcceptanceGateDecision`, `ThresholdRule`, `RequiredVotes`, `ElectionCommittee`, `ElectionOperationalStatus`, `HaltedAtGate`, `ResumptionTarget`, `ElectionRestored`, `RecordVacancyEventHandler`, `FillCommitteeSeatHandler`, `ExpressCommitteePositionHandler`, the three `Repository` interfaces.
