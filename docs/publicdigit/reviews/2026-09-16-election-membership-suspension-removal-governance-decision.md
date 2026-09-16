# Governance Decision: Election Membership Suspension, Removal and State-Machine Relationship

**Type:** Formal PO/ARB governance decision · **Date:** 2026-09-16
**Resolves:** `EM-OPEN-001`, `EM-OPEN-002`, `EM-OPEN-004` · **Remains open, not resolved by this decision:** `EM-OPEN-003` (what concept represents current exercisability, and where does a suspension decision live — this document's SM1/SM2 model is the candidate answer but has not been recorded as resolving `EM-OPEN-003` in `ELECTION_MANIFESTO.md`), `EM-OPEN-005` (who may restore, and with how many actors — same status), `EM-OPEN-006` (credential issuance/invalidation), `EM-OPEN-009` (temporary vs. indefinite suspension) · **Partially informs:** `EM-OPEN-007` (distinguishability is achieved at the Election Membership Lifecycle layer, not at the existing Voting Engine gate — see §9), `EM-OPEN-008` (actor-identity retention is now mandatory for suspension specifically; a general audit-everywhere rule is not adopted here)

---

## Decision authority

This document constitutes the formal PO/ARB decision for the Election Membership governance questions addressed below.

The decision resolves the applicable open governance questions only to the extent explicitly stated in this document.

---

## 1. State-machine model

The existing membership state mechanism remains **State Machine 1**.

State Machine 1 is an existing implementation mechanism and remains unchanged.

It must not be replaced, renamed, migrated, or refactored as part of implementing this decision.

A separate conceptual **State Machine 2 — Election Membership Lifecycle** is established with the following states:

* `INVITED`
* `ACTIVE`
* `SUSPENDED`
* `REMOVED`

State Machine 2 expresses the business lifecycle of an Election Member.

---

## 2. Election Membership lifecycle

The following transitions are authorized:

```text
INVITED   -> ACTIVE
ACTIVE    -> SUSPENDED
SUSPENDED -> ACTIVE
SUSPENDED -> REMOVED
```

Removal represents the final removal of the Election Member from the election.

A removed member cannot be reactivated or reinstated.

---

## 3. Activation

An `INVITED` Election Member must be activated explicitly.

Invitation does not automatically result in an active Election Membership.

Election Chief bulk-import/assignment is a separate authorized path and may establish the Election Membership directly as `ACTIVE`.

The distinction between invitation/activation and Chief bulk import must be preserved.

---

## 4. Voter-management authority

The existing `manageVoters` authorization model remains unchanged.

The authorized roles are:

* Election Chief
* Election Deputy

Commissioners are not added to `manageVoters` by this decision.

---

## 5. Suspension

A single authorized voter-management officer is sufficient to suspend an Election Member.

Suspension is therefore a one-actor action.

The existing two-person suspension-entry implementation is not itself the governing rule and must be changed where it conflicts with this decision.

The effective suspension transition is:

```text
ACTIVE -> SUSPENDED
```

Suspension immediately prevents the member from voting.

Removal is not required to prevent voting.

---

## 6. Removal and four-eyes principle

Removal is a two-step governance process:

### Step 1

```text
ACTIVE -> SUSPENDED
```

performed by an authorized officer.

### Step 2

```text
SUSPENDED -> REMOVED
```

performed by another authorized officer.

The officer who performed the effective suspension must not perform the subsequent removal.

Formally:

```text
SuspensionActor != RemovalActor
```

This separation-of-duties rule must be enforced server-side.

---

## 7. Suspension actor identity

The identity of the officer who performs the effective suspension must be retained as a stable actor/user identifier.

The existing `suspension_proposed_by` concept must not be repurposed for this purpose.

The effective suspension actor and the suspension proposer are distinct concepts.

---

## 8. Reinstatement

Reinstatement is specifically:

```text
SUSPENDED -> ACTIVE
```

A removed member cannot be reinstated.

Reinstatement must remain subject to the existing `manageVoters` authority model unless separately changed by governance decision.

---

## 9. Relationship between State Machine 2 and State Machine 1

State Machine 2 is authoritative for the Election Membership lifecycle.

State Machine 1 remains the existing mechanism used by the application and voting engine.

The synchronization contract is:

```text
SM2                         SM1

INVITED       ------------> invited
ACTIVE        ------------> active
SUSPENDED     ------------> inactive
REMOVED       ------------> removed
```

The following invariant applies:

```text
SM2 = ACTIVE => SM1 = ACTIVE
```

and:

```text
SM2 != ACTIVE => SM1 != ACTIVE
```

The reverse implication is deliberately not established:

```text
SM1 = ACTIVE => SM2 = ACTIVE
```

because State Machine 1 may contain independent conditions affecting voting allowance.

State Machine 1's existing hooks, events, and mechanisms remain unchanged unless a separate governance decision authorizes their modification.

---

## 10. Voting and Code

Suspension is sufficient to stop voting through the existing State Machine 1 / Voting Engine mechanism.

This decision does not modify:

* `Code.has_voted`;
* credential issuance;
* credential invalidation;
* existing anti-double-voting semantics.

Questions concerning credential issuance/invalidation and the distinction between suspension and having voted remain governed by their respective open governance items unless explicitly resolved elsewhere.

---

## 11. Implementation authorization

Following formal recording/adoption of this decision, engineering is authorized to implement the above model using TDD and the minimum required changes.

Implementation must:

* preserve State Machine 1;
* preserve existing hooks where they remain compatible;
* introduce State Machine 2 lifecycle semantics;
* enforce the four-eyes rule;
* preserve `manageVoters = Chief + Deputy`;
* avoid unrelated refactoring;
* leave `Code.has_voted` unchanged.

Any newly discovered governance ambiguity must be returned to PO/ARB rather than resolved implicitly by engineering.
