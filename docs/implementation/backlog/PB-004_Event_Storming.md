# PB-004 Election Reaction — Mini Event Storming (one page)

**Status:** **APPROVED by ARB with rulings** (OQ-1…OQ-4 resolved below) · **2026-07-07** · the PB-004 IDD (`PB-004_Election_Reaction_Implementation_Design.md`) traces to this model. No implementation.
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

## ARB rulings (2026-07-07 — RESOLVED)
- **OQ-1 → Greenfield `Election` bounded context.** Do NOT extend the legacy Lifecycle engine. New behavior driven by constitutional determinations belongs in a context with its own ubiquitous language/aggregates/policies; extending legacy blurs responsibility and hinders migration. The new `Election` context is initially a **consumer** exposing only the **minimum reaction behavior**.
- **OQ-2 → Dismissed = no correction, no event.** A dismissal is a legal conclusion that no corrective action is required; silence is a valid business outcome. The Inbox message is still marked **Processed** (operational), but no correction workflow starts.
- **OQ-3 → Ruling→CorrectionType mapping is owned by the Election context.** Adjudication decides *what was decided*; Election decides *how it affects election state*. Adjudication never encodes election-specific correction semantics (bounded-context autonomy).
- **OQ-4 → Idempotency at BOTH levels.** Infra: Messaging dedupe `(event_id, consumer_context)` (same message not reprocessed). Domain: the Election aggregate guarantees the same determination cannot produce multiple corrections. Different responsibilities — neither replaces the other.

## Consistency boundary (ARB refinement — explicit)
```text
Inbox Message ─► Election Aggregate ─► ElectionCorrectionApplied ─► Outbox
└──────────────── ONE transactional consistency boundary ─────────────┘
                     (everything beyond it is EVENTUALLY consistent)
```
One transaction spans: consume-claim → aggregate decision+write → correction event to outbox. Cross-context propagation (→ Contestation resolve, PB-005) is eventual.

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
