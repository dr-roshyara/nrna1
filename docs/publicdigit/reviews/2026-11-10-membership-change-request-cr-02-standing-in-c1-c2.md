# Membership Change Request CR-02: standing in C1/C2

| | |
|---|---|
| **Status** | ✅ **ACCEPTED: ruling by the Principal as Human ARB Chief (D-94), 2026-11-10; recorded as D-161.** Appended to the ratified documents **by addition only** |
| **Changes** | `2026-10-08-membership-architecture-ratified.md` §8 (Organisation line, C1/C2), by addition |
| **Route** | "Further changes require an explicit Architecture Decision / Change Request" (ratification act :480), as for CR-01 |
| **Driver** | `docs/publicdigit/architecture/reviews/2026-11-10-membership-c1-c2-contract-conformance-review.md` §3, §10 |

## 1. Business need

- **Coverage** (CR-01) says **where a person still belongs**: the membership period has not ended.
- **It does not say whether they are in good standing.** Lapsed (term expired without renewal) and suspended people are inside coverage (ratified §92, §102, §103).
- Release 1's forum write right and newsletter audience must apply to **members in good standing only** (D-161).
- Without this change, the forum and newsletter could not tell them apart, because neither may read Membership directly.

## 2. Additions (meaning of CR-01 otherwise unchanged)

**C1 `MembershipCoverageResolved`, two added fields:**

| Field | Meaning (ratified predicates) |
|---|---|
| `inForce` | ordered `[from, to)` intervals where some period is `InForce` (= `InLifetime ∧ TermCovered`; ratified §101), i.e. where `IsMember` holds (§104) |
| `suspended` | ordered `[from, to)` intervals where some period is `Suspended` (§103) |

- **Full state, never a delta**, as for `coverage`.
- **Published when coverage, `inForce` or `suspended` changes** (extends CR-01's "only when coverage changes").
- `schemaVersion` = 2.

**C2 `CoverageKnownAt`:** each item also returns `inForce` and `suspended` **as known at τ**. Same refusals; same `closedThrough`.

## 3. Unchanged

- **Membership returns facts only.** It decides no forum or newsletter right.
- **Belonging stays on `coverage`** (CR-01; Organisation B-2).
- The aggregate, invariants and correction algebra are unchanged.
- **Consumers remain Organisation (C1, C2).** Communication and Deliberation receive standing **through Organisation**, never from Membership directly.

## 4. Ruling

Accepted, 2026-11-10 (D-161).
