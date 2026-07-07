# Architecture Review Report — Messaging Platform Strategic Model

**Review type:** independent synthesis/review (ARB posture) of `Messaging_Platform_Strategic_Model.md`, preparing it for promotion into **AKB Release 1.2**.
**Date:** 2026-07-07 · **Reviewer role:** Architecture Review Board (challenge every assumption; add no new ideas) · **Governs:** the discovery→design transition.
**Constraint honored:** no code, no architecture tests, no repository behavior change. Discovered gaps are documented and classified, never implemented.
**Verdict:** **APPROVE the model for promotion, conditional on the recommended changes below (RC-1…RC-6) being applied to the discovery artifact.** Q1–Q4 resolved; Q5 consciously deferred as architecture debt.

---

## Step 1 — Challenge the model (is each claim business-, DDD-, or implementation-derived?)

| Section | Basis | Verdict |
|---------|-------|---------|
| "What it is" opens with *"moves events…"* | **Solution framing**, not business | **WEAKNESS RC-1** — must start from the business problem (trustworthy collaboration between autonomous contexts); Messaging is the *solution*, not the goal |
| Classification "Generic/Technical Subdomain realized as Platform Capability" | DDD, but **conflates role and classification** | **WEAKNESS RC-2** — separate them: *Platform Capability* (architectural role) **implemented using** a *Generic Technical Subdomain* (DDD classification) |
| Customer/Supplier + Published Language | DDD (derived from context autonomy, ADR-T8) | KEEP — architectural, not implementation |
| Ownership Matrix | DDD | KEEP (validated in Step 2) |
| Invariant Catalog | mostly DDD; a few **implementation names** leak in (config keys, store names) | **RC-3** — phrase invariants as properties; move concrete names to a "realized by" note or the future architecture doc |
| Invariant Catalog lacks a **verifier / host** dimension | gap | **RC-4** — add "who verifies" and "who hosts the executable guard" (owner-hosts-the-guard) |
| Deliverable 4/5 (fitness functions) | design detail | KEEP but **re-scope per Q2** (RC-5) |
| §6 open questions | discovery residue | **RC-6** — resolve Q1–Q4, defer Q5 as debt |

**No section depends on a *specific class* to make its point** — good; the model is largely implementation-agnostic. The leaks in RC-3 are names (config keys, table names), not reasoning.

---

## Step 2 — Ownership validation (why owns / why not / what if it changed)

| Entry | Why Messaging owns / doesn't | If ownership changed | Verdict |
|-------|------------------------------|----------------------|---------|
| Registry, Relay, Inbox, Execution, Recovery | These *are* the platform's reason to exist — reliable delivery + idempotent consumption + recovery | If a context owned them, every context would reinvent delivery → duplication, drift, no guarantee | **OWNS — justified** |
| Retry policy | Bounded-retry-to-dead-letter is a delivery property | If a context owned it, retry semantics would diverge per consumer | **OWNS — justified** |
| Retry numbers, cadence | Operational tuning, not architecture | If Messaging owned the numbers, ops couldn't tune without an ADR | **COORDINATES — justified** (Operations owns values) |
| Transport audit ledger | The dedupe/outbox rows are Messaging's own bookkeeping | If a context owned them, dedupe couldn't be enforced centrally | **OWNS (mechanism)** — business audit stays with contexts |
| Correlation/causation propagation | Messaging carries the ids; it does not assign business meaning | If Messaging owned meaning, business semantics would leak into infra | **OWNS carriage; semantics = business** — justified |
| **Anonymity (C1)** | Messaging carries payloads; the *rule* is constitutional | If Messaging "owned" it, the constitution's invariant would be hostage to an infra component; the owner could not evolve it | **PRESERVES (not owns) — justified (Q1)** |
| **Tenant isolation (C2)** | Same shape as anonymity: a constitutional/multi-tenant rule Messaging must not violate | If Messaging owned it, isolation policy would live in infra, away from the constitution | **PRESERVES (Election owns) — justified (Q4)** |
| **Ordering** | Delivery is per-message idempotent; cross-message ordering is a *consumer* consistency concern | If Messaging silently owned it, a hidden guarantee would exist that no ADR sanctioned | **DOES NOT OWN — justified (Q3)**; new ADR if ever needed |
| Replay mechanism / Monitoring | Mechanism is platform; policy/alerting is operational | If Messaging owned policy, ops decisions would be frozen in infra | **OWNS mechanism / OBSERVES — justified** |
| Business decisions | The whole point of the platform is to *not* hold these | If any leaked in, Messaging would become a hidden domain | **DOES NOT OWN — justified** |

**Result:** every ownership entry is justified; none is unexplained. Ownership is **complete**.

---

## Step 3 — Invariant validation (class · owner · preserver · verifier · host of executable architecture)

The review adds the missing **verifier/host** dimension (RC-4). "Host" = which suite should *own the executable guard*, following **owner-hosts-the-guard**.

| ID | Class | Owner | Preserver | Host of executable architecture |
|----|-------|-------|-----------|--------------------------------|
| P1–P10 (single-writer, execution/recovery ownership, txn, clock, messaging-ownership, hydration-path, port purity, immutability, dedupe key) | **Platform** | Messaging | — | **Messaging suite** (Messaging owns → Messaging hosts) |
| I1–I4 (at-least-once, effectively-once, bounded-retry, absolute deadline) | **Infrastructure** | Messaging/substrate | — | integration/feature tests |
| **C1 anonymity** | **Constitutional** | Constitution/Election | Messaging | **Constitutional suite** (owner hosts — **Q2**). Messaging *contributes* to the scan surface but does **not host** the guard |
| **C2 tenant isolation** | **Constitutional** | Election | Messaging | **Constitutional/Election suite** (owner hosts — Q4) |
| O1–O3 (retry numbers, cadence, alerting) | **Operational** | Operations | — | ops/config; not fitness tests |

**Discovered ownership mismatch (from this classification):** the anonymity guard added during C6B lives in `InboxMessagingArchitectureTest` (property #11) — i.e. hosted by a **Messaging-owned** test, but its **owner is the constitution**. That violates owner-hosts-the-guard. See Architecture Debt **AD-M1**. *(Documented and classified only — not fixed in this review, per the TDD rule.)*

---

## Step 4 — Resolution of open questions (DDD reasoning + ARB rulings)

| Q | Resolution | DDD basis |
|---|------------|-----------|
| **Q1** anonymity classification | **RESOLVED:** Constitutional invariant; Messaging **preserves** | an invariant belongs to the context whose language defines it (the constitution), not to the transport |
| **Q2** where the anonymity guard lives | **RESOLVED:** **Constitutional suite hosts it** (owner-hosts-the-guard). Messaging may contribute the scan surface but must not own the test | executable architecture follows ownership; a preserver does not host the owner's guarantee |
| **Q3** ordering | **RESOLVED:** explicitly **not owned**; any future in-order need is a **new ADR**, never a hidden Messaging feature | no un-sanctioned guarantees; make consistency requirements explicit |
| **Q4** tenant isolation | **RESOLVED:** Constitutional; **Election owns**, Messaging **preserves** | same reasoning as Q1 |
| **Q5** Outbox formal port / hexagonal symmetry | **DEFERRED → Architecture Debt AD-M2 / Future ADR.** Outbox is stable; PB-004 does not require changing it; do not redesign without business pressure | avoid speculative architecture; change under pressure, not aesthetics |

---

## Step 5 — Review findings

### Strengths
- Ownership matrix with a single owner per responsibility and a five-way disposition vocabulary (Owns/Coordinates/Preserves/Observes/Not-owned) — reusable across the AKB.
- Correct, hard-won distinction: Messaging **preserves** (not owns) anonymity and tenant isolation.
- Invariants classified by kind (Platform/Infrastructure/Constitutional/Operational) with zero business invariants inside Messaging — proof the boundary held.

### Weaknesses (→ recommended changes)
- **RC-1** Start from the business problem, not from Messaging.
- **RC-2** Separate architectural role (Platform Capability) from DDD classification (Generic Technical Subdomain).
- **RC-3** Remove residual implementation names from the invariant catalog (phrase as properties).
- **RC-4** Add verifier/host columns to the invariant catalog (owner-hosts-the-guard).
- **RC-5** Re-scope candidate fitness functions: Messaging suite hosts P-invariants only; C1/C2 guards move to the constitutional suite.
- **RC-6** Replace §6 open questions with the Q1–Q5 resolutions above.

### Risks
- **R-A:** the C6B anonymity guard currently sits in the wrong host (AD-M1); until relocated, ownership of that guarantee is misattributed in the executable architecture.
- **R-B:** deferring Q5 (AD-M2) leaves producer/consumer hexagonal asymmetry; acceptable while no consumer needs the outbox port, but must be revisited if a second producer emerges.

### Assumptions (made explicit)
- The relay substrate delivers at-least-once (I1) — assumed, not proven by a fitness test; validated operationally.
- Consumers perform their own anti-corruption inside handlers (ADR-T16) — assumed of every future consumer; PB-004 will be the first test of it.

### Architecture Debt
- **AD-M1 (relocate anonymity guard):** move the messaging anonymity scan from `InboxMessagingArchitectureTest` (property #11) to the **constitutional** suite (owner-hosts-the-guard, Q2). Remediate later via `Finding → Architecture Decision → RED → GREEN`. Not fixed here.
- **AD-M2 (outbox port symmetry):** decide whether the Outbox gets a formal Application port (Q5). Deferred until business pressure.

### Open ADR candidates
- **ADR-M1:** Messaging Platform capability definition + ownership matrix (promote from this model).
- **ADR-M2:** owner-hosts-the-guard principle (executable architecture follows invariant ownership) — generalizable beyond Messaging.
- **ADR-M3 (conditional):** cross-message ordering, only if a context ever needs it (Q3).

---

## Exit assessment
- Ownership: **complete.** · Invariants: **correctly classified** (+ host dimension added). · Responsibilities: **stable.** · Implementation assumptions: **flagged for removal (RC-1…RC-3, RC-6).**
- **Recommendation:** apply RC-1…RC-6 to `Messaging_Platform_Strategic_Model.md`, record the derived decision (Decision Log), then — and only then — write `Messaging_Platform_Architecture.md` (AKB Release 1.2). AD-M1/AD-M2 are tracked debt, not blockers. **PB-004 remains not started.**
