# Cross-Context Contract — Architectural Layer Classification

**Date:** 2026-07-31 · **Role:** Chief Software Architect / ARB · **Commission:** governance classification only
**Question:** **which architectural layer owns each invariant?** — so every rule has exactly one canonical home and the contract becomes a *navigation* document, not a competing source of truth.
**Constraints honored:** no ADR created or rewritten · no implementation · no context redesigned · WP-4 not implemented

---

## Phase 5 first — the discriminating test

The commission's sharpest instrument is the future-evolution test, so it is run **before** classifying: *assume the transport changes, hydrators disappear, the messaging platform and relay are replaced. Which invariants remain true?*

| Invariant | Survives total infrastructure replacement? | Therefore |
|---|---|---|
| **R-1** no cross-context Domain import | **Yes** — true under Kafka, gRPC, shared-DB polling, or carrier pigeon | **Strategic** |
| **R-2** no cross-context Infrastructure import | **Yes** — whatever the transport, a consumer must not depend on the producer's transport code | **Strategic** |
| **R-4** consumer reconstructs its own model | **Yes, in its core** — "the consumer, not the producer, assigns meaning" holds under any carrier. *"From primitives"* is transport-flavoured | **Strategic core, tactical mechanism** |
| **R-7** producer never names its consumers | **Yes** — a dependency-direction rule | **Strategic** |
| **R-9** identity crosses as an opaque string | **Yes** | **Strategic** |
| **R-3** hydrator is producer-side | **No — becomes vacuous.** Hydrators exist only because the relay re-materializes events for in-process dispatch | **Infrastructure** |
| **R-5** payload primitives only | **No — becomes meaningless.** Under protobuf/Avro "primitives" would be schema types; this is a serialization rule | **Infrastructure** |
| **R-6** provenance on the envelope, never in a domain event | **Partly** — *provenance* is a messaging concept, but *"a domain event carries no transport metadata"* survives any transport | **Tactical (subject) + Infrastructure (origin)** |
| **R-8** consumer idempotency | **Derived, not independent** — it follows from an **at-least-once delivery guarantee**. Change the guarantee and the requirement changes | **Infrastructure-derived, tactical consequence** |

**This test refines the previous review on one point:** the strategic validation called R-8 a "strategic requirement with tactical phrasing." It is more precisely **infrastructure-derived** — the obligation exists *because* delivery is at-least-once (ADR-T3), not independently of it.

## Phase 1 — Ownership classification

| # | Invariant | Primary layer | Secondary | Existing governing artifact | Should the contract own it? |
|---|---|---|---|---|---|
| R-1 | No cross-context Domain import | **L1 Strategic** | — | **ADR-T16** (frozen) · grounded in EPIC-002's accountability derivation | **No** — point to ADR-T16 |
| R-2 | No cross-context Infrastructure import | **L1 Strategic** | L4 (enforced) | **NONE** — see Phase 2 gap | **No** — but a canonical home must be chosen |
| R-3 | Hydrator is producer-side | **L3 Infrastructure** | — | Messaging platform design (ADR-MP series) | **No** — navigation note only |
| R-4 | Consumer reconstructs local VOs | **L1 Strategic** | **L2 Tactical** (VO/factory mechanics) | **ADR-T16** | **No** — point |
| R-5 | Payload primitives only | **L3 Infrastructure** | — | **ADR-T5** (event versioning/serialization) | **No** — descriptive guidance |
| R-6 | Provenance on the envelope | **L2 Tactical** (domain-event purity) | L3 (provenance itself) | **ADR-MP-06** (+ two further copies — Phase 2) | **No** — point |
| R-7 | Producer never names consumers | **L1 Strategic** | L3 (registry mechanics) | **PB-006** permanent principle *Registration ≠ Delivery* | **No** — point |
| R-8 | Consumer idempotency | **L3 Infrastructure-derived** | **L2 Tactical** (idempotent handlers/aggregates) | **ADR-T3** + **ADR-T4** | **No** — point |
| R-9 | Identity crosses as opaque string | **L1 Strategic** | — | **ADR-T16** | **No** — point |

**Result: the contract should own nothing.** Every invariant has, or needs, a home elsewhere. That is the correct outcome for a document derived from evidence.

## Phase 2 — Duplicate governance audit

| Invariant | Governing authorities found | Verdict |
|---|---|---|
| R-1, R-4, R-9 | ADR-T16 (single) | ✅ Clean |
| R-5 | ADR-T5 (single) | ✅ Clean |
| R-8 | ADR-T3 + ADR-T4 — **complementary, not duplicated** (guarantee vs. mechanism) | ✅ Clean |
| **R-6** | **THREE homes, each restating the invariant:** `ADR-MP-Messaging-Platform.md` · `EventProvenance.php` docblock · `developer_guide/audit_system/08_event_provenance.md` — plus PB-006's closure ruling | ⚠️ **Duplication.** Canonical owner should be **ADR-MP-06**; the docblock and guide should *point*, not restate |
| **R-2** | **NONE.** No ADR states it; **Deptrac enforces it** (fail mode, 0 violations) | ⚠️ **The inverse defect: a test without a rule.** Enforcement exists with no canonical statement — so the *reason* is unrecorded and a future engineer could "fix" the config believing it over-strict |
| R-3, R-7 | ADR-MP series / PB-006 record | ✅ Single, though see the observation below |

**Two navigation observations (not defects, worth recording):**
- **R-6's canonical home is an *infrastructure* ADR, but its subject is a *tactical* property** (what a domain event may contain). An engineer asking *"what may a domain event carry?"* would not think to open a Messaging-Platform ADR.
- **R-7 lives in a PB ticket record**, not in a strategic artifact, although it is a strategic dependency-direction rule.

Neither justifies moving a frozen decision. Both justify the contract existing as a **navigation map**.

## Phase 3 — Strategic dependency audit (has anything been over-elevated?)

| Candidate | Was it heading for strategy? | Corrected classification |
|---|---|---|
| **Hydrators (R-3)** | Yes — the contract's prominence made it read as a boundary law | **L3.** It is an artifact of one dispatch step. *(Its useful content — "don't import it" — is R-2, which is strategic for a different reason: dependency direction, not hydrators)* |
| **Payload shape (R-5)** | Yes | **L3.** Serialization. Elevating it would criminalize legitimate structured payloads |
| **Serialization / `schema_version`** | Never claimed | **L3** — ADR-T5 |
| **Transport mechanics** (outbox/inbox/relay) | Never claimed | **L3** |
| **Idempotency (R-8)** | Partly — previously read as strategic | **L3-derived + L2 consequence** |

**No strategic rule was found to be secretly tactical.** The over-elevation risk ran the other way — three infrastructure mechanisms sitting inside a document that reads like a boundary contract.

## Phase 4 — Infrastructure abstraction review

| Mechanism | Replaceable mechanism or architectural concept? | The enduring concept beneath it |
|---|---|---|
| **Outbox** | **Mechanism** | *State and its announcement commit atomically* (ADR-T3's real content) |
| **Inbox** | **Mechanism** | *Effectively-once consumption* (ADR-T4's real content) |
| **Hydrator** | **Mechanism — the most replaceable of all** | None. It exists solely because the relay re-materializes events for the in-process bus |
| **Relay / `outbox:process`** | **Mechanism** | *Eventual delivery with retry* |
| **`IntegrationEventDispatcher`** | **Mechanism** | *Deterministic consumer resolution + consumer isolation* (ADR-MP-06) |
| **`InboxMessage`** | **Mechanism** | *The crossing carries data, not model* — which is R-1/R-9 |

**None of these mechanisms is stable enough to become architectural policy, and none needs to be.** Every one of them has an enduring concept already recorded in an ADR. Policy attaches to the concept; the mechanism stays replaceable.

## Phase 6 — Governance matrix

| Invariant | Layer | Canonical owner | Contract role |
|---|---|---|---|
| R-1 no cross-context Domain import | **L1** Strategic | **ADR-T16** | **Pointer** |
| R-2 no cross-context Infrastructure import | **L1** Strategic | **⚠️ TO BE ASSIGNED** — recommended: ADR-T16 succession annotation | **Pointer**, once assigned |
| R-3 hydrator is producer-side | **L3** Infrastructure | ADR-MP series (messaging design) | **Descriptive note** |
| R-4 consumer reconstructs its own model | **L1** (+L2) | **ADR-T16** | **Pointer** |
| R-5 payload primitives only | **L3** Infrastructure | **ADR-T5** | **Descriptive guidance** |
| R-6 provenance on the envelope | **L2** (+L3) | **ADR-MP-06** *(two restatements should become pointers)* | **Pointer** |
| R-7 producer never names consumers | **L1** Strategic | **PB-006** principle *Registration ≠ Delivery* | **Pointer** |
| R-8 consumer idempotency | **L3**-derived (+L2) | **ADR-T3** + **ADR-T4** | **Pointer** |
| R-9 identity crosses as opaque string | **L1** Strategic | **ADR-T16** | **Pointer** |
| *(scope)* event-carried async integration only | **L1** Strategic scope | The contract itself | **Own it** — it is the map's own extent, not a rule about code |

**Reading the matrix:** four strategic invariants (R-1, R-2, R-7, R-9) plus R-4's core · one tactical (R-6) · three infrastructure (R-3, R-5, R-8) · **the contract owns exactly one thing: its own scope.**

## Final recommendation

1. **The contract becomes a NAVIGATION DOCUMENT.** It owns no rule. For each invariant it states the layer, the canonical owner, and a pointer. This satisfies the success criterion directly: it stops being a competing source of truth.
2. **Assign R-2 a canonical home — this is the one real governance gap.** Deptrac enforces a rule no artifact states. Recommended vehicle (ARB's choice): a **succession annotation to ADR-T16** extending its scope from *Domain types* to *any cross-context import*. Until assigned, R-2 is a test without a rule.
3. **Retire two restatements of R-6** into pointers (the `EventProvenance` docblock and audit-system guide 08 currently restate what ADR-MP-06 owns).
4. **Keep R-3, R-5, R-8 explicitly at Layer 3** with the enduring concept named beside each mechanism, so a future transport change updates *mechanisms* without re-litigating *architecture*.
5. **Record two navigation observations without moving anything:** R-6's canonical home is an infrastructure ADR hosting a tactical rule; R-7's is a PB record hosting a strategic rule. Frozen decisions stay put; the map compensates.
6. **WP-4 is unaffected and may proceed.** Every rule it must obey is Layer-1 strategic (already normative) or Layer-3 mechanical (already implemented). Its prerequisite G-2 is a **Layer-2 tactical** decision — the business-condition → inbox-marker seam — which this classification leaves entirely intact.

## Success criteria (self-check)

☑ Every invariant has one architectural owner (R-2's is the one to be *assigned*, and that gap is the finding) · ☑ duplicate governance identified (R-6's three homes) with canonical owner named · ☑ replaceable infrastructure separated from enduring architecture (Phase 4) · ☑ **future technology change would not invalidate any Layer-1 rule** (Phase 5, run first) · ☑ the contract is recommended to become a navigation document owning only its own scope.

---

**Traceability:** DA layer-classification commission 2026-07-31 · strategic validation `2026-07-31-cross-context-contract-strategic-validation.md` · contract `docs/architecture/Cross_Context_Integration_Contract.md` · ADR-T16 · ADR-T3/T4/T5 · ADR-MP-06 (+ `EventProvenance` docblock, guide 08) · PB-006 *Registration ≠ Delivery* · EPIC-002 accountability derivation · `deptrac.yaml` (R-2 enforcement without a stated rule). **No artifact modified; no code written.**
