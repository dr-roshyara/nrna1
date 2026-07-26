# EPIC-004 Architecture-to-Implementation Roadmap

**Kind:** implementation-governance artifact №1 — the refinement phase's root; everything else in the phase derives from it. **Role: Chief Engineer** — the question is never "what should the architecture be?" but *"how do we implement the approved architecture without violating it?"*
**Authorization:** Implementation Authorization (Refinement Scope Only), ARB 2026-07-26. This roadmap authorizes **nothing** to be coded; it is itself the artifact awaiting ARB approval, after which implementation begins per its gates.
**Immutable inputs:** the frozen chain (EPIC-004B..J) · EPIC-004K (APPROVED) · ADR-T21/T22/T23 (issued) · the Q-2 bootstrap policy (ratified — bootstrap, not constitutional) · the four Constitutional Policies · ADR-T1/T8/T11/T16/T19 · house rules (TDD RED-first · triple qualification per slice · dev guide per step = DoD · merge-gate as the stable interface · hydrator versioning vCurrent+vPrevious ONLY).
**Standing review questions for every slice (the Chief-Engineer conformance frame):** does it preserve the ratified boundaries? conform to ADR-T21/T22/T23? violate any frozen invariant? is the sequencing safe? are migrations reversible? do acceptance tests demonstrate *architectural conformance*, not merely behavior?

---

## 0. Scope inventory — what the approved architecture actually requires building

From the issued record, exhaustively: **(a)** `EvidenceSet` VO + `IssueDeterminationCommand`/`DeterminationIssued` additive schema v3 (ADR-T22) · **(b)** the APM itself — durable process store, state machine, conclude-time atomic fixation, event-logged record (EPIC-004K §§5–7, §11) · **(c)** `ChallengeRouted` publication + provenance-mint relocation + minting-allowlist update (ADR-T21) · **(d)** APM wiring — inbound consumption, issuance request path, `AdjudicationFailureDeclared` (event + integration counterpart), authority-decision intake port · **(e)** temporal machinery — demand deadlines, adjudication horizon, Expired handling (ruled policy) · **(f)** the finality evaluator (temporal policy; `finalize()` gains its caller) · **(g)** bootstrap-parameter configuration (per-election-type, org-overridable) · **(h)** **retention alignment** — the existing 30-day `audit:cleanup` violates the ratified Retention Invariant the moment windows go live; it must become EPW-aware · **(i)** end-to-end operational validation of the now-closed loop + triple qualification + CI.

**Two implementation dependency findings this roadmap surfaces** *(engineering findings derived from the approved architecture — not architectural discoveries; terminology per ARB Review Comment 2)*:
1. **ADR-T21 has a prerequisite the architecture consumed but did not own:** `ChallengeRouted` is only emitted by `Challenge::route()`, and the raise→admit→route path has **no application service and no production trigger** (the known "later backlog item" from PB-005). Publishing an event nothing emits is dead wiring. The raise-path slice is therefore **in this roadmap as WP-5**, flagged for explicit ARB scope confirmation (it is Contestation work the APM commission implied but did not contain). The *entry point* that lets a real actor file a challenge (UI/API) is **product work outside this roadmap** — flagged, not designed.
2. **The authority-decision intake has no production channel yet:** Q-1's published contract artifact is undecided by name and Governance-side. The APM's intake is a **port**; its first adapter is administrative/test-seam only, with the Governance contract's tactical design as recorded later work. The APM does not block on it.

## 1. Work-package decomposition

| WP | Content | Realizes | Size |
|---|---|---|---|
| **WP-1** | `EvidenceSet` VO · command field · `DeterminationIssued` payload **v3 (additive)** · hydrator window shifts to **(v3, v2) — v1 tolerance retired** per the versioning rule · aggregate `issue()` accepts and fixes the set (the ADR-T22-sanctioned succession) | ADR-T22 · R-4-expanded · INV-4 rider | S |
| **WP-2** | APM core: process-store migration + model (+`BelongsToTenant`) + mapper · state machine (Opened→Assembling⇄AwaitingDecision→Concluded×2 \| Expired) · **conclude-time atomic fixation** (conclusion + considered-set + authority-ref, one write) · unique-active-per-challenge (guard + unique index — the INV-B1 two-seat pattern) · event-logged admissions/demands | EPIC-004K §§5–6, §11 | M |
| **WP-3** | `ChallengeRouted` publication: Contestation outbox adapter case + hydrator + Canonical Event Catalog entry · provenance mint at the routing emission · `CoordinatesAdjudication` → `EventProvenance::fromConsumed` · `CorrelationIdMintingTest` allowlist update | ADR-T21 | S |
| **WP-4** | APM wiring: `(Adjudication, ChallengeRouted)` inbox handler + registry · issuance request path (PM conclusion txn → issuance txn, INV-B1-bridged crash recovery) · `AdjudicationFailureDeclared` event + integration counterpart + hydrator + catalog entry · authority-decision intake **port** + interim administrative adapter | EPIC-004K §§8–10, §12 · ADR-T23 | M |
| **WP-5** | Contestation raise-path minimal slice: raise→admit→route application services (aggregate methods exist, test-pinned) + chain-start provenance mint at raise · **ARB scope confirmation requested** (Contestation-side; the loop head's true beginning) | TP-2 · ADR-T21's prerequisite | M |
| **WP-6** | Temporal machinery: bootstrap-parameter config (per-election-type, org-overridable; INTERIM-marked) · adjudication-horizon timer → Expired (+ its announced fact; late decisions dead-letter per the ruled policy) · evidence-demand deadlines · **finality evaluator** (scheduled policy: window-closed ∧ unchallenged → `finalize()` via the transactional boundary; NO event — the armed condition holds) | EPIC-004K §7, §Q-2 · ruled expiry policy | M |
| **WP-7** | Retention alignment: `audit:cleanup` becomes **EPW-aware** (per-election arithmetic from the same config; nothing the Retention Invariant covers is deleted inside its window) | Constitutional Policy 2 · ratified bootstrap | S |
| **WP-8** | End-to-end operational validation: the full loop **with its head** over the real path (raise→route→APM→authority decision→issue→correct→resolve; and the failure-declared path) · triple qualification of every new element · CI green on the merge-gate | EPIC-003 R-1 closure · house qualification rule | M |

## 2. Dependency graph

```
WP-1 (EvidenceSet/v3) ──────────────┐
                                    ▼
WP-2 (APM core) ────────────► WP-4 (APM wiring) ────► WP-8 (E2E + qualification)
                                    ▲                     ▲
WP-3 (ChallengeRouted pub) ─────────┘                     │
WP-5 (raise path) ────────────────────────────────────────┘   (production head; test-seeded
                                                               head suffices for WP-4 dev)
WP-6 (temporal machinery) ── after WP-2 (timers act on the store); finality evaluator
                             independent of the APM (aggregate-only) but shares WP-6 config
WP-7 (retention) ─────────── after the WP-6 config exists; otherwise independent
```

**No cycles.** WP-1/WP-2/WP-3 are mutually independent and can proceed in parallel where capacity allows; the one-artifact-per-review rhythm still gates each individually.

## 3. Implementation order (recommended)

**WP-1 → WP-2 → WP-3 → WP-4 → WP-5 → WP-6 → WP-7 → WP-8.**
Rationale: WP-1 first because it is smallest, self-contained, and everything ruling-shaped depends on the v3 payload; WP-2 before WP-4 (nothing to wire without the core); WP-3 before WP-4 (the inbound event must exist as published language before its consumer registers); WP-5 after WP-4 so the head lands on a wired, tested APM (until then, test-seeded routed challenges — the existing `CorrectionLoopIntegrationTest` pattern — stand in); WP-6 after the store exists; WP-7 once the config it reads exists; WP-8 last, as the operational proof. **Each WP = one slice = one ARB review** (the established rhythm; no slice starts before its predecessor's acceptance).

## 4. Migration strategy

- **All schema changes are additive:** one new table (APM process store, tenant-scoped) · additive event payload v3 · config addition. No column drops, no destructive migration anywhere.
- **The one compatibility-sensitive act:** the hydrator window shift to (v3, v2). **Pre-deploy operational check:** zero pending v1 `DeterminationIssued` rows in `outbox_events` before the WP-1 deploy (expected: trivially true; verified, not assumed).
- **Rollback rules:** every WP reverts independently (drop table / unregister handler / revert adapter case). **Asymmetric rule for events (recorded):** *hydrators roll forward only* — a writer may be reverted to v2, but the (v3, v2) hydrator stays deployed so already-written v3 rows remain hydratable. Reverting the hydrator below any written version is forbidden.
- **The APM's two-transaction seam** (conclude → issue) is crash-safe by design: a concluded-but-unissued process re-requests issuance on redrive; INV-B1 makes the retry idempotent. This is an acceptance criterion, not a hope (WP-4 test).

## 5. Architectural acceptance gates (every slice, no exceptions)

1. `composer merge-gate` PASS (Architecture fitness suites · Deptrac 0 violations — **with the APM's Application/Infrastructure additions encoded in the per-context layer model** · greenfield PHPStan clean · GreenfieldCore regression 0 failed).
2. **Triple qualification** (Architecture · DDD · Trustworthiness — anonymity/forward-only/immutability/replay/causal-ordering/tenant-isolation) recorded per slice.
3. **Conformance-to-issued-record check** (the Chief-Engineer frame): the slice names which ADR/§ it realizes and demonstrates no frozen invariant is touched — INV-1..B1 tests stay green unmodified; modifying a frozen invariant's test is itself a red flag that routes to the ARB.
4. Dev guide per step (house DoD), grounded in committed code.

## 6. Testing strategy

- **TDD RED-first, literally, every class** (house rule; the one recorded historical lapse is the cautionary precedent).
- **Per-WP keystone tests (acceptance criteria in test form):** WP-1 — v3 round-trip + v2 tolerance + v1 rejection; the set is fixed in the event and immutable. WP-2 — exactly-once conclusion under concurrent redelivery; no admission after conclusion; unique-active-per-challenge under race (the INV-B1-style feature test). WP-3 — mint-at-route; `CorrelationIdMintingTest` green with the relocated allowlist; catalog completeness test green. WP-4 — the crash-recovery seam (concluded-but-unissued → redrive → exactly one determination); FailureDeclared publishes with correct provenance; late/duplicate authority decisions translate per the frozen family (replay/park/dead-letter). WP-5 — raise→route emits exactly one chain-start mint; TP-2 honored (Contestation never writes a Determination). WP-6 — horizon expiry under `FrozenClock`; late decision dead-letters; finality only when window-closed ∧ unchallenged; **Final still emits nothing**. WP-7 — nothing inside an open EPW is deleted; deletion resumes after closure. WP-8 — IT-style full-loop suites (upheld · dismissed · failure-declared · expired), ONE CorrelationId per conversation asserted end-to-end.
- **Reuse over invention (ER-03/04):** the existing patterns are the vocabulary — `FrozenClock`, org-scoped counting, outbox/inbox feature-test conventions, the translation-family unit tests.

## 7. Rollback / rollout order

Rollout follows the implementation order; each WP is dark until its consumer exists (publishing `ChallengeRouted` with no registered consumer is safe by design — events without consumers are the platform's normal state, not an error). The first *externally visible* behavior change is WP-6's finality transitions and WP-7's retention change — both flagged for operational announcement. Full-loop production activation is gated on WP-8's acceptance, and the challenge-filing entry point (product work, outside this roadmap) remains the final piece before real constitutional traffic.

## 8. Definition of Done — per slice and for the phase

**Per slice:** RED→GREEN evidence · keystone tests green · merge-gate PASS · triple qualification recorded · dev guide committed · conformance check (§5.3) recorded · **measurable conformance gate (ARB Review Comment 3): no deviation from a frozen ADR or architectural invariant without a recorded ARB decision — objectively verifiable: the slice's diff touches no frozen-invariant test and cites an issued ADR for every behavior it changes** · ARB slice acceptance. **Phase DoD:** all eight WPs accepted · WP-8's end-to-end proof green in CI · zero frozen-invariant tests modified · the two flagged externals (challenge-filing entry point; Governance authority-contract design) recorded as the next tracks' inputs, not silently absorbed.

## Open items surfaced by this roadmap (for the ARB at review)

1. **WP-5 ownership — EXPLICIT ARB DECISION REQUIRED (elevated to a gate per ARB Review Comment 1):** *Is WP-5 part of EPIC-004 implementation, or is it a prerequisite to be delivered by the Contestation workstream before EPIC-004 integration?* Roadmap recommendation: include in EPIC-004 (the loop head is this program's stated purpose); ownership stays clear either way — the decision, not the recommendation, settles it.
2. **WP-7 timing** — retention alignment could precede everything (it fixes a standing EPIC-003 finding) or ride the WP-6 config as sequenced here. Recommended: as sequenced (the arithmetic needs the config).
3. The two recorded externals (challenge-filing UX/API · Governance authority-contract artifact) — acknowledged as outside this authorization; owners to be assigned when their tracks open.

## Self-review (Chief-Engineer frame)

Every WP names the issued decision it realizes; nothing realizes an unissued idea ✅ · the two implementation dependency findings (raise-path prerequisite; authority-intake channel) are surfaced with recommendations, not silently absorbed into scope ✅ · no frozen invariant is touched anywhere; the one aggregate-code change (WP-1) rides its explicitly issued succession (ADR-T22) ✅ · migrations additive, rollbacks defined, the one compatibility-sensitive act has a pre-deploy check and an asymmetric-rollback rule ✅ · every slice carries architectural-conformance acceptance, not merely behavioral ✅ · no architecture is redesigned, no code is written here ✅.

**Stop condition: STOP.** This roadmap is the refinement phase's first artifact and authorizes nothing by itself. Await ARB review and approval — upon it, WP-1 begins as the first implementation slice under the established one-slice-one-review rhythm.

---
*Immutable inputs as headed · Codebase evidence: EPIC-003 inventories + the PB-005/PB-006/PB-007 implementation record (test conventions, merge-gate, scheduled-command and translation-family precedents) · Authorization: `EPIC-004_Q2_Resolution_Package.md` §RATIFIED·AUTHORIZED·TRANSITION RECOGNIZED.*

---

## APPROVED WITH THREE REVIEW COMMENTS (ARB, 2026-07-26 — all applied)

**Decision: APPROVED.** The ARB classifies this artifact as engineering governance, not architecture — the intended transition. Conditions applied in place: **(1)** WP-5 ownership elevated to an explicit ARB decision gate (open items §1) — *in EPIC-004, or a Contestation-workstream prerequisite?* — awaiting disposition; **(2)** "dependency discoveries" renamed **implementation dependency findings** (engineering findings derived from the approved architecture, reinforcing the Architect→Chief-Engineer role change); **(3)** the Definition of Done gains a measurable conformance gate: *no deviation from a frozen ADR or architectural invariant without a recorded ARB decision.*

**WP-1 authorization:** the ARB signalled comfort authorizing WP-1 upon these conditions; the explicit authorization is requested per item and recorded when given — not inferred from the signal.

## GATES RULED (ARB, 2026-07-26, explicit per-item)

1. **WP-5 ownership: PART OF EPIC-004.** The Contestation raise-path slice stays in this roadmap at its sequenced position, under this program's gates — one workstream owns the whole arc from raise to resolution. The WP-5 gate (Review Comment 1) is closed.
2. **WP-1 is AUTHORIZED** as the first implementation slice, under the one-slice-one-review cadence and every gate this roadmap defines (TDD RED-first · keystone tests · merge-gate · triple qualification · the measurable conformance gate · dev guide · STOP for slice acceptance).

**The roadmap is now fully in force.** Implementation begins at WP-1; no other WP is authorized; each subsequent WP opens only on its predecessor's ARB slice acceptance.
