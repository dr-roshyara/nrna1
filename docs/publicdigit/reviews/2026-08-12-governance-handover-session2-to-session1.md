# Governance handover — Session 2 → Session 1 (`D-ENT-1`)

**Type:** Cross-stream governance handover · **Date:** 2026-08-12 · **From:** IERVP (Session 2) · **To:** Session 1 (Master Matrix / verification estate)
**Purpose:** transfer the `D-ENT-1` domain decision as **external evidence**, with the discriminator for applying it. **This is the explicit handoff artifact whose absence was previously recorded.**

**⛔ This document classifies nothing.** No row of the Master Matrix is read as authority, classified, or modified by Session 2. **Session 1 remains the sole authority over its own artifacts.**

---

## 1 · Status of the decision being handed over

| Item | Status |
|---|---|
| **`D-ENT-1`** | ✅ **RESOLVED** |
| **`Q-A0`** | ✅ **= `F1`** |
| **Model B** | ✅ **ADOPTED** |
| **`ADR-002` amendment** | ⚠️ **PROPOSED — NOT YET APPLIED** |
| **Implementation** | ⛔ **NOT AUTHORISED** |
| **`Q3` (exercisability representation)** | 🔓 **OPEN** |

> ⚠️ **Read this before consuming anything below: the business decision is resolved, but `ADR-002` is NOT yet amended.** Until the Product Owner applies it, `ADR-002` remains **Accepted in its current form**, and its clause *"eligibility computed from membership at check time"* is **still the standing ADR text**. **Session 1 should treat `D-ENT-1` as an adopted Product Owner decision, and `ADR-002` as not yet reflecting it.**

## 2 · The adopted decision, in the form Session 1 needs

> **`ElectionMembership` is the election-specific entitlement record. Admission creates it. It remains associated with the election unless a defined election-level termination rule ends it. Whether it is currently *exercisable* is governed by the election's voting rules and voter-level suspension/governance state.**
>
> **`Q-A0` = `F1`: in Full Membership mode, organisation membership is an ADMISSION PREREQUISITE — NOT continuously evaluated for retaining the entitlement.**
> **In Election-Only mode, no organisation `Member` is required.**
> **The Election Chief may suspend an `ElectionMembership`; suspension is independent of organisation membership.**
> **Credential possession is NOT entitlement; credential control is a separate security concern.**

**The seven concepts, never to be collapsed:** admission entitlement · durable `ElectionMembership` · Chief suspension · voting exercisability · credential possession · credential issuance/revocation · already-voted.

---

## 3 · 🔑 The discriminator

> **A test is relevant to `D-ENT-1` only if its business assertion depends on organisation membership being CONTINUOUSLY REQUIRED **after** `ElectionMembership` has been established.**

| Test asserts… | Under `Q-A0`=`F1` |
|---|---|
| an **admission-time** consequence of organisation status *(e.g. a non-member or unpaid member cannot be assigned/imported as a voter)* | ✅ **GOVERNED BY `F1` — consistent, likely unaffected** |
| a **voting/exercise-time** consequence that **re-derives** entitlement from organisation status *(e.g. an admitted voter is refused at the ballot because their org membership lapsed)* | ⚠️ **POTENTIALLY AFFECTED — assess against the adopted decision** |
| suspension, restoration or removal behaviour | ⚠️ Relevant to **Question B**, **not** to `Q-A0`. Assess separately |
| credential issuance, arming or possession | ❌ **Not `D-ENT-1`** — a separate concern (`Q-E1`) |
| lifecycle/window behaviour | ❌ **Not `D-ENT-1`** |

**The pivot is the *moment of evaluation*: admission-time vs exercise-time.**

## 4 · What must NOT be used as a basis for classification

**Explicitly, per the commission:**

* ❌ class name
* ❌ method or test-method name
* ❌ the word *"eligibility"* appearing anywhere
* ❌ a reference to `ElectionMembership`
* ❌ implementation field names (`status`, `suspension_status`, `has_voted`, …)

> **Mechanism reference is not affectedness.** A test class may touch `ElectionMembership` purely as a fixture. **The 213 rows are `POTENTIALLY AFFECTED`; `AFFECTED` and `BLOCKED` are NOT ESTABLISHED** — and Session 2 has not established them either.

## 5 · What Session 1 may consume, and what it may not

| May consume | May **not** |
|---|---|
| The adopted decision in §2, as an **external authoritative business decision** | Treat the **proposed** `ADR-002` amendment as applied |
| The discriminator in §3, to classify **by test intent** | Reinterpret, extend or amend `D-ENT-1` |
| The concept separation in §2, to avoid conflating axes | Implement anything, or change tests/fixtures/code |
| The knowledge that `Q3` is **open**, so exercisability *representation* is not yet decided | Classify a row as affected because it references a mechanism |
| The implementation findings in §6, **as findings only** | Treat any finding as an approved repair, or as a business rule |

## 6 · Implementation findings passed as context — **FINDINGS ONLY**

**None is authorised for repair. None is a business rule. They are offered so Session 1 is not surprised by them while reading tests.**

| # | Finding |
|---|---|
| 1 | Suspension writes `status='inactive'` — the entitlement's own field |
| 2 | **Suspended and already-voted are indistinguishable at the enforcing predicate** (`isVoterInElection()` reads only `role` + `status`) |
| 3 | **A suspended voter is issued a fresh credential**; nothing revokes an existing one |
| 4 | `scopeEligible()` implements the **opposite** of `F1` — **0 callers**, and would return **0 of 20** rows because `members` is empty |
| 5 | Enforcement of suspension is **incidental** — nothing reads `suspension_status` |
| 6 | **No test anywhere asserts that a suspended voter cannot vote** — every such test concerns the *election* being suspended or the *organisation member* being suspended |
| 7 | `invited` is expected by schema, UI, tests and documentation, and **written by no production path** |
| 8 | **Full Membership mode has never been exercised** — `members` has **0 rows**, so no admission or retention scenario exists |

> **Finding 6 is the one most likely to matter to a verification estate:** the behaviour `D-ENT-1` and Question B govern is **currently unprotected by any test**. **Whether that is a coverage gap in Session 1's terms is Session 1's judgement, not mine.**
>
> **Finding 8 bounds what any classification can conclude:** the mode the decision concerns has **no runtime evidence at all**.

## 7 · Sequencing

```
Product Owner decides D-APPLY  (apply the ADR-002 amendment?)
            │
            ▼
   ADR-002 amended  ──────────────►  Session 1 consumes an APPLIED ADR
            │
            │  until then: D-ENT-1 is an adopted PO decision,
            │  and ADR-002 does not yet reflect it
            ▼
Session 1 classifies the 213 rows BY TEST INTENT, using §3
            │
            ▼
   Which tests actually verify the adopted rule?  → coverage / architectural gap
```

**Session 1 is not blocked.** Rows whose business authority is already established and which do not depend on `D-ENT-1` can proceed independently. **The 213 are not a reason to pause the Matrix.**

## 8 · Boundaries of this handover

* **Session 2 has not read, used, classified or modified** Session 1's Master Matrix, denominator, `SD-1`, `SD-2`, `SD-4`, Slice 1 classification, the 1,376-test baseline, or the 213 rows. The **213/1,376** figure is taken as given from the Product Owner's message.
* **Session 1 is not authority for Session 2**, and this handover does not make Session 2 authority for Session 1: it transfers **an adopted Product Owner decision plus a discriminator**, which Session 1 applies on its own authority.
* **Nothing in this document is an implementation instruction.**
* **The decision may be superseded**: if the Product Owner declines `D-APPLY`, or answers `D-APPLY-2` by restating the adopted wording, **this handover must be re-read before use.**

**Related:** [`ADR-002 application decision package`](2026-08-12-adr-002-application-decision-package.md) · [`amendment proposal`](2026-08-12-adr-002-amendment-proposal.md) · [`two-mode domain decision report`](2026-08-12-d-ent-1-two-mode-domain-decision-report.md) (`Q-A0` verification) · `PBDIGIT-68`
