# EM-IMPL-002 — Phase 1B
# Support Doubles Review — the six test doubles as ONE test-infrastructure boundary

**Architecture/DDD review · 2026-08-17 · scope: the six files under `tests/Unit/Contexts/Election/OperatingCoreApplication/Support/` at commit `1f4b4c5f` · nothing modified — files, RED tests, and production code all untouched (verified: `app/` clean; no new double introduced)**

> ## VERDICT — five 🟢 · one 🟡 · collective: 🟡 GO WITH CONDITION
> **The Support boundary is safe for the RED baseline.** All six doubles are passive: every business value they hold is injected by the test or delegated to production predicates; none decides meaning, authority, lifecycle, or constitutional rules; the D-1 authority wall holds across the entire directory and is *mechanically guarded* by the suite itself. **One condition is registered** (ProtocolAppend's publicly mutable entry store — currently benign, verified) and **one guard-completeness obligation** goes to the GREEN review. No file needs changing before GREEN begins.
>
> Governing principle applied throughout: *doubles may simulate infrastructure boundaries, observe behaviour, and provide deterministic fixtures — never become a second Domain Layer or a substitute for constitutional authority.*

**Evidence base:** the six Support files · all twelve production ports/VOs they reference (read in full) · the base fixture `OperatingCoreApplicationTestCase` (the only wiring site) · usage census of every convenience member across all nine test files · `StructuralApplicationGuardsRedTest` · the RED acceptance record (`f5edb951`) · proposal §3a/§8 anchors (`…EM-ARCH-002-increment2-application-layer-proposal.md:87,90`).

---

## 1 · InMemoryProtocolAppend — 🟡 GO WITH CONDITION

**A · Contract.** Production port `Port\ProtocolAppend` — one method, `append(ProtocolEntry): void`. The port is the Election Protocol: the permanent record outside the progression chain (EM-GOV-005, EM-VOC-008, B-7, F-PROTO-1). The tests need it because *what was recorded* is the primary observable of every handler (facts and refusals both), and the port itself deliberately has **no read surface** — so the double must add one for assertions.

**B · Permitted.** Simulating append; holding the appended `ProtocolEntry` list; exposing **read-only projections** for assertions. All six read helpers are projections computed from stored entries using **production predicates only**: `eventEntries()`/`refusals()` split on `ProtocolEntry::isRefusal()` (the entry's own structural predicate — the double classifies nothing); `eventKindSequence()` *reads* `$e->kind` (assigned by the caller at construction — the double never assigns a kind); `eventClassSequence()`/`entriesOfEvent()`/`countEventsOf()` reflect on stored event objects.

**C/D · Forbidden behaviour and sovereignty — clean.** It derives no facts (`append` stores verbatim) · classifies nothing (delegates to `isRefusal()`) · **never decides `HistoryKind`** (kinds arrive inside constructed entries; `HistoryKindAssignmentRedTest` pins the *handler's* assignment — the double merely lets the test see it) · manufactures no `DomainEvent` (only `get_class` on events the handler recorded) · no dedup, no ordering changes, no filtering on write · provides no persistence semantics (a PHP array is not a durability claim; F-PROTO-1 properties 1/5 remain an adapter's future obligation, G-6) · **is not an event bus**: nothing subscribes, nothing dispatches, nothing downstream consumes entries except test assertions. The feared shape — protocol double turning into hidden event processing — has no substrate: the double has no callbacks, no observers, no reaction of any kind.

**E · Conveniences.**

| Member | Class |
|---|---|
| `eventEntries()` · `refusals()` · `eventClassSequence()` · `eventKindSequence()` · `entriesOfEvent()` · `countEventsOf()` | **1 — pure observation** (projections via production predicates) |
| `public array $entries` | **2 — potentially architectural**, see the condition |

**F · GREEN leakage — none.** GREEN code is typed against `ProtocolAppend` (one `append` method); every helper is invisible to it. No helper's existence forces any handler behaviour the record didn't pin.

**🟡 THE CONDITION.** `$entries` is a **public, writable** array on a double whose own docblock claims *"append-only in meaning … nothing erases."* A test could truncate or rewrite recorded history mid-scenario and then assert on the doctored record — the silent-truncation failure (F-PROTO-1 property 5) reproduced *inside the test evidence*. **Verified today: benign** — zero writes anywhere in the suite; exactly one raw read (`QueryServicesRedTest:110`, asserting emptiness). The append-only meaning is currently protected by convention only, in the one place this programme keeps learning that convention is not protection.
**Smallest correction (registered, NOT applied — this review modifies nothing):** make `$entries` private, add a read accessor (e.g. `all(): array` returning a copy), and point the one raw read at it — a three-line change **at the next authorized touch of the Support files**. Until then the RED baseline may proceed: the exposure is latent and the current suite is verified not to exercise it.

---

## 2 · InMemoryElectionCommitteeRepository — 🟢 GO

**A.** `Repository\ElectionCommitteeRepository` (AG-1): `find(ElectionId): ?ElectionCommittee` · `save(ElectionCommittee): void`. Repo Rule 9 contract (aggregates only). Tests need retrieval and storage simulation for handler flows.
**B.** A map keyed by election id; `find` = lookup, `save` = store + count, `seed` = store without counting.
**C/D.** Enforces no business rule, computes no domain state, triggers no event or lifecycle transition, implements no transaction, deduplicates nothing (an overwrite is storage simulation, §7 below). It cannot decide election meaning: it never inspects the aggregate beyond its id.
**E.** `saveCount` — **class 1**: usage census shows it asserted **exclusively as zero** (UC-4 "mutates NO aggregate", query purity UQ-1…UQ-4 — both authorized pins, §4). `seed()` — **class 1**, and structurally safe: it is not on the production interface, so interface-typed GREEN code **cannot reach it**; the fixture/handler-save separation (seed doesn't count) is exactly right.
**F.** No leakage — the extra members are invisible through the port type.

## 3 · InMemoryAcceptanceGateDecisionRepository — 🟢 GO

As §2, keyed by `(electionId, gate)` — mirroring the contract's own identity ("one decision record per gate per election," `find(ElectionId, GateDesignation)`). The key shape is *retrieval identity copied from the port signature*, not an invented uniqueness rule. Notably faithful to **EM-GOV-068/DD-1**: it stores the aggregate only — no interval-state column exists to drift, so the "derived, never persisted" rule cannot be violated here even by accident. `saveCount`/`seed()` as §2 — class 1.

## 4 · InMemoryRecoveryProcessRepository — 🟢 GO

As §2, keyed by `(electionId, PeriodKind)` — again the port's own `find` identity (EM-GOV-061(b): the allowance is per election, never per failure). **Deliberately does NOT enforce** "a second instance of the same kind must never be created": a wrong second `save` would silently overwrite. That dumbness is **correct** — the invariant is domain/application property to prove, and a double that refused would *hide the defect from the test* by converting it into infrastructure behaviour. Clock readings: none computed, none stored (DD-1 respected by absence). `saveCount`/`seed()` — class 1.

## 5 · FixedInstantSource — 🟢 GO

**A.** `Port\InstantSource`: `now(): RecordedInstant` — recording instants only, no schedule semantics, no civil-time meaning (D-8, EM-OPEN-024, EM-ARCH-001 §5c).
**B/C/D.** Holds one injected instant; returns it. It **implements no scheduling, calculates no elapsed period, decides no expiry, advances no lifecycle** — interval arithmetic lives in `RecordedInstant::secondsUntil()` (domain), and period consequences live in the aggregates. Nothing attaches it to an OPEN gate or to inaction (G-3/D-4 — it is pull-only).
**Caller-supplied timestamps through commands:** verified excluded — every command factory in the base fixture is instant-free; the handler obtains the recording instant from this port (D-8 pinned in the wiring comment and honored by the command shapes).
**E.** `setNowEpoch()` — **class 1**: test-time control between acts. Its existence pins only what D-8 already establishes: each recorded fact carries the instant *at recording*, so handlers read `now()` per act rather than caching a constructor value. That is the authorized meaning, not a new decision.
**F.** No leakage — `setNowEpoch` is invisible through the port.

## 6 · FixedServicePolicySnapshot — 🟢 GO

**A.** `Port\ServicePolicySnapshot` (B-5 ACL): `snapshotFor(PeriodKind): PolicyBinding`. The governed durations live *outside* Election (EM-GOV-014 Part 2, EM-OPEN-050(a)); the port hands over only `(policyVersion, duration)` and can never supply a consequence (EM-OPEN-047, EM-GOV-058).
**B/C/D.** Returns injected, immutable `PolicyBinding` fixtures — it **calculates no duration** (values injected; positivity enforced by the production VO, not the double), **decides no start/expiry**, **derives no consequence**, and cannot become a policy engine: it contains no computation at all. The consequence-wall is structural — `PolicyBinding` physically has no consequence field to smuggle one through.
**E.** `requestedKinds` — **class 1**: observation letting a test pin that the handler snapshots for the *correct* kind (§8a, authorized) — and, jointly with the base fixture's UC-3/UC-4 wiring (no snapshot injected at all), that restoration/expiry flows never acquire a policy. The `RuntimeException` on a missing fixture is **class 1 and correctly typed**: an infrastructure exception (repo Rule 8 — not user-visible), so no test can mistake fixture misconfiguration for a domain refusal.
**F.** No leakage.

---

## 7 · G — Persistence boundary (collective)

The in-memory doubles establish **no** persistence semantics, and the review confirms each absence is genuine: **no transaction/unit-of-work** (save is immediate; nothing to commit or roll back — the persistence increment stays future, G-6) · **no concurrency model** (single-threaded maps; no locks, no versions) · **no uniqueness guarantee** (keyed overwrite is storage simulation; the AG-2/AG-3 one-per-key identities are the *ports' find signatures*, and their enforcement stays with domain/application logic) · **no idempotency** (a duplicate save overwrites and still increments `saveCount` — nothing silently absorbs duplicates, so **Q-UC4** — duplicate-expiry idempotency — remains genuinely OPEN, exactly as the acceptance record defers it; the doubles do not answer it by accident).

## 8 · H — Authority wall (RED-4 / D-1), swept

**Confirmed across the entire Support directory and the whole suite:** no class implements, mocks, stubs, fakes, or binds `OrganisationalAppointmentAuthority`. The only occurrences are ① the prohibition prose in `InMemoryProtocolAppend`'s docblock (a statement *of* the wall, not a breach of it), and ② the guard's own scan patterns. The production port file exists untouched (a wall, not an omission — B-3). The base fixture wires the port **nowhere**; no handler constructor accepts it. **The wall is additionally mechanical:** `StructuralApplicationGuardsRedTest` scans `app/` *and* `tests/` for `implements` and every common mock-factory pattern, and separately asserts the application namespace never even *references* the name. This matches the independent Governance observation in the acceptance record.

**One guard-completeness gap found (obligation for the GREEN review, NOT a Support defect):** the scan's regexes cover `implements …Authority` and mock factories, but not an **`extends`** route — `interface X extends OrganisationalAppointmentAuthority` plus `implements X` would evade both patterns. No such construct exists today (swept). Register: strengthen the guard with an `extends` pattern (or extend the reference-ban to `tests/`) at the next authorized test edit.

## 9 · M — Cross-double interaction

**Passive collaborators, confirmed structurally:** no Support file imports or references another Support file; no double calls, notifies, or observes another; the *only* composition site is the base fixture, whose wiring is the authorized record's own pin (proposal §3a "constructor-injected ports only; no new port", §8 flow diagrams — including the deliberate asymmetries: UC-3/UC-4/UC-5 receive **no** policy snapshot). The feared accidental architecture — `repository → application → protocol → hidden process manager` — has no substrate: the protocol double reacts to nothing, the repositories trigger nothing, and no double holds a reference through which a process could be managed. Data flows one way (handler → double), observations flow one way (double → assertion).

## 10 · N — Verdicts and register

| Double | Verdict |
|---|---|
| `InMemoryProtocolAppend` | 🟡 **GO WITH CONDITION** (§1 — public mutable `$entries`; benign today, verified) |
| `InMemoryElectionCommitteeRepository` | 🟢 GO |
| `InMemoryAcceptanceGateDecisionRepository` | 🟢 GO |
| `InMemoryRecoveryProcessRepository` | 🟢 GO |
| `FixedInstantSource` | 🟢 GO |
| `FixedServicePolicySnapshot` | 🟢 GO |
| **Collective Support boundary** | 🟡 **GO WITH CONDITION** — safe for the RED baseline; one registered condition, zero blocking issues |

**New architectural obligations for the GREEN review:**
1. Confirm handlers type-hint **the port interfaces only** — which structurally forecloses `seed()`, `saveCount`, `setNowEpoch`, `requestedKinds`, and every protocol helper from production reach.
2. Confirm GREEN enforces the AG-3 one-process-per-kind rule in domain/application logic (find-before-start) and **does not lean on the doubles' map-overwrite** as uniqueness or idempotency.
3. Confirm every `ProtocolEntry`'s `HistoryKind` is decided by domain/application semantics traceable to P-2H — the double never assigns kinds, so the GREEN review must check the assignment logic itself.
4. Confirm UC-3/UC-4/UC-5 GREEN code acquires **no** policy snapshot (the wiring omits the port; the review should verify no back-door acquisition).
5. Strengthen the RED-4 guard with an `extends` pattern (§8) at the next authorized test edit.
6. Apply the §1 condition (private `$entries` + accessor) at the next authorized Support touch.

**Accidental design decisions that must explicitly remain OPEN (none was closed by the doubles — verified):** transaction/unit-of-work semantics · storage-level concurrency and uniqueness enforcement · duplicate-expiry idempotency (**Q-UC4**) · refusal propagation shape (**Q-REF** — the fixture's `toleratingDomainRefusal` keeps both shapes live) · store technology and the durability/truncation-resistance realization of F-PROTO-1 properties 1/5 (an in-memory array decides nothing) · the constitution fact type (**Q-UC5**, carried).

**Confirmation:** no existing production code was changed — `app/` byte-clean in the working tree; commit `1f4b4c5f` was tests-only (independently attested in the acceptance record); this review changed no file whatsoever, including the six under review and the RED tests. *(One pre-existing untracked file, `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php`, predates this review and was not touched or reviewed.)*

---

**Traceability:** commit `1f4b4c5f` (RED suite) · RED acceptance record `f5edb951` · proposal `2026-08-17-EM-ARCH-002-increment2-application-layer-proposal.md` (§3a:87, ports:90) · boundary authorization `2026-08-17-EM-ARCH-002-boundary-authorization-registration.md` · ports read: `ProtocolAppend`/`ProtocolEntry`/`RefusalRecord`/`HistoryKind`/`InstantSource`/`ServicePolicySnapshot`/three AG repositories/`RecordedInstant`/`PolicyBinding`/`PeriodKind` · G-6 · W-10 · RED-4/D-1 · D-8/EM-OPEN-024 · B-5/EM-OPEN-047/EM-GOV-058 · EM-GOV-061(b) · EM-GOV-068/DD-1 · P-2H/F-PROTO-1 · repo Rules 8/9 · R-34.

---

> # PHASE 1B DELIVERED — collective 🟡 GO WITH CONDITION · no file modified · next: individual handler RED reviews, then GREEN
