# Registration — `EM-ARCH-002` BOUNDARY AUTHORIZED · `EM-IMPL-002` grant PREPARED

**Type:** Governance registration (Session 2) · **Date:** 2026-08-17

## 1 · The act, verbatim

> **"EM-ARCH-002 Boundary Authorization. I authorize the Increment-2 Application Layer boundary. Scope: Application orchestration only. Frozen Model A Domain Core unchanged. No infrastructure. No persistence technology. No lifecycle ownership. Decisions: A-3 UC-5 RecordCommitteeConstitution: INCLUDED. A-4 UC-6 EstablishGateDecision: EXCLUDED from implementation scope; construction fixture only. A-7 Meaning-2 representation: QUERY FORM approved. Conditions: RED obligations become part of EM-IMPL-002 grant. A-8 and A-9 remain recorded observations, not implemented decisions. Independent verification required before acceptance. Grant and START remain separate future acts."** — PO/ARB, 2026-08-17

*(Established pattern, overrulable. The act adopts the proposal with the author-review's recommendations confirmed BY THE PO — the review supplied evidence; this act supplies the ruling.)*

## 2 · Also registered from the PO's review

* **A-8 warning, binding on Increment 2:** ⛔ no `ProcessManager` / `Saga` / `WorkflowEngine` — the Command→Handler→Aggregate→Facts→Consequences flow stands; a process manager enters only on named triggers (failure-recovery coordination · multi-context participation · retry semantics), never on step-count. *"Many steps → Saga" is expressly rejected.*
* **A-9 sequencing confirmed:** implement orchestration verbatim from approved F-2 → RED proves → domain evolution proposal ONLY on repeated-complexity evidence. No pre-design.
* **The architectural aim, verbatim in substance:** *the success is that the Application Layer has been prevented from becoming a second Domain Layer.*

## 3 · `EM-IMPL-002` GRANT — PREPARED FOR SIGNATURE

> **"I grant the Implementation lane authority to execute EM-IMPL-002: the Increment-2 Application Layer as bounded by the authorized EM-ARCH-002 proposal (A-3 IN · A-4 OUT/fixture · A-7 query form). RED before GREEN as separate commits. The RED suite must pin, as failing-first tests: (i) W-1…W-10, including the PO's five named pins — RED-1 a condition never becomes an actor (unableToFunction can never cause gate closure) · RED-2 Inoperative does not block position expression (Meaning-1) · RED-3 no lifecycle advancement on GateSatisfied or restoration · RED-4 the D-1 wall extends to tests (no implementation, fake, mock or stub of the authority) · RED-5 HistoryKind separation (lifecycle ≠ progression-decision history); (ii) the F-2/F-5/F-6 consequence sequences (the A-9 traceability); (iii) the per-event HistoryKind table; (iv) the refusal taxonomy (A-5, one test per baseline domain exception); (v) consequence re-issue idempotence to the extent answerable without persistence. Every name inherits EM-OPEN-045's placeholder standing. No infrastructure, no persistence, no framework artifact, no lifecycle ownership, no ProcessManager/Saga. Independent verification precedes acceptance. START is a separate act."**

## 3a · GRANT AMENDED at PO review (2026-08-17) — four conditions and three attached questions added

**G-1** Application services ORCHESTRATE ONLY: they may not classify election state, derive authority, transition lifecycle, or modify aggregates directly.
**G-2** Every use case ships a RESPONSIBILITY TABLE (receive command = Application · authenticate caller = Application/Security · decide election meaning = Domain · record fact = Domain/Protocol · persist = Infrastructure-later).
**G-3** No ProcessManager/Saga until a NAMED domain process requires it (the registered triggers; never step-count).
**G-4** Every handler's RED proves three negatives: it cannot bypass the domain · cannot invent authority · cannot mutate lifecycle.

**Attached questions, answered in the deliverable (design note + RED pin each), never silently:**
**Q-1** AUTHORIZATION SPLIT — *authorization to SUBMIT a request* (Application/Security) vs *authority to MAKE AN ELECTION FACT TRUE* (Domain/external) must stay separate; each handler names which check it performs and which it expressly does NOT.
**Q-2** IDEMPOTENCY — duplicate command handling defined per use case (same fact exists → return existing result; never a second constitution/fact), to the extent answerable without persistence.
**Q-3** PROTOCOL-APPEND SEQUENCE — append happens only AFTER domain acceptance (validate → domain decision → accepted fact → append); refusals are recorded as refusals, never as facts. Pinned in RED.

**On signature + START: the lane implements under the grant AS AMENDED. Until then: nothing in `app/` or `tests/` moves.**

> **Signature line:** *"I sign the EM-IMPL-002 grant as amended (G-1…G-4, Q-1…Q-3) and START the Implementation lane."*

**Traceability.** Proposal + author review (evidence) · this act (ruling) · `EM-GOV-070`/`071` · the baseline freeze · A-3.
