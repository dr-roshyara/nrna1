# EPIC-004 Adjudication Tactical Work Package

**Kind:** design charter — a construction permit, not a design. This document defines **what Tactical DDD is allowed to design** for the Adjudication bounded context. It performs no Tactical DDD.
**Authorization:** ARB, 2026-07-25 — *"limited to the preparation and approval of a scoped Tactical DDD work package for Adjudication. Aggregate discovery, value objects, invariants, repositories, commands, and events shall begin only after that work package has been reviewed and frozen."*
**Constitutional inputs (binding, not subjects of this work):** the accepted Strategic Architecture Baseline (EPIC-002) · the accepted Tactical DDD Entry Assessment (EPIC-003) · the four Constitutional Policies · Special Review №1 (Adjudication = SUBSET → EXTEND, no rename) · Special Review №2 (all-custodial baseline) · the recorded falsifiability/reversal conditions.
**Governing test applied throughout:** *"Is this defining scope, or is this designing the solution?"* Only the former appears below.

---

## 1. Context Confirmation

- **Why Adjudication was selected (ARB grounds, recorded):** lowest-risk, highest-evidence starting point — strategic scope settled; an implementation nucleus exists (refine and complete, not invent); center of the constitutional correction loop; the four constitutional policies apply directly as tactical constraints; minimizes architectural uncertainty relative to the greenfield-heavy contexts.
- **Current implementation nucleus (per the Entry Assessment, evidence-based):** `app/Contexts/Adjudication` — complete hexagonal shape, thoroughly tested; the `Determination` aggregate records a binding ruling (Draft→Issued→Final), enforces one-determination-per-challenge, fixes ruling content in the immutable event (ADR-T19 Model B). Deliberately narrow: **no evidence-weighing, sufficiency, or judgment logic exists**; evidence enters as one opaque `EvidenceEnvelopeRef` whose referent (the "Evidence context") was never built; legitimacy is a command input, its rules deferred (ADR-T17). No production trigger exists anywhere for issuing a determination — the correction loop is an open arc.
- **Strategic responsibility (per the accepted baseline):** resolve contested/incomplete evidence into an authoritative determination; owns D1 (automatic vs. discretionary); never automatic (K1); carries the CB-3-Alt annotation (authority-validity split — open internal question, thin evidence, non-blocking).
- **Constitutional constraints in direct force:** all four policies — Superseding Constitutional Publication (Adjudication's corrections land as superseding publications, never mutations) · Evidence Preservation Window (the adjudication horizon is a component of the window) · Phase-1 Custodial Constraint (custodial investigation feeds adjudication under CL-1..CL-3) · Quarantine Pending Determination (integrity quarantine is a business source of the determinations this context issues — and the first identified natural production entry for the loop's missing head).
- **Integration boundaries (per the accepted Relationship Pattern Selection — fixed, not redesignable here):** COL-1 Customer–Supplier (customer of Collection & Aggregation; declare-failure is this context's bargaining power) · COL-2 Conformist, scoped to the record sublanguage (consumes the fixed record in its own terms) · COL-3a Customer–Supplier (customer of Custodial Integrity's attestations) · COL-3b Published Language consumer (one verifier among many, never privileged) · COL-5a/COL-5b outbound correction demands (the latter prospective-only).
- **Existing implementation evidence:** the four EPIC-003 inventory reports (2026-07-25), incorporated by reference.

## 2. Tactical Objectives

*(What Tactical DDD is expected to accomplish. Objectives only — no solutions, no models.)*

- **T-1 — Complete the Adjudication domain model** by extending the existing nucleus (per Special Review №1: extend, no rename, no parallel context) to cover the judgment half the nucleus deliberately excludes: how contested/incomplete evidence becomes a determination.
- **T-2 — Recover the missing production path:** define how a determination comes to be issued in production — including how the Integrity Quarantine path (Constitutional Policy 4) enters the context as a business trigger, closing the correction loop's open arc at its head.
- **T-3 — Define aggregate boundaries** within the extended context (what belongs to the existing Determination lifecycle, what belongs to the judgment process, and how they relate) — *the defining happens in Tactical DDD, not here.*
- **T-4 — Extend the ubiquitous language** to the judgment vocabulary (evidence sufficiency, standards of scrutiny, refusal/declare-failure), consistent with the strategic glossary and K1.
- **T-5 — Define the context's invariants**, encoding the applicable constitutional policies as tactical invariants — including "detection may be automatic; correction may not" and the one-determination-per-challenge rule already proven in the nucleus.
- **T-6 — Define the collaboration with Collection & Aggregation** (COL-1's customer contract in tactical terms): what the evidence body must provide for a determination to be possible, and what declare-failure demands when it cannot — *including deciding, as the Entry Assessment explicitly left open, whether the existing trust-evidence vocabulary becomes the canonical evidence source for adjudication.*
- **T-7 — Define how determinations reach the Superseding Constitutional Publication mechanism** (Constitutional Policy 1) at the business level — the correction's landing point as a consumer of this context's output.

## 3. Explicit Not-in-Scope

- No implementation; no PHP; no code of any kind.
- No repositories, aggregates, entities, value objects, factories, domain services, application services, commands, events, or persistence designs *in this document* (they are Tactical DDD's work, after freeze).
- No APIs, database schemas, UML, or sequence diagrams.
- No cryptographic design; no Self-Verifying Integrity work of any kind (greenfield, separately authorized someday).
- No work in any other bounded context: Collection & Aggregation, Contemporaneous Record-Fixing, Custodial Integrity remain untouched (Adjudication's *contracts with* them are in scope as consumer-side definitions; their internals are not).
- No redesign of strategic boundaries, relationship patterns, or constitutional policies; no reopening of the CB-3-Alt annotation (it remains an annotation unless separately ruled).
- No modification of the legacy voting flow, the legacy results path, or the messaging platform.
- No resolution of open business questions by tactical means (§5b — they are reported, not resolved).

## 4. Known Inputs

Tactical DDD may rely upon: the Strategic Context Map (accepted; five ratified BCs) · the Relationship Pattern Selection as adversarially reviewed (Adjudication's six edges above) · the Tactical DDD Entry Assessment incl. the Architectural Risk Register and both Special Reviews · the four Constitutional Policies with their recorded invariants · the four implementation-archaeology inventory reports · the existing implementation nucleus (`app/Contexts/Adjudication`, `app/Contexts/Contestation`'s reaction slice, `app/Contexts/Election`'s correction reaction) · the messaging platform (production-wired outbox/inbox/dispatcher with provenance) · the EPIC-002 research record (falsification-tested phenomena, especially P2) · ADR-T14/T16/T17/T19 and the Canonical Event Catalog.

## 5. Known Risks (acknowledged, not solved)

- **R-1 (Critical, carried):** no production trigger for the loop's head — T-2 addresses it; the quarantine entry is an identified candidate, not a settled design.
- **R-2 (Critical, decision made / realization pending):** corrections land via Superseding Constitutional Publication — the mechanism is ruled but unbuilt; T-7 defines the business-level contract only.
- **Evidence consumer alignment (open by design):** whether the existing trust-evidence vocabulary becomes adjudication's canonical evidence source is delegated to T-6 — a genuine tactical decision with strategic visibility; if it grows strategic implications, it routes back to the ARB.
- **Custody constraints:** CL-1..CL-3 bind any adjudication use of the custodial linkage — every investigative lookup is itself evidence.
- **Retention dependency:** the Evidence Preservation Window depends on the adjudication horizon this context's tactical model will imply — a two-way dependency to keep visible.
- **CB-3-Alt:** the authority-validity annotation stays open; tactical work must not silently absorb or foreclose it.

### 5b. Open business questions — REPORTED TO THE ARB, not resolved here *(per the standing instruction: Tactical DDD must not silently compensate for missing business decisions)*

- **Q-1 — Who may issue a determination?** `IssuedByAuthority` is an opaque string; no authority model, role check, or appointment rule exists anywhere. ADR-T17 deferred the legitimacy rules. *Who the constitutional oversight body is, and how its authority is established,* is a business/governance question — possibly touching the existing Governance context (authority delegation) — that tactical modeling cannot answer without inventing constitutional policy. **Requested: ARB guidance at package review** (an answer, a delegation-with-bounds, or an explicit deferral with the opaque reference retained).
- **Q-2 — What is the Contestation Window?** Constitutional Policy 2 made its definition an accepted consequence ("needed by the correction loop regardless"), and Adjudication's tactical model implies the adjudication-horizon component of the Evidence Preservation Window. The window's actual terms (durations, per-election-type variation, when a challenge may no longer be initiated) are business policy. **Requested: ARB definition or explicit interim rule at package review.**

Neither question blocks package approval; both must be answered (or explicitly deferred by the ARB) before the tactical model can encode the affected parts.

## 6. Success Criteria (when this work package is complete)

1. Scope agreed — §1 confirmed accurate by the ARB.
2. Objectives agreed — T-1..T-7 accepted (amended as the ARB directs).
3. Exclusions agreed — §3 accepted as binding on the tactical iteration.
4. Assumptions and open questions documented — §5/§5b acknowledged; Q-1/Q-2 answered or explicitly deferred.
5. **ARB approves and freezes the package.**

Nothing beyond this. Approval of this package authorizes aggregate discovery and subsequent Tactical DDD activities for Adjudication — and only for Adjudication, only within this scope.

## 7. Freeze Statement

> **This work package is frozen pending ARB approval. Tactical DDD may begin only after approval.** Any change to this package after approval routes through explicit ARB review. If tactical work later identifies unanswered business questions or strategic contradictions beyond §5b, it STOPS and reports to the ARB — it does not resolve them through tactical modeling.

---

**Self-review (per instruction):** no aggregate, entity, value object, repository, command, event, factory, service, API, schema, or diagram is designed anywhere above — tactical terms appear only as *names of future work* (§2) or *prohibitions* (§3) ✅ · every section derives from accepted strategic evidence, cited in place ✅ · the two identified business questions are reported, not resolved ✅ · the governing test ("defining scope vs. designing solution") holds in every section ✅. **STOP.** No aggregate discovery, no subsequent Tactical DDD activity, until the ARB approves and freezes this package.

---
*Authorization: session record 2026-07-25 + `.claude/CONTEXT.md` · Constitutional inputs: `EPIC-002_Canonical_Context_Map.md`, `EPIC-002_Relationship_Pattern_Selection.md`, `EPIC-003_Tactical_DDD_Entry_Assessment.md` (incl. §THE FOUR DECISIONS, both Special Reviews, and the Risk Register).*
