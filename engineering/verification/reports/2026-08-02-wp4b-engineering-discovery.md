# WP-4B — Engineering Discovery Package

**Date:** 2026-08-02 · **Prepared by:** Principal Engineer, under the Principal Architect
**Question:** **What must exist before WP-4B can legitimately be authorized?**
**Verdict:** ⏳ **authorization readiness cannot yet be determined — one sequencing assumption remains unresolved (§6).**
**Status:** discovery only. **No architecture redesigned · no governance modified · nothing implemented · no ruling drafted.**

> **Placement note.** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` returns `docs/publicdigit`. **This package is filed with the WP-4 artifacts instead, at ARB direction** — the WP-4 state correction and the WP-4A acceptance review live here. **Recorded rather than silently resolved.**

---

## 1. Business Capability

**From roadmap §WP-4, verbatim:** *"issuance request path (PM conclusion txn → issuance txn, INV-B1-bridged crash recovery)."* **No capability name is invented here; the roadmap's phrase is the name.**

| | |
|---|---|
| **Business objective** | **A concluded adjudication results in exactly one issued determination — including when the process crashes between the two transactions.** EPIC-004K §11: *"the conclusion + the fixed considered-set + the authority reference commit as one write to the PM record. This is the *working* record's fixation; the *permanent* fixation is the aggregate's."* |
| **Owning bounded context** | **Adjudication.** Both ends are in-context: the PM record (Application) and the Determination aggregate's issuance boundary |
| **Business responsibility** | **PM-5 → PM-6.** §10: `IssueDeterminationCommand` travels *"toward the Determination aggregate's issuance boundary (in-context)"*, carrying *"the authority's decided content + the fixed considered-set."* **R-4-expanded seats the permanent fixation at the aggregate, so a PM-side-only conclusion is by design not enough** |

**The constitutional point the capability protects, in EPIC-004K's own words:** *"a PM-side-only fixation would make 'issued' and 'what it was issued from' two separable records — exactly what P3 forbids."*

## 2. Scope

### Inside WP-4B — established by evidence

**The gap is observable in the code.** `AdjudicationProcessManager::receiveRulingDecision()` ends at:

```php
$this->store->save($process->concludeRulingRequested(...));
```

**and nothing follows it.** `grep -rn "issueDetermination" app/` returns **only** the service triple (`CoordinatesAdjudication` · `AdjudicationService` · `TransactionalAdjudicationService`) and a comment in Contestation. **No production caller connects a concluded process to issuance.** The seam does not exist.

**Roadmap keystone, already specified as WP-4B's acceptance criterion:** *"the crash-recovery seam (concluded-but-unissued → redrive → **exactly one** determination)."*

### Explicitly outside

**`AdjudicationFailureDeclared`** (WP-4C) · **the authority-decision intake port and interim adapter** (WP-4D) · **the loop-head inbox wiring** (WP-4A, accepted under R-69).

### ⚠️ Ambiguity identified, not resolved

**EPIC-004K §8 lists a fifth external trigger — *"Issuance confirmation — the aggregate's acceptance (or INV-B1 refusal → reconciliation; §12)"* — and §9 lists `DeterminationIssued` as *"observed for confirmation… Closes PM-6's loop."***

**Roadmap §WP-4's row does not mention confirmation observation.** **So it is unclear whether PM-6's confirmation half is inside WP-4B or is a further slice.** **The two readings produce different RED boundaries, and the ambiguity is recorded for the ARB rather than settled here.**

## 3. Dependency Analysis

| # | Dependency | Class | Why it exists |
|---|---|---|---|
| 1 | **INV-B1** — *at most one determination per challenge* | **Strategic** | It is what makes redrive safe. EPIC-004E records it as a **boundary** invariant, *"deliberately NOT the aggregate's"* — the seam relies on the boundary guard, not on aggregate state |
| 2 | **Determination issuance** (`CoordinatesAdjudication::issueDetermination`) | **Capability** | ✅ **exists.** The receiving end of the seam is built and tested |
| 3 | **PM conclusion** (`receiveRulingDecision` → `concludeRulingRequested`) | **Capability** | ✅ **exists.** The originating end is built and tested |
| 4 | **`TransactionManager` port + `TransactionalAdjudicationService`** | **Technical** | ✅ **exist.** The two-transaction shape has machinery already; `transactional(callable $work): mixed` |
| 5 | **A way to find a concluded-but-unissued process** | **Technical** | ⛔ **does not exist.** `AdjudicationProcessStore` has exactly five methods — `nextIdentity` · `activeForChallenge` · `latestForChallenge` · `save` · `dueForHorizon` — **and none of them can answer the redrive query the keystone requires** |
| 6 | **Authority-decision intake** (WP-4D) | **External** — *as recorded* | The WP-4 plan states the seam *"depends on the authority-decision intake, which is a recorded external outside this authorization."* **See the note below** |
| 7 | **Conflict handling family** — INV-B1 refusal → reconcile · redelivery → ack · another writer → dead-letter + escalate (EPIC-004K §12) | **Strategic** | The rules are written; `ConflictingDetermination` exists in Contestation. **Whether Adjudication needs its own counterpart is undetermined by the evidence** |
| 8 | **Authorization** | **Governance** | ⛔ **none.** R-68 split WP-4B out; **splitting is not authorizing** |

### ⚠️ On dependency 6 — the recorded claim and what the code shows

**The plan records the dependency. The repository shows something narrower.**

**`receiveRulingDecision()` is a public method already invoked directly by existing tests** (`AdjudicationProcessManagerTest`, `AdjudicationHorizonTest`). **The seam begins *after* that call returns.** **On the evidence, WP-4B's seam appears buildable and testable against the existing entry point, with WP-4D supplying the *production trigger* rather than the *capability being wired*.**

> **⛔ SUPERSEDED 2026-08-02 by `2026-08-02-wp4b-issuance-input-ownership-investigation.md`: NEITHER reading is established. `IssueDeterminationCommand` requires three inputs — `Jurisdiction`, `EvidenceEnvelopeRef`, `ContestedOutcomeRef` — that the concluded record does not hold and `receiveRulingDecision()` does not accept, so WP-4D would not close the gap and WP-4B is not buildable now. **What the gap MEANS is not settled**; §5 of that report enumerates candidate producers without choosing. §6's verdict here is unchanged; its reason is sharper.**
>
> **This is an observation, not a finding, and it is deliberately not resolved.** **The accepted planning artifact says the dependency exists; the code suggests it may be a dependency on production triggering rather than on implementability.** **Which reading governs is the ARB's to settle — and settling it changes WP-4B's sequencing.**

## 4. Engineering Readiness

| Required | Exists? | Evidence |
|---|---|---|
| **Authorization** | ⛔ **No** | no row in `ADR-AIP-LOG-Platform-Rulings.md`; **R-68 defines WP-4B, it does not permit work to start** |
| **Implementation plan** | ⛔ **No** | `.claude/plans/WP-4-apm-wiring.md` exists but its Phases 1–10 and its G-2 decision were scoped to the inbox wiring; **it contains no WP-4B plan** |
| **RED backlog** | ⛔ **No** | the plan's Phase 10 is *"restricted to what WP-4 can prove without WP-3B"*, six keystones, **all of them 4A's**. The seam is listed under *"Deferred to their own slices (not WP-4's RED)"* |
| **Acceptance criteria** | ⚠️ **Partly** | the **roadmap keystone exists** — *concluded-but-unissued → redrive → exactly one determination* — but it has not been expanded into a slice-level criteria table |
| **Developer guide** | ⛔ **No** | `developer_guide/adjudication/` holds 04 (WP-4A consumption); nothing for the seam. **Correctly absent — the guide ships with the step** |
| **Implementation boundary** | ⛔ **No** | no statement of which files may change, and §2's ambiguity would have to be settled to write one |

## 5. Tactical DDD Verification

**Assessed against what WP-4B would touch, using written architecture only.**

| Concern | Assessment | Basis |
|---|---|---|
| **Aggregate boundaries** | **preserved** — the seam does not enter the aggregate. Issuance already flows through the existing command and its boundary guard; **INV-B1 is a boundary invariant, not the aggregate's** | EPIC-004E §INV-B1 |
| **Repository boundaries** | ⚠️ **one tactical change appears required — identified, not designed.** The PM store's surface is enumerated in §11 as *"create-on-open, append admissions/demands, record conclusion, load-for-reaction, due-timer query. No query zoo"*, and **no member of it can find a concluded-but-unissued process.** **Whether the redrive query falls inside that enumerated surface or extends it is a tactical decision this package does not take** | §11 · `AdjudicationProcessStore` (5 methods) |
| **Application orchestration** | **preserved** — the two-transaction shape is expressible with the existing `TransactionManager` port and the `TransactionalAdjudicationService` decorator. **No new orchestration concept is implied by the evidence** | `Port/TransactionManager.php` |
| **Published language** | **unchanged** — `IssueDeterminationCommand` travels *"toward the Determination aggregate's issuance boundary (**in-context**)"*. **WP-4B produces no integration event** | EPIC-004K §10 |
| **Bounded-context ownership** | **unchanged** — both ends are Adjudication's. **No crossing, and ADR-T23 is untouched: the authority decides, the manager receives** | ADR-T23 |

**One tactical change is identified: a store-side means of finding concluded-but-unissued processes. It is named because the evidence demands it, and it is not designed here.**

## 6. Authorization Readiness

> # ⏳ AUTHORIZATION READINESS CANNOT YET BE DETERMINED — one sequencing assumption remains unresolved

**Single evidence-based reason:** **the accepted planning artifact records WP-4B as depending on the authority-decision intake, and the repository shows a callable entry point that suggests the seam could be built without it (§3, dependency 6).** **Until that assumption is resolved, the appropriate RED boundary cannot be established with confidence** — §2's scope ambiguity compounds it, but does not cause it.

### Classification of the unresolved assumption — so it is not mistaken for a defect

| Observation | Classification |
|---|---|
| **The repository and the roadmap disagree about dependency sequencing** | **Engineering Planning Assumption** |
| Strategic architecture | **unchanged** |
| Tactical architecture | **one implementation question** (§5, the store query) |
| Governance | **authorization decision required** |

> **The unresolved item is a planning assumption requiring governance disposition. It is NOT an architectural defect, and nothing in this package should be read as reporting one.**

### Why the verdict is not stated as "not ready"

**"Not ready" is a governance conclusion, and the evidence does not compel it.** What the evidence establishes is narrower and, in every other respect, favourable:

| Established | |
|---|---|
| Capability defined in accepted artifacts | ✅ |
| **Both ends of the seam exist in code** | ✅ |
| Acceptance keystone already written in the roadmap | ✅ |
| Strategic change required | ❌ **none** |
| Architectural blocker | ❌ **none identified** |
| Tactical change | **exactly one, identified and not designed** (§5) |
| **Dependency assumption** | ⚠️ **unresolved — the one open item** |

> **A package with no architectural blocker, both ends built and its acceptance criterion already written is not fairly described as "not ready."** **It is a package whose *sequencing* is undetermined.** **Whether that warrants deferral, or authorization with the assumption resolved as the slice's first act, is the ARB's to decide — engineering's contribution ends at naming the unresolved assumption.**

## 7. Next Engineering Step

> ### Determine whether WP-4B's recorded dependency on the authority-decision intake (WP-4D) is a true prerequisite.

**One activity. Evidence-based. It dominates every alternative because it is the only open item that changes the *sequence* rather than the *content* of the remaining work:**

- **If it is a true prerequisite** — WP-4B cannot be authorized until WP-4D is defined, authorized and built, and §WP-4's critical path runs **4D → 4B**.
- **If it governs only the production trigger** — WP-4B is buildable now against the existing `receiveRulingDecision()` entry point, and §WP-4's critical path shortens by a whole slice.

**Writing WP-4B's plan and RED boundary before this is settled would produce a boundary that may have to be discarded** — and §2's ambiguity is cheaper to resolve inside that plan than ahead of it.

---

**Traceability:** `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-4 · §keystone tests · `docs/implementation/EPIC-004K_*.md` §8 · §9 · §10 · §11 · §12 · `docs/implementation/EPIC-004E_Determination_Invariants.md` (INV-B1) · **ADR-T23** · `app/Contexts/Adjudication/Application/Process/AdjudicationProcessManager.php` · `.../Port/AdjudicationProcessStore.php` · `.../Port/TransactionManager.php` · `.../Service/CoordinatesAdjudication.php` · `.claude/plans/WP-4-apm-wiring.md` §Phase 10 · **R-68** (the subdivision). **Discovery only — no architecture proposed, no governance modified, no code changed, no ruling drafted.**
