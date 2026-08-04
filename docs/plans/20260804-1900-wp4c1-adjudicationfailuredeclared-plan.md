# WP-4C-1 — EP-01 Implementation Plan: `AdjudicationFailureDeclared`

**Created:** 2026-08-04 19:00 · **Author:** Chief Software Architect / DDD Technical Lead
**Authorized by:** **R-89** (WP-4C-1 authorized to plan and implement) · **Subdivided by R-88** · Owner: **Adjudication**
**Type:** delivery artefact. **No architecture proposed · no ADR reinterpreted · no governance decided.**
**Status:** 🔶 **PLAN — awaiting EP-01 approval. Nothing is implemented. Contains ONE HARD ESCALATION (§6/E1) that must be ruled before deliverable 3 can be planned further.**

**Out of scope, expressly:** Contestation work of any kind · WP-4C-2 · WP-4D · any edit to the frozen catalog · creating a new catalog version.

---

## 1. Objective

Announce the business fact PM-7 already records: **"the evidence was insufficient; no ruling can issue"** (EPIC-004K §10).

**The gap is an announcement, not a state transition.** `AdjudicationProcessState::concludeFailureDeclared()` exists (WP-2) and the manager already calls it — and that call site's own docblock names this slice:

```php
// AdjudicationProcessManager::receiveInsufficiencyDecision(), line 335
/**
 * PM-4 → PM-7: the authority found the evidence insufficient. The failure is
 * recorded; announcing it is WP-4's wiring.
 */
```

**So no new entry point is required.** WP-4C-1 supplies the announcement the existing entry point was written to expect.

## 2. Deliverables and dependency matrix

| # | Deliverable | Depends on | Exists? | Plannable now? |
|---|---|---|---|---|
| **D1** | `AdjudicationFailureDeclared` domain event | PM-7's recorded fields | ⛔ no | ✅ **yes** |
| **D2** | `AdjudicationFailureDeclaredHydrator` + provider registration | D1 · `EventHydrator` · `AdjudicationServiceProvider` | ⛔ no | ✅ **yes** |
| **D3** | the **catalog deliverable** | ADR-T5 · the frozen catalog | ⛔ no | ⛔ **NO — see §6/E1** |
| **D4** | the publication call site (enqueue in `receiveInsufficiencyDecision()`) | D1 · **event provenance** | ⛔ no | ⚠️ **qualified — see §6/E2** |

**D4 is listed although the roadmap's wording names only the event, hydrator and catalog.** Reason: **an event class nobody enqueues announces nothing**, and the existing entry point's docblock explicitly reserves the announcement for *"WP-4's wiring"*. Without D4 the slice delivers a class, not a capability. **Recorded as an interpretation, not assumed silently** — if the Board reads WP-4C-1 as excluding D4, deliverables reduce to D1+D2 and the announcement waits.

## 3. Event shape — from the record, nothing derived

PM-7 records `consideredEvidence · concludedByAuthority · reason · concludedAt`; the process carries `challengeRef`. **Every candidate field is already on the record — the event derives, defaults and computes nothing** (AP-2).

Precedent shape, `AdjudicationExpired(ChallengeRef, DateTimeImmutable)`. Proposed:

```
AdjudicationFailureDeclared
    challengeRef        ChallengeRef        the challenge whose adjudication failed
    reason              Reason              the authority's stated ground
    declaredByAuthority IssuedByAuthority   who declared it (PM-5's authority reference)
    declaredAt          DateTimeImmutable   process->concludedAt()
```

**`consideredEvidence` is deliberately NOT proposed on the payload.** ADR-T11 permits references, and `EvidenceSet` holds references only — but **no consumer need has been demonstrated**, and §10 states the occurrence as insufficiency, not as an evidence manifest. **Adding it would be speculative payload.** Recorded as a question for EP-01 approval, not decided here.

## 4. Implementation sequence

| Batch | Work | Leaves the repo buildable? |
|---|---|---|
| **B1** | **RED** — the keystones of §5, failing because D1/D2 are absent | ✅ |
| **B2** | **D1** — the event class | ✅ |
| **B3** | **D2** — hydrator + registration in `AdjudicationServiceProvider` | ✅ |
| **B4** | **D4** — the enqueue, *if* §6/E2 is resolved | ✅ |
| **B5** | VERIFY — `composer merge-gate` | — |
| **B6** | developer guide (`developer_guide/adjudication/07_…`) | — |
| **B7** | acceptance evidence | — |

**Cadence, unchanged and proven across WP-4B:** Change → Compile → Static analysis → Relevant tests → Commit.

## 5. RED test strategy

| K | Keystone | Fails today because |
|---|---|---|
| **K1** | the event carries exactly the record's fields, and nothing derived | the class is absent |
| **K2** | the hydrator round-trips a v1 payload to an equal event | the hydrator is absent |
| **K3** | a payload of any other schema version is **rejected loudly**, not guessed | the hydrator is absent — mirrors `AdjudicationExpiredHydrator`'s v1-only window (ADR-T5) |
| **K4** | the provider **registers** the hydrator — *published language requires BOTH publication and registration* (the WP-3A rule, quoted in `AdjudicationExpiredHydrator`) | no registration exists; precedent test: `EventHydratorRegistryWiringTest` |
| **K5** | **(only if E2 is resolved)** an insufficiency decision enqueues exactly one `AdjudicationFailureDeclared`, with provenance continuing the existing conversation | no enqueue exists |

**K3 and K4 are the load-bearing pair.** WP-3A established that publication alone does not make published language; registration is the other half, and it is the half that is easy to forget and invisible until a consumer fails.

## 6. Mandatory resolution — and two escalations

### E1 — 🛑 HARD ESCALATION: the catalog deliverable cannot be planned further

**Resolution attempted against ADR-T5, the frozen catalog, and the WP-6 precedent, as R-89 required. Evidence:**

| Fact | Source |
|---|---|
| The catalog is **FROZEN** and states its own change rule: *"Changes follow ADR-T5 (**version, never mutate**)"* | `Canonical_Event_Catalog_v1.0.md`, header |
| **ADR-T5's text governs EVENTS, not the catalog document:** *"Events version, never mutate; new version convertible-from-old else NEW event"* | `ADR-T-LOG-Tactical-Implementation.md` line 12 |
| **`AdjudicationExpired` is NOT in the catalog** — yet WP-6 shipped it with a registered hydrator and **was accepted** | `grep` of the catalog · `AdjudicationExpiredHydrator.php` |
| **`ChallengeRouted` IS in the catalog — at v1.0, dated 2026-06-26, predating WP-3A** | catalog line 15 |
| **Published-language status requires publication + REGISTRATION** — not a catalog row | `AdjudicationExpiredHydrator` docblock: *"the WP-3A rule: published language requires BOTH publication and registration"* |

**Engineering's resolution, stated at the strength the evidence supports:** **published-language status for `AdjudicationFailureDeclared` is achievable by D1+D2+D4 alone**, on the WP-6 precedent and the WP-3A rule. **Whether the roadmap's *"catalog entry"* additionally obliges WP-4C-1 to produce a NEW CATALOG VERSION cannot be answered from the evidence** — no work package has been shown to have added a row, and the artifact is frozen.

> 🛑 **STOP AND ESCALATE, per R-89.** **The Board must rule one of:** (a) WP-4C-1 must produce a new catalog version — a Board act on a frozen artifact; (b) the WP-6 precedent governs and D3 is discharged by registration, so D3 leaves WP-4C-1's scope; (c) D3 is deferred to a separate catalog-versioning package covering **both** `AdjudicationExpired` and `AdjudicationFailureDeclared`, since the same gap exists for the accepted WP-6 event.
>
> **Engineering does not choose. D3 is not planned further, and no catalog file is touched.**

**Note recorded, not acted on:** option (c) exists because **the gap is not new** — `AdjudicationExpired` has the same missing row today, in accepted work. That is an observation about the catalog's maintenance, not a defect claim against WP-6.

### E2 — ⚠️ Provenance: `start()` would likely violate the one-mint invariant

`enforceHorizon()` publishes `AdjudicationExpired` with `EventProvenance::start(...)`, justified in code because *"a clock consumed no message, so there is no incoming conversation to continue — this publication BEGINS one (ADR-MP-06; ARB Decision B)."*

**An insufficiency decision is not a clock.** It is the authority's ruling inside the adjudication conversation that the challenge began — so the announcement **continues** a conversation rather than beginning one, and the **Constitutional Audit Invariant** permits *exactly one CorrelationId mint per constitutional conversation* (enforced by `CorrelationIdMintingTest`'s allowlist). **Minting a second would breach it.**

**But `EventProvenance::fromConsumed(...)` needs the consumed decision's provenance, and the authority-decision intake is WP-4D — unauthorized and unbuilt.**

**Consequence for this plan:** **D1, D2 and their keystones are fully executable now** — this is precisely WP-4B's *"registration ≠ delivery"* / *"the seam is buildable while nothing feeds it"* position. **D4 is not**, unless the Board rules that the publication call site may accept provenance from its caller pending WP-4D. **Recorded as a dependency, not a stop condition for D1/D2.**

## 7. Verification strategy

`composer merge-gate` — Architecture fitness · Deptrac · greenfield PHPStan · widened regression. Plus: greenfield PHPStan clean at every batch, and **`CorrelationIdMintingTest` must remain green** — if D4 lands, that test is the invariant's enforcement and must not be modified to accommodate it.

## 8. Acceptance criteria

D1+D2 delivered with K1–K4 green · merge gate PASS · developer guide · **D3's disposition recorded per E1's ruling** · **D4 delivered only if E2 is resolved, otherwise recorded as carried** · acceptance evidence produced. **Engineering does not accept its own work (EP-02 · R-34).**

## 9. Definition of Done

**RED → GREEN → `composer merge-gate` PASS → evidence supporting triple qualification → developer guide → acceptance evidence → STOP.**

## 10. Traceability

**R-88** (subdivision) · **R-89** (authorization + the E1 obligation) · R-67 · R-79 · R-80 · EPIC-004K **§10** · §15.3 · **ADR-T5** · ADR-T11 · ADR-MP-06 · AP-2 · Constitutional Audit Invariant (one mint per conversation) · `Canonical_Event_Catalog_v1.0.md` (frozen) · WP-6 precedent (`AdjudicationExpired` + hydrator) · WP-3A rule (publication **and** registration) · EP-01 · EP-03 report `2026-08-04-wp4c-engineering-readiness-review.md`.
