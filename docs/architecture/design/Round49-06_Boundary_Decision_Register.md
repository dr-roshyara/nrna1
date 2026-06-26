# Round 49-06 — Boundary Decision Register (BDR)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** EBSD — decisions · **Built against:** Landscape v1.0 / Package 1.0.0
**Status:** 🔒 BOUNDARY DECISION REGISTER **v1.0 — IMMUTABLE.** **Decisions only.** Evidence in `49-04`; reasoning in `49-05`. Authoritative software-boundary output consumed by Migration (`49-03`) + Aggregate Discovery (Round 50). **Changes only by a versioned re-issue (BDR v1.x / v2.0) via ADR — never silently edited** (treated like an Architecture Constitution).
**Date:** 2026-06-26 · **closes Strategic DDD Discovery (Phase II)**

> **Separation maintained:** 49-04 Evidence · 49-05 Reasoning · **49-06 Decisions (this)**. **Certified governance concepts are UNCHANGED** — only *software boundaries* are decided (semantic ≠ software ownership). Verdicts per `Round48A` v1.1 taxonomy.
> **BC maturity (sub-class — different architectural states, not equally mature):** **Operational BC** (confirmed + implemented + behavior verified) · **Architectural BC — Unrealized** (confirmed but the certified behavior is absent in code) · **Architectural BC — Greenfield/Planned** (confirmed, to be built).

## Register

| # | Candidate | Decision | Confidence | Evidence-Suff. | Evidence | Notes |
|---|-----------|----------|-----------|----------------|----------|-------|
| BDR-01 | **Evidence** | **Confirmed BC — Operational** | High | Yes | EV-010, EV-011 | immutable System of Record; behavior verified |
| BDR-02 | **Voting** | **Confirmed BC — Operational** | Med-High | Yes | EV-070, EV-071 | anonymous vote SoR; Results=projection; Active-Record→R50 |
| BDR-03 | **Appointment (Authority)** | **Confirmed BC — Operational** | **Med-High** | Medium | EV-050, EV-051 | owns Mandate truth (lifecycle+status+policy); prior reversed (EP-5) |
| BDR-04 | **Contestation** | **Confirmed BC — Architectural (Greenfield/Planned)** | High | Yes | EV-090 | S-5 standing; closes the loop; build target |
| BDR-05 | **Adjudication** | **Confirmed BC — Architectural (Unrealized)**; existing jurisdiction-arbitration = separate concern | Medium | **No** | EV-001, EV-002, EV-003 | scope question open; election-determination behaviorally absent |
| BDR-06 | **Replay** | **Application Capability** (over Evidence) | Medium | **No** | EV-020, EV-021 | **owns operational state (Session/Certification) but NOT business truth**; revisit after implementation |
| BDR-07 | **Authorization** | **Supporting Subdomain / Domain Service** | Medium | Medium | EV-030, EV-031 | pure resolver; owns no truth; consumes Mandate |
| BDR-08 | **Election Lifecycle** | **Supporting Subdomain** (derivation over Election); candidate merge w/ Voting/Election | Medium | Medium | EV-060, EV-061 | computes read-model snapshot; owns no separate SoR |
| BDR-09 | **Audit** | **Infrastructure / Platform Capability** | Med-High | Med-High | EV-040, EV-041 | fire-and-forget logger; full-IP → Anonymity review |
| BDR-10 | **Results** | **Derived Read Model** (not a BC) | High | Yes | EV-070 | reconstructable from Vote (hasMany) |
| BDR-11 | **Legitimacy** | **Derived Read Model** (not a BC) | High | Yes | — | single resolver `LegitimacyOutcome`; never persisted |
| BDR-12 | **Anonymity** | **Architectural Invariant** (not a BC) | High | Yes | EV-070 | supreme; schema-level (no `user_id`) |
| BDR-13 | **Trust-Anchor / Consent** | **External Boundary** (not in software) | High | Yes | — | device-PKI "Trust" distinct (GI-2) |

## Tally
**Confirmed BCs: 5** — **3 Operational** (Evidence, Voting, Appointment) + **1 Greenfield** (Contestation) + **1 Unrealized** (Adjudication). **Application Capability: 1** (Replay). **Supporting Subdomain/Service: 2** (Authorization, Lifecycle). **Infrastructure: 1** (Audit). **Read Models: 2** (Results, Legitimacy). **Invariant: 1** (Anonymity). **External: 1** (Trust-Anchor/Consent).

**Of 8 owning candidates → 5 Confirmed BCs (1 unrealized), 3 downgraded** (Authorization service, Lifecycle supporting, Audit infra; Replay capability). Falsification worked; certified concepts untouched.

## Consumed by
- **Migration (`49-03`):** create authoritative modules **only** for BCs `BDR-01..05` (Evidence, Voting, Appointment, Contestation, Adjudication). Replay/Authorization/Lifecycle/Audit get **no standalone module** (capability/service/infra). Migrate **only** these confirmed; **Contestation built greenfield**.
- **Aggregate Discovery (Round 50):** potential aggregates within confirmed BCs; resolve BDR-05 (Adjudication scope) and BDR-06 (Replay) — both flagged **Evidence-Sufficiency = No**.
- **Governance items (parallel):** GI-1 Eligibility, GI-2 Trust/Consent (→ KRG).

## Re-open triggers (Evidence-Sufficiency = No)
- **BDR-05 Adjudication** — re-evaluate when the election-result/certification path is read (is certified Adjudication greenfield, or is jurisdiction-arbitration it?).
- **BDR-06 Replay** — re-evaluate after implementation (does it own a domain decision independent of Evidence?).

## Boundary Evolution History (append-only — never overwrite)
*A decision that changes later is **recorded as a new dated entry**, not an edit — so the boundary's history is auditable.*

| Date | BDR | From → To | Reason |
|------|-----|-----------|--------|
| 2026-06-26 | all | — → **v1.0 baseline** | initial EBSD verdicts (this register) |
| 2026-06-26 | BDR-05 | Unrealized (scope open) → **Architectural (Greenfield)**, scope RESOLVED → **BDR v1.1** | election-determination path read (below) |
| *(future)* | BDR-06 | Application Capability → ? | only if Replay is shown to own a domain decision (post-impl) |
| *(future)* | BDR-07/08 | Service/Supporting → ? | only on new evidence (e.g. Authorization acquires state) |

### BDR v1.1 delta — BDR-05 (Adjudication) scope RESOLVED *(append-only; v1.0 table unchanged)*
- **Read (provenance):** `ElectionLifecycleState` (…`Counting`→`ResultsPublished`→`Archived`); `app/Contexts/Elections/Domain/Events/ResultsPublishedEvent`; no result-dispute/contestation-resolution found. **[EV-100/101]**
- **Finding:** election **finality = publication finality** (lifecycle `ResultsPublished`, owned by **Lifecycle/Elections** — *administrative*), **NOT** adjudicative finality. Committee `ConstitutionalArbitrationKernel` = a **separate** (jurisdiction) concern.
- **Resolution:** certified **Adjudication** (adjudicate a contested outcome → binding determination) is **Architectural (Greenfield)** — paired with **Contestation** as the **unbuilt core of the correction loop**. The existing `ResultsPublished` is publication-finality (Lifecycle); jurisdiction-arbitration is separate.
- **BDR-05 Evidence-Sufficiency: Yes** (was No). **Confidence: Medium-High.**
- **Architectural significance:** the trustworthiness *differentiator* — **adjudicating a contested election with binding finality — is NOT implemented.** Adjudication+Contestation are the genuine greenfield Core to build.

---

## ✅ PHASE MILESTONE — Strategic DDD Discovery COMPLETE
- **Phase I — Knowledge Discovery & Certification (Rounds 38–46B): COMPLETE.**
- **Phase II — Strategic DDD Discovery (Rounds 47–49): COMPLETE** — this BDR is its terminal artifact.
- **Phase III — Strategic→Tactical Transition (Round 50): STARTS.** *(Renamed from "Tactical DDD": Round 50 still carries strategic confirmations — BDR-06 Replay; aggregate/consistency/transactional boundaries straddle strategic+tactical.)*
- **Phase IV — Tactical DDD: after Aggregate Discovery.**

**Frozen stable artifacts** (change only via versioned re-issue/ADR): `Round47-OP`, ADQC v1.1, EBSD methodology, `Round48A` BC-Evaluation-Framework v1.1, `Round49-04` Dossier, `Round49-05` Evaluation, `Round49-06` BDR. **No further methodology/framework documents** — progress now comes from migration, tactical design, implementation, fitness tests, and empirical validation.

---

*Round 49-06 — Boundary Decision Register — ISSUED (decisions only).*
*5 Confirmed BCs (Evidence/Voting/Appointment/Contestation/Adjudication-unrealized) · Replay=Application Capability · Authorization=service · Lifecycle=supporting · Audit=infrastructure · Results/Legitimacy=read models · Anonymity=invariant · Trust-Anchor=external. 8 owning candidates → 5 BCs + 3 downgrades. BDR-05/06 Evidence-Sufficiency=No (re-open triggers). Certified concepts UNCHANGED. Consumed by Migration (BDR-01..05 only) + Round 50 aggregates.*
