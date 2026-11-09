# Membership Change Request CR-01: Organisation as consumer, and "as known at" queries

| | |
|---|---|
| **Status** | ✅ **ACCEPTED (revision 2) — ruling by the Principal as human ARB Chief under D-25, 2026-11-09** (as reported in the Principal DDD review and accepted by the Principal). Recorded as D-73. C0–C4 appended to the ratified documents by addition only |
| **Changes, on acceptance** | `2026-10-08-membership-architecture-ratified.md` §8 "Context contracts" and `2026-10-08-membership-architecture-ratification-act.md` §11 "Ratified Context Boundaries", **by addition only** |
| **Route** | "Further changes require an explicit Architecture Decision / Change Request" (`…ratification-act.md:480`) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Drivers** | D-3, D-25, D-27, D-42; contracts R-3, R-14, R-4 (model document `2026-11-08-strategic-context-map-and-governance-tactical-model.md` §12.2–§12.4, §21) |

## 1. Business need

1. **A member's belonging counts only while their membership covers it** (D-27). When a ward member's membership ends, their *member* belonging to the ward stops counting for the ward's electorate (R-5), forums (D-56) and co-signatures (D-51); if the End is later annulled, it counts again. Organisation needs Membership's **coverage, worked out by Membership**.
2. **Qualification for an office** ("a member for at least two years"; R-14) needs Membership **facts**; Governance applies the rule. The ratified Governance contract today runs only the other way (AuthorityCheck).
3. **An electorate fixed at a cut-off** uses only what was recorded by then (D-42), which needs Membership's ratified KnownAt to be **queryable**.

## 2. Time parameters (one definition for C2, C3, C4)

| Parameter | Meaning |
|---|---|
| **`asOf`** | The **business date** for which membership is evaluated (effective time) |
| **`τ`** | The **knowledge boundary**: only Membership facts with `recordedAt ≤ τ` (inclusive) are considered |

Every answer is the ratified definition **`KnownAt(φ, asOf, τ)` = φ at `asOf` over `Res(F_τ)`, with `F_τ = {f : recordedAt(f) ≤ τ}`** (`2026-10-08-membership-architecture-final.md:82, 98`). `τ` omitted means "now" (the ratified `TrueAt`). **`asOf` and `τ` are never interchangeable:** "was X a member on 1 December, as known on 3 December" is `asOf` = 1 December, `τ` = 3 December.

## 3. `closedThrough` (the guarantee)

- **Meaning:** every Membership fact with `recordedAt ≤ closedThrough` is **committed and visible**, and **no fact with `recordedAt ≤ closedThrough` can still be committed later**. It is a statement about **committed knowledge**, not about event publication.
- **Event publication has no completeness meaning.** Events are ordered per membership only; a consumer can never infer from events that it has "everything up to τ". Completeness is obtained only through the queries C2 to C4.
- **When it cannot be met:** a query with `τ > closedThrough` is **refused** with `BOUNDARY_NOT_CLOSED` and the current `closedThrough`. **Never a partial answer.** The caller retries later.
- **Dependency:** the guarantee requires Membership to assign `recordedAt` so that ratified property P3 holds (§8).

## 4. Proposed additions

**C0. Boundary entry (act §11):** "**Organisation** owns organisational structure and person-to-unit belonging. It consumes Membership coverage and **never owns membership truth**."

**C1. Event `MembershipCoverageResolved`**

| Field | Meaning |
|---|---|
| `eventId` | Unique per publication |
| `organisationId`, `membershipId` | **Stream identity** (one stream per Membership aggregate) |
| `personRef` | The holder (constant for a membership, INV-1) |
| `seq` | The Membership aggregate version that produced this coverage. **Strictly increasing per membership**; gaps allowed (published only when coverage changes) |
| `coverage` | **The complete coverage as Membership now resolves it** (INV-COV): an ordered list of `[from, to)` intervals, `to` open for current. **Full state, never a delta** |
| `recordedAt` | Record time of the fact at `seq` |
| `schemaVersion` | Contract version |

- **Consumer rules:** keep the highest `seq` per stream. Lower `seq` → stale, ignored. Equal `seq`, same content → duplicate, ignored. **Equal `seq`, different content → integrity error, not applied, raised.** Because each event is full state, arriving out of order needs no reordering.
- **Corrections and annulments** produce a new event with a higher `seq` and the new full coverage. **Organisation never interprets Admit, End, Annul, Void or Correct**, and never derives coverage itself.
- **Delivery:** at least once; consumers are idempotent. **Projection rebuild** from C2 at `τ = closedThrough` (each answer carries its `seq`). Membership type segments are not included until an Organisation rule needs them.

**C2. Query `CoverageKnownAt(organisationId, membershipIds[], τ)`** → for each membership: `seq` as at `τ` and the coverage over `Res(F_τ)`; plus `closedThrough`. Refused per §3. No `asOf`: the consumer evaluates its business date against the intervals.

**C3. Query `MembershipFactsAsAt(organisationId, personRef, asOf, τ, predicates[])`** over the ratified predicates (`IsMember`, `CurrentType`, `Suspended`, the tenure predicates) → each predicate's value **or INDETERMINATE**, plus `closedThrough`. **Membership returns facts only; Governance decides qualification under its rules** (corrects the earlier R-14 design, which returned QUALIFIED).

**C4. Election predicate queries** (ratified §8 Election line) accept **`τ`** in addition to `asOf`, with §2's meaning. Election-Only mode needs none (unchanged).

## 5. Unchanged

The Membership aggregate, the correction algebra, policy switches and every invariant (INV-1 … INV-14, INV-COV); **no entitlement or qualification logic in Membership**; no shared persistence; Finance, Identity and Audit contracts.

## 6. Context relationships before and after

| Relationship | Before | After |
|---|---|---|
| Membership → Election | Predicates and events, `(MembershipId, asOf)` (ratified) | + `τ` (C4) |
| Governance → Membership | AuthorityCheck port (ratified) | Unchanged |
| Membership → Governance | — | **C3** (facts only) |
| Membership → Organisation | — | **C1, C2** |
| Organisation in §11 | Absent | **C0** |

Membership consumes nothing from Organisation (one-way). Governance ⇄ Membership carries different facts in each direction (authority evidence; predicate values): watched coupling, not shared ownership.

## 7. Scenario: one person, three knowledge boundaries

Electorate cut-off **T = 1 December, end of day**. Membership End **recorded 5 December, effective 28 November**. End **annulled 15 December**.

| Question | `asOf` | `τ` | Answer |
|---|---|---|---|
| Election fixes the electorate | 1 Dec | T | **Member** (the End was not yet known) |
| Organisation: does the ward belonging count, for the same snapshot (C2) | 1 Dec | T | **Counts** |
| Asked on 10 December | 1 Dec | 10 Dec | Not a member (End known, not yet annulled) |
| Asked today | 1 Dec | now | **Member** again (End annulled) |

All three contexts use the same `τ` for a given decision and store it with the decision, so **the basis of the 1 December electorate stays explainable**, while today's answer reflects the annulment (G-1, D-26, D-42). Organisation recalculates belonging from 5 and from 15 December onwards (D-27) without rewriting earlier decisions.

## 8. Dependency and remaining points

- **Membership's code takes `recordedAt` from the application clock before commit** (`app/Contexts/Membership/Infrastructure/Organisational/PostgresMemberRepository.php:219`). That can break P3 just after `τ`, so `closedThrough` (§3) cannot be guaranteed until it is fixed. Restoring a ratified property needs no Change Request; **it needs its own backlog item** (not created: the current review instruction excludes tickets; the Principal decides).
- Batched C2 for large electorates: a technical concern.

## 9. Ruling

**Accepted, 2026-11-09 (D-73).** Mandatory technical requirements carried into tactical design (not proven by acceptance): one knowledge boundary `τ` selected and propagated across a multi-context operation; the `closedThrough` guarantee proved by transaction ordering, not by moving timestamp generation; reliable ordering, duplicates, corrections and rebuilds for the coverage event; exact list of ratified predicates exposed by C3 (membership-tenure predicates only, never office tenure) and of Election queries extended by C4.
