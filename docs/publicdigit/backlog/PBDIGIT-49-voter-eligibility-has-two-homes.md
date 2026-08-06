# PBDIGIT-49 — Voter eligibility has two homes, and `PBDIGIT-35` named the wrong one

**Type:** Defect (single source of truth) + a correction · **Epic:** `PBDIGIT-EPIC-02` Membership · **Created:** 2026-08-06
**Found by:** diagnosing `PBDIGIT-47` on the live election `namaste 2026`

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | Unknown and unquantified. **Any eligibility check reading the empty table treats real voters as ineligible** |

---

## The evidence

For the live election `namaste 2026`, three sources were counted at the same moment:

| Source | Voters |
|---|---|
| lifecycle projection (`stateMachine.votersCount`) | **3** |
| **`election_memberships`** for this election | **3** ✅ agrees |
| `voters` table, this **election** | **0** |
| `voters` table, this **organisation** | **0** |

**So for this real election, voter eligibility lives in `election_memberships`. The `voters` table is empty.**

## ⚠️ This corrects `PBDIGIT-35`

`PBDIGIT-35` states that the authoritative home for voter status is *"the organisation- and election-scoped `voters` table"*, and reasons from that to conclude the retired `User` flags should be replaced by it.

**That was an inference from schema shape, not an observation of data.** The `voters` table has the right columns (`organisation_id`, `election_id`, `status`, `has_voted`) — **and this election does not use it.**

> **`PBDIGIT-35`'s finding stands: the retired `is_voter` / `can_vote` columns exist nowhere and code still reads them. Its conclusion about where eligibility *should* move does not — because it named one of two homes without checking which is populated.**

**The lesson is the same one this session has hit repeatedly:** a schema that *looks* authoritative is not evidence that it *is*. `PBDIGIT-35`'s acceptance criteria must not be implemented until this is settled.

## What must be established

1. **Which table is authoritative** — `election_memberships` or `voters`?
2. **What is `voters` for, then?** Is it dead, a different concept (e.g. imported voter registers), or a newer model not yet adopted?
3. **Which consumers read which?** `VoterEligibilityService`, `EligibilityEvaluator`, `EligibilitySnapshot`, `getActiveElection()`'s `voterSlugs` check, and the projection all touch eligibility. **`PBDIGIT-EPIC-02` MB-5 already records this concern and it is still open.**
4. **Does anything write `voters`?** If a code path populates it while others read `election_memberships`, eligibility depends on which path ran.

## Acceptance criteria

* [ ] The authoritative home is named and recorded; the other is removed or given a distinct, documented purpose.
* [ ] `PBDIGIT-35` is amended to reference the outcome rather than its own assumption.
* [ ] Every eligibility consumer inventoried and pointed at one source.
* [ ] A test that fails if a voter is eligible according to one store and not the other.

---

**Traceability:** observed 2026-08-06 on `namaste-2026-74d3721c` · `election_memberships` (3) vs `voters` (0) · `stateMachine.votersCount` = 3 · **`PBDIGIT-35` (corrected here)** · `PBDIGIT-EPIC-02` MB-5 · `PBDIGIT-47` · `PBDIGIT-48` (same pattern)
