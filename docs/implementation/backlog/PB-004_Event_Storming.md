# PB-004 Election Reaction — Mini Event Storming (one page)

**Status:** discovery artifact for ARB review · **2026-07-07** · precedes the PB-004 IDD (the IDD must trace back to this model). No implementation.
**Evidence:** Catalog 50-05 · Policy 50-06 · ADR-T1/T8/T14/T16/T20 · D-01/D-02 · Blueprint §6/§7. Grounded, not invented (ER-02).

---

## The flow (left → right)

```
DeterminationIssued          Election reaction            ElectionCorrectionApplied
(Adjudication, restricted) ─► [Inbox: consumer_context     ─► (Election/Lifecycle,
   via Messaging Platform        ='Election']                    Integration, Core)
                                 │                               → Outbox → (PB-005:
                                 ▼                                 Contestation resolves)
                          CorrectionTypeDecision
                          (Election owns)
```

| Element | Content (evidence) |
|---------|--------------------|
| **Incoming Integration Event** | `DeterminationIssued` — `determinationId · challengeId · outcome · legitimacy · reason · evidenceEnvelopeRef(envelopeHash) · issuedByAuthority · jurisdiction · finalizedAt` (50-05). Consumed via the **frozen Messaging Platform** Inbox (`InboxHandler`, `consumer_context='Election'`, `eventTypes=['DeterminationIssued']`). |
| **Business Meaning** | A **binding legal ruling** exists on a challenge. Adjudication *issues*; it does **not** enforce (SD-5 #4). Election must decide **whether and how** to apply the ruling to the affected election (consent-based; 50-03). |
| **Target Aggregate** | The **Election** (correction-application aggregate). *(OQ-1: greenfield `app/Contexts/Election` reaction slice vs the legacy Election/Lifecycle engine — for ARB.)* |
| **Invariants** | Anonymity preserved — **no un-casting**; `ContainedOnly` when a correction cannot reach individual votes (ADR-T8/T11). **Forward-only**, no saga/compensation (ADR-T8). **One aggregate + outbox per txn** (ADR-T1). **Idempotent** — a determination applied at most once per election. **Dismissed ⇒ no correction** (D-02). Tenant/election scoping preserved (D-06). |
| **Aggregate Decision** | `CorrectionTypeDecision` (Election owns — 50-06): map ruling `outcome`/`legitimacy` → `correctionType ∈ {ReRun \| Invalidate \| Accept \| ContainedOnly}` — or **no correction** when Dismissed. This is the core domain decision. |
| **Domain / Integration Event out** | `ElectionCorrectionApplied` — `electionId · determinationId · correctionType · appliedAt` (50-05). Emitted to the Election **Outbox** (frozen platform). |
| **Policies (when→then)** | *When* `DeterminationIssued` *and* ruling is upholding *then* decide correctionType and apply + emit `ElectionCorrectionApplied`. *When* Dismissed *then* Election stays silent (D-02). *(Downstream: Contestation reacts to `ElectionCorrectionApplied` to resolve — that is PB-005, not PB-004.)* |
| **Transaction Boundary** | One txn = { Election correction aggregate write **+** `ElectionCorrectionApplied` outbox row } (ADR-T1), executed inside the Inbox handler's transaction. |
| **Consistency Boundary** | The Election correction aggregate (immediate within; **eventual** across contexts via events; forward-only). |

---

## Open questions for ARB (resolve before the IDD traces to this model)
- **OQ-1 — Aggregate home:** greenfield `app/Contexts/Election` reaction slice, or extend the legacy Election/Lifecycle engine? (Reaction is Core + Integration; greenfield Core precedent = Contestation/Adjudication.)
- **OQ-2 — Dismissed path:** confirm Election emits **nothing** on Dismissed (D-02 "stays silent"), i.e. no `ElectionCorrectionApplied`, and whether an internal no-correction audit fact is wanted.
- **OQ-3 — Ruling→correctionType map:** confirm the authoritative mapping (incl. `Accept` = Upheld-but-accept, D-02) and that it is owned solely by Election (`CorrectionTypeDecision`).
- **OQ-4 — Idempotency:** Inbox dedupe is `(event_id, consumer_context)`; add an aggregate-level guard on `(determinationId, electionId)` to make re-application impossible even across replay?

---

## Architecture Review Gate (ARR — re: the frozen Messaging Platform)
| Question | Answer |
|----------|--------|
| Does PB-004 **consume** the Messaging Platform **unchanged**? | **Yes** — registers an `InboxHandler` + emits via the existing Outbox; no Shared change. |
| Does PB-004 require any **Platform Capability modification**? | **No.** |
| Does PB-004 **violate** any Architecture Principle (incl. PGP-01…05)? | **No** — Election owns its business decision; Shared stays business-agnostic; anonymity preserved. |
| Does PB-004 require a **new ADR**? | **Not for Messaging.** Possibly for the *domain* design (OQ-1 greenfield-vs-legacy) — a bounded-context structural decision, not a platform change. Resolve in Event-Storming review / IDD. |

**ARR routing:** re: the Messaging Platform → *consume-only · no modification · no violation · no Messaging ADR* → **PASS** (PB-004 is a consumer of the frozen platform). Domain-side OQ-1…OQ-4 are resolved in this Event-Storming review, then the IDD.

**Next:** on ARB sign-off of this model (and OQ rulings) → write the PB-004 IDD tracing to it → RED → GREEN → regression → evidence. No implementation until the IDD is approved.
