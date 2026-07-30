# Cross-Context Integration Contract — Strategic Validation

**Date:** 2026-07-31 · **Role:** Chief Software Architect / ARB · **Commission:** strategic review, recommendation only
**Question:** should the discovered contract remain DESCRIPTIVE, become NORMATIVE, or PARTIALLY NORMATIVE?
**Method:** *not* "the code does it" but "should every bounded context do it?" — each invariant tested against the **ratified strategic baseline**, then against counter-examples and future contexts.
**Constraints honored:** no code · no ADR created or changed · contract not rewritten · no context redesigned · WP-4 not implemented

---

## The finding that reframes the whole commission

**Most of R-1..R-9 are already normative.** They are not candidates for elevation — they are restatements of frozen decisions:

| Invariant | Already normative via | Consequence |
|---|---|---|
| R-1 no cross-context Domain import | **ADR-T16** (frozen) | Elevating again would **duplicate a frozen ADR** |
| R-4 consumers reconstruct local VOs | **ADR-T16** (its second half) | Same |
| R-9 identity crosses as opaque string | **ADR-T16** | Same |
| R-6 provenance on the envelope, never in a domain event | **ADR-MP-06** + PB-006 closure ruling (*"no Domain Event may carry correlation/causation"*) | Same |
| R-7 producer never names its consumers | **PB-006**'s recorded permanent principle *Registration ≠ Delivery* | Same |
| R-8 consumer idempotency | **ADR-T3** (at-least-once) + **ADR-T4** (inbox dedupe) | The *requirement* is normative; "two seats" is the pattern |

**Elevating these would violate the project's own governing rule — *the ubiquitous language lives once*.** The contract's correct relationship to them is **pointer, not policy**.

## 1. Strategic review of each invariant (Phase 1)

| # | Invariant | Classification | Why |
|---|---|---|---|
| **R-1** | No consumer imports another context's Domain | **STRATEGIC INVARIANT** — and grounded far deeper than convention | The ratified baseline states *"Zero Partnership/Shared Kernel anywhere is not a stylistic preference — it is a consequence of the domain's accountability requirement… constitutional trust requires every trust-bearing crossing to be attributable: who owed what to whom. Jointly-owned models blur precisely that attribution."* R-1 is therefore a consequence of **constitutional attributability**, not of the current code. Already normative (ADR-T16) |
| **R-2** | No consumer imports another context's **Infrastructure** | **STRATEGIC INVARIANT — and the one genuine gap** | Same autonomy logic one layer out: importing a producer's hydrator couples a consumer to the producer's *transport implementation*, which is strictly worse than coupling to its model. **Enforced by Deptrac, stated by no ADR** — ADR-T16 speaks of Domain/aggregate types. This is the single clause with normative content not already recorded elsewhere |
| **R-3** | The hydrator is producer-side | **TACTICAL / INFRASTRUCTURE — contingent, do NOT elevate** | It is a *consequence of the current relay design* (the relay hydrates to dispatch on the in-process event bus). Change that step and hydrators might not exist at all. What is strategic here is R-1/R-2, of which R-3 is one current expression |
| **R-4** | Consumers reconstruct local VOs from primitives | **STRATEGIC INVARIANT** (already ADR-T16) | Reconstruction is what makes semantic isolation real: each context assigns its own meaning |
| **R-5** | Payload carries primitives only | **TACTICAL GUIDANCE — do NOT elevate as written** | Its *strategic* content is "no domain types on the wire", which **is R-1 restated**. As a literal law, "primitives only" over-constrains: ADR-T22 already added a flat array, and a future Evidence context plausibly needs structured metadata. Elevating the literal form would make a legitimate payload shape an architectural violation |
| **R-6** | Provenance on the envelope | **STRATEGIC INVARIANT** (already ADR-MP-06) | Audit lineage must not become domain state |
| **R-7** | Producer never names its consumers | **STRATEGIC INVARIANT** (already PB-006 principle) | This is what makes a producer independently evolvable and a consumer independently addable |
| **R-8** | Consumer idempotency | **STRATEGIC requirement (already ADR-T3/T4), tactical phrasing** | At-least-once delivery *forces* idempotency; "two seats" is how it is currently realized, not a law |
| **R-9** | Identity crosses as an opaque string | **STRATEGIC INVARIANT** (already ADR-T16) | Prevents type coupling while permitting correlation |

## 2. Counter-example analysis (Phase 2)

| Scenario | Does an invariant legitimately break? |
|---|---|
| **Shared Kernel** | **No — and this is settled, not open.** The ratified baseline rejects SK *on every edge*, on accountability grounds, and records the falsifiability condition explicitly: *"should a genuinely co-owned model ever be discovered in later work, admitting it is an ARB-gated architectural change, not a silent pattern upgrade."* So SK is not a counter-example; it is a pre-agreed ARB gate |
| **Anti-Corruption Layer** | **No.** An ACL translates *at the consumer's edge from the wire*, not by importing foreign domain types. PB-004's `LegacyElectionExistenceAdapter` is the working proof: it reaches legacy **tables**, not another context's Domain |
| **Customer–Supplier** (ratified on the custody edge) | **No.** C–S governs *whose requirements shape the contract*, not what crosses it |
| **Legacy integration** (EPIC-006 strangler) | **R-1 is vacuous** — legacy code is not a bounded context with a Domain namespace. The *spirit* applies via ACL. Not an exception; an absence of subject |
| **External / third-party integration** | **R-5 can legitimately break.** A third party's payload format is not ours to constrain. Confirms R-5 as tactical, and that an ACL is the right seam |
| **Long-running workflows** | **No.** WP-2's PM is exactly this case and conforms unchanged |
| **Multiple producers of one event type** | **No.** R-7 makes it natural; consumers key on event type, not producer |
| **Event version evolution** | **No.** ADR-T5's window handled v1→v2→v3 without touching any invariant |

**Only one genuine exception found, and it belongs to a clause already classified tactical (R-5).**

## 3. Strategic DDD review (Phase 3)

| Property | Supported? | Note |
|---|---|---|
| Bounded-context autonomy | ✅ Strongly | R-1/R-2/R-9 make a context replaceable behind its payload contract |
| Ubiquitous language | ✅ | Each context names things in its own language; R-4 is the translation point |
| Independent deployment | ✅ | No compile-time coupling between contexts at all |
| Independent model evolution | ✅ | ADR-T5's versioning + R-5's looseness are what permit it |
| Anti-corruption | ✅ | R-4 *is* an ACL at the payload boundary, applied uniformly |
| Published-language evolution | ⚠️ **One weakness** | The contract describes the *current* window rule by reference but says nothing about **deprecating** a published event or **retiring a consumer**. v1-retirement happened in WP-1 as a slice decision, not under a stated rule |

**Strategic weakness recorded (not a blocker):** the contract governs how a crossing *works*, not how a crossing is *retired*. Deprecation is real future work (Evidence/Voting will add many events) and currently rests on ADR-T5 plus per-slice judgment.

## 4. Future bounded-context simulation (Phase 4)

| Context | Contract holds unchanged? | Finding |
|---|---|---|
| **Evidence** | Yes for the crossings; **R-5 strained** | Evidence metadata is plausibly structured (hashes, chains, timestamps, custody records). Fine under "no domain types on the wire"; awkward under a literal "primitives only" — a second reason R-5 must stay tactical |
| **Voting** | Yes | Anonymity constraints *reinforce* the contract: primitives only, no linkage, opaque identity |
| **Appointment** | Yes | Mandate events are already in the catalog and shaped like existing crossings |
| **Governance** | **NO — a scope limitation, not a violation** | Q-1 rules that *"Adjudication depends on Governance's authority decision through a published contract"*, and the crossing's **shape is explicitly undecided** (candidate names recorded, none chosen). It may be **request/response or query-shaped**, not event-carried. **The contract as written governs only event-carried asynchronous integration** — every rule presumes an `InboxMessage`. It does not cover synchronous contract calls |

**This is the commission's second material finding: the contract's *scope* must be stated.** Left implicit, a future engineer would reasonably read it as governing *all* cross-context communication and either force Governance's authority crossing into an event shape it may not want, or conclude the contract was violated.

## 5. Risks of elevation

| # | Risk | Mitigation |
|---|---|---|
| **E-1** | **Duplicating frozen ADRs** — elevating R-1/R-4/R-6/R-7/R-8/R-9 creates second homes for rules that already live in ADR-T16/T3/T4/MP-06 and PB-006, violating *rules live once* | Point, never restate (§7) |
| **E-2** | **Freezing a contingent mechanism** — elevating R-3 would make today's relay design architectural law | Classified tactical |
| **E-3** | **Over-constraining payloads** — elevating R-5 literally would make legitimate structured payloads violations | Classified tactical |
| **E-4** | **Silent over-reach** — an unscoped contract implies authority over synchronous crossings it never analysed | State the scope (§6/§7) |
| **E-5** | **Retirement gap** — no rule governs deprecating a published event | Recorded as future work, not elevated on one instance |

## 6. Recommendation (Phase 6)

> ### **PARTIALLY NORMATIVE — but with an unexpected partition.**

**Elevate exactly one clause: R-2** (no consumer imports another context's Infrastructure, including its hydrators). It is the only clause with normative content **not already recorded elsewhere**: Deptrac enforces it, no ADR states it, and WP-4 came within one design decision of violating it.

**Do not elevate R-1, R-4, R-6, R-7, R-8, R-9** — they are already normative. The contract should carry **pointers** to ADR-T16 / ADR-T3 / ADR-T4 / ADR-MP-06 / PB-006, not copies.

**Keep R-3 and R-5 explicitly DESCRIPTIVE / tactical**, each with its reason recorded (contingent mechanism; over-constraint risk).

**Add a scope statement:** the contract governs **event-carried asynchronous integration**. Synchronous or query-shaped crossings — Governance's authority contract being the live case — are **out of scope and unanalysed**.

**Record two items as future work, not as rules:** published-event **retirement/deprecation** (§3), and the optional fitness test making R-2/R-3's *reason* legible (Deptrac already covers the risk).

## 7. Proposed ARB decision

1. **Contract status: PARTIALLY NORMATIVE**, per §6.
2. **Elevate R-2 only.** Natural home: an **annotation to ADR-T16 extending its scope from Domain types to any cross-context import** — a succession-style amendment, not a new ADR, so the rule still lives once. *(The ARB decides the vehicle; this review does not create it.)*
3. **Convert R-1/R-4/R-6/R-7/R-8/R-9 to pointers** inside the contract document, citing their existing normative homes.
4. **Mark R-3 and R-5 as tactical/descriptive**, with their recorded reasons.
5. **Add the scope statement** limiting the contract to event-carried asynchronous integration, and **note Governance's authority crossing as an open integration-style question** owned by the Q-1 contract design.
6. **Record, do not rule:** published-event retirement; the optional R-2/R-3 fitness test.
7. **WP-4 may proceed** on this basis: every rule it must obey is either already normative or (R-2) already machine-enforced. **It introduces no new architectural concept**, and its one prerequisite (G-2, the business-condition → inbox-marker seam per PB-005 F-1) is unaffected by this review.

## Success criteria (self-check)

☑ Every invariant strategically classified (§1) · ☑ future contexts evaluated, incl. one that **fails** the scope (§4) · ☑ implementation convention distinguished from architectural policy (R-3/R-5 held back) · ☑ **only justified rules recommended for elevation — exactly one** · ☑ WP-4 can proceed knowing which rules are permanent architecture and which are current implementation choices.

---

**Traceability:** DA strategic-validation commission 2026-07-31 · contract `docs/architecture/Cross_Context_Integration_Contract.md` · readiness review `2026-07-31-wp4-integration-readiness-review.md` · ratified baseline `EPIC-002_Relationship_Pattern_Selection.md` (Published Language; Customer–Supplier on the custody edge; **zero Partnership/Shared Kernel, on accountability grounds, ARB-gated falsifiability**) · ADR-T16 · ADR-T3/T4/T5 · ADR-MP-06 · PB-006 (*Registration ≠ Delivery*) · Q-1 authority resolution · `deptrac.yaml`. **No artifact modified; no code written.**
