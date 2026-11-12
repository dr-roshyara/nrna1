# Proposed amendment to the C1 / C2 implementation specification (§4 ledger clock)

| | |
|---|---|
| **Status** | **PROPOSAL for ARB acceptance.** It amends the **mechanism** of the accepted specification (D-162); **the C1 / C2 contract (shapes, refusals, `closedThrough` meaning) is unchanged.** Written by Claude, not independent |
| **Subject** | `docs/publicdigit/reviews/2026-11-10-membership-cr01-c1-c2-implementation-specification.md` §4 and §11 K-2 (SHA-256 at the time of writing `5ea99458cb4e34a089f435b5487e0e99fdce2f90387c09872f9a26a69c336b2e`). **The specification itself is not edited** |
| **Why** | **(1) PO-6 / PO-8:** the ratified D-73 participant contract requires refusal on clock regression and **no adjusted timestamp**. D-93 records Membership's `max(...)` as a known non-conformance. **(2) PO-7:** one **database** clock for all writers of a stream; the spec uses `appClock`. **(3) Correction D-1** (M-TDD-01 rev 3 §5.2): due scheduled resignations must be recorded before `closedThrough` passes their date (D-204, D-205) |

## 1. Current text (accepted)

- **Append:** `recordedAt = max(appClock, last_recorded_at + 1µs, closed_through + 1µs)` (§4 step 1).
- **`closedThrough` read:** `FOR UPDATE NOWAIT`; if free, `closed_through = max(closed_through, appClock)`; if busy, the stored value (§4 step 2).
- **"One clock source for writers and readers"** (K-2).

## 2. Proposed text

| Step | Proposed |
|---|---|
| **Append** | lock the organisation's clock row `FOR UPDATE` (lock order unchanged: clock lock, then the membership's version compare-and-set, per the correction note). Read **`t = clock_timestamp()`** (database clock). **If `t ≤ closed_through` or `t < last_recorded_at`: refuse; the transaction rolls back and is retried.** Otherwise `recordedAt = t`; update `last_recorded_at`; insert the fact; write the C1 outbox row; commit. **No timestamp is ever adjusted** |
| **`closedThrough` step** | lock `FOR UPDATE NOWAIT`. **If busy:** return the stored `closed_through`. **If free:** read `t = clock_timestamp()`.<br>(a) **D-1:** for every scheduled resignation whose effective date has started (organisation-local, D-5(a)) by `t`, **record its End now** (same transaction, `recordedAt = t`); **if any fails, roll back and return the stored `closed_through`, unchanged.**<br>(b) Otherwise set `closed_through = max(closed_through, t)`, commit, return it.<br>**Under the lock and with (a) done, no append can still take `recordedAt ≤ closed_through`** |
| **Clock** | **one database clock (`clock_timestamp()` of the organisation's database) for every writer and the `closedThrough` step.** No application clock |

## 3. What does not change

- **C1 / C2 shapes, fields, refusals** (`BOUNDARY_NOT_CLOSED`, `SOURCE_NOT_LEDGER`, cross-tenant).
- **The meaning of `closedThrough`:** "every fact with `recordedAt ≤ closedThrough` is committed and visible, and none can still be committed later" (CR-01 :28).
- **READ COMMITTED; A3** (facts read only after `closedThrough` is fixed, in new statements); **per-organisation `closedThrough` (K-4); K-5.**

## 4. Consequences

| | |
|---|---|
| **clock regression** | appends are refused and retried until the clock passes the stored times; **the anomaly is flagged** (operations) |
| **equal timestamps** | two appends with the same `t` are allowed if `t ≥ last_recorded_at`; **`seq` orders them** (RA: total recording order) |
| **D-1 failure** | `closedThrough` stays; C2 refuses later τ; **consumers refuse** (fail closed) until the execution succeeds |
| **availability** | one stuck scheduled execution blocks that organisation's C2 answers. Accepted as fail-closed under D-73; **monitoring required** [TU] |

## 5. Proof obligations (NOT EXECUTED)

| # | Property |
|---|---|
| LC-1 | **no stored `recordedAt` differs from its `clock_timestamp()` reading** (no adjustment) |
| LC-2 | clock regression → refusal and retry; never a time ≤ `closed_through` |
| LC-3 | A1 … A3 re-run on the amended mechanism (two appends and a reader interleaved; a reported `closedThrough` never contradicted) |
| LC-4 | **D-1:** `closedThrough` never passes the start of a date with an unexecuted due declaration; a failed execution leaves it unchanged |
| LC-5 | one database clock: no application clock reaches `recordedAt` or `closed_through` |

## 6. Decision needed

**ARB acceptance of this amendment (mechanism only),** as an addition to D-162. **No business rule changes.** On acceptance, **PO-8's Membership part is resolved at design level**, still subject to LC-1 … LC-5.
