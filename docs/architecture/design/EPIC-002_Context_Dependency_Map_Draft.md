# EPIC-002 Pre-Work — Bounded-Context Dependency Map (DRAFT)

**Authority:** generated (AI-produced under Principal Architect directive, 2026-07-11) — **not authoritative until ARB ratification**
**Purpose:** ground the EPIC-002 sequencing decision in *domain dependencies* (evidence from code + frozen artifacts), not intuition — Principal Architect directive following the 2026-07-10 architecture-corpus implementation audit.
**Scope:** relationships only. No implementation. No redesign of certified boundaries (BDR v1.1 unchanged).
**Semantics:** arrows are **DDD context-map dependencies** (A ──► B means *B depends on concepts A owns*; B is downstream). They are **NOT process-flow order** — this distinction changes the map (see §3, Evidence).

---

## 1. Inputs

| Source | What it contributes |
|---|---|
| `Round49-06` BDR v1.1 (frozen) | Confirmed BCs: Evidence · Voting · Appointment · Contestation · Adjudication. Non-modules: Replay, Authorization, Lifecycle, Audit. Read Models: Results · Legitimacy. |
| `Round49-07` Migration Plan (frozen) | Strangler order **Evidence → Appointment → Voting**, chosen **by behavioral risk** ("lowest behavioral risk first; Voting last = live data + anonymity"), *not* by dependency. |
| `Round47-02` Certification | Legitimacy certified as **Read Model** (with Results). |
| `Round50-01/05` | Aggregate contracts: Determination requires `issuedByAuthority`; EvidenceEnvelope write-once; Mandate ACTIVE→REVOKED. |
| Implemented code | The actual dependency evidence below. |

## 2. Dependency evidence (code-grounded)

Relationship **types** are distinguished deliberately (ARB refinement R2) — they carry different architectural weight:

- **Ownership reference** — downstream holds an opaque ref to a concept the upstream *owns* (ADR-T16 string; no type coupling).
- **Published event** — downstream consumes the upstream's published integration event (outbox→inbox).
- **Operational dependency** — legacy runtime coupling not yet expressed as either of the above.
- **Derivation** — read-only projection over upstream facts (no authority).

| Downstream | Upstream | Carrier | Relationship type | Evidence |
|---|---|---|---|---|
| Adjudication | **Appointment/Authority** | `issuedByAuthority` | Ownership reference | `Adjudication/Domain/Determination/IssuedByAuthority.php` — "no determination exists without one" |
| Adjudication | **Evidence** | `evidenceEnvelopeRef` | Ownership reference | `Adjudication/Domain/Determination/EvidenceEnvelopeRef.php` — "owned by the Evidence context. Reference only (TP-1)" |
| Adjudication | Contestation | `challengeRef` | Ownership reference | `Adjudication/Domain/Determination/ChallengeRef.php` |
| Contestation | **Appointment/standing** | `raiserStandingRef` | Ownership reference | `Contestation/Domain/Challenge/RaiserStandingRef.php` — "identified constitutional actor" (never a voter, Q7/ADR-T11) |
| Contestation | Voting/Election outcomes | `ContestedOutcomeRef` | Ownership reference | `ElectionId`/`TargetType`/`TargetId` VO |
| Election (correction) | Adjudication | `DeterminationIssued` | Published event (IMPLEMENTED, PB-004) | inbox consumption via `DeterminationIssuedReactionHandler` |
| Contestation (reaction) | Adjudication · Election | `DeterminationIssued` · `ElectionCorrectionApplied` | Published events (IMPLEMENTED, PB-005) | two reaction handlers + parking |
| Voting | Membership/Eligibility · Appointment (administration) · Lifecycle | — | Operational dependency (legacy) | eligibility policies (`Contexts/Elections/Domain/Policies`), `ElectionLifecycleEngine` |
| Evidence | Voting/Lifecycle/operations (as *fact sources*) | — | Operational dependency (legacy, dormant) | `Security\Simplified` observation types; `SecurityEventRecorder` |
| Results · Legitimacy | Voting outcomes · Determinations · Corrections · Evidence | — | Derivation (BDR v1.1; not built) | — |
| Transparency | everything above | — | Derivation (Held, R47-02) | — |

**Load-bearing observation:** every cross-context dependency of the implemented core is satisfied by an **opaque reference** (ADR-T16). The greenfield core was deliberately decoupled — so **no remaining migration is a hard build-blocker for what exists**. Sequencing is therefore a *value/risk decision*, not a topological necessity. Both candidate orderings (§4) are viable; they optimize different things.

## 3. The map (DAG — the domain is not a linear chain)

```text
 [ Identity ]   [ Eligibility ]        ← EXTERNAL upstream systems today
      └──────┬───────┘                   (deliberately deferred: GI-1 / RQ-ID-01;
             ▼                            may become contexts later — shown so the
            Membership (operational, legacy)          deferral stays visible)
                 │  (persons, eligibility substrate)
                 ▼
   Appointment / Mandate  (authority precedes action)
      │            │              │
      │ standing   │ authority    │ administration
      ▼            ▼              ▼
 Contestation   Adjudication    Voting ◄── Election Lifecycle (supporting)
      │            ▲    ▲         │
      │ Challenge  │    │         │ observable facts
      └───────────►┘    │         ▼
        (implemented)   └───── Evidence  (facts recorded once, consumed by
                                  │       Adjudication & Replay)
   Adjudication ──► Election correction   (implemented loop, PB-004/005/006)
                                  │
                                  ▼
                     Results · Legitimacy  (derived — classification frozen
                                  │         as Read Model; re-open candidate §5)
                                  ▼
                            Transparency (held)
```

Corrections to the linear intuition:
- **Evidence is UPSTREAM of Adjudication** (a determination consumes evidence — `EvidenceEnvelopeRef`), even though evidence is *recorded* after votes in process time. Flow order ≠ dependency order.
- **Appointment fans out to three downstreams** (Contestation standing, Adjudication authority, Voting administration) — the widest upstream surface of any unmigrated BC.
- The graph is a **DAG with two roots** (Membership, Appointment), not a chain.

## 3a. Dependency matrix (reusable engineering artifact — ARB refinement R3)

Status legend: **I** = implemented · **D** = certified design, not implemented · **L** = legacy operational.

| BC | Depends on | Publishes | Consumes | Status |
|---|---|---|---|---|
| Evidence | fact sources (Voting/Lifecycle/ops) | `EvidenceRecorded` (D) | — | D (legacy types dormant, L) |
| Appointment | Membership; Identity/Eligibility (external) | `MandateGranted` · `MandateRevoked` (D; today `AuthorityDelegated/Revoked` in Governance, L) | — | D/L |
| Voting | Appointment · Membership/Eligibility · Lifecycle | `VoteAccepted` (D) | — | D (legacy Active-Record, L) |
| Contestation | Appointment (standing) · Election (outcome ref) | `ChallengeRaised` … `ChallengeResolved` (I) | `DeterminationIssued` (I) · `ElectionCorrectionApplied` (I) | **I** |
| Adjudication | Evidence (ref) · Appointment (ref) · Contestation (ref) | `DeterminationIssued` (I) | `ChallengeRaised`/routing (**D — raise→route path deliberately out of PB-005 scope**) | **I** (issuing side) |
| Election | Adjudication | `ElectionCorrectionApplied` (I) | `DeterminationIssued` (I) | **I** (reaction slice) |
| Results · Legitimacy | Voting · Adjudication · Election · Evidence | — (derived) | all correction-loop events (D) | D |

## 4. Sequencing evidence (input to EPIC-002 Strategic Discovery — NOT a re-open)

**Decomposition precedes ordering (PA refinement, 2026-07-11).** Before any ordering question, EPIC-002 Strategic Discovery first validates **what operational bounded contexts actually exist**. The certified decomposition (Evidence · Appointment · Voting · Read Models) is a **discovery input, not an immutable premise** — discovery may conclude a different decomposition (rename, merge, split). Any such conclusion enters through the **BDR re-open trigger with evidence**, exactly like the Legitimacy hypothesis in §5 — it does not silently rewrite BDR v1.1. Only after the decomposition is confirmed or formally revised does the ordering question below become meaningful. (Anchoring risk being mitigated: if ordering is discussed first, today's decomposition is unconsciously accepted as immutable.)

**Governance position (ARB refinement R1):** R49-07's migration order stands. This map does **not** reopen it. It provides evidence that may **confirm or challenge the current migration order during EPIC-002 Strategic Discovery**; only after discovery should the ARB ask whether the evidence justifies changing the order. This keeps discovery objective.

| Criterion | **Risk-first** (frozen R49-07: Evidence → Appointment → Voting) | **Dependency-first** (proposed: Appointment → Voting → Evidence…) |
|---|---|---|
| Optimizes | Lowest behavioral risk first; Voting last (live data + anonymity) | Authority explicit before modeling action; widest upstream first |
| Constitutional argument | Facts (evidence) underpin every later judgment | "Who may act" precedes every act; Mandate-vs-Committee (open R50-09 item) gets resolved earliest |
| Blocked by | Nothing (opaque refs) | Nothing (opaque refs) |
| Unlocks | Legitimacy answers need Evidence; Replay placement (BDR-06) resolves | Standing/authority semantics for Contestation & Adjudication stop being opaque strings |
| Both agree | **Voting migrates last among operational BCs** (live data + anonymity invariant) | same |

**Recommendation (draft, for ARB):** carry both columns into EPIC-002 Strategic Discovery as competing hypotheses. The discovery deliverables (ownership map, ubiquitous language, literature review) are precisely the evidence a confirm-or-change ruling needs.

## 5. Legitimacy — architectural hypothesis (not a re-open; ARB refinement R4)

BDR v1.1 / R47-02 classify Legitimacy as a **derived Read Model**; that classification **stands**. The Principal Architect assessment (2026-07-11) is recorded as an **architectural hypothesis**: for a constitutional platform, "why is this election legitimate — which determinations, which evidence, which rules" may be a core product capability, potentially a domain in itself. Per the burden-of-proof principle, the hypothesis carries no architectural force until evidence accumulates — EPIC-002 Strategic Discovery should collect it (falsification question: does Legitimacy *own decisions*, or only *derive* them? R49-02's test: "independent reason to change"). Only if the evidence is sufficient does this become a BDR re-open request.

## 6. EPIC-002 shape (Principal Architect ruling, 2026-07-11 — recorded)

- **Stream A (Product):** exactly ONE bounded context. Strategic Discovery only — literature review → state of the art → DDD ownership → ubiquitous language → open questions → **STOP** (no IDD, no implementation until ARB approves the discovery).
- **Stream B (Documentation, at EPIC-001 retrospective):** refresh stale c4 diagrams + remove superseded progress tables + consolidate docs. **No redesign.**
- **Process addition (retrospective item):** Architecture Gap Analysis (architecture → strategic design → code → gap analysis → retrospective) becomes a repeatable engineering-process activity per bounded context — the 2026-07-10 audit is the prototype run.
- **Standing constraint:** engineering platform is stable; architectural evolution only through demonstrated implementation needs (consistent with R-37 burden-of-proof).

## 7. Open questions for the ARB

1. Ratify or amend this map (arrow semantics, missing edges — esp. Membership↔Appointment boundary, and whether Identity/Eligibility's external-upstream representation is correct).
2. Confirm the governance position in §4: **decomposition before ordering** — discovery first validates the certified operational decomposition (challenges route through the BDR re-open trigger), then R49-07's standing order is confirmed or challenged with this map + discovery deliverables as evidence.
3. Admit the Legitimacy **hypothesis** (§5) into EPIC-002 discovery scope as a falsification question — no classification change now.
4. Choose the ONE bounded context for Stream A. (This map's fan-out evidence favors **Appointment/Mandate** as the discovery subject — it resolves the most opaque refs and the open Mandate-vs-Committee question — but the choice is the ARB's.)

---
*Traceability: produced from the 2026-07-10 architecture-corpus implementation audit (session log `.claude/sessions/2026-07-10.md`) + Principal Architect review directive (2026-07-11). Sources: R47-02 · R49-02/03/06/07 · R50-01/05/09 · implemented code cited inline.*
