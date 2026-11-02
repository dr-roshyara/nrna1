# K7 decision brief: amend PBDIGIT-30 B1 (PublicDigit a real organisation), or keep it

**Date:** 2026-11-02 · **Author:** Claude (DDD architect / requirements engineer; authority: generated)
**Status:** BRIEF FOR A PO DECISION. It decides nothing. B1, Decision C and BR-12 are unchanged by this file.
**Outcome, 2026-11-02:** the PO chose **K′**. It is recorded in:
- PBDIGIT-30 B1 (confirmation, not amended);
- BR-12 (reworded);
- Decision C §2a;
- register O-1.
**Register item:** O-1 (`2026-11-02-organisation-governance-strategic-design-status-assessment.md` §4).
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).

Labels: **[F]** fact verified in the repository · **[A]** assumption · **[R]** analysis or recommendation.

---

## 1. Facts

**F-1, B1 as approved [F].**
- Approved by the PO on 2026-08-06: `docs/publicdigit/backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md` §B1.
- PublicDigit is a **bootstrap organisation**. It "is **never** a user's working organisation".
- B1.1: on registration the user belongs to PublicDigit; "that membership is **infrastructure**, not a working context".
- B1.3: PublicDigit is ignored when real organisations are counted (0 → Create or Join, 1 → enter, 2+ → ask).
- Invariant: every authenticated request executes inside exactly one Working Organisation.

**F-2, PO direction to amend [F].**
- On 2026-11-01 the PO directed that PublicDigit is a **real organisation** and the default organisation, with its own Users, Members, Workers and Guests (BR-12; Decision C §2a).
- That direction is recorded but **is not the amendment**. BR-12 states: "Requires formal amendment of approved PBDIGIT-30 B1 (K7); until then B1 stands" (`docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md:34`).

**F-3, candidate wording "B1′" [F].** This is in Decision C §2a (`2026-11-01-organisation-dc-default-organisation-relationship-decision-record.md:90`):
- Registration creates a User affiliation with PublicDigit.
- At sign-in, a PublicDigit affiliation counts as a real organisation only if it is more than the User role.

**F-4, today's routing [F].** `app/Services/DashboardResolver.php` identifies the platform organisation by `type = 'platform'` and `is_default` (`:904-906`). It excludes that organisation from the count **regardless of role** (`:925-933`, also `:756-762`). This matches B1 as written.

**F-5, today's registration [F].** `app/Http/Controllers/Auth/RegisterController.php:60-64` gives **every** registrant the role `member` in the platform organisation. Decision C records this as finding F-C-3 (`member` overloaded) and gap G-6.

**F-6, implementation waiting on B1 [F].**
- PBDIGIT-32 (Working Organisation routing) is "UNBLOCKED — awaiting authorisation".
- Its objective is literally "ignore the bootstrap organisation, count the real ones" (`docs/publicdigit/backlog/PBDIGIT-32-implement-working-organisation-routing.md:18`).

**F-7, data placement [F].**
- DC-R9 (PROPOSED) places the User affiliation in **PublicDigit's organisation database**.
- The candidate tenancy direction C places "the platform service relationship" in the **minimal platform store** (Decision D §3, `2026-11-02-organisation-xd15-…-decision-proposal.md:60`).

**F-8, organisation-scoped access [F].** INV-ORG-SCOPE-1 (principle approved; ARB pending) makes a person's data accessible within the organisation the person is affiliated with.

## 2. The options

| | **K: keep B1** | **B1′: amend as worded** | **K′: keep B1, and give PublicDigit's real organisational life its own ordinary organisation** [R] |
|---|---|---|---|
| Meaning | PublicDigit stays bootstrap only; it has no members or workers on the platform | one PublicDigit organisation is both the registration default and a real organisation | the bootstrap entity stays infrastructure; the operator's members, workers and committees live in a separate, ordinary organisation |
| BR-12 | withdrawn | stands | **intent** kept (PublicDigit has a real organisational life); **wording** amended ("the default organisation" is no longer the same organisation as the real one) |
| Decision C | DC-R5 examples and OQ-C-6 for PublicDigit dropped | stands | DC-R1 / R2 stand for the bootstrap entity; OQ-C-6 moves to the operator organisation |
| Working Organisation (B1.3) | unchanged; code already matches (F-4) | counting becomes **role-aware** (User-only excluded) | unchanged; the operator organisation is counted like any other |
| Registration | unchanged rule; G-6 still to fix | creates a User affiliation in a **real** organisation | unchanged rule; G-6 still to fix |
| Affiliation (INV-ORG-SCOPE-1) | registrants are not organisation data of any real organisation | **every registrant becomes an affiliated person of a real organisation**; its administrators get organisation-scoped access to all of them unless a further rule excludes User-only persons [R] | registrants are not organisation data of the operator organisation |
| Account lifecycle (XD-3c, DC-R7, DC-R8) | unaffected | unaffected, provided DC-R8 (User affiliation is not a login gate) is confirmed | unaffected |
| Tenancy direction C (F-7) | bootstrap relationship is a platform-store fact | the User affiliation of every registrant sits in an **organisation** database; sign-in routing must consult it for every person | bootstrap relationship is a platform-store fact |
| PBDIGIT-32 | proceeds as written | acceptance criteria must be rewritten before authorisation | proceeds as written |

## 3. Implementation and migration consequences [R]

**B1′:**
- Existing data first. Every registrant holds `member` in the platform organisation (F-5). Under B1′ wording, `member` is "more than User", so **every account would be routed into PublicDigit** until those rows are remapped to User. A data migration must precede the routing change.
- `DashboardResolver` exclusion becomes role-aware.
- PBDIGIT-32 is re-scoped.
- A visibility rule for User-only persons is needed (see the Affiliation row in §2).

**K′:**
- Create the operator organisation.
- **[A] Unverified:** whether production holds real (non-registration) member or worker data in the platform organisation that would have to move. This needs a read-only check, the same authorisation class as V-2 (PBDIGIT-309).

**K:** no migration beyond fixing G-6.

**All options:**
- G-6 (role `member` at registration) and F-C-4 / F-C-5 remain gaps.
- None of them is authorised by this brief.

## 4. Recommendation [R]

**K′.**

**Why:**
- It meets the business intent of BR-12: PublicDigit has real members, workers and committees.
- It keeps B1's deterministic routing.
- It avoids two consequences that B1′ creates:
  - making every registrant organisation data of a real organisation (a data-minimisation risk under INV-ORG-SCOPE-1);
  - a data migration that is a precondition for correct routing.
- PBDIGIT-32 can proceed unchanged.

**Risks of K′:**
- Two PublicDigit-named entities, which needs clear naming in the UI.
- It still amends BR-12's wording.
- It depends on [A] above (existing data in the platform organisation).

**If the PO prefers B1′:**
- adopt it **with** an added rule that User-only affiliations are not organisation data visible to PublicDigit's organisation administrators;
- order the `member` → User remap before any routing change.

**K alone** is coherent but contradicts the PO's recorded business intent (F-2), so it is not recommended.

## 5. After the decision

Record the outcome:
- in PBDIGIT-30 (amendment section, or a note that B1 stands);
- in BR-12;
- in Decision C §2a;
- in register item O-1.

Re-check PBDIGIT-32's acceptance criteria. No code change follows from the decision alone.

## 6. The decision requested

> **Which applies: K′ (keep B1; PublicDigit's real organisational life in its own ordinary organisation), B1′ with the User-only visibility rule, or K (keep B1, withdraw BR-12)?**

---

## 7. Decision recorded (PO, 2026-11-02): K′

**Rationale (PO):**
- K′ separates **platform participation** (a person has an account and uses PublicDigit) from **organisational membership** (a person belongs to an organisation and takes part in its life).
- Creating a PublicDigit account does not, by itself, establish membership in the operator organisation.
- Account, affiliation and membership remain distinct concepts with their own lifecycle and ownership rules.
- B1′ was not selected because it would have needed a visibility exception to compensate for coupling registration to a real organisation.

**What it does not authorise:**
- schema changes;
- production data changes;
- creating the operator organisation;
- the Membership–Election slices S2–S4.

### 7a. Target behaviour and affected code paths (recorded; nothing implemented)

| Concern | Target under K′ | Today | Code path |
|---|---|---|---|
| Registration (password) | User affiliation in the bootstrap organisation; never Membership (DC-R1, DC-R2) | role `member` (gap G-6, independent of K7) | `app/Http/Controllers/Auth/RegisterController.php:60-64` |
| Registration (Google) | the same | no affiliation (F-C-5) | Decision C §4, E-5 |
| Identifying the bootstrap organisation | `type = 'platform'` and `is_default` | the same | `app/Models/Organisation.php:193-198`; `app/Services/DashboardResolver.php:904-906` |
| Sign-in counting (B1.3) | exclude the bootstrap organisation only; count the operator organisation like any other | the same | `DashboardResolver.php:756-762, 925-933` |
| **Constraint** | the operator organisation must **not** carry `type = 'platform'` or `is_default`, or routing would exclude it | — | — |
| Fallback | no silent substitution (INV-ORG-SCOPE-1) | `?? Organisation::first()` | `app/Services/OrganisationUserImportService.php:39` (PBDIGIT-309) |

**PBDIGIT-32:**
- Its scope stays valid: "ignore the bootstrap organisation, count the real ones" is unchanged.
- Its "one definition of real organisation" (acceptance criterion, `:54`) must keep the constraint above.
- No rewrite is needed.

### 7b. Verification prerequisite: read-only production check

**Question.** Does the production platform organisation hold real organisational data that would have to be preserved or moved to the operator organisation? That means anything beyond the `member` rows that registration writes automatically.

**Authorisation.**
- PO and Ops.
- A read-only connection, for each environment checked.
- **Not run by Claude without that authorisation.** No writes.

**Evidence to capture.**
- Counts only; no personal data.
- Environment name, timestamp, and who ran it.

```sql
-- Q0: identify the bootstrap organisation exactly as the code does (slug differs by seed, PBDIGIT-309)
SELECT id, slug, type, is_default FROM organisations WHERE type = 'platform';

-- Q1: role distribution in it. Anything other than 'member' indicates real use.
SELECT role, COUNT(*) FROM user_organisation_roles
WHERE organisation_id = :pid GROUP BY role;

-- Q2: 'member' rows not created together with the account (possible real assignments)
SELECT COUNT(*) FROM user_organisation_roles r JOIN users u ON u.id = r.user_id
WHERE r.organisation_id = :pid AND r.role = 'member'
  AND ABS(EXTRACT(EPOCH FROM (r.created_at - u.created_at))) > 60;

-- Q3: every organisation-scoped table, then a row count per table for :pid
SELECT table_name FROM information_schema.columns
WHERE column_name = 'organisation_id' AND table_schema = 'public';
-- for each table t:  SELECT COUNT(*) FROM t WHERE organisation_id = :pid;
-- (of interest: members, membership_*, committee_*, elections, voters, organisation_users, election_memberships)
```

**Reading the results.**
- **No data to preserve:** Q1 shows only `member`, Q2 = 0, and Q3 shows no rows outside `user_organisation_roles` / `users`.
- **Otherwise:** a preservation and migration decision is needed before the operator organisation is created.
- **Do not infer the result from the target design.**
