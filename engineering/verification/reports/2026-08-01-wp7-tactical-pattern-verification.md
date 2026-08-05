# WP-7 Tactical Pattern Verification Commission

**Date:** 2026-08-01 · **Role:** Senior DDD Architect · **Commission:** does every planned responsibility sit in the **correct tactical DDD pattern**? Placement verification only — no redesign, no new concepts, no strategic revisiting.
**Repository Integrity Gate:** ✅ PASSED.

> ## THE COMMISSION'S RESULT — ONE ASSIGNMENT IS WRONG, AND THE AUTHORITY SAYS SO
>
> **P-1 — EPW was planned as an Application Service computation. Constitutional Policy 2 names it, in its own ARB naming clause, "a single DOMAIN CONCEPT, not a configuration value."**
>
> My plan wrote *"a value object only if a keystone demands it; default is no."* **That default was set against the authority's own framing.** The remaining assignments verify clean.

---

## 1. Tactical responsibility inventory

| # | Responsibility (architectural, not implementation) |
|---|---|
| R1 | Resolve Contestation Window and Legal Safety Margin per election type |
| R2 | Provide Maximum Adjudication Duration |
| R3 | **Represent an election's Evidence Preservation Window** |
| R4 | Decide whether a given audit folder may be deleted |
| R5 | Map an audit folder to its election |
| R6 | Perform the deletion / iterate the audit tree |
| R7 | Read configuration values |

## 2. Pattern assignment matrix — one primary pattern each

| # | Responsibility | **Primary pattern** | Layer |
|---|---|---|---|
| R1 | CW + LSM resolution | **Port** (+ Adapter) | Application port · Infrastructure adapter |
| R2 | MAD provision | **Existing Port** — `AdjudicationDurations` | Application port (Adjudication's) |
| R3 | **The EPW itself** | ⚠️ **VALUE OBJECT** *(corrected — was "application-service computation")* | **Domain concept** |
| R4 | May this folder be deleted? | **Application Service** | Application |
| R5 | Folder → election | **Infrastructure Service** | Infrastructure |
| R6 | Deletion + traversal | **Infrastructure (CLI adapter)** | Infrastructure |
| R7 | Config reading | **Adapter** | Infrastructure |

## 3. Pattern justification — including the neighbours rejected

### R3 · Evidence Preservation Window — **Value Object** *(the correction)*

**Why VO:** Policy 2's ARB naming clause is explicit — *"a single **domain concept**, not a configuration value: retention is a business policy, not a technical setting."* An EPW has **no identity** (two windows with the same anchor and duration are the same window), it is **immutable**, and it answers a **domain question** — *is this window still open at time T?* **That is a Value Object by definition.**

**Why not Application Service** *(what I had planned)*: an application service **orchestrates**; it does not *embody a business concept*. Computing EPW inside one would scatter a named domain concept across orchestration code and leave the ubiquitous language with no home for a term the constitution defines. **This is the same defect class as WP-6's AP-1** — a business decision materializing where no owner authorized it, except here the concept, not the value, is misplaced.

**Why not Domain Service:** a domain service exists for logic that fits **no** entity or VO. EPW fits a VO perfectly, so a service would be a wrapper around arithmetic that belongs to the value itself.

**Why not Entity/Aggregate:** no identity, no lifecycle, no invariant to protect across time. **This is the answer to *"why intentionally no aggregate?"*** — an aggregate exists to guard invariants over a mutable, identified thing. EPW is a *computed fact*; the audit folder is a *file*. **Nothing here has a lifecycle to protect**, and inventing an aggregate would be pattern application without a domain reason.

**⚠️ Placement question this raises — flagged, not solved:** the Retention/Audit area **has no `Domain` folder** (`app/Contexts/*` holds eleven contexts; none is Retention). **A VO needs a home.** The minimal faithful option is a plain-PHP VO placed with the retention code **without creating a bounded context**. *Whether Retention should become a context is a **strategic** question and explicitly outside WP-7.*

### R1/R2 · Durations — **Port**

**Why Port:** the application must *ask for* a duration it may not *decide*. A port makes Q-2's ownership structural (§81 · WP-6's precedent) — the same reason `AdjudicationDurations` exists.

**Why not Repository:** a repository reconstitutes **aggregates from persistence**. A duration is neither an aggregate nor persisted state — it is **policy**. *(This answers "why Port instead of Repository?")*

**Why not read config directly:** that is precisely **AP-1** — infrastructure choosing a business value. The port keeps the Application layer facade-free (house Rule 2) and the decision with its owner.

**Why R2 reuses Adjudication's existing port:** MAD has **one home** (AP-2). Copying it into a retention config would be Decision Duplication — the exact defect corrected in WP-6.

### R4 · The deletion decision — **Application Service**

**Why Application Service:** it *coordinates* — resolve election, obtain durations, construct the EPW, ask the VO whether the window is open. **It decides nothing itself.**

**Why not Domain Service:** with R3 corrected to a VO, the business question is answered **by the VO**. What remains is orchestration.

**Why not Infrastructure:** *(this answers "why is the guard not infrastructure?")* — the guard **applies a constitutional invariant**. Placing it in the CLI command would bury Policy 2's application inside a delivery mechanism, where it could not be reused or tested independently of the console. **Infrastructure performs; it does not apply policy.**

### R5 · Folder → election — **Infrastructure Service**

Parsing `{slug}_{Ymd}_{Hi}` is a **storage-format concern**, an artifact of how `ElectionAuditService` names directories. It carries no business meaning and would change if the layout changed. **Adapters translate; they never decide.**

### R6/R7 · Deletion, traversal, config reading — **Infrastructure / Adapter**

Filesystem and configuration mechanics. `AuditCleanup` remains a **CLI adapter**: it invokes the application service and performs what it is told.

## 4. Boundary verification

| Rule | Holds? |
|---|---|
| Domain logic inside the domain | ⚠️ **only after P-1 is applied** — EPW moves from an application computation to a VO |
| Orchestration in the application layer | ✅ R4 coordinates and decides nothing |
| Infrastructure performs technical work only | ✅ R5/R6/R7 parse, traverse, read |
| **Policy consumed, not owned** | ✅ every duration arrives through a port; **nothing is defaulted or clamped** |
| Adapters translate, never decide | ✅ the config adapter **fails closed** rather than substituting |
| No responsibility crosses layers | ✅ after P-1 |

## 5. Dependency direction review

```
Infrastructure  (AuditCleanup CLI · folder parser · config adapter)
        │ depends on
        ▼
Application     (retention service · durations ports)
        │ depends on
        ▼
Domain concept  (EvidencePreservationWindow VO — pure PHP, no framework)
```

| Check | Result |
|---|---|
| Domain depends on nothing external | ✅ the VO is arithmetic over dates — **no framework, no config, no Eloquent** |
| Application depends on abstractions | ✅ two ports; no facade |
| Infrastructure depends on application/domain contracts | ✅ adapters implement ports |
| **Configuration consumed through ports, not shaping business logic** | ✅ **and this is what P-1 protects:** with EPW as a VO, config supplies *numbers* while the *concept* lives in the domain. Left as an application computation, configuration shape would have dictated the concept's shape |
| Inversions | **none** |

## 6. Tactical simplicity assessment

| Question | Answer |
|---|---|
| Can any responsibility reuse an existing abstraction? | ✅ **R2 does** — Adjudication's `AdjudicationDurations`, not a copy |
| Is each new pattern necessary? | **Port (R1)** — yes, CW/LSM have no home · **VO (R3)** — yes, required by Policy 2's naming · **Application Service (R4)** — yes, something must coordinate · **Infrastructure (R5–R7)** — mechanics |
| Has convenience created concepts? | **No.** No aggregate, no entity, no domain service, no repository, no factory, no domain event |
| Simplest model satisfying the strategic design? | ✅ **one VO · one new port · one application service · three infrastructure pieces** |

**Net change from this commission: +1 Value Object, −1 responsibility inside the application service.** The model gets *simpler to describe*, because a named constitutional concept now has one obvious home instead of being distributed through orchestration.

## 7. RED readiness recommendation

| Criterion | Status |
|---|---|
| Every responsibility has a tactical home | ✅ **after P-1** |
| Every pattern justified, neighbours rejected explicitly | ✅ §3 |
| Dependency directions conform | ✅ inward only |
| No unnecessary tactical concepts | ✅ |
| No architectural ambiguity remains | ⚠️ **one placement question** — where the VO lives, given no Retention `Domain` folder exists |

> ### **RED-READY once two things are settled — and neither is a strategic question:**
> 1. **Adopt P-1** — EPW becomes a **Value Object**, per Policy 2's own naming.
> 2. **Confirm the VO's placement** — plain-PHP VO beside the retention code, **without** creating a bounded context. *(If the ARB instead judges that Retention warrants a context, that is strategic and outside WP-7.)*

**The two gates from the plan are unchanged and unaffected:** WP-6 slice acceptance · the **EPW anchor** business decision (or approval of fail-closed).

**No further tactical review is warranted.** Every remaining question is either a business value or a one-line placement choice.

---

**Traceability:** **Constitutional Policy 2** (primary — `EPIC-003 §THE FOUR DECISIONS` №2, incl. the ARB naming clause *"a single domain concept, not a configuration value"*) · EPIC-004K §142 · WP-7 tactical plan `.claude/plans/WP-7-retention-alignment.md` · WP-6 findings **AP-1** (fail closed; no substituted business value) and **AP-2** (one home per parameter) · house Rule 2 (no facades in Application) · `app/Contexts/` inventory (no Retention context). **No code written; no plan redesigned; no strategic decision revisited.**
