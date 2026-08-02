# Programme Authorization Matrix — which work package is the next authorization candidate?

**Date:** 2026-08-02 · **Prepared by:** Principal Architect / Programme Steward
**Status:** **CONVENED — no ruling issued.** **No architecture redesigned · no code written · no Tactical DDD produced · the roadmap is not reprioritized.**

---

> # ⛔ FINDING FIRST — **WP-3B is not deferred. Its scope was delivered under WP-5 and ARB-approved on 2026-07-31.**
>
> **`tests/Architecture/Messaging/CorrelationIdMintingTest.php`, the allowlist comment, verbatim:**
>
> > *"**WP-5/WP-3B (ARB-approved 2026-07-31)**: the CORRECTION-LOOP origin. The integration conversation begins when the routing act publishes `ChallengeRouted` — the loop's head trigger (ADR-T21)."*
>
> **And the production code matches it.** `CoordinatesContestation::route()` calls `EventProvenance::start()` and publishes `ChallengeRouted`; the allowlist entry is `CoordinatesContestation.php`.
>
> **WP-3B's scope was: *"move `start()` from `CoordinatesAdjudication` to the routing act + update `CHAIN_ORIGIN_ALLOWLIST`."*** **Both halves are done.**

**Its recorded deferral reason — *"depends on the existence of a routing application service"* — is doubly out of date: the service exists (`CoordinatesContestation::raise/admit/route`), and the work the service was blocking has itself been performed.**

**Tenth occurrence of the programme's recurring pattern: a recorded state the repository has moved past, found only by reading the code.** *(Whether this constitutes WP-3B's **delivery**, and whether §WP-3 may therefore close, is a governance determination and is not made here.)*

---

## 1. Programme Authorization Matrix

| Work Package | Authorized | Architecture ready | Governance ready | Engineering ready | Eligible for ARB |
|---|---|---|---|---|---|
| **WP-3B** — correlation origin relocation | ⛔ no | ✅ **decided** (ADR-T21 · ADR-MP-06) | ⚠️ **mis-stated as deferred** | ✅ **apparently DELIVERED** | ⚠️ **yes — but for DISPOSITION, not authorization** |
| **WP-4B** — conclude→issue seam | ⛔ no | ⚠️ **3 open decisions** — `Jurisdiction` ownership · 2 integration paths | ⛔ no | ⛔ no RED boundary can be drawn | ⛔ **not yet** |
| **WP-4C** — `AdjudicationFailureDeclared` | ⛔ no | ⚠️ **shares WP-4B's open decisions** (its counterpart, hydrator and catalog entry inherit them) | ⛔ no | ⛔ nothing built | ⛔ **not yet** |
| **WP-4D** — authority-decision intake port | ⛔ no | ⚠️ **its own definition is one of the open questions** — every "authority-carried" option widens `receiveRulingDecision()` | ⛔ no | ⛔ nothing built | ⛔ **not yet** |
| **WP-7B-R1** — interim anchor extraction | ⛔ no — **R-60 *opened*, Planning Governance · Approval, *"opened, not delivered"*** | ✅ **fully decided in R-60's own text** — `EvidenceAnchorResolver` port + `TemporaryDefaultAnchorResolver`, **behaviour unchanged** | ✅ **one act away** | ✅ **scope is unambiguous; nothing else is required** | ✅ **YES** |
| **WP-8** — end-to-end validation | ⛔ no | ✅ defined in the roadmap | ⛔ **blocked by §WP-4** | ⛔ validates behaviour that does not exist | ⛔ **not eligible** |

**Excluded as closed:** WP-1 · WP-2 · WP-3A (R-67) · WP-4A (R-69) · WP-5 · WP-6 · WP-7 (R-66).

## 2. Critical path — the minimum governance acts that return engineering to implementation

### 2a. Shortest path to *any* engineering work — **one act**

```
authorize WP-7B-R1 to implement  →  RED → GREEN → VERIFY → acceptance evidence
```

**Nothing else is required.** R-60 already fixed the design; **only the permission is missing.**

### 2b. Shortest path to *closing §WP-4 and unblocking WP-8* — **four acts minimum**

```
1  decide ownership of `Jurisdiction`
2  decide the integration path for `EvidenceEnvelopeRef` and `ContestedOutcomeRef`
   (may be one act or two)
3  settle WP-4B's scope ambiguity — is PM-6's issuance confirmation inside it?
4  authorize WP-4B
        ↓ RED → GREEN → VERIFY → ACCEPT
   then WP-4C, then WP-4D, each with its own authorization
        ↓
   §WP-4 closes  →  WP-8 becomes eligible
```

**Acts 1–3 are all inputs to the same ARB session; act 4 cannot precede them, because WP-4B's RED boundary depends on their answers.**

### 2c. The act with the best ratio of effort to programme progress — **one act, no engineering at all**

```
dispose of WP-3B  →  if delivered, §WP-3 closes
```

**A work package may close with no code written, because the code is already written.**

## 3. Recommendation

> ### **WP-7B-R1 is the next legitimate authorization candidate.**

**Evidence, from accepted programme artifacts only:**

| Criterion | WP-7B-R1 |
|---|---|
| Prerequisites outstanding | **exactly one — the authorization itself** |
| Architectural decisions open | **none.** R-60's operative text specifies the port, the implementation and the constraint (*behaviour unchanged*) |
| Depends on any open work package | **no** |
| Modelling question unresolved | **none** |
| Risk | **low** — a refactor with an explicit no-behaviour-change constraint |

**Why not WP-4B:** three decisions and one scope ambiguity stand before its RED boundary can even be drawn (§2b). **Authorizing it now would authorize work whose boundary is undefined.**

**Why not WP-3B:** ⚠️ **it does not need an authorization.** On the evidence above its scope is already implemented and ARB-approved. **What it needs is a disposition — and that act is independent of this recommendation and can be taken in the same session.**

> **Stated at its evidential strength:** WP-7B-R1 is the **next legitimate authorization candidate**, not necessarily the **most valuable next governance act**. **§2c is arguably worth more** — it may close a roadmap work package for the cost of one ruling. **Choosing between them is the Board's; both are presented.**

**One consequence recorded, not urged:** **WP-7B-R1 does not advance §WP-4 and therefore does not move WP-8 closer.** **If the programme objective is WP-8, §2b is the critical path and WP-7B-R1 is parallel work — valuable, but not a substitute.**

## 4. Not in this commission

The ownership and integration-path decisions themselves · WP-4C's and WP-4D's definitions · WP-8 planning · the register-recording gap on WP-3A's and WP-4A's authorizations · contract R-2's missing ADR home · **any change to the roadmap's priority order.**

---

**Traceability:** **R-60** (WP-7B-R1 opened, *"opened, not delivered"*, classification corrected to **Planning Governance**) · **R-66 · R-67 · R-68 · R-69** · `tests/Architecture/Messaging/CorrelationIdMintingTest.php` (the allowlist and its *"WP-5/WP-3B (ARB-approved 2026-07-31)"* comment) · `app/Contexts/Contestation/Application/Service/CoordinatesContestation.php` · `.claude/plans/WP-3-challengerouted-published-language.md` · `engineering/verification/commissions/2026-08-02-issuance-input-ownership-decision-package.md` · `engineering/verification/reports/2026-08-02-wp4b-engineering-discovery.md` · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-4 · §WP-8 · §8. **No ruling issued · no architecture changed · no code written · no priority reordered.**
