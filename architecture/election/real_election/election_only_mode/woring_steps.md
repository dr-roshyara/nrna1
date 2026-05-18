Below is a **Phase A execution runbook** broken into a **time-boxed, minute-by-minute checklist** optimized for safe Strangler Fig initialization.

Goal of Phase A:

> Freeze current behavior without changing production logic, and establish a regression-safe baseline for all future migration phases.

---

# 🧭 Phase A — Execution Runbook (4 hours / 240 minutes)

## 🧱 Phase Structure Overview

| Block | Time        | Objective                                 |
| ----- | ----------- | ----------------------------------------- |
| A.0   | 0–25 min    | Baseline snapshot                         |
| A.1   | 25–140 min  | Contract tests (RED-first, behavior lock) |
| A.2   | 140–170 min | ElectionMode enum                         |
| A.3   | 170–230 min | Domain interfaces + exceptions            |
| A.4   | 230–240 min | Final validation + freeze checkpoint      |

---

# 🧭 A.0 — Baseline Snapshot (0–25 min)

## ⏱ 0–5 min — Pre-flight checks

* Ensure clean working tree
* Ensure DB is in stable state
* Ensure test environment is consistent

```bash
git status
php artisan test --stop-on-failure
```

---

## ⏱ 5–15 min — Full test run baseline capture

```bash
php artisan test > baseline_test_run.txt
```

Record:

* total tests passed
* failures (if any)
* execution time

---

## ⏱ 15–20 min — Snapshot key metrics

Manually note:

* voter assignment tests status
* eligibility tests status
* cache invalidation tests status

---

## ⏱ 20–25 min — Create baseline marker

```bash
git checkout -b migration/election-strangler-phase-a
git commit --allow-empty -m "Baseline snapshot before Phase A migration"
```

---

# 🧭 A.1 — Contract Tests (25–140 min)

## ⚠️ RULE

These tests MUST reflect **current system behavior**, not desired future state.

---

## ⏱ 25–35 min — Create test structure

```bash
mkdir -p tests/Unit/Contracts
```

Create file:

```text
ElectionMembershipContractTest.php
```

---

## ⏱ 35–55 min — Test: assign voter happy path

Write:

* creates record
* sets role=voter
* sets status=active
* sets election_id/user_id/org_id

Run:

```bash
php artisan test --filter assign_voter
```

---

## ⏱ 55–70 min — Test: duplicate active voter rejection

* expect exception
* ensure DB unchanged

Run immediately after writing

---

## ⏱ 70–85 min — Test: inactive reactivation path

* existing inactive voter
* becomes active again

---

## ⏱ 85–100 min — Test: invalid org membership rejection

* user not in organisation_roles
* expect InvalidArgumentException

---

## ⏱ 100–115 min — Test: bulkAssign success counters

* success count
* invalid count
* already existing count

---

## ⏱ 115–125 min — Test: eligible scope behavior

* active only
* expiry logic
* membership constraint logic

---

## ⏱ 125–135 min — Test: cache invalidation triggers

* saved event
* deleted event

---

## ⏱ 135–140 min — Run full contract suite

```bash
php artisan test tests/Unit/Contracts
```

Expected:

* ALL GREEN (based on current system behavior)

---

# 🧭 A.2 — ElectionMode Enum (140–170 min)

## ⏱ 140–150 min — Create enum file

```bash
mkdir -p app/Domain/Election/Enum
```

Create:

```php
ElectionMode.php
```

---

## ⏱ 150–160 min — Implement enum

* FullMembership
* ElectionOnly
* fromOrganisation()
* helper methods

---

## ⏱ 160–165 min — Create unit test

```bash
mkdir -p tests/Unit/Domain/Election
```

Write:

* fromOrganisation
* isElectionOnly
* label

---

## ⏱ 165–170 min — Run test

```bash
php artisan test tests/Unit/Domain/Election/ElectionModeTest.php
```

---

# 🧭 A.3 — Domain Interfaces (170–230 min)

## ⏱ 170–180 min — Create structure

```bash
mkdir -p app/Contexts/Elections/Domain/Policies
mkdir -p app/Contexts/Elections/Domain/Exceptions
```

---

## ⏱ 180–200 min — Create interface

```php
VoterQualificationPolicyInterface.php
```

* qualifies()
* qualifyingSubset()

No implementation.

---

## ⏱ 200–215 min — Create exceptions

* VoterNotEligibleException
* DuplicateVoterException

---

## ⏱ 215–225 min — Create interface contract test

```bash
mkdir -p tests/Unit/Contracts
```

Test:

* method signatures exist
* behavior contracts defined (mock-based)

---

## ⏱ 225–230 min — Run full unit contract suite

```bash
php artisan test tests/Unit/Contracts
```

Expected:

* still GREEN (no behavior change yet)

---

# 🧭 A.4 — Final Validation & Freeze Checkpoint (230–240 min)

## ⏱ 230–235 min — Full test suite run

```bash
php artisan test
```

Ensure:

* no regression
* no new failures introduced

---

## ⏱ 235–238 min — Code review sanity check

Verify:

* no changes in:

  * ElectionMembership logic
  * DB schema
  * controllers
  * services

---

## ⏱ 238–240 min — Commit Phase A checkpoint

```bash
git add .
git commit -m "Phase A complete: contract tests + ElectionMode + policy interfaces frozen"
```

---

# 🧠 Phase A Exit Criteria (Non-Negotiable)

Phase A is ONLY complete if:

* ✅ Baseline snapshot exists
* ✅ Contract tests reflect CURRENT behavior (not future rules)
* ✅ ElectionMode enum implemented
* ✅ Policy interface defined (no implementation yet)
* ✅ No production logic changed
* ✅ Full test suite green

---

# ⚠️ Final Advisory

Do NOT proceed to Phase B unless:

* Contract tests are stable
* You can explain every failure in Phase A in one sentence
* Baseline snapshot can be reproduced in CI

---

If you want next step, I can convert **Phase B into the same execution runbook (with policy wiring + controller migration order)** or generate a **CI pipeline that enforces strangler safety automatically**.
