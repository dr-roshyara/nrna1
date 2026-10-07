# Review — How a Member becomes an ElectionMembership (voter) of an election

**Date:** 2026-10-08 · **Mode:** read-only analysis (no code changed, no DB written, no test run) · **Role:** Senior DDD Architect
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable)
**Question (PO):** *"how members can be integrated as ElectionMembership of an election?"*
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Related:** `2026-10-07-membership-mode-switch-blocked-review.md` · `PBDIGIT-257` · `ADR_20261004_1549` (Voter Admission Policy & Voting Entitlement)

> **Evidence strength.** Every statement below comes from **code reading** at the cited lines. Nothing here was runtime-verified in this session. "Dormant" means *no caller found by `git grep`*, not *proven unreachable*.

---

## 1. Short answer

A Member becomes a voter **only by an election officer pulling them in**, through one of three admin-initiated paths. The election must have the snapshot `voter_source_strategy = 'full_membership'` (`MembershipRegistry`). **Nothing in the Membership context pushes members into elections:** approving, ending, expiring or marking a member overdue does not create an `ElectionMembership`.

Once a member is assigned, the `ElectionMembership{role: voter, status: active}` row **is** the entitlement (Model B, `PBDIGIT-68` D-ENT-1). The member's standing is **not re-checked at vote time**. Only `ElectionMembership` state and the election's admission policy are checked (`EloquentVotingEntitlementQuery.php:59-102`).

The design intent is coherent. **In practice the path is unreachable and internally inconsistent today** (§4).

## 2. Strategic DDD — ownership

| Question | Answer |
|---|---|
| Who owns *member standing* (status, fees, membership type and voting rights, expiry)? | **Membership context** (`members`, `membership_types`, fees) |
| Who owns *voter entitlement for one election*? | **Elections context**: the `ElectionMembership` aggregate plus the election's immutable `voter_source_strategy` snapshot |
| Who decides *eligibility to be registered*? | **Elections context**: `VoterEligibilityPolicy` → `FullMembershipPolicy` (`AppServiceProvider.php:141`) |
| Is there a Published-Language interaction? | **No.** Elections reads the Membership tables **directly with raw SQL joins** (`members → organisation_users → users`, `membership_types`). There is **no `MembershipStanding` port or published query**, and no event. |
| Does ownership change? | No. Recorded below as a **strategic observation, not a decision** (§5, O-1). |

Identity link: `members.organisation_user_id → organisation_users.id → organisation_users.user_id → users.id`. `ElectionMembership` keys on `user_id`, and the Member is never referenced. This is correct for anonymity and for keeping the context boundary clean.

## 3. The current integration paths (all pull, all admin-initiated)

| # | Path | Eligibility check used | Writes |
|---|---|---|---|
| P1 | **Single assign:** `ElectionVoterController::store` (`:233`) → `AssignVoterHandler` | `VoterEligibilityService::isEligibleVoter` (`:249`), then `policy->isEligible` again in the handler → **`FullMembershipPolicy::decideForContext`** | `ElectionMembership` create or restore, `role=voter, status=active` (`EloquentVoterRepository.php:44`) |
| P2 | **Bulk assign:** `bulkStore` (`:275`) → `BulkAssignVotersHandler` | `policy->qualifyingSubset` → **SQL** `EloquentVoterEligibilityQueryService.php:104-125` | `bulkInsert`, `role=voter, status=active` (`EloquentVoterRepository.php:73`); cache keys flushed |
| P3 | **CSV import (full membership):** `VoterImportService` | per row `isEligibleVoter` (`:181`), i.e. the **P1 predicate** | `ElectionMembership::bulkAssignVoters(...)` (`:134`), **a method that does not exist** → crash |
| (dropdown) | `index` (`:57`) → `unassignedEligibleQuery` (`VoterEligibilityService.php:53-90`) | **SQL**, the same predicate as P2 | none (read) |

All paths take the mode from `VoterSourceStrategy::fromElection($election)`, never from the organisation's mutable setting. **This is correct.**

## 4. Findings

### I-1 — Two different "eligible member" predicates (single path vs. bulk/dropdown)

| Condition | P1 / P3 (`FullMembershipPolicy`, `:55-62` plus context `:174-216`) | P2 / dropdown (SQL) |
|---|---|---|
| `members.status = active` | ✅ | ✅ |
| `fees_status ∈ {paid, exempt}` | ✅ | ✅ |
| `membership_types.grants_voting_rights = true` (or no type) | ❌ **not checked** | ✅ |
| `membership_expires_at` null or in the future | ❌ **not checked** | ✅ |
| member not soft-deleted | ✅ | ✅ |

**Consequence:** a member who is **expired**, or whose type **grants no voting rights**, is hidden from the dropdown and refused by bulk assignment, but is **accepted by a direct single assign or by the import validation**. The domain policy, which is the authoritative decision, is the **weaker** of the two.
**Classification:** Tactical architecture / Engineering. **Already owned:** `PBDIGIT-49` (eligibility has two homes) and the B correction already proposed to `PBDIGIT-96` / H-7. **No new ID.**

### I-2 — The Full Membership CSV import crashes

`VoterImportService.php:134` calls the undefined `ElectionMembership::bulkAssignVoters`. Its validation also uses the weaker P1 predicate (I-1).
**Owned:** `PBDIGIT-202` (crash) and `PBDIGIT-203` (import integrity). **No new ID.** When 202 is fixed, it should route through `BulkAssignVotersHandler` rather than re-creating a third write path. This is a recommendation for that story and is not decided here.

### I-3 — `Member::endMembership()` contradicts Model B (dormant)

`app/Models/Member.php:172-192` sets every **active** `ElectionMembership` of the member's user to `status = 'removed'` with a raw mass `update`. It does this regardless of:
- the election's `voter_source_strategy` (it would also remove **election-only** voters);
- the election's lifecycle state (it would also hit running or closed elections);
- whether the voter has already voted.

It also bypasses the repository, the domain events and the voter cache flush. The only scope is the ambient tenant scope (`ElectionMembership` uses `BelongsToTenant`), so in console context it would match nothing (cf. `PBDIGIT-211`).
**`git grep "endMembership("` finds no caller**, so it is dormant today.
**Classification:** Tactical architecture. It conflicts with `PBDIGIT-68` ("membership is an admission prerequisite, NOT continuously evaluated") and with the immutable snapshot. **Not a live defect**, so there is **no new ID** under the frozen-baseline rule. **Recommended B note on `PBDIGIT-68`:** "`Member::endMembership` encodes continuous evaluation; it must not be wired up without an ARB decision on whether ending a membership revokes election entitlement and, if so, in which lifecycle states."

### I-4 — The whole path is unreachable in practice today (stacked blockers)

For a real organisation to get a single eligible member into a full-membership election, all of the following must hold. **None does today:**

1. **The organisation must reach Full Membership mode.** The switch returns 500 (`PBDIGIT-257`). After that fix, the participation-lock guard still refuses any organisation with a locked election (review 2026-10-07 F-2, an **ARB decision**). The election snapshots its strategy at creation, so **only elections created after the switch** use `MembershipRegistry`.
2. **A member must have `fees_status` paid or exempt.** Fee payment cannot be recorded (`PBDIGIT-209`), and annual fee generation and overdue marking do nothing in scheduled context (`PBDIGIT-211`).
3. **The membership type must grant voting rights.** `grants_voting_rights` is read by five consumers and written by none (`IERVP-2`). Only members **with no type** pass the SQL predicate.
4. **Membership management needs authorization coverage.** There is no `MemberPolicy` (`IERVP-3`).

`PBDIGIT-68` G-7 already records "Full Membership mode unreachable". This review **adds `PBDIGIT-257` and F-2 as further causes**. No new ID.

### I-5 — Admission applies to both modes (consistent)

Vote-time `isAdmitted` (`EloquentVotingEntitlementQuery.php:84-102`, `PBDIGIT-213` C3) reads the election's `VoterAdmissionPolicy` **independently of the voter source strategy**. So an admitted-and-entitled member in a full-membership election goes through the same admission gate as an imported voter. **This is consistent with ADR_20261004_1549.**

What the ADR leaves open (its **G8**) is whether *membership standing itself* counts as admission evidence, or remains a registration-time prerequisite only. The code today implements the latter. **ARB / PO decision; not decided here.**

### I-6 — `User::isEligibleVoter` is a third, mode-blind home (known)

`app/Models/User.php:209` consults only Member records and takes no strategy. **Known as `IERVP-6`** and listed under `PBDIGIT-49`. No new ID.

## 5. Observations for governance (not decisions)

- **O-1 (Strategic):** Elections reads Membership's tables directly (raw joins in two classes plus one context builder). A **Membership-published "voting standing" query (port)** would give the predicate exactly **one home**, owned by the context that owns the facts. That would resolve I-1 and I-6 structurally. **This is a candidate ARB topic tied to `PBDIGIT-49`, not a new story.**
- **O-2 (Business):** the platform has **no "auto-enrol all eligible members" operation**. Bulk assignment from the filtered list is the closest equivalent. Whether one is wanted (and whether new members join a *running* election) is a **PO question**. It is not an obligation shown to be missing, so it gets no ID.

## 6. Classification summary

| Finding | Class | Owner | New ID? |
|---|---|---|---|
| I-1 predicate split | Tactical / Engineering | `PBDIGIT-49`, `96` (H-7) | No |
| I-2 import crash | Engineering defect | `PBDIGIT-202`, `203` | No |
| I-3 `endMembership` | Tactical (dormant) | B note → `PBDIGIT-68` | No |
| I-4 unreachable | Governance + defects | `PBDIGIT-68` G-7, `257`, F-2 (ARB), `209`, `211`, IERVP-2/3 | No |
| I-5 admission | Consistent | ADR_20261004_1549 G8 (open) | No |
| I-6 `User::isEligibleVoter` | Tactical | `PBDIGIT-49` / IERVP-6 | No |

**No new PBDIGIT ID is created.** Every obligation found is already owned (frozen-baseline rule, PO 2026-10-01).

## 7. Recommended order (advisory)

1. **ARB:** decide the participation-lock guard (F-2), the owner and shape of the single eligibility predicate (O-1 / `PBDIGIT-49`), and G8 (I-5).
2. **PO-authorise** `PBDIGIT-257` (crash), then `202` (import, through the handler), then `209` and the `grants_voting_rights` write path, so that a member can actually *qualify*.
3. Only then is an end-to-end full-membership election testable: a member is approved → fees are paid → the member is assigned → the member votes.

**Traceability:** `ElectionVoterController.php:57, :95, :233, :249, :261, :275, :292` · `VoterEligibilityService.php:31-90` · `EloquentVoterEligibilityQueryService.php:47-61, :104-125, :174-216` · `FullMembershipPolicy.php:55-62` · `AssignVoterHandler.php` · `BulkAssignVotersHandler.php` · `EloquentVoterRepository.php:44, :73` · `VoterImportService.php:134, :181` · `Member.php:172-192` · `User.php:209` · `EloquentVotingEntitlementQuery.php:59-102` · `AppServiceProvider.php:141`.

---

## 8. Correction (2026-10-08, same session — additive; text above left unchanged)

Canonical discovery for the Membership Domain Discovery plan (`docs/plans/20261008-0008-membership-domain-discovery-plan.md`, Phase A) found adopted rules that this review missed:

- **`D-ENT-2`** (PO, 2026-08-12, ADOPTED — `2026-08-12-session-2-final-governance-closure.md:46`) refines F1. Canonical form in `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` (catalogue PROPOSED, `EM-OPEN-017`; the rows themselves ADOPTED):
  - **EM-FM-003:** in Full Membership mode, *removal of Organisation Membership automatically suspends* the ElectionMembership;
  - **EM-FM-004:** this suspension is distinct from Chief suspension;
  - **EM-FM-006:** membership is an admission prerequisite, and it is *continuously relevant to whether the entitlement is exercisable*.
  - All `EM-FM-*` rows are **ADOPTED · DEFERRED**: not in force for the current phase (EM-SEQ-001/002).
- **EM-EO-004:** in Election-Only mode, removal of Organisation Membership does **not** suspend the ElectionMembership.
- The open Full Membership questions already have a register: **FM-1…FM-15** (`2026-08-12-election-only-first-governance-baseline.md:81-95`; EM-OPEN-014/015/016).

**Corrections to this review:**
1. **§1 "standing not re-checked at vote time"** is a correct description of the **code**. It is **not** the complete adopted rule: under EM-FM-006, membership affects *exercisability* in Full Membership mode. Code ≠ the deferred adopted rule. That gap is FM-11 (*"nothing subscribes"* to `MembershipSuspended`/`Terminated`/`Restored`).
2. **I-3** said `Member::endMembership()` "contradicts Model B". **Restated:** its *direction* (organisation-membership end affects the election) is consistent with EM-FM-003. Its *effect* conflicts with three adopted rules:
   - it sets `status='removed'` rather than an automatic suspension distinct from Chief suspension (EM-FM-003/004);
   - it ignores the election's mode, so it would also act on Election-Only voters (EM-EO-004);
   - it is not the "removal" act that FM-5 / `W-7` left open.
   It remains dormant. The proposed note on `PBDIGIT-68` should cite EM-FM-003/004, EM-EO-004 and FM-5.
3. **I-4** duplicates **FM-15** (reachability), which is already registered. **I-5 / G8** relates to EM-FM-006 and FM-14.
4. My statement that the standing-vs-entitlement question was "decided by F1 alone" (plan §3 C-1) was **too coarse**. The correct input is **F1 as refined by D-ENT-2** (the existence/exercisability split).
