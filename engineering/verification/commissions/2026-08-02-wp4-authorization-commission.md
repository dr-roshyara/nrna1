# ARB Commission — WP-4 Authorization

**Date:** 2026-08-02 · **Prepared by:** Recording Architect
**Status:** ⛔ **SUPERSEDED ON ITS CENTRAL QUESTION (2026-08-02) — see `engineering/verification/reports/2026-08-02-wp4-state-correction.md`.** **R-67 is not issued here.**
**Question as asked:** **Is WP-4 ready for authorization?**

> ## ⛔ The question is moot. WP-4 is implemented, gated and awaiting slice acceptance.
>
> **`c3165ee88` (2026-07-31) — WP-4 GREEN: `ChallengeRoutedReactionHandler` is in `app/Contexts/Adjudication/Application/` and registered in `AdjudicationServiceProvider::boot()`. The ARB accepted its RED and authorized its GREEN on 2026-07-31.**
>
> **This commission quoted the WP-4 plan's stale status header — *"OPEN — G-2 DECIDED; next step is RED"* — and did not read the plan body recording RED acceptance, GREEN completion and the gates.** **The document below is retained as the record of what was verified and how it went wrong. Its Strategic DDD and Capability Readiness findings stand; its premise does not.**

---

## 1. ⚠️ One supporting row is contradicted by the roadmap — corrected before deliberation

**The commission states:** *"WP-3A/WP-3B do not block WP-4 based on the roadmap's dependency graph."*

**The roadmap says otherwise for WP-3A.** Verbatim:

```
WP-2 (APM core) ────────────► WP-4 (APM wiring) ────► WP-8
                                    ▲
WP-3 (ChallengeRouted pub) ─────────┘
```

> *"**WP-3 before WP-4** (the inbound event must exist as **published language** before its consumer registers)"* — and the section closes: *"**no slice starts before its predecessor's acceptance**."*

**The correction is narrower than a blanket objection, and it matters:**

| | Blocks WP-4? | Why |
|---|---|---|
| **WP-3A** — `ChallengeRouted` publication | ⚠️ **Yes — on the acceptance rule only** | It is the *"inbound event must exist as published language"* dependency. **It is GREEN, so the published language already exists.** What is absent is **acceptance**, not the artifact |
| **WP-3B** — routing application service | ❌ **No** | WP-3's roadmap scope is publication only; the routing **application service** belongs to WP-5's scope. The graph note is explicit: *"**test-seeded head suffices for WP-4 dev**"* |

> **So the commission's claim holds for WP-3B and not for WP-3A — and WP-3A's gap is one ARB act, not engineering work.**

## 2. Evidence

**Roadmap position.** *According to the accepted roadmap, WP-4 is the next candidate work package* in the implementation order `WP-1 → WP-2 → WP-3 → WP-4 → …`. **That is roadmap evidence, not a priority claim.**

### Prerequisites

| Prerequisite | State |
|---|---|
| WP-1 accepted | ✅ |
| WP-2 accepted | ✅ |
| **WP-3A accepted** | ⚠️ **not yet — GREEN, acceptance pending** |
| WP-3B | not a prerequisite (§1) |

**Programme state:** ✅ synchronised — the roadmap specifies, CONTEXT states current state.


### Scope of WP-4 — from the roadmap

> **APM wiring:** `(Adjudication, ChallengeRouted)` inbox handler + registry · issuance request path (PM conclusion txn → issuance txn, INV-B1-bridged crash recovery) · `AdjudicationFailureDeclared` event + integration counterpart + hydrator + catalog entry · authority-decision intake **port** + interim administrative adapter. **Traces to EPIC-004K §§8–10, §12 · ADR-T23. Size M.**

**Keystone tests, already specified:** the crash-recovery seam (concluded-but-unissued → redrive → **exactly one** determination) · `FailureDeclared` publishes with correct provenance · late/duplicate authority decisions translate per the frozen family.

## 3. Strategic DDD Confirmation

| Element | Status |
|---|---|
| Bounded-context ownership · capability ownership | **no change** |
| Context-map relationships | **no change** |
| Published Language · Ubiquitous Language | **no change** |
| Strategic invariants | **no change** |

**WP-4 is implementation, not architecture evolution.** Its scope wires elements the earlier packages built.

## 4. Strategic Capability Readiness

**The conclusion of Strategic DDD.**

**Not *are the classes ready* — is the capability complete enough to begin?**

| Check | Status | Basis |
|---|---|---|
| Capability definition | ✅ | roadmap §WP-4 — inbox handler · issuance request path · `AdjudicationFailureDeclared` · authority-decision intake port |
| Capability **owner** | ✅ | the **Adjudication bounded context** |
| Capability **realization** | ✅ | the **APM coordinates** this capability |
| **Process Manager** | ✅ | the APM **orchestrates the capability's implementation** — it realizes, it does not own |
| Capability boundaries | ✅ | EPIC-004K §§8–10, §12 · ADR-T23 |
| Consumed capabilities | ✅ | `ChallengeRouted` as published language (WP-3A, **implemented**) · APM core (WP-2, accepted) |
| Published Language | ✅ | `ChallengeRouted` exists; `AdjudicationFailureDeclared` is **added by this package**, per its authorized scope |
| Strategic invariants | ✅ | unchanged — see §3 |

> **The capability is ready. What is not ready is its governance.**
>
> **Strategic DDD conclusion: the capability can now enter Tactical DDD implementation without revisiting Strategic DDD decisions.**

**Only now is it meaningful to ask what still blocks authorization.**

## 5. Architectural Blockers

**None identified.** *(Stated as **blocker**, not as absence of deficiency: what bears on authorization is whether architecture prevents execution, not whether architecture is complete.)*

## 6. Governance Blockers

| # | Blocker |
|---|---|
| **1** | **WP-3A acceptance** — required by *"no slice starts before its predecessor's acceptance"* |
| **2** | **WP-4 authorization** — none exists. The plan reads *"OPEN — G-2 DECIDED; next step is RED"*; no WP-4 ruling is in the register |

## 7. Decision — three distinct acts

### Phase A — Verification *(Recording Architect)*

✅ **Complete.** The commission satisfies the review model: evidence · strategic confirmation · capability readiness · blockers separated by kind · one contradicted input corrected before deliberation.

### Phase B — ARB Deliberation *(the Board)*

⏳ **Pending.** Architecture's contribution ends at §6: **no architectural blocker; two governance blockers, both ARB acts.**

**Sequencing follows from whatever the Board decides about WP-3A** — a statement of dependency, not a recommendation:

| If the Board… | Then |
|---|---|
| accepts WP-3A, then authorizes WP-4 | the roadmap's rule is satisfied as written |
| authorizes WP-4 without accepting WP-3A | a **departure from *"no slice starts before its predecessor's acceptance"***, to be recorded as such rather than left implicit |
| requires more evidence | the commission returns for further preparation |

**Architecture recommends no option.**

### Phase C — Governance Decision *(the Board)*

⬜ **APPROVE** / ⏸️ **DEFER** / ❌ **REJECT** — **not yet exercised. R-67 does not exist.**

> **If the Board authorizes WP-4, Engineering becomes *eligible* to begin RED, subject to all recorded governance preconditions.** **Authorization creates permission; engineering still runs its own process.**

## 8. Operational Evidence Review → PKS Classification *(if applicable)*

**The commission *may* produce operational evidence.** After the decision, **evaluate whether convening it generated reusable architectural, governance or engineering observations suitable for PKS recording** — an implementation observation · engineering friction · reusable engineering knowledge.

> **PKS classification is conditional, not implied.** ***"No reusable observation"* is a valid and complete outcome of this review** — the review always runs; the classification step runs only if the review finds something.

**Recorded for evaluation after the decision, not now. Nothing is promoted, and a single occurrence remains an observation.**

## 9. Not in this commission

WP-8 authorization (blocked on WP-4) · the C-2 release owner · **WP-3B's deferral** · **WP-7B-R1** — for which **R-60 *opened* the package (Delivery Governance · Approval, *"opened, not delivered"*) rather than authorizing execution**, unlike R-65's *"Slice 7C is authorized to implement"*. **Recorded as a distinction, not raised for decision here.**

---

> ## Architecture has completed its contribution.
>
> ## No architectural blocker has been identified.
>
> ## The remaining act is an ARB decision.

---

**Traceability:** `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §dependency graph · §implementation order · §WP-4 · §keystone tests · **R-60** · **R-65 / R-66** (the authorization/acceptance pattern) · `.claude/plans/WP-4-apm-wiring.md` · `.claude/CONTEXT.md` (WP status). **No outcome presumed · no priority prescribed · no ruling issued · no engineering started · no pattern promoted.**
